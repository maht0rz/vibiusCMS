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
  <body>

    <div class="col-xs-1 sidebar">
      <ul class="sidebar-menu">
        {{$sidebar}}

      </ul>
    </div>


    <div class="col-xs-11 content">
      <div class="col-xs-12  navbar-custom">
        <div class="row">
          <div class="col-xs-2 usr-acc">
            <a href="{{$url}}admin/action/logout" class="cs-btn trans"><span class="fa fa-power-off faicon"></span><span class="mtext">Logout</span></a>
          </div>
          {{$file}}
        </div>
      </div>
      <div class="row fullheight" style="margin-right:0;margin-left:0px;">
          <div class="col-xs-2 sub-sidebar " id="con-sidebar">
              <ul class="sidebar-menu sub-sidebar-menu " id="fileTree">



              </ul>
          </div>
          <div class="col-xs-10" style="padding:0px;padding-top:50px;height:100%;">
            {{$cnav}}
              <div class="col-xs-12 codebox" id="codebox" style="height:50%">

              </div>
          </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js" style="text/javascript"></script>
    <script src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js" style="text/javascript"></script>
    <script src="{{$url}}js/{{$uriKey}}"></script>

    <script src="{{$url}}rpdev/editor/ace.js" type="text/javascript" charset="utf-8"></script>
    {{$javascript}}
  </body>
</html>
