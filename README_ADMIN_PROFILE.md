# Profile Modules for SinDa Laravel App

## Overview

This module provides dedicated profile management systems for different user types in the SinDa Laravel application. Users can edit their personal information, contact details, and role-specific fields through secure, role-based interfaces.

## Supported User Types

- **Admin Users** - System administrators
- **CWO (Child Welfare Officers)** - Social workers and child welfare professionals
- **Healthcare Professionals** - Medical professionals and healthcare workers
- **Public Users** - General public users (existing implementation)

## Features

- **Role-based Access Control**: Each user type has their own dedicated profile functionality
- **Comprehensive Profile Management**: Edit personal info, contact details, and role-specific fields
- **Modern UI**: Clean, responsive interface with avatar display
- **Form Validation**: Server-side validation with user-friendly error messages
- **Account Deletion**: Secure account deletion with confirmation modal
- **Avatar Support**: Display user avatars or generate colorful initials-based avatars

## File Structure

```
app/
├── Http/Controllers/
│   ├── AdminProfileController.php          # Admin profile controller
│   ├── CwoProfileController.php            # CWO profile controller
│   ├── HealthcareProfileController.php     # Healthcare profile controller
│   └── ProfileController.php               # General profile controller (existing)
├── Models/
│   ├── User.php                           # User model (existing)
│   ├── UserProfile.php                    # Base profile model (existing)
│   ├── AdminProfile.php                   # Admin-specific profile model (existing)
│   ├── SocialWorkerProfile.php            # CWO profile model (existing)
│   └── HealthcareProfile.php              # Healthcare profile model (existing)

resources/views/
├── admin/users/admins/profile/
│   └── edit.blade.php                     # Admin profile edit view
├── admin/users/cwo/profile/
│   └── edit.blade.php                     # CWO profile edit view
├── admin/users/healthcare/profile/
│   └── edit.blade.php                     # Healthcare profile edit view
└── landing/profile/
    └── edit.blade.php                     # Public user profile edit view (existing)

routes/
└── web.php                               # Updated with all profile routes

tests/
├── AdminProfileTest.php                  # Test suite for admin profile
├── CwoProfileTest.php                    # Test suite for CWO profile
└── HealthcareProfileTest.php             # Test suite for healthcare profile
```

## Routes

The following routes are available for different user types:

### Admin Users
- `GET /admin/profile/edit` - Display admin profile edit form
- `PUT /admin/profile/update` - Update admin profile information
- `DELETE /admin/profile/delete` - Delete admin account

### CWO (Child Welfare Officers)
- `GET /cwo/profile/edit` - Display CWO profile edit form
- `PUT /cwo/profile/update` - Update CWO profile information
- `DELETE /cwo/profile/delete` - Delete CWO account

### Healthcare Professionals
- `GET /healthcare/profile/edit` - Display healthcare profile edit form
- `PUT /healthcare/profile/update` - Update healthcare profile information
- `DELETE /healthcare/profile/delete` - Delete healthcare account

### Public Users (Existing)
- `GET /profile/edit` - Display public user profile edit form
- `PUT /profile/update` - Update public user profile information
- `DELETE /profile/delete` - Delete public user account

All routes are protected by appropriate `auth` and role-specific middleware.

## Database Schema

The profile system uses multiple tables:

### users
- `id` (UUID)
- `name` (string)
- `email` (string)
- `role_id` (foreign key)

### user_profiles (Base Profile)
- `user_id` (UUID, foreign key)
- `phone` (string, nullable)
- `address_line1` (string, nullable)
- `address_line2` (string, nullable)
- `city` (string, nullable)
- `postcode` (string, nullable)
- `state` (string, nullable)

### admin_profiles
- `user_id` (UUID, foreign key)
- `display_name` (string, nullable)
- `department` (string, nullable)
- `position` (string, nullable)

### social_worker_profiles (CWO)
- `user_id` (UUID, foreign key)
- `agency_name` (string, nullable)
- `agency_code` (string, nullable)
- `placement_state` (string, nullable)
- `placement_district` (string, nullable)
- `staff_id` (string, nullable)

### healthcare_profiles
- `user_id` (UUID, foreign key)
- `profession` (string, nullable)
- `apc_expiry` (date, nullable)
- `facility_name` (string, nullable)
- `state` (string, nullable)

## Usage

### For Admin Users

1. **Access Profile**: Click on the profile dropdown in the header and select "Profile Details"
2. **Edit Information**: Update any of the following fields:
   - Full Name (required)
   - Phone Number
   - Display Name
   - Department
   - Position
   - Address Information (Line 1, Line 2, City, Postcode, State)
