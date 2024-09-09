<?php
$users_data = $this->session->userdata('auth_users');
$this->session->set_userdata('total_price_issue_bag','');

//print_r($pat_data);
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title; ?></title>
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

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-select.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-select.min.css">
<body onLoad="get_stock_available('<?php echo $recipient_selected_component_detail; ?>')" onload >


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');

 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">


<div class="row">
  <form method="post" name="issue_form" id="issue_form" >
  <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $patient_id; ?>">
   <input type="hidden" name="recipient_id" id="recipient_id" value="<?php echo $recipient_id; ?>">
   <input type="hidden" name="bag_id" id="bag_id" value="<?php echo $bag_id; ?>">
  <div class="col-lg-12">
    <div class="">
      <!-- /////////////////// -->
     
      <!-- row -->
      <!-- ***** -->

      <div class="row">
        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Recipient Id<span class="star">*</span></b></div>
              <div class="col-md-7">
               <input type="text" readonly class="m_input_default" value="<?php echo $reg_no; ?>" >
               
                <input type="hidden" name="issue_no" id="issue_no" class="m_input_default" value="<?php echo $reg_no; ?>" >
                <span id=""></span>
              </div>
            </div>
        </div>   <!-- col-4 -->

        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Order Number</b></div>
              <div class="col-md-7">
               <input type="text" readonly class="m_input_default" value="<?php echo $pat_data['patient_name'];; ?>" >
               
              </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Mobile No.</b></div>
              <div class="col-md-7">
               <input type="text" readonly class="m_input_default" value="<?php echo $pat_data['mobile_no']; ?>" >
               
              
              </div>
            </div>
        </div>
      </div>


<div class="row">
        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Gender</b></div>
              <div class="col-md-7">
              <?php
                $male=""; $female=""; $other="";
                if($pat_data!='empty')
                { 
                    if($pat_data['gender']==1)
                      $male="Male"; 
                    else if($pat_data['gender']==0)
                      $male="Female"; 
                    else if($pat_data['gender']==2)
                      $male="Others"; 
                }
              ?>
               <input type="text" readonly class="m_input_default" value="<?php echo $male; ?>" >
               
                
              </div>
            </div>
        </div>   <!-- col-4 -->

        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Client Name</b></div>
              <div class="col-md-7">
               
               <input type="text" readonly class="m_input_default" value="<?php echo $pat_data['relation_name']; ?>" >
              </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Email Id</b></div>
              <div class="col-md-7">
               <input type="text" readonly class="m_input_default" value="<?php echo $pat_data['patient_email']; ?>" >
               
               
              </div>
            </div>
        </div>
      </div>


