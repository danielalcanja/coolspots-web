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

class JSONController extends Controller
{
    public function locationAction()
    {
		/*
		 * ----------------
		 * Usage
		 * ----------------
		 * 
		 * /json/location
		 * 
		 * ----------------
		 * Parameters
		 * ----------------
		 * 
		 * Get specific page (see max_items_per_page in parameters.yml)
		 * /json/location?page=4
		 * 
		 * Get a especific location
		 * /json/location?id=9
		 * 
		 * Get all locations from a city
		 * /json/location?city=1
		 * 
		 * Get all locations from a specific state
		 * /json/location?state=14
		 * 
		 * Get all locations from a specific country
		 * /json/location?country=1
		 * 
		 * Get all locations from a specific category
		 * /json/location?category=1
		 * 
		 * Combinations:
		 * /json/location?city=1&category=8&page=2
		 */
		$request = $this->getRequest();
		$offset = ($request->get('page', 1) - 1) * $this->container->getParameter('max_items_per_page');
		
		$repository = $this->getDoctrine()->getRepository('SiteBundle:VwLocation');
		$rs = $repository->createQueryBuilder('c')
				->where('c.coverPic is not null');
		// check for the id parameter
		if($request->get('id')) $rs = $rs->andWhere('c.id = :id')->setParameter('id', $request->get('id'));
		
		// check for the city parameter
		if($request->get('city')) $rs = $rs->andWhere('c.idCity = :city')->setParameter('city', $request->get('city'));
		
		// check for the state parameter
		if($request->get('state')) $rs = $rs->andWhere('c.idState = :state')->setParameter('state', $request->get('state'));
		
		// check for the country parameter
		if($request->get('country')) $rs = $rs->andWhere('c.idCountry = :country')->setParameter('country', $request->get('country'));
		
		// check for the category parameter
		if($request->get('category')) $rs = $rs->andWhere('c.idCategory = :category')->setParameter('category', $request->get('category'));
		
		$rs = $rs->andWhere('c.enabled = :enabled')
				->setParameter('enabled', 'Y')
				->andWhere('c.deleted = :deleted')
				->setParameter('deleted', 'N')
				->orderBy('c.dateUpdated', 'desc')
				->setFirstResult($offset)
				->setMaxResults($this->container->getParameter('max_items_per_page'))
				->getQuery()
				->getResult();
		
//		$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
//		$json = $serializer->serialize($rs, 'json');
		
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
		$json = json_encode($arrLocations);
		$response = new Response($json);
		$response->headers->set('content-type', 'application/json');
		return $response;
    }
	
    public function locationInfoAction($id, $slug)
    {
		/*
		 * ----------------
		 * Usage
		 * ----------------
		 * 
		 * /json/locatonInfo/1/getulio-loft
		 */
		$repository = $this->getDoctrine()->getRepository('SiteBundle:CsLocation');
		$rs = $repository->createQueryBuilder('c')
				->where('c.id = :id')
				->andWhere('c.enabled = :enabled')
				->andWhere('c.deleted = :deleted')
				->setParameter('id', $id)
				->setParameter('enabled', 'Y')
				->setParameter('deleted', 'N')
				->getQuery()
				->getSingleResult();
		
		$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
		$json = $serializer->serialize($rs, 'json');		
		$response = new Response($json);
		$response->headers->set('content-type', 'application/json');
		
		return $response;
	}
	
	public function photosAction($idLocation, $slug)
	{
		/*
		 * ----------------
		 * Usage
		 * ----------------
		 * 
		 * /json/photos/1/getulio-loft
		 * 
		 * ----------------
		 * Parameters
		 * ----------------
		 * 
		 * Get specific page (see max_items_per_page in parameters.yml)
		 * /json/photos/1/getulio-loft?page=4
		 */
		
		$request = $this->getRequest();
		$offset = ($request->get('page', 1) - 1) * $this->container->getParameter('max_items_per_page');
		$rs = $this->getDoctrine()
				->getManager()
				->createQuery('SELECT p, u, l FROM SiteBundle:CsPics p
								JOIN p.idUser u
								JOIN p.idLocation l
								WHERE p.idLocation = :id
								ORDER BY p.dateAdded DESC')
				->setFirstResult($offset)
				->setMaxResults($this->container->getParameter('max_items_per_page'))
				->setParameter('id', $idLocation)->getResult();
		
		
		$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
		$json = $serializer->serialize($rs, 'json');
		
		$response = new Response($json);
		$response->headers->set('content-type', 'application/json');
		
		return $response;
	}
	
	public function getGeoAction($countryName, $countryCode, $stateName, $stateAbbr, $cityName) {
		/*
		 * ----------------
		 * Usage
		 * ----------------
		 * 
		 * /json/getgeo/Brazil/BR/Mato Grosso/MT/Sinop
		 */
		
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
				$json = json_encode(array('Error' => $e->getMessage()));
				$response = new Response($json);
				$response->headers->set('content-type', 'application/json');
				return($response);
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
				$json = json_encode(array('Error' => $e->getMessage()));
				$response = new Response($json);
				$response->headers->set('content-type', 'application/json');
				return($response);
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
				$json = json_encode(array('Error' => $e->getMessage()));
				$response = new Response($json);
				$response->headers->set('content-type', 'application/json');
				return($response);
			}
		}
		
