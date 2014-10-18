<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exec extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
     
    public function __construct(){
		parent::__construct();
        
        $this->load->database($this->db());
	}
    
	public function index()
	{   
        $this->load->model('mmission');
        $this->load->helper('custom_function');
        //$data['total_email'] = $this->mcontact->count_mail();
		//$data['contact'] = $this->mcontact->get_contact();
		//$data['tags_name'] = $this->mcontact->json_tags_name();
        $data['missions'] = $this->mmission->get_mission();
        $this->load->view('vExec',$data);
	}
    
    function db(){
        $conf = array();
        $conf['hostname'] = 'localhost';
        $conf['username'] = 'root';
        $conf['password'] = '';
        $conf['database'] = 'broadcast_msg';
        $conf['dbdriver'] = 'mysql';
        $conf['db_debug'] = TRUE;
        return $conf;
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */