<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounting extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();   
    }

    
	public function journal()
    {
        //unauthorise_permission(20,121);  
        $data['page_title'] = 'Journal Entries'; 
        $this->load->view('accounting/journal',$data);
    }

    public function journal_ajax_list()
    {   
        //unauthorise_permission(20,121);
        $this->load->model('accounting/journal_model','journal'); 
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->journal->get_datatables();

        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //print_r($list);die;
        foreach ($list as $journal) { 
            $no++;
            $row = array(); 
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }                  
              
            $row[] = $journal['created_date'];             
            $row[] = $journal['title'];
            $row[] = $journal['debit']; 
            $row[] = $journal['credit']; 
             
            $data[] = $row;
             
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->journal->count_all(),
                        "recordsFiltered" => $this->journal->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


}

?>