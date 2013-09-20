<?php

namespace CoolSpots\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PhotoController extends Controller
{
	/**
	 * @Template("SiteBundle:Photo:show.html.twig")
	 */
	public function showAction()
	{
		return(array());
	}
}
