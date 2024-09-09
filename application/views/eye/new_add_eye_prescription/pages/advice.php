<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script>
<style>
	.adBG{background:#2a854f;color:#fff;}
</style>

<section> 
	<ul class="nav nav-pills eye-tabs text-center">
		<li class="col-md-2" role="presentation"><a href="#medication" data-toggle="tab" class="adBG active">Medication</a></li>
		<li class="col-md-2" role="presentation"><a href="#procedures" data-toggle="tab" class="adBG">Procedures</a></li>
		<li class="col-md-2" role="presentation"><a href="#referral" data-toggle="tab" class="adBG">Referral</a></li>
		<li class="col-md-2" role="presentation"><a href="#advices" data-toggle="tab" class="adBG">Advice</a></li>
	</ul>
</section>
<script>
	$(document).ready(function(){

		$('.adBG').click(function(){
			$('.adBG').removeClass('active');
			$(this).addClass('active');
		});

		CKEDITOR.replace( 'advicess_cke', {
		    fullPage: true, 
		    allowedContent: true,
		    autoGrow_onStartup: true,
		    enterMode: CKEDITOR.ENTER_BR
		});

		var pres_id='<?php echo $pres_id;?>';
		if(pres_id !='')
		{
			var val='<?php echo $advice_adv['days'];?>';
			var radios3 = $('.advs_next_app');
			radios3.filter('[value="'+val+'"]').prop('checked', true).parent().addClass('active');
		}
	});
</script>
<br>
<?php $qtyoption = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,20,25,30,35,40,45,50);
	  $freoption = array(1,2,3,4,5,6,8,10,12,18,24,'SOS');
	  $instruct = array('One time a day','Morning afternoon and night','Morning and evening','Only at night', 'In Morning','In Afternoon','In Night','Before food','After Meal','Empty stomach','Every one hour','Every two hour','Every three hour','Every four hour','Every five hour','Every six hour','Five time in a day','SOS','Add a text Box');

	  ?>


