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
		$session = $request->getSession();
		$request->setLocale($session->get('_locale', 'en'));
		
		
		$repository = $this->getDoctrine()->getRepository('SiteBundle:CsLocation');
		$rs = $repository->createQueryBuilder('c')
				->where('c.coverPic is not null')
				->orderBy('c.dateUpdated', 'desc')
				->setMaxResults(100)
				->getQuery()
				->getResult();
        return(array('rs' => $rs));
    }
	
	public function localeAction($lang)
	{
		$request = $this->getRequest();
		$session = $request->getSession();
		$session->set('_locale', $lang);
		return $this->redirect($this->generateUrl('main'));
	}
}
