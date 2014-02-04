<?php

namespace CoolSpots\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use CoolSpots\SiteBundle\Entity\CsLocationFavorites;
use CoolSpots\ApiBundle\Library\SessionData;

class JSONFavoritesController extends Controller {
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

			if(isset($params->username)) {
				$User = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->username));
				if(!$User) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found!'), 'data' => null);
					throw new \Exception();
				}
				$Favorites = $em->getRepository('SiteBundle:CsLocationFavorites')->findBy(array('idUser' => $User->getId()));
			} else {
				$userid = $this->getUserSession();
				$page = isset($params->page) ? $params->page : 1;
				$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
				$Favorites = $em->getRepository('SiteBundle:VwLocationFavorites')->findBy(array('idUser' => $userid), array(), $this->container->getParameter('max_items_per_page'), $offset);
			}
			if(!$Favorites) {
				$this->jsonData = array('meta' => array('status' => 'OK', 'message' => 'Success!'), 'data' => null);
				throw new \Exception();
			}
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Favorites, 'json');
			
			$data = json_decode($json);
			
			for($i = 0; $i < count($data);$i++) {
				$rsPhotos = $em->getRepository('SiteBundle:CsPics')->findBy(array('idLocation' => $data[$i]->id), array('createdTime' => 'DESC'), $this->container->getParameter('max_items_per_page'));
				$arrPhotos = array();
				foreach($rsPhotos as $p) {
					array_push($arrPhotos, array(
						'caption' =>  $p->getCaption(),
						'lowResolution' => $p->getLowResolution(),
						'thumbnail' => $p->getThumbnail(),
						'standardResolution' => $p->getStandardResolution()
					));
				}
				$data[$i]->lastPhotos = $arrPhotos;
			}
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => $data));
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
		try{
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			if(!$params->id_location) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information. '), 'data' => null);
				throw new \Exception();
			}


			if(isset($params->username)) {
				$User = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->username));
			} else {
				$userid = $this->getUserSession();
				$User = $em->getRepository('SiteBundle:CsUsers')->find($userid);
			}

			if(!$User) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found!'), 'data' => null);
				throw new \Exception();
			}


			// check if the location already exists in user's favorites
			$Favorite = $em->getRepository('SiteBundle:CsLocationFavorites')->findOneBy(array(
				'idUser' => $User->getId(),
				'idLocation' => $params->id_location,
				'deleted' => 'N'
			));
			
			if($Favorite) {
				 $this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Location already exists in user\'s favorites'), 'data' => null);
				 throw new \Exception();
			} else {
				$Location = $em->getRepository('SiteBundle:CsLocation')->find($params->id_location);
				if(!$Location) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Location does not exists!'), 'data' => null);
					throw new \Exception();
				}
				
				try {
					$Favorite = new CsLocationFavorites();
					$Favorite->setDeleted('N');
					$Favorite->setIdLocation($Location);
					$Favorite->setIdUser($User);
					$Favorite->setDateAdded(new \DateTime());
					$em->persist($Favorite);
					$em->flush();
				} catch(\Exceptioin $e) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to insert into favorites table: ' . $e->getMessage()), 'data' => null);
					throw $e;
				}
			}
			$em->getConnection()->commit();
			
			if(!isset($params->username)) {
				$userFavorites = array();
				$session = $request->getSession();
				$Favorites = $em->getRepository('SiteBundle:CsLocationFavorites')->findBy(array('idUser' => $User->getId()));
				foreach($Favorites as $fav) {
					array_push($userFavorites, $fav->getIdLocation()->getId());
				}
				$session->set('favorites', $userFavorites);
			}
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Favorite, 'json');
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
			
			if(!$params->id_location) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information. '), 'data' => null);
				throw new \Exception();
			}


			if(isset($params->username)) {
				$User = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $params->username));
			} else {
				$userid = $this->getUserSession();
				$User = $em->getRepository('SiteBundle:CsUsers')->find($userid);
			}

			if(!$User) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found!'), 'data' => null);
				throw new \Exception();
			}

			// check if the location already exists in user's favorites
			$Favorite = $em->getRepository('SiteBundle:CsLocationFavorites')->findOneBy(array(
				'idUser' => $User->getId(),
				'idLocation' => $params->id_location,
				'deleted' => 'N'
			));

			if(!$Favorite) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Location does not exists in user\'s favorites'), 'data' => null);
				throw new \Exception();
			} else {
				try {
					$Favorite->setDeleted('Y');
					$em->persist($Favorite);
					$em->flush();
				} catch(\Exception $e) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to update favorite data: ' . $e->getMessage()), 'data' => null);
					throw $e;
				}
			}
			$em->getConnection()->commit();
			if(!isset($params->username)) {
				$userFavorites = array();
				$session = $request->getSession();
				$Favorites = $em->getRepository('SiteBundle:CsLocationFavorites')->findBy(array('idUser' => $User->getId()));
				foreach($Favorites as $fav) {
					array_push($userFavorites, $fav->getIdLocation()->getId());
				}
				$session->set('favorites', $userFavorites);
			}
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => null));
		} catch(\Exception $e) {
			$em->getConnection()->rollback();
			$em->close();
			return $this->jsonResponse($this->jsonData);
		}
	}	
}