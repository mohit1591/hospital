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

 <!--new css-->
    <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

    <!--new css-->

<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('415',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 

    /*table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true,
        "searching": false,
        "bLengthChange": false , 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('day_care/running_bill_info_ajax')?>",
            "type": "POST",
            "data": function(d){
                d.opd_id = getUrlData('opd_id');
                d.patient_id = getUrlData('patient_id');
                return d;

            },
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });*/
}); 
<?php } ?>




function reload_table()
{

    table.ajax.reload(null,false); //reload datatable ajax 
}


 
function checkboxValues() 
{         
    $('#table').dataTable();
     var allVals = [];
     $(':checkbox').each(function() 
     {
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
     });
     allbranch_delete(allVals);
} 
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
    	 
    <form>
    
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
              <td><?php
              if($payment->end_date=='0000-00-00 00:00:00')
              {
                
              }
              else
              {
                 // echo date('d-m-Y',strtotime($payment->end_date));
              }

                 ?>
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

        $grand_total_amount= $total_amount+$reg_charge; 

        if(isset($grand_total_amount) && isset($reg_charge)){
          $balance= $grand_total_amount-$reg_charge;
        }else{
          $balance= $grand_total_amount-$reg_charge;
        }
 
       ?>
        <div class="sale_medicine_bottom">
        <div class="row">
          <div class="col-sm-7 text-right">

      <a type="submit" class="btn-custom" href="<?php echo base_url('opd_charge_entry/add/'.$opd_id.'/'.$patient_id);?>"><i class="fa fa-pencil"></i> Edit Charges</a>

       <a type="submit" class="btn-custom" href="<?php echo base_url('day_care/generate_discharge_bill/'.$opd_id.'/'.$patient_id);?>"><i class="fa fa-database"></i> Generate Bill</a>

          </div>
        </div>

  

        <div class="left">
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
                    <input type="text" id="total_net_amount" value="<?php if(isset($grand_total_amount)) {echo number_format($grand_total_amount,2);}?>"  readonly="" />
                </div>
               <div class="sale_medicine_mod_of_payment">
                 <label>Received</label>
                  <input type="text" id="total_paid_amount" value="<?php if(isset($net_reg_data[0])){echo $net_reg_data[0];}?>"  readonly="" />
                </div>
                <div class="sale_medicine_mod_of_payment">
                    <label>Balance</label>
                    <input type="text" id="total_balance" value="<?php if(isset($balance)){echo number_format($balance,2);}?>"  readonly="" />
                </div>
                

            </div> <!-- right_box -->

            
        </div> <!-- left -->
      
    </div> <!-- sale_medicine_bottom -->
    </form>


   </div> <!-- close -->
   <div class="userlist-right">
  		<div class="btns">
         <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("day_care/print_running_bill/".$opd_id.'/'.$patient_id); ?>');"> <i class="fa fa-print"></i> Print</a>
               
               <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("day_care/print_letterhead_running_bill/".$opd_id.'/'.$patient_id); ?>');"> <i class="fa fa-print"></i>Letterhead Print</a>
             
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
                  window.location.href="<?php echo base_url('day_care/running_bill_info');?>?opd_id="+'<?php echo $opd_id ?>'+'&patient_id='+'<?php echo $patient_id ?>' 
                 }
              });
    }); 

 }
</script>

<div id="load_end_now_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

