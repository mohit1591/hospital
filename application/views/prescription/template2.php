<!DCOTYPE html>
<html lang="en">
<head> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css"> 
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>new_menu.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>angular.min.js"></script>
<style type="text/css">
    table.table thead,
    table.table thead th,
    table.table tbody,
    table.table tbody tr,
    table.table tbody tr td{border:none!important;}
</style>
<title><?php echo $page_title; ?></title>
</head>
<body>

<div class="container-fluid">
<?php
$this->load->view('include/prescription-header'); 
?>

    <!-- Main -->
    <div class="row">
        <div class="col-md-12 dashboardBox">
            <div class="row">
               
                <div class="col-sm-4 col-xs-4 text-center">
                    <div class="dash"><?php echo $page_title; ?></div>
                </div>
                <div class="col-sm-4 col-xs-6">
                    <div class="dsh">
                        <span><a id="print-icon" class="print-btn" href="javascript:void(0)" onclick="javascript:print()">Print</a> </span>
                        
                    </div>
                </div>
            </div> <!-- innerRow -->
        </div>
    </div> 
    <div class="row">
        <div class="col-md-12 mainBox" style="height:467px;">
            <div class="row">
                 <!-- 2 -->
                 <!-- 1 -->
                <?php //$this->load->view('include/prescription-left-menu'); left?>
                <div class="col-md-2 col-sm-3 col-xs-12 box">
                    <div class="box1">
                        <div class="head">Prescription Pdf Left</div>
                                         
                    </div>
                </div>
                <div class="col-md-1 col-sm-0 hidden-sm hidden-xs">
                    <img src="<?php echo ROOT_IMAGES_PATH; ?>brdr.png" class="h47">
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    
                    
<div class="modal-dialog modal-lg">

  <div class="modal-content">  
            
      <div class="modal-body">  
         <?php ?>
            <table class="patient-list-view-tbl">
              <tr>
                    <th> <?php echo $data= get_setting_value('PATIENT_REG_NO');?>  </th>
                    <th> OPD Reg. Date  </th>
                    
              </tr>
              <tr><td><?php echo $form_data['patient_code']; ?></td><td><?php echo $form_data['appointment_date']; ?></td></tr>  
              <tr>
                <th><label>Patient Name </label></th>
                <th><label>OPD No : </label></th>
              </tr>
              <tr>
                  <td><?php echo $form_data['patient_name']; ?></td>
                  <td><?php echo $form_data['booking_code']; ?></td> 
            </tr>
              <tr>
                <td><label> Mobile No. </label></td>
                <td><label>Consultant  </label></td>
               
                 
              </tr>
              <tr>
                <td><?php echo $form_data['mobile_no']; ?>  </td>
                <td><?php echo get_doctor_name($form_data['attended_doctor']); ?></td>
              </tr>
              <tr>
                <th>Gender / Age</th>
                <td>
                   <?php 
                    $gender = array('0'=>'Female','1'=>'Male');
                    echo $gender[$form_data['gender']]; 
                   ?>
                   / 
                   <?php 
                    $age = "";
                    if($form_data['age_y']>0)
                    {
                      $year = 'Years';
                      if($form_data['age_y']==1)
                      {
                        $year = 'Year';
                      }
                      $age .= $form_data['age_y']." ".$year;
                    }
                    if($form_data['age_m']>0)
                    {
                      $month = 'Months';
                      if($form_data['age_m']==1)
                      {
                        $month = 'Month';
                      }
                      $age .= ", ".$form_data['age_m']." ".$month;
                    }
                    if($form_data['age_d']>0)
                    {
                      $day = 'Days';
                      if($form_data['age_d']==1)
                      {
                        $day = 'Day';
                      }
                      $age .= ", ".$form_data['age_d']." ".$day;
                    }
                    echo $age; 
                  ?> 
                </td>

              </tr>
              

             <tr> 
                <td><label>Diagnosis</label></td>
                <td>
                  <?php echo $form_data['diagnosis']; ?>
                </td>
            </tr>

            <tr> 
                <td><label>Suggestion</label></td>
                <td>
                  <?php echo $form_data['suggestion']; ?>
                </td>
            </tr>

            <tr> 
                <td><label>Examination</label></td>
                <td>
                  <?php echo $form_data['examination']; ?>
                </td>
            </tr>

            <tr> 
                <td><label>Prrvious History</label></td>
                <td>
                  <?php echo $form_data['prv_history']; ?>
                </td>
            </tr>

             <tr> 
                <td><label>Chief Complaints</label></td>
                <td>
                  <?php echo $form_data['chief_complaints']; ?>
                </td>
            </tr>

            

            <tr> 
                <td><label>Personal History</label></td>
                <td>
                  <?php echo $form_data['personal_history']; ?>
                </td>
            </tr>

            

            <tr> 
                <td><label>Remark</label></td>
                <td>
                  <?php echo $form_data['remark']; ?>
                </td>
            </tr>
            <tr>
             <td colspan="4" align="center"><label>Test</label></td>
            </tr>

            <tr> 
                
                <td colspan="4">
                    <table>
                    <tr>
                      <th>Test Name</th>
                      
                    </tr>
                    <?php 
                    if(!empty($test_data)) { 
                    foreach ($test_data as $testdata) {  
                         ?>
                          <tr>
                              <td><?php echo $testdata->test_name; ?></td>
                              
                          </tr>

                         <?php 
                      }  
                      ?>
                      
                    <?php 
                    }
                    ?>
                    
                   </table> 
                 
                </td>
            </tr>
            <tr>
             <td colspan="4" align="center"><label>Medicine</label></td>
            </tr>

            <tr> 
                
                <td colspan="4">
                    <table>
                    <tr>
                      <th>Medicine Name</th>
                      <th>Medicine Type</th>
                      <th>Medicine Dose</th>
                      <th>Medicine Duration </th>
                      <th>Medicine Advice</th>
                    </tr>
                    <?php 
                    if(!empty($prescription_data)) { 
                    foreach ($prescription_data as $prescriptiondata) {  
                         ?>
                          <tr>
                              <td><?php echo $prescriptiondata->medicine_name; ?></td>
                              <td><?php echo $prescriptiondata->medicine_type; ?></td>
                              <td><?php echo $prescriptiondata->medicine_dose; ?></td>
                              <td><?php echo $prescriptiondata->medicine_frequency; ?></td>
                              <td><?php echo $prescriptiondata->medicine_duration; ?></td>
                              <td><?php echo $prescriptiondata->medicine_advice; ?></td>
                          </tr>

                         <?php 
                      }  
                      ?>
                      
                    <?php 
                    }
                    ?>
                    
                   </table> 
                 
                </td>
            </tr>  
           

            <?php if(!empty($form_data['appointment_date']) && $form_data['appointment_date']!=='0000-00-00'){ ?>
            <tr> 
                <td><label>Next Appointment Date</label></td>
                <td>
                  <?php echo $form_data['appointment_date']; ?>
                </td>
            </tr>
            <?php } ?>

            
            
            </table>
           
          
      </div>     
             
             
         
        </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->


                </div> <!-- 6 -->


                
        </div> <!-- 12 -->
    </div> <!-- row -->
<?php
$this->load->view('include/prescription-footer');
?>
</div> <!-- mainContainerFluid -->



</body>
</html>