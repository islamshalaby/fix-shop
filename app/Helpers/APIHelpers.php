<?php
  namespace App\Helpers;
  class APIHelpers {

      // format the response for the api
      public static function createApiResponse($is_error , $code , $message_en , $message_ar , $content , $lang){
          if($lang == 'en'){
            $message = $message_en;
          }else{
            $message = $message_ar;
          }
          $result = [];
          if($is_error){
              $result['success'] = false;
              $result['code'] = $code;
              $result['message'] = $message;
          }else{
              $result['success'] = true;
              $result['code'] = $code;
              if($content == null){
                  $result['message'] = $message;
                  $result['data'] = [];
              }else{
                  $result['data'] = $content;
              }
          }
          return $result;
      }

    // calculate the distance
    public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {
      if (($lat1 == $lat2) && ($lon1 == $lon2)) {
        return 0;
      }
      else {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
    
        if ($unit == "K") {
          return ($miles * 1.609344);
        } else if ($unit == "N") {
          return ($miles * 0.8684);
        } else {
          return $miles;
        }
      }
    }

    // send fcm notification
    public static function send_notification($title , $body , $image , $data , $token){
      
      $message= $body;
      $title= $title;
      $image = $image;
      $path_to_fcm='https://fcm.googleapis.com/fcm/send';
      $server_key="AAAAWNTnPSo:APA91bEbGZ-ZGPm7nEl6m9zM-73HrzwKS5uxHWteDcsdFxuzoZmVlOJV1DYUmP6i4KMkYZFrV3NHc5hL77BPRVMGYoq16XyUCmHCvgS4LojIELaNsJyWB2j9YHABTrnCEBsV6RV30bOX";

      $headers = array(
          'Authorization:key=' .$server_key,
          'Content-Type:application/json'
      );

      $fields =array('registration_ids'=>$token,  
                      'notification'=>array('title'=>$title,'body'=>$message , 'image'=>$image));  

      $payload =json_encode($fields);
      $curl_session =curl_init();
      curl_setopt($curl_session,CURLOPT_URL, $path_to_fcm);
      curl_setopt($curl_session,CURLOPT_POST, true);
      curl_setopt($curl_session,CURLOPT_HTTPHEADER, $headers);
      curl_setopt($curl_session,CURLOPT_RETURNTRANSFER,true);
      curl_setopt($curl_session,CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl_session,CURLOPT_IPRESOLVE, CURLOPT_IPRESOLVE);
      curl_setopt($curl_session,CURLOPT_POSTFIELDS, $payload);
      $result=curl_exec($curl_session);
      curl_close($curl_session);
      return $result;
    }

    // convert currency
    public static function converCurruncy2($from, $to) {
      // Fetching JSON
      $req_url = 'https://v6.exchangerate-api.com/v6/d265b7845f8c648be11097da/pair/' . $from . '/' . $to;
      $response_json = file_get_contents($req_url);

      // Decoding
      $response = json_decode($response_json);

      return ['value' => $response->conversion_rate];
    }

    // post json api
    public static function fetchApi($url, $data=[], $type='json', $method='post') {
      $path=$url;
      $token='$2y$12$ZtgKLOyfvyXH33JE67Ei0.qupt771t62d21M4/OJumBmsZ1bexxpNo';
      $headers = array(
          'Authorization:' .$token
      );
      if ($type == 'json') {
        array_push($headers, 'Content-Type:application/json');
      }
      
      
      $curl_session =curl_init();
      curl_setopt($curl_session,CURLOPT_URL, $path);
      if ($method == 'post') {
        $fields =$data;
        $payload =json_encode($fields);
        curl_setopt($curl_session,CURLOPT_POST, true);
        curl_setopt($curl_session,CURLOPT_POSTFIELDS, $payload);
      }
      curl_setopt($curl_session,CURLOPT_RETURNTRANSFER,true);
      curl_setopt($curl_session,CURLOPT_HTTPHEADER, $headers);
      curl_setopt($curl_session,CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl_session,CURLOPT_IPRESOLVE, CURLOPT_IPRESOLVE);
      
      $result=curl_exec($curl_session);
      curl_close($curl_session);
      $result = json_decode($result);
      return $result;
    }

  }
?>