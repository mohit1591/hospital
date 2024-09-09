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
    <div class="test-master">
        <div class="col">
            <div class="left">
                <div class="grp">
                    <div class="box"><input type="radio" name=""> <label>Branch</label></div>
                    <div class="box"><input type="radio" name=""> <label>Doctor</label></div>
                </div>

                <div class="grp">
                    <label>Department <span class="star">*</span></label>
                    <select>
                        <option>-Select-</option>
                    </select>
                </div> 

                <div class="grp">
                    <label>Test Name <span class="star">*</span></label>
                    <input type="text" name="">
                </div>

                <div class="grp">
                    <label>Default Value</label>
                    <input type="text" name="">
                </div>

                <div class="grp">
                    <label>Range From</label>
                    <input type="text" name="">
                </div>

                <div class="grp">
                    <label>Method</label>
                    <input type="text" name="">
                </div>

                <div class="grp">
                    <label>Test Type <span class="star">*</span></label>
                    <select>
                        <option>-Select-</option>
                    </select>
                </div>

                <div class="grp">
                    <label>Formula</label>
                    <input type="text" name="">
                </div>

                <div class="grp">
                    <label></label>
                    <a href="javascript:void(0)" class="btn-commission"><i class="fa fa-plus"></i>  Add Interpretation</a>
                </div>

            </div> <!-- left -->



            <div class="right">
                
                <div class="grp-full">
                    <div class="b1">
                        <label>Branch</label>
                        <select>
                            <option>-Select-</option>
                        </select>
                    </div>
                    <div class="b2">
                        <a href="javascript:void(0)" class="btn-new"><i class="fa fa-file-text-o"></i> Export</a>
                    </div>
                </div>
                
                <div class="grp-full">
                    <div class="b1">
                        <label>Test Head <span class="star">*</span></label>
                        <select>
                            <option>-Select-</option>
                        </select>
                    </div>
                    <div class="b2">
                        <a href="javascript:void(0)" class="btn-new"><i class="fa fa-plus"></i> New</a>
                    </div>
                </div>

                <div class="grp">
                    <label>Test Code</label>
                    <input type="text" name="">
                </div>

                <div class="grp">
                    <label>Rate <span class="star">*</span></label>
                    <input type="text" name="">
                </div>

                <div class="grp">
                    <label>Base Rate <span class="star">*</span></label>
                    <input type="text" name="">
                </div>
                
                <div class="grp-full">
                    <div class="b1">
                        <label>Range To</label>
                        <select>
                            <option>-Select-</option>
                        </select>
                    </div>
                    <div class="b2">
                        <a href="javascript:void(0)" class="btn-new"><i class="fa fa-cube"></i> Advance</a>
                    </div>
                </div>

                <div class="grp">
                    <label>Units</label>
                    <input type="text" name="">
                </div>

                <div class="grp">
                    <label>Test Under</label>
                    <input type="text" name="">
                </div>

                <div class="grp">
                    <label>Condition</label>
                    <input type="text" name="">
                </div>
                <div class="grp">
                    <label></label>
                    <a href="javascript:void(0)" class="btn-commission"><i class="fa fa-plus"></i>  Add Precaution</a>
                </div>

            </div> <!-- right -->
        </div> <!-- col -->
  	
    	
    	

        <div class="col2">
            <div class="col-left">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="50"><input type="checkbox" name=""></th>
                            <th>Sr.No.</th>
                            <th>ID</th>
                            <th>Department</th>
                            <th>Test Name</th>
                            <th>Rate</th>
                            <th>Base Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align="center"><input type="checkbox" name=""></td>
                            <td>1</td>
                            <td>75</td>
                            <td>LAB</td>
                            <td>HAEMOGLOBIN</td>
                            <td>60</td>
                            <td>40</td>
                        </tr>
                    </tbody>
                </table>                
            </div> <!-- col-left -->

            <div class="col-right">
                <div class="btns">
                    <button class="btn-save" type="submit" name="" id=""><i class="fa fa-floppy-o"></i>  Save</button>
                    <button class="btn-save" type="button" name="" id=""><i class="fa fa-refresh"></i>  Update</button>
                    <button class="btn-save" type="button" name="" id=""><i class="fa fa-trash"></i>  Delete</button>
                    <button class="btn-save" type="button" name="" id=""><i class="fa fa-sign-out"></i>  Exit</button>
                </div>
            </div> <!-- col-right -->
        </div> <!-- col -->

    </div> <!-- test-master -->
</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>