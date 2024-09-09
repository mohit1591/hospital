<section>
	<div class="row">
		<div class="col-xs-3">
			Visit: <small class="mini_outline_btn d-none" onclick="clear_checkups();" id="clear_checkuptype">clear</small>
		</div>
		<div class="col-xs-6 text-center">
			<div class="btn-group" role="group" aria-label="...">
				<label class="btn btn-default ht_btn"> <input type="radio" class="checkups" name="general_checkup" <?php if($history_radios_data['general_checkup']==1){ echo 'checked'; } ?> id="general_checkup" value="1"> General Checkup</label>
				<label class="btn btn-default ht_btn"> <input type="radio" class="checkups" name="general_checkup" <?php if($history_radios_data['general_checkup']==2){ echo 'checked'; } ?> id="routine_checkup" value="2"> Routine Checkup</label>
				<label class="btn btn-default ht_btn"> <input type="radio" class="checkups" name="general_checkup" <?php if($history_radios_data['general_checkup']==3){ echo 'checked'; } ?> id="postop_checkup" value="3"> PostOp Checkup</label>
			</div>
		</div>
		<div class="col-xs-3 text-right">
			<input type="text" name="visit_comm" value="<?php echo $history['visit_comm']; ?>" placeholder="free text...">
		</div>    
	</div>
