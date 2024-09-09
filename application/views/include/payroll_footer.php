
<div id="load_change_password_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_add_expense_collection_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_medicine_kit_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_banking_report_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_next_appoitment_report_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_referral_report_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_collection_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_gst_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_inventory_purchamodal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_inventory_purchase_return_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_inventory_allotment_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_inventory_allotment_return_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_inventory_garbage_list_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_stock_item_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>


<div id="load_add_comission_modal_popup" class="modal fade modal-45 modal-top z-index" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

  <div id="flash_msg"  class="booked_session_flash" style="display: none;">
     <i class="fa fa-check-circle"></i>
     <span id="flash_msg_text"></span>
</div>
<div id="flash_msg_n"  class="booked_session_flash_red" style="display: none;">
     <i class="fa fa-check-circle"></i>
     <span id="flash_msg_text_n"></span>
</div>





<!-- ///////////////////////////[ FOOTER AREA ]////////////////////////////// -->
<footer style="float:left;width:100%;margin-top:100px;">
    <div class="container-fluid payroll_footer">
      <section class="row">
        <div class="col-md-12">
          <p>Copyrights <?php echo date('Y');?> &copy; Sara Technologies Pvt Ltd, All rights reserved.</p>
        </div>
      </section>
    </div>
</footer>

<!-- ///////////////////////////[ FOOTER AREA ]////////////////////////////// -->




<script> 
function flash_session_msg(val)
{
    $('#flash_msg_text').html(val);
    $('#flash_msg').css('display','block');
    $('#flash_msg').slideDown('slow').delay(1500).slideUp('slow');
}
function error_flash_session_msg(val)
{
    $('#flash_msg_text_n').html(val);
    $('#flash_msg_n').css('display','block');
    $('#flash_msg_n').slideDown('slow').delay(1500).slideUp('slow');
}
$(document).ready(function(){
  var $modal = $('#load_change_password_popup');
  $('#change_password').on('click', function(){
    $modal.load('<?php echo base_url(); ?>/change-password',
    {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
   $modal.modal('show');
  });

});
});
</script>
