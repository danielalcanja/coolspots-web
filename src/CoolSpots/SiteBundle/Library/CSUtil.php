<?php

namespace CoolSpots\SiteBundle\Library;

class CSUtil {
	
	/**
	 * Converts a text to a slug format
	 * 
	 * @param $str String
	 * @return String $slug
	 */
	static public function slugify($text){ 
	  $ret = preg_replace('~[^-\w]+~', '', strtolower(iconv('utf-8', 'us-ascii//TRANSLIT', trim(preg_replace('~[^\\pL\d]+~u', '-', $text), '-'))));
	  return(empty($ret) ? 'n-a' : $ret);
	}
	
}
?>
