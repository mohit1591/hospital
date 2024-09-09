<?php  $users_data = $this->session->userdata('auth_users'); ?>
<!DOCTYPE html>
<html>
<head>
<title></title>
<style>
	*{margin:0;padding:0;box-sizing:border-box;-webkit-box-sizing:border-box;}
	page {
	  background: white;
	  display: block;
	  margin: 1em auto 0;
	  margin-bottom: 0.5cm;
	}
	page[size="A4"] {  
	                
			padding: 3em;
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
				<td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Hospital Collection Report</span></td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px;padding:1em;">
					<strong>From</strong>
					<span><?php echo $get['start_date']; ?></span>
					<strong>To</strong>
					<span><?php echo $get['end_date']; ?></span>
				</td>
				<td><input type="button" name="button_print" value="Print" id="print" onClick="return my_function();"/>
				</td>
			</tr>
			</table>
		<!-- Branch list start -->
	<?php 
	if(!empty($self_opd_collection_list))
    { ?>
		<div style="float:left;width:100%;border:1px solid #111;">
			<div style="float:left; width:100%;font-size:13px;">				
			<div style="float:left;width:100%;border-bottom:1px solid #111;font-size:13px;">
				<div style="float:left;width:8%;font-weight:600;padding:4px;"><u>S.No.</u></div>
				<div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Section</u></div>
				<?php if(in_array('218',$users_data['permission']['section'])){ ?><div style="float:left;width:15%;font-weight:600;padding:4px;">Reciept No.</div> <?php } ?>
				<div style="float:left;width:12%;font-weight:600;padding:4px;"><u>Date</u></div>
				<div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Patient Name</u></div> 
				<div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Doctor Name</u></div>
				<div style="float:left;width:15%;font-weight:600;padding:4px;text-align:right;"><u>Amount</u></div>
			</div>
				<div style="float:left;width:100%;font-weight:600;padding:4px;">
						<span style="border-bottom:1px solid #111;">Branch : Self</span>
				</div> 
			<?php
			$sort_col = array();
			foreach ($self_opd_collection_list as $key=> $row) 
			{
				$sort_col[$key] = $row['reciept_suffix'];
			}
			array_multisort($sort_col,SORT_DESC, $self_opd_collection_list);
			if(!empty($self_opd_collection_list))
            {  
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_opd_collection_list);
                foreach($self_opd_collection_list as $collection)
                { 
                 ?>
					<div style="float:left;width:100%;">
						<div style="float:left;width:8%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div> 
						<div style="float:left;width:15%;padding:1px 4px;"><?php echo $collection['section_name']; ?></div> 
						<?php if(in_array('218',$users_data['permission']['section'])){ ?> <div style="float:left;width:15%;padding:1px 4px;text-indent:15px;"><?php if(!empty($collection['reciept_prefix']) || !empty($collection['reciept_suffix'])){ echo $collection['reciept_prefix'].$collection['reciept_suffix']; } ?></div> <?php } ?>
						<div style="float:left;width:12%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($collection['created_date'])); ?> <span style="padding-left:5px;"></span></div>
						<div style="float:left;width:20%;padding:1px 4px;"><?php echo $collection['patient_name']; ?></div> 
						<div style="float:left;width:15%;padding:1px 4px;"><?php echo $collection['doctor_hospital_name']; ?></div>
						<div style="float:left;width:15%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $collection['debit']; ?></div>
					</div> 
                 <?php
                 $k++;	
                 $self_total = $self_total+$collection['debit'];
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                }
				?> 
			</div>
		<?php	
        }
		?>


	<?php 
	if(!empty($branch_collection_list))
    { ?>
	<div style="float:left;width:100%;border:1px solid #111;margin-bottom:10px;">

		<?php
		//print_r($branch_collection_list); exit;
        if(!empty($branch_collection_list))
        { 
          $branch_names = [];	
          foreach($branch_collection_list as $names) 
          {
          	 $branch_names[] = $names['branch_name'];
          }
		  $branch_names = array_unique($branch_names);	 

        ?>
        <div style="float:left;width:100%;border-bottom:1px solid #111;font-size:13px;">
			<div style="float:left;width:8%;font-weight:600;padding:4px;"><u>Sr.No.</u></div>
			<div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Section</u></div>
			<?php if(in_array('218',$users_data['permission']['section'])){ ?><div style="float:left;width:15%;font-weight:600;padding:4px;">Reciept No.</div> <?php } ?>
			<div style="float:left;width:12%;font-weight:600;padding:4px;"><u>Date</u></div>
			<div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Branch Name</u></div>
			<div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Doctor Name</u></div>
			<div style="float:left;width:15%;font-weight:600;padding:4px;text-align:right;"><u>Amount</u></div>
		</div> 
		<div style="float:left;width:100%;font-size:13px;">	
			
	<?php
	//echo "<pre>";print_r($branch_names); exit;
	if(!empty($branch_names))
	{
	foreach($branch_names as $names)
	{
	    ?>
	    <div style="float:left;width:100%;font-weight:600;padding:4px;">
		    <span style="border-bottom:1px solid #111;">Branch : <?php echo $names; ?></span>
		</div>	
		<?php 
		if(!empty($branch_collection_list)) 
		{
		    $i = 1; 	
			$branch_total = 0;	
			$count_branch = count($branch_collection_list);
			$n_bnc = '';

			$sort_col = array();
			foreach ($branch_collection_list as $key=> $row) 
			{
				$sort_col[$key] = $row['reciept_suffix'];
			}
			array_multisort($sort_col,SORT_DESC, $branch_collection_list);

		    foreach($branch_collection_list as $branchs)
		    { 
				if($names == $branchs['branch_name'])	
		     	{
		    ?>	  
				<div style="float:left; width: 100%">
				<div style="float:left;width:8%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>
				<div style="float:left;width:15%;padding:1px 4px;"><?php echo $collection['section_name']; ?></div> 
					<?php if(in_array('218',$users_data['permission']['section'])){ ?> <div style="float:left;width:15%;padding:1px 4px;text-indent:15px;"><?php if(!empty($branchs['reciept_prefix']) || !empty($branchs['reciept_suffix']))
		            {
		             echo $branchs['reciept_prefix'].$branchs['reciept_suffix'];
		            } 
		            ?></div> 
		         <?php } ?>
				<div style="float:left;width:12%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($branchs['created_date'])); ?></div>
				<div style="float:left;width:20%;padding:1px 4px;"><?php echo $branchs['patient_name']; ?></div>
				<div style="float:left;width:15%;padding:1px 4px;"><?php echo $branchs['doctor_hospital_name']; ?></div>

				<div style="float:left;width:15%;padding:1px 4px;text-align:right;<?php if($count_branch==$i){ echo 'border-bottom:1px solid #111'; } ?>;"><?php echo $branchs['debit']; ?></div>
				</div>		
			<?php
			$i++;
			$branch_total = $branch_total+$branchs['debit'];
		     }
			}
			?>

				<div style="float:left;width:100%;padding:4px;text-align:right;">
					<div style="float:right;width:20%; font-weight: bold;">
						<span style="float:left;">Total:</span>
						<span style="float:right;"><?php echo number_format($branch_total,2); ?></span>
					</div>
				</div>
			<?php 
			} 

	}
}
		
?>		 
		</div>
<?php	 
}
?>
</div>
<?php } ?>
			
</page>

</body>
</html>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
 <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<script>
function my_function()
{
 $("#print").hide();
  window.print();
}
</script>