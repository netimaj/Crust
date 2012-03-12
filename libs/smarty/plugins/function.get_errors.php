<?php

function smarty_function_get_errors($params, $template)
{
  $fields = array('email'           => 'Eposta', 
                  'password'        => 'Şifre', 
                  'password_retype' => 'Şifre Tekrar',
                  'name'    => 'İsim',
                  'lastname' => 'Soyisim',
                  'mobilephone' => 'Cep Telefonu',
                  'city_id' => 'Şehir',
                  'district_id' => 'Semt',
                  'address' => 'Adres',
                  'cooking_cat_id' => 'Kategori',
                  'cuisine_id' => 'Mutfak',
                  'served_in' => 'Hazırlama Süresi',
                  'unit_id' => 'Birim',
                  'price' => 'Fiyat',
                  'delivery_type' => 'Servis Biçimi'
                  );
  
  $errors = array('notblank'  => 'alanını doldurun.'
                 ,'notnull'   => 'alanını doldurun.'
                 ,'regexp'    => 'geçerli değil.'
                 ,'minlength' => 'çok kısa.'
                 ,'unique'    => 'başka biri tarafından alınmış.'
                 ,'email'     => 'geçersiz.'
                 ,'type'      => 'geçersiz.'
                 ,'wrong'     => 'uyuşmuyor'
                 ,'length'    => 'fazla uzun.'
                 );
  
  $error_stack = $template->getTemplateVars('error');
  
  if(empty($error_stack))
  {
    if(!isset($params['no_br']))
      echo '<br />';
      
    return;
  }
  
  if(is_string($error_stack))
  {
    echo '<div class="error"><ul><li>'.$error_stack.'</li></ul></div>';
    return;
  }
  
  $error_array = ((is_object($error_stack))) ? $error_stack->toArray() : $error_stack;
  $error_count = count($error_array);
  

  echo '<div class="error">';
  echo $error_count.' hata oluştu';
  echo '<ul>';
  foreach($error_array as $k => $v)
  {                  
    $f = ($fields[$k]) ? $fields[$k] : $k;
    echo '<li>'.$f.' '.$errors[$v[0]].'</li>';
  }
  echo '</ul></div>';

} 

?>