@extends('layouts.master')
@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">

        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Device Management</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-slate-400"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto flex items-center gap-3">
                <span class="inline-flex items-center bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full" id="record-count">
                    {{ $devices->count() }} Devices
                </span>
                <button type="button" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">
                    <i class="bx bx-plus"></i> Add Device
                </button>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="bx bx-devices text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Device Inventory</h6>
            </div>
            <div class="p-6">
                <div class="table-responsive">
                    <table id="example" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Image</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Reg Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Price</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Plan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Initial Deposit</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Category</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($devices as $device)
                            @php
                                $firstImage = is_array($device->images) && count($device->images) ? $device->images[0] : null;
                                $imageCount = is_array($device->images) ? count($device->images) : 0;
                            @endphp
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="relative inline-block">
                                        @if ($firstImage)
                                            <img class="h-12 w-12 rounded-xl object-cover border-2 border-slate-200 shadow-sm"
                                                 src="{{ asset('storage/' . $firstImage) }}"
                                                 alt="{{ $device->name }}"
                                                 onerror="this.onerror=null; this.src='https://picsum.photos/seed/device{{ $device->id }}/64/64.jpg';">
                                        @else
                                            <img class="h-12 w-12 rounded-xl object-cover border-2 border-slate-200 shadow-sm"
                                                 src="https://picsum.photos/seed/device{{ $device->id }}/64/64.jpg"
                                                 alt="{{ $device->name }}">
                                        @endif
                                        @if ($imageCount > 1)
                                            <span class="absolute -top-1 -right-1 bg-blue-600 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                                {{ $imageCount }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ $device->created_at?->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-sm font-semibold text-slate-800">{{ $device->name }}</span>
                                        <span class="text-xs text-slate-400 line-clamp-1">{{ \Illuminate\Support\Str::limit($device->description, 40) }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-slate-700 whitespace-nowrap">{{ number_format($device->price, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ $device->plan }} months</td>
                                <td class="px-4 py-3 text-sm font-medium text-blue-600 whitespace-nowrap">{{ number_format($device->initial_deposit, 2) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                        {{ $device->device_category?->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        <button class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors edit-btn"
                                                data-bs-toggle="modal" data-bs-target="#editDeviceModal"
                                                data-uuid="{{ $device->uuid }}"
                                                data-name="{{ $device->name }}"
                                                data-description="{{ $device->description }}"
                                                data-price="{{ $device->price }}"
                                                data-plan="{{ $device->plan }}"
                                                data-initial_deposit="{{ $device->initial_deposit }}"
                                                data-category_id="{{ $device->device_category_id }}"
                                                data-images='@json($device->images ?? [])'
                                                title="Edit Device">
                                            <i class="bx bx-edit text-sm"></i>
                                        </button>
                                        <button class="inline-flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors"
                                                data-uuid="{{ $device->uuid }}"
                                                onclick="delete_device(this.dataset.uuid)"
                                                title="Delete Device">
                                            <i class="bx bx-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════ Registration Modal ════════ --}}
<div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-mobile-alt text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Device Registration</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="{{ route('devices.store') }}" method="POST" enctype="multipart/form-data" id="registration_form">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Device Name</label>
                            <input type="text" name="name"
                                   class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Enter device name..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Device Price</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm font-medium text-slate-600 bg-slate-100 border border-r-0 border-slate-300 rounded-l-lg">TZS</span>
                                <input type="number" name="price"
                                       class="flex-1 min-w-0 px-3 py-2 text-sm border border-slate-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="0.00" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Initial Deposit</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm font-medium text-slate-600 bg-slate-100 border border-r-0 border-slate-300 rounded-l-lg">TZS</span>
                                <input type="number" name="initial_deposit"
                                       class="flex-1 min-w-0 px-3 py-2 text-sm border border-slate-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="0.00" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Payment Plan (months)</label>
                            <input type="number" name="plan"
                                   class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Enter payment plan..." min="1" required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Description</label>
                            <textarea name="description" rows="3"
                                      class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                      placeholder="Describe the device (specs, features, condition...)"></textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Device Category</label>
                            <select name="device_category"
                                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                <option value="">Select a category</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                                Device Images <span class="text-xs text-blue-600 normal-case font-normal">(unaweza kuchagua zaidi ya moja)</span>
                            </label>
                            <input type="file" name="images[]" multiple
                                   class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   accept="image/jpeg,image/png,image/gif,image/webp" required>
                            <p class="text-xs text-slate-400 mt-1">Formats: JPG, PNG, GIF, WebP. Kila picha max 2MB. Ctrl/Cmd + click kuchagua nyingi.</p>
                        </div>
                        <div class="sm:col-span-2" id="alert"></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="reg_btn">
                            <i class="bx bx-save"></i> Register Device
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ════════ Edit Modal ════════ --}}
<div class="modal fade" id="editDeviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-600 to-cyan-800 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-edit text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Update Device</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="{{ route('devices.update', ['device' => '__UUID__']) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="update_form"
                      data-url-template="{{ route('devices.update', ['device' => '__UUID__']) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_uuid">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Device Name</label>
                            <input type="text" id="edit_name" name="name"
                                   class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Device Price</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm font-medium text-slate-600 bg-slate-100 border border-r-0 border-slate-300 rounded-l-lg">TZS</span>
                                <input type="number" id="edit_price" name="price"
                                       class="flex-1 min-w-0 px-3 py-2 text-sm border border-slate-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       min="0" step="0.01" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Initial Deposit</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm font-medium text-slate-600 bg-slate-100 border border-r-0 border-slate-300 rounded-l-lg">TZS</span>
                                <input type="number" id="edit_initial_deposit" name="initial_deposit"
                                       class="flex-1 min-w-0 px-3 py-2 text-sm border border-slate-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       min="0" step="0.01" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Payment Plan (months)</label>
                            <input type="number" id="edit_plan" name="plan"
                                   class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   min="1" required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Description</label>
                            <textarea id="edit_description" name="description" rows="3"
                                      class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Device Category</label>
                            <select id="edit_device_category" name="device_category"
                                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                <option value="">Select a category</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Current Images</label>
                            <div id="current_images" class="flex flex-wrap gap-2 p-2 border border-slate-200 rounded-lg bg-slate-50 min-h-[60px]">
                                <span class="text-xs text-slate-400">Loading...</span>
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                                Add New Images <span class="text-xs text-blue-600 normal-case font-normal">(hiari)</span>
                            </label>
                            <input type="file" name="images[]" multiple
                                   class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   accept="image/jpeg,image/png,image/gif,image/webp">
                            <p class="text-xs text-slate-400 mt-1">Picha mpya zitaongezwa kwenye zilizopo.</p>
                        </div>
                        <div class="sm:col-span-2" id="update_alert"></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="update_btn">
                            <i class="bx bx-save"></i> Update Device
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // ═══ Record count ═══
    const recordCount = document.getElementById('record-count');
    if (recordCount) {
        const count = @json($devices->count());
        recordCount.textContent = count + ' Device' + (count !== 1 ? 's' : '');
    }

    // ═══ Shared submit helper ═══
    async function submitForm(url, formData, btn, alertDiv, idleLabel) {
        btn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Processing...';
        btn.disabled = true;
        alertDiv.innerHTML = '';

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Non-JSON response:', response.status, text.slice(0, 500));
                throw new Error(`Server returned ${response.status}. Angalia Network tab.`);
            }

            const data = await response.json();

            if (response.status === 422) {
                const msgs = Object.values(data.errors || {}).flat().join('<br>');
                throw new Error(msgs || data.message || 'Validation failed');
            }
            if (response.status === 419) {
                throw new Error('Session imeisha muda. Reload page.');
            }
            if (response.status >= 500) {
                throw new Error(data.debug || data.message || 'Server error.');
            }
            if (!data.success) {
                throw new Error(data.message || 'An error occurred');
            }

            alertDiv.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle me-2"></i>${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            setTimeout(() => location.reload(), 1200);

        } catch (err) {
            console.error(err);
            alertDiv.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-2"></i>${err.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
        } finally {
            btn.innerHTML = idleLabel;
            btn.disabled = false;
        }
    }

    // ═══ Registration form ═══
    document.getElementById('registration_form').addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm(
            this.action,
            new FormData(this),
            document.getElementById('reg_btn'),
            document.getElementById('alert'),
            '<i class="bx bx-save me-1"></i> Register Device'
        );
    });

    // ═══ Update form ═══
    document.getElementById('update_form').addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm(
            this.action,
            new FormData(this),
            document.getElementById('update_btn'),
            document.getElementById('update_alert'),
            '<i class="bx bx-save me-1"></i> Update Device'
        );
    });

    // ═══ Edit button — populate + set action ═══
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const uuid = this.dataset.uuid;
            const form = document.getElementById('update_form');

            // Weka action: /devices/{uuid}
            form.action = form.dataset.urlTemplate.replace('__UUID__', uuid);

            // Populate fields
            document.getElementById('edit_uuid').value            = uuid;
            document.getElementById('edit_name').value            = this.dataset.name || '';
            document.getElementById('edit_description').value     = this.dataset.description || '';
            document.getElementById('edit_price').value           = this.dataset.price || '';
            document.getElementById('edit_plan').value            = this.dataset.plan || '';
            document.getElementById('edit_initial_deposit').value = this.dataset.initial_deposit || '';
            document.getElementById('edit_device_category').value = this.dataset.category_id || '';

            // Onyesha current images
            const images = JSON.parse(this.dataset.images || '[]');
            const container = document.getElementById('current_images');
            container.innerHTML = '';

            // Safisha remove_images[] zilizobaki kutoka edit ya awali
            form.querySelectorAll('input[name="remove_images[]"]').forEach(el => el.remove());

            if (!images.length) {
                container.innerHTML = '<span class="text-xs text-slate-400">Hakuna picha. Ongeza mpya chini.</span>';
                return;
            }

            images.forEach(path => {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative group';
                wrapper.innerHTML = `
                    <img src="/storage/${path}" class="h-16 w-16 rounded-lg object-cover border-2 border-slate-200" onerror="this.onerror=null;this.src='https://picsum.photos/seed/fallback/64/64.jpg';">
                    <button type="button" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white rounded-full text-xs opacity-0 group-hover:opacity-100 transition-opacity" data-path="${path}">
                        <i class="bx bx-x"></i>
                    </button>
                `;
                wrapper.querySelector('button').addEventListener('click', function() {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'remove_images[]';
                    input.value = this.dataset.path;
                    form.appendChild(input);
                    wrapper.remove();

                    if (!container.children.length) {
                        container.innerHTML = '<span class="text-xs text-slate-400">Picha zote zimeondolewa. Ongeza mpya.</span>';
                    }
                });
                container.appendChild(wrapper);
            });
        });
    });
});

// ═══ Delete — fetch DELETE /devices/{uuid} ═══
function delete_device(uuid) {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var url = "{{ url('devices') }}/" + uuid;

    swal({
        title: "Delete Device",
        text: "Are you sure you want to Delete this Device?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        confirmButtonText: "Yes, Delete",
        cancelButtonText: "Cancel",
        closeOnConfirmation: false
    },
    function() {
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async (response) => {
            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                throw new Error('Server returned non-JSON (' + response.status + ')');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                $.notify(data.message, 'success');
                setTimeout(() => location.reload(), 500);
            } else {
                $.notify(data.message || 'Failed', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            $.notify(err.message || 'Error occurred', 'error');
        });
    });
}
</script>
@endpush