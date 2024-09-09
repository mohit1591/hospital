<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_price extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('test_price/test_price_model','test_price');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        // unauthorise_permission('11','57');
        $data['page_title'] = 'Test Price'; 
        $this->load->view('test_price/test_price_list',$data);
    }

  
  public function get_allsub_branch_list()
  {
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
         $users_data = $this->session->userdata('auth_users');
        // if($users_data['users_role']==2){
            $dropdown = '<label class="patient_sub_branch_label">Branches</label> <select id="sub_branch_id" name="sub_branch_id" onchange="get_test_list(this.value);"><option value="">Select Sub Branch</option><option  value='.$users_data['parent_id'].'>Self</option>';
            if(!empty($sub_branch_details)){
                $i=0;
                foreach($sub_branch_details as $key=>$value){
                    $dropdown .= '<option value="'.$sub_branch_details[$i]['id'].'">'.$sub_branch_details[$i]['branch_name'].'</option>';
                    $i = $i+1;
                }
               
            }
            $dropdown.='</select>';
            echo $dropdown; 
        // }
         
       
    }
 
   public function get_transactional_doctors_list()
   { //onchange = "get_test_list(this.value);"
      $this->load->model('test_price/Test_price_model','test_price');
      $result = $this->test_price->get_transactional_doctors_list();
      $dropdown = '<label class="patient_sub_branch_label">Doctors</label> <select  id="doctors_id" name="doctors_id"><option value="">Select Doctors</option>';
       if(!empty($result))
       { 
          for($i=0;$i<count($result);$i++){
                $dropdown .= '<option value="'.$result[$i]->id.'">'.$result[$i]->doctor_name.'</option>';
                   
          } 
       } 
        $dropdown .= '</select>';
        echo $dropdown;
   }

   public function get_all_departments_list()
   {
       $this->load->model('test_price/Test_price_model','test_price');
       $dropdown='';
       $result = $this->test_price->get_test_departments(5);
       if(!empty($result)){
           $dropdown = '<label class="patient_sub_branch_label">Department</label> <select id="department_id" onchange = "get_test_heads(this.value);"><option value="">Select Department</option>';
          for($i=0;$i<count($result);$i++){
                $dropdown .= '<option value="'.$result[$i]->id.'">'.$result[$i]->department.'</option>';
                   
          }
          $dropdown.='</select>';
         
       }
       echo $dropdown;
   }
   public function get_test_heads_list()
   {
     $department_id = $this->input->post('department_id');
     $dropdown='';
     if(!empty($department_id)){
          $this->load->model('test_price/Test_price_model','test_price');
          $result = $this->test_price->get_test_heads($department_id);
          if(!empty($result)){
               $dropdown = '<label class="patient_sub_branch_label">Test Heads</label> <select id="test_heads_id" onchange="get_test_list(this.value);"><option value="">Select Test Heads</option>';
               for($i=0;$i<count($result);$i++){
                    $dropdown .= '<option value="'.$result[$i]->id.'">'.$result[$i]->test_heads.'</option>';
               }
               $dropdown.='</select>';
               
          }
     }
     echo $dropdown;
     }

     public function get_test_list()
     {
          $post = $this->input->post(); 
          $data['page_title'] = 'Test Price'; 
          $formated_data='';
          if(!empty($post['doctors_id']) || !empty($post['branch_id']))
          {
              $result = $this->test_price->get_test_list(); 
              if(!empty($result))
              { 
                 $formated_data = $this->get_format_table($result,$post); 
              }
              else
              {
                $formated_data='<tr><td colspan="6" align="center"><font color="#b64a30">Test not available.</font></td></tr>';
              }
          }
          else
          {
             $formated_data='<tr><td colspan="6" align="center"><font color="#b64a30">Test not available.</font></td></tr>';
          } 
          echo $formated_data;
     }

     public function get_format_table($result='',$post=array())
     {
          $users_data = $this->session->userdata('auth_users');
          $rows=''; 
          if(isset($post['branch_id']) && !empty($post['branch_id']))
          {
             $branch_id = $post['branch_id'];
          }
          else
          {
             $branch_id = $users_data['parent_id'];
          }
          if(!empty($result))
          {
               $i=0;  
               foreach ($result as $test) 
               {
                  if($test['test_base_rate']>0)
                  { 
                        $test_rate = $test['test_rate'];
                        $test_base_rate = $test['test_base_rate'];
                  }
                  else
                  {
                        $test_rate = $test['rate'];
                        $test_base_rate = $test['base_rate'];
                       
                        if(isset($post['doctors_id']) && !empty($post['doctors_id']))
                         {
                            $rate_plan_data = $this->test_price->doctor_rate_plan($post['doctors_id']);
                            if(!empty($rate_plan_data))
                            {
                              /* Doctor Master rate */
                              if($rate_plan_data[0]->master_type==1)
                              {
                                 $pos_mstr = strpos($rate_plan_data[0]->master_rate,'-');
                                 if ($pos_mstr === true) 
                                 {
                                    $master_rate = str_replace('-', '',$rate_plan_data[0]->master_rate);
                                    $master_rate = $test_rate-(($test_rate/100)*$rate_plan_data[0]->master_rate);
                                 }
                                 else
                                 {
                                      $master_rate = str_replace('+', '',$rate_plan_data[0]->master_rate);
                                      $master_rate = $test_rate+(($test_rate/100)*$rate_plan_data[0]->master_rate);
                                 }
                              }
                              else
                              {
                                  $pos_mstr = strpos($rate_plan_data[0]->master_rate,'-');
                                   if ($pos_mstr === true) 
                                   {
                                      $master_rate = str_replace('-', '',$rate_plan_data[0]->master_rate);
                                      $master_rate = $test_rate-$rate_plan_data[0]->master_rate;
                                   }
                                   else
                                   {
                                        $master_rate = str_replace('+', '',$rate_plan_data[0]->master_rate);
                                        $master_rate = $test_rate+$rate_plan_data[0]->master_rate;
                                   }
                              }
                              //////////////////////////

                              /* Doctor Base rate */
                              if($rate_plan_data[0]->base_type==1)
                              {
                                 $pos_base = strpos($rate_plan_data[0]->base_rate,'-');
                                 if ($pos_base === true) 
                                 {
                                    $base_rate = str_replace('-', '',$rate_plan_data[0]->base_rate);
                                    $base_rate = $test_base_rate-(($test_base_rate/100)*$rate_plan_data[0]->base_rate);
                                 }
                                 else
                                 {
                                      $base_rate = str_replace('+', '',$rate_plan_data[0]->base_rate);
                                      $base_rate = $test_base_rate+(($test_base_rate/100)*$rate_plan_data[0]->base_rate); 
                                 }

                              }
                              else
                              {
                                  $pos_base = strpos($rate_plan_data[0]->base_rate,'-');
                                   if ($pos_base === true) 
                                   {
                                      $base_rate = str_replace('-', '',$rate_plan_data[0]->base_rate);
                                      $base_rate = $test_base_rate-$rate_plan_data[0]->base_rate;
                                   }
                                   else
                                   {
                                        $base_rate = str_replace('+', '',$rate_plan_data[0]->base_rate);
                                        $base_rate = $test_base_rate+$rate_plan_data[0]->base_rate;
                                   }
                              }
                              //////////////////////////
                              $test_rate = $master_rate;
                              $test_base_rate = $base_rate;
                            }
                            
                         } 
                  } 
                    if(!empty($test['doc_id'])){
                        
                         $rows.='<tr><td><input type="checkbox" class="checklist" name="test_id['.$i.'][id]" value="'.$test['id'].'" /></td><td>'.$test['department'].'</td><td>'.$test['test_name'].'</td><td><input type="text" name="test_id['.$i.'][test_rate]"  id="test_rate_'.$i.'"  value="'.$test_rate.'"/><input type="hidden" id="doc_id"  name="test_id['.$i.'][doc_id]" value="'.$test['doc_id'].'"/></td><td><input type="text" id="test_base_rate_'.$i.'" name="test_id['.$i.'][test_base_rate]"  value="'.$test_base_rate.'"/></td><td><button type="button" class="btn-custom" data-rateid="test_rate_'.$i.'"  
                         data-baserate="test_base_rate_'.$i.'" data-testid="'.$test['id'].'" data-docid="'.$test['doc_id'].'" id="save_test" onclick="save_test_rate(this);">Save</button></td></tr>';
                    }
                    else if(!empty($test['branch_id'])){
                        
                         $rows.='<tr><td><input type="checkbox" class="checklist" name="test_id['.$i.'][id]" value="'.$test['id'].'" /></td><td>'.$test['department'].'</td><td>'.$test['test_name'].'</td><td><input type="text" name="test_id['.$i.'][test_rate]"  id="test_rate_'.$i.'"  value="'.$test_rate.'"/><input type="hidden" id="doc_id" name="test_id['.$i.'][doc_id]" value="0"/></td><td><input type="text" id="test_base_rate_'.$i.'" name="test_id['.$i.'][test_base_rate]"  value="'.$test_base_rate.'"/></td><td><button type="button" class="btn-custom" data-rateid="test_rate_'.$i.'"  
                         data-baserate="test_base_rate_'.$i.'" data-testid="'.$test['id'].'" data-docid="'.$test['doc_id'].'" id="save_test" onclick="save_test_rate(this);">Save</button></td></tr>';
                    }
                    else {

                       
                         $rows.='<tr><td><input type="checkbox" class="checklist" name="test_id['.$i.'][id]" value="'.$test['id'].'" /></td><td>'.$test['department'].'</td><td>'.$test['test_name'].'</td><td><input type="text" name="test_id['.$i.'][test_rate]" id="rate_'.$i.'"   value="'.$test['rate'].'"/></td><td><input type="text" id="base_rate_'.$i.'" name="test_id['.$i.'][test_base_rate]"  value="'.$test['base_rate'].'"/></td><td><button data-rateid="rate_'.$i.'" data-baserate="base_rate_'.$i.'" data-testid="'.$test['id'].'" data-docid="" type="button" class="btn-custom" id="save_test" onclick="save_test_rate(this);">Save</button></td></tr>';  
                         }
                         $i++;
               }
          }
          else 
          {
               $rows.='<tr><td colspan="4" class="text-center">no record found</td></tr>';
          } 
          return $rows;
     }

     private function calc_string( $mathString )
      {
              $cf_DoCalc = create_function("", "return (" . $mathString . ");" );
             
              return $cf_DoCalc();
      }

     public function save_test_rate()
     {
          $post = $this->input->post();
          $data['page_title'] = "Test Price";
          $msg='';
          if(isset($post) && !empty($post))
          {
               if(array_key_exists('type',$post))
               {
                    if($post['type']=='1')
                    {
                         
                        if(empty($post['doc_id']))
                        {
                            $msg = 'doctors is required';
                            echo $msg;
                            die;
                        }
                        else
                        {
                            $this->test_price->save_test_rate();
                        }
                    }
                    else
                    {
                    
                         $this->test_price->save_test_rate();
                    }
               }
          }
         echo $msg;

     }

  public function save_price_list()
  {
    $post = $this->input->post();
    if(isset($post) && isset($post['test_id']) && !empty($post['test_id']))
    {  
       $this->test_price->save_test_all_rate();
    }
  } 

   
 
}
?>