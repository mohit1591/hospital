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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>  

 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.min.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>locales/bootstrap-datetimepicker.fr.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ============================= -->
<form method="post" id="operation_summary_data" >

<section class="userlist">
  <div class="userlist-box">
    <div class="row">
      <div class="col-xs-5">
        <div class="row m-b-5">
          <div class="col-xs-4"><strong>Booking No.</strong></div>
          <div class="col-xs-8">
              <?php if(!empty($data) && $data!="") { echo $data['booking_code']; } ?>
          </div>
        </div>
        <div class="row m-b-5 m-b-5">
          <div class="col-xs-4"><strong><?php echo $val= get_setting_value('PATIENT_REG_NO');?></strong></div>
            <div class="col-xs-8">
              <?php if(!empty($data) && $data!="") { echo $data['patient_code']; } ?>
            </div>
        </div>
        <div class="row m-b-5">
          <div class="col-xs-4"><strong>Patient Name</strong></div>
          <div class="col-xs-8">
              <?php if(!empty($data) && $data!="") { echo $data['patient_name']; } ?>
          </div>
        </div>
        <div class="row m-b-5">
          <div class="col-xs-4"><strong>Aadhaar No.</strong></div>
          <div class="col-xs-8">
              <?php if(!empty($data) && $data!="") { echo $data['adhar_no']; } ?>
          </div>
        </div>



       <div class="row m-b-5">
          <div class="col-xs-4"><strong>Summary Template</strong></div>
          <div class="col-xs-8">
              <select name="template_list" id="template_list" >
                <option value="">Select Summary Template</option>
                <?php 
                  if(!empty($ot_summary_template) && $ot_summary_template!="")
                  {
                      foreach($ot_summary_template as $ot_template)
                      {
                        echo "<option value=".$ot_template->id." >".$ot_template->name."</option>";
                         
                      }
                  }

                ?>
              </select>
             
          </div>
        </div>

          <div class="row m-b-5 m-b-5">
          <div class="col-xs-4"><strong>Diagnosis</strong></div>
          <div class="col-xs-8"> 
          <textarea placeholder="Diagnosis" id="diagnosis" name="diagnosis"><?php if($summary_data!="empty"){ echo $summary_data['diagnosis']; } ?></textarea> 
          </div>
        </div>

         <div class="row m-b-5 m-b-5">
          <div class="col-xs-4"><strong>OP. Findings</strong></div>
          <div class="col-xs-8"> 
          <textarea placeholder="OP. Findings" id="op_findings" name="op_findings"><?php if($summary_data!="empty"){ echo $summary_data['op_findings']; } ?></textarea> 
          </div>
        </div>
           
   



      </div> <!-- 5 -->
      <div class="col-xs-5">
        <div class="row m-b-5">
          <div class="col-xs-4"><strong>Mobile No.</strong></div>
          <div class="col-xs-8">
          <?php if(!empty($data) && $data!="") { echo $data['mobile_no']; } ?>
          </div>
        </div>
        <div class="row m-b-5 m-b-5">
          <div class="col-xs-4"><strong>Gender</strong></div>
          <div class="col-xs-8">
           <?php if(!empty($data) && $data!="") { if($data['gender']==0){ echo "Male"; } else if($data['gender']==1){ echo "Female"; }  } ?>
          </div>
        </div>

        <?php if($data['specialization_id']==EYE_SPECIALIZATION_ID){  ?>
        <div class="row m-b-5 m-b-5">
          <div class="col-xs-4"><strong>Eye Operated</strong></div>
          <div class="col-xs-8">
           <?php if(!empty($data) && $data!="") { if($data['operated_eye']==0){ echo ""; } else if($data['gender']==1){ echo "Left"; } else if($data['gender']==1){ echo "Right"; } else if($data['gender']==3){ echo "Both"; }  } ?>
          </div>
        </div>
        <?php } ?>

        <div class="row m-b-5 m-b-5">
          <div class="col-xs-4"><strong>Operation/Package</strong></div>
          <div class="col-xs-8"> 
           <?php if(!empty($data) && $data!="") { if($data['op_type']==1){ echo "OT (".$data['ot_pack_name'].")"; } else if($data['op_type']==2) { echo "OT (".$data['ot_pack_name'].")"; }   } ?>
          </div>
        </div>


        


          <div class="row m-b-5 m-b-5">
          <div class="col-xs-4"><strong>Procedures</strong></div>
          <div class="col-xs-8"> 
          <textarea placeholder="Procedures" id="procedures" name="procedures"><?php if($summary_data!="empty"){ echo $summary_data['procedures']; } ?></textarea> 
          </div>
        </div>

         <div class="row m-b-5 m-b-5">
          <div class="col-xs-4"><strong>Post OP. Order</strong></div>
          <div class="col-xs-8"> 
          <textarea placeholder="Post OP. Order" id="pos_op_orders" name="pos_op_orders"><?php if($summary_data!="empty"){ echo $summary_data['pos_op_orders']; } ?></textarea> 
          </div>
        </div>
     


      </div> <!-- 5 -->
    </div>

    
      <input type="hidden" name="patient_id" value="<?php echo $data['patient_id']; ?>">
      <input type="hidden" name="ot_booking_id" value="<?php echo $data['id']; ?>">
      <input type="hidden" name="ot_summary_id" value="<?php echo $rec_id; ?>">
      <div class="row">
      <div class="col-xs-5">
        <div class="row m-b-5">
          <div class="col-xs-4"><strong>OT Procedure</strong></div>
          <div class="col-xs-8">
              <select name="ot_procedure" id="ot_procedure" >
                <option value="">Select OT procedure</option>
                <?php 
                  if(!empty($ot_procedures) && $ot_procedures!="")
                  {
                      foreach($ot_procedures as $ot_procedure)
                      {
                        if($summary_data!='empty')
                        { 
                          if($summary_data['ot_procedure_id']==$ot_procedure->id )
                          { 
                            echo "<option selected=selected value=".$ot_procedure->id." >".$ot_procedure->ot_procedure."</option>";
                          }
                          else
                          {
                            echo "<option value=".$ot_procedure->id." >".$ot_procedure->ot_procedure."</option>";
                          }
                        }
                        else
                        {
                          echo "<option value=".$ot_procedure->id." >".$ot_procedure->ot_procedure."</option>";
                        }  
                      }
                  }

                ?>
              </select>
              <span id="ot_procedure_validate"></span>
          </div>
        </div>

         <div class="row m-b-5">
          <div class="col-xs-4"><strong>OT Post Observations</strong></div>
          <div class="col-xs-8">
              <select name="post_observations" id="post_observations" >
                <option value="">Select Post Observations</option>
                <?php 
                  if(!empty($post_observations) && $post_observations!="empty")
                  {
                      foreach($post_observations as $post_observations)
                      {
                            if($summary_data!='empty')
                            { 
                              if($summary_data['post_observation_id']==$post_observations->id )
                              { 
                                  echo "<option  selected=selected value=".$post_observations->id." >".$post_observations->post_observations."</option>";
                              }
                              else
                              {
                                echo "<option value=".$post_observations->id." >".$post_observations->post_observations."</option>";
                              }
                            }
                            else
                            {
                              echo "<option value=".$post_observations->id." >".$post_observations->post_observations."</option>";
                            }
                      } 
                  }
                ?>
              </select>
              <span id="post_observation_validate"></span>
          </div>
        </div>
      </div>  
      </div>
        <!-- prescription TAB -->
        <div class="tab-content row" style="width:100%;margin-top:15px;">
          <div class="inner_tab_box tab-pane fade class active in" id="tab_prescription">
            <div class="row m-t-10">
              <div class="col-xs-12">
                <div class="well tab-right-scroll">
                  <table id="prescription_name_table" class="table table-bordered table-striped">
                    <tbody>
                      <tr>
                        <?php if($data['booking_type']==1){ ?>
                        <td>Eye Drop</td>
                        <?php } ?>
                        <td>Medicine</td>
                        <td>Medicine Unit</td>
                        <td>Medicine Company</td>
                        <td>Salt</td>
                        <td>Dose</td>
                        <td>Duration (Days)</td>
                        <td>Frequency</td>
                        <td>Advice</td>
                        <td>Date</td>
                        <?php if($data['booking_type']==1){ ?>
                        <td>R</td>
                        <td>L</td>
                        <?php } ?>
                        <td width="80">
                          <a class="btn-w-60" href="javascript:void(0)" onclick="add_rows();">Add</a>
                        </td>
                      </tr>
            <?php if($rec_id==0) {   ?>      
                      <tr id="rec_id_0" >
                      <?php if($data['booking_type']==1){ ?>
                        <td> <input type="hidden" name="medicine[0][is_eyedrop]" value="0" id="is_eye_drop_0">  
                        <input type="checkbox" onclick="check_eye_drop(this);" value="1" id="is_eye_drop_0" name="medicine[0][is_eyedrop]">
                        </td>
                      <?php } else { ?>
                      <input type="hidden" name="medicine[0][is_eyedrop]" value="0" id="is_eye_drop_0">  
                      <?php } ?>
                        <td><input type="text" value="" id="medicine_name_0" class="w-100px medicine_val" name="medicine[0][medicine_name]" onkeyup="get_auto_complete_medicine(this);" ></td>
                        <td><input type="text" value="" id="medicine_unit_0" class="input-small w-100px" name="medicine[0][medicine_unit]" ></td>
                        <td><input type="text" value="" id="medicine_company_0" class="w-100px" name="medicine[0][medicine_company]"></td>
                        <td><input type="text" value="" id="medicine_salt_0" class="w-100px" name="medicine[0][medicine_salt]"></td>
                        <td><input id="medicine_dosage_0" type="text" value="" class="input-small w-100px" name="medicine[0][medicine_dosage]" onkeyup="get_auto_complete_medicine_dosage(this);" ></td>
                        <td><input type="text" value="" class="medicine-name duration_val1 w-100px ui-autocomplete-input" id="medicine_duration_0" name="medicine[0][medicine_duration]" onkeyup="get_auto_complete_medicine_duration(this);" ></td>
                        <td><input type="text" value="" class="medicine-name frequency_val1 w-100px ui-autocomplete-input" id="medicine_frequency_0" name="medicine[0][medicine_frequency]" onkeyup="get_auto_complete_medicine_frequency(this);" ></td>
                        <td><input type="text" value="" class="medicine-name advice_val1 w-100px" name="medicine[0][medicine_advice]" id="medicine_advice_0" onkeyup="get_auto_complete_medicine_advice(this);" ></td>
                        <td><input type="text" value="" class="datepicker_m medicine-name date_val1 w-100px" name="medicine[0][medicine_date]" id="medicine_date_0" onmouseover="show_date_time_picker(this);" ></td>
                        <?php if($data['booking_type']==1){ ?>
                        <td >
                        <input type="hidden" name="medicine[0][right_eye]" id="right_eye_val_0" value="0" >   
                        <input type="checkbox" class="right_eye_appned_0" style="display:none;"  id="right_eye_val_0" value="1" name="medicine[0][right_eye]">
                        </td>
                        <td >
                         <input type="hidden" name="medicine[0][left_eye]" id="left_eye_val_0" value="0" >  
                        <input type="checkbox" class="left_eye_appned_0" style="display:none;"  id="left_eye_val_0" value="1" name="medicine[0][left_eye]">
                        </td>
                        <?php } else {  ?>
                           <input type="hidden" name="medicine[0][right_eye]" id="right_eye_val_0" value="0" >  
                           <input type="hidden" name="medicine[0][left_eye]" id="left_eye_val_0" value="0" > 
                        <?php } ?>
                        <td width="80">
                          
                        </td>
                      </tr>
            <?php } else if($rec_id > 0)  {  if($medicine_data!="empty") { 
              $x=0;
              foreach($medicine_data as $medicines)
              {
                $checked=0;
              ?>                

                    <tr id="rec_id_<?php echo $x; ?>" >
                      <?php if($data['booking_type']==1){ ?>
                        <td> <input type="hidden" name="medicine[<?php echo $x; ?>][is_eyedrop]" value="0" id="is_eye_drop_<?php echo $x; ?>">  
                        <input type="checkbox" <?php if($medicines->is_eye_drop==1){ $checked=1; echo "checked=checked"; } ?> onclick="check_eye_drop(this);" value="1" id="is_eye_drop_<?php echo $x; ?>" name="medicine[<?php echo $x; ?>][is_eyedrop]">
                        </td>
                      <?php } else { ?>
                      <input type="hidden" name="medicine[<?php echo $x; ?>][is_eyedrop]" value="0" id="is_eye_drop_<?php echo $x; ?>">  
                      <?php } ?>
                        <td><input type="text" value="<?php echo $medicines->medicine_name ;?>" id="medicine_name_<?php echo $x; ?>" class="w-100px medicine_val" name="medicine[<?php echo $x; ?>][medicine_name]" onkeyup="get_auto_complete_medicine(this);" ></td>
                        <td><input type="text" value="<?php echo $medicines->medicine_unit ;?>" id="medicine_unit_<?php echo $x; ?>" class="input-small w-100px" name="medicine[<?php echo $x; ?>][medicine_unit]" ></td>
                        <td><input type="text" value="<?php echo $medicines->medicine_company ;?>" id="medicine_company_<?php echo $x; ?>" class="w-100px" name="medicine[<?php echo $x; ?>][medicine_company]"></td>
                        <td><input type="text" value="<?php echo $medicines->medicine_salt ;?>" id="medicine_salt_<?php echo $x; ?>" class="w-100px" name="medicine[<?php echo $x; ?>][medicine_salt]"></td>
                        <td><input id="medicine_dosage_<?php echo $x; ?>" type="text" value="<?php echo $medicines->medicine_dose ;?>" class="input-small w-100px" name="medicine[<?php echo $x; ?>][medicine_dosage]" onkeyup="get_auto_complete_medicine_dosage(this);" ></td>
                        <td><input type="text" value="<?php echo $medicines->medicine_duration ;?>" class="medicine-name duration_val1 w-100px ui-autocomplete-input" id="medicine_duration_<?php echo $x; ?>" name="medicine[<?php echo $x; ?>][medicine_duration]" onkeyup="get_auto_complete_medicine_duration(this);" ></td>
                        <td><input type="text" value="<?php echo $medicines->medicine_frequency ;?>" class="medicine-name frequency_val1 w-100px ui-autocomplete-input" id="medicine_frequency_<?php echo $x; ?>" name="medicine[<?php echo $x; ?>][medicine_frequency]" onkeyup="get_auto_complete_medicine_frequency(this);" ></td>
                        <td><input type="text" value="<?php echo $medicines->medicine_advice ;?>" class="medicine-name advice_val1 w-100px" name="medicine[<?php echo $x; ?>][medicine_advice]" id="medicine_advice_<?php echo $x; ?>" onkeyup="get_auto_complete_medicine_advice(this);" ></td>
                        <td><input type="text" value="<?php if(strtotime($medicines->medicine_date) > 0) { echo date('d-m-Y',strtotime($medicines->medicine_date)); } ?>" class="datepicker_m medicine-name date_val1 w-100px" name="medicine[<?php echo $x; ?>][medicine_date]" id="medicine_date_<?php echo $x; ?>" onmouseover="show_date_time_picker(this);" ></td>
                        <?php if($data['booking_type']==1){ ?>
                        <td>
                        <input type="hidden" name="medicine[<?php echo $x; ?>][right_eye]" id="right_eye_val_<?php echo $x; ?>" value="0" >  
                        <?php if($checked==1){ $style=""; } else { $style="style='display:none'"; } ?>
                        <input type="checkbox" class="right_eye_appned_<?php echo $x; ?>" <?php echo $style; ?>  id="right_eye_val_<?php echo $x; ?>" <?php if($medicines->right_eye==1){ echo "checked=checked"; } ?>  value="1" name="medicine[<?php echo $x; ?>][right_eye]">
                        </td>
                        <td >
                         <input type="hidden" name="medicine[<?php echo $x; ?>][left_eye]" id="left_eye_val_<?php echo $x; ?>" value="0" >  
                        <input type="checkbox" class="left_eye_appned_<?php echo $x; ?>" <?php echo $style; ?>  id="left_eye_val_<?php echo $x; ?>" <?php if($medicines->left_eye==1){ echo "checked=checked"; } ?> value="1" name="medicine[<?php echo $x; ?>][left_eye]">
                        </td>
                        <?php } else {  ?>
                           <input type="hidden" name="medicine[<?php echo $x; ?>][right_eye]" id="right_eye_val_<?php echo $x; ?>" value="0" >  
                           <input type="hidden" name="medicine[<?php echo $x; ?>][left_eye]" id="left_eye_val_<?php echo $x; ?>" value="0" > 
                        <?php } ?>
                        <?php if($x==0){ ?>
                        <td width="80">
                          
                        </td>
                        <?php } else if($x>0) { ?>
                        <td width="80"><a class="btn-w-60" onclick="delete_prescription_medicine(<?php echo $x; ?>);" href="javascript:void(0)">Delete</a></td>
                        <?php } ?>
                      </tr>

            <?php $x++; } } else {  ?>
                   <tr id="rec_id_0" >
                      <?php if($data['booking_type']==1){ ?>
                        <td> <input type="hidden" name="medicine[0][is_eyedrop]" value="0" id="is_eye_drop_0">  
                        <input type="checkbox" onclick="check_eye_drop(this);" value="1" id="is_eye_drop_0" name="medicine[0][is_eyedrop]">
                        </td>
                      <?php } else { ?>
                      <input type="hidden" name="medicine[0][is_eyedrop]" value="0" id="is_eye_drop_0">  
                      <?php } ?>
                        <td><input type="text" value="" id="medicine_name_0" class="w-100px medicine_val" name="medicine[0][medicine_name]" onkeyup="get_auto_complete_medicine(this);" ></td>
                        <td><input type="text" value="" id="medicine_unit_0" class="input-small w-100px" name="medicine[0][medicine_unit]" ></td>
                        <td><input type="text" value="" id="medicine_company_0" class="w-100px" name="medicine[0][medicine_company]"></td>
                        <td><input type="text" value="" id="medicine_salt_0" class="w-100px" name="medicine[0][medicine_salt]"></td>
                        <td><input id="medicine_dosage_0" type="text" value="" class="input-small w-100px" name="medicine[0][medicine_dosage]" onkeyup="get_auto_complete_medicine_dosage(this);" ></td>
                        <td><input type="text" value="" class="medicine-name duration_val1 w-100px ui-autocomplete-input" id="medicine_duration_0" name="medicine[0][medicine_duration]" onkeyup="get_auto_complete_medicine_duration(this);" ></td>
                        <td><input type="text" value="" class="medicine-name frequency_val1 w-100px ui-autocomplete-input" id="medicine_frequency_0" name="medicine[0][medicine_frequency]" onkeyup="get_auto_complete_medicine_frequency(this);" ></td>
                        <td><input type="text" value="" class="medicine-name advice_val1 w-100px" name="medicine[0][medicine_advice]" id="medicine_advice_0" onkeyup="get_auto_complete_medicine_advice(this);" ></td>
                        <td><input type="text" value="" class="datepicker_m medicine-name date_val1 w-100px" name="medicine[0][medicine_date]" id="medicine_date_0" onmouseover="show_date_time_picker(this);" ></td>
                        <?php if($data['booking_type']==1){ ?>
                        <td >
                        <input type="hidden" name="medicine[0][right_eye]" id="right_eye_val_0" value="0" >   
                        <input type="checkbox" class="right_eye_appned_0" style="display:none;"  id="right_eye_val_0" value="1" name="medicine[0][right_eye]">
                        </td>
                        <td >
                         <input type="hidden" name="medicine[0][left_eye]" id="left_eye_val_0" value="0" >  
                        <input type="checkbox" class="left_eye_appned_0" style="display:none;"  id="left_eye_val_0" value="1" name="medicine[0][left_eye]">
                        </td>
                        <?php } else {  ?>
                           <input type="hidden" name="medicine[0][right_eye]" id="right_eye_val_0" value="0" >  
                           <input type="hidden" name="medicine[0][left_eye]" id="left_eye_val_0" value="0" > 
                        <?php } ?>
                        <td width="80">
                          
                        </td>
                      </tr>

            <?php } } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div><!-- medicine div -->

        <textarea placeholder="remark" id="remark" name="remark"><?php if($summary_data!="empty"){ echo $summary_data['remark']; } ?></textarea> 

      </form>
       <input type="button" class="btn-update" name="form_sub" value="Submit" onclick="submit_ot_summary();">
    </div> 
  </section> 

