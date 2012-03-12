<?php
/**
* t_smarty.php
*/
class t_smarty
{

  private $smarty = null;
  private $path   = null;
  public  $layout = null;

  public function __construct()
  {
    $this->smarty = new Smarty(); 
    $this->smarty->compile_dir  = T_COMPILE;
    $this->smarty->cache_dir    = T_CACHE;
    $this->smarty->template_dir = VIEWS;
    $this->assign('url', URL);
  
  }

  public function assign($name, $value)
  {
    $this->smarty->assign($name, $value);
  }
  
  public function Layout($name)
  {
    $this->layout = 'layouts/'.$name.'.tpl';
  }
  
  public function render($controller, $name)
  {
    $this->smarty->assign('yield', $this->smarty->fetch($controller.'/'.$name.'.tpl'));
    $this->smarty->display($this->layout);
  }
  
  public function fetch($file)
  {
    return $this->smarty->fetch($file);
  }
    
}
?>
