<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title; ?></title>
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
<script src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-select.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-select.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<body>
<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
  //$booking_id=$booking_id;
  //print"<pre>";print_r($recepeint_detail);
 ?>
 <script type="text/javascript">
   var save_method; 
var table;

// Function to load list by ajax
<?php
if(in_array('1629',$users_data['permission']['action'])) 
{
?>

$(document).ready(function() { 
  var book_id= document.getElementById('booking_id').value;
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true,
        searching: false,
        paging: false ,
        "bInfo": false,
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": '<?php echo base_url("pediatrician/pediatrician_prescription/ajax_list")?>/'+book_id,
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

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

 function delete_growth_type(id)
 {    

    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .on('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('pediatrician/pediatrician_prescription/delete/'); ?>"+id, 
                 success: function(result)
                 {
                  //alert(result);
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
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
        .on('click', '#delete', function(e)
        {
            $.ajax({
                      type: "POST",
                      url: "<?php echo base_url('pediatrician/pediatrician_prescription/deleteall');?>",
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

<!-- ============================= Main content start here ===================================== -->
<form method="post" action="<?php echo current_url(); ?>" enctype="multipart/form-data">
<section class="userlist">

<div class="userlist-box">
<div class="row">

  <input type="hidden" name="growth_id" id="growth_id" value="<?php //echo $growth_id; ?>">
  <input type="hidden" name="booking_id" id="booking_id" value="<?php //echo $booking_id; ?>">
  <input type="hidden" name="patient_id" id="patient_id" value="<?php //echo $patient_id; ?>">
    <input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>">
   <div class="col-md-12">
                   
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Recipient Name</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="recipient" value="<?php echo $recepeint_detail['patient_name']; ?>" readonly>
                            </div>
                        </div>
                        <div class="row m-b-5 m-b-5">
                            <div class="col-xs-4"><strong>Age</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="age_y" id="age_y" class="numeric input-tiny2 media_input_tiny" maxlength="3" value="<?php echo $recepeint_detail['age_y']; ?>" readonly> Y &nbsp;
                                <input type="text" name="age_m" id="age_m" class="numeric input-tiny2 media_input_tiny" maxlength="2" value="<?php echo $recepeint_detail['age_m']; ?>" readonly> M &nbsp;
                                <input type="text" name="age_d" id="age_d" class="input-tiny2 media_input_tiny numeric" maxlength="2" value="<?php echo $recepeint_detail['age_d']; ?>" readonly> D
                                <input type="text" name="age_h" class="input-tiny2 media_input_tiny numeric" maxlength="2" value="<?php echo $recepeint_detail['age_h']; ?>" readonly> H
                            </div>
                        </div>
                        <div class="row m-b-5">
                          <div class="col-xs-4"><strong>Gender</strong></div>
                          <div class="col-xs-8">
                          <input type="radio" name="gender" value="1" <?php if($recepeint_detail['gender']==1){ echo 'checked="checked"'; } ?> readonly> Male &nbsp;
                          <input type="radio" name="gender" value="0" <?php if($recepeint_detail['gender']==0){ echo 'checked="checked"'; } ?> readonly> Female
                          <input type="radio" name="gender" value="2" <?php if($recepeint_detail['gender']==2){ echo 'checked="checked"'; } ?> readonly> Others

                          </div>
                        </div>
                        <?php //echo $recepeint_detail['doctor_id']; ?>
                        <div class="row m-b-5">
                          <div class="col-xs-4"><strong>Doctor Name</strong></div>
                          <div class="col-xs-8">
                            <select name="doctor_id" id="doctor_id" disabled>
                              <option value="">Select Doctor</option>
                              <?php

                              if($referal_doctor_list!="empty")
                              {
                              foreach($referal_doctor_list as $dr)
                              {
                              if(!empty($recepeint_detail['doctor_id']))
                              {  
                              if($recepeint_detail['doctor_id']==$dr->id)
                              $drselect="selected=selected";
                              else
                              $drselect="";

                              echo '<option value='.$dr->id.' '.$drselect.' >'.$dr->doctor_name.'</option>';
                              }
                              else
                              {
                              echo '<option value='.$dr->id.'>'.$dr->doctor_name.'</option>'; 
                              }
                              }
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      <div class="row m-b-5">
                        <div class="col-xs-4"><strong>Hospital Name</strong></div>
                      <div class="col-xs-8">
                          <select name="hospital_id" id="hospital_id" onChange="return get_other(this.value)" disabled>
                            <option value="">Select Hospital</option>
                          <?php
                          if($referal_hospital_list!="empty")
                            {
                            //print_r($referal_hospital_list);

                              foreach($referal_hospital_list as $hospital_list)
                              {
                              if(!empty($recepeint_detail['hospital_id']))
                              { 
                              if($recepeint_detail['hospital_id']==$hospital_list['id'])
                              $hosselect="selected=selected";
                              else
                              $hosselect="";

                              echo '<option value='.$hospital_list['id'].' '.$hosselect.' >'.$hospital_list['hospital_name'].'</option>';
                              }

                              }
                            }
                          ?>
                          </select>
                      </div>
                      </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Ward/Bed</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="patient_name" value="<?php echo $recepeint_detail['ward_bed_no']; ?>" readonly>
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Blood Group</strong></div>
                            <div class="col-xs-8">
                        
                                <select disabled>
                                <option value="">Select Blood Group</option>
                                <?php if(!empty($blood_groups))
                                { 
                                  foreach($blood_groups as $blood_grp)
                                  {
                                    $grpselect='';
                                    //echo $recepeint_detail['blood_group_id'];
                                    if($blood_grp->id==$recepeint_detail['blood_group_id'])
                                      {
                                         $grpselect="selected=selected";
                                      }
                                      else
                                      {
                                         $grpselect="";
                                      }?>
                                    <option value="<?php echo $blood_grp->id;?>" <?php echo $grpselect;?> ><?php echo $blood_grp->blood_group;?></option>

                                <?php } }?>
                                   
                                </select>
 <input type="hidden" id="blood_grp_id_cross" name='blood_grp_id_cross' value="<?php echo $recepeint_detail['ward_bed_no']; ?>"/>
                            </div>
                        </div>

                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Component</strong></div>
                            <div class="col-xs-8">
                            <textarea type="text" readonly><?php echo $recipient_component_detail;?></textarea>
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xs-5">
                        

                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Blood Group</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="blood_group" value="" id="blood_group" class="blood_group">
                                <input type="hidden" id="blood_group_id" value=""/>
                            </div>
                        </div>

                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Component Name</strong></div>
                            <div class="col-xs-8" id="option">
                                <select name="camp_id" id="camp_id" onchange="return get_stock_available_donor_data(this.value);">
                                  <option>Select Component</option>
                                  <?php if(!empty($component_list))
                                    { 
                                      foreach($component_list as $comp_name){?>
                                      <option value="<?php echo $comp_name->id; ?>"><?php echo $comp_name->component; ?></option>
                    
                                      <?php 
                                      } 
                                    } ?>
                                </select>
                            </div>
                            <!--<input type="hidden" id="camp_id" value=""/>-->
                        </div>
                        
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Donor Id</strong></div>
                            <div class="col-xs-8" id="donor_option">
                              <select name="donor_id" id="donor_id" class="" onchange="return get_donor_data(this.value);" >
                                  
                              <option value="">Select Donor</option>
                                <?php
                                /*if(!empty($donor_list))
                                {
                                foreach($donor_list as $donarlist)
                                {
                                
                                echo '<option value="'.$donarlist->id.'_'.$donarlist->blood_group.'_'.$donarlist->blood_group_id.'" >'.$donarlist->donor_code.'</option>';
                                }
                                }*/
                                ?> 
                              </select> 
                              <input type="hidden" id="donor_actual_id" value=""/>
                              </div>
                        </div>

                         <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Barcode</strong></div>
                            <div class="col-xs-8" id="bar_code">
                                <select>
                                  <option>Select Barcode</option>
                                </select>
                            </div>
                            <input type="hidden" id="barcode_id" value=""/>
                        </div>

                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Leuco Depleted</strong></div>
                            <div class="col-xs-8">
                                <input type="checkbox" name="leuco_depleted" value="" id="leuco_depleted" >
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Quantity</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="quantity" readonly value="" id="quantity">
                               
                            </div>
                        </div>

                        <div class="row m-b-5">
                            <div class="col-xs-4"></div>
                            <div class="col-xs-8">
                              
                                <span class="text-danger" id="quantity_error"></span>
                            </div>
                        </div>
                       <!--  <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Bag Bar Code</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="bar_code" value="" id="bar_code">
                            </div>
                        </div> -->

                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Expiry Date</strong></div>
                            <div class="col-xs-8">
                                <input type="text" class="expiry_date" name="expiry_date" value="" id="expiry_date">
                                <input type="hidden" name="component_price" id="component_price" />
                                 <a class="btn-new" onclick="donor_list();"> Add </a>
                            </div>
                        </div>

                        


                       
                       
                    </div> <!-- 5 -->
                    
                </div>

  
</div>
<div class="row"> 
      
       <!-- bootstrap data table -->
       <div class="table-responsive">
         <table class="table table-bordered table-striped advice_list" id="donor_list" style="width: 97%;">
            <thead>
                    <tr>
                      <th width="40" align="center"> 
                      <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                      <th>Sr No.</th> 
                      <th>Donor Id</th> 
                      <th>Blood Group</th> 
                      <th> Component Name</th> 
                      <th> Leuco Depleted </th> 
                      <th>Quantity</th> 
                      <th> Bag Bar Code</th>

                      <th> Expiry Date </th> 

                    </tr>
                   <tbody>
                    <?php $donor_list =  $this->session->userdata('donor_list'); ?>
                    <?php $i = 0;
                    if(!empty($donor_list))
                    {
                      $i = 1;
                      $lc_status='';
                      //$total_num = count($donor_list);
                      foreach($donor_list as $donorli)
                      {
                          if($donorli['leuco_depleted']==1)
                          {
                          $lc_status="Yes";
                          }
                          else
                          {
                          $lc_status="No";
                          }

                          
                        ?>
                        <tr>
                          <td>
                          <input type="checkbox" class="checklist part_checkbox booked_checkbox" name="donar_li[]" value="<?php echo $i; //echo $perticuller['charge_id']; ?>" >
                          </td>
                          <td><?php echo $i; ?></td>
                          <td><?php echo $donorli['donor_id']; ?><input type="hidden" name="donar_detail[<?php echo $i; ?>][donar_actual_id]" type="text" id="<?php echo $donorli['donor_actual_id'] ?>" value="<?php echo $donorli['donor_actual_id'] ?>"/></td>
                          <td><?php echo $donorli['blood_group']; ?><input type="hidden" type="text" id="<?php echo $donorli['blood_group_id'];?>" name="donar_detail[<?php echo $i; ?>][blood_group_id]" value="<?php echo $donorli['blood_group_id'];?>"/></td>
                          <td><?php echo $donorli['option']; ?><input type="hidden" type="text" id="<?php echo $donorli['camp_id'];?>" name="donar_detail[<?php echo $i; ?>][camp_id]"  value="<?php echo $donorli['camp_id'];?>"/></td>
                          <td><?php echo $lc_status; ?><input type="hidden" type="text" id="<?php echo $donorli['leuco_depleted'] ?>" name="donar_detail[<?php echo $i; ?>][leuco_depleted]" value="<?php echo $donorli['leuco_depleted'] ?>"/></td>
                          <td><?php echo $donorli['quantity']; ?><input type="hidden" id="<?php echo $donorli['quantity'] ?>" name="donar_detail[<?php echo $i;?>][quantity]" value="<?php echo $donorli['quantity']?>"/></td>
                          <td><?php echo $donorli['bar_code']; ?><input type="hidden" id="<?php echo $donorli['bar_code'] ?>" name="donar_detail[<?php echo $i;?>][bar_code]" value="<?php echo $donorli['bar_code']?>"/></td>
                          <td><?php echo $donorli['expiry_date']; ?><input type="hidden" id="<?php echo $donorli['expiry_date'] ?>" name="donar_detail[<?php echo $i;?>][expiry_date]" value="<?php echo $donorli['expiry_date']?>"/><input type="hidden" id="<?php echo $donorli['component_price'] ?>" name="donar_detail[<?php echo $i;?>][component_price]" value="<?php echo $donorli['component_price']?>"/></td>

                        </tr>
                        <?php
                        $i++;
                      }
                    }
                    ?> 
                    </tbody>
                
            </thead>  
            <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_donor_vals();">
            <i class="fa fa-trash"></i> Delete
            </a>
            <?php  if(!empty($form_error)){ ?>    
            <tr>
            <td colspan="5"><?php  echo form_error('donor_id');  ?></td>
            </tr>
            <?php } ?>
        </table>
        </div>
        
       
    </div>

    <div class="row"><h4 class="text-center">Recipient Vitals</h4></div>
    <div class="row"> 
     <div class="table-responsive">
         <table class="table table-bordered table-striped advice_list" style="width: 97%;">
            <thead>
                    <tr>
                       <th>Vitals</th> 
                      
                      <th> Pre-Transfusion</th> 
                      <th>Start Time</th> 
                      <th>After15 Min.</th> 
                      <th>After15 Min.</th> 
                      <th>After15 Min.</th>
                      <th>After15 Min.</th>
                      <th>After15 Min.</th>
                      <th>After15 Min.</th>
                      <th>After15 Min.</th>
                      <th>After15 Min.</th>
                      <th>After15 Min.</th>
                      <th>After15 Min.</th>
                      
                    </tr>
                    <tbody>
                    <?php 
                      $vl_first='';
                      $vl_second='';
                      $vl_third='';
                      $vl_fourth='';
                      
                      
                      $vl_fifth='';
                      
                      $vl_six='';
                      $vl_seven='';
                      $vl_eight='';
                      $vl_nine='';
                      $vl_ten='';
                      $vl_eleven='';
                      
                      
                     // print '<pre>'; print_r($vitals_list); die;
                    $i=1;
                    foreach($vitals_list as $vital)
                    {

                      if(isset($vital->pre_trans) && $vital->pre_trans!='')
                      {
                        $pre_trans_val= $vital->pre_trans;
                      }
                      else
                      {
                        $pre_trans_val='';
                      }
                      
                      if(isset($vital->value_first) && $vital->value_first!='')
                      {
                        $vl_first= $vital->value_first;
                      }
                      else
                      {
                        $vl_first='';
                      }
                      if(isset($vital->value_second) && $vital->value_second!='')
                      {
                        $vl_second= $vital->value_second;
                      }
                      else
                      {
                        $vl_second='';
                      }
                      if(isset($vital->value_third) && $vital->value_third!='')
                      {
                        $vl_third= $vital->value_third;
                      }
                      else
                      {
                        $vl_third='';
                      }
                      if(isset($vital->value_fourth) && $vital->value_fourth!='')
                      {
                        $vl_fourth= $vital->value_fourth;
                      }
                      else
                      {
                        $vl_fourth='';
                      }
                      
                      if(isset($vital->value_fifth) && $vital->value_fifth!='')
                      {
                        $vl_five= $vital->value_fifth;
                      }
                      else
                      {
                        $vl_five='';
                      }
                      if(isset($vital->value_six) && $vital->value_six!='')
                      {
                        $vl_six= $vital->value_six;
                      }
                      else
                      {
                        $vl_six='';
                      }
                      
                      if(isset($vital->value_seven) && $vital->value_seven!='')
                      {
                        $vl_seven= $vital->value_seven;
                      }
                      else
                      {
                        $vl_seven='';
                      }
                      
                      if(isset($vital->value_eight) && $vital->value_eight!='')
                      {
                        $vl_eight= $vital->value_eight;
                      }
                      else
                      {
                        $vl_eight='';
                      }
                      
                      if(isset($vital->value_nine) && $vital->value_nine!='')
                      {
                        $vl_nine= $vital->value_nine;
                      }
                      else
                      {
                        $vl_nine='';
                      }
                      
                      if(isset($vital->value_ten) && $vital->value_ten!='')
                      {
                        $vl_ten= $vital->value_ten;
                      }
                      else
                      {
                        $vl_ten='';
                      }
                      
                      if(isset($vital->value_eleven) && $vital->value_eleven!='')
                      {
                        $vl_eleven= $vital->value_eleven;
                      }
                      else
                      {
                        $vl_eleven='';
                      }
                      
                    ?>
                      <tr>
                      <td><b><?php echo $vital->vitals_name;?>(<?php echo $vital->vitals_unit;?>)</b></td>
                      
                      <td><input style="width:70px;"  name="vitals_ids[<?php echo $vital->id; ?>][pre_trans]" value="<?php echo $pre_trans_val; ?>" type="text"></td>
                      
                      <td><input type="hidden" name="vitals_ids[<?php echo $vital->id; ?>][vital_id]" value="<?php echo $vital->vitals_id; ?>"/> 
                      <input style="width:70px;"  name="vitals_ids[<?php echo $vital->id; ?>][first_val]" value="<?php echo $vl_first; ?>" type="text"></td>
                      <td><input style="width:70px;"  name="vitals_ids[<?php echo $vital->id; ?>][second_val]" value="<?php echo $vl_second; ?>" type="text"></td>
                      <td><input style="width:70px;"   value="<?php echo $vl_third; ?>" type="text" name="vitals_ids[<?php echo $vital->id; ?>][third_val]"></td>
                      
                      <td><input  style="width:70px;"  value="<?php echo $vl_fourth; ?>" type="text" name="vitals_ids[<?php echo $vital->id; ?>][fourth_val]"></td>
                      
                      
                      <td><input style="width:70px;"  value="<?php echo $vl_five; ?>" type="text" name="vitals_ids[<?php echo $vital->id; ?>][five_val]"></td>
                      
                      <td><input style="width:70px;"   value="<?php echo $vl_six; ?>" type="text" name="vitals_ids[<?php echo $vital->id; ?>][six_val]"></td>
                      
                      
                      <td><input style="width:70px;"   value="<?php echo $vl_seven; ?>" type="text" name="vitals_ids[<?php echo $vital->id; ?>][seven_val]"></td>
                      
                      <td><input style="width:70px;"  value="<?php echo $vl_eight; ?>" type="text" name="vitals_ids[<?php echo $vital->id; ?>][eight_val]"></td>
                      
                      
                      <td><input style="width:70px;"  value="<?php echo $vl_nine; ?>" type="text" name="vitals_ids[<?php echo $vital->id; ?>][nine_val]"></td>
                      
                      <td><input style="width:70px;"  value="<?php echo $vl_ten; ?>" type="text" name="vitals_ids[<?php echo $vital->id; ?>][ten_val]"></td>
                      
                      <td><input style="width:70px;"  value="<?php echo $vl_eleven; ?>" type="text" name="vitals_ids[<?php echo $vital->id; ?>][eleven_val]"></td>
                      
                      
                      
                      
                      
                      
                      
                      </tr>
                    <?php } ?>


                    </tbody>
                
            </thead>  
        </table>
        </div>
        
       
    </div>


    <div class="row">
    <div class="col-md-12">
    <div class="col-xs-5">
      <div class="row m-b-5">
        <div class="col-xs-4"><strong>Starting Transfusion Date</strong></div>
        <div class="col-xs-8">
        <input type="text" class="datepicker" name="transfustion_date" value="<?php echo $form_data['transfustion_date']; ?>">
         <?php if(!empty($form_error)){ echo form_error('transfustion_date'); } ?>
        </div>
      </div>

      <div class="row m-b-5">
        <div class="col-xs-4"><strong>Starting Transfusion Time</strong></div>
        <div class="col-xs-8">
        <input type="text" class="datepicker3" name="transfustion_time" value="<?php echo $form_data['transfustion_time']; ?>">
         <?php if(!empty($form_error)){ echo form_error('transfustion_time'); } ?>
        </div>
      </div>
    </div>
    <div class="col-xs-5">
     <div class="row m-b-5">
        <div class="col-xs-4"><strong>Cross Matching Form</strong></div>
        <div class="col-xs-8">
        <input type="hidden" id="capture_img_right_image" name="capture_img_right_image" value="" />
            <input type="hidden" name="old_requisitation_form"  value="<?php //echo $form_data['old_requisitation_form']; ?>" />
            <input type="file" id="img-input2" accept="image/*" name="cross_match_form" id="cross_match_form">
        </div>
      </div>

      <div class="row m-b-5" id="printdiv">
        <div class="col-xs-4"></div>
        <div class="col-xs-8">
        <div class="col-md-9 frm_s">
           <div class="rec-box">
              <?php 
              $img_path = BASE_PATH.'assets/images/photo.png';
              if(!empty($form_data['file_name']) && isset($form_data['file_name'])&& $form_data['file_name']!=''){
              $img_path = ROOT_UPLOADS_PATH.'blood_bank/recipent_document/'.$form_data['file_name'];
              } 
              ?>
          <img id="pimg2" src="<?php echo $img_path; ?>" class="img-responsive" style="margin: 0px auto;" >


          <a class="btn-custom" id="print_id" style="float:right;" href="javascript:void(0)" onClick="hide_div(); printDiv('printdiv');show_div();" title="Print" ><i class="fa fa-print"></i> Print</a>
          </div>

          </div>
          <div class="col-md-3"></div>
        </div>
      </div>
    </div>



    </div>
    </div>
    <div class="row">
    <div class="btn-box text-center">
        <button class="btn_issue_component" id="issue_component" type="submit" value="issue_component" name="issue_component">
              <i class="fa fa-plus"></i> Send To Issue Component
            </button>
    </div>
    </div>
    <!-- //////////// -->
  
        

    </div>
    <div class="userlist-right">
      <div class="right relative">
          <div class="fixed">
            <button class="btn-save" id="purchase_submit" type="submit" value="save"><i class="fa fa-floppy-o"></i> Save</button>
            <a href="<?php echo base_url('blood_bank/recipient'); ?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
          </div>
        </div>
           
        </div>
        
        </div>
    </div>  <!-- close -->
    
</section>  
</form>
<?php
$this->load->view('include/footer');
?>

  <script>
$('#selectAll').on('click', function () { 
                                                  if ($(this).hasClass('allChecked')) {
                                                      $('.checklist').prop('checked', false);
                                                  } else {
                                                      $('.checklist').prop('checked', true);
                                                  }
                                                  $(this).toggleClass('allChecked');
                                              });


  function hide_div()
  {
  $('#print_id').css('display','none');
  }
  function printDiv(divId) {
      var divContents = $("#printdiv").html();
      var printWindow = window.open('', '', 'height=400,width=800');
    $('#print_id').css('display','none');
      printWindow.document.write('<html moznomarginboxes mozdisallowselectionprint><head><title>Donor General Information</title>');
      printWindow.document.write('</head><body onLoad="style_css();" >');
      printWindow.document.write(divContents);
    
      printWindow.document.write('</body></html>');
      printWindow.document.close();
      printWindow.print();
  }
  function show_div()
  {
   $('#print_id').css('display','block');
  }

  $('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true 
  });

   $('.datepicker3').datetimepicker({
     format: 'LT'
  });
  
    //$('.expiry_date').datepicker({
   // format: 'dd-mm-yyyy', 
   // autoclose: true 
  //});
  
  $('#selectAll').on('click', function () { 
  if ($(this).hasClass('allChecked')) {
  $('.booked_checkbox').prop('checked', false);
  } else {

  $('.booked_checkbox').prop('checked', true);
  }
  $(this).toggleClass('allChecked');
  })</script>
  <script type="text/javascript">
function delete_donor_vals() 
{           
       
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true)
         {
              allVals.push($(this).val());
         } 
       });


       remove_donor_vals(allVals);
 } 

  function remove_donor_vals(allVals)
  {

   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('blood_bank/recipient/remove_donor_list');?>",
              
              data: {donor_li: allVals},
              
              dataType: "json",
              success: function(result) 
              { 

                $('#donor_list').html(result.html_data);
                
                  
              }
          });
   }
  }

  function donor_list()
  {
    var quantity = $('#quantity').val();
    if(quantity==0)
    {
      $('#quantity_error').html('qty not availiable');
      return false;

    }
    var donor_actual_id = $('#donor_actual_id').val();
    var blood_group = $('#blood_group').val();
    var blood_group_id = $('#blood_group_id').val();
    var camp_id = $('#camp_id').val();
    var bar_code = $('#bar_code option:selected').text();
    var component_price = $('#component_price').val();
    var expiry_date = $('#expiry_date').val();
    var donor_id = $('#donor_id option:selected').text();
    var option = $('#option option:selected').text();
    var l_dcheck = $("#leuco_depleted").is(':checked');
    if(l_dcheck==true)
    {
      
      leuco_depleted=1;
    }
    else
    {
      leuco_depleted=0;
    }

    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>blood_bank/recipient/donor_list/", 
            dataType: "json",
            data: 'quantity='+quantity+'&blood_group='+blood_group+'&option='+option+'&leuco_depleted='+leuco_depleted+'&bar_code='+bar_code+'&expiry_date='+expiry_date+'&donor_id='+donor_id+'&camp_id='+camp_id+'&blood_group_id='+blood_group_id+'&donor_actual_id='+donor_actual_id+'&component_price='+component_price,
            success: function(result)
            {
              $('#donor_list').html(result.html_data);
              
            } 
          });
  }
function get_donor_data(donor_id)
{   
  var new_split= donor_id.split('_');
  //var camp_id = $('#camp_id option:selected').text();
     var camp_id = $('#camp_id').val();
  $.ajax({url: "<?php echo base_url(); ?>general/get_donar_data/"+new_split[0]+'/'+camp_id, 
      success: function(result)
      {

        var result = JSON.parse(result);
        
        //$('#option').html(result.option); //20feb
         $('#donor_actual_id').val(new_split[0]);
         $('#quantity').val('1');
         $('#bar_code').html(result.bar_code);
         
         
        $('#blood_group_id').val(new_split[1]); //20feb
        
        
        
         //$('#camp_id').val(comp_id);
        
         $('#expiry_date').val(result.expiry_date);
         $('#component_price').val(result.component_price);
        
      } 
    });
    //get_particular_amount(); 
  }
  
  
  
  function get_stock_available_donor_data(component_id)
{   
 
 var blood_group_id = $('#blood_group_id').val();
  
  $.ajax({url: "<?php echo base_url(); ?>general/get_stock_available_donor_data/"+component_id+'/'+blood_group_id, 
      success: function(result)
      {

        var result = JSON.parse(result);
        
        $('#donor_id').html(result.donor_option); 
        // $('#donor_actual_id').val(new_split[0]);
        //$('#blood_group').val(new_split[1]); //20feb
        //$('#blood_group_id').val(new_split[2]); //20feb
      } 
    });
    //get_particular_amount(); 
  }
  
  
function get_comp_details(comp_id,donor_id)
{
  var blood_group_id=$('#blood_group_id').val();
  $.ajax({url: "<?php echo base_url(); ?>general/get_comp_detail/"+comp_id+'/'+donor_id+'/'+blood_group_id, 
      success: function(result)
      {
        var result = JSON.parse(result);
         if(result.quantity!='' && result.quantity!=0)
         {
          var qty= result.quantity;
         }
         else
         {
          var qty= 0;
         }
         $('#quantity').val(qty);
         $('#camp_id').val(comp_id);
         $('#bar_code').html(result.bar_code);
         $('#expiry_date').val(result.expiry_date);
         //$('#component_price').val(result.component_price);
      } 
    });
}

function bar_code_data(bar_code_id)
{
  var blood_group_id=$('#blood_group_id').val();
  var donor_id=$('#donor_id').val();
  var d= donor_id.split('_');
   //var comp_id=$('#comp_id').val();
   
   var camp_id = $('#camp_id').val();
   
  $.ajax({url: "<?php echo base_url(); ?>general/get_stock_available/"+camp_id+'/'+d[0]+'/'+blood_group_id+'/'+bar_code_id, 
      success: function(result)
      { 
        var result = JSON.parse(result);
         if(result.success=1)
         {
          $('#quantity_error').html(result.message);
          $('#quantity').val(result.quantity);
         }
         else
         {
             $('#quantity_error').html(result.message);
             $('#quantity').val(result.quantity);
         }
         
      } 
    });
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
  function readURL2(input) {
    if (input.files && input.files[0]) 
    {
      var reader = new FileReader();

    reader.onload = function (e) 
    {
      $('#pimg2').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
    }
  }

   $("#img-input2").change(function(){
  readURL2(this);
  });

$('#selectAll').on('click', function () { 
  if ($(this).hasClass('allChecked')) {
      $('.checklist').prop('checked', false);
  } else {
      $('.checklist').prop('checked', true);
  }
  $(this).toggleClass('allChecked');
});
$(document).ready(function(){
});


$(function () {
 
    var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('blood_bank/recipient/get_blood_group_name/'); ?>" + request.term,
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
        var names = ui.item.data.split("|");
          $('.blood_group').val(names[0]);
          $('#blood_group_id').val(names[1]);

        return false;
    }

    $(".blood_group").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
           
        }
    });
    });


</script>

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
    </div>
<!---Div to load popups -->
<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!--Div to load popups -->

</body>
</html>
  <script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"> 
