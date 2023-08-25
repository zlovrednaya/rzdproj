<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FunctionModel;
use DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\View;
class ResultController extends Controller{

    public function continueStep(){
        session_start();
        $FunctionModel = new FunctionModel();
        $result=$FunctionModel->getTrainInfo();

        $validated_array=$FunctionModel->validateTrainInfo($result);

        if ($validated_array!=null){
            $FunctionModel->setCookies($FunctionModel->createRZDCookies(),false);
            $result_view=$FunctionModel->requestToViewArr($validated_array);
        } else{
            $result_view=null;
        } 
        //var_dump($result_view);
        return view('result', compact('result_view'));
    }

}
?>