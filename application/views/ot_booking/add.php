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

<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"> 
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('529',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "aaSorting": [],
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('ot_schedule_list/ajax_list')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
}); 
<?php } ?>


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
   <!--  <div class="row m-b-5">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-6">
            <div id="child_branch" class="patient_sub_branch"></div>
          </div>
          <div class="col-md-6 text-right">
            <a href="javascript:void(0)" class="btn-a-search" id="opd_adv_search">
              <i class="fa fa-cubes" aria-hidden="true"></i> 
              Advance Search
            </a>
          </div> 
        </div> 
      </div>
       
    </div>   --> 
    
<form action="<?php echo current_url(); ?>" method="post" id="ot_form">
  <input type="hidden" value="<?php echo $form_data['data_id'] ?>" name="data_id"/>
<div class="row">
  <div class="col-md-12">
    
    <div class="row">
       <div class="row m-b-5">
              <div class="col-md-12">
                  <?php 
                  $checked_reg=''; 
                  $checked_ipd='';
                  $checked_nor='checked=true';
                  $set_payment_hide=0;
                  if(isset($_GET['reg']) && $_GET['reg']!='') 
                  {
                      $checked_reg="checked=true";
                      $checked_nor='';
                  }?>
                  <?php if(isset($form_data['ipd_id']) && $form_data['ipd_id']!='' && $form_data['ipd_id']!=0)
                   {
                      $checked_ipd="checked=true";
                      $checked_nor='';
                      $set_payment_hide=1;
                   }  
                   ?>
                  <?php if(isset($form_data['reg_patient']) && $form_data['reg_patient']!='' && $form_data['reg_patient']!=0)
                   {
                      $checked_reg="checked=true";
                      $checked_nor='';
                   }  
                   ?>
                   <input type="hidden" value="<?php echo $form_data['reg_patient'];?>" id="" name="reg_patient"/>
                  <span class="new_vendor">
                      <input type="radio" name="patient_type_radio" <?php echo $checked_nor; ?> onClick="window.location='<?php echo base_url('ot_booking/');?>add/';">
                    <label>New Patient</label>
                  </span> &nbsp;
                  <span class="new_vendor">
                        <input type="radio" name="patient_type_radio" <?php echo $checked_reg; ?> onClick="window.location='<?php echo base_url('patient');?>';"> 
                        <label>Registered Patient</label>
                  </span> &nbsp;
                 <?php if(in_array('734',$users_data['permission']['action']))
                       { ?>  
                          <?php if($form_data['specialization_id']==EYE_SPECIALIZATION_ID)
                                $style="style=display:none";
                              else
                                $style="";  

                          ?>
                            <span <?php echo $style; ?>  class="new_vendor" id="ipd_option_radio">
                              <input type="radio" name="patient_type_radio" onClick="window.location='<?php echo base_url('ipd_booking');?>';" <?php echo $checked_ipd; ?>> <label>IPD Patient</label>
                            </span>

                <?php } ?>
              </div>
          </div> <!-- innerrow -->
      <div class="col-sm-4">
        
          <!-- <div class="row m-b-5">
            <div class="col-xs-5">
              <a class="btn-custom" href="<?php //echo base_url('ipd_patient');?>"> <i class="fa fa-user"></i> Select Patient</a>
            </div>
            <div class="col-xs-7"></div>
          </div> -->
        
          <div class="row m-b-5">
            <div class="col-xs-5">
              <label>OT Booking No. <sapn class="star">*</sapn></label>
            </div>
            <div class="col-xs-7">
             <input type="hidden" value="<?php echo $form_data['ot_booking_code'];?>" name="ot_booking_code" />
             <?php if(!empty($form_data['ot_booking_code']))
              {
              ?>
              <div class="fright"><b><?php echo $form_data['ot_booking_code'];?></b></div>
              <?php }
              else
              {
              ?>
              <div class="fright"><b>textRegisterID</b></div>
              <?php } ?>
             
              
            </div>
          </div>

            <div class="row m-b-5">
            <div class="col-xs-5">
              <label><?php echo $data= get_setting_value('PATIENT_REG_NO');?><sapn class="star">*</sapn></label>
            </div>
            <div class="col-xs-7">
             <input type="hidden" value="<?php echo $form_data['patient_code'];?>" name="patient_reg_code" />
             <?php if(!empty($form_data['patient_code']))
              {
              ?>
              <div class="fright"><b><?php echo $form_data['patient_code'];?></b></div>
              <?php }
              else
              {
              ?>
              <div class="fright"><b>textRegisterID</b></div>
              <?php } ?>
             
              
            </div>
          </div>
        
          <div class="row m-b-5 <?php if(empty($form_data['ipd_no'])) { echo 'hide';}?>">
            <div class="col-xs-5">
              <label>IPD no. <sapn class="star">*</sapn></label>
            </div>
            <div class="col-xs-7">
              <?php if(!empty($form_data['ipd_no']))
              {
              ?>
              <div class="fright"><b><?php echo $form_data['ipd_no'];?></b></div>
              <?php }
              else
              {
              ?>
              <div class="fright"><b>textIPDid</b></div>
              <?php } ?>
            </div>
          </div>
        <input type="hidden" name="ipd_no" value="<?php echo $form_data['ipd_no'];?>"/>
        <input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id'];?>"/>
         <input type="hidden" name="ipd_id" value="<?php echo $form_data['ipd_id'];?>"/>
          <div class="row m-b-5">
            <div class="col-xs-5">
              <label>patient name <sapn class="star">*</sapn></label>
            </div>
            <div class="col-xs-7">
            <input type="hidden" value="<?php echo $form_data['simulation_id']; ?>" name="simulation_id" />
            <input type="hidden" id="simulationJson" value='<?=json_encode($simulation_list)?>'>
            <select class="mr" name="simulation_id" id="simulation_id" onchange="find_gender(this.value)">
            
              <?php foreach($simulation_list as $simulation){?>
              <option value="<?php echo $simulation->id; ?>" <?php if($form_data['simulation_id']==$simulation->id){ echo 'selected';}?>><?php echo $simulation->simulation;?></option>
              <?php }
              ?>

            </select>
         <input type="text" name="name" id="name"  class="mr-name alpha_numeric_space Cap_item_name" value="<?php echo $form_data['name'];?>" autofocus="" style="width: 96px !important;">
         
          <?php if(in_array('65',$users_data['permission']['action'])) { ?>
                    <a title="Add Simulation" href="javascript:void(0)" onClick="simulation_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a>
               <?php } ?>
          <?php if(!empty($form_error)){ echo form_error('simulation_id'); } ?>
          <?php if(!empty($form_error)){ echo form_error('name'); } ?>
          
            

            </div>
          </div>

            <!-- new code by mamta -->
    <div class="row m-b-5">
      <div class="col-xs-5">
        <strong> 
          <select name="relation_type"  class="w-90px" onchange="father_husband_son(this.value);">
          <?php foreach($gardian_relation_list as $gardian_list) 
          {?>
          <option value="<?php echo $gardian_list->id;?>" <?php if(isset($form_data['relation_type']) && $form_data['relation_type']==$gardian_list->id){echo 'selected';}?>><?php echo $gardian_list->relation;?></option>
          <?php }?>
          </select>

             </strong>
      </div>
      <div class="col-xs-7">
        <select class="mr m_mr" name="relation_simulation_id" id="relation_simulation_id">
          <option value="">Select</option>
          <?php
            if(!empty($simulation_list))
            {
              foreach($simulation_list as $simulation)
              {
                $selected_simulation = '';
               if(in_array($simulation->simulation,$simulations_array)){

                              $selected_simulation = 'selected="selected"';
                              
                         }
                         else{
                              if($simulation->id==$form_data['relation_simulation_id'])
                              {
                                   $selected_simulation = 'selected="selected"';
                              }
                         }
                echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
              }
            }
            ?> 
        </select> 
        <input type="text" value="<?php if(isset($form_data['relation_name'])){ echo $form_data['relation_name'];}?>" name="relation_name" id="relation_name" class="mr-name m_name"/>
      </div>
    </div> <!-- row -->

