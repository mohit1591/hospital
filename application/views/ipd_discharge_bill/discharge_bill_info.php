<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>  

 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.min.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>locales/bootstrap-datetimepicker.fr.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<!-- bootstrap -->
<!-- <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">


<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">


<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script> 


   <link rel="stylesheet" type="text/css" href="http://192.168.1.240/hmas/assets/css/bootstrap-datetimepicker.css">
<link rel="stylesheet" type="text/css" href="http://192.168.1.240/hmas/assets/css/bootstrap-datetimepicker.min.css">
<script type="text/javascript" src="http://192.168.1.240/hmas/assets/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="http://192.168.1.240/hmas/assets/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="http://192.168.1.240/hmas/assets/js/locales/bootstrap-datetimepicker.fr.js"></script>
 <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> -->
    <!--new css-->
 <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('415',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 

    var today =new Date();
    $('.particular_datepicker').datepicker({
    dateFormat: 'dd-mm-yy',
    minDate : today,
    onSelect: function (selected) {
 
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 1);
        }
    }) 

    /*table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true,
        "searching": false,
        "bLengthChange": false , 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('ipd_running_bill/running_bill_info_ajax')?>",
            "type": "POST",
            "data": function(d){
                d.ipd_id = getUrlData('ipd_id');
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


 
/*function checkboxValues() 
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
} */
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
   <form action="<?php echo current_url(); ?>" method="post">
    <div class="row m-b-5">
    <div class="col-xs-12">
      <div class="row">
        <div class="col-xs-3">
        <label>Discharge Bill NO.</label>
           <input type="text" value="<?php echo $form_data['discharge_bill_no'];?>" name="discharge_bill_no" readonly/>
         <!-- <input type="radio" name="types" < ?php if($type==2){ echo 'checked=""'; } ?> class="types" value="2" onclick="return type_set(2)" /> Sale  -->

         <!--  <input type="radio" name="types" < ?php if($type==3){ echo 'checked=""'; } ?> class="types" value="3" onclick="return type_set(3)" /> Manage Kit Quantity  Branch Allotment-->
        </div>
        <div class="col-xs-3">
          <label>Discharge Date</label>
           <input type="text" value="<?php echo $form_data['discharge_date'];?>" id="discharge_date" name="discharge_date" readonly class="datepicker date" data-date-format="dd-mm-yyyy HH:ii"/>
            

        </div>
        
        <div class="col-xs-3">
          <label>Next Appointment Date</label>
           <input type="text" id="next_app_date" name="next_app_date" value="<?php echo $form_data['next_app_date']?>" class="particular_datepicker" data-date-format="dd-mm-yyyy" />
            

        </div>

        
      </div>
    </div>
  </div>
  <input type="hidden" value="<?php echo $ipd_id; ?>" name="ipd_id" />
  <input type="hidden" value="<?php echo $patient_id; ?>" name="patient_id"/>
  <table style="margin-bottom:10px;">
  <tr>
    <th style="text-align:left;" width="10%">Particular </th>
    <td style="text-align:left;" width="10%"><input type="text" id="particular" name="particular" class="w-100px  particular_val alpha_numeric_space inputFocus"/><input type="hidden" id="particular_id" name="particular_id" class="w-100px  particular_val alpha_numeric_space inputFocus"/></td>
    
    
    <th style="text-align:left;padding-left:20px;" width="10%">Doctor </th>
    <td style="text-align:left;" width="10%">
        <select name="doctor" id="doctor" class="">
            <option value="">Select Doctor</option>
            <?php
            if(!empty($doctor_list))
            {
              
              foreach($doctor_list as $doctorlist)
              {
               
                echo '<option value="'.$doctorlist->id.'" '.$selected_particulars.'>'.$doctorlist->doctor_name.'</option>';
              }
            }
            ?> 
          </select> 
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
                  <th><input onclick="selectall();" type="checkbox" name="selectall" class="" id="selectAll" value=""></th>
                <th>SrNo.</th>
                <th>Particular</th>
                <th>Date</th>
                <th>Rate</th>
                <th>Qty</th>
                <th>Amount</th>
                <th>Action</th>
              </tr>
            </thead>  
            <tbody>
            <?php 
            //print_r($running_bill_data);
            $k=0;
            $i=1;
            $received_amount=0;
            $total_amount=0;
             array_sort_by_column($running_bill_data['CHARGES'], 'start_date');
            if(!empty($running_bill_data['CHARGES']))
            {

             //print '<pre>'; print_r($running_bill_data['CHARGES']);
 
            foreach($running_bill_data['CHARGES'] as $running_data)
              {
                  if(!empty($running_data['doctor_name']))
                  {
                      $doctor_name = ' ('.$running_data['doctor_name'].')';
                  }
                  else
                  {
                      $doctor_name='';
                  }
                
                ?>
              
            <tr>
                <td><input type="checkbox" name="dib_ids[]" class="checklist" value="<?php echo $running_data['id'];?>"></td>
              <td><?php echo $i; ?></td>
              <td><?php echo $running_data['particular'].$doctor_name;?></td>
              <td><?php echo date('d-m-Y H:i a',strtotime($running_data['start_date'])); ?></td>
              <td>
              <?php //if($running_data['type']==5)
                //{
                ?>
                 <input type="text" name="price" id="price_edit_<?php echo $running_data['id'];?>" value="<?php echo  $running_data['price']; ?>" onkeyup="update_row('<?php echo $running_data['id'];?>');payment_calculate('<?php echo $running_data['id'];?>');"/>
               <?php 
               // }

                //else
               // { 

                  //echo  $running_data['price'];
                //}?>
            <input type="hidden" name="price" id="price_edit_<?php echo $running_data['id'];?>" value="<?php echo  $running_data['price']; ?>" onkeyup="update_row('<?php echo $running_data['id'];?>');payment_calculate('<?php echo $running_data['id'];?>');"/>

              </td>
               <?php if($running_data['type']==5)
                {
                ?>
                <td><input type="text" name="qty" value="<?php echo  $running_data['quantity']; ?>" id="qty_edit_<?php echo $running_data['id'];?>" onkeyup="update_row('<?php echo $running_data['id'];?>');payment_calculate('<?php echo $running_data['id'];?>');"/> </td>

              <?php 
                }
                else
                {?>
               <td><input type="hidden" name="qty" value="<?php echo $running_data['quantity']; ?>" id="qty_edit_<?php echo $running_data['id'];?>" onkeyup="update_row('<?php echo $running_data['id'];?>');payment_calculate('<?php echo $running_data['id'];?>');"/><?php echo  $running_data['quantity']; ?></td>

                <?php 
                }
                ?>
             <?php if($running_data['type']==5)
                {
                   $add_attr="readonly";
                   $onkey_up_attr='';
                }
                else
                {
                  $add_attr="";
                  $onkey_up_attr='onkeyup=update_row('.$running_data['id'].')';
                }
                ?>

              <td><input type="text" name="net_price" <?php echo 'readonly'; //if($running_data['type']==7){ echo 'readonly'; } ?> value="<?php echo  $running_data['net_price']; ?>" id="net_price_edit_<?php echo $running_data['id'];?>" <?php echo $onkey_up_attr;?> <?php echo $add_attr;?>/> <?php $received_amount= $received_amount+$running_data['net_price'];
               $total_amount=$total_amount+ $running_data['net_price'];
              ?></td>

              <td><a class="btn-custom" onclick="return delete_charges('<?php echo $running_data['id'];?>','<?php echo $ipd_id;?>','<?php echo $patient_id;?>')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a></td>

             
            </tr>
            <?php 
            $i++;
            $k++;
          }
            } // echo  $total_amount;?>

             <?php $net_medicne_data=0;
            if(!empty($running_bill_data['medicine_payment']))
            {
            foreach($running_bill_data['medicine_payment'] as $payment)
              {?>
            <tr><td>&nbsp;</td><td><?php echo $i; ?></td>
              <td><?php echo $payment->particular ?></td>
              <td><?php echo date('d-m-Y',strtotime($payment->start_date)); ?></td>
              
                 <td></td>
              <td><?php echo  $payment->quantity; ?></td>
              <td><?php $net_medicne_data= $net_medicne_data+$payment->net_price;
              echo  $payment->net_price; ?></td>
              <td>&nbsp;<!-- <a class="btn-custom" onclick="return edit_advance_payment('< ?php echo $ipd_id;?>','< ?php echo $patient_id;?>')" href="javascript:void(0)" title="Edit" data-url="512" ><i class="fa fa-pencil"></i>Edit</a> --></td>
              </tr>
            <?php $i++;} } ?>

            <?php $net_pathology_data=0;
            if(!empty($running_bill_data['pathology_payment']))
            {
            foreach($running_bill_data['pathology_payment'] as $payment)
              {?>
            <tr>
                <td><input type="checkbox" name="dib_ids[]" class="checklist" value="<?php echo $payment->id;?>"></td>
                <td><?php echo $i; ?></td>
              <td><?php echo $payment->particular ?></td>
              <td><?php echo date('d-m-Y',strtotime($payment->start_date)); ?></td>
              
                 <td></td>
              <td><?php echo  $payment->quantity; ?></td>
              <td><?php $net_pathology_data= $net_pathology_data+$payment->net_price;
              echo  $payment->net_price; ?></td>
             <td><a class="btn-custom" onclick="return delete_charges('<?php echo $payment->id;?>','<?php echo $ipd_id;?>','<?php echo $patient_id;?>')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a></td>
              </tr>
            <?php $i++;} } 

        $total_amount = $total_amount+$net_pathology_data+$net_medicne_data;
        //print_r($running_bill_data['advance_payment']);
            ?>

            <?php 
            //print_r($running_bill_data['advance_payment']);
            $net_advance_data=array();
            if(!empty($running_bill_data['advance_payment']))
            {
            foreach($running_bill_data['advance_payment'] as $payment)
              {?>
            <tr>
                <td>&nbsp;</td>
                <td><?php echo $i; ?></td>
              <td><?php echo $payment->particular ?></td>
              <td><?php echo 'Up to '.date('d-m-Y');; ?></td>
              
                 <td></td>
              <td><?php echo  $payment->quantity; ?></td>
              <td><?php $net_advance_data[]= $payment->net_price;
              echo  $payment->net_price; ?></td>
              <td><a class="btn-custom" onclick="return edit_advance_payment('<?php echo $ipd_id;?>','<?php echo $patient_id;?>')" href="javascript:void(0)" title="Edit" data-url="512" ><i class="fa fa-pencil"></i>Edit</a></td>
              </tr>
            <?php } }  ?>

           
            
            </tbody>
        </table>
        <?php $attribute='';
        $attribute_paid= '';
        $attribute_on_paid="onkeypress=payemt_get_balance();";
        $attribute_on_re="onkeypress=payemt_get_balance();";
        if(isset($net_advance_data[0]) && $net_advance_data[0]>$total_amount){
           //$attribute_paid= "readonly"; //on 25 sep as discusswith Sir
           $attribute_paid= "";
           $attribute_on="";
        }
        else
        {
          $attribute= "readonly";
          $$attribute_on_re="";
        }
        if(isset($received_amount) && isset($net_advance_data[0])){
          $balance= $received_amount-$net_advance_data[0];

        }else{
          $balance='';
        }
        

        //print_r($received_amount); ?>
        <!-- <div class="sale_medicine_bottom"> -->
        <!-- <div class="row">
          <div class="col-sm-7 text-right">
          <div class="col-sm-3 text-right"><b><span class="star">*</span>Mode of Payment</b>
            <select  class="" name="payment_mode" onchange="select_payment_mode(this.value);">
             <option value="1" <?php if($form_data['payment_mode']==1) {echo 'selected';}else{echo 'selected';}?>>Cash</option>
             <option value="2" <?php if($form_data['payment_mode']==2) {echo 'selected';}?>>Card</option>
             <option value="3" <?php if($form_data['payment_mode']==3) {echo 'selected';}?>>Cheque</option>
              <option value="4" <?php if($form_data['payment_mode']==4) {echo 'selected';}?>>Neft</option>
            </select>
            <div id="payment_detail"></div>
          </div>
          <div class="col-sm-4 text-right"> <button type="submit" class="btn-custom"><i class="fa fa-pencil"></i>Generate Bill</button></div>
     
          </div>
        </div> -->
        <!-- /////////////////// bottom portion form here //////////////////// -->
        <div class="row">
          <div class="col-md-12">
            
            <div class="row">
              <div class="col-md-8">
                  
                  <div class="row m-b-2">
                    <div class="col-md-2">
                      <label>Remarks</label>
                    </div>
                    <div class="col-md-7">
                      <textarea name="discharge_remarks" class="m_input_default" id="discharge_remarks" maxlength="250"><?php echo $form_data['discharge_remarks']; ?></textarea>
                    </div>
                </div>
                  
              </div> <!-- blank -->
              <div class="col-md-4">
                
                <button type="submit" class="btn-custom m-t-5 m-r-3" style="float: right;"><i class="fa fa-pencil"></i>Generate Bill</button><br>

                <div class="row m-b-5">
                    <div class="col-md-5">
                      <label>Mode of Payment <span class="star">*</span></label>
                    </div>
                    <div class="col-md-7">
                      <select  name="payment_mode" onChange="payment_function(this.value,'');">
                      <?php foreach($payment_mode as $o_payment_mode) 
                      {?>
                      <option value="<?php echo $o_payment_mode->id;?>" <?php if($form_data['payment_mode']== $o_payment_mode->id){ echo 'selected';}?>><?php echo $o_payment_mode->payment_mode;?></option>
                      <?php }?>

                      </select>

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

            </div>

            <div id="payment_detail">


            </div>
           
                


                <div class="row m-b-5">
                    <div class="col-md-5">
                      <label>Total Amount</label>
                    </div>
                    <div class="col-md-7">
                      <input type="text" id="total_amount_bill" value="<?php if(isset($total_amount)){ echo number_format($total_amount,2,'.',''); }?>" name="total_amount" readonly="" class="price_float"/>
                    </div>
                </div>
                <div class="row m-b-5">
                    <div class="col-md-5">
                      <label>Discount</label>
                    </div>
                    <div class="col-md-7"> <!-- onkeypress -->
                      <input type="text" id="total_discount" name="total_discount" value="<?php echo $total_discount; ?>"  onkeyup="check_paid_amount();payemt_vals_new();" class="price_float"/>
                    </div>
                </div>
                <div class="row m-b-5">
                    <div class="col-md-5">
                      <label>Net Amount</label>
                    </div>
                    <div class="col-md-7">
                      <input type="text" id="net_amount" value=""  name="total_net_amount" readonly="" class="price_float"/>
                    </div>
                </div>

                <div class="row m-b-5">
                    <div class="col-md-5">
                      <label>Advance Payment</label>
                    </div>
                    <div class="col-md-7">
                      <input type="text" id="total_advance_amount" name="total_advance_discount" value="<?php if(isset($net_advance_data[0])){echo $net_advance_data[0];}else { echo '0.00';} ?>"  readonly class="price_float"/>
                    </div>
                </div>
                
                <div class="row m-b-5">
                    <div class="col-md-5">
                      <label>Paid Amount</label>
                    </div>
                    <div class="col-md-7">
                      <input type="text" onchange="check_paid_amount();" id="total_paid_amount" name="total_paid_amount" <?php echo $attribute_on_paid; ?> value="<?php if(isset($get_paid_amount)){echo $get_paid_amount;}?>"  <?php echo $attribute_paid; ?> class="price_float"/>
                    </div>
                </div>
                <div class="row m-b-5">
                    <div class="col-md-5">
                      <label>Refund Amount</label>
                    </div>
                    <div class="col-md-7">
                      <input type="text" id="total_refund_amount" name="total_refund_amount" <?php echo  $attribute_on_re; ?> value=""  <?php echo $attribute; ?>  class="price_float"/>
                    </div>
                </div>
                
                <div class="row m-b-5" id="refund_payment_div" style="display:none;">
                    <div class="col-md-5">
                      <label>Refund Mode of Payment </label>
                    </div>
                    <div class="col-md-7">
                      <select  name="refund_payment_mode" id="refund_payment_mode" onChange="refund_payment_function(this.value,'');">
                      <?php 
                      //echo "<pre>"; print_r($payment_mode); exit;
                      foreach($payment_mode as $ref_paymentmode) 
                      {?>
                      <option value="<?php echo $ref_paymentmode->id;?>" <?php if($form_data['refund_payment_mode']== $ref_paymentmode->id){ echo 'selected';}?>><?php echo $ref_paymentmode->payment_mode;?></option>
                      <?php }?>

                      </select>

                    </div>
                </div>
                <div id="refund_payment_detail">


            </div>
                <div class="row m-b-5">
                    <div class="col-md-5">
                      <label>Balance</label>
                    </div>
                    <div class="col-md-7">
                      <input type="text" id="total_balance" name="total_balance"  value="<?php if(isset($balance)){echo $balance;}?>"  readonly="" class="price_float"/>
                    </div>
                </div>

              </div>
            </div>


          </div>
        </div>




        <!-- <div class="left">
            <div class="right_box">
                <div class="sale_medicine_mod_of_payment">
                    <label>Total Amount</label>
                    <input type="text" id="total_amount_bill" value="<?php if(isset($total_amount)){ echo number_format($total_amount,2,'.',''); }?>" name="total_amount" readonly="" class="price_float"/>
                </div>
                <div class="sale_medicine_mod_of_payment">
                  <label>Discount (Rs.)</label>
                  <input type="text" id="total_discount" name="total_discount" value="0.00"  onkeypress="payemt_vals_new();" class="price_float"/>
                </div>
               <div class="sale_medicine_mod_of_payment">
                 <label>Advance Payment</label>
                  <input type="text" id="total_advance_amount" name="total_advance_discount" value="<?php if(isset($net_advance_data[0])){echo $net_advance_data[0];}?>"  readonly class="price_float"/>
                </div>
                 

                <div class="sale_medicine_mod_of_payment">
                  <label>Net Amount</label>
                  <input type="text" id="net_amount" value=""  name="total_net_amount" readonly="" class="price_float"/>
                </div>

                <div class="sale_medicine_mod_of_payment">
                  <label>Paid Amount</label>
                  <input type="text" id="total_paid_amount" name="total_paid_amount" <?php echo $attribute_on_paid; ?> value="<?php if(isset($get_paid_amount)){echo $get_paid_amount;}?>"  <?php echo $attribute_paid; ?> class="price_float"/>
                </div>

                <div class="sale_medicine_mod_of_payment">
                 <label>Refund Amount</label>
                  <input type="text" id="total_refund_amount" name="total_refund_amount" <?php echo  $attribute_on_re; ?> value=""  <?php echo $attribute; ?>  class="price_float"/>
                </div>
                <div class="sale_medicine_mod_of_payment">
                    <label>Balance</label>
                    <input type="text" id="total_balance" name="total_balance"  value="<?php if(isset($balance)){echo $balance;}?>"  readonly="" class="price_float"/>
                </div>
                

            </div> 

            
        </div> --> <!-- left -->
      
    <!-- </div> --> <!-- sale_medicine_bottom -->
    </form>


   </div> <!-- close -->
   <div class="userlist-right">
  		<div class="btns">
         
               
          <?php if(in_array('782',$users_data['permission']['action']) || in_array('130',$users_data['permission']['section'])){
          ?>
        <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
          <i class="fa fa-trash"></i> Delete
        </button>
          <?php } ?>   
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url('ipd_booking');?>'">
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
    
    <!-- Confirmation Box  end -->
     <div id="confirm_print_bill" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <div class="modal-footer">
             <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ipd_discharge_bill/print_discharge_bill_paidamount/".$ipd_id.'/'.$patient_id); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 
