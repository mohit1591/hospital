<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teeth_chart extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dental/teeth_chart/Teeth_chart_model','teeth_chart');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        //echo "hi";die;
        unauthorise_permission('285','1682');
        $data['page_title'] = 'Teeth Chart List'; 
        $this->load->view('dental/teeth_chart/list',$data);
    }

    public function ajax_list()
    { 
       
        unauthorise_permission('285','1682');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->teeth_chart->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $teeth_chart) {
         // print_r($chief_complaints);die;
            $no++;
            $row = array();
            if($teeth_chart->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else
            {
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
            $teeth_name='';
            $teeth_type='';
            if(!empty($teeth_chart->teeth_name))
            {
              if($teeth_chart->teeth_name==1)
              {
              $teeth_name='Permanent Teeth';
            }
            else
            {
               $teeth_name='Deciduous Teeth';
            }
            }  
            if(!empty($teeth_chart->teeth_type))
            {
              if($teeth_chart->teeth_type==1)
              {
              $teeth_type='Upper Left';
            }
            if($teeth_chart->teeth_type==2)
              {
              $teeth_type='Upper Right';
            }
            if($teeth_chart->teeth_type==3)
              {
              $teeth_type='Lower Left';
            }
           if($teeth_chart->teeth_type==4)
              {
              $teeth_type='Lower Right';
            }
            
            }               
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$teeth_chart->id.'">'.$check_script; 
            $row[] = $teeth_name;
            $row[] = $teeth_type;  
            $row[] = $teeth_chart->teeth_number;    
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($teeth_chart->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1684',$users_data['permission']['action']))
          {
               $btnedit = ' <a onClick="return edit_teeth_chart('.$teeth_chart->id.');" class="btn-custom" href="javascript:void(0)" style="'.$teeth_chart->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1685',$users_data['permission']['action']))
          {
               $btndelete = ' <a class="btn-custom" onClick="return delete_teeth_chart('.$teeth_chart->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
         }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->teeth_chart->count_all(),
                        "recordsFiltered" => $this->teeth_chart->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
       unauthorise_permission('285','1683');
        $data['page_title'] = "Add Teeth Chart";  
        $data['teeth_number_list']=$this->teeth_chart->get_list_teeth_number();
       //print"<pre>";print_r($data['teeth_number_list']);
        $post = $this->input->post();
         //print"<pre>";print_r($post);
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'teeth_name'=>"",
                                  'teeth_number'=>"",
                                  'teeth_type'=>"",
                                  'teeth_image'=>"",
                                  'sort_order'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->teeth_chart->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/teeth_chart/add',$data);       
    }
    
    public function edit($id="")
    {
       unauthorise_permission('285','1684');
    $data['teeth_number_list']=$this->teeth_chart->get_list_teeth_number();

     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->teeth_chart->get_by_id($id);
       //print_r($result);
        $data['page_title'] = "Update Teeth Chart";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'teeth_name'=>$result['teeth_name'],
                                  'teeth_number'=>$result['teeth_number'],
                                  'teeth_type'=>$result['teeth_type'],
                                  'sort_order'=>$result['sort_order'],
                                  'teeth_image'=>$result['teeth_image'],
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->teeth_chart->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/teeth_chart/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('teeth_name', 'teeth type', 'trim|required'); 
        $this->form_validation->set_rules('teeth_type', 'teeth category', 'trim|required'); 
        $this->form_validation->set_rules('teeth_number', 'teeth number', 'trim|required'); 
         $this->form_validation->set_rules('sort_order', 'sort order', 'trim|required');
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'teeth_name'=>$post['teeth_name'], 
                                        'teeth_type'=>$post['teeth_type'],
                                        'teeth_number'=>$post['teeth_number'],
                                        'sort_order'=>$post['sort_order'],
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }


    
 
    public function delete($id="")
    {
        unauthorise_permission('285','1685');
       if(!empty($id) && $id>0)
       {
           $result = $this->teeth_chart->delete($id);
           $response = "teeth chart Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('285','1685');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->teeth_chart->deleteall($post['row_id']);
            $response = "teeth chart successfully deleted.";
            echo $response;
        }
    }

    


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission('285','1686');
        $data['page_title'] = 'Teeth Chart Archive List';
        $this->load->helper('url');
        $this->load->view('dental/teeth_chart/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('285','1686');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('dental/teeth_chart/Teeth_chart_archive_model','teeth_chart_archive'); 

        $list = $this->teeth_chart_archive->get_datatables(); 
       // print_r($list);die; 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $teeth_chart) { 
            $no++;
            $row = array();
            if($teeth_chart->status==1)
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

             $teeth_name='';
            $teeth_type='';
            if(!empty($teeth_chart->teeth_name))
            {
              if($teeth_chart->teeth_name==1)
              {
              $teeth_name='Permanent Teeth';
            }
            else
            {
               $teeth_name='Deciduous Teeth';
            }
            }  
            if(!empty($teeth_chart->teeth_type))
            {
              if($teeth_chart->teeth_type==1)
              {
              $teeth_type='Upper Left';
            }
            if($teeth_chart->teeth_type==2)
              {
              $teeth_type='Upper Right';
            }
            if($teeth_chart->teeth_type==3)
              {
              $teeth_type='Lower Left';
            }
           if($teeth_chart->teeth_type==4)
              {
              $teeth_type='Lower Right';
            }
            
            }               
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$teeth_chart->id.'">'.$check_script; 
            $row[] = $teeth_name;
            $row[] = $teeth_type;  
            $row[] = $teeth_chart->number;    
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($teeth_chart->created_date));
            
          $btnrestore='';
          $btndelete='';
          if(in_array('1271',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_teeth_chart('.$teeth_chart->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1270',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$teeth_chart->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
           }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->teeth_chart_archive->count_all(),
                        "recordsFiltered" => $this->teeth_chart_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
         unauthorise_permission('285','1688');
        $this->load->model('dental/teeth_chart/Teeth_chart_archive_model','teeth_chart_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->teeth_chart_archive->restore($id);
           $response = "Teeth chart successfully restore in Teeth chart list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('285','1688');
        $this->load->model('dental/teeth_chart/Teeth_chart_archive_model','teeth_chart_archive');  
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->teeth_chart_archive->restoreall($post['row_id']);
            $response = "Teeth chart successfully restore in Teeth chart list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
          unauthorise_permission('285','1687');
     $this->load->model('dental/teeth_chart/Teeth_chart_archive_model','teeth_chart_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->teeth_chart_archive->trash($id);
           $response = "Teeth chart successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('285','1687');
       $this->load->model('dental/teeth_chart/Teeth_chart_archive_model','teeth_chart_archive');  
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->teeth_chart_archive->trashall($post['row_id']);
            $response = "Teeth chart successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function teeth_number_dropdown()
  {

      $teeth_number = $this->teeth_number->teeth_number_list();
      $dropdown = '<option value="">Select teeth number</option>'; 
      if(!empty($teeth_number))
      {
        foreach($teeth_number as $teeth_number_list)
        {
           $dropdown .= '<option value="'.$teeth_number_list->id.'">'.$teeth_number_list->number.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>