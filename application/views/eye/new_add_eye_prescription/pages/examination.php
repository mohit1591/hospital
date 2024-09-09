<style>     
	#examination_page .gray {background:#9e9e9e !important;color:white;}   
	.row .btn-default{width:unset !important;} 
	#examination_page .btn-toggle {margin:0 0 2px 0 ;}
	#examination_page .btn-toggle > .btn {padding:2px 10px !important;width:unset;}
	/*#examination_page .btn-toggle > .btn-info {background:#9e9e9e !important;border-color:#9e9e9e !important;}  */
	#examination_page .btn-toggle > .btn-default:hover {background:#fff;}
	#examination_page table.table td{border:0 !important;}
	#examination_page table td:first-child {width:auto;text-align:left;}
	#examination_page table.table {margin-bottom:0px;}
	.wPaint-menu.ui-draggable.wPaint-menu-alignment-horizontal{width: unset!important;}
	#wPaint > div {left:0px !important ;}   
</style>
<section class="panel panel-default" id="examination_page">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-2">General Examination : </div>
			<div class="col-md-2">
				<div class="c-btn-group">
					<label class="c-button <?php if($exam_general['examnsn_gen_examina']=='Normal' || $exam_general['examnsn_gen_examina']==''){ echo 'btn-info';}?>">
						<input type="radio" name="examnsn_gen_examina" <?php if($exam_general['examnsn_gen_examina']=='Normal' || $exam_general['examnsn_gen_examina']==''){ echo 'checked';}?> value="Normal" class="radio_toggle_g_examin"> Normal 
					</label>
					<label class="c-button <?php if($exam_general['examnsn_gen_examina']=='Abnormal'){ echo 'btn-info';}?>">
						<input type="radio" name="examnsn_gen_examina" <?php if($exam_general['examnsn_gen_examina']=='Abnormal'){ echo 'checked';}?> value="Abnormal" class="radio_toggle_g_examin"> Abnormal 
					</label>
				</div>
			</div>
			<div class="col-md-8 <?php if($exam_general['examnsn_gen_examina']=='Abnormal'){ echo 'd-block';} else{ echo 'd-none';}?>" id="abnormal_txtbox">
				<input type="text" name="general_exam_abnormal" value="<?php echo $exam_general['general_exam_abnormal'];?>" class="form-control">
			</div>
		</div>
		<div class="row">
			<div class="col-md-2">One Eyed :</div>
			<div class="col-md-2">				
				<div class="c-btn-group">
					<label class="c-button <?php if($exam_general['examnsn_gen_examina_eye']=='No'){ echo 'btn-info';}?>">
						<input type="radio" name="examnsn_gen_examina_eye" <?php if($exam_general['examnsn_gen_examina_eye']=='No'){ echo 'checked';}?> value="No" class="radio_toggle_one_eye"> No 
					</label>
					<label class="c-button <?php if($exam_general['examnsn_gen_examina_eye']=='Yes'){ echo 'btn-info';}?>">
						<input type="radio" name="examnsn_gen_examina_eye" <?php if($exam_general['examnsn_gen_examina_eye']=='Yes'){ echo 'checked';}?> value="Yes" class="radio_toggle_one_eye"> Yes 
					</label>
				</div>
			
			</div>
		</div>
	</div>
</section>


