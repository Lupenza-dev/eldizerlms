@extends('layouts.master')

@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        <div class="page-breadcrumb d-flex flex-wrap gap-3 align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Campus Management</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-slate-400"><i class="bx bx-building"></i></a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto flex items-center gap-3">
                <span class="inline-flex items-center bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full" id="record-count">
                    {{ $campuses->count() }} Campuses
                </span>
                <button type="button" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" id="sync_btn">
                    <i class="bx bx-sync"></i> Sync
                </button>
            </div>
        </div>

        <div id="sync_alert" role="status" aria-live="polite"></div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="bx bx-building text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Campus Directory</h6>
            </div>
            <div class="p-6">
                <div class="table-responsive">
                    <table id="example" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Reg Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Campus Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Campus Code</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($campuses as $campus)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ $campus->created_at?->format('d M Y') ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800">{{ $campus->campus_name }}</td>
                                <td class="px-4 py-3 text-sm text-slate-700 whitespace-nowrap">{{ $campus->campus_code }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('sync_btn').addEventListener('click', async function () {
        const button = this;
        const alertDiv = document.getElementById('sync_alert');
        button.disabled = true;
        button.innerHTML = '<i class="bx bx-loader bx-spin"></i> Syncing...';
        alertDiv.className = '';
        alertDiv.textContent = '';

        try {
            const response = await fetch("{{ route('campuses.sync') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Unable to sync campuses. Please try again.');
            }
            alertDiv.className = 'alert alert-success';
            alertDiv.textContent = data.message;
            setTimeout(() => location.reload(), 1500);
        } catch (error) {
            alertDiv.className = 'alert alert-danger';
            alertDiv.textContent = error.message || 'Unable to sync campuses. Please try again.';
            button.disabled = false;
            button.innerHTML = '<i class="bx bx-sync"></i> Sync';
        }
    });
</script>
@endpush
