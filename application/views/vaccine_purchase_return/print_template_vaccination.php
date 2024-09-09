<?php 
//print '<pre>';print_r($all_detail['purchase_list']);die;
$payment_mode=$payment_mode[0]->payment_mode;;



/* start thermal printing */
//print '<pre>';print_r($all_detail['purchase_list']);die;
if($template_data->printer_id==2){
		$template_data->template = str_replace("Discount (%):","Discount ({discount_percent}%):",$template_data->template);
	$template_data->template = str_replace("{discount_percent}",$all_detail['purchase_list'][0]->discount_percent,$template_data->template);
	$template_data->template = str_replace("{gender_age}","",$template_data->template);
	$template_data->template = str_replace("INVOICE:","Invoice No :",$template_data->template);
	$template_data->template = str_replace("Name:","Vendor Name :",$template_data->template);
	$template_data->template = str_replace("Bill To::","Vendor Code :",$template_data->template);
	
$template_data->template = str_replace("{vendor_name}",$all_detail['purchase_list'][0]->name,$template_data->template);
 //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
$template_data->template = str_replace("{mobile_no}",$all_detail['purchase_list'][0]->mobile,$template_data->template);

$template_data->template = str_replace("{invoice_no}",$all_detail['purchase_list'][0]->invoice_id,$template_data->template);
$template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['purchase_list'][0]->purchase_date)),$template_data->template);

$pos_start = strpos($template_data->template, '{start_loop}');
$pos_end = strpos($template_data->template, '{end_loop}');
$row_last_length = $pos_end-$pos_start;
$row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);
// Replace looping row//
$rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+10));
$template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);

//////////////////////// 
$i=1;
$tr_html = "";
foreach($all_detail['purchase_list']['medicine_list'] as $medicine_list)
{ 
	 $tr = $row_loop;
	 $tr = str_replace("{sn}",$i,$tr);
	 $tr = str_replace("{vaccine_name}",$medicine_list->vaccine_name,$tr);
	 $tr = str_replace("{vaccine_per_net_amount}",$medicine_list->total_amount,$tr);
	 $tr_html .= $tr;
	 $i++;

}
$template_data->template = str_replace("{tot_cgst}",$all_detail['purchase_list'][0]->cgst,$template_data->template);
$template_data->template = str_replace("{tot_sgst}",$all_detail['purchase_list'][0]->sgst,$template_data->template);
$template_data->template = str_replace("{tot_igst}",$all_detail['purchase_list'][0]->igst,$template_data->template);
$template_data->template = str_replace("{total_amount}",$all_detail['purchase_list'][0]->total_amount,$template_data->template);
//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
$template_data->template = str_replace("{total_vat}",$all_detail['purchase_list'][0]->vat_percent,$template_data->template);

$template_data->template = str_replace("{total_discount}",$all_detail['purchase_list'][0]->discount,$template_data->template);

$template_data->template = str_replace("{total_net}",$all_detail['purchase_list'][0]->net_amount,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['purchase_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{balance}",$all_detail['purchase_list'][0]->balance,$template_data->template);
$template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);
$template_data->template = str_replace("{signature}",ucfirst($all_detail['purchase_list'][0]->user_name),$template_data->template);
 echo $template_data->template; 

}
/* end thermal printing */





/* start dot printing */
if($template_data->printer_id==3){
	$template_data->template = str_replace("Disc:","Discount ({discount_percent}%):",$template_data->template);
	$template_data->template = str_replace("{discount_percent}",$all_detail['purchase_list'][0]->discount_percent,$template_data->template);
	$template_data->template = str_replace("{gender_age}","",$template_data->template);
	$template_data->template = str_replace("INVOICE:","Invoice No :",$template_data->template);
		$template_data->template = str_replace("Bill To::","Vendor Code :",$template_data->template);
	
		$template_data->template = str_replace("Name:","Vendor Name :",$template_data->template);
	$template_data->template = str_replace("{vendor_name}",$all_detail['purchase_list'][0]->name,$template_data->template);
 //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
$template_data->template = str_replace("{mobile_no}",$all_detail['purchase_list'][0]->mobile,$template_data->template);

$template_data->template = str_replace("{invoice_no}",$all_detail['purchase_list'][0]->invoice_id,$template_data->template);
$template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['purchase_list'][0]->purchase_date)),$template_data->template);

$pos_start = strpos($template_data->template, '{start_loop}');
$pos_end = strpos($template_data->template, '{end_loop}');
$row_last_length = $pos_end-$pos_start;
$row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);

