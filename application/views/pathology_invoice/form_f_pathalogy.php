
<!DOCTYPE html>
<html>
<head>
<title>Test Report</title>
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
<body style="background: rgb(204,204,204);font-family:Arial;color:#333;line-height:19px;">

	<page size="A4">
		
		<table width="100%" cellpadding="4px" cellspacing="0" border="0" style="border-collapse:collapse;">
			<tr>
				<th align="center"><h2>FORM F</h2></th>
			</tr>
			<tr>
				<th align="center">
					<h4>
						[ See Proviso to Section 4(3), Rule 9(4) and Rule 10(1A) ]
					</h4>
				</th>
			</tr>
			<tr>
				<th align="center">
					<h3>FORM FOR MAINTENACE OF RECORD IN RESPECT OF PREGNANT WOMAN BY GENTIC CLINIC/ ULTRASOUND CLINIC/ IMAGING CENTRE</h3>
				</th>
			</tr>
		</table>
		<table width="95%" cellpadding="4px" cellspacing="0" border="0" style="border-collapse:collapse;margin: auto;">
			<tr>
				<td width="25">1</td>
				<td>Name and address of the Genetic Clinic/ Ultrasound Clinic/ Imaging Centre.   <b><?php echo  ucfirst($get_by_id_data['booking_list'][0]->branch_name); ?></b></td>
			</tr>
			<tr>
				<td width="10">2</td>
				<td>Registration No. <b><?php echo  ucfirst($get_by_id_data['booking_list'][0]->patient_code); ?></b></td>
			</tr>
			<tr>
				<td width="10">3</td>
				<td>Patient's name and her age.<b> <?php echo  ucfirst($get_by_id_data['booking_list'][0]->patient_name); ?></b> and <b><?php echo  $get_by_id_data['booking_list'][0]->age_y; ?></b></td>
			</tr>
			<tr>
				<td width="10">4</td>
				<td>Number of children with sex of each child. <b><?php if($get_by_id_data['booking_list'][0]->gender==1){ echo 'Male'; } if($get_by_id_data['booking_list'][0]->gender==2){ echo 'Female';} if($get_by_id_data['booking_list'][0]->gender==2){ echo 'Others';} ?></b></td>
			</tr>
			<tr>
				<td width="10">5</td>
				<td>Husband's/ Father's name.</td>
			</tr>
			<tr>
				<td width="10">6</td>
				<td>Full address with Tel. No., if any <b> <?php echo  $get_by_id_data['booking_list'][0]->address; ?></b> and <b> <?php echo  $get_by_id_data['booking_list'][0]->mobile_no; ?></b></td>
			</tr>
			<tr>
				<td width="10">7</td>
				<td>Reffered by (full name and address of Doctor(s)/ Genetic Counseling Centre (Referral note to be preserved carefully with case papers)/ self referral)</td>
			</tr>
			<tr>
				<td width="10">8</td>
				<td>Last menstrual period/ weeks of pregnancy</td>
			</tr>
			<tr>
				<td width="10">9</td>
				<td>History of genetic/ medical disease in the family (specify)<br>Basis of diagnosis: (a) Clinical (b) Bio-chemical (c) Cytogentic (d) Other <br> (e.g. Radiological, ultrasonography etc. specify)</td>
			</tr>
			<tr>
				<td width="10">10</td>
				<td>Indication for pre-natal diagnosis<br>
					A. Previous child/ children with:
				</td>
			</tr>
			<tr>
				<td width="" colspan="2" style="padding-top:0.5em;padding-bottom:0.5em;">
					<table width="100%" border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
						<tr>
							<td style="padding:4px;">Chromosomal disorders</td>
							<td style="padding:4px;">Metabolic disorders</td>
							<td style="padding:4px;">Congential anomaly</td>
							<td style="padding:4px;">Single gen disorder</td>
						</tr>
						<tr>
							<td style="padding:4px;">Menetal retardation</td>
							<td style="padding:4px;">Haemoglobinopathy</td>
							<td style="padding:4px;">Sex linked disorders</td>
							<td style="padding:4px;">Any other (specify)</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="10"></td>
				<td>
					<p>B. Advance maternal age (35 years)</p>
					<p>C. Mother/ father/ sibling has genetic disease (specify)</p>				
					<p>D. Other (specify)</p>
				</td>
			</tr>
			<tr>
				<td width="10">11.</td>
				<td>
					<p>Procefures carried out (with name and registration No. of Gynaecologiest/ radiologist/ Registered Medical Practitioner) who performed it. <span style="display:inline-block;width:84%;border-bottom:1px dashed #333;"></span></p>
				</td>
			</tr>
			<tr>
				<td width="10"></td>
				<td>
					<div><b>Not-Invasive</b></div>
					<div>
						(i)  Ultrasound <span style="display:inline-block;width:87%;border-bottom:1px dashed #333;"></span>
					</div>
				</td>
			</tr>
			<tr>
				<td width="10"></td>
				<td>
					<div><b>Invasive</b></div>
					<table width="100%" border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-top:0.5em;margin-bottom:0.5em;">
						<tr>
							<td style="padding:4px;">Amniocentesis</td>
							<td style="padding:4px;">Chroionic Villi aspiration</td>
							<td style="padding:4px;">Foetal biopsy</td>
						</tr>
						<tr>
							<td style="padding:4px;">Cordocentesis</td>
							<td style="padding:4px;">Any other (specify)</td>
							<td style="padding:4px;"></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="10">12.</td>
				<td>Any complication of procedure - please specify</td>
			</tr>
			<tr>
				<td width="10">13.</td>
				<td>Laboratory test recommended [Strike out whichever is not applicable or not necessary ]</td>
			</tr>
			<tr>
				<td width="10"></td>
				<td>
					<table width="100%" border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-top:0.5em;margin-bottom:0.5em;">
						<tr>
							<td style="padding:4px;">Chromosomal studies</td>
							<td style="padding:4px;">Biochemical studies</td>
						</tr>
						<tr>
							<td style="padding:4px;">Molecular studies</td>
							<td style="padding:4px;">Preimplantation genetic diagnosis</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="10">14.</td>
				<td>
					<p>Result of</p>
					<p>(a) Pre-natal diagnostic procedure (give details) <span style="display:inline-block;width:59%;border-bottom:1px dashed #333;"></span></p>
					<p>(b) Ultrasonography &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  Normal/Abnormal</p>
					<p>(Specify abnormality detected, if any).</p>
				</td>
			</tr>
			<tr>
				<td width="10">15.</td>
				<td>Date(s) on which procedures carried out.</td>
			</tr>
			<tr>
				<td width="10">16.</td>
				<td>Date on which consent obtained. (In case of invasive)</td>
			</tr>
			<tr>
				<td width="10">17.</td>
				<td>The result of pre-natal diagnostic procedure was convyed to <span style="display:inline-block;width:22%;border-bottom:1px dashed #333;"></span> on <span style="display:inline-block;width:22%;border-bottom:1px dashed #333;"></span> </td>
			</tr>
			<tr>
				<td width="10">18.</td>
				<td>Was MTP advised/ conducted?</td>
			</tr>
			<tr>
				<td width="10">19.</td>
				<td>Date on which MTP carried out.</td>
			</tr>
			<tr>
				<td width="10" style="padding:1em;"></td>
				<td></td>
			</tr>
			<tr>
				<td width="50">Date :</td>
				<td align="right" style="text-align:right;">Name, Signature and Registration number of the </td>
			</tr>
			<tr>
				<td width="50">Place :</td>
				<td align="right" style="text-align:right;">Gynaecologiest/Radiologist/Director of the Clinic</td>
			</tr>
			<tr>
				<td width="10"></td>
				<td></td>
			</tr>
		</table>
		
		
		
		
		
		
		
		
		
		


	</page>

