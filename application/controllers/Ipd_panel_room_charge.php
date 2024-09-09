<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ipd_panel_room_charge extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_panel_room_charge/ipd_panel_room_charge_model','panel_room_charge');
        $this->load->library('form_validation');
        $this->load->model('general/general_model');
    }

    public function index()
    { 
        unauthorise_permission('119','729');
        $data['page_title'] = 'IPD Panel room charge List'; 
        $data['panel_list'] = $this->panel_room_charge->get_panel_list();
         
        $data['room_type_list'] = $this->general_model->room_type_list();
        $data['room_charge_type_list'] = $this->general_model->room_charge_type_list();
        $this->load->view('ipd_panel_room_charge/add',$data);
    }

    public function ajax_list_pannel_room()
    {
        unauthorise_permission('119','729');
        $users_data = $this->session->userdata('auth_users');
        $post = $this->input->post();
        $table ='';
        $room_list = $this->panel_room_charge->get_room_list($post['panel']);
        //echo "<pre>";print_r($room_list); exit;
        
        if((isset($room_list) && !empty($room_list)) && !empty($post['panel']))
        {
            $i=1;
            foreach($room_list as $particularlist)
            {  
                $table.='<tr class="append_row">';
                $table.='<td style="width:100px !important;text-align:left;">'.$particularlist->room_category.'</td>';
                $get_charges= get_room_charge_according_to_branch($users_data['parent_id']);
               	//echo "<pre>";print_r($get_charges); exit; 
                if(!empty($get_charges))
                {
                  foreach($get_charges as $charge_type)
                  {
                      $room_charges = get_panel_room_charge_accordint_to_id($users_data['parent_id'], $charge_type->id, $particularlist->id, '',$post['panel']); 
                      if(isset($room_charges) && !empty($room_charges))
                      {
                          //name="test_id['.$i.'][path_price]"
                      	$charge = $room_charges[0]['room_charge'];
                        $table.='<td><input type="text" id="price_'.$charge_type->id.'"  name="charge['.$charge_type->id.'][path_price]" onchange="add_panel_room_charge(this.value, '.$charge_type->id.','.$particularlist->id.')" value="'.$charge.'" >
                        <input type="hidden" name="test_id['.$i.'][test_id]"  id=""  value="'.$particularlist->id.'"/>
                        
                        </td>';
                      }
                      else
                      {
                      	$table.='<td><input type="text" onchange="add_panel_room_charge(this.value, '.$charge_type->id.','.$particularlist->id.')" id="charge"  id="price_'.$charge_type->id.'"  name="charge['.$charge_type->id.'][path_price]"  value="0.00">
                      	
                      	 <input type="hidden" name="test_id['.$i.'][test_id]"  id=""  value="'.$particularlist->id.'"/>
                      	 
                      	</td>';
                      	
                      	//
                      }
                       
                      
                  }

                }

                 $btn_save='<button type="button" class="btn-custom"   
                 data-testid="'.$particularlist->id.'" data-price="price_'.$i.'" data-docid="" id="save_test" onclick="save_panel_rate(this, '.$post['panel'].');">Save</button></a>';
                
                $table.='<td>'.$btn_save.'</td>';
                
                
                $table.='</tr>';
            
                $i++;
            }
            //onchange="add_panel_room_charge(this.value, '.$charge_type->id.','.$particularlist->id.')"
        }
        else
        {
             $table='<tr class="append_row"><td class="text-danger" colspan="4"><div class="text-center">No record found</div></td></tr>';
        }
               $output=array('data'=>$table);
               echo json_encode($output);
    }

    public function add()
    {
        $post = $this->input->post();
        if(isset($post) && !empty($post))
        {
            if(!empty($post['panel']))
            {
                $this->panel_room_charge->save_panel_room_charge();
                echo 1;
                return false;
            }
        }
        //print_r($post);
    }

    public function set_panel_price($panel_id="",$room_type="",$category_id="", $vals="0")
    {
        if(!empty($panel_id) && !empty($room_type))
        {
			$this->panel_room_charge->save_panel_room_charge($panel_id,$room_type,$category_id,$vals);
			echo 1;
			return false;
        }
    }

    

    public function increase_panel_price($panel_id="",$type="", $vals="0")
    {
        if(!empty($panel_id))
        {
			$this->panel_room_charge->increase_panel_price($panel_id,$type,$vals);
			echo 1;
			return false;
        }
    }
    public function print_panel_room($panel_id='')
    {
        $data['room_list'] ='';
        $data['panel_id'] =$panel_id;
        /*if(!empty($panel_id))
        {
            $data['room_list'] = $this->panel_room_charge->get_room_list($panel_id);   
        }*/
        
        /////////////

        $data['room_charge_type_list'] = $this->general_model->room_charge_type_list();
        $data['room_list'] = $this->panel_room_charge->get_room_list($panel_id);
        $this->load->view('ipd_panel_room_charge/print_html',$data);
       }

    
    public function save_price_list()
  {
    $post = $this->input->post();
   if(isset($post))
    {  
      $msg='';
      if(empty($post['panel']))
      {
           $msg=1;
      }
      else
      {
        $this->panel_room_charge->save_panel_all_rate();
        $msg=2;
      }
       
    }
      echo $msg;
  }

    



}
?>