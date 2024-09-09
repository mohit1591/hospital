<div class="modal-dialog" style="max-width:991px;width:auto;min-width:320px;">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
    <div class="modal-content"> 
        <form  id="diagnosis_set" method="post" class="form-inline">
            <input type="hidden" name="data_id" id="set_id" value="<?php echo $form_data['data_id']; ?>" /> 
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4><?php echo $page_title; ?></h4> 
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-md-3">
                        <label for="Create_New_ICD"><input type="radio" name="custom_type" value="1" id="Create_New_ICD" class="icd_1" checked="checked"> Create New ICD</label>
                    </div>
                    <div class="col-md-3">
                        <label for="Attach_to_existing_ICD"><input type="radio" name="custom_type" value="2" id="Attach_to_existing_ICD" class="icd_2"> Attach to existing ICD</label>
                    </div>
                    <div class="col-md-3">
                        <select name="" id="" class="form-control w-100">
                            <option value="">Ophthalmology</option>
                        </select>
                    </div>
                </div>

                <!--- Create ICD --->
                <div class="well m-t-5 icd_data_1 dd-none">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <input type="text" class="form-control w-100" placeholder="Enter new ICD name" name="icd_name" id="icd_name1" onkeyup="validate(this.value);">
                            <span id="icd_error" class="text-danger"></span>
                            <br>
                            <label for="Add_Laterality">
                                <input type="checkbox" id="Add_Laterality" name="is_laterality" value="1"> Add Laterality
                            </label> <br>                  
                            <p class="text-danger">Note: Don't enter laterality name, if using Add Laterality option</p>
                        </div>
                        <div class="col-md-3">
                             <select name="eye_type" id="eye_type" class="form-control">
                                 <option value=""> Select Eye</option>
                                <option <?php if($form_data['eye_type']=='L'){ echo 'selected';}?> value="L">Left Eye</option>
                                <option <?php if($form_data['eye_type']=='R'){ echo 'selected';}?> value="R">Right Eye</option>
                                <option <?php if($form_data['eye_type']=='BE'){ echo 'selected';}?> value="BE">Both Eye</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!--- Create ICD --->

                <!--- Attach ICD --->
                <div class="well m-t-5 icd_data_2 d-none">
                    <div class="row m-t-5">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="search_dropdown" style="position:relative;">
                                <input type="text" class="form-control w-100" placeholder="Search by Diagnosis name" id="search_box" onkeyup="return search_diagnosis(this.value);" name="attached_diagnosis">
                                <span id="attach_error" class="text-danger"></span>
                                <div class="search_dropdown_list" id="diagnosis_list" >
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control w-100" placeholder="ICD Code" id="attach_icd_code"  name="attach_icd_code" value="" readonly>
                             <span id="attach_icd_code_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="row append_icd_row m-t-5">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <input type="text" class="form-control w-100" id="attach_icd" placeholder="Enter new ICD name" name="attach_icd_name[]" onkeyup="validate(this.value);">
                             <span id="attach_icd_error" class="text-danger"></span>
                        </div>
                        <div class="col-md-3">
                            <button class="btn-custom append_icd_row_btn" type="button"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
               <button type="submit" class="btn-save">Save</button>
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->

    </div><!-- /.modal-dialog -->
    <script type="text/javascript">
        $(document).ready(function(){



// Custom ICD Diagnosis [modal]
$('.icd_1').click(function(){
    $('.icd_data_1').show();
    $('.icd_data_2').hide();
    $('.icd_data_3').hide();
});
$('.icd_2').click(function(){
    $('.icd_data_1').hide();
    $('.icd_data_2').show();
    $('.icd_data_3').hide();
});
$('.icd_3').click(function(){
    $('.icd_data_1').hide();
    $('.icd_data_2').hide();
    $('.icd_data_3').show();
});
// Custom ICD Diagnosis [modal] for append row
var i = 0;

$('.append_icd_row_btn').click(function(){
 i++;
    $('.icd_data_2').append('<div class="row m-t-5 append_icd_row'+i+'"> <div class="col-md-3"></div> <div class="col-md-6"> <input type="text" class="form-control w-100" placeholder="Enter new ICD name" name="attach_icd_name[]"> </div> <div class="col-md-3"> <div class="btn-custom" onclick="return icd_row_remove_btn('+i+');" role="button"><i class="fa fa-minus"></i></div> </div> </div>');
});

});


        function icd_row_remove_btn(i){
            $('.append_icd_row'+i).remove();
        }

        $('#diagnosis_set').submit(function(event){
             event.preventDefault(); 
            $('#overlay-loader').show();
           var type=  $('input[name="custom_type"]:radio').val();
           var form_data= $(this).serialize();
            if ($(".icd_1").is(":checked")) 
        {
            if($('#icd_name1').val().length==0)
            {

                var msg='Please fill the ICD name';
                $('#icd_error').text(msg);
                return false;
            }

        }
        if ($(".icd_2").is(":checked")) 
        {
            if($('#search_box').val().length==0)
            {
                var msg='This field is required';
                var msg2='Code can not be empty, choose ICD from Dropdown by Search';
                $('#attach_error').text(msg);
                $('#attach_icd_code_error').text(msg2);
                 if($('#attach_icd').val().length==0)
                {
                  
                    var msg3='Please enter ICD name';
                    $('#attach_icd_error').text(msg3);
                   
                }
                
                return false;
            }
                if($('#attach_icd').val().length==0)
                {
                    var msg3='Please enter ICD name';
                    $('#attach_icd_error').text(msg3);   
                    return false;
                }
        }
          
         var msg='Custom ICD saved successfully';

           $.ajax({
            url:"<?php echo base_url(); ?>eye/diagnosis_set/add_icd",
            type:"POST",
            data:form_data,
            success: function(result) {
               $('#custom_icd_diagnosis').modal('hide'); 
                flash_session_msg(msg);
                reload_table();      
            }
        });
       });

        function search_diagnosis(val)
        {
            var keyword=val;
            if(keyword.length > 2)
            { 
                 $('#attach_error').fadeOut();
                 $('#attach_icd_code_error').fadeOut();
               $.ajax({
                url:"<?php echo base_url(); ?>eye/diagnosis_set/diagno_Lists/"+keyword,
                success: function(data) {
                    $('#diagnosis_list').css('display','block');
                    $('#diagnosis_list').html(data);
                    $('.append_row_opt').click(function(){
                        $('#search_box').val($(this).text());
                        $('#attach_icd_code').val($(this).attr('data-type'));
                        $("#diagnosis_list").css('display','none');
                    });
                }

            }); 
           }
           else{
                $('#attach_error').fadeIn();
                 $('#attach_icd_code_error').fadeIn();
                 $('#attach_icd_code').val('');
                 $("#diagnosis_list").css('display','none');
           }
       }


    function validate(val)
    {
        if($('#icd_name1').val().length > 0)
        {
             $('#icd_error').fadeOut();
        }
        else if($('#icd_name1').val().length==0){
             $('#icd_error').fadeIn();
        }
         if($('#attach_icd').val().length > 0)
        {
             $('#attach_icd_error').fadeOut();
        }
        else if($('#attach_icd').val().length ==0){
             $('#attach_icd_error').fadeIn();
        }
    }


       

    </script>