<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Categories;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ProductController
 */
final class ProductControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->get(route('products.index'));

        $response->assertOk();
        $response->assertViewIs('product.index');
        $response->assertViewHas('products');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('products.create'));

        $response->assertOk();
        $response->assertViewIs('product.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProductController::class,
            'store',
            \App\Http\Requests\ProductStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $Categories_id = fake()->word();
        $name = fake()->name();
        $description = fake()->text();
        $image = fake()->word();
        $price = fake()->numberBetween(-10000, 10000);
        $stock = fake()->numberBetween(-10000, 10000);
        $categories = Categories::factory()->create();

        $response = $this->post(route('products.store'), [
            'Categories_id' => $Categories_id,
            'name' => $name,
            'description' => $description,
            'image' => $image,
            'price' => $price,
            'stock' => $stock,
            'categories_id' => $categories->id,
        ]);

        $products = Product::query()
            ->where('Categories_id', $Categories_id)
            ->where('name', $name)
            ->where('description', $description)
            ->where('image', $image)
            ->where('price', $price)
            ->where('stock', $stock)
            ->where('categories_id', $categories->id)
            ->get();
        $this->assertCount(1, $products);
        $product = $products->first();

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('product.id', $product->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertViewIs('product.show');
        $response->assertViewHas('product');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.edit', $product));

        $response->assertOk();
        $response->assertViewIs('product.edit');
        $response->assertViewHas('product');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProductController::class,
            'update',
            \App\Http\Requests\ProductUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $product = Product::factory()->create();
        $Categories_id = fake()->word();
        $name = fake()->name();
        $description = fake()->text();
        $image = fake()->word();
        $price = fake()->numberBetween(-10000, 10000);
        $stock = fake()->numberBetween(-10000, 10000);
        $categories = Categories::factory()->create();

        $response = $this->put(route('products.update', $product), [
            'Categories_id' => $Categories_id,
            'name' => $name,
            'description' => $description,
            'image' => $image,
            'price' => $price,
            'stock' => $stock,
            'categories_id' => $categories->id,
        ]);

        $product->refresh();

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('product.id', $product->id);

        $this->assertEquals($Categories_id, $product->Categories_id);
        $this->assertEquals($name, $product->name);
        $this->assertEquals($description, $product->description);
        $this->assertEquals($image, $product->image);
        $this->assertEquals($price, $product->price);
        $this->assertEquals($stock, $product->stock);
        $this->assertEquals($categories->id, $product->categories_id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));

        $this->assertModelMissing($product);
    }
}
