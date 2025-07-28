# Customer Model Usage Examples

## Overview
The Customer table has been successfully created with the following structure:

- `id` - bigint(20) unsigned auto_increment primary key
- `name` - varchar(255) nullable
- `email` - varchar(255) unique not null
- `country_id` - bigint(20) unsigned nullable (for future country relationship)
- `password` - varchar(255) not null
- `remember_token` - varchar(100) nullable
- `status` - varchar(255) not null default 'Active'
- `created_at` - timestamp nullable
- `updated_at` - timestamp nullable
- `deleted_at` - timestamp nullable (soft deletes enabled)

## Basic Usage Examples

### Creating a Customer
```php
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

// Create a new customer
$customer = Customer::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password123'),
    'country_id' => null, // Set to actual country ID when countries table exists
    'status' => 'Active'
]);
```

### Finding Customers
```php
// Find by ID
$customer = Customer::find(1);

// Find by email
$customer = Customer::where('email', 'john@example.com')->first();

// Get all active customers
$activeCustomers = Customer::where('status', 'Active')->get();

// Get customers with pagination
$customers = Customer::paginate(10);
```

### Updating a Customer
```php
$customer = Customer::find(1);
$customer->update([
    'name' => 'John Smith',
    'status' => 'Inactive'
]);
```

### Soft Delete (Customer can be restored)
```php
// Soft delete a customer
$customer = Customer::find(1);
$customer->delete();

// Get only non-deleted customers (default behavior)
$customers = Customer::all();

// Get including soft-deleted customers
$allCustomers = Customer::withTrashed()->get();

// Get only soft-deleted customers
$deletedCustomers = Customer::onlyTrashed()->get();

// Restore a soft-deleted customer
$customer = Customer::withTrashed()->find(1);
$customer->restore();
```

### Authentication
Since Customer extends Authenticatable, you can use it for authentication:

```php
// In your auth configuration (config/auth.php), you can add a customer guard:
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'customer' => [
        'driver' => 'session',
        'provider' => 'customers',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    'customers' => [
        'driver' => 'eloquent',
        'model' => App\Models\Customer::class,
    ],
],
```

### Using Factory for Testing
```php
// Create a single customer
$customer = Customer::factory()->create();

// Create multiple customers
$customers = Customer::factory(10)->create();

// Create with specific attributes
$customer = Customer::factory()->create([
    'name' => 'Test Customer',
    'email' => 'test@example.com'
]);
```

## Differences from User Model

The Customer model is separate from the User model and has these key differences:

1. **Purpose**: Users are for admin/staff, Customers are for end-users
2. **Fields**: Customer has `country_id` instead of multiple address fields
3. **Status**: Customer uses string status ('Active', 'Inactive') vs User's boolean
4. **Soft Deletes**: Customer supports soft deletes, allowing recovery
5. **Authentication**: Can be used with separate authentication guard

## Next Steps

1. **Create Countries Table** (optional):
   ```bash
   php artisan make:migration create_countries_table
   php artisan make:model Country
   ```

2. **Add Customer Authentication Routes**:
   Create separate login/register routes for customers

3. **Create Customer Controllers**:
   ```bash
   php artisan make:controller CustomerController
   php artisan make:controller Auth/CustomerAuthController
   ```

4. **Add Validation Rules**:
   Create form requests for customer registration/updates

## Testing

Run the customer tests:
```bash
php artisan test --filter=CustomerTest
```

Create sample data:
```bash
php artisan db:seed --class=CustomerSeeder
```
