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
        $xSignature = $request->header($config->signatureHeaderName);
        $xRequestId = $request->header('x-request-id') ? $request->header('x-request-id') : $_SERVER['HTTP_X_REQUEST_ID'] ;

       // $queryParams = $_GET;
        // Obtain Query params related to the request URL
        $queryParams = json_decode(file_get_contents('php://input', true));

        // Extract the "data.id" from the query params
       // $dataID = is_object($queryParams) ? $queryParams->data->id : $queryParams['data']['id'];

        if( !isset($queryParams->type, $queryParams->data) || !ctype_digit($queryParams->data->id) ){
            http_response_code(400);
            return false; 
        }

        $dataID =  $queryParams->data->id;
    
        if (! $xSignature) {
            return false;
        }

        // Separating the x-signature into parts
        $parts = explode(',', $xSignature);
        // Initializing variables to store ts and hash
        $ts = null;
        $hash = null;

        $secret = $config->signingSecret;

        if (empty($secret)) {
            throw InvalidConfig::signingSecretNotSet();
        }

        // Iterate over the values to obtain ts and v1
        foreach ($parts as $part) {
            // Split each part into key and value
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

        //Gera a string de comparação
        $manifest = "id:$dataID;request-id:$xRequestId;ts:$ts;";

        \Log::info('Manifest: '.$manifest);
        $computedSignature = hash_hmac('sha256', $manifest, $secret);

        \Log::info('Hash: '.$hash);
        \Log::info('Signature compare:'.$computedSignature);

        return hash_equals($hash, $computedSignature);
    }
}