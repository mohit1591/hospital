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
                    <th class="table-align">Lead ID</th>
                    <th class="table-align">Department</th>
                    <th class="table-align">Lead Type</th>
                    <th class="table-align">Source</th> 
                    <th class="table-align">Name</th>  
                    <th class="table-align">Phone</th>   
                    <th class="table-align">Follow-Up Date/Time</th> 
                    <th class="table-align">Appointment Date/Time</th> 
                    <th class="table-align">Last Remarks</th> 
                    <th class="table-align">Created By</th> 
                    <th class="table-align">Status</th> 

 </tr>
 <?php
   if(!empty($data_list))
   {
   	 
   	 $i=1;
   	 foreach($data_list as $leads)
   	 {
          
          
          $followup_date = '';
                    if(!empty($leads['followup_date']) && strtotime($leads['followup_date'])>1000000) 
                    {
                        $followup_date = date('d-m-Y', strtotime($leads['followup_date'])).' '.date('h:i A', strtotime($leads['followup_time']));
                    }
        
                    $appointment_date = '';
                    if(!empty($leads['appointment_date']) && strtotime($leads['appointment_date'])>1000000) 
                    {
                        $appointment_date = date('d-m-Y', strtotime($leads['appointment_date'])).' '.date('h:i A', strtotime($leads['appointment_time']));
                    }
                    
                    if($leads['department_id']=='-1')
                    {
                        $department_id = 'Vaccination';
                    }
                    elseif($leads['department_id']=='-2')
                    {
                        $department_id = 'Other';
                    }
                    else
                    {
                       $department_id = $leads['department'];
                    }
                    
                  
          
          ?>
   	    <tr>
   	       
            <td><?php echo $leads['crm_code']; ?></td>
            <td><?php echo $department_id; ?></td>
            <td><?php echo $leads['lead_type']; ?></td>
            <td><?php echo $leads['source']; ?></td>
            <td><?php echo $leads['name']; ?></td>
            <td><?php echo $leads['phone']; ?></td>
            <td><?php echo $followup_date; ?></td>
            
            <td><?php echo $appointment_date; ?></td>
            <td><?php echo $leads['last_remark']; ?></td>
            <td><?php echo $leads['uname']; ?></td>
            <td><?php echo $leads['current_status']; ?></td>
      		 
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>