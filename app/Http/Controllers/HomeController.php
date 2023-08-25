<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DBModel;
use App\Models\FunctionModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
    }
    
    
    public function submitCheck( $submitted_data){
        $today=today();
        $day45=date_add(today(),date_interval_create_from_date_string("45 days"));
        $validation=$submitted_data->validate([
            'train' => 'required|min:3',
        //not works with "required|date|before:$day45" why i dont know ->
            'departure_date'=> 'required|date'
        ]);
    }
    public function DBSave($submitted_data){    
        $DBfields = new DBModel();
        DB::table('rzd_requests')->insert([
            'request_data' => $submitted_data->input('train'),
            'created_at'=> DB::raw('CURRENT_TIMESTAMP(0)')
        ]);
        
        
    }

    public function FirstRZDRequest($submitted_data)
    { 
        session_start();
        $FunctionModel = new FunctionModel();
        $train=$submitted_data->input('train');
        $departure_date=$submitted_data->input('departure_date');
        Redis::set('train', $train);
        Redis::set('departure_date', $departure_date);
        $RID = $FunctionModel->getRID();
        //echo "<br>RID: ".$RID;

       if($RID){
            return redirect()->route('step2get');
        }
       else return redirect()->route('home'); 
    }
    
    public function allExecute(Request $submitted_data){
        $this->submitCheck($submitted_data);
        
        $this->DBSave($submitted_data);
        return $this->FirstRZDRequest($submitted_data);
      
    }
     
       
}
    


?>