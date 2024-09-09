<style>
	input[type=text], .form-control{width:100% !important;height:25px;background:#f8f8f8;font-size:13px;padding:2px;}
	input[type=text].w-40px{width:40px !important;}
	.row hr {margin:3px 0 10px;}
	.row input[type=range]{margin-top:10px;}
	table.table thead {background:#d9edf7 !important;color:black !important;}
	table.table thead >tr> th, table.table-bordered, table.table-bordered td {border-color:#aad4e8 !important;font-size:12px;}
	.row .well {min-height:auto;margin-bottom:0px;}
	.row textarea.form-control {height:75px;width:100%;}
	.input-group-addon {border-radius:0px;border-color:#aaa;} 
</style>

<section>
	<div class="row">
		<div class="col-md-6"><div class="panel-heading bg-theme text-center">R / OD</div> </div>
		<div class="col-md-6"><div class="panel-heading bg-theme text-center">L / OS</div></div>
	</div>
	<div class="row m-t-3">
		<div class="col-md-12 text-right btn_edit"><a href="javascript:void(0)" class="btn_fill" onclick="$('.first_row').toggle();">Edit</a><hr></div>
		<div class="col-md-5">
			<div class="label_name d-inline-block">VISUAL ACUITY (Va)  <i onclick="refraction_va_ltr();" class="fa fa-arrow-right" title="Copy Left to Right"></i>  </div>
			<div class="small first_row">UA: <?php echo $refrtsn_vl_act['refraction_va_ua_l'];?> | PR: <?php echo $refrtsn_vl_act['refraction_va_r_comm'];?> S <?php echo $refrtsn_vl_act['refraction_va_r_comm'];?> | I <?php echo $refrtsn_vl_act['refraction_va_r_comm'];?> | N <?php echo $refrtsn_vl_act['refraction_va_r_comm'];?> | T <?php echo $refrtsn_vl_act['refraction_va_r_comm'];?> | UA Near: <?php echo $refrtsn_vl_act['refraction_va_ua_l_2'];?> | PH: <?php echo $refrtsn_vl_act['refraction_va_ph_l'];?> | Glasses: <?php echo $refrtsn_vl_act['refraction_va_gls_l'];?> | Glasses Near: <?php echo $refrtsn_vl_act['refraction_va_gls_l_2'];?> | Contact Lens: <?php echo $refrtsn_vl_act['refraction_va_cl_l'];?> |</div>
			<div class=" first_row">Comments: <?php echo $refrtsn_vl_act['refraction_va_l_comm'];?> </div>
		</div>
		<div class="col-md-1"></div>
		<div class="col-md-5">
			<div class="label_name d-inline-block"> VISUAL ACUITY (Va) <i onclick="refraction_va_rtl();" class="fa fa-arrow-left" title="Copy Right to Left"></i> </div>
			<div class="small first_row">UA: <?php echo $refrtsn_vl_act['refraction_va_ua_r'];?> | PR: <?php echo $refrtsn_vl_act['refraction_va_r_comm'];?> S <?php echo $refrtsn_vl_act['refraction_va_r_comm'];?> | I <?php echo $refrtsn_vl_act['refraction_va_r_comm'];?> | N <?php echo $refrtsn_vl_act['refraction_va_r_comm'];?> | T <?php echo $refrtsn_vl_act['refraction_va_r_comm'];?> | UA Near: <?php echo $refrtsn_vl_act['refraction_va_ua_r_2'];?> | PH: <?php echo $refrtsn_vl_act['refraction_va_ph_r'];?> | Glasses: <?php echo $refrtsn_vl_act['refraction_va_gls_r'];?> | Glasses Near: <?php echo $refrtsn_vl_act['refraction_va_gls_l_2'];?> | Contact Lens: <?php echo $refrtsn_vl_act['refraction_va_cl_r'];?> |</div>
			<div class=" first_row">Comments: <?php echo $refrtsn_vl_act['refraction_va_r_comm'];?> </div>
		</div>
	</div> <br>
	<!-- 1st row -->
	<div class="row d-none first_row">
		<div class="col-md-1">
			<span>UA</span> <br>
			<small class="mini_outline_btn d-none ua_l_txt" onclick="$('.refraction_va_ua_l').prop('checked',false); $('.ua_l_txt').hide(); $('#refraction_va_ua_l_txt').val(''); $('.refraction_va_ua_l').parent().removeClass('active');">clear</small>
			<div class="m-t-3 d-none ua_l_txt">
				<input type="text" id="refraction_va_ua_l_txt" value="<?php echo $refrtsn_vl_act['refraction_va_ua_l_txt'];?>" name="refraction_va_ua_l_txt" class="form-control">
			</div>
		</div>
		<div class="col-md-4">
			<div class="btn-group">
				<label class="btn_radio_small"><input type="radio" value="PL-" name="refraction_va_ua_l" class="refraction_va_ua_l"> PL-</label>
				<label class="btn_radio_small"><input type="radio" value="PL+" name="refraction_va_ua_l" class="refraction_va_ua_l"> PL+</label>
				<label class="btn_radio_small"><input type="radio" value="FL" name="refraction_va_ua_l" class="refraction_va_ua_l"> FL</label>
				<label class="btn_radio_small"><input type="radio" value="HM" name="refraction_va_ua_l" class="refraction_va_ua_l"> HM</label>
				<label class="btn_radio_small"><input type="radio" value="CFCF" name="refraction_va_ua_l" class="refraction_va_ua_l"> CFCF</label>
				<label class="btn_radio_small"><input type="radio" value="FC" name="refraction_va_ua_l" class="refraction_va_ua_l"> FC</label>
				<label class="btn_radio_small"><input type="radio" value="1/60" name="refraction_va_ua_l" class="refraction_va_ua_l"> 1/60</label>
				<label class="btn_radio_small"><input type="radio" value="2/60" name="refraction_va_ua_l" class="refraction_va_ua_l"> 2/60</label>
				<label class="btn_radio_small"><input type="radio" value="3/60" name="refraction_va_ua_l" class="refraction_va_ua_l"> 3/60</label>
				<label class="btn_radio_small"><input type="radio" value="4/60" name="refraction_va_ua_l" class="refraction_va_ua_l"> 4/60</label>
				<label class="btn_radio_small"><input type="radio" value="5/60" name="refraction_va_ua_l" class="refraction_va_ua_l"> 5/60</label>
				<label class="btn_radio_small"><input type="radio" value="6/60" name="refraction_va_ua_l" class="refraction_va_ua_l"> 6/60</label>
				<label class="btn_radio_small"><input type="radio" value="6/36" name="refraction_va_ua_l" class="refraction_va_ua_l"> 6/36</label>
				<label class="btn_radio_small"><input type="radio" value="6/24" name="refraction_va_ua_l" class="refraction_va_ua_l"> 6/24</label>
				<label class="btn_radio_small"><input type="radio" value="6/18" name="refraction_va_ua_l" class="refraction_va_ua_l"> 6/18</label>
				<label class="btn_radio_small"><input type="radio" value="6/12" name="refraction_va_ua_l" class="refraction_va_ua_l"> 6/12</label>
				<label class="btn_radio_small"><input type="radio" value="6/9" name="refraction_va_ua_l" class="refraction_va_ua_l"> 6/9</label>
				<label class="btn_radio_small"><input type="radio" value="6/7.5" name="refraction_va_ua_l" class="refraction_va_ua_l"> 6/7.5</label>
				<label class="btn_radio_small"><input type="radio" value="6/6" name="refraction_va_ua_l" class="refraction_va_ua_l"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" value="6/5" name="refraction_va_ua_l" class="refraction_va_ua_l"> 6/5</label>
			</div>
		</div>
		<div class="col-md-1 btn-group">
			<label class="btn_radio_small btn-custom"><input type="checkbox" value="P" name="refraction_va_ua_l_p" id="refraction_va_ua_l_p"> P</label>
		</div>
		<div class="col-md-1">
			<span>UA</span>	<br>
			<small class="mini_outline_btn d-none ua_r_txt" onclick="$('.refraction_va_ua_r').prop('checked',false); $('.ua_r_txt').hide(); $('#refraction_va_ua_r_txt').val(''); $('.refraction_va_ua_r').parent().removeClass('active');">clear</small>
			<div class="m-t-3 d-none ua_r_txt">
				<input type="text" id="refraction_va_ua_r_txt" value="<?php echo $refrtsn_vl_act['refraction_va_ua_r_txt'];?>" name="refraction_va_ua_r_txt" class="form-control">
			</div>
		</div>
		<div class="col-md-4">
			<div class="btn-group">
				<label class="btn_radio_small"><input type="radio" value="PL-" name="refraction_va_ua_r" class="refraction_va_ua_r"> PL-</label>
				<label class="btn_radio_small"><input type="radio" value="PL+" name="refraction_va_ua_r" class="refraction_va_ua_r"> PL+</label>
				<label class="btn_radio_small"><input type="radio" value="FL" name="refraction_va_ua_r" class="refraction_va_ua_r"> FL</label>
				<label class="btn_radio_small"><input type="radio" value="HM" name="refraction_va_ua_r" class="refraction_va_ua_r"> HM</label>
				<label class="btn_radio_small"><input type="radio" value="CFCF" name="refraction_va_ua_r" class="refraction_va_ua_r"> CFCF</label>
				<label class="btn_radio_small"><input type="radio" value="FC" name="refraction_va_ua_r" class="refraction_va_ua_r"> FC</label>
				<label class="btn_radio_small"><input type="radio" value="1/60" name="refraction_va_ua_r" class="refraction_va_ua_r"> 1/60</label>
				<label class="btn_radio_small"><input type="radio" value="2/60" name="refraction_va_ua_r" class="refraction_va_ua_r"> 2/60</label>
				<label class="btn_radio_small"><input type="radio" value="3/60" name="refraction_va_ua_r" class="refraction_va_ua_r"> 3/60</label>
				<label class="btn_radio_small"><input type="radio" value="4/60" name="refraction_va_ua_r" class="refraction_va_ua_r"> 4/60</label>
				<label class="btn_radio_small"><input type="radio" value="5/60" name="refraction_va_ua_r" class="refraction_va_ua_r"> 5/60</label>
				<label class="btn_radio_small"><input type="radio" value="6/60" name="refraction_va_ua_r" class="refraction_va_ua_r"> 6/60</label>
				<label class="btn_radio_small"><input type="radio" value="6/36" name="refraction_va_ua_r" class="refraction_va_ua_r"> 6/36</label>
				<label class="btn_radio_small"><input type="radio" value="6/24" name="refraction_va_ua_r" class="refraction_va_ua_r"> 6/24</label>
				<label class="btn_radio_small"><input type="radio" value="6/18" name="refraction_va_ua_r" class="refraction_va_ua_r"> 6/18</label>
				<label class="btn_radio_small"><input type="radio" value="6/12" name="refraction_va_ua_r" class="refraction_va_ua_r"> 6/12</label>
				<label class="btn_radio_small"><input type="radio" value="6/9" name="refraction_va_ua_r" class="refraction_va_ua_r"> 6/9</label>
				<label class="btn_radio_small"><input type="radio" value="6/7.5" name="refraction_va_ua_r" class="refraction_va_ua_r"> 6/7.5</label>
				<label class="btn_radio_small"><input type="radio" value="6/6" name="refraction_va_ua_r" class="refraction_va_ua_r"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" value="6/5" name="refraction_va_ua_r" class="refraction_va_ua_r"> 6/5</label>
			</div>
		</div>
		<div class="col-md-1 btn-group">
			<label class="btn_radio_small btn-custom"><input type="checkbox" value="P" name="refraction_va_ua_r_p" id="refraction_va_ua_r_p"> P</label>
		</div>
	</div>
	<!-- 2nd row -->
	<div class="row m-t-3 d-none first_row">
		<div class="col-md-1">
			<br>
			<small class="mini_outline_btn d-none ua_l2_txt" onclick="$('.refraction_va_ua_l_2').prop('checked',false); $('.ua_l2_txt').hide(); $('#refraction_va_ua_l_2_txt').val(''); $('.refraction_va_ua_l_2').parent().removeClass('active');">clear</small>
			<div class="m-t-3 d-none ua_l2_txt">
				<input type="text" id="refraction_va_ua_l_2_txt" value="<?php echo $refrtsn_vl_act['refraction_va_ua_l_2_txt'];?>" name="refraction_va_ua_l_2_txt" class="form-control">
			</div>
		</div>
		<div class="col-md-4">
			<div class="btn-group">
				<label class="btn_radio_small"><input type="radio" value="N4" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> N4</label>
				<label class="btn_radio_small"><input type="radio" value="N5" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> N5</label>
				<label class="btn_radio_small"><input type="radio" value="N6" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> N6</label>
				<label class="btn_radio_small"><input type="radio" value="N8" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> N8</label>
				<label class="btn_radio_small"><input type="radio" value="N10" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> N10</label>
				<label class="btn_radio_small"><input type="radio" value="N12" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> N12</label>
				<label class="btn_radio_small"><input type="radio" value="N14" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> N14</label>
				<label class="btn_radio_small"><input type="radio" value="N18" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> N18</label>
				<label class="btn_radio_small"><input type="radio" value="N24" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> N24</label>
				<label class="btn_radio_small"><input type="radio" value="N26" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> N26</label>
				<label class="btn_radio_small"><input type="radio" value="N36" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> N36</label>
				<label class="btn_radio_small"><input type="radio" value="<.N36" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> <.N36</label>
				<label class="btn_radio_small"><input type="radio" value="<6/60" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> <6/60</label>
				<label class="btn_radio_small"><input type="radio" value="6/60" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> 6/60</label>
				<label class="btn_radio_small"><input type="radio" value="6/36" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> 6/36</label>
				<label class="btn_radio_small"><input type="radio" value="6/24" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> 6/24</label>
				<label class="btn_radio_small"><input type="radio" value="6/18" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> 6/18</label>
				<label class="btn_radio_small"><input type="radio" value="6/12" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> 6/12</label>
				<label class="btn_radio_small"><input type="radio" value="6/9" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> 6/9</label>
				<label class="btn_radio_small"><input type="radio" value="6/6" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" value="6/7.5" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> 6/7.5</label>
				<label class="btn_radio_small"><input type="radio" value="6/6" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" value="6/5" name="refraction_va_ua_l_2" class="refraction_va_ua_l_2"> 6/5</label>
			</div>
		</div>
		<div class="col-md-1 btn-group">
			<label class="btn_radio_small btn-custom"><input type="checkbox" value="P" name="refraction_va_ua_l_p_2" id="refraction_va_ua_l_p_2"> P</label>
		</div>
		<div class="col-md-1">
			<br>
			<small class="mini_outline_btn d-none ua_r2_txt" onclick="$('.refraction_va_ua_r_2').prop('checked',false); $('.ua_r2_txt').hide(); $('#refraction_va_ua_r_2_txt').val(''); $('.refraction_va_ua_r_2').parent().removeClass('active');">clear</small>
			<div class="m-t-3 d-none ua_r2_txt">
				<input type="text" id="refraction_va_ua_r_2_txt" value="<?php echo $refrtsn_vl_act['refraction_va_ua_r_2_txt'];?>" name="refraction_va_ua_r_2_txt" class="form-control">
			</div>
		</div>
		<div class="col-md-4">
			<div class="btn-group">
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="N4"> N4</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="N5"> N5</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="N6"> N6</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="N8"> N8</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="N10"> N10</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="N12"> N12</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="N14"> N14</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="N18"> N18</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="N24"> N24</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="N26"> N26</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="N36"> N36</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="<.N36"> <.N36</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="<6/60"> <6/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="6/60"> 6/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="6/36"> 6/36</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="6/24"> 6/24</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="6/18"> 6/18</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="6/12"> 6/12</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="6/9"> 6/9</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="6/6"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="6/7.5"> 6/7.5</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="6/6"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ua_r_2" name="refraction_va_ua_r_2" value="6/5"> 6/5</label>
			</div>
		</div>
		<div class="col-md-1 btn-group">
			<label class="btn_radio_small btn-custom"><input type="checkbox" value="P" name="refraction_va_ua_r_p_2" id="refraction_va_ua_r_p_2"> P</label>
		</div>
	</div>
	<br>
	<div class="row mb-5 d-none first_row">
		<div class="col-md-1">	</div>		
		<div class="col-md-5">			
			<div class="pr_table">
				<div>PR:</div>
				<div>
					<span>S</span>
					<select name="refraction_va_ua_l_pr_s" id="refraction_va_ua_l_pr_s">
						<option value="">Sel</option>
						<option value="+" <?php if($refrtsn_vl_act['refraction_va_ua_l_pr_s']=='+'){ echo 'selected';} ?>>+</option>
						<option value="-" <?php if($refrtsn_vl_act['refraction_va_ua_l_pr_s']=='-'){ echo 'selected';} ?>>-</option>
					</select>
				</div>
				<div>
					<span>I</span>
					<select name="refraction_va_ua_l_pr_i" id="refraction_va_ua_l_pr_i">
						<option value="">Sel</option>
						<option value="+" <?php if($refrtsn_vl_act['refraction_va_ua_l_pr_i']=='+'){ echo 'selected';} ?>>+</option>
						<option value="-" <?php if($refrtsn_vl_act['refraction_va_ua_l_pr_i']=='-'){ echo 'selected';} ?>>-</option>
					</select>
				</div>
				<div>
					<span>N</span>
					<select name="refraction_va_ua_l_pr_n" id="refraction_va_ua_l_pr_n">
						<option value="">Sel</option>
						<option value="+" <?php if($refrtsn_vl_act['refraction_va_ua_l_pr_n']=='+'){ echo 'selected';} ?>>+</option>
						<option value="-" <?php if($refrtsn_vl_act['refraction_va_ua_l_pr_n']=='-'){ echo 'selected';} ?>>-</option>
					</select>
				</div>
				<div>
					<span>T</span>
					<select name="refraction_va_ua_l_pr_t" id="refraction_va_ua_l_pr_t">
						<option value="">Sel</option>
						<option value="+" <?php if($refrtsn_vl_act['refraction_va_ua_l_pr_t']=='+'){ echo 'selected';} ?>>+</option>
						<option value="-" <?php if($refrtsn_vl_act['refraction_va_ua_l_pr_t']=='-'){ echo 'selected';} ?>>-</option>
					</select>
				</div>
			</div>			
		</div>
		<div class="col-md-1"></div>
		<div class="col-md-5">
			<div class="pr_table">
				<div>PR:</div>
				<div>
					<span>S</span>
					<select name="refraction_va_ua_r_pr_s" id="refraction_va_ua_r_pr_s">
						<option value="">Sel</option>
						<option value="+" <?php if($refrtsn_vl_act['refraction_va_ua_r_pr_s']=='+'){ echo 'selected';} ?>>+</option>
						<option value="-" <?php if($refrtsn_vl_act['refraction_va_ua_r_pr_s']=='-'){ echo 'selected';} ?>>-</option>
					</select>
				</div>
				<div>
					<span>I</span>
					<select name="refraction_va_ua_r_pr_i" id="refraction_va_ua_r_pr_i">
						<option value="">Sel</option>
						<option value="+" <?php if($refrtsn_vl_act['refraction_va_ua_r_pr_i']=='+'){ echo 'selected';} ?>>+</option>
						<option value="-" <?php if($refrtsn_vl_act['refraction_va_ua_r_pr_i']=='-'){ echo 'selected';} ?>>-</option>
					</select>
				</div>
				<div>
					<span>N</span>
					<select name="refraction_va_ua_r_pr_n" id="refraction_va_ua_r_pr_n">
						<option value="">Sel</option>
						<option value="+" <?php if($refrtsn_vl_act['refraction_va_ua_r_pr_n']=='+'){ echo 'selected';} ?>>+</option>
						<option value="-" <?php if($refrtsn_vl_act['refraction_va_ua_r_pr_n']=='-'){ echo 'selected';} ?>>-</option>
					</select>
				</div>
				<div>
					<span>T</span>
					<select name="refraction_va_ua_r_pr_t" id="refraction_va_ua_r_pr_t">
						<option value="">Sel</option>
						<option value="+" <?php if($refrtsn_vl_act['refraction_va_ua_r_pr_t']=='+'){ echo 'selected';} ?>>+</option>
						<option value="-" <?php if($refrtsn_vl_act['refraction_va_ua_r_pr_t']=='-'){ echo 'selected';} ?>>-</option>
					</select>
				</div>
			</div>			
		</div>
	</div>
	<br>
	<!-- 3rd row -->
	<div class="row m-t-3 d-none first_row">
		<div class="col-md-1">
			<span>Pinhole</span>
			<br>
			<small class="mini_outline_btn d-none ph_l_txt" onclick="$('.refraction_va_ph_l').prop('checked',false); $('.ph_l_txt').hide(); $('#refraction_va_ph_l_txt').val(''); $('.refraction_va_ph_l').parent().removeClass('active');">clear</small>
			<div class="m-t-3 d-none ph_l_txt">
				<input type="text" id="refraction_va_ph_l_txt" value="<?php echo $refrtsn_vl_act['refraction_va_ph_l_txt'];?>" name="refraction_va_ph_l_txt" class="form-control">
			</div>
		</div>
		<div class="col-md-4">
			<div class="btn-group">
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="PL-"> PL-</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="PL+"> PL+</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="FL"> FL</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="HM"> HM</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="CFCF"> CFCF</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="FC"> FC</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="1/60"> 1/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="2/60"> 2/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="3/60"> 3/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="4/60"> 4/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="5/60"> 5/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="6/60"> 6/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="6/36"> 6/36</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="6/24"> 6/24</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="6/18"> 6/18</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="6/12"> 6/12</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="6/9"> 6/9</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="6/7.5"> 6/7.5</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="6/6"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="6/5"> 6/5</label>
				<!-- <label class="btn_radio_small"><input type="radio" class="refraction_va_ph_l" name="refraction_va_ph_l" value="P"> P</label> -->
			</div>
		</div>
		<div class="col-md-1 btn-group">
			<label class="btn_radio_small btn-custom"><input type="checkbox" value="P" name="refraction_va_ph_l_p" id="refraction_va_ph_l_p"> P</label>
			<label class="btn_radio_small btn-custom"><input type="checkbox" value="NI" name="refraction_va_ph_l_ni" id="refraction_va_ph_l_ni"> NI</label>
		</div>
		<div class="col-md-1">
			<span>Pinhole</span>
			<br>
			<small class="mini_outline_btn d-none ph_r_txt" onclick="$('.refraction_va_ph_r').prop('checked',false);  $('.ph_r_txt').hide(); $('#refraction_va_ph_r_txt').val(''); $('.refraction_va_ph_r').parent().removeClass('active');">clear</small>
			<div class="m-t-3 d-none ph_r_txt">
				<input type="text" id="refraction_va_ph_r_txt" value="<?php echo $refrtsn_vl_act['refraction_va_ph_r_txt'];?>" name="refraction_va_ph_r_txt" class="form-control">
			</div>
		</div>
		<div class="col-md-4">
			<div class="btn-group">
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="PL-"> PL-</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="PL+"> PL+</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="FL"> FL</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="HM"> HM</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="CFCF"> CFCF</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="FC"> FC</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="1/60"> 1/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="2/60"> 2/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="3/60"> 3/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="4/60"> 4/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="5/60"> 5/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="6/60"> 6/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="6/36"> 6/36</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="6/24"> 6/24</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="6/18"> 6/18</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="6/12"> 6/12</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="6/9"> 6/9</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="6/7.5"> 6/7.5</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="6/6"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="6/5"> 6/5</label>
				<!-- <label class="btn_radio_small"><input type="radio" class="refraction_va_ph_r" name="refraction_va_ph_r" value="P"> P</label> -->
			</div>
		</div>
		<div class="col-md-1 btn-group">
			 <label class="btn-custom"><input type="checkbox" name="refraction_va_ph_r_p" value="P" id="refraction_va_ph_r_p"> P</label> 
			 <label class="btn-custom"><input type="checkbox" name="refraction_va_ph_r_ni" value="NI" id="refraction_va_ph_r_ni"> NI</label> 
		</div>
	</div>

	<!-- 4th row -->
	<div class="row m-t-3 d-none first_row">
		<div class="col-md-1">
			<span>Glasses</span>
			<br>
			<small class="mini_outline_btn d-none gls_l_txt" onclick="$('.refraction_va_gls_l').prop('checked',false); $('.gls_l_txt').hide(); $('#refraction_va_gls_l_txt').val(''); $('.refraction_va_gls_l').parent().removeClass('active');">clear</small>
			<div class="m-t-3 d-none gls_l_txt">
				<input type="text" id="refraction_va_gls_l_txt" value="<?php echo $refrtsn_vl_act['refraction_va_gls_l_txt'];?>" name="refraction_va_gls_l_txt" class="form-control">
			</div>
		</div>
		<div class="col-md-4">
			<div class="btn-group">
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="PL-"> PL-</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="PL+"> PL+</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="FL"> FL</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="HM"> HM</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="CFCF"> CFCF</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="FC"> FC</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="1/60"> 1/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="2/60"> 2/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="3/60"> 3/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="4/60"> 4/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="5/60"> 5/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="6/60"> 6/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="6/36"> 6/36</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="6/24"> 6/24</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="6/18"> 6/18</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="6/12"> 6/12</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="6/9"> 6/9</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="6/7.5"> 6/7.5</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="6/6"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l" name="refraction_va_gls_l" value="6/5"> 6/5</label>
			</div>
		</div>
		<div class="col-md-1 btn-group">
			<label class="btn_radio_small btn-custom"><input type="checkbox" value="P" name="refraction_va_gls_l_p" id="refraction_va_gls_l_p"> P</label>
		</div>
		<div class="col-md-1">
			<span>Glasses</span>
			<br>
			<small class="mini_outline_btn d-none gls_r_txt" onclick="$('.refraction_va_gls_r').prop('checked',false); $('.gls_r_txt').hide(); $('#refraction_va_gls_r_txt').val(''); $('.refraction_va_gls_r').parent().removeClass('active');">clear</small>
			<div class="m-t-3 d-none gls_r_txt">
				<input type="text" id="refraction_va_gls_r_txt" value="<?php echo $refrtsn_vl_act['refraction_va_gls_r_txt'];?>" name="refraction_va_gls_r_txt" class="form-control">
			</div>
		</div>
		<div class="col-md-4">
			<div class="btn-group">
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="PL-"> PL-</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="PL+"> PL+</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="FL"> FL</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="HM"> HM</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="CFCF"> CFCF</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="FC"> FC</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="1/60"> 1/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="2/60"> 2/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="3/60"> 3/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="4/60"> 4/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="5/60"> 5/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="6/60"> 6/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="6/36"> 6/36</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="6/24"> 6/24</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="6/18"> 6/18</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="6/12"> 6/12</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="6/9"> 6/9</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="6/7.5"> 6/7.5</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="6/6"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r" name="refraction_va_gls_r" value="6/5"> 6/5</label>
			</div>
		</div>
		<div class="col-md-1 btn-group">
			<label class="btn_radio_small btn-custom"><input type="checkbox" value="P" name="refraction_va_gls_r_p" id="refraction_va_gls_r_p"> P</label>
		</div>
	</div>

	<!-- 5th row -->
	<div class="row m-t-3 d-none first_row">
		<div class="col-md-1">
			<br>
			<small class="mini_outline_btn d-none gls_l2_txt" onclick="$('.refraction_va_gls_l_2').prop('checked',false); $('.gls_l2_txt').hide(); $('#refraction_va_gls_l_2_txt').val(''); $('.refraction_va_gls_l_2').parent().removeClass('active');">clear</small>
			 	<div class="m-t-3 d-none gls_l2_txt">
				<input type="text" id="refraction_va_gls_l_2_txt" value="<?php echo $refrtsn_vl_act['refraction_va_gls_l_2_txt'];?>" name="refraction_va_gls_l_2_txt" class="form-control">
			</div>
		</div>
		<div class="col-md-4">
			<div class="btn-group">
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="N4"> N4</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="N5"> N5</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="N6"> N6</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="N8"> N8</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="N10"> N10</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="N12"> N12</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="N14"> N14</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="N18"> N18</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="N24"> N24</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="N26"> N26</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="N36"> N36</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="<.N36"> <.N36</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="<6/60"> <6/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="6/60"> 6/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="6/36"> 6/36</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="6/24"> 6/24</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="6/18"> 6/18</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="6/12"> 6/12</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="6/9"> 6/9</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="6/6"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="6/7.5"> 6/7.5</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="6/6"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_l_2" name="refraction_va_gls_l_2" value="6/5"> 6/5</label>
			</div>
		</div>
		<div class="col-md-1 btn-group">
			<label class="btn_radio_small btn-custom"><input type="checkbox" value="P" name="refraction_va_gls_l_p_2" id="refraction_va_gls_l_p_2"> P</label>
		</div>
		<div class="col-md-1">
			<br>
			<small class="mini_outline_btn d-none gls_r2_txt" onclick="$('.refraction_va_gls_r_2').prop('checked',false); $('.gls_r2_txt').hide(); $('#refraction_va_gls_r_2_txt').val(''); $('.refraction_va_gls_r_2').parent().removeClass('active');">clear</small> 	<div class="m-t-3 d-none gls_r2_txt">
				<input type="text" id="refraction_va_gls_r_2_txt" value="<?php echo $refrtsn_vl_act['refraction_va_gls_r_2_txt'];?>" name="refraction_va_gls_r_2_txt" class="form-control">
			</div>
		</div>
		<div class="col-md-4">
			<div class="btn-group">
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="N4"> N4</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="N5"> N5</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="N6"> N6</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="N8"> N8</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="N10"> N10</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="N12"> N12</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="N14"> N14</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="N18"> N18</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="N24"> N24</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="N26"> N26</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="N36"> N36</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="<.N36"> <.N36</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="<6/60"> <6/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="6/60"> 6/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="6/36"> 6/36</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="6/24"> 6/24</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="6/18"> 6/18</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="6/12"> 6/12</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="6/9"> 6/9</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="6/6"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="6/7.5"> 6/7.5</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="6/6"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_gls_r_2" name="refraction_va_gls_r_2" value="6/5"> 6/5</label>
			</div>
		</div>
		<div class="col-md-1 btn-group">
			<label class="btn_radio_small btn-custom"><input type="checkbox" value="P" name="refraction_va_gls_r_p_2" id="refraction_va_gls_r_p_2"> P</label>
		</div>
	</div>

	<!-- 6th row -->
	<div class="row m-t-3 d-none first_row">
		<div class="col-md-1">
			<span>Contact Lens</span>
			<br>
			<small class="mini_outline_btn d-none cl_l_txt" onclick="$('.refraction_va_cl_l').prop('checked',false); $('.cl_l_txt').hide(); $('#refraction_va_cl_l_txt').val(''); $('.refraction_va_cl_l').parent().removeClass('active');">clear</small>
			<div class="m-t-3 d-none cl_l_txt">
				<input type="text" id="refraction_va_cl_l_txt" value="<?php echo $refrtsn_vl_act['refraction_va_cl_l_txt'];?>" name="refraction_va_cl_l_txt" class="form-control">
			</div>
		</div>
		<div class="col-md-4">
			<div class="btn-group">
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="PL-"> PL-</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="PL+"> PL+</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="FL"> FL</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="HM"> HM</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="CFCF"> CFCF</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="FC"> FC</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="1/60"> 1/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="2/60"> 2/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="3/60"> 3/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="4/60"> 4/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="5/60"> 5/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="6/60"> 6/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="6/36"> 6/36</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="6/24"> 6/24</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="6/18"> 6/18</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="6/12"> 6/12</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="6/9"> 6/9</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="6/7.5"> 6/7.5</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="6/6"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_l" name="refraction_va_cl_l" value="6/5"> 6/5</label>
			</div>
		</div>
		<div class="col-md-1 btn-group">
			<label class="btn_radio_small btn-custom"><input type="checkbox" value="P" name="refraction_va_cl_l_p" id="refraction_va_cl_l_p"> P</label>
		</div>
		<div class="col-md-1">
			<span>Contact Lens</span>
			<br>
			<small class="mini_outline_btn d-none cl_r_txt" onclick="$('.refraction_va_cl_r').prop('checked',false); $('.cl_r_txt').hide(); $('#refraction_va_cl_r_txt').val('');  $('.refraction_va_cl_r').parent().removeClass('active');">clear</small>
			<div class="m-t-3 d-none cl_r_txt">
				<input type="text" id="refraction_va_cl_r_txt" value="<?php echo $refrtsn_vl_act['refraction_va_cl_r_txt'];?>" name="refraction_va_cl_r_txt" class="form-control">
			</div>
		</div>
		<div class="col-md-4">
			<div class="btn-group">
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="PL-"> PL-</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="PL+"> PL+</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="FL"> FL</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="HM"> HM</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="CFCF"> CFCF</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="FC"> FC</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="1/60"> 1/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="2/60"> 2/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="3/60"> 3/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="4/60"> 4/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="5/60"> 5/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="6/60"> 6/60</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="6/36"> 6/36</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="6/24"> 6/24</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="6/18"> 6/18</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="6/12"> 6/12</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="6/9"> 6/9</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="6/7.5"> 6/7.5</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="6/6"> 6/6</label>
				<label class="btn_radio_small"><input type="radio" class="refraction_va_cl_r" name="refraction_va_cl_r" value="6/5"> 6/5</label>
			</div>
		</div>
		<div class="col-md-1 btn-group">
			<label class="btn_radio_small btn-custom"><input type="checkbox" value="P" name="refraction_va_cl_r_p" id="refraction_va_cl_r_p"> P</label>
		</div>
	</div>

	<div class="panel-footer first_row d-none">
		<div class="row">
			<div class="col-md-2">Comment:</div>
			<div class="col-md-4">
				<textarea name="refraction_va_l_comm" id="refraction_va_l_comm" class="form-control"><?php echo $refrtsn_vl_act['refraction_va_l_comm'];?></textarea>
			</div>
			<div class="col-md-2">Comment:</div>
			<div class="col-md-4">
				<textarea name="refraction_va_r_comm" id="refraction_va_r_comm"  class="form-control"><?php echo $refrtsn_vl_act['refraction_va_r_comm'];?></textarea> 
			</div>
		</div>
	</div>
</section>


<section class="panel panel-default">
	<div class="panel-body">
		<div class="row">				
			<div class="col-md-12">
				<div class="text-right btn_edit"><a href="javascript:void(0)" class="btn_fill" onclick="$('.kero_meter').toggle();">Edit</a><hr></div>
			</div>
			<div class="col-md-2">
				<div class="label_name">KERATOMETRY (K) <i onclick="refraction_km_ltr();" class="fa fa-arrow-right" title="Copy Left to Right"></i> </div>
			</div>
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="33.33%"></th>
							<th width="33.33%"></th>
							<th width="33.33%">Axis</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:left;">Kh</td>
							<td>
								<span class="kero_meter"><?php echo $refrtsn_kerat['refraction_km_l_kh'];?></span> <input type="text" name="refraction_km_l_kh" id="refraction_km_l_kh" class="w-100px kero_meter" value="<?php echo $refrtsn_kerat['refraction_km_l_kh'];?>" style="display:none;">
							</td>
							<td><span class="kero_meter"> <?php echo $refrtsn_kerat['refraction_km_l_kh_a'];?> </span> <input type="text" name="refraction_km_l_kh_a" id="refraction_km_l_kh_a" value="<?php echo $refrtsn_kerat['refraction_km_l_kh_a'];?>" class="w-100px kero_meter" style="display:none;"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Kv</td>
							
							<td>
								<span class="kero_meter"><?php echo $refrtsn_kerat['refraction_km_l_kv'];?></span> <input type="text" name="refraction_km_l_kv" id="refraction_km_l_kv" class="w-100px kero_meter" value="<?php echo $refrtsn_kerat['refraction_km_l_kv'];?>" style="display:none;">
							</td>
							<td><span class="kero_meter"> <?php echo $refrtsn_kerat['refraction_km_l_kv_a'];?> </span> <input type="text" name="refraction_km_l_kv_a" id="refraction_km_l_kv_a" value="<?php echo $refrtsn_kerat['refraction_km_l_kv_a'];?>" class="w-100px kero_meter" style="display:none;"></td>
						</tr>
					</tbody>
				</table>
			</div>			
			<div class="col-md-2">
				<div class="label_name">KERATOMETRY (K) <i onclick="refraction_km_rtl();" class="fa fa-arrow-left" title="Copy Right to Left"></i> </div>
			</div>
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="33.33%"></th>
							<th width="33.33%"></th>
							<th width="33.33%">Axis</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:left;">Kh</td>
							<td>
								<span class="kero_meter"><?php echo $refrtsn_kerat['refraction_km_r_kh'];?></span> <input type="text" name="refraction_km_r_kh" id="refraction_km_r_kh" class="w-100px kero_meter" value="<?php echo $refrtsn_kerat['refraction_km_r_kh'];?>" style="display:none;">
							</td>
							<td><span class="kero_meter"> <?php echo $refrtsn_kerat['refraction_km_r_kh_a'];?> </span> <input type="text" name="refraction_km_r_kh_a" id="refraction_km_r_kh_a" class="w-100px kero_meter" value="<?php echo $refrtsn_kerat['refraction_km_r_kh_a'];?>" style="display:none;"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Kv</td>
							<td>
								<span class="kero_meter"><?php echo $refrtsn_kerat['refraction_km_r_kv'];?></span> <input type="text" name="refraction_km_r_kv" id="refraction_km_r_kv" class="w-100px kero_meter" value="<?php echo $refrtsn_kerat['refraction_km_r_kv'];?>" style="display:none;">
							</td>
							<td><span class="kero_meter"> <?php echo $refrtsn_kerat['refraction_km_r_kv_a'];?> </span> <input type="text" name="refraction_km_r_kv_a" id="refraction_km_r_kv_a" class="w-100px kero_meter" value="<?php echo $refrtsn_kerat['refraction_km_r_kv_a'];?>" style="display:none;"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>			
</section>

<section class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="text-right btn_edit"><a href="javascript:void(0)" class="btn_fill" onclick="$('.meter_pgp').toggle();">Edit</a><hr></div>
			</div>
			<div class="col-md-2">
				<div class="label_name">PGP <i onclick="refraction_pgp_ltr();" class="fa fa-arrow-right" title="Copy Left to Right"></i> </div>
				<button type="button" class="btn_fill meter_pgp d-none" title="Fill PGP" onclick="return open_modals('refraction_pgp','PGP');">Fill <i class="fa fa-arrow-right"></i> </button> 	
				<button type="button" title="Copy PGP to Glass Prescription" onclick="cpoy_to_glass_pres('refraction_pgp');"  class="btn_fill"> Copy <i class="fa fa-arrow-down"></i></button>	
			</div>					
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="20%"></th>
							<th width="20%">Sph</th>
							<th width="20%">Cyl</th>
							<th width="20%">Axis</th>
							<th width="20%">Vision</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:left;">Distant</td> 
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_l_dt_sph'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_l_dt_sph'];?>" name="refraction_pgp_l_dt_sph" id="refraction_pgp_l_dt_sph" class="w-50px meter_pgp d-none"></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_l_dt_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_l_dt_cyl'];?>" name="refraction_pgp_l_dt_cyl" id="refraction_pgp_l_dt_cyl" class="w-50px meter_pgp d-none"></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_l_dt_axis'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_l_dt_axis'];?>" name="refraction_pgp_l_dt_axis" id="refraction_pgp_l_dt_axis" class="w-50px meter_pgp d-none"></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_l_dt_vision'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_l_dt_vision'];?>" name="refraction_pgp_l_dt_vision" id="refraction_pgp_l_dt_vision" class="w-50px meter_pgp d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Add <span class="text-danger">#</span></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_l_ad_sph'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_l_ad_sph'];?>" name="refraction_pgp_l_ad_sph" id="refraction_pgp_l_ad_sph" class="w-50px meter_pgp d-none"></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_l_ad_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_l_ad_cyl'];?>" name="refraction_pgp_l_ad_cyl" id="refraction_pgp_l_ad_cyl" class="w-50px meter_pgp d-none" disabled></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_l_ad_axis'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_l_ad_axis'];?>" name="refraction_pgp_l_ad_axis" id="refraction_pgp_l_ad_axis" class="w-50px meter_pgp d-none" disabled></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_l_ad_vision'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_l_ad_vision'];?>" name="refraction_pgp_l_ad_vision" id="refraction_pgp_l_ad_vision" class="w-50px meter_pgp d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Near</td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_l_nr_sph'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_l_nr_sph'];?>" name="refraction_pgp_l_nr_sph" id="refraction_pgp_l_nr_sph" class="w-50px meter_pgp d-none"></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_l_nr_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_l_nr_cyl'];?>" name="refraction_pgp_l_nr_cyl" id="refraction_pgp_l_nr_cyl" class="w-50px meter_pgp d-none"></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_l_nr_axis'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_l_nr_axis'];?>" name="refraction_pgp_l_nr_axis" id="refraction_pgp_l_nr_axis" class="w-50px meter_pgp d-none"></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_l_nr_vision'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_l_nr_vision'];?>" name="refraction_pgp_l_nr_vision" id="refraction_pgp_l_nr_vision" class="w-50px meter_pgp d-none"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-2">
				<div class="label_name">PGP <i onclick="refraction_pgp_rtl();" class="fa fa-arrow-left" title="Copy Right to Left"></i> </div>							
			</div>					
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="20%"></th>
							<th width="20%">Sph</th>
							<th width="20%">Cyl</th>
							<th width="20%">Axis</th>
							<th width="20%">Vision</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:left;">Distant</td> 
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_r_dt_sph'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_r_dt_sph'];?>" name="refraction_pgp_r_dt_sph" id="refraction_pgp_r_dt_sph" class="w-50px meter_pgp d-none"></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_r_dt_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_r_dt_cyl'];?>" name="refraction_pgp_r_dt_cyl" id="refraction_pgp_r_dt_cyl" class="w-50px meter_pgp d-none"></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_r_dt_axis'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_r_dt_axis'];?>" name="refraction_pgp_r_dt_axis" id="refraction_pgp_r_dt_axis" class="w-50px meter_pgp d-none"></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_r_dt_vision'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_r_dt_vision'];?>" name="refraction_pgp_r_dt_vision" id="refraction_pgp_r_dt_vision" class="w-50px meter_pgp d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Add <span class="text-danger">#</span></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_r_ad_sph'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_r_ad_sph'];?>" name="refraction_pgp_r_ad_sph" id="refraction_pgp_r_ad_sph" class="w-50px meter_pgp d-none"></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_r_ad_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_r_ad_cyl'];?>" name="refraction_pgp_r_ad_cyl" id="refraction_pgp_r_ad_cyl" class="w-50px meter_pgp d-none" disabled></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_r_ad_axis'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_r_ad_axis'];?>" name="refraction_pgp_r_ad_axis" id="refraction_pgp_r_ad_axis" class="w-50px meter_pgp d-none" disabled></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_r_ad_vision'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_r_ad_vision'];?>" name="refraction_pgp_r_ad_vision" id="refraction_pgp_r_ad_vision" class="w-50px meter_pgp d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Near</td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_r_nr_sph'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_r_nr_sph'];?>" name="refraction_pgp_r_nr_sph" id="refraction_pgp_r_nr_sph" class="w-50px meter_pgp d-none"></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_r_nr_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_r_nr_cyl'];?>" name="refraction_pgp_r_nr_cyl" id="refraction_pgp_r_nr_cyl" class="w-50px meter_pgp d-none"></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_r_nr_axis'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_r_nr_axis'];?>" name="refraction_pgp_r_nr_axis" id="refraction_pgp_r_nr_axis" class="w-50px meter_pgp d-none"></td>
							<td><span class="meter_pgp"><?php echo $refrtsn_pgp['refraction_pgp_r_nr_vision'];?></span> <input type="text" value="<?php echo $refrtsn_pgp['refraction_pgp_r_nr_vision'];?>" name="refraction_pgp_r_nr_vision" id="refraction_pgp_r_nr_vision" class="w-50px meter_pgp d-none"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="panel-footer meter_pgp d-none">
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-1 pr-0">Type of Lens :</div>
			<div class="col-md-3">
				<select name="refraction_pgp_l_lens" id="refraction_pgp_l_lens" class="custom-select">
					<option value="">Select</option>
					<option value="Single Vision - Distant" <?php if($refrtsn_pgp['refraction_pgp_l_lens']=='Single Vision - Distant'){ echo 'selected';} ?>>Single Vision - Distant</option>
					<option value="Single Vision - Near" <?php if($refrtsn_pgp['refraction_pgp_l_lens']=='Single Vision - Near'){ echo 'selected';} ?>>Single Vision - Near</option>
					<option value="Bifocal" <?php if($refrtsn_pgp['refraction_pgp_l_lens']=='Bifocal'){ echo 'selected';} ?>>Bifocal</option>
					<option value="Progressive" <?php if($refrtsn_pgp['refraction_pgp_l_lens']=='Progressive'){ echo 'selected';} ?>>Progressive</option>
					<option value="D Bifocal" <?php if($refrtsn_pgp['refraction_pgp_l_lens']=='D Bifocal'){ echo 'selected';} ?>>D Bifocal</option>
					<option value="KT Bifocal" <?php if($refrtsn_pgp['refraction_pgp_l_lens']=='KT Bifocal'){ echo 'selected';} ?>>KT Bifocal</option>
				</select>
			</div>			
			<div class="col-md-2"></div>
			<div class="col-md-1 pr-0">Type of Lens :</div>
			<div class="col-md-3">
				<select name="refraction_pgp_r_lens" id="refraction_pgp_r_lens" class="custom-select">
					<option value="">Select</option>
					<option value="Single Vision - Distant" <?php if($refrtsn_pgp['refraction_pgp_r_lens']=='Single Vision - Distant'){ echo 'selected';} ?>>Single Vision - Distant</option>
					<option value="Single Vision - Near" <?php if($refrtsn_pgp['refraction_pgp_r_lens']=='Single Vision - Near'){ echo 'selected';} ?>>Single Vision - Near</option>
					<option value="Bifocal" <?php if($refrtsn_pgp['refraction_pgp_r_lens']=='Bifocal'){ echo 'selected';} ?>>Bifocal</option>
					<option value="Progressive" <?php if($refrtsn_pgp['refraction_pgp_r_lens']=='Progressive'){ echo 'selected';} ?>>Progressive</option>
					<option value="D Bifocal" <?php if($refrtsn_pgp['refraction_pgp_r_lens']=='D Bifocal'){ echo 'selected';} ?>>D Bifocal</option>
					<option value="KT Bifocal" <?php if($refrtsn_pgp['refraction_pgp_r_lens']=='KT Bifocal'){ echo 'selected';} ?>>KT Bifocal</option>
				</select>
			</div>	
		</div>
	</div>
