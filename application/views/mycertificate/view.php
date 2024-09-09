<?php // print_r($form_data);?>
<div class="modal-dialog modal-lg">

  <div class="modal-content">  
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4>view</h4> 
            </div>
      <div class="modal-body form cmnClassF cf">  
         
            <table class="brlist-tbl-view">
              
              <tr>
                <td><label> Certificate Name </label></td>
                <td><?php echo $form_data['certi_name']; ?>
                </td>
               
              </tr>
                <tr>
                <td><label> Patient Name </label></td>
                <td><?php echo $form_data['patient_name']; ?>
                </td>
               
              </tr>
                <tr>
                <td><label> Template </label></td>
                <td><?php echo $form_data['template']; ?>
                </td>
               
              </tr>
            
              
      
             
            </table>
           
          
      </div>     
             
             
        <div class="modal-footer">  
           <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> 
 
        </div><!-- /.modal-content -->

<div id="load_add_ipd_room_type_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- /.modal-dialog -->