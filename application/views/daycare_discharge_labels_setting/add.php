<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<?php  $users_data = $this->session->userdata('auth_users'); ?>
<meta name="viewport" content="width=1024">


<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>script.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>custom.js"></script>

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
 



</head>

<body>
 

<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    
    <div class="userlist-box">
    <div class="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
     <form id="discharge_tab_setting_form">


      <div class="row">
        <div class="col-xs-4 br-h-small">
              <div class="row">
                <div class="col-xs-6"><strong>Title</strong></div>
                <div class="col-xs-6"><strong>Variable Name </strong></div>
              </div>
        </div> <!-- 6 -->

         <div class="col-xs-8">
          <div class="row">
            <div class="col-xs-4"><strong class="m14 p-l-2em">Value</strong></div>
            <div class="col-xs-4"><strong class="m15">Order By</strong></div>
            <div class="col-xs-2"><strong class="m16">Status</strong></div>
            <div class="col-xs-2"><strong class="m16">Print Status</strong></div>
          </div> <!-- innerRow -->
        </div> <!-- 5 -->
      </div> <!-- row -->


      


      <?php
//echo "<pre>";print_r($discharge_labels_setting_list); exit;

        if(in_array('691',$users_data['permission']['action']))
        {
        if(!empty($discharge_labels_setting_list))
        {
          foreach($discharge_labels_setting_list as $tab_setting_list)
          {
            //print_r($tab_setting_list); exit; 
        ?>
      <div class="row">
        <div class="col-xs-4 br-h-small">
              <div class="row">
                <div class="col-xs-6">
                  <strong><?php  echo $tab_setting_list['var_title']; ?></strong> <!-- text-uppercase -->
                </div>

                <?php if($tab_setting_list['id']==4 && $tab_setting_list['type']=='common') 
                { ?>
                 
                  <input type="hidden" class="m_name" value="<?php echo $tab_setting_list['setting_name']; ?>" name="data[<?php echo $tab_setting_list['id']; ?>][setting_name]"/>
                  <input type="hidden" class="m-l-1em m_name" name="data[<?php echo $tab_setting_list['id']; ?>][status]"  value="1"  checked="checked" >
                <input type="hidden" class="m-l-1em m_name" name="data[<?php echo $tab_setting_list['id']; ?>][print_status]"  value="1"  checked="checked" >
                  <div class="col-sm-6">
                  <?php  foreach($discharge_vital_setting_list as $discharge_labels){
                  ?>
                    <div class="row" style="margin-bottom: 8px;">
                      <div class="col-md-4"><label><?php echo $discharge_labels->var_title; ?></label></div>
                      <div class="col-md-8"><input type="text" class="w-100px m_name" name="vitals[<?php echo $discharge_labels->id; ?>][setting_name]" value="<?php echo $discharge_labels->setting_name; ?>"  onkeypress="return onlyAlphabets(event,this);"  data-toggle="tooltip"  title="Allow only characters." class="tooltip-text" <?php if(!empty($discharge_labels->setting_name)){ echo 'readonly'; } ?> placeholder=""></div>
                    </div>
                    <?php } ?> 
                  </div>
                  
           
               <?php 
                } 
                else 
                {     

                      if($tab_setting_list['type']=='common')
                      {  
                        ?>
                    <div class="col-xs-6">
                      <input class="form-control text-13px m_input_default"  name="data[<?php echo $tab_setting_list['id']; ?>][setting_name]" value="<?php echo $tab_setting_list['setting_name']; ?>"  onkeypress="return onlyAlphabets(event,this);" type="text" data-toggle="tooltip"  title="Allow only characters." class="tooltip-text" <?php if(!empty($tab_setting_list['setting_name'])){ echo 'readonly'; } ?>>
                  </div>
                    <?php 
                      }
                      elseif($tab_setting_list['type']=='vitals')
                      {  
                        ?>
                        <div class="col-xs-6">
                          <input class="form-control text-13px m_input_default"  name="vitals[<?php echo $tab_setting_list['id']; ?>][setting_name]" value="<?php echo $tab_setting_list['setting_name']; ?>"  onkeypress="return onlyAlphabets(event,this);" type="text" data-toggle="tooltip"  title="Allow only characters." class="tooltip-text" <?php if(!empty($tab_setting_list['setting_name'])){ echo 'readonly'; } ?>>
                      </div>
                    <?php 
                      }
                  
                  }
                  ?>
                
              </div> <!-- innerRow -->
              </div> <!-- 6 -->
              <?php 
              if($tab_setting_list['id']==4 && $tab_setting_list['type']=='common') 
              { ?>
                  <input type="hidden" class="m_input_default" name="data[<?php echo $tab_setting_list['id']; ?>][setting_value]" value="<?php echo $tab_setting_list['setting_value']; ?>"/>
                  <input type="hidden" class="m_input_default" name="data[<?php echo $tab_setting_list['id']; ?>][order_by]" value="<?php echo $tab_setting_list['order_by']; ?>"/>

                 <?php foreach($discharge_vital_setting_list as $discharge_labels){ ?> 
                      <div class="col-xs-8">
                        <div class="row m-b-5">
                          <div class="col-xs-4"> 
                          <input name="vitals[<?php echo $discharge_labels->id; ?>][vital_type]" value="0" <?php if($discharge_labels->vital_type==0){ echo 'checked="checked"'; } ?> type="radio"> Vitals
                          <input name="vitals[<?php echo $discharge_labels->id; ?>][vital_type]" value="1" <?php if($discharge_labels->vital_type==1){ echo 'checked="checked"'; } ?> type="radio"> Separated

                          
                          
                              <input class="w-90px" name="vitals[<?php echo $discharge_labels->id; ?>][setting_value]" value="<?php echo $discharge_labels->setting_value; ?>" type="text" placeholder="Custom Name" data-toggle="tooltip"  title="Custom Name" class="tooltip-text">
                           
                           
                             
                          </div>

                          <div class="col-xs-4">
                        
                          <input class="form-control text-13px m_input_default" name="vitals[<?php echo $discharge_labels->id; ?>][order_by]" value="<?php echo $discharge_labels->order_by; ?>" type="text" placeholder="Order By" data-toggle="tooltip"  title="Order By" class="tooltip-text">
                          </div>
                          <div class="col-xs-2">
                           <input type="hidden" />
                            <input type="checkbox" class="m-l-1em m_input_default" name="vitals[<?php echo $discharge_labels->id; ?>][status]"  value="1" <?php if($discharge_labels->status==1){ ?> checked="checked" <?php } ?>>
                          </div> <!-- 2 -->
                          <div class="col-xs-2">
                            <input type="checkbox" class="m-l-1em m_input_default" name="vitals[<?php echo $discharge_labels->id; ?>][print_status]"  value="1" <?php if($discharge_labels->print_status==1){ ?> checked="checked" <?php } ?>>
                        </div> <!-- 2 -->
                        </div> <!-- innerRow -->

                      </div> <!-- 5 -->

              <?php   
                } 
                } 
                else 
                { //echo "<pre>";print_r($tab_setting_list);
                  if($tab_setting_list['type']=='common')
                  { 
                      ?>
                        <div class="col-xs-8">
                          <div class="row">
                            <div class="col-xs-4">
                              <div class="p-l-2em">
                                <input class="form-control text-13px m_input_default" name="data[<?php echo $tab_setting_list['id']; ?>][setting_value]" value="<?php echo $tab_setting_list['setting_value']; ?>" type="text" placeholder="Custom Name" data-toggle="tooltip"  title="Custom Name" class="tooltip-text">
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <input class="form-control m_input_default" onkeypress="return isNumberKey(event);" name="data[<?php echo $tab_setting_list['id']; ?>][order_by]" value="<?php echo $tab_setting_list['order_by']; ?>" type="text" placeholder="Order By" data-toggle="tooltip"  title="Numeric Only" class="tooltip-text">
                            </div>
                            <div class="col-xs-2">
                              <input type="checkbox" class="m-l-1em " name="data[<?php echo $tab_setting_list['id']; ?>][status]"  value="1" <?php if($tab_setting_list['status']==1){ ?> checked="checked" <?php } ?>>
                            </div> <!-- 2 -->
                            <div class="col-xs-2">
                              <input type="checkbox" class="m-l-1em " name="data[<?php echo $tab_setting_list['id']; ?>][print_status]"  value="1" <?php if($tab_setting_list['print_status']==1){ ?> checked="checked" <?php } ?>>
                          </div> <!-- 2 -->
                          </div> <!-- innerRow -->
                    </div> <!-- 5 -->
                  <?php 

                    }
                    elseif($tab_setting_list['type']=='vitals')
                    { //vital input fields 
                      
                      ?>
                      <div class="col-xs-8">
                          <div class="row">
                            
                            <div class="col-xs-4"> 
                                <input name="vitals[<?php echo $tab_setting_list['id']; ?>][vital_type]" value="0" <?php if($tab_setting_list['vital_type']==0){ echo 'checked="checked"'; } ?> type="radio"> Vitals
                                <input name="vitals[<?php echo $tab_setting_list['id']; ?>][vital_type]" value="1" <?php if($tab_setting_list['vital_type']==1){ echo 'checked="checked"'; } ?> type="radio"> Separated
                               
                        
                                <input class="w-90px" name="vitals[<?php echo $tab_setting_list['id']; ?>][setting_value]" value="<?php echo $tab_setting_list['setting_value']; ?>" type="text" placeholder="Custom Name" data-toggle="tooltip"  title="Custom Name" class="tooltip-text">
                                

                            </div>
                            <div class="col-xs-4">
                                <input class="form-control m_input_default" onkeypress="return isNumberKey(event);" name="vitals[<?php echo $tab_setting_list['id']; ?>][order_by]" value="<?php echo $tab_setting_list['order_by']; ?>" type="text" placeholder="Order By" data-toggle="tooltip"  title="Numeric Only" class="tooltip-text">
                            </div>
                            <div class="col-xs-2">
                              <input type="checkbox" class="m-l-1em " name="vitals[<?php echo $tab_setting_list['id']; ?>][status]"  value="1" <?php if($tab_setting_list['status']==1){ ?> checked="checked" <?php } ?>>
                            </div> <!-- 2 -->
                            <div class="col-xs-2">
                              <input type="checkbox" class="m-l-1em " name="vitals[<?php echo $tab_setting_list['id']; ?>][print_status]"  value="1" <?php if($tab_setting_list['print_status']==1){ ?> checked="checked" <?php } ?>>
                          </div> <!-- 2 -->
                          </div> <!-- innerRow -->
                    </div> <!-- 5 -->
                  <?php 
                    }

                  ?>
        
        
        <?php } ?>

        
      </div> <!-- row -->
      
        <?php
            }
          }
         ?> 
      <div class="row">
        <div class="col-xs-5">
              <div class="row">
                <div class="col-xs-5"></div>
                <div class="col-xs-7 p-l-0 m19">
                    <button class="btn-update" name="submit" value="Save" type="submit"><i class="fa fa-floppy-o"></i>  Save</button>
                    <input class="btn-cancel" name="cancel" value="Close" type="button" onclick="window.location.href='<?php echo base_url(); ?>'">
                </div>
              </div>
        </div> <!-- 5 -->
        <div class="col-xs-7">
              <div class="row">
                <div class="col-xs-6"></div>
                <div class="col-xs-6">
                </div>
              </div>
        </div> <!-- 5 -->
      </div> <!-- row -->
      <?php } ?>
        </form>


   </div> <!-- close -->
 
 
  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script>  

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}

 function onlyAlphabets(e, t) {

            try {

                if (window.event) {

                    var charCode = window.event.keyCode;

                }

                else if (e) {

                    var charCode = e.which;

                }

                else { return true; }

                if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123))

                    return true;

                else

                    return false;

            }

            catch (err) {

                alert(err.Description);

            }

        } 
 
$("#discharge_tab_setting_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('.overlay-loader').show();
  $.ajax({
    url: "<?php echo base_url(); ?>daycare_discharge_labels_setting/",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
       flash_session_msg(result);    
       $('.overlay-loader').hide();    
    }
  });
});

$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});
</script>   
</div><!----container-fluid--->
</body>
</html>