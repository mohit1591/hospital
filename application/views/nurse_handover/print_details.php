<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Form</title>
	<style type="text/css">
		* {
			margin: 0;
			padding: 0;
		}

		table {
			border-collapse: collapse;
		}

		td,
		th {
			padding: 5px;
		}

		.routin-table th {
			border: 1px solid;
		}

		.routin-table td {
			border-right: 1px solid;
		}
	</style>
</head>

<body>
	<?php
	$gender = ["Female", "Male", "Other"];

	?>
	<center style="margin: 20px;">
		<table width="950px">
			<tr>
				<td>
					<table width="100%">
						<tr>
							<td>
								<img src="https://www.hospitalms.net.in/manwatkar/assets/plugin/kcfinder/upload/images/ml1.png">
							</td>
							<td align="center">
								<table>
									<tr>
										<td colspan="3" align="center">
											<h3 style="margin-bottom: 4px;"><strong>MANWATKAR MULTISPECIALITY HOSPITAL</strong></h3>
										</td>
									</tr>
									<tr>

										<td align="center" style="font-size: 18px;">
											<h4 style="margin-bottom: 4px;">NABH ACCREDITED HOSPITAL</h4>
											<p>Near Medicine Complex, Ekori Ward, Chandrapur - 442 402.</p>
										</td>

									</tr>
								</table>
							</td>
							<td align="center">
								<img src="https://www.hospitalms.net.in/manwatkar/assets/plugin/kcfinder/upload/images/ml2.png">
							</td>
							<td>
								<div>

									<span style="line-height: 20px;display: inline-block;margin: 10px 0;">
										Page No:
									</span>
									<span style="border: 1px solid; width: 80px; height: 20px;display: inline-block;"></span>
								</div>
								<div style="width: 150px;height: 60px;border: 1px solid;">
									<?= $data['ipd_no'] ?>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<hr>
							</td>
						</tr>
						<tr>
							<td colspan="4" align="center">
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
							<td colspan="4" align="left">
								Patient's Name <span style="border-bottom: 1px solid;width: 70%;display: inline-block;"><?= get_simulation_name($data['simulation_id']) ?> <?= $data['patient_name'] ?></span>

								Age <span style="border-bottom: 1px solid;width: 5%;display: inline-block;"><?= $data['age_y'] ?></span>

								Sex: <span style="border-bottom: 1px solid;display: inline-block;"><?= $gender[$data['gender']] ?></span>
								<div style="margin-top: 15px;margin-bottom: 15px;">
									Date: <?= date('d/m/Y H:i A', strtotime($all_details[0]['handover_date'])) ?> <span style="width: 20px;display: inline-block;"></span>
									DOA: <?= date('d/m/Y', strtotime($data['admission_date'])) ?><span style="border-bottom: 1px solid;width: 75%;display: inline-block;"></span>

								</div>

								<div style="text-align: left;margin-bottom: 15px;">
									Diagnosis: <span style="border-bottom: 1px solid;width: 90%;display: inline-block;"><?= implode(';',$diagnosis_name) ?></span>
								</div>
								<div style="margin-bottom: 15px;">
									<b>SITUATION:</b> <span style="border-bottom: 1px solid;width: 87%;display: inline-block;"><?= $all_details[0]['situation'] ?></span>
								</div>

							</td>
						</tr>

						<tr>
							<td colspan="4" style="">
								<?php
								foreach ($all_details as $all_details) {
								?>
									<table style="width: 50%;border: 1px solid;float:left" class="routin-table">
										<tr>
											<th></th>
											<th style="text-transform: uppercase;"><?= $all_details['shift'] ?> SHIFT</th>
											<!-- <th>NIGHT SHIFT</th> -->
										</tr>
										<tr>
											<td>B</td>
											<td style="border-bottom: none;"><b>MEDICAL HISTORY:</b> <?= $all_details['morning_shift_medical_history'] ?></td>
											<!-- <td style="border-bottom: none;"><b>MEDICAL HISTORY:</b></td> -->
										</tr>
										
										<tr>
											<td>C</td>
											<td><b>ALLERGIES:</b> <?= $all_details['morning_shift_allergies'] ?></td>
											<!-- <td><b>ALLERGIES:</b></td> -->
										</tr>
										
										<tr>
											<td>G</td>
											<td><b>BARTHEL INDEX:</b> <?= $all_details['morning_shift_barthel_index'] ?></td>
										</tr>
										<tr>
											<td  style="border-bottom:1px solid;">O</td>
											<td  style="border-bottom:1px solid;"><b>CURRENT TREATMENT:</b> <?= $all_details['morning_shift_current_treatment'] ?></td>
										</tr>
									
										<tr>
											<td>A</td>
											<td><b>VITAL SIGNS: TEMP.</b> <?= $all_details['morning_shift_vital_temp'] ?></td>
										</tr>

									
										<tr>
											<td>S</td>
											<td><b>HR:</b><?= $all_details['morning_shift_vital_hr'] ?> <b>RR:</b><?= $all_details['morning_shift_vital_rr'] ?></td>
										</tr>
										<tr>
											<td>E</td>
											<td><b>BP:</b><?= $all_details['morning_shift_vital_bp'] ?> <b>PAIN SCALE:</b><?= $all_details['morning_shift_vital_pain_scale'] ?></td>
										</tr>
										<tr>
											<td>S</td>
											<td><b>ABNORMAL LAB:</b> <?= $all_details['morning_shift_abnormal_lab'] ?></td>
										</tr>
										
										<tr>
											<td style="border-bottom:1px solid;">N</td>
											<td style="border-bottom:1px solid;"><b>LINES/FLUIDS:</b> <?= $all_details['morning_shift_lines_fluids'] ?></td>
										</tr>


										<tr>
											<td>E</td>
											<td><b>GOALS</b> <?= $all_details['morning_shift_goals'] ?></td>
											<!-- <td><b>GOALS</b></td> -->
										</tr>
										
										<tr>
											<td>M</td>
											<td><b>PENDING COUNSULTATIONS:</b> <?= $all_details['morning_shift_pending_consultations'] ?></td>
											<!-- <td><b>PENDING COUNSULTATIONS:</b></td> -->
										</tr>
										
										<tr>
											<td>E</td>
											<td><b>TEST TREATMENT PENDING:</b> <?= $all_details['morning_shift_test_treatment_pending'] ?></td>
										</tr>
									
										<tr>
											<td style="border-bottom:1px solid;">T</td>
											<td style="border-bottom:1px solid;"><b>DISCHARGE NEEDS:</b> <?= $all_details['morning_shift_discharge_needs'] ?></td>
										</tr>


										<tr>
											<td></td>
											<td><b>Name/Signature:(From - To)</b> <?= $all_details['morning_shift_signature_from'] ?> - <?= $all_details['morning_shift_signature_to'] ?></td>
										</tr>

										<tr>
											<td></td>
											<?php
												$date = new DateTime($all_details['morning_shift_date_time']);
												$formattedDate = $date->format('d/m/Y H:i');
												?>
											<td><b>Date:</b> <?= $formattedDate ?></td>

										</tr>

									</table>
								<?php
								}
								?>
							</td>

						</tr>
					</table>
				</td>
			</tr>
		</table>



		<table width="1050px" style="display: none;">
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
				<td rowspan="2">&nbsp;</td>
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
				<td rowspan="2">&nbsp;</td>
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
				<td rowspan="2">&nbsp;</td>
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
				<td rowspan="2">&nbsp;</td>
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