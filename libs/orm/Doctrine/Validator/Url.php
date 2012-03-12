<?php
/**
 * @author      Ahmet Özışık <aozisik@yahoo.com>
 */
class Doctrine_Validator_Url extends Doctrine_Validator_Driver
{
    /**
     * checks if given value is valid url
     *
     * @param mixed $value
     * @return boolean
     */
    public function validate($value)
    {
      if(empty($value)) return true;
      return filter_var($value, FILTER_VALIDATE_URL);
    }
}