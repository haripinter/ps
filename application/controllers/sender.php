<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sender extends CI_Controller {

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
        $this->load->model('msender');
	}
    
	public function index()
	{
        $this->load->helper('custom_function');
        $data['total_sender'] = $this->msender->count_sender();
		$data['sender'] = $this->msender->get_sender();
		$data['server'] = $this->msender->get_server_json();
		$this->load->view('vSender',$data);
        
	}
    
    function post_sender()
    {
        $result = $this->filter_post_sender();
        $result['status'] = 'success';
        $result['total_sender'] = $this->msender->count_sender();
        echo json_encode($result);
    }
    
    function filter_post_sender()
    {
        $this->load->library('crypto');
        $this->load->database($this->db());
        
        $result['data'] = 0;
        $result['success'] = 0;
        $result['error'] = 0;
        $result['error_data'] = array();
        $result['success_data'] = array();
        $ids  = $this->input->post('ids');
        $isv  = $this->input->post('iserver');
        $iml  = $this->input->post('iemail');
        $ipw  = $this->input->post('ipass');
        
        if(is_array($iml) && count($iml) >0){
            for($n=0; $n<count($iml); $n++){
                if($iml[$n]=='') continue;
                $data = array();
                $data['server'] = intval($isv[$n]);
                $data['email'] = strtolower($iml[$n]);
                $data['password'] = $ipw[$n];
                $data['status'] = 1;
                
                // for result
                $dota = array();
                $dota['server'] = $data['server'];
                $dota['server_name'] = $this->msender->get_server_name( $data['server'] );
                $dota['email'] = $data['email'];
                $dota['password'] = $data['password'];
                
                $r = FALSE;
                if(is_numeric($ids[$n]) && $ids[$n] > 0){
                    $data['id'] = $ids[$n];
                    $r = $this->msender->update_sender($data);
                    $dota['id'] = $ids[$n];
                }else{
                    $r = $this->msender->insert_sender($data);
                    $dota['id'] = ($r == FALSE)? 0 : $r;
                }
                
                if(!$r){
                    $result['error']++;
                    array_push($result['error_data'],$dota);
                }else{
                    $result['success']++;
                    array_push($result['success_data'],$dota);
                }
                $result['data']++;
            }
            return $result;
        }
        return FALSE;
    }
    
    function remove_sender(){
        $result = array();
        $result['status'] = 'failed';
        
        $id = $this->input->post('id');
        $xx = $this->msender->remove_sender($id);
        $result['total_sender'] = $this->msender->count_sender();
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