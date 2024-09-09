<!-- modal [commonly_used_diagnosis] -->

<div class="modal-dialog" style="min-width:320px;max-width:991px;width:auto;">
	<div class="modal-content">
		<form id="save_commonly_custom" method="post" name="save_commonly_custom">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4><?php echo $page_title; ?></h4>
			</div>
			<div class="modal-body">
				<div class="well">
					<div class="row form-group">
						<div class="col-md-8">
							<input type="text" class="form-control"  name="icd_name" value="<?php echo $icd_detail['icd_name']; ?>" readonly>
							<input type="hidden" id="third_step_icd" value="<?php echo $third_step['third_icd_code']; ?>" >
							<input type="hidden" name="data_type" id="data_type" value="<?php echo $icd_detail['data_type']; ?>" >
							<input type="hidden" name="data_id" id="data_id" value="" >
							<input type="hidden" name="is_code" id="is_code" value="<?php echo $icd_detail['is_code']; ?>">
							<input type="hidden" name="diagno_branch_id" value="<?php echo $branch_id; ?>">
							<input type="hidden" name="diagno_booking_id"  value="<?php echo $booking_id; ?>" > 
							<input type="hidden" name="diagno_patient_id" value="<?php echo $patient_id; ?>" >
							<input type="hidden"  id="eye_side_name" name="eye_side_name" value="<?php echo $icd_detail['icd_name']; ?>" >
						</div>
						<?php if($icd_detail['is_code']==1){ ?>
							<div class="col-md-4" id="dropdownlist">
								
								<select name="eye_side"  class="form-control" id="eye_side" onchange="get_eye_side(this.value);" required>
									<option id="blank" value="">Select</option>
									<?php foreach($icd_dropdowns as $icd_dropdown){ 

										$name_length=strlen($icd_detail['icd_name']);
										$drop_length=strlen($icd_dropdown['descriptions']);

										if($third_step['third_icd_code']==$icd_dropdown['icd_id'])
										{

											$selected_icd="selected=selected";
										}
										else{
											$selected_icd='';
										}

										?>
										<option value="<?php echo $icd_dropdown['icd_id'];?>" <?php echo $selected_icd; ?>><?php echo $icd_dropdown['descriptions']; ?></option>
									<?php } ?>
									<!-- ?php echo substr($icd_dropdown['descriptions'],$name_length,$drop_length); ?> -->	
								</select>
							</div>
						<?php } else{ ?>
							<div class="col-md-4" id="dropdownlist">
								
								<select name="eye_side" onchange="get_eye_side_custom(this.value);"  class="form-control" id="eye_side" >
								    <option value=""> Select Eye</option>
	                                <option <?php if($icd_detail['eyes_tps']=='L'){ echo 'selected';}?> value="L">Left Eye</option>
	                                <option <?php if($icd_detail['eyes_tps']=='R'){ echo 'selected';}?> value="R">Right Eye</option>
	                                <option <?php if($icd_detail['eyes_tps']=='BE'){ echo 'selected';}?> value="BE">Both Eye</option>
							    </select>
							</div>


							<div id="diagnosis_user_boxss" class="col-md-12 m-t-10 <?php if(!empty($icd_detail['eyes_tps'])){ echo "d-block";} else{ echo "d-none";}?>">
								<div class="row form-group">
									<div class="col-md-3">
										<strong>Diagnosis Comment</strong>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control"  value="<?php echo $icd_detail['diagnosis_comment']; ?>" name="diagnosis_comment">
									</div>
								</div>
								<div class="row form-group">
									<div class="col-md-3">
										<strong>User Comment</strong>
									</div>
									<div class="col-md-9">
										<textarea class="form-control" name="user_comment"><?php echo $icd_detail['user_comment']; ?></textarea>
									</div>
								</div>
							</div>

						<?php } ?>
					</div>
					<?php if($icd_detail['is_code']==1){ ?>
						<div id="diagnosis_user_box">
							<div class="row form-group">
								<div class="col-md-3 col-sm-4">
									<strong>Diagnosis Comment</strong>
								</div>
								<div class="col-md-9 col-sm-8">
									<input type="text" class="form-control" name="diagnosis_comment">
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-3 col-sm-4">
									<strong>User Comment</strong>
								</div>
								<div class="col-md-9 col-sm-8">
									<textarea class="form-control" name="user_comment"></textarea>
								</div>
							</div>
						</div>
					<?php } ?>
				</div> <br>
				<div class="well">
					<div class="row form-group">
						<div class="col-md-4">
							<strong>ICD-10-CM Code</strong>
							<input type="text" class="form-control" name="icd_code" id="ice_code" value="<?php echo $icd_detail['icd_code']; ?>" readonly="">
						</div>
						<div class="col-md-4">
							<strong>Entered By</strong>
							<input type="text" class="form-control" id="dgno_enter_by" value="<?php echo get_doctor_name($icd_detail['attended_doctor']); ?>" name="entered_by" value="<?php echo $icd_detail['done_by']; ?>" readonly="">
						</div>
						<div class="col-md-4">
							<strong>Entry Date and time</strong>
							<input type="text" class="form-control" name="entry_date" value="<?php echo $icd_detail['entry_date']; ?>" readonly="">
						</div>
					</div>
				</div><br>
				<div class="row">
					<div class="col-md-12">
						<div class="well">
							<h5 class="text-bold">Disorders of globe  <?php echo $icd_detail['globe_icd']; ?> -></h5>
							<ul class="dia_list">
								<?php if($first_step!=""){ ?>
									<li><strong id="first" class="<?php if($second_step!=""){ echo "text-danger";} else{ echo "text-success";}?>">↳</strong><a href="#"> <?php echo $first_step['first_icd_code']; ?></a> <?php echo $first_step['first_icd_name']; ?></li>
								<?php } ?>
								<li>
									<ul>
										<?php 
										
										if($second_step!=""){ ?>
											<li id="secn"><strong class="<?php if($third_step!=""){ echo "text-danger";} else{ echo "text-success";}?>" id="sec">↳</strong><a href="#"><?php echo $second_step['second_icd_code']; ?></a> <?php echo $second_step['second_icd_name']; ?></li>
										<?php } ?>
										<li>
											<?php 
											if($third_step!=""){ 
												?>
												<ul id="def">
													<li><strong class="text-success">↳</strong><a href="#"><span ><?php echo $third_step['third_icd_code']; ?></span></a> ...<span><?php echo $third_step['third_icd_name']; ?></span></li>
												</ul>
												
											<?php } ?>
											<ul id="abc">
												<li><strong class="text-success">↳</strong><a href="#"><span id="dd"></span></a> ...<span id="ee"></span></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</div> <br>
						<p class="text-danger text-bold">Note: ICD-10-CM code can be between 3-7 in size, you will have to Select the options till you reach the "Specific Code" pointed by - <strong class="text-success">↳</strong></p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="row">
					<div class="col-md-8">
						<p class="text-danger text-bold">↳ - Non-Specific Codes <span class="text-success">|↳ - Specific Code (Specific ICD-10-CM code that can be used to indicate a diagnosis)</span></p>
					</div>
					<div class="col-md-4">
						<button type="button" onclick="save_digno_data();" class="btn-save">Save</button>
						<button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- End of Commonly used --->
