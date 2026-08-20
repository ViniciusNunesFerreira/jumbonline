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
        $xSignature = $request->header('x-signature');
        $xRequestId = $request->header('x-request-id');
        $dataId = $request->query('data.id') ?? $request->input('data.id');

        if (! $xSignature || ! $dataId) {
            return false;
        }

        $secret = $config->signingSecret;

        if (empty($secret)) {
            throw InvalidConfig::signingSecretNotSet();
        }

        $ts = null;
        $hash = null;

        foreach (explode(',', $xSignature) as $part) {
            $keyValue = explode('=', $part, 2);
            if (count($keyValue) === 2) {
                $key = trim($keyValue[0]);
                $value = trim($keyValue[1]);
                if ($key === 'ts') {
                    $ts = $value;
                } elseif ($key === 'v1') {
                    $hash = $value;
                }
            }
        }

        if (! $ts || ! $hash) {
            return false;
        }

        // MP exige o data.id em minúsculas no manifesto — detalhe confirmado na doc oficial
        $manifest = "id:" . strtolower($dataId) . ";request-id:{$xRequestId};ts:{$ts};";
        $computedSignature = hash_hmac('sha256', $manifest, $secret);

        return hash_equals($computedSignature, $hash);
    }
}