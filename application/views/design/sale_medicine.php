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
    
<div class="sale_medicine">
    <div class="row">
        <div class="col-md-12">
            <div class="grp">
                <span class="new_vendor"><input type="radio" name="" checked=""> <label>New Patient</label></span>
                <span class="new_vendor"><input type="radio" name=""> <label>Registered Patient</label></span>
                <span class="new_vendor"><input type="radio" name=""> <label>IPD Patient</label></span>
            </div>
        </div>
    </div>    <!-- endRow -->

    <div class="sale_fields">
        
        <div class="grp-full">
            <div class="b1">
                <label>Patient Reg. No.</label>
            </div>
            <div class="b2">
                2017/0052
            </div>
            <div class="b3"></div>
        </div> <!-- grp-full -->
        
        <div class="grp-full">
            <div class="b1">
                <label>Reffered By. <span class="star">*</span></label>
            </div>
            <div class="b2">
                <select class="">
                    <option>-Select-</option>
                </select>
            </div>
            <div class="b3">
                <button class="btn-new">New</button>
            </div>
        </div> <!-- grp-full -->
        
    </div> <!-- sale_fields -->

    <div class="sale_fields">
        
        <div class="grp-full">
            <div class="b1">
                <label>Sale No.</label>
            </div>
            <div class="b2">
                SAL0004
            </div>
            <div class="b3"></div>
        </div> <!-- grp-full -->
        
        <div class="grp-full">
            <div class="b1">
                <label>Sale Date</label>
            </div>
            <div class="b2">
                <input type="text">
            </div>
            <div class="b3"></div>
        </div> <!-- grp-full -->
        
    </div> <!-- sale_fields -->

    <div class="sale_fields">
        
        <div class="grp-full">
            <div class="b1">
                <label>Patient Name <span class="star">*</span></label>
            </div>
            <div class="b2">
                <select class="mr">
                    <option>Mr.</option>
                </select>
                <input type="text" name="" class="mr_name">
            </div>
            <div class="b3"></div>
        </div> <!-- grp-full -->
        
        <div class="grp-full">
            <div class="b1">
                <label>Mobile No.</label>
            </div>
            <div class="b2">
                <input type="text" name="">
            </div>
            <div class="b3"></div>
        </div> <!-- grp-full -->
        
    </div> <!-- sale_fields -->





    <div class="sale_medicine_box">
        <div class="grp">
            <label>by code</label>
            <input type="text" name="">
        </div>
        <div class="grp">
            <label>by Name</label>
            <input type="text" name="">
        </div>
        <div class="grp">
            <label>by Batch</label>
            <input type="text" name="">
        </div>
        <div class="grp">
            <label>by company</label>
            <input type="text" name="">
            <button class="btn-new">Search</button>
        </div>
    </div> <!-- sale_medicine_box -->



    <div class="sale_medicine_tbl_box">
        <div class="left">
            <table class="table table-bordered table-striped">
                <thead class="bg-theme">
                    <tr>
                        <th class="40" align="center"><input type="checkbox" name=""></th>
                        <th>Select</th>
                        <th>Medicine Name</th>
                        <th>Packing</th>
                        <th>Batch No.</th>
                        <th>Mfg. Date</th>
                        <th>Exp. Date</th>
                        <th>Stock</th>
                        <th>Quantity</th>
                        <th>Rate</th>
                        <th>Discount</th>
                        <th>Tax(%)</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" name=""></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- left -->
        <div class="right">
             <button class="btn-new">Add</button>
        </div> <!-- right -->
    </div> <!-- sale_medicine_tbl_box -->



    <div class="sale_medicine_tbl_box">
        <div class="left">
            <table class="table table-bordered table-striped">
                <thead class="bg-theme">
                    <tr>
                        <th class="40" align="center"><input type="checkbox" name=""></th>
                        <th>Medicine Name</th>
                        <th>Packing</th>
                        <th>Batch No.</th>
                        <th>Mfg. Date</th>
                        <th>Exp. Date</th>
                        <th>Stock</th>
                        <th>Quantity</th>
                        <th>Rate</th>
                        <th>Discount</th>
                        <th>Tax(%)</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" name=""></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- left -->
        <div class="right">
             <!-- <button class="btn-new">Add</button> -->
        </div> <!-- right -->
    </div> <!-- sale_medicine_tbl_box -->





    <div class="sale_medicine_bottom">
        <div class="left">
            <div class="right_box">
                <div class="sale_medicine_mod_of_payment">
                    <label>Mode Of Payment</label>
                    <select>
                        <option>Cash</option>
                    </select>
                </div>

                <div class="sale_medicine_mod_of_payment">
                    <label>Total</label>
                    <input type="text" name="" value="0">
                </div>

                <div class="sale_medicine_mod_of_payment">
                    <label>Discount</label>
                    <input type="text" name="" value="0">
                </div>

                <div class="sale_medicine_mod_of_payment">
                    <label>Other Discount(%)</label>
                    <div class="grp">
                        <input class="input-tiny" type="text" value="0">
                        <input type="text" name="" value="0">
                    </div>
                </div>

                <div class="sale_medicine_mod_of_payment">
                    <label>Tax</label>
                    <input type="text" name="" value="0">
                </div>

                <div class="sale_medicine_mod_of_payment">
                    <label>Vat(%)</label>
                    <div class="grp">
                        <input class="input-tiny" type="text" value="0">
                        <input type="text" name="" value="0">
                    </div>
                </div>

                <div class="sale_medicine_mod_of_payment">
                    <label>Net Payable</label>
                    <input type="text" name="" value="0">
                </div>

                <div class="sale_medicine_mod_of_payment">
                    <label>Amount Paid</label>
                    <input type="text" name="" value="0">
                </div>

                <div class="sale_medicine_mod_of_payment">
                    <label>Balance</label>
                    <input type="text" name="" value="0">
                </div>

            </div> <!-- right_box -->

            
        </div> <!-- left -->
        <div class="right">
            <button class="btn-save"><i class="fa fa-floppy-o"></i> Save</button>
            <button class="btn-save"><i class="fa fa-refresh"></i> Update</button>
            <button class="btn-save"><i class="fa fa-sign-out"></i> Exit</button>
        </div> 
    </div> <!-- sale_medicine_bottom -->





</div> <!-- sale_medicine -->

</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>