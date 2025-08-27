# Multi-Assignee Case Management System

## Overview
The Laravel case management system has been updated to support multiple assignees per case, enabling true multi-role collaboration. This replaces the previous single-assignee system with a more flexible and collaborative approach.

## Key Features

### 1. Multiple Assignees per Case
- Cases can now be assigned to multiple users simultaneously
- Each assignee can view and update the case
- All assignees automatically see cases assigned to them in their dashboard

### 2. Primary Assignee (Lead)
- One assignee can be designated as the primary/lead assignee
- Primary assignee is clearly marked with a crown icon
- Ensures accountability while maintaining collaboration

### 3. Enhanced UI
- Select2 multi-select dropdown for choosing multiple assignees
- Clear visual distinction between primary and secondary assignees
- Badge system showing all assignees with role indicators

## Database Changes

### New Table: `case_assignments`
```sql
- report_id (UUID) - Foreign key to reports table
- user_id (UUID) - Foreign key to users table  
- is_primary (boolean) - Marks the primary assignee
- assigned_at (timestamp) - When the assignment was made
- unassigned_at (timestamp) - When the assignment was removed (soft delete)
```

### Removed Column
- `assigned_to` column removed from `reports` table

## Model Relationships

### Report Model
```php
// Get all assignees for this case
public function assignees()

// Get the primary assignee for this case  
public function primaryAssignee()

// Get all active assignments for this case
public function assignments()

// Scope to get cases assigned to a specific user
public function scopeAssignedTo($query, $userId)
```

### User Model
```php
// Get all cases assigned to this user
public function assignedCases()

// Get cases where this user is the primary assignee
public function primaryAssignedCases()

// Get all assignments for this user
public function caseAssignments()
```

## Controller Updates

### CaseController Changes
- Updated `store()` method to handle multiple assignees
- Updated `update()` method to manage assignment changes
- Updated `show()` method to display all assignees
- Updated `reportData()` method to show multiple assignees in DataTables

## Form Updates

### Create/Edit Forms
- Replaced single-select dropdown with Select2 multi-select
- Added separate dropdown for primary assignee selection
- Validation ensures primary assignee is one of the selected assignees

### Validation Rules
```php
'assignees' => 'nullable|array',
'assignees.*' => 'string|exists:users,id',
'primary_assignee' => 'nullable|string|exists:users,id',
```

## UI Enhancements

### Case List View
- Shows all assignees with primary assignee marked as "(Lead)"
- Maintains existing DataTable functionality

### Case Detail View
- Displays all assignees with role information
- Primary assignee highlighted with crown icon
- Assignment date tracking
- Dedicated "Case Assignments" section

### Assignment Cards
- Visual cards for each assignee
- Color coding (green for primary, blue for secondary)
- Role and assignment date information

## Usage Examples

### Assigning Multiple Users to a Case
1. Go to Cases → Add New Case or Edit Existing Case
2. In the "Assign To (Multiple)" field, select multiple users
3. In the "Primary Assignee (Lead)" field, select one user as the lead
4. Save the case

### Viewing Assigned Cases
- Users can access `auth()->user()->assignedCases` to see all cases assigned to them
- Primary assignees can access `auth()->user()->primaryAssignedCases` for lead cases

### Updating Case Status
- Any assignee can update the case status
- Changes are tracked with `last_updated_by` and `status_updated_at`

## Migration Notes

### Existing Data
- The migration automatically removes the old `assigned_to` column
- No data migration is needed if the system is new
- For existing systems with data, a separate migration script may be needed

### Backward Compatibility
- All existing functionality is preserved
- New features are additive and don't break existing workflows
- Role-based access control remains the same

## Benefits

1. **Improved Collaboration**: Multiple stakeholders can work on the same case
2. **Clear Accountability**: Primary assignee ensures someone is responsible
3. **Better Resource Allocation**: Cases can be assigned to teams rather than individuals
4. **Enhanced Transparency**: All assignees are clearly visible
5. **Flexible Workflows**: Supports various collaboration patterns

## Future Enhancements

Potential future improvements could include:
- Assignment history tracking
- Assignment notifications
- Team-based assignment templates
- Assignment workload balancing
- Assignment approval workflows
