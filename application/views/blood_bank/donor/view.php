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

<?php //print_r($donor_data); ?>

<!-- ======================= Main content start here ========================== -->
<section class="userlist">
<div class="userlist-box">
<div class="col-md-12">


  <div class="row mb-5 donar-border" style=""><b>Donor Details :</b></div>
  <br>
  <?php if(in_array('1538',$users_data['permission']['action'])) {
          ?>
    
      <!-- ********** -->
<div class="row m-b-5">
  <div class="col-md-4">
    <div class="row m-b-5">
      <div class="col-md-4"><b>Donor Id</b></div>
      <div class="col-md-8"><?php echo $donor_data['donor_code']; ?></div>
    </div>

    <div class="row m-b-5">
      <div class="col-md-4"><b>Gender</b></div>
      <div class="col-md-8"><?php echo $donor_data['donor_gender']; ?></div>
    </div>

    <div class="row m-b-5">
      <div class="col-md-4"><b>Reminder Service</b></div>
       <?php 
        if(!empty($donor_data['preferred_reminder_service']))
        {
          $preferred_reminder_service= $donor_data['preferred_reminder_service'];        
        }
        else
        {
           $preferred_reminder_service='-';
        }
        ?>
      <div class="col-md-8"><?php echo $preferred_reminder_service; ?></div>
    </div>

    <div class="row m-b-5">
      <div class="col-md-4"><b>Remarks</b></div>
      <?php 
        if(!empty($donor_data['remark']))
        {
          $remark= $donor_data['remark'];        
        }
        else
        {
           $remark='-';
        }
        ?>
      <div class="col-md-8"><?php echo $remark; ?></div>
    </div>
  </div>  <!-- col-4 -->


  <div class="col-md-4">
     <div class="row m-b-5">
      <div class="col-md-4"><b>Donor Name.</b></div>
      <div class="col-md-8"><?php echo $donor_data['simulation_donor']." ".$donor_data['donor_name']; ?></div>
    </div>
    
    <div class="row m-b-5">
      <div class="col-md-4"><b>Blood Group</b></div>
      <div class="col-md-8"><?php echo ($donor_data['blood_group_id']>0 ? $donor_data['blood_group'] : '-') ; ?></div>
    </div>

    <div class="row m-b-5">
      <div class="col-md-4"><b>Age</b></div>
      <?php $data= hms_patient_age_calulator($donor_data['age_y'],$donor_data['age_m'],$donor_data['age_d']);?>
      <div class="col-md-8"><?php echo $data; ?></div>
    </div>

  </div>
  <div class="col-md-4">
    
    <div class="row m-b-5">
      <div class="col-md-4"><b>Mobile No.</b></div>
       <?php 
        if(!empty($donor_data['mobile_no']))
        {
          $mobile= $donor_data['mobile_no'];        
        }
        else
        {
           $mobile='-';
        }?>
      <div class="col-md-8"><?php echo $mobile; ?></div>
    </div>
    
    <div class="row m-b-5">
      <div class="col-md-4"><b>Previous Donation date</b></div>
      <div class="col-md-8"><?php echo (strtotime($donor_data['previous_donation_date']) > 0 ? date('d-m-Y',strtotime($donor_data['previous_donation_date'])) : '-');  ?></div>
    </div>
  </div>
</div>








<!-- ***** -->
<?php
}
?><br>
  
    <?php if($examination_data!="empty"){ ?>
    <div class="row mb-5 donar-border" style=""><b>Examination Details :</b></div>
<br>
  <div class="row m-b-5">
    <div class="col-md-4">
      <div class="row m-b-5">
        <div class="col-md-4"><b>Illness</b></div>
        <div class="col-md-8"><?php echo $examination_data['illness']; ?></div>
      </div>

      <div class="row m-b-5">
        <div class="col-md-4"><b>Pulse Rate(/min)</b></div>
        <div class="col-md-8"><?php echo $examination_data['pulse']; ?></div>
      </div>

      <div class="row m-b-5">
        <div class="col-md-4"><b>Respiratory Rate</b></div>
        <div class="col-md-8"><?php echo $examination_data['temperature']; ?></div>
      </div>

      
      </div>

        <div class="col-md-4">
      <div class="row m-b-5">
        <div class="col-md-4"><b>Blood Pressure</b></div>
        <div class="col-md-8"><?php echo $examination_data['blood_pressure']; ?></div>
      </div>

      <div class="row m-b-5">
        <div class="col-md-5"><b>Haemoglobin(gms%)</b></div>
        <div class="col-md-7"><?php echo $examination_data['haemoglobin']; ?></div>
      </div>

      <div class="row m-b-5">
        <div class="col-md-4"><b>Outcome</b></div>
        <div class="col-md-8"><?php if($examination_data['outcome']==1) { echo 'Accepted'; } else if($examination_data['outcome']==2) { echo 'Temporary Referral'; } else {echo 'Permanent Referral'; }  ?></div>
      </div>
      
      </div>

        <div class="col-md-4">
      <div class="row m-b-5">
        <div class="col-md-4"><b>Temperature(°F)</b></div>
        <div class="col-md-8"><?php echo $examination_data['temperature']; ?></div>
      </div>

      <div class="row m-b-5">
        <div class="col-md-4"><b>Examiner Name</b></div>
        <div class="col-md-8"><?php echo $examination_data['examiner_name'];  ?></div>
      </div>

      <div class="row m-b-5">
        <div class="col-md-4"><b>Remarks</b></div>
        <div class="col-md-8"><?php echo $examination_data['remark']; ?></div>
      </div>
      </div>
    </div>
    <?php } ?>
    <br>



     
    <?php if($blood_details!="empty"){ ?>
 <div class="row mb-5 donar-border" style=""><b>Blood Details :</b></div>
<br>
  <div class="row m-b-5">
    <div class="col-md-4">
      <div class="row m-b-5">
        <div class="col-md-4"><b>Phlebotomist</b></div>
        <div class="col-md-8"><?php echo ucfirst($blood_details['phlebotomist_name']); ?></div>
      </div>

      <div class="row m-b-5">
        <div class="col-md-4"><b>Blood Expiry Date</b></div>
        <div class="col-md-8"><?php echo date('d-m-Y H:i:s',strtotime($blood_details['expiry_date'])); ?></div>
      </div>

      <div class="row m-b-5">
        <?php if(isset($blood_details['collection_date']) && !empty($blood_details['collection_date']))
      {
        $collection_date= date('d-m-Y',strtotime($blood_details['collection_date'])); ?>
      <?php } 
      else{
        $collection_date= '';
      }?>

      <?php if(isset($blood_details['collection_time']) && !empty($blood_details['collection_time']))
      {
        $collection_time=' '.date('H:i:s',strtotime($blood_details['collection_time'])); ?>
      <?php } 
      else
      {
        $collection_time='';
      }?>
        <div class="col-md-4"><b>Collection Date</b></div>
        <div class="col-md-8"><?php echo $collection_date.$collection_time; ?></div>
      </div>



      <div class="row m-b-5">
        <div class="col-md-4"><b>Quantity</b></div>
        <div class="col-md-8"><?php echo $blood_details['quantity'] ; ?></div>
      </div>

      
      </div>

        <div class="col-md-4">
      <div class="row m-b-5">
        <div class="col-md-4"><b>Blood Bag Type</b></div>
        <div class="col-md-8"><?php echo $blood_details['bag_type']; ?></div>
      </div>

      <div class="row m-b-5">
        <div class="col-md-4"><b>Venipuncture</b></div>
        <div class="col-md-8"><?php echo ($blood_details['venipuncture']==1) ? 'Right':'Left'  ; ?></div>
      </div>

      <div class="row m-b-5">
        <div class="col-md-4"><b>Post Complication</b></div>
        <div class="col-md-8"><?php echo $blood_details['post_name'] ;?></div>
      </div>
      
      </div>

        <div class="col-md-4">
      <div class="row m-b-5">
        <div class="col-md-4"><b>Bar Code</b></div>
        <div class="col-md-8"><?php echo get_bar_code_data($blood_details['id']); ?></div>
      </div>

      <div class="row m-b-5">
        <div class="col-md-4"><b>End Time</b></div>
        <div class="col-md-8"><?php echo date('H:m:s',strtotime($blood_details['end_time']))  ?></div>
      </div>

      <div class="row m-b-5">
        <div class="col-md-4"><b>Remarks</b></div>
        <div class="col-md-8"><?php echo $blood_details['remark']; ?></div>
      </div>
      </div>
    </div>
    <?php } ?>

