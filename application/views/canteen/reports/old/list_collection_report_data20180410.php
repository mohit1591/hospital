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
				<td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Collection Report</span></td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px;padding:1em;">
					<strong>From</strong>
					<span><?php echo $get['start_date']; ?></span>
					<strong>To</strong>
					<span><?php echo $get['end_date']; ?></span>
				</td>
				 <td><input type="button" name="button_print" value="Print" id="print" onClick="return my_function();"/></td>
			</tr>
		</table>
		<!-- Branch list start -->



<?php   if(!empty($branch_collection_list) || !empty($branch_vaccination_collection_list) || !empty($branch_medicine_collection_list) || !empty($branch_billing_collection_list) || !empty($pathology_branch_collection_list) || !empty($branch_ipd_collection_list))
        { ?>
	<div style="float:left;width:100%;border:1px solid #111;margin-bottom:10px;">

		<?php
		$pay_mode = array('0'=>'','1'=>'Cash', '2'=>'Cheque', '3'=>'Card', '4'=>'NEFT');
        if(!empty($branch_collection_list) || !empty($branch_vaccination_collection_list) || !empty($branch_medicine_collection_list) || !empty($branch_billing_collection_list) || !empty($pathology_branch_collection_list) || !empty($branch_ipd_collection_list))
        { 
          $branch_names = [];	
          foreach($branch_collection_list as $names)  //opd
          {
          	 $branch_names[] = $names->branch_name;
          }

           foreach($branch_vaccination_collection_list as $names) //vaccine
           {
           	 $branch_names[] = $names->branch_name;
           }
           foreach($branch_medicine_collection_list as $names) //medicine
           {
           	 $branch_names[] = $names->branch_name;
           }

           foreach($branch_billing_collection_list as $names) //billing
           {
           	 $branch_names[] = $names->branch_name;
           }

           foreach($pathology_branch_collection_list as $names) //pathology
           {
           	 $branch_names[] = $names->branch_name;
           }

           foreach($branch_ipd_collection_list as $names) //IPD
           {
           	 $branch_names[] = $names->branch_name;
           }
           
		$branch_names = array_unique($branch_names);	 

        ?>
        <div style="float:left;width:100%;border-bottom:1px solid #111;font-size:13px;">
			<div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Sr.No.</u></div>
			<?php if(in_array('218',$users_data['permission']['section'])){ ?><div style="float:left;width:10%;font-weight:600;padding:4px;">Reciept No.</div> <?php } ?>
			<div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Date</u></div>
			<div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Branch Name</u></div>
			<div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Doctor Name</u></div>
			<div style="float:left;width:20%;font-weight:600;padding:4px;text-align:right;"><u>Amount</u></div>
		</div> 
		<div style="float:left;width:100%;font-size:13px;">	
			
	<?php
	if(!empty($branch_names))
	{
	foreach($branch_names as $names)
	{
    ?>
    <div style="float:left;width:100%;font-weight:600;padding:4px;">
	    <span style="border-bottom:1px solid #111;">Branch : <?php echo $names; ?></span>
	</div>	
	<?php if(!empty($branch_collection_list)) { ?>
	<div style="float:left;width:100%;font-weight:600;padding:4px;">
		   <span style="border-bottom:1px solid #111;">OPD</span>
	</div>	
    <?php 

    $i = 1; 	
	$branch_total = 0;	
	$count_branch = count($branch_collection_list);
	$n_bnc = '';
    foreach($branch_collection_list as $branchs)
    { 

    

     if($names == $branchs->branch_name)	
     {
    ?>	  
		<div style="float:left; width: 100%">
		<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>
		<?php if(in_array('218',$users_data['permission']['section'])){ ?> <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php if(!empty($branchs->reciept_prefix) || !empty($branchs->reciept_suffix))
            {
             echo strtoupper($branchs->reciept_prefix).$branchs->reciept_suffix;
            } ?></div> <?php } ?>
		<div style="float:left;width:20%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($branchs->created_date)); ?><span style="padding-left:5px;"><?php //echo date('h:i A', strtotime($branchs->created_date)); ?></span></div>
		<div style="float:left;width:20%;padding:1px 4px;"><?php echo $branchs->patient_name; ?></div>
		<div style="float:left;width:20%;padding:1px 4px;"><?php echo $branchs->doctor_hospital_name;  //$pay_mode[$branchs->pay_mode]; ?></div>

		<div style="float:left;width:20%;padding:1px 4px;text-align:right;<?php if($count_branch==$i){ echo 'border-bottom:1px solid #111'; } ?>;"><?php echo $branchs->debit; ?></div>
		</div>		
	<?php
	$i++;
	$branch_total = $branch_total+$branchs->debit;
     }
	}
	?>

		<div style="float:left;width:100%;padding:4px;text-align:right;">
			<div style="float:right;width:20%; font-weight: bold;">
				<span style="float:left;">Total:</span>
				<span style="float:right;"><?php echo number_format($branch_total,2); ?></span>
			</div>
		</div>

		<?php } 


		if(!empty($branch_billing_collection_list)){ ?>
		<div style="float:left;width:100%;font-weight:600;padding:4px;">
		   <span style="border-bottom:1px solid #111;">Billing</span>
	</div>
	<?php 
	$x = 1; 	
	$branch_bill_total = 0;	
	$count_bill_branch = count($branch_billing_collection_list);
	$n_bnc = '';
    foreach($branch_billing_collection_list as $bill_branchs)
    {   
     if($names == $bill_branchs->branch_name)	
     {
    ?>	  
	<div style="float:left; width: 100%;">
	 <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>
	 <?php if(in_array('218',$users_data['permission']['section'])){ ?> <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php if(!empty($bill_branchs->reciept_prefix) || !empty($bill_branchs->reciept_suffix))
            {
             echo $bill_branchs->reciept_prefix.$bill_branchs->reciept_suffix;
            } ?></div> <?php } ?>

		<div style="float:left;width:20%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($bill_branchs->created_date)); ?><span style="padding-left:5px;"><?php //echo date('h:i A', strtotime($bill_branchs->created_date)); ?></span></div>
		<div style="float:left;width:20%;padding:1px 4px;"><?php echo $bill_branchs->patient_name; ?></div>
		<div style="float:left;width:20%;padding:1px 4px;"><?php echo $bill_branchs->doctor_hospital_name;  //$pay_mode[$branchs->pay_mode]; ?></div>
		
		<div style="float:left;width:20%;padding:1px 4px;text-align:right;<?php if($count_bill_branch==$x){ echo 'border-bottom:1px solid #111'; } ?>;"><?php echo $bill_branchs->debit; ?></div>
	</div>		
	<?php
	$i++;
	$branch_bill_total = $branch_bill_total+$bill_branchs->debit;
     }
	}
	?>

		<div style="float:left;width:100%;padding:4px;text-align:right;">
			<div style="float:right;width:20%; font-weight: bold;">
				<span style="float:left;">Total:</span>
				<span style="float:right;"><?php echo number_format($branch_bill_total,2); ?></span>
			</div>
		</div>
<?php 

}
//ipd start
if(!empty($branch_ipd_collection_list)){ ?>
		<div style="float:left;width:100%;font-weight:600;padding:4px;">
		   <span style="border-bottom:1px solid #111;">IPD</span>
	</div>
	<?php 
	$x = 1; 	
	$branch_ipd_total = 0;	
	$count_ipd_branch = count($branch_ipd_collection_list);
	$n_bnc = '';
    foreach($branch_ipd_collection_list as $ipd_branchs)
    {   
     if($names == $ipd_branchs->branch_name)	
     {
    ?>	  
	<div style="float:left; width: 100%;">
	 <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>

	 <?php if(in_array('218',$users_data['permission']['section'])){ ?> <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php if(!empty($ipd_branchs->reciept_prefix) || !empty($ipd_branchs->reciept_suffix)){ echo $ipd_branchs->reciept_prefix.$ipd_branchs->reciept_suffix; } ?></div> <?php } ?>

		<div style="float:left;width:20%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($ipd_branchs->created_date)); ?><span style="padding-left:5px;"><?php //echo date('h:i A', strtotime($ipd_branchs->created_date)); ?></span></div>
		<div style="float:left;width:20%;padding:1px 4px;"><?php echo $ipd_branchs->patient_name; ?></div>
		<div style="float:left;width:20%;padding:1px 4px;"><?php echo $ipd_branchs->doctor_hospital_name;  //$pay_mode[$branchs->pay_mode]; ?></div>
		
		<div style="float:left;width:20%;padding:1px 4px;text-align:right;<?php if($count_ipd_branch==$x){ echo 'border-bottom:1px solid #111'; } ?>;"><?php echo $ipd_branchs->debit; ?></div>
	</div>		
	<?php
	$i++;
	$branch_ipd_total = $branch_ipd_total+$ipd_branchs->debit;
     }
	}
	?>

		<div style="float:left;width:100%;padding:4px;text-align:right;">
			<div style="float:right;width:20%; font-weight: bold;">
				<span style="float:left;">Total:</span>
				<span style="float:right;"><?php echo number_format($branch_ipd_total,2); ?></span>
			</div>
		</div>
<?php 

}
//ipd end
 if(!empty($branch_medicine_collection_list)){ ?>
		<div style="float:left;width:100%;font-weight:600;padding:4px;">
		   <span style="border-bottom:1px solid #111;">Medicine Sale</span>
	</div>
	<?php 
	$c = 1; 	
	$branch_medi_total = 0;	
	$count_medi_branch = count($branch_medicine_collection_list);
	$n_bnc = '';
	$i=1;
    foreach($branch_medicine_collection_list as $medi_branchs)
    {   
     if($names == $medi_branchs->branch_name)	
     {
    ?>	  
	<div style="float:left; width: 100%;">
	 <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>
	 <?php if(in_array('218',$users_data['permission']['section'])){ ?> <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php if(!empty($medi_branchs->reciept_prefix) || !empty($medi_branchs->reciept_suffix))
            {
             echo $medi_branchs->reciept_prefix.$medi_branchs->reciept_suffix;
            } ?></div> <?php } ?>

		<div style="float:left;width:20%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($medi_branchs->created_date)); ?><span style="padding-left:5px;"><?php //echo date('h:i A', strtotime($medi_branchs->created_date)); ?></span></div>
		<div style="float:left;width:20%;padding:1px 4px;"><?php echo $medi_branchs->patient_name; ?></div>
		<div style="float:left;width:20%;padding:1px 4px;"><?php echo $medi_branchs->doctor_hospital_name;  //$pay_mode[$branchs->pay_mode]; ?></div>
		
		<div style="float:left;width:20%;padding:1px 4px;text-align:right;<?php if($count_medi_branch==$c){ echo 'border-bottom:1px solid #111'; } ?>;"><?php echo $medi_branchs->debit; ?></div>
	</div>		
	<?php
	$i++;
	$branch_medi_total = $branch_medi_total+$medi_branchs->debit;
     }
	}
	?>

		<div style="float:left;width:100%;padding:4px;text-align:right;">
			<div style="float:right;width:20%; font-weight: bold;">
				<span style="float:left;">Total:</span>
				<span style="float:right;"><?php echo number_format($branch_medi_total,2); ?></span>
			</div>
		</div>
<?php } ?>


<?php 
//print_r($branch_vaccination_collection_list); exit;
if(!empty($branch_vaccination_collection_list)){ ?>
		<div style="float:left;width:100%;font-weight:600;padding:4px;">
		   <span style="border-bottom:1px solid #111;">Vaccination</span>
	</div>
	<?php 
	$i = 1; 
	$c=1;	
	$branch_vaccination_total = 0;	
	$count_vaccination_branch = count($branch_vaccination_collection_list);
	$n_bnc = '';
    foreach($branch_vaccination_collection_list as $medi_branchs)
    {   
     if($names == $medi_branchs->branch_name)	
     {
    ?>	  
	<div style="float:left; width: 100%;">
	 <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>

	 <?php if(in_array('218',$users_data['permission']['section'])){ ?> <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php if(!empty($branchs->reciept_prefix) || !empty($branchs->reciept_suffix))
            {
             echo strtoupper($branchs->reciept_prefix).$branchs->reciept_suffix;
            } ?></div> <?php } ?>

		<div style="float:left;width:20%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($medi_branchs->created_date)); ?><span style="padding-left:5px;"><?php //echo date('h:i A', strtotime($medi_branchs->created_date)); ?></span></div>
		<div style="float:left;width:20%;padding:1px 4px;"><?php echo $medi_branchs->patient_name; ?></div>
		<div style="float:left;width:20%;padding:1px 4px;"><?php echo $medi_branchs->doctor_hospital_name;  //$pay_mode[$branchs->pay_mode]; ?></div>
		
		<div style="float:left;width:20%;padding:1px 4px;text-align:right;<?php if($count_vaccination_branch==$c){ echo 'border-bottom:1px solid #111'; } ?>;"><?php echo $medi_branchs->debit; ?></div>
	</div>		
	<?php
	$i++;
	$branch_vaccination_total = $branch_vaccination_total+$medi_branchs->debit;
     }
	}
	?>

		<div style="float:left;width:100%;padding:4px;text-align:right;">
			<div style="float:right;width:20%; font-weight: bold;">
				<span style="float:left;">Total:</span>
				<span style="float:right;"><?php echo number_format($branch_vaccination_total,2); ?></span>
			</div>
		</div>
<?php } ?>




<!--medicine return report -->

<?php if(!empty($branch_medicine_return_collection_list)){ ?>
		<div style="float:left;width:100%;font-weight:600;padding:4px;">
		   <span style="border-bottom:1px solid #111;">Medicine Purchase Return </span>
	</div>
	<?php 
	$c = 1; 	
	$branch_medi_total = 0;	
	$count_medi_branch = count($branch_medicine_return_collection_list);
	$n_bnc = '';
    foreach($branch_medicine_return_collection_list as $medi_branchs)
    {   
     if($names == $medi_branchs->branch_name)	
     {
    ?>	  
	<div style="float:left; width: 100%;">
	 <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>
	 <?php if(in_array('218',$users_data['permission']['section'])){ ?> <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php if(!empty($medi_branchs->reciept_prefix) || !empty($medi_branchs->reciept_suffix))
            {
             echo $medi_branchs->reciept_prefix.$medi_branchs->reciept_suffix;
            } ?></div> <?php } ?>
		<div style="float:left;width:20%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($medi_branchs->created_date)); ?><span style="padding-left:5px;"><?php //echo date('h:i A', strtotime($medi_branchs->created_date)); ?></span></div>
		<div style="float:left;width:20%;padding:1px 4px;"><?php echo $medi_branchs->patient_name; ?></div>
		<div style="float:left;width:20%;padding:1px 4px;"></div>
		
		<div style="float:left;width:20%;padding:1px 4px;text-align:right;<?php if($count_medi_branch==$c){ echo 'border-bottom:1px solid #111'; } ?>;"><?php echo $medi_branchs->debit; ?></div>
	</div>		
	<?php
	$i++;
	$branch_medi_total = $branch_medi_total+$medi_branchs->debit;
     }
	}
	?>

		<div style="float:left;width:100%;padding:4px;text-align:right;">
			<div style="float:right;width:20%; font-weight: bold;">
				<span style="float:left;">Total:</span>
				<span style="float:right;"><?php echo number_format($branch_medi_total,2); ?></span>
			</div>
		</div>
<?php } ?>

<!--medicine return report -->
<!-- Pathology collection report -->
<?php
				
        if(!empty($pathology_branch_collection_list))
        {
        	
        ?>
        <div style="float:left;width:100%;font-weight:600;padding:4px;">
		   <span style="border-bottom:1px solid #111;">Pathology </span>
		</div>	
        <?php 

        $i = 1; 	
		$branch_total = 0;	
		$count_branch = count($pathology_branch_collection_list);
		$n_bnc = '';
        foreach($pathology_branch_collection_list as $branchs)
        {   
         if($names == $branchs->branch_name)	
         {
        ?>
		<div style="float:left;width:100%;">
			<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>
			<?php if(in_array('218',$users_data['permission']['section'])){ ?> <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php if(!empty($branchs->reciept_prefix) || !empty($branchs->reciept_suffix))
            {
             echo strtoupper($branchs->reciept_prefix).$branchs->reciept_suffix;
            } ?></div> <?php } ?>
			<div style="float:left;width:15%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($branchs->created_date)); ?> </div>
			<div style="float:left;width:20%;padding:1px 4px;"><?php echo $branchs->patient_name; ?></div> 
			<div style="float:left;width:20%;padding:1px 4px;"><?php echo $branchs->doctor_hospital_name; //$pay_mode[$collection->pay_mode]; ?></div>
			<div style="float:right;width:20%;padding:1px 4px;text-align:right; <?php if($count_branch==$i){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $branchs->debit; ?></div>
		</div>	   		
		<?php
		$i++;
		$branch_total = $branch_total+$branchs->debit;
	     }
		}
		?>

			<div style="float:left;width:100%;padding:4px;text-align:right;">
				<div style="float:right;width:20%; font-weight: bold;">
					<span style="float:left;">Total:</span>
					<span style="float:right;"><?php echo number_format($branch_total,2); ?></span>
				</div>
			</div><?php
			
        }
		?>
<!-- Pathology collection end -->



		<?php
		}
	}
		
?>		 
		</div>
        <?php	 
        }
		?>
		
		<!-- Branch list end -->
        <!-- Doctor list start -->
        <?php
        
        ?>

		<!-- Doctor list end -->

		</div>
		<?php }
			if(!empty($self_opd_collection_list) || !empty($self_billing_collection_list) || !empty($self_medicine_collection_list) || !empty($self_ipd_collection_list) || !empty($self_medicine_return_collection_list) || !empty($pathology_self_collection_list))
                {

		 ?>
			<div style="float:left;width:100%;border:1px solid #111;">


                <div style="float:left; width:100%;font-size:13px;">				
				
					<div style="float:left;width:100%;border-bottom:1px solid #111;font-size:13px;">
						<div style="float:left;width:10%;font-weight:600;padding:4px;"><u>S.No.</u></div>

						<?php if(in_array('218',$users_data['permission']['section'])){ ?><div style="float:left;width:10%;font-weight:600;padding:4px;">Reciept No.</div> <?php } ?>

						<div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Date</u></div>
						<div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Patient Name</u></div> 
						<!-- <div style="float:left;width:25%;font-weight:600;padding:4px;"><u>Pay mode</u></div> -->
						<div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Doctor Name</u></div>
						<div style="float:left;width:20%;font-weight:600;padding:4px;text-align:right;"><u>Amount</u></div>
					</div>
					 <div style="float:left;width:100%;font-weight:600;padding:4px;">
						<span style="border-bottom:1px solid #111;">Branch : Self</span>
					</div> 
			 

				<?php
				if(!empty($self_opd_collection_list))
                {
                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
						<span style="border-bottom:1px solid #111;">OPD</span>
					</div>
                	<?php 
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_opd_collection_list);
                //echo "<pre>";print_r($self_collection_list);
                foreach($self_opd_collection_list as $collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:8%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div> 
							<?php if(in_array('218',$users_data['permission']['section'])){ ?> <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ echo $collection->reciept_prefix.$collection->reciept_suffix; } ?></div> <?php } ?>
							<div style="float:left;width:22%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($collection->created_date)); ?> <span style="padding-left:5px;"><?php //echo date('h:i A', strtotime($collection->created_date)); ?></span></div>
							<div style="float:left;width:20%;padding:1px 4px;"><?php echo $collection->patient_name; ?></div> 
							<div style="float:left;width:20%;padding:1px 4px;"><?php echo $collection->doctor_hospital_name; //$pay_mode[$collection->pay_mode]; ?></div>
							<div style="float:left;width:20%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $collection->debit; ?></div>
						</div> 
                 <?php
                 $k++;	
                 $self_total = $self_total+$collection->debit;
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
				<!-- ipd collection -->

				<?php
				//echo "<pre>";print_r($self_ipd_collection_list);
				if(!empty($self_ipd_collection_list))
                {
                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
						<span style="border-bottom:1px solid #111;">IPD</span>
					</div>
                	<?php 
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_ipd_collection_list);
                
                foreach($self_ipd_collection_list as $collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:8%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div>

							<?php if(in_array('218',$users_data['permission']['section'])){ ?> <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ echo $collection->reciept_prefix.$collection->reciept_suffix; } ?></div> <?php } ?>
							

							<div style="float:left;width:22%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($collection->created_date)); ?> <span style="padding-left:5px;"><?php //echo date('h:i A', strtotime($collection->created_date)); ?></span></div>
							<div style="float:left;width:20%;padding:1px 4px;"><?php echo $collection->patient_name; ?></div> 
							<div style="float:left;width:20%;padding:1px 4px;"><?php echo $collection->doctor_hospital_name; //$pay_mode[$collection->pay_mode]; ?></div>
							<div style="float:left;width:20%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $collection->debit; ?></div>
						</div> 
                 <?php
                 $k++;	
                 $self_total = $self_total+$collection->debit;
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

				<!-- ipd end -->

				<!--medicice return report self part -->

						<?php
						//print_r($self_medicine_return_collection_list);
						if(!empty($self_medicine_return_collection_list))
						{
							?>
							<div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
								<span style="border-bottom:1px solid #111;">Medicine Return</span>
							</div>
							<?php 
						$k = 1 ;
						$self_total = 0;
						$self_counter = count($self_medicine_return_collection_list);
						//echo "<pre>";print_r($self_collection_list);
						foreach($self_medicine_return_collection_list as $collection)
						{ 
						 ?>
								<div style="float:left;width:100%;">
									<div style="float:left;width:8%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div>
									<div style="float:left;width:22%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($collection->created_date)); ?> <span style="padding-left:5px;"><?php //echo date('h:i A', strtotime($collection->created_date)); ?></span></div>
									<div style="float:left;width:25%;padding:1px 4px;"></div> 
									<div style="float:left;width:25%;padding:1px 4px;"></div>
									<div style="float:left;width:20%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $collection->debit; ?></div>
								</div> 
						 <?php
						 $k++;	
						 $self_total = $self_total+$collection->debit;
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
				<!--medicice return report self part -->





				<?php
                if(!empty($self_billing_collection_list))
                {

                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;">
						<span style="border-bottom:1px solid #111;">Billing</span>
					</div>
                	<?php
                
                $l = 1 ;
                $self_billing_total = 0;
                $self_billing_counter = count($self_billing_collection_list);
                //echo "<pre>";print_r($self_collection_list);
                foreach($self_billing_collection_list as $billing_collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:8%;padding:1px 4px;text-indent:15px;"><?php echo $l; ?></div>

							<?php if(in_array('218',$users_data['permission']['section'])){ ?> <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php if(!empty($billing_collection->reciept_prefix) || !empty($billing_collection->reciept_suffix)){ echo $billing_collection->reciept_prefix.$billing_collection->reciept_suffix; } ?></div> <?php } ?>


							<div style="float:left;width:22%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($billing_collection->created_date)); ?> <span style="padding-left:5px;"><?php //echo date('h:i A', strtotime($billing_collection->created_date)); ?></span></div>
							<div style="float:left;width:20%;padding:1px 4px;"><?php echo $billing_collection->patient_name; ?></div> 
							<div style="float:left;width:20%;padding:1px 4px;"><?php echo $billing_collection->doctor_hospital_name; //$pay_mode[$collection->pay_mode]; ?></div>
							<div style="float:left;width:20%;padding:1px 4px;text-align:right; <?php if($self_billing_counter==$l){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $billing_collection->debit; ?></div>
						</div> 
                 <?php
                 $l++;	
                 $self_billing_total = $self_billing_total+$billing_collection->debit;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_billing_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                }
				?> 

				<?php
                if(!empty($self_medicine_collection_list))
                { ?>
            	<div style="float:left;width:100%;font-weight:600;padding:4px;">
					<span style="border-bottom:1px solid #111;">Medicine Sale</span>
				</div>
            <?php
                $m = 1 ;
                $self_med_total = 0;
                $self_med_counter = count($self_medicine_collection_list);
                //echo "<pre>";print_r($self_collection_list);
                foreach($self_medicine_collection_list as $med_collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $m; ?></div>
							<?php if(in_array('218',$users_data['permission']['section'])){ ?> <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php if(!empty($med_collection->reciept_prefix) || !empty($med_collection->reciept_suffix)){ echo $med_collection->reciept_prefix.$med_collection->reciept_suffix; } ?></div> <?php } ?>

							<div style="float:left;width:20%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($med_collection->created_date)); ?> <span style="padding-left:5px;"><?php //echo date('h:i A', strtotime($med_collection->created_date)); ?></span></div>
							<div style="float:left;width:20%;padding:1px 4px;"><?php echo $med_collection->patient_name; ?></div> 
							<div style="float:left;width:20%;padding:1px 4px;"><?php echo $med_collection->doctor_hospital_name; //$pay_mode[$collection->pay_mode]; ?></div>
							<div style="float:left;width:20%;padding:1px 4px;text-align:right; <?php if($self_med_counter==$m){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $med_collection->debit; ?></div>
						</div> 
                 <?php
                 $m++;	
                 $self_med_total = $self_med_total+$med_collection->debit;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_med_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                }
				?>



				<?php
                if(!empty($pathology_self_collection_list))
                { 
                $data_empty = 1;
                ?>
                
					<div style="float:left;width:100%;font-weight:600;padding:4px;">
					<span style="border-bottom:1px solid #111;">Pathology</span>
				</div>
                <?php
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($pathology_self_collection_list);
                foreach($pathology_self_collection_list as $collection)
                {
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div>
							<?php if(in_array('218',$users_data['permission']['section'])){ ?> <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ echo $collection->reciept_prefix.$collection->reciept_suffix; } ?></div> <?php } ?>

							<div style="float:left;width:15%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($collection->created_date)); ?> </div>
							<div style="float:left;width:20%;padding:1px 4px;"><?php echo $collection->patient_name; ?></div> 
							<div style="float:left;width:20%;padding:1px 4px;"><?php echo $collection->doctor_hospital_name; //$pay_mode[$collection->pay_mode]; ?></div>
							<div style="float:right;width:20%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $collection->debit; ?></div>
						</div> 
                 <?php
                 $k++;	
                 $self_total = $self_total+$collection->debit;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_total,2); ?></span>
						</div>
					</div> 
                
			<?php } ?>


			<?php 
                if(!empty($self_vaccination_collection_list))
                { 
                $data_empty = 1;
                ?>
                
					<div style="float:left;width:100%;font-weight:600;padding:4px;">
					<span style="border-bottom:1px solid #111;">Vaccination</span>
				</div>
                <?php
                $k = 1 ;
                $self_vaccine_total = 0;
                $self_counter = count($self_vaccination_collection_list);
                foreach($self_vaccination_collection_list as $vaccination_collection)
                {
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div>
							<?php if(in_array('218',$users_data['permission']['section'])){ ?> <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php if(!empty($vaccination_collection->reciept_prefix) || !empty($vaccination_collection->reciept_suffix)){ echo $vaccination_collection->reciept_prefix.$vaccination_collection->reciept_suffix; } ?></div> <?php } ?>

							<div style="float:left;width:15%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($vaccination_collection->created_date)); ?> </div>
							<div style="float:left;width:20%;padding:1px 4px;"><?php echo $vaccination_collection->patient_name; ?></div> 
							<div style="float:left;width:20%;padding:1px 4px;"><?php echo $vaccination_collection->doctor_hospital_name; //$pay_mode[$collection->pay_mode]; ?></div>
							<div style="float:right;width:20%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $vaccination_collection->debit; ?></div>
						</div> 
                 <?php
                 $k++;	
                 $self_vaccine_total = $self_vaccine_total+$vaccination_collection->debit;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_vaccine_total,2); ?></span>
						</div>
					</div> 
                
			<?php } ?>
                


				</div>
				<?php	
                }
				?>
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