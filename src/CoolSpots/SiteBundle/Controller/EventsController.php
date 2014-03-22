<?php

namespace CoolSpots\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class EventsController extends Controller
{
	/**
	 * @Template("SiteBundle:Events:index.html.twig")
	 */
    public function indexAction() {
		return(array());
    }

	/**
	 * @Template("SiteBundle:Events:details.html.twig")
	 */
    public function detailsAction(){
		return(array());
    }

}
