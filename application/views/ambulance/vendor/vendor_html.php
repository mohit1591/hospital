<html><head>
<title>Patient Report</title>
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
}
</style>
</head><body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:13px Arial;">
 <tr>
   <th> Vendor Code </th> 
                    <th> Vendor Name </th> 
                    <th> Vendor Type</th>  
                    <th> Mobile No.</th>  
                    <th> Email</th>
                    <th> Status </th> 
                    <th> Created Date </th> 

 </tr>
 <?php
   if(!empty($data_list))
   {
   	 
   	 $i=1;
   	 foreach($data_list as $vendor)
   	 {
          
          if($vendor->vendor_type==5)
                    {
                    $vendor_type = 'Ambulance';
                    }   
                    else if($vendor->vendor_type==2)
                    {
                    $vendor_type = 'Expense';
                    }
                   
                    else
                    {
                      $vendor_type='';
                    }
                    
                    if($vendor->status==1)
                    {
                        $status = 'Active';
                    }   
                    else{
                        $status = 'Inactive';
                    }
            
                  
   	   ?>
   	    <tr>
   	       
                <td><?php echo $vendor->vendor_id; ?></td>
                <td><?php echo $vendor->name; ?></td>
                <td><?php echo $vendor_type; ?></td>
                <td><?php echo $vendor->mobile; ?></td>
                <td><?php echo $vendor->email; ?></td>
                <td><?php echo $status; ?></td>
                <td><?php echo date('d-M-Y H:i A',strtotime($vendor->created_date)); ?></td>
      		 	
          
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>