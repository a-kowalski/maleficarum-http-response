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
        $view = $this
            ->getMockBuilder('Phalcon\Mvc\ViewBaseInterface')
            ->setMethods(['getViewsDir'])
            ->getMockForAbstractClass();
        $view
            ->expects($this->once())
            ->method('getViewsDir')
            ->willReturn('/foo/');

        $volt = $this
            ->getMockBuilder('Phalcon\Mvc\View\Engine\Volt')
            ->setMethods(['getView', 'render'])
            ->disableOriginalConstructor()
            ->getMock();

        $volt
            ->expects($this->once())
            ->method('getView')
            ->willReturn($view);

        $volt
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo('/foo/bar/baz.phtml'),
                $this->equalTo(['foo' => 'bar'])
            )
            ->willReturn('foo');

        $handler = new \Maleficarum\Response\Http\Handler\TemplateHandler($volt);
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
