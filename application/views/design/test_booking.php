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
    <div class="test-booking">
        <div class="grp-left">
            
            <div class="grp">
                <div class="boxone">
                    <input type="radio" name=""> <label>New Patient</label>
                </div>
                <div class="boxone">
                    <input type="radio" name=""> <label>Registered Patient</label>
                </div>
            </div>
            
            <div class="grp">
                <label>Patient Reg. No.</label>
                <div class="rslt">PATSe00024</div>
            </div>
            
            <div class="grp">
                <label>Lab Ref. No.</label>
                <div class="rslt">LPNSe00032</div>
            </div>
            
            <div class="grp">
                <label>Patient Name <span class="star">*</span></label>
                <div class="rslt">
                    <select class="mr">
                        <option>Mr.</option>
                        <option>Mrs.</option>
                    </select>
                    <input type="text" name="" class="p-name">
                </div>
            </div>
            
            <div class="grp">
                <label>Mobile No. <span class="star">*</span> </label>
                <input type="text" name="">
            </div>
            
            <div class="grp">
                <label>Gender  <span class="star">*</span></label>
                <div class="rslt">
                    <div class="gen">
                        <input type="radio" name=""> Male
                    </div>
                    <div class="gen">
                        <input type="radio" name=""> Female
                    </div>
                </div>
            </div>
            
            <div class="grp">
                <label>Age  <span class="star">*</span></label>
                <div class="rslt">
                    <div class="gen">
                        <input type="text" name="" class="input-tiny"> Y
                    </div>
                    <div class="gen">
                        <input type="text" name="" class="input-tiny"> M
                    </div>
                    <div class="gen">
                        <input type="text" name="" class="input-tiny"> D
                    </div>
                </div>
            </div>
            
            <div class="grp">
                <label>Department <span class="star">*</span></label>
                <select>
                    <option>-Select-</option>
                </select>
            </div>

        </div> <!-- // -->
    	


        <div class="grp-right">
            
            <div class="grp">
                <label>Address</label>
                <textarea></textarea>
            </div>

            <div class="grp">
                <label>Referred By <span class="star">*</span></label>
                <select>
                    <option>-Select-</option>
                </select>
            </div>

            <div class="grp">
                <label>Doctor Name </label>
                <select>
                    <option>-Select-</option>
                </select>
            </div>

            <div class="grp">
                <label>Sample Collected By </label>
                <select>
                    <option>-Select-</option>
                </select>
            </div>

            <div class="grp">
                <label>Staff Refrence </label>
                <select>
                    <option>-Select-</option>
                </select>
            </div>

            <div class="grp">
                <label>Booking Date</label>
                <input type="date" name="">
            </div>

        </div> <!-- // -->

    </div> <!-- test_booking -->

    <div class="test-booking2">
        <div class="left">
            <div class="txt">Test Booking dummy text</div>
            <div class="txt">Test Booking dummy text</div>
            <div class="txt">Test Booking dummy text</div>
            <div class="txt">Test Booking dummy text</div>
        </div> <!-- left -->

        <div class="right">
            <div class="grp">
                <input type="checkbox" name=""> <label>Select All</label> 
            </div>

            <div class="grp">
                <input type="text" name="">
                <button class="btn-new2" type="submit" name="" id=""><i class="fa fa-search"></i> Search</button>
            </div>

            <div class="grp">
                <label>Profile</label>
                <select>
                    <option>-Select-</option>
                </select>
                <a class="btn-new2" href="javascript:void(0)"><i class="fa fa-plus"></i> Add</a>
            </div>
        </div> <!-- right -->
    </div> <!-- test-booking2 -->


    <div class="test-booking"></div> <!-- test-booking -->

    <div class="test-booking"></div> <!-- test-booking -->
</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>