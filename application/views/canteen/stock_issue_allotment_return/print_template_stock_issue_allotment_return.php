<?php 
//print '<pre>';print_r($all_detail['stock_issue_list']);die;

if($template_data->printer_id==2)
{
	$template_data->template = str_replace("{member_name}",$all_detail['stock_issue_return_list'][0]->member_name,$template_data->template);
	$template_data->template = str_replace("{issue_date}",date('d-m-Y',strtotime($all_detail['stock_issue_return_list'][0]->issue_date)),$template_data->template);
	$template_data->template = str_replace("{issue_no}",$all_detail['stock_issue_return_list'][0]->return_no,$template_data->template);

	$template_data->template = str_replace("{email}",$all_detail['stock_issue_return_list'][0]->email,$template_data->template);
	$template_data->template = str_replace("{mobile}",$all_detail['stock_issue_return_list'][0]->mobile,$template_data->template);
	$template_data->template = str_replace("{address}",$all_detail['stock_issue_return_list'][0]->address,$template_data->template);


	$pos_start = strpos($template_data->template, '{start_loop}');
	$pos_end = strpos($template_data->template, '{end_loop}');
	$row_last_length = $pos_end-$pos_start;
	$row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);
	// Replace looping row//
	$rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+10));
	$template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);


	$i=1;
	$tr_html = "";
	//print '<pre>'; print_r($all_detail['purchase_list']['item_list']);
	foreach($all_detail['item_list'] as $item_list)
	{ 
			$tr = $row_loop;
			$tr = str_replace("{sn}",$i,$tr);
			$tr = str_replace("{item_name}",$item_list->item_name,$tr);
			$tr = str_replace("{item_amount}",$item_list->total_amount,$tr);
			$tr_html .= $tr;
			$i++;
	}

	//echo $i;
	$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

	$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
	
	/*$template_data->template = str_replace("{discount}",$all_detail['stock_issue_return_list'][0]->discount,$template_data->template);
	$template_data->template = str_replace("{net_amount}",$all_detail['stock_issue_return_list'][0]->net_amount,$template_data->template);*/
	$template_data->template = str_replace("{total_amount}",$all_detail['stock_issue_return_list'][0]->total_amount,$template_data->template);
	/*$template_data->template = str_replace("{paid_amount}",$all_detail['stock_issue_return_list'][0]->paid_amount,$template_data->template);
	$template_data->template = str_replace("{balance}",$all_detail['stock_issue_return_list'][0]->balance,$template_data->template);*/

	$template_data->template = str_replace("{sales_name}",$all_detail['purchase_list'][0]->username,$template_data->template);

	
	$template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);

	if(!empty($all_detail['stock_issue_return_list'][0]->remarks))
    {
       $template_data->template = str_replace("{remarks}",$all_detail['stock_issue_return_list'][0]->remarks,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{remarks}",' ',$template_data->template);
       $template_data->template = str_replace("Remarks :",' ',$template_data->template);
       $template_data->template = str_replace("Remarks",' ',$template_data->template);
    }


 echo $template_data->template; 

}
/* end thermal printing */





