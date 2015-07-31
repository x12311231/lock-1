<?php

namespace malkusch\lock\mutex;

/**
 * Tests for SpinlockMutex.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see SpinlockMutex
 */
class SpinlockMutexTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Tests failing to acquire the lock.
     *
     * @test
     * @expectedException malkusch\lock\exception\TimeoutException
     */
    public function testFailAcquireLock()
    {
        $mutex = $this->getMockForAbstractClass(SpinlockMutex::class, ["test"]);
        $mutex->expects($this->any())->method("acquire")->willReturn(false);

        $mutex->synchronized(function () {
            $this->fail("execution is not expected");
        });
    }
    
    /**
     * Tests executing code which exceeds the timeout fails.
     *
     * @test
     * @expectedException malkusch\lock\exception\LockReleaseException
     */
    public function testExecuteTooLong()
    {
        $mutex = $this->getMockForAbstractClass(SpinlockMutex::class, ["test", 1]);
        $mutex->expects($this->any())->method("acquire")->willReturn(true);
        $mutex->expects($this->any())->method("release")->willReturn(true);

        $mutex->synchronized(function () {
            sleep(2);
        });
    }

    /**
     * Tests failing to release a lock.
     *
     * @test
     * @expectedException malkusch\lock\exception\LockReleaseException
     */
    public function testFailReleasingLock()
    {
        $mutex = $this->getMockForAbstractClass(SpinlockMutex::class, ["test"]);
        $mutex->expects($this->any())->method("acquire")->willReturn(true);
        $mutex->expects($this->any())->method("release")->willReturn(false);
        
        $mutex->synchronized(function () {
        });
    }
    
    /**
     * Tests executing exactly unil the timeout will leave the key one more second.
     *
     * @test
     */
    public function testExecuteTimeoutLeavesOneSecondForKeyToExpire()
    {
        $this->markTestIncomplete();
    }
}