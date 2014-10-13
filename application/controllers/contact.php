<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller {

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
        $this->load->model('mcontact');
	}
    
	public function index()
	{
        $this->load->helper('custom_function');
        $data['total_email'] = $this->mcontact->count_mail();
		$data['contact'] = $this->mcontact->get_contact();
		$data['tags_name'] = $this->mcontact->json_tags_name();
        $this->load->view('vContact',$data);
	}
    
    function import()
    {
        $this->load->view('vContactImport');
    }
    
    function upload(){
        $conf   = array();
        $conf['upload_path'] = './tmp';
        $conf['allowed_types'] = 'txt|pdf';
        $conf['max_size'] = '10000000000';
        $conf['overwrite'] = true;
        $this->load->library('upload',$conf);
        
        
        $result = array();
        if(!$this->upload->do_upload('contact-upload')){
            $result['data']   = $this->upload->display_errors();
            $result['status'] = 'error';
        }else{
            $data   = $this->upload->data();
            
            $target['filename'] = $conf['upload_path'].'/'.$data['file_name'];
            
            $result['data']   =  $this->mcontact->filter_upload($target);
            unlink($target['filename']);
            $result['status'] = 'success';
        }
        echo json_encode($result);
    }
    
    function post_contact()
    {
        $result = $this->mcontact->filter_post_contact();
        $result['status'] = 'success';
        $result['total_email'] = $this->mcontact->count_mail();
        echo json_encode($result);
    }
    
    function remove_contact(){
        $result = array();
        $result['status'] = 'failed';
        
        $id = $this->input->post('iid');
        $xx = $this->mcontact->remove_contact($id);
        $result['total_email'] = $this->mcontact->count_mail();
        if($xx) $result['status'] = 'success';
        
        echo json_encode($result);
    }
    
    function tags()
    {
        $data['tags'] = $this->mcontact->get_tags();
        $this->load->view('vContactTags',$data);
    }
    
    function post_tag(){
        $result = $this->mcontact->filter_input();
        $result['status'] = 'success';
        echo json_encode($result);
    }
    
    function remove_tag(){
        $result = array();
        $result['status'] = 'failed';
        
        $id = $this->input->post('iid');
        $xx = $this->mcontact->remove_tag($id);
        if($xx) $result['status'] = 'success';
        
        echo json_encode($result);
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