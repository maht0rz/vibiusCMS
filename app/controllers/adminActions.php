<?php

class adminActions{

  function __construct(){
    $db = new vibius\core\Db();
    $this->_db = $db->connect();

    if(!isset($_SESSION['loggedIn'])){
          die('Unauthorized operation');
    }else{
      if($_SESSION['loggedIn'] !== true){
          die('Unauthorized operation');
      }
    }


  }

  public function getPages(){
    $query = $this->_db->prepare('SELECT * FROM pages');
    $query->execute();
    $results = $query->fetchAll();
    $end = array();
    foreach($results as $data){
        $end[$data['name']] = $data['file'];
    }
    echo json_encode($end);
  }

  public function getFile($file){

    $nf = preg_replace('/[^a-zA-Z0-9-_\.]/','', $file);
    if(strlen($nf)<1){
      die();
    }
    $f = dirname(__FILE__).'/../views/rpdev/pages/'.$nf.'.tpl.php';
    $query = $this->_db->prepare('SELECT * FROM pages WHERE file=:file');
    $query->execute(array(':file' => $file));
    $results = $query->fetchAll();

    foreach($results as $data){
        $output['fileName'] = $data['file'];
        $output['name'] = $data['name'];
        $output['desc'] = $data['descr'];
    }

    if(file_exists($f)){
      $output['file'] = file_get_contents($f);

    }else{
      $output['file'] = 'File not found';
    }
    echo json_encode($output);
  }

  public function updateFile(){

    $nf = preg_replace('/[^a-zA-Z0-9-_\.]/','', $_POST['file']);
    if(strlen($nf)<1){
      die();
    }
    $f = dirname(__FILE__).'/../views/rpdev/pages/'.$nf.'.tpl.php';
    if(file_exists($f)){
      $handle = fopen($f,'w+');
      if($handle){
        echo 'foundasf';
          fwrite($handle,$_POST['code']);
          fclose($handle);


              $q = $this->_db->prepare('UPDATE pages SET name=:name, descr=:descr WHERE file=:file');
              $q->execute(array('name'=>$_POST['name'],'descr'=>$_POST['desc'],'file'=>$_POST['file']));
      }else{
        echo "handle fail";
      }
    }else{
      $handle = fopen($f,'w+');
      if($handle){
          echo 'found';
            fwrite($handle,$_POST['code']);
            fclose($handle);
        $q = $this->_db->prepare('INSERT INTO pages (name,file,descr) VALUES (:name,:file,:descr)');
        $q->execute(array('name'=>$_POST['name'],'descr'=>$_POST['desc'],'file'=>$nf));
      }
    }
    echo $nf;
    echo "<pre>";
    print_r($_POST);

  }

  public function deleteFile(){

    $nf = preg_replace('/[^a-zA-Z0-9-_\.]/','', $_POST['file']);
    if(strlen($nf)<1){
      die();
    }
    $f = dirname(__FILE__).'/../views/rpdev/pages/'.$nf.'.tpl.php';
    if(file_exists($f)){
      $handle = fopen($f,'w+');
      if($handle){
        echo 'foundasf';
          fwrite($handle,$_POST['code']);
          fclose($handle);


              $q = $this->_db->prepare('DELETE FROM pages WHERE file=:file');
              $q->execute(array('file'=>$_POST['file']));
              unlink($f);
      }
    }
    echo $nf;
    echo "<pre>";
    print_r($_POST);

  }

  public function getLayouts(){
    $query = $this->_db->prepare('SELECT * FROM layouts');
    $query->execute();
    $results = $query->fetchAll();
    $end = array();
    foreach($results as $data){
        $end[$data['name']] = $data['file'];
    }
    echo json_encode($end);
  }

  public function getLayout($file){
    $nf = preg_replace('/[^a-zA-Z0-9-_\.]/','', $file);
    if(strlen($nf)<1){
      die();
    }
    $f = dirname(__FILE__).'/../views/rpdev/layouts/'.$nf.'.tpl.php';

    $query = $this->_db->prepare('SELECT * FROM layouts WHERE file=:file');
    $query->execute(array(':file' => $file));
    $results = $query->fetchAll();

    foreach($results as $data){
        $output['fileName'] = $data['file'];
        $output['name'] = $data['name'];
        $output['desc'] = $data['descr'];
    }

    if(file_exists($f)){
      $output['file'] = file_get_contents($f);

    }else{
      $output['file'] = 'File not found';
    }
    echo json_encode($output);
  }

