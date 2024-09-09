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
    <th>Sr.No.</th>
     <th>Issue Code</th>
      <th>Requirement Date</th>
    <th>Patient Name</th>
    <th> Donor Name </th> 
    <th>Donor Code </th> 
    <th>Blood Group</th>
    <th>Component</th>
    <th>Component Unit</th>
    
 </tr>
 
 <?php
   if(!empty($data_list))
   {
    
   	 $i=1;
     $tot_data=count($data_list);
   	 foreach($data_list as $reports)
   	 {
        if(!empty($reports->requirement_date))
		{
			$requirement_date = date('d-m-Y',strtotime($reports->requirement_date));
		} 
		else
		{
			$requirement_date = date('d-m-Y',strtotime($reports->requirement_date));
		}
    ?>
   	    <tr>
            <td align="center"><?php echo $i; ?></td>
            <td><?php echo $reports->patient_code; ?></td>
            <td><?php echo $requirement_date;  ?></td>
            <td><?php echo $reports->patient_name; ?></td>
            
            <td><?php echo $reports->donor_name; ?></td>
            <td><?php echo $reports->donor_code; ?></td>
            
            <td><?php echo $reports->blood_group; ?></td>
            <td><?php echo $reports->component; ?></td>
            <td><?php echo $reports->unit_qty; ?></td>
            
		 </tr>
   	   <?php
       
   	   $i++;	
   	 }
      
   }
 ?> 
</table>
</body></html>