<!DOCTYPE html>
<html>
<head>
	<title>Leasor Print</title>
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
</head>
<body style="background: rgb(204,204,204);font-family:Arial;color:#333;font-size:13px;"><page size="A4">
<table border="0" cellpadding="0" cellspacing="0" style="width:100%;font-family:Arial;">
	<tbody>
		<tr>
			<td style="height:100px;" valign="top" width="20%"><img src="https://www.hospitalms.in/assets/images/logo.png" style="width:100%;background-size:cover;" /></td>
			<td style="text-align:center;" valign="top" width="60%">
			<h2>Sara Technologies</h2>

			<p>B-208&nbsp;&nbsp; sector 62&nbsp;Noida 201301<br />
			Phone No.(s) :+919773600373<br />
			Email ID. :info@sarasolutions.in</p>
			</td>
			<td valign="top" width="20%">&nbsp;</td>
		</tr>
	</tbody>
</table>

<div style="width:100%;text-align:center;font-size:12pt;margin-bottom:5px;"><b style="text-decoration:underline;">Item Details</b></div>

<div style="float:left;width:100%;margin-top:20px;">
<div style="float:left;width:100%;display:inline-flex;">
<div style="width:10%;font-weight:bold;padding-bottom:10px;padding-left:5px;">Sr. No.</div>

<div style="width:50%;font-weight:bold;padding-bottom:10px;">Item</div>

<div style="width:25%;font-weight:bold;padding-bottom:10px;">Item Code</div>

<div style="width:25%;font-weight:bold;padding-bottom:10px;text-align:right;padding-right:1em;">Quantity</div>
</div>
<?php if(!empty($all_detail)){ 
//echo "<pre>"; print_r($all_detail);

$i=1;
foreach($all_detail as $item_details)
{
?>

<div style="float:left;width:100%;display:inline-flex;">
<div style="width:10%;line-height:17px;padding-left:15px;"><?php echo $i; ?></div>

<div style="width:50%;line-height:17px;"><?php echo $item_details->item_name; ?></div>

<div style="width:25%;line-height:17px;"><?php echo $item_details->item_code; ?></div>

<div style="width:25%;line-height:17px;text-align:right;padding-right:1em;"><?php echo $item_details->qty; ?></div>
</div>

<?php $i++; }  } ?>


<div style="float:left;width:100%;padding:5px 1em 5px 5px;text-align:right;">Signature</div>
</div>
</page></body>
</html>
