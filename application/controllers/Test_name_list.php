defined('BASEPATH') OR exit('No direct script access allowed');

class Test_name_list extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
       
        $this->load->library('form_validation');
    }
    
     public function index()
    { 
        $query = $this->db->query('SELECT path_test.test_name FROM `path_test` left join path_multi_interpration on path_multi_interpration.test_id = path_test.id where path_test.branch_id=0 and is_deleted=0');
        
        
		$result = $query->result(); 
		
        $data['row'] = $result;
        $this->load->view('test_list',$data);
    }
    
    
}