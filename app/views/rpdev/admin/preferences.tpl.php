{{$extends_rpdev/admin/layouts/basic}}

  {{$section_sidebar}}
    <li class="hassub"><a href="{{$url}}admin/dashboard"><span class="fa fa-sitemap faicon"></span>Links</a></li>
    <li class="hassub "><a href="{{$url}}admin/dashboard/pages"><span class="fa fa-file-o faicon"></span>Pages</a></li>
    <li class="hassub "><a href="{{$url}}admin/dashboard/layouts"><span class="fa fa-cubes faicon"></span>Layouts</a>
    </li>
    <li><a href="{{$url}}admin/dashboard/styles"><span class="fa fa-tags faicon"></span>Assets Manager</a></li>
    <li><a href="{{$url}}admin/dashboard/add-ons"><span class="fa fa-puzzle-piece faicon"></span>Add-ons</a></li>
    <li class="active"><a href="{{$url}}admin/dashboard/preferences"><span class="fa fa-cog faicon"></span>Preferences</a></li>
  {{$sectionEnd_sidebar}}

{{$section_file}}

  

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
                  <p>Username:</p>
                  <input id="pagename" type="text" class="form-control" placeholder="John">
              </div>
              <div class="col-xs-6">
                  <p>Password:</p>
                  <input id="pagedescription" type="text" class="form-control" placeholder="**********">
              </div>
          </div>
      </form>
    </div>
  </div>

{{$sectionEnd_cnav}}


  {{$section_javascript}}
    <script>
    current_file = false;
    old_file = '';
    $('#pagedescription').val('');
    $('#pagename').val('');
    function getPages(){
      $.get("{{$url}}admin/ajax/getUsers",function(data,status){
          console.log(data); 
		  json = jQuery.parseJSON(data);
           $('#fileTree').html('');
           $('#fileTree').append(
             '<li><a id="add-new" href="#"><span class="fa fa-plus faicon"></span><span class="mtext">Add new user</span></a></li>'
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
              $('#pagename').val(old_file);
              $('#pagedescription').val('');
        });
        $('#add-new').on('live click', function(){
            current_file = false;
            old_file = '';
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
               $.post("{{$url}}admin/ajax/updateUsers",
                {
                  pass:$('#pagedescription').val(),
                  user:$('#pagename').val(),
				  old_user:old_file
                },function(data){
                  console.log(data);
                });
              getPages();
          });

          $('#delete').click(function(){
              $('#curfile').text(current_file);
               $.post("{{$url}}admin/ajax/deleteUsers",
                {
                  user:$('#pagename').val()
                },function(data){
                  console.log(data);
                });
              getPages();
          });

      })
    </script>
  {{$sectionEnd_javascript}}

{{$display_rpdev/admin/layouts/basic}}
