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
    
<div class="medicine_entry">
    
        <button type="button" data-target="#medicine_entry" data-toggle="modal" class="btn btn-success">Medicine Entry</button>

        <div id="medicine_entry" class="modal fade modal-50" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="submit" data-dismiss="modal" class="close">&times;</button>
                        <h4><?php echo $page_title; ?></h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="grp-full">
                                        <div class="b1"><label>Medicine Code <span class="star">*</span></label></div>
                                        <div class="b2">
                                            <input type="text" name="">
                                        </div>
                                        <div class="b3"></div>
                                </div>  <!-- grp-full -->

                                <div class="grp-full">
                                        <div class="b1"><label>Medicine Name <span class="star">*</span></label></div>
                                        <div class="b2">
                                            <input type="text" name="">
                                        </div>
                                        <div class="b3"></div>
                                </div>  <!-- grp-full -->

                                <div class="grp-full">
                                        <div class="b1"><label>Unit <span class="star">*</span></label></div>
                                        <div class="b2">
                                            <select>
                                                <option>Power</option>
                                            </select>
                                        </div>
                                        <div class="b3">
                                            <button class="btn-new">New</button>
                                        </div>
                                </div>  <!-- grp-full -->

                                <div class="grp-full">
                                        <div class="b1"><label>Unit 2<sup>nd</sup> <span class="star">*</span></label></div>
                                        <div class="b2">
                                            <select>
                                                <option>Power</option>
                                            </select>
                                        </div>
                                        <div class="b3">
                                            <button class="btn-new">New</button>
                                        </div>
                                </div>  <!-- grp-full -->

                                <div class="grp-full">
                                        <div class="b1"><label>Conversion <span class="star">*</span></label></div>
                                        <div class="b2">
                                           <input type="text" name="">
                                        </div>
                                        <div class="b3"></div>
                                </div>  <!-- grp-full -->

                                <div class="grp-full">
                                        <div class="b1"><label>Min. Alert</label></div>
                                        <div class="b2">
                                           <input type="text" name="">
                                        </div>
                                        <div class="b3"></div>
                                </div>  <!-- grp-full -->

                                <div class="grp-full">
                                        <div class="b1"><label>Packing</label></div>
                                        <div class="b2">
                                           <input type="text" name="">
                                        </div>
                                        <div class="b3"></div>
                                </div>  <!-- grp-full -->

                                <div class="grp-full">
                                        <div class="b1"><label>Rack No.</label></div>
                                        <div class="b2">
                                            <select>
                                                <option>Rack001</option>
                                            </select>
                                        </div>
                                        <div class="b3">
                                            <button class="btn-new">New</button>
                                        </div>
                                </div>  <!-- grp-full -->


                                <div class="grp-full">
                                        <div class="b1"><label>Salt</label></div>
                                        <div class="b2">
                                            <input type="text" name="">
                                        </div>
                                        <div class="b3"></div>
                                </div>  <!-- grp-full -->

                                <div class="grp-full">
                                        <div class="b1"><label>Mfg. Company</label></div>
                                        <div class="b2">
                                            <select>
                                                <option>-Select-</option>
                                            </select>
                                        </div>
                                        <div class="b3">
                                            <button class="btn-new">New</button>
                                        </div>
                                </div>  <!-- grp-full -->

                                <div class="grp-full">
                                        <div class="b1"><label>MRP</label></div>
                                        <div class="b2">
                                            <input type="text" name="" value="0.00">
                                        </div>
                                        <div class="b3"></div>
                                </div>  <!-- grp-full -->

                                <div class="grp-full">
                                        <div class="b1"><label>Purchase Rate</label></div>
                                        <div class="b2">
                                            <input type="text" name="" value="0.00">
                                        </div>
                                        <div class="b3"></div>
                                </div>  <!-- grp-full -->

                                <div class="grp-full">
                                        <div class="b1"><label>Vat(%)</label></div>
                                        <div class="b2">
                                            <input type="text" name="" value="0.00">
                                        </div>
                                        <div class="b3"></div>
                                </div>  <!-- grp-full -->


                            </div> <!-- 12 -->
                        </div> <!-- row -->
                    </div> <!-- modal_body -->

                    <div class="modal-footer">
                        <button type="submit" class="btn-save">Save</button>
                        <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
                    </div>
                </div> <!-- modal_content -->
            </div> <!-- modal-dialog -->
        </div> <!-- modal -->






</div> <!-- medicine_entry -->



</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>