<div class="row">
        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Issue No.</b></div>
              <div class="col-md-7">
             
               <input type="text" readonly class="m_input_default" value="<?php echo $reg_no; ?>" >
               
                
              </div>
            </div>
        </div>   <!-- col-4 -->

       

        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Blood Group</b></div>
              <div class="col-md-7">
              <input type="text" readonly class="m_input_default" value="<?php echo $pat_data['blood_group']; ?>">
               <input type="hidden"  class="m_input_default" value="<?php echo $pat_data['b_id']; ?>" id="blood_group_id">
         
               
               
              </div>
            </div>
        </div>

         <div class="col-md-4">
    <div class="row mb-5">
       <div class="col-md-5"><b>Technician Name</b></div>
              <div class="col-md-7">
      <select name="technician_id" id="technician_id" class="m_input_default">
              <option value="">Select Technician</option>
              <?php
              if(!empty($technician_data))
              {
                
                foreach($technician_data as $technician_data_list)
                {
                    if($technician_data_list!='empty')
                    { 
                        if($pat_data['technician_id']==$technician_data_list->emp_id)
                          $select="selected=selected";
                        else
                          $select="";

                          echo '<option value="'.$technician_data_list->emp_id.'" '.$select.'>'.$technician_data_list->name.'</option>';

                    }
                    else
                    {
                      echo '<option value="'.$technician_data_list->emp_id.'" >'.$technician_data_list->name.'</option>';
                    }
                }
              }
              ?> 
            </select> 
          
          </div>
            </div>
            
           </div> 


      </div>
      
  
            
            
            <!-- here-->
      <div class="row">
        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Component</b></div>
              <div class="col-md-7">
               <textarea type="text" readonly><?php echo $recipient_component_detail;?></textarea>
               
              </div>
            </div>
        </div>
        <div class="col-md-4">
             <div class="row mb-5">
            <div class="col-md-5"><b>Billing for</b></div>
                <div class="col-md-7">
                    <input type="text" name="billing_for" class="m_input_default" value="<?php echo $pat_data['billing_for']; ?>" >
                
                </div>
        </div>
        </div>

        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Verify By Doctor</b></div>
              <div class="col-md-7">
      <select name="verify_doctor_id" id="verify_doctor_id" class="m_input_default">
              <option value="">Select Doctor</option>
              <?php
              if(!empty($doctor_data))
              {
                
                foreach($doctor_data as $doctor_list)
                {
                    
                        if($pat_data['verify_doctor_id']==$doctor_list->id)
                          $select="selected=selected";
                        else
                          $select="";

                          echo '<option value="'.$doctor_list->id.'" '.$select.'>'.$doctor_list->doctor_name.'</option>';

                }
              }
              ?> 
            </select> 
          
         </div>
            </div>
        </div> 
         

          
        </div>
            
            <!-- end here -->

      <br>

     
  <script type="text/javascript">
     $('.particular_datepicker').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true
                }); 
  </script>
 <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
  <script type="text/javascript">
    $(function () {
  //payment_calc_all();
 
    var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('blood_bank/recipient/get_particular_data/'); ?>" + request.term,
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
      get_part_total_amount(quantity);
      clearInterval(timerA); 
      }, 40);
  }

  function get_part_total_amount(quantity)
  {
    var amount= $('#charge').val();
    var total_amount= amount *quantity;
    $('#parti_amount').val(total_amount);
  }
  
  function add_perticulars()
  {
    var particular= $('#particular').val();
    var particular_id= $('#particular_id').val();
    var date= $('#date').val();
    var charge= $('#charge').val();
    var qty= $('#parti_qty').val();
    var amount= $('#parti_amount').val();
    
     $.ajax({
        url : "<?php echo base_url('blood_bank/recipient/add_perticulars/'); ?>"+ '<?php echo $recipient_id ?>',
        dataType: "json",
        method: 'post',
        data: {
        particular: particular,
         date : date,
        particular_id: particular_id,
        charge : charge,
        qty : qty,
        amount : amount,
       
      },
        success: function( result ) {
          //if(data==1)
          //{
          if(result.success==1)
            { 

              $("#table_tbody_bag").append(result.data);
                get_total_amount();

              //window.location.href='< ?php echo base_url('blood_bank/recipient/issue_component') ?>/'+data.recipient_id;
             
          }
        }
     });

  }

  function add_perticulars_old()
  {
    var particular= $('#particular').val();
    var particular_id= $('#particular_id').val();
    var date= $('#date').val();
    var charge= $('#charge').val();
    var qty= $('#parti_qty').val();
    var amount= $('#parti_amount').val();
    
     $.ajax({
        url : "<?php echo base_url('blood_bank/recipient/add_perticulars/'); ?>"+ '<?php echo $recipient_id ?>',
        dataType: "json",
        method: 'post',
        data: {
        particular: particular,
         date : date,
        particular_id: particular_id,
        charge : charge,
        qty : qty,
        amount : amount,
       
      },
        success: function( data ) {
          //if(data==1)
          //{
          if(data.success==1)
            { 
                $("#table_tbody_bag").append(result.data);
                get_total_amount();
              //window.location.href='< ?php echo base_url('blood_bank/recipient/issue_component') ?>/'+data.recipient_id;
             
          }
        }
     });

  }


  </script>
            
      
      
      
      <br>

      <div class="row">
     

         <!-- col-4 -->
    <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Component Required</b></div>
              <div class="col-md-7">
              <!-- <input type="text" name="component_name" id="component_name"  onkeyup="get_stock_available();get_donor_list();" class="m_input_default" value=""  placeholder="Type component name"> -->

              <select name="component_name" id="component_name" onchange="get_stock_available(); get_donor_list();">
                  <option>Select Component</option>
                  <?php if(!empty($component_list))
                    { 
                      foreach($component_list as $comp_name){?>
                      <option value="<?php echo $comp_name->id; ?>"><?php echo $comp_name->component; ?></option>
    
                      <?php 
                      } 
                    } ?>
                </select>

           <span id="patient_email_error"></span>
               
               
              </div>
            </div>
        </div>
         <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Donor Id</b></div>
              <div class="col-md-7" id="donor_data_id">
             
                <select name="donor_id" id="donor_id" class="" onchange="get_stock_available();" data-live-search="">
              <option value="">Select Donor</option>
              <?php
              if(!empty($donor_list))
              {
              foreach($donor_list as $donarlist)
              {

               echo '<option value="'.$donarlist->id.'_'.$donarlist->blood_group.'_'.$donarlist->blood_group_id.'" >'.$donarlist->donor_code.'</option>';
              }
              }
              ?> 
              </select>
               
                
              </div>
            </div>
        </div> 
        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Bag Bar Code</b></div>
              <div class="col-md-7">
             <input type="text" name="bar_code" id="bar_code"  onkeyup="get_stock_available();get_donor_list();" class="m_input_default" value=""  placeholder="Bag Bar Code">
            <span id="patient_email_error"></span>
               
              </div>
            </div>
        </div>

       
      </div>




      <!-- ****** -->

    </div>

  

  </div>


   <div class="col-lg-12" id="get_issue">
     <div class="">
      <table id="table" class="table table-striped table-bordered donor_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                   <th width="40" align="center"><input type="checkbox" name="selectall" class="" id="selectAll" value="">Select</th>
                   <th>Donor ID</th>
                   <th>Barcode</th>
                   <th>Blood Group</th>
                   
                   <th>Component Type</th>
                   <th>Volume</th>
                   <th>Created Date</th>
                   <th>Expiry Date</th>
                   <th>Status</th>
                   <th>Qty</th>
                  
                 </tr>
            </thead>  
            <tbody id="table_tbody">
            <?php //print_r($componenet_detail); 
             if(isset($componenet_detail))
            {
              ?>
              <input type="hidden" name="cross_match" id="cross_match" value="1"/>
             <?php foreach($componenet_detail as $stock)
              {
               // print_r($stock);
                 if(strtotime($stock['expiry_date']) >86400){
                  $expiry_date= date('d-m-Y H:i:s',strtotime($stock['expiry_date']));
                  }
                  else
                  {
                    $expiry_date='';
                  }
                  $bag_type_id='';
                  $exist_id='';
                  $get_stock_quantity= get_stock_quantity($bag_type_id,$stock['component_id'],$exist_id,$stock['d_id'],$stock['barcode']);
                  //echo $this->db->last_query();die;
                  $component_total_qty = $get_stock_quantity['total_qty'];
                  if($get_stock_quantity['total_qty']>=0){
                    $component_qty=$component_total_qty;
                   }

                ?>
                <tr id="cross_match_tr_<?php echo $stock['id']; ?>">
                <td>
                <input type="checkbox" class="cross_checkbox_box stock_checkbox_box" name="cross_match_check[]" value="<?php echo $stock['id']; ?>"></td>
                <td><?php echo $stock['option'];?></td>
                <td><?php echo $stock['qty'];?></td>
                <td><?php echo $expiry_date;?></td>
                <td><?php echo $stock['donor_id'];?></td>
                <td><?php echo $stock['barcode'];?></td>
                <td><?php echo $stock['component_price'];?></td></tr>

            <?php } } ?>
              

            </tbody>
        </table>
       <input type="button" id="data_handler" name="submit" value="Add" class="btn-update" onclick="add_to_cart();return false;">
     </div>
  </div>

