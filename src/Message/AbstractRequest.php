<?php

namespace Omnipay\BitPay\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpoint = 'https://bitpay.com/api';
    protected $testEndpoint = 'https://test.bitpay.com/api';

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    protected function getHttpMethod()
    {
        return 'POST';
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function sendData($data)
    {
        $response = $this->httpClient->request(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            array('Authorization' => 'Basic '.base64_encode($this->getApiKey().':')),
            $data
        );


        return $this->response = $this->createResponse($response, json_decode($response->getBody()->getContents(), true));
    }

    protected function createResponse($response, $data)
    {
        return $this->response = new PurchaseResponse($response, $data);
    }
}
