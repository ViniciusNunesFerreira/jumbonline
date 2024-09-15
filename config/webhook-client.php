<?php

return [
    'configs' => [
        // Do not change the order of every services declared here.
        [
            'name' => 'mercadopago',
            'signing_secret' => env('MERCADOPAGO_WEBHOOK_SECRET'),
            'signature_header_name' => 'x-signature',
            'signature_validator' => App\WebhookClient\SignatureValidator\MPSignatureValidator::class, 
            'webhook_profile' => \Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile::class,
            'webhook_response' => \Spatie\WebhookClient\WebhookResponse\DefaultRespondsTo::class,
            'webhook_model' => \Spatie\WebhookClient\Models\WebhookCall::class,
            'process_webhook_job' => \App\Jobs\MercadopagoWebhooks\ProcessMercadoPagoWebhookJob::class,
        ],
    ],

    // \Spatie\WebhookClient\SignatureValidator\DefaultSignatureValidator::class,
    /*
     * The integer amount of days after which models should be deleted.
     *
     * 7 deletes all records after 1 week. Set to null if no models should be deleted.
     */
    'delete_after_days' => 30,
];