<section>
	<div class="row">
		<div class="col-md-6">
			<!-- ------- Left side --------- -->
			<div class=" panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-10 text-center">R/OD </div> 
						<div class="col-xs-2 c-btn-group">
							<label class="c-button <?php if($exam_general['examnsn_lod_normal']=='Normal'){ echo 'btn-info';}?>">
								<input type="checkbox" onclick="$(this).parent().toggleClass('btn-info');" value="Normal" <?php if($exam_general['examnsn_lod_normal']=='Normal'){ echo 'checked';}?> name="examnsn_lod_normal"> Normal
							</label>
						</div>
					</div>
				</div>
				<!-- APPEARANCEffgh -->
				<div class="panel-body border-bottom">
					<div class="row">
						<div class="col-md-12">
							<span class="btn_fill">APPEARANCE</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_appearance').toggle();$('.fa_toggle1').toggleClass('fa-minus');"><i class="fa_toggle1 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row toggle_appearance d-none">
						<div class="col-md-3">
							<label class="small">Phthisis Bulbi</label> <br>
							<div class="c-btn-group">
								<label class="c-button  <?php if($exam_aprnc['examnsn_aprnc_l_phthisis']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_aprnc['examnsn_aprnc_l_phthisis']=='No'){ echo 'checked';}?> value="No" name="examnsn_aprnc_l_phthisis" class="radio_toggle_ph_bul opm1"> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_l_phthisis']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_aprnc['examnsn_aprnc_l_phthisis']=='Yes'){ echo 'checked';}?> name="examnsn_aprnc_l_phthisis" class="radio_toggle_ph_bul opm1"> Yes 
								</label>
							</div>
						</div>

						<div class="col-md-3">
							<label class="small">Anophthalmos</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_l_anthms']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_aprnc['examnsn_aprnc_l_anthms']=='No'){ echo 'checked';}?> value="No" name="examnsn_aprnc_l_anthms" class="radio_toggle_anop opm1"> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_l_anthms']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_aprnc['examnsn_aprnc_l_anthms']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_aprnc_l_anthms" class="radio_toggle_anop opm1"> Yes 
								</label>
							</div>
					
						</div>
						<div class="col-md-3">
							<label class="small">Micropththalmos</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_l_mcrthms']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_aprnc['examnsn_aprnc_l_mcrthms']=='No'){ echo 'checked';}?> value="No" name="examnsn_aprnc_l_mcrthms" class="radio_toggle_microp opm1"> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_l_mcrthms']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_aprnc['examnsn_aprnc_l_mcrthms']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_aprnc_l_mcrthms" class="radio_toggle_microp opm1"> Yes 
								</label>
							</div>
	
						</div>
						<div class="col-md-3">
							<label class="small">Artificial</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_l_artfsl']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_aprnc['examnsn_aprnc_l_artfsl']=='No'){ echo 'checked';}?> value="No" name="examnsn_aprnc_l_artfsl" class="radio_toggle_artifi opm1"> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_l_artfsl']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_aprnc['examnsn_aprnc_l_artfsl']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_aprnc_l_artfsl" class="radio_toggle_artifi opm1"> Yes 
								</label>
							</div>
					
						</div>
					</div>
					<div class="row toggle_appearance d-none mb-5">
						<div class="col-md-3">
							<label class="small">Proptosis</label><br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_l_prptsis']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_aprnc['examnsn_aprnc_l_prptsis']=='No'){ echo 'checked';}?> value="No" name="examnsn_aprnc_l_prptsis" class="radio_toggle_propt opm1"> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_l_prptsis']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_aprnc['examnsn_aprnc_l_prptsis']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_aprnc_l_prptsis" class="radio_toggle_propt opm1"> Yes 
								</label>
							</div>
							
						</div>
						<div class="col-md-3">
							<label class="small">Dystopia</label><br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_l_dsptpa']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_aprnc['examnsn_aprnc_l_dsptpa']=='No'){ echo 'checked';}?> value="No" name="examnsn_aprnc_l_dsptpa" class="radio_toggle_dystop opm1"> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_l_dsptpa']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_aprnc['examnsn_aprnc_l_dsptpa']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_aprnc_l_dsptpa" class="radio_toggle_dystop opm1"> Yes 
								</label>
							</div>
						
						</div>
						<div class="col-md-3">
							<label class="small">Injured</label><br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_l_injrd']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_aprnc['examnsn_aprnc_l_injrd']=='No'){ echo 'checked';}?> value="No" name="examnsn_aprnc_l_injrd" class="radio_toggle_injur opm1"> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_l_injrd']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_aprnc['examnsn_aprnc_l_injrd']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_aprnc_l_injrd" class="radio_toggle_injur opm1"> Yes 
								</label>
							</div>
							
						</div>
						<div class="col-md-3">
							<label class="small">Swollen</label><br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_l_swln']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_aprnc['examnsn_aprnc_l_swln']=='No'){ echo 'checked';}?> value="No" name="examnsn_aprnc_l_swln" class="radio_toggle_swollen opm1"> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_l_swln']=='Yes'){ echo 'btn-info';}?> ">
									<input type="radio" <?php if($exam_aprnc['examnsn_aprnc_l_swln']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_aprnc_l_swln" class="radio_toggle_swollen opm1"> Yes 
								</label>
							</div>
							
						</div>
					</div>
					<div class="row toggle_appearance d-none">
						<div class="col-md-3">
							<label class="small">Comments</label>
						</div>
						<div class="col-md-9">
							<textarea name="examnsn_aprnc_l_comm" class="form-control opm1"><?php echo $exam_aprnc['examnsn_aprnc_l_comm'];?></textarea>
						</div>
					</div>
					<input type="hidden" id="opm1" name="examnsn_aprnc_l_update" value="<?php echo $exam_aprnc['examnsn_aprnc_l_update'];?>">
				</div>
				<!-- APPENDAGES -->
				<div class="panel-body border-bottom">
					<div class="row">
						<div class="col-md-12">
							<span class="btn_fill">APPENDAGES</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_appen').toggle();$('.fa_toggle3').toggleClass('fa-minus');"><i class="fa_toggle3 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row d-none toggle_appen">
						<div class="col-md-3"></div>
						<div class="col-md-7 text-center pl-0">
								<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelids']=='Eyelids'){ echo 'btn-info';}?>">
									<input type="checkbox" <?php if($exam_apnds['examnsn_apndgs_l_eyelids']=='Eyelids'){ echo 'checked';}?>  class="toggle_appen_l" value="Eyelids" name="examnsn_apndgs_l_eyelids" onclick="$('.toggle_appen_a').toggle();"> Eyelids
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelshs']=='Eyelashes'){ echo 'btn-info';}?>">
									<input type="checkbox" class="toggle_appen_l" value="Eyelashes" name="examnsn_apndgs_l_eyelshs" <?php if($exam_apnds['examnsn_apndgs_l_eyelshs']=='Eyelashes'){ echo 'checked';}?> onclick="$('.toggle_appen_a_b').toggle();"> Eyelashes
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_lcmlc']=='Lacrimal sac'){ echo 'btn-info';}?>">
									<input type="checkbox" class="toggle_appen_l" value="Lacrimal sac" name="examnsn_apndgs_l_lcmlc" <?php if($exam_apnds['examnsn_apndgs_l_lcmlc']=='Lacrimal sac'){ echo 'checked';}?> onclick="$('.toggle_appen_a_c').toggle();"> Lacrimal sac
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_syrn']=='Syringing'){ echo 'btn-info';}?>">
									<input type="checkbox" class="toggle_appen_l" value="Syringing" name="examnsn_apndgs_l_syrn" <?php if($exam_apnds['examnsn_apndgs_l_syrn']=='Syringing'){ echo 'checked';}?> onclick="$('.toggle_appen_a_d').toggle();"> Syringing
								</label>
							</div>
						</div>
						<div class="col-md-2"></div>
					</div>
					<div class="row d-none toggle_appen_a">
						<div class="col-sm-3">
							<div class="small">Chalazion</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelids_chzn']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_apnds['examnsn_apndgs_l_eyelids_chzn']=='No'){ echo 'checked';}?> value="No" name="examnsn_apndgs_l_eyelids_chzn" class="radio_toggle_chalaz opm3"> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelids_chzn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_apnds['examnsn_apndgs_l_eyelids_chzn']=='Yes'){ echo 'checked';}?> name="examnsn_apndgs_l_eyelids_chzn" class="radio_toggle_chalaz opm3"> Yes 
								</label>
							</div>
		
						</div>
						<div class="col-sm-3">
							<div class="small">Ptosis</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelids_ptss']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_apndgs_l_eyelids_ptss" <?php if($exam_apnds['examnsn_apndgs_l_eyelids_ptss']=='No'){ echo 'checked';}?> class="radio_toggle_ptosis opm3"> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelids_ptss']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_apnds['examnsn_apndgs_l_eyelids_ptss']=='Yes'){ echo 'checked';}?> name="examnsn_apndgs_l_eyelids_ptss" class="radio_toggle_ptosis opm3"> Yes 
								</label>
							</div>
							
						</div>
						<div class="col-sm-3">
							<div class="small">Swelling</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelids_swln']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_apnds['examnsn_apndgs_l_eyelids_swln']=='No'){ echo 'checked';}?> value="No" name="examnsn_apndgs_l_eyelids_swln" class="radio_toggle_swellening opm3"> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelids_swln']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_apnds['examnsn_apndgs_l_eyelids_swln']=='Yes'){ echo 'checked';}?> name="examnsn_apndgs_l_eyelids_swln" class="radio_toggle_swellening opm3"> Yes 
								</label>
							</div>
				
						</div>
						<div class="col-sm-3">
							<div class="small">Entropion</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelids_intrpn']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_apnds['examnsn_apndgs_l_eyelids_intrpn']=='No'){ echo 'checked';}?> value="No" name="examnsn_apndgs_l_eyelids_intrpn" class="radio_toggle_enteropion opm3"> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelids_intrpn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_apnds['examnsn_apndgs_l_eyelids_intrpn']=='Yes'){ echo 'checked';}?> name="examnsn_apndgs_l_eyelids_intrpn" class="radio_toggle_enteropion opm3"> Yes 
								</label>
							</div>
				
						</div>
					</div>
					<div class="row d-none toggle_appen_a">
						<div class="col-sm-3">
							<div class="small">Ectropion</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelids_ectrpn']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_apnds['examnsn_apndgs_l_eyelids_ectrpn']=='No'){ echo 'checked';}?> value="No" name="examnsn_apndgs_l_eyelids_ectrpn" class="radio_toggle_ectropion opm3"> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelids_ectrpn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_apnds['examnsn_apndgs_l_eyelids_ectrpn']=='Yes'){ echo 'checked';}?> name="examnsn_apndgs_l_eyelids_ectrpn" class="radio_toggle_ectropion opm3"> Yes 
								</label>
							</div>
						
						</div>
						<div class="col-sm-3">
							<div class="small">Mass</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelids_mass']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_apnds['examnsn_apndgs_l_eyelids_mass']=='No'){ echo 'checked';}?> value="No" name="examnsn_apndgs_l_eyelids_mass" class="radio_toggle_mass opm3"> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelids_mass']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_apnds['examnsn_apndgs_l_eyelids_mass']=='Yes'){ echo 'checked';}?> name="examnsn_apndgs_l_eyelids_mass" class="radio_toggle_mass opm3"> Yes 
								</label>
							</div>
						
						</div>
						<div class="col-sm-3">
							<div class="small">Meibomitis</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelids_mbts']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_apnds['examnsn_apndgs_l_eyelids_mbts']=='No'){ echo 'checked';}?> value="No" name="examnsn_apndgs_l_eyelids_mbts" class="radio_toggle_meibomities opm3"> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelids_mbts']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_apnds['examnsn_apndgs_l_eyelids_mbts']=='Yes'){ echo 'checked';}?> name="examnsn_apndgs_l_eyelids_mbts" class="radio_toggle_meibomities opm3"> Yes 
								</label>
							</div>
						
						</div>
					</div>			
					<div class="row d-none toggle_appen_a_b">
						<div class="col-sm-3">
							<div class="small">Trichiasis</div>
							<div class="c-btn-group">
								<label class="c-button  <?php if($exam_apnds['examnsn_apndgs_l_eyelshs_tchs']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_apnds['examnsn_apndgs_l_eyelshs_tchs']=='No'){ echo 'checked';}?> value="No" name="examnsn_apndgs_l_eyelshs_tchs" class="radio_toggle_trichi opm3"> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelshs_tchs']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_apnds['examnsn_apndgs_l_eyelshs_tchs']=='Yes'){ echo 'checked';}?> name="examnsn_apndgs_l_eyelshs_tchs" class="radio_toggle_trichi opm3"> Yes 
								</label>
							</div>
						
						</div>
						<div class="col-sm-3">
							<div class="small">Dystrichiasis</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelshs_dtchs']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_apnds['examnsn_apndgs_l_eyelshs_dtchs']=='No'){ echo 'checked';}?> value="No" name="examnsn_apndgs_l_eyelshs_dtchs" class="radio_toggle_dystrich opm3"> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_eyelshs_dtchs']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_apnds['examnsn_apndgs_l_eyelshs_dtchs']=='Yes'){ echo 'checked';}?> name="examnsn_apndgs_l_eyelshs_dtchs" class="radio_toggle_dystrich opm3"> Yes 
								</label>
							</div>
						
						</div>
					</div>			
					<div class="row d-none toggle_appen_a_c">
						<div class="col-sm-3">
							<div class="small">Swelling</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_lcmlc_swln']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_apnds['examnsn_apndgs_l_lcmlc_swln']=='No'){ echo 'checked';}?> value="No" name="examnsn_apndgs_l_lcmlc_swln" class="radio_toggle_swellening2 opm3"> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_lcmlc_swln']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_apnds['examnsn_apndgs_l_lcmlc_swln']=='Yes'){ echo 'checked';}?> name="examnsn_apndgs_l_lcmlc_swln" class="radio_toggle_swellening2 opm3"> Yes 
								</label>
							</div>
							
						</div>
						<div class="col-sm-3">
							<div class="small">Regurgitation</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_lcmlc_regusn']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_apnds['examnsn_apndgs_l_lcmlc_regusn']=='No'){ echo 'checked';}?> value="No" name="examnsn_apndgs_l_lcmlc_regusn" class="radio_toggle_regur opm3"> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_lcmlc_regusn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_apnds['examnsn_apndgs_l_lcmlc_regusn']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_apndgs_l_lcmlc_regusn" class="radio_toggle_regur opm3"> Yes 
								</label>
							</div>
							
						</div>
					</div>			
					<div class="row d-none toggle_appen_a_d">
						<div class="col-sm-4">
							<div class="small">Syringing</div>
							<div class="c-btn-group">
								<label class="c-button  <?php if($exam_apnds['examnsn_apndgs_l_syrn_syrn']=='No'){ echo 'btn-info';}?>">
									<input type="radio"  <?php if($exam_apnds['examnsn_apndgs_l_syrn_syrn']=='No'){ echo 'checked';}?> value="No" name="examnsn_apndgs_l_syrn_syrn" class="radio_toggle_syring opm3"> Patent 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_l_syrn_syrn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_apnds['examnsn_apndgs_l_syrn_syrn']=='Yes'){ echo 'checked';}?>  value="Yes" name="examnsn_apndgs_l_syrn_syrn" class="radio_toggle_syring opm3"> Blocked
								</label>
							</div>
							
						</div>
					</div>		
					<div class="row d-none toggle_appen m-t-3">
						<div class="col-sm-3">
							<div class="small">Comments</div>						
						</div>
						<div class="col-sm-9">
							<textarea name="examnsn_apndgs_l_comm" class="form-control opm3"><?php echo $exam_apnds['examnsn_apndgs_l_comm'];?></textarea>
						</div>
					</div>
					<input type="hidden" name="examnsn_apndgs_l_update" id="opm3" value="<?php echo $exam_apnds['examnsn_apndgs_l_update'];?>">
				</div>
				<!-- CONJUNCTIVA -->
				<div class="panel-body border-bottom">
					<div class="row">
						<div class="col-md-12">
							<span class="btn_fill">CONJUNCTIVA</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_conj').toggle();$('.fa_toggle5').toggleClass('fa-minus');"><i class="fa_toggle5 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row d-none toggle_conj">
						<div class="col-md-3">
							<label class="small">Congestion</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_consn']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_consn']=='No'){ echo 'checked';}?> value="No" name="examnsn_conjtv_l_consn" class="radio_toggle_cong1 opm5"> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_consn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_consn']=='Yes'){ echo 'checked';}?> onclick="$('.toggle_conj_conges_l').toggle();"  value="Yes" name="examnsn_conjtv_l_consn" class="radio_toggle_cong1 opm5"> Yes 
								</label>
							</div>							
						</div>
						<div class="col-md-3">
							<label class="small">Tear</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_tear']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_tear']=='No'){ echo 'checked';}?> value="No" name="examnsn_conjtv_l_tear" class="radio_toggle_cong2 opm5"> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_tear']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_tear']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_conjtv_l_tear" class="radio_toggle_cong2 opm5"> Yes 
								</label>
							</div>
					
						</div>
						<div class="col-md-3">
							<label class="small">Conjuctival Bleb</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_cbleb']=='No'){ echo 'btn-info';}?>">
									<input type="radio"  <?php if($exam_conjtv['examnsn_conjtv_l_cbleb']=='No'){ echo 'checked';}?> value="No" name="examnsn_conjtv_l_cbleb" class="radio_toggle_cong3 opm5"> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_cbleb']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_cbleb']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_conjtv_l_cbleb" class="radio_toggle_cong3 opm5"> Yes 
								</label>
							</div>
						
						</div>
					</div>

					<div class="row d-none toggle_conj_conges_l">
						<div class="col-md-12">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_consn_sub']=='Generalized'){ echo 'btn-info';}?>">
									<input type="radio" value="Generalized"  <?php if($exam_conjtv['examnsn_conjtv_l_consn_sub']=='Generalized'){ echo 'checked';}?> name="examnsn_conjtv_l_consn_sub" class="radio_toggle_cong_sl opm5"> Generalized 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_consn_sub']=='Localized'){ echo 'btn-info';}?>">
									<input type="radio" value="Localized"  <?php if($exam_conjtv['examnsn_conjtv_l_consn_sub']=='Localized'){ echo 'checked';}?> name="examnsn_conjtv_l_consn_sub" class="radio_toggle_cong_sl opm5"> Localized 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_consn_sub']=='Ciliary'){ echo 'btn-info';}?>">
									<input type="radio" value="Ciliary"  <?php if($exam_conjtv['examnsn_conjtv_l_consn_sub']=='Ciliary'){ echo 'checked';}?> name="examnsn_conjtv_l_consn_sub" class="radio_toggle_cong_sl opm5"> Ciliary 
								</label>
							</div>							
						</div>
					</div>
					<div class="row d-none toggle_conj">
						<div class="col-md-6">
							<label class="small">SubConjunctival Haemorrhage</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_sbhrg']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_sbhrg']=='No'){ echo 'checked';}?> value="No" name="examnsn_conjtv_l_sbhrg" class="radio_toggle_cong4 opm5"> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_sbhrg']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_conjtv['examnsn_conjtv_l_sbhrg']=='Yes'){ echo 'checked';}?> name="examnsn_conjtv_l_sbhrg" class="radio_toggle_cong4 opm5"> Yes 
								</label>
							</div>
				
						</div>
						<div class="col-md-3">
							<label class="small">Foreign Body</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_fbd']=='No'){ echo 'btn-info';}?>">
									<input type="radio"  <?php if($exam_conjtv['examnsn_conjtv_l_fbd']=='No'){ echo 'checked';}?> value="No" name="examnsn_conjtv_l_fbd" class="radio_toggle_cong5 opm5"> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_fbd']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_fbd']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_conjtv_l_fbd" class="radio_toggle_cong5 opm5"> Yes 
								</label>
							</div>
							
						</div>
					</div>
					<div class="row d-none toggle_conj">
						<div class="col-md-3">
							<label class="small">Follicles</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_flcls']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_flcls']=='No'){ echo 'checked';}?> value="No" name="examnsn_conjtv_l_flcls" class="radio_toggle_cong6 opm5"> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_flcls']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_flcls']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_conjtv_l_flcls" class="radio_toggle_cong6 opm5"> Yes 
								</label>
							</div>
						
						</div>
						<div class="col-md-3">
							<label class="small">Papillae</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_paple']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_paple']=='No'){ echo 'checked';}?> value="No" name="examnsn_conjtv_l_paple" class="radio_toggle_cong7 opm5"> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_paple']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_paple']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_conjtv_l_paple" class="radio_toggle_cong7 opm5"> Yes 
								</label>
							</div>
							
						</div>
						<div class="col-md-3">
							<label class="small">Pinguecula</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_pngcla']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_pngcla']=='No'){ echo 'checked';}?> value="No" name="examnsn_conjtv_l_pngcla" class="radio_toggle_cong8 opm5"> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_pngcla']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_pngcla']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_conjtv_l_pngcla" class="radio_toggle_cong8 opm5"> Yes 
								</label>
							</div>
							
						</div>
					</div>
					<div class="row mb-5 d-none toggle_conj">
						<div class="col-md-3">
							<label class="small">Pterygium</label> <br>							
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_ptrgm']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_ptrgm']=='No'){ echo 'checked';}?> value="No" name="examnsn_conjtv_l_ptrgm" class="radio_toggle_cong9 opm5"> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_ptrgm']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_ptrgm']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_conjtv_l_ptrgm" class="radio_toggle_cong9 opm5"> Yes 
								</label>
							</div>
							
						</div>
						<div class="col-md-3">
							<label class="small">Phlycten</label> <br>							
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_phctn']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_phctn']=='No'){ echo 'checked';}?> value="No" name="examnsn_conjtv_l_phctn" class="radio_toggle_cong10 opm5"> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_phctn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_phctn']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_conjtv_l_phctn" class="radio_toggle_cong10 opm5"> Yes 
								</label>
							</div>
					
						</div>
						<div class="col-md-3">
							<label class="small">Discharge</label> <br>							
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_dseg']=='No'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_dseg']=='No'){ echo 'checked';}?> value="No" name="examnsn_conjtv_l_dseg" class="radio_toggle_cong11 opm5"> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_l_dseg']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_conjtv['examnsn_conjtv_l_dseg']=='Yes'){ echo 'checked';}?> value="Yes" name="examnsn_conjtv_l_dseg" class="radio_toggle_cong11 opm5"> Yes 
								</label>
							</div>
							
						</div>
					</div>
					<div class="row d-none toggle_conj">
						<div class="col-md-3">
							<label class="small">Comments </label> 
						</div>
						<div class="col-md-9">
							<textarea name="examnsn_conjtv_l_comm" class="form-control opm5"><?php echo $exam_conjtv['examnsn_conjtv_l_comm'];?></textarea>
						</div>
					</div>
					<input type="hidden" name="examnsn_conjtv_l_update" id="opm5" value="<?php echo $exam_conjtv['examnsn_conjtv_l_update'];?>">
				</div>
				<!-- CORNEA-->
				<div class="panel-body border-bottom">
					<div class="row">
						<div class="col-md-12">
							<span class="btn_fill">CORNEA</span>
							<label class="btn_fill" onclick="$('.toggle_cornea').toggle();$('.fa_toggle7').toggleClass('fa-minus');"><i class="fa_toggle7 fa fa-plus"></i></label>
						</div>
					</div>
					<div class="row toggle_cornea d-none mb-5">
						<div class="col-md-2">Size</div>
						<div class="col-md-10">					
						  <div class="c-btn-group">
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_sz']=='Normal'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_cornea['examnsn_cornea_l_sz']=='Normal'){ echo 'checked';}?> value="Normal" name="examnsn_cornea_l_sz" class="radio_toggle_crna1 opm7"> Normal 
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_sz']=='Micro'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_cornea['examnsn_cornea_l_sz']=='Micro'){ echo 'checked';}?> value="Micro" name="examnsn_cornea_l_sz" class="radio_toggle_crna1 opm7"> Micro 
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_sz']=='Macro'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_cornea['examnsn_cornea_l_sz']=='Macro'){ echo 'checked';}?> value="Macro" name="examnsn_cornea_l_sz" class="radio_toggle_crna1 opm7"> Macro 
								</label>
							</div>
							
						</div>
					</div>
					<div class="row toggle_cornea d-none mb-5">
						<div class="col-md-2">Shape</div>
						<div class="col-md-10">
						 	<div class="c-btn-group">
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_shp']=='Normal'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_cornea['examnsn_cornea_l_shp']=='Normal'){ echo 'checked';}?>  value="Normal" name="examnsn_cornea_l_shp" class="radio_toggle_crna2 opm7"> Normal 
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_shp']=='Irregular'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_cornea['examnsn_cornea_l_shp']=='Irregular'){ echo 'checked';}?>  name="examnsn_cornea_l_shp" value="Irregular" class="radio_toggle_crna2 opm7"> Irregular 
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_shp']=='Keratoconus'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_cornea['examnsn_cornea_l_shp']=='Keratoconus'){ echo 'checked';}?>  name="examnsn_cornea_l_shp" value="Keratoconus" class="radio_toggle_crna2 opm7"> Keratoconus 
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_shp']=='Keratoglobus'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_cornea['examnsn_cornea_l_shp']=='Keratoglobus'){ echo 'checked';}?>  name="examnsn_cornea_l_shp" value="Keratoglobus" class="radio_toggle_crna2 opm7"> Keratoglobus 
								</label>
							</div>
						 
					</div>
					</div>
					<div class="row toggle_cornea d-none mb-5">    
						<div class="col-md-2">Surface</div>
						<div class="col-md-10">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_srfs_nrml']=='Normal'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Normal" <?php if($exam_cornea['examnsn_cornea_l_srfs_nrml']=='Normal'){ echo 'checked';}?> name="examnsn_cornea_l_srfs_nrml" id="cornea_nrml"> Normal  
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_srfs_epcdft']=='Epi defect'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Epi defect" <?php if($exam_cornea['examnsn_cornea_l_srfs_epcdft']=='Epi defect'){ echo 'checked';}?> name="examnsn_cornea_l_srfs_epcdft" class="cornea_sur opm7"> Epi defect
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_srfs_think']=='Thinning'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Thinning" <?php if($exam_cornea['examnsn_cornea_l_srfs_think']=='Thinning'){ echo 'checked';}?> name="examnsn_cornea_l_srfs_think" class="cornea_sur opm7"> Thinning
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_srfs_scar']=='Scarring'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Scarring" <?php if($exam_cornea['examnsn_cornea_l_srfs_scar']=='Scarring'){ echo 'checked';}?> name="examnsn_cornea_l_srfs_scar" class="cornea_sur opm7"> Scarring
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_srfs_vascu']=='Vascularisation'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Vascularisation" <?php if($exam_cornea['examnsn_cornea_l_srfs_vascu']=='Vascularisation'){ echo 'checked';}?> name="examnsn_cornea_l_srfs_vascu" class="cornea_sur opm7"> Vascularisation
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_srfs_dgensn']=='Degeneration'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Degeneration" <?php if($exam_cornea['examnsn_cornea_l_srfs_dgensn']=='Degeneration'){ echo 'checked';}?> name="examnsn_cornea_l_srfs_dgensn" class="cornea_sur opm7"> Degeneration
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_srfs_dstph']=='Dystrophy'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Dystrophy" <?php if($exam_cornea['examnsn_cornea_l_srfs_dstph']=='Dystrophy'){ echo 'checked';}?> name="examnsn_cornea_l_srfs_dstph" class="cornea_sur opm7"> Dystrophy
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_srfs_fbd']=='Foreign body'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Foreign body" <?php if($exam_cornea['examnsn_cornea_l_srfs_fbd']=='Foreign body'){ echo 'checked';}?> name="examnsn_cornea_l_srfs_fbd" class="cornea_sur opm7"> Foreign body
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_srfs_tear']=='Tear'){ echo 'btn-info';}?>">
									<input type="checkbox" name="examnsn_cornea_l_srfs_tear" value="Tear" <?php if($exam_cornea['examnsn_cornea_l_srfs_tear']=='Tear'){ echo 'checked';}?> class="cornea_sur opm7"> Tear
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_srfs_kp']=='KP'){ echo 'btn-info';}?>">
									<input type="checkbox" name="examnsn_cornea_l_srfs_kp" value="KP" <?php if($exam_cornea['examnsn_cornea_l_srfs_kp']=='KP'){ echo 'checked';}?> class="cornea_sur opm7"> KP
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_srfs_opct']=='Opacity'){ echo 'btn-info';}?>">
									<input type="checkbox" name="examnsn_cornea_l_srfs_opct" value="Opacity" <?php if($exam_cornea['examnsn_cornea_l_srfs_opct']=='Opacity'){ echo 'checked';}?> class="cornea_sur opm7"> Opacity
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_l_srfs_ulcr']=='Ulcer'){ echo 'btn-info';}?>">
									<input type="checkbox" name="examnsn_cornea_l_srfs_ulcr" value="Ulcer" <?php if($exam_cornea['examnsn_cornea_l_srfs_ulcr']=='Ulcer'){ echo 'checked';}?> class="cornea_sur opm7"> Ulcer
								</label> 
							</div>
						</div>
					</div>
					<div class="row toggle_cornea d-none mb-5">
						<div class="col-md-3">Schirmer's Test 1</div>
						<div class="col-md-3 text-right">
							<span class=""><input type="text" name="examnsn_cornea_l_scht1_mm" value="<?php echo $exam_cornea['examnsn_cornea_l_scht1_mm'];?>" class="w-40px opm7"></span>
							<span class="">mm in </span>
						</div>
						<div class="col-md-3">
							<span class=""><input type="text" name="examnsn_cornea_l_scht1_min" value="<?php echo $exam_cornea['examnsn_cornea_l_scht1_min'];?>" class="w-40px opm7"></span>
							<span class="">min </span>
						</div>
					</div>
					<div class="row toggle_cornea d-none mb-5">
						<div class="col-md-3">Schirmer's Test 2</div>
						<div class="col-md-3 text-right">
							<span class=""><input type="text" name="examnsn_cornea_l_scht2_mm" value="<?php echo $exam_cornea['examnsn_cornea_l_scht2_mm'];?>" class="w-40px opm7"></span>
							<span class="">mm in </span>
						</div>
						<div class="col-md-3">
							<span class=""><input type="text" name="examnsn_cornea_l_scht2_min" value="<?php echo $exam_cornea['examnsn_cornea_l_scht2_min'];?>" class="w-40px opm7"></span>
							<span class="">min </span>
						</div>
					</div>
					<div class="row toggle_cornea d-none mb-5">
						<div class="col-md-2">Comments</div>
						<div class="col-md-10">
							<textarea name="examnsn_cornea_l_comm" class="form-control opm7"><?php echo $exam_cornea['examnsn_cornea_l_comm'];?></textarea>
						</div>
					</div>
				  <input type="hidden" name="examnsn_cornea_l_update" id="opm7" value="<?php echo $exam_cornea['examnsn_cornea_l_update'];?>">
				</div>
				<!-- ANTERIOR CHAMBER (AC) -->
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-12">
							<span class="btn_fill">ANTERIOR CHAMBER (AC)</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_ant_ch').toggle();$('.fa_toggle9').toggleClass('fa-minus');"><i class="fa_toggle9 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row mb-5 toggle_ant_ch d-none">
						<div class="col-md-3">Depth</div>
						<div class="col-md-5">
							<div class="btn-group">
								<label onclick="$('.abc_btn_input').hide()" class="btn btn-default btn-sm abc_btn  <?php if($exam_atrch['examnsn_ac_l_defth']=='Normal'){ echo 'btn-info';}?>"> Normal <input type="radio" class="examnsn_ac_l_defth opm9" name="examnsn_ac_l_defth" value="Normal" <?php if($exam_atrch['examnsn_ac_l_defth']=='Normal'){ echo 'checked';}?> ></label>
								<label onclick="$('#abc_btn_input1').show();$('#abc_btn_input2').hide();" class="btn btn-default btn-sm abc_btn <?php if($exam_atrch['examnsn_ac_l_defth']=='Shallow'){ echo 'btn-info';}?> ">Shallow <input type="radio" name="examnsn_ac_l_defth" value="Shallow"  <?php if($exam_atrch['examnsn_ac_l_defth']=='Shallow'){ echo 'checked';}?> class="examnsn_ac_l_defth opm9"></label>
								<label onclick="$('#abc_btn_input1').hide();$('#abc_btn_input2').show();" class="btn btn-default btn-sm abc_btn <?php if($exam_atrch['examnsn_ac_l_defth']=='Deep'){ echo 'btn-info';}?>">Deep <input type="radio" name="examnsn_ac_l_defth" value="Deep"  <?php if($exam_atrch['examnsn_ac_l_defth']=='Deep'){ echo 'checked';}?> class="examnsn_ac_l_defth opm9"></label>
							</div>
						</div>
						<div class="col-md-4 collapse abc_btn_input" id="abc_btn_input1">
							<input type="text" name="examnsn_ac_l_defth_txt1" value="<?php echo $exam_atrch['examnsn_ac_l_defth_txt1'];?>" class="form-control opm9">
						</div>
						<div class="col-md-4 collapse abc_btn_input"  id="abc_btn_input2">
							<input type="text" name="examnsn_ac_l_defth_txt2" value="<?php echo $exam_atrch['examnsn_ac_l_defth_txt2'];?>" class="form-control opm9">
						</div>
					</div>
					<div class="row mb-5 toggle_ant_ch d-none">
						<div class="col-md-3">Cells</div>
						<div class="col-md-5">														
							<div class="c-btn-group">
								<label class="c-button  <?php if($exam_atrch['examnsn_ac_l_cells']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" <?php if($exam_atrch['examnsn_ac_l_cells']=='No'){ echo 'checked';}?> name="examnsn_ac_l_cells" class="radio_toggle_antch1 opm9"> No 
								</label>
								<label class="c-button <?php if($exam_atrch['examnsn_ac_l_cells']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_atrch['examnsn_ac_l_cells']=='Yes'){ echo 'checked';}?> name="examnsn_ac_l_cells" class="radio_toggle_antch1 opm9"> Yes 
								</label>
							</div>
						</div>
						<div class="col-md-4 d-none" id="radio_toggle_antch1">  
							<input type="text" name="examnsn_ac_l_cells_txt" value="<?php echo $exam_atrch['examnsn_ac_l_cells_txt'];?>" class="form-control opm9">
						</div>
					 
					</div>
					<div class="row mb-5 toggle_ant_ch d-none">
						<div class="col-md-3">Flare</div>
						<div class="col-md-5">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_atrch['examnsn_ac_l_flar']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" <?php if($exam_atrch['examnsn_ac_l_flar']=='No'){ echo 'checked';}?> name="examnsn_ac_l_flar" class="radio_toggle_antch2 opm9"> No 
								</label>
								<label class="c-button <?php if($exam_atrch['examnsn_ac_l_flar']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_atrch['examnsn_ac_l_flar']=='Yes'){ echo 'checked';}?> name="examnsn_ac_l_flar" class="radio_toggle_antch2 opm9"> Yes 
								</label>
							</div>
						</div>
						<div class="col-md-4 d-none" id="radio_toggle_antch2">
							<input type="text" name="examnsn_ac_l_flar_txt" value="<?php echo $exam_atrch['examnsn_ac_l_flar_txt'];?>" class="form-control opm9">
						</div>
						
					</div>
					<div class="row mb-5 toggle_ant_ch d-none">
						<div class="col-md-3">Hyphema</div>
						<div class="col-md-5">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_atrch['examnsn_ac_l_hyfma']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" <?php if($exam_atrch['examnsn_ac_l_hyfma']=='No'){ echo 'checked';}?> name="examnsn_ac_l_hyfma" class="radio_toggle_antch3 opm9"> No 
								</label>
								<label class="c-button <?php if($exam_atrch['examnsn_ac_l_hyfma']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_atrch['examnsn_ac_l_hyfma']=='Yes'){ echo 'checked';}?> name="examnsn_ac_l_hyfma" class="radio_toggle_antch3 opm9"> Yes 
								</label>
							</div>
						</div>
						<div class="col-md-4 d-none" id="radio_toggle_antch3">
							<input type="text" name="examnsn_ac_l_hyfma_txt" value="<?php echo $exam_atrch['examnsn_ac_l_hyfma_txt'];?>" class="form-control opm9">
						</div>
						
						
					</div>
					<div class="row mb-5 toggle_ant_ch d-none">
						<div class="col-md-3">Hypopyon</div>
						<div class="col-md-5">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_atrch['examnsn_ac_l_hypn']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" <?php if($exam_atrch['examnsn_ac_l_hypn']=='No'){ echo 'checked';}?> name="examnsn_ac_l_hypn" class="radio_toggle_antch4 opm9"> No 
								</label>
								<label class="c-button <?php if($exam_atrch['examnsn_ac_l_hypn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_atrch['examnsn_ac_l_hypn']=='Yes'){ echo 'checked';}?> name="examnsn_ac_l_hypn" class="radio_toggle_antch4 opm9"> Yes 
								</label>
							</div>
						</div>
						<div class="col-md-4 d-none" id="radio_toggle_antch4">
							<input type="text" name="examnsn_ac_l_hypn_txt" value="<?php echo $exam_atrch['examnsn_ac_l_hypn_txt'];?>" class="form-control opm9">
						</div>
						
					</div>
					<div class="row mb-5 toggle_ant_ch d-none">
						<div class="col-md-3">Foreign Body</div>
						<div class="col-md-5">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_atrch['examnsn_ac_l_fbd']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" <?php if($exam_atrch['examnsn_ac_l_fbd']=='No'){ echo 'checked';}?> name="examnsn_ac_l_fbd" class="radio_toggle_antch5 opm9"> No 
								</label>
								<label class="c-button <?php if($exam_atrch['examnsn_ac_l_fbd']=='Yes'){ echo 'btn-info';}?> ">
									<input type="radio" value="Yes" <?php if($exam_atrch['examnsn_ac_l_fbd']=='Yes'){ echo 'checked';}?> name="examnsn_ac_l_fbd" class="radio_toggle_antch5 opm9"> Yes 
								</label>
							</div>
						</div>
						<div class="col-md-4 d-none" id="radio_toggle_antch5">
							<input type="text" name="examnsn_ac_l_fbd_txt"  value="<?php echo $exam_atrch['examnsn_ac_l_fbd_txt'];?>" class="form-control opm9">
						</div>
							
					</div>
					<div class="row mb-5 toggle_ant_ch d-none">
						<div class="col-md-3">Comments</div>
						<div class="col-md-9">
							<textarea name="examnsn_ac_l_comm" cols="30" rows="10" class="form-control opm9"> <?php echo $exam_atrch['examnsn_ac_l_comm'];?></textarea>
						</div>
					</div>
					<input type="hidden" name="examnsn_ac_l_update" id="opm9" value="<?php echo $exam_atrch['examnsn_ac_l_update'];?>">

				</div>
				<!-- PUPIL -->
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-12">
							<span class="btn_fill">PUPIL</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_pupil').toggle();$('.fa_toggle11').toggleClass('fa-minus');"><i class="fa_toggle11 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row mb-5 toggle_pupil d-none">
						<div class="col-md-4">Shape</div>
						<div class="col-md-8"> 
							<div class="btn-group">
								<label  class="btn shape btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_l_shp']=='Round'){ echo 'btn-info';}?>">Round <input type="radio" value="Round" name="examnsn_pupl_l_shp" class="opm11"  <?php if($exam_pupil['examnsn_pupl_l_shp']=='Round'){ echo 'checked';}?> ></label>
								<label  class="btn shape btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_l_shp']=='Eccentric'){ echo 'btn-info';}?>">Eccentric <input type="radio" value="Eccentric" name="examnsn_pupl_l_shp" class="opm11" <?php if($exam_pupil['examnsn_pupl_l_shp']=='Eccentric'){ echo 'checked';}?> ></label>
								<label  class="btn shape btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_l_shp']=='Irregular'){ echo 'btn-info';}?>">Irregular <input type="radio" value="Irregular" name="examnsn_pupl_l_shp" class="opm11" <?php if($exam_pupil['examnsn_pupl_l_shp']=='Irregular'){ echo 'checked';}?> ></label>
								<label  class="btn shape btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_l_shp']=='Oval'){ echo 'btn-info';}?>">Oval <input type="radio" value="Oval" name="examnsn_pupl_l_shp" class="opm11" <?php if($exam_pupil['examnsn_pupl_l_shp']=='Oval'){ echo 'checked';}?> ></label>
								<label  class="btn shape btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_l_shp']=='Polycoria'){ echo 'btn-info';}?>">Polycoria <input type="radio" value="Polycoria" name="examnsn_pupl_l_shp" class="opm11" <?php if($exam_pupil['examnsn_pupl_l_shp']=='Polycoria'){ echo 'checked';}?> ></label>
							</div>
					
						</div>
					</div>
					<div class="row mb-5 toggle_pupil d-none">
						<div class="col-md-4">Reaction to light Direct</div>
						<div class="col-md-8">
							<div class="btn-group">
								<label class="btn shape2 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_l_rld']=='Normal'){ echo 'btn-info';}?>">Normal <input type="radio" value="Normal" name="examnsn_pupl_l_rld" class="opm11" <?php if($exam_pupil['examnsn_pupl_l_rld']=='Normal'){ echo 'checked';}?>> </label>
								<label class="btn shape2 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_l_rld']=='Polycoria'){ echo 'btn-info';}?>">Sluggish <input type="radio" value="Sluggish" name="examnsn_pupl_l_rld" class="opm11" <?php if($exam_pupil['examnsn_pupl_l_rld']=='Polycoria'){ echo 'checked';}?>> </label>
								<label class="btn shape2 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_l_rld']=='Absent'){ echo 'btn-info';}?>">Absent <input type="radio" value="Absent" name="examnsn_pupl_l_rld" class="opm11" <?php if($exam_pupil['examnsn_pupl_l_rld']=='Absent'){ echo 'checked';}?>> </label>
							</div>
						
						</div>
					</div>
					<div class="row mb-5 toggle_pupil d-none">
						<div class="col-md-4">Reaction to light consensual</div>
						<div class="col-md-8">
							<div class="btn-group">
								<label class="btn shape3 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_l_rlc']=='Normal'){ echo 'btn-info';}?>">Normal <input type="radio" value="Normal" name="examnsn_pupl_l_rlc" class="opm11" <?php if($exam_pupil['examnsn_pupl_l_rlc']=='Normal'){ echo 'checked';}?>></label>
								<label class="btn shape3 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_l_rlc']=='Sluggish'){ echo 'btn-info';}?>">Sluggish <input type="radio" value="Sluggish" name="examnsn_pupl_l_rlc" class="opm11" <?php if($exam_pupil['examnsn_pupl_l_rlc']=='Sluggish'){ echo 'checked';}?>></label>
								<label class="btn shape3 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_l_rlc']=='Absent'){ echo 'btn-info';}?>">Absent <input type="radio" value="Absent" name="examnsn_pupl_l_rlc" class="opm11" <?php if($exam_pupil['examnsn_pupl_l_rlc']=='Absent'){ echo 'checked';}?>></label>
							</div>
					
						</div>
					</div>
					<div class="row mb-5 toggle_pupil d-none">
						<div class="col-md-4">Afferent pupillary defect (RAPD)</div>
						<div class="col-md-8">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_pupil['examnsn_pupl_l_apd']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" <?php if($exam_pupil['examnsn_pupl_l_apd']=='No'){ echo 'checked';}?> name="examnsn_pupl_l_apd" class="radio_toggle_apd1 opm11"> No 
								</label>
								<label class="c-button <?php if($exam_pupil['examnsn_pupl_l_apd']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" <?php if($exam_pupil['examnsn_pupl_l_apd']=='Yes'){ echo 'checked';}?> name="examnsn_pupl_l_apd" class="radio_toggle_apd1 opm11"> Yes 
								</label>
							</div>
						
						</div>
					</div>
					<div class="row mb-5 toggle_pupil d-none">
						<div class="col-md-4">Comments</div>
						<div class="col-md-8">
							<textarea name="examnsn_pupl_l_comm" class="form-control opm11"><?php echo $exam_pupil['examnsn_pupl_l_comm'];?></textarea>
						</div>
					</div>
					<input type="hidden" name="examnsn_pupl_l_update" id="opm11" value="<?php echo $exam_pupil['examnsn_pupl_l_update'];?>">
				</div>
				<!-- IRIS -->
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-12">
							<span class="btn_fill">IRIS</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_iris').toggle();$('.fa_toggle13').toggleClass('fa-minus');"><i class="fa_toggle13 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row mb-5 toggle_iris d-none"> 
						<div class="col-md-4">Shape</div>
						<div class="col-md-8">
						<div class="c-btn-group">
							<label class="c-button <?php if($exam_iris['examnsn_iris_l_shp']=='Normal'){ echo 'btn-info';}?>">
								<input type="radio" value="Normal" name="examnsn_iris_l_shp" class="radio_toggle_iris1 opm13" <?php if($exam_iris['examnsn_iris_l_shp']=='Normal'){ echo 'checked';}?>> Normal 
							</label>
							<label class="c-button <?php if($exam_iris['examnsn_iris_l_shp']=='Defects'){ echo 'btn-info';}?>">
								<input type="radio" value="Defects" name="examnsn_iris_l_shp" class="radio_toggle_iris1 opm13" <?php if($exam_iris['examnsn_iris_l_shp']=='Defects'){ echo 'checked';}?>> Defects 
							</label>
						</div>
						
						</div>
					</div>
					<div class="row mb-5 toggle_iris d-none">
						<div class="col-md-4">Neovascularisation (NVI)</div>
						<div class="col-md-8">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_iris['examnsn_iris_l_nvi']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_iris_l_nvi" class="radio_toggle_iris2 opm13" <?php if($exam_iris['examnsn_iris_l_nvi']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_iris['examnsn_iris_l_nvi']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_iris_l_nvi" class="radio_toggle_iris2 opm13" <?php if($exam_iris['examnsn_iris_l_nvi']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
					
						</div>
					</div>
					<div class="row mb-5 toggle_iris d-none">
						<div class="col-md-4">Synechiae</div>
						<div class="col-md-8">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_iris['examnsn_iris_l_synch']=='No'){ echo 'btn-info';}?>">
										<input type="radio" value="No" name="examnsn_iris_l_synch" class="radio_toggle_iris3 opm13" <?php if($exam_iris['examnsn_iris_l_synch']=='No'){ echo 'checked';}?>> No 
									</label>
									<label class="c-button <?php if($exam_iris['examnsn_iris_l_synch']=='Anterior'){ echo 'btn-info';}?>">
										<input type="radio" value="Anterior" name="examnsn_iris_l_synch" class="radio_toggle_iris3 opm13" <?php if($exam_iris['examnsn_iris_l_synch']=='Anterior'){ echo 'checked';}?>> Anterior 
									</label>
									<label class="c-button <?php if($exam_iris['examnsn_iris_l_synch']=='Posterior'){ echo 'btn-info';}?>">
										<input type="radio" value="Posterior" name="examnsn_iris_l_synch" class="radio_toggle_iris3 opm13" <?php if($exam_iris['examnsn_iris_l_synch']=='Posterior'){ echo 'checked';}?>> Posterior 
									</label>
							</div>

						</div>
					</div>
					<div class="row mb-5 toggle_iris d-none">
						<div class="col-md-4">Comments</div>
						<div class="col-md-8">
							<textarea name="examnsn_iris_l_comm" cols="30" rows="10" class="form-control opm13"><?php echo $exam_iris['examnsn_iris_l_comm'];?></textarea>
						</div>
					</div>
					<input type="hidden" name="examnsn_iris_l_update" id="opm13" value="<?php echo $exam_iris['examnsn_iris_l_update'];?>">
				</div>
				<!-- LENS -->
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-12">
							<span class="btn_fill">LENS</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_lens').toggle();$('.fa_toggle15').toggleClass('fa-minus');"><i class="fa_toggle15 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row mb-5 toggle_lens d-none">
						<div class="col-md-3">Nature </div>
						<div class="col-md-9">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_lens['examnsn_lens_l_ntr']=='Clear'){ echo 'btn-info';}?>">
									<input type="radio" value="Clear" name="examnsn_lens_l_ntr" class="radio_toggle_lens1 opm15" <?php if($exam_lens['examnsn_lens_l_ntr']=='Clear'){ echo 'checked';}?>> Clear 
								</label>
								<label class="c-button <?php if($exam_lens['examnsn_lens_l_ntr']=='Cataract'){ echo 'btn-info';}?>">
									<input type="radio" value="Cataract" name="examnsn_lens_l_ntr" class="radio_toggle_lens1 opm15" <?php if($exam_lens['examnsn_lens_l_ntr']=='Cataract'){ echo 'checked';}?>> Cataract 
								</label>
								<label class="c-button <?php if($exam_lens['examnsn_lens_l_ntr']=='Pseudophakia'){ echo 'btn-info';}?>">
									<input type="radio" value="Pseudophakia" name="examnsn_lens_l_ntr" class="radio_toggle_lens1 opm15" <?php if($exam_lens['examnsn_lens_l_ntr']=='Pseudophakia'){ echo 'checked';}?>> Pseudophakia 
								</label>
								<label class="c-button <?php if($exam_lens['examnsn_lens_l_ntr']=='Aphakia'){ echo 'btn-info';}?>">
									<input type="radio" value="Aphakia" name="examnsn_lens_l_ntr" class="radio_toggle_lens1 opm15" <?php if($exam_lens['examnsn_lens_l_ntr']=='Aphakia'){ echo 'checked';}?>> Aphakia 
								</label>
							</div>
						</div>
					</div>
				<!-- 	<div class="row mb-5 toggle_lens d-none">
					<div class="col-md-3">Position</div>
					<div class="col-md-9">
						<div class="c-btn-group">
							<label class="c-button <?php if($exam_lens['examnsn_lens_l_psn']=='Central'){ echo 'btn-info';}?>">
								<input type="radio" value="Central" name="examnsn_lens_l_psn" class="radio_toggle_lens2 opm15" <?php if($exam_lens['examnsn_lens_l_psn']=='Central'){ echo 'checked';}?>> Central 
							</label>
							<label class="c-button <?php if($exam_lens['examnsn_lens_l_psn']=='Decentered'){ echo 'btn-info';}?>">
								<input type="radio" value="Decentered" name="examnsn_lens_l_psn" class="radio_toggle_lens2 opm15" <?php if($exam_lens['examnsn_lens_l_psn']=='Decentered'){ echo 'checked';}?>> Decentered 
							</label>
							<label class="c-button <?php if($exam_lens['examnsn_lens_l_psn']=='Subluxated'){ echo 'btn-info';}?>">
								<input type="radio" value="Subluxated" name="examnsn_lens_l_psn" class="radio_toggle_lens2 opm15" <?php if($exam_lens['examnsn_lens_l_psn']=='Subluxated'){ echo 'checked';}?>> Subluxated 
							</label>
						</div>
				
					</div>
				</div>
				<div class="row mb-5 toggle_lens d-none">
					<div class="col-md-3">Size</div>
					<div class="col-md-9">
						<div class="c-btn-group">
							<label class="c-button <?php if($exam_lens['examnsn_lens_l_sz']=='Normal'){ echo 'btn-info';}?>">
								<input type="radio" value="Normal" name="examnsn_lens_l_sz" class="radio_toggle_lens3 opm15" <?php if($exam_lens['examnsn_lens_l_sz']=='Normal'){ echo 'checked';}?>> Normal
							</label>
							<label class="c-button <?php if($exam_lens['examnsn_lens_l_sz']=='Swollen'){ echo 'btn-info';}?>">
								<input type="radio" value="Swollen" name="examnsn_lens_l_sz" class="radio_toggle_lens3 opm15" <?php if($exam_lens['examnsn_lens_l_sz']=='Swollen'){ echo 'checked';}?>> Swollen 
							</label>
							<label class="c-button <?php if($exam_lens['examnsn_lens_l_sz']=='Absorbed'){ echo 'btn-info';}?>">
								<input type="radio" value="Absorbed" name="examnsn_lens_l_sz" class="radio_toggle_lens3 opm15" <?php if($exam_lens['examnsn_lens_l_sz']=='Absorbed'){ echo 'checked';}?>> Absorbed 
							</label>
							<label class="c-button <?php if($exam_lens['examnsn_lens_l_sz']=='Micro'){ echo 'btn-info';}?>">
								<input type="radio" value="Micro" name="examnsn_lens_l_sz" class="radio_toggle_lens3 opm15" <?php if($exam_lens['examnsn_lens_l_sz']=='Micro'){ echo 'checked';}?>> Micro 
							</label>
						</div>
				
					</div>
				</div> -->
					<div class="row mb-5 <?php if($exam_lens['examnsn_lens_l_ntr']=='Cataract'){ echo 'd-block';}else{ echo "d-none";}?>" id="nat_ap_l">
						<div class="col-md-3">LOCS Grading</div>
						<div class="col-md-9">
							<div class="row">
								<div class="col-sm-4">
									<div class="input-group">
										<span class="input-group-addon">NS</span>
										<select name="examnsn_lens_l_locsg_ns" class="form-control opm15">
										<option value="">Sel</option>
										<option value="1" <?php if($exam_lens['examnsn_lens_l_locsg_ns']=='1'){ echo 'selected';}?>>1</option>
										<option value="2" <?php if($exam_lens['examnsn_lens_l_locsg_ns']=='2'){ echo 'selected';}?>>2</option>
										<option value="3" <?php if($exam_lens['examnsn_lens_l_locsg_ns']=='3'){ echo 'selected';}?>>3</option>
										<option value="4" <?php if($exam_lens['examnsn_lens_l_locsg_ns']=='4'){ echo 'selected';}?>>4</option>
										<option value="5" <?php if($exam_lens['examnsn_lens_l_locsg_ns']=='5'){ echo 'selected';}?>>5</option>
										<option value="6" <?php if($exam_lens['examnsn_lens_l_locsg_ns']=='6'){ echo 'selected';}?>>6</option>
										</select>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										<span class="input-group-addon">C </span>
										<select name="examnsn_lens_l_locsg_c" class="form-control opm15">
											<option value="">Sel</option>
											<option value="1" <?php if($exam_lens['examnsn_lens_l_locsg_c']=='1'){ echo 'selected';}?>>1</option>
										<option value="2" <?php if($exam_lens['examnsn_lens_l_locsg_c']=='2'){ echo 'selected';}?>>2</option>
										<option value="3" <?php if($exam_lens['examnsn_lens_l_locsg_c']=='3'){ echo 'selected';}?>>3</option>
										<option value="4" <?php if($exam_lens['examnsn_lens_l_locsg_c']=='4'){ echo 'selected';}?>>4</option>
										<option value="5" <?php if($exam_lens['examnsn_lens_l_locsg_c']=='5'){ echo 'selected';}?>>5</option>
										<option value="6" <?php if($exam_lens['examnsn_lens_l_locsg_c']=='6'){ echo 'selected';}?>>6</option>
										</select>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										<span class="input-group-addon">P</span>
										<select name="examnsn_lens_l_locsg_p" class="form-control opm15">
											<option value="">Sel</option>
											<option value="1" <?php if($exam_lens['examnsn_lens_l_locsg_p']=='1'){ echo 'selected';}?>>1</option>
										<option value="2" <?php if($exam_lens['examnsn_lens_l_locsg_p']=='2'){ echo 'selected';}?>>2</option>
										<option value="3" <?php if($exam_lens['examnsn_lens_l_locsg_p']=='3'){ echo 'selected';}?>>3</option>
										<option value="4" <?php if($exam_lens['examnsn_lens_l_locsg_p']=='4'){ echo 'selected';}?>>4</option>
										<option value="5" <?php if($exam_lens['examnsn_lens_l_locsg_p']=='5'){ echo 'selected';}?>>5</option>
										<option value="6" <?php if($exam_lens['examnsn_lens_l_locsg_p']=='6'){ echo 'selected';}?>>6</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				
					<div class="row mb-5 toggle_lens d-none">
						<div class="col-md-3">Comments</div>
						<div class="col-md-9">
							<textarea name="examnsn_lens_l_comm" cols="30" rows="10" class="form-control opm15"><?php echo $exam_lens['examnsn_lens_l_comm'];?></textarea>
						</div>
					</div>
					<input type="hidden" name="examnsn_lens_l_update" id="opm15" value="<?php echo $exam_lens['examnsn_lens_l_update'];?>">

				</div>
				<!-- EXTRAOCULAR MOVEMENTS & SQUINT -->
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-12">
							<span class="btn_fill">EXTRAOCULAR MOVEMENTS & SQUINT</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_extra').toggle();$('.fa_toggle17').toggleClass('fa-minus');"><i class="fa_toggle17 fa fa-plus"></i></button>
						</div>
					</div> 
					<div class="row mb-5 toggle_extra d-none">
						<div class="col-md-4">Uniocular movements</div>
						<div class="col-md-8">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_extrmovs['examnsn_ems_l_unimv']=='Full'){ echo 'btn-info';}?>">
									<input type="radio" value="Full" name="examnsn_ems_l_unimv" class="btn_uni opm17" id="btn_uni_full" <?php if($exam_extrmovs['examnsn_ems_l_unimv']=='Full'){ echo 'checked';}?>> Full
								</label>
								<label class="c-button <?php if($exam_extrmovs['examnsn_ems_l_unimv']=='Restricted'){ echo 'btn-info';}?>">
									<input type="radio" value="Restricted" name="examnsn_ems_l_unimv" class="btn_uni opm17" <?php if($exam_extrmovs['examnsn_ems_l_unimv']=='Restricted'){ echo 'checked';}?>> Restricted 
								</label>
								<label class="c-button <?php if($exam_extrmovs['examnsn_ems_l_unimv']=='Overaction'){ echo 'btn-info';}?>">
									<input type="radio" value="Overaction" name="examnsn_ems_l_unimv" class="btn_uni opm17" <?php if($exam_extrmovs['examnsn_ems_l_unimv']=='Overaction'){ echo 'checked';}?>> Overaction 
								</label>
								<label class="c-button <?php if($exam_extrmovs['examnsn_ems_l_unimv']=='Limitation'){ echo 'btn-info';}?>">
									<input type="radio" value="Limitation" name="examnsn_ems_l_unimv" class="btn_uni opm17" <?php if($exam_extrmovs['examnsn_ems_l_unimv']=='Limitation'){ echo 'checked';}?>> Limitation 
								</label>
							</div>
					
						</div>
					</div>
					<div class="row mb-5 toggle_extra d-none">
						<div class="col-md-4">Binocular movements</div>
						<div class="col-md-8">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_extrmovs['examnsn_ems_l_bimv']=='Full'){ echo 'btn-info';}?>">
									<input type="radio" value="Full" name="examnsn_ems_l_bimv" class="radio_toggle_ems2 opm17" <?php if($exam_extrmovs['examnsn_ems_l_bimv']=='Full'){ echo 'checked';}?>> Full 
								</label>
								<label class="c-button <?php if($exam_extrmovs['examnsn_ems_l_bimv']=='Restricted'){ echo 'btn-info';}?>">
									<input type="radio" value="Restricted" name="examnsn_ems_l_bimv" class="radio_toggle_ems2 opm17" <?php if($exam_extrmovs['examnsn_ems_l_bimv']=='Restricted'){ echo 'checked';}?>> Restricted 
								</label>
							</div>
							
						</div>
					</div>
					<div class="row mb-5 toggle_extra d-none">
						<div class="col-md-4">Comments</div>
						<div class="col-md-8">
							<textarea name="examnsn_ems_l_comm" cols="30" rows="10" class="form-control opm17"><?php echo trim($exam_extrmovs['examnsn_ems_l_comm']);?></textarea>
						</div>
					</div>
					<div class="row mb-5 toggle_extra d-none">
						<div class="col-md-4">Prism</div>
						<div class="col-md-4">
							<div class="input-group">
								<input type="text" name="examnsn_ems_l_prsm" value="<?php echo $exam_extrmovs['examnsn_ems_l_prsm'];?>" class="form-control">
								<span class="input-group-addon">
									<i class="fa fa-caret-up"></i> 
								</span>
							</div>
						</div>
					</div>
					<section class="toggle_extra d-none">
						<div class="row mb-5">
							<div class="col-md-4">Squint</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button btn_squin <?php if($exam_extrmovs['examnsn_ems_l_sqnt']=='Tropia'){ echo 'btn-info';}?>"><input type="radio" value="Tropia" name="examnsn_ems_l_sqnt" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt']=='Tropia'){ echo 'checked';}?>>Tropia</label>
									<label class="c-button btn_squin <?php if($exam_extrmovs['examnsn_ems_l_sqnt']=='Phoria'){ echo 'btn-info';}?>"><input type="radio" value="Phoria" name="examnsn_ems_l_sqnt" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt']=='Phoria'){ echo 'checked';}?>>Phoria</label>
								</div>
						
							</div>
						</div>
						<div class="row mb-5 tropia <?php if($exam_extrmovs['examnsn_ems_l_sqnt']=='Tropia'){ echo 'd-block';} else{ echo 'd-none';}?>">
							<div class="col-md-4">Tropia</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button btn_tropia <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa']=='Horizontal'){ echo 'btn-info';}?>">Horizontal<input type="radio" value="Horizontal" name="examnsn_ems_l_sqnt_trpa" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa']=='Horizontal'){ echo 'checked';}?>></label>
									<label class="c-button btn_tropia <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa']=='Vertical'){ echo 'btn-info';}?>">Vertical<input type="radio" value="Vertical" name="examnsn_ems_l_sqnt_trpa" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa']=='Vertical'){ echo 'checked';}?>></label>
									<label class="c-button btn_tropia <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa']=='Paralytic'){ echo 'btn-info';}?>">Paralytic<input type="radio" value="Paralytic" name="examnsn_ems_l_sqnt_trpa" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa']=='Paralytic'){ echo 'checked';}?>></label>
								</div>
							
							</div>
						</div>
						<div class="row mb-5 phoria <?php if($exam_extrmovs['examnsn_ems_l_sqnt']=='Phoria'){ echo 'd-block';} else{ echo 'd-none';}?>">
							<div class="col-md-4">Phoria</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button btn_phoria <?php if($exam_extrmovs['examnsn_ems_l_sqnt_phoria']=='Esophoria(EP)'){ echo 'btn-info';}?>">Esophoria(EP)<input type="radio" value="Esophoria(EP)" name="examnsn_ems_l_sqnt_phoria" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_phoria']=='Esophoria(EP)'){ echo 'checked';}?>></label>
									<label class="c-button btn_phoria <?php if($exam_extrmovs['examnsn_ems_l_sqnt_phoria']=='Exophoria(X)'){ echo 'btn-info';}?>">Exophoria(X) <input type="radio" value="Exophoria(X)" name="examnsn_ems_l_sqnt_phoria" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_phoria']=='Exophoria(X)'){ echo 'checked';}?>></label>
								</div>
						
							</div>
						</div>
						<div class="row mb-5 horizontal <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa']=='Horizontal'){ echo 'd-block';} else{ echo 'd-none';}?>">
							<div class="col-md-4">Horizontal</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button btn_horizontal <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h']=='Esotropia(ET)'){ echo 'btn-info';}?>">Esotropia(ET)<input type="radio" name="examnsn_ems_l_sqnt_trpa_h" class="opm17" value="Esotropia(ET)" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h']=='Esotropia(ET)'){ echo 'checked';}?>></label>
									<label class="c-button btn_horizontal <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h']=='Exotropia(XT)'){ echo 'btn-info';}?>">Exotropia(XT)<input type="radio" name="examnsn_ems_l_sqnt_trpa_h" class="opm17" value="Exotropia(XT)" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h']=='Exotropia(XT)'){ echo 'checked';}?>></label>
								</div>							
							</div>
						</div>
						<div class="row mb-5 esotropia_ET <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h']=='Esotropia(ET)'){ echo 'd-block';}else{ echo 'd-none';} ?>">
							<div class="col-md-4">Esotropia(ET)</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button btn_esotropia_ET <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_et']=='ACS'){ echo 'btn-info';}?>">ACS<input type="radio" value="ACS" name="examnsn_ems_l_sqnt_trpa_h_et" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_et']=='ACS'){ echo 'checked';}?>></label>
									<label class="c-button btn_esotropia_ET <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_et']=='Sensory'){ echo 'btn-info';}?>">Sensory<input type="radio" value="Sensory" name="examnsn_ems_l_sqnt_trpa_h_et" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_et']=='Sensory'){ echo 'checked';}?>></label>
									<label class="c-button btn_esotropia_ET <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_et']=='Infantile'){ echo 'btn-info';}?>">Infantile<input type="radio" value="Infantile" name="examnsn_ems_l_sqnt_trpa_h_et" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_et']=='Infantile'){ echo 'checked';}?>></label>
									<label class="c-button btn_esotropia_ET <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_et']=='ICS'){ echo 'btn-info';}?>">ICS <input type="radio" value="ICS" name="examnsn_ems_l_sqnt_trpa_h_et" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_et']=='ICS'){ echo 'checked';}?>></label>
								</div>							
							</div>
						</div>
						<div class="row mb-5 exotropia_XT <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h']=='Esotropia(XT)'){ echo 'd-block';}else{ echo 'd-none';} ?>">
							<div class="col-md-4">Esotropia(XT)</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button btn_exotropia_XT <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_xt']=='ADS'){ echo 'btn-info';}?>">ADS<input type="radio" value="ADS" name="examnsn_ems_l_sqnt_trpa_h_xt" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_xt']=='ADS'){ echo 'checked';}?>></label>
									<label class="c-button btn_exotropia_XT <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_xt']=='IDS'){ echo 'btn-info';}?>">IDS<input type="radio" value="IDS" name="examnsn_ems_l_sqnt_trpa_h_xt" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_xt']=='IDS'){ echo 'checked';}?>></label>
									<label class="c-button btn_exotropia_XT <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_xt']=='Sensory'){ echo 'btn-info';}?>">Sensory<input type="radio" value="Sensory" name="examnsn_ems_l_sqnt_trpa_h_xt" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_h_xt']=='Sensory'){ echo 'checked';}?>></label>
								</div>
					
							</div>
						</div>
						<div class="row mb-5 vertical <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa']=='Vertical'){ echo 'd-block';} else{ echo 'd-none';}?>">
							<div class="col-md-4">Vertical</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button btn_vertical" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_v']=='Hypertropia(HT)'){ echo 'btn-info';}?>>Hypertropia(HT)<input type="radio" value="Hypertropia(HT)" name="examnsn_ems_l_sqnt_trpa_v" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_v']=='Hypertropia(HT)'){ echo 'checked';}?>></label>
									<label class="c-button btn_vertical <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_v']=='Hypotropia(HoT)'){ echo 'btn-info';}?>">Hypotropia(HoT)<input type="radio" value="Hypotropia(HoT)" name="examnsn_ems_l_sqnt_trpa_v" class="opm17" <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa_v']=='Hypotropia(HoT)'){ echo 'checked';}?>></label>
								</div>
							
							</div>
						</div>
						<div class="row mb-5 paralytic <?php if($exam_extrmovs['examnsn_ems_l_sqnt_trpa']=='Paralytic'){ echo 'd-block';} else{ echo 'd-none';}?>">
							<div class="col-md-4">Paralytic</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button <?php if($exam_extrmovs['examnsn_ems_l_para_mr']=='MR'){ echo 'btn-info';}?>"> 
										<input type="checkbox" value="MR" name="examnsn_ems_l_para_mr" class="btn_paralytic opm17" <?php if($exam_extrmovs['examnsn_ems_l_para_mr']=='MR'){ echo 'checked';}?> > MR
									</label>
									<label class="c-button <?php if($exam_extrmovs['examnsn_ems_l_para_lr']=='LR'){ echo 'btn-info';}?>">
										<input type="checkbox" value="LR" name="examnsn_ems_l_para_lr" class="btn_paralytic opm17" <?php if($exam_extrmovs['examnsn_ems_l_para_lr']=='LR'){ echo 'checked';}?> > LR
									</label>
									<label class="c-button <?php if($exam_extrmovs['examnsn_ems_l_para_so']=='SO'){ echo 'btn-info';}?>">
										<input type="checkbox" value="SO" name="examnsn_ems_l_para_so" class="btn_paralytic opm17" <?php if($exam_extrmovs['examnsn_ems_l_para_so']=='SO'){ echo 'checked';}?> > SO
									</label>
									<label class="c-button <?php if($exam_extrmovs['examnsn_ems_l_para_sr']=='SR'){ echo 'btn-info';}?> ">
										<input type="checkbox" value="SR" name="examnsn_ems_l_para_sr" class="btn_paralytic opm17" <?php if($exam_extrmovs['examnsn_ems_l_para_sr']=='SR'){ echo 'checked';}?> > SR
									</label>
									<label class="c-button <?php if($exam_extrmovs['examnsn_ems_l_para_ir']=='IR'){ echo 'btn-info';}?>">
										<input type="checkbox" value="IR" name="examnsn_ems_l_para_ir" class="btn_paralytic opm17" <?php if($exam_extrmovs['examnsn_ems_l_para_ir']=='IR'){ echo 'checked';}?> > IR
									</label>
									<label class="c-button  <?php if($exam_extrmovs['examnsn_ems_l_para_io']=='IO'){ echo 'btn-info';}?>">
										<input type="checkbox" value="IO" name="examnsn_ems_l_para_io" class="btn_paralytic opm17" <?php if($exam_extrmovs['examnsn_ems_l_para_io']=='IO'){ echo 'checked';}?> > IO
									</label>
								</div>
							</div>
						</div>
					</section>
					<input type="hidden" name="examnsn_ems_l_update" id="opm17" value="<?php echo $exam_extrmovs['examnsn_ems_l_update'];?>">

				</div>
				<!-- INTRAOCULAR PRESSURE (IOP) -->
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-12">
							<span class="btn_fill">INTRAOCULAR PRESSURE (IOP)</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_iop').toggle();$('.fa_toggle19').toggleClass('fa-minus');"><i class="fa_toggle19 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row mb-5 toggle_iop d-none">
						<div class="col-md-2">Value</div>
						<div class="col-md-4 slidecontainer">
							<input type="range" min="0" max="100" class="slider" value="<?php if($exam_intrprsr['examnsn_iop_l_value'] !=''){echo $exam_intrprsr['examnsn_iop_l_value'];}else {echo '0';} ?>" id="examnsn_myRange_l">
						</div>
						<div class="col-md-4">
							<div class="input-group">
								<input type="text" name="examnsn_iop_l_value" id="examnsn_iop_l_value" class="form-control" value="<?php echo $exam_intrprsr['examnsn_iop_l_value'];?>">
								<span class="input-group-addon">mmHG</span>
							</div>
						</div>
						<div class="col-md-2">
							<input type="text" name="examnsn_iop_l_time" value="<?php echo $exam_intrprsr['examnsn_iop_l_time'];?>" class="form-control datepicker3">
						</div>
					</div>
					<div class="row mb-5 toggle_iop d-none">
						<div class="col-md-2">Method</div>
						<div class="col-md-4">
							<select name="examnsn_iop_l_method" class="form-control">
								<option value="">Select</option>
								<option value="AT" <?php if($exam_intrprsr['examnsn_iop_l_method']=='AT'){ echo 'selected';}?> >AT</option>
								<option value="NCT" <?php if($exam_intrprsr['examnsn_iop_l_method']=='NCT'){ echo 'selected';}?> >NCT</option>
								<option value="Schiotz" <?php if($exam_intrprsr['examnsn_iop_l_method']=='Schiotz'){ echo 'selected';}?> >Schiotz</option>
								<option value="Perkins" <?php if($exam_intrprsr['examnsn_iop_l_method']=='Perkins'){ echo 'selected';}?> >Perkins</option>
							</select>
						</div>
					</div>
					<div class="row mb-5 toggle_iop d-none ">
						<div class="col-md-2">Comments</div>
						<div class="col-md-10">
							<textarea name="examnsn_iop_l_comm" cols="30" rows="10" class="form-control"><?php echo $exam_intrprsr['examnsn_iop_l_comm'];?></textarea>
						</div>
					</div>
				</div>
				<!-- GONIOSCOPY -->
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-12">
							<span class="btn_fill">GONIOSCOPY</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_gonio').toggle();$('.fa_toggle21').toggleClass('fa-minus');"><i class="fa_toggle21 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row mb-5 toggle_gonio d-none">
						<div class="col-md-12">
							<table class="table table-bordered">
								<tr>
									<td style="width:100px;">Superior: <i class="fa fa-arrow-right"></i></td>
									<td>
										<div class="row">
											<div class="col-sm-4">
												<select name="examnsn_gonispy_l_sup_d1" class="form-control opm21">
													<option value="">Select</option>
													<option value="0" <?php if($exam_gnscp['examnsn_gonispy_l_sup_d1']=='0'){ echo 'selected';}?>>0</option>
													<option value="1" <?php if($exam_gnscp['examnsn_gonispy_l_sup_d1']=='1'){ echo 'selected';}?>>1</option>
													<option value="2" <?php if($exam_gnscp['examnsn_gonispy_l_sup_d1']=='2'){ echo 'selected';}?>>2</option>
													<option value="3" <?php if($exam_gnscp['examnsn_gonispy_l_sup_d1']=='3'){ echo 'selected';}?>>3</option>
													<option value="4" <?php if($exam_gnscp['examnsn_gonispy_l_sup_d1']=='4'){ echo 'selected';}?>>4</option>
												</select>
											</div>
											<div class="col-sm-4">
												<select name="examnsn_gonispy_l_sup_d2"  class="form-control opm21">
													<option value="">Select</option>
													<option value="SS" <?php if($exam_gnscp['examnsn_gonispy_l_sup_d2']=='SS'){ echo 'selected';}?>>SS</option>
													<option value="TM-Ant" <?php if($exam_gnscp['examnsn_gonispy_l_sup_d2']=='TM-Ant'){ echo 'selected';}?>>TM-Ant</option>
													<option value="TM-Post" <?php if($exam_gnscp['examnsn_gonispy_l_sup_d2']=='TM-Post'){ echo 'selected';}?>>TM-Post</option>
													<option value="CB" <?php if($exam_gnscp['examnsn_gonispy_l_sup_d2']=='CB'){ echo 'selected';}?>>CB</option>
													<option value="SL" <?php if($exam_gnscp['examnsn_gonispy_l_sup_d2']=='SL'){ echo 'selected';}?>>SL</option>
												</select>
											</div>
											<div class="col-sm-4">
												<select name="examnsn_gonispy_l_sup_d3" class="form-control opm21">
													<option value="">Select</option>
													<option value="NVI" <?php if($exam_gnscp['examnsn_gonispy_l_sup_d3']=='NVI'){ echo 'selected';}?>>NVI</option>
													<option value="PAS" <?php if($exam_gnscp['examnsn_gonispy_l_sup_d3']=='PAS'){ echo 'selected';}?>>PAS</option>
												</select>
											</div>
										</div>
									</td>
									<td style="width:100px;"></td>
								</tr>
								<tr>
									<td>
										<div class="row">
											<div class="col-md-12 mb-5">Temporal:</div>
											<div class="col-md-12 mb-5">
												<select name="examnsn_gonispy_l_tmprl_d1" class="form-control opm21">
													<option value="">Select</option>
													<option value="0" <?php if($exam_gnscp['examnsn_gonispy_l_tmprl_d1']=='0'){ echo 'selected';}?>>0</option>
													<option value="1" <?php if($exam_gnscp['examnsn_gonispy_l_tmprl_d1']=='1'){ echo 'selected';}?>>1</option>
													<option value="2" <?php if($exam_gnscp['examnsn_gonispy_l_tmprl_d1']=='2'){ echo 'selected';}?>>2</option>
													<option value="3" <?php if($exam_gnscp['examnsn_gonispy_l_tmprl_d1']=='3'){ echo 'selected';}?>>3</option>
													<option value="4" <?php if($exam_gnscp['examnsn_gonispy_l_tmprl_d1']=='4'){ echo 'selected';}?>>4</option>
												</select>
											</div>
											<div class="col-md-12 mb-5">
												<select name="examnsn_gonispy_l_tmprl_d2"  class="form-control opm21">
													<option value="">Select</option>
													<option value="SS" <?php if($exam_gnscp['examnsn_gonispy_l_tmprl_d2']=='SS'){ echo 'selected';}?>>SS</option>
													<option value="TM-Ant" <?php if($exam_gnscp['examnsn_gonispy_l_tmprl_d2']=='TM-Ant'){ echo 'selected';}?>>TM-Ant</option>
													<option value="TM-Post" <?php if($exam_gnscp['examnsn_gonispy_l_tmprl_d2']=='TM-Post'){ echo 'selected';}?>>TM-Post</option>
													<option value="CB" <?php if($exam_gnscp['examnsn_gonispy_l_tmprl_d2']=='CB'){ echo 'selected';}?>>CB</option>
													<option value="SL" <?php if($exam_gnscp['examnsn_gonispy_l_tmprl_d2']=='SL'){ echo 'selected';}?>>SL</option>
												</select>
											</div>
											<div class="col-md-12 mb-5">
												<select name="examnsn_gonispy_l_tmprl_d3" class="form-control opm21">
													<option value="">Select</option>
													<option value="NVI" <?php if($exam_gnscp['examnsn_gonispy_l_tmprl_d3']=='NVI'){ echo 'selected';}?>>NVI</option>
													<option value="PAS" <?php if($exam_gnscp['examnsn_gonispy_l_tmprl_d3']=='PAS'){ echo 'selected';}?>>PAS</option>
												</select>
											</div>
										</div>
									</td>
									<td align="center" valign="middle">
										<img src="<?php echo base_url('assets/images/eye_cross.png');?>" alt="a" class="img-fluid">
									</td>
									<td align="center">
										<div class="row">
											<div class="col-md-12 mb-5">Nasal:</div>
											<div class="col-md-12 mb-5">
												<select name="examnsn_gonispy_l_nsl_d1" class="form-control opm21">
													<option value="">Select</option>
													<option value="0" <?php if($exam_gnscp['examnsn_gonispy_l_nsl_d1']=='0'){ echo 'selected';}?>>0</option>
													<option value="1" <?php if($exam_gnscp['examnsn_gonispy_l_nsl_d1']=='1'){ echo 'selected';}?>>1</option>
													<option value="2" <?php if($exam_gnscp['examnsn_gonispy_l_nsl_d1']=='2'){ echo 'selected';}?>>2</option>
													<option value="3" <?php if($exam_gnscp['examnsn_gonispy_l_nsl_d1']=='3'){ echo 'selected';}?>>3</option>
													<option value="4" <?php if($exam_gnscp['examnsn_gonispy_l_nsl_d1']=='4'){ echo 'selected';}?>>4</option>
												</select>
											</div>
											<div class="col-md-12 mb-5">
												<select name="examnsn_gonispy_l_nsl_d2"  class="form-control opm21">
													<option value="">Select</option>
													<option value="SS" <?php if($exam_gnscp['examnsn_gonispy_l_nsl_d2']=='SS'){ echo 'selected';}?>>SS</option>
													<option value="TM-Ant" <?php if($exam_gnscp['examnsn_gonispy_l_nsl_d2']=='TM-Ant'){ echo 'selected';}?>>TM-Ant</option>
													<option value="TM-Post" <?php if($exam_gnscp['examnsn_gonispy_l_nsl_d2']=='TM-Post'){ echo 'selected';}?>>TM-Post</option>
													<option value="CB" <?php if($exam_gnscp['examnsn_gonispy_l_nsl_d2']=='CB'){ echo 'selected';}?>>CB</option>
													<option value="SL" <?php if($exam_gnscp['examnsn_gonispy_l_nsl_d2']=='SL'){ echo 'selected';}?>>SL</option>
												</select>
											</div>
											<div class="col-md-12 mb-5">
												<select name="examnsn_gonispy_l_nsl_d3"  class="form-control opm21">
													<option value="">Select</option>
													<option value="NVI" <?php if($exam_gnscp['examnsn_gonispy_l_nsl_d3']=='NVI'){ echo 'selected';}?>>NVI</option>
													<option value="PAS" <?php if($exam_gnscp['examnsn_gonispy_l_nsl_d3']=='PAS'){ echo 'selected';}?>>PAS</option>
												</select>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td width="20%">Inferior: <i class="fa fa-arrow-right"></i></td>
									<td width="60% ">
										<div class="row">
											<div class="col-sm-4">
												<select name="examnsn_gonispy_l_infr_d1"  class="form-control opm21">
													<option value="">Select</option>
													<option value="0" <?php if($exam_gnscp['examnsn_gonispy_l_infr_d1']=='0'){ echo 'selected';}?>>0</option>
													<option value="1" <?php if($exam_gnscp['examnsn_gonispy_l_infr_d1']=='1'){ echo 'selected';}?>>1</option>
													<option value="2" <?php if($exam_gnscp['examnsn_gonispy_l_infr_d1']=='2'){ echo 'selected';}?>>2</option>
													<option value="3" <?php if($exam_gnscp['examnsn_gonispy_l_infr_d1']=='3'){ echo 'selected';}?>>3</option>
													<option value="4" <?php if($exam_gnscp['examnsn_gonispy_l_infr_d1']=='4'){ echo 'selected';}?>>4</option>
												</select>
											</div>
											<div class="col-sm-4">
												<select name="examnsn_gonispy_l_infr_d2" class="form-control opm21">
													<option value="">Select</option>
													<option value="SS" <?php if($exam_gnscp['examnsn_gonispy_l_infr_d2']=='SS'){ echo 'selected';}?>>SS</option>
													<option value="TM-Ant" <?php if($exam_gnscp['examnsn_gonispy_l_infr_d2']=='TM-Ant'){ echo 'selected';}?>>TM-Ant</option>
													<option value="TM-Post" <?php if($exam_gnscp['examnsn_gonispy_l_infr_d2']=='TM-Post'){ echo 'selected';}?>>TM-Post</option>
													<option value="CB" <?php if($exam_gnscp['examnsn_gonispy_l_infr_d2']=='CB'){ echo 'selected';}?>>CB</option>
													<option value="SL" <?php if($exam_gnscp['examnsn_gonispy_l_infr_d2']=='SL'){ echo 'selected';}?>>SL</option>
												</select>
											</div>
											<div class="col-sm-4">
												<select name="examnsn_gonispy_l_infr_d3" class="form-control opm21">
													<option value="">Select</option>
													<option value="NVI" <?php if($exam_gnscp['examnsn_gonispy_l_infr_d3']=='NVI'){ echo 'selected';}?>>NVI</option>
													<option value="PAS" <?php if($exam_gnscp['examnsn_gonispy_l_infr_d3']=='PAS'){ echo 'selected';}?>>PAS</option>
												</select>
											</div>
										</div>
									</td>
									<td width="20%"></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="row mb-5 toggle_gonio d-none ">
						<div class="col-md-2">Comments</div>
						<div class="col-md-10">
							<textarea name="examnsn_gonispy_l_comm" cols="30" rows="10" class="form-control opm21"><?php echo $exam_gnscp['examnsn_gonispy_l_comm'];?></textarea>
						</div>
					</div>
					<input type="hidden" name="examnsn_gonispy_l_update" id="opm21" value="<?php echo $exam_gnscp['examnsn_gonispy_l_update'];?>">

				</div>
				<!-- FUNDUS -->
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-10">
							<span class="btn_fill">FUNDUS</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_fundus').toggle();$('.fa_toggle23').toggleClass('fa-minus');"><i class="fa_toggle23 fa fa-plus"></i></button>
						</div>
						<div class="col-md-2 c-btn-group">
							<label class="c-button <?php if($exam_fundus['toggle_fundus_nrml']=='Normal'){ echo 'btn-info';}?>">
								<input type="checkbox" value="Normal" name="toggle_fundus_nrml" class="toggle_fundus_nrml opm23" <?php if($exam_fundus['toggle_fundus_nrml']=='Normal'){ echo 'checked';}?>> Normal
							</label>
							
						</div>     
					</div>

					<div class="row mb-5 toggle_fundus d-none">
						<div class="col-md-3">Dialet</div>
						<div class="col-md-4">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_fundus['examnsn_fundus_l_dilate']=='Undilated'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_fundus['examnsn_fundus_l_dilate']=='Undilated'){ echo 'selected';}?> name="examnsn_fundus_l_dilate" value="Undilated" class="radio_toggle_l_dialet opm23"> Undilated 
								</label>
								<label class="c-button <?php if($exam_fundus['examnsn_fundus_l_dilate']=='Dilated'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_fundus['examnsn_fundus_l_dilate']=='Dilated'){ echo 'selected';}?> name="examnsn_fundus_l_dilate" value="Dilated" class="radio_toggle_l_dialet opm23"> Dilated 
								</label>
							</div>
						</div>
					</div>

					<div class="row mb-5 toggle_fundus d-none">
						<div class="col-md-3">Media</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_l_mda" id="examnsn_fundus_l_mda" class="form-control opm23">
								<option value="">Select</option>
								<option value="Clear"  <?php if($exam_fundus['examnsn_fundus_l_mda']=='Clear'){ echo 'selected';}?>>Clear</option>
								<option value="Hazy" <?php if($exam_fundus['examnsn_fundus_l_mda']=='Hazy'){ echo 'selected';}?>>Hazy</option>
							</select>
						</div>
						<div class="col-md-5">
							<input type="text" name="examnsn_fundus_l_mda_comm" value="<?php echo $exam_fundus['examnsn_fundus_l_mda_comm'];?>" class="form-control opm23">
						</div>
					</div>
					<div class="row mb-5 toggle_fundus d-none">
						<div class="col-md-3">PVD</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_l_pvd" id="examnsn_fundus_l_pvd" class="form-control opm23">
								<option value="">Select</option>
								<option value="Absent" <?php if($exam_fundus['examnsn_fundus_l_pvd']=='Absent'){ echo 'selected';}?>>Absent</option>
								<option value="Present" <?php if($exam_fundus['examnsn_fundus_l_pvd']=='Present'){ echo 'selected';}?>>Present</option>
							</select>
						</div>
						
					</div>
					<div class="row mb-5 toggle_fundus d-none">
						<div class="col-md-3">Optic Disc Size</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_l_ods" id="examnsn_fundus_l_ods" class="form-control opm23">
								<option value="">Select</option>
								<option value="Small" <?php if($exam_fundus['examnsn_fundus_l_ods']=='Small'){ echo 'selected';}?>>Small</option>
								<option value="Medium" <?php if($exam_fundus['examnsn_fundus_l_ods']=='Medium'){ echo 'selected';}?>>Medium</option>
								<option value="Large" <?php if($exam_fundus['examnsn_fundus_l_ods']=='Large'){ echo 'selected';}?>>Large</option>
							</select>
						</div>
						
					</div>
					<div class="row mb-5 toggle_fundus d-none">
						<div class="col-md-3">Cup/Disc Ratio (C/D)</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_l_cdr" id="examnsn_fundus_l_cdr" class="form-control opm23">
								<option value="">Select</option>
								<option value="0.1" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.1'){ echo 'selected';}?>>0.1</option>
								<option value="0.15" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.15'){ echo 'selected';}?>>0.15</option>
								<option value="0.2" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.2'){ echo 'selected';}?>>0.2</option>
								<option value="0.25" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.25'){ echo 'selected';}?>>0.25</option>
								<option value="0.3" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.3'){ echo 'selected';}?>>0.3</option>
								<option value="0.35" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.35'){ echo 'selected';}?>>0.35</option>
								<option value="0.4" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.4'){ echo 'selected';}?>>0.4</option>
								<option value="0.45" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.45'){ echo 'selected';}?>>0.45</option>
								<option value="0.5" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.5'){ echo 'selected';}?>>0.5</option>
								<option value="0.55" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.55'){ echo 'selected';}?>>0.55</option>
								<option value="0.6" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.6'){ echo 'selected';}?>>0.6</option>
								<option value="0.65" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.65'){ echo 'selected';}?>>0.65</option>
								<option value="0.7" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.7'){ echo 'selected';}?>>0.7</option>
								<option value="0.75" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.75'){ echo 'selected';}?>>0.75</option>
								<option value="0.8" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.8'){ echo 'selected';}?>>0.8</option>
								<option value="0.85" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.85'){ echo 'selected';}?>>0.85</option>
								<option value="0.9" <?php if($exam_fundus['examnsn_fundus_l_cdr']=='0.9'){ echo 'selected';}?>>0.9</option>
							</select>
						</div>
						<div class="col-md-5">
							<input type="text" name="examnsn_fundus_l_cdr_txt" value="<?php echo $exam_fundus['examnsn_fundus_l_cdr_txt'];?>" class="form-control opm23">
						</div>
					</div>
					<div class="row mb-5 toggle_fundus d-none">
						<div class="col-md-3">Optic Disc</div>
						<div class="col-md-9">							
							<input type="text" name="examnsn_fundus_l_opdisc" value="<?php echo $exam_fundus['examnsn_fundus_l_opdisc'];?>" id="examnsn_fundus_l_opdisc" class="form-control opm23">
						</div>
					</div>
					<div class="row mb-5 toggle_fundus d-none">
						<div class="col-md-3">Blood Vessels</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_l_bldvls" class="form-control opm23">
								<option value="">Select</option>
								<option value="Sclerosed" <?php if($exam_fundus['examnsn_fundus_l_bldvls']=='Sclerosed'){ echo 'selected';}?>>Sclerosed</option>
								<option value="Sheathed" <?php if($exam_fundus['examnsn_fundus_l_bldvls']=='Sheathed'){ echo 'selected';}?>>Sheathed</option>
								<option value="Tortuous" <?php if($exam_fundus['examnsn_fundus_l_bldvls']=='Tortuous'){ echo 'selected';}?>>Tortuous</option>
								<option value="Attenuated" <?php if($exam_fundus['examnsn_fundus_l_bldvls']=='Attenuated'){ echo 'selected';}?>>Attenuated</option>
								<option value="Engorged" <?php if($exam_fundus['examnsn_fundus_l_bldvls']=='Engorged'){ echo 'selected';}?>>Engorged</option>
							</select>
						</div>
						<div class="col-md-5">
							<input type="text" name="examnsn_fundus_l_bldvls_txt" id="examnsn_fundus_l_bldvls_txt" value="<?php echo $exam_fundus['examnsn_fundus_l_bldvls_txt'];?>" class="form-control opm23">
						</div>
					</div>
					<div class="row mb-5 toggle_fundus d-none">
						<div class="col-md-3">Macula</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_l_mcla"  class="form-control opm23">
								<option value="">Select</option>
								<option value="Clear" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Clear'){ echo 'selected';}?>>Clear</option>
								<option value="Hard Exudates" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Hard Exudates'){ echo 'selected';}?>>Hard Exudates</option>
								<option value="Microaneurysm" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Microaneurysm'){ echo 'selected';}?>>Microaneurysm</option>
								<option value="Hemorrhages" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Hemorrhages'){ echo 'selected';}?>>Hemorrhages</option>
								<option value="Subretinal Hemorrhages" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Subretinal Hemorrhages'){ echo 'selected';}?>>Subretinal Hemorrhages</option>
								<option value="Scar" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Scar'){ echo 'selected';}?>>Scar</option>
								<option value="Atrophic area" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Atrophic area'){ echo 'selected';}?>>Atrophic area</option>
								<option value="Pigment Alteration" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Pigment Alteration'){ echo 'selected';}?>>Pigment Alteration</option>
								<option value="Drusen" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Drusen'){ echo 'selected';}?>>Drusen</option>
								<option value="Subretinal Fluid" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Subretinal Fluid'){ echo 'selected';}?>>Subretinal Fluid</option>
								<option value="Cystoid" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Cystoid'){ echo 'selected';}?>>Cystoid</option>
								<option value="Thickening" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Thickening'){ echo 'selected';}?>>Thickening</option>
								<option value="Whitening" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Whitening'){ echo 'selected';}?>>Whitening</option>
								<option value="Cotton Wool Spots" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Cotton Wool Spots'){ echo 'selected';}?>>Cotton Wool Spots</option>
								<option value="Pigment Epithelial Detachment" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Pigment Epithelial Detachment'){ echo 'selected';}?>>Pigment Epithelial Detachment</option>
								<option value="Altered Foveal Reflex" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Altered Foveal Reflex'){ echo 'selected';}?>>Altered Foveal Reflex</option>
								<option value="Vascular Abnormalities" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Vascular Abnormalities'){ echo 'selected';}?>>Vascular Abnormalities</option>
								<option value="Pigmentary Changes" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Pigmentary Changes'){ echo 'selected';}?>>Pigmentary Changes</option>
								<option value="Epiretinal Membrane" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Epiretinal Membrane'){ echo 'selected';}?>>Epiretinal Membrane</option>
								<option value="FTMH" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='FTMH'){ echo 'selected';}?>>FTMH</option>
								<option value="Lamellar Hole" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Lamellar Hole'){ echo 'selected';}?>>Lamellar Hole</option>
								<option value="ILM Striae" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='ILM Striae'){ echo 'selected';}?>>ILM Striae</option>
								<option value="White Dots" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='White Dots'){ echo 'selected';}?>>White Dots</option>
								<option value="Yellow Flecks" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Yellow Flecks'){ echo 'selected';}?>>Yellow Flecks</option>
								<option value="Cherry Red Spot" <?php if($exam_fundus['examnsn_fundus_l_mcla']=='Cherry Red Spot'){ echo 'selected';}?>>Cherry Red Spot</option>
							</select>
						</div>
						<div class="col-md-5">
							<input type="text" name="examnsn_fundus_l_mcla_txt" value="<?php echo $exam_fundus['examnsn_fundus_l_mcla_txt'];?>" id="examnsn_fundus_l_mcla_txt" class="form-control opm23">
						</div>
					</div>
					<div class="row mb-5 toggle_fundus d-none">
						<div class="col-md-3">Vitreous</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_l_vtrs" class="form-control opm23">
								<option value="">Select</option>
								<option value="Clear" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Clear'){ echo 'selected';}?>>Clear</option>
								<option value="Cells" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Cells'){ echo 'selected';}?>>Cells</option>
								<option value="Haze" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Haze'){ echo 'selected';}?>>Haze</option>
								<option value="Exudates" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Exudates'){ echo 'selected';}?>>Exudates</option>
								<option value="Active" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Active'){ echo 'selected';}?>>Active</option>
								<option value="Inactive" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Inactive'){ echo 'selected';}?>>Inactive</option>
								<option value="Pars Plana Exudate" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Pars Plana Exudate'){ echo 'selected';}?>>Pars Plana Exudate</option>
								<option value="Snow Banking" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Snow Banking'){ echo 'selected';}?>>Snow Banking</option>
								<option value="Snow Ball Opacities" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Snow Ball Opacities'){ echo 'selected';}?>>Snow Ball Opacities</option>
								<option value="Hemorrhage-Fresh" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Hemorrhage-Fresh'){ echo 'selected';}?>>Hemorrhage-Fresh</option>
								<option value="Hemorrhage-Old" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Hemorrhage-Old'){ echo 'selected';}?>>Hemorrhage-Old</option>
								<option value="Vitreous Bands" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Vitreous Bands'){ echo 'selected';}?>>Vitreous Bands</option>
								<option value="Optically Empty Vitreous" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Optically Empty Vitreous'){ echo 'selected';}?>>Optically Empty Vitreous</option>
								<option value="Vitreouschisis" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Vitreouschisis'){ echo 'selected';}?>>Vitreouschisis</option>
								<option value="Vitreous Floaters" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Vitreous Floaters'){ echo 'selected';}?>>Vitreous Floaters</option>
								<option value="Vitreous Condensation" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Vitreous Condensation'){ echo 'selected';}?>>Vitreous Condensation</option>
								<option value="Posterior Vitreous Detachment" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Posterior Vitreous Detachment'){ echo 'selected';}?>>Posterior Vitreous Detachment</option>
								<option value="Weiss Ring" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Weiss Ring'){ echo 'selected';}?>>Weiss Ring</option>
								<option value="Retrohyaloid Haem" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Retrohyaloid Haem'){ echo 'selected';}?>>Retrohyaloid Haem</option>
								<option value="Silicone Oil in Situ" <?php if($exam_fundus['examnsn_fundus_l_vtrs']=='Silicone Oil in Situ'){ echo 'selected';}?>>Silicone Oil in Situ</option>
							</select>
						</div>
						<div class="col-md-5">
							<input type="text" name="examnsn_fundus_l_vtrs_txt" value="<?php echo $exam_fundus['examnsn_fundus_l_vtrs_txt'];?>" class="form-control opm23">
						</div>
					</div>
					<div class="row mb-5 toggle_fundus d-none">
						<div class="col-md-3">Retinal Detachment </div>
						<div class="col-md-4">
							<select name="examnsn_fundus_l_rtnldcht" class="form-control opm23">
								<option value="">Select</option>
								<option value="Partial" <?php if($exam_fundus['examnsn_fundus_l_rtnldcht']=='Partial'){ echo 'selected';}?> >Partial</option>
								<option value="Sub-total" <?php if($exam_fundus['examnsn_fundus_l_rtnldcht']=='Sub-total'){ echo 'selected';}?> >Sub-total</option>
								<option value="Total" <?php if($exam_fundus['examnsn_fundus_l_rtnldcht']=='Total'){ echo 'selected';}?> >Total</option>
								<option value="SubClinical" <?php if($exam_fundus['examnsn_fundus_l_rtnldcht']=='SubClinical'){ echo 'selected';}?> >SubClinical</option>
								<option value="Rhegmatogenous" <?php if($exam_fundus['examnsn_fundus_l_rtnldcht']=='Rhegmatogenous'){ echo 'selected';}?> >Rhegmatogenous</option>
								<option value="Exudative" <?php if($exam_fundus['examnsn_fundus_l_rtnldcht']=='Exudative'){ echo 'selected';}?> >Exudative</option>
								<option value="Tractional" <?php if($exam_fundus['examnsn_fundus_l_rtnldcht']=='Tractional'){ echo 'selected';}?> >Tractional</option>
								<option value="Combined" <?php if($exam_fundus['examnsn_fundus_l_rtnldcht']=='Combined'){ echo 'selected';}?> >Combined</option>
								<option value="Multiple Breaks" <?php if($exam_fundus['examnsn_fundus_l_rtnldcht']=='Multiple Breaks'){ echo 'selected';}?> >Multiple Breaks</option>
							</select>
						</div>
						<div class="col-md-5">
							<input type="text" name="examnsn_fundus_l_rtnldcht_txt" value="<?php echo $exam_fundus['examnsn_fundus_l_rtnldcht_txt'];?>" class="form-control opm23">
						</div>
					</div>
					<div class="row mb-5 toggle_fundus d-none">
						<div class="col-md-3">Peripheral Lesions</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_l_perlsn"  class="form-control opm23">
								<option value="">Select</option>
								<option value="Atrophic Hole" <?php if($exam_fundus['examnsn_fundus_l_perlsn']=='Atrophic Hole'){ echo 'selected';}?>>Atrophic Hole</option>
								<option value="Horse Shoe Tear" <?php if($exam_fundus['examnsn_fundus_l_perlsn']=='Horse Shoe Tear'){ echo 'selected';}?>>Horse Shoe Tear</option>
								<option value="Operculated Break" <?php if($exam_fundus['examnsn_fundus_l_perlsn']=='Operculated Break'){ echo 'selected';}?>>Operculated Break</option>
								<option value="Dialysis" <?php if($exam_fundus['examnsn_fundus_l_perlsn']=='Dialysis'){ echo 'selected';}?>>Dialysis</option>
								<option value="Giant Retinal Tear" <?php if($exam_fundus['examnsn_fundus_l_perlsn']=='Giant Retinal Tear'){ echo 'selected';}?>>Giant Retinal Tear</option>
								<option value="Multiple Breaks" <?php if($exam_fundus['examnsn_fundus_l_perlsn']=='Multiple Breaks'){ echo 'selected';}?>>Multiple Breaks</option>
								<option value="White Without Pressure Areas" <?php if($exam_fundus['examnsn_fundus_l_perlsn']=='White Without Pressure Areas'){ echo 'selected';}?>>White Without Pressure Areas</option>
							</select>
						</div>
						<div class="col-md-5">
							<input type="text" name="examnsn_fundus_l_perlsn_txt"  value="<?php echo $exam_fundus['examnsn_fundus_l_perlsn_txt'];?>"class="form-control opm23">
						</div>
					</div>
					<div class="row mb-5 toggle_fundus d-none">
						<div class="col-md-3"></div>
						<div class="col-md-5">
							<input type="text" name="examnsn_fundus_l_fnds" value="<?php echo $exam_fundus['examnsn_fundus_l_fnds'];?>" id="examnsn_fundus_l_fnds" class="form-control opm23">
						</div>
					</div>
					<div class="row mb-5 toggle_fundus d-none">
						<div class="col-md-3">Comments</div>
						<div class="col-md-9">
							<textarea  name="examnsn_fundus_l_comm" class="form-control opm23"><?php echo $exam_fundus['examnsn_fundus_l_comm'];?></textarea>
						</div>
					</div>
					<input type="hidden" name="examnsn_fundus_l_update" id="opm23" value="<?php echo $exam_fundus['examnsn_fundus_l_update'];?>">

				</div>
			</div>
		</div>
			<div class="col-md-6">
			<!-- ------- Right side --------- -->
			<div class=" panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-10 text-center">L/OS</div> 
						<div class="col-xs-2 c-btn-group">
							<label class="c-button <?php if($exam_general['examnsn_rod_normal']=='Normal'){ echo 'btn-info';}?>">
								<input type="checkbox" onclick="$(this).parent().toggleClass('btn-info');" <?php if($exam_general['examnsn_rod_normal']=='Normal'){ echo 'checked';}?> value="Normal" name="examnsn_rod_normal"> Normal
							</label>
						</div>
					</div>
				</div>
				<!-- APPEARANCEffgh -->
				<div class="panel-body border-bottom">
					<div class="row">
						<div class="col-md-12">
							<span class="btn_fill">APPEARANCE</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_appearance_ri8').toggle();$('.fa_toggle1_ri8').toggleClass('fa-minus');"><i class="fa_toggle1_ri8 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row toggle_appearance_ri8 d-none">
						<div class="col-md-3">
							<label class="small">Phthisis Bulbi</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_r_phthisis']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_aprnc_r_phthisis" class="radio_toggle_ph_bul_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_phthisis']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_r_phthisis']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_aprnc_r_phthisis" class="radio_toggle_ph_bul_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_phthisis']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						
						</div>
						<div class="col-md-3">
							<label class="small">Anophthalmos</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_r_anthms']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_aprnc_r_anthms" class="radio_toggle_anop_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_anthms']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_r_anthms']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_aprnc_r_anthms" class="radio_toggle_anop_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_anthms']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						
						</div>
						<div class="col-md-3">
							<label class="small">Micropththalmos</label> <br>
							<div class="c-btn-group">
								<label class="c-button  <?php if($exam_aprnc['examnsn_aprnc_r_mcrthms']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_aprnc_r_mcrthms" class="radio_toggle_microp_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_mcrthms']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_r_mcrthms']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_aprnc_r_mcrthms" class="radio_toggle_microp_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_mcrthms']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						</div>
						<div class="col-md-3">
							<label class="small">Artificial</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_r_artfsl']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_aprnc_r_artfsl" class="radio_toggle_artifi_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_artfsl']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_r_artfsl']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_aprnc_r_artfsl" class="radio_toggle_artifi_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_artfsl']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						</div>
					</div>
					<div class="row toggle_appearance_ri8 d-none mb-5">
						<div class="col-md-3">
							<label class="small">Proptosis</label><br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_r_prptsis']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_aprnc_r_prptsis" class="radio_toggle_propt_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_prptsis']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_r_prptsis']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_aprnc_r_prptsis" class="radio_toggle_propt_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_prptsis']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
					
						</div>
						<div class="col-md-3">
							<label class="small">Dystopia</label><br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_r_dsptpa']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_aprnc_r_dsptpa" class="radio_toggle_dystop_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_dsptpa']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_r_dsptpa']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_aprnc_r_dsptpa" class="radio_toggle_dystop_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_dsptpa']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						
						</div>
						<div class="col-md-3">
							<label class="small">Injured</label><br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_r_injrd']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_aprnc_r_injrd" class="radio_toggle_injur_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_injrd']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_r_injrd']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_aprnc_r_injrd" class="radio_toggle_injur_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_injrd']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
							
						</div>
						<div class="col-md-3">
							<label class="small">Swollen</label><br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_r_swln']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_aprnc_r_swln" class="radio_toggle_swollen_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_swln']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_aprnc['examnsn_aprnc_r_swln']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_aprnc_r_swln" class="radio_toggle_swollen_ri8 opm2" <?php if($exam_aprnc['examnsn_aprnc_r_swln']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						
						</div>
					</div>
					<div class="row toggle_appearance_ri8 d-none">
						<div class="col-md-3">
							<label class="small">Comments</label>
						</div>
						<div class="col-md-9">
							<textarea name="examnsn_aprnc_r_comm" class="form-control opm2"><?php echo $exam_aprnc['examnsn_aprnc_r_comm']; ?></textarea>
						</div>
					</div>
					<input type="hidden" name="examnsn_aprnc_r_update" id="opm2" value="<?php echo $exam_aprnc['examnsn_aprnc_r_update'];?>">

				</div>
				<!-- APPENDAGES -->
				<div class="panel-body border-bottom">
					<div class="row">
						<div class="col-md-12">
							<span class="btn_fill">APPENDAGES</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_appen_ri8').toggle();$('.fa_toggle3_ri8').toggleClass('fa-minus');"><i class="fa_toggle3_ri8 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row d-none toggle_appen_ri8">
						<div class="col-md-3"></div>
						<div class="col-md-7 text-center pl-0">
							<div class="c-btn-group">
								<label class="c-button  <?php if($exam_apnds['examnsn_apndgs_r_eyelids']=='Eyelids'){ echo 'btn-info';}?>">
									<input type="checkbox" class="toggle_appen_r" value="Eyelids" name="examnsn_apndgs_r_eyelids" onclick="$('.toggle_appen_a_ri8').toggle();" <?php if($exam_apnds['examnsn_apndgs_r_eyelids']=='Eyelids'){ echo 'checked';}?>> Eyelids
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelshs']=='Eyelashes'){ echo 'btn-info';}?>">
									<input type="checkbox" class="toggle_appen_r" value="Eyelashes" name="examnsn_apndgs_r_eyelshs" onclick="$('.toggle_appen_a_b_ri8').toggle();" <?php if($exam_apnds['examnsn_apndgs_r_eyelshs']=='Eyelashes'){ echo 'checked';}?>> Eyelashes
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_lcmlc']=='Lacrimal sac'){ echo 'btn-info';}?>">
									<input type="checkbox" class="toggle_appen_r" value="Lacrimal sac" name="examnsn_apndgs_r_lcmlc" onclick="$('.toggle_appen_a_c_ri8').toggle();" <?php if($exam_apnds['examnsn_apndgs_r_lcmlc']=='Lacrimal sac'){ echo 'checked';}?>> Lacrimal sac
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_syrn']=='Syringing'){ echo 'btn-info';}?>">
									<input type="checkbox" class="toggle_appen_r" value="Syringing" name="examnsn_apndgs_r_syrn" onclick="$('.toggle_appen_a_d_ri8').toggle();" <?php if($exam_apnds['examnsn_apndgs_r_syrn']=='Syringing'){ echo 'checked';}?>> Syringing
								</label>
							</div>

						</div>
						<div class="col-md-2"></div>
					</div>
					<div class="row d-none toggle_appen_a_ri8">
						<div class="col-sm-3">
							<div class="small">Chalazion</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelids_chzn']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_apndgs_r_eyelids_chzn" class="radio_toggle_chalaz_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelids_chzn']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelids_chzn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_apndgs_r_eyelids_chzn" class="radio_toggle_chalaz_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelids_chzn']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
				
						</div>
						<div class="col-sm-3">
							<div class="small">Ptosis</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelids_ptss']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_apndgs_r_eyelids_ptss" class="radio_toggle_ptosis_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelids_ptss']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelids_ptss']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_apndgs_r_eyelids_ptss" class="radio_toggle_ptosis_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelids_ptss']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						
						</div>
						<div class="col-sm-3">
							<div class="small">Swelling</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelids_swln']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_apndgs_r_eyelids_swln" class="radio_toggle_swellening_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelids_swln']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelids_swln']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_apndgs_r_eyelids_swln" class="radio_toggle_swellening_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelids_swln']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
					
						</div>
						<div class="col-sm-3">
							<div class="small">Entropion</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelids_intrpn']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_apndgs_r_eyelids_intrpn" class="radio_toggle_enteropion_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelids_intrpn']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelids_intrpn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_apndgs_r_eyelids_intrpn" class="radio_toggle_enteropion_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelids_intrpn']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						
						</div>
					</div>
					<div class="row d-none toggle_appen_a_ri8">
						<div class="col-sm-3">
							<div class="small">Ectropion</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelids_ectrpn']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_apndgs_r_eyelids_ectrpn" class="radio_toggle_ectropion_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelids_ectrpn']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelids_ectrpn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_apndgs_r_eyelids_ectrpn" class="radio_toggle_ectropion_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelids_ectrpn']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						
						</div>
						<div class="col-sm-3">
							<div class="small">Mass</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelids_mass']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_apndgs_r_eyelids_mass" class="radio_toggle_mass_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelids_mass']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelids_mass']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_apndgs_r_eyelids_mass" class="radio_toggle_mass_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelids_mass']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						
						</div>
						<div class="col-sm-3">
							<div class="small">Meibomitis</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelids_mbts']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_apndgs_r_eyelids_mbts" class="radio_toggle_meibomities_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelids_mbts']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelids_mbts']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_apndgs_r_eyelids_mbts" class="radio_toggle_meibomities_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelids_mbts']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
					
						</div>
					</div>
					
					<div class="row d-none toggle_appen_a_b_ri8">
						<div class="col-sm-3">
							<div class="small">Trichiasis</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelshs_tchs']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_apndgs_r_eyelshs_tchs" class="radio_toggle_trichi_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelshs_tchs']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelshs_tchs']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_apndgs_r_eyelshs_tchs" class="radio_toggle_trichi_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelshs_tchs']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
					
						</div>
						<div class="col-sm-3">
							<div class="small">Dystrichiasis</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelshs_dtchs']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_apndgs_r_eyelshs_dtchs" class="radio_toggle_dystrich_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelshs_dtchs']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_eyelshs_dtchs']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_apndgs_r_eyelshs_dtchs" class="radio_toggle_dystrich_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_eyelshs_dtchs']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						
						</div>
					</div>			
					<div class="row d-none toggle_appen_a_c_ri8">
						<div class="col-sm-3">
							<div class="small">Swelling</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_lcmlc_swln']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_apndgs_r_lcmlc_swln" class="radio_toggle_swellening2_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_lcmlc_swln']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_lcmlc_swln']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_apndgs_r_lcmlc_swln" class="radio_toggle_swellening2_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_lcmlc_swln']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
				
						</div>
						<div class="col-sm-3">
							<div class="small">Regurgitation</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_lcmlc_regusn']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_apndgs_r_lcmlc_regusn" class="radio_toggle_regur_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_lcmlc_regusn']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_lcmlc_regusn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_apndgs_r_lcmlc_regusn" class="radio_toggle_regur_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_lcmlc_regusn']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
					
						</div>
					</div>			
					<div class="row d-none toggle_appen_a_d_ri8">
						<div class="col-sm-4">
							<div class="small">Syringing</div>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_syrn_syrn']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_apndgs_r_syrn_syrn" class="radio_toggle_syring_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_syrn_syrn']=='No'){ echo 'checked';}?>> Patent 
								</label>
								<label class="c-button <?php if($exam_apnds['examnsn_apndgs_r_syrn_syrn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_apndgs_r_syrn_syrn" class="radio_toggle_syring_ri8 opm4" <?php if($exam_apnds['examnsn_apndgs_r_syrn_syrn']=='Yes'){ echo 'checked';}?>> Blocked
								</label>
							</div>
						</div>
					</div>		
					<div class="row d-none toggle_appen_ri8 m-t-3">
						<div class="col-sm-3">
							<div class="small">Comments</div>						
						</div>
						<div class="col-sm-9">
							<textarea name="examnsn_apndgs_r_comm" class="form-control opm4"><?php echo $exam_apnds['examnsn_apndgs_r_comm'];?></textarea>
						</div>
					</div>
				<input type="hidden" name="examnsn_apndgs_r_update" id="opm4" value="<?php echo $exam_apnds['examnsn_apndgs_r_update'];?>">
				</div>
				<!-- CONJUNCTIVA -->
				<div class="panel-body border-bottom">
					<div class="row">
						<div class="col-md-12">
							<span class="btn_fill">CONJUNCTIVA</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_conj_ri8').toggle();$('.fa_toggle5_ri8').toggleClass('fa-minus');"><i class="fa_toggle5_ri8 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row d-none toggle_conj_ri8">
						<div class="col-md-3">
							<label class="small">Congestion</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_consn']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_conjtv_r_consn" class="radio_toggle_cong1_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_consn']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_consn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" onclick="$('.toggle_conj_conges_r').toggle();" name="examnsn_conjtv_r_consn" class="radio_toggle_cong1_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_consn']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						
						</div>
						<div class="col-md-3">
							<label class="small">Tear</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_tear']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_conjtv_r_tear" class="radio_toggle_cong2_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_tear']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_tear']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_conjtv_r_tear" class="radio_toggle_cong2_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_tear']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						</div>
						<div class="col-md-3">
							<label class="small">Conjuctival Bleb</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_cbleb']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_conjtv_r_cbleb" class="radio_toggle_cong3_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_cbleb']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_cbleb']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_conjtv_r_cbleb" class="radio_toggle_cong3_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_cbleb']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						</div>
					</div>

					<div class="row d-none toggle_conj_conges_r">
						<div class="col-md-12">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_consn_sub']=='Generalized'){ echo 'btn-info';}?>">
									<input type="radio" value="Generalized" name="examnsn_conjtv_r_consn_sub" class="radio_toggle_cong_sr opm6" <?php if($exam_conjtv['examnsn_conjtv_r_consn_sub']=='Generalized'){ echo 'checked';}?>> Generalized 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_consn_sub']=='Localized'){ echo 'btn-info';}?>">
									<input type="radio" value="Localized" name="examnsn_conjtv_r_consn_sub" class="radio_toggle_cong_sr opm6" <?php if($exam_conjtv['examnsn_conjtv_r_consn_sub']=='Localized'){ echo 'checked';}?>> Localized 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_consn_sub']=='Ciliary'){ echo 'btn-info';}?>">
									<input type="radio" value="Ciliary" name="examnsn_conjtv_r_consn_sub" class="radio_toggle_cong_sr opm6"  <?php if($exam_conjtv['examnsn_conjtv_r_consn_sub']=='Ciliary'){ echo 'checked';}?>> Ciliary 
								</label>
							</div>							
						</div>
					</div>
					<div class="row d-none toggle_conj_ri8">
						<div class="col-md-6">
							<label class="small">SubConjunctival Haemorrhage</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_sbhrg']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_conjtv_r_sbhrg" class="radio_toggle_cong4_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_sbhrg']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_sbhrg']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_conjtv_r_sbhrg" class="radio_toggle_cong4_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_sbhrg']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						</div>
						<div class="col-md-3">
							<label class="small">Foreign Body</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_fbd']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_conjtv_r_fbd" class="radio_toggle_cong5_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_fbd']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_fbd']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_conjtv_r_fbd" class="radio_toggle_cong5_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_fbd']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>				
						</div>
					</div>
					<div class="row d-none toggle_conj_ri8">
						<div class="col-md-3">
							<label class="small">Follicles</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_flcls']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_conjtv_r_flcls" class="radio_toggle_cong6_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_flcls']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_flcls']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_conjtv_r_flcls" class="radio_toggle_cong6_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_flcls']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
			
						</div>
						<div class="col-md-3">
							<label class="small">Papillae</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_paple']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_conjtv_r_paple" class="radio_toggle_cong7_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_paple']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_paple']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_conjtv_r_paple" class="radio_toggle_cong7_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_paple']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						</div>
						<div class="col-md-3">
							<label class="small">Pinguecula</label> <br>
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_pngcla']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_conjtv_r_pngcla" class="radio_toggle_cong8_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_pngcla']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_pngcla']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_conjtv_r_pngcla" class="radio_toggle_cong8_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_pngcla']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						
						</div>
					</div>
					<div class="row mb-5 d-none toggle_conj_ri8">
						<div class="col-md-3">
							<label class="small">Pterygium</label> <br>							
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_ptrgm']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_conjtv_r_ptrgm" class="radio_toggle_cong9_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_ptrgm']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_ptrgm']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_conjtv_r_ptrgm" class="radio_toggle_cong9_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_ptrgm']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						
						</div>
						<div class="col-md-3">
							<label class="small">Phlycten</label> <br>							
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_phctn']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_conjtv_r_phctn" class="radio_toggle_cong10_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_phctn']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_phctn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_conjtv_r_phctn" class="radio_toggle_cong10_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_phctn']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						</div>
						<div class="col-md-3">
							<label class="small">Discharge</label> <br>							
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_dseg']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_conjtv_r_dseg" class="radio_toggle_cong11_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_dseg']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_conjtv['examnsn_conjtv_r_dseg']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_conjtv_r_dseg" class="radio_toggle_cong11_ri8 opm6" <?php if($exam_conjtv['examnsn_conjtv_r_dseg']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						</div>
					</div>
					<div class="row d-none toggle_conj_ri8">
						<div class="col-md-3">
							<label class="small">Comments</label> 
						</div>
						<div class="col-md-9">
							<textarea name="examnsn_conjtv_r_comm" class="form-control opm6"><?php echo $exam_conjtv['examnsn_conjtv_r_comm'];?></textarea>
						</div>
					</div>
					<input type="hidden" name="examnsn_conjtv_r_update" id="opm6" value="<?php echo $exam_conjtv['examnsn_conjtv_r_update'];?>">
				</div>
				<!-- CORNEA-->
				<div class="panel-body border-bottom">
					<div class="row">
						<div class="col-md-12">
							<span class="btn_fill">CORNEA</span>
							<label class="btn_fill" onclick="$('.toggle_cornea_ri8').toggle();$('.fa_toggle7_ri8').toggleClass('fa-minus');"><i class="fa_toggle7_ri8 fa fa-plus"></i></label>
						</div>
					</div>
					<div class="row toggle_cornea_ri8 d-none mb-5">
						<div class="col-md-2">Size</div>
						<div class="col-md-10">					
						  <div class="c-btn-group">
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_sz']=='Normal'){ echo 'btn-info';}?>">
									<input type="radio" value="Normal" name="examnsn_cornea_r_sz" class="radio_toggle_crna1_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_sz']=='Normal'){ echo 'checked';}?>> Normal 
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_sz']=='Micro'){ echo 'btn-info';}?>">
									<input type="radio" value="Micro" name="examnsn_cornea_r_sz" class="radio_toggle_crna1_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_sz']=='Micro'){ echo 'checked';}?>> Micro 
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_sz']=='Macro'){ echo 'btn-info';}?>">
									<input type="radio" value="Macro" name="examnsn_cornea_r_sz" class="radio_toggle_crna1_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_sz']=='Macro'){ echo 'checked';}?>> Macro 
								</label>
							</div>
						</div>
					</div>
					<div class="row toggle_cornea_ri8 d-none mb-5">
						<div class="col-md-2">Shape</div>
						<div class="col-md-10">
						 	<div class="c-btn-group">
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_shp']=='Normal'){ echo 'btn-info';}?>">
									<input type="radio" value="Normal" name="examnsn_cornea_r_shp" class="radio_toggle_crna2_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_shp']=='Normal'){ echo 'checked';}?>> Normal 
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_shp']=='Irregular'){ echo 'btn-info';}?>">
									<input type="radio" value="Irregular" name="examnsn_cornea_r_shp" class="radio_toggle_crna2_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_shp']=='Irregular'){ echo 'checked';}?>> Irregular 
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_shp']=='Keratoconus'){ echo 'btn-info';}?>">
									<input type="radio" value="Keratoconus" name="examnsn_cornea_r_shp" class="radio_toggle_crna2_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_shp']=='Keratoconus'){ echo 'checked';}?>> Keratoconus 
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_shp']=='Keratoglobus'){ echo 'btn-info';}?>">
									<input type="radio" value="Keratoglobus" name="examnsn_cornea_r_shp" class="radio_toggle_crna2_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_shp']=='Keratoglobus'){ echo 'checked';}?>> Keratoglobus 
								</label>
							</div> 
					</div>
					</div>
					<div class="row toggle_cornea_ri8 d-none mb-5">    
						<div class="col-md-2">Surface</div>
						<div class="col-md-10">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_srfs_nrml']=='Normal'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Normal" name="examnsn_cornea_r_srfs_nrml" id="cornea_nrml_ri8" <?php if($exam_cornea['examnsn_cornea_r_srfs_nrml']=='Normal'){ echo 'checked';}?>> Normal  
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_srfs_epcdft']=='Epi defect'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Epi defect" name="examnsn_cornea_r_srfs_epcdft" class="cornea_sur_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_srfs_epcdft']=='Epi defect'){ echo 'checked';}?>> Epi defect
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_srfs_think']=='Thinning'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Thinning" name="examnsn_cornea_r_srfs_think" class="cornea_sur_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_srfs_think']=='Thinning'){ echo 'checked';}?>> Thinning
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_srfs_scar']=='Scarring'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Scarring" name="examnsn_cornea_r_srfs_scar" class="cornea_sur_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_srfs_scar']=='Scarring'){ echo 'checked';}?>> Scarring
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_srfs_vascu']=='Vascularisation'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Vascularisation" name="examnsn_cornea_r_srfs_vascu" class="cornea_sur_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_srfs_vascu']=='Vascularisation'){ echo 'checked';}?>> Vascularisation
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_srfs_dgensn']=='Degeneration'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Degeneration" name="examnsn_cornea_r_srfs_dgensn" class="cornea_sur_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_srfs_dgensn']=='Degeneration'){ echo 'checked';}?>> Degeneration
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_srfs_dstph']=='Dystrophy'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Dystrophy" name="examnsn_cornea_r_srfs_dstph" class="cornea_sur_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_srfs_dstph']=='Dystrophy'){ echo 'checked';}?>> Dystrophy
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_srfs_fbd']=='Foreign body'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Foreign body" name="examnsn_cornea_r_srfs_fbd" class="cornea_sur_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_srfs_fbd']=='Foreign body'){ echo 'checked';}?>> Foreign body
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_srfs_tear']=='Tear'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Tear" name="examnsn_cornea_r_srfs_tear" class="cornea_sur_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_srfs_tear']=='Tear'){ echo 'checked';}?>> Tear
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_srfs_kp']=='KP'){ echo 'btn-info';}?>">
									<input type="checkbox" value="KP" name="examnsn_cornea_r_srfs_kp" class="cornea_sur_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_srfs_kp']=='KP'){ echo 'checked';}?>> KP
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_srfs_opct']=='Opacity'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Opacity" name="examnsn_cornea_r_srfs_opct" class="cornea_sur_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_srfs_opct']=='Opacity'){ echo 'checked';}?>> Opacity
								</label>
								<label class="c-button <?php if($exam_cornea['examnsn_cornea_r_srfs_ulcr']=='Ulcer'){ echo 'btn-info';}?>">
									<input type="checkbox" value="Ulcer" name="examnsn_cornea_r_srfs_ulcr" class="cornea_sur_ri8 opm8" <?php if($exam_cornea['examnsn_cornea_r_srfs_ulcr']=='Ulcer'){ echo 'checked';}?>> Ulcer
								</label> 
							</div>
						</div>
					</div>
					<div class="row toggle_cornea_ri8 d-none mb-5">
						<div class="col-md-3">Schirmer's Test 1</div>
						<div class="col-md-3 text-right">
							<span class=""><input type="text" name="examnsn_cornea_r_scht1_mm" value="<?php echo $exam_cornea['examnsn_cornea_r_scht1_mm'];?>" class="w-40px opm8"></span>
							<span class="">mm in </span>
						</div>
						<div class="col-md-3">
							<span class=""><input type="text" name="examnsn_cornea_r_scht1_min" value="<?php echo $exam_cornea['examnsn_cornea_r_scht1_min'];?>" class="w-40px opm8"></span>
							<span class="">min </span>
						</div>
					</div>
					<div class="row toggle_cornea_ri8 d-none mb-5">
						<div class="col-md-3">Schirmer's Test 2</div>
						<div class="col-md-3 text-right">
							<span class=""><input type="text" name="examnsn_cornea_r_scht2_mm" value="<?php echo $exam_cornea['examnsn_cornea_r_scht2_mm'];?>" class="w-40px opm8"></span>
							<span class="">mm in </span>
						</div>
						<div class="col-md-3">
							<span class=""><input type="text" name="examnsn_cornea_r_scht2_min" value="<?php echo $exam_cornea['examnsn_cornea_r_scht2_min'];?>" class="w-40px opm8"></span>
							<span class="">min </span>
						</div>
					</div>
					<div class="row toggle_cornea_ri8 d-none mb-5">
						<div class="col-md-2">Comments</div>
						<div class="col-md-10">
							<textarea name="examnsn_cornea_r_comm" class="form-control opm8"><?php echo $exam_cornea['examnsn_cornea_r_comm'];?></textarea>
						</div>
					</div>
				 <input type="hidden" name="examnsn_cornea_r_update" id="opm8" value="<?php echo $exam_cornea['examnsn_cornea_r_update'];?>">
				</div>
				<!-- ANTERIOR CHAMBER (AC) -->
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-12">
							<span class="btn_fill">ANTERIOR CHAMBER (AC)</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_ant_ch_ri8').toggle();$('.fa_toggle9_ri8').toggleClass('fa-minus');"><i class="fa_toggle9_ri8 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row mb-5 toggle_ant_ch_ri8 d-none">
						<div class="col-md-3">Depth</div>
						<div class="col-md-5">
							<div class="btn-group">
								<label onclick="$('.abc_btn_input_ri8').hide()" class="btn btn-default btn-sm abc_btn <?php if($exam_atrch['examnsn_ac_r_defth']=='Normal'){ echo 'btn-info';}?>"> Normal <input type="radio" name="examnsn_ac_r_defth" value="Normal" class="examnsn_ac_r_defth opm10" <?php if($exam_atrch['examnsn_ac_r_defth']=='Normal'){ echo 'checked';}?>></label>
								<label onclick="$('#abc_btn_input1_ri8').show();$('#abc_btn_input2_ri8').hide();" class="btn btn-default btn-sm abc_btn <?php if($exam_atrch['examnsn_ac_r_defth']=='Shallow'){ echo 'btn-info';}?>">Shallow <input type="radio" name="examnsn_ac_r_defth" value="Shallow" class="examnsn_ac_r_defth opm10" <?php if($exam_atrch['examnsn_ac_r_defth']=='Shallow'){ echo 'checked';}?>></label>
								<label onclick="$('#abc_btn_input1_ri8').hide();$('#abc_btn_input2_ri8').show();" class="btn btn-default btn-sm abc_btn <?php if($exam_atrch['examnsn_ac_r_defth']=='Deep'){ echo 'btn-info';}?>">Deep <input type="radio" value="Deep" name="examnsn_ac_r_defth" class="examnsn_ac_r_defth opm10" <?php if($exam_atrch['examnsn_ac_r_defth']=='Deep'){ echo 'checked';}?>></label>
							</div>
						</div>
						<div class="col-md-4 collapse abc_btn_input_ri8" id="abc_btn_input1_ri8">
							<input type="text" name="examnsn_ac_r_defth_txt1" class="form-control opm10" value="<?php echo $exam_atrch['examnsn_ac_r_defth_txt1'];?>">
						</div>
						<div class="col-md-4 collapse abc_btn_input_ri8" id="abc_btn_input2_ri8">
							<input type="text" name="examnsn_ac_r_defth_txt2" class="form-control opm10" value="<?php echo $exam_atrch['examnsn_ac_r_defth_txt2'];?>">
						</div>
					</div>
					<div class="row mb-5 toggle_ant_ch_ri8 d-none">
						<div class="col-md-3">Cells</div>
						<div class="col-md-5">														
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_atrch['examnsn_ac_r_cells']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_ac_r_cells" class="radio_toggle_antch1_ri8 opm10" <?php if($exam_atrch['examnsn_ac_r_cells']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_atrch['examnsn_ac_r_cells']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_ac_r_cells" class="radio_toggle_antch1_ri8 opm10" <?php if($exam_atrch['examnsn_ac_r_cells']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						</div>
						<div class="col-md-4 d-none" id="radio_toggle_antch1_ri8">  
							<input type="text" name="examnsn_ac_r_cells_txt" class="form-control opm10" value="<?php echo $exam_atrch['examnsn_ac_r_cells_txt'];?>">
						</div>  
					</div>
					<div class="row mb-5 toggle_ant_ch_ri8 d-none">
						<div class="col-md-3">Flare</div>
						<div class="col-md-5">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_atrch['examnsn_ac_r_flar']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_ac_r_flar" class="radio_toggle_antch2_ri8 opm10" <?php if($exam_atrch['examnsn_ac_r_flar']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_atrch['examnsn_ac_r_flar']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_ac_r_flar" class="radio_toggle_antch2_ri8 opm10" <?php if($exam_atrch['examnsn_ac_r_flar']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						</div>
						<div class="col-md-4 d-none" id="radio_toggle_antch2_ri8">
							<input type="text" name="examnsn_ac_r_flar_txt" value="<?php echo $exam_atrch['examnsn_ac_r_flar_txt'];?>" class="form-control opm10">
						</div>
					</div>
					<div class="row mb-5 toggle_ant_ch_ri8 d-none">
						<div class="col-md-3">Hyphema</div>
						<div class="col-md-5">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_atrch['examnsn_ac_r_hyfma']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_ac_r_hyfma" class="radio_toggle_antch3_ri8 opm10" <?php if($exam_atrch['examnsn_ac_r_hyfma']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_atrch['examnsn_ac_r_hyfma']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_ac_r_hyfma" class="radio_toggle_antch3_ri8 opm10" <?php if($exam_atrch['examnsn_ac_r_hyfma']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						</div>
						<div class="col-md-4 d-none" id="radio_toggle_antch3_ri8">
							<input type="text" name="examnsn_ac_r_hyfma_txt" class="form-control opm10" value="<?php echo $exam_atrch['examnsn_ac_r_hyfma_txt'];?>">
						</div>						
					</div>
					<div class="row mb-5 toggle_ant_ch_ri8 d-none">
						<div class="col-md-3">Hypopyon</div>
						<div class="col-md-5">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_atrch['examnsn_ac_r_hypn']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_ac_r_hypn" class="radio_toggle_antch4_ri8 opm10" <?php if($exam_atrch['examnsn_ac_r_hypn']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_atrch['examnsn_ac_r_hypn']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_ac_r_hypn" class="radio_toggle_antch4_ri8 opm10" <?php if($exam_atrch['examnsn_ac_r_hypn']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						</div>
						<div class="col-md-4 d-none" id="radio_toggle_antch4_ri8">
							<input type="text" name="examnsn_ac_r_hypn_txt" class="form-control opm10" value="<?php echo $exam_atrch['examnsn_ac_r_hypn_txt'];?>">
						</div>
					</div>
					<div class="row mb-5 toggle_ant_ch_ri8 d-none">
						<div class="col-md-3">Foreign Body</div>
						<div class="col-md-5">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_atrch['examnsn_ac_r_fbd']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_ac_r_fbd" class="radio_toggle_antch5_ri8 opm10" <?php if($exam_atrch['examnsn_ac_r_fbd']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_atrch['examnsn_ac_r_fbd']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_ac_r_fbd" class="radio_toggle_antch5_ri8 opm10" <?php if($exam_atrch['examnsn_ac_r_fbd']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						</div>
						<div class="col-md-4 d-none" id="radio_toggle_antch5_ri8">
							<input type="text" name="examnsn_ac_r_fbd_txt" class="form-control opm10" value="<?php echo $exam_atrch['examnsn_ac_r_fbd_txt'];?>">
						</div>
					</div>
					<div class="row mb-5 toggle_ant_ch_ri8 d-none">
						<div class="col-md-3">Comments</div>
						<div class="col-md-9">
							<textarea name="examnsn_ac_r_comm" cols="30" rows="10" class="form-control opm10"><?php echo $exam_atrch['examnsn_ac_r_comm'];?></textarea>
						</div>
					</div>
					<input type="hidden" name="examnsn_ac_r_update" id="opm10" value="<?php echo $exam_atrch['examnsn_ac_r_update'];?>">

				</div>
				<!-- PUPIL -->
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-12">
							<span class="btn_fill">PUPIL</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_pupil_ri8').toggle();$('.fa_toggle11_ri8').toggleClass('fa-minus');"><i class="fa_toggle11_ri8 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row mb-5 toggle_pupil_ri8 d-none">
						<div class="col-md-4">Shape</div>
						<div class="col-md-8"> 
							<div class="btn-group">
								<label  class="btn shape_ri8 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_r_shp']=='Round'){ echo 'btn-info';}?>">Round <input type="radio" value="Round" name="examnsn_pupl_r_shp" class="opm12" <?php if($exam_pupil['examnsn_pupl_r_shp']=='Round'){ echo 'checked';}?>></label>
								<label  class="btn shape_ri8 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_r_shp']=='Eccentric'){ echo 'btn-info';}?>">Eccentric <input type="radio" value="Eccentric" name="examnsn_pupl_r_shp" class="opm12" <?php if($exam_pupil['examnsn_pupl_r_shp']=='Eccentric'){ echo 'checked';}?>></label>
								<label  class="btn shape_ri8 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_r_shp']=='Irregular'){ echo 'btn-info';}?>">Irregular <input type="radio" value="Irregular" name="examnsn_pupl_r_shp" class="opm12" <?php if($exam_pupil['examnsn_pupl_r_shp']=='Irregular'){ echo 'checked';}?>></label>
								<label  class="btn shape_ri8 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_r_shp']=='Oval'){ echo 'btn-info';}?>">Oval <input type="radio" value="Oval" name="examnsn_pupl_r_shp" class="opm12" <?php if($exam_pupil['examnsn_pupl_r_shp']=='Oval'){ echo 'checked';}?>></label>
								<label  class="btn shape_ri8 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_r_shp']=='Polycoria'){ echo 'btn-info';}?>">Polycoria <input type="radio" value="Polycoria" name="examnsn_pupl_r_shp" class="opm12" <?php if($exam_pupil['examnsn_pupl_r_shp']=='Polycoria'){ echo 'checked';}?>></label>
							</div>

						</div>
					</div>
					<div class="row mb-5 toggle_pupil_ri8 d-none">
						<div class="col-md-4">Reaction to light Direct</div>
						<div class="col-md-8">
							<div class="btn-group">
								<label class="btn shape2_ri8 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_r_rld']=='Normal'){ echo 'btn-info';}?>">Normal <input type="radio" value="Normal" name="examnsn_pupl_r_rld" class="opm12" <?php if($exam_pupil['examnsn_pupl_r_rld']=='Normal'){ echo 'checked';}?>> </label>
								<label class="btn shape2_ri8 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_r_rld']=='Sluggish'){ echo 'btn-info';}?>">Sluggish <input type="radio" value="Sluggish" name="examnsn_pupl_r_rld" class="opm12" <?php if($exam_pupil['examnsn_pupl_r_rld']=='Sluggish'){ echo 'checked';}?>> </label>
								<label class="btn shape2_ri8 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_r_rld']=='Absent'){ echo 'btn-info';}?>">Absent <input type="radio" value="Absent" name="examnsn_pupl_r_rld" class="opm12" <?php if($exam_pupil['examnsn_pupl_r_rld']=='Absent'){ echo 'checked';}?>> </label>
							</div>
						</div>
					</div>
					<div class="row mb-5 toggle_pupil_ri8 d-none">
						<div class="col-md-4">Reaction to light consensual</div>
						<div class="col-md-8">
							<div class="btn-group">
								<label class="btn shape3_ri8 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_r_rlc']=='Normal'){ echo 'btn-info';}?>">Normal <input type="radio" value="Normal" name="examnsn_pupl_r_rlc" class="opm12" <?php if($exam_pupil['examnsn_pupl_r_rlc']=='Normal'){ echo 'checked';}?>></label>
								<label class="btn shape3_ri8 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_r_rlc']=='Sluggish'){ echo 'btn-info';}?>">Sluggish <input type="radio" value="Sluggish" name="examnsn_pupl_r_rlc" class="opm12" <?php if($exam_pupil['examnsn_pupl_r_rlc']=='Sluggish'){ echo 'checked';}?>></label>
								<label class="btn shape3_ri8 btn-sm btn-default <?php if($exam_pupil['examnsn_pupl_r_rlc']=='Absent'){ echo 'btn-info';}?>">Absent <input type="radio" value="Absent" name="examnsn_pupl_r_rlc" class="opm12" <?php if($exam_pupil['examnsn_pupl_r_rlc']=='Absent'){ echo 'checked';}?>></label>
							</div>
						</div>
					</div>
					<div class="row mb-5 toggle_pupil_ri8 d-none">
						<div class="col-md-4">Afferent pupillary defect (RAPD)</div>
						<div class="col-md-8">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_pupil['examnsn_pupl_r_apd']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_pupl_r_apd" class="radio_toggle_apd1_ri8 opm12" <?php if($exam_pupil['examnsn_pupl_r_apd']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_pupil['examnsn_pupl_r_apd']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_pupl_r_apd" class="radio_toggle_apd1_ri8 opm12" <?php if($exam_pupil['examnsn_pupl_r_apd']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						</div>
					</div>
					<div class="row mb-5 toggle_pupil_ri8 d-none">
						<div class="col-md-4">Comments</div>
						<div class="col-md-8">
							<textarea name="examnsn_pupl_r_comm" class="form-control opm12"><?php echo $exam_pupil['examnsn_pupl_r_comm'];?>
							</textarea>
						</div>
					</div>
				  <input type="hidden" name="examnsn_pupl_r_update" id="opm12" value="<?php echo $exam_pupil['examnsn_pupl_r_update'];?>">
				</div>
				<!-- IRIS -->
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-12">
							<span class="btn_fill">IRIS</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_iris_ri8').toggle();$('.fa_toggle13_ri8').toggleClass('fa-minus');"><i class="fa_toggle13_ri8 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row mb-5 toggle_iris_ri8 d-none"> 
						<div class="col-md-4">Shape</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button <?php if($exam_iris['examnsn_iris_r_shp']=='Normal'){ echo 'btn-info';}?>">
										<input type="radio" value="Normal" name="examnsn_iris_r_shp" class="radio_toggle_iris1_ri8 opm14"  <?php if($exam_iris['examnsn_iris_r_shp']=='Normal'){ echo 'checked';}?>> Normal 
									</label>
									<label class="c-button <?php if($exam_iris['examnsn_iris_r_shp']=='Defects'){ echo 'btn-info';}?>">
										<input type="radio" value="Defects" name="examnsn_iris_r_shp" class="radio_toggle_iris1_ri8 opm14"  <?php if($exam_iris['examnsn_iris_r_shp']=='Defects'){ echo 'checked';}?>> Defects 
									</label>
								</div>
							</div>
					</div>
					<div class="row mb-5 toggle_iris_ri8 d-none">
						<div class="col-md-4">Neovascularisation (NVI)</div>
						<div class="col-md-8">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_iris['examnsn_iris_r_nvi']=='No'){ echo 'btn-info';}?>">
									<input type="radio" value="No" name="examnsn_iris_r_nvi" class="radio_toggle_iris2_ri8 opm14" <?php if($exam_iris['examnsn_iris_r_nvi']=='No'){ echo 'checked';}?>> No 
								</label>
								<label class="c-button <?php if($exam_iris['examnsn_iris_r_nvi']=='Yes'){ echo 'btn-info';}?>">
									<input type="radio" value="Yes" name="examnsn_iris_r_nvi" class="radio_toggle_iris2_ri8 opm14" <?php if($exam_iris['examnsn_iris_r_nvi']=='Yes'){ echo 'checked';}?>> Yes 
								</label>
							</div>
						</div>
					</div>
					<div class="row mb-5 toggle_iris_ri8 d-none">
						<div class="col-md-4">Synechiae</div>
						<div class="col-md-8">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_iris['examnsn_iris_r_synch']=='No'){ echo 'btn-info';}?>">
										<input type="radio" value="No" name="examnsn_iris_r_synch" class="radio_toggle_iris3_ri8 opm14" <?php if($exam_iris['examnsn_iris_r_synch']=='No'){ echo 'checked';}?>> No 
									</label>
									<label class="c-button <?php if($exam_iris['examnsn_iris_r_synch']=='Anterior'){ echo 'btn-info';}?>">
										<input type="radio" value="Anterior" name="examnsn_iris_r_synch" class="radio_toggle_iris3_ri8 opm14" <?php if($exam_iris['examnsn_iris_r_synch']=='Anterior'){ echo 'checked';}?>> Anterior 
									</label>
									<label class="c-button <?php if($exam_iris['examnsn_iris_r_synch']=='Posterior'){ echo 'btn-info';}?>">
										<input type="radio" value="Posterior" name="examnsn_iris_r_synch" class="radio_toggle_iris3_ri8 opm14" <?php if($exam_iris['examnsn_iris_r_synch']=='Posterior'){ echo 'checked';}?>> Posterior 
									</label>
							</div>
						</div>
					</div>
					<div class="row mb-5 toggle_iris_ri8 d-none">
						<div class="col-md-4">Comments</div>
						<div class="col-md-8">
							<textarea name="examnsn_iris_r_comm" cols="30" rows="10" class="form-control opm14"><?php echo $exam_iris['examnsn_iris_r_comm'];?></textarea>
						</div>
					</div>					
					<input type="hidden" name="examnsn_iris_r_update" id="opm14" value="<?php echo $exam_iris['examnsn_iris_r_update'];?>">

				</div>
				<!-- LENS -->
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-12">
							<span class="btn_fill">LENS</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_lens_ri8').toggle();$('.fa_toggle15_ri8').toggleClass('fa-minus');"><i class="fa_toggle15_ri8 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row mb-5 toggle_lens_ri8 d-none">
						<div class="col-md-3">Nature</div>
						<div class="col-md-9">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_lens['examnsn_lens_r_ntr']=='Clear'){ echo 'btn-info';}?>">
									<input type="radio" value="Clear" name="examnsn_lens_r_ntr" class="radio_toggle_lens1_ri8 opm16" <?php if($exam_lens['examnsn_lens_r_ntr']=='Clear'){ echo 'checked';}?>> Clear 
								</label>
								<label class="c-button <?php if($exam_lens['examnsn_lens_r_ntr']=='Cataract'){ echo 'btn-info';}?>">
									<input type="radio" value="Cataract" name="examnsn_lens_r_ntr" class="radio_toggle_lens1_ri8 opm16" <?php if($exam_lens['examnsn_lens_r_ntr']=='Cataract'){ echo 'checked';}?>> Cataract 
								</label>
								<label class="c-button <?php if($exam_lens['examnsn_lens_r_ntr']=='Pseudophakia'){ echo 'btn-info';}?>">
									<input type="radio" value="Pseudophakia" name="examnsn_lens_r_ntr" class="radio_toggle_lens1_ri8 opm16" <?php if($exam_lens['examnsn_lens_r_ntr']=='Pseudophakia'){ echo 'checked';}?>> Pseudophakia 
								</label>
								<label class="c-button <?php if($exam_lens['examnsn_lens_r_ntr']=='Aphakia'){ echo 'btn-info';}?>">
									<input type="radio" value="Aphakia" name="examnsn_lens_r_ntr" class="radio_toggle_lens1_ri8 opm16" <?php if($exam_lens['examnsn_lens_r_ntr']=='Aphakia'){ echo 'checked';}?>> Aphakia 
								</label>
							</div>
						</div>
					</div>
					<!-- <div class="row mb-5 toggle_lens_ri8 d-none">
						<div class="col-md-3">Position</div>
						<div class="col-md-9">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_lens['examnsn_lens_r_psn']=='Central'){ echo 'btn-info';}?>">
									<input type="radio" name="examnsn_lens_r_psn" value="Central" class="radio_toggle_lens2_ri8 opm16" <?php if($exam_lens['examnsn_lens_r_psn']=='Central'){ echo 'checked';}?>> Central 
								</label>
								<label class="c-button <?php if($exam_lens['examnsn_lens_r_psn']=='Decentered'){ echo 'btn-info';}?>">
									<input type="radio" value="Decentered" name="examnsn_lens_r_psn" class="radio_toggle_lens2_ri8 opm16" <?php if($exam_lens['examnsn_lens_r_psn']=='Decentered'){ echo 'checked';}?>> Decentered 
								</label>
								<label class="c-button <?php if($exam_lens['examnsn_lens_r_psn']=='Subluxated'){ echo 'btn-info';}?>">
									<input type="radio" value="Subluxated" name="examnsn_lens_r_psn" class="radio_toggle_lens2_ri8 opm16" <?php if($exam_lens['examnsn_lens_r_psn']=='Subluxated'){ echo 'checked';}?>> Subluxated 
								</label>
							</div>
						</div>
					</div>
					<div class="row mb-5 toggle_lens_ri8 d-none">
						<div class="col-md-3">Size</div>
						<div class="col-md-9">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_lens['examnsn_lens_r_sz']=='Normal'){ echo 'btn-info';}?>">
									<input type="radio" value="Normal" name="examnsn_lens_r_sz" class="radio_toggle_lens3_ri8 opm16" <?php if($exam_lens['examnsn_lens_r_sz']=='Normal'){ echo 'checked';}?>> Normal
								</label>
								<label class="c-button <?php if($exam_lens['examnsn_lens_r_sz']=='Swollen'){ echo 'btn-info';}?>">
									<input type="radio" value="Swollen" name="examnsn_lens_r_sz" class="radio_toggle_lens3_ri8 opm16" <?php if($exam_lens['examnsn_lens_r_sz']=='Swollen'){ echo 'checked';}?>> Swollen 
								</label>
								<label class="c-button <?php if($exam_lens['examnsn_lens_r_sz']=='Absorbed'){ echo 'btn-info';}?>">
									<input type="radio" value="Absorbed" name="examnsn_lens_r_sz" class="radio_toggle_lens3_ri8 opm16" <?php if($exam_lens['examnsn_lens_r_sz']=='Absorbed'){ echo 'checked';}?>> Absorbed 
								</label>
								<label class="c-button <?php if($exam_lens['examnsn_lens_r_sz']=='Micro'){ echo 'btn-info';}?>">
									<input type="radio" value="Micro" name="examnsn_lens_r_sz" class="radio_toggle_lens3_ri8 opm16" <?php if($exam_lens['examnsn_lens_r_sz']=='Micro'){ echo 'checked';}?>> Micro 
								</label>
							</div>
						</div>
					</div> -->
					<div class="row mb-5 <?php if($exam_lens['examnsn_lens_r_ntr']=='Cataract'){ echo 'd-block';}else{ echo "d-none";}?>" id="nat_ap_r">
						<div class="col-md-3">LOCS Grading</div>
						<div class="col-md-9">
							<div class="row">
								<div class="col-sm-4">
									<div class="input-group">
										<span class="input-group-addon">NS</span>
										<select name="examnsn_lens_r_locsg_ns" class="form-control opm16">
											<option value="">Sel</option>
											<option value="1" <?php if($exam_lens['examnsn_lens_r_locsg_ns']=='1'){ echo 'selected';}?>>1</option>
										<option value="2" <?php if($exam_lens['examnsn_lens_r_locsg_ns']=='2'){ echo 'selected';}?>>2</option>
										<option value="3" <?php if($exam_lens['examnsn_lens_r_locsg_ns']=='3'){ echo 'selected';}?>>3</option>
										<option value="4" <?php if($exam_lens['examnsn_lens_r_locsg_ns']=='4'){ echo 'selected';}?>>4</option>
										<option value="5" <?php if($exam_lens['examnsn_lens_r_locsg_ns']=='5'){ echo 'selected';}?>>5</option>
										<option value="6" <?php if($exam_lens['examnsn_lens_r_locsg_ns']=='6'){ echo 'selected';}?>>6</option>
										</select>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										<span class="input-group-addon">C </span>
										<select name="examnsn_lens_r_locsg_c" class="form-control opm16">
											<option value="">Sel</option>
											<option value="1" <?php if($exam_lens['examnsn_lens_r_locsg_c']=='1'){ echo 'selected';}?>>1</option>
										<option value="2" <?php if($exam_lens['examnsn_lens_r_locsg_c']=='2'){ echo 'selected';}?>>2</option>
										<option value="3" <?php if($exam_lens['examnsn_lens_r_locsg_c']=='3'){ echo 'selected';}?>>3</option>
										<option value="4" <?php if($exam_lens['examnsn_lens_r_locsg_c']=='4'){ echo 'selected';}?>>4</option>
										<option value="5" <?php if($exam_lens['examnsn_lens_r_locsg_c']=='5'){ echo 'selected';}?>>5</option>
										<option value="6" <?php if($exam_lens['examnsn_lens_r_locsg_c']=='6'){ echo 'selected';}?>>6</option>
										</select>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										<span class="input-group-addon">P</span>
										<select name="examnsn_lens_r_locsg_p" class="form-control opm16">
											<option value="">Sel</option>
											<option value="1" <?php if($exam_lens['examnsn_lens_r_locsg_p']=='1'){ echo 'selected';}?>>1</option>
										<option value="2" <?php if($exam_lens['examnsn_lens_r_locsg_p']=='2'){ echo 'selected';}?>>2</option>
										<option value="3" <?php if($exam_lens['examnsn_lens_r_locsg_p']=='3'){ echo 'selected';}?>>3</option>
										<option value="4" <?php if($exam_lens['examnsn_lens_r_locsg_p']=='4'){ echo 'selected';}?>>4</option>
										<option value="5" <?php if($exam_lens['examnsn_lens_r_locsg_p']=='5'){ echo 'selected';}?>>5</option>
										<option value="6" <?php if($exam_lens['examnsn_lens_r_locsg_p']=='6'){ echo 'selected';}?>>6</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
			
					<div class="row mb-5 toggle_lens_ri8 d-none">
						<div class="col-md-3">Comments</div>
						<div class="col-md-9">
							<textarea name="examnsn_lens_r_comm" cols="30" rows="10" class="form-control opm16"><?php echo $exam_lens['examnsn_lens_r_comm'];?></textarea>
						</div>
					</div>
				   <input type="hidden" name="examnsn_lens_r_update" id="opm16" value="<?php echo $exam_lens['examnsn_lens_r_update'];?>">

				</div>
				<!-- EXTRAOCULAR MOVEMENTS & SQUINT -->
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-12">
							<span class="btn_fill">EXTRAOCULAR MOVEMENTS & SQUINT</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_extra_ri8').toggle();$('.fa_toggle17_ri8').toggleClass('fa-minus');"><i class="fa_toggle17_ri8 fa fa-plus"></i></button>
						</div>
					</div> 
					<div class="row mb-5 toggle_extra_ri8 d-none">
						<div class="col-md-4">Uniocular movements</div>
						<div class="col-md-8">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_extrmovs['examnsn_ems_r_unimv']=='Full'){ echo 'btn-info';}?>">
									<input type="radio" value="Full" name="examnsn_ems_r_unimv" class="btn_uni_ri8 opm18" <?php if($exam_extrmovs['examnsn_ems_r_unimv']=='Full'){ echo 'checked';}?>> Full
								</label>
								<label class="c-button <?php if($exam_extrmovs['examnsn_ems_r_unimv']=='Restricted'){ echo 'btn-info';}?>">
									<input type="radio" value="Restricted" name="examnsn_ems_r_unimv" class="btn_uni_ri8 opm18" <?php if($exam_extrmovs['examnsn_ems_r_unimv']=='Restricted'){ echo 'checked';}?> > Restricted 
								</label>
								<label class="c-button <?php if($exam_extrmovs['examnsn_ems_r_unimv']=='Overaction'){ echo 'btn-info';}?>">
									<input type="radio" value="Overaction" name="examnsn_ems_r_unimv" class="btn_uni_ri8 opm18" <?php if($exam_extrmovs['examnsn_ems_r_unimv']=='Overaction'){ echo 'checked';}?> > Overaction 
								</label>
								<label class="c-button <?php if($exam_extrmovs['examnsn_ems_r_unimv']=='Limitation'){ echo 'btn-info';}?>">
									<input type="radio" value="Limitation" name="examnsn_ems_r_unimv" class="btn_uni_ri8 opm18" <?php if($exam_extrmovs['examnsn_ems_r_unimv']=='Limitation'){ echo 'checked';}?> > Limitation 
								</label>
							</div>
						</div>
					</div>
					<div class="row mb-5 toggle_extra_ri8 d-none">
						<div class="col-md-4">Binocular movements</div>
						<div class="col-md-8">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_extrmovs['examnsn_ems_r_bimv']=='Full'){ echo 'btn-info';}?>">
									<input type="radio" value="Full" name="examnsn_ems_r_bimv" class="radio_toggle_ems2_ri8 opm18" <?php if($exam_extrmovs['examnsn_ems_r_bimv']=='Full'){ echo 'checked';}?>> Full 
								</label>
								<label class="c-button <?php if($exam_extrmovs['examnsn_ems_r_bimv']=='Restricted'){ echo 'btn-info';}?>">
									<input type="radio" value="Restricted" name="examnsn_ems_r_bimv" class="radio_toggle_ems2_ri8 opm18" <?php if($exam_extrmovs['examnsn_ems_r_bimv']=='Restricted'){ echo 'checked';}?>> Restricted 
								</label>
							</div>
						</div>
					</div>
					<div class="row mb-5 toggle_extra_ri8 d-none">
						<div class="col-md-4">Comments</div>
						<div class="col-md-8">
							<textarea name="examnsn_ems_r_comm" cols="30" rows="10" class="form-control opm18"><?php echo trim($exam_extrmovs['examnsn_ems_r_comm']);?></textarea>
						</div>
					</div>
					<div class="row mb-5 toggle_extra_ri8 d-none">
						<div class="col-md-4">Prism</div>
						<div class="col-md-4">
							<div class="input-group">
								<input type="text" name="examnsn_ems_r_prsm" class="form-control opm18" value="<?php echo $exam_extrmovs['examnsn_ems_r_prsm'];?>">
								<span class="input-group-addon">
									<i class="fa fa-caret-up"></i> 
								</span>
							</div>
						</div>
					</div>
					<section class="toggle_extra_ri8 d-none">
						<div class="row mb-5">
							<div class="col-md-4">Squint</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button btn_squin_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt']=='Tropia'){ echo 'btn-info';}?>">Tropia<input type="radio" value="Tropia" name="examnsn_ems_r_sqnt" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt']=='Tropia'){ echo 'checked';}?>></label>
									<label class="c-button btn_squin_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt']=='Phoria'){ echo 'btn-info';}?>">Phoria<input type="radio" value="Phoria" name="examnsn_ems_r_sqnt" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt']=='Phoria'){ echo 'checked';}?>></label>
								</div>
								
							</div>
						</div>
						<div class="row mb-5 tropia_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt']=='Tropia'){ echo 'd-block';} else{ echo 'd-none'; } ?>">
							<div class="col-md-4">Tropia</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button btn_tropia_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa']=='Horizontal'){ echo 'btn-info';}?>">Horizontal<input type="radio" value="Horizontal" name="examnsn_ems_r_sqnt_trpa" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa']=='Horizontal'){ echo 'checked';}?>></label>
									<label class="c-button btn_tropia_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa']=='Vertical'){ echo 'btn-info';}?>">Vertical<input type="radio" value="Vertical" name="examnsn_ems_r_sqnt_trpa" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa']=='Vertical'){ echo 'checked';}?>></label>
									<label class="c-button btn_tropia_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa']=='Paralytic'){ echo 'btn-info';}?>">Paralytic<input type="radio" value="Paralytic" name="examnsn_ems_r_sqnt_trpa" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa']=='Paralytic'){ echo 'checked';}?>></label>
								</div>
							</div>
						</div>
						<div class="row mb-5 phoria_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt']=='Phoria'){ echo 'd-block';} else{ echo 'd-none'; } ?>">
							<div class="col-md-4">Phoria</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button btn_phoria_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_phoria']=='Esophoria(EP)'){ echo 'btn-info';}?>">Esophoria(EP)<input type="radio" value="Esophoria(EP)" name="examnsn_ems_r_sqnt_phoria" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt_phoria']=='Esophoria(EP)'){ echo 'checked';}?>></label>
									<label class="c-button btn_phoria_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_phoria']=='Exophoria(X)'){ echo 'btn-info';}?>">Exophoria(X) <input type="radio" value="Exophoria(X)" name="examnsn_ems_r_sqnt_phoria" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt_phoria']=='Exophoria(X)'){ echo 'checked';}?>></label>
								</div>  
							</div>
						</div>
						<div class="row mb-5 horizontal_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa']=='Horizontal'){ echo 'd-block';} else{ echo 'd-none';}?>">
							<div class="col-md-4">Horizontal</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button btn_horizontal_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h']=='Esotropia(ET)'){ echo 'btn-info';}?>">Esotropia(ET)<input type="radio" name="examnsn_ems_r_sqnt_trpa_h"  class="opm18" value="Esotropia(ET)" <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h']=='Esotropia(ET)'){ echo 'checked';}?>></label>
									<label class="c-button btn_horizontal_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h']=='Exotropia(XT)'){ echo 'btn-info';}?>">Exotropia(XT)<input type="radio" value="Exotropia(XT)" name="examnsn_ems_r_sqnt_trpa_h" class="opm18"  <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h']=='Exotropia(XT)'){ echo 'checked';}?>></label>
								</div>
							</div>
						</div>
						<div class="row mb-5 esotropia_ET_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h']=='Esotropia(ET)'){ echo 'd-block';} else{ echo 'd-none';} ?>">
							<div class="col-md-4">Esotropia(ET)</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button btn_esotropia_ET_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_et']=='ACS'){ echo 'btn-info';}?>">ACS<input type="radio" value="ACS" name="examnsn_ems_r_sqnt_trpa_h_et" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_et']=='ACS'){ echo 'checked';}?>></label>
									<label class="c-button btn_esotropia_ET_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_et']=='Sensory'){ echo 'btn-info';}?>">Sensory<input type="radio" value="Sensory" name="examnsn_ems_r_sqnt_trpa_h_et" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_et']=='Sensory'){ echo 'checked';}?>></label>
									<label class="c-button btn_esotropia_ET_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_et']=='Infantile'){ echo 'btn-info';}?>">Infantile<input type="radio" value="Infantile" name="examnsn_ems_r_sqnt_trpa_h_et" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_et']=='Infantile'){ echo 'checked';}?>></label>
									<label class="c-button btn_esotropia_ET_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_et']=='ICS'){ echo 'btn-info';}?>">ICS <input type="radio" value="ICS" name="examnsn_ems_r_sqnt_trpa_h_et" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_et']=='ICS'){ echo 'checked';}?>></label>
								</div>
							</div>
						</div>
						<div class="row mb-5 exotropia_XT_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h']=='Esotropia(XT)'){ echo 'd-block';} else{ echo 'd-none';} ?>">
							<div class="col-md-4">Esotropia(XT)</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button btn_exotropia_XT_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_xt']=='ADS'){ echo 'btn-info';}?>">ADS<input type="radio" value="ADS" name="examnsn_ems_r_sqnt_trpa_h_xt" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_xt']=='ADS'){ echo 'checked';}?>></label>
									<label class="c-button btn_exotropia_XT_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_xt']=='IDS'){ echo 'btn-info';}?>">IDS<input type="radio" value="IDS" name="examnsn_ems_r_sqnt_trpa_h_xt" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_xt']=='IDS'){ echo 'checked';}?>></label>
									<label class="c-button btn_exotropia_XT_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_xt']=='Sensory'){ echo 'btn-info';}?>">Sensory<input type="radio" value="Sensory" name="examnsn_ems_r_sqnt_trpa_h_xt" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_h_xt']=='Sensory'){ echo 'checked';}?>></label>
								</div>
							</div>
						</div>
						<div class="row mb-5 vertical_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa']=='Vertical'){ echo 'd-block';} else{ echo 'd-none';}?>">
							<div class="col-md-4">Vertical</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button btn_vertical_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_v']=='Hypertropia(HT)'){ echo 'btn-info';}?>">Hypertropia(HT)<input type="radio" value="Hypertropia(HT)" name="examnsn_ems_r_sqnt_trpa_v" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_v']=='Hypertropia(HT)'){ echo 'checked';}?>></label>
									<label class="c-button btn_vertical_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_v']=='Hypotropia(HoT)'){ echo 'btn-info';}?>">Hypotropia(HoT)<input type="radio" value="Hypotropia(HoT)" name="examnsn_ems_r_sqnt_trpa_v" class="opm18" <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa_v']=='Hypotropia(HoT)'){ echo 'checked';}?>></label>
								</div>
							</div>
						</div>
						<div class="row mb-5 paralytic_ri8 <?php if($exam_extrmovs['examnsn_ems_r_sqnt_trpa']=='Paralytic'){ echo 'd-block';} else{ echo 'd-none';}?>">
							<div class="col-md-4">Paralytic</div>
							<div class="col-md-8">
								<div class="c-btn-group">
									<label class="c-button <?php if($exam_extrmovs['examnsn_ems_r_para_mr']=='MR'){ echo 'btn-info';}?>"> 
										<input type="checkbox" value="MR" name="examnsn_ems_r_para_mr" class="btn_paralytic_ri8 opm18" <?php if($exam_extrmovs['examnsn_ems_r_para_mr']=='MR'){ echo 'checked';}?>> MR
									</label>
									<label class="c-button <?php if($exam_extrmovs['examnsn_ems_r_para_lr']=='LR'){ echo 'btn-info';}?>">
										<input type="checkbox" value="LR" name="examnsn_ems_r_para_lr" class="btn_paralytic_ri8 opm18" <?php if($exam_extrmovs['examnsn_ems_r_para_lr']=='LR'){ echo 'checked';}?>> LR
									</label>
									<label class="c-button <?php if($exam_extrmovs['examnsn_ems_r_para_so']=='SO'){ echo 'btn-info';}?>">
										<input type="checkbox" value="SO" name="examnsn_ems_r_para_so" class="btn_paralytic_ri8 opm18" <?php if($exam_extrmovs['examnsn_ems_r_para_so']=='SO'){ echo 'checked';}?>> SO
									</label>
									<label class="c-button <?php if($exam_extrmovs['examnsn_ems_r_para_sr']=='SR'){ echo 'btn-info';}?>">
										<input type="checkbox" value="SR" name="examnsn_ems_r_para_sr" class="btn_paralytic_ri8 opm18" <?php if($exam_extrmovs['examnsn_ems_r_para_sr']=='SR'){ echo 'checked';}?>> SR
									</label>
									<label class="c-button <?php if($exam_extrmovs['examnsn_ems_r_para_ir']=='IR'){ echo 'btn-info';}?>">
										<input type="checkbox" value="IR" name="examnsn_ems_r_para_ir" class="btn_paralytic_ri8 opm18" <?php if($exam_extrmovs['examnsn_ems_r_para_ir']=='IR'){ echo 'checked';}?>> IR
									</label>
									<label class="c-button <?php if($exam_extrmovs['examnsn_ems_r_para_io']=='IO'){ echo 'btn-info';}?>">
										<input type="checkbox" value="IO" name="examnsn_ems_r_para_io" class="btn_paralytic_ri8 opm18" <?php if($exam_extrmovs['examnsn_ems_r_para_io']=='IO'){ echo 'checked';}?>> IO
									</label>
								</div>
							</div>
						</div>
					</section>
				    <input type="hidden" name="examnsn_ems_r_update" id="opm18" value="<?php echo $exam_extrmovs['examnsn_ems_r_update'];?>">

				</div>
				<!-- INTRAOCULAR PRESSURE (IOP) -->
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-12">
							<span class="btn_fill">INTRAOCULAR PRESSURE (IOP)</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_iop_ri8').toggle();$('.fa_toggle19_ri8').toggleClass('fa-minus');"><i class="fa_toggle19_ri8 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row mb-5 toggle_iop_ri8 d-none">
						<div class="col-md-2">Value</div>
						<div class="col-md-4 slidecontainer">
							<input type="range" min="0" max="100" class="slider" value="<?php if($exam_intrprsr['examnsn_iop_r_value'] !=''){ echo $exam_intrprsr['examnsn_iop_r_value'];} else{ echo '0';}?>" id="examnsn_myRange_r">
						</div>
						<div class="col-md-4">
							<div class="input-group">
								<input type="text" name="examnsn_iop_r_value" id="examnsn_iop_r_value" class="form-control" value="<?php echo $exam_intrprsr['examnsn_iop_r_value'];?>">
								<span class="input-group-addon">mmHG</span>
							</div>
						</div>
						<div class="col-md-2">
							<input type="text" name="examnsn_iop_r_time" value="<?php echo $exam_intrprsr['examnsn_iop_r_time'];?>" class="form-control datepicker3">
						</div>
					</div>
					<div class="row mb-5 toggle_iop_ri8 d-none">
						<div class="col-md-2">Method</div>
						<div class="col-md-4">
							<select name="examnsn_iop_r_method"   class="form-control">
								<option value="">Select</option>
								<option value="AT" <?php if($exam_intrprsr['examnsn_iop_r_method']=='AT'){ echo 'selected';}?> >AT</option>
								<option value="NCT" <?php if($exam_intrprsr['examnsn_iop_r_method']=='NCT'){ echo 'selected';}?> >NCT</option>
								<option value="Schiotz" <?php if($exam_intrprsr['examnsn_iop_r_method']=='Schiotz'){ echo 'selected';}?> >Schiotz</option>
								<option value="Perkins" <?php if($exam_intrprsr['examnsn_iop_r_method']=='Perkins'){ echo 'selected';}?> >Perkins</option>
							</select>
						</div>
					</div>
					<div class="row mb-5 toggle_iop_ri8 d-none ">
						<div class="col-md-2">Comments</div>
						<div class="col-md-10">
							<textarea name="examnsn_iop_r_comm" cols="30" rows="10" class="form-control">
								<?php echo $exam_intrprsr['examnsn_iop_r_comm'];?>
							</textarea>
						</div>
					</div>
				</div>
				<!-- GONIOSCOPY -->
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-12">
							<span class="btn_fill">GONIOSCOPY</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_gonio_ri8').toggle();$('.fa_toggle21_ri8').toggleClass('fa-minus');"><i class="fa_toggle21_ri8 fa fa-plus"></i></button>
						</div>
					</div>
					<div class="row mb-5 toggle_gonio_ri8 d-none">
						<div class="col-md-12">
							<table class="table table-bordered">
								<tr>
									<td style="width:100px;">Superior: <i class="fa fa-arrow-right"></i></td>
									<td>
										<div class="row">
											<div class="col-sm-4">
												<select name="examnsn_gonispy_r_sup_d1"   class="form-control opm22">
													<option value="">Select</option>
													<option value="0" <?php if($exam_gnscp['examnsn_gonispy_r_sup_d1']=='0'){ echo 'selected';}?>>0</option>
													<option value="1" <?php if($exam_gnscp['examnsn_gonispy_r_sup_d1']=='1'){ echo 'selected';}?>>1</option>
													<option value="2" <?php if($exam_gnscp['examnsn_gonispy_r_sup_d1']=='2'){ echo 'selected';}?>>2</option>
													<option value="3" <?php if($exam_gnscp['examnsn_gonispy_r_sup_d1']=='3'){ echo 'selected';}?>>3</option>
													<option value="4" <?php if($exam_gnscp['examnsn_gonispy_r_sup_d1']=='4'){ echo 'selected';}?>>4</option>
												</select>
											</div>
											<div class="col-sm-4">
												<select name="examnsn_gonispy_r_sup_d2"   class="form-control opm22">
													<option value="">Select</option>
													<option value="SS" <?php if($exam_gnscp['examnsn_gonispy_r_sup_d2']=='SS'){ echo 'selected';}?>>SS</option>
													<option value="TM-Ant" <?php if($exam_gnscp['examnsn_gonispy_r_sup_d2']=='TM-Ant'){ echo 'selected';}?>>TM-Ant</option>
													<option value="TM-Post" <?php if($exam_gnscp['examnsn_gonispy_r_sup_d2']=='TM-Post'){ echo 'selected';}?>>TM-Post</option>
													<option value="CB" <?php if($exam_gnscp['examnsn_gonispy_r_sup_d2']=='CB'){ echo 'selected';}?>>CB</option>
													<option value="SL" <?php if($exam_gnscp['examnsn_gonispy_r_sup_d2']=='SL'){ echo 'selected';}?>>SL</option>
												</select>
											</div>
											<div class="col-sm-4">
												<select name="examnsn_gonispy_r_sup_d3"   class="form-control opm22">
													<option value="">Select</option>
													<option value="NVI" <?php if($exam_gnscp['examnsn_gonispy_r_sup_d3']=='NVI'){ echo 'selected';}?>>NVI</option>
													<option value="PAS" <?php if($exam_gnscp['examnsn_gonispy_r_sup_d3']=='PAS'){ echo 'selected';}?>>PAS</option>
												</select>
											</div>
										</div>
									</td>
									<td style="width:100px;"></td>
								</tr>
								<tr>
									<td>
										<div class="row">
											<div class="col-md-12 mb-5">Nasal:</div>
											<div class="col-md-12 mb-5">
												<select name="examnsn_gonispy_r_tmprl_d1"   class="form-control opm22">
													<option value="">Select</option>
													<option value="0" <?php if($exam_gnscp['examnsn_gonispy_r_tmprl_d1']=='0'){ echo 'selected';}?>>0</option>
													<option value="1" <?php if($exam_gnscp['examnsn_gonispy_r_tmprl_d1']=='1'){ echo 'selected';}?>>1</option>
													<option value="2" <?php if($exam_gnscp['examnsn_gonispy_r_tmprl_d1']=='2'){ echo 'selected';}?>>2</option>
													<option value="3" <?php if($exam_gnscp['examnsn_gonispy_r_tmprl_d1']=='3'){ echo 'selected';}?>>3</option>
													<option value="4" <?php if($exam_gnscp['examnsn_gonispy_r_tmprl_d1']=='4'){ echo 'selected';}?>>4</option>
												</select>
											</div>
											<div class="col-md-12 mb-5">
												<select name="examnsn_gonispy_r_tmprl_d2"   class="form-control opm22">
													<option value="">Select</option>
													<option value="SS" <?php if($exam_gnscp['examnsn_gonispy_r_tmprl_d2']=='SS'){ echo 'selected';}?>>SS</option>
													<option value="TM-Ant" <?php if($exam_gnscp['examnsn_gonispy_r_tmprl_d2']=='TM-Ant'){ echo 'selected';}?>>TM-Ant</option>
													<option value="TM-Post" <?php if($exam_gnscp['examnsn_gonispy_r_tmprl_d2']=='TM-Post'){ echo 'selected';}?>>TM-Post</option>
													<option value="CB" <?php if($exam_gnscp['examnsn_gonispy_r_tmprl_d2']=='CB'){ echo 'selected';}?>>CB</option>
													<option value="SL" <?php if($exam_gnscp['examnsn_gonispy_r_tmprl_d2']=='SL'){ echo 'selected';}?>>SL</option>
												</select>
											</div>
											<div class="col-md-12 mb-5">
												<select name="examnsn_gonispy_r_tmprl_d3"   class="form-control opm22">
													<option value="">Select</option>
													<option value="NVI" <?php if($exam_gnscp['examnsn_gonispy_r_tmprl_d3']=='NVI'){ echo 'selected';}?>>NVI</option>
													<option value="PAS" <?php if($exam_gnscp['examnsn_gonispy_r_tmprl_d3']=='PAS'){ echo 'selected';}?>>PAS</option>
												</select>
											</div>
										</div>
									</td>
									<td align="center" valign="middle">
										<img src="<?php echo base_url('assets/images/eye_cross.png');?>" alt="a" class="img-fluid">
									</td>
									<td align="center">
										<div class="row">
											<div class="col-md-12 mb-5">Temporal:</div>
											<div class="col-md-12 mb-5">
												<select name="examnsn_gonispy_r_nsl_d1"   class="form-control opm22">
													<option value="">Select</option>
													<option value="0" <?php if($exam_gnscp['examnsn_gonispy_r_nsl_d1']=='0'){ echo 'selected';}?>>0</option>
													<option value="1" <?php if($exam_gnscp['examnsn_gonispy_r_nsl_d1']=='1'){ echo 'selected';}?>>1</option>
													<option value="2" <?php if($exam_gnscp['examnsn_gonispy_r_nsl_d1']=='2'){ echo 'selected';}?>>2</option>
													<option value="3" <?php if($exam_gnscp['examnsn_gonispy_r_nsl_d1']=='3'){ echo 'selected';}?>>3</option>
													<option value="4" <?php if($exam_gnscp['examnsn_gonispy_r_nsl_d1']=='4'){ echo 'selected';}?>>4</option>
												</select>
											</div>
											<div class="col-md-12 mb-5">
												<select name="examnsn_gonispy_r_nsl_d2"   class="form-control opm22">
													<option value="">Select</option>
													<option value="SS" <?php if($exam_gnscp['examnsn_gonispy_r_nsl_d2']=='SS'){ echo 'selected';}?>>SS</option>
													<option value="TM-Ant" <?php if($exam_gnscp['examnsn_gonispy_r_nsl_d2']=='TM-Ant'){ echo 'selected';}?>>TM-Ant</option>
													<option value="TM-Post" <?php if($exam_gnscp['examnsn_gonispy_r_nsl_d2']=='TM-Post'){ echo 'selected';}?>>TM-Post</option>
													<option value="CB" <?php if($exam_gnscp['examnsn_gonispy_r_nsl_d2']=='CB'){ echo 'selected';}?>>CB</option>
													<option value="SL" <?php if($exam_gnscp['examnsn_gonispy_r_nsl_d2']=='SL'){ echo 'selected';}?>>SL</option>
												</select>
											</div>
											<div class="col-md-12 mb-5">
												<select name="examnsn_gonispy_r_nsl_d3"   class="form-control opm22">
													<option value="">Select</option>
													<option value="NVI" <?php if($exam_gnscp['examnsn_gonispy_r_nsl_d3']=='NVI'){ echo 'selected';}?>>NVI</option>
													<option value="PAS" <?php if($exam_gnscp['examnsn_gonispy_r_nsl_d3']=='PAS'){ echo 'selected';}?>>PAS</option>
												</select>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td width="20%">Inferior: <i class="fa fa-arrow-right"></i></td>
									<td width="60% ">
										<div class="row">
											<div class="col-sm-4">
												<select name="examnsn_gonispy_r_infr_d1"   class="form-control opm22">
													<option value="">Select</option>
													<option value="0" <?php if($exam_gnscp['examnsn_gonispy_r_infr_d1']=='0'){ echo 'selected';}?>>0</option>
													<option value="1" <?php if($exam_gnscp['examnsn_gonispy_r_infr_d1']=='1'){ echo 'selected';}?>>1</option>
													<option value="2" <?php if($exam_gnscp['examnsn_gonispy_r_infr_d1']=='2'){ echo 'selected';}?>>2</option>
													<option value="3" <?php if($exam_gnscp['examnsn_gonispy_r_infr_d1']=='3'){ echo 'selected';}?>>3</option>
													<option value="4" <?php if($exam_gnscp['examnsn_gonispy_r_infr_d1']=='4'){ echo 'selected';}?>>4</option>
												</select>
											</div>
											<div class="col-sm-4">
												<select name="examnsn_gonispy_r_infr_d2"   class="form-control opm22">
													<option value="">Select</option>
													<option value="SS" <?php if($exam_gnscp['examnsn_gonispy_r_infr_d2']=='SS'){ echo 'selected';}?>>SS</option>
													<option value="TM-Ant" <?php if($exam_gnscp['examnsn_gonispy_r_infr_d2']=='TM-Ant'){ echo 'selected';}?>>TM-Ant</option>
													<option value="TM-Post" <?php if($exam_gnscp['examnsn_gonispy_r_infr_d2']=='TM-Post'){ echo 'selected';}?>>TM-Post</option>
													<option value="CB" <?php if($exam_gnscp['examnsn_gonispy_r_infr_d2']=='CB'){ echo 'selected';}?>>CB</option>
													<option value="SL" <?php if($exam_gnscp['examnsn_gonispy_r_infr_d2']=='SL'){ echo 'selected';}?>>SL</option>
												</select>
											</div>
											<div class="col-sm-4">
												<select name="examnsn_gonispy_r_infr_d3"   class="form-control opm22">
													<option value="">Select</option>
													<option value="NVI" <?php if($exam_gnscp['examnsn_gonispy_r_infr_d3']=='NVI'){ echo 'selected';}?>>NVI</option>
													<option value="PAS" <?php if($exam_gnscp['examnsn_gonispy_r_infr_d3']=='PAS'){ echo 'selected';}?>>PAS</option>
												</select>
											</div>
										</div>
									</td>
									<td width="20%"></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="row mb-5 toggle_gonio_ri8 d-none ">
						<div class="col-md-2">Comments</div>
						<div class="col-md-10">
							<textarea name="examnsn_gonispy_r_comm" cols="30" rows="10" class="form-control opm22"><?php echo $exam_gnscp['examnsn_gonispy_r_comm'];?></textarea>
						</div>
					</div>
					<input type="hidden" name="examnsn_gonispy_r_update" id="opm22" value="<?php echo $exam_gnscp['examnsn_gonispy_r_update'];?>">

				</div>
				<!-- FUNDUS --> 
				<div class="panel-body border-bottom">
					<div class="row mb-5">
						<div class="col-md-10">
							<span class="btn_fill">FUNDUS</span>
							<button type="button" class="btn_fill" onclick="$('.toggle_fundus_ri8').toggle();$('.fa_toggle23_ri8').toggleClass('fa-minus');"><i class="fa_toggle23_ri8 fa fa-plus"></i></button>
						</div>
						<div class="col-md-2 c-btn-group">
							<label class="c-button  <?php if($exam_fundus['toggle_fundus_ri8_nrml']=='Normal'){ echo 'btn-info';}?>">
								<input type="checkbox" value="Normal" name="toggle_fundus_ri8_nrml" class="toggle_fundus_ri8_nrml opm24" <?php if($exam_fundus['toggle_fundus_ri8_nrml']=='Normal'){ echo 'checked';}?>> Normal
							</label>
						</div>     
					</div>


					<div class="row mb-5 toggle_fundus_ri8 d-none">
						<div class="col-md-3">Dialet</div>
						<div class="col-md-4">
							<div class="c-btn-group">
								<label class="c-button <?php if($exam_fundus['examnsn_fundus_r_dialet']=='Undilated'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_fundus['examnsn_fundus_r_dialet']=='Undilated'){ echo 'checked';}?> name="examnsn_fundus_r_dialet" value="Undilated" class="radio_toggle_r_dialet opm24"> Undilated 
								</label>
								<label class="c-button <?php if($exam_fundus['examnsn_fundus_r_dialet']=='Dilated'){ echo 'btn-info';}?>">
									<input type="radio" <?php if($exam_fundus['examnsn_fundus_r_dialet']=='Dilated'){ echo 'checked';}?> name="examnsn_fundus_r_dialet" value="Dilated" class="radio_toggle_r_dialet opm24"> Dilated 
								</label>
							</div>
						</div>
					</div>				

					<div class="row mb-5 toggle_fundus_ri8 d-none">
						<div class="col-md-3">Media</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_r_mda" id="examnsn_fundus_r_mda" class="form-control opm24">
								<option value="">Select</option>
								<option value="Clear"  <?php if($exam_fundus['examnsn_fundus_r_mda']=='Clear'){ echo 'selected';}?>>Clear</option>
								<option value="Hazy" <?php if($exam_fundus['examnsn_fundus_r_mda']=='Hazy'){ echo 'selected';}?>>Hazy</option>
							</select>
						</div>
						<div class="col-md-5">
							<input type="text" name="examnsn_fundus_r_mda_comm"  value="<?php echo $exam_fundus['examnsn_fundus_r_mda_comm']; ?>" class="form-control opm24">
						</div>
					</div>
					<div class="row mb-5 toggle_fundus_ri8 d-none">
						<div class="col-md-3">PVD</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_r_pvd" id="examnsn_fundus_r_pvd" class="form-control opm24">
								<option value="">Select</option>
								<option value="Absent" <?php if($exam_fundus['examnsn_fundus_r_pvd']=='Absent'){ echo 'selected';}?>>Absent</option>
								<option value="Present" <?php if($exam_fundus['examnsn_fundus_r_pvd']=='Present'){ echo 'selected';}?>>Present</option>
							</select>
						</div>
						
					</div>
					<div class="row mb-5 toggle_fundus_ri8 d-none">
						<div class="col-md-3">Optic Disc Size</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_r_ods" id="examnsn_fundus_r_ods" class="form-control opm24">
								<option value="">Select</option>
								<option value="Small" <?php if($exam_fundus['examnsn_fundus_r_ods']=='Small'){ echo 'selected';}?>>Small</option>
								<option value="Medium" <?php if($exam_fundus['examnsn_fundus_r_ods']=='Medium'){ echo 'selected';}?>>Medium</option>
								<option value="Large" <?php if($exam_fundus['examnsn_fundus_r_ods']=='Large'){ echo 'selected';}?>>Large</option>
							</select>
						</div>
						
					</div>
					<div class="row mb-5 toggle_fundus_ri8 d-none">
						<div class="col-md-3">Cup/Disc Ratio (C/D)</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_r_cdr" id="examnsn_fundus_r_cdr" class="form-control opm24">
								<option value="">Select</option>
								<option value="0.1" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.1'){ echo 'selected';}?>>0.1</option>
								<option value="0.15" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.15'){ echo 'selected';}?>>0.15</option>
								<option value="0.2" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.2'){ echo 'selected';}?>>0.2</option>
								<option value="0.25" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.25'){ echo 'selected';}?>>0.25</option>
								<option value="0.3" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.3'){ echo 'selected';}?>>0.3</option>
								<option value="0.35" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.35'){ echo 'selected';}?>>0.35</option>
								<option value="0.4" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.4'){ echo 'selected';}?>>0.4</option>
								<option value="0.45" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.45'){ echo 'selected';}?>>0.45</option>
								<option value="0.5" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.5'){ echo 'selected';}?>>0.5</option>
								<option value="0.55" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.55'){ echo 'selected';}?>>0.55</option>
								<option value="0.6" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.6'){ echo 'selected';}?>>0.6</option>
								<option value="0.65" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.65'){ echo 'selected';}?>>0.65</option>
								<option value="0.7" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.7'){ echo 'selected';}?>>0.7</option>
								<option value="0.75" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.75'){ echo 'selected';}?>>0.75</option>
								<option value="0.8" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.8'){ echo 'selected';}?>>0.8</option>
								<option value="0.85" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.85'){ echo 'selected';}?>>0.85</option>
								<option value="0.9" <?php if($exam_fundus['examnsn_fundus_r_cdr']=='0.9'){ echo 'selected';}?>>0.9</option>
							</select>
						</div>
						<div class="col-md-5">
							<input type="text" name="examnsn_fundus_r_cdr_txt" value="<?php echo $exam_fundus['examnsn_fundus_r_cdr_txt'];?>" class="form-control opm24">
						</div>
					</div>
					<div class="row mb-5 toggle_fundus_ri8 d-none">
						<div class="col-md-3">Optic Disc</div>
						<div class="col-md-9">							
							<input type="text" name="examnsn_fundus_r_opdisc" id="examnsn_fundus_r_opdisc" class="form-control opm24" value="<?php echo $exam_fundus['examnsn_fundus_r_opdisc'];?>">
						</div>
					</div>
					<div class="row mb-5 toggle_fundus_ri8 d-none">
						<div class="col-md-3">Blood Vessels</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_r_bldvls" class="form-control opm24">
								<option value="">Select</option>
								<option value="Sclerosed" <?php if($exam_fundus['examnsn_fundus_r_bldvls']=='Sclerosed'){ echo 'selected';}?>>Sclerosed</option>
								<option value="Sheathed" <?php if($exam_fundus['examnsn_fundus_r_bldvls']=='Sheathed'){ echo 'selected';}?>>Sheathed</option>
								<option value="Tortuous" <?php if($exam_fundus['examnsn_fundus_r_bldvls']=='Tortuous'){ echo 'selected';}?>>Tortuous</option>
								<option value="Attenuated" <?php if($exam_fundus['examnsn_fundus_r_bldvls']=='Attenuated'){ echo 'selected';}?>>Attenuated</option>
								<option value="Engorged" <?php if($exam_fundus['examnsn_fundus_r_bldvls']=='Engorged'){ echo 'selected';}?>>Engorged</option>
							</select>
						</div>
						<div class="col-md-5">
							<input type="text" name=" examnsn_fundus_r_bldvls_txt" id="examnsn_fundus_r_bldvls_txt" class="form-control opm24" value="<?php echo $exam_fundus['examnsn_fundus_r_bldvls_txt'];?>" >
						</div>
					</div>
					<div class="row mb-5 toggle_fundus_ri8 d-none">
						<div class="col-md-3">Macula</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_r_mcla" class="form-control opm24">
								<option value="">Select</option>
								<option value="Clear" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Clear'){ echo 'selected';}?>>Clear</option>
								<option value="Hard Exudates" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Hard Exudates'){ echo 'selected';}?>>Hard Exudates</option>
								<option value="Microaneurysm" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Microaneurysm'){ echo 'selected';}?>>Microaneurysm</option>
								<option value="Hemorrhages" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Hemorrhages'){ echo 'selected';}?>>Hemorrhages</option>
								<option value="Subretinal Hemorrhages" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Subretinal Hemorrhages'){ echo 'selected';}?>>Subretinal Hemorrhages</option>
								<option value="Scar" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Scar'){ echo 'selected';}?>>Scar</option>
								<option value="Atrophic area" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Atrophic area'){ echo 'selected';}?>>Atrophic area</option>
								<option value="Pigment Alteration" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Pigment Alteration'){ echo 'selected';}?>>Pigment Alteration</option>
								<option value="Drusen" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Drusen'){ echo 'selected';}?>>Drusen</option>
								<option value="Subretinal Fluid" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Subretinal Fluid'){ echo 'selected';}?>>Subretinal Fluid</option>
								<option value="Cystoid" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Cystoid'){ echo 'selected';}?>>Cystoid</option>
								<option value="Thickening" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Thickening'){ echo 'selected';}?>>Thickening</option>
								<option value="Whitening" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Whitening'){ echo 'selected';}?>>Whitening</option>
								<option value="Cotton Wool Spots" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Cotton Wool Spots'){ echo 'selected';}?>>Cotton Wool Spots</option>
								<option value="Pigment Epithelial Detachment" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Pigment Epithelial Detachment'){ echo 'selected';}?>>Pigment Epithelial Detachment</option>
								<option value="Altered Foveal Reflex" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Altered Foveal Reflex'){ echo 'selected';}?>>Altered Foveal Reflex</option>
								<option value="Vascular Abnormalities" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Vascular Abnormalities'){ echo 'selected';}?>>Vascular Abnormalities</option>
								<option value="Pigmentary Changes" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Pigmentary Changes'){ echo 'selected';}?>>Pigmentary Changes</option>
								<option value="Epiretinal Membrane" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Epiretinal Membrane'){ echo 'selected';}?>>Epiretinal Membrane</option>
								<option value="FTMH" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='FTMH'){ echo 'selected';}?>>FTMH</option>
								<option value="Lamellar Hole" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Lamellar Hole'){ echo 'selected';}?>>Lamellar Hole</option>
								<option value="ILM Striae" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='ILM Striae'){ echo 'selected';}?>>ILM Striae</option>
								<option value="White Dots" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='White Dots'){ echo 'selected';}?>>White Dots</option>
								<option value="Yellow Flecks" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Yellow Flecks'){ echo 'selected';}?>>Yellow Flecks</option>
								<option value="Cherry Red Spot" <?php if($exam_fundus['examnsn_fundus_r_mcla']=='Cherry Red Spot'){ echo 'selected';}?>>Cherry Red Spot</option>
							</select>
						</div>
						<div class="col-md-5">
							<input type="text" name="examnsn_fundus_r_mcla_txt" id="examnsn_fundus_r_mcla_txt" class="form-control opm24" value="<?php echo $exam_fundus['examnsn_fundus_r_mcla_txt'];?>" >
						</div>
					</div>
					<div class="row mb-5 toggle_fundus_ri8 d-none">
						<div class="col-md-3">Vitreous</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_r_vtrs" class="form-control opm24">
								<option value="">Select</option>
								<option value="Clear" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Clear'){ echo 'selected';}?>>Clear</option>
								<option value="Cells" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Cells'){ echo 'selected';}?>>Cells</option>
								<option value="Haze" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Haze'){ echo 'selected';}?>>Haze</option>
								<option value="Exudates" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Exudates'){ echo 'selected';}?>>Exudates</option>
								<option value="Active" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Active'){ echo 'selected';}?>>Active</option>
								<option value="Inactive" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Inactive'){ echo 'selected';}?>>Inactive</option>
								<option value="Pars Plana Exudate" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Pars Plana Exudate'){ echo 'selected';}?>>Pars Plana Exudate</option>
								<option value="Snow Banking" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Snow Banking'){ echo 'selected';}?>>Snow Banking</option>
								<option value="Snow Ball Opacities" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Snow Ball Opacities'){ echo 'selected';}?>>Snow Ball Opacities</option>
								<option value="Hemorrhage-Fresh" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Hemorrhage-Fresh'){ echo 'selected';}?>>Hemorrhage-Fresh</option>
								<option value="Hemorrhage-Old" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Hemorrhage-Old'){ echo 'selected';}?>>Hemorrhage-Old</option>
								<option value="Vitreous Bands" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Vitreous Bands'){ echo 'selected';}?>>Vitreous Bands</option>
								<option value="Optically Empty Vitreous" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Optically Empty Vitreous'){ echo 'selected';}?>>Optically Empty Vitreous</option>
								<option value="Vitreouschisis" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Vitreouschisis'){ echo 'selected';}?>>Vitreouschisis</option>
								<option value="Vitreous Floaters" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Vitreous Floaters'){ echo 'selected';}?>>Vitreous Floaters</option>
								<option value="Vitreous Condensation" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Vitreous Condensation'){ echo 'selected';}?>>Vitreous Condensation</option>
								<option value="Posterior Vitreous Detachment" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Posterior Vitreous Detachment'){ echo 'selected';}?>>Posterior Vitreous Detachment</option>
								<option value="Weiss Ring" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Weiss Ring'){ echo 'selected';}?>>Weiss Ring</option>
								<option value="Retrohyaloid Haem" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Retrohyaloid Haem'){ echo 'selected';}?>>Retrohyaloid Haem</option>
								<option value="Silicone Oil in Situ" <?php if($exam_fundus['examnsn_fundus_r_vtrs']=='Silicone Oil in Situ'){ echo 'selected';}?>>Silicone Oil in Situ</option>
							</select>
						</div>
						<div class="col-md-5">
							<input type="text" name="examnsn_fundus_r_vtrs_txt" class="form-control opm24" value="<?php echo $exam_fundus['examnsn_fundus_r_vtrs_txt'];?>">
						</div>
					</div>
					<div class="row mb-5 toggle_fundus_ri8 d-none">
						<div class="col-md-3">Retinal Detachment </div>
						<div class="col-md-4">
							<select name="examnsn_fundus_r_rtnldcht" class="form-control opm24">
								<option value="">Select</option>
								<option value="Partial" <?php if($exam_fundus['examnsn_fundus_r_rtnldcht']=='Partial'){ echo 'selected';}?> >Partial</option>
								<option value="Sub-total" <?php if($exam_fundus['examnsn_fundus_r_rtnldcht']=='Sub-total'){ echo 'selected';}?> >Sub-total</option>
								<option value="Total" <?php if($exam_fundus['examnsn_fundus_r_rtnldcht']=='Total'){ echo 'selected';}?> >Total</option>
								<option value="SubClinical" <?php if($exam_fundus['examnsn_fundus_r_rtnldcht']=='SubClinical'){ echo 'selected';}?> >SubClinical</option>
								<option value="Rhegmatogenous" <?php if($exam_fundus['examnsn_fundus_r_rtnldcht']=='Rhegmatogenous'){ echo 'selected';}?> >Rhegmatogenous</option>
								<option value="Exudative" <?php if($exam_fundus['examnsn_fundus_r_rtnldcht']=='Exudative'){ echo 'selected';}?> >Exudative</option>
								<option value="Tractional" <?php if($exam_fundus['examnsn_fundus_r_rtnldcht']=='Tractional'){ echo 'selected';}?> >Tractional</option>
								<option value="Combined" <?php if($exam_fundus['examnsn_fundus_r_rtnldcht']=='Combined'){ echo 'selected';}?> >Combined</option>
								<option value="Multiple Breaks" <?php if($exam_fundus['examnsn_fundus_r_rtnldcht']=='Multiple Breaks'){ echo 'selected';}?> >Multiple Breaks</option>
							</select>
						</div>
						<div class="col-md-5">
							<input type="text" name="examnsn_fundus_r_rtnldcht_txt" class="form-control opm24" value="<?php echo $exam_fundus['examnsn_fundus_r_rtnldcht_txt'];?>">
						</div>
					</div>
					<div class="row mb-5 toggle_fundus_ri8 d-none">
						<div class="col-md-3">Peripheral Lesions</div>
						<div class="col-md-4">
							<select name="examnsn_fundus_r_perlsn" class="form-control opm24">
								<option value="">Select</option>
								<option value="Atrophic Hole" <?php if($exam_fundus['examnsn_fundus_r_perlsn']=='Atrophic Hole'){ echo 'selected';}?>>Atrophic Hole</option>
								<option value="Horse Shoe Tear" <?php if($exam_fundus['examnsn_fundus_r_perlsn']=='Horse Shoe Tear'){ echo 'selected';}?>>Horse Shoe Tear</option>
								<option value="Operculated Break" <?php if($exam_fundus['examnsn_fundus_r_perlsn']=='Operculated Break'){ echo 'selected';}?>>Operculated Break</option>
								<option value="Dialysis" <?php if($exam_fundus['examnsn_fundus_r_perlsn']=='Dialysis'){ echo 'selected';}?>>Dialysis</option>
								<option value="Giant Retinal Tear" <?php if($exam_fundus['examnsn_fundus_r_perlsn']=='Giant Retinal Tear'){ echo 'selected';}?>>Giant Retinal Tear</option>
								<option value="Multiple Breaks" <?php if($exam_fundus['examnsn_fundus_r_perlsn']=='Multiple Breaks'){ echo 'selected';}?>>Multiple Breaks</option>
								<option value="White Without Pressure Areas" <?php if($exam_fundus['examnsn_fundus_r_perlsn']=='White Without Pressure Areas'){ echo 'selected';}?>>White Without Pressure Areas</option>
							</select>
						</div>
						<div class="col-md-5">
							<input type="text" name="examnsn_fundus_r_perlsn_txt" class="form-control opm24" value="<?php echo $exam_fundus['examnsn_fundus_r_perlsn_txt'];?>" >
						</div>
					</div>
					<div class="row mb-5 toggle_fundus_ri8 d-none">
						<div class="col-md-3">	</div>
						<div class="col-md-5">
							<input type="text" name="examnsn_fundus_r_fnds" value="<?php echo $exam_fundus['examnsn_fundus_r_fnds'];?>" id="examnsn_fundus_r_fnds" class="form-control opm24">
						</div>
					</div>
					<div class="row mb-5 toggle_fundus_ri8 d-none">
						<div class="col-md-3">Comments</div>
						<div class="col-md-9">
							<textarea  name="examnsn_fundus_r_comm" class="form-control opm24"><?php echo $exam_fundus['examnsn_fundus_r_comm'];?></textarea>
						</div>
					</div>
					<input type="hidden" name="examnsn_fundus_r_update" id="opm24" value="<?php echo $exam_fundus['examnsn_fundus_r_update'];?>">
				</div>
			</div>
		</div>
	</div>
