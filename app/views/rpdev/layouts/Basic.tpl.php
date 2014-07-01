<link rel="stylesheet" type="text/css" href="{{$url}}css/{{$uriKey}}">
<h1>Welcome to my website</h1>
<p>{{$content}}</p>

<?php
   echo (round((microtime(true) - $GLOBALS['execution_time']) * 1000, 2)).' ms';
?>

<script src="{{$url}}js/{{$uriKey}}"></script>