<!-- Confirmation Box  for bill paid amount end -->

     <div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <!-- <a type="button" data-dismiss="modal" class="btn-anchor" id="delete" onClick="return openPrintWindow('< ?php echo base_url("sales_medicine/print_sales_report"); ?>', 'windowTitle', 'width=820,height=600');" target="_blank">Print</a> -->

            <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ipd_discharge_bill/print_discharge_bill/".$ipd_id.'/'.$patient_id); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 

<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_allot_to_branch_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</div>
<script>
$(document).ready(function(){
  //reload_table();
   $('#selectAll').on('click', function () { //alert('test');
                                 
         if ($("#selectAll").hasClass('allChecked')) {
             $('.checklist').prop('checked', false);
         } else {
             $('.checklist').prop('checked', true);
         }
         $(this).toggleClass('allChecked');
    });
});
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
$('documnet').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print'){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('ipd_booking');?>'; 
    }) ;
   
       
  <?php }?>
  
  <?php if(isset($_GET['billstatus']) && $_GET['billstatus']=='printbill'){?>
  $('#confirm_print_bill').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('ipd_booking');?>'; 
    }) ;
   
       
  <?php }?>
 });

function edit_advance_payment(ipd_id,patient_id){

    var $modal = $('#load_advance_payment_modal_popup');
    $modal.load('<?php echo base_url().'ipd_discharge_bill/update_advance_payment/' ?>'+ipd_id+'/'+patient_id,
    {

    //'id1': '1',
    //'id2': '2'
    },
    function(){
    $modal.modal('show');
    });
}

