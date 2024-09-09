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
input.w-40px{width:30px;border:none;padding:2px 4px;}
</style>
<div class="modal-dialog">
  <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
  </div>
  <div class="modal-content"> 
    <form method="post" id="bcva_add">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      <div class="modal-body">   
        <div class="row"> 
          <div class="col-md-6">
            <div class="grid-box">
              <h5>Sphere(+)</h5>
              <div class="tbl_responsive">
                <table class="tbl_grid" cellpadding="0" cellspacing="0" border="1" id="sphere_plus">
                <?php
                  $sp_pls_i=0;
                  $sp_pls_j=0;
                  if($sphere_plus_data!="empty")
                  {
                    $a=array();
                    foreach($sphere_plus_data as $key=>$val)
                    {
                        $a[$val->address_i][$val->address_j]=$val->value;
                    }
                    if($sphere_plus_data_last_row!="empty")
                    {
                      $cnt_i=$sphere_plus_data_last_row[0]->address_i;
                      $cnt_j=$sphere_plus_data_last_row[0]->address_j;
                      $sp_pls_i=$cnt_i;
                      $sp_pls_j=$cnt_j;
                      for($i=1;$i<=$cnt_i;$i++)
                      {
                        echo "<tr>";
                        for($j=1;$j<=$cnt_j;$j++)
                        {
                          
                            echo '<td id="sphere_plus_'.$i.'_'.$j.'"><input type="text" class="w-40px" name="sphere_plus['.$i.']['.$j.']" value="'.$a[$i][$j].'" onclick="set_array_value_sph('."'sph'".', this.value);return false;" ></td>';
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
                        echo '<td id="sphere_plus_'.$i.'_'.$j.'" ><input type="text" class="w-40px" name="sphere_plus['.$i.']['.$j.']"></td>';
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
              <h5>Sphere(-)</h5>
              <div class="tbl_responsive">
                <table class="tbl_grid" cellpadding="0" cellspacing="0" border="1" id="sphere_minus">
                <?php
                  if($sphere_minus_data!="empty")
                  {
                    $a=array();
                    foreach($sphere_minus_data as $key=>$val)
                    {
                        $a[$val->address_i][$val->address_j]=$val->value;
                    }
                    if($sphere_minus_data_last_row!="empty")
                    {
                      $cnt_i=$sphere_minus_data_last_row[0]->address_i;
                      $cnt_j=$sphere_minus_data_last_row[0]->address_j;
                      for($i=1;$i<=$cnt_i;$i++)
                      {
                        echo "<tr>";
                        for($j=1;$j<=$cnt_j;$j++)
                        {
                          echo '<td id="sphere_minus_'.$i.'_'.$j.'" ><input type="text" class="w-40px" name="sphere_minus['.$i.']['.$j.']" value="'.$a[$i][$j].'" onclick="set_array_value_sph('."'sph'".', this.value);return false;" ></td>';
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
                        echo '<td id="sphere_minus_'.$i.'_'.$j.'" ><input type="text" class="w-40px" name="sphere_minus['.$i.']['.$j.']"></td>';
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


        <div class="row"> 
          <div class="col-md-6">
            <div class="grid-box">
              <h5>Cylinder(+)</h5>
              <div class="tbl_responsive">
                <table class="tbl_grid" cellpadding="0" cellspacing="0" border="1" id="cylinder_plus">
                <?php
                  if($cylinder_plus_data!="empty")
                  {
                    $a=array();
                    foreach($cylinder_plus_data as $key=>$val)
                    {
                        $a[$val->address_i][$val->address_j]=$val->value;
                    }
                    if($cylinder_plus_data_last_row!="empty")
                    {
                      $cnt_i=$cylinder_plus_data_last_row[0]->address_i;
                      $cnt_j=$cylinder_plus_data_last_row[0]->address_j;
                      for($i=1;$i<=$cnt_i;$i++)
                      {
                        echo "<tr>";
                        for($j=1;$j<=$cnt_j;$j++)
                        {
                          
                            echo '<td id="cylinder_plus_'.$i.'_'.$j.'" ><input type="text" class="w-40px" name="cylinder_plus['.$i.']['.$j.']" value="'.$a[$i][$j].'" onclick="set_array_value_cyl('."'cyl'".', this.value);return false;" ></td>';
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
                        echo '<td id="cylinder_plus_'.$i.'_'.$j.'"  ><input type="text" class="w-40px" name="cylinder_plus['.$i.']['.$j.']"></td>';
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
              <h5>Cylinder(-)</h5>
              <div class="tbl_responsive">
                <table class="tbl_grid" cellpadding="0" cellspacing="0" border="1" id="cylinder_minus">
                <?php
                  if($cylinder_minus_data!="empty")
                  {
                    $a=array();
                    foreach($cylinder_minus_data as $key=>$val)
                    {
                        $a[$val->address_i][$val->address_j]=$val->value;
                    }
                    if($cylinder_minus_data_last_row!="empty")
                    {
                      $cnt_i=$cylinder_minus_data_last_row[0]->address_i;
                      $cnt_j=$cylinder_minus_data_last_row[0]->address_j;
                      for($i=1;$i<=$cnt_i;$i++)
                      {
                        echo "<tr>";
                        for($j=1;$j<=$cnt_j;$j++)
                        {
                          
                            echo '<td id="cylinder_minus_'.$i.'_'.$j.'" ><input type="text" class="w-40px" name="cylinder_minus['.$i.']['.$j.']" value="'.$a[$i][$j].'" onclick="set_array_value_cyl('."'cyl'".', this.value);return false;" ></td>';
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
                        echo '<td id="cylinder_minus_'.$i.'_'.$j.'"  ><input type="text" class="w-40px" name="cylinder_minus['.$i.']['.$j.']"></td>';
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

        <div class="row"> 
          <div class="col-md-6">
            <div class="grid-box">
              <h5>Add</h5>
              <div class="tbl_responsive">
                <table class="tbl_grid" cellpadding="0" cellspacing="0" border="1" id="add">
                <?php
                  if($add_data!="empty")
                  {
                    $a=array();
                    foreach($add_data as $key=>$val)
                    {
                        $a[$val->address_i][$val->address_j]=$val->value;
                    }
                    if($add_data_last_row!="empty")
                    {
                      $cnt_i=$add_data_last_row[0]->address_i;
                      $cnt_j=$add_data_last_row[0]->address_j;
                      for($i=1;$i<=$cnt_i;$i++)
                      {
                        echo "<tr>";
                        for($j=1;$j<=$cnt_j;$j++)
                        {
                          
                            echo '<td id="add_'.$i.'_'.$j.'"  ><input type="text" class="w-40px" name="add['.$i.']['.$j.']" value="'.$a[$i][$j].'" onclick="set_array_value_add('."'add'".', this.value);return false;" ></td>';
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
                        echo '<td id="add_'.$i.'_'.$j.'"  ><input type="text" class="w-40px" name="add['.$i.']['.$j.']"></td>';
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
              <h5>Axis</h5>
              <div class="tbl_responsive">
                <table class="tbl_grid" cellpadding="0" cellspacing="0" border="1" id="axis">
                <?php
                  if($axis_data!="empty")
                  {
                    $a=array();
                    foreach($axis_data as $key=>$val)
                    {
                        $a[$val->address_i][$val->address_j]=$val->value;
                    }
                    if($axis_data_last_row!="empty")
                    {
                      $cnt_i=$axis_data_last_row[0]->address_i;
                      $cnt_j=$axis_data_last_row[0]->address_j;
                      for($i=1;$i<=$cnt_i;$i++)
                      {
                        echo "<tr>";
                        for($j=1;$j<=$cnt_j;$j++)
                        {
                          
                            echo '<td id="axis_'.$i.'_'.$j.'" ><input type="text" class="w-40px" name="axis['.$i.']['.$j.']" value="'.$a[$i][$j].'" onclick="set_array_value_axis('."'axis'".', this.value);return false;" ></td>';
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
                          echo '<td id="axis_'.$i.'_'.$j.'"  ><input type="text" class="w-40px" name="axis['.$i.']['.$j.']"></td>';
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


          <div class="row"> 
          <div class="col-md-6">
            <div class="grid-box">
              <h5>DVA</h5>
              <div class="tbl_responsive">
                <table class="tbl_grid" cellpadding="0" cellspacing="0" border="1" id="dva">
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
                          
                            echo '<td id="dva_'.$i.'_'.$j.'"  ><input type="text" class="w-40px" name="dva['.$i.']['.$j.']" value="'.$a[$i][$j].'" onclick="set_array_value_dva('."'dva'".', this.value);return false;" ></td>';
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
                          echo '<td id="dva_'.$i.'_'.$j.'"  ><input type="text" class="w-40px" name="dva['.$i.']['.$j.']"></td>';
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
                <table class="tbl_grid" cellpadding="0" cellspacing="0" border="1" id="axis">
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
                          echo '<td id="nva_'.$i.'_'.$j.'" ><input type="text" class="w-40px" name="nva['.$i.']['.$j.']" value="'.$a[$i][$j].'" onclick="set_array_value_nva('."'nva'".', this.value);return false;" ></td>';
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
                          echo '<td id="nva_'.$i.'_'.$j.'"  ><input type="text" class="w-40px" name="nva['.$i.']['.$j.']"></td>';
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




   
    <div class="grid-box-3" style="margin-top:30px;width:90%;" >
      <table class="tbl_grid" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td style="border:none;"><h6>SPH(+)</h6></td>
          <!-- <td><input type="text" class="w-40px"></td> -->
            <td style="border:none;padding-top:5px;">
              <button type="button" class="submitBtn" style="margin-left: 12px;margin-right:15px;" onclick="get_last_row('sphere_plus');return false;" >Add</button>
            </td>
       
          <td style="border:none;"><h6>SPH(-)</h6></td>
          <!-- <td><input type="text" class="w-40px"></td> -->
            <td style="border:none;padding-top:5px;">
              <button type="button" class="submitBtn" style="margin-left: 12px;margin-right:15px;" onclick="get_last_row('sphere_minus');return false;" >Add</button>
            </td>
      
          <td style="border:none;"><h6>CYL(+)</h6></td>
          <!-- <td><input type="text" class="w-40px"></td> -->
            <td style="border:none;padding-top:5px;"><button type="submit" class="submitBtn" style="margin-left: 12px;margin-right:15px;" onclick="get_last_row('cylinder_plus');return false;"  >Add</button></td>
       
          <td style="border:none;"><h6>CYL(-)</h6></td>
          <!-- <td><input type="text" class="w-40px"></td> -->
            <td style="border:none;padding-top:5px;"><button type="submit" class="submitBtn" style="margin-left: 12px;margin-right:15px;" onclick="get_last_row('cylinder_minus');return false;"  >Add</button></td>

          <td style="border:none;"><h6>ADD</h6></td>
          <!-- <td><input type="text" class="w-40px"></td> -->
            <td style="border:none;padding-top:5px;"><button type="submit" class="submitBtn" style="margin-left: 12px;margin-right:15px;" onclick="get_last_row('add');return false;"  >Add</button></td>

          <td style="border:none;"><h6>AXIS</h6></td>
          <!-- <td><input type="text" class="w-40px"></td> -->
            <td style="border:none;padding-top:5px;"><button type="submit" class="submitBtn" style="margin-left: 12px;margin-right:15px;" onclick="get_last_row('axis');return false;"  >Add</button></td>
       
         
        </tr>
        
      </table>
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
    <div class="grid-box-3" style="width:30%;margin-right:80px;">
      <table class="tbl_grid" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td style="border:none;"></td>
          <td style="border:none;"><h6>SPH</h6></td>
          <td style="border:none;"><h6>CYL</h6></td>
          <td style="border:none;"><h6>ADD</h6></td>
          <td style="border:none;"><h6>AXIS</h6></td>
          <td style="border:none;"><h6>DVA</h6></td>
          <td style="border:none;"><h6>NVA</h6></td>
        </tr>
        <tr>
          <td style="border:none;">R</td>
          <td><input type="text" class="w-40px" id="sph_right_side" ></td>
          <td><input type="text" class="w-40px" id="cyl_right_side"  ></td>
          <td><input type="text" class="w-40px" id="add_right_side"  ></td>
          <td><input type="text" class="w-40px" id="axis_right_side" ></td>
          <td><input type="text" class="w-40px" id="dva_right_side"  ></td>
          <td><input type="text" class="w-40px" id="nva_right_side"  ></td>
          <td rowspan="2" style="border:none;padding-left:5px;">
            <button type="button" class="submitBtn" onclick="set_all_values_to_prescription();">Add</button>
          </td>
        </tr>
        <tr>
          <td style="border:none;">L</td>
          <td><input type="text" class="w-40px" id="sph_left_side" ></td>
          <td><input type="text" class="w-40px" id="cyl_left_side" ></td>
          <td><input type="text" class="w-40px" id="add_left_side" ></td>
          <td><input type="text" class="w-40px" id="axis_left_side" ></td>
          <td><input type="text" class="w-40px" id="dva_left_side" ></td>
          <td><input type="text" class="w-40px" id="nva_left_side" ></td>
        </tr>
      </table>
    </div>
  </div>
</div>


      <div class="modal-footer"> 
         <input type="button"  class="btn-update" name="submit" value="Save"  onclick="save_data();return false;" />
         <button type="button" class="btn-cancel" data-dismiss="modal" data-number="1">Close</button>
      </div>
    </div>
     </form> 
</div>

<script type="text/javascript">
<?php
$users_data = $this->session->userdata('auth_users');
if(in_array('1385',$users_data['permission']['action'])) 
{
?>
function save_data()
{ 
    var $modal = $('#load_add_type_modal_popup');
  $('#overlay-loader').show();
   $.ajax({
    url: "<?php echo base_url('eye/bcva/'); ?>save",
    type: "post",
    data: $("#bcva_add").serialize(),
    success: function(result) 
    {
        $('#overlay-loader').hide();
        $modal.modal('refresh');
    }
  });
}   

<?php } ?>

function add_sphere_row_cols(i,j,tab_id)
{
  max_i=parseInt(i) +1;
  max_j=parseInt(j) +1;
  var x=0;
  var y=0;
  var p=0;
  var q=0;
  var string="";
  var string1="";
  for(x=parseInt(i)+1;x<max_i+1;x++)
  {
    string+="<tr>";
    for(y=1;y<max_j+1;y++)
    {
      string+='<td id="'+tab_id+'_'+x+'_'+y+'" ><input type="text" class="w-40px" name="'+tab_id+'['+x+']['+y+']" value="" ></td>';
    }
    string+="</tr>";
  }
  $("#"+tab_id).append(string);
  for(p=parseInt(i);p>=1;p--)
  {
    one_les_col=parseInt(max_j)-1;  
    for(q=max_j;q<=max_j;q++)
    {
      string1='<td id="'+tab_id+'_'+p+'_'+q+'"><input type="text" class="w-40px" name="'+tab_id+'['+p+']['+q+']" value="" ></td>'; 
      $( string1 ).insertAfter("#"+tab_id+"_"+p+"_"+one_les_col);
    }
  }
}

function get_last_row(tab_id)
{
  last_row_id=$("#"+tab_id+" tr:last").find("td:last").attr("id");
  var new_id=last_row_id.replace(tab_id+"_","");
  var ids= new_id.split("_");
  i=ids[0];
  j=ids[1];
  add_sphere_row_cols(i,j,tab_id);
}

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
            url: "<?php echo base_url('eye/bcva/set_nva');?>",
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
  else
  {
    alert("Select Right Left Option");
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
            dataType:'json',
            url: "<?php echo base_url('eye/bcva/set_dva');?>",
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
  else
  {
    alert("Select Right Left Option");
  }  
}


function set_array_value_sph(block,value)
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
            dataType:'json',
            url: "<?php echo base_url('eye/bcva/set_sph');?>",
            data: {select_val: select_val, block:block, value:value  },
            success: function(result) 
            {
              if(result.left!=0)
              {
                $("#sph_left_side").val(result.left);
              }
              if(result.right!=0)
              {
                $("#sph_right_side").val(result.right);
              }
            }
          });
  }
}


function set_array_value_cyl(block,value)
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
            dataType:'json',
            url: "<?php echo base_url('eye/bcva/set_cyl');?>",
            data: {select_val: select_val, block:block, value:value  },
            success: function(result) 
            {
              if(result.left!=0)
              {
                $("#cyl_left_side").val(result.left);
              }
              if(result.right!=0)
              {
                $("#cyl_right_side").val(result.right);
              }
            }
          });
  }
}


function set_array_value_add(block,value)
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
            dataType:'json',
            url: "<?php echo base_url('eye/bcva/set_add');?>",
            data: {select_val: select_val, block:block, value:value  },
            success: function(result) 
            {
              if(result.left!=0)
              {
                $("#add_left_side").val(result.left);
              }
              if(result.right!=0)
              {
                $("#add_right_side").val(result.right);
              }
            }
          });
  }
}


function set_array_value_axis(block,value)
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
            dataType:'json',
            url: "<?php echo base_url('eye/bcva/set_axis');?>",
            data: {select_val: select_val, block:block, value:value  },
            success: function(result) 
            {
              if(result.left!=0)
              {
                $("#axis_left_side").val(result.left);
              }
              if(result.right!=0)
              {
                $("#axis_right_side").val(result.right);
              }
            }
          });
  }
}

function set_all_values_to_prescription()
{
  var nva_left=$("#nva_left_side").val();
  var nva_right=$("#nva_right_side").val();
  var dva_left=$("#dva_left_side").val();
  var dva_right=$("#dva_right_side").val();
  var add_right=$("#add_right_side").val();
  var add_left=$("#add_left_side").val();
  var axis_left=$("#axis_left_side").val();
  var axis_right=$("#axis_right_side").val();
  var cyl_right=$("#cyl_right_side").val();
  var cyl_left=$("#cyl_left_side").val();
  var sph_right=$("#sph_right_side").val();
  var sph_left=$("#sph_left_side").val();
  $.ajax({
            type: "POST",
            dataType: 'json',
            url: "<?php echo base_url('eye/bcva/set_values_for_prescription');?>",
            data: {"nva_left":nva_left, "nva_right": nva_right, "dva_left": dva_left, "dva_right":dva_right, "add_left":add_left, "add_right":add_right, 
            "axis_left":axis_left, "axis_right":axis_right, "cyl_left":cyl_left, "cyl_right":cyl_right, "sph_left": sph_left, "sph_right":sph_right  },
            success: function(result) 
            {
              $("#load_add_type_modal_popup").modal('hide');
              if(result.dva_right!="")
              {
                $("#bcva_dva_right").val(result.dva_right);
              }
              if(result.dva_left!="")
              {
                $("#bcva_dva_left").val(result.dva_left);
              }  
              if(result.nva_right!="")
              {
                $("#bcva_nva_right").val(result.nva_right);
              }
              if(result.nva_left!="")
              {
                $("#bcva_nva_left").val(result.nva_left);
              }
              if(result.sph_left!="")
              {
                $("#bcva_sph_left").val(result.sph_left);
              }
              if(result.sph_right!="")
              {
                $("#bcva_sph_right").val(result.sph_right);
              }
              if(result.cyl_left!="")
              {
                $("#bcva_cyl_left").val(result.cyl_left);
              }
              if(result.cyl_right!="")
              {
                $("#bcva_cyl_right").val(result.cyl_right);
              }
              if(result.axis_left!="")
              {
                $("#bcva_axis_left").val(result.axis_left);
              }
              if(result.axis_right!="")
              {
                $("#bcva_axis_right").val(result.axis_right);
              }
              if(result.add_left!="")
              {
                $("#bcva_add_left").val(result.add_left);
              }
              if(result.add_right!="")
              {
                $("#bcva_add_right").val(result.add_right);
              }
            }  
        });
}


</script>
</div>
</div>