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

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 

</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<style>
  .adBG{background:#2a854f;color:#fff;}
</style>

<?php $qtyoption = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,20,25,30,35,40,45,50);
    $freoption = array(1,2,3,4,5,6,8,10,12,18,24,'SOS');
    $instruct = array('One time a day','Morning afternoon and night','Morning and evening','Only at night', 'In Morning','In Afternoon','In Night','Before food','After Meal','Empty stomach','Every one hour','Every two hour','Every three hour','Every four hour','Every five hour','Every six hour','Five time in a day','SOS','Add a text Box');

    ?>





  <form id="prescription_form" name="prescription_form" action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">

      <div class="userlist">
        <div class="userlist-box">
          
          <div class="row">
            <div class="col-md-4">
              <div class="row">
                <div class="col-xs-6"><label>Medicine Set Name <span class="text-danger">*</span></label></div>
                <div class="col-xs-6">
                  <input type="text" name="set_name" placeholder="Set Name" value="<?php if(!empty($set_data)){ echo $set_data[0]->set_name; }?>" class="form-control" required>
                  <?php if(!empty($form_error)){ echo form_error('set_name'); } ?>
                </div>
              </div>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4"></div>
          </div>
          <div class="row" style="margin-top:20px;">
              <div class="col-lg-12">
                  <table class="table table-bordered text-center" id="medic_set">
                    <thead>
                      <tr>
                        <th class="text-center" width="200">Name</th>
                        <th class="text-center" width="100">Type</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Frequency</th>
                        <th class="text-center">Duration</th>
                        <th class="text-center">Taper</th>
                        <th class="text-center">Eye</th>
                        <th class="text-center">Instruction</th>
                        <th class="text-center"></th>               
                      </tr>
                    </thead>

                    <tbody id="medication_body">
                      <?php 
                      if(!empty($set_data)){

                        foreach ($set_data as $key => $medication) {?>
                          <tr id="appends_<?php echo $key;?>">
                            <td class="text-left" valign="top" style="vertical-align:top;position:relative;">
                              <input id="mname_<?php echo $key;?>" type="text" name="advs[medication][<?php echo $key;?>][mname]" class="medicine_name" value="<?php echo $medication->medicine_name;?>" onkeyup="search_func('<?php echo $key;?>');">
                              <div class="append_box_medi_<?php echo $key;?> advs_append_box">
                              </div>
                              <div class="small label label-danger d-none" id="fill_med_<?php echo $key;?>">Fill Medicine</div> <div class="small label label-info d-none" id="medavailqty_<?php echo $key;?>"></div>
                            </td>
                            <td valign="top" style="vertical-align:top;">
                              <input name="advs[medication][<?php echo $key;?>][mtype]" value="<?php echo $medication->medicine_type;?>" id="mtype_<?php echo $key;?>" type="text" class="">
                            </td>
                            <td valign="top" style="vertical-align:top;">
                              <select name="advs[medication][<?php echo $key;?>][mqty]" onchange="return validate_qty(this.value,'<?php echo $key;?>');" id="mqty_<?php echo $key;?>" class="w-50px">
                                <option value="">Sel</option>
                                <option <?php if($medication->quantity=='1/4'){ echo 'selected'; }?> value="1/4">1/4</option>
                                <option <?php if($medication->quantity=='1/2'){ echo 'selected'; }?> value="1/2">1/2</option>
                                <?php foreach ($qtyoption as $opt) { ?>
                                  <option <?php if($medication->quantity==$opt){ echo 'selected'; }?> value="<?php echo $opt;?>"><?php echo $opt;?></option>
                                <?php } ?>
                              </select>
                              <div class="small label label-danger" id="qterr_<?php echo $key;?>"></div>
                            </td> 
                            <td valign="top" style="vertical-align:top;">
                              <select name="advs[medication][<?php echo $key;?>][mfrq]" id="mfreq_<?php echo $key;?>" class="w-50px">
                                <option value="">Sel</option>
                                <?php foreach ($freoption as $opt) { ?>
                                  <option  <?php if($medication->frequency==$opt){ echo 'selected'; }?> value="<?php echo $opt;?>"><?php echo $opt;?></option>
                                <?php } ?>
                              </select>
                            </td>  
                            <td valign="top" style="vertical-align:top;" class="text-left">
                              <div style="display:flex;justify-content:space-between;">
                                <select name="advs[medication][<?php echo $key;?>][mdur]" id="mdur_<?php echo $key;?>" style="width:49%">
                                  <option value="">Sel</option>
                                  <?php foreach ($qtyoption as $opt) { ?>
                                    <option  <?php if($medication->duration==$opt){ echo 'selected'; }?> value="<?php echo $opt;?>"><?php echo $opt;?></option>
                                  <?php } ?>
                                </select>

                                <select name="advs[medication][<?php echo $key;?>][mdurd]" id="mdurd_<?php echo $key;?>" style="width:49%">
                                  <option value="">Sel</option>
                                  <option  <?php if($medication->duration_unit=='D'){ echo 'selected'; }?> value="D">Days</option>
                                  <option  <?php if($medication->duration_unit=='W'){ echo 'selected'; }?> value="W">Weeks</option>
                                  <option  <?php if($medication->duration_unit=='M'){ echo 'selected'; }?> value="M">Months</option>
                                  <option  <?php if($medication->duration_unit=='F'){ echo 'selected'; }?> value="F">Next followup</option>
                                </select>
                              </div>

                              <div class="small label label-danger d-none" id="fill_dur_<?php echo $key;?>">Fill  Duration</div>
                              <div class="small label label-danger d-none" id="fill_durdw_<?php echo $key;?>">Select Days OR Week</div>
                            </td> 
                            <td valign="top" style="vertical-align:top;padding-top:6px;">
                             <a href="javascript:void(0);" onclick="open_tapper('<?php echo $key;?>','<?php echo $set_id;?>','<?php echo $medication->medicine_id;?>');" class="btn-new"><i class="fa fa-plus"></i> Add</a>` 
                           <!--   <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#new_tapring_set<?php echo $tpkey;?>">Open Modal</button> -->
                            </td>  
                            <td valign="top" style="vertical-align:top;">
                              <select name="advs[medication][<?php echo $key;?>][eside]"  id="meye_<?php echo $key;?>" class="w-50px">
                                <option value="">Sel</option>
                                <option <?php if($medication->eyes=='L'){ echo 'selected'; }?> value="L">L</option>
                                <option <?php if($medication->eyes=='R'){ echo 'selected'; }?> value="R">R</option>
                                <option <?php if($medication->eyes=='BE'){ echo 'selected'; }?> value="BE">BE</option>
                              </select>
                            </td>
                            <td valign="top" style="vertical-align:top;">
                              <select name="advs[medication][<?php echo $key;?>][minst]" id="minst_<?php echo $key;?>" class="w-50px">
                                <option value="">Sel</option>
                                <?php foreach ($instruct as $inst) { ?>
                                  <option <?php if($medication->instrucion==$inst){ echo 'selected'; }?> value="<?php echo $inst;?>"><?php echo $inst;?></option>
                                <?php } ?>
                              </select>
                            </td> 
                            <td valign="top" style="vertical-align:top;padding-top:6px;">
                              <?php if($key==0) { ?>
                                <a href="javascript:void(0);" onclick="append_medicine();" class="btn-new"><i class="fa fa-plus"></i></a>
                              <?php } else if($key>0) { ?>
                                <a href="javascript:void(0);" onclick="remove_medication('<?php echo $key;?>');" class="btn-new"><i class="fa fa-times"></i></a>
                              <?php } ?>

                            </td> 
                            <input type="hidden" id="med_id_<?php echo $key;?>" name="advs[medication][<?php echo $key;?>][med_id]" value='<?php echo $medication->medicine_id;?>' >                 
                          </tr>
                        <?php } } else { ?>

                          <tr>
                            <td class="text-left" valign="top" style="vertical-align:top;position:relative;">
                              <input id="mname_0" type="text" name="advs[medication][0][mname]" class="medicine_name" onkeyup="search_func(0);">
                              <div class="append_box_medi_0 advs_append_box">
                              </div>
                              <div class="small label label-danger d-none" id="fill_med_0">Fill Medicine</div><div class="small label label-info d-none" id="medavailqty_0"></div>
                            </td>
                            <td valign="top" style="vertical-align:top;">
                              <input name="advs[medication][0][mtype]" id="mtype_0" type="text" class="">
                            </td>
                            <td valign="top" style="vertical-align:top;">
                              <select name="advs[medication][0][mqty]" id="mqty_0" onchange="return validate_qty(this.value,'0');" class="w-50px">
                                <option value="">Sel</option>
                                <option value="1/4">1/4</option>
                                <option value="1/2">1/2</option>
                                <?php foreach ($qtyoption as $opt) { ?>
                                  <option value="<?php echo $opt;?>"><?php echo $opt;?></option>
                                <?php } ?>
                              </select>
                              <div class="small label label-danger" id="qterr_0"></div>
                            </td> 
                            <td valign="top" style="vertical-align:top;">
                              <select name="advs[medication][0][mfrq]" id="mfreq_0" class="w-50px">
                                <option value="">Sel</option>
                                <?php foreach ($freoption as $opt) { ?>
                                  <option value="<?php echo $opt;?>"><?php echo $opt;?></option>
                                <?php } ?>
                              </select>
                            </td>  
                            <td valign="top" style="vertical-align:top;" class="text-left">
                              <div style="display:flex;justify-content:space-between;">
                                <select name="advs[medication][0][mdur]" id="mdur_0" style="width:49%">
                                  <option value="">Sel</option>
                                  <?php foreach ($qtyoption as $opt) { ?>
                                    <option value="<?php echo $opt;?>"><?php echo $opt;?></option>
                                  <?php } ?>
                                </select>

                                <select name="advs[medication][0][mdurd]" id="mdurd_0" style="width:49%">
                                  <option value="">Sel</option>
                                  <option value="D">Days</option>
                                  <option value="W">Weeks</option>
                                  <option value="M">Months</option>
                                  <option value="F">Next followup</option>
                                </select>
                              </div>

                              <div class="small label label-danger d-none" id="fill_dur_0">Fill  Duration</div>
                              <div class="small label label-danger d-none" id="fill_durdw_0">Select Days OR Week</div>
                            </td> 
                            <td valign="top" style="vertical-align:top;padding-top:6px;">
                              <a href="javascript:void(0);" onclick="open_tapper(0,'0','0');" class="btn-new"><i class="fa fa-plus"></i> Add</a>
                            </td>  
                            <td valign="top" style="vertical-align:top;">
                              <select name="advs[medication][0][eside]"  id="meye_0" class="w-50px">
                                <option value="">Sel</option>
                                <option value="L">L</option>
                                <option value="R">R</option>
                                <option value="BE">BE</option>
                              </select>
                            </td>
                            <td valign="top" style="vertical-align:top;">
                              <select name="advs[medication][0][minst]" id="minst_0" class="w-50px">
                                <option value="">Sel</option>
                                <?php foreach ($instruct as $inst) { ?>
                                  <option value="<?php echo $inst;?>"><?php echo $inst;?></option>
                                <?php } ?>
                              </select>
                            </td> 
                            <td valign="top" style="vertical-align:top;padding-top:6px;">
                              <a href="javascript:void(0);" onclick="append_medicine();" class="btn-new"><i class="fa fa-plus"></i></a>
                            </td>       
                            <input type="hidden" id="med_id_0" name="advs[medication][0][med_id]">          
                          </tr>
                        <?php } ?>
                      </tbody>
                  </table>
              </div>
            </div>
             
          </div> <!-- userlist-box -->
            <div class="userlist-right">
              <div class="fixed">
                <div class="btns">
                  <button class="btn-save" type="submit" id="form_submit">Save</button>
                  <button type="button" onclick="window.location.href='<?php echo base_url()."medicine_sets"; ?>'" class="btn-save">Exit</button>
                </div>
              </div>
            </div>

            <input type="hidden" name="branch_id" value="<?php echo $users_data['parent_id'];?>">
            <input type="hidden" name="set_id" value="<?php if(!empty($set_id)){ echo $set_id;}?>">

          </div>
        </div>
    </form>


<script>  
  var count=0;
  var count2=0;
  var sddddd=0;
  var sn=0;
  var frequecy=0;
  function append_medicine()
  {
    count=$('#medication_body tr').length;
    $('#medication_body').append('<tr id="appends_'+count+'"><td class="text-left" valign="top" style="vertical-align:top;position:relative;"><input id="mname_'+count+'" type="text" name="advs[medication]['+count+'][mname]" class="medicine_name" onkeyup="search_func('+count+');"><div class="append_box_medi_'+count+' advs_append_box">               </div><small class="label label-danger d-none" id="fill_med_'+count+'">Fill Medicine</small><small class="label label-info d-none" id="medavailqty_'+count+'"></small></td>     <td valign="top" style="vertical-align:top;"><input id="mtype_'+count+'" type="text" name="advs[medication]['+count+'][mtype]" class=""></td><td valign="top" style="vertical-align:top;"><select onchange="return validate_qty(this.value,'+count+');" id="mqty_'+count+'" name="advs[medication]['+count+'][mqty]" class="w-50px"><option value="">Sel</option><option value="1/4">1/4</option><option value="1/2">1/2</option><?php foreach ($qtyoption as $opt) { ?><option value="<?php echo $opt;?>"><?php echo $opt;?></option><?php } ?></select><div class="small label label-danger" id="qterr_'+count+'"></div></td><td valign="top" style="vertical-align:top;"><select name="advs[medication]['+count+'][mfrq]" id="mfreq_'+count+'" class="w-50px"><option value="">Sel</option><?php foreach ($freoption as $opt) { ?><option value="<?php echo $opt;?>"><?php echo $opt;?></option><?php } ?></select></td><td valign="top" style="vertical-align:top;" class="text-left"><div style="display:flex;justify-content:space-between;"><select name="advs[medication]['+count+'][mdur]" id="mdur_'+count+'" style="width:49%"><option value="">Sel</option>  <?php foreach ($qtyoption as $opt) { ?><option value="<?php echo $opt;?>"><?php echo $opt;?></option><?php } ?></select><select name="advs[medication]['+count+'][mdurd]" id="mdurd_'+count+'" style="width:49%"><option value="">Sel</option><option value="D">Days</option><option value="W">Weeks</option><option value="M">Months</option><option value="F">Next followup</option></select></div><div class="small label label-danger d-none" id="fill_dur_'+count+'">Fill  Duration</div><div class="small label label-danger d-none" id="fill_durdw_'+count+'">Select Days OR Week</div></td><td valign="top" style="vertical-align:top;padding-top:6px;">  <a href="javascript:void(0);" onclick="open_tapper('+count+','+0+','+0+');" class="btn-new"><i class="fa fa-plus"></i> Add</a></td><td valign="top" style="vertical-align:top;"><select name="advs[medication]['+count+'][eside]" id="meye_'+count+'" class="w-50px"><option value="">Sel</option><option value="L">L</option><option value="R">R</option><option value="BE">BE</option></select></td><td valign="top" style="vertical-align:top;"><select name="advs[medication]['+count+'][minst]" id="minst_'+count+'" class="w-50px"><option value="">Sel</option><?php foreach ($instruct as $inst) { ?><option value="<?php echo $inst;?>"><?php echo $inst;?></option><?php } ?> </select></td> <td><a href="javascript:void(0);" onclick="remove_medication('+count+');" class="btn-new"><i class="fa fa-times"></i></a></td><input type="hidden" id="med_id_'+count+'" name="advs[medication]['+count+'][med_id]"></tr>');
  }
  function remove_medication(id)
  {
    $('#appends_'+id).remove();
  }
  
  function append_medicine_freq()
  {
    sn++;
    var rowCount = $('#tap_set_body tr').length;
    count2=rowCount;
    sn=rowCount+1;
    $('.del_all').hide();
    $('.del_lst_'+rowCount).show();
    $('#tap_set_body').append('<tr id="med_freq_row_'+count2+'"><td style="width:unset;"><input type="text" name="tp_data['+count2+'][sn]" style="width:60px;" class="form-contorl" value="'+sn+'"></td><td><select name="tp_data['+count2+'][wdays]" class="stedt w-60px dayselect" style="width:60px;" onchange="list_wday(this.value,'+count2+')" id="week_day_'+count2+'"><?php foreach ($qtyoption as $opt) { ?> <option value="<?php echo $opt;?>"><?php echo $opt;?></option><?php } ?></select><input style="width:70px;" name="tp_data['+count2+'][days]" type="number" value="1" class="form-contorl stedtr"  readonly></td><td><input name="tp_data['+count2+'][st_date]" type="text" style="width:85px;" class="form-contorl st_dateo" id="st_dateo_'+count2+'" value=""></td><td class="stedt"><input name="tp_data['+count2+'][en_date]" style="width:85px;" type="text" class="form-contorl en_dateo" style="width:60px;" id="en_dateo_'+count2+'" value=""></td><td><input name="tp_data['+count2+'][st_time]" type="text" style="width:60px;" class="form-contorl datepicker3"></td><td><input name="tp_data['+count2+'][en_time]" style="width:60px;" type="text" class="form-contorl datepicker3"></td><td><input  style="width:60px;" type="number" name="tp_data['+count2+'][freq]" value="'+frequecy+'" class="form-contorl w-100px"></td><td><input type="number" style="width:60px;" name="tp_data['+count2+'][intvl]" class="form-contorl"></td><td class="del_all del_lst_'+count2+'"><a href="#" onclick="remove_medicine_freq('+count2+')" class="btn-custom"><i class="fa fa-times"></i></a></td></tr>');
    getdate(count2);  
    $(".dayselect").val(sddddd);
    frequecy--;
    if(frequecy<=0)
      frequecy=0;

    $('.datepicker3').datetimepicker({
          format: 'LT',
      });
  }
  function remove_medicine_freq(id)
  {
    sn--;
    $('#med_freq_row_'+id).remove();
    $('.del_lst_'+(id-1)).show();
    count2--;
  }

  function search_func(id)
  {  
      var medicine_name = $('#mname_'+id).val();
      $.ajax({
         type: "POST",
         url: "<?php echo base_url('eye/add_eye_prescription/ajax_list_medicine')?>",
         data: {'medicine_name' : medicine_name},
         dataType: "json",
         success: function(msg){
          $(".append_row_opt").remove();
          $(".append_box_medi_"+id).show().html(msg.data);
          $('.append_row_opt').click(function(){
        $('#mname_'+id).val($(this).text());
        $('#mtype_'+id).val($(this).attr('data-type'));
        $('#medavailqty_'+id).show();
        $('#medavailqty_'+id).text('Avail Qty : '+$(this).attr('data-qty'));
        $('#med_id_'+id).val($(this).attr('data-id'));
        $(".append_box_medi_"+id).hide()
      });
         }
      }); 
  }
  function open_tapper(no,set_id,medc_id)
  {
   
    $('#tap_set_body').html('');
    count2=0;
    sn=0;
    var mdur = $('#mdur_'+no).val();
    var mdurd = $('#mdurd_'+no).val();
    var freq = $('#mfreq_'+no).val();

    frequecy=freq;
    if(mdurd=='D')
      sddddd=1;
    else if(mdurd=='W')
      sddddd=7;
    if($('#mname_'+no).val()=='')
      $('#fill_med_'+no).css('display','inline-block');
    else
      $('#fill_med_'+no).hide();
    if(mdur=='' || mdurd=='')
      $('#fill_dur_'+no).css('display','inline-block');
    else if(mdurd=='M' || mdurd=='F'){
      $('#fill_dur_'+no).hide();
      $('#fill_durdw_'+no).css('display','inline-block');
    }
    else
    {
      $('#fill_dur_'+no).hide();
      $('#fill_durdw_'+no).hide();
    }
    if(set_id !='0' && medc_id !='0')
    {
      $('#new_tapring_set').modal('show');
      $('#medicine_nm').val($('#mname_'+no).val());
      $(".med_tp_id").val($('#med_id_'+no).val());
        $.ajax({
         type: "POST",
         url: "<?php echo base_url('medicine_sets/tapper_ajax_list')?>",
         data: {'set_id' : set_id, 'medi_id' : medc_id},
         dataType: "json",
         success: function(msg){
          $('#tap_set_body').html(msg);
         }
      }); 
    }
    else
    {
      $('#new_tapring_set').modal('show');
      $('#medicine_nm').val($('#mname_'+no).val());
      $(".med_tp_id").val($('#med_id_'+no).val());
      for (var i = 0; i < mdur; i++) 
      {
        append_medicine_freq();       
      }
    }
  
  }
  
  function change_days(day)
  {
    $('.st_dateo').val('');
    $('.en_dateo').val('');
    $(".dayselect").val(day);
    sddddd=+(day);
    var rowCount = $('#tap_set_body tr').length;
    for (var i = 0; i < rowCount; i++) {    
           getdate(i);      
    }   
  }

  function list_wday(dayes, num)
  {
    var rowCount = $('#tap_set_body tr').length;
    for (var i = num; i < rowCount; i++) {    
          new_getdate(dayes,i);   
    }
  }

  function getdate(nos) 
  {
    var wdays=sddddd-1;
    if(sddddd==1)
    {
      $('.stedt').hide();
      $('.stedtr').show();
    }
    if(sddddd==7)
    {
      $('.stedt').show();
      $('.stedtr').hide();
    }
    var days=+(sddddd*nos);
      var newdate = new Date();
      newdate.setDate(newdate.getDate() + days);    
      var dd = newdate.getDate();
      var mm = newdate.getMonth() + 1;
      var y = newdate.getFullYear();
      var someFormattedDate = dd + '/' + mm + '/' + y;
      var st=mm + '/' + dd + '/' + y;
      $('#st_dateo_'+nos).val(someFormattedDate);
      var date = new Date(st);
        var newdate1 = new Date(date);
        newdate1.setDate(newdate1.getDate() + wdays);    
      var dd1 = newdate1.getDate();
      var mm1 = newdate1.getMonth() + 1;
      var y1 = newdate1.getFullYear();
      var someFormattedDate1 = dd1 + '/' + mm1 + '/' + y1;
      $('#en_dateo_'+nos).val(someFormattedDate1);
  }   

  function new_getdate(ds,nos) 
  {
      var st=$('#st_dateo_'+nos).val();
      var dayys=$('#week_day_'+nos).val();
      var edays=(+dayys-1)
      var stn=st.split('/');
      var st_dt=stn[1]+'/'+stn[0]+'/'+stn[2];
      var date = new Date(st_dt);
        var newdate1 = new Date(date);
        newdate1.setDate(newdate1.getDate() + (+(edays)));    
      var dd1 = newdate1.getDate();
      var mm1 = newdate1.getMonth() + 1;
      var y1 = newdate1.getFullYear();

      var someFormattedDate1 = dd1 + '/' + mm1 + '/' + y1;
      $('#en_dateo_'+nos).val(someFormattedDate1);

      var someFormattedDate2 = mm1 + '/' +dd1 + '/' + y1;
      var date2 = new Date(someFormattedDate2);
        var newdate2 = new Date(date2);
        newdate2.setDate(newdate2.getDate() + 1)    
      var dd2 = newdate2.getDate();
      var mm2 = newdate2.getMonth() + 1;
      var y2 = newdate2.getFullYear();
      var someFormattedDate3 = dd2 + '/' + mm2 + '/' + y2;
      $('#st_dateo_'+(nos+1)).val(someFormattedDate3);
  } 

  function save_tapper_values()
  {
     $.ajax({
        type: "POST",
              url: "<?php echo base_url();?>medicine_sets/save_tapper_set",
              data: $('#tapper_form').serialize(),
              success:function(result)
              {
                console.log(result);
              }
        });
  }

  function validate_qty(val,id)
  {
    var qty=$('#medavailqty_'+id).text();
    var qty1= qty.split(':');
    qty2=+(val);
    qty3=+(qty1[1])
    if(qty2>qty3)
    {
      $('#qterr_'+id).text('Qty exceeded.');
      $('#mqty_'+id).val('');
    }

  }

  
</script>
</div>
</body>
</html>
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div class="modal fade" id="new_tapring_set">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="tapper_form" name="tapper_form">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4>New Tapring Set</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-2">
              <label for="">Medicine Name</label>
            </div>
            <div class="col-md-3">
              <input type="text" id="medicine_nm" class="form-control">
            </div>
            <div class="col-md-4">
              <div class="stedt">
                No. Of Days : 
                <select class="w-50px dayselect" id="main_week_day" onchange="change_days(this.value);">
                  <?php foreach ($qtyoption as $opt) { ?>
                    <option value="<?php echo $opt;?>"><?php echo $opt;?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <button type="button" data-dismiss="modal" onclick="save_tapper_values();" class="btn-save">Save</button>
            </div>
            <div class="col-md-offset-6 col-md-6 text-danger">
              <span class="text-right">Note: Keep Frequency to 0 if you dont want to taper for that Week.</span>
            </div>
          </div>

          <br>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width:unset;">Sr no.</th>  
                  <th>No of Days</th>
                  <th>Start Date</th>
                  <th class="stedt">End Date</th>
                  <th>Start Time </th>
                  <th>End Time</th>
                  <th>Frequency(Day)</th>
                  <th>Interval(Hour)</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="tap_set_body">

              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <a href="#" onclick="append_medicine_freq();" class="btn btn-sm btn-primary text-center"><i class="fa fa-plus"></i></a>
          <input type="hidden" name="tp_data[0][med_id]" class="med_tp_id"> 

          <input type="hidden" name="branch_id" value="<?php echo $users_data['parent_id']; ?>">
          <input type="hidden" name="set_id" value="<?php if(!empty($set_id)){ echo $set_id;}?>">
          <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>