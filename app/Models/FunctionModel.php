<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;

use DateTime;
class FunctionModel extends Model
{

  public $RZDCookies=array();

  public function __construct(){

  }
  public function buildrequest($goalrequest,$train,$departure_date_input,$RID){
    $dep_date = new DateTime($departure_date_input);
    $departure_date= $dep_date->format('d.m.Y');
    $fulldata = ['layer_id' => $goalrequest, 'dir' => 0,'code0'=>'2004000','code1'=>'2000000','dt0'=>$departure_date,'tnum0'=>$train];
    if ($RID!=null)
      $fulldata+= array('rid'=>$RID);
    return $fulldata;
  }
  public function retrievefromstring($what,$from){
    preg_match($what,$from,$matches);
    if($matches!=null) return $matches[1];
  }


    public function getCookies($server_output){
        $cookie_JSESSIONID=$this->retrievefromstring('/JSESSIONID=(.*)(;)/',$server_output);
        $cookie_lang=$this->retrievefromstring("/lang=(.*?)(;)/",$server_output);
        $cookie_AuthFlag=$this->retrievefromstring("/AuthFlag=(.*?)(;)/",$server_output);
        $cookie_ClientUid=$this->retrievefromstring("/ClientUid=(.*?)(;)/",$server_output);
        $_SESSION['JSESSIONID']=$cookie_JSESSIONID;
        $_SESSION['lang']=$cookie_lang;
        $_SESSION['AuthFlag']=$cookie_AuthFlag;
        $_SESSION['ClientUid']=$cookie_ClientUid;
  }    
  public function createRZDCookies(){
    $RZDCookies=['JSESSIONID'=>$_SESSION['JSESSIONID'],'lang'=>$_SESSION['lang'],'AuthFlag'=>$_SESSION['AuthFlag'],'ClientUid'=>$_SESSION['ClientUid']];
    return $RZDCookies;
  
  }
      public function setCookies($RZDCookies,$flag){

        foreach ($RZDCookies as $cookie=>$value)
        {
          if($flag) setcookie($cookie, $value); 
          else setcookie($cookie, null); 
        }
    }


    public function curl_send($data,$cookies,$need_header){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,'https://pass.rzd.ru/timetable/public/ru');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
      $cookies_string="";
      if($cookies!=null){
        foreach($cookies as $key=>$value){
          $cookies_string.=$key;
          $cookies_string.='=';
          $cookies_string.=$value;
          $cookies_string.=';';
        }  
        curl_setopt($ch, CURLOPT_COOKIE,$cookies_string);
      }
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      if($need_header){
        curl_setopt($ch, CURLOPT_HEADER,1);
      }
      curl_setopt($ch, CURLOPT_VERBOSE, true); 
      $server_output=curl_exec($ch);
      return $server_output;
}

    //first step pass to homecontroller
    public function getRID(){
      $data=$this->buildrequest(5764,Redis::get('train'),Redis::get('departure_date'),null);
      $server_output=$this->curl_send($data,null,true);
      $this->getCookies($server_output);
      $cookies=$this->createRZDCookies();
      $this->setCookies($cookies,1);
      $RID=$this->retrievefromstring('/"RID":(.*)(,)/',$server_output);
      $_SESSION['RID']=$RID;
      return $RID; 
  }    
  //second step
    public function getTrainInfo(){
      $data=$this->buildrequest(5764,Redis::get('train'),Redis::get('departure_date'),$_SESSION['RID']);
      $RZDCookies=$this->createRZDCookies();
      $server_output_train =$this->curl_send($data,$RZDCookies,false);
      return $server_output_train;
    }
    public function validateTrainInfo($result){
      if(strpos($result,"FAIL")==0){
        return json_decode($result);
      } else 
        return null;
    }
    public function requestToViewArr($array_places){
      //$array_places=json_decode($result);
      //var_dump($array_places);
      $coupe_rating=array('2Т','2Х','2Б','2Э','2Ф','2Ц','2А','2Д','2К','2Н','2У','2Л');
       $five_tariffs=array();
       $five_best_dn=array();
       $five_carts=array();
      $arr_to_view=array();
      $k=0; //5 seats counter 
      $previous_rating=13;
      foreach ($array_places->lst as $lst){
        $arr_to_view['train_info']=[0=>$lst->number,1=>$lst->date0,2=>$lst->station0,3=>$lst->station1];
        foreach($lst->cars as $cars){
          $class=$cars->clsType;
          for($i=0;$i<count($coupe_rating);$i++){
            if($class==$coupe_rating[$i]){
              $places_rating=$i;
              //echo " <br>placesrating:". $places_rating . $cars->tariff ." up". $cars->tariff2 . "places" . $cars->places;
              //best class by rating
              if($previous_rating>$places_rating){
                $previous_rating=$places_rating;
                $class1=$class;
                $places=$cars->places;
                $carnum=$cars->cnumber;
                $tariff=$cars->tariff;
                $tariff2=$cars->tariff2;
                
                //echo "<br> TRAIN CARS INFO:" . $carnum . " " . $places . " up". $tariff ." down".$tariff2." " . $class1 . " ";
                $arr_places=array();
                $j=0;  //counter of places
                //receive array of places
                $comma_separated=explode(',',$places);
                for($i=0;$i<count($comma_separated);$i++){
                  //places with format from-to
                  $best_before=preg_match_all("/(.*)(-)/",$comma_separated[$i],$matchesbef);
                  $best_after=preg_match_all("/(-)(.*)/",$comma_separated[$i],$matchesaft);
                  if($matchesbef[1]!=null&&$matchesaft[2]!=null){
                    while(((int)$matchesaft[2][0]-(int)$matchesbef[1][0])>=0){
                      $arr_places[$j]=(int)$matchesbef[1][0];
                      (int)$matchesbef[1][0]++;
                      $j++;
                    }
                  } else{
                    $arr_places[$j]=(int)$comma_separated[$i];
                    $j++;
                  }
                }
                for($i=0;$i<count($arr_places);$i++){
                  if($k==5)
                  break;
                  //нечетные - нижние
                  //+два соседних не равно 0
                  //крч - тариф 2 - нижнее - тариф 1 -верхнее
                  $t=$i; //чтоб не бесил первый индекс -1
                  $e=$i;
                  if($i==0) {$t=1;}
                  if($i==count($arr_places)-1){$e=count($arr_places)-2;}
                  if(($arr_places[$i]%2!=0)&&$arr_places[$t-1]==$arr_places[$i]-1&&$arr_places[$t+1]==$arr_places[$i]+1&&$tariff2!=null){
                    $five_best_dn[$k]=$arr_places[$i];
                    $five_tariffs[$k]=$tariff=$cars->tariff2;
                    $five_carts[$k]=$carnum=$cars->cnumber;
                    $arr_to_view['places']=$five_best_dn;
                    $arr_to_view['cars']=$five_carts;
                    $arr_to_view['tariffs']=$five_tariffs;
                    $k++;
                                     } 
                }
              }
            }
        }
      }
    } 
    if ($five_best_dn==null)
    {
      $arr_to_view['places']=null;
    }

    return $arr_to_view; 
  } 

    
}
