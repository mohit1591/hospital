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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>



<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.min.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>locales/bootstrap-datetimepicker.fr.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
 <!--new css-->
<!--     
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script> -->
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
rel = "stylesheet">
    <!--new css-->

<script type="text/javascript">
function getUrlData(name) { 
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( window.location.href );
    if( results == null )
          return "";
   else
      return results[1];
}

</script>




</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">

 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo '<script> flash_session_msg("'.$flash_success.'");</script> ';
   ?>
   <script>  
    //$('.dlt-modal').modal('show'); 
    </script> 
    <?php
 }
?>
    <div class="userlist-box">
   
    <div class="row m-b-5">
    <div class="col-xs-12">
      <div class="row">
        <div class="col-xs-6">
        </div>
        <div class="col-xs-6"></div>
      </div>
    </div>
  </div>
    	 
  <form action="<?php echo current_url(); ?>" method="post">
     <input type="hidden" value="<?php echo $opd_id; ?>" name="opd_id" />
  <input type="hidden" value="<?php echo $patient_id; ?>" name="patient_id"/>
  
   <input type="hidden" value="<?php echo $day_care_details['referral_doctor']; ?>" name="referral_doctor"/>
    <input type="hidden" value="<?php echo $day_care_details['referral_hospital']; ?>" name="referral_hospital"/>






     <div class="row m-b-5">
    <div class="col-xs-12">
      <div class="row">
        <div class="col-xs-3">
        <label>Discharge Bill NO.</label>
           <input type="text" value="<?php echo $form_data['discharge_bill_no'];?>" name="discharge_bill_no" readonly/>
        
        </div>
        <div class="col-xs-3">
          <label>Discharge Date</label>
           <input type="text" value="<?php echo $form_data['discharge_date'];?>" name="discharge_date" readonly class="datepicker date" data-date-format="dd-mm-yyyy HH:ii"/>
            

        </div>

        
      </div>
    </div>
  </div>
  
  
  
  <table style="margin-bottom:10px;">
  <tr>
    <th style="text-align:left;" width="10%">Particular </th>
    <td style="text-align:left;" width="10%"><input type="text" id="particular" name="particular" class="w-300px  particular_val alpha_numeric_space inputFocus"/><input type="hidden" id="particular_id" name="particular_id" class="w-100px  particular_val alpha_numeric_space inputFocus"/></td>
    
    
    <th style="text-align:left;padding-left:20px;" > </th>
    <td style="text-align:left;" >
        
          </td>
    <th style="text-align:left;padding-left:20px;" width="10%">Date </th>
    <td style="text-align:left;" width="10%"><input type="text" id="date" name="date" value="<?php echo $form_data['particular_date']?>" class="w-100px particular_datepicker" /></td>
    <th style="text-align:left;padding-left:20px;" width="10%">Rate </th>
    <td style="text-align:left;" width="10%"><input type="text" id="charge"  name="rate" class="w-100px price_float"/></td>
    <th style="text-align:left;padding-left:20px;" width="5%">Qty. </th>
    <td style="text-align:left;" width="5%"><input type="text" id="qty" name=qty"" value="0.00" class="w-50px numeric" onblur="payemt_vals(this.value);"/></td>
    <th style="text-align:left;padding-left:20px;" width="10%">Amount </th>
    <td style="text-align:left;" width="10%"><input type="text" name="amount" id="amount" value="0.00" class="w-100px price_float" readonly/></td>
    <th style="text-align:left;" width="5%"></th>
    <td style="text-align:left;" width="10%"><a href="#" class="btn-custom" onclick="add_perticulars();">Add</a></td>
    </tr>
    </table>
    
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered table-responsive medicine_stock_list" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>Sr No.</th>
                <th>Particular</th>
                <th>Date</th>
                <th>Rate</th>
                <th>Qty</th>
                <th>Amount</th>
                <th>Action</th>
              </tr>
            </thead>  
            <tbody>
            <?php $i=1;
            $k=0;
            $total_amount=0;
            array_sort_by_column($running_bill_data['CHARGES'], 'start_date');
            if(!empty($running_bill_data['CHARGES']))
            {
                $last = end($running_bill_data['CHARGES']);
               $last = current(array_slice($running_bill_data['CHARGES'], -1));
              $date_array=array();
              //echo '<pre>'; print_r($running_bill_data['CHARGES']);die;
              $date_array=array();
            foreach($running_bill_data['CHARGES'] as $running_data)
              {

                ?>
            <tr>
              <td><?php echo $i; ?></td>
              <td><?php echo $running_data['particular'];?></td>
              <td><?php echo date('d-m-Y',strtotime($running_data['start_date'])); ?></td>
              
              <td><?php echo  $running_data['price']; ?></td>
              <td><?php echo  number_format($running_data['quantity'],0); ?></td>
              <td>
                  <?php 
                     $total_amount= $total_amount+$running_data['net_price'];

                     echo  $running_data['net_price']; 
                  ?> 
              </td>
              <td>
              <input type="hidden"  id="start_date_<?php echo $running_data['id'];?>" value="<?php echo date('d-m-Y',strtotime($running_data['start_date']));?>" />
             
             <?php if($running_data['type']==5){?>
              <button type ="button" class="btn-custom" id="deleteAll" onclick="return delete_charges('<?php echo $running_data['id'];?>');">
              <i class="fa fa-trash"></i> Delete
             </button>
             <?php }?>
             </td>
            </tr>
            <?php 
            $i++;
            $k++;
          }
            }?>

            <?php $net_reg_data=array();
            if(!empty($running_bill_data['advance_payment']))
            {
            foreach($running_bill_data['advance_payment'] as $payment)
              {?>
            <tr style="font-weight: bold;"><td><?php echo $i; ?></td>
              <td><?php echo "Registration Charge" ; //echo $payment->particular ?></td>
              <td><?php echo date('d-m-Y',strtotime($payment->start_date)); ?></td>
              <td>
              </td>

              
              <td><?php echo $payment->quantity; ?></td>
              <td><?php $net_reg_data[]= $payment->net_price;
              echo $reg_charge =  $payment->net_price; ?></td>
              <td></td>
            </tr>
            <?php } } ?>
            
            </tbody>
        </table>
        <?php 
        $total_bill_amount =$total_amount;
        $net_total_amount= $total_bill_amount+$reg_charge; 
        if(!empty($get_paid_amount))
        {
            $balance= $net_total_amount-$get_paid_amount;
        }
        else
        {
           $balance= $net_total_amount; 
        }
        
        if(!empty($total_discount))
        {
           $net_total_amount= $net_total_amount-$total_discount; 
           $balance= $net_total_amount-$get_paid_amount;
           $net_amount = $net_total_amount;
        }
        else
        {
            $net_amount = $net_total_amount;
            $net_total_amount= $net_total_amount; 
            $balance= $net_total_amount; 
        }
        


 
       ?>
        <div class="sale_medicine_bottom" >
        <div class="row">
          <div class="col-sm-12 text-right">

      <a type="submit" class="btn-custom" href="<?php echo base_url('opd_charge_entry/add/'.$opd_id.'/'.$patient_id);?>"><i class="fa fa-pencil"></i> Edit Charges</a>&nbsp;


        <button type="submit" class="btn-custom" style="float: right;"> <i class="fa fa-database"></i> Generate Bill</button>

          </div>
        </div>
        <div class="left" style="width: 100%;">
            <div class="right_box">


                <div class="sale_medicine_mod_of_payment">
                    
                      <label>Mode of Payment <span class="star">*</span></label>
                    
                      <select  name="payment_mode" onChange="payment_function(this.value,'');">
                      <?php foreach($payment_mode as $payment_mode) 
                      {?>
                      <option value="<?php echo $payment_mode->id;?>" <?php if($form_data['payment_mode']== $payment_mode->id){ echo 'selected';}?>><?php echo $payment_mode->payment_mode;?></option>
                      <?php }?>

                      </select>

                   
                </div>

              <div class="sale_medicine_mod_of_payment">
                    <label>Total Amount</label>
                      <input type="text" id="total_amount_bill" value="<?php if(isset($total_bill_amount)){ echo number_format($total_bill_amount,2,'.',''); }?>" name="total_amount" readonly="" class="price_float"/>
                </div>
               <div class="sale_medicine_mod_of_payment">
                 <label>Registration Charge</label>
                  <input type="text" id="total_reg_amount" value="<?php if(isset($net_reg_data[0])){echo $net_reg_data[0];}?>"  readonly="" />
                </div>

                
                <div class="sale_medicine_mod_of_payment">
                    
                      <label>Discount (Rs.)</label>
                    
                      <input type="text" id="total_discount" name="total_discount" value="<?php echo $total_discount; ?>"  onkeyup="check_paid_amount();payemt_vals_new();" class="price_float"/>
                  
                </div>
                <div class="sale_medicine_mod_of_payment"><label>Net Amount</label>
                      <input type="text" id="net_amount" value="<?php if(isset($net_amount)){ echo number_format($net_amount,2,'.',''); }?>" name="total_net_amount" readonly="" class="price_float"/>
                  
                </div>

                 <div class="sale_medicine_mod_of_payment"><label>Paid Amount</label>
                    <input type="text" onchange="check_paid_amount();" id="total_paid_amount" name="total_paid_amount" value="<?php if(isset($get_paid_amount)){echo $get_paid_amount;}?>"  class="price_float"/>
                   
                </div>

                <div class="sale_medicine_mod_of_payment">
                    <label>Balance</label>
                    <input type="text" id="total_balance" value="<?php if(isset($balance)){echo number_format($balance,2);}?>" name="total_balance"  readonly="" />
                </div>
                

            </div> <!-- right_box -->

            
        </div> <!-- left -->
      
    </div> <!-- sale_medicine_bottom -->
    </form>


   </div> <!-- close -->
   <div class="userlist-right">
  		<div class="btns">
         <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("day_care/print_running_bill/".$opd_id.'/'.$patient_id); ?>');"> <i class="fa fa-print"></i> Print</a>
               
              <!-- <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('< ?php echo base_url("day_care/print_letterhead_running_bill/".$opd_id.'/'.$patient_id); ?>');"> <i class="fa fa-print"></i>Letterhead Print</a>-->
             
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url('day_care');?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
  		</div>
  	</div> 

  	<!-- right -->
 
  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

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

