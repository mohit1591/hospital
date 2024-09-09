<!DOCTYPE html>
<html>
<head>
<title></title>
<style>
  *{margin:0;padding:0;box-sizing:border-box;-webkit-box-sizing:border-box;}
  page {
    background: white;
    display: block;
    margin: 1em auto 0;
    margin-bottom: 0.5cm;
    box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
  }
  page[size="A4"] {  
                        width: 21cm;
			min-height: 29.7cm;
			padding: 3em;
			font-size: 13px;
			float: left;
  }
    @page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
</style>
</head>
<body style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">

  <page size="A4">
    
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
        <td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Banking Report</span></td>
      </tr>
      <tr>
        <td style="text-align:center;font-size:13px;padding:1em;">
          <strong>From</strong>
          <span><?php echo $get['start_date']; ?></span>
          <strong>To</strong>
          <span><?php echo $get['end_date']; ?></span>
        </td>
         <td><input type="button" name="button_print" value="Print" id="print" onClick="return my_function();"/></td>
      </tr>
    </table>
      <div style="float:left;width:100%;border:1px solid #111;font-size:13px;border-bottom: none;">
        <div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Sr.No.</u></div>
        <div style="float:left;width:35%;font-weight:600;padding:4px;"><u>Bank A/C</u></div>
        <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>A/C Name</u></div> 
        
        <div style="float:left;width:15%;font-weight:600;padding:4px 1em 4px 4px;text-align:right;"><u>Deposit Date</u></div>
        <div style="float:left;width:15%;font-weight:600;padding:4px;text-align: right;"><u>Amount</u></div>

        </div> 
 <?php if(!empty($self_branch_bank_list) && isset($self_branch_bank_list)){ ?>

      <!-- self branch -->

      <div style="float:left;width:100%;  border:solid 1px #000;  padding:6px 4px;  text-decoration: underline;"><b>Branch Name: Self</b>

      

    <div style="float:left;width:100%;font-size:13px;"> 
 
   
  <?php if(!empty($self_branch_bank_list)&& isset($self_branch_bank_list)) { ?>

    <?php 

    $i = 1;   
  
      foreach($self_branch_bank_list as $branchs)
      {   
    //print '<pre>';print_r($branchs);
      ?>    
      <div style="float:left; width: 100%">
      <div style="float:left;width:15%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>
      <div style="float:left;width:35%;padding:1px 4px;"><?php echo $branchs->bank_name.'/'.$branchs->account_no; ?></div>
      <div style="float:left;width:20%;padding:1px 4px;"><?php echo $branchs->account_holder; ?></div> 
       
      <div style="float:left;width:15%;padding:1px 4px;"><?php echo date('d-m-Y',strtotime($branchs->deposite_date));  //$pay_mode[$branchs->pay_mode]; ?></div>
       <div style="float:left;width:15%;padding:1px 4px; text-align: right;"><?php echo $branchs->amount; ?></div>
     

      </div>    


        
      <?php  $i++; } }  ?>



      

    </div>
  <?php if(!empty($banking_according_total[$parent_id]) && isset($banking_according_total[$parent_id])) {?>
    <div style="float:left width:100%;">
       <div style="float:right;width:40%; margin-top:10px;  border-top:solid 1px #000;"> 
       <div style="float:left; width:40%;font-weight:bold;">Bank A/C No.</div>
         <div style="float:right; width:60%;font-weight:bold;text-align: right;">Total Amount</div>
         <?php foreach($banking_according_total[$parent_id] as $bank_tot){ ?>
          <div style="float:left; width:60%"><?php echo $bank_tot->name_account_no;?></div>
          <div style="float:right; width:40%;text-align: right;"><?php echo $bank_tot->total_amount;?></div>
        <?php }?>
       </div>

    </div>
      <?php }?>
      </div>

      <!-- selft branch -->




        <?php } ?>

  
    <!-- Branch list start -->

    <?php  
    if(isset($branch_bank_collection_list['bank_name']) && !empty($branch_bank_collection_list['bank_name'])){

    //print '<pre>';print_r($branch_bank_collection_list['bank_name']);
    foreach($branch_bank_collection_list['bank_name']as $branchs)
      { 
       // print '<pre>';print_r($branchs);?>
    <div style="float:left;width:100%;  border:solid 1px #000;  padding:6px 4px;  text-decoration: underline;"><b style="margin-bottom:3px;float:left;text-decoration: underline;">Branch Name: <?php echo ucfirst($branchs['branch_name']);?></b>

   

    <div style="float:left;width:100%;font-size:13px;"> 
 
   
  <?php if(!empty($branchs['result'])) { ?>

    <?php 

    $i = 1;   
  
      foreach($branchs['result'] as $branchs)
      {   
    //print '<pre>';print_r($branchs);
      ?>    
      <div style="float:left; width: 100%;">
      <div style="float:left;width:15%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>
      <div style="float:left;width:35%;padding:1px 4px;"><?php echo $branchs['bank_name'].'/'.$branchs['account_no']; ?></div>
      <div style="float:left;width:20%;padding:1px 4px;"><?php echo $branchs['account_holder']; ?></div> 
      
      <div style="float:left;width:15%;padding:1px 1em 1px 4px;text-align:right;"><?php echo date('d-m-Y',strtotime($branchs['deposite_date']));  //$pay_mode[$branchs->pay_mode]; ?></div>
        <div style="float:left;width:15%;padding:1px 4px;text-align: right;"><?php echo $branchs['amount']; ?></div>
     

      </div>    



      <?php  $i++; } }  ?>



      

    </div>
  <?php if(!empty($banking_according_total[$branchs['branch_id']])) {?>
    <div style="float:left width:100%;">
       <div style="float:right;width:40%; margin-top:10px;  border-top:solid 1px #000;"> 
       <div style="float:left; width:40%;font-weight:bold;">Bank A/C No.</div>
         <div style="float:right; width:60%;font-weight:bold;text-align: right;">Total Amount</div>
         <?php foreach($banking_according_total[$branchs['branch_id']] as $bank_tot){ ?>
          <div style="float:left; width:60%"><?php echo $bank_tot->name_account_no;?></div>
          <div style="float:right; width:40%;text-align: right;"><?php echo $bank_tot->total_amount;?></div>
        <?php }?>
       </div>

    </div>
      <?php }?>  
      </div>
      <?php } }?>

     
  </page>

</body>
</html>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
 <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

<script>
$(document).ready(function(){
 
});
function expenses_report()
{ 
   
    //var branch_id = '<?php echo $_GET['branch_id']; ?>';//$('#sub_branch_id').val();
      var start_date = '<?php echo $get['start_date']; ?>';
      var end_date = '<?php echo $get['end_date']; ?>';
       //$("#print").css('display','none')
      $("#print").removeAttr("onClick");
      $("#print").attr("onClick","return my_function();");
      //$("#print").css('display','none');

     
        
  // window.open('<?php echo base_url('reports/print_opd_expenses_reports?') ?>branch_id='+branch_id+'&start_date='+start_date+'&end_date='+end_date,'mywin','width=800,height=600');

} 

function my_function()
{
 $("#print").hide();
  window.print();
}
</script>