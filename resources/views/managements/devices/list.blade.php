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
                    <table id="devices_table" class="table w-full" style="width:100%">
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
                                                data-images="{{ base64_encode(json_encode(
                                                     is_array($device->images)
                                                         ? $device->images
                                                         : (json_decode($device->images ?? '[]', true) ?: [])
                                                 )) }}"
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
                                Device Images <span class="text-xs text-blue-600 normal-case font-normal">(drag & drop or browse)</span>
                            </label>
                            <input type="file" name="images[]" multiple id="image_upload"
                                   accept="image/jpeg,image/png,image/gif,image/webp" required>
                            <p class="text-xs text-slate-400 mt-1">Formats: JPG, PNG, GIF, WebP. Max 2MB per image. Drag to reorder.</p>
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
                                Add New Images <span class="text-xs text-blue-600 normal-case font-normal">(optional, drag & drop)</span>
                            </label>
                            <input type="file" name="images[]" multiple id="edit_image_upload"
                                   accept="image/jpeg,image/png,image/gif,image/webp">
                            <p class="text-xs text-slate-400 mt-1">New images will be added to the existing ones.</p>
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

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/filepond@4.32.7/dist/filepond.min.css">
<link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview@4.6.12/dist/filepond-plugin-image-preview.min.css">
@endpush