  public function updateLayout(){

    $nf = preg_replace('/[^a-zA-Z0-9-_\.]/','', $_POST['file']);
    if(strlen($nf)<1){
      die();
    }
    $f = dirname(__FILE__).'/../views/rpdev/layouts/'.$nf.'.tpl.php';
    if(file_exists($f)){
      $handle = fopen($f,'w+');
      if($handle){
        echo 'foundasf';
          fwrite($handle,$_POST['code']);
          fclose($handle);


              $q = $this->_db->prepare('UPDATE layouts SET name=:name, descr=:descr WHERE file=:file');
              $q->execute(array('name'=>$_POST['name'],'descr'=>$_POST['desc'],'file'=>$_POST['file']));
      }else{
        echo "handle fail";
      }
    }else{
      $handle = fopen($f,'w+');
      if($handle){
          echo 'found';
            fwrite($handle,$_POST['code']);
            fclose($handle);
        $q = $this->_db->prepare('INSERT INTO layouts (name,file,descr) VALUES (:name,:file,:descr)');
        $q->execute(array('name'=>$_POST['name'],'descr'=>$_POST['desc'],'file'=>$nf));
      }
    }
    echo $nf;
    echo "<pre>";
    print_r($_POST);

  }

  public function deleteLayout(){

  $nf = preg_replace('/[^a-zA-Z0-9-_\.]/','', $_POST['file']);
  if(strlen($nf)<1){
    die();
  }
    $f = dirname(__FILE__).'/../views/rpdev/layouts/'.$nf.'.tpl.php';
    if(file_exists($f)){
      $handle = fopen($f,'w+');
      if($handle){
        echo 'foundasf';
          fwrite($handle,$_POST['code']);
          fclose($handle);


              $q = $this->_db->prepare('DELETE FROM layouts WHERE file=:file');
              $q->execute(array('file'=>$_POST['file']));
              unlink($f);
      }
    }
    echo $nf;
    echo "<pre>";
    print_r($_POST);

  }

  public function getLinks(){
    $query = $this->_db->prepare('SELECT * FROM routes');
    $query->execute();
    $results = $query->fetchAll();
    $end = array();
    foreach($results as $data){
        $end[$data['route']] = $data['page'];
    }
    echo json_encode($end);
  }

  public function updateLink(){

        $q = $this->_db->prepare('UPDATE routes SET page=:page WHERE route=:route');
        $q->execute(array('page'=>$_POST['view'],'route'=>$_POST['url']));

        $q = $this->_db->prepare('INSERT INTO routes (route,page) VALUES (:route,:page)');
        $q->execute(array('page'=>$_POST['view'],'route'=>$_POST['url']));

  }

  public function deleteLink(){

              $q = $this->_db->prepare('DELETE FROM routes WHERE route=:route');
              $q->execute(array('route' => $_POST['url']));
  }

  public function getLink(){

              $q = $this->_db->prepare('SELECT * FROM routes WHERE route=:route');
              $q->execute(array('route' => $_POST['url']));
              $results = $q->fetchAll();
              $end = array();
              foreach($results as $data){
                  $end[$data['route']] = $data['page'];
              }
              echo json_encode($end);
  }

  public function getStylesheet(){
    $results = scandir(dirname(__DIR__).'/../public/rpdev/custom/css/');
    $unwanted = array('.','..');
    $results = array_diff($results,$unwanted);
    $end = array();
    foreach($results as $key => $value){
        $end[$value] = 'css';
    }
    echo json_encode($end);
  }

  public function getJavascript(){
    $results = scandir(dirname(__DIR__).'/../public/rpdev/custom/js/');
    $unwanted = array('.','..');
    $results = array_diff($results,$unwanted);
    $end = array();
    foreach($results as $key => $value){
        $end[$value] = 'js';
    }
    echo json_encode($end);
  }

