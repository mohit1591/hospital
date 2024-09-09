<html lang="en">
<head><meta charset="euc-kr">

</head>
<body style="font:8px 'Arial';">
	<page size="A4">
		<h3 style="float:left;width:100%;text-align:center;margin:2px 2px;position:relative;">
			<span style="position:absolute;height:2px;width:40%;background:#eee;"></span>
			<span style="font-size:11px">OPD SUMMARY</span>
		</h3>
		<?php  if($form_data['history_flag']==1){ 
			$hist='';
		foreach ($chief_complaints as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $hist=1;
        }} 
        foreach ($ophthalmic as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $hist=1;
        }} 
        foreach ($systemic as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $hist=1;
        }} 
        foreach ($drug_allergies as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $hist=1;
        }} 
        foreach ($contact_allergies as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $hist=1;
        }} 
        foreach ($food_allergies as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $hist=1;
        }} 
        if($hist==1){?>
 		<strong style="font-size:11px">HISTORY</strong>
		<hr style="margin: 2px !important;">
	  <?php } ?>
		<ul style="list-style:none;margin:5px 0 0px;padding:0px;font-size:11px;">
			<?php if($history_radios_data['general_checkup'] > 0){?>
			<li style=""> <strong style="font-size: 10px;">Visit:</strong> <?php if($history_radios_data['general_checkup']==1){ echo ' General Checkup, ';}else if($history_radios_data['general_checkup']==2){ echo 'Routine Checkup, ';} else if($history_radios_data['general_checkup']==3){ echo 'PostOp Checkup, ';} echo $history['visit_comm'] ; ?></li>

			<?php } if($chief_complaints['bdv_m']==1){?>
			<li style="font-size:12px"> Blurring/Diminution of vision in <strong style="font-size:10px;"> <?php if(!empty($chief_complaints['history_chief_blurr_side'])){ echo $chief_complaints['history_chief_blurr_side'].' Eye ';}?></strong>    <strong style="font-size:10px;"> <?php if(!empty($chief_complaints['history_chief_blurr_dur'])){ echo 'since '.$chief_complaints['history_chief_blurr_dur'].' ';} if(!empty($chief_complaints['history_chief_blurr_unit'])){ echo $chief_complaints['history_chief_blurr_unit'];}if(!empty($chief_complaints['history_chief_blurr_comm'])){ echo ' - '.$chief_complaints['history_chief_blurr_comm'];} if($chief_complaints['history_chief_blurr_dist']==1){echo ' - Distant, ';}if($chief_complaints['history_chief_blurr_near']==1){echo ' Near,';}if($chief_complaints['history_chief_blurr_pain']==1){echo ' Pain and ';}if($chief_complaints['history_chief_blurr_ug']==1){echo ' Using Glasses, ';}?>  </li>
		<?php } if($chief_complaints['pain_m']==1){?></strong>
			<li style=""> Pain in <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_pains_side'])){ echo $chief_complaints['history_chief_pains_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_pains_dur'])){ echo 'since '.$chief_complaints['history_chief_pains_dur'].' ';} if(!empty($chief_complaints['history_chief_pains_unit'])){ echo $chief_complaints['history_chief_pains_unit'];}if(!empty($chief_complaints['history_chief_pains_comm'])){ echo ' - '.$chief_complaints['history_chief_pains_comm'].', ';}?>
				</strong></li>
		<?php } if($chief_complaints['redness_m']==1){?>
			<li style=""> Redness in  <strong style="font-size: 10px;"> 
				<?php if(!empty($chief_complaints['history_chief_rednes_side'])){ echo $chief_complaints['history_chief_rednes_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_rednes_dur'])){ echo 'since '.$chief_complaints['history_chief_rednes_dur'].' ';} if(!empty($chief_complaints['history_chief_rednes_unit'])){ echo $chief_complaints['history_chief_rednes_unit'];}if(!empty($chief_complaints['history_chief_rednes_comm'])){ echo ' - '.$chief_complaints['history_chief_rednes_comm'].', ';}?>
					</strong>  </li>
		<?php } if($chief_complaints['injury_m']==1){?>
			<li style=""> Injury in  <strong style="font-size: 10px;">
			<?php if(!empty($chief_complaints['history_chief_injuries_side'])){ echo $chief_complaints['history_chief_injuries_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_injuries_dur'])){ echo 'since '.$chief_complaints['history_chief_injuries_dur'].' ';} if(!empty($chief_complaints['history_chief_injuries_unit'])){ echo $chief_complaints['history_chief_injuries_unit'];}if(!empty($chief_complaints['history_chief_injuries_comm'])){ echo ' - '.$chief_complaints['history_chief_injuries_comm'].', ';}?>
			</strong>  </li>
		<?php } if($chief_complaints['water_m']==1){?>
			<li style=""> Watering in  <strong style="font-size: 10px;">
			<?php if(!empty($chief_complaints['history_chief_waterings_side'])){ echo $chief_complaints['history_chief_waterings_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_waterings_dur'])){ echo 'since '.$chief_complaints['history_chief_waterings_dur'].' ';} if(!empty($chief_complaints['history_chief_waterings_unit'])){ echo $chief_complaints['history_chief_waterings_unit'];}if(!empty($chief_complaints['history_chief_waterings_comm'])){ echo ' - '.$chief_complaints['history_chief_waterings_comm'].', ';}?>
			</strong>  </li>
		<?php } if($chief_complaints['discharge_m']==1){?>
			<li style=""> Discharge in  <strong style="font-size: 10px;"> 
				<?php if(!empty($chief_complaints['history_chief_discharges_side'])){ echo $chief_complaints['history_chief_discharges_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_discharges_dur'])){ echo 'since '.$chief_complaints['history_chief_discharges_dur'].' ';} if(!empty($chief_complaints['history_chief_discharges_unit'])){ echo $chief_complaints['history_chief_discharges_unit'];}if(!empty($chief_complaints['history_chief_discharges_comm'])){ echo ' - '.$chief_complaints['history_chief_discharges_comm'].', ';}?>
					
				</strong>  </li>
		<?php } if($chief_complaints['dryness_m']==1){?>
			<li style=""> Dryness in  <strong style="font-size: 10px;">
				<?php if(!empty($chief_complaints['history_chief_dryness_side'])){ echo $chief_complaints['history_chief_dryness_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_dryness_dur'])){ echo 'since '.$chief_complaints['history_chief_dryness_dur'].' ';} if(!empty($chief_complaints['history_chief_dryness_unit'])){ echo $chief_complaints['history_chief_dryness_unit'];}if(!empty($chief_complaints['history_chief_dryness_comm'])){ echo ' - '.$chief_complaints['history_chief_dryness_comm'].', ';}?>
					</strong>  </li>
		<?php } if($chief_complaints['itch_m']==1){?>
			<li style=""> Itching in  <strong style="font-size: 10px;"> 
				<?php if(!empty($chief_complaints['history_chief_itchings_side'])){ echo $chief_complaints['history_chief_itchings_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_itchings_dur'])){ echo 'since '.$chief_complaints['history_chief_itchings_dur'].' ';} if(!empty($chief_complaints['history_chief_itchings_unit'])){ echo $chief_complaints['history_chief_itchings_unit'];}if(!empty($chief_complaints['history_chief_itchings_comm'])){ echo ' - '.$chief_complaints['history_chief_itchings_comm'].', ';}?>
				</strong>  </li>
		<?php } if($chief_complaints['fbd_m']==1){?>
			<li style=""> Fbsensation in  <strong style="font-size: 10px;">
				<?php if(!empty($chief_complaints['history_chief_fbsensation_side'])){ echo $chief_complaints['history_chief_fbsensation_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_fbsensation_dur'])){ echo 'since '.$chief_complaints['history_chief_fbsensation_dur'].' ';} if(!empty($chief_complaints['history_chief_fbsensation_unit'])){ echo $chief_complaints['history_chief_fbsensation_unit'];}if(!empty($chief_complaints['history_chief_fbsensation_comm'])){ echo ' - '.$chief_complaints['history_chief_fbsensation_comm'].', ';}?>
			   </strong>  </li>
		<?php } if($chief_complaints['devs_m']==1){?>	
			<li style=""> Deviation Squint in  <strong style="font-size: 10px;">
				<?php if(!empty($chief_complaints['history_chief_dev_squint_side'])){ echo $chief_complaints['history_chief_dev_squint_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_dev_squint_dur'])){ echo 'since '.$chief_complaints['history_chief_dev_squint_dur'].' ';} if(!empty($chief_complaints['history_chief_dev_squint_unit'])){ echo $chief_complaints['history_chief_dev_squint_unit'];}if(!empty($chief_complaints['history_chief_dev_squint_comm'])){ echo ' - '.$chief_complaints['history_chief_dev_squint_comm'];}
				if($chief_complaints['history_chief_dev_diplopia'] !=''){echo ' - '.$chief_complaints['history_chief_dev_diplopia'];}if($chief_complaints['history_chief_dev_truma'] !=''){echo ', '.$chief_complaints['history_chief_dev_diplopia'];} if($chief_complaints['history_chief_dev_ps'] !=''){echo ', '.$chief_complaints['history_chief_dev_ps'];}?></strong>  </li>
		<?php } if($chief_complaints['heads_m']==1){?>
			<li style=""> Headache Strain in  <strong style="font-size: 10px;">
				<?php if(!empty($chief_complaints['history_chief_head_strain_side'])){ echo $chief_complaints['history_chief_head_strain_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_head_strain_dur'])){ echo 'since '.$chief_complaints['history_chief_head_strain_dur'].' ';} if(!empty($chief_complaints['history_chief_head_strain_unit'])){ echo $chief_complaints['history_chief_head_strain_unit'];}if(!empty($chief_complaints['history_chief_head_strain_comm'])){ echo ' - '.$chief_complaints['history_chief_head_strain_comm'].', ';}?>
				</strong>  </li>
		<?php } if($chief_complaints['canss_m']==1){?>
			<li style=""> Change In Size Shape in  <strong style="font-size: 10px;">
				<?php if(!empty($chief_complaints['history_chief_size_shape_side'])){ echo $chief_complaints['history_chief_size_shape_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_size_shape_dur'])){ echo 'since '.$chief_complaints['history_chief_size_shape_dur'].' ';} if(!empty($chief_complaints['history_chief_size_shape_unit'])){ echo $chief_complaints['history_chief_size_shape_unit'];}if(!empty($chief_complaints['history_chief_size_shape_comm'])){ echo ' - '.$chief_complaints['history_chief_size_shape_comm'].', ';}?>
				</strong>  </li>
		<?php } if($chief_complaints['ovs_m']==1){?>
			<li style=""> Other Visual Symptoms in <strong style="font-size: 10px;">  
				<?php if(!empty($chief_complaints['history_chief_ovs_side'])){ echo $chief_complaints['history_chief_ovs_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_ovs_dur'])){ echo 'since '.$chief_complaints['history_chief_ovs_dur'].' ';} if(!empty($chief_complaints['history_chief_ovs_unit'])){ echo $chief_complaints['history_chief_ovs_unit'];}if(!empty($chief_complaints['history_chief_ovs_comm'])){ echo ' - '.$chief_complaints['history_chief_ovs_comm'];}
				 if($chief_complaints['history_chief_ovs_glare']==1){echo ' - Glare,';}if($chief_complaints['history_chief_ovs_floaters']==1){echo ' Floaters,';}if($chief_complaints['history_chief_ovs_photophobia']==1){echo ' Photophobia,';}if($chief_complaints['history_chief_ovs_color_halos']==1){echo ' Colored Halos,';} if($chief_complaints['history_chief_ovs_metamorphopsia']==1){echo ' Metamorphopsia, ';}if($chief_complaints['history_chief_ovs_chromatopsia']==1){echo ' Chromatopsia,';}if($chief_complaints['history_chief_ovs_dnv']==1){echo ' Diminished Night Vision and ';}if($chief_complaints['history_chief_ovs_ddv']==1){echo ' Diminished Day Vision';}?></strong>  </li>
		<?php } if($chief_complaints['sdv_m']==1){?>
			<li style=""> Shadow Defect In Vision in  <strong style="font-size: 10px;">
				<?php if(!empty($chief_complaints['history_chief_sdiv_side'])){ echo $chief_complaints['history_chief_sdiv_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_sdiv_dur'])){ echo 'since '.$chief_complaints['history_chief_sdiv_dur'].' ';} if(!empty($chief_complaints['history_chief_sdiv_unit'])){ echo $chief_complaints['history_chief_sdiv_unit'];}if(!empty($chief_complaints['history_chief_sdiv_comm'])){ echo ' - '.$chief_complaints['history_chief_sdiv_comm'].', ';}?>
				</strong>  </li>
		<?php } if($chief_complaints['doe_m']==1){?>
			<li style=""> Discoloration Of Eye in  <strong style="font-size: 10px;">
				<?php if(!empty($chief_complaints['history_chief_doe_side'])){ echo $chief_complaints['history_chief_doe_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_doe_dur'])){ echo 'since '.$chief_complaints['history_chief_doe_dur'].' ';} if(!empty($chief_complaints['history_chief_doe_unit'])){ echo $chief_complaints['history_chief_doe_unit'];}if(!empty($chief_complaints['history_chief_doe_comm'])){ echo ' - '.$chief_complaints['history_chief_doe_comm'].', ';}?>
					
				</strong>  </li>
		<?php } if($chief_complaints['swel_m']==1){?>
			<li style=""> Swelling in  <strong style="font-size: 10px;">
				<?php if(!empty($chief_complaints['history_chief_swell_side'])){ echo $chief_complaints['history_chief_swell_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_swell_dur'])){ echo 'since '.$chief_complaints['history_chief_swell_dur'].' ';} if(!empty($chief_complaints['history_chief_swell_unit'])){ echo $chief_complaints['history_chief_swell_unit'];}if(!empty($chief_complaints['history_chief_swell_comm'])){ echo ' - '.$chief_complaints['history_chief_swell_comm'].', ';}?>
				</strong>  </li>
		<?php } if($chief_complaints['burns_m']==1){?>
			<li style=""> Sensation Burning in  <strong style="font-size: 10px;">
				<?php if(!empty($chief_complaints['history_chief_sen_burn_side'])){ echo $chief_complaints['history_chief_sen_burn_side'].' Eye ';}?></strong>    <strong style="font-size: 10px;"> <?php if(!empty($chief_complaints['history_chief_sen_burn_dur'])){ echo 'since '.$chief_complaints['history_chief_sen_burn_dur'].' ';} if(!empty($chief_complaints['history_chief_sen_burn_unit'])){ echo $chief_complaints['history_chief_sen_burn_unit'];}if(!empty($chief_complaints['history_chief_sen_burn_comm'])){ echo ' - '.$chief_complaints['history_chief_sen_burn_comm'].', ';}?>
				</strong>  </li>
		<?php } if($chief_complaints['history_chief_comm'] !=''){?>
			<li style="margin-top:8px;">  <strong style="font-size: 10px;"> <?php echo '- ' .$chief_complaints['history_chief_comm'];?></strong>  </li>
		<?php }?>	

		<?php if($ophthalmic['gla_m']==1){?>
			<li style=""> Known cases of Glaucoma <strong style="font-size: 10px;">
				<?php if(!empty($ophthalmic['history_ophthalmic_glau_l_dur'])){ echo 'Left Eye since '.$ophthalmic['history_ophthalmic_glau_l_dur'].' '.$ophthalmic['history_ophthalmic_glau_l_unit'];}?></strong> <strong style="font-size: 10px;"> <?php if(!empty($ophthalmic['history_ophthalmic_glau_r_dur'])){ echo ' & Right Eye since '.$ophthalmic['history_ophthalmic_glau_r_dur'].' '.$ophthalmic['history_ophthalmic_glau_r_unit'];} if(!empty($ophthalmic['history_ophthalmic_glau_comm'])){ echo ' - '.$ophthalmic['history_ophthalmic_glau_comm'].', ';}?>
			</strong></li>
		<?php } if($ophthalmic['reti_m']==1){?>
			<li style=""> Retinal Detachment <strong style="font-size: 10px;">
				<?php if(!empty($ophthalmic['history_ophthalmic_renti_d_l_dur'])){ echo 'Left Eye since '.$ophthalmic['history_ophthalmic_renti_d_l_dur'].' '.$ophthalmic['history_ophthalmic_renti_d_l_unit'];}?></strong> <strong style="font-size: 10px;"> <?php if(!empty($ophthalmic['history_ophthalmic_renti_d_r_dur'])){ echo ' & Right Eye since '.$ophthalmic['history_ophthalmic_renti_d_r_dur'].' '.$ophthalmic['history_ophthalmic_renti_d_r_unit'];} if(!empty($ophthalmic['history_ophthalmic_renti_d_comm'])){ echo ' - '.$ophthalmic['history_ophthalmic_renti_d_comm'].', ';}?>
		<?php } if($ophthalmic['glass_m']==1){?>
			<li style=""> Glasses <strong style="font-size: 10px;">
				<?php if(!empty($ophthalmic['history_ophthalmic_glas_l_dur'])){ echo 'Left Eye since '.$ophthalmic['history_ophthalmic_glas_l_dur'].' '.$ophthalmic['history_ophthalmic_glas_l_unit'];}?></strong> <strong style="font-size: 10px;"> <?php if(!empty($ophthalmic['history_ophthalmic_glas_r_dur'])){ echo ' & Right Eye since '.$ophthalmic['history_ophthalmic_glas_r_dur'].' '.$ophthalmic['history_ophthalmic_glas_r_unit'];} if(!empty($ophthalmic['history_ophthalmic_glas_comm'])){ echo ' - '.$ophthalmic['history_ophthalmic_glas_comm'].', ';}?>
				</strong></li>
		<?php } if($ophthalmic['eyedi_m']==1){?>
			<li style=""> Eye Disease <strong style="font-size: 10px;">
				<?php if(!empty($ophthalmic['history_ophthalmic_eye_d_l_dur'])){ echo 'Left Eye since '.$ophthalmic['history_ophthalmic_eye_d_l_dur'].' '.$ophthalmic['history_ophthalmic_eye_d_l_unit'];}?></strong> <strong style="font-size: 10px;"> <?php if(!empty($ophthalmic['history_ophthalmic_eye_d_r_dur'])){ echo ' & Right Eye since '.$ophthalmic['history_ophthalmic_eye_d_r_dur'].' '.$ophthalmic['history_ophthalmic_eye_d_r_unit'];} if(!empty($ophthalmic['history_ophthalmic_eye_d_comm'])){ echo ' - '.$ophthalmic['history_ophthalmic_eye_d_comm'].', ';}?>
				</strong></li>
		<?php } if($ophthalmic['eyesu_m']==1){?>
			<li style=""> Eye Surgery <strong style="font-size: 10px;"> 
				<?php if(!empty($ophthalmic['history_ophthalmic_eye_s_l_dur'])){ echo 'Left Eye since '.$ophthalmic['history_ophthalmic_eye_s_l_dur'].' '.$ophthalmic['history_ophthalmic_eye_s_l_unit'];}?></strong> <strong style="font-size: 10px;"> <?php if(!empty($ophthalmic['history_ophthalmic_eye_s_r_dur'])){ echo ' & Right Eye since '.$ophthalmic['history_ophthalmic_eye_s_r_dur'].' '.$ophthalmic['history_ophthalmic_eye_s_r_unit'];} if(!empty($ophthalmic['history_ophthalmic_eye_s_comm'])){ echo ' - '.$ophthalmic['history_ophthalmic_eye_s_comm'].', ';}?>
			</strong></li>
		<?php } if($ophthalmic['uve_m']==1){?>
			<li style=""> Uveitis <strong style="font-size: 10px;">
				<?php if(!empty($ophthalmic['history_ophthalmic_uvei_l_dur'])){ echo 'Left Eye since '.$ophthalmic['history_ophthalmic_uvei_l_dur'].' '.$ophthalmic['history_ophthalmic_uvei_l_unit'];}?></strong> <strong style="font-size: 10px;"> <?php if(!empty($ophthalmic['history_ophthalmic_uvei_r_dur'])){ echo ' & Right Eye since '.$ophthalmic['history_ophthalmic_uvei_r_dur'].' '.$ophthalmic['history_ophthalmic_uvei_r_unit'];} if(!empty($ophthalmic['history_ophthalmic_uvei_comm'])){ echo ' - '.$ophthalmic['history_ophthalmic_uvei_comm'].', ';}?>
			</strong></li>
		<?php } if($ophthalmic['retil_m']==1){?>
			<li style=""> Retinal Laser <strong style="font-size: 10px;">
			<?php if(!empty($ophthalmic['history_ophthalmic_renti_l_l_dur'])){ echo 'Left Eye since '.$ophthalmic['history_ophthalmic_renti_l_l_dur'].' '.$ophthalmic['history_ophthalmic_renti_l_l_unit'];}?></strong> <strong style="font-size: 10px;"> <?php if(!empty($ophthalmic['history_ophthalmic_renti_l_r_dur'])){ echo ' & Right Eye since '.$ophthalmic['history_ophthalmic_renti_l_r_dur'].' '.$ophthalmic['history_ophthalmic_renti_l_r_unit'];} if(!empty($ophthalmic['history_ophthalmic_renti_l_comm'])){ echo ' - '.$ophthalmic['history_ophthalmic_renti_l_comm'].', ';}?> 
			</strong></li>
		<?php } if($ophthalmic['history_ophthalmic_comm'] !=''){?>
			<li style="margin-top:8px;"> <strong style="font-size: 10px;"> <?php echo $ophthalmic['history_ophthalmic_comm'];?></strong> </li>
		<?php }?>
	
		<?php if($systemic['dia_m']==1){?>
		<li style=""> Diabetes <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_diab_dur'])){ echo 'since '.$systemic['history_systemic_diab_dur'].' '.$systemic['history_systemic_diab_unit'];} if(!empty($systemic['history_systemic_diab_comm'])){ echo ' - '.$systemic['history_systemic_diab_comm'].', ';}?>
		</strong></li>
		<?php } if($systemic['hyper_m']==1){?>
		<li style=""> Hypertension   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_hyper_dur'])){ echo 'since '.$systemic['history_systemic_hyper_dur'].' '.$systemic['history_systemic_hyper_unit'];} if(!empty($systemic['history_systemic_hyper_comm'])){ echo ' - '.$systemic['history_systemic_hyper_comm'].', ';}?>
				
			</strong></li>
		<?php } if($systemic['alcoh_m']==1){?>
		<li style=""> Alcoholism   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_alcoh_dur'])){ echo 'since '.$systemic['history_systemic_alcoh_dur'].' '.$systemic['history_systemic_alcoh_unit'];} if(!empty($systemic['history_systemic_alcoh_comm'])){ echo ' - '.$systemic['history_systemic_alcoh_comm'].', ';}?>
			</strong></li>
		<?php } if($systemic['smok_m']==1){?>
		<li style=""> Smoking Tobacco   <strong style="font-size: 10px;"><?php if(!empty($systemic['history_systemic_smokt_dur'])){ echo 'since '.$systemic['history_systemic_smokt_dur'].' '.$systemic['history_systemic_smokt_unit'];} if(!empty($systemic['history_systemic_smokt_comm'])){ echo ' - '.$systemic['history_systemic_smokt_comm'].', ';}?>
			
		</strong></li>
		<?php } if($systemic['card_m']==1){?>
		<li style=""> Cardiac Disorder   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_cardd_dur'])){ echo 'since '.$systemic['history_systemic_cardd_dur'].' '.$systemic['history_systemic_cardd_unit'];} if(!empty($systemic['history_systemic_cardd_comm'])){ echo ' - '.$systemic['history_systemic_cardd_comm'].', ';}?>
				
			</strong></li>
		<?php } if($systemic['steri_m']==1){?>
		<li style=""> Steroid Intake   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_steri_dur'])){ echo 'since '.$systemic['history_systemic_steri_dur'].' '.$systemic['history_systemic_steri_unit'];} if(!empty($systemic['history_systemic_steri_comm'])){ echo ' - '.$systemic['history_systemic_steri_comm'].', ';}?>
				
			</strong></li>
		<?php } if($systemic['drug_m']==1){?>
		<li style=""> Drug Abuse   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_drug_dur'])){ echo 'since '.$systemic['history_systemic_drug_dur'].' '.$systemic['history_systemic_drug_unit'];} if(!empty($systemic['history_systemic_drug_comm'])){ echo ' - '.$systemic['history_systemic_drug_comm'].', ';}?>
		</strong></li>
		<?php } if($systemic['hiva_m']==1){?>
		<li style=""> Hiv Aids   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_hiva_dur'])){ echo 'since '.$systemic['history_systemic_hiva_dur'].' '.$systemic['history_systemic_hiva_unit'];} if(!empty($systemic['history_systemic_hiva_comm'])){ echo ' - '.$systemic['history_systemic_hiva_comm'].', ';}?>
		</strong></li>
		<?php } if($systemic['cant_m']==1){?>
		<li style=""> Cancer Tumor   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_cantu_dur'])){ echo 'since '.$systemic['history_systemic_cantu_dur'].' '.$systemic['history_systemic_cantu_unit'];} if(!empty($systemic['history_systemic_cantu_comm'])){ echo ' - '.$systemic['history_systemic_cantu_comm'].', ';}?>
		</strong></li>
		<?php } if($systemic['tuber_m']==1){?>
		<li style=""> Tuberculosis   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_tuberc_dur'])){ echo 'since '.$systemic['history_systemic_tuberc_dur'].' '.$systemic['history_systemic_tuberc_unit'];} if(!empty($systemic['history_systemic_tuberc_comm'])){ echo ' - '.$systemic['history_systemic_tuberc_comm'].', ';}?>
			</strong></li>
		<?php } if($systemic['asth_m']==1){?>
		<li style=""> Asthma   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_asthm_dur'])){ echo 'since '.$systemic['history_systemic_asthm_dur'].' '.$systemic['history_systemic_asthm_unit'];} if(!empty($systemic['history_systemic_asthm_comm'])){ echo ' - '.$systemic['history_systemic_asthm_comm'].', ';}?>
		</strong></li>
		<?php } if($systemic['cnsds_m']==1){?>
		<li style=""> Cns Disorder Stroke   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_cncds_dur'])){ echo 'since '.$systemic['history_systemic_cncds_dur'].' '.$systemic['history_systemic_cncds_unit'];} if(!empty($systemic['history_systemic_cncds_comm'])){ echo ' - '.$systemic['history_systemic_cncds_comm'].', ';}?>
				
			</strong></li>
		<?php } if($systemic['hypo_m']==1){?>
		<li style=""> Hypothyroidism   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_hypo_dur'])){ echo 'since '.$systemic['history_systemic_hypo_dur'].' '.$systemic['history_systemic_hypo_unit'];} if(!empty($systemic['history_systemic_hypo_comm'])){ echo ' - '.$systemic['history_systemic_hypo_comm'].', ';}?>
				
			</strong></li>
		<?php } if($systemic['hyperth_m']==1){?>
		<li style=""> Hyperthyroidism   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_hyperth_dur'])){ echo 'since '.$systemic['history_systemic_hyperth_dur'].' '.$systemic['history_systemic_hyperth_unit'];} if(!empty($systemic['history_systemic_hyperth_comm'])){ echo ' - '.$systemic['history_systemic_hyperth_comm'].', ';}?>
				
			</strong></li>
		<?php } if($systemic['hepac_m']==1){?>
		<li style=""> Hepatitis Cirrhosis   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_heptc_dur'])){ echo 'since '.$systemic['history_systemic_heptc_dur'].' '.$systemic['history_systemic_heptc_unit'];} if(!empty($systemic['history_systemic_heptc_comm'])){ echo ' - '.$systemic['history_systemic_heptc_comm'].', ';}?>
				
			</strong></li>
		<?php } if($systemic['renald_m']==1){?>
		<li style=""> Renal Disorder   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_rendis_dur'])){ echo 'since '.$systemic['history_systemic_rendis_dur'].' '.$systemic['history_systemic_rendis_unit'];} if(!empty($systemic['history_systemic_rendis_comm'])){ echo ' - '.$systemic['history_systemic_rendis_comm'].', ';}?>
				
			</strong></li>
		<?php } if($systemic['acid_m']==1){?>
		<li style=""> Acidity   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_acid_dur'])){ echo 'since '.$systemic['history_systemic_acid_dur'].' '.$systemic['history_systemic_acid_unit'];} if(!empty($systemic['history_systemic_acid_comm'])){ echo ' - '.$systemic['history_systemic_acid_comm'].', ';}?>
				
			</strong></li>
		<?php } if($systemic['oins_m']==1){?>
		<li style=""> On Insulin   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_onins_dur'])){ echo 'since '.$systemic['history_systemic_onins_dur'].' '.$systemic['history_systemic_onins_unit'];} if(!empty($systemic['history_systemic_onins_comm'])){ echo ' - '.$systemic['history_systemic_onins_comm'].', ';}?>
				
			</strong></li>
		<?php } if($systemic['oasp_m']==1){?>
		<li style=""> On Aspirin Blood Thinners   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_oasbth_dur'])){ echo 'since '.$systemic['history_systemic_oasbth_dur'].' '.$systemic['history_systemic_oasbth_unit'];} if(!empty($systemic['history_systemic_oasbth_comm'])){ echo ' - '.$systemic['history_systemic_oasbth_comm'].', ';}?>
				
			</strong></li>
		<?php } if($systemic['acon_m']==1){?>
		<li style=""> Consanguinity   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_consan_dur'])){ echo 'since '.$systemic['history_systemic_consan_dur'].' '.$systemic['history_systemic_consan_unit'];} if(!empty($systemic['history_systemic_consan_comm'])){ echo ' - '.$systemic['history_systemic_consan_comm'].', ';}?>
				
			</strong></li>
		<?php } if($systemic['thd_m']==1){?>
		<li style=""> Thyroid Disorder   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_thyrd_dur'])){ echo 'since '.$systemic['history_systemic_thyrd_dur'].' '.$systemic['history_systemic_thyrd_unit'];} if(!empty($systemic['history_systemic_thyrd_comm'])){ echo ' - '.$systemic['history_systemic_thyrd_comm'].', ';}?>
				
			</strong></li>
		<?php } if($systemic['chewt_m']==1){?>
		<li style=""> Chewing Tobacco   <strong style="font-size: 10px;">
			<?php if(!empty($systemic['history_systemic_chewt_dur'])){ echo 'since '.$systemic['history_systemic_chewt_dur'].' '.$systemic['history_systemic_chewt_unit'];} if(!empty($systemic['history_systemic_chewt_comm'])){ echo ' - '.$systemic['history_systemic_chewt_comm'].', ';}?>
				
			</strong></li>
		<?php } if($systemic['history_systemic_comm'] !=''){?>

			<li style="margin-top:8px;">  <strong style="font-size: 10px;"> <?php echo $systemic['history_systemic_comm'];?> </strong> </li>
		<?php }?>

		<?php if($history['family'] !=''){?>
			<li style="">  <strong style="font-size: 10px;"> <?php echo 'Family history of '.$history['family'];?></strong>  </li>
		<?php } if($history['medical'] !=''){?>
			<li style="">  <strong style="font-size: 10px;"> <?php echo 'Medical history of '.$history['medical'];?></strong>  </li>
		<?php }?>

		<?php if($drug_allergies['antimi_agen_m']==1){?>
			<?php if($drug_allergies['ampic_m']==1){?>
				<li style=""> Ampicillin since  <strong style="font-size: 10px;">
					<?php if(!empty($drug_allergies['history_drug_antimicrobial_ampici_dur'])){ echo $drug_allergies['history_drug_antimicrobial_ampici_dur'].' '.$drug_allergies['history_drug_antimicrobial_ampici_unit'];} if(!empty($drug_allergies['history_drug_antimicrobial_ampici_comm'])){ echo ' - '.$drug_allergies['history_drug_antimicrobial_ampici_comm'].', ';}?>
					</strong></li>

			<?php } if($drug_allergies['amox_m']==1){?>
				<li style=""> Amoxicillin since  <strong style="font-size: 10px;">
					<?php if(!empty($drug_allergies['history_drug_antimicrobial_amoxi_dur'])){ echo $drug_allergies['history_drug_antimicrobial_amoxi_dur'].' '.$drug_allergies['history_drug_antimicrobial_amoxi_unit'];} if(!empty($drug_allergies['history_drug_antimicrobial_amoxi_comm'])){ echo ' - '.$drug_allergies['history_drug_antimicrobial_amoxi_comm'].', ';}?>
				</strong></li>
			<?php } if($drug_allergies['ceftr_m']==1){?>
			<li style=""> Ceftriaxone since  <strong style="font-size: 10px;"> 
				<?php if(!empty($drug_allergies['history_drug_antimicrobial_ceftr_dur'])){ echo $drug_allergies['history_drug_antimicrobial_ceftr_dur'].' '.$drug_allergies['history_drug_antimicrobial_ceftr_unit'];} if(!empty($drug_allergies['history_drug_antimicrobial_ceftr_comm'])){ echo ' - '.$drug_allergies['history_drug_antimicrobial_ceftr_comm'].', ';}?>
				</strong></li>
			<?php } if($drug_allergies['cipro_m']==1){?>
			<li style=""> Ciprofloxacin since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_antimicrobial_ciprof_dur'])){ echo $drug_allergies['history_drug_antimicrobial_ciprof_dur'].' '.$drug_allergies['history_drug_antimicrobial_ciprof_unit'];} if(!empty($drug_allergies['history_drug_antimicrobial_ciprof_comm'])){ echo ' - '.$drug_allergies['history_drug_antimicrobial_ciprof_comm'].', ';}?>
				</strong></li>
			<?php } if($drug_allergies['clari_m']==1){?>
			<li style=""> Clarithromycin since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_antimicrobial_clarith_dur'])){ echo $drug_allergies['history_drug_antimicrobial_clarith_dur'].' '.$drug_allergies['history_drug_antimicrobial_clarith_unit'];} if(!empty($drug_allergies['history_drug_antimicrobial_clarith_comm'])){ echo ' - '.$drug_allergies['history_drug_antimicrobial_clarith_comm'].', ';}?>
				</strong></li>
			<?php } if($drug_allergies['cotri_m']==1){?>
			<li style=""> Co Trimoxazole since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_antimicrobial_cotri_dur'])){ echo $drug_allergies['history_drug_antimicrobial_cotri_dur'].' '.$drug_allergies['history_drug_antimicrobial_cotri_unit'];} if(!empty($drug_allergies['history_drug_antimicrobial_cotri_comm'])){ echo ' - '.$drug_allergies['history_drug_antimicrobial_cotri_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['etham_m']==1){?>
			<li style=""> Ethambutol since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_antimicrobial_ethamb_dur'])){ echo $drug_allergies['history_drug_antimicrobial_ethamb_dur'].' '.$drug_allergies['history_drug_antimicrobial_ethamb_unit'];} if(!empty($drug_allergies['history_drug_antimicrobial_ethamb_comm'])){ echo ' - '.$drug_allergies['history_drug_antimicrobial_ethamb_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['ison_m']==1){?>
			<li style=""> Isoniazid since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_antimicrobial_isoni_dur'])){ echo $drug_allergies['history_drug_antimicrobial_isoni_dur'].' '.$drug_allergies['history_drug_antimicrobial_isoni_unit'];} if(!empty($drug_allergies['history_drug_antimicrobial_isoni_comm'])){ echo ' - '.$drug_allergies['history_drug_antimicrobial_isoni_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['metro_m']==1){?>
			<li style=""> Metronidazole since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_antimicrobial_metron_dur'])){ echo $drug_allergies['history_drug_antimicrobial_metron_dur'].' '.$drug_allergies['history_drug_antimicrobial_metron_unit'];} if(!empty($drug_allergies['history_drug_antimicrobial_metron_comm'])){ echo ' - '.$drug_allergies['history_drug_antimicrobial_metron_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['penic_m']==1){?>
			<li style=""> Penicillin since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_antimicrobial_penic_dur'])){ echo $drug_allergies['history_drug_antimicrobial_penic_dur'].' '.$drug_allergies['history_drug_antimicrobial_penic_unit'];} if(!empty($drug_allergies['history_drug_antimicrobial_penic_comm'])){ echo ' - '.$drug_allergies['history_drug_antimicrobial_penic_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['rifa_m']==1){?>
			<li style=""> Rifampicin since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_antimicrobial_rifam_dur'])){ echo $drug_allergies['history_drug_antimicrobial_rifam_dur'].' '.$drug_allergies['history_drug_antimicrobial_rifam_unit'];} if(!empty($drug_allergies['history_drug_antimicrobial_rifam_comm'])){ echo ' - '.$drug_allergies['history_drug_antimicrobial_rifam_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['strep_m']==1){?>
			<li style=""> Streptomycin since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_antimicrobial_strept_dur'])){ echo $drug_allergies['history_drug_antimicrobial_strept_dur'].' '.$drug_allergies['history_drug_antimicrobial_strept_unit'];} if(!empty($drug_allergies['history_drug_antimicrobial_strept_comm'])){ echo ' - '.$drug_allergies['history_drug_antimicrobial_strept_comm'].', ';}?>
					
				</strong></li>
		<?php } } ?>

		<?php if($drug_allergies['antif_agen_m']==1){?>
			<?php if($drug_allergies['ketoc_m']==1){?>
				<li style=""> Ketoconazole since  <strong style="font-size: 10px;">
					<?php if(!empty($drug_allergies['history_drug_antifungal_ketoco_dur'])){ echo $drug_allergies['history_drug_antifungal_ketoco_dur'].' '.$drug_allergies['history_drug_antifungal_ketoco_unit'];} if(!empty($drug_allergies['history_drug_antifungal_ketoco_comm'])){ echo ' - '.$drug_allergies['history_drug_antifungal_ketoco_comm'].', ';}?>
						
					</strong></li>
			<?php } if($drug_allergies['fluco_m']==1){?>
				<li style=""> Fluconazole since  <strong style="font-size: 10px;">
					<?php if(!empty($drug_allergies['history_drug_antifungal_flucon_dur'])){ echo $drug_allergies['history_drug_antifungal_flucon_dur'].' '.$drug_allergies['history_drug_antifungal_flucon_unit'];} if(!empty($drug_allergies['history_drug_antifungal_flucon_comm'])){ echo ' - '.$drug_allergies['history_drug_antifungal_flucon_comm'].', ';}?>
						
					</strong></li>
			<?php } if($drug_allergies['itrac_m']==1){?>
			<li style=""> Itraconazole since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_antifungal_itrac_dur'])){ echo $drug_allergies['history_drug_antifungal_itrac_dur'].' '.$drug_allergies['history_drug_antifungal_itrac_unit'];} if(!empty($drug_allergies['history_drug_antifungal_itrac_comm'])){ echo ' - '.$drug_allergies['history_drug_antifungal_itrac_comm'];}?></strong></li>
		<?php } } ?>

		<?php if($drug_allergies['ant_agen_m']==1){?>
			<?php if($drug_allergies['acyclo_m']==1){?>
				<li style=""> Acyclovir since  <strong style="font-size: 10px;"> 
					<?php if(!empty($drug_allergies['history_drug_antiviral_acyclo_dur'])){ echo $drug_allergies['history_drug_antiviral_acyclo_dur'].' '.$drug_allergies['history_drug_antiviral_acyclo_unit'];} if(!empty($drug_allergies['history_drug_antiviral_acyclo_comm'])){ echo ' - '.$drug_allergies['history_drug_antiviral_acyclo_comm'].', ';}?>
						
					</strong></li>
			<?php } if($drug_allergies['efavir_m']==1){?>
				<li style=""> Efavirenz since  <strong style="font-size: 10px;">
					<?php if(!empty($drug_allergies['history_drug_antiviral_efavir_dur'])){ echo $drug_allergies['history_drug_antiviral_efavir_dur'].' '.$drug_allergies['history_drug_antiviral_efavir_unit'];} if(!empty($drug_allergies['history_drug_antiviral_efavir_comm'])){ echo ' - '.$drug_allergies['history_drug_antiviral_efavir_comm'].', ';}?>
						
					</strong></li>
			<?php } if($drug_allergies['enfuv_m']==1){?>
			<li style=""> Enfuvirtide since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_antiviral_enfuv_dur'])){ echo $drug_allergies['history_drug_antiviral_enfuv_dur'].' '.$drug_allergies['history_drug_antiviral_enfuv_unit'];} if(!empty($drug_allergies['history_drug_antiviral_enfuv_comm'])){ echo ' - '.$drug_allergies['history_drug_antiviral_enfuv_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['nelfin_m']==1){?>
			<li style=""> Nelfinavir since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_antiviral_nelfin_dur'])){ echo $drug_allergies['history_drug_antiviral_nelfin_dur'].' '.$drug_allergies['history_drug_antiviral_nelfin_unit'];} if(!empty($drug_allergies['history_drug_antiviral_nelfin_comm'])){ echo ' - '.$drug_allergies['history_drug_antiviral_nelfin_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['nevira_m']==1){?>
			<li style=""> Nevirapine since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_antiviral_nevira_dur'])){ echo $drug_allergies['history_drug_antiviral_nevira_dur'].' '.$drug_allergies['history_drug_antiviral_nevira_unit'];} if(!empty($drug_allergies['history_drug_antiviral_nevira_comm'])){ echo ' - '.$drug_allergies['history_drug_antiviral_nevira_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['zidov_m']==1){?>
			<li style=""> Nevirapine since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_antiviral_zidov_dur'])){ echo $drug_allergies['history_drug_antiviral_zidov_dur'].' '.$drug_allergies['history_drug_antiviral_zidov_unit'];} if(!empty($drug_allergies['history_drug_antiviral_zidov_comm'])){ echo ' - '.$drug_allergies['history_drug_antiviral_zidov_comm'].', ';}?>
					
				</strong></li>
		<?php } } ?>


		<?php if($drug_allergies['nsaids_m']==1){?>
			<?php if($drug_allergies['aspirin_m']==1){?>
				<li style=""> Aspirin since  <strong style="font-size: 10px;">
					<?php if(!empty($drug_allergies['history_drug_nsaids_aspirin_dur'])){ echo $drug_allergies['history_drug_nsaids_aspirin_dur'].' '.$drug_allergies['history_drug_nsaids_aspirin_unit'];} if(!empty($drug_allergies['history_drug_nsaids_aspirin_comm'])){ echo ' - '.$drug_allergies['history_drug_nsaids_aspirin_comm'].', ';}?>
						
					</strong></li>
			<?php } if($drug_allergies['paracet_m']==1){?>
				<li style=""> Paracetamol since  <strong style="font-size: 10px;">
					<?php if(!empty($drug_allergies['history_drug_nsaids_paracet_dur'])){ echo $drug_allergies['history_drug_nsaids_paracet_dur'].' '.$drug_allergies['history_drug_nsaids_paracet_unit'];} if(!empty($drug_allergies['history_drug_nsaids_paracet_comm'])){ echo ' - '.$drug_allergies['history_drug_nsaids_paracet_comm'].', ';}?>
						
					</strong></li>
			<?php } if($drug_allergies['ibupro_m']==1){?>
			<li style=""> Ibuprofen since  <strong style="font-size: 10px;"> 
				<?php if(!empty($drug_allergies['history_drug_nsaids_ibupro_dur'])){ echo $drug_allergies['history_drug_nsaids_ibupro_dur'].' '.$drug_allergies['history_drug_nsaids_ibupro_unit'];} if(!empty($drug_allergies['history_drug_nsaids_ibupro_comm'])){ echo ' - '.$drug_allergies['history_drug_nsaids_ibupro_comm'].', ';}?>
				</strong></li>
			<?php } if($drug_allergies['diclo_m']==1){?>
			<li style=""> Diclofenac since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_nsaids_diclo_dur'])){ echo $drug_allergies['history_drug_nsaids_diclo_dur'].' '.$drug_allergies['history_drug_nsaids_diclo_unit'];} if(!empty($drug_allergies['history_drug_nsaids_diclo_comm'])){ echo ' - '.$drug_allergies['history_drug_nsaids_diclo_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['aceclo_m']==1){?>
			<li style=""> Aceclofenac since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_nsaids_aceclo_dur'])){ echo $drug_allergies['history_drug_nsaids_aceclo_dur'].' '.$drug_allergies['history_drug_nsaids_aceclo_unit'];} if(!empty($drug_allergies['history_drug_nsaids_aceclo_comm'])){ echo ' - '.$drug_allergies['history_drug_nsaids_aceclo_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['napro_m']==1){?>
			<li style=""> Naproxen since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_nsaids_napro_dur'])){ echo $drug_allergies['history_drug_nsaids_napro_dur'].' '.$drug_allergies['history_drug_nsaids_napro_unit'];} if(!empty($drug_allergies['history_drug_nsaids_napro_comm'])){ echo ' - '.$drug_allergies['history_drug_nsaids_napro_comm'].', ';}?>
					
				</strong></li>
		<?php } } ?>

		<?php if($drug_allergies['eye_drops_m']==1){?>
			<?php if($drug_allergies['tropip_m']==1){?>
				<li style=""> Tropicamide P since  <strong style="font-size: 10px;">
					<?php if(!empty($drug_allergies['history_drug_eye_tropicp_dur'])){ echo $drug_allergies['history_drug_eye_tropicp_dur'].' '.$drug_allergies['history_drug_eye_tropicp_unit'];} if(!empty($drug_allergies['history_drug_eye_tropicp_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_tropicp_comm'].', ';}?>
						
					</strong></li>
			<?php } if($drug_allergies['tropi_m']==1){?>
				<li style=""> Tropicamide since  <strong style="font-size: 10px;">
					<?php if(!empty($drug_allergies['history_drug_eye_tropica_dur'])){ echo $drug_allergies['history_drug_eye_tropica_dur'].' '.$drug_allergies['history_drug_eye_tropica_unit'];} if(!empty($drug_allergies['history_drug_eye_tropica_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_tropica_comm'].', ';}?>
						
					</strong></li>
			<?php } if($drug_allergies['timolol_m']==1){?>
			<li style=""> Timolol since  <strong style="font-size: 10px;">
			<?php if(!empty($drug_allergies['history_drug_eye_timol_dur'])){ echo $drug_allergies['history_drug_eye_timol_dur'].' '.$drug_allergies['history_drug_eye_timol_unit'];} if(!empty($drug_allergies['history_drug_eye_timol_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_timol_comm'].', ';}?>
				
			</strong></li>
			<?php } if($drug_allergies['homide_m']==1){?>
			<li style=""> Homide since  <strong style="font-size: 10px;"> 
				<?php if(!empty($drug_allergies['history_drug_eye_homide_dur'])){ echo $drug_allergies['history_drug_eye_homide_dur'].' '.$drug_allergies['history_drug_eye_homide_unit'];} if(!empty($drug_allergies['history_drug_eye_homide_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_homide_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['brimo_m']==1){?>
			<li style=""> Brimonidine since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_eye_brimon_dur'])){ echo $drug_allergies['history_drug_eye_brimon_dur'].' '.$drug_allergies['history_drug_eye_brimon_unit'];} if(!empty($drug_allergies['history_drug_eye_brimon_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_brimon_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['latan_m']==1){?>
			<li style=""> Latanoprost since  <strong style="font-size: 10px;">
			<?php if(!empty($drug_allergies['history_drug_eye_latan_dur'])){ echo $drug_allergies['history_drug_eye_latan_dur'].' '.$drug_allergies['history_drug_eye_latan_unit'];} if(!empty($drug_allergies['history_drug_eye_latan_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_latan_comm'].', ';}?>
				
			</strong></li>


			<?php } if($drug_allergies['travo_m']==1){?>
				<li style=""> Travoprost since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_eye_travo_dur'])){ echo $drug_allergies['history_drug_eye_travo_dur'].' '.$drug_allergies['history_drug_eye_travo_unit'];} if(!empty($drug_allergies['history_drug_eye_travo_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_travo_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['tobra_m']==1){?>
			<li style=""> Tobramycin since  <strong style="font-size: 10px;">
			<?php if(!empty($drug_allergies['history_drug_eye_tobra_dur'])){ echo $drug_allergies['history_drug_eye_tobra_dur'].' '.$drug_allergies['history_drug_eye_tobra_unit'];} if(!empty($drug_allergies['history_drug_eye_tobra_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_tobra_comm'].', ';}?>
				
			</strong></li>
			<?php } if($drug_allergies['moxif_m']==1){?>
			<li style=""> Moxifloxacin since  <strong style="font-size: 10px;">
			<?php if(!empty($drug_allergies['history_drug_eye_moxif_dur'])){ echo $drug_allergies['history_drug_eye_moxif_dur'].' '.$drug_allergies['history_drug_eye_moxif_unit'];} if(!empty($drug_allergies['history_drug_eye_moxif_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_moxif_comm'].', ';}?>
				
			</strong></li>
			<?php } if($drug_allergies['homat_m']==1){?>
			<li style=""> Homatropine since  <strong style="font-size: 10px;">
			<?php if(!empty($drug_allergies['history_drug_eye_homat_dur'])){ echo $drug_allergies['history_drug_eye_homat_dur'].' '.$drug_allergies['history_drug_eye_homat_unit'];} if(!empty($drug_allergies['history_drug_eye_homat_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_homat_comm'].', ';}?>
				
			</strong></li>
			<?php } if($drug_allergies['piloc_m']==1){?>
			<li style=""> Pilocarpine since  <strong style="font-size: 10px;"> 
				<?php if(!empty($drug_allergies['history_drug_eye_piloca_dur'])){ echo $drug_allergies['history_drug_eye_piloca_dur'].' '.$drug_allergies['history_drug_eye_piloca_unit'];} if(!empty($drug_allergies['history_drug_eye_piloca_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_piloca_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['cyclop_m']==1){?>
				<li style=""> Cyclopentolate since  <strong style="font-size: 10px;">
				<?php if(!empty($drug_allergies['history_drug_eye_cyclop_dur'])){ echo $drug_allergies['history_drug_eye_cyclop_dur'].' '.$drug_allergies['history_drug_eye_cyclop_unit'];} if(!empty($drug_allergies['history_drug_eye_cyclop_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_cyclop_comm'].', ';}?>
					
				</strong></li>

			<?php } if($drug_allergies['atrop_m']==1){?>
			<li style=""> Atropine since  <strong style="font-size: 10px;"> 
				<?php if(!empty($drug_allergies['history_drug_eye_atropi_dur'])){ echo $drug_allergies['history_drug_eye_atropi_dur'].' '.$drug_allergies['history_drug_eye_atropi_unit'];} if(!empty($drug_allergies['history_drug_eye_atropi_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_atropi_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['phenyl_m']==1){?>
			<li style=""> Phenylephrine since  <strong style="font-size: 10px;"> 
				<?php if(!empty($drug_allergies['history_drug_eye_phenyl_dur'])){ echo $drug_allergies['history_drug_eye_phenyl_dur'].' '.$drug_allergies['history_drug_eye_phenyl_unit'];} if(!empty($drug_allergies['history_drug_eye_phenyl_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_phenyl_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['tropic_m']==1){?>
			<li style=""> Tropicacyl since  <strong style="font-size: 10px;"> 
				<?php if(!empty($drug_allergies['history_drug_eye_tropicac_dur'])){ echo $drug_allergies['history_drug_eye_tropicac_dur'].' '.$drug_allergies['history_drug_eye_tropicac_unit'];} if(!empty($drug_allergies['history_drug_eye_tropicac_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_tropicac_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['parac_m']==1){?>
			<li style=""> Paracain since  <strong style="font-size: 10px;"> 
				<?php if(!empty($drug_allergies['history_drug_eye_paracain_dur'])){ echo $drug_allergies['history_drug_eye_paracain_dur'].' '.$drug_allergies['history_drug_eye_paracain_unit'];} if(!empty($drug_allergies['history_drug_eye_paracain_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_paracain_comm'].', ';}?>
					
				</strong></li>
			<?php } if($drug_allergies['ciplox_m']==1){?>
			<li style=""> Ciplox since  <strong style="font-size: 10px;">
			<?php if(!empty($drug_allergies['history_drug_eye_ciplox_dur'])){ echo $drug_allergies['history_drug_eye_ciplox_dur'].' '.$drug_allergies['history_drug_eye_ciplox_unit'];} if(!empty($drug_allergies['history_drug_eye_ciplox_comm'])){ echo ' - '.$drug_allergies['history_drug_eye_ciplox_comm'].', ';}?>
				
			</strong></li>
		<?php } } ?>


		<?php if($contact_allergies['alco_m']==1){?>
		<li style=""> Allergic to Alcohal since  <strong style="font-size: 10px;">
			<?php if(!empty($contact_allergies['history_contact_alcohol_dur'])){ echo $contact_allergies['history_contact_alcohol_dur'].' '.$contact_allergies['history_contact_alcohol_unit'];} if(!empty($contact_allergies['history_contact_alcohol_comm'])){ echo ' - '.$contact_allergies['history_contact_alcohol_comm'].', ';}?>

			</strong></li>
		<?php } if($contact_allergies['latex_m']==1){?>
			<li style=""> Latex since  <strong style="font-size: 10px;">
			<?php if(!empty($contact_allergies['history_contact_latex_dur'])){ echo $contact_allergies['history_contact_latex_dur'].' '.$contact_allergies['history_contact_latex_unit'];} if(!empty($contact_allergies['history_contact_latex_comm'])){ echo ' - '.$contact_allergies['history_contact_latex_comm'].', ';}?>
				
			</strong></li>
		<?php } if($contact_allergies['betad_m']==1){?>
			<li style=""> Betadine since  <strong style="font-size: 10px;">
			<?php if(!empty($contact_allergies['history_contact_betad_dur'])){ echo $contact_allergies['history_contact_betad_dur'].' '.$contact_allergies['history_contact_betad_unit'];} if(!empty($contact_allergies['history_contact_betad_comm'])){ echo ' - '.$contact_allergies['history_contact_betad_comm'].', ';}?>
				
			</strong></li>
		<?php } if($contact_allergies['adhes_m']==1){?>
			<li style=""> Adhesive Tape since  <strong style="font-size: 10px;">
				<?php if(!empty($contact_allergies['history_contact_adhes_dur'])){ echo $contact_allergies['history_contact_adhes_dur'].' '.$contact_allergies['history_contact_adhes_unit'];} if(!empty($contact_allergies['history_contact_adhes_comm'])){ echo ' - '.$contact_allergies['history_contact_adhes_comm'].', ';}?>
					
				</strong></li>
		<?php } if($contact_allergies['tegad_m']==1){?>
			<li style=""> Tegaderm since  <strong style="font-size: 10px;">
			<?php if(!empty($contact_allergies['history_contact_tegad_dur'])){ echo $contact_allergies['history_contact_tegad_dur'].' '.$contact_allergies['history_contact_tegad_unit'];} if(!empty($contact_allergies['history_contact_tegad_comm'])){ echo ' - '.$contact_allergies['history_contact_tegad_comm'].', ';}?>
				
			</strong></li>
		<?php } if($contact_allergies['trans_m']==1){?>
			<li style=""> Transpore since  <strong style="font-size: 10px;">
			<?php if(!empty($contact_allergies['history_contact_transp_dur'])){ echo $contact_allergies['history_contact_transp_dur'].' '.$contact_allergies['history_contact_transp_unit'];} if(!empty($contact_allergies['history_contact_transp_comm'])){ echo ' - '.$contact_allergies['history_contact_transp_comm'].', ';}?>
				
			</strong></li>
		<?php }?>


		<?php if($food_allergies['seaf_m']==1){?>
		<li style=""> All Seafood since  <strong style="font-size: 10px;">
			<?php if(!empty($food_allergies['history_food_seaf_dur'])){ echo $food_allergies['history_food_seaf_dur'].' '.$food_allergies['history_food_seaf_unit'];} if(!empty($food_allergies['history_food_seaf_comm'])){ echo ' - '.$food_allergies['history_food_seaf_comm'].', ';}?>
				
			</strong></li>
		<?php } if($food_allergies['corn_m']==1){?>
			<li style=""> Corn since  <strong style="font-size: 10px;">
			<?php if(!empty($food_allergies['history_food_corn_dur'])){ echo $food_allergies['history_food_corn_dur'].' '.$food_allergies['history_food_corn_unit'];} if(!empty($food_allergies['history_food_corn_comm'])){ echo ' - '.$food_allergies['history_food_corn_comm'].', ';}?>
				
			</strong></li>
		<?php } if($food_allergies['egg_m']==1){?>
			<li style=""> Egg since  <strong style="font-size: 10px;">
			<?php if(!empty($food_allergies['history_food_egg_dur'])){ echo $food_allergies['history_food_egg_dur'].' '.$food_allergies['history_food_egg_unit'];} if(!empty($food_allergies['history_food_egg_comm'])){ echo ' - '.$food_allergies['history_food_egg_comm'].', ';}?>

			</strong></li>
		<?php } if($food_allergies['milk_m']==1){?>
			<li style=""> Milk Proteins since  <strong style="font-size: 10px;">
			<?php if(!empty($food_allergies['history_food_milk_p_dur'])){ echo $food_allergies['history_food_milk_p_dur'].' '.$food_allergies['history_food_milk_p_unit'];} if(!empty($food_allergies['history_food_milk_p_comm'])){ echo ' - '.$food_allergies['history_food_milk_p_comm'].', ';}?>
				
			</strong></li>
		<?php } if($food_allergies['pean_m']==1){?>
			<li style=""> Peanuts since  <strong style="font-size: 10px;">
			<?php if(!empty($food_allergies['history_food_pean_dur'])){ echo $food_allergies['history_food_pean_dur'].' '.$food_allergies['history_food_pean_unit'];} if(!empty($food_allergies['history_food_pean_comm'])){ echo ' - '.$food_allergies['history_food_pean_comm'].', ';}?>
				
			</strong></li>
		<?php } if($food_allergies['shell_m']==1){?>
			<li style=""> Shellfish Only since  <strong style="font-size: 10px;">
			<?php if(!empty($food_allergies['history_food_shell_dur'])){ echo $food_allergies['history_food_shell_dur'].' '.$food_allergies['history_food_shell_unit'];} if(!empty($food_allergies['history_food_shell_comm'])){ echo ' - '.$food_allergies['history_food_shell_comm'].', ';}?>
				
			</strong></li>
		<?php } if($food_allergies['soy_m']==1){?>
			<li style=""> Soy Protein since  <strong style="font-size: 10px;">
			<?php if(!empty($food_allergies['history_food_soy_dur'])){ echo $food_allergies['history_food_soy_dur'].' '.$food_allergies['history_food_soy_unit'];} if(!empty($food_allergies['history_food_soy_comm'])){ echo ' - '.$food_allergies['history_food_soy_comm'].', ';}?>
				
			</strong></li>
		<?php } if($food_allergies['lact_m']==1){?>
			<li style=""> Lactose since  <strong style="font-size: 10px;">
			<?php if(!empty($food_allergies['history_food_lact_dur'])){ echo $food_allergies['history_food_lact_dur'].' '.$food_allergies['history_food_lact_unit'];} if(!empty($food_allergies['history_food_lact_comm'])){ echo ' - '.$food_allergies['history_food_lact_comm'].', ';}?>
				
			</strong></li>
		<?php } if($food_allergies['mush_m']==1){?>
			<li style=""> Mushroom since  <strong style="font-size: 10px;">
			<?php if(!empty($food_allergies['history_food_mush_dur'])){ echo $food_allergies['history_food_mush_dur'].' '.$food_allergies['history_food_mush_unit'];} if(!empty($food_allergies['history_food_mush_comm'])){ echo ' - '.$food_allergies['history_food_mush_comm'].', ';}?>				
			</strong></li>
		<?php } if($food_allergies['history_food_comm'] !=''){?>

			<li style="margin-top:8px;">  <strong style="font-size: 10px;"> <?php echo ' - '.$food_allergies['history_food_comm'].', ';?>  </strong></li>
		<?php }?>

		</ul>
		<?php $his='';
		foreach ($history as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $his=1;
        }} 
        if($his==1){?>	
		<strong style="float:left;width:100%;font-size:10px;">VITAL SIGNS/ ANTHROPOMETRY</strong>
		<hr style="margin: 2px !important;">
		<table class="table-border" width="100%" style="font-size:11px;">
			<tr>
			<?php if($history['temperature'] !='0'){?>
				<td>Temperature :  <strong style="color: <?php echo $food_allergies['history_vital_temp_update'];?>"> <?php echo $history['temperature'].' °C';?></strong></td>
			<?php } if($history['pulse'] !='0'){?>
				<td>Pulse :   <strong style="color: <?php echo $food_allergies['history_vital_pulse_update'];?>"> <?php echo $history['pulse'].' bpm';?></strong></td>
			<?php } if($history['blood_pressure'] !='0'){?>
				<td>RR :    <strong style="color: <?php echo $food_allergies['history_vital_bp_update'];?>"> <?php echo $history['blood_pressure'].' mmHg';?></strong> </td>
			<?php } if($history['rr'] !='0'){?>
				<td>BP :   <strong style="">  <?php echo $history['rr'].' brpm';?></strong></td>
			<?php } ?>
			</tr>
			<tr>
			<?php if($history['height'] !='0'){?>
				<td>Height :   <strong style=""> <?php echo $history['height'].' cms';?></strong></td>
			<?php } if($history['weight'] !='0.00'){?>
				<td>Weight :   <strong style=""> <?php echo $history['weight'].' kg';?></strong></td>
			<?php } if($history['bmi'] !='0.00'){?>
				<td>BMI :  <strong style="">  <?php echo $history['bmi'].' kg/m2';?></strong></td>
			<?php } ?>
			</tr>
			<tr>
			<?php if($history['comment'] !=''){?>
				<td colspan="3">Vital comments : <?php echo $history['comment'];?></td>
			<?php } ?>
			</tr>
		</table>
		<?php } } if($form_data['contactlens_flag']==1){ ?>

		<?php 
		$refrtsn_cnt_lns='';
		foreach ($refrtsn_cntct_lns as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $refrtsn_cnt_lns=1;
        }} 
        if($refrtsn_cnt_lns==1){?>
		<br>
		
		<section style="float:left;width:100%;">
			<strong style="float:left;width:100%;margin-top: 100px;">CONTACT LENS PRESCRIPTIONS</strong>
		      <hr style="margin: 2px !important;">
			<div style="float:left;width:48.5%;padding:0px 5px;">
				<div style="text-align:center;background:#ddd;padding:3px;">R/OD</div>
				<table border="1" cellpadding="0" cellspacing="0" width="100%" style=";font-size:11px;border-collapse:collapse;">
					<tr>
						<td style="padding:3px;" valign="top">BC</td>
						<td style="padding:3px;" valign="top">DIA</td>
						<td style="padding:3px;" valign="top">SPH</td>
						<td style="padding:3px;" valign="top">CYL</td>
						<td style="padding:3px;" valign="top">AXIS</td>
						<td style="padding:3px;" valign="top">ADD</td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_cntct_lns['refraction_clp_l_bc'] !=''){ echo $refrtsn_cntct_lns['refraction_clp_l_bc'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_cntct_lns['refraction_clp_l_dia'] !=''){ echo $refrtsn_cntct_lns['refraction_clp_l_dia'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_cntct_lns['refraction_clp_l_sph'] !=''){ echo $refrtsn_cntct_lns['refraction_clp_l_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_cntct_lns['refraction_clp_l_cyl'] !=''){ echo $refrtsn_cntct_lns['refraction_clp_l_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_cntct_lns['refraction_clp_l_axis'] !=''){ echo $refrtsn_cntct_lns['refraction_clp_l_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_cntct_lns['refraction_clp_l_add'] !=''){ echo $refrtsn_cntct_lns['refraction_clp_l_add'];}else {echo '--';}?></td>
					</tr>
				</table>
				<table width="100%" style="font-size:11px">
					<tr>
						<?php if(!empty($refrtsn_cntct_lns['refraction_clp_l_clr'])){?>
						<td style="padding:3px;" valign="top">Color : <strong style="font-size: 10px;"><?php echo $refrtsn_cntct_lns['refraction_clp_l_clr'];?> </strong></td>
						<?php } if(!empty($refrtsn_cntct_lns['refraction_clp_l_tp'])){?>
						<td style="padding:3px;" valign="top">Type : <strong style="font-size: 10px;"><?php echo $refrtsn_cntct_lns['refraction_clp_l_tp'];?></strong></td>
						<?php } ?>
					</tr>
					<tr>
						<?php if(!empty($refrtsn_cntct_lns['refraction_clp_l_advice'])){?>
						<td style="padding:3px;" valign="top">Advice :  <strong style="font-size: 10px;"><?php echo $refrtsn_cntct_lns['refraction_clp_l_advice'];?></strong> </td>
						<?php } if(!empty($refrtsn_cntct_lns['refraction_clp_l_rv_date'])){?>
						<td style="padding:3px;" valign="top">Revisit Date :  <strong style="font-size: 10px;"><?php if(!empty($refrtsn_cntct_lns['refraction_clp_l_rv_date'])){ echo date('d/m/Y',strtotime($refrtsn_cntct_lns['refraction_clp_l_rv_date']));}?></strong></td>
						<?php } ?>
					</tr>
				</table>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;">
				<div style="text-align:center;background:#ddd;padding:3px;">L/OS</div>
				<table border="1" cellpadding="0" cellspacing="0" width="100%" style=";font-size:11px;border-collapse:collapse;">
					<tr>
						<td style="padding:3px;" valign="top">BC</td>
						<td style="padding:3px;" valign="top">DIA</td>
						<td style="padding:3px;" valign="top">SPH</td>
						<td style="padding:3px;" valign="top">CYL</td>
						<td style="padding:3px;" valign="top">AXIS</td>
						<td style="padding:3px;" valign="top">ADD</td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_cntct_lns['refraction_clp_r_bc'] !=''){ echo $refrtsn_cntct_lns['refraction_clp_r_bc'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_cntct_lns['refraction_clp_r_dia'] !=''){ echo $refrtsn_cntct_lns['refraction_clp_r_dia'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_cntct_lns['refraction_clp_r_sph'] !=''){ echo $refrtsn_cntct_lns['refraction_clp_r_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_cntct_lns['refraction_clp_r_cyl'] !=''){ echo $refrtsn_cntct_lns['refraction_clp_r_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_cntct_lns['refraction_clp_r_axis'] !=''){ echo $refrtsn_cntct_lns['refraction_clp_r_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_cntct_lns['refraction_clp_r_add'] !=''){ echo $refrtsn_cntct_lns['refraction_clp_r_add'];}else {echo '--';}?></td>
					</tr>
				</table>

				<table width="100%" style="font-size:11px">
					<tr>
						<?php if(!empty($refrtsn_cntct_lns['refraction_clp_r_clr'])){?>
						<td style="padding:3px;" valign="top">Color : <strong style="font-size: 10px;"><?php echo $refrtsn_cntct_lns['refraction_clp_r_clr'];?> </strong></td>
						<?php } if(!empty($refrtsn_cntct_lns['refraction_clp_r_tp'])){?>
						<td style="padding:3px;" valign="top">Type : <strong style="font-size: 10px;"><?php echo $refrtsn_cntct_lns['refraction_clp_r_tp'];?></strong></td>
						<?php } ?>
					</tr>
					<tr>
						<?php if(!empty($refrtsn_cntct_lns['refraction_clp_r_advice'])){?>
						<td style="padding:3px;" valign="top">Advice :  <strong style="font-size: 10px;"><?php echo $refrtsn_cntct_lns['refraction_clp_r_advice'];?></strong> </td>
						<?php } if(!empty($refrtsn_cntct_lns['refraction_clp_r_rv_date'])){?>
						<td style="padding:3px;" valign="top">Revisit Date :  <strong style="font-size: 10px;"><?php if(!empty($refrtsn_cntct_lns['refraction_clp_r_rv_date'])){ echo date('d/m/Y',strtotime($refrtsn_cntct_lns['refraction_clp_r_rv_date']));}?></strong></td>
						<?php } ?>
					</tr>
				</table>
			</div>
		</section>

	<?php } } if($form_data['glassesprescriptions_flag']==1){ 

		$glasns='';
		foreach ($refrtsn_glassp as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $glasns=1;
        }} 
        if($glasns==1){?>
	
		
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
					<?php if(!empty($refrtsn_glassp['refraction_gps_l_advs'])){ echo 'Advice : <strong style="font-size: 10px;"> '.$refrtsn_glassp['refraction_gps_l_advs'].' | </strong>';}
					if(!empty($refrtsn_glassp['refraction_gps_l_tol'])){ echo 'Type of Lens- <strong style="font-size: 10px;"> '.$refrtsn_glassp['refraction_gps_l_tol'].' | </strong>';} 
					if(!empty($refrtsn_glassp['refraction_gps_l_ipd'])){ echo 'IPD - <strong style="font-size: 10px;"> '.$refrtsn_glassp['refraction_gps_l_ipd'].' | </strong>';}
					if(!empty($refrtsn_glassp['refraction_gps_l_lns_mat'])){ echo 'Lens Material- <strong style="font-size: 10px;"> '.$refrtsn_glassp['refraction_gps_l_lns_mat'].' | </strong>';}
					if(!empty($refrtsn_glassp['refraction_gps_l_lns_tnt'])){ echo 'Lens Tint- <strong style="font-size: 10px;"> '.$refrtsn_glassp['refraction_gps_l_lns_tnt'].' | </strong>';}
					if(!empty($refrtsn_glassp['refraction_gps_l_fm'])){ echo 'Frame Material- <strong style="font-size: 10px;"> '.$refrtsn_glassp['refraction_gps_l_fm'].' | </strong>';}?>
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
						<?php if(!empty($refrtsn_glassp['refraction_gps_r_advs'])){ echo 'Advice : <strong style="font-size: 10px;"> '.$refrtsn_glassp['refraction_gps_r_advs'].' | </strong>';}
						if(!empty($refrtsn_glassp['refraction_gps_r_tol'])){ echo 'Type of Lens- <strong style="font-size: 10px;"> '.$refrtsn_glassp['refraction_gps_r_tol'].' | </strong>';} 
						if(!empty($refrtsn_glassp['refraction_gps_r_ipd'])){ echo 'IPD - <strong style="font-size: 10px;"> '.$refrtsn_glassp['refraction_gps_r_ipd'].' | </strong>';}
						if(!empty($refrtsn_glassp['refraction_gps_r_lns_mat'])){ echo 'Lens Material- <strong style="font-size: 10px;"> '.$refrtsn_glassp['refraction_gps_r_lns_mat'].' | </strong>';}
						if(!empty($refrtsn_glassp['refraction_gps_r_lns_tnt'])){ echo 'Lens Tint- <strong style="font-size: 10px;"> '.$refrtsn_glassp['refraction_gps_r_lns_tnt'].' | </strong>';}
						if(!empty($refrtsn_glassp['refraction_gps_r_fm'])){ echo 'Frame Material- <strong style="font-size: 10px;"> '.$refrtsn_glassp['refraction_gps_r_fm'].' | </strong>';}?>
					</div>
			</div>
		</section>	
		<?php } } if($form_data['intermediate_glasses_prescriptions_flag']==1){ 

		$interme_glas='';
		foreach ($refrtsn_inter_glass as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $interme_glas=1;
        }} 
        if($interme_glas==1){?>

		<br>
		
		<section style="float:left;width:100%;">
			<strong style="font-size: 10px;">INTERMEDIATE GLASSES PRESCRIPTIONS</strong>
			<hr style="margin: 2px !important;">
			<div style="float:left;width:48.5%;padding:0px 5px;">
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
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_l_dt_sph'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_l_dt_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_l_dt_cyl'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_l_dt_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_l_dt_axis'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_l_dt_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_l_dt_vision'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_l_dt_vision'];}else {echo '--';}?></td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Add</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_l_ad_sph'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_l_ad_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_l_ad_cyl'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_l_ad_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_l_ad_axis'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_l_ad_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_l_ad_vision'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_l_ad_vision'];}else {echo '--';}?></td>
					</tr>

					<tr>
						<td style="padding:3px;" valign="top">Near</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_l_nr_sph'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_l_nr_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_l_nr_cyl'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_l_nr_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_l_nr_axis'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_l_nr_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_l_nr_vision'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_l_nr_vision'];}else {echo '--';}?></td>
					</tr>
				</table>
				<br>
				<div style="font-size:11px;">
					<?php if(!empty($refrtsn_inter_glass['refraction_itgp_l_advs'])){ echo 'Advice : <strong style="font-size: 10px;"> '.$refrtsn_inter_glass['refraction_itgp_l_advs'].' | </strong>';}
						if(!empty($refrtsn_inter_glass['refraction_itgp_l_tol'])){ echo 'Type of Lens- <strong style="font-size: 10px;"> '.$refrtsn_inter_glass['refraction_itgp_l_tol'].' | </strong>';} 
						if(!empty($refrtsn_inter_glass['refraction_itgp_l_ipd'])){ echo 'IPD - <strong style="font-size: 10px;"> '.$refrtsn_inter_glass['refraction_itgp_l_ipd'].'mm | </strong>';}
						if(!empty($refrtsn_inter_glass['refraction_itgp_l_lns_mat'])){ echo 'Lens Material- <strong style="font-size: 10px;"> '.$refrtsn_inter_glass['refraction_itgp_l_lns_mat'].' | </strong>';}
						if(!empty($refrtsn_inter_glass['refraction_itgp_l_lns_tnt'])){ echo 'Lens Tint- <strong style="font-size: 10px;"> '.$refrtsn_inter_glass['refraction_itgp_l_lns_tnt'].' | </strong>';}
						if(!empty($refrtsn_inter_glass['refraction_itgp_l_fm'])){ echo 'Frame Material- <strong style="font-size: 10px;"> '.$refrtsn_inter_glass['refraction_itgp_l_fm'].' | </strong>';}?>
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
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_l_dt_sph'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_r_dt_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_r_dt_cyl'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_r_dt_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_r_dt_axis'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_r_dt_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_r_dt_vision'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_r_dt_vision'];}else {echo '--';}?></td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Add</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_r_ad_sph'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_r_ad_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_r_ad_cyl'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_r_ad_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_r_ad_axis'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_r_ad_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_r_ad_vision'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_r_ad_vision'];}else {echo '--';}?></td>
					</tr>

					<tr>
						<td style="padding:3px;" valign="top">Near</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_r_nr_sph'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_r_nr_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_r_nr_cyl'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_r_nr_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_r_nr_axis'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_r_nr_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_inter_glass['refraction_itgp_r_nr_vision'] !=''){ echo $refrtsn_inter_glass['refraction_itgp_r_nr_vision'];}else {echo '--';}?></td>
					</tr>
				</table>
				<br>
				<div style="font-size:11px;">
					<?php if(!empty($refrtsn_inter_glass['refraction_itgp_r_advs'])){ echo 'Advice : <strong style="font-size: 10px;"> '.$refrtsn_inter_glass['refraction_itgp_r_advs'].' | </strong>';}
						if(!empty($refrtsn_inter_glass['refraction_itgp_r_tol'])){ echo 'Type of Lens- <strong style="font-size: 10px;"> '.$refrtsn_inter_glass['refraction_itgp_r_tol'].' | </strong>';} 
						if(!empty($refrtsn_inter_glass['refraction_itgp_r_ipd'])){ echo 'IPD - <strong style="font-size: 10px;"> '.$refrtsn_inter_glass['refraction_itgp_r_ipd'].'mm | </strong>';}
						if(!empty($refrtsn_inter_glass['refraction_itgp_r_lns_mat'])){ echo 'Lens Material- <strong style="font-size: 10px;"> '.$refrtsn_inter_glass['refraction_itgp_r_lns_mat'].' | </strong>';}
						if(!empty($refrtsn_inter_glass['refraction_itgp_r_lns_tnt'])){ echo 'Lens Tint- <strong style="font-size: 10px;"> '.$refrtsn_inter_glass['refraction_itgp_r_lns_tnt'].' | </strong>';}
						if(!empty($refrtsn_inter_glass['refraction_itgp_r_fm'])){ echo 'Frame Material- <strong style="font-size: 10px;"> '.$refrtsn_inter_glass['refraction_itgp_r_fm'].' | </strong>';}?>
				</div>
			</div>
		</section>	

		<?php } } if($form_data['examination_flag']==1){
		$vl_act='';
		foreach ($refrtsn_vl_act as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $vl_act=1;
        }} 
        //echo $refrtsn_intrap['refraction_intra_press_r_mg']; die;
       // echo $vl_act; die;
       // if($vl_act==1){?>
		<section style="float:left;width:100%;">
		<strong style="font-size:11px">EXAMINATION</strong>
		   <hr style="margin: 2px !important;">
			<div style="float:left;width:48.5%;padding:0px 5px;">
				<div style="text-align:center;background:#ddd;padding:3px;margin-bottom:5px;">R/OD</div>
				<div style="font-size:11px;">
					<strong style="font-size: 10px;">VA: </strong><?php if($refrtsn_vl_act['refraction_va_ua_l'] !='0'){ echo 'UA - '.$refrtsn_vl_act['refraction_va_ua_l'];} if(!empty($refrtsn_vl_act['refraction_va_ua_l_p'])){ echo '('.$refrtsn_vl_act['refraction_va_ua_l_p'].') ';} if(!empty($refrtsn_vl_act['refraction_va_ua_l_txt'])){ echo '('.$refrtsn_vl_act['refraction_va_ua_l_txt'].')';} ?> 
					<?php if(!empty($refrtsn_vl_act['refraction_va_ua_l_2'])){ echo 'UA Near - '.$refrtsn_vl_act['refraction_va_ua_l_2'];} if(!empty($refrtsn_vl_act['refraction_va_ua_l_p_2'])){ echo '('.$refrtsn_vl_act['refraction_va_ua_l_p_2'].') ';} if(!empty($refrtsn_vl_act['refraction_va_ua_l_2_txt'])){ echo '('.$refrtsn_vl_act['refraction_va_ua_l_2_txt'].')';} ?> 
					<?php if(!empty($refrtsn_vl_act['refraction_va_ph_l'])){ echo ' PH - '.$refrtsn_vl_act['refraction_va_ph_l'];} if(!empty($refrtsn_vl_act['refraction_va_ph_l_p'])){ echo '('.$refrtsn_vl_act['refraction_va_ph_l_p'].') ';} if(!empty($refrtsn_vl_act['refraction_va_ph_l_ni'])){ echo '('.$refrtsn_vl_act['refraction_va_ph_l_ni'].')';} if(!empty($refrtsn_vl_act['refraction_va_ph_l_txt'])){ echo '  ('.$refrtsn_vl_act['refraction_va_ph_l_txt'].')';} ?>
					<?php if(!empty($refrtsn_vl_act['refraction_va_gls_l'])){ echo ' Glasses - '.$refrtsn_vl_act['refraction_va_gls_l'];} if(!empty($refrtsn_vl_act['refraction_va_gls_l_p'])){ echo '('.$refrtsn_vl_act['refraction_va_gls_l_p'].') ';} if(!empty($refrtsn_vl_act['refraction_va_gls_l_txt'])){ echo '('.$refrtsn_vl_act['refraction_va_gls_l_txt'].')';} ?> 
					 <?php if(!empty($refrtsn_vl_act['refraction_va_cl_l'])){echo ' Contact Lens - '.$refrtsn_vl_act['refraction_va_cl_l'];} if(!empty($refrtsn_vl_act['refraction_va_cl_l_p'])){'('.$refrtsn_vl_act['refraction_va_cl_l_p'].') ';} if(!empty($refrtsn_vl_act['refraction_va_cl_l_txt'])){ echo '('.$refrtsn_vl_act['refraction_va_cl_l_txt'].')';}?> </strong>
					<?php if(!empty($refrtsn_vl_act['refraction_va_gls_l_2'])){echo ' Glasses Near - '.$refrtsn_vl_act['refraction_va_gls_l_2'];} if(!empty($refrtsn_vl_act['refraction_va_gls_l_p_2'])){ echo '('.$refrtsn_vl_act['refraction_va_gls_l_p_2'].') ';} if(!empty($refrtsn_vl_act['refraction_va_gls_l_2_txt'])){ echo '('.$refrtsn_vl_act['refraction_va_gls_l_2_txt'].')';}?> 
					
					<strong style="font-size: 10px;"><?php if(!empty($refrtsn_vl_act['refraction_va_l_comm'])){ echo '('.$refrtsn_vl_act['refraction_va_l_comm'].')';}?><?php if(!empty($refrtsn_vl_act['refraction_va_ua_l_pr_s']) || !empty($refrtsn_vl_act['refraction_va_ua_l_pr_i']) || !empty($refrtsn_vl_act['refraction_va_ua_l_pr_n']) || !empty($refrtsn_vl_act['refraction_va_ua_l_pr_t'])){ ?> PR(UA): 
						<?php if(!empty($refrtsn_vl_act['refraction_va_ua_l_pr_s'])){echo ' S '.$refrtsn_vl_act['refraction_va_ua_l_pr_s']; }
						if(!empty($refrtsn_vl_act['refraction_va_ua_l_pr_i'])){echo ' I '.$refrtsn_vl_act['refraction_va_ua_l_pr_i']; }
						if(!empty($refrtsn_vl_act['refraction_va_ua_l_pr_n'])){echo ' N '.$refrtsn_vl_act['refraction_va_ua_l_pr_n']; }
						if(!empty($refrtsn_vl_act['refraction_va_ua_l_pr_t'])){echo ' T '.$refrtsn_vl_act['refraction_va_ua_l_pr_t']; }?>
					<br>
					<?php } ?>
					<div style="color:<?php echo $refrtsn_intrap['refraction_intra_l_update'];?>">   <?php if(!empty($refrtsn_intrap['refraction_intra_press_l_mg'])){ echo 'IOP: '.$refrtsn_intrap['refraction_intra_press_l_mg'].' at '.$refrtsn_intrap['refraction_intra_press_l_time'].'-'.$refrtsn_intrap['refraction_intra_press_l_comm'];}?>  </div>  </strong>
				</div>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;">
				<div style="text-align:center;background:#ddd;padding:3px;margin-bottom:5px;">L/OS</div>
				<div style="font-size:11px;">
					<strong style="font-size: 10px;">VA: </strong><?php if($refrtsn_vl_act['refraction_va_ua_r'] !='0'){ echo 'UA - '.$refrtsn_vl_act['refraction_va_ua_r'];} if(!empty($refrtsn_vl_act['refraction_va_ua_r_p'])){ echo '('.$refrtsn_vl_act['refraction_va_ua_r_p'].') ';} if(!empty($refrtsn_vl_act['refraction_va_ua_r_txt'])){ echo '('.$refrtsn_vl_act['refraction_va_ua_r_txt'].')';} ?> 
					<?php if(!empty($refrtsn_vl_act['refraction_va_ua_r_2'])){ echo 'UA Near - '.$refrtsn_vl_act['refraction_va_ua_r_2'];} if(!empty($refrtsn_vl_act['refraction_va_ua_r_p_2'])){ echo '('.$refrtsn_vl_act['refraction_va_ua_r_p_2'].') ';} if(!empty($refrtsn_vl_act['refraction_va_ua_r_2_txt'])){ echo '('.$refrtsn_vl_act['refraction_va_ua_r_2_txt'].')';} ?> 
					<?php if(!empty($refrtsn_vl_act['refraction_va_ph_r'])){ echo ' PH - '.$refrtsn_vl_act['refraction_va_ph_r'];} if(!empty($refrtsn_vl_act['refraction_va_ph_r_p'])){ echo '('.$refrtsn_vl_act['refraction_va_ph_r_p'].') ';} if(!empty($refrtsn_vl_act['refraction_va_ph_r_ni'])){ echo '('.$refrtsn_vl_act['refraction_va_ph_r_ni'].')';} if(!empty($refrtsn_vl_act['refraction_va_ph_r_txt'])){ echo '  ('.$refrtsn_vl_act['refraction_va_ph_r_txt'].')';} ?>
					<?php if(!empty($refrtsn_vl_act['refraction_va_gls_r'])){ echo ' Glasses - '.$refrtsn_vl_act['refraction_va_gls_r'];} if(!empty($refrtsn_vl_act['refraction_va_gls_r_p'])){ echo '('.$refrtsn_vl_act['refraction_va_gls_r_p'].') ';} if(!empty($refrtsn_vl_act['refraction_va_gls_r_txt'])){ echo '('.$refrtsn_vl_act['refraction_va_gls_r_txt'].')';} ?> 
					 <?php if(!empty($refrtsn_vl_act['refraction_va_cl_r'])){echo ' Contact Lens - '.$refrtsn_vl_act['refraction_va_cl_r'];} if(!empty($refrtsn_vl_act['refraction_va_cl_r_p'])){'('.$refrtsn_vl_act['refraction_va_cl_r_p'].') ';} if(!empty($refrtsn_vl_act['refraction_va_cl_r_txt'])){ echo '('.$refrtsn_vl_act['refraction_va_cl_r_txt'].')';}?> 
					<?php if(!empty($refrtsn_vl_act['refraction_va_gls_r_2'])){echo ' Glasses Near - '.$refrtsn_vl_act['refraction_va_gls_r_2'];} if(!empty($refrtsn_vl_act['refraction_va_gls_r_p_2'])){ echo '('.$refrtsn_vl_act['refraction_va_gls_r_p_2'].') ';} if(!empty($refrtsn_vl_act['refraction_va_gls_r_2_txt'])){ echo '('.$refrtsn_vl_act['refraction_va_gls_r_2_txt'].')';}?> 
					
					<strong style="font-size: 10px;"><?php if(!empty($refrtsn_vl_act['refraction_va_r_comm'])){ echo '('.$refrtsn_vl_act['refraction_va_r_comm'].')';}?> <?php if(!empty($refrtsn_vl_act['refraction_va_ua_r_pr_s']) || !empty($refrtsn_vl_act['refraction_va_ua_r_pr_i']) || !empty($refrtsn_vl_act['refraction_va_ua_r_pr_n']) || !empty($refrtsn_vl_act['refraction_va_ua_r_pr_t']) ){ ?> PR(UA): 
						<?php if(!empty($refrtsn_vl_act['refraction_va_ua_r_pr_s'])){echo ' S '.$refrtsn_vl_act['refraction_va_ua_r_pr_s']; }
						if(!empty($refrtsn_vl_act['refraction_va_ua_r_pr_i'])){echo ' I '.$refrtsn_vl_act['refraction_va_ua_r_pr_i']; }
						if(!empty($refrtsn_vl_act['refraction_va_ua_r_pr_n'])){echo ' N '.$refrtsn_vl_act['refraction_va_ua_r_pr_n']; }
						if(!empty($refrtsn_vl_act['refraction_va_ua_r_pr_t'])){echo ' T '.$refrtsn_vl_act['refraction_va_ua_r_pr_t']; }?>
					<br>
					<?php } ?>
					<div style="color:<?php echo $refrtsn_intrap['refraction_intra_r_update'];?>">   <?php if(!empty($refrtsn_intrap['refraction_intra_press_r_mg'])){ echo 'IOP: '.$refrtsn_intrap['refraction_intra_press_r_mg'].' at '.$refrtsn_intrap['refraction_intra_press_r_time'].'-'.$refrtsn_intrap['refraction_intra_press_r_comm'];}?>  </div>  </strong>
				</div>
			</div>
		</section>	
		<?php //}
		$kerats='';
		foreach ($refrtsn_kerat as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $kerats=1;
        }} 
        if($kerats==1){?>
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;">
			<strong style="float:left;width:100%;font-size:10px;">Keratometry:</strong>
				<table border="1" cellpadding="0" cellspacing="0" width="100%" style=";font-size:11px;border-collapse:collapse;">
					<tr>
						<td style="padding:3px;" valign="top"></td>
						<td style="padding:3px;" valign="top"></td>
						<td style="padding:3px;" valign="top">Axis</td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Kh</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_kerat['refraction_km_l_kh'] !=''){ echo $refrtsn_kerat['refraction_km_l_kh'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_kerat['refraction_km_l_kh_a'] !=''){ echo $refrtsn_kerat['refraction_km_l_kh_a'];}else {echo '--';}?></td>
						
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Kv</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_kerat['refraction_km_l_kv'] !=''){ echo $refrtsn_kerat['refraction_km_l_kv'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_kerat['refraction_km_l_kv_a'] !=''){ echo $refrtsn_kerat['refraction_km_l_kv_a'];}else {echo '--';}?></td>
					</tr>
				</table>
			</div>

			<div style="float:right;width:48.5%;padding:0px 5px;">
				<strong style="float:left;width:100%;font-size:10px;">Keratometry:</strong>
				<table border="1" cellpadding="0" cellspacing="0" width="100%" style=";font-size:11px;border-collapse:collapse;">
					<tr>
						<td style="padding:3px;" valign="top"></td>
						<td style="padding:3px;" valign="top"></td>
						<td style="padding:3px;" valign="top">Axis</td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Kh</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_kerat['refraction_km_r_kh'] !=''){ echo $refrtsn_kerat['refraction_km_r_kh'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_kerat['refraction_km_r_kh_a'] !=''){ echo $refrtsn_kerat['refraction_km_r_kh_a'];}else {echo '--';}?></td>
						
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Kv</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_kerat['refraction_km_r_kv'] !=''){ echo $refrtsn_kerat['refraction_km_r_kv'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_kerat['refraction_km_r_kv_a'] !=''){ echo $refrtsn_kerat['refraction_km_r_kv_a'];}else {echo '--';}?></td>
					</tr>
				</table>
			</div>
		</section>
		<br>
	 <?php }
	    $refpgp='';
		foreach ($refrtsn_pgp as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $refpgp=1;
        }} 
        if($refpgp==1){?>

		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;">
			<strong style="float:left;width:100%;font-size:10px;">PGP:<?php if(!empty($refrtsn_pgp['refraction_pgp_l_lens'])){ echo 'Type of Lens- '.$refrtsn_pgp['refraction_pgp_l_lens']; } ?> </strong>
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
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_l_dt_sph'] !=''){ echo $refrtsn_pgp['refraction_pgp_l_dt_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_l_dt_cyl'] !=''){ echo $refrtsn_pgp['refraction_pgp_l_dt_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_l_dt_axis'] !=''){ echo $refrtsn_pgp['refraction_pgp_l_dt_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_l_dt_vision'] !=''){ echo $refrtsn_pgp['refraction_pgp_l_dt_vision'];}else {echo '--';}?></td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Near</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_l_nr_sph'] !=''){ echo $refrtsn_pgp['refraction_pgp_l_nr_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_l_nr_cyl'] !=''){ echo $refrtsn_pgp['refraction_pgp_l_nr_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_l_nr_axis'] !=''){ echo $refrtsn_pgp['refraction_pgp_l_nr_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_l_nr_vision'] !=''){ echo $refrtsn_pgp['refraction_pgp_l_nr_vision'];}else {echo '--';}?></td>
					</tr>
				</table>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;">
			<strong style="float:left;width:100%;font-size:10px;">PGP:<?php if(!empty($refrtsn_pgp['refraction_pgp_r_lens'])){ echo 'Type of Lens- '.$refrtsn_pgp['refraction_pgp_r_lens']; } ?> </strong>
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
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_r_dt_sph'] !=''){ echo $refrtsn_pgp['refraction_pgp_r_dt_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_r_dt_cyl'] !=''){ echo $refrtsn_pgp['refraction_pgp_r_dt_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_r_dt_axis'] !=''){ echo $refrtsn_pgp['refraction_pgp_r_dt_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_r_dt_vision'] !=''){ echo $refrtsn_pgp['refraction_pgp_r_dt_vision'];}else {echo '--';}?></td>
					</tr>

					<tr>
						<td style="padding:3px;" valign="top">Near</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_r_nr_sph'] !=''){ echo $refrtsn_pgp['refraction_pgp_r_nr_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_r_nr_cyl'] !=''){ echo $refrtsn_pgp['refraction_pgp_r_nr_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_r_nr_axis'] !=''){ echo $refrtsn_pgp['refraction_pgp_r_nr_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pgp['refraction_pgp_r_nr_vision'] !=''){ echo $refrtsn_pgp['refraction_pgp_r_nr_vision'];}else {echo '--';}?></td>
					</tr>
				</table> 
			</div>
		</section>	

		<br>
	   <?php }
	    $refauto='';
		foreach ($refrtsn_auto_ref as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $refauto=1;
        }} 
        if($refauto==1){?>
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;">
			<strong style="float:left;width:100%;font-size:10px;">Auto Refraction: </strong>
				<table border="1" cellpadding="0" cellspacing="0" width="100%" style=";font-size:11px;border-collapse:collapse;">
					<tr>
						<td style="padding:3px;" valign="top"></td>
						<td style="padding:3px;" valign="top">Sph</td>
						<td style="padding:3px;" valign="top">Cyl</td>
						<td style="padding:3px;" valign="top">Axis</td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Dry</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_l_dry_sph'] !=''){ echo $refrtsn_auto_ref['refraction_ar_l_dry_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_l_dry_cyl'] !=''){ echo $refrtsn_auto_ref['refraction_ar_l_dry_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_l_dry_axis'] !=''){ echo $refrtsn_auto_ref['refraction_ar_l_dry_axis'];}else {echo '--';}?></td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Dilated</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_l_dd_sph'] !=''){ echo $refrtsn_auto_ref['refraction_ar_l_dd_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_l_dd_cyl'] !=''){ echo $refrtsn_auto_ref['refraction_ar_l_dd_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_l_dd_axis'] !=''){ echo $refrtsn_auto_ref['refraction_ar_l_dd_axis'];}else {echo '--';}?></td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top"></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_l_b1_sph'] !=''){ echo $refrtsn_auto_ref['refraction_ar_l_b1_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_l_b1_cyl'] !=''){ echo $refrtsn_auto_ref['refraction_ar_l_b1_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_l_b1_axis'] !=''){ echo $refrtsn_auto_ref['refraction_ar_l_b1_axis'];}else {echo '--';}?></td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top"></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_l_b2_sph'] !=''){ echo $refrtsn_auto_ref['refraction_ar_l_b2_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_l_b2_cyl'] !=''){ echo $refrtsn_auto_ref['refraction_ar_l_b2_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_l_b2_axis'] !=''){ echo $refrtsn_auto_ref['refraction_ar_l_b2_axis'];}else {echo '--';}?></td>
					</tr>
				</table>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;">
				<strong style="float:left;width:100%;font-size:10px;">Auto Refraction: </strong>
				<table border="1" cellpadding="0" cellspacing="0" width="100%" style=";font-size:11px;border-collapse:collapse;">
					<tr>
						<td style="padding:3px;" valign="top"></td>
						<td style="padding:3px;" valign="top">Sph</td>
						<td style="padding:3px;" valign="top">Cyl</td>
						<td style="padding:3px;" valign="top">Axis</td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Dry</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_r_dry_sph'] !=''){ echo $refrtsn_auto_ref['refraction_ar_r_dry_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_r_dry_cyl'] !=''){ echo $refrtsn_auto_ref['refraction_ar_r_dry_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_r_dry_axis'] !=''){ echo $refrtsn_auto_ref['refraction_ar_r_dry_axis'];}else {echo '--';}?></td>
					</tr>

					<tr>
						<td style="padding:3px;" valign="top">Dilated</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_r_dd_sph'] !=''){ echo $refrtsn_auto_ref['refraction_ar_r_dd_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_r_dd_cyl'] !=''){ echo $refrtsn_auto_ref['refraction_ar_r_dd_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_r_dd_axis'] !=''){ echo $refrtsn_auto_ref['refraction_ar_r_dd_axis'];}else {echo '--';}?></td>	
					</tr>

					<tr>
						<td style="padding:3px;" valign="top"></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_r_b1_sph'] !=''){ echo $refrtsn_auto_ref['refraction_ar_r_b1_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_r_b1_cyl'] !=''){ echo $refrtsn_auto_ref['refraction_ar_r_b1_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_r_b1_axis'] !=''){ echo $refrtsn_auto_ref['refraction_ar_r_b1_axis'];}else {echo '--';}?></td>
					</tr>

					<tr>
						<td style="padding:3px;" valign="top"></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_r_b2_sph'] !=''){ echo $refrtsn_auto_ref['refraction_ar_r_b2_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_r_b2_cyl'] !=''){ echo $refrtsn_auto_ref['refraction_ar_r_b2_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_auto_ref['refraction_ar_r_b2_axis'] !=''){ echo $refrtsn_auto_ref['refraction_ar_r_b2_axis'];}else {echo '--';}?></td>	
					</tr>
				</table> 
				<?php echo $refrtsn_auto_ref['refraction_dry_ref_l_comm'];?>
			</div>
		</section>	
		<br>
		<?php }
	    $refdry='';
		foreach ($refrtsn_dry_ref as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $refdry=1;
        }} 
        if($refdry==1){?>
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;">
			<strong style="float:left;width:100%;font-size:10px;">Dry Refraction: </strong>
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
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_l_dt_sph'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_l_dt_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_l_dt_cyl'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_l_dt_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_l_dt_axis'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_l_dt_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_l_dt_vision'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_l_dt_vision'];}else {echo '--';}?></td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Near</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_l_nr_sph'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_l_nr_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_l_nr_cyl'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_l_nr_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_l_nr_axis'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_l_nr_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_l_nr_vision'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_l_nr_vision'];}else {echo '--';}?></td>
					</tr>
				</table>
				<?php echo $refrtsn_dry_ref['refraction_dry_ref_l_comm'];?>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;">
				<strong style="float:left;width:100%;font-size:10px;">Dry Refraction: </strong>
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
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_r_dt_sph'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_r_dt_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_r_dt_cyl'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_r_dt_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_r_dt_axis'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_r_dt_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_r_dt_vision'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_r_dt_vision'];}else {echo '--';}?></td>
					</tr>

					<tr>
						<td style="padding:3px;" valign="top">Near</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_r_nr_sph'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_r_nr_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_r_nr_cyl'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_r_nr_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_r_nr_axis'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_r_nr_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dry_ref['refraction_dry_ref_r_nr_vision'] !=''){ echo $refrtsn_dry_ref['refraction_dry_ref_r_nr_vision'];}else {echo '--';}?></td>
					</tr>
				</table> 
				<?php echo $refrtsn_dry_ref['refraction_dry_ref_r_comm'];?>
			</div>
		</section>	
		<br>

	    <?php }
	    $refdltd='';
		foreach ($refrtsn_dltd as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $refdltd=1;
        }} 
        if($refdltd==1){?>
		
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;">
			<strong style="float:left;width:100%;font-size:10px;">Refraction (Dilated): </strong>
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
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_l_dt_sph'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_l_dt_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_l_dt_cyl'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_l_dt_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_l_dt_axis'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_l_dt_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_l_dt_vision'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_l_dt_vision'];}else {echo '--';}?></td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Near</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_l_nr_sph'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_l_nr_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_l_nr_cyl'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_l_nr_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_l_nr_axis'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_l_nr_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_l_nr_vision'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_l_nr_vision'];}else {echo '--';}?></td>
					</tr>
				</table>
				<?php echo $refrtsn_dltd['refraction_ref_dtd_l_comm'];?>
				<?php if(!empty($refrtsn_dltd['refraction_ref_dtd_l_du'])){ echo '<br><b> Drug Used: </b>'.$refrtsn_dltd['refraction_ref_dtd_l_du'];}?>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;">
				<strong style="float:left;width:100%;font-size:10px;">Refraction (Dilated): </strong>
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
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_r_dt_sph'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_r_dt_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_r_dt_cyl'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_r_dt_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_r_dt_axis'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_r_dt_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_r_dt_vision'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_r_dt_vision'];}else {echo '--';}?></td>
					</tr>

					<tr>
						<td style="padding:3px;" valign="top">Near</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_r_nr_sph'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_r_nr_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_r_nr_cyl'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_r_nr_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_r_nr_axis'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_r_nr_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_dltd['refraction_ref_dtd_r_nr_vision'] !=''){ echo $refrtsn_dltd['refraction_ref_dtd_r_nr_vision'];}else {echo '--';}?></td>
					</tr>
				</table> 
				<?php echo $refrtsn_dltd['refraction_ref_dtd_r_comm'];?>
				<?php if(!empty($refrtsn_dltd['refraction_ref_dtd_r_du'])){ echo '<br><b> Drug Used: </b>'.$refrtsn_dltd['refraction_ref_dtd_r_du'];}?>
			</div>
		</section>				
		<br>
		<?php }
	    $retip='';
		foreach ($refrtsn_retip as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $retip=1;
        }} 
        if($retip==1){ ?>
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;">
			<strong style="float:left;width:100%;font-size:10px;">Retinoscopy: </strong>
				<div style="float:left;width:150px;">
					<table style="width:100%">
						<tbody>
							<tr>
								<td width="33.33%"></td>
								<td width="33.33%" align="center"><?php echo $refrtsn_retip['refraction_rtnp_l_t'];?></td>
								<td width="33.33%"></td>
							</tr>
							<tr>
								<td align="center"><?php echo $refrtsn_retip['refraction_rtnp_l_l'];?></td>
								<td class="plus_symbol" align="center"  style="font-size:25px;vertical-align:middle;"> &#43;</td>
								<td align="center"><?php echo $refrtsn_retip['refraction_rtnp_l_r'];?></td>
							</tr>
							<tr>
								<td width="33.33%"></td>
								<td width="33.33%" align="center"><?php echo $refrtsn_retip['refraction_rtnp_l_b'];?></td>
								<td width="33.33%"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div style="float:left;width:150px;">
					<table style="width:100%;font:12px 'Arial'">
						<tr> <td> <?php if(!empty($refrtsn_retip['refraction_rtnp_l_va'])){ echo '<b>VA: </b>'.$refrtsn_retip['refraction_rtnp_l_va'];}?> <b>HA:</b> <?php echo $refrtsn_retip['refraction_rtnp_l_ha'];?> </td> </tr>
						<tr> <td>  <?php if(!empty($refrtsn_retip['refraction_rtnp_l_at_dis'])){ echo '<b>At Distance:</b> '.$refrtsn_retip['refraction_rtnp_l_at_dis'];}?> </td> </tr>
						<tr> <td>  <?php if(!empty($refrtsn_retip['refraction_rtnp_l_du'])){ echo '<b>Drug Used:</b> '.$refrtsn_retip['refraction_rtnp_l_du'];}?>  </td> </tr>
					</table>
				</div>
				 <small><?php echo "<br>".$refrtsn_retip['refraction_rtnp_l_comm'];?> </small>
			</div>

			<div style="float:right;width:48.5%;padding:0px 5px;">
				<strong style="float:left;width:100%;font-size:10px;">Retinoscopy: </strong>
				<div style="float:left;width:150px;">
					<table style="width:100%;font:12px 'Arial'">
						<tbody>
							<tr>
								<td width="33.33%"></td>
								<td width="33.33%" align="center"><?php echo $refrtsn_retip['refraction_rtnp_r_t'];?></td>
								<td width="33.33%"></td>
							</tr>
							<tr>
								<td align="center"><?php echo $refrtsn_retip['refraction_rtnp_r_l'];?></td>
								<td class="plus_symbol" align="center" style="font-size:25px;vertical-align:middle;">&#43;</td>
								<td align="center"><?php echo $refrtsn_retip['refraction_rtnp_r_r'];?></td>
							</tr>
							<tr>
								<td width="33.33%"></td>
								<td width="33.33%" align="center"><?php echo $refrtsn_retip['refraction_rtnp_r_b'];?></td>
								<td width="33.33%"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div style="float:right;width:150px">
					<table style="width:100%;font:12px 'Arial'">
						<tr> <td> <?php if(!empty($refrtsn_retip['refraction_rtnp_r_va'])){ echo '<b>VA: </b>'.$refrtsn_retip['refraction_rtnp_r_va'];}?> <b>HA:</b> <?php echo $refrtsn_retip['refraction_rtnp_r_ha'];?> </td> </tr>
						<tr> <td>  <?php if(!empty($refrtsn_retip['refraction_rtnp_r_at_dis'])){ echo '<b>At Distance:</b> '.$refrtsn_retip['refraction_rtnp_r_at_dis'];}?> </td> </tr>
						<tr> <td>  <?php if(!empty($refrtsn_retip['refraction_rtnp_r_du'])){ echo '<b>Drug Used:</b> '.$refrtsn_retip['refraction_rtnp_r_du'];}?>  </td> </tr>
					</table>
				</div>
				 <small><?php echo "<br>".$refrtsn_retip['refraction_rtnp_r_comm'];?> </small>
			</div>
		</section>
		 <hr style="margin: 2px !important;">
		<?php }
	    $refpmt='';
		foreach ($refrtsn_pmt as $key => $value)
	    {
        if($value !=0 && !empty($value)){
        $refpmt=1;
        }} 
        if($refpmt==1){?>
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;">
			<strong style="float:left;width:100%;font-size:10px;">PMT: </strong>
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
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_l_dt_sph'] !=''){ echo $refrtsn_pmt['refraction_pmt_l_dt_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_l_dt_cyl'] !=''){ echo $refrtsn_pmt['refraction_pmt_l_dt_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_l_dt_axis'] !=''){ echo $refrtsn_pmt['refraction_pmt_l_dt_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_l_dt_vision'] !=''){ echo $refrtsn_pmt['refraction_pmt_l_dt_vision'];}else {echo '--';}?></td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Near</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_l_nr_sph'] !=''){ echo $refrtsn_pmt['refraction_pmt_l_nr_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_l_nr_cyl'] !=''){ echo $refrtsn_pmt['refraction_pmt_l_nr_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_l_nr_axis'] !=''){ echo $refrtsn_pmt['refraction_pmt_l_nr_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_l_nr_vision'] !=''){ echo $refrtsn_pmt['refraction_pmt_l_nr_vision'];}else {echo '--';}?></td>
					</tr>
				</table>
				<small>
				<?php if($refrtsn_clr_vsn['refraction_col_vis_l'] !=''){ echo 'Color Vision : '.$refrtsn_clr_vsn['refraction_col_vis_l'].'<br>';}?>

				<?php if($refrtsn_const_sen['refraction_contra_sens_l'] !=''){ echo 'Contrast Sensitivity : '.$refrtsn_const_sen['refraction_contra_sens_l'].'<br>';;}?>

				<?php if($refrtsn_orthoptics['refraction_ortho_l'] !=''){ echo 'Orthoptics : '.$refrtsn_orthoptics['refraction_ortho_l'].'<br>';;}?></small>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;">
					<strong style="float:left;width:100%;font-size:10px;">PMT: </strong>
				<table border="1" cellpadding="0" cellspacing="0" width="100%" style=";font-size:10px;border-collapse:collapse;">
					<tr>
						<td style="padding:3px;" valign="top"></td>
						<td style="padding:3px;" valign="top">Sph</td>
						<td style="padding:3px;" valign="top">Cyl</td>
						<td style="padding:3px;" valign="top">Axis</td>
						<td style="padding:3px;" valign="top">Vision</td>
					</tr>
					<tr>
						<td style="padding:3px;" valign="top">Distant</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_r_dt_sph'] !=''){ echo $refrtsn_pmt['refraction_pmt_r_dt_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_r_dt_cyl'] !=''){ echo $refrtsn_pmt['refraction_pmt_r_dt_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_r_dt_axis'] !=''){ echo $refrtsn_pmt['refraction_pmt_r_dt_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_r_dt_vision'] !=''){ echo $refrtsn_pmt['refraction_pmt_r_dt_vision'];}else {echo '--';}?></td>
					</tr>

					<tr>
						<td style="padding:3px;" valign="top">Near</td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_r_nr_sph'] !=''){ echo $refrtsn_pmt['refraction_pmt_r_nr_sph'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_r_nr_cyl'] !=''){ echo $refrtsn_pmt['refraction_pmt_r_nr_cyl'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_r_nr_axis'] !=''){ echo $refrtsn_pmt['refraction_pmt_r_nr_axis'];}else {echo '--';}?></td>
						<td style="padding:3px;" valign="top"><?php if($refrtsn_pmt['refraction_pmt_r_nr_vision'] !=''){ echo $refrtsn_pmt['refraction_pmt_r_nr_vision'];}else {echo '--';}?></td>
					</tr>
				</table> 

				<small>
				<?php if($refrtsn_clr_vsn['refraction_col_vis_r'] !=''){ echo 'Color Vision : '.$refrtsn_clr_vsn['refraction_col_vis_r'].'<br>';}?>

				<?php if($refrtsn_const_sen['refraction_contra_sens_r'] !=''){ echo 'Contrast Sensitivity : '.$refrtsn_const_sen['refraction_contra_sens_r'].'<br>';;}?>

				<?php if($refrtsn_orthoptics['refraction_ortho_r'] !=''){ echo 'Orthoptics : '.$refrtsn_orthoptics['refraction_ortho_r'];}?></small>
			</div>
		</section>
		 <hr style="margin: 2px !important;">
		<?php }
	    $aprnc='';
		foreach ($exam_aprnc as $key => $value)
	    {
        if(!empty($value) && $value !='No' ){
        $aprnc=1;
        }} 
        if($aprnc==1){?>
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;color:<?php echo $exam_aprnc['examnsn_aprnc_l_update'];?>">
		  <strong style="float:left;width:100%;font-size:10px;">Appearance: </strong>
				<?php if($exam_aprnc['examnsn_aprnc_l_phthisis'] !='No' && $exam_aprnc['examnsn_aprnc_l_phthisis'] =='Yes'){ echo 'Phthisis Bulbi, ';}
				if($exam_aprnc['examnsn_aprnc_l_anthms'] !='No' && $exam_aprnc['examnsn_aprnc_l_anthms'] =='Yes'){ echo 'Anophthalmos, ';}
				if($exam_aprnc['examnsn_aprnc_l_mcrthms'] !='No' && $exam_aprnc['examnsn_aprnc_l_mcrthms'] =='Yes'){ echo 'Micropththalmos, ';}
				if($exam_aprnc['examnsn_aprnc_l_artfsl'] !='No' && $exam_aprnc['examnsn_aprnc_l_artfsl'] =='Yes'){ echo 'Artificial, ';}
				if($exam_aprnc['examnsn_aprnc_l_prptsis'] !='No' && $exam_aprnc['examnsn_aprnc_l_prptsis'] =='Yes'){ echo 'Proptosis, ';}
				if($exam_aprnc['examnsn_aprnc_l_dsptpa'] !='No' && $exam_aprnc['examnsn_aprnc_l_dsptpa'] =='Yes'){ echo 'Dystopia, ';}
				if($exam_aprnc['examnsn_aprnc_l_injrd'] !='No' && $exam_aprnc['examnsn_aprnc_l_injrd'] =='Yes'){ echo 'Injured, ';}
				if($exam_aprnc['examnsn_aprnc_l_swln'] !='No' && $exam_aprnc['examnsn_aprnc_l_swln'] =='Yes'){ echo 'Swollen, ';}
				if($exam_aprnc['examnsn_aprnc_l_comm'] !=''){ echo $exam_aprnc['examnsn_aprnc_l_comm'];}
				?>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px; color:<?php echo $exam_aprnc['examnsn_aprnc_r_update'];?>">
				<strong style="float:left;width:100%;font-size:10px;">Appearance: </strong>
				<?php if($exam_aprnc['examnsn_aprnc_r_phthisis'] !='No' && $exam_aprnc['examnsn_aprnc_r_phthisis'] =='Yes'){ echo 'Phthisis Bulbi, ';}
				if($exam_aprnc['examnsn_aprnc_r_anthms'] !='No' && $exam_aprnc['examnsn_aprnc_r_anthms'] =='Yes'){ echo 'Anophthalmos, ';}
				if($exam_aprnc['examnsn_aprnc_r_mcrthms'] !='No' && $exam_aprnc['examnsn_aprnc_r_mcrthms'] =='Yes'){ echo 'Micropththalmos, ';}
				if($exam_aprnc['examnsn_aprnc_r_artfsl'] !='No' && $exam_aprnc['examnsn_aprnc_r_artfsl'] =='Yes'){ echo 'Artificial, ';}
				if($exam_aprnc['examnsn_aprnc_r_prptsis'] !='No' && $exam_aprnc['examnsn_aprnc_r_prptsis'] =='Yes'){ echo 'Proptosis, ';}
				if($exam_aprnc['examnsn_aprnc_r_dsptpa'] !='No' && $exam_aprnc['examnsn_aprnc_r_dsptpa'] =='Yes'){ echo 'Dystopia, ';}
				if($exam_aprnc['examnsn_aprnc_r_injrd'] !='No' && $exam_aprnc['examnsn_aprnc_r_injrd'] =='Yes'){ echo 'Injured, ';}
				if($exam_aprnc['examnsn_aprnc_r_swln'] !='No' && $exam_aprnc['examnsn_aprnc_r_swln'] =='Yes'){ echo 'Swollen, ';}
				if($exam_aprnc['examnsn_aprnc_r_comm'] !=''){ echo $exam_aprnc['examnsn_aprnc_r_comm'];}
				?>
			</div>
		</section>
		 <hr style="margin: 2px !important;">
		<?php }
	    $apnds='';
		foreach ($exam_apnds as $key => $value)
	    {
        if( !empty($value) && $value !='No' ){
        $apnds=1;
        }} 
        if($apnds==1){	?>
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px; color:<?php echo $exam_apnds['examnsn_apndgs_l_update'];?>">
		  <strong style="float:left;width:100%;font-size:10px;"> Appendages: </strong>
				<?php if($exam_apnds['examnsn_apndgs_l_eyelids'] !='0'){ 
					if($exam_apnds['examnsn_apndgs_l_eyelids_chzn'] !='No' && $exam_apnds['examnsn_apndgs_l_eyelids_chzn'] =='Yes'){ echo 'Chalazion, ';}
					if($exam_apnds['examnsn_apndgs_l_eyelids_ptss'] !='No' && $exam_apnds['examnsn_apndgs_l_eyelids_ptss'] =='Yes'){ echo 'Ptosis, ';}
					if($exam_apnds['examnsn_apndgs_l_eyelids_swln'] !='No' && $exam_apnds['examnsn_apndgs_l_eyelids_swln'] =='Yes'){ echo 'Swelling, ';}
					if($exam_apnds['examnsn_apndgs_l_eyelids_intrpn'] !='No' && $exam_apnds['examnsn_apndgs_l_eyelids_intrpn'] =='Yes'){ echo 'Entropion, ';}
					if($exam_apnds['examnsn_apndgs_l_eyelids_ectrpn'] !='No' && $exam_apnds['examnsn_apndgs_l_eyelids_ectrpn'] =='Yes'){ echo 'Ectropion, ';}
					if($exam_apnds['examnsn_apndgs_l_eyelids_mass'] !='No' && $exam_apnds['examnsn_apndgs_l_eyelids_mass'] =='Yes'){ echo 'Mass, ';}
					if($exam_apnds['examnsn_apndgs_l_eyelids_mbts'] !='No' && $exam_apnds['examnsn_apndgs_l_eyelids_mbts'] =='Yes'){ echo 'Meibomitis, ';}
				}
				if($exam_apnds['examnsn_apndgs_l_eyelshs'] !='0'){ 
					if($exam_apnds['examnsn_apndgs_l_eyelshs_tchs'] !='No' && $exam_apnds['examnsn_apndgs_l_eyelshs_tchs'] =='Yes'){ echo 'Trichiasis, ';}
					if($exam_apnds['examnsn_apndgs_l_eyelshs_dtchs'] !='No' && $exam_apnds['examnsn_apndgs_l_eyelshs_dtchs'] =='Yes'){ echo 'Dystrichiasis, ';}
				}
				if($exam_apnds['examnsn_apndgs_l_lcmlc'] !='0'){ 
					if($exam_apnds['examnsn_apndgs_l_lcmlc_swln'] !='No' && $exam_apnds['examnsn_apndgs_l_lcmlc_swln'] =='Yes'){ echo 'Swelling, ';}
					if($exam_apnds['examnsn_apndgs_l_lcmlc_regusn'] !='No' && $exam_apnds['examnsn_apndgs_l_lcmlc_regusn'] =='Yes'){ echo 'Regurgitation, ';}
				}
				if($exam_apnds['examnsn_apndgs_l_syrn'] !='0'){ 
					if($exam_apnds['examnsn_apndgs_l_syrn_syrn'] !='No' && $exam_apnds['examnsn_apndgs_l_syrn_syrn'] =='Yes'){ echo 'Blocked, ';}else{ echo 'Patent, ';}
				}
				if($exam_apnds['examnsn_apndgs_l_comm'] !=''){ 
					 echo '<br>'.$exam_apnds['examnsn_apndgs_l_comm'];
				}
				?>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;color:<?php echo $exam_apnds['examnsn_apndgs_r_update'];?>">
				 <strong style="float:left;width:100%;font-size:10px;"> Appendages: </strong>
				<?php if($exam_apnds['examnsn_apndgs_r_eyelids'] !='0'){ 
					if($exam_apnds['examnsn_apndgs_r_eyelids_chzn'] !='No'){ echo 'Chalazion, ';}
					if($exam_apnds['examnsn_apndgs_r_eyelids_ptss'] !='No'){ echo 'Ptosis, ';}
					if($exam_apnds['examnsn_apndgs_r_eyelids_swln'] !='No'){ echo 'Swelling, ';}
					if($exam_apnds['examnsn_apndgs_r_eyelids_intrpn'] !='No'){ echo 'Entropion, ';}
					if($exam_apnds['examnsn_apndgs_r_eyelids_ectrpn'] !='No'){ echo 'Ectropion, ';}
					if($exam_apnds['examnsn_apndgs_r_eyelids_mass'] !='No'){ echo 'Mass, ';}
					if($exam_apnds['examnsn_apndgs_r_eyelids_mbts'] !='No'){ echo 'Meibomitis, ';}
				}
				if($exam_apnds['examnsn_apndgs_r_eyelshs'] !='0'){ 
					if($exam_apnds['examnsn_apndgs_r_eyelshs_tchs'] !='No'){ echo 'Trichiasis, ';}
					if($exam_apnds['examnsn_apndgs_r_eyelshs_dtchs'] !='No'){ echo 'Dystrichiasis, ';}
				}
				if($exam_apnds['examnsn_apndgs_r_lcmlc'] !='0'){ 
					if($exam_apnds['examnsn_apndgs_r_lcmlc_swln'] !='No'){ echo 'Swelling, ';}
					if($exam_apnds['examnsn_apndgs_r_lcmlc_regusn'] !='No'){ echo 'Regurgitation, ';}
				}
				if($exam_apnds['examnsn_apndgs_r_syrn'] !='0'){ 
					if($exam_apnds['examnsn_apndgs_r_syrn_syrn'] !='No'){ echo 'Blocked, ';}else{ echo 'Patent, ';}
				}
				if($exam_apnds['examnsn_apndgs_r_comm'] !=''){ 
					 echo '<br>'.$exam_apnds['examnsn_apndgs_r_comm'];
				}
				?>
			</div>
		</section>		
	 <hr style="margin: 2px !important;">
	<?php }
    $conjtv='';
	foreach ($exam_conjtv as $key => $value)
    {
    if(!empty($value) && $value !='No' ){
    $conjtv=1;
    }} 
    if($conjtv==1){	?>
	<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;color:<?php echo $exam_conjtv['examnsn_conjtv_l_update'];?>">
		  
				<?php if(!empty($exam_conjtv['examnsn_conjtv_l_consn']) && $exam_conjtv['examnsn_conjtv_l_consn'] !='No')
				{ 
				    
				    if(!empty($exam_conjtv['examnsn_conjtv_l_consn_sub']) && $exam_conjtv['examnsn_conjtv_l_consn_sub']!=''){
				    ?>
				<strong style="float:left;width:100%;font-size:10px;">CONJUNCTIVA: </strong>
				    <?php 
				    if(!empty($exam_conjtv['examnsn_conjtv_l_consn_sub']) && $exam_conjtv['examnsn_conjtv_l_consn_sub']!=''){ echo 'Congestion - '.$exam_conjtv['examnsn_conjtv_l_consn_sub'].', ';} else{ echo 'Congestion, ';}
				    }
				    
				}
				if($exam_conjtv['examnsn_conjtv_l_tear'] !='No' && $exam_conjtv['examnsn_conjtv_l_tear']=='Yes'){ echo 'Tear, ';}
				if($exam_conjtv['examnsn_conjtv_l_cbleb'] !='No' && $exam_conjtv['examnsn_conjtv_l_cbleb']=='Yes'){ echo 'Conjuctival Bleb, ';}
				if($exam_conjtv['examnsn_conjtv_l_sbhrg'] !='No' && $exam_conjtv['examnsn_conjtv_l_sbhrg']=='Yes'){ echo 'SubConjunctival Haemorrhage, ';}
				if($exam_conjtv['examnsn_conjtv_l_fbd'] !='No' && $exam_conjtv['examnsn_conjtv_l_fbd']=='Yes'){ echo 'Foreign Body, ';}
				if($exam_conjtv['examnsn_conjtv_l_flcls'] !='No' && $exam_conjtv['examnsn_conjtv_l_flcls']=='Yes'){ echo 'Follicles, ';}
				if($exam_conjtv['examnsn_conjtv_l_paple'] !='No' && $exam_conjtv['examnsn_conjtv_l_paple']=='Yes'){ echo 'Papillae, ';}
				if($exam_conjtv['examnsn_conjtv_l_pngcla'] !='No' && $exam_conjtv['examnsn_conjtv_l_pngcla']=='Yes'){ echo 'Pinguecula, ';}
				if($exam_conjtv['examnsn_conjtv_l_ptrgm'] !='No' && $exam_conjtv['examnsn_conjtv_l_ptrgm']=='Yes'){ echo 'Pterygium, ';}
				if($exam_conjtv['examnsn_conjtv_l_phctn'] !='No' && $exam_conjtv['examnsn_conjtv_l_phctn']=='Yes'){ echo 'Phlycten, ';}
				if($exam_conjtv['examnsn_conjtv_l_dseg'] !='No' && $exam_conjtv['examnsn_conjtv_l_dseg']=='Yes'){ echo 'Discharge, ';}
				if($exam_conjtv['examnsn_conjtv_l_comm'] !=''){ echo $exam_conjtv['examnsn_conjtv_l_comm'];}
				?>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;color:<?php echo $exam_conjtv['examnsn_conjtv_r_update'];?>">
			    
			    <?php  
			    if(($exam_conjtv['examnsn_conjtv_r_comm'] !='') || ($exam_conjtv['examnsn_conjtv_r_consn'] !='No' && $exam_conjtv['examnsn_conjtv_r_consn']=='Yes') || ($exam_conjtv['examnsn_conjtv_r_tear'] !='No' && $exam_conjtv['examnsn_conjtv_r_tear']=='Yes') || ($exam_conjtv['examnsn_conjtv_r_cbleb'] !='No' && $exam_conjtv['examnsn_conjtv_r_cbleb']=='Yes') || ($exam_conjtv['examnsn_conjtv_r_sbhrg'] !='No' && $exam_conjtv['examnsn_conjtv_r_sbhrg']=='Yes') || ($exam_conjtv['examnsn_conjtv_r_fbd'] !='No' && $exam_conjtv['examnsn_conjtv_r_fbd']=='Yes') || ($exam_conjtv['examnsn_conjtv_r_flcls'] !='No' && $exam_conjtv['examnsn_conjtv_r_flcls']=='Yes') || ($exam_conjtv['examnsn_conjtv_r_paple'] !='No' && $exam_conjtv['examnsn_conjtv_r_paple']=='Yes') || ($exam_conjtv['examnsn_conjtv_r_pngcla'] !='No' && $exam_conjtv['examnsn_conjtv_r_pngcla']=='Yes') || ($exam_conjtv['examnsn_conjtv_r_ptrgm'] !='No' && $exam_conjtv['examnsn_conjtv_r_ptrgm']=='Yes') || ($exam_conjtv['examnsn_conjtv_r_phctn'] !='No' && $exam_conjtv['examnsn_conjtv_r_phctn']=='Yes') || ($exam_conjtv['examnsn_conjtv_r_dseg'] !='No' && $exam_conjtv['examnsn_conjtv_r_dseg']=='Yes')){
			    ?>
				<strong style="float:left;width:100%;font-size:10px;">CONJUNCTIVA: </strong>
				<?php if($exam_conjtv['examnsn_conjtv_r_consn'] !='No' && $exam_conjtv['examnsn_conjtv_r_consn']=='Yes'){ echo 'Congestion, ';}
				if($exam_conjtv['examnsn_conjtv_r_tear'] !='No' && $exam_conjtv['examnsn_conjtv_r_tear']=='Yes'){ echo 'Tear, ';}
				if($exam_conjtv['examnsn_conjtv_r_cbleb'] !='No' && $exam_conjtv['examnsn_conjtv_r_cbleb']=='Yes'){ echo 'Conjuctival Bleb, ';}
				if($exam_conjtv['examnsn_conjtv_r_sbhrg'] !='No' && $exam_conjtv['examnsn_conjtv_r_sbhrg']=='Yes'){ echo 'SubConjunctival Haemorrhage, ';}
				if($exam_conjtv['examnsn_conjtv_r_fbd'] !='No' && $exam_conjtv['examnsn_conjtv_r_fbd']=='Yes'){ echo 'Foreign Body, ';}
				if($exam_conjtv['examnsn_conjtv_r_flcls'] !='No' && $exam_conjtv['examnsn_conjtv_r_flcls']=='Yes'){ echo 'Follicles, ';}
				if($exam_conjtv['examnsn_conjtv_r_paple'] !='No' && $exam_conjtv['examnsn_conjtv_r_paple']=='Yes'){ echo 'Papillae, ';}
				if($exam_conjtv['examnsn_conjtv_r_pngcla'] !='No' && $exam_conjtv['examnsn_conjtv_r_pngcla']=='Yes'){ echo 'Pinguecula, ';}
				if($exam_conjtv['examnsn_conjtv_r_ptrgm'] !='No' && $exam_conjtv['examnsn_conjtv_r_ptrgm']=='Yes'){ echo 'Pterygium, ';}
				if($exam_conjtv['examnsn_conjtv_r_phctn'] !='No' && $exam_conjtv['examnsn_conjtv_r_phctn']=='Yes'){ echo 'Phlycten, ';}
				if($exam_conjtv['examnsn_conjtv_r_dseg'] !='No' && $exam_conjtv['examnsn_conjtv_r_dseg']=='Yes'){ echo 'Discharge, ';}
				if($exam_conjtv['examnsn_conjtv_r_comm'] !=''){ echo $exam_conjtv['examnsn_conjtv_r_comm'];}
                
                
		}
				?>
			</div>
		</section>
		 <hr style="margin: 2px !important;">
		<?php }
		else { ?>
		<section style="float:left;width:100%;">
		<?php if($exam_general['examnsn_lod_normal']=='Normal'){ ?>
			<div style="float:left;width:48.5%;padding:0px 5px;">
				<strong style="float:left;width:100%;font-size:10px;">CONJUNCTIVA: </strong> Normal
			</div>
		<?php } if($exam_general['examnsn_rod_normal']=='Normal'){ ?>
			<div style="float:right;width:48.5%;padding:0px 5px;">
			   <strong style="float:left;width:100%;font-size:10px;">CONJUNCTIVA: </strong> Normal
			 </div>
		<?php } ?>
		</section>
		<?php }
	    $cornea='';
		foreach ($exam_cornea as $key => $value)
	    {
	    if(!empty($value) && $value !='Normal' ){
	    $cornea=1;
	    }} 
	    if($cornea==1){	?>
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;color:<?php echo $exam_cornea['examnsn_cornea_l_update'];?>">
		  <strong style="float:left;width:100%;font-size:10px;">Cornea:</strong>
				
				<?php 
				if($exam_cornea['examnsn_cornea_l_sz'] !='Normal'){ echo 'Size-'.$exam_cornea['examnsn_cornea_l_sz'].', ';}
				if($exam_cornea['examnsn_cornea_l_shp'] !='Normal'){ echo 'Shap-'.$exam_cornea['examnsn_cornea_l_shp'].', ';}
			
	    if(!empty($exam_cornea['examnsn_cornea_l_srfs_epcdft']) || $exam_cornea['examnsn_cornea_l_srfs_epcdft']!='' || !empty($exam_cornea['examnsn_cornea_l_srfs_think']) || $exam_cornea['examnsn_cornea_l_srfs_think']!='' || !empty($exam_cornea['examnsn_cornea_l_srfs_scar']) || $exam_cornea['examnsn_cornea_l_srfs_scar']!='' || 
			!empty($exam_cornea['examnsn_cornea_l_srfs_vascu']) || $exam_cornea['examnsn_cornea_l_srfs_vascu']!='' || 
			!empty($exam_cornea['examnsn_cornea_l_srfs_dgensn']) || $exam_cornea['examnsn_cornea_l_srfs_dgensn']!='' || 
			!empty($exam_cornea['examnsn_cornea_l_srfs_dstph']) || $exam_cornea['examnsn_cornea_l_srfs_dstph']!='' || 
			!empty($exam_cornea['examnsn_cornea_l_srfs_fbd']) || $exam_cornea['examnsn_cornea_l_srfs_fbd']!='' || 
			!empty($exam_cornea['examnsn_cornea_l_srfs_tear']) || $exam_cornea['examnsn_cornea_l_srfs_tear']!='' || 
			!empty($exam_cornea['examnsn_cornea_l_srfs_kp']) || $exam_cornea['examnsn_cornea_l_srfs_kp']!='' || 
			!empty($exam_cornea['examnsn_cornea_l_srfs_opct']) || $exam_cornea['examnsn_cornea_l_srfs_opct']!='' || 
			!empty($exam_cornea['examnsn_cornea_l_srfs_ulcr']) || $exam_cornea['examnsn_cornea_l_srfs_ulcr']!='')
			{ 
					echo 'Surface-';
					if($exam_cornea['examnsn_cornea_l_srfs_epcdft'] !='' && $exam_cornea['examnsn_cornea_l_srfs_epcdft'] =='Yes'){ echo 'Epi defect, ';}
					if($exam_cornea['examnsn_cornea_l_srfs_think'] !='' && $exam_cornea['examnsn_cornea_l_srfs_think'] =='Yes'){ echo 'Thinning, ';}
					if($exam_cornea['examnsn_cornea_l_srfs_scar'] !='' && $exam_cornea['examnsn_cornea_l_srfs_scar'] =='Yes'){ echo 'Scarring, ';}
					if($exam_cornea['examnsn_cornea_l_srfs_vascu'] !='' && $exam_cornea['examnsn_cornea_l_srfs_vascu'] =='Yes'){ echo 'Vascularisation, ';}
					if($exam_cornea['examnsn_cornea_l_srfs_dgensn'] !='' && $exam_cornea['examnsn_cornea_l_srfs_dgensn'] =='Yes'){ echo 'Degeneration, ';}
					if($exam_cornea['examnsn_cornea_l_srfs_dstph'] !='' && $exam_cornea['examnsn_cornea_l_srfs_dstph'] =='Yes'){ echo 'Dystrophy, ';}
					if($exam_cornea['examnsn_cornea_l_srfs_fbd'] !='' && $exam_cornea['examnsn_cornea_l_srfs_fbd'] =='Yes'){ echo 'Foreign Body, ';}
					if($exam_cornea['examnsn_cornea_l_srfs_tear'] !='' && $exam_cornea['examnsn_cornea_l_srfs_tear'] =='Yes'){ echo 'Tear, ';}
					if($exam_cornea['examnsn_cornea_l_srfs_kp'] !='' && $exam_cornea['examnsn_cornea_l_srfs_kp'] =='Yes'){ echo 'KP, ';}
					if($exam_cornea['examnsn_cornea_l_srfs_opct'] !='' && $exam_cornea['examnsn_cornea_l_srfs_opct'] =='Yes'){ echo 'Opacity, ';}
					if($exam_cornea['examnsn_cornea_l_srfs_ulcr'] !='' && $exam_cornea['examnsn_cornea_l_srfs_ulcr'] =='Yes'){ echo 'Ulcer, ';}
				}
				
				if($exam_cornea['examnsn_cornea_l_scht1_mm']!='' ||
				$exam_cornea['examnsn_cornea_l_scht1_min'] !=''){ echo 'Schirmer Test-1('.$exam_cornea['examnsn_cornea_l_scht1_mm'].'mm in '.$exam_cornea['examnsn_cornea_l_scht1_min'].' min), ';}
				if($exam_cornea['examnsn_cornea_l_scht2_mm']!='' ||
				$exam_cornea['examnsn_cornea_l_scht2_min'] !=''){ echo ' Schirmer Test-2('.$exam_cornea['examnsn_cornea_l_scht2_mm'].'mm in '.$exam_cornea['examnsn_cornea_l_scht2_min'].' min)';}
					echo '<br>'.$exam_cornea['examnsn_cornea_l_comm'];
				?>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;color:<?php echo $exam_cornea['examnsn_cornea_r_update'];?>">
				  
				  <?php 
				  if(!empty($exam_cornea['examnsn_cornea_r_srfs_epcdft']) || !empty($exam_cornea['examnsn_cornea_r_srfs_think']) || !empty($exam_cornea['examnsn_cornea_r_srfs_scar']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_vascu']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_dgensn']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_dstph']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_fbd']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_tear']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_kp']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_opct']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_ulcr']) || !empty($exam_cornea['examnsn_cornea_r_sz']) || !empty($exam_cornea['examnsn_cornea_r_shp']) || !empty($exam_cornea['examnsn_cornea_r_scht1_mm']) || !empty($exam_cornea['examnsn_cornea_r_scht1_min']) || !empty($exam_cornea['examnsn_cornea_r_scht2_mm']) || !empty($exam_cornea['examnsn_cornea_r_scht2_min'])){
				  ?>
				  
				  <strong style="float:left;width:100%;font-size:10px;">Cornea:</strong>
					<?php 
				if($exam_cornea['examnsn_cornea_r_sz'] !='Normal' && !empty($exam_cornea['examnsn_cornea_r_sz'])){ echo 'Size-'.$exam_cornea['examnsn_cornea_r_sz'].', ';}
				if($exam_cornea['examnsn_cornea_r_shp'] !='Normal' && !empty($exam_cornea['examnsn_cornea_r_shp'])){ echo 'Shap-'.$exam_cornea['examnsn_cornea_r_shp'].', ';}
			
				if(!empty($exam_cornea['examnsn_cornea_r_srfs_epcdft']) || !empty($exam_cornea['examnsn_cornea_r_srfs_think']) || !empty($exam_cornea['examnsn_cornea_r_srfs_scar']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_vascu']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_dgensn']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_dstph']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_fbd']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_tear']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_kp']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_opct']) || 
        			!empty($exam_cornea['examnsn_cornea_r_srfs_ulcr'])){
					echo 'Surface-';
					if($exam_cornea['examnsn_cornea_r_srfs_epcdft'] !='' && $exam_cornea['examnsn_cornea_r_srfs_epcdft'] =='Yes'){ echo 'Epi defect, ';}
					if($exam_cornea['examnsn_cornea_r_srfs_think'] !='' && $exam_cornea['examnsn_cornea_r_srfs_think'] =='Yes'){ echo 'Thinning, ';}
					if($exam_cornea['examnsn_cornea_r_srfs_scar'] !='' && $exam_cornea['examnsn_cornea_r_srfs_scar'] =='Yes'){ echo 'Scarring, ';}
					if($exam_cornea['examnsn_cornea_r_srfs_vascu'] !='' && $exam_cornea['examnsn_cornea_r_srfs_vascu'] =='Yes'){ echo 'Vascularisation, ';}
					if($exam_cornea['examnsn_cornea_r_srfs_dgensn'] !='' && $exam_cornea['examnsn_cornea_r_srfs_dgensn'] =='Yes'){ echo 'Degeneration, ';}
					if($exam_cornea['examnsn_cornea_r_srfs_dstph'] !='' && $exam_cornea['examnsn_cornea_r_srfs_dstph'] =='Yes'){ echo 'Dystrophy, ';}
					if($exam_cornea['examnsn_cornea_r_srfs_fbd'] !='' && $exam_cornea['examnsn_cornea_r_srfs_fbd'] =='Yes'){ echo 'Foreign Body, ';}
					if($exam_cornea['examnsn_cornea_r_srfs_tear'] !='' && $exam_cornea['examnsn_cornea_r_srfs_tear'] =='Yes'){ echo 'Tear, ';}
					if($exam_cornea['examnsn_cornea_r_srfs_kp'] !='' && $exam_cornea['examnsn_cornea_r_srfs_kp'] =='Yes'){ echo 'KP, ';}
					if($exam_cornea['examnsn_cornea_r_srfs_opct'] !='' && $exam_cornea['examnsn_cornea_r_srfs_opct'] =='Yes'){ echo 'Opacity, ';}
					if($exam_cornea['examnsn_cornea_r_srfs_ulcr'] !='' && $exam_cornea['examnsn_cornea_r_srfs_ulcr'] =='Yes'){ echo 'Ulcer, ';}
				}
				
				if($exam_cornea['examnsn_cornea_r_scht1_mm']!='' ||
				$exam_cornea['examnsn_cornea_r_scht1_min'] !=''){ echo ' Schirmer Test-1('.$exam_cornea['examnsn_cornea_r_scht1_mm'].'mm in '.$exam_cornea['examnsn_cornea_r_scht1_min'].' min ), ';}
				if($exam_cornea['examnsn_cornea_r_scht2_mm']!='' ||
				$exam_cornea['examnsn_cornea_r_scht2_min'] !=''){ echo ' Schirmer Test-2('.$exam_cornea['examnsn_cornea_r_scht2_mm'].'mm in '.$exam_cornea['examnsn_cornea_r_scht2_min'].' min)';}
					echo '<br>'.$exam_cornea['examnsn_cornea_r_comm'];
					
        			}
				?>
			</div>
		</section>	
		 <hr style="margin: 2px !important;">
	   <?php } 	  
	    else { ?>
	    <section style="float:left;width:100%;">
		<?php if($exam_general['examnsn_lod_normal']=='Normal'){ ?>
			<div style="float:left;width:48.5%;padding:0px 5px;">
				<strong style="float:left;width:100%;font-size:10px;">Cornea: </strong> Size/Shape/Surface Normal 
			</div>
			<?php } if($exam_general['examnsn_rod_normal']=='Normal'){ ?>
			<div style="float:right;width:48.5%;padding:0px 5px;">
			   <strong style="float:left;width:100%;font-size:10px;">Cornea: </strong> Size/Shape/Surface Normal 
			 </div>

		<?php } ?>
	    </section>
		<?php }

	    $atrch='';
		foreach ($exam_atrch as $key => $value)
	    {
	    if($value =='Yes' ){
	    $atrch=1;
	    }} 
	    if($atrch==1){	?>
	   
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;color:<?php echo $exam_atrch['examnsn_ac_l_update'];?>">
			    <?php if(!empty($exam_atrch['examnsn_ac_l_defth']) && $exam_atrch['examnsn_ac_l_defth'] !='Normal'){ ?>
		  <strong style="float:left;width:100%;font-size:10px;">Anterior Chamber:</strong>
				
				<?php }
				if(!empty($exam_atrch['examnsn_ac_l_defth']) && $exam_atrch['examnsn_ac_l_defth'] !='Normal'){ echo 'Depth-'.$exam_atrch['examnsn_ac_l_defth'].' '.$exam_atrch['examnsn_ac_l_defth_txt1'].' '.$exam_atrch['examnsn_ac_l_defth_txt2'].', ';}
				
					if(!empty($exam_atrch['examnsn_ac_l_cells']) && $exam_atrch['examnsn_ac_l_cells'] !='No'){ echo 'Cells '.$exam_atrch['examnsn_ac_l_cells_txt'].', ';}
					if(!empty($exam_atrch['examnsn_ac_l_flar']) && $exam_atrch['examnsn_ac_l_flar'] !='No'){ echo 'Flare '.$exam_atrch['examnsn_ac_l_flar_txt'].', ';}
					if(!empty($exam_atrch['examnsn_ac_l_hyfma']) && $exam_atrch['examnsn_ac_l_hyfma'] !='No'){ echo 'Hyphema '.$exam_atrch['examnsn_ac_l_hyfma_txt'].', ';}
					if(!empty($exam_atrch['examnsn_ac_l_hypn']) && $exam_atrch['examnsn_ac_l_hypn'] !='No'){ echo 'Hypopyon '.$exam_atrch['examnsn_ac_l_hypn_txt'].', ';}
					if(!empty($exam_atrch['examnsn_ac_l_fbd']) && $exam_atrch['examnsn_ac_l_fbd'] !='No'){ echo 'Foreign Body '.$exam_atrch['examnsn_ac_l_fbd_txt'].', ';}
					echo '<br>'.$exam_atrch['examnsn_ac_l_comm']; 
				?>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;color:<?php echo $exam_atrch['examnsn_ac_r_update'];?>">
				 <strong style="float:left;width:100%;font-size:10px;">Anterior Chamber:</strong>
				<?php 
				if($exam_atrch['examnsn_ac_r_defth'] !='Normal'){ echo 'Depth-'.$exam_atrch['examnsn_ac_r_defth'].' '.$exam_atrch['examnsn_ac_r_defth_txt1'].' '.$exam_atrch['examnsn_ac_r_defth_txt2'].', ';}				
					if($exam_atrch['examnsn_ac_r_cells'] !='No'){ echo 'Cells '.$exam_atrch['examnsn_ac_r_cells_txt'].', ';}
					if($exam_atrch['examnsn_ac_r_flar'] !='No'){ echo 'Flare '.$exam_atrch['examnsn_ac_r_flar_txt'].', ';}
					if($exam_atrch['examnsn_ac_r_hyfma'] !='No'){ echo 'Hyphema '.$exam_atrch['examnsn_ac_r_hyfma_txt'].', ';}
					if($exam_atrch['examnsn_ac_r_hypn'] !='No'){ echo 'Hypopyon '.$exam_atrch['examnsn_ac_r_hypn_txt'].', ';}
					if($exam_atrch['examnsn_ac_r_fbd'] !='No'){ echo 'Foreign Body '.$exam_atrch['examnsn_ac_r_fbd_txt'].', ';}
					echo '<br>'.$exam_atrch['examnsn_ac_r_comm']; 
				?>
			</div>
		</section>	
		 <hr style="margin: 2px !important;">
	   <?php }	    
	      else {
	      	?>
	    <section style="float:left;width:100%;">
		<?php if($exam_general['examnsn_lod_normal']=='Normal'){ ?>
			<div style="float:left;width:48.5%;padding:0px 5px;">
				<strong style="float:left;width:100%;font-size:10px;">Anterior Chamber: </strong> Normal depth, No cells/flare 
			</div>
				<?php } if($exam_general['examnsn_rod_normal']=='Normal'){ ?>
			<div style="float:right;width:48.5%;padding:0px 5px;">
			   <strong style="float:left;width:100%;font-size:10px;">Anterior Chamber: </strong> Normal depth, No cells/flare 
			 </div>

		<?php } }

	    if($exam_pupil['examnsn_pupl_l_shp'] !='' || $exam_pupil['examnsn_pupl_l_comm'] !=''){ ?>
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;color:<?php echo $exam_pupil['examnsn_pupl_l_update'];?>">
		  <strong style="float:left;width:100%;font-size:10px;">Pupil:</strong>
				
				<?php 
				if($exam_pupil['examnsn_pupl_l_shp'] !=''){ echo 'Shape- '.$exam_pupil['examnsn_pupl_l_shp'].', ';}
				
					if($exam_pupil['examnsn_pupl_l_rld'] !='Normal'){ echo 'Reaction to light Direct- '.$exam_pupil['examnsn_pupl_l_rld'].', ';}
					if($exam_pupil['examnsn_pupl_l_rlc'] !='Normal'){ echo 'Reaction to light consensual-
						 '.$exam_pupil['examnsn_pupl_l_rlc'].', ';}
					if($exam_pupil['examnsn_pupl_l_apd'] !='No'){ echo 'RAPD, ';}
					echo '<br>'.$exam_pupil['examnsn_pupl_l_comm']; 
				?>
			</div>
			<?php 
			?>
			<div style="float:right;width:48.5%;padding:0px 5px;color:<?php echo $exam_pupil['examnsn_pupl_r_update'];?>">
				<strong style="float:left;width:100%;font-size:10px;">Pupil:</strong>
				<?php 
				if($exam_pupil['examnsn_pupl_r_shp'] !=''){ echo 'Shape- '.$exam_pupil['examnsn_pupl_r_shp'].', ';}
				
					if($exam_pupil['examnsn_pupl_r_rld'] !='Normal'){ echo 'Reaction to light Direct- '.$exam_pupil['examnsn_pupl_r_rld'].', ';}
					if($exam_pupil['examnsn_pupl_r_rlc'] !='Normal'){ echo 'Reaction to light consensual-
						 '.$exam_pupil['examnsn_pupl_r_rlc'].', ';}
					if($exam_pupil['examnsn_pupl_r_apd'] !='No'){ echo 'RAPD, ';}
					echo '<br>'.$exam_pupil['examnsn_pupl_r_comm']; 
				?>
			</div>
		</section>	
			 <hr style="margin: 2px !important;">
		<?php }		
		  else { ?>
	    <section style="float:left;width:100%;">
		<?php if($exam_general['examnsn_lod_normal']=='Normal'){ ?>
			<div style="float:left;width:48.5%;padding:0px 5px;">
				<strong style="float:left;width:100%;font-size:10px;">Pupil: </strong> Round shape, Normal direct & Consensual reflex  
			</div>
				<?php } if($exam_general['examnsn_rod_normal']=='Normal'){ ?>
			<div style="float:right;width:48.5%;padding:0px 5px;">
			   <strong style="float:left;width:100%;font-size:10px;">Pupil: </strong>Round shape, Normal direct & Consensual reflex  
			 </div>
		<?php } }
	    $iris='';
		foreach ($exam_iris as $key => $value)
	    {
	    if(!empty($value) && $value !='Normal' && $value !='No' ){
	    $iris=1;
	    }} 
	    if($iris==1){	?>	
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;color:<?php echo $exam_iris['examnsn_iris_l_update'];?>">
		  <strong style="float:left;width:100%;font-size:10px;">Iris: </strong>
				
				<?php 
				if($exam_iris['examnsn_iris_l_shp'] !='Normal'){ echo 'Shape- '.$exam_iris['examnsn_iris_l_shp'].', ';}				
				if($exam_iris['examnsn_iris_l_nvi'] !='No'){ echo 'Neovascularisation (NVI), ';}
				if($exam_iris['examnsn_iris_l_synch'] !='No'){ echo 'Synechiae-  '.$exam_iris['examnsn_iris_l_synch'].', ';}
				echo '<br>'.$exam_iris['examnsn_iris_l_comm']; 
				?>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;color:<?php echo $exam_iris['examnsn_iris_r_update'];?>">
				 <strong style="float:left;width:100%;font-size:10px;">Iris: </strong>
				<?php 
				if($exam_iris['examnsn_iris_r_shp'] !='Normal'){ echo 'Shape- '.$exam_iris['examnsn_iris_r_shp'].', ';}				
				if($exam_iris['examnsn_iris_r_nvi'] !='No'){ echo 'Neovascularisation (NVI), ';}
				if($exam_iris['examnsn_iris_r_synch'] !='No'){ echo 'Synechiae-  '.$exam_iris['examnsn_iris_r_synch'].', ';}
				echo '<br>'.$exam_iris['examnsn_iris_r_comm']; 
				?>
			</div>
		</section>		
		 <hr style="margin: 2px !important;">
		<?php }
		 else { ?>
	    <section style="float:left;width:100%;">
		<?php if($exam_general['examnsn_lod_normal']=='Normal'){ ?>
			<div style="float:left;width:48.5%;padding:0px 5px;">
				<strong style="float:left;width:100%;font-size:10px;">Iris: </strong> Within normal Limits  
			</div>	
		<?php } if($exam_general['examnsn_rod_normal']=='Normal'){ ?>
			<div style="float:right;width:48.5%;padding:0px 5px;">
			   <strong style="float:left;width:100%;font-size:10px;">Iris: </strong>Within normal Limits  
			 </div>

		<?php } ?>
		</section>
		<?php }
		
	    $lens='';
		foreach ($exam_lens as $key => $value)
	    {
	    if(!empty($value) && $value !='Normal' && $value !='Clear'){
	    $lens=1;
	    }} 
	    if($lens==1){	?>	
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;color:<?php echo $exam_lens['examnsn_lens_l_update'];?>">
			<?php 
				if($exam_lens['examnsn_lens_l_ntr'] !='Clear'){ echo '<strong style="float:left;width:100%;font-size:10px;">Lens: </strong> Nature- '.$exam_lens['examnsn_lens_l_ntr'].', ';}				
				/*if($exam_lens['examnsn_lens_l_psn'] !='Central'){ echo 'Position- '.$exam_lens['examnsn_lens_l_psn'].', ';}else{ echo 'Central, '; }
				if($exam_lens['examnsn_lens_l_sz'] !='Normal'){ echo 'Lens Size- '.$exam_lens['examnsn_lens_l_sz'].', ';}else{ echo 'Crystalline, ';}*/
				if($exam_lens['examnsn_lens_l_ntr']=='Cataract')
				{
					echo ' LOCS Grading- ';
					if($exam_lens['examnsn_lens_l_locsg_ns'] !='') { echo ' NS:'.$exam_lens['examnsn_lens_l_locsg_ns']; }
					if($exam_lens['examnsn_lens_l_locsg_c'] !='') { echo ' C:'.$exam_lens['examnsn_lens_l_locsg_c']; }
					if($exam_lens['examnsn_lens_l_locsg_p'] !='') { echo ' P:'.$exam_lens['examnsn_lens_l_locsg_p']; }

				}				
				echo '<br>'.$exam_lens['examnsn_lens_l_comm']; 
				?>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;color:<?php echo $exam_lens['examnsn_lens_r_update'];?>">
				<?php 
				if($exam_lens['examnsn_lens_r_ntr'] !='Clear'){ echo '<strong style="float:left;width:100%;font-size:10px;">Lens: </strong> Nature- '.$exam_lens['examnsn_lens_r_ntr'].', ';}
				/*if($exam_lens['examnsn_lens_r_psn'] !='Central'){ echo 'Position- '.$exam_lens['examnsn_lens_r_psn'].', ';}else{ echo 'Central, '; }
				if($exam_lens['examnsn_lens_r_sz'] !='Normal'){ echo 'Lens Size- '.$exam_lens['examnsn_lens_r_sz'].', ';}else{ echo 'Crystalline, ';}*/
				if($exam_lens['examnsn_lens_r_ntr'] =='Cataract')
				{
					'Lens Grading- ';
					if($exam_lens['examnsn_lens_r_locsg_ns'] !='') { echo 'NS:'.$exam_lens['examnsn_lens_r_locsg_ns']; }
					if($exam_lens['examnsn_lens_r_locsg_c'] !='') { echo ' C:'.$exam_lens['examnsn_lens_r_locsg_c']; }
					if($exam_lens['examnsn_lens_r_locsg_p'] !='') { echo ' P:'.$exam_lens['examnsn_lens_r_locsg_p']; }

				}				
				echo '<br>'.$exam_lens['examnsn_lens_r_comm']; 
				?>
			</div>
		</section>	
		 <hr style="margin: 2px !important;">
	    <?php }	 
	   else { ?>
	    <section style="float:left;width:100%;">
		<?php if($exam_general['examnsn_lod_normal']=='Normal'){ ?>
			<div style="float:left;width:48.5%;padding:0px 5px;">
				<strong style="float:left;width:100%;font-size:10px;">Lens: </strong> Clear   
			</div>
				<?php } if($exam_general['examnsn_rod_normal']=='Normal'){ ?>
			<div style="float:right;width:48.5%;padding:0px 5px;">
			   <strong style="float:left;width:100%;font-size:10px;">Lens: </strong> Clear   
			 </div>
		<?php } ?>
		</section>
		<?php }
	    $extrmovs='';
		foreach ($exam_extrmovs as $key => $value)
	    {
	    if(!empty($value) && $value !='Full' && $value !='0' ){
	    $extrmovs=1;
	    }} 
	    if($extrmovs==1){	?>	
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;color:<?php echo $exam_extrmovs['examnsn_ems_l_update'];?>">
		  <strong style="float:left;width:100%;font-size:10px;">Extra Ocular Movements: </strong>
				
				<?php 
				if($exam_extrmovs['examnsn_ems_l_unimv'] !='Full'){ echo 'Uniocular- '.$exam_extrmovs['examnsn_ems_l_unimv'].', ';}			
				if($exam_extrmovs['examnsn_ems_l_bimv'] !='Full'){ echo 'Binocular- '.$exam_extrmovs['examnsn_ems_l_bimv'].', ';}
				if($exam_extrmovs['examnsn_ems_l_prsm'] !=''){ echo 'Prism- '.$exam_extrmovs['examnsn_ems_l_prsm'].', ';}
				if($exam_extrmovs['examnsn_ems_l_sqnt'] =='Tropia')
				{
					echo 'Squint- '.$exam_extrmovs['examnsn_ems_l_sqnt'].', ';
					if($exam_extrmovs['examnsn_ems_l_sqnt_trpa'] =='Horizontal') 
					{
					 echo 'Tropia-'.$exam_extrmovs['examnsn_ems_l_sqnt_trpa'].', ';
						if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h'] =='Esotropia(ET)') 
						{ echo 'Horizontal-'.$exam_extrmovs['examnsn_ems_l_sqnt_trpa_h'].', '; 
							if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_et'] !='')
							{ echo 'Esotropia(ET)-'.$exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_et'].', '; }
						}
						else if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h'] =='Exotropia(XT)')
						{
							echo 'Horizontal-'.$exam_extrmovs['examnsn_ems_l_sqnt_trpa_h'].', '; 
							if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_xt'] !='')
							{ echo 'Exotropia(XT)-'.$exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_xt'].', '; }
						}
					 }
					else if($exam_extrmovs['examnsn_ems_l_sqnt_trpa'] =='Vertical') 
					{ 
						echo 'Tropia-'.$exam_extrmovs['examnsn_ems_l_sqnt_trpa'].', ';
						if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_v'] !='') 
						{ 
							echo 'Vertical-'.$exam_extrmovs['examnsn_ems_l_sqnt_trpa_v'].', '; 
						}
					 }
					else if($exam_extrmovs['examnsn_ems_l_sqnt_trpa'] =='Paralytic') 
					{ 
						echo 'Tropia-'.$exam_extrmovs['examnsn_ems_l_sqnt_trpa'].', Paralytic-';
						if($exam_extrmovs['examnsn_ems_l_para_mr'] !='0') 
						{ 
							echo $exam_extrmovs['examnsn_ems_l_para_mr'].', '; 
						}
						if($exam_extrmovs['examnsn_ems_l_para_lr'] !='0') 
						{ 
							echo $exam_extrmovs['examnsn_ems_l_para_lr'].', '; 
						}
						if($exam_extrmovs['examnsn_ems_l_para_so'] !='0') 
						{ 
							echo $exam_extrmovs['examnsn_ems_l_para_so'].', '; 
						}
						if($exam_extrmovs['examnsn_ems_l_para_sr'] !='0') 
						{ 
							echo $exam_extrmovs['examnsn_ems_l_para_sr'].', '; 
						}
						if($exam_extrmovs['examnsn_ems_l_para_ir'] !='0') 
						{ 
							echo $exam_extrmovs['examnsn_ems_l_para_ir'].', '; 
						}
						if($exam_extrmovs['examnsn_ems_l_para_io'] !='0') 
						{ 
							echo $exam_extrmovs['examnsn_ems_l_para_io'].', '; 
						}
					}
					
				}
				else if($exam_extrmovs['examnsn_ems_l_sqnt'] =='Phoria'){
						echo 'Squint- '.$exam_extrmovs['examnsn_ems_l_sqnt'].', ';
						echo 'Phoria-'.$exam_extrmovs['examnsn_ems_l_sqnt_phoria'].', ';
				}				
				echo '<br>'.$exam_extrmovs['examnsn_ems_l_comm']; 
				?>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;color:<?php echo $exam_extrmovs['examnsn_ems_r_update'];?>">
				 <strong style="float:left;width:100%;font-size:10px;">Extra Ocular Movements: </strong>
				<?php 
				if($exam_extrmovs['examnsn_ems_r_unimv'] !='Full'){ echo 'Uniocular- '.$exam_extrmovs['examnsn_ems_r_unimv'].', ';}			
				if($exam_extrmovs['examnsn_ems_r_bimv'] !='Full'){ echo 'Binocular- '.$exam_extrmovs['examnsn_ems_r_bimv'].', ';}
				if($exam_extrmovs['examnsn_ems_r_prsm'] !=''){ echo 'Prism- '.$exam_extrmovs['examnsn_ems_r_prsm'].', ';}
				if($exam_extrmovs['examnsn_ems_r_sqnt'] =='Tropia')
				{
					echo 'Squint- '.$exam_extrmovs['examnsn_ems_r_sqnt'].', ';
					if($exam_extrmovs['examnsn_ems_r_sqnt_trpa'] =='Horizontal') 
					{
					 echo 'Tropia-'.$exam_extrmovs['examnsn_ems_r_sqnt_trpa'].', ';
						if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h'] =='Esotropia(ET)') 
						{ echo 'Horizontal-'.$exam_extrmovs['examnsn_ems_r_sqnt_trpa_h'].', '; 
							if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_et'] !='')
							{ echo 'Esotropia(ET)-'.$exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_et'].', '; }
						}
						else if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h'] =='Exotropia(XT)')
						{
							echo 'Horizontal-'.$exam_extrmovs['examnsn_ems_r_sqnt_trpa_h'].', '; 
							if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_xt'] !='')
							{ echo 'Exotropia(XT)-'.$exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_xt'].', '; }
						}
					 }
					else if($exam_extrmovs['examnsn_ems_r_sqnt_trpa'] =='Vertical') 
					{ 
						echo 'Tropia-'.$exam_extrmovs['examnsn_ems_r_sqnt_trpa'].', ';
						if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_v'] !='') 
						{ 
							echo 'Vertical-'.$exam_extrmovs['examnsn_ems_r_sqnt_trpa_v'].', '; 
						}
					 }
					else if($exam_extrmovs['examnsn_ems_r_sqnt_trpa'] =='Paralytic') 
					{ 
						echo 'Tropia-'.$exam_extrmovs['examnsn_ems_r_sqnt_trpa'].', Paralytic-';
						if($exam_extrmovs['examnsn_ems_r_para_mr'] !='0') 
						{ 
							echo $exam_extrmovs['examnsn_ems_r_para_mr'].', '; 
						}
						if($exam_extrmovs['examnsn_ems_r_para_lr'] !='0') 
						{ 
							echo $exam_extrmovs['examnsn_ems_r_para_lr'].', '; 
						}
						if($exam_extrmovs['examnsn_ems_r_para_so'] !='0') 
						{ 
							echo $exam_extrmovs['examnsn_ems_r_para_so'].', '; 
						}
						if($exam_extrmovs['examnsn_ems_r_para_sr'] !='0') 
						{ 
							echo $exam_extrmovs['examnsn_ems_r_para_sr'].', '; 
						}
						if($exam_extrmovs['examnsn_ems_r_para_ir'] !='0') 
						{ 
							echo $exam_extrmovs['examnsn_ems_r_para_ir'].', '; 
						}
						if($exam_extrmovs['examnsn_ems_r_para_io'] !='0') 
						{ 
							echo $exam_extrmovs['examnsn_ems_r_para_io'].', '; 
						}
					}
					
				}
				else if($exam_extrmovs['examnsn_ems_r_sqnt'] =='Phoria'){
						echo 'Squint- '.$exam_extrmovs['examnsn_ems_r_sqnt'].', ';
						echo 'Phoria-'.$exam_extrmovs['examnsn_ems_r_sqnt_phoria'].', ';
				}				
				echo '<br>'.$exam_extrmovs['examnsn_ems_r_comm']; 
				?>
			</div>
		</section>
		
		 <hr style="margin: 2px !important;">
		<?php }		
		  else { ?>
	    <section style="float:left;width:100%;">
		<?php if($exam_general['examnsn_lod_normal']=='Normal'){ ?>
			<div style="float:left;width:48.5%;padding:0px 5px;">
				<strong style="float:left;width:100%;font-size:10px;">Extra Ocular Movements: </strong> Uniocular and Binocular movements full and normal    
			</div>
				<?php } if($exam_general['examnsn_rod_normal']=='Normal'){ ?>
			<div style="float:right;width:48.5%;padding:0px 5px;">
			   <strong style="float:left;width:100%;font-size:10px;">Extra Ocular Movements: </strong> Uniocular and Binocular movements full and normal    
			 </div>
		<?php } ?>
		</section>
		<?php } ?>
		
		<section style="float:left;width:100%;">
		<div style="float:left;width:48.5%;padding:0px 5px;">  <?php if(!empty($exam_intrprsr['examnsn_iop_l_value'])){ echo 'IOP: '.$exam_intrprsr['examnsn_iop_l_value'].' at '.$exam_intrprsr['examnsn_iop_l_time'].'-'.$exam_intrprsr['examnsn_iop_l_comm'];}?>  
		
	

		</div>
		<div style="float:right;width:48.5%;padding:0px 5px;"> <?php if(!empty($exam_intrprsr['examnsn_iop_r_value'])){ echo 'IOP: '.$exam_intrprsr['examnsn_iop_r_value'].' at '.$exam_intrprsr['examnsn_iop_r_time'].'-'.$exam_intrprsr['examnsn_iop_r_comm'];}?>  
		
	

		</div>
		</section>
		<?php 
	    $gnscp='';
		foreach ($exam_gnscp as $key => $value)
	    {
	    if(!empty($value) && $value !='Normal' && $value !='0' ){
	    $gnscp=1;
	    }} 
	    if($gnscp==1){	?>	
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;display:flex;color:<?php echo $exam_gnscp['examnsn_gonispy_l_update'];?>">
			 <strong style="float:left;width:100%;font-size:10px;">Gonioscopy:</strong>
				<table style="width:100%">
					<tbody>
						<tr>
							<td width="33.33%"></td>
							<td width="33.33%" align="center"> Superior : <?php if(!empty($exam_gnscp['examnsn_gonispy_l_sup_d1'])){ echo $exam_gnscp['examnsn_gonispy_l_sup_d1'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_l_sup_d2'])){ echo $exam_gnscp['examnsn_gonispy_l_sup_d2'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_l_sup_d3'])){ echo $exam_gnscp['examnsn_gonispy_l_sup_d3'];}?> </td>
							<td width="33.33%"></td>
						</tr>
						<tr>
							<td align="center"> Temporal:<?php if(!empty($exam_gnscp['examnsn_gonispy_l_tmprl_d1'])){ echo $exam_gnscp['examnsn_gonispy_l_tmprl_d1'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_l_tmprl_d2'])){ echo $exam_gnscp['examnsn_gonispy_l_tmprl_d2'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_l_tmprl_d3'])){ echo $exam_gnscp['examnsn_gonispy_l_tmprl_d3'];}?> </td>
							<td class="plus_symbol" align="center" style="font-size:30px;"> &chi;</td>
							<td align="center"> Nasal:
							<?php if(!empty($exam_gnscp['examnsn_gonispy_l_nsl_d1'])){ echo $exam_gnscp['examnsn_gonispy_l_nsl_d1'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_l_nsl_d2'])){ echo $exam_gnscp['examnsn_gonispy_l_nsl_d2'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_l_nsl_d3'])){ echo $exam_gnscp['examnsn_gonispy_l_nsl_d3'];}?> </td>
						</tr>
						<tr>
							<td width="33.33%"></td>
							<td width="33.33%" align="center">
							Inferior: <?php if(!empty($exam_gnscp['examnsn_gonispy_l_infr_d1'])){ echo $exam_gnscp['examnsn_gonispy_l_infr_d1'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_l_infr_d2'])){ echo $exam_gnscp['examnsn_gonispy_l_infr_d2'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_l_infr_d3'])){ echo $exam_gnscp['examnsn_gonispy_l_infr_d3'];}?>
								</td>
							<td width="33.33%"></td>
						</tr>
					</tbody>
				</table>
				<?php echo '<br>'.$exam_gnscp['examnsn_gonispy_l_comm']; ?>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;color:<?php echo $exam_gnscp['examnsn_gonispy_r_update'];?>">
				 <strong style="float:left;width:100%;font-size:10px;">Gonioscopy:</strong>
				<table style="width:100%">
					<tbody>
						<tr>
							<td width="33.33%"></td>
							<td width="33.33%" align="center"> Superior : <?php if(!empty($exam_gnscp['examnsn_gonispy_r_sup_d1'])){ echo $exam_gnscp['examnsn_gonispy_r_sup_d1'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_r_sup_d2'])){ echo $exam_gnscp['examnsn_gonispy_r_sup_d2'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_r_sup_d3'])){ echo $exam_gnscp['examnsn_gonispy_r_sup_d3'];}?> </td>
							<td width="33.33%"></td>
						</tr>
						<tr>
							<td align="center"> Temporal:<?php if(!empty($exam_gnscp['examnsn_gonispy_r_tmprl_d1'])){ echo $exam_gnscp['examnsn_gonispy_r_tmprl_d1'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_r_tmprl_d2'])){ echo $exam_gnscp['examnsn_gonispy_r_tmprl_d2'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_r_tmprl_d3'])){ echo $exam_gnscp['examnsn_gonispy_r_tmprl_d3'];}?> </td>
							<td class="plus_symbol" align="center" style="font-size:30px;"> &chi;</td>
							<td align="center"> Nasal:
							<?php if(!empty($exam_gnscp['examnsn_gonispy_r_nsl_d1'])){ echo $exam_gnscp['examnsn_gonispy_r_nsl_d1'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_r_nsl_d2'])){ echo $exam_gnscp['examnsn_gonispy_r_nsl_d2'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_r_nsl_d3'])){ echo $exam_gnscp['examnsn_gonispy_r_nsl_d3'];}?> </td>
						</tr>
						<tr>
							<td width="33.33%"></td>
							<td width="33.33%" align="center">
							Inferior:  <?php if(!empty($exam_gnscp['examnsn_gonispy_r_infr_d1'])){ echo $exam_gnscp['examnsn_gonispy_r_infr_d1'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_r_infr_d2'])){ echo $exam_gnscp['examnsn_gonispy_r_infr_d2'].', ';} if(!empty($exam_gnscp['examnsn_gonispy_r_infr_d3'])){ echo $exam_gnscp['examnsn_gonispy_r_infr_d3'];}?></td>
							<td width="33.33%"></td>
						</tr>
					</tbody>
				</table>
				<?php echo '<br>'.$exam_gnscp['examnsn_gonispy_r_comm']; ?>
			</div>
		</section>		
		 <hr style="margin: 2px !important;">
		<?php }
	    $fundus='';
		foreach ($exam_fundus as $key => $value)
	    {
	    if(!empty($value) && $value !='Normal' && $value !='0' ){
	    $fundus=1;
	    }} 
	    if($fundus==1){	?>	
		<section style="float:left;width:100%;">
			<div style="float:left;width:48.5%;padding:0px 5px;color:<?php echo $exam_fundus['examnsn_fundus_l_update'];?>">
		  <strong style="float:left;width:100%;font-size:10px;">Fundus:</strong>
				
				<?php  if(!empty($exam_fundus['examnsn_fundus_l_dilate'])){ echo $exam_fundus['examnsn_fundus_l_dilate'].', ';}
				if($exam_fundus['examnsn_fundus_l_mda'] !=''){ echo ' Media- '.$exam_fundus['examnsn_fundus_l_mda']; if(!empty($exam_fundus['examnsn_fundus_l_mda_comm'])){ echo '('.$exam_fundus['examnsn_fundus_l_mda_comm'].'), ';}}
				if($exam_fundus['examnsn_fundus_l_pvd'] !=''){ echo ' PVD- '.$exam_fundus['examnsn_fundus_l_pvd'].', ';}
				if($exam_fundus['examnsn_fundus_l_ods'] !=''){ echo ' Optic Disc Size- '.$exam_fundus['examnsn_fundus_l_ods'].', ';}
				if($exam_fundus['examnsn_fundus_l_cdr'] !=''){ echo ' CupRatio- '.$exam_fundus['examnsn_fundus_l_cdr']; if(!empty($exam_fundus['examnsn_fundus_l_cdr_txt'])){ echo '('.$exam_fundus['examnsn_fundus_l_cdr_txt'].'), ';}}
				if($exam_fundus['examnsn_fundus_l_opdisc'] !=''){ echo ' Optic Disc- '.$exam_fundus['examnsn_fundus_l_opdisc'].', ';}
				if($exam_fundus['examnsn_fundus_l_bldvls'] !=''){ echo ' Blood Vessels- '.$exam_fundus['examnsn_fundus_l_bldvls'];} if(!empty($exam_fundus['examnsn_fundus_l_bldvls_txt'])){ echo '('.$exam_fundus['examnsn_fundus_l_bldvls_txt'].'), ';}
				if($exam_fundus['examnsn_fundus_l_mcla'] !=''){ echo ' Macula- '.$exam_fundus['examnsn_fundus_l_mcla'];} if(!empty($exam_fundus['examnsn_fundus_l_mcla_txt'])){ echo '('.$exam_fundus['examnsn_fundus_l_mcla_txt'].'), ';}
				if($exam_fundus['examnsn_fundus_l_vtrs'] !=''){ echo ' Vitreous- '.$exam_fundus['examnsn_fundus_l_vtrs']; if(!empty($exam_fundus['examnsn_fundus_l_vtrs_txt'])){ echo '('.$exam_fundus['examnsn_fundus_l_vtrs_txt'].'), ';}}
				if($exam_fundus['examnsn_fundus_l_rtnldcht'] !=''){ echo ' Retinal Detachment- '.$exam_fundus['examnsn_fundus_l_rtnldcht']; if(!empty($exam_fundus['examnsn_fundus_l_rtnldcht_txt'])){ echo '('.$exam_fundus['examnsn_fundus_l_rtnldcht_txt'].'), ';}}
				if($exam_fundus['examnsn_fundus_l_perlsn'] !=''){ echo ' Peripheral Lesions- '.$exam_fundus['examnsn_fundus_l_perlsn']; if(!empty($exam_fundus['examnsn_fundus_l_perlsn_txt'])){ echo '('.$exam_fundus['examnsn_fundus_l_perlsn_txt'].'), ';}}
					echo $exam_fundus['examnsn_fundus_l_fnds']; 
					echo '<br>'.$exam_fundus['examnsn_fundus_l_comm']; 
				?>
			</div>
			<div style="float:right;width:48.5%;padding:0px 5px;color:<?php echo $exam_fundus['examnsn_fundus_r_update'];?>">
				<strong style="float:left;width:100%;font-size:10px;">Fundus:</strong>
				<?php  
				if(!empty($exam_fundus['examnsn_fundus_r_dialet'])){ echo $exam_fundus['examnsn_fundus_r_dialet'].', ';} 
				if($exam_fundus['examnsn_fundus_r_mda'] !=''){ echo ' Media- '.$exam_fundus['examnsn_fundus_r_mda']; if(!empty($exam_fundus['examnsn_fundus_r_mda_comm'])){ echo '('.$exam_fundus['examnsn_fundus_r_mda_comm'].'), ';}}
				if($exam_fundus['examnsn_fundus_r_pvd'] !=''){ echo ' PVD- '.$exam_fundus['examnsn_fundus_r_pvd'].', ';}
				if($exam_fundus['examnsn_fundus_r_ods'] !=''){ echo ' Optic Disc Size- '.$exam_fundus['examnsn_fundus_r_ods'].', ';}
				if($exam_fundus['examnsn_fundus_r_cdr'] !=''){ echo ' CupRatio- '.$exam_fundus['examnsn_fundus_r_cdr']; if(!empty($exam_fundus['examnsn_fundus_r_cdr_txt'])){ echo '('.$exam_fundus['examnsn_fundus_r_cdr_txt'].'), ';}}
				if($exam_fundus['examnsn_fundus_r_opdisc'] !=''){ echo ' Optic Disc- '.$exam_fundus['examnsn_fundus_r_opdisc'].', ';}
				if($exam_fundus['examnsn_fundus_r_bldvls'] !=''){ echo ' Blood Vessels- '.$exam_fundus['examnsn_fundus_r_bldvls'];} if(!empty($exam_fundus['examnsn_fundus_r_bldvls_txt'])){ echo '('.$exam_fundus['examnsn_fundus_r_bldvls_txt'].'), ';}
				if($exam_fundus['examnsn_fundus_r_mcla'] !=''){ echo ' Macula- '.$exam_fundus['examnsn_fundus_r_mcla'];} if(!empty($exam_fundus['examnsn_fundus_r_mcla_txt'])){ echo '('.$exam_fundus['examnsn_fundus_r_mcla_txt'].'), ';}
				if($exam_fundus['examnsn_fundus_r_vtrs'] !=''){ echo ' Vitreous- '.$exam_fundus['examnsn_fundus_r_vtrs']; if(!empty($exam_fundus['examnsn_fundus_r_vtrs_txt'])){ echo '('.$exam_fundus['examnsn_fundus_r_vtrs_txt'].'), ';}}
				if($exam_fundus['examnsn_fundus_r_rtnldcht'] !=''){ echo ' Retinal Detachment- '.$exam_fundus['examnsn_fundus_r_rtnldcht']; if(!empty($exam_fundus['examnsn_fundus_r_rtnldcht_txt'])){ echo '('.$exam_fundus['examnsn_fundus_r_rtnldcht_txt'].'), ';}}
				if($exam_fundus['examnsn_fundus_r_perlsn'] !=''){ echo ' Peripheral Lesions- '.$exam_fundus['examnsn_fundus_r_perlsn']; if(!empty($exam_fundus['examnsn_fundus_r_perlsn_txt'])){ echo '('.$exam_fundus['examnsn_fundus_r_perlsn_txt'].'), ';}}
					echo $exam_fundus['examnsn_fundus_r_fnds']; 
					echo '<br>'.$exam_fundus['examnsn_fundus_r_comm']; 
				?>
			</div>
		</section>	

<?php } } 

