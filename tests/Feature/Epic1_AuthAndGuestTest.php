<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Epic1_AuthAndGuestTest extends TestCase
{
    use RefreshDatabase;

    // AC 0.1: The system shall allow users to access the product catalog without authentication.
    public function test_guest_can_browse_products()
    {
        $response = $this->getJson('/api/v1/products');
        $response->assertStatus(200);
    }

    // AC 0.2, AC 0.3, AC 0.4: Guest restrictions & redirects
    public function test_guest_cannot_add_to_cart_or_checkout()
    {
        // API should return 401 Unauthorized
        $this->postJson('/api/v1/cart', ['product_id' => 1])->assertStatus(401);
        $this->postJson('/api/v1/orders', [])->assertStatus(401);

        // Web routes should redirect to login (AC 0.4)
        $this->get('/cart')->assertRedirect('/login');
        $this->get('/checkout')->assertRedirect('/login');
    }

    // AC 1.1: Registration form requires Name, Email, Password
    public function test_user_registration_validation()
    {
        $response = $this->postJson('/api/v1/register', []);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    // AC 1.2: Prevent duplicate email registration
    public function test_duplicate_email_registration_fails()
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson('/api/v1/register', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    // AC 1.3: Passwords hashed
    public function test_passwords_are_hashed()
    {
        $this->postJson('/api/v1/register', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertNotEquals('password123', $user->password);
    }

    // AC 2.1: Authenticate using email and password
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)->assertJsonStructure(['access_token']);
    }

    // AC 2.2: Redirect Customer to home/product
    public function test_login_redirects_customer_to_home()
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/'); // Assuming home is /
    }

    // AC 2.3: Redirect Admin to /admin/dashboard
    public function test_login_redirects_admin_to_dashboard()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
    }

    // AC 2.4: Prevent non-admin from accessing /admin routes
    public function test_non_admin_cannot_access_admin_routes()
    {
        $user = User::factory()->create(['is_admin' => false]);
        
        $response = $this->actingAs($user)->get('/admin/dashboard');
        
        // Either 403 Forbidden or redirected
        $this->assertTrue(in_array($response->status(), [403, 302, 404]));
        
        if ($response->status() === 302) {
            $response->assertRedirect();
        }
    }
}
