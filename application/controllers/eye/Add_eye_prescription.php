<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Add_eye_prescription extends CI_Controller 
{
 
	function __construct() 
	{
		parent::__construct();
		auth_users();  
    $this->load->model('eye/add_prescription/add_new_prescription_model','add_prescript'); 
     $this->load->model('general/general_model');
    }

    public function index($booking_id="")
    {
      $data['page_title']='Eye Prescription';
       $post= $this->input->post();
       $result=$this->add_prescript->get_new_data_by_id($booking_id);
     //  print_r($result);die();
      // unauthorise_permission('351','2108');
       if(!empty($post))  
       {
         $this->add_prescript->save();
       }
      // echo "<pre>";print_r($post);die();
      /* Investigation Tab data */
      $data['eye_region'] = $this->general_model->eye_region();
      $data['lab_investigations'] = $this->general_model->lab_test_list();
      //print_r($data['lab_investigations']);die;
      $data['ophthal_set'] = $this->general_model->ophthal_set();
      $data['lab_set'] = $this->general_model->lab_set();
      $data['radiology_set'] = $this->general_model->radiology_set();
      $data['xray_mri_investigation']=$this->general_model->xray_mri_investigation();
      $data['pres_id']='';
     /* Investigation Tab */

   /* Diagnosis Tab */
     $data['icd_eye_sections']=$this->general_model->icd_eye_section();
     $data['custom_made_icds']=$this->general_model->custom_made_icd();
    // print_r($data['custom_made_icds']);die;
     /* Diagnosis Tab */

     /* Advice Tab */
     $data['procedure_eye_region'] = $this->general_model->procedure_eye_region();
    	$this->load->view('eye/new_add_eye_prescription/prescription',$data);
    }
    
    public function drawing_prescription($booking_id="", $pres_id="")
    {
        $post = $this->input->post();
        if(isset($post) && !empty($post))
        {    
            $drawing_data = $this->session->userdata('drawing_data');
            if(!isset($drawing_data) || empty($drawing_data))
            {
                $this->session->set_userdata('drawing_data');
                $drawing_data = $this->session->userdata('drawing_data');
            }
            $upload_dir = DIR_UPLOAD_PATH."eye/prescription_drawing/";
            $img = $_POST['image'];
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file_name =  mktime() . ".jpeg";
            $file = $upload_dir .$file_name;
            $success = file_put_contents($file, $data);  
            $data_arr = array('image'=>$file_name, 'remark'=>$_POST['remark'], 'booking_id'=>$_POST['booking_id'], 'pres_id'=>$_POST['pres_id']);
            $drawing_data[] = $data_arr;
            $this->session->set_userdata('drawing_data', $drawing_data);
            echo '1';
            exit;
        }
        $data['booking_id'] = $booking_id;
        $data['pres_id'] = $pres_id;
        $data['drawing_master']=$this->add_prescript->drawing_master();
        $this->load->view('eye/new_add_eye_prescription/pages/drawing',$data);
    }
    
    public function delete_drawing($key="")
    {
        if($key!="")
        {
            $data_arr = [];
            $drawing_data = $this->session->userdata('drawing_data');
            if(!empty($drawing_data))
            {
                unset($drawing_data[$key]);  
                $this->session->set_userdata('drawing_data', $drawing_data);
            }
        }
    }
    
    
    public function drawing_data()
    {
        $drawing_data = $this->session->userdata('drawing_data');
        if(!empty($drawing_data))
        {
            $tr_data = '';
            foreach($drawing_data as $key=>$drawing)
            {
                $tr_data .= '<tr>
                               <td><a href="'.ROOT_UPLOADS_PATH.'eye/prescription_drawing/'.$drawing['image'].'"  target="_blank"><img src="'.ROOT_UPLOADS_PATH.'eye/prescription_drawing/'.$drawing['image'].'" width="80px"/></a></td>
                               <td>'.$drawing['remark'].'</td>
                               <td><a class="btn-save" href="javascript:void(0);"  onclick="return delete_drawing('.$key.');" style="">Delete</a></td>
                             </tr>';
            }
        }
        else
        {
            $tr_data = '<tr><td colspan="3" style="color:red;">Record not found</td></tr>';
        }
        echo $tr_data;
    }

    public function test($booking_id="", $pres_id="")
    {
     $post= $this->input->post();
      //unauthorise_permission('351','2108');
     $this->load->model('plan_management/Plan_management_model','plan_mgmt');
     $plan_list=$this->plan_mgmt->get_plan_list();
     $result=$this->add_prescript->get_new_data_by_id($booking_id);
     $data['plan_list']=$plan_list;
     $data['datas']=$result;
     $data['medicine_sets']=$this->add_prescript->get_medicine_sets($result['branch_id']);
     $data['dilate_status'] = $result['dilate_status'];
     $drawing_data = $this->session->userdata('drawing_data');
    if(!isset($drawing_data) || empty($drawing_data))
    {
        $drawing_list = $this->add_prescript->get_drawing($booking_id, $pres_id); 
        $this->session->set_userdata('drawing_data', $drawing_list);
    }
     
     
     $data['form_data']=array('history_flag'=>1, 'contactlens_flag'=>1, 'glassesprescriptions_flag'=>1,'intermediate_glasses_prescriptions_flag'=>1, 'examination_flag'=>1, 'diagnosis_flag'=>1,'investigations_flag'=>1,'advice_flag'=>1);
      /* Investigation Tab data */
      $data['eye_region'] = $this->general_model->eye_region();
      $data['lab_investigations'] = $this->general_model->lab_test_list();
      $data['ophthal_set'] = $this->general_model->ophthal_set();
      $data['lab_set'] = $this->general_model->lab_set();
      $data['radiology_set'] = $this->general_model->radiology_set();
      $data['xray_mri_investigation']=$this->general_model->xray_mri_investigation();
     /* Investigation Tab */

      /* diagnosis */
       $data['icd_eye_sections']=$this->general_model->icd_eye_section();
       $data['custom_made_icds']=$this->general_model->custom_made_icd();
      /* Diagnosis */

     /* Advice Tab data */
      $data['advice_sets'] = $this->add_prescript->get_advice_sets($result['branch_id']);
      $data['procedure_eye_region'] = $this->general_model->procedure_eye_region();
     /* Advice Tab */

      
     if(!empty($pres_id))
     {
      $pres_result = $this->add_prescript->get_prescription_by_id($booking_id,$pres_id);
      $result_edit = $this->add_prescript->get_prescription_new_by_id($booking_id,$pres_id);
      $result_refraction = $this->add_prescript->get_prescription_refraction_new_by_id($booking_id,$pres_id);
      $result_examination = $this->add_prescript->get_prescription_examination_id($booking_id,$pres_id);


      $result_biometry = $this->add_prescript->get_prescription_biometry_id($booking_id,$pres_id);
      
      //echo "<pre>"; print_r($result_biometry); exit;
      
      //biometry start
      
    $data['biometry_ob_k1_one'] = $result_biometry['biometry_ob_k1_one'];
    $data['biometry_ob_k1_two'] = $result_biometry['biometry_ob_k1_two'];
    $data['biometry_ob_k1_three'] = $result_biometry['biometry_ob_k1_three'];
    $data['biometry_ob_k2_one'] = $result_biometry['biometry_ob_k2_one'];
    $data['biometry_ob_k2_two'] = $result_biometry['biometry_ob_k2_two'];
    $data['biometry_ob_k2_three'] = $result_biometry['biometry_ob_k2_three'];
    $data['biometry_ob_al_one'] = $result_biometry['biometry_ob_al_one'];
    $data['biometry_ob_al_two'] = $result_biometry['biometry_ob_al_two'];
    $data['biometry_ob_al_three'] = $result_biometry['biometry_ob_al_three'];
    $data['biometry_ascan_one'] = $result_biometry['biometry_ascan_one'];
    $data['biometry_ascan_two'] = $result_biometry['biometry_ascan_two'];
    $data['biometry_ascan_three'] = $result_biometry['biometry_ascan_three'];
    $data['biometry_ascan_sec_one'] = $result_biometry['biometry_ascan_sec_one'];
    $data['biometry_ascan_sec_two'] = $result_biometry['biometry_ascan_sec_two'];
    $data['biometry_ascan_sec_three'] = $result_biometry['biometry_ascan_sec_three'];
    $data['biometry_ascan_thr_one'] = $result_biometry['biometry_ascan_thr_one'];
    $data['biometry_ascan_thr_two'] = $result_biometry['biometry_ascan_thr_two'];
    $data['biometry_ascan_thr_three'] = $result_biometry['biometry_ascan_thr_three'];
    $data['biometry_iol_one'] = $result_biometry['biometry_iol_one'];
    
    
    $data['biometry_srk_one'] = $result_biometry['biometry_srk_one'];
    
    $data['biometry_error_one'] = $result_biometry['biometry_error_one'];
    $data['biometry_barett_one'] = $result_biometry['biometry_barett_one'];
    $data['biometry_error_one_two'] = $result_biometry['biometry_error_one_two'];
    $data['biometry_iol_two'] = $result_biometry['biometry_iol_two'];
    $data['biometry_ascan_sec_sec'] = $result_biometry['biometry_ascan_sec_sec'];
    $data['biometry_error_sec'] = $result_biometry['biometry_error_sec'];
    
    $data['biometry_barett_sec'] = $result_biometry['biometry_barett_sec'];
    $data['biometry_error_one_sec'] = $result_biometry['biometry_error_one_sec'];
    $data['biometry_iol_thr'] = $result_biometry['biometry_iol_thr'];
    $data['biometry_ascan_sec_thr'] = $result_biometry['biometry_ascan_sec_thr'];
    $data['biometry_error_thr'] = $result_biometry['biometry_error_thr'];
    $data['biometry_barett_thr'] = $result_biometry['biometry_barett_thr'];
    $data['biometry_error_one_thr'] = $result_biometry['biometry_error_one_thr'];
    $data['biometry_iol_four'] = $result_biometry['biometry_iol_four'];
    $data['biometry_ascan_sec_four'] = $result_biometry['biometry_ascan_sec_four'];
    $data['biometry_error_four'] = $result_biometry['biometry_error_four'];
    $data['biometry_barett_four'] = $result_biometry['biometry_barett_four'];
    $data['biometry_error_one_four'] = $result_biometry['biometry_error_one_four'];
    
    
    $data['biometry_iol_five'] = $result_biometry['biometry_iol_five'];
    $data['biometry_ascan_sec_five'] = $result_biometry['biometry_ascan_sec_five'];
    
    $data['biometry_error_five'] = $result_biometry['biometry_error_five'];
    
    $data['biometry_barett_five'] = $result_biometry['biometry_barett_five'];
    
    $data['biometry_error_one_five'] = $result_biometry['biometry_error_one_five'];
    
    $data['biometry_remarks'] = $result_biometry['biometry_remarks'];
    
      //end of biometry

      
      /* advice */
      $result_advice=$this->add_prescript->get_advice_by_id($booking_id,$pres_id);
      $advice_medication=json_decode($result_advice['medication_tab']);
      $data['advice_medication']=(array)$advice_medication;
      $advice_procedure=json_decode($result_advice['proceduces_tab']);
      $data['advice_procedure']=(array)$advice_procedure;
      $advice_referral=json_decode($result_advice['referral_tab']);
      $data['advice_referral']=(array)$advice_referral;
      $advice_advice=json_decode($result_advice['advice_tab']);
      $data['advice_adv']=(array)$advice_advice;
      $advice_comm=json_decode($result_advice['comments']);
      $data['advice_comm']=(array)$advice_comm;
    
      /* advice end */
       /* Investigation */
      $result_investigation=$this->add_prescript->get_investigation_by_id($booking_id,$pres_id);
      $result_advice=$this->add_prescript->get_advice_by_id($booking_id,$pres_id);
      $ophthal_data=json_decode($result_investigation['ophthal_set']);
      $data['ophthal_data']=(array)$ophthal_data;
      $laboratory_data=json_decode($result_investigation['laboratory_set']);
      $data['laboratory_data']=(array)$laboratory_data;
      $radiology_data=json_decode($result_investigation['radiology_set']);
      $data['radiology_data']=(array)$radiology_data;
      $invest_comment=json_decode($result_investigation['invest_comm']);
      $data['invest_comment']=(array)$invest_comment;
      $data['prescription_id']=$pres_id;
      $data['sale_id']=$pres_result['sale_id'];
      $data['form_data']=$pres_result;
      /* investigation */

      /* Diagnosis */
      $diagno_result=$this->add_prescript->get_diagnosis_data_by_id($booking_id,$pres_id);
      //echo "<pre>"; print_r($diagno_result); exit;
      $diagnosis_data=json_decode($diagno_result['diagnosis_data']);
      $diagno_lists=(array)$diagnosis_data;
      $data['diagno_lists']=(array)$diagno_lists['diagnosis_data'];
      $data['provisional_comment']=$diagno_result['provisional_cmnt'];
      $data['provisional_check']=$diagno_result['provisional_check'];
      $data['provisional_date']=$diagno_result['created_date'];

      /* diagnosis */
//echo "<pre>"; print_r($result_edit['chief_complaints']); exit;

      $history_radios_data=json_decode($result_edit['history_radios']); 
      $data['history_radios_data']=(array)$history_radios_data;
      $chief_complaints=json_decode($result_edit['chief_complaints']);
      $data['chief_complaints']=(array)$chief_complaints;
      $ophthalmic=json_decode($result_edit['ophthalmic']);     
      $data['ophthalmic']=(array)$ophthalmic;
      $systemic=json_decode($result_edit['systemic']);
      $data['systemic']=(array)$systemic;
      $drug_allergies=json_decode($result_edit['drug_allergies']);
      $data['drug_allergies']=(array)$drug_allergies;
      $contact_allergies=json_decode($result_edit['contact_allergies']);
      $data['contact_allergies']=(array)$contact_allergies;
      $food_allergies=json_decode($result_edit['food_allergies']);
      $data['food_allergies']=(array)$food_allergies;
      //$data['pres_id']=$result_edit['id'];
      $data['pres_id']=$pres_id;
      // Refraction
      $refraction_visual_acuity=json_decode($result_refraction['visual_acuity']); 
      $data['refrtsn_vl_act']=(array)$refraction_visual_acuity;

      $refraction_keratometry=json_decode($result_refraction['keratometry']); 
      $data['refrtsn_kerat']=(array)$refraction_keratometry;

      $refraction_pgp=json_decode($result_refraction['pgp']); 
      $data['refrtsn_pgp']=(array)$refraction_pgp;

      $refraction_auto_refraction=json_decode($result_refraction['auto_refraction']); 
      $data['refrtsn_auto_ref']=(array)$refraction_auto_refraction;

      $refraction_dry_refraction=json_decode($result_refraction['dry_refraction']); 
      $data['refrtsn_dry_ref']=(array)$refraction_dry_refraction;

      $refraction_delated=json_decode($result_refraction['refraction_delated']); 
      $data['refrtsn_dltd']=(array)$refraction_delated;

      $refraction_retinoscopy=json_decode($result_refraction['retinoscopy']); 
      $data['refrtsn_retip']=(array)$refraction_retinoscopy;

      $refraction_pmt=json_decode($result_refraction['pmt']); 
      $data['refrtsn_pmt']=(array)$refraction_pmt;

      $refraction_glass_pres=json_decode($result_refraction['glass_prescription']); 
      $data['refrtsn_glassp']=(array)$refraction_glass_pres;

       $refraction_inter_glass=json_decode($result_refraction['inter_glass_presc']); 
      $data['refrtsn_inter_glass']=(array)$refraction_inter_glass;
      
      $refraction_contact_lens_presc=json_decode($result_refraction['contact_lens_presc']); 
      $data['refrtsn_cntct_lns']=(array)$refraction_contact_lens_presc;

       $refraction_color_vision=json_decode($result_refraction['color_vision']); 
      $data['refrtsn_clr_vsn']=(array)$refraction_color_vision;

      $refraction_contrast_sensivity=json_decode($result_refraction['contrast_sensivity']); 
      $data['refrtsn_const_sen']=(array)$refraction_contrast_sensivity;

       $refraction_intra_pres=json_decode($result_refraction['intraocular_press']); 
      $data['refrtsn_intrap']=(array)$refraction_intra_pres;
      
     
      
      $refraction_orthoptics=json_decode($result_refraction['orthoptics']); 
      $data['refrtsn_orthoptics']=(array)$refraction_orthoptics;
      // examination

      $exam_general=json_decode($result_examination['general_examina']); 
      $data['exam_general']=(array)$exam_general;

      $exam_appearance=json_decode($result_examination['appearance']); 
      $data['exam_aprnc']=(array)$exam_appearance;
      $exam_appendages=json_decode($result_examination['appendages']); 
      $data['exam_apnds']=(array)$exam_appendages;
      $exam_conjuctiva=json_decode($result_examination['conjuctiva']); 
      $data['exam_conjtv']=(array)$exam_conjuctiva;
      $exam_cornea=json_decode($result_examination['cornea']); 
      $data['exam_cornea']=(array)$exam_cornea;
     
      $exam_aterior_chamber=json_decode($result_examination['aterior_chamber']); 
      $data['exam_atrch']=(array)$exam_aterior_chamber;
      $exam_pupil=json_decode($result_examination['pupil']); 
      $data['exam_pupil']=(array)$exam_pupil;
      $exam_iris=json_decode($result_examination['iris']); 
      $data['exam_iris']=(array)$exam_iris;
      $exam_lens=json_decode($result_examination['lens']); 
      $data['exam_lens']=(array)$exam_lens;
      $exam_extrmovs=json_decode($result_examination['extraocular_move_squint']); 
      $data['exam_extrmovs']=(array)$exam_extrmovs;
      $exam_intrprsr=json_decode($result_examination['intraocular_pressure']); 
      $data['exam_intrprsr']=(array)$exam_intrprsr;
      $exam_gonioscopy=json_decode($result_examination['gonioscopy']); 
      $data['exam_gnscp']=(array)$exam_gonioscopy;
      $exam_fundus=json_decode($result_examination['fundus']); 
      $data['exam_fundus']=(array)$exam_fundus;
      $data['history']=array('visit_comm'=> $result_edit['free_test'],
                        'family' => $result_edit['family'],
                        'medical' => $result_edit['medical'],
                        'temperature'=>$result_edit['temperature'],
                        'pulse'=>$result_edit['pulse'],
                        'blood_pressure'=>$result_edit['blood_pressure'],
                        'rr'=>$result_edit['rr'],
                        'height'=>$result_edit['height'],
                        'weight'=>$result_edit['weight'],
                        'bmi'=>$result_edit['bmi'],
                        'comment'=>$result_edit['comment']);
     }
     else
     { 
      $pres_id=1;
      $result_refraction = $this->add_prescript->get_blank_refraction_id($pres_id);
      $result_examination = $this->add_prescript->get_blank_examination_id($pres_id);

      $refraction_visual_acuity=json_decode($result_refraction['visual_acuity']); 
      $data['refraction_visual_acuity']=(array)$refraction_visual_acuity;

      $refraction_keratometry=json_decode($result_refraction['keratometry']); 
      $data['refraction_keratometry']=(array)$refraction_keratometry;

      $refraction_pgp=json_decode($result_refraction['pgp']); 
      $data['refraction_pgp']=(array)$refraction_pgp;

      $refraction_auto_refraction=json_decode($result_refraction['auto_refraction']); 
      $data['refraction_auto_ref']=(array)$refraction_auto_refraction;

      $refraction_dry_refraction=json_decode($result_refraction['dry_refraction']); 
      $data['refraction_dry_ref']=(array)$refraction_dry_refraction;

      $refraction_delated=json_decode($result_refraction['refraction_delated']); 
      $data['refraction_delated']=(array)$refraction_delated;

      $refraction_retinoscopy=json_decode($result_refraction['retinoscopy']); 
      $data['refraction_retinoscopy']=(array)$refraction_retinoscopy;

      $refraction_pmt=json_decode($result_refraction['pmt']); 
      $data['refraction_pmt']=(array)$refraction_pmt;

      $refraction_glass_pres=json_decode($result_refraction['glass_prescription']); 
      $data['refraction_glass_pres']=(array)$refraction_glass_pres;

       $refraction_inter_glass=json_decode($result_refraction['inter_glass_presc']); 
      $data['refraction_inter_glass']=(array)$refraction_inter_glass;
      
      $refraction_contact_lens_presc=json_decode($result_refraction['contact_lens_presc']); 
      $data['refraction_contact_lens']=(array)$refraction_contact_lens_presc;

       $refraction_color_vision=json_decode($result_refraction['color_vision']); 
      $data['refraction_color_vision']=(array)$refraction_color_vision;

      $refraction_contrast_sensivity=json_decode($result_refraction['contrast_sensivity']); 
      $data['refraction_const_sen']=(array)$refraction_glass_pres;

       $refraction_intra_pres=json_decode($result_refraction['intraocular_press']); 
      $data['refraction_intra_pres']=(array)$refraction_intra_pres;
      
      $refraction_orthoptics=json_decode($result_refraction['orthoptics']); 
      $data['refraction_orthoptics']=(array)$refraction_orthoptics;



       $exam_general=json_decode($result_examination['general_examina']); 
      $data['exam_general']=(array)$exam_general;
      $exam_appearance=json_decode($result_examination['appearance']); 
      $data['exam_aprnc']=(array)$exam_appearance;
      $exam_appendages=json_decode($result_examination['appendages']); 
      $data['exam_apnds']=(array)$exam_appendages;
      $exam_conjuctiva=json_decode($result_examination['conjuctiva']); 
      $data['exam_conjtv']=(array)$exam_conjuctiva;
      $exam_cornea=json_decode($result_examination['cornea']); 
      $data['exam_cornea']=(array)$exam_cornea;
      $exam_aterior_chamber=json_decode($result_examination['aterior_chamber']); 
      $data['exam_atrch']=(array)$exam_aterior_chamber;
      $exam_pupil=json_decode($result_examination['pupil']); 
      $data['exam_pupil']=(array)$exam_pupil;
      $exam_iris=json_decode($result_examination['iris']); 
      $data['exam_iris']=(array)$exam_iris;
      $exam_lens=json_decode($result_examination['lens']); 
      $data['exam_lens']=(array)$exam_lens;
      $exam_lens=json_decode($result_examination['lens']); 
      $data['exam_cornea']=(array)$exam_cornea;
      $exam_extrmovs=json_decode($result_examination['extraocular_move_squint']); 
      $data['exam_extrmovs']=(array)$exam_extrmovs;
      $exam_intrprsr=json_decode($result_examination['intraocular_pressure']); 
      $data['exam_intrprsr']=(array)$exam_intrprsr;
      $exam_gonioscopy=json_decode($result_examination['gonioscopy']); 
      $data['exam_gnscp']=(array)$exam_gonioscopy;
      $exam_fundus=json_decode($result_examination['fundus']); 
      $data['exam_fundus']=(array)$exam_fundus;

         /* Diagnosis Tab */
          $diagno_result=$this->add_prescript->get_diagnosis_by_id($booking_id,$pres_id);
          $data['diagno_lists']=$this->add_prescript->get_diagnosis_list($booking_id,$result['branch_id'],$result['patient_id']);       

         /* Diagnosis Tab */


      $data['history_radios_data']=array(
       'general_checkup' => '',
       'special_status' => '');
      $data['chief_complaints']=array(
        'bdv_m' => '',
        'history_chief_blurr_side' => '',
        'history_chief_blurr_dur' => '',
        'history_chief_blurr_unit' => '',
        'history_chief_blurr_comm' => '',
        'history_chief_blurr_dist' => '',
        'history_chief_blurr_near' => '',
        'history_chief_blurr_pain' => '',
        'history_chief_blurr_ug' => '',
        'pain_m' => '',
        'history_chief_pains_side' =>'', 
        'history_chief_pains_dur' => '',
        'history_chief_pains_unit' => '',
        'history_chief_pains_comm' => '',
        'redness_m' => '',
        'history_chief_rednes_side' => '',
        'history_chief_rednes_dur' => '',
        'history_chief_rednes_unit' => '',
        'history_chief_rednes_comm' => '',
        'injury_m' => '',
        'history_chief_injuries_side' => '',
        'history_chief_injuries_dur' => '',
        'history_chief_injuries_unit' => '',
        'history_chief_injuries_comm' => '',
        'water_m' => '',
        'history_chief_waterings_side' => '',
        'history_chief_waterings_dur' => '',
        'history_chief_waterings_unit' => '',
        'history_chief_waterings_comm' => '',
        'discharge_m' => '',
        'history_chief_discharges_side' => '',
        'history_chief_discharges_dur' => '',
        'history_chief_discharges_unit' => '',
        'history_chief_discharges_comm' => '',
        'dryness_m' => '',
        'history_chief_dryness_side' =>'',
        'history_chief_dryness_dur' => '',
        'history_chief_dryness_unit' => '',
        'history_chief_dryness_comm' => '',
        'itch_m' => '',
        'history_chief_itchings_side' => '',
        'history_chief_itchings_dur' => '',
        'history_chief_itchings_unit' => '',
        'history_chief_itchings_comm' => '',
        'fbd_m' => '',
        'history_chief_fbsensation_side' =>'',
        'history_chief_fbsensation_dur' => '',
        'history_chief_fbsensation_unit' => '',
        'history_chief_fbsensation_comm' => '',
        'devs_m' => '',
        'history_chief_dev_squint_side' => '',
        'history_chief_dev_squint_dur' => '',
        'history_chief_dev_squint_unit' => '',
        'history_chief_dev_squint_comm' => '',
        'heads_m' => '',
        'history_chief_head_strain_side' => '',
        'history_chief_head_strain_unit' => '',
        'history_chief_head_strain_comm' => '',
        'canss_m' => '',
        'history_chief_size_shape_side' => '',
        'history_chief_size_shape_dur' => '',
        'history_chief_size_shape_unit' => '',
        'history_chief_size_shape_comm' => '',
        'ovs_m' => '',
        'history_chief_ovs_side' => '',
        'history_chief_ovs_dur' => '',
        'history_chief_ovs_unit' => '',
        'history_chief_ovs_comm' => '',
        'history_chief_ovs_glare' => '',
        'history_chief_ovs_floaters' => '',
        'history_chief_ovs_photophobia' => '',
        'history_chief_ovs_color_halos' => '',
        'history_chief_ovs_metamorphopsia' => '',
        'history_chief_ovs_chromatopsia' => '',
        'history_chief_ovs_dnv' => '',
        'history_chief_ovs_ddv' => '',
        'sdv_m' => '',
        'history_chief_sdiv_side' =>'',
        'history_chief_sdiv_dur' => '',
        'history_chief_sdiv_unit' => '',
        'history_chief_sdiv_comm' => '',
        'doe_m' => '',
        'history_chief_doe_side' => '',
        'history_chief_doe_dur' => '',
        'history_chief_doe_unit' => '',
        'history_chief_doe_comm' => '',
        'swel_m' => '',
        'history_chief_swell_side' => '',
        'history_chief_swell_dur' => '',
        'history_chief_swell_unit' => '',
        'history_chief_swell_comm' => '',
        'burns_m' => '',
        'history_chief_sen_burn_side' => '',
        'history_chief_sen_burn_dur' => '',
        'history_chief_sen_burn_unit' => '',
        'history_chief_sen_burn_comm' => '',
        'history_chief_comm' => '');

      $data['ophthalmic']=array(
      'gla_m' => '',
      'history_ophthalmic_glau_l_dur' => '',
      'history_ophthalmic_glau_l_unit' => '',
      'history_ophthalmic_glau_r_dur' => '',
      'history_ophthalmic_glau_r_unit' => '',
      'history_ophthalmic_glau_comm' => '',
      'reti_m' => '',
      'history_ophthalmic_renti_d_l_dur' => '',
      'history_ophthalmic_renti_d_l_unit' => '',
      'history_ophthalmic_renti_d_r_dur' => '',
      'history_ophthalmic_renti_d_r_unit' => '',
      'history_ophthalmic_renti_d_comm' => '',
      'glass_m' => '',
      'history_ophthalmic_glas_l_dur' => '',
      'history_ophthalmic_glas_l_unit' => '',
      'history_ophthalmic_glas_r_dur' => '',
      'history_ophthalmic_glas_r_unit' => '',
      'history_ophthalmic_glas_comm' => '',
      'eyedi_m' => '',
      'history_ophthalmic_eye_d_l_dur' => '',
      'history_ophthalmic_eye_d_l_unit' => '',
      'history_ophthalmic_eye_d_r_dur' => '',
      'history_ophthalmic_eye_d_r_unit' => '',
      'history_ophthalmic_eye_d_comm' => '',
      'eyesu_m' => '',
      'history_ophthalmic_eye_s_l_dur' => '',
      'history_ophthalmic_eye_s_l_unit' => '',
      'history_ophthalmic_eye_s_r_dur' => '',
      'history_ophthalmic_eye_s_r_unit' => '',
      'history_ophthalmic_eye_s_comm' => '',
      'uve_m' => '',
      'history_ophthalmic_uvei_l_dur' => '',
      'history_ophthalmic_uvei_l_unit' => '',
      'history_ophthalmic_uvei_r_dur' => '',
      'history_ophthalmic_uvei_r_unit' => '',
      'history_ophthalmic_uvei_comm' => '',
      'retil_m' => '',
      'history_ophthalmic_renti_l_l_dur' =>'', 
      'history_ophthalmic_renti_l_l_unit' =>'', 
      'history_ophthalmic_renti_l_r_dur' => '',
      'history_ophthalmic_renti_l_r_unit' => '',
      'history_ophthalmic_renti_l_comm' => '',
      'history_ophthalmic_comm' => '');

      $data['systemic']=array(
        'dia_m' => '',
        'history_chief_blurr_side' => '',
        'history_chief_blurr_dur' => '',
        'history_chief_blurr_unit' => '',
        'history_chief_blurr_comm' => '', 
        'hyper_m' => '',
        'history_chief_pains_side' => '',
        'history_chief_pains_dur' => '',
        'history_chief_pains_unit' => '',
        'history_chief_pains_comm' => '',
        'alcoh_m' => '',
        'history_chief_rednes_side' => '',
        'history_chief_rednes_dur' => '',
        'history_chief_rednes_unit' => '',
        'history_chief_rednes_comm' => '',
        'smok_m' => '',
        'history_chief_injuries_side' => '',
        'history_chief_injuries_dur' => '',
        'history_chief_injuries_unit' => '',
        'history_chief_injuries_comm' => '',
        'card_m' => '',
        'history_chief_waterings_side' => '',
        'history_chief_waterings_dur' => '',
        'history_chief_waterings_unit' => '',
        'history_chief_waterings_comm' => '',
        'steri_m' => '',
        'history_chief_discharges_side' => '',
        'history_chief_discharges_dur' => '',
        'history_chief_discharges_unit' => '',
        'history_chief_discharges_comm' => '',
        'drug_m' => '',
        'history_chief_dryness_side' => '',
        'history_chief_dryness_dur' => '',
        'history_chief_dryness_unit' => '',
        'history_chief_dryness_comm' => '',
        'hiva_m' => '',
        'history_chief_itchings_side' => '',
        'history_chief_itchings_dur' => '',
        'history_chief_itchings_unit' => '',
        'history_chief_itchings_comm' => '',
        'cant_m' => '',
        'history_chief_fbsensation_side' => '',
        'history_chief_fbsensation_dur' => '',
        'history_chief_fbsensation_unit' => '',
        'history_chief_fbsensation_comm' => '',
        'tuber_m' => '',
        'history_chief_dev_squint_side' => '',
        'history_chief_dev_squint_dur' => '',
        'history_chief_dev_squint_unit' => '',
        'history_chief_dev_squint_comm' => '',
        'asth_m' => '',
        'history_chief_head_strain_side' => '',
        'history_chief_head_strain_dur' => '',
        'history_chief_head_strain_unit' => '',
        'history_chief_head_strain_comm' => '',
        'cnsds_m' => '',
        'history_chief_size_shape_side' => '',
        'history_chief_size_shape_dur' => '',
        'history_chief_size_shape_unit' => '',
        'history_chief_size_shape_comm' => '',
        'hypo_m' => '',
        'history_chief_ovs_side' => '',
        'history_chief_ovs_dur' => '',
        'history_chief_ovs_unit' => '',
        'history_chief_ovs_comm' => '',
        'hyperth_m' => '',
        'history_chief_sdiv_side' => '',
        'history_chief_sdiv_dur' => '',
        'history_chief_sdiv_unit' => '',
        'history_chief_sdiv_comm' => '',
        'hepac_m' => '',
        'history_chief_doe_side' => '',
        'history_chief_doe_dur' => '',
        'history_chief_doe_unit' => '',
        'history_chief_doe_comm' => '',
        'acid_m' => '',
        'history_chief_swell_side' => '',
        'history_chief_swell_dur' => '',
        'history_chief_swell_unit' => '',
        'history_chief_swell_comm' => '',
        'oins_m' => '',
        'history_chief_sen_burn_side' => '',
        'history_chief_sen_burn_dur'=> '',
        'history_chief_sen_burn_unit' =>'', 
        'history_chief_sen_burn_comm' => '',
        'oasp_m' => '',
        'acon_m' => '',
        'thd_m' => '',
        'chewt_m' => '');

      $drugs=array(
            'antimi_agen_m' => '',
            'ampic_m' => '',
            'history_drug_antimicrobial_ampici_dur' => '',
            'history_drug_antimicrobial_ampici_unit' => '',
            'history_drug_antimicrobial_ampici_comm' => '',
            'amox_m' => '',
            'history_drug_antimicrobial_amoxi_dur' => '',
            'history_drug_antimicrobial_amoxi_unit' => '',
            'history_drug_antimicrobial_amoxi_comm' => '',
            'ceftr_m' => '',
            'history_drug_antimicrobial_ceftr_dur' => '',
            'history_drug_antimicrobial_ceftr_unit' => '',
            'history_drug_antimicrobial_ceftr_comm' => '',
            'cipro_m' => '',
            'history_drug_antimicrobial_ciprof_dur' => '',
            'history_drug_antimicrobial_ciprof_unit' => '',
            'history_drug_antimicrobial_ciprof_comm' => '',
            'clari_m' => '',
            'history_drug_antimicrobial_clarith_dur' => '',
            'history_drug_antimicrobial_clarith_unit' => '',
            'history_drug_antimicrobial_clarith_comm' => '',
            'cotri_m' => '',
            'history_drug_antimicrobial_cotri_dur' => '',
            'history_drug_antimicrobial_cotri_unit' => '',
            'history_drug_antimicrobial_cotri_comm' => '',
            'etham_m' => '',
            'history_drug_antimicrobial_ethamb_dur' => '',
            'history_drug_antimicrobial_ethamb_unit' => '',
            'history_drug_antimicrobial_ethamb_comm' => '',
            'ison_m'=> '',
            'history_drug_antimicrobial_isoni_dur' => '',
            'history_drug_antimicrobial_isoni_unit' => '',
            'history_drug_antimicrobial_isoni_comm' => '',
            'metro_m' => '',
            'history_drug_antimicrobial_metron_dur' => '',
            'history_drug_antimicrobial_metron_unit' => '',
            'history_drug_antimicrobial_metron_comm' => '',
            'penic_m' => '',
            'history_drug_antimicrobial_penic_dur' => '',
            'history_drug_antimicrobial_penic_unit' => '',
            'history_drug_antimicrobial_penic_comm' => '',
            'rifa_m' => '',
            'history_drug_antimicrobial_rifam_dur' => '',
            'history_drug_antimicrobial_rifam_unit' => '',
            'history_drug_antimicrobial_rifam_comm' => '',
            'strep_m' => '',
            'history_drug_antimicrobial_strept_dur' => '',
            'history_drug_antimicrobial_strept_unit' => '',
            'history_drug_antimicrobial_strept_comm' => '',
            'antif_agen_m' => '',
            'ketoc_m' => '',
            'history_drug_antifungal_ketoco_dur' => '',
            'history_drug_antifungal_ketoco_unit' => '',
            'history_drug_antifungal_ketoco_comm' => '',
            'fluco_m' => '',
            'history_drug_antifungal_flucon_dur' => '',
            'history_drug_antifungal_flucon_unit' => '',
            'history_drug_antifungal_flucon_comm' => '',
            'itrac_m' => '',
            'history_drug_antifungal_itrac_dur' => '',
            'history_drug_antifungal_itrac_unit' => '',
            'history_drug_antifungal_itrac_comm' => '',
            'ant_agen_m' => '',
            'acyclo_m' => '',
            'history_drug_antiviral_acyclo_dur' => '',
            'history_drug_antiviral_acyclo_unit' => '',
            'history_drug_antiviral_acyclo_comm' => '',
            'efavir_m' => '',
            'history_drug_antiviral_efavir_dur' => '',
            'history_drug_antiviral_efavir_unit' => '',
            'history_drug_antiviral_efavir_comm' => '',
            'enfuv_m' => '',
            'history_drug_antiviral_enfuv_dur' => '',
            'history_drug_antiviral_enfuv_unit' => '',
            'history_drug_antiviral_enfuv_comm' => '',
            'nelfin_m' => '',
            'history_drug_antiviral_nelfin_dur' => '',
            'history_drug_antiviral_nelfin_unit' => '',
            'history_drug_antiviral_nelfin_comm' => '',
            'nevira_m' => '',
            'history_drug_antiviral_nevira_dur' => '',
            'history_drug_antiviral_nevira_unit' => '',
            'history_drug_antiviral_nevira_comm' => '',
            'zidov_m' => '',
            'history_drug_antiviral_zidov_dur' => '',
            'history_drug_antiviral_zidov_unit' => '',
            'history_drug_antiviral_zidov_comm' => '',
            'nsaids_m' => '',
            'aspirin_m' => '',
            'history_drug_nsaids_aspirin_dur' => '',
            'history_drug_nsaids_aspirin_unit' => '',
            'history_drug_nsaids_aspirin_comm' => '',
            'paracet_m' => '',
            'history_drug_nsaids_paracet_dur' => '',
            'history_drug_nsaids_paracet_unit' => '',
            'history_drug_nsaids_paracet_comm' => '',
            'ibupro_m' => '',
            'history_drug_nsaids_ibupro_dur' => '',
            'history_drug_nsaids_ibupro_unit' => '',
            'history_drug_nsaids_ibupro_comm' => '',
            'diclo_m' => '',
            'history_drug_nsaids_diclo_dur' => '',
            'history_drug_nsaids_diclo_unit' => '',
            'history_drug_nsaids_diclo_comm' => '',
            'aceclo_mnapro_m' => '',
            'history_drug_nsaids_aceclo_dur' => '',
            'history_drug_nsaids_aceclo_unit' => '',
            'history_drug_nsaids_aceclo_comm' => '',
            'napro_m' => '',
            'history_drug_nsaids_napro_dur' => '',
            'history_drug_nsaids_napro_unit' => '',
            'history_drug_nsaids_napro_comm' => '',
            'eye_drops_m' => '',
            'tropip_m' => '',
            'history_drug_eye_tropicp_dur' => '',
            'history_drug_eye_tropicp_unit' => '',
            'history_drug_eye_tropicp_comm' => '',
            'tropi_m' => '',
            'history_drug_eye_tropica_dur' => '',
            'history_drug_eye_tropica_unit' => '',
            'history_drug_eye_tropica_comm' => '',
            'timolol_m' => '',
            'history_drug_eye_timol_dur' => '',
            'history_drug_eye_timol_unit' => '',
            'history_drug_eye_timol_comm' => '',
            'homide_m' => '',
            'history_drug_eye_homide_dur' => '',
            'history_drug_eye_homide_unit' => '',
            'history_drug_eye_homide_comm' => '',
            'brimo_m' => '',
            'history_drug_eye_brimon_dur' => '',
            'history_drug_eye_brimon_unit' => '',
            'history_drug_eye_brimon_comm' => '',
            'latan_m' => '',
            'history_drug_eye_latan_dur' => '',
            'history_drug_eye_latan_unit' => '',
            'history_drug_eye_latan_comm' => '',
            'travo_m' => '',
            'history_drug_eye_travo_dur' => '',
            'history_drug_eye_travo_unit' => '',
            'history_drug_eye_travo_comm' => '',
            'tobra_m' => '',
            'history_drug_eye_tobra_dur' => '',
            'history_drug_eye_tobra_unit' => '',
            'history_drug_eye_tobra_comm' => '',
            'moxif_m' => '',
            'history_drug_eye_moxif_dur' => '',
            'history_drug_eye_moxif_unit' => '',
            'history_drug_eye_moxif_comm' => '',
            'homat_m' => '',
            'history_drug_eye_homat_dur' => '',
            'history_drug_eye_homat_unit' => '',
            'history_drug_eye_homat_comm' => '',
            'piloc_m' => '',
            'history_drug_eye_piloca_dur' => '',
            'history_drug_eye_piloca_unit' => '',
            'history_drug_eye_piloca_comm' => '',
            'cyclop_m' => '',
            'history_drug_eye_cyclop_dur' => '',
            'history_drug_eye_cyclop_unit' => '',
            'history_drug_eye_cyclop_comm' => '',
            'atrop_m' => '',
            'history_drug_eye_atropi_dur' => '',
            'history_drug_eye_atropi_unit' => '',
            'history_drug_eye_atropi_comm' => '',
            'phenyl_m' => '',
            'history_drug_eye_phenyl_dur' => '',
            'history_drug_eye_phenyl_unit' => '',
            'history_drug_eye_phenyl_comm' => '',
            'tropic_m' => '',
            'history_drug_eye_tropicac_dur' => '',
            'history_drug_eye_tropicac_unit' => '',
            'history_drug_eye_tropicac_comm' => '',
            'parac_m' => '',
            'history_drug_eye_paracain_dur' => '',
            'history_drug_eye_paracain_unit' => '',
            'history_drug_eye_paracain_comm' => '',
            'ciplox_m' => '',
            'history_drug_eye_ciplox_dur' => '',
            'history_drug_eye_ciplox_unit' => '',
            'history_drug_eye_ciplox_comm' =>'' );

            $data['contact_allergies']=array(
                'alco_m' => '',
                'history_contact_alcohol_dur' => '',
                'history_contact_alcohol_unit' => '',
                'history_contact_alcohol_comm' => '',
                'latex_m' => '',
                'history_contact_latex_dur' => '',
                'history_contact_latex_unit' => '',
                'history_contact_latex_comm' => '',
                'betad_m' => '',
                'history_contact_betad_dur' => '',
                'history_contact_betad_unit' => '',
                'history_contact_betad_comm' => '',
                'adhes_m' => '',
                'history_contact_adhes_dur' => '',
                'history_contact_adhes_unit' => '',
                'history_contact_adhes_comm' => '',
                'egad_m' => '',
                'history_contact_tegad_dur' => '',
                'history_contact_tegad_unit' => '',
                'history_contact_tegad_comm' => '',
                'trans_m' => '',
                'history_contact_transp_dur' => '',
                'history_contact_transp_unit' => '',
                'history_contact_transp_comm' => '');

            $data['food_allergies']=array(
                'seaf_m' => '', 
                'history_food_seaf_dur' => '', 
                'history_food_seaf_unit' => '', 
                'history_food_seaf_comm' => '', 
                'corn_m' => '', 
                'history_food_corn_dur' => '', 
                'history_food_corn_unit' => '', 
                'history_food_corn_comm' => '', 
                'egg_m' => '', 
                'history_food_egg_dur' => '', 
                'history_food_egg_unit' => '', 
                'history_food_egg_comm' => '', 
                'milk_m' => '', 
                'history_food_milk_p_dur' => '', 
                'history_food_milk_p_unit' => '', 
                'history_food_milk_p_comm' => '', 
                'pean_m' => '', 
                'history_food_pean_dur' => '', 
                'history_food_pean_unit' => '', 
                'history_food_pean_comm' => '', 
                'shell_m' => '', 
                'history_food_shell_dur' => '', 
                'history_food_shell_unit' => '', 
                'history_food_shell_comm' => '', 
                'soy_m' => '', 
                'history_food_soy_dur' => '', 
                'history_food_soy_unit' => '', 
                'history_food_soy_comm' => '', 
                'lact_m' => '', 
                'history_food_lact_dur' => '', 
                'history_food_lact_unit' => '', 
                'history_food_lact_comm' => '', 
                'mush_m' => '', 
                'history_food_mush_dur' => '', 
                'history_food_mush_unit' => '', 
                'history_food_mush_comm' => '', 
                'history_food_comm' => '');

           $data['history']=array('visit_comm'=>'',
                        'temperature'=>'',
                        'pulse'=>'',
                        'blood_pressure'=>'',
                        'rr'=>'',
                        'height'=>'',
                        'weight'=>'',
                        'bmi'=>'',
                        'comment'=>'');
     }      

     $data['page_title']='Eye Prescription';
     $data['booking_code']=$result['booking_code'];
     $data['booking_date']=$result['booking_date'];
     $data['branch_id']=$result['branch_id'];
     $data['patient_id']=$result['patient_id'];
     $data['booking_id']=$result['id'];
     $data['doctor_list']=$this->add_prescript->get_doctor_list_both($result['branch_id']);


      // unauthorise_permission('351','2108');
       if(!empty($post))
       {
         $this->add_prescript->save();
         $this->session->set_flashdata('success','Prescription successfully added.');
         redirect(base_url('eyes_prescription'));
       }

       if(!empty($pres_id))
      {
          $files_uploaded = $this->add_prescript->get_by_upload_files($booking_id,$pres_id);   
          $eye_file_upload = $this->session->userdata('eye_file_upload'); 
          if(empty($eye_file_upload) && !empty($files_uploaded))
          {
              $eye_file_arr = [];
              foreach($files_uploaded as $files_upload)
              {
                  $eye_file_arr[] = $files_upload;
              }
              $this->session->set_userdata('eye_file_upload', $eye_file_arr); 
          }
          else
          {
              $this->session->set_userdata('eye_file_upload', ''); 
          }
      } 
      // echo "ddd";die;
      $this->load->view('eye/new_add_eye_prescription/prescription',$data);
    }

    public function fill_eye_data($modal,$title)
    {
        $data['page_title'] = $title; 
        $data['var_name'] = $modal; 
        $this->load->view('eye/new_add_eye_prescription/pages/add_refraction',$data);       
    }
    public function fill_eye_data_auto_refraction($modal)
    {
        $data['page_title'] = $modal; 
        $this->load->view('eye/new_add_eye_prescription/pages/auto_refraction_add',$data);       
    }
    public function ajax_list_medicine(){
     $result_medicine = $this->add_prescript->medicine_list_search();  
     $post=$this->input->post();
     if(count($result_medicine)>0 && isset($result_medicine) || !empty($ids))
     {
      foreach($result_medicine as $medicine)
      {
        if(!in_array($medicine->id,$ids))
        {
          if($medicine->stock_quantit>0 || !empty($medicine->stock_quantit))
          {
            $table.='<div class="append_row_opt" data-id="'.$medicine->id.'" data-type="'.$medicine->medicine_unit_2.'" data-qty="'.$medicine->stock_quantit.'">'.$medicine->medicine_name.'('.$medicine->medicine_unit_2.')';
            $table.='</div>';
          }
        }
      }
    }
    
    $output=array('data'=>$table);
    echo json_encode($output);
  }

  public function save_tapper_set()
  {
    $post=$this->input->post();
    $this->add_prescript->save_medicine_freqdata();
    return 1;
  }

    public function ajax_list_referal_doc()
    {
      $post=$this->input->post();
      $referal_doctor=$this->add_prescript->referal_doctor_list($post['branch_id'],$post['doc_name']); 
     $table='';
     if(count($referal_doctor)>0 && isset($referal_doctor) || !empty($ids))
     {
      foreach($referal_doctor as $referal)
      {
          $table.='<div class="append_row_refer" data-type="'.$referal->id.'">'.$referal->doctor_name;
          $table.='</div>';
      }
    }
    else
    {
      $table.='<div class="append_row text-danger">No record found</div>';
    }
    $output=array('data'=>$table);
    echo json_encode($output);
  }
  /* Diagnosis */
  public function diagnosis_hirerachy($id="",$branch_id="",$booking_id="",$patient_id="")
  {

    if(isset($id) && !empty($id) && is_numeric($id))
      {      
       $users_data=$this->session->userdata('auth_users');
        $this->load->model('general/general_model');   
         $result = $this->add_prescript->get_commonly_icd($id);
         $icd_code=$result['icd_id'];
         $icd_name=$result['descriptions'];
       
      if(strlen($icd_code)==6){
     		 $icd_dropdowns=$this->general_model->icd_dropdownlist($icd_code);
     		 $icd_seconddropdowns='';
     	}
     	elseif(strlen($icd_code)==5)
     	{
     		
     		$icd_seconddropdowns=$this->general_model->icd_seconddropdownlist($icd_code,7,5);
     	}
     	elseif(strlen($icd_code)==7)
     	{

     		$icd_code3=substr($icd_code,0,6);
     		$icd_thirddropdowns=$this->general_model->icd_dropdownlist($icd_code3);
     	}
    
         if($icd_dropdowns)
         {
         	//echo "hello";die;
         	$icd_length=strlen($icd_code);
         	$icd_length=$icd_length-1;
         	$icd_id=substr($icd_code,0,$icd_length);

         	$icd_firstdropdowns=$this->general_model->icd_firstdropdownlist($icd_id);
          	$data['icd_dropdowns']=$icd_dropdowns;
          	$data['first_step']=array('first_icd_name'=>$icd_firstdropdowns['descriptions'],'first_icd_code'=>$icd_id);
          	$data['second_step']=array('second_icd_name'=>$icd_name,'second_icd_code'=>$icd_code);
          	 $data['third_step']='';
         }

         elseif($icd_seconddropdowns)
         {
         	//echo "hey";die;
            $data['icd_dropdowns']=$icd_seconddropdowns;
             $data['first_step']=array('first_icd_name'=>$icd_name,'first_icd_code'=>$icd_code);
            $data['second_step']='';
             $data['third_step']='';
         } 
         elseif($icd_thirddropdowns)
         {
         	//echo "hi";die;
         	$icd_length=strlen($icd_code);
         	$icd_length=$icd_length-2;
         	$icd_length2=strlen($icd_code)-1;
         	$icd_id1=substr($icd_code,0,$icd_length);
         	$icd_id2=substr($icd_code,0,$icd_length2);
         	
         	$icd_firstdropdowns=$this->general_model->icd_firstdropdownlist($icd_id1);         	
         	$icd_secdropdowns=$this->general_model->icd_firstdropdownlist($icd_id2);
            $data['icd_dropdowns']=$icd_thirddropdowns;
             $data['first_step']=array('first_icd_name'=>$icd_firstdropdowns['descriptions'],'first_icd_code'=>$icd_id1);
            $data['second_step']=array('second_icd_name'=>$icd_secdropdowns['descriptions'],'second_icd_code'=>$icd_id2);;
            $data['third_step']=array('third_icd_name'=>$icd_name,'third_icd_code'=>$icd_code);
         } 
       // print_r($data['first_step']);die;
        $book_data=$this->add_prescript->get_new_data_by_id($booking_id);
        $data['page_title'] = $result['descriptions'];  
        $data['branch_id'] = $branch_id;
        $data['booking_id'] = $booking_id;
        $data['patient_id'] = $patient_id;
        $post = $this->input->post();
        $data['icd_detail']=array(
        					          'is_code'=>1,
        					          'data_type'=>1,
                            'icd_code'=>$result['icd_id'],
                            'attended_doctor'=>$book_data['attended_doctor'],
                            'globe_icd'=>substr($result['icd_id'],0,3),
                            'entry_date'=>date('d/m/Y |h:i A'),
                            'icd_name'=>$result['descriptions'],
                            'done_by'=>$users_data['username'],
                            'user_id'=>$user_data['id']
                             );
        
 
       $this->load->view('eye/new_add_eye_prescription/pages/diagnosis_hirerachy',$data);       
      }
  }

  public function custom_hirerachy($id="",$branch_id="",$booking_id="",$patient_id="")
  {

    if(isset($id) && !empty($id) && is_numeric($id))
    {  

     $users_data=$this->session->userdata('auth_users');
     $this->load->model('general/general_model');   
     $result = $this->add_prescript->get_custom_icd($id);
     if($result['custom_type']==1)
     {
	      $new_icd=json_decode($result['new_icd']);
	      $icd_code=$new_icd->icd_code;
	      $icd_name=$new_icd->icd_name;
	      $is_code=0;
     }
      if($result['custom_type']==2)
     {
      $attached_icd=json_decode($result['attached_icd']);

      $icd_code=$attached_icd->attach_icd_code;
      $icd_name=$attached_icd->attached_diagnosis;
      $is_code=1;
      
     }
     	if(strlen($icd_code)==5)
     	{
     		$icd_seconddropdowns=$this->general_model->icd_dropdownlist($icd_code);
     	}
     	elseif(strlen($icd_code)==6){
     		$icd_code2=substr($icd_code,0,5);
     		 $icd_dropdowns=$this->general_model->icd_seconddropdownlist($icd_code2,7,5);
     		 $icd_seconddropdowns='';
     	}
     	
     	elseif(strlen($icd_code)==7)
     	{

     		$icd_code3=substr($icd_code,0,6);
     		$icd_thirddropdowns=$this->general_model->icd_dropdownlist($icd_code3);
     	}
    
         if($icd_dropdowns)
         {
         	$icd_length=strlen($icd_code);
         	$icd_length=$icd_length-1;
         	$icd_id=substr($icd_code,0,$icd_length);

         	$icd_firstdropdowns=$this->general_model->icd_firstdropdownlist($icd_id);
          	$data['icd_dropdowns']=$icd_dropdowns;
          	$data['first_step']=array('first_icd_name'=>$icd_firstdropdowns['descriptions'],'first_icd_code'=>$icd_id);
          	$data['second_step']=array('second_icd_name'=>$icd_name,'second_icd_code'=>$icd_code);
          	 $data['third_step']='';
         }

         elseif($icd_seconddropdowns)
         {
            $data['icd_dropdowns']=$icd_seconddropdowns;
             $data['first_step']=array('first_icd_name'=>$icd_name,'first_icd_code'=>$icd_code);
            $data['second_step']='';
             $data['third_step']='';
         } 
         elseif($icd_thirddropdowns)
         {
         	$icd_length=strlen($icd_code);
         	$icd_length=$icd_length-2;
         	$icd_length2=strlen($icd_code)-1;
         	$icd_id1=substr($icd_code,0,$icd_length);
         	$icd_id2=substr($icd_code,0,$icd_length2);
         	
         	$icd_firstdropdowns=$this->general_model->icd_firstdropdownlist($icd_id1);         	
         	$icd_secdropdowns=$this->general_model->icd_firstdropdownlist($icd_id2);
            $data['icd_dropdowns']=$icd_thirddropdowns;
             $data['first_step']=array('first_icd_name'=>$icd_firstdropdowns['descriptions'],'first_icd_code'=>$icd_id1);
            $data['second_step']=array('second_icd_name'=>$icd_secdropdowns['descriptions'],'second_icd_code'=>$icd_id2);;
            $data['third_step']=array('third_icd_name'=>$icd_name,'third_icd_code'=>$icd_code);
         }  
     
     $data['page_title'] = $icd_name; 
     $data['branch_id'] = $branch_id;
     $data['booking_id'] = $booking_id;
     $data['patient_id'] = $patient_id; 
     $post = $this->input->post();
     $data['icd_detail']=array(
      'is_code'=>$is_code,
      'data_type'=>2,
      'icd_code'=>$icd_code,
      'globe_icd'=>$icd_code,
      'entry_date'=>date('d/m/Y |h:i A'),
      'icd_name'=>$icd_name,
      'eyes_tps'=>$result['eye_type'],
      'done_by'=>$users_data['username'],
      'user_id'=>$user_data['id']
    );
    $this->load->view('eye/new_add_eye_prescription/pages/diagnosis_hirerachy',$data);       
  }
}

public function save_commonly_custom()
{
  $post=$this->input->post();
  $save=$this->add_prescript->save_commonly_custom();
  $save_id=$this->db->insert_id();
  if($post['data_id']!="")
  {
    $id=$post['data_id'];
  }
  else{
    $id=$save_id;
  }
  $post_data=array
          (
              'icd_id' =>$id,
              'eye_side_name' =>$post['eye_side_name'],
              'diagnosis_comment' => $post['diagnosis_comment'],
              'user_comment'=>$post['user_comment'],
              'icd_code' =>$post['icd_code'],
              'is_code'=>$post['is_code'],
              'entered_by'=>$post['entered_by'],
              'entry_date'=>$post['entry_date']
          );
          
   echo json_encode($post_data);

}

 public function edit_hirerachy($id="",$branch_id="",$booking_id="",$patient_id="")
  {

    if(isset($id) && !empty($id) && is_numeric($id))
    {  

     $users_data=$this->session->userdata('auth_users');
     $this->load->model('general/general_model');   
     $result = $this->add_prescript->get_diagnosis_by_id($id);
      $icd_code=$result['icd_code'];
      $icd_name=$result['eye_side_name'];
   
      if(strlen($icd_code)==6){
               $icd_code2=substr($icd_code,0,5); 
         $icd_dropdowns=$this->general_model->icd_seconddropdownlist($icd_code2,7,5);
         $icd_seconddropdowns='';
      }
     
      elseif(strlen($icd_code)==7)
      {
        
        $icd_code3=substr($icd_code,0,6);
        $icd_thirddropdowns=$this->general_model->icd_dropdownlist($icd_code3);
      }
        
         if($icd_dropdowns && $result['is_code']==1)
         {

          $icd_length=strlen($icd_code);
          $icd_length=$icd_length-1;
          $icd_id=substr($icd_code,0,$icd_length);

          $icd_firstdropdowns=$this->general_model->icd_firstdropdownlist($icd_id);
            $data['icd_dropdowns']=$icd_dropdowns;
            $data['first_step']=array('first_icd_name'=>$icd_firstdropdowns['descriptions'],'first_icd_code'=>$icd_id);
            $data['second_step']=array('second_icd_name'=>$icd_name,'second_icd_code'=>$icd_code);
             $data['third_step']='';
         }

        
         elseif($icd_thirddropdowns && $result['is_code']==1)
         {
          
          $icd_length=strlen($icd_code);
          $icd_length=$icd_length-2;
          $icd_length2=strlen($icd_code)-1;
          $icd_id1=substr($icd_code,0,$icd_length);
          $icd_id2=substr($icd_code,0,$icd_length2);
          
          $icd_firstdropdowns=$this->general_model->icd_firstdropdownlist($icd_id1);          
          $icd_secdropdowns=$this->general_model->icd_firstdropdownlist($icd_id2);
            $data['icd_dropdowns']=$icd_thirddropdowns;
             $data['first_step']=array('first_icd_name'=>$icd_firstdropdowns['descriptions'],'first_icd_code'=>$icd_id1);
            $data['second_step']=array('second_icd_name'=>$icd_secdropdowns['descriptions'],'second_icd_code'=>$icd_id2);;
            $data['third_step']=array('third_icd_name'=>$icd_name,'third_icd_code'=>$icd_code);
         }
         elseif($result['is_code']==0)
         {
          $data['first_step']=array('first_icd_name'=>$result['eye_icd_name'],'first_icd_code'=>$result['icd_code']);
          $data['second_step']='';
          $data['third_step']='';
         }
        $data['page_title'] = $result['icd_name']; 
        $data['branch_id'] = $branch_id;
        $data['booking_id'] = $booking_id;
        $data['patient_id'] = $patient_id; 
        $entry_date=date('d/m/Y |h:i A',strtotime($result['created_date'])); 
        $post = $this->input->post();
        $data['icd_detail']=array(
                            'data_id'=>$id,
                            'is_code'=>$result['is_code'],
                            'data_type'=>$result['data_type'],
                            'icd_code'=>$result['icd_code'],
                            'done_by'=>$result['entered_by'],
                            'entry_date'=>$entry_date,
                            'diagnosis_comment'=>$result['diagnosis_comment'],
                            'user_comment'=>$result['user_comment'],
                            'icd_name'=>$result['icd_name'],
                            'eyes_tps'=>$result['eye_type'],
                            'eye_side_name'=>$result['eye_side_name'],
                            'user_id'=>$user_data['id']
                             );
     if(isset($post) && !empty($post))
     { 
        $this->add_prescript->save_commonly_custom();
        // echo 1;
        // return false;
    }
    $this->load->view('eye/new_add_eye_prescription/pages/edit_hierarchy',$data);       
  }
}

 public function search_hierarchy($id="",$custom_type="",$branch_id="",$booking_id="",$patient_id="")
  {

    if(isset($id) && !empty($id) && is_numeric($id))
      {      
       $users_data=$this->session->userdata('auth_users');
        $this->load->model('general/general_model');   
        if($custom_type==1)
        {
        	$result = $this->add_prescript->get_commonly_icd($id);	
        	$icd_code=$result['icd_id'];
            $icd_name=$result['descriptions'];
        }
        elseif($custom_type==2)
        {
        	  $result = $this->add_prescript->get_custom_icd($id);
		     if($result['custom_type']==1)
		     {
			      $new_icd=json_decode($result['new_icd']);
			      $icd_code=$new_icd->icd_code;
			      $icd_name=$new_icd->icd_name;
			      $is_code=0;
		     }
		      elseif($result['custom_type']==2)
		     {
		      $attached_icd=json_decode($result['attached_icd']);

		      $icd_code=$attached_icd->attach_icd_code;
		      $icd_name=$attached_icd->attached_diagnosis;
		      $is_code=1;
		      
		     }	
        }
         
      
         	if(strlen($icd_code)==5)
     	{
     		$icd_seconddropdowns=$this->general_model->icd_dropdownlist($icd_code);
     	}
     	elseif(strlen($icd_code)==6){
     		$icd_code2=substr($icd_code,0,5);
     		 $icd_dropdowns=$this->general_model->icd_seconddropdownlist($icd_code2,7,5);
     		 $icd_seconddropdowns='';
     	}
     	
     	elseif(strlen($icd_code)==7)
     	{

     		$icd_code3=substr($icd_code,0,6);
     		$icd_thirddropdowns=$this->general_model->icd_dropdownlist($icd_code3);
     	}
    
         if($icd_dropdowns)
         {
         	$icd_length=strlen($icd_code);
         	$icd_length=$icd_length-1;
         	$icd_id=substr($icd_code,0,$icd_length);

         	$icd_firstdropdowns=$this->general_model->icd_firstdropdownlist($icd_id);
          	$data['icd_dropdowns']=$icd_dropdowns;
          	$data['first_step']=array('first_icd_name'=>$icd_firstdropdowns['descriptions'],'first_icd_code'=>$icd_id);
          	$data['second_step']=array('second_icd_name'=>$icd_name,'second_icd_code'=>$icd_code);
          	 $data['third_step']='';
         }

         elseif($icd_seconddropdowns)
         {
            $data['icd_dropdowns']=$icd_seconddropdowns;
             $data['first_step']=array('first_icd_name'=>$icd_name,'first_icd_code'=>$icd_code);
            $data['second_step']='';
             $data['third_step']='';
         } 
         elseif($icd_thirddropdowns)
         {
         	$icd_length=strlen($icd_code);
         	$icd_length=$icd_length-2;
         	$icd_length2=strlen($icd_code)-1;
         	$icd_id1=substr($icd_code,0,$icd_length);
         	$icd_id2=substr($icd_code,0,$icd_length2);
         	
         	$icd_firstdropdowns=$this->general_model->icd_firstdropdownlist($icd_id1);         	
         	$icd_secdropdowns=$this->general_model->icd_firstdropdownlist($icd_id2);
            $data['icd_dropdowns']=$icd_thirddropdowns;
             $data['first_step']=array('first_icd_name'=>$icd_firstdropdowns['descriptions'],'first_icd_code'=>$icd_id1);
            $data['second_step']=array('second_icd_name'=>$icd_secdropdowns['descriptions'],'second_icd_code'=>$icd_id2);;
            $data['third_step']=array('third_icd_name'=>$icd_name,'third_icd_code'=>$icd_code);
         }  
         
         
        $data['branch_id'] = $branch_id;
        $data['booking_id'] = $booking_id;
        $data['patient_id'] = $patient_id;
        $post = $this->input->post();

        if($custom_type==1)
        {
        	 $data['page_title'] = $result['descriptions'];
        	$data['icd_detail']=array(
        		'is_code'=>1,
        		'data_type'=>1,
        		'icd_code'=>$result['icd_id'],
        		'globe_icd'=>substr($result['icd_id'],0,3),
        		'entry_date'=>date('d/m/Y |h:i A'),
        		'icd_name'=>$result['descriptions'],
        		'done_by'=>$users_data['username'],
        		'user_id'=>$user_data['id']
        	);
        }
       
        elseif($custom_type==2)
        {
        	 $data['page_title'] = $icd_name;
        	$data['icd_detail']=array(
        		'is_code'=>$is_code,
        		'data_type'=>2,
        		'icd_code'=>$icd_code,
        		'globe_icd'=>$icd_code,
        		'entry_date'=>date('d/m/Y |h:i A'),
        		'icd_name'=>$icd_name,
        		'done_by'=>$users_data['username'],
        		'user_id'=>$user_data['id']
        	);
        }
        
    
      	$this->load->view('eye/new_add_eye_prescription/pages/diagnosis_hirerachy',$data);

      
              
      }
  }

public function delete_hierarchy($id="")
{
  $this->db->delete('hms_std_eye_prescription_diagnosis_data',array('id'=>$id));
 
}

public function search_diagno_Lists()
 {
  $keyword=$this->uri->segment(4);
  $custom_type=$this->uri->segment(5);
  if($custom_type==1)
  {
  	$icd_eye_section_code=$this->add_prescript->icd_diagnosis_list($keyword);
  	if(!empty($icd_eye_section_code))
  	{
	    foreach($icd_eye_section_code as $section_code)
	    {
	     $options .= '<div class="append_row_opt" data-id="'.$section_code['id'].'" data-type="'.$section_code['icd_id'].'">'.ucwords(strtolower($section_code['descriptions'])).'</div>';
	   	} 
 	} 
  }
  elseif($custom_type==2)
  {
  	$icd_eye_section_codes=$this->add_prescript->icd_custom_diagnosis_list($keyword);
  	foreach($icd_eye_section_codes as $icd_eye_section)
  	{
  		if($icd_eye_section['custom_type']==1)
		     {
			      $new_icd=json_decode($icd_eye_section['new_icd']);
			      $icd_code=$new_icd->icd_code;
			      $icd_name=$new_icd->icd_name;
			      $is_code=0;
		     }
		      elseif($icd_eye_section['custom_type']==2)
		     {
		      $attached_icd=json_decode($icd_eye_section['attached_icd']);

		      $icd_code=$attached_icd->attach_icd_code;
		      $icd_name=$attached_icd->attach_icd_name;
		      $is_code=1;
		      
		     }	

		     $options .= '<div class="append_row_opt" data-id="'.$icd_eye_section['id'].'" data-type="'.$icd_code.'">'.ucwords(strtolower($icd_name)).'</div>';     
  	}

  }
  echo $options;
}
 /* Diagnosis */
  public function save_advice_template()
  {
   $post=$this->input->post();
    $this->add_prescript->save_advice_data();
   // return 1;
  }
  public function get_advice_by_id()
  {
  	 $post=$this->input->post();
  	 $result=$this->add_prescript->get_advice_sets($post['branch_id'],$post['set_id']);
  	 echo json_encode($result);
  }

  /* Search procedure */

     public function procedure_eye_test_search($keyword="")
    {
      if($keyword)
      {
         $options = "";
          $this->load->model('general/general_model'); 
         $test_head_list = $this->add_prescript->procedure_test_list($keyword);
         if(!empty($test_head_list))
         {
            foreach($test_head_list as $test_head)
            {
            	// $options .= '<div class="append_row_opt" data-id="'.$test_head->id.'" >'.ucwords(strtolower($test_head->test_name)).'</div>'; 
               $options .= '<div class="append_row_opt" data-toggle="modal" data-target="#eye_region_modal" data-id="'.$test_head->id.'" data-type="'.$test_head->test_name.'" value="'.$test_head->id.'">'.ucwords(strtolower($test_head->test_name)).'</div>';
            } 
         }
         echo $options;
      }
    }
  /* Search procedure */

 /* delete eye prescription */
    public function delete_eye_prescription($pres_id="")
    {
     $result= $this->add_prescript->delete_eye_prescription($pres_id);
     $response = "Eye Prescription successfully deleted.";
           echo $response;
    }
  /* delete eye prescription */

  /* view eye prescription*/
  public function view_prescription($pres_id,$booking_id='')
  { 
      $this->load->model('eye_prescription/prescription_model','prescription');
      $this->load->library('m_pdf');
        
        $form_data= $this->prescription->get_by_ids($pres_id); 
        $data['form_data'] = $form_data;
        $data['page_title'] = $data['form_data']['patient_name']." Prescription";
     
      $pres_result = $this->add_prescript->get_prescription_by_id($booking_id,$pres_id);
      
      $result_edit = $this->add_prescript->get_prescription_new_by_id($booking_id,$pres_id);
      $data['drawing_list'] = $this->add_prescript->get_drawing($booking_id,$pres_id);
      $result_refraction = $this->add_prescript->get_prescription_refraction_new_by_id($booking_id,$pres_id);
      
      $result_examination = $this->add_prescript->get_prescription_examination_id($booking_id,$pres_id);
      /* advice */
      $result_advice=$this->add_prescript->get_advice_by_id($booking_id,$pres_id);
       $users_data = $this->session->userdata('auth_users');
       /*if($users_data['parent_id']=='110')
       {
           //echo $this->db->last_query();
           echo "<pre>"; print_r($result_advice['advice_tab']); exit;
       }*/
      
      $advice_medication=json_decode($result_advice['medication_tab']);
      $data['advice_medication']=(array)$advice_medication;
     // print_r($data['advice_medication']);die();
      $advice_procedure=json_decode($result_advice['proceduces_tab']);
      $data['advice_procedure']=(array)$advice_procedure;
      $advice_referral=json_decode($result_advice['referral_tab']);
      $data['advice_referral']=(array)$advice_referral;
      $advice_advice=json_decode($result_advice['advice_tab']);
      $data['advice_adv']=(array)$advice_advice;
      $advice_comm=json_decode($result_advice['comments']);
      $data['advice_comm']=(array)$advice_comm;
      /* advice end */
       /* Investigation */
      $result_investigation=$this->add_prescript->get_investigation_by_id($booking_id,$pres_id);
      $result_advice=$this->add_prescript->get_advice_by_id($booking_id,$pres_id);
      $ophthal_data=json_decode($result_investigation['ophthal_set']);
      $data['ophthal_data']=(array)$ophthal_data;
      $laboratory_data=json_decode($result_investigation['laboratory_set']);
      $data['laboratory_data']=(array)$laboratory_data;
      $radiology_data=json_decode($result_investigation['radiology_set']);
      $data['radiology_data']=(array)$radiology_data;
      $invest_comment=json_decode($result_investigation['invest_comm']);
      $data['invest_comment']=(array)$invest_comment;
      $data['prescription_id']=$pres_result['id'];
      $data['branch_id']=$pres_result['branch_id'];
      $data['patient_id']=$pres_result['patient_id'];
      $data['booking_id']=$pres_result['booking_id'];
      $data['sale_id']=$pres_result['sale_id'];
      /* investigation */

      /* Diagnosis */
      $diagno_result=$this->add_prescript->get_diagnosis_data_by_id($booking_id,$pres_id);
      $diagnosis_data=json_decode($diagno_result['diagnosis_data']);
      $diagno_lists=(array)$diagnosis_data;
      $data['diagno_lists']=(array)$diagno_lists['icd'];
      $data['provisional_comment']=$diagno_result['provisional_cmnt'];
      $data['provisional_check']=$diagno_result['provisional_check'];
      $data['provisional_date']=$diagno_result['created_date'];

      /* diagnosis */


      $history_radios_data=json_decode($result_edit['history_radios']); 
      $data['history_radios_data']=(array)$history_radios_data;
      $chief_complaints=json_decode($result_edit['chief_complaints']);
      $data['chief_complaints']=(array)$chief_complaints;
      $med_history=json_decode($result_edit['history_mh']); 
      $data['med_history']=(array)$med_history;
      $ophthalmic=json_decode($result_edit['ophthalmic']);     
      $data['ophthalmic']=(array)$ophthalmic;
      $systemic=json_decode($result_edit['systemic']);
      $data['systemic']=(array)$systemic;
      $drug_allergies=json_decode($result_edit['drug_allergies']);
      $data['drug_allergies']=(array)$drug_allergies;
      $contact_allergies=json_decode($result_edit['contact_allergies']);
      $data['contact_allergies']=(array)$contact_allergies;
      $food_allergies=json_decode($result_edit['food_allergies']);
      $data['food_allergies']=(array)$food_allergies;
      //$data['pres_id']=$result_edit['id'];
      $data['pres_id']=$pres_id;
      // Refraction
      $refraction_visual_acuity=json_decode($result_refraction['visual_acuity']); 
      $data['refrtsn_vl_act']=(array)$refraction_visual_acuity;

      $refraction_keratometry=json_decode($result_refraction['keratometry']); 
      $data['refrtsn_kerat']=(array)$refraction_keratometry;

      $refraction_pgp=json_decode($result_refraction['pgp']); 
      $data['refrtsn_pgp']=(array)$refraction_pgp;

      $refraction_auto_refraction=json_decode($result_refraction['auto_refraction']); 
      $data['refrtsn_auto_ref']=(array)$refraction_auto_refraction;

      $refraction_dry_refraction=json_decode($result_refraction['dry_refraction']); 
      $data['refrtsn_dry_ref']=(array)$refraction_dry_refraction;

      $refraction_delated=json_decode($result_refraction['refraction_delated']); 
      $data['refrtsn_dltd']=(array)$refraction_delated;

      $refraction_retinoscopy=json_decode($result_refraction['retinoscopy']); 
      $data['refrtsn_retip']=(array)$refraction_retinoscopy;

      $refraction_pmt=json_decode($result_refraction['pmt']); 
      $data['refrtsn_pmt']=(array)$refraction_pmt;

      $refraction_glass_pres=json_decode($result_refraction['glass_prescription']); 
      $data['refrtsn_glassp']=(array)$refraction_glass_pres;

       $refraction_inter_glass=json_decode($result_refraction['inter_glass_presc']); 
      $data['refrtsn_inter_glass']=(array)$refraction_inter_glass;
      
      $refraction_contact_lens_presc=json_decode($result_refraction['contact_lens_presc']); 
      $data['refrtsn_cntct_lns']=(array)$refraction_contact_lens_presc;

       $refraction_color_vision=json_decode($result_refraction['color_vision']); 
      $data['refrtsn_clr_vsn']=(array)$refraction_color_vision;

      $refraction_contrast_sensivity=json_decode($result_refraction['contrast_sensivity']); 
      $data['refrtsn_const_sen']=(array)$refraction_contrast_sensivity;

       $refraction_intra_pres=json_decode($result_refraction['intraocular_press']); 
      $data['refrtsn_intrap']=(array)$refraction_intra_pres;
      
       //echo "<pre>"; print_r($data['refrtsn_intrap']);
      
      $refraction_orthoptics=json_decode($result_refraction['orthoptics']); 
      $data['refrtsn_orthoptics']=(array)$refraction_orthoptics;
      // examination

      $exam_general=json_decode($result_examination['general_examina']); 
      $data['exam_general']=(array)$exam_general;

      $exam_appearance=json_decode($result_examination['appearance']); 
      $data['exam_aprnc']=(array)$exam_appearance;
      $exam_appendages=json_decode($result_examination['appendages']); 
      $data['exam_apnds']=(array)$exam_appendages;
      $exam_conjuctiva=json_decode($result_examination['conjuctiva']); 
      $data['exam_conjtv']=(array)$exam_conjuctiva;
      $exam_cornea=json_decode($result_examination['cornea']); 
      $data['exam_cornea']=(array)$exam_cornea;
     
      $exam_aterior_chamber=json_decode($result_examination['aterior_chamber']); 
      $data['exam_atrch']=(array)$exam_aterior_chamber;
      $exam_pupil=json_decode($result_examination['pupil']); 
      $data['exam_pupil']=(array)$exam_pupil;
      $exam_iris=json_decode($result_examination['iris']); 
      $data['exam_iris']=(array)$exam_iris;
      $exam_lens=json_decode($result_examination['lens']); 
      $data['exam_lens']=(array)$exam_lens;
      $exam_extrmovs=json_decode($result_examination['extraocular_move_squint']); 
      $data['exam_extrmovs']=(array)$exam_extrmovs;
      $exam_intrprsr=json_decode($result_examination['intraocular_pressure']); 
      $data['exam_intrprsr']=(array)$exam_intrprsr;
      //echo "<pre>"; print_r($data['exam_intrprsr']); exit;
      $exam_gonioscopy=json_decode($result_examination['gonioscopy']); 
      $data['exam_gnscp']=(array)$exam_gonioscopy;
      $exam_fundus=json_decode($result_examination['fundus']); 
      $data['exam_fundus']=(array)$exam_fundus;
      $data['history']=array('visit_comm'=> $result_edit['free_test'],
                        'family' => $result_edit['family'],
                        'medical' => $result_edit['medical'],
                        'medical_history_duration'=>$result_edit['medical_history_duration'],
                        'medical_history_unit'=>$result_edit['medical_history_unit'],
                        'medical_history_comments'=>$result_edit['medical_history_comments'],


                        'temperature'=>$result_edit['temperature'],
                        'pulse'=>$result_edit['pulse'],
                        'blood_pressure'=>$result_edit['blood_pressure'],
                        'rr'=>$result_edit['rr'],
                        'height'=>$result_edit['height'],
                        'weight'=>$result_edit['weight'],
                        'bmi'=>$result_edit['bmi'],
                        'comment'=>$result_edit['comment']);

      /*Header part coding*/


      $result_biometry = $this->add_prescript->get_prescription_biometry_id($booking_id,$pres_id);
      
      //echo "<pre>"; print_r($result_biometry); exit;
      
      //biometry start
      
    $data['biometry_ob_k1_one'] = $result_biometry['biometry_ob_k1_one'];
    $data['biometry_ob_k1_two'] = $result_biometry['biometry_ob_k1_two'];
    $data['biometry_ob_k1_three'] = $result_biometry['biometry_ob_k1_three'];
    $data['biometry_ob_k2_one'] = $result_biometry['biometry_ob_k2_one'];
    $data['biometry_ob_k2_two'] = $result_biometry['biometry_ob_k2_two'];
    $data['biometry_ob_k2_three'] = $result_biometry['biometry_ob_k2_three'];
    $data['biometry_ob_al_one'] = $result_biometry['biometry_ob_al_one'];
    $data['biometry_ob_al_two'] = $result_biometry['biometry_ob_al_two'];
    $data['biometry_ob_al_three'] = $result_biometry['biometry_ob_al_three'];
    $data['biometry_ascan_one'] = $result_biometry['biometry_ascan_one'];
    $data['biometry_ascan_two'] = $result_biometry['biometry_ascan_two'];
    $data['biometry_ascan_three'] = $result_biometry['biometry_ascan_three'];
    $data['biometry_ascan_sec_one'] = $result_biometry['biometry_ascan_sec_one'];
    $data['biometry_ascan_sec_two'] = $result_biometry['biometry_ascan_sec_two'];
    $data['biometry_ascan_sec_three'] = $result_biometry['biometry_ascan_sec_three'];
    $data['biometry_ascan_thr_one'] = $result_biometry['biometry_ascan_thr_one'];
    $data['biometry_ascan_thr_two'] = $result_biometry['biometry_ascan_thr_two'];
    $data['biometry_ascan_thr_three'] = $result_biometry['biometry_ascan_thr_three'];
    $data['biometry_iol_one'] = $result_biometry['biometry_iol_one'];
    
    
    $data['biometry_srk_one'] = $result_biometry['biometry_srk_one'];
    
    $data['biometry_error_one'] = $result_biometry['biometry_error_one'];
    $data['biometry_barett_one'] = $result_biometry['biometry_barett_one'];
    $data['biometry_error_one_two'] = $result_biometry['biometry_error_one_two'];
    $data['biometry_iol_two'] = $result_biometry['biometry_iol_two'];
    $data['biometry_ascan_sec_sec'] = $result_biometry['biometry_ascan_sec_sec'];
    $data['biometry_error_sec'] = $result_biometry['biometry_error_sec'];
    
    $data['biometry_barett_sec'] = $result_biometry['biometry_barett_sec'];
    $data['biometry_error_one_sec'] = $result_biometry['biometry_error_one_sec'];
    $data['biometry_iol_thr'] = $result_biometry['biometry_iol_thr'];
    $data['biometry_ascan_sec_thr'] = $result_biometry['biometry_ascan_sec_thr'];
    $data['biometry_error_thr'] = $result_biometry['biometry_error_thr'];
    $data['biometry_barett_thr'] = $result_biometry['biometry_barett_thr'];
    $data['biometry_error_one_thr'] = $result_biometry['biometry_error_one_thr'];
    $data['biometry_iol_four'] = $result_biometry['biometry_iol_four'];
    $data['biometry_ascan_sec_four'] = $result_biometry['biometry_ascan_sec_four'];
    $data['biometry_error_four'] = $result_biometry['biometry_error_four'];
    $data['biometry_barett_four'] = $result_biometry['biometry_barett_four'];
    $data['biometry_error_one_four'] = $result_biometry['biometry_error_one_four'];
    
    
    $data['biometry_iol_five'] = $result_biometry['biometry_iol_five'];
    $data['biometry_ascan_sec_five'] = $result_biometry['biometry_ascan_sec_five'];
    
    $data['biometry_error_five'] = $result_biometry['biometry_error_five'];
    
    $data['biometry_barett_five'] = $result_biometry['biometry_barett_five'];
    
    $data['biometry_error_one_five'] = $result_biometry['biometry_error_one_five'];
    
    $data['biometry_remarks'] = $result_biometry['biometry_remarks'];


        $age = "";
        if($form_data['age_y']>0)
        {
          $year = 'Years';
          if($form_data['age_y']==1)
          {
            $year = 'Year';
          }
          $age .= $form_data['age_y']." ".$year;
        }
        if($form_data['age_m']>0)
        {
          $month = 'Months';
          if($form_data['age_m']==1)
          {
            $month = 'Month';
          }
          $age .= ", ".$form_data['age_m']." ".$month;
        }
        if($form_data['age_d']>0)
        {
          $day = 'Days';
          if($form_data['age_d']==1)
          {
            $day = 'Day';
          }
          $age .= ", ".$form_data['age_d']." ".$day;
        }
        $appointment_date='-';
        $booking_date='';

        if(!empty($form_data['appointment_date']) && $form_data['appointment_date']!='0000-00-00')
        {
          $appointment_date = date('d-m-Y',strtotime($form_data['appointment_date']));
        }

         if(!empty($form_data['booking_date']) && $form_data['booking_date']!='0000-00-00')
        {
          $booking_date = date('d-m-Y',strtotime($form_data['booking_date']));
        }
       
      
      $print_setting=$this->add_prescript->prescription_html_template('',$pres_result['branch_id']); 
      //echo "<pre>"; print_r($print_setting); exit;
      $data['print_setting']=$print_setting;
      $header_replace_part = $print_setting->page_details;
       $middle_replace_part = $print_setting->page_middle;
      $simulation = get_simulation_name($form_data['simulation_id']);
      
      //$attended_doctor_name = get_doctor_name($form_data['attended_doctor']);
      $attended_doctor_signature = get_attended_doctor_signature($form_data['attended_doctor']);
      
       $attended_doctor_name = $attended_doctor_signature->doctor_name;
       $signature = $attended_doctor_signature->signature;
      $sign_photo='';
      if(!empty($signature) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$signature))
        {
            $sign_img = ROOT_UPLOADS_PATH.'doctor_signature/'.$signature;
            $sign_photo = '<img src="'.$sign_img.'" width="100px" />';
        }
      $header_replace_part = str_replace("{patient_name}",$simulation.' '.$form_data['patient_name'],$header_replace_part); 
      
      $header_replace_part = str_replace("{patient_address}",$form_data['paddress'].' '.$form_data['paddress1'].' '.$form_data['paddress2'],$header_replace_part); 
      
      $header_replace_part = str_replace("{patient_age}",$age,$header_replace_part);
      $header_replace_part = str_replace("{patient_reg_no}",$form_data['patient_code'],$header_replace_part);
      $header_replace_part = str_replace("{app_id}",$form_data['booking_code'],$header_replace_part);
      $header_replace_part = str_replace("{mobile_no}",$form_data['mobile_no'],$header_replace_part);
      $header_replace_part = str_replace("{booking_date}",$booking_date,$header_replace_part);
      $header_replace_part = str_replace("{doctor_name}",'Dr. '.get_doctor_name($form_data['attended_doctor']),$header_replace_part);
      
      
      
      if(!empty($form_data['relation']))
        {
        $rel_simulation = get_simulation_name($form_data['relation_simulation_id']);
        $header_replace_part = str_replace("{parent_relation_type}",$form_data['relation'],$header_replace_part);
        }
        else
        {
         $header_replace_part = str_replace("{parent_relation_type}",'',$header_replace_part);
        }

    if(!empty($form_data['relation_name']))
        {
        $rel_simulation = get_simulation_name($form_data['relation_simulation_id']);
        $header_replace_part = str_replace("{parent_relation_name}",$rel_simulation.' '.$form_data['relation_name'],$header_replace_part);
        }
        else
        {
         $header_replace_part = str_replace("{parent_relation_name}",'',$header_replace_part);
        }
      
      
      
    


     $autho='<section style="text-align: right;">Dr. '.get_doctor_name($form_data['attended_doctor']).'
        <br>'.date('h:i A',strtotime($form_data['created_date'])) .', '. $booking_date.'
      </section>';
       

      /*$this->load->view('eye_prescription/view',$data);
      
      $stylesheet = file_get_contents(ROOT_CSS_PATH.'report_pdf.css'); 
      $this->m_pdf->pdf->WriteHTML($stylesheet,1); */
      $middle_replace=$this->load->view('eye_prescription/view',$data,true);
      
      $middle_replace_part = str_replace("{prescription_data}",$middle_replace,$middle_replace_part);
       //echo $middle_replace_part;die;
      $middle_replace_part = str_replace("{signature_report_data}",$autho,$middle_replace_part);
       $stylesheet = file_get_contents(ROOT_CSS_PATH.'eye_presecription_pdf.css'); 
      $this->m_pdf->pdf->WriteHTML($stylesheet,1); 
       $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 

        $this->m_pdf->pdf->SetHeader($print_setting->page_header.$header_replace_part);
        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->SetFooter($print_setting->page_footer);
        $pdfFilePath = $data['form_data']['patient_name'].'_report.pdf'; 
        $pdfFilePath = DIR_UPLOAD_PATH.'temp/'.$pdfFilePath; 
        $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
        $this->m_pdf->pdf->Output();
    }
    /* view eye prescription*/

    public function get_medicine_sets_data()
    {
      $post=$this->input->post();
      $this->load->model('medicine_sets/medicine_set_model','medicine_set');
      $results=$this->medicine_set->get_medicine_set_data($post['branch_id'],$post['set_id']);
      echo json_encode($results);
    }

  public function tapper_ajax_list()
  {
    $users_data = $this->session->userdata('auth_users');
    $post=$this->input->post();
    $qtyoption = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,20,25,30,35,40,45,50);
    // 
    $tpsdata=$this->add_prescript->get_medicine_set_tapperdata($post['medi_id'], $users_data['parent_id'], $post['pres_id']);
    if(!empty($tpsdata)){ 
      $ij=0;
      $html='';
        foreach ($tpsdata as $tpkey => $tpd) 
        {
         $visuald='d-none';
         
            $selected='';
            foreach ($qtyoption as $opt)
            {
             if($opt==$tpd['day'])
             {
                $selected.='<option value="'.$opt.'" selected>'.$opt.'</option>';
             }
             else
             {
                $selected.='<option value="'.$opt.'">'.$opt.'</option>';
             }
            } 
            
            $ij++;   
          $html.='<tr id="med_freq_row_'.$tpkey.'"><td style="width:unset;"><input type="text" name="tp_data['.$tpkey.'][sn]" style="width:60px;" class="form-contorl" value="'.$ij.'"></td><td><select name="tp_data['.$tpkey.'][wdays]" class="stedt w-60px dayselect" style="width:60px;" onchange="list_wday(this.value,'.$tpkey.')" id="week_day_'.$tpkey.'">'.$selected.'</select><input style="width:70px;" name="tp_data['.$tpkey.'][days]" type="number" value="1" class="form-contorl stedtr '.$visuald.'"  readonly></td><td><input name="tp_data['.$tpkey.'][st_date]" type="text" style="width:85px;" class="form-contorl st_dateo" id="st_dateo_'.$tpkey.'" value="'.$tpd['st_date'].'"></td><td class="stedt"><input name="tp_data['.$tpkey.'][en_date]" style="width:85px;" type="text" class="form-contorl en_dateo" style="width:60px;" id="en_dateo_'.$tpkey.'" value="'.$tpd['en_date'].'"></td><td><input name="tp_data['.$tpkey.'][st_time]" type="text" style="width:60px;" class="form-contorl datepicker3" value="'.$tpd['st_time'].'"></td><td><input name="tp_data['.$tpkey.'][en_time]" style="width:60px;" type="text" class="form-contorl datepicker3" value="'.$tpd['en_time'].'"></td><td><input  style="width:60px;" type="number" name="tp_data['.$tpkey.'][freq]" value="'.$tpd['freq'].'" class="form-contorl w-100px"></td><td><input type="number" style="width:60px;" name="tp_data['.$tpkey.'][intvl]" class="form-contorl" value="'.$tpd['intvl'].'" ></td><td class="del_all del_lst_'.$tpkey.'"><a href="#" onclick="remove_medicine_freq('.$tpkey.')" class="btn-custom"><i class="fa fa-times"></i></a></td> </tr><script>$(".datepicker3").datetimepicker({
          format: "LT"});</script>';
        } 
    } 
     echo json_encode($html);
  }

  function get_plan_managment_by_id()
  {
    $post=$this->input->post();
     $this->load->model('plan_management/Plan_management_model','plan_mgmt');
     $plan_list=$this->plan_mgmt->get_by_id($post['plan_id']);
     echo json_encode($plan_list);
  }

   public function update_eye_status($status='',$booking_id='')
    {
      $result=$this->add_prescript->update_appointment_type($booking_id,$status);
      echo $result;
      return false;       
    }


    public function prescription_file_upload()
    {   
        if(!empty($_FILES['file']))
        {
            $this->load->helper(array('form', 'url'));
            $config['upload_path']   = DIR_UPLOAD_PATH.'eye/prescription/files/';  
            $config['max_size']      = 1000; 
            $config['encrypt_name'] = TRUE;
            $config['allowed_types'] = '*';
            $this->load->library('upload', $config);
            //print_r($config);die;
            if($this->upload->do_upload('file')) 
            {  
              $file_data = $this->upload->data();  
              $eye_file_upload = $this->session->userdata('eye_file_upload');
              //echo count($eye_file_upload);die;
             
              $file_arr = array('orig_name'=>$file_data['orig_name'], 'file_name'=>$file_data['file_name']);   
              $total = count($eye_file_upload); 
              if(!empty($eye_file_upload))
              { // echo '<pre>'; print_r($eye_file_upload);die;
                  $eye_file_upload[] = $file_arr; 
                  $this->session->set_userdata('eye_file_upload', $eye_file_upload);
              }
              else
              {  
                  $eye_file_upload = [];
                  $eye_file_upload[0] = $file_arr;  
                  $this->session->set_userdata('eye_file_upload', $eye_file_upload);
                  $eye_file_upload = $this->session->userdata('eye_file_upload'); 
              }
              //echo "<pre>"; print_r($eye_file_upload);die; 
              $table_data = ''; 
              if(!empty($eye_file_upload))
              {
                  $i=1;
                  foreach($eye_file_upload as $key=>$eye_file)
                  {
                      $filename = "'".$eye_file['file_name']."'";
                      $key = "'".$key."'";
                      $table_data .= '<tr>
                                        <td>'.$i.'</td>
                                        <td><a target="_blank" href="'.ROOT_UPLOADS_PATH.'eye/prescription/files/'.$eye_file['file_name'].'">'.$eye_file['orig_name'].'</td>
                                        <td><button type="button" onclick="remove_pres_file('.$key.','.$filename.')" class="btn-custom"><i class="fa fa-trash"></i> Delete </button></td>
                                      </tr>';
                   $i++;                          
                  } 
              }
              
              $arr = array('status'=>1, 'message'=>'File successfully Uploaded', 'table'=>$table_data);
              echo json_encode($arr, true);
            }
            else
            {
                $error = array('status'=>'2', 'message' => $this->upload->display_errors());
                echo json_encode($error);
            }
            
        } 
        
    }
    
    
    public function remove_upload_files()
    {
        $post = $this->input->post();
        if(!empty($post))
        {
              $eye_file_upload = $this->session->userdata('eye_file_upload');
              unset($eye_file_upload[$post['keys']]);
              unlink(DIR_UPLOAD_PATH.'eye/prescription/files/'.$post['filename']);
              $table_data = '';
              
              if(!empty($eye_file_upload))
              {
                  $i=1;
                  $filename = "'".$eye_file['file_name']."'";
                      $key = "'".$key."'";
                  foreach($eye_file_upload as $key=>$eye_file)
                  {
                      $table_data .= '<tr>
                                        <td>'.$i.'</td>
                                        <td><a target="_blank" href="'.ROOT_UPLOADS_PATH.'eye/prescription/files/'.$eye_file['file_name'].'">'.$eye_file['orig_name'].'</td>
                                        <td><button type="button" onclick="remove_pres_file('.$key.','.$filename.')" class="btn-custom"><i class="fa fa-trash"></i> Delete </button></td>
                                      </tr>';
                   $i++;                          
                  } 
              }
               $this->session->set_userdata('eye_file_upload', $eye_file_upload);
              $arr = array('status'=>1, 'msg'=>'File successfully Deleted', 'data'=>$table_data);
              echo json_encode($arr, true);
        }
    }
    
    public function print_chasma_details($pres_id,$booking_id='')
    { 
      $this->load->model('eye_prescription/prescription_model','prescription');
      $this->load->library('m_pdf');
        
        $form_data= $this->prescription->get_by_ids($pres_id); 
        $data['form_data'] = $form_data;
        $data['page_title'] = $data['form_data']['patient_name']." Prescription";
     
      $pres_result = $this->add_prescript->get_prescription_by_id($booking_id,$pres_id);
      
      $result_edit = $this->add_prescript->get_prescription_new_by_id($booking_id,$pres_id);
      $data['drawing_list'] = $this->add_prescript->get_drawing($booking_id,$pres_id);
      $result_refraction = $this->add_prescript->get_prescription_refraction_new_by_id($booking_id,$pres_id);
      
      $result_examination = $this->add_prescript->get_prescription_examination_id($booking_id,$pres_id);
      /* advice */
      $result_advice=$this->add_prescript->get_advice_by_id($booking_id,$pres_id);
      $advice_medication=json_decode($result_advice['medication_tab']);
      $data['advice_medication']=(array)$advice_medication;
     // print_r($data['advice_medication']);die();
      $advice_procedure=json_decode($result_advice['proceduces_tab']);
      $data['advice_procedure']=(array)$advice_procedure;
      $advice_referral=json_decode($result_advice['referral_tab']);
      $data['advice_referral']=(array)$advice_referral;
      $advice_advice=json_decode($result_advice['advice_tab']);
      $data['advice_adv']=(array)$advice_advice;
      $advice_comm=json_decode($result_advice['comments']);
      $data['advice_comm']=(array)$advice_comm;
      /* advice end */
       /* Investigation */
      $result_investigation=$this->add_prescript->get_investigation_by_id($booking_id,$pres_id);
      $result_advice=$this->add_prescript->get_advice_by_id($booking_id,$pres_id);
      $ophthal_data=json_decode($result_investigation['ophthal_set']);
      $data['ophthal_data']=(array)$ophthal_data;
      $laboratory_data=json_decode($result_investigation['laboratory_set']);
      $data['laboratory_data']=(array)$laboratory_data;
      $radiology_data=json_decode($result_investigation['radiology_set']);
      $data['radiology_data']=(array)$radiology_data;
      $invest_comment=json_decode($result_investigation['invest_comm']);
      $data['invest_comment']=(array)$invest_comment;
      $data['prescription_id']=$pres_result['id'];
      $data['branch_id']=$pres_result['branch_id'];
      $data['patient_id']=$pres_result['patient_id'];
      $data['booking_id']=$pres_result['booking_id'];
      $data['sale_id']=$pres_result['sale_id'];
      /* investigation */

      /* Diagnosis */
      $diagno_result=$this->add_prescript->get_diagnosis_data_by_id($booking_id,$pres_id);
      $diagnosis_data=json_decode($diagno_result['diagnosis_data']);
      $diagno_lists=(array)$diagnosis_data;
      $data['diagno_lists']=(array)$diagno_lists['icd'];
      $data['provisional_comment']=$diagno_result['provisional_cmnt'];
      $data['provisional_check']=$diagno_result['provisional_check'];
      $data['provisional_date']=$diagno_result['created_date'];

      /* diagnosis */


      $history_radios_data=json_decode($result_edit['history_radios']); 
      $data['history_radios_data']=(array)$history_radios_data;
      $chief_complaints=json_decode($result_edit['chief_complaints']);
      $data['chief_complaints']=(array)$chief_complaints;
      $med_history=json_decode($result_edit['history_mh']); 
      $data['med_history']=(array)$med_history;
      $ophthalmic=json_decode($result_edit['ophthalmic']);  
      $data['ophthalmic']=(array)$ophthalmic;
      $systemic=json_decode($result_edit['systemic']);
      $data['systemic']=(array)$systemic;
      $drug_allergies=json_decode($result_edit['drug_allergies']);
      $data['drug_allergies']=(array)$drug_allergies;
      $contact_allergies=json_decode($result_edit['contact_allergies']);
      $data['contact_allergies']=(array)$contact_allergies;
      $food_allergies=json_decode($result_edit['food_allergies']);
      $data['food_allergies']=(array)$food_allergies;
      //$data['pres_id']=$result_edit['id'];
      $data['pres_id']=$pres_id;
      // Refraction
      $refraction_visual_acuity=json_decode($result_refraction['visual_acuity']); 
      $data['refrtsn_vl_act']=(array)$refraction_visual_acuity;

      $refraction_keratometry=json_decode($result_refraction['keratometry']); 
      $data['refrtsn_kerat']=(array)$refraction_keratometry;

      $refraction_pgp=json_decode($result_refraction['pgp']); 
      $data['refrtsn_pgp']=(array)$refraction_pgp;

      $refraction_auto_refraction=json_decode($result_refraction['auto_refraction']); 
      $data['refrtsn_auto_ref']=(array)$refraction_auto_refraction;

      $refraction_dry_refraction=json_decode($result_refraction['dry_refraction']); 
      $data['refrtsn_dry_ref']=(array)$refraction_dry_refraction;

      $refraction_delated=json_decode($result_refraction['refraction_delated']); 
      $data['refrtsn_dltd']=(array)$refraction_delated;

      $refraction_retinoscopy=json_decode($result_refraction['retinoscopy']); 
      $data['refrtsn_retip']=(array)$refraction_retinoscopy;

      $refraction_pmt=json_decode($result_refraction['pmt']); 
      $data['refrtsn_pmt']=(array)$refraction_pmt;

      $refraction_glass_pres=json_decode($result_refraction['glass_prescription']); 
      $data['refrtsn_glassp']=(array)$refraction_glass_pres;

       $refraction_inter_glass=json_decode($result_refraction['inter_glass_presc']); 
      $data['refrtsn_inter_glass']=(array)$refraction_inter_glass;
      
      $refraction_contact_lens_presc=json_decode($result_refraction['contact_lens_presc']); 
      $data['refrtsn_cntct_lns']=(array)$refraction_contact_lens_presc;

       $refraction_color_vision=json_decode($result_refraction['color_vision']); 
      $data['refrtsn_clr_vsn']=(array)$refraction_color_vision;

      $refraction_contrast_sensivity=json_decode($result_refraction['contrast_sensivity']); 
      $data['refrtsn_const_sen']=(array)$refraction_contrast_sensivity;

       $refraction_intra_pres=json_decode($result_refraction['intraocular_press']); 
      $data['refrtsn_intrap']=(array)$refraction_intra_pres;
      
      $refraction_orthoptics=json_decode($result_refraction['orthoptics']); 
      $data['refrtsn_orthoptics']=(array)$refraction_orthoptics;
      // examination

      $exam_general=json_decode($result_examination['general_examina']); 
      $data['exam_general']=(array)$exam_general;

      $exam_appearance=json_decode($result_examination['appearance']); 
      $data['exam_aprnc']=(array)$exam_appearance;
      $exam_appendages=json_decode($result_examination['appendages']); 
      $data['exam_apnds']=(array)$exam_appendages;
      $exam_conjuctiva=json_decode($result_examination['conjuctiva']); 
      $data['exam_conjtv']=(array)$exam_conjuctiva;
      $exam_cornea=json_decode($result_examination['cornea']); 
      $data['exam_cornea']=(array)$exam_cornea;
     
      $exam_aterior_chamber=json_decode($result_examination['aterior_chamber']); 
      $data['exam_atrch']=(array)$exam_aterior_chamber;
      $exam_pupil=json_decode($result_examination['pupil']); 
      $data['exam_pupil']=(array)$exam_pupil;
      $exam_iris=json_decode($result_examination['iris']); 
      $data['exam_iris']=(array)$exam_iris;
      $exam_lens=json_decode($result_examination['lens']); 
      $data['exam_lens']=(array)$exam_lens;
      $exam_extrmovs=json_decode($result_examination['extraocular_move_squint']); 
      $data['exam_extrmovs']=(array)$exam_extrmovs;
      $exam_intrprsr=json_decode($result_examination['intraocular_pressure']); 
      $data['exam_intrprsr']=(array)$exam_intrprsr;
      $exam_gonioscopy=json_decode($result_examination['gonioscopy']); 
      $data['exam_gnscp']=(array)$exam_gonioscopy;
      $exam_fundus=json_decode($result_examination['fundus']); 
      $data['exam_fundus']=(array)$exam_fundus;
      $data['history']=array('visit_comm'=> $result_edit['free_test'],
                        'family' => $result_edit['family'],
                        'medical' => $result_edit['medical'],
                        'medical_history_duration'=>$result_edit['medical_history_duration'],
                        'medical_history_unit'=>$result_edit['medical_history_unit'],
                        'medical_history_comments'=>$result_edit['medical_history_comments'],
                        

                        'temperature'=>$result_edit['temperature'],
                        'pulse'=>$result_edit['pulse'],
                        'blood_pressure'=>$result_edit['blood_pressure'],
                        'rr'=>$result_edit['rr'],
                        'height'=>$result_edit['height'],
                        'weight'=>$result_edit['weight'],
                        'bmi'=>$result_edit['bmi'],
                        'comment'=>$result_edit['comment']);

      /*Header part coding*/

        $age = "";
        if($form_data['age_y']>0)
        {
          $year = 'Years';
          if($form_data['age_y']==1)
          {
            $year = 'Year';
          }
          $age .= $form_data['age_y']." ".$year;
        }
        if($form_data['age_m']>0)
        {
          $month = 'Months';
          if($form_data['age_m']==1)
          {
            $month = 'Month';
          }
          $age .= ", ".$form_data['age_m']." ".$month;
        }
        if($form_data['age_d']>0)
        {
          $day = 'Days';
          if($form_data['age_d']==1)
          {
            $day = 'Day';
          }
          $age .= ", ".$form_data['age_d']." ".$day;
        }
        $appointment_date='-';
        $booking_date='';

        if(!empty($form_data['appointment_date']) && $form_data['appointment_date']!='0000-00-00')
        {
          $appointment_date = date('d-m-Y',strtotime($form_data['appointment_date']));
        }

         if(!empty($form_data['booking_date']) && $form_data['booking_date']!='0000-00-00')
        {
          $booking_date = date('d-m-Y',strtotime($form_data['booking_date']));
        }
       
      
      $print_setting=$this->add_prescript->prescription_html_template('',$pres_result['branch_id']); 
      $data['print_setting']=$print_setting;
      $header_replace_part = $print_setting->page_details;
       $middle_replace_part = $print_setting->page_middle;
      $simulation = get_simulation_name($form_data['simulation_id']);
      
      //$attended_doctor_name = get_doctor_name($form_data['attended_doctor']);
      $attended_doctor_signature = get_attended_doctor_signature($form_data['attended_doctor']);
      
       $attended_doctor_name = $attended_doctor_signature->doctor_name;
       $signature = $attended_doctor_signature->signature;
      $sign_photo='';
      if(!empty($signature) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$signature))
        {
            $sign_img = ROOT_UPLOADS_PATH.'doctor_signature/'.$signature;
            $sign_photo = '<img src="'.$sign_img.'" width="100px" />';
        }
      
      
      $header_replace_part = str_replace("{patient_name}",$simulation.' '.$form_data['patient_name'],$header_replace_part); 
      
      $header_replace_part = str_replace("{patient_address}",$form_data['paddress'].' '.$form_data['paddress1'].' '.$form_data['paddress2'],$header_replace_part); 
      
      $header_replace_part = str_replace("{patient_age}",$age,$header_replace_part);
      $header_replace_part = str_replace("{patient_reg_no}",$form_data['patient_code'],$header_replace_part);
      $header_replace_part = str_replace("{app_id}",$form_data['booking_code'],$header_replace_part);
      $header_replace_part = str_replace("{mobile_no}",$form_data['mobile_no'],$header_replace_part);
      $header_replace_part = str_replace("{booking_date}",$booking_date,$header_replace_part);
      $header_replace_part = str_replace("{doctor_name}",'Dr. '.$attended_doctor_name,$header_replace_part);
    
     if(!empty($form_data['relation']))
        {
        $rel_simulation = get_simulation_name($form_data['relation_simulation_id']);
        $header_replace_part = str_replace("{parent_relation_type}",$form_data['relation'],$header_replace_part);
        }
        else
        {
         $header_replace_part = str_replace("{parent_relation_type}",'',$header_replace_part);
        }

    if(!empty($form_data['relation_name']))
        {
        $rel_simulation = get_simulation_name($form_data['relation_simulation_id']);
        $header_replace_part = str_replace("{parent_relation_name}",$rel_simulation.' '.$form_data['relation_name'],$header_replace_part);
        }
        else
        {
         $header_replace_part = str_replace("{parent_relation_name}",'',$header_replace_part);
        }

     $autho='<section style="text-align: right;">'.$sign_photo.'<br>Dr. '.$attended_doctor_name.'
        <br>'.date('h:i A',strtotime($form_data['booking_time'])) .', '. $booking_date.'
      </section>';
       

      $this->load->view('eye_prescription/chasma_view',$data);
       
      $stylesheet = file_get_contents(ROOT_CSS_PATH.'eye_presecription_pdf.css'); 
      $this->m_pdf->pdf->WriteHTML($stylesheet,1); 
      $middle_replace=$this->load->view('eye_prescription/chasma_view',$data,true);
      // echo $middle_replace;die;
      $middle_replace_part = str_replace("{prescription_data}",$middle_replace,$middle_replace_part);
      $middle_replace_part = str_replace("{signature_report_data}",$autho,$middle_replace_part);
       $stylesheet = file_get_contents(ROOT_CSS_PATH.'eye_presecription_pdf.css'); 
      $this->m_pdf->pdf->WriteHTML($stylesheet,1); 
       $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
        $this->m_pdf->pdf->SetHeader($print_setting->page_header.$header_replace_part);
        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->SetFooter($print_setting->page_footer);
        $pdfFilePath = $data['form_data']['patient_name'].'_report.pdf'; 
        $pdfFilePath = DIR_UPLOAD_PATH.'temp/'.$pdfFilePath; 
        $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
        $this->m_pdf->pdf->Output();
    }
    
    public function ajax_list_attended_doc()
    {
      $post=$this->input->post();
      $referal_doctor=$this->add_prescript->attended_doctor_list($post['branch_id'],$post['doc_name']); 
     $table='';
     if(count($referal_doctor)>0 && isset($referal_doctor) || !empty($ids))
     {
      foreach($referal_doctor as $referal)
      {
          $table.='<div class="append_row_refer" data-type="'.$referal->id.'">'.$referal->doctor_name;
          $table.='</div>';
      }
    }
    else
    {
      $table.='<div class="append_row text-danger">No record found</div>';
    }
    $output=array('data'=>$table);
    echo json_encode($output);
  }

}
?>
