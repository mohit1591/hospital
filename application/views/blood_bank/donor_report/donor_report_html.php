<html><head>
<title>Blood Bank Collection Report</title>
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
<style>
body
{
  font-size: 10px;
} 
td
{
  padding-left:3px;
  font:13px Arial;
}
</style>
</head><body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:13px Arial !important;">
 <tr>
     <th>S.No.</th>
   <th> Donor Name </th>
                   
                    <th> Donor Code </th>
                    <th> Mobile No. </th> 
                    
                    <th> Blood Group </th>
                    <th>Component</th>
                    <th>Qty</th>
                    <th> Expiry Date </th>
                    <th> Status </th>
 </tr>
 <?php
   if(!empty($data_list))
   {
    
   	 $i=1;
     $tot_data=count($data_list);
   	 foreach($data_list as $reports)
   	 {
   	     
   	     $alert_days = get_setting_value('BLOODBANK_COMPONENT_EXPIRY_DAY');
            $expire_timestamp = strtotime($reports->expiry_date)-(86400*$alert_days);
            $expire_alert_days = date('Y-m-d',$expire_timestamp);
            $current_date = date('Y-m-d');
            $expire_date = date('d-m-Y H:i:s',strtotime($reports->expiry_date));
            if($reports->expiry_date!='0000-00-00' && $reports->expiry_date!='00-00-0000' && $expire_date!='01-01-1970' && $expire_date!='00-00-0000' && $expire_date!='0000-00-00')
            {
              if($current_date>=$expire_alert_days)
              {
                $expire_date = $expire_date;  
              }
              else
              {
                $expire_date = $expire_date; 
              }
              
            }
            else
            {
              $expire_date = ''; 
            }
            
            $status_flag='';  
            if($reports->flag==2)
            {
               $status_flag='Issued'; 
               
            }
            else if($reports->flag==1)
            {
                //echo $qty;
               
                $check_beg_qcy= check_beg_qc($reports->donor_id);
                //print_r($check_beg_qcy);  die;
                if(strtotime(date('d-m-Y H:i:s',strtotime($reports->expiry_date)))<strtotime(date('d-m-Y H:i:s')) && $check_beg_qcy[0]->blood_condition!=2)
                {
                     $status_flag="Expired";
                }
               
                else if(count($check_beg_qcy)>0)
                {
                   
                   if($check_beg_qcy[0]->blood_condition==2)
                   {
                        $status_flag="Discard"; 
                   }
                   else
                   {
                       $status_flag="Tested"; 
                   }
                }
                else
                {
                    $status_flag="Untested";
                }
               
                
            }
   	    
           
         ?>
   	    <tr>
   	        <td align="center"><?php echo $i; ?></td>
            <td><?php echo $reports->donor_name; ?></td>
            <td><?php echo $reports->donor_code; ?></td>
      		 	<td><?php echo  $reports->mobile_no; ?></td>
      		 	
      		 	<td><?php echo  $reports->blood_group; ?></td>
      		 	<td><?php echo $reports->component_name; ?></td>
      		 	
      		 	<td><?php echo $reports->qty; ?></td>
      		 	<td><?php echo $expire_date; ?></td>
      		 	<td><?php echo $status_flag; ?></td>
      		 	
		 </tr>
   	   <?php
      
   	   $i++;	
   	 }
         
   }
 ?> 
</table>
</body></html>