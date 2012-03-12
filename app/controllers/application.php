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
    
    private $own_helper;
		


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
        $this->helper     = load_helper(ucwords($controller));     				
        
        $this->Layout('default');
        $this->assign('controller', $controller);
    }  

    public function __destruct()
    {
        echo parent::render($this->controller, $this->action);
    }

    /**
    * Form işlemesini yapar
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
            _debug('Model bulunamadı', $model_name);

        $model = new $model_name();

        if(!method_exists($model, $function_name))
            _debug('Model metodu bulunamadı', $model_name.'::'.$function_name);

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
}
?>