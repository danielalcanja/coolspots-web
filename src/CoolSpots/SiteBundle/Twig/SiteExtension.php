<?php

namespace CoolSpots\SiteBundle\Twig;

class SiteExtension extends \Twig_Extension {
	public function getFilters() {
		return(array(
			new \Twig_SimpleFilter('isFavorite', array($this, 'isFavoriteFilter')),
		));
	}
	
	public function isFavoriteFilter($idLocation, $userFavorites = array()) {
		if(!is_array($userFavorites)) return(false);
		return(in_array($idLocation, $userFavorites));
	}
	
	public function getName() {
        return 'site_extension';
    }
}
?>
