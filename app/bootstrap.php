<?php
session_start();
#
if(!defined('ADMIN')) require dirname(__FILE__).'/config.php';
#
require ROUTER;   
require LIB_SMARTY;
require TEMPLATE_ENGINE;
require DATABASE;
require APPLICATION_CONTROLLER;

/*spl_autoload_register(array('Doctrine', 'autoload'));
spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));

$manager = Doctrine_Manager::getInstance();
$manager->setAttribute(Doctrine::ATTR_VALIDATE, Doctrine::VALIDATE_ALL);
$manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_CONSERVATIVE);

$dbh       = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
$oDB       = Doctrine_Manager::connection($dbh);

$oDB->setOption('username', DB_USER);
$oDB->setOption('password', DB_PASSWORD);

$oDB->setCharset('utf8');
$oDB->setCollate('utf8_general_ci');
Doctrine_Core::generateModelsFromDb(DIR_MODELS, array('Doctrine'), array('generateTableClasses' => true));
Doctrine::loadModels(DIR_MODELS);  
*/
require FUNCTIONS;  

if(!defined('ADMIN') and !defined('AJAX'))
  call_controller($routes_controller, $routes_action, $routes_parameters);
?>