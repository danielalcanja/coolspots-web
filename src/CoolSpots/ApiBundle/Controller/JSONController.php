<?php

namespace CoolSpots\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Doctrine\ORM\NoResultException;
use CoolSpots\SiteBundle\Library\CSUtil;
use CoolSpots\SiteBundle\Entity\CsGeoCountry;
use CoolSpots\SiteBundle\Entity\CsGeoState;
use CoolSpots\SiteBundle\Entity\CsGeoCity;
use CoolSpots\SiteBundle\Entity\CsCategory;
use CoolSpots\SiteBundle\Entity\CsLocation;
use CoolSpots\SiteBundle\Entity\CsUsers;
use CoolSpots\SiteBundle\Entity\CsPics;
use CoolSpots\SiteBundle\Entity\CsTags;

class JSONController extends Controller
{
	private $last_max_timestamp = 0;

	
	private function jsonResponse($arrResponse) {
		$json = json_encode($arrResponse);
		$response = new Response($json);
		$response->headers->set('content-type', 'application/json');
		return $response;
	}
	
    public function locationAction()
    {
		$request = $this->getRequest();
		$content = $request->getContent();
		
		$params = json_decode(utf8_decode($content));
		if($params === null) return $this->jsonResponse(array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null));
		
		$page = isset($params->page) ? $params->page : 1;
		$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
		
		$repository = $this->getDoctrine()->getRepository('SiteBundle:VwLocation');
		$rs = $repository->createQueryBuilder('c')
				->where('c.coverPic is not null');
		// check for the id parameter
		if(isset($params->id)) $rs = $rs->andWhere('c.id = :id')->setParameter('id', $params->id);
		
		// check for the city parameter
		if(isset($params->city)) $rs = $rs->andWhere('c.idCity = :city')->setParameter('city', $params->city);
		
		// check for the state parameter
		if(isset($params->state)) $rs = $rs->andWhere('c.idState = :state')->setParameter('state', $params->state);
		
		// check for the country parameter
		if(isset($params->country)) $rs = $rs->andWhere('c.idCountry = :country')->setParameter('country', $params->country);
		
		// check for the category parameter
		if(isset($params->category)) $rs = $rs->andWhere('c.idCategory = :category')->setParameter('category', $params->category);
		
		$rs = $rs->andWhere('c.enabled = :enabled')
				->setParameter('enabled', 'Y')
				->andWhere('c.deleted = :deleted')
				->setParameter('deleted', 'N')
				->orderBy('c.dateUpdated', 'desc')
				->setFirstResult($offset)
				->setMaxResults($this->container->getParameter('max_items_per_page'))
				->getQuery()
				->getResult();
		
