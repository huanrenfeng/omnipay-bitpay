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
        //return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
        return $this->getTestMode() ? 'https://test.bitpay.com'  : 'https://bitpay.com';
    }

    public function sendData($data)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'X-Accept-Version' => '2.0.0',
            //'Authorization' => 'Basic ' . base64_encode($this->getApiKey() . ':'),
        ];

        if( !is_null($data) ){
            $data['token'] = $this->getApiKey();
        }

        $body = $data ? json_encode($data) : null;

        $response = $this->httpClient->request($this->getHttpMethod(), $this->getEndpoint(), $headers, $body);

        return $this->response = $this->createResponse($this, json_decode($response->getBody()->getContents(), true));
    }

    protected function createResponse($response, $data)
    {
        return $this->response = new PurchaseResponse($response, $data);
    }
}
