<?php 
 if(isset($examination_id) && $examination_id!='')
  {
    $examination_id=$examination_id;
  }
  else
  {
    $examination_id=$this->session->userdata('sess_examin_id');
  }

  if(isset($blood_detail_id) && $blood_detail_id!='')
  {
    $blood_detail_id=$blood_detail_id;
  }
  else
  {
    $blood_detail_id=$this->session->userdata('sess_blood_detail_id');
  }

  if(isset($blood_detail_id) && $blood_detail_id!='')
  {
    $blood_detail_id=$blood_detail_id;
  }
  else
  {
    $blood_detail_id=$this->session->userdata('sess_blood_detail_id');
  }

  if(isset($qc_id) && $qc_id!='')
  {
    $qc_id=$qc_id;
  }
  else
  {
    $qc_id=$this->session->userdata('sess_qc_rec_id');
  }

?>
<form method="post" id="blood_components_form">
  <input type="hidden" name="donor_id" value="<?php echo $donor_id; ?>" id="donor_id">
  <input type="hidden" name="examination_id" value="<?php echo $examination_id; ?>" id="examination_id">
  <input type="hidden" name="collection_time" value="<?php echo $collection_time; ?>" id="collection_time">
  <input type="hidden" name="blood_detail_id" value="<?php echo $blood_detail_id; ?>" id="blood_detail_id">
  <input type="hidden" name="qc_id" id="qc_id" value="<?php echo $qc_id; ?>" >

<?php //print_r($component_data); ?>

<div class="">
  <div class="row mb-5">
     <div class="col-md-2"><b>Bag Type</b></div>
        <div class="col-md-4">
         <!--  <select name="blood_bag_type" id="blood_bag_type" onchange="get_components_for_bag_type(this.value);" >
            <option value="">Select Blood Bag</option> -->
 <input type="hidden" name="bag_type_id" value="<?php echo $blood_details['blood_bag_type_id']; ?>" id="bag_type_id">
            <?php if($component_data!="empty") {  ?>
              <input type="hidden" name="action" value="update" id="action" >
            <?php } else { ?>
                <input type="hidden" name="action" value="add" id="action" >
            <?php } ?>

              <?php
                    if($blood_bags!="empty")
                    {
                      foreach($blood_bags as $bags)
                      {
                          if($blood_details!="empty")
                          {
                              if($blood_details['blood_bag_type_id']==$bags->id)
                              {
                                echo $bags->bag_type;
                              }
                              /*else
                                $bg_select="";*/
                          }
                          else
                          {
                            $bg_select="";
                          }
                        //echo '<option '.$bg_select.' value='.$bags->id.'>'.$bags->bag_type.'</option>';
                      }
                    }
                ?>
          <!-- </select>   -->
        </div> 
  </div>
  <div class="row mb-5">
    <div class="col-md-2"><b>Components for bag</b></div>
      <div class="col-md-11">
        
      </div>
    </div>
    <div class="row mb-5">
    <div class="col-md-0"></div>
      <div class="col-md-12">
      <div id="component_div_id"></div>
      <span id="component_error"></span>
      </div>
    </div>
  
      
</div>
  
  <input type="submit" name="submit" value="Submit" onclick="save_components();return false;" class="btn-update">
  
</form>


<script>



<?php 
if($component_data=="empty"){ 
  ?>
var bag_type_id=<?php echo $blood_details['blood_bag_type_id']; ?>;
var cl_date = <?php echo $blood_details['collection_date'];?>;

var cl_time = '<?php echo $collection_time;?>';
$(document).ready(function() 
{ 
  $.ajax({
            type: "POST",
            url: "<?php echo base_url('blood_bank/donor_examinations/get_components_by_bag_type');?>",
            data: {'bag_type_id':bag_type_id,'component_data':"empty",'collection_date':cl_date,'collection_time':cl_time},
            success: function(result) 
            {

              $("#component_div_id").html(result);
            }
        });
}); 
<?php }  else { ?>
$(document).ready(function() 
{ 
  var bag_type_id=<?php echo $blood_details['blood_bag_type_id']; ?>;
  $.ajax({
            type: "POST",
            url: "<?php echo base_url('blood_bank/donor_examinations/get_components_by_bag_type');?>",
            data: {'bag_type_id':bag_type_id, 'component_data':<?php echo json_encode($component_data); ?>},
            success: function(result) 
            {

              $("#component_div_id").html(result);
            }
        });
});

<?php } ?>


