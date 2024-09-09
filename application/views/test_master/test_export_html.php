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
<table width="100%" cellpadding="0" cellspacing="0" border="1px">
 <tr>
      <th> Test Code </th>
      <th> Test name </th>
      <th> Test Type </th> 
      <th> Department </th>
      <th> Test Heads </th> 
      <th> Unit </th>
      <th> Price </th> 
      <th>Sort Order</th>
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 
   	 $i=1;
   	 $test_type_arr = array('0'=>'Normal', '1'=>'Hading');
     foreach($data_list as $test_master)
   	 {
           
   	   ?>
   	    <tr> 
            <td style="text-align: left;"><?php echo $test_master->test_code; ?></td>
            <td style="text-align: left;"><?php echo $test_master->test_name; ?></td>
            <td style="text-align: left;"><?php echo $test_type_arr[$test_master->test_type_id]; ?></td>
            <td style="text-align: left;"><?php echo $test_master->department; ?></td> 
            <td style="text-align: left;"><?php echo $test_master->test_heads; ?></td>
            <td style="text-align: left;"><?php echo $test_master->unit; ?></td> 
             <td style="text-align: left;"><?php echo $test_master->rate; ?></td> 
              <td style="text-align: left;"><?php echo $test_master->sort_order; ?></td>   
        </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>