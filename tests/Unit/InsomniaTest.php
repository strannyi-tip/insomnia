<?php

namespace Unit;

use Codeception\Test\Unit;
use StrannyiTip\Insomnia\Core\Insomnia;
use StrannyiTip\Insomnia\Core\Proxy;

class InsomniaTest extends Unit
{
    protected Insomnia $insomnia;

    protected function _before(): void
    {
        $this->insomnia = new Insomnia();
    }

    public function testGet()
    {
        $result = $this->insomnia
                        ->connect('http://127.0.0.1', 8000)
                        ->get();
        $this->assertTrue($result, 'Test for GET request completed with TRUE result');
        $this->assertArrayHasKey('response', $this->insomnia->asArray(), 'Test for correct response received');
        $this->assertTrue($this->insomnia->asObject()->has('response'), 'Test for get result as object is correct');
        $this->assertEquals([
            'response' => 'Your ip is 127.0.0.1',
        ], $this->insomnia->asArray());
    }

    public function testSimplePost()
    {
        $result = $this->insomnia
                    ->connect('http://127.0.0.1', 8000)
                    ->post([
                        'name' => 'Hungry Tester',
                    ]);
        $this->assertTrue($result, 'Test for POST request completed with TRUE result');
        $this->assertArrayHasKey('response', $this->insomnia->asArray(), 'Test for correct response received');
        $this->assertEquals([
            'response' => 'Your name is Hungry Tester',
        ], $this->insomnia->asArray());
        $this->assertEquals('Your name is Hungry Tester', $this->insomnia->asObject()->get('response'));
    }

    public function testFileSend()
    {
        $result = $this->insomnia
                        ->connect('http://127.0.0.1', 8000)
                        ->addFile(__DIR__ . '/data/images/test.png')
                        ->post(['my_name' => 'Work For Food']);
        $this->assertTrue($result, 'Test for POST request completed with TRUE result');
        $this->assertEquals([
            'name' => __DIR__ . '/data/images/test.png',
            'mime' => \mime_content_type(__DIR__ . '/data/images/test.png'),
            'postname' => \basename(__DIR__ . '/data/images/test.png'),
        ], $this->insomnia->asObject()->get('response')['file'], 'Test for check response is correct');
        $this->assertEquals('Work For Food', $this->insomnia->asObject()->get('response')['name'], 'Test for check response is correct');
    }

    public function testSendCookies()
    {
        $result = $this->insomnia
                        ->connect('http://127.0.0.1', 8000)
                        ->addCookie('token', md5('token'))
                        ->addCookie('name', 'Nautilus Pompilius')
                        ->post(['cookies' => true]);
        $this->assertTrue($result, 'Test for POST request completed with TRUE result');
        $this->assertEquals([
            'token' => 'Your token: ' . md5('token'),
            'name' => 'Your name: Nautilus Pompilius',
        ], $this->insomnia->asObject()->get('response'), 'Test for cookie send work correctly');
    }

    public function testSendHeader()
    {
        $result = $this->insomnia
            ->connect('http://127.0.0.1', 8000)
            ->addHeader('User-Agent: SuperMegaBrowser Ubuntu x128')
            ->post(['headers' => true]);
        $this->assertTrue($result, 'Test for POST request completed with TRUE result');
        $this->assertEquals('Your agent: SuperMegaBrowser Ubuntu x128', $this->insomnia->asObject()->get('response'), 'Test is header setted correctly');
    }

    public function testUseProxy()
    {
        $proxy = new Proxy();
        $proxy
            ->setAddress('127.0.0.1')
            ->setPort(33333);
        $result = $this->insomnia
            ->connect('http://127.0.0.1', 8000)
            ->setProxy($proxy)
            ->post(['name' => 'SOAD']);
        $this->assertTrue($result, 'Test for POST request completed with TRUE result');
        $this->assertEquals('PROXY: Your name is SOAD', $this->insomnia->asObject()->get('response'), 'Test for proxy work correctly');
    }
}
