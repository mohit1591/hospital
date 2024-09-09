<?php //print_r($form_data);?>
<div class="modal-dialog modal-lg">

  <div class="modal-content">  
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
      <div class="modal-body">  
         
            <table class="medicine_entry_view">
              <tr>
                <td><label>Medicine code : </label></td>
                <td><?php echo $form_data['medicine_code']; ?></td>
                <td><label> Name </label></td>
                <td><?php echo $form_data['medicine_name']; ?></td>
              </tr>

              <tr>
                <td><label>Unit</label></td>
                <td>
                  <?php  $unit_listname=  unit_list($form_data['unit_id']);
                  echo $unit_listname[0]->medicine_unit; //echo $form_data['unit_id']; ?> 
                </td>
                <td><label>Unit 2nd </label></td>
                <td>
                <?php  $unit_second_listname=  unit_list($form_data['unit_second_id']);
                   echo $unit_second_listname[0]->medicine_unit; ?> 
                </td>
              </tr>

              <tr>
                <td><label>Conversion </label></td>
                <td><?php echo $form_data['conversion']; ?></td>
                <td><label>Min Alert </label></td>
                <td><?php echo $form_data['min_alrt']; ?></td>
              </tr>

              <tr>
                  <td><label>Packing</label></td>
                  <td>  <?php echo $form_data['packing']; ?> </td>
                <td><label>Rack No</label></td>
                <td>
                   <?php 
                    $rack_listname=  rack_list($form_data['rack_no']);
                    echo $rack_listname[0]->rack_no; 
                   ?>
                </td>
              </tr>

              <tr>
                <td><label>Salt</label></td>
                <td> <?php echo $form_data['salt']; ?> </td>
                <td><label>Manufacturing company </label></td>
                <td>
                   <?php 
                    $manuf_company_listname=  manuf_company_list($form_data['manuf_company']);
                    echo $manuf_company_listname[0]->company_name; 
                   ?>
                </td>
              </tr>

              <tr>
                <td><label>mrp </label></td>
                <td><?php echo $form_data['mrp']; ?> </td>
                <td><label>Purchase Rate </label></td>
                <td>
                   <?php 
                    echo $form_data['purchase_rate']; 
                   ?>
                </td>
              </tr>

              <tr>
                <td><label>vat(%) </label></td>
                <td> <?php echo $form_data['vat']; ?> </td>
                <td><label>Status </label></td>
                <td> <?php echo $form_data['status']; ?> </td>
              </tr>
             
            </table>
           
          
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