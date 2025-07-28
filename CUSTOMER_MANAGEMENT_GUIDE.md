# Customer Management System Guide

## Overview
The Customer Management system has been successfully implemented in the admin panel. This system allows administrators to manage customer accounts separately from user accounts.

## Features Implemented

### 1. **Admin Navigation**
- Added "Customer Management" menu item in the admin sidebar
- Located under User Management section
- Includes submenu items:
  - Customer List
  - Add Customer

### 2. **Customer Management Controller**
- **Location**: `app/Http/Controllers/Admin/CustomerManagementController.php`
- **Features**:
  - Full CRUD operations (Create, Read, Update, Delete)
  - Search and filter functionality
  - Status management
  - Soft delete support
  - AJAX status updates

### 3. **Routes**
All routes are prefixed with `/admin/customer-management/` and protected by admin authentication:

```php
GET    /admin/customer-management              # List customers
GET    /admin/customer-management/create       # Create form
POST   /admin/customer-management/store        # Store new customer
GET    /admin/customer-management/{id}         # View customer details
GET    /admin/customer-management/{id}/edit    # Edit form
PUT    /admin/customer-management/{id}         # Update customer
DELETE /admin/customer-management/{id}         # Soft delete customer
POST   /admin/customer-management/{id}/restore # Restore deleted customer
DELETE /admin/customer-management/{id}/force   # Permanently delete customer
POST   /admin/customer-management/change-status # AJAX status change
```

### 4. **Views**
- **Index**: `resources/views/admin/pages/customer_management/index.blade.php`
- **Create**: `resources/views/admin/pages/customer_management/create.blade.php`
- **Edit**: `resources/views/admin/pages/customer_management/edit.blade.php`
- **Show**: `resources/views/admin/pages/customer_management/show.blade.php`

### 5. **Styling**
- Custom CSS: `public/assets/admin/pages/customer_management/style.css`
- Responsive design
- Bootstrap integration
- Dark mode support

## How to Use

### Accessing Customer Management
1. Login to admin panel
2. Navigate to "Customer Management" in the sidebar
3. Click "Customer List" to view all customers

### Adding a New Customer
1. Go to Customer Management → Add Customer
2. Fill in the required fields:
   - **Email** (required, unique)
   - **Password** (required, min 6 characters)
   - **Name** (optional)
   - **Country ID** (optional)
   - **Status** (required: Active/Inactive/Suspended)
3. Click "Create Customer"

### Viewing Customer Details
1. In the customer list, click the "View" button
2. See complete customer information
3. View account statistics and timeline

### Editing a Customer
1. Click "Edit" button in customer list or details page
2. Modify any field except ID
3. Leave password empty to keep current password
4. Click "Update Customer"

### Managing Customer Status
- **Quick Status Change**: Use dropdown in customer list
- **Status Options**:
  - **Active**: Customer can login and use services
  - **Inactive**: Customer account is disabled
  - **Suspended**: Customer account is temporarily suspended

### Deleting Customers
- **Soft Delete**: Click "Delete" button (can be restored)
- **Restore**: Available for soft-deleted customers
- **Permanent Delete**: Permanently removes customer data

### Search and Filter
- **Search**: By name or email
- **Filter**: By status (Active/Inactive/Suspended)
- **Include Deleted**: Show soft-deleted customers
- **Clear Filters**: Reset all filters

## Customer Table Structure

The customer table has the following structure:
```sql
id              bigint(20) unsigned  auto_increment primary key
name            varchar(255)         nullable
email           varchar(255)         unique not null
country_id      bigint(20) unsigned  nullable
password        varchar(255)         not null
remember_token  varchar(100)         nullable
status          varchar(255)         not null default 'Active'
created_at      timestamp            nullable
updated_at      timestamp            nullable
deleted_at      timestamp            nullable (soft deletes)
```

## Key Differences from User Management

| Feature | Users | Customers |
|---------|-------|-----------|
| Purpose | Admin/Staff accounts | End-user accounts |
| Fields | Role-based access | Status-based access |
| Deletion | Hard delete | Soft delete with restore |
| Country | Multiple address fields | Single country_id |
| Status | Boolean active/inactive | String Active/Inactive/Suspended |

## Security Features

1. **Authentication**: Admin login required
2. **Authorization**: Admin role required
3. **Validation**: Server-side form validation
4. **CSRF Protection**: All forms protected
5. **Password Hashing**: Bcrypt encryption
6. **Soft Deletes**: Data recovery possible

## Technical Implementation

### Controller Methods
- `index()` - List customers with search/filter
- `create()` - Show create form
- `store()` - Save new customer
- `show()` - Display customer details
- `edit()` - Show edit form
- `update()` - Update customer
- `destroy()` - Soft delete customer
- `restore()` - Restore deleted customer
- `forceDelete()` - Permanently delete
- `changeStatus()` - AJAX status update

### Validation Rules
```php
'name' => 'nullable|string|max:255'
'email' => 'required|string|email|max:255|unique:customers'
'password' => 'required|string|min:6|confirmed' (create)
'password' => 'nullable|string|min:6|confirmed' (update)
'country_id' => 'nullable|integer'
'status' => 'required|string|in:Active,Inactive,Suspended'
```

### AJAX Features
- Real-time status updates
- No page refresh required
- Success/error notifications

## Testing

The system includes:
- Unit tests for Customer model
- Factory for generating test data
- Seeder for sample customers

Run tests:
```bash
php artisan test --filter=CustomerTest
```

Generate sample data:
```bash
php artisan db:seed --class=CustomerSeeder
```

## Future Enhancements

Potential improvements:
1. **Country Management**: Create countries table and relationship
2. **Customer Authentication**: Separate customer login system
3. **Customer Dashboard**: Frontend customer portal
4. **Email Notifications**: Account status change notifications
5. **Export/Import**: CSV/Excel customer data management
6. **Advanced Filtering**: Date ranges, bulk operations
7. **Customer Groups**: Categorize customers
8. **Activity Logs**: Track customer account changes

## Troubleshooting

### Common Issues
1. **Route not found**: Clear route cache with `php artisan route:clear`
2. **View not found**: Check view file paths and names
3. **Permission denied**: Ensure user has admin role
4. **Database errors**: Run migrations with `php artisan migrate`

### Debug Commands
```bash
# Check routes
php artisan route:list --name=admin.customer_management

# Clear caches
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Check database
php artisan tinker
>>> App\Models\Customer::count()
```

## Support

For issues or questions about the Customer Management system:
1. Check this documentation
2. Review the controller and model code
3. Check Laravel logs in `storage/logs/`
4. Test with sample data using factories and seeders
