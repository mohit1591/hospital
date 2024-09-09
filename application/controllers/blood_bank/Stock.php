<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Stock extends CI_Controller 
{
 
	function __construct() 
	{
        parent::__construct();	
        auth_users();  
        $this->load->model('blood_bank/stock/stock_model','stock_model');
        $this->load->model('blood_bank/recipient/Recipient_model','recipient');
        $this->load->library('form_validation');
  }


  public function index()
  {
    unauthorise_permission('266','1542');
    $this->session->unset_userdata('stock_search');
    $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    $data['blood_groups']=$this->blood_general_model->get_blood_group_list(); 
    $data['component_list']=$this->blood_general_model->get_component_list();
    $this->load->model('default_search_setting/default_search_setting_model'); 
    $default_search_data = $this->default_search_setting_model->get_default_setting();
        
        // End Defaul Search
         //$data['form_data'] = array('donor_id'=>'','bar_code'=>'','blood_group'=>'','component_id'=>'','start_date'=>$start_date, 'end_date'=>$end_date,'status'=>''); 

         if(isset($default_search_data[1]) && !empty($default_search_data) && $default_search_data[1]==1)
        {
            $start_date = '';
            $end_date = '';
        }
        else
        {
            $start_date = date('d-m-Y');
            $end_date = date('d-m-Y');
        }
        // End Defaul Search
        $data['form_data'] = array('donor_id'=>'','bar_code'=>'','blood_group'=>'','component_id'=>'','start_date'=>$start_date, 'end_date'=>$end_date,'status'=>'','stock_value'=>'1'); 
    $data['page_title'] = 'Stock List'; 
    $this->load->view('blood_bank/stock/list',$data);
  }


  public function ajax_list()
  {
    unauthorise_permission('266','1542');
     
    //print"<pre>"; 
    // print_r($data['blood_groups']); 
    $list = $this->stock_model->get_datatables();  
    //print"<pre>";print_r($list);die;
    $data = array();
    $no = $_POST['start'];
    $i = 1;
    $total_num = count($list);
    foreach ($list as $stock) 
    {
    
            //print"<pre>";print_r($stock);die;
            $exist_ids='';
            $stockdata='';
            $stock_data=$this->stock_model->get_stock_quantity($stock->bag_type_id,$stock->component_id,$exist_ids,$stock->donors_id,'',$stock->blood_grp_id);
            $stock_search = $this->session->userdata('stock_search');
            if($stock_search['stock_value']=='2')
            {
            if($stock_data['total_qty']>0)
            {
            //print_r($stock_data['total_qty']);
                $no++;
                $row = array();
                if($stock->donation_status==1)
                {
                  $crps='';
                    if($stock->cross_match=='1')
                    {
                      $crps = '<font color="red">Cross Matched</font>';
                    }
                    if($stock_data['total_qty']>0 && strtotime(date('d-m-Y H:i:s',strtotime($stock->expiry_date)))>strtotime(date('d-m-Y H:i:s')))
                    {
                        $status = '<font color="green">In-Stock</font>'.'<br>'.$crps;
                    }
                    else if($stock_data['total_qty']>0 && strtotime(date('d-m-Y H:i:s',strtotime($stock->expiry_date)))<strtotime(date('d-m-Y H:i:s')))
                    {
                        $status = '<font color="red">Expired</font>'.'<br>'.$crps;
                    }
                    else
                    {
                        $status = '<font color="green">Issued</font>';
                    }
                    
                }   
                else if($stock->donation_status==2){
                    $status = '<font color="red">Awaiting Results</font>';
                } 
                else if($stock->donation_status==3){
                  $status = '<font color="red">Unfit to Donate</font>';
                }
                else if($stock->donation_status==4)
                {
                  $status = '<font color="red">Discarded (Test Failed)</font>';
                  
                }
                else if($stock->donation_status==5)
                {
                  $status = '<font color="red">QC Failed</font>';
                  
                }
                else
                {
                  $status = '';
                }
                
                
                
                ////////// Check  List /////////////////medicine_preferred_reminder_service
                $check_script = "";
                if($i==$total_num)
                {
                  
                }                 
                ////////// Check list end ///////////// 
                $row[] = '<input type="checkbox" name="employee" class="checklist" value="'.$stock->id.'">'.$check_script;
                $row[] = $stock->donor_code;
                $row[] = $stock->bar_code;
                $row[] = $stock->blood;

                
                       
                $row[] = $stock->component_name;
                 $row[] = $stock->bag_type;
                $row[] = $stock->volumn;
                

                $row[] = date('d-m-Y',strtotime($stock->created_date));
                $row[] = date('d-m-Y',strtotime($stock->expiry_date));

                /*$stock_qc_done = get_qc_status($stock->id);
                if(!empty($stock->qc_reminder_date) && $stock->qc_reminder_date!='1970-01-01')
                {
                    $qc_remminder = date('d-m-Y',strtotime($stock->qc_reminder_date));
                }
                else
                {
                   $qc_remminder ='';
                }
                
                if(!empty($stock_qc_done))
                {
                  $qc = 'Done'.' '.$qc_remminder;
                }
                else
                {
                  $qc='Pending'.' '.$qc_remminder;
                }
                $row[] = $qc;*/
                $row[] = $status;
                if($stock_data['total_qty']>=0)
                {
                   

                   $row[] = $stock_data['total_qty'];
                }
                else
                {
                   $row[]='';
                }
                 $pig =$stock->pigtails;
                if(empty($stock->pigtails))
                {
                    $pig = 0;
                }
                
                $row[] = '<a  onclick="return change_quantity('.$stock->id.','.$stock->donor_id.');" title="Update Quantity"> '.$pig.' </a>';
                       
                   
                $users_data = $this->session->userdata('auth_users');
                $btnedit='';
                $btndelete='';
              if(in_array('1548',$users_data['permission']['action']))
              {
                    $btnview = ' <a onClick="return view_stock_details('.$stock->id.','.$stock->blood_grp_id.','.$stock->component_id.');" class="btn-custom" href="javascript:void(0)" style="'.$stock->id.'" title="History"><i class="fa fa-eye" aria-hidden="true"></i>History</a>';
              }

                $print_barcode_url = "'".base_url('blood_bank/stock/print_barcode/').$stock->bar_code."'";
                $btn_print_barcode = ' <a class="btn-custom" href="javascript:void(0)" onclick = "return print_window_page('.$print_barcode_url.');" title="Print Barcode" ><i class="fa fa-print"></i> Print Barcode </a>';

                //$btnedit = ' <a class="btn-custom" href="'.base_url("blood_bank/donor_examinations/add/".$stock->donors_id).'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';

                 //$btnqc = ' <a class="btn-custom" href="'.base_url("blood_bank/stock/performe_qc/".$stock->id."/".$stock->donors_id."/".$stock->blood_grp_id."/".$stock->component_id).'" title="QC"><i class="fa fa-pencil"></i> QC</a>';

            
              $row[] = $btn_print_barcode.$btnview.$btndelete;
                 
            
                $data[] = $row;
                $i++;
            }
            }
            else if($stock_search['stock_value']=='3')
            {
              if($stock_data['total_qty']=='0' && $stock->donation_status=='1')
              {  
            // print_r($stock_data['total_qty']);
                $no++;
                $row = array();
                if($stock->donation_status==1)
                {
                  $crps='';
                    if($stock->cross_match=='1')
                    {
                      $crps = '<font color="red">Cross Matched</font>';
                    }
                    if($stock_data['total_qty']>0 && strtotime(date('d-m-Y H:i:s',strtotime($stock->expiry_date)))>strtotime(date('d-m-Y H:i:s')))
                    {
                        $status = '<font color="green">In-Stock</font>'.'<br>'.$crps;
                    }
                      else if(strtotime(date('d-m-Y H:i:s',strtotime($stock->expiry_date)))<strtotime(date('d-m-Y H:i:s')))
                    {
                          $status = '<font color="red">Expired</font>';
                    }
                    else
                    {
                        $status = '<font color="green">Issued</font>';
                    }
                    
                }   
                else if($stock->donation_status==2){
                    $status = '<font color="red">Awaiting Results</font>';
                } 
                else if($stock->donation_status==3){
                  $status = '<font color="red">Unfit to Donate</font>';
                }
                else if($stock->donation_status==4)
                {
                  $status = '<font color="red">Discarded (Test Failed)</font>';
                  
                }
                else if($stock->donation_status==5)
                {
                  $status = '<font color="red">QC Failed</font>';
                  
                }
                else
                {
                  $status = '';
                }
                
                
                
                ////////// Check  List /////////////////medicine_preferred_reminder_service
                $check_script = "";
                if($i==$total_num)
                {
                  
                }                 
                ////////// Check list end ///////////// 
                $row[] = '<input type="checkbox" name="employee" class="checklist" value="'.$stock->id.'">'.$check_script;
                $row[] = $stock->donor_code;
                $row[] = $stock->bar_code;
                $row[] = $stock->blood;

               
                       
                $row[] = $stock->component_name;
                $row[] = $stock->bag_type;
                $row[] = $stock->volumn;
                

                $row[] = date('d-m-Y',strtotime($stock->created_date));
                $row[] = date('d-m-Y',strtotime($stock->expiry_date));

                /*$stock_qc_done = get_qc_status($stock->id);
                if(!empty($stock->qc_reminder_date) && $stock->qc_reminder_date!='1970-01-01')
                {
                    $qc_remminder = date('d-m-Y',strtotime($stock->qc_reminder_date));
                }
                else
                {
                   $qc_remminder ='';
                }
                
                if(!empty($stock_qc_done))
                {
                  $qc = 'Done'.' '.$qc_remminder;
                }
                else
                {
                  $qc='Pending'.' '.$qc_remminder;
                }
                $row[] = $qc;*/
                $row[] = $status;
                if($stock_data['total_qty']>=0)
                {
                   

                   $row[] = $stock_data['total_qty'];
                }
                else
                {
                   $row[]='';
                }
                
               
                //$row[]=$name;  
                $users_data = $this->session->userdata('auth_users');
                $btnedit='';
                $btndelete='';
              if(in_array('1548',$users_data['permission']['action']))
              {
                    $btnview = ' <a onClick="return view_stock_details('.$stock->id.','.$stock->blood_grp_id.','.$stock->component_id.');" class="btn-custom" href="javascript:void(0)" style="'.$stock->id.'" title="History"><i class="fa fa-eye" aria-hidden="true"></i>History</a>';
              }

                $print_barcode_url = "'".base_url('blood_bank/stock/print_barcode/').$stock->bar_code."'";
                $btn_print_barcode = ' <a class="btn-custom" href="javascript:void(0)" onclick = "return print_window_page('.$print_barcode_url.');" title="Print Barcode" ><i class="fa fa-print"></i> Print Barcode </a>';

                //$btnedit = ' <a class="btn-custom" href="'.base_url("blood_bank/donor_examinations/add/".$stock->donors_id).'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';

                 //$btnqc = ' <a class="btn-custom" href="'.base_url("blood_bank/stock/performe_qc/".$stock->id."/".$stock->donors_id."/".$stock->blood_grp_id."/".$stock->component_id).'" title="QC"><i class="fa fa-pencil"></i> QC</a>';

            
              $row[] = $btn_print_barcode.$btnview.$btndelete;
                 
            
                $data[] = $row;
                $i++;
                
            
            }
            }
            else
            {
                
                // print_r($stock_data['total_qty']);
                $no++;
                $row = array();
                if($stock->donation_status==1)
                {
                  $crps='';
                    if($stock->cross_match=='1')
                    {
                      $crps = '<font color="red">Cross Matched</font>';
                    }
                    if($stock_data['total_qty']>0 && strtotime(date('d-m-Y H:i:s',strtotime($stock->expiry_date)))>strtotime(date('d-m-Y H:i:s')))
                    {
                        $status = '<font color="green">In-Stock</font>'.'<br>'.$crps;
                    }
                    else if(strtotime(date('d-m-Y H:i:s',strtotime($stock->expiry_date)))<strtotime(date('d-m-Y H:i:s')))
                    {
                        //$stock_data['total_qty']>0 &&
                        $status = '<font color="red">Expired</font>';
                    }
                    else
                    {
                        $status = '<font color="green">Issued</font>';
                    }
                    
                }   
                else if($stock->donation_status==2){
                    $status = '<font color="red">Awaiting Results</font>';
                } 
                else if($stock->donation_status==3){
                  $status = '<font color="red">Unfit to Donate</font>';
                }
                else if($stock->donation_status==4)
                {
                  $status = '<font color="red">Discarded (Test Failed)</font>';
                  
                }
                else if($stock->donation_status==5)
                {
                  $status = '<font color="red">QC Failed</font>';
                  
                }
                else
                {
                  $status = '';
                }
                
                
                
                ////////// Check  List /////////////////medicine_preferred_reminder_service
                $check_script = "";
                if($i==$total_num)
                {
                  
                }                 
                ////////// Check list end ///////////// 
                $row[] = '<input type="checkbox" name="employee" class="checklist" value="'.$stock->id.'">'.$check_script;
                $row[] = $stock->donor_code;
                $row[] = $stock->bar_code;
                $row[] = $stock->blood;
                
                $row[] = $stock->component_name;
                $row[] = $stock->bag_type;
                $row[] = $stock->volumn;
                

                $row[] = date('d-m-Y',strtotime($stock->created_date));
                $row[] = date('d-m-Y',strtotime($stock->expiry_date));

                /*$stock_qc_done = get_qc_status($stock->id);
                if(!empty($stock->qc_reminder_date) && $stock->qc_reminder_date!='1970-01-01')
                {
                    $qc_remminder = date('d-m-Y',strtotime($stock->qc_reminder_date));
                }
                else
                {
                   $qc_remminder ='';
                }
                
                if(!empty($stock_qc_done))
                {
                  $qc = 'Done'.' '.$qc_remminder;
                }
                else
                {
                  $qc='Pending'.' '.$qc_remminder;
                }
                $row[] = $qc;*/
                $row[] = $status;
                if($stock_data['total_qty']>=0)
                {
                   

                   $row[] = $stock_data['total_qty'];
                }
                else
                {
                   $row[]='';
                }
                
                $users_data = $this->session->userdata('auth_users');
                $btnedit='';
                $btndelete='';
              if(in_array('1548',$users_data['permission']['action']))
              {
                    $btnview = ' <a onClick="return view_stock_details('.$stock->id.','.$stock->blood_grp_id.','.$stock->component_id.');" class="btn-custom" href="javascript:void(0)" style="'.$stock->id.'" title="History"><i class="fa fa-eye" aria-hidden="true"></i>History</a>';
              }

                $print_barcode_url = "'".base_url('blood_bank/stock/print_barcode/').$stock->bar_code."'";
                $btn_print_barcode = ' <a class="btn-custom" href="javascript:void(0)" onclick = "return print_window_page('.$print_barcode_url.');" title="Print Barcode" ><i class="fa fa-print"></i> Print Barcode </a>';

                /*$btnedit = ' <a class="btn-custom" href="'.base_url("blood_bank/donor_examinations/add/".$stock->donors_id).'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';

                 $btnqc = ' <a class="btn-custom" href="'.base_url("blood_bank/stock/performe_qc/".$stock->id."/".$stock->donors_id."/".$stock->blood_grp_id."/".$stock->component_id).'" title="QC"><i class="fa fa-pencil"></i> QC</a>';*/

            
              //$row[] = $btnqc.$btn_print_barcode.$btnedit.$btnview.$btndelete;

              $row[] = $btn_print_barcode.$btnview.$btndelete;
                 
            
                $data[] = $row;
                $i++;
        
    }
    
    
    


    /*

    
    $exist_ids='';
    $stockdata='';
    $stock_data=$this->stock_model->get_stock_quantity($stock->bag_type_id,$stock->component_id,$exist_ids,'','',$stock->blood_grp_id);
    
        $no++;
        $row = array();
        if($stock->status==1)
        {
            $status = '<font color="green">Active</font>';
        }   
        else{
            $status = '<font color="red">Inactive</font>';
        } 
        
        
        $check_script = "";
        if($i==$total_num)
        {
          
        }                 
        
        $row[] = $i;
        $row[] = $stock->blood;
        $row[] = $stock->component_name;
        if($stock_data['total_qty']>=0)
        {
           $row[] = $stock_data['total_qty']; 
        }
        else
        {
           $row[]='';
        }
        
        
       
       
       
        $users_data = $this->session->userdata('auth_users');
        $btnedit='';
        $btndelete='';
      if(in_array('1548',$users_data['permission']['action']))
      {
            $btnview = ' <a onClick="return view_stock_details('.$stock->id.','.$stock->blood_grp_id.','.$stock->component_id.');" class="btn-custom" href="javascript:void(0)" style="'.$stock->id.'" title="History"><i class="fa fa-eye" aria-hidden="true"></i>History</a>';
      }
    
      $row[] = $btnedit.$btnview.$btndelete;
         
    
        $data[] = $row;
        $i++;
    */}

    $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->stock_model->count_all(),
                    "recordsFiltered" => $this->stock_model->count_filtered(),
                    "data" => $data,
            );
    //output to json format
    echo json_encode($output);
  }

 

    public function view($id="",$blood_grp_id="",$component_id="")
    { 
        $data['page_title'] = "Stock List";
        $data['stock_list'] = $this->stock_model->stock_view_list($id);
    //print '<pre>';print_r( $data['stock_list']);
        //$data['stock_list_detail'] = $this->stock_model->stock_view_detail_list($id,$blood_grp_id,$component_id);  
        $data['blood_grp_id']=$blood_grp_id;
        $data['component_id']=$component_id;
        $data['stock_id']=$id;
        $this->load->view('blood_bank/stock/history_view',$data); 
    }
    public function view_start($id="",$blood_grp_id="",$component_id="")
    {  
        
            $data['page_title'] = "Stock List";
            
            $data['stock_list'] = $this->stock_model->stock_view_list($id);
            $data['stock_list_detail'] = $this->stock_model->get_datatables_stock_history($id,$blood_grp_id,$component_id);
            //print_r($data['stock_list_detail']);die;

            //print_r($data['stock_list']);
            $post = $this->input->post();    

            $list =  $data['stock_list_detail'];
            $total_count = count($list);

            //;  
            //print"<pre>";print_r($list);die;
            $data = array();
            $no = $_POST['start'];
            $i = 1;
            $total_num = count($list);
            foreach ($list as $stock) {

           
            $exist_ids='';
            $stockdata='';
            //$stock_data=$this->recipient->get_stock_quantity($stock->bag_type_id,$stock->component_id,$exist_ids,'',$stock->bar_code);
            
            if($stock->blood_group_id!='')
            {
                $blood_grp_id=$stock->blood_group_id;
            }
            else
            {
                $blood_grp_id=$blood_grp_id;
            }
             
            $stock_data=$this->stock_model->get_stock_quantity_new($stock->bag_type_id,$stock->component_id,$exist_ids,'',$stock->bar_code,$blood_grp_id);


             $qty=$stock_data['total_qty'];
             //echo $qty;
                          
           //print_r($stock_data['total_qty']);
            $no++;
            $row = array();
          

            if($stock->status==1)
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

            }  
            $status_flag='';  
                 
            ////////// Check list end ///////////// 
            //$row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$stock->id.'">'.$check_script;
            
               
            if($stock->is_issued!=1)
            {
            
            if($qty>0 || $stock->flag==2)
            {
            if($stock->flag==2)
            {
               $status_flag='Issued'; 
               $qty=$stock->credit;
            }
            else if($stock->flag==1)
            {
                //echo $qty;
               
                $check_beg_qcy= $this->recipient->check_beg_qc($stock->donor_id);
                //print_r($check_beg_qcy);
                if(strtotime(date('d-m-Y H:i:s',strtotime($stock->expiry_date)))<strtotime(date('d-m-Y H:i:s')) && $check_beg_qcy[0]->blood_condition!=2)
                {
                     $status_flag="Expired";
                }
               
                else if(count($check_beg_qcy)>0)
                {
                   
                   if($check_beg_qcy[0]->blood_condition==2)
                   {
                        $status_flag="Discard"; 
                   }
                   else
                   {
                       $status_flag="Tested"; 
                   }
                }
                else
                {
                    $status_flag="Untested";
                }
               
                
            }
            $row[] = $i;
            $row[] = $stock->donor_code;
            //$row[] = $stock->patient_code;
            $row[] = $stock->bar_code;
            $row[] = $stock->blood_group;
            $row[] = $stock->component_name;

           
            // if(strtotime(date('d-m-Y',strtotime($stock->expiry_date)))<strtotime(date('d-m-Y')))
            // {
            //      $status_flag="Expired";
            // }

           
            $row[] = $qty;

            $alert_days = get_setting_value('BLOODBANK_COMPONENT_EXPIRY_DAY');
            $expire_timestamp = strtotime($stock->expiry_date)-(86400*$alert_days);
            $expire_alert_days = date('Y-m-d',$expire_timestamp);
            //echo $expire_alert_days;
            $current_date = date('Y-m-d');
            //echo $expire_alert_days;
            $expire_date = date('d-m-Y H:i:s',strtotime($stock->expiry_date));
            //strtotime($medicine_sess[$medicine->id.'.'.$medicine->batch_no]["manuf_date"]) < 31536000
            
            if($stock->expiry_date!='0000-00-00' && $stock->expiry_date!='00-00-0000' && $expire_date!='01-01-1970' && $expire_date!='00-00-0000' && $expire_date!='0000-00-00')
            {
              if($current_date>=$expire_alert_days)
              {
                $expire_date = '<div class="m_alert_red">'.$expire_date.'</div>';  
              }
              else
              {
                $expire_date = $expire_date; 
              }
              
            }
            else
            {
              $expire_date = '<div class="m_alert_red"></div>'; 
            }


            $row[] = $expire_date;
            $row[]=$status_flag;
            $row[]=date('d-m-Y H:i:s',strtotime($stock->created_date));;


            //$row[]=$name;  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            if(in_array('1548',$users_data['permission']['action']))
            {
            $btnview = ' <a onClick="return view_stock_details('.$stock->id.');" class="btn-custom" href="javascript:void(0)" style="'.$stock->id.'" title="view"><i class="fa fa-eye" aria-hidden="true"></i>view</a>';
            }

            $row[] = $btnedit.$btnview.$btndelete;


            $data[] = $row;
            $i++;
            
            }
            }
            }

           /* $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" =>$total_count, //$this->stock_model->count_all_stock_history($id,$blood_grp_id,$component_id),
            "recordsFiltered" => $this->stock_model->count_filtered_stock_history($id,$blood_grp_id,$component_id),
            "data" => $data,
            );*/
            
            $recordsTotal = $this->stock_model->count_all_stock_history($id,$blood_grp_id,$component_id);
           /* echo $this->db->last_query(); 
            echo $recordsTotal; die;*/
            $recordsFiltered = $recordsTotal;
            
            $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $recordsTotal,
                        "recordsFiltered" =>$recordsFiltered,
                        "data" => $data,
                );
            
            
            //output to json format
            echo json_encode($output);            
        //$this->load->view('blood_bank/stock/history_view',$data);     
      
    }


 public function advance_search()
    {
        $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();
       // print_r($post);
       // $data['data']=get_setting_value('PATIENT_REG_NO');
        //$data['simulation_list'] = $this->general_model->simulation_list();
        $data['blood_groups']=$this->blood_general_model->get_blood_group_list();
        $data['component_list']=$this->blood_general_model->get_component_list(); 

        $data['form_data'] = array(
                                    "start_date"=>"",
                                    "end_date"=>"",
                                    'component_id'=>'',
                                    'donor_id'=>'',
                                    'blood_group'=>'',
                                    'bar_code'=>'',
                                    'status'=>'1'                                  
                          
                                     );
        if(isset($post) && !empty($post))
        {
            $merge= array_merge($data['form_data'],$post); 
            $this->session->set_userdata('stock_search', $merge);
        }
        $stock_search = $this->session->userdata('stock_search');
        if(isset($stock_search) && !empty($stock_search))
        {
            $data['form_data'] = $stock_search;
        }
        $this->load->view('blood_bank/stock/advance_search',$data);
    }


   public function reset_search()
    {
        $this->session->unset_userdata('stock_search');
    }

    public function stock_dashboard()
    {
        $data['page_title']='Stock Dashboard';
        $search_data= $this->session->userdata('stock_dashboard_search');
        $start_date = date('d-m-Y');
        $end_date = date('d-m-Y');
        if(isset($search_data))
        {
            
            if(empty($search_data['component']) && empty($search_data['blood_group']))
            {
                $data['all_blood_group_list']=$this->stock_model->get_blood_group_list();
                $data['all_component_list']=$this->stock_model->get_component_list();
            }
            if(empty($search_data['component']) && !empty($search_data['blood_group']))
            {
                $component = $search_data['component'];
                $blood_grp_id = $search_data['blood_group'];
                $data['all_component_list']=$this->stock_model->get_component_list();
                
                $data['blood_group_name'] = $this->stock_model->get_blood_group_name($blood_grp_id);
            }
            else if(!empty($search_data['component']) && empty($search_data['blood_group']))
            {
                $data['all_blood_group_list']=$this->stock_model->get_blood_group_list();
                $component = $search_data['component'];
                $blood_grp_id = $search_data['blood_group'];
                $data['components_name'] = $this->stock_model->get_components_name($component);
                
                
            }
            else if(!empty($search_data['component']) && !empty($search_data['blood_group']))
            {
                $data['all_blood_group_list']=$this->stock_model->get_blood_group_list();
                $component = $search_data['component'];
                $blood_grp_id = $search_data['blood_group'];
                $data['components_name'] = $this->stock_model->get_components_name($component);
                $data['blood_group_name'] = $this->stock_model->get_blood_group_name($blood_grp_id);
                
                
            }
            else
            {
                $data['all_data'] = $this->stock_model->all_data_list();  
                $component = $search_data['component'];
                $blood_grp_id = $search_data['blood_group'];
            }
            
        }
        else
        {
            $start_date = '';
            $end_date = '';
            $blood_grp_id = '';  
            //echo "ss"; die;
            $data['all_blood_group_list']=$this->stock_model->get_blood_group_list();
            $data['all_component_list']=$this->stock_model->get_component_list();
            
           
        
        }
        $blood_grp_id = $search_data['blood_group'];
        $this->load->model('blood_bank/general/blood_bank_general_model','blood_general');
        $blood_group_data= $this->blood_general->get_blood_group_by_id($search_data['blood_group']);
         if(count($blood_group_data)>0 && $blood_group_data!='empty')
         {
            $data['blood_group']=$blood_group_data[0]->blood_group;
         }
         else
         {
            $data['blood_group']='';
         }
         
         $data['form_data']=array('component'=>$component,'blood_group'=>$data['blood_group'],'blood_grp_id'=>$search_data['blood_group']);
         $data['blood_groups']=$this->blood_general->get_blood_group_list();
         $data['component_list']=$this->blood_general->get_component_list();
         $this->load->view('blood_bank/stock/stock_dashboard',$data);
    }

    public function stock_dashboard_old()
    {
        $data['page_title']='Stock Dashboard';
        $search_data= $this->session->userdata('stock_dashboard_search');
        $start_date = date('d-m-Y');
        $end_date = date('d-m-Y');
        if(isset($search_data))
        {
        $data['all_data'] = $this->stock_model->all_data_list();  
        $start_date = $search_data['start_date'];
        $end_date = $search_data['end_date'];
        $blood_grp_id = $search_data['blood_group'];
        }
        else
        {
        $start_date = '';
        $end_date = '';
        $blood_grp_id = '';  
        }
        
        //print '<pre>';print_r($data['all_data']);die;
        

        $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
         $blood_group_data= $this->blood_general_model->get_blood_group_by_id($search_data['blood_group']);
         if(count($blood_group_data)>0 && $blood_group_data!='empty')
         {
            $data['blood_group']=$blood_group_data[0]->blood_group;
         }
         else
         {
            $data['blood_group']='';
         }
        
         
         $data['form_data']=array('start_date'=>$start_date,'end_date'=>$end_date,'blood_group'=>$data['blood_group'],'blood_grp_id'=>$blood_grp_id);
         $data['blood_groups']=$this->blood_general_model->get_blood_group_list();
       $this->load->view('blood_bank/stock/stock_dashboard',$data);
    }

    public function dash_board_all_data()
    {
         $data['all_data'] = $this->stock_model->all_data_list();
         print_r($data['all_data']);die;
         
    }

    public function reset_search_dashboard()
    {

        $this->session->unset_userdata('stock_dashboard_search');
    }

    public function advance_search_dashboard()
    {

        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();

      
        $data['form_data'] = array(
                                    "start_date"=>"",
                                    "end_date"=>"",
                                    'blood_group'=>"",

            );
        if(isset($post) && !empty($post))
        {
//print_r($post);die;
            $marge_post = array_merge($data['form_data'],$post);
            $this->session->set_userdata('stock_dashboard_search', $marge_post);
        }
        $stock_dashboard_search = $this->session->userdata('stock_dashboard_search');
        if(isset($stock_dashboard_search) && !empty($stock_dashboard_search))
        {
            $data['form_data'] = $stock_dashboard_search;
        }
        //$this->load->view('medicine_entry/advance_search',$data);
    }

     public function print_barcode($bar_code)
    {
      
      $users_data = $this->session->userdata('auth_users'); 
      if(!empty($bar_code))
      {
        $data['barcode_text'] = $bar_code;
        $this->load->view('blood_bank/stock/print_barcode_template',$data);
      }
    }
    
    public function stock_excel()
    {
       // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          
          // Field names in the first row
          $fields = array('Donor ID','Barcode','Blood Group','Component','Bag Type','Volumne','Created Date','Expiry Date','Status','Qty');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;
          foreach ($fields as $field)
          {
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
              $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
              
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
          $list = $this->stock_model->get_datatables();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach ($list as $stock) 
               {
                   
                   
    
            //print"<pre>";print_r($stock);die;
            $exist_ids='';
            $stockdata='';
            $stock_data=$this->stock_model->get_stock_quantity($stock->bag_type_id,$stock->component_id,$exist_ids,$stock->donors_id,'',$stock->blood_grp_id);
            $stock_search = $this->session->userdata('stock_search');
            if($stock_search['stock_value']=='2')
            {
            if($stock_data['total_qty']>0)
            {
           
                if($stock->donation_status==1)
                {
                  $crps='';
                    if($stock->cross_match=='1')
                    {
                      $crps = 'Cross Matched';
                    }
                    if($stock_data['total_qty']>0 && strtotime(date('d-m-Y H:i:s',strtotime($stock->expiry_date)))>strtotime(date('d-m-Y H:i:s')))
                    {
                        $status = 'In-Stock';
                    }
                    else if($stock_data['total_qty']>0 && strtotime(date('d-m-Y H:i:s',strtotime($stock->expiry_date)))<strtotime(date('d-m-Y H:i:s')))
                    {
                        $status = 'Expired';
                    }
                    else
                    {
                        $status = 'Issued';
                    }
                    
                }   
                else if($stock->donation_status==2){
                    $status = 'Awaiting Results';
                } 
                else if($stock->donation_status==3){
                  $status = 'Unfit to Donate';
                }
                else if($stock->donation_status==4)
                {
                  $status = 'Discarded (Test Failed)';
                  
                }
                else if($stock->donation_status==5)
                {
                  $status = 'QC Failed>';
                  
                }
                else
                {
                  $status = '';
                }
                $donor_code = $stock->donor_code;
                $bar_code = $stock->bar_code;
                $blood = $stock->blood;
                $component_name = $stock->component_name;
                $volumn = $stock->volumn;
                $created_date = date('d-m-Y',strtotime($stock->created_date));
                $expiry_date = date('d-m-Y',strtotime($stock->expiry_date));
                
                if($stock_data['total_qty']>=0)
                {
                   $total_qty = $stock_data['total_qty'];
                }
                else
                {
                   $total_qty='';
                }
                 $pig =$stock->pigtails;
                if(empty($stock->pigtails))
                {
                    $pig = 0;
                }
                
                 array_push($rowData,$donor_code,$bar_code,$blood,$component_name,$stock->bag_type,$volumn,$created_date,$expiry_date,$status,$total_qty);
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    $i++;    
                
               
               
               
            }
            }
            else if($stock_search['stock_value']=='3')
            {
              if($stock_data['total_qty']=='0' && $stock->donation_status=='1')
              {  
            
                if($stock->donation_status==1)
                {
                  $crps='';
                    if($stock->cross_match=='1')
                    {
                      $crps = 'Cross Matched';
                    }
                    if($stock_data['total_qty']>0 && strtotime(date('d-m-Y H:i:s',strtotime($stock->expiry_date)))>strtotime(date('d-m-Y H:i:s')))
                    {
                        $status = 'In-Stock';
                    }
                      else if(strtotime(date('d-m-Y H:i:s',strtotime($stock->expiry_date)))<strtotime(date('d-m-Y H:i:s')))
                    {
                          $status = 'Expired';
                    }
                    else
                    {
                        $status = 'Issued';
                    }
                    
                }   
                else if($stock->donation_status==2){
                    $status = 'Awaiting Results';
                } 
                else if($stock->donation_status==3){
                  $status = 'Unfit to Donate';
                }
                else if($stock->donation_status==4)
                {
                  $status = 'Discarded (Test Failed)';
                  
                }
                else if($stock->donation_status==5)
                {
                  $status = 'QC Failed';
                  
                }
                else
                {
                  $status = '';
                }
                
                $donor_code = $stock->donor_code;
                $bar_code = $stock->bar_code;
                $blood = $stock->blood;
                $component_name = $stock->component_name;
                $volumn = $stock->volumn;
                $created_date = date('d-m-Y',strtotime($stock->created_date));
                $expiry_date = date('d-m-Y',strtotime($stock->expiry_date));
                
                if($stock_data['total_qty']>=0)
                {
                   $total_qty = $stock_data['total_qty'];
                }
                else
                {
                   $total_qty='';
                }
                
                 array_push($rowData,$donor_code,$bar_code,$blood,$component_name,$stock->bag_type,$volumn,$created_date,$expiry_date,$status,$total_qty);
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    $i++;    
             
                
            
            }
            }
            else
            {
                
                $no++;
                if($stock->donation_status==1)
                {
                  $crps='';
                    if($stock->cross_match=='1')
                    {
                      $crps = 'Cross Matched';
                    }
                    if($stock_data['total_qty']>0 && strtotime(date('d-m-Y H:i:s',strtotime($stock->expiry_date)))>strtotime(date('d-m-Y H:i:s')))
                    {
                        $status = 'In-Stock';
                    }
                    else if($stock_data['total_qty']>0 && strtotime(date('d-m-Y H:i:s',strtotime($stock->expiry_date)))<strtotime(date('d-m-Y H:i:s')))
                    {
                        $status = 'Expired';
                    }
                    else
                    {
                        $status = 'Issued';
                    }
                    
                }   
                else if($stock->donation_status==2){
                    $status = 'Awaiting Results';
                } 
                else if($stock->donation_status==3){
                  $status = 'Unfit to Donate';
                }
                else if($stock->donation_status==4)
                {
                  $status = 'Discarded (Test Failed)';
                  
                }
                else if($stock->donation_status==5)
                {
                  $status = 'QC Failed';
                  
                }
                else
                {
                  $status = '';
                }
                
                $donor_code = $stock->donor_code;
                $bar_code = $stock->bar_code;
                $blood = $stock->blood;
                $component_name = $stock->component_name;
                $volumn = $stock->volumn;
                $created_date = date('d-m-Y',strtotime($stock->created_date));
                $expiry_date = date('d-m-Y',strtotime($stock->expiry_date));
                
                if($stock_data['total_qty']>=0)
                {
                   $total_qty = $stock_data['total_qty'];
                }
                else
                {
                   $total_qty='';
                }
                
            
            
            array_push($rowData,$donor_code,$bar_code,$blood,$component_name,$stock->bag_type,$volumn,$created_date,$expiry_date,$status,$total_qty);
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    $i++;           
        
    }
    
    
    
  
                    
                    
               }
             
          }

          // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
               foreach($data as $boking_data)
               {
                    $col = 0;
                    $row_val=1;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $boking_data[$field]);

                         $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                          $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                         $col++;
                         $row_val++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          //header('Content-Type: application/octet-stream');
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=blood_stock_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }
    
    
    public function stock_dashboard_excel()
    {
        
        $search_data= $this->session->userdata('stock_dashboard_search');
        $start_date = date('d-m-Y');
        $end_date = date('d-m-Y');
        if(isset($search_data))
        {
            
            if(empty($search_data['component']) && empty($search_data['blood_group']))
            {
                $all_blood_group_list=$this->stock_model->get_blood_group_list();
                $all_component_list=$this->stock_model->get_component_list();
            }
            if(empty($search_data['component']) && !empty($search_data['blood_group']))
            {
                $component = $search_data['component'];
                $blood_grp_id = $search_data['blood_group'];
                $all_component_list=$this->stock_model->get_component_list();
                
                $blood_group_name = $this->stock_model->get_blood_group_name($blood_grp_id);
            }
            else if(!empty($search_data['component']) && empty($search_data['blood_group']))
            {
                $all_blood_group_list=$this->stock_model->get_blood_group_list();
                $component = $search_data['component'];
                $blood_grp_id = $search_data['blood_group'];
                $components_name = $this->stock_model->get_components_name($component);
                
                
            }
            else if(!empty($search_data['component']) && !empty($search_data['blood_group']))
            {
                $all_blood_group_list=$this->stock_model->get_blood_group_list();
                $component = $search_data['component'];
                $blood_grp_id = $search_data['blood_group'];
                $components_name = $this->stock_model->get_components_name($component);
                $blood_group_name = $this->stock_model->get_blood_group_name($blood_grp_id);
                
                
            }
            else
            {
                $all_data = $this->stock_model->all_data_list();  
                $component = $search_data['component'];
                $blood_grp_id = $search_data['blood_group'];
            }
            
        }
        else
        {
            $start_date = '';
            $end_date = '';
            $blood_grp_id = '';  
            $all_blood_group_list=$this->stock_model->get_blood_group_list();
            $all_component_list=$this->stock_model->get_component_list();
        }
        $blood_grp_id = $search_data['blood_group'];
        $this->load->model('blood_bank/general/blood_bank_general_model','blood_general');
        $blood_group_data= $this->blood_general->get_blood_group_by_id($search_data['blood_group']);
         $blood_group=$blood_group_data[0]->blood_group;
         $blood_groups=$this->blood_general->get_blood_group_list();
         $component_list=$this->blood_general->get_component_list();
         
         
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          
          // Field names in the first row
          
         
          $fields = array('Component Name','Blood Group','Total QTY','In-Stock','Awaiting Test Results','Alert Expiry soon (12 days)','Alert (7 days)','QC','Discarded (expired)','Discarded (QC failed)','Discarded (Test failed)');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;
          foreach ($fields as $field)
          {
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
              $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
              $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
              $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
              $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
              
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
          
        /*$search_data= $this->session->userdata('stock_dashboard_search');
        $all_data = $this->stock_model->all_data_list();  
        $component = $search_data['component'];
        $blood_grp_id = $search_data['blood_group'];*/
        $i=0;
        $rowData = array();
            $data= array();
        if(isset($all_data['all_blood_group']))
		{ 
		    
    		foreach($all_data['all_blood_group'] as $data_each)
    		{
    		  
    		     $total_aw=0;
			      
						$bag_type_id='';
						$exist_ids='';
						//$donated_data['component_id'][0]
						$get_stoc_qty= get_stock_dashboard_grand_total_quantity($bag_type_id="",$data_each['blood_component_id'],$exist_ids='',$donated_data['donor_id'][0],$data_each['blood_group_id']) ;
						if($get_stoc_qty['total_qty']>0){
						    if(!empty($get_stoc_qty['total_qty'])){ $total_aw =$total_aw+ $get_stoc_qty['total_qty']; } 
							$i++;}
							
					$total_ex =0;		
							 
									
							$bag_type_id='';
							$exist_ids=''; //$donated_data['component_id'][0]
							$get_stoc_qty= get_stock_dashboard_quantity($bag_type_id="",$data_each['blood_component_id'],$exist_ids='',$donated_data['donor_id'][0],$data_each['blood_group_id']) ;
							if($get_stoc_qty['total_qty']>0){
							 if(!empty($get_stoc_qty['total_qty'])){ $total_ex =$total_ex+$get_stoc_qty['total_qty']; }  $i++;}
							  
								$get_untested_qty= get_stock_quantity_awaiting_data('',$data_each['blood_component_id'],'','',$data_each['blood_group_id']) ;
								if(!empty($get_untested_qty['total_qty'])){ $untested_qty = $get_untested_qty['total_qty']; }else{  $untested_qty = '0'; }
								
						//alert 12 days
						$get_twelve_qty= get_stock_quantity_twelve_data('',$data_each['blood_component_id'],'','',$data_each['blood_group_id'],12) ;
						if(!empty($get_twelve_qty['total_qty'])){ $twelve_qty= $get_twelve_qty['total_qty']; }else{ $twelve_qty= '0'; } 
						//alert 7 days
						$get_seven_qty= get_stock_quantity_seven_data('',$data_each['blood_component_id'],'','',$data_each['blood_group_id'],12) ;
						
						if(!empty($get_seven_qty['total_qty'])){ $sevqty = $get_seven_qty['total_qty'];  }else { $sevqty = '0'; }
                            //Qc
                            $get_qc_qty= get_stock_quantity_tested_data('',$data_each['blood_component_id'],'','',$data_each['blood_group_id'],12) ;
                            
						//alert 12 days
						
						if($get_qc_qty['total_qty']>0){
						 if(!empty($get_qc_qty['total_qty'])){ $test_stk_qty = $get_qc_qty['total_qty'];  }
						}else { $test_stk_qty= '0';} 
									
						$get_stoc_qty_expred= get_stock_quantity_expired_by_group('',$data_each['blood_component_id'],'','',$data_each['blood_group_id']) ; 
						if(!empty($get_stoc_qty_expred['total_qty'])){ $exp_qty_stock = $get_stoc_qty_expred['total_qty'];}else {  $exp_qty_stock = '0'; }
						        
						 //expired 
						 $get_stoc_qty= get_stock_quantity_qc_failed_data('',$data_each['blood_component_id'],'','',$data_each['blood_group_id']) ;
						 if(!empty($get_stoc_qty['total_qty'])){ $qc_fail_qty= $get_stoc_qty['total_qty'];}else {  $qc_fail_qty= '0'; }
										
						$get_stoc_qty= get_stock_quantity_discarded_data('',$data_each['blood_component_id'],'','',$data_each['blood_group_id']) ;
						if(!empty($get_stoc_qty['total_qty'])){ $stock_dis_qty= $get_stoc_qty['total_qty']; }else { $stock_dis_qty= '0'; }
						
						
						

    		     
    		     
    		       array_push($rowData,$data_each['blood_component'],$data_each['blood_group'],$total_aw,$total_ex,$untested_qty,$twelve_qty,$sevqty,$test_stk_qty,$exp_qty_stock,$qc_fail_qty,$stock_dis_qty);
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    $i++;    
                
    		    
    		}
    		
		}
		else if(!empty($form_data['component']) && empty($form_data['blood_grp_id']))
		{
			    
			        foreach($all_blood_group_list as $blood_li)
			        { 
			           $component_ids = $form_data['component'];
			           
			           
			            
                  /* <div style="margin-bottom:5px;">
        				<a href="#!" class="btn_quick_search">< ?php echo $components_name;?></a>
        				<a href="#!" class="btn_border">< ?php echo $blood_li->blood_group;?></a>
        			</div> */
            
            		
						        $total_aw=0;
						        $bag_type_id='';
								$exist_ids='';
								$get_stoc_qty= get_stock_dashboard_grand_total_quantity($bag_type_id="",$component_ids,$exist_ids='','',$blood_li->id) ;
								if($get_stoc_qty['total_qty']>0){
								if(!empty($get_stoc_qty['total_qty'])){ $total_aw =$total_aw+ $get_stoc_qty['total_qty']; }  $i++;}
								         
						$total_ex =0;		
					 
							
							$bag_type_id='';
							$exist_ids=''; //$donated_data['component_id'][0]
							$get_stoc_qty= get_stock_dashboard_quantity($bag_type_id="",$component_ids,$exist_ids='','',$blood_li->id) ;
							if($get_stoc_qty['total_qty']>0){
							 if(!empty($get_stoc_qty['total_qty'])){ $total_ex =$total_ex+$get_stoc_qty['total_qty']; }  $i++;}
							
								//'',$data_each['blood_component_id'],'','',$data_each['blood_group_id']
								$get_untested_qty= get_stock_quantity_awaiting_data("",$component_ids,'','',$blood_li->id) ;
								if(!empty($get_untested_qty['total_qty'])){ $untested_qty= $get_untested_qty['total_qty']; }else{  $untested_qty= '0'; }
								
						//alert 12 days
						$get_twelve_qty= get_stock_quantity_twelve_data($bag_type_id="",$group_wise[0]['component_id'][0],$exist_ids='','',$blood_li->id,12) ;
						if(!empty($get_twelve_qty['total_qty'])){ $twelve_qty= $get_twelve_qty['total_qty']; }else{ $twelve_qty= '0'; } 
										
						//alert 7 days
						$get_seven_qty= get_stock_quantity_seven_data($bag_type_id="",$component_ids,$exist_ids='','',$blood_li->id,12) ;
							if(!empty($get_seven_qty['total_qty'])){ $sevqty = $get_seven_qty['total_qty'];  }else { $sevqty= '0'; } 
                    //Qc
                    $get_qc_qty= get_stock_quantity_tested_data($bag_type_id="",$component_ids,$exist_ids='','',$blood_li->id,12) ;
            
						if($get_qc_qty['total_qty']>0){
							 if(!empty($get_qc_qty['total_qty'])){ $test_stk_qty= $get_qc_qty['total_qty'];  }
							}else { $test_stk_qty= '0';} 
						$get_stoc_qty_expred= get_stock_quantity_expired_by_group($bag_type_id="",$component_ids,$exist_ids='','',$blood_grp_ids) ; 
						if(!empty($get_stoc_qty_expred['total_qty'])){ $exp_qty_stock= $get_stoc_qty_expred['total_qty'];}else {  $exp_qty_stock= '0'; }
						 
						 //expired 
						 $get_stoc_qty= get_stock_quantity_qc_failed_data($bag_type_id="",$component_ids,$exist_ids='','',$blood_li->id) ;
						 if(!empty($get_stoc_qty['total_qty'])){ $qc_fail_qty= $get_stoc_qty['total_qty'];}else {  $qc_fail_qty= '0'; } 
										
						$get_stoc_qty= get_stock_quantity_discarded_data($bag_type_id="",$component_ids,$exist_ids='','',$blood_li->id) ;
						
						if(!empty($get_stoc_qty['total_qty'])){ $stock_dis_qty= $get_stoc_qty['total_qty']; }else { $stock_dis_qty= '0'; }
										
			            
			            
			            
			             array_push($rowData,$components_name,$blood_li->blood_group,$total_aw,$total_ex,$untested_qty,$twelve_qty,$sevqty,$test_stk_qty,$exp_qty_stock,$qc_fail_qty,$stock_dis_qty);
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    $i++;    
			            
			            
			        }
			//end of all the blood group table
			    
			}
			else if(empty($form_data['component']) && !empty($form_data['blood_grp_id']))
			{
			    
			    
			        
			        
			        foreach($all_component_list as $bloodcomponent)
                    {
			           $blood_grp_ids = $form_data['blood_grp_id'];
			           
			          
			            
                   /*  <div style="margin-bottom:5px;">
        				<a href="#!" class="btn_quick_search"><?php echo $bloodcomponent->component;?></a>
        				<a href="#!" class="btn_border"><?php echo $blood_group_name;?></a>
        			</div> */
            
            		
				        $total_aw=0;
				        $bag_type_id='';
						$exist_ids='';
							$get_stoc_qty= get_stock_dashboard_grand_total_quantity($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids) ;
							if($get_stoc_qty['total_qty']>0){
							if(!empty($get_stoc_qty['total_qty'])){ $total_aw =$total_aw+ $get_stoc_qty['total_qty']; }  
							$i++;
							}
							
                            $total_ex =0;		
                            $bag_type_id='';
                            $exist_ids=''; //$donated_data['component_id'][0]
                            $get_stoc_qty= get_stock_dashboard_quantity($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids) ;
                            if($get_stoc_qty['total_qty']>0){
                            if(!empty($get_stoc_qty['total_qty'])){ $total_ex =$total_ex+$get_stoc_qty['total_qty']; }  $i++;}
							
							$get_untested_qty= get_stock_quantity_awaiting_data("",$bloodcomponent->id,'','',$blood_grp_ids) ;
							if(!empty($get_untested_qty['total_qty'])){ $untested_qty= $get_untested_qty['total_qty']; }else{  $untested_qty= '0'; }
							
						//alert 12 days
						$get_twelve_qty= get_stock_quantity_twelve_data($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids,12);
						if(!empty($get_twelve_qty['total_qty'])){ $twelve_qty= $get_twelve_qty['total_qty']; }else{ $twelve_qty= '0'; } 
						//alert 7 days
						$get_seven_qty= get_stock_quantity_seven_data($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids,12) ;
						if(!empty($get_seven_qty['total_qty'])){ $sevqty= $get_seven_qty['total_qty'];  }else { $sevqty= '0'; } 
						
                        //Qc
                        $get_qc_qty= get_stock_quantity_tested_data($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids,12);
                        
						//alert 12 days
						
						if($get_qc_qty['total_qty']>0){
						 if(!empty($get_qc_qty['total_qty'])){ $test_stk_qty= $get_qc_qty['total_qty'];  }
						}else { $test_stk_qty = '0';} 
						$get_stoc_qty_expred= get_stock_quantity_expired_by_group($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids) ; 
					        if(!empty($get_stoc_qty_expred['total_qty'])){ $exp_qty_stock= $get_stoc_qty_expred['total_qty'];}else {  $exp_qty_stock = '0'; }
					     //expired 
						 $get_stoc_qty= get_stock_quantity_qc_failed_data($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids);
						 if(!empty($get_stoc_qty['total_qty'])){ $qc_fail_qty= $get_stoc_qty['total_qty'];}else {  $qc_fail_qty = '0'; } 
						 $get_stoc_qty= get_stock_quantity_discarded_data($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_grp_ids); 
						 if(!empty($get_stoc_qty['total_qty'])){ $stock_dis_qty= $get_stoc_qty['total_qty']; }else { $stock_dis_qty = '0'; } 
						 
						  array_push($rowData,$bloodcomponent->component,$blood_group_name,$total_aw,$total_ex,$untested_qty,$twelve_qty,$sevqty,$test_stk_qty,$exp_qty_stock,$qc_fail_qty,$stock_dis_qty);
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    $i++;    
						 
						 
		     }
			//end of all the blood group table
			    
			
			}
			else if(!empty($form_data['component']) && !empty($form_data['blood_grp_id']))
			{
			    
			        $blood_grp_ids = $form_data['blood_grp_id'];
			        
			        $componentids = $form_data['component'];
			           
			          /*
			            
                   <div style="margin-bottom:5px;">
        				<a href="#!" class="btn_quick_search"><?php echo $components_name;?></a>
        				<a href="#!" class="btn_border"><?php echo $blood_group_name;?></a>
        			</div> */
            
            		
					

						        $total_aw=0;
						        $bag_type_id='';
								$exist_ids='';
									$get_stoc_qty= get_stock_dashboard_grand_total_quantity($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids) ;
									if($get_stoc_qty['total_qty']>0){
									if(!empty($get_stoc_qty['total_qty'])){ $total_aw =$total_aw+ $get_stoc_qty['total_qty']; } 
									$i++;}
								
							
							$total_ex =0;		
						 
								
								$bag_type_id='';
								$exist_ids=''; //$donated_data['component_id'][0]
								$get_stoc_qty= get_stock_dashboard_quantity($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids) ;
								if($get_stoc_qty['total_qty']>0){
								 if(!empty($get_stoc_qty['total_qty'])){ $total_ex =$total_ex+$get_stoc_qty['total_qty']; }  $i++;}
									
									$get_untested_qty= get_stock_quantity_awaiting_data("",$componentids,'','',$blood_grp_ids) ;
								 if(!empty($get_untested_qty['total_qty'])){ $untested_qty = $get_untested_qty['total_qty']; }else{  $untested_qty = '0'; }
						//alert 12 days
						$get_twelve_qty= get_stock_quantity_twelve_data($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids,12) ;
						 if(!empty($get_twelve_qty['total_qty'])){ $twelve_qty = $get_twelve_qty['total_qty']; }else{ $twelve_qty = '0'; } 
						//alert 7 days
						$get_seven_qty= get_stock_quantity_seven_data($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids,12) ;
						if(!empty($get_seven_qty['total_qty'])){ $sevqty= $get_seven_qty['total_qty'];  }else { $sevqty= '0'; }
										
                            //Qc
                            
                            $get_qc_qty= get_stock_quantity_tested_data($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids,12) ;
                            
						//alert 12 days
						
									if($get_qc_qty['total_qty']>0){
									 if(!empty($get_qc_qty['total_qty'])){ $test_stk_qty = $get_qc_qty['total_qty'];  }
									}else { $test_stk_qty = '0';}
							$get_stoc_qty_expred= get_stock_quantity_expired_by_group($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids) ; 
						        if(!empty($get_stoc_qty_expred['total_qty'])){ $exp_qty_stock= $get_stoc_qty_expred['total_qty'];}else {  $exp_qty_stock= '0'; }
						 //expired 
						 $get_stoc_qty= get_stock_quantity_qc_failed_data($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids) ;
						 
						 if(!empty($get_stoc_qty['total_qty'])){ $qc_fail_qty = $get_stoc_qty['total_qty'];}else {  $qc_fail_qty = '0'; }
						$get_stoc_qty= get_stock_quantity_discarded_data($bag_type_id="",$componentids,$exist_ids='','',$blood_grp_ids) ;
						if(!empty($get_stoc_qty['total_qty'])){ $stock_dis_qty= $get_stoc_qty['total_qty']; }else { $stock_dis_qty = '0'; } 

					

				  array_push($rowData,$components_name,$blood_group_name,$total_aw,$total_ex,$untested_qty,$twelve_qty,$sevqty,$test_stk_qty,$exp_qty_stock,$qc_fail_qty,$stock_dis_qty);
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    $i++;    
                    
			//end of all the blood group table
			    
			
			}
			else 
			{
			    if(!empty($all_blood_group_list))
			    {
			  
			    //echo "<pre>";print_r($all_blood_group_list); exit;  
			    
			    
			$blood_comp=array();
             $rk=0;
			foreach($all_blood_group_list as $blood_li)
            {
                 
                $a=0;
                foreach($all_component_list as $bloodcomponent)
                {
                    /* <div style="margin-bottom:5px;">
				<a href="#!" class="btn_quick_search"><?php echo $bloodcomponent->component;?></a>
				<a href="#!" class="btn_border"><?php echo $blood_li->blood_group;?></a>
			</div> */

		     $total_aw=0;
		      
					$bag_type_id='';
					$exist_ids='';
					//$donated_data['component_id'][0]
					$get_stoc_qty= get_stock_dashboard_grand_total_quantity($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_li->id) ;
					if($get_stoc_qty['total_qty']>0){
					 if(!empty($get_stoc_qty['total_qty'])){ $total_aw =$total_aw+ $get_stoc_qty['total_qty']; } 
					 $i++;}
				
			
				$total_ex =0;		
			 
					
					$bag_type_id='';
					$exist_ids=''; //$donated_data['component_id'][0]
					$get_stoc_qty= get_stock_dashboard_quantity($bag_type_id="",$bloodcomponent->id,$exist_ids='','',$blood_li->id) ;
					if($get_stoc_qty['total_qty']>0){
					 if(!empty($get_stoc_qty['total_qty'])){ $total_ex =$total_ex+$get_stoc_qty['total_qty']; }  $i++;}
				
				$get_untested_qty= get_stock_quantity_awaiting_data('',$bloodcomponent->id,'','',$blood_li->id) ;
				if(!empty($get_untested_qty['total_qty'])){ $untested_qty = $get_untested_qty['total_qty']; }else{  $untested_qty =  '0'; }
				
						//alert 12 days
						$get_twelve_qty= get_stock_quantity_twelve_data('',$bloodcomponent->id,'','',$blood_li->id,12) ;
						if(!empty($get_twelve_qty['total_qty'])){ $twelve_qty = $get_twelve_qty['total_qty']; }else{ $twelve_qty = '0'; } 
						
						
						//alert 7 days
						$get_seven_qty= get_stock_quantity_seven_data('',$bloodcomponent->id,'','',$blood_li->id,12) ;
						
						if(!empty($get_seven_qty['total_qty'])){ $sevqty = $get_seven_qty['total_qty'];  }else { $sevqty =  '0'; } 
                            //Qc
                            $get_qc_qty= get_stock_quantity_tested_data('',$bloodcomponent->id,'','',$blood_li->id) ;
                       
						//alert 12 days
				
							if($get_qc_qty['total_qty']>0){
							 if(!empty($get_qc_qty['total_qty'])){ $test_stk_qty = $get_qc_qty['total_qty'];  }
							}else { $test_stk_qty = '0';} 
							$get_stoc_qty_expred= get_stock_quantity_expired_by_group('',$bloodcomponent->id,'','',$blood_li->id) ; 
						        if(!empty($get_stoc_qty_expred['total_qty'])){ $exp_qty_stock = $get_stoc_qty_expred['total_qty'];}else {  $exp_qty_stock = '0'; }
						 //expired 
						 $get_stoc_qty= get_stock_quantity_qc_failed_data('',$bloodcomponent->id,'','',$blood_li->id) ;
						 
						 if(!empty($get_stoc_qty['total_qty'])){ $qc_fail_qty = $get_stoc_qty['total_qty'];}else {  $qc_fail_qty = '0'; } 
						 $get_stoc_qty= get_stock_quantity_discarded_data('',$bloodcomponent->id,'','',$blood_li->id) ;
						 if(!empty($get_stoc_qty['total_qty'])){ $stock_dis_qty = $get_stoc_qty['total_qty']; }else { $stock_dis_qty = '0'; } 
						 
						 
						  array_push($rowData,$bloodcomponent->component,$blood_li->blood_group,$total_aw,$total_ex,$untested_qty,$twelve_qty,$sevqty,$test_stk_qty,$exp_qty_stock,$qc_fail_qty,$stock_dis_qty);
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    $i++;    

                }
                $rk++;
            }
            
          

			 
			}
			    
	     }
        
      // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
               foreach($data as $boking_data)
               {
                    $col = 0;
                    $row_val=1;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $boking_data[$field]);

                         $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                          $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                         $col++;
                         $row_val++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          //header('Content-Type: application/octet-stream');
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=blood_stock_dashboard_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }
    
    
    public function import_stock_excel()
    {
        //unauthorise_permission(97,628);
        $this->load->library('form_validation');
        $this->load->library('excel');
        $data['page_title'] = 'Import Blood Bank stock excel';
        $arr_data = array();
        $header = array();
        $path='';

       // print_r($_FILES); die;
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['patient_list']) || $_FILES['patient_list']['error']>0)
            {
               
               $this->form_validation->set_rules('patient_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               
                $config['upload_path']   = DIR_UPLOAD_PATH.'temp/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('patient_list')) 
                {
                    $error = array('error' => $this->upload->display_errors()); 
                    $data['file_upload_eror'] = $error; 

                    //echo "<pre> dfd";print_r(DIR_UPLOAD_PATH.'patient/'); exit;
                }
                else 
                { 
                    $data = $this->upload->data();
                    $path = $config['upload_path'].$data['file_name'];
           
                    //read file from path
                    $objPHPExcel = PHPExcel_IOFactory::load($path);
                    //get only the Cell Collection
                    $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                    //extract to a PHP readable array format
                    foreach ($cell_collection as $cell) 
                    {
                        $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                        $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                        $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                        //header will/should be in row 1 only. of course this can be modified to suit your need.
                        if ($row == 1) 
                        {
                            $header[$row][$column] = $data_value;
                        } 
                        else 
                        {
                            $arr_data[$row][$column] = $data_value;
                        }
                    }
                    //send the data in an array format
                    $data['header'] = $header;
                    $data['values'] = $arr_data;
                   
                }

                //echo "<pre>";print_r($arr_data); exit;
                if(!empty($arr_data))
                {
                    //echo '<pre>'; print_r($arr_data); exit;
                    $arrs_data = array_values($arr_data);
                    $total_patient = count($arrs_data);
                   
                   $array_keys = array('unit_no','donor_code','donor_name','know_illness','blood_pressure','pulse_rate','respiratory','gms','temprature','blood_group','blood_bag_type','component_price','platelet','prbc','ffp','date_of_collection','date_of_expiry','qty','blood_unit_no','blood_qc','post_complucation');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U');
                    $patient_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $patient_all_data= array();
                    for($i=0;$i<$total_patient;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $patient_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $patient_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    }
                    //echo "<pre>"; print_r($patient_all_data); exit;
                    $this->stock_model->save_all_blood_stock($patient_all_data);
                }
                if(!empty($path))
                {
                    unlink($path);
                }
               
                echo 1;
                return false;
            }
            else
            {

                $data['form_error'] = validation_errors();
                //echo "<pre>"; print_r($data['form_error']); exit;
                

            }
        }

        $this->load->view('blood_bank/stock/advance_import_stock_excel',$data);
    } 


    public function sample_stock_import_excel()
    {
            //unauthorise_permission(97,627);
            // Starting the PHPExcel library
            $this->load->library('excel');
            $this->excel->IO_factory();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
            $objPHPExcel->setActiveSheetIndex(0);
            // Field names in the first row
            $fields = array('Unit No.','Donor Code','Donor Name(*)','Known Illness','Blood Pressure','Pulse Rate','Respiratory Rate','gms%','Temperature','Bloog group(A+ve,B+ve,..)','Blood Bag Type', 'Component Price','PLATELET','PRBC','FFP','Collection Date(dd-mm-yyyy)','Expiry Date(dd-mm-yyyy)','Quantity','Blood unit No.','Blood QC(PASSED/FAILED)','Post Complication [if any]');
      
              $col = 0;
            foreach ($fields as $field)
            {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $col++;
            }
            $rowData = array();
            $data= array();
          
            // Fetching the table data
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            // Sending headers to force the user to download the file
            header('Content-Type: application/vnd.ms-excel charset=UTF-8');
            header("Content-Disposition: attachment; filename=stock_sample1_".time().".xls");  
            header("Pragma: no-cache"); 
            header("Expires: 0");
            ob_end_clean();
            $objWriter->save('php://output');
           
    }


// Please write code above this
}
?>
