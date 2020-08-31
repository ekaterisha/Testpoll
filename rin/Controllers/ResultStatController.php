<?php namespace Testpoll\rin\Controllers;
class ResultStatController {

    public $model;

    public function __construct(){
        $this->model = new \Testpoll\rin\Models\ResultStatModel();
    }

    public function read(){

        $this->model->getData();
        $dataProvider['user_result'] = isset($this->model->data['user_result']) ? $this->model->data['user_result'] : null;
        $dataProvider['tests_result'] = $this->model->data['tests_result'];
        $dataProvider['one_test_result'] = $this->model->data['one_test_result'];

        return include 'rin/Views/ResultStatView.php';
        
    }
}
   