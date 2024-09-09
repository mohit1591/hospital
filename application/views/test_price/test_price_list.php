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

<body id="test_list">


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">

         <form id="price_list">
            
               <!-- bootstrap data table -->
               <div class="grp_box m-b-5">
                    <div class="grp">
                    <?php
                $branch_attribute = get_permission_attr(1,2);  
                //if(in_array('1',$users_data['permission']['section']) && in_array('2',$users_data['permission']['action']) && $branch_attribute>0)
                if(in_array('1',$users_data['permission']['section']) && in_array('2',$users_data['permission']['action']))
                {
                ?>
                    <input type="radio"  class="" onclick ="get_data(this.value);" id="branch" name="type" id="status" value="0" checked="checked"/> Branch   &nbsp;&nbsp;
                <?php
                }
                ?>    
               <input type="radio"  onclick ="get_data(this.value);" id="doctors" class="" name="type"  id="login_status" value="1" /> Doctors  
                    </div>
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
          <table id="table" class="table table-striped table-bordered test_price" cellspacing="0" width="100%">
               <thead class="bg-theme">
                    <tr>
                         
                         <th> <input name="selectall" class="" id="selectAll" value="" type="checkbox"> </th>
                         <th>  Department </th>
                         <th>  Test Name </th> 
                         <th> Patient Rate </th>
                         <th> Branch Rate </th> 
                         <th>Action</th>
                        
                         
                         
                    </tr>
               </thead>  
               <tbody id="test_lists">
                  <tr><td colspan="6" align="center"><font color="#b64a30">Test not available.</font></td></tr>
               </tbody>
          </table>
          </form>
     </div> <!-- close -->
     <div class="userlist-right">
         
       
  		<div class="permission-btns" style="float:left;">
             <div class="btns">
               <button class="btn-exit m-t-30px" onclick="return price_list_form()">
                    <i class="fa fa-save"></i> Save
               </button>
               <button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>'">
                    <i class="fa fa-sign-out"></i> Exit
               </button>
  	     </div>
         </div>
  	</div> 
  	<!-- right --> 
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script>
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

$(document).ready(function(){
     $.post('<?php echo base_url('test_price/get_allsub_branch_list/'); ?>',{},function(result){
          $("#child_branch").html(result);
     });
     $.post('<?php echo base_url('test_price/get_all_departments_list/'); ?>',{},function(result){
        $("#departments_list").html(result);
     });
 });
 var count = 0;
 function get_test_list(val){
       $("#test_lists tbody").html('<tr><td colspan="6" align="center"><font color="#b64a30">Test not available.</font></td></tr>');
       var Id = $("#test_heads_id :selected").val();
       var branchId = $("#sub_branch_id :selected").val();
        var depId = $("#department_id :selected").val();
        var doctors_id = $("#doctors_id :selected").val();
        var chooseBranch;
        var chooseTestHead;
        var chooseDoc;
       if(val==branchId)
       {
          chooseBranch = val;
          chooseTestHead=Id;
          chooseDoc = doctors_id;
       }
       else if(val==Id)
       {
          chooseTestHead=val;
          chooseBranch=branchId;
          chooseDoc= doctors_id;
       }
       else if(val==doctors_id)
       {
          chooseDoc = val;
          chooseBranch=branchId;
          chooseTestHead = Id;
       } 
      if(Id!='')
       {
       $.post('<?php echo base_url('test_price/get_test_list/'); ?>',{'test_head_id':chooseTestHead,'branch_id':chooseBranch,'department_id':depId,'doctors_id':doctors_id},function(result){
            $("#test_lists").html(result);
        });
        
       }
 }
  
 
 function get_data(value){
     $("#test_lists tbody").html('<tr><td colspan="6" align="center"><font color="#b64a30">Test not available.</font></td></tr>');
     
     if(value==0)
     {
          $('#doctors_id').prop('selectedIndex',0);
          document.getElementById("branch_list").style.display = "block";
          document.getElementById("doctors_list").style.display = "none";
          document.getElementById("test_heads_ids").style.display="none";
          $("#department_id").val('');
          $("#test_head_id").val('');
          $("#sub_branch_id").val('');
     }
     else if(value==1)
     {
          $('#branch').prop('selectedIndex',0);
          $.post('<?php echo base_url('test_price/get_transactional_doctors_list/'); ?>',{},function(result)
              {
               if(result!='')
               {
                    $("#transactional_doctors_list").html(result);
               }

          })
          document.getElementById("doctors_list").style.display = "block";
          document.getElementById("branch_list").style.display = "none";
          document.getElementById("test_heads_ids").style.display="none";
          $("#department_id").val('');
          $("#test_head_id").val('');
          $("#sub_branch_id").val('');
     }

 }
 function get_test_heads(value){
     $("#test_lists tbody").html('<tr><td colspan="6" align="center"><font color="#b64a30">Test not available.</font></td></tr>');
     if(value!=''){
          $.post('<?php echo base_url('test_price/get_test_heads_list/'); ?>',{'department_id':value},function(result){
                  document.getElementById("test_heads_ids").style.display="block";
                  $("#test_heads_ids").html(result);
          })
     }
 }
 function save_test_rate(obj)
 {

    
      var test_id = $(obj).data('testid');
      
      var rateID = $(obj).data('rateid');
      var baseRateId = $(obj).data('baserate');
     
      var rate = $("#"+rateID).val();
      var base_rate = $("#"+baseRateId).val();

     var Id = $("#test_head_id :selected").val();
     var branchId = $("#sub_branch_id :selected").val();
     var depId = $("#department_id :selected").val();
     var doc_ids = $("#doctors_id :selected").val();
     var type = $("input[name='type']:checked").val()
    
     
     $.post('<?php echo base_url('test_price/save_test_rate/'); ?>',{'rate':rate,'base_rate':base_rate,'test_head_id':Id,'branch_id':branchId,'department_id':depId,'doc_id':doc_ids,'test_id':test_id,'type':type},function(result){
          if(type==1 && doc_ids=='')
          {
               
               $("#msg").html(result);
          }
          else
          {
               $("#msg").html('');
               var msg = "test price saved successfully";
               flash_session_msg(msg);
          }

     })

 }

 function price_list_form()
 {
  $.ajax({
    url: "<?php echo base_url('test_price/save_price_list'); ?>",
    type: "post",
    data: $('#price_list').serialize(),
    success: function(result) 
    {
      flash_session_msg('Test Price Successfully Saved.');
    }
  });
 }
</script>

<!-- Confirmation Box -->

    


</div><!-- container-fluid -->
</body>
</html>