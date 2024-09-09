$template_data->template = str_replace("Discount (%):","Discount ({discount_percent}%):",$template_data->template);
	$template_data->template = str_replace("{discount_percent}",$all_detail['purchase_list'][0]->discount_percent,$template_data->template);



	$template_data->template = str_replace("Disc:","Discount ({discount_percent}%):",$template_data->template);
	$template_data->template = str_replace("{discount_percent}",$all_detail['purchase_list'][0]->discount_percent,$template_data->template);



	ALTER TABLE `hms_medicine_sale` CHANGE `discount_percent` `discount_percent` INT NOT NULL;

	ALTER TABLE `hms_medicine_sale_return` CHANGE `discount_percent` `discount_percent` INT NOT NULL;