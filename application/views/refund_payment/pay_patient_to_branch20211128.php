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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 



</head>

<body>
 

<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->

<script>
          function get_payment_mode(id)
                        {
                        
                    if(id=='2')
                    { 
                         $('#tr_transaction_no').addClass('row_hide');
                         $('#tr_bank_name').addClass('row_hide');
                         $('#tr_cheque_no').addClass('row_hide');
                         $('#tr_cheque_date').addClass('row_hide');
                         $('#tr_card_no').removeClass('row_hide');
                         $("#transaction_no").val('');
                         $("#bank_name").val('');
                         $("#cheque_no").val('');
                         $("#cheque_date").val('');   
                    }
                    else if(id=='4')
                    {
                         $('#tr_transaction_no').removeClass('row_hide');
                         $('#tr_bank_name').addClass('row_hide');
                         $('#tr_cheque_no').addClass('row_hide');
                         $('#tr_cheque_date').addClass('row_hide');
                         $('#tr_card_no').addClass('row_hide');
                         $("#bank_name").val('');
                         $("#cheque_no").val(''); 
                         $("#card_no").val(''); 
                         $("#cheque_date").val(''); 
                    }
                    else if(id=='3')
                    {
                         $('#tr_transaction_no').addClass('row_hide');
                         $('#tr_bank_name').removeClass('row_hide');
                         $('#tr_cheque_no').removeClass('row_hide');
                         $('#tr_cheque_date').removeClass('row_hide');
                         $('#tr_card_no').addClass('row_hide'); 
                         $("#transaction_no").val(''); 
                         $("#card_no").val('');  
                    }
                    else
                    {
                         $('#tr_transaction_no').addClass('row_hide');
                         $('#tr_bank_name').addClass('row_hide');
                         $('#tr_cheque_no').addClass('row_hide');
                         $('#tr_cheque_date').addClass('row_hide');
                         $('#tr_card_no').addClass('row_hide');
                         $("#transaction_no").val('');
                         $("#bank_name").val('');
                         $("#cheque_no").val(''); 
                         $("#card_no").val(''); 
                         $("#cheque_date").val(''); 
                    }

              }
     get_payment_mode(<?php echo $form_data['payment_mode']; ?>);
</script>
<style>
.row_hide{display:none;}
</style>
<section class="userlist">
    <div class="userlist-box">
		<div class="overlay-loader">
			<img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
		</div>
		<?php $amount='';?>
  
		 <form action="<?php echo base_url('refund_payment/save_refund_details/'.$form_data['pid'].'/'.$form_data['section_id'].'/'.$form_data['parent_id'].'/'.$form_data['branch_id']); ?>" method="post">
	   
		<input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['pid']; ?>" />
		<input type="hidden" name="section_id" id="section_id" value="<?php echo $form_data['section_id']; ?>" />
		<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $form_data['parent_id']; ?>" />
		<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $form_data['branch_id']; ?>" />
		<!-- ///////////////////////////////////[ START WITH HERE ]////////////////////////////////////// -->

<div class="row">
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-4"><b>Patient Name:</b></div>
            <div class="col-md-8"><?php echo $patient_data['patient_name']; ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-4"><b>Patient Mobile:</b></div>
            <div class="col-md-8"><?php echo $patient_data['mobile_no']; ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-4"><b><?php echo $data= get_setting_value('PATIENT_REG_NO');?>:</b></div>
            <div class="col-md-8"><?php echo $patient_data['patient_code']; ?></div>
        </div>
    </div>
