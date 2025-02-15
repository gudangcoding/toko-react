<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\OrderController
 */
final class OrderControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $orders = Order::factory()->count(3)->create();

        $response = $this->get(route('orders.index'));

        $response->assertOk();
        $response->assertViewIs('order.index');
        $response->assertViewHas('orders');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('orders.create'));

        $response->assertOk();
        $response->assertViewIs('order.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\OrderController::class,
            'store',
            \App\Http\Requests\OrderStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $user = User::factory()->create();
        $total = fake()->numberBetween(-10000, 10000);
        $status = fake()->randomElement(/** enum_attributes **/);
        $payment_status = fake()->randomElement(/** enum_attributes **/);
        $shipping_status = fake()->randomElement(/** enum_attributes **/);
        $shipping = Shipping::factory()->create();
        $payment = Payment::factory()->create();

        $response = $this->post(route('orders.store'), [
            'user_id' => $user->id,
            'total' => $total,
            'status' => $status,
            'payment_status' => $payment_status,
            'shipping_status' => $shipping_status,
            'shipping_id' => $shipping->id,
            'payment_id' => $payment->id,
        ]);

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->where('total', $total)
            ->where('status', $status)
            ->where('payment_status', $payment_status)
            ->where('shipping_status', $shipping_status)
            ->where('shipping_id', $shipping->id)
            ->where('payment_id', $payment->id)
            ->get();
        $this->assertCount(1, $orders);
        $order = $orders->first();

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('order.id', $order->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $order = Order::factory()->create();

        $response = $this->get(route('orders.show', $order));

        $response->assertOk();
        $response->assertViewIs('order.show');
        $response->assertViewHas('order');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $order = Order::factory()->create();

        $response = $this->get(route('orders.edit', $order));

        $response->assertOk();
        $response->assertViewIs('order.edit');
        $response->assertViewHas('order');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\OrderController::class,
            'update',
            \App\Http\Requests\OrderUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $order = Order::factory()->create();
        $user = User::factory()->create();
        $total = fake()->numberBetween(-10000, 10000);
        $status = fake()->randomElement(/** enum_attributes **/);
        $payment_status = fake()->randomElement(/** enum_attributes **/);
        $shipping_status = fake()->randomElement(/** enum_attributes **/);
        $shipping = Shipping::factory()->create();
        $payment = Payment::factory()->create();

        $response = $this->put(route('orders.update', $order), [
            'user_id' => $user->id,
            'total' => $total,
            'status' => $status,
            'payment_status' => $payment_status,
            'shipping_status' => $shipping_status,
            'shipping_id' => $shipping->id,
            'payment_id' => $payment->id,
        ]);

        $order->refresh();

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('order.id', $order->id);

        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals($total, $order->total);
        $this->assertEquals($status, $order->status);
        $this->assertEquals($payment_status, $order->payment_status);
        $this->assertEquals($shipping_status, $order->shipping_status);
        $this->assertEquals($shipping->id, $order->shipping_id);
        $this->assertEquals($payment->id, $order->payment_id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $order = Order::factory()->create();

        $response = $this->delete(route('orders.destroy', $order));

        $response->assertRedirect(route('orders.index'));

        $this->assertModelMissing($order);
    }
}
