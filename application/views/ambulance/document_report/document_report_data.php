<!DOCTYPE html>
<html>
<head>
<title></title>
<style>
  *{margin:0;padding:0;box-sizing:border-box;-webkit-box-sizing:border-box;}
  page{
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
        <td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Document Report</span></td>
      </tr>
     <!--  <tr>
        <td style="text-align:center;font-size:13px;padding:1em;">
          <strong>From</strong>
          <span><?php echo $get['start_date']; ?></span>
          <strong>To</strong>
          <span><?php echo $get['end_date']; ?></span>
        </td>
      </tr> -->
    </table>
      
          <div style="float:left; margin-top:10px; width:100%;border:1px solid #111;border-top:none;font-size:13px;border-bottom:none;">        
        
          <div style="float:left;width:100%;border:1px solid #111;border-left:none;border-right:none;font-size:13px;">
            <div style="float:left;width:8%;font-weight:600;padding:4px;border-left:none;border-right:none;"><u>S.No.</u></div>
            <div style="float:left;width:13%;font-weight:600;padding:4px;border-left:none;border-right:none;"><u>Vehicle No</u></div>
            <div style="float:left;width:13%;font-weight:600;padding:4px;border-left:none;border-right:none;"><u>Document</u></div>
            <div style="float:left;width:13%;font-weight:600;padding:4px;border-left:none;border-right:none;"><u>Renewal Date</u></div>             
            <div style="float:left;width:13%;font-weight:600;padding:4px;text-align:right;border-left:none;border-right:none;"><u>Expiry Date</u></div>
            <div style="float:left;width:20%;font-weight:600;padding:4px;text-align:right;border-left:none;border-right:none;"><u>Created Date</u></div>
            <div style="float:left;width:20%;font-weight:600;padding:4px;text-align:right;border-left:none;border-right:none;"><u>Remarks</u></div>
          </div>
      <div style="float:left;width:100%;border:1px solid #111;border-top:none;font-size:13px;">
        <?php
        if(!empty($documents_list))
        {
        $l = 1 ;
        foreach($documents_list as $source_report)
        { ?>
            
        <div style="float:left; width: 100%">
            <div style="float:left;width:8%;padding:1px 4px;text-indent:15px;"><?php echo $l; ?></div>
            <div style="float:left;width:13%;padding:1px 4px;"><?php echo $source_report['vehicle_no']; ?></div>
            <div style="float:left;width:13%;padding:1px 4px;"><?php echo $source_report['document']; ?></div>
            <div style="float:left;width:13%;padding:1px 4px;text-align:right;border-left:none;border-right:none;"><?php echo date('d-m-Y', strtotime($source_report['renewal_date'])); ?></div>
            <div style="float:left;width:13%;padding:1px 4px;text-align:right;border-left:none;border-right:none;"><?php echo date('d-m-Y', strtotime($source_report['expiry_date'])); ?></div>
            <div style="float:left;width:20%;padding:1px 4px;text-align:right;border-left:none;border-right:none;"><?php echo date('d-m-Y', strtotime($source_report['created_date'])); ?></div>
            <div style="float:left;width:20%;padding:1px 4px;text-align:right;border-left:none;border-right:none;"><?php echo $source_report['remarks']; ?></div>
        </div>
                 <?php
                 $l++;  
                 } }
                ?>
          </div>
        </div>
        
  </page>

</body>
</html>