</body>
</html>
<!---->

<!DOCTYPE html>
<html>
<head>
<title>Test Report</title>
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
		
		<table width="100%" cellpadding="4px" cellspacing="0" border="0" style="border-collapse:collapse;">
			<tr>
				<th align="center"><h3>DECLARATION OF PREGNANT WOMAN</h3></th>
			</tr>
			<tr>
				<td align="left">
					I, Ms.<span style="display:inline-block;width:10%;border-bottom:1px dashed #333;"></span>
					(name of pregnant woman) declare that by undergoing ultrasonogrpahy/ image scanning etc. I do not want ot know the sex of my foetus.
				</td>
			</tr>
			<tr>
				<td align="right">
					Signature/ Thump impression of pregnant woman
				</td>
			</tr>
		</table>
		<table width="95%" cellpadding="4px" cellspacing="0" border="0" style="border-collapse:collapse;margin: auto;">
			<tr>
				<th><h3 style="margin:0.5em 0;">DECLARATION OF DOCTOR/PERSON CONDUCTION ULTRASONOGRPHY/IMAGE SCANNING</h3></th>
			</tr>
			<tr>
				<td>I, <span style="display:inline-block;width:10%;border-bottom:1px dashed #333;"></span> (name of ther person conducting Ultrasonography/image scanning) declare that while conducting ultrsonography/image scanning on Ms.<span style="display:inline-block;width:10%;border-bottom:1px dashed #333;"></span> (name of preganat woman), I have neither detected nor disclosed the sex of her foetus to any body in any manner.</td>
			</tr>
			<tr>
				<td style="text-align:right;"><div style="width:70%;float:right;margin:0.5em 0;">Name and signature of the person conducting Ultrasonography/ image scanning/ Director or owner of genetic clinic/ ultrsound clinic/ imaging centre.</div></td>
			</tr>
			<tr>
				<th align="left"><h3>Important Note:-</h3></th>
			</tr>
			<tr>
				<td>
					<table width="90%" cellpadding="4px" cellspacing="0" border="0" style="border-collapse:collapse;margin: auto;">
						<tr>
							<td width="30" valign="top">(i)</td>
							<td> Ultrasound is not indicated/ advised/ performed to determine the sex of foetus except for diagnosis of sex-linked diseases such as Duchenne Muscular Dystrophy, Haemophilia A & B etc.
							</td>
						</tr>
						<tr>
							<td width="30" valign="top">(ii)</td>
							<td>During pregnancy Ultrasonography should only be performed when indicated. The following is the representative list of indications for ultrsound during pregnancy.</td>
						</tr>
						<tr>
							<td colspan="2" valign="top">
								<table width="80%" cellpadding="4px" cellspacing="0" border="0" style="border-collapse:collapse;margin: auto;">
									<tr>
										<td width="30" valign="top">(1)</td>
										<td>To diagnose intra-uterine and/ or ectopic pregnancy and confirm viability.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(2)</td>
										<td>Estimation of gestational age (dating).</td>
									</tr>
									<tr>
										<td width="30" valign="top">(3)</td>
										<td>Detection of number of fetuses and their chorionicity.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(4)</td>
										<td>Suspected pregnancy with IUCD in-situ or suspected pregnancy following contraceptive failure/ MTP failure.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(5)</td>
										<td>Vaginal bleeding/ leaking.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(6)</td>
										<td>Follow-up of cases of abortion.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(7)</td>  
										<td>Assessment of cervical canal and diameter of internal os.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(8)</td>   
										<td>Discrepancy between uterine size and period of amenorrhoea.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(9)</td>   
										<td>Any suspected adenexal or uterine pathology/ abnormality.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(10)</td>
										<td>Detection of chromosomal abnormalities, foetal structural defects and other abnormalities and their follow-up.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(11)</td>
										<td>To evalute foetal presentation and position.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(12)</td>
										<td>Assessment of liquor amnii.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(13)</td>
										<td>Preterm labour/ preterm premature rupture of membranes.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(14)</td>
										<td>Evalution of placental position, thickness, grading and abnormalities (placentapraevia, retroplacental haemorrhage, abnormal adherence etc.).</td>
									</tr>
									<tr>
										<td width="30" valign="top">(15) </td>
										<td>Evalution of  umbilical cord - presentation, insertion, nuchal encirclement, number of vessels and presence of true knot.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(16) </td>
										<td>Evalution of previous Caesarean Section scars.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(17) </td>
										<td>Evalution of foetal growth parameters, foetal weight and foetal well being.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(18) </td>
										<td>Colour flow mapping and duplex Doppler studies.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(19) </td>
										<td>Ultrasound guided procedures such as medical termination of pregnancy, external cephalic version etc. and their follow-up.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(20) </td>
										<td>Adunct to diagnostic and therapeutic invsive interventions such as chornionic villus smapling (CVS), amniocenteses, foetal blood sampling, foetal sking biopsy, amnioinfusion, intrauterine infusion, placement of shunts etc.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(21) </td>
										<td>Observation of intra-partun events.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(22) </td>
										<td>Medical/surgical conditions compicating pregnancy.</td>
									</tr>
									<tr>
										<td width="30" valign="top">(23) </td>
										<td>Researcg.scuebtific studies in recognized institutions.</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<th style="text-align:justify;line-height:15px;">
					Person conducting ultrasonography on pregnant women shall keep complete record thereof in the clinic/centre in Form - F and any deficiency or inaccuracy found therein shall amount to contravention of provisions of section 5 or section 6 of the Act, unless contrary is proved by the person conducting such ultrasonography.
				</th>
			</tr>
		</table>
		
		
		
		
		
		
		
		
		
		


	</page>

