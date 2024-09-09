<!DOCTYPE html>
<html>
<head>
<title>MLC Print</title>
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
	  width: 21cm;  /* 21cm for A4/4  */
	  height: 29.7cm; /* 29.7cm  for A4/4 */ 
	  padding: 2em;
	  font-size:13px;
	}
</style>
</head>
<body style="background: rgb(204,204,204);font-family:Arial;color:#333;line-height:22px;">

	<page size="A4">
		
		<table width="100%" cellpadding="4px" cellspacing="0" border="0" style="border-collapse:collapse;margin-top:1em;">
			<tr>
				<th align="center"><h2><u>Medic-legal Certification</u></h2></th>
			</tr>
		</table>
		<table width="100%" cellpadding="4px" cellspacing="0" border="0" style="border-collapse:collapse;margin: 1em auto;">
			
			<tr>
				<td valign="top">1. OPD No. <span style="display:inline-block;width:30%;border-bottom:1px dashed #333;"><b><?php echo  $booking_id; ?></b></span>
					&nbsp; 2. Date and time of examination <span style="display:inline-block;width:31%;border-bottom:1px dashed #333;"><b><?php echo date('d-m-y',strtotime($get_by_id_data['opd_list'][0]->booking_date)); ?></b></span>

					&nbsp; 2. MLC No. <span style="display:inline-block;width:31%;border-bottom:1px dashed #333;"><b><?php echo $get_by_id_data['opd_list'][0]->mlc; ?></b></span>

				</td>
			</tr>

			<tr>
				<td valign="top">3. Name  <span style="display:inline-block;width:50%;border-bottom:1px dashed #333;"><b><?php echo ucfirst($get_by_id_data['opd_list'][0]->patient_name); ?></b></span>
					Age  <span style="display:inline-block;width:10%;border-bottom:1px dashed #333;"><b><?php echo  ucfirst($get_by_id_data['opd_list'][0]->age_y); ?></b></span>years, Sex : <span style="display:inline-block;width:17%;border-bottom:1px dashed #333;"><b><?php if($get_by_id_data['opd_list'][0]->gender==1){ echo 'Male'; } if($get_by_id_data['opd_list'][0]->gender==2){ echo 'Female';} if($get_by_id_data['opd_list'][0]->gender==2){ echo 'Others';} ?></b></span>
				</td>
			</tr>
			<tr>
				<td valign="top">4. Address  <span style="display:inline-block;width:90.2%;border-bottom:1px dashed #333;"><b><?php echo  $get_by_id_data['opd_list'][0]->address; ?></b></span>
					<br><span style="display:inline-block;width:96.9%;border-bottom:1px dashed #333;margin-left:1.5%;"></span>
				</td>
			</tr>
			<tr>
				<td valign="top">5. Identification marsk : 
					<div style="float:right;width:80%;">
						(i) <span style="display:inline-block;width:95.5%;border-bottom:1px dashed #333;"></span><br>
						(ii) <span style="display:inline-block;width:95.2%;border-bottom:1px dashed #333;"></span>
					</div>
				</td>
			</tr>
			<tr>
				<td valign="top">6. Bought by (Name & address) :  <span style="display:inline-block;width:72%;border-bottom:1px dashed #333;"></span>
				</td>
			</tr>
			<tr>
				<td valign="top">7. Requisition (if any) from :  <span style="display:inline-block;width:76.5%;border-bottom:1px dashed #333;"></span>
				</td>
			</tr>
			<tr>
				<td valign="top">8. History and alleged cause of injury :  <span style="display:inline-block;width:68.2%;border-bottom:1px dashed #333;"></span>
					<br><span style="display:inline-block;width:96.9%;border-bottom:1px dashed #333;margin-left:1.5%;"></span>
					<br><span style="display:inline-block;width:96.9%;border-bottom:1px dashed #333;margin-left:1.5%;"></span>
				</td>
			</tr>
			<tr>
				<td valign="top">9. History was stated by the injured :  <span style="display:inline-block;width:69%;border-bottom:1px dashed #333;"></span>
				</td>
			</tr>
			<tr>
				<td valign="top">10. Details of injuries :  <span style="display:inline-block;width:80%;border-bottom:1px dashed #333;"></span><br><span style="display:inline-block;width:96.9%;border-bottom:1px dashed #333;margin-left:1.5%;"></span><br><span style="display:inline-block;width:96.9%;border-bottom:1px dashed #333;margin-left:1.5%;"></span><br><span style="display:inline-block;width:96.9%;border-bottom:1px dashed #333;margin-left:1.5%;"></span><br><span style="display:inline-block;width:96.9%;border-bottom:1px dashed #333;margin-left:1.5%;"></span>
				</td>
			</tr>
			<tr>
				<td valign="top">11. Finding of physical examination :  <span style="display:inline-block;width:69.2%;border-bottom:1px dashed #333;"></span><br><span style="display:inline-block;width:96.9%;border-bottom:1px dashed #333;margin-left:1.5%;"></span><br><span style="display:inline-block;width:96.9%;border-bottom:1px dashed #333;margin-left:1.5%;"></span>
				</td>
			</tr>
			<tr>
				<td valign="top">12. Number of additional sheets if any :  <span style="display:inline-block;width:67.5%;border-bottom:1px dashed #333;"></span>
				</td>
			</tr>
			<tr>
				<td valign="top">13. Whether admitted or not: Admitted/Observation/Out patient/Expired in casualty/Referred.**</td>
			</tr>
			<tr>
				<td valign="top">14. Opinion: Could not be as alleged.** <span style="float:right;">Injuries appeared Fresh/Old.</span></td>
			</tr>
			<tr>
				<td valign="top">
					<div style="float:left;width:60%;">
						<p>Date : ______________________________</p>
						<p>Place: ______________________________</p>
						<p>Name of Institution: ___________________</p>
					</div>
					<div style="float:right;width:40%;">
						<table width="100%">
							<tr>
								<td>Signature :</td>
								<td>______________________________</td>
							</tr>
							<tr>
								<td>Name :</td>
								<td>______________________________</td>
							</tr>
							<tr>
								<td>Designation :</td>
								<td>______________________________</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td><hr></td>
			</tr>
			<tr>
				<th align="left">** Strike off which is not applicable.</th>
			</tr>
			<tr>
				<td valign="top">
					Issued to  <span style="display:inline-block;width:50%;border-bottom:1px dashed #333;"></span>
					as per his request No.  <span style="display:inline-block;width:10%;border-bottom:1px dashed #333;"></span>
					dated  <span style="display:inline-block;width:8.5%;border-bottom:1px dashed #333;"></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<div style="display:block;margin-top:1em;">
						Date:  <span style="display:inline-block;width:10%;border-bottom:1px dashed #333;"></span>
						<span style="display:inline-block;float:right;width:50%;">Signature of the issuing officer:</span>
					</div>
				</td>
			</tr>
		</table>
		
		
		
		
		
		
		
		
		
		


	</page>

</body>
</html>