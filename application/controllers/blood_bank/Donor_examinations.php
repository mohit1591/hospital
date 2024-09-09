<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Donor_examinations extends CI_Controller 
{
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
    $this->load->model('blood_bank/donor/donor_examinations_model','donor_examination'); 
    $this->load->model('blood_bank/donor/donor_model','donor');
    $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    $this->load->library('form_validation');
  }

  public function add($donor_id)
  {
    unauthorise_permission(265,1541);

    $this->session->unset_userdata('sess_examin_id');
    $this->session->unset_userdata('sess_blood_detail_id');
    $this->session->unset_userdata('sess_qc_rec_id');
 
    if($donor_id!="")
    {
      $data['donor_data']   = $this->donor->get_by_id($donor_id);
      $donor_id=$data['donor_data']['id'];
      $data['donor_id']     = $donor_id;
      $data['page_title'] = 'Donor Examination Form'; 
      
      $examination_data=$this->donor_examination->examination_data('',$donor_id);
      if($examination_data!="empty")
      {
        $data['examination_data']=$examination_data;
        $data['examination_id']=$examination_data['id'];
      }
      else
      {
        $data['examination_data']="empty";
        $data['examination_id']=0;
      }

      $blood_detail_data=$this->donor_examination->blood_details_data('',$donor_id);
      if($blood_detail_data!="empty")
      {
        $data['blood_details']=$blood_detail_data;
        $data['blood_detail_id']=$blood_detail_data['id'];
      }
      else
      {
        $data['blood_details']="empty";  
        $data['blood_detail_id']=0;
      }
    
      $qc_data=$this->donor_examination->blood_qc_data('',$donor_id);
      //print_r($qc_data);
      if($qc_data!="empty")
      {
        $data['qc_data']=$qc_data;
        $data['qc_id']=$qc_data['id'];
        $qc_data_fields=$this->donor_examination->blood_qc_data_fields($qc_data['id'],$donor_id);
        $data['qc_data_fields']=$qc_data_fields;
      }
      else
      {
        $data['qc_data']="empty";  
        $data['qc_id']=0;
      }
    
      $data['component_details']="empty";  
      $data['component_id']=0;

      $this->load->view('blood_bank/donor_examinations/add',$data);
    }  
    else
    {
      redirect(base_url()."dashboard");
    }
  }


  public function get_examination_form($examination_id="")
  {
    //unauthorise_permission(265,1541);
    $employee_type='1';
    $data['examiner_data'] =$this->donor_examination->get_by_examiner_id($employee_type);
    
    $donor_id=$this->input->post('donor_id');
    $data['donor_id'] = $donor_id;
    $data['donor_data'] =$this->donor->get_by_id($donor_id);
    $data['blood_groups']=$this->blood_general_model->get_blood_group_list();
    $data['deferral_reason']=$this->blood_general_model->get_deferral_list();
    $data['examination_id']=$examination_id;
    $examination_data=$this->donor_examination->examination_data($examination_id,$donor_id);
    if($examination_data!="empty")
    {
      
      $data['examination_data']=$examination_data;
      $data['examination_id']=$examination_data['id'];
    }
    else
    {
     
      $data['examination_data']="empty";
      $data['examination_id']=0;
    }


    $this->load->view('blood_bank/donor_examinations/examination_add',$data);
  }

  public function get_blood_details_form($blood_detail_id="")
  {
  	//print_r($blood_detail_id);
    $donor_id=$this->input->post('donor_id');
     $employee_type='2';
    $data['phlebotomist_data'] =$this->donor_examination->get_by_examiner_id($employee_type);
    $data['donor_id'] = $donor_id;
    $data['donor_data'] =$this->donor->get_by_id($donor_id);
    $data['start_time']=$data['donor_data']['start_time'];
    $data['blood_bags']=$this->blood_general_model->get_blood_bag_list();
    $data['post_complication']=$this->blood_general_model->get_post_complication_list();
    $data['bar_code_details']=$this->blood_general_model->get_bar_code_details($blood_detail_id);
   
   // examination data
  
      $examination_id=$this->input->post('examination_id');

      if(isset($examination_id) && $examination_id!='')
      {

      $examination_id=$examination_id;
      }
      else
      {

      $examination_id=$this->session->userdata('sess_examin_id');

      }

      if($examination_id > 0 && $examination_id!="")
      {
        $data['examination_id']=$examination_id;
        $examination_data=$this->donor_examination->examination_data($examination_id,$donor_id); 
        if($examination_data!="empty")
        {
         
          $data['examination_data']=$examination_data;
        }
        else
        {
         
          $data['examination_data']="empty";
        } 
      }
      // else
      // {
      //   $data['examination_id']='';
      // }
    $data['blood_detail_id']=$blood_detail_id;
    $blood_details=$this->donor_examination->blood_details_data($blood_detail_id,$donor_id);
    if($blood_details!="empty")
    {
       //$data['phlebotomist_data']=$phlebotomist_data_by_id;
      $data['blood_details']=$blood_details;
      $data['blood_detail_id']=$blood_details['id'];
    }
    else
    {
      //$data['phlebotomist_data']="empty";
      $data['blood_details']="empty";
      $data['blood_detail_id']=0;
    }
    // examination data
    //print '<pre>'; print_r($data);die;
    $this->load->view('blood_bank/donor_examinations/blood_details',$data);
  }

  public function get_bag_qc_form($qc_id="")
  {
    $donor_id=$this->input->post('donor_id');
    $data['donor_id']=$donor_id;
    $employee_type='3';
    $data['technician_data'] =$this->donor_examination->get_by_examiner_id($employee_type);
    $data['donor_data']=$this->donor->get_by_id($donor_id);
    $data['qc_fields']=$this->blood_general_model->get_qc_fields();
    $examination_data=$this->donor_examination->examination_data('',$donor_id);
    if($examination_data!="empty")
    {
     
      $data['examination_data']=$examination_data;
      $data['examination_id']=$examination_data['id'];
    }
    else
    {
      
      $data['examination_data']="empty";
      $data['examination_id']=0;
    }

    $blood_detail_data=$this->donor_examination->blood_details_data('',$donor_id);
    if($blood_detail_data!="empty")
    {
      
      $data['blood_details']=$blood_detail_data;
      $data['blood_detail_id']=$blood_detail_data['id'];
    }
    else
    {
      
      $data['blood_details']="empty";  
      $data['blood_detail_id']=0;
    }
    
    $qc_data=$this->donor_examination->blood_qc_data('',$donor_id);
    //$blood_get_components=$this->donor_examination->blood_get_components('',$donor_id);

    //print_r($blood_get_components);

    if($qc_data!="empty")
    {
      $data['qc_data']=$qc_data;
      
      //$data['technician_data']=$technician_data_by_id;
      $data['qc_id']=$qc_data['id'];
      $qc_data_fields=$this->donor_examination->blood_qc_data_fields($qc_data['id'],$donor_id);
      $data['qc_data_fields']=$qc_data_fields;
      //print"<pre>";print_r($data['qc_data_fields']);
    }
    else
    {
     // $data['technician_data']="empty";
      $data['qc_data']="empty";  
      $data['qc_id']=0;
    }
    $this->load->view('blood_bank/donor_examinations/bag_qc',$data);
  }

  public function get_component_details_form($donor_id="")
  {
    $donor_id=$this->input->post('donor_id');
    $blood_detail_id=$this->input->post('blood_detail_id');
    $data['donor_id'] = $donor_id;
    $data['donor_data'] =$this->donor->get_by_id($donor_id);
    $data['blood_bags']=$this->blood_general_model->get_blood_bag_list();
    $examination_data=$this->donor_examination->examination_data('',$donor_id);
    if($examination_data!="empty")
    {
      $data['examination_data']=$examination_data;
      $data['examination_id']=$examination_data['id'];
    }
    else
    {
      $data['examination_data']="empty";
      $data['examination_id']=0;
    }

    $blood_detail_data=$this->donor_examination->blood_details_data('',$donor_id);
    //print_r($blood_detail_data);
     if($blood_detail_data!="empty")
    {
      $data['blood_details']=$blood_detail_data;
      $data['collection_time']=$blood_detail_data['collection_time'];
      $data['blood_detail_id']=$blood_detail_data['id'];
    }
    else
    {
      $data['blood_details']="empty"; 
       $data['collection_time']="empty";
      $data['blood_detail_id']=0;
    }
    
    $qc_data=$this->donor_examination->blood_qc_data('',$donor_id);
     //print_r($qc_data);
     //die;
    if($qc_data!="empty")
    {
      $data['qc_data']=$qc_data;
      $data['qc_id']=$qc_data['id'];
      $qc_data_fields=$this->donor_examination->blood_qc_data_fields($qc_data['id'],$donor_id);
      $data['qc_data_fields']=$qc_data_fields;
    }
    else
    {
      $data['qc_data']="empty";  
      $data['qc_id']=0;
    }

    $component_data=$this->donor_examination->blood_components('',$donor_id);

     //print_r($component_data);
 //die;
    if($component_data!="empty")
    { 
      $data['component_data']=$component_data;
       $data['component_id']='empty';
    }
    else
    {
      $data['component_data']="empty";
       $data['component_id']='empty';
    }

    $this->load->view('blood_bank/donor_examinations/component',$data);
  }



  // Function to save examination form
  public function save_examination()
  {
    $users_data = $this->session->userdata('auth_users');
    $branch_id=$users_data['parent_id'];
    //print_r($_POST);die;
   // print_r($_FILES);
   //die;
    // $start_time='';
    // if(!empty($this->input->post('start_time')))
    //   {
    //     $start_time=date('H:i:s',strtotime($this->input->post('start_time')));
    //   }
    //   else
    //   {
    //     $start_time='';
    //   }
    $response=$this->_validate();
    $file_name2='';
    if($response!="200")
    {
      echo $response;die;
    }
    else
    {
      $examination_id=$this->input->post('examination_id');
      $donor_data_array=array('blood_group_id'=>$this->input->post('blood_group_id'),'stage'=>'1');
      $where_condition=" id=".$this->input->post('donor_id')." ";
      $this->blood_general_model->common_update('hms_blood_donor',$donor_data_array,$where_condition);
      $examination_array=array(
                                'illness'=>$this->input->post('illness'),
                                'branch_id'=>$branch_id,
                                'remark'=>$this->input->post('remark'),
                                'donor_id'=>$this->input->post('donor_id'),
                                'blood_pressure'=>$this->input->post('blood_pressure'),
                                'temperature'=>$this->input->post('temperature'),
                                'pulse'=>$this->input->post('pulse'),
                                'haemoglobin'=>$this->input->post('haemoglobin'),
                                'respiratory_rate'=>$this->input->post('respiratory_rate'),
                                'examiner_id'=>$this->input->post('examiner_id'),
                                //'start_time'=>$start_time,
                                'outcome'=>$this->input->post('outcome'),
                                'deferral_reason'=>$this->input->post('deferral_reason'),
                                'deferral_eligiblity'=>date('Y-m-d',strtotime($this->input->post('eligible_by'))),
                                'created_by'=>$users_data['id'],
                                'created_date'=>date('Y-m-d')
                              );
      if($examination_id > 0)
      {
        //echo 'eee';die;
            if(!empty($_FILES['general_form']['name']))
              { 
                
                $config['upload_path']   = DIR_UPLOAD_PATH.'blood_bank/donor_profile/'; 
                $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                $config['max_size']      = 1000; 
                $config['encrypt_name'] = TRUE; 
                $this->load->library('upload', $config);
              //print_r($config);die;
                if($this->upload->do_upload('general_form')) 
                {

                  $file_data = $this->upload->data(); 
                  $file_name2= $file_data['file_name'];
                }

              }
              else
              {
                $file_name2=$this->input->post('old_examination_form'); 
              }
         $examination_array["examination_form"]=$file_name2;
         // print_r($examination_array);
         //die;
         $where_condition=" id=".$this->input->post('examination_id')." ";
         $this->blood_general_model->common_update('hms_blood_examination',$examination_array,$where_condition);
          $flag=($this->input->post('outcome')==1) ? 1 : 0; 
          echo json_encode(array('st'=>1,'msg'=>"Record Updated Successfully",'flag'=>$flag,'examination_id'=>$examination_id ));
      }
      else
      {
        //echo 'wqe';die;
         if(!empty($_FILES['examination_form']['name']))
      { 
        
        $config['upload_path']   = DIR_UPLOAD_PATH.'blood_bank/donor_profile/'; 
        $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
        $config['max_size']      = 1000; 
        $config['encrypt_name'] = TRUE; 
        $this->load->library('upload', $config);
      //print_r($config);die;
        if($this->upload->do_upload('examination_form')) 
        {

          $file_data = $this->upload->data(); 
          $file_name2= $file_data['file_name'];
        }

        }
        else
        {
          $file_name2=''; 
        }
         $examination_array["examination_form"]=$file_name2;
        // print_r($examination_array);
         //die;
        $inserted_id=$this->blood_general_model->common_insert('hms_blood_examination',$examination_array);

          $this->session->set_userdata('sess_examin_id',$inserted_id);
        $examination_id=$inserted_id;
        
        $flag=($this->input->post('outcome')==1) ? 1 : 0; 
        if($inserted_id > 0)
          echo json_encode(array('st'=>1, 'msg'=>'Record Inserted Successfully', 'flag'=>$flag,'examination_id'=>$examination_id));
        else
          echo json_encode(array('st'=>1, 'msg'=>'There is some issue.. Please try after some time'));
      }  


    }
  }
  // Function to save examination form
 public function calc_times()
  {
    if($this->input->post('flag')==1)
    {
        $nw=date('H:i:s');
        echo $nw;
    }
  }

  // Funciton to save blood details
  public function save_blood_details()
  {
      
    $response=$this->_validate_blood_details();
    $users_data = $this->session->userdata('auth_users');
    $branch_id=$users_data['parent_id'];

    if(!empty($this->input->post('other_id')))
    {
      $other_id=$this->input->post('other_id');

    }
    else
    {
      $other_id='';
    }
    if(!empty($this->input->post('other_id')))
    {
      $other_post=$this->input->post('other_post');
    }
    else
    {
     $other_post='';
    }

    if($response!="200")
    {
      echo $response;die;
    }
    else
    {
      //print_r($this->input->post());die; //blood_bag_type
      $blood_detail_id=$this->input->post('blood_detail_id');
      $examination_id=$this->input->post('examination_id');
      $donor_id=$this->input->post('donor_id');
      $examination_array=array(
                          'volume'=>$this->input->post('volume'),
                          'modified_by'=>$users_data['id'],
                          'modified_date'=>date('Y-m-d'),
                        );
      $examination_condition=" id=".$examination_id." and donor_id=".$donor_id." and branch_id=".$branch_id." ";
      $examination_array["modified_by"]=$users_data['id'];
      $examination_array["modified_date"]=date('Y-m-d H:i:s');
      $exm_rec_id=$this->blood_general_model->common_update('hms_blood_examination',$examination_array, $examination_condition);
      $file_name3='';
      $end_time='';
      $collection_time='';
       if(!empty($this->input->post('end_time')))
      {
      	$end_time=date('H:i:s',strtotime($this->input->post('end_time')));
      }
      else
      {
      	$end_time='';
      }
      if(!empty($this->input->post('collection_time')))
      {
      	$collection_time=date('H:i:s',strtotime($this->input->post('collection_time')));
      }
      else
      {
      	$collection_time='';
      }
      $blood_details_array=array(
                                  'donor_id'=>$donor_id,
                                  'phlebotomist'=>$this->input->post('phlebotomist'),
                                  'blood_bag_type_id'=>$this->input->post('blood_bag_type'),
                                  'bar_code'=>$this->input->post('bag_bar_code'),
                                  'collection_date'=>date('Y-m-d',strtotime($this->input->post('collection_date'))),
                                  'expiry_date'=>date('Y-m-d:H:i:s',strtotime($this->input->post('expiry_date'))),
                                  'venipuncture'=>$this->input->post('venipuncture'),
                                 'collection_time'=>$collection_time,
                                  'end_time'=>$end_time,
                                  'quantity'=>$this->input->post('quantity'),
                                  'collection_duration'=>$this->input->post('collection_duration'),
                                  'post_compilations'=>$this->input->post('post_compilation'),
                                  'other_post'=>$other_post,
                                  'other_id'=>$other_id,
                                  'remark'=>$this->input->post('remark'),
                                  'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                );
      //print_r($this->input->post);
      //($blood_details_array);
      //die;
        if($blood_detail_id > 0)
        {
          if(!empty($_FILES['taking_form']['name']))
              { 
                
                $config['upload_path']   = DIR_UPLOAD_PATH.'blood_bank/donor_profile/'; 
                $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                $config['max_size']      = 1000; 
                $config['encrypt_name'] = TRUE; 
                $this->load->library('upload', $config);
              //print_r($config);die;
                if($this->upload->do_upload('taking_form')) 
                {

                  $file_data = $this->upload->data(); 
                  $file_name3= $file_data['file_name'];
                }

              }
              else
              {
                $file_name3=$this->input->post('old_taking_form'); 
              }

         $blood_details_array["taking_form"]=$file_name3;
          $bd_condition=" id=".$blood_detail_id." and donor_id=".$donor_id." and branch_id=".$branch_id." ";
          $blood_details_array["modified_by"]=$users_data['id'];
          $blood_details_array["modified_date"]=date('Y-m-d H:i:s');
          $bd_rec_id=$this->blood_general_model->common_update('hms_blood_details',$blood_details_array, $bd_condition);
          //echo $this->db->last_query(); exit;


          //echo"ddddd"; print_r($bd_rec_id);
          //die;
          $bar_code_details=$this->input->post('bar_code_detail');
          $this->db->where(array('branch_id'=>$branch_id,'blood_detail_id'=>$blood_detail_id));
          $this->db->delete('hms_blood_details_to_barcode');
          if(!empty($bar_code_details))
          {
          foreach($bar_code_details as $bar_codec)
          {
              $data_bar_code_array=array('blood_detail_id'=>$blood_detail_id,
                                          'bar_code'=>$bar_codec['bar_code']);
              $data_bar_code_array["branch_id"]=$branch_id;
              $data_bar_code_array["created_by"]=$users_data['id'];
              $data_bar_code_array["created_date"]=date('Y-m-d H:i:s'); 
              $this->blood_general_model->common_insert('hms_blood_details_to_barcode',$data_bar_code_array);
          }

          }
          echo json_encode(array('st'=>1, 'msg'=>'Record Updated Successfully'));
        }
        else
        {

          if(!empty($_FILES['taking_form']['name']))
              { 
                
                $config['upload_path']   = DIR_UPLOAD_PATH.'blood_bank/donor_profile/'; 
                $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                $config['max_size']      = 1000; 
                $config['encrypt_name'] = TRUE; 
                $this->load->library('upload', $config);
              //print_r($config);die;
                if($this->upload->do_upload('taking_form')) 
                {

                  $file_data = $this->upload->data(); 
                  $file_name3= $file_data['file_name'];
                }

              }
              else
              {
                $file_name3=''; 
              }
         $blood_details_array["taking_form"]=$file_name3;

          $blood_details_array["branch_id"]=$branch_id;
          $blood_details_array["created_by"]=$users_data['id'];
          $blood_details_array["created_date"]=date('Y-m-d H:i:s');
          $donor_rec_id = $this->blood_general_model->common_insert('hms_blood_details',$blood_details_array);
          //echo $this->db->last_query();die;
         //echo"fii"; print_r($donor_rec_id);
          $bar_code_details=$this->input->post('bar_code_detail');

          if(!empty($bar_code_details))
          {
            foreach($bar_code_details as $bar_codec)
            {
              $data_bar_code_array=array('blood_detail_id'=>$donor_rec_id,
                                          'bar_code'=>$bar_codec['bar_code']);
              $data_bar_code_array["branch_id"]=$branch_id;
              $data_bar_code_array["created_by"]=$users_data['id'];
              $data_bar_code_array["created_date"]=date('Y-m-d H:i:s'); 
              $this->blood_general_model->common_insert('hms_blood_details_to_barcode',$data_bar_code_array);
            }
            
          }
          
          $this->session->set_userdata('sess_blood_detail_id',$donor_rec_id);
          if($donor_rec_id > 0)
          echo json_encode(array('st'=>1, 'msg'=>'Record Inserted Successfully'));
        else
          echo json_encode(array('st'=>1, 'msg'=>'There is some issue.. Please try after some time'));
          
        }

    }
  }
  // function to save blood details 


  // fucntion to save blood qc details 
  public function save_blood_qc_details()
  {
    $users_data = $this->session->userdata('auth_users');
    $branch_id=$users_data['parent_id'];
    $blood_qc_id=$this->input->post('qc_id');
    $donor_id=$this->input->post('donor_id');
    /*print_r($this->input->post());
    die;*/
    $blood_qc_array=array(
                          'technician_id'=>$this->input->post('technician_id'),
                          'final_result'=>$this->input->post('final_result'),
                          'blood_condition'=>$this->input->post('bld_cndtn'),
                          'donor_status'=>$this->input->post('donor_status'),
                          'remark'=>$this->input->post('remark'),
                          'qc_date'=>date('Y-m-d',strtotime($this->input->post('qc_date'))),
                          'qc_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$this->input->post('qc_time'))),
                          'status'=>1,
                          'ip_address'=>$_SERVER['REMOTE_ADDR'],
                         );
   // print_r($blood_qc_array);
    //die;
    if($blood_qc_id > 0)
    {
      //print '<pre>'; print_r($_POST);die;
      $blood_qc_array["modified_by"]=$users_data['id'];
      $blood_qc_array["modified_date"]=date('Y-m-d H:i:s');
      $qc_condition=" id=".$blood_qc_id." and donor_id=".$donor_id." and branch_id=".$branch_id." ";
      $q_rec_id=$this->blood_general_model->common_update('hms_blood_qc_examination',$blood_qc_array, $qc_condition);

      //echo $this->db->last_query(); exit;

      $blood_don_array["donor_status"]=$this->input->post('donor_status');
      $donor_up_condition="id=".$donor_id." and branch_id=".$branch_id." ";
      $do_id=$this->blood_general_model->common_update('hms_blood_donor',$blood_don_array, $donor_up_condition);
      
      
      $blood_stck_array["donation_status"]=$this->input->post('donor_status');
      $donor_stck_condition="donor_id=".$donor_id." and branch_id=".$branch_id." ";
      $do_id=$this->blood_general_model->common_update('hms_blood_stock',$blood_stck_array, $donor_stck_condition);


      $this->db->where('branch_id',$branch_id);
      $this->db->where('donor_id',$donor_id);
      $this->db->where('qc_exm_id',$blood_qc_id);
      $this->db->delete('hms_blood_qc_examination_to_fields');

      if($this->input->post('qc_field_id')!=""  && !empty($this->input->post('qc_field_id')) )
      {
          $qc_fields=$this->input->post('qc_field_id');
          $qc_method=$this->input->post('qc_method');
          $qc_result=$this->input->post('qc_result');
          $i=1;

          foreach($qc_fields as $fld)
          {
            //print_r($fld);
            $fields_array=array(
                                'branch_id'=>$branch_id,
                                'donor_id'=>$this->input->post('donor_id'),
                                'qc_field_id'=>$fld,
                                'method'=>$qc_method[$i],
                                'result'=>$qc_result[$i],
                                'qc_exm_id'=>$blood_qc_id,
                               );
             //echo"<pre>";print_r($qc_method[$i]);
            //echo"<pre>";print_r($fields_array);
            //die;
             //print_r($this->input->post());
          //die;
            $this->blood_general_model->common_insert('hms_blood_qc_examination_to_fields',$fields_array);
           
            $i++;
          }
        }

      echo json_encode(array('st'=>1, 'msg'=>'Record Updated Successfully'));
    }
    else
    {
      $blood_qc_array["branch_id"]=$branch_id;
      $blood_qc_array["donor_id"]=$this->input->post('donor_id');
      $blood_qc_array["created_by"]=$users_data['id'];
      $blood_qc_array["created_date"]=date('Y-m-d H:i:s');
      $qc_rec_id = $this->blood_general_model->common_insert('hms_blood_qc_examination',$blood_qc_array);
      $this->session->set_userdata('sess_qc_rec_id',$qc_rec_id);
      //echo $this->db->last_query(); exit;
      //stock update and donor update
      $blood_don_array["donor_status"]=$this->input->post('donor_status');
      $donor_up_condition="id=".$this->input->post('donor_id')." and branch_id=".$branch_id." ";
      $do_id=$this->blood_general_model->common_update('hms_blood_donor',$blood_don_array, $donor_up_condition);
      
      
      //update stock status 
      $blood_stck_array["donation_status"]=$this->input->post('donor_status');
      $donor_stck_condition="donor_id=".$donor_id." and branch_id=".$branch_id." ";
      $do_id=$this->blood_general_model->common_update('hms_blood_stock',$blood_stck_array, $donor_stck_condition);

      if($qc_rec_id > 0)
      {
        if($this->input->post('qc_field_id')!=""  && !empty($this->input->post('qc_field_id')) )
        {
          $qc_fields=$this->input->post('qc_field_id');
          $qc_method=$this->input->post('qc_method');
          $qc_result=$this->input->post('qc_result');
          $i=1;
          foreach($qc_fields as $fld)
          {
           // print_r($fld);
            $fields_array=array('branch_id'=>$branch_id,
                                'donor_id'=>$this->input->post('donor_id'),
                                'qc_field_id'=>$fld,
                                'method'=>$qc_method[$i],
                                'result'=>$qc_result[$i],
                                'qc_exm_id'=>$qc_rec_id,
                               );
             //echo"<pre>";print_r($fields_array);
            
            $this->blood_general_model->common_insert('hms_blood_qc_examination_to_fields',$fields_array);
            $i++;
          }
        }
        echo json_encode(array('st'=>1, 'msg'=>'Record inserted Successfully'));
      }
    }
  }
  // function to save blood qc details


  // Function to save blood components
    public function save_blood_components()
    {
      
     // print_r($this->input->post());die;

    //  print '<pre>'; print_r($_POST);die;
      $users_data = $this->session->userdata('auth_users');
      $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
      $this->form_validation->set_rules('component_details[]', 'Component', 'trim|required');
      if ($this->form_validation->run() == FALSE) 
      { 
        echo json_encode(array('st'=>0, 'component'=>form_error('component_ids[]')));
      }
      else
      {
        $component_ids=$this->input->post('component_ids');
        $bar_code=$this->input->post('bar_code');
        $expiry_date=$this->input->post('expiry_date');
        $count=count($this->input->post('component_name'));
        $donor_id=$this->input->post('donor_id');
        $action=$this->input->post('action');
        $bag_type_id=$this->input->post('bag_type_id');
        $component_name=$this->input->post('component_name');
        $comp_price=$this->input->post('comp_price');
        $quantity=$this->input->post('quantity');
        $get_blood_group= $this->donor_examination->get_blood_grp_detail($donor_id);
        //print_r($get_blood_group[0]->blood_group_id);die;

        if($action=="update")
        {
            $this->db->where('donor_id',$donor_id);
            $this->db->where('bag_type_id',$bag_type_id);
            $this->db->where('branch_id',$users_data['parent_id']);
            $this->db->delete('hms_blood_extract_component');

            /* delete  from  hms_blood_extract_component_bar_code*/

            $this->db->where('donor_id',$donor_id);
            $this->db->where('bag_type_id',$bag_type_id);
            $this->db->where('branch_id',$users_data['parent_id']);
            $this->db->delete('hms_blood_extract_component_bar_code');
            /* delete  from  hms_blood_extract_component_bar_code*/

            $this->db->where('donor_id',$donor_id);
            $this->db->where('bag_type_id',$bag_type_id);
            $this->db->where('branch_id',$users_data['parent_id']);
            $this->db->where('flag',1);
            $this->db->delete('hms_blood_stock');

        }
        $main_array=array();
     /* insert into data in hms_blood_extract_component */


          if(!empty($this->input->post('component_details')))
          {
          $component_deatil= count($this->input->post('component_details'));
          $comp_details_data= $this->input->post('component_details');

            foreach($comp_details_data as $details)
            {
             if(isset($details['component_ids']))
              {
              $data_array= array(
                                'donor_id'=>$donor_id,
                                'branch_id'=>$users_data['parent_id'],
                                'bag_type_id' =>$this->input->post('bag_type_id'),
                                'component_name'=>$details['component_name'],
                                'component_price'=>$details['comp_price'],
                                'component_id'=>$details['component_ids'],
                                'qty'=>$details['quantity'],
                                'volumn'=>$details['volumn'],
                                'expiry_date' =>date('Y-m-d H:i:s',strtotime($details['expiry_date'])),
                                'created_by'=>$users_data['id'],
                                'created_date'=>date('Y-m-d H:i:s'),
                                'status'=>1,
                                'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                );
              // print_r($data_array);
              $inserted_id=$this->blood_general_model->common_insert('hms_blood_extract_component',$data_array);


               $bar_code_detail= $details['bar_code_detail'];
               if(!empty($bar_code_detail))
               {
                foreach($bar_code_detail as $bar_code_d)
                {
                    $data_array_bar_code_table= array(
                                'donor_id'=>$donor_id,
                                'branch_id'=>$users_data['parent_id'],
                                'extraction_id' =>$inserted_id,
                                
                                'component_id'=>$details['component_ids'],
                                'bar_code'=>$bar_code_d['bar_code'],
                                'expiry_date' =>date('Y-m-d H:i:s',strtotime($details['expiry_date'])),
                                'created_by'=>$users_data['id'],
                                'created_date'=>date('Y-m-d H:i:s'),
                                'status'=>1,
                                'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                );
              // print_r($data_array);
                $this->blood_general_model->common_insert('hms_blood_extract_component_bar_code',$data_array_bar_code_table);


                /* insert data into stock according to bar_code  */
            
                $is_issued_status = $issued_component[$details['component_ids']];
                if(empty($is_issued_status))
                {
                    $is_issued_status=0;
                }
                //echo $is_issued_status; die;
                 $stock_array=array(
                                  'donor_id'=>$donor_id,
                                  'branch_id'=>$users_data['parent_id'],
                                  'bag_type_id' =>$this->input->post('bag_type_id'),
                                  'component_id' => $details['component_ids'],
                                  'component_name'=>$details['component_name'],
                                  'component_price'=>$details['comp_price'],
                                  'qty'=>1,
                                  'volumn'=>$details['volumn'],
                                   'expiry_date' =>date('Y-m-d H:i:s',strtotime($details['expiry_date'])), /* new field */
                                  'debit'=>1,/* change vale 1 */
                                  'blood_group_id'=>$get_blood_group[0]->blood_group_id,
                                  'is_issued'=>$is_issued_status,
                                  'bar_code' => $bar_code_d['bar_code'],
                                  'created_date'=>date('Y-m-d H:i:s'),
                                  'created_by'=>$users_data['id'],
                                  'status'=>1,
                                  'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                  'flag'=>1,
                                );
             // print_r($stock_array);
              $stock_inserted_id=$this->blood_general_model->common_insert('hms_blood_stock',$stock_array);
              // echo $this->db->last_query(); exit;

               
//ad stock //echo $this->db->last_query(); exit;
                 /* insert data into stock according to bar_code  */

                }
               }
               
               
               
               
             }
            }
          }
          /* insert into data in hms_blood_extract_component */

         echo json_encode(array('st'=>1,'msg'=>"Record Updated Successfully"));
      }
    }
  // Function to save blood components 



  // function to validate form
  public function _validate()
  {
    $users_data = $this->session->userdata('auth_users');
    $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
    $this->form_validation->set_rules('blood_group_id', 'Blood group', 'trim|required');
    $this->form_validation->set_rules('examiner_id', 'Examiner', 'trim|required');
    if ($this->form_validation->run() == FALSE) 
    { 
      $data_error=array('blood_group'=>form_error('blood_group_id'),'examiner'=>form_error('examiner_id'));
      echo json_encode(array('st'=>0, $data_error));
      //echo json_encode(array('st'=>0, 'examiner'=>form_error('examiner_id')));
    }
    else
    {
        return "200";
    }
  }
  // Function to validate form


   // function to validate form
  public function _validate_blood_details()
  {
     $users_data = $this->session->userdata('auth_users');
    $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
    $this->form_validation->set_rules('blood_bag_type', 'Blood Bag', 'trim|required');
    //$this->form_validation->set_rules('bar_code_detail[]', 'Bar Code', 'trim|required');
    if ($this->form_validation->run() == FALSE) 
    { 
      echo json_encode(array('st'=>0, 'bag_type'=>form_error('blood_bag_type'), 'bar_code'=>form_error('bar_code_detail')));
    }
    else
    {
        return "200";
    }
  }
  // Function to validate form


// Function to calculate difference in times for examination 
  public function calc_diff_times()
  {
    if($this->input->post('flag')==1)
    {
        $nw=date('H:i:s');
        echo $nw;
    }
    else if($this->input->post('flag')==2)
    {
      $start=$this->input->post('start');
      $end=$this->input->post('end');
      if($end!="")
      {
        $diff=date_diff(date_create($start),date_create($end));
        echo $diff->h.":".$diff->i.":".$diff->s;
      }
    }
  }
// Function to calculate differences in times for examination


// Function to set blood expiry
public function set_blood_expiry()
{
  $collection_date=$this->input->post('collection_date');

  $setting=get_general_settings('BLOOD_EXP_FROM_DATE_COLL');
  //print_r($setting); exit;
  if(!empty($setting))
  {
    $value=$setting[0]->setting_value1;
    if(empty($value))
    {
        $value=0;
    }
    //$expiry_time= date('d-m-Y h:i:s A', strtotime(" +".$value." months", strtotime($collection_date)  ));
    
    $date = date('Y-m-d', strtotime($collection_date));
    $date_time = date('H:i:s', strtotime($collection_date));
    
    //echo $newdate = date('Y-m-d', strtotime($date. ' + 10 days')); 
     $newdate = date('Y-m-d', strtotime($date.' +'.$value.' days')); //die;
    
    $expiry_time = date('d-m-Y h:i A', strtotime("$newdate $date_time"));
    //$expiry_time = date('Y-m-d H:i:s', strtotime($collection_date . ' +'.$value.' days'));
    
    //$expiry_time = date('Y-m-d H:i:s', strtotime($collection_date . ' +'.$value.' days'));
  }
  else
  {
    $expiry_time="";
  }
  echo $expiry_time;
}
// Function to set blood expiry
public function get_expiry_according_to_component($collection_date="",$bag_id="",$component_id="",$collection_time='')
{

  $res=$this->donor_examination->get_components_for_bag($bag_id);
  
 // echo "<pre>";print_r($res); exit;
  
  $expiry_time='';
  
  $i=1;
  foreach($res as $dt)
  {
  	//print_r($dt);

    if($component_id==$dt->id)
    {
    	if($dt->expiry_time!="")
    	{
    		if($dt->interval_time!="")
    		{
    			if($dt->interval_time==1)
    			{
    			    if(!empty($collection_date))
    			    {
    			       $hours_cal=$collection_date.' '.$collection_time;
                         $value=$dt->expiry_time;
                         $added_date=date("Y-m-d h:i A",strtotime(" +".$value." hours", strtotime($hours_cal)));
                         $expiry_time= date('d-m-Y h:i A', strtotime($added_date));
                         //echo $expiry_time; 
    			    }
    			    else
    			    {
    			        $added_date='';
                         $expiry_time= '';
                         //echo $expiry_time; 
    			    }
    			 
                 
    			}

    			if($dt->interval_time==2)
    			{
    			    if(!empty($collection_date))
    			    {
    			        $day_time='';
                      $days=$dt->expiry_time;
        			  $date_val= date('Y-m-d', strtotime($collection_date. '+ '.$days.'days'));
        			  $day_time= date('d-m-Y', strtotime($date_val));
        			  $expiry_time=$day_time.' '.$collection_time;  
    			    }
    			    else
    			    {
    			         $added_date='';
                         $expiry_time= '';
    			    }
    			            

                
    			}
    			if($dt->interval_time==3)
    			{
    			    if(!empty($collection_date))
    			    {
    			        $week=$dt->expiry_time;
        			   $value=$week*7;
        			   $date_val= date('Y-m-d', strtotime($collection_date. '+ '.$value.'days'));
        			   $week_time= date('d-m-Y', strtotime($date_val));
        			   $expiry_time=$week_time.' '.$collection_time; 
    			        
    			    }
    			    else
    			    {
    			        $added_date='';
                         $expiry_time= ''; 
    			    }
    			   
             
    				
    			}
    			if($dt->interval_time==4)
    			{
    			    if(!empty($collection_date))
    			    {
    			        $value=$dt->expiry_time;
                        $month_time= date('d-m-Y', strtotime(" +".$value." months", strtotime($collection_date)));
                        $expiry_time=$month_time.' '.$collection_time;
    			    }
    			    else
    			    {
    			        $added_date='';
                         $expiry_time= ''; 
    			    }
    				
    				
                    //echo $expiry_time;		
    			}


    		}
    	}
    	else
    	{
    		$expiry_time="";
    	}
   
      return $expiry_time;
    }

    $i++;
  }
  
}



public function get_components_by_bag_type()
{
    
    /*$post = $this->input->post(); 
    echo "<pre>";print_r($post); exit;*/
  $bag_id=$this->input->post('bag_type_id');
  $component_data=$this->input->post('component_data');
  //print_r($component_data); exit;
  $collection_date=$this->input->post('collection_date');
   $collection_time=$this->input->post('collection_time');
  // print_r($this->input->post);
   //die;
  $res=$this->donor_examination->get_components_for_bag($bag_id);
  //$blood_details=$this->donor_examination->blood_details_data($blood_detail_id,$donor_id);
  //print '<pre>'; print_r($res);die;
  $dataSet="";
  $script_datepicker='';
  $date_new='';
  if($res!="empty" && $component_data=="empty")
  {
    $dataSet="<table style='width:100%;'><td></td><td><b>Component Name</b></td><td><b>Unit/Barcode</b></td><td><b>Volume</b></td><td><b>Component Price</b></td><td><b>Expiry Date</b></td>"; //<td><b>Bag Bar Code</b></td> remove varcode
    $i=1;
    foreach($res as $dt)
    {

      //print_r($dt);
      //;
          $check_script= "";

          $get_expiry_date= $this->get_expiry_according_to_component($collection_date,$bag_id,$dt->id,$collection_time);
   //print_r($dt);
   //die;
      $dataSet.="<tr>";
      $dataSet.="<td style='padding:3px 0;'><input ct='".$i."' class='cheeck'  type='checkbox' value='".$dt->component_id."' name='component_details[".$i."][component_ids]' id='component_id_".$dt->id."' onclick='clear_values(this);' > <input type='hidden' name='component_details[".$i."][component_name]' id='component_name[".$i."]' value='".$dt->component."' >  </td>";
      $dataSet.="<td style='padding:3px 0;' >".$dt->component."</td>";

      $dataSet.="<td style='padding:3px 0;' ><input type='text' class='w-40px numeric' name='component_details[".$i."][quantity]' id='quantity_id_".$i."' value='' onkeyup='open_text_box(".$dt->id.",".$i.");'><span id='bardiv_".$i."_".$dt->id."' class='ba_div_".$dt->id."'><span><span id='quantity_error_".$i."'></span></td>";

      // $dataSet.="<td style='padding:3px 0;'><input type='text' name='bar_code[".$i."]' id='bar_code_id_".$i."' value=''><span id='bar_code_error_".$i."' ></span></td>";

      $dataSet.="<td style='padding:3px 0;' ><input type='text' name='component_details[".$i."][volumn]' id='comp_id_".$i."' value='".$dt->volumn."'><span id='volumn_id_".$i."' ></span></td>
      <td style='padding:3px 0;' ><input type='text' readonly name='component_details[".$i."][comp_price]' id='comp_price_id_".$i."' value='".$dt->unit_price."'><span id='comp_price_id_".$i."' ></span></td>";

       

      $dataSet.="<td style='padding:3px 0;' ><input class='collection_date' type='text' name='component_details[".$i."][expiry_date]' id='expiry_date_id_".$i."' class='collection_date' value='".$get_expiry_date."'><span id='expiry_date_error_".$i."' onchange='return false;'>".$check_script."</span></td>";
      $dataSet.="</tr>";
      $i++;
    }
    $dataSet.="</table><script>$('.collection_date').datepicker({
    dateFormat: 'dd-mm-yy',
    autoclose: true, 
});</script>";
  }
  else if($res!="empty" && $component_data!="empty")
  {

      $dataSet="<table style='width:100%;'><td></td><td><b>Component Name</b></td><td><b>Quantity/Barcode</b></td><td><b>Volume</b></td><td><b>Component Price</b></td> <td><b>Expiry Date</b></td>";
      //<td><b>Bag Bar Code</b></td> remove barcode
      $x=1;
      $not_arr=array();
      foreach($component_data as $data)
      {
        
        array_push($not_arr, $data['component_id']);
        


        $get_expiry_date= $this->get_expiry_according_to_component($collection_date,$bag_id,$data['component_id'],$collection_time);
        
        //echo "<pre>"; print_r($get_expiry_date); 
        $get_all_bar_code= $this->donor_examination->get_all_bar_code($data['component_id'],$data['id']);

        $dataSet.="<tr>";
        $dataSet.="<td style='padding:3px 0;'><input ct='".$x."' onclick='clear_values(this);' class='cheeck'  type='checkbox' value='".$data['component_id']."' name='component_details[".$x."][component_ids]' id='component_id_".$data['id']."'  checked > <input type='hidden' name='component_details[".$x."][component_name]' id='component_name[".$x."]' value='".$data['component']."' >      </td>";
        $dataSet.="<td style='padding:3px 0;' >".$data['component']."</td>"; 

         $dataSet.="
         
         <td style='padding:3px 0;' ><input type='text' class='w-40px numeric' name='component_details[".$x."][quantity]' id='quantity_id_".$x."' value='".$data['qty']."' onkeyup='open_text_box(".$data['id'].",".$x.");'>";



          if($get_all_bar_code!='empty')
          {
            $bar=0;
            foreach($get_all_bar_code as $bar_codes)
            {
               $dataSet.="<span id='bardiv_".$x."_".$data['id']."' class='ba_div_".$data['id']."'><br><input type='text' style='margin-top: 2px;' name='component_details[".$x."][bar_code_detail][".$bar."][bar_code]' id='bar_code_id_".$x."' value='".$bar_codes->bar_code."' class='w-60px' placeholder='Barcode'/></span>";
               $bar++;
            }
          }
         


          $dataSet.="<span><span id='quantity_error_".$x."' ></span></td>";

        $dataSet.="<td style='padding:3px 0;' ><input type='text' class='w-40px numeric' name='component_details[".$x."][volumn]' id='volumn_id_".$x."' value='".$data['volumn']."'></td>";
        
        $dataSet.="<td style='padding:3px 0;' ><input type='text' readonly name='component_details[".$x."][comp_price]' id='comp_price_id_".$x."' value='".$data['component_price']."'><span id='comp_price_id_".$x."' ></span></td>";
        $date_val='';
         if(!empty($data['expiry_date']))
         { 
          if($data['expiry_date']!='0000-00-00' && $data['expiry_date']!='1970-01-01 00:00:00')
              {
                 $date_val=date('d-m-Y',strtotime($data['expiry_date']));
                 
                
              }
             else
             {
                
                $date_val=$get_expiry_date;
             }
         }
         else
         {
            $date_val=$get_expiry_date;
         }
        

        $dataSet.="<td style='padding:3px 0;'><input type='text' name='component_details[".$x."][expiry_date]' id='expiry_date_id_".$x."' value='".$date_val."' class='collection_date'><span id='expiry_date_error_".$x."'></span></td>";
        $dataSet.="</tr>";
        $x++;
      }
      $j=$x;

      foreach($res as $tt)
      { 
         //print_r($tt);
         
        //print_r($get_expiry_date);
        if(!in_array($tt->component_id,$not_arr))
        {
          $get_expiry_date= $this->get_expiry_according_to_component($collection_date,$bag_id,$tt->id,$collection_time);

          $dataSet.="<tr>";
          $dataSet.="<td style='padding:3px 0;'><input ct='".$j."' onclick='clear_values(this);'  class='cheeck'  type='checkbox' value='".$tt->component_id."' name='component_details[".$j."][component_ids]' id='component_id_".$tt->id."' > <input type='hidden' name='component_details[".$j."][component_name]' id='component_name[".$j."]' value='".$tt->component."' >  </td>";
          $dataSet.="<td style='padding:3px 0;' >".$tt->component."</td>"; 

          $dataSet.="<td style='padding:3px 0;' ><input type='text' class='w-40px numeric' name='component_details[".$j."][quantity]' id='quantity_id_".$j."' value='' onkeyup='open_text_box(".$tt->id.",".$j.");'><span id='bardiv_".$j."_".$tt->id."' class='ba_div_".$tt->id."'><span><span id='quantity_error_".$j."'></span></td>";
          
          
          $dataSet.="<td style='padding:3px 0;' ><input type='text' class='w-40px numeric' name='component_details[".$j."][volumn]' id='volumn_id_".$j."' value=''><span id='volumn_error_".$j."'></span></td>";

          // $dataSet.="<td style='padding:3px 0;' ><input type='text' name='bar_code[".$j."]' id='bar_code_id_".$j."' value=''><span id='bar_code_error_".$j."' ></span></td>";

          $dataSet.="<td style='padding:3px 0;' ><input type='text' readonly name='component_details[".$j."][comp_price]' id='comp_price_id_".$j."' value='".$tt->unit_price."'><span id='comp_price_id_".$j."' ></span></td>";

       // $date_val='';
         //print($get_expiry_date);
           

          $dataSet.="<td style='padding:3px 0;' ><input type='text' name='component_details[".$j."][expiry_date]' id='expiry_date_id_".$j."' class='collection_date' value='".$get_expiry_date."'><span id='expiry_date_error_".$j."' ></span></td>";
          $dataSet.="</tr>";
          $j++;
        }

      }


      $dataSet.="</table><script>$('.collection_date').datepicker({
    dateFormat: 'dd-mm-yy',
    autoclose: true, 
});</script>";
  }
  
  echo $dataSet;
}

// Function to get components for bag type
public function get_components_by_bag_type_old()
{
  $bag_id=$this->input->post('bag_type_id');
  $component_data=$this->input->post('component_data');
  $collection_date=$this->input->post('collection_date');
   $collection_time=$this->input->post('collection_time');
  // print_r($this->input->post);
   //die;
  $res=$this->donor_examination->get_components_for_bag($bag_id);
  //$blood_details=$this->donor_examination->blood_details_data($blood_detail_id,$donor_id);
  //print '<pre>'; print_r($res);die;
  $dataSet="";
  $script_datepicker='';
  $date_new='';
  if($res!="empty" && $component_data=="empty")
  {
    $dataSet="<table style='width:100%;'><td></td><td><b>Component Name</b></td><td><b>Quantity/Barcode</b></td><td><b>Component Price</b></td><td><b>Expiry Date</b></td>"; //<td><b>Bag Bar Code</b></td> remove varcode
    $i=1;
    foreach($res as $dt)
    {

    	//print_r($dt);
    	//;
          $check_script= "";

          $get_expiry_date= $this->get_expiry_according_to_component($collection_date,$bag_id,$dt->id,$collection_time);
   //print_r($dt);
   //die;
      $dataSet.="<tr>";
      $dataSet.="<td style='padding:3px 0;'><input ct='".$i."' class='cheeck'  type='checkbox' value='".$dt->component_id."' name='component_details[".$i."][component_ids]' id='component_id_".$dt->id."' onclick='clear_values(this);' > <input type='hidden' name='component_details[".$i."][component_name]' id='component_name[".$i."]' value='".$dt->component."' >  </td>";
      $dataSet.="<td style='padding:3px 0;' >".$dt->component."</td>";

      $dataSet.="<td style='padding:3px 0;' ><input type='text' class='w-40px numeric' name='component_details[".$i."][quantity]' id='quantity_id_".$i."' value='' onkeyup='open_text_box(".$dt->id.",".$i.");'><span id='bardiv_".$i."_".$dt->id."' class='ba_div_".$dt->id."'><span><span id='quantity_error_".$i."'></span></td>";

      // $dataSet.="<td style='padding:3px 0;'><input type='text' name='bar_code[".$i."]' id='bar_code_id_".$i."' value=''><span id='bar_code_error_".$i."' ></span></td>";

      $dataSet.="<td style='padding:3px 0;' ><input type='text' readonly name='component_details[".$i."][comp_price]' id='comp_price_id_".$i."' value='".$dt->unit_price."'><span id='comp_price_id_".$i."' ></span></td>";

       

      $dataSet.="<td style='padding:3px 0;' ><input  type='text' name='component_details[".$i."][expiry_date]' id='expiry_date_id_".$i."' readonly value='".$get_expiry_date."'><span id='expiry_date_error_".$i."' onchange='return false;'>".$check_script."</span></td>";
      $dataSet.="</tr>";
      $i++;
    }
    $dataSet.="</table>";
  }
  else if($res!="empty" && $component_data!="empty")
  {

      $dataSet="<table style='width:100%;'><td></td><td><b>Component Name</b></td><td><b>Quantity/Barcode</b></td><td><b>Component Price</b></td> <td><b>Expiry Date</b></td>";
      //<td><b>Bag Bar Code</b></td> remove barcode
      $x=1;
      $not_arr=array();
      foreach($component_data as $data)
      {
        
        array_push($not_arr, $data['component_id']);
        


        $get_expiry_date= $this->get_expiry_according_to_component($collection_date,$bag_id,$data['component_id'],$collection_time);
        $get_all_bar_code= $this->donor_examination->get_all_bar_code($data['component_id'],$data['id']);

        $dataSet.="<tr>";
        $dataSet.="<td style='padding:3px 0;'><input ct='".$x."' onclick='clear_values(this);' class='cheeck'  type='checkbox' value='".$data['component_id']."' name='component_details[".$x."][component_ids]' id='component_id_".$data['id']."'  checked > <input type='hidden' name='component_details[".$x."][component_name]' id='component_name[".$x."]' value='".$data['component']."' >      </td>";
        $dataSet.="<td style='padding:3px 0;' >".$data['component']."</td>"; 

         $dataSet.="<td style='padding:3px 0;' ><input type='text' class='w-40px numeric' name='component_details[".$x."][quantity]' id='quantity_id_".$x."' value='".$data['qty']."' onkeyup='open_text_box(".$data['id'].",".$x.");'>";



          if($get_all_bar_code!='empty')
          {
            $bar=0;
            foreach($get_all_bar_code as $bar_codes)
            {
               $dataSet.="<span id='bardiv_".$x."_".$data['id']."' class='ba_div_".$data['id']."'><br><input type='text' style='margin-top: 2px;' name='component_details[".$x."][bar_code_detail][".$bar."][bar_code]' id='bar_code_id_".$x."' value='".$bar_codes->bar_code."' class='w-60px' placeholder='Barcode'/></span>";
               $bar++;
            }
          }
         


          $dataSet.="<span><span id='quantity_error_".$x."' ></span></td>";

        //$dataSet.="<td style='padding:3px 0;' ><input type='text' name='bar_code[".$x."]' id='bar_code_id_".$x."' value='".$data['bar_code']."'><span id='bar_code_error_".$x."' ></span></td>";
        
        $dataSet.="<td style='padding:3px 0;' ><input type='text' readonly name='component_details[".$x."][comp_price]' id='comp_price_id_".$x."' value='".$data['component_price']."'><span id='comp_price_id_".$x."' ></span></td>";
        $date_val='';
         if(!empty($data['expiry_date']))
         {
          if($data['expiry_date']!='0000-00-00')
          {
            $date_val=date('d-m-Y H:i:s',strtotime($data['expiry_date']));
          }
           else
         {
          $date_val=$get_expiry_date;
         }
         }
         else
         {
          $date_val=$get_expiry_date;
         }
        

        $dataSet.="<td style='padding:3px 0;'><input type='text' name='component_details[".$x."][expiry_date]' id='expiry_date_id_".$x."' value='".$date_val."' readonly><span id='expiry_date_error_".$x."'  ></span></td>";
        $dataSet.="</tr>";
        $x++;
      }
      $j=$x;

      foreach($res as $tt)
      { 
         //print_r($tt);
         
        //print_r($get_expiry_date);
        if(!in_array($tt->component_id,$not_arr))
        {
          $get_expiry_date= $this->get_expiry_according_to_component($collection_date,$bag_id,$tt->id,$collection_time);

          $dataSet.="<tr>";
          $dataSet.="<td style='padding:3px 0;'><input ct='".$j."' onclick='clear_values(this);'  class='cheeck'  type='checkbox' value='".$tt->component_id."' name='component_details[".$j."][component_ids]' id='component_id_".$tt->id."' > <input type='hidden' name='component_details[".$j."][component_name]' id='component_name[".$j."]' value='".$tt->component."' >  </td>";
          $dataSet.="<td style='padding:3px 0;' >".$tt->component."</td>"; 

          $dataSet.="<td style='padding:3px 0;' ><input type='text' class='w-40px numeric' name='component_details[".$j."][quantity]' id='quantity_id_".$j."' value='' onkeyup='open_text_box(".$tt->id.",".$j.");'><span id='bardiv_".$j."_".$tt->id."' class='ba_div_".$tt->id."'><span><span id='quantity_error_".$j."'></span></td>";

          // $dataSet.="<td style='padding:3px 0;' ><input type='text' name='bar_code[".$j."]' id='bar_code_id_".$j."' value=''><span id='bar_code_error_".$j."' ></span></td>";

          $dataSet.="<td style='padding:3px 0;' ><input type='text' readonly name='component_details[".$j."][comp_price]' id='comp_price_id_".$j."' value='".$tt->unit_price."'><span id='comp_price_id_".$j."' ></span></td>";

       // $date_val='';
         //print($get_expiry_date);
           

          $dataSet.="<td style='padding:3px 0;' ><input type='text' name='component_details[".$j."][expiry_date]' id='expiry_date_id_".$j."' readonly value='".$get_expiry_date."'><span id='expiry_date_error_".$j."' ></span></td>";
          $dataSet.="</tr>";
          $j++;
        }

      }


      $dataSet.="</table>";
  }
  
  echo $dataSet;
}
// Function to get component for bag type

// Please write code above this
}
?>