</section>

<section class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12 text-right btn_edit"><a href="javascript:void(0)" class="btn_fill" onclick="$('.auto_ref').toggle();">Edit</a><hr></div>
			<div class="col-md-2">
				<div class="label_name">AUTO REFRACTION (ARx) <i onclick="refraction_ar_ltr();" title="Copy Left to Right" class="fa fa-arrow-right"></i></div>
				<button type="button" class="btn_fill auto_ref d-none" title="Fill AUTO REFRACTION" onclick="return open_modals_2('refraction_ar');">Fill <i class="fa fa-arrow-right"></i> </button> 	
				
			</div>
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="25%"></th>
							<th width="25%">Sph</th>
							<th width="25%">Cyl</th>
							<th width="25%">Axis</th>
						</tr>
					</thead>
					<tbody> 
						<tr> 
							<td style="text-align:left;">Dry</td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_l_dry_sph'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_l_dry_sph'];?>" name="refraction_ar_l_dry_sph" id="refraction_ar_l_dry_sph" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_l_dry_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_l_dry_cyl'];?>" name="refraction_ar_l_dry_cyl" id="refraction_ar_l_dry_cyl" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_l_dry_axis'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_l_dry_axis'];?>" name="refraction_ar_l_dry_axis" id="refraction_ar_l_dry_axis" class="w-50px auto_ref d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Dilated</td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_l_dd_sph'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_l_dd_sph'];?>" name="refraction_ar_l_dd_sph" id="refraction_ar_l_dd_sph" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_l_dd_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_l_dd_cyl'];?>" name="refraction_ar_l_dd_cyl" id="refraction_ar_l_dd_cyl" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_l_dd_axis'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_l_dd_axis'];?>" name="refraction_ar_l_dd_axis" id="refraction_ar_l_dd_axis" class="w-50px auto_ref d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_l_b1_sph'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_l_b1_sph'];?>" name="refraction_ar_l_b1_sph" id="refraction_ar_l_b1_sph" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_l_b1_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_l_b1_cyl'];?>" name="refraction_ar_l_b1_cyl" id="refraction_ar_l_b1_cyl" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_l_b1_axis'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_l_b1_axis'];?>" name="refraction_ar_l_b1_axis" id="refraction_ar_l_b1_axis" class="w-50px auto_ref d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_l_b2_sph'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_l_b2_sph'];?>" name="refraction_ar_l_b2_sph" id="refraction_ar_l_b2_sph" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_l_b2_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_l_b2_cyl'];?>" name="refraction_ar_l_b2_cyl" id="refraction_ar_l_b2_cyl" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_l_b2_axis'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_l_b2_axis'];?>" name="refraction_ar_l_b2_axis" id="refraction_ar_l_b2_axis" class="w-50px auto_ref d-none"></td>
						</tr>
					</tbody>
				</table> 
			</div>
			<div class="col-md-2">
				<div class="label_name">AUTO REFRACTION (ARx) <i onclick="refraction_ar_rtl();" title="Copy Right to Left" class="fa fa-arrow-left"></i> </div>
			</div>
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="25%"></th>
							<th width="25%">Sph</th>
							<th width="25%">Cyl</th>
							<th width="25%">Axis</th>
						</tr>
					</thead>
					<tbody>
						<tr> 
							<td style="text-align:left;">Dry</td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_r_dry_sph'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_r_dry_sph'];?>" name="refraction_ar_r_dry_sph" id="refraction_ar_r_dry_sph" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_r_dry_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_r_dry_cyl'];?>" name="refraction_ar_r_dry_cyl" id="refraction_ar_r_dry_cyl" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_r_dry_axis'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_r_dry_axis'];?>" name="refraction_ar_r_dry_axis" id="refraction_ar_r_dry_axis" class="w-50px auto_ref d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Dilated</td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_r_dd_sph'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_r_dd_sph'];?>" name="refraction_ar_r_dd_sph" id="refraction_ar_r_dd_sph" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_r_dd_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_r_dd_cyl'];?>" name="refraction_ar_r_dd_cyl" id="refraction_ar_r_dd_cyl" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_r_dd_axis'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_r_dd_axis'];?>" name="refraction_ar_r_dd_axis" id="refraction_ar_r_dd_axis" class="w-50px auto_ref d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_r_b1_sph'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_r_b1_sph'];?>" name="refraction_ar_r_b1_sph" id="refraction_ar_r_b1_sph" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_r_b1_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_r_b1_cyl'];?>" name="refraction_ar_r_b1_cyl" id="refraction_ar_r_b1_cyl" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_r_b1_axis'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_r_b1_axis'];?>" name="refraction_ar_r_b1_axis" id="refraction_ar_r_b1_axis" class="w-50px auto_ref d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_r_b2_sph'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_r_b2_sph'];?>" name="refraction_ar_r_b2_sph" id="refraction_ar_r_b2_sph" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_r_b2_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_r_b2_cyl'];?>" name="refraction_ar_r_b2_cyl" id="refraction_ar_r_b2_cyl" class="w-50px auto_ref d-none"></td>
							<td><span class="auto_ref"><?php echo $refrtsn_auto_ref['refraction_ar_r_b2_axis'];?></span> <input type="text" value="<?php echo $refrtsn_auto_ref['refraction_ar_r_b2_axis'];?>" name="refraction_ar_r_b2_axis" id="refraction_ar_r_b2_axis" class="w-50px auto_ref d-none"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>

