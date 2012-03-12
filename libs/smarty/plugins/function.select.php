<?php

function smarty_function_select($params, &$smarty)
{
  if(!isset($params['json']) and !isset($params['array'])) return false;
  $id   = ($params['id']) ? $params['id'] : $params['name'];
  //$type = ($params['type']) ? $params['type'] : 'text';
  $name = ($params['form']) ? $params['form'].'['.$params['name'].']' : 'form['.$params['name'].']';
  
  
  if(isset($params['json']))
  {
    $json_file = JSON_PATH.$params['json'].'.json';
    $json      = file_get_contents($json_file);
    $array     = json_decode($json, true);
  }
  else
  {
    $array = $params['array'];
    
  }
  // 0 = a, arg = a
  //$keys      = ($params['keys']) ? array_keys($array) : $array;
  
  $html      = '<select name="'.$name.'" id="'.$id.'"';
  if($params['class']) $html .= ' class="'.$params['class'].'"';
  $html     .= '>';
  
  $value     = $smarty->getVariable('form');
  $value     = $value->value[$params['name']];
  
  $html .= ($value == '') ? '<option value="" disabled="disabled" selected="selected">'.l_(array('l' => 'Please select')).'</option>' : '<option value="" disabled="disabled">'.l_(array('l' => 'Please select')).'</option>';
  
  foreach($array as $v)
  {
    
    $html .= '<option value="'.$v.'"';
    if($value == $v)
      $html .= ' selected="selected"';
      
    $html .= (!$params['item']) ? '>'.$v.'</option>' : '>'.$v[$params['item']].'</option>';
  }
  
  $html .= '</select>';
  return $html;
}
    
    
 
?>
