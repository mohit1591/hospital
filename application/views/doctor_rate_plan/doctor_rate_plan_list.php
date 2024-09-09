<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
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
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>





</head>
  <script type="text/javascript">

    var save_method; 
    var table; 
    var profile_table; 
    <?php
if(in_array('1228',$users_data['permission']['action'])) 
{
?>
    $(document).ready(function() { 
     
        table = $('#panel_price').DataTable({  
            "processing": true,
            "searching": false,
            "bLengthChange": false, 
            "serverSide": true, 
            "order": [], 
            "pageLength": '2000',
            "ajax": {
                "url": "<?php echo base_url('doctor_rate_plan/path_ajax_list/'.$form_data['data_id'])?>",
                "type": "POST",
                "data":function(d){
                              d.branch_id =  '<?php echo $users_data["parent_id"]; ?>';
                              d.dept_id =  $('#department_id').val();
                              d.test_head =  $("#test_heads_id :selected").val();
                              d.doctor_id ='<?php echo $doctor_id; ?>';
                              return d;
                        }
            },
            "columnDefs": [
                            { 
                                "targets": [ 0 , -1 ], //last column
                                "orderable": false, //set not orderable

                            },
                          ],
        });
        
        
        
    }); 
    <?php } ?>

    function reload_table()
    {
        table.ajax.reload(null,false); //reload datatable ajax 
    }
    </script>

<body id="test_list">


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">
        
        <div class="row m-t-10">
                    <div class="col-xs-11">
                        <ul class="nav nav-tabs" >
            <li  class="active"  ><a onclick="return tab_links('tab_test_history')" data-toggle="tab" href="#tab_test_history">Test</a></li>
                <li  ><a onclick="return tab_links('tab_profile_history')" data-toggle="tab" href="#tab_profile_history">Profile</a></li>
             </ul>
                <div class="tab-content">
                                    <div class="tab-content">

                            
    <div id="tab_test_history" class="inner_tab_box tab-pane fade   in active ">
        <div class="row">
            <div class="col-xs-12">
                
                <form id="price_list">
            
               <!-- bootstrap data table -->
               <div class="grp_box m-b-5">
               
               <?php
               if(in_array('1',$users_data['permission']['section']) && in_array('2',$users_data['permission']['action']))
                {
               ?>     
                    <div class="grp" id="branch_list" > <div id="child_branch" class="grp"></div></div>
                <?php
                }
                ?>   
                    <div class="grp" id="doctors_list"  style="display:none;"> <div id="transactional_doctors_list" class="grp"></div><p class="msg" id="msg" style="color:red;"></p></div>
                    
                    
                    <div id="departments_list" class="grp"></div>
                    <div id="test_heads_ids" class="grp" ></div>
                    
               </div>
                <!-- bootstrap data table -->
             
                <!-- bootstrap data table -->
          <table id="panel_price" class="table table-striped table-bordered path_panel_price" cellspacing="0" width="100%">
               <thead class="bg-theme">
                    <tr>
                         
                         <th> <input name="selectall" class="" id="selectAll" value="" type="checkbox"> </th>
                         <th>Department </th>
                         <th>Test Name </th> 
                         <th>Price </th>
                         <th>Action</th>
                        
                      </tr>
               </thead>  
               <tbody id="test_lists">
                  <tr><td colspan="6" align="center"><font color="#b64a30">Test not available.</font></td></tr>
               </tbody>
          </table>
          </form>
                                        
                </div>
                
            </div>
        </div>
                            
            <div id="tab_profile_history" class="inner_tab_box tab-pane fade">
                <div class="row">
                    <div class="col-xs-8">
            <table id="panel_price"  class="table table-striped table-bordered path_panel_price dataTable no-footer" cellspacing="0" width="100%">
               <thead class="bg-theme">
                    <tr>
                         
                         <th> <input name="selectall" class="" id="selectAll" value="" type="checkbox"> </th>
                        
                         <th>Profile Name </th> 
                         <th>Price </th>
                         <th>Action</th>
                        
                      </tr>
               </thead>  
               <tbody>
                   <?php 
                   if(!empty($profile_list))
                   {
                       $i=0;
                       foreach ($profile_list as $profile_Management) 
                       {
                           //echo "<pre>"; print_r($profile_Management); exit;
                           ?>
                          <tr>
                         
                         <td>  </td>
                        
                         <td><?php echo $profile_Management->profile_name;?></td> 
                         <td><input type="hidden" name="doctor_id" id="doctor_id" value="<?php echo $doctor_id; ?>"/> <input type="hidden" name="profile_id[<?php echo $i;?>][profile_id]"   value="<?php echo $profile_Management->profile_id;?>"/><input type="text" name="profile_id[<?php echo $i?>][path_price]"  id="profile_price_<?php echo $i; ?>"  value="<?php echo $profile_Management->path_price;?>"/> </td>
                         <td><button type="button" class="btn-custom"   
                data-profilprice="profile_price_<?php echo $i;?>" data-profileid="<?php echo $profile_Management->profile_id;?>" data-docid="" id="save_test" onclick="save_profile_rate_rate(this);">Save</button></td>
                        
                      </tr>
                      <?php 
                       $i++;     
                       }
                   }
                   else
                   {
                   ?>
                  <tr><td colspan="3" align="center"><font color="#b64a30">Test not available.</font></td></tr>
                  <?php } ?>
               </tbody>
          </table>
                    </div>
                    
                </div>
            </div>
            
    </div>
