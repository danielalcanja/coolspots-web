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
        return(array());
    }
}
