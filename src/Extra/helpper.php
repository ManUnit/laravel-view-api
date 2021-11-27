<?php  

use Tbp\Viewapi\Jsonview ; 
use Illuminate\Http\Request  ;

if (! function_exists('apiview')) {
function apiview(...$keys)
    {   
       // print_r(apache_request_headers()) ;
        $envappkey = 'API_APP_KEY';
        $appkey = 'api-app-key';
        
        // print_r(apache_request_headers()['api-app-key']);
        // print(env($envappkey,'null') );
        
        if (!isset(apache_request_headers()[$appkey])){  return response()->json( ['message'=>'Error  API reqeuest rejected'] , 400 ) ; } 
        if (isset(apache_request_headers()[$appkey]) && apache_request_headers()[$appkey] ==  env($envappkey,'null')  ){   return response()->json( [ $keys[0] => $keys[1] ] , 200);
            // if( gettype($keys[0]) == 'string'){  
            //        return response()->json( [ $keys[0] => $keys[1] ] , 200); 
            //    }else{ 
            //        return response()->json(  $keys ,200 ); 
            //    }
        }else {  
               return response()->json( ['message'=>'Error API call rejected  '] ,400 ); 
        }
    }
}

if (! function_exists('callapi')) {
    function callapi($method, $url, $data , $apikey){
            $curl = curl_init();
            switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
            }
            // OPTIONS:
            // key koeiw4rlw94rdsor984dlmuetn
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'API-APP-KEY: '. $apikey,
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            // EXECUTE:
            $result = curl_exec($curl);
            if(!$result){die("Connection Failure");}
            curl_close($curl);
            return $result;
        }
    }

?>
