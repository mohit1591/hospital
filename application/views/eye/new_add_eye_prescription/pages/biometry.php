<section class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			
			
			
<style type="text/css">
.mt-3 {margin-top:1.5rem;}
.pt-3 {padding-top:1.5rem;}
.text-red {color:red;}
td:first-child  {width:auto;text-align:unset;}
.float-right {float:right;}
.table-borderless th,
.table-borderless td {border:0!important;}
.d_mb_20 {margin-bottom:20px;}
.d_pl {padding-left:2rem;}
.d_pl li {margin-bottom:1rem;}
</style>


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
				<td><input type="text" value="<?php echo $biometry_ob_k1_one; ?>" name="biometry_ob_k1_one" id="biometry_ob_k1_one" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_ob_k1_two; ?>" name="biometry_ob_k1_two" id="biometry_ob_k1_two" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_ob_k1_three; ?>" name="biometry_ob_k1_three" id="biometry_ob_k1_three" class="" style="display: inline-block;"></td>
			</tr>
			<tr>
				<td><input type="text" value="<?php echo $biometry_ob_k2_one; ?>" name="biometry_ob_k2_one" id="biometry_ob_k2_one" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_ob_k2_two; ?>" name="biometry_ob_k2_two" id="biometry_ob_k2_two" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_ob_k2_three; ?>" name="biometry_ob_k2_three" id="biometry_ob_k2_three" class="" style="display: inline-block;"></td>
			</tr>
			<tr>
				<td><input type="text" value="<?php echo $biometry_ob_al_one; ?>" name="biometry_ob_al_one" id="biometry_ob_al_one" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_ob_al_two; ?>" name="biometry_ob_al_two" id="biometry_ob_al_two" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_ob_al_three; ?>" name="biometry_ob_al_three" id="biometry_ob_al_three" class="" style="display: inline-block;"></td>
			</tr>
			<tr>
				<td style="width:200px" rowspan="3">ASCAN <br> & <br>AUTO-K</td>
				<td><input type="text" value="<?php echo $biometry_ascan_one; ?>" name="biometry_ascan_one" id="biometry_ascan_one" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_ascan_two; ?>" name="biometry_ascan_two" id="biometry_ascan_two" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_ascan_three; ?>" name="biometry_ascan_three" id="biometry_ascan_three" class="" style="display: inline-block;"></td>
			</tr>
			<tr>
				<td><input type="text" value="<?php echo $biometry_ascan_sec_one; ?>" name="biometry_ascan_sec_one" id="biometry_ascan_sec_one" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_ascan_sec_two; ?>" name="biometry_ascan_sec_two" id="biometry_ascan_sec_two" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_ascan_sec_three; ?>" name="biometry_ascan_sec_three" id="biometry_ascan_sec_three" class="" style="display: inline-block;"></td>
			</tr>
			<tr>
				<td><input type="text" value="<?php echo $biometry_ascan_thr_one; ?>" name="biometry_ascan_thr_one" id="biometry_ascan_thr_one" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_ascan_thr_two; ?>" name="biometry_ascan_thr_two" id="biometry_ascan_thr_two" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_ascan_thr_three; ?>" name="biometry_ascan_thr_three" id="biometry_ascan_thr_three" class="" style="display: inline-block;"></td>
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
					<select name="biometry_iol_one" id="" class="form-control" style="max-width:200px">
						<option <?php if($biometry_iol_one=='RYCF'){ ?> selected="selected" <?php } ?> value="RYCF">RYCF</option>
						<option <?php if($biometry_iol_one=='AUROFLEX'){ ?> selected="selected" <?php } ?> value="AUROFLEX">AUROFLEX</option>
						<option <?php if($biometry_iol_one=='ULTIMA'){ ?> selected="selected" <?php } ?> value="ULTIMA">ULTIMA</option>
						<option <?php if($biometry_iol_one=='AUROFLEX EV'){ ?> selected="selected" <?php } ?> value="AUROFLEX EV">AUROFLEX EV</option>
						<option <?php if($biometry_iol_one=='ACRIOL'){ ?> selected="selected" <?php } ?> value="ACRIOL">ACRIOL</option>
						<option <?php if($biometry_iol_one=='AUROVUE'){ ?> selected="selected" <?php } ?> value="AUROVUE">AUROVUE</option>
						<option <?php if($biometry_iol_one=='SP'){ ?> selected="selected" <?php } ?> value="SP">SP</option>
						<option <?php if($biometry_iol_one=='ACRIVISION'){ ?> selected="selected" <?php } ?> value="ACRIVISION">ACRIVISION</option>
						<option <?php if($biometry_iol_one=='IQ'){ ?> selected="selected" <?php } ?> value="IQ">IQ</option>
						<option <?php if($biometry_iol_one=='CT LUCIA'){ ?> selected="selected" <?php } ?> value="CT LUCIA">CT LUCIA</option>
						<option <?php if($biometry_iol_one=='CT ASPHINA'){ ?> selected="selected" <?php } ?> value="CT ASPHINA">CT ASPHINA</option>
						<option <?php if($biometry_iol_one=='ULTRASERT'){ ?> selected="selected" <?php } ?> value="ULTRASERT">ULTRASERT</option>
						<option <?php if($biometry_iol_one=='RAYONE CFLEX'){ ?> selected="selected" <?php } ?> value="RAYONE CFLEX">RAYONE CFLEX</option>
						<option <?php if($biometry_iol_one=='RAYONE ASPHERIC'){ ?> selected="selected" <?php } ?> value="RAYONE ASPHERIC">RAYONE ASPHERIC</option>
						<option <?php if($biometry_iol_one=='RAYONE'){ ?> selected="selected" <?php } ?> value="RAYONE">RAYONE </option>
						<option <?php if($biometry_iol_one=='HYDROPHOBIC'){ ?> selected="selected" <?php } ?> value="HYDROPHOBIC">HYDROPHOBIC</option>
						<option <?php if($biometry_iol_one=='PANOPTIX'){ ?> selected="selected" <?php } ?> value="PANOPTIX">PANOPTIX</option>
						<option <?php if($biometry_iol_one=='TORIC'){ ?> selected="selected" <?php } ?> value="TORIC">TORIC </option>
					</select>
				</td>
				
				<td><input type="text" value="<?php echo $biometry_srk_one; ?>" name="biometry_srk_one" id="biometry_ascan_sec_one" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_error_one; ?>" name="biometry_error_one" id="biometry_error_one" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_barett_one; ?>" name="biometry_barett_one" id="biometry_barett_one" class="" style="display: inline-block;"></td>
				
				<td><input type="text" value="<?php echo $biometry_error_one_two; ?>" name="biometry_error_one_two" id="biometry_error_one_two" class="" style="display: inline-block;"></td>
				
			</tr>
			<tr>
				<td style="padding:1px;">
					<select name="biometry_iol_two" id="biometry_iol_two" class="form-control" style="max-width:200px">
						<option <?php if($biometry_iol_two=='RYCF'){ ?> selected="selected" <?php } ?> value="RYCF">RYCF</option>
						<option <?php if($biometry_iol_two=='AUROFLEX'){ ?> selected="selected" <?php } ?> value="AUROFLEX">AUROFLEX</option>
						<option <?php if($biometry_iol_two=='ULTIMA'){ ?> selected="selected" <?php } ?> value="ULTIMA">ULTIMA</option>
						<option <?php if($biometry_iol_two=='AUROFLEX EV'){ ?> selected="selected" <?php } ?> value="AUROFLEX EV">AUROFLEX EV</option>
						<option <?php if($biometry_iol_two=='ACRIOL'){ ?> selected="selected" <?php } ?> value="ACRIOL">ACRIOL</option>
						<option <?php if($biometry_iol_two=='AUROVUE'){ ?> selected="selected" <?php } ?> value="AUROVUE">AUROVUE</option>
						<option <?php if($biometry_iol_two=='SP'){ ?> selected="selected" <?php } ?> value="SP">SP</option>
						<option <?php if($biometry_iol_two=='ACRIVISION'){ ?> selected="selected" <?php } ?> value="ACRIVISION">ACRIVISION</option>
						<option <?php if($biometry_iol_two=='IQ'){ ?> selected="selected" <?php } ?> value="IQ">IQ</option>
						<option <?php if($biometry_iol_two=='CT LUCIA'){ ?> selected="selected" <?php } ?> value="CT LUCIA">CT LUCIA</option>
						<option <?php if($biometry_iol_two=='CT ASPHINA'){ ?> selected="selected" <?php } ?> value="CT ASPHINA">CT ASPHINA</option>
						<option <?php if($biometry_iol_two=='ULTRASERT'){ ?> selected="selected" <?php } ?> value="ULTRASERT">ULTRASERT</option>
						<option <?php if($biometry_iol_two=='RAYONE CFLEX'){ ?> selected="selected" <?php } ?> value="RAYONE CFLEX">RAYONE CFLEX</option>
						<option <?php if($biometry_iol_two=='RAYONE ASPHERIC'){ ?> selected="selected" <?php } ?> value="RAYONE ASPHERIC">RAYONE ASPHERIC</option>
						<option <?php if($biometry_iol_two=='RAYONE'){ ?> selected="selected" <?php } ?> value="RAYONE">RAYONE </option>
						<option <?php if($biometry_iol_two=='HYDROPHOBIC'){ ?> selected="selected" <?php } ?> value="HYDROPHOBIC">HYDROPHOBIC</option>
						<option <?php if($biometry_iol_two=='PANOPTIX'){ ?> selected="selected" <?php } ?> value="PANOPTIX">PANOPTIX</option>
						<option <?php if($biometry_iol_two=='TORIC'){ ?> selected="selected" <?php } ?> value="TORIC">TORIC </option>
					</select>
				</td>
				<td><input type="text" value="<?php echo $biometry_ascan_sec_sec; ?>" name="biometry_ascan_sec_sec" id="biometry_ascan_sec_sec" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_error_sec; ?>" name="biometry_error_sec" id="biometry_error_sec" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_barett_sec; ?>" name="biometry_barett_sec" id="biometry_barett_sec" class="" style="display: inline-block;"></td>
				
				<td><input type="text" value="<?php echo $biometry_error_one_sec; ?>" name="biometry_error_one_sec" id="biometry_error_one_two_sec" class="" style="display: inline-block;"></td>
			</tr>
			<tr>
				<td style="padding:1px;">
					<select name="biometry_iol_thr" id="biometry_iol_thr" class="form-control" style="max-width:200px">
						<option <?php if($biometry_iol_thr=='RYCF'){ ?> selected="selected" <?php } ?> value="RYCF">RYCF</option>
						<option <?php if($biometry_iol_thr=='AUROFLEX'){ ?> selected="selected" <?php } ?> value="AUROFLEX">AUROFLEX</option>
						<option <?php if($biometry_iol_thr=='ULTIMA'){ ?> selected="selected" <?php } ?> value="ULTIMA">ULTIMA</option>
						<option <?php if($biometry_iol_thr=='AUROFLEX EV'){ ?> selected="selected" <?php } ?> value="AUROFLEX EV">AUROFLEX EV</option>
						<option <?php if($biometry_iol_thr=='ACRIOL'){ ?> selected="selected" <?php } ?> value="ACRIOL">ACRIOL</option>
						<option <?php if($biometry_iol_thr=='AUROVUE'){ ?> selected="selected" <?php } ?> value="AUROVUE">AUROVUE</option>
						<option <?php if($biometry_iol_thr=='SP'){ ?> selected="selected" <?php } ?> value="SP">SP</option>
						<option <?php if($biometry_iol_thr=='ACRIVISION'){ ?> selected="selected" <?php } ?> value="ACRIVISION">ACRIVISION</option>
						<option <?php if($biometry_iol_thr=='IQ'){ ?> selected="selected" <?php } ?> value="IQ">IQ</option>
						<option <?php if($biometry_iol_thr=='CT LUCIA'){ ?> selected="selected" <?php } ?> value="CT LUCIA">CT LUCIA</option>
						<option <?php if($biometry_iol_thr=='CT ASPHINA'){ ?> selected="selected" <?php } ?> value="CT ASPHINA">CT ASPHINA</option>
						<option <?php if($biometry_iol_thr=='ULTRASERT'){ ?> selected="selected" <?php } ?> value="ULTRASERT">ULTRASERT</option>
						<option <?php if($biometry_iol_thr=='RAYONE CFLEX'){ ?> selected="selected" <?php } ?> value="RAYONE CFLEX">RAYONE CFLEX</option>
						<option <?php if($biometry_iol_thr=='RAYONE ASPHERIC'){ ?> selected="selected" <?php } ?> value="RAYONE ASPHERIC">RAYONE ASPHERIC</option>
						<option <?php if($biometry_iol_thr=='RAYONE'){ ?> selected="selected" <?php } ?> value="RAYONE">RAYONE </option>
						<option <?php if($biometry_iol_thr=='HYDROPHOBIC'){ ?> selected="selected" <?php } ?> value="HYDROPHOBIC">HYDROPHOBIC</option>
						<option <?php if($biometry_iol_thr=='PANOPTIX'){ ?> selected="selected" <?php } ?> value="PANOPTIX">PANOPTIX</option>
						<option <?php if($biometry_iol_thr=='TORIC'){ ?> selected="selected" <?php } ?> value="TORIC">TORIC </option>
					</select>
				</td>
				<td><input type="text" value="<?php echo $biometry_ascan_sec_thr; ?>" name="biometry_ascan_sec_thr" id="biometry_ascan_sec_thr" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_error_thr; ?>" name="biometry_error_thr" id="biometry_error_thr" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_barett_thr; ?>" name="biometry_barett_thr" id="biometry_barett_thr" class="" style="display: inline-block;"></td>
				
				<td><input type="text" value="<?php echo $biometry_error_one_thr; ?>" name="biometry_error_one_thr" id="biometry_error_one_thr" class="" style="display: inline-block;"></td>
			</tr>
			<tr>
				<td style="padding:1px;">
					<select name="biometry_iol_four" id="biometry_iol_four" class="form-control" style="max-width:200px">
						<option <?php if($biometry_iol_four=='RYCF'){ ?> selected="selected" <?php } ?> value="RYCF">RYCF</option>
						<option <?php if($biometry_iol_four=='AUROFLEX'){ ?> selected="selected" <?php } ?> value="AUROFLEX">AUROFLEX</option>
						<option <?php if($biometry_iol_four=='ULTIMA'){ ?> selected="selected" <?php } ?> value="ULTIMA">ULTIMA</option>
						<option <?php if($biometry_iol_four=='AUROFLEX EV'){ ?> selected="selected" <?php } ?> value="AUROFLEX EV">AUROFLEX EV</option>
						<option <?php if($biometry_iol_four=='ACRIOL'){ ?> selected="selected" <?php } ?> value="ACRIOL">ACRIOL</option>
						<option <?php if($biometry_iol_four=='AUROVUE'){ ?> selected="selected" <?php } ?> value="AUROVUE">AUROVUE</option>
						<option <?php if($biometry_iol_four=='SP'){ ?> selected="selected" <?php } ?> value="SP">SP</option>
						<option <?php if($biometry_iol_four=='ACRIVISION'){ ?> selected="selected" <?php } ?> value="ACRIVISION">ACRIVISION</option>
						<option <?php if($biometry_iol_four=='IQ'){ ?> selected="selected" <?php } ?> value="IQ">IQ</option>
						<option <?php if($biometry_iol_four=='CT LUCIA'){ ?> selected="selected" <?php } ?> value="CT LUCIA">CT LUCIA</option>
						<option <?php if($biometry_iol_four=='CT ASPHINA'){ ?> selected="selected" <?php } ?> value="CT ASPHINA">CT ASPHINA</option>
						<option <?php if($biometry_iol_four=='ULTRASERT'){ ?> selected="selected" <?php } ?> value="ULTRASERT">ULTRASERT</option>
						<option <?php if($biometry_iol_four=='RAYONE CFLEX'){ ?> selected="selected" <?php } ?> value="RAYONE CFLEX">RAYONE CFLEX</option>
						<option <?php if($biometry_iol_four=='RAYONE ASPHERIC'){ ?> selected="selected" <?php } ?> value="RAYONE ASPHERIC">RAYONE ASPHERIC</option>
						<option <?php if($biometry_iol_four=='RAYONE'){ ?> selected="selected" <?php } ?> value="RAYONE">RAYONE </option>
						<option <?php if($biometry_iol_four=='HYDROPHOBIC'){ ?> selected="selected" <?php } ?> value="HYDROPHOBIC">HYDROPHOBIC</option>
						<option <?php if($biometry_iol_four=='PANOPTIX'){ ?> selected="selected" <?php } ?> value="PANOPTIX">PANOPTIX</option>
						<option <?php if($biometry_iol_four=='TORIC'){ ?> selected="selected" <?php } ?> value="TORIC">TORIC </option>
					</select>
				</td>
				<td><input type="text" value="<?php echo $biometry_ascan_sec_four; ?>" name="biometry_ascan_sec_four" id="biometry_ascan_sec_four" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_error_four; ?>" name="biometry_error_four" id="biometry_error_four" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_barett_four; ?>" name="biometry_barett_four" id="biometry_barett_four" class="" style="display: inline-block;"></td>
				
				<td><input type="text" value="<?php echo $biometry_error_one_four; ?>" name="biometry_error_one_four" id="biometry_error_one_four" class="" style="display: inline-block;"></td>
			</tr>
			<tr>
				<td style="padding:1px;">
					<select name="biometry_iol_five" id="biometry_iol_five" class="form-control" style="max-width:200px">
						<option <?php if($biometry_iol_five=='RYCF'){ ?> selected="selected" <?php } ?> value="RYCF">RYCF</option>
						<option <?php if($biometry_iol_five=='AUROFLEX'){ ?> selected="selected" <?php } ?> value="AUROFLEX">AUROFLEX</option>
						<option <?php if($biometry_iol_five=='ULTIMA'){ ?> selected="selected" <?php } ?> value="ULTIMA">ULTIMA</option>
						<option <?php if($biometry_iol_five=='AUROFLEX EV'){ ?> selected="selected" <?php } ?> value="AUROFLEX EV">AUROFLEX EV</option>
						<option <?php if($biometry_iol_five=='ACRIOL'){ ?> selected="selected" <?php } ?> value="ACRIOL">ACRIOL</option>
						<option <?php if($biometry_iol_five=='AUROVUE'){ ?> selected="selected" <?php } ?> value="AUROVUE">AUROVUE</option>
						<option <?php if($biometry_iol_five=='SP'){ ?> selected="selected" <?php } ?> value="SP">SP</option>
						<option <?php if($biometry_iol_five=='ACRIVISION'){ ?> selected="selected" <?php } ?> value="ACRIVISION">ACRIVISION</option>
						<option <?php if($biometry_iol_five=='IQ'){ ?> selected="selected" <?php } ?> value="IQ">IQ</option>
						<option <?php if($biometry_iol_five=='CT LUCIA'){ ?> selected="selected" <?php } ?> value="CT LUCIA">CT LUCIA</option>
						<option <?php if($biometry_iol_five=='CT ASPHINA'){ ?> selected="selected" <?php } ?> value="CT ASPHINA">CT ASPHINA</option>
						<option <?php if($biometry_iol_five=='ULTRASERT'){ ?> selected="selected" <?php } ?> value="ULTRASERT">ULTRASERT</option>
						<option <?php if($biometry_iol_five=='RAYONE CFLEX'){ ?> selected="selected" <?php } ?> value="RAYONE CFLEX">RAYONE CFLEX</option>
						<option <?php if($biometry_iol_five=='RAYONE ASPHERIC'){ ?> selected="selected" <?php } ?> value="RAYONE ASPHERIC">RAYONE ASPHERIC</option>
						<option <?php if($biometry_iol_five=='RAYONE'){ ?> selected="selected" <?php } ?> value="RAYONE">RAYONE </option>
						<option <?php if($biometry_iol_five=='HYDROPHOBIC'){ ?> selected="selected" <?php } ?> value="HYDROPHOBIC">HYDROPHOBIC</option>
						<option <?php if($biometry_iol_five=='PANOPTIX'){ ?> selected="selected" <?php } ?> value="PANOPTIX">PANOPTIX</option>
						<option <?php if($biometry_iol_five=='TORIC'){ ?> selected="selected" <?php } ?> value="TORIC">TORIC </option>
					</select>
				</td>
				<td><input type="text" value="<?php echo $biometry_ascan_sec_five; ?>" name="biometry_ascan_sec_five" id="biometry_ascan_sec_five" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_error_five; ?>" name="biometry_error_five" id="biometry_error_five" class="" style="display: inline-block;"></td>
				<td><input type="text" value="<?php echo $biometry_barett_five; ?>" name="biometry_barett_five" id="biometry_barett_five" class="" style="display: inline-block;"></td>
				
				<td><input type="text" value="<?php echo $biometry_error_one_five; ?>" name="biometry_error_one_five" id="biometry_error_one_five" class="" style="display: inline-block;"></td>
			</tr>
			<tfoot>
				<tr>
					<td colspan="5">
						<strong>REMARKS:</strong> <br>
						<textarea name="biometry_remarks" id="biometry_remarks" rows="8" class="form-control" placeholder=""><?php echo $biometry_remarks; ?></textarea>
					</td>
				</tr>
			</tfoot>
		</table>
		
	</div>
		</div>
	</div>
</section>