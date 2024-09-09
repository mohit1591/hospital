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
	  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
	}
	page[size="A4"] {  
	                width: 21cm;
			min-height: 29.7cm;
			padding: 3em;
			font-size: 13px;
			float: left;
	}
	@page {
	size: auto;   /* auto is the initial value */
	margin: 0;  /* this affects the margin in the printer settings */
	}
</style>
</head>
<body style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">

	<page size="A4">
		
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Source Report</span></td>
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
		<table>
				



				<?php
		
        if(!empty($branch_source_from_list))
        { 
          $branch_names = [];	
          foreach($branch_source_from_list as $names)
          {
          	 $branch_names[] = $names->branch_name;
          }	 
        ?>
        <div style="float:left;width:100%;border:1px solid #111;font-size:13px;">
			<div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Sr.No.</u></div>
			<div style="float:left;width:30%;font-weight:600;padding:4px;"><u>Source</u></div>
			<div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Total</u></div>
			<div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Appointment</u></div>
			<div style="float:left;width:15%;font-weight:600;padding:4px;"><u>OPD Booking</u></div>
			<div style="float:left;width:15%;font-weight:600;padding:4px;"><u>OPD Billing</u></div>
			
		</div> 
		<div style="float:left;width:100%;border:1px solid #111;border-top:none;font-size:13px;">	
			
	<?php
	if(!empty($branch_names))
	{
	foreach($branch_names as $names)
	{
    ?>
    <div style="float:left;width:100%;font-weight:600;padding:4px;">
		   <span style="border-bottom:1px solid #111;">Branch : <?php echo $names; ?></span>
	</div>	
	<?php if(!empty($branch_source_from_list)) { ?>
	
    <?php 

    $i = 1; 	
	$branch_total = 0;	
	$count_branch = count($branch_source_from_list);
	$n_bnc = '';
    foreach($branch_source_from_list as $branchs)
    {   
     if($names == $branchs->branch_name)	
     {
    ?>	  
	<div style="float:left; width: 100%">
	 <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>
		<div style="float:left;width:30%;padding:1px 4px;"><?php echo $branchs->source; ?></div>
		<div style="float:left;width:15%;padding:1px 4px;"><?php echo $branchs->total; ?></div>
		<div style="float:left;width:15%;padding:1px 4px;"><?php echo $branchs->total_enquiry; ?></div>
		<div style="float:left;width:15%;padding:1px 4px;"><?php echo $branchs->total_booking; ?></div>
		<div style="float:left;width:15%;padding:1px 4px;"><?php echo $branchs->total_billing; ?></div>
		
		
	</div>		
	<?php
	$i++;
	
     }
	}
} 
}
}
		
?>		 
</div>
<?php	 
}
?>					
					<div style="float:left; margin-top:10px; width:100%;border:1px solid #111;border-top:none;font-size:13px;border-bottom:none;">				
				
					<div style="float:left;width:100%;border:1px solid #111;border-left:none;border-right:none;font-size:13px;">
						<div style="float:left;width:10%;font-weight:600;padding:4px;border-left:none;border-right:none;"><u>S.No.</u></div>
						<div style="float:left;width:30%;font-weight:600;padding:4px;border-left:none;border-right:none;"><u>Source</u></div>
						<div style="float:left;width:15%;font-weight:600;padding:4px;border-left:none;border-right:none;"><u>Total</u></div>
						<div style="float:left;width:15%;font-weight:600;padding:4px;border-left:none;border-right:none;"><u>Appointment</u></div> 
						
						<div style="float:left;width:15%;font-weight:600;padding:4px;border-left:none;border-right:none;"><u>OPD Booking</u></div>
						<div style="float:left;width:15%;font-weight:600;padding:4px;border-left:none;border-right:none;"><u>OPD Billing</u></div>
						
					</div>
			<div style="float:left;width:100%;border:1px solid #111;border-top:none;font-size:13px;">
			 <div style="float:left;width:100%;font-weight:600;padding:4px;">
				<span style="border-bottom:1px solid #111;">Branch : Self</span>
			</div> 
			 
				<?php
                if(!empty($source_from_list))
                {
				$l = 1 ;
                $source_total = 0;
                $source_counter = count($source_from_list);
                //echo "<pre>";print_r($self_collection_list);
                foreach($source_from_list as $source_report)
                { 
                 ?>
						

			<div style="float:left; width: 100%">
			 <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $l; ?></div>
				<div style="float:left;width:30%;padding:1px 4px;"><?php echo $source_report->source; ?></div>
				<div style="float:left;width:15%;padding:1px 4px;"><?php echo $source_report->total; ?></div>
				<div style="float:left;width:15%;padding:1px 4px;"><?php echo $source_report->total_enquiry; ?></div>
				<div style="float:left;width:15%;padding:1px 4px;"><?php echo $source_report->total_booking; ?></div>
				<div style="float:left;width:15%;padding:1px 4px;"><?php echo $source_report->total_billing; ?></div>
				
			</div>
                 <?php
                 $l++;	
                 } 
                ?>
					
                </div>
                <?php	
                }
				?> 
				</div>
				
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