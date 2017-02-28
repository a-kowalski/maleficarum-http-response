<?php
declare(strict_types = 1);

/**
 * Tests for the \Maleficarum\Response\Http\Handler\RawHandler class.
 */

namespace Maleficarum\Response\Http\Tests\Handler;

class RawHandlerTest extends \PHPUnit\Framework\TestCase
{
    /* ------------------------------------ Method: __construct START ---------------------------------- */
    public function testConstructWithoutContentType() {
        $handler = new \Maleficarum\Response\Http\Handler\RawHandler();

        $this->assertSame('text/html', $handler->getContentType());
    }

    public function testConstructWithContentType() {
        $handler = new \Maleficarum\Response\Http\Handler\RawHandler('foo');

        $this->assertSame('foo', $handler->getContentType());
    }
    /* ------------------------------------ Method: __construct END ------------------------------------ */

    /* ------------------------------------ Method: handle START --------------------------------------- */
    public function testHandleWithoutData() {
        $handler = new \Maleficarum\Response\Http\Handler\RawHandler();
        $handler->handle();

        $this->assertSame('', $handler->getBody());
    }

    public function testHandleWithData() {
        $handler = new \Maleficarum\Response\Http\Handler\RawHandler();
        $handler->handle('foo');

        $this->assertSame('foo', $handler->getBody());
    }
    /* ------------------------------------ Method: handle END ----------------------------------------- */
}