</div>

</div>
</div>

         
     </div> <!-- close -->
     <div class="userlist-right">
         
       
  		<div class="btns">
     
              <!-- <button class="btn-exit m-t-30px" onclick="return price_list_form()">
                    <i class="fa fa-save"></i> Save
               </button>-->
       
               <button class="btn-exit" onclick="window.location.href='<?php echo base_url('doctors'); ?>'">
                    <i class="fa fa-sign-out"></i> Exit
               </button>
  	     </div>
  	</div> 
  	<!-- right --> 
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script>
 function tab_links(vals)
  {
    $('.inner_tab_box').removeClass('in');
    $('.inner_tab_box').removeClass('active');  
    $('#'+vals).addClass('class','in');
    $('#'+vals).addClass('class','active');
  }
 $(document).ready(function(){ 
   $('#selectAll').on('click', function () { 
                                 
         if ($("#selectAll").hasClass('allChecked')) {
             $('.checklist').prop('checked', false);
         } else {
             $('.checklist').prop('checked', true);
         }
         $(this).toggleClass('allChecked');
    });
});
 function set_test_head(head_id)
{
    $.ajax({
        url: "<?php echo base_url('doctor_rate_plan/set_test_head/'); ?>"+head_id, 
        success: function(result)
        { 
           reload_table();
        }
    });
}

