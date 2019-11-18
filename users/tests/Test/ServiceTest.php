<?php

namespace Test;

use \UnitTestCase;

class ServiceTest extends UnitTestCase
{
    public function testSessionCase()
    {
        $di = di();
        $this->assertTrue(
            $di->has('session'),
            'No Session Service'
        );
    }
    public function testCacheCase()
    {
        $di = di();
        $this->assertTrue(
            $di->has('cache'),
            'No Cache Service'
        );
    }
    public function testUrlCase()
    {
        $di = di();
        $this->assertTrue(
            $di->has('url'),
            'No Url Service'
        );
    }
    public function testConfigCase()
    {
        $di = di();
        $this->assertTrue(
            $di->has('config'),
            'No Config Service'
        );
    }
    public function testDbCase()
    {
        $di = di();
        $this->assertTrue(
            $di->has('db'),
            'No DB Service'
        );
    }
    public function testModelsMetadataCase()
    {
        $di = di();
        $this->assertTrue(
            $di->has('modelsMetadata'),
            'No DB Service'
        );
    }
    public function testRouterCase()
    {
        $di = di();
        $this->assertTrue(
            $di->has('router'),
            'No Router Service'
        );
    }

}