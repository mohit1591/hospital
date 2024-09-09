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
    <div class="panel-particular-box">
<form name="particular_panel_charge" id="particular_panel_charge" action="" method="post">
<table id="table" class="table table-striped table-bordered iipd_panel_company_list" cellspacing="0" width="100%">
            <thead class="">
                <tr>
                  <td>Panel</td>
                   <td colspan=""><select name="panel" id="panel" onchange="get_particular();">
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
                  <td><div id="increment_by_value" style="display: none;">Increment By Rs <input class="numeric number" type="text" name="increment_by" onchange="add_more_panel_room_charge(this.value,1);" id="increment_by"> % <input class="numeric number" type="text" name="increment_by_per" id="increment_by_per" onchange="add_more_panel_room_charge(this.value,2);"></div></td>
                </tr>
                <tr class="bg-theme" id="previours_row">
                <th> Particular </th> 
                <th colspan="2"> Charge </th> 
                </tr>
                <tr>
                 <td class="append_row text-danger" colspan="15"><div class="text-left">No record found</div></td>
            </tr>
                
            </thead>
            
                
    </table>
    </form>
    </div>
</div>
<div class="userlist-right">
      <div class="btns">
      <?php //if(in_array('728',$users_data['permission']['action'])) {
               ?>
          <!-- <button class="btn-update" id="modal_add" onClick="return print_panel_particular();"><i class="fa fa-print"></i> Print
             </button> -->
             <?php //} ?>
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
  
function get_particular()
{  
    var panel = $('#panel').val();
    $.ajax({
       type: "POST",
       url: "<?php echo base_url('opd_panel_particular_charge/ajax_list_pannel_particular')?>",
       data: {'panel' : panel},
       dataType: "json",
       success: function(msg){
            $(".append_row").remove();
            $("#previours_row").after(msg.data);
            $('#increment_by').val('');
            $('#increment_by_per').val('');
            $("#increment_by_value").css("display", "block");
                   
       }
    }); 
}
function add_panel_particular_charge(val,particular_id,charge_id)
{

  var msg = 'Panel charges updated successfully.';
var panel_id = $('#panel').val(); 
$.ajax({
    url: "<?php echo base_url('opd_panel_particular_charge/set_panel_price/'); ?>"+panel_id+"/"+particular_id+"/"+val+"/"+charge_id,
    success: function(result) { 
      if(result==1)
      {
        $('#increment_by').val('');
        $('#increment_by_per').val('');
        flash_session_msg(msg);
        get_particular();
        
      } 
            
      $('#overlay-loader').hide();
    }
  });
}

function add_more_panel_room_charge(vals,type)
{
  var msg = 'Panel charges updated successfully.';
  var panel_id = $('#panel').val(); 
  $.ajax({
    url: "<?php echo base_url('opd_panel_particular_charge/increase_panel_price/'); ?>"+panel_id+"/"+type+"/"+vals, 
    success: function(result) { 
      if(result==1)
      {
        $('#increment_by').val('');
        $('#increment_by_per').val('');
        flash_session_msg(msg);
        get_particular();
        
      } 
            
      $('#overlay-loader').hide();
    }
  });
}
function print_panel_particular()
{
  var panel = $('#panel').val();
  
  print_window_page('<?php echo base_url('opd_panel_particular_charge/print_panel_particular/'); ?>'+panel);
  //
}
</script>