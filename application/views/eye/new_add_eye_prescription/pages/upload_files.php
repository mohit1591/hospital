<!--<script type="text/javascript" src="< ?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script>-->
<div class="row">
	<div class="col-xs-8">
			<table class="table table-bordered" id="table_uploads">
			    <thead class="bg-info">
					<tr>
						<th width="20%">S.No.</th>
						<th>Files</th> 
						<th width="20%">Action</th> 
					</tr>
				</thead>
				<tbody> 
				<?php
				$eye_file_upload = $this->session->userdata('eye_file_upload'); 
				if(!empty($eye_file_upload))
				{
				    $i=1;
				    foreach($eye_file_upload as $key=>$eye_file)
				    {
				    ?>
				    <tr>
				        <td><?php echo $i; ?></td>
				        <td><a href="<?php echo ROOT_UPLOADS_PATH.'eye/prescription/files/'.$eye_file['file_name']; ?>" target="_blank"><?php echo $eye_file['orig_name']; ?></a></td>
				        <td><button type="button" onclick="remove_pres_file('<?php echo $key; ?>', '<?php echo $eye_file['file_name']; ?>')" class="btn-custom"><i class="fa fa-trash"></i> Delete </button></td>
				    </tr>
				    <?php
				    $i++;
				    }
				}
				else
				{
				?>
				    <tr>
				        <td colspan="3">Files not found.</td>
				    </tr>
			    <?php
				}
			    ?>	    
				</tbody>
			</table>		
    </div>
    <div class="col-xs-4">
      <form  enctype="multipart/form-data" id="upload_form">
		<input type="file" name="file_uploads" id="file_uploads" onchange="return upload_files();" />
		<div class="col-xs-12 error" id="error_text" style="color:red;"></div>
	  
    </div>	
</div>

<script> 
function upload_files()
{
    var formData = new FormData();
    formData.append('file', $('#file_uploads')[0].files[0]);
    
    $.ajax({
           url : '<?php echo base_url();?>eye/add_eye_prescription/prescription_file_upload',
           type : 'POST',
           data : formData,
           dataType: "json",
           processData: false, 
           contentType: false, 
           success : function(data) { 
               if(data.status=='1')
               {  
                   $('#table_uploads tbody').html(data.table); 
                   flash_session_msg(data.message); 
               }
               else
               {
                   $('#error_text').html(data.message);
               }
           }
    });
}

function remove_pres_file(keys,filename)
{
    $.ajax({
        url: "<?php echo base_url('eye/add_eye_prescription/remove_upload_files'); ?>",
        type: "POST",
        dataType: "json",
        data: { keys:keys, filename:filename },
        success: function(result) 
        { 
            flash_session_msg(result.msg);  
            $('#table_uploads tbody').html(result.data); 
        }
      });
}
</script>