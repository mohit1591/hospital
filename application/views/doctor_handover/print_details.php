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

<center style="margin: 20px;">
	<table width="950px">
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td>
							<img  src="https://www.hospitalms.net.in/manwatkar/assets/plugin/kcfinder/upload/images/ml1.png" >
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
							<td  align="center">
									<img  src="https://www.hospitalms.net.in/manwatkar/assets/plugin/kcfinder/upload/images/ml2.png" >
								</td>
						<td>
							<div style="text-align: right;">
								
								<span style="line-height: 20px;display: inline-block;margin: 10px 0;">
									Page No: 
								</span>
								<span style="border: 1px solid; width: 80px; height: 20px;display: inline-block;"></span>
							</div>
							<div style="text-align: right;">
								
								<span style="line-height: 20px;display: inline-block;margin: 10px 0;">
									I.P. No: 
								</span>
								<span style="border: 1px solid; width: 110px; height: 20px;display: inline-block;"><?=$data['ipd_no']?></span>
							</div>
							<div style="text-align: right;">
								
								<span style="line-height: 20px;display: inline-block;margin: 10px 0;">
									MRN No.
								</span>
								<span style="border: 1px solid; width: 110px; height: 20px;display: inline-block;"><?=$data['patient_code']?></span>
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
							<h3 style="font-size: 22px;background: #333;color: #fff;padding: 5px 20px;">DOCTORS HANDOVER SHEET</h3>
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
							Patient's Name <span style="border-bottom: 1px solid;width: 65%;display: inline-block;"><?=get_simulation_name($data['simulation_id'])?> <?=$data['patient_name']?></span>

							Date: <?=date('d/m/Y, H:i A',strtotime($all_details[0]['handover_date']))?></span>
							<div style="margin-bottom: 15px;">
							</div>

						</td>
					</tr>

					<tr>
						<td colspan="4">
							<?php
									foreach($all_details as $all_details)
									{
										?>
												<table style="width: 100%;border: 1px solid;" class="routin-table">
								<tr>
									<th style="text-transform: uppercase;"><?=$all_details['shift']?> SHIFT</th>
								</tr>
								<tr>
									<th align="left"><h3>Complaints</h3></th>
								<tr>
									<td><div class="" style="display: flex;">
										Current Complaints : <span style="border-bottom: 1px solid;width: 81%;display: inline-block;"><?=$all_details['current_complaints']?></span></div>
									</td>
								</tr>
								<tr>
									<td><div class="" style="display: flex;">
										General Condition: <span style="border-bottom: 1px solid;width: 85%;display: inline-block;"><?=$all_details['general_condition']?></span></div>
									</td>
								</tr>
								<tr>
									<td><div class="" style="display: flex;">
										Any Changes in Medication: <span style="border-bottom: 1px solid;width: 75%;display: inline-block;"><?=$all_details['any_changes_in_medication']?></span></div>
									</td>
								</tr>
								<tr>
									<td><div class="" style="display: flex;">
										Pending Investigations: <span style="border-bottom: 1px solid;width: 80%;display: inline-block;"><?=$all_details['pending_investigations']?></span></div>
									</td>
								</tr>
								<tr>
									<td><div class="" style="display: flex;">
										Care Plan: <span style="border-bottom: 1px solid;width: 90%;display: inline-block;"><?=$all_details['care_plan']?></span></div>
									</td>
								</tr>
								<tr>
									<td><div class="" style="display: flex;">
										Discharge/Transfer/Shifting
										(As per SBAR) <span style="border-bottom: 1px solid;width: 60%;display: inline-block;"><?=$all_details['discharge_transfer_shifting']?></span></div>
									</td>
								</tr>
								
								<tr>
									<td style="border-bottom:1px solid;"></td>
								</tr>

								<tr>
									<td>
										<h3>Medication Reconcilation Sheet</h3>
										<div class="" >
										Current Medication: <span style="border-bottom: 1px solid;width: 50%;display: inline-block;"><?=$all_details['current_medication']?></span>
										<span style="border-bottom: 1px solid;width: 100%;display: inline-block;"></span></div>
										
									</td>
								</tr>

								<tr>
									<td><div class="" >
										Medication During Stay : <span style="border-bottom: 1px solid;width: 70%;display: inline-block;"><?=$all_details['medication_during_stay']?></span>
										<span style="border-bottom: 1px solid;width: 100%;display: inline-block;"></span></div>
										
									</td>
								</tr>								
								<tr>
									<td><div class="" >
										Medication On Discharge :. <span style="border-bottom: 1px solid;width: 70%;display: inline-block;"><?=$all_details['medication_on_discharge']?></span>
										<span style="border-bottom: 1px solid;width: 100%;display: inline-block;"></span></div>
										
									</td>
								</tr>

									
								<tr>
									<td height="10px"></td>
									<!-- <td style="border-bottom:1px solid;"></td> -->
								</tr>

								<tr>
									<td style="padding: 0;">
										<table width="100%">
											<tr>
												<td align="center" style="border: 1px solid;border-left: none;font-size: 20px;">Pain grade / Action taken : <?=$all_details['pain_grade']?></td>
												<td  style="border: 1px solid;border-right: none;font-size: 20px;">Remarks/Sign. <br><?=$all_details['remark']?></td>
												<td  style="border: 1px solid;border-right: none;font-size: 20px;">PAIN ASSESMENT SCALE <br><?=$all_details['pain_assesment_scale']?></td>
											</tr>
											
										</table>
									</td>
								</tr>	
								<tr>
									<?php
										$date = new DateTime($all_details['morning_shift_date_time']);
										$formattedDate = $date->format('d/m/Y H:i');
										?>
									<td>
										Given by : <span style="border-bottom: 1px solid;width: 26%;display: inline-block; padding-right: 4px;"><?=$all_details['given_by']?></span>
										Taken By: <span style="border-bottom: 1px solid;width: 26%;display: inline-block; padding-right: 4px;"><?=$all_details['taken_by']?></span>
										Date: <span style="border-bottom: 1px solid;width: 26%;display: inline-block; text-align:center"><?= $formattedDate ?></span>
									</td>
								</tr>

								<!-- <tr>
									<td height="10px"></td>
								</tr>

								<tr>
									<th>MORNING / EVENING / NIGHT SHIFT</th>
								</tr>
								<tr>
									<th align="left"><h3>Complaints</h3></th>
								<tr>
									<td>
										Current Complaints : <span style="border-bottom: 1px solid;width: 81%;display: inline-block;"></span>
									</td>
								</tr>
								<tr>
									<td>
										General Condition: <span style="border-bottom: 1px solid;width: 85%;display: inline-block;"></span>
									</td>
								</tr>
								<tr>
									<td>
										Any Changes in Medication: <span style="border-bottom: 1px solid;width: 75%;display: inline-block;"></span>
									</td>
								</tr>
								<tr>
									<td>
										Pending Investigations: <span style="border-bottom: 1px solid;width: 80%;display: inline-block;"></span>
									</td>
								</tr>
								<tr>
									<td>
										Care Plan: <span style="border-bottom: 1px solid;width: 90%;display: inline-block;"></span>
									</td>
								</tr>
								<tr>
									<td>
										Discharge/Transfer/Shifting
										(As per SBAR) <span style="border-bottom: 1px solid;width: 60%;display: inline-block;"></span>
									</td>
								</tr>
								
								<tr>
									<td style="border-bottom:1px solid;"></td>
								</tr>

								<tr>
									<td>
										<h2>Medication Reconcilation Sheet</h2>
										Current Medication: <span style="border-bottom: 1px solid;width: 80%;display: inline-block;"></span>
										<span style="border-bottom: 1px solid;width: 100%;display: inline-block;"></span>
										
									</td>
								</tr>

								<tr>
									<td>
										Medication During Stay : <span style="border-bottom: 1px solid;width: 80%;display: inline-block;"></span>
										<span style="border-bottom: 1px solid;width: 100%;display: inline-block;"></span>
										
									</td>
								</tr>								
								<tr>
									<td>
										Medication On Discharge :. <span style="border-bottom: 1px solid;width: 80%;display: inline-block;"></span>
										<span style="border-bottom: 1px solid;width: 100%;display: inline-block;"></span>
										
									</td>
								</tr>

								<tr>
									<td>
										Given by : <span style="border-bottom: 1px solid;width: 40%;display: inline-block;"></span>
										Taken By:<span style="border-bottom: 1px solid;width: 40%;display: inline-block;"></span>
										
									</td>
								</tr>	
								<tr>
									<td height="10px"></td>
									
								</tr>

								<tr>
									<td style="padding: 0;">
										<table width="100%">
											<tr>
												<td align="center" style="border: 1px solid;border-left: none;font-size: 20px;">Pain grade / Action taken :</td>
												<td  style="border: 1px solid;border-right: none;font-size: 20px;">Remarks/Sign.</td>
											</tr>
											<tr>
												<td align="center" style="border: 1px solid;border-left: none;font-size: 20px;">PAIN ASSESMENT SCALE</td>
												<td  height="50px" style="border: 1px solid;border-right: none;">&nbsp;</td>
											</tr>
										</table>
									</td>
								</tr>	
								<tr>
									<td height="10px"></td>
								</tr>						 -->

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


	<!-- <p style="margin: 40px 0;">MMH/MR/DR/27/01</p> -->
</center>	

</body>
</html>