<script type="text/javascript">
 
 $('#template_list').change(function(){  
      var template_id = $(this).val();
      $.ajax({url: "<?php echo base_url(); ?>operation_summary/get_template/"+template_id, 
        success: function(result)
        {
           load_values(result);
        } 
      }); 
  });

  function load_values(jdata)
  {
       var obj = JSON.parse(jdata);
       $('#diagnosis').val(obj.diagnosis);
       $('#op_findings').val(obj.op_findings);
       $('#procedures').val(obj.procedures);
       $('#pos_op_orders').val(obj.pos_op_orders);
       
  };

function check_eye_drop(ref)
{
  chk_val=$(ref).is(":checked"); 
  ref_id=$(ref).attr('id');
  extract_count=ref_id.split("_");
  var cnt=extract_count[3];
  
  if(chk_val==0)
  {
     $(".left_eye_appned_"+cnt).prop('checked',false);
    $(".right_eye_appned_"+cnt).prop('checked',false);
    $(".left_eye_appned_"+cnt).css('display','none');
    $(".right_eye_appned_"+cnt).css('display','none');

  }  
  else
  {
     $(".left_eye_appned_"+cnt).prop('checked',false);
    $(".right_eye_appned_"+cnt).prop('checked',false);
    $(".left_eye_appned_"+cnt).css('display','');
    $(".right_eye_appned_"+cnt).css('display','');
    
  }
}

