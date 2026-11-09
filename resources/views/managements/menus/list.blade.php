@extends('layouts.master')
@section('content')
@php
    $inputCls = 'w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors';
    $labelCls = 'block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5';
    $modeBadge = [
        'link'     => 'bg-slate-100 text-slate-600',
        'section'  => 'bg-amber-100 text-amber-700',
        'dropdown' => 'bg-blue-100 text-blue-700',
        'hub'      => 'bg-violet-100 text-violet-700',
    ];
    $payload = collect($rows)->mapWithKeys(fn ($r) => [$r['menu']->uuid => [
        'title'         => $r['menu']->title,
        'key'           => $r['menu']->key,
        'description'   => $r['menu']->description,
        'icon'          => $r['menu']->icon,
        'color'         => $r['menu']->color,
        'display_mode'  => $r['menu']->display_mode,
        'parent_id'     => $r['menu']->parent_id,
        'route_name'    => $r['menu']->route_name,
        'url'           => $r['menu']->url,
        'active_routes' => $r['menu']->active_routes,
        'permission'    => $r['menu']->permission,
        'sort_order'    => $r['menu']->sort_order,
        'is_active'     => $r['menu']->is_active,
        'roles'         => $r['menu']->roles->pluck('id'),
        'id'            => $r['menu']->id,
    ]]);
    $defaultRoleIds = $roles->whereIn('name', ['Admin', 'Super Admin'])->pluck('id')->values();
@endphp
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">

        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Menu Management</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('settings.index') }}" class="text-slate-400"><i class="bx bx-cog"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('settings.index') }}" class="text-slate-400">Settings</a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">Menus</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto flex items-center gap-3">
                <span class="inline-flex items-center bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                    {{ count($rows) }} {{ Str::plural('Menu', count($rows)) }}
                </span>
                <button type="button" id="add_menu_btn" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    <i class="bx bx-plus"></i> Add Menu
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="bx bx-menu text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Menu Tree</h6>
                <span class="ms-auto text-xs text-slate-300">Changes apply to every user immediately</span>
            </div>
            <div class="p-6">
                <div class="table-responsive">
                    <table class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Menu</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Route / URL</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Permission</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Roles</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($rows as $row)
                            @php $menu = $row['menu']; @endphp
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors {{ $menu->is_active ? '' : 'opacity-50' }}">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2" style="padding-left: {{ $row['depth'] * 1.75 }}rem">
                                        @if ($row['depth'])<i class="bx bx-subdirectory-right text-slate-300"></i>@endif
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center shrink-0">
                                            <i class="bx {{ $menu->icon ?: ($menu->display_mode === 'section' ? 'bx-hash' : 'bx-circle') }} text-slate-600"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-slate-800">{{ $menu->title }}</div>
                                            <div class="text-xs text-slate-400">{{ $menu->key }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $modeBadge[$menu->display_mode] ?? '' }}">{{ ucfirst($menu->display_mode) }}</span>
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-600">
                                    {{ $menu->route_name ?: ($menu->url ?: '—') }}
                                    @if ($menu->route_name && ! Route::has($menu->route_name))
                                        <span class="text-red-500 font-semibold">(missing)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-600">{{ $menu->permission ?: '—' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse ($menu->roles as $role)
                                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">{{ $role->name }}</span>
                                        @empty
                                            <span class="text-xs text-red-500">No roles</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if ($menu->is_active)
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700">Active</span>
                                    @else
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-200 text-slate-600">Hidden</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        <button type="button" class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg move-btn" data-uuid="{{ $menu->uuid }}" data-direction="up" title="Move up"><i class="bx bx-up-arrow-alt"></i></button>
                                        <button type="button" class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg move-btn" data-uuid="{{ $menu->uuid }}" data-direction="down" title="Move down"><i class="bx bx-down-arrow-alt"></i></button>
                                        <button type="button" class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg edit-btn" data-uuid="{{ $menu->uuid }}" title="Edit"><i class="bx bx-edit text-sm"></i></button>
                                        <button type="button" class="inline-flex items-center justify-center w-8 h-8 {{ $menu->is_active ? 'bg-amber-500 hover:bg-amber-600' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white rounded-lg status-btn" data-uuid="{{ $menu->uuid }}" title="{{ $menu->is_active ? 'Hide' : 'Show' }}"><i class="bx {{ $menu->is_active ? 'bx-hide' : 'bx-show' }} text-sm"></i></button>
                                        <button type="button" class="inline-flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg delete-btn" data-uuid="{{ $menu->uuid }}" data-name="{{ $menu->title }}" title="Delete"><i class="bx bx-trash text-sm"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-sm text-slate-400">
                                    <i class="bx bx-menu text-3xl block mb-2"></i>
                                    No menus yet. Run <code>php artisan db:seed --class=MenuSeeder</code> or click <strong>Add Menu</strong>.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════ Create / Edit Modal ════════ --}}
