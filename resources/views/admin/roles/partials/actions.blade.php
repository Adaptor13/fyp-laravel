<div class="btn-group" role="group">
    <a href="{{ route('roles.show', $role->id) }}" class="btn btn-sm btn-info" title="View Role">
        <i class="ti ti-eye"></i>
    </a>
    
    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning" title="Edit Role">
        <i class="ti ti-edit"></i>
    </a>
    
    <a href="{{ route('roles.assign-permissions', $role->id) }}" class="btn btn-sm btn-primary" title="Assign Permissions">
        <i class="ti ti-key"></i>
    </a>
    
    @if($role->users_count == 0)
        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline" 
              onsubmit="return confirm('Are you sure you want to delete this role?')">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-sm btn-danger" title="Delete Role">
                <i class="ti ti-trash"></i>
            </button>
        </form>
    @else
        <button type="button" class="btn btn-sm btn-danger" disabled title="Cannot delete role with assigned users">
            <i class="ti ti-trash"></i>
        </button>
    @endif
</div>
