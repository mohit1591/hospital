<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_excel extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->library('form_validation');
    }
    
    
    public function collection_reports_excel()
    {
          $this->load->model('reports/reports_model','reports');
          $get = $this->input->get();
          $departs = array(); 
          if(!empty($get['dept']) && $get['dept'] !='') 
          { 
            $dept = $get['dept']; 
            $departs = explode(',', $dept); 
          }
          $users_data = $this->session->userdata('auth_users');
          $data['expense_list'] = $this->reports->get_expenses_details($get); 
          $branch_list= $this->session->userdata('sub_branches_data');
          $parent_id= $users_data['parent_id'];
          $branch_ids = array_column($branch_list, 'id'); 
            
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          $collection_tab_setting = $this->reports->get_collection_tab_setting();
          //  Field names in the first row
          $fields_array = array();
          if(!empty($collection_tab_setting))
           {
              foreach ($collection_tab_setting as $collection_setting) 
              {
                $fields_array[] = $collection_setting->setting_value;
              }
           }
           
           $fields = $fields_array;
          
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
        $self_opd_coll = $this->reports->self_opd_collection_list($get);
        $self_opd_coll_data='';
        $self_opd_coll_payment_mode='';
        //echo "<pre>"; print_r($self_opd_coll); exit;
        if(in_array(2, $departs) || empty($departs))  
        {
          $self_opd_coll_data =  $self_opd_coll['self_opd_coll'];
          $self_opd_coll_payment_mode = $self_opd_coll['self_opd_coll_payment_mode'];
        }
        //echo "<pre>"; print_r($self_opd_coll_payment_mode); exit;
        
        
          
          $data_excel= array();
          ////////////////////////////////////
          
          $total_tabs = count($collection_tab_setting);
          ////////////////////////////////////opd /////////////////////////////////////////
          if(!empty($self_opd_coll_data))
          {
               $i=0;
               $rowData = array();
               $k = 1;
               $opd_k=1;
               $self_opd_coll_total =0;
               
               if($opd_k=='1')
               {
               		//$i=$k;
                   array_push($rowData,'OPD');
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i = $i+1;
               }
               
               
               foreach($self_opd_coll_data as $collection)
               {
                   // echo "<pre>"; print_r($collection); exit;
                   //$collection =$collection[0];
                 
                   foreach ($collection_tab_setting as $tab_value) 
                   {
                       if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
                       {
                         
                          array_push($rowData,$opd_k);
                       }
                       if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
                       {
                           
                           if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ 
                               
                               array_push($rowData,$collection->reciept_prefix.$collection->reciept_suffix);
                               
                           }else{
                               
                               
                               array_push($rowData,'');
                           }
                           
                       }
                       if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
                       {
                          
                          array_push($rowData,trim($collection->patient_name));
                       }
                       
                        if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
                        {
                           
                            array_push($rowData,$collection->patient_code);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
                        {
                            
                            array_push($rowData,date('d-m-Y', strtotime($collection->created_date)));
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
                        {
                            
                            array_push($rowData,$collection->doctor_name);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
                        {
                           
                            array_push($rowData,$collection->doctor_hospital_name);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
                        {
                            
                            array_push($rowData,$collection->mobile_no);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
                        {
                            
                            array_push($rowData,$collection->panel_type);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
                        {
                            
                            array_push($rowData,$collection->booking_code);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
                        {
                           
                            array_push($rowData,$collection->total_amount);
                        }
                        
                        if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
                        {
                            array_push($rowData,$collection->discount_amount);
                        }
                        
                        if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
                        {
                            
                            array_push($rowData,$collection->net_amount);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                        {
                            
                            array_push($rowData,$collection->debit);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
                        {
                            if($collection->balance=='1.00' || $collection->balance=='0.00'){
                               
                                array_push($rowData,'0.00');
                            }else{ 
                                 
                                array_push($rowData,number_format($collection->balance-1,2));
                            }
                            
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                        {
                            
                            array_push($rowData,$collection->mode);
                        }
                       
                       
                   }
                   $self_opd_coll_total = $self_opd_coll_total+$collection->debit;
                   
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;  
                $k++;
                 
               
                   $opd_k++;
               }
            //echo "<pre>"; print_r($data_excel); exit;
               ////////////////payment total ////////////
               
              

               ///////////////Payment total end ////////
               if(!empty($self_opd_coll_payment_mode))
               {
               		$i=$k;
                   foreach($self_opd_coll_payment_mode as $payment_mode_opd)
                   {

                       	   foreach ($collection_tab_setting as $tab_value) 
                           {
                               
                                if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                                {
                                    
                                   array_push($rowData,number_format($payment_mode_opd->tot_debit,2));
                                }
                                if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                                {
                                    
                                    array_push($rowData,$payment_mode_opd->mode);
                                }
                               
                               
                           }
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   }
               }
               //echo "<pre>"; print_r($data_excel); exit;
                if(!empty($self_opd_coll_total))
               {
               		
                   		   foreach ($collection_tab_setting as $tab_value) 
                           {
                                if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                                {
                                 array_push($rowData,number_format($self_opd_coll_total,2));
                                }
                                if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                                {
                                	array_push($rowData,'Total');
                                }
                               
                               
                           }

                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   
               }
               
               
               
                
             
          }
          
          /////opd billing/////
            
            
            
            
            
      if(in_array(4, $departs) || empty($departs))  
      {
        $self_billing_collection_list = $this->reports->self_billing_collection_list($get);
        $self_bill_coll_data =  $self_billing_collection_list['self_bill_coll'];
        $self_bill_coll_payment_mode = $self_billing_collection_list['self_bill_coll_payment_mode'];
      }
        
          if(!empty($self_bill_coll_data))
          {
               //$i=0;
               $rowData = array();
              // $k = 1;
               $opd_billing_k=1;
               $self_billing_coll_total =0;
               
               if($opd_billing_k=='1')
               {
               		//$i=$k;
                   array_push($rowData,'OPD Billing');
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i = $i+1;
               }
               
               
               foreach($self_bill_coll_data as $collection)
               {
                   // echo "<pre>"; print_r($collection); exit;
                   //$collection =$collection[0];
                 
                   foreach ($collection_tab_setting as $tab_value) 
                   {
                       if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
                       {
                         
                          array_push($rowData,$opd_billing_k);
                       }
                       if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
                       {
                           
                           if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ 
                               
                               array_push($rowData,$collection->reciept_prefix.$collection->reciept_suffix);
                               
                           }else{
                               
                               
                               array_push($rowData,'');
                           }
                           
                       }
                       if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
                       {
                          
                          array_push($rowData,trim($collection->patient_name));
                       }
                       
                        if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
                        {
                           
                            array_push($rowData,$collection->patient_code);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
                        {
                            
                            array_push($rowData,date('d-m-Y', strtotime($collection->created_date)));
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
                        {
                            
                            array_push($rowData,$collection->doctor_name);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
                        {
                           
                            array_push($rowData,$collection->doctor_hospital_name);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
                        {
                            
                            array_push($rowData,$collection->mobile_no);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
                        {
                            
                            array_push($rowData,$collection->panel_type);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
                        {
                            
                            array_push($rowData,$collection->booking_code);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
                        {
                           
                            array_push($rowData,$collection->total_amount);
                        }
                        
                        if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
                        {
                            array_push($rowData,$collection->discount_amount);
                        }
                        
                        if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
                        {
                            
                            array_push($rowData,$collection->net_amount);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                        {
                            
                            array_push($rowData,$collection->debit);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
                        {
                            if($collection->balance=='1.00' || $collection->balance=='0.00'){
                               
                                array_push($rowData,'0.00');
                            }else{ 
                                 
                                array_push($rowData,number_format($collection->balance-1,2));
                            }
                            
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                        {
                            
                            array_push($rowData,$collection->mode);
                        }
                       
                       
                   }
                   $self_billing_coll_total = $self_billing_coll_total+$collection->debit;
                   
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;  
                //$k++;
                 
               
                   $opd_billing_k++;
               }

               ////////////////payment total ////////////
               
              

               ///////////////Payment total end ////////
               if(!empty($self_bill_coll_payment_mode))
               {
               		//$i=$;
                   foreach($self_bill_coll_payment_mode as $payment_mode_billing)
                   {

                       	   foreach ($collection_tab_setting as $tab_value) 
                           {
                               
                                if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                                {
                                    
                                   array_push($rowData,number_format($payment_mode_billing->tot_debit,2));
                                }
                                if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                                {
                                    
                                    array_push($rowData,$payment_mode_billing->mode);
                                }
                               
                               
                           }
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   }
               }
               
                if(!empty($self_billing_coll_total))
               {
               		
                   		   foreach ($collection_tab_setting as $tab_value) 
                           {
                                if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                                {
                                 array_push($rowData,number_format($self_billing_coll_total,2));
                                }
                                if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                                {
                                	array_push($rowData,'Total');
                                }
                               
                               
                           }

                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   
               }
               
               
               
                
          //echo "<pre>"; print_r($data_excel); exit;   
          }
          //opd billing end ///
          
          //////////////////////////////////////ipd //////////////////////////
          if(in_array(5, $departs) || empty($departs))  
          {
            $self_ipd_collection_list = $this->reports->self_ipd_collection_list($get);
         
            $self_ipd_coll_data = $self_ipd_collection_list['ipd_coll'];
            $self_ipd_coll_payment_mode = $self_ipd_collection_list['ipd_coll_payment_mode'];
          }
           //echo "<pre>"; print_r($self_ipd_coll_payment_mode); exit;
          if(!empty($self_ipd_coll_data))
          {
              $ipd_k=1;
              
              if($ipd_k=='1')
               {
               		//$i=$k;
                   array_push($rowData,'IPD');
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i = $i+1;
               }
              
          	   foreach($self_ipd_coll_data as $collection)
               {
                   foreach ($collection_tab_setting as $tab_value) 
                   {
                       if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
                       {
                         
                          array_push($rowData,$ipd_k);
                       }
                       if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
                       {
                           
                           if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ 
                               
                               array_push($rowData,$collection->reciept_prefix.$collection->reciept_suffix);
                               
                           }else{
                               
                               
                               array_push($rowData,'');
                           }
                           
                       }
                       if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
                       {
                          
                          array_push($rowData,trim($collection->patient_name));
                       }
                       
                        if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
                        {
                           
                            array_push($rowData,$collection->patient_code);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
                        {
                            
                            array_push($rowData,date('d-m-Y', strtotime($collection->created_date)));
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
                        {
                            
                            array_push($rowData,$collection->doctor_name);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
                        {
                           
                            array_push($rowData,$collection->doctor_hospital_name);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
                        {
                            
                            array_push($rowData,$collection->mobile_no);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
                        {
                            
                            array_push($rowData,$collection->panel_type);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
                        {
                            
                            array_push($rowData,$collection->booking_code);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
                        {
                           
                            array_push($rowData,$collection->total_amount);
                        }
                        
                        if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
                        {
                            array_push($rowData,$collection->discount_amount);
                        }
                        
                        if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
                        {
                            
                            array_push($rowData,$collection->net_amount);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                        {
                            
                            array_push($rowData,$collection->debit);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
                        {
                            if($collection->balance=='1.00' || $collection->balance=='0.00'){
                               
                                array_push($rowData,'0.00');
                            }else{ 
                                 
                                array_push($rowData,number_format($collection->balance-1,2));
                            }
                            
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                        {
                            
                            array_push($rowData,$collection->mode);
                        }
                       
                       
                   }
                   $self_ipd_coll_total = $self_ipd_coll_total+$collection->debit;
                   
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;  
                $k++;
                 
               
                   $ipd_k++;
               }




               	////////////////payment total ////////////
               
               

               ///////////////Payment total end ////////
               if(!empty($self_ipd_coll_payment_mode))
               {
               		
                   foreach($self_ipd_coll_payment_mode as $payment_mode_opd)
                   {
                            $po = count($collection_tab_setting);
                       	   foreach ($collection_tab_setting as $tab_value) 
                           {
                               
                                if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                                {
                                    
                                   array_push($rowData,number_format($payment_mode_opd->tot_debit,2));
                                }
                                if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                                {
                                    
                                    array_push($rowData,$payment_mode_opd->mode);
                                }
                               
                              $po--; 
                           }
                    $count = count($rowData);
                    for($j=$po;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   }
               }
               
               
               if(!empty($self_ipd_coll_total))
               {
               		
                   		   foreach ($collection_tab_setting as $tab_value) 
                           {
                                if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                                {
                                 array_push($rowData,number_format($self_ipd_coll_total,2));
                                }
                                if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                                {
                                	array_push($rowData,'Total');
                                }
                               
                               
                           }

                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   
               }
               
               



          }
          //////////////////////////////////////ipd //////////////////////////

          /////////////////////////////////pathology/////////////////////////
          if(in_array(1, $departs) || empty($departs))  
          {
          $self_path_collection_list = $this->reports->pathology_self_collection_list($get);
         
            $self_path_coll_data = $self_path_collection_list['path_coll'];
            $self_path_coll_payment_mode = $self_path_collection_list['path_coll_pay_mode'];
          }
           //echo "<pre>"; print_r($self_ipd_coll_payment_mode); exit;
          if(!empty($self_path_coll_data))
          {
              $path_k=1;
              
              if($path_k=='1')
               {
               		//$i=$k;
                   array_push($rowData,'Pathology');
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i = $i+1;
               }
          	   foreach($self_path_coll_data as $collection)
               {
                   foreach ($collection_tab_setting as $tab_value) 
                   {
                       if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
                       {
                         
                          array_push($rowData,$path_k);
                       }
                       if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
                       {
                           
                           if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ 
                               
                               array_push($rowData,$collection->reciept_prefix.$collection->reciept_suffix);
                               
                           }else{
                               
                               
                               array_push($rowData,'');
                           }
                           
                       }
                       if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
                       {
                          
                          array_push($rowData,trim($collection->patient_name));
                       }
                       
                        if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
                        {
                           
                            array_push($rowData,$collection->patient_code);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
                        {
                            
                            array_push($rowData,date('d-m-Y', strtotime($collection->created_date)));
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
                        {
                            
                            array_push($rowData,$collection->doctor_name);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
                        {
                           
                            array_push($rowData,$collection->doctor_hospital_name);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
                        {
                            
                            array_push($rowData,$collection->mobile_no);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
                        {
                            
                            array_push($rowData,$collection->panel_type);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
                        {
                            
                            array_push($rowData,$collection->booking_code);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
                        {
                           
                            array_push($rowData,$collection->total_amount);
                        }
                        
                        if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
                        {
                            array_push($rowData,$collection->discount_amount);
                        }
                        
                        if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
                        {
                            
                            array_push($rowData,$collection->net_amount);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                        {
                            
                            array_push($rowData,$collection->debit);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
                        {
                            if($collection->balance=='1.00' || $collection->balance=='0.00'){
                               
                                array_push($rowData,'0.00');
                            }else{ 
                                 
                                array_push($rowData,number_format($collection->balance-1,2));
                            }
                            
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                        {
                            
                            array_push($rowData,$collection->mode);
                        }
                       
                       
                   }
                   $self_path_coll_total = $self_path_coll_total+$collection->debit;
                   
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;  
                $k++;
                 
               
                   $path_k++;
               }




               	////////////////payment total ////////////
               
               

               ///////////////Payment total end ////////
               if(!empty($self_path_coll_payment_mode))
               {
               		
                   foreach($self_path_coll_payment_mode as $payment_mode_opd)
                   {

                       	   foreach ($collection_tab_setting as $tab_value) 
                           {
                               
                                if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                                {
                                    
                                   array_push($rowData,number_format($payment_mode_opd->tot_debit,2));
                                }
                                if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                                {
                                    
                                    array_push($rowData,$payment_mode_opd->mode);
                                }
                               
                               
                           }
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   }
               }
               
                if(!empty($self_path_coll_total))
               {
               		
                   		   foreach ($collection_tab_setting as $tab_value) 
                           {
                                if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                                {
                                 array_push($rowData,number_format($self_path_coll_total,2));
                                }
                                if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                                {
                                	array_push($rowData,'Total');
                                }
                               
                               
                           }

                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   
               }
               
               



          }
          

          ///////////////////////////////pathology end ///////////////////////
          
          /////////////////////////////////medicine sale/////////////////////////
          if(in_array(3, $departs) || empty($departs)) 
          {
            $self_sale_med_collection_list = $this->reports->self_medicine_collection_list($get);
         
            $self_sale_med_coll_data = $self_sale_med_collection_list['med_coll'];
            $self_sale_med_coll_payment_mode = $self_sale_med_collection_list['med_coll_pay_mode'];
           } //echo "<pre>"; print_r($self_ipd_coll_payment_mode); exit;
          if(!empty($self_sale_med_coll_data))
          {
              $med_k=1;
              
              if($med_k=='1')
               {
               		//$i=$k;
                   array_push($rowData,'Medicine');
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i = $i+1;
               }
          	   foreach($self_sale_med_coll_data as $collection)
               {
                   foreach ($collection_tab_setting as $tab_value) 
                   {
                       if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
                       {
                         array_push($rowData,$med_k);
                       }
                       if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
                       {
                           
                           if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ 
                               
                               array_push($rowData,$collection->reciept_prefix.$collection->reciept_suffix);
                               
                           }else{
                               
                               
                               array_push($rowData,'');
                           }
                           
                       }
                       if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
                       {
                          
                          array_push($rowData,trim($collection->patient_name));
                       }
                       
                        if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
                        {
                           
                            array_push($rowData,$collection->patient_code);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
                        {
                            
                            array_push($rowData,date('d-m-Y', strtotime($collection->created_date)));
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
                        {
                            
                            array_push($rowData,$collection->doctor_name);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
                        {
                           
                            array_push($rowData,$collection->doctor_hospital_name);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
                        {
                            
                            array_push($rowData,$collection->mobile_no);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
                        {
                            
                            array_push($rowData,$collection->panel_type);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
                        {
                            
                            array_push($rowData,$collection->booking_code);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
                        {
                           
                            array_push($rowData,$collection->total_amount);
                        }
                        
                        if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
                        {
                            array_push($rowData,$collection->discount_amount);
                        }
                        
                        if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
                        {
                            
                            array_push($rowData,$collection->net_amount);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                        {
                            
                            array_push($rowData,$collection->debit);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
                        {
                            if($collection->balance=='1.00' || $collection->balance=='0.00'){
                               
                                array_push($rowData,'0.00');
                            }else{ 
                                 
                                array_push($rowData,number_format($collection->balance-1,2));
                            }
                            
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                        {
                            
                            array_push($rowData,$collection->mode);
                        }
                       
                       
                   }
                   $self_sale_med_coll_total = $self_sale_med_coll_total+$collection->debit;
                   
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;  
                $k++;
                $med_k++;
                 
               }




               	////////////////payment total ////////////
                
              

               ///////////////Payment total end ////////
               if(!empty($self_sale_med_coll_payment_mode))
               {
               		
                   foreach($self_sale_med_coll_payment_mode as $payment_mode_opd)
                   {

                       	   foreach ($collection_tab_setting as $tab_value) 
                           {
                               
                                if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                                {
                                    
                                   array_push($rowData,number_format($payment_mode_opd->tot_debit,2));
                                }
                                if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                                {
                                    
                                    array_push($rowData,$payment_mode_opd->mode);
                                }
                               
                               
                           }
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   }
               }
               
               if(!empty($self_sale_med_coll_total))
               {
                   	
                   		   foreach ($collection_tab_setting as $tab_value) 
                           {
                                if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                                {
                                 array_push($rowData,number_format($self_sale_med_coll_total,2));
                                }
                                if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                                {
                                	array_push($rowData,'Total');
                                }
                               
                               
                           }

                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   
               }
               
               



          }
          
           /////////////////////////////////Dialysis/////////////////////////
          if(in_array(16, $departs) || empty($departs)) 
          {
            $self_sale_dialysis_collection_list = $this->reports->self_dialysis_collection_list($get);
         
            $self_dialysis_coll_data = $self_sale_dialysis_collection_list['self_dialysis_coll'];
            $self_dialysis_coll_payment_mode = $self_sale_dialysis_collection_list['self_dialysis_coll_payment_mode'];
           } //echo "<pre>"; print_r($self_ipd_coll_payment_mode); exit;
          if(!empty($self_dialysis_coll_data))
          {
              $med_k=1;
              
              if($med_k=='1')
               {
               		//$i=$k;
                   array_push($rowData,'Dialysis');
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i = $i+1;
               }
          	   foreach($self_dialysis_coll_data as $collection)
               {
                   foreach ($collection_tab_setting as $tab_value) 
                   {
                       if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
                       {
                         array_push($rowData,$med_k);
                       }
                       if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
                       {
                           
                           if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ 
                               
                               array_push($rowData,$collection->reciept_prefix.$collection->reciept_suffix);
                               
                           }else{
                               
                               
                               array_push($rowData,'');
                           }
                           
                       }
                       if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
                       {
                          
                          array_push($rowData,trim($collection->patient_name));
                       }
                       
                        if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
                        {
                           
                            array_push($rowData,$collection->patient_code);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
                        {
                            
                            array_push($rowData,date('d-m-Y', strtotime($collection->created_date)));
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
                        {
                            
                            array_push($rowData,$collection->doctor_name);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
                        {
                           
                            array_push($rowData,$collection->doctor_hospital_name);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
                        {
                            
                            array_push($rowData,$collection->mobile_no);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
                        {
                            
                            array_push($rowData,$collection->panel_type);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
                        {
                            
                            array_push($rowData,$collection->booking_code);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
                        {
                           
                            array_push($rowData,$collection->total_amount);
                        }
                        
                        if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
                        {
                            array_push($rowData,$collection->discount_amount);
                        }
                        
                        if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
                        {
                            
                            array_push($rowData,$collection->net_amount);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                        {
                            
                            array_push($rowData,$collection->debit);
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
                        {
                            if($collection->balance=='1.00' || $collection->balance=='0.00'){
                               
                                array_push($rowData,'0.00');
                            }else{ 
                                 
                                array_push($rowData,number_format($collection->balance-1,2));
                            }
                            
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                        {
                            
                            array_push($rowData,$collection->mode);
                        }
                       
                       
                   }
                   $self_dialysis_coll_total = $self_dialysis_coll_total+$collection->debit;
                   
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;  
                $k++;
                $med_k++;
                 
               }




               	////////////////payment total ////////////
                
              

               ///////////////Payment total end ////////
               if(!empty($self_dialysis_coll_payment_mode))
               {
               		
                   foreach($self_dialysis_coll_payment_mode as $payment_mode_opd)
                   {

                       	   foreach ($collection_tab_setting as $tab_value) 
                           {
                               
                                if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                                {
                                    
                                   array_push($rowData,number_format($payment_mode_opd->tot_debit,2));
                                }
                                if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                                {
                                    
                                    array_push($rowData,$payment_mode_opd->mode);
                                }
                               
                               
                           }
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   }
               }
               
               if(!empty($self_dialysis_coll_total))
               {
                   	
                   		   foreach ($collection_tab_setting as $tab_value) 
                           {
                                if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                                {
                                 array_push($rowData,number_format($self_dialysis_coll_total,2));
                                }
                                if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                                {
                                	array_push($rowData,'Total');
                                }
                               
                               
                           }

                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   
               }
               
               



          }
          

          ///////////////////////////////Dialysis end ///////////////////////
          
          
        //echo "<pre>"; print_r($data_excel); exit;
          // Fetching the table data
          $row = 2;
          if(!empty($data_excel))
          {
               foreach($data_excel as $boking_data)
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
               
               
               
               //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col-2,$row,'Total');
               //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col-1,$row,$self_opd_coll_total);
             
               
               
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          //header('Content-Type: application/octet-stream');
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=collection_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
             if (ob_get_length() > 0 ) {
                    ob_end_clean();
             }
          $objWriter->save('php://output');
         }
    
    }
    
    
    
    public function test_collection()
    {
      
      $data['self_billing_collection_list'] = $this->reports->self_billing_collection_list($get);
      
      $data['self_ambulance_collection_list'] = $this->reports->self_ambulance_collection_list($get);
      
      $data['self_medicine_collection_list'] = $this->reports->self_medicine_collection_list($get);
      $data['self_medicine_return_collection_list'] = $this->reports->self_medicine_return_collection_list($get);
      $data['self_ipd_collection_list'] = $this->reports->self_ipd_collection_list($get);

      $data['pathology_doctor_collection_list'] = $this->reports->pathology_doctor_collection_list($get);
      $data['pathology_self_collection_list'] = $this->reports->pathology_self_collection_list($get);
      $data['self_vaccination_collection_list'] = $this->reports->self_vaccination_collection_list($get);
      //print_r($data['branch_vaccination_collection_list']); die;
      $data['self_ot_collection_list'] = $this->reports->self_ot_collection_list($get);

      $data['self_blood_bank_collection_list'] = $this->reports->self_blood_bank_collection_list($get);
           // print_r($data['self_ot_collection_list']); die;
      if(!empty($branch_ids))
      {
        $data['branch_collection_list'] = $this->reports->branch_opd_collection_list($get,$branch_ids);
        $data['branch_billing_collection_list'] = $this->reports->branch_billing_collection_list($get,$branch_ids);
        
        $data['branch_ambulance_collection_list'] = $this->reports->branch_ambulance_collection_list($get,$branch_ids);
        
        $data['branch_medicine_collection_list'] = $this->reports->branch_medicine_collection_list($get,$branch_ids);
        $data['branch_medicine_return_collection_list'] = $this->reports->branch_medicine_return_collection_list($get,$branch_ids);
        
        $data['branch_vaccination_collection_list'] = $this->reports->branch_vaccination_collection_list($get,$branch_ids);
        //print_r($data['branch_vaccination_collection_list']); die;
        $data['branch_ipd_collection_list'] = $this->reports->branch_ipd_collection_list($get,$branch_ids);

        $data['pathology_branch_collection_list'] = $this->reports->pathology_branch_collection_list($get,$branch_ids);
        $data['branch_ot_collection_list'] = $this->reports->branch_ot_collection_list($get,$branch_ids);

        /* blood bank collection report */
        $data['blood_bank_branch_collection_list'] = $this->reports->blood_bank_branch_collection_list($get,$branch_ids);
    }
    }

    
   

}
?>