<div class="tab-content" style="border:none;">
	<!--------Row Start------>		
	<div class="tab-pane active" id="medication">
		<div class="row">
			<div class="col-lg-12">
				<table class="table table-bordered text-center" id="medic_set">
					<thead >
						<tr>
							<th class="text-center" width="200">Name</th>
							<th class="text-center" width="100">Type</th>
							<th class="text-center">Quantity</th>
							<th class="text-center">Frequency</th>
							<th class="text-center">Duration</th>
							<th class="text-center">Taper</th>
							<th class="text-center">Eye</th>
							<th class="text-center">Instruction</th>
							<th class="text-center"></th>								
						</tr>
					</thead>
					
					<tbody id="medication_body">
						<?php 
						 if(!empty($advice_medication)){
							foreach ($advice_medication as $key => $medication) { ?>
							<tr id="appends_<?php echo $key;?>">
							<td class="text-left" valign="top" style="vertical-align:top;position:relative;">
								<input id="mname_<?php echo $key;?>" type="text" name="advs[medication][<?php echo $key;?>][mname]" class="medicine_name" value="<?php echo $medication->mname;?>" onkeyup="search_func('<?php echo $key;?>');">
								<div class="append_box_medi_<?php echo $key;?> advs_append_box">
								</div>
								<div class="small label label-danger d-none" id="fill_med_<?php echo $key;?>">Fill Medicine</div> <div class="small label label-info d-none" id="medavailqty_<?php echo $key;?>"></div>
							</td>
							<td valign="top" style="vertical-align:top;">
								<input name="advs[medication][<?php echo $key;?>][mtype]" value="<?php echo $medication->mtype;?>" id="mtype_<?php echo $key;?>" type="text" class="">
							</td>
							<td valign="top" style="vertical-align:top;">
								<select name="advs[medication][<?php echo $key;?>][mqty]" onchange="return validate_qty(this.value,'<?php echo $key;?>');" id="mqty_<?php echo $key;?>" class="w-50px form-control">
									<option value="">Sel</option>
									<option <?php if($medication->mqty=='1/4'){ echo 'selected'; }?> value="1/4">1/4</option>
									<option <?php if($medication->mqty=='1/2'){ echo 'selected'; }?> value="1/2">1/2</option>
									<?php foreach ($qtyoption as $opt) { ?>
										<option <?php if($medication->mqty==$opt){ echo 'selected'; }?> value="<?php echo $opt;?>"><?php echo $opt;?></option>
									<?php } ?>
								</select>
								<div class="small label label-danger" id="qterr_<?php echo $key;?>"></div>
							</td> 
							<td valign="top" style="vertical-align:top;">
								<select name="advs[medication][<?php echo $key;?>][mfrq]" id="mfreq_<?php echo $key;?>" class="w-50px">
									<option value="">Sel</option>
									<?php foreach ($freoption as $opt) { ?>
										<option  <?php if($medication->mfrq==$opt){ echo 'selected'; }?> value="<?php echo $opt;?>"><?php echo $opt;?></option>
									<?php } ?>
								</select>
							</td>  
							<td valign="top" style="vertical-align:top;" class="text-left">
								<div style="display:flex;justify-content:space-between;">
									<select name="advs[medication][<?php echo $key;?>][mdur]" id="mdur_<?php echo $key;?>" style="width:49%">
										<option value="">Sel</option>
										<?php foreach ($qtyoption as $opt) { ?>
											<option  <?php if($medication->mdur==$opt){ echo 'selected'; }?> value="<?php echo $opt;?>"><?php echo $opt;?></option>
										<?php } ?>
									</select>
								
									<select name="advs[medication][<?php echo $key;?>][mdurd]" id="mdurd_<?php echo $key;?>" style="width:49%">
										<option value="">Sel</option>
										<option  <?php if($medication->mdurd=='D'){ echo 'selected'; }?> value="D">Days</option>
										<option  <?php if($medication->mdurd=='W'){ echo 'selected'; }?> value="W">Weeks</option>
										<option  <?php if($medication->mdurd=='M'){ echo 'selected'; }?> value="M">Months</option>
										<option  <?php if($medication->mdurd=='F'){ echo 'selected'; }?> value="F">Next followup</option>
									</select>
								</div>

								<div class="small label label-danger d-none" id="fill_dur_<?php echo $key;?>">Fill  Duration</div>
								<div class="small label label-danger d-none" id="fill_durdw_<?php echo $key;?>">Select Days OR Week</div>
							</td> 
							<td valign="top" style="vertical-align:top;padding-top:6px;">
								<!-- <a href="javascript:void(0);" onclick="open_tapper('<?php echo $key;?>');" class="btn-new"><i class="fa fa-plus"></i> Add</a> -->
								<a href="javascript:void(0);" onclick="open_edit_tapper('<?php echo $key;?>','<?php echo $pres_id;?>','<?php echo $medication->med_id;?>');" class="btn-new"><i class="fa fa-pencil"></i> Edit</a>
								
							</td>  
							<td valign="top" style="vertical-align:top;">
								<select name="advs[medication][<?php echo $key;?>][eside]"  id="meye_<?php echo $key;?>" class="w-50px">
									<option value="">Sel</option>
									<option <?php if($medication->eside=='L'){ echo 'selected'; }?> value="L">L</option>
									<option <?php if($medication->eside=='R'){ echo 'selected'; }?> value="R">R</option>
									<option <?php if($medication->eside=='BE'){ echo 'selected'; }?> value="BE">BE</option>
								</select>
							</td>
							<td valign="top" style="vertical-align:top;">
								<select name="advs[medication][<?php echo $key;?>][minst]" id="minst_<?php echo $key;?>" class="w-50px">
									<option value="">Sel</option>
									<?php foreach ($instruct as $inst) { ?>
										<option <?php if($medication->minst==$inst){ echo 'selected'; }?> value="<?php echo $inst;?>"><?php echo $inst;?></option>
										<?php } ?>
								</select>
							</td> 
							<td valign="top" style="vertical-align:top;padding-top:6px;">
								<?php if($key==0) { ?>
								  <a href="javascript:void(0);" onclick="append_medicine();" class="btn-new"><i class="fa fa-plus"></i></a>
								<?php } else if($key>0) { ?>
								  <a href="javascript:void(0);" onclick="remove_medication('<?php echo $key;?>');" class="btn-new"><i class="fa fa-times"></i></a>
							   <?php } ?>

							</td> 
							<input type="hidden" id="med_id_<?php echo $key;?>" name="advs[medication][<?php echo $key;?>][med_id]" value='<?php echo $medication->med_id;?>' >  								
							</tr>
						<?php } } else { ?>

						<tr>
							<td class="text-left" valign="top" style="vertical-align:top;position:relative;">
								<input id="mname_0" type="text" name="advs[medication][0][mname]" class="medicine_name" onkeyup="search_func(0);">
								<div class="append_box_medi_0 advs_append_box">
								</div>
								<div class="small label label-danger d-none" id="fill_med_0">Fill Medicine</div><div class="small label label-info d-none" id="medavailqty_0"></div>
							</td>
							<td valign="top" style="vertical-align:top;">
								<input name="advs[medication][0][mtype]" id="mtype_0" type="text" class="">
							</td>
							<td valign="top" style="vertical-align:top;">
								<select name="advs[medication][0][mqty]" onchange="return validate_qty(this.value,'0');" id="mqty_0" class="w-50px form-control">
									<option value="">Sel</option>
									<option value="1/4">1/4</option>
									<option value="1/2">1/2</option>
									<?php foreach ($qtyoption as $opt) { ?>
										<option value="<?php echo $opt;?>"><?php echo $opt;?></option>
									<?php } ?>
								</select>
								<div class="small label label-danger" id="qterr_0"></div>
							</td> 
							<td valign="top" style="vertical-align:top;">
								<select name="advs[medication][0][mfrq]" id="mfreq_0" class="w-50px">
									<option value="">Sel</option>
									<?php foreach ($freoption as $opt) { ?>
										<option value="<?php echo $opt;?>"><?php echo $opt;?></option>
									<?php } ?>
								</select>
							</td>  
							<td valign="top" style="vertical-align:top;" class="text-left">
								<div style="display:flex;justify-content:space-between;">
									<select name="advs[medication][0][mdur]" id="mdur_0" style="width:49%">
										<option value="">Sel</option>
										<?php foreach ($qtyoption as $opt) { ?>
											<option value="<?php echo $opt;?>"><?php echo $opt;?></option>
										<?php } ?>
									</select>
								
									<select name="advs[medication][0][mdurd]" id="mdurd_0" style="width:49%">
										<option value="">Sel</option>
										<option value="D">Days</option>
										<option value="W">Weeks</option>
										<option value="M">Months</option>
										<option value="F">Next followup</option>
									</select>
								</div>

								<div class="small label label-danger d-none" id="fill_dur_0">Fill  Duration</div>
								<div class="small label label-danger d-none" id="fill_durdw_0">Select Days OR Week</div>
							</td> 
							<td valign="top" style="vertical-align:top;padding-top:6px;">
								<a href="javascript:void(0);" onclick="open_tapper(0);" class="btn-new"><i class="fa fa-plus"></i> Add</a>
							</td>  
							<td valign="top" style="vertical-align:top;">
								<select name="advs[medication][0][eside]"  id="meye_0" class="w-50px">
									<option value="">Sel</option>
									<option value="L">L</option>
									<option value="R">R</option>
									<option value="BE">BE</option>
								</select>
							</td>
							<td valign="top" style="vertical-align:top;">
								<select name="advs[medication][0][minst]" id="minst_0" class="w-50px">
									<option value="">Sel</option>
									<?php foreach ($instruct as $inst) { ?>
										<option value="<?php echo $inst;?>"><?php echo $inst;?></option>
										<?php } ?>
								</select>
							</td> 
							<td valign="top" style="vertical-align:top;padding-top:6px;">
								<a href="javascript:void(0);" onclick="append_medicine();" class="btn-new"><i class="fa fa-plus"></i></a>
							</td>  			
							<input type="hidden" id="med_id_0" name="advs[medication][0][med_id]"> 					
						</tr>
					<?php } ?>
					</tbody>

				</table>
		
				<div class="row">
					<div class="col-md-9">
						<div class="row">
							<div class="col-md-2">
								<label>Comments:</label>
							</div>
							<div class="col-md-7">
								<textarea name="advs[comments][medi_comm]" class="form-control"><?php echo $advice_comm['medi_comm'];?></textarea>			
							</div>
							<div class="col-md-3">
								<ul class="list-unstyled">									
									<li class="m-b-5"> <span class="label-icon">P</span> <span> Pharmacy</span></li>
									<li class="m-b-5"> <span class="label-icon">D</span> <span> CIMS Drug Index</span></li>
									<li class="m-b-5"> <span class="label-icon">M</span> <span>My Medication List</span></li>
								</ul> 			
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<label>Glasses:<input type="checkbox" onchange="return appear_boxs();" name="advs[comments][appear_check]" id="appear_check" <?php if($advice_comm['appear_check']=="1"){ echo 'checked';}?> value="<?php if($advice_comm['appear_check']=="1"){ echo '1';} else{ echo '0'; }?>"></label>
							</div>
							<div class="col-md-7">
								<textarea name="advs[comments][appear_box]" id="appear_box" class="form-control <?php if($advice_adv['appear_check']=="1"){ echo 'd-box';} else{ echo 'd-none'; }?>"><?php echo $advice_comm['appear_box'];?></textarea>			
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<label>Medication Sets</label>	
						<select onclick="get_datas(this.value);" multiple class="form-control">
							<?php if(!empty($medicine_sets)){
							 foreach ($medicine_sets as $key => $sets_data) {?>						
							<option value="<?php echo $sets_data['id'];?>"><?php echo $sets_data['set_name'];?></option>
							<?php }} ?>
						</select>
					</div>

					
				</div>
			</div>
		</div>

		
	
	</div>

	<!--------Row Start----->			
	<div class="tab-pane" id="procedures">
		<section>
		<!-- 	<br><br>
		<div class="row text-center">
			<div class="col-lg-4">
				<label class="btn btn-sm"> <input type="radio" name=""><span>Commonly used Procedures</span></label>							
			</div>
			<div class="col-lg-4">
				<label class="btn btn-sm"> <input type="radio" name=""><span>Custom made Procedures</span></label>							
			</div>
		</div> 	
		
		
		<br> -->
			<div class="row">
				<div class="col-md-12">
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="ophthal">   					

							<br>	
							<div class="row">
								<div class="col-lg-3 col-md-4 col-sm-6">
									<div>Eye Region</div>
									<select name="provisional_diagnosis" id="advs_eye_rgn" class="form-control" multiple="">
									<?php
										if(!empty($procedure_eye_region))
										{
											foreach($procedure_eye_region as $region)
											{
												$region_select = "";
												if($region->id==$form_data['eye_region_test_head'])
												{
													$region_select = "selected='selected'";
												}
												echo '<option value="'.$region->id.'" '.$region_select.'>'.$region->test_heads.'</option>';
											}
										}
									?>
									</select>
								</div>

								<div class="col-lg-3 col-md-4 col-sm-6" id="advs_proc_box">
									<div>Procedure</div>
									<select name="provisional_diagnosis" id="advs_proces" class="form-control" multiple="">
										<?php
										if(!empty($ophthal_investigations))
										{
											foreach($ophthal_investigations as $ophthal_investigation)
											{
												$investigation_select = "";
												if($ophthal_investigation->id==$form_data['ophthal_investigation'])
												{
													$investigation_select = "selected='selected'";
												}
												echo '<h5 '.$investigation_select.'>'.$ophthal_investigation->test_heads.'</h5>';
											}
										}
										?>
									</select>
								</div>

							<div class="col-lg-6 col-md-4 col-sm-6" id="advs_proc_box">
									<div class="row">
										<div class="col-md-6">
											<div><span class="text-danger">A</span> - Advised  |  <span class="text-success">P</span> - Performed</div>
										</div>
										<div class="col-md-6">
											<input type="text" placeholder="Search Procedure" id="procedure" onkeyup="search_procedure(this.value);" class="form-control">
											 <div class="search_dropdown_list" id="procedure_list">
                            
                        					 </div>
										</div>
									</div>
									<br>
									<div class="row m-t-5">
										<div class="col-md-12">
											<table class="table table-bordered" id="table_advs_proces">
												<?php if(!empty($advice_procedure)){
												foreach ($advice_procedure as $key => $proce) { ?>
													<tr id="apnd_advs_proces_<?php echo $key;?>">
														<td style="width:auto;text-align:left;">							<div class="text-bold">
															<span class="text-success">P -</span> <?php echo $proce->test_name .' - '.$proce->eye_side.' Eyes - '.$proce->pros_id; ?></div>	<ul class="small" style="list-style:disc;padding-left:20px;"><?php if(!empty($proce->p_comm)){ echo '<li>'.$proce->p_comm.'</li>';}?> <?php if(!empty($proce->u_comm)){ echo '<li>'.$proce->u_comm.'</li>';}?></ul class="small">
																<hr>
																<div class="small text-bold">Added by <?php echo $proce->ent_by;?> on <?php echo $proce->tdntime;?> </div>
																<div class="small text-bold">Performed by <?php echo $proce->pref_by_name.'('.$proce->surg_date.')';?></div>
															</td>
															<td width="100" valign="top" style="vertical-align:top;"><a href="javascript:void(0);" onclick="edit_advs_proces('<?php echo $key;?>')" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a><a href="javascript:void(0);" onclick="delete_advs_proces('<?php echo $key;?>')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
															</td>
																<input type="hidden" value="<?php echo $key;?>" name="advs[procedures][<?php echo $key;?>][pros_id]" id="ed_id_<?php echo $key;?>"><input type="hidden" value="<?php echo $proce->test_name;?>" name="advs[procedures][<?php echo $key;?>][test_name]" id="ed_tname_<?php echo $key;?>"><input type="hidden" value="<?php echo $proce->eye_side;?>" name="advs[procedures][<?php echo $key;?>][eye_side]" id="ed_tside_<?php echo $key;?>"><input type="hidden" value="<?php echo $proce->surg_date;?>" name="advs[procedures][<?php echo $key;?>][surg_date]" id="ed_tsdate_<?php echo $key;?>"><input type="hidden" value="<?php echo $proce->pref_by;?>" name="advs[procedures][<?php echo $key;?>][pref_by]" id="ed_tperby_<?php echo $key;?>"><input type="hidden" value="<?php echo $proce->pref_by_name;?>" name="advs[procedures][<?php echo $key;?>][pref_by_name]" id="ed_tperby_name_<?php echo $key;?>"> <input type="hidden" value="<?php echo $proce->sno_code;?>" name="advs[procedures][<?php echo $key;?>][sno_code]" id="ed_tsnocode_<?php echo $key;?>"><input type="hidden" value="<?php echo $proce->p_comm;?>" name="advs[procedures][<?php echo $key;?>][p_comm]" id="ed_tpcomm_<?php echo $key;?>"><input type="hidden" value="<?php echo $proce->u_comm;?>" name="advs[procedures][<?php echo $key;?>][u_comm]" id="ed_tucomm_<?php echo $key;?>"><input type="hidden" value="<?php echo $proce->ent_by;?>" name="advs[procedures][<?php echo $key;?>][ent_by]" id="ed_tenby_<?php echo $key;?>"><input type="hidden" value="<?php echo $proce->tdntime;?>" name="" id="ed_tdnt_<?php echo $key;?>">
													</tr>
												<?php }} ?>
											</table>
										</div>
									</div>
								</div>
							


							</div>                         
							<br>
							<div class="row">
								<div class="col-md-2">
									<label>Comments:</label>
								</div>

								<div class="col-md-7">
									<textarea name="advs[comments][proce_comm]" class="form-control"><?php echo $advice_comm['proce_comm'];?></textarea>			
								</div>

								<div class="col-md-3">
									<ul class="list-unstyled">									
										<li class="m-b-5"> <span class="label-icon">C</span> <span>Standard Procedures</span></li>
										<li class="m-b-5"> <span class="label-icon">CC</span> <span>Custom Procedures</span></li>
									</ul> 			
								</div>
							</div>                  
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>


	<!--------Row Start------>
	<div class="tab-pane" id="referral">
		<h4>Outside Organisation</h4><br>
		<a href="javascript:void(0);" id="doctor_add_modal" class="btn bg-theme"><i class="fa fa-plus"></i> Add Doctor</a>
		<div class="dina_doc">
			<?php
			 if(!empty($advice_referral))
			 { 
				foreach ($advice_referral as $key => $referral) {?>
				 <div class="jumbotron" style="padding:20px;" id="referral_add_<?php echo $key;?>">
				 	<div class="row">
				 		<div class="col-md-12 text-right">
				 			<a href="#" onclick="remove_refer_doc('<?php echo $key;?>')" class="btn-custom">Delete <i class="fa fa-trash"></i></a>
				 		</div>
				 	</div>
				 	<div class="row">	
				 		<div class="col-lg-2">
				 			<label>Date</label><br>	
				 			<input type="text" class="datepicker" value="<?php echo $referral->date;?>" name="advs[referral][<?php echo $key;?>][date]" placeholder="Date">
				 		</div>
				 		<div class="col-lg-5">
				 			<label>Location</label><br>
				 			<input type="text" name="advs[referral][<?php echo $key;?>][location]" value="<?php echo $referral->location;?>" placeholder="Location">
				 		</div>
				 		<div class="col-lg-5">
				 			<label>Doctor </label><br>
				 			<input type="text" name="advs[referral][<?php echo $key;?>][doctor]" id="doc_name_<?php echo $key;?>" value="<?php echo $referral->doctor;?>" onkeyup="return search_referal_doc('<?php echo $key;?>');"  placeholder="Doctor">
				 			<div class="append_box_doc_<?php echo $key;?> advs_append_box">
				 			</div>
				 		</div>
				 	</div>
				 	<br>
				 	<div class="row">
				 		<div class="col-lg-8">
				 			<label>Referral Note</label><br>
				 			<textarea class="w-100 form-control" name="advs[referral][<?php echo $key;?>][note]"><?php echo $referral->note;?></textarea>
				 		</div> 
				 	</div> 
				 	<input type="hidden" value="<?php echo $referral->doct_id;?>" id="doct_id_<?php echo $key;?>" name="advs[referral][<?php echo $key;?>][doct_id]">
				 </div>				
			<?php }} else { ?>

			<div class="jumbotron" id="referral_add_0" style="padding:20px;">
					<div class="row">
						<div class="col-lg-2">
							<label>Date</label><br>
							<input type="text" name="advs[referral][0][date]" class="datepicker" placeholder="Date">
						</div>
						<div class="col-lg-5">
							<label>Location</label><br>
							<input type="text"  name="advs[referral][0][location]"  placeholder="Location">
						</div>
						<div class="col-lg-5">
							<label>Doctor</label><br>
							<input type="text" id="doc_name_0"  name="advs[referral][0][doctor]" onkeyup="return search_referal_doc('0');"  placeholder="Doctor">
							<div class="append_box_doc_0 advs_append_box">
							</div>
						</div>
					</div>
			
					<br>
					<div class="row">
						<div class="col-lg-8">
							<label>Referral Note</label><br>
							<textarea  name="advs[referral][0][note]" class="w-100 form-control"></textarea>
						</div>           	
					</div>
					<input type="hidden" id="doct_id_0" name="advs[referral][0][doct_id]">
			</div>
		<?php } ?>
		</div>
		<br>
		<div class="row">
			<div class="col-lg-2">
				<a href="javascript:void(0);" onclick="add_refer_docs();" class="btn bg-theme"><i class="fa fa-plus"></i>Add</a>       	
			</div>
		</div>

	</div>
	<!--------Row Start------>			
	<div class="tab-pane" id="advices">
		<h5>Follow Up</h5><br>  
		<div class="row">
			<form>
			<div class="col-lg-12">
				
				<div class="bg-lightgray" style="background:#eee;padding:20px;">
					<div class="row">
						<div class="col-lg-4">
							<label>Date <small class="mini_outline_btn" onclick="clear_dates();" id="clear_cdate">clear</small> </label><br>
							<input class="datepicker" value="<?php if($advice_adv['date']){echo $advice_adv['date'];} else if($datas['booking_date']){ echo date('d-m-Y',strtotime($datas['booking_date']));}?>" id="nxt_app_dt" type="text" name="advs[advice][date]" placeholder="Date">
						</div>
						<div class="col-lg-4 w-200px">
							<label>Location</label><br>
							<input value="<?php if($advice_adv['location'] !=''){echo $advice_adv['location'];}?>" type="text" name="advs[advice][location]" placeholder="Location">
						</div>
						<div class="col-lg-4">	
							<label>Doctor</label><br>
							<select id="advs_sel_doct" onchange="advs_sel_docts();" name="advs[advice][doctor]" class="w-200px">
								<option value="">Select Doctor Name</option>
								<?php foreach ($doctor_list as $doctor) {?>
									<option <?php if($advice_adv['doctor']==$doctor->id){ echo 'selected';}?> value="<?php echo $doctor->id;?>"><?php echo $doctor->doctor_name;?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<input type="hidden" name="advs[advice][doctor_name]" value="<?php echo $doctor->doctor_name;?>" id="advs_docts">
					<div class="row m-t-5">
						<div class="col-lg-4">
							<label>Time</label><br>
							<input type="text" name="advs[advice][time]" value="<?php echo $advice_adv['time'];?>" class="datepicker3" placeholder="Select Time">
						</div>
						<div class="col-lg-4">
							<label>Appointment Type*</label><br>
							<select name="advs[advice][app_type]" class="w-200px">
							  	<option <?php if($advice_adv['app_type']=="1" || $datas['app_type']=='1'){ echo 'selected';} ?> value="1">Review</option>
							  	<option <?php if($advice_adv['app_type']=="0" || $datas['app_type']=='0'){ echo 'selected';} ?> value="0">New</option>
							  	<option <?php if($advice_adv['app_type']=="2" || $datas['app_type']=='2'){ echo 'selected';} ?> value="2">Post OP</option>
							</select>
						</div>
						<div class="col-lg-4">
							<label>Appointment Category</label><br>
							<select name="advs[advice][app_cat]" class="w-200px">
								 <option value="">Select Appointment Category</option>
								 <option <?php if($advice_adv['app_cat']=="Discounted"){ echo 'selected';} ?> value="Discounted">Discounted</option>
								 <option <?php if($advice_adv['app_cat']=="Paid"){ echo 'selected';} ?> value="Paid">Paid</option>
								 <option <?php if($advice_adv['app_cat']=="Free"){ echo 'selected';} ?> value="Free">Free</option>
							</select>
						</div>
					</div>

				</div>
				<div class="well">
					<label class="checkbox-inline"><input type="checkbox" onchange="next_app_book();" name="advs[advice][make_app]" id="next_app_books" <?php if($advice_adv['make_app']=="1"){ echo 'checked';}?> value="<?php if($advice_adv['make_app']=="1"){ echo '1';} else{ echo '0'; }?>">Make Appointment</label>
				</div>


				
				<div class="well" style="background:#eee;">
					<div class="row">
						<div class="col-xs-12 text-center">
							<label>OR</label><br>
							<label class="btn_radio_small"><input type="radio" value="1" name="advs[advice][days]" class="advs_next_app"> Immediate</label>
							<label class="btn_radio_small"><input type="radio" value="2" name="advs[advice][days]" class="advs_next_app"> 2 D</label>
							<label class="btn_radio_small"><input type="radio" value="3" name="advs[advice][days]" class="advs_next_app"> 3 D</label>
							<label class="btn_radio_small"><input type="radio" value="5" name="advs[advice][days]" class="advs_next_app"> 5 D</label>
							<label class="btn_radio_small"><input type="radio" value="10" name="advs[advice][days]" class="advs_next_app"> 10 D</label>
							<label class="btn_radio_small"><input type="radio" value="7" name="advs[advice][days]" class="advs_next_app"> 1 W</label>
							<label class="btn_radio_small"><input type="radio" value="14" name="advs[advice][days]" class="advs_next_app"> 2 W</label>
							<label class="btn_radio_small"><input type="radio" value="21" name="advs[advice][days]" class="advs_next_app"> 3 W</label>
							<label class="btn_radio_small"><input type="radio" value="42" name="advs[advice][days]" class="advs_next_app"> 6 W</label>
							<label class="btn_radio_small"><input type="radio" value="30" name="advs[advice][days]" class="advs_next_app"> 1 M</label>
							<label class="btn_radio_small"><input type="radio" value="60" name="advs[advice][days]" class="advs_next_app"> 2 M</label>
							<label class="btn_radio_small"><input type="radio" value="90" name="advs[advice][days]" class="advs_next_app"> 3 M</label>
							<label class="btn_radio_small"><input type="radio" value="180" name="advs[advice][days]" class="advs_next_app"> 6 M</label>
							<label class="btn_radio_small"><input type="radio" value="365" name="advs[advice][days]" class="advs_next_app"> 1 Y</label>
							<label class="btn_radio_small"><input type="radio" value="0" name="advs[advice][days]" class="advs_next_app"> SOS</label>
						</div>		
					</div>	
				</div>	


				<br>
				<p>**Note: Please uncheck the check box if you dont want to create followup appointment automatically</p>

				<div class="well">
						<div class="row">
							<div class="col-lg-2">		
								<label>Advice &amp; Precautions: <hr style="border-top: 3px solid #c3c3c3; margin: 0;"> </label>
							</div>
							<div class="col-lg-10">
								<select type="form-control" onchange="add_tmpl_new_data(this.value)" id="add_tmpl_new" name="advice_set" style="width:100%">
									<option value="">Select Advice Set</option>
								<?php if(!empty($advice_sets)){
									foreach ($advice_sets as $key => $sets) { ?>
										<option <?php if($sets['id']=="demo"){ echo 'selected';} ?> value="<?php echo $sets['id'];?>"><?php echo $sets['set_name'];?></option>
									<?php } } ?>
									<option value="0">Add New</option>
								</select>
							</div>
							<div class="col-lg-10 d-none m-t-5" id="set_advs_name"> <input type="text" placeholder="Enter advice set name" id="advice_set_name" name="set_name"></div>
						</div>
						<br>
						<div class="row m-t-5">
							<div class="col-lg-2">
								<button type="button" onclick="save_advice_tmpl();" class="btn-custom">Save Advice Set</button>	
							</div>
							<div class="col-lg-10">
								<textarea class="w-100 advs_cke" name="advs[advice][advice_txt]" id="advicess_cke"><?php if(!empty($advice_adv['advice_txt'])) {echo $advice_adv['advice_txt'];} ?></textarea>
							</div>	
							
						</div>
				</div>
			</div>
		</form>
		</div>
		<!-- row -->
	</div> 
	<!-- tab-pane -->


</div>



<div class="modal fade" id="new_tapring_set">
	<div class="modal-dialog">
		<div class="modal-content">
		 <form id="tapper_form" name="tapper_form">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4>New Tapring Set</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-2">
						<label for="">Medicine Name</label>
					</div>
					<div class="col-md-3">
						<input type="text" id="medicine_nm" class="form-control">
					</div>
					<div class="col-md-4">
						<div class="stedt">
							No. Of Days : 
								<select class="w-50px dayselect" id="main_week_day" onchange="change_days(this.value);">
									<?php foreach ($qtyoption as $opt) { ?>
										<option value="<?php echo $opt;?>"><?php echo $opt;?></option>
									<?php } ?>
								</select>
						</div>
					</div>
					<div class="col-md-3">
						<button type="button" id="tp_savebtn" data-dismiss="modal" onclick="save_tapper_values();" class="btn-save">Save</button>
					</div>
					<div class="col-md-offset-6 col-md-6 text-danger">
						 <span class="text-right">Note: Keep Frequency to 0 if you dont want to taper for that Week. </span>
					</div>
				</div>

				<br>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th style="width:unset;">Sr no.</th>	
							<th>No of Days</th>
							<th>Start Date</th>
							<th class="stedt">End Date</th>
							<th>Start Time </th>
							<th>End Time</th>
							<th>Frequency(Day)</th>
							<th>Interval(Hour)</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="tap_set_body">
						
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<a href="#" onclick="append_medicine_freq();" class="btn btn-sm btn-primary text-center"><i class="fa fa-plus"></i></a>
				<input type="hidden" name="tp_data[0][med_id]" class="med_tp_id">	

				<input type="hidden" name="tmp_branch_id" value="<?php echo $branch_id; ?>">
				<input type="hidden" name="tmp_booking_id" value="<?php echo $booking_id; ?>">
				<input type="hidden" name="tmp_patient_id" value="<?php echo $patient_id; ?>">
				<input type="hidden" name="tmp_pres_id" value="<?php if(!empty($pres_id)){ echo $pres_id;}?>">
				<button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
			</div>
		</form>
		</div>
	</div>
</div>


<div class="modal fade" id="eye_region_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 id="advic_title"></h4>
			</div>
			<div class="modal-body">
				<form id="advs_proces_form">
				<div class="row">
					<div class="col-md-3">
						<label> State : </label><br>
						<input type="radio" id="perf_radios" onchange="advs_states(this.value)" value="A" checked name="er_state"> Advised<br>
						<input type="radio" id="perf_radios_p" onchange="advs_states(this.value)" value="P" name="er_state"> Performed
					</div>
					<div class="col-md-6">
						<label>Name : </label>
						<input type="text" class="form-control" id="advs_tname" readonly>
					</div>
					<div class="col-md-3">
						<label>Side : </label>
						<select name="" id="advs_tside" class="form-control">							
		                    <option value="Both">Bilateral</option>
		                    <option value="Right">Right</option>
		                    <option value="Left">Left</option>              
						</select>
					</div>
				</div>
				
				<div class="row d-none" id="advs_perfomed">
					<br>
					<div class="col-md-3"></div>
					<div class="col-md-6">
						<label>Performed by : </label>
						<input type="hidden" id="advs_tperby_name" class="form-control">
						<select id="advs_tperby" onchange="advs_proce_sel_docts();" name="advs[advice][doctor]" class="w-200px form-control">
								<option value="">Select Doctor Name</option>
								<?php foreach ($doctor_list as $doctor) {?>
									<option value="<?php echo $doctor->id;?>"><?php echo $doctor->doctor_name;?></option>
								<?php } ?>
							</select>
					</div>
					<div class="col-md-3">
						<label>Surgery Date :</label>
						<input type="text" class="datepicker" id="advs_tsdate" class="form-control">
					</div>

				</div> 
				<br>
				<div class="row">
					<div class="col-md-3">
						<label>Procedure Comments : </label>
					</div>
					<div class="col-md-9">
						<textarea name="" id="advs_tpcomm" class="form-control" rows="3"></textarea>
					</div>
				</div> 
				<br>
				<div class="row">
					<div class="col-md-3">
						<label>User Comments : </label>
					</div>
					<div class="col-md-9">
						<textarea name="" id="advs_tucomm" class="form-control" rows="3"></textarea>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-4">
						<label> Snomed Code : </label>
						<input type="text" id="advs_tsnocode" class="form-control" readonly>
					</div>
					<div class="col-md-4">
						<label>Entered By :  </label>
						<input type="text" id="advs_tenby" value="<?php echo get_doctor_name($datas['attended_doctor']); ?>" class="form-control" readonly>
					</div>
					<div class="col-md-4">
						<label>Entry Date & Time :</label>
						<input type="text" id="advs_tdnt" class="form-control" readonly>
					</div>
				</div>
			</form>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="save_advs_proces();" data-dismiss="modal" class="btn-save">Save</button>
				<button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


<script>	
	var count=0;
	var count2=0;
	var sddddd=0;
	var sn=0;
	var frequecy=0;
	function append_medicine()
	{
		count=$('#medication_body tr').length;
		$('#medication_body').append('<tr id="appends_'+count+'"><td class="text-left" valign="top" style="vertical-align:top;position:relative;"><input id="mname_'+count+'" type="text" name="advs[medication]['+count+'][mname]" class="medicine_name" onkeyup="search_func('+count+');"><div class="append_box_medi_'+count+' advs_append_box">								</div><small class="label label-danger d-none" id="fill_med_'+count+'">Fill Medicine</small><small class="label label-info d-none" id="medavailqty_'+count+'"></small></td>			<td valign="top" style="vertical-align:top;"><input id="mtype_'+count+'" type="text" name="advs[medication]['+count+'][mtype]" class=""></td><td valign="top" style="vertical-align:top;"><select onchange="return validate_qty(this.value,'+count+');" id="mqty_'+count+'" name="advs[medication]['+count+'][mqty]" class="w-50px form-control"><option value="">Sel</option><option value="1/4">1/4</option><option value="1/2">1/2</option><?php foreach ($qtyoption as $opt) { ?><option value="<?php echo $opt;?>"><?php echo $opt;?></option><?php } ?></select><div class="small label label-danger" id="qterr_'+count+'"></div></td><td valign="top" style="vertical-align:top;"><select name="advs[medication]['+count+'][mfrq]" id="mfreq_'+count+'" class="w-50px"><option value="">Sel</option><?php foreach ($freoption as $opt) { ?><option value="<?php echo $opt;?>"><?php echo $opt;?></option><?php } ?></select></td><td valign="top" style="vertical-align:top;" class="text-left"><div style="display:flex;justify-content:space-between;"><select name="advs[medication]['+count+'][mdur]" id="mdur_'+count+'" style="width:49%"><option value="">Sel</option>	<?php foreach ($qtyoption as $opt) { ?><option value="<?php echo $opt;?>"><?php echo $opt;?></option><?php } ?></select><select name="advs[medication]['+count+'][mdurd]" id="mdurd_'+count+'" style="width:49%"><option value="">Sel</option><option value="D">Days</option><option value="W">Weeks</option><option value="M">Months</option><option value="F">Next followup</option></select></div><div class="small label label-danger d-none" id="fill_dur_'+count+'">Fill  Duration</div><div class="small label label-danger d-none" id="fill_durdw_'+count+'">Select Days OR Week</div></td><td valign="top" style="vertical-align:top;padding-top:6px;">	<a href="javascript:void(0);" onclick="open_tapper('+count+');" class="btn-new"><i class="fa fa-plus"></i> Add</a></td><td valign="top" style="vertical-align:top;"><select name="advs[medication]['+count+'][eside]" id="meye_'+count+'" class="w-50px"><option value="">Sel</option><option value="L">L</option><option value="R">R</option><option value="BE">BE</option></select></td><td valign="top" style="vertical-align:top;"><select name="advs[medication]['+count+'][minst]" id="minst_'+count+'" class="w-50px"><option value="">Sel</option><?php foreach ($instruct as $inst) { ?><option value="<?php echo $inst;?>"><?php echo $inst;?></option><?php } ?>	</select></td> <td><a href="javascript:void(0);" onclick="remove_medication('+count+');" class="btn-new"><i class="fa fa-times"></i></a></td><input type="hidden" id="med_id_'+count+'" name="advs[medication]['+count+'][med_id]"></tr>');
	}
	function remove_medication(id)
	{
		$('#appends_'+id).remove();
	}
	
	function append_medicine_freq()
	{
		var rowCount = $('#tap_set_body tr').length;
		sn=rowCount+1;
		count2=rowCount;
		$('.del_all').hide();
		$('.del_lst_'+rowCount).show();
		$('#tap_set_body').append('<tr id="med_freq_row_'+count2+'"><td style="width:unset;"><input type="text" name="tp_data['+count2+'][sn]" class="form-contorl" value="'+sn+'"></td><td><select name="tp_data['+count2+'][wdays]" class="stedt w-50px dayselect" onchange="list_wday(this.value,'+count2+')" id="week_day_'+count2+'"><?php foreach ($qtyoption as $opt) { ?>	<option value="<?php echo $opt;?>"><?php echo $opt;?></option><?php } ?></select><input name="tp_data['+count2+'][days]" type="number" value="1" class="form-contorl stedtr"  readonly></td><td><input name="tp_data['+count2+'][st_date]" type="text" class="form-contorl st_dateo" id="st_dateo_'+count2+'" value=""></td><td class="stedt"><input name="tp_data['+count2+'][en_date]" type="text" class="form-contorl en_dateo" id="en_dateo_'+count2+'" value=""></td><td><input name="tp_data['+count2+'][st_time]" type="text" class="form-contorl datepicker3"></td><td><input name="tp_data['+count2+'][en_time]" type="text" class="form-contorl datepicker3"></td><td><input type="number" name="tp_data['+count2+'][freq]" value="'+frequecy+'" class="form-contorl w-100px"></td><td><input type="number" name="tp_data['+count2+'][intvl]" class="form-contorl"></td><td class="del_all del_lst_'+count2+'"><a href="#" onclick="remove_medicine_freq('+count2+')" class="btn-custom"><i class="fa fa-times"></i></a></td></tr>');
		getdate(count2);	
		$(".dayselect").val(sddddd);
		frequecy--;
		if(frequecy<=0)
			frequecy=0;

		$('.datepicker3').datetimepicker({
	        format: 'LT',
	    });
	}
	function remove_medicine_freq(id)
	{
		sn--;
		$('#med_freq_row_'+id).remove();
		$('.del_lst_'+(id-1)).show();
		count2--;
	}

	function search_func(id)
	{  
	    var medicine_name = $('#mname_'+id).val();
	    $.ajax({
	       type: "POST",
	       url: "<?php echo base_url('eye/add_eye_prescription/ajax_list_medicine')?>",
	       data: {'medicine_name' : medicine_name},
	       dataType: "json",
	       success: function(msg){
	        $(".append_row_opt").remove();
	        $(".append_box_medi_"+id).show().html(msg.data);
	        $('.append_row_opt').click(function(){
				$('#mname_'+id).val($(this).text());
				$('#mtype_'+id).val($(this).attr('data-type'));
				$('#medavailqty_'+id).show();
				$('#medavailqty_'+id).text('Avail Qty : '+$(this).attr('data-qty'));
				$('#med_id_'+id).val($(this).attr('data-id'));
				$(".append_box_medi_"+id).hide();
			});
	       }
	    }); 
	}
	function open_tapper(no)
	{
		$('#tap_set_body').html('');
		count2=0;
		sn=0;
		var mdur = $('#mdur_'+no).val();
		var mdurd = $('#mdurd_'+no).val();
		var freq = $('#mfreq_'+no).val();

		frequecy=freq;
		if(mdurd=='D')
			sddddd=1;
		else if(mdurd=='W')
			sddddd=7;
		if($('#mname_'+no).val()=='')
			$('#fill_med_'+no).css('display','inline-block');
		else
			$('#fill_med_'+no).hide();
		if(mdur=='' || mdurd=='')
			$('#fill_dur_'+no).css('display','inline-block');
		else if(mdurd=='M' || mdurd=='F'){
			$('#fill_dur_'+no).hide();
			$('#fill_durdw_'+no).css('display','inline-block');
		}
		else
		{
			$('#fill_dur_'+no).hide();
			$('#fill_durdw_'+no).hide();
		}
		if(mdur=='' || mdurd=='' || mdurd=='M' || mdurd=='F' || $('#mname_'+no).val()=='')
		{}
		else
		{
			$('#new_tapring_set').modal('show');
			$('#medicine_nm').val($('#mname_'+no).val());
			$("#tp_savebtn").attr('data-type', no);
			$(".med_tp_id").val($('#med_id_'+no).val());
			for (var i = 0; i < mdur; i++) 
			{
				append_medicine_freq();				
			}
		}
	
	}
	
	function change_days(day)
	{
		$('.st_dateo').val('');
		$('.en_dateo').val('');
		$(".dayselect").val(day);
		sddddd=+(day);
		var rowCount = $('#tap_set_body tr').length;
		for (var i = 0; i < rowCount; i++) {		
		       getdate(i); 			
		}		
	}

	function list_wday(dayes, num)
	{
		var rowCount = $('#tap_set_body tr').length;
		for (var i = num; i < rowCount; i++) {		
		      new_getdate(dayes,i);		
		}
	}

	function getdate(nos) 
	{
		var wdays=sddddd-1;
		if(sddddd==1)
		{
			$('.stedt').hide();
			$('.stedtr').show();
		}
		if(sddddd==7)
		{
			$('.stedt').show();
			$('.stedtr').hide();
		}
		var days=+(sddddd*nos);
	    var newdate = new Date();
	    newdate.setDate(newdate.getDate() + days);    
	    var dd = newdate.getDate();
	    var mm = newdate.getMonth() + 1;
	    var y = newdate.getFullYear();
	    var someFormattedDate = dd + '/' + mm + '/' + y;
	    var st=mm + '/' + dd + '/' + y;
	    $('#st_dateo_'+nos).val(someFormattedDate);
    	var date = new Date(st);
        var newdate1 = new Date(date);
        newdate1.setDate(newdate1.getDate() + wdays);    
	    var dd1 = newdate1.getDate();
	    var mm1 = newdate1.getMonth() + 1;
	    var y1 = newdate1.getFullYear();
	    var someFormattedDate1 = dd1 + '/' + mm1 + '/' + y1;
	    $('#en_dateo_'+nos).val(someFormattedDate1);
	}   

	function new_getdate(ds,nos) 
	{
	    var st=$('#st_dateo_'+nos).val();
	    var dayys=$('#week_day_'+nos).val();
	    var edays=(+dayys-1)
	    var stn=st.split('/');
	    var st_dt=stn[1]+'/'+stn[0]+'/'+stn[2];
	    var date = new Date(st_dt);
        var newdate1 = new Date(date);
        newdate1.setDate(newdate1.getDate() + (+(edays)));    
	    var dd1 = newdate1.getDate();
	    var mm1 = newdate1.getMonth() + 1;
	    var y1 = newdate1.getFullYear();

	    var someFormattedDate1 = dd1 + '/' + mm1 + '/' + y1;
	    $('#en_dateo_'+nos).val(someFormattedDate1);

	    var someFormattedDate2 = mm1 + '/' +dd1 + '/' + y1;
	    var date2 = new Date(someFormattedDate2);
        var newdate2 = new Date(date2);
        newdate2.setDate(newdate2.getDate() + 1)    
	    var dd2 = newdate2.getDate();
	    var mm2 = newdate2.getMonth() + 1;
	    var y2 = newdate2.getFullYear();
	    var someFormattedDate3 = dd2 + '/' + mm2 + '/' + y2;
	    $('#st_dateo_'+(nos+1)).val(someFormattedDate3);
	} 
	

	$('#advs_eye_rgn').change(function(){
			var test_head_id = $(this).val(); 
		   $.ajax({url: "<?php echo base_url(); ?>eye/ophthal_set/advice_eye_region_test/"+test_head_id, 
		   	success: function(result)
		   	{ 
		   		$('#advs_proc_box').show();
		   		$('#advs_proces').html(result);     
		   	} 
		   }); 
		});

	function save_tapper_values()
	{
		var tapr = $('#tp_savebtn').attr('data-type');
		 $.ajax({
			 	type: "POST",
	            url: "<?php echo base_url();?>eye/add_eye_prescription/save_tapper_set",
	            data: $('#tapper_form').serialize(),
	            success:function(result)
	            {
	            	
		        }
    		});
	}

	
	$('#advs_proces').change(function(){
		$('#advs_proces_form').trigger("reset");
		var eye_region=$('#advs_eye_rgn').val();
		var invest=$('#advs_proces').val();
		var investig_name= $('#advs_proces option:selected').html();
		$('#advs_tname').val(investig_name);
		$('#advs_tsdate').val('<?php echo date('d/m/Y');?>');
		$('#advs_tperby').val();
		$('#advs_tsnocode').val(invest);
		$('#advs_tdnt').val('<?php echo date('d/m/Y h:i A');?>');
		$('#advic_title').text(investig_name+'('+invest+')');
	});


	function save_advs_proces()
	{
		var id=$('#advs_tsnocode').val();
		var tname=$('#advs_tname').val();
		var tside=$('#advs_tside').val();
		var tsdate=$('#advs_tsdate').val();
		var advs_tperby=$('#advs_tperby').val();
		var tperby=$('#advs_tperby_name').val();
		var tsnocode=$('#advs_tsnocode').val();
		var tpcomm=$('#advs_tpcomm').val();
		var tucomm=$('#advs_tucomm').val();
		var tenby=$('#advs_tenby').val();
		var tdnt=$('#advs_tdnt').val();	
		$('#apnd_advs_proces_'+id).remove();	
		$('#table_advs_proces').append('<tr id="apnd_advs_proces_'+id+'"><td style="width:auto;text-align:left;">						<div class="text-bold"><span class="text-success">P -</span> '+tname+' - '+tside+' Eyes- '+id+'</div>	<ul class="small" style="list-style:disc;padding-left:20px;"><li>'+tpcomm+'</li><li>'+tucomm+'</li></ul class="small"><hr><div class="small text-bold">Added by '+tenby+' on '+tdnt+' </div><div class="small text-bold">Performed by '+tperby+'('+tsdate+')</div></td><td width="100" valign="top" style="vertical-align:top;"><a href="javascript:void(0);" onclick="edit_advs_proces('+id+')" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a><a href="javascript:void(0);" onclick="delete_advs_proces('+id+')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></td><input type="hidden" value="'+id+'" name="advs[procedures]['+id+'][pros_id]" id="ed_id_'+id+'"><input type="hidden" value="'+tname+'" name="advs[procedures]['+id+'][test_name]" id="ed_tname_'+id+'"><input type="hidden" value="'+tside+'" name="advs[procedures]['+id+'][eye_side]" id="ed_tside_'+id+'"><input type="hidden" value="'+tsdate+'" name="advs[procedures]['+id+'][surg_date]" id="ed_tsdate_'+id+'"><input type="hidden" value="'+advs_tperby+'" name="advs[procedures]['+id+'][pref_by]" id="ed_tperby_'+id+'"><input type="hidden" value="'+tperby+'" name="advs[procedures]['+id+'][pref_by_name]" id="ed_tperby_name_'+id+'"><input type="hidden" value="'+tsnocode+'" name="advs[procedures]['+id+'][sno_code]" id="ed_tsnocode_'+id+'"><input type="hidden" value="'+tpcomm+'" name="advs[procedures]['+id+'][p_comm]" id="ed_tpcomm_'+id+'"><input type="hidden" value="'+tucomm+'" name="advs[procedures]['+id+'][u_comm]" id="ed_tucomm_'+id+'"><input type="hidden" value="'+tenby+'" name="advs[procedures]['+id+'][ent_by]" id="ed_tenby_'+id+'"><input type="hidden" value="'+tdnt+'" name="advs[procedures]['+id+'][tdntime]" id="ed_tdnt_'+id+'"></tr>');
		$('#advs_proces_form').trigger("reset");
		$('#advs_perfomed').hide();
	}
	function advs_states(val)
	{
		if(val=='P')
		{
			$('#advs_perfomed').show();
		}
		else{
			$('#advs_perfomed').hide();
		}
	}
	function edit_advs_proces(id)
	{ 
		var perform= $('#ed_tperby_'+id).val();
		$('#advs_tsnocode').val($('#ed_id_'+id).val());
		$('#advs_tname').val($('#ed_tname_'+id).val());
		$('#advs_tside').val($('#ed_tside_'+id).val());
		$('#advs_tsdate').val($('#ed_tsdate_'+id).val());
		$('#advs_tperby').val($('#ed_tperby_'+id).val());	
		$('#advs_tperby_name').val($('#ed_tperby_name_'+id).val());	
		$('#advs_tsnocode').val($('#ed_tsnocode_'+id).val());
		$('#advs_tpcomm').val($('#ed_tpcomm_'+id).val());
		$('#advs_tucomm').val($('#ed_tucomm_'+id).val());
		$('#advs_tdnt').val($('#ed_tdnt_'+id).val());	
		$('#advic_title').text($('#ed_tname_'+id).val()+'('+$('#ed_tsnocode_'+id).val()+')');	
		$('#eye_region_modal').modal('show');
		if(perform !='')
		{
		 $("#perf_radios_p").prop("checked", true);
		 $('#advs_perfomed').show();
		}
	}
    function delete_advs_proces(id)
    {
    	$('#apnd_advs_proces_'+id).remove();
    }
	// referal doctor show
    $('#doctor_add_modal').on('click', function(){
    	var $modal = $('#load_add_modal_popup');
		$modal.load('<?php echo base_url().'doctors/add/' ?>',
		{
	  	},
		function(){
		$modal.modal('show');
		});
	});


			

	var d_count=0;
	function add_refer_docs()
	{
		d_count=$(".dina_doc > div").length;
		$('.dina_doc').prepend('<div class="jumbotron" style="padding:20px;" id="referral_add_'+d_count+'"><div class="row"><div class="col-md-12 text-right"><a href="#" onclick="remove_refer_doc('+d_count+')" class="btn-custom">Delete <i class="fa fa-trash"></i></a></div></div><div class="row">	<div class="col-lg-2"> <label>Date</label><br>	<input type="text" class="datepicker" name="advs[referral]['+d_count+'][date]" placeholder="Date"></div><div class="col-lg-5">	<label>Location</label><br>	<input type="text" name="advs[referral]['+d_count+'][location]" placeholder="Location"></div><div class="col-lg-5"><label>Doctor</label><br>		<input type="text" name="advs[referral]['+d_count+'][doctor]" id="doc_name_'+d_count+'" onkeyup="return search_referal_doc('+d_count+');"  placeholder="Doctor"><div class="append_box_doc_'+d_count+' advs_append_box">	</div></div></div><br><div class="row"><div class="col-lg-8"><label>Referral Note</label><br>	<textarea class="w-100 form-control" name="advs[referral]['+d_count+'][note]"></textarea></div> </div><input type="hidden" id="doct_id_'+d_count+'" name="advs[referral]['+d_count+'][doct_id]"> </div> ');

		 $('.datepicker').datepicker({
                    dateFormat: 'dd-mm-yy',
                    autoclose: true
                });
	}

    function search_referal_doc(id)
	{  	    
	var doc_name = $('#doc_name_'+id).val();
    $.ajax({
	       type: "POST",
	       url: "<?php echo base_url('eye/add_eye_prescription/ajax_list_referal_doc')?>",
	       data: {'doc_name' : doc_name},
	       dataType: "json",
	       success: function(msg){
	        $(".append_row_refer").remove();
	        $(".append_box_doc_"+id).show().html(msg.data);
	        $('.append_row_refer').click(function(){
				$('#doc_name_'+id).val($(this).text());
				$('#doct_id_'+id).val($(this).attr('data-type'));
				$(".append_box_doc_"+id).hide();
			});
	       }
	    });
	}

	function remove_refer_doc(id)
	{
		$('#referral_add_'+id).remove();
	}
	 $('.datepicker').datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true
        });
		$('.advs_next_app').click(function(){
			$('.advs_next_app').parent().removeClass('active');
			$(this).parent().addClass('active');
			next_app_date($(this).val());
		});
	function advs_sel_docts()
	{
		$('#advs_docts').val($("#advs_sel_doct option:selected").text());
	}


	function advs_proce_sel_docts()
	{
		$('#advs_tperby_name').val($("#advs_tperby option:selected").text());
	}

	function add_tmpl_new_data(val)
	{
		var branch_id="<?php echo $branch_id;?>";
		if(val=='0')
		{
			$('#set_advs_name').show();
		}
		else{
			$('#set_advs_name').hide();
			$.ajax({
			 	type: "POST",
	            url: "<?php echo base_url();?>eye/add_eye_prescription/get_advice_by_id",
	            data: {branch_id:branch_id, set_id:val},
	            success:function(result)
	            {
	            	var obj = JSON.parse(result);
	            	CKEDITOR.instances['advicess_cke'].setData(obj[0]['set_data']);
		        }
    		});
		}
	}




	function save_advice_tmpl()
	{
		var set_name = $('#advice_set_name').val();
		var set_data = CKEDITOR.instances['advicess_cke'].getData();
		var set_id = $('#add_tmpl_new').val();
		if(set_id !='0')
		{
			set_name=$("#add_tmpl_new option:selected").text();
		}
		 $.ajax({
			 	type: "POST",
	            url: "<?php echo base_url();?>eye/add_eye_prescription/save_advice_template",
	            data: {'ad_set_name':set_name, 'ad_set_txt':set_data, 'set_id' : set_id},
	            success:function(result)
	            {
	            	alert('Advice set added successfully');
		        }
    		});
	}

	function next_app_date(ds) 
	{
		var book_date="<?php echo $datas['booking_date'];?>";
	    var date = new Date(book_date);
        var newdate = new Date(date);
        newdate.setDate(newdate.getDate() + (+ds))    
	    var dd = newdate.getDate();
	    var mm = newdate.getMonth() + 1;
	    var y = newdate.getFullYear();
	    var someFormattedDate = dd + '-' + mm + '-' + y;
	    $('#nxt_app_dt').val(someFormattedDate);
	} 
	
	function clear_dates()
	{
		 $('#nxt_app_dt').val('');
	}

