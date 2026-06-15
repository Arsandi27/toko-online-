<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::findOrCreate('Admin');
    Role::findOrCreate('Customer');
});

it('assigns customer role on registration', function () {
    $this->post('/register', [
        'name' => 'New Customer',
        'email' => 'new@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirect('/dashboard');

    expect(User::query()->where('email', 'new@example.com')->first()->hasRole('Customer'))->toBeTrue();
});

it('allows admin to create category and product', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $this->actingAs($admin)
        ->post(route('admin.categories.store'), [
            'name' => 'Sepatu',
            'description' => 'Kategori sepatu',
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.categories.index'));

    $category = Category::query()->firstOrFail();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Sepatu Lari',
            'description' => 'Nyaman',
            'price' => 150000,
            'stock' => 5,
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.products.index'));

    expect(Product::query()->where('name', 'Sepatu Lari')->exists())->toBeTrue();
});

it('checks out an order and lets admin verify payment', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $customer = User::factory()->create();
    $customer->assignRole('Customer');

    $category = Category::query()->create([
        'name' => 'Tas',
        'slug' => 'tas',
        'is_active' => true,
    ]);

    $product = Product::query()->create([
        'category_id' => $category->id,
        'name' => 'Tas Kanvas',
        'slug' => 'tas-kanvas',
        'price' => 200000,
        'stock' => 3,
        'is_active' => true,
    ]);

    $this->actingAs($customer)
        ->post(route('cart.items.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ])
        ->assertRedirect(route('cart.index'));

    $this->actingAs($customer)
        ->post(route('checkout.store'), [
            'recipient_name' => 'Dzaky',
            'recipient_phone' => '08123456789',
            'shipping_address' => 'Jl. Laravel No. 1',
        ])
        ->assertRedirect();

    $order = Order::query()->with('payment')->firstOrFail();

    $this->actingAs($customer)
        ->post(route('orders.payment-proof.store', $order), [
            'proof' => UploadedFile::fake()->image('proof.jpg'),
        ])
        ->assertRedirect(route('orders.show', $order));

    $this->actingAs($admin)
        ->patch(route('admin.orders.payment.update', $order), [
            'status' => 'verified',
            'notes' => 'Valid',
        ])
        ->assertRedirect(route('admin.orders.show', $order));

    expect($order->fresh()->status)->toBe('processing')
        ->and($order->payment->fresh()->status)->toBe('verified')
        ->and($product->fresh()->stock)->toBe(1);
});