<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_allot_to_branch_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</div>
<script>

</script>
<!-- container-fluid -->
</body>
</html>

<script>
function check_paid_amount()
  {

    var total_amount = $('#total_amount_bill').val();
    var discount = $('#total_discount').val();
    var paid_amount = $('#total_paid_amount').val();
    var net_amount = $('#net_amount').val();
    
    if(parseFloat(discount)>parseFloat(total_amount))
    {
      alert('Discount amount can not be greater than total amount');
      $('#total_discount').val('0.00');
      return false;
    }
    if(parseFloat(paid_amount)>parseFloat(net_amount))
    {
      alert('Paid amount can not be greater than total amount');
      $('#total_paid_amount').val('0.00');
      return false;
    }
    payment_calc_all();
  }


  
$(".datepicker").datetimepicker({
        format: "dd-mm-yyyy HH:ii P",
        showMeridian: true,
        autoclose: true,
        todayBtn: true
    });

$('.datepickerewe').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    $('.datepicker1').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
    $('.datepicker2').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0
    });

  function payemt_vals_new()
  {

    var timerA = setInterval(function(){  
    payment_calc_all();
    clearInterval(timerA); 
    }, 80);
  }

  function payment_calc_all()
  {
        var discount = $('#total_discount').val();
        var total_amount= $('#total_amount_bill').val();
        var net_amount = $('#net_amount').val();
        var total_paid_amount= $('#total_paid_amount').val();
        var total_reg_amount= $('#total_reg_amount').val();
        
        $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>day_care/payment_calc_all/", 
        dataType: "json",
        data: 'discount='+discount+'&total_reg_amount='+total_reg_amount+'&net_amount='+net_amount+'&pay_amount='+total_paid_amount+'&total_amount='+total_amount+'&opd_id='+'<?php echo $opd_id; ?>'+'&patient_id='+'<?php echo $patient_id; ?>',
      success: function(result)
      {
            
            $('#total_discount').val(result.discount_amount);
            $('#net_amount').val(result.net_amount);
            $('#total_paid_amount').val(result.pay_amount);
            
            $('#total_discount').val(result.discount);
            $('#total_balance').val(result.balance_due);
            $('#total_amount_bill').val(result.total_amount_bill);
            
      } 
    });
    }


