<?php //print_r($form_data);?>
<div class="modal-dialog modal-lg">

  <div class="modal-content">  
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
      <div class="modal-body form cmnClassF cf">  
         
            <table class="brlist-tbl-view">
              <tr>
                <td><label>Medicine code : </label></td>
                <td><?php echo $form_data['medicine_code']; ?></td>
                 <td><label>Purchase Rate </label></td>
                <td>
                   <?php 
                    echo $form_data['purchase_rate']; 
                   ?>
                </td>
              </tr>
              <tr>
                <td><label> Name </label></td>
                <td><?php echo $form_data['medicine_name']; ?>
                </td>
               
              </tr>
              <tr>
                <td><label>mrp </label></td>
                <td><?php echo $form_data['mrp']; ?> 
                </td>
                <td><label>vat(%) </label></td>
                <td> 
                  <?php echo $form_data['vat']; ?>
                </td>
              </tr>
              <tr>
                <td><label>Manufacturing company </label></td>
                <td>
                   <?php 
                    $manuf_company_listname=  manuf_company_list($form_data['manuf_company']);
                    echo $manuf_company_listname[0]->company_name; 
                   ?>
                </td>
                </td>
                <td><label>Salt</label></td>
                <td> 
                  <?php $form_data['salt']; ?>
                </td>
              </tr>
              <tr>
                <td><label>Rack No</label></td>
                <td>
                   <?php 
                    $rack_listname=  rack_list($form_data['rack_no']);
                    echo $rack_listname[0]->rack_no; 
                   ?>
                </td>
              
                   <td><label>Packing</label></td>
                  <td> 
                     <?php echo $form_data['packing']; ?>
                  </td>
                  <?php
                
                ?> 
              </tr>
              <tr>
                <td valign="top"><label>Min Alert </label></td>
                <td colspan="3">
                 <?php echo $form_data['min_alrt']; ?>
                </td>
              </tr>
              <tr>
                <td><label>Conversion </label></td>
                <td>
                   <?php echo $form_data['conversion']; ?>
                </td>
                <td><label>Unit</label></td>
                <td>
                  <?php  $unit_listname=  unit_list($form_data['unit_id']);
                  echo $unit_listname[0]->medicine_unit;

                  //echo $form_data['unit_id']; ?> 
                </td>
                <td><label>Unit 2nd </label></td>
                <td>
                <?php  $unit_second_listname=  unit_list($form_data['unit_second_id']);
                   echo $unit_second_listname[0]->medicine_unit; ?> 
                </td>
              </tr>
             
            </table>
           
          
      </div>     
             
             
        <div class="modal-footer">  
           <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> 
 
        </div><!-- /.modal-content -->
 
</div><!-- /.modal-dialog -->