</body>
</html>
<!---->




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
				<td valign="top">1. Booking No. <span style="display:inline-block;width:30%;border-bottom:1px dashed #333;"><b><?php echo  $get_by_id_data['booking_list'][0]->booking_code; ?></b></span>
					&nbsp; 2. Date and time of examination <span style="display:inline-block;width:31%;border-bottom:1px dashed #333;"><b><?php echo date('d-m-y',strtotime($get_by_id_data['booking_list'][0]->booking_date)); ?></b></span>
				</td>
			</tr>
			<tr>
				<td valign="top">3. Name  <span style="display:inline-block;width:50%;border-bottom:1px dashed #333;"><b><?php echo ucfirst($get_by_id_data['booking_list'][0]->patient_name); ?></b></span>
					Age  <span style="display:inline-block;width:10%;border-bottom:1px dashed #333;"><b><?php echo  ucfirst($get_by_id_data['booking_list'][0]->age_y); ?></b></span>years, Sex : <span style="display:inline-block;width:17%;border-bottom:1px dashed #333;"><b><?php if($get_by_id_data['booking_list'][0]->gender==1){ echo 'Male'; } if($get_by_id_data['booking_list'][0]->gender==2){ echo 'Female';} if($get_by_id_data['booking_list'][0]->gender==2){ echo 'Others';} ?></b></span>
				</td>
			</tr>
			<tr>
				<td valign="top">4. Address  <span style="display:inline-block;width:90.2%;border-bottom:1px dashed #333;"><b><?php echo  $get_by_id_data['booking_list'][0]->address; ?></b></span>
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