// Replace looping row//
$rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+12));
$template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
//////////////////////// 
$i=1;
$j=0;
$tr_html = "";
$total_quantity_amt=0;
$total_discount_amt=0;
$total_vat_amt=0;
$total_mrp=0;
$total_cgst=0;
$total_sgst=0;
$total_igst=0;
$tot_medicine=0;
foreach($all_detail['purchase_list']['medicine_list'] as $medicine_list)
{ 
	$tr = $row_loop;
	$tot_medicine=$tot_medicine+$i;
	$tr = str_replace("{s_no}",$i,$tr);
	$total_quantity_amt=$total_quantity_amt+$medicine_list->qty;
	$total_discount_amt=$total_discount_amt+$medicine_list->discount;
	$total_vat_amt=$total_vat_amt+$medicine_list->vat;
	$total_cgst=$total_cgst+$medicine_list->m_cgst;
	$total_sgst=$total_sgst+$medicine_list->m_sgst;
	$total_igst=$total_igst+$medicine_list->m_igst;
	$total_mrp=$total_mrp+$medicine_list->mrp;
	$tr = str_replace("{vaccine_qty}",$medicine_list->qty,$tr);
	$tr = str_replace("{vaccine_per_discount}",$medicine_list->discount,$tr);
	$tr = str_replace("{vaccine_per_vat}",$medicine_list->vat,$tr);
	$tr = str_replace("{vaccine_per_price}",$medicine_list->p_r,$tr);
	$tr = str_replace("{cgst}",$medicine_list->m_cgst,$tr);
	  $tr = str_replace("{sgst}",$medicine_list->m_sgst,$tr);
	  $tr = str_replace("{igst}",$medicine_list->m_igst,$tr);
	$tr = str_replace("{vaccine_name}",$medicine_list->vaccination_name,$tr);
	$tr = str_replace("{vaccine_per_net_amount}",$medicine_list->total_amount,$tr);
	$tr_html .= $tr;
	$i++;
	$j++;

}
//echo $i;
$template_data->template = str_replace("{total_cgst}",$total_cgst,$template_data->template);
$template_data->template = str_replace("{total_sgst}",$total_sgst,$template_data->template);
$template_data->template = str_replace("{total_igst}",$total_igst,$template_data->template);

$template_data->template = str_replace("{tot_cgst}",$all_detail['purchase_list'][0]->cgst,$template_data->template);
$template_data->template = str_replace("{tot_sgst}",$all_detail['purchase_list'][0]->sgst,$template_data->template);
$template_data->template = str_replace("{tot_igst}",$all_detail['purchase_list'][0]->igst,$template_data->template);
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
$template_data->template = str_replace("{total_vaccine}",$j,$template_data->template);
$template_data->template = str_replace("{total_quantity}",$total_quantity_amt,$template_data->template);
$template_data->template = str_replace("{total_discount_amt}",$total_discount_amt,$template_data->template);
$template_data->template = str_replace("{total_vat_amt}",$total_vat_amt,$template_data->template);
$template_data->template = str_replace("{total_amt_per}",$total_mrp,$template_data->template);
$template_data->template = str_replace("{total_per_price_amt}",$total_mrp,$template_data->template);
//echo $i;
$template_data->template = str_replace("{total_vat}",$all_detail['purchase_list'][0]->vat_percent,$template_data->template);
$template_data->template = str_replace("{salesman}",$all_detail['purchase_list'][0]->vendor_id,$template_data->template);

$template_data->template = str_replace("{total_discount}",$all_detail['purchase_list'][0]->discount,$template_data->template);

