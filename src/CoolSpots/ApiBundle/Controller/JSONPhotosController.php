<?php

namespace CoolSpots\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use CoolSpots\SiteBundle\Entity\CsPicsLikes;
use CoolSpots\ApiBundle\Library\SessionData;

class JSONPhotosController extends Controller {
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
	
	public function likesAction(){
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
			
			$Pic = $em->getRepository('SiteBundle:CsPics')->find($params->id);
			if(!$Pic) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Pic not found'), 'data' => null);
				throw new \Exception();
			}

			$page = isset($params->page) ? $params->page : 1;
			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');

			try {
				$Likes = $em->getRepository('SiteBundle:CsPicsLikes')->findBy(array('idPic' => $Pic), array('dateAdded' => 'DESC'), $this->container->getParameter('max_items_per_page'), $offset);
			} catch(\Exception $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to perform query: ' . $e->getMessage()), 'data' => null);
				throw $e;
			}
			
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Likes, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));			
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function likesAddAction(){
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
			
			$Pic = $em->getRepository('SiteBundle:CsPics')->find($params->id);
			if(!$Pic) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Pic not found'), 'data' => null);
				throw new \Exception();
			}
			
			// search user by username
			if(isset($params->from)) {
				$User = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->from));
			} else {
				$userid = $this->getUserSession();
				$User = $em->getRepository('SiteBundle:CsUsers')->find($userid);
			}
			
			if(!$User) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found!'), 'data' => null);
				throw new \Exception();
			}
			
			$Like = $em->getRepository('SiteBundle:CsPicsLikes')->findOneBy(array('deleted' => 'N', 'idUser' => $User, 'idPic' => $Pic));
			if($Like) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Already liked this pic'), 'data' => null);
				throw new \Exception();
			} else {
				try {
					$Like = new CsPicsLikes();
					$Like->setDateAdded(new \DateTime());
					$Like->setIdPic($Pic);
					$Like->setIdUser($User);
					$Like->setDeleted('N');
					$em->persist($Like);
					
					$Pic->setLikesCount($Pic->getLikesCount() + 1);
					$em->persist($Pic);
					
					$em->flush();
				} catch(\Exception $e) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to execute action:' . $e->getMessage()), 'data' => null);
					throw $e;
				}
			}
			$em->getConnection()->commit();
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Like, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));			
		} catch(\Exception $e) {
			$em->getConnection()->rollback();
			$em->close();
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function likesRemoveAction(){
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
			
			$Pic = $em->getRepository('SiteBundle:CsPics')->find($params->id);
			if(!$Pic) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Pic not found'), 'data' => null);
				throw new \Exception();
			}
			
			// search user by username
			if(isset($params->from)) {
				$User = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->from));
			} else {
				$userid = $this->getUserSession();
				$User = $em->getRepository('SiteBundle:CsUsers')->find($userid);
			}
			
			if(!$User) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found!'), 'data' => null);
				throw new \Exception();
			}
			
			$Like = $em->getRepository('SiteBundle:CsPicsLikes')->findOneBy(array('deleted' => 'N', 'idUser' => $User, 'idPic' => $Pic));
			if(!$Like) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'You never liked this pic before'), 'data' => null);
				throw new \Exception();
			} else {
				try {
					$Like->setDeleted('Y');
					$em->persist($Like);
					
					$Pic->setLikesCount($Pic->getLikesCount() - 1);
					$em->persist($Pic);
					
					$em->flush();
				} catch(\Exception $e) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to execute action:' . $e->getMessage()), 'data' => null);
					throw $e;
				}
			}
			$em->getConnection()->commit();
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => null));			
		} catch(\Exception $e) {
			$em->getConnection()->rollback();
			$em->close();
			return $this->jsonResponse($this->jsonData);
		}
	}		
}