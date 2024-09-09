<?php //print_r($form_data);die; ?>
<div class="modal-dialog modal-lg w-1200px">

  <div class="modal-content">  
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
      <div class="modal-body">  
        
      <div class="row">
        <div class="col-sm-4">
            <div class="row m-b-5">
              <div class="col-xs-6"><label><?php echo $data= get_setting_value('CUSTOMER_REG_NO');?></label></div>
              <div class="col-xs-6">
                <?php echo $form_data['customer_code']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label>Customer Name </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['simulation'].' '.$form_data['customer_name']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label> Mobile No. </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['mobile_no']; ?>
              </div>
            </div>
            <div class="row m-b-5">
              <div class="col-xs-6"><label>Gender </label></div>
              <div class="col-xs-6">
                <?php 
                    $gender = array('0'=>'Female','1'=>'Male','2'=>'Others');
                    echo $gender[$form_data['gender']]; 
                   ?>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-6"><label>Age </label></div>
              <div class="col-xs-6">
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
                    if($form_data['age_h']>0)
                    {
                      $hours = 'Hours';
                      $age .= ", ".$form_data['age_h']." ".$hours;
                    }
                    echo $age; 
                  ?> 
              </div>
            </div>

            

              <div class="row m-b-5">
              <div class="col-xs-6"><label> Address1 </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['address']; ?>
              </div>
            </div>
            <div class="row m-b-5">
              <div class="col-xs-6"><label> Address2 </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['address2']; ?>
              </div>
            </div>
            <div class="row m-b-5">
              <div class="col-xs-6"><label> Address3 </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['address3']; ?>
              </div>
            </div>
            <div class="row m-b-5">
              <div class="col-xs-6"><label> Aadhaar No. </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['adhar_no']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label>Country </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['country']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label> State </label></div>
              <div class="col-xs-6">
                <?php echo ucfirst(strtolower($form_data['state'])); ?> 
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label>City </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['city']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label>PIN Code </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['pincode']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label>Marital Status </label></div>
              <div class="col-xs-6">
                <?php if($form_data['marital_status']==1) { echo 'Married'; } elseif($form_data['marital_status']==0){ echo "Unmarried";} ?>
              </div>
            </div>
            <?php if($form_data['anniversary']!='0000-00-00' && $form_data['anniversary']!='1970-01-01') { ?>
            <div class="row m-b-5">
              <div class="col-xs-6"><label> Marriage Anniversary </label></div>
              <div class="col-xs-6">
                <?php echo date('d-m-Y',strtotime($form_data['anniversary'])); ?>
              </div>
            </div>
            <?php } ?>

            <div class="row m-b-5">
              <div class="col-xs-6"><label>Religion </label></div>
              <div class="col-xs-6">
                <?php echo get_religion_name($form_data['religion_id']); ?>
              </div>
            </div>
            <?php if($form_data['dob']!='1970-01-01' && $form_data['dob']!='0000-00-00'){ ?>
            <div class="row m-b-5">
              <div class="col-xs-6"><label>DOB</label></div>
              <div class="col-xs-6">
                <?php echo date('d-m-Y',strtotime($form_data['dob'])); ?>
              </div>
            </div>
            <?php } ?>
            
             <div class="row m-b-5">
              <div class="col-xs-6"><label> <?php echo $form_data['relation']; ?></label></div>
              <div class="col-xs-6">
                <?php if(!empty($form_data['relation_name'])){ echo $form_data['rel_simulation'].' '.$form_data['relation_name']; } ?>
              </div>
            </div>
     
           

            <div class="row m-b-5">
              <div class="col-xs-6"><label>Mother Name </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['mother']; ?>
              </div>
            </div>


        </div> <!-- 4 -->



        <div class="col-sm-4">

            <div class="row m-b-5">
              <div class="col-xs-6"><label>Guardian Name </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['guardian_name']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label> Guardian Email </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['guardian_email']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label>Guardian Mobile </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['guardian_phone']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label> Relation </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['gardian_relation']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label>customer Email </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['customer_email']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label> Monthly Income </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['monthly_income']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label>Occupation </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['occupation']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label> Insurance Type </label></div>
              <div class="col-xs-6">
                  <?php 
                    $ins_type = array('0'=>'Normal','1'=>'TPA');
                    echo $ins_type[$form_data['insurance_type']]; 
                   ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label>Type </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['insurance_types']; ?> 
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label> Insurance Company </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['insurance_company']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label>Policy No. </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['polocy_no']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label> TPA ID </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['tpa_id']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label>Insurance Amount </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['ins_amount']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label> Authorization No. </label></div>
              <div class="col-xs-6">
                <?php echo $form_data['ins_authorization_no']; ?>
              </div>
            </div>
             

            <div class="row m-b-5">
              <div class="col-xs-6"><label> Status </label></div>
              <div class="col-xs-6">
                <?php 
                    $status = array('0'=>'Inactive','1'=>'Active');
                    echo $status[$form_data['status']]; 
                   ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"><label> Created Date </label></div>
              <div class="col-xs-6">
                <?php echo date('d M Y H:i A', strtotime($form_data['created_date'])); ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-6"></div>
              <div class="col-xs-6"></div>
            </div>
          
        </div> <!-- 4 -->



        <div class="col-sm-4">

            <div class="row m-b-5">
              <div class="col-xs-12 text-center"><label>customer Photo</label></div>
                <?php
                   $img_path  = $file_img = base_url('assets/images/photo.png');
                   if(!empty($form_data['photo']) && file_exists(DIR_UPLOAD_PATH.'customer/'.$form_data['photo']))
                      {
                        $img_path = ROOT_UPLOADS_PATH.'customer/'.$form_data['photo'];
                      }  
                  ?>
                  
            </div>

            <div class="row m-b-5">
              <div class="col-xs-12 text-center"><img src="<?php echo $img_path; ?>" width="100px"></div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-4"><label>Username </label></div>
              <div class="col-xs-8">
                <?php echo $form_data['username']; ?> 
              </div>
            </div>
            <div class="row m-b-5">
              <div class="col-xs-4"><label>Remarks </label></div>
              <div class="col-xs-8">
                <?php echo $form_data['remark']; ?> 
              </div>
            </div>
          
        </div> <!-- 4 -->
      </div> <!-- row -->






           
          
      </div>     
             
             
        <div class="modal-footer">  
           <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
        </div> 
 
        </div><!-- /.modal-content -->
<script>
function comission(ids)
{ 
  var $modal = $('#load_add_comission_modal_popup');
  $modal.load('<?php echo base_url().'doctors/view_comission/' ?>',
  {
    //'id1': '1',
    'id': ids
    },
  function(){
  $modal.modal('show');
  });
} 
</script>        
<div id="load_add_comission_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>     
</div><!-- /.modal-dialog -->