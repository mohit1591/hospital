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
    
    <!--  // prescription button modal -->


                <div class="row">
                    <div class="col-xs-2">
                        <button class="btn-commission2" type="button"  data-toggle="modal" data-target="#prescription_select_patient"> Select Patient</button>
                    </div> <!-- 5 -->
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><label>OPD No.</label></div>
                            <div class="col-xs-8">
                                <input type="text" name="">
                            </div>
                        </div>
                        <div class="row m-b-5 m-b-5">
                            <div class="col-xs-4"><label>Pateint Reg No.</label></div>
                            <div class="col-xs-8">
                                <input type="text" name="">
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><label>Pateint Name</label></div>
                            <div class="col-xs-8">
                                <input type="text" name="">
                            </div>
                        </div>
                    </div> <!-- 5 -->
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><label>Mobile No.</label></div>
                            <div class="col-xs-8">
                                <input type="text" name="">
                            </div>
                        </div>
                        <div class="row m-b-5 m-b-5">
                            <div class="col-xs-4"><label>Gender</label></div>
                            <div class="col-xs-8">
                                <input type="radio" name=""> Male
                                <input type="radio" name=""> Female
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><label>DOB</label></div>
                            <div class="col-xs-8">
                                <input type="text" name="" class="input-tiny"> Y
                                <input type="text" name="" class="input-tiny"> M
                                <input type="text" name="" class="input-tiny"> D
                            </div>
                        </div>
                    </div> <!-- 5 -->
                </div> <!-- row -->


                <div class="row m-t-10">
                    <div class="col-xs-12">
                        <label>
                            <b>BP</b> 
                            <input type="text" name="" class="input-tiny"> 
                            <span>mm/Hg</span>
                        </label> &nbsp;
                        <label>
                            <b>Temp</b> 
                            <input type="text" name="" class="input-tiny"> 
                            <span>&#x2109;</span>
                        </label> &nbsp;
                        <label>
                            <b>Weight</b> 
                            <input type="text" name="" class="input-tiny"> 
                            <span>kg</span>
                        </label> &nbsp;
                        <label>
                            <b>Height</b> 
                            <input type="text" name="" class="input-tiny"> 
                            <span>cm</span>
                        </label> &nbsp;
                        <label>
                            <b>Spo2</b> 
                            <input type="text" name="" class="input-tiny"> 
                            <span>%</span>
                        </label> &nbsp;
                    </div>
                </div> <!-- row -->


                <div class="row m-t-10">
                    <div class="col-xs-11">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab_previous_history">Previous History</a></li>
                            <li><a data-toggle="tab" href="#tab_personal_history">Personal History</a></li>
                            <li><a data-toggle="tab" href="#tab_chief_complaint">Chief Complaint</a></li>
                            <li><a data-toggle="tab" href="#tab_examination">Examination</a></li>
                            <li><a data-toggle="tab" href="#tab_diagnosis">Diagnosis</a></li>
                            <li><a data-toggle="tab" href="#tab_test_result">Test Result</a></li>
                            <li><a data-toggle="tab" href="#tab_prescription">Prescription</a></li>
                            <li><a data-toggle="tab" href="#tab_suggestions">Suggestions</a></li>
                            <li><a data-toggle="tab" href="#tab_remarks">Remark</a></li>
                            <li><a data-toggle="tab" href="#tab_appointment">Next Appointment</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="tab_previous_history" class="tab-pane fade in active">
                                <div class="row m-t-10">
                                    <div class="col-xs-8">
                                        <div class="well"><h4>Previous History</h4></div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="well tab-right-scroll">
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tab_personal_history" class="tab-pane fade">
                                <div class="row m-t-10">
                                    <div class="col-xs-8">
                                        <div class="well"><h4>Personal History</h4></div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="well tab-right-scroll">
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tab_chief_complaint" class="tab-pane fade">
                                <div class="row m-t-10">
                                    <div class="col-xs-8">
                                        <div class="well"><h4>Chief Complaint</h4></div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="well tab-right-scroll">
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tab_examination" class="tab-pane fade">
                                <div class="row m-t-10">
                                    <div class="col-xs-8">
                                        <div class="well"><h4>Examination</h4></div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="well tab-right-scroll">
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tab_diagnosis" class="tab-pane fade">
                                <div class="row m-t-10">
                                    <div class="col-xs-8">
                                        <div class="well"><h4>Diagnosis</h4></div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="well tab-right-scroll">
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tab_test_result" class="tab-pane fade">
                                <div class="row m-t-10">
                                    <div class="col-xs-12">
                                        <div class="well tab-right-scroll">
                                            <table class="table table-bordered table-striped">
                                                <tbody>
                                                    <tr>
                                                        <td>Test Name</td>
                                                        <td width="80">
                                                            <a href="javascript:void(0)" class="btn-w-60">Add</a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td width="80">
                                                            <a href="javascript:void(0)" class="btn-w-60">Delete</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tab_prescription" class="tab-pane fade">
                                <div class="row m-t-10">
                                    <div class="col-xs-12">
                                        <div class="well tab-right-scroll">
                                            <table class="table table-bordered table-striped">
                                                <tbody>
                                                    <tr>
                                                        <td>Medicine</td>
                                                        <td>Type</td>
                                                        <td>Dose</td>
                                                        <td>Duration (Days)</td>
                                                        <td>Frequency</td>
                                                        <td>Advoice</td>
                                                        <td width="80">
                                                            <a href="javascript:void(0)" class="btn-w-60">Add</a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td width="80">
                                                            <a href="javascript:void(0)" class="btn-w-60">Delete</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tab_suggestions" class="tab-pane fade">
                                <div class="row m-t-10">
                                    <div class="col-xs-8">
                                        <div class="well"><h4>Suggestion</h4></div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="well tab-right-scroll">
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="tab_remarks" class="tab-pane fade">
                                <div class="well tab-right-scroll" style="overflow-x: hidden;padding:5px 10px;">
                                <div class="row m-t-10">
                                    <div class="col-xs-1">
                                        <label><b>Remark</b></label>
                                    </div>
                                    <div class="col-xs-11">
                                        <textarea class="form-control" rows="8"></textarea>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <div id="tab_appointment" class="tab-pane fade">
                                <div class="well tab-right-scroll" style="overflow-x: hidden;padding:5px 10px;">
                                    <div class="row m-t-10">
                                        <div class="col-xs-2">
                                            <label><b>Next Appointment</b></label>
                                        </div>
                                        <div class="col-xs-10">
                                            <input type="checkbox" name="">
                                            <input type="date" name="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div> <!-- 11 -->
                





                
           <div class="col-xs-1">
            <div class="prescription_btns">
                <button class="btn-save" type="button" name=""><i class="fa fa-floppy-o"></i> Save</button>
                <button class="btn-save" type="button" name=""><i class="fa fa-history"></i> History</button>
                <button class="btn-save" type="button" name=""><i class="fa fa-info-circle"></i> View</button>
                <button class="btn-save" type="button" name=""><i class="fa fa-upload"></i> Upload</button>
                <button class="btn-save" type="button" name="" data-dismiss="modal"><i class="fa fa-sign-out"></i> Exit</button>
            </div>


            </div> <!-- row -->
        