// function to add more rows
<?php if($medicine_data=="empty") { ?>
var row_count=1;
<?php } else {  ?>
var row_count="<?php echo count($medicine_data); ?>";
<?php } ?>  

function add_rows()
{
  var string='<tr id="rec_id_'+row_count+'">';

  if(<?php echo $data['booking_type']; ?>==1)
  {
    string+='<td><input type="hidden" value="0" name="medicine['+row_count+'][is_eyedrop]" id="is_eye_drop_'+row_count+'" ><input type="checkbox" onclick="check_eye_drop(this);" value="1" id="is_eye_drop_'+row_count+'" name="medicine['+row_count+'][is_eyedrop]"></td>';
  }
  else
  {
    string+='<input type="hidden" name="medicine['+row_count+'][is_eyedrop]" id="is_eye_drop_'+row_count+'" value=0 >';
  }

  string+='<td><input type="text" value="" id="medicine_name_'+row_count+'" class="w-100px medicine_val" name="medicine['+row_count+'][medicine_name]" onkeyup="get_auto_complete_medicine(this);" ></td>'+
              '<td><input type="text" value="" id="medicine_unit_'+row_count+'" class="input-small w-100px" name="medicine['+row_count+'][medicine_unit]"></td>'+
              '<td><input type="text" value="" id="medicine_company_'+row_count+'" class="w-100px" name="medicine['+row_count+'][medicine_company]"></td>'+
              '<td><input type="text" value="" id="medicine_salt_'+row_count+'" class="w-100px" name="medicine['+row_count+'][medicine_salt]"></td>'+
              '<td><input type="text" value="" class="input-small w-100px dosage_val" name="medicine['+row_count+'][medicine_dosage]" id="medicine_dosage_'+row_count+'" onkeyup="get_auto_complete_medicine_dosage(this);"></td>'+
              '<td><input type="text" value="" class="medicine-name duration_val1 w-100px ui-autocomplete-input" id="medicine_duration_'+row_count+'"  name="medicine['+row_count+'][medicine_duration]" onkeyup="get_auto_complete_medicine_duration(this);"></td>'+
              '<td><input type="text" value="" class="medicine-name frequency_val1 w-100px ui-autocomplete-input" id="medicine_frequency_'+row_count+'" name="medicine['+row_count+'][medicine_frequency]" onkeyup="get_auto_complete_medicine_frequency(this);"></td>'+
              '<td><input type="text" value="" class="medicine-name advice_val1 w-100px" name="medicine['+row_count+'][medicine_advice]" id="medicine_advice_'+row_count+'" onkeyup="get_auto_complete_medicine_advice(this);" ></td>'+
              '<td><input type="text" value="" class="datepicker_m medicine-name date_val1 w-100px" name="medicine['+row_count+'][medicine_date]" id="medicine_date_'+row_count+'" onmouseover="show_date_time_picker(this);" ></td>';
              
if(<?php echo $data['booking_type']; ?>==1)
{
  string+='<td id="right_eye_appned_'+row_count+'"><input type="hidden" name="medicine['+row_count+'][right_eye]" id="right_eye_val_'+row_count+'" value="0" ><input type="checkbox" style="display:none;" class="right_eye_appned_'+row_count+'" id="right_eye_val_'+row_count+'" value="1" name="medicine['+row_count+'][right_eye]"></td>'+
    '<td ><input type="hidden" name="medicine['+row_count+'][left_eye]" id="left_eye_val_'+row_count+'" value="0"><input type="checkbox" style="display:none;" class="left_eye_appned_'+row_count+'"   id="left_eye_val_'+row_count+'" value="1" name="medicine['+row_count+'][left_eye]"></td>';
}
else
{
  string+='<input type="hidden" value="0" id="right_eye_val_'+row_count+'" name="medicine['+row_count+'][right_eye]">'+
          '<input type="hidden" id="left_eye_val_'+row_count+'" value="0" name="medicine['+row_count+'][left_eye]">';

}

string+='<td width="80"><a class="btn-w-60" onclick="delete_prescription_medicine('+row_count+');" href="javascript:void(0)">Delete</a></td>';

string+='</tr>';

if(row_count==1)
{
  $(string).insertAfter("#rec_id_0");
}
else
{
  v=row_count-1;
  $(string).insertAfter("#rec_id_"+v);
}

row_count++;

}
// Function to add new rows



