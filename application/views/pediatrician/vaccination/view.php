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