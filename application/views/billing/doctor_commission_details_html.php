<!DOCTYPE html>
<html>
<table width="100%" cellpadding="0" cellspacing="0" border="1px">    
<head>
	<title>Doctor Commission List</title>
</head>
<tbody>
			<tr>
			  	<td colspan="3"><h2 align="center">Doctor Commission List</h2></td>
			</tr>
			<tr>
		  	 
		  	 <td width="30%" align="left" class="text-left"><b>Start Date :</b> <?php echo $_GET['start_date']; ?></td>
		  	 <td width="30%" align="left" class="text-left"><b>End Date :</b> <?php echo $_GET['end_date']; ?></td>
		  </tr>
		 
 <tr> 
    <th style="text-align: left;">Doctor Name </th>
    <th style="text-align: left;">Commission</th>
    <th style="text-align: left;">Paid Amount</th>
    
 </tr>
 
	<?php
	 
	 echo $html;
	?>
	</tbody>
</table>
</html>