function search_procedure(keyword)
{
    
   
     $.ajax({
       url: "<?php echo base_url('eye/add_eye_prescription/procedure_eye_test_search')?>/"+keyword,
        success: function(data) {
    
            $('#procedure_list').css('display','block');
            $('#procedure_list').html(data);
            $('.append_row_opt').click(function(){
              var id=$(this).attr('data-id');
			 var investig_name=$(this).attr('data-type');
			 $('#advs_tname').val(investig_name);
			 $('#advs_tsdate').val('<?php echo date('d/m/Y');?>');
			 $('#advs_tperby').val();
			 $('#advs_tsnocode').val(id);
			 $('#advs_tdnt').val('<?php echo date('d/m/Y h:i A');?>');
              $("#procedure_list").css('display','none');
              $("#procedure").val('');
              $('#advic_title').text(investig_name+'('+id+')');

          });
         }
	}); 

 
}
function get_datas(mids)
{
	var branch_id="<?php echo $branch_id;?>";
	 $.ajax({
			 	type: "POST",
	            url: "<?php echo base_url();?>eye/add_eye_prescription/get_medicine_sets_data",
	            data: {'branch_id':branch_id, 'set_id' : mids},
	            dataType:'json',
	            success:function(result)
	            {
	            	 $.each(result, function(index, element) {
	            	 	count=$('#medication_body tr').length;
	            	 	var quantity_sel='';
	            	 	var quantity_sel_opt='<option value="">Sel</option><option value="1/4">1/4</option><option value="1/2">1/2</option>';
	            	 	var opt_mfreq_sel='<option value="">Sel</option>';
	            	 	var opt_mdur_sel='<option value="">Sel</option>';

	            	 	<?php foreach ($qtyoption as $opt) {?>  
	            	 		var opt = '<?php echo $opt; ?>';
	            	 		if(element.quantity==opt)
	            	 		{
	            	 			quantity_sel_opt +='<option value="<?php echo $opt;?>" selected><?php echo $opt;?></option>';
	            	 		}
	            	 		else{
	            	 			quantity_sel_opt +='<option value="<?php echo $opt;?>"><?php echo $opt;?></option>';
	            	 		}
	            	 		if(element.duration==opt)
	            	 		{
	            	 		 opt_mdur_sel +='<option value="<?php echo $opt;?>" selected><?php echo $opt;?></option>';
	            	 		}
	            	 		else{
	            	 			opt_mdur_sel +='<option value="<?php echo $opt;?>"><?php echo $opt;?></option>';
	            	 		}
	            	 	<?php } ?>
	            	 	quantity_sel +='<select id="mqty_'+count+'" onchange="return validate_qty(this.value,'+count+');" name="advs[medication]['+count+'][mqty]" class="w-50px form-control">'+quantity_sel_opt+'</select>';

	            	 	<?php foreach ($freoption as $opt) { ?>
	            	 		var opt1 = '<?php echo $opt; ?>';
	            	 		if(element.frequency==opt1)
	            	 		{
	            	 			opt_mfreq_sel +='<option value="<?php echo $opt;?>" selected><?php echo $opt;?></option>';
	            	 		}
	            	 		else{
	            	 			opt_mfreq_sel +='<option value="<?php echo $opt;?>"><?php echo $opt;?></option>';
	            	 		}
	            	 	<?php } ?>
	            	 	var opt_eye_sel='<option value="">Sel</option>';
	            	 	if(element.eyes=='L')
	            	 	{
	            	 	opt_eye_sel+='<option value="L" selected>L</option>';
	            	 	}
	            	 	else{
	            	 	 opt_eye_sel+='<option value="L">L</option>';
	            	 	}
	            	 	if(element.eyes=='R')
	            	 	{
	            	 	opt_eye_sel+='<option value="R" selected>R</option>';
	            	 	}
	            	 	else{
	            	 	 opt_eye_sel+='<option value="R">R</option>';
	            	 	}
	            	 	if(element.eyes=='BE')
	            	 	{
	            	 	opt_eye_sel+='<option value="BE" selected>BE</option>';
	            	 	}
	            	 	else{
	            	 	 opt_eye_sel+='<option value="BE" >BE</option>';
	            	 	}

	            	 	var opt_dur_unit='<option value="">Sel</option>';
	            	 	if(element.duration_unit=='D')
	            	 	{
	            	 	opt_dur_unit+='<option value="D" selected>Days</option>';
	            	 	}
	            	 	else{
	            	 	 opt_dur_unit+='<option value="D">Days</option>';
	            	 	}
	            	 	if(element.duration_unit=='W')
	            	 	{
	            	 	opt_dur_unit+='<option value="W" selected>Weeks</option>';
	            	 	}
	            	 	else{
	            	 	 opt_dur_unit+='<option value="W">Weeks</option>';
	            	 	}
	            	 	if(element.duration_unit=='M')
	            	 	{
	            	 	opt_dur_unit+='<option value="M" selected>Months</option>';
	            	 	}
	            	 	else{
	            	 	 opt_dur_unit+='<option value="M">Months</option>';
	            	 	}
	            	 	if(element.duration_unit=='F')
	            	 	{
	            	 	opt_dur_unit+='<option value="F" selected>Next followup</option>';
	            	 	}
	            	 	else{
	            	 	 opt_dur_unit+='<option value="F">Next followup</option>';
	            	 	}
	            	 	

	            	 	var opt_intruct='<option value="">Sel</option>';
	            	 	<?php foreach ($instruct as $inst) { ?>
	            	 		var opt2 = '<?php echo $inst; ?>';
	            	 		if(element.instrucion==opt2)
	            	 		{
	            	 			opt_intruct +='<option value="<?php echo $inst;?>" selected><?php echo $inst;?></option>';
	            	 		}
	            	 		else{
	            	 			opt_intruct +='<option value="<?php echo $inst;?>"><?php echo $inst;?></option>';
	            	 		}
	            	 	<?php } ?>

						$('#medication_body').append('<tr id="appends_'+count+'"><td class="text-left" valign="top" style="vertical-align:top;position:relative;"><input id="mname_'+count+'" type="text" value="'+element.medicine_name+'" name="advs[medication]['+count+'][mname]" class="medicine_name" onkeyup="search_func('+count+');"><div class="append_box_medi_'+count+' advs_append_box">								</div><small class="label label-danger d-none" id="fill_med_'+count+'">Fill Medicine</small><small class="label label-info d-none" id="medavailqty_'+count+'"></small></td>			<td valign="top" style="vertical-align:top;"><input id="mtype_'+count+'" type="text" value="'+element.medicine_type+'" name="advs[medication]['+count+'][mtype]" class=""></td><td valign="top" style="vertical-align:top;">'+quantity_sel+'<div class="small label label-danger" id="qterr_'+count+'"></div></td><td valign="top" style="vertical-align:top;"><select name="advs[medication]['+count+'][mfrq]" id="mfreq_'+count+'" class="w-50px">'+opt_mfreq_sel+'</select></td><td valign="top" style="vertical-align:top;" class="text-left"><div style="display:flex;justify-content:space-between;"><select name="advs[medication]['+count+'][mdur]" id="mdur_'+count+'" style="width:49%">'+opt_mdur_sel+'</select><select name="advs[medication]['+count+'][mdurd]" id="mdurd_'+count+'" style="width:49%">'+opt_dur_unit+'</select></div><div class="small label label-danger d-none" id="fill_dur_'+count+'">Fill  Duration</div><div class="small label label-danger d-none" id="fill_durdw_'+count+'">Select Days OR Week</div></td><td valign="top" style="vertical-align:top;padding-top:6px;">	<a href="javascript:void(0);" onclick="open_set_tapper('+count+','+element.set_id+','+element.medicine_id+');" class="btn-new"><i class="fa fa-pencil"></i> Edit</a></td><td valign="top" style="vertical-align:top;"><select name="advs[medication]['+count+'][eside]" id="meye_'+count+'" class="w-50px">'+opt_eye_sel+'</select></td><td valign="top" style="vertical-align:top;"><select name="advs[medication]['+count+'][minst]" id="minst_'+count+'" class="w-50px">'+opt_intruct+'</select></td> <td><a href="javascript:void(0);" onclick="remove_medication('+count+');" class="btn-new"><i class="fa fa-times"></i></a></td><input type="hidden" id="med_id_'+count+'" value="'+element.medicine_id+'" name="advs[medication]['+count+'][med_id]"></tr>');
			        });
		        }
    		});
}

  function open_set_tapper(no,set_id,medc_id)
	{
	    $('#tap_set_body').html('');
		count2=0;
		sn=0;
		var mdur = $('#mdur_'+no).val();
		var mdurd = $('#mdurd_'+no).val();
		var freq = $('#mfreq_'+no).val();

		frequecy=freq;
		if(mdurd=='D')
			sddddd=1;
		else if(mdurd=='W')
			sddddd=7;
		if($('#mname_'+no).val()=='')
			$('#fill_med_'+no).css('display','inline-block');
		else
			$('#fill_med_'+no).hide();
		if(mdur=='' || mdurd=='')
			$('#fill_dur_'+no).css('display','inline-block');
		else if(mdurd=='M' || mdurd=='F'){
			$('#fill_dur_'+no).hide();
			$('#fill_durdw_'+no).css('display','inline-block');
		}
		else
		{
			$('#fill_dur_'+no).hide();
			$('#fill_durdw_'+no).hide();
		}
		    if(set_id !='0' && medc_id !='0')
    {
      $('#new_tapring_set').modal('show');
      $('#medicine_nm').val($('#mname_'+no).val());
      $(".med_tp_id").val($('#med_id_'+no).val());
        $.ajax({
         type: "POST",
         url: "<?php echo base_url('medicine_sets/tapper_ajax_list')?>",
         data: {'set_id' : set_id, 'medi_id' : medc_id},
         dataType: "json",
         success: function(msg){
          console.log(msg);
          $('#tap_set_body').html(msg);
         }
      }); 
    }
    else
    {
      $('#new_tapring_set').modal('show');
      $('#medicine_nm').val($('#mname_'+no).val());
      $(".med_tp_id").val($('#med_id_'+no).val());
      for (var i = 0; i < mdur; i++) 
      {
        append_medicine_freq();       
      }
     }
	}


	function open_edit_tapper(no,pres_id,medc_id)
	{
	    $('#tap_set_body').html('');
		count2=0;
		sn=0;
		var mdur = $('#mdur_'+no).val();
		var mdurd = $('#mdurd_'+no).val();
		var freq = $('#mfreq_'+no).val();

		frequecy=freq;
		if(mdurd=='D')
			sddddd=1;
		else if(mdurd=='W')
			sddddd=7;
		if($('#mname_'+no).val()=='')
			$('#fill_med_'+no).css('display','inline-block');
		else
			$('#fill_med_'+no).hide();
		if(mdur=='' || mdurd=='')
			$('#fill_dur_'+no).css('display','inline-block');
		else if(mdurd=='M' || mdurd=='F'){
			$('#fill_dur_'+no).hide();
			$('#fill_durdw_'+no).css('display','inline-block');
		}
		else
		{
			$('#fill_dur_'+no).hide();
			$('#fill_durdw_'+no).hide();
		}
 if(pres_id !='0' && medc_id !='0')
    {
      $('#new_tapring_set').modal('show');
      $('#medicine_nm').val($('#mname_'+no).val());
      $(".med_tp_id").val($('#med_id_'+no).val());
        $.ajax({
         type: "POST",
         url: "<?php echo base_url('eye/add_eye_prescription/tapper_ajax_list')?>",
         data: {'pres_id' : pres_id, 'medi_id' : medc_id},
         dataType: "json",
         success: function(msg){
             if(msg='null')
          	 { 
          	 	open_tapper(no);
          	 }
          	 else
	         {
	         	$('#tap_set_body').html(msg);
	         }
         // $('#tap_set_body').html(msg);
         }
      }); 
    }
    else
    {
      $('#new_tapring_set').modal('show');
      $('#medicine_nm').val($('#mname_'+no).val());
      $(".med_tp_id").val($('#med_id_'+no).val());
      for (var i = 0; i < mdur; i++) 
      {
        append_medicine_freq();       
      }
     }
	}

	function next_app_book()
	{
		if($("#next_app_books").prop('checked') == true){
			$("#next_app_books").val('1');
		}
		else{
			$("#next_app_books").val('0');
		}
	}


	function appear_boxs()
	{
		if($("#appear_check").prop('checked') == true){
			$("#appear_check").val('1');
			$('#appear_box').show();
		}
		else{
			$("#appear_check").val('0');
			$('#appear_box').val('');
			$('#appear_box').hide();
		}
	}

	function validate_qty(val,id)
	{
		var qty=$('#medavailqty_'+id).text();
		var qty1= qty.split(':');
		qty2=+(val);
		qty3=+(qty1[1])
		if(qty2>qty3)
		{
			$('#qterr_'+id).text('Qty exceeded.');
			$('#mqty_'+id).val('');
		}

	}

</script>

<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
