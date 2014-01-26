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
use CoolSpots\SiteBundle\Entity\CsSubscriptions;
use CoolSpots\SiteBundle\Entity\CsUsers;
use CoolSpots\SiteBundle\Entity\CsPics;
use CoolSpots\SiteBundle\Entity\CsTags;
use CoolSpots\SiteBundle\Entity\CsLocationComments;
use CoolSpots\SiteBundle\Entity\CsLocationLikes;
use CoolSpots\ApiBundle\Library\SessionData;

class JSONLocationsController extends Controller {
	private $last_max_timestamp = 0;
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
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}

			$page = isset($params->page) ? $params->page : 1;
			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');

			try {
				$repository = $this->getDoctrine()->getRepository('SiteBundle:VwLocation');
				$rs = $repository->createQueryBuilder('c')
						->where('c.lastPic is not null');
				// check for the id parameter
				if(isset($params->id)) $rs = $rs->andWhere('c.id = :id')->setParameter('id', $params->id);

				if(isset($params->geo)) {
					// check for the city parameter
					if(isset($params->geo->cityName)) $rs = $rs->andWhere('c.cityName = :city')->setParameter('city', $params->geo->cityName);

					// check for the state parameter
					if(isset($params->geo->stateName)) $rs = $rs->andWhere('c.stateName = :state')->setParameter('state', $params->geo->stateName);

					// check for the country parameter
					if(isset($params->geo->countryName)) $rs = $rs->andWhere('c.countryName = :country')->setParameter('country', $params->geo->countryName);
				}

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
							->orderBy('c.createdTime', 'desc')
							->setParameter('idLocation', $item->getId())
							->setMaxResults($this->container->getParameter('max_items_per_page'))
							->getQuery()
							->getResult();
					$arrPhotos = array();
					foreach($rsPhotos as $p) {
						array_push($arrPhotos, array(
							'caption' =>  $p->getCaption(),
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
			} catch(\Exception $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to perform query: ' . $e->getMessage()), 'data' => null);
				throw $e;
			}
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success!'), 'data' => $arrLocations));
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
    }
	
    public function infoAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}

			// validate data
			if(!isset($params->id)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'You must provide a location ID'), 'data' => null);
				throw new \Exception();
			}
			
			try {
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
	
	public function photosAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}

			// validate data
			if(!isset($params->id)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information. '), 'data' => null);
				throw new \Exception();
			}

			$page = isset($params->page) ? $params->page : 1;
			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
			
			try {
				$rs = $this->getDoctrine()
						->getManager()
						->createQuery('SELECT p, u, l FROM SiteBundle:CsPics p
										JOIN p.idUser u
										JOIN p.idLocation l
										WHERE p.idLocation = :id
										ORDER BY p.dateAdded DESC')
						->setFirstResult($offset)
						->setMaxResults($this->container->getParameter('max_items_per_page'))
						->setParameter('id', $params->id)
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
	
	public function addAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		
		// get entity manager
		$em = $this->getDoctrine()->getManager();
		$em->getConnection()->beginTransaction();
		try {
			$params = json_decode($content); //$params = json_decode(utf8_decode($content));
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}

			if(!isset($params->id_instagram) || !isset($params->id_foursquare) || 
					!isset($params->geo) || 
					!isset($params->geo->countryName) ||
					!isset($params->geo->countryCode) || !isset($params->geo->stateName) ||
					!isset($params->geo->cityName) || 
					!isset($params->category) || 
					!isset($params->category->id) || !isset($params->category->exid) ||
					!isset($params->category->name) 
					|| !isset($params->name)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information. '), 'data' => null);
				throw new \Exception();
			}

			// first, check if instagram id isn't already registered
			$rsLocation = $em->getRepository('SiteBundle:CsLocation')->findOneBy(array(
				'idInstagram' => $params->id_instagram,
				'idFoursquare' => $params->id_foursquare,
				'enabled' => 'Y',
				'deleted' => 'N'
			));

			if($rsLocation) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Location already exists!'), 'data' => null);
				throw new \Exception();
			}
			
			// check if the country is already in the database
			try {
				$Country = $this->getDoctrine()->getRepository('SiteBundle:CsGeoCountry')->createQueryBuilder('c')
						->where('LOWER(c.countryName) = :countryName')
						->andWhere('LOWER(c.countryCode) = :countryCode')
						->andWhere('c.enabled = :enabled')
						->andWhere('c.deleted = :deleted')
						->setParameter('countryName', mb_strtolower($params->geo->countryName, 'UTF-8'))
						->setParameter('countryCode', mb_strtolower($params->geo->countryCode, 'UTF-8'))
						->setParameter('enabled', 'Y')
						->setParameter('deleted', 'N')
						->getQuery()
						->getSingleResult();
			} catch(NoResultException $e) {
				try {
					$Country = new CsGeoCountry();
					$Country->setCountryName($params->geo->countryName);
					$Country->setCountryCode($params->geo->countryCode);
					$Country->setEnabled('Y');
					$Country->setDeleted('N');
					$em->persist($Country);
					$em->flush();
				} catch(\Exception $e) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to insert country data: ' . $e->getMessage()), 'data' => null);
					throw $e;
				}
			} catch(\Exception $e) {
				throw $e;
			}

			// check if the state is already in the database
			try {
				$State = $this->getDoctrine()->getRepository('SiteBundle:CsGeoState')->createQueryBuilder('c')
						->where('LOWER(c.stateName) = :stateName')
						->andWhere('LOWER(c.stateAbbr) = :stateAbbr')
						->andWhere('c.idCountry = :idCountry')
						->andWhere('c.enabled = :enabled')
						->andWhere('c.deleted = :deleted')
						->setParameter('stateName', mb_strtolower($params->geo->stateName, 'UTF-8'))
						->setParameter('stateAbbr', mb_strtolower($params->geo->stateName, 'UTF-8'))
						->setParameter('idCountry', $Country->getId())
						->setParameter('enabled', 'Y')
						->setParameter('deleted', 'N')
						->getQuery()
						->getSingleResult();
			} catch(NoResultException $e) {
				try {
					$State = new CsGeoState();
					$State->setIdCountry($Country);
					$State->setStateName($params->geo->stateName);
					$State->setStateAbbr($params->geo->stateName);
					$State->setEnabled('Y');
					$State->setDeleted('N');
					$em->persist($State);
					$em->flush();
				} catch(\Exception $e) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to insert state data: ' . $e->getMessage()), 'data' => null);
					throw $e;
				}
			} catch(\Exception $e) {
				throw $e;
			}

			// check if the city is already in the database
			try {
				$City = $this->getDoctrine()->getRepository('SiteBundle:CsGeoCity')->createQueryBuilder('c')
						->where('LOWER(c.cityName) = :cityName')
						->andWhere('c.idCountry = :idCountry')
						->andWhere('c.idState = :idState')
						->andWhere('c.enabled = :enabled')
						->andWhere('c.deleted = :deleted')
						->setParameter('cityName', mb_strtolower($params->geo->cityName, 'UTF-8'))
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
					$City->setCityName($params->geo->cityName);
					$City->setEnabled('Y');
					$City->setDeleted('N');
					$em->persist($City);
					$em->flush();
				} catch(Exception $e) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to insert city data: ' . $e->getMessage()), 'data' => null);
					throw $e;
				}
			} catch(\Exception $e) {
				throw $e;
			}
			
			
			$Category = null;

			// check if category exists
			if($params->category->id == 0) {
				try {
					$Category = new CsCategory();
					$Category->setExid($params->category->exid);
					$Category->setName($params->category->name);
					$em->persist($Category);
					$em->flush();
				} catch(\Exception $e) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to insert category data: ' . $e->getMessage()), 'data' => null);
					throw $e;
				}
			} else {
				$Category = $em->getRepository('SiteBundle:CsCategory')->find($params->category->id);
				if(!$Category) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to find the category id'), 'data' => null);
					throw new \Exception();
				}
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
			} catch(\Exception $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Error inserting location: ' . $e->getMessage()), 'data' => null);
				throw $e;
			}
			
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Location, 'json');
			

			$rsSubscription = $em->getRepository('SiteBundle:CsSubscriptions')->findOneBy(array('object' => 'location', 'objectId' => $Location->getIdInstagram()));
			if($rsSubscription) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'The location you are trying to create already has a instagram subscription'), 'data' => null);
				throw new \Exception();
			}

			$max_subscriptions = $this->container->getParameter('instagram_max_subscriptions');
			try {
				$rsInstagram = $em->createQuery('
							SELECT i FROM SiteBundle:VwInstagramApi i
							WHERE i.totalSubscriptions < :max
							ORDER BY i.id')
						->setParameter('max', $max_subscriptions)
						->setMaxResults(1)
						->getSingleResult();
			} catch(NoResultException $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'No more instagram client id for subscriptions'), 'data' => null);
				throw new \Exception();
			}
			
			$client_id = $rsInstagram->getClientId();
			$client_secret = $rsInstagram->getClientSecret();
			$Instagram = $em->getRepository('SiteBundle:CsInstagramApi')->find($rsInstagram->getId());

			$attachment =  array(
				'client_id' => $client_id,
				'client_secret' => $client_secret,
				'object' => 'location',
				'object_id' => $Location->getIdInstagram(),
				'aspect' => $this->container->getParameter('instagram_aspect'),
				'verify_token' => $this->container->getParameter('instagram_verify_token'),
				'callback_url'=> $this->container->getParameter('instagram_callback_url')
			);
			
			
			// url for instagram api
			$subscription_url = $this->container->getParameter('instagram_subscription_api');

			$subscription_ch = curl_init();

			// post data
			curl_setopt($subscription_ch, CURLOPT_URL,$subscription_url);
			curl_setopt($subscription_ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($subscription_ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($subscription_ch, CURLOPT_POST, true);
			curl_setopt($subscription_ch, CURLOPT_POSTFIELDS, $attachment);
			curl_setopt($subscription_ch, CURLOPT_RETURNTRANSFER, true);  //to suppress the curl output 
			$subscription_result = curl_exec($subscription_ch);
			curl_close($subscription_ch);
			$subscription_response = json_decode($subscription_result, true);
			
			if($subscription_response['meta']['code'] == 200) {
				try {
					$Subscription = new CsSubscriptions();
					$Subscription->setObject('location');
					$Subscription->setObjectId($Location->getIdInstagram());
					$Subscription->setChangedAspect(null);
					$Subscription->setTime(null);
					$Subscription->setUpdated('P');
					$Subscription->setCycleCount(0);
					$Subscription->setIdInstagramApi($Instagram);
					$Subscription->setSubscriptionId($subscription_response['data']['id']);
					$em->persist($Subscription);
					$em->flush();
				} catch(\Exception $e) {
					$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to insert subscription data: ' . $e->getMessage()), 'data' => null);
					throw $e;
				}
			} else  {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Fail executing instagram API call (subscription): ' . $subscription_response['meta']['error_message']), 'data' => null);
				throw new \Exception();
			}

			// now, we must add the subscription calling the subscription URL
			$url = str_replace(array('@OBJECT_ID@', '@CLIENT_ID@'), array($Subscription->getObjectId(), $client_id), $this->container->getParameter('instagram_photo_update_url'));
			// check if there is a min_timestamp
			if($Location->getMinTimestamp())
			{
				if($Location->getMinTimestamp() > 0) $url .= str_replace(array('@MIN_TIMESTAMP@'), array($Location->getMinTimestamp()), $this->container->getParameter('instagram_photo_update_extra_params'));
			}
			
			// download location's photos
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_POST, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close ($ch);
			$response = json_decode($result, true);
			if(!isset($response['data'])) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Fail executing instagram API call (download recents): ' . $response['meta']['error_message']), 'data' => null);
				throw new \Exception();
			}

			try {
				foreach($response['data'] as $data) {
					// check if user is already registered
					$User = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $data['user']['username'], 'deleted' => 'N'));
					if(!$User) {
						try {
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
						} catch(\Exception $e) {
							$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to insert user data: ' . $e->getMessage()), 'data' => null);
							throw $e;
						}
					}

					// add the photo to database
					try {
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
					} catch(\Exception $e) {
						$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to insert photo data: ' . $e->getMessage()), 'data' => null);
						throw $e;
					}

					// add the photo's tags
					foreach($data['tags'] as $tag) {
						try {
						$Tag = new CsTags();
							$Tag->setIdLocation($Location);
							$Tag->setIdPic($Pic);
							$Tag->setTag($tag);
							$em->persist($Tag);
							$em->flush();
						} catch(\Exception $e) {
							$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to insert photo\'s tag data: ' . $e->getMessage()), 'data' => null);
							throw $e;
						}
					}

					$this->last_max_timestamp = ($data['caption']['created_time'] > $this->last_max_timestamp) ? $data['caption']['created_time'] : $this->last_max_timestamp;
				} 
			} catch(\Exception $e) {
				throw $e;
			}

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
			
			// commit all changes to database
			$em->getConnection()->commit();
			// return success
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));
		} catch(\Exception $e) {
			$em->getConnection()->rollback();
			$em->close();
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function commentsAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		
		$em = $this->getDoctrine()->getManager();
		try{
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			if(!$params->id) {
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
			
			$page = isset($params->page) ? $params->page : 1;
			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');
			
			$Comments = $em->getRepository('SiteBundle:CsLocationComments')->findBy(array('idLocation' => $params->id), array('dateAdded' => 'DESC'), $this->container->getParameter('max_items_per_page'), $offset);
			if(!$Comments) {
				$this->jsonData = array('meta' => array('status' => 'OK', 'message' => 'Success!'), 'data' => null);
				throw new \Exception();
			}

			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Comments, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function commentsAddAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		//$params = json_decode($content);
		
		$em = $this->getDoctrine()->getManager();
		$em->getConnection()->beginTransaction();
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			if(!isset($params->id) || !isset($params->comment)) {
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
			
			$Location = $em->getRepository('SiteBundle:CsLocation')->find($params->id);
			if(!$Location) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Location not found!'), 'data' => null);
				throw new \Exception();
			}
			
			try {
				$Comment = new CsLocationComments();
				$Comment->setIdUser($User);
				$Comment->setIdLocation($Location);
				$Comment->setDateAdded(new \DateTime());
				$Comment->setComment($params->comment);
				$Comment->setDeleted('S');
				$em->persist($Comment);
				$em->flush();
			} catch(\Exception $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable insert comment: ' . $e->getMessage()), 'data' => null);
				throw $e;
			}
			
			$em->getConnection()->commit();
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Comment, 'json');
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
		try {
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

			try {
				$repository = $this->getDoctrine()->getRepository('SiteBundle:VwLocation');
				$rs = $repository->createQueryBuilder('c')
						->where('c.lastPic is not null')
						->where('c.name like :keyword')->setParameter('keyword', '%' . $params->keyword . '%');
				// check for the id parameter
				if(isset($params->id)) $rs = $rs->andWhere('c.id = :id')->setParameter('id', $params->id);

				if(isset($params->geo)) {
					// check for the city parameter
					if(isset($params->geo->cityName)) $rs = $rs->andWhere('c.cityName = :city')->setParameter('city', $params->geo->cityName);

					// check for the state parameter
					if(isset($params->geo->stateName)) $rs = $rs->andWhere('c.stateName = :state')->setParameter('state', $params->geo->stateName);

					// check for the country parameter
					if(isset($params->geo->countryName)) $rs = $rs->andWhere('c.countryName = :country')->setParameter('country', $params->geo->countryName);
				}

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
							->setMaxResults($this->container->getParameter('max_items_per_page'))
							->getQuery()
							->getResult();
					$arrPhotos = array();
					foreach($rsPhotos as $p) {
						array_push($arrPhotos, array(
							'caption' =>  $p->getCaption(),
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
			} catch(\Exception $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to perform query: ' . $e->getMessage()), 'data' => null);
				throw $e;
			}
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success!'), 'data' => $arrLocations));
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
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
			
			$Location = $em->getRepository('SiteBundle:CsLocation')->find($params->id);
			if(!$Location) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Location not found'), 'data' => null);
				throw new \Exception();
			}

			$page = isset($params->page) ? $params->page : 1;
			$offset = ($page - 1) * $this->container->getParameter('max_items_per_page');

			try {
				$Likes = $em->getRepository('SiteBundle:CsLocationLikes')->findBy(array('idLocation' => $Location), array('dateAdded' => 'DESC'), $this->container->getParameter('max_items_per_page'), $offset);
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
			
			$Location = $em->getRepository('SiteBundle:CsLocation')->find($params->id);
			if(!$Location) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Location not found'), 'data' => null);
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
			
			$Like = $em->getRepository('SiteBundle:CsLocationLikes')->findOneBy(array('deleted' => 'N', 'idUser' => $User, 'idLocation' => $Location));
			if($Like) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Already liked this location'), 'data' => null);
				throw new \Exception();
			} else {
				try {
					$Like = new CsLocationLikes();
					$Like->setDateAdded(new \DateTime());
					$Like->setIdLocation($Location);
					$Like->setIdUser($User);
					$Like->setDeleted('N');
					$em->persist($Like);
					
					$Location->setLikesCount($Location->getLikesCount() + 1);
					$em->persist($Location);
					
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
}
