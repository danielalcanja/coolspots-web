<?php

namespace CoolSpots\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomeController extends Controller
{
    /**
	 * @Template("SiteBundle:Home:index.html.twig")
	 */
	public function indexAction()
    {
		$request = $this->getRequest();
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
				->setMaxResults(1000)
				->getQuery()
				->getResult();
        return(array('rs' => $rs));
    }

}
