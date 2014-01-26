<?php

namespace CoolSpots\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use CoolSpots\SiteBundle\Entity\CsEvents;
use CoolSpots\ApiBundle\Library\SessionData;

class JSONEventsController extends Controller {
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
			
			$page = isset($params->page) ? $params->page : 1;
			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
			
			$where = array();
			$where['public'] = 'Y';
			
			if(isset($params->geo)) {
				if(isset($params->geo->countryName)) $where['countryName'] = $params->geo->countryName;
				if(isset($params->geo->stateName)) $where['stateName'] = $params->geo->stateName;
				if(isset($params->geo->cityName)) $where['cityName'] = $params->geo->cityName;
			}

			$Events = $em->getRepository('SiteBundle:VwEvents')->findBy($where, array('dateStart' => 'DESC'), $this->container->getParameter('max_items_per_page'), $offset);
			
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Events, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));	
			
		} catch(\Exception $e) {
			die($e->getMessage());
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function addAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		
		$em = $this->getDoctrine()->getManager();
		$em->getConnection()->beginTransaction();
		$cover_pic = null;
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			if(!isset($params->id_location) || !isset($params->name) || !isset($params->tag) || !isset($params->dateStart) || !isset($params->dateEnd)) {
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
			
			// search location by id
			$Location = $em->getRepository('SiteBundle:CsLocation')->find($params->id_location);
			if(!$Location) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Location not found'), 'data' => null);
				throw new \Exception();
			}
			
			try {
				$Event = new CsEvents();
				$Event->setIdUser($User);
				$Event->setIdLocation($Location);
				if(isset($params->pic)) {
					$event_dir = realpath($this->container->getParameter('events_pics_dir'));
					$subdir = date('Y/m/d');
					$pic_content = base64_decode($params->pic);
					if(!$pic_content) {
						$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to decode the base64 string'), 'data' => null);
						throw new \Exception();
					}
					$pic_filename = md5(time() . '_' . rand(0, time())) . '.jpg';
					if(!is_dir($event_dir . '/' . $subdir)) {
						mkdir($event_dir . '/' . $subdir, 0777, true);
					}
					
					$fp = fopen($event_dir . '/' . $subdir . '/' . $pic_filename, 'wb');
					if(!$fp) {
						$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to create image file'));
						throw new \Exception();						
					}
					fputs($fp, $pic_content);
					fclose($fp);
					
					$cover_pic = $this->container->getParameter('events_pics_url') . '/' . $subdir . '/' . $pic_filename;
					$Event->setCoverPic($cover_pic);
				}
				$Event->getDateAdded(new \DateTime());
				$dateStart = new \DateTime();
				$dateStart->setTimestamp($params->dateStart);
				$Event->setDateStart($dateStart);
				
				
				$dateEnd = new \DateTime();
				$dateEnd->setTimestamp($params->dateEnd);
				$Event->setDateEnd($dateEnd);
				
				$Event->setDeleted('N');
				if(isset($params->description)) $Event->setDescription($params->description);
				$Event->setName($params->name);
				$Event->setTag($params->tag);
				$Event->setPublic(isset($params->public) ? $params->public : 'Y');
				$em->persist($Event);
				$em->flush();
			} catch(\Exception $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to create event: ' . $e->getMessage()), 'data' => null);
				throw $e;
			}
			$em->getConnection()->commit();
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Event, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));			
		} catch(\Exception $e) {
			$em->getConnection()->rollback();
			$em->close();
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function photosAction() {
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
			
			try {
				$Event = $em->getRepository('SiteBundle:CsEvents')->find($params->id);
				if(!$Event) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Event not found!'), 'data' => null);
					throw new \Exception();
				}
			} catch(\Exception $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to perform query: ' . $e->getMessage()), 'data' => null);
				throw $e;
			}
			
			$page = isset($params->page) ? $params->page : 1;
			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
			
			try {
				$rs = $this->getDoctrine()
						->getManager()
						->createQuery('SELECT p, u, l FROM SiteBundle:VwPicsTags p
										JOIN p.idUser u
										JOIN p.idLocation l
										WHERE p.idLocation = :id
										AND p.tag = :tag
										AND p.createdTime >= :start
										AND p.createdTime <= :end
										ORDER BY p.dateAdded DESC')
						->setFirstResult($offset)
						->setMaxResults($this->container->getParameter('max_items_per_page'))
						->setParameter('id', $params->id)
						->setParameter('tag', $Event->getTag())
						->setParameter('start', $Event->getDateStart())
						->setParameter('end', $Event->getDateEnd())
						->getResult();
			} catch(\Exception $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to perform query: ' . $e->getMessage()), 'data' => null);
				throw $e;
			}

			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($rs, 'json');

			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function privateAction() {
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
			} else {
				$userid = $this->getUserSession();
				$User = $em->getRepository('SiteBundle:CsUsers')->find($userid);
			}
			
			if(!$User) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found!'), 'data' => null);
				throw new \Exception();
			}
			
			$page = isset($params->page) ? $params->page : 1;
			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
			
			$where = array();
			$where['idUser'] = $User->getId();
			
			if(isset($params->geo)) {
				if(isset($params->geo->countryName)) $where['countryName'] = $params->geo->countryName;
				if(isset($params->geo->stateName)) $where['stateName'] = $params->geo->stateName;
				if(isset($params->geo->cityName)) $where['cityName'] = $params->geo->cityName;
			}
			
			$Events = $em->getRepository('SiteBundle:VwEventsPrivate')->findBy($where, array('dateStart' => 'DESC'), $this->container->getParameter('max_items_per_page'), $offset);
			
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Events, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));	
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function privateSearchAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		$em = $this->getDoctrine()->getManager();
		try{ 
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			if(!isset($params->keyword)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information.'), 'data' => null);
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
			
			$page = isset($params->page) ? $params->page : 1;
			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
			
			$rs = $em->getRepository('SiteBundle:VwEventsPrivate')->createQueryBuilder('c')
					->where('c.name like :keyword')->setParameter('keyword', '%' . $params->keyword . '%')
					->andWhere('c.idUser = :idUser')->setParameter('idUser', $User->getId());
			
			if(isset($params->geo)) {
				if(isset($params->geo->countryName)) $rs = $rs->andWhere('c.countryName = :countryName')->setParameter('countryName', $params->geo->countryName);
				if(isset($params->geo->countryState)) $rs = $rs->andWhere('c.stateName = :stateName')->setParameter('stateName', $params->geo->stateName);
				if(isset($params->geo->countryCity)) $rs = $rs->andWhere('c.cityName = :cityName')->setParameter('cityName', $params->geo->cityName);
			}
			
			$rs = $rs->orderBy('c.dateStart', 'desc')
				->setFirstResult($offset)
				->setMaxResults($this->container->getParameter('max_items_per_page'))
				->getQuery()
				->getResult();
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($rs, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));	
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function selfAction() {
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
			} else {
				$userid = $this->getUserSession();
				$User = $em->getRepository('SiteBundle:CsUsers')->find($userid);
			}
			
			if(!$User) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'User not found!'), 'data' => null);
				throw new \Exception();
			}
			
			$page = isset($params->page) ? $params->page : 1;
			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
			
			$Events = $em->getRepository('SiteBundle:CsEvents')->findBy(array('idUser' => $User->getId(), 'deleted' => 'N'), array('dateStart' => 'DESC'), $this->container->getParameter('max_items_per_page'), $offset);
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Events, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));	
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function removeAction() {
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
			
			try {
				$Event = $em->getRepository('SiteBundle:CsEvents')->findOneBy(array('id' => $params->id, 'idUser' => $User->getId(), 'deleted' => 'N'));
				if(!$Event) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Event not found'), 'data' => null);
					throw new \Exception('Event not found!');
				}
				
				$Event->setDeleted('Y');
				$em->persist($Event);
				$em->flush();
			} catch(\Exception $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to delete event: ' . $e->getMessage()), 'data' => null);
				throw $e;
			}
			$em->getConnection()->commit();
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => null));	
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
		try{ 
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			if(!isset($params->keyword)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information.'), 'data' => null);
				throw new \Exception();
			}
			
			$page = isset($params->page) ? $params->page : 1;
			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
			
			$rs = $em->getRepository('SiteBundle:VwEvents')->createQueryBuilder('c')
					->where('c.name like :keyword')->setParameter('keyword', '%' . $params->keyword . '%');
			if(isset($params->geo)) {
				if(isset($params->geo->countryName)) $rs = $rs->andWhere('c.countryName = :countryName')->setParameter('countryName', $params->geo->countryName);
				if(isset($params->geo->countryState)) $rs = $rs->andWhere('c.stateName = :stateName')->setParameter('stateName', $params->geo->stateName);
				if(isset($params->geo->countryCity)) $rs = $rs->andWhere('c.cityName = :cityName')->setParameter('cityName', $params->geo->cityName);
			}
			
			if(isset($params->id_location)) $rs = $rs->andWhere('c.idLocation = :id_location')->setParameter('id_location', $params->id_location);
			
			$rs = $rs->orderBy('c.dateStart', 'desc')
				->setFirstResult($offset)
				->setMaxResults($this->container->getParameter('max_items_per_page'))
				->getQuery()
				->getResult();
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($rs, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));	
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function statusAction() {
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
			
			if(!isset($params->id) || !isset($params->status)) {
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
			
			try {
				$Event = $em->getRepository('SiteBundle:CsEvents')->findOneBy(array('id' => $params->id, 'idUser' => $User->getId(), 'deleted' => 'N'));
				if(!$Event) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Event not found'), 'data' => null);
					throw new \Exception('Event not found!');
				}
				$Event->setPublic(($params->status == 'P' ? 'Y' : 'N'));
				$em->persist($Event);
				$em->flush();
			} catch(\Exception $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to change event status: ' . $e->getMessage()), 'data' => null);
				throw $e;
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