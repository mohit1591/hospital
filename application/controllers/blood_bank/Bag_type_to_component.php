<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bag_type_to_component extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('blood_bank/bag_type_to_component/Bag_type_to_component_model','bag_type_to_component');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('258','1474');
        $data['page_title'] = 'Bag Type To Component List'; 
        $this->load->view('blood_bank/bag_type_to_component/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('258','1474');
        $list = $this->bag_type_to_component->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $bag_type_to_component) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($bag_type_to_component->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List /////////////////medicine_preferred_reminder_service
            $check_script = "";
            if($i==$total_num)
            {
            /*$check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";*/
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$bag_type_to_component->bag_type_id.'">'.$check_script;
            


            $row[] = $bag_type_to_component->bag_type;
            //$row[] = $bag_type_to_component->component;
            //$row[]=$name;  
            //$row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($bag_type_to_component->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
          if(in_array('1476',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_camp_details('.$bag_type_to_component->bag_type_id.');" class="btn-custom" href="javascript:void(0)" style="'.$bag_type_to_component->bag_type_id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
             }
          if(in_array('1480',$users_data['permission']['action'])){
                $btnview = ' <a onClick="return view_camp_details('.$bag_type_to_component->bag_type_id.');" class="btn-custom" href="javascript:void(0)" style="'.$bag_type_to_component->bag_type_id.'" title="view"><i class="fa fa-eye" aria-hidden="true"></i>view</a>';
          }
          if(in_array('1477',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_camp_details('.$bag_type_to_component->bag_type_id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btnview.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->bag_type_to_component->count_all(),
                        "recordsFiltered" => $this->bag_type_to_component->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
     
    public function add($id='')
    {
        unauthorise_permission('258','1475');
        $data['page_title'] = "Add Bag Type To Component"; 
        $data['bag_type_list']=$this->bag_type_to_component->bag_type_list();
        $data['component_list'] = $this->bag_type_to_component->component_list();
        $data['btc_data']="empty";
        $data['btc_id']=0;
        
        $this->load->view('blood_bank/bag_type_to_component/add',$data);       
    }


    public function save()
    {
      $response=$this->_validate();
      $users_data = $this->session->userdata('auth_users');
      if($response!="200")
      {
        echo $response;die;
      }
      else
      {
        $component_list=$this->input->post();

        $expiry_date=$this->input->post('expiry_date');
        $interval=$this->input->post('interval');
        if($this->input->post('component_id')!="" && !empty($this->input->post('component_id')))
        {
          foreach($this->input->post('component_id') as $list)
          {
            $data_arr=array( 
                              'branch_id' => $users_data['parent_id'],
                              'bag_type_id'=>$this->input->post('bag_type_id'),
                              'component_id'=>$list,
                              'expiry_time'=>$expiry_date[$list],
                              'interval_time'=>$interval[$list],
                              'status'=>$this->input->post('status'),
                              'created_by'=>$users_data['id'],
                              'created_date'=>date('Y-m-d H:i:s'),
                            );
            $this->db->insert('hms_blood_bag_type_to_component',$data_arr); 
            //echo $this->db->last_query();die;
          }
          echo json_encode(array('st'=>1, 'msg'=>"Bag type component successfully created" ));
        }
      }
    }
    
    public function edit($bag_type_id="")
    {
      unauthorise_permission('258','1476');
        $users_data = $this->session->userdata('auth_users');
        $branch_id  = $users_data['parent_id'];
        $data['page_title'] = "Update Bag Type To Component";
        $result = $this->bag_type_to_component->get_by_bag_type_id($bag_type_id);
        $data['component_list'] = $this->bag_type_to_component->component_list();
        $data['bag_type_list'] = $this->bag_type_to_component->bag_type_list();
        $data['btc_id']=$bag_type_id;
        $data['btc_data']=$result;
        $post = $this->input->post();
        $this->load->view('blood_bank/bag_type_to_component/add',$data);       
    }
     
    public function update_data()
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id  = $users_data['parent_id'];
        $response=$this->_validate();
        if($response!="200")
        {
          echo $response;die;
        }  
        else
        {
          if($this->input->post('component_id')!="" && !empty($this->input->post('component_id')))
          {
            $this->db->where('branch_id',$branch_id);
            $this->db->where('bag_type_id',$this->input->post('btc_id'));
            $this->db->delete('hms_blood_bag_type_to_component');
            $expiry_date=$this->input->post('expiry_date');
            $interval=$this->input->post('interval');
            foreach($this->input->post('component_id') as $list)
            {
              $data_arr=array( 
                                'branch_id' => $users_data['parent_id'],
                                'bag_type_id'=>$this->input->post('bag_type_id'),
                                'component_id'=>$list,
                                'expiry_time'=>$expiry_date[$list],
                                'interval_time'=>$interval[$list],
                                'status'=>$this->input->post('status'),
                                'created_by'=>$users_data['id'],
                                'created_date'=>date('Y-m-d H:i:s'),
                              );
              $this->db->insert('hms_blood_bag_type_to_component',$data_arr); 
            }
            echo json_encode(array('st'=>1, 'msg'=>"Bag type component successfully updated" ));
          }
        }
    }
    
    private function _validate()
    {
      $post = $this->input->post();    
      $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
      $this->form_validation->set_rules('bag_type_id', 'bag type', 'trim|required'); 
      $this->form_validation->set_rules('validate_component', 'Component', 'trim|required'); 
      if ($this->form_validation->run() == FALSE) 
      {  
        echo json_encode(array('st'=>0, 'bag_type'=>form_error('bag_type_id'), 'component'=>form_error('validate_component') ));
      } 
      else
      {
        return "200";
      }  
    }

     /* callbackurl */
    /* check validation laready exit */
     public function bag_type_id($str)
      {
        echo $str;die;
        $post = $this->input->post();
         if(!empty($post['bag_type_id']))
          {
               $this->load->model('blood_bank/general/blood_bank_general_model','general'); 
               if(!empty($post['btc_id']) && $post['btc_id']>0)
               {
                      $data_cat= $this->bag_type_to_component->get_by_id($post['btc_id']);
                      if($data_cat['bag_type_id']==$str && $post['btc_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $bag_type_id = $this->general->check_bag_type_id($str);

                        if(empty($bag_type_id))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('bag_type_id', 'The bag type already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $deferral_reason = $this->general->check_bag_type($post['bag_type_id'], $post['btc_id']);
                    if(empty($deferral_reason))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('bag_type_id', 'The bag type already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('bag_type_id', 'The bag type field is required.');
               return false; 
          } 
      }
     /* check validation laready exit */
 
    public function delete($id="")
    {
      unauthorise_permission('258','1477');
       if(!empty($id) && $id>0)
       {
           $result = $this->bag_type_to_component->delete($id);
           $response = "bag type to component successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('258','1477');
        $post = $this->input->post(); 
        //print_r($post);
        //die; 
        if(!empty($post))
        {
            $result = $this->bag_type_to_component->deleteall($post['row_id']);
            $response = "bag type to component successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
       unauthorise_permission('258','1480');
        $data['page_title'] = "Bag Type To Component List";
        $data['bag_type_list'] = $this->bag_type_to_component->bag_type_with_component_list($id);
        $post = $this->input->post();                
        $this->load->view('blood_bank/bag_type_to_component/view',$data);     
      
    }

     


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('258','1478');
        $data['page_title'] = 'Bag Type To Component Archive List'; 
        $this->load->helper('url');
        $this->load->view('blood_bank/bag_type_to_component/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('258','1478');
        $this->load->model('blood_bank/bag_type_to_component/Bag_type_to_component_archive_model','bag_type_to_component_archive'); 

        $list = $this->bag_type_to_component_archive->get_datatables();  
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $bag_type_to_component_archive) { 
            $no++;
            $row = array();
            if($bag_type_to_component_archive->status==1)
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
            /*$check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";*/
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$bag_type_to_component_archive->bag_type_id.'">'.$check_script; 
             $row[] = $bag_type_to_component_archive->bag_type;
            //$row[] = $bag_type_to_component_archive->component;;   
            //$row[] = $status;
           // $row[] = date('d-M-Y H:i A',strtotime($bag_type_to_component_archive->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1481',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_camp_details('.$bag_type_to_component_archive->bag_type_id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1479',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$bag_type_to_component_archive->bag_type_id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->bag_type_to_component_archive->count_all(),
                        "recordsFiltered" => $this->bag_type_to_component_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }



    public function restore($id="")
    {
        unauthorise_permission('258','1481');
        $this->load->model('blood_bank/bag_type_to_component/Bag_type_to_component_archive_model','bag_type_to_component_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->bag_type_to_component_archive->restore($id);
           $response = "Bag type to component successfully restore in bag type to component list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission('258','1481');
      $this->load->model('blood_bank/bag_type_to_component/Bag_type_to_component_archive_model','bag_type_to_component_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->bag_type_to_component_archive->restoreall($post['row_id']);
            $response = "Bag type to component successfully restore in Bag type to component list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('258','1479');
          $this->load->model('blood_bank/bag_type_to_component/Bag_type_to_component_archive_model','bag_type_to_component_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->bag_type_to_component_archive->trash($id);
           $response = "Bag type to component successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('258','1479');
         $this->load->model('blood_bank/bag_type_to_component/Bag_type_to_component_archive_model','bag_type_to_component_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->bag_type_to_component_archive->trashall($post['row_id']);
            $response = "Bag type to component successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function bag_type_archive_dropdown()
  {

      $deferral_reason_archive_list = $this->bag_type_archive->bag_type_archive_list();
      $dropdown = '<option value="">Select Bag Type</option>'; 
      if(!empty($deferral_reason_archive_list))
      {
        foreach($deferral_reason_archive_list as $deferral_reason_archive)
        {
           $dropdown .= '<option value="'.$deferral_reason_archive->id.'">'.$deferral_reason_archive->deferral_reason_archive.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>