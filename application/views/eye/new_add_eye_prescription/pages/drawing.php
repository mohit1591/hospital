<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title>Add Drawing</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.6.95/css/materialdesignicons.css" rel="stylesheet">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>/drawing/web/jquery.drawr.combined.js?v=2"></script>
</head>

<body>
<section class="userlist"> 
          <div class="row">
    		<div class="col-lg-2 col-md-2 col-sm-6">
    			<div><b>Drawing Master</b></div>
    			<select name="drawing_master" id="drawing_master" onchange="return make_base(this.value)" class="form-control"> 
    			<option value="">Select Drawing</option>
    				<?php
    				if(!empty($drawing_master))
    				{
    					foreach($drawing_master as $drawing)
    					{  
    						echo '<option value="'.$drawing['image'].'">'.$drawing['title'].'</option>';
    					}
    				}
    				?>
    			</select>
    		</div>
    		
    		<div class="col-lg-2 col-md-2 col-sm-6">
    			<div><b>Remark</b></div>
    			<textarea name="remark" id="remark" class="inputFocus form-control" ></textarea>
    		</div>
    		
    	  </div>
          <div class="row">  
            <div id="drawr-container" style="width:100vw;height:100vh; border:2px dotted gray;">
            	<canvas id="demo-canvas" class="demo-canvas drawr-test1"></canvas>
            	<input type="file" id="file-picker" style="display:none;">     
            </div>
          </div> 
 
</section> 

 <script type="text/javascript">











	$("#drawr-container .demo-canvas").drawr({
		"enable_tranparency" : false
	});

	$(".demo-canvas").drawr("start");
	
	//add custom save button.
	var buttoncollection = $("#drawr-container .demo-canvas").drawr("button", {
		"icon":"mdi mdi-folder-open mdi-24px"
	}).on("touchstart mousedown",function(){
		//alert("demo of a custom button with your own functionality!");
		$("#file-picker").click();
	});
	var buttoncollection = $("#drawr-container .demo-canvas").drawr("button", {
		"icon":"mdi mdi-content-save mdi-24px"
	}).on("touchstart mousedown",function(){
		var imagedata = $("#drawr-container .demo-canvas").drawr("export","image/jpeg");
		var title_drawing = $('#title').val(); 
	    var remark = $('#remark').val();
	    var booking_id = '<?php echo $booking_id; ?>';
	    var pres_id = '<?php echo $booking_id; ?>';
		$.ajax({
              type: "POST",
              url: '<?php echo current_url(); ?>',
              data: {image: imagedata, remark: remark, booking_id: booking_id, pres_id: pres_id},
              success: function(result) 
              { 
                  window.close();
              }
          }); 
	}); 
	
	var canvas = document.getElementById('demo-canvas'),
	context = canvas.getContext('2d'); 

	function make_base(vals)
	{
	  var img_path = '<?php echo ROOT_UPLOADS_PATH.'eye/drawing_master/'; ?>'+vals;  
	  base_image = new Image();
	  base_image.src = img_path;
	  base_image.onload = function(){
	    context.drawImage(base_image, 0, 0);
	  }
	}
	
	$("#file-picker")[0].onchange = function(){
	var file = $("#file-picker")[0].files[0];
	if (!file.type.startsWith('image/')){ return }
	var reader = new FileReader();
	reader.onload = function(e) { 
		$("#demo-canvas").drawr("load",e.target.result);
	};
	reader.readAsDataURL(file);
};


 

</script>
</body>
</html>