@push('scripts')
<script src="https://unpkg.com/filepond@4.32.7/dist/filepond.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview@4.6.12/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type@1.2.9/dist/filepond-plugin-file-validate-type.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size@2.2.8/dist/filepond-plugin-file-validate-size.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (!window.FilePond) {
        console.error('FilePond is not loaded. Check FilePond CSS/JS assets in layouts.master.');
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize
    );

    const acceptedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    const pondOptions = {
        allowMultiple: true,
        acceptedFileTypes: acceptedTypes,
        maxFileSize: '2MB',
        maxFiles: 10,
        allowReorder: true,
        allowBrowse: true,
        allowDrop: true,
        instantUpload: false,
        storeAsFile: true,
        labelIdle: 'Drag & drop images here or <span class="filepond--label-action">click to browse</span>',
        imagePreviewHeight: 100,
        stylePanelLayout: 'compact',
        credits: false
    };

    const registrationInput = document.getElementById('image_upload');
    const editInput = document.getElementById('edit_image_upload');
    const registrationForm = document.getElementById('registration_form');
    const updateForm = document.getElementById('update_form');
    const editModal = document.getElementById('editDeviceModal');
    const currentImages = document.getElementById('current_images');
    const registrationAlert = document.getElementById('alert');
    const updateAlert = document.getElementById('update_alert');

    const pond = FilePond.create(registrationInput, pondOptions);
    const editPond = FilePond.create(editInput, pondOptions);

    function showAlert(element, message, type = 'danger') {
        element.replaceChildren();
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.setAttribute('role', 'alert');
        alert.append(document.createTextNode(message));

        const close = document.createElement('button');
        close.type = 'button';
        close.className = 'btn-close';
        close.setAttribute('data-bs-dismiss', 'alert');
        close.setAttribute('aria-label', 'Close');
        alert.appendChild(close);
        element.appendChild(alert);
    }

    function syncPondToInput(filePond, input) {
        const transfer = new DataTransfer();
        filePond.getFiles().forEach(item => {
            if (item.file instanceof File) transfer.items.add(item.file);
        });
        input.files = transfer.files;
    }

    async function submitForm(form, button, alertElement, idleLabel) {
        const originalHtml = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Processing...';
        alertElement.replaceChildren();

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                const body = await response.text();
                console.error('Non-JSON response:', response.status, body.slice(0, 500));
                throw new Error(`Server returned ${response.status}. Check Laravel logs and the Network tab.`);
            }

            const data = await response.json();

            if (response.status === 422) {
                throw new Error(Object.values(data.errors || {}).flat().join(' ') || data.message || 'Validation failed.');
            }
            if (response.status === 419) {
                throw new Error('Session expired. Please reload the page and try again.');
            }
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Unable to save the device.');
            }

            showAlert(alertElement, data.message || 'Device saved successfully.', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } catch (error) {
            console.error('Device form submission failed:', error);
            showAlert(alertElement, error.message || 'An unexpected error occurred.');
        } finally {
            button.disabled = false;
            button.innerHTML = idleLabel || originalHtml;
        }
    }

    registrationForm.addEventListener('submit', function (event) {
        event.preventDefault();

        if (pond.getFiles().length === 0) {
            showAlert(registrationAlert, 'Please add at least one image.');
            return;
        }

        syncPondToInput(pond, registrationInput);
        submitForm(
            this,
            document.getElementById('reg_btn'),
            registrationAlert,
            '<i class="bx bx-save me-1"></i> Register Device'
        );
    });

    updateForm.addEventListener('submit', function (event) {
        event.preventDefault();

        // Only newly selected images are submitted as images[].
        // Existing images remain on the server unless remove_images[] is submitted.
        syncPondToInput(editPond, editInput);
        submitForm(
            this,
            document.getElementById('update_btn'),
            updateAlert,
            '<i class="bx bx-save me-1"></i> Update Device'
        );
    });

    function decodeImages(encoded) {
        if (!encoded) return [];
        try {
            const binary = atob(encoded);
            const bytes = Uint8Array.from(binary, char => char.charCodeAt(0));
            const json = new TextDecoder().decode(bytes);
            const parsed = JSON.parse(json);
            if (typeof parsed === 'string') return JSON.parse(parsed);
            return Array.isArray(parsed) ? parsed : [];
        } catch (error) {
            console.error('Unable to decode device images:', error);
            return [];
        }
    }

    function renderCurrentImages(images, form) {
        currentImages.replaceChildren();

        if (!images.length) {
            const empty = document.createElement('span');
            empty.className = 'text-xs text-slate-400';
            empty.textContent = 'No existing images. Add images below.';
            currentImages.appendChild(empty);
            return;
        }

        images.forEach(path => {
            if (typeof path !== 'string' || !path.trim()) return;

            const wrapper = document.createElement('div');
            wrapper.className = 'relative group flex flex-col items-center gap-1';

            const image = document.createElement('img');
            image.src = @json(asset('storage')) + '/' + path.replace(/^\/+/, '');
            image.alt = 'Device image';
            image.className = 'h-16 w-16 rounded-lg object-cover border-2 border-slate-200';
            image.onerror = function () {
                this.alt = 'Image could not be loaded';
                this.classList.add('opacity-50');
            };

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'text-xs text-red-600 hover:text-red-800';
            removeButton.textContent = 'Remove image';
            removeButton.addEventListener('click', function () {
                // Prevent duplicate remove markers for the same image.
                const alreadyMarked = [...form.querySelectorAll('input[name="remove_images[]"]')]
                    .some(input => input.value === path);
                if (!alreadyMarked) {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'remove_images[]';
                    hidden.value = path;
                    form.appendChild(hidden);
                }

                wrapper.remove();
                if (!currentImages.children.length) {
                    const empty = document.createElement('span');
                    empty.className = 'text-xs text-slate-400';
                    empty.textContent = 'No remaining images. Add new images below.';
                    currentImages.appendChild(empty);
                }
            });

            wrapper.append(image, removeButton);
            currentImages.appendChild(wrapper);
        });
    }

    // Populate data when the Edit modal is opened by an Edit button.
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button || !button.classList.contains('edit-btn')) return;

        const uuid = button.dataset.uuid || '';
        updateForm.action = updateForm.dataset.urlTemplate.replace(
            '__UUID__',
            encodeURIComponent(uuid)
        );

        document.getElementById('edit_uuid').value = uuid;
        document.getElementById('edit_name').value = button.dataset.name || '';
        document.getElementById('edit_description').value = button.dataset.description || '';
        document.getElementById('edit_price').value = button.dataset.price || '';
        document.getElementById('edit_plan').value = button.dataset.plan || '';
        document.getElementById('edit_initial_deposit').value = button.dataset.initial_deposit || '';
        document.getElementById('edit_device_category').value = button.dataset.category_id || '';

        updateAlert.replaceChildren();
        updateForm.querySelectorAll('input[name="remove_images[]"]').forEach(input => input.remove());
        editPond.removeFiles();

        const images = decodeImages(button.dataset.images || '');
        renderCurrentImages(images, updateForm);
    });

    document.getElementById('exampleLargeModal').addEventListener('hidden.bs.modal', function () {
        registrationForm.reset();
        pond.removeFiles();
        registrationAlert.replaceChildren();
    });

    // Initialize DataTables once, if the plugin is available.
    try {
        if (window.jQuery && jQuery.fn.DataTable && document.getElementById('devices_table')) {
            if (!jQuery.fn.DataTable.isDataTable('#devices_table')) {
                jQuery('#devices_table').DataTable();
            }
        }
    } catch (error) {
        console.warn('DataTable initialization skipped:', error.message);
    }

    const recordCount = document.getElementById('record-count');
    if (recordCount) {
        const count = @json($devices->count());
        recordCount.textContent = count + ' Device' + (count !== 1 ? 's' : '');
    }
});

// Delete device — preserves the existing route pattern.
function delete_device(uuid) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const url = @json(url('devices')) + '/' + encodeURIComponent(uuid);

    const confirmDelete = () => {
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async response => {
            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                throw new Error('Server returned a non-JSON response (' + response.status + ').');
            }
            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Failed to delete device.');
            }
            if (window.jQuery && jQuery.notify) {
                jQuery.notify(data.message || 'Device deleted successfully.', 'success');
            }
            setTimeout(() => window.location.reload(), 500);
        })
        .catch(error => {
            console.error('Delete device failed:', error);
            if (window.jQuery && jQuery.notify) {
                jQuery.notify(error.message || 'An error occurred.', 'error');
            } else {
                alert(error.message || 'An error occurred.');
            }
        });
    };

    if (window.swal) {
        swal({
            title: 'Delete Device',
            text: 'Are you sure you want to delete this device?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            closeOnConfirmation: false
        }, confirmDelete);
    } else if (window.confirm('Are you sure you want to delete this device?')) {
        confirmDelete();
    }
}
</script>
@endpush
