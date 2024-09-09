<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Path_profile_panel_price extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('path_profile_panel_price/path_profile_panel_price_model','path_profile_panel_price');
        $this->load->library('form_validation');
    }

    public function index()
    { 
      //echo "Test";die;
       // unauthorise_permission(217,1228);
        $data['page_title'] = 'Profile Panel Price'; 
        $data['form_data']=array('data_id'=>'');
        $this->load->view('path_profile_panel_price/path_profile_panel_price_list',$data);
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
				$dropdown = '<label class="patient_sub_branch_label">Select Panel</label> <select id="paneln_ids"  onchange="get_profile_list(this.value);"><option value="">Select Panel</option>';
			 foreach($data['insurance_company_list'] as $company_list)
                              {
                               $dropdown.='<option value="'.$company_list->id.'">'.$company_list->insurance_company.'</option>'; 
                              }	
                              $dropdown.='</select>';			

			}
			echo $dropdown;
	}

     public function get_profile_list()
     {
          $post = $this->input->post(); 
          $data['page_title'] = 'Profile Pannel Price'; 
          $formated_data='';
          if(!empty($post['doctors_id']) || !empty($post['branch_id']))
          {
              $result = $this->path_profile_panel_price->get_profile_list(); 
              if(!empty($result))
              { 
                 $formated_data = $this->get_format_table($result,$post); 
              }
              else
              {
                $formated_data='<tr><td colspan="6" align="center"><font color="#b64a30">Profile not available.</font></td></tr>';
              }
          }
          else
          {
             $formated_data='<tr><td colspan="6" align="center"><font color="#b64a30">Profile not available.</font></td></tr>';
          } 
          echo $formated_data;
     }


    public function path_ajax_list()
    {  
     
      //  $test_master_head = $this->session->userdata('master_test_head');
      //  print_r()
        $users_data = $this->session->userdata('auth_users');
        $list = [];
         $paneln_ids='';
         //$dept_ids='';

        // if(isset($test_master_head) && !empty($test_master_head))
        // {
          $list = $this->path_profile_panel_price->get_datatables('');  
         //echo "<pre>" ;print_r($list);die;
        //}
        if(!empty($_POST['paneln_ids']) && isset($_POST['paneln_ids']))
        {
          $paneln_ids= $_POST['paneln_ids'];
        }
    
       // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $btn_save='';
        $total_num = count($list);
        foreach ($list as $profile_master) {
          
            $no++;
            $row = array(); 
             $row[] = '<input type="checkbox" name="profile_id['.$i.'][profile_id]" class="checklist" value="'.$profile_master->id.'">';
            $row[] = $profile_master->profile_name;   
            $row[] = '<input type="hidden" name="paneln_ids" id="panel_id" value="'.$paneln_ids.'"/> <input type="text" name="profile_id['.$i.'][master_rate]"  id="master_price_'.$i.'"  value="'.$profile_master->master_rate.'"/>';
            $row[] = '<input type="hidden" name="paneln_ids" id="panel_id" value="'.$paneln_ids.'"/> <input type="text" name="profile_id['.$i.'][base_rate]"  id="base_price_'.$i.'"  value="'.$profile_master->base_rate.'"/>';
               if(in_array('1229',$users_data['permission']['action']))
               {
                    $btn_save='<button type="button" class="btn-custom" data-paneln_ids="paneln_ids"  data-id="'.$i.'"
                  data-profile_id="'.$profile_master->id.'" id="save_profile" onclick="save_panel_rate(this);">Save</button></a>
                ';
               } 
               
                      $row[] = $btn_save;
                              
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                       "recordsTotal" => $this->path_profile_panel_price->count_all('',$profile_id),
                        "recordsFiltered" => $this->path_profile_panel_price->count_filtered(),
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
         // print_r($post);die;
          $data['page_title'] = " Profile Panel Price";
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
              $this->path_profile_panel_price->save_panel_rate();
              $msg=2;
            }
          	
          }
         echo $msg;

     }

  public function save_price_list()
  {
    $post = $this->input->post();
   if(isset($post) )
    {  
      $msg='';
      if(empty($post['paneln_ids']))
      {
       // echo 1;die;
          // $this->session->set_flashdata('error','Please select panel.');
           $msg='1';
      }
      else if(empty($post['profile_id']))
      {
       // echo 1;die;
          // $this->session->set_flashdata('error','Please select panel.');
           $msg='1';
      }
      else
      {
        $this->path_profile_panel_price->save_panel_all_rate();
        $msg= 2;
      }
       
    }
      echo $msg;
  } 

   
 
}
?>