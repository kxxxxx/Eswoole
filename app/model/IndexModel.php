<?php
namespace app\model;
use system\BaseModel;

class IndexModel extends BaseModel{
    function index(){
        $data = $this->use('db')->select('news_data',['title'],['LIMIT'=>10]);
        return $data;
    }
}