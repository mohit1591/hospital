<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diagnosis_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    auth_users();
    $this->load->model('eye/diagnosis_set/diagnosis_set_model','diagnosis_set');
    $this->load->library('form_validation');
  }

  public function index()
  { 
    unauthorise_permission('393','2409');
    $data['page_title'] = 'Custom ICD List'; 
    $this->load->view('eye/diagnosis_set/list',$data);
  }

  public function ajax_list()
  { 
    unauthorise_permission('393','2409');
    $users_data = $this->session->userdata('auth_users');
    $sub_branch_details = $this->session->userdata('sub_branches_data');
    $parent_branch_details = $this->session->userdata('parent_branches_data');
    $list = $this->diagnosis_set->get_datatables();
        //print '<pre>'; print_r($list);die;
    $data = array();
    $no = $_POST['start'];
    $i = 1;
    $total_num = count($list);
   //  print_r(json_decode($list[0]->attached_icd));die;
    foreach ($list as $diagnosis) {
      $new_icd=json_decode($diagnosis->new_icd);
      $attached_icd=json_decode($diagnosis->attached_icd);
      if($diagnosis->custom_type==1)
      {
        $icd_name=$new_icd->icd_name;
        $icd_code=$new_icd->icd_code;
        $is_synonym='No';
        if($new_icd->is_laterality==1)
        {
          $is_laterality='Yes';
        }
        else{
          $is_laterality='No';
        }

        $is_translated='No';
      }
      elseif($diagnosis->custom_type==2)
      {
        $icd_name=$attached_icd->attach_icd_name;
        $icd_code=$attached_icd->attach_icd_code;
        $is_synonym='Yes';
        $is_laterality='No';
        $is_translated='No';
      }

      $no++;
      $row = array();
      if($diagnosis_set->status==1)
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
        $check_script = "";
      }                 

            ////////// Check list end ///////////// 
      $checkboxs = "";
      if($users_data['parent_id']==$diagnosis->branch_id)
      {
       $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$diagnosis->id.'">'.$check_script;
     }else{
       $row[]='';
     }


     $row[] = $icd_name;  
     $row[] = $icd_code;
     $row[] = $is_synonym; 
     $row[] = $is_laterality;
     $row[] = $is_translated;
     $btnedit='';
     $btndelete='';
   

     if($users_data['parent_id']==$diagnosis->branch_id)
     {
      if(in_array('2397',$users_data['permission']['action'])){
       $btnedit =' <a onClick="return edit_diagnosis('.$diagnosis->id.','.$diagnosis->custom_type.');"  class="btn-custom" href="javascript:void(0)" style="'.$diagnosis->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
     }
      if(in_array('2397',$users_data['permission']['action'])){
       $btndelete = ' <a class="btn-custom" onClick="return delete_diagnosis('.$diagnosis->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
     }
   }

   $row[] = $btnedit.$btndelete;


   $data[] = $row;
   $i++;

 }

 $output = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $this->diagnosis_set->count_all(),
  "recordsFiltered" => $this->diagnosis_set->count_filtered(),
  "data" => $data,
);
        //output to json format
 echo json_encode($output);
}


public function add()
{

     // echo json_encode("hi");die;

  unauthorise_permission('393','2397');
  $data['page_title'] = "Add Custom ICD ";  
  $post = $this->input->post();
  $data['form_error'] = []; 
  $data['form_data'] = array(
    'data_id'=>"", 
    'diagnosis_set_name'=>"",
    'diagnosis_id'=>"",

    'status'=>"1"
  );    

  if(isset($post) && !empty($post))
  {   
    $data['form_data'] = $this->_validate('');
    if($this->form_validation->run() == TRUE)
    {
      $this->diagnosis_set->save();
      echo 1;
      return false;

    }
    else
    {
      $data['form_error'] = validation_errors();  
    }     
  }

  $this->load->model('eye/diagnosis_set/diagnosis_set_model','diagnosis_set');
  $diagnosislist = $this->diagnosis_set->diagnosisLists();
  $data['diagnosislist']=$diagnosislist;


  $this->load->view('eye/diagnosis_set/add',$data);       
}