function my_func(data_id){
  var $modal = $('#load_end_now_modal_popup');
var data_id= $('#end_now_add_modal_'+data_id).val();
var start_date = $('#start_date_'+data_id).val();
$modal.load('<?php echo base_url().'ipd_running_bill/end_now/' ?>'+data_id+'/'+start_date,
{

  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});



}
function update_row(edit_id){

   var timerA = setInterval(function(){  
      update_perticulars(edit_id,1);
     
      clearInterval(timerA); 
      }, 1000);
}


function update_perticulars(edit_id,update_row)
{
  var price_edit= $('#price_edit_'+edit_id).val();
  var qty_edit= $('#qty_edit_'+edit_id).val();
  var net_price_edit= $('#net_price_edit_'+edit_id).val();
        $('.overlay-loader').css('display','block');
      $.ajax({
            url : "<?php echo base_url('ipd_discharge_bill/update_discharge_date/'); ?>",
            dataType: "json",
            method: 'post',
            data: {data_id:edit_id,price_edit:price_edit,qty_edit:qty_edit,net_price:net_price_edit},
            success: function( data ) {
               payment_calc_all(update_row);
              //alert();
                // window.location.href="<?php echo base_url('ipd_discharge_bill/discharge_bill_info');?>/"+'<?php echo $ipd_id ?>'+'/'+'<?php echo $patient_id ?>';
               }
      });
}

