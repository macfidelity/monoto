<?php

/**
 * Tests
 * Playground for unit tests
 */
class test extends PHPUnit_Framework_TestCase {
    public function test_odd_or_even_to_true() {
        $this->assertTrue( odd_or_even( 3 ) == true );
    }
}

?>
