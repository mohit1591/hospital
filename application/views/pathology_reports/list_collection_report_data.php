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
	  height: 29.7cm;  
	  padding: 3em;
	  font-size:13px;
	}
</style>
</head>
<body>

	<page size="A4" style="font:13px Arial;">
		
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
			</tr>
		</table>
		<!-- Branch list start -->
		
		
		<!-- Branch list end -->
        <!-- Doctor list start -->
        <?php
        $pay_mode = array('0'=>'','1'=>'Cash', '2'=>'Cheque', '3'=>'Card', '4'=>'NEFT');
        $data_empty = 0;
        if(!empty($doctor_collection_list))
        {
        	$data_empty = 1;
        ?>
        <div style="float:left;width:100%;margin-top:10px; border:1px solid #111;font-size:13px;">
			<div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Sr.No.</u></div>
			<div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Date</u></div>
			<div style="float:left;width:25%;font-weight:600;padding:4px;"><u>Doctor name</u></div>
			<div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Pay mode</u></div>
			<div style="float:right;width:18%;font-weight:600;padding:4px;text-align:right;"><u>Amount</u></div>
		</div>
		
			<!-- body -->
		<div style="float:left;width:100%;border:1px solid #111;border-top:none;font-size:13px;">	
				<div style="float:left;width:100%;font-weight:600;padding:4px;">
					<span style="border-bottom:1px solid #111;">Doctors</span>
				</div>
				<?php
                $j=1;
                $doctors_counter = count($doctor_collection_list);
                $doctor_total = '0.00';
                foreach($doctor_collection_list as $doctors)
                {
                	
				?>
				<div style="float:left;width:100%;margin-top:10px;  font-size:13px;">
					<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $j; ?></div>
					<div style="float:left;width:20%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($doctors->created_date)); ?> </div>
					<div style="float:left;width:25%;padding:1px 4px;"><?php echo $doctors->doctor_name; ?></div>
					<div style="float:left;width:20%;padding:1px 4px;"><?php echo $pay_mode[$doctors->pay_mode]; ?></div>
					<div style="float:right;width:18%;padding:1px 4px;text-align:right;<?php if($doctors_counter==$j){ echo ' border-bottom:1px solid #111'; } ?>;"><?php echo $doctors->debit; ?></div>
				</div>
				<?php
				$doctor_total = $doctor_total+$doctors->debit;
				$j++;
                }
				?>
				<div style="float:left;width:100%;padding:4px;text-align:right;">
					<div style="float:right;width:20%; font-weight: bold;">
						<span style="float:left;">Total:</span>
						<span style="float:right;"><?php echo number_format($doctor_total,2); ?></span>
					</div>
				</div> 
		</div>
        <?php	
        }
        ?>

		<!-- Doctor list end -->
		
		
		
		
		
				
		
				<?php
                if(!empty($self_collection_list) || !empty($branch_collection_list))
                {
                 $data_empty = 1;
                ?>
                <div style="float:left; margin-top:10px; width:100%;border:1px solid #111;border-top:none;font-size:13px;">				
				
					<div style="float:left;width:100%;border:1px solid #111;border-left:none;border-right:none;font-size:13px;">
						<div style="float:left;width:10%;font-weight:600;padding:4px;"><u>S.No.</u></div>
						<div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Date</u></div>
						<div style="float:left;width:25%;font-weight:600;padding:4px;"><u>Patient name</u></div> 
						<!-- <div style="float:left;width:25%;font-weight:600;padding:4px;"><u>Pay mode</u></div> -->
						<div style="float:left;width:25%;font-weight:600;padding:4px;"><u>Doctor Name</u></div>
						<div style="float:right;width:20%;font-weight:600;padding:4px;text-align:right;"><u>Amount</u></div>
					</div>
					<div style="float:left;width:100%;font-weight:600;padding:4px;">
						<span style="border-bottom:1px solid #111;">Branch : Self</span>
					</div>
                <?php
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_collection_list);
                foreach($self_collection_list as $collection)
                {
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div>
							<div style="float:left;width:15%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($collection->created_date)); ?> </div>
							<div style="float:left;width:25%;padding:1px 4px;"><?php echo $collection->patient_name; ?></div> 
							<div style="float:left;width:25%;padding:1px 4px;"><?php echo $collection->doctor_name; //$pay_mode[$collection->pay_mode]; ?></div>
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
                

                <!-- Child Branch start -->
                <?php
				
		        if(!empty($branch_collection_list))
		        {  
		        	$data_empty = 1;
		          $branch_names = [];	
		          foreach($branch_collection_list as $names)
		          {
		          	 $branch_names[] = $names->branch_name;
		          }
		         $branch_names = array_unique($branch_names);	 
		        ?>
		          
					
				<?php
				if(!empty($branch_names))
				{
				foreach($branch_names as $names)
				{
		        ?>
		        <div style="float:left;width:100%;font-weight:600;padding:4px;">
					   <span style="border-bottom:1px solid #111;">Branch : <?php echo $names; ?></span>
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
				<div style="float:left;width:100%;">
					<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>
					<div style="float:left;width:15%;padding:1px 4px;"><?php echo date('d-m-Y', strtotime($branchs->created_date)); ?> </div>
					<div style="float:left;width:25%;padding:1px 4px;"><?php echo $branchs->patient_name; ?></div> 
					<div style="float:left;width:25%;padding:1px 4px;"><?php echo $branchs->doctor_name; //$pay_mode[$collection->pay_mode]; ?></div>
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
				}
				
				?>		 
				</div>
		        <?php	 
		        }
				?>
                <!-- Child Branch End -->
                <?php	
                }
                if($data_empty==0)
                {
                	echo '<div style="float:left; margin-top:10px; width:100%;border:1px solid #111; font-weight:bold; text-align:center; font-size:13px;"> Record not found</div>';
                }
				?> 


	</page>

</body>
</html>