<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ucva extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('eye/ucva/ucva_model','ucvamodel');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('241','1377');
        $data['page_title'] = 'UCVA Manage List'; 
        $this->load->view('eye/ucva/list',$data);
    }

    public function add()
    {
        unauthorise_permission('241','1378');
        $data['dva_data']=$this->ucvamodel->get_dva_data(0);
        $data['dva_data_last_row']=$this->ucvamodel->get_dva_data(1);

        $data['nva_data']=$this->ucvamodel->get_nva_data(0);
        $data['nva_data_last_row']=$this->ucvamodel->get_nva_data(1);

        $data['page_title'] = "UCVA MASTER"; 
        $this->load->view('eye/ucva/add',$data);       
    }
    
    public function save()
    {
      unauthorise_permission('241','1378');
      $users_data = $this->session->userdata('auth_users');
      if(!empty($this->input->post('dva')))
      {
        $dva=$this->input->post('dva');
        $dva_array=array();
        $cnt=count($dva);
        $this->ucvamodel->delete_dva_branch_data();
        for($i=1;$i<=$cnt;$i++)
        {   
            $cnt_j=count($dva[$i]);
            for($j=1;$j<=$cnt_j;$j++)
            {
              $dva_array['address_i']=$i;
              $dva_array['address_j']=$j;
              $dva_array['branch_id']=$users_data['parent_id'];
              $dva_array['value']=$dva[$i][$j];
              $dva_array['created_date']=date('Y-m-d H:i:s');
              $this->ucvamodel->save_dva_branch_data($dva_array);
            }  
        }
      }


      if(!empty($this->input->post('nva')))
      {
        $nva=$this->input->post('nva');
        $nva_array=array();
        $cnt=count($nva);
        $this->ucvamodel->delete_nva_branch_data();
        for($i=1;$i<=$cnt;$i++)
        {   
            $cnt_j=count($nva[$i]);
            for($j=1;$j<=$cnt_j;$j++)
            {
              $nva_array['address_i']=$i;
              $nva_array['address_j']=$j;
              $nva_array['branch_id']=$users_data['parent_id'];
              $nva_array['value']=$nva[$i][$j];
              $nva_array['created_date']=date('Y-m-d H:i:s');
              $this->ucvamodel->save_nva_branch_data($nva_array);
            }  
        }
      }
    }

    public function set_nva()
    {   
      $right_nva="";
      $left_nva="";
      if($this->input->post('block')=='nva')
      { 
        if($this->input->post('select_val')==1)
        {
          $right_nva=$this->input->post('value');
          $this->session->set_userdata('nva_val_right',$right_nva);
          echo json_encode(array("right"=>$right_nva, "left"=>0));
        }
        else if($this->input->post('select_val')==2)
        {
          $left_nva=$this->input->post('value');
          $this->session->set_userdata('nva_val_left',$left_nva);
          echo json_encode(array("right"=>0, "left"=>$left_nva));
        }
        else if($this->input->post('select_val')==3)
        {
          $right_nva=$this->input->post('value');
          $left_nva=$this->input->post('value');
          $this->session->set_userdata('nva_val_right',$right_nva);
          $this->session->set_userdata('nva_val_left',$left_nva);
          echo json_encode(array("right"=>$right_nva, "left"=>$left_nva));
        }
      }
    }

    public function set_dva()
    {   
      $right_dva="";
      $left_dva="";
      if($this->input->post('block')=='dva')
      { 
        if($this->input->post('select_val')==1)
        {
          $right_dva=$this->input->post('value');
          $this->session->set_userdata('dva_val_right',$right_dva);
          echo json_encode(array("right"=>$right_dva, "left"=>0)); 
        }
        else if($this->input->post('select_val')==2)
        {
          $left_dva=$this->input->post('value');
          $this->session->set_userdata('dva_val_left',$left_dva);
          echo json_encode(array("right"=>0, "left"=>$left_dva));
        }
        else if($this->input->post('select_val')==3)
        {
          $right_dva=$this->input->post('value');
          $left_dva=$this->input->post('value');
          $this->session->set_userdata('dva_val_right',$right_dva);
          $this->session->set_userdata('dva_val_left',$left_dva);
          echo json_encode(array("right"=>$right_dva, "left"=>$left_dva));
        }
      }
    }

    public function set_value_for_prescription()
    {
      $nva_left=$this->input->post('nva_left');
      $nva_right=$this->input->post('nva_right');
      $dva_left=$this->input->post('dva_left');
      $dva_right=$this->input->post('dva_right');
      $data_array=array(
                        "dva_right"=>$dva_right,
                        "dva_left"=>$dva_left,
                        "nva_right"=>$nva_right,
                        "nva_left"=>$nva_left, 
                        );
      $this->session->set_userdata('values_for_prescription_ucva',$data_array);
      echo json_encode(array(
                        "dva_right"=>$dva_right,
                        "dva_left"=>$dva_left,
                        "nva_right"=>$nva_right,
                        "nva_left"=>$nva_left,
                        ));
      



    }
    
// Please write code above
}
?>