<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>

 
  <div class="col-lg-12">
     <div class="">
         <table id="table" class="table table-striped table-bordered donor_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="100%" style="padding:1px;"><b>Surcharge / Add ons</b></th>
                </tr>
            </thead>  
        </table>
          <!--<div style="padding:5px;"></div>-->
    <table style="margin-bottom:10px;">
  <tr>
    <th style="text-align:left;" width="10%"> Surcharge</th>
    <td style="text-align:left;" width="10%"><input type="text" id="particular" name="particular" class="w-150px  particular_val alpha_numeric_space inputFocus"/><input type="hidden" id="particular_id" name="particular_id" class="w-100px  particular_val alpha_numeric_space inputFocus"/></td>
    
  <th style="text-align:left;padding-left:20px;" width="10%">Date </th>
    <td style="text-align:left;" width="10%"><input type="text" id="date" name="date" value="<?php echo $particular_date;?>" class="w-100px particular_datepicker" /></td>
    <th style="text-align:left;padding-left:20px;" width="10%">Rate </th>
    <td style="text-align:left;" width="10%"><input type="text" id="charge"  name="rate" class="w-150px price_float"/></td>
    <th style="text-align:left;padding-left:20px;" width="5%">Qty. </th>
    <td style="text-align:left;" width="5%"><input type="text" id="parti_qty" name="parti_qty" value="0.00" class="w-50px numeric" onblur="payemt_vals(this.value);"/></td>
    <th style="text-align:left;padding-left:20px;" width="10%">Amount </th>
    <td style="text-align:left;" width="10%"><input type="text" name="parti_amount" id="parti_amount" value="0.00" class="w-150px price_float" readonly/></td>
    <th style="text-align:left;" width="5%">&nbsp;</th>
    <td style="text-align:left;" width="10%"><a href="#" class="btn-custom" onclick="add_perticulars();">Add</a></td>
    </tr>
    </table>
  </div>
  </div>   