3. **Save Changes**: Click "Save Changes" to update the profile
4. **Delete Account**: Use the "Delete My Account" button (requires confirmation)

### For CWO (Child Welfare Officers)

1. **Access Profile**: Click on the profile dropdown in the header and select "Profile Details"
2. **Edit Information**: Update any of the following fields:
   - Full Name (required)
   - Phone Number
   - Agency Name
   - Agency Code
   - Staff ID
   - Placement State
   - Placement District
   - Address Information (Line 1, Line 2, City, Postcode, State)
3. **Save Changes**: Click "Save Changes" to update the profile
4. **Delete Account**: Use the "Delete My Account" button (requires confirmation)

### For Healthcare Professionals

1. **Access Profile**: Click on the profile dropdown in the header and select "Profile Details"
2. **Edit Information**: Update any of the following fields:
   - Full Name (required)
   - Phone Number
   - Profession
   - Facility Name
   - APC Expiry Date
   - Address Information (Line 1, Line 2, City, Postcode, State)
3. **Save Changes**: Click "Save Changes" to update the profile
4. **Delete Account**: Use the "Delete My Account" button (requires confirmation)

### For Developers

#### Adding New Profile Fields

1. **Update Migration**: Add new columns to the appropriate profile table
2. **Update Model**: Add fields to the `$fillable` array in the profile model
3. **Update Controller**: Add validation rules and update the `update` method
4. **Update View**: Add form fields to the edit view

#### Customizing Validation Rules

Edit the validation rules in the respective controller's `update()` method:

```php
// Example for CWO
$rules = [
    'name' => 'required|string|max:255',
    'phone' => 'nullable|string|max:20',
    'agency_name' => 'nullable|string|max:150',
    // Add your custom rules here
];
```

#### Customizing the UI

Each profile edit view is located in its respective directory:
- Admin: `resources/views/admin/users/admins/profile/edit.blade.php`
- CWO: `resources/views/admin/users/cwo/profile/edit.blade.php`
- Healthcare: `resources/views/admin/users/healthcare/profile/edit.blade.php`

All views use:
- Bootstrap 5 for styling
- Tabler Icons for icons
- Custom CSS for avatar styling

## Security Features

- **Role-based Access**: Each user type can only access their own profile functionality
- **CSRF Protection**: All forms include CSRF tokens
- **Input Validation**: Server-side validation for all inputs
- **SQL Injection Prevention**: Uses Eloquent ORM with parameterized queries
- **XSS Protection**: Blade templating engine provides automatic XSS protection

## Testing

Run the test suites to verify functionality:

```bash
# Test all profile modules
php artisan test tests/AdminProfileTest.php
php artisan test tests/CwoProfileTest.php
php artisan test tests/HealthcareProfileTest.php
```

The test suites cover:
- Role-specific access to profile edit pages
- Non-authorized access restrictions
- Profile update functionality
- Account deletion

## Integration with Existing System

The profile modules integrate seamlessly with the existing SinDa application:

- **Header Integration**: Profile link automatically routes to role-specific page based on user type
- **Existing Models**: Uses existing User, UserProfile, and role-specific profile models
- **Consistent UI**: Follows the same design patterns as other admin pages
- **Middleware**: Uses existing role-based middleware

## Future Enhancements

Potential improvements for the profile modules:

1. **Avatar Upload**: Add file upload functionality for profile pictures
2. **Password Change**: Add password change functionality
3. **Two-Factor Authentication**: Implement 2FA for enhanced security
4. **Profile Export**: Allow users to export their profile data
5. **Activity Logging**: Log profile changes for audit purposes
6. **Email Notifications**: Send email confirmations for profile changes
7. **Profile Templates**: Create customizable profile templates for different roles

## Troubleshooting

### Common Issues

1. **403 Forbidden Error**: Ensure the user has the correct role
2. **Validation Errors**: Check that all required fields are filled correctly
3. **Route Not Found**: Verify that routes are properly registered in `web.php`
4. **Database Errors**: Ensure all required tables and columns exist

### Debug Mode

Enable debug mode in `.env` to see detailed error messages:

```
APP_DEBUG=true
```

## Support

For issues or questions regarding the profile modules, please refer to:
- Laravel documentation: https://laravel.com/docs
- Application logs: `storage/logs/laravel.log`
- Test suites: `tests/AdminProfileTest.php`, `tests/CwoProfileTest.php`, `tests/HealthcareProfileTest.php`
