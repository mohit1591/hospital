<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opd_panel_particular_charge extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('opd_panel_particular_charge/opd_panel_particular_charge_model','panel_particular_charge');
        $this->load->library('form_validation');
    }

    public function index()
    { 
       // echo "hi";die();
     //   unauthorise_permission('118','298');
        $data['page_title'] = 'OPD panel particular charge List'; 
        $data['panel_list'] = $this->panel_particular_charge->get_panel_list();
        $this->load->view('opd_panel_particular_charge/add',$data);
    }

    public function ajax_list_pannel_particular()
    {
        //unauthorise_permission('118','298');
        $post = $this->input->post();
        $table ='';
        $particular_list = $this->panel_particular_charge->get_particular_list($post['panel']);
        //echo "<pre>"; print_r($particularlist); exit;
        if((isset($particular_list) && !empty($particular_list)) && !empty($post['panel']))
        {
            foreach($particular_list as $particularlist)
            { 
              $table.='<tr class="append_row">';
                $table.='<td>'.$particularlist['particular'].'</td>';
                $table.='<td><input type="text" id="charge" name="charge['.$particularlist['id'].']" value="'.$particularlist['particular_charge'].'" onchange="add_panel_particular_charge(this.value,'.$particularlist['id'].','.$particularlist['charge_id'].')"></td>';
                $table.='</tr>';
            }
        }
        else
        {
             $table='<tr class="append_row"><td class="text-danger" colspan="15"><div class="text-center">No record found</div></td></tr>';
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
        $this->load->view('opd_panel_particular_charge/print_html',$data);
        
    }



    



}
?>