public function edit($id="",$type="")
{
 unauthorise_permission('393','2397');
 if(isset($id) && !empty($id) && is_numeric($id))
 {      
  $diagnosisss=$this->diagnosis_set->get_icds($id);  
  

  if($type==1)
  {
    $diagnosis=json_decode($diagnosisss['new_icd']);
    $icd_name=$diagnosis->icd_name;
    $icd_code=$diagnosis->icd_code;
  }
  elseif($type==2)
  {
    $diagnosis=json_decode($diagnosisss['attached_icd']);
    $icd_name=$diagnosis->attach_icd_name;
    $icd_code=$diagnosis->attach_icd_code;
  }
  $data['page_title'] = "Update Custom ICD";  
  $post = $this->input->post();
  $data['form_error'] = ''; 
  $data['icd_code']=$icd_code;
  $data['attached_diagnosis']=$diagnosis->attached_diagnosis;
  $data['type']=$type;
  $data['form_data'] = array(
    'data_id'=>$id,
    'icd_name'=>$icd_name,
    'eye_type'=>$diagnosisss['eye_type']
  );  

 // print_r($diagnosis);die;

  if(isset($post) && !empty($post))
  {  

    $data['form_data'] = $this->_validate($id);

    if($this->form_validation->run() == TRUE)
    {


      if($post['type']==1)
      {

       $new_icd=array('icd_name'=>$post['icd_name'],'icd_code'=>$icd_code,'is_laterality'=>$diagnosis->is_laterality);
       $this->db->set('eye_type',$post['eye_type']);
       $this->db->where('hms_custom_icds.id',$post['data_id']);
       $this->db->update('hms_custom_icds',array('new_icd'=>json_encode($new_icd)));

     }
     elseif($post['type']==2){

      $attached_icd=array('attached_diagnosis'=>$diagnosis->attached_diagnosis,'attach_icd_code'=>$icd_code,'attach_icd_name'=>$post['icd_name']);
      $this->db->set('eye_type',$post['eye_type']);
      $this->db->where('hms_custom_icds.id',$post['data_id']);
      $this->db->update('hms_custom_icds',array('attached_icd'=>json_encode($attached_icd)));
    }
     // echo 1;
    return false;

  }
  else
  {
    $data['form_error'] = validation_errors();  
  } 

}
$this->load->model('eye/diagnosis_set/diagnosis_set_model','diagnosis_set');
$diagnosislist = $this->diagnosis_set->diagnosisLists();
$data['diagnosislist']=$diagnosislist;


$this->load->view('eye/diagnosis_set/edit',$data);       
}
}

private function _validate($id='')
{
  $post = $this->input->post(); 
 // print_r($post);die;   
  $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
  $this->form_validation->set_rules('icd_name', 'ICD Name', 'trim|required'); 

  if ($this->form_validation->run() == FALSE) 
  {  

    $data['form_data'] = array(

      'data_id'=>$post['data_id'],
      'icd_name'=>$post['icd_name'],

    );   
    return $data['form_data'];
  }  
  
}

public function delete($id="")
{
 unauthorise_permission('393','2397');
 if(!empty($id) && $id>0)
 {

   $result = $this->diagnosis_set->delete($id);
   $response = "Diagnosis Set successfully deleted.";
   echo $response;
 }
}

function deleteall()
{
  unauthorise_permission('393','2397');
  $post = $this->input->post();  
  if(!empty($post))
  {
    $result = $this->diagnosis_set->deleteall($post['row_id']);
    $response = "Diagnosis Set successfully deleted.";
    echo $response;
  }
}

public function view($id="")
{  
 if(isset($id) && !empty($id) && is_numeric($id))
 {      
  $data['form_data'] = $this->diagnosis_set->get_by_id($id);  
  $data['page_title'] = $data['form_data']['diagnosis_set']." detail";
  $this->load->view('eye/diagnosis_set/view',$data);     
}
}  


    ///// employee Archive Start  ///////////////
public function archive()
{
  unauthorise_permission('393','1283');
  $data['page_title'] = 'Diagnosis Set Archive List';
  $this->load->helper('url');
  $this->load->view('eye/diagnosis_set/archive',$data);
}

