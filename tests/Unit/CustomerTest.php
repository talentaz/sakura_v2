<?php

namespace Tests\Unit;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test customer creation with factory.
     *
     * @return void
     */
    public function test_customer_creation()
    {
        $customer = Customer::factory()->create([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
        ]);

        $this->assertDatabaseHas('customers', [
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'status' => 'Active',
        ]);

        $this->assertEquals('Test Customer', $customer->name);
        $this->assertEquals('test@example.com', $customer->email);
        $this->assertEquals('Active', $customer->status);
    }

    /**
     * Test customer fillable attributes.
     *
     * @return void
     */
    public function test_customer_fillable_attributes()
    {
        $customer = new Customer();
        $fillable = $customer->getFillable();

        $expectedFillable = [
            'name', 'email', 'password', 'country_id', 'status'
        ];

        $this->assertEquals($expectedFillable, $fillable);
    }

    /**
     * Test customer hidden attributes.
     *
     * @return void
     */
    public function test_customer_hidden_attributes()
    {
        $customer = new Customer();
        $hidden = $customer->getHidden();

        $expectedHidden = [
            'password', 'remember_token'
        ];

        $this->assertEquals($expectedHidden, $hidden);
    }
}
