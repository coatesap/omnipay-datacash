<?php

namespace Omnipay\DataCash\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use SimpleXMLElement;
use Omnipay\Common\Message\AbstractRequest;

/**
 * DataCash Refund Request
 */
class QueryRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://mars.transaction.datacash.com/Transaction';
    protected $testEndpoint = 'https://testserver.datacash.com/Transaction';

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getData()
    {
        $this->validate('transactionReference');

        $data = new SimpleXMLElement('<Request/>');

        $data->Authentication->client = $this->getMerchantId();
        $data->Authentication->password = $this->getPassword();

        $data->Transaction->HistoricTxn->reference = $this->getTransactionReference();
        $data->Transaction->HistoricTxn->method = 'query';

        return $data;
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * @param SimpleXMLElement $data
     * @return \Omnipay\Common\Message\ResponseInterface|Response
     * @throws InvalidResponseException
     */
    public function sendData($data)
    {
        // post to DataCash
        $xml = $data->saveXML();
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $xml)->send();

        return $this->response = new Response($this, $httpResponse->getBody());
    }
}
