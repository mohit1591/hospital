<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_template extends CI_Controller {
 
  function __construct() 
  {
      parent::__construct();	
      auth_users();  
      $this->load->model('email_template/email_template_model','email_templates');
      $this->load->library('form_validation');
  }

    
  public function index()
  {
      unauthorise_permission(102,642);
      
      $data['page_title'] = 'Email Template List'; 
      $this->load->view('email_template/list',$data);
  }

  public function ajax_list()
  { 
		    unauthorise_permission(102,642);
        $users_data = $this->session->userdata('auth_users');
      	$sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
      	$list = $this->email_templates->get_datatables();
        //echo "<pre>"; print_r($list); exit;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $Sms) 
        {
            $no++;
            $row = array();
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
               ////////// Check  List /////////////////
          $btnedit='';
          $btndelete='';
          if($Sms->form_name=='1' && in_array('93',$users_data['permission']['section'])) 
          {
			         if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }
                $row[] = $Sms->module_name;
                $row[] = $Sms->subject; 
                
                if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
               $data[] = $row;
          }
          elseif($Sms->form_name=='2' && in_array('85',$users_data['permission']['section'])) 
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
               $row[] = $Sms->subject; 
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
               $data[] = $row;
            }
            elseif($Sms->form_name=='3' && in_array('151',$users_data['permission']['section'])) 
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
               $data[] = $row;
            }

             elseif($Sms->form_name=='4' && in_array('58',$users_data['permission']['section'])) 
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
              // $row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
               $data[] = $row;
            }

            elseif($Sms->form_name=='6' && in_array('60',$users_data['permission']['section'])) 
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
               $data[] = $row;
            }

            elseif($Sms->form_name=='17' && in_array('130',$users_data['permission']['section']))
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
               $data[] = $row;
            }

            elseif($Sms->form_name=='18' && in_array('19',$users_data['permission']['section']))
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
              // $row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
               $data[] = $row;
            }

            elseif($Sms->form_name=='22' && in_array('145',$users_data['permission']['section']))
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               
               $row[] = $Sms->subject; 
               //$row[] = $Sms->template; 
               
               //$row[] = date('d-M-Y H:i A',strtotime($Sms->created_date)); 
               
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
               $data[] = $row;
          }
          elseif($Sms->form_name=='24' && in_array('146',$users_data['permission']['section']))
          {
               if($users_data['parent_id']==$Sms->branch_id)
               { 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
               }
               else
               {
                    $row[]='';
               }

               $row[] = $Sms->module_name;
               $row[] = $Sms->subject; 
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
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
               $row[] = $Sms->subject; 
               if($users_data['parent_id']==$Sms->branch_id){
                    if(in_array('644',$users_data['permission']['action'])){
                         $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                    }
                    
               }
               
               $row[] = $btnedit.$btndelete;
               $data[] = $row;
            }
            elseif($Sms->form_name=='26' && in_array('121',$users_data['permission']['section'])) 
            {
                 if($users_data['parent_id']==$Sms->branch_id)
                 { 
                      $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Sms->id.'">'.$check_script;
                 }
                 else
                 {
                      $row[]='';
                 }
                  $row[] = $Sms->module_name;
                  $row[] = $Sms->subject; 
                  
                  if($users_data['parent_id']==$Sms->branch_id){
                      if(in_array('644',$users_data['permission']['action'])){
                           $btnedit = ' <a onClick="return edit_email_template('.$Sms->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Sms->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                      }
                      
                 }
                 
                 $row[] = $btnedit.$btndelete;
                 $data[] = $row;
            }

               
               $i++;
          }

           $output = array(
               "draw" => $_POST['draw'],
               "recordsTotal" => $this->email_templates->count_all(),
               "recordsFiltered" => $this->email_templates->count_filtered(),
               "data" => $data,
          );
           //output to json format
          
          echo json_encode($output);
     }

  public function add()
	{
      unauthorise_permission(102,643);
     
      $data_id='';
      $form_name='';
      $template='';
      $short_code='';
      $subject='';
  	
      $data['form_name']  = get_email_form_name();
  		$data['page_title'] = "Add Email Template";
  		$data['form_error'] = [];
  		$post = $this->input->post();
  		$data['form_data'] = array(
                                  "data_id"=>$data_id,
                                  "form_name"=>$form_name,
                                  "subject"=>$subject,
                                  "message"=>$template,
                                  "short_code"=>$short_code
  			                      );
      if(isset($post) && !empty($post))
      {  
        $data['form_data'] = $this->_validate();
        if($this->form_validation->run() == TRUE)
        {
              $this->email_templates->save();
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
       
		$this->load->view('email_template/add',$data);
	}

	public function edit($id="")
  {
     	
     unauthorise_permission(102,644);
     $data['form_name']  = get_email_form_name_edit();
     
     if(isset($id) && !empty($id) && is_numeric($id))
     {      
        $result = $this->email_templates->get_by_id($id);  
        $data['page_title'] = "Update Email Template";  
        $post = $this->input->post();
       //print_r($post); exit;
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                'data_id'=>$result['id'],
                                'type' =>$result['type'],
                                'form_name'=>$result['form_name'],
                                'subject' =>$result['subject'],
                                'message' =>$result['template'],
                                'short_code'=>$result['short_code']
                              );    
        
        if(isset($post) && !empty($post))
        {   
          	
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->email_templates->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       
       $this->load->view('email_template/add',$data);       
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



  public function email_template_dropdown()
  {
      $value= $this->input->post('value');
      $data_array= array('form_id'=>$value);
      $opd_list = $this->email_templates->template_list($data_array);
      $data['subject']= $opd_list['subject'];
      $data['template']= $opd_list['template'];
      $data['short_code']= $opd_list['short_code'];
      echo json_encode($data);
  }

 } 
 ?>