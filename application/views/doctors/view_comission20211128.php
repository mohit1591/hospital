 <?php 
  $users_data = $this->session->userdata('auth_users'); 
                $user_role= $users_data['users_role'];
                

	if (array_key_exists("permission",$users_data)){
		 $permission_section = $users_data['permission']['section'];
		 $permission_action = $users_data['permission']['action'];
	}
	else{
		 $permission_section = array();
		 $permission_action = array();
		 $permission_section_new=array();
	   }
 ?>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader-comission">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="comission_form" class="form-inline"> 
      
                <div class="modal-header">
                    <button type="button" class="close" data-number="2" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body"> 

               <div class="row">
                <?php
                if(!empty($dept_list))
                { 
                   //echo "<pre>";print_r($comission); exit;
                   $types = array('0'=>'Rs.', '1'=>'%');
                   foreach($dept_list as $dept)
                   {
                    
                        
                         if($dept->department =='OPD' && in_array('85',$permission_section))
                      {
                        
                    ?> 
                        <div class="col-md-12 m-b1">
                          <div class="row">
                            <div class="col-md-4">
                            <label> <?php echo $dept->department; ?> </label>
                            </div>
                            <div class="col-md-8">
                                <?php  
                                  if(isset($comission['data'][$dept->id]['numb'])) 
                                    { 
                                      //echo $comission['data'][$dept->id]['type'];
                                       if(isset($comission['data'][$dept->id]['type']) && $comission['data'][$dept->id]['type']==1)
                                       {
                                          echo $comission['data'][$dept->id]['numb']." %";  
                                       }
                                       else
                                       {
                                        echo 'Rs. '.$comission['data'][$dept->id]['numb']; 
                                        
                                       }
                                       
                                    }
                                    else
                                    { 
                                      echo '<span class="danger-text">N/A</span>';
                                    } 
                                    ?>  
                            </div>
                          </div> <!-- innerrow -->
                        </div> <!-- 12 -->
                 <?php

               }
               elseif($dept->department =='OPD Billing' && in_array('151',$permission_section))
               {
                  ?>
                  <div class="col-md-12 m-b1">
                          <div class="row">
                            <div class="col-md-4">
                            <label> <?php echo $dept->department; ?> </label>
                            </div>
                            <div class="col-md-8">
                                <?php  
                                  if(isset($comission['data'][$dept->id]['numb'])) 
                                    { 
                                      //echo $comission['data'][$dept->id]['type'];
                                       if(isset($comission['data'][$dept->id]['type']) && $comission['data'][$dept->id]['type']==1)
                                       {
                                          echo $comission['data'][$dept->id]['numb']." %";  
                                       }
                                       else
                                       {
                                        echo 'Rs. '.$comission['data'][$dept->id]['numb']; 
                                        
                                       }
                                       
                                    }
                                    else
                                    { 
                                      echo '<span class="danger-text">N/A</span>';
                                    } 
                                    ?>  
                            </div>
                          </div> <!-- innerrow -->
                        </div> <!-- 12 -->
                  <?php
               }
               elseif($dept->department =='Medicine' && in_array('60',$permission_section))
               {
                  ?>
                  <div class="col-md-12 m-b1">
                          <div class="row">
                            <div class="col-md-4">
                            <label> <?php echo $dept->department; ?> </label>
                            </div>
                            <div class="col-md-8">
                                <?php  
                                  if(isset($comission['data'][$dept->id]['numb'])) 
                                    { 
                                      //echo $comission['data'][$dept->id]['type'];
                                       if(isset($comission['data'][$dept->id]['type']) && $comission['data'][$dept->id]['type']==1)
                                       {
                                          echo $comission['data'][$dept->id]['numb']." %";  
                                       }
                                       else
                                       {
                                        echo 'Rs. '.$comission['data'][$dept->id]['numb']; 
                                        
                                       }
                                       
                                    }
                                    else
                                    { 
                                      echo '<span class="danger-text">N/A</span>';
                                    } 
                                    ?>  
                            </div>
                          </div> <!-- innerrow -->
                        </div> <!-- 12 -->
                  <?php
               }

               elseif($dept->department =='IPD' && in_array('121',$permission_section))
               {
                  ?>
                  <div class="col-md-12 m-b1">
                          <div class="row">
                            <div class="col-md-4">
                            <label> <?php echo $dept->department; ?> </label>
                            </div>
                            <div class="col-md-8">
                                <?php  
                                  if(isset($comission['data'][$dept->id]['numb'])) 
                                    { 
                                      //echo $comission['data'][$dept->id]['type'];
                                       if(isset($comission['data'][$dept->id]['type']) && $comission['data'][$dept->id]['type']==1)
                                       {
                                          echo $comission['data'][$dept->id]['numb']." %";  
                                       }
                                       else
                                       {
                                        echo 'Rs. '.$comission['data'][$dept->id]['numb']; 
                                        
                                       }
                                       
                                    }
                                    else
                                    { 
                                      echo '<span class="danger-text">N/A</span>';
                                    } 
                                    ?>  
                            </div>
                          </div> <!-- innerrow -->
                        </div> <!-- 12 -->
                  <?php
               }
               elseif($dept->department =='OT' && in_array('134',$permission_section))
               {
                  ?>
                  <div class="col-md-12 m-b1">
                          <div class="row">
                            <div class="col-md-4">
                            <label> <?php echo $dept->department; ?> </label>
                            </div>
                            <div class="col-md-8">
                                <?php  
                                  if(isset($comission['data'][$dept->id]['numb'])) 
                                    { 
                                      //echo $comission['data'][$dept->id]['type'];
                                       if(isset($comission['data'][$dept->id]['type']) && $comission['data'][$dept->id]['type']==1)
                                       {
                                          echo $comission['data'][$dept->id]['numb']." %";  
                                       }
                                       else
                                       {
                                        echo 'Rs. '.$comission['data'][$dept->id]['numb']; 
                                        
                                       }
                                       
                                    }
                                    else
                                    { 
                                      echo '<span class="danger-text">N/A</span>';
                                    } 
                                    ?>  
                            </div>
                          </div> <!-- innerrow -->
                        </div> <!-- 12 -->
                  <?php
               }
               elseif($dept->department =='BLOOD BANK' && in_array('262',$permission_section))
               {
                  ?>
                  <div class="col-md-12 m-b1">
                          <div class="row">
                            <div class="col-md-4">
                            <label> <?php echo $dept->department; ?> </label>
                            </div>
                            <div class="col-md-8">
                                <?php  
                                  if(isset($comission['data'][$dept->id]['numb'])) 
                                    { 
                                      //echo $comission['data'][$dept->id]['type'];
                                       if(isset($comission['data'][$dept->id]['type']) && $comission['data'][$dept->id]['type']==1)
                                       {
                                          echo $comission['data'][$dept->id]['numb']." %";  
                                       }
                                       else
                                       {
                                        echo 'Rs. '.$comission['data'][$dept->id]['numb']; 
                                        
                                       }
                                       
                                    }
                                    else
                                    { 
                                      echo '<span class="danger-text">N/A</span>';
                                    } 
                                    ?>  
                            </div>
                          </div> <!-- innerrow -->
                        </div> <!-- 12 -->
                  <?php
               }
              elseif(in_array($dept->department,$path_dept) && in_array('145',$permission_section))
               { 
                  ?>
                  <div class="col-md-12 m-b1">
                          <div class="row">
                            <div class="col-md-4">
                            <label> <?php echo $dept->department; ?> </label>
                            </div>
                            <div class="col-md-8">
                                <?php  
                                  if(isset($comission['data'][$dept->id]['numb'])) 
                                    { 
                                      //echo $comission['data'][$dept->id]['type'];
                                       if(isset($comission['data'][$dept->id]['type']) && $comission['data'][$dept->id]['type']==1)
                                       {
                                          echo $comission['data'][$dept->id]['numb']." %";  
                                       }
                                       else
                                       {
                                        echo 'Rs. '.$comission['data'][$dept->id]['numb']; 
                                        
                                       }
                                       
                                    }
                                    else
                                    { 
                                      echo '<span class="danger-text">N/A</span>';
                                    } 
                                    ?>  
                            </div>
                          </div> <!-- innerrow -->
                        </div> <!-- 12 -->
                  <?php
               }
 
               

                        
                 
                   }
                }
                ?>
                </div> <!-- row -->

            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer">  
               <button type="button" class="btn-cancel" data-number="2">Close</button>
            </div>
    </form>     

<script>    
$("#comission_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader-comission').show();  
  $.ajax({
    url: "<?php echo base_url('doctors/add_comission'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        var msg = "Your pathology share details saved.";
        $('#load_add_comission_modal_popup').modal('hide');
        flash_session_msg(msg);  
      } 
      else
      {
        $("#load_add_comission_modal_popup").html(result);
      }       
      $('#overlay-loader-comission').hide();
    }
  });
}); 

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}

$("button[data-number=2]").click(function(){
    $('#load_add_comission_modal_popup').modal('hide');
});
  
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div>  
</div><!-- /.modal-dialog -->