  public function getFileStyle($file){
    $nf = preg_replace('/[^a-zA-Z0-9-_\.]/','', $file);
    if(strlen($nf)<1){
      die();
    }
    $f = dirname(__DIR__).'/../public/rpdev/custom/css/'.$nf.'';
      $output['name'] = $nf;
    if(file_exists($f)){
      $output['file'] = file_get_contents($f);

    }else{
      $output['file'] = 'File not found';
    }
    echo json_encode($output);
  }

  public function getFileJavascript($file){
    $nf = preg_replace('/[^a-zA-Z0-9-_\.]/','', $file);
    if(strlen($nf)<1){
      die();
    }
    $f = dirname(__DIR__).'/../public/rpdev/custom/js/'.$nf.'';
$output['name'] = $nf;
    if(file_exists($f)){
      $output['file'] = file_get_contents($f);

    }else{
      $output['file'] = 'File not found';
    }
    echo json_encode($output);
  }

  public function updateScript(){

    $nf = preg_replace('/[^a-zA-Z0-9-_\.]/','', $_POST['file']);
    if(strlen($nf)<1){
      die();
    }
    $f = dirname(__DIR__).'/../public/rpdev/custom/js/'.$nf.'';
    if(file_exists($f)){
      $handle = fopen($f,'w+');
      if($handle){
        echo 'foundasf';
          fwrite($handle,$_POST['code']);
          fclose($handle);
      }else{
        echo "handle fail";
      }
      if($_POST['file'] != $_POST['old_file']){
        unlink(dirname(__DIR__).'/../public/rpdev/custom/js/'.$_POST['old_file']);
      }
    }else{
      $handle = fopen($f,'w+');
      if($handle){
          echo 'found';
            fwrite($handle,$_POST['code']);
            fclose($handle);
      }
    }
    echo $nf;
    echo "<pre>";
    print_r($_POST);

  }

  public function deleteScript(){
    $nf = preg_replace('/[^a-zA-Z0-9-_\.]/','', $_POST['file']);
    unlink(dirname(__DIR__).'/../public/rpdev/custom/js/'.$nf);
    echo "\n dirname(__DIR__).'/../public/rpdev/custom/js/'.$nf";
  }

  public function updateStyle(){

    $nf = preg_replace('/[^a-zA-Z0-9-_\.]/','', $_POST['file']);
    if(strlen($nf)<1){
      die();
    }
    $f = dirname(__DIR__).'/../public/rpdev/custom/css/'.$nf.'';
    if(file_exists($f)){
      $handle = fopen($f,'w+');
      if($handle){
        echo 'foundasf';
          fwrite($handle,$_POST['code']);
          fclose($handle);
      }else{
        echo "handle fail";
      }
      if($_POST['file'] != $_POST['old_file']){
        unlink(dirname(__DIR__).'/../public/rpdev/custom/css/'.$_POST['old_file']);
      }
    }else{
      $handle = fopen($f,'w+');
      if($handle){
          echo 'found';
            fwrite($handle,$_POST['code']);
            fclose($handle);
      }
    }
    echo $nf;
    echo "<pre>";
    print_r($_POST);

  }

  public function deleteStyle(){
    $nf = preg_replace('/[^a-zA-Z0-9-_\.]/','', $_POST['file']);
    unlink(dirname(__DIR__).'/../public/rpdev/custom/css/'.$nf);
    echo "\n dirname(__DIR__).'/../public/rpdev/custom/css/'.$nf";
  }
	
  public function getUsers(){
	  $q = $this->_db->prepare('SELECT * FROM users');
	  $q->execute();
	  $res = $q->fetchAll();
	  foreach ($res as $user){
		  $users[$user['username']] = true;
	  }
	  echo json_encode($users);
  }
	
  public function updateUsers(){
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	  
	if(strlen($pass) > 2){
		$q = $this->_db->prepare('DELETE FROM users WHERE username=:user');
		$q->execute(array(':user' => $_POST['user']));



		$mystery = uniqid(rand(), true);

		$pass = hash('sha256',$user.$mystery.$pass);

		$query = $this->_db->prepare('INSERT INTO users (username,password,mystery) VALUES (:user,:pass,:mystery)');
		$query->execute(array(
			':user' => $user,
			':pass' => $pass,
			':mystery' => $mystery,
		));
	}else{
			echo "updating";
		$query = $this->_db->prepare('UPDATE users SET username=:newuser WHERE username=:user');
		$query->execute(array(
			':user' => $_POST['old_user'],
			':newuser' => $user
		));
	}
	  
	
	  
  }
	
