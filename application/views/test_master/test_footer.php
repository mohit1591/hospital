<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">


<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
 

</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="test-footer">
    
    	<div class="grp">
    		<label>Department <span class="star">*</span></label>
    		<select name="dept_id">
    			<option value="">Select Department</option>
                <?php
                    if(!empty($dept_list)){
                        foreach($dept_list as $dept){
                            $dept_select = "";
                            if($dept->id==$form_data['dept_id']){
                                $dept_select = "selected='selected'";
                            }
                            echo '<option value="'.$dept->id.'" '.$dept_select.'>'.$dept->department.'</option>';
                        }
                    }
                ?>
    		</select>
    	</div>

        <div class="grp">
            <label>Doctor <span class="star">*</span></label>
            <select name="dept_id">
                <option value="">Select Doctor</option>
                <?php
                 if(!empty($doctor_list))
                 {
                    foreach($doctor_list as $doctor)
                    {
                        $doctor_select = "";
                        if($doctor->id==$form_data['doctor_id'])
                        {
                            $doctor_select = "selected='selected'";
                        }
                        echo '<option value="'.$doctor->id.'">'.$doctor->doctor_name.'</option>';
                    }
                 }
                ?>
            </select>
        </div>

    	
    	<div class="grp">
    		<label>Signature  <span class="star">*</span></label>
    		<textarea name="signature"><?php echo $form_data['signature']; ?></textarea>
    	</div>

    	
    	<div class="grp">
    		<label>Digital Signature <span class="star">*</span></label>
    		<input type="file" name="sign_image">
    	</div>


    	<div class="grp">
    		<div class="test-footer-photo-frame">
    			<img src="<?php echo base_url('assets/images/photo.png'); ?>" class="">
    		</div>
    	</div>


    	<div class="grp">
    		<div class="btns">
    			<button type="submit" name="" id="" class="btn-save"><i class="fa fa-floppy-o"></i> Save</button>
    			<button type="button" name="" id="" class="btn-save"><i class="fa fa-trash-o"></i> Delete</button>
    			<button type="button" name="" id="" class="btn-save"><i class="fa fa-sign-out"></i> Exit</button>
    		</div>
    	</div>


    </div> <!-- test-footer -->
</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>