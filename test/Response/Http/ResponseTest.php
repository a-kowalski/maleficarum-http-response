<?php
declare(strict_types = 1);

/**
 * Tests for the \Maleficarum\Response\Http\Response class.
 */

namespace Maleficarum\Response\Http\Tests;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
    /* ------------------------------------ Method: __construct START ---------------------------------- */
    public function testConstruct() {
        $handler = $this
            ->getMockBuilder('Maleficarum\Response\Http\Handler\AbstractHandler')
            ->disableOriginalConstructor()
            ->getMock();
        $phalconResponse = $this
            ->getMockBuilder('Phalcon\Http\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $response = $this
            ->getMockBuilder('Maleficarum\Response\Http\Response')
            ->setMethods(['setStatusCode'])
            ->setConstructorArgs([$phalconResponse, $handler])
            ->getMock();
        $response
            ->expects($this->once())
            ->method('setStatusCode')
            ->with(
                $this->equalTo(200)
            );

        $response->__construct($phalconResponse, $handler);
    }
    /* ------------------------------------ Method: __construct END ------------------------------------ */

    /* ------------------------------------ Method: render START --------------------------------------- */
    public function testRender() {
        $handler = $this
            ->getMockBuilder('Maleficarum\Response\Http\Handler\AbstractHandler')
            ->setMethods(['render'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $handler
            ->expects($this->once())
            ->method('handle')
            ->with(
                $this->equalTo('foo'),
                $this->equalTo('bar')
            );

        $phalconResponse = $this
            ->getMockBuilder('Phalcon\Http\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $response = $this
            ->getMockBuilder('Maleficarum\Response\Http\Response')
            ->setMethods(['setStatusCode'])
            ->setConstructorArgs([$phalconResponse, $handler])
            ->getMock();

        $response->render('foo', 'bar');
    }
    /* ------------------------------------ Method: render END ----------------------------------------- */

    /* ------------------------------------ Method: output START --------------------------------------- */
    public function testOutput() {
        $handler = $this
            ->getMockBuilder('Maleficarum\Response\Http\Handler\AbstractHandler')
            ->setMethods(['getContentType', 'getBody'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $handler
            ->expects($this->once())
            ->method('getContentType')
            ->willReturn('foo');
        $handler
            ->expects($this->once())
            ->method('getBody')
            ->willReturn('bar');

        $phalconResponse = $this
            ->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(['setHeader', 'setContent', 'send', 'setStatusCode'])
            ->disableOriginalConstructor()
            ->getMock();
        $phalconResponse
            ->expects($this->once())
            ->method('setHeader')
            ->with(
                $this->equalTo('Content-Type'),
                $this->equalTo('foo')
            );
        $phalconResponse
            ->expects($this->once())
            ->method('setContent')
            ->with(
                $this->equalTo('bar')
            )
            ->willReturn($phalconResponse);
        $phalconResponse
            ->expects($this->once())
            ->method('send');

        $response = $this
            ->getMockBuilder('Maleficarum\Response\Http\Response')
            ->setMethods(['setStatusCode'])
            ->setConstructorArgs([$phalconResponse, $handler])
            ->getMock();

        $response->output();
    }
    /* ------------------------------------ Method: output END ----------------------------------------- */
    
    /* ------------------------------------ Method: redirect START ------------------------------------- */
    public function testRedirectWithoutTermination() {
        $handler = $this
            ->getMockBuilder('Maleficarum\Response\Http\Handler\AbstractHandler')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $phalconResponse = $this
            ->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(['redirect', 'setStatusCode'])
            ->disableOriginalConstructor()
            ->getMock();
        $phalconResponse
            ->expects($this->once())
            ->method('redirect')
            ->with(
                $this->equalTo('foo')
            );

        $response = $this
            ->getMockBuilder('Maleficarum\Response\Http\Response')
            ->setMethods(['setStatusCode'])
            ->setConstructorArgs([$phalconResponse, $handler])
            ->getMock();

        $response->redirect('foo', false);
    }

    public function testRedirectWithTermination() {
        $handler = $this
            ->getMockBuilder('Maleficarum\Response\Http\Handler\AbstractHandler')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $phalconResponse = $this
            ->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(['redirect', 'send'])
            ->disableOriginalConstructor()
            ->getMock();
        $phalconResponse
            ->expects($this->once())
            ->method('redirect')
            ->with(
                $this->equalTo('foo')
            );

        $response = $this
            ->getMockBuilder('Maleficarum\Response\Http\Response')
            ->setMethods(['setStatusCode', 'terminate'])
            ->setConstructorArgs([$phalconResponse, $handler])
            ->getMock();
        $response
            ->expects($this->once())
            ->method('terminate');

        $response->redirect('foo');
    }
    /* ------------------------------------ Method: redirect END --------------------------------------- */

    /* ------------------------------------ Method: addHeader START ------------------------------------ */
    public function testAddHeader() {
        $handler = $this
            ->getMockBuilder('Maleficarum\Response\Http\Handler\AbstractHandler')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $phalconResponse = $this
            ->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(['setHeader'])
            ->disableOriginalConstructor()
            ->getMock();
        $phalconResponse
            ->expects($this->once())
            ->method('setHeader')
            ->with(
                $this->equalTo('foo'),
                $this->equalTo('bar')
            );

        $response = $this
            ->getMockBuilder('Maleficarum\Response\Http\Response')
            ->setMethods(['setStatusCode'])
            ->setConstructorArgs([$phalconResponse, $handler])
            ->getMock();

        $response->addHeader('foo', 'bar');
    }
    /* ------------------------------------ Method: addHeader END -------------------------------------- */

    /* ------------------------------------ Method: clearHeaders START --------------------------------- */
    public function testClearHeaders() {
        $handler = $this
            ->getMockBuilder('Maleficarum\Response\Http\Handler\AbstractHandler')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $phalconResponse = $this
            ->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(['resetHeaders'])
            ->disableOriginalConstructor()
            ->getMock();
        $phalconResponse
            ->expects($this->once())
            ->method('resetHeaders');

        $response = $this
            ->getMockBuilder('Maleficarum\Response\Http\Response')
            ->setMethods(['setStatusCode'])
            ->setConstructorArgs([$phalconResponse, $handler])
            ->getMock();

        $response->clearHeaders();
    }
    /* ------------------------------------ Method: clearHeaders END ----------------------------------- */

    /* ------------------------------------ Method: isSent START --------------------------------------- */
    public function testIsSent() {
        $handler = $this
            ->getMockBuilder('Maleficarum\Response\Http\Handler\AbstractHandler')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $phalconResponse = $this
            ->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(['isSent'])
            ->disableOriginalConstructor()
            ->getMock();
        $phalconResponse
            ->expects($this->once())
            ->method('isSent')
            ->willReturn(true);

        $response = $this
            ->getMockBuilder('Maleficarum\Response\Http\Response')
            ->setMethods(['setStatusCode'])
            ->setConstructorArgs([$phalconResponse, $handler])
            ->getMock();

        $this->assertTrue($response->isSent());
    }
    /* ------------------------------------ Method: isSent END ----------------------------------------- */

    /* ------------------------------------ Method: setStatusCode START -------------------------------- */
    public function testSetStatusCode() {
        $handler = $this
            ->getMockBuilder('Maleficarum\Response\Http\Handler\AbstractHandler')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $phalconResponse = $this
            ->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(['setStatusCode'])
            ->disableOriginalConstructor()
            ->getMock();

        $phalconResponse
            ->expects($this->exactly(2))
            ->method('setStatusCode')
            ->with(
                $this->equalTo(200),
                $this->isType('string')
            )
            ->willReturn($phalconResponse);

        $response = new \Maleficarum\Response\Http\Response($phalconResponse, $handler);
        $response->setStatusCode(200);
    }
    /* ------------------------------------ Method: setStatusCode END ---------------------------------- */
}
