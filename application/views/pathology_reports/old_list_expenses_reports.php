<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title; ?></title>
<meta name="viewport" content="width=1024">


<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>buttons.bootstrap.min.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>



<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>


</head>

<body id="expenses">


<div class="container-fluid">
<table id="table" class="table table-bordered print_path_expenses_reports" cellspacing="0" width="100%">
<thead class="bg-theme">
     <tr>
      
          <th align="left"> Voucher No. </th>
          <th align="left"> Expense Category </th> 
          <th align="left"> Expense Date </th> 
          <th align="left"> Amount </th>  
     </tr>
</thead>  
<tbody>
     <?php 
     if(!empty($expense_list))
     {
        foreach($expense_list as $expenses)
        {
          ?>
          <tr>
               <td><?php echo $expenses->vouchar_no; ?></td>
               <td><?php echo $expenses->exp_category; ?></td>
               <td><?php echo date('d-m-Y H:i A', strtotime($expenses->expenses_date)); ?></td>
               <td><?php echo $expenses->paid_amount; ?></td>
          </tr>
          <?php
        }
     }
     else
     {
      ?>
       <tr>
            <td colspan="4"><div style="text-align:center" class="text-danger">Record not found</div></td>
       </tr>   
      <?php
     }
     ?>           
           


</tbody>

</table> 

</div>
</body>
</html>