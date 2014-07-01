{{$extends_rpdev/admin/layouts/basic}}

  {{$section_sidebar}}
    <li class="hassub"><a href="{{$url}}admin/dashboard"><span class="fa fa-sitemap faicon"></span>Links</a></li>
    <li class="hassub "><a href="{{$url}}admin/dashboard/pages"><span class="fa fa-file-o faicon"></span>Pages</a></li>
    <li class="hassub "><a href="{{$url}}admin/dashboard/layouts"><span class="fa fa-cubes faicon"></span>Layouts</a>
    </li>
    <li ><a href="{{$url}}admin/dashboard/styles"><span class="fa fa-tags faicon"></span>Assets Manager</a></li>
    <li class="active"><a href="{{$url}}admin/dashboard/add-ons"><span class="fa fa-puzzle-piece faicon"></span>Add-ons</a></li>
    <li><a href="{{$url}}admin/dashboard/preferences"><span class="fa fa-cog faicon"></span>Preferences</a></li>
  {{$sectionEnd_sidebar}}

{{$section_file}}

  <div class="col-xs-4 " style="margin-top:14px">
    <p><b>File:</b> <span id="curfile"></span></p>
  </div>

  <div class="col-xs-2 pull-right usr-acc">
    <a href="#" class="cs-btn trans" id="save"><span class="fa fa-save faicon"></span><span class="mtext">Save changes</span></a>
  </div>
  <div class="col-xs-1 pull-right usr-acc closeit">
    <a href="#" class="cs-btn trans" id="delete"><span class="fa fa-times faicon"></span><span class="mtext"></span></a>
  </div>
{{$sectionEnd_file}}

{{$section_cnav}}

  <div class="col-xs-12 content-nav">
    <div class="con-nav">
      <form action="#" method="post">
          <div class="row" style="margin:0;">
              <div class="col-xs-6">
                  <p>Asset name</p>
                  <input id="pagename" type="text" class="form-control" placeholder="My new asset">
              </div>
          </div>
      </form>
    </div>
  </div>

{{$sectionEnd_cnav}}


  {{$section_javascript}}
    <script>
        var editor = ace.edit("codebox");
        editor.setTheme("ace/theme/monokai");
        editor.getSession().setMode("ace/mode/css");



    </script>
    <script>
    current_file = false;
    old_file = '';
    editor.getSession().setValue('');
    $('#pagedescription').val('');
    $('#pagename').val('');
    function getPages(){

        $('#fileTree').html('');
      $.get("{{$url}}admin/ajax/getAddOns",function(data,status){
        console.log(data);
           json = jQuery.parseJSON(data);

           $('#fileTree').append(
             '<li><a id="add-new-stylesheet" href="#"><span class="fa fa-plus faicon"></span><span class="mtext">Add new add-on</span></a></li>'
           );

           delete json.url;
           $.each( json, function( key, val ) {

              $('#fileTree').append( '<li class="folder-styles" style="display:none" data-url="'+key+'" data-file="'+val+'"><a  href="#"><span class="fa fa-file-o faicon"></span><span class="removefile" style="color:#333" class="fa fa-times pull-right"></span><span class="mtext">'+key+'</span></a></li>' );

          });
          $('.folder-styles').fadeIn();
        $('.folder-styles').on('live click', function(){
post_type = 'css';
            file = $(this).data('url');
			$('#curfile').text(file);
            console.log(file);
			$('#pagename').val(file);
            $('.folder-pages').removeClass('menu-selected');
            $('.folder-styles').removeClass('menu-selected');
            $(this).addClass('menu-selected');
            $.get("{{$url}}admin/ajax/getfileAddOn/"+file,function(data,status){
              console.log(data);
              json = jQuery.parseJSON(data);
              current_file = json.fileName;
              
              editor.getSession().setMode("ace/mode/php");
              editor.getSession().setValue(json.file);
current_file = $('#pagename').val();
            });
        });
        $('#add-new-stylesheet').on('live click', function(){
          post_type = 'css';
            current_file = false;
            old_file = '';
            editor.getSession().setValue('');
            $('#pagedescription').val('');
            $('#pagename').val('');
            $('#curfile').text('');
        });
      });



    }
      $(document).ready(function(){
        getPages();
          $('.folder').siblings('ul').hide();
          $('.folder').click(function(){
            $(this).children('a').toggleClass('menu-selected');
            $(this).children('a').children('span.mtext').children('span.crt').toggleClass('fa-angle-down');
            $(this).next('ul').slideToggle();
          });



          var navHeight = $('.content-nav').height();
          var docHeight = $(document).height();
          $('#codebox').height(docHeight-navHeight-50);

          $('#save').click(function(){
              var code = editor.getSession().getValue();
              
			  post_url = "{{$url}}admin/ajax/updateAddOn";
               $.post(post_url,
                {
                  code:code,
                  file:$('#pagename').val(),
                  old_file:current_file,
                  desc:$('#pagedescription').val(),
                  name:$('#pagename').val()
                },function(data){
                  console.log(data);
                });
              console.log(current_file + "\n" + code + $('#pagename').val() + $('#pagedescription').val());
              getPages();
          });

          $('#delete').click(function(){
              var code = editor.getSession().getValue();
              if(!current_file){
                current_file = $('#pagename').val();
              }
              if(post_type == 'js'){
                var post_url = "{{$url}}admin/ajax/deleteScript";
              }else{
                var post_url = "{{$url}}admin/ajax/deleteStyle";
              }
			  post_url = "{{$url}}admin/ajax/deleteAddOn";
               $.post(post_url,
                {
                  code:code,
                  file:current_file,
                  old_file:$('#curfile').text(),
                  desc:$('#pagedescription').val(),
                  name:$('#pagename').val()
                },function(data){
                  console.log(data);
                });
              console.log(current_file + "\n" + code + $('#pagename').val() + $('#pagedescription').val());
              getPages();
          });

      })
    </script>
  {{$sectionEnd_javascript}}

{{$display_rpdev/admin/layouts/basic}}
