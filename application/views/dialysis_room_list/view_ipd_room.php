
<div class="modal-dialog modal-lg">
     <div class="modal-content">  
          <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo $page_title; ?></h4> 
          </div>
          <div class="modal-body form cmnClassF cf">  
               <table class="brlist-tbl-view">
                   <?php $form_data_count = count($form_data); 
                         for($i=0;$i<$form_data_count;$i++) { 
                            if($i==0){
                    ?>
                                <tr>
                                <td>
                                        <label> Room Type </label>
                                    </td>
                                    <td><?php echo $form_data[$i]['room_category']; ?></td>
                                    </tr><tr>
                                    <th><label> Room No </label></th>
                                    <td>
                                        <label>  <?php echo $form_data[$i]['room_no']; ?>  </label>
                                    </td>
                                    
                                </tr>
                    <?php   }
                        }
                    ?>


            

                    <?php $form_data_count = count($form_data);
                           // $form_data_row_count = round($form_data_count/2);
                           // print_r($form_data_row_count);
                           
                              
                        for($i=0;$i<$form_data_count;$i++){
                            if($i!=0){ ?>
                                <tr>
                                <td><label> <?php echo ucfirst($form_data[$i]['charge_type']).'' ?> </label></td>
                                <td><?php echo $form_data[$i]['room_charge']; ?>  </td>
                               </tr>

                    <?php   }
                            } ?>
                             
             <tr><td><label>No of Beds</label></td><td><?php echo $form_data[0]['total_bad']; ?></td></tr>
                
               </table>
          </div>     
          <div class="modal-footer">  
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
        </div> 
 
     </div><!-- /.modal-content
    
</div><!-- /.modal-dialog -->