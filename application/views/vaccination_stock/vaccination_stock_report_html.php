<html><head>
<title>Vaccination Stock Report</title>
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
*{margin:0;padding:0;box-sizing:border-box;}
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
<table width="100%" cellpadding="0" cellspacing="0" border="1" style="font:13px Arial;border-collapse: collapse;">
 <tr>
    <th>Sr.No.</th>
    <th>Vaccination Name</th>
    <th>Vaccination Company</th>
    <th>Packing</th>
    <th>Batch No.</th>
    <th>Quantity</th>
    <th>Expiry Date</th>
    <th>Rack No.</th>
    <th>Min Alrt</th>
   <!--  <th>MRP</th> -->
   <!--  <th>Purchase Rate</th> -->
    <th>Created Date</th>

    
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
   	   ?>
   	    <tr>
   	        <td><?php echo $i; ?>.</td>
      		 	<td><?php echo $data->vaccination_name; ?></td>
            <td><?php echo $data->company_name; ?></td>
      		 	<td><?php echo $data->packing; ?></td>
            <td><?php echo $data->batch_no; ?></td>
            <td align="right" style="padding-right:5px;">
              <?php $quantity= $this->vaccination_stock->get_batch_med_qty($data->id,$data->batch_no);
                if($quantity['total_qty']>0){
                  echo $quantity['total_qty']; 
                } else {
                  echo '0';
                }
              ?>

            </td>
      		 	<td><?php if($data->expiry_date!="0000-00-00"){echo date('d-m-Y',strtotime($data->expiry_date));}else{echo '';} ?></td>
      		 	<td align="right" style="padding-right:5px;"><?php echo $data->rack_nu; ?></td>
            <td><?php echo $data->min_alrt; ?></td>
      		 	<!-- <td align="right" style="padding-right:5px;"><?php //echo $data->per_pic_rate; ?></td> -->
           <!--  <td align="right" style="padding-right:5px;"><?php echo $data->purchase_rate; ?></td> -->
            <td><?php echo date('d-M-Y H:i A',strtotime($data->stock_created_date)); ?></td>
      		 	
      		 	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>