// function to autocomplete medicine
function get_auto_complete_medicine(ref)
{
    value =$(ref).val();
    ref_id=$(ref).attr('id');
    extract_count=ref_id.split("_");
    var cnt=extract_count[2];
    $(function () 
    {
      var getData = function (request, response) 
      { 
        $.ajax({
                  url : "<?php echo base_url('eye/prescription_template/get_eye_medicine_auto_vals/'); ?>" + value,
                  dataType: "json",
                  method: 'post',
                  data: {
                          name_startsWith: value,
                          type: 'country_table',
                          //row_num : row
                        },
                  success: function( data ) 
                  {
                    response( $.map( data, function( item ) 
                    {
                      var code = item.split("|");
                      return {
                        label: code[0],
                        value: code[0],
                        data : item,
                          }
                    }));
                  }
              });
      };
      var selectItem = function (event, ui) 
      {
        var names = ui.item.data.split("|");
        $('#'+ref_id).val(names[0]);
        $('#medicine_unit_'+cnt).val(names[1]);
        $('#medicine_company_'+cnt).val(names[3]);
        $('#medicine_salt_'+cnt).val(names[2]);
        return false;
      }
      $("#"+ref_id).autocomplete(
      {
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() 
        {
          //$("#test_val").val("").css("display", 2);
        }
      });
  });
}
// function to autocomplete medicine

