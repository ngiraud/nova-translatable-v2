<?php

namespace NGiraud\NovaTranslatable\Tests;

use NGiraud\NovaTranslatable\Tests\Fixtures\Product;

class ResourceCreationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->authenticate();
    }

    /** @test */
    function test_can_create_resources()
    {
        $this->withoutExceptionHandling();

        $response = $this->postJson("/nova-api/products", [
            'name' => ['fr' => 'french name', 'en' => 'english name'],
        ]);

        $response->assertStatus(201);

        $model = Product::first();
        $this->assertEquals('test', $model->name);
    }
}
