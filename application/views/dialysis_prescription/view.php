<div class="modal-dialog modal-lg">

  <div class="modal-content">  
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
      <div class="modal-body">  
         
            <table class="patient-list-view-tbl">
             
              <tr>
                <td><label><?php echo $data= get_setting_value('PATIENT_REG_NO');?> </label></td>
                <td><?php echo $form_data['patient_code']; ?></td>
                <th><label>Patient name : </label></th>
                <td><label>  <?php echo $form_data['patient_name']; ?>  </label></td> 
              </tr>
              <tr>
                <td><label> Mobile No. </label></td>
                <td><?php echo $form_data['mobile_no']; ?>
                </td>
                <td><label>Gender </label></td>
                <td>
                   <?php 
                    $gender = array('0'=>'Female','1'=>'Male');
                    echo $gender[$form_data['gender']]; 
                   ?>
                </td>
              </tr>
              <tr>
                <td><label>Age </label></td>
                <td>
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
                <td><label> &nbsp; </label></td>
                <td> 
                  &nbsp;
                </td>
              </tr>
              
            
             
              
                <td><label>Remark </label></td>
                <td><?php echo $form_data['remark']; ?> 
                </td>
                <td><label> Status </label></td>
                <td> 
                  <?php 
                    $status = array('0'=>'Inactive','1'=>'Active');
                    echo $status[$form_data['status']]; 
                   ?>
                </td>
              </tr>
              <tr>
                <td><label>Create date </label></td>
                <td class="cbranch-td-right">
                  <?php echo date('d M Y H:i A', strtotime($form_data['created_date'])); ?>
                </td>
                <td> </td>
                <td>
                  
                </td>
              </tr> 
            </table>
           
          
      </div>     
             
             
        <div class="modal-footer">  
           <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
        </div> 
 
        </div><!-- /.modal-content -->
       
<div id="load_add_comission_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>     
</div><!-- /.modal-dialog -->