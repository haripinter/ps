<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crypto extends CI_Controller {
   var $a = 77;
   var $b = 19;
   var $p = 193;
   var $k = 1337;
   var $P = array(133,78);
   
   var $dP = array();   
   var $pesan = "";
   
   //int[] P, int[] dP, String M, int k, int a, int b, int p
   function enkrip($pesan=''){
      if($pesan=='') return '';
      
      $dP = $this->kali($this->p,$this->a,$this->b,$this->k,$this->P);
      $kdP = $this->kali($this->p, $this->a, $this->b, $this->k, $dP);
      $XkdP = $kdP[0];
      $kP = $this->kali($this->p, $this->a, $this->b, $this->k, $this->P);
      $head = $this->unichr($kP[0])."###".$this->unichr($kP[1])."###";
      $MM="";
      for($cd=0; $cd<strlen($pesan); $cd++){
         $eMx = $XkdP ^ $this->uniord($pesan[$cd]);
         $MM = $MM . $this->unichr($eMx);
      }
      $MM = $head . $MM;
      $MM = base64_encode($MM);
      return $MM;
   }
   
   //String M, int d, int a, int b, int p
   function dekrip($pesan=''){
      if($pesan=='') return '';
      
      $pesan = base64_decode($pesan);
      $msg = "";
      $dmp = explode("###",$pesan);
      if(count($dmp)!=3) return $pesan;
      $kP = array($this->uniord($dmp[0]),$this->uniord($dmp[1]));
      $dkP = $this->kali($this->p,$this->a,$this->b,$this->k,$kP);
      $pesan = $dmp[2];
      for($dr=0; $dr<strlen($pesan); $dr++){
         $eMX = $dkP[0] ^ $this->uniord($pesan[$dr]);
         $msg = $msg . $this->unichr($eMX);
      }
      return $msg;
   }
       
   private function invers($c, $p){
      $invc = 0;
      $c = $c%$p;
      $a = array(1,0,$p);
      $b = array(0,1,(int)$c);
      $ulang = true;
      while($ulang){
         if($b[2]==0){
            $ulang = false;
            break;
         }
         if($b[2]==1){
            $invc = $b[1]%$p;
            $ulang = false;
            break;
         }
         if($ulang){
            $q = (int)($a[2]/$b[2]);
            $t= array(($a[0]-$q*$b[0]), $a[1]-$q*$b[1], $a[2]-$q*$b[2]);
            $a = $b;
            $b = $t;
         }
      }
      return $invc;
   }
   
   //int p, int a, int b, int k, int[] xy
   private function kali($p, $a, $b, $k, $xy){
      $n = $k%2;
      $k = (int)($k/2);
      $R = $this->jumlah($p,$a,$b,$xy[0],$xy[1],$xy[0],$xy[1]);
      
      if($k>1){
          $R = $this->kali($p, $a, $b, $k, $R);
      }
      if($n==1){
          $R = $this->jumlah($p,$a,$b,$R[0],$R[1],$xy[0],$xy[1]);
      }
      return $R;
   }
   
   //int p, int a, int b, int x1, int y1, int x2, int y2
   private function jumlah($p, $a, $b, $x1, $y1, $x2, $y2){
      $point = array();
      $x3;
      $y3;
      if($x1==0 && $y1==0){
         $x3 = $x2;
         $y3 = $y2;
      }else if($x2==0 && $y2==0){
         $x3 = $x1;
         $y3 = $y1;
      }else if($y1 == -$y2){
         $x3 = 0;
         $y3 = 0;
      }else if($x1==$x2 && $y1==$y2){
         $inv = $this->invers(2*$y1,$p);
         $lambda = (3*pow($x1, 2)+$a)*$inv;
         $lambda = $lambda%$p;
         
         $x3 = (int)pow($lambda, 2) - 2*$x1;
         $y3 = -$y1 + (int)$lambda*($x1-$x3);
         
         $n=1;
         while($x3<0){
             $x3 = $n*$p+$x3;
             $n++;
         }
      
         $n=1;
         while($y3<0){
             $y3 = $n*$p+$y3;
             $n++;
         }
         $point[0] = $x3%$p;
         $point[1] = $y3%$p;
      }else{
         $tX = ($x2-$x1);
         $tY = ($y2-$y1);
         $lambda = $tY*($this->invers($tX,$p));
         $lambda = $lambda%$p;
         
         $x3 = (int)(pow($lambda, 2)-$x1-$x2);
         $y3 = $lambda*($x1-$x3)-$y1;
         $n=1;
         while($x3<0){
             $x3 = $n*$p+$x3;
             $n++;
         }
      
         $n=1;
         while($y3<0){
             $y3 = $n*$p+$y3;
             $n++;
         }
         $point[0] = $x3%$p;
         $point[1] = $y3%$p;
      }
      return $point;
   }
   
   private function unichr($intval) {
      return mb_convert_encoding(pack('n', $intval), 'UTF-8', 'UTF-16BE');
   }
   
   private function uniord($u) {
      $k = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
      $k1 = ord(substr($k, 0, 1));
      $k2 = ord(substr($k, 1, 1));
      return $k2 * 256 + $k1;
   }
}
?>
