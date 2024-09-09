<style>
*{margin:0;padding:0;box-sizing:border-box;}
.grid-frame {float:left;width:100%;padding:10px;display:flex;justify-content: space-between;flex-wrap: wrap;}
.grid-box{float:left;width:95%;height:fit-content;border:1px solid #fff;margin-bottom:10px;padding:5px;}
.grid-box-3{float:left;width:auto;height:fit-content;border:1px solid #fff;margin-bottom:10px;padding:5px;}
.tbl_responsive {float:left;width:100%;overflow:auto;}
.tbl_grid{float:left;width:100%;border-color:#aaa;border-collapse:collapse;display:table;}
.tbl_grid td{border:1px solid #aaa;font-size:13px;}
.form-radio {display:block;font-size:13px;padding:4px;}
.submitBtn {background:#666;color:#FFF;font-size:13px;border:3px solid #666;border-radius:3px;padding:2px 10px;cursor:pointer;}
input.w-40px{width:32.7px;border:none;padding:2px 4px;}
</style>
<div class="modal-dialog">
  <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
  </div>
  <div class="modal-content"> 
    <form method="post" id="ucva_add">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      <div class="modal-body">   
        <div class="row"> 
          <div class="col-md-6">
            <div class="grid-box">
              <h5>DVA</h5>
              <div class="tbl_responsive">
                <table class="tbl_grid" cellpadding="0" cellspacing="0" border="1">
                <?php
                  if($dva_data!="empty")
                  {
                    $a=array();
                    foreach($dva_data as $key=>$val)
                    {
                        $a[$val->address_i][$val->address_j]=$val->value;
                    }
                    if($dva_data_last_row!="empty")
                    {
                      $cnt_i=$dva_data_last_row[0]->address_i;
                      $cnt_j=$dva_data_last_row[0]->address_j;
                      for($i=1;$i<=$cnt_i;$i++)
                      {
                        echo "<tr>";
                        for($j=1;$j<=$cnt_j;$j++)
                        {
                          
                            echo '<td><input type="text" class="w-40px" name="dva['.$i.']['.$j.']" value="'.$a[$i][$j].'" onclick="set_array_value_dva('."'dva'".', this.value);return false;" ></td>';
                        }
                      }
                        echo "</tr>";
                    }
                  }
                  else
                  {
                      
                    $i=7;$j=12; 
                    for($i=1;$i<=7;$i++)
                      {
                        echo "<tr>";
                        for($j=1;$j<=12;$j++)
                        {
                          echo '<td><input type="text" class="w-40px" name="dva['.$i.']['.$j.']"></td>';
                        }
                        echo "</tr>";
                      }
                  }
                ?>
                </table>



              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="grid-box">
              <h5>NVA</h5>
              <div class="tbl_responsive">
                <table class="tbl_grid" cellpadding="0" cellspacing="0" border="1">
                <?php
                  if($nva_data!="empty")
                  {
                    $a=array();
                    foreach($nva_data as $key=>$val)
                    {
                        $a[$val->address_i][$val->address_j]=$val->value;
                    }
                    if($nva_data_last_row!="empty")
                    {
                      $cnt_i=$nva_data_last_row[0]->address_i;
                      $cnt_j=$nva_data_last_row[0]->address_j;
                      for($i=1;$i<=$cnt_i;$i++)
                      {
                        echo "<tr>";
                        for($j=1;$j<=$cnt_j;$j++)
                        {
                          
                          echo '<td><input type="text" class="w-40px" name="nva['.$i.']['.$j.']" value="'.$a[$i][$j].'" onclick="set_array_value_nva('."'nva'".', this.value);return false;" ></td>';
                        }
                      }
                        echo "</tr>";
                    }
                  }
                  else
                  {
                      $i=7;$j=12; 
                      for($i=1;$i<=7;$i++)
                      {
                        echo "<tr>";
                        for($j=1;$j<=12;$j++)
                        {
                          echo '<td><input type="text" class="w-40px" name="nva['.$i.']['.$j.']"></td>';
                        }
                        echo "</tr>";
                      }
                  }
                ?>
                </table>
              </div>
            </div>
          </div>

        </div>



    <div class="grid-frame" style="align-items: flex-end;flex-wrap:wrap;">
     <div class="grid-box-3">
      <div class="form-radio">
        <label><input type="radio" class="rlb" name="rlb" value="1" checked="true" /> Right Eye</label>
      </div>
      <div class="form-radio">
        <label><input type="radio" class="rlb" name="rlb" value="2" /> Left Eye</label>
      </div>
      <div class="form-radio">
        <label><input type="radio" class="rlb" name="rlb" value="3" /> Both Eye</label>
      </div>
       <input type="hidden" name="rlb" class="rlb" value="0" >
    </div>
    <div class="grid-box-3" style="width:30%;">
      <table class="tbl_grid" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td style="border:none;"></td>
          <td style="border:none;"><h6>DVA</h6></td>
          <td style="border:none;"><h6>NVA</h6></td>
        </tr>
        <tr>
          <td style="border:none;">R</td>
          <td><input type="text" class="w-40px" id="dva_right_side"></td>
          <td><input type="text" class="w-40px" id="nva_right_side" ></td>
          <td rowspan="2" style="border:none;padding-left:5px;">
            <button type="button" class="submitBtn" onclick="set_value_for_prescription();">Add</button>
          </td>
        </tr>
        <tr>
          <td style="border:none;">L</td>
          <td><input type="text" class="w-40px" id="dva_left_side"></td>
          <td><input type="text" class="w-40px" id="nva_left_side" ></td>
          
        </tr>
      </table>
    </div>
   
  </div>



      <div class="modal-footer"> 
         <input type="button"  class="btn-update" name="submit" value="Save"  onclick="save_data();return false;" />
         <button type="button" class="btn-cancel" data-dismiss="modal" data-number="1">Close</button>
      </div>
    </div>
     </form> 

<script>  


</script>  


<script type="text/javascript">
<?php
$users_data = $this->session->userdata('auth_users');
if(in_array('1378',$users_data['permission']['action'])) 
{
?>
function save_data()
{ 
   var $modal = $('#load_add_type_modal_popup');
  $('#overlay-loader').show();
   $.ajax({
    url: "<?php echo base_url('eye/ucva/'); ?>save",
    type: "post",
    data: $("#ucva_add").serialize(),
    success: function(result) 
    {
        $('#overlay-loader').hide();
        $modal.modal('refresh');
    }
  });
}

<?php } ?>

function set_array_value_nva(block,value)
{
  if( $("input[name='rlb']:checked").val()=="" || $("input[name='rlb']:checked").val()=="undefined" || $("input[name='rlb']:checked").val()==" " || $("input[name='rlb']:checked").val()==null )
  {
    select_val=0;
  }
  else
  {
    select_val=$("input[name='rlb']:checked").val();
  }
  if(select_val!=0)
  {
    $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('eye/ucva/set_nva');?>",
            data: {select_val: select_val, block:block, value:value  },
            success: function(result) 
            {
              if(result.left!=0)
              {
                $("#nva_left_side").val(result.left);
              }
              if(result.right!=0)
              {
                $("#nva_right_side").val(result.right);
              }
            }
          });
  } 
}   

function set_array_value_dva(block,value)
{
 
  if( $("input[name='rlb']:checked").val()=="" || $("input[name='rlb']:checked").val()=="undefined" || $("input[name='rlb']:checked").val()==" " || $("input[name='rlb']:checked").val()==null )
  {
    select_val=0;
  }
  else
  {
    select_val=$("input[name='rlb']:checked").val();
  }
  if(select_val!=0)
  {
    $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('eye/ucva/set_dva');?>",
            data: {select_val: select_val, block:block, value:value  },
            success: function(result) 
            {
              if(result.left!=0)
              {
                $("#dva_left_side").val(result.left);
              }
              if(result.right!=0)
              {
                $("#dva_right_side").val(result.right);
              }
            }
          });
  } 
}

function set_value_for_prescription()
{
  var nva_left=$("#nva_left_side").val();
  var nva_right=$("#nva_right_side").val();
  var dva_left=$("#dva_left_side").val();
  var dva_right=$("#dva_right_side").val();
  $.ajax({
            type: "POST",
            dataType: 'json',
            url: "<?php echo base_url('eye/ucva/set_value_for_prescription');?>",
            data: { "nva_left":nva_left, "nva_right": nva_right, "dva_left": dva_left, "dva_right":dva_right  },
            success: function(result) 
            {
              $("#load_add_type_modal_popup").modal('hide');
              if(result.dva_right!="")
              {
                $("#ucva_dva_right").val(result.dva_right);
              }
              if(result.dva_left!="")
              {
                $("#ucva_dva_left").val(result.dva_left);
              }  
              if(result.nva_right!="")
              {
                $("#ucva_nva_right").val(result.nva_right);
              }
              if(result.nva_left!="")
              {
                $("#ucva_nva_left").val(result.nva_left);
              }
            }  
        });
}
</script>


</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->