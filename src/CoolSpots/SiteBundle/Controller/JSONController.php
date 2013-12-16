<?php

namespace CoolSpots\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class JSONController extends Controller
{
    public function locationAction()
    {
		/*
		 * Filters
		 * 
		 * Get a especific location
		 * /json/location?id=9
		 * 
		 * Get all locations from a city
		 * /json/locations?city=1
		 * 
		 * Get all locations from a specific state
		 * /json/locations?state=14
		 * 
		 * Get all locations from a specific country
		 * /json/locations?country=1
		 * 
		 * Get all locations from a specific category
		 * /json/locations?category=1
		 * 
		 * Combinations:
		 * /json/locations?city=1&category=8
		 */
		$request = $this->getRequest();
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
				->setMaxResults(1000)
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
		$rs = $this->getDoctrine()
				->getManager()
				->createQuery('SELECT p, u, l FROM SiteBundle:CsPics p
								JOIN p.idUser u
								JOIN p.idLocation l
								WHERE p.idLocation = :id
								ORDER BY p.dateAdded DESC')
				->setParameter('id', $idLocation)->getResult();
		
		$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
		$json = $serializer->serialize($rs, 'json');
		
		$response = new Response($json);
		$response->headers->set('content-type', 'application/json');
		
		return $response;
	}
}
