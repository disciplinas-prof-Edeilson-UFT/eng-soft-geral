<?php 
namespace src\controllers\site;
use src\controllers\BaseController;

include_once __DIR__ . '/../base-controller.php';

class FeedController extends BaseController{
    public function show(){

        $this->view('feed');
    }
}