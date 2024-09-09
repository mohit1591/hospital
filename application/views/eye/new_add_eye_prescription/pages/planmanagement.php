<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script>
<div class="row">
	<div class="col-xs-10">
		<textarea id="pln_txt" name="advs[comments][planmanagement]" class="w-100"><?php if(!empty($advice_comm['planmanagement'])){ echo $advice_comm['planmanagement'];}?></textarea>			
    </div>
    <div class="col-xs-2">
		<select onclick="get_plan(this.value);" multiple class="form-control">
			<?php  if(!empty($plan_list)){
			 foreach ($plan_list as $key => $sets_data) {?>						
			<option value="<?php echo $sets_data->id;?>"><?php echo $sets_data->plan_name;?></option>
			<?php }} ?>
		</select>		
    </div>	
</div>

<script>
	function get_plan(pid)
	{
		var branch_id="<?php echo $branch_id;?>";
		 $.ajax({
				 	type: "POST",
		            url: "<?php echo base_url();?>eye/add_eye_prescription/get_plan_managment_by_id",
		            data: {'plan_id' : pid},
		            success:function(result)
		            {
		            	var obj = $.parseJSON(result);
		            	CKEDITOR.instances['pln_txt'].setData(obj.plan_text);
		            }
		        });
    }
    $(document).ready(function(){
		CKEDITOR.replace( 'pln_txt', {
		    fullPage: true, 
		    allowedContent: true,
		    autoGrow_onStartup: true,
		    enterMode: CKEDITOR.ENTER_BR
		});
	});
</script>