<section class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="text-right btn_edit"><a href="javascript:void(0)" class="btn_fill" onclick="$('.dry_ref').toggle();">Edit</a><hr></div>
			</div>
			<div class="col-md-2">
				<div class="label_name">DRY REFRACTION <i onclick="refraction_dry_ref_ltr();" title="Copy Left to Right" class="fa fa-arrow-right"></i> </div>
				<button type="button" class="btn_fill dry_ref d-none" title="Fill DRY REFRACTION" onclick="return open_modals('refraction_dry_ref','DRY-REFRACTION');">Fill <i class="fa fa-arrow-right"></i> </button> 	
				<button type="button" onclick="cpoy_to_glass_pres('refraction_dry_ref');" title="Copy Dry Refraction to Glass Prescription" class="btn_fill"> Copy <i class="fa fa-arrow-down"></i></button>	
			</div>					
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="20%"></th>
							<th width="20%">Sph</th>
							<th width="20%">Cyl</th>
							<th width="20%">Axis</th>
							<th width="20%">Vision</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:left;">Distant</td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_l_dt_sph'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_l_dt_sph'];?>" name="refraction_dry_ref_l_dt_sph" id="refraction_dry_ref_l_dt_sph" class="w-50px dry_ref d-none"></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_l_dt_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_l_dt_cyl'];?>" name="refraction_dry_ref_l_dt_cyl" id="refraction_dry_ref_l_dt_cyl" class="w-50px dry_ref d-none"></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_l_dt_axis'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_l_dt_axis'];?>" name="refraction_dry_ref_l_dt_axis" id="refraction_dry_ref_l_dt_axis" class="w-50px dry_ref d-none"></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_l_dt_vision'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_l_dt_vision'];?>" name="refraction_dry_ref_l_dt_vision" id="refraction_dry_ref_l_dt_vision" class="w-50px dry_ref d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Add <span class="text-danger">#</span></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_l_ad_sph'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_l_ad_sph'];?>" name="refraction_dry_ref_l_ad_sph" id="refraction_dry_ref_l_ad_sph" class="w-50px dry_ref d-none"></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_l_ad_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_l_ad_cyl'];?>" name="refraction_dry_ref_l_ad_cyl" id="refraction_dry_ref_l_ad_cyl" class="w-50px dry_ref d-none" disabled></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_l_ad_axis'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_l_ad_axis'];?>" name="refraction_dry_ref_l_ad_axis" id="refraction_dry_ref_l_ad_axis" class="w-50px dry_ref d-none" disabled></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_l_ad_vision'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_l_ad_vision'];?>" name="refraction_dry_ref_l_ad_vision" id="refraction_dry_ref_l_ad_vision" class="w-50px dry_ref d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Near</td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_l_nr_sph'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_l_nr_sph'];?>" name="refraction_dry_ref_l_nr_sph" id="refraction_dry_ref_l_nr_sph" class="w-50px dry_ref d-none"></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_l_nr_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_l_nr_cyl'];?>" name="refraction_dry_ref_l_nr_cyl" id="refraction_dry_ref_l_nr_cyl" class="w-50px dry_ref d-none"></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_l_nr_axis'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_l_nr_axis'];?>" name="refraction_dry_ref_l_nr_axis" id="refraction_dry_ref_l_nr_axis" class="w-50px dry_ref d-none"></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_l_nr_vision'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_l_nr_vision'];?>" name="refraction_dry_ref_l_nr_vision" id="refraction_dry_ref_l_nr_vision" class="w-50px dry_ref d-none"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-2">
				<div class="label_name">DRY REFRACTION <i onclick="refraction_dry_ref_rtl();" title="Copy Right to Left" class="fa fa-arrow-left"></i></div>
			</div>					
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="20%"></th>
							<th width="20%">Sph</th>
							<th width="20%">Cyl</th>
							<th width="20%">Axis</th>
							<th width="20%">Vision</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:left;">Distant</td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_r_dt_sph'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_r_dt_sph'];?>" name="refraction_dry_ref_r_dt_sph" id="refraction_dry_ref_r_dt_sph" class="w-50px dry_ref d-none"></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_r_dt_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_r_dt_cyl'];?>" name="refraction_dry_ref_r_dt_cyl" id="refraction_dry_ref_r_dt_cyl" class="w-50px dry_ref d-none"></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_r_dt_axis'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_r_dt_axis'];?>" name="refraction_dry_ref_r_dt_axis" id="refraction_dry_ref_r_dt_axis" class="w-50px dry_ref d-none"></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_r_dt_vision'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_r_dt_vision'];?>" name="refraction_dry_ref_r_dt_vision" id="refraction_dry_ref_r_dt_vision" class="w-50px dry_ref d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Add <span class="text-danger">#</span></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_r_ad_sph'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_r_ad_sph'];?>" name="refraction_dry_ref_r_ad_sph" id="refraction_dry_ref_r_ad_sph" class="w-50px dry_ref d-none"></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_r_ad_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_r_ad_cyl'];?>" name="refraction_dry_ref_r_ad_cyl" id="refraction_dry_ref_r_ad_cyl" class="w-50px dry_ref d-none" disabled></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_r_ad_axis'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_r_ad_axis'];?>" name="refraction_dry_ref_r_ad_axis" id="refraction_dry_ref_r_ad_axis" class="w-50px dry_ref d-none" disabled></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_r_ad_vision'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_r_ad_vision'];?>" name="refraction_dry_ref_r_ad_vision" id="refraction_dry_ref_r_ad_vision" class="w-50px dry_ref d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Near</td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_r_nr_sph'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_r_nr_sph'];?>" name="refraction_dry_ref_r_nr_sph" id="refraction_dry_ref_r_nr_sph" class="w-50px dry_ref d-none"></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_r_nr_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_r_nr_cyl'];?>" name="refraction_dry_ref_r_nr_cyl" id="refraction_dry_ref_r_nr_cyl" class="w-50px dry_ref d-none"></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_r_nr_axis'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_r_nr_axis'];?>" name="refraction_dry_ref_r_nr_axis" id="refraction_dry_ref_r_nr_axis" class="w-50px dry_ref d-none"></td>
							<td><span class="dry_ref"><?php echo $refrtsn_dry_ref['refraction_dry_ref_r_nr_vision'];?></span> <input type="text" value="<?php echo $refrtsn_dry_ref['refraction_dry_ref_r_nr_vision'];?>" name="refraction_dry_ref_r_nr_vision" id="refraction_dry_ref_r_nr_vision" class="w-50px dry_ref d-none"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="panel-footer dry_ref d-none">
		<div class="row">
			<div class="col-md-2">Comment:</div>
			<div class="col-md-4">
				<textarea name="refraction_dry_ref_l_comm" id="refraction_dry_ref_l_comm" class="form-control"><?php echo $refrtsn_dry_ref['refraction_dry_ref_l_comm'];?></textarea>
			</div>
			<div class="col-md-2">Comment:</div>
			<div class="col-md-4">
				<textarea name="refraction_dry_ref_r_comm" id="refraction_dry_ref_r_comm"  class="form-control"><?php echo $refrtsn_dry_ref['refraction_dry_ref_l_comm'];?></textarea>
			</div>
		</div>
	</div>
</section>

<section class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12 text-right btn_edit"><a href="javascript:void(0)" class="btn_fill" onclick="$('.ref_dil').toggle();">Edit</a><hr></div>
			<div class="col-md-2">
				<div class="label_name">REFRACTION (DILATED) <i onclick="refraction_ref_dtd_ltr();" title="Copy Left to Right" class="fa fa-arrow-right"></i> </div>
				<button type="button" class="btn_fill ref_dil d-none" title="Fill REFRACTION (DILATED)" onclick="return open_modals('refraction_ref_dtd','REFRACTION-DILATED');">Fill <i class="fa fa-arrow-right"></i> </button> 	
				<button type="button" title="Copy Dilated Refraction to Glass Prescription" onclick="cpoy_to_glass_pres('refraction_ref_dtd');" class="btn_fill"> Copy <i class="fa fa-arrow-down"></i></button>	
			</div>			
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="20%"></th>
							<th width="20%">Sph</th>
							<th width="20%">Cyl</th>
							<th width="20%">Axis</th>
							<th width="20%">Vision</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:left;">Distant</td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_l_dt_sph'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_l_dt_sph'];?>" name="refraction_ref_dtd_l_dt_sph" id="refraction_ref_dtd_l_dt_sph" class="w-50px ref_dil d-none"></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_l_dt_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_l_dt_cyl'];?>" name="refraction_ref_dtd_l_dt_cyl" id="refraction_ref_dtd_l_dt_cyl" class="w-50px ref_dil d-none"></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_l_dt_axis'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_l_dt_axis'];?>" name="refraction_ref_dtd_l_dt_axis" id="refraction_ref_dtd_l_dt_axis" class="w-50px ref_dil d-none"></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_l_dt_vision'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_l_dt_vision'];?>" name="refraction_ref_dtd_l_dt_vision" id="refraction_ref_dtd_l_dt_vision" class="w-50px ref_dil d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Add <span class="text-danger">#</span></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_l_ad_sph'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_l_ad_sph'];?>" name="refraction_ref_dtd_l_ad_sph" id="refraction_ref_dtd_l_ad_sph" class="w-50px ref_dil d-none"></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_l_ad_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_l_ad_cyl'];?>" name="refraction_ref_dtd_l_ad_cyl" id="refraction_ref_dtd_l_ad_cyl" class="w-50px ref_dil d-none" disabled ></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_l_ad_axis'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_l_ad_axis'];?>" name="refraction_ref_dtd_l_ad_axis" id="refraction_ref_dtd_l_ad_axis" class="w-50px ref_dil d-none" disabled></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_l_ad_vision'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_l_ad_vision'];?>" name="refraction_ref_dtd_l_ad_vision" id="refraction_ref_dtd_l_ad_vision" class="w-50px ref_dil d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Near</td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_l_nr_sph'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_l_nr_sph'];?>" name="refraction_ref_dtd_l_nr_sph" id="refraction_ref_dtd_l_nr_sph" class="w-50px ref_dil d-none"></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_l_nr_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_l_nr_cyl'];?>" name="refraction_ref_dtd_l_nr_cyl" id="refraction_ref_dtd_l_nr_cyl" class="w-50px ref_dil d-none"></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_l_nr_axis'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_l_nr_axis'];?>" name="refraction_ref_dtd_l_nr_axis" id="refraction_ref_dtd_l_nr_axis" class="w-50px ref_dil d-none"></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_l_nr_vision'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_l_nr_vision'];?>" name="refraction_ref_dtd_l_nr_vision" id="refraction_ref_dtd_l_nr_vision" class="w-50px ref_dil d-none"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-2">
				<div class="label_name">REFRACTION (DILATED) <i onclick="refraction_ref_dtd_rtl();" title="Copy Right to Left" class="fa fa-arrow-left"></i> </div>							
			</div>			
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="20%"></th>
							<th width="20%">Sph</th>
							<th width="20%">Cyl</th>
							<th width="20%">Axis</th>
							<th width="20%">Vision</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:left;">Distant</td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_r_dt_sph'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_r_dt_sph'];?>" name="refraction_ref_dtd_r_dt_sph" id="refraction_ref_dtd_r_dt_sph" class="w-50px ref_dil d-none"></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_r_dt_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_r_dt_cyl'];?>" name="refraction_ref_dtd_r_dt_cyl" id="refraction_ref_dtd_r_dt_cyl" class="w-50px ref_dil d-none"></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_r_dt_axis'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_r_dt_axis'];?>" name="refraction_ref_dtd_r_dt_axis" id="refraction_ref_dtd_r_dt_axis" class="w-50px ref_dil d-none"></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_r_dt_vision'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_r_dt_vision'];?>" name="refraction_ref_dtd_r_dt_vision" id="refraction_ref_dtd_r_dt_vision" class="w-50px ref_dil d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Add <span class="text-danger">#</span></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_r_ad_sph'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_r_ad_sph'];?>" name="refraction_ref_dtd_r_ad_sph" id="refraction_ref_dtd_r_ad_sph" class="w-50px ref_dil d-none"></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_r_ad_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_r_ad_cyl'];?>" name="refraction_ref_dtd_r_ad_cyl" id="refraction_ref_dtd_r_ad_cyl" class="w-50px ref_dil d-none" disabled ></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_r_ad_axis'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_r_ad_axis'];?>" name="refraction_ref_dtd_r_ad_axis" id="refraction_ref_dtd_r_ad_axis" class="w-50px ref_dil d-none" disabled></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_r_ad_vision'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_r_ad_vision'];?>" name="refraction_ref_dtd_r_ad_vision" id="refraction_ref_dtd_r_ad_vision" class="w-50px ref_dil d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Near</td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_r_nr_sph'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_r_nr_sph'];?>" name="refraction_ref_dtd_r_nr_sph" id="refraction_ref_dtd_r_nr_sph" class="w-50px ref_dil d-none"></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_r_nr_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_r_nr_cyl'];?>" name="refraction_ref_dtd_r_nr_cyl" id="refraction_ref_dtd_r_nr_cyl" class="w-50px ref_dil d-none"></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_r_nr_axis'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_r_nr_axis'];?>" name="refraction_ref_dtd_r_nr_axis" id="refraction_ref_dtd_r_nr_axis" class="w-50px ref_dil d-none"></td>
							<td><span class="ref_dil"><?php echo $refrtsn_dltd['refraction_ref_dtd_r_nr_vision'];?></span> <input type="text" value="<?php echo $refrtsn_dltd['refraction_ref_dtd_r_nr_vision'];?>" name="refraction_ref_dtd_r_nr_vision" id="refraction_ref_dtd_r_nr_vision" class="w-50px ref_dil d-none"></td>
						</tr>
					</tbody>
				</table>							
			</div>
		</div>					
	</div>
	<div class="panel-footer ref_dil d-none">
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-4">Drug Used</div>
					<div class="col-md-8">
						<select name="refraction_ref_dtd_l_du" id="refraction_ref_dtd_l_du" class="custom-selct">
							<option value="">Select</option>
							<option value="Tropicamide" <?php if($refrtsn_dltd['refraction_ref_dtd_l_du']=='Tropicamide'){ echo 'selected';} ?>>Tropicamide</option>
							<option value="Tropicamide - P" <?php if($refrtsn_dltd['refraction_ref_dtd_l_du']=='Tropicamide - P'){ echo 'selected';} ?>>Tropicamide - P</option>
							<option value="Atropine" <?php if($refrtsn_dltd['refraction_ref_dtd_l_du']=='Atropine'){ echo 'selected';} ?>>Atropine</option>
							<option value="Cyclopentolate" <?php if($refrtsn_dltd['refraction_ref_dtd_l_du']=='Cyclopentolate'){ echo 'selected';} ?>>Cyclopentolate</option>
							<option value="Homide" <?php if($refrtsn_dltd['refraction_ref_dtd_l_du']=='Homide'){ echo 'selected';} ?>>Homide</option>
							<option value="Dry" <?php if($refrtsn_dltd['refraction_ref_dtd_l_du']=='Dry'){ echo 'selected';} ?>>Dry</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-2"></div>
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-4">Drug Used</div>
					<div class="col-md-8">
						<select name="refraction_ref_dtd_r_du" id="refraction_ref_dtd_r_du" class="custom-selct">
							<option value="">Select</option>
							<option value="Tropicamide" <?php if($refrtsn_dltd['refraction_ref_dtd_r_du']=='Tropicamide'){ echo 'selected';} ?>>Tropicamide</option>
							<option value="Tropicamide - P" <?php if($refrtsn_dltd['refraction_ref_dtd_r_du']=='Tropicamide - P'){ echo 'selected';} ?>>Tropicamide - P</option>
							<option value="Atropine" <?php if($refrtsn_dltd['refraction_ref_dtd_r_du']=='Atropine'){ echo 'selected';} ?>>Atropine</option>
							<option value="Cyclopentolate" <?php if($refrtsn_dltd['refraction_ref_dtd_r_du']=='Cyclopentolate'){ echo 'selected';} ?>>Cyclopentolate</option>
							<option value="Homide" <?php if($refrtsn_dltd['refraction_ref_dtd_r_du']=='Homide'){ echo 'selected';} ?>>Homide</option>
							<option value="Dry" <?php if($refrtsn_dltd['refraction_ref_dtd_r_du']=='Dry'){ echo 'selected';} ?>>Dry</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-footer ref_dil d-none">
		<div class="row">
			<div class="col-md-2">Comment:</div>
			<div class="col-md-4">
				<textarea name="refraction_ref_dtd_l_comm" id="refraction_ref_dtd_l_comm" class="form-control"><?php echo $refrtsn_dltd['refraction_ref_dtd_l_comm'];?></textarea>
			</div>
			<div class="col-md-2">Comment:</div>
			<div class="col-md-4">
				<textarea name="refraction_ref_dtd_r_comm" id="refraction_ref_dtd_r_comm" class="form-control"><?php echo $refrtsn_dltd['refraction_ref_dtd_r_comm'];?></textarea>
			</div>
		</div>
	</div>
</section>


<section class="panel panel-default">
	<div class="panel-body retin_pad">			
		<div class="row">
			<div class="col-md-12 text-right btn_edit"><a href="javascript:void(0)" class="btn_fill" onclick="$('.retin').toggle();$('.retin_pad').css({'padding-bottom':'0px', 'margin-bottom':'0px', 'border':'0'})">Edit</a><hr class="retin_pad"></div>
			<div class="col-md-2 retin">
				<div class="label_name">RETINOSCOPY (R) <i class="fa fa-arrow-right" title="Copy Left to Right"></i></div>
			</div>
			<div class="col-md-4 retin">
				<div class="small">VA1 : <?php echo $refrtsn_retip['refraction_rtnp_l_t'];?> | VA2 : <?php echo $refrtsn_retip['refraction_rtnp_l_b'];?> | HA1 : <?php echo $refrtsn_retip['refraction_rtnp_l_l'];?> | HA2 : <?php echo $refrtsn_retip['refraction_rtnp_l_r'];?> | VA : <?php echo $refrtsn_retip['refraction_rtnp_l_va'];?> HA : <?php echo $refrtsn_retip['refraction_rtnp_l_ha'];?></div>
				<div class="">At Distance : <?php echo $refrtsn_retip['refraction_rtnp_l_at_dis'];?> | Drug Used : <?php echo $refrtsn_retip['refraction_rtnp_l_du'];?></div>
			</div>
			<div class="col-md-2 retin">
				<div class="label_name">RETINOSCOPY (R) <i class="fa fa-arrow-left" title="Copy Right to Left"></i></div>
			</div>		
			<div class="col-md-4 retin">
				<div class="small">VA1 : <?php echo $refrtsn_retip['refraction_rtnp_r_t'];?> | VA2 : <?php echo $refrtsn_retip['refraction_rtnp_r_b'];?> | HA1 : <?php echo $refrtsn_retip['refraction_rtnp_r_l'];?> | HA2 : <?php echo $refrtsn_retip['refraction_rtnp_r_r'];?> | VA : <?php echo $refrtsn_retip['refraction_rtnp_r_va'];?> HA : <?php echo $refrtsn_retip['refraction_rtnp_r_ha'];?></div>
				<div class="">At Distance : <?php echo $refrtsn_retip['refraction_rtnp_r_at_dis'];?> | Drug Used : <?php echo $refrtsn_retip['refraction_rtnp_r_du'];?></div>
			</div>
		</div>	
	</div>
	<div class="panel-footer retin d-none">
		<div class="row">
			<div class="col-md-2">
				<div class="label_name">RETINOSCOPY (R) <i onclick="refraction_rtnp_ltr();" title="Copy Left to Right" class="fa fa-arrow-right"></i></div>				
			</div>
			<div class="col-md-4">
				<div class="well">
					<table class="">
						<tr>
							<td width="33.33%"></td>
							<td width="33.33%"><input type="text" value="<?php echo $refrtsn_retip['refraction_rtnp_l_t'];?>" name="refraction_rtnp_l_t" id="refraction_rtnp_l_t" class="form-control"></td>
							<td width="33.33%"></td>
						</tr>
						<tr>
							<td><input type="text" value="<?php echo $refrtsn_retip['refraction_rtnp_l_l'];?>" name="refraction_rtnp_l_l" id="refraction_rtnp_l_l" class="form-control"></td>
							<td class="plus_symbol"><span class="hr"></span><span class="vr"></span></td>
							<td><input type="text" value="<?php echo $refrtsn_retip['refraction_rtnp_l_r'];?>" name="refraction_rtnp_l_r" id="refraction_rtnp_l_r" class="form-control"></td>
						</tr>
						<tr>
							<td width="33.33%"></td>
							<td width="33.33%"><input value="<?php echo $refrtsn_retip['refraction_rtnp_l_b'];?>" type="text" name="refraction_rtnp_l_b" id="refraction_rtnp_l_b" class="form-control"></td>
							<td width="33.33%"></td>
						</tr>
					</table>
				</div>
				<div class="well">
					<div class="row">
						<div class="col-xs-2">VA:</div>
						<div class="col-xs-4">
							<input type="text" value="<?php echo $refrtsn_retip['refraction_rtnp_l_va'];?>" name="refraction_rtnp_l_va" id="refraction_rtnp_l_va" class="form-control">
						</div>
						<div class="col-xs-2">HA:</div>
						<div class="col-xs-4">
							<input type="text" value="<?php echo $refrtsn_retip['refraction_rtnp_l_ha'];?>" name="refraction_rtnp_l_ha" id="refraction_rtnp_l_ha" class="form-control">
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="label_name">RETINOSCOPY (R) <i onclick="refraction_rtnp_rtl();" title="Copy Right to Left" class="fa fa-arrow-left"></i></div>
			</div>
			<div class="col-md-4">
				<div class="well">
					<table class="">
						<tr>
							<td width="33.33%"></td>
							<td width="33.33%"><input type="text" value="<?php echo $refrtsn_retip['refraction_rtnp_r_t'];?>" name="refraction_rtnp_r_t" id="refraction_rtnp_r_t" class="form-control"></td>
							<td width="33.33%"></td>
						</tr>
						<tr>
							<td><input type="text" value="<?php echo $refrtsn_retip['refraction_rtnp_r_l'];?>" name="refraction_rtnp_r_l" id="refraction_rtnp_r_l" class="form-control"></td>
							<td class="plus_symbol"><span class="hr"></span><span class="vr"></span></td>
							<td><input type="text" value="<?php echo $refrtsn_retip['refraction_rtnp_r_r'];?>" name="refraction_rtnp_r_r" id="refraction_rtnp_r_r" class="form-control"></td>
						</tr>
						<tr>
							<td width="33.33%"></td>
							<td width="33.33%"><input type="text" value="<?php echo $refrtsn_retip['refraction_rtnp_r_b'];?>" name="refraction_rtnp_r_b" id="refraction_rtnp_r_b" class="form-control"></td>
							<td width="33.33%"></td>
						</tr>
					</table>
				</div>
				<div class="well">
					<div class="row">
						<div class="col-xs-2">VA:</div>
						<div class="col-xs-4">
							<input type="text" value="<?php echo $refrtsn_retip['refraction_rtnp_r_va'];?>" name="refraction_rtnp_r_va" id="refraction_rtnp_r_va" class="form-control">
						</div>
						<div class="col-xs-2">HA:</div>
						<div class="col-xs-4">
							<input type="text" value="<?php echo $refrtsn_retip['refraction_rtnp_r_ha'];?>" name="refraction_rtnp_r_ha" id="refraction_rtnp_r_ha" class="form-control">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-body retin d-none">
		<div class="row">
			<div class="col-md-6" style="border-right:1px solid gray;">
				<div class="row">
					<div class="col-sm-3"> <label class="label_name">At Distance:</label> </div>
					<div class="col-sm-2 pr-0"> <input type="text" value="<?php echo $refrtsn_retip['refraction_rtnp_l_at_dis'];?>" name="refraction_rtnp_l_at_dis" id="refraction_rtnp_l_at_dis" class="form-control"> </div>
					<div class="col-sm-2 pl-0" style="border-right:1px solid gray;">&nbsp;<span>Mt/cm</span></div>
					<div class="col-sm-2 text-right"> <label class="label_name">Drug Used:</label> </div>
					<div class="col-sm-2 pr-0">

						<select name="refraction_rtnp_l_du" id="refraction_rtnp_l_du" class="w-60px">
							<option value="">Select</option>
							<option value="Tropicamide" <?php if($refrtsn_retip['refraction_rtnp_l_du']=='Tropicamide'){ echo 'selected';} ?>>Tropicamide</option>
							<option value="Tropicamide - P" <?php if($refrtsn_retip['refraction_rtnp_l_du']=='Tropicamide - P'){ echo 'selected';} ?>>Tropicamide - P</option>
							<option value="Atropine" <?php if($refrtsn_retip['refraction_rtnp_l_du']=='Atropine'){ echo 'selected';} ?>>Atropine</option>
							<option value="Cyclopentolate" <?php if($refrtsn_retip['refraction_rtnp_l_du']=='Cyclopentolate'){ echo 'selected';} ?>>Cyclopentolate</option>
							<option value="Homide" <?php if($refrtsn_retip['refraction_rtnp_l_du']=='Homide'){ echo 'selected';} ?>>Homide</option>
							<option value="Dry" <?php if($refrtsn_retip['refraction_rtnp_l_du']=='Dry'){ echo 'selected';} ?>>Dry</option>
						</select>

				</div>
				<div class="col-sm-1 pl-0">&nbsp;<span>Mt/cm</span></div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-sm-3"> <label class="label_name">At Distance:</label> </div>
					<div class="col-sm-2 pr-0"> <input type="text" value="<?php echo $refrtsn_retip['refraction_rtnp_r_at_dis'];?>" name="refraction_rtnp_r_at_dis" id="refraction_rtnp_r_at_dis" class="form-control"> </div>
					<div class="col-sm-2 pl-0" style="border-right:1px solid gray;">&nbsp;<span>Mt/cm</span></div>
					<div class="col-sm-2 text-right"> <label class="label_name">Drug Used:</label> </div>
					<div class="col-sm-2 pr-0"> 
						
						<select name="refraction_rtnp_r_du" id="refraction_rtnp_r_du" class="w-60px">
							<option value="">Select</option>
							<option value="Tropicamide" <?php if($refrtsn_retip['refraction_rtnp_r_du']=='Tropicamide'){ echo 'selected';} ?>>Tropicamide</option>
							<option value="Tropicamide - P" <?php if($refrtsn_retip['refraction_rtnp_r_du']=='Tropicamide - P'){ echo 'selected';} ?>>Tropicamide - P</option>
							<option value="Atropine" <?php if($refrtsn_retip['refraction_rtnp_r_du']=='Atropine'){ echo 'selected';} ?>>Atropine</option>
							<option value="Cyclopentolate" <?php if($refrtsn_retip['refraction_rtnp_r_du']=='Cyclopentolate'){ echo 'selected';} ?>>Cyclopentolate</option>
							<option value="Homide" <?php if($refrtsn_retip['refraction_rtnp_r_du']=='Homide'){ echo 'selected';} ?>>Homide</option>
							<option value="Dry" <?php if($refrtsn_retip['refraction_rtnp_r_du']=='Dry'){ echo 'selected';} ?>>Dry</option>
						</select>
				</div>
				<div class="col-sm-1 pl-0">&nbsp;<span>Mt/cm</span></div>
			</div>
		</div>
	</div>
	<div class="panel-footer retin d-none">
		<div class="row">
			<div class="col-md-6">
				<div class="well">
					<div class="row">
						<div class="col-md-4"><label>Comment:</label></div>
						<div class="col-md-8">
							<textarea name="refraction_rtnp_l_comm" id="refraction_rtnp_l_comm" class="form-control"><?php echo $refrtsn_retip['refraction_rtnp_l_comm'];?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="well">
					<div class="row">
						<div class="col-md-4"><label>Comment:</label></div>
						<div class="col-md-8">
							<textarea name="refraction_rtnp_r_comm" id="refraction_rtnp_r_comm" class="form-control"><?php echo $refrtsn_retip['refraction_rtnp_r_comm'];?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="panel panel-default">
	<div class="panel-body">			
		<div class="row">
			<div class="col-md-12 text-right btn_edit"><a href="javascript:void(0)" class="btn_fill" onclick="$('.pmt').toggle();">Edit</a><hr></div>
			<div class="col-md-2">
				<div class="label_name">PMT <i onclick="refraction_pmt_ltr();" title="Copy Left to Right" class="fa fa-arrow-right"></i> </div>
				<button type="button" class="btn_fill pmt d-none" title="Fill PMT" onclick="return open_modals('refraction_pmt','PMT');">Fill <i class="fa fa-arrow-right"></i> </button> 	
				<button type="button" title="Copy PMT to Glass Prescription" onclick="cpoy_to_glass_pres('refraction_pmt');" class="btn_fill"> Copy <i class="fa fa-arrow-down"></i></button>	
			</div>			
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="20%"></th>
							<th width="20%">Sph</th>
							<th width="20%">Cyl</th>
							<th width="20%">Axis</th>
							<th width="20%">Vision</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:left;">Distant</td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_l_dt_sph'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_l_dt_sph'];?>" name="refraction_pmt_l_dt_sph" id="refraction_pmt_l_dt_sph" class="w-50px pmt d-none"></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_l_dt_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_l_dt_cyl'];?>" name="refraction_pmt_l_dt_cyl" id="refraction_pmt_l_dt_cyl" class="w-50px pmt d-none"></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_l_dt_axis'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_l_dt_axis'];?>" name="refraction_pmt_l_dt_axis" id="refraction_pmt_l_dt_axis" class="w-50px pmt d-none"></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_l_dt_vision'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_l_dt_vision'];?>" name="refraction_pmt_l_dt_vision" id="refraction_pmt_l_dt_vision" class="w-50px pmt d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Add<span class="text-danger">#</span></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_l_ad_sph'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_l_ad_sph'];?>" name="refraction_pmt_l_ad_sph" id="refraction_pmt_l_ad_sph" class="w-50px pmt d-none"></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_l_ad_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_l_ad_cyl'];?>" name="refraction_pmt_l_ad_cyl" id="refraction_pmt_l_ad_cyl" class="w-50px pmt d-none" disabled ></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_l_ad_axis'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_l_ad_axis'];?>" name="refraction_pmt_l_ad_axis" id="refraction_pmt_l_ad_axis" class="w-50px pmt d-none" disabled ></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_l_ad_vision'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_l_ad_vision'];?>" name="refraction_pmt_l_ad_vision" id="refraction_pmt_l_ad_vision" class="w-50px pmt d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Near</td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_l_nr_sph'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_l_nr_sph'];?>" name="refraction_pmt_l_nr_sph" id="refraction_pmt_l_nr_sph" class="w-50px pmt d-none"></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_l_nr_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_l_nr_cyl'];?>" name="refraction_pmt_l_nr_cyl" id="refraction_pmt_l_nr_cyl" class="w-50px pmt d-none"></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_l_nr_axis'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_l_nr_axis'];?>" name="refraction_pmt_l_nr_axis" id="refraction_pmt_l_nr_axis" class="w-50px pmt d-none"></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_l_nr_vision'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_l_nr_vision'];?>" name="refraction_pmt_l_nr_vision" id="refraction_pmt_l_nr_vision" class="w-50px pmt d-none"></td>
						</tr>
					</tbody>
				</table>
			</div>					
			<div class="col-md-2">
				<div class="label_name">PMT <i onclick="refraction_pmt_rtl();" title="Copy Right to Left" class="fa fa-arrow-left"></i> </div>							
			</div>			
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="20%"></th>
							<th width="20%">Sph</th>
							<th width="20%">Cyl</th>
							<th width="20%">Axis</th>
							<th width="20%">Vision</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:left;">Distant</td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_r_dt_sph'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_r_dt_sph'];?>" name="refraction_pmt_r_dt_sph" id="refraction_pmt_r_dt_sph" class="w-50px pmt d-none"></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_r_dt_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_r_dt_cyl'];?>" name="refraction_pmt_r_dt_cyl" id="refraction_pmt_r_dt_cyl" class="w-50px pmt d-none"></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_r_dt_axis'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_r_dt_axis'];?>" name="refraction_pmt_r_dt_axis" id="refraction_pmt_r_dt_axis" class="w-50px pmt d-none"></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_r_dt_vision'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_r_dt_vision'];?>" name="refraction_pmt_r_dt_vision" id="refraction_pmt_r_dt_vision" class="w-50px pmt d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Add<span class="text-danger">#</span></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_r_ad_sph'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_r_ad_sph'];?>" name="refraction_pmt_r_ad_sph" id="refraction_pmt_r_ad_sph" class="w-50px pmt d-none"></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_r_ad_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_r_ad_cyl'];?>" name="refraction_pmt_r_ad_cyl" id="refraction_pmt_r_ad_cyl" class="w-50px pmt d-none" disabled></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_r_ad_axis'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_r_ad_axis'];?>" name="refraction_pmt_r_ad_axis" id="refraction_pmt_r_ad_axis" class="w-50px pmt d-none" disabled></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_r_ad_vision'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_r_ad_vision'];?>" name="refraction_pmt_r_ad_vision" id="refraction_pmt_r_ad_vision" class="w-50px pmt d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Near</td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_r_nr_sph'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_r_nr_sph'];?>" name="refraction_pmt_r_nr_sph" id="refraction_pmt_r_nr_sph" class="w-50px pmt d-none"></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_r_nr_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_r_nr_cyl'];?>" name="refraction_pmt_r_nr_cyl" id="refraction_pmt_r_nr_cyl" class="w-50px pmt d-none"></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_r_nr_axis'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_r_nr_axis'];?>" name="refraction_pmt_r_nr_axis" id="refraction_pmt_r_nr_axis" class="w-50px pmt d-none"></td>
							<td><span class="pmt"><?php echo $refrtsn_pmt['refraction_pmt_r_nr_vision'];?></span> <input type="text" value="<?php echo $refrtsn_pmt['refraction_pmt_r_nr_vision'];?>" name="refraction_pmt_r_nr_vision" id="refraction_pmt_r_nr_vision" class="w-50px pmt d-none"></td>
						</tr>
						</tr>
					</tbody>
				</table>							
			</div>
		</div>
	</div>
