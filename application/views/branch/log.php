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
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <form id="prescription_form" name="prescription_form" action="<?php //echo current_url(); ?>" method="post" enctype="multipart/form-data">
    <!--  // prescription button modal -->
    <input type="hidden" name="data_id" value="<?php //echo $form_data['data_id']; ?>">

               

                <div class="row m-t-10">
                    <div class="col-xs-11">
                      <div class="container">
  
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Total Entry</a></li>
    <li><a data-toggle="tab" href="#menu1">Users
    </a></li>
    <!--<li><a data-toggle="tab" href="#menu2">Menu 2</a></li>
    <li><a data-toggle="tab" href="#menu3">Menu 3</a></li>-->
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <table id="table" class="new_class table table-striped table-bordered users_list" cellspacing="0" width="100%">
                <thead class="bg-theme">
                    <tr>
                        
                        <th style="width: 200px!important;"> Module </th> 
                        <th> Total Entry</th> 
                        <th> Last Entry ID </th> 
                        <th> Last Entry IP </th> 
                       
                        <th> Last Entry Created By </th> 
                        <th> Last Entry Date </th> 
                    </tr>
                </thead> 
                 <?php 
     if(!empty($per_ids))
       {
        //$data_split=array();
            $data_split='';
            $data_split_ipd='';
            $data_split_ot='';
            $data_split_dia='';
            $data_split_pat='';
            $data_split_medicine='';
            $data_split_users='';
            $data_split_pa='';
            $data_split_dr='';
            $data_split_inven='';
            $data_split_vacc='';
            $data_split_medicine_purchase='';
            $data_split_medicine_purchase_return='';
            $data_split_medicine_return='';
            $data_split_vaccine_purchase='';
            $patient_code='';
            $ip_address='';
            $created_date='';
            $created_by='';
            $data_split_inven_allot_return='';
            $data_split_inven_issue_allot='';
            $data_split_inven_purchase='';
            $data_split_inven_purchase_return='';
            $type=0;
             if(in_array('19',$per_ids))
        {
          $data_split_pa='Patients';
         
        $data['total_patients_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'hms_patient',$type);
       
        

          $total_patients='0';
          if($data['total_patients_data'][0]['count']>0)
          {
                 $total_patients=$data['total_patients_data'][0]['count'];
           }
            $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'hms_patient'); 

           if(!empty($data['last_login_details'][0]['patient_code']))

            {

              $patient_code=$data['last_login_details'][0]['patient_code'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            }


                ?> 
               <tbody>
                <tr>
     <td><?php echo $data_split_pa;?></td>
     <td><?php echo $total_patients;?>
    </td>
     <td><?php echo $patient_code;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
     </tr>
                </tbody>  
                          
                           <?php
        

        }

        if(in_array('20',$per_ids))
        {
          $data_split_dr='Doctors';
          $doctor_code='';
          $type=0;

        $data['total_doctor_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'hms_doctors',$type);
        $total_doctor='0';
          if($data['total_doctor_data'][0]['count']>0)
          {
        $total_doctor=$data['total_doctor_data'][0]['count'];
        }
         $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'hms_doctors'); 

           if(!empty($data['last_login_details'][0]['doctor_code']))

            {

              $doctor_code=$data['last_login_details'][0]['doctor_code'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            } 
        
        ?>
        
          <tbody>
                <tr>
     <td><?php echo $data_split_dr;?></td>
     <td><?php echo $total_doctor;?>
</td>
     <td><?php echo $doctor_code;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
         </tr>
                </tbody>
        <?php
       
        }

         if(in_array('85',$per_ids))
        {
          $data_split='OPD Booking';
          $booking_code='';
          $type=2;
        $data['total_opd_data']=get_total_entry_for_branch_opd($users_data['parent_id'],$branch_id,$type,'hms_opd_booking');
        $total_opd='0';
          if($data['total_opd_data'][0]['count']>0)
          {
        $total_opd=$data['total_opd_data'][0]['count'];
        }
        $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'hms_opd_booking'); 

           if(!empty($data['last_login_details'][0]['booking_code']))

            {

              $booking_code=$data['last_login_details'][0]['booking_code'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            } 
        //echo $data_split;
        ?>
       
          <tbody>
                <tr>
     <td><?php echo $data_split;?></td>
     <td><?php echo $total_opd;?>
</td>
    <td><?php echo $booking_code;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
     </tr>
                </tbody>
        <?php
       

        }

         if(in_array('151',$per_ids))
        {
          $data_split='OPD Billing';
          $booking_code='';
          $type=3;
        $data['total_opd_data']=get_total_entry_for_branch_opd($users_data['parent_id'],$branch_id,$type,'hms_opd_booking');
        $total_opd='0';
          if($data['total_opd_data'][0]['count']>0)
          {
        $total_opd=$data['total_opd_data'][0]['count'];
        }
        $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'hms_opd_booking'); 

           if(!empty($data['last_login_details'][0]['booking_code']))

            {

              $booking_code=$data['last_login_details'][0]['booking_code'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            } 
        //echo $data_split;
        ?>
       
          <tbody>
                <tr>
     <td><?php echo $data_split;?></td>
     <td><?php echo $total_opd;?>
</td>
    <td><?php echo $booking_code;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
     </tr>
                </tbody>
        <?php
       

        }

         if(in_array('121',$per_ids))
        {
          $data_split_ipd='IPD Booking';
          $ipd_no='';
          $type=0;
          $data['total_ipd_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'hms_ipd_booking',$type);
           $total_ipd='0';
           if($data['total_ipd_data'][0]['count']>0)
          {
          $total_ipd=$data['total_ipd_data'][0]['count'];
            }
             $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'hms_ipd_booking'); 

           if(!empty($data['last_login_details'][0]['ipd_no']))

            {

              $ipd_no=$data['last_login_details'][0]['ipd_no'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            } 
           // echo $data_split_ipd;
            ?>
             
           <tbody>
                <tr>
     <td><?php echo $data_split_ipd;?></td>
     <td><?php echo $total_ipd;?>
</td>
      <td><?php echo $ipd_no;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
   
     </tr>
                </tbody>
            <?php
       


        }

        if(in_array('134',$per_ids))
        {
          $data_split_ot='OT Booking';
          $booking_code='';
          $type=0;
          $data['total_ot_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'hms_operation_booking',$type);
          $total_ot='0';
           if($data['total_ot_data'][0]['count']>0)
          {
     $total_ot=$data['total_ot_data'][0]['count'];
           }
            $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'hms_operation_booking'); 

           if(!empty($data['last_login_details'][0]['booking_code']))

            {

              $booking_code=$data['last_login_details'][0]['booking_code'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            } 
          
         // echo $data_split_ot;
          ?>
         

          <tbody>
                <tr>
     <td><?php echo $data_split_ot;?></td>
     <td><?php echo $total_ot;?>
</td>
    <td><?php echo $booking_code;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
    
     </tr>
                </tbody>
          <?php
       

        }

         if(in_array('207',$per_ids))
        {
          $data_split_dia='Dialysis Booking';
          $booking_code='';
          $type=0;
          $data['total_dialysis_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'hms_dialysis_booking',$type);
          $total_dialysis='0';
           if($data['total_dialysis_data'][0]['count']>0)
          {
          $total_dialysis=$data['total_dialysis_data'][0]['count'];
           }
           $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'hms_dialysis_booking'); 

           if(!empty($data['last_login_details'][0]['booking_code']))

            {

              $booking_code=$data['last_login_details'][0]['booking_code'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            } 
    
        ?>


             <tbody>
                <tr>
     <td><?php echo $data_split_dia;?></td>
     <td><?php echo $total_dialysis;?>
</td>
    <td><?php echo $booking_code;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
    
     </tr>
                </tbody>

        <?php
       
        }

          if(in_array('145',$per_ids))
        {
          $data_split_pat='Pathology Booking';
          $lab_reg_no='';
          $type=0;
           $data['total_pathology_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'path_test_booking',$type);
           $total_pathology='0';
            if($data['total_pathology_data'][0]['count']>0)
          {
          $total_pathology=$data['total_pathology_data'][0]['count'];
          }
          $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'path_test_booking'); 

           if(!empty($data['last_login_details'][0]['lab_reg_no']))

            {

              $lab_reg_no=$data['last_login_details'][0]['lab_reg_no'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            } 
    
           
           ?>
           
          <tbody>
                <tr>
     <td><?php echo $data_split_pat;?></td>
     <td><?php echo $total_pathology;?>
</td>
      <td><?php echo $lab_reg_no;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
     </tr>
                </tbody>
           <?php
       

        }
        if(in_array('58',$per_ids))
        {
          $total_medicine_purchase='0';
          $purchase_id='';
          $type=0;
          $data_split_medicine_purchase='Medicine Purchase';
          $data['total_medicine_purchase_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'hms_medicine_purchase',$type);

          if($data['total_medicine_purchase_data'][0]['count']>0)
          {
          $total_medicine_purchase=$data['total_medicine_purchase_data'][0]['count'];
          }
          $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'hms_medicine_purchase'); 

           if(!empty($data['last_login_details'][0]['purchase_id']))

            {

              $purchase_id=$data['last_login_details'][0]['purchase_id'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            } 
    

          
        ?>
        <tbody>
                <tr>
     <td><?php echo $data_split_medicine_purchase;?></td>
     <td><?php echo $total_medicine_purchase;?>
</td>
      <td><?php echo $purchase_id;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
     </tr>
                </tbody>
         
        <?php

        }

        if(in_array('59',$per_ids))
        {
          $total_medicine_purchase_return='0';
          $return_no='';
          $type=0;
          $data_split_medicine_purchase_return='Medicine Purchase Return';
          $data['total_medicine_purchase_return_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'hms_medicine_return',$type);

          if($data['total_medicine_purchase_return_data'][0]['count']>0)
          {
          $total_medicine_purchase_return=$data['total_medicine_purchase_return_data'][0]['count'];
          }
          $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'hms_medicine_return'); 

           if(!empty($data['last_login_details'][0]['return_no']))

            {

              $return_no=$data['last_login_details'][0]['return_no'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            } 
          
        ?>
        <tbody>
                <tr>
     <td><?php echo $data_split_medicine_purchase_return;?></td>
     <td><?php echo $total_medicine_purchase_return;?>
</td>
     <td><?php echo $return_no;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
     </tr>
                </tbody>
         
        <?php

        }


        if(in_array('60',$per_ids))
        {
          $total_medicine='0';
          $sale_no='';
          $type=0;
          $data_split_medicine='Medicine Sale';
          $data['total_medicine_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'hms_medicine_sale',$type);

          if($data['total_medicine_data'][0]['count']>0)
          {
          $total_medicine=$data['total_medicine_data'][0]['count'];
          }

          $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'hms_medicine_sale'); 

           if(!empty($data['last_login_details'][0]['sale_no']))

            {

              $sale_no=$data['last_login_details'][0]['sale_no'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            } 
          
        ?>
        <tbody>
                <tr>
     <td><?php echo $data_split_medicine;?></td>
     <td><?php echo $total_medicine;?>
</td>
     <td><?php echo $sale_no;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
     </tr>
                </tbody>
         
        <?php

        }

        if(in_array('61',$per_ids))
        {
          $total_medicine_return='0';
          $return_no='';
          $type=0;
          $data_split_medicine_return='Medicine Sale Return';
          $data['total_medicine_return_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'hms_medicine_sale_return',$type);

          if($data['total_medicine_return_data'][0]['count']>0)
          {
          $total_medicine_return=$data['total_medicine_return_data'][0]['count'];
          }

           $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'hms_medicine_sale_return'); 

           if(!empty($data['last_login_details'][0]['return_no']))

            {

              $return_no=$data['last_login_details'][0]['return_no'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            }
          
        ?>
        <tbody>
                <tr>
     <td><?php echo $data_split_medicine_return;?></td>
     <td><?php echo $total_medicine_return;?>
</td>
    <td><?php echo $return_no;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
     </tr>
                </tbody>
         
        <?php

        }


if(in_array('165',$per_ids))
        {
          $total_inven_purchase='0';
          $purchase_no='';
          $type=0;
          $data_split_inven_purchase='Inventory Purchase';
          $data['total_inven_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'path_purchase_item',$type);

          if($data['total_inven_data'][0]['count']>0)
          {
          $total_inven_purchase=$data['total_inven_data'][0]['count'];
          }
           $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'path_purchase_item'); 

           if(!empty($data['last_login_details'][0]['purchase_no']))

            {

              $purchase_no=$data['last_login_details'][0]['purchase_no'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            }


         
        ?>
 <tbody>
                <tr>
     <td><?php echo $data_split_inven_purchase;?></td>
     <td><?php echo $total_inven_purchase;?>
</td>
      <td><?php echo $purchase_no;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
         </tr>
                </tbody>
        

        <?php

        }
        if(in_array('166',$per_ids))
        {
          $total_inven_purchase_return='0';
          $return_no='';
          $type=0;
          $data_split_inven_purchase_return='Inventory Purchase Return';
          $data['total_inven_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'path_purchase_return_item',$type);

          if($data['total_inven_data'][0]['count']>0)
          {
          $total_inven_purchase_return=$data['total_inven_data'][0]['count'];
          }

                     $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'path_purchase_return_item'); 

           if(!empty($data['last_login_details'][0]['return_no']))

            {

              $return_no=$data['last_login_details'][0]['return_no'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            }
          
         
        ?>
 <tbody>
                <tr>
     <td><?php echo $data_split_inven_purchase_return;?></td>
     <td><?php echo $total_inven_purchase_return;?>
</td>
     <td><?php echo $return_no;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
     </tr>
                </tbody>
        

        <?php

        }
        if(in_array('167',$per_ids))
        {
          $total_inven_issue_allot='0';
          $issue_no='';
          $type=0;
          $data_split_inven_issue_allot='Inventory Issue/Allot';
          $data['total_inven_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'hms_stock_issue_allotment',$type);

          if($data['total_inven_data'][0]['count']>0)
          {
          $total_inven_issue_allot=$data['total_inven_data'][0]['count'];
          }

                  $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'hms_stock_issue_allotment'); 

           if(!empty($data['last_login_details'][0]['issue_no']))

            {

              $issue_no=$data['last_login_details'][0]['issue_no'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            }
         
        ?>
 <tbody>
                <tr>
     <td><?php echo $data_split_inven_issue_allot;?></td>
     <td><?php echo $total_inven_issue_allot;?>
</td>
     <td><?php echo $issue_no;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>     </tr>
                </tbody>
        

        <?php

        }
        if(in_array('168',$per_ids))
        {
          $total_inven_allot_return='0';
          $return_no='';
          $type=0;
          $data_split_inven_allot_return='Inventory Issue/Allot Return';
          $data['total_inven_allot_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'hms_stock_issue_allotment_return_item',$type);

          if($data['total_inven_allot_data'][0]['count']>0)
          {
          $total_inven_allot_return=$data['total_inven_allot_data'][0]['count'];
          }
            $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'hms_stock_issue_allotment_return_item'); 

           if(!empty($data['last_login_details'][0]['return_no']))

            {

              $return_no=$data['last_login_details'][0]['return_no'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            }
          
         
        ?>
 <tbody>
                <tr>
     <td><?php echo $data_split_inven_allot_return;?></td>
     <td><?php echo $total_inven_allot_return;?>
</td>
     <td><?php echo $return_no;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td> 
     </tr>
                </tbody>
        

        <?php

        }
        if(in_array('181',$per_ids))
        {
          $total_vacc='0';
          $purchase_id='';
          $type=0;
          $data_split_vaccine_purchase='Vaccination Purchase';
          $data['total_vacc_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'hms_vaccination_purchase',$type);

          if($data['total_vacc_data'][0]['count']>0)
          {
          $total_vacc=$data['total_vacc_data'][0]['count'];
          }

           $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'hms_vaccination_purchase'); 

           if(!empty($data['last_login_details'][0]['purchase_id']))

            {

              $purchase_id=$data['last_login_details'][0]['purchase_id'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            }
          
  
        ?>
        

          <tbody>
                <tr>
     <td><?php echo $data_split_vaccine_purchase;?></td>
     <td><?php echo $total_vacc;?>
</td>
         <td><?php echo $purchase_id;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
     </tr>
                </tbody>
        <?php

        }

        if(in_array('180',$per_ids))
        {
          $total_vacc='0';
          $return_no='';
          $type=0;
          $data_split_vacc='Vaccination Purchase Return';
          $data['total_vacc_data']=get_total_entry_for_branch($users_data['parent_id'],$branch_id,'hms_vaccination_purchase_return',$type);

          if($data['total_vacc_data'][0]['count']>0)
          {
          $total_vacc=$data['total_vacc_data'][0]['count'];
          }
          $data['last_login_details']=get_total_entry_for_branch_last_login($users_data['parent_id'],$branch_id,'hms_vaccination_purchase_return'); 

           if(!empty($data['last_login_details'][0]['return_no']))

            {

              $return_no=$data['last_login_details'][0]['return_no'];
            }
              if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            }
  
        ?>
        

          <tbody>
                <tr>
     <td><?php echo $data_split_vacc;?></td>
     <td><?php echo $total_vacc;?>
</td>
      <td><?php echo $return_no;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?></td>
     </tr>
                </tbody>
        <?php

        } 

         if(in_array('5',$per_ids))
        {
          $total_users='0';
          $data_split_users='Users';
          $username='';
          $data['total_user_data']=get_total_entry_for_branch_users($branch_id);
          $data['last_login_details']=get_total_entry_for_branch_users_last($users_data['parent_id'],$branch_id,'hms_users');
          //print_r($data['total_user_data']);
          if($data['total_user_data'][0]['count']>0)
          {
          $total_users=$data['total_user_data'][0]['count'];
           if(!empty($data['last_login_details'][0]['username']))

            {

              $username=$data['last_login_details'][0]['username'];
            }
            if(!empty($data['last_login_details'][0]['ip_address']))

            {

              $ip_address=$data['last_login_details'][0]['ip_address'];
            }

            if(!empty($data['last_login_details'][0]['branch_name']))

            {

              $created_by=$data['last_login_details'][0]['branch_name'];
            }

            if(!empty($data['last_login_details'][0]['created_date']))

            {
              if($data['last_login_details'][0]['created_date']!='0000-00-00 00:00:00')

              //$created_date=$data['last_login_details'][0]['created_date'];
             $created_date=date('d-m-Y h:i:sa',strtotime($data['last_login_details'][0]['created_date']));
            }

          }

           
           //print_r($data['last_login_details']); 

          
              


  
        ?>
                 <tbody>
                <tr>
     <td><?php echo $data_split_users;?></td>
     <td><?php echo $total_users;?>
</td>
    <td><?php echo $username;?></td>
   <td><?php echo $ip_address;?></td>
 <td><?php echo $created_by;?></td>
   <td><?php echo $created_date;?>
     </tr>
                </tbody>
        <?php

        }

      }
      ?>
      </table>
    </div>
    <div id="menu1" class="tab-pane fade">
        <table id="table" class="table table-striped table-bordered users_list" cellspacing="0" width="100%">
                <thead class="bg-theme">
                <tr>
                     <th style="width: 150px; text-align:center;">Emp Id</th> 
                        <th>Employee Type</th> 
                        <th>User Name </th>
                         <th>Employee Name </th>
                        <th>Last Login IP </th> 

                       <th>Last Login Date & Time</th> 
 
                       </tr>
                </thead>  
                
           <?php 
           if(!empty($user_list))
           {
            foreach ($user_list as $value) {
              $last_login_time='';
              if((!empty($value->last_login_time)) &&($value->last_login_time!='0000-00-00 00:00:00'))
              {
                $last_login_time=date('d-m-Y h:i:sa',strtotime($value->last_login_time));

              }
             
             ?>
<tbody>
                <tr>
                  <td><?php echo $value->reg_no;?></td>
     <td><?php echo $value->emp_type;?></td>
         <td><?php echo $value->username;?></td>
         
         <td><?php echo $value->name;?></td>

         <td><?php echo $value->last_login_ip;?></td>

<td><?php echo $last_login_time;?></td>

     </tr>
                </tbody>
   <?php
  }
 }

  ?>
                


              
            </table>
    </div>
    
  </div>
</div>


</div> <!-- 11 -->
                




        
      <div class="col-xs-1">
      <div class="prescription_btns">
      
      <a href="<?php echo base_url('branch'); ?>"  class="btn-anchor" >
          <i class="fa fa-sign-out"></i> Exit
        </a>
      </div>


      </div> <!-- row -->


 
</form>
</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->

</body>
</html>