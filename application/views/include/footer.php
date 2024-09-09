<script type="text/javascript">
function crm_report()
{
  var $modal = $('#load_add_collection_modal_popup');
  $modal.load('<?php echo base_url().'crm/report/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}	
</script>
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


<div id="load_add_medicine_quantity_report_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_popup_maintenance_page" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_add_comission_modal_popup" class="modal fade modal-45 modal-top z-index" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
<div id="load_notice_board_page" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_enquiry_source_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_collection_setting_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
<!-- vaccine billing report  -->
<div id="load_add_vaccine_report_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_send_login_credential_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<!-- footer -->
  <div class="row footer-row" style="margin-top:1em">
    <div class="col-md-12" id="footer">
      <p class="text-center">Copyrights <?php echo date('Y'); ?> &copy; Sara Technologies Pvt Ltd, All rights reserved.</p>
    </div>
  </div>
  <!-- footer close -->