  public function deleteUsers(){
	$user = $_POST['user'];
		$q = $this->_db->prepare('DELETE FROM users WHERE username=:user');
		$q->execute(array(':user' => $_POST['user']));

  }
	
	
    public function getAddOns(){
    $results = scandir(dirname(__DIR__).'/add_ons');
    $unwanted = array('.','..');
    $results = array_diff($results,$unwanted);
    $end = array();
    foreach($results as $key => $value){
        $end[$value] = 'js';
    }
    echo json_encode($end);
  }
	
  public function updateAddOn(){

    $nf = preg_replace('/[^a-zA-Z0-9-_\.]/','', $_POST['file']);
    if(strlen($nf)<1){
      die();
    }
    $f = dirname(__DIR__).'/add_ons/'.$nf.'';
    if(file_exists($f)){
      $handle = fopen($f,'w+');
      if($handle){
        echo 'foundasf';
          fwrite($handle,$_POST['code']);
          fclose($handle);
      }else{
        echo "handle fail";
      }
      if($_POST['file'] != $_POST['old_file']){
        unlink(dirname(__DIR__).'/add_ons/'.$_POST['old_file']);
      }
    }else{
      $handle = fopen($f,'w+');
      if($handle){
          echo 'found';
            fwrite($handle,$_POST['code']);
            fclose($handle);
      }
    }
    echo $nf;
    echo "<pre>";
    print_r($_POST);

  }

  public function deleteAddOn(){
	 
    $nf = preg_replace('/[^a-zA-Z0-9-_\.]/','', $_POST['file']);
    unlink(dirname(__DIR__).'/add_ons/'.$nf);
    echo "\n dirname(__DIR__).'/add_ons/'.$nf";
  }
	
	public function getFileAddOn($file){
    $nf = preg_replace('/[^a-zA-Z0-9-_\.]/','', $file);
    if(strlen($nf)<1){
      die();
    }
    $f = dirname(__FILE__).'/../add_ons/'.$nf.'';


    if(file_exists($f)){
      $output['file'] = file_get_contents($f);

    }else{
      $output['file'] = 'File not fafsound';
    }
    echo json_encode($output);
  }
	
  public function getEditable(){
	  $page = $_POST['page'];
	  
	  $q = $this->_db->prepare('SELECT * FROM pages WHERE name=:descr');
	  $q->execute(array(':descr' => $page));
	  
	  $res = $q->fetchAll();
	  foreach($res as $r){
		  $req = $r['file'];
	  }
	  $_SESSION['view_vars'] = array();
	  $v = new \vibius\core\View();
	  $v->load('rpdev/pages/'.$req)->getView();
	  
	  $vars = $_SESSION['view_vars'];
	  
	  $q = $this->_db->prepare('SELECT * FROM pages');
	  $q->execute(array(':descr' => $page));
	  
	  $stor = scandir(dirname(__DIR__).'/storage/');
	  $arr = array();
	  $res = $q->fetchAll();
	  foreach($res as $r){
		  array_push($arr,$r['file'].'.php');
	  }
	  
	  $unwanted = array('.','..');
	  $stor = array_diff($stor,$unwanted);
	  
	  foreach($stor as $con){
		  if( !in_array($con,$arr)){
			  unlink(dirname(__DIR__).'/storage/'.$con);
		  }
	  }
	  
	  foreach($vars as $k => $v){
			$storage[$k] = 'Not edited yet.';	  
	  }
	  
	  foreach($vars as $var){
		  $f = dirname(__DIR__).'/storage/'.$req.'.php';
		  if(file_exists($f)){
			  $storag = require $f;
		  }else{
			  $handle = fopen($f,'w+');
			  
			  if($handle){
				  fwrite($handle, '<?php return '.var_export($storage,true).';');
				  fclose($handle);
			  }
			  $storag = require $f;
		  }
	  }
	  
	  echo json_encode($storag);
  }
	

}
