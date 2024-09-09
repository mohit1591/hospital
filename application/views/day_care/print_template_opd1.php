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
			<td style="height:100px;" valign="top" width="20%"><img src="http://192.168.1.240/opd_medicine/assets/images/logo.png" style="width:100%;background-size:cover;" /></td>
			<td style="text-align:center;" valign="top" width="60%">
			<h2>OPD Clinic</h2>

			<p>E-156 Sara Technology sector 63 Noida 201301<br />
			Phone No.(s) :+918506080374<br />
			Email ID. :info@sarasolutions.in</p>
			</td>
			<td valign="top" width="20%">&nbsp;</td>
		</tr>
	</tbody>
</table>

<div style="width:100%;text-align:center;font-size:12pt;margin-bottom:5px;"><b style="text-decoration:underline;">Cash Receipt</b></div>

<table border="0" cellpadding="0" cellspacing="0" style="width:100%;font-family:Arial;border-top:2px solid #111;border-bottom:2px solid #111;padding:5px 0;">
	<tbody>
		<tr>
			<td valign="top" width="50%">
			<div style="width:100%;display:inline-flex;">
			<div style="width:40%;line-height:17px;font-weight:600;">Patient Reg. No. :</div>

			<div style="width:60%;line-height:17px;">{patient_reg_no}</div>
			</div>

			<div style="width:100%;display:inline-flex;">
			<div style="width:40%;line-height:17px;font-weight:600;">Patient Name :</div>

			<div style="width:60%;line-height:17px;">{patient_name}</div>
			</div>

			<div style="width:100%;display:inline-flex;">
			<div style="width:40%;line-height:17px;font-weight:600;">Mobile No. :</div>

			<div style="width:60%;line-height:17px;">{mobile_no}</div>
			</div>

			<div style="width:100%;display:inline-flex;">
			<div style="width:40%;line-height:17px;font-weight:600;">Gender/Age :</div>

			<div style="width:60%;line-height:17px;">{gender_age}</div>
			</div>

			<div style="width:100%;display:inline-flex;">
			<div style="width:40%;line-height:17px;font-weight:600;">Address :</div>


			<div style="width:60%;line-height:17px;">{patient_address}</div>
			</div>

			<div style="width:100%;display:inline-flex;">
			<div style="width:40%;line-height:17px;font-weight:600;">OPD Type :</div>
			

			<div style="width:60%;line-height:17px;">{opd_type}</div>
			</div>
			<div style="width:100%;display:inline-flex;">
			<div style="width:40%;line-height:17px;font-weight:600;">Pannel Type :</div>
			

			<div style="width:60%;line-height:17px;">{pannel_type}</div>
			</div>
			</td>
			<td valign="top" width="10%">&nbsp;</td>
			<td valign="top" width="40%">{booking_date}{booking_code} {Consultant} {specialization} {next_app_date}</td>
		</tr>
	</tbody>
</table>

<div style="margin-top:10px;">Received with Thanks a sum of Rs.{paid_amount} from {patient_name}</div>

<div style="float:left;width:100%;margin-top:20px;">
<div style="float:left;width:100%;display:inline-flex;">
<div style="width:10%;font-weight:bold;padding-bottom:10px;padding-left:5px;">Sr. No.</div>

<div style="width:50%;font-weight:bold;padding-bottom:10px;">Particulars</div>

<div style="width:25%;font-weight:bold;padding-bottom:10px;">{Quantity_level}</div>

<div style="width:25%;font-weight:bold;padding-bottom:10px;text-align:right;padding-right:1em;">Charges</div>
</div>
<!-- body -->{start_loop}

<div style="float:left;width:100%;display:inline-flex;">
<div style="width:10%;line-height:17px;padding-left:15px;">{s_no}</div>

<div style="width:50%;line-height:17px;">{particular}</div>

<div style="width:25%;line-height:17px;">{quantity}</div>

<div style="width:25%;line-height:17px;text-align:right;padding-right:1em;">{amount}</div>
</div>
{end_loop}

<div style="float:left;width:100%;display:inline-flex;">
<div style="width:50%;line-height:17px;">
<div style="float:left;width:100%;padding:5px 0;text-align:left;margin-top:72px;">
<div style="float:left;width:40%;font-weight:bold;">Payment Mode :</div>

<div style="float:right;width:60%;">{payment_mode}</div>
</div>
{remarks}</div>

<div style="width:25%;line-height:17px;">&nbsp;</div>

<div style="width:25%;line-height:17px;text-align:right;padding-right:1em;">
<div style="width:100%;border-top:1px solid #111;">
<div style="float:left;font-weight:bold;">Total:</div>

<div style="float:right;font-weight:bold;">{net_amount}</div>
</div>

<div style="float:left;width:100%;margin-top:10px;">
<div style="float:left;font-weight:bold;">Discount :</div>

<div style="float:right;font-weight:bold;">{total_discount}</div>
</div>

<div style="float:left;width:100%;margin-top:10px;">
<div style="float:left;font-weight:bold;">Received :</div>

<div style="float:right;font-weight:bold;">{paid_amount}</div>
</div>

<div style="float:left;width:100%;padding:5px 0;">
<div style="float:left;font-weight:bold;">Balance :</div>

<div style="float:right;font-weight:bold;">{balance}</div>
</div>

<div style="float:left;width:100%;padding:5px 0;margin-top:10px;font-weight:bold;">Signature</div>
</div>
</div>

<div style="float:left;width:100%;padding:5px 1em 5px 5px;text-align:right;">{sales_name}</div>
</div>
</page></body>
</html>
