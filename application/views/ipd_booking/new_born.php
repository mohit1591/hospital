<script>
  //$('.datepicker').datepicker({
  $('#born_date').datepicker({
    dateFormat: 'dd-mm-yy',
    autoclose: true,
  });


  $('.datepicker3').datetimepicker({
    format: 'LT'
  });
</script>
<div class="modal-dialog" role="document" style="width:30%">
  <div class="modal-content">
    <!--onclick="$('#load_sample_modal_popup').hide();"-->
    <div class="modal-header">
      <h4>Baby of <?php echo $patient_details['patient_name']; ?>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </h4>
    </div>
    <form id="newborn_form" method="post" action="">
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 m-b1">
            <div class="row">
              <div class="col-md-4">
                <lable> Date:</lable>
              </div>
              <div class="col-md-4">

                <input type="hidden" name="baby_of" value="<?php echo $patient_details['patient_name']; ?>">
                <input type="hidden" name="patient_id" value="<?php echo $patient_details['patient_id']; ?>">
                <input type="hidden" name="ipd_id" value="<?php echo $ipd_id; ?>">
                <input type="text" name="born_date" class="datepicker" id="born_date" value="<?php echo $born_date; ?>">
              </div>
              <div class="col-md-4">

                <input type="text" name="born_time" class="w-65px datepicker3" id="born_time" value="<?php echo $born_time; ?>">
              </div>
            </div> <!-- innerrow -->
          </div> <!-- 12 -->
        </div> <!-- row -->

        <div class="row">
          <div class="col-md-12 m-b1">
            <div class="row">
              <div class="col-md-4">

                <lable> Weight:</lable>
              </div>
              <div class="col-md-8">

                <input type="text" name="weight" id="weight" placeholder="Weight" value="<?php echo $weight; ?>">
              </div>
            </div> <!-- innerrow -->
          </div> <!-- 12 -->
        </div> <!-- row -->
        <div class="row">
          <div class="col-md-12 m-b1">
            <div class="row">
              <div class="col-md-4">


                <lable> Sex:</lable>
              </div>
              <div class="col-md-8">

                <input type="radio" name="gender" value="1" <?php if ($gender == 1) {
                                                              echo 'checked="checked"';
                                                            } ?>> Male &nbsp;
                <input type="radio" name="gender" value="0" <?php if ($gender == 0) {
                                                              echo 'checked="checked"';
                                                            } ?>> Female
                <input type="radio" name="gender" value="2" <?php if ($gender == 2) {
                                                              echo 'checked="checked"';
                                                            } ?>> Others

              </div>
            </div> <!-- innerrow -->
          </div> <!-- 12 -->
        </div> <!-- row -->


        <div class="row">
          <div class="col-md-12 m-b1">
            <div class="row">
              <div class="col-md-4">


                <lable> Type of Delivery:</lable>
              </div>
              <div class="col-md-8">

                <select name="type_of_delivery" id="type_of_delivery">
                  <option value="Normal" <?php echo $type_of_delivery == 'Normal' ? 'selected':''; ?>>Normal</option>
                  <option value="Operative" <?php echo $type_of_delivery == 'Operative' ? 'selected':''; ?>>Operative</option>
                </select>

              </div>
            </div> <!-- innerrow -->
          </div> <!-- 12 -->
        </div> <!-- row -->

        <div class="row">
          <div class="col-md-12 m-b1">
            <div class="row">
              <div class="col-md-4">


                <lable> Caste:</lable>
              </div>
              <div class="col-md-8">
                  <select name="caste" id="caste" class="w-150px m_select_btn">
                    <option value="">Select Caste</option>
                    <?php
                     if(!empty($caste_list)){
                        foreach($caste_list as $cast)
                        {
                          $sel = $cast->name == $caste ?'selected' : '';
                          echo '<option value="'.$cast->name.'" '.$sel.'>'.$cast->name.'</option>';
                        }
                     }
                  ?>
                  </select>
                  <a href="<?php echo base_url('caste_master'); ?>" target="_blank" title="Add New Caste" class="btn-new" ><i class="fa fa-plus"></i> New</a>
                  <!-- <input type="text" name="caste" value="<?=$caste?>"> -->
              </div>
            </div> <!-- innerrow -->
          </div> <!-- 12 -->
        </div> <!-- row -->

        <div class="row">
          <div class="col-md-12 m-b1">
            <div class="row">
              <div class="col-md-4">


                <lable> Religion:</lable>
              </div>
              <div class="col-md-8">
                  
                  <select name="religion" id="religion" class="w-150px m_select_btn">
                    <option value="">Select Religion</option>
                    <?php
                     if(!empty($religion_list)){
                        foreach($religion_list as $rel)
                        {
                          $sel = $rel->religion == $religion ? 'selected' : '';
                          echo '<option value="'.$rel->religion.'" '.$sel.'>'.$rel->religion.'</option>';
                        }
                     }
                  ?>
                  </select>
                  <a href="<?php echo base_url('religion'); ?>" target="_blank" title="Add New Religion" class="btn-new" ><i class="fa fa-plus"></i> New</a> 
              </div>
            </div> <!-- innerrow -->
          </div> <!-- 12 -->
        </div> <!-- row -->

        <div class="row">
          <div class="col-md-12 m-b1">
            <div class="row">
              <div class="col-md-4">
                <lable> Age of Mother:</lable>
              </div>
              <div class="col-md-8">
              <input type="text" name="para" value="<?=$age_of_mother?>">
              </div>
            </div> <!-- innerrow -->
          </div> <!-- 12 -->
        </div> <!-- row -->

        <div class="row">
          <div class="col-md-12 m-b1">
            <div class="row">
              <div class="col-md-4">


                <lable> Para:</lable>
              </div>
              <div class="col-md-8">
              <input type="text" name="para" value="<?=$para?>">
              </div>
            </div> <!-- innerrow -->
          </div> <!-- 12 -->
        </div> <!-- row -->


        <div class="row">
          <div class="col-md-12 m-b1">
            <div class="row">
              <div class="col-md-4">


                <lable> Remarks:</lable>
              </div>
              <div class="col-md-8">
                  <textarea type="text" name="remarks" rows="7"><?=$remarks?></textarea>
              </div>
            </div> <!-- innerrow -->
          </div> <!-- 12 -->
        </div> <!-- row -->
        

      </div> <!-- modal-body -->
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn-update" id="save_new_born">Save</button>
        <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
      </div>

    </form>
  </div>
</div>
<script>
  $('#save_new_born').on('click', function(e) {

    // e.preventDefault();
    $.ajax({
      'type': 'POST',
      'url': '<?php echo base_url('ipd_booking/save_new_born'); ?>',
      'data': $("#newborn_form").serialize(),
      'dataType': 'json',
      success: function(result) {
        //$(".loader_modal").hide();
        if (result.success == 1) {
          flash_session_msg(result.msg);
          $('#load_add_modal_popup').modal('hide');
        }

      }
    });
  });
</script>