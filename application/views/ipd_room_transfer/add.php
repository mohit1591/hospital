<?php
$users_data = $this->session->userdata('auth_users');
$flash_success = $this->session->flashdata('success');
//print_r($flash_success);die;
?>
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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>

</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
  <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo '<script> flash_session_msg("'.$flash_success.'");</script> ';
   ?>
   <script>  
    //$('.dlt-modal').modal('show'); 
    </script> 
    <?php
 }
?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
  <div class="userlist-box media_tbl_full">
   
      <form action="<?php echo current_url(); ?>" method="post">
      <input type="hidden" value="<?php echo $form_data['data_id'];?>" name="data_id"/>
      <input type="hidden" value="<?php echo $form_data['patient_id'];?>" name="patient_id"/>
      <input type="hidden" value="<?php echo $form_data['ipd_id'];?>" name="ipd_id"/>
     <div class="row">
          <div class="col-md-12">
         <input type="hidden"  name="ipd_id" value="<?php echo $form_data['ipd_id'];?>"/>
         <input type="hidden"  name="patient_id" value="<?php echo $form_data['patient_id'];?>"/>
          <div class="row">
              <div class="col-sm-10 col-md-offset-1">

                  <!-- new content -->
            <div class="row">
              <div class="col-md-5">

                <table class="ipd_tbl_room_transfer">
                <tr>
                <th>IPD No. :</th>
                <td> <?php echo $patient_details['ipd_no']; ?></td>
                </tr>
                <tr>
                <th> Patient Registration No. : </th>
                <td> <?php echo $patient_details['patient_code']; ?></td>
                </tr>
                <tr>
                <th> Patient Name : </th>
                <td><?php echo $patient_details['patient_name']; ?></td>
                </tr>
                <tr>
                <th> Mobile Number : </th>
                <td> <?php echo $patient_details['mobile_no']; ?></td>
                </tr>
                <tr>
                <th>  Room Type : </th>
                <td> <?php echo $patient_details['room_category']; ?></td>
                </tr>
                <tr>
                <th>  Room No. : </th>
                <td> <?php echo $patient_details['room_no']; ?></td>
                </tr>
                <tr>
                <th>  Bed No. : </th>
                <td> <?php echo $patient_details['bad_no']; ?></td>
                </tr>
                </table>


                </div>


              <div class="col-md-1">
                <table>
                  <tr>
                    <td valign="middle" height="150px"><b>To</b></td>
                  </tr>
                </table>
              </div>

              <div class="col-md-6">
                <div class="row m-b-5">
                  <div class="col-sm-5"> <label> Room Type <span class="star">*</span></label> </div>
                  <div class="col-sm-7">
                  <select name="room_id" value="room_id" onchange="room_no_select(this.value);" id="room_id">
                  <option value="">Select Room Type</option>
                  <?php foreach($room_category as $room_cat) {?>
                  <option value="<?php echo $room_cat->id;?>" <?php if(isset($form_data['room_id']) && $form_data['room_id']==$room_cat->id) {echo 'selected';}else{ echo '';}?>><?php echo $room_cat->room_category; ?></option>
                  <?php }?>
                  </select>
                   <?php if(!empty($form_error)){ echo form_error('room_id'); } ?>
                  </div>
                </div>
              
              <div class="row m-b-5">
                <div class="col-sm-5"> <label> Room No.<span class="star">*</span></label> </div>
                <div class="col-sm-7">
                <select name="room_no_id" id="room_no_id" onchange="select_no_bed(this.value);">
                    <option value="">-Select-</option>
                </select>
                 <?php if(!empty($form_error)){ echo form_error('room_no_id'); } ?>
                 </div>
                </div>

                <div class="row m-b-5">
                <div class="col-sm-5"> <label> Bed No.<span class="star">*</span></label> </div>
                <div class="col-sm-7">
               <select name="bed_no_id" id="bed_no_id">
                    <option value="">-Select-</option>
                </select>
                 <?php if(!empty($form_error)){ echo form_error('bed_no_id'); } ?>
                </div>
                </div>

                <div class="row m-b-5">
                <div class="col-sm-5"> <label> Transfer Date <span class="star">*</span></label> </div>
                <div class="col-sm-7"><input type="text" name="transfer_date" value="<?php  echo $form_data['transfer_date']?>" class="datepicker"/></div>
                 <?php if(!empty($form_error)){ echo form_error('transfer_date'); } ?>
                </div>

                <div class="row m-b-5">
                <div class="col-sm-5"> <label> Transfer Time <span class="star">*</span></label> </div>
                <div class="col-sm-7"><input type="text" name="transfer_time" value="<?php  echo $form_data['transfer_time']?>" class="datepicker3"/></div>
                 <?php if(!empty($form_error)){ echo form_error('transfer_time'); } ?>
                </div>

                <div class="row m-b-5">
                   <div class="col-sm-5"> <label> </label> </div>
                   <div class="col-sm-7"><button class="btn-save"> <i class="fa fa-floppy-o"></i> Transfer</button>
                   <a href="<?php echo base_url('ipd_booking'); ?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
                   </div>
                </div>

              </div>
              </div>
                  <!-- new content -->
             

              </div> <!-- 4 -->

              </div> <!-- inner row -->

          </div>
      </div>

      </form>
    
  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script type="text/javascript">
  
  $(document).ready(function(){

});
</script>



</body>
</html>
<script type="text/javascript">

function room_no_select(value_room,room_no_id){
  $.ajax({
  url: "<?php echo base_url('ipd_booking/select_room_number/'); ?>",
  type: "post",
  data: {room_id:value_room,room_no_id:room_no_id},
  success: function(result) 
  {
  $('#room_no_id').html(result);
  }
  });
}
function select_no_bed(value_bed,bed_id){

  var room_id= $("#room_id option:selected").val();
  var ipd_id = $("#type_id").val();

  $.ajax({
  url: "<?php echo base_url('ipd_booking/select_bed_no_number/'); ?>",
  type: "post",
  data: {room_id:room_id,room_no_id:value_bed,bed_id:bed_id,ipd_id:ipd_id},
  success: function(result) 
  {
  $('#bed_no_id').html(result);
  }
  });

}

$(document).ready(function (){
  room_no_select('<?php echo $form_data['room_id'];?>','<?php echo $form_data['room_no_id'];?>');
    select_no_bed('<?php echo $form_data['room_no_id'];?>','<?php echo $form_data['bed_no_id'];?>')
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true 
  });

 $('.datepicker3').datetimepicker({
     format: 'LT'
  });
});


</script>