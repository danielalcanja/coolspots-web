<?php

namespace CoolSpots\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UsersController extends Controller
{
	/**
	 * @Template("SiteBundle:Users:index.html.twig")
	 */
    public function indexAction()
    {
		return(array());
    }

}
