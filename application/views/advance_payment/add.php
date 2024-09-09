<?php
$users_data = $this->session->userdata('auth_users');
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
<script type="text/javascript">
var save_method; 
var table;



$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#doctor_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'patient/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});
 

$('#opd_adv_search').on('click', function(){
$modal.load('<?php echo base_url().'opd_billing/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});

});

function edit_patient(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'patient/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_patient(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'patient/view/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}


 
function checkboxValues() 
{         
    $('#table').dataTable();
     var allVals = [];
     $(':checkbox').each(function() 
     {
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
     });
     allbranch_delete(allVals);
} 

function allbranch_delete(allVals)
 {    
   if(allVals!="")
   {
       $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
        })
        .one('click', '#delete', function(e)
        { 
            $.ajax({
                      type: "POST",
                      url: "<?php echo base_url('opd_billing/deleteall');?>",
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
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
<section class="userlist">
  <div class="userlist-box media_tbl_full">
    <table class="ptl_tbl m-b-5">
      <tr>
      <td></td>
      <td>  <a class="btn-custom" href="<?php echo base_url('advance_payment');?>"> <i class=""></i> Payment List</a></td>
      </tr>
    </table>
      <form action="<?php echo current_url(); ?>" method="post" id="opd_form">
      <input type="hidden" value="<?php echo $form_data['data_id'];?>" name="data_id"/>
     <div class="row">
          <div class="col-md-12">
         <input type="hidden"  name="ipd_id" value="<?php echo $form_data['ipd_id'];?>"/>
         <input type="hidden"  name="patient_id" value="<?php echo $form_data['patient_id'];?>"/>
          <div class="row">
              <div class="col-sm-8 col-md-offset-2">

                  <table class="table table-bordered table-striped">
                    <tbody>
                    <?php //print_r($patient_details);die; ?>
                      <tr>
                      <th style="text-align:left;" width="50%">IPD No. :</th>
                      <td style="text-align:left;" width="50%"><?php echo $patient_details['ipd_no']; ?></td>
                      </tr>
                      <tr>
                      <th style="text-align:left;" width="50%"><?php echo $data= get_setting_value('PATIENT_REG_NO');?> :</th>
                      <td style="text-align:left;" width="50%"><?php echo $patient_details['patient_code']; ?></td>
                      </tr>
                      <tr>
                      <th style="text-align:left;" width="50%">Patient Name :</th>
                      <td style="text-align:left;" width="50%"><?php echo $patient_details['patient_name']; ?></td>
                      </tr>
                      <tr>
                      <th style="text-align:left;" width="50%">Mobile Number :</th>
                      <td style="text-align:left;" width="50%"><?php echo $patient_details['mobile_no']; ?></td>
                      </tr>
                      <tr>
                      <th style="text-align:left;" width="50%">Room Type :</th>
                      <td style="text-align:left;" width="50%"><?php echo $patient_details['room_category']; ?></td>
                      </tr>
                      <tr>
                      <th style="text-align:left;" width="50%">Room No. :</th>
                      <td style="text-align:left;" width="50%"><?php echo $patient_details['room_no']; ?></td>
                      </tr>
                        <tr>
                      <th style="text-align:left;" width="50%">Bed No. :</th>
                      <td style="text-align:left;" width="50%"><?php echo $patient_details['bad_name']; ?></td>
                      </tr>
                    </tbody>
                  </table>
              <div class="row">
                  <div class="col-md-6">
                      <div class="row m-b-5">
                      <div class="col-sm-5">
                          <b>Particular<span class="star">*</span></b>
                      </div>
                      <div class="col-sm-7">
                          
                          <input type="text" name="particular" value="<?php echo $form_data['particular']; ?>">
                          <small class="text-normal"><?php if(!empty($form_error)){ echo form_error('particular'); } ?></small>
                          </div>
                      </div>
                      <div class="row m-b-5">
                      <div class="col-sm-5">
                          <b>Mode of Payment<span class="star">*</span></b>
                      </div>
                      <div class="col-sm-7">
                          <select  name="payment_mode" onChange="payment_function(this.value,'');">
                          <?php foreach($payment_mode as $payment_mode) 
                          {?>
                          <option value="<?php echo $payment_mode->id;?>" <?php if($form_data['payment_mode']== $payment_mode->id){ echo 'selected';}?>><?php echo $payment_mode->payment_mode;?></option>
                          <?php }?>

                          </select>
                      </div>
                      </div>

                       <div id="updated_payment_detail">
                 <?php if(!empty($form_data['field_name']))
                 { foreach ($form_data['field_name'] as $field_names) {
                     $tot_values= explode('_',$field_names);

                    ?>

                  <div class="row m-b-5" id="branch"> 
                  <div class="col-md-5">
                  <strong><?php echo $tot_values[1];?><span class="star">*</span></strong>
                  </div>
                  <div class="col-md-7"> 
                  <input type="text" name="field_name[]" value="<?php echo $tot_values[0];?>" /><input type="hidden" value="<?php echo $tot_values[2];?>" name="field_id[]" />
                   <?php 
                      if(empty($tot_values[0]))
                      {
                      if(!empty($form_error)){ echo '<div class="text-danger">The '.strtolower($tot_values[1]).' field is required.</div>'; } 
                      }
                      ?>
                  </div>
                  </div>
               <?php } }?>
                     
                   </div>
                  
                <div id="payment_detail">
                    

                </div>


                  </div>

                  
                  <div class="col-md-6 text-right">
                      <div class="row">
                          <div class="col-md-12">
                              <label> Amount <span class="star">*</span><input type="text" class="w-100px price_float" name="amount" value="<?php echo $form_data['amount'] ?>"> <small class="text-normal"><?php if(!empty($form_error)){ echo form_error('amount'); } ?></small></label>

                    
               
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <label> Date: <input type="text" class="w-100px datepicker" name="payment_date" value="<?php echo $form_data['payment_date'] ?>"> </label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                          <button class="btn-save" id="btnsubmit"> <i class="fa fa-floppy-o"></i> Submit</button>
                          <a class="btn-anchor" href="<?php echo base_url('ipd_booking');?>"> <i class="fa fa-sign-out"></i> Exit</a>
                          </div>
                        </div>
                  </div>
              </div>

              </div> <!-- 4 -->

              </div> <!-- inner row -->

          </div>
      </div>

      </form>
    
  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>



</body>
</html>
<script type="text/javascript">
  function payment_function(value,error_field){
    $('#updated_payment_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('advance_payment/get_payment_mode_data')?>",
        data: {'payment_mode_id' : value,'error_field':error_field},
       success: function(msg){
         $('#payment_detail').html(msg);
        }
    });
     
   
  $('.datepicker').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true
                });  
   
  }
  $(document).ready(function(){
   
  //payment_function('<?php echo $form_data['payment_mode'];?>','<?php echo form_error('field_name[]');?>');


    //payment_calc_all();
   
    $('.datepicker').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true
                }); 
  });

$('#btnsubmit').on("click",function(){
     $(':input[id=btnsubmit]').prop('disabled', true);
       $('#opd_form').submit();
  })
</script>