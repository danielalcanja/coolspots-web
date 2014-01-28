<?php

namespace CoolSpots\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use CoolSpots\ApiBundle\Library\SessionData;
use Doctrine\ORM\NoResultException;
use CoolSpots\SiteBundle\Entity\CsInbox;

class JSONInboxController extends Controller {
	private $jsonData = array();
	
	private function jsonResponse($arrResponse) {
		$json = json_encode($arrResponse);
		$response = new Response($json);
		$response->headers->set('content-type', 'application/json');
		return $response;
	}
	
	private function getUserSession() {
		$session_file = sprintf("%s/sess_%s", $this->container->getParameter('session_dir'), $_COOKIE['PHPSESSID']);
		if(!file_exists($session_file)) {
			$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User\'s session not found'), 'data' => null);
			throw new \Exception();
		}

		$content = file_get_contents($session_file);
		$SessionData = SessionData::unserialize($content);
		if(!is_array($SessionData)) {
			$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid user\'s session data'), 'data' => null);
			throw new \Exception();
		}
		if(!isset($SessionData['_sf2_attributes']['userid'])) {
			$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not authenticated'), 'data' => null);
			throw new \Exception();
		}
		return $SessionData['_sf2_attributes']['userid'];
	}
	
	public function indexAction(){
		$request = $this->getRequest();
		$content = $request->getContent();
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		$em = $this->getDoctrine()->getManager();
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			// search user by username
			if(isset($params->username)) {
				$User = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->username));
			} else {
				$userid = $this->getUserSession();
				$User = $em->getRepository('SiteBundle:CsUsers')->find($userid);
			}
			
			if(!$User) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found'), 'data' => null);
				throw new \Exception();
			}
			
			$page = isset($params->page) ? $params->page : 1;
			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
			
			$Messages = $em->getRepository('SiteBundle:CsInbox')->findBy(array('idUserTo' => $User, 'deleted' => 'N'), array('dateAdded' => 'DESC'), $this->container->getParameter('max_items_per_page'), $offset);
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Messages, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function sentAction(){
		$request = $this->getRequest();
		$content = $request->getContent();
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		$em = $this->getDoctrine()->getManager();
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			// search user by username
			if(isset($params->username)) {
				$User = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->username));
			} else {
				$userid = $this->getUserSession();
				$User = $em->getRepository('SiteBundle:CsUsers')->find($userid);
			}
			
			if(!$User) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found'), 'data' => null);
				throw new \Exception();
			}
			
			$page = isset($params->page) ? $params->page : 1;
			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
			
			$Messages = $em->getRepository('SiteBundle:CsInbox')->findBy(array('idUserFrom' => $User, 'deleted' => 'N'), array('dateAdded' => 'DESC'), $this->container->getParameter('max_items_per_page'), $offset);
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Messages, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function addAction(){
		$request = $this->getRequest();
		$content = $request->getContent();
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		$em = $this->getDoctrine()->getManager();
		$em->getConnection()->beginTransaction();
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			if(!isset($params->to) || !isset($params->title) || !isset($params->message)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information.'), 'data' => null);
				throw new \Exception();
			}
			
			// search user by username
			if(isset($params->from)) {
				$UserFrom = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->from));
			} else {
				$userid = $this->getUserSession();
				$UserFrom = $em->getRepository('SiteBundle:CsUsers')->find($userid);
			}
			
			if(!$UserFrom) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found'), 'data' => null);
				throw new \Exception();
			}
			
			$UserTo = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->to));
			if(!$UserTo) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found'), 'data' => null);
				throw new \Exception();
			}
			
			try {
				$Message = new CsInbox();
				$Message->setIdUserFrom($UserFrom);
				$Message->setIdUserTo($UserTo);
				$Message->setDateAdded(new \DateTime());
				$Message->setTitle($params->title);
				$Message->setMessage($params->message);
				$Message->setDeleted('N');
				$em->persist($Message);
				$em->flush();
			} catch(\Exception $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to send message: ' . $e->getMessage()), 'data' => null);
				throw new \Exception();
			}
			
			$em->getConnection()->commit();
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Message, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));
		} catch(\Exception $e) {
			$em->getConnection()->rollback();
			$em->close();
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function removeAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		$em = $this->getDoctrine()->getManager();
		$em->getConnection()->beginTransaction();
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			if(!isset($params->messages)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information.'), 'data' => null);
				throw new \Exception();
			}
			
			// search user by username
			if(isset($params->username)) {
				$User = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->username));
			} else {
				$userid = $this->getUserSession();
				$User = $em->getRepository('SiteBundle:CsUsers')->find($userid);
			}
			
			if(!$User) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found'), 'data' => null);
				throw new \Exception();
			}
			
			$total = 0;
			try {
				$Messages = $em->getRepository('SiteBundle:CsInbox')->createQueryBuilder('c')
					->where('c.id in (:id)')
					->andWhere('c.idUserFrom = :idUserFrom')
					->andWhere('c.deleted = :deleted')
					->setParameter('id', $params->messages)
					->setParameter('idUserFrom', $User->getId())
					->setParameter('deleted', 'N')
					->getQuery()
					->getResult();
				
				foreach($Messages as $m) {
					$m->setDeleted('Y');
					$em->persist($m);
					$total++;
				}
				
				$em->flush();
				
			} catch(NoResultException $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'No messages found'), 'data' => null);
				throw $e;
			} catch(\Exception $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Error performing query: ' . $e->getMessage()), 'data' => null);
				throw $e;
			}
			
			$em->getConnection()->commit();
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => array('deleted' => $total)));
			
		} catch(\Exception $e) {
			$em->getConnection()->rollback();
			$em->close();
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function readAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		$em = $this->getDoctrine()->getManager();
		$em->getConnection()->beginTransaction();
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			if(!isset($params->id)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information.'), 'data' => null);
				throw new \Exception();
			}
			
			// search user by username
			if(isset($params->username)) {
				$User = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->username));
			} else {
				$userid = $this->getUserSession();
				$User = $em->getRepository('SiteBundle:CsUsers')->find($userid);
			}
			
			if(!$User) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found'), 'data' => null);
				throw new \Exception();
			}
			
			$Message = $em->getRepository('SiteBundle:CsInbox')->findOneBy(array('idUserTo' => $User->getId(), 'deleted' => 'N', 'id' => $params->id));
			if(!$Message) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Message not found'), 'data' => null);
				throw new \Exception();
			}
			
			try {
				if(!$Message->getDateRead()) {
					$Message->setDateRead(new \DateTime());
					$em->persist($Message);
					$em->flush();
				}
			} catch(\Exception $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found'), 'data' => null);
				throw new \Exception();
			}
			$em->getConnection()->commit();
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Message, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function sentReadAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		$em = $this->getDoctrine()->getManager();
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			if(!isset($params->id)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information.'), 'data' => null);
				throw new \Exception();
			}
			
			// search user by username
			if(isset($params->username)) {
				$User = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->username));
			} else {
				$userid = $this->getUserSession();
				$User = $em->getRepository('SiteBundle:CsUsers')->find($userid);
			}
			
			if(!$User) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found'), 'data' => null);
				throw new \Exception();
			}
			
			$Message = $em->getRepository('SiteBundle:CsInbox')->findOneBy(array('idUserFrom' => $User->getId(), 'deleted' => 'N', 'id' => $params->id));
			if(!$Message) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Message not found'), 'data' => null);
				throw new \Exception();
			}
						
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Message, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
}