<br>
     
    <?php if($qc_data!="empty"){ ?>
    <div class="row mb-5 donar-border" style=""><b>Bag QC Details :</b></div>
    <br>
    <div class="row m-b-5">
    <div class="col-md-4">
      <div class="row m-b-5">
        <div class="col-md-4"><b>Technician Name</b></div>
        <div class="col-md-8"><?php echo ucfirst($qc_data['technician_name']); ?></div>
      </div>

      <div class="row m-b-5">
        <div class="col-md-4"><b>Remarks</b></div>
        <div class="col-md-8"><?php echo $qc_data['remark']; ?></div>
      </div>

      

      
      </div>

        <div class="col-md-4">
      <div class="row m-b-5">
        <div class="col-md-4"><b>Blood Condition</b></div>
        <div class="col-md-8"><?php echo ($qc_data['blood_condition']==1) ? 'Accepted' : 'Rejected' ; ?></div>
      </div>

       <div class="row m-b-5">
      <div class="col-md-4"><b>QC Date/Time</b></div>
      <div class="col-md-8">
      <?php if(isset($qc_data['qc_date']) && !empty($qc_data['qc_date']))
      {
        $qc_date= date('d-m-Y',strtotime($qc_data['qc_date'])); ?>
      <?php } 
      else{
        $qc_date= '';
      }?>

      <?php if(isset($qc_data['qc_time']) && !empty($qc_data['qc_time']))
      {
        $qc_time=' '.date('H:i:s',strtotime($qc_data['qc_time'])); ?>
      <?php } 
      else
      {
        $qc_time='';
      }?>
      <?php echo $qc_date.$qc_time; ?>

      </div>
      </div>
      
      </div>

      <div class="col-md-4">
      <div class="row m-b-5">
        <div class="col-md-4"><b>Final Result</b></div>
        <div class="col-md-8"><?php echo $qc_data['final_result']; ?></div>
      </div>

      
      </div>
    </div>




    <div class="row m-b-5">
        <div class="col-md-2"><b>Bag QC Test</b></div>
       
      <?php if($qc_data_fields!="empty") { ?>
        <div class="col-md-10">
          <table style="margin-left:2%;margin-top:2%;border:1px solid #000; padding:4px;width:50%;"> <thead> <tr style='border:1px solid #000;'><td style="width:25%;text-align:center;border:1px solid #000;"><b>Qc Field</b></td><td style="width:25%;text-align:center;border:1px solid #000;" ><b>Method</b></td><td style="width:25%;text-align:center;border:1px solid #000;" ><b>Result</b></td></tr></thead>
        <?php foreach($qc_data_fields as $fields) {
          //print_r($fields->method);
          $method='';
          $get_sub_category=bag_qc_subcategory_name_field($fields->method);
          //print_r($get_sub_category->qc_field);
          if($get_sub_category!='empty')
          {
            $method=$get_sub_category->qc_field;
          }
          else
          {
            $method='';
          }
          $result=''; 
          if($fields->result!='')
          {
            if($fields->result==1)
            {
              $result='Positive';
            }
            else
            {
              $result='Negative';
            }
          }
              ?>
              <tr style='border:1px solid #000;'>
              <td style="width:25%;border:1px solid #000;text-align:center;" ><b><?php echo $fields->qc_field; ?></b></td>
              <td style="width:25%;border:1px solid #000;text-align:center;" ><?php echo $method; ?></td>
              <td style="width:25%;border:1px solid #000;text-align:center;" ><?php echo $result; ?></td>
              </tr>
        <?php } ?> </table> </div> <?php } ?>
    </div>  
      <?php } ?>