</div>
		
		<!-- ====== // -->
		<h4>Booking Detail</h4>
       <table id="dataTable" class="table table-striped table-bordered refund_payment_list dataTable no-footer" role="grid" aria-describedby="table_info" cellspacing="0">
          <thead class="bg-theme">
            <tr>
              <th>S.No</th>
              <th>Booking Id</th>
              <th>Booking Date</th>
              <th>Net Amount </th>
            </tr>
           
          </thead>
          <tbody>
          <?php if($form_data['section_id']==2)
          {?>
          <?php $i=1; ?>
        <?php if(!empty($booking_details_for_patient)){ ?>
          <?php foreach($booking_details_for_patient as $key=>$type) {?>
          <tr>
            <td><?php echo $i++;?></td>
            <td><?php echo $type['id']; ?></td>
          <td><?php echo date("d-m-Y", strtotime($type['booking_date']) ); ?></td>
         
            <td><?php echo $type['net_amount']; ?></td>
              <?php $x=0;
              $type['net_amount']=$x+$type['net_amount']; 
        $amount=$type['net_amount'];
        ?>
           </tr>

                        <?php } ?>

                        <?php } else  { ?>
        
          <tr>
          <td colspan="6">No Data Available in Table</td>
          </tr>
        
          <?php } ?>
          <?php } elseif ($form_data['section_id']==1){?>
          
            <?php $i=1; ?>
        <?php if(!empty($pathology_booking_data)){ //print_r($pathology_booking_data);?>

          
          <tr>
            <td><?php echo $i++;?></td>
            <td><?php echo $pathology_booking_data['id']; ?></td>
          <td><?php echo date("d-m-Y", strtotime($pathology_booking_data['booking_date']) ); ?></td>
         
            <td><?php echo $pathology_booking_data['net_amount']; ?></td>
              <?php $x=0;
              $pathology_booking_data['net_amount']=$x+$pathology_booking_data['net_amount']; 
        $amount=$pathology_booking_data['net_amount'];
        ?>
           </tr>

                    

                        <?php }else{ 
                            
                             ?>
        
          <tr>
          <td colspan="6">No Data Available in Table</td>
          </tr>
        
          <?php }  }elseif ($form_data['section_id']==5){
           $i=1;  
           if(!empty($ipd_booking_data)){ ?>
            <tr>
            <td><?php echo $i++;?></td>
            <td><?php echo $ipd_booking_data['ipd_no']; ?></td>
            <td><?php echo date("d-m-Y", strtotime($ipd_booking_data['admission_date']) ); ?></td>
             <td><?php echo $ipd_booking_data['net_amount']; ?></td>
              <?php $x=0;
             $ipd_booking_data['net_amount']=$x+$ipd_booking_data['net_amount']; $amount=$ipd_booking_data['net_amount'];
        ?>
           </tr>
        <?php }else{ ?>
        
          <tr>
          <td colspan="6">No Data Available in Table</td>
          </tr>
        
          <?php }  }elseif ($form_data['section_id']==4){
            $i=1; 
             if(!empty($billing_data)){ //print_r($billing_data); die;?>

          
          <tr>
            <td><?php echo $i++;?> </td>
            <td><?php echo $billing_data['id']; ?></td>
          <td><?php echo date("d-m-Y", strtotime($billing_data['booking_date']) ); ?></td>
         
            <td><?php echo $billing_data['net_amount']; ?></td>
              <?php $x=0;
                    $billing_data['net_amount']=$x+$billing_data['net_amount']; 
                    $amount=$billing_data['net_amount'];
              ?>
           </tr>

            <?php }  else  { ?>
        
          <tr>
          <td colspan="6">No Data Available in Table</td>
          </tr>
        
          <?php } ?>
          <?php } 
          
            elseif ($form_data['section_id']==8){ 
          $i=1; 
          
          if(!empty($ot_booking_data)){ //print_r($pathology_booking_data);?>


            <tr>
                <td><?php echo $i++;?></td>
                <td><?php echo $ot_booking_data['id']; ?></td>
                <td><?php echo date("d-m-Y", strtotime($ot_booking_data['operation_date']) ); ?></td>

                <td><?php echo $ot_booking_data['net_amount']; ?></td>
                <?php $x=0;
                $ot_booking_data['net_amount']=$x+$ot_booking_data['net_amount']; 
                $amount=$ot_booking_data['net_amount'];
                ?>
            </tr>



            <?php } else  
            { ?>

            <tr>
                <td colspan="6">No Data Available in Table</td>
             </tr>

            <?php } ?>
            

            <?php }
            
            /* receipent detail of payment refund */

            elseif ($form_data['section_id']==10){

             $i=1; ?>
            <?php if(!empty($receipent_booking_data)){ //print_r($pathology_booking_data);?>


            <tr>
                <td><?php echo $i++;?></td>
                <td><?php echo $receipent_booking_data['id']; ?></td>
                <td><?php echo date("d-m-Y", strtotime($receipent_booking_data['requirement_date']) ); ?></td>

                <td><?php echo $receipent_booking_data['net_amount']; ?></td>
                <?php $x=0;
                $receipent_booking_data['net_amount']=$x+$receipent_booking_data['net_amount']; 
                $amount=$receipent_booking_data['net_amount'];
                ?>
            </tr>



            <?php } else  
            { ?>

            <tr>
                <td colspan="6">No Data Available in Table</td>
             </tr>

            <?php } 
            
                
            }
            
             elseif ($form_data['section_id']==13){

             $i=1; ?>
            <?php if(!empty($ambulance_data)){ //print_r($pathology_booking_data);?>


            <tr>
                <td><?php echo $i++;?></td>
                <td><?php echo $ambulance_data['booking_no']; ?></td>
                <td><?php echo date("d-m-Y", strtotime($ambulance_data['booking_date']) ); ?></td>

                <td><?php echo $ambulance_data['net_amount']; ?></td>
                <?php $x=0;
                $ambulance_data['net_amount']=$x+$ambulance_data['net_amount']; 
                $amount=$ambulance_data['net_amount'];
                ?>
            </tr>



            <?php } 
          else  
            { ?>

            <tr>
                <td colspan="6">No Data Available in Table</td>
             </tr>

            <?php } 
            
                
            }
             elseif($form_data['section_id']==14)
           { 
          // echo "<pre>"; print_r($daycare_booking_data); exit;
            $i=1; 
            if(!empty($daycare_booking_data)){ ?>
           <tr>
            <td><?php echo $i++;?></td>
            <td><?php echo $daycare_booking_data['booking_code']; ?></td>
          <td><?php echo date("d-m-Y", strtotime($daycare_booking_data['booking_date']) ); ?></td>
         
            <td><?php echo $daycare_booking_data['paid_amount'] + $daycare_booking_data['daycare_paid_amount']; ?></td>
              <?php $x=0;
              $daycare_booking_data['net_amount']=$x+$daycare_booking_data['paid_amount'] + $daycare_booking_data['daycare_paid_amount']; 

           echo $amount = $daycare_booking_data['paid_amount'] + $daycare_booking_data['daycare_paid_amount'];
            ?>
           </tr>
        

            <?php } else  { ?>
        
          <tr>
          <td colspan="6">No Data Available in Table</td>
          </tr>
        
          <?php } ?>

          <?php }
          /* receipent detail of payment refund */
          else {?>
           
              <?php $i=1; ?>
        <?php if(!empty($medicine_booking_data)){ ?>
          <?php //foreach($medicine_booking_data as $key=>$medicine_type) {?>
          <tr>
            <td><?php echo $i++;?></td>
            <td><?php echo $medicine_booking_data['id']; ?></td>
          <td><?php echo date("d-m-Y", strtotime($medicine_booking_data['sale_date']) ); ?></td>
         
            <td><?php echo $medicine_booking_data['net_amount']; ?></td>
              <?php $x=0;
              $medicine_booking_data['net_amount']=$x+$medicine_booking_data['net_amount']; 
        $amount=$medicine_booking_data['net_amount'];
        ?>
           </tr>

                        <?php //} ?>

                        <?php } else  { ?>
        
          <tr>
          <td colspan="6">No Data Available in Table</td>
          </tr>
        
          <?php } ?>
          <?php }  
          
          ?>

          </tbody>
        </table>
		
		
		
	
		
		
		<!--<h4>Test Details</h4>-->
		<div class="row m-b-5">
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-5"><label>Total Amount</label></div>
					<div class="col-md-7"><?php echo number_format((float)$amount, 2, '.', '');?></div>
				</div>
			</div>
		</div>
		<div  id="inner_table" style="display:none;">
		<div class="row">
			<div class="col-sm-4">
				<div class="row m-b-5">
					<div class="col-sm-5"><b>Mode of Payment</b></div>
					<div class="col-sm-7">
						<select name="pay_mode" id="pay_mode" onChange="return pay_fields(this.value);">
						  <?php foreach($payment_mode as $payment_mode) 
						  {?>
						  <option value="<?php echo $payment_mode->id;?>" ><?php echo $payment_mode->payment_mode;?></option>
						  <?php }?>
						</select>
					</div>
				</div> <!-- innerRow -->
				<div class="">
					<div id="cheque"> </div>
				</div>
				
			</div> <!-- 4 -->
			<div class="col-sm-4">
				<div class="row m-b-5">
					<div class="col-sm-5"><b>Amount to Pay <span class="star">*</span> </b></div>
					<div class="col-sm-7"><input type="text" class="price_float" name="refund_amount" id="refund_amount" value=""/></div>
				</div> <!-- innerRow -->
			</div> <!-- 4 -->
			<div class="col-sm-4">
				<div class="row m-b-5">
					<div class="col-sm-5"><b>Refund date </b></div>
					<div class="col-sm-7"> <input type="text" name="refund_date" id="refund_date" value="<?php echo date("d-m-Y"); ?>" class="datepicker"/></div>
				</div> <!-- innerRow -->
			</div> <!-- 4 -->
			<div class="col-sm-4">
				
			</div> <!-- 4 -->
		</div> <!-- ROW -->
		<div class="row" >
		   <div class="col-sm-4">
		       </div>
		       <div class="col-sm-4">
		           </div>
		   <div class="col-sm-4">
			<div class="row m-b-5">
					<div class="col-sm-5"></div>
					<div class="col-sm-7"><button class="btn-update" name="submit" value="Save" type="submit"><i class="fa fa-floppy-o"></i>  Pay</button>	
					</div>
		   </div>
		    </div>
		</div>
		</div>
		
		
		
		
		<div id="updated_payment_detail">
			  <?php if(!empty($form_data['field_name']))
			  { foreach ($form_data['field_name'] as $field_names) {
			  $tot_values= explode('_',$field_names);

			  ?>

				<div class="row m-b-5" id="branch"> 
					<div class="col-md-5">
						<strong><?php echo $tot_values[1];?><span class="star">*</span></strong>
					</div>
					<div class="col-md-7"> 
						  <input type="text" name="field_name[]" value="<?php echo $tot_values[0];?>" /><input type="hidden" value="<?php echo $tot_values[2];?>" name="field_id[]" />
						  <?php 
						  if(empty($tot_values[0]))
						  {
						  if(!empty($form_error)){ echo '<div class="text-danger">The '.strtolower($tot_values[1]).' field is required.</div>'; } 
						  }
						  ?>
					</div>
				</div>
				<?php } }?>
		</div> <!-- ROW -->
		
		
		
		
		
		<!-- ///////////////////////////////////[ ENDS HERE ]////////////////////////////////////// -->
		
		
		
	</div> <!-- userlist-left -->
	<div class="userlist-right relative">
		<div class="fixed">
					
                    <button type="button" onClick="return receive_now(1)" class="btn-anchor"><i class="fa fa-money"></i> Refund Now</button>
                    <button class="btn-exit" name="cancel" value="Close" type="button" onclick="window.location.href='<?php echo base_url('refund_payment'); ?>'"><i class="fa fa-sign-out"></i> Exit</button>
					
		</div>
	</div> <!-- userlist-right -->
  </form>
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>


  <script type="text/javascript">
          $(document).ready(function(){
   var Vals = $('#pay_mode :selected').val();
   pay_fields(Vals);
});         
</script>
         <script>

