<html><head>
<title>Medicine Kit Stock Report</title>

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
    <!-- <th>Sr.No.</th> -->
    <th>Medicine Kit Name.</th>
    <th>Amount</th>
    <th>Quantity</th>
    <th>Created Date</th>

    
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
          $qty_data = $this->packages->get_med_kit_qty($data->id,$data->medicine_kit_stock_branch_id);
          //  if(empty($packages->qty_kit))
          // {
          //      $qty_data=0;
          // }
          // else
          // {
          //      $qty_data = $packages->qty_kit;
          // }
            if(empty($qty_data['total_qty']))
          {
               $qty_data=0;
          }
          else
          {
               $qty_data = $qty_data['total_qty'];
          }
             
             
   	   ?>
   	    <tr>
   	      <!--   <td><?php //echo $i; ?>.</td> -->
      		<td><?php echo $data->title; ?></td>
               <td><?php echo $data->amount; ?></td>

      		<td><?php echo $qty_data; ?></td>
               <td><?php echo date('d-M-Y H:i A',strtotime($data->created_date)); ?></td>
      		 	
          </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>