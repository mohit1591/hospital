<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opd_charge_entry extends CI_Controller {
 
	function __construct() 
	{
  	parent::__construct();	
  	auth_users();  
  	$this->load->model('opd_charge_entry/opd_charge_entry_model','charge_entry');
  	$this->load->library('form_validation');
  }

    
	public function index()
    {
        unauthorise_permission(128,777);
        $data['page_title'] = 'Charge Entry'; 
        $this->load->view('opd_charge_entry/list',$data);
        
    }

    public function add($opd_id="",$patient_id="")
    {
           
        unauthorise_permission(128,777);
        $post = $this->input->post();
        if(!isset($post) || empty($post))
        {
          $this->session->unset_userdata('opd_particular_charge_billing');  
        }

 
        
        $data['page_title'] = 'Day Care Charge Entry';
        $data['particulars_charges']='';
        $data['data_id']='';
        $data['patient_id']=$patient_id;
        $data['opd_id']=$opd_id;
        $this->load->model('general/general_model');
        $data['patient_details']= $this->general_model->get_patient_according_to_day_care($opd_id,$patient_id); 
        $data['particulars_list'] = $this->general_model->particulars_list();
        $data['perticuller_list']= $this->general_model->get_particular_details_opd($opd_id,$patient_id);

       if((!isset($post) || empty($post)) && count($data['perticuller_list'])>=1)
       {
          
            $opd_particular_charge_billing = $this->session->userdata('opd_particular_charge_billing');
            //echo '<pre>'; print_r($opd_particular_charge_billing); exit;
            if(isset($opd_particular_charge_billing) && !empty($opd_particular_charge_billing))
            {
              $opd_particular_charge_billing = $opd_particular_charge_billing; 
            }
            else
            {
              $opd_particular_charge_billing = [];
            }
            $i=1;
            foreach($data['perticuller_list'] as $particulars_data)
            {
              //$particulars_data['charge_id']
                $opd_particular_charge_billing[] = array('charge_id'=>$i,'particular'=>$particulars_data['particular'],'s_date'=>date('d-m-Y',strtotime($particulars_data['s_date'])), 'quantity'=>$particulars_data['quantity'], 'amount'=>$particulars_data['amount'],'particulars'=>$particulars_data['particulars'],'charges'=>$particulars_data['charges']);
                $amount_arr = array_column($opd_particular_charge_billing, 'amount'); 
                $total_amount = array_sum($amount_arr);
                $this->session->set_userdata('opd_particular_charge_billing', $opd_particular_charge_billing);

            $i++;
            }


            $total = $total_amount;
          
            if($total==0)
            {
              $totamount = '0.00';
            }
            else
            {
              $totamount = number_format($total,2,'.','');
            }

             $opd_particular_payment_array = array('total_amount'=>$totamount,'particulars_charges'=>number_format($total_amount,2,'.',''));
            $this->session->set_userdata('opd_particular_payment', $opd_particular_payment_array);
       }
      
        $data['simulation_list'] = $this->general_model->simulation_list();

//echo "<pre>";print_r($post); exit;

        if(isset($post) && !empty($post))
        {   
            
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
              
                $charge_id = $this->charge_entry->save($opd_id,$patient_id);
                $this->session->set_flashdata('success','OPD charge added successfully.');
                redirect(base_url('opd_charge_entry/add/'.$post['opd_id'].'/'.$patient_id));
            }
            else
            {
                $data['form_error'] = validation_errors(); 
            }
                
        }
        $this->load->view('opd_charge_entry/add',$data);
    }



    public function particular_payment_calculation()
    {
          $post = $this->input->post();



          if(isset($post) && !empty($post))
          {   
            $opd_particular_charge_billing = $this->session->userdata('opd_particular_charge_billing');

            if(isset($opd_particular_charge_billing) && !empty($opd_particular_charge_billing))
            {
              $opd_particular_charge_billing = $opd_particular_charge_billing; 
            }
            else
            {
              $opd_particular_charge_billing = [];
            }

            $p = count($opd_particular_charge_billing)+1; 

          $opd_particular_charge_billing[] = array('charge_id'=>$p,'particular'=>$post['particular'],'s_date'=>$post['s_date'], 'quantity'=>$post['quantity'], 'amount'=>$post['amount'],'particulars'=>$post['particulars'],'charges'=>$post['charges']);
          $amount_arr = array_column($opd_particular_charge_billing, 'amount'); 
          $total_amount = array_sum($amount_arr);
          
       //print_r($opd_particular_charge_billing); exit; // [charge_id] => 1

          $this->session->set_userdata('opd_particular_charge_billing', $opd_particular_charge_billing);

       //print '<pre>';print_r($opd_particular_charge_billing);die;

          $html_data = $this->perticuller_list();
          $total = $total_amount;
          
          if($total==0)
          {
            $totamount = '0.00';
          }
          else
          {
            $totamount = number_format($total,2,'.','');
          }
          
          $response_data = array('html_data'=>$html_data, 'total_amount'=>$totamount,'particulars_charges'=>number_format($total_amount,2,'.',''));
          $opd_particular_payment_array = array('total_amount'=>$totamount,'particulars_charges'=>number_format($total_amount,2,'.',''));
          $this->session->set_userdata('opd_particular_payment', $opd_particular_payment_array);
          $json = json_encode($response_data,true);
          echo $json;
            
       }
    }

    private function _validate()
    {
        $users_data = $this->session->userdata('auth_users');  
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('simulation_id', 'simulation', 'trim|required'); 
        $this->form_validation->set_rules('patient_name', 'patient name', 'trim|required'); 
        $ipd_particular_billing_list = $this->session->userdata('opd_particular_charge_billing');
        if(!isset($ipd_particular_billing_list) && empty($ipd_particular_billing_list) && empty($post['data_id']))
        {
          $this->form_validation->set_rules('particular_id', 'particular_id', 'trim|callback_check_ipd_particular_id');
        }

        if ($this->form_validation->run() == FALSE) 
        {  
            
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'patient_id'=>$post['patient_id'], 
                                        'patient_code'=>$post['patient_code'],
                                        'simulation_id'=>$post['simulation_id'],
                                        'patient_name'=>$post['patient_name'],
                                        'mobile_no'=>$post['mobile_no'],
                                        
                                        'age_y'=>$post['age_y'],
                                        'age_m'=>$post['age_m'],
                                        'age_d'=>$post['age_d'],
                                        
                                       );

                                         
            return $data['form_data'];
        }   
    }

    public function check_opd_particular_id()
    {
       $opd_particular_billing_list = $this->session->userdata('opd_particular_charge_billing');
       if(isset($opd_particular_billing_list) && !empty($opd_particular_billing_list))
       {
          return true;
       }
       else
       {
          $this->form_validation->set_message('check_opd_particular_id', 'Please select a particular.');
          return false;
       }
    }

    public function opd_particular_calculation()
    {
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {
           $charges = $post['charges'];
           $quantity = $post['quantity'];
           $amount = ($charges*$quantity);
           $pay_arr = array('charges'=>$charges, 'amount'=>$amount,'quantity'=>$quantity);
           $json = json_encode($pay_arr,true);
           echo $json;
       }
    }

    

    private function perticuller_list()
    {
        $particular_data = $this->session->userdata('opd_particular_charge_billing');
         $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.booked_checkbox').prop('checked', false);
                                  } else {
                                      $('.booked_checkbox').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              
                              "; 
        $rows = '<thead class="bg-theme"><tr>           
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectAll" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                    <th>Particular</th>
                     <th>Date</th>
                    <th>Quantity</th>
                    <th>Charges</th>
                    <th>Amount</th>
                  </tr></thead>';  
           if(isset($particular_data) && !empty($particular_data))
           {
              
              $i = 1;
              foreach($particular_data as $particulardata)
              {
                //$particulardata['particular'] for delete ids
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="particular_id[]" class="part_checkbox booked_checkbox" value="'.$i.'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$particulardata['particulars'].'</td>
                            <td>'.$particulardata['s_date'].'</td>
                            <td>'.$particulardata['quantity'].'</td>
                            <td>'.$particulardata['charges'].'</td>
                            <td>'.$particulardata['amount'].'</td>
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Particular data not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }


    public function remove_opd_particular()
    {
       $post =  $this->input->post();
       
       if(isset($post['particular_id']) && !empty($post['particular_id']))
       {
           $opd_particular_charge_billing = $this->session->userdata('opd_particular_charge_billing');
           //echo "<pre>"; print_r($opd_particular_charge_billing); exit; 
           $opd_particular_payment = $this->session->userdata('opd_particular_payment'); 
           
           $particular_id_list = array_column($opd_particular_charge_billing, 'charge_id'); 
           //echo "<pre>";print_r($opd_particular_charge_billing); exit;
           foreach($opd_particular_charge_billing as $key=>$perticuller_ids)
           { 
             // echo "<pre>";print_r($perticuller_ids['charge_id']); 
              // change name for delete $perticuller_ids['charge_id']//  old particular
              if(in_array($perticuller_ids['charge_id'],$post['particular_id']))
              {  
                //echo "<pre>"; print_r($perticuller_ids); exit;
                 unset($opd_particular_charge_billing[$key]);
                 //echo $opd_particular_payment['particulars_charges'];die;
                 $this->session->unset_userdata('opd_particular_payment');
                
              }
           }  
     
       
        $amount_arr = array_column($opd_particular_charge_billing, 'amount'); 
        $total_amount = array_sum($amount_arr);
        $this->session->set_userdata('opd_particular_charge_billing',$opd_particular_charge_billing);
        $html_data = $this->perticuller_list();
        $particulars_charges = $total_amount;
        $bill_total = $total_amount;
        $response_data = array('html_data'=>$html_data,'particulars_charges'=>$particulars_charges,'total_amount'=>$bill_total);
        $json = json_encode($response_data,true);
        echo $json;
       }
    }



}
?>