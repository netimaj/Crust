<?php
/**
* Crust Framework Router
* 
* @author Ahmet Özışık
* @version 1.1
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

$routes_root_controller = 'home';
$routes_root_action     = 'index';

$routes_controller = (!empty($routing_request_array[0])) ? $routing_request_array[0] : $routes_root_controller;
$routes_action     = (!empty($routing_request_array[1])) ? $routing_request_array[1] : $routes_root_action;
$routes_parameters = (count($routing_request_array) > 2) ? array_slice($routing_request_array, 2) : null;
?>