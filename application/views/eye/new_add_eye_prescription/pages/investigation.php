<style>
	.eye-tabs a.active{background:var(--green);color:#fff;}
	.eye-tabs:hover a:hover {background:transparent;color:#333;}
</style> 
<!-- //////////////////////////////////////////////////// -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


   <script>
   	var  availableTags=[];
   	var opthal_auto_compt= $('#opthal_auto_compt').val();
   	$(function()
   	{
   		var path="<?php echo base_url() ;?>eye/ophthal_set/ophthal_investigation_list";

   		$.ajax({
   			url: path,
   			type: "get", 
   			data: $(this).serialize(),
   			success: function(result) 
   			{
   				var inv=$.parseJSON(result);

   				for (i=0; i < inv.length; i++){
   					availableTags.push(inv[i].test_name);
   				}
   			} 
   		});
   		$( "#opthal_auto_compt" ).autocomplete({
   			source: availableTags
   		}); 

   		var path="<?php echo base_url() ;?>eye/radiology_set/radio_investigation_list";
   		$.ajax({
   			url: path,
   			type: "get", 
   			data: $(this).serialize(),
   			success: function(result) 
   			{
   				var inv=$.parseJSON(result);

   				for (i=0; i < inv.length; i++){
   					availableTags.push(inv[i].test_name);
   				}
   			} 
   		});
   		$( "#radio_auto_compt" ).autocomplete({
   			source: availableTags
   		}); 	
   	});
 	
  </script>

<section>
	<div class="row">
		<div class="col-lg-12 text-center ">
			<ul class="nav nav-pills eye-tabs">
				<li class="col-lg-3" role="presentation"><a href="#ophthal" data-toggle="tab" class="active op">Ophthal</a></li>
				<li class="col-lg-3" role="presentation"><a href="#Laboratory" data-toggle="tab" class=" op">Laboratory</a></li>
				<li class="col-lg-3" role="presentation"><a href="#Radiology" data-toggle="tab" class=" op">Radiology</a></li>
			</ul>
			<script>
				$(document).ready(function(){
					$('.op').click(function(){							
						$('.op').removeClass('active');
						$(this).addClass('active');
					});
				});
			</script>
		</div>		
	</div> 

	
	<div class="row"> 
		<div class="col-md-12">
			<div class="tab-content">
				<input type="hidden" name="data-id" id="investig_presc_id" value="<?php echo $prescription_id; ?>">
				<div role="tabpanel" class="tab-pane active" id="ophthal">   					

					<br>	
					<div class="row text-right">
						<div class="col-lg-4"></div>
						<div class="col-lg-5"></div>
						<div class="col-lg-3">
							<div style="position:relative;text-align:left;">
								<input class="form-control m-b-4" type="text" name="" id="opthal_search" onkeyup="return ophthal_search(this.value);"placeholder="Search by any Ophthal Investigations Name"><br> 
								<div class="search_dropdown_list" id="ophthal_search_list"></div>
							</div>
						</div>	
                       	
					</div>
					<br>
					

					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-6">
							<div>Ophthal Set</div>
							<select name="ophthal_set" id="ophthal_set" class="form-control" multiple="">

								<?php
								if(!empty($ophthal_set))
								{
									foreach($ophthal_set as $ophthal)
									{
										$ophthal_select = "";
										if($ophthal->id==$form_data['ophthal_set'])
										{
											$ophthal_select = "selected='selected'";
										}
										echo '<option value="'.$ophthal->id.'" '.$ophthal_select.'>'.$ophthal->ophthal_set_name.'</option>';
									}
								}
								?>
							</select>
						</div>

						<div class="col-lg-2 col-md-2 col-sm-6">
							<div>Eye Region</div>
							<select name="eye_region_test_head" id="eye_region" class="form-control" multiple="">
								<?php
								if(!empty($eye_region))
								{
									foreach($eye_region as $region)
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

						<div class="col-lg-2 col-md-2 col-sm-6" id="investig_box">
							<div>Investigations</div>
							<select name="ophthal_investigation" id="investig" class="form-control" multiple="" >
								<option value="">ALL</option>
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
										echo '<h5  '.$investigation_select.'>'.$ophthal_investigation->test_heads.'</h5>';
									}
								}
								?>
							</select>
						</div>

						<div class="col-lg-6" id="append_div"> 

							<?php //echo "<pre>"; print_r($ophthal_data['ophthal_test']);die();
							$i=0;
							if($ophthal_data['ophthal_test'])
							{
							 foreach($ophthal_data['ophthal_set'] as $investigation) {
								?>	
								<table class="table table-bordered add_opthal_<?php echo $i; ?>">
									<tr>
										<td style="width:unset;text-align:left;">
											<div class="d-block">
												<input type="radio" name="investig_name[ophthal][ophthal_set][<?php echo $i; ?>][radio]" value="1" class="adv_<?php echo $i; ?> advis" onchange="return performed(this.value,'<?php echo $i; ?>');" <?php if($investigation->radio==1){ ?> checked <?php } ?>>Advised
											</div>
											<div class="d-block">
												<input type="radio" name="investig_name[ophthal][ophthal_set][<?php echo $i; ?>][radio]" class="adv_<?php echo $i; ?>" value="2" onchange="return performed(this.value,'<?php echo $i; ?>');" <?php if($investigation->radio==2){ ?> checked <?php } ?>>
													Performed
											</div>
										</td>
										<td>
											<input type="text" name="investig_name[ophthal][ophthal_set][<?php echo $i; ?>][test_name]"  class="form-control m-b-4" value="<?php echo $investigation->test_name;?>">
											<input type="hidden" name="investig_name[ophthal][ophthal_set][<?php echo $i; ?>][set_id]"  class="form-control m-b-4" value="<?php echo $investigation->set_id;?>">
											<div class="<?php if($investigation->radio==2){  echo 'd-block';} else{ echo 'd-none';} ?>" id="performed_res<?php echo $i; ?>">
												<div class="small text-bold"> Findings </div><input type="text" name="investig_name[ophthal][ophthal_set][<?php echo $i; ?>][performed_res]" class="form-control m-b-4 " value="<?php echo $investigation->performed_res;?>">
											</div>
											
										</td>
										<td>
											<select name="investig_name[ophthal][ophthal_set][<?php echo $i; ?>][eye_side]" class="w-50px">
												<option <?php if($investigation->eye_side=='B/E'){ echo "selected";} ?> value="B/E">B/E</option>
													<option   <?php if($investigation->eye_side=='R'){ echo "selected";} ?> value="R">R</option>
													<option  <?php if($investigation->eye_side=='L'){ echo "selected";} ?> value="L">L</option>
											</select>
										</td>
										<td>
											<button type="button"class="btn-custom aa_'+count+'" onclick="return remove_opthal_test('<?php echo $i; ?>');"><i class="fa fa-times"></i>
											</button>
										</td>
									</tr>
								</table>
								<?php $i++;} } if(!empty($ophthal_data['ophthal_test'])){
									foreach($ophthal_data['ophthal_test'] as $investigation) {
									?>
									<table class="table table-bordered add_opthal_<?php echo $i; ?>">
										<tr>
											<td valign="top" style="width:unset;text-align:left;">
												<div class="d-block">
													<input type="radio" name="investig_name[ophthal][ophthal_test][<?php echo $investigation->test_id;?>][test_radio]" value="1" class="adv_<?php echo $i; ?> advis" onchange="return performed(this.value,'<?php echo $i; ?>');"<?php if($investigation->test_radio==1){ ?> checked <?php } ?>>Advised
												</div>
												<div class="d-block">
													<input type="radio" name="investig_name[ophthal][ophthal_test][<?php echo $investigation->test_id;?>][test_radio]" class="adv_<?php echo $i; ?>" value="2" onchange="return performed(this.value,'<?php echo $i; ?>');"<?php if($investigation->test_radio==2){ ?> checked <?php } ?>>
												Performed</div>
												</td>
												<td valign="top">
													<input type="text" name="investig_name[ophthal][ophthal_test][<?php echo $investigation->test_id;?>][test_name]" class="form-control m-b-4" value="<?php echo $investigation->test_name;?>">
													<input type="hidden" name="investig_name[ophthal][ophthal_test][<?php echo $investigation->test_id;?>][eye_region_id]" class="form-control m-b-4" value="<?php echo $investigation->eye_region_id;?>">
													<input type="hidden" name="investig_name[ophthal][ophthal_test][<?php echo $investigation->test_id;?>][test_id]" class="form-control m-b-4" value="<?php echo $investigation->test_id;?> ">
												<div class="<?php if($investigation->test_radio==2){ echo 'd-block';} else{ echo 'd-none';} ?>" id="performed_res<?php echo $i; ?>">
												<div class="small text-bold"> Findings </div>
													<input type="text" name="investig_name[ophthal][ophthal_test][<?php echo $investigation->test_id;?>][performed_res]" class="form-control m-b-4" value="<?php echo $investigation->performed_res;?>" >
												</div>
												
												
												</td>
												<td valign="top">
													<select name="investig_name[ophthal][ophthal_test][<?php echo $investigation->test_id;?>][eye_side]" class="w-50px">
														<option <?php if($investigation->eye_side=='B/E'){ echo "selected";} ?> value="B/E">B/E</option>
												<option   <?php if($investigation->eye_side=='R'){ echo "selected";} ?> value="R">R</option>
												<option  <?php if($investigation->eye_side=='L'){ echo "selected";} ?> value="L">L</option>
													</select>
												</td>
												<td valign="top">
													<button type="button"class="btn-custom aa_<?php echo $i; ?>" onclick="return remove_opthal_test('<?php echo $i; ?>');"><i class="fa fa-times"></i>
													</button>
												</td>
											</tr>
									</table>
								<?php $i++;}} ?> 
						</div>
					</div>                         
					<br>
					<br>
					<div class="row">
						<div class="col-md-3">
							<label>Comments:</label>
						</div>

						<div class="col-md-5">
							<textarea class="form-control" name="comment_ophthal"><?php echo trim($invest_comment['comment_ophthal']); ?>
							</textarea>			
						</div>

						<div class="col-md-3">
							<ul class="list-unstyled">									
								<li class="m-b-5"> <span class="label-icon">C</span> <span>Custom Created ICD</span></li>
								<li class="m-b-5"> <span class="label-icon">CC</span> <span> Translated ICD</span></li>
							</ul> 			
						</div>
					</div>                  
				</div>
				<!---Ophthal Set ----->
				<!--- Laboratory ---->

				<div role="tabpanel" class="tab-pane" id="Laboratory">					
					<br>	
					<div class="row text-right">
						<div class="col-lg-4"></div>
						<div class="col-lg-5"></div>
						<div class="col-lg-3">
							<div style="position:relative;text-align:left;">
								<input class="form-control" type="text" name="" id="lab_search" onkeyup="return lab1_search(this.value);"placeholder="Search by any Laboratory Investigations Name">
								<div class="search_dropdown_list" id="lab_search_list"></div>                            
							</div>
						</div>		
					</div>
					<br>

					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-6">
							<div>Laboratory Sets</div>
							<select name="lab_set" id="lab_set" class="form-control" multiple="">

								<?php
								if(!empty($lab_set))
								{
									foreach($lab_set as $lab)
									{
										$lab_select = "";
										if($lab->id==$form_data['lab_set'])
										{
											$lab_select = "selected='selected'";
										}
										echo '<option value="'.$lab->id.'" '.$lab.'>'.$lab->set_name.'</option>';
									}
								}
								?> 
							</select>
						</div>

						<div class="col-lg-3 col-md-3 col-sm-6">
							<div>Investigation Tests</div>
							<select name="lab_investigation" id="lab_investig" class="form-control" multiple="">
								<option value="">ALL</option>
								<?php
								if(!empty($lab_investigations))
								{
									foreach($lab_investigations as $lab_investigation)
									{
										$lab_investigation_select = "";
										if($lab_investigation->id==$form_data['ophthal_investigation'])
										{
											$lab_investigation_select = "selected='selected'";
										}
										echo '<option value="'.$lab_investigation->id.'" '.$lab_investigation_select.'>'.$lab_investigation->test_name.'</option>';
									}
								}
								?>
							</select>
						</div>

						<div class="col-lg-6" id="lab_append_div">
						<?php // echo "<pre>"; print_r($laboratory_data['lab_test']);die();
						$j=0;
						if($laboratory_data['lab_set'])
						{
							foreach($laboratory_data['lab_set'] as $investigation) {
								?>         	
								<table class="table table-bordered add_lab_<?php echo $j;?>"><tr>
									<td style="width:unset;text-align:left;">
										<div class="d-block">
											<input type="radio" name="investig_name[lab][lab_set][<?php echo $j;?>][lab_radio]" value="1" class="adv_<?php echo $j;?> ladvis" onchange="return lab_performed(this.value,'<?php echo $j;?>');" <?php if($investigation->lab_radio==1){ ?> checked <?php } ?>>Advised
										</div>
										<div class="d-block">
											<input type="radio" name="investig_name[lab][lab_set][<?php echo $j;?>][lab_radio]" class="adv_<?php echo $j;?>" value="2" onchange="return lab_performed(this.value,'<?php echo $j;?>');" <?php if($investigation->lab_radio==2){ ?> checked <?php } ?>>
										Performed</div>
									</td>
									<td>
										<input type="text" name="investig_name[lab][lab_set][<?php echo $j;?>][test_name]" class="form-control m-b-4" value="<?php echo $investigation->test_name;?>">
									<div class=" <?php if($investigation->lab_radio==2){ echo 'd-block';} else{ echo 'd-none';}?>" id="lab_performed_res<?php echo $j;?>">
									 <div class="small text-bold"> Findings </div>
										<input type="text" name="investig_name[lab][lab_set][<?php echo $j;?>][lab_performed_res]" class="form-control m-b-4" value="<?php echo $investigation->lab_performed_res;?>">
									</div>
									
										<input type="hidden" name="investig_name[lab][lab_set][<?php echo $j;?>][lab_performed_res]" class="<?php if($investigation->lab_radio==1){ echo 'd-block';} else{ echo 'd-none';}?>" >
									
									
										<input type="hidden" name="investig_name[lab][lab_set][<?php echo $j;?>][test_id]"  class="form-control m-b-4" value="<?php echo $investigation->test_id;?>">
										<input type="hidden" name="investig_name[lab][lab_set][<?php echo $j;?>][lab_test_id]" class="form-control m-b-4" value="<?php echo $investigation->lab_set_id;?>">
									</td>
									<td>
										<button type="button"class="btn-custom aa_<?php echo $j;?>" onclick="return remove_lab_test('<?php echo $j;?>');"><i class="fa fa-times"></i></button>
									</td>
								</tr>
							</table>	
							<?php $j++; } } 
					if(!empty($laboratory_data['lab_test'])){
						foreach($laboratory_data['lab_test'] as $investigation) {
						 ?>
						 <table class="table table-bordered add_lab_<?php echo $j;?>"><tr><td style="width:unset;text-align:left;"><div class="d-block"><input type="radio" name="investig_name[lab][lab_test][<?php echo $investigation->lab_test_id;?>][test_radio]" value="1" class="adv_<?php echo $j;?> ladvis" onchange="return lab_performed(this.value,'<?php echo $j;?>');" <?php if($investigation->test_radio==1){ ?> checked <?php } ?>>Advised</div><div class="d-block"><input type="radio" name="investig_name[lab][lab_test][<?php echo $investigation->lab_test_id;?>][test_radio]" class="adv_<?php echo $j;?>" value="2" onchange="return lab_performed(this.value,'<?php echo $j;?>');" <?php if($investigation->test_radio==2){ ?> checked <?php } ?>>Performed</div></td><td><input type="text" name="investig_name[lab][lab_test][<?php echo $investigation->lab_test_id;?>][investig_name]" id="investig_name" class="form-control m-b-4" value="<?php echo $investigation->investig_name;?>">
						 	
						 <div class="<?php if($investigation->test_radio==2){ echo 'd-block';} else { echo 'd-none'; } ?>" id="lab_performed_res<?php echo $j;?>">
							<div class="small text-bold"> Findings </div>
						 	<input type="text" name="investig_name[lab][lab_test][<?php echo $investigation->lab_test_id;?>][lab_performed_res]" class="form-control m-b-4" value="<?php echo $investigation->lab_performed_res;?> " >
						 	</div>
								<input type="hidden" name="investig_name[lab][lab_test][<?php echo $investigation->lab_test_id;?>][lab_performed_res]" class="<?php if($investigation->test_radio==1){ echo 'd-block';} else { echo 'd-none'; } ?>" >
					
						 		<input type="hidden" name="investig_id[]"  class="form-control m-b-4" value="<?php echo $investigation->lab_test_id;?>"><input type="hidden" name="investig_name[lab][lab_test][<?php echo $investigation->lab_test_id;?>][lab_test_id]"  class="form-control m-b-4" value="<?php echo $investigation->lab_test_id;?>"></td><td><button type="button"class="btn-custom aa_<?php echo $j;?>" onclick="return remove_lab_test('<?php echo $j;?>');"><i class="fa fa-times"></i></button></td></tr></table>
						<?php $j++; }} ?>
						</div>
					</div>
					<br><br>
					<div class="row">
						<div class="col-md-3">
							<label>Comments:</label>
						</div>

						<div class="col-md-5">
							<textarea class="form-control" name="comment_lab"><?php echo $invest_comment['comment_lab']; ?></textarea>			
						</div>

						<div class="col-md-3">
							<ul class="list-unstyled">									
								<li class="m-b-5"> <span class="label-icon">C</span> <span>Custom Created ICD</span></li>
								<li class="m-b-5"> <span class="label-icon">CC</span> <span> Translated ICD</span></li>
							</ul> 			
						</div>
					</div>
				</div>
				<!--- Laboratory ---->

				<!--- Radiology ---->

				<div role="tabpanel" class="tab-pane" id="Radiology">

					<br>	
					<div class="row text-right">
						<div class="col-lg-4"></div>
						<div class="col-lg-5"></div>
						<div class="col-lg-3">
							<div style="position:relative;text-align:left;">
								<input class="form-control" type="text" name="" id="radio_search" onkeyup="return radio1_search(this.value);"placeholder="Search by any Radiology Investigations Name">
								<div class="search_dropdown_list" id="radio_search_list"></div>
							</div>
						</div>		
					</div>
					<br>

					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-6">
							<div>Radiology Sets</div>
							<select name="radiology_set" id="radiology_set" class="form-control" multiple="">

								<?php
								if(!empty($radiology_set))
								{
									foreach($radiology_set as $radio)
									{
										$radio_select = "";
										if($radio->id==$form_data['radiology_set'])
										{
											$radio_select = "selected='selected'";
										}
										echo '<option value="'.$radio->id.'" '.$radio_select.'>'.$radio->set_name.'</option>';
									}
								}
								?>
							</select>
						</div>

						<div class="col-lg-3 col-md-4 col-sm-6">
							<div>
							Xrays, MRI, CT & Others</div>
							<select name="provisional_diagnosis" id="radiology_investig" class="form-control" multiple="">
								
								<?php
								if(!empty($xray_mri_investigation))
								{
									foreach($xray_mri_investigation as $xray_mri)
									{
										$xray_mri_select = "";
										if($xray_mri->id==$form_data['ophthal_set'])
										{
											$xray_mri_select = "selected='selected'";
										}
										echo '<option value="'.$xray_mri->id.'" '.$xray_mri_select.'>'.$xray_mri->test_name.'</option>';
									}
								}
								?>
							</select>
						</div>

					<div class="col-lg-6" id="radiology_append_div">        
					<?php // echo "<pre>"; print_r($radiology_data);die();
						$k=0;
						if($radiology_data['radio_set'])
						{
							foreach($radiology_data['radio_set'] as $investigation) {
								?>              	
						<table class="table table-bordered add_radio_<?php echo $k;?>">
							<tr>
								<td style="width:unset;text-align:left;">
									<div class="d-block">
									 <input type="radio" name="investig_name[radio][radio_set][<?php echo $k;?>][radiolo_radio]" value="1" class="adv_<?php echo $k;?> radvis" onchange="return radio_performed(this.value,'<?php echo $k;?>');" <?php if($investigation->radiolo_radio==1){ ?> checked <?php } ?>> Advised
									</div>
									<div class="d-block">
										<input type="radio" name="investig_name[radio][radio_set][<?php echo $k;?>][radiolo_radio]" class="adv_<?php echo $k;?>" value="2" onchange="return radio_performed(this.value,'<?php echo $k;?>');" <?php if($investigation->radiolo_radio==2){ ?> checked <?php } ?>> Performed
									</div>
								</td>
								<td>
									<input type="text" name="investig_name[radio][radio_set][<?php echo $k;?>][test_name]" id="investig_name" class="form-control m-b-4" value="<?php echo $investigation->test_name;?>">
								<div class="<?php if($investigation->radiolo_radio==2){ echo 'd-block';} else{ echo 'd-none'; } ?>" id="radio_performed_res<?php echo $k;?>">
								  <div class="small text-bold"> Findings </div>
									<input type="text" name="investig_name[radio][radio_set][<?php echo $k;?>][radio_performed_res]" class="form-control m-b-4" value="<?php echo $investigation->radio_performed_res;?>">
								</div>
								
									<input type="hidden" name="investig_name[radio][radio_set][<?php echo $k;?>][test_id]"  class="form-control m-b-4" value="<?php echo $investigation->test_id;?>">
									<input type="hidden" name="investig_name[radio][radio_set][<?php echo $k;?>][radio_set_id]"  class="form-control m-b-4" value="<?php echo $investigation->radio_set_id;?>">
								</td>
									<td>
										<select name="" id="" class="form-control">
											<option selected="selected" value="" id="">w/o contrast</option>
											<option value="">with contrast</option>
											<option value="">with complete screening</option>
											<option value="">screening of other region</option>
											<option value="">3D-reconstruction</option>
										</select>
									</td>
								<td>
									<button type="button"class="btn-custom aa_<?php echo $k;?>" onclick="return remove_radio_test('<?php echo $k;?>');"><i class="fa fa-times"></i>
									</button>
								</td>
							</tr>
						</table>
					<?php $k++; }} 
					if(!empty($radiology_data['radio_test'])){
						foreach($radiology_data['radio_test'] as $investigation) {?>
							<table class="table table-bordered add_radio_<?php echo $k;?>">
								<tr>
									<td style="width:unset;text-align:left;">
										<div class="d-block">
											<input type="radio" name="investig_name[radio][radio_test][<?php echo $investigation->test_id;?>][test_radio]" value="1" class="adv_<?php echo $k;?> radvis" onchange="return radio_performed(this.value,'<?php echo $k;?>');" <?php if($investigation->test_radio==1){ ?> checked <?php } ?>>Advised
										</div>
										<div class="d-block">
											<input type="radio" name="investig_name[radio][radio_test][<?php echo $investigation->test_id;?>][test_radio]" class="adv_<?php echo $k;?>" value="2" onchange="return radio_performed(this.value,'<?php echo $k;?>');" <?php if($investigation->test_radio==2){ ?> checked <?php } ?>> Performed
										</div>
									</td>
									<td>
										<input type="text" name="investig_name[radio][radio_test][<?php echo $investigation->test_id;?>][test_name]" class="form-control m-b-4" value="<?php echo $investigation->test_name;?>">
										<div class="<?php if($investigation->test_radio==2){ echo 'd-block';} else{ echo 'd-none';} ?>" id="radio_performed_res<?php echo $k;?>">
								  		<div class="small text-bold"> Findings </div>
											<input type="text" name="investig_name[radio][radio_test][<?php echo $investigation->test_id;?>][radio_performed_res]" class="form-control m-b-4" value="<?php echo $investigation->radio_performed_res;?>">
										</div>
										<input type="hidden" name="investig_name[radio][radio_test][<?php echo $investigation->test_id;?>][test_id]"  class="form-control m-b-4" value="<?php echo $investigation->test_id;?>">
									</td>
									<td>
										<select name="" id="" class="form-control">
											<option selected="selected" value="" id="">w/o contrast</option>
											<option value="">with contrast</option>
											<option value="">with complete screening</option>
											<option value="">screening of other region</option>
											<option value="">3D-reconstruction</option>
										</select>
									</td>
									<td>
										<button type="button"class="btn-custom aa_<?php echo $k;?>" onclick="return remove_opthal_test('<?php echo $k;?>');"><i class="fa fa-times"></i>
										</button>
									</td>
								</tr>
							</table>
						<?php $k++; }} ?>
						</div>
					</div>
					<br><br>
					<div class="row">
						<div class="col-md-3">
							<label>Comments:</label>
						</div>

						<div class="col-md-5">
							<textarea class="form-control" name="comment_radiology"><?php echo $invest_comment['comment_radiology']; ?></textarea>			
						</div>

						<div class="col-md-3">
							<ul class="list-unstyled">									
								<li class="m-b-5"> <span class="label-icon">C</span> <span>Custom Created ICD</span></li>
								<li class="m-b-5"> <span class="label-icon">CC</span> <span> Translated ICD</span></li>
							</ul> 			
						</div>
					</div>

				</div>
				<!--- Radiology ---->
			</div>
		</div>
	</div>
</section>

<script>


		$('#eye_region').change(function(){
			var test_head_id = $(this).val(); 
		   $.ajax({url: "<?php echo base_url(); ?>eye/ophthal_set/eye_region_test/"+test_head_id, 
		   	success: function(result)
		   	{ 
		   		$('#investig_box').show();
		   		$('#investig').html(result);   
		   	} 
		   }); 
		})  

					/* Ophthal Set */						
		$(document).ready(function(){
					$('#investig_box').hide();
					var total_tr='';
					var count = 0;

					$('#investig').change(function(){
						total_tr= $('#append_div').children('table').length;

						count = total_tr;
						var ophthal_set=$('#ophthal_set').val();
						var eye_region=$('#eye_region').val();
						var invest=$('#investig').val();
						var investig_name= $('#investig option:selected').html();

						$('#append_div').append('<table class="table table-bordered add_opthal_'+count+'"><tr><td valign="top" style="width:unset;text-align:left;"><div class="d-block"><input type="radio" name="investig_name[ophthal][ophthal_test]['+invest+'][test_radio]" value="1" class="adv_'+count+' advis" onchange="return performed(this.value,'+count+');" checked="checked"> Advised</div><div class="d-block"><input type="radio" name="investig_name[ophthal][ophthal_test]['+invest+'][test_radio]" class="adv_'+count+'" value="2" onchange="return performed(this.value,'+count+');"> Performed</div></td><td valign="top"><input type="text" name="investig_name[ophthal][ophthal_test]['+invest+'][test_name]" class="form-control m-b-4" value="'+investig_name +' "><input type="hidden" name="investig_name[ophthal][ophthal_test]['+invest+'][eye_region_id]" class="form-control m-b-4" value="'+eye_region+' "><input type="hidden" name="investig_name[ophthal][ophthal_test]['+invest+'][test_id]" class="form-control m-b-4" value="'+invest+' "><div id="performed_res'+count+'"  style="display:none;">							<div class="small text-bold"> Findings </div><input type="text" name="investig_name[ophthal][ophthal_test]['+invest+'][performed_res]" class="form-control m-b-4" value=""></div></td><td valign="top"><select name="investig_name[ophthal][ophthal_test]['+invest+'][eye_side]" id="eye_side'+invest+'" class="w-50px"><option value="B/E">B/E</option><option value="R">R</option><option value="L">L</option></select></td><td valign="top"><button type="button"class="btn-custom aa_'+count+'" onclick="return remove_opthal_test('+count+');"><i class="fa fa-times"></i></button></td></tr></table>');

						count++;

					})	

					$('#ophthal_set').change(function(){
						total_tr= $('#append_div').children('table').length;
						count=total_tr;
						var output='';
						var ophthal_set=$('#ophthal_set').val();
						var eye_region=$('#eye_region').val();
						var invest=$('#ophthal_set').val();
						var path="<?php echo base_url() ;?>eye/ophthal_set/ophthal_set_list/"+invest;

						$.ajax({
							url: path,
							type: "post",
							data: $(this).serialize(),
							success: function(result) 
							{
								var inv=$.parseJSON(result);
								for (i=0; i < inv.length; i++){
									var LRselected='';
									var Rselected='';
									var Lselected='';
									if(inv[i].eye_side=='B/E')
									{
										LRselected='selected="selected"';
									}
									else if(inv[i].eye_side=='R')
									{
										Rselected='selected="selected"';
									}
									else if(inv[i].eye_side=='L')
									{
										Lselected='selected="selected"';
									}

									output +='<table class="table table-bordered add_opthal_'+count+'"><tr><td style="width:unset;text-align:left;"><div class="d-block"><input type="radio" name="investig_name[ophthal][ophthal_set]['+count+'][radio]" value="1" class="adv_'+count+' advis" onchange="return performed(this.value,'+count+');" checked="checked"> Advised</div><div class="d-block"><input type="radio" name="investig_name[ophthal][ophthal_set]['+count+'][radio]" class="adv_'+count+'" value="2" onchange="return performed(this.value,'+count+');"> Performed</div></td><td><input type="text" name="investig_name[ophthal][ophthal_set]['+count+'][test_name]"  class="form-control m-b-4" value="'+inv[i].investig_name+' "><input type="hidden" name="investig_name[ophthal][ophthal_set]['+count+'][set_id]"  class="form-control m-b-4" value="'+ophthal_set+' "><div id="performed_res'+count+'"  style="display:none;"><div class="small text-bold"> Findings </div><input type="text" name="investig_name[ophthal][ophthal_set]['+count+'][performed_res]" class="form-control m-b-4" value=""></div></td><td><select name="investig_name[ophthal][ophthal_set]['+count+'][eye_side]" id="eye_side'+inv[i].test_id+'" class="w-50px"><option value="B/E" '+LRselected+'>B/E</option><option value="R" '+Rselected+'>R</option><option value="L" '+Lselected+'>L</option></select></td><td><button type="button"class="btn-custom aa_'+count+'" onclick="return remove_opthal_test('+count+');"><i class="fa fa-times"></i></button></td></tr></table>';
									count++;

								}
								$('#append_div').append(output);
							}
						}); 

					});
			
			/* Ophthal Set End */	

			/* Lab Set */	
		
					var total_tr_count='';

					var count_lab = 0;

					$('#lab_investig').change(function(){
						total_tr_count= $('#lab_append_div').children('table').length;

						count_lab = total_tr_count;
						var invest=$('#lab_investig').val();
						var investig_name= $('#lab_investig option:selected').html();
						$('#lab_append_div').append('<table class="table table-bordered add_invest_'+count_lab+'"><tr><td style="width:unset;text-align:left;"><div class="d-block"><input type="radio" name="investig_name[lab][lab_test]['+invest+'][test_radio]" value="1" class="adv_'+count_lab+' ladvis" onchange="return lab_performed(this.value,'+count_lab+');" checked> Advised</div><div class="d-block"><input type="radio" name="investig_name[lab][lab_test]['+invest+'][test_radio]" class="adv_'+count_lab+'" value="2" onchange="return lab_performed(this.value,'+count_lab+');"> Performed</div></td><td><input type="text" name="investig_name[lab][lab_test]['+invest+'][investig_name]" id="investig_name" class="form-control m-b-4" value="'+investig_name +' "><div id="lab_performed_res'+count_lab+'"  style="display:none;">							<div class="small text-bold"> Findings </div><input type="text" name="investig_name[lab][lab_test]['+invest+'][lab_performed_res]" class="form-control m-b-4" value=""></div><input type="hidden" name="investig_id[]"  class="form-control m-b-4" value="'+invest +' "><input type="hidden" name="investig_name[lab][lab_test]['+invest+'][lab_test_id]"  class="form-control m-b-4" value="'+invest +' "></td><td><button type="button"class="btn-custom aa_'+count_lab+'" onclick="return remove_invest_test('+count_lab+');"><i class="fa fa-times"></i></button></td></tr></table>');

						count_lab++;

					});

					$('#lab_set').change(function(){
						total_tr_count= $('#lab_append_div').children('table').length;
						count_lab=total_tr_count;
						var output='';
						var lab_set=$('#lab_set').val();
						var invest=$('#lab_set').val();
			      var path="<?php echo base_url() ;?>eye/laboratory_set/lab_set_list/"+invest;

			      $.ajax({
			      	url: path,
			      	type: "post",
			      	data: $(this).serialize(),
			      	success: function(result) 
			      	{


	      		var inv=$.parseJSON(result);


	      		for (i=0; i < inv.length; i++){

	      			var LRselected='';
	      			var Rselected='';
	      			var Lselected='';
	      			if(inv[i].eye_side=='B/E')
	      			{
	      				LRselected='selected="selected"';
	      			}
	      			else if(inv[i].eye_side=='R')
	      			{
	      				Rselected='selected="selected"';
	      			}
	      			else if(inv[i].eye_side=='L')
	      			{
	      				Lselected='selected="selected"';
	      			}

      			output +='<table class="table table-bordered add_labo_'+count_lab+'"><tr><td style="width:unset;text-align:left;"><div class="d-block"><input type="radio" name="investig_name[lab][lab_set]['+count_lab+'][lab_radio]" value="1" class="adv_'+count_lab+' ladvis" onchange="return lab_performed(this.value,'+count_lab+');" checked="checked"> Advised</div><div class="d-block"><input type="radio" name="investig_name[lab][lab_set]['+count_lab+'][lab_radio]" class="adv_'+count_lab+'" value="2" onchange="return lab_performed(this.value,'+count_lab+');"> Performed</div></td><td><input type="text" name="investig_name[lab][lab_set]['+count_lab+'][test_name]" id="investig_name" class="form-control m-b-4" value="'+inv[i].investig_name+' "><div id="lab_performed_res'+count_lab+'"  style="display:none;">							<div class="small text-bold"> Findings </div><input type="text" name="investig_name[lab][lab_set]['+count_lab+'][lab_performed_res]" class="form-control m-b-4" value="" ></div><input type="hidden" name="investig_name[lab][lab_set]['+count_lab+'][test_id]"  class="form-control m-b-4" value="'+inv[i].test_id+' "><input type="hidden" name="investig_name[lab][lab_set]['+count_lab+'][lab_set_id]"  class="form-control m-b-4" value="'+lab_set +' "></td><td><button type="button"class="btn-custom aa_'+count_lab+'" onclick="return remove_labo_test('+count_lab+');"><i class="fa fa-times"></i></button></td></tr></table>';
      			count_lab++;

      		}
      		$('#lab_append_div').append(output);


      	}
      }); 

  });
		

				/* Lab Set End */	

				/* Radiology Set */

					var total_radio_tr='';

					var count_radio = 0;

					$('#radiology_investig').change(function(){
						total_radio_tr= $('#radiology_append_div').children('table').length;
						count_radio = total_radio_tr;
						var invest=$('#radiology_investig').val();
						var investig_name= $('#radiology_investig option:selected').html();

						$('#radiology_append_div').append('<table class="table table-bordered add_radio_'+count_radio+'"><tr><td style="width:unset;text-align:left;"><div class="d-block"><input type="radio" name="investig_name[radio][radio_test]['+invest+'][test_radio]" value="1" class="adv_'+count_radio+' radvis" onchange="return radio_performed(this.value,'+count_radio+');" checked="checked"> Advised</div><div class="d-block"><input type="radio" name="investig_name[radio][radio_test]['+invest+'][test_radio]" class="adv_'+count_radio+'" value="2" onchange="return radio_performed(this.value,'+count_radio+');"> Performed</div></td><td><input type="text" name="investig_name[radio][radio_test]['+invest+'][test_name]" id="investig_name" class="form-control m-b-4" value="'+investig_name +' "><div id="radio_performed_res'+count_radio+'"  style="display:none;">							<div class="small text-bold"> Findings </div><input type="text" name="investig_name[radio][radio_test]['+invest+'][radio_performed_res]" class="form-control m-b-4" value=" "></div><input type="hidden" name="investig_name[radio][radio_test]['+invest+'][test_id]"  class="form-control m-b-4" value="'+invest +' "></td><td><button type="button"class="btn-custom aa_'+count_radio+'" onclick="return remove_radio_test('+count_radio+');"><i class="fa fa-times"></i></button></td></tr></table>');

						count_radio++;

					})

					$('#radiology_set').change(function(){
						total_radio_tr= $('#radiology_append_div').children('table').length;
						count_radio=total_radio_tr;
						var output='';
						var invest=$('#radiology_set').val();

						// var radio_test=$('#radiology_investig').val();
						//alert(radio_test);
			      var path="<?php echo base_url() ;?>eye/radiology_set/radio_set_list/"+invest;

			      $.ajax({
			      	url: path,
			      	type: "post",
			      	data: $(this).serialize(),
			      	success: function(result) 
			      	{

	      		var inv=$.parseJSON(result);


	      		for (i=0; i < inv.length; i++){

	      			var LRselected='';
	      			var Rselected='';
	      			var Lselected='';
	      			if(inv[i].eye_side=='B/E')
	      			{
	      				LRselected='selected="selected"';
	      			}
	      			else if(inv[i].eye_side=='R')
	      			{
	      				Rselected='selected="selected"';
	      			}
	      			else if(inv[i].eye_side=='L')
	      			{
	      				Lselected='selected="selected"';
	      			}

      			output +='<table class="table table-bordered add_radio_'+count_radio+'"><tr><td style="width:unset;text-align:left;"><div class="d-block"><input type="radio" name="investig_name[radio][radio_set]['+count_radio+'][radiolo_radio]" value="1" class="adv_'+count_radio+' radvis" onchange="return radio_performed(this.value,'+count_radio+');" checked="checked"> Advised</div><div class="d-block"><input type="radio" name="investig_name[radio][radio_set]['+count_radio+'][radiolo_radio]" class="adv_'+count_radio+'" value="2" onchange="return radio_performed(this.value,'+count_radio+');"> Performed</div></td><td><input type="text" name="investig_name[radio][radio_set]['+count_radio+'][test_name]" id="investig_name" class="form-control m-b-4" value="'+inv[i].investig_name+' "><div id="radio_performed_res'+count_radio+'" style="display:none;">							<div class="small text-bold"> Findings </div><input type="text" name="investig_name[radio][radio_set]['+count_radio+'][radio_performed_res]"  class="form-control m-b-4" value=""></div><input type="hidden" name="investig_name[radio][radio_set]['+count_radio+'][test_id]"  class="form-control m-b-4" value="'+inv[i].test_id+' "><input type="hidden" name="investig_name[radio][radio_set]['+count_radio+'][radio_set_id]"  class="form-control m-b-4" value="'+invest +' "></td><td><button type="button"class="btn-custom aa_'+count_radio+'" onclick="return remove_radio_test('+count_radio+');"><i class="fa fa-times"></i></button></td></tr></table>';
      			count_radio++;

      		}
      		$('#radiology_append_div').append(output);


      	}
      }); 

  });
		

				/* Radiology Set  End*/	


					var perform=$('.advis').val();
					if(perform==1)
					{
						$('#performed_res').hide();
					}
					else if(perform==2){
						$('#performed_res').show();
					}

					var lab_perform=$('.ladvis').val();
					if(lab_perform==1)
					{
						$('#lab_performed_res').hide();
					}
					else if(perform==2){
						$('#lab_performed_res').show();
					}
				});

				function remove_test(count)
				{
					$('.add_'+count).remove();
				}
				
				function remove_opthal_test(count)
				{
					$('.add_opthal_'+count).remove();
				}
				function remove_invest_test(count)
				{
					$('.add_invest_'+count).remove();
				}
				function remove_labo_test(count)
				{
					$('.add_labo_'+count).remove();
				}
				
				function remove_radio_test(count)
				{
					$('.add_radio_'+count).remove();
				}
				
				function remove_lab_test(count)
				{
					$('.add_lab_'+count).remove();
				}
				
				
				
				
				
			
				
				
				
				
				

				function performed(val,id)
				{
					var val=val;
					if(val==1)
					{
						$('#performed_res'+id).hide();
					}
					else if(val==2){
						$('#performed_res'+id).show();
					}

				}
				function lab_performed(val,id)
				{
					var val=val;
					if(val==1)
					{
						$('#lab_performed_res'+id).hide();
					}
					else if(val==2){
						$('#lab_performed_res'+id).show();
					}

				}

				function radio_performed(val,id)
				{
					var val=val;
					if(val==1)
					{
						$('#radio_performed_res'+id).hide();
					}
					else if(val==2){
						$('#radio_performed_res'+id).show();
					}

				}


				/* Search */

				function ophthal_search(keyword)
				{
						$.ajax({
							url:"<?php echo base_url(); ?>eye/ophthal_set/investigation_search/"+keyword,
							success: function(data) {
								$('#ophthal_search_list').css('display','block');
								$('#ophthal_search_list').html(data);
								$('.append_row_opt').click(function(){
									 var invest_id=$(this).attr('data-id');
									 var eye_region_id=$(this).attr('data-type');
									 var investig_name= $(this).attr('rel');
									ophthal_investig(invest_id,eye_region_id,investig_name);
									$("#ophthal_search_list").css('display','none');
									$("#ophthal_search").val('');
								});
							}

						}); 
				}

				function ophthal_investig(invest_id,eye_region_id,investig_name)
				{
					
					var total_tr='';
					var count = 0;		
						total_tr= $('#append_div').children('table').length;

						count = total_tr;
						var eye_region=eye_region_id;
						var invest=invest_id;
						var investig_name= investig_name;

						$('#append_div').append('<table class="table table-bordered add_opthal_'+count+'"><tr><td style="width:unset;text-align:left;"><div class="d-block"><input type="radio" name="investig_name[ophthal][ophthal_test]['+invest+'][test_radio]" value="1" class="adv_'+count+' advis" onchange="return performed(this.value,'+count+');" checked="checked"> Advised</div><div class="d-block"><input type="radio" name="investig_name[ophthal][ophthal_test]['+invest+'][test_radio]" class="adv_'+count+'" value="2" onchange="return performed(this.value,'+count+');"> Performed</div></td><td><input type="text" name="investig_name[ophthal][ophthal_test]['+invest+'][test_name]" class="form-control m-b-4" value="'+investig_name +' "><input type="hidden" name="investig_name[ophthal][ophthal_test]['+invest+'][eye_region_id]" class="form-control m-b-4" value="'+eye_region+' "><input type="hidden" name="investig_name[ophthal][ophthal_test]['+invest+'][test_id]" class="form-control m-b-4" value="'+invest+' "><div id="performed_res'+count+'" style="display:none;">							<div class="small text-bold"> Findings </div><input type="text" name="investig_name[ophthal][ophthal_test]['+invest+'][performed_res]"  class="form-control m-b-4" value=""></div></td><td><select name="investig_name[ophthal][ophthal_test]['+invest+'][eye_side]" id="eye_side'+invest+'" class="w-50px"><option value="B/E">B/E</option><option value="R">R</option><option value="L">L</option></select></td><td><button type="button"class="btn-custom aa_'+count+'" onclick="return remove_opthal_test('+count+');"><i class="fa fa-times"></i></button></td></tr></table>');

						count++;
				}


			
				function lab1_search(keyword)
				{
					
						$.ajax({
							url:"<?php echo base_url(); ?>eye/laboratory_set/investigation_search/"+keyword,
							success: function(data) {
								
								$('#lab_search_list').css('display','block');
								$('#lab_search_list').html(data);
								$('.append_row_opt').click(function(){
									 var invest_id=$(this).attr('data-id');
									 var investig_name= $(this).attr('rel');
									lab_investig(invest_id,investig_name);
									$("#lab_search_list").css('display','none');
									$("#lab_search").val('');
								});
							}

						}); 
				}

				function lab_investig(invest_id,investig_name)
				{
					
					var total_tr='';
					var count_lab = 0;		
						total_tr= $('#lab_append_div').children('table').length;

						count_lab = total_tr;
						var invest=invest_id;
						var investig_name= investig_name;
						$('#lab_append_div').append('<table class="table table-bordered add_lab_'+count_lab+'"><tr><td style="width:unset;text-align:left;"><div class="d-block"><input type="radio" name="investig_name[lab][lab_test]['+invest+'][test_radio]" value="1" class="adv_'+count_lab+' ladvis" onchange="return lab_performed(this.value,'+count_lab+');" checked> Advised</div><div class="d-block"><input type="radio" name="investig_name[lab][lab_test]['+invest+'][test_radio]" class="adv_'+count_lab+'" value="2" onchange="return lab_performed(this.value,'+count_lab+');"> Performed</div></td><td><input type="text" name="investig_name[lab][lab_test]['+invest+'][investig_name]" id="investig_name" class="form-control m-b-4" value="'+investig_name +' "><input type="text" name="investig_name[lab][lab_test]['+invest+'][lab_performed_res]" id="lab_performed_res'+count_lab+'" class="form-control m-b-4" value=" " style="display:none;><input type="hidden" name="investig_id[]"  class="form-control m-b-4" value="'+invest +' "><input type="hidden" name="investig_name[lab][lab_test]['+invest+'][lab_test_id]"  class="form-control m-b-4" value="'+invest +' "></td><td><button type="button"class="btn-custom aa_'+count_lab+'" onclick="return remove_lab_test('+count_lab+');"><i class="fa fa-times"></i></button></td></tr></table>');

						count_lab++;
				}


				
				function radio1_search(keyword)
				{
						$.ajax({
							url:"<?php echo base_url(); ?>eye/radiology_set/investigation_search/"+keyword,
							success: function(data) {
								
								$('#radio_search_list').css('display','block');
								$('#radio_search_list').html(data);
								$('.append_row_opt').click(function(){
									 var invest_id=$(this).attr('data-id');
									 var investig_name= $(this).attr('rel');
									radio_investig(invest_id,investig_name);
									$("#radio_search_list").css('display','none');
									$("#radio_search").val('');
								});
							}

						}); 
				}

				function radio_investig(invest_id,investig_name)
				{
					
					var total_radio_tr='';
					var count_radio = 0;		
						total_radio_tr= $('#radiology_append_div').children('table').length;
						count_radio = total_radio_tr;
						var invest=invest_id;
						var investig_name=investig_name;

						$('#radiology_append_div').append('<table class="table table-bordered add_radio_'+count_radio+'"><tr><td style="width:unset;text-align:left;"><div class="d-block"><input type="radio" name="investig_name[radio][radio_test]['+invest+'][test_radio]" value="1" class="adv_'+count_radio+' radvis" onchange="return radio_performed(this.value,'+count_radio+');" checked="checked"> Advised</div><div class="d-block"><input type="radio" name="investig_name[radio][radio_test]['+invest+'][test_radio]" class="adv_'+count_radio+'" value="2" onchange="return radio_performed(this.value,'+count_radio+');"> Performed</div></td><td><input type="text" name="investig_name[radio][radio_test]['+invest+'][test_name]" id="investig_name" class="form-control m-b-4" value="'+investig_name +' "><input type="text" name="investig_name[radio][radio_test]['+invest+'][radio_performed_res]" id="radio_performed_res'+count_radio+'" class="form-control m-b-4" value=" " style="display:none;"><input type="hidden" name="investig_name[radio][radio_test]['+invest+'][test_id]"  class="form-control m-b-4" value="'+invest +' "></td><td><button type="button"class="btn-custom aa_'+count_radio+'" onclick="return remove_radio_test('+count_radio+');"><i class="fa fa-times"></i></button></td></tr></table>');

						count_radio++;
				}
				
				/* Search */
			</script>


