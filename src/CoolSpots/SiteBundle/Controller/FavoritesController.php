<?php

namespace CoolSpots\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FavoritesController extends Controller
{
    /**
	 * @Template("SiteBundle:Favorites:index.html.twig")
	 */
	public function indexAction()
    {
		return(array());
    }

}
