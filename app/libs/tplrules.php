<?php


/*
      This loads additional view into your template.

      USAGE: {{$view_view/path/name}}
*/
$view->addRule('view',function($p) use($view){
   return $view->load($p)->getView();
});





/*
      This collects up your css/js dependencies, so they are available for display after load of page.

      USAGE: {{$assets_assets/path/name:css}}  {{$assets_assets/path/name:js}}

      + use Assets Bootstraper to create collection from $ _SESSION['assets'] array, then DISPLAY IT:

      $route->any('css',function() use ($bootstraper){
          $bootstraper->addCollection('main',$_SESSION['assets']);
          $bootstraper->getStylesheet('main');
      });

      $route->any('js',function() use ($bootstraper){
          $bootstraper->addCollection('main',$_SESSION['assets']);
          $bootstraper->getJavaScript('main');
      });

      WARNING: empty $_SESSION['assets'] array when opening a route, otherwise your assets will get mixed:
      $route->get('/',function() use($view){
            $_SESSION['assets'] = array();
            $view->load('about')->display();
      });
*/

$view->addRule('assets',function($p){
   $key = str_replace('/','-',$_SERVER['REQUEST_URI']);
   array_push($_SESSION['assets'][$key],$p);
});





/*

      This enables frondend developers to flawlessly work with templates and layouts.

      USAGE:
 
       {{$extends_view/path/name}}
             {{$section_sectionNameFromExtendedView}}
                         <p> i will be displayed in section declared above </p>
             {{$sectionEnd_sectionNameFromExtendedView}}

             {{$section_anotherSectionNameFromExtendedView}}
                         <p> i will be displayed in section declared above </p>
             {{$sectionEnd_anotherSectionNameFromExtendedView}}
        {{$display_view/path/name}}


        *NOTE: you CAN nest extended views

*/

$view->addRule('extends',function($p){
 return '
         <?php
          $v = new vibius\core\View();
         $tpls["'.$p.'"] = $v->load("'.$p.'");
          ?>
     ';
});

$view->addRule('display',function($p){
  return '<?php $tpls["'.$p.'"]->vars($data)->display(); ?>';
});

$view->addRule('section',function($p){
  return '<?php  ob_start(); ?>';
});

$view->addRule('sectionEnd',function($p){
  return '<?php $data["'.$p.'"] = ob_get_clean();  ?> ';
});

$view->addRule('addon',function($p){
	$output = file_get_contents(dirname(__DIR__).'/add_ons/'.$p.'.php');
	return $output;
});

$view->addRule('editable',function($p){
  $_SESSION['view_vars'][$p] = true;
  return '<?php  ob_start(); ?>';
});

$view->addRule('editableEnd',function($p){
  return '<?php $data["'.$p.'"] = ob_get_clean();  ?> ';
});