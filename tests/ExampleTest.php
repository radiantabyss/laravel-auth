<?php

namespace RA\FormBuilderAdmin\Tests;

use Orchestra\Testbench\TestCase;
use RA\FormBuilderAdmin\FormBuilderAdminServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [FormBuilderAdminServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
