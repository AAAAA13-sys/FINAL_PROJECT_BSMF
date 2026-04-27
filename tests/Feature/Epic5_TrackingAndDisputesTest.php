<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\Dispute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Epic5_TrackingAndDisputesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $order;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        
        $this->order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'delivered',
        ]);
    }

    // AC 7.1: Users shall see a list of their orders with status and date
    public function test_user_can_view_order_history()
    {
        $response = $this->actingAs($this->user)->getJson('/api/v1/orders');
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'order_number', 'status', 'created_at']
                     ]
                 ]);
    }

    // AC 7.2: Admin updates to order status shall reflect in the user interface
    public function test_admin_status_update_reflects_for_user()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        
        // Admin updates status
        $this->actingAs($admin)->postJson("/admin/orders/{$this->order->id}/status", [
            'status' => 'out_for_delivery'
        ]);

        // User views status via API
        $response = $this->actingAs($this->user)->getJson("/api/v1/orders/{$this->order->id}");
        
        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'out_for_delivery']);
    }

    // AC 8.1, 8.2: File disputes for delivered orders, unique dispute number
    public function test_customer_can_file_dispute()
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/support', [
            'order_id' => $this->order->id,
            'subject' => 'Missing Item',
            'description' => 'I did not receive the blue car.'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['data' => ['id', 'dispute_number']]);
                 
        $disputeNumber = $response->json('data.dispute_number');
        $this->assertStringStartsWith('DSP-', $disputeNumber);
    }

    // AC 8.3, 8.4, 8.5: Admin views all submitted disputes, stored in DB
    public function test_admin_can_view_all_disputes()
    {
        $dispute = Dispute::create([
            'dispute_number' => 'DSP-TEST1234',
            'user_id' => $this->user->id,
            'order_id' => $this->order->id,
            'subject' => 'Test Dispute',
            'description' => 'Test description',
            'status' => 'open'
        ]);

        $admin = User::factory()->create(['is_admin' => true]);
        
        $response = $this->actingAs($admin)->get('/admin/disputes');
        
        $response->assertStatus(200)
                 ->assertSee('DSP-TEST1234')
                 ->assertSee('Test Dispute');
    }
}
