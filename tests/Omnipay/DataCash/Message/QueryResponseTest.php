<?php

namespace Omnipay\DataCash\Message;

use Omnipay\Tests\TestCase;

class QueryResponseTest extends TestCase
{
    public function testQuerySuccess()
    {
        $httpResponse = $this->getMockHttpResponse('QuerySuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('123456', $response->getData()->QueryTxnResult->authcode);
        $this->assertSame('ACCEPTED', $response->getMessage());
    }

    public function testQueryFailure()
    {
        $httpResponse = $this->getMockHttpResponse('QueryFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Invalid query ref', $response->getMessage());
    }
}
