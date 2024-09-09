<?php
$users_data = $this->session->userdata('auth_users');
?>
<div class="modal-dialog modal-lg">

  <div class="modal-content">  
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
      <div class="modal-body form cmnClassF cf">  
         
            <table class="brlist-tbl-view">
              <tr>
                <th><label>Branch ID : </label></th>
                <td><label>  <?php echo $form_data['branch_code']; ?>  </label></td>
                <td><label>Contact Person </label></td>
                <td><?php echo $form_data['contact_person']; ?></td>
                    
              </tr>
              <tr>
                <td><label>Branch Name </label></td>
                <td><?php echo $form_data['branch_name']; ?>
                </td>
                <?php if($users_data['users_role']==2){?>
                    <td><label>Branch Type.</label></td>
                    <?php if($form_data['branch_type']==0){?>
                         <td>Demo</td>
                    <?php }elseif($form_data['branch_type']==1){ ?>
                          <td>Live</td>
                    <?php } ?>

               <?php }else{ ?>
                    
               <?php } ?>
                
              </tr>
              <tr>
                <td><label>Contact No. </label></td>
                <td><?php echo $form_data['contact_no']; ?>
                <td><label>Email </label></td>
                <td class="small_caps"><?php echo $form_data['email']; ?> 
                </td>
                <td></td>
                <td> 
                 
                </td>
              </tr>
              <tr>
                <td valign="top"><label>Address </label></td>
                <td colspan="3">
                 <?php echo $form_data['address']; ?>
                </td>
              </tr>
              <tr>
                <td valign="top"><label>Address2 </label></td>
                <td colspan="3">
                 <?php echo $form_data['address2']; ?>
                </td>
              </tr>
              <tr>
                <td valign="top"><label>Address3 </label></td>
                <td colspan="3">
                 <?php echo $form_data['address3']; ?>
                </td>
              </tr>
              <tr>
                <td><label>Country </label></td>
                <td>
                   <?php echo $form_data['country']; ?>
                </td>
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
                <td><label>User Name </label></td>
                <td>
                  <?php echo $form_data['username']; ?>
                </td>
              </tr> 
              <tr>
                <td><label>Branch Status </label></td>
                <td>
                 <?php if($form_data['status']==1){ echo 'Active'; }else{ echo 'Inactive'; } ?> 
                </td>
                <td><label>Login Status </label></td>
                <td>
                 <?php if($form_data['login_status']==1){ echo 'Active'; }else{ echo 'Inactive'; } ?>
                </td>
              </tr>
              <tr>
                <td><label>Created Date </label></td>
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