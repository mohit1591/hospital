<!--- 13-12-2019 --->

<script>
	function diagnosis_hirerachy(id)
	{

        var branch_id  ='<?php echo $branch_id; ?>';
        var booking_id ='<?php echo $booking_id; ?>';
        var patient_id ='<?php echo $patient_id; ?>';
        var $modal = $('#commonly_used_diagnosis_modal');
        $modal.load('<?php echo base_url().'eye/add_eye_prescription/diagnosis_hirerachy/' ?>'+id+'/'+branch_id+'/'+booking_id+'/'+patient_id,
        {
            
        },
        function(){
         $modal.modal('show');
     });
    }
    function custom_hirerachy(id)
    {
        var branch_id  ='<?php echo $branch_id; ?>';
        var booking_id ='<?php echo $booking_id; ?>';
        var patient_id ='<?php echo $patient_id; ?>';
        var $modal = $('#commonly_used_diagnosis_modal');
        $modal.load('<?php echo base_url().'eye/add_eye_prescription/custom_hirerachy/' ?>'+id+'/'+branch_id+'/'+booking_id+'/'+patient_id,
        {
           
        },
        function(){
         $modal.modal('show');
     });
    } 

    function edit_hierarchy(id)
    {
        var branch_id  ='<?php echo $branch_id; ?>';
        var booking_id ='<?php echo $booking_id; ?>';
        var patient_id ='<?php echo $patient_id; ?>';
        var $modal = $('#commonly_used_edit_modal');
        $modal.load('<?php echo base_url().'eye/add_eye_prescription/edit_hirerachy/' ?>'+id+'/'+branch_id+'/'+booking_id+'/'+patient_id,
        {
            
        },
        function(){
          $modal.modal('show');
      });
    }

    function search_hierarchy(id,custom_type)
    {
        var branch_id  ='<?php echo $branch_id; ?>';
        var booking_id ='<?php echo $booking_id; ?>';
        var patient_id ='<?php echo $patient_id; ?>';
        var $modal = $('#commonly_used_diagnosis_modal');
        $modal.load('<?php echo base_url().'eye/add_eye_prescription/search_hierarchy/' ?>'+id+'/'+custom_type+'/'+branch_id+'/'+booking_id+'/'+patient_id,
        {
            
        },
        function(){
          $modal.modal('show');
      });
    }

    function delete_hierarchy(id)
    {

        $.ajax({

            url:'<?php echo base_url(); ?>eye/add_eye_prescription/delete_hierarchy/'+id,
            success:function(result)
            {
                $('#hierarchy_section_'+id).remove();

            }


        });
    }

    



</script>
<!--- 13-12-2019 --->
<section>
	<div class="row">

		<div class="col-md-3">
			<label><input type="checkbox" id="diagnosis_check" onchange="provisional_cmnt(this.value);" name="diagnosis_check" <?php if($provisional_check==1){echo "checked='checked'";}?> value="1" > Provisional Diagnosis </label> <i class="fa fa-info-circle"  data-toggle="modal" data-target="#modal_info_dia" title="Show history of provisional diagnosis"></i>
		</div>
		<div class="col-md-4 <?php if($provisional_check==1){ echo 'd-block';} else{echo 'd-none'; }?>" id="provisional_diagnosis" >
			<input type="text" class="form-control" name="diagnosis_cmnt" value="<?php echo $provisional_comment; ?>">
		</div>
	</div>
	<hr>
