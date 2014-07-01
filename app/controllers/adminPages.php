<?php

class adminPages{

  function __construct(){

    $this->_view = new vibius\core\View();
    $this->_db = new vibius\core\Db();
    $this->_db = $this->_db->connect();
    $_SESSION['vibius_token'] = md5(uniqid(rand(), true));
    $this->_view->GlobalVar('token',$_SESSION['vibius_token']);
    if(!isset($_SESSION['loggedIn'])){
          $this->_view->load('rpdev/admin/login')->display();
          die();
    }else{
      if($_SESSION['loggedIn'] !== true){
          $this->_view->load('rpdev/admin/login')->display();
          die();
      }
    }


  }

  public function dashboard(){
    $q = $this->_db->prepare('SELECT * FROM pages');
    $q->execute();
    $data = $q->fetchAll();
    $this->_view->load('rpdev/admin/links')->vars(array('layouts'=>$data))->display();
  }

  public function pages(){
    $this->_view->load('rpdev/admin/dashboard')->display();
  }

  public function styles(){
    $this->_view->load('rpdev/admin/styles')->display();
  }

  public function layouts(){
    $this->_view->load('rpdev/admin/layouts')->display();
  }

  public function preferences(){
    $this->_view->load('rpdev/admin/preferences')->display();
  }
	
  public function add_ons(){
    $this->_view->load('rpdev/admin/add_ons')->display();
  }
	
}
