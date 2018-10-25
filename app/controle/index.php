<?php
namespace app\controle;
use system\Tool;
use system\BaseControle;
use app\model\IndexModel;

class index extends BaseControle{
    public function index(){
        $data = IndexModel::get()->index();
        return $data;
    }
}
