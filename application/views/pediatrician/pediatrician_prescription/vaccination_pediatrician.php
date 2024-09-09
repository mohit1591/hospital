<?php $users_data = $this->session->userdata('auth_users'); ?>
<!DOCTYPE html>
<html>
<head><title><?php if($print_type==1){echo ''; }else{echo $page_title.PAGE_TITLE;}?></title>
<meta name="viewport" content="width=1024">


<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<link rel="stylesheet" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-multiselect.css" type="text/css">
<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-multiselect.js"></script>
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('1605',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('pediatrician/Pediatrician_age_chart/ajax_list')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
}); 
<?php } ?>


$(document).ready(function(){
var $modal = $('#load_add_age_vaccination_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'pediatrician/age_vaccination/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_age_vaccination(id)
{
  var $modal = $('#load_add_age_vaccination_modal_popup');
  $modal.load('<?php echo base_url().'pediatrician/age_vaccination/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}


 
function checkboxValues() 
{         
    $('#table').dataTable();
     var allVals = [];
     $(':checkbox').each(function() 
     {
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
     });
     allbranch_delete(allVals);
}

function allbranch_delete(allVals)
 {    
   if(allVals!="")
   {
      $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
        })
        .one('click', '#delete', function(e)
        {
            $.ajax({
                      type: "POST",
                      url: "<?php echo base_url('pediatrician/pediatrician_age_chart/deleteall');?>",
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
        });
   }      
 }
</script>
<?php if(isset($print_type) && $print_type ==1)
 {
  
 }
 else
 {?>

<style>

*{transition:all 0.3s ease;padding:0px;margin:0px;box-sizing:box;}
body,html {font-size:14px;font-family:arial;}
body{padding-right:0px !important;}
a{font-size:12px;}


/*naviagtion*/
.navigation{background:#df8000;}
ul.menu{list-style: none;padding:0 2em;margin:0px;}
ul.menu li{padding:5px 12px;float: left;margin-right: 10px;}
ul.menu li a{color:#fff;text-decoration: none;}
.table-box{margin:2% 0;padding:1em 3em;min-height:450px;}
.table-box thead th{background:#fad099;color:#333;}




.quote-box{padding:10px;margin:2% 0;color:#df8000;font-size:15px;*height:128px;}
.copy-box a{color:#fff;text-decoration: none;}
.copy-box {background: #df8000;padding:5px;}

.theme-color{color:#df8000;}

.font th{text-align:center;}
.table-box tbody td:nth-child(1){background:#fad099;color:#333;text-align: center;}


.table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th{border:1px solid #777;}

ul.chart-menu{list-style: disc;}
/*.blue_td{color:#fff;background:#3595db;}
.yellow_td{color:#333;background: #fdf585;}
.green_td{color:#333;background: #d1e48a;}*/
  
.silders_c
{
  float: left;
  width: 99%;
  overflow: auto;
}
.blue_td{color:#fff;background:#3595db;}
.yellow_td{color:#333;background: #fdf585;}
.green_td{color:#333;background: #d1e48a;}
</style>

 <?php }?>


</head>

<body>


<div class="container-fluid">
 <?php
 if(isset($print_type) && $print_type ==1)
 {

 }
 else
 {
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 }

 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">
      <div class="" style="margin-bottom:1.5em;border:solid 1px #ccc; padding:5px; float:left;width:99%;min-height:100px;*border:1px solid #eee;clear:both;margin-top:1em;">
        <div style="float:left;width:35%;min-height:100px;margin-right:1.5%;">
        <div style="float:left;width:100%;margin-bottom:2px;">
        <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">UHID No.</div>

      <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php if(!empty($opd_booking_data)){echo $opd_booking_data['patient_code'];}?> </div>
      </div>

      <div style="float:left;width:100%;margin-bottom:2px;">
      <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Child Name :</div>

      <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php if(!empty($opd_booking_data)){echo $opd_booking_data['patient_name'];}?></div>
      </div>

      <div style="float:left;width:100%;margin-bottom:2px;">
      <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Gender/Age</div>

      <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php if($opd_booking_data['gender']==0){ echo 'Female'; } ?><?php if($opd_booking_data['gender']==1){ echo 'Male'; } ?>
               <?php if(!empty($opd_booking_data) && $opd_booking_data['age_y']){ echo $opd_booking_data['age_y'].'Y';}?></div>
      </div>

      <div style="float:left;width:100%;margin-bottom:2px;">
      <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Mobile No</div>

      <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php if(!empty($opd_booking_data)){echo $opd_booking_data['mobile_no'];}?></div>
      </div>

      <div style="float:left;width:100%;margin-bottom:2px;">
      <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;"></div>

      <div style="float:left;width:50%;font-size:small;line-height:17px;"></div>
      </div>
      </div>

      <div style="float:right;width:32%;min-height:100px;">
      <div style="float:left;width:100%;margin-bottom:2px;">
      <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">OPD No.</div>

      <div style="float:left;width:50%;font-size:small;line-height:17px;"> <?php if(!empty($opd_booking_data)){echo $opd_booking_data['booking_code'];}?></div>
      </div>

      <div style="float:left;width:100%;margin-bottom:2px;">
      <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Aadhaar No.</div>

      <div style="float:left;width:50%;font-size:small;line-height:17px;"> <?php if(!empty($opd_booking_data) && !empty($opd_booking_data['adhar_no'])){echo $opd_booking_data['adhar_no'];}?></div>
      </div>
      </div>
      </div>
      <!-- row -->



    <div class="<?php if($print_type==1){echo '';}else{ echo 'box_scroll1';} ?>"> 
    <!-- <form> -->
    <input type="hidden" name="booking_id" value="<?php echo $booking_id;?>"/>
    <input type="hidden" name="patient_id" value="<?php echo $patient_id;?>"/>
    <input type="hidden" name="vaccine_date" value="<?php echo $vaccine_date;?>"/>
    <?php if(in_array('1605',$users_data['permission']['action'])) 
    {
       ?>
      
      <table class="table-vaccinessssss"  style="border:1px solid #ddd;" cellspacing="0" width="100%" >
        <thead class="" style="background-color:#fff!important;color:#000">
          <tr>
            <th align="center" style="color:#000;border: 1px solid #777;" ></th>
                     <?php
                     if(!empty($age_list)) 
                     {
                      foreach ($age_list as $age_list_data)
                       {

                      ?>
            <th align="center" id="<?php echo $age_list_data->id ?>" style="border: 1px solid #777;min-width:100px;text-align:center;"><?php echo $age_list_data->title ?></th>

            <?php
              }
                        }
            ?>
            
              
            
          </tr>

          <tr role="row">
            <th align="center" style="color:#000;border: 1px solid #777;">Age /Vaccine</th>
              <?php
              if(!empty($date_age_list)) 
              {
                  foreach ($date_age_list as $date_age_list_n)
                  {

                  ?>
                  <td style="border: 1px solid #777;font-size:11px;padding:4px;" align="center" id="<?php echo $age_list_data->id ?>"><?php if(!empty($date_age_list_n['start_age']) && !empty($date_age_list_n['end_age'])){ echo date('d-m-Y',strtotime($date_age_list_n['start_age'])).' <br>To <br>'.date('d-m-Y',strtotime($date_age_list_n['end_age']));}else{echo date('d-m-Y',strtotime($date_age_list_n['end_age']));} ?></td>

                  <?php
                }
              }
              ?>
            
              
            
          </tr>
        </thead>
          
        <tbody>
        <?php 
        
        foreach($vaccination_list as $vaccination_li)
        {
           $recommend_vaccination_age= get_recommended_age_according_to_vaccine($vaccination_li->id);
          $data_recomend_age=[];
           foreach($recommend_vaccination_age as $recomend_age)
           {
            $data_recomend_age[]=$recomend_age->age_id;
           }

          $immuniation_vaccination_age= get_catchup_immuniation_age_according_to_vaccine($vaccination_li->id);

          $data_immuniation_age=[];
           foreach($immuniation_vaccination_age as $immuniation_age)
           {
            $data_immuniation_age[]=$immuniation_age->age_id;
           }

           $risk_vaccination_age= get_catchup_risk_age_according_to_vaccine($vaccination_li->id);

          $data_risk_age=[];
           foreach($risk_vaccination_age as $risk_age)
           {
            $data_risk_age[]=$risk_age->age_id;
           }
           
           
         ?>
          <tr>
            <td valign="top" style="color:red !important; border: 1px solid #777;"><?php echo $vaccination_li->vaccination_name;?></td>
             <?php
                     if(!empty($age_list)) 
                     {
          foreach ($age_list as $age_list_data)
          {
                $get_age_according_limit= get_age_according_to_limit($age_list_data->start_age_type,$age_list_data->end_age_type,$age_list_data->start_age,$age_list_data->end_age,$age_list_data->title,$vaccine_date);

                

                $yellow_td='';
                $re_vaccination_name='';
                $check_box_input_yellow='';
                $mark_checked='';
                $check_vaccine_already_exists= get_vaccine_already_exits($age_list_data->id,$vaccination_li->id,$booking_id);
             
              if(count($check_vaccine_already_exists)>0)
              {
                $fa_icon='<span style="background-color: #f2f2f2;"><img src='.ROOT_IMAGES_PATH.'Green-check-icon.png></span>';
                $type_input='_'.$check_vaccine_already_exists[0]->id;

                
              }
              else
              {
                $fa_icon='';
                $type_input='';

               
              }

              if(in_array($age_list_data->id,$data_recomend_age))
              {
                $yellow_td="yellow_td";
                $re_vaccination_name=$vaccination_li->vaccination_name;
               
                /* check box part for yellow td */
                $check_vaccine_already_exists= get_vaccine_already_exits($age_list_data->id,$vaccination_li->id,$booking_id);
             
                  if(count($check_vaccine_already_exists)>0)
                  {

                  }
                  else
                  {
                    if(!empty($get_age_according_limit['start_age']) && !empty($get_age_according_limit['end_age']))
                    { 
                      if(strtotime(date('d-m-Y',strtotime($get_age_according_limit['end_age'])))<=strtotime(date('d-m-Y')))
                      {
                       
                         $check_box_input_yellow='<input type="checkbox" value="'.$vaccination_li->id.'.'.'0'.'.'.$age_list_data->id.'" id="'.$vaccination_li->id.'_'.$age_list_data->id.'_'. $type_input.'"/>';
                         
                         $mark_checked='<a style="background-color: #f2f2f2;"  onClick="return mark_done_vaccination('.$age_list_data->id.','.$vaccination_li->id.','.$booking_id.')" href="javascript:void(0)"> Mark</a>';
                      }
                      else
                      {
                        $check_box_input_yellow='';
                      }
                    }else
                    {
                      $check_box_input_yellow='<input type="checkbox" value="'.$vaccination_li->id.'.'.'0'.'.'.$age_list_data->id.'" id="'.$vaccination_li->id.'_'.$age_list_data->id.'_'. $type_input.'"/>';
                      
                      $mark_checked='<a style="background-color: #f2f2f2;"  onClick="return mark_done_vaccination('.$age_list_data->id.','.$vaccination_li->id.','.$booking_id.')" href="javascript:void(0)"> Mark</a>';
                    }
                  }
                /* check box part for yellow td */ 
              }
              else
              {
                $yellow_td='';
                $re_vaccination_name='';
                $check_box_input_yellow='';
              }

              $green_td='';
                $ca_vaccination_name='';
                $check_box_input_green='';
              if(in_array($age_list_data->id,$data_immuniation_age))
              {
                $green_td="green_td";
                $ca_vaccination_name=$vaccination_li->vaccination_name;
                
                /* check box part for green td */
                $check_vaccine_already_exists= get_vaccine_already_exits('',$vaccination_li->id,$booking_id);
             
                if(count($check_vaccine_already_exists)>0)
                {

                }
                else
                {
                  if(!empty($get_age_according_limit['start_age']) && !empty($get_age_according_limit['end_age']))
                    { 
                      if(strtotime(date('d-m-Y',strtotime($get_age_according_limit['end_age'])))<=strtotime(date('d-m-Y')))
                      {
                         $check_box_input_green='<input type="checkbox" value="'.$vaccination_li->id.'.'.'0'.'.'.$age_list_data->id.'" id="'.$vaccination_li->id.'_'.$age_list_data->id.'"/>';
                         
                         //$mark_checked='<a style="background-color: #f2f2f2;"  onClick="return mark_done_vaccination('.$age_list_data->id.','.$vaccination_li->id.','.$booking_id.')" href="javascript:void(0)"> Mark</a>';
                      }
                      else
                      {
                        $check_box_input_green='';
                      }
                    }else
                    {
                      $check_box_input_green='<input type="checkbox" value="'.$vaccination_li->id.'.'.'0'.'.'.$age_list_data->id.'" id="'.$vaccination_li->id.'_'.$age_list_data->id.'_'. $type_input.'"/>';
                      
                      $mark_checked='<a style="background-color: #f2f2f2;"  onClick="return mark_done_vaccination('.$age_list_data->id.','.$vaccination_li->id.','.$booking_id.')" href="javascript:void(0)"> Mark</a>';
                    }
                }
                  /* check box part for green td */ 
              }
              else
              {
                $green_td='';
                $ca_vaccination_name='';
                $check_box_input_green='';
              }

                $blue_td='';
                $ri_vaccination_name='';
                $color_blue_td='';
                $check_box_blue='';
              if(in_array($age_list_data->id,$data_risk_age))
              {
                $blue_td="blue_td";
                $re_vaccination_name='';
                $color_blue_td="color:#fff;";
                $ri_vaccination_name=$vaccination_li->vaccination_name;
                
                /* check box part for blue td */
                $check_vaccine_already_exists= get_vaccine_already_exits($age_list_data->id,$vaccination_li->id,$booking_id);
             
                if(count($check_vaccine_already_exists)>0)
                {

                }
                else
                {
                  if(!empty($get_age_according_limit['start_age']) && !empty($get_age_according_limit['end_age']))
                    { 
                      if(strtotime(date('d-m-Y',strtotime($get_age_according_limit['end_age'])))<=strtotime(date('d-m-Y')))
                      {
                         $check_box_blue='<input type="checkbox" value="'.$vaccination_li->id.'.'.'0'.'.'.$age_list_data->id.'" id="'.$vaccination_li->id.'_'.$age_list_data->id.'_'. $type_input.'"/>';
                         
                         $mark_checked='<a style="background-color: #f2f2f2;"  onClick="return mark_done_vaccination('.$age_list_data->id.','.$vaccination_li->id.','.$booking_id.')" href="javascript:void(0)"> Mark</a>';
                      }
                      else
                      {
                        $check_box_blue='';
                      }
                    }else
                    {
                      $check_box_blue='<input type="checkbox" value="'.$vaccination_li->id.'.'.'0'.'.'.$age_list_data->id.'" id="'.$vaccination_li->id.'_'.$age_list_data->id.'_'. $type_input.'"/>';
                      
                      $mark_checked='<a style="background-color: #f2f2f2;"  onClick="return mark_done_vaccination('.$age_list_data->id.','.$vaccination_li->id.','.$booking_id.')" href="javascript:void(0)"> Mark</a>';


                    }
                }
                  /* check box part for blue td */ 
              }
              else
              {
                $blue_td='';
                $color_blue_td='';
                $check_box_blue='';
                $ri_vaccination_name='';
              }

             

              
          ?>
          <input type="hidden" id="data_all_ids" name="" value="<?php echo $vaccination_li->id;?>_<?php echo $age_list_data->id;?><?php echo $type_input;?>"/>
          <td valign="top"  class="<?php echo $yellow_td;?> <?php echo $green_td;?> <?php echo $blue_td;?>" style="border: 1px solid #777;"><a href="#" style="<?php echo $color_blue_td; ?>" class="w-40px" id="" value="<?php echo $vaccination_li->id;?>">
              
              <input type="hidden" name="vaccination_id" id="vaccination_id" class="vaccination_id" value="<?php echo $vaccination_li->id;?>"/>
              <?php echo $check_box_input_green; ?><?php echo $check_box_input_yellow; ?><?php echo $check_box_blue; ?><?php echo $re_vaccination_name;?> <?php echo $ri_vaccination_name;?> <?php echo $fa_icon; echo $mark_checked; ?>  
              <span id="icon_id_<?php echo $vaccination_li->id;?>_<?php echo $age_list_data->id;?>"></span><input id="span_id_<?php echo $vaccination_li->id;?>_<?php echo $age_list_data->id;?>" type="hidden" name="span_id" value="<?php echo $vaccination_li->id;?>_<?php echo $age_list_data->id;?>"></a>
          <div class="m_alert_orange" style="font-size: 10px;"><?php if(isset($check_vaccine_already_exists[0]->vaccination_date_time) && strtotime($check_vaccine_already_exists[0]->vaccination_date_time)>86400){ echo date('d-m-Y H:i:s',strtotime($check_vaccine_already_exists[0]->vaccination_date_time));}?></div>
          

            </td>
          <?php 
          } 

          }?>
          </tr>
        <?php } ?>

        </tbody>
      </table>
      
        <?php } ?>
    <!-- </form> -->
<!-- **************** -->


    <!-- **************** -->
       </div>
       <div style="border: 1px solid #ccc; float: right; padding:5px; font-weight: bold;">
        <table align="right" cellpadding="0" cellspacing="0" width="400px">
           <tbody><tr>
               <td class="orange"><div class="orange" style="background: #FDF585;border:1px solid #888;width:15px!important;height:15px!important;"></div></td>
               <td>Range of recommended ages for all children</td>
           </tr>
           <tr>
               <td class="orange1"><div class="m_alert_orange_mark orange1" style="background: #D1E48A none repeat scroll 0% 0%;border:1px solid #888"></div></td>
               <td>Range of recommended ages for catchup-immunization</td>
           </tr>
            <tr>
               <td class="orange2"><div class="m_alert_orange_mark" style="background: #3595DB none repeat scroll 0% 0%;border:1px solid #888"></div></td>
               <td>Range of recommended ages for certain high-risk group</td>
           </tr>
           <tr>
               <td class="orange3"><div class="m_alert_orange_mark" style="background: #fff none repeat scroll 0% 0%;border:1px solid #888"></div></td>
               <td>Not routinely recommended</td>
           </tr>
            
        </tbody></table>
       </div>
   </div> <!-- close -->




 <?php
 if(isset($print_type) && $print_type ==1)
 {
 
 }
 else
 {?>
   <div class="userlist-right">
        <div class="btns">
            
        
          <button class="btn-update" onclick="window.location.href='<?php echo base_url('opd'); ?>'">
            <i class="fa fa-sign-out"></i> Exit
          </button>
          <button class="btn-update history_vaccine_pres">
            <i class="fa fa-sign-out"></i> History
          </button>
           <button class="btn-update" onClick="return send_vacc_pre_chart_report('<?php echo $booking_id; ?>','<?php echo $vaccine_date ?>','<?php echo $patient_id; ?>','send');">
                <i class="fa fa-envelope"></i> Email
           </button>  

           <button  class="btn-update m-b-2"  onClick="return print_window_page('<?php echo base_url("pediatrician/pediatrician_prescription/pdf_vaccination_details/$booking_id/$vaccine_date/$patient_id/1"); ?>');"> <i class="fa fa-print"></i>Chart Print</button>

          <button class="btn-update" onclick="load_billing_model()">
                         <i class="fa fa-refresh"></i> Billing
                    </button>
        </div>
      </div> 
<?php }?>
   
    <!-- right -->
 
  <!-- cbranch-rslt close -->
 
</section> <!-- cbranch -->
<?php
if(isset($print_type) && $print_type==1)
 {

 }
 else
 {
  $this->load->view('include/footer');
 }

?>

<script>  
function load_billing_model()
{
     $('#table').dataTable();
    

     var allVals = [];
     var batchNo= [];
     var agevals=[];
     $(':checkbox').each(function() 
     {
          if($(this).prop('checked')==true)
          {

               allVals.push($(this).val());
               
              var v_id = $(this).attr('id');

              var n_v_id= v_id.split("_");
              agevals.push(n_v_id[1]);

              if(n_v_id[2]== undefined){
              var edit_id='';
              var get_span=$('#span_id_'+v_id).val();
              }
              else
              {
              var edit_id= n_v_id[2];
              var get_span='';
              }

          
              // batchNo.push($(this).attr('name'));
          

          } 
            
            
          

     });
     var $modal = $('#load_billing_to_branch_modal_popup');
     $modal.load('<?php echo base_url('pediatrician/pediatrician_prescription/billing_vaccine/'); ?>',{'vaccine_ids':allVals,'batch_no':0,'booking_id':'<?php echo $booking_id;?>','patient_id':'<?php echo $patient_id; ?>','age_id':agevals},function(){
          $modal.modal('show');
     });

   
}
function send_vacc_pre_chart_report(booking_id,vaccination_date,patient_id,type)
  {
   
    var $modal = $('#load_add_vac_pres_modal_popup');
    $modal.load('<?php echo base_url().'pediatrician/pediatrician_prescription/send_email/' ?>'+booking_id+"/"+vaccination_date+'/'+patient_id+'/'+type,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }

$(document).on('click', '.date_select', function (event) {
 var v_id = $(this).attr('id');
    var n_v_id= v_id.split("_");
   
    //alert(n_v_id);
    if(n_v_id[2]== undefined){
      var edit_id='';
      var get_span=$('#span_id_'+v_id).val();
      //$('#span_id_'+v_id).val();
      //$('#span_id_'+n_v_id[2]).val();
      //ask shalini//
      //$('#span_id_'+n_v_id[2]).val();

    }
    else
    {
      var edit_id= n_v_id[2];
       var get_span='0';
    }
  
  var $modal = $('#load_add_pediatrician_modal_popup');
$modal.load('<?php echo base_url().'pediatrician/pediatrician_prescription/save_date_vaccine' ?>/'+n_v_id[0]+'/'+n_v_id[1]+'/'+'<?php echo $booking_id;?>'+'/'+'<?php echo $patient_id; ?>'+'/'+'<?php echo $vaccine_date; ?>'+'/'+get_span+'/'+edit_id,
{
  
},
function(){
$modal.modal('show');
});
  });

$(document).on('click', '.history_vaccine_pres', function (event) {
  var $modal = $('#load_add_vaccine_history_modal_popup');
        $modal.load('<?php echo base_url().'pediatrician/pediatrician_prescription/history_vaccine_pres' ?>/'+'<?php echo $booking_id;?>',
      {
      },
      function(){
        $modal.modal('show');
      });
});


$('#selectAll').on('click', function () { 
  if ($(this).hasClass('allChecked')) {
      $('.checklist').prop('checked', false);
  } else {
      $('.checklist').prop('checked', true);
  }
  $(this).toggleClass('allChecked');
});
 function delete_age_vaccine(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('pediatrician/age_vaccination/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 
 function mark_done_vaccinationsss(age_id,vaccine_id,booking_id)
 {
     var patient_id ='<?php echo $patient_id; ?>';
     var attended_doctor = '<?php echo $attended_doctor; ?>';
     var msg = 'Marked successfully.';
     $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                type: "POST",
                url: "<?php echo base_url('pediatrician/pediatrician_prescription/mark_vaccination/'); ?>", 
                dataType: "json",
                data: 'age_id='+age_id+'&vaccine_id='+vaccine_id+'&booking_id='+booking_id+'&patient_id='+patient_id+'&attended_doctor='+attended_doctor,
                success: function(result)
                 {
                    flash_session_msg(msg);
                    return window.location.reload(true);
                 }
              });
    });     
 }


function mark_done_vaccination(age_id,vaccine_id,booking_id)
 {
     var patient_id ='<?php echo $patient_id; ?>';
     var attended_doctor = '<?php echo $attended_doctor; ?>';
     
     $.ajax({
         url: "<?php echo base_url('pediatrician/pediatrician_prescription/mark_vaccination_date'); ?>/"+age_id+"/"+vaccine_id+"/"+booking_id+"/"+patient_id+"/"+attended_doctor,
        success: function(output){
          
          $('#load_marked_modal_popup').html(output).modal('show');
        }
       
    });
 }


</script> 
<!-- Confirmation Box -->
<?php
if(isset($print_type) && $print_type==1)
 {

 }
 else
 {?>
  <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>


 <?php }?>

   <!-- modal -->
<div id="load_marked_modal_popup" class="modal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!-- Confirmation Box end -->

<div id="load_add_pediatrician_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_add_vaccine_history_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_billing_n_to_branch_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_vac_pres_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_billing_to_branch_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div> <!-- container-fluid -->
</body>
</html>