function get_total_amount(quantity){

  var amount= $('#charge').val();
  var total_amount= amount *quantity;
  $('#amount').val(total_amount);

}


$(function () {
  payment_calc_all();
 
    var i=1;
    var patient_type = "<?=$ipd_details['patient_type']?>";
    var panel_name = "<?=$ipd_details['panel_name']?>";
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('ipd_discharge_bill/get_particular_data/'); ?>" + request.term + "/" + patient_type + "/" + panel_name,
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

function get_total_amount(quantity){

  var amount= $('#charge').val();
  var total_amount= amount *quantity;
  $('#amount').val(total_amount);

}

function payment_calculate(edit_id){
  
  var qty= $('#qty_edit_'+edit_id).val();
  var amount= $('#price_edit_'+edit_id).val();
  var total_amount= amount *qty;
  $('#net_price_edit_'+edit_id).val(total_amount);
}


  function payemt_vals(quantity)
  {
      var timerA = setInterval(function(){  
      get_total_amount(quantity);
      clearInterval(timerA); 
      }, 80);
  }

  function payemt_vals_new()
  {

    var timerA = setInterval(function(){  
    payment_calc_all();
    clearInterval(timerA); 
    }, 80);
  }

  function payemt_get_balance()
  {
  var timerA = setInterval(function(){  
   payment_new_calc_all(2);
   clearInterval(timerA); 
  }, 80);
  }

  function payment_new_calc_all(update_row)
  {
        var discount = $('#total_discount').val();
        var total_amount= $('#total_amount_bill').val();
        var net_amount = $('#net_amount').val();
        var total_paid_amount= $('#total_paid_amount').val();
        var total_refund_amount= $('#total_refund_amount').val();
        var total_advance_amount= $('#total_advance_amount').val();
        $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>ipd_discharge_bill/payment_calc_all/", 
        dataType: "json",
        data: 'discount='+discount+'&net_amount='+net_amount+'&pay_amount='+total_paid_amount+'&total_refund_amount='+total_refund_amount+'&total_amount='+total_amount+'&total_advance_amount='+total_advance_amount+'&ipd_id='+'<?php echo $ipd_id; ?>'+'&patient_id='+'<?php echo $patient_id; ?>'+'&update_row='+update_row,
      success: function(result)
      {

        //$('#total_refund_amount').val(result.refund_amount);
        $('#total_balance').val(result.balance_due);
      } 
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
        url : "<?php echo base_url('ipd_discharge_bill/add_perticulars/'); ?>"+ '<?php echo $ipd_id ?>'+'/'+'<?php echo $patient_id;?>'+'/'+'<?php echo strtotime($form_data['discharge_date']);?>',
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
            { //alert(data.success);
              window.location.href='<?php echo base_url('ipd_discharge_bill/discharge_bill_info') ?>/'+data.ipd_id+'/'+data.patient_id+'/'+data.discharge_date;
             //window.location.href="<?php echo base_url('ipd_discharge_bill/discharge_bill_info');?>/"+'<?php echo $ipd_id ?>'+'/'+'<?php echo $patient_id ?>'+'/'+'<?php echo strtotime($form_data['discharge_date']);?>';
          }
        }
     });

  }
  
   function delete_charges(id,ipd_id,patient_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('ipd_discharge_bill/delete_charges/'); ?>"+id+'/'+ipd_id+'/'+patient_id+'/'+'<?php echo strtotime($form_data['discharge_date']);?>', 
                 /*success: function(result)
                 {
                     alert(result.ipd_id);
                      window.location.href='<?php echo base_url('ipd_discharge_bill/discharge_bill_info') ?>/'+result.ipd_id+'/'+result.patient_id+'/'+result.discharge_date;
             

                    //window.location.href="<?php echo base_url('ipd_discharge_bill/discharge_bill_info/'.$ipd_id.'/'.$patient_id.'/'.$form_data['discharge_date']);?>"
                 }*/
                 
                  success: function( data ) { var result = JSON.parse(data); //console.log(result);
                  //if(data==1)
                  //{
                  if(result.success==1)
                    { //alert(data.success);
                      window.location.href='<?php echo base_url('ipd_discharge_bill/discharge_bill_info') ?>/'+result.ipd_id+'/'+result.patient_id+'/'+result.discharge_date;
                   }
                }
              });
    });     
 }

  function payment_calc_all(update_row)
    {

     
       var discount = $('#total_discount').val();
       var total_amount= $('#total_amount_bill').val();
        var net_amount = $('#net_amount').val();
        var total_paid_amount= $('#total_paid_amount').val();
        var total_refund_amount= $('#total_refund_amount').val();
        var total_advance_amount= $('#total_advance_amount').val();
        $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>ipd_discharge_bill/payment_calc_all/", 
        dataType: "json",
        data: 'discount='+discount+'&net_amount='+net_amount+'&pay_amount='+total_paid_amount+'&total_refund_amount='+total_refund_amount+'&total_amount='+total_amount+'&total_advance_amount='+total_advance_amount+'&ipd_id='+'<?php echo $ipd_id; ?>'+'&patient_id='+'<?php echo $patient_id; ?>'+'&update_row='+update_row,
      success: function(result)
      {
            
            $('#total_discount').val(result.discount_amount);
            $('#net_amount').val(result.net_amount);
            $('#total_paid_amount').val(result.pay_amount);
            $('#total_refund_amount').val(result.refund_amount);
            $('#total_discount').val(result.discount);
            $('#total_balance').val(result.balance_due);
            $('#total_amount_bill').val(result.total_amount_bill);
            $('#total_advance_amount').val(result.total_advance_amount);
            
            if(result.refund_amount>0)
            {
                
                $('#refund_payment_div').css("display", "block");
            }
            
            
           $('.overlay-loader').css('display','none'); 
      } 
    });
    
    
    }


    $(document).ready(function(){

 
   
  });
 $('.particular_datepicker').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true
                }); 
