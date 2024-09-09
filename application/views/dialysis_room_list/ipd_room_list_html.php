<html>
<head>
<title>IPD Room List Report</title>
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
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
</style>
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px">
 <tr>
    <th>Sr.No.</th>
     <th>Room No.</th>
    <th>Room Type</th>
    <?php 
    $users_data= $this->session->userdata('auth_users');

    $get_charges= get_room_charge_according_to_branch($users_data['parent_id']); 
    foreach($get_charges as $charges_type){

    ?>
    <th> <?php echo ucfirst($charges_type->charge_type);?> </th>
                    <?php }?>
    <th>No. of Beds</th>
    
  </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
   	   ?>
   	    <tr>
            <td><?php echo $i; ?>.</td>
            <td><?php echo $data->room_no; ?></td>
            <td><?php echo $data->room_category; ?></td>
            <?php $get_charges= get_room_charge_according_to_branch($users_data['parent_id']);

            foreach($get_charges as $charge_type)
            {
            $room_charges = get_room_charge_accordint_to_id($users_data['parent_id'], $charge_type->id, $data->room_type_id, $data->id);
            echo '<td>';
            if(!empty($room_charges[0]->room_charge))
              {
            ?>
            <?php echo $room_charges[0]->room_charge;?>
            <?php
             }
             else
             {
                echo 0;
              }  
             
             } 
             ?>
            </td>
            <td><?php $get_bad_number= count_bad($data->id);

              if($get_bad_number[0]->total_bad >0 && isset($get_bad_number))
              {
               echo $get_bad_number[0]->total_bad;
              }else
              {
              echo 0;
              }?>
           
            </td>
      		 
      </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body>
</html>