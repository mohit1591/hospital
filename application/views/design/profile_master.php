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
    <div class="profile-master">
    
        <div class="pro-left">
            <div class="grp">
                <div class="p1">
                    <input type="radio" name=""> <label>Branch</label>
                </div>
                <div class="p1">
                <input type="radio" name=""> <label>Doctor</label>
                </div>
            </div>

            <div class="grp">
                <div class="p1">
                    <label>Profile <span class="star">*</span></label>
                </div>
                <div class="p1">
                    <select>
                        <option>-Select-</option>
                    </select>
                    <a class="btn-new"><i class="fa fa-plus"></i> New</a>
                </div>
            </div>

            <div class="pframe">
                <div class="txt">Liver function test</div>
                <div class="txt">Liver function test</div>
            </div>

        </div> <!-- pro-left -->

        <div class="pro-right">
            <div class="grp">
                <label>Branch</label>
                <select>
                    <option>-Select-</option>
                    <option></option>
                </select>
            </div>

            <div class="pframe2">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th align="center" width="50"><input type="checkbox" name=""></th>
                            <th>Sr.No.</th>
                            <th>ID</th>
                            <th>Head Name</th>
                            <th>Test Name</th>
                            <th>Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align="center"><input type="checkbox" name=""></td>
                            <td>2</td>
                            <td>44</td>
                            <td>Haemotology Routine</td>
                            <td>Haemoglobin</td>
                            <td>50</td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- pframe2 -->

            <div class="pframe-right">
                <div class="btns">
                    <a class="btn-new" href="javascript:void(0)"><i class="fa fa-plus"></i> New</a>
                </div>
            </div>

        </div> <!-- pro-right -->


    </div> <!-- profile-master -->




<div class="profile-master2">
    <div class="proleft">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="40"></th>
                        <th width="100">Sr. No.</th>
                        <th width="100">ID</th>
                        <th>Test Name</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center"><input type="checkbox" name=""></td>
                        <td>1</td>
                        <td>174</td>
                        <td>SERUM CHOLESTEROL </td>
                    </tr>
                </tbody>
            </table>
    </div> <!-- proleft -->

    <div class="proright">
        <div class="btns">
            <a href="javascript:void(0)" class="btn-new"><i class="fa fa-plus"></i> New</a>
            <button type="submit" class="btn-save" name="" id=""><i class="fa fa-floppy-o"></i> Save</button>
            <button type="button" class="btn-save" name="" id=""><i class="fa fa-sign-out"></i> Exit</button>

        </div>
    </div> <!-- proright -->
</div> <!-- profile-master2 -->













</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>