</section>

 <section>
 <h4>Chief Complaints</h4> 
 <div class="btn-group">
   <label class="btn-custom" style="text-transform:unset!important;"><input type="checkbox" <?php if($chief_complaints['bdv_m']==1){echo 'checked';}?> name="bdv_m" value="1" id="bdv_m"> Blurring/Diminution of vision</label>  
   <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['redness_m']==1){echo 'checked';}?> name="redness_m" value="1" id="redness_m"> Redness</label>  
   <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['pain_m']==1){echo 'checked';}?> name="pain_m" value="1" id="pain_m"> Pain</label>
   <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['injury_m']==1){echo 'checked';}?> name="injury_m" value="1" id="injury_m"> Injury</label>
   <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['water_m']==1){echo 'checked';}?> name="water_m" value="1" id="water_m"> Watering</label>
   <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['discharge_m']==1){echo 'checked';}?> name="discharge_m" value="1" id="discharge_m"> Discharge</label>
   <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['dryness_m']==1){echo 'checked';}?> name="dryness_m" value="1" id="dryness_m"> Dryness</label>
   <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['itch_m']==1){echo 'checked';}?> name="itch_m" value="1" id="itch_m"> Itching</label>
   <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['fbd_m']==1){echo 'checked';}?> name="fbd_m" value="1" id="fbd_m"> FB Sensation</label>
   <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['devs_m']==1){echo 'checked';}?> name="devs_m" value="1" id="devs_m"> Deviation/Squint</label>
   <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['heads_m']==1){echo 'checked';}?> name="heads_m" value="1" id="heads_m"> Headache/Strain</label>
   <label class="btn-custom" style="text-transform:unset!important;"><input type="checkbox" <?php if($chief_complaints['canss_m']==1){echo 'checked';}?> name="canss_m" value="1" id="canss_m"> Change in Size/Shape</label>
   <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['ovs_m']==1){echo 'checked';}?> name="ovs_m" value="1" id="ovs_m"> Other Visual Symptoms</label>
   <label class="btn-custom" style="text-transform:unset!important;"><input type="checkbox" <?php if($chief_complaints['sdv_m']==1){echo 'checked';}?> name="sdv_m" value="1" id="sdv_m"> Shadow/Defect in vision</label>
   <label class="btn-custom" style="text-transform:unset!important;"><input type="checkbox" <?php if($chief_complaints['doe_m']==1){echo 'checked';}?> name="doe_m" value="1" id="doe_m"> Discoloration of Eye</label>
   <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['swel_m']==1){echo 'checked';}?> name="swel_m" value="1" id="swel_m"> Swelling</label>
   <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['burns_m']==1){echo 'checked';}?> name="burns_m" value="1" id="burns_m"> Burning Sensation</label>
 </div>     

  <div class="panel">
    <div class="panel-body">
      <section class="my_float_div">
        <div class="row">
          <div class="col-md-2  m-b-5">
            <label>Name</label>
          </div>
          <div class="col-md-1  m-b-5">
            <label>Side</label>
          </div>
          <div class="col-md-1  m-b-5">
            <label>Duration</label>
          </div>
          <div class="col-md-3  m-b-5">
            <label>Duration Unit</label>
          </div>
          <div class="col-md-3  m-b-5">
            <label>Comments</label>
          </div>
          <div class="col-md-2  m-b-5">
            <label>Options</label>
          </div>
        </div>
      </section>

      <section class="my_float_div" id="append_redness">
        <div class="row" id="pains">
                  <div class="col-md-2  m-b-5">
                    <label>Pain</label>
                  </div>
                  <div class="col-md-1  m-b-5">
                    <select class="form-control" name="history_chief_pains_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_pains_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_pains_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_pains_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                    </select>  
                  </div>
                  <div class="col-md-1 m-b-5">
                    <select class="form-control" name="history_chief_pains_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_pains_dur']==$i){ echo 'selected';} ?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                       
                    </select>
                  </div>
                  <div class="col-md-1  m-b-5">
                    <select class="form-control" name="history_chief_pains_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_pains_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_pains_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_pains_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_pains_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                    </select>
                  </div>
                  <div class="col-md-3  m-b-5">
                    <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_pains_comm'];?>" name="history_chief_pains_comm">
                  </div></div>

        <div class="row" id="blurr">
                 <div class="col-md-2  m-b-5">
                   <label style="text-transform:unset !important">Blurring Diminution of Vision</label>
                 </div>
                     <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_blurr_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_blurr_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_blurr_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_blurr_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_blurr_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_blurr_dur']==$i){ echo 'selected';} ?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_blurr_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_blurr_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_blurr_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_blurr_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_blurr_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_blurr_comm']; ?>" name="history_chief_blurr_comm">
                 </div>
                 <div class="col-md-4  m-b-5">
                   <div class="btn-group">
                     <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['history_chief_blurr_dist']==1){ echo 'checked';} ?> name="history_chief_blurr_dist" id="history_chief_blurr_dist" value="1" >Distant</label>
                     <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['history_chief_blurr_near']==1){ echo 'checked';} ?> name="history_chief_blurr_near" id="history_chief_blurr_near" value="1" >Near</label>
                     <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['history_chief_blurr_pain']==1){ echo 'checked';} ?> name="history_chief_blurr_pain" id="history_chief_blurr_pain" value="1" >Pain</label>
                     <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['history_chief_blurr_ug']==1){ echo 'checked';} ?> name="history_chief_blurr_ug" id="history_chief_blurr_ug" value="1" >Using Glasses</label>
                   </div>
                 </div></div>
               
        <div class="row" id="rednes">
                 <div class="col-md-2  m-b-5">
                   <label>Redness </label>
                 </div>
                     <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_rednes_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_rednes_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_rednes_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_rednes_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_rednes_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_rednes_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_rednes_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_rednes_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_rednes_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_rednes_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_rednes_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_rednes_comm']; ?>" name="history_chief_rednes_comm">
                 </div></div>

        <div class="row" id="injuries">
                 <div class="col-md-2  m-b-5">
                   <label>Injury  </label>
                 </div>
                   <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_injuries_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_injuries_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_injuries_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_injuries_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_injuries_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_injuries_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_injuries_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_injuries_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_injuries_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_injuries_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_injuries_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_injuries_comm'];?>" name="history_chief_injuries_comm">
                 </div></div>

        <div class="row" id="waterings">
                 <div class="col-md-2  m-b-5">
                   <label>Watering </label>
                 </div>
                   <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_waterings_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_waterings_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_waterings_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_waterings_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_waterings_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_waterings_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_waterings_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_waterings_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_waterings_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_waterings_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_waterings_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_waterings_comm'];?>" name="history_chief_waterings_comm">
                 </div></div>

        <div class="row" id="discharges">
                 <div class="col-md-2  m-b-5">
                   <label>Discharge </label>
                 </div>
                     <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_discharges_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_discharges_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_discharges_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_discharges_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_discharges_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_discharges_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_discharges_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_discharges_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_discharges_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_discharges_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_discharges_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_discharges_comm'];?>" name="history_chief_discharges_comm">
                 </div></div>

        <div class="row" id="dryness">
                 <div class="col-md-2  m-b-5">
                   <label>Dryness </label>
                 </div>
                   <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_dryness_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_dryness_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_dryness_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_dryness_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_dryness_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_dryness_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_dryness_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_dryness_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_dryness_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_dryness_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_dryness_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_dryness_comm']; ?>" name="history_chief_dryness_comm">
                 </div></div>

        <div class="row" id="itchings">
                 <div class="col-md-2  m-b-5">
                   <label>Itching  </label>
                 </div>
                   <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_itchings_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_itchings_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_itchings_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_itchings_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_itchings_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_itchings_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_itchings_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_itchings_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_itchings_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_itchings_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_itchings_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_itchings_comm']; ?>" name="history_chief_itchings_comm">
                 </div></div>

        <div class="row" id="fbsensation">
                 <div class="col-md-2  m-b-5">
                   <label>FB Sensation </label>
                 </div>
                     <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_fbsensation_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_fbsensation_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_fbsensation_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_fbsensation_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_fbsensation_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_fbsensation_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_fbsensation_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_fbsensation_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_fbsensation_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_fbsensation_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_fbsensation_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_fbsensation_comm']; ?>" name="history_chief_fbsensation_comm">
                 </div></div>

        <div class="row" id="dev_squint">
                 <div class="col-md-2  m-b-5">
                   <label>Deviation Squint </label>
                 </div>
                     <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_dev_squint_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_dev_squint_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_dev_squint_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_dev_squint_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_dev_squint_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_dev_squint_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_dev_squint_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_dev_squint_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_dev_squint_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option  <?php if($chief_complaints['history_chief_dev_squint_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option  <?php if($chief_complaints['history_chief_dev_squint_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_dev_squint_comm']; ?>" name="history_chief_dev_squint_comm">
                 </div>
                 <div class="col-md-4  m-b-5">
                   <div class="btn-group">

                    <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['history_chief_dev_diplopia']==1){ echo 'checked';} ?> name="history_chief_dev_diplopia" id="history_chief_dev_diplopia" value="1" >Diplopia</label>

                    <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['history_chief_dev_truma']==1){ echo 'checked';} ?> name="history_chief_dev_truma" id="history_chief_dev_truma" value="1" >Trauma</label>

                    <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['history_chief_dev_ps']==1){ echo 'checked';} ?> name="history_chief_dev_ps" id="history_chief_dev_ps" value="1" >Past Surgery</label>
                   </div>
                 </div></div>

        <div class="row" id="head_strain">
                 <div class="col-md-2  m-b-5">
                   <label>Headache Strain </label>
                 </div>
                     <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_head_strain_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_head_strain_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_head_strain_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_head_strain_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_head_strain_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_head_strain_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_head_strain_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_head_strain_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_head_strain_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_head_strain_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_head_strain_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_head_strain_comm']; ?>" name="history_chief_head_strain_comm">
                 </div></div>

        <div class="row" id="size_shape">
                 <div class="col-md-2  m-b-5">
                   <label>Change In Size Shape </label>
                 </div>
                     <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_size_shape_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_size_shape_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_size_shape_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_size_shape_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_size_shape_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_size_shape_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_size_shape_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_size_shape_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_size_shape_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_size_shape_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_size_shape_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_size_shape_comm']; ?>" name="history_chief_size_shape_comm">
                 </div></div>

        <div class="row" id="ovs">
                 <div class="col-md-2  m-b-5">
                   <label>Other Visual Symptoms </label>
                 </div>
                     <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_ovs_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_ovs_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_ovs_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_ovs_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_ovs_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_ovs_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_ovs_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_ovs_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_ovs_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_ovs_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_ovs_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_ovs_comm']; ?>" name="history_chief_ovs_comm">
                 </div>
                 <div class="col-md-4  m-b-5">
                   <div class="btn-group">
                     <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['history_chief_ovs_glare']==1){ echo 'checked';}?> name="history_chief_ovs_glare" value="1" id="history_chief_ovs_glare">Glare</label>
                     <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['history_chief_ovs_floaters']==1){ echo 'checked';}?> name="history_chief_ovs_floaters" value="1" id="history_chief_ovs_floaters">Floaters</label>
                     <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['history_chief_ovs_photophobia']==1){ echo 'checked';}?> name="history_chief_ovs_photophobia" value="1" id="history_chief_ovs_photophobia">Photophobia</label>
                     <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['history_chief_ovs_color_halos']==1){ echo 'checked';}?> name="history_chief_ovs_color_halos" value="1" id="history_chief_ovs_color_halos">Colored Halos</label>
                      <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['history_chief_ovs_metamorphopsia']==1){ echo 'checked';}?> name="history_chief_ovs_metamorphopsia" value="1" id="history_chief_ovs_metamorphopsia">Metamorphopsia</label>
                     <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['history_chief_ovs_chromatopsia']==1){ echo 'checked';}?> name="history_chief_ovs_chromatopsia" value="1" id="history_chief_ovs_chromatopsia">Chromatopsia</label>
                     <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['history_chief_ovs_dnv']==1){ echo 'checked';}?> name="history_chief_ovs_dnv" value="1" id="history_chief_ovs_dnv">Diminished Night Vision</label>
                     <label class="btn-custom"><input type="checkbox" <?php if($chief_complaints['history_chief_ovs_ddv']==1){ echo 'checked';}?> name="history_chief_ovs_ddv" value="1" id="history_chief_ovs_ddv">Diminished Day Vision</label>

                   </div>
                 </div> </div>

        <div class="row" id="sdiv">
                 <div class="col-md-2  m-b-5">
                   <label>Shadow Defect In Vision </label>
                 </div>
                     <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_sdiv_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_sdiv_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_sdiv_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_sdiv_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_sdiv_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_sdiv_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_sdiv_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_sdiv_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_sdiv_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_sdiv_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_sdiv_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_sdiv_comm']; ?>" name="history_chief_sdiv_comm">
                 </div></div>

        <div class="row" id="doe">
                 <div class="col-md-2  m-b-5">
                   <label>Discoloration Of Eye </label>
                 </div>
                     <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_doe_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_doe_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_doe_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_doe_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_doe_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_doe_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_doe_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_doe_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_doe_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_doe_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_doe_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_doe_comm']; ?>" name="history_chief_doe_comm">
                 </div>          </div>

        <div class="row" id="swell">
                 <div class="col-md-2  m-b-5">
                   <label>Swelling  </label>
                 </div>
                     <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_swell_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_swell_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_swell_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_swell_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_swell_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_swell_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_swell_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_swell_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_swell_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_swell_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_swell_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_swell_comm']; ?>" name="history_chief_swell_comm">
                 </div></div>

        <div class="row" id="sen_burn">
                 <div class="col-md-2  m-b-5">
                   <label>Sensation Burning</label>
                 </div>
                     <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_sen_burn_side">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_sen_burn_side']=='Left'){ echo 'selected';}?> value="Left">L</option>
                     <option <?php if($chief_complaints['history_chief_sen_burn_side']=='Right'){ echo 'selected';}?> value="Right">R</option>
                     <option <?php if($chief_complaints['history_chief_sen_burn_side']=='Both'){ echo 'selected';}?> value="Both">B/E</option>
                   </select>  
                 </div>
                 <div class="col-md-1 m-b-5">
                   <select class="form-control" name="history_chief_sen_burn_dur">
                      <option value="">Please Select</option>
                      <?php for($i=1; $i<=40;$i++) { ?>
                        <option <?php if($chief_complaints['history_chief_sen_burn_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php } ?>
                      
                   </select>
                 </div>
                 <div class="col-md-1  m-b-5">
                   <select class="form-control" name="history_chief_sen_burn_unit">
                     <option value="">Please Select</option>
                     <option <?php if($chief_complaints['history_chief_sen_burn_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                     <option <?php if($chief_complaints['history_chief_sen_burn_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                     <option <?php if($chief_complaints['history_chief_sen_burn_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                     <option <?php if($chief_complaints['history_chief_sen_burn_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
                   </select>
                 </div>
                 <div class="col-md-3  m-b-5">
                   <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_sen_burn_comm']; ?>" name="history_chief_sen_burn_comm">
                 </div></div>
      </section>

      <section>
        <div class="row">
          <div class="col-md-2">
            Comments
          </div>
          <div class="col-md-10">
            <textarea class="form-control w-100" name="history_chief_comm"><?php echo $chief_complaints['history_chief_comm'];?></textarea>
          </div>
        </div>
      </section>
    </div>
  </div>
</section>  

  <section>
 <h4>Ophthalmic History</h4> 
 <div class="btn-group">
   <label class="btn-custom"><input type="checkbox" <?php if($ophthalmic['gla_m']==1){ echo 'checked'; }?> name="gla_m" value="1" id="gla_m">Glaucoma</label>    
   <label class="btn-custom"><input type="checkbox" <?php if($ophthalmic['reti_m']==1){ echo 'checked'; }?> name="reti_m" value="1" id="reti_m">Retinal Detachment</label>
   <label class="btn-custom"><input type="checkbox" <?php if($ophthalmic['glass_m']==1){ echo 'checked'; }?> name="glass_m" value="1" id="glass_m">Glass</label>
   <label class="btn-custom"><input type="checkbox" <?php if($ophthalmic['eyedi_m']==1){ echo 'checked'; }?> name="eyedi_m" value="1" id="eyedi_m">Eye Disease</label>
   <label class="btn-custom"><input type="checkbox" <?php if($ophthalmic['eyesu_m']==1){ echo 'checked'; }?> name="eyesu_m" value="1" id="eyesu_m">Eye Surgery</label>
   <label class="btn-custom"><input type="checkbox" <?php if($ophthalmic['uve_m']==1){ echo 'checked'; }?> name="uve_m" value="1" id="uve_m">Uveitis</label>  
   <label class="btn-custom"><input type="checkbox" <?php if($ophthalmic['retil_m']==1){ echo 'checked'; }?> name="retil_m" value="1" id="retil_m">Retinal Laser</label>   
 </div>     


  <div class="panel">
    <div class="panel-body">
      <section class="my_float_div2">
        <div class="row">
          <div class="col-md-1  m-b-5">
            <label>Name</label>
          </div>
          <div class="col-md-2  m-b-5">
            <label>Left duration</label>
          </div>
          <div class="col-md-2  m-b-5">
            <label>left duration Unit</label>
          </div>
          <div class="col-md-1  m-b-5">
            <label>Copy</label>
          </div>
          <div class="col-md-2  m-b-5">
            <label>Right duration</label>
          </div>
          <div class="col-md-2  m-b-5">
            <label>Right duration Unit</label>
          </div>
          <div class="col-md-2  m-b-5">
            <label>Comments</label>
          </div>
        </div>
      </section>

      <section class="my_float_div2">

        <div class="row" id="glau">
          <div class="col-md-1  m-b-5">
            <label>Glaucoma</label>
          </div>
          <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_ophthalmic_glau_l_dur" id="history_ophthalmic_glau_l_dur">
               <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($ophthalmic['history_ophthalmic_glau_l_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>
            </select>  
          </div>
          <div class="col-md-2 m-b-5">
            <select class="form-control" name="history_ophthalmic_glau_l_unit" id="history_ophthalmic_glau_l_unit">
             <option value="">Please Select</option>
             <option <?php if($ophthalmic['history_ophthalmic_glau_l_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($ophthalmic['history_ophthalmic_glau_l_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($ophthalmic['history_ophthalmic_glau_l_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($ophthalmic['history_ophthalmic_glau_l_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
          </div>
          <div class="col-md-1  m-b-5">
            <button type="button" type="button" class="btn-custom" onclick="glaucoma()"><i class="fa fa-arrow-right"></i></button>
          </div> 
          <div class="col-md-2  m-b-5">
             <select class="form-control" name="history_ophthalmic_glau_r_dur" id="history_ophthalmic_glau_r_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($ophthalmic['history_ophthalmic_glau_r_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>
              
           </select>
          </div>
          <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_ophthalmic_glau_r_unit" id="history_ophthalmic_glau_r_unit">
             <option value="">Please Select</option>
             <option <?php if($ophthalmic['history_ophthalmic_glau_r_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($ophthalmic['history_ophthalmic_glau_r_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($ophthalmic['history_ophthalmic_glau_r_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($ophthalmic['history_ophthalmic_glau_r_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
          </div>
          <div class="col-md-2  m-b-5">
            <input type="" placeholder="Comment..." value="<?php echo $ophthalmic['history_ophthalmic_glau_comm']; ?>" name="history_ophthalmic_glau_comm">
          </div>           
        </div>

       <div class="row" id="renti_d">
         <div class="col-md-1  m-b-5">
           <label>Retinal Detachment</label>
         </div>
         <div class="col-md-2  m-b-5">
          <select class="form-control" name="history_ophthalmic_renti_d_l_dur" id="history_ophthalmic_renti_d_l_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($ophthalmic['history_ophthalmic_renti_d_l_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>
              
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_ophthalmic_renti_d_l_unit" id="history_ophthalmic_renti_d_l_unit">
             <option value="">Please Select</option>
             <option <?php if($ophthalmic['history_ophthalmic_renti_d_l_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($ophthalmic['history_ophthalmic_renti_d_l_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option  <?php if($ophthalmic['history_ophthalmic_renti_d_l_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($ophthalmic['history_ophthalmic_renti_d_l_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div>
         <div class="col-md-1  m-b-5">
           <button type="button" onclick="retinal_detachment()" class="btn-custom"><i class="fa fa-arrow-right"></i></button>
         </div> 
         <div class="col-md-2  m-b-5">
             <select class="form-control" name="history_ophthalmic_renti_d_r_dur" id="history_ophthalmic_renti_d_r_dur" id="history_ophthalmic_renti_d_r_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($ophthalmic['history_ophthalmic_renti_d_r_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>
              
           </select>
         </div>
         <div class="col-md-2  m-b-5">
           <select class="form-control" name="history_ophthalmic_renti_d_r_unit" id="history_ophthalmic_renti_d_r_unit" id="history_ophthalmic_renti_d_r_unit">
             <option value="">Please Select</option>
             <option <?php if($ophthalmic['history_ophthalmic_renti_d_r_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($ophthalmic['history_ophthalmic_renti_d_r_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($ophthalmic['history_ophthalmic_renti_d_r_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($ophthalmic['history_ophthalmic_renti_d_r_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div>
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $ophthalmic['history_ophthalmic_renti_d_comm']; ?>" name="history_ophthalmic_renti_d_comm">
         </div>          
       </div>

       <div class="row" id="glas">
         <div class="col-md-1  m-b-5">
           <label>Glasses</label>
         </div>
         <div class="col-md-2  m-b-5">
           <select class="form-control" name="history_ophthalmic_glas_l_dur" id="history_ophthalmic_glas_l_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($ophthalmic['history_ophthalmic_glas_l_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>
           </select>  
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_ophthalmic_glas_l_unit" id="history_ophthalmic_glas_l_unit">
             <option value="">Please Select</option>
             <option <?php if($ophthalmic['history_ophthalmic_glas_l_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($ophthalmic['history_ophthalmic_glas_l_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($ophthalmic['history_ophthalmic_glas_l_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($ophthalmic['history_ophthalmic_glas_l_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div>
         <div class="col-md-1  m-b-5">
           <button type="button" onclick="glassess()" class="btn-custom"><i class="fa fa-arrow-right"></i></button>
         </div> 
         <div class="col-md-2  m-b-5">
             <select class="form-control" name="history_ophthalmic_glas_r_dur" id="history_ophthalmic_glas_r_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($ophthalmic['history_ophthalmic_glas_r_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>
              
           </select>
         </div>
         <div class="col-md-2  m-b-5">
           <select class="form-control" name="history_ophthalmic_glas_r_unit" id="history_ophthalmic_glas_r_unit">
             <option value="">Please Select</option>
             <option  <?php if($ophthalmic['history_ophthalmic_glas_r_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($ophthalmic['history_ophthalmic_glas_r_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($ophthalmic['history_ophthalmic_glas_r_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($ophthalmic['history_ophthalmic_glas_r_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div>
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $ophthalmic['history_ophthalmic_glas_comm']; ?>" name="history_ophthalmic_glas_comm">
         </div>          
       </div>
        
       <div class="row" id="eye_d">
         <div class="col-md-1  m-b-5">
           <label>Eye Disease</label>
         </div>
         <div class="col-md-2  m-b-5">
           <select class="form-control" name="history_ophthalmic_eye_d_l_dur" id="history_ophthalmic_eye_d_l_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($ophthalmic['history_ophthalmic_eye_d_l_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>
           </select>  
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_ophthalmic_eye_d_l_unit" id="history_ophthalmic_eye_d_l_unit">
             <option value="">Please Select</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_d_l_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_d_l_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_d_l_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_d_l_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div>
         <div class="col-md-1  m-b-5">
           <button type="button" onclick="eye_disease()" class="btn-custom"><i class="fa fa-arrow-right"></i></button>
         </div> 
         <div class="col-md-2  m-b-5">
             <select class="form-control" name="history_ophthalmic_eye_d_r_dur" id="history_ophthalmic_eye_d_r_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($ophthalmic['history_ophthalmic_eye_d_r_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>
              
           </select>
         </div>
         <div class="col-md-2  m-b-5">
           <select class="form-control" name="history_ophthalmic_eye_d_r_unit" id="history_ophthalmic_eye_d_r_unit">
             <option value="">Please Select</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_d_r_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_d_r_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_d_r_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_d_r_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div>
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $ophthalmic['history_ophthalmic_eye_d_comm']; ?>" name="history_ophthalmic_eye_d_comm">
         </div>          
       </div>
       
       <div class="row" id="eye_s">
         <div class="col-md-1  m-b-5">
           <label>Eye Surgery</label>
         </div>
         <div class="col-md-2  m-b-5">
           <select class="form-control" name="history_ophthalmic_eye_s_l_dur" id="history_ophthalmic_eye_s_l_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($ophthalmic['history_ophthalmic_eye_s_l_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>
           </select>  
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_ophthalmic_eye_s_l_unit" id="history_ophthalmic_eye_s_l_unit">
             <option value="">Please Select</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_s_l_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_s_l_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_s_l_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_s_l_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div>
         <div class="col-md-1  m-b-5">
           <button type="button" onclick="eye_surgery()" class="btn-custom"><i class="fa fa-arrow-right"></i></button>
         </div> 
         <div class="col-md-2  m-b-5">
             <select class="form-control" name="history_ophthalmic_eye_s_r_dur" id="history_ophthalmic_eye_s_r_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($ophthalmic['history_ophthalmic_eye_s_r_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>
              
           </select>
         </div>
         <div class="col-md-2  m-b-5">
           <select class="form-control" name="history_ophthalmic_eye_s_r_unit" id="history_ophthalmic_eye_s_r_unit">
             <option value="">Please Select</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_s_r_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_s_r_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_s_r_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($ophthalmic['history_ophthalmic_eye_s_r_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div>
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $ophthalmic['history_ophthalmic_eye_s_comm']; ?>" name="history_ophthalmic_eye_s_comm">
         </div>          
       </div>

       <div class="row" id="uvei">
         <div class="col-md-1  m-b-5">
           <label>Uveitis</label>
         </div>
         <div class="col-md-2  m-b-5">
           <select class="form-control" name="history_ophthalmic_uvei_l_dur" id="history_ophthalmic_uvei_l_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($ophthalmic['history_ophthalmic_uvei_l_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>
           </select>  
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_ophthalmic_uvei_l_unit" id="history_ophthalmic_uvei_l_unit">
             <option value="">Please Select</option>
              <option <?php if($ophthalmic['history_ophthalmic_uvei_l_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($ophthalmic['history_ophthalmic_uvei_l_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
             <option <?php if($ophthalmic['history_ophthalmic_uvei_l_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($ophthalmic['history_ophthalmic_uvei_l_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div>
         <div class="col-md-1  m-b-5">
           <button type="button" onclick="uveitis()" class="btn-custom"><i class="fa fa-arrow-right"></i></button>
         </div> 
         <div class="col-md-2  m-b-5">
             <select class="form-control" name="history_ophthalmic_uvei_r_dur" id="history_ophthalmic_uvei_r_dur">
              <option value="">Please Select </option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($ophthalmic['history_ophthalmic_uvei_r_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>
              
           </select>
         </div>
         <div class="col-md-2  m-b-5">
           <select class="form-control" name="history_ophthalmic_uvei_r_unit" id="history_ophthalmic_uvei_r_unit">
              <option value="">Please Select</option>
              <option <?php if($ophthalmic['history_ophthalmic_uvei_r_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($ophthalmic['history_ophthalmic_uvei_r_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
             <option <?php if($ophthalmic['history_ophthalmic_uvei_r_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($ophthalmic['history_ophthalmic_uvei_r_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div>
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $ophthalmic['history_ophthalmic_uvei_comm']; ?>" name="history_ophthalmic_uvei_comm">
         </div>          
       </div>

       <div class="row" id="renti_l">
         <div class="col-md-1  m-b-5">
           <label>Retinal Laser</label>
         </div>
         <div class="col-md-2  m-b-5">
           <select class="form-control" name="history_ophthalmic_renti_l_l_dur" id="history_ophthalmic_renti_l_l_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($ophthalmic['history_ophthalmic_renti_l_l_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>
           </select>  
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_ophthalmic_renti_l_l_unit" id="history_ophthalmic_renti_l_l_unit">
             <option value="">Please Select</option>
             <option <?php if($ophthalmic['history_ophthalmic_renti_l_l_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($ophthalmic['history_ophthalmic_renti_l_l_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($ophthalmic['history_ophthalmic_renti_l_l_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($ophthalmic['history_ophthalmic_renti_l_l_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div>
         <div class="col-md-1  m-b-5">
           <button type="button" onclick="retinal_laser()" class="btn-custom"><i class="fa fa-arrow-right"></i></button>
         </div> 
         <div class="col-md-2  m-b-5">
             <select class="form-control" name="history_ophthalmic_renti_l_r_dur" id="history_ophthalmic_renti_l_r_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($ophthalmic['history_ophthalmic_renti_l_r_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>
              
           </select>
         </div>
         <div class="col-md-2  m-b-5">
           <select class="form-control" name="history_ophthalmic_renti_l_r_unit" id="history_ophthalmic_renti_l_r_unit">
             <option value="">Please Select</option>
             <option <?php if($ophthalmic['history_ophthalmic_renti_l_r_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($ophthalmic['history_ophthalmic_renti_l_r_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($ophthalmic['history_ophthalmic_renti_l_r_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($ophthalmic['history_ophthalmic_renti_l_r_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div>
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $ophthalmic['history_ophthalmic_renti_l_comm']; ?>" name="history_ophthalmic_renti_l_comm">
         </div>          
       </div>

      </section>

      <section>
        <div class="row">
          <div class="col-md-2">
            Comments
          </div>
          <div class="col-md-10">
            <textarea class="form-control w-100" name="history_ophthalmic_comm"> <?php echo $ophthalmic['history_ophthalmic_comm']; ?></textarea>
          </div>

        </div>
      </section>
    </div>
  </div>
</section>  

<section>
 <h4>Systemic History</h4> 
 <div class="btn-group">
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['dia_m']==1){ echo 'checked'; }?> name="dia_m" value="1" id="dia_m">Diabetes</label>    
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['hyper_m']==1){ echo 'checked'; }?> name="hyper_m" value="1" id="hyper_m">Hypertension</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['alcoh_m']==1){ echo 'checked'; }?> name="alcoh_m" value="1" id="alcoh_m">Alcoholism</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['smok_m']==1){ echo 'checked'; }?> name="smok_m" value="1" id="smok_m">Smoking Tobacco</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['card_m']==1){ echo 'checked'; }?> name="card_m" value="1" id="card_m">Cardiac Disorder</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['steri_m']==1){ echo 'checked'; }?> name="steri_m" value="1" id="steri_m">Steroid Intake</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['drug_m']==1){ echo 'checked'; }?> name="drug_m" value="1" id="drug_m">Drug Abuse</label>    
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['hiva_m']==1){ echo 'checked'; }?> name="hiva_m" value="1" id="hiva_m">Hiv Aids</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['cant_m']==1){ echo 'checked'; }?> name="cant_m" value="1" id="cant_m">Cancer Tumor</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['tuber_m']==1){ echo 'checked'; }?> name="tuber_m" value="1" id="tuber_m">Tuberculosis</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['asth_m']==1){ echo 'checked'; }?> name="asth_m" value="1" id="asth_m">Asthma</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['cnsds_m']==1){ echo 'checked'; }?> name="cnsds_m" value="1" id="cnsds_m">Cns Disorder Stroke</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['hypo_m']==1){ echo 'checked'; }?> name="hypo_m" value="1" id="hypo_m">Hypothyroidism</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['hyperth_m']==1){ echo 'checked'; }?> name="hyperth_m" value="1" id="hyperth_m">Hyperthyroidism</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['hepac_m']==1){ echo 'checked'; }?> name="hepac_m" value="1" id="hepac_m">Hepatitis Cirrhosis</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['renald_m']==1){ echo 'checked'; }?> name="renald_m" value="1" id="renald_m">Renal Disorder</label>    
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['acid_m']==1){ echo 'checked'; }?> name="acid_m" value="1" id="acid_m">Acidity</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['oins_m']==1){ echo 'checked'; }?> name="oins_m" value="1" id="oins_m">On insulin</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['oasp_m']==1){ echo 'checked'; }?> name="oasp_m" value="1" id="oasp_m">On Aspirin Blood Thinners</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['acon_m']==1){ echo 'checked'; }?> name="acon_m" value="1" id="acon_m">Consanguinity</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['thd_m']==1){ echo 'checked'; }?> name="thd_m" value="1" id="thd_m">Thyroid Disorder</label>
   <label class="btn-custom"><input type="checkbox" <?php if($systemic['chewt_m']==1){ echo 'checked'; }?> name="chewt_m" value="1" id="chewt_m">Chewing Tobacco</label>      
 </div>     


  <div class="panel">
    <div class="panel-body">
      <section>
        <div class="row">
          <div class="col-md-2  m-b-5">
            <label>Name</label>
          </div>
          <div class="col-md-2  m-b-5">
            <label>Duration</label>
          </div>
          <div class="col-md-2  m-b-5">
            <label>Duration Unit</label>
          </div>
          <div class="col-md-2  m-b-5">
            <label>Comments</label>
          </div>           
        </div>
      </section>

      <section>
       
        <div class="row" id="diab">
          <div class="col-md-2  m-b-5">
            <label>Diabetes</label>
          </div>
          <div class="col-md-2  m-b-5">
             <select class="form-control" name="history_systemic_diab_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_diab_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
          </div>
          <div class="col-md-2 m-b-5">
            <select class="form-control" name="history_systemic_diab_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_diab_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($systemic['history_systemic_diab_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_diab_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($systemic['history_systemic_diab_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
          </div> 
          <div class="col-md-2  m-b-5">
            <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_diab_comm']; ?>" name="history_systemic_diab_comm">
          </div>           
        </div>

        
       <div class="row" id="hyper">
         <div class="col-md-2  m-b-5">
           <label>Hypertension</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_hyper_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_hyper_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_hyper_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_hyper_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
             <option <?php if($systemic['history_systemic_hyper_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_hyper_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
             <option <?php if($systemic['history_systemic_hyper_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_hyper_comm']; ?>" name="history_systemic_hyper_comm">
         </div>          
       </div>

       <div class="row" id="smokt">
         <div class="col-md-2  m-b-5">
           <label>Smoking Tobacco</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_smokt_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_smokt_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_smokt_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_smokt_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
             <option <?php if($systemic['history_systemic_smokt_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_smokt_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
             <option <?php if($systemic['history_systemic_smokt_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_smokt_comm']; ?>" name="history_systemic_smokt_comm">
         </div>          
       </div>

        
       <div class="row" id="alcoh">
         <div class="col-md-2  m-b-5">
           <label>Alcoholism</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_alcoh_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_alcoh_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_alcoh_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_alcoh_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
             <option <?php if($systemic['history_systemic_alcoh_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_alcoh_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
             <option <?php if($systemic['history_systemic_alcoh_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_alcoh_comm']; ?>" name="history_systemic_alcoh_comm">
         </div>          
       </div>

       <div class="row" id="cardd">
         <div class="col-md-2  m-b-5">
           <label>Cardiac Disorder</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_cardd_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_cardd_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_cardd_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_cardd_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
             <option <?php if($systemic['history_systemic_cardd_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_cardd_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
             <option <?php if($systemic['history_systemic_cardd_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_cardd_comm']; ?>" name="history_systemic_cardd_comm">
         </div>          
       </div>

       <div class="row" id="steri">
         <div class="col-md-2  m-b-5">
           <label>Steroid Intake</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_steri_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_steri_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_steri_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_steri_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
             <option <?php if($systemic['history_systemic_steri_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_steri_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
             <option <?php if($systemic['history_systemic_steri_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_steri_comm']; ?>" name="history_systemic_steri_comm">
         </div>          
       </div>

       <div class="row" id="drug">
         <div class="col-md-2  m-b-5">
           <label>Drug Abuse</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_drug_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_drug_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_drug_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_drug_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
             <option <?php if($systemic['history_systemic_drug_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_drug_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
             <option <?php if($systemic['history_systemic_drug_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_drug_comm']; ?>" name="history_systemic_drug_comm">
         </div>          
       </div>

        
       <div class="row" id="hiva">
         <div class="col-md-2  m-b-5">
           <label>Hiv Aids</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_hiva_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_hiva_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_hiva_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_hiva_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
             <option <?php if($systemic['history_systemic_hiva_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_hiva_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
             <option  <?php if($systemic['history_systemic_hiva_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_hiva_comm']; ?>" name="history_systemic_hiva_comm">
         </div>          
       </div>

       <div class="row" id="cantu">
         <div class="col-md-2  m-b-5">
           <label>Cancer Tumor</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_cantu_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_cantu_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_cantu_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_cantu_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
             <option <?php if($systemic['history_systemic_cantu_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_cantu_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
             <option <?php if($systemic['history_systemic_cantu_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_cantu_comm']; ?>" name="history_systemic_cantu_comm">
         </div>          
       </div>

        
       <div class="row" id="tuberc">
         <div class="col-md-2  m-b-5">
           <label>Tuberculosis</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_tuberc_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_tuberc_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_tuberc_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_tuberc_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($systemic['history_systemic_tuberc_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_tuberc_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($systemic['history_systemic_tuberc_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_tuberc_comm']; ?>" name="history_systemic_tuberc_comm">
         </div>          
       </div>

        
       <div class="row" id="asthm">
         <div class="col-md-2  m-b-5">
           <label>Asthma</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_asthm_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_asthm_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_asthm_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_asthm_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($systemic['history_systemic_asthm_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_asthm_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($systemic['history_systemic_asthm_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_asthm_comm']; ?>" name="history_systemic_asthm_comm">
         </div>          
       </div>

       <div class="row" id="cncds">
         <div class="col-md-2  m-b-5">
           <label>Cns Disorder Stroke</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_cncds_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_cncds_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_cncds_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_cncds_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($systemic['history_systemic_cncds_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_cncds_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($systemic['history_systemic_cncds_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_cncds_comm']; ?>" name="history_systemic_cncds_comm">
         </div>          
       </div>

        
       <div class="row" id="hypo">
         <div class="col-md-2  m-b-5">
           <label>Hypothyroidism</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_hypo_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_hypo_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_hypo_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_hypo_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($systemic['history_systemic_hypo_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_hypo_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($systemic['history_systemic_hypo_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_hypo_comm']; ?>" name="history_systemic_hypo_comm">
         </div>          
       </div>

        
       <div class="row" id="hyperth">
         <div class="col-md-2  m-b-5">
           <label>Hyperthyroidism</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_hyperth_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_hyperth_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_hyperth_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_hyperth_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($systemic['history_systemic_hyperth_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_hyperth_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($systemic['history_systemic_hyperth_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_hyperth_comm']; ?>" name="history_systemic_hyperth_comm">
         </div>          
       </div>

       <div class="row" id="heptc">
         <div class="col-md-2  m-b-5">
           <label>Hepatitis Cirrhosis</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_heptc_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_heptc_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_heptc_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_heptc_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option  <?php if($systemic['history_systemic_heptc_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_heptc_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($systemic['history_systemic_heptc_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_heptc_comm']; ?>" name="history_systemic_heptc_comm">
         </div>          
       </div>

       <div class="row" id="rend">
         <div class="col-md-2  m-b-5">
           <label>Renal Disorder</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_rendis_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_rendis_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_rendis_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_rendis_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($systemic['history_systemic_rendis_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_rendis_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($systemic['history_systemic_rendis_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_rendis_comm']; ?>" name="history_systemic_rendis_comm">
         </div>          
       </div>

        
       <div class="row" id="acid">
         <div class="col-md-2  m-b-5">
           <label>Acidity</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_acid_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_acid_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_acid_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_acid_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($systemic['history_systemic_acid_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_acid_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($systemic['history_systemic_acid_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_acid_comm']; ?>" name="history_systemic_acid_comm">
         </div>          
       </div>

       <div class="row" id="onins">
         <div class="col-md-2  m-b-5">
           <label>On Insulin</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_onins_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_onins_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_onins_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_onins_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($systemic['history_systemic_onins_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_onins_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($systemic['history_systemic_onins_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_onins_comm']; ?>" name="history_systemic_onins_comm">
         </div>          
       </div>

      
       <div class="row" id="oasbth">
         <div class="col-md-2  m-b-5">
           <label>On Aspirin Blood Thinners</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_oasbth_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_oasbth_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_oasbth_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_oasbth_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($systemic['history_systemic_oasbth_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_oasbth_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($systemic['history_systemic_oasbth_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_oasbth_comm']; ?>" name="history_systemic_oasbth_comm">
         </div>          
       </div>

        
       <div class="row" id="consan">
         <div class="col-md-2  m-b-5">
           <label>Consanguinity</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_consan_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_consan_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_consan_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_consan_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($systemic['history_systemic_consan_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_consan_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($systemic['history_systemic_consan_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_consan_comm']; ?>" name="history_systemic_consan_comm">
         </div>          
       </div>

       <div class="row" id="thyrd">
         <div class="col-md-2  m-b-5">
           <label>Thyroid Disorder</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_thyrd_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_thyrd_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_thyrd_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_thyrd_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($systemic['history_systemic_thyrd_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_thyrd_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($systemic['history_systemic_thyrd_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_thyrd_comm']; ?>" name="history_systemic_thyrd_comm">
         </div>          
       </div>

       <div class="row" id="chewt">
         <div class="col-md-2  m-b-5">
           <label>Chewing Tobacco</label>
         </div>
         <div class="col-md-2  m-b-5">
            <select class="form-control" name="history_systemic_chewt_dur">
              <option value="">Please Select</option>
              <?php for($i=1; $i<=40;$i++) { ?>
                <option <?php if($systemic['history_systemic_chewt_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
             <?php } ?>               
           </select>
         </div>
         <div class="col-md-2 m-b-5">
           <select class="form-control" name="history_systemic_chewt_unit">
             <option value="">Please Select</option>
             <option <?php if($systemic['history_systemic_chewt_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
             <option <?php if($systemic['history_systemic_chewt_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
             <option <?php if($systemic['history_systemic_chewt_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
             <option <?php if($systemic['history_systemic_chewt_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
           </select>
         </div> 
         <div class="col-md-2  m-b-5">
           <input type="" placeholder="Comment..." value="<?php echo $systemic['history_systemic_chewt_comm']; ?>" name="history_systemic_chewt_comm">
         </div>          
       </div>

        <div class="row">
          <div class="col-md-2">
            Comments
          </div>
          <div class="col-md-10">
            <textarea class="form-control w-100" name="history_systemic_comm"> <?php echo $systemic['history_systemic_comm']; ?> </textarea>
          </div>

        </div>
      </section>
    </div>
    </div>
   </section>

  <section>
 <div class="row m-b-5">
   <div class="col-md-2  mb-5">
     <label>Family History</label>
   </div>
   <div class="col-md-3  mb-5">
     <input type="text" placeholder="Family History" value="<?php echo $history['family'];?>" name="family_history"> 
   </div>
   <div class="col-md-2 mb-5">
     <label>Medical History</label>
   </div>           

   <div class="col-md-3 mb-5">
     <input type="text" placeholder="Medical History" value="<?php echo $history['medical'];?>" name="medical_history">
   </div>           
 </div>
  </section>

  <section>
 <div class="row m-b-5">
   <div class="col-md-2  mb-5">
     <label>Special Status : <small class="mini_outline_btn d-none" onclick="clear_special_status();" id="clear_special_status">clear</small></label>
   </div>
   <div class="col-md-6 mb-5">
      <div class="btn-group">
        <label class="btn btn-default ht_btn"> <input type="radio" class="special_status" id="special_status_brestf" <?php if($history_radios_data['special_status']==1){ echo 'checked';} ?> name="special_status" value="1"> Breastfeeding</label>
         <label class="btn btn-default ht_btn"> <input type="radio" class="special_status" id="special_status_preg" <?php if($history_radios_data['special_status']==2){ echo 'checked';} ?> name="special_status"  value="2"> Pregnant</label>  
      </div>
    </div>              
 </div>
</section> 

<br><br>
<section>
  <h4>Drug Allergies</h4> 
  <div class="btn-group">
    <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['antimi_agen_m']==1){ echo 'checked';}?> name="antimi_agen_m" value="1" id="antimi_agen_m">Antimicrobial Agents</label>    
    <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['antif_agen_m']==1){ echo 'checked';}?> name="antif_agen_m" value="1" id="antif_agen_m">Antifungal Agents</label>
    <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['ant_agen_m']==1){ echo 'checked';}?> name="ant_agen_m" value="1" id="ant_agen_m">Antiviral Agents</label>
    <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['nsaids_m']==1){ echo 'checked';}?> name="nsaids_m" value="1" id="nsaids_m">Nsaids</label>
    <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['eye_drops_m']==1){ echo 'checked';}?> name="eye_drops_m" value="1" id="eye_drops_m">Eye Drops</label>      
  </div>   
 </section>

 <section id="antimi_agen" style="padding-left:20px;">
    <h4>Antimicrobial agents</h4> 
    <div class="btn-group">
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['ampic_m']==1){ echo 'checked';}?> name="ampic_m" value="1" id="ampic_m">Ampicillin</label>    
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['amox_m']==1){ echo 'checked';}?> name="amox_m" value="1" id="amox_m">Amoxicillin</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['ceftr_m']==1){ echo 'checked';}?> name="ceftr_m" value="1" id="ceftr_m">Ceftriaxone</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['cipro_m']==1){ echo 'checked';}?> name="cipro_m" value="1" id="cipro_m">Ciprofloxacin</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['clari_m']==1){ echo 'checked';}?> name="clari_m" value="1" id="clari_m">Clarithromycin</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['cotri_m']==1){ echo 'checked';}?> name="cotri_m" value="1" id="cotri_m">Co Trimoxazole</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['etham_m']==1){ echo 'checked';}?> name="etham_m" value="1" id="etham_m">Ethambutol</label>    
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['ison_m']==1){ echo 'checked';}?> name="ison_m" value="1" id="ison_m">Isoniazid</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['metro_m']==1){ echo 'checked';}?> name="metro_m" value="1" id="metro_m">Metronidazole</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['penic_m']==1){ echo 'checked';}?> name="penic_m" value="1" id="penic_m">Penicillin</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['rifa_m']==1){ echo 'checked';}?> name="rifa_m" value="1" id="rifa_m">Rifampicin</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['strep_m']==1){ echo 'checked';}?> name="strep_m" value="1" id="strep_m">Streptomycin</label>      
    </div>     


    <div class="panel">
      <div class="panel-body">
        <section>
          <div class="row">
            <div class="col-md-2  m-b-5">
              <label>Name</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Duration</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Duration Unit</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Comments</label>
            </div>           
          </div>
        </section>

        <section>
          
          <div class="row" id="ampici">
            <div class="col-md-2  m-b-5">
              <label>Ampicillin </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_ampici_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antimicrobial_ampici_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_ampici_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ampici_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ampici_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ampici_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ampici_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antimicrobial_ampici_comm']; ?>" name="history_drug_antimicrobial_ampici_comm">
            </div>           
          </div>

          
          <div class="row" id="amoxi">
            <div class="col-md-2  m-b-5">
              <label>Amoxicillin  </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_amoxi_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antimicrobial_amoxi_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_amoxi_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_amoxi_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_amoxi_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_amoxi_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_amoxi_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antimicrobial_amoxi_comm']; ?>" name="history_drug_antimicrobial_amoxi_comm">
            </div>          
          </div>
          
          <div class="row" id="ceftr">
            <div class="col-md-2  m-b-5">
              <label>Ceftriaxone  </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_ceftr_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antimicrobial_ceftr_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_ceftr_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ceftr_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ceftr_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ceftr_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ceftr_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antimicrobial_ceftr_comm']; ?>" name="history_drug_antimicrobial_ceftr_comm">
            </div>          
          </div>
          
          <div class="row" id="ciprof">
            <div class="col-md-2  m-b-5">
              <label>Ciprofloxacin  </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_ciprof_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antimicrobial_ciprof_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_ciprof_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ciprof_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ciprof_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ciprof_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ciprof_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antimicrobial_ciprof_comm']; ?>" name="history_drug_antimicrobial_ciprof_comm">
            </div>          
          </div>
          
          <div class="row" id="clarith">
            <div class="col-md-2  m-b-5">
              <label>Clarithromycin </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_clarith_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antimicrobial_clarith_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_clarith_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_clarith_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_clarith_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_clarith_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_clarith_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antimicrobial_clarith_comm']; ?>" name="history_drug_antimicrobial_clarith_comm">
            </div>          
          </div>
          <div class="row" id="cotri">
            <div class="col-md-2  m-b-5">
              <label>Co Trimoxazole </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_cotri_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antimicrobial_cotri_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_cotri_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_cotri_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_cotri_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_cotri_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_cotri_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antimicrobial_cotri_comm']; ?>" name="history_drug_antimicrobial_cotri_comm">
            </div>          
          </div>
          
          <div class="row" id="ethamb">
            <div class="col-md-2  m-b-5">
              <label>Ethambutol </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_ethamb_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antimicrobial_ethamb_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_ethamb_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ethamb_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ethamb_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ethamb_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_ethamb_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antimicrobial_ethamb_comm']; ?>" name="history_drug_antimicrobial_ethamb_comm">
            </div>          
          </div>
          
          <div class="row" id="isoni">
            <div class="col-md-2  m-b-5">
              <label>Isoniazid </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_isoni_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antimicrobial_isoni_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_isoni_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_isoni_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_isoni_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_isoni_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option  <?php if($drug_allergies['history_drug_antimicrobial_isoni_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antimicrobial_isoni_comm']; ?>" name="history_drug_antimicrobial_isoni_comm">
            </div>          
          </div>
          
          <div class="row" id="metron">
            <div class="col-md-2  m-b-5">
              <label>Metronidazole </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_metron_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antimicrobial_metron_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_metron_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_metron_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_metron_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_metron_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_metron_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antimicrobial_metron_comm']; ?>" name="history_drug_antimicrobial_metron_comm">
            </div>          
          </div>
          
          <div class="row" id="penic">
            <div class="col-md-2  m-b-5">
              <label>Penicillin </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_penic_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antimicrobial_penic_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_penic_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_penic_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_penic_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_penic_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_penic_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antimicrobial_penic_comm']; ?>" name="history_drug_antimicrobial_penic_comm">
            </div>          
          </div>
          
          <div class="row" id="rifam">
            <div class="col-md-2  m-b-5">
              <label>Rifampicin </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_rifam_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antimicrobial_rifam_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_rifam_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_rifam_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option  <?php if($drug_allergies['history_drug_antimicrobial_rifam_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_rifam_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_rifam_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antimicrobial_rifam_comm']; ?>" name="history_drug_antimicrobial_rifam_comm">
            </div>          
          </div>
          
          <div class="row" id="strept">
            <div class="col-md-2  m-b-5">
              <label>Streptomycin  </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_strept_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antimicrobial_strept_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antimicrobial_strept_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_strept_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_strept_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option  <?php if($drug_allergies['history_drug_antimicrobial_strept_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antimicrobial_strept_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antimicrobial_strept_comm']; ?>" name="history_drug_antimicrobial_strept_comm">
            </div>          
          </div>
        </section>       
      </div>
    </div>
</section> 

<section id="antif_agen" style="padding-left:20px;">
    <h4>Antifungal agents</h4> 
    <div class="btn-group">
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['ketoc_m']==1){ echo 'checked';} ?> name="ketoc_m" value="1" id="ketoc_m">Ketoconazole </label>    
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['fluco_m']==1){ echo 'checked';} ?> name="fluco_m" value="1" id="fluco_m">Fluconazole </label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['itrac_m']==1){ echo 'checked';} ?> name="itrac_m" value="1" id="itrac_m">Itraconazole </label>          
    </div>     


    <div class="panel">
      <div class="panel-body">
        <section>
          <div class="row">
            <div class="col-md-2  m-b-5">
              <label>Name</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Duration</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Duration Unit</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Comments</label>
            </div>          
          </div>
        </section>

        <section>
          
          <div class="row" id="ketoco">
            <div class="col-md-2  m-b-5">
              <label>Ketoconazole </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antifungal_ketoco_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antifungal_ketoco_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antifungal_ketoco_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antifungal_ketoco_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antifungal_ketoco_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antifungal_ketoco_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antifungal_ketoco_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antifungal_ketoco_comm']; ?>" name="history_drug_antifungal_ketoco_comm">
            </div>          
          </div>
            

           
          <div class="row" id="flucon">
            <div class="col-md-2  m-b-5">
              <label>Fluconazole </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antifungal_flucon_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antifungal_flucon_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antifungal_flucon_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antifungal_flucon_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antifungal_flucon_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antifungal_flucon_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antifungal_flucon_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antifungal_flucon_comm']; ?>" name="history_drug_antifungal_flucon_comm">
            </div>          
          </div>

           
          <div class="row" id="itrac">
            <div class="col-md-2  m-b-5">
              <label>Itraconazole</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antifungal_itrac_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antifungal_itrac_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antifungal_itrac_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antifungal_itrac_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antifungal_itrac_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antifungal_itrac_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antifungal_itrac_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antifungal_itrac_comm']; ?>" name="history_drug_antifungal_itrac_comm">
            </div>          
          </div>
        </section>      
      </div>
    </div>
</section>

<section id="ant_agen" style="padding-left:20px;">
    <h4>Antiviral Agents</h4> 
    <div class="btn-group">
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['acyclo_m']==1){ echo 'checked';} ?> name="acyclo_m" value="1" id="acyclo_m">Acyclovir</label>    
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['efavir_m']==1){ echo 'checked';} ?> name="efavir_m" value="1" id="efavir_m">Efavirenz</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['enfuv_m']==1){ echo 'checked';} ?> name="enfuv_m" value="1" id="enfuv_m">Enfuvirtide</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['nelfin_m']==1){ echo 'checked';} ?> name="nelfin_m" value="1" id="nelfin_m">Nelfinavir</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['nevira_m']==1){ echo 'checked';} ?> name="nevira_m" value="1" id="nevira_m">Nevirapine</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['zidov_m']==1){ echo 'checked';} ?> name="zidov_m" value="1" id="zidov_m">Zidovudine </label>           
    </div>     


    <div class="panel">
      <div class="panel-body">
        <section>
          <div class="row">
            <div class="col-md-2  m-b-5">
              <label>Name</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Duration</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Duration Unit</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Comments</label>
            </div>          
          </div>
        </section>

        <section>
          
          <div class="row" id="acyclo">
            <div class="col-md-2  m-b-5">
              <label>Acyclovir</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antiviral_acyclo_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antiviral_acyclo_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antiviral_acyclo_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antiviral_acyclo_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antiviral_acyclo_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antiviral_acyclo_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antiviral_acyclo_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antiviral_acyclo_comm']; ?>" name="history_drug_antiviral_acyclo_comm">
            </div>          
          </div>
            

           
          <div class="row" id="efavir">
            <div class="col-md-2  m-b-5">
              <label>Efavirenz</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antiviral_efavir_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antiviral_efavir_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antiviral_efavir_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antiviral_efavir_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antiviral_efavir_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antiviral_efavir_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antiviral_efavir_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antiviral_efavir_comm']; ?>" name="history_drug_antiviral_efavir_comm">
            </div>          
          </div>

           
          <div class="row" id="enfuv">
            <div class="col-md-2  m-b-5">
              <label>Enfuvirtide</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antiviral_enfuv_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antiviral_enfuv_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antiviral_enfuv_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antiviral_enfuv_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option  <?php if($drug_allergies['history_drug_antiviral_enfuv_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antiviral_enfuv_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antiviral_enfuv_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antiviral_enfuv_comm']; ?>" name="history_drug_antiviral_enfuv_comm">
            </div>          
          </div>

           
          <div class="row" id="nelfin">
            <div class="col-md-2  m-b-5">
              <label>Nelfinavir</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antiviral_nelfin_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antiviral_nelfin_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antiviral_nelfin_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antiviral_nelfin_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antiviral_nelfin_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antiviral_nelfin_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antiviral_nelfin_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antiviral_nelfin_comm']; ?>" name="history_drug_antiviral_nelfin_comm">
            </div>          
          </div>

           
          <div class="row" id="nevira">
            <div class="col-md-2  m-b-5">
              <label>Nevirapine</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antiviral_nevira_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antiviral_nevira_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antiviral_nevira_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antiviral_nevira_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antiviral_nevira_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antiviral_nevira_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antiviral_nevira_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antiviral_nevira_comm']; ?>" name="history_drug_antiviral_nevira_comm">
            </div>          
          </div>

           
          <div class="row" id="zidov">
            <div class="col-md-2  m-b-5">
              <label>Zidovudine </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_antiviral_zidov_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_antiviral_zidov_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_antiviral_zidov_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_antiviral_zidov_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_antiviral_zidov_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_antiviral_zidov_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_antiviral_zidov_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_antiviral_zidov_comm']; ?>" name="history_drug_antiviral_zidov_comm">
            </div>          
          </div>

        </section>      
      </div>
    </div>
</section> 

<section id="nsaids" style="padding-left:20px;">
    <h4>Nsaids</h4> 
    <div class="btn-group">
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['aspirin_m']==1){ echo 'checked';} ?> name="aspirin_m" value="1" id="aspirin_m">Aspirin</label>    
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['paracet_m']==1){ echo 'checked';} ?> name="paracet_m" value="1" id="paracet_m">Paracetamol</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['ibupro_m']==1){ echo 'checked';} ?> name="ibupro_m" value="1" id="ibupro_m">Ibuprofen</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['diclo_m']==1){ echo 'checked';} ?> name="diclo_m" value="1" id="diclo_m">Diclofenac</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['aceclo_m']==1){ echo 'checked';} ?> name="aceclo_m" value="1" id="aceclo_m">Aceclofenac</label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['napro_m']==1){ echo 'checked';} ?> name="napro_m" value="1" id="napro_m">Naproxen </label>           
    </div>     


    <div class="panel">
      <div class="panel-body">
        <section>
          <div class="row">
            <div class="col-md-2  m-b-5">
              <label>Name</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Duration</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Duration Unit</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Comments</label>
            </div>          
          </div>
        </section>

        <section>
          
          <div class="row" id="aspirin">
            <div class="col-md-2  m-b-5">
              <label>Aspirin</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_nsaids_aspirin_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_nsaids_aspirin_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_nsaids_aspirin_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_nsaids_aspirin_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_nsaids_aspirin_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_nsaids_aspirin_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_nsaids_aspirin_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_nsaids_aspirin_comm']; ?>" name="history_drug_nsaids_aspirin_comm">
            </div>          
          </div>
            

           <div class="row" id="paracet">
            <div class="col-md-2  m-b-5">
              <label>Paracetamol</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_nsaids_paracet_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_nsaids_paracet_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_nsaids_paracet_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_nsaids_paracet_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_nsaids_paracet_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_nsaids_paracet_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_nsaids_paracet_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_nsaids_paracet_comm']; ?>" name="history_drug_nsaids_paracet_comm">
            </div>          
          </div>

           
          <div class="row" id="ibupro">
            <div class="col-md-2  m-b-5">
              <label>Ibuprofen</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_nsaids_ibupro_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_nsaids_ibupro_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_nsaids_ibupro_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_nsaids_ibupro_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_nsaids_ibupro_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_nsaids_ibupro_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_nsaids_ibupro_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_nsaids_ibupro_comm']; ?>" name="history_drug_nsaids_ibupro_comm">
            </div>          
          </div>

           
          <div class="row" id="diclo">
            <div class="col-md-2  m-b-5">
              <label>Diclofenac</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_nsaids_diclo_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_nsaids_diclo_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_nsaids_diclo_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_nsaids_diclo_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_nsaids_diclo_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_nsaids_diclo_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_nsaids_diclo_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_nsaids_diclo_comm']; ?>" name="history_drug_nsaids_diclo_comm">
            </div>          
          </div>

           
          <div class="row" id="aceclo">
            <div class="col-md-2  m-b-5">
              <label>Aceclofenac</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_nsaids_aceclo_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_nsaids_aceclo_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_nsaids_aceclo_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_nsaids_aceclo_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_nsaids_aceclo_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_nsaids_aceclo_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_nsaids_aceclo_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_nsaids_aceclo_comm']; ?>" name="history_drug_nsaids_aceclo_comm">
            </div>          
          </div>

           
          <div class="row" id="napro">
            <div class="col-md-2  m-b-5">
              <label>Naproxen </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_nsaids_napro_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_nsaids_napro_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_nsaids_napro_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_nsaids_napro_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_nsaids_napro_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_nsaids_napro_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_nsaids_napro_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_nsaids_napro_comm']; ?>" name="history_drug_nsaids_napro_comm">
            </div>          
          </div>

        </section>      
      </div>
    </div>
</section> 

<section id="eye_drops" style="padding-left:20px;">
    <h4>Eye Drops</h4> 
    <div class="btn-group">
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['tropip_m']==1){ echo 'checked';} ?> name="tropip_m" value="1" id="tropip_m">Tropicamide_P</label>    
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['tropi_m']==1){ echo 'checked';} ?> name="tropi_m" value="1" id="tropi_m">Tropicamide </label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['timolol_m']==1){ echo 'checked';} ?> name="timolol_m" value="1" id="timolol_m">Timolol </label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['homide_m']==1){ echo 'checked';} ?> name="homide_m" value="1" id="homide_m">Homide </label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['brimo_m']==1){ echo 'checked';} ?> name="brimo_m" value="1" id="brimo_m">Brimonidine </label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['latan_m']==1){ echo 'checked';} ?> name="latan_m" value="1" id="latan_m">Latanoprost </label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['travo_m']==1){ echo 'checked';} ?> name="travo_m" value="1" id="travo_m">Travoprost </label>    
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['tobra_m']==1){ echo 'checked';} ?> name="tobra_m" value="1" id="tobra_m">Tobramycin </label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['moxif_m']==1){ echo 'checked';} ?> name="moxif_m" value="1" id="moxif_m">Moxifloxacin </label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['homat_m']==1){ echo 'checked';} ?> name="homat_m" value="1" id="homat_m">Homatropine </label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['piloc_m']==1){ echo 'checked';} ?> name="piloc_m" value="1" id="piloc_m">Pilocarpine  </label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['cyclop_m']==1){ echo 'checked';} ?> name="cyclop_m" value="1" id="cyclop_m">Cyclopentolate  </label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['atrop_m']==1){ echo 'checked';} ?> name="atrop_m" value="1" id="atrop_m">Atropine </label> 
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['phenyl_m']==1){ echo 'checked';} ?> name="phenyl_m" value="1" id="phenyl_m">Phenylephrine  </label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['tropic_m']==1){ echo 'checked';} ?> name="tropic_m" value="1" id="tropic_m">Tropicacyl  </label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['parac_m']==1){ echo 'checked';} ?> name="parac_m" value="1" id="parac_m">Paracain  </label>
      <label class="btn-custom"><input type="checkbox" <?php if($drug_allergies['ciplox_m']==1){ echo 'checked';} ?> name="ciplox_m" value="1" id="ciplox_m">Ciplox   </label>    
    </div>     


    <div class="panel">
      <div class="panel-body">
        <section>
          <div class="row">
            <div class="col-md-2  m-b-5">
              <label>Name</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Duration</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Duration Unit</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Comments</label>
            </div>          
          </div>
        </section>

        <section>
          <div class="row" id="tropicp">
            <div class="col-md-2  m-b-5">
              <label>Tropicamide P  </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_tropicp_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_tropicp_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_tropicp_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_tropicp_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_tropicp_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_tropicp_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_tropicp_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_tropicp_comm']; ?>" name="history_drug_eye_tropicp_comm">
            </div>          
          </div>

          
          <div class="row" id="tropica">
            <div class="col-md-2  m-b-5">
              <label>Tropicamide</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_tropica_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_tropica_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_tropica_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_tropica_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_tropica_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_tropica_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_tropica_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_tropica_comm']; ?>" name="history_drug_eye_tropica_comm">
            </div>          
          </div>
          
          <div class="row" id="timol">
            <div class="col-md-2  m-b-5">
              <label>Timolol   </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_timol_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_timol_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_timol_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_timol_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_timol_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_timol_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_timol_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_timol_comm']; ?>" name="history_drug_eye_timol_comm">
            </div>          
          </div>
          
          <div class="row" id="homide">
            <div class="col-md-2  m-b-5">
              <label>Homide   </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_homide_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_homide_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_homide_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_homide_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_homide_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_homide_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_homide_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_homide_comm']; ?>" name="history_drug_eye_homide_comm">
            </div>          
          </div>
          
          <div class="row" id="brimon">
            <div class="col-md-2  m-b-5">
              <label>Brimonidine  </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_brimon_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_brimon_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_brimon_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_brimon_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_brimon_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_brimon_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_brimon_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_brimon_comm']; ?>" name="history_drug_eye_brimon_comm">
            </div>          
          </div>
          
          <div class="row" id="latan">
            <div class="col-md-2  m-b-5">
              <label>Latanoprost  </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_latan_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_latan_dur']==$i){ echo 'selected';}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_latan_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_latan_unit']=='Days'){ echo 'selected';}?>  value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_latan_unit']=='Weeks'){ echo 'selected';}?>  value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_latan_unit']=='Months'){ echo 'selected';}?>  value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_latan_unit']=='Years'){ echo 'selected';}?>  value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_latan_comm']; ?>" name="history_drug_eye_latan_comm">
            </div>          
          </div>
          
          <div class="row" id="travo">
            <div class="col-md-2  m-b-5">
              <label>Travoprost  </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_travo_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_travo_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_travo_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_travo_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_travo_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_travo_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_travo_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_travo_comm']; ?>" name="history_drug_eye_travo_comm">
            </div>          
          </div>
          
          <div class="row" id="tobra">
            <div class="col-md-2  m-b-5">
              <label>Tobramycin  </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_tobra_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_tobra_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_tobra_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_tobra_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_tobra_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_tobra_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_tobra_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_tobra_comm']; ?>" name="history_drug_eye_tobra_comm">
            </div>          
          </div>
          
          <div class="row" id="moxif">
            <div class="col-md-2  m-b-5">
              <label>Moxifloxacin  </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_moxif_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_moxif_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_moxif_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_moxif_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_moxif_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_moxif_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_moxif_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_moxif_comm']; ?>" name="history_drug_eye_moxif_comm">
            </div>          
          </div>
          
          <div class="row" id="homat">
            <div class="col-md-2  m-b-5">
              <label>Homatropine  </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_homat_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_homat_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_homat_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_homat_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_homat_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_homat_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_homat_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_homat_comm']; ?>" name="history_drug_eye_homat_comm">
            </div>          
          </div>
          
          <div class="row" id="piloca">
            <div class="col-md-2  m-b-5">
              <label>Pilocarpine  </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_piloca_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_piloca_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_piloca_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_piloca_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_piloca_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_piloca_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_piloca_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_piloca_comm']; ?>" name="history_drug_eye_piloca_comm">
            </div>          
          </div>
          
          <div class="row" id="cyclop">
            <div class="col-md-2  m-b-5">
              <label>Cyclopentolate   </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_cyclop_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_cyclop_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_cyclop_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_cyclop_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_cyclop_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_cyclop_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_cyclop_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $chief_complaints['history_chief_sen_burn_comm']; ?>" name="history_drug_eye_cyclop_comm">
            </div>          
          </div>
           
          <div class="row" id="atropi">
            <div class="col-md-2  m-b-5">
              <label>Atropine   </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_atropi_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_atropi_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_atropi_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_atropi_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_atropi_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_atropi_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_atropi_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_atropi_comm']; ?>" name="history_drug_eye_atropi_comm">
            </div>          
          </div>
           
          <div class="row" id="phenyl">
            <div class="col-md-2  m-b-5">
              <label>Phenylephrine   </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_phenyl_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_phenyl_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_phenyl_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_phenyl_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_phenyl_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_phenyl_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_phenyl_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_phenyl_comm']; ?>" name="history_drug_eye_phenyl_comm">
            </div>          
          </div>
           
          <div class="row" id="tropicac">
            <div class="col-md-2  m-b-5">
              <label>Tropicacyl   </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_tropicac_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_tropicac_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_tropicac_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_tropicac_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_tropicac_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_tropicac_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_tropicac_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_tropicac_comm']; ?>" name="history_drug_eye_tropicac_comm">
            </div>          
          </div>
           
          <div class="row" id="paracain">
            <div class="col-md-2  m-b-5">
              <label>Paracain   </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_paracain_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_paracain_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_paracain_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_paracain_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_paracain_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_paracain_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_paracain_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_paracain_comm']; ?>" name="history_drug_eye_paracain_comm">
            </div>          
          </div>
           
          <div class="row" id="ciplox">
            <div class="col-md-2  m-b-5">
              <label>Ciplox   </label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="form-control" name="history_drug_eye_ciplox_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($drug_allergies['history_drug_eye_ciplox_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="form-control" name="history_drug_eye_ciplox_unit">
                <option value="">Please Select</option>
                <option <?php if($drug_allergies['history_drug_eye_ciplox_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($drug_allergies['history_drug_eye_ciplox_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($drug_allergies['history_drug_eye_ciplox_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($drug_allergies['history_drug_eye_ciplox_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $drug_allergies['history_drug_eye_ciplox_comm']; ?>" name="history_drug_eye_ciplox_comm">
            </div>          
          </div>
        </section>      
      </div>
    </div>
</section>
  <br><br> 

<section>
    <h4>Contact Allergies</h4> 
    <div class="btn-group">
      <label class="btn-custom"><input type="checkbox" <?php if($contact_allergies['alco_m']==1){ echo 'checked';}?> value="1" name="alco_m" id="alco_m">Alcohol</label>    
      <label class="btn-custom"><input type="checkbox" <?php if($contact_allergies['latex_m']==1){ echo 'checked';}?> value="1" name="latex_m" id="latex_m">Latex</label>
      <label class="btn-custom"><input type="checkbox" <?php if($contact_allergies['betad_m']==1){ echo 'checked';}?> value="1" name="betad_m" id="betad_m">Betadine</label>
      <label class="btn-custom"><input type="checkbox" <?php if($contact_allergies['adhes_m']==1){ echo 'checked';}?> value="1" name="adhes_m" id="adhes_m">Adhesive Tape</label>
      <label class="btn-custom"><input type="checkbox" <?php if($contact_allergies['tegad_m']==1){ echo 'checked';}?> value="1" name="tegad_m" id="tegad_m">Tegaderm</label>
      <label class="btn-custom"><input type="checkbox" <?php if($contact_allergies['trans_m']==1){ echo 'checked';}?> value="1" name="trans_m" id="trans_m">Transpore</label>            
    </div>     


    <div class="panel">
      <div class="panel-body">
        <section>
          <div class="row">
            <div class="col-md-2  m-b-5">
              <label>Name</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Duration</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Duration Unit</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Comments</label>
            </div>           
          </div>
        </section>

        <section>
          
          <div class="row" id="alcohol">
            <div class="col-md-2  m-b-5">
              <label>Alcohol</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="w-60px" name="history_contact_alcohol_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($contact_allergies['history_contact_alcohol_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="w-60px" name="history_contact_alcohol_unit">
                <option value="">Please Select</option>
                <option <?php if($contact_allergies['history_contact_alcohol_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($contact_allergies['history_contact_alcohol_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($contact_allergies['history_contact_alcohol_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($contact_allergies['history_contact_alcohol_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div>           

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $contact_allergies['history_contact_alcohol_comm']; ?>" name="history_contact_alcohol_comm">
            </div>           
          </div>
            

           
          <div class="row" id="latex">
            <div class="col-md-2  m-b-5">
              <label>Latex</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="w-60px" name="history_contact_latex_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($contact_allergies['history_contact_latex_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="w-60px" name="history_contact_latex_unit">
                <option value="">Please Select</option>
                <option <?php if($contact_allergies['history_contact_latex_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($contact_allergies['history_contact_latex_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($contact_allergies['history_contact_latex_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($contact_allergies['history_contact_latex_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $contact_allergies['history_contact_latex_comm']; ?>" name="history_contact_latex_comm">
            </div>          
          </div>

           
          <div class="row" id="betad">
            <div class="col-md-2  m-b-5">
              <label>Betadine</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="w-60px" name="history_contact_betad_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($contact_allergies['history_contact_betad_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="w-60px" name="history_contact_betad_unit">
                <option value="">Please Select</option>
                <option <?php if($contact_allergies['history_contact_betad_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($contact_allergies['history_contact_betad_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($contact_allergies['history_contact_betad_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($contact_allergies['history_contact_betad_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $contact_allergies['history_contact_betad_comm']; ?>" name="history_contact_betad_comm">
            </div>          
          </div>

          <div class="row" id="adhes">
            <div class="col-md-2  m-b-5">
              <label>Adhesive Tape</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="w-60px" name="history_contact_adhes_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($contact_allergies['history_contact_adhes_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="w-60px" name="history_contact_adhes_unit">
                <option value="">Please Select</option>
                <option <?php if($contact_allergies['history_contact_adhes_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($contact_allergies['history_contact_adhes_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($contact_allergies['history_contact_adhes_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option  <?php if($contact_allergies['history_contact_adhes_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $contact_allergies['history_contact_adhes_comm']; ?>" name="history_contact_adhes_comm">
            </div>          
          </div>

           
          <div class="row" id="tegad">
            <div class="col-md-2  m-b-5">
              <label>Tegaderm</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="w-60px" name="history_contact_tegad_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($contact_allergies['history_contact_tegad_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="w-60px" name="history_contact_tegad_unit">
                <option value="">Please Select</option>
                <option <?php if($contact_allergies['history_contact_tegad_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($contact_allergies['history_contact_tegad_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($contact_allergies['history_contact_tegad_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($contact_allergies['history_contact_tegad_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $contact_allergies['history_contact_tegad_comm']; ?>" name="history_contact_tegad_comm">
            </div>          
          </div>

           
          <div class="row" id="transp">
            <div class="col-md-2  m-b-5">
              <label>Transpore</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="w-60px" name="history_contact_transp_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($contact_allergies['history_contact_transp_dur']==$i){ echo 'selected';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select>  
            </div>
            <div class="col-md-2 m-b-5">
              <select class="w-60px" name="history_contact_transp_unit">
                <option value="">Please Select</option>
                <option <?php if($contact_allergies['history_contact_transp_unit']=='Days'){ echo 'selected';}?> value="Days">Days</option>
                <option <?php if($contact_allergies['history_contact_transp_unit']=='Weeks'){ echo 'selected';}?> value="Weeks">Weeks</option>
                <option <?php if($contact_allergies['history_contact_transp_unit']=='Months'){ echo 'selected';}?> value="Months">Months</option>
                <option <?php if($contact_allergies['history_contact_transp_unit']=='Years'){ echo 'selected';}?> value="Years">Years</option>
              </select>
            </div>          

            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $contact_allergies['history_contact_transp_comm']; ?>" name="history_contact_transp_comm">
            </div>          
          </div>

        </section>       
      </div>
    </div>
</section>

<section>
    <h4>Food Allergies</h4> 
    <div class="btn-group">
      <label class="btn-custom"><input type="checkbox" <?php if($food_allergies['seaf_m']==1){ echo 'checked';} ?> name="seaf_m" value="1" id="seaf_m">All Seafood</label>    
      <label class="btn-custom"><input type="checkbox" <?php if($food_allergies['corn_m']==1){ echo 'checked';} ?> name="corn_m" value="1" id="corn_m">Corn</label>
      <label class="btn-custom"><input type="checkbox" <?php if($food_allergies['egg_m']==1){ echo 'checked';} ?> name="egg_m" value="1" id="egg_m">Egg</label>
      <label class="btn-custom"><input type="checkbox" <?php if($food_allergies['milk_m']==1){ echo 'checked';} ?> name="milk_m" value="1" id="milk_m">Milk Proteins</label>
      <label class="btn-custom"><input type="checkbox" <?php if($food_allergies['pean_m']==1){ echo 'checked';} ?> name="pean_m" value="1" id="pean_m">Peanuts</label>
      <label class="btn-custom"><input type="checkbox" <?php if($food_allergies['shell_m']==1){ echo 'checked';} ?> name="shell_m" value="1" id="shell_m">Shellfish Only</label>
      <label class="btn-custom"><input type="checkbox" <?php if($food_allergies['soy_m']==1){ echo 'checked';} ?> name="soy_m" value="1" id="soy_m">Soy Protein</label>
      <label class="btn-custom"><input type="checkbox" <?php if($food_allergies['lact_m']==1){ echo 'checked';} ?> name="lact_m" value="1" id="lact_m">Lactose</label>
      <label class="btn-custom"><input type="checkbox" <?php if($food_allergies['mush_m']==1){ echo 'checked';} ?> name="mush_m" value="1" id="mush_m">Mushroom</label>            
    </div>     


    <div class="panel">
      <div class="panel-body">
        <section>
          <div class="row">
            <div class="col-md-2  m-b-5">
              <label>Name</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Duration</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Duration Unit</label>
            </div>
            <div class="col-md-2  m-b-5">
              <label>Comments</label>
            </div>           
          </div>
        </section>

        <section>
          <div class="row" id="seaf">
            <div class="col-md-2  m-b-5">
              <label>All Seafood</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="w-60px" name="history_food_seaf_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($food_allergies['history_food_seaf_dur']==$i){ echo 'selected';} ?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="w-60px" name="history_food_seaf_unit">
                <option value="">Please Select</option>
                <option <?php if($food_allergies['history_food_seaf_unit']=='Days'){ echo 'selected';} ?> value="Days">Days</option>
                <option <?php if($food_allergies['history_food_seaf_unit']=='Weeks'){ echo 'selected';} ?> value="Weeks">Weeks</option>
                <option <?php if($food_allergies['history_food_seaf_unit']=='Months'){ echo 'selected';} ?> value="Months">Months</option>
                <option <?php if($food_allergies['history_food_seaf_unit']=='Years'){ echo 'selected';} ?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $food_allergies['history_food_seaf_comm']; ?>" name="history_food_seaf_comm">
            </div>           
          </div>
          
            
          <div class="row" id="corn">
            <div class="col-md-2  m-b-5">
              <label>Corn</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="w-60px" name="history_food_corn_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($food_allergies['history_food_corn_dur']==$i){ echo 'selected';} ?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="w-60px" name="history_food_corn_unit">
                <option value="">Please Select</option>
                <option <?php if($food_allergies['history_food_corn_unit']=='Days'){ echo 'selected';} ?> value="Days">Days</option>
                <option <?php if($food_allergies['history_food_corn_unit']=='Weeks'){ echo 'selected';} ?> value="Weeks">Weeks</option>
                <option <?php if($food_allergies['history_food_corn_unit']=='Months'){ echo 'selected';} ?> value="Months">Months</option>
                <option <?php if($food_allergies['history_food_corn_unit']=='Years'){ echo 'selected';} ?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $food_allergies['history_food_corn_comm']; ?>" name="history_food_corn_comm">
            </div>          
          </div>
          
            
          <div class="row" id="egg">
            <div class="col-md-2  m-b-5">
              <label>Egg</label>
            </div>
            <div class="col-md-2  m-b-5">
               <select class="w-60px" name="history_food_egg_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($food_allergies['history_food_egg_dur']==$i){ echo 'selected';} ?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="w-60px" name="history_food_egg_unit">
                <option value="">Please Select</option>
                <option <?php if($food_allergies['history_food_egg_unit']=='Days'){ echo 'selected';} ?> value="Days">Days</option>
                <option <?php if($food_allergies['history_food_egg_unit']=='Weeks'){ echo 'selected';} ?> value="Weeks">Weeks</option>
                <option <?php if($food_allergies['history_food_egg_unit']=='Months'){ echo 'selected';} ?> value="Months">Months</option>
                <option <?php if($food_allergies['history_food_egg_unit']=='Years'){ echo 'selected';} ?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $food_allergies['history_food_egg_comm']; ?>" name="history_food_egg_comm">
            </div>          
          </div>

          <div class="row" id="milk_p">
            <div class="col-md-2  m-b-5">
              <label>Milk Proteins</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="w-60px" name="history_food_milk_p_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($food_allergies['history_food_milk_p_dur']==$i){ echo 'selected';} ?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="w-60px" name="history_food_milk_p_unit">
                <option value="">Please Select</option>
                <option <?php if($food_allergies['history_food_milk_p_unit']=='Days'){ echo 'selected';} ?> value="Days">Days</option>
                <option <?php if($food_allergies['history_food_milk_p_unit']=='Weeks'){ echo 'selected';} ?> value="Weeks">Weeks</option>
                <option <?php if($food_allergies['history_food_milk_p_unit']=='Months'){ echo 'selected';} ?> value="Months">Months</option>
                <option <?php if($food_allergies['history_food_milk_p_unit']=='Years'){ echo 'selected';} ?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $food_allergies['history_food_milk_p_comm']; ?>" name="history_food_milk_p_comm">
            </div>          
          </div>

            
          <div class="row" id="pean">
            <div class="col-md-2  m-b-5">
              <label>Peanuts</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="w-60px" name="history_food_pean_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($food_allergies['history_food_pean_dur']==$i){ echo 'selected';} ?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="w-60px" name="history_food_pean_unit">
                <option value="">Please Select</option>
                <option <?php if($food_allergies['history_food_pean_unit']=='Days'){ echo 'selected';} ?> value="Days">Days</option>
                <option <?php if($food_allergies['history_food_pean_unit']=='Weeks'){ echo 'selected';} ?> value="Weeks">Weeks</option>
                <option <?php if($food_allergies['history_food_pean_unit']=='Months'){ echo 'selected';} ?> value="Months">Months</option>
                <option <?php if($food_allergies['history_food_pean_unit']=='Years'){ echo 'selected';} ?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $food_allergies['history_food_pean_comm']; ?>" name="history_food_pean_comm">
            </div>          
          </div>

          <div class="row" id="shell">
            <div class="col-md-2  m-b-5">
              <label>Shellfish Only</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="w-60px" name="history_food_shell_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($food_allergies['history_food_shell_dur']==$i){ echo 'selected';} ?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="w-60px" name="history_food_shell_unit">
                <option value="">Please Select</option>
                <option <?php if($food_allergies['history_food_shell_unit']=='Days'){ echo 'selected';} ?> value="Days">Days</option>
                <option <?php if($food_allergies['history_food_shell_unit']=='Weeks'){ echo 'selected';} ?> value="Weeks">Weeks</option>
                <option <?php if($food_allergies['history_food_shell_unit']=='Months'){ echo 'selected';} ?> value="Months">Months</option>
                <option <?php if($food_allergies['history_food_shell_unit']=='Years'){ echo 'selected';} ?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $food_allergies['history_food_shell_comm']; ?>" name="history_food_shell_comm">
            </div>          
          </div>

          <div class="row" id="soy">
            <div class="col-md-2  m-b-5">
              <label>Soy Protein</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="w-60px" name="history_food_soy_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($food_allergies['history_food_soy_dur']==$i){ echo 'selected';} ?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="w-60px" name="history_food_soy_unit">
                <option value="">Please Select</option>
                <option <?php if($food_allergies['history_food_soy_unit']=='Days'){ echo 'selected';} ?> value="Days">Days</option>
                <option <?php if($food_allergies['history_food_soy_unit']=='Weeks'){ echo 'selected';} ?> value="Weeks">Weeks</option>
                <option <?php if($food_allergies['history_food_soy_unit']=='Months'){ echo 'selected';} ?> value="Months">Months</option>
                <option <?php if($food_allergies['history_food_soy_unit']=='Years'){ echo 'selected';} ?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $food_allergies['history_food_soy_comm']; ?>" name="history_food_soy_comm">
            </div>          
          </div>
            
          <div class="row" id="lact">
            <div class="col-md-2  m-b-5">
              <label>Lactose</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="w-60px" name="history_food_lact_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($food_allergies['history_food_lact_dur']==$i){ echo 'selected';} ?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="w-60px" name="history_food_lact_unit">
                <option value="">Please Select</option>
                <option <?php if($food_allergies['history_food_lact_unit']=='Days'){ echo 'selected';} ?> value="Days">Days</option>
                <option <?php if($food_allergies['history_food_lact_unit']=='Weeks'){ echo 'selected';} ?> value="Weeks">Weeks</option>
                <option <?php if($food_allergies['history_food_lact_unit']=='Months'){ echo 'selected';} ?> value="Months">Months</option>
                <option <?php if($food_allergies['history_food_lact_unit']=='Years'){ echo 'selected';} ?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $food_allergies['history_food_lact_comm']; ?>" name="history_food_lact_comm">
            </div>          
          </div>
            
          <div class="row" id="mush">
            <div class="col-md-2  m-b-5">
              <label>Mushroom</label>
            </div>
            <div class="col-md-2  m-b-5">
              <select class="w-60px" name="history_food_mush_dur">
               <option value="">Please Select</option>
               <?php for($i=1; $i<=40;$i++) { ?>
                 <option <?php if($food_allergies['history_food_mush_dur']==$i){ echo 'selected';} ?> value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php } ?>               
            </select> 
            </div>
            <div class="col-md-2 m-b-5">
              <select class="w-60px" name="history_food_mush_unit">
                <option value="">Please Select</option>
                <option <?php if($food_allergies['history_food_mush_unit']=='Days'){ echo 'selected';} ?> value="Days">Days</option>
                <option <?php if($food_allergies['history_food_mush_unit']=='Weeks'){ echo 'selected';} ?> value="Weeks">Weeks</option>
                <option <?php if($food_allergies['history_food_mush_unit']=='Months'){ echo 'selected';} ?> value="Months">Months</option>
                <option <?php if($food_allergies['history_food_mush_unit']=='Years'){ echo 'selected';} ?> value="Years">Years</option>
              </select>
            </div> 
            <div class="col-md-2  m-b-5">
              <input type="text" placeholder="Comment..." value="<?php echo $food_allergies['history_food_mush_comm']; ?>" name="history_food_mush_comm">
            </div>          
          </div>



        </section>
        <section>
         <div class="row">
           <div class="col-md-2">
             Other
           </div>
           <div class="col-md-8">
             <input class="form-control" placeholder="Comment..." value="<?php echo $food_allergies['history_food_comm']; ?>" type="text" name="history_food_comm">
           </div>

         </div>
       </section>       
      </div>
    </div>
</section>
  

<section>
    <h4>Vital Signs</h4>

    <div class="panel">
      <div class="panel-body">
        <section>
          <div class="row">
            <div class="col-md-3 ">
              <label>Temperature</label>
            </div>
            <div class="col-md-3 ">
              <label>Pulse</label>
            </div>
            <div class="col-md-3 ">
              <label>Blood Pressure</label>
            </div>
            <div class="col-md-3 ">
              <label>RR</label>
            </div>                       
          </div>

          <div class="row">
            <div class="col-md-3 ">
              <div class="input-group">
                <input type="number" class="form-control form-control-lg" name="history_vital_temp" id="history_vital_temp" value="<?php echo $history['temperature'];?>">
                <span class="input-group-addon">&deg;C</span>
                <input type="hidden" name="history_vital_temp_update" id="history_vital_temp_update" value="<?php echo $food_allergies['history_vital_temp_update'];?>">
              </div>
            </div>
            <div class="col-md-3 ">
              <div class="input-group">
                <input type="number" class="form-control form-control-lg" name="history_vital_pulse" id="history_vital_pulse" value="<?php echo $history['pulse']?>">
                <span class="input-group-addon">bpm</span>
                <input type="hidden" name="history_vital_pulse_update" id="history_vital_pulse_update" value="<?php echo $food_allergies['history_vital_temp_update'];?>">
              </div> 
            </div>
            <div class="col-md-3 ">
              <div class="input-group">
                <input type="number" class="form-control form-control-lg" name="history_vital_bp" id="history_vital_bp" value="<?php echo $history['blood_pressure']?>">
                <span class="input-group-addon">mmHg</span>
                <input type="hidden" name="history_vital_bp_update" id="history_vital_bp_update" value="<?php echo $food_allergies['history_vital_bp_update'];?>">
              </div>
            </div>
            <div class="col-md-3 ">
              <div class="input-group">
                <input type="number" class="form-control form-control-lg" name="history_vital_rr" value="<?php echo $history['rr']?>">
                <span class="input-group-addon">brpm</span>
              </div>
            </div>                       
          </div>
        </section>             
      </div>
    </div>    
</section> 


<section>
    <h4>Anthropometry</h4>

    <div class="panel">
      <div class="panel-body">
        <section>
          <div class="row">
            <div class="col-md-3 mb-5">
              <label>Height</label>
            </div>
            <div class="col-md-3 mb-5">
              <label>Weight</label>
            </div>
            <div class="col-md-3 mb-5">
              <label>BMI</label>
            </div>                
          </div>

          <div class="row">
            <div class="col-md-3 mb-5">
              <div class="input-group">
                <input type="number" class="form-control form-control-lg" name="history_anthropometry_height" value="<?php echo $history['height']?>" id="height" onKeyUp="myFunction()">
                <span class="input-group-addon">cms</span>
              </div>
            </div>
            <div class="col-md-3 mb-5">
              <div class="input-group">
                <input type="number" class="form-control form-control-lg" name="history_anthropometry_weight" value="<?php echo $history['weight']?>" id="weight" onKeyUp="myFunction()">
                <span class="input-group-addon">kg</span>
              </div>
            </div>
            <div class="col-md-3 mb-5">
              <div class="input-group">
                <input type="text" class="form-control form-control-lg" name="history_anthropometry_bmi" value="<?php echo $history['bmi']?>" id="bmi_calculate"> 
                <span class="input-group-addon">kg/m2</span>
              </div>
            </div>                                  
          </div>

          
        </section> 
         
         <section>
         <div class="row">
           <div class="col-md-2">
             Comments
           </div>
           <div class="col-md-10">
             <textarea class="form-control w-100" name="history_anthropometry_comm"><?php echo $history['comment']?></textarea>
           </div>

         </div>
       </section>
      </div>
    </div>    
</section>  
<script>
$(document).ready(function(){

  var presid='<?php echo $pres_id;?>';
  if(presid !='')
  {
    $('#history_vital_temp').change(function() {
      $('#history_vital_temp_update').val('red');
    });
    $('#history_vital_pulse').change(function() {
      $('#history_vital_pulse_update').val('red');
    });
    $('#history_vital_bp').change(function() {
      $('#history_vital_bp_update').val('red');
    }); 
  }

  $('#pains, #blurr, #rednes, #injuries, #waterings, #discharges,#dryness, #itchings, #fbsensation, #dev_squint, #head_strain, #size_shape, #ovs, #sdiv, #doe, #swell, #sen_burn, #glau, #renti_d, #glas, #eye_d, #eye_s, #uvei, #renti_l, #diab, #hyper, #smokt, #alcoh, #cardd, #steri, #drug, #hiva, #cantu, #tuberc, #asthm, #cncds, #hypo, #hyperth, #heptc, #rend, #acid, #onins, #oasbth, #consan, #thyrd, #chewt, #antimi_agen, #antif_agen, #ant_agen, #nsaids, #eye_drops, #ampici, #amoxi, #ceftr, #ciprof, #clarith, #cotri, #ethamb, #isoni, #metron, #penic, #rifam, #strept, #ketoco, #flucon, #itrac,  #acyclo, #efavir, #enfuv, #nelfin, #nevira, #zidov, #aspirin, #paracet, #ibupro, #diclo, #aceclo, #napro, #alcohol, #latex, #betad, #adhes, #tegad, #transp,  #seaf, #corn, #egg, #milk_p, #pean, #shell, #soy, #lact, #mush, #tropicp, #tropica, #timol, #homide, #brimon, #latan, #travo, #tobra, #moxif, #homat, #piloca, #cyclop, #atropi, #phenyl, #tropicac, #paracain, #ciplox').css('display','none');
  //
    $("#pain_m").click(function(){
       $(this).parent().toggleClass('bg-theme');
       $("#pains").toggle();
    });
    //
    $("#bdv_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#blurr").toggle();
    });
    //
    $("#redness_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#rednes").toggle();
    });
    //
    $("#injury_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#injuries").toggle();
    });
    //
    $("#water_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#waterings").toggle();
    });
    //
    $("#discharge_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#discharges").toggle();
    });
    //
    $("#dryness_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#dryness").toggle();
    });
    //
    $("#itch_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#itchings").toggle();
    });
     //
    $("#fbd_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#fbsensation").toggle();
    });
     //
    $("#devs_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#dev_squint").toggle();
    });
     //
    $("#heads_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#head_strain").toggle();
    });
     //
    $("#canss_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#size_shape").toggle();
    });
     //
    $("#ovs_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#ovs").toggle();
    });
     //
    $("#sdv_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#sdiv").toggle();
    });
     //
    $("#doe_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#doe").toggle();
    });
    //
    $("#swel_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#swell").toggle();
    });
    //
    $("#burns_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#sen_burn").toggle();
    });
    //
    $("#gla_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#glau").toggle();
    });
    //
    $("#reti_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#renti_d").toggle();
    });
    //
    $("#glass_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#glas").toggle();
    });
    //
    $("#eyedi_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#eye_d").toggle();
    });
    //
    $("#eyesu_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#eye_s").toggle();
    });
    //
    $("#uve_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#uvei").toggle();
    });
    //
    $("#retil_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#renti_l").toggle();
    });
    //
    $("#dia_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#diab").toggle();
    });
    //
    $("#hyper_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#hyper").toggle();
    });
    //
    $("#alcoh_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#alcoh").toggle();
    });
    //
    $("#smok_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
      $("#smokt").toggle();
    });
    //
    $("#card_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#cardd").toggle();
    });
    //
    $("#steri_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#steri").toggle();
    });

    //
    $("#drug_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#drug").toggle();
    });//
    $("#hiva_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
      $("#hiva").toggle();
    });
       
    //
    $("#cant_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#cantu").toggle();
    });
    //
    $("#tuber_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#tuberc").toggle();
    });
    //
    $("#asth_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#asthm").toggle();
    });
    //
    $("#cnsds_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#cncds").toggle();
    });
    //
    $("#hypo_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#hypo").toggle();
    });
    //
    $("#hyperth_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#hyperth").toggle();
    });
    //
    $("#hepac_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#heptc").toggle();
    });
    //
    $("#renald_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#rend").toggle();
    });
    //
    $("#acid_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#acid").toggle();
    });
    //
    $("#oins_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#onins").toggle();
    });
    //
    $("#oasp_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#oasbth").toggle();
    });
    //
    $("#acon_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#consan").toggle();
    });
    //
    $("#thd_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#thyrd").toggle();
    });
    //
    $("#chewt_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#chewt").toggle();
    });

      //
    $("#antimi_agen_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#antimi_agen").toggle();
    });
    //
    $("#antif_agen_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#antif_agen").toggle();
    });
    //
    $("#ant_agen_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#ant_agen").toggle();
    });
    //
    $("#nsaids_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#nsaids").toggle();
    });
    //
    $("#eye_drops_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#eye_drops").toggle();
    });
     //
    $("#ampic_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#ampici").toggle();
    });
     //
    $("#amox_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#amoxi").toggle();
    });
     //
    $("#ceftr_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#ceftr").toggle();
    });
     //
    $("#cipro_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#ciprof").toggle();
    });
     //
    $("#clari_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#clarith").toggle();
    });
     //
    $("#cotri_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#cotri").toggle();
    }); //
    $("#etham_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#ethamb").toggle();
    });
     //
    $("#ison_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#isoni").toggle();
    });
     //
    $("#metro_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#metron").toggle();
    });
     //
    $("#penic_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#penic").toggle();
    });
     //
    $("#rifa_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#rifam").toggle();
    });
     //
    $("#ketoc_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
      $("#ketoco").toggle();
    });  
    //
   $("#fluco_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#flucon").toggle();
    });
   //
   $("#itrac_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#itrac").toggle();
    });
     //
    $("#acyclo_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
      $("#acyclo").toggle();
    });  
    //
   $("#efavir_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#efavir").toggle();
    });
   //
   $("#enfuv_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#enfuv").toggle();
    });
    //
    $("#nelfin_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
      $("#nelfin").toggle();
    });  
    //
   $("#nevira_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#nevira").toggle();
    });
   //
   $("#zidov_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#zidov").toggle();
    });

     //
    $("#aspirin_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
      $("#aspirin").toggle();
    });  
    //
   $("#paracet_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#paracet").toggle();
    });
   //
   $("#ibupro_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#ibupro").toggle();
    });
    //
    $("#diclo_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
      $("#diclo").toggle();
    });  
    //
   $("#aceclo_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#aceclo").toggle();
    });
   //
   $("#napro_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#napro").toggle();
    });

    //
    $("#strep_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#strept").toggle();
    });
      //
    $("#tropip_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#tropicp").toggle();
    });
    //
    $("#tropi_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#tropica").toggle();
    });
    //
    $("#timolol_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#timol").toggle();
    });
    //
    $("#homide_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#homide").toggle();
    });
    //
    $("#brimo_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#brimon").toggle();
    });
    //
    $("#latan_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#latan").toggle();
    });
    //
    $("#travo_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#travo").toggle();
    });
    //
    $("#tobra_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#tobra").toggle();
    });
    //
    $("#moxif_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#moxif").toggle();
    });
    //
    $("#homat_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#homat").toggle();
    });
    //
    $("#piloc_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#piloca").toggle();
    });
    //
    $("#cyclop_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#cyclop").toggle();
    });
    //
    $("#atrop_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#atropi").toggle();
    });
    //
    $("#phenyl_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#phenyl").toggle();
    });
    //
    $("#tropic_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#tropicac").toggle();
    });
    //
    $("#parac_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#paracain").toggle();
    });
    //
    $("#ciplox_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#ciplox").toggle();
    });

    //
    $("#alco_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#alcohol").toggle();
    });
    //
    $("#latex_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#latex").toggle();
    });
    //
    $("#betad_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#betad").toggle();
    });
    //
    $("#adhes_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#adhes").toggle();
    });
    //
    $("#tegad_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#tegad").toggle();
    });
    //
    $("#trans_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#transp").toggle();
    });

     //
    $("#seaf_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#seaf").toggle();
    });
     //
    $("#corn_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#corn").toggle();
    });
     //
    $("#egg_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#egg").toggle();
    });
     //
    $("#milk_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#milk_p").toggle();
    });
     //
    $("#pean_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#pean").toggle();
    });
     //
    $("#shell_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#shell").toggle();
    });
     //
    $("#soy_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#soy").toggle();
    });
     //
    $("#lact_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#lact").toggle();
    });
     //
    $("#mush_m").click(function(){
      $(this).parent().toggleClass('bg-theme');
       $("#mush").toggle();
    });
    //
    $("#history_chief_ovs_glare").click(function(){
      $(this).parent().toggleClass('bg-theme');
    });
    //
    $("#history_chief_ovs_floaters").click(function(){
      $(this).parent().toggleClass('bg-theme');
    });
    //
    $("#history_chief_ovs_photophobia").click(function(){
      $(this).parent().toggleClass('bg-theme');
    });
    //
    $("#history_chief_ovs_color_halos").click(function(){
      $(this).parent().toggleClass('bg-theme');

    });
    
       //
    $("#history_chief_ovs_metamorphopsia").click(function(){
      $(this).parent().toggleClass('bg-theme');
    });
    //
    $("#history_chief_ovs_chromatopsia").click(function(){
      $(this).parent().toggleClass('bg-theme');
    });
    //
    $("#history_chief_ovs_dnv").click(function(){
      $(this).parent().toggleClass('bg-theme');
    });
     //
    $("#history_chief_ovs_ddv").click(function(){
      $(this).parent().toggleClass('bg-theme');

    });
     //
    $("#history_chief_blurr_dist").click(function(){
      $(this).parent().toggleClass('bg-theme');

    });
    //
    $("#history_chief_blurr_near").click(function(){
      $(this).parent().toggleClass('bg-theme');
    });
     //
    $("#history_chief_blurr_pain").click(function(){
      $(this).parent().toggleClass('bg-theme');
    });
       //
    $("#history_chief_blurr_ug").click(function(){
      $(this).parent().toggleClass('bg-theme');
    });
     //
    $("#history_chief_dev_diplopia").click(function(){
      $(this).parent().toggleClass('bg-theme');
    });
      //
    $("#history_chief_dev_truma").click(function(){
      $(this).parent().toggleClass('bg-theme');
    });
      //
    $("#history_chief_dev_ps").click(function(){
      $(this).parent().toggleClass('bg-theme');
    });
    //
    $('.ht_btn').click(function(){
      $('.ht_btn').removeClass('btn-save');
      $(this).addClass('btn-save');
    });
});

