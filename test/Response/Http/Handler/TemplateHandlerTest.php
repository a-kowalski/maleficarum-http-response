<?php
declare(strict_types = 1);

/**
 * Tests for the \Maleficarum\Response\Http\Handler\TemplateHandler class.
 */

namespace Maleficarum\Response\Http\Tests\Handler;

class TemplateHandlerTest extends \PHPUnit\Framework\TestCase
{
    /* ------------------------------------ Method: handle START --------------------------------------- */
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testHandleWithoutTemplate() {
        $handler = $this
            ->getMockBuilder('Maleficarum\Response\Http\Handler\TemplateHandler')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $handler->handle();
    }

    public function testHandle() {
        $view = $this->createMock('Twig_Environment');
        $view
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo('bar/baz.html'),
                $this->equalTo(['foo' => 'bar'])
            )
            ->willReturn('foo');

        $handler = new \Maleficarum\Response\Http\Handler\TemplateHandler($view);
        $handler->handle('bar/baz', ['foo' => 'bar']);

        $this->assertSame('foo', $handler->getBody());
    }
    /* ------------------------------------ Method: handle END ----------------------------------------- */

    /* ------------------------------------ Method: getContentType START ------------------------------- */
    public function testGetContentType() {
        $handler = $this
            ->getMockBuilder('Maleficarum\Response\Http\Handler\TemplateHandler')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->assertSame('text/html', $handler->getContentType());
    }
    /* ------------------------------------ Method: getContentType END --------------------------------- */
}
