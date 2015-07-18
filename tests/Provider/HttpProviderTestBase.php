<?php

namespace Distance\Tests\Provider;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class HttpProviderTestBase extends ProviderTestBase
{
    protected function getClientWithBody($body)
    {
        $client = new Client();

        $mock = new Mock([
            new Response(200, [], Stream::factory($body)),
        ]);

        $client->getEmitter()->attach($mock);

        return $client;
    }

    protected function getClientWithException()
    {
        $client = $this
            ->getMockBuilder('GuzzleHttp\Client')
            ->setMethods(['send'])
            ->getMock();

        $client->method('send')
            ->will( $this->throwException(
                new \Exception('shold not requested')
            ) );

        $mock = new Mock([
            new Response(200, []),
        ]);

        $client->getEmitter()->attach($mock);

        return $client;
    }
}