		$json = json_encode(array('id_contry' => $Country->getId(), 'id_state' => $State->getId(), 'id_city' => $City->getId()));
		$response = new Response($json);
		$response->headers->set('content-type', 'application/json');
		return $response;
	}
	
	public function addLocationAction() {
		/*
		 * ----------------
		 * Usage
		 * ----------------
		 * 
		 * (POST METHOD) /json/addlocation
		 * 
		 * ----------------
		 * Parameters
		 * ----------------
		 * * Mandatory:
		 *   - id_country
		 *   - id_state
		 *   - id_city
		 *   - id_instagram
		 *   - id_foursquare
		 *   - id_category (0 = create at runtime)
		 *   - category_name
		 *   - category_exid
		 *   - name
		 * 
		 * * Optional:
		 *   - address
		 *   - postal_code
		 *   - phone
		 */
		
		$request = $this->getRequest();
		
		// check if all parameters are passed to the controler
		if($request->get('id_country') === null || $request->get('id_state') === null || $request->get('id_city') === null
			|| $request->get('id_instagram') === null || $request->get('id_foursquare') === null || $request->get('id_category') === null 
			|| $request->get('category_name') === null || $request->get('category_exid') === null || $request->get('name') === null) {
			$json = json_encode(array('status' => 'ERROR', 'message' => 'Missing mantatory parameters'));
			$response = new Response($json);
			$response->headers->set('content-type', 'application/json');
			return $response;
		}
		
		// first, check if instagram id isn't already registered
		$rsLocation = $this->getDoctrine()->getRepository('SiteBundle:CsLocation')->createQueryBuilder('c')
					->where('c.idInstagram = :idInstagram')
					->andWhere('c.idFoursquare = :idFoursquare')
					->andWhere('c.enabled = :enabled')
					->andWhere('c.deleted = :deleted')
					->setParameter('idInstagram', $request->get('id_instagram'))
					->setParameter('idFoursquare', $request->get('id_foursquare'))
					->setParameter('enabled', 'Y')
					->setParameter('deleted', 'N')
					->getQuery()
					->getResult();
		if(count($rsLocation) > 0) {
			$json = json_encode(array('status' => 'ERROR', 'message' => 'Location already exists!'));
			$response = new Response($json);
			$response->headers->set('content-type', 'application/json');
			return $response;
		}
		
		// get entity manager
		$em = $this->getDoctrine()->getManager();
		
		// get country, state and city objects
		$Country = $em->getRepository('SiteBundle:CsGeoCountry')->find($request->get('id_country'));
		$State = $em->getRepository('SiteBundle:CsGeoState')->find($request->get('id_state'));
		$City = $em->getRepository('SiteBundle:CsGeoCity')->find($request->get('id_city'));
		$Category = null;
		
		// check if category exists
		if($request->get('id_category') == 0) {
			$Category = new CsCategory();
			$Category->setExid($request->get('category_exid'));
			$Category->setName($request->get('category_name'));
			$em->persist($Category);
			$em->flush();
		} else {
			$Category = $em->getRepository('SiteBundle:CsCategory')->find($request->get('id_category'));
		}
		
		try {
			// insert the new location
			$Location = new CsLocation();
			$Location->setAddress($request->get('address'));
			$Location->setCheckinsCount(0);
			$Location->setCoverPic(null);
			$Location->setDateAdded(new \DateTime());
			$Location->setDateUpdated(null);
			$Location->setDeleted('N');
			$Location->setEnabled('Y');
			$Location->setIdCategory($Category);
			$Location->setIdCity($City);
			$Location->setIdCountry($Country);
			$Location->setIdFoursquare($request->get('id_foursquare'));
			$Location->setIdInstagram($request->get('id_instagram'));
			$Location->setIdState($State);
			$Location->setLastMinId(null);
			$Location->setLikesCount(0);
			$Location->setMinTimestamp(null);
			$Location->setName($request->get('name'));
			$Location->setNextMaxId(null);
			$Location->setPhone($request->get('phone'));
			$Location->setPostalCode($request->get('postal_code'));
			$Location->setSlug(CSUtil::slugify($request->get('name')));
			$em->persist($Location);
			$em->flush();
		} catch(Exception $e) {
			$json = json_encode(array('status' => 'ERROR', 'message' => $e->getMessage()));
			$response = new Response($json);
			$response->headers->set('content-type', 'application/json');
			return $response;
		}
		
		$json = json_encode(array('status' => 'OK', 'message' => 'Location successful registered!', 'id' => $Location->getId()));
		$response = new Response($json);
		$response->headers->set('content-type', 'application/json');
		return $response;
	}
}
