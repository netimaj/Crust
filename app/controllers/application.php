<?php
/**
* Crust Framework Application Controller
* 
* This controller is the main one. 
* Every requested controller is a chlid of this class.
* But this controller cannot be requested directly.
* 
* @author Ahmet Özışık
*/
abstract class ApplicationController extends t_smarty
{
    protected $controller;
    protected $action;
    protected $parameters;
		protected $helper;
    private   $own_helper;
		
    public function __construct($controller, $action, $parameters)
    {
        // Initialize smarty
        parent::__construct();
        
        $this->assign('base_url', URL);
        
        $this->controller = $controller;
        $this->action     = $action;
        $this->parameters = $parameters;        
				
        // Helper for this CLASS
        $this->own_helper = load_helper('Application');
        // Helper for the current controller
        $this->helper     = load_helper(ucwords($controller), $this);     				
        
        $this->Layout('default');
        $this->assign('controller', $controller);
    }


    public function __destruct()
    {
    	
    	if(defined('404'))
			{
				header('HTTP/1.0 404 Not Found');
				$this->controller = 'errors';
				$this->action 		= '404_not_found';
			}
			else if(defined('500'))
			{
				header('HTTP/1.0 500 Internal Server Error');
				$this->controller= 'errors';
				$this->action = '500_server_error';
			}
				
			
			// no output if requested
			if(defined('NO_TEMPLATE_OUTPUT')) return;
			    	
      echo parent::render($this->controller, $this->action);
    }

    /**
    * Sends POST data to a model's function
    * 
    * @param mixed $model_name
    * @param mixed $function_name
    * @param mixed $form
    * @param mixed $assign_form_on_failure
    */
    public function process_form($model_name, $function_name, $form, $put_key=null, $assign_form_on_failure=true, $assign_error_on_failure=true, $assign_success=true)
    {
        if(empty($form))
        return false;

        if(!class_exists($model_name))
            _debug('Model file not found', $model_name);

        $model = new $model_name();

        if(!method_exists($model, $function_name))
            _debug('Model method not found', $model_name.'::'.$function_name);

        // İşlemi gerçekleştiriyoruz

        $process = (empty($put_key)) ? $model->$function_name($form) : $model->$function_name($form, $put_key);

        if($process !== true)
        {
            if($assign_error_on_failure == true)
                $this->assign('error', $process);
            if($assign_form_on_failure == true)
                $this->assign('form', $form);
        }
        else
            if($assign_success == true)
                $this->assign('success', true);
    }
		
		/**
		 * Search for the given index for a parameter, if not found redirect back to homepage
		 * Can be used for pages that requires a DB table row id for a task 
		 * 
		 * Usage: $id = $this->request_id_in_parameter(0, true);
		 * 
		 * Also can be used for non-numeric values (can be handy for nosql databases that employ string keys)
		 * Usage: $string_key = $this->request_id_in_parameter(0, false);
		 * 
		 * @param mixed $parameter_key
		 * @param boolean $not_numeric
		 * @return mixed parameter content 
		 */
		public function require_id_in_parameter($parameter_key=0, $not_numeric=false)
		{
			if(!isset($this->parameters[$parameter_key]) or empty($this->parameters[$parameter_key]))
				redirect_to('home');
			
			if($not_numeric == false and !is_numeric($this->parameters[$parameter_key]))
				redirect_to('home');
			
			return $this->parameters[$parameter_key];
		}		
		
		/**
		 * Assigns title in smarty to be inserted between <title> </title> tags
		 * 
		 * @param string $title
		 */
		public function set_title($title)
		{
			$this->assign('title', $title);
		}  		
}
?>