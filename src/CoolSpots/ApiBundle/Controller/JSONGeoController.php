<?php
namespace CoolSpots\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Doctrine\ORM\NoResultException;

class JSONGeoController extends Controller {
	private $jsonData = array();
	
	private function jsonResponse($arrResponse) {
		$json = json_encode($arrResponse);
		$response = new Response($json);
		$response->headers->set('content-type', 'application/json');
		return $response;
	}
	
	public function countriesAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		$em = $this->getDoctrine()->getManager();
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			$Countries = $em->getRepository('SiteBundle:CsGeoCountry')->findBy(array('deleted' => 'N', 'enabled' => 'Y'), array('countryName' => 'ASC'));
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Countries, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));	
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function countriesSearchAction() {
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
			
			$Countries = $em->getRepository('SiteBundle:CsGeoCountry')->createQueryBuilder('c')
					->where('LOWER(c.countryName) like :keyword')->setParameter('keyword', '%' . mb_strtolower($params->keyword, 'UTF-8') . '%')
					->orderBy('c.countryName', 'ASC')
					->getQuery()
					->getResult();
			
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Countries, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));	
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function statesAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		$em = $this->getDoctrine()->getManager();
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			if(!isset($params->geo) || !isset($params->geo->countryName)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information. '), 'data' => null);
				throw new \Exception();
			}
			
			try {
				$Country = $em->getRepository('SiteBundle:CsGeoCountry')->createQueryBuilder('c')
					->where('LOWER(c.countryName) = :countryName')
					->andWhere('c.enabled = :enabled')
					->andWhere('c.deleted = :deleted')
					->setParameter('countryName', mb_strtolower($params->geo->countryName, 'UTF-8'))
					->setParameter('enabled', 'Y')
					->setParameter('deleted', 'N')
					->getQuery()
					->getSingleResult();
			} catch(NoResultException $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to find country by name'), 'data' => null);
				throw $e;
			}
			
			$States = $em->getRepository('SiteBundle:CsGeoState')->createQueryBuilder('c')
					->where('c.deleted = :deleted')->setParameter('deleted', 'N')
					->andWhere('c.enabled = :enabled')->setParameter('enabled', 'Y')
					->andWhere('c.idCountry = :idCountry')
					->setParameter('idCountry', $Country->getId())
					->orderBy('c.stateName', 'ASC')
					->getQuery()
					->getResult();
			
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($States, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));	
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function statesSearchAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		$em = $this->getDoctrine()->getManager();
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			if(!isset($params->geo) || !isset($params->geo->countryName) || !isset($params->keyword)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information. '), 'data' => null);
				throw new \Exception();
			}
			
			try {
				$Country = $em->getRepository('SiteBundle:CsGeoCountry')->createQueryBuilder('c')
					->where('LOWER(c.countryName) = :countryName')
					->andWhere('c.enabled = :enabled')
					->andWhere('c.deleted = :deleted')
					->setParameter('countryName', mb_strtolower($params->geo->countryName, 'UTF-8'))
					->setParameter('enabled', 'Y')
					->setParameter('deleted', 'N')
					->getQuery()
					->getSingleResult();
			} catch(NoResultException $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to find country by name'), 'data' => null);
				throw $e;
			}
			
			$States = $em->getRepository('SiteBundle:CsGeoState')->createQueryBuilder('c')
					->where('LOWER(c.stateName) like :keyword')->setParameter('keyword', '%' . mb_strtolower($params->keyword, 'UTF-8') . '%')
					->andWhere('c.deleted = :deleted')->setParameter('deleted', 'N')
					->andWhere('c.enabled = :enabled')->setParameter('enabled', 'Y')
					->andWhere('c.idCountry = :idCountry')->setParameter('idCountry', $Country->getId())
					->orderBy('c.stateName', 'ASC')
					->getQuery()
					->getResult();
			
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($States, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));	
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function citiesAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		$em = $this->getDoctrine()->getManager();
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			if(!isset($params->geo) || !isset($params->geo->countryName) || !isset($params->geo->stateName)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information. '), 'data' => null);
				throw new \Exception();
			}
			
			try {
				$Country = $em->getRepository('SiteBundle:CsGeoCountry')->createQueryBuilder('c')
					->where('LOWER(c.countryName) = :countryName')
					->andWhere('c.enabled = :enabled')
					->andWhere('c.deleted = :deleted')
					->setParameter('countryName', mb_strtolower($params->geo->countryName, 'UTF-8'))
					->setParameter('enabled', 'Y')
					->setParameter('deleted', 'N')
					->getQuery()
					->getSingleResult();
			} catch(NoResultException $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to find country by name'), 'data' => null);
				throw $e;
			}
			
			try {
				$State = $em->getRepository('SiteBundle:CsGeoState')->createQueryBuilder('c')
					->where('LOWER(c.stateName) = :stateName')
					->andWhere('c.idCountry = :idCountry')
					->andWhere('c.enabled = :enabled')
					->andWhere('c.deleted = :deleted')
					->setParameter('stateName', mb_strtolower($params->geo->stateName, 'UTF-8'))
					->setParameter('idCountry', $Country->getId())
					->setParameter('enabled', 'Y')
					->setParameter('deleted', 'N')
					->getQuery()
					->getSingleResult();
			} catch(NoResultException $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to find state by name'), 'data' => null);
				throw $e;
			}
			$Cities = $em->getRepository('SiteBundle:CsGeoCity')->createQueryBuilder('c')
					->where('c.deleted = :deleted')->setParameter('deleted', 'N')
					->andWhere('c.enabled = :enabled')->setParameter('enabled', 'Y')
					->andWhere('c.idCountry = :idCountry')
					->andWhere('c.idState = :idState')
					->setParameter('idCountry', $Country->getId())
					->setParameter('idState', $State->getId())
					->orderBy('c.cityName', 'ASC')
					->getQuery()
					->getResult();
			
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Cities, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));	
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
	public function citiesSearchAction() {
		$request = $this->getRequest();
		$content = $request->getContent();
		$params = json_decode($content); //$params = json_decode(utf8_decode($content));
		$em = $this->getDoctrine()->getManager();
		try {
			if($params === null) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Invalid post content'), 'data' => null);
				throw new \Exception();
			}
			
			if(!isset($params->geo) || !isset($params->geo->countryName) || !isset($params->geo->stateName) || !isset($params->keyword)) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Missing mandatory parameters. See the API documentation for more information. '), 'data' => null);
				throw new \Exception();
			}
			
			try {
				$Country = $em->getRepository('SiteBundle:CsGeoCountry')->createQueryBuilder('c')
					->where('LOWER(c.countryName) = :countryName')
					->andWhere('c.enabled = :enabled')
					->andWhere('c.deleted = :deleted')
					->setParameter('countryName', mb_strtolower($params->geo->countryName, 'UTF-8'))
					->setParameter('enabled', 'Y')
					->setParameter('deleted', 'N')
					->getQuery()
					->getSingleResult();
			} catch(NoResultException $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to find country by name'), 'data' => null);
				throw $e;
			}
			
			try {
				$State = $em->getRepository('SiteBundle:CsGeoState')->createQueryBuilder('c')
					->where('LOWER(c.stateName) = :stateName')
					->andWhere('c.idCountry = :idCountry')
					->andWhere('c.enabled = :enabled')
					->andWhere('c.deleted = :deleted')
					->setParameter('stateName', mb_strtolower($params->geo->stateName, 'UTF-8'))
					->setParameter('idCountry', $Country->getId())
					->setParameter('enabled', 'Y')
					->setParameter('deleted', 'N')
					->getQuery()
					->getSingleResult();
			} catch(NoResultException $e) {
				$this->jsonData = array('meta' => array('status' => 'ERROR', 'message' => 'Unable to find state by name'), 'data' => null);
				throw $e;
			}
			$Cities = $em->getRepository('SiteBundle:CsGeoCity')->createQueryBuilder('c')
					->where('LOWER(c.cityName) like :keyword')->setParameter('keyword', '%' . mb_strtolower($params->keyword, 'UTF-8') . '%')
					->andWhere('c.deleted = :deleted')->setParameter('deleted', 'N')
					->andWhere('c.enabled = :enabled')->setParameter('enabled', 'Y')
					->andWhere('c.idCountry = :idCountry')
					->andWhere('c.idState = :idState')
					->setParameter('idCountry', $Country->getId())
					->setParameter('idState', $State->getId())
					->orderBy('c.cityName', 'ASC')
					->getQuery()
					->getResult();
			
			$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
			$json = $serializer->serialize($Cities, 'json');
			return $this->jsonResponse(array('meta' => array('status' => 'OK', 'message' => 'Success'), 'data' => json_decode($json)));	
		} catch(\Exception $e) {
			return $this->jsonResponse($this->jsonData);
		}
	}
	
}