function my_func(data_id){
  var $modal = $('#load_end_now_modal_popup');
var data_id= $('#end_now_add_modal_'+data_id).val();
var start_date = $('#start_date_'+data_id).val();
var opd_id= '<?php echo $opd_id;?>';
var patient_id= '<?php echo $patient_id;?>';
//alert('<?php echo base_url().'day_care/end_now/' ?>'+data_id+'/'+start_date+'/'+opd_id+'/'+patient_id);
$modal.load('<?php echo base_url().'day_care/end_now/' ?>'+data_id+'/'+start_date+'/'+opd_id+'/'+patient_id,
{

  },
function(){
$modal.modal('show');
});



}

function add_perticulars()
  {
    var particular= $('#particular').val();
    var particular_id= $('#particular_id').val();
    
    var date= $('#date').val();
    var charge= $('#charge').val();
    var qty= $('#qty').val();
    var discharge_date= $('#discharge_date').val();
    var amount= $('#amount').val();
    
    var doctor_id = $('#doctor').val();
    var doctor = $('#doctor option:selected').text();
    
     $.ajax({
        url : "<?php echo base_url('day_care/add_perticulars/'); ?>"+ '<?php echo $opd_id ?>'+'/'+'<?php echo $patient_id;?>'+'/'+'<?php echo strtotime($form_data['discharge_date']);?>',
        dataType: "json",
        method: 'post',
        data: {
        particular: particular,
        particular_id: particular_id,
        date : date,
        charge : charge,
        qty : qty,
        amount : amount,
        doctor_id : doctor_id,
        doctor : doctor
      },
        success: function( data ) {
          //if(data==1)
          //{
          if(data.success==1)
            { //alert(data.success); day_care/running_bill_info/28/792908
              window.location.href='<?php echo base_url('day_care/running_bill_info') ?>/'+data.opd_id+'/'+data.patient_id+'/'+data.discharge_date;
             
          }
        }
     });

  }

 function delete_charges(id)
 {    
   
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('day_care/delete/'); ?>"+id, 
                 success: function(result)
                 {
                  window.location.href="<?php echo base_url('day_care/running_bill_info');?>/"+'<?php echo $opd_id ?>'+'/'+'<?php echo $patient_id ?>' 
                 }
              });
    }); 

 }

 $('documnet').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print'){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
      window.location.href='<?php echo base_url('day_care');?>';
    }) ;
   
       
  <?php }?>
  
  <?php if(isset($_GET['billstatus']) && $_GET['billstatus']=='printbill'){?>
  $('#confirm_print_bill').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('day_care');?>'; 
    }) ;
   
       
  <?php }?>
 });
 
 
 $(function () {
  payment_calc_all();
 
    var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('day_care/get_particular_data/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
         
         row_num : row
      },
       success: function( data ) {
         response( $.map( data, function( item ) {
          var code = item.split("|");
          return {
            label: code[0],
            value: code[0],
            data : item
          }
        }));
      }
      });

       
    };

    var selectItem = function (event, ui) {
        //$(".medicine_val").val(ui.item.value);

        var names = ui.item.data.split("|");

        $('.particular_val').val(names[0]);
        $('#charge').val(names[1]);
        $('#particular_id').val(names[2]);
          

        return false;
    }

    $(".particular_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
    });
    
    function payemt_vals(quantity)
  {
      var timerA = setInterval(function(){  
      get_total_amount(quantity);
      clearInterval(timerA); 
      }, 80);
  }
  
  function get_total_amount(quantity){

  var amount= $('#charge').val();
  var total_amount= amount *quantity;
  $('#amount').val(total_amount);

}
 $('.particular_datepicker').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true
                }); 
</script>


<!-- Confirmation Box  end -->
  <!--    <div id="confirm_print_bill" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <div class="modal-footer">
             <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("day_care/print_discharge_bill_reciept/".$opd_id.'/'.$patient_id); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>  -->
<!-- Confirmation Box for bill paid amount end -->

     <div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>

          <div class="modal-footer">
            <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("day_care/print_discharge_bill/".$opd_id.'/'.$patient_id); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 

<div id="load_end_now_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_allot_to_branch_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

