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
        <td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Source Report</span></td>
      </tr>
      <tr>
        <td style="text-align:center;font-size:13px;padding:1em;">
          <strong>From</strong>
          <span><?php echo $get['start_date']; ?></span>
          <strong>To</strong>
          <span><?php echo $get['end_date']; ?></span>
        </td>
      </tr>
    <table>
        



        <?php
    
        if(!empty($branch_source_from_list))
        { 
          $branch_names = []; 
          foreach($branch_source_from_list as $names)
          {
             $branch_names[] = $names->branch_name;
          }  
        ?>
      <div style="float:left;width:100%;border:1px solid #111;font-size:13px;">
      <div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Sr.No.</u></div>
      <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Source</u></div>
      <div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Total</u></div>
      <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>No. of App.</u></div>
      <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>No. of Booking</u></div>
      <div style="float:left;width:20%;font-weight:600;padding:4px;text-align:right;"><u>No. of Billing</u></div>
    </div> 
    <div style="float:left;width:100%;border:1px solid #111;border-top:none;font-size:13px;"> 
      
  <?php
  if(!empty($branch_names))
  {
  foreach($branch_names as $names)
  {
    ?>
    <div style="float:left;width:100%;font-weight:600;padding:4px;">
       <span style="border-bottom:1px solid #111;">Branch : <?php echo $names; ?></span>
  </div>  
  <?php if(!empty($branch_source_from_list)) { ?>
  
    <?php 

    $i = 1;   
  $branch_total = 0;  
  $count_branch = count($branch_source_from_list);
  $n_bnc = '';
    foreach($branch_source_from_list as $branchs)
    {   
     if($names == $branchs->branch_name)  
     {
    ?>    
  <div style="float:left; width: 100%">
   <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>
    <div style="float:left;width:20%;padding:1px 4px;"><?php echo $branchs->source; ?></div>
    <div style="float:left;width:10%;padding:1px 4px;"><?php echo $branchs->total; ?></div>
    <div style="float:left;width:20%;padding:1px 4px;"><?php echo $branchs->total_enquiry; ?></div>
    <div style="float:left;width:20%;padding:1px 4px;"><?php echo $branchs->total_booking; ?></div>
    
    <div style="float:left;width:20%;padding:1px 4px;text-align:right;border-bottom:1px solid #111';"><?php echo $branchs->total_billing; ?></div>
  </div>    
  <?php
  $i++;
  
     }
  }
} 
}
}
    
?>     
</div>
<?php  
}
?>          
          <div style="float:left; margin-top:10px; width:100%;border:1px solid #111;border-top:none;font-size:13px;border-bottom:none;">        
        
          <div style="float:left;width:100%;border:1px solid #111;border-left:none;border-right:none;font-size:13px;">
            <div style="float:left;width:5%;font-weight:600;padding:4px;border-left:none;border-right:none;"><u>S.No.</u></div>
            <div style="float:left;width:15%;font-weight:600;padding:4px;border-left:none;border-right:none;"><u>Patient Name</u></div>
            <div style="float:left;width:5%;font-weight:600;padding:4px;border-left:none;border-right:none;"><u>Age</u></div>             
            <div style="float:left;width:15%;font-weight:600;padding:4px;text-align:right;border-left:none;border-right:none;"><u>Mobile No.</u></div>
            <div style="float:left;width:20%;font-weight:600;padding:4px;text-align:right;border-left:none;border-right:none;"><u>Reference</u></div>
            <div style="float:left;width:10%;font-weight:600;padding:4px;text-align:right;border-left:none;border-right:none;"><u>Location</u></div>
            <div style="float:left;width:10%;font-weight:600;padding:4px;text-align:right;border-left:none;border-right:none;"><u>Pickup</u></div>
            <div style="float:left;width:10%;font-weight:600;padding:4px;text-align:right;border-left:none;border-right:none;"><u>Drop</u></div>
            <div style="float:left;width:10%;font-weight:600;padding:4px;text-align:right;border-left:none;border-right:none;"><u>Remarks</u></div>
          </div>
      <div style="float:left;width:100%;border:1px solid #111;border-top:none;font-size:13px;">
       <div style="float:left;width:100%;font-weight:600;padding:4px;">
        <span style="border-bottom:1px solid #111;">Branch : Self</span>
      </div> 
       
        <?php
        if(!empty($appointment_list))
        {
        $l = 1 ;
        $source_total = 0;
        $source_counter = count($appointment_list);
        
        $date_array = array_unique(array_column($appointment_list,'booking_date'));
        //print_r($date_array); die;
              
        foreach($appointment_list as $source_report)
        { 
          if(in_array($source_report['booking_date'],$date_array))
          {
          ?>
           <div style="float:left; width: 100%">
            <div style="float:left;width:%;padding:1px 4px;text-indent:15px;"><b><?php echo date('d-m-Y',strtotime($source_report['booking_date'])); ?></b></div>

            </div> 
          <?php   
           $used_date = array_search($source_report['booking_date'],$date_array);
           unset($date_array[$used_date]);
          } 
              
          ?>
            
        <div style="float:left; width: 100%">
            <div style="float:left;width:5%;padding:1px 4px;text-indent:15px;"><?php echo $l; ?></div>
            <div style="float:left;width:15%;padding:1px 4px;"><?php echo $source_report['patient_name']; ?></div>
            <div style="float:left;width:5%;padding:1px 4px;"><?php echo $source_report['age_y']; ?></div>
            <div style="float:left;width:15%;padding:1px 4px;text-align:right;border-left:none;border-right:none;"><?php echo $source_report['mobile_no']; ?></div>
            <div style="float:left;width:20%;padding:1px 4px;text-align:right;border-left:none;border-right:none;"><?php echo $source_report['doctor_name']; ?></div>
            <div style="float:left;width:10%;padding:1px 4px;text-align:right;border-left:none;border-right:none;"><?php echo $source_report['city']; //state ?></div>
            <div style="float:left;width:10%;padding:1px 4px;text-align:right;border-left:none;border-right:none;"><?php echo $source_report['source']; //state ?></div>
            <div style="float:left;width:10%;padding:1px 4px;text-align:right;border-left:none;border-right:none;"><?php echo $source_report['destination']; //state ?></div>
            <div style="float:left;width:10%;padding:1px 4px;text-align:right;border-left:none;border-right:none;"><?php echo $source_report['remark']; ?></div>
        </div>
                 <?php
                 $l++;  
                 } 
                ?>
          
                </div>
                <?php 
                }
        ?> 
        </div>
        
  </page>

</body>
</html>