<!DOCTYPE html>
<html>
<head>
<?php
$users_data = $this->session->userdata('auth_users'); 
//$field_list = mandatory_section_field_list(4);
$field_list = mandatory_section_field_list(7);

?>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"> 
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
    <script type="text/javascript">
    var save_method; 
    var table; 
    $(document).ready(function() { 
        table = $('#test_data').DataTable({  
            "processing": true, 
            "serverSide": true,
            
            "sScrollY": "350px",
            "bScrollCollapse": true,

            "order": [], 
            "pageLength": '20',
            "ajax": {
                "url": "<?php echo base_url('test_master/test_ajax_list/'.$form_data['data_id'])?>",
                "type": "POST",
                "data":function(d){
                              d.branch_id =  '<?php echo $users_data["parent_id"]; ?>';
                              d.dept_id =  $('#dept_id').val();
                              d.test_head =  $("#test_heads_id :selected").val();
                              return d;
                        }
            },
            "columnDefs": [
                            { 
                                "targets": [ 0 , -1 ], //last column
                                "orderable": false, //set not orderable

                            },
                          ],
        });
    }); 

    function reload_table()
    {
        table.ajax.reload(null,false); //reload datatable ajax 
    }
    $(function () {
    var getData = function (request, response) {
        $.getJSON(
            "<?php echo base_url('test_master/get_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $("#default_vals").val(ui.item.value);
        return false;
    }

    $("#default_vals").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
    });

    $(document).ready(function(){
        var $modal = $('#load_add_test_heads_modal_popup');
        var $modals = $('#load_add_test_method_modal_popup');
        var $modal_sample_type = $('#load_add_test_sample_type_modal_popup');

        $('#modal_add_heads').on('click', function(){
        $modal.load('<?php echo base_url().'test_heads/add/' ?>',
        {
          //'id1': '1',
          //'id2': '2'
          },
        function(){
        $modal.modal('show');
        });

        });
         $('#modal_add_sample_type').on('click', function(){
        $modal_sample_type.load('<?php echo base_url().'test_sample_type/add/' ?>',
        {
          //'id1': '1',
          //'id2': '2'
          },
        function(){
        $modal_sample_type.modal('show');
        });

        });
        
         
           $('#modal_add_method').on('click', function(){ 
            $modals.load('<?php echo base_url().'test_method/add/' ?>',
            {
              //'id1': '1',
              //'id2': '2'
              },
            function(){
            $modals.modal('show');
            }); 
        });   

    });

    function isNumberKey(evt) 
    {
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      } else {
          return true;
      }      
    }

    function allow_con_result(event) {
        var regex = new RegExp("^[0-9\.\!<>=!]"); //^[0-9\.\b]+$
        var key = String.fromCharCode(event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    }      
   
</script> 
</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
 
<!-- ============================= Main content start here ===================================== -->
<form id="test_form" name="test_form" method="post" accept="<?php echo base_url('test_master/add'); ?>">
<input type="hidden" name="data_id" value="<?php echo $form_data['data_id']; ?>">
<section class="userlist">
    <div class="test-master">
        <div class="col">
            
                <!-- ======================================================= -->
                <div class="row m-b-5">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>Department <span class="star">*</span></label>
                                    </div>
                                    <div class="col-xs-8">
                                        <select name="dept_id" class="m_input_default" id="dept_id" onchange="return get_heads(this.value);">
                                            <option value="">Select Department</option>
                                              <?php
                                               if(!empty($dept_list))
                                               {
                                                  foreach($dept_list as $dept)
                                                  {
                                                      $dept_select = "";
                                                      if($dept->id==$form_data['dept_id'])
                                                      {
                                                          $dept_select = "selected='selected'";
                                                      }
                                                      echo '<option value="'.$dept->id.'" '.$dept_select.'>'.$dept->department.'</option>';
                                                  }
                                               }
                                              ?>
                                        </select>
                                        <?php
                                        if(!empty($form_error)){ echo form_error('dept_id'); }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>Test head <span class="star">*</span></label>
                                    </div>
                                    <div class="col-xs-8">
                                        <select name="test_head_id" class="w-150px m_select_btn" id="test_heads_id" onchange="set_test_head(this.value);">
                                          <option value="">Select Test Head</option> 
                                          <?php
                                           if(!empty($form_data['dept_id']))
                                           {
                                              $test_heads_list = test_heads_list($form_data['dept_id']);
                                              if(!empty($test_heads_list))
                                              {
                                                  foreach($test_heads_list as $test_heads)
                                                  {
                                                      $select_heads = "";
                                                      if($test_heads->id==$form_data['test_head_id'])
                                                      {
                                                        $select_heads = 'selected="selected"';
                                                      }
                                                      echo '<option '.$select_heads.' value="'.$test_heads->id.'">'.$test_heads->test_heads.'</option>';
                                                  }
                                              }
                                           }
                                          ?>
                                      </select>
                                        <?php if(in_array('828',$users_data['permission']['action'])):?>
                                            <a href="javascript:void(0)" class="btn-new"  id="modal_add_heads"><i class="fa fa-plus"></i>  New</a>
                                       <?php endif;?>
                                       <?php if(!empty($form_error)){ echo form_error('test_head_id'); } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- row -->
                <div class="row m-b-5">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-sm-6">
                                
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>test name <span class="star">*</span></label>
                                    </div>
                                    <div class="col-xs-8">
                                        <input type="text"  name="test_name" class="m_input_default" value="<?php echo $form_data['test_name']; ?>" autofocus>
                                        <?php if(!empty($form_error)){ echo form_error('test_name'); } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>test code <span class="star">*</span></label>
                                    </div>
                                    <div class="col-xs-8"><b>
                                       <div class="m_input_default"><?php echo $form_data['test_code'];  ?></div>
                                    </b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- row -->





                <div class="row m-b-5">
                    <div class="col-xs-12">
                        <div class="row">
                            
                            <div class="col-sm-6">
                                
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>Patient Rate <span class="star">*</span></label>
                                    </div>
                                    <div class="col-xs-8">
                                       <input type="text" name="rate" class="price_float m_input_default" value="<?php echo $form_data['rate']; ?>"> 
                                      <?php 
                                                  if(!empty($form_error)){ echo form_error('rate'); } 
                                              
                                          ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>branch rate <?php if(!empty($field_list)){
                                           if(isset($field_list[2]) && $field_list[2]['mandatory_field_id']==35 && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){?>         
                                                          <span class="star">*</span>
                                                     <?php 
                                                     }
                                                } 
                                                ?></label>
                                    </div>
                                    <div class="col-xs-8">
                                        <input type="text" name="base_rate" class="price_float m_input_default" value="<?php echo $form_data['base_rate']; ?>"> 
                                        <?php if(!empty($field_list)){
                                        if(isset($field_list[2]) && $field_list[2]['mandatory_field_id']=='35' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){
                                                    if(!empty($form_error)){ echo form_error('base_rate'); }
                                                  }
                                                }
                                            ?> 
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div> <!-- row -->
                <div class="row m-b-5">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-sm-6">
                                
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>Default value 
                                         <?php if(!empty($field_list)){
                                          if($field_list[0]['mandatory_field_id']==33 && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){?>         
                                        <span class="star">*</span>
                                              <?php 
                                              }
                                            }
                                          ?>
                                        </label>
                                    </div>
                                    <div class="col-xs-8">
                                        <input type="text" class="m_input_default" id="default_vals" name="default_value" value="<?php echo $form_data['default_value']; ?>">
                                         <?php if(!empty($field_list)){
                                          if($field_list[0]['mandatory_field_id']=='33' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                                              if(!empty($form_error)){ echo form_error('default_value');} 
                                              }
                                          }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>Units  
                                          <?php if(!empty($field_list)){
                                            if(isset($field_list[3]) && $field_list[3]['mandatory_field_id']==36 && $field_list[3]['mandatory_branch_id']==$users_data['parent_id']){?>         <span class="star">*</span>
                                                      <?php 
                                                      }
                                                 }
                                                ?>
                                        </label>
                                    </div>
                                    <div class="col-xs-8">
                                        <select name="unit_id" id="unit_id" class="w-150px m_select_btn">
                                            <option value="">Select Unit</option>
                                            <?php
                                            if(!empty($unit_list))
                                            {
                                                foreach($unit_list as $unit)
                                                {
                                                    $select_unit = "";
                                                    if($unit->id==$form_data['unit_id'])
                                                    {
                                                        $select_unit = 'selected="selected"'; 
                                                    }
                                                    echo '<option '.$select_unit.' value="'.$unit->id.'">'.$unit->unit.'</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                         <?php if(in_array('849',$users_data['permission']['action'])): ?>
                                            <a href="javascript:void(0)" class="btn-new" onclick="add_unit();"><i class="fa fa-plus"></i>  New</a>
                                       <?php endif;?>

                                       <?php if(!empty($field_list)){
                                         if(isset($field_list[3]) && $field_list[3]['mandatory_field_id']=='36' && $field_list[3]['mandatory_branch_id']==$users_data['parent_id']){
                                              if(!empty($form_error)){ echo form_error('unit_id'); }
                                            }
                                          }
                                        ?>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div> <!-- row -->






                <div class="row m-b-5">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>Range from</label>
                                    </div>
                                     <div class="col-xs-8">
                                        <input type="text" class="m_input_default w-64px" name="range_from_pre"  value="<?php echo $form_data['range_from_pre']; ?>" placeholder="Prefix">
                                        <input type="text" class="m_input_default w-64px" name="range_from"  value="<?php echo $form_data['range_from']; ?>" placeholder="Value">
                                        <input type="text" class="m_input_default w-64px" name="range_from_post"  placeholder="Suffix" value="<?php echo $form_data['range_from_post']; ?>">
										
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>Range to  </label>
                                    </div>
                                   <div class="col-xs-8">
                                        <input type="text" class="input-tiny" placeholder="Prefix" name="range_to_pre" value="<?php echo $form_data['range_to_pre']; ?>" class="w-114px">
                                        <input type="text" class="input-tiny" placeholder="Value" name="range_to" value="<?php echo $form_data['range_to']; ?>" class="w-114px">
                                        <input type="text" class="input-tiny" placeholder="Suffix" name="range_to_post" value="<?php echo $form_data['range_to_post']; ?>" class="w-114px">
                                        <a href="javascript:void(0)" class="btn-new px-5" id="advance_range"><i class="fa fa-cube"></i> Adv.</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- row -->
                <div class="row m-b-5">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>Method 
                                          <?php if(!empty($field_list)){
                                            if($field_list[1]['mandatory_field_id']==34 && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){?>         
                                                    <span class="star">*</span>
                                            <?php 
                                            }
                                          }
                                         ?>
                                        </label>
                                    </div>
                                    <div class="col-xs-8">
                                        <select name="method_id" id="test_method_id" class="w-150px">
                                         <option value="">Select Method</option>
                                            <?php
                                                if(!empty($method_list))
                                                {
                                                    foreach($method_list as $method)
                                                    {
                                                        $select_method = "";
                                                        if($method->id==$form_data['method_id'])
                                                        {
                                                            $select_method = 'selected="selected"';
                                                        }
                                                        echo '<option '.$select_method.' value="'.$method->id.'">'.$method->test_method.'</option>';
                                                    }
                                                }
                                                ?>
                                        </select>
                                         <?php if(in_array('835',$users_data['permission']['action'])):?>
                                          <a href="javascript:void(0)" class="btn-new" id="modal_add_method"><i class="fa fa-plus"></i>  New</a>
                                        <?php endif;?>

                                       <?php if(!empty($field_list)){
                                          if(isset($field_list[1]) && $field_list[1]['mandatory_field_id']=='34' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){
                                                          if(!empty($form_error)){ echo form_error('method_id'); }
                                                      }
                                                  }
                                          ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                           // $branch_attribute = get_permission_attr(1,2);  
                            //if(in_array('1',$users_data['permission']['section']) && in_array('2',$users_data['permission']['action']) && $branch_attribute>0)
                            //if(in_array('1',$users_data['permission']['section']) && in_array('2',$users_data['permission']['action']))
                            //{
                            ?> 
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>test under</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <div class="formula_box w-200px" id="test_under_box">
                                           <?php
                                            if(!empty($form_data['test_under']))
                                            {
                                                $exp_test_under = explode(',', $form_data['test_under']);
                                                foreach($exp_test_under as $exp_under)
                                                {
                                                    if($exp_under>0)
                                                    {
                                                       $test_data = get_test($exp_under); 
                                                    echo '<span class="mini_box" meta="'.$exp_under.'">'.$test_data->test_code.'</span>';
                                                    } 
                                                }
                                            }
                                           ?>
                                        <div class="tu">
                                          <input type="hidden" name="test_under" id="test_under" value="<?php echo $form_data['test_under']; ?>"> 
                                          <a href="javascript:void(0);"  class="btn-dmas btn-dmas-cancel" onclick="back_under();">&#8626;</a>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                           // } 
                           // else
                           // {
                             ?>
                             <!--<input type="hidden" name="base_rate" value="<?php //echo $form_data['base_rate']; ?>"> -->
                             <?php  
                           // }
                            ?>
                        </div>
                    </div>
                </div> <!-- row -->
                <div class="row m-b-5">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row m-b-5">
                                    <div class="col-xs-4">
                                        <label>sample type </label>
                                    </div>
                                    <div class="col-xs-8">
                                        <select  id="sample_type_id" name="sample_type_id" class="w-150px">
                                            <option value="">Select Sample Type</option>
                                            <?php
                                            if(!empty($sample_type_list))
                                            {
                                                foreach($sample_type_list as $sample_type)
                                                {
                                                    $select_sample_type = "";
                                                    if($sample_type->id==$form_data['sample_type_id'])
                                                    {
                                                        $select_sample_type = 'selected="selected"';
                                                    }
                                                    echo '<option '.$select_sample_type.' value="'.$sample_type->id.'">'.$sample_type->sample_type.'</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                         <a href="javascript:void(0)" class="btn-new" id="modal_add_sample_type"><i class="fa fa-plus"></i>   New</a>
                                    </div>
                                </div> <!-- row -->

                                <div class="row m-b-5">
                                    <div class="col-xs-4">
                                        <label>test type  </label>
                                    </div>
                                    <div class="col-xs-8">
                                       <select name="test_type_id" class="m_input_default" id="test_type_id">
                                            <option value="0" <?php if($form_data['test_type_id']==0){ echo 'selected="selected"'; } ?> >Normal</option>
                                            <option value="1" <?php if($form_data['test_type_id']==1){ echo 'selected="selected"'; } ?>>Heading</option>
                                        </select>
                                        <?php if(!empty($form_error)){ echo form_error('test_type_id'); } ?>
                                    </div>
                                </div><!-- row -->

                                <div class="row m-b-2">
                                    <div class="col-xs-4">
                                        <label>Formula  </label>
                                    </div>
                                    <div class="col-xs-8">
                                        <div class="formula_box w-200px" id="formula_box">
                                            <?php
                                                if(!empty($form_data['formula']))
                                                {
                                                    $exp_formula = explode(',', $form_data['formula']);
                                                    foreach($exp_formula as $exp_forml)
                                                    {
                                                        if(!empty($exp_forml))
                                                        { 
                                                            if(is_numeric($exp_forml))
                                                            {
                                                               $test_data = get_test($exp_forml);
                                                               echo '<span class="mini_box" meta="'.$exp_forml.'">'.$test_data->test_code.'</span>';
                                                            }
                                                            else if(strpos($exp_forml, '|') !== false)
                                                            {  
                                                              $inp_for_val = str_replace('|', '', $exp_forml);
                                                              echo '<span class="mini_box input_box_formula" meta="'.$exp_forml.'"><input type="text" name="formula_val[]" value="'.$inp_for_val.'" onkeyup="change_formula_input(this.value)"  class="formula_input price_float" /></span>';
                                                            }
                                                            else
                                                            {
                                                                echo '<span class="mini_box_space" meta="'.$exp_forml.'">'.$exp_forml.'</span>';
                                                            } 
                                                        }
                                                    }
                                                }
                                               ?>
                                        </div>
                                        <input type="hidden" class="h-auto" name="formula" id="formula" value="<?php echo $form_data['formula']; ?>">
                                    </div>
                                </div>

                                <div class="row m-b-5">
                                    <div class="col-xs-4"></div>
                                    <div class="col-xs-8">
                                        
                                        <div class="" style="padding-bottom: 5px;">
                                            <a href="javascript:void(0);"  class="btn-dmas" onclick="formula(0,'+');">+</a>
                                            <a href="javascript:void(0);"  class="btn-dmas" onclick="formula(0,'-');">-</a>
                                            <a href="javascript:void(0);"  class="btn-dmas  p-t-10"  onclick="formula(0,'*');">*</a>
                                            <a href="javascript:void(0);"  class="btn-dmas" onclick="formula(0,'/');">/</a>
                                            <a href="javascript:void(0);"  class="btn-dmas" onclick="formula(0,'=');">=</a>
                                            <a href="javascript:void(0);"  class="btn-dmas" onclick="formula(0,'val');">Val</a> 
                                        <a href="javascript:void(0);"  class="btn-dmas" onclick="formula(0,'^');">^</a>  
                                        <a href="javascript:void(0);"  class="btn-dmas" onclick="formula(0,'(');">(</a>  
                                        <a href="javascript:void(0);"  class="btn-dmas" onclick="formula(0,')');">)</a>  
                                            <a href="javascript:void(0);"  class="btn-dmas" onclick="back_formula();">&#8626;</a>
                                        </div>
                                    </div>
                                </div> <!-- row -->
                                
                                 <div class="row m-b-5">
                                    <div class="col-xs-4">
                                        
                                    </div>
                                    <div class="col-xs-8">
                                       
                                    </div>
                                </div><!-- row -->

                                 <div class="row m-b-5">
                                    <div class="col-xs-4">
                                        <label>Result Type </label>
                                    </div>
                                    <div class="col-xs-8">

                                    <?php //print_r($form_data); ?>
                                       <select name="test_result_type" class="m_input_default" id="test_result_type">
                                            <option value="0" <?php if($form_data['test_result_type']==0){ echo 'selected="selected"'; } ?> >Textbox</option>
                                            <option value="1" <?php if($form_data['test_result_type']==1){ echo 'selected="selected"'; } ?>>Dropdown</option>
                                        </select>
                                        <?php if(!empty($form_error)){ echo form_error('test_result_type'); } ?>
                                    </div>
                                </div><!-- row -->

                                <div class="block" style="margin-left:0px;">
                                  <a href="javascript:void(0)" onclick="multi_interpration()" class="btn-commission"><i class="fa fa-plus"></i>  Add Interpretation</a>
                                  
                                  <?php if(in_array('223',$users_data['permission']['section'])){ ?>
                                  
                                  <a href="javascript:void(0)" class="btn-commission closed" id="suggestion_btn" ><i class="fa fa-eye"></i> Suggestions</a>
                                 <?php } ?> 
                                 
                                  <?php  if(in_array('953',$users_data['permission']['action'])){?>
                                        <a href="javascript:void(0)" class="btn-commission" id="invetory_select"><i class="fa fa-refresh"></i> Inventory</a>
                                        <?php }?>
                                </div>  
                                

                            </div>
                            <div class="col-sm-6">
                              <div class="row m-b-5">
                                    <div class="col-xs-4">
                                
                                        <label>All Range</label> 
                                    </div> 
                                    <div class="col-xs-8">
                                        <input type="radio" name="all_range_show" value="1" <?php if($form_data['all_range_show']==1){ echo 'checked="checked"'; } ?> /> Show
                                        <input type="radio" <?php if(empty($form_data['all_range_show']) || $form_data['all_range_show']==0){ echo 'checked="checked"'; } ?> name="all_range_show" value="0" /> Hide 
                                    </div> 
                                </div>
                           
                            
                            <!--neha 13-2-2019-->
                                <div class="row">
                                    <div class="col-xs-4"> 
                                        <label>Out Source</label> 
                                    </div> 
                                    <div class="col-xs-8">
                                        <input type="checkbox" name="is_outsource" value="1" <?php if($form_data['is_outsource']==1){ echo 'checked="checked"'; } ?> /> yes
                                         
                                    </div> 
                                </div>
                              <!--neha 13-2-2019 end-->
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>Condition  </label>
                                    </div>
                                    <div class="col-xs-8"> 
                                        <div class="ptm_box"> 
                                            <div class="formula_box" id="condition_box">
                                              <?php 
                                                if(!empty($form_data['condition']))
                                                {
                                                    $exp_condition = explode(',', $form_data['condition']);
                                                    foreach($exp_condition as $exp_cnd)
                                                    {
                                                        if(!empty($exp_cnd))
                                                        {
                                                            if(is_numeric($exp_cnd))
                                                            {
                                                                
                                                               $test_data = get_test($exp_cnd);
                                                               echo '<span class="mini_box" meta="'.$exp_cnd.'">'.$test_data->test_code.'</span>';
                                                            }
                                                            else
                                                            {
                                                                echo '<span class="mini_box_space" meta="'.$exp_cnd.'">'.$exp_cnd.'</span>';
                                                            }
                                                        } 
                                                    }
                                                }
                                               ?>
                                            </div>
                                            <input name="condition" class="" id="condition" value="" type="hidden"> 
                                            <!-- <a href="javascript:void(0);" class="btn-dmas  btn-dmas-cancel" onclick="return back_condition();" style="margin-top:0.3em;">&#8626;</a> -->
                                            <div class="ptm_box2">
                                              <label>Result :</label>
                                              <input onkeypress="return allow_con_result(event);" name="condition_result" value="<?php echo $form_data['condition_result']; ?>" class="w-90px m-t-5" type="text">
                                            </div>
                                            <div class="ptm_box3 relative">
                                              <a href="javascript:void(0);" class="btn-dmas" onclick="return condition(0,'+');">+</a>
                                              <a href="javascript:void(0);" class="btn-dmas" onclick="return condition(0,'-');">-</a>
                                              <a href="javascript:void(0);" class="btn-dmas p-t-10" onclick="return condition(0,'*');">*</a>
                                              <a href="javascript:void(0);" class="btn-dmas" onclick="return condition(0,'/');">/</a>
                                              <a href="javascript:void(0);" class="btn-dmas" onclick="condition(0,'&lt;');">&lt;</a>
                                              <a href="javascript:void(0);" class="btn-dmas" onclick="condition(0,'&gt;');">&gt;</a> 
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);" class="btn-dmas  btn-dmas-cancel pos_r_t" onclick="return back_condition();">&#8626;</a>
                                    </div>
                                </div> <!-- row -->

                                <div class="row m-t-5">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-8" >
                                        <button class="btn-update" id="form_submit"><i class="fa fa-refresh"></i> Submit</button>
                                        
                                       


                                        <a class="btn-anchor" href="<?php echo base_url("test_master");?>"><i class="fa fa-sign-out"></i> Exit</a>
                                    </div>
                                    
                                    
                                </div> <!-- row -->
                            </div>
                        </div>
                    </div>
                </div> <!-- row -->
              
              
                <!-- ========================================================= -->


            <div class="row">
            <div class="col-xs-12">  
                <table id="range_box" class="table table-bordered table-striped add_master" cellpadding="1" cellspacing="0" <?php if(empty($range_list)){ ?>  style="display: none;" <?php } ?>>
                <thead class="bg-theme">
                   <tr>
                       <th>Gender</th>
                       <th>Age</th>
                       <th>Lower Bound</th>
                       <th>Upper Bound</th>
                       <th>Action</th>
                   </tr> 
                </thead>
                <tbody>   
                   <?php
                    if(!empty($range_list))
                    {
                        $r = 0;
                        foreach($range_list as $range)
                        {
                        ?>
                         <tr class="range_row" id="row_<?php echo $r; ?>">
                             <td> 
                             <div style="width:100%;"> 
                                <input name="gender[<?php echo $r; ?>]" value="1" type="radio" <?php if($range->gender==1){ echo 'checked="checked"'; } ?>> Male 
                             </div>
                             <div style="width:100%;">   
                                <input name="gender[<?php echo $r; ?>]" value="0" type="radio" <?php if($range->gender==0){ echo 'checked="checked"'; } ?> > Female 
                             </div>
                             <div style="width:100%;">   
                                <input name="gender[<?php echo $r; ?>]" value="2" type="radio" <?php if($range->gender==2){ echo 'checked="checked"'; } ?> > Both 
                             </div>   
                             </td>
                             <td>
                                <input type="text" name="start_age[<?php echo $r; ?>]" value="<?php echo $range->start_age; ?>" class="mini_textbox" /> 
                                To 
                                <input type="text" name="end_age[<?php echo $r; ?>]" value="<?php echo $range->end_age; ?>" class="mini_textbox" /> 
                                <select class="mini_textbox" name="age_type[<?php echo $r; ?>]"> 
                                    <option <?php if($range->age_type==0){ echo 'selected="selected"'; } ?> value="0">Days</option>
                                    <option value="1" <?php if($range->age_type==1){ echo 'selected="selected"'; } ?>>Months</option>
                                    <option value="2" <?php if($range->age_type==2){ echo 'selected="selected"'; } ?>>Years</option>
                                    <option value="3" <?php if($range->age_type==3){ echo 'selected="selected"'; } ?>>Hours</option>
                                </select> 
                             </td> 
                               <td>
                                 <input type="text"  placeholder="Prefix"  name="range_start_pre[<?php echo $r; ?>]" value="<?php echo $range->range_from_pre; ?>"  />
                                 <input type="text"   placeholder="Value" name="range_start[<?php echo $r; ?>]" value="<?php echo $range->range_from; ?>" />
                                 <input type="text"  placeholder="Suffix" name="range_start_post[<?php echo $r; ?>]" value="<?php echo $range->range_from_post; ?>" />
                             </td> 
                             <td>
                                 <input type="text"   placeholder="Prefix" name="range_end_pre[<?php echo $r; ?>]" value="<?php echo $range->range_to_pre; ?>" />
                                 <input type="text"  placeholder="Value"  name="range_end[<?php echo $r; ?>]" value="<?php echo $range->range_to; ?>" />
                                 <input type="text"   placeholder="Suffix" name="range_end_post[<?php echo $r; ?>]" value="<?php echo $range->range_to_post; ?>" />
                             </td> 
                             <td>
                                 <?php
                                  if($r==0)
                                  {
                                    echo '<a href="javascript:void(0);" class="btn-custom" onclick="add_range()"><i class="fa fa-plus"></i>  Add</a>';
                                    if($form_data['data_id']>0)
                                    {
                                       echo '<a href="javascript:void(0);" class="btn-custom" onclick="remove_range('.$r.','.$range->id.')"> Remove </a>'; 
                                    }
                                  }
                                  else
                                  {
                                    echo '<a href="javascript:void(0);" class="btn-custom" onclick="remove_range('.$r.','.$range->id.')"> Remove </a>';
                                  }
                                 ?>
                             </td>
                         </tr> 
                        <?php    
                        $r++;
                        } 
                    }
                   ?>
                   </tbody>
               </table>
            </div>
            <div class="col-xs-5"></div>
        </div> <!-- row -->

        <!-- Multi Interpretation   -->
        <div class="row">
            <div class="col-xs-12">  
                <table id="multi_interpration_table" class="table table-bordered table-striped multi_interpration_table" cellpadding="0" cellspacing="0" <?php if(empty($form_data['interpretation'])){ ?>  style="display: none;" <?php } ?>>
                <thead class="bg-theme">
                   <tr>
                       <th>Interpretation</th>
                       <th>Condition</th> 
                       <th>Description</th>
                       <th>Action</th>
                   </tr> 
                </thead>
                <tbody id="multi_interpration_data">   
                   <?php
                    $total_inter_record = count($form_data['interpretation']);
                    if(!empty($form_data['interpretation']))
                    {
                        $in = 0;
                        $interpretation_data = $this->session->userdata('multi_interpretation');
                        foreach($form_data['interpretation'] as $int_pre)
                        {
                        ?>
                         <tr class="range_row" id="in_row_<?php echo $in; ?>"> 
                             <td>
                                <input type="text" name="interpretation[<?php echo $in; ?>][title]" value="<?php echo $int_pre['title']; ?>" class="" />   
                             </td> 
                               <td>
                                 <input type="radio"  name="interpretation[<?php echo $in; ?>][condition]" value="0" <?php if($int_pre['condition']==0){ echo 'checked="checked"';} ?>  /> All

                                 <input type="radio"  name="interpretation[<?php echo $in; ?>][condition]" value="1" <?php if($int_pre['condition']==1){ echo 'checked="checked"';} ?>  /> Low
                                 <input type="radio"  name="interpretation[<?php echo $in; ?>][condition]" value="2" <?php if($int_pre['condition']==2){ echo 'checked="checked"';} ?> /> Normal
                                 <input type="radio"  name="interpretation[<?php echo $in; ?>][condition]" value="3" <?php if($int_pre['condition']==3){ echo 'checked="checked"';} ?> /> High
                             </td> 
                             <td>
                                <a href="javascript:void(0)" onclick="add_interpretation(<?php echo $in; ?>)" class="btn-commission"><i class="fa fa-plus"></i> Add Interpretation</a>
                             </td> 
                             <td>
                                 <?php
                                  if($in==0)
                                  {
                                    echo '<a href="javascript:void(0);" class="btn-custom" onclick="add_multi_interpration()"><i class="fa fa-plus"></i> Add</a>'; 
                                  }
                                  else
                                  {
                                    echo '<a href="javascript:void(0);" class="btn-custom" onclick="remove_interpration('.$in.')"> Remove </a>';
                                  }
                                 ?>
                             </td>
                         </tr> 
                        <?php    
                        $in++;
                        } 
                    }
                    else
                    {
                    ?>
                     <tr class="range_row" id="in_row_0"> 
                             <td>
                                <input type="text" name="interpretation[0][title]" value="" class="" />   
                             </td> 
                               <td>
                                 <input type="radio"  checked="checked"  name="interpretation[0][condition]" value="0"  /> All
                                 <input type="radio"    name="interpretation[0][condition]" value="1"  /> Low
                                 <input type="radio"  name="interpretation[0][condition]" value="2"  /> Normal
                                 <input type="radio"  name="interpretation[0][condition]" value="3"  /> High
                             </td> 
                             <td>
                                <a href="javascript:void(0)" onclick="add_interpretation(0)" class="btn-commission"><i class="fa fa-plus"></i> Interpretation</a>
                             </td> 
                             <td>
                                 <a href="javascript:void(0);" class="btn-custom" onclick="add_multi_interpration()"><i class="fa fa-plus"></i> Add</a>
                             </td>
                         </tr>
                    <?php  
                    }
                   ?>
                   </tbody>
               </table>
            </div>
            <div class="col-xs-5"></div>
        </div> <!-- row -->
        <!-- End multi interpreation -->



          <!-- new code by mamta -->
 <?php //print_r($item_list);die; ?>
          <div class="row">
            <div class="col-xs-12">  
                <table id="inventory_box" class="table table-bordered table-striped add_master" cellpadding="0" cellspacing="0" <?php if(empty($form_data['item_list'])){ ?>  style="display: none;" <?php } ?>>
                <thead class="bg-theme">
                   <tr>
                       <th>S.No.</th>
                       <th>Item</th>
                       <th>Quantity</th>
                       <th>Unit</th>
                      <th>Action</th>
                   </tr> 
                </thead>
                <tbody>   
                   <?php


                    if(!empty($form_data['item_list']))
                    {
                        $r = 0;
                        $i=1;
                        foreach($form_data['item_list'] as $item)
                        {

                           $get_unit_according_item= get_units_by_item($item['item_id']);
                           $get_unit_by_unit_id= get_units_by_id($item['unit_id']);

                        ?>
                         <tr class="inventory_row" id="row_<?php echo $r; ?>">
                           <td><?php echo $i; ?></td>
                            
                             <td>
                                <input type="text" name="item_name[<?php echo $r; ?>]" value="<?php echo $item['item']; ?>" class="" id="item_name_<?php echo $r;?>" /> 
                                <input type="hidden" name="item_id[<?php echo $r; ?>]" value="<?php echo $item['item_id']; ?>" class="" id="item_id_<?php echo $r;?>" /> 
                                
                             </td> 
                             <td>
                                 <input type="text" name="quantity[<?php echo $r; ?>]" value="<?php echo $item['inventory_qty']; ?>" id="quantity_<?php echo $r;?>" />
                             </td> 
                            <td>
                                 <select name="unit_value[<?php echo $r; ?>]" id="unit_dropdown_<?php echo $r; ?>" >
                                 
                                  <option value="">Select Unit</option>
                                  <?php  if(!empty($get_unit_according_item[0])) { foreach($get_unit_according_item[0] as $get_unit)
                                  {

                                      if(!empty($get_unit))
                                      {
                                  ?>
                                 <option value="<?php echo $get_unit['id'];?>" <?php if($get_unit['id']==$get_unit_by_unit_id[0]->id){ echo 'selected';} ?>><?php echo $get_unit['first_name'];?></option>
                                 <?php } } }?>

                                 </select>
                             </td> 
                             <td>
                                 <?php
                                  if($r==0)
                                  {
                                    echo '<a href="javascript:void(0);" class="btn-custom" onclick="add_inventory()"><i class="fa fa-plus"></i> Add</a>';
                                    if($form_data['data_id']>0)
                                    {
                                       echo '<a href="javascript:void(0);" class="btn-custom" onclick="remove_inventory('.$r.')"> Remove </a>'; 
                                    }
                                  }
                                  else
                                  {
                                    echo '<a href="javascript:void(0);" class="btn-custom" onclick="remove_inventory('.$r.')"> Remove </a>';
                                  }
                                 ?>
                             </td>
                         </tr> 
                        <?php    
                        $r++;$i++;
                        } 
                    }
                   ?>
                   </tbody>
               </table>
            </div>
            <div class="col-xs-5"></div>
        </div> <!-- row -->

        <!-- new code by mamta-->
        <div class="row">
            <div class="col-xs-7"></div>
            <div class="col-xs-5">
                <!-- <button class="btn-update" id="form_submit"><i class="fa fa-refresh"></i> Submit</button>
                <a class="btn-anchor" href="<?php echo base_url("test_master");?>"><i class="fa fa-sign-out"></i> Exit</a> -->
            </div>
        </div> <!-- row -->

 <?php if(in_array('223',$users_data['permission']['section'])) { ?>
 
<?php if($form_data['data_id']>0) {  ?>        
        <div class="row">
          <div class="col-xs-12">  
          <div id="suggestion_box"></div>
          <?php //print_r($test_suggestions);
            $i=0;
            if($test_suggestions!="empty")
            {
          ?>  
          <table id="suggestion_box_tab" class="table table-bordered table-striped add_master" cellpadding="0" cellspacing="0" ><thead class="bg-theme"><tr ><th>Test Name</th><th>Condition</th><th>Action</th></tr></thead><tbody>
          <?php
            foreach($test_suggestions as $data)
            {
              $i++;
            ?>  
              
              <tr id="sug_tr_<?php echo $i; ?>" >
                    <td>
                        <input type="textbox" disabled id="suggested_test_id_<?php echo $i; ?>" class="autocomplete_test_data" value="<?php echo $data->test_name; ?>" onkeyup="get_autocomplete_test(<?php echo $i; ?>);"> 
                        <input type="hidden" value="<?php echo $data->suggested_test_id; ?>" name="suggested_test_id[<?php echo $i; ?>]" id="suggested_test_id_val_<?php echo $i; ?>" >
                    </td>
                    <td>
                        <input type="radio" <?php if($data->test_condition==0){ echo "checked=checked";}?> name="test_condition[<?php echo $i; ?>]" value="0" >Normal 
                        <input type="radio" <?php if($data->test_condition==1){ echo "checked=checked";}?> name="test_condition[<?php echo $i; ?>]" value="1">High 
                        <input type="radio" <?php if($data->test_condition==2){ echo "checked=checked";}?> name="test_condition[<?php echo $i; ?>]" value="2">Low 
                    </td>
                    <td>
                      <button class="btn-custom" onclick="remove_test_suggestion(<?php echo $i; ?>);return false;">Remove</button>  


                    </td>
              </tr>
             
            <?php   
            }
          ?>
           
           </tbody></table>
           <button style="float: right;margin-right: 38px;" class="btn-custom" onclick="add_more_suggestion();return false;" ><i class="fa fa-plus"></i> Add</button>
          <?php } ?> 

          </div>
        </div>
<?php } else { ?>
      
      <div class="row">
          <div class="col-xs-12">  
          <div id="suggestion_box"></div>

          </div>
        </div>

<?php } ?>


<?php } ?>




        </div> <!-- col -->
  	
    	
    	

        <div class="col2"> <!-- style="height:295px;overflow-y:auto;overflow-x:hidden;border:1px solid #ddd;" -->
            <div class="col-leftt">
                <table id="test_data" class="table table-bordered table-striped add_test_master_tbl">
                    <thead class="bg-theme">
                        <tr>    
                            <th>Code</th> 
                            <th>Test Name</th> 
                            <th>Patient Rate</th> 
                            <th>Sort Order</th>
                            <th>Action</th> 
                        </tr>
                    </thead> 
                </table>                
            </div> <!-- col-left --> 
        </div> <!-- col -->



        








    </div> <!-- test-master -->
</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
<div id="load_add_test_sample_type_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_test_heads_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_test_method_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
<script type="text/javascript">
function multi_interpration()
{  
  $('#multi_interpration_table').slideDown();
}
var inp = <?php echo $total_inter_record+1; ?>;
function add_multi_interpration()
{
   var html = '<tr class="in_range_row" id="in_row_'+inp+'"><td><input type="text" name="interpretation['+inp+'][title]" value="" class="" /></td><td><input checked="checked" type="radio"  name="interpretation['+inp+'][condition]" value="0"  /> All<input  type="radio"  name="interpretation['+inp+'][condition]" value="1"  /> Low <input type="radio"  name="interpretation['+inp+'][condition]" value="2"  /> Normal <input type="radio"  name="interpretation['+inp+'][condition]" value="3"  /> High </td>  <td> <a href="javascript:void(0)" onclick="add_interpretation('+inp+')" class="btn-commission"><i class="fa fa-plus"></i> Interpretation</a> </td> <td> <a href="javascript:void(0);" class="btn-custom" onclick="remove_interpration('+inp+')"> Remove </a> </td> </tr> ';
   $('#multi_interpration_data').append(html);
   inp++;
}

function remove_interpration(inp)
{
  $('#in_row_'+inp).remove();
}



var r = 0;
$("#invetory_select").click(function()
{
   r++; 
   if(r==1)
   {
     $('#inventory_box').fadeIn();
     add_inventory();
   }
   else
   {
     $('#inventory_box').fadeOut();
     $('.inventory_row').remove();
     r = 0;
     i = 0;
   }    
   
});


$("#suggestion_btn").click(function(e)
{
  ct=0;
  if($(this).hasClass('closed'))
  {
    ct++;
    $("#suggestion_box").css('display', 'block'); 
    $("#suggestion_btn").addClass('opened');
    $("#suggestion_btn").removeClass('closed');
    add_suggestion(ct);
    //bind_autocomplete();
  }
  else if($(this).hasClass('opened'))
  {
    $("#suggestion_box").css('display', 'none'); 
    $("#suggestion_box").html('');
    $("#suggestion_btn").addClass('closed'); 
    $("#suggestion_btn").removeClass('opened');
    ct=0;
  }
});


var sug_count=0;
function add_suggestion(ctval)
{
  sug_count++;
  if(ctval>=1)
  {
     var string='<table id="suggestion_box_tab" class="table table-bordered table-striped add_master" cellpadding="0" cellspacing="0"  ><thead class="bg-theme"><tr ><th>Test Name</th><th>Condition</th><th>Action</th></tr></thead><tbody><tr id="sug_tr_'+sug_count+'" ><td><input type="textbox" id="suggested_test_id_'+sug_count+'" class="autocomplete_test_data" onkeyup="get_autocomplete_test('+sug_count+');" > <input type="hidden" value="" name="suggested_test_id['+sug_count+']" id="suggested_test_id_val_'+sug_count+'" >  </td><td><input type="radio" name="test_condition['+sug_count+']" value="0" checked >Normal <input type="radio" name="test_condition['+sug_count+']" value="1">High <input type="radio" name="test_condition['+sug_count+']" value="2">Low </td><td><button class="btn-custom" onclick="add_more_suggestion();return false;"><i class="fa fa-plus"></i> Add</button>  </td></tr></tbody></table>';
  }
/*  else if(ctval==0)
  {
    var string='<table id="suggestion_box_tab" class="table table-bordered table-striped add_master" cellpadding="0" cellspacing="0" ><thead class="bg-theme"><tr  ><th>Test Name</th><th>Condition</th><th>Action</th></tr></thead><tbody><tr id="sug_tr_'+sug_count+'" ><td><input type="textbox" id="suggested_test_id_'+sug_count+'" class="autocomplete_test_data"  onkeyup="get_autocomplete_test('+sug_count+');" > <input type="hidden" value="" name="suggested_test_id['+sug_count+']" id="suggested_test_id_val_'+sug_count+' " >  </td><td><input type="radio" name="test_condition['+sug_count+']" value="0" checked >Normal <input type="radio" name="test_condition['+sug_count+']" value="1">High <input type="radio" name="test_condition['+sug_count+']" value="2">Low</td><td><button class="btn-custom" onclick="remove_test_suggestion();">Remove</button>  </td></tr></tbody></table>';
  }*/
  $("#suggestion_box").append(string);
}


<?php if($form_data['data_id']>0 && $test_suggestions!="empty") { ?>
        var cou="<?php echo count($test_suggestions) + 1; ?>";
<?php } else { ?>
        var cou=2;
<?php } ?>  



function add_more_suggestion()
{
  var string='<tr id="sug_tr_'+cou+'"><td><input type="textbox" id="suggested_test_id_'+cou+'" class="autocomplete_test_data" onkeyup="get_autocomplete_test('+cou+');" > <input type="hidden" value="" name="suggested_test_id['+cou+']" id="suggested_test_id_val_'+cou+'" >  </td><td><input type="radio" name="test_condition['+cou+']" value="0" checked >Normal <input type="radio" name="test_condition['+cou+']" value="1">High <input type="radio" name="test_condition['+cou+']" value="2">Low</td><td><button class="btn-custom" onclick="remove_test_suggestion('+cou+');">Remove</button>  </td></tr>';
  $("#suggestion_box_tab").append(string);
  cou++;
   // bind_autocomplete();
}

function remove_test_suggestion(tr_id)
{
  $("#sug_tr_"+tr_id).remove();
}


//For autocomplete test name
 
function bind_autocomplete()
{ 
    /*var getData = function (request, response) {
        $.getJSON(
            "<?php echo base_url(); ?>test_master/get_auto_complete_test/" + request.term,
            function (data) {
              if(!data)
              {
                var result = [
                  { label: 'No matches found', 
                    value: -1
                  }
                ];
                response(result);
          
              }
              else
              {
                response(data); 
              }
            });
    };*/

   /* var selectItem = function (event, ui) 
    {*/

      /*if(ui.item.value==-1)
      {
        $("#company_name").val('');
        $("company_id").val("");
        $("#contact_name").val('');
        $("#contact_id").val("");
          return false;
      }
      else
      {
        $("#company_name").val(ui.item.value);
          $("#company_id").val(ui.item.id);
          $("#contact_name").val('');
        $("#contact_id").val("");
          return false;
      }*/
    //}

   /* $(".autocomplete_test_data").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {  
        }
    });*/
}

function get_autocomplete_test(row_id)
{
  //console.log('suggested_test_id_'+row_id);
   
    var getData = function (request, response) {
        $.getJSON(
            "<?php echo base_url(); ?>test_master/get_auto_complete_test/" + request.term,
            function (data) {
              if(!data)
              {
                var result = [
                  { label: 'No matches found', 
                    value: -1
                  }
                ];
                response(result);
          
              }
              else
              {
                response(data); 
              }
            });
    };

    var selectItem = function (event, ui) 
    {
      console.log(ui);
      if(ui.item.value==-1)
      {
        $('#suggested_test_id_'+row_id).val('');
        $('#suggested_test_id_val_'+row_id).val("");
        return false;
      }
      else
      {
        $('#suggested_test_id_'+row_id).val(ui.item.value);
        $('#suggested_test_id_val_'+row_id).val(ui.item.id);
        return false;
      }
    }

     $('#suggested_test_id_'+row_id).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {  
        }
    });

      
}



// for auto complete test name







function add_inventory()
{ 
  //var drop_down='';
   if(int_i==0)
    {
      var action_btn = '<a href="javascript:void(0);" class="btn-custom" onclick="add_inventory('+int_i+')"><i class="fa fa-plus"></i> Add</a>';
    }
    else
    {
      var rowCount = $('#inventory_box >tbody >tr').length; 
      if(rowCount==0)
      {
         var action_btn = '<a href="javascript:void(0);" class="btn-custom" onclick="add_inventory('+int_i+')"><i class="fa fa-plus"></i> Add</a>';
      } 
      else
      {
         var action_btn = '<a href="javascript:void(0);" class="btn-custom" onclick="remove_inventory('+int_i+',0)"> Remove </a>';
      } 
    }  
    
    var drop_down='<select id="unit_dropdown_'+int_i+'" name="unit_value['+int_i+']"><option value="">Select Unit</option></select>'
    $('#inventory_box').append('<tr class="range_row" id="row_'+int_i+'"><td>'+j+'</td><td><input type="text" name="item_name['+int_i+']" value="" class="" id="item_name_'+int_i+'"/><input type="hidden" name="item_id['+int_i+']" value="" class="" id="item_id_'+int_i+'"/></td> <td><input type="text" name="quantity['+int_i+']" value="" class="qty" id="quantity_'+int_i+'" /></td> <td>'+drop_down+'</td> <td> '+action_btn+' </td></tr>');
   
      var getData1 = function (request, response) { 
        //var id = this.element.attr('id'); 
        row = int_i ;
        //alert(JSON.stringify(request))  
        $.ajax({
        url : "<?php echo base_url('stock_purchase/get_item_values/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
       data: {
         name_startsWith: request.term,
         
         row_num : row
      },
       success: function( data ) {
         response( $.map( data, function( item ) {
          var code = item.split("|");
          return {
            label: code[0],
            value: code[0],
            data : item
          }
        }));
      }
      });
    };

      var selectItem1 = function (event, ui) {
          var rowCount = $('#inventory_box >tbody >tr').length-1; 
         var id = $(this).attr('id'); 
         var explode = id.split("_");
         var names = ui.item.data.split("|");
        
         //alert($('#quantity_'+i).val(names[6]));
          $("#item_id_"+explode[2]).val(names[4]);
          $("#item_name_"+explode[2]).val(names[0]);
          $('#quantity_'+explode[2]).val(names[6]);
          $('#unit_dropdown_'+explode[2]).html(names[7]);
          
         return false;
      }
      
      $("#item_name_"+int_i).autocomplete({
          source: getData1,
          select: selectItem1,
          minLength: 0,
          change: function() 
          {   

          }
      });


    int_i++;
    j++;


    
}


 /* autocomplete code in item */
    <?php
    if(!empty($item_list))
    {
    $r = 0;
    $i=1;
    foreach($item_list as $item)
    {
    ?>

    var getData1 = function (request, response) { 
       //var id = this.element.attr('id'); 
        row = i ;
        //alert(JSON.stringify(request))  
        $.ajax({
        url : "<?php echo base_url('stock_purchase/get_item_values/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
        data: {
        name_startsWith: request.term,

        row_num : row
        },
       success: function( data ) {
         response( $.map( data, function( item ) {
          var code = item.split("|");
          return {
            label: code[0],
            value: code[0],
            data : item
          }
        }));
      }
      });
    };

      var selectItem1 = function (event, ui) {
          var rowCount = $('#inventory_box >tbody >tr').length-1; 
         var id = $(this).attr('id'); 
         var explode = id.split("_");
         var names = ui.item.data.split("|");
        
          $("#item_id_"+explode[2]).val(names[4]);
          $("#item_name_"+explode[2]).val(names[0]);
          $('#quantity_'+explode[2]).val(names[6]);
          $('#unit_dropdown_'+explode[2]).append(names[7]);
          
         return false;
      }
       $("#item_name_<?php echo $r; ?>").autocomplete({
          source: getData1,
          select: selectItem1,
          minLength: 2,
          change: function() 
          {   

          }
      });
  
      <?php $i++; $r++;} }?>

    /* autocomplete code in item */

  function get_heads(dept_id)
  {
    if(dept_id!="" && dept_id>0)
    {
        $.ajax({url: "<?php echo base_url(); ?>test_heads/test_heads_dropdown/"+dept_id, 
            success: function(result)
            {
              $('#test_heads_id').html(result); 
            } 
          }); 
    }
  }
 
function add_unit()
{
  var $modal = $('#load_add_unit_modal_popup');
  $modal.load('<?php echo base_url().'unit/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}


function add_interpretation(vals)
{
  var $modal = $('#load_add_interpretation_modal_popup');
  $modal.load('<?php echo base_url().'test_master/add_interpretation/'; ?>'+vals,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function add_precuation()
{
  var $modal = $('#load_add_interpretation_modal_popup');
  $modal.load('<?php echo base_url().'test_master/add_precuation/'; ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

<?php
if(!empty($range_list))
{
  $total_range = count($range_list)+1;   
  echo 'var i= '.$total_range.';';
}
else
{
  echo 'var i= 0;';
  echo 'var int_i = '.$form_data['int_i'].';';  
}    
?>
j= int_i+1;
function add_range()
{ 
    if(i==0)
    {
      var action_btn = '<a href="javascript:void(0);" class="btn-custom" onclick="add_range('+i+')"><i class="fa fa-plus"></i> Add</a>';
    }
    else
    {
      var rowCount = $('#range_box >tbody >tr').length;  
      if(rowCount==0)
      {
         var action_btn = '<a href="javascript:void(0);" class="btn-custom" onclick="add_range('+i+')"><i class="fa fa-plus"></i> Add</a>';
      } 
      else
      {
         var action_btn = '<a href="javascript:void(0);" class="btn-custom" onclick="remove_range('+i+',0)"> Remove </a>';
      } 
    }  

    $('#range_box').append('<tr class="range_row" id="row_'+i+'"><td><div style="width:100%"><input name="gender['+i+']" value="1" checked="checked" type="radio"> Male</div> <div style="width:100%;"><input name="gender['+i+']" value="0" checked="checked" type="radio" > Female</div> <div style="width:100%;"><input name="gender['+i+']" value="2" checked="checked" type="radio" > Both </div></td><td><input type="text" maxlength="3" class="mini_textbox" name="start_age['+i+']" name="" onkeypress="return isNumberKey(event);"/> To <input type="text" name="end_age['+i+']" value="" maxlength="3" class="mini_textbox" onkeypress="return isNumberKey(event);" /> <select name="age_type['+i+']"><option value="0">Days</option><option value="1">Months</option><option value="2">Years</option><option value="3">Hours</option></select> </td> <td><input type="text"  name="range_start_pre['+i+']" value="" placeholder="Prefix" /><input type="text"  name="range_start['+i+']" value="" placeholder="Value" /><input type="text" placeholder="Suffix"  name="range_start_post['+i+']" value=""  /></td> <td><input type="text" placeholder="Prefix"  name="range_end_pre['+i+']" value=""  /><input type="text" placeholder="Value" name="range_end['+i+']" value=""   /><input type="text" name="range_end_post['+i+']" value="" placeholder="Suffix"   /></td> <td> '+action_btn+' </td></tr>');
    i++;
}
 
function remove_range(id,rid)
{
    $('#row_'+id).remove();
    if(rid>0)
    {
        $.ajax({
            url: "<?php echo base_url('test_master/remove_range/'); ?>"+rid, 
            success: function(result)
            { 

            }
        });
    }
}

function isNumberKey(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      } else {
          return true;
      }      
  }



var r = 0;
$("#advance_range").click(function()
{
   r++; 
   if(r==1)
   {
     $('#range_box').fadeIn();
     add_range();
   }
   else
   {
     $('#range_box').fadeOut();
     $('.range_row').remove();
     r = 0;
     i = 0;
   }    
   
});

$("#test_type_id").change(function()
{
    var type_id = $('#test_type_id').val(); 
    if(type_id==1)
    {
       $('#test_under').removeAttr('readonly',false); 
    }
    else
    {
       $('#test_under').val('');
       $('#test_under').attr('readonly',true); 
    }     
});
 
function formula(tid,vals)
{  
    if(tid>0)
    {
        var classs = "mini_box";
        var inp_data = tid;
    }
    else
    {
        var classs = "mini_box_space";
        var inp_data = vals;
    }
   var inp_val = $('#formula').val(); 
   if(vals=='val')
   {
      $('#formula_box').append('<span class="mini_box input_box_formula" meta="0"><input type="text" name="formula_val[]" value="" onkeyup="change_formula_input(this.value)"  class="formula_input price_float" /></span>');
      var vals_inp = $('#formula').val(inp_val+'0'+','); 
   }
   else
   {
      $('#formula_box').append('<span meta="'+inp_data+'" class="'+classs+'">'+vals+'</span>'); 
      var vals_inp = $('#formula').val(inp_val+inp_data+','); 
   }  
}


function change_formula_input(val)
{   
    var meta = $(".input_box_formula").attr("meta");
    var str = $('#formula').val();
    var avoid_text = meta+',';  
    var res = str.replace(avoid_text, "");  
    var new_val = res+'|'+val+',';  
    $('#formula').val(new_val);
    $('.input_box_formula').attr('meta','|'+val);
}

function back_formula()
{
    var str = $('#formula').val();  
    var avoid_text = $('#formula_box span').last().attr('meta')+',';  
    var res = str.replace(avoid_text, "");  
    $('#formula_box span').last().remove();
    $('#formula').val(res);  
}

function sort_test_master(id,value){

    if(id!=''){
        $.post('<?php echo base_url('test_master/save_sort_order_data/'); ?>',{'test_id':id,'sort_order_value':value},function(result){
            if(result!=''){
                reload_table();
            }

        })
    }
}
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
function condition(tid,vals)
{  
    if(tid>0)
    {
        var classs = "mini_box";
        var inp_data = tid;
    }
    else
    {
        var classs = "mini_box_space";
        var inp_data = vals;
    }
   var inp_val = $('#condition').val(); 
   var vals_inp = $('#condition').val(inp_val+inp_data+','); 
   $('#condition_box').append('<span meta="'+inp_data+'" class="'+classs+'">'+vals+'</span>');
}

function back_condition()
{
    var str = $('#condition').val();  
    var avoid_text = $('#condition_box span').last().attr('meta')+',';  
    var res = str.replace(avoid_text, "");  
    $('#condition_box span').last().remove();
    $('#condition').val(res);  
}


function test_under(tid,vals)
{  
    var test_type = $('#test_type_id').val(); 
    if(test_type==1)
    {
        var classs = "mini_box";
        var inp_data = tid;
        var inp_val = $('#test_under').val(); 
        var vals_inp = $('#test_under').val(inp_val+inp_data+','); 
        $('#test_under_box').append('<span meta="'+inp_data+'" class="'+classs+'">'+vals+'</span>');
    } 
}

function back_under()
{ 
    var test_type = $('#test_type_id').val();
    if(test_type==1)
    {
        var str = $('#test_under').val();  
        var avoid_text = $('#test_under_box span').last().attr('meta')+',';  
        var res = str.replace(avoid_text, "");  
        $('#test_under_box span').last().remove();
        $('#test_under').val(res);  
    } 
}

function set_test_head(head_id)
{
    $.ajax({
        url: "<?php echo base_url('test_master/set_test_head/'); ?>"+head_id, 
        success: function(result)
        { 
           reload_table();
        }
    });
}
$(document).ready(function(){
  $('#load_add_test_method_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
$(document).ready(function(){
  $('#load_add_test_sample_type_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
$(document).ready(function(){
  $('#load_add_test_heads_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
$(document).ready(function(){
  $('#load_add_unit_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});

function remove_inventory(tr_id)
{
   $("#row_"+tr_id).remove();
}
</script>
<div id="load_add_interpretation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_unit_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
</body>
</html>