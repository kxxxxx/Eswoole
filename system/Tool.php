<?php
namespace system;

class Tool{
    static function checkE($val,$defalut,$key=''){
        if(!empty($key)){
            if(empty($val[$key])){
                return $defalut;
            }
            return $val[$key];
        }
        if(empty($val)){
            return $defalut;
        }
        return $val;
    }
    static function checkI($val,$defalut,$key=''){
        if(!empty($key)){
            if(isset($val[$key])){
                return $defalut;
            }
            return $val[$key];
        }
        if(isset($val)){
            return $defalut;
        }
        return $val;
    }
}