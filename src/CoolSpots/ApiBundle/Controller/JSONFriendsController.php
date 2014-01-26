<?php

namespace CoolSpots\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use CoolSpots\SiteBundle\Entity\CsFriends;
use CoolSpots\SiteBundle\Entity\CsFriendsRequest;
use CoolSpots\ApiBundle\Library\SessionData;

class JSONFriendsController extends Controller {
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
		$userid = null;
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}

			if(isset($params->username)) {
				$User = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->username));
				if(!$User) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found!'), 'data' => null);
					throw new \Exception();
				}
				$userid = $User->getId();
			} else {
				$userid = $this->getUserSession();
			}
			
			$page = isset($params->page) ? $params->page : 1;
			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
			
			$Friends = $em->getRepository('SiteBundle:VwFriends')->findBy(array('idUser' => $userid), array('fullName' => 'ASC'), $this->container->getParameter('max_items_per_page'), $offset);
				
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Friends, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));			
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function requestAction() {
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
			
			if(!isset($params->to) || !isset($params->message)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information.'), 'data' => null);
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
				
			// search friend by username
			$Friend = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->to));
			if(!$Friend) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Friend not found!'), 'data' => null);
				throw new \Exception();
			}
			
			if($User->getId() == $Friend->getId()) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Self friend request is not allowed'), 'data' => null);
				throw new \Exception();
			}
			
			// friendship already exists?
			$Friendship = $em->getRepository('SiteBundle:CsFriends')->findOneBy(array('idUser' => $User->getId(), 'idUserFriend' => $Friend->getId()));
			if($Friendship) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Users are already friends.'), 'data' => null);
				throw new \Exception();
			} 
			
			// request for friendship already exists?
			$FriendshipRequest = $em->getRepository('SiteBundle:CsFriendsRequest')->findOneBy(array('idUser' => $User->getId(), 'idUserFriend' => $Friend->getId(), 'status' => 'P'));
			$FriendshipRequestRev = $em->getRepository('SiteBundle:CsFriendsRequest')->findOneBy(array('idUser' => $Friend->getId(), 'idUserFriend' => $User->getId(), 'status' => 'P'));
			if($FriendshipRequest || $FriendshipRequestRev) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'There is already a pending friend request.'), 'data' => null);
				throw new \Exception();
			} else {
				try {
					$FriendshipRequest = new CsFriendsRequest();
					$FriendshipRequest->setIdUser($User);
					$FriendshipRequest->setIdUserFriend($Friend);
					$FriendshipRequest->setDateAdded(new \DateTime());
					$FriendshipRequest->setMessage($params->message);
					$FriendshipRequest->setStatus('P');
					$em->persist($FriendshipRequest);
					$em->flush();
				} catch(\Exception $e) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to create a friendship request: ' . $e->getMessage()), 'data' => null);
					throw $e;
				}
			}
			$em->getConnection()->commit();
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($FriendshipRequest, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));
		} catch(\Exception $e) {
			$em->getConnection()->rollback();
			$em->close();
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function requestsStatusAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		
		$em = $this->getDoctrine()->getManager();
		$em->getConnection()->beginTransaction();
		
		try {
			if(!isset($params->from) || !isset($params->status)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information.'), 'data' => null);
				throw new \Exception();
			}
			
			// search user by username
			if(isset($params->to)) {
				$Friend = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->to));
			} else {
				$userid = $this->getUserSession();
				$Friend = $em->getRepository('SiteBundle:CsUsers')->find($userid);
			}

			if(!$Friend) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Friend not found!'), 'data' => null);
				throw new \Exception();
			}
			
			// search friend by username
			$User = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->from));
			if(!$User) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found!'), 'data' => null);
				throw new \Exception();
			}
			
			// friendship exists?
			$Friendship = $em->getRepository('SiteBundle:CsFriends')->findOneBy(array('idUser' => $User->getId(), 'idUserFriend' => $Friend->getId()));
			if($Friendship) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Users are already friends.'), 'data' => null);
				throw new \Exception();
			} 
			
			// request for friendship exists?
			$FriendshipRequest = $em->getRepository('SiteBundle:CsFriendsRequest')->findOneBy(array('idUser' => $User->getId(), 'idUserFriend' => $Friend->getId(), 'status' => 'P'));
			if(!$FriendshipRequest) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'There is no pending friend request.'), 'data' => null);
				throw new \Exception();
			} else {
				try {
					$FriendshipRequest->setStatus($params->status);
					$em->persist($FriendshipRequest);
					
					$Friendship = new CsFriends();
					$Friendship->setIdUser($User);
					$Friendship->setIdUserFriend($Friend);
					$Friendship->setDateAdded(new \DateTime());
					$em->persist($Friendship);
					
					$FriendshipRev = new CsFriends();
					$FriendshipRev->setIdUser($Friend);
					$FriendshipRev->setIdUserFriend($User);
					$FriendshipRev->setDateAdded(new \DateTime());
					$em->persist($FriendshipRev);
					
					$em->flush();
				} catch(\Exception $e) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to change friend\'s request status: ' . $e->getMessage()), 'data' => null);
					throw $e;
				}
			}
			$em->getConnection()->commit();
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($FriendshipRev, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));
		} catch(\Exception $e) {
			$em->getConnection()->rollback();
			$em->close();
			return $this->jsonResponse($this->jsonData);
		}
	}
}