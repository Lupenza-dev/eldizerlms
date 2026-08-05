@extends('layouts.master')
@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">User Management</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}" class="text-slate-400"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">Edit {{ $user->name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    <i class="bx bx-arrow-back"></i> Back to Users
                </a>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="bx bx-user-check text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Edit User: {{ $user->name }}</h6>
            </div>

            {{-- Form --}}
            <div class="p-6">
                <form id="update_form" action="{{ route('update.user') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->uuid }}">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label text-sm font-medium text-slate-700">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required minlength="3" maxlength="50">
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label text-sm font-medium text-slate-700">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="phone_number" class="form-label text-sm font-medium text-slate-700">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $user->phone_number }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-sm font-medium text-slate-700 d-block mb-2">Roles</label>
                            <div class="row g-3">
                                @foreach($roles as $role)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]" id="role_{{ Str::slug($role->name) }}" value="{{ $role->name }}" {{ $user->roles->pluck('name')->contains($role->name) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role_{{ Str::slug($role->name) }}">{{ $role->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-sm font-medium text-slate-700 d-block mb-2">Permissions</label>
                            <div class="row g-3">
                                @foreach($permissions as $permission)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" id="permission_{{ Str::slug($permission->name) }}" value="{{ $permission->name }}" {{ $user->permissions->pluck('name')->contains($permission->name) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission_{{ Str::slug($permission->name) }}">{{ $permission->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex gap-2">
                        <button type="button" id="update_btn" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                            <i class="bx bx-save me-1"></i> Update User
                        </button>
                    </div>

                    <div id="alert" class="mt-3"></div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('update_btn').addEventListener('click', function(e) {
        e.preventDefault();

        const btn = document.getElementById('update_btn');
        const alertDiv = document.getElementById('alert');
        const originalBtn = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Updating...';
        alertDiv.innerHTML = '';

        const formData = new FormData(document.getElementById('update_form'));

        fetch("{{ route('update.user') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json().catch(() => ({})))
        .then(data => {
            if (data.success) {
                alertDiv.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                setTimeout(() => window.location.href = "{{ route('users.index') }}", 1500);
            } else {
                let message = data.message || 'An error occurred';
                if (data.errors && typeof data.errors === 'object') {
                    message = Object.values(data.errors).flat().join('<br>');
                }
                alertDiv.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertDiv.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-2"></i>An error occurred. Please try again.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalBtn;
        });
    });
</script>
@endpush