$(document).ready(function(){
var pain_m = '<?php echo $chief_complaints['pain_m'];?>';
var bdv_m = '<?php echo $chief_complaints['bdv_m'];?>';
var redness_m='<?php echo $chief_complaints['redness_m'];?>';
var injury_m ='<?php echo $chief_complaints['injury_m'];?>';
var water_m ='<?php echo $chief_complaints['water_m'];?>';
var discharge_m ='<?php echo $chief_complaints['discharge_m'];?>';
var dryness_m ='<?php echo $chief_complaints['dryness_m'];?>';
var itch_m ='<?php echo $chief_complaints['itch_m'];?>'; 
var fbd_m = '<?php echo $chief_complaints['fbd_m'];?>'; 
var devs_m ='<?php echo $chief_complaints['devs_m'];?>'; 
var heads_m ='<?php echo $chief_complaints['heads_m'];?>'; 
var canss_m ='<?php echo $chief_complaints['canss_m'];?>'; 
var ovs_m ='<?php echo $chief_complaints['ovs_m'];?>'; 
var sdv_m ='<?php echo $chief_complaints['sdv_m'];?>';
var doe_m ='<?php echo $chief_complaints['doe_m'];?>';
var swel_m ='<?php echo $chief_complaints['swel_m'];?>'; 
var burns_m ='<?php echo $chief_complaints['burns_m'];?>'; 
var history_chief_blurr_dist ='<?php echo $chief_complaints['history_chief_blurr_dist'];?>';
var history_chief_blurr_near ='<?php echo $chief_complaints['history_chief_blurr_near'];?>';
var history_chief_blurr_pain ='<?php echo $chief_complaints['history_chief_blurr_pain'];?>';
var history_chief_blurr_ug ='<?php echo $chief_complaints['history_chief_blurr_ug'];?>';
var history_chief_dev_diplopia ='<?php echo $chief_complaints['history_chief_dev_diplopia'];?>';
var history_chief_dev_truma ='<?php echo $chief_complaints['history_chief_dev_truma'];?>'; 
var history_chief_dev_ps ='<?php echo $chief_complaints['history_chief_dev_ps'];?>';
var history_chief_ovs_glare ='<?php echo $chief_complaints['history_chief_ovs_glare'];?>'; 
var history_chief_ovs_floaters ='<?php echo $chief_complaints['history_chief_ovs_floaters'];?>';
var history_chief_ovs_photophobia ='<?php echo $chief_complaints['history_chief_ovs_photophobia'];?>';
var history_chief_ovs_color_halos ='<?php echo $chief_complaints['history_chief_ovs_color_halos'];?>';
var history_chief_ovs_metamorphopsia='<?php echo $chief_complaints['history_chief_ovs_metamorphopsia'];?>';
var history_chief_ovs_chromatopsia ='<?php echo $chief_complaints['history_chief_ovs_chromatopsia'];?>';
var history_chief_ovs_dnv ='<?php echo $chief_complaints['history_chief_ovs_dnv'];?>';
var history_chief_ovs_ddv ='<?php echo $chief_complaints['history_chief_ovs_ddv'];?>';
var gla_m = '<?php echo $ophthalmic['gla_m'];?>';
var reti_m = '<?php echo $ophthalmic['reti_m'];?>';
var glass_m = '<?php echo $ophthalmic['glass_m'];?>';
var eyedi_m = '<?php echo $ophthalmic['eyedi_m'];?>';
var eyesu_m = '<?php echo $ophthalmic['eyesu_m'];?>';
var uve_m = '<?php echo $ophthalmic['uve_m'];?>';
var retil_m = '<?php echo $ophthalmic['retil_m'];?>';
var dia_m ='<?php echo $systemic['dia_m'];?>';
var hyper_m ='<?php echo $systemic['hyper_m'];?>';
var alcoh_m ='<?php echo $systemic['alcoh_m'];?>';
var smok_m ='<?php echo $systemic['smok_m'];?>';
var card_m ='<?php echo $systemic['card_m'];?>';
var steri_m ='<?php echo $systemic['steri_m'];?>';
var drug_m ='<?php echo $systemic['drug_m'];?>';
var hiva_m ='<?php echo $systemic['hiva_m'];?>';
var cant_m ='<?php echo $systemic['cant_m'];?>';
var tuber_m ='<?php echo $systemic['tuber_m'];?>';
var asth_m ='<?php echo $systemic['asth_m'];?>';
var cnsds_m ='<?php echo $systemic['cnsds_m'];?>';
var hypo_m ='<?php echo $systemic['hypo_m'];?>';
var hyperth_m ='<?php echo $systemic['hyperth_m'];?>';
var hepac_m ='<?php echo $systemic['hepac_m'];?>';
var renald_m ='<?php echo $systemic['renald_m'];?>';
var acid_m ='<?php echo $systemic['acid_m'];?>';
var oins_m ='<?php echo $systemic['oins_m'];?>';
var oasp_m ='<?php echo $systemic['oasp_m'];?>';
var acon_m ='<?php echo $systemic['acon_m'];?>';
var thd_m ='<?php echo $systemic['thd_m'];?>';
var chewt_m ='<?php echo $systemic['chewt_m'];?>';
var antimi_agen_m ='<?php echo $drug_allergies['antimi_agen_m'];?>';
var antif_agen_m ='<?php echo $drug_allergies['antif_agen_m'];?>';
var ant_agen_m ='<?php echo $drug_allergies['ant_agen_m'];?>';
var nsaids_m ='<?php echo $drug_allergies['nsaids_m'];?>';
var eye_drops_m ='<?php echo $drug_allergies['eye_drops_m'];?>';
var ampic_m ='<?php echo $drug_allergies['ampic_m'];?>';
var amox_m ='<?php echo $drug_allergies['amox_m'];?>'; 
var ceftr_m ='<?php echo $drug_allergies['ceftr_m'];?>';
var cipro_m ='<?php echo $drug_allergies['cipro_m'];?>';
var clari_m ='<?php echo $drug_allergies['clari_m'];?>';
var cotri_m ='<?php echo $drug_allergies['cotri_m'];?>';
var etham_m ='<?php echo $drug_allergies['etham_m'];?>';
var ison_m ='<?php echo $drug_allergies['ison_m'];?>'; 
var metro_m ='<?php echo $drug_allergies['metro_m'];?>';
var penic_m ='<?php echo $drug_allergies['penic_m'];?>';
var rifa_m ='<?php echo $drug_allergies['rifa_m'];?>'; 
var strep_m ='<?php echo $drug_allergies['strep_m'];?>';
var ketoc_m ='<?php echo $drug_allergies['ketoc_m'];?>'; 
var fluco_m ='<?php echo $drug_allergies['fluco_m'];?>'; 
var itrac_m ='<?php echo $drug_allergies['itrac_m'];?>'; 
var acyclo_m ='<?php echo $drug_allergies['acyclo_m']; ?>';
var efavir_m ='<?php echo $drug_allergies['efavir_m'];?>'; 
var enfuv_m ='<?php echo $drug_allergies['enfuv_m'];?>'; 
var nelfin_m ='<?php echo $drug_allergies['nelfin_m'];?>'; 
var nevira_m ='<?php echo $drug_allergies['nevira_m'];?>'; 
var zidov_m ='<?php echo $drug_allergies['zidov_m'];?>'; 
var aspirin_m ='<?php echo $drug_allergies['aspirin_m'];?>';
var paracet_m ='<?php echo $drug_allergies['paracet_m'];?>';
var ibupro_m ='<?php echo $drug_allergies['ibupro_m'];?>'; 
var diclo_m ='<?php echo $drug_allergies['diclo_m'];?>'; 
var aceclo_m ='<?php echo $drug_allergies['aceclo_m'];?>'; 
var napro_m ='<?php echo $drug_allergies['napro_m'];?>'; 
var tropip_m ='<?php echo $drug_allergies['tropip_m'];?>'; 
var tropi_m ='<?php echo $drug_allergies['tropi_m'];?>'; 
var timolol_m ='<?php echo $drug_allergies['timolol_m'];?>';
var homide_m ='<?php echo $drug_allergies['homide_m'];?>'; 
var brimo_m ='<?php echo $drug_allergies['brimo_m'];?>'; 
var latan_m ='<?php echo $drug_allergies['latan_m'];?>'; 
var travo_m ='<?php echo $drug_allergies['travo_m'];?>'; 
var tobra_m ='<?php echo $drug_allergies['tobra_m'];?>'; 
var moxif_m ='<?php echo $drug_allergies['moxif_m'];?>'; 
var homat_m ='<?php echo $drug_allergies['homat_m'];?>'; 
var piloc_m ='<?php echo $drug_allergies['piloc_m'];?>'; 
var cyclop_m ='<?php echo $drug_allergies['cyclop_m'];?>'; 
var atrop_m ='<?php echo $drug_allergies['atrop_m'];?>'; 
var phenyl_m ='<?php echo $drug_allergies['phenyl_m'];?>'; 
var tropic_m ='<?php echo $drug_allergies['tropic_m'];?>'; 
var parac_m ='<?php echo $drug_allergies['parac_m'];?>'; 
var ciplox_m ='<?php echo $drug_allergies['ciplox_m'];?>'; 
var alco_m ='<?php echo $contact_allergies['alco_m'];?>';
var latex_m ='<?php echo $contact_allergies['latex_m'];?>';
var betad_m ='<?php echo $contact_allergies['betad_m'];?>';
var adhes_m ='<?php echo $contact_allergies['adhes_m'];?>';
var tegad_m ='<?php echo $contact_allergies['tegad_m'];?>';
var trans_m ='<?php echo $contact_allergies['trans_m'];?>';
var seaf_m='<?php echo $food_allergies['seaf_m']; ?>';
var corn_m='<?php echo $food_allergies['corn_m']; ?>';
var egg_m='<?php echo $food_allergies['egg_m']; ?>'; 
var milk_m='<?php echo $food_allergies['milk_m']; ?>';
var pean_m='<?php echo $food_allergies['pean_m']; ?>';
var shell_m='<?php echo $food_allergies['shell_m'];?>';
var soy_m='<?php echo $food_allergies['soy_m']; ?>'; 
var lact_m='<?php echo $food_allergies['lact_m']; ?>';
var mush_m='<?php echo $food_allergies['mush_m']; ?>';
var special_status='<?php echo $history_radios_data['special_status']; ?>';
var general_checkup='<?php echo $history_radios_data['general_checkup']; ?>';

    if(pain_m==1){
       $('#pain_m').parent().toggleClass('bg-theme');
       $("#pains").toggle();
    }
    //
    if(bdv_m==1){
      $('#bdv_m').parent().toggleClass('bg-theme');
       $("#blurr").toggle();
    }
    //
    if(redness_m==1){
      $('#redness_m').parent().toggleClass('bg-theme');
       $("#rednes").toggle();
    }
    //
    if(injury_m==1){
      $('#injury_m').parent().toggleClass('bg-theme');
       $("#injuries").toggle();
    }
    //
    if(water_m==1){
      $('#water_m').parent().toggleClass('bg-theme');
       $("#waterings").toggle();
    }
    //
    if(discharge_m==1){
      $('#discharge_m').parent().toggleClass('bg-theme');
       $("#discharges").toggle();
    }
    //
    if(dryness_m==1){
      $('#dryness_m').parent().toggleClass('bg-theme');
       $("#dryness").toggle();
    }
    //
    if(itch_m==1){
      $('#itch_m').parent().toggleClass('bg-theme');
       $("#itchings").toggle();
    }
     //
    if(fbd_m==1){
      $('#fbd_m').parent().toggleClass('bg-theme');
       $("#fbsensation").toggle();
    }
     //
    if(devs_m==1){
      $('#devs_m').parent().toggleClass('bg-theme');
       $("#dev_squint").toggle();
    }
     //
    if(heads_m==1){
      $('#heads_m').parent().toggleClass('bg-theme');
       $("#head_strain").toggle();
    }
     //
    if(canss_m==1){
      $('#canss_m').parent().toggleClass('bg-theme');
       $("#size_shape").toggle();
    }
     //
    if(ovs_m==1){
      $('#ovs_m').parent().toggleClass('bg-theme');
       $("#ovs").toggle();
    }
     //
    if(sdv_m==1){
      $('#sdv_m').parent().toggleClass('bg-theme');
       $("#sdiv").toggle();
    }
     //
    if(doe_m==1){
      $('#doe_m').parent().toggleClass('bg-theme');
       $("#doe").toggle();
    }
    //
    if(swel_m==1){
      $('#swel_m').parent().toggleClass('bg-theme');
       $("#swell").toggle();
    }
    //
    if(burns_m==1){
      $('#burns_m').parent().toggleClass('bg-theme');
       $("#sen_burn").toggle();
    }
    //
    if(gla_m==1){
      $('#gla_m').parent().toggleClass('bg-theme');
       $("#glau").toggle();
    }
    //
    if(reti_m==1){
      $('#reti_m').parent().toggleClass('bg-theme');
       $("#renti_d").toggle();
    }
    //
    if(glass_m==1){
      $('#glass_m').parent().toggleClass('bg-theme');
       $("#glas").toggle();
    }
    //
    if(eyedi_m==1){
      $('#eyedi_m').parent().toggleClass('bg-theme');
       $("#eye_d").toggle();
    }
    //
    if(eyesu_m==1){
      $('#eyesu_m').parent().toggleClass('bg-theme');
       $("#eye_s").toggle();
    }
    //
    if(uve_m==1){
      $('#uve_m').parent().toggleClass('bg-theme');
       $("#uvei").toggle();
    }
    //
    if(retil_m==1){
      $('#retil_m').parent().toggleClass('bg-theme');
       $("#renti_l").toggle();
    }
    //
    if(dia_m==1){
      $('#dia_m').parent().toggleClass('bg-theme');
       $("#diab").toggle();
    }
    //
    if(hyper_m==1){
      $('#hyper_m').parent().toggleClass('bg-theme');
       $("#hyper").toggle();
    }
    //
    if(alcoh_m==1){
      $('#alcoh_m').parent().toggleClass('bg-theme');
       $("#alcoh").toggle();
    }
    //
    if(smok_m==1){
      $('#smok_m').parent().toggleClass('bg-theme');
      $("#smokt").toggle();
    }
    //
    if(card_m==1){
      $('#card_m').parent().toggleClass('bg-theme');
       $("#cardd").toggle();
    }
    //
    if(steri_m==1){
      $('#steri_m').parent().toggleClass('bg-theme');
       $("#steri").toggle();
    }

    //
    if(drug_m==1){
      $('#drug_m').parent().toggleClass('bg-theme');
       $("#drug").toggle();
    }//
    if(hiva_m==1){
      $('#hiva_m').parent().toggleClass('bg-theme');
      $("#hiva").toggle();
    }
       
    //
    if(cant_m==1){
      $('#cant_m').parent().toggleClass('bg-theme');
       $("#cantu").toggle();
    }
    //
    if(tuber_m==1){
      $('#tuber_m').parent().toggleClass('bg-theme');
       $("#tuberc").toggle();
    }
    //
    if(asth_m==1){
      $('#asth_m').parent().toggleClass('bg-theme');
       $("#asthm").toggle();
    }
    //
    if(cnsds_m==1){
      $('#cnsds_m').parent().toggleClass('bg-theme');
       $("#cncds").toggle();
    }
    //
    if(hypo_m==1){
      $('#hypo_m').parent().toggleClass('bg-theme');
       $("#hypo").toggle();
    }
    //
    if(hyperth_m==1){
      $('#hyperth_m').parent().toggleClass('bg-theme');
       $("#hyperth").toggle();
    }
    //
    if(hepac_m==1){
      $('#hepac_m').parent().toggleClass('bg-theme');
       $("#heptc").toggle();
    }
    //
    if(renald_m==1){
      $('#renald_m').parent().toggleClass('bg-theme');
       $("#rend").toggle();
    }
    //
    if(acid_m==1){
      $('#acid_m').parent().toggleClass('bg-theme');
       $("#acid").toggle();
    }
    //
    if(oins_m==1){
      $('#oins_m').parent().toggleClass('bg-theme');
       $("#onins").toggle();
    }
    //
    if(oasp_m==1){
      $('#oasp_m').parent().toggleClass('bg-theme');
       $("#oasbth").toggle();
    }
    //
    if(acon_m==1){
      $('#acon_m').parent().toggleClass('bg-theme');
       $("#consan").toggle();
    }
    //
    if(thd_m==1){
      $('#thd_m').parent().toggleClass('bg-theme');
       $("#thyrd").toggle();
    }
    //
    if(chewt_m==1){
      $('#chewt_m').parent().toggleClass('bg-theme');
       $("#chewt").toggle();
    }

      //
    if(antimi_agen_m==1){
      $('#antimi_agen_m').parent().toggleClass('bg-theme');
       $("#antimi_agen").toggle();
    }
    //
    if(antif_agen_m==1){
      $('#antif_agen_m').parent().toggleClass('bg-theme');
       $("#antif_agen").toggle();
    }
    //
    if(ant_agen_m==1){
      $('#ant_agen_m').parent().toggleClass('bg-theme');
       $("#ant_agen").toggle();
    }
    //
    if(nsaids_m==1){
      $('#nsaids_m').parent().toggleClass('bg-theme');
       $("#nsaids").toggle();
    }
    //
    if(eye_drops_m==1){
      $('#eye_drops_m').parent().toggleClass('bg-theme');
       $("#eye_drops").toggle();
    }
     //
    if(ampic_m==1){
      $('#ampic_m').parent().toggleClass('bg-theme');
       $("#ampici").toggle();
    }
     //
    if(amox_m==1){
      $('#amox_m').parent().toggleClass('bg-theme');
       $("#amoxi").toggle();
    }
     //
    if(ceftr_m==1){
      $('#ceftr_m').parent().toggleClass('bg-theme');
       $("#ceftr").toggle();
    }
     //
    if(cipro_m==1){
      $('#cipro_m').parent().toggleClass('bg-theme');
       $("#ciprof").toggle();
    }
     //
    if(clari_m==1){
      $('#clari_m').parent().toggleClass('bg-theme');
       $("#clarith").toggle();
    }
     //
    if(cotri_m==1){
      $('#cotri_m').parent().toggleClass('bg-theme');
       $("#cotri").toggle();
    } //
    if(etham_m==1){
      $('#etham_m').parent().toggleClass('bg-theme');
       $('#ethamb').toggle();
    }
     //
    if(ison_m==1){
      $('#ison_m').parent().toggleClass('bg-theme');
       $("#isoni").toggle();
    }
     //
    if(metro_m==1){
      $('#metro_m').parent().toggleClass('bg-theme');
       $("#metron").toggle();
    }
     //
    if(penic_m==1){
      $('#penic_m').parent().toggleClass('bg-theme');
       $("#penic").toggle();
    }
     //
    if(rifa_m==1){
      $('#rifa_m').parent().toggleClass('bg-theme');
       $("#rifam").toggle();
    }
     //
    if(strep_m==1){
      $('#strep_m').parent().toggleClass('bg-theme');
      $("#strept").toggle();
    }  
    //
   if(ketoc_m==1){
      $('#ketoc_m').parent().toggleClass('bg-theme');
       $("#ketoco").toggle();
    }
   //
   if(fluco_m==1){
      $('#fluco_m').parent().toggleClass('bg-theme');
       $("#flucon").toggle();
    }
     //
    if(itrac_m==1){
      $('#itrac_m').parent().toggleClass('bg-theme');
      $("#itrac").toggle();
    }  
    //
   if(acyclo_m==1){
      $('#acyclo_m').parent().toggleClass('bg-theme');
       $("#acyclo").toggle();
    }
   //
   if(efavir_m==1){
      $('#efavir_m').parent().toggleClass('bg-theme');
       $("#efavir").toggle();
    }
    //
    if(enfuv_m==1){
      $('#enfuv_m').parent().toggleClass('bg-theme');
      $("#enfuv").toggle();
    }  
    //
   if(nelfin_m==1){
      $('#nelfin_m').parent().toggleClass('bg-theme');
       $("#nelfin").toggle();
    }
   //
   if(nevira_m==1){
      $('#nevira_m').parent().toggleClass('bg-theme');
       $("#nevira").toggle();
    }

     //
    if(zidov_m==1){
      $('#zidov_m').parent().toggleClass('bg-theme');
      $("#zidov").toggle();
    }  
    //
   if(aspirin_m==1){
      $('#aspirin_m').parent().toggleClass('bg-theme');
       $("#aspirin").toggle();
    }
   //
   if(paracet_m==1){
      $('#paracet_m').parent().toggleClass('bg-theme');
       $("#paracet").toggle();
    }
    //
    if(ibupro_m==1){
      $('#ibupro_m').parent().toggleClass('bg-theme');
      $("#ibupro").toggle();
    }  
    //
   if(diclo_m==1){
      $('#diclo_m').parent().toggleClass('bg-theme');
       $("#diclo").toggle();
    }
   //
   if(aceclo_m==1){
      $('#aceclo_m').parent().toggleClass('bg-theme');
       $("#aceclo").toggle();
    }

    //
    if(napro_m==1){
      $('#napro_m').parent().toggleClass('bg-theme');
       $("#napro").toggle();
    }
      //
    if(tropip_m==1){
      $('#tropip_m').parent().toggleClass('bg-theme');
       $("#tropicp").toggle();
    }
    //
    if(tropi_m==1){
      $('#tropi_m').parent().toggleClass('bg-theme');
       $("#tropica").toggle();
    }
    //
    if(timolol_m==1){
      $('#timolol_m').parent().toggleClass('bg-theme');
       $("#timol").toggle();
    }
    //
    if(homide_m==1){
      $('#homide_m').parent().toggleClass('bg-theme');
       $("#homide").toggle();
    }
    //
    if(brimo_m==1){
      $('#brimo_m').parent().toggleClass('bg-theme');
       $("#brimon").toggle();
    }
    //
    if(latan_m==1){
      $('#latan_m').parent().toggleClass('bg-theme');
       $("#latan").toggle();
    }
    //
    if(travo_m==1){
      $('#travo_m').parent().toggleClass('bg-theme');
       $("#travo").toggle();
    }
    //
    if(tobra_m==1){
      $('#tobra_m').parent().toggleClass('bg-theme');
       $("#tobra").toggle();
    }
    //
    if(moxif_m==1){
      $('#moxif_m').parent().toggleClass('bg-theme');
       $("#moxif").toggle();
    }
    //
    if(homat_m==1){
      $('#homat_m').parent().toggleClass('bg-theme');
       $("#homat").toggle();
    }
    //
    if(piloc_m==1){
      $('#piloc_m').parent().toggleClass('bg-theme');
       $("#piloca").toggle();
    }
    //
    if(cyclop_m==1){
      $('#cyclop_m').parent().toggleClass('bg-theme');
       $("#cyclop").toggle();
    }
    //
    if(atrop_m==1){
      $('#atrop_m').parent().toggleClass('bg-theme');
       $("#atropi").toggle();
    }
    //
    if(phenyl_m==1){
      $('#phenyl_m').parent().toggleClass('bg-theme');
       $("#phenyl").toggle();
    }
    //
    if(tropic_m==1){
      $('#tropic_m').parent().toggleClass('bg-theme');
       $("#tropicac").toggle();
    }
    //
    if(parac_m==1){
      $('#parac_m').parent().toggleClass('bg-theme');
       $("#paracain").toggle();
    }
    //
    if(ciplox_m==1){
      $('#ciplox_m').parent().toggleClass('bg-theme');
       $("#ciplox").toggle();
    }

    //
    if(alco_m==1){
      $('#alco_m').parent().toggleClass('bg-theme');
       $("#alcohol").toggle();
    }
    //
    if(latex_m==1){
      $('#latex_m').parent().toggleClass('bg-theme');
       $("#latex").toggle();
    }
    //
    if(betad_m==1){
      $('#betad_m').parent().toggleClass('bg-theme');
       $("#betad").toggle();
    }
    //
    if(adhes_m==1){
      $('#adhes_m').parent().toggleClass('bg-theme');
       $("#adhes").toggle();
    }
    //
    if(tegad_m==1){
      $('#tegad_m').parent().toggleClass('bg-theme');
       $("#tegad").toggle();
    }
    //
    if(trans_m==1){
      $('#trans_m').parent().toggleClass('bg-theme');
       $("#transp").toggle();
    }

     //
    if(seaf_m==1){
      $('#seaf_m').parent().toggleClass('bg-theme');
       $("#seaf").toggle();
    }
     //
    if(corn_m==1){
      $('#corn_m').parent().toggleClass('bg-theme');
       $("#corn").toggle();
    }
     //
    if(egg_m==1){
      $('#egg_m').parent().toggleClass('bg-theme');
       $("#egg").toggle();
    }
     //
    if(milk_m==1){
      $('#milk_m').parent().toggleClass('bg-theme');
       $("#milk_p").toggle();
    }
     //
    if(pean_m==1){
      $('#pean_m').parent().toggleClass('bg-theme');
       $("#pean").toggle();
    }
     //
    if(shell_m==1){
      $('#shell_m').parent().toggleClass('bg-theme');
       $("#shell").toggle();
    }
     //
    if(soy_m==1){
      $('#soy_m').parent().toggleClass('bg-theme');
       $("#soy").toggle();
    }
     //
    if(lact_m==1){
      $('#lact_m').parent().toggleClass('bg-theme');
       $("#lact").toggle();
    }
     //
    if(mush_m==1){
      $('#mush_m').parent().toggleClass('bg-theme');
       $("#mush").toggle();
    }
    //
    if(history_chief_ovs_glare==1){
      $('#history_chief_ovs_glare').parent().toggleClass('bg-theme');
    }
    //
    if(history_chief_ovs_floaters==1){
      $('#history_chief_ovs_floaters').parent().toggleClass('bg-theme');
    }
    //
    if(history_chief_ovs_photophobia==1){
      $('#history_chief_ovs_photophobia').parent().toggleClass('bg-theme');
    }
    //
    if(history_chief_ovs_color_halos==1){
      $('#history_chief_ovs_color_halos').parent().toggleClass('bg-theme');   
    }
     //
    if(history_chief_ovs_metamorphopsia==1){
      $('#history_chief_ovs_metamorphopsia').parent().toggleClass('bg-theme');
    }
    //
    if(history_chief_ovs_chromatopsia==1){
      $('#history_chief_ovs_chromatopsia').parent().toggleClass('bg-theme');
    }
    //
    if(history_chief_ovs_dnv==1){
      $('#history_chief_ovs_dnv').parent().toggleClass('bg-theme');
    }
     //
    if(history_chief_ovs_ddv==1){
      $('#history_chief_ovs_ddv').parent().toggleClass('bg-theme');    
    }
     //
    if(history_chief_blurr_dist==1){
      $('#history_chief_blurr_dist').parent().toggleClass('bg-theme');
    }

    //
    if(history_chief_blurr_near==1){
      $('#history_chief_blurr_near').parent().toggleClass('bg-theme');
    }
     //
    if(history_chief_blurr_pain==1){
      $('#history_chief_blurr_pain').parent().toggleClass('bg-theme');
    }
       //
    if(history_chief_blurr_ug==1){
      $('#history_chief_blurr_ug').parent().toggleClass('bg-theme');
    }
     //
    if(history_chief_dev_diplopia==1){
      $('#history_chief_dev_diplopia').parent().toggleClass('bg-theme');
    }
      //
    if(history_chief_dev_truma==1){
      $('#history_chief_dev_truma').parent().toggleClass('bg-theme');
    }
      //
    if(history_chief_dev_ps==1){
      $('#history_chief_dev_ps').parent().toggleClass('bg-theme');
    }
    //
    if(general_checkup==1){
       $('#general_checkup').parent().addClass('btn-save');
       $('#clear_checkuptype').show();
    }
    else if(general_checkup==2)
    {
      $('#routine_checkup').parent().addClass('btn-save');
      $('#clear_checkuptype').show();
    }
    else if(general_checkup==3)
    {
      $('#postop_checkup').parent().addClass('btn-save');
      $('#clear_checkuptype').show();
    }
    if(special_status==1)
    {
      $('#special_status_brestf').parent().addClass('btn-save');
      $('#clear_special_status').show();
    }
    else if(special_status==2)
    {
      $('#special_status_preg').parent().addClass('btn-save');    
       $('#clear_special_status').show();  
    }
});



  function myFunction() 
  {
    var weight= $('#weight').val();
    var height= $('#height').val();   
    var newheight= height/100;
    if(weight!='' && newheight!='')
    {
      var bmi=parseFloat(weight/(newheight*newheight)).toFixed(2);
    }
    else
    {
      var bmi='';
    }
    $('#bmi_calculate').val(bmi);

  }

  $('.checkups').click(function(){
      $('#clear_checkuptype').show();
  });

  function clear_checkups()
  {
    $('.checkups').prop('checked', false);
    $('.checkups').parent().removeClass('btn-save');
    $('#clear_checkuptype').hide();
  }

  $('.special_status').click(function(){
      $('#clear_special_status').show();
  });
  function clear_special_status()
  {
    $('.special_status').prop('checked', false);
    $('.special_status').parent().removeClass('btn-save');
    $('#clear_special_status').hide();
  }

  function glaucoma()
  {
    $('#history_ophthalmic_glau_r_dur').val($('#history_ophthalmic_glau_l_dur').val());
    $('#history_ophthalmic_glau_r_unit').val($('#history_ophthalmic_glau_l_unit').val());
  }
   function retinal_detachment()
  {
    $('#history_ophthalmic_renti_d_r_dur').val($('#history_ophthalmic_renti_d_l_dur').val());
    $('#history_ophthalmic_renti_d_r_unit').val($('#history_ophthalmic_renti_d_l_unit').val());
  }
   function glassess()
  {
    $('#history_ophthalmic_glas_r_dur').val($('#history_ophthalmic_glas_l_dur').val());
    $('#history_ophthalmic_glas_r_unit').val($('#history_ophthalmic_glas_l_unit').val());
  }
   function eye_disease()
  {
    $('#history_ophthalmic_eye_d_r_dur').val($('#history_ophthalmic_eye_d_l_dur').val());
    $('#history_ophthalmic_eye_d_r_unit').val($('#history_ophthalmic_eye_d_l_unit').val());
  }

   function eye_surgery()
  {
    $('#history_ophthalmic_eye_s_r_dur').val($('#history_ophthalmic_eye_s_l_dur').val());
    $('#history_ophthalmic_eye_s_r_unit').val($('#history_ophthalmic_eye_s_l_unit').val());
  }
   function uveitis()
  {
    $('#history_ophthalmic_uvei_r_dur').val($('#history_ophthalmic_uvei_l_dur').val());
    $('#history_ophthalmic_uvei_r_unit').val($('#history_ophthalmic_uvei_l_unit').val());
  }
   function retinal_laser()
  {
    $('#history_ophthalmic_renti_l_r_dur').val($('#history_ophthalmic_renti_l_l_dur').val());
    $('#history_ophthalmic_renti_l_r_unit').val($('#history_ophthalmic_renti_l_l_unit').val());
  }
</script>












