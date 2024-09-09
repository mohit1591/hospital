<?php
$users_data = $this->session->userdata('auth_users'); 
$report_search = $this->session->userdata('report_search'); 
//echo '<pre>'; print_r($data_list);die;
?> 
<!DOCTYPE html>
<html>
<head>
	<script src="<?php echo ROOT_ASSETS_PATH; ?>vendors/jquery/dist/jquery.min.js" type="text/javascript"></script>
  <title></title>
  <style>
    *{margin:0;padding:0;box-sizing:border-box;-webkit-box-sizing:border-box;}
    page {
    	width: 21cm;
      background: white;
      display: block;
      margin: 1em auto 0;
      
      padding-top:20px !important;
      padding-bottom:20px !important;
      margin-bottom: 0.5cm;
      
    }
    page[size="A4"] {  

      padding: 1em;
      font-size: 13px;
      float: left;
    }
    @page {
      size: auto;   
      margin: 0;  
    }
  </style>
</head>
<body style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">

  <page size="A4">
    
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
        <td style="text-align:center;font-size:18px;">
        	<span style="border-bottom:2px solid #111;">CRM Report</span>
        	<input type="button" name="button_print" value="Print" id="print" onClick="return my_function();" style="float: right;" />
        </td>
      </tr> 
    </table>


    <table cellpadding="0" cellspacing="0" border="1" width="100%" style="margin-top: 20px;">
       <thead>
       	    <tr>
       	    	<th>S.No.</th>
       	    	<th>Lead ID</th>
       	    	<th>Name</th>
       	    	<th>Phone</th>
       	    	<th>Department</th>
       	    	<th>Follow-Up Date/Time</th>
       	    	<th>Appointment Date/Time</th>
       	    	<th>Call Status</th>
       	    	<th>Created By</th>
       	    	<th>Created Date</th>
       	    </tr>
       </thead> 
       <tbody>
       	<?php
         if(!empty($data_list))
         {
         	$i=1;
         	foreach($data_list as $data)
         	{
            ?>
             <tr>
	       	 	<td><?php echo  $i;?></td>
	       	 	<td><?php echo  $data['crm_code']; ?></td>
	       	 	<td><?php echo  $data['name']; ?></td>
	       	 	<td><?php echo  $data['phone']; ?></td>
	       	 	<td><?php echo  $data['department']; ?></td>
	       	 	<td><?php echo  date('d-m-Y', strtotime($data['followup_date2'])); ?> <?php echo  date('h:i A', strtotime($data['followup_time2'])); ?></td>
	       	 	<td><?php echo  date('d-m-Y', strtotime($data['appointment_date'])); ?> <?php echo  date('h:i A', strtotime($data['appointment_time'])); ?></td> 
	       	 	<td><?php echo  $data['callstatus']; ?></td>
	       	 	<td><?php echo  $data['uname']; ?></td>
	       	 	<td><?php echo  date('d-m-Y h:i A', strtotime($data['created_date'])); ?></td>
	       	 </tr>
            <?php	
            $i++;	
         	}
         }
         else
         {
         ?>
         <tr>
       	 	<td colspan="10" style="text-align: center; font-weight: bold;">Record not found.</td>
       	 </tr>
         <?php	
         }
       	?> 
       </tbody>
    </table>
    <!-- Branch list start  -->
 
</page>

                  </body>
                  </html>
                  
                  <style type="text/css" media="print">
                    @page 
                    {
                      size:  auto;   /* auto is the initial value */
                      margin: 0mm;  /* this affects the margin in the printer settings */
                    }

                    html
                    {
                      /*background-color: #FFFFFF;*/ 
                      margin: 0px;  /* this affects the margin on the html before sending to printer */
                    }

                    body
                    {
                      border: solid 0px black ;
                      /* margin: 10mm 15mm 10mm 15mm;  margin you want for the content */
                    }
                  </style>
                  <script type="text/javascript">
                  	function my_function()
					      {
					        $("#print").hide();
					        window.print();
					      }
					      my_function();
                  </script>