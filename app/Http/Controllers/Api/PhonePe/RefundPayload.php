<?php

namespace App\Http\Controllers\Api\PhonePe;

class RefundPayload
{
    public $merchantId;
    public $transactionId;
    public $providerReferenceId;
    public $amount;
    public $merchantOrderId;
    public $message;
}
