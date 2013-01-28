<?php
/**
* Crust Framework Config
* 
* @author Ahmet Özışık
*/
define('CRUST_VERSION', '0.9.2');
define('IN_SCRIPT'   , true);                   
define('SERVICE_MAIL', 'no-reply@example.com');

define('SMTP_SERVER', 'smtp.example.com');
define('SMTP_USERNAME', 'smtp@example.com');
define('SMTP_PASSWORD', '123456');


define('MAIL_REPLY_TO', 'no-reply@example.com');
define('MAIL_SENDER', 'no-reply@example.com');


define('ENVIRONMENT', 'development');
#define('ENVIRONMENT', 'production');
#define('ENVIRONMENT', 'local');
 
if(ENVIRONMENT == 'development')
{
  define('SUB_FOLDER'     , '/framework/');
  define('ROOT'           , $_SERVER['DOCUMENT_ROOT'].'/');
  define('URL'            , 'http://localhost/framework/');
  define('NOCACHE'        , true);      
  define('DB_DSN'         , 'mysql:dbname=framework;host=127.0.0.1');
  define('DB_USER'        , 'root');
  define('DB_PASSWORD'    , '');  
  //define('LOCAL_MAIL_DIR', 'C:/Users/pc/Desktop/inbox');
}
elseif(ENVIRONMENT == 'local')
{
  /**
  * Local environment
  */
}

else
{
  /**
  * Production environment
  */
}
// Class name t_smarty
define('LIB_SMARTY'     , ROOT.'libs/smarty/Smarty.class.php');
define('TEMPLATE_ENGINE', ROOT.'libs/smarty/t_smarty.php');
define('T_COMPILE'      , ROOT.'tmp/cache/');
define('T_CACHE'        , ROOT.'tmp/cache/');
// Routing
define('ROUTER' , ROOT.'app/router.php');
// Class name db
define('DATABASE', ROOT.'libs/orm/Doctrine.php');

define('VIEWS', ROOT.'app/views/');
define('VIEWS_LAYOUTS', VIEWS.'layouts/');
#define('VIEWS_ERRORS', VIEWS);

define('FUNCTIONS' , ROOT.'app/functions.php');
define('APPLICATION_CONTROLLER', ROOT.'app/controllers/application.php');
define('APPLICATION_HELPER', ROOT.'app/helpers/application.helper.php');

define('DIR_HELPERS', ROOT.'app/helpers/');
define('DIR_CONTROLLERS', ROOT.'app/controllers/');
define('DIR_MODELS', ROOT.'app/models/');
define('DIR_LIBS', ROOT.'libs/');

define('CSS_PATH', URL.'public/css/');
define('IMAGES_PATH', URL.'public/images/');
define('JS_PATH', URL.'public/js/');
define('JSON_PATH' ,  URL.'public/json/');

define('UPLOADS_PATH', ROOT.'public/uploads/');
define('LIB_PHP_MAILER', ROOT.'libs/php_mailer/class.phpmailer.php');
            
?>