<?php //if($payment_data=="empty") {  $style="display:none"; } else { $style="display:block"; }  ?> 
<?php //if(!empty($particular_data)){ $style="display:block";}?>
  <div class="col-lg-12" id="get_issue_bag" style="<?php echo $style; ?>">
    <div class="" style="float:left;width:100%;">
      <div style="padding:5px;"><b>Blood Issue Cart</b></div>
      <table id="table_bag" class="table table-striped table-bordered donor_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th> Component Issued </th>   
                    
                    <th> Unit Quantity </th>
                    
                    <th> Cross Matching </th>  
                    <th> Surcharge / Add ons </th>
                   
                    <th> Component Price </th>
                    <th> Issue Date </th>
                    <th> Action </th>
                </tr>
            </thead>  
            <tbody id="table_tbody_bag">
                  <?php 
                  if($issue_data!="empty"){
                    
                    foreach($issue_data as $issue)
                    {
                      if(strtotime($issue->expiry_date)>86400)
                      {
                        $expiry_date=date('d-m-Y H:i:s',strtotime($issue->expiry_date));
                      }
                      else
                      {
                        $expiry_date=date('d-m-Y H:i:s'); 
                      }
                      if(strtotime($issue->issue_date)>86400)
                      {
                        $issue_date=date('d-m-Y',strtotime($issue->issue_date));
                      }
                      else
                      {
                      $issue_date=date('d-m-Y'); 
                      }
                      $check_script='';
                      
                      $check_script_issue='';
                      $table="<input type='hidden' name='rec_ids[".$issue->id."]' id='rec_ids_".$issue->id."' value='".$issue->donor_id."' >"; 
                  $table.="<tr id='data_tr_".$issue->id."'>";
                  $table.="<input type='hidden' class='stock_checkbox' name='issue_check[]' value='".$issue->id."'><input type='hidden' name='donor_id[".$issue->id."]' value='".$issue->donor_id."'>";
                  $table.="<td>".$issue->donor_code.' '.$issue->bar_code.'<br>'.$issue->component_name."<input type='hidden' name='comp_name[".$issue->id."]' id='comp_name_'".$issue->id." value='".$issue->component_name."'><input type='hidden' name='comp_id[".$issue->id."]' id='comp_id_'".$issue->id." value='".$issue->component_id."'></td>";
                 

                  
                 


                  $table.="<td>".$issue->qty."<input type='hidden' name='quantity[".$issue->id."]' id='quantity_'".$issue->id."' value='".$issue->qty."'>";
                  $table.="<input type='hidden' name='expiry_date[".$issue->id."]'  value='".$expiry_date."' id='expiry_datepicker_".$issue->id."' readonly></td>"; 
                  
                   if($issue->cross_match=='1')
                   {
                    $cross_m = 'Yes';
                   }
                   else
                   {
                    $cross_m = 'No';
                   }
                   $table.="<td>".$cross_m."</td>";
                   
                  $table.="<td></td>";
                  $table.="<input type='hidden' name='bar_code[".$issue->id."]' id='bar_code_'".$issue->id." value='".$issue->bar_code."'>"; 
                  $table.="<td><input type='text' class='iss_cart_price' name='component_price[".$issue->id."]' id='comp_price_".$issue->id."' value='".$issue->component_price."' onkeyup='return change_price(".$issue->component_price.",this.value)'></td>"; 
                  $table.="<td><input type='text' name='issue_date[".$issue->id."]' value='".$issue_date."' id='issue_datepicker_".$issue->id."'>".$check_script_issue."</td>"; 
                  $table.="<td><input class='btn-update' type='button' name='rev' value='Remove' onclick='remove_component_from_bucket(".$issue->id."); return false;'> </td>"; 
                  $table.="</tr>";
                    
                      
                    }

                  }
                 // echo "<pre>"; print_r($particular_data); exit;
                  if(!empty($particular_data))
                  {
                    $i=1;
                    foreach($particular_data as $particular)
                    {

                            if(strtotime($particular->payment_date)>86400)
                            {
                              $payment_date=date('d-m-Y',strtotime($particular->payment_date));
                            }
                            else
                            {
                              $payment_date=date('d-m-Y'); 
                            }

                            $table.="<tr id='data_tr_".$i."'>";
                            $table.="<input type='hidden' name='particular_id[".$particular->particular_id."]' value='".$particular->particular_id."'>";
                            $table.="<td><input type='hidden' name='comp_name[".$particular->id."]' id='comp_name_'".$particular->id." value='".$particular->particular."'><input type='hidden' name='comp_id[".$particular->id."]' id='comp_id_'".$particular->id." value='".$particular->component_id."'></td>";
                            
                            $table.="<td>".$particular->quantity."<input type='hidden' name='quantity[".$particular->id."]' id='quantity_'".$particular->id."' value='".$particular->quantity."'>";
                            $table.="<td>&nbsp;</td><td>".$particular->particular."</td>"; 

                            
                            
                            $table.="<td><input type='text' class='iss_cart_price' name='component_price[".$particular->recipient_id."]' id='comp_price_".$particular->recipient_id."' value='".$particular->net_price."' onkeyup='return change_price(".$particular->net_price.",this.value)'></td>"; 
                            $table.="<td><input type='text' name='expiry_date[".$particular->id."]'  value='".$payment_date."' id='expiry_datepicker_".$particular->id."' readonly></td>"; 
                            $table.="<td><input class='btn-update' type='button' name='rev' value='Remove' onclick='remove_particular_charge(".$particular->id.",".$particular->recipient_id."); return false;'> </td>"; 
                            $table.="</tr>";
                          $i++;
                    }
                  }
                  echo $table;
                  ?>



            </tbody>
      </table>


      <div class="col-xs-4 " id="payment_box" style="float:right;">
        <div class="col-md-12">
          <div class="row m-b-5 opd_m_left">
            <div class="col-md-5"><b>Mode of Payment</b></div>
              <div class="col-md-7 opd_p_left">
                <select  name="payment_mode" class="m_input_default" onChange="payment_function(this.value,'');">
                  <?php foreach($payment_mode as $payment_mode) 
                      {
                          if($payment_data!="empty")
                          {
                            if($payment_data['pay_mode']==$payment_mode->id)
                              $select="selected=selected";
                            else
                              $select="";
                          }
                          else
                          {
                            $select="";
                          }
                        ?>
                      <option <?php echo $select; ?> value="<?php echo $payment_mode->id;?>"><?php echo $payment_mode->payment_mode;?></option>
                  <?php }?>
                </select>
              </div>
            </div>
        </div>
       

        <?php if($payment_fields!="empty"){ ?>
         <div id="payment_detail">
            <?php   //print_r($payment_fields);die; 
                    foreach ($payment_fields as $field_names) 
                    {
                    ?>

                  <div class="row m-b-5">
                    <div class="col-md-12">
                      <div class="row">
                       <div class="col-md-5"><b><?php echo $field_names->field_name;?><span class="star">*</span></b></div> 
                      <div class="col-md-7"> 
                      <input type="text" name="field_name[]" value="<?php echo $field_names->field_value;?>" /><input type="hidden" value="<?php echo $field_names->field_id;?>" name="field_id[]" />
                      </div>
                      </div>
                    </div>
                  </div>
                   <?php } ?>
         </div>
         <?php }  else { ?>
            <div id="payment_detail"></div>
         <?php } ?>

        <?php if($payment_data=="empty"){  ?>
            <input type="hidden" name="hidden_pay_id" value=0 id="hidden_pay_id" >
        <?php } else { ?>
          <input type="hidden" name="hidden_pay_id" value="<?php echo $payment_data['id']; ?>" id="hidden_pay_id" >
        <?php } ?>



         <div class="row m-b-5">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Total Amount</b></div>
                         <div class="col-md-7">
                              <input type="text" readonly=""  name="total_amount" id="total_amount" class="price_float m_input_default" value="<?php if($payment_data!="empty"){ echo number_format($payment_data['total_amount'],2,'.', ''); } ?>">
                         </div>
                    </div>
               </div>
          </div> <!-- row -->

          <?php if($pat_data!="empty") 
              { 
              if($pat_data['shipment_amount'] > 0)
              {

                  $display="display:block;";
                  $shipment_amount=$pat_data['shipment_amount'];
              }
              else
              {
                  $display="display:none;";
                  $shipment_amount=0;
              }
              } 
               if(!empty($pat_data['issue_by_mode']))
                {
                    if($pat_data['issue_by_mode']=='1')
                    {
                      $display="display:block;";
                      $shipment_amount=$pat_data['shipment_amount'];

                    }
                    if($pat_data['issue_by_mode']=='2')
                    {
                      $display="display:none;";
                      $shipment_amount=0;

                    }
                }
              ?>
          <div class="row m-b-5" id="textboxes" style="<?php echo $display; ?>">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Shipment Charge</b></div>
                         <div class="col-md-7">
                              <input type="text"  name="shipment_charge" class="price_float m_input_default" id="shipment_charge" value="<?php  echo $shipment_amount; ?>" onkeyup="get_total_amount();">
                         </div>
                    </div>
               </div>
          </div> <!-- row -->
        <?php
        $discount_val_setting = get_setting_value('ENABLE_DISCOUNT'); 
        if($discount_val_setting==1)
        {
        ?>  
         <div class="row m-b-5">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Discount</b>
                          <input type="text" style="float:right;" onkeyup="return get_total_amount();" placeholder="%" value="<?php if($pat_data!="empty") { echo $pat_data['discount_percent']; } else { echo "0"; } ?>" name="discount_percent" id="discount_percent" placeholder="%" class="input-tiny price_float">
                         </div>
                         <div class="col-md-7">
                              <input type="text" name="discount" onkeyup="return get_total_amount();" class="price_float m_input_default"  id="discount" value="<?php if($pat_data!="empty") { echo number_format($pat_data['discount_amount'],2,'.', ''); } ?>">
                         </div>
                    </div>
               </div>
          </div>
        <?php } else { ?>
        <input type="hidden" name="discount" readonly="" id="discount" value="0">
        <?php } ?>
          <div class="row m-b-5">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Net Amount</b></div>
                         <div class="col-md-7">
                              <input type="text" readonly="" name="net_amount" class="price_float m_input_default" id="net_amount" value="<?php if($pat_data!="empty") { echo number_format($pat_data['net_amount'],2,'.', ''); } ?>">
                         </div>
                    </div>
               </div>
          </div> <!-- row -->
          
          
          
          
          <div class="row m-b-5">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Paid Amount</b></div>
                         <div class="col-md-7">
                              <input type="text" name="paid_amount" class="price_float m_input_default" id="paid_amount" onkeyup="set_paid_and_balance_amount(this.value);" value="<?php if($pat_data!="empty") { echo number_format($pat_data['paid_amount'],2,'.', ''); } ?>">
                         </div>
                    </div>
               </div>
          </div> <!-- row -->
          <div class="row m-b-5" >
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Balance</b></div>
                         <div class="col-md-7">
                              <input type="text" name="balance" id="balance" class="price_float" value="<?php if($pat_data!="empty") { echo number_format($pat_data['balance'],2,'.', ''); } ?>" >
                         </div>
                    </div>
               </div>
          </div> 

    </div>        
     </div>
     <div class="col-md-8"></div>
     <div class="col-sm-4" style="float:left;"> <!-- 4 -->
     <?php if($action=='new') {  ?>
       <input type="button" id="data_handler_sub" name="submit" value="Submit" class="btn-update su_ntn" onclick="save_issue_bag();return false;" style="float: left;">
       
             <button type="button" class="btn-update" onclick="window.location.href='<?php echo base_url('blood_bank/recipient'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>

    <?php } else {   ?>
    <input type="button" id="data_handler_sub" name="submit" value="Submit" class="btn-update" onclick="update_issue_bag();return false;" style="float: left;"> 
     <button type="button" class="btn-update" onclick="window.location.href='<?php echo base_url('blood_bank/recipient'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>  
     <?php } ?>
   </div>
   </div>

  </form>   
    </div>
