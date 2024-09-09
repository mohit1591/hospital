<html><head>
<title>Schedule medicines Report</title>
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
  padding-left:5px !important;;
  font:14px Arial;
}

</style>
</head><body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:14px Arial !important;">
    <tr><td colspan="10" align="center"><h3> Schedule medicines Report </h3></td></tr>
 <tr>
      <th width="40" align="center">S.No.</th> 
        <th> Bill No. </th>  
        <th> Doctor/Hospital Name </th>  
        <th> Patient Name </th>  
        <th> Medicine Name </th>
        <th> Quantity </th>  
        <th> Manuf. Company </th>  
        <th> Batch No. </th>  
        <th> Exp. Date </th>
        <th> Bill Date </th>
 </tr>
 <?php 
   if(!empty($report_list))
   { 
     $k=1;
     foreach($report_list as $data_list)
     {
         $sale_no = '';
         $patient_name = '';
         $bill_date = '';
         $kk = '';
         if(!in_array($data_list->sale_id, $sale_id_arr))
         {
             $sale_no = $data_list->sale_no;
             $patient_name = $data_list->patient_name;
             $bill_date = date('d-m-Y h:i A', strtotime($data_list->created)); 
             $kk = $k;
         }
         $doctor_name = '';
         if($data_list->referred_by==0)
         {
             if(!empty($data_list->doctor_name))
             {
                 $doctor_name = $data_list->doctor_name;
             }
         }
         else if($data_list->referred_by==1)
         {
             if(!empty($data_list->hospital_name))
             {
                 $doctor_name = $data_list->hospital_name;
             }
         }
         

       ?>
      <tr>
        <td align="center"><?php echo $kk; ?></td>
        <td><?php echo $sale_no; ?></td>
        <td><?php echo $doctor_name; ?></td>
        <td><?php echo $patient_name; ?></td>
        <td><?php echo $data_list->medicine_name; ?></td>
        <td><?php echo $data_list->qty; ?></td>
        <td><?php echo $data_list->company_name; ?></td> 
        <td><?php echo $data_list->batch_no; ?></td>
        <td><?php echo date('d-m-Y', strtotime($data_list->expiry_date)); ?></td>
        <td><?php echo $bill_date ?></td>
     </tr>
       <?php
       if(!in_array($data_list->sale_id, $sale_id_arr))
        {
            $k++;
            $sale_id_arr[] = $data_list->sale_id; 
        }
       if($tot_data==$i){?>
        
     <?php }
       $i++;  
     }
     ?>  

   <?php       
   }
 ?> 
</table>
</body></html>