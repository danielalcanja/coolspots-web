<?php

namespace CoolSpots\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ExploreController extends Controller
{
	/**
	 * @Template("SiteBundle:Explore:index.html.twig")
	 */
    public function indexAction()
    {
		return(array());
    }

}