</section>	




<!-- General Examination -->
 <script>
	$(document).ready(function(){
		$('.radio_toggle_g_examin').click(function(){
			$('.radio_toggle_g_examin').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
			$('#abnormal_txtbox').toggle();
		});	

		// one eye
		$('.radio_toggle_one_eye').click(function(){
			$('.radio_toggle_one_eye').parent().removeClass('btn-info');   
			$(this).parent().addClass('btn-info'); 
		});			
		/*Phthisis Bulbi*/
		$('.radio_toggle_ph_bul').click(function(){
			$('.radio_toggle_ph_bul').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});		
		/*Anophthalmos*/
		$('.radio_toggle_anop').click(function(){
			$('.radio_toggle_anop').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});		
		/*	*/
		$('.radio_toggle_microp').click(function(){
			$('.radio_toggle_microp').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Artificial*/
		$('.radio_toggle_artifi').click(function(){
			$('.radio_toggle_artifi').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	

		/*Proptosis*/
		$('.radio_toggle_propt').click(function(){
			$('.radio_toggle_propt').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	

		/*Dystopia*/
		$('.radio_toggle_dystop').click(function(){
			$('.radio_toggle_dystop').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	

		//Injured
		$('.radio_toggle_injur').click(function(){
			$('.radio_toggle_injur').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	

		//Swollen
		$('.radio_toggle_swollen').click(function(){
			$('.radio_toggle_swollen').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});		
		/*APPENDAGES*/
		$('.toggle_appen_l').click(function(){
			$(this).parent().toggleClass('btn-info');  
		});
		/*Chalazion*/
		$('.radio_toggle_chalaz').click(function(){
			$('.radio_toggle_chalaz').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Ptosis*/
		$('.radio_toggle_ptosis').click(function(){
			$('.radio_toggle_ptosis').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Swelling*/
		$('.radio_toggle_swellening').click(function(){
			$('.radio_toggle_swellening').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Entropion*/
		$('.radio_toggle_enteropion').click(function(){
			$('.radio_toggle_enteropion').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Ectropion*/
		$('.radio_toggle_ectropion').click(function(){
			$('.radio_toggle_ectropion').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Mass*/
		$('.radio_toggle_mass').click(function(){
			$('.radio_toggle_mass').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Meibomitis*/
		$('.radio_toggle_meibomities').click(function(){
			$('.radio_toggle_meibomities').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Trichiasis*/
		$('.radio_toggle_trichi').click(function(){
			$('.radio_toggle_trichi').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Dystrichiasis*/
		$('.radio_toggle_dystrich').click(function(){
			$('.radio_toggle_dystrich').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Swelling	*/
		$('.radio_toggle_swellening2').click(function(){
			$('.radio_toggle_swellening2').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Regurgitation*/
		$('.radio_toggle_regur').click(function(){
			$('.radio_toggle_regur').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Syringing	*/
		$('.radio_toggle_syring').click(function(){
			$('.radio_toggle_syring').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*CONJUNCTIVA	*/
		$('.radio_toggle_cong1').click(function(){
			$('.radio_toggle_cong1').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*CONJUNCTIVA	sub */
		$('.radio_toggle_cong_sl').click(function(){
			$('.radio_toggle_cong_sl').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
			
		/*TEAR*/
		$('.radio_toggle_cong2').click(function(){
			$('.radio_toggle_cong2').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/* Conjuctival Bleb*/
        $('.radio_toggle_cong3').click(function(){
			$('.radio_toggle_cong3').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*SubConjunctival Haemorrhage*/
        $('.radio_toggle_cong4').click(function(){
			$('.radio_toggle_cong4').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Foreign Body*/
		$('.radio_toggle_cong5').click(function(){
			$('.radio_toggle_cong5').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});				
		/*Follicles*/
       $('.radio_toggle_cong6').click(function(){
			$('.radio_toggle_cong6').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Papillae*/
		$('.radio_toggle_cong7').click(function(){
			$('.radio_toggle_cong7').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Pinguecula*/
		$('.radio_toggle_cong8').click(function(){
			$('.radio_toggle_cong8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Pterygium*/
		$('.radio_toggle_cong9').click(function(){
			$('.radio_toggle_cong9').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Phlycten*/
       $('.radio_toggle_cong10').click(function(){
			$('.radio_toggle_cong10').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Discharge*/
		$('.radio_toggle_cong11').click(function(){
			$('.radio_toggle_cong11').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Size*/
		$('.radio_toggle_crna1').click(function(){
			$('.radio_toggle_crna1').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info');
		});	
		/*Shape*/
		$('.radio_toggle_crna2').click(function(){
			$('.radio_toggle_crna2').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info');
		});
		/*Tear*/
		$('.cornea_sur').click(function(){
			$('#cornea_nrml').prop('checked',false).parent().removeClass('btn-info');
			$(this).parent().toggleClass('btn-info');  
		})
		$('#cornea_nrml').click(function(){   
			$(this).prop('checked',true).parent().addClass('btn-info');
			$('.cornea_sur').prop('checked',false).parent().removeClass('btn-info');
		});	
		/*Depth*/
		$('.examnsn_ac_l_defth').click(function(){
			$('.examnsn_ac_l_defth').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info');
		});
		/*Cells*/
		$('.radio_toggle_antch1').click(function(){
			$('.radio_toggle_antch1').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info');
			if($(this).val()=='Yes') 
				$('#radio_toggle_antch1').show();
			else
				$('#radio_toggle_antch1').hide();
		});
		/*Flare*/
        $('.radio_toggle_antch2').click(function(){
			$('.radio_toggle_antch2').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
			if($(this).val()=='Yes') 
				$('#radio_toggle_antch2').show();
			else
				$('#radio_toggle_antch2').hide();
		});
		/*Hyphema*/
		$('.radio_toggle_antch3').click(function(){
			$('.radio_toggle_antch3').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
			if($(this).val()=='Yes') 
				$('#radio_toggle_antch3').show();
			else
				$('#radio_toggle_antch3').hide();
		});	
		/*Hypopyon*/
		$('.radio_toggle_antch4').click(function(){
			$('.radio_toggle_antch4').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
			if($(this).val()=='Yes') 
				$('#radio_toggle_antch4').show();
			else
				$('#radio_toggle_antch4').hide();
		});	
		/*Foreign Body*/
		$('.radio_toggle_antch5').click(function(){
			$('.radio_toggle_antch5').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
			if($(this).val()=='Yes') 
				$('#radio_toggle_antch5').show();
			else
				$('#radio_toggle_antch5').hide();
		});
		/*PUPIL*/
		$('.shape').on('click', function(){
			$('.shape').removeClass('btn-info');
			$(this).addClass('btn-info');
		});	
		/*Reaction to light Direct*/
			$('.shape2').on('click', function(){
				$('.shape2').removeClass('btn-info');
				$(this).addClass('btn-info');
		});	
		/*Reaction to light consensual*/
			$('.shape3').on('click', function(){
				$('.shape3').removeClass('btn-info');
				$(this).addClass('btn-info');
			});
		/*Afferent pupillary defect (RAPD)*/
		$('.radio_toggle_apd1').click(function(){
				$('.radio_toggle_apd1').parent().removeClass('btn-info');
				$(this).parent().addClass('btn-info'); 
		});	
			/*IRIS  Shape*/
		$('.radio_toggle_iris1').click(function(){
			$('.radio_toggle_iris1').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Neovascularisation (NVI)*/
		$('.radio_toggle_iris2').click(function(){
				$('.radio_toggle_iris2').parent().removeClass('btn-info');
				$(this).parent().addClass('btn-info'); 
			});
		/*	Synechiae*/
		$('.radio_toggle_iris3').click(function(){
			$('.radio_toggle_iris3').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Nature*/
		$('.radio_toggle_lens1').click(function(){
			$('.radio_toggle_lens1').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 			
			if($(this).val()=='Cataract')
			{
				$('#nat_ap_l').show();
			}
			else{
				$('#nat_ap_l').hide();
			}
		});
		/*Position*/
		$('.radio_toggle_lens2').click(function(){
			$('.radio_toggle_lens2').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Size*/
		$('.radio_toggle_lens3').click(function(){
			$('.radio_toggle_lens3').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Uniocular movements*/
		$('.btn_uni').click(function(){
			$('.btn_uni').parent().removeClass('btn-info');
			$(this).prop('checked',true).parent().toggleClass('btn-info');  
		});	
		/*Binocular movements*/
		$('.radio_toggle_ems2').click(function(){
			$('.radio_toggle_ems2').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	

		/*Squint*/
		$('.btn_squin').click(function(){
			$('.btn_squin').removeClass('btn-info');
			$(this).addClass('btn-info');
		});
		$('.btn_phoria').click(function(){
			$('.btn_phoria').removeClass('btn-info');
			$(this).addClass('btn-info');
		});
		$('.btn_squin:first-child').click(function(){
			$('.tropia').show();
			$('.phoria').hide();
		});
		$('.btn_squin:last-child').click(function(){
			$('.tropia').hide();
			$('.phoria').show();											
			$('.horizontal').hide();
			$('.vertical').hide();
			$('.paralytic').hide();
			$('.esotropia_ET').hide();
		});
		/*Tropia*/
			$('.btn_tropia').click(function(){
				$('.btn_tropia').removeClass('btn-info');
				$(this).addClass('btn-info');
			});
			$('.btn_tropia:first-child').click(function(){
				$('.horizontal').show();
				$('.vertical').hide();
				$('.paralytic').hide();
				$('.exotropia_XT').hide();
				$('.esotropia_ET').hide();

			});
			$('.btn_tropia:nth-child(2)').click(function(){
				$('.horizontal').hide();
				$('.vertical').show();
				$('.paralytic').hide();
				$('.exotropia_XT').hide();
				$('.esotropia_ET').hide();
			});
			$('.btn_tropia:last-child').click(function(){
				$('.horizontal').hide();
				$('.vertical').hide();
				$('.paralytic').show();
				$('.exotropia_XT').hide();
				$('.esotropia_ET').hide(); 
			});
			/*Horizontal*/
			$('.btn_horizontal').click(function(){
				$('.btn_horizontal').removeClass('btn-info');
				$(this).addClass('btn-info');
			});
			$('.btn_horizontal:first-child').click(function(){
				$('.esotropia_ET').show(); 
				$('.exotropia_XT').hide(); 
			});
			$('.btn_horizontal:last-child').click(function(){
				$('.esotropia_ET').hide(); 
				$('.exotropia_XT').show(); 
			});	
		 /*   Esotropia(ET)*/
			$('.btn_esotropia_ET').click(function(){
				$('.btn_esotropia_ET').removeClass('btn-info');
				$(this).addClass('btn-info');
			});
			/*Esotropia(XT)*/
			$('.btn_exotropia_XT').click(function(){
				$('.btn_exotropia_XT').removeClass('btn-info');
				$(this).addClass('btn-info');
			});
			/*Vertical*/
			$('.btn_vertical').click(function(){
				$('.btn_vertical').removeClass('btn-info');
				$(this).addClass('btn-info');
			});
			/*Paralytic*/
			$('.btn_paralytic').click(function(){
				$(this).parent().toggleClass('btn-info');  
			});
			/*FUNDUS*/
			$('.toggle_fundus_nrml').click(function(){
				$(this).parent().toggleClass('btn-info');  
				$('.toggle_fundus').show();
				$('.fa_toggle23').addClass('fa-minus');
				if($(this).prop('checked') == true)
				{
					$('#examnsn_fundus_l_mda').val('Clear');
					$('#examnsn_fundus_l_pvd').val('Absent');
					$('#examnsn_fundus_l_ods').val('Medium');
					$('#examnsn_fundus_l_cdr').val('0.3');
					$('#examnsn_fundus_l_opdisc').val('healthy');
					$('#examnsn_fundus_l_bldvls_txt').val('normal');
					$('#examnsn_fundus_l_mcla_txt').val('foveal reflex present');
					$('#examnsn_fundus_l_fnds').val('within normal limits');
				}
				else{
					$('#examnsn_fundus_l_mda').val('');
					$('#examnsn_fundus_l_pvd').val('');
					$('#examnsn_fundus_l_ods').val('');
					$('#examnsn_fundus_l_cdr').val('');
					$('#examnsn_fundus_l_opdisc').val('');
					$('#examnsn_fundus_l_bldvls_txt').val('');
					$('#examnsn_fundus_l_mcla_txt').val('');
					$('#examnsn_fundus_l_fnds').val('');
				}
			});


		/* fundus dialet*/
		$('.radio_toggle_l_dialet').click(function(){
			$('.radio_toggle_l_dialet').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	

		/*Right site*/
		/*Phthisis Bulbi*/
		$('.radio_toggle_ph_bul_ri8').click(function(){
			$('.radio_toggle_ph_bul_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Anophthalmos*/
		$('.radio_toggle_anop_ri8').click(function(){
			$('.radio_toggle_anop_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Micropththalmos*/
		$('.radio_toggle_microp_ri8').click(function(){
			$('.radio_toggle_microp_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Artificial*/
		$('.radio_toggle_artifi_ri8').click(function(){
			$('.radio_toggle_artifi_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Proptosis*/
		$('.radio_toggle_propt_ri8').click(function(){
			$('.radio_toggle_propt_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Dystopia*/
		$('.radio_toggle_dystop_ri8').click(function(){
			$('.radio_toggle_dystop_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Injured*/
		$('.radio_toggle_injur_ri8').click(function(){
				$('.radio_toggle_injur_ri8').parent().removeClass('btn-info');
				$(this).parent().addClass('btn-info'); 
			});
		/*	Swollen*/
		$('.radio_toggle_swollen_ri8').click(function(){
			$('.radio_toggle_swollen_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*APPENDAGES*/
		$('.toggle_appen_r').click(function(){
			$(this).parent().toggleClass('btn-info');  
		});
		/*Chalazion*/
		$('.radio_toggle_chalaz_ri8').click(function(){
			$('.radio_toggle_chalaz_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Ptosis*/
		$('.radio_toggle_ptosis_ri8').click(function(){
			$('.radio_toggle_ptosis_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Swelling*/
		$('.radio_toggle_swellening_ri8').click(function(){
			$('.radio_toggle_swellening_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Entropion*/
		$('.radio_toggle_enteropion_ri8').click(function(){
			$('.radio_toggle_enteropion_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Ectropion*/
		$('.radio_toggle_ectropion_ri8').click(function(){
			$('.radio_toggle_ectropion_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Mass*/
		$('.radio_toggle_mass_ri8').click(function(){
			$('.radio_toggle_mass_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Meibomitis*/
		$('.radio_toggle_meibomities_ri8').click(function(){
			$('.radio_toggle_meibomities_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Trichiasis*/
		$('.radio_toggle_trichi_ri8').click(function(){
			$('.radio_toggle_trichi_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
	/*	Dystrichiasis*/
		$('.radio_toggle_dystrich_ri8').click(function(){
			$('.radio_toggle_dystrich_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Swelling*/
		$('.radio_toggle_swellening2_ri8').click(function(){
			$('.radio_toggle_swellening2_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Regurgitation*/
		$('.radio_toggle_regur_ri8').click(function(){
			$('.radio_toggle_regur_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Syringing*/
		$('.radio_toggle_syring_ri8').click(function(){
			$('.radio_toggle_syring_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Congestion*/
		$('.radio_toggle_cong1_ri8').click(function(){
			$('.radio_toggle_cong1_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Tear*/
		$('.radio_toggle_cong2_ri8').click(function(){
			$('.radio_toggle_cong2_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*CONJUNCTIVA	sub */
		$('.radio_toggle_cong_sr').click(function(){
			$('.radio_toggle_cong_sr').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Conjuctival Bleb*/
		$('.radio_toggle_cong3_ri8').click(function(){
			$('.radio_toggle_cong3_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*SubConjunctival Haemorrhage*/
		$('.radio_toggle_cong4_ri8').click(function(){
			$('.radio_toggle_cong4_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Foreign Body*/
		$('.radio_toggle_cong5_ri8').click(function(){
			$('.radio_toggle_cong5_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Follicles*/
		$('.radio_toggle_cong6_ri8').click(function(){
			$('.radio_toggle_cong6_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	
		/*Papillae*/
		$('.radio_toggle_cong7_ri8').click(function(){
			$('.radio_toggle_cong7_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});	

		/*Pinguecula*/
		$('.radio_toggle_cong8_ri8').click(function(){
			$('.radio_toggle_cong8_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Pterygium*/
		$('.radio_toggle_cong9_ri8').click(function(){
			$('.radio_toggle_cong9_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Pterygium*/
		$('.radio_toggle_cong9_ri8').click(function(){
			$('.radio_toggle_cong9_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Phlycten*/
		$('.radio_toggle_cong10_ri8').click(function(){
			$('.radio_toggle_cong10_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Discharge*/
		$('.radio_toggle_cong11_ri8').click(function(){
			$('.radio_toggle_cong11_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
		});
		/*Size*/
		$('.radio_toggle_crna1_ri8').click(function(){
			$('.radio_toggle_crna1_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info');
		});	
		/*Shape*/
		$('.radio_toggle_crna2_ri8').click(function(){
			$('.radio_toggle_crna2_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info');
		});
		/*Surface*/
		$('.cornea_sur_ri8').click(function(){
			$('#cornea_nrml_ri8').prop('checked',false).parent().removeClass('btn-info');
			$(this).parent().toggleClass('btn-info');  
		});
		$('#cornea_nrml_ri8').click(function(){   
			$(this).prop('checked',true).parent().addClass('btn-info');
			$('.cornea_sur_ri8').prop('checked',false).parent().removeClass('btn-info');
		});
		/*Depth*/
		$('.examnsn_ac_r_defth').click(function(){
			$('.examnsn_ac_r_defth').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info');
		});

		/*Cells*/
		$('.radio_toggle_antch1_ri8').click(function(){
			$('.radio_toggle_antch1_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info');
			if($(this).val()=='Yes') 
				$('#radio_toggle_antch1_ri8').show();
			else
				$('#radio_toggle_antch1_ri8').hide();
		});	
		/*Flare*/
		$('.radio_toggle_antch2_ri8').click(function(){
			$('.radio_toggle_antch2_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
			if($(this).val()=='Yes') 
				$('#radio_toggle_antch2_ri8').show();
			else
				$('#radio_toggle_antch2_ri8').hide();
		});	
		/*Hyphema*/
		$('.radio_toggle_antch3_ri8').click(function(){
			$('.radio_toggle_antch3_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
			if($(this).val()=='Yes') 
				$('#radio_toggle_antch3_ri8').show();
			else
				$('#radio_toggle_antch3_ri8').hide();
		});
		/*Hypopyon*/
		$('.radio_toggle_antch4_ri8').click(function(){
			$('.radio_toggle_antch4_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
			if($(this).val()=='Yes') 
				$('#radio_toggle_antch4_ri8').show();
			else
				$('#radio_toggle_antch4_ri8').hide();
		});	
		/*Foreign Body*/
		$('.radio_toggle_antch5_ri8').click(function(){
			$('.radio_toggle_antch5_ri8').parent().removeClass('btn-info');
			$(this).parent().addClass('btn-info'); 
			if($(this).val()=='Yes') 
				$('#radio_toggle_antch5_ri8').show();
			else
				$('#radio_toggle_antch5_ri8').hide();
		});
		/*Shape*/
		$('.shape_ri8').on('click', function(){
				$('.shape_ri8').removeClass('btn-info');
				$(this).addClass('btn-info');
			});	
		/*	Reaction to light Direct*/
			$('.shape2_ri8').on('click', function(){
				$('.shape2_ri8').removeClass('btn-info');
				$(this).addClass('btn-info');
			});	
			/*Reaction to light consensual*/
			$('.shape3_ri8').on('click', function(){
				$('.shape3_ri8').removeClass('btn-info');
				$(this).addClass('btn-info');
			});
			/*Afferent pupillary defect (RAPD)*/
			$('.radio_toggle_apd1_ri8').click(function(){
				$('.radio_toggle_apd1_ri8').parent().removeClass('btn-info');
				$(this).parent().addClass('btn-info'); 
			});
			/*Shape*/
			$('.radio_toggle_iris1_ri8').click(function(){
				$('.radio_toggle_iris1_ri8').parent().removeClass('btn-info');
				$(this).parent().addClass('btn-info'); 
			});
			/*Neovascularisation (NVI)*/
			$('.radio_toggle_iris2_ri8').click(function(){
				$('.radio_toggle_iris2_ri8').parent().removeClass('btn-info');
				$(this).parent().addClass('btn-info'); 
			});
			/*Synechiae*/
			$('.radio_toggle_iris3_ri8').click(function(){
				$('.radio_toggle_iris3_ri8').parent().removeClass('btn-info');
				$(this).parent().addClass('btn-info'); 
			});
			/*Nature*/
			$('.radio_toggle_lens1_ri8').click(function(){
				$('.radio_toggle_lens1_ri8').parent().removeClass('btn-info');
				$(this).parent().addClass('btn-info'); 
				if($(this).val()=='Cataract')
				{
					$('#nat_ap_r').show();
				}
				else{
					$('#nat_ap_r').hide();
				}
			});
			/*Position*/
			$('.radio_toggle_lens2_ri8').click(function(){
				$('.radio_toggle_lens2_ri8').parent().removeClass('btn-info');
				$(this).parent().addClass('btn-info'); 
			});
			/*Size*/
			$('.radio_toggle_lens3_ri8').click(function(){
				$('.radio_toggle_lens3_ri8').parent().removeClass('btn-info');
				$(this).parent().addClass('btn-info'); 
			});
			/*Uniocular movements*/
			$('.btn_uni_ri8').click(function(){
				$('.btn_uni_ri8').parent().removeClass('btn-info');
				$(this).prop('checked',true).parent().toggleClass('btn-info');  
			});
			/*Binocular movements*/
			$('.radio_toggle_ems2_ri8').click(function(){
				$('.radio_toggle_ems2_ri8').parent().removeClass('btn-info');
				$(this).parent().addClass('btn-info'); 
			});
			/*Squint*/
			$('.btn_squin_ri8').click(function(){
				$('.btn_squin_ri8').removeClass('btn-info');
				$(this).addClass('btn-info');
			});
			$('.btn_squin_ri8:first-child').click(function(){
				$('.tropia_ri8').show();
				$('.phoria_ri8').hide();
			});
			$('.btn_squin_ri8:last-child').click(function(){
				$('.tropia_ri8').hide();
				$('.phoria_ri8').show();											
				$('.horizontal_ri8').hide();
				$('.vertical_ri8').hide();
				$('.paralytic_ri8').hide();
				$('.esotropia_ET_ri8').hide();
			});
			/*Tropia*/
			$('.btn_tropia_ri8').click(function(){
				$('.btn_tropia_ri8').removeClass('btn-info');
				$(this).addClass('btn-info');
			});
			$('.btn_tropia_ri8:first-child').click(function(){
				$('.horizontal_ri8').show();
				$('.vertical_ri8').hide();
				$('.paralytic_ri8').hide();
				$('.exotropia_XT_ri8').hide();
				$('.esotropia_ET_ri8').hide();

			});
			$('.btn_tropia_ri8:nth-child(2)').click(function(){
				$('.horizontal_ri8').hide();
				$('.vertical_ri8').show();
				$('.paralytic_ri8').hide();
				$('.exotropia_XT_ri8').hide();
				$('.esotropia_ET_ri8').hide();
			});
			$('.btn_tropia_ri8:last-child').click(function(){
				$('.horizontal_ri8').hide();
				$('.vertical_ri8').hide();
				$('.paralytic_ri8').show();
				$('.exotropia_XT_ri8').hide();
				$('.esotropia_ET_ri8').hide(); 
			});
			$('.btn_phoria_ri8').click(function(){
				$('.btn_phoria_ri8').removeClass('btn-info');
				$(this).addClass('btn-info');
			});
			/*Horizontal*/
			$('.btn_horizontal_ri8').click(function(){
				$('.btn_horizontal_ri8').removeClass('btn-info');
				$(this).addClass('btn-info');
			});
			$('.btn_horizontal_ri8:first-child').click(function(){
				$('.esotropia_ET_ri8').show(); 
				$('.exotropia_XT_ri8').hide(); 
			});
			$('.btn_horizontal_ri8:last-child').click(function(){
				$('.esotropia_ET_ri8').hide(); 
				$('.exotropia_XT_ri8').show(); 
			});
			/*Esotropia(ET)*/
			$('.btn_esotropia_ET_ri8').click(function(){
				$('.btn_esotropia_ET_ri8').removeClass('btn-info');
				$(this).addClass('btn-info');
			});
			/*Esotropia(XT)*/
			$('.btn_exotropia_XT_ri8').click(function(){
				$('.btn_exotropia_XT_ri8').removeClass('btn-info');
				$(this).addClass('btn-info');
			});
			/*Vertical*/
			$('.btn_vertical_ri8').click(function(){
				$('.btn_vertical_ri8').removeClass('btn-info');
				$(this).addClass('btn-info');
			});
			/*Paralytic*/
			$('.btn_paralytic_ri8').click(function(){
				$(this).parent().toggleClass('btn-info');  
			});	
			/*FUNDUS*/
			$('.toggle_fundus_ri8_nrml').click(function(){
				$(this).parent().toggleClass('btn-info');
				$('.toggle_fundus_ri8').show();
				$('.fa_toggle23_ri8').addClass('fa-minus'); 
				if($(this).prop('checked') == true)
				{
					$('#examnsn_fundus_r_mda').val('Clear');
					$('#examnsn_fundus_r_pvd').val('Absent');
					$('#examnsn_fundus_r_ods').val('Medium');
					$('#examnsn_fundus_r_cdr').val('0.3');
					$('#examnsn_fundus_r_opdisc').val('healthy');
					$('#examnsn_fundus_r_bldvls_txt').val('normal');
					$('#examnsn_fundus_r_mcla_txt').val('foveal reflex present');
					$('#examnsn_fundus_r_fnds').val('within normal limits');
				}
				else{
					$('#examnsn_fundus_r_mda').val('');
					$('#examnsn_fundus_r_pvd').val('');
					$('#examnsn_fundus_r_ods').val('');
					$('#examnsn_fundus_r_cdr').val('');
					$('#examnsn_fundus_r_opdisc').val('');
					$('#examnsn_fundus_r_bldvls_txt').val('');
					$('#examnsn_fundus_r_mcla_txt').val('');
					$('#examnsn_fundus_r_fnds').val('');
				}
			});	

			/* fundus dialet*/
			$('.radio_toggle_r_dialet').click(function(){
				$('.radio_toggle_r_dialet').parent().removeClass('btn-info');
				$(this).parent().addClass('btn-info'); 
			});																					
			var eyelids_l= '<?php echo $exam_apnds['examnsn_apndgs_l_eyelids'];?>';
			var eyelshs_l= '<?php echo $exam_apnds['examnsn_apndgs_l_eyelshs'];?>';
			var lcmlc_l= '<?php echo $exam_apnds['examnsn_apndgs_l_lcmlc'];?>';
			var syrn_l= '<?php echo $exam_apnds['examnsn_apndgs_l_syrn'];?>';
			var eyelids_r= '<?php echo $exam_apnds['examnsn_apndgs_r_eyelids'];?>';
			var eyelshs_r= '<?php echo $exam_apnds['examnsn_apndgs_r_eyelshs'];?>';
			var lcmlc_r= '<?php echo $exam_apnds['examnsn_apndgs_r_lcmlc'];?>';
			var syrn_r= '<?php echo $exam_apnds['examnsn_apndgs_r_syrn'];?>';
			var conjtv_l= '<?php echo $exam_conjtv['examnsn_conjtv_l_consn'];?>';
			var conjtv_r= '<?php echo $exam_conjtv['examnsn_conjtv_r_consn'];?>';
			if(eyelids_l=='Eyelids')
				$('.toggle_appen_a').show();
			if(eyelshs_l=='Eyelashes')	
				$('.toggle_appen_a_b').show();
			if(lcmlc_l=='Lacrimal sac')	
				$('.toggle_appen_a_c').show();
			if(syrn_l=='Syringing')
				$('.toggle_appen_a_d').show();
			if(eyelids_r=='Eyelids')	
				$('.toggle_appen_a_ri8').show();
			if(eyelshs_r=='Eyelashes')
				$('.toggle_appen_a_b_ri8').show();
			if(lcmlc_r=='Lacrimal sac')
				$('.toggle_appen_a_c_ri8').show();
			if(syrn_r=='Syringing')	
				$('.toggle_appen_a_d_ri8').show();
			if(conjtv_l=='Yes')
				$('.toggle_conj_conges_l').show();
			if(conjtv_r=='Yes')
				$('.toggle_conj_conges_r').show();		
			var edit='<?php echo $pres_id;?>';
			if(edit !='' &&  edit >1)
			{
				$('.toggle_appearance').show();$('.fa_toggle1').addClass('fa-minus');
				$('.toggle_appen').show();$('.fa_toggle3').addClass('fa-minus');
				$('.toggle_conj').show();$('.fa_toggle5').addClass('fa-minus');	
				$('.toggle_cornea').show();$('.fa_toggle7').addClass('fa-minus');
				$('.toggle_ant_ch').show();$('.fa_toggle9').addClass('fa-minus');
				$('.toggle_pupil').show();$('.fa_toggle11').addClass('fa-minus');
				$('.toggle_iris').show();$('.fa_toggle13').addClass('fa-minus');
				$('.toggle_lens').show();$('.fa_toggle15').addClass('fa-minus');
				$('.toggle_extra').show();$('.fa_toggle17').addClass('fa-minus');
				$('.toggle_iop').show();$('.fa_toggle19').addClass('fa-minus');
				$('.toggle_gonio').show();$('.fa_toggle21').addClass('fa-minus');
				$('.toggle_fundus').show();$('.fa_toggle23').addClass('fa-minus');

				$('.toggle_appearance_ri8').show();$('.fa_toggle1_ri8').addClass('fa-minus');
				$('.toggle_appen_ri8').show();$('.fa_toggle3_ri8').addClass('fa-minus');
				$('.toggle_conj_ri8').show();$('.fa_toggle5_ri8').addClass('fa-minus');	
				$('.toggle_cornea_ri8').show();$('.fa_toggle7_ri8').addClass('fa-minus');
				$('.toggle_ant_ch_ri8').show();$('.fa_toggle9_ri8').addClass('fa-minus');
				$('.toggle_pupil_ri8').show();$('.fa_toggle11_ri8').addClass('fa-minus');
				$('.toggle_iris_ri8').show();$('.fa_toggle13_ri8').addClass('fa-minus');
				$('.toggle_lens_ri8').show();$('.fa_toggle15_ri8').addClass('fa-minus');
				$('.toggle_extra_ri8').show();$('.fa_toggle17_ri8').addClass('fa-minus');
				$('.toggle_iop_ri8').show();$('.fa_toggle19_ri8').addClass('fa-minus');
				$('.toggle_gonio_ri8').show();$('.fa_toggle21_ri8').addClass('fa-minus');
				$('.toggle_fundus_ri8').show();$('.fa_toggle23_ri8').addClass('fa-minus');

				$('.opm1').change(function() {
					 $('#opm1').val('red');
					});
				$('.opm2').change(function() {
					  $('#opm2').val('red');
					});
				$('.opm3').change(function() {
					  $('#opm3').val('red');
					});
				$('.opm4').change(function() {
					  $('#opm4').val('red');
					});
				$('.opm5').change(function() {
					  $('#opm5').val('red');
					});
				$('.opm6').change(function() {
					  $('#opm6').val('red');
					});
				$('.opm7').change(function() {
					  $('#opm7').val('red');
					});
				$('.opm8').change(function() {
					  $('#opm8').val('red');
					});
				$('.opm9').change(function() {
					  $('#opm9').val('red');
					});
				$('.opm10').change(function() {
					  $('#opm10').val('red');
					});
				$('.opm11').change(function() {
					  $('#opm11').val('red');
					});
				$('.opm12').change(function() {
					  $('#opm12').val('red');
					});
				$('.opm13').change(function() {
					  $('#opm13').val('red');
					});
				$('.opm14').change(function() {
					  $('#opm14').val('red');
					});
				$('.opm15').change(function() {
					  $('#opm15').val('red');
					});
				$('.opm16').change(function() {
					  $('#opm16').val('red');
					});
				$('.opm17').change(function() {
					  $('#opm17').val('red');
					});
				$('.opm18').change(function() {
					  $('#opm18').val('red');
					});
				
				$('.opm21').change(function() {
					  $('#opm21').val('red');
					});
				$('.opm22').change(function() {
					  $('#opm22').val('red');
					});
				$('.opm23').change(function() {
					  $('#opm23').val('red');
					});
				$('.opm24').change(function() {
					  $('#opm24').val('red');
					});       

			}

			var ac_l_defth ='<?php echo $exam_atrch['examnsn_ac_l_defth'];?>';
			var ac_l_cells ='<?php echo $exam_atrch['examnsn_ac_l_cells'];?>';
			var ac_l_flar ='<?php echo $exam_atrch['examnsn_ac_l_flar'];?>';
			var ac_l_hyfma ='<?php echo $exam_atrch['examnsn_ac_l_hyfma'];?>';
			var ac_l_hypn ='<?php echo $exam_atrch['examnsn_ac_l_hypn'];?>';
			var ac_l_fbd ='<?php echo $exam_atrch['examnsn_ac_l_fbd'];?>';
			if(ac_l_defth=='Shallow')
				$('#abc_btn_input1').show();
			if(ac_l_defth=='Deep')
				$('#abc_btn_input2').show();
			if(ac_l_cells=='Yes')
				$('#radio_toggle_antch1').show();
			if(ac_l_flar=='Yes')
			 $('#radio_toggle_antch2').show();
		    if(ac_l_hyfma=='Yes')
			 $('#radio_toggle_antch3').show();
			if(ac_l_hypn=='Yes')
			 $('#radio_toggle_antch4').show();
		    if(ac_l_fbd=='Yes')
			 $('#radio_toggle_antch5').show();

			var ac_r_defth ='<?php echo $exam_atrch['examnsn_ac_r_defth'];?>';
			var ac_r_cells ='<?php echo $exam_atrch['examnsn_ac_r_cells'];?>';
			var ac_r_flar ='<?php echo $exam_atrch['examnsn_ac_r_flar'];?>';
			var ac_r_hyfma ='<?php echo $exam_atrch['examnsn_ac_r_hyfma'];?>';
			var ac_r_hypn ='<?php echo $exam_atrch['examnsn_ac_r_hypn'];?>';
			var ac_r_fbd ='<?php echo $exam_atrch['examnsn_ac_r_fbd'];?>';
			if(ac_r_defth=='Shallow')
				$('#abc_btn_input1_ri8').show();
			if(ac_r_defth=='Deep')
				$('#abc_btn_input2_ri8').show();
			if(ac_r_cells=='Yes')
				$('#radio_toggle_antch1_ri8').show();
			if(ac_r_flar=='Yes')
			 $('#radio_toggle_antch2_ri8').show();
		    if(ac_r_hyfma=='Yes')
			 $('#radio_toggle_antch3_ri8').show();
			if(ac_r_hypn=='Yes')
			 $('#radio_toggle_antch4_ri8').show();
		    if(ac_r_fbd=='Yes')
			 $('#radio_toggle_antch5_ri8').show();
	});
$(document).on('input', '#examnsn_myRange_l', function() {
    $('#examnsn_iop_l_value').val($(this).val());
});
$(document).on('input', '#examnsn_myRange_r', function() {
    $('#examnsn_iop_r_value').val($(this).val() );
});
 </script>