// function to autocomplete dosage
function get_auto_complete_medicine_dosage(ref)
{
  value =$(ref).val();
  ref_id=$(ref).attr('id');
  extract_count=ref_id.split("_");
  var cnt=extract_count[2];  
  $(function () {
                  var getData = function (request, response) 
                  { 
                    $.getJSON(
                            "<?php echo base_url('eye/prescription_template/get_eye_dosage_vals/'); ?>" + value,
                                  function (data) {
                                      response(data);
                                  });
                  };
                  var selectItem = function (event, ui) 
                  {
                    $("#medicine_dosage_"+cnt).val(ui.item.value);
                    return false;
                  }
                  $("#medicine_dosage_"+cnt).autocomplete(
                  {
                    source: getData,
                    select: selectItem,
                    minLength: 1,
                    change: function() {
                                           //$("#test_val").val("").css("display", 2);
                                        }
                  });
                });

}
// function to autocomplete dosage


// function to autocomplete duration
function get_auto_complete_medicine_duration(ref)
{
  value =$(ref).val();
  ref_id=$(ref).attr('id');
  extract_count=ref_id.split("_");
  var cnt=extract_count[2];  
  $(function () {
                  var getData = function (request, response) 
                  { 
                    $.getJSON(
                            "<?php echo base_url('eye/prescription_template/get_eye_duration_vals/'); ?>" + value,
                                  function (data) {
                                      response(data);
                                  });
                  };
                  var selectItem = function (event, ui) 
                  {
                    $("#medicine_duration_"+cnt).val(ui.item.value);
                    return false;
                  }
                  $("#medicine_duration_"+cnt).autocomplete(
                  {
                    source: getData,
                    select: selectItem,
                    minLength: 1,
                    change: function() {
                                           //$("#test_val").val("").css("display", 2);
                                        }
                  });
                });

}
// function to autocomplete duration

