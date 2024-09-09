<?php $users_data = $this->session->userdata('auth_users'); ?>
<html>
<head>
<title></title>
<script type="text/javascript">
window.print();
window.onfocus=function(){ window.close();}
</script>

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
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:13px Arial;">
 <tr>
    <th>Room Type</th>
    <?php 
    if(!empty($room_charge_type_list))
    {
      $room_charge_type_list_count = count($room_charge_type_list);
      for($i=0;$i<$room_charge_type_list_count;$i++)
       { 
      ?>
          <th>
              <?php 
                echo ucfirst($room_charge_type_list[$i]['charge_type']); 
              ?>
          </th>
  <?php 
       } 
    }
    ?>
   
 </tr>
 <?php 
   if(isset($room_list) && !empty($room_list))
   {
   	 $i=1;
   	 foreach($room_list as $particularlist)
     { 
       ?> 
       <tr>
        <td><?php echo $particularlist->room_category; ?> </td>
        <?php 
        $get_charges= get_room_charge_according_to_branch($users_data['parent_id']);
            //echo "<pre>";print_r($get_charges); exit; 
            if(!empty($get_charges))
            {
              foreach($get_charges as $charge_type)
              {
                  $room_charges = get_panel_room_charge_accordint_to_id($users_data['parent_id'], $charge_type->id, $particularlist->id, '',$panel_id); 
                  //if(isset($room_charges) && !empty($room_charges))
                 // {
                    $charge = $room_charges[0]['room_charge'];
                   ?>
                   <td><?php echo $charge; ?></td>
                  <?php  
                  //}
              }
            }
            ?>
            </tr>
          
   	    <!-- <tr>
            <td>< ?php echo $particularlist['particular']; ?></td>
            <td>< ?php echo round($particularlist['panel_charge']); ?></td>
      </tr> -->
   	   <?php
   	   $i++;	
   	 }	
   }
   else
   { ?>
          <tr class="append_row"><td class="text-danger" colspan="15"><div class="text-center">No record found</div></td></tr>
      <?php } ?> 
</table>
</body>
</html>