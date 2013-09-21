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
		$repository = $this->getDoctrine()->getRepository('SiteBundle:CsLocation');
		$rs = $repository->createQueryBuilder('c')
				->where('c.coverPic is not null')
				->andWhere('c.enabled = :enabled')
				->andWhere('c.deleted = :deleted')
				->orderBy('c.dateUpdated', 'desc')
				->setParameter('enabled', 'Y')
				->setParameter('deleted', 'N')
				->setMaxResults(1000)
				->getQuery()
				->getResult();
		
		$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
		$json = $serializer->serialize($rs, 'json');		
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