$template_data->template = str_replace("{total_net}",$all_detail['purchase_list'][0]->net_amount,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['purchase_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{total_gross}",$all_detail['purchase_list'][0]->total_amount,$template_data->template);

$template_data->template = str_replace("{balance}",$all_detail['purchase_list'][0]->balance,$template_data->template);
$template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);
$template_data->template = str_replace("{signature}",ucfirst($all_detail['purchase_list'][0]->user_name),$template_data->template);
if(!empty($all_detail['purchase_list'][0]->remarks))
        {
           $template_data->template = str_replace("{remarks}",$all_detail['purchase_list'][0]->remarks,$template_data->template);
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
//print '<pre>';print_r($all_detail['sales_list']);die;
if($template_data->printer_id==1){
	$template_data->template = str_replace("Discount (%):","Discount ({discount_percent}%):",$template_data->template);
	$template_data->template = str_replace("{discount_percent}",$all_detail['purchase_list'][0]->discount_percent,$template_data->template);
	$template_data->template = str_replace("{gender_age}","",$template_data->template);
	$template_data->template = str_replace("INVOICE:","Invoice No :",$template_data->template);
	$template_data->template = str_replace("Name:","Vendor Name :",$template_data->template);
		$template_data->template = str_replace("Bill To:","Vendor Code :",$template_data->template);
	$template_data->template = str_replace("{vendor_name}",$all_detail['purchase_list'][0]->name,$template_data->template);
 //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
$template_data->template = str_replace("{mobile_no}",$all_detail['purchase_list'][0]->mobile,$template_data->template);
    $template_data->template = str_replace("{refered_by}",'',$template_data->template);

$template_data->template = str_replace("{invoice_no}",$all_detail['purchase_list'][0]->invoice_id,$template_data->template);
$template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['purchase_list'][0]->purchase_date)),$template_data->template);

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
foreach($all_detail['purchase_list']['medicine_list'] as $medicine_list)
{ 

	//print_r($medicine_list);
	 $tr = $row_loop;
	 $tr = str_replace("{s_no}",$i,$tr);
	 $tr = str_replace("{quantity}",$medicine_list->qty,$tr);
	  $tr = str_replace("{mrp}",$medicine_list->p_r,$tr);
	 $tr = str_replace("{vaccine_name}",$medicine_list->vaccination_name,$tr);
	 $tr = str_replace("{cgst}",$medicine_list->m_cgst,$tr);
	  $tr = str_replace("{sgst}",$medicine_list->m_sgst,$tr);
	  $tr = str_replace("{igst}",$medicine_list->m_igst,$tr);
	 $tr = str_replace("{total_amount}",$medicine_list->total_amount,$tr);
	 $tr_html .= $tr;
	 $i++;

}
$template_data->template = str_replace("{purchase_no}",$all_detail['purchase_list'][0]->purchase_id,$template_data->template);

$template_data->template = str_replace("{email_id}",$all_detail['purchase_list'][0]->v_email,$template_data->template);
$template_data->template = str_replace("{address}",$all_detail['purchase_list'][0]->v_address,$template_data->template);
$template_data->template = str_replace("{tot_cgst}",$all_detail['purchase_list'][0]->cgst,$template_data->template);
$template_data->template = str_replace("{tot_sgst}",$all_detail['purchase_list'][0]->sgst,$template_data->template);
$template_data->template = str_replace("{tot_igst}",$all_detail['purchase_list'][0]->igst,$template_data->template);
//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

//echo $i;
$template_data->template = str_replace("{vat}",$all_detail['purchase_list'][0]->vat_percent,$template_data->template);
$template_data->template = str_replace("{sales_name}",$all_detail['purchase_list'][0]->vendor_id,$template_data->template);

$template_data->template = str_replace("{discount}",$all_detail['purchase_list'][0]->discount_percent,$template_data->template);

$template_data->template = str_replace("{net_amount}",$all_detail['purchase_list'][0]->net_amount,$template_data->template);
$template_data->template = str_replace("{vat_percent}",$all_detail['purchase_list'][0]->vat_percent,$template_data->template);
$template_data->template = str_replace("{discount_percent}",$all_detail['purchase_list'][0]->discount_percent,$template_data->template);
$template_data->template = str_replace("{tot_discount}",$all_detail['purchase_list'][0]->discount,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['purchase_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{gross_total_amount}",$all_detail['purchase_list'][0]->total_amount,$template_data->template);

$template_data->template = str_replace("{balance}",$all_detail['purchase_list'][0]->balance,$template_data->template);
$template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);
$template_data->template = str_replace("{signature}",ucfirst($all_detail['purchase_list'][0]->user_name),$template_data->template);
if(!empty($all_detail['purchase_list'][0]->remarks))
        {
           $template_data->template = str_replace("{remarks}",$all_detail['purchase_list'][0]->remarks,$template_data->template);
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

<?php
if(!empty($download_type) && $download_type==2)
{
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>html2canvas.js"></script>
<script>
 //$(function(){
  //$("#gimg").click(function(){

    //$('#gimg').hide();
$(document).ready(function() { 
     html2canvas($("page"), {
        onrendered: function(canvas) {
           var imgsrc = canvas.toDataURL("image/png");
            $.ajax({
                url:'<?php echo base_url('vaccine_purchase_return/save_image')?>', 
                type:'POST', 
                data:{
                    data:imgsrc,
                    patient_name:"<?php echo $all_detail['purchase_list'][0]->name; ?>",
                    patient_code:"<?php echo $all_detail['purchase_list'][0]->vendor_id; ?>"
                    },
                success: function(result)
                {
                    //alert(result); return 1;
                     location.href =result;
                //var dt = canvas.toDataURL();
               // this.href = dt; //this may not work in the future..
 

                    //var opened = view.open(object_url, "_blank");
                    //view.location.href = object_url;
                    //var dataURL = $canvas[0].toDataURL('image/png');
                    //w.document.write("<img src='" + dataURL + "' alt='from canvas'/>");
                }

                
            });
           
        }
     });
  });   
  //});  
  //});
</script>
<?php } ?>

