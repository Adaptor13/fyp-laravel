# Migration Cleanup Guide

## Overview
The migrations have been successfully combined to create a cleaner, more maintainable database structure. This document lists the old migrations that should be deleted after confirming the new combined migrations work correctly.

## New Combined Migrations Created

1. **`2025_07_26_000001_create_initial_tables.php`** - Combined:
   - `2025_07_26_000002_create_roles_table.php`
   - `2025_07_26_000003_create_users_table.php`
   - `2025_08_11_185731_alter_sessions_user_id_to_uuid.php`

2. **`2025_08_12_000001_create_all_profile_tables.php`** - Combined:
   - `2025_08_12_063104_create_user_profiles_table.php`
   - `2025_08_12_063105_create_law_enforcement_profiles_table.php`
   - `2025_08_12_063105_create_social_worker_profiles_table.php`
   - `2025_08_12_063106_create_public_user_profiles_table.php`
   - `2025_08_14_024242_create_healthcare_profiles_table.php`
   - `2025_08_14_024325_create_admin_profiles_table.php`
   - `2025_08_14_030850_create_gov_official_profiles_table.php`

3. **`2025_08_27_000001_create_case_assignment_system.php`** - Combined:
   - `2025_08_27_213837_create_case_assignments_table.php`
   - `2025_08_27_213952_remove_assigned_to_from_reports_table.php`
   - `2025_08_28_000001_recreate_case_assignments_table.php`

## Migrations to Keep

- `0001_01_01_000001_create_cache_table.php` - Laravel default
- `0001_01_01_000002_create_jobs_table.php` - Laravel default
- `2025_07_26_074756_create_reports_table.php` - Updated (removed assigned_to column)

## Migrations to Delete (After Testing)

### Profile-related migrations:
- `2025_08_12_063104_create_user_profiles_table.php`
- `2025_08_12_063105_create_law_enforcement_profiles_table.php`
- `2025_08_12_063105_create_social_worker_profiles_table.php`
- `2025_08_12_063106_create_public_user_profiles_table.php`
- `2025_08_14_024242_create_healthcare_profiles_table.php`
- `2025_08_14_024325_create_admin_profiles_table.php`
- `2025_08_14_030850_create_gov_official_profiles_table.php`

### Healthcare profile updates:
- `2025_08_23_010240_update_healthcare_profiles_table.php`
- `2025_08_23_215243_remove_moh_facility_code_from_healthcare_profiles_table.php`

### Case assignment system:
- `2025_08_27_213837_create_case_assignments_table.php`
- `2025_08_27_213952_remove_assigned_to_from_reports_table.php`
- `2025_08_28_000001_recreate_case_assignments_table.php`

### Initial tables:
- `2025_07_26_000002_create_roles_table.php`
- `2025_07_26_000003_create_users_table.php`
- `2025_08_11_185731_alter_sessions_user_id_to_uuid.php`

## Testing Steps

1. **Backup your database** before proceeding
2. **Reset migrations**: `php artisan migrate:reset`
3. **Run new migrations**: `php artisan migrate`
4. **Verify all tables are created correctly**
5. **Test application functionality**
6. **Delete old migration files** if everything works correctly

## Benefits of Combined Migrations

- **Reduced complexity**: From 17 migrations to 5
- **Better organization**: Related tables are created together
- **Easier maintenance**: Fewer files to manage
- **Cleaner history**: Logical grouping of database changes
- **Faster setup**: Fewer migration steps for new environments

## Notes

- The healthcare profiles table now includes the `profession` field by default and excludes the `moh_facility_code` field
- The sessions table now uses UUID from the start, eliminating the need for the alter migration
- The case assignment system is now properly structured with UUID primary keys
- All profile tables are created in a single migration for better organization

