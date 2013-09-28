<?php

namespace CoolSpots\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AboutController extends Controller
{
	/**
	 * @Template("SiteBundle:About:index.html.twig")
	 */
    public function indexAction()
    {
		return(array());
    }

}
