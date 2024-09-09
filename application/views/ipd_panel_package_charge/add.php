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
    <div class="panel-particular-new-box">
<form name="particular_panel_charge" id="particular_panel_charge" action="" method="post">
<table id="table" class="table table-striped table-bordered test_price iipd_panel_company_list" cellspacing="0" width="100%">
            <thead class="">
                <tr>
                  <td colspan="2" style="text-align:left;">Panel &nbsp;&nbsp;&nbsp;
                  <select name="panel" id="panel" onchange="get_package();">
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
                  <td colspan="3"><div id="increment_by_value" style="display: none;">Increment By <input class="numeric number" onchange="change_other(this.value,1);" type="text" name="increment_by" value='' placeholder="Amount" id="increment_by"> &nbsp; OR &nbsp; <input class="numeric number" type="text" onchange="change_other(this.value,2);" name="increment_by_per" id="increment_by_per" placeholder="%" value='' >
                  
                  <a href="javascript:void(0);"  onclick="return add_more_panel_package_charge();" class="btn-update">Apply</a>
                  <!-- onchange="add_more_panel_package_charge(this.value,2);"-->
                  </div></td>
                </tr>
                <tr class="bg-theme" id="previours_row">
                    <th colspan="5" style="text-align:left;"> Package </th> 
                    <th colspan="5"> Charge </th> 
                    <th>Action</th>
                </tr>
                <tr>
                 <td class="append_row text-danger" colspan="15"><div class="text-center">No record found</div></td>
            </tr>
                
            </thead>
            
                
    </table>
    </form>
    </div>
</div>
<div class="userlist-right">
      <div class="btns">
          
          <button class="btn-exit m-t-30px" onclick="return price_list_form()">
                    <i class="fa fa-save"></i> Save
               </button>
      <?php if(in_array('732',$users_data['permission']['action'])) {
               ?>
          <button class="btn-update" id="modal_add" onClick="return print_panel_package();"><i class="fa fa-print"></i> Print
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

function save_panel_rate(obj,paneln_ids)
 {

    
    var package_id = $(obj).data('testid');
    var price_k = $(obj).data('price');
    var price = $("#"+price_k).val();
    
    if(paneln_ids=='')
    {
      alert('Please select panel.');
      return false;
    }
    
     $.post('<?php echo base_url('ipd_panel_package_charge/save_panel_rate/'); ?>',{'package_id':package_id,'panel_id':paneln_ids,'charge':price},function(result){
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
     })

 }
function price_list_form()
 {

  $.ajax({
    url: "<?php echo base_url('ipd_panel_package_charge/save_price_list'); ?>",
    type: "post",
    data: $('#particular_panel_charge').serialize(),
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
               var msg = "Panel price saved successfully";
               flash_session_msg(msg); 
          }
    }
  });
 }  
function get_package()
{  
    var panel = $('#panel').val();
    $.ajax({
       type: "POST",
       url: "<?php echo base_url('ipd_panel_package_charge/ajax_list_pannel_package')?>",
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
function add_panel_package_charge(val,package_id,charge_id)
{
  var msg = 'Panel charges updated successfully.';
var panel_id = $('#panel').val(); 
$.ajax({
    url: "<?php echo base_url('ipd_panel_package_charge/set_panel_price/'); ?>"+panel_id+"/"+package_id+"/"+val+"/"+charge_id,
    success: function(result) { 
      if(result==1)
      {
        $('#increment_by').val('');
        $('#increment_by_per').val('');
        flash_session_msg(msg);
        get_package();
       
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

function add_more_panel_package_charge(vals,type)
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
    url: "<?php echo base_url('ipd_panel_package_charge/increase_panel_price/'); ?>"+panel_id+"/"+type+"/"+vals, 
    success: function(result) { 
      if(result==1)
      {
        $('#increment_by').val('');
        $('#increment_by_per').val('');
        flash_session_msg(msg);
        get_package();
        
      } 
            
      $('#overlay-loader').hide();
    }
  });
}

function print_panel_package()
{
  var panel = $('#panel').val();
  
  print_window_page('<?php echo base_url('ipd_panel_package_charge/print_panel_package/'); ?>'+panel);
  //
}
</script>