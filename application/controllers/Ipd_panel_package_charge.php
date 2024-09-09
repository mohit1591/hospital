<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_panel_package_charge extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_panel_package_charge/ipd_panel_package_charge_model','package_panel_charge');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('120','731');
        $data['page_title'] = 'IPD panel package charge List'; 
        $data['panel_list'] = $this->package_panel_charge->get_panel_list();
        $this->load->view('ipd_panel_package_charge/add',$data);
    }

    public function ajax_list_pannel_package()
    {
        unauthorise_permission('120','731');
        $post = $this->input->post();
        $table ='';
        $package_list = $this->package_panel_charge->get_package_list($post['panel']);
        if((isset($package_list) && !empty($package_list)) && !empty($post['panel']))
        {
            $i=1;
            foreach($package_list as $packagelist)
            { 
              $table.='<tr class="append_row">';
                $table.='<td colspan="5" style="text-align:left;">'.$packagelist['name'].'</td>';
                $table.='<td colspan="5"><input type="text" id="price_'.$i.'"  name="test_id['.$i.'][path_price]" name="charge['.$packagelist['charge_id'].']" value="'.$packagelist['package_charge'].'" >
                
                
                <input type="hidden" name="test_id['.$i.'][test_id]"  id=""  value="'.$packagelist['id'].'"/>
                
                
                </td>';
                
                
                $btn_save='<button type="button" class="btn-custom"   
                 data-testid="'.$packagelist['id'].'" data-price="price_'.$i.'" data-docid="" id="save_test" onclick="save_panel_rate(this, '.$post['panel'].');">Save</button></a>';
                
                $table.='<td>'.$btn_save.'</td>';
                
                $table.='</tr>';
                //onchange="add_panel_package_charge(this.value,'.$packagelist['id'].','.$packagelist['charge_id'].')"
            
                $i++;
            }
        }
        else
        {
             $table='<tr class="append_row"><td class="text-danger" colspan="15"><div class="text-center">No record found</div></td></tr>';
        }
               $output=array('data'=>$table);
               echo json_encode($output);
    }

   
    public function set_panel_price($panel_id="",$package_id="", $vals="0",$charge_id='')
    {
        if(!empty($panel_id) && !empty($package_id))
        {
            $this->package_panel_charge->save_package_panel_charge($panel_id,$package_id,$vals,$charge_id);
            echo 1;
            return false;
        }
    }

    

    public function increase_panel_price($panel_id="",$type="", $vals="0")
    {
        if(!empty($panel_id))
        {
            $this->package_panel_charge->increase_package_panel_charge($panel_id,$type,$vals);
            echo 1;
            return false;
        }
    }
    public function print_panel_package($panel_id='')
    {
        $data['package_list'] ='';
        if(!empty($panel_id))
        {
            $data['package_list'] = $this->package_panel_charge->get_package_list($panel_id);   
        }
        $this->load->view('ipd_panel_package_charge/print_html',$data);
        
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
        $this->package_panel_charge->save_panel_all_rate();
        $msg=2;
      }
       
    }
      echo $msg;
  } 
  
   public function save_panel_rate()
     {

          $post = $this->input->post();
          //echo "<pre>"; print_r($post); exit;
          $data['page_title'] = "Panel Price";
          $msg='';
          if(isset($post) && !empty($post))
          { 
            if(empty($post['panel_id']))
            {
                $msg=1;
            }
            else
            {
              $this->package_panel_charge->save_panel_rate();
              $msg=2;
            }
          	
          }
         echo $msg;

     }


}
?>