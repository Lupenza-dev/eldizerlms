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
                                @if (Route::has('roles.menus'))
                                <button class="btn btn-sm btn-outline-secondary role-menus-btn" data-role-id="{{ $role->id }}"><i class="bx bx-menu"></i> Menus</button>
                                @endif
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

{{-- ════════ Role → Menus modal ════════ --}}
<div class="modal fade" id="roleMenusModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Menus: <span id="role_menus_name"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <p class="text-muted small mb-3">Tick the menus this role can see. Checking a submenu also grants its parents. A menu with a required permission is still hidden if the role lacks that permission.</p>
        <div class="d-flex gap-2 mb-3">
            <button type="button" class="btn btn-sm btn-light" id="role_menus_all">Select all</button>
            <button type="button" class="btn btn-sm btn-light" id="role_menus_none">Clear</button>
        </div>
        <div id="role_menus_tree"><div class="text-center text-muted py-4"><i class="bx bx-loader bx-spin"></i> Loading...</div></div>
        <div id="role_menus_alert" class="mt-3"></div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="role_menus_save">Save menus</button>
    </div>
</div></div></div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    function init() {
        const modalEl = document.getElementById('roleMenusModal');
        if (!modalEl) return;
        const modal   = bootstrap.Modal.getOrCreateInstance(modalEl);
        const treeDiv = document.getElementById('role_menus_tree');
        const alert   = document.getElementById('role_menus_alert');
        const saveBtn = document.getElementById('role_menus_save');
        const csrf    = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const baseUrl = "{{ url('roles') }}/";
        let roleId = null;

        const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

        function render(nodes, assigned, depth) {
            return nodes.map(n => `
                <div class="menu-node" style="margin-left:${depth * 1.5}rem">
                    <label class="form-check-label d-flex align-items-center gap-2 py-1 ${n.is_active ? '' : 'text-muted'}">
                        <input class="form-check-input menu-check" type="checkbox" value="${n.id}" ${assigned.includes(n.id) ? 'checked' : ''}>
                        <i class="bx ${esc(n.icon || (n.mode === 'section' ? 'bx-hash' : 'bx-circle'))}"></i>
                        <span class="${n.children.length ? 'fw-semibold' : ''}">${esc(n.title)}</span>
                        <span class="badge bg-light text-secondary border">${esc(n.mode)}</span>
                        ${n.is_active ? '' : '<span class="badge bg-secondary">hidden</span>'}
                    </label>
                    <div class="menu-children">${render(n.children, assigned, 1)}</div>
                </div>`).join('');
        }

        // Checking a parent toggles its whole subtree; checking a child also checks its ancestors.
        treeDiv.addEventListener('change', function (e) {
            if (!e.target.classList.contains('menu-check')) return;
            const node = e.target.closest('.menu-node');
            node.querySelectorAll('.menu-children .menu-check').forEach(c => c.checked = e.target.checked);
            if (e.target.checked) {
                let parent = node.parentElement.closest('.menu-node');
                while (parent) {
                    parent.querySelector(':scope > label .menu-check').checked = true;
                    parent = parent.parentElement.closest('.menu-node');
                }
            }
        });

        document.getElementById('role_menus_all').addEventListener('click', () => treeDiv.querySelectorAll('.menu-check').forEach(c => c.checked = true));
        document.getElementById('role_menus_none').addEventListener('click', () => treeDiv.querySelectorAll('.menu-check').forEach(c => c.checked = false));

        document.querySelectorAll('.role-menus-btn').forEach(btn => btn.addEventListener('click', async function () {
            roleId = this.dataset.roleId;
            alert.innerHTML = '';
            treeDiv.innerHTML = '<div class="text-center text-muted py-4"><i class="bx bx-loader bx-spin"></i> Loading...</div>';
            modal.show();
            try {
                const res  = await fetch(baseUrl + roleId + '/menus', { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                document.getElementById('role_menus_name').textContent = data.role;
                treeDiv.innerHTML = data.tree.length
                    ? render(data.tree, data.assigned, 0)
                    : '<div class="text-muted">No menus defined yet. Seed them with <code>php artisan db:seed --class=MenuSeeder</code>.</div>';
            } catch (err) {
                treeDiv.innerHTML = '<div class="alert alert-danger">Failed to load menus.</div>';
            }
        }));

        saveBtn.addEventListener('click', async function () {
            saveBtn.disabled = true;
            alert.innerHTML = '';
            const body = new FormData();
            body.append('_method', 'PUT');
            treeDiv.querySelectorAll('.menu-check:checked').forEach(c => body.append('menu_ids[]', c.value));
            try {
                const res  = await fetch(baseUrl + roleId + '/menus', {
                    method: 'POST', body,
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                if (!res.ok || !data.success) throw new Error(data.message || 'Failed to save');
                alert.innerHTML = `<div class="alert alert-success mb-0">${esc(data.message)}</div>`;
                setTimeout(() => location.reload(), 700);
            } catch (err) {
                alert.innerHTML = `<div class="alert alert-danger mb-0">${esc(err.message)}</div>`;
            } finally {
                saveBtn.disabled = false;
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush
