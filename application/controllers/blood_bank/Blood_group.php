<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Blood_group extends CI_Controller 
{
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
    $this->load->model('blood_bank/blood_group/blood_group_model','blood_group'); 
    $this->load->library('form_validation');
  }

  public function index()
  {
    //unauthorise_permission(93,594);
    $data['page_title'] = 'Blood Group'; 
    $this->load->view('blood_bank/blood_group/list',$data);
  }

  // Function to show list of blood Groups starts here
  public function ajax_list()
  {
    //unauthorise_permission('69','432');
    $users_data = $this->session->userdata('auth_users');
    $list = $this->blood_group->get_datatables();  
    $data = array();
    $no = $_POST['start'];
    $i = 1;
    $total_num = count($list);
    foreach ($list as $blood_group) 
    {
      $no++;
        $row = array();
        if($blood_group->status==1)
        {
            $status = '<font color="green">Active</font>';
        }   
        else{
            $status = '<font color="red">Inactive</font>';
        } 
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
        ////////// Check list end ///////////// 
        $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$blood_group->id.'">'.$check_script; 
        $row[] = $blood_group->blood_group;  
        $row[] = $status;
        $row[] = date('d-M-Y H:i A',strtotime($blood_group->created_date)); 
       
      $btnedit='';
      $btndelete='';
      //if(in_array('434',$users_data['permission']['action'])){
           $btnedit = ' <a onClick="return edit_chief_complaints('.$blood_group->id.');" class="btn-custom" href="javascript:void(0)" style="'.$blood_group->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
      //}
       //if(in_array('435',$users_data['permission']['action'])){
           $btndelete = ' <a class="btn-custom" onClick="return delete_chief_complaints('.$blood_group->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
      //}
      $row[] = $btnedit.$btndelete;
      $data[] = $row;
      $i++;
    }

    $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->blood_group->count_all(),
                    "recordsFiltered" => $this->blood_group->count_filtered(),
                    "data" => $data,
            );
    echo json_encode($output);
  }
  // Function to show list of blood Groups Ends here 

  // Function to open add form starts here
  Public function add()
  {
    $data['page_title']="Add Blood Group";
    $this->load->view('blood_bank/blood_group/add',$data);
  }
  // function to open add form ends here
      
// Please write code above this
}
?>
