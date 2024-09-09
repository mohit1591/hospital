<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Journal_model extends CI_Model {

	var $table = 'hms_payment';
	var $column = array('hms_payment.id','hms_payment.patient_id','hms_payment.debit','hms_payment.credit');  
	var $order = array('id' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_payment.*, (CASE WHEN hms_payment.section_id=1 THEN path_test_booking.lab_reg_no ELSE 'N/A' END) as booking_code, hms_payment_mode.payment_mode, hms_patient.patient_name, hms_doctors.doctor_name"); 
		$this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id','left');
		$this->db->join('hms_doctors','hms_doctors.id = hms_payment.doctor_id','left');
		$this->db->join('hms_patient','hms_patient.id = hms_payment.patient_id','left');
		$this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
		$this->db->from('hms_payment'); 
        $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
        $this->db->where('hms_payment.section_id',1);
        $this->db->where('hms_payment.parent_id > 0');

		$i = 0;
	
		foreach ($this->column as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column) - 1 == $i) //last loop+
					$this->db->group_end(); //close bracket
			}
			$column[$i] = $item; // set column array variable to order processing
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		$result = $query->result(); 
		if(!empty($result))
		{
			$row = [];
			$i=0;
			$dr = "<div style='float:right; font-weight:bold;'>DR</div>";
			foreach($result as $journal)
			{ 
				$dr_title = "";
	            $dr_amount = "";
	            $cr_amount = "";
	            ///////// Test Booking Entry //////////////////////
	            if($journal->section_id==1 && $journal->parent_id>0 && $journal->debit>0 && $journal->balance>0)
	            {
	                $dr_title = $journal->payment_mode.'  A/C'.$dr;
	                $dr_amount = $journal->debit ;
	            
		            $row[$i]['created_date'] = date('d-m-Y', strtotime($journal->created_date));             
		            $row[$i]['id'] = $journal->id;
		            $row[$i]['title'] = $dr_title;
		            $row[$i]['debit'] = $dr_amount; 
		            $row[$i]['credit'] = $cr_amount;  
	                
		            /////// Discount Clouse /////////////////
		            if($journal->discount_amount>0)
		            {
			            $i = $i+1;
		                $row[$i]['id'] = $journal->id;
		                $row[$i]['created_date'] = "";         
			            $row[$i]['title'] = "Test Booking Discount A/C".$dr;
			            $row[$i]['debit'] = $journal->discount_amount; 
			            $row[$i]['credit'] = "";  
	                }
		            ///////////////////////////////////////

		            

		            //////// End DR Entry ///////////////////

	                //////// CR entry
	                $dr_title = " &nbsp; &nbsp; To, Test Booking";
		            $dr_amount = "";
		            $cr_amount =  $journal->total_amount;
		            $i = $i+1;
		            $row[$i]['id'] = $journal->id;
	                $row[$i]['created_date'] = "";             
		            $row[$i]['title'] = $dr_title;
		            $row[$i]['debit'] = $dr_amount; 
		            $row[$i]['credit'] = $cr_amount; 
		            //////// END CR entry 

		            // Entry Details //////////////
		            $i = $i+1;
		            $dr_title = "<span style='font-style:italic;'>(Being ".$journal->payment_mode." test booking to	".$journal->patient_name."(".$journal->booking_code."))<span>";
		            $dr_amount = "";
		            $cr_amount =  "";
		            $row[$i]['id'] = $journal->id;
	                $row[$i]['created_date'] = "";             
		            $row[$i]['title'] = $dr_title;
		            $row[$i]['debit'] = ""; 
		            $row[$i]['credit'] = ""; 
		            // End Entry Details /////////
                }

				/////// Balance Due Clouse /////////////////
				 else if($journal->parent_id>0 && $journal->credit=='0.00' && $journal->debit>0 && $journal->doctor_id==0)
				    {  
				    	//////// DR Entry ////////////
				        $i = $i+1;
				        $row[$i]['id'] = $journal->id;
				        $row[$i]['created_date'] = date('d-m-Y', strtotime($journal->created_date));        
				        $row[$i]['title'] = $journal->payment_mode." A/C".$dr;
				        $row[$i]['debit'] = $journal->debit; 
				        $row[$i]['credit'] = "";  
				        ///////////////////////////////

				        //////// CR Entry ////////////
                        $i = $i+1;
				        $row[$i]['id'] = $journal->id;
				        $row[$i]['created_date'] = "";        
				        $row[$i]['title'] = " &nbsp; &nbsp; To,  Balance Clearance";
				        $row[$i]['debit'] = ""; 
				        $row[$i]['credit'] = $journal->debit; 

				        //////// Description Entry ////////////
                        $i = $i+1;
				        $row[$i]['id'] = $journal->id;
				        $row[$i]['created_date'] = "";        
				        $row[$i]['title'] = " <span style='font-style:italic;'>(Being ".$journal->payment_mode." balance clearance to ".$journal->patient_name."(".$journal->booking_code."))</span>";
				        $row[$i]['debit'] = ""; 
				        $row[$i]['credit'] = ""; 
				        //////////////////////////////
				    }
				    ///////////////////////////////////////

				    /////// Doctor Commission Clouse /////////////////
				 else if($journal->parent_id==0 && $journal->credit=='0.00' && $journal->debit>0 && $journal->doctor_id>0)
				    {  
				    	//////// DR Entry ////////////
				        $i = $i+1;
				        $row[$i]['id'] = $journal->id;
				        $row[$i]['created_date'] = date('d-m-Y', strtotime($journal->created_date));        
				        $row[$i]['title'] = "Doctor Commision A/C".$dr;
				        $row[$i]['debit'] = $journal->debit; 
				        $row[$i]['credit'] = "";  
				        ///////////////////////////////

				        //////// CR Entry ////////////
                        $i = $i+1;
				        $row[$i]['id'] = $journal->id;
				        $row[$i]['created_date'] = "";        
				        $row[$i]['title'] = " &nbsp; &nbsp; To, ".$journal->payment_mode;
				        $row[$i]['debit'] = ""; 
				        $row[$i]['credit'] = $journal->debit; 

				        //////// Description Entry ////////////
                        $i = $i+1;
				        $row[$i]['id'] = $journal->id;
				        $row[$i]['created_date'] = "";        
				        $row[$i]['title'] = " <span style='font-style:italic;'>(Being ".$journal->payment_mode." doctor commission to Dr. ".$journal->doctor_name.")</span>";
				        $row[$i]['debit'] = ""; 
				        $row[$i]['credit'] = ""; 
				        //////////////////////////////
				    }
				    ///////////////////////////////////////
	        $i++;    
			}

			return $row;
			
		}
		else
		{
           return $result;
		} 
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

}
?>