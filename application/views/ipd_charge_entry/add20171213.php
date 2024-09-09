<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(4);
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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>select2.full.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>select2.min.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 


<script type="text/javascript">
var save_method; 
var table; 
 
 
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function delete_particulars_vals() 
{           
       
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true)
         {
              allVals.push($(this).val());
         } 
       });


       remove_particulars_vals(allVals);
  } 

  function remove_particulars_vals(allVals)
  {

   if(allVals!="")
   {
      var particulars_charges = $('#particulars_charges').val();
     
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('ipd_charge_entry/remove_ipd_particular');?>",
              
              data: {particular_id: allVals,particulars_charges:particulars_charges},
              
              dataType: "json",
              success: function(result) 
              { 

                $('#particular_list').html(result.html_data);
                $('#particulars_charges').val(result.particulars_charges);
                $('#total_amount').val(result.total_amount); 
                  
              }
          });
   }
  }

 

</script>

</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
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
<section class="path-booking">
<form action="<?php echo current_url(); ?>" method="post">
<input type="hidden" name="patient_id" value="<?php echo $patient_details['p_id']; ?>" />
<input type="hidden" name="patient_type" value="<?php echo $patient_details['patient_type']; ?>" />
<input type="hidden" name="panel_name" value="<?php echo $patient_details['panel_name']; ?>" />
<div class="row">
  <div class="col-xs-4 media_50">
    
   
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Patient Reg. No.</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" readonly="" name="patient_code" value="<?php echo $patient_details['patient_code']; ?>" /> 
      </div>
    </div> <!-- row -->
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>IPD No.</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" readonly="" name="patient_code" value="<?php echo $patient_details['ipd_no']; ?>" /> 
      </div>
    </div> <!-- row -->

    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Patient Name <span class="star">*</span></strong>
      </div>
      <div class="col-xs-8">
        <select class="mr" name="simulation_id" id="simulation_id"  disabled="true">
          <option value="">Select</option>
          <?php
            
            if(!empty($simulation_list))
            {
              foreach($simulation_list as $simulation)
              {
                $selected_simulation = '';
                if($simulation->id==$patient_details['simulation_id'])
                {
                     $selected_simulation = 'selected="selected"';
                }
                        
                echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
              }
            }
            ?> 
        </select> 
        <?php 
        if(!empty($simulation_list))
        {
          foreach($simulation_list as $simulation)
          {
            $selected_simulation = '';
            if($simulation->id==$patient_details['simulation_id'])
            {
                 $selected_simulation = 'selected="selected"';
            }
                    
            echo '<input type="hidden" name="simulation_id" value="'.$simulation->id.'">';
          }
        }
        ?>
        <input type="text" name="patient_name" readonly id="patient_name" value="<?php echo $patient_details['patient_name']; ?>" class="mr-name txt_firstCap" autofocus/>
        
          <?php if(!empty($form_error)){ echo form_error('simulation_id'); } ?>
          <?php if(!empty($form_error)){ echo form_error('patient_name'); } ?>
      </div>
    
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Mobile No.</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" name="mobile_no" readonly data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text numeric" value="<?php echo $patient_details['mobile_no']; ?>" maxlength="10" >
        
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Gender </strong>
      </div>
      <div class="col-xs-8" id="gender">
        <input type="radio" name="gender"  disabled="true" value="1" <?php if($patient_details['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
            <input type="radio" name="gender"  disabled="true" value="0" <?php if($patient_details['gender']==0){ echo 'checked="checked"'; } ?>> Female
             <input type="radio" name="gender"  disabled="true" value="2" <?php if($patient_details['gender']==2){ echo 'checked="checked"'; } ?>> Others
            <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Age</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" name="age_y" readonly  class="input-tiny numeric" maxlength="3" value="<?php echo $patient_details['age_y']; ?>"> Y &nbsp;
              <input type="text" name="age_m"  readonly class="input-tiny numeric" maxlength="2" value="<?php echo $patient_details['age_m']; ?>"> M &nbsp;
              <input type="text" name="age_d"  readonly class="input-tiny numeric" maxlength="2" value="<?php echo $patient_details['age_d']; ?>"> D
             
      </div>
    </div> <!-- row -->
    
    
       

    
    
  </div> <!-- Main 4 -->




  <div class="col-xs-4 media_50">
  <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Particulars  </strong>
      </div>
      <div class="col-xs-8">
        <select name="particulars" id="particular_id" class="" onchange="return get_particulars_data(this.value);">
            <option value="">Select Particulars</option>
            <?php
            if(!empty($particulars_list))
            {
              //print '<pre>';print_r($particulars_list);die;
              foreach($particulars_list as $particularslist)
              {
                if(isset($form_data['particular_id']) && $form_data['particular_id']==$particularslist->id){
                  $selected_particulars="selected=selected";
                }else{
                 $selected_particulars=''; 
                }
                echo '<option value="'.$particularslist->id.'" '.$selected_particulars.'>'.$particularslist->particular.'</option>';
              }
            }
            ?> 
          </select> 
             <a  class="btn-new" id="patient_ipd_particular_add_modal"> New</a>   
              
               
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Charges</strong>
      </div>
      <div class="col-xs-8">
        <input type="text"  name="charges" class="price_float" id="charges" value="">
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Quantity</strong>
      </div>
      <div class="col-xs-8">
        <input type="text"  name="quantity" id="quantity"  class="numeric" onkeyup="get_particular_amount();" value="">
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Amount</strong>
      </div>
      <div class="col-xs-8">
        <input type="text"  class="price_float" name="amount" id="amount" value="">
        <a class="btn-new" onclick="particular_payment_calculation();"> Add </a>
      </div>
    </div> <!-- row -->

    

<?php $ipd_particular_payment =  $this->session->userdata('ipd_particular_payment'); ?>
    
    
  </div> <!-- Main 4 -->
  
  <div class="col-xs-4 media_100">
    
    <table class="table table-bordered table-striped opd_billing_table" id="particular_list">
      <thead class="bg-theme">
          <tr>
              <th align="center" width="">
                <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle(this);">
              </th>
              <th scope="col">S.No.</th>
              <th>Particular</th>
              
              <th>Quantity</th>
              <th>Charges</th>
              <th>Amount</th>
          </tr>
           </thead>
          <?php 
         
          $perticuller_list = $this->session->userdata('ipd_particular_charge_billing'); 
           //print '<pre>'; print_r($perticuller_list);die;
     
          $i = 0;
          if(!empty($perticuller_list))
          {
             $i = 1;
             foreach($perticuller_list as $perticuller)
             {

              ?>
                <tr>
                  <td>
                    <input type="checkbox" class="part_checkbox booked_checkbox" name="particular_id[]" value="<?php echo $perticuller['charge_id']; ?>" >
                  </td>
                  <td><?php echo $i; ?></td>
                  <td><?php echo $perticuller['particulars']; ?></td>
                  <td><?php echo $perticuller['quantity']; ?></td>
                   <td><?php echo $perticuller['charges']; ?></td>
                  <td><?php echo $perticuller['amount']; ?></td>
                
                </tr>
              <?php
              $i++;
             }
          }
          ?> 
          <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_particulars_vals();">
             <i class="fa fa-trash"></i> Delete
          </a>
      <?php  if(!empty($form_error)){ ?>    
     <tr>
            <td colspan="5"><?php  echo form_error('particular_id');  ?></td>
        </tr>
        <?php } ?>
  </table>
 

 <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Total Amount</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" readonly=""  class="price_float" name="particulars_charges" id="particulars_charges" value="<?php if(!empty($ipd_particular_payment['particulars_charges'])) { echo number_format($ipd_particular_payment['particulars_charges'],2,'.',''); } else{ echo $particulars_charges; } ?>">
      </div>
    </div> <!-- row -->

<input type="hidden" name="ipd_id" value="<?php echo $ipd_id; ?>">
<input type="hidden" name="data_id" value="<?php echo $data_id; ?>">
 <div class="row m-b-5">
      <div class="col-xs-4">
        <strong></strong>
      </div>
      <div class="col-xs-8">
        <button class="btn-save"><i class="fa fa-floppy-o"></i> Save</button>
        <button class="btn-update" type="button" onclick="window.location.href='<?php echo base_url('ipd_booking'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
      </div>
    </div> <!-- row -->


  </div> <!-- Main 4 -->

</div> <!-- MainRow -->





  <!-- box -->


</form>

</section> <!-- close -->
<?php
$this->load->view('include/footer');
?>

<script>
$(document).ready(function(){
  $('#selectall').on('click', function () { 
  
  if ($(this).hasClass('allChecked')) {
  $('.booked_checkbox').prop('checked', false);
  } else {
  $('.booked_checkbox').prop('checked', true);
  }
  $(this).toggleClass('allChecked');
  })


var $modal = $('#load_add_ipd_perticular_modal_popup');

$('#patient_ipd_particular_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'ipd_perticular/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});


});

  
$(".txt_firstCap").on('keyup', function(){

   var str = $('.txt_firstCap').val();
   var part_val = str.split(" ");
    for ( var i = 0; i < part_val.length; i++ )
    {
      var j = part_val[i].charAt(0).toUpperCase();
      part_val[i] = j + part_val[i].substr(1);
    }
      
   $('.txt_firstCap').val(part_val.join(" "));
  
  });


$(document).ready(function() {
  $(".select2").select2();
}); 
</script>
<script>  
function get_particulars_data(particulars_id)
{
    var charges = $('#charges').val();
    var amount = $('#amount').val();
    var quantity = $('#quantity').val();
    $.ajax({url: "<?php echo base_url(); ?>general/ipd_particulars_list/"+particulars_id, 
      success: function(result)
      {
        var result = JSON.parse(result);
        $('#charges').val(result.charges);
        $('#amount').val(result.amount); 
        $('#quantity').val(result.quantity);  
      } 
    });
    //get_particular_amount(); 
  }


  function get_particular_amount()
  {
    var charges = $('#charges').val();
    var quantity = $('#quantity').val();
    var amount = $('#amount').val();
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>ipd_charge_entry/ipd_particular_calculation/", 
            dataType: "json",
            data: 'charges='+charges+'&quantity='+quantity,
            success: function(result)
            { 
               $('#amount').val(result.amount); 
            } 
          });
  }

  
   

  function isNumberKey(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      } else {
          return true;
      }      
  }

  <?php
  if($i>0)
  {
    $total_i = $i;
    echo 'var row_sn = '.$total_i.';';
  }
  else
  {
    echo 'var row_sn = 1;';
  }
  ?>
  

  $(document).ready(function(){

    $("#particular_list").on('click','.remCF',function(){
        $(this).parent().parent().remove();
    });

    });


  function particular_payment_calculation()
  {
    var amount = $('#amount').val();
    var quantity = $('#quantity').val();
    var particular = $('#particular_id').val();
    var charges= $('#charges').val();
    var particulars_charges = $('#particulars_charges').val();
    var particulars = $('#particular_id option:selected').text();
    

    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>ipd_charge_entry/particular_payment_calculation/", 
            dataType: "json",
            data: 'amount='+amount+'&quantity='+quantity+'&particular='+particular+'&particulars='+particulars+'&particulars_charges='+particulars_charges+'&charges='+charges,
            success: function(result)
            {

              $('#particular_list').html(result.html_data);
              $('#total_amount').val(result.total_amount);
              $('#particulars_charges').val(result.particulars_charges);   
              $('#charges').val('');
              $('#amount').val('');
              $('#quantity').val('1');  
              
               
            } 
          });
  }



</script> 
<!-- Confirmation Box -->

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->


<div id="load_add_ipd_perticular_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>


</div>
</div>


</body>
</html>