<div class="modal fade" id="menuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center"><i class="bx bx-menu text-white text-lg"></i></div>
                    <h5 class="text-white font-semibold text-base mb-0" id="menu_modal_title">Add Menu</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form method="POST" id="menu_form"
                      data-store-url="{{ route('menus.store') }}"
                      data-update-url="{{ route('menus.update', ['menu' => '__UUID__']) }}">
                    @csrf
                    <input type="hidden" name="_method" id="form_method" value="POST">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                        <div>
                            <label class="{{ $labelCls }}">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" class="{{ $inputCls }}" required>
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Key <span class="normal-case font-normal text-slate-400">(auto from title)</span></label>
                            <input type="text" name="key" class="{{ $inputCls }}" placeholder="e.g. loan-reports">
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Type <span class="text-red-500">*</span></label>
                            <select name="display_mode" id="display_mode" class="{{ $inputCls }}" required>
                                @foreach ($modes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Parent</label>
                            <select name="parent_id" class="{{ $inputCls }}">
                                <option value="">— Top level —</option>
                                @foreach ($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->title }} ({{ $parent->display_mode }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div data-needs-target>
                            <label class="{{ $labelCls }}">Route</label>
                            <input type="text" name="route_name" list="route_names" class="{{ $inputCls }}" placeholder="Start typing a route name...">
                            <datalist id="route_names">
                                @foreach ($routeNames as $name)<option value="{{ $name }}">@endforeach
                            </datalist>
                        </div>
                        <div data-needs-target>
                            <label class="{{ $labelCls }}">URL <span class="normal-case font-normal text-slate-400">(if no route)</span></label>
                            <input type="text" name="url" class="{{ $inputCls }}" placeholder="/path or https://...">
                        </div>
                        <div class="md:col-span-2">
                            <label class="{{ $labelCls }}">Active route patterns <span class="normal-case font-normal text-slate-400">(comma separated, e.g. loan.*,customers.show)</span></label>
                            <input type="text" name="active_routes" class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Required permission</label>
                            <select name="permission" class="{{ $inputCls }}">
                                <option value="">— None —</option>
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission }}">{{ $permission }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Icon <span class="normal-case font-normal text-slate-400">(boxicons)</span></label>
                            <div class="flex items-center gap-2">
                                <input type="text" name="icon" id="icon_input" class="{{ $inputCls }}" placeholder="bx-cog">
                                <i id="icon_preview" class="bx bx-cog text-2xl text-slate-600"></i>
                            </div>
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Card color <span class="normal-case font-normal text-slate-400">(hub cards)</span></label>
                            <select name="color" class="{{ $inputCls }}">
                                <option value="">Default</option>
                                @foreach ($colors as $color)<option value="{{ $color }}">{{ ucfirst($color) }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Sort order</label>
                            <input type="number" name="sort_order" class="{{ $inputCls }}" placeholder="auto">
                        </div>
                        <div class="md:col-span-2">
                            <label class="{{ $labelCls }}">Description <span class="normal-case font-normal text-slate-400">(shown on hub cards)</span></label>
                            <input type="text" name="description" class="{{ $inputCls }}">
                        </div>
                        <div class="flex items-end">
                            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" checked> Active (visible)
                            </label>
                        </div>
                    </div>

                    <h6 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 pt-4 border-t border-slate-100">Visible to roles</h6>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        @foreach ($roles as $role)
                            <label class="inline-flex items-center gap-2 text-sm text-slate-700 rounded-lg border border-slate-200 px-3 py-2">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="form-check-input role-check"> {{ $role->name }}
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-slate-400 mt-2 mb-0">A menu is shown when the user has one of these roles (and the required permission, if set). Parents are hidden automatically when none of their children are visible.</p>

                    <div id="menu_alert" class="mt-4"></div>

                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg" data-bs-dismiss="modal"><i class="bx bx-x"></i> Cancel</button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg" id="menu_submit_btn"><i class="bx bx-save"></i> Save Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    function init() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const menus     = @json($payload);
        const defaults  = {
            title: '', key: '', description: '', icon: '', color: '', display_mode: 'link', parent_id: '',
            route_name: '', url: '', active_routes: '', permission: '', sort_order: '', is_active: true,
            roles: @json($defaultRoleIds), id: null,
        };
        const form      = document.getElementById('menu_form');
        const modal     = bootstrap.Modal.getOrCreateInstance(document.getElementById('menuModal'));
        const alertDiv  = document.getElementById('menu_alert');
        const submitBtn = document.getElementById('menu_submit_btn');
        const baseUrl   = "{{ url('settings/menus') }}/";

        function refresh() {
            const mode = document.getElementById('display_mode').value;
            form.querySelectorAll('[data-needs-target]').forEach(el => el.style.display = mode === 'section' ? 'none' : '');
            const icon = document.getElementById('icon_input').value.trim() || 'bx-circle';
            document.getElementById('icon_preview').className = 'bx ' + icon + ' text-2xl text-slate-600';
        }
        form.addEventListener('input', refresh);
        form.addEventListener('change', refresh);

        function fill(values) {
            Object.entries(values).forEach(([key, val]) => {
                if (key === 'roles') {
                    const ids = (val || []).map(String);
                    form.querySelectorAll('.role-check').forEach(c => c.checked = ids.includes(c.value));
                    return;
                }
                const checkbox = form.querySelector(`input[type="checkbox"][name="${key}"]`);
                if (checkbox) { checkbox.checked = !!val; return; }
                const field = form.querySelector(`[name="${key}"]:not([type="hidden"])`);
                if (field) field.value = val ?? '';
            });
            // A menu cannot be its own parent.
            form.querySelectorAll('select[name="parent_id"] option').forEach(o => o.disabled = values.id && o.value == values.id);
            refresh();
        }

        document.getElementById('add_menu_btn').addEventListener('click', () => {
            form.action = form.dataset.storeUrl;
            document.getElementById('form_method').value = 'POST';
            document.getElementById('menu_modal_title').textContent = 'Add Menu';
            alertDiv.innerHTML = '';
            fill(defaults);
            modal.show();
        });

        document.querySelectorAll('.edit-btn').forEach(btn => btn.addEventListener('click', function () {
            const uuid = this.dataset.uuid;
            form.action = form.dataset.updateUrl.replace('__UUID__', uuid);
            document.getElementById('form_method').value = 'PUT';
            document.getElementById('menu_modal_title').textContent = 'Update Menu';
            alertDiv.innerHTML = '';
            fill(Object.assign({}, defaults, menus[uuid] || {}));
            modal.show();
        }));

        async function send(url, options) {
            const response = await fetch(url, Object.assign({
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            }, options));
            if (!(response.headers.get('content-type') || '').includes('application/json')) {
                throw new Error(`Server returned ${response.status}. Check the Network tab.`);
            }
            const data = await response.json();
            if (response.status === 422 && data.errors) throw new Error(Object.values(data.errors).flat().join('<br>'));
            if (response.status === 419) throw new Error('Session expired. Please reload the page.');
            if (!response.ok || data.success === false) throw new Error(data.debug || data.message || 'An error occurred');
            return data;
        }

        function notify(msg, type) {
            if (window.$ && $.notify) { $.notify(msg, type); } else { alert(msg); }
        }

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            submitBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Saving...';
            submitBtn.disabled = true;
            alertDiv.innerHTML = '';
            try {
                const data = await send(form.action, { method: 'POST', body: new FormData(form) });
                alertDiv.innerHTML = `<div class="alert alert-success"><i class="bx bx-check-circle me-2"></i>${data.message}</div>`;
                setTimeout(() => location.reload(), 700);
            } catch (err) {
                alertDiv.innerHTML = `<div class="alert alert-danger"><i class="bx bx-error-circle me-2"></i>${err.message}</div>`;
            } finally {
                submitBtn.innerHTML = '<i class="bx bx-save"></i> Save Menu';
                submitBtn.disabled = false;
            }
        });

        document.querySelectorAll('.move-btn').forEach(btn => btn.addEventListener('click', function () {
            const body = new FormData();
            body.append('direction', this.dataset.direction);
            send(baseUrl + this.dataset.uuid + '/move', { method: 'POST', body })
                .then(() => location.reload())
                .catch(err => notify(err.message, 'error'));
        }));

        document.querySelectorAll('.status-btn').forEach(btn => btn.addEventListener('click', function () {
            send(baseUrl + this.dataset.uuid + '/status', { method: 'POST' })
                .then(d => { notify(d.message, 'success'); setTimeout(() => location.reload(), 400); })
                .catch(err => notify(err.message, 'error'));
        }));

        document.querySelectorAll('.delete-btn').forEach(btn => btn.addEventListener('click', function () {
            const { uuid, name } = this.dataset;
            swal({
                title: 'Delete Menu',
                text: `Are you sure you want to delete "${name}"?`,
                type: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, Delete', cancelButtonText: 'Cancel'
            }, function () {
                send(baseUrl + uuid, { method: 'DELETE' })
                    .then(d => { notify(d.message, 'success'); setTimeout(() => location.reload(), 400); })
                    .catch(err => notify(err.message, 'error'));
            });
        }));
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush
