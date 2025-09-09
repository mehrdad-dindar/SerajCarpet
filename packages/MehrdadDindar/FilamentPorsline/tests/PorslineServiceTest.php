<?php

namespace MehrdadDindar\FilamentPorsline\Tests;

use MehrdadDindar\FilamentPorsline\Services\PorslineService;
use PHPUnit\Framework\TestCase;

class PorslineServiceTest extends TestCase
{
    public function test_service_can_be_instantiated()
    {
        $service = new PorslineService();
        $this->assertInstanceOf(PorslineService::class, $service);
    }

    public function test_get_surveys_returns_array()
    {
        $service = new PorslineService();
        $surveys = $service->getSurveys();
        $this->assertIsArray($surveys);
    }
}
