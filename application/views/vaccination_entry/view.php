<?php //print_r($form_data);die;?>
<div class="modal-dialog modal-lg">

  <div class="modal-content">  
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
      <div class="modal-body">  
         
            <table class="medicine_entry_view">
              <tr>
                <td><label>Vaccination Code </label></td>
                <td><?php 
                  if(isset($form_data['vaccination_code']) && $form_data['vaccination_code']!=''){
                                echo $form_data['vaccination_code'];
                              }else{
                                echo '';
                              } ?></td>

                <td><label>Vaccination Name </label></td>
                <td><?php 
                if(isset($form_data['vaccination_name']) && $form_data['vaccination_name']!=''){
                                echo $form_data['vaccination_name'];
                              }else{
                                echo '';
                              }
                              ?></td>
                
                
                
              </tr>

                <tr>
                <td><label>Unit1</label></td>
                <td>
                  <?php  

                  $unit_listname=  vaccination_unit_list($form_data['unit_id']);
                  
                  if(isset($unit_listname[0]->vaccination_unit) && $unit_listname[0]->vaccination_unit!=''){
                    echo $unit_listname[0]->vaccination_unit;
                  }else{
                   echo '';
                  }
                   //echo $form_data['unit_id']; ?> 
                </td>
                <td><label>Unit2 </label></td>
                <td>
                <?php  $unit_second_listname=  vaccination_unit_list($form_data['unit_second_id']);
                 if(isset($unit_second_listname[0]->vaccination_unit) && $unit_second_listname[0]->vaccination_unit!=''){
                   echo $unit_second_listname[0]->vaccination_unit;
                  }else{
                     echo '';
                  }
                   ?> 
                </td>
              </tr>


              <tr>
                <td><label>Conversion </label></td>
                <td><?php 
                if(isset($form_data['conversion']) && $form_data['conversion']!=''){
                                 echo $form_data['conversion'];
                              }else{
                               echo '';
                              }
                              ?></td>
               
                <td><label>Min Alert </label></td>
                <td><?php 
                if(isset($form_data['min_alrt']) && $form_data['min_alrt']!=''){
                 echo $form_data['min_alrt'];
                }else{
                  echo '';
                }
                 ?></td>
              </tr>
              <tr>
                  <td><label>Packing</label></td>
                  <td>  <?php 
                  if(isset($form_data['packing'])&& $form_data['packing']!=''){
                    echo $form_data['packing'];
                  }else{
                    echo '';
                  }
                   ?> </td>

                   <td><label>Rack No.</label></td>
                <td>
                   <?php 

                    $rack_listname=  vaccination_rack_list($form_data['rack_no']);
                    if(isset($rack_listname[0]->rack_no)&& $rack_listname[0]->rack_no!=''){
                      echo $rack_listname[0]->rack_no; 
                    }else{
                      echo '';
                    }
                    
                   ?>
                </td>
                
              </tr>
                   <tr>
                  <td><label>Salt</label></td>
                  <td> <?php 
                         if(isset($form_data['salt']) && $form_data['salt']!=''){
                             echo $form_data['salt'];
                         }else{
                           echo '';
                         }

                 

                   ?> </td>
                   <td><label>Mfg. company </label></td>
                <td>
                   <?php 

                    $manuf_company_listname=  vaccination_manuf_company_list($form_data['manuf_company']);
                    if(isset($manuf_company_listname[0]->company_name) && $manuf_company_listname[0]->company_name!=''){

                      echo $manuf_company_listname[0]->company_name; 
                    }else{
                      echo '';
                    }
                   ?>
                </td>
               
              </tr>

               <tr>
                <td><label>MRP</label></td>
                <td><?php 
                    if(isset($form_data['mrp']) && $form_data['mrp']!=''){
                       echo $form_data['mrp']; 
                    }else{
                       echo ''; 
                    }
               

                ?> </td>
                <td><label>Purchase Rate </label></td>
                <td>
                   <?php 
                   if(isset($form_data['purchase_rate'])&& $form_data['purchase_rate']!=''){
                    echo $form_data['purchase_rate']; 
                   }else{
                    echo '';
                   }
                    
                   ?>
                </td>
              </tr>
              <tr>
                <td><label>Discount(%)</label></td>
                <td> <?php 

                 if(isset($form_data['discount']) && $form_data['discount']!=''){
                             echo $form_data['discount'];
                         }else{
                           echo '';
                         }

                ?> </td>

                 <td><label>HSN No.</label></td>
                <td> <?php 

                 if(isset($form_data['hsn_no']) && $form_data['hsn_no']!=''){
                             echo $form_data['hsn_no'];
                         }else{
                           echo '';
                         }

                ?> </td>
               
              </tr>
              <tr>
                <td><label>CGST(%)</label></td>
                <td> <?php 
   if(isset($form_data['cgst']) && $form_data['cgst']!=''){
                             echo $form_data['cgst'];
                         }else{
                           echo '';
                         }

                         ?> </td>
                <td><label>Status </label></td>
                <td> <?php if($form_data['status']==1){
                  echo 'Active';
                  }else{echo 'Inactive'; }?> </td>
                <td> </td>
              </tr>
                <tr>
                <td><label>SGST(%)</label></td>
                <td> <?php 
 if(isset($form_data['sgst']) && $form_data['sgst']!=''){
                             echo $form_data['sgst'];
                         }else{
                           echo '';
                         }
 ?> </td>
                <td></td>
                <td> </td>
              </tr>
              <tr>
                <td><label>IGST(%)</label></td>
                <td> <?php 
 if(isset($form_data['igst']) && $form_data['igst']!=''){
                             echo $form_data['igst'];
                         }else{
                           echo '';
                         }
                         ?> </td>
                <td></td>
                <td> </td>
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