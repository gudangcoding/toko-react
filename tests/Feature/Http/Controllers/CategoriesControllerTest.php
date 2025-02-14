<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Categories;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CategoriesController
 */
final class CategoriesControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $categories = Categories::factory()->count(3)->create();

        $response = $this->get(route('categories.index'));

        $response->assertOk();
        $response->assertViewIs('category.index');
        $response->assertViewHas('categories');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('categories.create'));

        $response->assertOk();
        $response->assertViewIs('category.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CategoriesController::class,
            'store',
            \App\Http\Requests\CategoriesStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = fake()->name();

        $response = $this->post(route('categories.store'), [
            'name' => $name,
        ]);

        $categories = Category::query()
            ->where('name', $name)
            ->get();
        $this->assertCount(1, $categories);
        $category = $categories->first();

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('category.id', $category->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $category = Categories::factory()->create();

        $response = $this->get(route('categories.show', $category));

        $response->assertOk();
        $response->assertViewIs('category.show');
        $response->assertViewHas('category');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $category = Categories::factory()->create();

        $response = $this->get(route('categories.edit', $category));

        $response->assertOk();
        $response->assertViewIs('category.edit');
        $response->assertViewHas('category');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CategoriesController::class,
            'update',
            \App\Http\Requests\CategoriesUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $category = Categories::factory()->create();
        $name = fake()->name();

        $response = $this->put(route('categories.update', $category), [
            'name' => $name,
        ]);

        $category->refresh();

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('category.id', $category->id);

        $this->assertEquals($name, $category->name);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $category = Categories::factory()->create();
        $category = Category::factory()->create();

        $response = $this->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));

        $this->assertModelMissing($category);
    }
}
