<?php

namespace CoolSpots\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MessagesController extends Controller
{
    /**
	 * @Template("SiteBundle:Messages:index.html.twig")
	 */
	public function indexAction()
    {
		return(array());
    }

}
