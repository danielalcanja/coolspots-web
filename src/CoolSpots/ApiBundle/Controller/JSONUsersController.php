<?php

namespace CoolSpots\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use CoolSpots\SiteBundle\Entity\CsUsers;
use CoolSpots\ApiBundle\Library\SessionData;

class JSONUsersController extends Controller {
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
	
	public function indexAction() {
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
			
			$Users = $em->getRepository('SiteBundle:CsUsers')->findBy(array(), array('fullName' => 'ASC'), $this->container->getParameter('max_items_per_page'), $offset);
			
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Users, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function addAction() {
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
			
			if(!isset($params->access_token) || !isset($params->user) || !isset($params->user->id) || !isset($params->user->username) || !isset($params->user->full_name) || !isset($params->user->profile_picture)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information.'), 'data' => null);
				throw new \Exception();
			}
			
			// check if user already exists
			$User = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->user->username, 'deleted' => 'N'));
			if($User) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User already exists'), 'data' => null);
				throw new \Exception();
			} else {
				try {
					$User = new CsUsers();
					$User->setAccessToken(null);
					$User->setBio(null);
					$User->setEmail(null);
					$User->setFullName($params->user->full_name);
					$User->setProfilePicture($params->user->profile_picture);
					$User->setTokenDate(null);
					$User->setUsername($params->user->username);
					$em->persist($User);
					$em->flush();
				} catch(\Exception $e) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to create user: ' . $e->getMessage()), 'data' => null);
					throw $e;
				}
			}
			$em->getConnection()->commit();
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($User, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));
		} catch(\Exception $e) {
			$em->getConnection()->rollback();
			$em->close();
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function searchAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		
		$em = $this->getDoctrine()->getManager();
		
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			if(!isset($params->keyword)) {
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
			
			$page = isset($params->page) ? $params->page : 1;
			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
			
			//$Users = $em->getRepository('SiteBundle:CsUsers')->findBy(array(), array('fullName' => 'ASC'), $this->container->getParameter('max_items_per_page'), $offset);
			$Users = $em->getRepository('SiteBundle:CsUsers')->createQueryBuilder('c')
					->where('c.fullName like :keyword')->setParameter('keyword', '%' . $params->keyword . '%')
					->orderBy('c.fullName', 'ASC')
					->setFirstResult($offset)
					->setMaxResults($this->container->getParameter('max_items_per_page'))
					->getQuery()
					->getResult();
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Users, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}	
}