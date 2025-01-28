<?php

namespace App\WebhookClient\SignatureValidator;

use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Illuminate\Http\Request;
use Spatie\WebhookClient\Exceptions\InvalidConfig;
use Spatie\WebhookClient\WebhookConfig;

class MPSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
       
      /*  $xSignature = $request->header('x-signature') ? $request->header('x-signature') : $_SERVER['HTTP_X_SIGNATURE'];
        $xRequestId = $request->header('x-request-id') ? $request->header('x-request-id') : $_SERVER['HTTP_X_REQUEST_ID'] ;

       
        $queryParams = json_decode(file_get_contents('php://input', true));

        if( !isset($queryParams->type, $queryParams->data) || !ctype_digit($queryParams->data->id) ){
            http_response_code(400);
            return false; 
        }

        $dataID =  $queryParams->data->id;
    
        if (! $xSignature) {
            return false;
        }

       
        $parts = explode(',', $xSignature);
        
        $ts = null;
        $hash = null;

        $secret = $config->signingSecret;

        if (empty($secret)) {
            throw InvalidConfig::signingSecretNotSet();
        }

        
        foreach ($parts as $part) {
            
            $keyValue = explode('=', $part, 2);
            if (count($keyValue) == 2) {
                $key = trim($keyValue[0]);
                $value = trim($keyValue[1]);
                if ($key === "ts") {
                    $ts = $value;
                } elseif ($key === "v1") {
                    $hash = $value;
                }
            }
        }

        
        $manifest = "id:$dataID;request-id:$xRequestId;ts:$ts;";
        $computedSignature = hash_hmac('sha256', $manifest, $secret);

        if(hash_equals($hash, $computedSignature) == 1){
            return true;
        } */

        return true;

    }
}