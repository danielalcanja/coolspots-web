<?php

namespace CoolSpots\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
	/**
	 * @Template("SiteBundle:Default:index.html.twig")
	 */
    public function indexAction()
    {
		$request = $this->getRequest();
		$page = $request->get('page', 1);
		$max_per_page = $this->container->getParameter('max_items_per_page');
		$offset = $max_per_page * ($page - 1);
		
		$session = $request->getSession();
		$request->setLocale($session->get('_locale', 'en_US'));
		
		$repository = $this->getDoctrine()->getRepository('SiteBundle:VwLocation');
		$rs = $repository->createQueryBuilder('c')
				->where('c.lastPic is not null')
				->andWhere('c.enabled = :enabled')
				->andWhere('c.deleted = :deleted')
				->orderBy('c.dateUpdated', 'desc')
				->setParameter('enabled', 'Y')
				->setParameter('deleted', 'N')
				->setFirstResult($offset)
				->setMaxResults($max_per_page)
				->getQuery()
				->getResult();
		$total_locations = count($rs);
		$next_page = $total_locations > 0 ? $page + 1 : false;
		
        return(array('rs' => $rs, 'ul_count' => 1, 'total_locations' => $total_locations, 'page' => $page, 'next_page' => $next_page));
    }
	
	public function localeAction($lang)
	{
		$request = $this->getRequest();
		$session = $request->getSession();
		$session->set('_locale', $lang);
		return $this->redirect($this->generateUrl('main'));
	}
}
