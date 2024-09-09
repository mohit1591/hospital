<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ipd_panel_particular_charge extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_panel_particular_charge/ipd_panel_particular_charge_model','panel_particular_charge');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('118','727');
        $data['page_title'] = 'IPD panel particular charge List'; 
        $data['panel_list'] = $this->panel_particular_charge->get_panel_list();
        $this->load->view('ipd_panel_particular_charge/add',$data);
    }

    public function ajax_list_pannel_particular()
    {
        unauthorise_permission('118','727');
        $post = $this->input->post();
        $table ='';
        $particular_list = $this->panel_particular_charge->get_particular_list($post['panel'],$post['q']);
        //echo "<pre>"; print_r($particularlist); exit;
        if((isset($particular_list) && !empty($particular_list)) && !empty($post['panel']))
        {
            $i=1;
            foreach($particular_list as $particularlist)
            { 
              $table.='<tr class="append_row">';
                $table.='<td colspan="5" style="text-align:left;">'.$particularlist['particular'].'</td>';
                $table.='<td colspan="5"><input type="text" id="price_'.$i.'"  name="test_id['.$i.'][path_price]"  value="'.$particularlist['particular_charge'].'" >
                
                
                <input type="hidden" name="test_id['.$i.'][test_id]"  id=""  value="'.$particularlist['id'].'"/>
                  
                
                </td>';
                // name="charge['.$particularlist['id'].']"
                
                $btn_save='<button type="button" class="btn-custom"   
                 data-testid="'.$particularlist['id'].'" data-price="price_'.$i.'" data-docid="" id="save_test" onclick="save_panel_rate(this, '.$post['panel'].');">Save</button></a>';
                
                $table.='<td>'.$btn_save.'</td>';
                      
                $table.='</tr>';
                $i++;
            }
            
            //onchange="add_panel_particular_charge(this.value,'.$particularlist['id'].','.$particularlist['charge_id'].')"
        }
        else
        {
             $table='<tr class="append_row"><td class="text-danger" colspan="15"><div class="text-center">No record found</div></td></tr>';
        }
               $output=array('data'=>$table);
               echo json_encode($output);
    }
    
    public function save_panel_rate()
     {

          $post = $this->input->post();
        //   echo "<pre>"; print_r($post); exit;
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
              $this->panel_particular_charge->save_panel_rate();
              $msg=2;
            }
          	
          }
         echo $msg;

     }

    public function add()
    {
        $post = $this->input->post();
        if(isset($post) && !empty($post))
        {
            if(!empty($post['panel']))
            {
                $this->panel_particular_charge->save_panel_particular_charge();
                echo 1;
                return false;
            }
        }
        //print_r($post);
    }

     public function set_panel_price($panel_id="",$particular_id="",$vals="0",$charge_id='')
    {
        if(!empty($panel_id) && !empty($particular_id))
        {
            $this->panel_particular_charge->save_panel_particular_charge($panel_id,$particular_id,$vals,$charge_id);
            echo 1;
            return false;
        }
    }

    public function increase_panel_price($panel_id="",$type="", $vals="0")
    {
        
        if(!empty($panel_id))
        {
            $this->panel_particular_charge->increase_panel_price($panel_id,$type,$vals);
            echo 1;
            return false;
        }
    }

    public function print_panel_particular($panel_id='')
    {
        $data['particular_list'] ='';
        if(!empty($panel_id))
        {
            $data['particular_list'] = $this->panel_particular_charge->get_particular_list($panel_id);   
        }
        $this->load->view('ipd_panel_particular_charge/print_html',$data);
        
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
        $this->panel_particular_charge->save_panel_all_rate();
        $msg=2;
      }
       
    }
      echo $msg;
  } 

    



}
?>