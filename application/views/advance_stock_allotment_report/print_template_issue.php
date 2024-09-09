<?php
    $template_data->template = str_replace("{item_discount}","",$template_data->template);
	$template_data->template = str_replace("{vendor_name}",$branch_name,$template_data->template);
	$template_data->template = str_replace("{purchase_date}","",$template_data->template);
	$template_data->template = str_replace("{purchase_no}","",$template_data->template);
    $template_data->template = str_replace("{sales_name}","",$template_data->template);
	$template_data->template = str_replace("{vendor_email}","",$template_data->template);
	$template_data->template = str_replace("{vendor_mobile}","",$template_data->template);
	$template_data->template = str_replace("{vendor_address}","",$template_data->template);
	
	$template_data->template = str_replace("MRP","Date",$template_data->template);
	
	$template_data->template = str_replace("Purchase Order","Branch Allotment",$template_data->template);
	
	$template_data->template = str_replace("Total:","",$template_data->template);
	
	$template_data->template = str_replace("Item Discount:","",$template_data->template);
	$template_data->template = str_replace("Bill Discount:","",$template_data->template);
	$template_data->template = str_replace("CGST:","",$template_data->template);
	$template_data->template = str_replace("SGST:","",$template_data->template);
	$template_data->template = str_replace("IGST:","",$template_data->template);
	$template_data->template = str_replace("Net Amount:","",$template_data->template);
	$template_data->template = str_replace("Signature","",$template_data->template);
	
	$template_data->template = str_replace("Address:","",$template_data->template);
	
	$template_data->template = str_replace("GST IN :","",$template_data->template);
	
	$template_data->template = str_replace("Pay. Due Date:","",$template_data->template);
	
	$template_data->template = str_replace("Date :","",$template_data->template);
	
	$template_data->template = str_replace("Customer Code:","",$template_data->template);
	
	$template_data->template = str_replace("Purchase No.:","",$template_data->template);
	$template_data->template = str_replace("Price","",$template_data->template);
	$template_data->template = str_replace("Discount","",$template_data->template);
	$template_data->template = str_replace("CGST","",$template_data->template);
	$template_data->template = str_replace("SGST","",$template_data->template);
	$template_data->template = str_replace("IGST","",$template_data->template);
	$template_data->template = str_replace("Amount","",$template_data->template);

	$pos_start = strpos($template_data->template, '{start_loop}');
	$pos_end = strpos($template_data->template, '{end_loop}');
	$row_last_length = $pos_end-$pos_start;
	$row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);
	// Replace looping row//
	$rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+12));

	$template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
	//////////////////////// 
	$i=1;
	$tr_html = "";
	//print '<pre>'; print_r($item_details); die;
	foreach($item_details as $purchases_list)
	{ 
	    
	        $serial_no=array();
            $purchase_item_serial =get_serial_no_by_stock($purchases_list->stock_ids,$purchases_list->id,$purchases_list->branchids);
            //echo "<pre>"; print_r($purchase_item_serial); exit;
            $l=1;
            foreach ($purchase_item_serial as  $serial) 
            {
                array_push($serial_no, $serial->serial_no);
                if($l==3)
                {
                  array_push($serial_no,'<br>'); 
                  $l=1;
                }
                $l++;
            } 
            if(!empty($serial_no))
            {
               $serial_data=implode(",", $serial_no); 
               $mnd = " (".$serial_data.")";
            }
            else
            {
                $mnd ='';
            }
	  //  print_r($purchases_list);
  $tr = $row_loop;
  $tr = str_replace("{s_no}",$i,$tr);
  $tr = str_replace("{quantity}",$purchases_list->debit,$tr);
  $tr = str_replace("{mrp}","",$tr);
  $tr = str_replace("{item_name}",$purchases_list->item.'<br>'.$mnd,$tr);
  $tr = str_replace("{cgst}","",$tr);
  $tr = str_replace("{sgst}","",$tr);
  $tr = str_replace("{batch_no}","",$tr);
  $tr = str_replace("{exp_date}","",$tr);
  $tr = str_replace("{igst}","",$tr);
  $tr = str_replace("{discount}","",$tr);
  $tr = str_replace("{total_amount}","",$tr);  
  $tr = str_replace("{purchase_amount}",date('d-m-Y',strtotime($purchases_list->created_date)),$tr);
  $tr_html .= $tr;
  $i++;
	


	}

//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

$template_data->template = str_replace("{vat}","",$template_data->template);
$template_data->template = str_replace("Bill To:","",$template_data->template);
$template_data->template = str_replace("{sales_name}","",$template_data->template);

$template_data->template = str_replace("{customer_code}","",$template_data->template);

$template_data->template = str_replace("{vendor_gst}","",$template_data->template);

$template_data->template = str_replace("{payment_due_date}","",$template_data->template);


$template_data->template = str_replace("{tot_discount}","",$template_data->template);

$template_data->template = str_replace("{net_amount}","",$template_data->template);


$template_data->template = str_replace("{paid_amount}","",$template_data->template);

$template_data->template = str_replace("{gross_total_amount}","",$template_data->template);
$template_data->template = str_replace("{item_discount}","",$template_data->template);
$template_data->template = str_replace("{tot_cgst}","",$template_data->template);
$template_data->template = str_replace("{tot_sgst}","",$template_data->template);
$template_data->template = str_replace("{tot_igst}","",$template_data->template);
$template_data->template = str_replace("{balance}","",$template_data->template);
$template_data->template = str_replace("{payment_mode}","",$template_data->template);
 $template_data->template = str_replace("{signature}","",$template_data->template);


 $template_data->template = str_replace("{remarks}",' ',$template_data->template);
 $template_data->template = str_replace("Remarks :",' ',$template_data->template);
 $template_data->template = str_replace("Remarks",' ',$template_data->template);

echo $template_data->template;

?>






