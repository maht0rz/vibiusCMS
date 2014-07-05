{{$assets_rpdev/css/bootstrap::css}}
{{$assets_rpdev/css/magic::css}}
{{$assets_rpdev/css/admin::css}}
<!DOCTYPE html>
<html>
  <head>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,400italic,500italic,500,700,700italic,900,900italic,300,300italic,100italic,100&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="{{$url}}rpdev/codemirror/lib/codemirror.css">
    <link rel="stylesheet" type="text/css" href="{{$url}}css/{{$uriKey}}">
  </head>
  <body class="loginpage">
    <div class="container">
        <div class="row">
            <div class="col-xs-4 col-xs-offset-4 loginbox">
                <div class="inner">
                    <form action="{{$url}}admin/action/login" method="post">
                        <?php
                          if(!empty($error)){
                        ?>
                        <div class="cs-alert">
                            {{$error}}
                        </div>
                        <?php
                          }
                        ?>
                        <p>Username</p>
                        <input placeholder="Your Name" name="name" type="text" class="form-control">
                        <p style="margin-top:10px">Password</p>
                        <input placeholder="**********" type="password" name="password" class="form-control">
                        <input type="hidden" name="token" value="{{$token}}">
                        <input type="submit" class="form-control loginbtn trans" value="Sign in">
                    </form>
                </div>
            </div>
        </div>
    </div>
  </body>
</html>