</div>
</section> <!-- cbranch -->
  </div>
<?php
$this->load->view('include/footer');
?>
<script>

    $('#selectAll').on('click', function () { 
    if ($(this).hasClass('allChecked')) {
    $('.stock_checkbox_box').prop('checked', false);
    } else {
    $('.stock_checkbox_box').prop('checked', true);
    }
    $(this).toggleClass('allChecked');
    })
    </script>
<script type="text/javascript">

 function remove_particular_charge(id,reciepient_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('blood_bank/recipient/delete_charges/'); ?>"+id+'/'+reciepient_id, 
                 
                  success: function( data ) { var result = JSON.parse(data);
                  
                  if(result.success==1)
                    { 
                      window.location.href='<?php echo base_url('blood_bank/recipient/issue_component') ?>/'+result.recipient_id;
                   }
                }
              });
    });     
 } 
$(function() {
    $('input[name="issue_by_mode"]').on('click', function() {
        if ($(this).val() == '1') 
        {
            $('#textboxes').show();
            get_total_amount()
        }
        else 
        {
            $('#textboxes').hide();
            $('#shipment_charge').val(0);
            get_total_amount();
        }
    });
});


function change_price(id,vals)
{
  get_total_amount();
}


function get_total_amount()
{
  $("#overlay-loader").show();
  var total=0;
  $('.iss_cart_price').each(function(){
      total=total+parseInt($(this).val());
  });
  payment_calc(total);
}