</section>
<div id="append_value">
    <?php if(!empty($diagno_lists)) {
        foreach($diagno_lists as $key=> $list){
            ?>
            
            <section id="hierarchy_section_<?php echo $list->id; ?>">
               
               <div class="row">
                  <div class="col-md-6">
                     <small><span class="text-primary"><?php if(!empty($list->eye_side_name) && $list->eye_side_name!=""){echo $list->eye_side_name;}else{ echo $list->icd_name; } ?></span> - <?php echo $list->icd_code; ?></small>
                     <?php if($list->is_code==1){?>
                         <ul class="small">
                          <?php if(!empty($list->diagnosis_comment)){ ?>  <li>&bullet; <?php echo $list->diagnosis_comment; ?></li>
                          <?php } if(!empty($list->user_comment)){ ?>
                            <li>&bullet; <?php echo $list->user_comment; ?></li>
                          <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
                <div class="col-md-6 text-right">
                 <a href="javascript:void(0);" onclick="edit_hierarchy('<?php echo $list->id; ?>');"class="btn-custom"><i class="fa fa-edit"></i></a>
                 <a href="javascript:void(0);" onclick="delete_hierarchy('<?php echo $list->id; ?>');"class="btn-custom"><i class="fa fa-times"></i></a>
             </div>
         </div>
         
         <div class="row">
          <div class="col-md-12 small">- Added by <?php echo $list->entered_by; ?> on <?php if(!empty($prescription_id)){ $entry_date=$list->created_date; echo $entry_date;} else { $entry_date=date('d/m/Y |h:i A',strtotime($list->created_date)); echo $entry_date; } ?></div>
      </div>
      <input type="hidden" value="<?php echo $list->user_comment; ?>" name="diagnosis[icd_data][icd][<?php echo $key;?>][user_comment]">
      <input type="hidden" value="<?php echo $list->diagnosis_comment; ?>" name="diagnosis[icd_data][icd][<?php echo $key;?>][diagnosis_comment]">
      <input type="hidden" value="<?php echo $list->eye_side_name; ?>" name="diagnosis[icd_data][icd][<?php echo $key;?>][eye_side_name]">
      <input type="hidden" value="<?php echo $list->icd_code; ?>" name="diagnosis[icd_data][icd][<?php echo $key;?>][icd_code]">
      <input type="hidden" value="<?php echo $list->id; ?>" name="diagnosis[icd_data][icd][<?php echo $key;?>][id]">
      <input type="hidden" value="<?php echo $list->entered_by; ?>" name="diagnosis[icd_data][icd][<?php echo $key;?>][entered_by]">
      <input type="hidden" value="<?php echo $entry_date; ?>" name="diagnosis[icd_data][icd][<?php echo $key;?>][created_date]">
      <input type="hidden" value="<?php echo $list->is_code; ?>" name="diagnosis[icd_data][icd][<?php echo $key;?>][is_code]">
      <hr>
  </section>

<?php }} ?>

</div>
<section>
 
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#commonly_used_diagnosis" data-toggle="tab"> Commonly Used Diagnosis </label>
            </a>
        </li>
        <li role="presentation" >
            <a href="#custom_made_diagnosis" data-toggle="tab"> Custom Made Diagnosis </a>
        </li>
       <!--  <li role="presentation">
            <a href="#translated_diagnosis" data-toggle="tab"> Translated Diagnosis </a>
        </li>
        <li role="presentation">
            <a href="#frequently_used_diagnosis" data-toggle="tab"> Frequently Used Diagnosis </a>
        </li> -->
    </ul>
</section>


<section>
    <!-- tab content -->
    <div class="tab-content" style="padding:10px;">
        <!-- tab content1 [commonly_used_diagnosis] -->
        <div role="tabpanel" class="tab-pane active" id="commonly_used_diagnosis">
            <div class="row">
                <div class="col-md-3">
                    <label for="">Commonly Used Diagnosis</label>

                    <select name="diagnosis_list" id="diagnosis" class="form-control" multiple="" >
                    	<?php
                    	if(!empty($icd_eye_sections))
                    	{
                    		foreach($icd_eye_sections as $icd_eye_section)
                    		{
                    			$diagnosis_select = "";
                    			if($icd_eye_section['id']==$form_data['diagnosis'])
                    			{
                    				$diagnosis_select = "selected='selected'";
                    			}
                    			echo '<option value="'.$icd_eye_section['id'].'" '.$diagnosis_select.'>'.$icd_eye_section['descriptions'].'</option>';
                    		}
                    	}
                    	?>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-none" id="diagnosis_set_box">
                        <label for="">List</label>
                        
                        <select name="diagnosis_set_name" id="diagnosis_test" class="form-control" multiple="" >
                        	<?php

                        	if(!empty($diagnosis_set))
                        	{
                        		foreach($diagnosis_set as $diagnosi_set)
                        		{
                        			$diagnosi_set_select = "";
                        			if($diagnosi_set->id==$form_data['diagnosis_set'])
                        			{
                        				$diagnosi_set_select = "selected='selected'";
                        			}
                        			echo '<option value="'.$diagnosi_set->id.'" '.$diagnosi_set_select.' >'.$diagnosi_set->diagnosis_set_name.'</option>';
                        		}
                        	}
                        	?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-addon"><span class="label label-success">I + C</span></div>
                        <input type="text" class="form-control form-control-lg" name="icd_search" id="icd_search" onkeyup="return icd_diagno_search(this.value,1);" placeholder="Search by any Diagnosis Name / Code">
                        <div class="search_dropdown_list" id="icd_diagno_list" >
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- tab content2 [custom_made_diagnosis] -->
        <div role="tabpanel" class="tab-pane" id="custom_made_diagnosis">
            <div class="row">
                <div class="col-md-3">
                	<select name="custom_made" id="custom_made_test" class="form-control" multiple="" >
                		<?php 
                     
                		if(!empty($custom_made_icds))
                		{
                			foreach($custom_made_icds as $custom_icd)
                			{
                				//print_r($custom_icd);die;
                				if($custom_icd['custom_type']==1)
                				{
                					$new_icd=json_decode($custom_icd['new_icd']);
                					$icd_name=$new_icd->icd_name;

                				}
                				elseif($custom_icd['custom_type']==2){

                					$attach_icd=json_decode($custom_icd['attached_icd']);
                					$icd_name=$attach_icd->attach_icd_name;
                				}

                				$custom_icd_select = "";
                				if($custom_set['id']==$form_data['custom_set'])
                				{
                					$custom_icd_select = "selected='selected'";
                				}
                				echo '<option onclick="custom_hirerachy('.$custom_icd['id'].');" value="'.$custom_icd['id'].'" '.$custom_icd_select.' >'.$icd_name.'</option>';
                			}
                		}
                		?>
                	</select>
                </div>
                <div class="col-md-6">
                    
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon" style="font-weight: bold;"><span class="label label-success">C</span></span>
                        <input type="text" class="form-control" name="icd_custom_search"  id="icd_custom_search" autocomplete="off" onkeyup="return icd_diagno_search(this.value,2);" placeholder="Search by Custom Diagnosis Name / Code">
                        <div class="search_dropdown_list" id="icd_custom_diagno_list">
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- tab content3 [translated_diagnosis] -->
    <!--     <div role="tabpanel" class="tab-pane" id="translated_diagnosis">
            <div class="row">
                <div class="col-md-3">
                    <select name="" id="" multiple="" class="form-control">
                        <option value="" data-toggle="modal" data-target="#commonly_used_diagnosis_modal">simple2 myopic astigmatism</option>
                        <option value="" data-toggle="modal" data-target="#commonly_used_diagnosis_modal">compound myopic astigmatism</option>
                    </select>
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon" style="font-weight: bold;"><span class="label label-success">C</span></span>
                        <input type="text" class="form-control" name="" autocomplete="off" placeholder="Search by Custom Diagnosis Name / Code">
                    </div>
                </div>
            </div>
        </div> -->
        <!-- tab content4 [frequently_used_diagnosis] -->
     <!--    <div role="tabpanel" class="tab-pane" id="frequently_used_diagnosis">
            <div class="row">
                <div class="col-md-3">
                    <select name="" id="" multiple="" class="form-control">
                        <option value="" data-toggle="modal" data-target="#commonly_used_diagnosis_modal">simple3 myopic astigmatism</option>
                        <option value="" data-toggle="modal" data-target="#commonly_used_diagnosis_modal">compound myopic astigmatism</option>
                    </select>
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon" style="font-weight: bold;"><span class="label label-success">C</span></span>
                        <input type="text" class="form-control" name="" autocomplete="off" placeholder="Search by Custom Diagnosis Name / Code">
                    </div>
                </div>
            </div>
        </div> -->
        <!-- ------------------ -->
        <div class="row">
            <div class="col-md-12">
                <ul class="dia_bottom_left">
                    <li><span>I</span>Standard ICD</li>
                    <li><span>C</span>Custom Created ICD</li>
                    <li><span>T</span>Translated ICD</li>
                </ul>
            </div>
        </div>
        <!-- ------------------ -->
    </div>
    <!-- modal [commonly_used_diagnosis] -->
    <div class="modal fade" id="commonly_used_diagnosis_modal"></div> 
    <div class="modal fade" id="commonly_used_edit_modal"></div> 
    <!-- End of Commonly used --->
</section> 

<div class="modal fade" id="modal_info_dia">
    <div class="modal-dialog" style="max-width:450px;">
        <div class="modal-content">
            <div class="modal-header" style="cursor:move;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>Provisional Diagnosis History</h4>
            </div>
            <div class="modal-body">
                <?php  if($provisional_check==1){?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Date</th>
                                <th>Instruction</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><?php echo date('d/m/Y h:i A',strtotime($provisional_date)); ?></td>
                                <td class="instr"><?php echo $provisional_comment ?> by <?php echo $diagno_lists[0]->entered_by;?> </td>
                            </tr>
                        </tbody>
                    </table>
                <?php }else{?>
                    Provisional Diagnosis not found
                <?php } ?>
            </div>
        </div>
    </div>
</div>




<script>
    
 function provisional_cmnt(val)
 {
    
    if($("#diagnosis_check").is(':checked')) {
        $('#provisional_diagnosis').show();
    }
    else{
        $('#provisional_diagnosis').hide();
        
    }
}


$('#diagnosis').change(function(){

  var section_id = $(this).val(); 


  $.ajax({url: "<?php echo base_url(); ?>eye/diagnosis_set/diagnosisLists/"+section_id, 
     success: function(result)
     { 
        $('#diagnosis_set_box').show();
        $('#diagnosis_test').html(result);   
    } 
}); 
});  

function icd_diagno_search(keyword,custom_type)
{
    
    var keyword=keyword;
    if(keyword.length > 2)
    {
     $.ajax({
        url:"<?php echo base_url(); ?>eye/add_eye_prescription/search_diagno_Lists/"+keyword+'/'+custom_type,
        success: function(data) {
            if(custom_type==2)
            {
               $('#icd_custom_diagno_list').css('display','block');
               $('#icd_custom_diagno_list').html(data);
               $('.append_row_opt').click(function(){
                  var id=$(this).attr('data-id');
                  search_hierarchy(id,custom_type);
                  
                  $("#icd_custom_diagno_list").css('display','none');
                  $("#icd_custom_search").val('');

              });
               
           }
           else if(custom_type==1){
            $('#icd_diagno_list').css('display','block');
            $('#icd_diagno_list').html(data);
            $('.append_row_opt').click(function(){
              var id=$(this).attr('data-id');
              search_hierarchy(id,custom_type);
              
              $("#icd_diagno_list").css('display','none');
              $("#icd_search").val('');

          });
            
        }
    }

}); 
 }
 
}


</script>

<script>
    // Make the DIV element draggable:
dragElement(document.getElementById("modal_info_dia"));

function dragElement(elmnt) {
    var pos1 = 0,
        pos2 = 0,
        pos3 = 0,
        pos4 = 0;
    if (document.getElementById(elmnt.id + "header")) {
        // if present, the header is where you move the DIV from:
        document.getElementById(elmnt.id + "header")
            .onmousedown = dragMouseDown;
    } else {
        // otherwise, move the DIV from anywhere inside the DIV:
        elmnt.onmousedown = dragMouseDown;
    }

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
        // stop moving when mouse button is released:
        document.onmouseup = null;
        document.onmousemove = null;
    }
}
</script>