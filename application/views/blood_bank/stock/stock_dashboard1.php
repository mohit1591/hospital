<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $page_title.PAGE_TITLE; ?></title>
	<meta name="viewport" content="width=1024">
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">

	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

	<!-- links -->
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">

	<!-- js -->
	<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
	<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

	<!-- datatable js -->


	<style>

		span.circle {background:green;margin:auto;padding:1rem;color:#fff;border-radius:50px;display:inline-block;}
		.abc_box {float:left;width:100%;border:2px solid green;padding:20px;margin-bottom:20px;border-radius:2px;}
		.repeatDiv{float:left;width:100%;margin-top: 20px}
	</style>
	<script type="text/javascript">
		$(document).ready(function() { 

			$.ajax({
				url: "<?php echo base_url(); ?>blood_bank/stock/advance_search_dashboard/",
				type: "post",
				data: $('#new_stock_dashboard').serialize(),
				success: function(result) 
				{ 
					$.ajax ({
						"url": "<?php echo base_url('blood_bank/stock/stock_dashboard')?>",
						"type": "POST"
					});


				}
			}); 

		}); 



	</script>

</head>

<body>


	<div class="container-fluid">
		<?php
		$this->load->view('include/header');
		$this->load->view('include/inner_header');
		?>
		<!-- ============================= Main content start here ===================================== -->
		<section class="userlist">
			<div class="container">
				<form id="new_stock_dashboard"/>
				<div class="row m-b-5">
					<div class="col-md-12">

						<ul class="list-inline">
							<li>
								From Date 
								<input type="text" name="start_date" value="<?php echo $form_data['start_date'];?>" id="start_date_p" class=" m_input_default"  onkeyup="return form_submit();">
							</li>
							<li>
								To Date 
								<input type="text" name="end_date" id="end_date_p" value="<?php echo $form_data['end_date'];?>" class=" m_input_default"  onkeyup="return form_submit();">
							</li>
							<li>
								Blood Group
								<select name="blood_group" onchange="return form_submit();">
									<option value="">Select Blood Group</option>
									<?php if(!empty($blood_groups))
									{ 

										?>
										<option value="all" <?php if($form_data['blood_grp_id']=='all'){echo 'selected';} ?>>All</option>
										<?php foreach($blood_groups as $groups)
											{?>
										<option value="<?php echo $groups->id;?>" <?php if($form_data['blood_grp_id']==$groups->id){echo 'selected';}?>><?php echo $groups->blood_group; ?></option>
										<?php 
									} 
								}?>

							</select>
						</li>

						 <input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">
							<!-- <li>
								<button type="submit" class="btn-custom"><i class="fa fa-search"></i> Search</button>
							</li> -->
						</ul>

					</div>
				</div>
			</form>
			<br>
			<?php if(isset($all_data['all_blood_group']))
			{ 
				//print_r($all_data);
			
			$arr=array();
			foreach($all_data['all_blood_group'] as $data_each)
			 { //print_r($data_each);
			 	$count=0;
			 	?>
				<div class="repeatDiv">
				<div class="row m-b-5">
					<div class="col-md-6 text-right" style="border-right:2px solid green;">
						<span class="circle"><?php echo $data_each['blood_group'];?></span> 
					</div>  
					<div class="col-md-6 text-left">
						<span class="circle" style="background:#fff;color:#000;" id="total_blood_group_qty_<?php echo $data_each['blood_group_id']; ?>"><?php ?></span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="abc_box">

							<div class="row">
								<div class="col-md-6 text-center">
									<p>
										<button class="btn btn-success">Tested</button> <br>
										<p></p>

										<?php $i=1;$old_value=0;if(!empty($data_each['tested_data']))
										{
										foreach($data_each['tested_data'] as $tested_data)
										{
											$colour = ($i%2 == 0)? 'btn-primary': 'btn-danger';
										$bag_type_id='';
										$exist_ids='';
										$get_stoc_qty= get_stock_quantity_tested_data($bag_type_id="",$tested_data['component_id'][0],$exist_ids='',$tested_data['donor_id'][0],$data_each['blood_group_id']) ;
										//if($get_stoc_qty['total_qty']>0){

											?>
										<button class="btn <?php echo $colour; ?>" style="width:124px;"><?php echo $tested_data['component_name'][0];?></button> 
										<?php 
										$count= $get_stoc_qty['total_qty']+$count;
										 echo $get_stoc_qty['total_qty'];
										?> <br>
										<p></p>
										<?php $i++; //}
										} }?>
									</p>

									

								</div>
								<div class="col-md-6 text-center">
									
									<button class="btn btn-danger">Untested</button> <br>
									<p></p>


									<?php if(!empty($data_each['untested_data']))
										{ $i=1;
										$uniqueArray=array();
											foreach($data_each['untested_data'] as $untested_data)
										{
										   
										    if(!in_array($untested_data['component_id'][0],$uniqueArray))
										    {
										       $uniqueArray[] =$untested_data['component_id'][0];
											$bag_type_id='';
											$exist_ids='';
											$get_stoc_qty= get_stock_quantity_untested_data($bag_type_id="",$untested_data['component_id'][0],$exist_ids='',$untested_data['donor_id'][0],$data_each['blood_group_id']) ;
										//	if($get_stoc_qty['total_qty']>0){
											$colour = ($i%2 == 0)? 'btn-primary': 'btn-danger';?>
										<button class="btn <?php echo $colour;?>" style="width:124px;"><?php echo $untested_data['component_name'][0];?></button> 
										<?php 
										 
										 $count=$count+$get_stoc_qty['total_qty'];
										echo $get_stoc_qty['total_qty'];
										?> <br>
										<p></p>
										<?php //} 
										}} } ?>
								</div>
							</div></div>
							</div> <!-- col-md-6 -->

							<div class="col-md-6">
								<div class="abc_box">

									<div class="row">
										<div class="col-md-4 text-center">
											<button class="btn btn-success">Issued</button> <br>
											<p></p>
											<?php if(!empty($data_each['issued_data']))
											{ $i=1;
											foreach($data_each['issued_data'] as $issued_data)
											{
												$colour = ($i%2 == 0)? 'btn-default': 'btn-warning';
												$bag_type_id='';
												$exist_ids='';
												$get_stoc_qty= get_issued_component_quantity($bag_type_id="",$issued_data['component_id'][0],$exist_ids='',$issued_data['donor_id'][0],$data_each['blood_group_id']) ;
												//if($get_stoc_qty['total_qty']>0){
												?>
											<button class="btn <?php echo $colour; ?>" style="width:124px;"><?php echo $issued_data['component_name'][0];?></button> 
											<?php 
											//$count=$count+$get_stoc_qty['total_qty'];
											echo $get_stoc_qty['total_qty'];
											?> 
										<br>
											<p></p>
											<?php $i++; //} 
											} }?>
										</div>  
										<div class="col-md-4 text-center">
											<button class="btn btn-danger">Donation</button> <br>
											<p></p>
											<?php $i=1;if(!empty($data_each['donated_data']))
										{
										foreach($data_each['donated_data'] as $donated_data)
										{
											
											$colour = ($i%2 == 0)? 'btn-success': 'btn-warning';
											$bag_type_id='';
											$exist_ids='';
											$get_stoc_qty= get_stock_quantity($bag_type_id="",$donated_data['component_id'][0],$exist_ids='',$donated_data['donor_id'][0],$data_each['blood_group_id']) ;
											if($get_stoc_qty['total_qty']>0){
											?>
										<button class="btn <?php echo $colour; ?>" style="width:124px;"><?php echo $donated_data['component_name'][0];?></button> 

										<?php 
										//$count=$count+$get_stoc_qty['total_qty'];
										echo $get_stoc_qty['total_qty'];
										?>

										<br>
										<p></p>
										<?php $i++;}} }?>
										</div> 
										<div class="col-md-4 text-center">
											<button class="btn btn-info">Discard/Expiry</button> <br>
											<p></p>

											<?php $i=1;if(!empty($data_each['expired']))
										{
										    $uniqueArray_ex = array();
										foreach($data_each['expired'] as $expired)
										{
											$colour = ($i%2 == 0)? 'btn-primary': 'btn-danger';
											$bag_type_id='';
											$exist_ids='';
											
											if(!in_array($expired['component_id'][0],$uniqueArray_ex))
										    {
										        $uniqueArray_ex[] =$expired['component_id'][0];
											$get_stoc_qty= get_stock_quantity_expired_data($bag_type_id="",$expired['component_id'][0],$exist_ids='',$expired['donor_id'][0],$data_each['blood_group_id'],$expired['expiry_time'][0]) ;
										//	if($get_stoc_qty['total_qty']>0){
										?>
										<button class="btn <?php echo $colour;?>" style="width:124px;"><?php echo $expired['component_name'][0];?></button> 
										<?php 
										//$count=$count+$get_stoc_qty['total_qty'];
										echo $get_stoc_qty['total_qty'];
										?> <br>
										<p></p>
										<?php $i++;}
										}} ?>
										<?php //echo $count;
										 ?>
										 <script type="text/javascript">
										 	$('#total_blood_group_qty_<?php echo $data_each['blood_group_id']; ?>').html('<?php echo $count; ?>');
										 </script>
										</div>
									</div> 
									
								</div>

							</div>
						</div> <!-- col-md-6 -->
					</div> <!-- repeatDiv -->

			<?php } } else{ $count=0;  ?>

			<div class="repeatDiv">
				<div class="row m-b-5">
					<div class="col-md-6 text-right" style="border-right:2px solid green;">
						<span class="circle"><?php if(isset($blood_group)){echo $blood_group;}else {echo '';}?></span> 
					</div>  
					<div class="col-md-6 text-left">
						<span class="circle" style="background:#fff;color:#000;" id="total_blood_group_qty_<?php echo $form_data['blood_grp_id']; ?>"></span>
					</div>
				</div>





				<div class="row">
					<div class="col-md-6">
						<div class="abc_box">

							<div class="row">
								<div class="col-md-6 text-center">
									<p>
										<button class="btn btn-success">Tested</button> <br>
										<p></p>
										<?php $i=1;$old_value=0;if(!empty($all_data['tested_data']))
										{
										foreach($all_data['tested_data'] as $tested_data)
										{
										    //echo $form_data['blood_grp_id'];
											$colour = ($i%2 == 0)? 'btn-primary': 'btn-danger';
										$bag_type_id='';
										$exist_ids='';
										$get_stoc_qty= get_stock_quantity_tested_data($bag_type_id="",$tested_data['component_id'][0],$exist_ids='',$tested_data['donor_id'][0],$form_data['blood_grp_id']) ;
									//	if($get_stoc_qty['total_qty']>0){

											?>
										<button class="btn <?php echo $colour; ?>" style="width:124px;"><?php echo $tested_data['component_name'][0];?></button> 
										<?php 
										
										 echo $get_stoc_qty['total_qty'];
										 $count=$count+$get_stoc_qty['total_qty'];
										?> <br>
										<p></p>
										<?php $i++; //}
										} }?>
									</p>

									

								</div>
								<div class="col-md-6 text-center">
									
									<button class="btn btn-danger">Untested</button> <br>
									<p></p>


									<?php 
							
                                        
                                        //$unique = array_multi_unique($all_data['untested_data']);
                                        
                                       //print '<pre>'; print_r($unique);
									foreach($all_data['untested_data'] as $untested_data)
									{
									   
                                        $component_name[]=$untested_data['component_name'][0];
                                       
									}
									//print '<pre>'; print_r($all_data['untested_data']);die;
									if(!empty($all_data['untested_data']))
										{ $i=1;
									
									    $uniqueArray = array();
										foreach($all_data['untested_data'] as $untested_data)
										{
										    //echo $form_data['blood_grp_id'];
										    if(!in_array($untested_data['component_id'][0],$uniqueArray))
										    {
										       $uniqueArray[] =$untested_data['component_id'][0];
										    
										   // echo $untested_data['donor_id'][0];
											$bag_type_id='';
											$exist_ids='';
											
											$get_stoc_qty= get_stock_quantity_untested_data($bag_type_id="",$untested_data['component_id'][0],$exist_ids='',$untested_data['donor_id'][0],$form_data['blood_grp_id']) ;
											//if($get_stoc_qty['total_qty']>0){
											$colour = ($i%2 == 0)? 'btn-primary': 'btn-danger';?>
										<button class="btn <?php echo $colour;?>" style="width:124px;"><?php echo $untested_data['component_name'][0];?></button> 
										<?php 
										
										echo $get_stoc_qty['total_qty'];
										$count=$count+$get_stoc_qty['total_qty'];
										?> <br>
										<p></p>
										<?php }
										}}?>
								</div>
							</div></div>
							</div> <!-- col-md-6 -->

							<div class="col-md-6">
								<div class="abc_box">

									<div class="row">
										<div class="col-md-4 text-center">
											<button class="btn btn-success">Issued</button> <br>
											<p></p>
											<?php  if(!empty($all_data['issued_data']))
											{ $i=1;
											foreach($all_data['issued_data'] as $issued_data)
											{
											    //print_r($issued_data['component_name'][0]);
												$colour = ($i%2 == 0)? 'btn-default': 'btn-warning';
												$bag_type_id='';
												$exist_ids='';
												$get_stoc_qty= get_issued_component_quantity($bag_type_id="",$issued_data['component_id'][0],$exist_ids='',$issued_data['donor_id'][0]) ;
												//if($get_stoc_qty['total_qty']>0){
												?>
											<button class="btn <?php echo $colour; ?>" style="width:124px;"><?php echo $issued_data['component_name'][0];?></button> 
											<?php 
											
											echo $get_stoc_qty['total_qty'];
											//$count=$count+$get_stoc_qty['total_qty'];
											?> 
										<br>
											<p></p>
											<?php $i++; //} 
											} }?>
										</div>  
										<div class="col-md-4 text-center">
											<button class="btn btn-danger">Donation</button> <br>
											<p></p>
											<?php $i=1;if(!empty($all_data['donated_data']))
										{
										foreach($all_data['donated_data'] as $donated_data)
										{
											$colour = ($i%2 == 0)? 'btn-success': 'btn-warning';
											$bag_type_id='';
											$exist_ids='';
											$get_stoc_qty= get_stock_quantity($bag_type_id="",$donated_data['component_id'][0],$exist_ids='',$donated_data['donor_id'][0],$form_data['blood_grp_id']) ;
											if($get_stoc_qty['total_qty']>0){
											?>
										<button class="btn <?php echo $colour; ?>" style="width:124px;"><?php echo $donated_data['component_name'][0];?></button> 

										<?php 
										
										echo $get_stoc_qty['total_qty'];
										//$count=$count+$get_stoc_qty['total_qty'];
										?>

										<br>
										<p></p>
										<?php $i++;}
										} }?>
										</div> 
										<div class="col-md-4 text-center">
											<button class="btn btn-info">Discard/Expiry</button> <br>
											<p></p>

											<?php $i=1;if(!empty($all_data['expired']))
										{
										    $uniqueArray_ex = array();
										foreach($all_data['expired'] as $expired)
										{
										    //echo $expired['expiry_time'][0];
											$colour = ($i%2 == 0)? 'btn-primary': 'btn-danger';
											$bag_type_id='';
											$exist_ids='';
											if(!in_array($expired['component_id'][0],$uniqueArray_ex))
										    {
										        $uniqueArray_ex[] =$expired['component_id'][0];
											$get_stoc_qty= get_stock_quantity_expired_data($bag_type_id="",$expired['component_id'][0],$exist_ids='',$expired['donor_id'][0],$form_data['blood_grp_id'],$expired['expiry_time'][0]) ;
										//	if($get_stoc_qty['total_qty']>0){
										?>
										<button class="btn <?php echo $colour;?>" style="width:124px;"><?php echo $expired['component_name'][0];?></button> 
										<?php 
										
										echo $get_stoc_qty['total_qty'];
										//$count=$count+$get_stoc_qty['total_qty'];
										?> <br>
										<p></p>
										<?php $i++;} 
										}}?>
										<?php //echo $count;
										 ?>
										 <script type="text/javascript">
										 	$('#total_blood_group_qty_<?php echo $form_data['blood_grp_id']; ?>').html('<?php echo $count; ?>');
										 </script>
										</div>
									</div> 
									
								</div>

							</div>
						</div> <!-- col-md-6 -->
					</div> <!-- repeatDiv -->
					<?php }?>


				</div>

			</section> <!-- cbranch -->
			<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
			<?php
			$this->load->view('include/footer');
			?>

		</div><!-- container-fluid -->
		<!--new css-->


		<!--new css-->
		<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
		<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script> 
		<script>

			function form_submit()
			{

				$('#new_stock_dashboard').delay(200).submit();
			}

			$("#new_stock_dashboard").on("submit", function(event) { 
				event.preventDefault(); 
				$('#overlay-loader').show();
				var branch_type= $('#branch_type').val();
				if(branch_type=="self")
				{
					$('#for_other').removeClass('hide');
					$('#for_brach_type').addClass('hide');
					$('#for_archive').removeClass('hide');

				}
				else
				{
					$('#for_other').addClass('hide');
					$('#for_brach_type').removeClass('hide');
					$('#for_archive').addClass('hide');
				}
				$.ajax({
					url: "<?php echo base_url(); ?>blood_bank/stock/advance_search_dashboard/",
					type: "post",
					data: $(this).serialize(),
					success: function(result) 
					{
						$.ajax ({
							"url": "<?php echo base_url('blood_bank/stock/dash_board_all_data')?>",
							"type": "POST",
							success: function(result) 
							{
								window.location.reload();
							}
						});
					}
				});
			});

			function reset_search_dashboard()
			{ 
				$('#start_date_p').val('');
				$('#end_date_p').val('');
				$.ajax({url: "<?php echo base_url(); ?>blood_bank/stock/reset_search_dashboard/", 
					success: function(result)
					{ 
						reload_table();
					} 
				}); 
			}
			var today =new Date();
			$('#start_date_p').datepicker({
				format: "dd-mm-yyyy",
				maxDate : "+0d",
				onSelect: function (selected) {
					form_submit();
					var dt = new Date(selected);
					dt.setDate(dt.getDate() + 1);
					$("#end_date_p").datepicker("option", "minDate", selected);
				}
			})

			$('#end_date_p').datepicker({
				format: "dd-mm-yyyy",

				onSelect: function (selected) {
					form_submit();
					var dt = new Date(selected);
					dt.setDate(dt.getDate() - 1);
					$("#start_date_p").datepicker("option", "maxDate", selected);
				}
			})
		// $('.datepicker').datepicker({
  //                   format: "dd-mm-yyyy",
  //                   autoclose: true
  //               });  

 function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>blood_bank/stock/reset_search_dashboard/", 
      success: function(result)
      { 
        $(ele).find(':input').each(function() {
                switch(this.type) {

                case 'select-multiple':
                case 'select-one':
                case 'text':
                case 'textarea':
                $(this).val('');
                break;
                case 'checkbox':
                case 'radio':
                this.checked = false;
                }
          });
        window.location.reload();
      } 
  }); 

}
</script>



<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>


</body>
</html>