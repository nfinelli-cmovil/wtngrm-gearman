<?php
namespace Desyncr\Wtngrm\Gearman\Service;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-12-21 at 23:18:25.
 */
class GearmanServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GearmanService
     */
    protected $object;
    protected $mock;
    protected $defaults =
            array('servers' => 
                    array('client' =>
                        array(
                             array('host' => '127.0.0.1', 'port' => 4730)
                    )
                )
            );

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->mock = $this->getMock('\GearmanClient');
        $this->mock->expects($this->any())
                    ->method('addServer');

       $this->object = new GearmanService($this->mock, $this->defaults);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Desyncr\Wtngrm\Gearman\Service\GearmanService::__construct
     */
    public function testConfiguration()
    {
        $options = array(
            'servers' => array(
                'client' => array(
                    array('host' => '127.0.0.1', 'port' => 4730),
                    array('host' => '127.0.0.2', 'port' => 4730)
                )
            )
        );

        $this->mock->expects($this->exactly(2))
                    ->method('addServer');

        $this->object = new GearmanService($this->mock, $options);
        $this->assertEquals($options['servers'], $this->object->getOption('servers'));

    }


    /**
     * @covers Desyncr\Wtngrm\Gearman\Service\GearmanService::dispatch
     */
    public function testDispatch()
    {
        $key = 'test.job';
        $job = array('id' => $key);
        $this->object->add($key, $job);

        $this->mock->expects($this->once())
                    ->method('doBackground');

        $this->object->dispatch();

    }

    /**
     * @covers Desyncr\Wtngrm\Gearman\Service\GearmanService::dispatch
     */
    public function testDispatchMultipleJobs()
    {

        for ($i = 0 ; $i <= 4 ; $i++ ) {
            $this->object->add($key . $i, $job[$i]);
        }

        $this->mock->
            expects($this->exactly(5))->
            method('doBackground');

        $this->object->dispatch();
    }
}
