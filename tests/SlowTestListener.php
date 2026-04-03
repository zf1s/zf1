<?php
class SlowTestListener implements PHPUnit_Framework_TestListener
{
    private $startTime;
    private $threshold = 0.5; // seconds

    public function startTest(PHPUnit_Framework_Test $test)
    {
        $this->startTime = microtime(true);
    }

    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        $elapsed = microtime(true) - $this->startTime;
        if ($elapsed > $this->threshold) {
            fprintf(STDERR, "\nSLOW TEST (%.1fs): %s\n", $elapsed, $test->toString());
        }
    }

    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time) {}
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time) {}
    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time) {}
    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time) {}
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        if (strpos($suite->getName(), 'Zend_') === 0) {
            fprintf(STDERR, "\n>>> SUITE START: %s (%d tests)\n", $suite->getName(), $suite->count());
        }
    }
    public function endTestSuite(PHPUnit_Framework_TestSuite $suite) {}
    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time) {}
}
