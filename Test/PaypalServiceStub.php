<?php

namespace Beelab\PaypalBundle\Test;

use Beelab\PaypalBundle\Paypal\Service;
use Guzzle\Http\Client;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\PayPal\Message\ExpressAuthorizeRequest;
use Omnipay\PayPal\Message\ExpressAuthorizeResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @codeCoverageIgnore
 */
class PaypalServiceStub extends Service
{
    /**
     * Start transaction. You need to call setTransaction() before.
     *
     * @return ResponseInterface
     */
    public function start(): ResponseInterface
    {
        $request = new ExpressAuthorizeRequest(new Client(), new Request());

        $response = new ExpressAuthorizeResponse($request, 'ACK=Success&TOKEN=pippo');
        $this->transaction->setToken($response->getTransactionReference());

        return $response;
    }

    /**
     * Complete transaction. You need to call setTransaction() before.
     */
    public function complete(): void
    {
    }
}
