@extends('layouts.master')
@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Professional Management</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('settings.index') }}" class="text-slate-400"><i class="bx bx-cog"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('settings.index') }}" class="text-slate-400">Settings</a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">Professionals</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto flex items-center gap-3">
                <span class="inline-flex items-center bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full" id="record-count">
                    {{ $professionals->count() }} Professionals
                </span>
                <button type="button" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-toggle="modal" data-bs-target="#createProfessionalModal">
                    <i class="bx bx-plus"></i> Add Professional
                </button>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="bx bx-briefcase text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Professional Directory</h6>
            </div>
            <div class="p-6">
                <div class="table-responsive">
                    <table id="professionals_table" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Description</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Created</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($professionals as $professional)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-9 h-9 rounded-full bg-violet-100 flex items-center justify-center shrink-0">
                                            <i class="bx bx-briefcase text-violet-600"></i>
                                        </div>
                                        <span class="text-sm font-semibold text-slate-800">{{ $professional->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500 max-w-xs truncate">
                                    {{ $professional->description ?: '—' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">
                                    {{ $professional->created_at?->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        <button type="button" class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors edit-btn"
                                                data-bs-toggle="modal" data-bs-target="#editProfessionalModal"
                                                data-uuid="{{ $professional->uuid }}"
                                                data-name="{{ $professional->name }}"
                                                data-description="{{ $professional->description }}"
                                                title="Edit Professional">
                                            <i class="bx bx-edit text-sm"></i>
                                        </button>
                                        <button type="button" class="inline-flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors delete-btn"
                                                data-uuid="{{ $professional->uuid }}"
                                                data-name="{{ $professional->name }}"
                                                title="Delete Professional">
                                            <i class="bx bx-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-400">
                                    <i class="bx bx-briefcase text-3xl block mb-2"></i>
                                    No professionals yet. Click <strong>Add Professional</strong> to create one.
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

{{-- ════════ Create Modal ════════ --}}
<div class="modal fade" id="createProfessionalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-plus text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Add Professional</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="{{ route('professionals.store') }}" method="POST" id="create_form">
                    @csrf
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Name</label>
                            <input type="text" name="name"
                                   class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Enter professional name..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Description <span class="text-xs text-slate-400 normal-case font-normal">(optional)</span></label>
                            <textarea name="description" rows="4"
                                      class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                      placeholder="Brief description..."></textarea>
                        </div>
                        <div id="create_alert"></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="create_btn">
                            <i class="bx bx-save"></i> Create Professional
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ════════ Edit Modal ════════ --}}
<div class="modal fade" id="editProfessionalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-600 to-cyan-800 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-edit text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Update Professional</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="{{ route('professionals.update', ['professional' => '__UUID__']) }}"
                      method="POST"
                      id="edit_form"
                      data-url-template="{{ route('professionals.update', ['professional' => '__UUID__']) }}">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Name</label>
                            <input type="text" id="edit_name" name="name"
                                   class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-colors" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Description <span class="text-xs text-slate-400 normal-case font-normal">(optional)</span></label>
                            <textarea id="edit_description" name="description" rows="4"
                                      class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-colors"></textarea>
                        </div>
                        <div id="edit_alert"></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="edit_btn">
                            <i class="bx bx-save"></i> Update Professional
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
(function () {
    'use strict';

    function init() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        console.log('[Professionals] script initialized');

        // ═══ Record count ═══
        const recordCount = document.getElementById('record-count');
        if (recordCount) {
            const count = @json($professionals->count());
            recordCount.textContent = count + ' Professional' + (count !== 1 ? 's' : '');
        }

        // ═══ DataTable — guarded ═══
        try {
            if (window.jQuery && jQuery.fn && jQuery.fn.DataTable && document.getElementById('professionals_table')) {
                if (!jQuery.fn.DataTable.isDataTable('#professionals_table')) {
                    jQuery('#professionals_table').DataTable();
                    console.log('[Professionals] DataTable initialized');
                }
            }
        } catch (e) {
            console.warn('[Professionals] DataTable init skipped:', e.message);
        }

        // ═══ Shared submit helper ═══
        async function submitForm(url, formData, btn, alertDiv, idleLabel) {
            btn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Processing...';
            btn.disabled = true;
            if (alertDiv) alertDiv.innerHTML = '';

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
                    console.error('[Professionals] Non-JSON response:', response.status, text.slice(0, 500));
                    throw new Error(`Server returned ${response.status}. Check the Network tab.`);
                }

                const data = await response.json();

                if (response.status === 422) {
                    const msgs = Object.values(data.errors || {}).flat().join('<br>');
                    throw new Error(msgs || data.message || 'Validation failed');
                }
                if (response.status === 419) {
                    throw new Error('Session expired. Please reload the page.');
                }
                if (response.status >= 500) {
                    throw new Error(data.debug || data.message || 'Server error.');
                }
                if (!data.success) {
                    throw new Error(data.message || 'An error occurred');
                }

                if (alertDiv) {
                    alertDiv.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bx bx-check-circle me-2"></i>${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
                }
                setTimeout(() => location.reload(), 900);

            } catch (err) {
                console.error('[Professionals] Submit error:', err);
                if (alertDiv) {
                    alertDiv.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bx bx-error-circle me-2"></i>${err.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
                }
            } finally {
                btn.innerHTML = idleLabel;
                btn.disabled = false;
            }
        }

        // ═══ Create form ═══
        const createForm = document.getElementById('create_form');
        if (createForm) {
            createForm.addEventListener('submit', function (e) {
                e.preventDefault();
                submitForm(
                    this.action,
                    new FormData(this),
                    document.getElementById('create_btn'),
                    document.getElementById('create_alert'),
                    '<i class="bx bx-save me-1"></i> Create Professional'
                );
            });
            console.log('[Professionals] Create form bound');
        } else {
            console.warn('[Professionals] #create_form not found');
        }

        // ═══ Edit form ═══
        const editForm = document.getElementById('edit_form');
        if (editForm) {
            editForm.addEventListener('submit', function (e) {
                e.preventDefault();
                submitForm(
                    this.action,
                    new FormData(this),
                    document.getElementById('edit_btn'),
                    document.getElementById('edit_alert'),
                    '<i class="bx bx-save me-1"></i> Update Professional'
                );
            });
            console.log('[Professionals] Edit form bound');
        } else {
            console.warn('[Professionals] #edit_form not found');
        }

        // ═══ Edit buttons — populate + set action ═══
        document.querySelectorAll('.edit-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const uuid = this.dataset.uuid || '';
                if (editForm) {
                    editForm.action = editForm.dataset.urlTemplate.replace('__UUID__', uuid);
                }
                const nameInput = document.getElementById('edit_name');
                const descInput = document.getElementById('edit_description');
                if (nameInput) nameInput.value = this.dataset.name || '';
                if (descInput) descInput.value = this.dataset.description || '';
            });
        });

        // ═══ Delete buttons — bound via JS, NOT inline onclick ═══
        document.querySelectorAll('.delete-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                deleteProfessional(this.dataset.uuid, this.dataset.name);
            });
        });

        // ═══ Delete function ═══
        function deleteProfessional(uuid, name) {
            const url = "{{ url('settings/professionals') }}/" + uuid;

            swal({
                title: "Delete Professional",
                text: `Are you sure you want to delete "${name || 'this professional'}"?`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Yes, Delete",
                cancelButtonText: "Cancel"
            }, function () {
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
                        if (window.$ && $.notify) $.notify(data.message, 'success');
                        setTimeout(() => location.reload(), 500);
                    } else {
                        if (window.$ && $.notify) $.notify(data.message || 'Failed', 'error');
                    }
                })
                .catch(err => {
                    console.error('[Professionals] Delete error:', err);
                    if (window.$ && $.notify) $.notify(err.message || 'Error occurred', 'error');
                });
            });
        }
    }

    // ═══ Init — handle both cases (DOM ready or already parsed) ═══
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush