<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Balance_clearance_history extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('balance_clearance_history/balance_clearance_history_model','balance_clearance_history');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission(39,231);
        $data['page_title'] = 'Balance Clearance History List'; 
        $data['form_data'] = array(
                                   'start_date'=>date('d-m-Y'), 
                                   'end_date'=>date('d-m-Y')
                                 );
        $this->load->view('balance_clearance_history/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(39,231);
         $users_data = $this->session->userdata('auth_users');

        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
     
            $list = $this->balance_clearance_history->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $balance) 
        { 
            $no++;
            $row = array();      
            
            $row[] = $i;
            $row[] = $balance->patient_code; 
            $row[] = $balance->patient_name;  
            $row[] = $balance->mobile_no;  
            $row[] = date('d-m-Y', strtotime($balance->created_date));  
            $row[] = $balance->pay_mode;  
            $row[] = $balance->debit;   
            $row[] = ' <a class="btn-custom" href="javascript:void(0);" onclick="return update_payment('.$balance->id.');"><i class="fa fa-pencil"></i> Edit Payment </a>  ';
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->balance_clearance_history->count_all(),
                        "recordsFiltered" => $this->balance_clearance_history->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
     

    public function edit($id="")
    {
     unauthorise_permission(39,232);
     if(isset($id) && !empty($id) && is_numeric($id))
      {     
        $result = $this->balance_clearance_history->get_by_id($id);  
        $data['balance_data'] = $result;
        $data['payment_mode_list'] = $this->balance_clearance_history->payment_mode_list($id);  
        $data['page_title'] = "Update Balance Clearance";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'amount'=>$result['debit'],
                                  'pay_mode'=>$result['pay_mode'], 
                                  'status'=>$result['status']
                                
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->balance_clearance_history->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('balance_clearance_history/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('pay_mode', 'payment mode', 'trim|required'); 
        $this->form_validation->set_rules('amount', 'amount', 'numeric|trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'pay_mode'=>$post['pay_mode'], 
                                        'amount'=>$post['amount'] 
                                       
                                       ); 
            return $data['form_data'];
        }   
    }

   public function advance_search()
    { 
        $post = $this->input->post(); 
        if(isset($post) && !empty($post))
        { 
            $this->session->set_userdata('balance_search', $post);
        } 
    }

    public function reset_search()
    {
        $this->session->unset_userdata('balance_search');
    }
 
     

}
?>