		$arrLocations = array();
		foreach($rs as $item) {
			$rpPhotos = $this->getDoctrine()->getRepository('SiteBundle:CsPics');
			$rsPhotos = $rpPhotos->createQueryBuilder('c')
					->where('c.idLocation = :idLocation')
					->orderBy('c.dateAdded', 'desc')
					->setParameter('idLocation', $item->getId())
					->setMaxResults(5)
					->getQuery()
					->getResult();
			$arrPhotos = array();
			foreach($rsPhotos as $p) {
				array_push($arrPhotos, array(
					'low_resolution' => $p->getLowResolution(),
					'thumbnail' => $p->getThumbnail(),
					'standard_resolution' => $p->getStandardResolution()
				));
			}
			
			$arrLocations[] = array(
				'address' => $item->getAddress(),
				'checkinsCount' => $item->getCheckinsCount(),
				'categoryName' => $item->getCategoryName(),
				'cityName' => $item->getCityName(),
				'countryName' => $item->getCountryName(),
				'coverPic' => $item->getCoverPic(),
				'dateAdded' => $item->getDateAdded()->getTimeStamp(),
				'dateUpdated' => $item->getDateUpdated()->getTimeStamp(),
				'deleted' => $item->getDeleted(),
				'enabled' => $item->getEnabled(),
				'id' =>  $item->getId(),
				'idCategory' => $item->getIdCategory(),
				'idCity' => $item->getIdCity(),
				'idCountry' => $item->getIdCountry(),
				'idFoursquare' => $item->getIdFoursquare(),
				'idInstagram' => $item->getIdInstagram(),
				'idState' => $item->getIdState(),
				'lastMinId' => $item->getLastMinId(),
				'lastPhotos' => $arrPhotos,
				'minTimestamp' => $item->getMinTimestamp(),
				'name' => $item->getName(),
				'nextMaxId' => $item->getNextMaxId(),
				'phone' => $item->getPhone(),
				'postalCode' => $item->getPostalCode(),
				'slug' => $item->getSlug(),
				'stateName' => $item->getStateName()
			);
		}
		$arrJson = array('meta' => array('status' => 'OK', 'message' => 'Success!'), 'data' => $arrLocations);
		$json = json_encode($arrJson);
		$response = new Response($json);
		$response->headers->set('content-type', 'application/json');
		return $response;
    }
	
    public function locationInfoAction()
    {
		$request = $this->getRequest();
		$content = $request->getContent();
		
		$params = json_decode(utf8_decode($content));
		if($params === null) return $this->jsonResponse(array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null));
		
		// validate data
		if(!isset($params->id)) return $this->jsonResponse(array('meta' => array('status' => 'ERROR', 'message' => 'You must provide a location ID'), 'data' => null));
		
		$repository = $this->getDoctrine()->getRepository('SiteBundle:CsLocation');
		$rs = $repository->createQueryBuilder('c')
				->where('c.id = :id')
				->andWhere('c.enabled = :enabled')
				->andWhere('c.deleted = :deleted')
				->setParameter('id', $params->id)
				->setParameter('enabled', 'Y')
				->setParameter('deleted', 'N')
				->getQuery()
				->getSingleResult();
		
		$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
		$json = $serializer->serialize($rs, 'json');
		
		$arrJson = array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json));
		$response = new Response(json_encode($arrJson));
		$response->headers->set('content-type', 'application/json');
		
		return $response;
	}
	
	public function photosAction()
	{
		$request = $this->getRequest();
		$content = $request->getContent();
		
		$params = json_decode(utf8_decode($content));
		if($params === null) return $this->jsonResponse(array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null));
		
		// validate data
		if(!isset($params->id)) return $this->jsonResponse(array('meta' => array('status' => 'ERROR', 'message' => 'You must provide a locatoin ID'), 'data' => null));
		
		$page = isset($params->page) ? $params->page : 1;
		$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
		$rs = $this->getDoctrine()
				->getManager()
				->createQuery('SELECT p, u, l FROM SiteBundle:CsPics p
								JOIN p.idUser u
								JOIN p.idLocation l
								WHERE p.idLocation = :id
								ORDER BY p.dateAdded DESC')
				->setFirstResult($offset)
				->setMaxResults($this->container->getParameter('max_items_per_page'))
				->setParameter('id', $params->id)->getResult();
		
		
		$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
		$json = $serializer->serialize($rs, 'json');
		
		$arrJson = array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json));
		$response = new Response(json_encode($arrJson));
		
		$response->headers->set('content-type', 'application/json');
		
		return $response;
	}
	
	private function getGeo($countryName, $countryCode, $stateName, $stateAbbr, $cityName) {
		$em = $this->getDoctrine()->getManager();
		
		// check if the country is already in the database
		try {
			$Country = $this->getDoctrine()->getRepository('SiteBundle:CsGeoCountry')->createQueryBuilder('c')
					->where('LOWER(c.countryName) = :countryName')
					->andWhere('LOWER(c.countryCode) = :countryCode')
					->andWhere('c.enabled = :enabled')
					->andWhere('c.deleted = :deleted')
					->setParameter('countryName', mb_strtolower($countryName, 'UTF-8'))
					->setParameter('countryCode', mb_strtolower($countryCode, 'UTF-8'))
					->setParameter('enabled', 'Y')
					->setParameter('deleted', 'N')
					->getQuery()
					->getSingleResult();
		} catch(NoResultException $e) {
			try {
				$Country = new CsGeoCountry();
				$Country->setCountryName($countryName);
				$Country->setCountryCode($countryCode);
				$Country->setEnabled('Y');
				$Country->setDeleted('N');
				$em->persist($Country);
				$em->flush();
			} catch(Exception $e) {
				return(false);
			}
		}
		
		// check if the state is already in the database
		try {
			$State = $this->getDoctrine()->getRepository('SiteBundle:CsGeoState')->createQueryBuilder('c')
					->where('LOWER(c.stateName) = :stateName')
					->andWhere('LOWER(c.stateAbbr) = :stateAbbr')
					->andWhere('c.idCountry = :idCountry')
					->andWhere('c.enabled = :enabled')
					->andWhere('c.deleted = :deleted')
					->setParameter('stateName', mb_strtolower($stateName, 'UTF-8'))
					->setParameter('stateAbbr', mb_strtolower($stateAbbr, 'UTF-8'))
					->setParameter('idCountry', $Country->getId())
					->setParameter('enabled', 'Y')
					->setParameter('deleted', 'N')
					->getQuery()
					->getSingleResult();
		} catch(NoResultException $e) {
			try {
				$State = new CsGeoState();
				$State->setIdCountry($Country);
				$State->setStateName($stateName);
				$State->setStateAbbr($stateAbbr);
				$State->setEnabled('Y');
				$State->setDeleted('N');
				$em->persist($State);
				$em->flush();
			} catch(Exception $e) {
				return(false);
			}
		}
		
		// check if the city is already in the database
		try {
			$City = $this->getDoctrine()->getRepository('SiteBundle:CsGeoCity')->createQueryBuilder('c')
					->where('LOWER(c.cityName) = :cityName')
					->andWhere('c.idCountry = :idCountry')
					->andWhere('c.idState = :idState')
					->andWhere('c.enabled = :enabled')
					->andWhere('c.deleted = :deleted')
					->setParameter('cityName', mb_strtolower($cityName, 'UTF-8'))
					->setParameter('idCountry', $Country->getId())
					->setParameter('idState', $State->getId())
					->setParameter('enabled', 'Y')
					->setParameter('deleted', 'N')
					->getQuery()
					->getSingleResult();
		} catch(NoResultException $e) {
			try {
				$City = new CsGeoCity();
				$City->setIdCountry($Country);
				$City->setIdState($State);
				$City->setCityName($cityName);
				$City->setEnabled('Y');
				$City->setDeleted('N');
				$em->persist($City);
				$em->flush();
			} catch(Exception $e) {
				return(false);
			}
		}
		
		return(array('id_country' => $Country->getId(), 'id_state' => $State->getId(), 'id_city' => $City->getId()));
	}
	
	public function addLocationAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		
		// get entity manager
		$em = $this->getDoctrine()->getManager();
		
		$params = json_decode(utf8_decode($content));
		if($params === null) return $this->jsonResponse(array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null));
		
		if(!isset($params->id_instagram) || !isset($params->id_foursquare) || 
				!isset($params->geo) || 
				!isset($params->geo->countryName) ||
				!isset($params->geo->countryCode) || !isset($params->geo->stateName) || !isset($params->geo->stateAbbr) ||
				!isset($params->geo->cityName) || 
				!isset($params->category) || 
				!isset($params->category->id) || !isset($params->category->exid) ||
				!isset($params->category->name) 
				|| !isset($params->name)) {
			return $this->jsonResponse(array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information. '), 'data' => null));
		}
		
		// first we need the geo information
		$geo = $this->getGeo($params->geo->countryName, $params->geo->countryCode, $params->geo->stateName, $params->geo->stateAbbr, $params->geo->cityName);
		if(!$geo) {
			return $this->jsonResponse(array('meta' => array('status' => 'ERROR', 'message' => 'Unexpected error on inserting geo information'), 'data' => null));
		}
				
		// first, check if instagram id isn't already registered
		$rsLocation = $em->getRepository('SiteBundle:CsLocation')->findOneBy(array(
			'idInstagram' => $params->id_instagram,
			'idFoursquare' => $params->id_foursquare,
			'enabled' => 'Y',
			'deleted' => 'N'
		));
		
		if($rsLocation) {
			return $this->jsonResponse(array('meta' => array('status' => 'ERROR', 'message' => 'Location already exists!'), 'data' => null));
		}
		
//		$rsLocation = $this->getDoctrine()->getRepository('SiteBundle:CsLocation')->createQueryBuilder('c')
//					->where('c.idInstagram = :idInstagram')
//					->andWhere('c.idFoursquare = :idFoursquare')
//					->andWhere('c.enabled = :enabled')
//					->andWhere('c.deleted = :deleted')
//					->setParameter('idInstagram', $params->id_instagram)
//					->setParameter('idFoursquare', $params->id_foursquare)
//					->setParameter('enabled', 'Y')
//					->setParameter('deleted', 'N')
//					->getQuery()
//					->getResult();
//		if(count($rsLocation) > 0) {
//			return $this->jsonResponse(array('meta' => array('status' => 'ERROR', 'message' => 'Location already exists!'), 'data' => null));
//		}
		
		
		// get country, state and city objects
		$Country = $em->getRepository('SiteBundle:CsGeoCountry')->find($geo['id_country']);
		$State = $em->getRepository('SiteBundle:CsGeoState')->find($geo['id_state']);
		$City = $em->getRepository('SiteBundle:CsGeoCity')->find($geo['id_city']);
		$Category = null;
		
		// check if category exists
		if($params->category->id == 0) {
			$Category = new CsCategory();
			$Category->setExid($params->category->exid);
			$Category->setName($params->category->name);
			$em->persist($Category);
			$em->flush();
		} else {
			$Category = $em->getRepository('SiteBundle:CsCategory')->find($params->category->id);
		}
		
		try {
			// insert the new location
			$Location = new CsLocation();
			if(isset($params->address)) $Location->setAddress($params->address);
			$Location->setCheckinsCount(0);
			$Location->setCoverPic(null);
			$Location->setDateAdded(new \DateTime());
			$Location->setDateUpdated(null);
			$Location->setDeleted('N');
			$Location->setEnabled('Y');
			$Location->setIdCategory($Category);
			$Location->setIdCity($City);
			$Location->setIdCountry($Country);
			$Location->setIdFoursquare($params->id_foursquare);
			$Location->setIdInstagram($params->id_instagram);
			$Location->setIdState($State);
			$Location->setLastMinId(null);
			$Location->setLikesCount(0);
			$Location->setMinTimestamp(null);
			$Location->setName($params->name);
			$Location->setNextMaxId(null);
			if(isset($params->phone)) $Location->setPhone(isset($params->phone));
			if(isset($params->postal_code)) $Location->setPostalCode($params->postal_code);
			$Location->setSlug(CSUtil::slugify($params->name));
			$em->persist($Location);
			$em->flush();
		} catch(Exception $e) {
			return $this->jsonResponse(array('meta' => array('status' => 'ERROR', 'message' => 'Error inserting location: ' . $e->getMessage()), 'data' => null));
		}
		
		$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
		$json = $serializer->serialize($Location, 'json');
		
		
		// now, we must add the subscription calling the subscription URL
		$subscription_url = sprintf("http://api.coolspots.com.br/instagram/subscription/location/%d?jsonapi=%d", $Location->getIdInstagram(), md5(time()));
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$subscription_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //to suppress the curl output 
		$subscription_result = curl_exec($ch);
		curl_close($ch);
		$jsonSubscription = json_decode($subscription_result);
		
		// if something goes wrong ...
		if($jsonSubscription->meta->status == 'ERROR') {
			return $this->jsonResponse(array('meta' => array('status' => 'OK_WITH_WARNING', 'message' => 'Location created sucessful, but got error on subscription call: ' . $jsonSubscription->meta->message), 'data' => json_decode($json)));
		}
				
		// everthing is OK. Now let's try to fetch the initial photos from the new location.
		$Subscription = $em->getRepository('SiteBundle:CsSubscriptions')->find($jsonSubscription->data->id);
		if(!$Subscription) {
			return $this->jsonResponse(array('meta' => array('status' => 'OK_WITH_WARNING', 'message' => 'Location created sucessful, but got error on subscription operation: unable to find subscription id'), 'data' => json_decode($json)));
		}
		
		$APIKeys = $em->getRepository('SiteBundle:CsInstagramApi')->findBy(array('enabled' => 'Y', 'deleted' => 'N'));
		$arrKeys = array();
		foreach($APIKeys as $apikey) array_push($arrKeys, $apikey->getClientId());
		
		$client_id = $arrKeys[rand(0, count($arrKeys) - 1)];
		$url = str_replace(array('@OBJECT_ID@', '@CLIENT_ID@'), array($Subscription->getObjectId(), $client_id), $this->container->getParameter('instagram_photo_update_url'));
		// check if there is a min_timestamp
		if($Location->getMinTimestamp())
		{
			if($Location->getMinTimestamp() > 0) $url .= str_replace(array('@MIN_TIMESTAMP@'), array($Location->getMinTimestamp()), $this->container->getParameter('instagram_photo_update_extra_params'));
		}

		$this->getLocationPhotos($Location, $url);
		
		// update the location info
		if($this->last_max_timestamp > 0)
		{
			$Location->setDateUpdated(new \DateTime());
			$Location->setMinTimestamp($this->last_max_timestamp + 1);
			$em->persist($Location);
			$em->flush();
		}
		
		// update subscription status
		$Subscription->setUpdated('Y');
		$em->persist($Subscription);
		$em->flush();
		
		$arrJson = array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json));
		$response = new Response(json_encode($arrJson));
		$response->headers->set('content-type', 'application/json');
		return($response);
	}
	
	private function getLocationPhotos($Location, $url) {
		if(!$url) return false;
		
		$em = $this->getDoctrine()->getManager();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close ($ch);
		$response = json_decode($result, true);
		if(!isset($response['data'])) return false;
		
		foreach($response['data'] as $data) {
			// check if user is already registered
			$User = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $data['user']['username']));
			if(!$User) {
				$User = new CsUsers();
				$User->setAccessToken(null);
				$User->setBio(substr($data['user']['bio'],0,150));
				$User->setEmail(null);
				$User->setFullName(substr(str_replace(array("'", '"'), '', $data['user']['full_name']),0,150));
				$User->setProfilePicture($data['user']['profile_picture']);
				$User->setTokenDate(null);
				$User->setUsername($data['user']['username']);
				$em->persist($User);
				$em->flush();
			}
			
			// add the photo to database
			$Pic = new CsPics();
			$Pic->setCaption(substr(str_replace(array("'", '"'), '', $data['caption']['text']),0,150));
			$photoCreatedTime = new \DateTime();
			$photoCreatedTime->setTimestamp($data['caption']['created_time']);
			$Pic->setCreatedTime($photoCreatedTime);
			$Pic->setDateAdded(new \DateTime());
			$Pic->setIdLocation($Location);
			$Pic->setIdUser($User);
			$Pic->setLikesCount($data['likes']['count']);
			$Pic->setLowResolution($data['images']['low_resolution']['url']);
			$Pic->setStandardResolution($data['images']['standard_resolution']['url']);
			$Pic->setThumbnail($data['images']['thumbnail']['url']);
			$Pic->setType(1);
			$em->persist($Pic);
			$em->flush();
			
			// add the photo's tags
			foreach($data['tags'] as $tag) {
				$Tag = new CsTags();
				$Tag->setIdLocation($Location);
				$Tag->setIdPic($Pic);
				$Tag->setTag($tag);
				$em->persist($Tag);
				$em->flush();
			}
			
			$this->last_max_timestamp = ($data['caption']['created_time'] > $this->last_max_timestamp) ? $data['caption']['created_time'] : $this->last_max_timestamp;
		}
		
		// finish
		return(true);
	}
}