function payment_function(value,error_field){
           $('#updated_payment_detail').html('');
                $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('ipd_discharge_bill/get_payment_mode_data')?>",
                     data: {'payment_mode_id' : value,'error_field':error_field},
                     success: function(msg){
                     $('#payment_detail').html(msg);
                     }
                });


           $('.particular_datepicker').datepicker({
           format: "dd-mm-yyyy",
           autoclose: true
           });  

      }
      
      function refund_payment_function(value,error_field){
           $('#updated_payment_detail').html('');
                $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('ipd_discharge_bill/get_payment_mode_data')?>",
                     data: {'payment_mode_id' : value,'error_field':error_field},
                     success: function(msg){
                     $('#refund_payment_detail').html(msg);
                     }
                });



      }
      $(document).ready(function(){
           $('.particular_datepicker').datepicker({
           format: "dd-mm-yyyy",
           autoclose: true
           }); 
      }); 

function checkboxValues() 
{         
   // $('#table').dataTable();
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

function allbranch_delete(allVals)
 {  
   var ipd_id='<?php echo $ipd_id;?>';
   var patient_id='<?php echo $patient_id;?>';
   if(allVals!="")
   {
       $('#confirm').modal({
        backdrop: 'static',
        keyboard: false
        })
        .one('click', '#delete', function(e)
        {
             $.ajax({
                 type: "POST", 
                 url: "<?php echo base_url('ipd_discharge_bill/deleteAll_charges'); ?>", 
                 data: {row_id: allVals, ipd_id: ipd_id, patient_id: patient_id},
                 success: function(result)
                 {
                    window.location.href="<?php echo base_url('ipd_discharge_bill/discharge_bill_info/'.$ipd_id.'/'.$patient_id.'/'.strtotime($form_data['discharge_date']));?>";
                 }
              });
        });
   }   

   else{
        $('#confirm-select').modal({
            backdrop: 'static',
            keyboard: false
          });
     }      

 }
</script>

<div id="load_end_now_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_advance_payment_modal_popup" class="modal fade modal-100" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<script type="text/javascript">
$(document).ready(function(){
<?php
if(empty($_POST))
{

if((!empty($pathology_balance))|| (!empty($medicine_balance)))
{ ?>  

  $('#ipd_due_balance').modal({
     backdrop: 'static',
      keyboard: false
        })
<?php 

}
}
?>

});
</script>

<div id="ipd_due_balance" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content bg-r-border">
            <div class="modal-header bg-theme bg-red"><b><span>Notice</span></b><button type="button" class="close close1" data-dismiss="modal" data-number="4" aria-label="Close"><span aria-hidden="true">×</span></button></div>
          <div class="modal-footer  text-l">
            <?php if(!empty($pathology_balance)) { ?>
           <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Please clear the Lab due amount of Rs. <?php echo $pathology_balance;  ?> </span></p>
          <?php } ?>
          <?php if(!empty($medicine_balance)) { ?>
           <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Please clear Sale medicine due amount of Rs. <?php echo $medicine_balance;  ?> </span></p>
          <?php } ?>
          
          </div>
        </div>
      </div>  
    </div>
    
    <div class="overlay-loader"><img src="<?php echo base_url().'assets/images/loader.gif';?>"></div>