// function to autocomplete frequency

function get_auto_complete_medicine_frequency(ref)
{
  value =$(ref).val();
  ref_id=$(ref).attr('id');
  extract_count=ref_id.split("_");
  var cnt=extract_count[2];  
  $(function () {
                  var getData = function (request, response) 
                  { 
                    $.getJSON(
                            "<?php echo base_url('eye/prescription_template/get_eye_frequency_vals/'); ?>" + value,
                                  function (data) {
                                      response(data);
                                  });
                  };
                  var selectItem = function (event, ui) 
                  {
                    $("#medicine_frequency_"+cnt).val(ui.item.value);
                    return false;
                  }
                  $("#medicine_frequency_"+cnt).autocomplete(
                  {
                    source: getData,
                    select: selectItem,
                    minLength: 1,
                    change: function() {
                                           //$("#test_val").val("").css("display", 2);
                                        }
                  });
                });

}
// function to autocomplete frequency


function get_auto_complete_medicine_advice(ref)
{
    value =$(ref).val();
  ref_id=$(ref).attr('id');
  extract_count=ref_id.split("_");
  var cnt=extract_count[2];  
  $(function () {
                  var getData = function (request, response) 
                  { 
                    $.getJSON(
                            "<?php echo base_url('eye/prescription_template/get_eye_advice_vals/'); ?>" + value,
                                  function (data) {
                                      response(data);
                                  });
                  };
                  var selectItem = function (event, ui) 
                  {
                    $("#medicine_advice_"+cnt).val(ui.item.value);
                    return false;
                  }
                  $("#medicine_advice_"+cnt).autocomplete(
                  {
                    source: getData,
                    select: selectItem,
                    minLength: 1,
                    change: function() {
                                           //$("#test_val").val("").css("display", 2);
                                        }
                  });
                });
}

function show_date_time_picker(ref)
{
  var id=$(ref).attr('id');
  $('#'+id).datepicker({
    dateFormat: 'dd-mm-yy',
    startDate : new Date(),
    autoclose: true, 
  });
}


function submit_ot_summary()
{
  $.ajax({
            type: "POST",
            dataType:'json',
            url: "<?php echo base_url('operation_summary/save_data');?>",
            data: $("#operation_summary_data").serialize(),
            success: function(result) 
            {
              if(result.st==0)
              {
                $("#ot_procedure_validate").html(result.ot_procedure);
                $("#post_observation_validate").html(result.post_observations);
              }
              else if(result.st==1)
              {

                 flash_session_msg(result.message);
                 window.location.href= "<?php echo base_url('ot_booking'); ?>";
               // location.reload(true);
              }
            }
        });
}


function delete_prescription_medicine(row_id)
{
  $("#rec_id_"+row_id).remove();
}

</script>

<?php
$this->load->view('include/footer');
?>
</body>
</html>