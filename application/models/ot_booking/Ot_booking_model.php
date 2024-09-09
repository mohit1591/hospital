<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Ot_booking_model extends CI_Model
{
    var $table = "hms_operation_booking";
    var $column = [
        "hms_operation_booking.id",
        "hms_operation_booking.ipd_id",
        "hms_patient.patient_name",
        "hms_patient.mobile_no",
        "hms_patient.patient_code",
        "hms_patient.gender",
        "hms_patient.address",
        "hms_patient.father_husband",
        "hms_operation_booking.ot_room_no",
        "hms_operation_booking.operation_name",
        "hms_operation_booking.operation_date",
        "hms_operation_booking.operation_time",
        "hms_operation_booking.referred_by",
        "hms_operation_booking.specialization_id",
        "hms_operation_booking.mode_of_payment",
        "hms_ipd_room_category.room_category",
        "hms_ipd_rooms.room_no",
        "hms_ipd_room_to_bad.bad_no",
        "hms_operation_booking.total_amount",
        "hms_operation_booking.discount_amount",
        "hms_operation_booking.net_amount",
        "hms_operation_booking.paid_amount",
        "hms_operation_booking.balance_amount",
        "hms_operation_booking.created_date",
        "hms_operation_booking.modified_date",
        "hms_operation_booking.created_by",
    ];
    var $order = ["id" => "desc"];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $search = $this->session->userdata("ot_booking_serach");
        $users_data = $this->session->userdata("auth_users");
        $user_data = $this->session->userdata("auth_users");
        $this->db
            ->select("hms_operation_booking.*,hms_ipd_booking.ipd_no,hms_ipd_booking.patient_id,hms_doctors.doctor_name,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_category.room_category as room_type, hms_patient.patient_name as p_name,hms_patient.patient_code as p_code,hms_patient.mobile_no,(CASE WHEN hms_patient.gender=1 THEN  'Male' ELSE 'Female' END ) as gender, concat_ws(' ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address, hms_patient.father_husband,hms_ot_room.room_no as ot_room,hms_specialization.specialization,sim.simulation as father_husband_simulation,hms_ot_pacakge.amount as pack_amount,hms_ot_management.amount as ot_amount,(CASE WHEN hms_operation_booking.op_type=1 THEN hms_ot_management.name ELSE hms_ot_pacakge.name END) as ot_pack_name,

			(CASE WHEN hms_operation_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_operation_booking.   referral_doctor=0 THEN concat('Other ',hms_operation_booking.ref_by_other) ELSE concat('Dr. ',docs.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode
			");

        $this->db->from($this->table);
        $this->db->join(
            "hms_ot_pacakge",
            "hms_ot_pacakge.id=hms_operation_booking.package_id",
            "left"
        );
        $this->db->join(
            "hms_ot_management",
            "hms_ot_management.id=hms_operation_booking.operation_name",
            "left"
        );
        $this->db->join(
            "hms_patient",
            "hms_patient.id=hms_operation_booking.patient_id",
            "left"
        );
        $this->db->join(
            "hms_doctors",
            "hms_doctors.id=hms_operation_booking.doctor_id",
            "left"
        );
        $this->db->join(
            "hms_ipd_booking",
            "hms_ipd_booking.id=hms_operation_booking.ipd_id",
            "left"
        );
        $this->db->join(
            "hms_ipd_rooms",
            "hms_ipd_rooms.id=hms_ipd_booking.room_id",
            "left"
        );
        $this->db->join(
            "hms_ipd_room_to_bad",
            "hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id",
            "left"
        );
        $this->db->join(
            "hms_ipd_room_category",
            "hms_ipd_room_category.id=hms_ipd_booking.room_type_id",
            "left"
        );
        $this->db->join(
            "hms_ot_room",
            "hms_ot_room.id=hms_operation_booking.ot_room_no",
            "left"
        );

        $this->db->join(
            "hms_specialization",
            "hms_specialization.id=hms_operation_booking.specialization_id",
            "left"
        );
        $this->db->join(
            "hms_simulation as sim",
            "sim.id=hms_patient.f_h_simulation",
            "left"
        );

        $this->db->join(
            "hms_hospital",
            "hms_hospital.id = hms_operation_booking.referral_hospital",
            "left"
        );
        $this->db->join(
            "hms_doctors as docs",
            "docs.id = hms_operation_booking.referral_doctor",
            "left"
        );

        $this->db->join(
            "hms_payment_mode",
            "hms_payment_mode.id=hms_operation_booking.mode_of_payment",
            "left"
        );

        $this->db->where("hms_operation_booking.is_deleted", "0");
        if ($users_data["users_role"] == 4) {
            $this->db->where(
                'hms_operation_booking.patient_id = "' .
                    $users_data["parent_id"] .
                    '"'
            );
        } elseif ($users_data["users_role"] == 3) {
            $this->db->where(
                'hms_operation_booking.referral_doctor = "' .
                    $users_data["parent_id"] .
                    '"'
            );
        } else {
            $this->db->where(
                'hms_operation_booking.branch_id = "' .
                    $users_data["parent_id"] .
                    '"'
            );
        }

        $i = 0;

        if (isset($search) && !empty($search)) {
			
            if (isset($search["start_date"]) && !empty($search["start_date"])) {
                $start_date = date("Y-m-d", strtotime($search["start_date"]));
                $this->db->where(
                    'hms_operation_booking.operation_date >= "' .
                        $start_date .
                        '"'
                );
            }

            if (isset($search["end_date"]) && !empty($search["end_date"])) {
                $end_date = date("Y-m-d", strtotime($search["end_date"]));
                $this->db->where(
                    'hms_operation_booking.operation_date <= "' .
                        $end_date .
                        '"'
                );
            }

            if (isset($search["ipd_no"]) && !empty($search["ipd_no"])) {
                $this->db->where(
                    'hms_ipd_booking.ipd_no = "' . $search["ipd_no"] . '"'
                );
            }
            if (
                isset($search["patient_code"]) &&
                !empty($search["patient_code"])
            ) {
                $this->db->where(
                    'hms_patient.patient_code = "' .
                        $search["patient_code"] .
                        '"'
                );
            }
            if (
                isset($search["patient_name"]) &&
                !empty($search["patient_name"])
            ) {
                $this->db->where(
                    'hms_patient.patient_name = "' .
                        $search["patient_name"] .
                        '"'
                );
            }
            if (isset($search["mobile_no"]) && !empty($search["mobile_no"])) {
                $this->db->where(
                    'hms_patient.mobile_no = "' . $search["mobile_no"] . '"'
                );
            }

            if (isset($search["adhar_no"]) && !empty($search["adhar_no"])) {
                $this->db->where(
                    'hms_patient.adhar_no = "' . $search["adhar_no"] . '"'
                );
            }

            if ($search["insurance_type"] != "") {
                $this->db->where(
                    "hms_patient.insurance_type",
                    $search["insurance_type"]
                );
            }

            if (!empty($search["insurance_type_id"])) {
                $this->db->where(
                    "hms_patient.insurance_type_id",
                    $search["insurance_type_id"]
                );
            }

            if (!empty($search["ins_company_id"])) {
                $this->db->where(
                    "hms_patient.ins_company_id",
                    $search["ins_company_id"]
                );
            }

            if (
                isset($search["operation_time"]) &&
                !empty($search["operation_time"])
            ) {
                $this->db->where(
                    'hms_operation_booking.operation_time = "' .
                        $search["operation_time"] .
                        '"'
                );
            }
            if (
                isset($search["operation_date"]) &&
                !empty($search["operation_date"])
            ) {
                $this->db->where(
                    'hms_operation_booking.operation_date = "' .
                        date("Y-m-d", strtotime($search["operation_date"])) .
                        '"'
                );
            }

			if (!empty($search["doctor_id"])) {
                $this->db->where(
                    "hms_doctors.id",
                    $search["doctor_id"]
                );
            }

			if (!empty($search["specialization_id"])) {
                $this->db->where(
                    "hms_specialization.id",
                    $search["specialization_id"]
                );
            }
			if (!empty($search["pacakage_name"])) {
                $this->db->where(
                    "hms_ot_pacakge.id",
                    $search["pacakage_name"]
                );
            }

			if (!empty($search["operation_name"])) {
                $this->db->where(
                    "hms_ot_management.id",
                    $search["operation_name"]
                );
            }
        }

        $emp_ids = "";
        if ($users_data["emp_id"] > 0) {
            if ($users_data["record_access"] == "1") {
                $emp_ids = $users_data["id"];
            }
        } elseif (!empty($get["employee"]) && is_numeric($get["employee"])) {
            $emp_ids = $get["employee"];
        }

        if (isset($emp_ids) && !empty($emp_ids)) {
            $this->db->where(
                "hms_operation_booking.created_by IN (" . $emp_ids . ")"
            );
        }

        foreach (
            $this->column
            as $item // loop column
        ) {
            if ($_POST["search"]["value"]) {
                // if datatable send POST for search
                if ($i === 0) {
                    // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST["search"]["value"]);
                } else {
                    $this->db->or_like($item, $_POST["search"]["value"]);
                }

                if (count($this->column) - 1 == $i) {
                    //last loop+
                    $this->db->group_end();
                } //close bracket
            }
            $column[$i] = $item; // set column array variable to order processing
            $i++;
        }

        if (isset($_POST["order"])) {
            // here order processing
            $this->db->order_by(
                $column[$_POST["order"]["0"]["column"]],
                $_POST["order"]["0"]["dir"]
            );
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function search_report_data()
    {
        $search = $this->session->userdata("ot_booking_serach");
        $users_data = $this->session->userdata("auth_users");
        $this->db
            ->select("hms_operation_booking.*,hms_ipd_booking.ipd_no,hms_ipd_booking.patient_id,hms_doctors.doctor_name,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_category.room_category as room_type, hms_patient.patient_name as p_name,hms_patient.patient_code as p_code,hms_patient.mobile_no,(CASE WHEN hms_patient.gender=1 THEN  'Male' ELSE 'Female' END ) as gender, concat_ws(' ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address, hms_patient.father_husband,hms_ot_room.room_no as ot_room,hms_specialization.specialization,sim.simulation as father_husband_simulation,hms_ot_pacakge.amount as pack_amount,(CASE WHEN hms_operation_booking.op_type=1 THEN hms_ot_management.name ELSE hms_ot_pacakge.name END) as ot_pack_name,

			(CASE WHEN hms_operation_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_operation_booking.   referral_doctor=0 THEN concat('Other ',hms_operation_booking.ref_by_other) ELSE concat('Dr. ',docs.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode
			");

        $this->db->from("hms_operation_booking");

        /*$this->db->join('hms_patient','hms_patient.id=hms_operation_booking.patient_id','left');
        $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_operation_booking.ipd_id','left'); 
         $this->db->join('hms_doctors','hms_doctors.id=hms_operation_booking.doctor_id','left');
         $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id=hms_ipd_booking.room_id','left');
		$this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id','left');
		$this->db->join('hms_ipd_room_category','hms_ipd_room_category.id=hms_ipd_booking.room_type_id','left');*/

        $this->db->join(
            "hms_ot_pacakge",
            "hms_ot_pacakge.id=hms_operation_booking.package_id",
            "left"
        );
        $this->db->join(
            "hms_ot_management",
            "hms_ot_management.id=hms_operation_booking.operation_name",
            "left"
        );
        $this->db->join(
            "hms_patient",
            "hms_patient.id=hms_operation_booking.patient_id",
            "left"
        );
        $this->db->join(
            "hms_doctors",
            "hms_doctors.id=hms_operation_booking.doctor_id",
            "left"
        );
        $this->db->join(
            "hms_ipd_booking",
            "hms_ipd_booking.id=hms_operation_booking.ipd_id",
            "left"
        );
        $this->db->join(
            "hms_ipd_rooms",
            "hms_ipd_rooms.id=hms_ipd_booking.room_id",
            "left"
        );
        $this->db->join(
            "hms_ipd_room_to_bad",
            "hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id",
            "left"
        );
        $this->db->join(
            "hms_ipd_room_category",
            "hms_ipd_room_category.id=hms_ipd_booking.room_type_id",
            "left"
        );
        $this->db->join(
            "hms_ot_room",
            "hms_ot_room.id=hms_operation_booking.ot_room_no",
            "left"
        );

        $this->db->join(
            "hms_specialization",
            "hms_specialization.id=hms_operation_booking.specialization_id",
            "left"
        );
        $this->db->join(
            "hms_simulation as sim",
            "sim.id=hms_patient.f_h_simulation",
            "left"
        );

        $this->db->join(
            "hms_hospital",
            "hms_hospital.id = hms_operation_booking.referral_hospital",
            "left"
        );
        $this->db->join(
            "hms_doctors as docs",
            "docs.id = hms_operation_booking.referral_doctor",
            "left"
        );

        $this->db->join(
            "hms_payment_mode",
            "hms_payment_mode.id=hms_operation_booking.mode_of_payment",
            "left"
        );

        $this->db->where("hms_operation_booking.is_deleted", "0");
        if ($users_data["users_role"] == 4) {
            $this->db->where(
                'hms_operation_booking.patient_id = "' .
                    $users_data["parent_id"] .
                    '"'
            );
        } elseif ($users_data["users_role"] == 3) {
            $this->db->where(
                'hms_operation_booking.referral_doctor = "' .
                    $users_data["parent_id"] .
                    '"'
            );
        } else {
            $this->db->where(
                'hms_operation_booking.branch_id = "' .
                    $users_data["parent_id"] .
                    '"'
            );
        }

        $i = 0;

        if (isset($search) && !empty($search)) {
            if (isset($search["start_date"]) && !empty($search["start_date"])) {
                $start_date = date("Y-m-d", strtotime($search["start_date"]));
                $this->db->where(
                    'hms_operation_booking.operation_date >= "' .
                        $start_date .
                        '"'
                );
            }

            if (isset($search["end_date"]) && !empty($search["end_date"])) {
                $end_date = date("Y-m-d", strtotime($search["end_date"]));
                $this->db->where(
                    'hms_operation_booking.operation_date <= "' .
                        $end_date .
                        '"'
                );
            }

            if (isset($search["ipd_no"]) && !empty($search["ipd_no"])) {
                $this->db->where(
                    'hms_ipd_booking.ipd_no = "' . $search["ipd_no"] . '"'
                );
            }
            if (
                isset($search["patient_code"]) &&
                !empty($search["patient_code"])
            ) {
                $this->db->where(
                    'hms_patient.patient_code = "' .
                        $search["patient_code"] .
                        '"'
                );
            }
            if ($search["insurance_type"] != "") {
                $this->db->where(
                    "hms_patient.insurance_type",
                    $search["insurance_type"]
                );
            }

            if (!empty($search["insurance_type_id"])) {
                $this->db->where(
                    "hms_patient.insurance_type_id",
                    $search["insurance_type_id"]
                );
            }

            if (!empty($search["ins_company_id"])) {
                $this->db->where(
                    "hms_patient.ins_company_id",
                    $search["ins_company_id"]
                );
            }
            if (
                isset($search["patient_name"]) &&
                !empty($search["patient_name"])
            ) {
                $this->db->where(
                    'hms_patient.patient_name = "' .
                        $search["patient_name"] .
                        '"'
                );
            }
            if (isset($search["mobile_no"]) && !empty($search["mobile_no"])) {
                $this->db->where(
                    'hms_patient.mobile_no = "' . $search["mobile_no"] . '"'
                );
            }

            if (isset($search["adhar_no"]) && !empty($search["adhar_no"])) {
                $this->db->where(
                    'hms_patient.adhar_no = "' . $search["adhar_no"] . '"'
                );
            }

            if (
                isset($search["operation_time"]) &&
                !empty($search["operation_time"])
            ) {
                $this->db->where(
                    'hms_operation_booking.operation_time = "' .
                        $search["operation_time"] .
                        '"'
                );
            }
            if (
                isset($search["operation_date"]) &&
                !empty($search["operation_date"])
            ) {
                $this->db->where(
                    'hms_operation_booking.operation_date = "' .
                        date("Y-m-d", strtotime($search["operation_date"])) .
                        '"'
                );
            }
        }

        $emp_ids = "";
        if ($users_data["emp_id"] > 0) {
            if ($users_data["record_access"] == "1") {
                $emp_ids = $users_data["id"];
            }
        } elseif (!empty($get["employee"]) && is_numeric($get["employee"])) {
            $emp_ids = $get["employee"];
        }

        if (isset($emp_ids) && !empty($emp_ids)) {
            $this->db->where(
                "hms_operation_booking.created_by IN (" . $emp_ids . ")"
            );
        }
        //echo $this->db->last_query();die;
        $result = $this->db->get()->result();
        //echo $this->db->last_query();die;
        //print_r($result);die;
        return $result;
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST["length"], $_POST["start"]);
        }
        $query = $this->db->get();
        // echo $this->db->last_query();die;
        return $query->result();
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

    public function get_by_id($id = "", $booking_code = "")
    {
        $this->db->select(
            "hms_operation_booking.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_ot_pacakge.name as package_name,hms_ot_pacakge.amount,hms_ipd_booking.ipd_no,hms_ipd_booking.patient_type,hms_patient.patient_code,hms_patient.simulation_id,hms_patient.patient_name,hms_patient.adhar_no,hms_patient.mobile_no,hms_patient.gender,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_ipd_rooms.room_no,hms_patient.relation_name,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_ipd_room_to_bad.bad_no as bed_no,hms_simulation.simulation ,(CASE WHEN hms_operation_booking.op_type=1 THEN hms_ot_management.name ELSE hms_ot_pacakge.name END) as ot_pack_name,hms_patient.modified_date as patient_modified_date,hms_patient.dob,hms_gardian_relation.relation,hms_countries.country as country_name, hms_state.state as state_name, hms_cities.city as city_name,hms_patient.address as address1,hms_patient.pincode"
        );
        $this->db->from("hms_operation_booking");
        if (!empty($id)) {
            $this->db->where("hms_operation_booking.id", $id);
        }
        if (!empty($booking_code)) {
            $this->db->where(
                "hms_operation_booking.booking_code",
                $booking_code
            );
        }
        $this->db->join(
            "hms_ipd_booking",
            "hms_ipd_booking.id=hms_operation_booking.ipd_id",
            "left"
        );
        $this->db->join(
            "hms_ipd_rooms",
            "hms_ipd_rooms.id=hms_ipd_booking.room_id",
            "left"
        );
        $this->db->join(
            "hms_ipd_room_to_bad",
            "hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id",
            "left"
        );
        $this->db->join(
            "hms_ot_pacakge",
            "hms_ot_pacakge.id=hms_operation_booking.package_id",
            "left"
        );
        $this->db->join(
            "hms_ot_management",
            "hms_ot_management.id=hms_operation_booking.operation_name",
            "left"
        );
        $this->db->join(
            "hms_users",
            "hms_users.id = hms_operation_booking.created_by"
        );
        $this->db->join(
            "hms_employees",
            "hms_employees.id=hms_users.emp_id",
            "left"
        );
        $this->db->join(
            "hms_branch",
            "hms_branch.users_id=hms_users.id",
            "left"
        );

        $this->db->join(
            "hms_patient",
            "hms_patient.id=hms_operation_booking.patient_id",
            "left"
        );
        $this->db->join(
            "hms_countries",
            "hms_countries.id = hms_patient.country_id",
            "left"
        ); // country name
        $this->db->join(
            "hms_state",
            "hms_state.id = hms_patient.state_id",
            "left"
        ); // state name
        $this->db->join(
            "hms_cities",
            "hms_cities.id = hms_patient.city_id",
            "left"
        ); // city name
        $this->db->join(
            "hms_gardian_relation",
            "hms_gardian_relation.id = hms_patient.relation_type",
            "left"
        );
        $this->db->join(
            "hms_simulation",
            "hms_simulation.id = hms_patient.simulation_id",
            "left"
        );
        $this->db->where("hms_operation_booking.is_deleted", "0");
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }
    public function get_patient_by_id($id)
    {
        $this->db->select("hms_patient.*");
        $this->db->from("hms_patient");
        $this->db->where("hms_patient.id", $id);
        //$this->db->where('hms_medicine_vendors.is_deleted','0');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        return $query->row_array();
    }

    public function save()
    {
        $user_data = $this->session->userdata("auth_users");
        $post = $this->input->post();

        //print '<pre>';print_r($post);die;
        $data_patient = [
            //"patient_code"=>$post['patient_reg_code'],
            "patient_name" => $post["name"],
            "simulation_id" => $post["simulation_id"],
            "branch_id" => $user_data["parent_id"],
            "adhar_no" => $post["adhar_no"],
            "relation_type" => $post["relation_type"],
            "relation_name" => $post["relation_name"],
            "relation_simulation_id" => $post["relation_simulation_id"],
            "gender" => $post["gender"],
            "age_y" => $post["age_y"],
            "age_m" => $post["age_m"],
            "age_d" => $post["age_d"],
            "address" => $post["address"],
            "address2" => $post["address_second"],
            "address3" => $post["address_third"],
            "mobile_no" => $post["mobile_no"],
        ];

        if (!empty($post["data_id"]) && $post["data_id"] > 0) {
            // $this->db->where(array('ot_booking_id'=>$post['data_id']));
            // $this->db->delete('hms_ot_booking_pacakge_details');

            // code to update payment modes relations data
            $this->db->where([
                "branch_id" => $user_data["parent_id"],
                "parent_id" => $post["data_id"],
                "type" => 17,
                "section_id" => 17,
            ]);
            $this->db->delete("hms_payment_mode_field_value_acc_section");
            if (!empty($post["field_name"])) {
                $post_field_value_name = $post["field_name"];
                $counter_name = count($post_field_value_name);
                for ($i = 0; $i < $counter_name; $i++) {
                    $data_field_value = [
                        "field_value" => $post["field_name"][$i],
                        "field_id" => $post["field_id"][$i],
                        "type" => 17,
                        "section_id" => 17,
                        "p_mode_id" => $post["payment_mode"],
                        "branch_id" => $user_data["parent_id"],
                        "parent_id" => $post["data_id"],
                        "ip_address" => $_SERVER["REMOTE_ADDR"],
                    ];
                    $this->db->set("created_by", $user_data["id"]);
                    $this->db->set("created_date", date("Y-m-d H:i:s"));
                    $this->db->insert(
                        "hms_payment_mode_field_value_acc_section",
                        $data_field_value
                    );
                }
            }
            // code to update payment mode relation data

            if (isset($post["pacakage_name"]) && $post["op_type"] == 2) {
                $p_hours = $this->get_package_hours($post["pacakage_name"]);
                //$time_n=date('H:i:s',strtotime($post['operation_time']));
                $time_n = date(
                    "H:i:s",
                    strtotime(date("d-m-Y") . " " . $post["operation_time"])
                );
                $time = date(
                    "H:i:s",
                    strtotime($time_n . "+" . $p_hours[0]->hours . " hour")
                );
            }
            if (isset($post["operation_name"]) && $post["op_type"] == 1) {
                $p_hours = $this->get_operation_hours($post["operation_name"]);
                //$time_n=date('H:i:s',strtotime($post['operation_time']));
                $time_n = date(
                    "H:i:s",
                    strtotime(date("d-m-Y") . " " . $post["operation_time"])
                );
                $time = date(
                    "H:i:s",
                    strtotime($time_n . "+" . $p_hours[0]->hours . " hour")
                );
            }

            $data = [
                "branch_id" => $user_data["parent_id"],
                "patient_id" => $post["patient_id"],
                "ipd_id" => $post["ipd_id"],
                "operation_name" => $post["operation_name"],
                "op_type" => $post["op_type"],
                "operation_end_time" => $time,
                "ot_room_no" => $post["operation_room"],
                "package_id" => $post["pacakage_name"],
                "operation_date" => date(
                    "Y-m-d",
                    strtotime($post["operation_date"])
                ),
                "operation_booking_date" => date(
                    "Y-m-d",
                    strtotime($post["operation_booking_date"])
                ),
                //'operation_time'=>$post['operation_time'],
                "operation_time" => date(
                    "H:i:s",
                    strtotime(date("d-m-Y") . " " . $post["operation_time"])
                ),
                "remarks" => $post["remarks"],
                "referred_by" => $post["referred_by"],
                "referral_doctor" => $post["referral_doctor"],
                "referral_hospital" => $post["referral_hospital"],
                "ref_by_other" => $post["ref_by_other"],
                "ip_address" => $_SERVER["REMOTE_ADDR"],
                "operated_eye" => $post["operated_eye"],
                "mode_of_payment" => $post["payment_mode"],
                "balance_amount" => $post["balance"],
                "net_amount" => $post["net_amount"],
                "total_amount" => $post["total_amount"],
                "paid_amount" => $post["paid_amount"],
                "discount_amount" => $post["discount"],
                "discount_percent" => $post["discount_percent"],
            ];
            //print_r($data);die;
            $this->db->set("modified_by", $user_data["id"]);
            $this->db->set("modified_date", date("Y-m-d H:i:s"));
            $this->db->where("id", $post["patient_id"]);
            $this->db->update("hms_patient", $data_patient);

            $this->db->where(["operation_id" => $post["data_id"]]);
            $this->db->delete("hms_operation_to_doctors");
            $this->db->set("modified_by", $user_data["id"]);
            $this->db->set("modified_date", date("Y-m-d H:i:s"));
            $this->db->where("id", $post["data_id"]);
            $this->db->update("hms_operation_booking", $data);
            //echo $this->db->last_query();die;

            /**** Code to update data in payment table ***/

            if ($post["ipd_id"] == "" || $post["ipd_id"] == 0) {
                $this->db->where([
                    "branch_id" => $user_data["parent_id"],
                    "parent_id" => $post["data_id"],
                    "section_id" => 8,
                    "patient_id" => $post["patient_id"],
                ]);
                $this->db->where("balance > 0");
                //$this->db->delete('hms_payment');

                /* new code by mamta */
                $this->db->where("patient_id", $post["patient_id"]);
                $query_d_pay = $this->db->get("hms_payment");
                $row_d_pay = $query_d_pay->result();
                if (
                    $post["balance"] == "" ||
                    $post["balance"] == "0" ||
                    $post["balance"] == "0.00"
                ) {
                    $bal = "1.00";
                } else {
                    $bal = $post["balance"];
                }
                // $payment_array=array('parent_id'=>$post['data_id'],
                // 					 'hospital_id'=>$post['referral_hospital'],
                // 					 'doctor_id'=>$post['referral_doctor'],
                // 					 'branch_id'=>$user_data['parent_id'],
                // 					 'section_id'=>8,
                // 					 'patient_id'=>$post['patient_id'],
                // 					 'total_amount'=>$post['total_amount'],
                // 					 'discount_amount'=>$post['discount'],
                // 					 'net_amount'=>$post['net_amount'],
                // 					 'credit'=>$post['net_amount'],
                // 					 'debit'=>$post['paid_amount'],
                // 					 'balance'=>$bal,
                // 					 'status'=>1,
                // 					 'created_by'=>$user_data['id'],
                // 					 'created_date'=>date('Y-m-d H:i:s'),
                // 					);
                // $this->db->insert('hms_payment',$payment_array);

                if (!empty($row_d_pay)) {
                    foreach ($row_d_pay as $row_d) {
                        //print_r($comission_arr);die;
                        $doctor_comission = 0;
                        $hospital_comission = 0;
                        $comission_type = "";
                        $total_comission = 0;
                        if (
                            !empty($post["referral_doctor"]) ||
                            !empty($post["referral_hospital"])
                        ) {
                            $comission_arr = get_doc_hos_comission(
                                $post["referral_doctor"],
                                $post["referral_hospital"],
                                $post["paid_amount"],
                                6
                            );
                            if (!empty($comission_arr)) {
                                $doctor_comission =
                                    $comission_arr["doctor_comission"];
                                $hospital_comission =
                                    $comission_arr["hospital_comission"];
                                $comission_type =
                                    $comission_arr["comission_type"];
                                $total_comission =
                                    $comission_arr["total_comission"];
                            }
                        }

                        $payment_data = [
                            "parent_id" => $post["data_id"],
                            "branch_id" => $user_data["parent_id"],
                            "section_id" => "8",
                            "doctor_id" => $post["referral_doctor"],
                            "hospital_id" => $post["referral_hospital"],
                            "patient_id" => $post["patient_id"],
                            "total_amount" => str_replace(
                                ",",
                                "",
                                $post["total_amount"]
                            ),
                            "discount_amount" => $post["discount"],
                            "net_amount" => str_replace(
                                ",",
                                "",
                                $post["net_amount"]
                            ),
                            "credit" => str_replace(
                                ",",
                                "",
                                $post["net_amount"]
                            ),
                            "debit" => $post["paid_amount"],
                            "pay_mode" => $post["payment_mode"],
                            "balance" => $bal,
                            "doctor_comission" => $doctor_comission,
                            "hospital_comission" => $hospital_comission,
                            "comission_type" => $comission_type,
                            "total_comission" => $total_comission,
                            "paid_amount" => $post["paid_amount"],
                            "created_date" => $row_d->created_date,
                            "created_by" => $user_data["id"],
                        ];

                        $this->db->where("id", $row_d->id);
                        $this->db->update("hms_payment", $payment_data);
                    }
                }
            }
            /* new code by mamta */

            /*** Code to update data in payment table ***/

            /* code for package detail */

            /* code for package detail */

            //pacakage_name as package id

            //if(!empty($post['data_id']) && !empty($post['pacakage_name']) && $post['pacakage_name']!='0.00')
            $p_name = "";
            $p_p_name = "";
            if (!empty($post["data_id"]) && !empty($post["ipd_id"])) {
                $this->db->where("ot_id", $post["data_id"]);
                $this->db->where("ipd_id", $post["ipd_id"]);
                $this->db->where("patient_id", $post["patient_id"]);
                $this->db->delete("hms_ipd_patient_to_charge");

                if (!empty($post["op_type"]) && $post["op_type"] == 1) {
                    $package_charge = get_ot_charge($post["operation_name"]);
                    $amount = $package_charge[0]["amount"];
                    $p_name = $post["operation_name"];
                } else {
                    $package_charge = get_ot_package_charge(
                        $post["pacakage_name"]
                    );
                    $amount = $package_charge[0]["amount"];
                    $p_p_name = $post["pacakage_name"];
                }
                //echo "<pre>";print_r($package_charge); exit;
                $ot_charge = [
                    "branch_id" => $user_data["parent_id"],
                    "ipd_id" => $post["ipd_id"],
                    "patient_id" => $post["patient_id"],
                    "ot_id" => $post["data_id"],
                    "ot_package_id" => $p_p_name,
                    "ot_operation_id" => $p_name,
                    "type" => 6,
                    "quantity" => 1,
                    "start_date" => date(
                        "Y-m-d",
                        strtotime($post["operation_date"])
                    ),
                    "end_date" => date(
                        "Y-m-d",
                        strtotime($post["operation_date"])
                    ),
                    "particular" => $post["operation_name"] . " (OT)",
                    "price" => $amount,
                    "panel_price" => $amount,
                    "net_price" => $amount,
                    "status" => 1,
                    "created_date" => date("Y-m-d H:i:s"),
                ];
                $this->db->insert("hms_ipd_patient_to_charge", $ot_charge);
            }

            $post_doctor = count($post["doctor_names"]);
            foreach ($post["doctor_names"] as $key => $value) {
                $doctor_array = [
                    "operation_id" => $post["data_id"],
                    "doctor_id" => $key,
                    "doctor_name" => $value[0],
                ];
                $this->db->insert("hms_operation_to_doctors", $doctor_array);
            }
            $ot_book_id = $post["data_id"];
        } else {
            $ot_booking_no = generate_unique_id(23);

            // else block for no data id i.e insert code
            $patient_data = $this->get_patient_by_id($post["patient_id"]);

            if (isset($post["pacakage_name"]) && $post["op_type"] == 2) {
                $p_hours = $this->get_package_hours($post["pacakage_name"]);
                //print_r($p_hours);die;
                //$time_n=date('H:i:s',strtotime($post['operation_time']));
                $time_n = date(
                    "H:i:s",
                    strtotime(date("d-m-Y") . " " . $post["operation_time"])
                );
                $time = date(
                    "H:i:s",
                    strtotime($time_n . "+" . $p_hours[0]->hours . " hour")
                );
            }
            if (isset($post["operation_name"]) && $post["op_type"] == 1) {
                $p_hours = $this->get_operation_hours($post["operation_name"]);
                //$time_n=date('H:i:s',strtotime($post['operation_time']));
                $time_n = date(
                    "H:i:s",
                    strtotime(date("d-m-Y") . " " . $post["operation_time"])
                );
                $time = date(
                    "H:i:s",
                    strtotime($time_n . "+" . $p_hours[0]->hours . " hour")
                );
            }

            if (count($patient_data) > 0) {
                $this->db->set("modified_by", $user_data["id"]);
                $this->db->set("modified_date", date("Y-m-d H:i:s"));
                $this->db->where("id", $post["patient_id"]);
                $this->db->update("hms_patient", $data_patient);
                $patient_id = $post["patient_id"];
                if ($post["specialization_id"] == EYE_SPECIALIZATION_ID) {
                    $booking_type = "1";
                } else {
                    $booking_type = 0;
                }
                $data = [
                    "branch_id" => $user_data["parent_id"],
                    "patient_id" => $patient_id,
                    "booking_code" => $ot_booking_no,
                    "op_type" => $post["op_type"],
                    "ipd_id" => $post["ipd_id"],
                    "operation_name" => $post["operation_name"],
                    "package_id" => $post["pacakage_name"],
                    "ot_room_no" => $post["operation_room"],
                    "operation_date" => date(
                        "Y-m-d",
                        strtotime($post["operation_date"])
                    ),
                    "operation_end_time" => $time,
                    "operation_time" => date(
                        "H:i:s",
                        strtotime(date("d-m-Y") . " " . $post["operation_time"])
                    ),
                    "operation_booking_date" => date(
                        "Y-m-d",
                        strtotime($post["operation_booking_date"])
                    ),
                    "remarks" => $post["remarks"],
                    "referred_by" => $post["referred_by"],
                    "referral_doctor" => $post["referral_doctor"],
                    "referral_hospital" => $post["referral_hospital"],
                    "ref_by_other" => $post["ref_by_other"],
                    "ip_address" => $_SERVER["REMOTE_ADDR"],
                    "specialization_id" => $post["specialization_id"],
                    "booking_type" => $booking_type,
                    "operated_eye" => $post["operated_eye"],
                    "mode_of_payment" => $post["payment_mode"],
                    "balance_amount" => $post["balance"],
                    "net_amount" => $post["net_amount"],
                    "total_amount" => $post["total_amount"],
                    "paid_amount" => $post["paid_amount"],
                    "discount_amount" => $post["discount"],
                    "discount_percent" => $post["discount_percent"],
                ];
            } else {
                $patient_reg_code = generate_unique_id(4);
                $this->db->set("created_by", $user_data["id"]);
                $this->db->set("created_date", date("Y-m-d H:i:s"));
                $this->db->set("patient_code", $patient_reg_code);
                $this->db->insert("hms_patient", $data_patient);
                $patient_id = $this->db->insert_id();
                //create user
                $data = [
                    "users_role" => 4,
                    "parent_id" => $patient_id,
                    "username" => "PAT000" . $patient_id,
                    "password" => md5("PASS" . $patient_id),
                    //"email"=>$post['patient_email'],
                    "status" => "1",
                    "ip_address" => $_SERVER["REMOTE_ADDR"],
                    "created_by" => $user_data["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                ];
                $this->db->insert("hms_users", $data);
                $users_id = $this->db->insert_id();
                /*$this->db->select('*');
              $this->db->where('users_role','4');
              $query = $this->db->get('hms_permission_to_role');     
               $permission_list = $query->result();
        if(!empty($permission_list))
        {
          foreach($permission_list as $permission)
          {
            $data = array(
                    'users_role' =>4,
                    'users_id' => $users_id,
                    'master_id' => $patient_id,
                    'section_id' => $permission->section_id,
                    'action_id' => $permission->action_id, 
                    'permission_status' => '1',
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'created_by' =>$user_data['id'],
                    'created_date' =>date('Y-m-d H:i:s'),
                 );
            $this->db->insert('hms_permission_to_users',$data);
          }
        }*/

                ////////// Send SMS /////////////////////
                if (in_array("640", $user_data["permission"]["action"])) {
                    if (!empty($post["mobile_no"])) {
                        send_sms(
                            "patient_registration",
                            18,
                            $post["name"],
                            $post["mobile_no"],
                            [
                                "{patient_name}" => $post["name"],
                                "{username}" => "PAT000" . $patient_id,
                                "{password}" => "PASS" . $patient_id,
                            ]
                        );
                    }
                }
                if ($post["specialization_id"] == EYE_SPECIALIZATION_ID) {
                    $booking_type = "1";
                } else {
                    $booking_type = 0;
                }
                //echo $this->db->last_query();die;
                $data = [
                    "branch_id" => $user_data["parent_id"],
                    "patient_id" => $patient_id,
                    "booking_code" => $ot_booking_no,
                    "ot_room_no" => $post["operation_room"],
                    "op_type" => $post["op_type"],
                    "ipd_id" => $post["ipd_id"],
                    "operation_end_time" => $time,
                    "operation_name" => $post["operation_name"],
                    "package_id" => $post["pacakage_name"],
                    "operation_date" => date(
                        "Y-m-d",
                        strtotime($post["operation_date"])
                    ),
                    "operation_booking_date" => date(
                        "Y-m-d",
                        strtotime($post["operation_booking_date"])
                    ),
                    "operation_time" => date(
                        "H:i:s",
                        strtotime(date("d-m-Y") . " " . $post["operation_time"])
                    ),
                    "remarks" => $post["remarks"],
                    "referred_by" => $post["referred_by"],
                    "referral_doctor" => $post["referral_doctor"],
                    "referral_hospital" => $post["referral_hospital"],
                    "ip_address" => $_SERVER["REMOTE_ADDR"],
                    "specialization_id" => $post["specialization_id"],
                    "booking_type" => $booking_type,
                    "operated_eye" => $post["operated_eye"],
                    "mode_of_payment" => $post["payment_mode"],
                    "balance_amount" => $post["balance"],
                    "net_amount" => $post["net_amount"],
                    "total_amount" => $post["total_amount"],
                    "paid_amount" => $post["paid_amount"],
                    "discount_amount" => $post["discount"],
                    "discount_percent" => $post["discount_percent"],
                ];
            }
            $this->db->set("created_by", $user_data["id"]);
            $this->db->set("created_date", date("Y-m-d H:i:s"));
            $this->db->insert("hms_operation_booking", $data);
            $last_id = $this->db->insert_id();

            /**** Code to insert data in payment table ***/
            if ($post["ipd_id"] == "" || $post["ipd_id"] == 0) {
                if (
                    $post["balance"] == "" ||
                    $post["balance"] == "0" ||
                    $post["balance"] == "0.00"
                ) {
                    $bal = "1.00";
                } else {
                    $bal = $post["balance"];
                }

                $doctor_comission = 0;
                $hospital_comission = 0;
                $comission_type = "";
                $total_comission = 0;
                if (
                    !empty($post["referral_doctor"]) ||
                    !empty($post["referral_hospital"])
                ) {
                    $comission_arr = get_doc_hos_comission(
                        $post["referral_doctor"],
                        $post["referral_hospital"],
                        $post["paid_amount"],
                        6
                    );
                    if (!empty($comission_arr)) {
                        $doctor_comission = $comission_arr["doctor_comission"];
                        $hospital_comission =
                            $comission_arr["hospital_comission"];
                        $comission_type = $comission_arr["comission_type"];
                        $total_comission = $comission_arr["total_comission"];
                    }
                }
                $payment_array = [
                    "parent_id" => $last_id,
                    "hospital_id" => $post["referral_hospital"],
                    "doctor_id" => $post["referral_doctor"],
                    "branch_id" => $user_data["parent_id"],
                    "section_id" => 8,
                    "patient_id" => $patient_id,
                    "total_amount" => $post["total_amount"],
                    "discount_amount" => $post["discount"],
                    "net_amount" => $post["net_amount"],
                    "credit" => $post["net_amount"],
                    "debit" => $post["paid_amount"],
                    "balance" => $bal,
                    "doctor_comission" => $doctor_comission,
                    "hospital_comission" => $hospital_comission,
                    "comission_type" => $comission_type,
                    "total_comission" => $total_comission,
                    "status" => 1,
                    "created_by" => $user_data["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                ];
                $this->db->insert("hms_payment", $payment_array);
                $payment_id = $this->db->insert_id();
            }
            /*** Code to insert data in payment table ***/

            /* genereate receipt number */
            if (in_array("218", $user_data["permission"]["section"])) {
                if ($post["paid_amount"] > 0) {
                    $hospital_receipt_no = check_hospital_receipt_no();
                    $data_receipt_data = [
                        "branch_id" => $user_data["parent_id"],
                        "section_id" => 16,
                        "parent_id" => $last_id,
                        "payment_id" => $payment_id,
                        "reciept_prefix" => $hospital_receipt_no["prefix"],
                        "reciept_suffix" => $hospital_receipt_no["suffix"],
                        "created_by" => $user_data["id"],
                        "created_date" => date("Y-m-d H:i:s"),
                    ];
                    $this->db->insert(
                        "hms_branch_hospital_no",
                        $data_receipt_data
                    );
                }
            }

            /*** code to insert relational data for mode of payment if any  ***/
            if (!empty($post["field_name"])) {
                $post_field_value_name = $post["field_name"];
                $counter_name = count($post_field_value_name);
                for ($i = 0; $i < $counter_name; $i++) {
                    $data_field_value = [
                        "field_value" => $post["field_name"][$i],
                        "field_id" => $post["field_id"][$i],
                        "type" => 17,
                        "section_id" => 17,
                        "p_mode_id" => $post["payment_mode"],
                        "branch_id" => $user_data["parent_id"],
                        "parent_id" => $last_id,
                        "ip_address" => $_SERVER["REMOTE_ADDR"],
                    ];
                    $this->db->set("created_by", $user_data["id"]);
                    $this->db->set("created_date", $created_date);
                    $this->db->insert(
                        "hms_payment_mode_field_value_acc_section",
                        $data_field_value
                    );
                }
            }
            /** Code to insert relational data for mode of payment if any ***/

            /* code for package detail */
            $doctor_wise = $this->get_package_ot_details(
                $post["pacakage_name"]
            );
            $particular_id = "";
            $doctor_id = "";

            //$particular_wise=$post['particular_wise'];
            //print_r($post);die;
            if ($post["op_type"] == 2) {
                if (!empty($doctor_wise)) {
                    $i = 0;
                    foreach ($doctor_wise as $key => $val) {
                        if (
                            isset($val->doctor_name) &&
                            !empty($val->doctor_name)
                        ) {
                            $doctor_id = $val->doctor_id;
                            $data = [
                                "branch_id" => $user_data["parent_id"],
                                //'doctor_id'=>$doctor_id,
                                "doctor_name" => $val->doctor_name,
                                "master_type" => $val->master_type,
                                "master_rate" => $val->master_rate,
                                "code" => $val->code,
                                "ot_type" => $val->ot_type,
                                "ot_package_id" => $val->ot_package_id,
                                "ot_booking_id" => $last_id,

                                "created_date" => date("Y-m-d H:i:s"),
                                "created_by" => $user_data["id"],
                            ];

                            $this->db->insert(
                                "hms_ot_booking_pacakge_details",
                                $data
                            );
                        }

                        if (
                            isset($val->particular_name) &&
                            !empty($val->particular_name)
                        ) {
                            $particular_id = $val->particular_id;
                            $data = [
                                "branch_id" => $user_data["parent_id"],
                                //'particular_id'=>$particular_id,
                                "particular_name" => $val->particular_name,
                                "master_type" => $val->master_type,
                                "master_rate" => $val->master_rate,
                                "code" => $val->code,
                                "ot_type" => $val->ot_type,
                                "ot_package_id" => $post["pacakage_name"],
                                "ot_booking_id" => $last_id,

                                "created_date" => date("Y-m-d H:i:s"),
                                "created_by" => $user_data["id"],
                            ];
                            $this->db->insert(
                                "hms_ot_booking_pacakge_details",
                                $data
                            );
                        }
                    }
                }
            }

            /* code for package detail */

            /* code for package detail */
            $doctor_wise = $this->get_ot_details($post["operation_name"]);
            $particular_id = "";
            $doctor_id = "";

            //$particular_wise=$post['particular_wise'];
            //print_r($post);die;
            if ($post["op_type"] == 1) {
                if (!empty($doctor_wise)) {
                    $i = 0;
                    foreach ($doctor_wise as $key => $val) {
                        if (
                            isset($val->doctor_name) &&
                            !empty($val->doctor_name)
                        ) {
                            $doctor_id = $val->doctor_id;
                            $data = [
                                "branch_id" => $user_data["parent_id"],
                                //'doctor_id'=>$doctor_id,
                                "doctor_name" => $val->doctor_name,
                                "master_type" => $val->master_type,
                                "master_rate" => $val->master_rate,
                                "ot_type" => $val->ot_type,
                                "code" => $val->code,
                                "ot_mgt_id" => $val->ot_mgt_id,
                                "ot_booking_id" => $last_id,

                                "created_date" => date("Y-m-d H:i:s"),
                                "created_by" => $user_data["id"],
                            ];

                            $this->db->insert(
                                "hms_ot_booking_ot_details",
                                $data
                            );
                        }

                        if (
                            isset($val->particular_name) &&
                            !empty($val->particular_name)
                        ) {
                            $particular_id = $val->particular_id;
                            $data = [
                                "branch_id" => $user_data["parent_id"],
                                //'particular_id'=>$particular_id,
                                "particular_name" => $val->particular_name,
                                "master_type" => $val->master_type,
                                "master_rate" => $val->master_rate,
                                "ot_type" => $val->ot_type,
                                "code" => $val->code,
                                "ot_mgt_id" => $val->ot_mgt_id,
                                "ot_booking_id" => $last_id,

                                "created_date" => date("Y-m-d H:i:s"),
                                "created_by" => $user_data["id"],
                            ];
                            $this->db->insert(
                                "hms_ot_booking_ot_details",
                                $data
                            );
                        }
                    }
                }
            }

            /* code for ot detail */

            //echo $this->db->last_query();die;
            $ot_book_id = $last_id;

            //if(!empty($ot_book_id) && !empty($post['pacakage_name']) && $post['pacakage_name']!='0.00')
            //pacakage_name as package id
            $p_p_name = "";
            $p_name = "";
            if (!empty($ot_book_id) && !empty($post["ipd_id"])) {
                if (!empty($post["op_type"]) && $post["op_type"] == 1) {
                    $package_charge = get_ot_charge($post["operation_name"]);
                    $amount = $package_charge[0]["amount"];
                    $p_name = $post["operation_name"];
                } else {
                    $package_charge = get_ot_package_charge(
                        $post["pacakage_name"]
                    );
                    $amount = $package_charge[0]["amount"];
                    $p_p_name = $post["pacakage_name"];
                }
                //echo "<pre>";print_r($package_charge); exit;
                $ot_charge = [
                    "branch_id" => $user_data["parent_id"],
                    "ipd_id" => $post["ipd_id"],
                    "patient_id" => $patient_id,
                    "ot_id" => $ot_book_id,
                    "ot_package_id" => $p_p_name,
                    "ot_operation_id" => $p_name,
                    "type" => 6,
                    "quantity" => 1,
                    "start_date" => date(
                        "Y-m-d",
                        strtotime($post["operation_date"])
                    ),
                    "end_date" => date(
                        "Y-m-d",
                        strtotime($post["operation_date"])
                    ),
                    "particular" => $post["operation_name"] . " (OT)",
                    "price" => $amount,
                    "panel_price" => $amount,
                    "net_price" => $amount,
                    "status" => 1,
                    "created_date" => date("Y-m-d H:i:s"),
                ];
                $this->db->insert("hms_ipd_patient_to_charge", $ot_charge);
                //echo $this->db->last_query();die;
            }

            foreach ($post["doctor_names"] as $key => $value) {
                $doctor_array = [
                    "operation_id" => $last_id,
                    "doctor_id" => $key,
                    "doctor_name" => $value[0],
                ];

                $this->db->insert("hms_operation_to_doctors", $doctor_array);
            }
        }
        return $ot_book_id;
    }
    public function get_package_ot_details($package_id = "")
    {
        $user_data = $this->session->userdata("auth_users");
        $this->db->select("*");
        $this->db->where("branch_id", $user_data["parent_id"]);
        $this->db->where("ot_package_id", $package_id);
        $query = $this->db->get("hms_ot_pacakge_details")->result();

        //print_r($query);die;
        return $query;
    }

    public function get_ot_details($ot_id = "")
    {
        $user_data = $this->session->userdata("auth_users");
        $this->db->select("*");
        $this->db->where("branch_id", $user_data["parent_id"]);
        $this->db->where("ot_mgt_id", $ot_id);
        $query = $this->db->get("hms_ot_details")->result();

        //print_r($query);die;
        return $query;
    }
    public function get_package_hours($package_name = "")
    {
        $user_data = $this->session->userdata("auth_users");
        $this->db->select("*");
        $this->db->where("branch_id", $user_data["parent_id"]);
        $this->db->where("id", $package_name);
        $query = $this->db->get("hms_ot_pacakge")->result();
        //print_r($query);die;
        return $query;
    }

    public function ot_room_list()
    {
        $user_data = $this->session->userdata("auth_users");
        $this->db->select("*");
        $this->db->where("branch_id", $user_data["parent_id"]);
        $this->db->where("is_deleted", 0);
        $query = $this->db->get("hms_ot_room")->result();
        //print_r($query);die;
        return $query;
    }

    public function get_operation_hours($op_name = "")
    {
        $user_data = $this->session->userdata("auth_users");
        $this->db->select("*");
        $this->db->where("branch_id", $user_data["parent_id"]);
        $this->db->where("id", $op_name);
        $query = $this->db->get("hms_ot_management")->result();
        //print_r($query);die;
        return $query;
    }

    public function delete($id = "")
    {
        if (!empty($id) && $id > 0) {
            $user_data = $this->session->userdata("auth_users");
            $this->db->set("is_deleted", 1);
            $this->db->set("deleted_by", $user_data["id"]);
            $this->db->set("deleted_date", date("Y-m-d H:i:s"));
            $this->db->where("id", $id);
            $this->db->update("hms_operation_booking");
            //echo $this->db->last_query();die;
        }
    }

    public function deleteall($ids = [])
    {
        if (!empty($ids)) {
            $id_list = [];
            foreach ($ids as $id) {
                if (!empty($id) && $id > 0) {
                    $id_list[] = $id;
                }
            }
            $branch_ids = implode(",", $id_list);
            $user_data = $this->session->userdata("auth_users");
            $this->db->set("is_deleted", 1);
            $this->db->set("deleted_by", $user_data["id"]);
            $this->db->set("deleted_date", date("Y-m-d H:i:s"));
            $this->db->where("id IN (" . $branch_ids . ")");
            $this->db->update("hms_operation_booking");
            //echo $this->db->last_query();die;
        }
    }

    public function get_vals($vals = "")
    {
        $response = "";
        if (!empty($vals)) {
            $users_data = $this->session->userdata("auth_users");
            $this->db->select("*");
            $this->db->where("status", "1");
            $this->db->order_by("medicine_type", "ASC");
            $this->db->where("is_deleted", 0);
            // $this->db->where('medicine_type LIKE "'.$vals.'%"');
            $this->db->where("branch_id", $users_data["parent_id"]);
            $query = $this->db->get("hms_operation_booking");
            $result = $query->result();
            //echo $this->db->last_query();
            if (!empty($result)) {
                foreach ($result as $vals) {
                    $response[] = $vals->medicine_type;
                }
            }
            return $response;
        }
    }
    public function get_patient_by_id_with_ipd_detail($id)
    {
        $this->db->select(
            "hms_patient.*,hms_patient.id as p_id,hms_ipd_booking.id as ipd_id,hms_ipd_booking.ipd_no,hms_ipd_booking.patient_type,hms_ipd_rooms.room_no,hms_patient.modified_date as patient_modified_date"
        );
        $this->db->from("hms_patient");
        $this->db->where("hms_ipd_booking.patient_id", $id);
        $this->db->join(
            "hms_ipd_booking",
            "hms_ipd_booking.patient_id=hms_patient.id",
            "left"
        );
        $this->db->join(
            "hms_ipd_rooms",
            "hms_ipd_rooms.id=hms_ipd_booking.room_id",
            "left"
        );
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        return $query->row_array();
    }

    public function pacakage_list()
    {
        $user_data = $this->session->userdata("auth_users");
        $this->db->select("*");
        $this->db->where("branch_id", $user_data["parent_id"]);
        $this->db->where("status", 1);
        $this->db->where("is_deleted", 0);
        $this->db->order_by("name", "ASC");
        $query = $this->db->get("hms_ot_pacakge");
        return $query->result();
    }
    public function remarks_list()
    {
        $user_data = $this->session->userdata("auth_users");
        $this->db->select("*");
        $this->db->where("branch_id", $user_data["parent_id"]);
        $this->db->where("status", 1);
        $this->db->where("is_deleted", 0);
        $this->db->order_by("remarks", "ASC");
        $query = $this->db->get("hms_ot_remarks")->result();
        //print_r($query);die;
        return $query;
    }

    public function get_doctor_name($vals = "", $specialization_id = "")
    {
        $response = "";
        if (!empty($vals)) {
            $vals = urldecode($vals);
            $users_data = $this->session->userdata("auth_users");
            $this->db->select("*");
            $this->db->order_by("hms_doctors.doctor_name", "ASC");
            $this->db->where("hms_doctors.is_deleted", 0);
            $this->db->where("hms_doctors.doctor_type IN (0,2)");
            if (!empty($vals)) {
                $this->db->like("hms_doctors.doctor_name", $vals, "after");
            }
            $this->db->where("hms_doctors.branch_id", $users_data["parent_id"]);
            if ($specialization_id != "") {
                $this->db->where(
                    "hms_doctors.specilization_id",
                    $specialization_id
                );
            }
            $query = $this->db->get("hms_doctors");
            $result = $query->result();

            $this->db->like("name", $vals, "after");
            $this->db->where("is_deleted", 0);
            $this->db->where("branch_id", $users_data["parent_id"]);
            $query2 = $this->db->get("hms_employees");
            $result2 = $query2->result();
            //echo $this->db->last_query();
            $data = [];
            if (!empty($result)) {
                foreach ($result as $vals) {
                    //$response[] = $vals->medicine_name;
                    $name = $vals->doctor_name . "|" . $vals->id;
                    array_push($data, $name);
                }
            }

            if (!empty($result2)) {
                foreach ($result2 as $vals) {
                    //$response[] = $vals->medicine_name;
                    $name = $vals->name . "|" . $vals->id;
                    array_push($data, $name);
                }
            }
            // echo json_encode($data);
            return $data;
        }
    }
    function doctor_list_by_otids($id)
    {
        $this->db->select("hms_operation_to_doctors.*");
        $this->db->from("hms_operation_to_doctors");
        $this->db->where("hms_operation_to_doctors.operation_id", $id);

        $query = $this->db->get()->result();
        $data = [];
        foreach ($query as $res) {
            $data[$res->doctor_id][] = $res->doctor_name;
        }
        return $data;
    }

    function get_all_detail_print($ids = "", $branch_ids = "")
    {
        $result_operation = [];
        $this->db->select(
            "hms_operation_booking.*,hms_users.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_ot_pacakge.remarks as pacakge_remarks,hms_ot_pacakge.type as pacakge_type,hms_ot_pacakge.amount as package_amount,hms_ot_pacakge.days,hms_ot_pacakge.name as package_name,hms_ipd_booking.ipd_no,hms_ipd_booking.patient_type,hms_patient.patient_code,hms_patient.simulation_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_ipd_rooms.room_no,hms_simulation.simulation,hms_ipd_room_to_bad.bad_no as bed_no,	(CASE WHEN hms_operation_booking.op_type=1 THEN hms_ot_management.name ELSE  hms_ot_pacakge.name END) as op_name, (CASE WHEN hms_operation_booking.op_type=1 THEN hms_ot_management.amount ELSE  hms_ot_pacakge.amount END) as op_charge,  hms_sms.simulation as rel_simulation,hms_gardian_relation.relation,hms_patient.relation_simulation_id,hms_patient.relation_type,hms_patient.relation_name,hms_operation_type.operation_type, pymt_mode.payment_mode, hms_ot_room.room_no as ot_room,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,hms_countries.country as country_name, hms_state.state as state_name, hms_cities.city as city_name,hms_patient.address as address1,hms_patient.pincode"
        );
        $this->db->from("hms_operation_booking");
        $this->db->where("hms_operation_booking.id", $ids);
        $this->db->where("hms_operation_booking.branch_id", $branch_ids);
        $this->db->join(
            "hms_ipd_booking",
            "hms_ipd_booking.id=hms_operation_booking.ipd_id",
            "left"
        );
        $this->db->join(
            "hms_ot_management",
            "hms_ot_management.id=hms_operation_booking.operation_name",
            "left"
        );

        $this->db->join(
            "hms_ot_room",
            "hms_ot_room.id=hms_operation_booking.ot_room_no",
            "left"
        );

        $this->db->join(
            "hms_ipd_rooms",
            "hms_ipd_rooms.id=hms_ipd_booking.room_id",
            "left"
        );
        $this->db->join(
            "hms_ipd_room_to_bad",
            "hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id",
            "left"
        );

        $this->db->join(
            "hms_payment_mode as pymt_mode",
            "pymt_mode.id=hms_operation_booking.mode_of_payment",
            "left"
        );

        $this->db->join(
            "hms_ot_pacakge",
            "hms_ot_pacakge.id=hms_operation_booking.package_id",
            "left"
        );
        $this->db->join(
            "hms_operation_type",
            "hms_operation_type.id=hms_ot_pacakge.type",
            "left"
        );

        $this->db->join(
            "hms_patient",
            "hms_patient.id=hms_operation_booking.patient_id",
            "left"
        );

        $this->db->join(
            "hms_countries",
            "hms_countries.id = hms_patient.country_id",
            "left"
        ); // country name
        $this->db->join(
            "hms_state",
            "hms_state.id = hms_patient.state_id",
            "left"
        ); // state name
        $this->db->join(
            "hms_cities",
            "hms_cities.id = hms_patient.city_id",
            "left"
        ); // city name

        $this->db->join(
            "hms_gardian_relation",
            "hms_gardian_relation.id=hms_patient.relation_type",
            "left"
        );
        $this->db->join(
            "hms_simulation",
            "hms_simulation.id = hms_patient.simulation_id",
            "left"
        );
        $this->db->join(
            "hms_simulation as hms_sms",
            "hms_sms.id = hms_patient.relation_simulation_id",
            "left"
        );

        $this->db->join(
            "hms_branch_hospital_no",
            "hms_branch_hospital_no.parent_id = hms_operation_booking.id AND hms_branch_hospital_no.section_id=16",
            "left"
        );
        $this->db->join(
            "hms_users",
            "hms_users.id = hms_operation_booking.created_by"
        );
        $this->db->join(
            "hms_employees",
            "hms_employees.id=hms_users.emp_id",
            "left"
        );
        $this->db->join(
            "hms_branch",
            "hms_branch.users_id=hms_users.id",
            "left"
        );

        $this->db->where("hms_operation_booking.is_deleted", "0");
        $query = $this->db->get();
        $result_operation["operation_list"] = $query->result();

        //print_r($result_operation['operation_list']);die;
        $this->db->select("hms_operation_to_doctors.*");
        $this->db->where(
            'hms_operation_to_doctors.operation_id = "' . $ids . '"'
        );
        $this->db->from("hms_operation_to_doctors");
        $result_operation["operation_list"]["doctor_list"] = $this->db
            ->get()
            ->result();
        return $result_operation;
    }

    function get_ot_detail_print($ids = "", $branch_id = "")
    {
        $result_operation = [];
        $this->db->select(
            "hms_ot_booking_pacakge_details.*,hms_ot_booking_pacakge_details.particular_name as particular"
        );
        $this->db->from("hms_ot_booking_pacakge_details");
        $this->db->where("hms_operation_booking.id", $ids);
        $this->db->where("hms_operation_booking.branch_id", $branch_id);
        $this->db->join(
            "hms_ot_pacakge",
            "hms_ot_pacakge.id=hms_ot_booking_pacakge_details.ot_package_id",
            "left"
        );
        $this->db->join(
            "hms_operation_booking",
            "hms_operation_booking.id=hms_ot_booking_pacakge_details.ot_booking_id",
            "left"
        );

        $this->db->where("hms_ot_pacakge.is_deleted", "0");
        $this->db->where("hms_operation_booking.is_deleted", "0");
        $query = $this->db->get();
        $result_operation["operation_list"] = $query->result();

        $this->db->select("hms_operation_to_doctors.*");
        $this->db->where(
            'hms_operation_to_doctors.operation_id = "' . $ids . '"'
        );
        $this->db->from("hms_operation_to_doctors");
        $result_operation["operation_list"]["doctor_list"] = $this->db
            ->get()
            ->result();
        return $result_operation;
    }

    function template_format($data = "")
    {
        $users_data = $this->session->userdata("auth_users");
        $this->db->select("hms_print_branch_template.*");
        $this->db->where($data);
        $this->db->from("hms_print_branch_template");
        $query = $this->db->get()->row();
        //print_r($query);exit;
        return $query;
    }
    function template_format_ot_detail($data = "")
    {
        $users_data = $this->session->userdata("auth_users");
        $this->db->select("hms_branch_ot_detail_print_setting.*");
        $this->db->where($data);
        $this->db->from("hms_branch_ot_detail_print_setting");
        $query = $this->db->get()->row();
        //print_r($query);exit;
        return $query;
    }
    public function operation_list()
    {
        $users_data = $this->session->userdata("auth_users");
        $this->db->select("hms_ot_management.*");
        $this->db->where("status", 1);
        $this->db->where("is_deleted", 0);
        $this->db->where("branch_id  IN (" . $users_data["parent_id"] . ")");
        $this->db->from("hms_ot_management");
        $this->db->order_by("name", "asc");
        $query = $this->db->get()->result();
        //print_r($query);exit;
        return $query;
    }

    public function ot_scheduled_time(
        $operation_date = "",
        $start_datetime = "",
        $end_datetime = "",
        $operation_room = ""
    ) {
        $users_data = $this->session->userdata("auth_users");
        $this->db->select(
            'hms_operation_booking.*,CONCAT(hms_operation_booking.operation_date," ",hms_operation_booking.operation_time) as ot_start_datetime, CONCAT(hms_operation_booking.operation_date," ",hms_operation_booking.operation_end_time) as ot_end_datetime '
        );
        $this->db->where("branch_id  IN (" . $users_data["parent_id"] . ")");
        $this->db->where("hms_operation_booking.is_deleted", 0);
        $this->db->from("hms_operation_booking");
        $query = $this->db->get()->result();
        $msg = [];
        //print '<pre>'; print_r($query);die;
        foreach ($query as $res) {
            if (
                $res->operation_date == $operation_date &&
                $res->ot_room_no == $operation_room &&
                ($start_datetime >= $res->ot_start_datetime &&
                    $start_datetime <= $res->ot_end_datetime)
            ) {
                $msg["error"] = 1;
            } else {
                $msg["error"] = 2;
            }
        }
        return $msg;
    }

    public function ot_scheduled_doctor(
        $operation_date = "",
        $start_datetime = "",
        $end_datetime = "",
        $doctors = ""
    ) {
        $users_data = $this->session->userdata("auth_users");
        $this->db->select(
            'hms_operation_booking.*,CONCAT(hms_operation_booking.operation_date," ",hms_operation_booking.operation_time) as ot_start_datetime, CONCAT(hms_operation_booking.operation_date," ",hms_operation_booking.operation_end_time) as ot_end_datetime '
        );
        $this->db->where("branch_id  IN (" . $users_data["parent_id"] . ")");
        $this->db->where("hms_operation_booking.is_deleted", 0);
        $this->db->from("hms_operation_booking");
        $query = $this->db->get()->result();
        $msg = [];
        $new_val = [];
        $doctor = [];
        //print '<pre>'; print_r($query);die;
        foreach ($query as $res) {
            if (
                $res->operation_date == $operation_date &&
                ($start_datetime >= $res->ot_start_datetime &&
                    $start_datetime <= $res->ot_end_datetime)
            ) {
                foreach ($doctors as $key => $value) {
                    $new_val[] = $key;
                }

                $this->db->select("hms_operation_to_doctors.*");
                $this->db->where(
                    "hms_operation_to_doctors.doctor_id IN (" .
                        implode(",", $new_val) .
                        ")"
                );
                $this->db->where(
                    "hms_operation_to_doctors.operation_id",
                    $res->id
                );
                $result_doctor = $this->db
                    ->get("hms_operation_to_doctors")
                    ->result();

                if (!empty($result_doctor)) {
                    $doctor["error"] = 4;
                } else {
                    $doctor["error"] = 5;
                }
            } else {
                $msg["error"] = 2;
            }
        }

        return ["doctor_error" => $doctor["error"], "error" => $msg["error"]];
    }

    function payment_mode_detail_according_to_field(
        $p_mode_id = "",
        $parent_id = "",
        $type_id,
        $section_id
    ) {
        //12,13//
        $users_data = $this->session->userdata("auth_users");
        $this->db->select(
            "hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name"
        );
        $this->db->join(
            "hms_payment_mode_to_field",
            "hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id"
        );

        $this->db->where(
            "hms_payment_mode_field_value_acc_section.p_mode_id",
            $p_mode_id
        );
        $this->db->where(
            "hms_payment_mode_field_value_acc_section.branch_id",
            $users_data["parent_id"]
        );
        $this->db->where(
            "hms_payment_mode_field_value_acc_section.type",
            $type_id
        );
        $this->db->where(
            "hms_payment_mode_field_value_acc_section.parent_id",
            $parent_id
        );
        $this->db->where(
            "hms_payment_mode_field_value_acc_section.section_id",
            $section_id
        );
        $query = $this->db
            ->get("hms_payment_mode_field_value_acc_section")
            ->result();

        return $query;
    }

    // Please write code above this
}
?>
