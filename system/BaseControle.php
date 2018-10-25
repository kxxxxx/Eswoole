<?php
namespace system;
use system\Tool;

class BaseControle{
    public $post = [];
    public $get = [];
    public $header = [];
    public $server = [];
    public $rawContent = [];
    public $carried_out_status = false;
    function init($obj){
        $this->post = &$obj->post;
        $this->get = &$obj->get;
        $this->header = &$obj->header;
        $this->server = &$obj->server;
        $this->rawContent = &$obj->rawContent;
    }
    function post($key,$default=''){
        return Tool::checkE($this->post[$key],$default);
    }
    function get($key,$default=''){
        return Tool::checkE($this->post[$key],$default);
    }
}
