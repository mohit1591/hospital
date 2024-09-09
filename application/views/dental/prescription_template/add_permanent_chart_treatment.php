<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
<img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
</div>
<div class="modal-content"> 
 <form  id="data_list" class="form-inline">
<div class="modal-header">
 <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
 <h4><?php echo $page_title; ?></h4> 
</div>
<div class="row">
  <div class="col-md-12">
    <div class="well">
      <div class="teeth-head">
        <h3 class="text-center"><?php echo $page_title; ?></h3>
   </div>

      <div class="row">
        <div class="col-md-6"><h4 class="text-center">Upper Left</h4></div>
        <div class="col-md-6"><h4 class="text-center">Upper Right</h4></div>
      </div>
      <div class="row">
       <?php
      if(!empty($get_permanent))
      {
        ?>
        <div class="col-md-6 b-right b-bottom">
          
              
             <div class="teeth-img-box">
                <ul class="teeth-li">
                <?php
                foreach ($get_permanent as  $get_permanent_upper_left) {
                  if($get_permanent_upper_left->teeth_type==1)
                  {
                   if(!empty($get_permanent_upper_left->teeth_image))
                     {
                                $img_path = ROOT_UPLOADS_PATH.'dental/teeth_chart/'.$get_permanent_upper_left->teeth_image;
                           }
                  
                ?>
                  <li><img src="<?php echo $img_path; ?>" alt="" class="img-responsive"></li>
                 <?php

               }}
               ?>
                </ul>
                <ul class="teeth-num">
                <?php
                foreach ($get_permanent as  $get_permanent_upper_left_num) {
                if($get_permanent_upper_left_num->teeth_type==1)
                  {
                ?>
                  <li><input type="text" class="w-40px" id='teeth_number' onclick='get_teeth_num_val(this.value);' name="teeth_number"  value="<?php echo $get_permanent_upper_left_num->teeth_number; ?>" readonly></li>
                 <?php
               }
             }
               ?>
                </ul>
              </div> 
              
            
          
        </div>
       
       
        <div class="col-md-6  b-bottom">
              <div class="teeth-img-box">
                <ul class="teeth-li">
                <?php
                foreach ($get_permanent as  $get_permanent_upper_right) {
                  if($get_permanent_upper_right->teeth_type==2)
                  {
                   if(!empty($get_permanent_upper_right->teeth_image))
                     {
                                $img_path_type = ROOT_UPLOADS_PATH.'dental/teeth_chart/'.$get_permanent_upper_right->teeth_image;
                           }
                  
                ?>
                  <li><img src="<?php echo $img_path_type; ?>" alt="" class="img-responsive text-center"></li>
                 <?php
               }
             }
             ?>
                </ul>
                <ul class="teeth-num">
                <?php
                foreach ($get_permanent as  $get_permanent_upper_right_num) {
                  if($get_permanent_upper_right_num->teeth_type==2)
                  {
                   
                  
                ?>
                    <li><input type="text" class="w-40px" id='teeth_number' onclick='get_teeth_num_val(this.value);' name="teeth_number" value="<?php echo $get_permanent_upper_right_num->teeth_number; ?>" readonly>
              </li>
                 <?php
               }
             }
             ?>
                </ul>
              </div>
            
            <div>
          </div>
        </div>
        <?php
      }
      ?>
      
      </div>


      <div class="row">
       <?php
      if(!empty($get_permanent))
      {
     
     
        ?>
        <div class="col-md-6 b-right">
          
          <div class="teeth-img-box">
            <ul class="teeth-num">
 <?php
                foreach ($get_permanent as  $get_permanent_lower_left) {
                  if($get_permanent_lower_left->teeth_name==1)
                  {
                if($get_permanent_lower_left->teeth_type==3)
                  {
                ?>
              <li><input type="text" class="w-40px" id='teeth_number' onclick='get_teeth_num_val(this.value);' name="teeth_number" value="<?php echo $get_permanent_lower_left->teeth_number; ?>" readonly></li>
             <?php
           }
         }
         }
         ?>
            </ul>
            <ul class="teeth-li">
             <?php
                foreach ($get_permanent as  $get_permanent_lower_left_num) {
                  if($get_permanent_lower_left_num->teeth_name==1)
                  {
                  if($get_permanent_lower_left_num->teeth_type==3)
                  {
                   if(!empty($get_permanent_lower_left_num->teeth_image))
                     {
                                $img_path_second = ROOT_UPLOADS_PATH.'dental/teeth_chart/'.$get_permanent_lower_left_num->teeth_image;
                           }
                  
                ?>
              <li><img src="<?php echo $img_path_second ;?>" alt="" class="img-responsive"></li>
              <?php
            }}
          }
          ?>
             
            </ul>
            
          </div> 
        
        </div>
        <div class="col-md-6 ">
          
          <div class="teeth-img-box">
            <ul class="teeth-num">
             <?php
                foreach ($get_permanent as  $get_permanent_lower_right) {
                if($get_permanent_lower_right->teeth_type==4)
                  {
                    if($get_permanent_lower_right->teeth_name==1)
                  {
                ?>
              <li><input type="text" class="w-40px" id='teeth_number' onclick='get_teeth_num_val(this.value);' name="teeth_number" value="<?php echo $get_permanent_lower_right->teeth_number; ?>" readonly></li>
              <?php
            }
          }
          }
            ?>
             
            </ul>
            <ul class="teeth-li">
            <?php
                foreach ($get_permanent as  $get_permanent_lower_right_num) {
                  if($get_permanent_lower_right_num->teeth_type==4)
                  {
                    if($get_permanent_lower_right_num->teeth_name==1)
                  {
                   if(!empty($get_permanent_lower_right_num->teeth_image))
                     {
                                $img_path_lower_right = ROOT_UPLOADS_PATH.'dental/teeth_chart/'.$get_permanent_lower_right_num->teeth_image;
                           }
                  
                ?>
              <li><img src="<?php echo $img_path_lower_right ;?>" alt="" class="img-responsive text-center"></li>
             <?php
           }
         }
         }
         ?>
            </ul>
            
          </div>
            
        </div>
          <?php
      }
      ?>
      </div>
      
      <div class="row">
        <div class="col-md-6"><h4 class="text-center">Lower Left</h4></div>
        <div class="col-md-6"><h4 class="text-center">Lower Right</h4></div>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer"> 
        
         <button type="button" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>     

<script>
function get_teeth_num_val(number)
{
  //alert(number);
  //var number=$('#teeth_number').val();
  if(number!='')
  {
    $('#get_teeth_number_val_treatment').val(number);
  $('#load_add_chart_perma_treatment_modal_popup').modal('hide');
}
  //alert(number);
}  

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}


$("button[data-number=1]").click(function(){
    $('#load_add_chart_perma_treatment_modal_popup').modal('hide');
});


</script>  

        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->