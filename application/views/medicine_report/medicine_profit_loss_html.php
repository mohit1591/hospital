<html>
<head>
<title>Pathology Collection Report</title>
<?php
if($print_status==1)
{
?>
<script type="text/javascript">
window.print();
window.onfocus=function(){ window.close();}
</script>
<?php	
}
?>
</head>
<style>
body
{
	font-size: 10px;
}	
td
{
	padding-left:3px;
}
</style>
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px">
 <tr>
    <th width="40" align="center">S.No.</th> 
                    <th align="center"> Purchase </th> 
                    <th align="center"> Purchase Return </th> 
                    <th align="center"> Sales </th>
                    <th align="center"> Sales Return </th> 
                    <th align="center"> Pay to Vendor </th>  
                    <th align="center"> Date </th>  
 </tr>
 </thead>

 <tbody>
                <?php 
                $purchase_grand_total=0;
        $purchase_grand_return_total = 0;
        $sales_grand_total = 0;
        $sales_grand_return_total = 0;
        $vendor_grand_total=0;
        $search = $this->session->userdata('consolidated_search');

        $date1 = new DateTime(date('Y-m-d',strtotime($search['start_date'])));
        $date2 = new DateTime(date('Y-m-d',strtotime($search['end_date'])));
        $date2->modify("+1 days");
        $interval = $date1->diff($date2);
        $days= $interval->days;
        $total_num = $days;          
        $k=1;           
        $i=1;

        $start_date_time = new DateTime($search['start_date']);
        $end_date_time = new DateTime($search['end_date']);

        /*var_dump($date1 == $date2);
        var_dump($date1 < $date2);
        var_dump($date1 > $date2);*/
        $p=0;
        while($k <= $days) 
        { 
            $date1_v='';
            if($start_date_time == $end_date_time)
            {
                $date1_v = $search['start_date'];
            }
            else
            {
                $date1_v= date('d-m-Y', strtotime($search['start_date']. $p.'  days'));
            }


              $purchase_total = get_purchase_amount($date1_v);
                $purchase_grand_total = $purchase_grand_total+$purchase_total;
                $purchase_return_total = get_purchase_return_amount($date1_v);
                $purchase_grand_return_total = $purchase_grand_return_total+$purchase_return_total;
                $sale_total = get_sales_amount($date1_v);
                $sales_grand_total = $sales_grand_total+$sale_total;
                $sale_return_total = get_sales_return_amount($date1_v);

                $sales_grand_return_total = $sales_grand_return_total+$sale_return_total;
                $vendor_total =get_vendor_payment_amount($date1_v);
                $vendor_grand_total = $vendor_grand_total+$vendor_total;

              ?>

              <tr>
              <td align="center"><?php echo $k; ?></td>
              <td align="center"><?php echo $purchase_total; ?></td>
              <td align="center"><?php echo $purchase_grand_total; ?></td>
              <td align="center"><?php echo $sale_total; ?></td>
              <td align="center"><?php echo $sales_grand_return_total; ?></td>
              <td align="center"><?php echo $vendor_total;?></td>
              <td align="center"><?php echo $date1_v; ?></td>
              </tr>
              <?php  
                
              if($k==$days)
               {
                ?>
                <tr>
                  <td align="center">Total</td>
                  <td align="center"><?php echo $purchase_grand_total; ?></td>
                  <td align="center"><?php echo $purchase_grand_return_total; ?></td>
                  <td align="center"><?php echo $sales_grand_total; ?></td>
                  <td align="center"><?php echo $sales_grand_return_total; ?></td>
                  <td align="center"><?php echo $vendor_grand_total; ?></td>
                  <td></td>
                </tr>
                <?php 
              }

            $k++;
          $p++;


        }
                ?>
  </tbody> 
</table>
</body>
</html>