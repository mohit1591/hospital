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
    
<div class="medicine_stock">

    <div class="grp">
        <label>Searching Criteria</label>    
        <select>
            <option>-Select-</option>
        </select>
        <input type="text" name="">
    </div>

</div> <!-- medicine_stock -->



    
<div class="medicine_stock">

    <div class="medicine_stock_left">
        <table class="table table-bordered table-striped">
            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"><input type="checkbox" name=""></th>
                    <th>Medicine Name</th>
                    <th>Packing</th>
                    <th>Batch No.</th>
                    <th>Quantity</th>
                    <th>Ex. Date</th>
                    <th>Rack No.</th>
                    <th>MRP</th>
                    <th>P.Rate</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox" name=""></td>
                    <td>AAA</td>
                    <td>packed</td>
                    <td>BA0125c</td>
                    <td>20</td>
                    <td>21/2/2017</td>
                    <td>Rack 01</td>
                    <td>100</td>
                    <td>50</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="medicine_stock_right">
        <button class="btn-save"><i class="fa fa-print"></i> Print All</button>
        <button class="btn-save"><i class="fa fa-folder-open"></i> Open</button>
        <button class="btn-save"><i class="fa fa-trash"></i> Delete</button>
        <button class="btn-save"><i class="fa fa-share-square-o"></i> Export</button>
        <button class="btn-save"><i class="fa fa-sign-out"></i> Exit</button>
    </div>

</div> <!-- medicine_stock -->









</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>