function pay_fields(value)
{
   $('#updated_payment_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('refund_payment/get_payment_mode_data')?>",
        data: {'payment_mode_id' : value},
       success: function(msg){
         $('#cheque').html(msg);

        }
    });
     
         $('.datepicker').datepicker({
                  format: "dd-mm-yyyy",
                  autoclose: true
                  });

           $('#cheque_date').datepicker({
            format: 'dd-mm-yyyy', 
            autoclose: true, 
          });

} 

function receive_now(vals)
{  
  if(vals==1)
  {
    $('#inner_table').slideDown();
    $('#pay_amount').val('');
    $('#bank_name').val('');
    $('#cheque_no').val('');
    $('#cheque_date').val('');
    $('#transection_no').val('');
  }
  else
  {
    $('#inner_table').attr('style','display:none');
  }$("#branch_hospital_form").on("submit", function(event) { 
     event.preventDefault();
     var Vals = $('#pay_mode :selected').val();
     $("#transaction_no_msg").html("");
     $("#cheque_no_msg").html("");
     $("#bank_name_msg").html("");
     $("#pay_amount_msg").html("");
        if($("#pay_amount").val()==''){
               receive_now(1);
               $("#pay_amount_msg").html("The amount is required");
          }
          if($("#pay_amount").val()!==''){
               $("#pay_amount_msg").html("");
               hospital_commission_final_step();
          }

         
     
     
  
}); 
function hospital_commission_final_step(){
     $.ajax({
          url: "<?php echo base_url('billing/hospital_commission'); ?>",
          type: "post",
          data: $('#branch_hospital_form').serialize(),
          success: function(result) 
          {
               var hospital_id = $('#hospital_id').val();
               var branch_id = $('#branch_id').val(); 
               hospital_comission(hospital_id);
               receive_now(0);
               flash_session_msg('Hospital commission successfully payed.');
          }
     });
}
}  


         </script>
          <script> 
            function payment_function(value,error_field){
                  $('#updated_payment_detail').html('');
                  $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('refund_payment/get_payment_mode_data')?>",
                  data: {'payment_mode_id' : value,'error_field':error_field},
                  success: function(msg){
                  $('#payment_detail').html(msg);
                  }
                  });


                  
            }
         

          function checkAlphaNumeric(e) {
               if ((e.keyCode >= 48 && e.keyCode <= 57) ||
                  (e.keyCode >= 65 && e.keyCode <= 90) ||
                  (e.keyCode >= 97 && e.keyCode <= 122)){
                    return true;
               }
               else{
                    return false;
               }
          }
        
          function isNumberKey(evt) {
               var charCode = (evt.which) ? evt.which : event.keyCode;
               if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
               } else {
                    return true;
               }      
          }
     
          $("#pay_form").on("submit", function(event) { 
               event.preventDefault(); 
               $('.overlay-loader').show();
               var ids = $('#data_id').val();
               var sub_branch_id = $('#sub_branch_id').val(); 
               var msg = 'payment successfully done.';
               var patient_id= $('#patient_id').val();
               var paid_date= $('#paid_date').val();
                var now = new Date(),
                now = now.getHours()+':'+now.getMinutes()+':'+now.getSeconds();
              // alert(paid_date);
               $.ajax({
                    url: "<?php echo current_url(); ?>",
                    type: "post",
                    data: $(this).serialize(),
                    success: function(result) 
                    {


                         if(!isNaN(result))
                         {
                              flash_session_msg(msg);
                              $('#load_add_pay_now_modal_popup').modal('hide');
                              get_balance_clearance_list(<?php echo $this->uri->segment('4'); ?>);
                              //print_bill(result,'<?php echo $this->uri->segment('5'); ?>');
                              
                              print_window_page('<?php echo base_url(); ?>balance_clearance/print_patient_balance_receipt/'+result+'/'+patient_id+'/'+paid_date+'/'+<?php echo $this->uri->segment('5'); ?>);
                         } 
                         else
                         {
                              $("#load_add_pay_now_modal_popup").html(result);
                         }
                         $('.overlay-loader').hide();       
                    }
               });
          }); 

           function print_bill(id,type)
           {
            alert();
              print_window_page('<?php echo base_url(); ?>balance_clearance/print_patient_balance_receipt/'+id+'/'+type);
             /* var printWindow = openPrintWindow('< ?php echo base_url(); ?>balance_clearance/print_patient_balance_receipt/'+id+'/'+type, 'windowTitle', 'width=820,height=600');
               var printAndClose = function() {
                  if (printWindow.document.readyState == 'complete') {
                      clearInterval(sched);
                      printWindow.print();
                      printWindow.close();
                  }
              }
              var sched = setInterval(printAndClose, 200);*/
           }

           /* function openPrintWindow(url, name, specs) 
            {
                 var printWindow =  window.open(url, name, specs);
                   var printAndClose = function() {
                       if (printWindow.document.readyState == 'complete') {
                           clearInterval(sched);
                           printWindow.print();
                           printWindow.close();
                       }
                   }
                   var sched = setInterval(printAndClose, 200);
             }*/
          </script>  
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
 

</script>   
</div><!----container-fluid--->
</body>
</html>