</section>

<section class="panel panel-default">
	<div class="panel-body">			
		<div class="row">
			<div class="col-md-12 text-right btn_edit"><a href="javascript:void(0)" class="btn_fill" onclick="$('.glasses_pre').toggle();">Edit</a><hr></div>
			<div class="col-md-2">
				<div class="label_name">GLASSES PRESCRIPTIONS (Rx)  <i onclick="refraction_gps_ltr();" title="Copy Left to Right" class="fa fa-arrow-right"></i> </div>
				<button type="button" class="btn_fill glasses_pre d-none" title="Fill Glasses Prescription" onclick="return open_modals('refraction_gps','GLASSES-PRESCRIPTIONS');">Fill <i class="fa fa-arrow-right"></i> </button> 	
				<div class="btn-group">
					<label class="btn-custom btn_fill"><input type="checkbox" value="1" name="refraction_gps_ad_check" id="refraction_gps_ad_check"> Advise</label>
			    </div>
			</div>			
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="20%"></th>
							<th width="20%">Sph</th>
							<th width="20%">Cyl</th>
							<th width="20%">Axis</th>
							<th width="20%">Vision</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:left;">Distant</td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_l_dt_sph'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_l_dt_sph'];?>" name="refraction_gps_l_dt_sph" id="refraction_gps_l_dt_sph" class="w-50px glasses_pre d-none"></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_l_dt_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_l_dt_cyl'];?>" name="refraction_gps_l_dt_cyl" id="refraction_gps_l_dt_cyl" class="w-50px glasses_pre d-none"></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_l_dt_axis'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_l_dt_axis'];?>" name="refraction_gps_l_dt_axis" id="refraction_gps_l_dt_axis" class="w-50px glasses_pre d-none"></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_l_dt_vision'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_l_dt_vision'];?>" name="refraction_gps_l_dt_vision" id="refraction_gps_l_dt_vision" class="w-50px glasses_pre d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Add <span class="text-danger">#</span></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_l_ad_sph'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_l_ad_sph'];?>" name="refraction_gps_l_ad_sph" id="refraction_gps_l_ad_sph" class="w-50px glasses_pre d-none"></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_l_ad_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_l_ad_cyl'];?>" name="refraction_gps_l_ad_cyl" id="refraction_gps_l_ad_cyl" class="w-50px glasses_pre d-none" disabled></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_l_ad_axis'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_l_ad_axis'];?>" name="refraction_gps_l_ad_axis" id="refraction_gps_l_ad_axis" class="w-50px glasses_pre d-none" disabled></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_l_ad_vision'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_l_ad_vision'];?>" name="refraction_gps_l_ad_vision" id="refraction_gps_l_ad_vision" class="w-50px glasses_pre d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Near</td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_l_nr_sph'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_l_nr_sph'];?>" name="refraction_gps_l_nr_sph" id="refraction_gps_l_nr_sph" class="w-50px glasses_pre d-none"></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_l_nr_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_l_nr_cyl'];?>" name="refraction_gps_l_nr_cyl" id="refraction_gps_l_nr_cyl" class="w-50px glasses_pre d-none"></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_l_nr_axis'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_l_nr_axis'];?>" name="refraction_gps_l_nr_axis" id="refraction_gps_l_nr_axis" class="w-50px glasses_pre d-none"></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_l_nr_vision'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_l_nr_vision'];?>" name="refraction_gps_l_nr_vision" id="refraction_gps_l_nr_vision" class="w-50px glasses_pre d-none"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-2">
				<div class="label_name">GLASSES PRESCRIPTIONS (Rx)  <i onclick="refraction_gps_rtl();" title="Copy Right to Left" class="fa fa-arrow-left"></i></div>							
			</div>			
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="20%"></th>
							<th width="20%">Sph</th>
							<th width="20%">Cyl</th>
							<th width="20%">Axis</th>
							<th width="20%">Vision</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:left;">Distant</td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_r_dt_sph'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_r_dt_sph'];?>" name="refraction_gps_r_dt_sph" id="refraction_gps_r_dt_sph" class="w-50px glasses_pre d-none"></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_r_dt_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_r_dt_cyl'];?>" name="refraction_gps_r_dt_cyl" id="refraction_gps_r_dt_cyl" class="w-50px glasses_pre d-none"></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_r_dt_axis'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_r_dt_axis'];?>" name="refraction_gps_r_dt_axis" id="refraction_gps_r_dt_axis" class="w-50px glasses_pre d-none"></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_r_dt_vision'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_r_dt_vision'];?>" name="refraction_gps_r_dt_vision" id="refraction_gps_r_dt_vision" class="w-50px glasses_pre d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Add <span class="text-danger">#</span></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_r_ad_sph'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_r_ad_sph'];?>" name="refraction_gps_r_ad_sph" id="refraction_gps_r_ad_sph" class="w-50px glasses_pre d-none"></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_r_ad_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_r_ad_cyl'];?>" name="refraction_gps_r_ad_cyl" id="refraction_gps_r_ad_cyl" class="w-50px glasses_pre d-none" disabled></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_r_ad_axis'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_r_ad_axis'];?>" name="refraction_gps_r_ad_axis" id="refraction_gps_r_ad_axis" class="w-50px glasses_pre d-none" disabled></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_r_ad_vision'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_r_ad_vision'];?>" name="refraction_gps_r_ad_vision" id="refraction_gps_r_ad_vision" class="w-50px glasses_pre d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Near</td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_r_nr_sph'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_r_nr_sph'];?>" name="refraction_gps_r_nr_sph" id="refraction_gps_r_nr_sph" class="w-50px glasses_pre d-none"></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_r_nr_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_r_nr_cyl'];?>" name="refraction_gps_r_nr_cyl" id="refraction_gps_r_nr_cyl" class="w-50px glasses_pre d-none"></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_r_nr_axis'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_r_nr_axis'];?>" name="refraction_gps_r_nr_axis" id="refraction_gps_r_nr_axis" class="w-50px glasses_pre d-none"></td>
							<td><span class="glasses_pre"><?php echo $refrtsn_glassp['refraction_gps_r_nr_vision'];?></span> <input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_r_nr_vision'];?>" name="refraction_gps_r_nr_vision" id="refraction_gps_r_nr_vision" class="w-50px glasses_pre d-none"></td>
						</tr>
					</tbody>
				</table>							
			</div>
		</div>	
	</div>
	<div class="panel-footer">
		<label class="small"><input name="refraction_gps_ad_show_print" id="refraction_gps_ad_show_print" type="checkbox" <?php if($refrtsn_glassp['refraction_gps_ad_show_print']==1){ echo 'checked';};?>><span>Show Add in Print</span></label>
	</div>
	<div class="panel-footer d-none glasses_pre">
		<div class="row">
			<div class="col-md-6" style="border-right:1px solid lightgray;">
				<div class="row">
					<div class="col-sm-4 text-right">Type of Lens</div>
					<div class="col-sm-2">
						<select name="refraction_gps_l_tol" id="refraction_gps_l_tol"  class="w-80px">
							<option value="">Select</option>
							<option value="Single Vision - Distant" <?php if($refrtsn_glassp['refraction_gps_l_tol']=='Single Vision - Distant'){ echo 'selected';} ?>>Single Vision - Distant</option>
							<option value="Single Vision - Near" <?php if($refrtsn_glassp['refraction_gps_l_tol']=='Single Vision - Near'){ echo 'selected';} ?>>Single Vision - Near</option>
							<option value="Bifocal" <?php if($refrtsn_glassp['refraction_gps_l_tol']=='Bifocal'){ echo 'selected';} ?>>Bifocal</option>
							<option value="Progressive" <?php if($refrtsn_glassp['refraction_gps_l_tol']=='Progressive'){ echo 'selected';} ?>>Progressive</option>
							<option value="Bifocal or Progressive" <?php if($refrtsn_glassp['refraction_gps_l_tol']=='Bifocal or Progressive'){ echo 'selected';} ?>>Bifocal or Progressive</option>
							<option value="D Bifocal" <?php if($refrtsn_glassp['refraction_gps_l_tol']=='D Bifocal'){ echo 'selected';} ?>>D Bifocal</option>
							<option value="KT Bifocal" <?php if($refrtsn_glassp['refraction_gps_l_tol']=='KT Bifocal'){ echo 'selected';} ?>>KT Bifocal</option>
						</select>
					</div>
					<div class="col-sm-3 text-right">IPD</div>
					<div class="col-sm-3">
						<div class="input-group">
							<input type="text" name="refraction_gps_l_ipd" id="refraction_gps_l_ipd" value="<?php echo $refrtsn_glassp['refraction_gps_l_ipd'];?>" class="form-control">
							<span class="input-group-addon">mm</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-sm-4 text-right">Type of Lens</div>
					<div class="col-sm-2">
						<select name="refraction_gps_r_tol" id="refraction_gps_r_tol"  class="w-80px">
							<option value="">Select</option>
							<option value="Single Vision - Distant" <?php if($refrtsn_glassp['refraction_gps_r_tol']=='Single Vision - Distant'){ echo 'selected';} ?>>Single Vision - Distant</option>
							<option value="Single Vision - Near" <?php if($refrtsn_glassp['refraction_gps_r_tol']=='Single Vision - Near'){ echo 'selected';} ?>>Single Vision - Near</option>
							<option value="Bifocal" <?php if($refrtsn_glassp['refraction_gps_r_tol']=='Bifocal'){ echo 'selected';} ?>>Bifocal</option>
							<option value="Progressive" <?php if($refrtsn_glassp['refraction_gps_r_tol']=='Progressive'){ echo 'selected';} ?>>Progressive</option>
							<option value="Bifocal or Progressive" <?php if($refrtsn_glassp['refraction_gps_r_tol']=='Bifocal or Progressive'){ echo 'selected';} ?>>Bifocal or Progressive</option>
							<option value="D Bifocal" <?php if($refrtsn_glassp['refraction_gps_r_tol']=='D Bifocal'){ echo 'selected';} ?>>D Bifocal</option>
							<option value="KT Bifocal" <?php if($refrtsn_glassp['refraction_gps_r_tol']=='KT Bifocal'){ echo 'selected';} ?>>KT Bifocal</option>
						</select>
					</div>
					<div class="col-sm-3 text-right">IPD</div>
					<div class="col-sm-3">
						<div class="input-group">
							<input type="text" value="<?php echo $refrtsn_glassp['refraction_gps_r_ipd'];?>" name="refraction_gps_r_ipd" id="refraction_gps_r_ipd" class="form-control">
							<span class="input-group-addon">mm</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-footer d-none glasses_pre">
		<div class="row">
			<div class="col-md-6" style="border-right:1px solid lightgray;">
				<div class="row">
					<div class="col-md-4">
						<div class="clearfix">Lens Material</div>
						<select name="refraction_gps_l_lns_mat" id="refraction_gps_l_lns_mat"  class="form-control">
							<option value="">Select</option>
							<option value="Mineral" <?php if($refrtsn_glassp['refraction_gps_l_lns_mat']=='Mineral'){ echo 'selected';} ?>>Mineral</option>
							<option value="CR 39r" <?php if($refrtsn_glassp['refraction_gps_l_lns_mat']=='CR 39r'){ echo 'selected';} ?>>CR 39r</option>
							<option value="Patient Choice" <?php if($refrtsn_glassp['refraction_gps_l_lns_mat']=='Patient Choice'){ echo 'selected';} ?>>Patient Choice</option>
							<option value="Polycarbonate" <?php if($refrtsn_glassp['refraction_gps_l_lns_mat']=='Polycarbonate'){ echo 'selected';} ?>>Polycarbonate</option>
							<option value="Trivex" <?php if($refrtsn_glassp['refraction_gps_l_lns_mat']=='Trivex'){ echo 'selected';} ?>>Trivex</option>
							<option value="Organic" <?php if($refrtsn_glassp['refraction_gps_l_lns_mat']=='Organic'){ echo 'selected';} ?>>Organic</option>
							<option value="Evalution" <?php if($refrtsn_glassp['refraction_gps_l_lns_mat']=='D Bifocal'){ echo 'selected';} ?>>Evalution</option>
							<option value="MR7" <?php if($refrtsn_glassp['refraction_gps_l_lns_mat']=='Evalution'){ echo 'selected';} ?>>MR7</option>
							<option value="MR10" <?php if($refrtsn_glassp['refraction_gps_l_lns_mat']=='MR10'){ echo 'selected';} ?>>MR10</option>
							<option value="MR8" <?php if($refrtsn_glassp['refraction_gps_l_lns_mat']=='MR8'){ echo 'selected';} ?>>MR8</option>
							<option value="Press on" <?php if($refrtsn_glassp['refraction_gps_l_lns_mat']=='Press on'){ echo 'selected';} ?>>Press on</option>
						</select>
					</div>
					<div class="col-md-4">
						<div class="clearfix">Lens Tint</div>
						<select name="refraction_gps_l_lns_tnt" id="refraction_gps_l_lns_tnt"  class="form-control">
							<option value="">Select</option>
							<option value="White" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='White'){ echo 'selected';} ?>>White</option>
							<option value="Amber" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Amber'){ echo 'selected';} ?>>Amber</option>
							<option value="B1" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='B1'){ echo 'selected';} ?>>B1</option>
							<option value="B2" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='B2'){ echo 'selected';} ?>>B2</option>
							<option value="B3" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='B3'){ echo 'selected';} ?>>B3</option>
							<option value="Brown" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Brown'){ echo 'selected';} ?>>Brown</option>
							<option value="G-15" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='G-15'){ echo 'selected';} ?>>G-15</option>
							<option value="Gradient Tint" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Gradient Tint'){ echo 'selected';} ?>>Gradient Tint</option>
							<option value="Patient Choice" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Patient Choice'){ echo 'selected';} ?>>Patient Choice</option>
							<option value="Photogrey" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Photogrey'){ echo 'selected';} ?>>Photogrey</option>
							<option value="Photobrown" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Photobrown'){ echo 'selected';} ?>>Photobrown</option>
							<option value="SP1" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='SP1'){ echo 'selected';} ?>>SP1</option>
							<option value="SP11" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='SP11'){ echo 'selected';} ?>>SP11</option>
							<option value="SP2" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='SP2'){ echo 'selected';} ?>>SP2</option>
							<option value="Yellow" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Yellow'){ echo 'selected';} ?>>Yellow</option>
							<option value="Grey" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Grey'){ echo 'selected';} ?>>Grey</option>
							<option value="Clear" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Clear'){ echo 'selected';} ?>>Clear</option>
							<option value="Grey/Brown" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Grey/Brown'){ echo 'selected';} ?>>Grey/Brown</option>
							<option value="Gray C/Brown C" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Gray C/Brown C'){ echo 'selected';} ?>>Gray C/Brown C</option>
							<option value="Grey A" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Grey A'){ echo 'selected';} ?>>Grey A</option>
							<option value="Gray / Brown / Green" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Gray / Brown / Green'){ echo 'selected';} ?>>Gray / Brown / Green</option>
							<option value="PGX / PBX" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='PGX / PBX'){ echo 'selected';} ?>>PGX / PBX</option>
							<option value="Non Tintable" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Non Tintable'){ echo 'selected';} ?>>Non Tintable</option>
							<option value="Pink" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Pink'){ echo 'selected';} ?>>Pink</option>
							<option value="Photo Fusion Grey" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Photo Fusion Grey'){ echo 'selected';} ?>>Photo Fusion Grey</option>
							<option value="Phot Fusion Brown" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Phot Fusion Brown'){ echo 'selected';} ?>>Phot Fusion Brown</option>
							<option value="Polarised Brown" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Polarised Brown'){ echo 'selected';} ?>>Polarised Brown</option>
							<option value="Polarised Grey" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Polarised Grey'){ echo 'selected';} ?>>Polarised Grey</option>
							<option value="Transition" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Transition'){ echo 'selected';} ?>>Transition</option>
							<option value="A1" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='A1'){ echo 'selected';} ?>>A1</option>
							<option value="A2" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='A2'){ echo 'selected';} ?>>A2</option>
							<option value="Colabar C" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Colabar C'){ echo 'selected';} ?>>Colabar C</option>
							<option value="Colabar D" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Colabar D'){ echo 'selected';} ?>>Colabar D</option>
							<option value="Non Clear" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Non Clear'){ echo 'selected';} ?>>Non Clear</option>
							<option value="Polarised" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Polarised'){ echo 'selected';} ?>>Polarised</option>
							<option value="Anti-reflection Coating" <?php if($refrtsn_glassp['refraction_gps_l_lns_tnt']=='Anti-reflection Coating'){ echo 'selected';} ?>>Anti-reflection Coating</option>
						</select>
					</div>
					<div class="col-md-4">
						<div class="clearfix">Frame Material</div>
						<select name="refraction_gps_l_fm" id="refraction_gps_l_fm"  class="form-control">
							<option value="">Select</option>
							<option value="Patient Choice" <?php if($refrtsn_glassp['refraction_gps_l_fm']=='Patient Choice'){ echo 'selected';} ?>>Patient Choice</option>
							<option value="Shell" <?php if($refrtsn_glassp['refraction_gps_l_fm']=='Shell'){ echo 'selected';} ?>>Shell</option>
							<option value="Metal" <?php if($refrtsn_glassp['refraction_gps_l_fm']=='Metal'){ echo 'selected';} ?>>Metal</option>
							<option value="Titanium" <?php if($refrtsn_glassp['refraction_gps_l_fm']=='Titanium'){ echo 'selected';} ?>>Titanium</option>
							<option value="Shell(Pediatric Use)" <?php if($refrtsn_glassp['refraction_gps_l_fm']=='Shell(Pediatric Use)'){ echo 'selected';} ?>>Shell(Pediatric Use)</option>
							<option value="Hypo-Allergenic Metal" <?php if($refrtsn_glassp['refraction_gps_l_fm']=='Hypo-Allergenic Metal'){ echo 'selected';} ?>>Hypo-Allergenic Metal</option>
							<option value="Plastic" <?php if($refrtsn_glassp['refraction_gps_l_fm']=='Plastic'){ echo 'selected';} ?>>Plastic</option>
							<option value="Carbon" <?php if($refrtsn_glassp['refraction_gps_l_fm']=='Carbon'){ echo 'selected';} ?>>Carbon</option>
							<option value="Acetate" <?php if($refrtsn_glassp['refraction_gps_l_fm']=='Acetate'){ echo 'selected';} ?>>Acetate</option>
							<option value="Aluminium" <?php if($refrtsn_glassp['refraction_gps_l_fm']=='Aluminium'){ echo 'selected';} ?>>Aluminium</option>
							<option value="Optyl" <?php if($refrtsn_glassp['refraction_gps_l_fm']=='Optyl'){ echo 'selected';} ?>>Optyl</option>
							<option value="Polyamide" <?php if($refrtsn_glassp['refraction_gps_l_fm']=='Polyamide'){ echo 'selected';} ?>>Polyamide</option>
							<option value="Stainless Steel" <?php if($refrtsn_glassp['refraction_gps_l_fm']=='Stainless Steel'){ echo 'selected';} ?>>Stainless Steel</option>
							<option value="TR-90" <?php if($refrtsn_glassp['refraction_gps_l_fm']=='TR-90'){ echo 'selected';} ?>>TR-90</option>
							<option value="Ultem" <?php if($refrtsn_glassp['refraction_gps_l_fm']=='Ultem'){ echo 'selected';} ?>>Ultem</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-4">
						<div class="clearfix">Lens Material</div>
						<select name="refraction_gps_r_lns_mat" id="refraction_gps_r_lns_mat" class="form-control">
							<option value="">Select</option>
							<option value="Mineral" <?php if($refrtsn_glassp['refraction_gps_r_lns_mat']=='Mineral'){ echo 'selected';} ?>>Mineral</option>
							<option value="CR 39r" <?php if($refrtsn_glassp['refraction_gps_r_lns_mat']=='CR 39r'){ echo 'selected';} ?>>CR 39r</option>
							<option value="Patient Choice" <?php if($refrtsn_glassp['refraction_gps_r_lns_mat']=='Patient Choice'){ echo 'selected';} ?>>Patient Choice</option>
							<option value="Polycarbonate" <?php if($refrtsn_glassp['refraction_gps_r_lns_mat']=='Polycarbonate'){ echo 'selected';} ?>>Polycarbonate</option>
							<option value="Trivex" <?php if($refrtsn_glassp['refraction_gps_r_lns_mat']=='Trivex'){ echo 'selected';} ?>>Trivex</option>
							<option value="Organic" <?php if($refrtsn_glassp['refraction_gps_r_lns_mat']=='Organic'){ echo 'selected';} ?>>Organic</option>
							<option value="Evalution" <?php if($refrtsn_glassp['refraction_gps_r_lns_mat']=='D Bifocal'){ echo 'selected';} ?>>Evalution</option>
							<option value="MR7" <?php if($refrtsn_glassp['refraction_gps_r_lns_mat']=='Evalution'){ echo 'selected';} ?>>MR7</option>
							<option value="MR10" <?php if($refrtsn_glassp['refraction_gps_r_lns_mat']=='MR10'){ echo 'selected';} ?>>MR10</option>
							<option value="MR8" <?php if($refrtsn_glassp['refraction_gps_r_lns_mat']=='MR8'){ echo 'selected';} ?>>MR8</option>
							<option value="Press on" <?php if($refrtsn_glassp['refraction_gps_r_lns_mat']=='Press on'){ echo 'selected';} ?>>Press on</option>
						</select>
					</div>
					<div class="col-md-4">
						<div class="clearfix">Lens Tint</div>
						<select name="refraction_gps_r_lns_tnt" id="refraction_gps_r_lns_tnt"  class="form-control">
							<option value="">Select</option>
							<option value="White" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='White'){ echo 'selected';} ?>>White</option>
							<option value="Amber" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Amber'){ echo 'selected';} ?>>Amber</option>
							<option value="B1" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='B1'){ echo 'selected';} ?>>B1</option>
							<option value="B2" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='B2'){ echo 'selected';} ?>>B2</option>
							<option value="B3" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='B3'){ echo 'selected';} ?>>B3</option>
							<option value="Brown" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Brown'){ echo 'selected';} ?>>Brown</option>
							<option value="G-15" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='G-15'){ echo 'selected';} ?>>G-15</option>
							<option value="Gradient Tint" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Gradient Tint'){ echo 'selected';} ?>>Gradient Tint</option>
							<option value="Patient Choice" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Patient Choice'){ echo 'selected';} ?>>Patient Choice</option>
							<option value="Photogrey" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Photogrey'){ echo 'selected';} ?>>Photogrey</option>
							<option value="Photobrown" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Photobrown'){ echo 'selected';} ?>>Photobrown</option>
							<option value="SP1" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='SP1'){ echo 'selected';} ?>>SP1</option>
							<option value="SP11" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='SP11'){ echo 'selected';} ?>>SP11</option>
							<option value="SP2" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='SP2'){ echo 'selected';} ?>>SP2</option>
							<option value="Yellow" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Yellow'){ echo 'selected';} ?>>Yellow</option>
							<option value="Grey" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Grey'){ echo 'selected';} ?>>Grey</option>
							<option value="Clear" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Clear'){ echo 'selected';} ?>>Clear</option>
							<option value="Grey/Brown" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Grey/Brown'){ echo 'selected';} ?>>Grey/Brown</option>
							<option value="Gray C/Brown C" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Gray C/Brown C'){ echo 'selected';} ?>>Gray C/Brown C</option>
							<option value="Grey A" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Grey A'){ echo 'selected';} ?>>Grey A</option>
							<option value="Gray / Brown / Green" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Gray / Brown / Green'){ echo 'selected';} ?>>Gray / Brown / Green</option>
							<option value="PGX / PBX" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='PGX / PBX'){ echo 'selected';} ?>>PGX / PBX</option>
							<option value="Non Tintable" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Non Tintable'){ echo 'selected';} ?>>Non Tintable</option>
							<option value="Pink" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Pink'){ echo 'selected';} ?>>Pink</option>
							<option value="Photo Fusion Grey" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Photo Fusion Grey'){ echo 'selected';} ?>>Photo Fusion Grey</option>
							<option value="Phot Fusion Brown" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Phot Fusion Brown'){ echo 'selected';} ?>>Phot Fusion Brown</option>
							<option value="Polarised Brown" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Polarised Brown'){ echo 'selected';} ?>>Polarised Brown</option>
							<option value="Polarised Grey" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Polarised Grey'){ echo 'selected';} ?>>Polarised Grey</option>
							<option value="Transition" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Transition'){ echo 'selected';} ?>>Transition</option>
							<option value="A1" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='A1'){ echo 'selected';} ?>>A1</option>
							<option value="A2" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='A2'){ echo 'selected';} ?>>A2</option>
							<option value="Colabar C" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Colabar C'){ echo 'selected';} ?>>Colabar C</option>
							<option value="Colabar D" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Colabar D'){ echo 'selected';} ?>>Colabar D</option>
							<option value="Non Clear" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Non Clear'){ echo 'selected';} ?>>Non Clear</option>
							<option value="Polarised" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Polarised'){ echo 'selected';} ?>>Polarised</option>
							<option value="Anti-reflection Coating" <?php if($refrtsn_glassp['refraction_gps_r_lns_tnt']=='Anti-reflection Coating'){ echo 'selected';} ?>>Anti-reflection Coating</option>
						</select>
					</div>
					<div class="col-md-4">
						<div class="clearfix">Frame Material</div>
						<select name="refraction_gps_r_fm" id="refraction_gps_r_fm"  class="form-control">
							<option value="">Select</option>
							<option value="Patient Choice" <?php if($refrtsn_glassp['refraction_gps_r_fm']=='Patient Choice'){ echo 'selected';} ?>>Patient Choice</option>
							<option value="Shell" <?php if($refrtsn_glassp['refraction_gps_r_fm']=='Shell'){ echo 'selected';} ?>>Shell</option>
							<option value="Metal" <?php if($refrtsn_glassp['refraction_gps_r_fm']=='Metal'){ echo 'selected';} ?>>Metal</option>
							<option value="Titanium" <?php if($refrtsn_glassp['refraction_gps_r_fm']=='Titanium'){ echo 'selected';} ?>>Titanium</option>
							<option value="Shell(Pediatric Use)" <?php if($refrtsn_glassp['refraction_gps_r_fm']=='Shell(Pediatric Use)'){ echo 'selected';} ?>>Shell(Pediatric Use)</option>
							<option value="Hypo-Allergenic Metal" <?php if($refrtsn_glassp['refraction_gps_r_fm']=='Hypo-Allergenic Metal'){ echo 'selected';} ?>>Hypo-Allergenic Metal</option>
							<option value="Plastic" <?php if($refrtsn_glassp['refraction_gps_r_fm']=='Plastic'){ echo 'selected';} ?>>Plastic</option>
							<option value="Carbon" <?php if($refrtsn_glassp['refraction_gps_r_fm']=='Carbon'){ echo 'selected';} ?>>Carbon</option>
							<option value="Acetate" <?php if($refrtsn_glassp['refraction_gps_r_fm']=='Acetate'){ echo 'selected';} ?>>Acetate</option>
							<option value="Aluminium" <?php if($refrtsn_glassp['refraction_gps_r_fm']=='Aluminium'){ echo 'selected';} ?>>Aluminium</option>
							<option value="Optyl" <?php if($refrtsn_glassp['refraction_gps_r_fm']=='Optyl'){ echo 'selected';} ?>>Optyl</option>
							<option value="Polyamide" <?php if($refrtsn_glassp['refraction_gps_r_fm']=='Polyamide'){ echo 'selected';} ?>>Polyamide</option>
							<option value="Stainless Steel" <?php if($refrtsn_glassp['refraction_gps_r_fm']=='Stainless Steel'){ echo 'selected';} ?>>Stainless Steel</option>
							<option value="TR-90" <?php if($refrtsn_glassp['refraction_gps_r_fm']=='TR-90'){ echo 'selected';} ?>>TR-90</option>
							<option value="Ultem" <?php if($refrtsn_glassp['refraction_gps_r_fm']=='Ultem'){ echo 'selected';} ?>>Ultem</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="row m-t-5">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-4">Advice:</div>
					<div class="col-md-8">
						<textarea name="refraction_gps_l_advs" id="refraction_gps_l_advs"  class="form-control"><?php echo $refrtsn_glassp['refraction_gps_l_advs'];?></textarea>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-4">Advice:</div>
					<div class="col-md-8">
						<textarea name="refraction_gps_r_advs" id="refraction_gps_r_advs"  class="form-control"><?php echo $refrtsn_glassp['refraction_gps_r_advs'];?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<section  class="panel panel-default ">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12 text-right btn_edit"><a href="javascript:void(0)" class="btn_fill" onclick="$('.inter_glass').toggle();">Edit</a> <hr></div>
			<div class="col-md-2">
				<div class="label_name">INTERMEDIATE GLASSES PRESCRIPTIONS (Rx) <i onclick="refraction_itgp_ltr();" title="Copy Left to Right" class="fa fa-arrow-right"></i> </div>
				<button type="button" class="btn_fill inter_glass d-none" title="Fill Glasses Prescription" onclick="return open_modals('refraction_itgp','INTERMEDIATE-GLASSES-PRESCRIPTIONS');">Fill <i class="fa fa-arrow-right"></i> </button> 
				<div class="btn-group">
					<label class="btn-custom btn_fill"><input type="checkbox" value="1" name="refraction_itgp_ad_check" id="refraction_itgp_ad_check"> Advise</label>
				</div>
			</div>
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="20%"></th>
							<th width="20%">Sph</th>
							<th width="20%">Cyl</th>
							<th width="20%">Axis</th>
							<th width="20%">Vision</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:left;">Distant</td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_l_dt_sph'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_l_dt_sph'];?>" name="refraction_itgp_l_dt_sph" id="refraction_itgp_l_dt_sph" class="w-50px inter_glass d-none"></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_l_dt_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_l_dt_cyl'];?>" name="refraction_itgp_l_dt_cyl"  id="refraction_itgp_l_dt_cyl" class="w-50px inter_glass d-none"></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_l_dt_axis'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_l_dt_axis'];?>" name="refraction_itgp_l_dt_axis" id="refraction_itgp_l_dt_axis" class="w-50px inter_glass d-none"></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_l_dt_vision'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_l_dt_vision'];?>" name="refraction_itgp_l_dt_vision" id="refraction_itgp_l_dt_vision" class="w-50px inter_glass d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Add <span class="text-danger">#</span></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_l_ad_sph'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_l_ad_sph'];?>" name="refraction_itgp_l_ad_sph"  id="refraction_itgp_l_ad_sph" class="w-50px inter_glass d-none"></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_l_ad_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_l_ad_cyl'];?>" name="refraction_itgp_l_ad_cyl"  id="refraction_itgp_l_ad_cyl" class="w-50px inter_glass d-none" disabled></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_l_ad_axis'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_l_ad_axis'];?>" name="refraction_itgp_l_ad_axis"  id="refraction_itgp_l_ad_axis" class="w-50px inter_glass d-none" disabled></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_l_ad_vision'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_l_ad_vision'];?>" name="refraction_itgp_l_ad_vision"  id="refraction_itgp_l_ad_vision" class="w-50px inter_glass d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Near</td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_l_nr_sph'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_l_nr_sph'];?>" name="refraction_itgp_l_nr_sph"  id="refraction_itgp_l_nr_sph" class="w-50px inter_glass d-none"></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_l_nr_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_l_nr_cyl'];?>" name="refraction_itgp_l_nr_cyl"  id="refraction_itgp_l_nr_cyl" class="w-50px inter_glass d-none"></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_l_nr_axis'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_l_nr_axis'];?>" name="refraction_itgp_l_nr_axis"  id="refraction_itgp_l_nr_axis" class="w-50px inter_glass d-none"></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_l_nr_vision'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_l_nr_vision'];?>" name="refraction_itgp_l_nr_vision"  id="refraction_itgp_l_nr_vision" class="w-50px inter_glass d-none"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-2">
				<div class="label_name">INTERMEDIATE GLASSES PRESCRIPTIONS (Rx) <i onclick="refraction_itgp_rtl();" title="Copy Right to Left" class="fa fa-arrow-left"></i> </div>
			</div>
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="20%"></th>
							<th width="20%">Sph</th>
							<th width="20%">Cyl</th>
							<th width="20%">Axis</th>
							<th width="20%">Vision</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:left;">Distant</td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_r_dt_sph'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_r_dt_sph'];?>" name="refraction_itgp_r_dt_sph" id="refraction_itgp_r_dt_sph" class="w-50px inter_glass d-none"></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_r_dt_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_r_dt_cyl'];?>" name="refraction_itgp_r_dt_cyl" id="refraction_itgp_r_dt_cyl" class="w-50px inter_glass d-none"></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_r_dt_axis'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_r_dt_axis'];?>" name="refraction_itgp_r_dt_axis" id="refraction_itgp_r_dt_axis" class="w-50px inter_glass d-none"></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_r_dt_vision'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_r_dt_vision'];?>" name="refraction_itgp_r_dt_vision" id="refraction_itgp_r_dt_vision" class="w-50px inter_glass d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Add <span class="text-danger">#</span></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_r_ad_sph'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_r_ad_sph'];?>" name="refraction_itgp_r_ad_sph" id="refraction_itgp_r_ad_sph" class="w-50px inter_glass d-none"></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_r_ad_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_r_ad_cyl'];?>" name="refraction_itgp_r_ad_cyl" id="refraction_itgp_r_ad_cyl" class="w-50px inter_glass d-none" disabled></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_r_ad_axis'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_r_ad_axis'];?>" name="refraction_itgp_r_ad_axis" id="refraction_itgp_r_ad_axis" class="w-50px inter_glass d-none" disabled></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_r_ad_vision'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_r_ad_vision'];?>" name="refraction_itgp_r_ad_vision" id="refraction_itgp_r_ad_vision" class="w-50px inter_glass d-none"></td>
						</tr>
						<tr>
							<td style="text-align:left;">Near</td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_r_nr_sph'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_r_nr_sph'];?>" name="refraction_itgp_r_nr_sph" id="refraction_itgp_r_nr_sph" class="w-50px inter_glass d-none"></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_r_nr_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_r_nr_cyl'];?>" name="refraction_itgp_r_nr_cyl" id="refraction_itgp_r_nr_cyl" class="w-50px inter_glass d-none"></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_r_nr_axis'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_r_nr_axis'];?>" name="refraction_itgp_r_nr_axis" id="refraction_itgp_r_nr_axis" class="w-50px inter_glass d-none"></td>
							<td><span class="inter_glass"><?php echo $refrtsn_inter_glass['refraction_itgp_r_nr_vision'];?></span> <input type="text" value="<?php echo $refrtsn_inter_glass['refraction_itgp_r_nr_vision'];?>" name="refraction_itgp_r_nr_vision" id="refraction_itgp_r_nr_vision" class="w-50px inter_glass d-none"></td>
						</tr>
					</tbody>
				</table>
			</div>						
		</div>
	</div>
	<div class="panel-footer">
		<label class="small"><input type="checkbox" name="refraction_itgp_ad_show_print" id="refraction_itgp_ad_show_print" <?php if($refrtsn_inter_glass['refraction_itgp_ad_show_print']==1){ echo 'checked';}?>><span>Show Add in Print</span></label>
	</div>
	<div class="panel-footer d-none inter_glass">
		<div class="row">
			<div class="col-md-6" style="border-right:1px solid lightgray;">
				<div class="row">
					<div class="col-sm-4 text-right">Type of Lens</div>
					<div class="col-sm-2">
						<select name="refraction_itgp_l_tol"  id="refraction_itgp_l_tol" class="w-80px">
							<option value="">Select</option>
							<option value="Single Vision - Distant" <?php if($refrtsn_inter_glass['refraction_itgp_l_tol']=='Single Vision - Distant'){ echo 'selected';} ?>>Single Vision - Distant</option>
							<option value="Single Vision - Near" <?php if($refrtsn_inter_glass['refraction_itgp_l_tol']=='Single Vision - Near'){ echo 'selected';} ?>>Single Vision - Near</option>
							<option value="Bifocal" <?php if($refrtsn_inter_glass['refraction_itgp_l_tol']=='Bifocal'){ echo 'selected';} ?>>Bifocal</option>
							<option value="Progressive" <?php if($refrtsn_inter_glass['refraction_itgp_l_tol']=='Progressive'){ echo 'selected';} ?>>Progressive</option>
							<option value="Bifocal or Progressive" <?php if($refrtsn_inter_glass['refraction_itgp_l_tol']=='Bifocal or Progressive'){ echo 'selected';} ?>>Bifocal or Progressive</option>
							<option value="D Bifocal" <?php if($refrtsn_inter_glass['refraction_itgp_l_tol']=='D Bifocal'){ echo 'selected';} ?>>D Bifocal</option>
							<option value="KT Bifocal" <?php if($refrtsn_inter_glass['refraction_itgp_l_tol']=='KT Bifocal'){ echo 'selected';} ?>>KT Bifocal</option>
						</select>
					</div>
					<div class="col-sm-3 text-right">IPD</div>
					<div class="col-sm-3">
						<div class="input-group">
							<input type="text" name="refraction_itgp_l_ipd" value="<?php echo $refrtsn_inter_glass['refraction_itgp_l_ipd'];?>" id="refraction_itgp_l_ipd" class="form-control">
							<span class="input-group-addon">mm</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-sm-4 text-right">Type of Lens</div>
					<div class="col-sm-2">
						<select name="refraction_itgp_r_tol" id="refraction_itgp_r_tol"  class="w-80px">
							<option value="">Select</option>
							<option value="Single Vision - Distant" <?php if($refrtsn_inter_glass['refraction_itgp_r_tol']=='Single Vision - Distant'){ echo 'selected';} ?>>Single Vision - Distant</option>
							<option value="Single Vision - Near" <?php if($refrtsn_inter_glass['refraction_itgp_r_tol']=='Single Vision - Near'){ echo 'selected';} ?>>Single Vision - Near</option>
							<option value="Bifocal" <?php if($refrtsn_inter_glass['refraction_itgp_r_tol']=='Bifocal'){ echo 'selected';} ?>>Bifocal</option>
							<option value="Progressive" <?php if($refrtsn_inter_glass['refraction_itgp_r_tol']=='Progressive'){ echo 'selected';} ?>>Progressive</option>
							<option value="Bifocal or Progressive" <?php if($refrtsn_inter_glass['refraction_itgp_r_tol']=='Bifocal or Progressive'){ echo 'selected';} ?>>Bifocal or Progressive</option>
							<option value="D Bifocal" <?php if($refrtsn_inter_glass['refraction_itgp_r_tol']=='D Bifocal'){ echo 'selected';} ?>>D Bifocal</option>
							<option value="KT Bifocal" <?php if($refrtsn_inter_glass['refraction_itgp_r_tol']=='KT Bifocal'){ echo 'selected';} ?>>KT Bifocal</option>
						</select>
					</div>
					<div class="col-sm-3 text-right">IPD</div>
					<div class="col-sm-3">
						<div class="input-group">
							<input type="text" name="refraction_itgp_r_ipd" value="<?php echo $refrtsn_inter_glass['refraction_itgp_r_ipd'];?>" id="refraction_itgp_r_ipd" class="form-control">
							<span class="input-group-addon">mm</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-footer d-none inter_glass">
		<div class="row">
			<div class="col-md-6" style="border-right:1px solid lightgray;">
				<div class="row">
					<div class="col-md-4">
						<div class="clearfix">Lens Material</div>
						<select name="refraction_itgp_l_lns_mat"  id="refraction_itgp_l_lns_mat" class="form-control">
							<option value="">Select</option>
							<option value="Mineral" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_mat']=='Mineral'){ echo 'selected';} ?>>Mineral</option>
							<option value="CR 39r" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_mat']=='CR 39r'){ echo 'selected';} ?>>CR 39r</option>
							<option value="Patient Choice" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_mat']=='Patient Choice'){ echo 'selected';} ?>>Patient Choice</option>
							<option value="Polycarbonate" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_mat']=='Polycarbonate'){ echo 'selected';} ?>>Polycarbonate</option>
							<option value="Trivex" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_mat']=='Trivex'){ echo 'selected';} ?>>Trivex</option>
							<option value="Organic" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_mat']=='Organic'){ echo 'selected';} ?>>Organic</option>
							<option value="Evalution" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_mat']=='D Bifocal'){ echo 'selected';} ?>>Evalution</option>
							<option value="MR7" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_mat']=='Evalution'){ echo 'selected';} ?>>MR7</option>
							<option value="MR10" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_mat']=='MR10'){ echo 'selected';} ?>>MR10</option>
							<option value="MR8" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_mat']=='MR8'){ echo 'selected';} ?>>MR8</option>
							<option value="Press on" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_mat']=='Press on'){ echo 'selected';} ?>>Press on</option>
						</select>
					</div>
					<div class="col-md-4">
						<div class="clearfix">Lens Tint</div>
						<select name="refraction_itgp_l_lns_tnt"  id="refraction_itgp_l_lns_tnt" class="form-control">
							<option value="">Select</option>
							<option value="White" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='White'){ echo 'selected';} ?>>White</option>
							<option value="Amber" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Amber'){ echo 'selected';} ?>>Amber</option>
							<option value="B1" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='B1'){ echo 'selected';} ?>>B1</option>
							<option value="B2" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='B2'){ echo 'selected';} ?>>B2</option>
							<option value="B3" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='B3'){ echo 'selected';} ?>>B3</option>
							<option value="Brown" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Brown'){ echo 'selected';} ?>>Brown</option>
							<option value="G-15" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='G-15'){ echo 'selected';} ?>>G-15</option>
							<option value="Gradient Tint" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Gradient Tint'){ echo 'selected';} ?>>Gradient Tint</option>
							<option value="Patient Choice" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Patient Choice'){ echo 'selected';} ?>>Patient Choice</option>
							<option value="Photogrey" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Photogrey'){ echo 'selected';} ?>>Photogrey</option>
							<option value="Photobrown" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Photobrown'){ echo 'selected';} ?>>Photobrown</option>
							<option value="SP1" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='SP1'){ echo 'selected';} ?>>SP1</option>
							<option value="SP11" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='SP11'){ echo 'selected';} ?>>SP11</option>
							<option value="SP2" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='SP2'){ echo 'selected';} ?>>SP2</option>
							<option value="Yellow" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Yellow'){ echo 'selected';} ?>>Yellow</option>
							<option value="Grey" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Grey'){ echo 'selected';} ?>>Grey</option>
							<option value="Clear" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Clear'){ echo 'selected';} ?>>Clear</option>
							<option value="Grey/Brown" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Grey/Brown'){ echo 'selected';} ?>>Grey/Brown</option>
							<option value="Gray C/Brown C" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Gray C/Brown C'){ echo 'selected';} ?>>Gray C/Brown C</option>
							<option value="Grey A" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Grey A'){ echo 'selected';} ?>>Grey A</option>
							<option value="Gray / Brown / Green" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Gray / Brown / Green'){ echo 'selected';} ?>>Gray / Brown / Green</option>
							<option value="PGX / PBX" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='PGX / PBX'){ echo 'selected';} ?>>PGX / PBX</option>
							<option value="Non Tintable" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Non Tintable'){ echo 'selected';} ?>>Non Tintable</option>
							<option value="Pink" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Pink'){ echo 'selected';} ?>>Pink</option>
							<option value="Photo Fusion Grey" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Photo Fusion Grey'){ echo 'selected';} ?>>Photo Fusion Grey</option>
							<option value="Phot Fusion Brown" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Phot Fusion Brown'){ echo 'selected';} ?>>Phot Fusion Brown</option>
							<option value="Polarised Brown" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Polarised Brown'){ echo 'selected';} ?>>Polarised Brown</option>
							<option value="Polarised Grey" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Polarised Grey'){ echo 'selected';} ?>>Polarised Grey</option>
							<option value="Transition" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Transition'){ echo 'selected';} ?>>Transition</option>
							<option value="A1" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='A1'){ echo 'selected';} ?>>A1</option>
							<option value="A2" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='A2'){ echo 'selected';} ?>>A2</option>
							<option value="Colabar C" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Colabar C'){ echo 'selected';} ?>>Colabar C</option>
							<option value="Colabar D" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Colabar D'){ echo 'selected';} ?>>Colabar D</option>
							<option value="Non Clear" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Non Clear'){ echo 'selected';} ?>>Non Clear</option>
							<option value="Polarised" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Polarised'){ echo 'selected';} ?>>Polarised</option>
							<option value="Anti-reflection Coating" <?php if($refrtsn_inter_glass['refraction_itgp_l_lns_tnt']=='Anti-reflection Coating'){ echo 'selected';} ?>>Anti-reflection Coating</option>
						</select>
					</div>
					<div class="col-md-4">
						<div class="clearfix">Frame Material</div>
						<select name="refraction_itgp_l_fm"  id="refraction_itgp_l_fm" class="form-control">
							<option value="">Select</option>
							<option value="Patient Choice" <?php if($refrtsn_inter_glass['refraction_itgp_l_fm']=='Patient Choice'){ echo 'selected';} ?>>Patient Choice</option>
							<option value="Shell" <?php if($refrtsn_inter_glass['refraction_itgp_l_fm']=='Shell'){ echo 'selected';} ?>>Shell</option>
							<option value="Metal" <?php if($refrtsn_inter_glass['refraction_itgp_l_fm']=='Metal'){ echo 'selected';} ?>>Metal</option>
							<option value="Titanium" <?php if($refrtsn_inter_glass['refraction_itgp_l_fm']=='Titanium'){ echo 'selected';} ?>>Titanium</option>
							<option value="Shell(Pediatric Use)" <?php if($refrtsn_inter_glass['refraction_itgp_l_fm']=='Shell(Pediatric Use)'){ echo 'selected';} ?>>Shell(Pediatric Use)</option>
							<option value="Hypo-Allergenic Metal" <?php if($refrtsn_inter_glass['refraction_itgp_l_fm']=='Hypo-Allergenic Metal'){ echo 'selected';} ?>>Hypo-Allergenic Metal</option>
							<option value="Plastic" <?php if($refrtsn_inter_glass['refraction_itgp_l_fm']=='Plastic'){ echo 'selected';} ?>>Plastic</option>
							<option value="Carbon" <?php if($refrtsn_inter_glass['refraction_itgp_l_fm']=='Carbon'){ echo 'selected';} ?>>Carbon</option>
							<option value="Acetate" <?php if($refrtsn_inter_glass['refraction_itgp_l_fm']=='Acetate'){ echo 'selected';} ?>>Acetate</option>
							<option value="Aluminium" <?php if($refrtsn_inter_glass['refraction_itgp_l_fm']=='Aluminium'){ echo 'selected';} ?>>Aluminium</option>
							<option value="Optyl" <?php if($refrtsn_inter_glass['refraction_itgp_l_fm']=='Optyl'){ echo 'selected';} ?>>Optyl</option>
							<option value="Polyamide" <?php if($refrtsn_inter_glass['refraction_itgp_l_fm']=='Polyamide'){ echo 'selected';} ?>>Polyamide</option>
							<option value="Stainless Steel" <?php if($refrtsn_inter_glass['refraction_itgp_l_fm']=='Stainless Steel'){ echo 'selected';} ?>>Stainless Steel</option>
							<option value="TR-90" <?php if($refrtsn_inter_glass['refraction_itgp_l_fm']=='TR-90'){ echo 'selected';} ?>>TR-90</option>
							<option value="Ultem" <?php if($refrtsn_inter_glass['refraction_itgp_l_fm']=='Ultem'){ echo 'selected';} ?>>Ultem</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-4">
						<div class="clearfix">Lens Material</div>
						<select name="refraction_itgp_r_lns_mat" id="refraction_itgp_r_lns_mat" class="form-control">
							<option value="">Select</option>
							<option value="Mineral" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_mat']=='Mineral'){ echo 'selected';} ?>>Mineral</option>
							<option value="CR 39r" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_mat']=='CR 39r'){ echo 'selected';} ?>>CR 39r</option>
							<option value="Patient Choice" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_mat']=='Patient Choice'){ echo 'selected';} ?>>Patient Choice</option>
							<option value="Polycarbonate" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_mat']=='Polycarbonate'){ echo 'selected';} ?>>Polycarbonate</option>
							<option value="Trivex" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_mat']=='Trivex'){ echo 'selected';} ?>>Trivex</option>
							<option value="Organic" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_mat']=='Organic'){ echo 'selected';} ?>>Organic</option>
							<option value="Evalution" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_mat']=='D Bifocal'){ echo 'selected';} ?>>Evalution</option>
							<option value="MR7" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_mat']=='Evalution'){ echo 'selected';} ?>>MR7</option>
							<option value="MR10" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_mat']=='MR10'){ echo 'selected';} ?>>MR10</option>
							<option value="MR8" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_mat']=='MR8'){ echo 'selected';} ?>>MR8</option>
							<option value="Press on" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_mat']=='Press on'){ echo 'selected';} ?>>Press on</option>
						</select>
					</div>
					<div class="col-md-4">
						<div class="clearfix">Lens Tint</div>
						<select name="refraction_itgp_r_lns_tnt" id="refraction_itgp_r_lns_tnt" class="form-control">
							<option value="">Select</option>
							<option value="White" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='White'){ echo 'selected';} ?>>White</option>
							<option value="Amber" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Amber'){ echo 'selected';} ?>>Amber</option>
							<option value="B1" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='B1'){ echo 'selected';} ?>>B1</option>
							<option value="B2" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='B2'){ echo 'selected';} ?>>B2</option>
							<option value="B3" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='B3'){ echo 'selected';} ?>>B3</option>
							<option value="Brown" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Brown'){ echo 'selected';} ?>>Brown</option>
							<option value="G-15" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='G-15'){ echo 'selected';} ?>>G-15</option>
							<option value="Gradient Tint" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Gradient Tint'){ echo 'selected';} ?>>Gradient Tint</option>
							<option value="Patient Choice" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Patient Choice'){ echo 'selected';} ?>>Patient Choice</option>
							<option value="Photogrey" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Photogrey'){ echo 'selected';} ?>>Photogrey</option>
							<option value="Photobrown" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Photobrown'){ echo 'selected';} ?>>Photobrown</option>
							<option value="SP1" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='SP1'){ echo 'selected';} ?>>SP1</option>
							<option value="SP11" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='SP11'){ echo 'selected';} ?>>SP11</option>
							<option value="SP2" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='SP2'){ echo 'selected';} ?>>SP2</option>
							<option value="Yellow" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Yellow'){ echo 'selected';} ?>>Yellow</option>
							<option value="Grey" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Grey'){ echo 'selected';} ?>>Grey</option>
							<option value="Clear" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Clear'){ echo 'selected';} ?>>Clear</option>
							<option value="Grey/Brown" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Grey/Brown'){ echo 'selected';} ?>>Grey/Brown</option>
							<option value="Gray C/Brown C" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Gray C/Brown C'){ echo 'selected';} ?>>Gray C/Brown C</option>
							<option value="Grey A" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Grey A'){ echo 'selected';} ?>>Grey A</option>
							<option value="Gray / Brown / Green" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Gray / Brown / Green'){ echo 'selected';} ?>>Gray / Brown / Green</option>
							<option value="PGX / PBX" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='PGX / PBX'){ echo 'selected';} ?>>PGX / PBX</option>
							<option value="Non Tintable" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Non Tintable'){ echo 'selected';} ?>>Non Tintable</option>
							<option value="Pink" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Pink'){ echo 'selected';} ?>>Pink</option>
							<option value="Photo Fusion Grey" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Photo Fusion Grey'){ echo 'selected';} ?>>Photo Fusion Grey</option>
							<option value="Phot Fusion Brown" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Phot Fusion Brown'){ echo 'selected';} ?>>Phot Fusion Brown</option>
							<option value="Polarised Brown" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Polarised Brown'){ echo 'selected';} ?>>Polarised Brown</option>
							<option value="Polarised Grey" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Polarised Grey'){ echo 'selected';} ?>>Polarised Grey</option>
							<option value="Transition" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Transition'){ echo 'selected';} ?>>Transition</option>
							<option value="A1" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='A1'){ echo 'selected';} ?>>A1</option>
							<option value="A2" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='A2'){ echo 'selected';} ?>>A2</option>
							<option value="Colabar C" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Colabar C'){ echo 'selected';} ?>>Colabar C</option>
							<option value="Colabar D" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Colabar D'){ echo 'selected';} ?>>Colabar D</option>
							<option value="Non Clear" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Non Clear'){ echo 'selected';} ?>>Non Clear</option>
							<option value="Polarised" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Polarised'){ echo 'selected';} ?>>Polarised</option>
							<option value="Anti-reflection Coating" <?php if($refrtsn_inter_glass['refraction_itgp_r_lns_tnt']=='Anti-reflection Coating'){ echo 'selected';} ?>>Anti-reflection Coating</option>
						</select>
					</div>
					<div class="col-md-4">
						<div class="clearfix">Frame Material</div>
						<select  name="refraction_itgp_r_fm" id="refraction_itgp_r_fm" class="form-control">
							<option value="">Select</option>
							<option value="Patient Choice" <?php if($refrtsn_inter_glass['refraction_itgp_r_fm']=='Patient Choice'){ echo 'selected';} ?>>Patient Choice</option>
							<option value="Shell" <?php if($refrtsn_inter_glass['refraction_itgp_r_fm']=='Shell'){ echo 'selected';} ?>>Shell</option>
							<option value="Metal" <?php if($refrtsn_inter_glass['refraction_itgp_r_fm']=='Metal'){ echo 'selected';} ?>>Metal</option>
							<option value="Titanium" <?php if($refrtsn_inter_glass['refraction_itgp_r_fm']=='Titanium'){ echo 'selected';} ?>>Titanium</option>
							<option value="Shell(Pediatric Use)" <?php if($refrtsn_inter_glass['refraction_itgp_r_fm']=='Shell(Pediatric Use)'){ echo 'selected';} ?>>Shell(Pediatric Use)</option>
							<option value="Hypo-Allergenic Metal" <?php if($refrtsn_inter_glass['refraction_itgp_r_fm']=='Hypo-Allergenic Metal'){ echo 'selected';} ?>>Hypo-Allergenic Metal</option>
							<option value="Plastic" <?php if($refrtsn_inter_glass['refraction_itgp_r_fm']=='Plastic'){ echo 'selected';} ?>>Plastic</option>
							<option value="Carbon" <?php if($refrtsn_inter_glass['refraction_itgp_r_fm']=='Carbon'){ echo 'selected';} ?>>Carbon</option>
							<option value="Acetate" <?php if($refrtsn_inter_glass['refraction_itgp_r_fm']=='Acetate'){ echo 'selected';} ?>>Acetate</option>
							<option value="Aluminium" <?php if($refrtsn_inter_glass['refraction_itgp_r_fm']=='Aluminium'){ echo 'selected';} ?>>Aluminium</option>
							<option value="Optyl" <?php if($refrtsn_inter_glass['refraction_itgp_r_fm']=='Optyl'){ echo 'selected';} ?>>Optyl</option>
							<option value="Polyamide" <?php if($refrtsn_inter_glass['refraction_itgp_r_fm']=='Polyamide'){ echo 'selected';} ?>>Polyamide</option>
							<option value="Stainless Steel" <?php if($refrtsn_inter_glass['refraction_itgp_r_fm']=='Stainless Steel'){ echo 'selected';} ?>>Stainless Steel</option>
							<option value="TR-90" <?php if($refrtsn_inter_glass['refraction_itgp_r_fm']=='TR-90'){ echo 'selected';} ?>>TR-90</option>
							<option value="Ultem" <?php if($refrtsn_inter_glass['refraction_itgp_r_fm']=='Ultem'){ echo 'selected';} ?>>Ultem</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="row m-t-5">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-4">Advice:</div>
					<div class="col-md-8">
						<textarea name="refraction_itgp_l_advs" id="refraction_itgp_l_advs" class="form-control"><?php echo $refrtsn_inter_glass['refraction_itgp_l_advs'];?></textarea>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-4">Advice:</div>
					<div class="col-md-8">
						<textarea name="refraction_itgp_r_advs" id="refraction_itgp_r_advs" class="form-control"><?php echo $refrtsn_inter_glass['refraction_itgp_r_advs'];?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<section class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12 text-right btn_edit"><a href="javascript:void(0)" class="btn_fill" onclick="$('.contact_lens').toggle();">Edit</a><hr></div>
			<div class="col-md-2">
				<div class="label_name">CONTACT LENS (CL) <i onclick="refraction_clp_ltr();" title="Copy Left to Right" class="fa fa-arrow-right"></i> </div>
			</div>								
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="16.666%">BC</th>
							<th width="16.666%">DIA</th>
							<th width="16.666%">SPH</th>
							<th width="16.666%">CYL</th>
							<th width="16.666%">AXIS</th>
							<th width="16.666%">ADD</th>
						</tr>									
					</thead>								
					<tbody>
						<tr>
							<td><span class="contact_lens"> <?php echo $refrtsn_cntct_lns['refraction_clp_l_bc'];?></span> <input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_l_bc'];?>" name="refraction_clp_l_bc" id="refraction_clp_l_bc" class="w-50px contact_lens d-none"></td>
							<td><span class="contact_lens"> <?php echo $refrtsn_cntct_lns['refraction_clp_l_dia'];?> </span> <input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_l_dia'];?>" name="refraction_clp_l_dia" id="refraction_clp_l_dia" class="w-50px contact_lens d-none"></td>
							<td><span class="contact_lens"> <?php echo $refrtsn_cntct_lns['refraction_clp_l_sph'];?></span> <input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_l_sph'];?>" name="refraction_clp_l_sph" id="refraction_clp_l_sph" class="w-50px contact_lens d-none"></td>
							<td><span class="contact_lens"> <?php echo $refrtsn_cntct_lns['refraction_clp_l_cyl'];?> </span> <input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_l_cyl'];?>" name="refraction_clp_l_cyl" id="refraction_clp_l_cyl" class="w-50px contact_lens d-none"></td>
							<td><span class="contact_lens"> <?php echo $refrtsn_cntct_lns['refraction_clp_l_axis'];?> </span> <input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_l_axis'];?>" name="refraction_clp_l_axis" id="refraction_clp_l_axis" class="w-50px contact_lens d-none"></td>
							<td><span class="contact_lens"> <?php echo $refrtsn_cntct_lns['refraction_clp_l_add'];?> </span> <input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_l_add'];?>" name="refraction_clp_l_add" id="refraction_clp_l_add" class="w-50px contact_lens d-none"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-2">
				<div class="label_name">CONTACT LENS (CL) <i onclick="refraction_clp_rtl();" title="Copy Right to Left" class="fa fa-arrow-left"></i> </div>
			</div>								
			<div class="col-md-4">
				<table class="table table-bordered">
					<thead class="bg-info">
						<tr>
							<th width="16.666%">BC</th>
							<th width="16.666%">DIA</th>
							<th width="16.666%">SPH</th>
							<th width="16.666%">CYL</th>
							<th width="16.666%">AXIS</th>
							<th width="16.666%">ADD</th>
						</tr>									
					</thead>								
					<tbody>
						<tr>
							<td><span class="contact_lens"><?php echo $refrtsn_cntct_lns['refraction_clp_r_bc'];?></span> <input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_r_bc'];?>" name="refraction_clp_r_bc" id="refraction_clp_r_bc" class="w-50px contact_lens d-none"></td>
							<td><span class="contact_lens"><?php echo $refrtsn_cntct_lns['refraction_clp_r_dia'];?></span> <input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_r_dia'];?>" name="refraction_clp_r_dia" id="refraction_clp_r_dia" class="w-50px contact_lens d-none"></td>
							<td><span class="contact_lens"><?php echo $refrtsn_cntct_lns['refraction_clp_r_sph'];?></span> <input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_r_sph'];?>" name="refraction_clp_r_sph" id="refraction_clp_r_sph" class="w-50px contact_lens d-none"></td>
							<td><span class="contact_lens"><?php echo $refrtsn_cntct_lns['refraction_clp_r_cyl'];?></span> <input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_r_cyl'];?>" name="refraction_clp_r_cyl" id="refraction_clp_r_cyl" class="w-50px contact_lens d-none"></td>
							<td><span class="contact_lens"><?php echo $refrtsn_cntct_lns['refraction_clp_r_axis'];?></span> <input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_r_axis'];?>" name="refraction_clp_r_axis" id="refraction_clp_r_axis" class="w-50px contact_lens d-none"></td>
							<td><span class="contact_lens"><?php echo $refrtsn_cntct_lns['refraction_clp_r_add'];?></span> <input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_r_add'];?>" name="refraction_clp_r_add" id="refraction_clp_r_add" class="w-50px contact_lens d-none"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<hr>
		<div class="row contact_lens">
			<div class="col-md-2"></div>
			<div class="col-md-4">
				<div class="small">Color: <?php echo $refrtsn_cntct_lns['refraction_clp_l_clr'];?> |Type: <?php echo $refrtsn_cntct_lns['refraction_clp_l_tp'];?> </div>
				<div class="small">Advice: <?php echo $refrtsn_cntct_lns['refraction_clp_l_advice'];?> |Revisit Date: <?php if($refrtsn_cntct_lns['refraction_clp_l_rv_date'] !=''){  echo date('d/m/Y',strtotime($refrtsn_cntct_lns['refraction_clp_l_rv_date']));} ?></div>
			</div>
			<div class="col-md-2"></div>
			<div class="col-md-4">
				<div class="small">Color: <?php echo $refrtsn_cntct_lns['refraction_clp_r_clr'];?> |Type: <?php echo $refrtsn_cntct_lns['refraction_clp_r_tp'];?> </div>
				<div class="small">Advice: <?php if($refrtsn_cntct_lns['refraction_clp_r_rv_date'] !=''){ echo $refrtsn_cntct_lns['refraction_clp_r_advice'];} ?> |Revisit Date: <?php echo date('d/m/Y',strtotime($refrtsn_cntct_lns['refraction_clp_r_rv_date']));?></div>
			</div>
		</div>
	</div>
	<div class="panel-footer d-none contact_lens">
		<div class="row">
			<div class="col-md-2">Revisit Date: </div>
			<div class="col-md-2">
				<input type="date" value="<?php echo $refrtsn_cntct_lns['refraction_clp_l_rv_date'];?>" name="refraction_clp_l_rv_date" id="refraction_clp_l_rv_date" class="form-control">
			</div>
			<div class="col-md-2"></div>
			<div class="col-md-2">Revisit Date: </div>
			<div class="col-md-2">
				<input type="date" value="<?php echo $refrtsn_cntct_lns['refraction_clp_r_rv_date'];?>" name="refraction_clp_r_rv_date" id="refraction_clp_r_rv_date" class="form-control">
			</div>
			<div class="col-md-2"></div>
		</div>
	</div>
	<div class="panel-footer d-none contact_lens">
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-4">
				<div class="row">
					<div class="col-sm-6">
						<div class="row">
							<div class="col-xs-4">Color</div>
							<div class="col-xs-8">
								<input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_l_clr'];?>" name="refraction_clp_l_clr" id="refraction_clp_l_clr" class="form-control">
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="row">
							<div class="col-xs-4">Type</div>
							<div class="col-xs-8">
								<!-- <input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_l_tp'];?>" name="refraction_clp_l_tp" id="refraction_clp_l_tp" class="form-control"> -->
								
							<select  name="refraction_clp_l_tp" id="refraction_clp_l_tp" class="form-control">
								<option <?php if($refrtsn_cntct_lns['refraction_clp_l_tp']==''){echo 'selected';} ?> value="">Select</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_l_tp']=='Soft'){echo 'selected';} ?> value="Soft">Soft</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_l_tp']=='RGP'){echo 'selected';} ?> value="RGP">RGP</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_l_tp']=='Disposable'){echo 'selected';} ?> value="Disposable">Disposable</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_l_tp']=='Extended Wear'){echo 'selected';} ?> value="Extended Wear">Extended Wear</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_l_tp']=='Rose-K'){echo 'selected';} ?> value="Rose-K">Rose-K</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_l_tp']=='Ortho-K'){echo 'selected';} ?> value="Ortho-K">Ortho-K</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_l_tp']=='Plastic'){echo 'selected';} ?> value="Plastic">Plastic</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_l_tp']=='Cosmetic'){echo 'selected';} ?> value="Cosmetic">Cosmetic</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_l_tp']=='Bi-focal'){echo 'selected';} ?> value="Bi-focal">Bi-focal</option>
							</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-2"></div>
			<div class="col-md-4">
				<div class="row">
					<div class="col-sm-6">
						<div class="row">
							<div class="col-xs-4">Color</div>
							<div class="col-xs-8">
								<input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_r_clr'];?>" name="refraction_clp_r_clr" id="refraction_clp_r_clr" class="form-control">
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="row">
							<div class="col-xs-4">Type</div>
							<div class="col-xs-8">
								<!-- <input type="text" value="<?php echo $refrtsn_cntct_lns['refraction_clp_r_tp'];?>" name="refraction_clp_r_tp" id="refraction_clp_r_tp" class="form-control"> -->
								<select  name="refraction_clp_r_tp" id="refraction_clp_r_tp" class="form-control">
								<option <?php if($refrtsn_cntct_lns['refraction_clp_r_tp']==''){echo 'selected';} ?> value="">Select</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_r_tp']=='Soft'){echo 'selected';} ?> value="Soft">Soft</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_r_tp']=='RGP'){echo 'selected';} ?> value="RGP">RGP</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_r_tp']=='Disposable'){echo 'selected';} ?> value="Disposable">Disposable</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_r_tp']=='Extended Wear'){echo 'selected';} ?> value="Extended Wear">Extended Wear</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_r_tp']=='Rose-K'){echo 'selected';} ?> value="Rose-K">Rose-K</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_r_tp']=='Ortho-K'){echo 'selected';} ?> value="Ortho-K">Ortho-K</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_r_tp']=='Plastic'){echo 'selected';} ?> value="Plastic">Plastic</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_r_tp']=='Cosmetic'){echo 'selected';} ?> value="Cosmetic">Cosmetic</option>
								<option <?php if($refrtsn_cntct_lns['refraction_clp_r_tp']=='Bi-focal'){echo 'selected';} ?> value="Bi-focal">Bi-focal</option>
							</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-footer d-none contact_lens">
		<div class="row">
			<div class="col-md-2">Advice: </div>
			<div class="col-md-4">
				<textarea name="refraction_clp_l_advice" id="refraction_clp_l_advice" class="form-control"><?php echo $refrtsn_cntct_lns['refraction_clp_l_advice'];?></textarea>
			</div>
			<div class="col-md-2">Advice: </div>
			<div class="col-md-4">
				<textarea name="refraction_clp_r_advice" id="refraction_clp_r_advice" class="form-control"><?php echo $refrtsn_cntct_lns['refraction_clp_r_advice'];?></textarea>
			</div>
		</div>
	</div>
