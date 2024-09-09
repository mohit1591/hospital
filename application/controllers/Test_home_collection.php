<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_home_collection extends CI_Controller 
{

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('test_home_collection/test_home_collection_model','test_home_model');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission(221,1249);
        $data['page_title'] = 'Test Home Collection'; 
        $data['form_data']=array('data_id'=>'');
        $this->load->view('test_home_collection/list',$data);
    }


    public function ajax_list()
    { 
        unauthorise_permission(221,1249);
        $users_data = $this->session->userdata('auth_users');
        $list = $this->test_home_model->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $records) {
         
            $no++;
            $row = array();
            if($records->status==1)
            {
                $status = '<font color="green">Enable</font>';
            }   
            else{
                $status = '<font color="red">Disable</font>';
            }
            $row[] = $status;  
            $row[] = $records->charge; 
            $row[] = date('d-M-Y H:i A',strtotime($records->created_date));
            $btnedit='';
            $btndelete='';
            if($users_data['parent_id']==$records->branch_id)
            {
                if(in_array('829',$users_data['permission']['action']))
                {
                    $btnedit =' <a onClick="return edit_record('.$records->id.');" class="btn-custom" href="javascript:void(0)" style="'.$records->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                }
            }
            $row[] = $btnedit;
            $data[] = $row;
            $i++;
        }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_home_model->count_all(),
                        "recordsFiltered" => $this->test_home_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    public function edit($id)
    {
        unauthorise_permission(221,1248);
        $record_data=$this->test_home_model->get_by_id($id);
        $data['record_data']=$record_data;
        $data['page_title']='Edit Test Home Collection';
        $this->load->view('test_home_collection/edit',$data);
    }

    public function save()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  

        if($this->input->post('status')==1)
        {
            $this->form_validation->set_rules('charge', 'Charge', 'trim|required|is_numeric');
        }
         $this->form_validation->set_rules('status', 'Status', 'trim|required');
        if($this->form_validation->run()==FALSE) 
        {  
            echo json_encode(array('st'=>0, 'charge_error'=>form_error('charge') )); 
        }
        else
        {
            if($this->input->post('status')==1)
                $charge=$this->input->post('charge');
            else
                $charge="0.00";   
            $data_array=array('status'=>$this->input->post('status'),
                              'charge'=>$charge,
                              'modified_by'=>$users_data['id'],
                             );
            $result=$this->test_home_model->update_home_collection($data_array, $this->input->post('rec_id'));
            if($result=="200")
                    echo json_encode(array('st'=>1, 'message'=>'Record updated Successfully.'));
                else
                    echo json_encode(array('st'=>2, 'message'=>'Please Try after sometime.'));


        }    
    }


//  Please write code above
}