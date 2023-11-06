<?php

namespace App\DependencyInjection\Framework;

use Stripe\StripeClient;

trait StripeClientDI
{
    protected StripeClient $stripeClient;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setStripeClient()
    {
        $this->stripeClient = new StripeClient($_ENV['STRIPE_SECRET']);
    }

}
