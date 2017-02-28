<?php
declare(strict_types = 1);

/**
 * Tests for the \Maleficarum\Response\Http\Handler\JsonHandler class.
 */

namespace Maleficarum\Response\Http\Tests\Handler;

class JsonHandlerTest extends \PHPUnit\Framework\TestCase
{
    /* ------------------------------------ Method: handle START --------------------------------------- */
    public function testHandleWithoutConfig() {
        $handler = new \Maleficarum\Response\Http\Handler\JsonHandler();
        $handler->handle();
        
        $body = $this->getProperty($handler, 'body');

        $this->assertEquals(['meta' => ['status' => 'success', 'version' => null], 'data' => []], $body);
    }

    /**
     * @dataProvider handleDataProvider
     */
    public function testHandleWithConfig($data, $meta, $success, $expected) {
        $config = $this
            ->getMockBuilder('Maleficarum\Config\AbstractConfig')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->setProperty($config, 'data', ['global' => ['version' => 1.0]]);
        
        $handler = new \Maleficarum\Response\Http\Handler\JsonHandler();
        $handler->setConfig($config);
        $handler->handle($data, $meta, $success);

        $body = $this->getProperty($handler, 'body');

        $this->assertEquals($expected, $body);
    }

    public function handleDataProvider() {
        return [
            [[], [], true, ['meta' => ['status' => 'success', 'version' => 1.0], 'data' => []]],
            [[], ['bar' => 'baz'], true, ['meta' => ['status' => 'success', 'version' => 1.0, 'bar' => 'baz'], 'data' => []]],
            [[], [], false, ['meta' => ['status' => 'failure', 'version' => 1.0], 'data' => []]]
        ];
    }
    /* ------------------------------------ Method: handle END ----------------------------------------- */

    /* ------------------------------------ Method: getBody START -------------------------------------- */
    public function testGetBodyWithoutProfiler() {
        $handler = new \Maleficarum\Response\Http\Handler\JsonHandler();
        $this->setProperty($handler, 'body', ['foo' => 'bar']);

        $this->assertSame('{"foo":"bar"}', $handler->getBody());
    }

    public function testGetBodyWithProfiler() {
        $timeProfiler = $this
            ->getMockBuilder('Maleficarum\Profiler\Time')
            ->setMethods(['getProfile', 'isComplete'])
            ->disableOriginalConstructor()
            ->getMock();
        $timeProfiler
            ->expects($this->once())
            ->method('isComplete')
            ->willReturn(true);
        $timeProfiler
            ->expects($this->exactly(3))
            ->method('getProfile')
            ->willReturn(10);

        $databaseProfiler = $this
            ->getMockBuilder('Maleficarum\Profiler\Database')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $handler = new \Maleficarum\Response\Http\Handler\JsonHandler();
        $handler
            ->addProfiler($timeProfiler, 'time')
            ->addProfiler($databaseProfiler, 'database');

        $this->setProperty($handler, 'body', ['meta' => []]);

        $this->assertSame('{"meta":{"time_profile":{"exec_time":10,"req_per_s":0.1},"database_profile":{"query_count":0,"overall_query_exec_time":0}}}', $handler->getBody());
    }
    /* ------------------------------------ Method: getBody END ---------------------------------------- */

    /* ------------------------------------ Method: getContentType START ------------------------------- */
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
