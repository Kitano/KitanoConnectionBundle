<?php

namespace Kitano\ConnectionBundle\Tests\Manager;

use Kitano\ConnectionBundle\Model\Connection;
use Kitano\ConnectionBundle\Manager\FilterValidator;

use Symfony\Component\Validator\Validation;

class FilterValidatorTest extends \PHPUnit_Framework_TestCase
{
    private $filterValidator;
    private static $iteration;

    public function setUp()
    {
        $this->filterValidator = new FilterValidator();
        $this->filterValidator->setValidator(Validation::createValidatorBuilder()->getValidator());
        self::$iteration++;
    }

    public function tearDown()
    {
        $this->filterValidator = null;
    }

    public function getGoodFiltersBag()
    {
        return array(
            array(
                array('type' => 'foo')
            ),
            array(
                array('type' => array('foo', 'bar'))
            ),
        );
    }

    public function getBadFiltersBag()
    {
        return array(
            array(
                array('foo' => 'bar'),
            ),
            array(
                array('type' => 'foo', 'status' => 'bar'),
            ),
            array(
                array('status' => 0),
            ),
            array(
                array('status' => 1, 'type' => 'foo', 'depth' => 2),
            ),
            array(
                array('type' => 'foo', 'depth' => 'bar')
            ),
        );
    }

    /**
     * @dataProvider getGoodFiltersBag
     */
    public function testValidatorValidateGoodFilters($filters)
    {
        $this->filterValidator->validateFilters($filters);

        switch (self::$iteration) {
            case 1:
                $this->assertArrayHasKey('type', $filters);
                $this->assertContains('foo', $filters['type']);
                $this->assertArrayHasKey('depth', $filters);
                $this->assertEquals(1, $filters['depth']);
                break;

            case 2:
                $this->assertArrayHasKey('type', $filters);
                $this->assertContains('foo', $filters['type']);
                $this->assertContains('bar', $filters['type']);
                break;

            case 3:
                $this->assertArrayHasKey('status', $filters);
                $this->assertEquals(Connection::STATUS_CONNECTED, $filters['status']);
                break;

            case 4:
                $this->assertArrayHasKey('depth', $filters);
                $this->assertEquals(2, $filters['depth']);
                break;

            case 5:
                $this->assertArrayNotHasKey('bar', $filters);
                break;
        }
    }

    /**
     * @dataProvider getBadFiltersBag
     * @expectedException \Kitano\ConnectionBundle\Exception\InvalidFilterException
     */
    public function testValidatorThrowExceptions($filters)
    {
        $this->filterValidator->validateFilters($filters);
    }
}