if(!empty($form_data['drawing_flag']))
{
	if(!empty($drawing_list))
	{
      ?>
    <section style="float:left;width:100%;">
		<strong style="font-size: 11px;">DRAWING</strong>  
      <table class="table table-bordered text-center" id="set_drawing" style="width:500px; margin-left:100px; text-align:center;" border="1px" cellpading="0" cellspacing="0">
			<thead >
				<tr>
					<th class="text-center" width="200px;">Image</th>
					<th class="text-center" width="200px;">Remark</th> 
				</tr>
			</thead>
			<tbody>
			    <?php
                foreach($drawing_list as $key=>$drawing)
	            {
	                echo '<tr>
	                               <td><a href="'.ROOT_UPLOADS_PATH.'eye/prescription_drawing/'.$drawing['image'].'"  target="_blank"><img src="'.ROOT_UPLOADS_PATH.'eye/prescription_drawing/'.$drawing['image'].'" width="80px"/></a></td>
	                               <td>'.$drawing['remark'].'</td> 
	                             </tr>';
	            }
			    ?>
			</tbody>
     </table>
     </section>
      <?php
	} 
}
if($form_data['diagnosis_flag']==1){
	if(!empty($diagno_lists)){ ?>
		
	<section style="float:left;width:100%;">
		  <strong style="float:left;width:100%;font-size:11px;">DIAGNOSIS: </strong>
		  <hr style="margin: 2px !important;">
				<ul style="list-style:none;margin:5px 0 10px;">
				<?php  $di=0;
					foreach ($diagno_lists as $key => $diagno) { $di++; ?>
					<li style="margin-top:2px;"><b><?php echo $di; ?>.</b> <?php echo $diagno->eye_side_name;?> - <b><?php echo $diagno->icd_code;?></b></li>
					<li><?php echo $diagno->diagnosis_comment;?></li>
				<?php } ?>
				</ul>
		</section>

		<?php } if(!empty($provisional_comment)){?>
		<section style="float:left;width:100%;">
		  <strong style="float:left;width:100%;font-size:11px;">PROVISIONAL DIAGNOSIS: </strong>
		  <hr style="margin: 2px !important;">
				<ul style="list-style:none;margin:5px 0 10px;padding:0;">
					<li style="margin-top:2px;"><b> 1.</b> <?php echo $provisional_comment.' on: ';?>  <b><?php echo date('d/m/Y h:i A',strtotime($provisional_date));?></b></li>
				</ul>
		</section>
	<?php } ?>
		<?php if(!empty($ophthal_data)){ ?>
		<section style="float:left;width:100%;">
		  <strong style="float:left;width:100%;font-size:10px;">OPHTHAL INVESTIGATIONS: </strong>
		  <hr style="margin: 2px !important;">
				<div style="float:left;width:100%;margin-bottom:5px;">
					<?php
						if(!empty($ophthal_data['ophthal_set'])){ $i=0;
							foreach ($ophthal_data['ophthal_set'] as $key => $opthal) {
								$i++;
							echo ' <strong style="font-size: 10px;"> '.$i.'- </strong> '.$opthal->test_name.'('.$opthal->eye_side.')';} }
						if(!empty($ophthal_data['ophthal_test'])){ $i++;
							foreach ($ophthal_data['ophthal_test'] as $keys => $opthal_test) {
							echo ' <strong style="font-size: 10px;"> '.$i.'- </strong> '.$opthal_test->test_name.'('.$opthal_test->eye_side.')';}} ?>
					
				</div>
				<div style="float:left;width:100%;margin-bottom:20px;">
					<div style="float:left;width:20%;">
						<strong style="font-size: 10px;">Performed:</strong>
					</div>
					<div style="float:left;width:80%;">
						<table width="100%" border="1" style="border-collapse:collapse;font:12px 'Arial';text-align:center;">
							<tr>
								<th>Investigation</th>
								<th>Findings</th>
								<th>Remark</th>
								<th>Doctor's Remark</th>
							</tr>

						<?php if(!empty($ophthal_data)){ 
							foreach ($ophthal_data['ophthal_set'] as $key => $opthals) {
							if(!empty($opthals->performed_res)){?>
							<tr>
								<td><?php echo $opthals->test_name.'('.$opthals->eye_side.')';?></td>
								<td><?php echo $opthals->performed_res;?></td>
								<td>-</td>
								<td>-</td>
							</tr>
							<?php }} ?>
						<?php 
							foreach ($ophthal_data['ophthal_test'] as $keys => $opthal_tst) {
							if(!empty($opthal_tst->performed_res)){?>
							<tr>
								<td><?php echo $opthal_tst->test_name.'('.$opthal_tst->eye_side.')';?></td>
								<td><?php echo $opthal_tst->performed_res;?></td>
								<td>-</td>
								<td>-</td>
							</tr>
							<?php }}} ?>
						</table>
					</div>
				</div>
				<span><?php if(!empty($invest_comment['comment_ophthal']) && $invest_comment['comment_ophthal'] !=''){ echo $invest_comment['comment_ophthal'];}?></span>
		</section>
		<?php } if(!empty($radiology_data)){?>
		<section style="float:left;width:100%;">
		  <strong style="float:left;width:100%;font-size:10px;">RADIOLOGY INVESTIGATIONS: </strong>
		  <hr style="margin: 2px !important;">
				<div style="float:left;width:100%;margin-bottom:5px;">
					<?php 
						if(!empty($radiology_data['radio_set'])){ $j=0;
							foreach ($radiology_data['radio_set'] as $key => $radio_set) {
								$j++;
							echo ' <strong style="font-size: 10px;"> '.$j.'- </strong> '.$radio_set->test_name;} }
						if(!empty($radiology_data['radio_test'])){ $j++;
							foreach ($radiology_data['radio_test'] as $keys => $radio_test) {
							echo ' <strong style="font-size: 10px;"> '.$j.'- </strong> '.$radio_test->test_name;}} ?>
					
				</div>
				<div style="float:left;width:100%;margin-bottom:20px;">
					<div style="float:left;width:20%;">
						<strong style="font-size: 10px;">Performed:</strong>
					</div>
					<div style="float:left;width:80%;">
						<table width="100%" border="1" style="border-collapse:collapse;font:12px 'Arial';text-align:center;">				
						
							<tr>
								<th>Investigation</th>
								<th>Findings</th>
								<th>Remark</th>
								<th>Doctor's Remark</th>
							</tr>

						<?php if(!empty($radiology_data)){ 
							foreach ($radiology_data['radio_set'] as $key => $radios) {
							if(!empty($radios->radio_performed_res)){?>
							<tr>
								<td><?php echo $radios->test_name;?></td>
								<td><?php echo $radios->radio_performed_res;?></td>
								<td>-</td>
								<td>-</td>
							</tr>
							<?php }} ?>
						<?php 
							foreach ($radiology_data['radio_test'] as $keys => $radio_tst) {
							if(!empty($radio_tst->radio_performed_res)){?>
							<tr>
								<td><?php echo $radio_tst->test_name;?></td>
								<td><?php echo $radio_tst->radio_performed_res;?></td>
								<td>-</td>
								<td>-</td>
							</tr>
							<?php }}} ?>
						</table>
					</div>
				</div>
				<span><?php if(!empty($invest_comment['comment_radiology']) && $invest_comment['comment_radiology'] !=''){ echo '<br>'.$invest_comment['comment_radiology'];}?></span>
		</section>

		<?php } if(!empty($laboratory_data)){?>
		<section style="float:left;width:100%;">
		  <strong style="float:left;width:100%;font-size:10px;">LABORATORY INVESTIGATIONS: </strong>
		  <hr style="margin: 2px !important;">
				<div style="float:left;width:100%;margin-bottom:5px;">
					<?php
						if(!empty($laboratory_data['lab_set'])){ $k=0;
							foreach ($laboratory_data['lab_set'] as $key => $lab_set) {
								$k++;
							echo ' <strong style="font-size: 10px;"> '.$k.'- </strong> '.$lab_set->test_name;} }
						if(!empty($laboratory_data['lab_test'])){ $k++;
							foreach ($laboratory_data['lab_test'] as $keys => $lab_test) {
							echo ' <strong style="font-size: 10px;"> '.$k.'- </strong> '.$lab_test->investig_name;}} ?>
					
				</div>
				<div style="float:left;width:100%;margin-bottom:20px;">
					<div style="float:left;width:20%;">
						<strong style="font-size: 10px;">Performed:</strong>
					</div>
					<div style="float:left;width:80%;">
						<table width="100%" border="1" style="border-collapse:collapse;font:12px 'Arial';text-align:center;">				
						
							<tr>
								<th>Investigation</th>
								<th>Findings</th>
								<th>Remark</th>
								<th>Doctor's Remark</th>
							</tr>

						<?php if(!empty($laboratory_data)){ 
							foreach ($laboratory_data['lab_set'] as $key => $lab_sets) {
							if(!empty($lab_sets->lab_performed_res)){?>
							<tr>
								<td><?php echo $lab_sets->test_name;?></td>
								<td><?php echo $lab_sets->lab_performed_res;?></td>
								<td>-</td>
								<td>-</td>
							</tr>
							<?php }} ?>
						<?php 
							foreach ($laboratory_data['lab_test'] as $keys => $lab_tests) {
							if(!empty($lab_tests->lab_performed_res)){?>
							<tr>
								<td><?php echo $lab_tests->investig_name;?></td>
								<td><?php echo $lab_tests->lab_performed_res;?></td>
								<td>-</td>
								<td>-</td>
							</tr>
							<?php }}} ?>
						</table>
					</div>
				</div>
			<span><?php if(!empty($invest_comment['comment_lab']) && $invest_comment['comment_lab'] !=''){ echo '<br>'.$invest_comment['comment_lab'];}?></span>
		</section>
      <?php } } 
      if(!empty($advice_comm['planmanagement'])){ ?>
      	 <hr style="margin: 2px !important;">
			<p><b>Management Plan : </b><?php echo $advice_comm['planmanagement']; ?> </p>
		<?php }  if($form_data['advice_flag']){?>
		<section style="float:left;width:100%;">
			<?php if(!empty($advice_procedure)){ ?>
		  <strong style="float:left;width:100%;font-size:10px;">PROCEDURE: </strong>
		  <hr style="margin: 2px !important;">
				<div style="float:left;width:100%;margin-bottom:5px;">
					<?php $l=0;
							foreach ($advice_procedure as $key => $procedure) {
								$l++;
							echo ' <strong style="font-size: 10px;"> '.$l.'- </strong> '.$procedure->test_name.'('.$procedure->eye_side.') - '.$procedure->p_comm;} }
						?>
					
				</div>
				<span><?php if(!empty($advice_comm['proce_comm']) && $advice_comm['proce_comm'] !=''){ echo $advice_comm['proce_comm'];}?></span>
		</section>


<?php  if(!empty($advice_medication)){
	   $medi=array();
     foreach ($advice_medication as $key => $medicine) {
     	if(!empty($medicine->mname)){
     		$medi[]=$medicine->mname;
     	}
      }

 ?>
		<section style="float:left;width:100%;">
		    <?php 
		    if(!empty($advice_adv)){ if(!empty($advice_adv['advice_txt'])){ ?>
			<span><strong style="float:left;width:100%;font-size:10px;">ADVICE: </strong> <?php  echo $advice_adv['advice_txt'];?></span>
		    <?php } }
		
		    if(count($medi)>0){ ?>
			
			<hr style="margin: 2px !important;">
			<div><b>Medication(Rx)</b></div>
			<div><b>New : </b></div>
			<table width="100%" border="1" style="border-collapse:collapse;font:12px 'Arial';text-align:center;margin-bottom:10px;">
				<thead>
					<tr>
						<th style="padding:3px;font-size:10px;">Sr No.</th>
						<th style="padding:3px;font-size:10px;">Name</th>
						<th style="padding:3px;font-size:10px;">Quantity</th>
						<th style="padding:3px;font-size:10px;" class="text-center">Frequency</th>
						<th style="padding:3px;font-size:10px;">Duration</th>
						<th style="padding:3px;font-size:10px;">Eye</th>
						<th style="padding:3px;font-size:10px;">Instruction</th>
					</tr>
				</thead>
				<tbody>
					<?php  $n=0;
					foreach ($advice_medication as $key => $medicine) { 
					if(!empty($medicine->mname)){ $n++; ?>

					<tr>
						<td style="padding:3px;font-size:10px;"><?php echo $n;?></td>
						<td style="padding:3px;font-size:10px;"><b><?php echo $medicine->mname;?></b></td>
						<td style="padding:3px;font-size:10px;"> <b><?php echo $medicine->mqty;?> </td>
						<td style="padding:3px;font-size:10px;"> <b><?php echo $medicine->mfrq;?> </td>
						<td style="padding:3px;font-size:10px;"> <b><?php echo $medicine->mdur.' '.$medicine->mdurd;?> </td>
						<td style="padding:3px;font-size:10px;"> <?php echo $medicine->eside;?> </td>
						<td style="padding:3px;font-size:10px;"> <?php echo $medicine->minst;?></td>
					</tr>
					<?php } $medicine_datas=get_medicine_freqdata($medicine->med_id, $branch_id, $patient_id,$booking_id,$pres_id); 
					if(!empty($medicine_datas)){?>
					<tr style="background-color:#bbb">
						<td colspan="7" style="padding:4px;">
							<table width="100%" border="1" style="border-collapse:collapse;font:12px 'Arial';text-align:center;background:#fff;">
								<thead>
									<tr>
										<th style="padding:3px;font-size:10px;">Sr No.</th>
										<th style="padding:3px;font-size:10px;">No of Days</th>
										<th style="padding:3px;font-size:10px;">Start Date</th>
										<th style="padding:3px;font-size:10px;">End Date</th>
										<th style="padding:3px;font-size:10px;">Start Time</th>
										<th style="padding:3px;font-size:10px;">End Time</th>
										<th style="padding:3px;font-size:10px;" width="16%">Frequency</th>
										<th style="padding:3px;font-size:10px;" width="16%">Interval</th>
									</tr>
								</thead>
								<tbody>
									<?php $x=0; foreach ($medicine_datas as $key => $medicine) { $x++; ?>
									<tr class="taper_row">
										<td style="padding:3px;font-size:10px;"><?php echo $x;?></td>
										<td style="padding:3px;font-size:10px;"><?php echo $medicine['day'];?></td>
										<td style="padding:3px;font-size:10px;"><?php echo $medicine['st_date'];?></td>
										<td style="padding:3px;font-size:10px;"><?php echo $medicine['en_date'];?> </td>
										<td style="padding:3px;font-size:10px;"><?php echo $medicine['st_time'];?></td>
										<td style="padding:3px;font-size:10px;"><?php echo $medicine['en_time'];?></td>
										<td style="padding:3px;font-size:10px;"><?php echo $medicine['freq'];?> times a day</td>
										<td style="padding:3px;font-size:10px;">Every <?php echo $medicine['intvl'];?> hours</td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</td>
					</tr>
					<?php  }}  ?>
				</tbody>
			</table>

		<?php } if(!empty($advice_comm['medi_comm'])){
			echo '<b style="font-size:10px;">Medication Comments : </b><span style="font-size:10px;">'.$advice_comm['medi_comm'].'</span><br>';
		} ?>
		<?php if($advice_comm['appear_check']==1 && !empty($advice_comm['appear_box'])){
			echo '<b style="font-size:10px;>Glasses Comments : </b><span style="font-size:10px;>'.$advice_comm['appear_box'].'</span><br>';
		} ?>
		<?php if(!empty($advice_adv)){ if(!empty($advice_adv['doctor_name'])){ 
		
		if(!empty($advice_adv['days'])){
		?>
			<span style="font-size:10px;><b>For Followup :</b> - Visit <b><?php  echo 'Dr. '.$advice_adv['doctor_name'];?></b> <?php if(!empty($advice_adv['days'])){ echo 'after '.$advice_adv['days'].' Days'; }?>  </span><br>
		<?php } } } if(!empty($advice_referral)){ 
			foreach ($advice_referral as $key => $referral) { ?>
			<span><?php if(!empty($referral->doctor)){ ?><b>For Referral :</b> - Visit <b><?php  echo 'Dr. '.$referral->doctor; ?></b> at <b> <?php echo $referral->location.' on '; ?></b>  <b><?php if(!empty($referral->date)) { echo date('D M d, Y', strtotime($referral->date));} ?></b>
			
				<?php } if(!empty($referral->note)) {?> <br>
				<b> Note : </b><?php  echo $referral->note;} ?></span>
		<?php }}?>
		</section>
	<?php } } 
	
	//echo $advice_adv['advice_txt']; die;
	 ?>



	<?php if($form_data['biometry_flag']){ ?>
	<section class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			
			
			

	<div class="container bg-white pt-3">
		<h3 class="text-center">BIOMETRY </h3>
		
		<table class="table table-bordered">
			<tr>
				<td style="width:200px"><b>RIGHT <br> EYE</b></td>
				<td>K1</td>
				<td>K2</td>
				<td>AL</td>
			</tr>
			<tr>
				<td style="width:200px" rowspan="3">OB</td>
				<td><?php echo $biometry_ob_k1_one; ?></td>
				<td><?php echo $biometry_ob_k1_two; ?></td>
				<td><?php echo $biometry_ob_k1_three; ?></td>
			</tr>
			<tr>
				<td><?php echo $biometry_ob_k2_one; ?></td>
				<td><?php echo $biometry_ob_k2_two; ?></td>
				<td><?php echo $biometry_ob_k2_three; ?></td>
			</tr>
			<tr>
				<td><?php echo $biometry_ob_al_one; ?></td>
				<td><?php echo $biometry_ob_al_two; ?></td>
				<td><?php echo $biometry_ob_al_three; ?></td>
			</tr>
			<tr>
				<td style="width:200px" rowspan="3">ASCAN <br> & <br>AUTO-K</td>
				<td><?php echo $biometry_ascan_one; ?></td>
				<td><?php echo $biometry_ascan_two; ?></td>
				<td><?php echo $biometry_ascan_three; ?></td>
			</tr>
			<tr>
				<td><?php echo $biometry_ascan_sec_one; ?></td>
				<td><?php echo $biometry_ascan_sec_two; ?></td>
				<td><?php echo $biometry_ascan_sec_three; ?></td>
			</tr>
			<tr>
				<td><?php echo $biometry_ascan_thr_one; ?></td>
				<td><?php echo $biometry_ascan_thr_two; ?></td>
				<td><?php echo $biometry_ascan_thr_three; ?></td>
			</tr>
		</table>

		<table class="table table-bordered">
			<tr>
				<td style="width:200px"> IOL </td>
				<td>SRK-T</td>
				<td>ERROR</td>
				<td>BARETT</td>
				<td>ERROR</td>
			</tr>
			<tr>
				<td style="padding:1px;">
					<?php if($biometry_iol_one=='RYCF'){ ?> RYCF <?php } ?>
						<?php if($biometry_iol_one=='AUROFLEX'){ ?> AUROFLEX <?php } ?>
						<?php if($biometry_iol_one=='ULTIMA'){ ?> ULTIMA <?php } ?>
						<?php if($biometry_iol_one=='AUROFLEX EV'){ ?> AUROFLEX <?php } ?>
						<?php if($biometry_iol_one=='ACRIOL'){ ?> ACRIOL <?php } ?>
						<?php if($biometry_iol_one=='AUROVUE'){ ?> AUROVUE <?php } ?>
						<?php if($biometry_iol_one=='SP'){ ?> SP <?php } ?>
						<?php if($biometry_iol_one=='ACRIVISION'){ ?> ACRIVISION <?php } ?>
						<?php if($biometry_iol_one=='IQ'){ ?> IQ <?php } ?>
						<?php if($biometry_iol_one=='CT LUCIA'){ ?> CT LUCIA <?php } ?>
						<?php if($biometry_iol_one=='CT ASPHINA'){ ?> CT ASPHINA <?php } ?>
						<?php if($biometry_iol_one=='ULTRASERT'){ ?> ULTRASERT <?php } ?>
						<?php if($biometry_iol_one=='RAYONE CFLEX'){ ?> RAYONE CFLEX <?php } ?>
						<?php if($biometry_iol_one=='RAYONE ASPHERIC'){ ?> RAYONE ASPHERIC <?php }?>
						<?php if($biometry_iol_one=='RAYONE'){ ?> RAYONE <?php } ?> 
						<?php if($biometry_iol_one=='HYDROPHOBIC'){ ?> HYDROPHOBIC <?php } ?> 
						<?php if($biometry_iol_one=='PANOPTIX'){ ?> PANOPTIX <?php } ?> 
						<?php if($biometry_iol_one=='TORIC'){ ?> TORIC <?php } ?> 
					
				</td>
				
				<td><?php echo $biometry_srk_one; ?></td>
				<td><?php echo $biometry_error_one; ?></td>
				<td><?php echo $biometry_barett_one; ?></td>
				
				<td><?php echo $biometry_error_one_two; ?></td>
				
			</tr>
			<tr>
				<td style="padding:1px;">
					<?php if($biometry_iol_two=='RYCF'){ ?> RYCF <?php } ?>
						<?php if($biometry_iol_two=='AUROFLEX'){ ?> AUROFLEX <?php } ?>
						<?php if($biometry_iol_two=='ULTIMA'){ ?> ULTIMA <?php } ?>
						<?php if($biometry_iol_two=='AUROFLEX EV'){ ?> AUROFLEX <?php } ?>
						<?php if($biometry_iol_two=='ACRIOL'){ ?> ACRIOL <?php } ?>
						<?php if($biometry_iol_two=='AUROVUE'){ ?> AUROVUE <?php } ?>
						<?php if($biometry_iol_two=='SP'){ ?> SP <?php } ?>
						<?php if($biometry_iol_two=='ACRIVISION'){ ?> ACRIVISION <?php } ?>
						<?php if($biometry_iol_two=='IQ'){ ?> IQ <?php } ?>
						<?php if($biometry_iol_two=='CT LUCIA'){ ?> CT LUCIA <?php } ?>
						<?php if($biometry_iol_two=='CT ASPHINA'){ ?> CT ASPHINA <?php } ?>
						<?php if($biometry_iol_two=='ULTRASERT'){ ?> ULTRASERT <?php } ?>
						<?php if($biometry_iol_two=='RAYONE CFLEX'){ ?> RAYONE CFLEX <?php } ?>
						<?php if($biometry_iol_two=='RAYONE ASPHERIC'){ ?> RAYONE ASPHERIC <?php }?>
						<?php if($biometry_iol_two=='RAYONE'){ ?> RAYONE <?php } ?> 
						<?php if($biometry_iol_two=='HYDROPHOBIC'){ ?> HYDROPHOBIC <?php } ?> 
						<?php if($biometry_iol_two=='PANOPTIX'){ ?> PANOPTIX <?php } ?> 
						<?php if($biometry_iol_two=='TORIC'){ ?> TORIC <?php } ?>
				</td>
				<td><?php echo $biometry_ascan_sec_sec; ?></td>
				<td><?php echo $biometry_error_sec; ?></td>
				<td><?php echo $biometry_barett_sec; ?></td>
				
				<td><?php echo $biometry_error_one_sec; ?></td>
			</tr>
			<tr>
				<td style="padding:1px;">
					<?php if($biometry_iol_thr=='RYCF'){ ?> RYCF <?php } ?>
						<?php if($biometry_iol_thr=='AUROFLEX'){ ?> AUROFLEX <?php } ?>
						<?php if($biometry_iol_thr=='ULTIMA'){ ?> ULTIMA <?php } ?>
						<?php if($biometry_iol_thr=='AUROFLEX EV'){ ?> AUROFLEX <?php } ?>
						<?php if($biometry_iol_thr=='ACRIOL'){ ?> ACRIOL <?php } ?>
						<?php if($biometry_iol_thr=='AUROVUE'){ ?> AUROVUE <?php } ?>
						<?php if($biometry_iol_thr=='SP'){ ?> SP <?php } ?>
						<?php if($biometry_iol_thr=='ACRIVISION'){ ?> ACRIVISION <?php } ?>
						<?php if($biometry_iol_thr=='IQ'){ ?> IQ <?php } ?>
						<?php if($biometry_iol_thr=='CT LUCIA'){ ?> CT LUCIA <?php } ?>
						<?php if($biometry_iol_thr=='CT ASPHINA'){ ?> CT ASPHINA <?php } ?>
						<?php if($biometry_iol_thr=='ULTRASERT'){ ?> ULTRASERT <?php } ?>
						<?php if($biometry_iol_thr=='RAYONE CFLEX'){ ?> RAYONE CFLEX <?php } ?>
						<?php if($biometry_iol_thr=='RAYONE ASPHERIC'){ ?> RAYONE ASPHERIC <?php }?>
						<?php if($biometry_iol_thr=='RAYONE'){ ?> RAYONE <?php } ?> 
						<?php if($biometry_iol_thr=='HYDROPHOBIC'){ ?> HYDROPHOBIC <?php } ?> 
						<?php if($biometry_iol_thr=='PANOPTIX'){ ?> PANOPTIX <?php } ?> 
						<?php if($biometry_iol_thr=='TORIC'){ ?> TORIC <?php } ?>
				</td>
				<td><?php echo $biometry_ascan_sec_thr; ?></td>
				<td><?php echo $biometry_error_thr; ?></td>
				<td><?php echo $biometry_barett_thr; ?></td>
				
				<td><?php echo $biometry_error_one_thr; ?></td>
			</tr>
			<tr>
				<td style="padding:1px;">
					<?php if($biometry_iol_four=='RYCF'){ ?> RYCF <?php } ?>
						<?php if($biometry_iol_four=='AUROFLEX'){ ?> AUROFLEX <?php } ?>
						<?php if($biometry_iol_four=='ULTIMA'){ ?> ULTIMA <?php } ?>
						<?php if($biometry_iol_four=='AUROFLEX EV'){ ?> AUROFLEX <?php } ?>
						<?php if($biometry_iol_four=='ACRIOL'){ ?> ACRIOL <?php } ?>
						<?php if($biometry_iol_four=='AUROVUE'){ ?> AUROVUE <?php } ?>
						<?php if($biometry_iol_four=='SP'){ ?> SP <?php } ?>
						<?php if($biometry_iol_four=='ACRIVISION'){ ?> ACRIVISION <?php } ?>
						<?php if($biometry_iol_four=='IQ'){ ?> IQ <?php } ?>
						<?php if($biometry_iol_four=='CT LUCIA'){ ?> CT LUCIA <?php } ?>
						<?php if($biometry_iol_four=='CT ASPHINA'){ ?> CT ASPHINA <?php } ?>
						<?php if($biometry_iol_four=='ULTRASERT'){ ?> ULTRASERT <?php } ?>
						<?php if($biometry_iol_four=='RAYONE CFLEX'){ ?> RAYONE CFLEX <?php } ?>
						<?php if($biometry_iol_four=='RAYONE ASPHERIC'){ ?> RAYONE ASPHERIC <?php }?>
						<?php if($biometry_iol_four=='RAYONE'){ ?> RAYONE <?php } ?> 
						<?php if($biometry_iol_four=='HYDROPHOBIC'){ ?> HYDROPHOBIC <?php } ?> 
						<?php if($biometry_iol_four=='PANOPTIX'){ ?> PANOPTIX <?php } ?> 
						<?php if($biometry_iol_four=='TORIC'){ ?> TORIC <?php } ?>
				</td>
				<td><?php echo $biometry_ascan_sec_four; ?></td>
				<td><?php echo $biometry_error_four; ?></td>
				<td><?php echo $biometry_barett_four; ?></td>
				
				<td><?php echo $biometry_error_one_four; ?></td>
			</tr>
			<tr>
				<td style="padding:1px;">
					<?php if($biometry_iol_five=='RYCF'){ ?> RYCF <?php } ?>
						<?php if($biometry_iol_five=='AUROFLEX'){ ?> AUROFLEX <?php } ?>
						<?php if($biometry_iol_five=='ULTIMA'){ ?> ULTIMA <?php } ?>
						<?php if($biometry_iol_five=='AUROFLEX EV'){ ?> AUROFLEX <?php } ?>
						<?php if($biometry_iol_five=='ACRIOL'){ ?> ACRIOL <?php } ?>
						<?php if($biometry_iol_five=='AUROVUE'){ ?> AUROVUE <?php } ?>
						<?php if($biometry_iol_five=='SP'){ ?> SP <?php } ?>
						<?php if($biometry_iol_five=='ACRIVISION'){ ?> ACRIVISION <?php } ?>
						<?php if($biometry_iol_five=='IQ'){ ?> IQ <?php } ?>
						<?php if($biometry_iol_five=='CT LUCIA'){ ?> CT LUCIA <?php } ?>
						<?php if($biometry_iol_five=='CT ASPHINA'){ ?> CT ASPHINA <?php } ?>
						<?php if($biometry_iol_five=='ULTRASERT'){ ?> ULTRASERT <?php } ?>
						<?php if($biometry_iol_five=='RAYONE CFLEX'){ ?> RAYONE CFLEX <?php } ?>
						<?php if($biometry_iol_five=='RAYONE ASPHERIC'){ ?> RAYONE ASPHERIC <?php }?>
						<?php if($biometry_iol_five=='RAYONE'){ ?> RAYONE <?php } ?> 
						<?php if($biometry_iol_five=='HYDROPHOBIC'){ ?> HYDROPHOBIC <?php } ?> 
						<?php if($biometry_iol_five=='PANOPTIX'){ ?> PANOPTIX <?php } ?> 
						<?php if($biometry_iol_five=='TORIC'){ ?> TORIC <?php } ?>
				</td>
				<td><?php echo $biometry_ascan_sec_five; ?></td>
				<td><?php echo $biometry_error_five; ?></td>
				<td><?php echo $biometry_barett_five; ?></td>
				
				<td><?php echo $biometry_error_one_five; ?></td>
			</tr>
			<?php if(!empty($biometry_remarks)){ ?>
			<tfoot>
				<tr>
					<td colspan="5">
						<strong style="font-size: 10px;">REMARKS:</strong> <br>
						<?php echo $biometry_remarks; ?>
					</td>
				</tr>
			</tfoot>
			<?php } ?>
		</table>
		
	</div>
		</div>
	</div>
</section>
<?php } ?>

<!--	<section>
		< ?php if(!empty($form_data['ref_doctor_name'])){
		 $ref_address=$form_data['address'];
        if(!empty($form_data['address2']))
        {
          $ref_address.= ', '.$form_data['address2'];
        }
         if(!empty($form_data['address3']))
        {
          $ref_address.= ', '.$form_data['address3'];
        }
        if(!empty($form_data['ref_doctor_name']))
        {
        ?>
        
        
       <b> Refferal Doctor Details : </b> <br/> <div> <b>Name :</b> < ?php echo $form_data['ref_doctor_name']; if(!empty($form_data['ref_mobile_no'])){ ?> <br/> <b> Contact No. : </b>  < ?php echo $form_data['ref_mobile_no']; } if(!empty($ref_address)){ ?><br/>  <b> Address : </b>< ?php echo $ref_address;
   } }?> </div>
   
   < ?php  } ?>
	</section>  -->
</page>
</body>
</html>