<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Inquiry;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_inquiry_can_have_invoice()
    {
        // Create a user
        $user = User::factory()->create();

        // Create an inquiry
        $inquiry = Inquiry::create([
            'vehicle_name' => 'Test Vehicle',
            'inqu_name' => 'Test Customer',
            'inqu_email' => 'test@example.com',
            'inqu_mobile' => '1234567890',
            'inqu_country' => 'Test Country',
            'site_url' => 'test.com',
            'total_price' => 10000.00,
        ]);

        // Create an invoice for the inquiry
        $invoice = Invoice::create([
            'inquiry_id' => $inquiry->id,
            'paid_amount' => 10000.00,
            'description' => 'Test Invoice',
            'created_by' => $user->id,
        ]);

        // Test relationships
        $this->assertEquals($inquiry->id, $invoice->inquiry_id);
        $this->assertEquals($invoice->id, $inquiry->invoice->id);
        $this->assertEquals($user->id, $invoice->created_by);
    }

    public function test_inquiry_without_invoice_returns_null()
    {
        // Create an inquiry without invoice
        $inquiry = Inquiry::create([
            'vehicle_name' => 'Test Vehicle',
            'inqu_name' => 'Test Customer',
            'inqu_email' => 'test@example.com',
            'inqu_mobile' => '1234567890',
            'inqu_country' => 'Test Country',
            'site_url' => 'test.com',
            'total_price' => 10000.00,
        ]);

        // Test that invoice relationship returns null
        $this->assertNull($inquiry->invoice);
    }
}