function payment_calc(total_amount)
{
  var total_amount=parseInt(total_amount);
  var discount_percent=parseInt($("#discount_percent").val());
  var discount_amount=0;
  var paid_amount=parseInt($("#paid_percent").val());
  var net_amount =0;
  var shipment_charge= parseInt($("#shipment_charge").val());
  var balance=0;
  if(discount_percent!=0 && discount_percent!="" && !isNaN(discount_percent))
  {
    discount_amount = (  parseInt(discount_percent) * parseInt(total_amount) ) /100;
    net_amount = parseInt(total_amount) - parseInt(discount_amount);
  }
  else
  {
      var discount_amount =parseInt($("#discount").val()); 
      if(discount_amount!=0 && discount_amount!="" && !isNaN(discount_amount))
      {
          
          net_amount=total_amount-discount_amount;
      }
      else
      {
          net_amount=total_amount;
      }
    
  }
  
  

  if(shipment_charge!=0 && shipment_charge!="" && !isNaN(shipment_charge) )
  {
    net_amount=parseInt(shipment_charge)+parseInt(net_amount);
  }
  
  paid_amount=parseInt(net_amount); 
  
  
  paid_amount=parseInt(paid_amount); 

  balance=parseInt(net_amount)-parseInt(paid_amount);
  $("#total_amount").val(total_amount);
  $("#discount").val(discount_amount);
  $("#paid_amount").val(paid_amount);
  $("#net_amount").val(net_amount);
  
  $("#balance").val(balance);
  $("#overlay-loader").hide();
}

 function set_paid_and_balance_amount(val)
  {
      var paid_amount=$("#paid_amount").val();
      var net_amount=$("#net_amount").val();
      
       balance=parseInt(net_amount)-parseInt(paid_amount);
      //set_balance_amount=(vat_amount+net_amount)-paid_amount;
      $("#balance").val(balance);

  }

  function payment_function(value,error_field)
  {
           $('#updated_payment_detail').html('');
                $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('test/get_payment_mode_data')?>",
                     data: {'payment_mode_id' : value,'error_field':error_field},
                     success: function(msg){
                     $('#payment_detail').html(msg);
                     }
                });
                 $('.datepicker').datepicker({
           format: "dd-mm-yyyy",
           autoclose: true
           }); 
  }

              

