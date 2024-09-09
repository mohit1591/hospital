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

		*{margin:0px;padding:0px;box-sizing:border-box;}
		.ss_box{display:flex;justify-content:flex-end;width:100%;margin:10px auto 0;padding:10px;font-size:14px;}
		.ss_inner {display:flex;justify-content:space-between;width:600px;}
		.btn_quick_search{display:inline-block;background:#5B9BD5;color:#fff;padding:15px;text-decoration:none;}
		.btn_border{display:inline-block;border:1px solid lightgray;color:#333;padding:15px;text-decoration:none;}
		.ss_right {display:flex;flex-direction:column;}
		.ss_right_box{width:200px;border:1px solid lightgray;padding:15px;margin:10px 0px 0;}
		.ss_box_content {display:flex;flex-direction:column;width:100%;margin:20px auto 20px auto;}
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
					<div class="col-md-9">

						<ul class="list-inline">
							
							<li>
								Component
								<select name="component" onchange="return form_submit();">
								    <option value="">Select Component</option>
								    
									
									<?php if(!empty($component_list))
									{ 

										?>
										
										<?php foreach($component_list as $components)
											{?>
										<option value="<?php echo $components->id;?>" <?php if($form_data['component']==$components->id){echo 'selected';}?>><?php echo $components->component; ?></option>
										<?php 
									} 
								}?>

							</select>
							</li>
							<li>
								Blood Group
								<select name="blood_group" onchange="return form_submit();">
									<option value="">Select Blood Group</option>
									<?php if(!empty($blood_groups))
									{ 

										?>
										<!--<option value="all" < ?php if($form_data['blood_grp_id']=='all'){echo 'selected';} ?>>All</option>-->
										<?php foreach($blood_groups as $groups)
											{?>
										<option value="<?php echo $groups->id;?>" <?php if($form_data['blood_grp_id']==$groups->id){echo 'selected';}?>><?php echo $groups->blood_group; ?></option>
										<?php 
									} 
								}?>

							</select>
						</li>

						<!-- <input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">-->
						 
						</ul>

					</div>
					<div class="col-md-3" style="align:right;">
                    <a href="<?php echo base_url('blood_bank/stock/stock_dashboard_excel'); ?>" class="btn-anchor m-b-2"><i class="fa fa-file-excel-o"></i> Excel</a>
                    <a href="<?php echo base_url('blood_bank/stock/'); ?>" class="btn-anchor m-b-2"><i class="fa fa-sign-out"></i> Exit</a>

            </div>
				</div>
			</form>






       
        <table id="table" class="table table-striped table-bordered donor_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="100%" style="padding:1px;"></th>
                </tr>
            </thead>  
        </table>
        
    




		
	<!-- 1 -->
	<div class="ss_box_content">
		
		<?php if(isset($all_data['all_blood_group']))
			{ 
				//print_r($all_data);
			
			$arr=array();
			foreach($all_data['all_blood_group'] as $data_each)
			 { //echo "<pre>";print_r($data_each); exit;
			 	//$count=0;

//echo 'ff'.$data_each['component_id'].'ffffff';

			 	?>
			<div style="margin-bottom:5px;">
				<a href="#!" class="btn_quick_search"><?php echo $data_each['blood_component'];?></a>
				<a href="#!" class="btn_border"><?php echo $data_each['blood_group'];?></a>
			</div>

			<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:14px;text-align:center;">
			<tr>
				<td width="120">Total QTY</td>
				<td style="background:#E1EFD8;">In-Stock</td>
				<td>Awaiting test results</td>
				<td>Alert Expiry soon (12 days)</td>
				<td style="background:red;">Alert (7 days)</td>
				<td width="50">QC</td>
				<td>Discarded <br> (expired)</td>
				<td>Discarded <br> (QC failed)</td>
				<td>Discarded <br> (Test failed)</td>
			</tr>

			
					

					<tr>
						<?php 
						        $total_aw=0;
						      
									$bag_type_id='';
									$exist_ids='';
									//$donated_data['component_id'][0]
									$get_stoc_qty= get_stock_dashboard_grand_total_quantity($bag_type_id="",$data_each['blood_component_id'],$exist_ids='',$donated_data['donor_id'][0],$data_each['blood_group_id']) ;
									if($get_stoc_qty['total_qty']>0){
									?>
										<?php if(!empty($get_stoc_qty['total_qty'])){ $total_aw =$total_aw+ $get_stoc_qty['total_qty']; } ?>
										<?php $i++;}
								
								?>
								
						      <td><?php echo $total_aw; ?></td>
										<?php
								$total_ex =0;		
							 
									
									$bag_type_id='';
									$exist_ids=''; //$donated_data['component_id'][0]
									$get_stoc_qty= get_stock_dashboard_quantity($bag_type_id="",$data_each['blood_component_id'],$exist_ids='',$donated_data['donor_id'][0],$data_each['blood_group_id']) ;
									if($get_stoc_qty['total_qty']>0){
									 if(!empty($get_stoc_qty['total_qty'])){ $total_ex =$total_ex+$get_stoc_qty['total_qty']; }  $i++;}
									
								?>
								<td><?php echo $total_ex; ?></td>
								<?php
							
								//echo $data_each['blood_component_id'].'commmmmm';
								$get_untested_qty= get_stock_quantity_awaiting_data('',$data_each['blood_component_id'],'','',$data_each['blood_group_id']) ;
								?>
								<td><?php if(!empty($get_untested_qty['total_qty'])){ echo $get_untested_qty['total_qty']; }else{  echo '0'; } ?></td>
										
						<?php 
						//alert 12 days
						$get_twelve_qty= get_stock_quantity_twelve_data('',$data_each['blood_component_id'],'','',$data_each['blood_group_id'],12) ;
									
									?>
										<td><?php if(!empty($get_twelve_qty['total_qty'])){ echo $get_twelve_qty['total_qty']; }else{ echo '0'; } ?></td>
										
						<?php 
						//alert 7 days
						$get_seven_qty= get_stock_quantity_seven_data('',$data_each['blood_component_id'],'','',$data_each['blood_group_id'],12) ;
									
									?>
										<td><?php if(!empty($get_seven_qty['total_qty'])){ echo $get_seven_qty['total_qty'];  }else { echo '0'; } ?></td>
                        <?php 
                            //Qc
                            $get_qc_qty= get_stock_quantity_tested_data('',$data_each['blood_component_id'],'','',$data_each['blood_group_id'],12) ;
                        ?>
						<td <?php if($get_qc_qty['total_qty']>0){ ?>style="background:red;" <?php } ?>><?php 
						//alert 12 days
						
									if($get_qc_qty['total_qty']>0){
									 if(!empty($get_qc_qty['total_qty'])){ echo $get_qc_qty['total_qty'];  }
									}else { echo '0';} ?></td>
						<td>
						    <?php  $get_stoc_qty_expred= get_stock_quantity_expired_by_group('',$data_each['blood_component_id'],'','',$data_each['blood_group_id']) ; 
						        if(!empty($get_stoc_qty_expred['total_qty'])){ echo $get_stoc_qty_expred['total_qty'];}else {  echo '0'; }
						    
						    ?></td>
						 <?php //expired 
						 $get_stoc_qty= get_stock_quantity_qc_failed_data('',$data_each['blood_component_id'],'','',$data_each['blood_group_id']) ;
									
									?>
										<td><?php if(!empty($get_stoc_qty['total_qty'])){ echo $get_stoc_qty['total_qty'];}else {  echo '0'; } ?></td>
										
						<?php $get_stoc_qty= get_stock_quantity_discarded_data('',$data_each['blood_component_id'],'','',$data_each['blood_group_id']) ;
									
									?>
										<td><?php if(!empty($get_stoc_qty['total_qty'])){ echo $get_stoc_qty['total_qty']; }else { echo '0'; } ?></td>
										
					</tr>

					

					
					

			
		</table>
<br>
					<p></p>
			<?php }

			}
		
			else if(!empty($form_data['component']) && empty($form_data['blood_grp_id']))
			{
			    //echo "sdsdsd"; die;
			    //echo "<pre>"; print_r($all_blood_group_list); die;
			   
			    //all the component of Selected blood group
			        
			        
			        //foreach($all_blood_group_list as $group_wise)
			        foreach($all_blood_group_list as $blood_li)
			        { 
			            //echo "<pre>";print_r($group_wise[0]); exit;
			            
			           $component_ids = $form_data['component'];
			           
			           ?>
			            
			            
                   <div style="margin-bottom:5px;">
        				<a href="#!" class="btn_quick_search"><?php echo $components_name;?></a>
        				<a href="#!" class="btn_border"><?php echo $blood_li->blood_group;?></a>
        			</div>
            
            			<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:14px;text-align:center;">
            			<tr>
            				<td width="120">Total QTY</td>
            				<td style="background:#E1EFD8;">In-Stock</td>
            				<td>Awaiting test results</td>
            				<td>Alert Expiry soon (12 days)</td>
            				<td style="background:red;">Alert (7 days)</td>
            				<td width="50">QC</td>
            				<td>Discarded <br> (expired)</td>
            				<td>Discarded <br> (QC failed)</td>
            				<td>Discarded <br> (Test failed)</td>
            			</tr>

			
					

					<tr>
						<?php 
						        $total_aw=0;
						        $bag_type_id='';
								$exist_ids='';
									$get_stoc_qty= get_stock_dashboard_grand_total_quantity($bag_type_id="",$component_ids,$exist_ids='','',$blood_li->id) ;
									if($get_stoc_qty['total_qty']>0){
									?>
										<?php if(!empty($get_stoc_qty['total_qty'])){ $total_aw =$total_aw+ $get_stoc_qty['total_qty']; } ?>
										<?php $i++;}
								
								?>
								
						      <td><?php echo $total_aw; ?></td>
										<?php
								$total_ex =0;		
							 
									
									$bag_type_id='';
									$exist_ids=''; //$donated_data['component_id'][0]
									$get_stoc_qty= get_stock_dashboard_quantity($bag_type_id="",$component_ids,$exist_ids='','',$blood_li->id) ;
									if($get_stoc_qty['total_qty']>0){
									 if(!empty($get_stoc_qty['total_qty'])){ $total_ex =$total_ex+$get_stoc_qty['total_qty']; }  $i++;}
									
								?>
								<td><?php echo $total_ex; ?></td>
								<?php
							
								//'',$data_each['blood_component_id'],'','',$data_each['blood_group_id']
								$get_untested_qty= get_stock_quantity_awaiting_data("",$component_ids,'','',$blood_li->id) ;
								?>
								<td><?php if(!empty($get_untested_qty['total_qty'])){ echo $get_untested_qty['total_qty']; }else{  echo '0'; } ?></td>
										
						<?php 
						//alert 12 days
						$get_twelve_qty= get_stock_quantity_twelve_data($bag_type_id="",$group_wise[0]['component_id'][0],$exist_ids='','',$blood_li->id,12) ;
									
									?>
										<td><?php if(!empty($get_twelve_qty['total_qty'])){ echo $get_twelve_qty['total_qty']; }else{ echo '0'; } ?></td>
										
						<?php 
						//alert 7 days
						$get_seven_qty= get_stock_quantity_seven_data($bag_type_id="",$component_ids,$exist_ids='','',$blood_li->id,12) ;
									
									?>
										<td><?php if(!empty($get_seven_qty['total_qty'])){ echo $get_seven_qty['total_qty'];  }else { echo '0'; } ?></td>
                        <?php 
                            //Qc
                            
                            $get_qc_qty= get_stock_quantity_tested_data($bag_type_id="",$component_ids,$exist_ids='','',$blood_li->id,12) ;
                        ?>
						<td <?php if($get_qc_qty['total_qty']>0){ ?>style="background:red;" <?php } ?>><?php 
						//alert 12 days
						
									if($get_qc_qty['total_qty']>0){
									 if(!empty($get_qc_qty['total_qty'])){ echo $get_qc_qty['total_qty'];  }
									}else { echo '0';} ?></td>
						<td>
						    <?php  $get_stoc_qty_expred= get_stock_quantity_expired_by_group($bag_type_id="",$component_ids,$exist_ids='','',$blood_grp_ids) ; 
						        if(!empty($get_stoc_qty_expred['total_qty'])){ echo $get_stoc_qty_expred['total_qty'];}else {  echo '0'; }
						    
						    ?></td>
						 <?php //expired 
						 $get_stoc_qty= get_stock_quantity_qc_failed_data($bag_type_id="",$component_ids,$exist_ids='','',$blood_li->id) ;
									
									?>
										<td><?php if(!empty($get_stoc_qty['total_qty'])){ echo $get_stoc_qty['total_qty'];}else {  echo '0'; } ?></td>
										
						<?php $get_stoc_qty= get_stock_quantity_discarded_data($bag_type_id="",$component_ids,$exist_ids='','',$blood_li->id) ;
									
									?>
										<td><?php if(!empty($get_stoc_qty['total_qty'])){ echo $get_stoc_qty['total_qty']; }else { echo '0'; } ?></td>
										
					</tr>

					

					
					
                
                			
                		</table>
                <br>
                <p></p>
			<?php }
			//end of all the blood group table
			    
			}
			else if(empty($form_data['component']) && !empty($form_data['blood_grp_id']))
			{
			    
			    
			        
			        
			        foreach($all_component_list as $bloodcomponent)
                    {
			           $blood_grp_ids = $form_data['blood_grp_id'];
			           
			           ?>
			            
			            
                   <div style="margin-bottom:5px;">
        				<a href="#!" class="btn_quick_search"><?php echo $bloodcomponent->component;?></a>
        				<a href="#!" class="btn_border"><?php echo $blood_group_name;?></a>
        			</div>
            
            			<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:14px;text-align:center;">
            			<tr>
            				<td width="120">Total QTY</td>
            				<td style="background:#E1EFD8;">In-Stock</td>
            				<td>Awaiting test results</td>
            				<td>Alert Expiry soon (12 days)</td>
            				<td style="background:red;">Alert (7 days)</td>
            				<td width="50">QC</td>
            				<td>Discarded <br> (expired)</td>
            				<td>Discarded <br> (QC failed)</td>
            				<td>Discarded <br> (Test failed)</td>
            			</tr>

			
					

					<tr>
						<?php 
						        $total_aw=0;
						        $bag_type_id='';
								$exist_ids='';
									$get_stoc_qty= get_stock_dashboard_grand_total_quantity($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids) ;
									if($get_stoc_qty['total_qty']>0){
									?>
										<?php if(!empty($get_stoc_qty['total_qty'])){ $total_aw =$total_aw+ $get_stoc_qty['total_qty']; } ?>
										<?php $i++;}
								
								?>
								
						      <td><?php echo $total_aw; ?></td>
										<?php
								$total_ex =0;		
							 
									
									$bag_type_id='';
									$exist_ids=''; //$donated_data['component_id'][0]
									$get_stoc_qty= get_stock_dashboard_quantity($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids) ;
									if($get_stoc_qty['total_qty']>0){
									 if(!empty($get_stoc_qty['total_qty'])){ $total_ex =$total_ex+$get_stoc_qty['total_qty']; }  $i++;}
									
								?>
								<td><?php echo $total_ex; ?></td>
								<?php
							
								//'',$data_each['blood_component_id'],'','',$data_each['blood_group_id']
								$get_untested_qty= get_stock_quantity_awaiting_data("",$bloodcomponent->id,'','',$blood_grp_ids) ;
								?>
								<td><?php if(!empty($get_untested_qty['total_qty'])){ echo $get_untested_qty['total_qty']; }else{  echo '0'; } ?></td>
										
						<?php 
						//alert 12 days
						$get_twelve_qty= get_stock_quantity_twelve_data($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids,12) ;
									
									?>
										<td><?php if(!empty($get_twelve_qty['total_qty'])){ echo $get_twelve_qty['total_qty']; }else{ echo '0'; } ?></td>
										
						<?php 
						//alert 7 days
						$get_seven_qty= get_stock_quantity_seven_data($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids,12) ;
									
									?>
										<td><?php if(!empty($get_seven_qty['total_qty'])){ echo $get_seven_qty['total_qty'];  }else { echo '0'; } ?></td>
                        <?php 
                            //Qc
                            
                            $get_qc_qty= get_stock_quantity_tested_data($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids,12) ;
                        ?>
						<td <?php if($get_qc_qty['total_qty']>0){ ?>style="background:red;" <?php } ?>><?php 
						//alert 12 days
						
									if($get_qc_qty['total_qty']>0){
									 if(!empty($get_qc_qty['total_qty'])){ echo $get_qc_qty['total_qty'];  }
									}else { echo '0';} ?></td>
						<td>
						    <?php  $get_stoc_qty_expred= get_stock_quantity_expired_by_group($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids) ; 
						        if(!empty($get_stoc_qty_expred['total_qty'])){ echo $get_stoc_qty_expred['total_qty'];}else {  echo '0'; }
						    
						    ?></td>
						 <?php //expired 
						 $get_stoc_qty= get_stock_quantity_qc_failed_data($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids) ;
									
									?>
										<td><?php if(!empty($get_stoc_qty['total_qty'])){ echo $get_stoc_qty['total_qty'];}else {  echo '0'; } ?></td>
										
						<?php $get_stoc_qty= get_stock_quantity_discarded_data($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids) ;
									
									?>
										<td><?php if(!empty($get_stoc_qty['total_qty'])){ echo $get_stoc_qty['total_qty']; }else { echo '0'; } ?></td>
										
					</tr>

					

					
					
                
                			
                		</table>
                <br>
                <p></p>
			<?php }
			//end of all the blood group table
			    
			
			}
			else if(!empty($form_data['component']) && !empty($form_data['blood_grp_id']))
			{
			    
			        $blood_grp_ids = $form_data['blood_grp_id'];
			        
			        $componentids = $form_data['component'];
			           
			           ?>
			            
			            
                   <div style="margin-bottom:5px;">
        				<a href="#!" class="btn_quick_search"><?php echo $components_name;?></a>
        				<a href="#!" class="btn_border"><?php echo $blood_group_name;?></a>
        			</div>
            
            			<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:14px;text-align:center;">
            			<tr>
            				<td width="120">Total QTY</td>
            				<td style="background:#E1EFD8;">In-Stock</td>
            				<td>Awaiting test results</td>
            				<td>Alert Expiry soon (12 days)</td>
            				<td style="background:red;">Alert (7 days)</td>
            				<td width="50">QC</td>
            				<td>Discarded <br> (expired)</td>
            				<td>Discarded <br> (QC failed)</td>
            				<td>Discarded <br> (Test failed)</td>
            			</tr>

			
					

					<tr>
						<?php 
						        $total_aw=0;
						        $bag_type_id='';
								$exist_ids='';
									$get_stoc_qty= get_stock_dashboard_grand_total_quantity($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids) ;
									if($get_stoc_qty['total_qty']>0){
									?>
										<?php if(!empty($get_stoc_qty['total_qty'])){ $total_aw =$total_aw+ $get_stoc_qty['total_qty']; } ?>
										<?php $i++;}
								
								?>
								
						      <td><?php echo $total_aw; ?></td>
										<?php
								$total_ex =0;		
							 
									
									$bag_type_id='';
									$exist_ids=''; //$donated_data['component_id'][0]
									$get_stoc_qty= get_stock_dashboard_quantity($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids) ;
									if($get_stoc_qty['total_qty']>0){
									 if(!empty($get_stoc_qty['total_qty'])){ $total_ex =$total_ex+$get_stoc_qty['total_qty']; }  $i++;}
									
								?>
								<td><?php echo $total_ex; ?></td>
								<?php
							
								//'',$data_each['blood_component_id'],'','',$data_each['blood_group_id']
								$get_untested_qty= get_stock_quantity_awaiting_data("",$componentids,'','',$blood_grp_ids) ;
								?>
								<td><?php if(!empty($get_untested_qty['total_qty'])){ echo $get_untested_qty['total_qty']; }else{  echo '0'; } ?></td>
										
						<?php 
						//alert 12 days
						$get_twelve_qty= get_stock_quantity_twelve_data($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids,12) ;
									
									?>
										<td><?php if(!empty($get_twelve_qty['total_qty'])){ echo $get_twelve_qty['total_qty']; }else{ echo '0'; } ?></td>
										
						<?php 
						//alert 7 days
						$get_seven_qty= get_stock_quantity_seven_data($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids,12) ;
									
									?>
										<td><?php if(!empty($get_seven_qty['total_qty'])){ echo $get_seven_qty['total_qty'];  }else { echo '0'; } ?></td>
                        <?php 
                            //Qc
                            
                            $get_qc_qty= get_stock_quantity_tested_data($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids,12) ;
                        ?>
						<td <?php if($get_qc_qty['total_qty']>0){ ?>style="background:red;" <?php } ?>><?php 
						//alert 12 days
						
									if($get_qc_qty['total_qty']>0){
									 if(!empty($get_qc_qty['total_qty'])){ echo $get_qc_qty['total_qty'];  }
									}else { echo '0';} ?></td>
						<td>
						    <?php  $get_stoc_qty_expred= get_stock_quantity_expired_by_group($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids) ; 
						        if(!empty($get_stoc_qty_expred['total_qty'])){ echo $get_stoc_qty_expred['total_qty'];}else {  echo '0'; }
						    
						    ?></td>
						 <?php //expired 
						 $get_stoc_qty= get_stock_quantity_qc_failed_data($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids) ;
									
									?>
										<td><?php if(!empty($get_stoc_qty['total_qty'])){ echo $get_stoc_qty['total_qty'];}else {  echo '0'; } ?></td>
										
						<?php $get_stoc_qty= get_stock_quantity_discarded_data($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids) ;
									
									?>
										<td><?php if(!empty($get_stoc_qty['total_qty'])){ echo $get_stoc_qty['total_qty']; }else { echo '0'; } ?></td>
										
					</tr>

					

					
					
                
                			
                		</table>
                <br>
                <p></p>
			<?php 
			//end of all the blood group table
			    
			
			}
			else 
			{
			    if(!empty($all_blood_group_list))
			    {
			  
			    //echo "<pre>";print_r($all_blood_group_list); exit;  
			    
			    
			$blood_comp=array();
             $rk=0;
			foreach($all_blood_group_list as $blood_li)
            {
                 
                $a=0;
                foreach($all_component_list as $bloodcomponent)
                {

                    
                    ?>
                    
                    <div style="margin-bottom:5px;">
				<a href="#!" class="btn_quick_search"><?php echo $bloodcomponent->component;?></a>
				<a href="#!" class="btn_border"><?php echo $blood_li->blood_group;?></a>
			</div>

			<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:14px;text-align:center;">
			<tr>
				<td width="120">Total QTY</td>
				<td style="background:#E1EFD8;">In-Stock</td>
				<td>Awaiting test results</td>
				<td>Alert Expiry soon (12 days)</td>
				<td style="background:red;">Alert (7 days)</td>
				<td width="50">QC</td>
				<td>Discarded <br> (expired)</td>
				<td>Discarded <br> (QC failed)</td>
				<td>Discarded <br> (Test failed)</td>
			</tr>

			
					

					<tr>
						<?php 
						        $total_aw=0;
						      
									$bag_type_id='';
									$exist_ids='';
									//$donated_data['component_id'][0]
									$get_stoc_qty= get_stock_dashboard_grand_total_quantity($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_li->id) ;
									if($get_stoc_qty['total_qty']>0){
									?>
										<?php if(!empty($get_stoc_qty['total_qty'])){ $total_aw =$total_aw+ $get_stoc_qty['total_qty']; } ?>
										<?php $i++;}
								
								?>
								
						      <td><?php echo $total_aw; ?></td>
										<?php
								$total_ex =0;		
							 
									
									$bag_type_id='';
									$exist_ids=''; //$donated_data['component_id'][0]
									$get_stoc_qty= get_stock_dashboard_quantity($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_li->id) ;
									if($get_stoc_qty['total_qty']>0){
									 if(!empty($get_stoc_qty['total_qty'])){ $total_ex =$total_ex+$get_stoc_qty['total_qty']; }  $i++;}
									
								?>
								<td><?php echo $total_ex; ?></td>
								<?php
							
								//echo $data_each['blood_component_id'].'commmmmm';
								$get_untested_qty= get_stock_quantity_awaiting_data('',$bloodcomponent->id,'','',$blood_li->id) ;
								?>
								<td><?php if(!empty($get_untested_qty['total_qty'])){ echo $get_untested_qty['total_qty']; }else{  echo '0'; } ?></td>
										
						<?php 
						//alert 12 days
						$get_twelve_qty= get_stock_quantity_twelve_data('',$bloodcomponent->id,'','',$blood_li->id,12) ;
									
									?>
										<td><?php if(!empty($get_twelve_qty['total_qty'])){ echo $get_twelve_qty['total_qty']; }else{ echo '0'; } ?></td>
										
						<?php 
						//alert 7 days
						$get_seven_qty= get_stock_quantity_seven_data('',$bloodcomponent->id,'','',$blood_li->id,12) ;
									
									?>
										<td><?php if(!empty($get_seven_qty['total_qty'])){ echo $get_seven_qty['total_qty'];  }else { echo '0'; } ?></td>
                        <?php 
                            //Qc
                            $get_qc_qty= get_stock_quantity_tested_data('',$bloodcomponent->id,'','',$blood_li->id) ;
                        ?>
						<td <?php if($get_qc_qty['total_qty']>0){ ?>style="background:red;" <?php } ?>><?php 
						//alert 12 days
						
									if($get_qc_qty['total_qty']>0){
									 if(!empty($get_qc_qty['total_qty'])){ echo $get_qc_qty['total_qty'];  }
									}else { echo '0';} ?></td>
						<td>
						    <?php  $get_stoc_qty_expred= get_stock_quantity_expired_by_group('',$bloodcomponent->id,'','',$blood_li->id) ; 
						        if(!empty($get_stoc_qty_expred['total_qty'])){ echo $get_stoc_qty_expred['total_qty'];}else {  echo '0'; }
						    
						    ?></td>
						 <?php //expired 
						 $get_stoc_qty= get_stock_quantity_qc_failed_data('',$bloodcomponent->id,'','',$blood_li->id) ;
									
									?>
										<td><?php if(!empty($get_stoc_qty['total_qty'])){ echo $get_stoc_qty['total_qty'];}else {  echo '0'; } ?></td>
										
						<?php $get_stoc_qty= get_stock_quantity_discarded_data('',$bloodcomponent->id,'','',$blood_li->id) ;
									
									?>
										<td><?php if(!empty($get_stoc_qty['total_qty'])){ echo $get_stoc_qty['total_qty']; }else { echo '0'; } ?></td>
										
					</tr>

					

					
					

			
		</table>
<br>
					<p></p>
                    
                    <?php 

                }
                $rk++;
            }
            
          

            
            //echo "<pre>"; print_r($blood_comp); exit;
			 
			}
			    
			    ?>


		
	
	
	
	
	


	<?php }  ?>
		
	</div>
			

			
				


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

				$('#new_stock_dashboard').delay(100).submit();
			}

			$("#new_stock_dashboard").on("submit", function(event) { 
				event.preventDefault(); 
				$('#overlay-loader').show();
				/*var branch_type= $('#branch_type').val();
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
				}*/
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
		/*	var today =new Date();
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
			})*/
 

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