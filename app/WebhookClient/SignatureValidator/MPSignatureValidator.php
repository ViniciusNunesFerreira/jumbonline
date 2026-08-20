<?php

namespace App\WebhookClient\SignatureValidator;

use App\Services\MercadoPagoWebhookSignature;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Illuminate\Http\Request;
use Spatie\WebhookClient\Exceptions\InvalidConfig;
use Spatie\WebhookClient\WebhookConfig;

class MPSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        $secret = $config->signingSecret;

        if (empty($secret)) {
            throw InvalidConfig::signingSecretNotSet();
        }

        return MercadoPagoWebhookSignature::isValid($request, $secret);
    }
}