function remove_component_from_bucket(id)
{

  var component_price=$("#comp_price_"+id).val();
  $("#data_tr_"+id).remove();
  var allVals_remain = [];
  $('.iss_cart_price').each(function()
  {
    var id=$(this).val();
    allVals_remain.push($(this).val());
  });
  if(allVals_remain.length <= 0 )
  {
    $("#data_handler_sub").css('display','none');
  }
   get_total_amount();
}

function get_donor_list()
{
    var allVals = [];
    //alert(allVals);
    $('.stock_checkbox').each(function()
    {
    var id=$(this).val();
    allVals.push($(this).val());
    });
    // loop to get ids that are added to purchase bucket
    //$("#overlay-loader").show();

    //var component_name=$("#component_name").val();
    var component_name = $('#component_name option:selected').text();
      var bar_code=$("#bar_code").val();
      var blood_group_id=$("#blood_group_id").val();
    if((component_name!='') ||(bar_code!=''))
    {
      $("#overlay-loader").show();
      $.ajax({
              "url": "<?php echo base_url(); ?>blood_bank/recipient/get_donor_list",
              "type": "POST",
              dataType:'json',
              "data":{'component_name':component_name,'blood_group_id':blood_group_id,'bar_code':bar_code,'vals':allVals},
              success: function(result)
              {
                if(result.st==1)
                {
                  $("#donor_data_id").html(result.option);
                }
              }
              
          });
    }
}

function get_stock_available(component_namess='')
{
  // loop to get ids that are added to purchase bucket
  var allVals = [];
   //alert(allVals);
  $('.stock_checkbox').each(function()
  {
    var id=$(this).val();
    allVals.push($(this).val());
  });
  // loop to get ids that are added to purchase bucket
  //$("#overlay-loader").show();

  //var component_name=$("#component_name").val();
  if(component_namess)
  {
       var component_name =  component_namess;
  }
  else
  {
     var component_name = $('#component_name option:selected').text(); 
  }
  
  var bag_type=$("#bag_id").val();
  var donor_id=$("#donor_id").val();
  var bar_code=$("#bar_code").val();
  var blood_group_id=$("#blood_group_id").val();
  
  if((component_name!='')||(bar_code!='')||(donor_id!=''))
  {

    $("#overlay-loader").show();
    $.ajax({
            "url": "<?php echo base_url(); ?>blood_bank/recipient/get_stocks_available",
            "type": "POST",
            dataType:'json',
            "data":{'component_name':component_name,'donor_id':donor_id,'bar_code':bar_code,'vals':allVals,'bag_type_id':bag_type,'blood_group_id':blood_group_id},
            success: function(result)
            {
              if(result.st==0)
              {
                $("#overlay-loader").hide();
                $("#get_issue").show();
                $("#table_tbody").html(result.msg);
                $("#data_handler").css('display','none');
              }
              else if(result.st==1)
              {
                $("#overlay-loader").hide();
                $("#get_issue").show();
                $("#table_tbody").html(result.data);
               // $("#donor_data_id").html(result.option);
                 //$("#donor_id").css('display','block');
                $("#data_handler").css('display','block');
              }
            }
        });
  }
  else
  {
      get_donar_list_according_blood_grp();
      //location.reload(true);
     $("#table_tbody").html('');
     $("#overlay-loader").hide();
  }

}
function get_donar_list_according_blood_grp()
{
   var option='';
    <?php
    if(!empty($donor_list))
    {
    foreach($donor_list as $donarlist)
    {?>

     option +='<option value="<?php echo $donarlist->id ?>_<?php echo $donarlist->blood_group?>_<?php echo $donarlist->blood_group_id ?>"><?php echo $donarlist->donor_code; ?></option>';
   <?php }
    }
    ?> 
   
    $('#donor_id').html(option);
}