</section>

<section class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-2">
				<div class="label_name">COLOR VISION <i onclick="refraction_col_vis_ltr();" title="Copy Left to Right" class="fa fa-arrow-right"></i></div>
			</div>
			<div class="col-md-4">							
				<textarea name="refraction_col_vis_l" id="refraction_col_vis_l" style="height: 60px" class="form-control"><?php echo $refrtsn_clr_vsn['refraction_col_vis_l'];?></textarea>						
			</div>
			<div class="col-md-2">
				<div class="label_name">COLOR VISION <i onclick="refraction_col_vis_rtl();" title="Copy Right to Left" class="fa fa-arrow-left pointer"></i></div>
			</div>
			<div class="col-md-4">							
				<textarea name="refraction_col_vis_r" id="refraction_col_vis_r" style="height: 60px" class="form-control"><?php echo $refrtsn_clr_vsn['refraction_col_vis_r'];?></textarea>
			</div>	
		</div>
</section>

<section class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12 text-right btn_edit"><a href="javascript:void(0)" class="btn_fill" onclick="$('.con_sen').toggle();">Edit</a><hr></div>
			<div class="col-md-2">
				<div class="label_name">CONTRAST SENSITIVITY <i onclick="refraction_contra_sens_ltr()" title="Copy Left to Right" class="fa fa-arrow-right"></i> </div>
				<small class="mini_outline_btn d-none cons_clr_l" onclick="$('.refraction_contra_sens_l').prop('checked', false); $('.refraction_contra_sens_l').parent().removeClass('active'); $(this).hide();">clear</small>
			</div>								
			<div class="col-md-4">
				<div class="btn-group">
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="2.25">2.25</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="2.10">2.10</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="1.95">1.95</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="1.80">1.80</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="1.65">1.65</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="1.50">1.50</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="1.35">1.35</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="1.20">1.20</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="1.05">1.05</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="0.90">0.90</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="0.75">0.75</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="0.60">0.60</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="0.45">0.45</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="0.30">0.30</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="0.15">0.15</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_l" class="refraction_contra_sens_l" value="0.00">0.00</label>
				</div>							
			</div>				
			<div class="col-md-2">
				<div class="label_name">CONTRAST SENSITIVITY <i onclick="refraction_contra_sens_rtl();" title="Copy Right to Left" class="fa fa-arrow-left"></i> </div>
				<small class="mini_outline_btn d-none cons_clr_r" onclick="$('.refraction_contra_sens_r').prop('checked', false); $('.refraction_contra_sens_r').parent().removeClass('active'); $(this).hide();">clear</small>
			</div>
			<div class="col-md-4">
				<div class="btn-group">
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="2.25">2.25</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="2.10">2.10</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="1.95">1.95</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="1.80">1.80</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="1.65">1.65</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="1.50">1.50</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="1.35">1.35</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="1.20">1.20</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="1.05">1.05</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="0.90">0.90</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="0.75">0.75</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="0.60">0.60</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="0.45">0.45</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="0.30">0.30</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="0.15">0.15</label>
					<label class="btn_radio_small d-none con_sen"> <input type="radio" name="refraction_contra_sens_r" class="refraction_contra_sens_r" value="0.00">0.00</label>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-2">
				<div class="label_name text-center">INTRAOCULAR PRESSURE <i onclick="refraction_intra_press_ltr();" title="Copy Left to Right" class="fa fa-arrow-right"></i></div>	
			</div>
			<div class="col-md-4">
				<div class="row">
					<div class="col-sm-5 slidecontainer small">
						<input type="range" min="0" max="100" class="slider opm19" value="<?php if($refrtsn_intrap['refraction_intra_press_l_mg'] !=''){ echo $refrtsn_intrap['refraction_intra_press_l_mg'];} else{ echo '0';}?>" id="myRange_l">
					</div>
					<div class="col-sm-7">
						<span class="d-inline"><input id="range_l" type="text" value="<?php echo $refrtsn_intrap['refraction_intra_press_l_mg']; ?>" class="w-40px opm19" name="refraction_intra_press_l_mg"> mmHG</span>
						<span class="d-inline"><input type="text" class="datepicker3 opm19" style="width: 65px !important;" name="refraction_intra_press_l_time" value="<?php echo $refrtsn_intrap['refraction_intra_press_l_time']; ?>" id="refraction_intra_press_l_time"></span>
					</div>
				</div>
				<input type="hidden" id="opm19" name="refraction_intra_press_l_update" value="<?php echo $refrtsn_intrap['refraction_intra_l_update']; ?>">
			</div>
			<div class="col-md-2">
				<div class="label_name text-center">INTRAOCULAR PRESSURE <i onclick="refraction_intra_press_rtl();" title="Copy Right to Left" class="fa fa-arrow-left"></i></div>	
			</div>
			<div class="col-md-4">
       			<div class="row">
					<div class="col-sm-5 slidecontainer">
           				<input type="range" min="0" max="100" class="slider opm20" value="<?php if($refrtsn_intrap['refraction_intra_press_r_mg'] !=''){ echo $refrtsn_intrap['refraction_intra_press_r_mg'];} else{ echo '0';}?>" id="myRange_r">
           			</div>
					<div class="col-sm-7">
						<span class="d-inline"><input id="range_r" type="text" value="<?php echo $refrtsn_intrap['refraction_intra_press_r_mg']; ?>" class="w-40px opm20" name="refraction_intra_press_r_mg"> mmHG</span>
						<span class="d-inline"><input type="text" value="<?php echo $refrtsn_intrap['refraction_intra_press_r_time']; ?>" class="datepicker3 opm20" style="width: 65px !important;" name="refraction_intra_press_r_time" id="refraction_intra_press_r_time"></span>								
					</div>
				</div>
				<input type="hidden" id="opm20" name="refraction_intra_press_r_update" value="<?php echo $refrtsn_intrap['refraction_intra_r_update']; ?>">
			</div>
		</div>

		<div class="row m-t-3">
			<div class="col-md-2">
				<label class="small">Comment:</label>
			</div>								
			<div class="col-md-4">
				<textarea style="height: 60px" name="refraction_intra_press_l_comm" id="refraction_intra_press_l_comm" class="form-control opm19"><?php echo $refrtsn_intrap['refraction_intra_press_l_comm']; ?></textarea>
			</div>
			<div class="col-md-2">
				<label class="small">Comment:</label>
			</div>								
			<div class="col-md-4">
				<textarea style="height: 60px" name="refraction_intra_press_r_comm" id="refraction_intra_press_r_comm" class="form-control opm20"><?php echo $refrtsn_intrap['refraction_intra_press_r_comm']; ?></textarea>
			</div>
		</div>
	</div>