<!-- new code by mamta -->
        
          <div class="row m-b-5">
            <div class="col-xs-5">
              <label>mobile no.</label>
            </div>
            <div class="col-xs-7">
            <input type="text" name="" value="<?php echo $form_data['country_code'];?>" class="country_code" placeholder="+91" style="width:59px;"> 
              <input type="text" name="mobile_no" class="number" id="mobile_no" maxlength="10" value="<?php echo $form_data['mobile_no'];?>" onKeyPress="return isNumberKey(event);">
                    <div class="f_right">
                    <?php if(!empty($form_error)){ echo form_error('mobile_no'); } ?>
                    </div>
            </div>
          </div>

           <div class="row m-b-5">
            <div class="col-xs-5">
               <label>Gender <span class="star">*</span></label>
            </div>
            <div class="col-xs-7" id="gender">
            <input type="hidden" value="<?php echo $form_data['gender']; ?>" name="gender"/>
               <label><input type="radio" name="gender" value="1" checked <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?> > Male</label> &nbsp;
               <label><input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female</label>
               <label><input type="radio" name="gender" value="2" <?php if($form_data['gender']==2){ echo 'checked="checked"'; } ?> > Others</label>
               <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
            </div>
          </div>
        
          <div class="row m-b-5">
            <div class="col-xs-5">
               <label>Age <sapn class="star">*</sapn></label>
            </div>
            <div class="col-xs-7">
               <div class="fright">
                  <input type="text" name="age_y" class="input-tiny" value="<?php echo $form_data['age_y']; ?>"> Y
                  <input type="text" name="age_m" class="input-tiny" value="<?php echo $form_data['age_m']; ?>"> M
                  <input type="text" name="age_d" class="input-tiny" value="<?php echo $form_data['age_d']; ?>"> D
               </div>
               <?php if(!empty($form_error)){ echo form_error('age_y'); } ?>
            </div>
          </div>
        
          <div class="row m-b-3">
            <div class="col-xs-5">
               <label>Address 1</label>
            </div>
            <div class="col-xs-7">
               <input type="text" name="address" id="" class="address" value="<?php echo $form_data['address'];?>"/>
               <?php //if(!empty($form_error)){ echo form_error('address'); } ?>
            </div>
          </div>

           <div class="row m-b-3">
            <div class="col-xs-5">
               <label>Address 2</label>
            </div>
            <div class="col-xs-7">
               <input type="text"  name="address_second" class="address" value="<?php echo $form_data['address_second'];?>"/>
              
            </div>
          </div>
           <div class="row m-b-3">
            <div class="col-xs-5">
               <label>Address 3</label>
            </div>
            <div class="col-xs-7">
               <input type="text" name="address_third" class="address" value="<?php echo $form_data['address_third'];?>"/>
               
            </div>
          </div>

           <div class="row m-b-3">
            <div class="col-xs-5">
               <label>Aadhaar No.</label>
            </div>
            <div class="col-xs-7">
               <input type="text" name="adhar_no" value="<?php echo $form_data['adhar_no'];?>"/>
               <?php if(!empty($form_error)){ echo form_error('adhar_no'); } ?>
            </div>
          </div>
        
          <div class="row m-b-5 <?php if(empty($form_data['room_no'])) { echo 'hide';}?>">
            <div class="col-xs-5">
               <label>room no. <span class="star">*</span></label>
            </div>
            <div class="col-xs-7">
            <input type="hidden" value="<?php echo $form_data['room_no'];?>" name="room_no"/>
               <select  name="room_no">
                  <option>select room</option>
                  <option value="<?php echo $form_data['room_no'];?>" selected><?php echo $form_data['room_no'];?></option>
               </select>
               <?php if(!empty($form_error)){ echo form_error('room_no'); } ?>
            </div>
          </div>
        
          <div class="row m-b-5 <?php if(empty($form_data['patient_type'])) { echo 'hide';}?>">
            <div class="col-xs-5">
               <label>patient Type <span class="star">*</span></label>
               <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>A doctor within a given area available for consultation by patients insured under the National Health Insurance Scheme It has two type <br> Normal: Having no policy. <br>Panel:Having policy.</span>
               </a>
               </sup>
            </div>
            <div class="col-xs-7">
             <input type="hidden" value="<?php echo $form_data['patient_type']; ?>" name="patient_type"/>
               <label><input type="radio" name="patient_type" <?php if($form_data['patient_type']==1){ echo 'checked="checked"'; } ?>> normal</label> &nbsp;
               <label><input type="radio" name="patient_type" <?php if($form_data['patient_type']==2){ echo 'checked="checked"'; } ?>>panel</label>

                <?php if(!empty($form_error)){ echo form_error('patient_type'); } ?>
            </div>
          </div>

      </div> <!-- 4 -->


      
      <div class="col-sm-4">

      <div class="row m-b-5">
        <div class="col-xs-4">
           <label>Operation Room </label>
        </div>

        <div class="col-xs-8">
          <select name="operation_room" class="w-145px" id="operation_room">
          <option value="">Select Operation Room</option>
          <?php foreach($ot_room_list as $ot_room)
          {?>
          <option value="<?php echo $ot_room->id;?>" <?php if(isset($form_data['operation_room']) && $form_data['operation_room']== $ot_room->id){echo 'selected';}?>><?php echo $ot_room->room_no;?></option>
          <?php }?>

          </select>
          <?php if(!empty($form_error)){ echo form_error('room_no'); } ?>
          <a title="Add Operation Room" class="btn-new" onclick="add_ot_room_no();"><i class="fa fa-plus"></i> New</a> 
        </div>
        
      </div>

        <div class="row m-b-5">
              <div class="col-md-12" id="op_type">
                 <span class="new_vendor"><input type="radio" name="op_type" value="1" <?php if($form_data['op_type']==1){echo 'checked';} ?> > <label>Operation</label></span> &nbsp;
                  <span class="new_vendor"><input type="radio" name="op_type" value="2"  <?php if($form_data['op_type']==2){echo 'checked';} ?> > <label>Operation Package</label>
                    <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>According to the central government health scheme (CGHS), package is defined as a lump sum cost of inpatient treatment for which a patient has been referred by a competent authority or CGHS to the hospital or diagnostic center.</span></a></sup>
                  </span> &nbsp;
               
              </div>
          </div>
        
          <div class="row m-b-5" id="op_name"  <?php if($form_data['op_type']==1){  }else{ ?> style="display: none;" <?php  } ?> >
            <div class="col-xs-4">
               <label>operation name <span class="star">*</span></label>
            </div>

            <div class="col-xs-8">
              <select name="operation_name" id="operation_name" class="w-145px" id="ot_name_id" onchange="get_operation_prices(this.value);">
              <option value="">Select Operation</option>
              <?php foreach($operation_list as $op_list)
              {?>
              <option value="<?php echo $op_list->id;?>" <?php if(isset($form_data['operation_name']) && $form_data['operation_name']== $op_list->id){echo 'selected';}?>  > <?php echo $op_list->name; ?>   </option>
              <?php }?>

              </select>
              <?php if(!empty($form_error)){ echo form_error('operation_name'); } ?>
               <a title="Add Operation" class="btn-new" onclick="add_operation_name();"><i class="fa fa-plus"></i> New</a>
            </div>
            
          </div>
          
       
          <div class="row m-b-5" id="package_name"  <?php if($form_data['op_type']==2){  }else{ ?> style="display: none;" <?php  } ?>>
            <div class="col-xs-4">
               <label>package <span class="star">*</span> </label>
            </div>
            <div class="col-xs-8">
               <select class="w-150px" name="pacakage_name" id="ot_pacakge_id" onchange="get_package_prices(this.value);">
                <option value="" >Select OT package</option>
                 <?php foreach($ot_pacakage_list as $package_list) {?>
                <option value="<?php echo $package_list->id; ?>" <?php  if($package_list->id==$form_data['pacakage_name']){ echo 'selected';}?>><?php echo $package_list->name; ?></option>
                <?php }?>
               </select>
               <a title="Add Package" class="btn-new" onclick="add_package();"><i class="fa fa-plus"></i> New</a>
               <?php if(!empty($form_error)){ echo form_error('pacakage_name'); } ?>
            </div>
          </div>

          <div class="row m-b-5">
            <div class="col-xs-4">
               <label>Operation Booking Date <span class="star">*</span></label>
            </div>
            <div class="col-xs-8">
               <input type="text" name="operation_booking_date" class="datepicker" placeholder="dd/mm/yyyy" value="<?php echo $form_data['operation_booking_date']; ?>">
             
              <!-- <select class="w-50px">
                  <option>AM</option>
                  <option>PM</option>
               </select>-->
              <?php if(!empty($form_error)){ echo form_error('operation_booking_date'); } ?>
            </div>
          </div>
         
        
          <div class="row m-b-5">
            <div class="col-xs-4">
               <label>Operation Date & Time <span class="star">*</span></label>
            </div>
            <div class="col-xs-8">
               <input type="text" name="operation_date" class="w-100px datepicker" placeholder="dd/mm/yyyy" value="<?php echo $form_data['operation_date']; ?>">
               <input type="text" name="operation_time" class="w-95px datepicker3" placeholder="" value="<?php echo $form_data['operation_time']; ?>">
              <!-- <select class="w-50px">
                  <option>AM</option>
                  <option>PM</option>
               </select>-->
              <?php if(!empty($form_error)){ echo form_error('operation_date'); } ?>
            </div>
          </div>
           
            



        <?php if(in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])) { ?>

        <div class="row m-b-5">
            <div class="col-md-12">
               <div class="row">
                 <div class="col-md-4"><b>Referred By</b></div>
                 <div class="col-md-8" id="referred_by">
                   <input type="radio" name="referred_by" value="0" <?php if($form_data['referred_by']==0){ echo 'checked="checked"'; } ?>> Doctor &nbsp;
                    <input type="radio" name="referred_by" value="1" <?php if($form_data['referred_by']==1){ echo 'checked="checked"'; } ?>> Hospital
                    <?php if(!empty($form_error)){ echo form_error('referred_by'); } ?>
                 </div>
               </div>
            </div>
          </div> <!-- row -->
        
        <div class="row m-b-5" id="doctor_div" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?> >
            <div class="col-sm-4"><label>Referred by Doctor</label></div>
            <div class="col-sm-8">
                <select name="referral_doctor" class="m_input_default" id="refered_id" onChange="return get_others(this.value)">
                      <option value="">Select Doctor</option>
                      <?php
                      if(!empty($referal_doctor_list))
                      {
                        foreach($referal_doctor_list as $referal_doctor)
                        {
                          ?>
                            <option <?php if($form_data['referral_doctor']==$referal_doctor->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_doctor->id; ?>"><?php echo $referal_doctor->doctor_name; ?></option>
                            
                          <?php
                        }

                        ?>

                        <option value="0" <?php if(!empty($form_data['ref_by_other'])){ if($form_data['referral_doctor']=='0'){ echo "selected"; }} ?>> Others </option>
                        <?php
                      }
                      ?>
                    </select> 

                <?php if(!empty($form_error)){ echo form_error('referral_doctor'); } ?>
            </div>
        </div>

        <div class="row m-b-5" id="ref_by_other" <?php if(!empty($form_data['ref_by_other']) && $form_data['referral_doctor']=='0'){ }else{ ?> style="display: none;" <?php } ?>>
    
           <div class="col-md-4"><b> Other </b></div>
           <div class="col-xs-8">
            <input type="text" name="ref_by_other" id="ref_other" value="<?php echo $form_data['ref_by_other']; ?>" >
              <?php if(!empty($form_error)){ echo form_error('ref_by_other'); } ?>
         </div>
       </div>
       <!-- row -->






        <div class="row m-b-5" id="hospital_div" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>
    
         <div class="col-md-4"><b>Referred By Hospital</b></div>
         <div class="col-sm-8">
           <select name="referral_hospital" class="m_input_default" id="referral_hospital" >
              <option value="">Select Hospital</option>
              <?php
              if(!empty($referal_hospital_list))
              {
                foreach($referal_hospital_list as $referal_hospital)
                {
                  ?>
                    <option <?php if($form_data['referral_hospital']==$referal_hospital->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_hospital->id; ?>"><?php echo $referal_hospital->hospital_name; ?></option>
                    
                  <?php
                }
              }
              ?>

              
            </select> 
            <?php if(!empty($form_error)){ echo form_error('referral_hospital'); } ?>
         </div>
      
  </div> <!-- row -->
<?php } else if(in_array('38',$users_data['permission']['section']) && !in_array('174',$users_data['permission']['section'])){ 

    ?>
    <div class="row m-b-5">
            <div class="col-sm-4"><label>Referred By Doctor<span class="star">*</span></label></div>
            <div class="col-sm-8">
                <select name="referral_doctor" class="m_input_default" id="refered_id" onChange="return get_others(this.value)">
                      <option value="">Select Doctor</option>
                      <?php
                      if(!empty($referal_doctor_list))
                      {
                        foreach($referal_doctor_list as $referal_doctor)
                        {
                          ?>
                            <option <?php if($form_data['referral_doctor']==$referal_doctor->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_doctor->id; ?>"><?php echo $referal_doctor->doctor_name; ?></option>
                            
                          <?php
                        }

                        ?>

                        <option value="0" <?php if(!empty($form_data['ref_by_other'])){ if($form_data['referral_doctor']=='0'){ echo "selected"; }} ?>> Others </option>
                        <?php
                      }
                      ?>
                    </select> 

                <?php if(!empty($form_error)){ echo form_error('referral_doctor'); } ?>
            </div>
        </div>

        <div class="row m-b-5" id="ref_by_other" <?php if(!empty($form_data['ref_by_other']) && $form_data['referral_doctor']=='0'){ }else{ ?> style="display: none;" <?php } ?>>
    
           <div class="col-md-4"><b> Other </b></div>
           <div class="col-xs-8">
            <input type="text" name="ref_by_other" id="ref_other" value="<?php echo $form_data['ref_by_other']; ?>" >
              <?php if(!empty($form_error)){ echo form_error('ref_by_other'); } ?>
         </div>
       </div>
       <!-- row -->
         <input type="hidden" name="referred_by" value="0">
  <input type="hidden" name="referral_hospital" value="0">
    <?php
    }else if(!in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])){

        ?>
        <div class="row m-b-5">
    
         <div class="col-md-4"><b>Referred by Hospital</b></div>
         <div class="col-sm-8">
           <select name="referral_hospital" class="m_input_default" id="referral_hospital" >
              <option value="">Select Hospital</option>
              <?php
              if(!empty($referal_hospital_list))
              {
                foreach($referal_hospital_list as $referal_hospital)
                {
                  ?>
                    <option <?php if($form_data['referral_hospital']==$referal_hospital->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_hospital->id; ?>"><?php echo $referal_hospital->hospital_name; ?></option>
                    
                  <?php
                }
              }
              ?>

              
            </select> 
            <?php if(!empty($form_error)){ echo form_error('referral_hospital'); } ?>
         </div>
      
  </div> <!-- row -->

  <input type="hidden" name="referred_by" value="1">
  <input type="hidden" name="referral_doctor" value="0">  
  <?php } ?>


        <?php if($specialization_list!="" && !empty($specialization_list)) { ?>
              <div class="row m-b-5">
              <div class="col-md-4"><b>Specialization</b></div>
              <div class="col-sm-8">
              <select name="specialization_id" id="specialization_id" onchange="hide_show_ipd_option(this.value);">
              <option>Select Specialization</option>
        <?php foreach($specialization_list as $list) {  ?>
              <option  <?php if($form_data['specialization_id']==$list->id){ echo 'selected="selected"'; } ?>  value="<?php echo $list->id; ?>"><?php echo $list->specialization; ?></option>
        <?php } ?>
              </select>
              </div>
              </div>
        <?php } ?>


          <div class="row">
            <div class="col-xs-4">
               <label>Doctor List</label>
            </div>
            <div class="col-xs-8">
               <input type="text" class=" m-b-5 doctor_name_ot inputFocus" name="doctor_name" id="doctor_name_ot" >
               <input type="hidden" class=" m-b-5 doctor_id_ot inputFocus" name="doctor_id" id="doctor_id_ot" >
               <div class="p-t-2px">
                  <a title="Add Doctor" class="btn-new" onclick="add_doctor_list();">Add</a>
                  <a title="Remove Doctor" class="btn-new" onclick="remove_row();">Delete</a>
               </div>
            </div>
          </div>

        
          <div class="row m-t-5 m-b-5">
            <div class="col-xs-12">
               <div class="row">
                  <div class="col-sm-4"></div>
                 <!--  <div class="col-sm-7 ot_booking_delete">
                     <a class="btn-new">Delete</a>
                  </div> -->
               </div>
               <div class="ot_border">
               <from id="deleteform">
                  <table class="table table-bordered table-striped ot_table" id="doctor_list">
                     <thead class="bg-theme">
                        <tr>
                           <th><input type="checkbox" name="" onClick="toggle(this);add_check();"></th>
                           <th>S.No.</th>
                           <th>Doctor Name</th>
                        </tr>
                     </thead>
                     <tbody id="append_doctor_list">
                       <?php $i=1;if(!empty($doctor_list)){
                        
                        foreach($doctor_list as $key=>$value){
                        ?>

                       <tr><td><input type="checkbox" name="doctor_names[<?php echo $key?>][]" checked value="<?php echo $value[0]; ?>" class="child_checkbox"/><td><?php echo $i; ?></td><td><?php echo $value[0];?></td></tr>

                       <?php $i++;} }?>
                     </tbody>
                  </table>
                  </form>
               </div>         
            </div>
          </div>

          <?php if($form_data['specialization_id']==EYE_SPECIALIZATION_ID){ ?>
                <div class="row m-b-5" id="eye_operated_block"  style="display:block;">
          <?php } else { ?>
                <div class="row m-b-5" id="eye_operated_block"  style="display:none;">
          <?php } ?>        

            <div class="col-xs-4">
               <label>Eye to be Operated<span class="star">*</span></label>
            </div>
            <div class="col-xs-8">
                <input type="radio" name="operated_eye" id="operated_eye" value="1" checked="true" >Left
                <input type="radio" name="operated_eye" id="operated_eye" <?php if($form_data['operated_eye']==2 ) { echo "checked=true"; } ?> value="2">Right
                <input type="radio" name="operated_eye" id="operated_eye" value="3" <?php if($form_data['operated_eye']==3 ) { echo "checked=true"; } ?>>Both
               <!--  <input type="hidden" name="operated_eye" id="operated_eye" value="0" > -->
            </div>
          </div>

          <?php if($form_data['specialization_id']==EYE_SPECIALIZATION_ID){ ?>
                <div id="eye_operated_block_hidden"></div>
          <?php } else { ?>
                <div id="eye_operated_block_hidden"><input type="hidden" name="operated_eye" id="operated_eye" value="0" > </div>
          <?php } ?>
          
          <div class="row m-b-5">
            <div class="col-xs-4">
               <label>Remarks </label> <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>You can write your own remarks or you can select from dropdown Remarks Master.</span>
               </a>
               </sup>
            </div>
            <div class="col-xs-8">
            
            <select  name="remarks_list" class="w-250px" onchange="new_remark_field(this.value);"> &nbsp;
               <option value="">Select remarks</option>
               <?php foreach($remarks_list as $remarks){?>
                <option value="<?php echo $remarks->remarks; ?>"><?php echo $remarks->remarks; ?></option>
                <?php }?>
               </select>    
              <textarea name="remarks" class="m_input_default" id="remarks_id"><?php echo $form_data['remarks']; ?></textarea>
               <?php if(!empty($form_error)){ echo form_error('remarks'); } ?>
            </div>
          </div>
        
          <div class="row m-b-5">
            <div class="col-xs-4">
               <!-- <label>Remarks <span class="star">*</span></label> -->
            </div>
            <div class="col-xs-8">
               <button class="btn-update" type="submit" id="btnsubmit"> <i class="fa fa-floppy-o"></i> Submit</button>
               <a class="btn-anchor" href="<?php echo base_url('ot_booking');?>"> <i class="fa fa-sign-out"></i> Exit</a>
            </div>
          </div>
      </div> <!-- 4 -->


    <?php 
    
    if($set_payment_hide==0) { ?>  

      <div class="col-xs-4 media_margin_left" id="payment_box">
        <div class="col-md-12">
          <div class="row m-b-5 opd_m_left">
            <div class="col-md-5"><b>Mode of Payment</b></div>
              <div class="col-md-7 opd_p_left">
                <select  name="payment_mode" class="m_input_default" onChange="payment_function(this.value,'');">
                  <?php foreach($payment_mode as $payment_mode) 
                    {?>
                      <option value="<?php echo $payment_mode->id;?>" <?php if($form_data['payment_mode']== $payment_mode->id){ echo 'selected';}?>><?php echo $payment_mode->payment_mode;?></option>
                    <?php }?>
                </select>
              </div>
            </div>
        </div>

         <div id="payment_detail">
            <?php if(!empty($form_data['field_name']))
                 { foreach ($form_data['field_name'] as $field_names) {
                     $tot_values= explode('_',$field_names);

                    ?>

                  <div class="row m-b-5">
                    <div class="col-md-12">
                      <div class="row">
                       <div class="col-md-5"><b><?php echo $tot_values[1];?><span class="star">*</span></b></div> 
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
                    </div>
                  </div>

                  
                   <?php } }?>

         </div>

         <div class="row m-b-5">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Total Amount</b></div>
                         <div class="col-md-7">
                              <input type="text" readonly=""  name="total_amount" id="total_amount" class="price_float m_input_default" value="<?php echo number_format($form_data['total_amount'],2,'.', ''); ?>">
                         </div>
                    </div>
               </div>
          </div> <!-- row -->
<?php
$discount_val_setting = get_setting_value('ENABLE_DISCOUNT'); 
if($discount_val_setting==1)
{
?>  
         <div class="row m-b-5">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Discount</b>
                          <input type="text" style="float:right;" onkeyup="return discount_prcnt(this.value);" placeholder="%" value="<?php echo $form_data['discount_percent']; ?>" name="discount_percent" id="discount_percent" class="input-tiny price_float">
                         </div>
                         <div class="col-md-7">
                              <input type="text" name="discount" class="price_float m_input_default" onchange="check_paid_amount();discount_vals();"  id="discount" value="<?php echo number_format($form_data['discount'],2,'.', ''); ?>">
                         </div>
                    </div>
               </div>
          </div>
<?php } else { ?>
      <input type="hidden" name="discount" readonly="" id="discount" value="0">
<?php } ?>
          <div class="row m-b-5">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Net Amount</b></div>
                         <div class="col-md-7">
                              <input type="text" readonly="" name="net_amount" class="price_float m_input_default" id="net_amount" value="<?php echo number_format($form_data['net_amount'],2,'.', ''); ?>">
                         </div>
                    </div>
               </div>
          </div> <!-- row -->
          <div class="row m-b-5">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Paid Amount</b></div>
                         <div class="col-md-7">
                              <input type="text" name="paid_amount" class="price_float m_input_default" id="paid_amount" value="<?php echo number_format($form_data['paid_amount'],2,'.', '');  ?>" onkeyup="set_paid_and_balance_amount(this.value);">
                         </div>
                    </div>
               </div>
          </div> <!-- row -->
        <div class="row m-b-5" >
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Balance</b></div>
                         <div class="col-md-7">
                              <input type="text" name="balance" id="balance" class="price_float" value="<?php echo number_format($form_data['balance'],2,'.', '');  ?>" onkeyup="set_paid_and_balance_amount(this.value);">
                         </div>
                    </div>
               </div>
          </div> 

    </div>

    <?php } else { ?>

      <input type="hidden" value="0"  name="total_amount" id="total_amount">
      <input type="hidden" value="0"  name="discount"     id="discount">
      <input type="hidden" value="0"  name="net_amount"   id="net_amount">
      <input type="hidden" value="0"  name="paid_amount"  id="paid_amount">
      <input type="hidden" value="0"  name="balance"      id="balance"  >
      <input type="hidden" value="0"  name="payment_mode"      id="payment_mode"  >
      <input type="hidden" value="0"  name="discount_percent"      id="discount_percent"  >

    <?php } ?>
      <div class="col-sm-4"></div> <!-- 4 -->

    </div> <!-- inner row -->




  </div>
</div>

</form>
    
  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<!--new css-->

<!--new css-->
<script>
 function discount_vals()
  {
     var timerA = setInterval(function(){  
          payment_calc();
          clearInterval(timerA); 
        }, 100);
  }


  function payment_calc(percent_val)
  {
      var total_amount=$("#total_amount").val();
      var discount_amount= $("#discount").val();
      $("#discount").val(discount_amount);  
      var net_amount=$("#total_amount").val() - discount_amount;
      $("#net_amount").val(net_amount);
      $("#paid_amount").val(net_amount);
      $("#balance").val('0.00');
  }
  
  
function check_paid_amount()
{
    var total_amount = $('#total_amount').val();
    var discount = $('#discount').val();
    var net_amount = $('#net_amount').val();
    var paid_amount = $('#paid_amount').val();
    var balance = $('#balance').val();
    
    if(parseInt(discount)>parseInt(total_amount))
    {
      alert('Discount amount can not be greater than total amount');
      $('#discount').val('0.00');
      return false;
    }
    if(parseInt(paid_amount)>parseInt(net_amount))
    {
      alert('Paid amount can not be greater than total amount');
      $('#paid_amount').val(net_amount);
      return false;
    }
}


function get_others(val)
  {
    
    if(val=='0')
    {
      $("#ref_by_other").css("display","block");
    }
    else
    {
      $("#ref_by_other").css("display","none");
    }
  }
 $('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true 
  });

   $('.datepicker3').datetimepicker({
     format: 'LT'
  });

function father_husband_son()
{
   $("#relation_name").css("display","block");
}

   $(function() {
    //alert();
          
              var get_address  =  
             [
              <?php
              $address_list= get_patient_address();
              if(!empty($address_list))
              { 
                 foreach($address_list as $addres_li)
                  { 
                    echo '"'.$addres_li.'"'.',';  
                  }
              }   
              ?> 
            ];
            $( ".address" ).autocomplete({
               source: get_address
            });
         });

// $('#op_type').click(function(){
//   $('#op_name').show();
//   $('#package_name').hide();

//   });

// $('#op_type_p').click(function(){
//   $('#op_name').hide();
//   $('#package_name').show();

//   });
$(document).ready(function() {
    $("input[name$='op_type']").click(function() 
    {

      var test = $(this).val();

      if(test==1)
      {
        $("#package_name").hide();
        //$("#package_name").html('');
        $("#op_name").show();
        
        
      }
      else if(test==2)
      {
        // $("#op_name").hide();
         $("#op_name").css("display","none"); 
          //$("#op_name").html('');
          $("#package_name").show();
         
          //$("#refered_id :selected").val('');
      }
        
    });
});

$(document).ready(function() {
    $("input[name$='referred_by']").click(function() 
    {
      var test = $(this).val();
      if(test==0)
      {
        $("#hospital_div").hide();
        $("#doctor_div").show();
        $('#referral_hospital').val('');
        
      }
      else if(test==1)
      {
          $("#doctor_div").hide();
          $("#ref_by_other").css("display","none"); 
          $("#hospital_div").show();
          $('#refered_id').val('');
          $('#ref_other').val('');
          //$("#refered_id :selected").val('');
      }
        
    });
});


function add_doctor_list(){
var rowCount = $('#doctor_list tr').length;

var doc= $('#doctor_name_ot').val();
var doctor_id= $('#doctor_id_ot').val();

 $.ajax({
        url : "<?php echo base_url('ot_booking/append_doctor_list/'); ?>",
        method: 'post',
        data: {name : doc ,rowCount:rowCount,doctor_id:doctor_id},
        success: function( data ) {
        
         $('#append_doctor_list').append(data);
      }
      });

}
$(document).ready(function(){

   form_submit();
 });

$(function () {

    var i=1;
    var getData = function (request, response) { 
        row = i ;
        specialization_id=$("#specialization_id").val();
        $.ajax({
        url : "<?php echo base_url('ot_booking/get_doctor_name/'); ?>" + request.term +"/"+specialization_id,
        dataType: "json",
        method: 'post',
       data: {
         name_startsWith: request.term,
         
         row_num : row
      },
       success: function( data ) {
         response( $.map( data, function( item ) {
          var code = item.split("|");
          return {
            label: code[0],
            value: code[0],
            data : item
          }
        }));
      }
      });

       
    };


    var selectItem = function (event, ui) {
        //$(".medicine_val").val(ui.item.value);

        var names = ui.item.data.split("|");

          $('.doctor_name_ot').val(names[0]);
          $('.doctor_id_ot').val(names[1]);
          

        return false;
    }


    $(".doctor_name_ot").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
});


function reset_search()
{ 
  $('#start_date_patient').val('');
  $('#end_date_patient').val('');
  $('#reciept_code').val('');
  $('#patient_name').val('');
  $('#mobile_no').val('');
  $.ajax({url: "<?php echo base_url(); ?>opd_billing/reset_search/", 
    success: function(result)
    { 
          
      //document.getElementById("search_form").reset(); 
      reload_table();
    } 
  }); 
}


$('.start_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_datepicker').val();
      $('.end_datepicker').datepicker('setStartDate', start_data); 
      form_submit();
  });

  $('.end_datepicker').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
     form_submit();
  });

