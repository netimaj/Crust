<?php
/**
* Crust Framework Router
* 
* @author Ahmet Özışık
* @version 1.2
 * 
 * 
 * --CHANGELOG
 * 		Mapping feature
*/

$routes_root_controller = 'home';
$routes_root_action     = 'index';

/**
 * USING THE ARRAY BELOW YOU CAN REWRITE URLS
 * $mapping['cars'] = 'show_content/cars';
 * 
 * Use the URL to be shown as the key and the url it should actually call as value 
 */
$mapping = array();



/**
 * DO NOT TOUCH BELOW
 * 
 */
function routing_sanitize($param)
{
  return strip_tags($param);
}
// Get requested url
$routing_request_uri = $_SERVER['REQUEST_URI'];
// If there is any subfolder remove it
if(SUB_FOLDER)
$routing_request_uri = substr($routing_request_uri, strlen(SUB_FOLDER), strlen($routing_request_uri));
// Explode the URL into an array
$routing_request_array = explode('/', $routing_request_uri);
// Sanitize request
$routing_request_array = array_map('routing_sanitize', $routing_request_array);

//MAPPING
if(count($mapping) > 0)
{
	
	foreach($mapping as $map => $connect_to)
	{
		if($routing_request_array[0] == $map)
		{
			$temporary = array();
			
			$temporary = explode('/', $connect_to);
			// Sanitize request
			$temporary = array_map('routing_sanitize', $temporary);			
		}
		else {
			continue;
		}
	}
	
	if(isset($temporary) and is_array($temporary) and count($temporary) > 0)
	{
		unset($routing_request_array[0]);
		$routing_request_array = array_merge($temporary, $routing_request_array);
	}
}

$routes_controller = (!empty($routing_request_array[0])) ? $routing_request_array[0] : $routes_root_controller;
$routes_action     = (!empty($routing_request_array[1])) ? $routing_request_array[1] : $routes_root_action;
$routes_parameters = (count($routing_request_array) > 2) ? array_slice($routing_request_array, 2) : null;
?>