</section>


<section class="panel panel-default">
	<div class="panel-body">		
		<div class="row">
			<div class="col-md-2">
				<label class="label_name"> ORTHOPTICS <i onclick="refraction_ortho_ltr();" title="Copy Left to Right" class="fa fa-arrow-right"></i></label>
			</div>								
			<div class="col-md-4">
				<textarea style="height: 60px" name="refraction_ortho_l" id="refraction_ortho_l" class="form-control w-100"><?php echo $refrtsn_orthoptics['refraction_ortho_l']; ?></textarea>						
			</div>
			<div class="col-md-2">
				<label class="label_name"> ORTHOPTICS <i onclick="refraction_ortho_rtl();" title="Copy Right to Left" class="fa fa-arrow-left"></i></label>
			</div>								
			<div class="col-md-4">
				<textarea style="height: 60px" name="refraction_ortho_r" id="refraction_ortho_r" class="form-control w-100"><?php echo $refrtsn_orthoptics['refraction_ortho_r']; ?></textarea>
			</div>						
		</div>
	</div>
</section>

<script>
$(document).on('input', '#myRange_l', function() {
    $('#range_l').val($(this).val());
});
$(document).on('input', '#myRange_r', function() {
    $('#range_r').val($(this).val() );
});
$(document).ready(function(){
    var pres_id = '<?php echo $pres_id;?>';
    if(pres_id =='')
    {
    	$('.datepicker3').val('<?php echo date('h:i A');?>');
		$('.first_row').toggle();
		$('.kero_meter').toggle();
		$('.meter_pgp').toggle();
		$('.auto_ref').toggle();
		$('.con_sen').toggle();
		$('.contact_lens').toggle();
		$('.inter_glass').toggle();
		$('.glasses_pre').toggle();
		$('.pmt').toggle();
		$('.retin').toggle();
		$('.ref_dil').toggle();
		$('.dry_ref').toggle();
		$('.btn_edit').toggle();
	}
	else{
		$('.opm19').change(function() {
			  $('#opm19').val('red');
			});
		$('.opm20').change(function() {
			 $('#opm20').val('red');
			});
	}


	$('.refraction_va_ua_l').click(function(){
		$('.ua_l_txt').show();
		$('.refraction_va_ua_l').parent().removeClass('active');
		$(this).parent().addClass('active');
	});
	$('.refraction_va_ua_r').click(function(){
		$('.ua_r_txt').show();
		$('.refraction_va_ua_r').parent().removeClass('active');
		$(this).parent().addClass('active');
	});

	$('.refraction_va_ua_l_2').click(function(){
		$('.ua_l2_txt').show();
		$('.refraction_va_ua_l_2').parent().removeClass('active');
		$(this).parent().addClass('active');
	});
	$('.refraction_va_ua_r_2').click(function(){
		$('.ua_r2_txt').show();
		$('.refraction_va_ua_r_2').parent().removeClass('active');
		$(this).parent().addClass('active');
	});

    $('.refraction_va_ph_l').click(function(){
    	$('.ph_l_txt').show();
		$('.refraction_va_ph_l').parent().removeClass('active');
		$(this).parent().addClass('active');
	});
	$('.refraction_va_ph_r').click(function(){
		$('.ph_r_txt').show();
		$('.refraction_va_ph_r').parent().removeClass('active');
		$(this).parent().addClass('active');
	});
   $('.refraction_va_gls_l').click(function(){
   	    $('.gls_l_txt').show();
		$('.refraction_va_gls_l').parent().removeClass('active');
		$(this).parent().addClass('active');
	});
    $('.refraction_va_gls_r').click(function(){
    	 $('.gls_r_txt').show();
		$('.refraction_va_gls_r').parent().removeClass('active');
		$(this).parent().addClass('active');
	});
	$('.refraction_va_gls_l_2').click(function(){
		 $('.gls_l2_txt').show();
		$('.refraction_va_gls_l_2').parent().removeClass('active');
		$(this).parent().addClass('active');
	});
	$('.refraction_va_gls_r_2').click(function(){
		 $('.gls_r2_txt').show();
		$('.refraction_va_gls_r_2').parent().removeClass('active');
		$(this).parent().addClass('active');
	});

	$('.refraction_va_cl_l').click(function(){
		 $('.cl_l_txt').show();
		$('.refraction_va_cl_l').parent().removeClass('active');
		$(this).parent().addClass('active');
	});
	$('.refraction_va_cl_r').click(function(){
		 $('.cl_r_txt').show();
		$('.refraction_va_cl_r').parent().removeClass('active');
		$(this).parent().addClass('active');
	});

	$('.refraction_contra_sens_l').click(function(){
		$('.cons_clr_l').show();
		$('.refraction_contra_sens_l').parent().removeClass('active');
		$(this).parent().addClass('active');
	});

	$('.refraction_contra_sens_r').click(function(){
		$('.cons_clr_r').show();
		$('.refraction_contra_sens_r').parent().removeClass('active');
		$(this).parent().addClass('active');
	});

	$("#refraction_va_ua_l_p").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });
    $("#refraction_va_ua_r_p").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });
    $("#refraction_va_ua_l_p_2").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });
    $("#refraction_va_ua_r_p_2").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });
    $("#refraction_va_ph_l_p").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });
    $("#refraction_va_ph_l_ni").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });
    $("#refraction_va_ph_r_p").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });
    $("#refraction_va_ph_r_ni").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });

    $("#refraction_va_gls_l_p").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });
    $("#refraction_va_gls_r_p").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });
    $("#refraction_va_gls_l_p_2").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });
    $("#refraction_va_gls_r_p_2").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });
   $("#refraction_va_cl_l_p").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });
    $("#refraction_va_cl_r_p").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });

    $("#refraction_itgp_ad_check").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });
    $("#refraction_gps_ad_check").on('click',function(){
      $(this).parent().toggleClass('bg-theme');
    });
    
});
    
    function open_modals(modal_tab,title) {
			var $modal = $('#load_add_type_modal_popup');
			$modal.load('<?php echo base_url().'eye/add_eye_prescription/fill_eye_data/' ?>'+modal_tab+'/'+title,
			{
			},
			function(){
			$modal.modal('show');
			});
  	}

  	function open_modals_2(modal_tab) {
			var $modal = $('#load_add_type_modal_popup');
			$modal.load('<?php echo base_url().'eye/add_eye_prescription/fill_eye_data_auto_refraction/' ?>'+modal_tab,
			{
			},
			function(){
			$modal.modal('show');
			});
  	}

  	function refraction_va_ltr()
  	{
  		  $('#refraction_va_ua_r_txt').val($('#refraction_va_ua_l_txt').val());
  		  $('#refraction_va_ua_r_2_txt').val($('#refraction_va_ua_l_2_txt').val());
  		  $('#refraction_va_ph_r_txt').val($('#refraction_va_ph_l_txt').val());
  		  $('#refraction_va_gls_r_txt').val($('#refraction_va_gls_l_txt').val());
  		  $('#refraction_va_gls_r_2_txt').val($('#refraction_va_gls_l_2_txt').val());
  		  $('#refraction_va_cl_r_txt').val($('#refraction_va_cl_l_txt').val());
  		  $('#refraction_va_r_comm').val($('#refraction_va_l_comm').val());

  		  $('#refraction_va_ua_r_pr_s').val($('#refraction_va_ua_l_pr_s').val());
  		  $('#refraction_va_ua_r_pr_i').val($('#refraction_va_ua_l_pr_i').val());
  		  $('#refraction_va_ua_r_pr_n').val($('#refraction_va_ua_l_pr_n').val());
  		  $('#refraction_va_ua_r_pr_t').val($('#refraction_va_ua_l_pr_t').val());

  		  if($('#refraction_va_ua_l_p').prop('checked') == true) {
			   $('#refraction_va_ua_r_p').prop('checked', true).parent().toggleClass('bg-theme');
			   $('.ua_r_txt').show();
			}
		 if($('#refraction_va_ua_l_p_2').prop('checked') == true) {
			   $('#refraction_va_ua_r_p_2').prop('checked', true).parent().toggleClass('bg-theme');
			   $('.ua_r2_txt').show();
			}  
  		 if($('#refraction_va_ph_l_p').prop('checked') == true) {
			   $('#refraction_va_ph_r_p').prop('checked', true).parent().toggleClass('bg-theme');
			   $('.ph_r_txt').show();
			} 
  		 if($('#refraction_va_ph_l_ni').prop('checked') == true) {
			   $('#refraction_va_ph_r_ni').prop('checked', true).parent().toggleClass('bg-theme');
			   $('.gls_r_txt').show();
			} 
		 if($('#refraction_va_gls_l_p').prop('checked') == true) {
		   $('#refraction_va_gls_r_p').prop('checked', true).parent().toggleClass('bg-theme');
		   $('.gls_r2_txt').show();
		} 
		if($('#refraction_va_gls_l_p_2').prop('checked') == true) {
		   $('#refraction_va_gls_r_p_2').prop('checked', true).parent().toggleClass('bg-theme');
		   $('.cl_r_txt').show();
		} 
		if($('#refraction_va_cl_l_p').prop('checked') == true) {
		   $('#refraction_va_cl_r_p').prop('checked', true).parent().toggleClass('bg-theme');
		   $('.cons_clr_r').show();
		}
  		  var radiosl1 = $('input[name=refraction_va_ua_l]');
  		  var radiosr1 = $('input[name=refraction_va_ua_r]');
  		  var vals1=$('input[name=refraction_va_ua_l]:checked').val();  		 
  		  if(radiosl1.is(':checked') === true) 
  		  {
  		  	$('.refraction_va_ua_r').parent().removeClass('active');
		      radiosr1.filter('[value="'+vals1+'"]').prop('checked', true).parent().addClass('active');
		  }

		  var radiosl2 = $('input[name=refraction_va_ua_l_2]');
  		  var radiosr2 = $('input[name=refraction_va_ua_r_2]');
  		  var vals2=$('input[name=refraction_va_ua_l_2]:checked').val();  		 
  		  if(radiosl2.is(':checked') === true) 
  		  {
  		  	$('.refraction_va_ua_r_2').parent().removeClass('active');
		      radiosr2.filter('[value="'+vals2+'"]').prop('checked', true).parent().addClass('active');
		  }
		  var radiosl3 = $('input[name=refraction_va_ph_l]');
  		  var radiosr3 = $('input[name=refraction_va_ph_r]');
  		  var vals3=$('input[name=refraction_va_ph_l]:checked').val();  		 
  		  if(radiosl3.is(':checked') === true) 
  		  {
  		  	$('.refraction_va_ph_r').parent().removeClass('active');
		      radiosr3.filter('[value="'+vals3+'"]').prop('checked', true).parent().addClass('active');
		  }
		  var radiosl4 = $('input[name=refraction_va_gls_l]');
  		  var radiosr4 = $('input[name=refraction_va_gls_r]');
  		  var vals4=$('input[name=refraction_va_gls_l]:checked').val();  		 
  		  if(radiosl4.is(':checked') === true) 
  		  {
  		  	$('.refraction_va_gls_r').parent().removeClass('active');
		      radiosr4.filter('[value="'+vals4+'"]').prop('checked', true).parent().addClass('active');
		  }
		  var radiosl5 = $('input[name=refraction_va_gls_l_2]');
  		  var radiosr5 = $('input[name=refraction_va_gls_r_2]');
  		  var vals5=$('input[name=refraction_va_gls_l_2]:checked').val();  		 
  		  if(radiosl5.is(':checked') === true) 
  		  {
  		  	$('.refraction_va_gls_r_2').parent().removeClass('active');
		      radiosr5.filter('[value="'+vals5+'"]').prop('checked', true).parent().addClass('active');
		  }
		  var radiosl6 = $('input[name=refraction_va_cl_l]');
  		  var radiosr6 = $('input[name=refraction_va_cl_r]');
  		  var vals6=$('input[name=refraction_va_cl_l]:checked').val();  		 
  		  if(radiosl6.is(':checked') === true) 
  		  {
  		  	$('.refraction_va_cl_r').parent().removeClass('active');
		      radiosr6.filter('[value="'+vals6+'"]').prop('checked', true).parent().addClass('active');
		  }
  	}
	function refraction_va_rtl()
	{
		 $('#refraction_va_ua_l_txt').val($('#refraction_va_ua_r_txt').val());
  		  $('#refraction_va_ua_l_2_txt').val($('#refraction_va_ua_r_2_txt').val());
  		  $('#refraction_va_ph_l_txt').val($('#refraction_va_ph_r_txt').val());
  		  $('#refraction_va_gls_l_txt').val($('#refraction_va_gls_r_txt').val());
  		  $('#refraction_va_gls_l_2_txt').val($('#refraction_va_gls_r_2_txt').val());
  		  $('#refraction_va_cl_l_txt').val($('#refraction_va_cl_r_txt').val());
  		  $('#refraction_va_l_comm').val($('#refraction_va_r_comm').val());

  		  $('#refraction_va_ua_l_pr_s').val($('#refraction_va_ua_r_pr_s').val());
  		  $('#refraction_va_ua_l_pr_i').val($('#refraction_va_ua_r_pr_i').val());
  		  $('#refraction_va_ua_l_pr_n').val($('#refraction_va_ua_r_pr_n').val());
  		  $('#refraction_va_ua_l_pr_t').val($('#refraction_va_ua_r_pr_t').val());

  		  if($('#refraction_va_ua_r_p').prop('checked') == true) {
			   $('#refraction_va_ua_l_p').prop('checked', true).parent().toggleClass('bg-theme');
			   $('.ua_l_txt').show();
			}
		 if($('#refraction_va_ua_r_p_2').prop('checked') == true) {
			   $('#refraction_va_ua_l_p_2').prop('checked', true).parent().toggleClass('bg-theme');
			   $('.ua_l2_txt').show();
			}  
  		 if($('#refraction_va_ph_r_p').prop('checked') == true) {
			   $('#refraction_va_ph_l_p').prop('checked', true).parent().toggleClass('bg-theme');
			   $('.ph_l_txt').show();
			} 
  		 if($('#refraction_va_ph_r_ni').prop('checked') == true) {
			   $('#refraction_va_ph_l_ni').prop('checked', true).parent().toggleClass('bg-theme');
			   $('.gls_l_txt').show();
			} 
		 if($('#refraction_va_gls_r_p').prop('checked') == true) {
		   $('#refraction_va_gls_l_p').prop('checked', true).parent().toggleClass('bg-theme');
		   $('.gls_l2_txt').show();
		} 
		if($('#refraction_va_gls_r_p_2').prop('checked') == true) {
		   $('#refraction_va_gls_l_p_2').prop('checked', true).parent().toggleClass('bg-theme');
		   $('.cl_l_txt').show();
		} 
		if($('#refraction_va_cl_r_p').prop('checked') == true) {
		   $('#refraction_va_cl_l_p').prop('checked', true).parent().toggleClass('bg-theme');
		   $('.cons_clr_l').show();
		}
  		  var radiosr1 = $('input[name=refraction_va_ua_r]');
  		  var radiosl1 = $('input[name=refraction_va_ua_l]');
  		  var vals1=$('input[name=refraction_va_ua_r]:checked').val();  		 
  		  if(radiosr1.is(':checked') === true) 
  		  {
  		  	$('.refraction_va_ua_l').parent().removeClass('active');
		      radiosl1.filter('[value="'+vals1+'"]').prop('checked', true).parent().addClass('active');
		  }

		  var radiosr2 = $('input[name=refraction_va_ua_r_2]');
  		  var radiosl2 = $('input[name=refraction_va_ua_l_2]');
  		  var vals2=$('input[name=refraction_va_ua_r_2]:checked').val();  		 
  		  if(radiosr2.is(':checked') === true) 
  		  {
  		  	$('.refraction_va_ua_l_2').parent().removeClass('active');
		      radiosl2.filter('[value="'+vals2+'"]').prop('checked', true).parent().addClass('active');
		  }
		  var radiosr3 = $('input[name=refraction_va_ph_r]');
  		  var radiosl3 = $('input[name=refraction_va_ph_l]');
  		  var vals3=$('input[name=refraction_va_ph_r]:checked').val();  		 
  		  if(radiosr3.is(':checked') === true) 
  		  {
  		  	$('.refraction_va_ph_l').parent().removeClass('active');
		      radiosl3.filter('[value="'+vals3+'"]').prop('checked', true).parent().addClass('active');
		  }
		  var radiosr4 = $('input[name=refraction_va_gls_r]');
  		  var radiosl4 = $('input[name=refraction_va_gls_l]');
  		  var vals4=$('input[name=refraction_va_gls_r]:checked').val();  		 
  		  if(radiosr4.is(':checked') === true) 
  		  {
  		  	$('.refraction_va_gls_l').parent().removeClass('active');
		      radiosl4.filter('[value="'+vals4+'"]').prop('checked', true).parent().addClass('active');
		  }
		  var radiosr5 = $('input[name=refraction_va_gls_r_2]');
  		  var radiosl5 = $('input[name=refraction_va_gls_l_2]');
  		  var vals5=$('input[name=refraction_va_gls_r_2]:checked').val();  		 
  		  if(radiosr5.is(':checked') === true) 
  		  {
  		  	$('.refraction_va_gls_l_2').parent().removeClass('active');
		      radiosl5.filter('[value="'+vals5+'"]').prop('checked', true).parent().addClass('active');
		  }
		  var radiosr6 = $('input[name=refraction_va_cl_r]');
  		  var radiosl6 = $('input[name=refraction_va_cl_l]');
  		  var vals6=$('input[name=refraction_va_cl_r]:checked').val();  		 
  		  if(radiosr6.is(':checked') === true) 
  		  {
  		  	$('.refraction_va_cl_l').parent().removeClass('active');
		      radiosl6.filter('[value="'+vals6+'"]').prop('checked', true).parent().addClass('active');
		  }
	}

  	function refraction_km_ltr()
  	{
		$('#refraction_km_r_kh').val($('#refraction_km_l_kh').val());
		$('#refraction_km_r_kh_a').val($('#refraction_km_l_kh_a').val());
		$('#refraction_km_r_kv').val($('#refraction_km_l_kv').val());
		$('#refraction_km_r_kv_a').val($('#refraction_km_l_kv_a').val());
  	}
    function refraction_km_rtl()
    {
    	$('#refraction_km_l_kh').val($('#refraction_km_r_kh').val());
		$('#refraction_km_l_kh_a').val($('#refraction_km_r_kh_a').val());
		$('#refraction_km_l_kv').val($('#refraction_km_r_kv').val());
		$('#refraction_km_l_kv_a').val($('#refraction_km_r_kv_a').val());
    }

    function refraction_pgp_ltr(){
    	$('#refraction_pgp_r_dt_sph').val($('#refraction_pgp_l_dt_sph').val());
		$('#refraction_pgp_r_dt_cyl').val($('#refraction_pgp_l_dt_cyl').val());
		$('#refraction_pgp_r_dt_axis').val($('#refraction_pgp_l_dt_axis').val());
		$('#refraction_pgp_r_dt_vision').val($('#refraction_pgp_l_dt_vision').val());
		$('#refraction_pgp_r_ad_sph').val($('#refraction_pgp_l_ad_sph').val());
		$('#refraction_pgp_r_ad_cyl').val($('#refraction_pgp_l_ad_cyl').val());
		$('#refraction_pgp_r_ad_axis').val($('#refraction_pgp_l_ad_axis').val());
		$('#refraction_pgp_r_ad_vision').val($('#refraction_pgp_l_ad_vision').val());
		$('#refraction_pgp_r_nr_sph').val($('#refraction_pgp_l_nr_sph').val());
		$('#refraction_pgp_r_nr_cyl').val($('#refraction_pgp_l_nr_cyl').val());
		$('#refraction_pgp_r_nr_axis').val($('#refraction_pgp_l_nr_axis').val());
		$('#refraction_pgp_r_nr_vision').val($('#refraction_pgp_l_nr_vision').val());
		$('#refraction_pgp_r_lens').val($('#refraction_pgp_l_lens').val());
    }

     function refraction_pgp_rtl(){
    	$('#refraction_pgp_l_dt_sph').val($('#refraction_pgp_r_dt_sph').val());
		$('#refraction_pgp_l_dt_cyl').val($('#refraction_pgp_r_dt_cyl').val());
		$('#refraction_pgp_l_dt_axis').val($('#refraction_pgp_r_dt_axis').val());
		$('#refraction_pgp_l_dt_vision').val($('#refraction_pgp_r_dt_vision').val());
		$('#refraction_pgp_l_ad_sph').val($('#refraction_pgp_r_ad_sph').val());
		$('#refraction_pgp_l_ad_cyl').val($('#refraction_pgp_r_ad_cyl').val());
		$('#refraction_pgp_l_ad_axis').val($('#refraction_pgp_r_ad_axis').val());
		$('#refraction_pgp_l_ad_vision').val($('#refraction_pgp_r_ad_vision').val());
		$('#refraction_pgp_l_nr_sph').val($('#refraction_pgp_r_nr_sph').val());
		$('#refraction_pgp_l_nr_cyl').val($('#refraction_pgp_r_nr_cyl').val());
		$('#refraction_pgp_l_nr_axis').val($('#refraction_pgp_r_nr_axis').val());
		$('#refraction_pgp_l_nr_vision').val($('#refraction_pgp_r_nr_vision').val());
		$('#refraction_pgp_l_lens').val($('#refraction_pgp_r_lens').val());
    }

    function refraction_ar_ltr()
    {
    	$('#refraction_ar_r_dry_sph').val($('#refraction_ar_l_dry_sph').val());
		$('#refraction_ar_r_dry_cyl').val($('#refraction_ar_l_dry_cyl').val());
		$('#refraction_ar_r_dry_axis').val($('#refraction_ar_l_dry_axis').val());
		$('#refraction_ar_r_dd_sph').val($('#refraction_ar_l_dd_sph').val());
		$('#refraction_ar_r_dd_cyl').val($('#refraction_ar_l_dd_cyl').val());
		$('#refraction_ar_r_dd_axis').val($('#refraction_ar_l_dd_axis').val());
		$('#refraction_ar_r_b1_sph').val($('#refraction_ar_l_b1_sph').val());
		$('#refraction_ar_r_b1_cyl').val($('#refraction_ar_l_b1_cyl').val());
		$('#refraction_ar_r_b1_axis').val($('#refraction_ar_l_b1_axis').val());
		$('#refraction_ar_r_b2_sph').val($('#refraction_ar_l_b2_sph').val());
		$('#refraction_ar_r_b2_cyl').val($('#refraction_ar_l_b2_cyl').val());
		$('#refraction_ar_r_b2_axis').val($('#refraction_ar_l_b2_axis').val());
    }
    function refraction_ar_rtl()
    {
    	$('#refraction_ar_l_dry_sph').val($('#refraction_ar_r_dry_sph').val());
		$('#refraction_ar_l_dry_cyl').val($('#refraction_ar_r_dry_cyl').val());
		$('#refraction_ar_l_dry_axis').val($('#refraction_ar_r_dry_axis').val());
		$('#refraction_ar_l_dd_sph').val($('#refraction_ar_r_dd_sph').val());
		$('#refraction_ar_l_dd_cyl').val($('#refraction_ar_r_dd_cyl').val());
		$('#refraction_ar_l_dd_axis').val($('#refraction_ar_r_dd_axis').val());
		$('#refraction_ar_l_b1_sph').val($('#refraction_ar_r_b1_sph').val());
		$('#refraction_ar_l_b1_cyl').val($('#refraction_ar_r_b1_cyl').val());
		$('#refraction_ar_l_b1_axis').val($('#refraction_ar_r_b1_axis').val());
		$('#refraction_ar_l_b2_sph').val($('#refraction_ar_r_b2_sph').val());
		$('#refraction_ar_l_b2_cyl').val($('#refraction_ar_r_b2_cyl').val());
		$('#refraction_ar_l_b2_axis').val($('#refraction_ar_r_b2_axis').val());
    }
    function refraction_dry_ref_ltr()
    {
    	$('#refraction_dry_ref_r_dt_sph').val($('#refraction_dry_ref_l_dt_sph').val());
		$('#refraction_dry_ref_r_dt_cyl').val($('#refraction_dry_ref_l_dt_cyl').val());
		$('#refraction_dry_ref_r_dt_axis').val($('#refraction_dry_ref_l_dt_axis').val());
		$('#refraction_dry_ref_r_dt_vision').val($('#refraction_dry_ref_l_dt_vision').val());
		$('#refraction_dry_ref_r_ad_sph').val($('#refraction_dry_ref_l_ad_sph').val());
		$('#refraction_dry_ref_r_ad_cyl').val($('#refraction_dry_ref_l_ad_cyl').val());
		$('#refraction_dry_ref_r_ad_axis').val($('#refraction_dry_ref_l_ad_axis').val());
		$('#refraction_dry_ref_r_ad_vision').val($('#refraction_dry_ref_l_ad_vision').val());
		$('#refraction_dry_ref_r_nr_sph').val($('#refraction_dry_ref_l_nr_sph').val());
		$('#refraction_dry_ref_r_nr_cyl').val($('#refraction_dry_ref_l_nr_cyl').val());
		$('#refraction_dry_ref_r_nr_axis').val($('#refraction_dry_ref_l_nr_axis').val());
		$('#refraction_dry_ref_r_nr_vision').val($('#refraction_dry_ref_l_nr_vision').val());
		$('#refraction_dry_ref_r_comm').val($('#refraction_dry_ref_l_comm').val());
    }


     function refraction_dry_ref_rtl()
    {
    	$('#refraction_dry_ref_l_dt_sph').val($('#refraction_dry_ref_r_dt_sph').val());
		$('#refraction_dry_ref_l_dt_cyl').val($('#refraction_dry_ref_r_dt_cyl').val());
		$('#refraction_dry_ref_l_dt_axis').val($('#refraction_dry_ref_r_dt_axis').val());
		$('#refraction_dry_ref_l_dt_vision').val($('#refraction_dry_ref_r_dt_vision').val());
		$('#refraction_dry_ref_l_ad_sph').val($('#refraction_dry_ref_r_ad_sph').val());
		$('#refraction_dry_ref_l_ad_cyl').val($('#refraction_dry_ref_r_ad_cyl').val());
		$('#refraction_dry_ref_l_ad_axis').val($('#refraction_dry_ref_r_ad_axis').val());
		$('#refraction_dry_ref_l_ad_vision').val($('#refraction_dry_ref_r_ad_vision').val());
		$('#refraction_dry_ref_l_nr_sph').val($('#refraction_dry_ref_r_nr_sph').val());
		$('#refraction_dry_ref_l_nr_cyl').val($('#refraction_dry_ref_r_nr_cyl').val());
		$('#refraction_dry_ref_l_nr_axis').val($('#refraction_dry_ref_r_nr_axis').val());
		$('#refraction_dry_ref_l_nr_vision').val($('#refraction_dry_ref_r_nr_vision').val());
		$('#refraction_dry_ref_l_comm').val($('#refraction_dry_ref_r_comm').val());
    }
   function refraction_ref_dtd_ltr()
   {
   		$('#refraction_ref_dtd_r_dt_sph').val($('#refraction_ref_dtd_l_dt_sph').val());
		$('#refraction_ref_dtd_r_dt_cyl').val($('#refraction_ref_dtd_l_dt_cyl').val());
		$('#refraction_ref_dtd_r_dt_axis').val($('#refraction_ref_dtd_l_dt_axis').val());
		$('#refraction_ref_dtd_r_dt_vision').val($('#refraction_ref_dtd_l_dt_vision').val());
		$('#refraction_ref_dtd_r_ad_sph').val($('#refraction_ref_dtd_l_ad_sph').val());
		$('#refraction_ref_dtd_r_ad_cyl').val($('#refraction_ref_dtd_l_ad_cyl').val());
		$('#refraction_ref_dtd_r_ad_axis').val($('#refraction_ref_dtd_l_ad_axis').val());
		$('#refraction_ref_dtd_r_ad_vision').val($('#refraction_ref_dtd_l_ad_vision').val());
		$('#refraction_ref_dtd_r_nr_sph').val($('#refraction_ref_dtd_l_nr_sph').val());
		$('#refraction_ref_dtd_r_nr_cyl').val($('#refraction_ref_dtd_l_nr_cyl').val());
		$('#refraction_ref_dtd_r_nr_axis').val($('#refraction_ref_dtd_l_nr_axis').val());
		$('#refraction_ref_dtd_r_nr_vision').val($('#refraction_ref_dtd_l_nr_vision').val());
		$('#refraction_ref_dtd_r_du').val($('#refraction_ref_dtd_l_du').val());
		$('#refraction_ref_dtd_r_comm').val($('#refraction_ref_dtd_l_comm').val());
   }
   function refraction_ref_dtd_rtl()
   {
   	   	$('#refraction_ref_dtd_l_dt_sph').val($('#refraction_ref_dtd_r_dt_sph').val());
		$('#refraction_ref_dtd_l_dt_cyl').val($('#refraction_ref_dtd_r_dt_cyl').val());
		$('#refraction_ref_dtd_l_dt_axis').val($('#refraction_ref_dtd_r_dt_axis').val());
		$('#refraction_ref_dtd_l_dt_vision').val($('#refraction_ref_dtd_r_dt_vision').val());
		$('#refraction_ref_dtd_l_ad_sph').val($('#refraction_ref_dtd_r_ad_sph').val());
		$('#refraction_ref_dtd_l_ad_cyl').val($('#refraction_ref_dtd_r_ad_cyl').val());
		$('#refraction_ref_dtd_l_ad_axis').val($('#refraction_ref_dtd_r_ad_axis').val());
		$('#refraction_ref_dtd_l_ad_vision').val($('#refraction_ref_dtd_r_ad_vision').val());
		$('#refraction_ref_dtd_l_nr_sph').val($('#refraction_ref_dtd_r_nr_sph').val());
		$('#refraction_ref_dtd_l_nr_cyl').val($('#refraction_ref_dtd_r_nr_cyl').val());
		$('#refraction_ref_dtd_l_nr_axis').val($('#refraction_ref_dtd_r_nr_axis').val());
		$('#refraction_ref_dtd_l_nr_vision').val($('#refraction_ref_dtd_r_nr_vision').val());
		$('#refraction_ref_dtd_l_du').val($('#refraction_ref_dtd_r_du').val());
		$('#refraction_ref_dtd_l_comm').val($('#refraction_ref_dtd_r_comm').val());
   }

   function refraction_rtnp_ltr()
   {
   	$('#refraction_rtnp_r_t').val($('#refraction_rtnp_l_t').val());
	$('#refraction_rtnp_r_l').val($('#refraction_rtnp_l_l').val());
	$('#refraction_rtnp_r_r').val($('#refraction_rtnp_l_r').val());
	$('#refraction_rtnp_r_b').val($('#refraction_rtnp_l_b').val());
	$('#refraction_rtnp_r_va').val($('#refraction_rtnp_l_va').val());
	$('#refraction_rtnp_r_ha').val($('#refraction_rtnp_l_ha').val());
	$('#refraction_rtnp_r_at_dis').val($('#refraction_rtnp_l_at_dis').val());
	$('#refraction_rtnp_r_du').val($('#refraction_rtnp_l_du').val());
	$('#refraction_rtnp_r_comm').val($('#refraction_rtnp_l_comm').val());
   }
    function refraction_rtnp_rtl()
   {
   	$('#refraction_rtnp_l_t').val($('#refraction_rtnp_r_t').val());
	$('#refraction_rtnp_l_l').val($('#refraction_rtnp_r_l').val());
	$('#refraction_rtnp_l_r').val($('#refraction_rtnp_r_r').val());
	$('#refraction_rtnp_l_b').val($('#refraction_rtnp_r_b').val());
	$('#refraction_rtnp_l_va').val($('#refraction_rtnp_r_va').val());
	$('#refraction_rtnp_l_ha').val($('#refraction_rtnp_r_ha').val());
	$('#refraction_rtnp_l_at_dis').val($('#refraction_rtnp_r_at_dis').val());
	$('#refraction_rtnp_l_du').val($('#refraction_rtnp_r_du').val());
	$('#refraction_rtnp_l_comm').val($('#refraction_rtnp_r_comm').val());
   }
   function refraction_pmt_ltr(){
   	$('#refraction_pmt_r_dt_sph').val($('#refraction_pmt_l_dt_sph').val());
	$('#refraction_pmt_r_dt_cyl').val($('#refraction_pmt_l_dt_cyl').val());
	$('#refraction_pmt_r_dt_axis').val($('#refraction_pmt_l_dt_axis').val());
	$('#refraction_pmt_r_dt_vision').val($('#refraction_pmt_l_dt_vision').val());
	$('#refraction_pmt_r_ad_sph').val($('#refraction_pmt_l_ad_sph').val());
	$('#refraction_pmt_r_ad_cyl').val($('#refraction_pmt_l_ad_cyl').val());
	$('#refraction_pmt_r_ad_axis').val($('#refraction_pmt_l_ad_axis').val());
	$('#refraction_pmt_r_ad_vision').val($('#refraction_pmt_l_ad_vision').val());
	$('#refraction_pmt_r_nr_sph').val($('#refraction_pmt_l_nr_sph').val());
	$('#refraction_pmt_r_nr_cyl').val($('#refraction_pmt_l_nr_cyl').val());
	$('#refraction_pmt_r_nr_axis').val($('#refraction_pmt_l_nr_axis').val());
	$('#refraction_pmt_r_nr_vision').val($('#refraction_pmt_l_nr_vision').val());
   }

    function refraction_pmt_rtl()
    {
   	$('#refraction_pmt_l_dt_sph').val($('#refraction_pmt_r_dt_sph').val());
	$('#refraction_pmt_l_dt_cyl').val($('#refraction_pmt_r_dt_cyl').val());
	$('#refraction_pmt_l_dt_axis').val($('#refraction_pmt_r_dt_axis').val());
	$('#refraction_pmt_l_dt_vision').val($('#refraction_pmt_r_dt_vision').val());
	$('#refraction_pmt_l_ad_sph').val($('#refraction_pmt_r_ad_sph').val());
	$('#refraction_pmt_l_ad_cyl').val($('#refraction_pmt_r_ad_cyl').val());
	$('#refraction_pmt_l_ad_axis').val($('#refraction_pmt_r_ad_axis').val());
	$('#refraction_pmt_l_ad_vision').val($('#refraction_pmt_r_ad_vision').val());
	$('#refraction_pmt_l_nr_sph').val($('#refraction_pmt_r_nr_sph').val());
	$('#refraction_pmt_l_nr_cyl').val($('#refraction_pmt_r_nr_cyl').val());
	$('#refraction_pmt_l_nr_axis').val($('#refraction_pmt_r_nr_axis').val());
	$('#refraction_pmt_l_nr_vision').val($('#refraction_pmt_r_nr_vision').val());
   }
   function refraction_gps_ltr()
   {
   	$('#refraction_gps_r_dt_sph').val($('#refraction_gps_l_dt_sph').val());
	$('#refraction_gps_r_dt_cyl').val($('#refraction_gps_l_dt_cyl').val());
	$('#refraction_gps_r_dt_axis').val($('#refraction_gps_l_dt_axis').val());
	$('#refraction_gps_r_dt_vision').val($('#refraction_gps_l_dt_vision').val());
	$('#refraction_gps_r_ad_sph').val($('#refraction_gps_l_ad_sph').val());
	$('#refraction_gps_r_ad_cyl').val($('#refraction_gps_l_ad_cyl').val());
	$('#refraction_gps_r_ad_axis').val($('#refraction_gps_l_ad_axis').val());
	$('#refraction_gps_r_ad_vision').val($('#refraction_gps_l_ad_vision').val());
	$('#refraction_gps_r_nr_sph').val($('#refraction_gps_l_nr_sph').val());
	$('#refraction_gps_r_nr_cyl').val($('#refraction_gps_l_nr_cyl').val());
	$('#refraction_gps_r_nr_axis').val($('#refraction_gps_l_nr_axis').val());
	$('#refraction_gps_r_nr_vision').val($('#refraction_gps_l_nr_vision').val());
	$('#refraction_gps_r_tol').val($('#refraction_gps_l_tol').val());
	$('#refraction_gps_r_ipd').val($('#refraction_gps_l_ipd').val());
	$('#refraction_gps_r_lns_mat').val($('#refraction_gps_l_lns_mat').val());
	$('#refraction_gps_r_lns_tnt').val($('#refraction_gps_l_lns_tnt').val());
	$('#refraction_gps_r_fm').val($('#refraction_gps_l_fm').val());
	$('#refraction_gps_r_advs').val($('#refraction_gps_l_advs').val());
   }

   function refraction_gps_rtl()
   {
   	$('#refraction_gps_l_dt_sph').val($('#refraction_gps_r_dt_sph').val());
	$('#refraction_gps_l_dt_cyl').val($('#refraction_gps_r_dt_cyl').val());
	$('#refraction_gps_l_dt_axis').val($('#refraction_gps_r_dt_axis').val());
	$('#refraction_gps_l_dt_vision').val($('#refraction_gps_r_dt_vision').val());
	$('#refraction_gps_l_ad_sph').val($('#refraction_gps_r_ad_sph').val());
	$('#refraction_gps_l_ad_cyl').val($('#refraction_gps_r_ad_cyl').val());
	$('#refraction_gps_l_ad_axis').val($('#refraction_gps_r_ad_axis').val());
	$('#refraction_gps_l_ad_vision').val($('#refraction_gps_r_ad_vision').val());
	$('#refraction_gps_l_nr_sph').val($('#refraction_gps_r_nr_sph').val());
	$('#refraction_gps_l_nr_cyl').val($('#refraction_gps_r_nr_cyl').val());
	$('#refraction_gps_l_nr_axis').val($('#refraction_gps_r_nr_axis').val());
	$('#refraction_gps_l_nr_vision').val($('#refraction_gps_r_nr_vision').val());
	$('#refraction_gps_l_tol').val($('#refraction_gps_r_tol').val());
	$('#refraction_gps_l_ipd').val($('#refraction_gps_r_ipd').val());
	$('#refraction_gps_l_lns_mat').val($('#refraction_gps_r_lns_mat').val());
	$('#refraction_gps_l_lns_tnt').val($('#refraction_gps_r_lns_tnt').val());
	$('#refraction_gps_l_fm').val($('#refraction_gps_r_fm').val());
	$('#refraction_gps_l_advs').val($('#refraction_gps_r_advs').val());
   }

   function refraction_itgp_ltr()
   {
   	$('#refraction_itgp_r_dt_sph').val($('#refraction_itgp_l_dt_sph').val());
	$('#refraction_itgp_r_dt_cyl').val($('#refraction_itgp_l_dt_cyl').val());
	$('#refraction_itgp_r_dt_axis').val($('#refraction_itgp_l_dt_axis').val());
	$('#refraction_itgp_r_dt_vision').val($('#refraction_itgp_l_dt_vision').val());
	$('#refraction_itgp_r_ad_sph').val($('#refraction_itgp_l_ad_sph').val());
	$('#refraction_itgp_r_ad_cyl').val($('#refraction_itgp_l_ad_cyl').val());
	$('#refraction_itgp_r_ad_axis').val($('#refraction_itgp_l_ad_axis').val());
	$('#refraction_itgp_r_ad_vision').val($('#refraction_itgp_l_ad_vision').val());
	$('#refraction_itgp_r_nr_sph').val($('#refraction_itgp_l_nr_sph').val());
	$('#refraction_itgp_r_nr_cyl').val($('#refraction_itgp_l_nr_cyl').val());
	$('#refraction_itgp_r_nr_axis').val($('#refraction_itgp_l_nr_axis').val());
	$('#refraction_itgp_r_nr_vision').val($('#refraction_itgp_l_nr_vision').val());
	$('#refraction_itgp_r_tol').val($('#refraction_itgp_l_tol').val());
	$('#refraction_itgp_r_ipd').val($('#refraction_itgp_l_ipd').val());
	$('#refraction_itgp_r_lns_mat').val($('#refraction_itgp_l_lns_mat').val());
	$('#refraction_itgp_r_lns_tnt').val($('#refraction_itgp_l_lns_tnt').val());
	$('#refraction_itgp_r_fm').val($('#refraction_itgp_l_fm').val());
	$('#refraction_itgp_r_advs').val($('#refraction_itgp_l_advs').val());
   }

   function refraction_itgp_rtl()
   {
   	$('#refraction_itgp_l_dt_sph').val($('#refraction_itgp_r_dt_sph').val());
	$('#refraction_itgp_l_dt_cyl').val($('#refraction_itgp_r_dt_cyl').val());
	$('#refraction_itgp_l_dt_axis').val($('#refraction_itgp_r_dt_axis').val());
	$('#refraction_itgp_l_dt_vision').val($('#refraction_itgp_r_dt_vision').val());
	$('#refraction_itgp_l_ad_sph').val($('#refraction_itgp_r_ad_sph').val());
	$('#refraction_itgp_l_ad_cyl').val($('#refraction_itgp_r_ad_cyl').val());
	$('#refraction_itgp_l_ad_axis').val($('#refraction_itgp_r_ad_axis').val());
	$('#refraction_itgp_l_ad_vision').val($('#refraction_itgp_r_ad_vision').val());
	$('#refraction_itgp_l_nr_sph').val($('#refraction_itgp_r_nr_sph').val());
	$('#refraction_itgp_l_nr_cyl').val($('#refraction_itgp_r_nr_cyl').val());
	$('#refraction_itgp_l_nr_axis').val($('#refraction_itgp_r_nr_axis').val());
	$('#refraction_itgp_l_nr_vision').val($('#refraction_itgp_r_nr_vision').val());
	$('#refraction_itgp_l_tol').val($('#refraction_itgp_r_tol').val());
	$('#refraction_itgp_l_ipd').val($('#refraction_itgp_r_ipd').val());
	$('#refraction_itgp_l_lns_mat').val($('#refraction_itgp_r_lns_mat').val());
	$('#refraction_itgp_l_lns_tnt').val($('#refraction_itgp_r_lns_tnt').val());
	$('#refraction_itgp_l_fm').val($('#refraction_itgp_r_fm').val());
	$('#refraction_itgp_l_advs').val($('#refraction_itgp_r_advs').val());
   }

   function refraction_clp_ltr()
   {
   	 $('#refraction_clp_r_bc').val($('#refraction_clp_l_bc').val());
	 $('#refraction_clp_r_dia').val($('#refraction_clp_l_dia').val());
	 $('#refraction_clp_r_sph').val($('#refraction_clp_l_sph').val());
	 $('#refraction_clp_r_cyl').val($('#refraction_clp_l_cyl').val());
	 $('#refraction_clp_r_axis').val($('#refraction_clp_l_axis').val());
	 $('#refraction_clp_r_add').val($('#refraction_clp_l_add').val());
	 $('#refraction_clp_r_rv_date').val($('#refraction_clp_l_rv_date').val());
	 $('#refraction_clp_r_clr').val($('#refraction_clp_l_clr').val());
	 $('#refraction_clp_r_tp').val($('#refraction_clp_l_tp').val());
	 $('#refraction_clp_r_advice').val($('#refraction_clp_l_advice').val());
   }

   function refraction_clp_rtl()
   {
   	 $('#refraction_clp_l_bc').val($('#refraction_clp_r_bc').val());
	 $('#refraction_clp_l_dia').val($('#refraction_clp_r_dia').val());
	 $('#refraction_clp_l_sph').val($('#refraction_clp_r_sph').val());
	 $('#refraction_clp_l_cyl').val($('#refraction_clp_r_cyl').val());
	 $('#refraction_clp_l_axis').val($('#refraction_clp_r_axis').val());
	 $('#refraction_clp_l_add').val($('#refraction_clp_r_add').val());
	 $('#refraction_clp_l_rv_date').val($('#refraction_clp_r_rv_date').val());
	 $('#refraction_clp_l_clr').val($('#refraction_clp_r_clr').val());
	 $('#refraction_clp_l_tp').val($('#refraction_clp_r_tp').val());
	 $('#refraction_clp_l_advice').val($('#refraction_clp_r_advice').val());
   }

   	function refraction_col_vis_ltr(){
  		$('#refraction_col_vis_r').val($('#refraction_col_vis_l').val());
  	}
  	function refraction_col_vis_rtl(){
  		$('#refraction_col_vis_l').val($('#refraction_col_vis_r').val());
  	}

  	function refraction_contra_sens_ltr()
  	{
  		  var radiosl = $('input[name=refraction_contra_sens_l]');
  		  var radiosr = $('input[name=refraction_contra_sens_r]');
  		  var vals=$('input[name=refraction_contra_sens_l]:checked').val();
  		  if(radiosl.is(':checked') === true) 
  		  {
  		  	$('.refraction_contra_sens_r').parent().removeClass('active');
		      radiosr.filter('[value="'+vals+'"]').prop('checked', true).parent().addClass('active');
		  }
  	}
  	function refraction_contra_sens_rtl()
  	{
  		  var radiosr = $('input[name=refraction_contra_sens_r]');
  		  var radiosl = $('input[name=refraction_contra_sens_l]');
  		  var vals=$('input[name=refraction_contra_sens_r]:checked').val();
  		  if(radiosr.is(':checked') === true) 
  		  {
  		  	$('.refraction_contra_sens_l').parent().removeClass('active');
		      radiosl.filter('[value="'+vals+'"]').prop('checked', true).parent().addClass('active');
		  }
  	}  	

  	function refraction_intra_press_ltr(){
  		$('#myRange_r').val($('#myRange_l').val());
  		$('#range_r').val($('#range_l').val());
  		$('#refraction_intra_press_r_comm').val($('#refraction_intra_press_l_comm').val());
  		$('#refraction_intra_press_r_time').val($('#refraction_intra_press_l_time').val());
  	}
    function refraction_intra_press_rtl(){
    	$('#myRange_l').val($('#myRange_r').val());
 		$('#range_l').val($('#range_r').val());
 		$('#refraction_intra_press_l_comm').val($('#refraction_intra_press_r_comm').val());
 		$('#refraction_intra_press_l_time').val($('#refraction_intra_press_r_time').val());
    }

  	function refraction_ortho_ltr(){
  		$('#refraction_ortho_r').val($('#refraction_ortho_l').val());
  	}
    function refraction_ortho_rtl(){
    	$('#refraction_ortho_l').val($('#refraction_ortho_r').val());
    }

   $(document).ready(function(){
	var refraction_va_ua_l_p = '<?php echo $refrtsn_vl_act['refraction_va_ua_l_p'];?>';
	var refraction_va_ua_r_p = '<?php echo $refrtsn_vl_act['refraction_va_ua_r_p'];?>';
	var refraction_va_ua_l_p_2 = '<?php echo $refrtsn_vl_act['refraction_va_ua_l_p_2'];?>';
	var refraction_va_ua_r_p_2 = '<?php echo $refrtsn_vl_act['refraction_va_ua_r_p_2'];?>';
	var refraction_va_ph_l_p = '<?php echo $refrtsn_vl_act['refraction_va_ph_l_p'];?>';
	var refraction_va_ph_l_ni = '<?php echo $refrtsn_vl_act['refraction_va_ph_l_ni'];?>';
	var refraction_va_ph_r_p = '<?php echo $refrtsn_vl_act['refraction_va_ph_r_p'];?>';
	var refraction_va_ph_r_ni = '<?php echo $refrtsn_vl_act['refraction_va_ph_r_ni'];?>';
	var refraction_va_gls_l_p = '<?php echo $refrtsn_vl_act['refraction_va_gls_l_p'];?>';
	var refraction_va_gls_r_p = '<?php echo $refrtsn_vl_act['refraction_va_gls_r_p'];?>';
	var refraction_va_gls_l_p_2 = '<?php echo $refrtsn_vl_act['refraction_va_gls_l_p_2'];?>';
	var refraction_va_gls_r_p_2 = '<?php echo $refrtsn_vl_act['refraction_va_gls_r_p_2'];?>';
	var refraction_va_cl_l_p = '<?php echo $refrtsn_vl_act['refraction_va_cl_l_p'];?>';
	var refraction_va_cl_r_p = '<?php echo $refrtsn_vl_act['refraction_va_cl_r_p'];?>';
	var refraction_itgp_ad_check = '<?php echo $refrtsn_inter_glass['refraction_itgp_ad_check'];?>';
	var refraction_gps_ad_check = '<?php echo $refrtsn_glassp['refraction_gps_ad_check'];?>';
	var refraction_va_ua_l ='<?php echo $refrtsn_vl_act['refraction_va_ua_l'];?>';
	var refraction_va_ua_r ='<?php echo $refrtsn_vl_act['refraction_va_ua_r'];?>';
	var refraction_va_ua_l_2 ='<?php echo $refrtsn_vl_act['refraction_va_ua_l_2'];?>';
	var refraction_va_ua_r_2 ='<?php echo $refrtsn_vl_act['refraction_va_ua_r_2'];?>';
	var refraction_va_ph_l ='<?php echo $refrtsn_vl_act['refraction_va_ph_l'];?>';
	var refraction_va_ph_r ='<?php echo $refrtsn_vl_act['refraction_va_ph_r'];?>';
	var refraction_va_gls_l ='<?php echo $refrtsn_vl_act['refraction_va_gls_l'];?>';
	var refraction_va_gls_r ='<?php echo $refrtsn_vl_act['refraction_va_gls_r'];?>';
	var refraction_va_gls_l_2 ='<?php echo $refrtsn_vl_act['refraction_va_gls_l_2'];?>';
	var refraction_va_gls_r_2 ='<?php echo $refrtsn_vl_act['refraction_va_gls_r_2'];?>';
	var refraction_va_cl_l ='<?php echo $refrtsn_vl_act['refraction_va_cl_l'];?>';
	var refraction_va_cl_r ='<?php echo $refrtsn_vl_act['refraction_va_cl_r'];?>';
	var refraction_contra_sens_l ='<?php echo $refrtsn_const_sen['refraction_contra_sens_l'];?>';
	var refraction_contra_sens_r ='<?php echo $refrtsn_const_sen['refraction_contra_sens_r'];?>';

		if(refraction_va_ua_l !='')
			$('.ua_l_txt').show();
		if(refraction_va_ua_r !='')
		    $('.ua_r_txt').show();
		if(refraction_va_ua_l_2 !='')
			$('.ua_l2_txt').show();
		if(refraction_va_ua_r_2 !='')
			$('.ua_r2_txt').show();
		if(refraction_va_ph_l !='')
			$('.ph_l_txt').show();
		if(refraction_va_ph_r !='')
			$('.ph_r_txt').show();
		if(refraction_va_gls_l !='')
			$('.gls_l_txt').show();
		if(refraction_va_gls_r !='')
			$('.gls_r_txt').show();
		if(refraction_va_gls_l_2 !='')
			$('.gls_l2_txt').show();
		if(refraction_va_gls_r_2 !='')
			$('.gls_r2_txt').show();
		if(refraction_va_cl_l !='')
			$('.cl_l_txt').show();
		if(refraction_va_cl_r !='')
		    $('.cl_r_txt').show();

		if(refraction_va_ua_l_p=='P'){
	      $('#refraction_va_ua_l_p').parent().toggleClass('bg-theme');
	       $('#refraction_va_ua_l_p').prop('checked', true);
	    }
	    if(refraction_va_ua_r_p=='P'){
	      $('#refraction_va_ua_r_p').parent().toggleClass('bg-theme');
	      $('#refraction_va_ua_r_p').prop('checked', true);
	    }
	    if(refraction_va_ua_l_p_2=='P'){
	      $('#refraction_va_ua_l_p_2').parent().toggleClass('bg-theme');
	      $('#refraction_va_ua_l_p_2').prop('checked', true);
	    }
	    if(refraction_va_ua_r_p_2=='P'){
	      $('#refraction_va_ua_r_p_2').parent().toggleClass('bg-theme');
	      $('#refraction_va_ua_r_p_2').prop('checked', true);
	    }
	    if(refraction_va_ph_l_p=='P'){
	      $('#refraction_va_ph_l_p').parent().toggleClass('bg-theme');
	      $('#refraction_va_ph_l_p').prop('checked', true);
	    }
	    if(refraction_va_ph_l_ni=='NI'){
	      $('#refraction_va_ph_l_ni').parent().toggleClass('bg-theme');
	      $('#refraction_va_ph_l_ni').prop('checked', true);
	    }
	    if(refraction_va_ph_r_p=='P'){
	      $('#refraction_va_ph_r_p').parent().toggleClass('bg-theme');
	      $('#refraction_va_ph_r_p').prop('checked', true);
	    }
	    if(refraction_va_ph_r_ni=='NI'){
	      $('#refraction_va_ph_r_ni').parent().toggleClass('bg-theme');
	      $('#refraction_va_ph_r_ni').prop('checked', true);
	    }

	    if(refraction_va_gls_l_p=='P'){
	      $('#refraction_va_gls_l_p').parent().toggleClass('bg-theme');
	      $('#refraction_va_gls_l_p').prop('checked', true);
	    }
	    if(refraction_va_gls_r_p=='P'){
	      $('#refraction_va_gls_r_p').parent().toggleClass('bg-theme');
	      $('#refraction_va_gls_r_p').prop('checked', true);
	    }
	    if(refraction_va_gls_l_p_2=='P'){
	      $('#refraction_va_gls_l_p_2').parent().toggleClass('bg-theme');
	      $('#refraction_va_gls_l_p_2').prop('checked', true);
	    }
	    if(refraction_va_gls_r_p_2=='P'){
	      $('#refraction_va_gls_r_p_2').parent().toggleClass('bg-theme');
	      $('#refraction_va_gls_r_p_2').prop('checked', true);
	    }
	   if(refraction_va_cl_l_p=='P'){
	      $('#refraction_va_cl_l_p').parent().toggleClass('bg-theme');
	      $('#refraction_va_cl_l_p').prop('checked', true);
	    }
	    if(refraction_va_cl_r_p=='P'){
	      $('#refraction_va_cl_r_p').parent().toggleClass('bg-theme');
	      $('#refraction_va_cl_r_p').prop('checked', true);
	    }

	    if(refraction_itgp_ad_check==1){
	      $('#refraction_itgp_ad_check').parent().toggleClass('bg-theme');
	      $('#refraction_itgp_ad_check').prop('checked', true);
	    }
	    if(refraction_gps_ad_check==1){
	      $('#refraction_gps_ad_check').parent().toggleClass('bg-theme');
	      $('#refraction_gps_ad_check').prop('checked', true);
	    }

	    var radios1 = $('input[name=refraction_va_ua_l]');
		radios1.filter('[value="'+refraction_va_ua_l+'"]').prop('checked', true).parent().addClass('active');
		var radios2 = $('input[name=refraction_va_ua_r]');
		radios2.filter('[value="'+refraction_va_ua_r+'"]').prop('checked', true).parent().addClass('active');
		var radios3 = $('input[name=refraction_va_ua_l_2]');
		radios3.filter('[value="'+refraction_va_ua_l_2+'"]').prop('checked', true).parent().addClass('active');
		var radios4 = $('input[name=refraction_va_ua_r_2]');
		radios4.filter('[value="'+refraction_va_ua_r_2+'"]').prop('checked', true).parent().addClass('active');
		var radios5 = $('input[name=refraction_va_ph_l]');
		radios5.filter('[value="'+refraction_va_ph_l+'"]').prop('checked', true).parent().addClass('active');
		var radios6 = $('input[name=refraction_va_ph_r]');
		radios6.filter('[value="'+refraction_va_ph_r+'"]').prop('checked', true).parent().addClass('active');
		var radios7 = $('input[name=refraction_va_gls_l]');
		radios7.filter('[value="'+refraction_va_gls_l+'"]').prop('checked', true).parent().addClass('active');
		var radios8 = $('input[name=refraction_va_gls_r]');
		radios8.filter('[value="'+refraction_va_gls_r+'"]').prop('checked', true).parent().addClass('active');
		var radios9 = $('input[name=refraction_va_gls_l_2]');
		radios9.filter('[value="'+refraction_va_gls_l_2+'"]').prop('checked', true).parent().addClass('active');
		var radios10 = $('input[name=refraction_va_gls_r_2]');
		radios10.filter('[value="'+refraction_va_gls_r_2+'"]').prop('checked', true).parent().addClass('active');
		var radios11 = $('input[name=refraction_va_cl_l]');
		radios11.filter('[value="'+refraction_va_cl_l+'"]').prop('checked', true).parent().addClass('active');
		var radios12 = $('input[name=refraction_va_cl_r]');
		radios12.filter('[value="'+refraction_va_cl_r+'"]').prop('checked', true).parent().addClass('active');
		var radios13 = $('input[name=refraction_contra_sens_l]');
		radios13.filter('[value="'+refraction_contra_sens_l+'"]').prop('checked', true).parent().addClass('active');
  		var radios14 = $('input[name=refraction_contra_sens_r]');
		radios14.filter('[value="'+refraction_contra_sens_r+'"]').prop('checked', true).parent().addClass('active');
	});


	function cpoy_to_glass_pres(tab_name)
	{
		  $('#refraction_gps_l_dt_sph').val($('#'+tab_name+'_l_dt_sph').val());
		  $('#refraction_gps_l_dt_cyl').val($('#'+tab_name+'_l_dt_cyl').val());
		  $('#refraction_gps_l_dt_axis').val($('#'+tab_name+'_l_dt_axis').val());
		  $('#refraction_gps_l_dt_vision').val($('#'+tab_name+'_l_dt_vision').val());
		  $('#refraction_gps_l_ad_sph').val($('#'+tab_name+'_l_nr_sph').val());
		  $('#refraction_gps_l_ad_vision').val($('#'+tab_name+'_l_ad_vision').val());
		  $('#refraction_gps_l_nr_sph').val($('#'+tab_name+'_l_nr_sph').val());
		  $('#refraction_gps_l_nr_cyl').val($('#'+tab_name+'_l_nr_cyl').val());
		  $('#refraction_gps_l_nr_axis').val($('#'+tab_name+'_l_nr_axis').val());
		  $('#refraction_gps_l_nr_vision').val($('#'+tab_name+'_l_nr_vision').val());

		  $('#refraction_gps_r_dt_sph').val($('#'+tab_name+'_r_dt_sph').val());
		  $('#refraction_gps_r_dt_cyl').val($('#'+tab_name+'_r_dt_cyl').val());
		  $('#refraction_gps_r_dt_axis').val($('#'+tab_name+'_r_dt_axis').val());
		  $('#refraction_gps_r_dt_vision').val($('#'+tab_name+'_r_dt_vision').val());
		  $('#refraction_gps_r_ad_sph').val($('#'+tab_name+'_r_ad_sph').val());
		  $('#refraction_gps_r_ad_vision').val($('#'+tab_name+'_r_ad_vision').val());
		  $('#refraction_gps_r_nr_sph').val($('#'+tab_name+'_r_nr_sph').val());
		  $('#refraction_gps_r_nr_cyl').val($('#'+tab_name+'_r_nr_cyl').val());
		  $('#refraction_gps_r_nr_axis').val($('#'+tab_name+'_r_nr_axis').val());
		  $('#refraction_gps_r_nr_vision').val($('#'+tab_name+'_r_nr_vision').val());
	}
	$(function () {
	  $('[data-toggle="tooltip"]').tooltip()
	})
</script>



<div id="load_add_type_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
