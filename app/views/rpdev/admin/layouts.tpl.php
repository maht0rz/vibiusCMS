{{$extends_rpdev/admin/layouts/basic}}

  {{$section_sidebar}}
    <li class="hassub"><a href="{{$url}}admin/dashboard"><span class="fa fa-sitemap faicon"></span>Links</a></li>
    <li class="hassub "><a href="{{$url}}admin/dashboard/pages"><span class="fa fa-file-o faicon"></span>Pages</a></li>
    <li class="hassub active"><a href="{{$url}}admin/dashboard/layouts"><span class="fa fa-cubes faicon"></span>Layouts</a>
    </li>
    <li><a href="{{$url}}admin/dashboard/styles"><span class="fa fa-tags faicon"></span>Assets Manager</a></li>
    <li><a href="{{$url}}admin/dashboard/add-ons"><span class="fa fa-puzzle-piece faicon"></span>Add-ons</a></li>
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
                  <p>Page name</p>
                  <input id="pagename" type="text" class="form-control" placeholder="My new page">
              </div>
              <div class="col-xs-6">
                  <p>Page description</p>
                  <input id="pagedescription" type="text" class="form-control" placeholder="My new page description">
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
        editor.getSession().setMode("ace/mode/php");



    </script>
    <script>
    current_file = false;
    old_file = '';
    editor.getSession().setValue('');
    $('#pagedescription').val('');
    $('#pagename').val('');
    function getPages(){
      $.get("{{$url}}admin/ajax/getLayouts",function(data,status){
           json = jQuery.parseJSON(data);
           $('#fileTree').html('');
           $('#fileTree').append(
             '<li><a id="add-new" href="#"><span class="fa fa-plus faicon"></span><span class="mtext">Add new layout</span></a></li>'
           );

           delete json.url;
           $.each( json, function( key, val ) {

              $('#fileTree').append( '<li class="folder-pages" style="display:none" data-url="'+key+'" data-file="'+val+'"><a  href="#"><span class="fa fa-file-o faicon"></span><span class="removefile" style="color:#333" class="fa fa-times pull-right"></span><span class="mtext">'+key+'</span></a></li>' );

          });
          $('.folder-pages').fadeIn();
        $('.folder-pages').on('live click', function(){
             file = $(this).data('file');
            old_file = $(this).data('url');
            $('.folder-pages').removeClass('menu-selected');
            $(this).addClass('menu-selected');
            $('#curfile').text($(this).data('file'));
            $.get("{{$url}}admin/ajax/getfileLayout/"+file,function(data,status){
              console.log(data);
              json = jQuery.parseJSON(data);
              current_file = json.fileName;
              $('#pagename').val(json.name);
              $('#pagedescription').val(json.desc);
              editor.getSession().setValue(json.file);

            });
        });
        $('#add-new').on('live click', function(){
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
              if(!current_file){
                current_file = $('#pagename').val();
              }
              $('#curfile').text(current_file);
               $.post("{{$url}}admin/ajax/updateLayout",
                {
                  code:code,
                  file:current_file,
                  old_file:old_file,
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
              $('#curfile').text(current_file);
               $.post("{{$url}}admin/ajax/deleteLayout",
                {
                  code:code,
                  file:current_file,
                  old_file:old_file,
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