<br>
  <?php if($component_data!="empty"){ ?>
    <div class="row mb-5"><div class="col-md-2"><b>Component Extracted From Bag <u><?php echo $blood_details['bag_type']; ?></u></b></div>
    <div class="col-md-10">
     
      <?php if($component_data!="empty") { ?>
      
        <table style="margin-left:2%;margin-top:2%;border:1px solid #000; padding:4px;width:50%;"> <thead> <tr style='border:1px solid #000;'><td style="width:25%;text-align:center;border:1px solid #000;"><b>Component</b></td><td style="width:25%;text-align:center;border:1px solid #000;" ><b>Bar Code</b></td><td style="width:25%;text-align:center;border:1px solid #000;" ><b>Quantity</b></td><td style="width:25%;text-align:center;border:1px solid #000;" ><b>Component Price</b></td><td style="width:25%;text-align:center;border:1px solid #000;" ><b>Expiry Date & Time</b></td></tr></thead>
      <?php foreach($component_data as $cmp) { ?>
            <tr style='border:1px solid #000;'>
            <td style="width:25%;border:1px solid #000;text-align:center;" ><b><?php echo $cmp->component; ?></b></td>
            <td style="width:25%;border:1px solid #000;text-align:center;" ><?php echo $cmp->b_code; ?></td>
            <td style="width:25%;border:1px solid #000;text-align:center;" ><?php if(isset($cmp->b_code) && $cmp->b_code!='') {echo '1';}?></td>
            <td style="width:25%;border:1px solid #000;text-align:center;" ><?php echo $cmp->unit_price;?></td>
            <td style="width:25%;border:1px solid #000;text-align:center;" ><?php echo date('d-m-Y H:i:s',strtotime($cmp->expiry_date)); ?></td>
            </tr>
      <?php } ?> </table> <?php } ?>
     
      <?php } ?>
      </div>
      <button type="button" class="btn-update" onclick="window.location.href='<?php echo base_url('blood_bank/donor'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
    </div>  
    


</div>
  
</div>

</section>
</div>
</body>

</html>