<?php
declare(strict_types = 1);

/**
 * Tests for the \Maleficarum\Response\Http\Handler\JsonHandler class.
 */

namespace Maleficarum\Response\Http\Tests\Handler;

use Maleficarum\Response\Plugin\AbstractPlugin;

class JsonHandlerTest extends \PHPUnit\Framework\TestCase
{
    /* ------------------------------------ Method: getContentType START ------------------------------- */
    /**
     * @dataProvider handleDataProvider
     */
    public function testHandle($data, $meta, $success, $expected) {
        $handler = new \Maleficarum\Response\Http\Handler\JsonHandler();
        $handler->handle($data, $meta, $success);

        $body = $this->getProperty($handler, 'body');

        $this->assertEquals($expected, $body);
    }

    public function handleDataProvider() {
        return [
            [[], [], true, ['meta' => ['status' => 'success'], 'data' => []]],
            [[], ['bar' => 'baz'], true, ['meta' => ['status' => 'success', 'bar' => 'baz'], 'data' => []]],
            [[], [], false, ['meta' => ['status' => 'failure'], 'data' => []]]
        ];
    }

    public function testGetBodyWithPlugin() {
        $handler = new \Maleficarum\Response\Http\Handler\JsonHandler();
        $handler->handle();

        $handler->addPlugin(new class extends AbstractPlugin {
            public function getName(): string {
                return 'foo';
            }

            public function execute() {
                return 'bar';
            }
        });

        $this->assertEquals('{"meta":{"status":"success","foo":"bar"},"data":[]}', $handler->getBody());
    }

    public function testGetContentType() {
        $handler = $this
            ->getMockBuilder('Maleficarum\Response\Http\Handler\JsonHandler')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->assertSame('application/json', $handler->getContentType());
    }
    /* ------------------------------------ Method: getContentType END --------------------------------- */

    /* ------------------------------------ Helper methods START --------------------------------------- */
    /**
     * Set object property value
     *
     * @param object $object
     * @param string $property
     * @param mixed $value
     */
    private function setProperty($object, string $property, $value) {
        $reflection = new \ReflectionProperty($object, $property);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
        $reflection->setAccessible(false);
    }

    /**
     * Get object property value
     *
     * @param object $object
     * @param string $property
     *
     * @return mixed
     */
    private function getProperty($object, string $property) {
        $reflection = new \ReflectionProperty($object, $property);
        $reflection->setAccessible(true);
        $value = $reflection->getValue($object);
        $reflection->setAccessible(false);

        return $value;
    }
    /* ------------------------------------ Helper methods END ----------------------------------------- */
}
