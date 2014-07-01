<?php
use vibius\core as core;

$route = new core\Router();
$view = new core\View();
$url = new core\Url();
$db = new core\Db();


require_once 'libs/tplrules.php';

$key = str_replace('/','-',$_SERVER['REQUEST_URI']);
$baseUrl = $url->to('');

$_SESSION['assets'][$key] = array();
$_SESSION['view_vars'] = array();




$view->GlobalVar('uriKey',$key);
$view->GlobalVar('url',$baseUrl);

$db = $db->connect();

$q = $db->prepare('SELECT * FROM routes');
$q->execute();
$allRoutes = $q->fetchAll();

foreach($allRoutes as $r){
  $v = $r['page'];
  $v = preg_replace('/[^a-zA-Z0-9-_\.]/','', $v);
  $route->any($r['route'],function() use ($view,$v){
      $args = func_get_args();
      $c = 0;
      $data = array();
      foreach($args as $arg){
        $data['urlVar'.$c] = $arg;
        $c++;
      }
      $view->load('rpdev/pages/'.$v)->vars(array('data'=>$data))->display();
  });
}


$route->get('css/{$route}',function($route) use($view){
    $bootstraper = new vibius\plugins\AssetsBootstraper();
    $bootstraper->addCollection('main',$_SESSION['assets'][$route]);
    $bootstraper->getStylesheet('main');
});
$route->get('js/{$route}',function($route) use($view){
    $bootstraper = new vibius\plugins\AssetsBootstraper();
    $bootstraper->addCollection('main',$_SESSION['assets'][$route]);
    $bootstraper->getJavascript('main');
});

$route->get('admin/dashboard','adminPages%dashboard');
$route->get('admin/dashboard/content','adminPages%content');
$route->get('admin/dashboard/pages','adminPages%pages');
$route->get('admin/dashboard/layouts','adminPages%layouts');
$route->get('admin/dashboard/styles','adminPages%styles');
$route->get('admin/dashboard/add-ons','adminPages%add_ons');
$route->get('admin/dashboard/preferences','adminPages%preferences');

$route->get('admin/login',function() use($view){
    $_SESSION['vibius_token'] = md5(uniqid(rand(), true));
    $view->GlobalVar('token',$_SESSION['vibius_token']);
    $view->load('rpdev/admin/login')->display();
});

$route->any('admin/action/logout',function() use($baseUrl,$db,$view){
	$_SESSION['loggedIn'] = false;
header('Location: '.$baseUrl.'admin/login');
});

$route->any('admin/action/login',function() use($baseUrl,$db,$view){
	
    if(!isset($_SESSION['vibius_token'])  ){
        header('Location: '.$baseUrl.'admin/login');
    }

    if($_POST['token'] == $_SESSION['vibius_token']){
		
	  $query = $db->prepare('SELECT * FROM users WHERE username=:user');
	  $query->execute(array('user' => $_POST['name']));
      // handle login
	  $results = $query->fetchAll();
		if( empty($results) ){
			  $data['error'] = 'Invalid username/password';
			  $_SESSION['vibius_token'] = md5(uniqid(rand(), true));
      		  $view->GlobalVar('token',$_SESSION['vibius_token']);
			  $view->load('rpdev/admin/login')->vars($data)->display();
			  echo $_SESSION['vibius_token'];
			return;
		}
	  foreach($results as $details){
		  $inputPass = hash('sha256',$_POST['name'].$details['mystery'].$_POST['password']);
		  
		  
		  if( $inputPass == $details['password'] ){
			  $_SESSION['loggedIn'] = true;
			  header('Location: '.$baseUrl.'admin/dashboard');
			  return;
		  }else{
			  $data['error'] = 'Invalid username/password';
			  $_SESSION['vibius_token'] = md5(uniqid(rand(), true));
      		  $view->GlobalVar('token',$_SESSION['vibius_token']);
			  $view->load('rpdev/admin/login')->vars($data)->display();
			  echo $_SESSION['vibius_token'];
			  return;
		  }
	  }
	  
	  
      #$_SESSION['loggedIn'] = true;
      

    }else{
		header('Location: '.$baseUrl.'admin/login');

    }
    $_SESSION['vibius_token'] = md5(uniqid(rand(), true));
});

$route->any('admin/action/register',function() use($baseUrl,$db){
	
	
	if( empty($_GET['username']) || empty($_GET['password']) ){
		echo 'Valid username and password required';
		return;
	}
	
	$user = $_GET['username'];
	$pass = $_GET['password'];
	
	$mystery = uniqid(rand(), true);
	
	$pass = hash('sha256',$user.$mystery.$pass);
	
	$query = $db->prepare('INSERT INTO users (username,password,mystery) VALUES (:user,:pass,:mystery)');
	$query->execute(array(
		':user' => $user,
		':pass' => $pass,
		':mystery' => $mystery,
	));
	
			
	
});


$route->get('vars',function() use($view){
  $_SESSION['view_vars'] = array();
  $view->load('landing')->getView();
  print_r($_SESSION['view_vars']);
});

$route->ajax('admin/ajax/getPages','adminActions%getPages');
$route->ajax('admin/ajax/getfilePages/{$id}','adminActions%getFile');
$route->ajax('admin/ajax/updatePage','adminActions%updateFile');
$route->ajax('admin/ajax/deletePage','adminActions%deleteFile');

$route->ajax('admin/ajax/getLayouts','adminActions%getLayouts');
$route->ajax('admin/ajax/getfileLayout/{$id}','adminActions%getLayout');
$route->ajax('admin/ajax/updateLayout','adminActions%updateLayout');
$route->ajax('admin/ajax/deleteLayout','adminActions%deleteLayout');

$route->ajax('admin/ajax/getLink','adminActions%getLink');

$route->ajax('admin/ajax/getLinks','adminActions%getLinks');

$route->ajax('admin/ajax/updateLink','adminActions%updateLink');
$route->ajax('admin/ajax/deleteLink','adminActions%deleteLink');

$route->ajax('admin/ajax/getStylesheet','adminActions%getStylesheet');
$route->ajax('admin/ajax/getJavascript','adminActions%getJavascript');

$route->ajax('admin/ajax/getfileStyle/{$id}','adminActions%getFileStyle');
$route->ajax('admin/ajax/getfileJavascript/{$id}','adminActions%getFileJavascript');

$route->ajax('admin/ajax/updateScript','adminActions%updateScript');
$route->ajax('admin/ajax/deleteScript','adminActions%deleteScript');

$route->ajax('admin/ajax/updateStyle','adminActions%updateStyle');
$route->ajax('admin/ajax/deleteStyle','adminActions%deleteStyle');

$route->ajax('admin/ajax/getUsers','adminActions%getUsers');
$route->ajax('admin/ajax/updateUsers','adminActions%updateUsers');
$route->ajax('admin/ajax/deleteUsers','adminActions%deleteUsers');

$route->ajax('admin/ajax/getAddOns','adminActions%getAddOns');
$route->ajax('admin/ajax/updateAddOn','adminActions%updateAddOn');
$route->ajax('admin/ajax/deleteAddOn','adminActions%deleteAddOn');
$route->ajax('admin/ajax/getfileAddOn/{$id}','adminActions%getFileAddOn');

$route->ajax('admin/ajax/getEditable','adminActions%getEditable');