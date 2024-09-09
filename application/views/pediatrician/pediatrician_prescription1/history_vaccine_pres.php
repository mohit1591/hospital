<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(5);
//print_r($form_data);
?>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="emp_type" class="form-inline">
       
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body">
<div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped medicine_allotment" id="medicine_list">
                         <thead class="bg-theme">
                              <tr>
                    <th align="center" width="40">Sr No.</th>
                                  <th>Vaccine Code</th>
                                   <th>Vaccine Name</th>
                                   <th>Age Group</th>
                                   <th>Qty</th>
                                   <th>Total Amount</th>
                                   <th>Discount</th>
                                   <th>Net Amount</th>
                                   <th>Paid Amount</th>
                                   <th>Balance</th>
                  
                              </tr>
                         </thead>
                         <tbody id="medicine">
                         <?php if(!empty($vaccine_history))
                              {
                                $i=1;
                              foreach($vaccine_history as $vaccination)
                              {?>  
                              <tr>
                              <td align="center"><?php echo $i; ?></td>
                              <td><?php echo $vaccination->vaccination_code; ?></td>
                              <td><?php echo $vaccination->vaccination_name; ?></td>
                              <td><?php echo $vaccination->title; ?></td>
                              <td><?php echo $vaccination->qty; ?></td>
                              <td><?php echo $vaccination->total_amount;?></td>
                              <td><?php echo $vaccination->discount;?></td>
                              <td><?php echo $vaccination->net_amount;?></td>
                              <td><?php echo $vaccination->paid_amount;?></td>
                              <td><?php echo $vaccination->balance;?></td></tr>
                              <?php $i++; }} ?>
                              </tbody>
                    </table>
                </div> <!-- 8 -->
              </div>

            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     


</div><!-- /.modal-dialog -->