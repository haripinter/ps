<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail extends CI_Controller {
    public function __construct(){
		parent::__construct();
        
        $this->load->database($this->db());
        $this->load->model('mmail');
	}
    
    public function index()
    {
        $data['message'] = $this->mmail->get_message_out();
        $this->load->view('vMail',$data);
    }
    
    function create( $id=null )
    {
        $data['message'] = '';
        if(isset($id) && is_numeric($id) && $id > 0){
            $data['message'] = $this->mmail->get_message_out( array('id'=>$id) );
        }
        $this->load->view('vMailCreate', $data);
    }
    
    function post_message()
    {
        $result = $this->mmail->post_message_out();
        $result['status'] = 'success';
        echo json_encode($result);
    }
    
    function remove_message_out()
    {
        $result = array();
        $result['status'] = 'failed';
        
        $id = $this->input->post('iid');
        $xx = $this->mmail->remove_message_out($id);
        if($xx) $result['status'] = 'success';
        echo json_encode($result);
    }
    
    function t(){
        $conf = array();
        $conf['useragent'] = 'Mozilla/5.0 (Power PC) Gecko/20100101 Firefox/30.0';
        $conf['protocol']  = 'smtp';
        $conf['smtp_host'] = 'ssl://sayegan.idwebhost.com';
        $conf['smtp_port'] = 465;
        $conf['newline']   = "\r\n"; 
        $conf['mailtype']  = 'html';
        $conf['charset']   = 'utf-8';
        $conf['smtp_user'] = 'mail@kreswanti.com';
        $conf['smtp_pass'] = 'kreswantimail';
        
        $this->load->library('email',$conf);
        $this->email->from('mail@kreswanti.com');
        $this->email->to('hspinter@yahoo.com');
        $this->email->subject('subject');
        $this->email->message('<p>Suatu hari, sekelompok semut tengah berjalan melewati hutan. Diantara jalan yang dilewati, rupanya terdapat genangan air yang cukup besar dan akhirnya menenggelamkan dua diantara sekelompok semut tersebut. Mereka jatuh dan tidak tahu bagaimana cara berenang. Mereka hanya berteriak dan berusaha sekuat mungkin untuk bisa menyentuh daratan. Genangan air itu rupanya cukup besar, sehingga setiap kali dua semut nyaris berhasil, gelombang air seakan membuat mereka kembali menjauh dari daratan yang dituju.<br><br><a href="http://www.kreswanti.com/" title="kreswanti brooch"><img style="float: left; margin-right: 10px; width: 244.75px;" src="http://kreswanti.com/images/produsen-bros-cantik.jpg" alt="bros cantik kain"></a>Melihat hal ini, sekelompok semut lainnya akhirnya berkata, “Hai, genangan air itu tidak akan bisa membuatmu kembali. Usahamu hanya akan sia-sia. Kamu akan mati disana.”<br></p>
<p align="center"></p>
Namun kedua semut hanya mengabaikan komentar dari teman sekelompoknya. Mereka tidak mendengar ocehan itu dan hanya berusaha sekuat mungkin untuk mencoba dan terus mencoba. Namun kelompok semut yang lainnya kembali berkata, “Sudah kukatakan, usahamu itu tidak akan pernah membuahkan hasil. Kamu hanya akan tenggelam dan mati disana.” Semakin banyak anggota semut yang meminta mereka menghentikan usahanya, akhirnya satu semut pun menyerah. Ia berpikir bahwa apa yang dikatakan kelompoknya adalah benar. Untuk bisa kembali menyentuh daratan, sepertinya hanyalah mimpi yang sia-sia. Usahanya yang sudah ia lakukan nyatanya tak membuahkan hasil juga. Ia&nbsp; menyerah dan akhirnya mati disana.<br><br>Sedangkan semut lain, ia masih saja berupaya sekuat tenaga. Kelompoknya terheran-heran, mengapa ia terus saja melakukan hal konyol seperti itu. “Hai, apa kau tidak dengar apa yang kita katakan? Berhentilah, percuma. Kau tidak akan pernah berhasil!” Namun tak lama, selembar daun gugur terjatuh tepat disampingnya. Tanpa berpikir panjang, semut pun segera naik dan akhirnya selamat sampai ke darat.<br><br>Saat ia tiba, semut lain bertanya, “Apa kau tidak dengar apa yang kita katakan tadi?” Lalu semut itu pun menjelaskan bahwa sebenarnya ia tuli. Telinganya tidak cukup baik untuk mendengarkan suara dengan frekuensi yang tidak dekat jaraknya. Ia justru mengira bahwa kelompok menyemangatinya sepanjang waktu.<br><br><a href="http://www.kreswanti.com/" title="kreswanti brooch"><img style="width: 248px; float: left; margin-right:11px" src="http://kreswanti.com/images/butik-online-gamis.jpg" alt="kreswanti butik online"></a>Ada kekuatan hidup dan mati yang berdasar kepada ucapan dan tutur kata yang diberikan seseorang. Seseorang yang berkata dengan segenap ketulusan hatinya, akan membuat mereka yang mendengar menjadi mampu untuk melewati berbagai hal sulit didalam kehidupannya. Namun seorang yang berkata dengan segenap kebenciannya, sama dengan ia telah membunuh dirinya sendiri.<br><br>Dengan memberikan semangat dan motivasi kepada orang lain, sama halnya dengan kita turut memotivasi diri sendiri. Jangan selalu mendengar anggapan buruk dari orang lain terhadap apa yang kita lakukan. Anggapan buruk, hanya akan menjadi penghalang dalam perjalanan kita mencapai tujuan. Percayalah bahwa kerja keras, pasti akan meninggalkan hasil yang berarti. Percayalah bahwa kita akan bisa mencapainya dengan cara dan kerja keras yang kita lakukan sendiri.<p></p>
<p align="center"><br></p>
<p>Semoga tulisan ini bermanfaat untuk kita semua. :)<br></p>
<p><br></p>
<p>Mau aneka bros cantik, <a target="_blank" href="http://www.kreswanti.com/">Simple &amp; Elegan</a>?!&nbsp; <a target="_blank" href="http://www.kreswanti.com/">Cek aja langsung di SINI</a>.<br></p>');
        
        if($this->email->send()){
            echo 'success';
            echo 'sent';
        }else{
            echo 'failed';
            echo 'failed';
        }
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
?>