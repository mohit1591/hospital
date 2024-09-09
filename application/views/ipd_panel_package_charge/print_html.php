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
    <th>Package</th>
    <th>Charge</th>
 </tr>
 <?php
   if(!empty($package_list))
   {
   	 $i=1;
   	 foreach($package_list as $packagelist)
   	 {
      ?>
   	    <tr>
            <td><?php echo $packagelist['name']; ?></td>
            <td><?php echo round($packagelist['package_charge']); ?></td>
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