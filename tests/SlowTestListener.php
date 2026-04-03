<?php
/**
 * PHPUnit test listener that reports slow tests and suite starts.
 *
 * Prints a warning to stderr for any test exceeding the threshold,
 * and logs suite starts with test counts for progress tracking.
 *
 * Usage: uncomment the <listeners> block in phpunit.xml.dist to enable.
 */
class SlowTestListener implements PHPUnit_Framework_TestListener
{
    private $threshold;

    public function __construct($threshold = 0.5)
    {
        $this->threshold = $threshold;
    }

    public function startTest(PHPUnit_Framework_Test $test) {}

    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        if ($time > $this->threshold) {
            $name = $test instanceof PHPUnit_Framework_SelfDescribing ? $test->toString() : get_class($test);
            fprintf(STDERR, "\nSLOW TEST (%.1fs): %s\n", $time, $name);
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