/* start dot printing */
if($template_data->printer_id==3)
{
	$template_data->template = str_replace("{member_name}",$all_detail['stock_issue_return_list'][0]->member_name,$template_data->template);
	$template_data->template = str_replace("{issue_date}",date('d-m-Y',strtotime($all_detail['stock_issue_return_list'][0]->issue_date)),$template_data->template);
	$template_data->template = str_replace("{issue_no}",$all_detail['stock_issue_return_list'][0]->return_no,$template_data->template);

	$template_data->template = str_replace("{email}",$all_detail['stock_issue_return_list'][0]->email,$template_data->template);
	$template_data->template = str_replace("{mobile}",$all_detail['stock_issue_return_list'][0]->mobile,$template_data->template);
	$template_data->template = str_replace("{address}",$all_detail['stock_issue_return_list'][0]->address,$template_data->template);




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
	foreach($all_detail['item_list'] as $item_list)
	{ 
			$tr = $row_loop;
			$tr = str_replace("{s_no}",$i,$tr);
			$tr = str_replace("{item_name}",$item_list->item_name,$tr);
			$tr = str_replace("{item_code}",$item_list->item_code,$tr);
			$tr = str_replace("{item_unit}",$item_list->unit,$tr);
			$tr = str_replace("{item_category}",$item_list->category,$tr);
			$tr = str_replace("{item_quantity}",$item_list->quantity,$tr);
			$tr = str_replace("{item_price}",$item_list->item_price,$tr);
			$tr = str_replace("{item_amount}",$item_list->total_amount,$tr);
			$tr_html .= $tr;
			$i++;
	}

	$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

	$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
	
	/*$template_data->template = str_replace("{discount}",$all_detail['stock_issue_return_list'][0]->discount,$template_data->template);
	$template_data->template = str_replace("{net_amount}",$all_detail['stock_issue_return_list'][0]->net_amount,$template_data->template);*/
	$template_data->template = str_replace("{total_amount}",$all_detail['stock_issue_return_list'][0]->total_amount,$template_data->template);
	/*$template_data->template = str_replace("{paid_amount}",$all_detail['stock_issue_return_list'][0]->paid_amount,$template_data->template);
	$template_data->template = str_replace("{balance}",$all_detail['stock_issue_return_list'][0]->balance,$template_data->template);*/

	$template_data->template = str_replace("{sales_name}",$all_detail['stock_issue_return_list'][0]->username,$template_data->template);

	
	$template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);

	if(!empty($all_detail['stock_issue_return_list'][0]->remarks))
    {
       $template_data->template = str_replace("{remarks}",$all_detail['stock_issue_return_list'][0]->remarks,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{remarks}",' ',$template_data->template);
       $template_data->template = str_replace("Remarks :",' ',$template_data->template);
       $template_data->template = str_replace("Remarks",' ',$template_data->template);
    }
	
	echo $template_data->template;
}
/* end dot printing */

/* start leaser printing */
//print '<pre>';print_r($all_detail['purchase_list']);die;
if($template_data->printer_id==1)
{
	
	$template_data->template = str_replace("{member_name}",$all_detail['stock_issue_return_list'][0]->member_name,$template_data->template);
	$template_data->template = str_replace("{issue_date}",date('d-m-Y',strtotime($all_detail['stock_issue_return_list'][0]->issue_date)),$template_data->template);
	$template_data->template = str_replace("{issue_no}",$all_detail['stock_issue_return_list'][0]->return_no,$template_data->template);

	$template_data->template = str_replace("{email}",$all_detail['stock_issue_return_list'][0]->email,$template_data->template);
	$template_data->template = str_replace("{mobile}",$all_detail['stock_issue_return_list'][0]->mobile,$template_data->template);
	$template_data->template = str_replace("{address}",$all_detail['stock_issue_return_list'][0]->address,$template_data->template);
	
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
	//print '<pre>'; print_r($all_detail['purchase_list']['item_list']);
	foreach($all_detail['item_list'] as $item_list)
	{ 
			$tr = $row_loop;
			$tr = str_replace("{s_no}",$i,$tr);
			$tr = str_replace("{item_name}",$item_list->item_name,$tr);
			$tr = str_replace("{item_code}",$item_list->item_code,$tr);
			$tr = str_replace("{item_unit}",$item_list->unit,$tr);
			$tr = str_replace("{item_category}",$item_list->category,$tr);
			$tr = str_replace("{item_quantity}",$item_list->quantity,$tr);
			$tr = str_replace("{item_price}",$item_list->item_price,$tr);
			$tr = str_replace("{item_amount}",$item_list->total_amount,$tr);
			$tr_html .= $tr;
			$i++;
	}

//echo $i;
	$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
	
	/*$template_data->template = str_replace("{discount}",$all_detail['stock_issue_return_list'][0]->discount,$template_data->template);
	$template_data->template = str_replace("{net_amount}",$all_detail['stock_issue_return_list'][0]->net_amount,$template_data->template);*/
	$template_data->template = str_replace("{total_amount}",$all_detail['stock_issue_return_list'][0]->total_amount,$template_data->template);
	/*$template_data->template = str_replace("{paid_amount}",$all_detail['stock_issue_return_list'][0]->paid_amount,$template_data->template);
	$template_data->template = str_replace("{balance}",$all_detail['stock_issue_return_list'][0]->balance,$template_data->template);*/

	$template_data->template = str_replace("{sales_name}",$all_detail['stock_issue_return_list'][0]->username,$template_data->template);

	$template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);

	if(!empty($all_detail['stock_issue_return_list'][0]->remarks))
    {
       $template_data->template = str_replace("{remarks}",$all_detail['stock_issue_return_list'][0]->remarks,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{remarks}",' ',$template_data->template);
       $template_data->template = str_replace("Remarks :",' ',$template_data->template);
       $template_data->template = str_replace("Remarks",' ',$template_data->template);
    }
	echo $template_data->template;
}

/* end leaser printing*/
?>






