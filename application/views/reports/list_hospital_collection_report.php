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
	$mode_check=0;
    if(!empty($collection_tab_setting))
    {
      foreach ($collection_tab_setting as $collection_setting) 
      {

        if(strcmp(strtolower($collection_setting->setting_name),'paid_amount')=='0')
        {
          $mode_check=1;
        }
      }

    }
	//print_r($self_opd_collection_list);die;
	if(!empty($self_opd_collection_list['over_all_collection']))
    { ?>
		<div style="float:left;width:100%;border:1px solid #111;">
			<div style="float:left; width:100%;font-size:13px;">				
			<div style="float:left;width:100%;border-bottom:1px solid #111;font-size:13px;display:flex;justify-content:space-around;">
				<!-- <div style="float:left;width:8%;font-weight:600;padding:4px;"><u>S.No.</u></div>
				<div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Section</u></div>
				<?php if(in_array('218',$users_data['permission']['section'])){ ?><div style="float:left;width:15%;font-weight:600;padding:4px;">Reciept No.</div> <?php } ?>
				<div style="float:left;width:12%;font-weight:600;padding:4px;"><u>Date</u></div>
				<div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Patient Name</u></div> 
				<div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Doctor Name</u></div>
				<div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Payment Mode</u></div>
				<div style="float:left;width:10%;font-weight:600;padding:4px;text-align:right;"><u>Amount</u></div> -->
				<?php 
           if(!empty($collection_tab_setting))
           {
              foreach ($collection_tab_setting as $collection_setting) 
              {
                  ?>
                  <div style="float:left;width:10%;font-weight:600;padding:4px;text-align:left;"><u><?php echo $collection_setting->setting_value; ?></u></div>
                  <?php
                  if(strcmp(strtolower($collection_setting->setting_name),'s_no')=='0')
				  {
				  	?>
				  	<div style="float:left;width:10%;font-weight:600;padding:4px;text-align:left;"><u>Section</u></div>
				  	<?php 
				  } 
              }
           }
           ?>
			</div>
				<div style="float:left;width:100%;font-weight:600;padding:4px;">
						<span style="border-bottom:1px solid #111;">Branch : Self</span>
				</div> 
			<?php
			$sort_col = array();
			foreach ($self_opd_collection_list['over_all_collection'] as $key=> $row) 
			{
				$sort_col[$key] = $row['reciept_suffix'];
			}
		/*	array_multisort($sort_col,SORT_DESC, $self_opd_collection_list['over_all_collection']);*/
		
		if($get['order_by']=='ASC')
			{
				array_multisort($sort_col,SORT_ASC, $self_opd_collection_list['over_all_collection']);
			}
			else
			{
				array_multisort($sort_col,SORT_DESC, $self_opd_collection_list['over_all_collection']);
			}
			
			if(!empty($self_opd_collection_list['over_all_collection']))
            {  
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_opd_collection_list['over_all_collection']);
                foreach($self_opd_collection_list['over_all_collection'] as $collection)
                { 
                 ?>
					<div style="float:left;width:100%;display:flex;justify-content:space-around;">
						<?php   
					    foreach ($collection_tab_setting as $tab_value) 
					    { 
					      if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $k; ?></div>

					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection['section_name']; ?></div>
					        <?php
					      }

					      if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if(!empty($collection['reciept_prefix']) || !empty($collection['reciept_suffix'])){ echo $collection['reciept_prefix'].$collection['reciept_suffix']; } ?></div>
					        <?php
					      }

					      if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection['patient_name']; ?></div>
					        <?php
					      }

					      if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection['patient_code']; ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo date('d-m-Y', strtotime($collection['created_date'])); ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection['doctor_name']; ?></div>
					        <?php
					      }

					      if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection['doctor_hospital_name']; ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection['mobile_no']; ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection['panel_type']; ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection['booking_code']; ?></div>
					        <?php
					      }

					      if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
					      {
					        ?>
					        
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection['total_amount']; ?></div>

					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection['discount_amount']; ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection['net_amount']; ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection['debit']; ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($collection['balance']=='1.00' || $collection['balance']=='0.00'){ echo '0.00'; }else{ echo number_format($collection['balance']-1,2); } ?></div>
					        <?php
					      }

					      if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection['mode_name'] ?></div>
					        <?php
					      }
					      


					    } 
					      ?>
						
					</div> 
                 <?php
                 $k++;	
                 $self_total = $self_total+$collection['debit'];
                } 
                ?>
                	<?php 
					if($mode_check==1)
					{
					$p_m_k=1;
                	foreach($self_opd_collection_list['over_all_collection_payment_mode'] as $payment_mode_overall){?>
                	<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:150px; font-weight: bold;<?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
							<span style="float:left;"><?php echo $payment_mode_overall['mode_name']; ?></span>
							<span style="float:right;"><?php echo number_format($payment_mode_overall['tot_debit'],2); ?></span>
						</div>
					</div> 
					<?php $p_m_k++; }?>

					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                }
            }
				?> 
			</div>
		<?php	
        }
		?>


	<?php 
	if(!empty($branch_collection_list['over_all_branch_data']))
    { ?>
	<div style="float:left;width:100%;border-top:1px solid #111;margin-bottom:10px;">

		<?php
		//print_r($branch_collection_list); exit;
        if(!empty($branch_collection_list['over_all_branch_data']))
        { 
          $branch_names = [];	
          foreach($branch_collection_list['over_all_branch_data'] as $names) 
          {
          	 $branch_names[] = $names['branch_name'];
          }
		  $branch_names = array_unique($branch_names);	 

        ?>
        <div style="float:left;width:100%;border-bottom:1px solid #111;font-size:13px;display:flex;justify-content:space-around;">
			<!-- <div style="float:left;width:8%;font-weight:600;padding:4px;"><u>Sr.No.</u></div>
			<div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Section</u></div>
			<?php if(in_array('218',$users_data['permission']['section'])){ ?><div style="float:left;width:15%;font-weight:600;padding:4px;">Reciept No.</div> <?php } ?>
			<div style="float:left;width:12%;font-weight:600;padding:4px;"><u>Date</u></div>
			<div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Branch Name</u></div>
			<div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Doctor Name</u></div>
			<div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Payment Mode</u></div>
			<div style="float:left;width:10%;font-weight:600;padding:4px;text-align:right;"><u>Amount</u></div> -->
			<?php 
           if(!empty($collection_tab_setting))
           {
              foreach ($collection_tab_setting as $collection_setting) 
              {
                  ?>
                  <div style="float:left;width:10%;font-weight:600;padding:4px;"><u><?php echo $collection_setting->setting_value; ?></u></div>
                  <?php
                  if(strcmp(strtolower($collection_setting->setting_name),'s_no')=='0')
				  {
				  	?>
				  	<div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Section</u></div>
				  	<?php 
				  } 
              }
           }
           ?>
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
		if(!empty($branch_collection_list['over_all_branch_data'])) 
		{
		    $i = 1; 	
			$branch_total = 0;	
			$count_branch = count($branch_collection_list['over_all_branch_data']);
			$n_bnc = '';

			$sort_col = array();
			foreach ($branch_collection_list['over_all_branch_data'] as $key=> $row) 
			{
				$sort_col[$key] = $row['reciept_suffix'];
			}
			array_multisort($sort_col,SORT_DESC, $branch_collection_list['over_all_branch_data']);

		    foreach($branch_collection_list['over_all_branch_data'] as $branchs)
		    { 
				if($names == $branchs['branch_name'])	
		     	{
		    ?>	  
				<div style="float:left; width: 100%;display:flex;justify-content:space-around;">
				<?php   
					    foreach ($collection_tab_setting as $tab_value) 
					    { 
					      if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $i; ?></div>

					        <div style="float:left;width:10%;padding:1px 4px;"><?php echo $branchs['section_name']; ?></div>
					        <?php
					      }

					      if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if(!empty($branchs['reciept_prefix']) || !empty($branchs['reciept_suffix'])){ echo $branchs['reciept_prefix'].$branchs['reciept_suffix']; } ?></div>
					        <?php
					      }

					      if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs['patient_name']; ?></div>
					        <?php
					      }

					      if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs['patient_code']; ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo date('d-m-Y', strtotime($branchs['created_date'])); ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs['doctor_name']; ?></div>
					        <?php
					      }

					      if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs['doctor_hospital_name']; ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs['mobile_no']; ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs['panel_type']; ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs['booking_code']; ?></div>
					        <?php
					      }

					      if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
					      {
					        ?>
					        
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs['total_amount']; ?></div>

					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs['discount_amount']; ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs['net_amount']; ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs['debit']; ?></div>
					        <?php
					      }
					      if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($branchs['balance']=='1.00' || $branchs['balance']=='0.00'){ echo '0.00'; }else{ echo number_format($branchs['balance']-1,2); } ?></div>
					        <?php
					      }

					      if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
					      {
					        ?>
					        <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs['mode_name'] ?></div>
					        <?php
					      }
					      


					    } 
					      ?>
				 
				</div>		
			<?php
			$i++;
			$branch_total = $branch_total+$branchs['debit'];
		     }
			}
			?>
			<?php 
			if($mode_check==1)
            {
            $p_m_k=1;
			foreach($branch_collection_list['over_all_branch_data_payment_mode'] as $paymode_data){ ?>

			<div style="float:left;width:100%;padding:4px;text-align:right;">
					<div style="float:right;width:150px; font-weight: bold;<?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
						<span style="float:left;"><?php echo $paymode_data['mode_name'];?></span>
						<span style="float:right;"><?php echo number_format($paymode_data['tot_debit'],2);?></span>
					</div>
				</div>
			<?php $p_m_k++; } ?>
				<div style="float:left;width:100%;padding:4px;text-align:right;">
					<div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
						<span style="float:left;">Total:</span>
						<span style="float:right;"><?php echo number_format($branch_total,2); ?></span>
					</div>
				</div>
			<?php 
			}
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