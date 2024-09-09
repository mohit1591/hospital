<!DOCTYPE html>
<html>
<head>
   <title><?php echo $page_title.PAGE_TITLE; ?></title>
   <?php  $users_data = $this->session->userdata('auth_users'); ?>
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
   <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
   <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
   <!-- datatable js --> 
   <script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
   <script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>

   <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
   <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

</head>

<body style="padding-bottom: 70px;">
   <div class="container-fluid">
      <?php
      $this->load->view('include/header');
      $this->load->view('include/inner_header');
      ?>
   
 
 
   <div class="row m-t-5">
   	<div class="col-md-12">
   		<label class="btn btn-sm"><input type="radio" name="new_patient"> <span>New Patient</span></label>
   		<label class="btn btn-sm"><input type="radio" name="new_patient"> <span>Registered Patient</span></label>
   	</div>
   </div>    
 </div>
 


<section class="path-booking">
	
 
	<div class="row">
		<div class="col-xs-4"> <!--1 -->


			<div class="row mb-2">
				<div class="col-md-4"><b>UHID No.</b></div>
				<div class="col-md-8" id="">
					<input type="text" name="" value="" >
				</div>
			</div>

			<div class="row mb-2">
				<div class="col-md-4"><b>Patient Name</b></div>
				<div class="col-md-8">
					<select class="mr m_mr">
						<option value="">Select</option>                                          
					</select>
					<input type="text" class="mr-name" value="">
				</div>
			</div>
	    

	  <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Father Name</b></div>
	         <div class="col-md-8" id="">
	           <select class="mr m_mr" name="simulation_id" id="simulation_id" onchange="find_gender(this.value)">
                  <option value="">Select</option>                                          
                                    </select>
                <input type="text" name="name" class="mr-name alpha_space_name inputFocus m_name txt_firstCap" value=""></div>
	       </div>
	    </div>
	  </div>


	  <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Mobile No.</b></div>
	         <div class="col-md-8" id="">
	           <input type="text" name="" value="" ></div>
	       </div>
	    </div>
	  </div>

	  <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Gender</b></div>
	         <div class="col-md-8" id="">
	           <input type="radio" name="" value="" > <b>Male</b>
	           <input type="radio" name="" value="" > <b>Female</b>
	           <input type="radio" name="" value="" > <b>Other</b></div>
	       </div>
	    </div>
	  </div>

	  <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>DOB</b></div>
	         <div class="col-md-8" id="">
	           <input type="text" name="" value="" ></div>
	       </div>
	    </div>
	  </div>


	  <div class="row m-b-5">
       <div class="col-md-12">
          <div class="row">
            <div class="col-md-4"><b>Age</b></div>
            <div class="col-md-8 m-b-5" id="">
             <input type="text" id="age_y" name="" class="input-tiny m_tiny numeric"  maxlength="3" value=>  Y
              <input type="text" id="age_m" name=""  class="input-tiny m_tiny numeric" maxlength="2" value=>  M
              <input type="text" id="age_d" name=""  class="input-tiny m_tiny numeric"  maxlength="2" value=>  D 
               </div>
           </div>
        </div>
     </div> 

       <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Guardian Name</b></div>
	         <div class="col-md-8" id="">
	           <input type="text" name="" value="" ></div>
	       </div>
	    </div>
	  </div>

	      <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Guardian Email</b></div>
	         <div class="col-md-8" id="">
	           <input type="text" name="" value="" ></div>
	       </div>
	    </div>
	  </div>

	      <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Guardian Mobile No.</b></div>
	         <div class="col-md-8" id="">
	           <input type="text" name="" value="" ></div>
	       </div>
	    </div>
	  </div>

	    <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Relation</b></div>
	         <div class="col-md-8" id="">
	           <select  name="simulation_id" id="simulation_id">
                  <option value="">Select</option>                                         
                </select>
                </div>
	       </div>
	    </div>
	  </div>	  
    </div>
    
    
    <!-- [////////////2nd collum ////// ] -->
    <div class="col-xs-4 media_margin_left">   	
       <div class="row m-b-5">
	      <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Booking Date</b></div>
	         <div class="col-md-8" id="">
	           <input type="text" name="" value=".........../........./........" ></div>
	       </div>
	       </div>
	   </div>

       <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Booking Time</b></div>
	         <div class="col-md-8" id="">
	           <input type="text" name="" value=".........../........./........" ></div>
	       </div>
	    </div>
	   </div>

       <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Source</b></div>
	         <div class="col-md-8 m-b-5" id="">
	           <input type="text" name="" value=""></div>
	    </div>
	   </div>    

       <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div style="padding-left: 30px" class="col-md-4 m-b-5"><b>Destination</b></div>
	         <div style="padding-left: 20px" class="col-md-8" id="">
	           <input type="text" name="" value=""></div>
	       </div>
	    </div>
	   </div>    

       <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div style="padding-left: 30px" class="col-md-4"><b>Address</b></div>
	         <div style="padding-left: 20px" class="col-md-8" id="">
	           <input type="text" name="" value="" ></div>
	       </div>
	    </div>
   	   </div>
   
   	   <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b></b></div>
	         <div style="padding-left: 20px" class="col-md-8" id="">
	           <input type="text" name="" value="" ></div>
	       </div>
	    </div>
   	   </div>
   
   	   <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b></b></div>
	         <div style="padding-left: 20px" class="col-md-8" id="">
	           <input type="text" name="" value="" ></div>
	       </div>
	    </div>
   	   </div>

          <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div style="padding-left: 30px" class="col-md-4"><b>Country</b></div>
	         <div style="padding-left: 20px" class="col-md-8" id="">
	           <select  name="simulation_id" id="simulation_id">
                  <option value="">Select</option>                                         
                </select>
                </div>
	       </div>
	    </div>
   	   </div>  
   
       <div class="row m-b-5">
	     <div class="col-md-12">
	       <div class="row">
	         <div style="padding-left: 30px" class="col-md-4"><b>State</b></div>
	         <div style="padding-left: 20px" class="col-md-8" id="">
	           <select  name="simulation_id" id="simulation_id">
                  <option value="">Select</option>                                         
                </select>
                </div>
	          </div>
	       </div>
	    </div>
   
   
      	<div class="row m-b-5">
	      <div class="col-md-12">
	       <div class="row">
	         <div style="padding-left: 30px" class="col-md-4"><b>City</b></div>
	         <div style="padding-left: 20px" class="col-md-8" id="">
	           <select  name="simulation_id" id="simulation_id">
                  <option value="">Select</option>                                         
                </select>
                </div>
	          </div>
	       </div>
	    </div>   
   
       <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div style="padding-left: 30px" class="col-md-4"><b>Pincode</b></div>
	         <div style="padding-left: 20px" class="col-md-8" id="">
	           <input type="text" name="" value="" ></div>
	       </div>
	    </div>
	   </div>  
	   </div>
    </div>
    
    
    
    <!-- [////////////3rd collum ////// ] -->
    
   <div class="col-xs-4 media_margin_left">
	    <div class="row m-b-5">
	       <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Payment Mode</b></div>
	         <div class="col-md-8" id="">
	           <select  name="simulation_id" id="simulation_id">
                  <option value="">Select</option>                                         
                </select>
                </div>
	       </div>
	       </div>
	    </div>
   
          <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Total Amount</b></div>
	         <div class="col-md-8" id="">
	           <input type="text" name="" value=""></div>
	       </div>
	    </div>
   	      </div>
   
   
          <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Discount (%)</b></div>
	         <div class="col-md-8" id="">
	           <input type="text" name="" value=""></div>
	       </div>
	    </div>
   	      </div>
   
          <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Net Amount</b></div>
	         <div class="col-md-8" id="">
	           <input type="text" name="" value=""></div>
	       </div>
	    </div>
   	      </div>
   
          <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Paid Amount</b></div>
	         <div class="col-md-8" id="">
	           <input type="text" name="" value=""></div>
	       </div>
	    </div>
   	      </div>
   
          <div class="row m-b-5">
	    <div class="col-md-12">
	       <div class="row">
	         <div class="col-md-4"><b>Balance</b></div>
	         <div class="col-md-8" id="">
	           <input type="text" name="" value=""></div>
	       </div>
	    </div>
   	      </div>    
   
          <div style="padding-top: 50px" class="row m-b-5">
          <div class="col-md-12">
          <div class="row">
            <div class="col-md-4">
               <button style="background-color: #0e854f;color: white;width: 120px;height: 40px" type="button" class="btn ">Save</ button>
            </div>
            <div class="col-md-4"><button style="background-color: #0e854f;color: white;width: 120px;height: 40px" type="button" class="btn ">Print</ button></div>
            <div class="col-md-4"><button style="background-color: #0e854f;color: white;width: 120px;height: 40px" type="button" class="btn ">Exit</ button></div>              
              </div>
              </div>
            </div>       
          </div>
   </div>    
</section>



<!-- [////////////Footer ////// ] -->
   <!-- container-fluid -->
   <div class="container-fluid  navbar-fixed-bottom">
      <?php $this->load->view('include/footer'); ?>
   </div>
</body>
</html>


  

