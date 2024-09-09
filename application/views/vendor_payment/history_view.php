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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>


<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 



</head>

<body id="bal_list" >


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">

         <form>
          <table id="table" class="table table-striped table-bordered vendor_payment_list" cellspacing="0" width="100%">
               <thead class="bg-theme">
                    <tr>
                      <th>Sr. No.</th>
                      <th>Name </th>
                      <th>Amount</th> 
                      <th>Date </th> 
                      <th>Action</th>
                    </tr>
               </thead>  
               <tbody>
                <?php if(!empty($list)){ $i=0;
                  foreach ($list as $key => $detail) { $i++; ?>
                   <tr>
                      <td><?php echo $i;?></td>
                      <td><?php echo $detail['name'];?> </td>
                      <td><?php echo $detail['paid_amount'];?></td> 
                      <td><?php echo date('d-m-Y',strtotime($detail['expenses_date']));?> </td> 
                      <td><button type="button" class="btn-custom" name="pay_now" 
                              id="pay_now" onclick="pay_now_to_branch('<?php echo $detail['paid_to_id'];?>','<?php echo $detail['paid_amount'];?>','<?php echo $detail['type'];?>','<?php echo $detail['branch_id'];?>','<?php echo $detail['id'];?>','<?php echo $detail['pay_id'];?>','<?php echo $detail['section_id'];?>','<?php echo $detail['parent_id'];?>');">Edit</button>
                           <button type="button" class="btn-custom" name="pay_delete" 
                              id="pay_delete" onclick="pay_now_to_delete('<?php echo $detail['id'];?>','<?php echo $detail['pay_id'];?>','<?php echo $detail['type'];?>');"> Delete</button>

                      </td>
                    </tr>
                  <?php }}else{?>
                  <tr><td colspan="5">No Record found..</td></tr>
                  <?php } ?>
               </tbody>
          </table>
          </form>
     </div> <!-- close -->
     <div class="userlist-right">
         
       
      <div class="btns">
     
             
               <button class="btn-exit m-t-30px" onclick="window.location.href='<?php echo base_url('vendor_payment'); ?>'">
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
function pay_now_to_branch(id,balance,type,branch_id,exp_id,pay_id,section_id,parent_id)
{ 
    var $modal = $('#load_add_pay_now_modal_popup');
    $modal.load('<?php echo base_url().'vendor_payment/pay_now/' ?>'+id+'/'+type+'/'+branch_id,
    {'bal': balance,'section_id':section_id,'expense_id':exp_id, 'payment_id':pay_id,'parent_id':parent_id},
     function(){
          $modal.modal('show');
     });
}

function pay_now_to_delete(expense_id,payment_id,type)
{ 
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
        })
        .one('click', '#delete', function(e)
        {
           $('.overlay-loader').show();
            var msg = 'Payment successfully deleted.';
          $.ajax({
                   type: "POST",
                   url: "<?php echo base_url('vendor_payment/paid_delete_data'); ?>", 
                   data: {expense_id:expense_id,payment_id:payment_id,type:type},
                   success: function(result)
                   {
                       if(result ==1){
                         flash_session_msg(msg);
                         location.reload();
                     }
                   $('.overlay-loader').hide(); 
                  }
                });
           });
}

</script>
</div><!-- container-fluid -->

<!-- Confirmation Box -->

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->

<div id="load_add_pay_now_modal_popup" class="modal fade modal-45" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</body>
</html>