<script>
	$(document).ready(function(){
		$('#diagnosis_user_box').hide();
		if($('#third_step_icd').val().length > 0)
		{
			$('#def').show();
			$('#abc').hide();
		}
		else{
			$('#def').hide();
			$('#abc').hide();
		}


	});

	function get_eye_side(id)
	{
		$('#diagnosis_user_box').show();
		var eye_side=$("#eye_side option:selected").text();

		if(id.length ==6)
		{
			$('#secn').hide();
		}

		$('#first').removeClass('text-success');
		$('#first').addClass('text-danger');
		$('#sec').removeClass('text-success');
		$('#sec').addClass('text-danger');
		$('#ice_code').val(id);
		$('#def').hide();
		$('#blank').hide();
		$('#abc').show();
		$('#dd').text(id);
		$('#ee').text(eye_side);
		$('#eye_side_name').val(eye_side);

	}

	function get_eye_side_custom(val)
	{
		if(val !='')
		{
			$('#diagnosis_user_boxss').show();
		}
		else{
		  $('#diagnosis_user_boxss').hide();	
		}
		
	}


	function save_digno_data(){
		var count=$('#append_value section').length;
		var form_data=$('#save_commonly_custom').serialize();
		$.ajax({
			type:"POST",
			url:'<?php echo base_url();?>eye/add_eye_prescription/save_commonly_custom',
			data:form_data,
			success:function(result)
			{
				
				var diagno=$.parseJSON(result);

				if(diagno.diagnosis_comment .length > 0)
				{
					var diagno_comment='<li>&bullet;'+diagno.diagnosis_comment+'</li>';
				}
				else{
					var diagno_comment='';
				}
				if(diagno.user_comment .length > 0)
				{
					var user_comment='<li>&bullet;'+diagno.user_comment+'</li>';
				}
				else{
					var user_comment='';
				}
				var output ='<section id="hierarchy_section_'+diagno.icd_id+'"><div class="row"><div class="col-md-6"><small><span class="text-primary">'+diagno.eye_side_name+'</span> - '+diagno.icd_code+'</small><ul class="small">'+diagno_comment+user_comment+'</ul></div><div class="col-md-6 text-right"><a href="javascript:void(0);" onclick="edit_hierarchy('+diagno.icd_id+');"class="btn-custom"><i class="fa fa-edit"></i></a><a href="javascript:void(0);" onclick="delete_hierarchy('+diagno.icd_id+');"class="btn-custom"><i class="fa fa-times"></i></a></div></div><div class="row"><div class="col-md-12 small">- Added by '+diagno.entered_by+' on '+diagno.entry_date+'</div></div> <input type="hidden" value="'+user_comment+'" name="diagnosis[icd_data][icd]['+count+'][user_comment]">    <input type="hidden" value="'+diagno_comment+'" name="diagnosis[icd_data][icd]['+count+'][diagnosis_comment]">    <input type="hidden" value="'+diagno.eye_side_name+'" name="diagnosis[icd_data][icd]['+count+'][eye_side_name]">    <input type="hidden" value="'+diagno.icd_code+'" name="diagnosis[icd_data][icd]['+count+'][icd_code]">    <input type="hidden" value="'+diagno.icd_id+'" name="diagnosis[icd_data][icd]['+count+'][id]"><input type="hidden" value="'+diagno.entered_by+'" name="diagnosis[icd_data][icd]['+count+'][entered_by]"><input type="hidden" value="'+diagno.entry_date+'" name="diagnosis[icd_data][icd]['+count+'][created_date]"><input type="hidden" value="'+diagno.is_code+'" name="diagnosis[icd_data][icd]['+count+'][is_code]"><hr></section>';

				$('#hierarchy_section_'+diagno.icd_id).remove();
				$('#append_value').append(output);

				$('#commonly_used_diagnosis_modal').modal('hide');	


			}
		});
	}

</script>