<?php

namespace CoolSpots\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PhotoController extends Controller
{
	/**
	 * @Template("SiteBundle:Photo:show.html.twig")
	 */
	public function showAction($idLocation, $slug, $page)
	{
		$max_per_page = $this->container->getParameter('max_items_per_page');
		$em = $this->getDoctrine()->getManager();
		
		$request = $this->getRequest();
		$session = $request->getSession();
		$request->setLocale($session->get('_locale', 'en_US'));
		$rsInfo = $em->createQuery('
					SELECT l FROM SiteBundle:VwLocation l
					WHERE l.id = :id
					AND l.enabled = :enabled
					AND l.deleted = :deleted')
				->setParameter('id', $idLocation)
				->setParameter('enabled', 'Y')
				->setParameter('deleted', 'N')
				->getSingleResult();

		$offset = $max_per_page * ($page - 1);
		
		$rsPics = $em->createQuery('
					SELECT p, u, l FROM SiteBundle:CsPics p
					JOIN p.idUser u
					JOIN p.idLocation l
					WHERE p.idLocation = :id
					ORDER BY p.createdTime DESC')
				->setParameter('id', $idLocation)
				->setFirstResult($offset)
				->setMaxResults($max_per_page)
				->getResult();
		$total_pics = count($rsPics);
		$next_page = $total_pics > 0 ? $page + 1 : false;
		return(array('location' => $rsInfo, 'pics' => $rsPics, 'page' => $page, 'total_pics' => $total_pics, 'next_page' => $next_page, 'ul_count' => 1, 'last_date' => $request->get('last_date')));
	}
}