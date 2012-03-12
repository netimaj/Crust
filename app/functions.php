<?php
  
function _404()
{
  require ROOT.'public/404.php'; 
  exit;
}

function _debug($message, $param)
{
  require ROOT.'public/debug.php';
  exit;
}

function _500()
{
  require ROOT.'public/500.php';
  exit;
}

function redirect_to($request, $to_referer=false)
{
  header(($to_referer) ? 'Location: '.$_SERVER['HTTP_REFERER'] : 'Location: '.URL.$request);
  exit;
}

function load_helper($helper_name)
{
  $file_name = DIR_HELPERS.strtolower($helper_name).'.helper.php';    
  if(!file_exists($file_name))
    return null;

  include($file_name);
  $class_name = $helper_name.'Helper';
  
  if(!class_exists($class_name))
    return null;
  
  return new $class_name();
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
* Mail gönderir
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
* Sends a portal message to a user
* 
* @param mixed $receiver_id
* @param mixed $subject
* @param mixed $message
*/
function send_system_message($receiver_id, $subject, $message)
{
  $messages = new Messages();
  $messages->send_message($receiver_id, '-1', array('subject'=>$subject,'message'=>$message));
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
* Bir JSON dosyasını array olarak döndürür
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
* Verilen ayar ismiyle veritabanından o ayarın değerini çeker
* 
* @param mixed $config_name
*/
function get_config($config_name)
{
  if(empty($config_name)) return false;
  $config_name = strip_tags($config_name);
  $config_name = addslashes($config_name);
  
  $settings = new Settings();
  $value    = $settings->get_setting($config_name);
  
  return (!empty($value)) ? $value : false;
}

/**
* Kafasından şifre üretir
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

/** mysql timestamp döndrürür
* 
* 
*/
function timestamp($time=null)
{
  $format = 'Y-m-d H:i:s';
  return isset($time) ? date($format, $time) : date($format);
}


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


function slug($str)
{
  $str = str_replace(array('ğ', 'Ğ', 'İ', 'ı', 'ü', 'Ü', 'ş', 'Ş', 'ö', 'Ö', 'ç', 'Ç'), array('g', 'g', 'i', 'i', 'u', 'u', 's', 's', 'o', 'o', 'c', 'c'), $str); 
  
  $str = strtolower(trim($str));
  $str = preg_replace('/[^a-z0-9-]/', '-', $str);
  $str = preg_replace('/-+/', "-", $str);
  return $str;
}

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
 * Bir doğru orantı denklemi çözer
 * a     b
 * c     d=return
 */
function dogru_oranti($a, $b, $c) {
  return ($a == 0) ? 0 : ($b * $c) / $a;
}


/**
 * Her gün yenilenen bir cache yaratır
 */
function daily_cache_exists($name)
{
  $filename = DIR_LOGS.$name.strtotime(date('d.m.Y')).'.log';
  
  return (file_exists($filename))
   ? unserialize(file_get_contents($filename))
   : false;
}

function daily_cache_create($name, $content)
{
  $filename = DIR_LOGS.$name.strtotime(date('d.m.Y')).'.log'; 
  file_put_contents($filename, serialize($content)); 
}