<!DOCTYPE html>
<html>
<head>
	<title>Doctor Commission List</title>
	<?php if($_GET['printer_id']==2){ ?> 	
		<style>
*{margin:0;padding:0;box-sizing:border-box;-webkit-box-sizing:border-box;}
	page {
	  background: white;
	  display: block;
	  margin: 1em auto;
	  margin-bottom: 0.5cm;
	  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
	}
	page[size="A4"] {  
	  width: 21cm; /* 21cm */
	  height: 14.85cm; /* 29.7cm  for A4/2 */ 
	  padding: 2em;
	  font-size:13px;
	}
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
</style>
<?php }elseif($_GET['printer_id']==3)
{
	?>

	<style>
*{margin:0;padding:0;box-sizing:border-box;-webkit-box-sizing:border-box;}
	page {
	  background: white;
	  display: block;
	  margin: 1em auto;
	  margin-bottom: 0.5cm;
	  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
	}
	page[size="A4"] {  
	  width: 10.5cm;  /* 21cm for A4/4  */
	  min-height: 14.5cm; /* 29.7cm  for A4/4 */ 
	  padding: 1em;
	  font-size:12px;
	}
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
</style>

	<?php
}
else{ ?>
	<style type="text/css">*{margin:0;padding:0;box-sizing:border-box;-webkit-box-sizing:border-box;font-family:Arial;}
	page {
	  background: white;
	  display: block;
	  margin: 1em auto 0;
	  margin-bottom: 0.5cm;
	  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
	}
	page[size="A4"] {  
	  width: 21cm;
	  height: 27.7cm;  
	  padding: 3em;
	  font-size:13px;
	}
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
	</style>

	<?php } 


?>
</head>
<body style="background: rgb(204,204,204);font-family:Arial;color:#333;font-size:13px;">
		<page size="A4">
		  <table width="100%" class="m-b-5">
			<tbody>
			
					<tr>
					  	<td colspan="4"><h2 align="center">Doctor Commission List</h2></td>
					</tr>
					<tr>
				  	 
				  	 <td width="30%" align="left" class="text-left"><b>Start Date :</b> <?php echo $_GET['start_date']; ?></td>
				  	 <td width="30%" align="left" class="text-left"><b>End Date :</b> <?php echo $_GET['end_date']; ?></td>
				  	
				  	  <td width="10%" align="left" class="text-left print"><input type="button" name="button_print" value="Print"  id="print" onClick="return my_function();"/></td>
				  </tr>
		  </tbody>
		</table>

			<div style="display:block;border-top:2px solid black;">&nbsp;</div>
		 		<div style="float:left;width:100%;margin-top:20px;">

					<div style="float:left;width:100%;display:inline-flex;">
                        <div style="width:40%;font-weight:bold;padding-bottom:10px;padding-left:5px;"> Doctor Name </div>
                        <div style="width:30%;font-weight:bold;padding-bottom:10px;"> Commission</div>
                        <div style="width:30%;font-weight:bold;padding-bottom:10px;"> Paid Amount</div>
                        
                    </div>
	<?php
	 
	 echo $html;
	?>
	</div>
	</div> 
</page>
</html>