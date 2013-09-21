<?php

namespace CoolSpots\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PhotoController extends Controller
{
	/**
	 * @Template("SiteBundle:Photo:show.html.twig")
	 */
	public function showAction($idLocation, $slug)
	{
		$request = $this->getRequest();
		$session = $request->getSession();
		$request->setLocale($session->get('_locale', 'en_US'));
		
		$em = $this->getDoctrine()->getManager();
				
		$rsInfo = $em->createQuery('
					SELECT l, g, c FROM SiteBundle:CsLocation l
					JOIN l.idGeo g
					JOIN l.idCategory c
					WHERE l.id = :id
					AND l.enabled = :enabled
					AND l.deleted = :deleted')
				->setParameter('id', $idLocation)
				->setParameter('enabled', 'Y')
				->setParameter('deleted', 'N')
				->getSingleResult();
		
		$rsPics = $em->createQuery('
					SELECT p, u, l FROM SiteBundle:CsPics p
					JOIN p.idUser u
					JOIN p.idLocation l
					WHERE p.idLocation = :id
					ORDER BY p.dateAdded DESC')
				->setParameter('id', $idLocation)
				->getResult();
		
		return(array('location' => $rsInfo, 'pics' => $rsPics));
	}
}
