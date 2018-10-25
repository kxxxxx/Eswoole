<?php
namespace system;

class route
{
    private static $static_route;
    private function __construct()
    {
        
    }
    static function instance(){
        if(empty(self::$static_route)){
            self::$static_route = new static();
        }
        return self::$static_route;
    }
    function to($request,$response){
        $uri_str = $request->server['request_uri'];
        $temp_data= explode('/',$uri_str);
        if(count($temp_data)<3){
            return "url not complete";
        };
        $class_name = "\\app\\controle\\".$temp_data[1];
        $function_name = $temp_data[2];
        
        if(!class_exists($class_name)){
            return "class not exist";
        }
        $obj = new $class_name;
        if(!method_exists($obj,$function_name)){
            return "function not exist";
        }
        $obj_temp = new \stdClass;
        $obj_temp->post = &$request->post;
        $obj_temp->get = &$request->get;
        $obj_temp->header = &$request->header;
        $obj_temp->server = &$request->server;
        if(method_exists($request,'rawContent')){
            $obj_temp->rawContent = $request->rawContent();
        }else{
            $obj_temp->rawContent = &$request->rawContent;
        }
        $obj->init($obj_temp);
        if($obj->carried_out_status){
            return ['status'=>500,'msg'=>'未经授权访问'];
        }
        return $obj->$function_name($obj_temp,$response);
    }
}