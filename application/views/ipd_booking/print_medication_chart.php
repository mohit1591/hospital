<?php 
    $user_detail = $this->session->userdata('auth_users');
    $users_data = $this->session->userdata('auth_users');
    $genders = array('0'=>'Female','1'=>'Male');
    
   ?>
   <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Form</title>
	<style type="text/css">
		*{margin: 0;padding: 0;}	
		table{border-collapse: collapse;}
		td{padding: 5px;border: 1px solid #333	;}
	</style>
</head>
<body>

<center style="margin: 20px;">
	<table width="1050px" >
		<tr>
				<td colspan="14px">
					<table width="100%">
						<tr>
						   
            				<td align="center" style="border-right: 0;width: 150px;" >
            					<img  src="https://www.hospitalms.net.in/manwatkar/assets/plugin/kcfinder/upload/images/ml1.png" >
            				</td>
            		
            
            				<td align="center" style="border-right:0; border-left: 0;width: 450px;" >
            					<h3 style="margin-bottom: 4px;">MANWATKAR MULTISPECIALITY HOSPITAL</h3>
            					<h4 style="margin-bottom: 4px;">NABH ACCREDITED HOSPITAL</h4>
            					<p>Near Medicine Complex, Ekori Ward, Chandrapur - 442 402.</p>
            				</td>
            				<td align="center" style="border-left: 0; width: 110px;" >
            					<img  src="https://www.hospitalms.net.in/manwatkar/assets/plugin/kcfinder/upload/images/ml2.png" >
            				</td>
            						<td style="padding: 0;width: auto;" >
            					<table width="100%">
            						<tr>
            							<td style="border-top: 0;border-left: 0;border-right: 0;" colspan="2">Page No:</td>
            						</tr>
            						<tr>
            							<td style="border-left: 0;">TPA</td>
            							<td style="border-left: 0;border-right: 0;"> <?php echo $data['patient_type'] == '1' ? "Regular":"Panel ".$panel_company_name[0]->insurance_company ?></td>
            						</tr>
            						<tr>
            							<td style="border-left: 0;border-bottom: 0;border-right: 0;" colspan="2">IPD No: <?=$data['ipd_no']?></td>
            						</tr>
            					</table>
            				</td>
            				 </tr>
            				</table>
				</td>
			</tr>
			<tr>
				<td colspan="6">PATIENT NAME: <?=get_simulation_name($data['simulation_id'])?> <?=$data['patient_name']?></td>
				<td>AGE:</td>
				<td colspan="2"><?=$data['age_y']?> YEARS</td>
				<td>SEX:</td>
				<td><?=strtoupper($genders[$data['gender']]);?></td>
				<td>ROOM</td>
				
				<td><?=$data['room_no']?>/<?=$data['bad_name']?></td>
				<td colspan="2">UHID-<?=$data['patient_code']?></td>
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
				<td colspan="17">DIAGNOSIS:<?=str_replace('/','; ',$diagnosis_name)?></td>
			</tr>
			<tr>
				<td width="50px" rowspan="2"><b>Sr. No.</b></td>
				<td rowspan="2" width="50px"><b>Date</b></td>
				<td rowspan="2" width="500px" colspan=""><b>Name of Drug</b></td>
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

            <?php 
                if(!empty($medication_chart_list))
                {
                    $sr_no = 1;
                    foreach($medication_chart_list as $row)
                    {
                        ?>
                            <tr>
                                <td rowspan="2"><?=$sr_no?></td>
                                <td rowspan="2"><?=$row['date']?></td>
                                <td rowspan="2" colspan=""><?=$row['medicine_name']?></td>
                                <td rowspan="2"><?=$row['medicine_dose']?> </td>
                                <td rowspan="2"><?=$row['medicine_duration']?></td>
                                <td rowspan="2"><?=$row['medicine_frequency']?></td>
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
                        <?php
                        $sr_no++;
                    }
                }
            ?>

			
			
	</table>
	<p style="margin: 40px 0;">Dr. <?=$doctor->doctor_name?> <?=$doctor->qualification?> 
    <?php if(!empty($doctor->doc_reg_no)): ?>
    [Regn. No.: <?=$doctor->doc_reg_no?>]
    <?php endif;?>
</p>
</center>	

</body>
</html>