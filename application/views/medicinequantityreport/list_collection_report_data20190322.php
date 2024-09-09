<?php  $users_data = $this->session->userdata('auth_users'); ?>
<!DOCTYPE html>
<html>
<head>
<title>Medicine Quantity Report</title>
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
				<td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Medicine Quantity Report</span></td>
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


	<div style="float:left;width:100%;border:1px solid #111;margin-bottom:10px;">

	
        <div style="float:left;width:100%;border-bottom:1px solid #111;font-size:13px;">
			<div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Sr.No.</u></div>
		    <div style="float:left;width:20%;font-weight:600;padding:4px;">Purchase</div> 
			<div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Purchase Return</u></div>
			<div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Sale</u></div>
			<div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Sale Return</u></div>
			</div>
		</div> 
		<?php 
		$i=1;
		
		foreach($medicine_list as $medicines)
		{ 
			if(!empty($medicines['medicine_name']))
		{ ?>
		<div style="float:left;width:100%;font-size:13px;">	
			<div style="float:left;width:100%;font-weight:600;padding:4px;">
			<span style="border-bottom:1px solid #111;"><?php echo $medicines['medicine_name']; ?> <?php if($get['module_type']==1){echo "(".$medicines['med_name'].")";}?> </span>
			</div>	

			<div style="float:left;width:100%;">
			<div style="float:left;width:20%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>
			<div style="float:left;width:20%;padding:1px 4px;text-indent:15px;"><?php if($medicines['purchsase_quantity']['total_qty']>0){echo $medicines['purchsase_quantity']['total_qty'];}else{echo '';} ?></div>
			<div style="float:left;width:20%;padding:1px 4px;text-indent:15px;"><?php  echo abs($medicines['purchsase_return_quantity']['total_qty']); ?></div> 
			<div style="float:left;width:20%;padding:1px 4px;"><?php echo abs($medicines['sale_quantity']['total_qty']);?></div>
			<div style="float:left;width:20%;padding:1px 4px;"><?php if($medicines['sale_return_quantity']['total_qty']>0){ echo $medicines['sale_return_quantity']['total_qty'];} else{echo '';}?></div> 
			
			</div>
		</div>
       <?php $i++;} }?>
	
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