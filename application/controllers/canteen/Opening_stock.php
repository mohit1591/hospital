<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opening_stock extends CI_Controller {

  function __construct() 
  {
     parent::__construct();  
     auth_users();  
     $this->load->library('form_validation');
  }

  public function index()
  {
    $data['page_title'] = 'Opening Stock';
    // $this->load->view('canteen/product_stock/list', $data);
    $this->load->view('canteen/opening_stock/list', $data);
  }
  public function add()
  {
    $data['page_title'] = 'Add Opening Stock';
    // $this->load->view('canteen/product/add', $data);
    $this->load->view('canteen/opening_stock/add', $data);
  }

}
?>