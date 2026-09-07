@extends('layouts.master')

@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        <div class="page-breadcrumb d-flex align-items-center mb-4">
            <div class="breadcrumb-title pe-3"><span class="text-lg font-bold text-slate-700">Roles & Permissions</span></div>
            <div class="ms-auto">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal"><i class="bx bx-plus"></i> Add Role</button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4"><h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Role access</h6></div>
            <div class="p-4 table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Role</th><th>Assigned permissions</th><th class="text-end">Action</th></tr></thead>
                    <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td class="fw-semibold">{{ $role->name }}</td>
                            <td>
                                @forelse($role->permissions as $permission)
                                    <span class="badge bg-light text-dark border me-1 mb-1">{{ $permission->name }}</span>
                                @empty
                                    <span class="text-muted">No permissions assigned</span>
                                @endforelse
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#roleModal{{ $role->id }}"><i class="bx bx-shield-quarter"></i> Permissions</button>
                                @if(!in_array($role->name, ['Admin', 'Super Admin', 'Agent', 'Customer', 'Internal User']))
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this role?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createRoleModal" tabindex="-1"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('roles.store') }}">
    @csrf
    <div class="modal-header"><h5 class="modal-title">Create Role</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <label class="form-label">Role name</label><input class="form-control mb-3" name="name" required maxlength="125">
        <label class="form-label">Permissions</label>
        <div class="row">@foreach($permissions as $permission)<div class="col-md-6 mb-2"><label class="form-check-label"><input class="form-check-input me-1" type="checkbox" name="permissions[]" value="{{ $permission->name }}"> {{ $permission->name }}</label></div>@endforeach</div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary">Create role</button></div>
</form></div></div>

@foreach($roles as $role)
<div class="modal fade" id="roleModal{{ $role->id }}" tabindex="-1"><div class="modal-dialog modal-lg"><form class="modal-content" method="POST" action="{{ route('roles.update', $role) }}">
    @csrf @method('PUT')
    <div class="modal-header"><h5 class="modal-title">Permissions: {{ $role->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body"><div class="row">
        @foreach($permissions as $permission)<div class="col-md-6 mb-2"><label class="form-check-label"><input class="form-check-input me-1" type="checkbox" name="permissions[]" value="{{ $permission->name }}" @checked($role->permissions->contains('name', $permission->name))> {{ $permission->name }}</label></div>@endforeach
    </div></div>
    <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary">Save permissions</button></div>
</form></div></div>
@endforeach
@endsection
