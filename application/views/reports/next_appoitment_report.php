<?php
$users_data = $this->session->userdata('auth_users');

?>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  class="form-inline">
 
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
     <div class="modal-body">   
          <div class="row">
               <div class="col-md-12">
                   <!-- <div class="row m-b-5">
                         <div class="col-md-5">
                              <label><b>User</b></label>
                         </div>
                        <div class="col-md-7">
                         <select name="employee_name" id="employee_name" >
                            <option value="">Select User</option>
                            < ?php
                            if(!empty($employee_list))
                            {
                              foreach($employee_list as $employee)
                              {
                                $selected_country = "";
                                if($employee->id==$form_data['employee_name'])
                                {
                                  $selected_country = 'selected="selected"';
                                }
                                echo '<option value="'.$employee->id.'" '.$selected_country.'>'.$employee->name.'</option>';
                              }
                            }
                            ?> 
                          </select> 
         
                         </div>
                    </div>-->
                    <div class="row m-b-5">
                         <div class="col-md-5">
                              <label><b>From Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" id="from_c_date" name="from_c_date" class="collection_datepicker" value="<?php echo date('d-m-Y'); ?>" onblur="reset_search_value()">
                         </div>
                    </div>
                    <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>To Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" name="to_c_date" id="to_c_date" class="collection_datepicker_to" value="<?php echo date('d-m-Y'); ?>" onblur="reset_search_value()">
                         </div>
                    </div>

                     <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>Branches:</b></label>
                          </div>
                             
                               <?php    $users_data = $this->session->userdata('auth_users');
                                        $sub_branch_details = $this->session->userdata('sub_branches_data');
                                        $parent_branch_details = $this->session->userdata('parent_branches_data');
                                        //$branch_name = get_branch_name($parent_branch_details[0]);
                                    ?>
                                     <div class="col-md-7">
                            <select name="branch_id" id="next_branch_id"> 
                                <option  selected="selected" <?php if(isset($_POST['branch_id']) && $_POST['branch_id']==$users_data['parent_id']){ echo 'selected="selected"'; } ?> value="<?php echo $users_data['parent_id'];?>">Self</option>';
                                 <?php 
                                 if(!empty($sub_branch_details)){
                                     $i=0;
                                    foreach($sub_branch_details as $key=>$value){
                                         ?>
                                         <option value="<?php echo $sub_branch_details[$i]['id'];?>" <?php if(isset($_POST['branch_id'])&& $_POST['branch_id']==$sub_branch_details[$i]['id']){ echo 'selected="selected"'; } ?> ><?php echo $sub_branch_details[$i]['branch_name'];?> </option>
                                         <?php 
                                         $i = $i+1;
                                     }
                                   
                                 }
                                ?> 
                                </select>
                             </div>
                             </div>

                    <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>Export Type:</b></label>
                         </div>
                         <div class="col-md-7">
                             <select name="print_opt" id="print_opt">  
                                 <option value="3">Excel</option>
                              </select>
                         </div>
                    </div>
                    
               </div> <!-- 12 -->
          </div> <!-- row -->  
         
      </div> <!-- modal-body --> 

        <div class="modal-footer">
            <div> 
                <a href="javascript:void(0)" class="btn-anchor" onclick="return export_report();"> Print </a> 
                 <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
        </div>
</form>     

<script type="text/javascript">  
$('.collection_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.collection_datepicker').val();
      $('.collection_datepicker_to').datepicker('setStartDate', start_data);
       
  });

  $('.collection_datepicker_to').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });
function export_report()
{
    var start_date = $('#from_c_date').val();
    var end_date = $('#to_c_date').val(); 
    var branch_id = $('#next_branch_id').val(); 
    window.location.href='<?php echo base_url("reports/next_appoitment_excel?start_date="); ?>'+start_date+'&end_date='+end_date+'&branch_id='+branch_id;
}
</script>
</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->