<?php
  
function _404()
{
	define('404', true);
  exit;
}

function _debug($message, $param)
{
  require ROOT.'public/debug.php';
  exit;
}

function _500()
{
	define('500', true);
  exit;
}

function redirect_to($request, $to_referer=false)
{
	if(filter_var($request, FILTER_VALIDATE_URL))
	{
		header('Location: '.$request);
		exit;
	}
	
  header(($to_referer) ? 'Location: '.$_SERVER['HTTP_REFERER'] : 'Location: '.URL.$request);
  exit;
}

function load_helper($helper_name, $instance=null)
{
  $file_name = DIR_HELPERS.strtolower($helper_name).'.helper.php';    
  if(!file_exists($file_name))
    return null;

  include($file_name);
  $class_name = $helper_name.'Helper';
  
  if(!class_exists($class_name))
    return null;
  
  return (is_null($instance)) ? new $class_name() : new $class_name($instance);
}

function call_controller($name, $action, $parameters)
{
  // Controller path
  $path = DIR_CONTROLLERS.$name.'.controller.php';
  // Check if controller exists
  if(!file_exists($path))
    (ENVIRONMENT == 'production') ? _404() : _debug('Controller not found', $name);  
  // Include controller
  require $path;
  
  $controller_name = ucwords($name).'Controller';
  // Declare controller
  $controller = new $controller_name($name, $action, $parameters);
  $call       = (method_exists($controller, $action)) ? $action : 'index';
  // Make call
  $controller->$call();
}

/**
* Obviously sends mail
* 
* @param mixed $to
* @param mixed $subject
* @param mixed $template_vars
* @param mixed $template
*/
function send_mail($to, $subject, $template_vars=array(), $template)
{
  $smarty = new t_smarty();
  
  if(!empty($template_vars))
    foreach($template_vars as $x => $y)
      $smarty->assign($x, $y);
  
  $message = $smarty->fetch($template);
  
  $mail_header = 'Content-type: text/html; charset:UTF-8'."\r\n"
                .'To: <'.$to.'>'." \r\n"
                .'From: '.get_config('service_name').' <'.get_config('service_mail')."> \r\n";  
  
  if(ENVIRONMENT == 'development')
  {
    file_put_contents(LOCAL_MAIL_DIR.'/mail_'.md5(microtime()).'.html', $message);
  }
  else
  {
  	require LIB_PHP_MAILER;
		
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->From     = MAIL_SENDER; //Gönderen kısmında yer alacak e-mail adresi
		$mail->Sender   = MAIL_SENDER;
		$mail->ReplyTo  = MAIL_REPLY_TO;
		$mail->FromName =  get_config('service_name');
		$mail->Host     = SMTP_SERVER; //SMTP server adresi
		$mail->SMTPAuth = true; //SMTP server'a kullanıcı adı ile bağlanılcağını belirtiyoruz
		$mail->Username = SMTP_USERNAME; //SMTP kullanıcı adı
		$mail->Password = SMTP_PASSWORD; //SMTP şifre
		$mail->WordWrap = 50;
		$mail->Subject  = $subject; // Konu		
		$mail->IsHTML(true);
		
		$mail->Body = $message;
		$mail->AltBody = strip_tags($message);
		
		$mail->AddAddress($to);
		
		$mail->Send(); 

		$mail->ClearAddresses();
		$mail->ClearAttachments();
				
   //@mail($to, $subject, $message, $mail_header);  
  }
}

/**
* Giriş yapmış kullanıcıları reddet
* 
*/
function deny_logged_users()
{
  if(isset($_SESSION[USER_SESSION]))
    redirect_to('home', false);
}
               
/**
* Giriş yapmamış kullanıcıları reddet
* 
*/
function deny_unlogged_users()
{
  if(!isset($_SESSION[USER_SESSION]))
    redirect_to('home', false);
}

/**
* Returns a json file as an array
* 
* @param mixed $file
* @return mixed
*/
function getJsonArray($file)
{
  $content = @file_get_contents($file);
  if(empty($content)) return false;
  return json_decode($content, true);
}

/**
* Generates a random password
* 
* @param mixed $len
*/
function random_password($len = 6)
{
    $r = '';
    for($i=0; $i<$len; $i++)
        $r .= chr(rand(0, 25) + ord('a'));
    return $r;
}

/** 
* Returns valid timestamp for mySQL timestamp format
* 
*/
function timestamp($time=null)
{
  $format = 'Y-m-d H:i:s';
  return isset($time) ? date($format, $time) : date($format);
}

/**
 * count words in a text
 */
function word_count($str)
{
     $words = 0;
     $str = str_replace('&nbsp;', '', $str);
     $str = str_replace("\n", ' ', $str);
     $str = eregi_replace(" +", " ", $str);

     $array = explode(" ", $str);
     
     
     for($i=0;$i < count($array);$i++)
     {
         if (eregi("[0-9A-Za-zÀ-ÖØ-öø-ÿ]", $array[$i]))
         {
            $words++;       
         }
           
     }
     return $words;
}

/**
 * normalize strings for web addresses
 */
function slug($str)
{
  $str = str_replace(array('ğ', 'Ğ', 'İ', 'ı', 'ü', 'Ü', 'ş', 'Ş', 'ö', 'Ö', 'ç', 'Ç'), array('g', 'g', 'i', 'i', 'u', 'u', 's', 's', 'o', 'o', 'c', 'c'), $str); 
  
  $str = strtolower(trim($str));
  $str = preg_replace('/[^a-z0-9-]/', '-', $str);
  $str = preg_replace('/-+/', "-", $str);
  return $str;
}

/**
 * check if template exists
 */
function template_exists($name)
{
  return (file_exists(VIEWS.$name)) ? true : false;
}

if(!function_exists('mb_ucwords'))
{
  function mb_ucwords($str)
  {
    $str = mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
    return ($str);
  }
}


/**
 * Returns a calculated ratio
 * a     b
 * c     d=return
 * 
 * if it's b out of a, what it would be out of c
 */
function ratio($a, $b, $c) {
  return ($a == 0) ? 0 : ($b * $c) / $a;
}


/**
 * Reads or returns false a cache
 */
function daily_cache_exists($name)
{
  $filename = DIR_LOGS.$name.strtotime(date('d.m.Y')).'.log';
  
  return (file_exists($filename))
   ? unserialize(file_get_contents($filename))
   : false;
}

/*
 * Creates a cache which is renewed every day
 */
function daily_cache_create($name, $content)
{
  $filename = DIR_LOGS.$name.strtotime(date('d.m.Y')).'.log'; 
  file_put_contents($filename, serialize($content)); 
}