<!-- // Select patient for select_patient button -->
<div id="prescription_select_patient" class="modal modal fade in modal-95 m-t-30" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php echo $prescription_select_patient; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row m-b-10">
                    <div class="col-xs-12">
                        <label>Search By:</label> &nbsp;
                        <select>
                            <option>Prashant</option>
                            <option>Radha Mohan</option>
                        </select> &nbsp;
                        <input type="date" name=""> &nbsp;
                        <button type="button" name="" class="btn-new2"><i class="fa fa-search"></i> Search</button>
                    </div>  
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="prescription_select_patient">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="40" style="padding-left: 1em;"><input type="checkbox" name=""></th>
                                        <th>OPD No.</th>
                                        <th>Patient Reg. No.</th>
                                        <th>Patient Name</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>OPD Date</th>
                                        <th>Mobile</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="40" align="center"><input type="checkbox" name=""></td>
                                        <td>2017/0014</td>
                                        <td>2017/0020</td>
                                        <td>Testopd</td>
                                        <td>45 Year 8 month 16 days</td>
                                        <td>Male</td>
                                        <td>5/9/2017 12:00:00 AM</td>
                                        <td>9711750000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>  
                </div>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <!-- <button class="btn-save" type="button" name=""> Save</button> -->
                <button class="btn-cancel" type="button" name="" data-dismiss="modal"> Close</button>
            </div>
        </div>
    </div>
</div>

</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>