<?php namespace Testpoll\rin\Controllers;
class TestingController {

    public $model;

    public function __construct(){
        $this->model = new \Testpoll\rin\Models\TestingModel();
    }
   
    public function read(){

        $this->model->getData();
        $dataProvider = $this->model->data;

        return include 'rin/Views/TestingView.php';
    }

    public function create(){
        $this->model->setData();
        header("Location: index.php?r=ResultStat&test_id=".$this->model->test_id."&user_id=".$this->model->user_id);
    }

    public function check(){
        $this->model->checkData();
    }


}