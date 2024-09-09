<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">


<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css"> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">
<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.6.95/css/materialdesignicons.css" rel="stylesheet">
<!--<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>-->
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<!--<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>-->
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>/drawing/web/jquery.drawr.combined.js?v=2"></script>
<!-- datatable js --> 

</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
     
 <!--<form  id="drawing" action="<?php echo current_url(); ?>" method="post" class="form-inline">-->
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />    
         
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-2">
                    <label>Title<span class="star">*</span></label>
                </div>
                <div class="col-md-10">
                    <input type="text" name="title" id="title" class="inputFocus" value="<?php echo $form_data['title']; ?>">
                    <div class="text-danger" style="display:none;">The title field is required.</div>
                    <?php if(!empty($form_error)){ echo form_error('title'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  
           
          
          <div class="row">  
            <div id="drawr-container" style="width:700px;height:600px; border:2px dotted gray; margin-left:17%;">
            		<canvas id="canvas3"></canvas>
            	</div>
            	<input type="file" id="file-picker" style="display:none;">     
          </div> <!-- row --> 
          
             
      </div> <!-- modal-body -->
 
<!--</form> -->
  <!-- cbranch-rslt close -->
 
</section> <!-- cbranch -->

 <script type="text/javascript">  
//Turn a canvas element into a sketch area
//width and height are grabbed automatically.
$("#canvas3").drawr({
	"enable_tranparency" : false
});

//Enable drawing mode, show controls
$("#canvas3").drawr("start");

//add custom save button.
var buttoncollection = $("#canvas3").drawr("button", {
	"icon":"mdi mdi-folder-open mdi-24px"
}).on("touchstart mousedown",function(){
	$("#file-picker").click();
});
var buttoncollection = $("#canvas3").drawr("button", {
	"icon":"mdi mdi-content-save mdi-24px"
}).on("touchstart mousedown",function(){
	var imagedata = $("#canvas3").drawr("export","image/jpeg");
	$('#image_data').val(imagedata);
	var title_drawing = $('#title').val(); 
	var data_id = $('#type_id').val(); 
	if(title_drawing=="")
	{
	    $('.text-danger').show();
	    return false;
	}
	$('.text-danger').hide();
	$.ajax({
              type: "POST",
              url: '<?php echo current_url(); ?>',
              data: {image: imagedata, title: title_drawing, data_id: data_id},
              success: function(result) 
              { 
                  window.location.href="<?php echo base_url('eye/drawing'); ?>";
              }
          }); 
	/*var element = document.createElement('a');
	element.setAttribute('href', imagedata);
	element.setAttribute('download', "test.jpg");
	element.style.display = 'none';
	document.body.appendChild(element);
	element.click();
	document.body.removeChild(element);
	return save();*/
});

/*function save()
{
var title_drawing = $('#title').val();
var imagedata = $('#image_data').val();
$.ajax({
        type: 'POST',
        url: '<?php echo current_url(); ?>',
        data: '{ "image" : "' + imagedata + '", "title" : "' + title_drawing + '" }',
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        success: function (msg) {
            alert("Done, Picture Uploaded.");
        }
    });
}*/
<?php
if(!empty($form_data['data_id']))
{ 
    $upload_dir = ROOT_UPLOADS_PATH.'eye/drawing_master/'.$form_data['image'];
    $img = file_get_contents($upload_dir);
    $img = 'data:image/jpeg;base64,'.base64_encode($img);
?> 
var canvas = document.getElementById('canvas3'),
context = canvas.getContext('2d');

make_base();

function make_base()
{
  base_image = new Image();
  base_image.src = '<?php echo $upload_dir; ?>';
  base_image.onload = function(){
    context.drawImage(base_image, 0, 0);
  }
}
<?php    
}
?>

$("#file-picker")[0].onchange = function(){
	var file = $("#file-picker")[0].files[0];
	if (!file.type.startsWith('image/')){ return }
	var reader = new FileReader();
	reader.onload = function(e) { 
		$("#canvas3").drawr("load",e.target.result);
	};
	reader.readAsDataURL(file);
};




</script>     
<?php
$this->load->view('include/footer');
?> 

</div><!-- container-fluid -->
</body>
</html>