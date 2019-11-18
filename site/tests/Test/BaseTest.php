<?php

namespace Test;

use \UnitTestCase;

/**
 * Class UnitTest
 */
class BaseTest extends UnitTestCase
{
    public function testBaseCase()
    {
        $this->assertTrue(
            extension_loaded('phalcon')
        );
    }
}