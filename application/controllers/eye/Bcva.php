<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bcva extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('eye/bcva/bcva_model','bcvamodel');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('242','1384');
        $data['page_title'] = 'BCVA Manage List'; 
        $this->load->view('eye/bcva/list',$data);
    }

    public function add()
    {
        unauthorise_permission('242','1385');
        $data['sphere_plus_data']=$this->bcvamodel->get_sphere_plus_data(0);
        $data['sphere_plus_data_last_row']=$this->bcvamodel->get_sphere_plus_data(1);

        $data['sphere_minus_data']=$this->bcvamodel->get_sphere_minus_data(0);
        $data['sphere_minus_data_last_row']=$this->bcvamodel->get_sphere_minus_data(1);


        $data['cylinder_plus_data']=$this->bcvamodel->get_cylinder_plus_data(0);
        $data['cylinder_plus_data_last_row']=$this->bcvamodel->get_cylinder_plus_data(1);

        $data['cylinder_minus_data']=$this->bcvamodel->get_cylinder_minus_data(0);
        $data['cylinder_minus_data_last_row']=$this->bcvamodel->get_cylinder_minus_data(1);


        $data['add_data']=$this->bcvamodel->get_add_data(0);
        $data['add_data_last_row']=$this->bcvamodel->get_add_data(1);

        $data['axis_data']=$this->bcvamodel->get_axis_data(0);
        $data['axis_data_last_row']=$this->bcvamodel->get_axis_data(1);

        $data['dva_data']=$this->bcvamodel->get_dva_data(0);
        $data['dva_data_last_row']=$this->bcvamodel->get_dva_data(1);

        $data['nva_data']=$this->bcvamodel->get_nva_data(0);
        $data['nva_data_last_row']=$this->bcvamodel->get_nva_data(1);

        $data['page_title'] = "BCVA MASTER";  
        $this->load->view('eye/bcva/add',$data);       
    }
    
    public function save()
    {
      unauthorise_permission('242','1385');
      $users_data = $this->session->userdata('auth_users');

      if(!empty($this->input->post('sphere_plus')))
      {
        $sphere_plus=$this->input->post('sphere_plus');
        $sphere_plus_array=array();
        $cnt=count($sphere_plus);
        $this->bcvamodel->delete_sphere_plus_branch_data();
        for($i=1;$i<=$cnt;$i++)
        {   
            $cnt_j=count($sphere_plus[$i]);
            for($j=1;$j<=$cnt_j;$j++)
            {
              $sphere_plus_array['address_i']=$i;
              $sphere_plus_array['address_j']=$j;
              $sphere_plus_array['branch_id']=$users_data['parent_id'];
              $sphere_plus_array['value']=$sphere_plus[$i][$j];
              $sphere_plus_array['created_date']=date('Y-m-d H:i:s');
              $this->bcvamodel->save_sphere_plus_branch_data($sphere_plus_array);
            }  
        }
      }


      if(!empty($this->input->post('sphere_minus')))
      {
        $sphere_minus=$this->input->post('sphere_minus');
        $sphere_minus_array=array();
        $cnt=count($sphere_minus);
        $this->bcvamodel->delete_sphere_minus_branch_data();
        for($i=1;$i<=$cnt;$i++)
        {   
            $cnt_j=count($sphere_minus[$i]);
            for($j=1;$j<=$cnt_j;$j++)
            {
              $sphere_minus_array['address_i']=$i;
              $sphere_minus_array['address_j']=$j;
              $sphere_minus_array['branch_id']=$users_data['parent_id'];
              $sphere_minus_array['value']=$sphere_minus[$i][$j];
              $sphere_minus_array['created_date']=date('Y-m-d H:i:s');
              $this->bcvamodel->save_sphere_minus_branch_data($sphere_minus_array);
            }  
        }
      }


      if(!empty($this->input->post('cylinder_plus')))
      {
        $cylinder_plus=$this->input->post('cylinder_plus');
        $cylinder_plus_array=array();
        $cnt=count($cylinder_plus);
        $this->bcvamodel->delete_cylinder_plus_branch_data();
        for($i=1;$i<=$cnt;$i++)
        {   
            $cnt_j=count($cylinder_plus[$i]);
            for($j=1;$j<=$cnt_j;$j++)
            {
              $cylinder_plus_array['address_i']=$i;
              $cylinder_plus_array['address_j']=$j;
              $cylinder_plus_array['branch_id']=$users_data['parent_id'];
              $cylinder_plus_array['value']=$cylinder_plus[$i][$j];
              $cylinder_plus_array['created_date']=date('Y-m-d H:i:s');
              $this->bcvamodel->save_cylinder_plus_branch_data($cylinder_plus_array);
            }  
        }
      }


      if(!empty($this->input->post('cylinder_minus')))
      {
        $cylinder_minus=$this->input->post('cylinder_minus');
        $cylinder_minus_array=array();
        $cnt=count($cylinder_minus);
        $this->bcvamodel->delete_cylinder_minus_branch_data();
        for($i=1;$i<=$cnt;$i++)
        {   
            $cnt_j=count($cylinder_minus[$i]);
            for($j=1;$j<=$cnt_j;$j++)
            {
              $cylinder_minus_array['address_i']=$i;
              $cylinder_minus_array['address_j']=$j;
              $cylinder_minus_array['branch_id']=$users_data['parent_id'];
              $cylinder_minus_array['value']=$cylinder_minus[$i][$j];
              $cylinder_minus_array['created_date']=date('Y-m-d H:i:s');
              $this->bcvamodel->save_cylinder_minus_branch_data($cylinder_minus_array);
            }  
        }
      }

      if(!empty($this->input->post('add')))
      {
        $add=$this->input->post('add');
        $add_array=array();
        $cnt=count($add);
        $this->bcvamodel->delete_add_branch_data();
        for($i=1;$i<=$cnt;$i++)
        {   
            $cnt_j=count($add[$i]);
            for($j=1;$j<=$cnt_j;$j++)
            {
              $add_array['address_i']=$i;
              $add_array['address_j']=$j;
              $add_array['branch_id']=$users_data['parent_id'];
              $add_array['value']=$add[$i][$j];
              $add_array['created_date']=date('Y-m-d H:i:s');
              $this->bcvamodel->save_add_branch_data($add_array);
            }  
        }
      }


       if(!empty($this->input->post('axis')))
      {
        $axis=$this->input->post('axis');
        $axis_array=array();
        $cnt=count($axis);
        $this->bcvamodel->delete_axis_branch_data();
        for($i=1;$i<=$cnt;$i++)
        {   
            $cnt_j=count($axis[$i]);
            for($j=1;$j<=$cnt_j;$j++)
            {
              $axis_array['address_i']=$i;
              $axis_array['address_j']=$j;
              $axis_array['branch_id']=$users_data['parent_id'];
              $axis_array['value']=$axis[$i][$j];
              $axis_array['created_date']=date('Y-m-d H:i:s');
              $this->bcvamodel->save_axis_branch_data($axis_array);
            }  
        }
      }

      if(!empty($this->input->post('dva')))
      {
        $dva=$this->input->post('dva');
        $dva_array=array();
        $cnt=count($dva);
        $this->bcvamodel->delete_dva_branch_data();
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
              $this->bcvamodel->save_dva_branch_data($dva_array);
            }  
        }
      }

      if(!empty($this->input->post('nva')))
      {
        $nva=$this->input->post('nva');
        $nva_array=array();
        $cnt=count($nva);
        $this->bcvamodel->delete_nva_branch_data();
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
              $this->bcvamodel->save_nva_branch_data($nva_array);
            }  
        }
      }
      return "200";
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
          echo json_encode(array("right"=>$right_dva, "left"=>0 )); 
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

    public function set_sph()
    {   
      $right_sph="";
      $left_sph="";
      if($this->input->post('block')=='sph')
      { 
        if($this->input->post('select_val')==1)
        {
          $right_sph=$this->input->post('value');
          $this->session->set_userdata('sph_val_right',$right_sph);
          echo json_encode(array("right"=>$right_sph, "left"=>0 )); 
        }
        else if($this->input->post('select_val')==2)
        {
          $left_sph=$this->input->post('value');
          $this->session->set_userdata('sph_val_left',$left_sph);
          echo json_encode(array("right"=>0, "left"=>$left_sph));
        }
        else if($this->input->post('select_val')==3)
        {
          $right_sph=$this->input->post('value');
          $left_sph=$this->input->post('value');
          $this->session->set_userdata('sph_val_right',$right_sph);
          $this->session->set_userdata('sph_val_left',$left_sph);
          echo json_encode(array("right"=>$right_sph, "left"=>$left_sph));
        }
      }
    }

    public function set_cyl()
    {   
      $right_cyl="";
      $left_cyl="";
      if($this->input->post('block')=='cyl')
      { 
        if($this->input->post('select_val')==1)
        {
          $right_cyl=$this->input->post('value');
          $this->session->set_userdata('cyl_val_right',$right_cyl);
          echo json_encode(array("right"=>$right_cyl, "left"=>0 )); 
        }
        else if($this->input->post('select_val')==2)
        {
          $left_cyl=$this->input->post('value');
          $this->session->set_userdata('cyl_val_left',$left_cyl);
          echo json_encode(array("right"=>0, "left"=>$left_cyl));
        }
        else if($this->input->post('select_val')==3)
        {
          $right_cyl=$this->input->post('value');
          $left_cyl=$this->input->post('value');
          $this->session->set_userdata('cyl_val_right',$right_cyl);
          $this->session->set_userdata('cyl_val_left',$left_cyl);
          echo json_encode(array("right"=>$right_cyl, "left"=>$left_cyl));
        }
      }
    }


    public function set_add()
    {   
      $right_add="";
      $left_add="";
      if($this->input->post('block')=='add')
      { 
        if($this->input->post('select_val')==1)
        {
          $right_add=$this->input->post('value');
          $this->session->set_userdata('add_val_right',$right_add);
          echo json_encode(array("right"=>$right_add, "left"=>0 )); 
        }
        else if($this->input->post('select_val')==2)
        {
          $left_add=$this->input->post('value');
          $this->session->set_userdata('add_val_left',$left_add);
          echo json_encode(array("right"=>0, "left"=>$left_add));
        }
        else if($this->input->post('select_val')==3)
        {
          $right_add=$this->input->post('value');
          $left_add=$this->input->post('value');
          $this->session->set_userdata('add_val_right',$right_add);
          $this->session->set_userdata('add_val_left',$left_add);
          echo json_encode(array("right"=>$right_add, "left"=>$left_add));
        }
      }
    }

    public function set_axis()
    {   
      $right_axis="";
      $left_axis="";
      if($this->input->post('block')=='axis')
      { 
        if($this->input->post('select_val')==1)
        {
          $right_axis=$this->input->post('value');
          $this->session->set_userdata('axis_val_right',$right_axis);
          echo json_encode(array("right"=>$right_axis, "left"=>0 )); 
        }
        else if($this->input->post('select_val')==2)
        {
          $left_axis=$this->input->post('value');
          $this->session->set_userdata('axis_val_left',$left_axis);
          echo json_encode(array("right"=>0, "left"=>$left_axis));
        }
        else if($this->input->post('select_val')==3)
        {
          $right_axis=$this->input->post('value');
          $left_axis=$this->input->post('value');
          $this->session->set_userdata('axis_val_right',$right_axis);
          $this->session->set_userdata('axis_val_left',$left_axis);
          echo json_encode(array("right"=>$right_axis, "left"=>$left_axis));
        }
      }
    }

    public function set_values_for_prescription()
    {
      $nva_left=$this->input->post('nva_left');
      $nva_right=$this->input->post('nva_right');
      $dva_left=$this->input->post('dva_left');
      $dva_right=$this->input->post('dva_right');
      $add_right=$this->input->post('add_right');
      $add_left=$this->input->post('add_left');
      $axis_left=$this->input->post('axis_left');
      $axis_right=$this->input->post('axis_right');
      $cyl_right=$this->input->post('cyl_right');
      $cyl_left=$this->input->post('cyl_left');
      $sph_right=$this->input->post('sph_right');
      $sph_left=$this->input->post('sph_left');
        $data_array=array(
                        "dva_right"=>$dva_right,
                        "dva_left"=>$dva_left,
                        "nva_right"=>$nva_right,
                        "nva_left"=>$nva_left, 
                        "add_right"=>$add_right,
                        "add_left"=>$add_left,
                        "axis_left"=>$axis_left,
                        "axis_right"=>$axis_right,
                        "cyl_right"=>$cyl_right,
                        "cyl_left"=>$cyl_left,
                        "sph_right"=>$sph_right,
                        "sph_left"=>$sph_left,  
                      );
        $this->session->set_userdata('values_for_prescription',$data_array);
        echo json_encode(array(
                        "dva_right"=>$dva_right,
                        "dva_left"=>$dva_left,
                        "nva_right"=>$nva_right,
                        "nva_left"=>$nva_left, 
                        "add_right"=>$add_right,
                        "add_left"=>$add_left,
                        "axis_left"=>$axis_left,
                        "axis_right"=>$axis_right,
                        "cyl_right"=>$cyl_right,
                        "cyl_left"=>$cyl_left,
                        "sph_right"=>$sph_right,
                        "sph_left"=>$sph_left,  
                      ) );
        
    }


}
?>