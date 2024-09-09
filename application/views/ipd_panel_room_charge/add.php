<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(3);
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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 


</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<section class="userlist">
    <div class="userlist-box">
    <div class="panel-particular-new-room-box">
<form name="room_panel_charge" id="room_panel_charge" action="" method="post">
<table id="table" class="table table-striped table-bordered iipd_panel_company_list" cellspacing="0" width="100%">
            <thead class="">
                <tr>
                  <td colspan="2" style="text-align:left;">Panel &nbsp;&nbsp;&nbsp;
                  <select name="panel" id="panel" onchange="get_room();">
                  <option value="">Select Panel</option>
                  <?php
                    if(!empty($panel_list))
                    {
                      foreach($panel_list as $panel)
                      {
                      ?>
                       <option value="<?php echo $panel->id; ?>"><?php echo $panel->insurance_company; ?></option>
                      <?php 
                      }
                    }
                  ?></select></td>
                  <td colspan="3">
                  <div id="increment_by_pers" style="display: none;">Increment By  <input class="numeric number" type="text" name="increment_by" placeholder="Amount" onchange="change_other(this.value,1);" id="increment_by">  <input class="numeric number" type="text" name="increment_by_per" id="increment_by_per" placeholder="%" onchange="change_other(this.value,2);">
                  
                  <a href="javascript:void(0);"  onclick="return add_more_panel_room_charge();" class="btn-update">Apply</a>
                  </div></td>
                </tr>
                <tr class="bg-theme" id="previours_row">
                <th style="width:200px;"> Room Type </th>
                <?php  
                  if(!empty($room_charge_type_list))
                  {
                    $room_charge_type_list_count = count($room_charge_type_list);
                    for($i=0;$i<$room_charge_type_list_count;$i++)
                     { 
                    ?>
                        <th>
                            <?php 
                              echo ucfirst($room_charge_type_list[$i]['charge_type']); 
                            ?>
                        </th>
                <?php 
                     } 
                  } 
                ?> 
               <th>Action</th>
                </tr>
                <tr>
                 <td class="append_row text-danger" colspan="4"><div class="text-center">No record found</div></td>
            </tr>
                
            </thead>
            
                
    </table>
    </form>
    </div>
</div>
<div class="userlist-right">
      <div class="btns">
          
          <!--<button class="btn-exit m-t-30px" onclick="return price_list_form()">
                    <i class="fa fa-save"></i> Save
               </button>-->
       <?php if(in_array('730',$users_data['permission']['action'])) {
               ?>
          <button class="btn-update" id="modal_add" onClick="return print_panel_room();"><i class="fa fa-print"></i> Print
             </button>
             <?php } ?>
          <button class="btn-update" onclick="window.location.href='<?php echo base_url(); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
      </div>
    </div> 
</section>
   
  
<?php
$this->load->view('include/footer');
?>
<script type="text/javascript">

function price_list_form()
 {

  $.ajax({
    url: "<?php echo base_url('ipd_panel_room_charge/save_price_list'); ?>",
    type: "post",
    data: $('#room_panel_charge').serialize(),
    success: function(result) 
    {
       if(result==1)
          {
             error_flash_session_msg('please select panel'); 
             reload_table();
          }
          else
          {

               $("#msg").html('');
               var msg = "panel price saved successfully";
               flash_session_msg(msg); 
          }
    }
  });
 }

function add_more_panel_room_charge()
{
    var msg = 'Panel charges updated successfully.';
    var panel_id = $('#panel').val(); 
    var increment_by_per = $('#increment_by_per').val();
    var increment_by = $('#increment_by').val();
    
   if(increment_by_per!='')
   {
      var type=2;
      var vals=increment_by_per;
   }
   else if(increment_by!='')
   {
      var type=1;
      var vals=increment_by;
   }
   else
   {
       alert('Please input some value!');
       return false;
   }
  $.ajax({
    url: "<?php echo base_url('ipd_panel_room_charge/increase_panel_price/'); ?>"+panel_id+"/"+type+"/"+vals, 
    success: function(result) { 
      if(result==1)
      {
        $('#increment_by').val('');
        $('#increment_by_per').val('');
        flash_session_msg(msg);
        get_room();
        
      } 
            
      $('#overlay-loader').hide();
    }
  });
}

 function change_other(vals,type)
{
   if(type==1)
   { 
     $('#increment_by_per').val('');
   }
   else if(type==2)
   {
     $('#increment_by').val('');   
   }
  
}

function get_room()
{  
    var panel = $('#panel').val();
    $.ajax({
       type: "POST",
       url: "<?php echo base_url('ipd_panel_room_charge/ajax_list_pannel_room')?>",
       data: {'panel' : panel},
       dataType: "json",
       success: function(msg)
       {
          $(".append_row").remove();
          $("#previours_row").after(msg.data);
          $('#increment_by').val('');
          $('#increment_by_per').val('');
          $("#increment_by_pers").css("display", "block");
                   
       }
    }); 
}
function add_panel_room_charge(vals,room_type,category_id)
{
  var msg = 'Panel charges updated successfully.';
  var panel_id = $('#panel').val(); 
  $.ajax({
    url: "<?php echo base_url('ipd_panel_room_charge/set_panel_price/'); ?>"+panel_id+"/"+room_type+"/"+category_id+"/"+vals, 
    success: function(result) { 
      if(result==1)
      {
        $('#increment_by').val('');
        $('#increment_by_per').val('');
        flash_session_msg(msg);
        get_room();
        
      } 
        
          
        $('#overlay-loader').hide();
    }
  });
}

function add_more_panel_room_charge_old(vals,type)
{
  var msg = 'Panel charges updated successfully.';
  var panel_id = $('#panel').val(); 
  $.ajax({
    url: "<?php echo base_url('ipd_panel_room_charge/increase_panel_price/'); ?>"+panel_id+"/"+type+"/"+vals, 
    success: function(result) { 
      if(result==1)
      {
        $('#increment_by').val('');
        $('#increment_by_per').val('');
        flash_session_msg(msg);
        get_room();
        
      } 
            
      $('#overlay-loader').hide();
    }
  });
}


function print_panel_room()
{
  var panel = $('#panel').val();
  
  print_window_page('<?php echo base_url('ipd_panel_room_charge/print_panel_room/'); ?>'+panel);
  //
}
</script>