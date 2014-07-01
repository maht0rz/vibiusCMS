{{$extends_rpdev/admin/layouts/basic}}

  {{$section_sidebar}}
    <li class="hassub active"><a href="{{$url}}admin/dashboard"><span class="fa fa-sitemap faicon"></span>Links</a></li>
    <li class="hassub "><a href="{{$url}}admin/dashboard/pages"><span class="fa fa-file-o faicon"></span>Pages</a></li>
    <li class="hassub"><a href="{{$url}}admin/dashboard/layouts"><span class="fa fa-cubes faicon"></span>Layouts</a>
    </li>
    <li><a href="{{$url}}admin/dashboard/styles"><span class="fa fa-tags faicon"></span>Assets Manager</a></li>
    <li><a href="{{$url}}admin/dashboard/add-ons"><span class="fa fa-puzzle-piece faicon"></span>Add-ons</a></li>
    <li><a href="{{$url}}admin/dashboard/preferences"><span class="fa fa-cog faicon"></span>Preferences</a></li>
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


    <div class="col-xs-12 content-nav" >
      <div class="con-nav">
        <form action="#" method="post">
            <div class="row" style="margin:0;">
                <div class="col-xs-6">
                    <p>Link URL</p>
                    <input id="pagename" type="text" class="form-control" placeholder="My new page">
                </div>
                <div class="col-xs-6">
                    <p>Page attached</p>
                    <select id="pagepage" class="form-control" style='border:none'>
                    <?php foreach($layouts as $layout){ ?>

                          <option value="<?=$layout['name']?>"><?=$layout['name']?></option>

                    <?php } ?>
                  </select>
                </div>
            </div>
        </form>
      </div>
    </div>
	
	 <div class="col-xs-12 " id="codebox" >
        <form action="#" method="post" id="editables">
            
        </form>
    </div>

  {{$sectionEnd_cnav}}

  {{$section_javascript}}
<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
    <script>
    current_file = false;
    old_file = '';
    $('#pagename').val('');
		function inittiny(){
			tinymce.init({selector:'textarea'});
		}
    function getPages(){
      $.get("{{$url}}admin/ajax/getLinks",function(data,status){
           json = jQuery.parseJSON(data);
           $('#fileTree').html('');
           $('#fileTree').append(
             '<li><a id="add-new" href="#"><span class="fa fa-plus faicon"></span><span class="mtext">Add new link</span></a></li>'
           );
           console.log(json);
           $.each( json, function( key, val ) {

              $('#fileTree').append( '<li class="folder-pages" style="display:none" data-url="'+key+'" data-file="'+val+'"><a  href="#"><span class="fa fa-file-o faicon"></span><span class="removefile" style="color:#333" class="fa fa-times pull-right"></span><span class="mtext">'+key+'</span></a></li>' );

          });
          $('.folder-pages').fadeIn();

        $('.folder-pages').on('live click', function(){
            $.post("{{$url}}admin/ajax/getLink",
             {
               url:$(this).data('url')
             },function(data){
               jsons = jQuery.parseJSON(data);
				 
               $.each( jsons, function( key, val ) {
                 $('#pagename').val(key);
                 $('#pagepage').val(val);
              });
             });
			var page = $('#pagepage').val();
			console.log(page);
			 $.post("{{$url}}admin/ajax/getEditable",
             {
              page:page
             },function(data){
				 console.log(data);

               jsons = jQuery.parseJSON(data);
				 console.log(jsons);
               $.each( jsons, function( key, val ) {
                 //$('#editables').append('<div class="col-xs-6" style="margin-top:20px"><p style="color:#fff">' +key+'</p> <textarea class="form-control editable-text">'+val+'</textarea> </div>');
              });
				 inittiny();
             });
			
        });

        $('#add-new').on('live click', function(){
            current_file = false;
            old_file = '';
            $('#pagepage').val('');
            $('#pagename').val('');
        });
      });
    }
      $(document).ready(function(){
          getPages();

          $('#save').click(function(){
               $.post("{{$url}}admin/ajax/updateLink",
                {
                  url:$('#pagename').val(),
                  view:$('#pagepage').val()
                },function(data){
                });
              getPages();
          });

          $('#delete').click(function(){
               $.post("{{$url}}admin/ajax/deleteLink",
                {
                  url:$('#pagename').val(),
                  view:$('#pagepage').val()
                },function(data){
                });
              getPages();
          });

      })
    </script>
  {{$sectionEnd_javascript}}

{{$display_rpdev/admin/layouts/basic}}
