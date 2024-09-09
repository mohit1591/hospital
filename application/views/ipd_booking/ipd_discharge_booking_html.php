<html>
<head>
<title>IPD Booking</title>
<?php
if($print_status==1)
{
?>
<script type="text/javascript">
window.print();
window.onfocus=function(){ window.close();}
</script>
<?php	
}
?>
</head>
<style>
body
{
	font-size: 10px;
}	
td
{
	padding-left:3px;
}
</style>
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:13px Arial;">
 <tr>
    <th>S.No.</th>
    <th>Bill No.</th>
    <th>IPD No.</th> 
    <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th> 
    <th>Patient Name </th> 
    <th>Mobile No.</th>
    <th>Age</th>
    <th>Gender</th> 
    <th>Admission</th>
    <th>Discharge Date</th>
    <th>Total</th> 
    <th>Discount</th> 
    <th>Paid Amount</th>
    <th>Balance</th>
    <th>Type </th>
    
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $reports)
   	 {
      
                $gender = array('0'=>'Female', '1'=>'Male','2'=>'Others');
                $age_y = $reports->age_y;
                $age_m = $reports->age_m;
                $age_d = $reports->age_d;
                $age_h = $reports->age_h;
                $age = "";
                if($age_y>0)
                {
                $year = 'Years';
                if($age_y==1)
                {
                  $year = 'Year';
                }
                $age .= $age_y." ".$year;
                }
                if($age_m>0)
                {
                $month = 'Months';
                if($age_m==1)
                {
                  $month = 'Month';
                }
                $age .= ", ".$age_m." ".$month;
                }
                if($age_d>0)
                {
                $day = 'Days';
                if($age_d==1)
                {
                  $day = 'Day';
                }
                $age .= ", ".$age_d." ".$day;
                }
                if($age_h>0)
                {
                $hours = 'Hours';
                
                $age .= " ".$age_h." ".$hours;
                } 

            $admission_discharge = date('d-m-Y',strtotime($reports->admission_date)).' '.date('h:i:A',strtotime($reports->admission_time)); 
            $discharge_dates = date('d-m-Y H:i A',strtotime($reports->discharge_date));
            if($reports->patient_type==1)
            {
              $patient_type='Normal';
            }
            else if($reports->patient_type==2)
            {
              $patient_type='Panel';
            }

$ipd_total_amount  = $reports->total_amount_dis_bill;
            $Total_amt = $ipd_total_amount;
            //$row[] = $reports->paid_amount_dis_bill;
            $IPD_refund_amount='0';
            if($reports->ipd_refund_amount>0)
            {
              $IPD_refund_amount = $reports->ipd_refund_amount;
            }

           $discount_amount = $reports->discount_amount_dis_bill;
            //$ipd_paid_amount = $ipd_booking->ipd_paid_amount;
            
            $discount = $discount_amount;
            //$row[] = $ipd_booking->paid_amount_dis_bill;
           
            //$row[] = $ipd_booking->ipd_paid_amount;
           $IPD_refund_amount='0';
            if($reports->ipd_refund_amount>0)
            {
              $IPD_refund_amount = $reports->ipd_refund_amount;
            }
            $paid_amounts = $reports->ipd_paid_amount-$IPD_refund_amount;
            if(!empty($paid_amounts))
            {
              $paid_amounts = number_format($paid_amounts,2);
            }
            $paid_Amount = $paid_amounts;

            if($reports->ipd_balance_amount<0)
            {
              $ipdBalanceAmount = '0.00';//$ipd_booking->balance_amount_dis_bill;
            }
            else
            {
             // $ipdBalanceAmount = $ipd_booking->ipd_balance_amount;
              $pay_amt = $reports->ipd_paid_amount-$IPD_refund_amount;
              $ipdBalanceAmount = $ipd_total_amount-($pay_amt+$discount_amount);
              $ipdBalanceAmount = number_format($ipdBalanceAmount,2);
            }
            $Balance_amt = $ipdBalanceAmount;
   	   ?>
   	    <tr>

   	        <td><?php echo $i; ?></td>
      		 	<td><?php echo $reports->discharge_bill_no; ?></td>
            <td><?php echo $reports->ipd_no; ?></td>
      		 	<td><?php echo $reports->patient_code; ?></td>
      		 	<td><?php echo $reports->patient_name; ?></td>
      		 	<td><?php echo $reports->mobile_no; ?></td>
      		 	<td><?php echo $age; ?></td>
      		 	<td><?php echo $gender[$reports->gender]; ?></td>
            <td><?php echo $admission_discharge; ?></td>
            <td><?php echo $discharge_dates; ?></td>
            <td><?php echo $Total_amt; ?></td>
            <td><?php echo $discount; ?></td>
            <td><?php echo $paid_Amount; ?></td>
            <td><?php echo $Balance_amt; ?></td>
            <td><?php echo $patient_type; ?></td>
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body>
</html>