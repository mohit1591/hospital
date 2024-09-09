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
                                  <th>Attended Doctor</th>
                                  <th>Vaccine Date</th>
                                  <th>Total Amount</th>
                                   <th>Discount</th>
                                   <th>Net Amount</th>
                                   <th>Paid Amount</th>
                                   <th>Balance</th>
                                   <th>Action</th>
                  
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
                              <td><?php echo $vaccination->doctor_name;?></td>
                              <td><?php if(strtotime($vaccination->vaccination_date_time)>86400){ echo date('d-m-Y H:i:s',strtotime($vaccination->vaccination_date_time));} ?></td>
                             <td><?php  echo number_format($vaccination->total_amount,2);?></td>
                              <td><?php echo number_format($vaccination->discount_amount,2);?></td>
                              <td><?php echo number_format($vaccination->net_amount,2);?></td>
                              <td><?php echo number_format($vaccination->paid_amount,2);?></td>
                              <td><?php echo number_format($vaccination->balance,2);?></td>
                              <td><a class="btn-custom" onclick =" return edit_data('<?php echo $vaccination->id;?>','<?php echo $vaccination->booking_id;?>','<?php echo $vaccination->patient_id;?>')" title="Edit Booking"><i class="fa fa-pencil"></i> Edit</a>
                             <!--  <a class="btn-custom" onclick="return delete_opd_booking(1860)" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> -->

                              </td>

                              </tr>
                              <?php $i++; }} ?>
                              </tbody>
                    </table>
                </div> <!-- 8 -->
              </div>

            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <button type="button" class="btn-cancel" data-dismiss="modal" data-number="1">Close</button>
            </div>
    </form>     


</div><!-- /.modal-dialog -->
<script>

function edit_data(edit_id,booking_id,patient_id)
{
  var $modal = $('#load_billing_n_to_branch_modal_popup');
  $modal.load('<?php echo base_url('pediatrician/pediatrician_prescription/billing_vaccine/'); ?>',{'booking_id':booking_id,'patient_id':patient_id,'age_id':'','edit_id':edit_id},function(){
  $modal.modal('show');
  });
}
// $(document).ready(function()
// {
//    $("button[data-number=1]").click(function(){
//       $('#load_add_vaccine_history_modal_popup').modal('hide');
//    });
// });
</script>



