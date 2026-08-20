<?php

namespace App\Services;

use Illuminate\Http\Request;

class MercadoPagoWebhookSignature
{
    public static function isValid(Request $request, ?string $secret): bool
    {
        if (empty($secret)) {
            return false;
        }

        $xSignature = $request->header('x-signature');
        $xRequestId = $request->header('x-request-id');
        $dataId = $request->query('data.id') ?? $request->input('data.id');

        if (! $xSignature || ! $dataId) {
            return false;
        }

        $ts = null;
        $hash = null;

        foreach (explode(',', $xSignature) as $part) {
            $keyValue = explode('=', $part, 2);
            if (count($keyValue) === 2) {
                $key = trim($keyValue[0]);
                $value = trim($keyValue[1]);
                if ($key === 'ts') { $ts = $value; }
                elseif ($key === 'v1') { $hash = $value; }
            }
        }

        if (! $ts || ! $hash) {
            return false;
        }

        $manifest = "id:" . strtolower($dataId) . ";request-id:{$xRequestId};ts:{$ts};";
        $computedSignature = hash_hmac('sha256', $manifest, $secret);

        return hash_equals($computedSignature, $hash);
    }
}