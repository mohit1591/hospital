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
            <!-- <tr>
				
				<td colspan="17">DIAGNOSIS:<?=$diagnosis_name?></td>
			</tr> -->
			<tr>
				<td colspan="14px">
					<h3 style="margin:5px 0;text-align: center;">NURSE'S NOTES</h3>
				</td>
			</tr>

            <tr>
                <td colspan="2"><b>Date</b></td>
                <td colspan="3"><b>Shift</b></td>
                <td colspan="8"><b>Notes</b></td>
                <td colspan="1"><b>Remark/Signature</b></td>
            </tr>
			<?php
                foreach($nurses_note_list as $key => $value) {
                    ?>
                        <tr>
                            <td colspan="2"><?=date('d-m-Y',strtotime($value['date']))?></td>
                            <td colspan="3"><?=$value['shift']?></td>
                            <td colspan="8"><div style="padding:20px"><?=$value['note']?></div></td>
                            <td colspan="1"><?=$value['remark']?></td>
                        </tr>
                    <?php
                }
            ?>
			

			
			
	</table>
	<!-- <p style="margin: 40px 0;">Dr. <?=$doctor->doctor_name?> <?=$doctor->qualification?> 
    <?php if(!empty($doctor->doc_reg_no)): ?>
    [Regn. No.: <?=$doctor->doc_reg_no?>]
    <?php endif;?> -->
</p>
</center>	

</body>
</html>