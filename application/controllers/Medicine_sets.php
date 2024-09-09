<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_sets extends CI_Controller {
 
    function __construct() 
    {
        parent::__construct();  
        auth_users();  
        $this->load->model('medicine_sets/medicine_set_model','medicine_set');
        $this->load->library('form_validation');
    }

  public function index()
  {

        unauthorise_permission('394','2410');
        $users_data = $this->session->userdata('auth_users');    
        $data['page_title'] = 'Medicine Set List';
        $this->load->view('medicine_sets/list',$data);
  }

    public function ajax_list()
    {   
        unauthorise_permission('394','2410');
        $users_data = $this->session->userdata('auth_users');

        $list = $this->medicine_set->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
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
           
        foreach ($list as $test) 
        {
          $row = array(); 
            $no++;
             if($users_data['parent_id']==$test->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test->id.'">'.$check_script; 
             }else{
               $row[]='';
             }
            $row[] = $no;
            $row[] = $test->set_name;
            $row[] = date('d-m-Y h:i A',strtotime($test->created_date));
              if(in_array('2410',$users_data['permission']['action']))
              {
                $btn_edit = ' <a class="btn-custom" href="'.base_url("medicine_sets/edit_set/".$test->id).'" title="Edit Booking"><i class="fa fa-pencil"></i> Edit</a>';
              }
              if(in_array('2410',$users_data['permission']['action']))
              {
                $btn_delete = ' <a class="btn-custom" onClick="return delete_medicine_set_booking('.$test->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
              }
            // End Action Button //
            $row[] = $btn_edit.$btn_delete;    
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_set->count_all(),
                        "recordsFiltered" => $this->medicine_set->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    
    public function delete_set($id="")
    {
        unauthorise_permission('394','2410');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_set->delete_medicine_set($id);
           $response = "Medicine Set successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('394','2410');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_set->deleteall($post['row_id']);
            $response = "Medicine Set Booking successfully deleted.";
            echo $response;
        }
    }


    public function add_medicine_set()
    {
       unauthorise_permission('394','2410');
      $data['page_title']= 'Add Medicine Set';
      $data['set_data']='';
      $data['form_error'] = []; 
      $post = $this->input->post();  
      if(!empty($post))
      {

            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $result=$this->medicine_set->save_medicine_set();
                if($result)
                $this->session->set_flashdata('success','Medicine Set Added Successfully.');
                redirect(base_url('medicine_sets'));
              //  echo 1;
              // return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors(); 
                 $this->load->view('medicine_sets/booking',$data); 
            } 
      }
      else{
        $this->load->view('medicine_sets/booking',$data);
      }
      
    }

    public function edit_set($id='')
    {
       unauthorise_permission('394','2410');
      $data['page_title']= 'Edit Medicine Set';
      $users_data = $this->session->userdata('auth_users');
      $data['set_data']=$this->medicine_set->get_medicine_set_data($users_data['parent_id'],$id);
      $data['set_id']= $id;
      $post = $this->input->post();  
      if(!empty($post))
      {
           $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->medicine_set->save_medicine_set();
                $this->session->set_flashdata('success','Medicine Set Updated Successfully.');
                redirect(base_url('medicine_sets'));
               // echo 1;
               // return false;                
            }
            else
            {
                $data['form_error'] = validation_errors();  
                 $this->load->view('medicine_sets/booking',$data);
            }     
      }
      else{
        $this->load->view('medicine_sets/booking',$data);
      }      
    }

    private function _validate($id='')
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('set_name', 'Set name', 'trim|required|callback_check_unique_value['.$id.']'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['set_data'] = array(
                                        'set_id'=>$post['set_id'],
                                        'set_name'=>$post['set_name'] 
                                       ); 
            return $data['form_data'];
        }   
    }

  public function save_tapper_set()
  {
    $post=$this->input->post();
    $this->medicine_set->save_medicine_freqdata();
    return 1;
  }


 public function tapper_ajax_list()
  {
    $this->load->helper('ganeral_helper');
    $users_data = $this->session->userdata('auth_users');
    $post=$this->input->post();
    $qtyoption = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,20,25,30,35,40,45,50);
    // 
    $tpsdata=get_medicine_set_tapperdata($post['medi_id'], $users_data['parent_id'], $post['set_id']);
    if(!empty($tpsdata)){ 
      $ij=0;
      $html='';
        foreach ($tpsdata as $tpkey => $tpd) 
        {
            //$tpd['st_date']
            if(empty($tpd['st_date']))
            {
                $Date = date('d/m/Y'); //"5/1/2022";
                //echo date('d-m-Y',strtotime($Date));
                $date_s = date('d/m/Y', strtotime($Date. ' + '.$ij.' days'));
               
            }
            else
            {
                $date_s = $tpd['st_date'];
            }
         $visuald='d-none';
         
            $selected='';
            foreach ($qtyoption as $opt)
            {
             if($opt==$tpd['day'])
             {
                $selected.='<option value="'.$opt.'" selected>'.$opt.'</option>';
             }
             else
             {
                $selected.='<option value="'.$opt.'">'.$opt.'</option>';
             }
            } 
            
            $ij++;   
          $html.='<tr id="med_freq_row_'.$tpkey.'"><td style="width:unset;"><input type="text" name="tp_data['.$tpkey.'][sn]" style="width:60px;" class="form-contorl" value="'.$ij.'"></td><td><select name="tp_data['.$tpkey.'][wdays]" class="stedt w-60px dayselect" style="width:60px;" onchange="list_wday(this.value,'.$tpkey.')" id="week_day_'.$tpkey.'">'.$selected.'</select><input style="width:70px;" name="tp_data['.$tpkey.'][days]" type="number" value="1" class="form-contorl stedtr '.$visuald.'"  readonly></td><td><input name="tp_data['.$tpkey.'][st_date]" type="text" style="width:85px;" class="form-contorl st_dateo" id="st_dateo_'.$tpkey.'" value="'.$date_s.'"></td><td class="stedt"><input name="tp_data['.$tpkey.'][en_date]" style="width:85px;" type="text" class="form-contorl en_dateo" style="width:60px;" id="en_dateo_'.$tpkey.'" value="'.$tpd['en_date'].'"></td><td><input name="tp_data['.$tpkey.'][st_time]" type="text" style="width:60px;" class="form-contorl datepicker3" value="'.$tpd['st_time'].'"></td><td><input name="tp_data['.$tpkey.'][en_time]" style="width:60px;" type="text" class="form-contorl datepicker3" value="'.$tpd['en_time'].'"></td><td><input  style="width:60px;" type="number" name="tp_data['.$tpkey.'][freq]" value="'.$tpd['freq'].'" class="form-contorl w-100px"></td><td><input type="number" style="width:60px;" name="tp_data['.$tpkey.'][intvl]" class="form-contorl" value="'.$tpd['intvl'].'" ></td><td class="del_all del_lst_'.$tpkey.'"><a href="#" onclick="remove_medicine_freq('.$tpkey.')" class="btn-custom"><i class="fa fa-times"></i></a></td> </tr><script>$(".datepicker3").datetimepicker({
          format: "LT"});</script>';
        } 
//<input type="hidden" id="med_id_'.$tpkey.'" name="advs[medication]['.$tpkey.'][med_id]" value="'.$tpd['med_id'].'" >
    } 
     echo json_encode($html);
  }

    function check_unique_value($set_name, $id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->medicine_set->check_unique_value($users_data['parent_id'], $set_name,$id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Medicine Set name already exist.');
            $response = false;
        }
        return $response;
    }

// please write code above    
}
?>