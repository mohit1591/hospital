<div class="modal-dialog modal-lg">

  <div class="modal-content">  
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
      <div class="modal-body form cmnClassF cf">  
         
            <table class="brlist-tbl-view">
              <tr>
                <th><label>Reg. no. : </label></th>
                <td><label>  <?php echo $form_data['reg_no']; ?>  </label></td>
                <td><label>Employee Type : </label></td>
                <td><?php echo $form_data['emp_type']; ?></td>
              </tr>
              <tr>
            
                <td><label>Name </label></td>
                <td><?php  echo $form_data['simulation'].' '.$form_data['name']; ?>
                </td>
                <td><label>Email </label></td>
                <td><?php echo $form_data['email']; ?> 
                </td>
              </tr>
              <tr>
                
                <td><label>Contact No. </label></td>
                <td><?php echo $form_data['contact_no']; ?>
                </td>
              </tr>
              <tr>
                <td><label>DOB </label></td>
               <td><?php if(isset($form_data['dob']) && $form_data['dob']!='1970-01-01'){echo date('d M Y',strtotime($form_data['dob']));} else {echo '';}?> 
                </td>
                <td><label> Age </label></td>
                <td> 
                  <?php echo $form_data['age']; ?>
                </td>
              </tr>
			  
			  <?php /*?>
               <tr>
                <td><label>Anniversary </label></td>
                <td><?php if(isset($form_data['anniversary']) && $form_data['anniversary']!='1970-01-01'){echo date('d M Y',strtotime($form_data['anniversary']));} else {echo '';}?> 
                </td>
              </tr>
			 <?php */?>
			  
              <tr>
                
                <td><label>Sex </label></td>
                <td> 
                  <?php if($form_data['sex']==1){ echo 'Male'; }elseif($form_data['sex']==0){ echo 'Female'; }elseif($form_data['sex']==2){echo 'Others';} ?>
                </td>
                <td><label>Salary </label></td>
                <td><?php echo $form_data['salary']; ?></td>
              </tr>
			  <?php /*?>
              <tr>
                <td><label>Marital Status </label></td>
                <td>
                   <?php if($form_data['merital_status']==1){ echo 'Single'; }else{ echo 'Married'; } ?>
                </td>
               
              </tr>
			  <?php */?>
			  
              <tr>
                <td valign="top"><label>Address </label></td>
                <td colspan="">
                 <?php echo $form_data['address']; ?>
                </td>
                 <td><label>Qualification </label></td>
                <td> 
                  <?php echo $form_data['qualification']; ?>
                </td>
              </tr>
              <tr>
                <td><label>Country </label></td>
                <td>
                   <?php echo $form_data['country']; ?>
                </td>
                
              </tr>

              <tr>
                
                <td><label>State </label></td>
                <td>
                  <?php echo ucfirst(strtolower($form_data['state'])); ?> 
                </td>
              </tr>

              <tr>
                <td><label>City </label></td>
                <td>
                  <?php echo $form_data['city']; ?>
                </td>
                <td><label>PIN Code </label></td>
                <td>
                  <?php echo $form_data['postal_code']; ?>
                </td>
              </tr> 
              <tr>
                
                <td><label> Status </label></td>
                <td>
                 <?php if($form_data['status']==1){ echo 'Active'; }else{ echo 'Inactive'; } ?>
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
</div><!-- /.modal-dialog -->