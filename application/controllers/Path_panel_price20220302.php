<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Path_panel_price extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('path_panel_price/path_panel_price_model','path_panel_price');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission(217,1228);
        $this->session->unset_userdata('panel_test_search');
        $this->session->unset_userdata('master_test_head');
        $data['page_title'] = 'Panel Price'; 
        $data['form_data']=array('data_id'=>'');
        $this->load->view('path_panel_price/path_panel_price_list',$data);
    }

 

   public function get_all_departments_list()
   {
       $this->load->model('path_panel_price/Path_panel_price_model','path_panel_price');
       $dropdown='';
        $this->load->model('general/general_model'); 
       $result = $this->path_panel_price->get_test_departments();
       if(!empty($result)){
           $dropdown = '<label class="patient_sub_branch_label">Department</label> <select id="department_id" onchange = "get_test_heads(this.value);"><option value="">Select Department</option>';
          for($i=0;$i<count($result);$i++){
                $dropdown .= '<option value="'.$result[$i]->id.'">'.$result[$i]->department.'</option>';
                   
          }
          $dropdown.='</select>';
         
       }
       echo $dropdown;
   }
   public function get_test_heads_list()
   {
     $department_id = $this->input->post('department_id');
     $dropdown='';
      $this->load->model('general/general_model'); 
     if(!empty($department_id)){
          $this->load->model('path_panel_price/Path_panel_price_model','path_panel_price');
          $result = $this->path_panel_price->get_test_heads($department_id);
          if(!empty($result)){
               $dropdown = '<label class="patient_sub_branch_label">Test Heads</label> <select id="test_heads_id" onchange="set_test_head(this.value);"><option value="">Select Test Heads</option>';
               for($i=0;$i<count($result);$i++){
                    $dropdown .= '<option value="'.$result[$i]->id.'">'.$result[$i]->test_heads.'</option>';
               }
               $dropdown.='</select>';
               
          }
     }
     echo $dropdown;
     }
	public function set_test_head($head_id="")
	{

		if(!empty($head_id))
		{
		$this->session->set_userdata('master_test_head',$head_id);
		}
		else
		{
		$this->session->unset_userdata('master_test_head');
		}
	}

  public function set_panel_test_search()
  {
    $post = $this->input->post();
    if(!empty($post['test_name']))
    { 
        $this->session->set_userdata('panel_test_search',$post['test_name']);
    } 
  }

	public function get_panel_list()
	{
	 $dropdown='';
	 //$this->session->unset_userdata('master_test_head');
	 $users_data = $this->session->userdata('auth_users');
	 $this->load->model('general/general_model'); 
	 $post = $this->input->post();
		$this->load->model('path_panel_price/Path_panel_price_model','path_panel_price');
		$data['insurance_company_list'] = $this->general_model->insurance_company_list($users_data['parent_id']);
		if(!empty($data['insurance_company_list'])){
				$dropdown = '<label class="patient_sub_branch_label">Select Panel</label> <select id="paneln_ids" onchange="get_test_list(this.value);"><option value="">Select Panel</option>';
			 foreach($data['insurance_company_list'] as $company_list)
                              {
                               $dropdown.='<option value="'.$company_list->id.'">'.$company_list->insurance_company.'</option>'; 
                              }	
                              $dropdown.='</select>';			

			}
			echo $dropdown;
	}

     public function get_test_list()
     {
          $post = $this->input->post(); 
          $data['page_title'] = 'Pannel Price'; 
          $formated_data='';
          if(!empty($post['doctors_id']) || !empty($post['branch_id']))
          {
              $result = $this->path_panel_price->get_test_list(); 
              if(!empty($result))
              { 
                 $formated_data = $this->get_format_table($result,$post); 
              }
              else
              {
                $formated_data='<tr><td colspan="6" align="center"><font color="#b64a30">Test not available.</font></td></tr>';
              }
          }
          else
          {
             $formated_data='<tr><td colspan="6" align="center"><font color="#b64a30">Test not available.</font></td></tr>';
          } 
          echo $formated_data;
     }


    public function path_ajax_list($test_id="")
    {  
     
        $test_master_head = $this->session->userdata('master_test_head');
        $users_data = $this->session->userdata('auth_users');
        $list = [];
        $paneln_ids='';
        $dept_ids='';
        $panel_test_search = $this->session->userdata('panel_test_search');

        if(isset($test_master_head) && !empty($test_master_head))
        {
          $list = $this->path_panel_price->get_datatables('',$test_id);  
        }
        else if(!empty($panel_test_search))
        {
           $list = $this->path_panel_price->get_datatables();  
        }
        if(!empty($_POST['paneln_ids']) && isset($_POST['paneln_ids']))
        {
          $paneln_ids= $_POST['paneln_ids'];
        }
        if(!empty($_POST['dept_id']) && isset($_POST['dept_id']))
        {
          $dept_ids= $_POST['dept_id'];
        }
       // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $btn_save='';
        $total_num = count($list);
        foreach ($list as $test_master) {
         // print_r($simulation);die;
            $no++;
            $row = array(); 
            $dept_id = "'".$test_master->dept_id."'";
            $head_id = "'".$test_master->test_head_id."'"; 
             $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test_master->id.'">';
            $row[] = $test_master->department;  
            $row[] = $test_master->test_name;     
            $row[] = '
                    <input type="hidden" name="test_heads_id" id="test_heads_id" value="'.$test_master->test_head_id.'"/>
                    <input type="hidden" name="dept_id" id="dept_id" value="'.$test_master->dept_id.'"/>
                    <input type="hidden" name="paneln_ids" id="panel_id" value="'.$paneln_ids.'"/> 
                    <input type="hidden" name="test_id['.$i.'][test_id]"  id=""  value="'.$test_master->id.'"/>
                    <input type="text" name="test_id['.$i.'][path_price]"  id="price_'.$i.'"  value="'.$test_master->path_price.'"/>';
                if(in_array('1229',$users_data['permission']['action']))
                {
                    $btn_save='<button type="button" class="btn-custom" data-test_heads_id="test_heads_id_'.$i.'"  
                data-price="price_'.$i.'" data-testid="'.$test_master->id.'" data-docid="" id="save_test" onclick="save_panel_rate(this, '.$dept_id.', '.$head_id.');">Save</button></a>
                ';
                }
               
                      $row[] = $btn_save;
                              
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->path_panel_price->count_all('',$test_id),
                        "recordsFiltered" => $this->path_panel_price->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
          
    } 

    
    

     private function calc_string( $mathString )
      {
              $cf_DoCalc = create_function("", "return (" . $mathString . ");" );
             
              return $cf_DoCalc();
      }

     public function save_panel_rate()
     {

          $post = $this->input->post();
          $data['page_title'] = "Panel Price";
          $msg='';
          if(isset($post) && !empty($post))
          { 
            if(empty($post['paneln_ids']))
            {
                 //$this->session->set_flashdata('error','Please select panel.');
                 $msg=1;
            }
            else
            {
              $this->path_panel_price->save_panel_rate();
              $msg=2;
            }
          	
          }
         echo $msg;

     }

  public function save_price_list()
  {
    $post = $this->input->post();
   if(isset($post) && isset($post['test_heads_id']) && !empty($post['test_heads_id']))
    {  
      $msg='';
      if(empty($post['paneln_ids']))
      {
           //$this->session->set_flashdata('error','Please select panel.');
           $msg=1;
      }
      else
      {
        $this->path_panel_price->save_panel_all_rate();
        $msg=2;
      }
       
    }
      echo $msg;
  } 

   
 
}
?>