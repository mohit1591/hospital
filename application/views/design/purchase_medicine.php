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
    
<div class="purchase_medicine">
    <div class="row">
        <div class="col-md-12">
            <div class="grp">
                <span class="new_vendor"><input type="radio" name="" checked=""> <label>New Vendor</label></span>
                <span class="new_vendor"><input type="radio" name=""> <label>Registered Vendor</label></span>
            </div>
        </div>
    </div>    <!-- endRow -->

    <div class="purchase_fields">
        <div class="purchase_fields_left">
            <div class="purchase_medicine_left">
                <div class="grp">
                    <label>vendor code</label>
                    <div class="vendor_code">VED0008</div>
                </div>
            </div>
            <div class="purchase_medicine_left">
                <div class="grp"></div>
            </div>
            <div class="purchase_medicine_left clear">
                <div class="grp">
                    <label>invoice no.</label>
                    <input type="text" name="">
                </div>
            </div>
            <div class="purchase_medicine_left">
                <div class="grp">
                    <label>mobile No.</label>
                    <input type="text" name="">
                </div>
            </div>
            <div class="purchase_medicine_left">
                <div class="grp m-t-5">
                    <label>purchase no.</label>
                    <input type="text" name="">
                </div>
            </div>
            <div class="purchase_medicine_left">
                <div class="grp m-t-5">
                    <label>email id</label>
                    <input type="text" name="">
                </div>
            </div>
            <div class="purchase_medicine_left">
                <div class="grp m-t-5">
                    <label>vendor name <span class="star">*</span></label>
                    <input type="text" name="">
                </div>
            </div>
            <div class="purchase_medicine_left">
                <div class="grp m-t-5">
                    <label>purchase date</label>
                    <input type="date" name="">
                </div>
            </div>
        </div> <!-- purchase_fields_left -->




        <div class="purchase_fields_right">
            <div class="grp">
                <label class="Remark-Date">Remark-Date (MM/YYYY)</label>
            </div>
            <div class="grp">
                <label class="address">Address</label>
                <textarea></textarea>
            </div>
        </div> <!-- purchase_fields_right -->
    </div> <!-- purchase_fields -->





    <div class="purchase_medicine_box">
        <div class="grp">
            <label>by code</label>
            <input type="text" name="">
        </div>
        <div class="grp2">
            <label>by name</label>
            <input type="text" name="">
            <button class="btn-new">New</button>
        </div>
        <div class="grp3">
            <label>by company</label>
            <input type="text" name="">
            <button class="btn-new">Search</button>
        </div>
    </div> <!-- purchase_medicine_box -->



    <div class="purchase_medicine_tbl_box">
        <div class="left">
            <table class="table table-bordered table-striped">
                <thead class="bg-theme">
                    <tr>
                        <th class="40" align="center"><input type="checkbox" name=""></th>
                        <th>Select</th>
                        <th>Medicine Name</th>
                        <th>Packing</th>
                        <th>Batch No.</th>
                        <th>Conv.</th>
                        <th>Mfd. Date</th>
                        <th>Exp. Date</th>
                        <th>Unit1</th>
                        <th>Unit2</th>
                        <th>Free</th>
                        <th>MRP</th>
                        <th>P.Rate</th>
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
    </div> <!-- purchase_medicine_tbl_box -->



    <div class="purchase_medicine_tbl_box">
        <div class="left">
            <table class="table table-bordered table-striped">
                <thead class="bg-theme">
                    <tr>
                        <th class="40" align="center"><input type="checkbox" name=""></th>
                        <th>Medicine Name</th>
                        <th>Packing</th>
                        <th>Batch No.</th>
                        <th>Conv.</th>
                        <th>Mfd. Date</th>
                        <th>Exp. Date</th>
                        <th>Unit1</th>
                        <th>Unit2</th>
                        <th>Free</th>
                        <th>MRP</th>
                        <th>P.Rate</th>
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
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- left -->
        <div class="right">
             <!-- <button class="btn-new">Add</button> -->
        </div> <!-- right -->
    </div> <!-- purchase_medicine_tbl_box -->





    <div class="purchase_medicine_bottom">
        <div class="left">
            <div class="right_box">
                <div class="purchase_medicine_mod_of_payment">
                    <label>Mode Of Payment</label>
                    <select>
                        <option>Cash</option>
                    </select>
                </div>

                <div class="purchase_medicine_mod_of_payment">
                    <label>Discount</label>
                    <input type="text" name="" value="0">
                </div>

                <div class="purchase_medicine_mod_of_payment">
                    <label>Other Discount(%)</label>
                    <div class="grp">
                        <input class="input-tiny" type="text" value="0">
                        <input type="text" name="" value="0">
                    </div>
                </div>

                <div class="purchase_medicine_mod_of_payment">
                    <label>Tax</label>
                    <input type="text" name="" value="0">
                </div>

                <div class="purchase_medicine_mod_of_payment">
                    <label>Vat(%)</label>
                    <div class="grp">
                        <input class="input-tiny" type="text" value="0">
                        <input type="text" name="" value="0">
                    </div>
                </div>

                <div class="purchase_medicine_mod_of_payment">
                    <label>Net Amount</label>
                    <input type="text" name="" value="0">
                </div>

            </div> <!-- right_box -->

            
        </div> <!-- left -->
        <div class="right">
            <button class="btn-save"><i class="fa fa-floppy-o"></i> Save</button>
            <button class="btn-save"><i class="fa fa-refresh"></i> Update</button>
            <button class="btn-save"><i class="fa fa-sign-out"></i> Exit</button>
        </div> <!-- dont delete this div -->
    </div> <!-- purchase_medicine_bottom -->





</div> <!-- purchase_medicine -->

</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>