function form_submit()
{
  $('#search_form').delay(200).submit();
}
$("#search_form").on("submit", function(event) { 
  event.preventDefault();  
   
  $.ajax({
    url: "<?php echo base_url('opd_billing/advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    { 
      reload_table();        
    }
  });

});


function blank_print(id){ 
  $.ajax({
      url: "<?php echo base_url();?>prescription/print_blank_prescription_pdf/"+id, 
      type: 'post',
      dataType: 'json',
      async: false,
      success: function(response){
        if(response.success)
        { 
          printdiv(response.pdf_template);
         }
         else
         {
          alert(response.msg);   
         }
      },
      }); 
           }

function printdiv(printpage)
{
var headstr = "<html><head><title></title></head><body>";
var footstr = "</body>";
var newstr = printpage
var oldstr = document.body.innerHTML;
//document.getElementById('header').style.display = 'none';
//document.getElementById('footer').style.display = 'none';

document.body.innerHTML = headstr+newstr+footstr;
window.print();
//window.location.reload();
return false;
}







 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 $book_id = $this->session->userdata('ot_book_id');
 ?>
  $('documnet').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print' && isset($book_id)){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('ot_booking/add');?>'; 
    }) ;
   
       
  <?php }?>
 });
 
  function confirm_booking(id)
  {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'opd_billing/confirm_booking/' ?>'+id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }

 function delete_opd_booking(booking_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('opd_billing/delete_booking/'); ?>"+booking_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

function add_package()
{
  var $modal = $('#load_add_ot_pacakge_modal_popup');
  $modal.load('<?php echo base_url().'ot_pacakge/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function add_operation_name()
{
    var $modal = $('#load_add_ot_management_modal_popup');
    $modal.load('<?php echo base_url().'ot_management/add/' ?>',
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
}

function get_ot_pacakge()
{
   $.ajax({url: "<?php echo base_url(); ?>ot_pacakge/ot_summary_dropdown/", 
    success: function(result)
    {
      $('#ot_pacakge_id').html(result); 
    } 
  });
}

function get_ot_name()
{
   $.ajax({url: "<?php echo base_url(); ?>ot_booking/ot_name_dropdown/", 
    success: function(result)
    {
     
      $('#ot_name_id').html(result); 
      
    } 
  });
}
function add_remarks(){
    var $modal = $('#load_add_ot_remarks_modal_popup');
    $modal.load('<?php echo base_url().'ot_remarks/' ?>',
    {
    //'id1': '1',
    //'id2': '2'
    },
    function(){
    $modal.modal('show');
    });
}

function add_ot_room_no(){
    var $modal = $('#load_add_ot_room_master_modal_popup');
    $modal.load('<?php echo base_url().'ot_room/add' ?>',
    {
    //'id1': '1',
    //'id2': '2'
    },
    function(){
    $modal.modal('show');
    });
}



 function toggle(source) 
  {  
     checkboxes = document.getElementsByClassName('child_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }

function new_remark_field(remarks_value){
  $('#remarks_id').val(remarks_value);
}
 /*function openPrintWindow(url, name, specs) {
  var printWindow = window.open(url, name, specs);
    var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
        }
    }
    var sched = setInterval(printAndClose, 200);
    return printWindow;
}*/

/* function print_pdf(id)
 {

    var printWindow = openPrintWindow('< ?php echo base_url(); ?>opd_billing/print_billing_report/'+id, 'windowTitle', 'width=820,height=600');
     var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
        }
    }
    var sched = setInterval(printAndClose, 200);
 }*/


function remove_row(){

jQuery('input:checkbox:checked').parents("tr").remove();

      }
$(document).ready(function(){
  $('#load_add_ot_pacakge_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
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


 function hide_show_ipd_option(val)
 {
   if(val==<?php echo EYE_SPECIALIZATION_ID; ?>)
   {
      $("#ipd_option_radio").css("display","none");
      $("#eye_operated_block").css("display","block");
      $("#eye_operated_block_hidden").html('');
   }
   else
   {
      $("#ipd_option_radio").css("display","inline-block");
      $("#eye_operated_block").css("display","none");
      string="<input type='hidden' name='operated_eye' id='operated_eye' value='0' >";
      $("#eye_operated_block_hidden").html(string);
   }
 }

 function get_operation_prices(val)
 {
   $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('ot_booking/get_amount_details_operation_management');?>",
            data: {op_mgmt_id:val  },
            success: function(result) 
            {
              <?php     if($set_payment_hide==0) { ?> 
              $("#total_amount").val(result.amount);
              $("#net_amount").val(result.amount);
              $("#paid_amount").val(result.amount);
              $("#discount").val('0.00');
              $("#balance").val('0.00');
              $("#discount_percent").val('0.00');
              <?php } ?>
            }
          });
 }

 function get_package_prices(val)
 {
    $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('ot_booking/get_amount_details_operation_package');?>",
            data: {pkg_id:val  },
            success: function(result) 
            {
              <?php     if($set_payment_hide==0) { ?> 
              $("#total_amount").val(result.amount);
              $("#net_amount").val(result.amount);
              $("#paid_amount").val(result.amount);
              $("#discount").val('0.00');
              $("#balance").val('0.00');
              $("#discount_percent").val('0.00');
              <?php } ?>
            }
          });
 }



  function payment_function(value,error_field)
  {
           $('#updated_payment_detail').html('');
                $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('test/get_payment_mode_data')?>",
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

  function discount_prcnt(percent_val)
  {
      var total_amount=$("#total_amount").val();
      var discount_amount=  (percent_val * total_amount)/100;
      $("#discount").val(discount_amount);  
      var net_amount=$("#total_amount").val() - discount_amount;
      $("#net_amount").val(net_amount);
      $("#paid_amount").val(net_amount);
      $("#balance").val('0.00');
  }

  function set_paid_and_balance_amount(val)
  {
      var paid_amount=$("#paid_amount").val();
      var net_amount=$("#net_amount").val();

      set_balance_amount=net_amount-paid_amount;
      $("#balance").val(set_balance_amount);

  }

function find_gender(id){
     if(id!==''){
          $.post('<?php echo base_url().'simulation/find_gender' ?>',{'simulation_id':id},function(result){
               if(result!==''){
                    $("#gender").html(result);
               }
          })
     }
 }


</script> 
<script type="text/javascript">
$(document).ready(function(){
<?php
if(empty($_POST))
{
if((empty($ot_pacakage_list)) || (empty($operation_list)) || (empty($simulation_list)))
{
  
?>  

 
  $('#ot_row_count').modal({
     backdrop: 'static',
      keyboard: false
        })
<?php 
}
}
?>

});
 $("button[data-number=4]").click(function(){
    $('#ot_row_count').modal('hide');
   /* $(this).hide();*/
});
</script>
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css" rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<!-- Confirmation Box -->
  <div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <!-- <a type="button" data-dismiss="modal" class="btn-anchor" id="delete" onClick="return openPrintWindow('< ?php echo base_url("sales_medicine/print_sales_report"); ?>', 'windowTitle', 'width=820,height=600');" target="_blank">Print</a> -->

            <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ot_booking/print_ot_booking_report"); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>

  


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
      <div id="ot_row_count" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content bg-r-border">
            <div class="modal-header bg-theme bg-red"><b><span>Notice</span></b><button type="button" class="close close1" data-dismiss="modal" data-number="4" aria-label="Close"><span aria-hidden="true">×</span></button></div>
          <div class="modal-footer  text-l">
            <?php if(empty($simulation_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Simulation is required.</span></p><?php } ?>
          <?php if(empty($ot_pacakage_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Package is required.</span></p><?php } ?>
          <?php if(empty($operation_list)) { ?>
           <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Operation is required.</span></p>
          <?php } ?>
          </div>
        </div>
      </div>  
    </div> 

<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>

<div id="load_add_ot_pacakge_modal_popup" class="modal fade modal-50" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_ot_management_modal_popup" class="modal fade modal-50" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_add_ot_room_master_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<script>

  $('#btnsubmit').on("click",function(){
     $(':input[id=btnsubmit]').prop('disabled', true);
       $('#ot_form').submit();
  })
 
 <?php
  if(isset($_GET['lid']) && !empty($_GET['lid']) && $_GET['lid']>0 && !empty($lead_ot_id))
{
  ?>
  $(document).ready(function(){
   get_operation_prices('<?php echo $lead_ot_id; ?>');
  });
  <?php
}
  ?>
   function simulation_modal()
  {
      var $modal = $('#load_add_simulation_modal_popup');
      $modal.load('<?php echo base_url().'simulation/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  }
  
  $(document).ready(function(){
  $('#load_add_simulation_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
$("#operation_name").select2();

$(document).ready(function(){
  const json = $("#simulationJson").val();
    const objData = JSON.parse(json);
    const flag = $("#simulation_id option:selected").val();
    console.log(objData);
    // Check if flag exists in objData->id
    const flagExists = objData.some(item => item.id === flag && item.gender == 1);
    const flagExists0 = objData.some(item => item.id === flag && item.gender == 0);
    if(flagExists) {
      $("input[name=gender][value='1']").prop("checked", true);
    } else if(flagExists0) {
        $("input[name=gender][value='0']").prop("checked", true);
    } else {
        $("input[name=gender][value='2']").prop("checked", true);
    }
 
});
</script>

<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
