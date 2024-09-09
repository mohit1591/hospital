<html>
<head>
<title></title>
<script type="text/javascript">
window.print();
window.onfocus=function(){ window.close();}
</script>

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
    <th>Particular</th>
    <th>Charge</th>
 </tr>
 <?php
   if(!empty($particular_list))
   {
    //echo "<pre>";print_r($particular_list); die;
   	 $i=1;
   	 foreach($particular_list as $particularlist)
   	 {
      ?>
   	    <tr>
            <td><?php echo $particularlist['particular']; ?></td>
            <td><?php echo round($particularlist['particular_charge']); ?></td>
      </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
   else
   { ?>
          <tr class="append_row"><td class="text-danger" colspan="15"><div class="text-center">No record found</div></td></tr>
      <?php } ?> 
</table>
</body>
</html>