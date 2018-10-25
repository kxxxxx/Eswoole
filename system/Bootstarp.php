<?php
namespace system;
use system\Tool;
// \Swoole\Runtime::enableCoroutine();
class bootstrap {
    protected $server;
    protected $server_info;
    function __construct($config){
        spl_autoload_register([$this,'autoload']);
        $this->config_init($config);
        $this->server = new \swoole_websocket_server($this->server_info['host'], $this->server_info['port']);
        $this->server->on('request',[$this,'request']);
        $this->server->on('open',[$this,'open']);
        $this->server->on('message',[$this,'message']);
        $this->server->start();
    }
    function autoload($class){
        $path = PATH.'/' . str_replace("\\", "/", $class) . '.php';
        if (file_exists($path)) {
            require $path;
            return true; 
        } 
        return false; 
    }
    function config_init($config){
        $this->server_info['port'] = Tool::checkE($config,9602,'port');
        $this->server_info['host'] = Tool::checkE($config,"127.0.0.1",'host');
        $this->server_info['allow_origin'] = Tool::checkE($config,[],'allow_origin');
    }
    function request($request, $response){
        if($request->server['request_uri']==='/reload'){
            $this->server->reload();
            $response->end('success');
        }
        $back_string = Route::instance()->to($request,$response);
        if(is_array($back_string)){
            $back_string = json_encode($back_string);
        }
        if(is_bool($back_string)){
            $back_string = "system error";
        }
        $response->header('charset','utf-8',true);
        if(!empty($request->header['origin'])&&in_array($request->header['origin'],$this->server_info['allow_origin'])){
            $response->header("Access-Control-Allow-Origin",$request->header['origin'],true);
            $response->header("Access-Control-Allow-Headers","token",true);
        }
        $response->end($back_string);
    }
    function open(){

    }
    function message(\swoole_websocket_server $server, $frame){
        $data = json_decode($frame->data,true);
        if(!Tool::checkE($data,false)||!Tool::checkE($data,false,'url')){
            $back_string = false;
        }else{
            $request = new \stdClass;
            $request->get = Tool::checkE($data,[],'get');
            $request->post = Tool::checkE($data,[],'post');
            $request->header = Tool::checkE($data,[],'header');
            $request->server = [];
            $request->server['request_uri'] = $data['url'];
            $request->rawContent = $frame->data;
            $request->websocket = ['server'=>$this->server,'frame'=>$frame];
            $back_string = Route::instance()->to($request,[]);
        }
        if(is_array($back_string)){
            $back_string = ['status'=>200,'method'=>$data['url'],'callback'=>$back_string];
        }
        if(is_bool($back_string)){
            $back_string = ['status'=>500,'msg'=>"system error"];
        }
        $back_string = json_encode($back_string);
        $this->server->push($frame->fd,$back_string);
    }
}