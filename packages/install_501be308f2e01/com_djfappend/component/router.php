<?php
/**
 * @version		$Id: router.php 14401 2010-01-26 14:10:00Z louis $
 * @package		Joomla
 * @subpackage	Djfacl
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

/**
 * @param	array	A named array
 * @return	array
 */
function DjfappendBuildRoute(&$query) {
	$segments = array ();
	
	if (isset ( $query ['view'] )) {
		$segments [] = 'view';
		$segments [] = $query ['view'];
		unset ( $query ['view'] );
	}
	if (isset ( $query ['id'] )) {
		$segments [] = 'id';
		$segments [] = $query ['id'];
		unset ( $query ['id'] );
	}
	if (isset ( $query ['controller'] )) {
		$segments [] = 'controller';
		$segments [] = $query ['controller'];
		unset ( $query ['controller'] );
	}
	if (isset ( $query ['task'] )) {
		$segments [] = 'task';
		$segments [] = $query ['task'];
		unset ( $query ['task'] );
	}
	
	if (isset ( $query ['tmpl'] )) {
		$segments [] = 'tmpl';
		$segments [] = $query ['tmpl'];
		unset ( $query ['tmpl'] );
	}
	if (isset ( $query ['from'] )) {
		$segments [] = 'from';
		$segments [] = $query ['from'];
		unset ( $query ['from'] );
	}
	if (isset ( $query ['id_jarticle'] )) {
		$segments [] = 'id_jarticle';
		$segments [] = $query ['id_jarticle'];
		unset ( $query ['id_jarticle'] );
	}

if (isset ( $query ['format'] )) {
		$segments [] = 'format';
		$segments [] = $query ['format'];
		unset ( $query ['format'] );
	}
if (isset ( $query ['id_field'] )) {
		$segments [] = 'id_field';
		$segments [] = $query ['id_field'];
		unset ( $query ['id_field'] );
	}
	
	
	return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/Djfacl/task/bid/Itemid
 *
 * index.php?/Djfacl/bid/Itemid
 */
function DjfappendParseRoute($segments) {
	$count = count ( $segments );
	$vars = array ();
	$menu = &JSite::getMenu ();
	$item = &$menu->getActive ();
	
	for($i = 0; $i < count ( $segments ); $i ++) {
		if (isset ( $segments [$i+1] )) {
			$vars [$segments [$i]] = $segments [$i + 1];
			//echo("<br>".$segments [$i]." = ".$segments [$i + 1]);
		}
	}
	
	return $vars;
}