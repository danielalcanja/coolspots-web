<?php

namespace CoolSpots\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
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
	
	public function likesAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		$em = $this->getDoctrine()->getManager();
		$userid = null;
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
			
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => null));			
			
			
//			$page = isset($params->page) ? $params->page : 1;
//			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
//			
//			$Friends = $em->getRepository('SiteBundle:VwFriends')->findBy(array('idUser' => $userid), array('fullName' => 'ASC'), $this->container->getParameter('max_items_per_page'), $offset);
//				
//			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
//			$json = $serializer->serialize($Friends, 'json');
//			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));			
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
}