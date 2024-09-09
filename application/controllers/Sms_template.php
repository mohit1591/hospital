<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_template extends CI_Controller {
 
  function __construct() 
  {
      parent::__construct();	
      auth_users();  
      $this->load->model('sms_template/sms_template_model','sms_templates');
      $this->load->library('form_validation');
  }

    
  public function index()
  {
      unauthorise_permission(102,642);
      
      $data['page_title'] = 'SMS Template List'; 
      $this->load->view('sms_template/list',$data);
  }

  public function ajax_list()
  { 
		    unauthorise_permission(102,642);
        $users_data = $this->session->userdata('auth_users');
      	$sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
      	$list = $this->sms_templates->get_datatables();
        //echo "<pre>";print_r($list); exit;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $Sms) 
        {
            $no++;
            $row = array();
             
               ////////// Check  List /////////////////
               $check_script = "";
               if($i==$total_num)
               {
		        $check_script = "<script>$('#selectAll').on('click', function () { 
		                      if ($(this).hasClass('allChecked')) {
		                          $('.checklist').prop('checked', false);
		                      } else {
		                          $('.checklist').prop('checked', true);
		                      }
		                      $(this).toggleClass('allChecked');
		                  })</script>";
               }


          if($Sms->form_name=='1' && in_array('93',$users_data['permission']['action'])) 
          {

                             
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
                //$row[] = $Sms->type; 
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }


          elseif(($Sms->form_name=='2' || $Sms->form_name=='29') && in_array('85',$users_data['permission']['section'])) 
          {

                           
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }
            elseif(($Sms->form_name=='3' || $Sms->form_name=='30') && in_array('151',$users_data['permission']['section'])) 
          {

                              
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

             elseif(($Sms->form_name=='4' || $Sms->form_name=='34') && in_array('60',$users_data['permission']['section'])) 
          {

                         
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

            elseif($Sms->form_name=='5' && in_array('61',$users_data['permission']['section'])) 
          {

                           
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

            elseif($Sms->form_name=='6' && in_array('58',$users_data['permission']['section'])) 
          {

                             
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

            elseif($Sms->form_name=='7' && in_array('59',$users_data['permission']['section'])) 
          {

                             
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

            elseif($Sms->form_name=='8' && in_array('36',$users_data['permission']['section'])) 
          {

                           
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
              // $row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

            elseif($Sms->form_name=='9' && in_array('99',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

            elseif($Sms->form_name=='10' && in_array('39',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

            elseif($Sms->form_name=='11' && in_array('146',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

          elseif($Sms->form_name=='12') 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

          elseif($Sms->form_name=='13') 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

            elseif($Sms->form_name=='14') 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

          elseif($Sms->form_name=='15' && in_array('121',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

          elseif($Sms->form_name=='16' && in_array('127',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

          elseif($Sms->form_name=='32' && in_array('134',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }
            
            elseif(($Sms->form_name=='17' || $Sms->form_name=='31') && in_array('130',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }
            
            elseif(($Sms->form_name=='18' || $Sms->form_name=='28') && in_array('19',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

             elseif($Sms->form_name=='19' && in_array('20',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

            elseif($Sms->form_name=='20' && in_array('1',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

            elseif($Sms->form_name=='21' && in_array('5',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

            elseif(($Sms->form_name=='22' || $Sms->form_name=='33') && in_array('145',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

            elseif($Sms->form_name=='23' && in_array('145',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

            elseif($Sms->form_name=='24' && in_array('146',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

            elseif($Sms->form_name=='25' && in_array('35',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               
               //$row[] = $Sms->subject; 
               $row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

            elseif($Sms->form_name=='26' && in_array('121',$users_data['permission']['section'])) 
          {

                          
               ////////// Check list end ///////////// 
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }
               $row[] = $Sms->module_name;
               $row[] = $Sms->template; 
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_sms_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit;
               $data[] = $row;
            }

          
               $i++;

          }
           $output = array(
               "draw" => $_POST['draw'],
               "recordsTotal" => $this->sms_templates->count_all(),
               "recordsFiltered" => $this->sms_templates->count_filtered(),
               "data" => $data,
          );
           //output to json format
          
          echo json_encode($output);
     }

  public function add()
	{
      unauthorise_permission(102,643);
      /*$get_data= $this->sms_templates->template_format();
      if(count($get_data)>0)
      {
          $data_id=$get_data['id'];
          $form_name=$get_data['form_name'];
          $template=$get_data['template'];
          $short_code=$get_data['short_code'];
      }
      else
      {*/
          $data_id='';
          $form_name='';
          $template='';
          $short_code='';
          
      //}	
          $data['form_name']  = get_form_name();
      		$data['page_title'] = "Add SMS Template";
      		$data['form_error'] = [];
      		$post = $this->input->post();
      		$data['form_data'] = array(
                                      "data_id"=>$data_id,
                                      "form_name"=>$form_name,
                                      "message"=>$template,
                                      "short_code"=>$short_code
      			                      );
      if(isset($post) && !empty($post))
      {  
        $data['form_data'] = $this->_validate();
        if($this->form_validation->run() == TRUE)
        {
              $this->sms_templates->save();
              echo 1;
              return false;
              /*$this->session->set_flashdata('success','SMS template succefully saved.');
              redirect('sms_template/add');*/
        }
        else
        {
              $data['form_error'] = validation_errors();  
        }       
      }
       
		$this->load->view('sms_template/add',$data);
	}

	public function edit($id="")
  {
     	
     unauthorise_permission(102,644);
     $data['form_name']  = get_form_name_edit();
     
     if(isset($id) && !empty($id) && is_numeric($id))
     {      
        $result = $this->sms_templates->get_by_id($id);  
        $data['page_title'] = "Update Sms Template";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                 'data_id'=>$result['id'],
                                  'form_name'=>$result['form_name'],
                                  'message' =>$result['template'],
                                  'short_code'=>$result['short_code']
                              );    
        
        if(isset($post) && !empty($post))
        {   
          	
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->sms_templates->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       
       $this->load->view('sms_template/add',$data);       
      }
    }



    private function _validate()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('form_name', 'form name', 'trim|required');
        if ($this->form_validation->run() == FALSE) 
        {  
           
            $data['form_data'] = array(
                                    "data_id"=>$_POST['data_id'],
                                    "form_name"=>$_POST['form_name'],
                                    "message"=>$_POST['message'],
                                    "short_code"=>$_POST['short_code']
                                  );  
            return $data['form_data'];
        }   
    }



  public function sms_template_dropdown()
  {
      $value= $this->input->post('value');
      $data_array= array('form_id'=>$value);
      $opd_list = $this->sms_templates->template_list($data_array);
      $data['template']= $opd_list['template'];
      $data['short_code']= $opd_list['short_code'];
      echo json_encode($data);
  }

 } 
 ?>