function get_components_for_bag_type(bag_type)
{
  $.ajax({
            type: "POST",
            url: "<?php echo base_url('blood_bank/donor_examinations/get_components_by_bag_type');?>",
            data: {'bag_type_id':bag_type},
            success: function(result) 
            {
             $("#component_div_id").html(result);
            }
        });    
}

function set_datepicker(ref)
{
  $("#"+ref).datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
  }); 

}

function save_components()
{
  //response=validate_row();
  var response=200;
  if(response=="200")
  {

    $.ajax({
          type: "POST",
          url: "<?php echo base_url('blood_bank/donor_examinations/save_blood_components');?>",
          data: $("#blood_components_form").serialize(),
          dataType:'json',
          success: function(result) 
          {
            
            if(result.st==0)
            {
              $("#component_error").html(result.component);
            }
            else if(result.st==1)
            {
              $("#component_error").html('');
                flash_session_msg(result.msg);
              
               tab_navigation('bag_qc',$("#qc_id").val(),'');
              $(".t1").removeClass('active');
              $(".t2").removeClass('active');
              //$(".t3").addClass('active');
              $(".t3").removeClass('active');
              $(".t4").addClass('active');
           
            }
          }
      });
  }

}


function validate_row()
{
  
  $val=1; 
  $( ".cheeck" ).each(function( index ) 
  {
      if($(this).prop('checked')==true)
      {
        row_id=$(this).attr('ct');
        
        if($("#bar_code_id_"+row_id).val()=="")
        {
           $val=0; 
           $("#bar_code_error_"+row_id).html('<br/><font style="color:red;" >Please enter bar code</font>'); 
        }
        else
        {
          $("#bar_code_error_"+row_id).html('');
        }
         if($("#quantity_id_"+row_id).val()=="")
        {
           $val=0; 
           $("#quantity_error_"+row_id).html('<br/><font style="color:red;" >Please enter quantity</font>'); 
        }
        else
        {
          $("#quantity_error_"+row_id).html('');
        }

        if($("#expiry_date_id_"+row_id).val()=="")
        {
          $val=0;
          $("#expiry_date_error_"+row_id).html('<br/><font style="color:red;"> Please enter expiry date</font>'); 
        }
        else
        {
          $("#expiry_date_error_"+row_id).html('');
        }


      }
  });

  if($val==1)
    return "200";
  else
    return "203";
}
$(document).ready(function(){
  //set_blood_expiry();
});
// function set_blood_expiry()
// {
//   $.ajax({
//             type: "POST",
//             url: "<?php //echo base_url('blood_bank/donor_examinations/set_blood_expiry');?>",
//             data: { 'collection_date':'<?php //echo $blood_details['collection_date'];?>'},
//             success: function(result) 
//             {
//               //alert(result);
//               //$("#expiry_date").val(result);
//                //$("#expiry_date").replaceWith(string);
//                $('.expiry_date').val(result);
            
             
//             }
//         });
// }

function clear_values(ref)
{
  var id=$(ref).attr('ct');
  if( $(ref).prop('checked')==false)
  {
    $("#bar_code_id_"+id).val('');
     $("#quantity_id_"+id).val('');
    //$("#expiry_date_id_"+id).val('');
  }

}

function open_text_box(comp_id,td_id)
{
  var qty=  $('#quantity_id_'+td_id).val();
  $('#bardiv_'+td_id+'_'+comp_id).html('');
   $('.ba_div_'+comp_id).html('');
 
  var i;
  for (i = 0; i < qty; i++) 
  {
    $('#bardiv_'+td_id+'_'+comp_id).append("<div class='col-md-0'><input type='text' style='margin-top: 2px;' name='component_details["+td_id+"][bar_code_detail]["+i+"][bar_code]' id='bar_code_id_"+td_id+"' class='w-60px' placeholder='Barcode'/></div>");
  }
     //$('#bardiv_'+td_id+'_'+comp_id).html("<br><input type='text' style='margin-top: 2px;'/>");
  
 
}
</script>