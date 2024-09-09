<?php
class Nurse_handover_template extends CI_Controller
{
    protected $gender;
    public function __construct()
    {
        parent::__construct();
       
        $this->load->model('nurse_handover_template/nurse_handover_template_model','nurse_handover_template');
        $this->load->library('form_validation');
        $this->gender = ["Female","Male"];
        
    }

    public function index()
    {
        $data['page_title'] = 'Nurse Handover Template List'; 
        $this->load->view('nurse_handover_template/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(10,50);
        $users_data = $this->session->userdata('auth_users');

        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
    
       
            $list = $this->nurse_handover_template->get_datatables();
     
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $mc) {
         // print_r($religion);die;
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
            ////////// Check list end ///////////// 
            if($users_data['parent_id']==$mc->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$mc->id.'">'.$check_script; 
             }else{
               $row[]='';
             }
            $row[] = $mc->name;  
                    
            $row[] = date('d-M-Y H:i A',strtotime($mc->created_date)); 
 
            //Action button /////
            $btnedit = ""; 
            $btndelete = "";
         
            if($users_data['parent_id']==$mc->branch_id){
              
                 if(in_array('52',$users_data['permission']['action'])) 
                 {
                    
                    $btnedit = ' <a href="'.base_url('nurse_handover_template/edit/').$mc->id.'" class="btn-custom" href="javascript:void(0)" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                 }
                 if(in_array('53',$users_data['permission']['action'])) 
                 {
                    $btndelete = ' <a class="btn-custom" onClick="return delete_religion('.$mc->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                 } 
                 $print_medication_chart_print = "'".base_url('nurse_handover_template/print_details/'.$mc->id)."'";
            
                // $btn_medication_chart_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_medication_chart_print.')"  title="Print Nurse Handover Template" ><i class="fa fa-bar-chart"></i> Print</a>';
            }

            // End Action Button //
            
    
             $row[] = $btnedit.$btndelete;
             
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->nurse_handover_template->count_all(),
                        "recordsFiltered" => $this->nurse_handover_template->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function add($booking_id="")
    {
        $data['prescription_tab_setting'] = get_ipd_prescription_tab_setting();
        $data['prescription_medicine_tab_setting'] = get_ipd_prescription_medicine_tab_setting();
        $this->load->model('opd/opd_model','opd');
        $this->load->model('general/general_model'); 
        
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $post = $this->input->post();
        $patient_id = "";
        $patient_code = "";
        $simulation_id = "";
        $patient_name = "";
        $mobile_no = "";
        $gender = "";
        $age_y = "";
        $age_m = "";
        $age_d = "";
        $address = "";
        $city_id = "";
        $state_id = "";
        $country_id = ""; 
        $this->load->model('ipd_booking/ipd_booking_model');
        $this->load->model('patient/patient_model');
        //  $ipd_booking_data = $this->ipd_booking_model->get_by_id($booking_id);
   
       
        $data['simulation_list'] = $this->general_model->simulation_list();
   
        $data['page_title'] = "Add Nurse Handover Template";
        
        $post = $this->input->post();
        
        $data['form_error'] = []; 
     
        if(isset($post) && !empty($post))
        {   
          
          $this->nurse_handover_template->save();
          
          // $this->session->set_userdata('ipd_prescription_id',$prescription_id);
          $this->session->set_flashdata('success','Medication chart successfully added.');
          redirect(base_url('nurse_handover_template'));
        }   
        $this->load->model('ipd_booking/ipd_booking_model');
        $this->load->model('patient/patient_model');
        if(!empty($booking_id)) {
            $ipd_booking_data = $this->ipd_booking_model->get_by_id($booking_id);

            $data['form_data'] = [
                'ipd_id' => $ipd_booking_data['ipd_id'],
                'ipd_no' => $ipd_booking_data['ipd_no'],
                'patient_id' => $ipd_booking_data['patient_id'],
                'patient_code' => $ipd_booking_data['patient_code'],
                'patient_name' => $ipd_booking_data['patient_name'],
                'age_y' => $ipd_booking_data['age_y'],
                'age_m' => $ipd_booking_data['age_m'],
                'age_d' => $ipd_booking_data['age_d'],
                'mobile_no' => $ipd_booking_data['mobile_no'],
                'gender' => $ipd_booking_data['gender'],
            ];
        }
       
        // $data['medication_chart_list'] = $this->db->where(['booking_id'=>$booking_id,'patient_id'=>$patient_id])->get('hms_ipd_medication_chart')->result_array();
        $this->load->view('nurse_handover_template/add',$data);
    }

    public function get_ipd_details($term="")
    {
        $this->load->model('ipd_booking/ipd_booking_model');
        $this->load->model('patient/patient_model');
        $ipd_booking_data = $this->ipd_booking_model->get_by_id("",$term);
       $data = $ipd_booking_data['patient_name'] . ' ('.$ipd_booking_data['ipd_no'].')
       |' . $ipd_booking_data['ipd_no'].'
       |' . $ipd_booking_data['id'];
        echo json_encode([$data]);
    }

    public function get_full_ipd_details($booking_id="")
    {
        $this->load->model('ipd_booking/ipd_booking_model');
        $this->load->model('patient/patient_model');
        $ipd_booking_data = $this->ipd_booking_model->get_by_id($booking_id);
    
        echo json_encode($ipd_booking_data);
    }

    public function edit($id="")
    {
        $data['prescription_tab_setting'] = get_ipd_prescription_tab_setting();
        $data['prescription_medicine_tab_setting'] = get_ipd_prescription_medicine_tab_setting();
        
        $this->load->model('general/general_model'); 
        
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $post = $this->input->post();
        $patient_id = "";
        
        $this->load->model('ipd_booking/ipd_booking_model');
        $this->load->model('patient/patient_model');
        //  $ipd_booking_data = $this->ipd_booking_model->get_by_id($booking_id);
   
       
        $data['simulation_list'] = $this->general_model->simulation_list();
   
        $data['page_title'] = "Add Nurse Handover Template";
        
        $post = $this->input->post();
        
        $data['form_error'] = []; 
        $data['all_details'] = $this->nurse_handover_template->get_by_id($id);
        $this->load->model('ipd_booking/ipd_booking_model');
        $this->load->model('patient/patient_model');
        $ipd_booking_data = $this->ipd_booking_model->get_by_id($data['all_details']['ipd_id']);
        $data['form_data'] = [
            'ipd_no' => $ipd_booking_data['ipd_no'],
            'mobile_no' => $ipd_booking_data['mobile_no'],
            'patient_code' => $ipd_booking_data['patient_code'],
            'gender' => $ipd_booking_data['gender'],
            'patient_name' => $ipd_booking_data['patient_name'],
            'age_y' => $ipd_booking_data['age_y'],
            'age_m' => $ipd_booking_data['age_m'],
            'age_d' => $ipd_booking_data['age_d'],
            'ipd_id' => $ipd_booking_data['id']
        ];
        if(isset($post) && !empty($post))
        {   
          
          $this->nurse_handover_template->update($id);
          $this->session->set_userdata('madication_chart_id',$id);
          // $this->session->set_userdata('ipd_prescription_id',$prescription_id);
          $this->session->set_flashdata('success','Medication chart successfully updated.');
          redirect(base_url('nurse_handover_template/?status=print'));
        } 
       
        $data['medication_chart_list'] = $this->db->where(['medication_chart_table_id'=>$id])->get('hms_ipd_medication_chart')->result_array();
        $this->load->view('nurse_handover_template/add',$data);
    }
    public function delete($id="")
    {
       
        $this->nurse_handover_template->delete($id);
        $this->session->set_flashdata('success','Nurse Handover Template successfully deleted.');
        redirect(base_url('nurse_handover'));
    }

    public function print_details($id="")
    {
        if(empty($id))
            $id= $this->session->userdata('nurse_handover_id');
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $data = [];
        $this->load->model('ipd_booking/ipd_booking_model');
        $this->load->model('patient/patient_model');
        $this->load->model('general/general_model');  
        $data['all_details'] = $this->nurse_handover_template->get_by_id($id);
        $ipd_booking_data = $this->ipd_booking_model->get_by_id($data['all_details']['ipd_id']);
       
        $patient_id = $ipd_booking_data['patient_id'];
        $data['diagnosis_name'] = $this->db->where('id',$data['all_details']['diagnosis_id'])->get('hms_opd_diagnosis')->row_array()['diagnosis'];
       
        
        $data['data'] = $ipd_booking_data; //attend_doctor_id
        $data['doctor'] = get_doctor_signature($ipd_booking_data['attend_doctor_id']);
        $data['panel_company_name'] = $this->general_model->panel_company_details($data['data']['panel_name']);

        $this->load->view('nurse_handover_template/print_details',$data);
    }

    function deleteall()
    {
   
    $post = $this->input->post();  
   
        if(!empty($post))
        {
            $result = $this->nurse_handover_template->deleteall($post['row_id']);
            $response = "Nurse Handover Templates successfully deleted.";
            echo $response;
        }
    }

    public function diagnosis_list(){
        $term = $this->input->get();
        $data = [];
        // $this->db->select("diagnosis as text,id");
        if(!empty($term['term'])){
            $this->db->like('diagnosis', $term['term']);
            $this->db->limit(10);
            $data = $this->db->get('hms_opd_diagnosis')->result_array();
        } 
        else {
            $this->db->limit(10);
            $data = $this->db->get('hms_opd_diagnosis')->result_array();
        }
        echo json_encode($data);
    }

    public function get_details_by_id($id) {
        $data = $this->nurse_handover_template->get_by_id($id);
        echo json_encode($data);
    }
}
?>