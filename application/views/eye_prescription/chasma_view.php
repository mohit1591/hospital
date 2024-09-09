<html lang="en">
<head><meta charset="euc-kr">

</head>
<body style="font:12px 'Arial';">
	<page size="A4">
		
		
			<section style="float:left;width:100%;">
			<strong style="font-size:11px;">GLASSES PRESCRIPTIONS</strong>
		      <hr style="margin: 1px !important;">
			<div style="float:left;width:48.5%;padding:0px 2px;">
				<div style="text-align:center;background:#ddd;padding:3px;">R/OD</div>
				<table border="1" cellpadding="0" cellspacing="0" width="100%" style=";font-size:11px;border-collapse:collapse;">
					<tr>
						<td style="padding:3px;" valign="top"></td>
						<td style="padding:3px;" valign="top">Sph</td>
						<td style="padding:3px;" valign="top">Cyl</td>
						<td style="padding:3px;" valign="top">Axis</td>
						<td style="padding:3px;" valign="top">Vision</td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Distant</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_l_dt_sph'] !=''){ echo $refrtsn_glassp['refraction_gps_l_dt_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_l_dt_cyl'] !=''){ echo $refrtsn_glassp['refraction_gps_l_dt_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_l_dt_axis'] !=''){ echo $refrtsn_glassp['refraction_gps_l_dt_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_l_dt_vision'] !=''){ echo $refrtsn_glassp['refraction_gps_l_dt_vision'];}else {echo '--';}?></td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Add</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_l_ad_sph'] !=''){ echo $refrtsn_glassp['refraction_gps_l_ad_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_l_ad_cyl'] !=''){ echo $refrtsn_glassp['refraction_gps_l_ad_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_l_ad_axis'] !=''){ echo $refrtsn_glassp['refraction_gps_l_ad_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_l_ad_vision'] !=''){ echo $refrtsn_glassp['refraction_gps_l_ad_vision'];}else {echo '--';}?></td>
					</tr>

					<tr>
						<td style="padding:3px;" valign="top">Near</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_l_nr_sph'] !=''){ echo $refrtsn_glassp['refraction_gps_l_nr_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_l_nr_cyl'] !=''){ echo $refrtsn_glassp['refraction_gps_l_nr_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_l_nr_axis'] !=''){ echo $refrtsn_glassp['refraction_gps_l_nr_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_l_nr_vision'] !=''){ echo $refrtsn_glassp['refraction_gps_l_nr_vision'];}else {echo '--';}?></td>
					</tr>
				</table>
				<br>
					<div style="font-size:11px;">
					<?php if(!empty($refrtsn_glassp['refraction_gps_l_advs'])){ echo 'Advice : <strong> '.$refrtsn_glassp['refraction_gps_l_advs'].' | </strong>';}
					if(!empty($refrtsn_glassp['refraction_gps_l_tol'])){ echo 'Type of Lens- <strong> '.$refrtsn_glassp['refraction_gps_l_tol'].' | </strong>';} 
					if(!empty($refrtsn_glassp['refraction_gps_l_ipd'])){ echo 'IPD - <strong> '.$refrtsn_glassp['refraction_gps_l_ipd'].' | </strong>';}
					if(!empty($refrtsn_glassp['refraction_gps_l_lns_mat'])){ echo 'Lens Material- <strong> '.$refrtsn_glassp['refraction_gps_l_lns_mat'].' | </strong>';}
					if(!empty($refrtsn_glassp['refraction_gps_l_lns_tnt'])){ echo 'Lens Tint- <strong> '.$refrtsn_glassp['refraction_gps_l_lns_tnt'].' | </strong>';}
					if(!empty($refrtsn_glassp['refraction_gps_l_fm'])){ echo 'Frame Material- <strong> '.$refrtsn_glassp['refraction_gps_l_fm'].' | </strong>';}?>
					</div>

			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;">
				<div style="text-align:center;background:#ddd;padding:3px;">L/OS</div>
				<table border="1" cellpadding="0" cellspacing="0" width="100%" style=";font-size:11px;border-collapse:collapse;">
					<tr>
						<td style="padding:3px;" valign="top"></td>
						<td style="padding:3px;" valign="top">Sph</td>
						<td style="padding:3px;" valign="top">Cyl</td>
						<td style="padding:3px;" valign="top">Axis</td>
						<td style="padding:3px;" valign="top">Vision</td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Distant</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_r_dt_sph'] !=''){ echo $refrtsn_glassp['refraction_gps_r_dt_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_r_dt_cyl'] !=''){ echo $refrtsn_glassp['refraction_gps_r_dt_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_r_dt_axis'] !=''){ echo $refrtsn_glassp['refraction_gps_r_dt_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_r_dt_vision'] !=''){ echo $refrtsn_glassp['refraction_gps_r_dt_vision'];}else {echo '--';}?></td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Add</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_r_ad_sph'] !=''){ echo $refrtsn_glassp['refraction_gps_r_ad_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_r_ad_cyl'] !=''){ echo $refrtsn_glassp['refraction_gps_r_ad_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_r_ad_axis'] !=''){ echo $refrtsn_glassp['refraction_gps_r_ad_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_r_ad_vision'] !=''){ echo $refrtsn_glassp['refraction_gps_r_ad_vision'];}else {echo '--';}?></td>
					</tr>

					<tr>
						<td style="padding:3px;" valign="top">Near</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_r_nr_sph'] !=''){ echo $refrtsn_glassp['refraction_gps_r_nr_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_r_nr_cyl'] !=''){ echo $refrtsn_glassp['refraction_gps_r_nr_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_r_nr_axis'] !=''){ echo $refrtsn_glassp['refraction_gps_r_nr_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_glassp['refraction_gps_r_nr_vision'] !=''){ echo $refrtsn_glassp['refraction_gps_r_nr_vision'];}else {echo '--';}?></td>
					</tr>
				</table>
				<br>
					<div style="font-size:11px;">
						<?php if(!empty($refrtsn_glassp['refraction_gps_r_advs'])){ echo 'Advice : <strong> '.$refrtsn_glassp['refraction_gps_r_advs'].' | </strong>';}
						if(!empty($refrtsn_glassp['refraction_gps_r_tol'])){ echo 'Type of Lens- <strong> '.$refrtsn_glassp['refraction_gps_r_tol'].' | </strong>';} 
						if(!empty($refrtsn_glassp['refraction_gps_r_ipd'])){ echo 'IPD - <strong> '.$refrtsn_glassp['refraction_gps_r_ipd'].' | </strong>';}
						if(!empty($refrtsn_glassp['refraction_gps_r_lns_mat'])){ echo 'Lens Material- <strong> '.$refrtsn_glassp['refraction_gps_r_lns_mat'].' | </strong>';}
						if(!empty($refrtsn_glassp['refraction_gps_r_lns_tnt'])){ echo 'Lens Tint- <strong> '.$refrtsn_glassp['refraction_gps_r_lns_tnt'].' | </strong>';}
						if(!empty($refrtsn_glassp['refraction_gps_r_fm'])){ echo 'Frame Material- <strong> '.$refrtsn_glassp['refraction_gps_r_fm'].' | </strong>';}?>
					</div>
			</div>
		</section>
		<br>
        <p><strong>USAGE:</strong> REGULAR / FOR NEAR / FOR DISTANCE </p>
        <p><strong>GLASS TYPE:</strong> BIFOCAL / KRYPTOK / MULTIFOCAL </p>

	
</page>
</body>
</html>