public function archive_ajax_list()
{
  unauthorise_permission('393','1283');
  $this->load->model('eye/diagnosis_set/diagnosis_archive_model','diagnosis_archive'); 


  $list = $this->diagnosis_archive->get_datatables();


  $data = array();
  $no = $_POST['start'];
  $i = 1;
  $total_num = count($list);
  foreach ($list as $diagnosis_set) {
         // print_r($diagnosis_set);die;
    $no++;
    $row = array();
    if($diagnosis_set->status==1)
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
      $check_script = "";
    }                 
            ////////// Check list end ///////////// 
    $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$diagnosis_set->id.'">'.$check_script; 
    $row[] = $diagnosis_set->diagnosis_set_name;  
    $row[] = $status;
    $row[] = date('d-M-Y H:i A',strtotime($diagnosis_set->created_date)); 
    $users_data = $this->session->userdata('auth_users');
    $btnrestore='';
    $btndelete='';
    if(in_array('1285',$users_data['permission']['action'])){
     $btnrestore = ' <a onClick="return restore_diagnosis('.$diagnosis_set->id.');" class="btn-custom" href="javascript:void(0)"  title="diagnosis Set"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
   }
   if(in_array('1284',$users_data['permission']['action'])){
    $btndelete = ' <a onClick="return trash('.$diagnosis_set->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
  }
  $row[] = $btnrestore.$btndelete;


  $data[] = $row;
  $i++;
}

$output = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $this->diagnosis_archive->count_all(),
  "recordsFiltered" => $this->diagnosis_archive->count_filtered(),
  "data" => $data,
);
        //output to json format
echo json_encode($output);
}

public function restore($id="")
{
  unauthorise_permission('393','1285');
  $this->load->model('eye/diagnosis_set/diagnosis_archive_model','diagnosis_archive');
  if(!empty($id) && $id>0)
  {
   $result = $this->diagnosis_archive->restore($id);
   $response = "Diagnosis set successfully restore in diagnosis set list.";
   echo $response;
 }
}

function restoreall()
{ 
  unauthorise_permission('393','1285');
  $this->load->model('eye/diagnosis_set/diagnosis_archive_model','diagnosis_archive');
  $post = $this->input->post();  
  if(!empty($post))
  {
    $result = $this->diagnosis_archive->restoreall($post['row_id']);
    $response = "Diagnosis set successfully restore in diagnosis set list.";
    echo $response;
  }
}

public function trash($id="")
{
  unauthorise_permission('393','1284');
  $this->load->model('eye/diagnosis_set/diagnosis_archive_model','diagnosis_archive');
  if(!empty($id) && $id>0)
  {
   $result = $this->diagnosis_archive->trash($id);
   $response = "Diagnosis set successfully deleted parmanently.";
   echo $response;
 }
}

function trashall()
{
  unauthorise_permission('393','1284');
  $this->load->model('eye/diagnosis_set/diagnosis_archive_model','diagnosis_archive');
  $post = $this->input->post();  
  if(!empty($post))
  {
    $result = $this->diagnosis_archive->trashall($post['row_id']);
    $response = "Diagnosis set successfully deleted parmanently.";
    echo $response;
  }
}


public function diagnosis_dropdown()
{
  $ot_type_list = $this->diagnosis_set->diagnosis_list();
  $dropdown = '<option value="">Select Diagnosis</option>'; 
  if(!empty($ot_type_list))
  {
    foreach($ot_type_list as $ot_type)
    {
     $dropdown .= '<option value="'.$ot_type->id.'">'.$ot_type->diagnosis.'</option>';
   }
 } 
 echo $dropdown; 
}


