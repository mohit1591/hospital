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
    min-height: 29.7cm !important;  
    padding: 3em;
    font-size:13px;
    overflow-y:auto;
  }
</style>
</head>
<body style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">

  <page size="A4">
    
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
        <td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">GST Report</span></td>
      </tr>
      <tr>
        <td style="text-align:center;font-size:13px;padding:1em;">
          <strong>From</strong>
          <span><?php echo $get['start_date']; ?></span>
          <strong>To</strong>
          <span><?php echo $get['end_date']; ?></span>
        </td>
      </tr>
    </table>
    <!-- Branch list start -->

    <div style="float:left;width:100%;border:1px solid #111;font-size:13px;">
      <div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Sr.No.</u></div>
      <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Purchase No.</u></div>
      <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Vendor Name</u></div>
      <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Tot.Amt.</u></div>

      <div style="float:left;width:10%;font-weight:600;padding:4px;text-align:right;"><u>CGST Rs.</u></div>

      <div style="float:left;width:10%;font-weight:600;padding:4px;text-align:right;"><u>SGST Rs.</u></div>
     
      <div style="float:left;width:10%;font-weight:600;padding:4px;text-align:right;"><u>IGST Rs.</u></div>
    </div> 

    <div style="float:left;width:100%;border:1px solid #111;border-top:none;font-size:13px;"> 
 
   
  <?php if(!empty($branch_gst_list)) { ?>

    <?php 

    $i = 1;   
  
      foreach($branch_gst_list as $branchs)
      {   
     
      ?>    
      <div style="float:left; width: 100%">
      <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>
      <div style="float:left;width:20%;padding:1px 4px;"><?php echo $branchs->purchase_id; ?></div>
      <div style="float:left;width:20%;padding:1px 4px;"><?php echo $branchs->vendor_name; ?></div>
      <div style="float:left;width:20%;padding:1px 4px;"><?php echo $branchs->total_amount; ?></div>
      <div style="float:left;width:10%;padding:1px 4px;text-align:right;"><?php echo $branchs->cgst;  //$pay_mode[$branchs->pay_mode]; ?></div>
      <div style="float:left;width:10%;padding:1px 4px;text-align:right;"><?php echo $branchs->sgst;  //$pay_mode[$branchs->pay_mode]; ?></div>
      <div style="float:left;width:10%;padding:1px 4px;text-align:right;"><?php echo $branchs->igst;  //$pay_mode[$branchs->pay_mode]; ?></div>

      </div>    



      <?php $i++; } 

      } ?>  

    </div>
        
  </page>

</body>
</html>