<?php
$users_data = $this->session->userdata('auth_users');  ?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">


<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>

</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header'); 
  $query_string = "";
  if(!empty($_SERVER['QUERY_STRING']))
  {
    $query_string = '?'.$_SERVER['QUERY_STRING'];
  }
  ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
   
   <form action="<?php echo current_url().$query_string; ?>" method="post" > 
  
 <div class="sale_fields">
        
        <div class="grp-full">
            <div class="b1">
            
                <label>Room Type.</label>
                <select name="room_id" value="room_id" onchange="room_no_select(this.value); get_room_type(this.value);" id="room_id">
                    <option value="">-Select-</option>
                    <option value="All">All</option>
                    <?php foreach($room_type_list as $room_type){?>
                    <option value="<?php echo $room_type->id; ?>" ><?php echo $room_type->room_category; ?></option>
                    <?php }?>
                </select>
            </div>
           
            
        </div> <!-- grp-full -->
        
        <div class="grp-full" id="room_no" style="display: none;">
            <div class="b1">
          
                <label>Room No.</label>
          
            <div class="b2">
                <select name="room_no_id" id="room_no_id" onchange="select_no_bed(this.value);">
                    <option value="">-Select-</option>
                </select>
               
            </div>

        </div> <!-- grp-full -->
        
    </div> <!-- sale_fields -->

   </div> <!-- left -->
 
<div class="" id="bed_no_id" style="width: 100%; float: left; vertical-align: top;"></div>
    </div> <!-- sale_medicine_bottom -->

    
   
</form>
</section> <!-- section close -->

 


<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>

<script>
      function room_no_select(value_room,room_no_id)
      {
        if(value_room=='All')
        {
            
            $("#room_no").css("display", "none");
            $("#room_no_id").val('');
            
            $.ajax({
                    url: "<?php echo base_url('dialysis_room_list/select_all_bed_number/'); ?>",
                    type: "post",
                    success: function(result) 
                    {
                      $('#bed_no_id').html(result);
                    }
                });
        }

         else
        {
           $("#room_no").css("display", "block");
           //$("#room_no").css("display", "none");
            //$("#room_no_id").val('');
            $.ajax({
                url: "<?php echo base_url('dialysis_room_list/select_all_bed_number/'); ?>",
                type: "post",
                data: {room_id:value_room,room_no_id:room_no_id},
                success: function(result) 
                {
                  $('#bed_no_id').html(result);
                }
            });
         }

       /* else
        {
            $("#room_no").css("display", "block");
            $.ajax({
                url: "<?php echo base_url('ipd_booking/select_room_number/'); ?>",
                type: "post",
                data: {room_id:value_room,room_no_id:room_no_id},
                success: function(result) 
                {
                  $('#room_no_id').html(result);
                }
            });
         }*/
     }

     function get_room_type(value_room,room_no_id)
     {
        if(value_room!='All')
        {
          $("#room_no").css("display", "block");
              $.ajax({
                  url: "<?php echo base_url('dialysis_booking/select_room_number/'); ?>",
                  type: "post",
                  data: {room_id:value_room,room_no_id:room_no_id},
                  success: function(result) 
                  {
                    $('#room_no_id').html(result);
                  }
              });
        }
     }

     function select_no_bed(value_bed,bed_id){

        var room_id= $("#room_id option:selected").val();
        var ipd_id = $("#type_id").val();
        
        $.ajax({
                url: "<?php echo base_url('dialysis_room_list/select_bed_no_number/'); ?>",
                type: "post",
                data: {room_id:room_id,room_no_id:value_bed,bed_id:bed_id,ipd_id:ipd_id},
                success: function(result) 
                {
                  $('#bed_no_id').html(result);
                }
            });

     }
     
      function free_room(ipd_id,room_ids,room_no_id,bed_id)
      {
          
      var confirmation = confirm('Are you sure you want to make free this room?');
      if(confirmation){

        $.ajax({
                url: "<?php echo base_url('dialysis_room_list/free_room/'); ?>",
                type: "post",
                data: {room_id:room_ids,room_no_id:room_no_id,bed_id:bed_id,ipd_id:ipd_id},
                success: function(result) 
                {
                  $('#bed_no_id').html(result);
                  var msg = 'Room free successfully .';
                    flash_session_msg(msg);
                }
            });
  }

     }
     function search_filled(room_ids,room_no_id,status)
     {
         $.ajax({
                url: "<?php echo base_url('dialysis_room_list/search_filled/'); ?>",
                type: "post",
                data: {room_id:room_ids,room_no_id:room_no_id,status:status},
                success: function(result) 
                {
                  $('#bed_no_id').html(result);
                }
            });
     }
     


     function all_search_filled(status)
      {

            $.ajax({
                    url: "<?php echo base_url('dialysis_room_list/all_search_filled/'); ?>",
                    type: "post",
                    data: {status:status},
                    success: function(result) 
                    {
                      $('#bed_no_id').html(result);
                    }
                });
        }
        
    function generate_discharge_bill(booking_id,patient_id,type)
 {
   $.ajax({
        url: "<?php echo base_url('ipd_booking/select_discharge_date'); ?>/"+booking_id+"/"+patient_id+"/"+type,
        success: function(output){
          
          $('#load_discharge_modal_popup').html(output).modal('show');
        },
        error:function(){
            alert("failure");
        }
    });
 }
     
</script>
<div id="load_discharge_modal_popup" class="modal" role="dialog" data-backdrop="static" data-keyboard="false"></div>