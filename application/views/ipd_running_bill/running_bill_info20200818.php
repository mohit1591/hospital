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
 
         <!-- <input type="radio" name="types" < ?php if($type==2){ echo 'checked=""'; } ?> class="types" value="2" onclick="return type_set(2)" /> Sale  -->

         <!--  <input type="radio" name="types" < ?php if($type==3){ echo 'checked=""'; } ?> class="types" value="3" onclick="return type_set(3)" /> Manage Kit Quantity  Branch Allotment-->
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
                <th>Start Date</th>
                <th>End Date</th>
                <th>Rate</th>
                <th>Qty</th>
                <th>Amount</th>
                <th>Action</th>
              </tr>
            </thead>  
            <tbody>
            <?php $i=1;
            $k=0;
            $received_amount=0;
 array_sort_by_column($running_bill_data['CHARGES'], 'start_date');
            if(!empty($running_bill_data['CHARGES']))
            {
                $last = end($running_bill_data['CHARGES']);
               $last = current(array_slice($running_bill_data['CHARGES'], -1));
              // echo '<pre>'; print_r($last);die;
              $date_array=array();
              //echo '<pre>'; print_r($running_bill_data['CHARGES']);die;
              $date_array=array();
            foreach($running_bill_data['CHARGES'] as $running_data)
              {

                ?>
            <tr>
              <td><?php echo $i; ?></td>
              <td><?php echo $running_data['particular'];?></td>
              <td><?php echo date('d-m-Y H:i a',strtotime($running_data['start_date'])); ?></td>
              <td>
              <?php
              if($running_data['end_date']!='')
              {
                 /*if($running_data['type']==3 && ($running_data['end_date']>= date('Y-m-d') || $running_data['start_date']>= date('Y-m-d')))
                 {*/
                 if($running_data['type']==3 && ($running_data['end_date']>= date('Y-m-d') && $running_data['start_date']>= date('Y-m-d')) && $last['end_date']==$running_data['end_date'])
                 {
                     if($running_data['end_date']== date('Y-m-d') && $running_data['transfer_status']=='2')
                     {
                         echo date('d-m-Y H:i a',strtotime($running_data['start_date'])); 
                     }
                     else
                     {
                         echo '<font color="green"><b>Running</b></font>';
                     }
                  
                 }
                 else
                 {
                   echo date('d-m-Y H:i a',strtotime($running_data['start_date']));
                 }
                 
              }
              else
              {

                echo date('d-m-Y H:i a',strtotime($running_data['start_date']));
              }
              ?> 
              </td>
              
              <td><?php echo  $running_data['price']; ?></td>
              <td><?php echo  $running_data['quantity']; ?></td>
              <td>
                  <?php 
                        $received_amount= $received_amount+$running_data['net_price'];
                        echo  $running_data['net_price']; 
                  ?> 
              </td>
              <td>
              <input type="hidden"  id="start_date_<?php echo $running_data['id'];?>" value="<?php echo date('d-m-Y',strtotime($running_data['start_date']));?>" />
              <?php
              if($running_data['type']==3)
              {
              ?>
               <button type="button" id="end_now_add_modal_<?php echo $running_data['id'];?>" class="btn-custom" value="<?php echo $running_data['id']; ?>" onclick="my_func('<?php echo $running_data['id'];?>');">
              </i>End Date
             </button>
              <?php  
              }
              ?>
              
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

<!--ipd charge entry -->
   <?php /* $charge_entry_amount=0;
            if(!empty($running_bill_data['particluar_charge_entry']))
            {
            foreach($running_bill_data['particluar_charge_entry'] as $cahrge_entry)
              {?>
            <tr ><td><?php echo $i; ?></td>
              <td><?php echo $cahrge_entry->particular;?></td>
              <td><?php  echo date('d-m-Y',strtotime($cahrge_entry->start_date)); ?></td>
              <td><?php
              if($cahrge_entry->start_date=='0000-00-00 00:00:00')
              {
                
              }
              else
              {

                echo date('d-m-Y',strtotime($cahrge_entry->start_date));
              }

                 ?></td>
               <td><?php $charge_entry_amount+= $cahrge_entry->net_price;
              echo  $cahrge_entry->net_price; ?></td>
              <td><?php echo  $cahrge_entry->quantity; ?></td>
              <td><?php 
              echo  $cahrge_entry->net_price; ?></td>
              <td></td></tr>
            <?php } }     $received_amount= $received_amount + $charge_entry_amount; */ ?>

            <!-- ipd charge entry -->

            <?php $net_medicne_data=0;
            if(!empty($running_bill_data['medicine_payment']))
            {
            foreach($running_bill_data['medicine_payment'] as $medicinepayment)
              {?>
                    <tr><td><?php echo $i; ?></td>
                    <td><?php echo $medicinepayment->particular ?></td>
                    <td><?php echo date('d-m-Y',strtotime($medicinepayment->start_date)); ?></td>

                    <td><?php echo date('d-m-Y',strtotime($medicinepayment->start_date)); ?></td>
                    <td><?php echo $medicinepayment->net_price; ?></td>
                    <td><?php echo  $medicinepayment->quantity; ?></td>
                    <td><?php $net_medicne_data= $net_medicne_data+$medicinepayment->net_price;
                    echo  $medicinepayment->net_price; ?></td>
                    <td>&nbsp;</td>
                    </tr>
            <?php 
            $i++;
            } 
          } 
            $received_amount = $received_amount+$net_medicne_data;

            ?>

            <?php 
            $net_pathology_data=0;
            if(!empty($running_bill_data['pathology_payment']))
            {
            foreach($running_bill_data['pathology_payment'] as $payment)
              {?>
                <tr><td><?php echo $i; ?></td>
                <td><?php echo $payment->particular ?></td>
                <td><?php echo date('d-m-Y',strtotime($payment->start_date)); ?></td>

                <td><?php echo date('d-m-Y',strtotime($payment->start_date)); ?></td>
                <td><?php echo $payment->net_price; ?></td>
                <td><?php echo  $payment->quantity; ?></td>
                <td><?php $net_pathology_data= $net_pathology_data+$payment->net_price;
                echo  $payment->net_price; ?></td>
                <td>&nbsp;</td>
                </tr>
            <?php $i++;} 

          } 

            $received_amount = $received_amount+$net_pathology_data;
            //print_r($running_bill_data['advance_payment']);
            ?>



            <?php $net_advance_data=array();
            if(!empty($running_bill_data['advance_payment']))
            {
            foreach($running_bill_data['advance_payment'] as $payment)
              {?>
            <tr style="font-weight: bold;"><td><?php echo $i; ?></td>
              <td><?php echo $payment->particular ?></td>
              <td><?php echo 'Up to '.date('d-m-Y');; ?></td>
              <td><?php
              if($payment->end_date=='0000-00-00 00:00:00')
              {
                
              }
              else
              {

                echo date('d-m-Y',strtotime($payment->end_date));
              }

                 ?></td>
                 <td></td>
              <td><?php echo  $payment->quantity; ?></td>
              <td><?php $net_advance_data[]= $payment->net_price;
              echo  $payment->net_price; ?></td>
              <td></td></tr>
            <?php } } ?>
            
            </tbody>
        </table>
        <?php 
        if(isset($received_amount) && isset($net_advance_data[0])){
          $balance= $received_amount-$net_advance_data[0];
        }else{
          $balance=$received_amount;
        }
        

        //print_r($received_amount); ?>
        <div class="sale_medicine_bottom">
        <div class="row">
          <div class="col-sm-7 text-right">

      <a type="submit" class="btn-custom" href="<?php echo base_url('ipd_charge_entry/add/'.$ipd_id.'/'.$patient_id);?>"><i class="fa fa-pencil"></i> Edit Charges</a>
          </div>
        </div>
        <div class="left">
            <div class="right_box">
                <div class="sale_medicine_mod_of_payment">
                    <label>Total Amount</label>
                    <input type="text" id="total_net_amount" value="<?php if(isset($received_amount)) {echo $received_amount;}?>"  readonly="" />
                </div>
               <div class="sale_medicine_mod_of_payment">
                 <label>Received</label>
                  <input type="text" id="total_paid_amount" value="<?php if(isset($net_advance_data[0])){echo $net_advance_data[0];}?>"  readonly="" />
                </div>
                <div class="sale_medicine_mod_of_payment">
                    <label>Balance</label>
                    <input type="text" id="total_balance" value="<?php if(isset($balance)){echo $balance;}?>"  readonly="" />
                </div>
                

            </div> <!-- right_box -->

            
        </div> <!-- left -->
      
    </div> <!-- sale_medicine_bottom -->
    </form>


   </div> <!-- close -->
   <div class="userlist-right">
  		<div class="btns">
         <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("ipd_running_bill/print_running_bill/".$ipd_id.'/'.$patient_id); ?>');"> <i class="fa fa-print"></i> Print</a>
               
             
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url('ipd_running_bill');?>'">
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
var ipd_id= '<?php echo $ipd_id;?>';
var patient_id= '<?php echo $patient_id;?>';
$modal.load('<?php echo base_url().'ipd_running_bill/end_now/' ?>'+data_id+'/'+start_date+'/'+ipd_id+'/'+patient_id,
{

  //'id1': '1',
  //'id2': '2'
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
                 url: "<?php echo base_url('ipd_running_bill/delete/'); ?>"+id, 
                 success: function(result)
                 {
                  //http://192.168.1.240/hmas/ipd_running_bill/running_bill_info?ipd_id=50&patient_id=130
                  //alert("<?php echo base_url('ipd_running_bill/running_bill_info');?>?ipd_id="+'<?php echo $ipd_id ?>'+'&patient_id='+'<?php echo $patient_id ?>');
                  window.location.href="<?php echo base_url('ipd_running_bill/running_bill_info');?>?ipd_id="+'<?php echo $ipd_id ?>'+'&patient_id='+'<?php echo $patient_id ?>' 
                 }
              });
    });     
 }
</script>

<div id="load_end_now_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>

