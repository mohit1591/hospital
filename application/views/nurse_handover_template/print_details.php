<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Form</title>
	<style type="text/css">
		*{margin: 0;padding: 0;}	
		table{border-collapse: collapse;}
		td,th{padding: 5px;}
		.routin-table th{border: 1px solid;}
		.routin-table td {
			border-right: 1px solid;
		}
	</style>
</head>
<body>
<?php
$gender = ["F", "M", "Other"];
?>
<center style="margin: 20px;">
	<table width="950px">
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td>
							<img src="https://3.imimg.com/data3/WX/CG/MY-5377020/manwatkar-multispeciality-hospital-logo-90x90.png">
						</td>
						<td align="center">
							<table>
							<tr>
								<td colspan="3" align="center">
									<h1 style="margin-bottom: 4px;font-size: 30px;"><strong>MANWATKAR MULTISPECIALITY HOSPITAL</strong></h1>
								</td>
							</tr>
							<tr>
								<td  align="center">
									<img style="filter: grayscale(100%);" src="https://www.desunhospital.com/wp-content/uploads/2023/12/NABH-LOGO.png" width="50px">
								</td>
								<td align="center" style="font-size: 18px;">
									<h4 style="margin-bottom:7px;">An I.S.O.9001:2015 Certified Hospital</h4>
									<p style="margin-bottom: 7px;">Near Medicine Complex,</p>
									<p>Ekori Ward, Chandrapur - 442 402</p>
								</td>
								<td  align="center">
									<img style="filter: grayscale(100%);" src="https://www.desunhospital.com/wp-content/uploads/2023/12/NABH-LOGO.png" width="50px">
								</td>
							</tr>
							</table>
						</td>
						<td>
							<div>
								
								<span style="line-height: 20px;display: inline-block;margin: 10px 0;">
									Page No: 
								</span>
								<span style="border: 1px solid; width: 80px; height: 20px;display: inline-block;"></span>
							</div>
							<div style="width: 150px;height: 60px;border: 1px solid;">
								
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<hr>
						</td>
					</tr>
					<tr>
						<td colspan="3" align="center">
							<h3 style="font-size: 22px;">HAND OVER - SBAR FOR NURSES</h3>
							<p style="margin-top: 6px;margin-bottom: 10px; font-size: 18px;">(Situation, Background, Assessment, Recommendation)</p>
						</td>
					</tr>
					<!-- <tr>
						<td colspan="3" align="center">
							Patient's Name <span style="border-bottom: 1px solid;width: 70%;display: inline-block;"></span>
						</td>
					</tr>
					<tr>
						<td colspan="3" align="center">
							Age<span style="border-bottom: 1px solid;width: 5%;display: inline-block;"></span>
						</td>
					</tr> -->
					<tr>
						<td colspan="3" align="left">
							Patient's Name <span style="border-bottom: 1px solid;width: 70%;display: inline-block;"><?=$data['patient_name']?></span>

							Age <span style="border-bottom: 1px solid;width: 5%;display: inline-block;"><?=$data['age_y']?></span>

							Sex: M/F <span style="border-bottom: 1px solid;display: inline-block;"><?=$gender[$data['gender']]?></span>
							<div style="margin-top: 15px;margin-bottom: 15px;">
								Date: <?=date('d/m/Y',strtotime($all_details['handover_date']))?> <span style="width: 20px;display: inline-block;"></span>
							DOA: <span style="border-bottom: 1px solid;width: 75%;display: inline-block;"><?=$all_details['doa']?></span>

							</div>

							<div style="text-align: left;margin-bottom: 15px;"> 
							Diagnosis: <span style="border-bottom: 1px solid;width: 90%;display: inline-block;"><?=$diagnosis_name?></span>
							</div>
							<div style="margin-bottom: 15px;">
							<b>SITUATION:</b> <span style="border-bottom: 1px solid;width: 87%;display: inline-block;"><?=$all_details['situation']?></span>
							</div>

						</td>
					</tr>

					<tr>
						<td colspan="3">
							<table style="width: 100%;border: 1px solid;" class="routin-table">
								<tr>
									<th></th>
									<th>MORNING SHIFT</th>
									<th>NIGHT SHIFT</th>
								</tr>
								<tr>
									<td>B</td>
									<td style="border-bottom: none;">MEDICAL HISTORY:</td>
									<td style="border-bottom: none;">MEDICAL HISTORY:</td>
								</tr>
								<tr>
									<td>A</td>
									<td style="border-bottom: none;"><?=$all_details['morning_shift_medical_history']?></td>
									<td style="border-bottom: none;"><?=$all_details['night_shift_medical_history']?></td>
								</tr>
								<tr>
									<td>C</td>
									<td>ALLERGIES:</td>
									<td>ALLERGIES:</td>
								</tr>
								<tr>
									<td>K</td>
									<td><?=$all_details['morning_shift_allergies']?></td>
									<td><?=$all_details['night_shift_allergies']?></td>
								</tr>
								<tr>
									<td>G</td>
									<td>BARTHEL INDEX: <?=$all_details['morning_shift_barthel_index']?></td>
									<td>BARTHEL INDEX: <?=$all_details['night_shift_barthel_index']?></td>
								</tr>
								<tr>
									<td>O</td>
									<td>CURRENT TREATMENT: <?=$all_details['morning_shift_current_treatment']?></td>
									<td>CURRENT TREATMENT: <?=$all_details['night_shift_current_treatment']?></td>
								</tr>
								<tr>
									<td>U</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>N</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td style="border-bottom:1px solid;">D</td>
									<td  style="border-bottom:1px solid;"></td>
									<td style="border-bottom:1px solid;"></td>
								</tr>
								<tr>
									<td>A</td>
									<td>VITAL SIGNS: TEMP. <?=$all_details['morning_shift_vital_temp']?></td>
									<td>VITAL SIGNS: TEMP. <?=$all_details['night_shift_vital_temp']?></td>
								</tr>

								<tr>
									<td>S</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>S</td>
									<td>HR:<?=$all_details['morning_shift_vital_hr']?> RR:<?=$all_details['morning_shift_vital_rr']?></td>
									<td>HR:<?=$all_details['night_shift_vital_hr']?> RR:<?=$all_details['night_shift_vital_rr']?></td>
								</tr>
								<tr>
									<td>E</td>
									<td>BP:<?=$all_details['morning_shift_vital_bp']?> PAIN SCALE:<?=$all_details['morning_shift_vital_pain_scale']?></td>
									<td>BP:<?=$all_details['night_shift_vital_bp']?> PAIN SCALE:<?=$all_details['night_shift_vital_pain_scale']?></td>
								</tr>
								<tr>
									<td>S</td>
									<td>ABNORMAL LAB:</td>
									<td>ABNORMAL LAB:</td>
								</tr>
								<tr>
									<td>M</td>
									<td><?=$all_details['morning_shift_abnormal_lab']?></td>
									<td><?=$all_details['night_shift_abnormal_lab']?></td>
								</tr>
								<tr>
									<td>E</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td style="border-bottom:1px solid;">N</td>
									<td style="border-bottom:1px solid;">LINES/FLUIDS: <?=$all_details['morning_shift_lines_fluids']?></td>
									<td style="border-bottom:1px solid;">LINES/FLUIDS: <?=$all_details['night_shift_lines_fluids']?></td>
								</tr>
								


								<tr>
									<td>R</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>E</td>
									<td>GOALS</td>
									<td>GOALS</td>
								</tr>
								<tr>
									<td>C</td>
									<td><?=$all_details['morning_shift_goals']?></td>
									<td><?=$all_details['night_shift_goals']?></td>
								</tr>
								<tr>
									<td>O</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>E</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>M</td>
									<td>PENDING COUNSULTATIONS:</td>
									<td>PENDING COUNSULTATIONS:</td>
								</tr>
								<tr>
									<td>M</td>
									<td><?=$all_details['morning_shift_pending_consultations']?></td>
									<td><?=$all_details['night_shift_pending_consultations']?></td>
								</tr>
								<tr>
									<td>E</td>
									<td>TEST TREATMENT PENDING:</td>
									<td>TEST TREATMENT PENDING:</td>
								</tr>
								<tr>
									<td>N</td>
									<td><?=$all_details['morning_shift_test_treatment_pending']?></td>
									<td><?=$all_details['night_shift_test_treatment_pending']?></td>
								</tr>
								<tr>
									<td>D</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>A</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>T</td>
									<td>DISCHARGE NEEDS:</td>
									<td>DISCHARGE NEEDS:</td>
								</tr>

								<tr>
									<td>I</td>
									<td><?=$all_details['morning_shift_discharge_needs']?></td>
									<td><?=$all_details['night_shift_discharge_needs']?></td>
								</tr>
								<tr>
									<td>O</td>
									<td></td>
									<td></td>
								</tr>
								


								<tr>
									<td style="border-bottom:1px solid;">N</td>
									<td style="border-bottom:1px solid;"></td>
									<td style="border-bottom:1px solid;"></td>
								</tr>

								<tr>
									<td></td>
									<td>Name/Signature: <?=$all_details['morning_shift_signature']?></td>
									<td>Name/Signature: <?=$all_details['night_shift_signature']?></td>
								</tr>

								<tr>
									<td></td>
									<td>Date: <?=$all_details['morning_shift_date_time']?></td>
									<td>Date: <?=$all_details['night_shift_date_time']?></td>

								</tr>

							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>



	<table width="1050px"  style="display: none;">
		<tr>
				<td align="center" style="border-right: 0;width: 50px;" colspan="">
					<img style="filter: grayscale(100%);" src="https://www.desunhospital.com/wp-content/uploads/2023/12/NABH-LOGO.png" width="40px">
				</td>
				<td align="center" style="border: 0;border-top: 1px solid; width: 50px;" colspan="">
					<img style="filter: grayscale(100%);" src="https://www.desunhospital.com/wp-content/uploads/2023/12/NABH-LOGO.png" width="40px">
				</td>
				<!-- <td></td> -->
				<!-- <td></td> -->

				<td align="center" style="border-right:0; border-left: 0;width: 200px;" colspan="7">
					<h3 style="margin-bottom: 4px;"><strong>MANWATKAR MULTISPECIALITY HOSPITAL</strong></h3>
					<h4 style="margin-bottom: 4px;">NABH ACCREDITED HOSPITAL</h4>
					<p>Near Medicine Complex, Ekori Ward, Chandrapur - 442 402.</p>
				</td>
				<td align="center" style="border: 0;border-top: 1px solid; width: 140px;" colspan="">
					<img style="filter: grayscale(100%);" src="https://www.desunhospital.com/wp-content/uploads/2023/12/NABH-LOGO.png" width="50px">
				</td>
				<td align="center" style="border-left: 0; width: 140px;" colspan="">
					<img style="filter: grayscale(100%);" src="https://www.desunhospital.com/wp-content/uploads/2023/12/NABH-LOGO.png" width="50px">
				</td>
				<!-- <td></td>
				<td></td> -->

				<td style="padding: 0;border: 0;border-top: 1px solid;width: auto;" colspan="4">
					<table width="100%">
						<tr>
							<td style="border-top: 0;border-left: 0;" colspan="2">Page No:</td>
						</tr>
						<tr>
							<td style="border-left: 0;">TPA</td>
							<td style="border-left: 0;">Regular</td>
						</tr>
						<tr>
							<td style="border-left: 0;border-bottom: 0;" colspan="2">IPD No: MMHIP00431</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="6">PATIENT NAME: Mr. WAMANRAO SADASHIV JAGTAP</td>
				<td>AGE:</td>
				<td colspan="2">76 YEARS</td>
				<td>SEX:</td>
				<td>MALE</td>
				<td>ROOM</td>
				<td>203</td>
				<td colspan="2">UHID-MMHP00599</td>
			</tr>
			<tr>
				<td colspan="4"><b>Information Provided by: Patient/Family/other</b></td>
				<td colspan="5"><b>Previous Drug Reaction: Yes/No</b></td>
				<td colspan="6"><b>Drug Allergy: Yes/No/Not known. If yes:</b></td>
			</tr>
			<tr>
				<td colspan="14px">
					<h3 style="margin:5px 0;text-align: center;">MEDICATION CHART (TO BE FILLED BY DOCTOR)</h3>
				</td>
			</tr>
			<tr>
				<td></td>
				<td colspan="17">DIAGNOSIS:O/C Hemia Surgery</td>
			</tr>
			<tr>
				<td width="50px" rowspan="2"><b>Sr. No.</b></td>
				<td rowspan="2"><b>Date & Time: 25.06.23</b></td>
				<td rowspan="2" width="450px" colspan=""><b>Name of Drug</b></td>
				<td rowspan="2" width="80px"><b>Dose</b></td>
				<td rowspan="2"><b>Route</b></td>
				<td rowspan="2" width="180px"><b>Freq.</b></td>
				<td rowspan="2" width="150px"><b>Sign & Name of DMO</b></td>
				<td colspan="7" align="center"><b>BY NURSES</b></td>
				<!-- <td rowspan="2"></td> -->

			</tr>
			<tr>	
				<td><b>Time</b></td>
				<td><b>Sign With Name</b></td>
				<td><b>Time</b></td>
				<td><b>Sign With Name</b></td>
				<td><b>Time</b></td>
				<td><b>Sign With Name</b></td>
				<td><b>Verified by Sign Name</b></td>
			</tr>

			<tr>
				<td rowspan="2">1</td>
				<td rowspan="2">D3</td>
				<td rowspan="2" colspan="">INJ. CEFTRIAXONE + SULBACTUM /CEFCINAL SL</td>
				<td rowspan="2">1.5 GM </td>
				<td rowspan="2">IV</td>
				<td rowspan="2">BD<br /> 1--0--1</td>
				<td rowspan="2"></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>

			<tr>
				<td rowspan="2">2</td>
				<td rowspan="2">D3</td>
				<td rowspan="2" colspan="">INJECTION. DICLOFENAC/DICLOZONE IN 100 ML NS OVER 30 MIN</td>
				<td rowspan="2">75 GM </td>
				<td rowspan="2">IV</td>
				<td rowspan="2">TDS<br /> 1--1--1</td>
				<td rowspan="2"></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>


			<tr>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2" >&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>

			<tr>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2" >&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2" >&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2" >&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td rowspan="2">&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			
	</table>
	<!-- <p style="margin: 40px 0;">Dr. Madhuri Manwatkar MBBS, MS. [Regn. No.: 69437]</p> -->
</center>	

</body>
</html>