$(document).ready(function(){
    
     $.post('<?php echo base_url('doctor_rate_plan/get_all_departments_list/'); ?>',{},function(result){
        $("#departments_list").html(result);
     });
 });
 var count = 0;
 function get_test_list(val){

       $("#test_lists tbody").html('<tr><td colspan="6" align="center"><font color="#b64a30">Test not available.</font></td></tr>');
       var Id = $("#test_heads_id :selected").val();
       var branchId = $("#sub_branch_id :selected").val();
        var depId = $("#department_id :selected").val();
        var doctor_id = '<?php echo $doctor_id; ?>';
        
        var chooseBranch;
        var chooseTestHead;
        var chooseDoc;
       if(val==branchId)
       {
          chooseBranch = val;
          chooseTestHead=Id;
          doctor_id=doctor_id;
       }
       else if(val==Id)
       {
          chooseTestHead=val;
          chooseBranch=branchId;
          
          doctor_id=doctor_id;
       }
       else if(val==doctors_id)
       {
          chooseDoc = val;
          chooseBranch=branchId;
          chooseTestHead = Id;
          doctor_id=doctor_id;
       } 
       else if(val==doctor_id)
       {
        doctor_id=val;
        chooseBranch=branchId;
        chooseTestHead = Id;
        chooseDoc= doctors_id;
       }
       $.post('<?php echo base_url('doctor_rate_plan/get_test_list/'); ?>',{'test_head_id':chooseTestHead,'branch_id':chooseBranch,'department_id':depId,'doctor_id':doctor_id},function(result){
            $("#test_lists").html(result);
            reload_table();
        });
 }
  
 
 function get_test_heads(value){
     $("#test_lists tbody").html('<tr><td colspan="6" align="center"><font color="#b64a30">Test not available.</font></td></tr>');
     if(value!=''){
          $.post('<?php echo base_url('doctor_rate_plan/get_test_heads_list/'); ?>',{'department_id':value},function(result){
                  document.getElementById("test_heads_ids").style.display="block";
                  $("#test_heads_ids").html(result);
          })
     }
 }
  function get_panel_list(value){
     var branchId = $("#sub_branch_id :selected").val();
      var doctor_id = '<?php echo $doctor_id; ?>';
     $("#test_lists tbody").html('<tr><td colspan="6" align="center"><font color="#b64a30">Test not available.</font></td></tr>');
     
 }
 function save_test_rate_plan(obj)
 {

    
    var test_id = $(obj).data('testid');
    var price_k = $(obj).data('price');
    var price = $("#"+price_k).val();
    var Id = $("#test_heads_id :selected").val();
    var branchId = $("#sub_branch_id :selected").val();
    var depId = $("#department_id :selected").val();
    var doctor_id = '<?php echo $doctor_id; ?>';
     
     $.post('<?php echo base_url('doctor_rate_plan/save_test_rate_plan/'); ?>',{'test_head_id':Id,'branch_id':branchId,'department_id':depId,'test_id':test_id,'doctor_id':doctor_id,'price':price},function(result){
          if(result==1)
          {
             error_flash_session_msg('please select panel'); 
             reload_table();
          }
          else
          {

           $("#msg").html('');
               var msg = "Rate plan price saved successfully";
               flash_session_msg(msg); 
          }
     })

 }

 function price_list_form()
 {
 
   var doctor_id = '<?php echo $doctor_id; ?>';
   var depId = $("#department_id :selected").val();
    var Id = $("#test_heads_id :selected").val();
  $.ajax({
    url: "<?php echo base_url('doctor_rate_plan/save_price_list'); ?>",
    type: "post",
    data: $('#price_list').serialize(),
    success: function(result) 
    {
       if(result==1)
          {
             error_flash_session_msg('please select panel'); 
             reload_table();
          }
          else
          {

               $("#msg").html('');
               var msg = "Rate Plan price saved successfully";
               flash_session_msg(msg); 
          }
    }
  });
 }
 
  function save_profile_rate_rate(obj)
 {
    var profile_id = $(obj).data('profileid');
    var profprice_k = $(obj).data('profilprice');
    var price = $("#"+profprice_k).val();
    var branchId = $("#sub_branch_id :selected").val();
    var doctor_id = '<?php echo $doctor_id; ?>';
     
     $.post('<?php echo base_url('doctor_rate_plan/save_profile_rate_plan/'); ?>',{'branch_id':branchId,'profile_id':profile_id,'doctor_id':doctor_id,'price':price},function(result){
          if(result==1)
          {
             error_flash_session_msg('please select Rate Plan'); 
             reload_table();
          }
          else
          {

           $("#msg").html('');
               var msg = "Rate plan price saved successfully";
               flash_session_msg(msg); 
          }
     })

 }

</script>

<!-- Confirmation Box -->

    


</div><!-- container-fluid -->
</body>
</html>