function check_diagnosis_set($diagnosis_set, $id='') 
{     
  $users_data = $this->session->userdata('auth_users');
  $result = $this->diagnosis_set->check_unique_value($users_data['parent_id'], $diagnosis_set,$id);
  if($result == 0)
    $response = true;
  else {
    $this->form_validation->set_message('check_diagnosis_set', 'Diagnosis set name already exist.');
    $response = false;
  }
  return $response;
}
/* 10-12-2019 */
public function diagnosisLists()
{
  $this->load->model('general/general_model');
  $section_id=$this->uri->segment(4);


   //  print_r($section_id);die;
  $array1 =array();

  $icd_eye_section_codes=$this->general_model->icd_eye_code($section_id,4,6);
  $i=0;
  foreach($icd_eye_section_codes as $section_codes)
  {


    $check_length=$this->general_model->check_eye_code($section_codes['icd_id']);
    $check_second_length=$this->general_model->check_secondeye_code($section_codes['icd_id']);
    
    if($check_length > 0)
    {
        //echo $i.'one'; exit;
      $icd_eye_section_code2=$this->general_model->icd_eye_code_three($section_codes['icd_id'],5,7);
        //echo "<pre>"; print_r($icd_eye_section_code2); exit;

      if(!empty($icd_eye_section_code2))
      {

        foreach ($icd_eye_section_code2 as $value) 
        {
          $array1[$i]['id'] =  $value['id'];
          $array1[$i]['icd_id'] =  $value['icd_id'];
          $array1[$i]['descriptions'] = $value['descriptions'];
          $i++;
        }
      }

      $k=$i;
       // exit;
       //array_push($icd_combine,$icd_eye_section_code2);
    }
    elseif($check_second_length > 0)
    {
      $icd_eye_section_code1=$this->general_model->icd_eye_code_two($section_codes['icd_id'],4,6);
        //echo "<pre>"; print_r($icd_eye_section_code1); exit;
        //echo $k; exit;
      if(!empty($icd_eye_section_code1))
      {

        foreach ($icd_eye_section_code1 as $value) 
        {
          $array1[$k]['id'] =  $value['id'];
          $array1[$k]['icd_id'] =  $value['icd_id'];
          $array1[$k]['descriptions'] = $value['descriptions'];
          $k++;
        }
        $i=$k;
      }
    }      

    }

    if(!empty($array1))
    {
      foreach($array1 as $section_code)
      {
            //  print_r($section_code); 
       $options .= '<option onclick="diagnosis_hirerachy('.$section_code['id'].');" value="'.$section_code['icd_id'].'">'.ucwords(strtolower($section_code['descriptions'])).'</option>';
     } 
   }
   echo $options;

 }

 /* 10-12-2019 */
 /* 11-12-2019 */
 public function diagno_Lists()
 {
  $this->load->model('general/general_model');
  $keyword=$this->uri->segment(4);
   //  print_r($section_id);die;
  $min=4;
  $max=7;
  $icd_eye_section_code=$this->diagnosis_set->icd_diagnosis_list($min,$max,$keyword);

  if(!empty($icd_eye_section_code))
  {
    foreach($icd_eye_section_code as $section_code)
    {
     $options .= '<div class="append_row_opt" data-type="'.$section_code['icd_id'].'">'.ucwords(strtolower($section_code['descriptions'])).'</div>';
   } 
 } 
 echo $options;

}

public function add_icd()
{
  $users_data = $this->session->userdata('auth_users');
  $post=$this->input->post();

  if(!empty($post) && $post!="")
  {
    $custom_type=$post['custom_type'];
    if($custom_type==1)
    {

      $is_laterality=0;

      if($post['is_laterality']==1)
      {
        $is_laterality=1;
      }
      $this->load->helper('ganeral');
      $icd_code=generate_unique_id(54);
      

      $new_icd=array('icd_name'=>$post['icd_name'],'icd_code'=>$icd_code,'is_laterality'=>$is_laterality);
       //print_r($new_icd);die;
      $attached_icd='';

      $this->db->insert('hms_custom_icds',array('branch_id'=>$users_data['parent_id'],'custom_type'=>$custom_type,'new_icd'=>json_encode($new_icd), 'eye_type'=>$post['eye_type'],'attached_icd'=>json_encode($attached_icd),'created_date'=>date('Y-m-d h:i:s'),'ip_address'=>$_SERVER['REMOTE_ADDR']));
    }
    elseif($custom_type==2)
    {
      $new_icd='';
     // $attached_icd_name=implode(',',$post['attach_icd_name']);
      $attached_icd_names=$post['attach_icd_name'];


      foreach($attached_icd_names as $attached_icd_name)
      {

       $attached_icd=array('attached_diagnosis'=>$post['attached_diagnosis'],'attach_icd_code'=>$post['attach_icd_code'],'attach_icd_name'=>$attached_icd_name);
       $this->db->insert('hms_custom_icds',array('branch_id'=>$users_data['parent_id'],'custom_type'=>$custom_type,'new_icd'=>json_encode($new_icd),'attached_icd'=>json_encode($attached_icd),'created_date'=>date('Y-m-d h:i:s'),'ip_address'=>$_SERVER['REMOTE_ADDR']));
     }
   }
   $this->session->set_flashdata('success','Custom ICD successfully created.'); 
 }
}
/* 11-12-2019 */


}
?>