// Function to save recipient Details
function add_to_cart()
{
  
  $("#data_handler_sub").css('display','block');
  $("#overlay-loader").show();
  var allVals = [];
  var allVals2 = [];
  if($('#cross_match').val()==1)
  {
     $('.cross_checkbox_box').each(function()
    {

        if($(this).prop('checked')==true)
        {

             var id=$(this).val();
             $("#cross_match_tr_"+id).remove();
             allVals.push($(this).val());
            
             
        }
    });
      var cross_match=1;
  }
  else
  {

    $('.stock_checkbox_box').each(function()
    {
      if($(this).prop('checked')==true)
      {
        var id=$(this).val();
        $("#stock_tr_"+id).remove();
        allVals.push($(this).val());
      
      }
    });
      var cross_match =0;
  }
  if(cross_match==0)
  {
    var cross_matchn =cross_match;
  }
  else
  {
    var cross_matchn =allVals;
  }
   if(allVals.length <= 0)
  {
    alert('Please select a row');
     $("#overlay-loader").hide();
  }
  else
  {
     $("#get_issue_bag").hide();
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>blood_bank/recipient/add_to_cart",
            dataType:'json', 
            data: {'vals':allVals,cross_match_id:cross_matchn},
            success: function(result)
            {    
              $("#overlay-loader").hide();
              if(result.st==1)
              {
                $("#get_issue_bag").show();
                $("#table_tbody_bag").append(result.data);
                get_total_amount();

                  var allVals_remain = [];
                  $('.stock_checkbox_box').each(function()
                  {
                    var id=$(this).val();
                    allVals_remain.push($(this).val());
                  });
                  if(allVals_remain.length <= 0 )
                  {
                    $("#data_handler").css('display','none');
                  }
              }
               }
          });
  } 
}

function save_issue_bag()
{

 var recipient_id =$("#recipient_id").val();
  //$("#overlay-loader").show();
  $.ajax({
            type: "POST",
           
            url:  "<?php echo base_url(); ?>blood_bank/recipient/save_issue_bag", 
            data: $("#issue_form").serialize(),
            success: function(result)
            {   

               $("#data_handler_sub").attr("disabled", true);
               //$('.su_ntn').attr('onclick','update_issue_bag()');
               print_data(recipient_id);
              
                window.location.href='<?php echo base_url('blood_bank/recipient/');?>';
              
            }
          });
}



function update_issue_bag()
{
  //('ddd');
  //$("#overlay-loader").show();
   var recipient_id =$("#recipient_id").val();

  $.ajax({
            type: "POST",
            
            url:  "<?php echo base_url(); ?>blood_bank/recipient/update_issue_bag", 
            data: $("#issue_form").serialize(),
            success: function(result)
            { 
               $("#data_handler_sub").attr("disabled", true);
                //print_data(recipient_id);
                
                window.location.href='<?php echo base_url('blood_bank/recipient/?status=print');?>'; 
                
            }
          });   
}

function print_data(recipient_id)
{

  var recipient_id =$("#recipient_id").val();
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .on('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('blood_bank/recipient/issue_component/');?>'+recipient_id; 
    }) ;

  $("#data_handler_sub").attr("disabled", false);
}


// Functions to Focus on popups
$(document).ready(function() {

  get_total_amount();
  $('#load_add_simulation_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
// Function to focus on popups

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
// Function to save Donor Details

</script>


<!---Div to load popups -->
<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!--Div to load popups -->
</body>
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
<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <!-- <a type="button" data-dismiss="modal" class="btn-anchor" id="delete" onClick="return openPrintnewWindow('< ?php echo base_url("purchase/print_purchase_recipt"); ?>', 'windowTitle', 'width=820,height=600');" target="_blank">Print</a> -->
             <?php $url=  base_url("blood_bank/recipient/print_issue_recipt/$recipient_id"); ?>
            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo $url; ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 
</html>
