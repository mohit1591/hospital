<?php
$users_data = $this->session->userdata('auth_users');

?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">

<?php
if($print_status==1)
{
?>
<script type="text/javascript">
window.print();
//window.onfocus=function(){ window.close();}
</script>
<?php 
}
?>
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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>  

 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.min.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>locales/bootstrap-datetimepicker.fr.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<style>
  *{margin:0;padding:0;box-sizing:border-box;}
  .grid-frame2 {float:left;width:100%;padding:10px;display:flex;flex-wrap: wrap;}
  .grid-frame2 .grid-box{float:left;height:fit-content;border:1px solid #aaa;margin-bottom:10px;padding:5px;}
  .grid-frame2 .grid-box-3{float:left;width:auto;height:fit-content;border:1px solid #fff;margin-bottom:10px;padding:5px;}
  .grid-head {float:left;width:100%;background:lightgray;padding:6px 20px;font-size:14px;text-transform:capitalize;}
  .grid-body {float:left;width:100%;padding:6px 20px;font-size:14px;}
  .tbl_responsive {float:left;width:100%;overflow:auto;}
  .tbl_grid{float:left;width:100%;border-color:#aaa;border-collapse:collapse;display:table;}
  .tbl_grid td{border:1px solid #aaa;font-size:13px;}
  .form-radio {display:block;font-size:13px;padding:4px;}
  .submitBtn {background:#666;color:#FFF;font-size:13px;border:3px solid #666;border-radius:3px;padding:2px 10px;cursor:pointer;}
  input.input-responsive{width:100%;border:none;/*padding:16px 4px;*/outline:0;color:#000;text-align: center;}
  input.w-60px{width:60px;padding:4px;outline:0;}
  .grid-box{width:60%;}
</style> 
</head>

<body>


<div class="container-fluid">
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">

<div class="well" style="float:left;width:100%;">
  <form method="post" id="biometric_form" >
    <input type="hidden" name="patient_id" value="<?php echo $booking_data['patient_id']; ?>" >
    <input type="hidden" name="branch_id" value="<?php echo $booking_data['branch_id']; ?>" >
    <input type="hidden" name="opd_booking_id" value="<?php echo $booking_data['id']; ?>" >
  <div class="grid-frame2">
    <div class="grid-box" style="margin-left:21%;">
      <h5>DVA</h5>
      <div class="tbl_responsive">
        <table class="tbl_grid" cellpadding="0" cellspacing="0" border="1">
          <tr>
            <td rowspan="3" width="50" align="center" valign="bottom">R</td> 
            <td colspan="2" align="center" height="30">UCVA</td> 
            <td colspan="7" align="center" height="30">BCVA</td> 
          </tr>
          <tr>
            <td align="center" height="30">NVA</td>
            <td align="center" height="30">DVA</td>
            <td align="center" height="30">SPH</td>
            <td align="center" height="30">CYL</td>
            <td align="center" height="30">AXIS</td>
            <td align="center" height="30">ADD</td>
            <td align="center" height="30">DVA</td>
            <td align="center" height="30">NVA</td>
          </tr>
          <tr>  
            <td height="30">
            <input type="text" class="input-responsive ucva" name="ucva_nva_right" id="ucva_nva_right" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->ucva_nva_right; } ?>" ></td>
            <td><input type="text" class="input-responsive ucva" name="ucva_dva_right" id="ucva_dva_right" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->ucva_dva_right; } ?>" ></td>
            <td><input type="text" class="input-responsive bcva" name="bcva_sph_right" id="bcva_sph_right" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_sph_right; } ?>" ></td>
            <td><input type="text" class="input-responsive bcva" name="bcva_cyl_right" id="bcva_cyl_right" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_cyl_right; } ?>" ></td>
            <td><input type="text" class="input-responsive bcva" name="bcva_axis_right" id="bcva_axis_right" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_axis_right; } ?>" ></td>
            <td><input type="text" class="input-responsive bcva" name="bcva_add_right" id="bcva_add_right"  value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_add_right; } ?>"></td>
            <td><input type="text" class="input-responsive bcva" name="bcva_dva_right" id="bcva_dva_right"  value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_dva_right; } ?>"></td>
            <td><input type="text" class="input-responsive bcva" name="bcva_nva_right" id="bcva_nva_right" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_nva_right; } ?>" ></td> 
          </tr>
          <tr> 
            <td width="50" height="30" align="center">L</td>
            <td><input type="text" class="input-responsive ucva" name="ucva_nva_left" id="ucva_nva_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->ucva_nva_left; } ?>" ></td>
            <td><input type="text" class="input-responsive ucva" name="ucva_dva_left" id="ucva_dva_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->ucva_dva_left; } ?>" ></td>
            <td><input type="text" class="input-responsive bcva" name="bcva_sph_left" id="bcva_sph_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_sph_left; } ?>"  ></td>
            <td><input type="text" class="input-responsive bcva" name="bcva_cyl_left" id="bcva_cyl_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_cyl_left; } ?>"  ></td>
            <td><input type="text" class="input-responsive bcva" name="bcva_axis_left" id="bcva_axis_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_axis_left; } ?>" ></td>
            <td><input type="text" class="input-responsive bcva" name="bcva_add_left" id="bcva_add_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_add_left; } ?>" ></td>
            <td><input type="text" class="input-responsive bcva" name="bcva_dva_left" id="bcva_dva_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_dva_left; } ?>"  ></td>
            <td><input type="text" class="input-responsive bcva" name="bcva_nva_left" id="bcva_nva_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_nva_left; } ?>" ></td> 
          </tr>
        </table>
      </div>
    </div>
  </div>
  <!-- /// Bottom grids -->
  

  <div class="grid-frame2">

  <?php if($keratometer_data!="empty") { ?>

    <div class="grid-box" style="width:40%;height:260px;border:1px solid #aaa;padding:0px;margin-left:120px;">
      <div class="grid-head">Keratometer Readings</div>
      <div class="grid-body">
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
          <tr>
            <td width="15%" height="30"  align="center">RE</td>
            <td></td>
            <td width="15%" height="30"  align="center">LE</td>
          </tr>

          <?php 
            $i=1;

              foreach($keratometer_data as $data) 
              { 
                $right_val="";
                $left_val="";
                if($keratometer_details!="empty")
                {
                  foreach($keratometer_details as $dt)
                  {
                    if($dt->kera_id==$data->id)
                    {
                      $right_val=$dt->right_eye;
                      $left_val=$dt->left_eye;
                    }
                  } 
                }
              ?>
              <tr>
                <td width="15%"  align="center"><input type="text" name="kera_re[<?php echo $data->id; ?>]" class="w-60px" 
                value="<?php  echo $right_val;   ?>" ></td>
                <td align="center"><?php echo $data->keratometer; ?></td>
                <td width="15%"  align="center"><input type="text" name="kera_le[<?php echo $data->id; ?>]" class="w-60px" value="<?php  echo $left_val;   ?>" ></td>
              </tr>
          <?php $i++; } ?>
        </table>
      </div>
    </div>
    
   <?php } ?> 
    
    
  <?php if($iol_data!="empty") { ?>
    <div class="grid-box" style="width:40%;height:260px;border:1px solid #aaa;padding:0px;margin-left:20px;">
      <div class="grid-head">IOL Section</div>
      <div class="grid-body">
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
          <tr>
            <td width="15%" height="30" align="center">RE</td>
            <td></td>
            <td width="15%" height="30"  align="center">LE</td>
          </tr>
    <?php foreach($iol_data as $iol) 
          { 
            $right_val="";
                $left_val="";
                if($iol_details!="empty")
                {
                  foreach($iol_details as $dt)
                  {
                    if($dt->iol_id==$iol->id)
                    {
                      $right_val=$dt->right_eye;
                      $left_val=$dt->left_eye;
                    }
                  } 
                }
          ?>
          <tr>
            <td width="15%" align="center"><input type="text" name="iol_re[<?php echo $iol->id; ?>]" class="w-60px" value="<?php echo $right_val; ?>" ></td>
            <td align="center"><?php echo $iol->iol_section; ?></td>
            <td width="15%" align="center"><input type="text" name="iol_le[<?php echo $iol->id; ?>]" class="w-60px" value="<?php echo $left_val; ?>" ></td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </div>
  <?php } ?>
  </form>
  </div>
  

</section> <!-- section close -->
</div><!-- container-fluid -->



</body>
</html>