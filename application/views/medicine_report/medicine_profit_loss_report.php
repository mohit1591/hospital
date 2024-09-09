<?php  $users_data = $this->session->userdata('auth_users'); ?>
<!DOCTYPE html>
<html>
<head>
<title>Medicine Profit Loss Report</title>
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
				<td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Medicine Profit Loss Report</span></td>
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
			<div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Sr.No.</u></div>
			<div style="float:left;width:30%;font-weight:600;padding:4px;"><u>Medicine Name</u></div>
		    <div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Batch No.</u></div>
		    <div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Qty</u></div>
		    <div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Sale Amt</u></div>
		    <div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Sale Ret Amt</u></div>
		    <div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Purchase Amt</u></div>
		    <div style="float:left;width:10%;font-weight:600;padding:4px;text-align: right;"><u>Profit</u></div>
		</div>
	</div> 
		<?php 
	//	echo "<pre>";print_r($medicine_list); exit;
		$i=1;
		$total_sale=0;
		$total_purchase=0;
		$total_profit=0;
		$total_sale_return=0;
		foreach($medicine_list as $medicines)
		{ 
			if(!empty($medicines['medicine_name']))
			{ 
				
				
				?>

			 <div style="float:left;width:100%;font-size:13px;">	
				<div style="float:left;width:100%;font-weight:600;padding:4px;">
					<div style="float:left;width:10%;padding:1px 4px;"><?php echo $i; ?></div>
					<div style="float:left;width:30%;padding:1px 4px;"><?php echo  wordwrap(trim($medicines['medicine_name']),15,'<br>'); ?></div>
					
					<div style="float:left;width:10%;padding:1px 4px;"><?php  echo $medicines['batch_no']; ?></div> 

					<div style="float:left;width:10%;padding:1px 4px;"><?php  echo abs($medicines['sale_total_qty']); ?></div> 

					<div style="float:left;width:10%;padding:1px 4px;"><?php  echo number_format(abs($medicines['sale_total_price']-$medicines['sale_discount_amount']),2); ?></div> 
					<div style="float:left;width:10%;padding:1px 4px;"><?php  echo number_format(abs($medicines['sale_return_total_price']),2); ?></div> 

					<div style="float:left;width:10%;padding:1px 4px;"><?php  

					if(!empty($medicines['purchase_total_price']) && $medicines['purchase_total_price']!='')
					{
						//$purchasetotalprice = $medicines['purchase_total_price']; //*$medicines['sale_total_qty']
						$purchasetotalprice = ($medicines['purchase_total_price']-$medicines['purchase_discount_amount'])*$medicines['sale_total_qty'];
					}
					else
					{
						//$purchasetotalprice = $medicines['opening_stock_price']; //*$medicines['sale_total_qty']
					    $purchasetotalprice = $medicines['opening_stock_price']*$medicines['sale_total_qty'];
					    
					}

					
					echo number_format($purchasetotalprice,2); ?></div> 
					<div style="float:left;width:10%;padding:1px 4px;text-align: right;"><?php  
					
				 	$discounted_amt = $medicines['sale_total_price']-$medicines['sale_discount_amount'];
					$total_purchase_profit = $discounted_amt-$purchasetotalprice;
					$total_med_profit = $total_purchase_profit-$medicines['sale_return_total_price'];
					echo number_format($total_med_profit,2); ?></div> 
					
			
			</div>
			</div>
       <?php 
       	$total_sale=$total_sale+($medicines['sale_total_price']-$medicines['sale_discount_amount']);
		
		$total_sale_return=$total_sale_return+$medicines['sale_return_total_price'];

		//$total_purchase=$total_purchase+$medicines['purchase_total_price'];

		$total_purchasetotalprice = $total_purchasetotalprice+$purchasetotalprice;

		$total_profit=$total_profit+$total_med_profit;

       $i++;} 



   }
		?>

		<div style="float:left;width:100%;font-size:13px;">	
		<div style="float:left;width:100%;font-weight:600;padding:4px;border-top:1px solid #ccc;">
			
			<div style="float:left;width:15%;padding:1px 4px;">&nbsp;</div> 
			<div style="float:left;width:35%;padding:1px 4px;text-align: right;">Total</div> 
			<div style="float:left;width:10%;padding:1px 4px;">&nbsp;</div>

			<div style="float:left;width:10%;padding:1px 4px;"><?php  echo number_format($total_sale,2); ?></div> 
			<div style="float:left;width:10%;padding:1px 4px;"><?php echo number_format($total_sale_return,2) ?></div>

			<div style="float:left;width:10%;padding:1px 4px;"><?php  echo number_format($total_purchasetotalprice,2); ?></div> 
			<div style="float:left;width:10%;padding:1px 4px;text-align: right;"><?php  echo number_format($total_profit,2); ?></div> 
			
	
	</div>
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