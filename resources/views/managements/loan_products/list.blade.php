@extends('layouts.master')
@section('content')
@php
    $inputCls = 'w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors';
    $labelCls = 'block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5';
    $editPayload = $products->mapWithKeys(fn ($p) => [$p->uuid => [
        'name'                      => $p->name,
        'code'                      => $p->code,
        'description'               => $p->description,
        'icon'                      => $p->icon,
        'color'                     => $p->color,
        'calculation_method'        => $p->calculation_method,
        'markup_rate'               => round($p->markup_rate * 100, 4),
        'fixed_total_amount'        => $p->fixed_total_amount,
        'plan_months'               => $p->plan_months,
        'interest_rate'             => round($p->interest_rate * 100, 4),
        'has_fees_and_charges'      => $p->has_fees_and_charges,
        'fees_is_percentage'        => $p->fees_is_percentage,
        'fees_value'                => $p->fees_is_percentage ? round($p->fees_value * 100, 4) : $p->fees_value,
        'has_penalty'               => $p->has_penalty,
        'penalty_is_percentage'     => $p->penalty_is_percentage,
        'penalty_value'             => $p->penalty_is_percentage ? round($p->penalty_value * 100, 4) : $p->penalty_value,
        'mandate_multiplier'        => $p->mandate_multiplier,
        'min_amount'                => $p->min_amount,
        'max_amount'                => $p->max_amount,
        'requires_agent'            => $p->requires_agent,
        'requires_device'           => $p->requires_device,
        'allows_initial_deposit'    => $p->allows_initial_deposit,
        'requires_eco_water_campus' => $p->requires_eco_water_campus,
        'register_with_eco_water'   => $p->register_with_eco_water,
        'is_active'                 => $p->is_active,
        'sort_order'                => $p->sort_order,
    ]]);
@endphp
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">

        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Loan Products</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('settings.index') }}" class="text-slate-400"><i class="bx bx-cog"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('settings.index') }}" class="text-slate-400">Settings</a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">Loan Products</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto flex items-center gap-3">
                <span class="inline-flex items-center bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                    {{ $products->count() }} {{ Str::plural('Product', $products->count()) }}
                </span>
                <button type="button" id="add_product_btn" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    <i class="bx bx-plus"></i> Add Loan Product
                </button>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="bx bx-package text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Loan Product Catalogue</h6>
            </div>
            <div class="p-6">
                <div class="table-responsive">
                    <table id="loan_products_table" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Pricing</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Plan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Fees / Penalty</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Requires</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Loans</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($products as $product)
                            @php $c = $product->color ?: 'slate'; @endphp
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $product->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-9 h-9 rounded-lg bg-{{ $c }}-100 flex items-center justify-center shrink-0">
                                            <i class="bx {{ $product->icon ?: 'bx-package' }} text-{{ $c }}-600 text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-slate-800">{{ $product->name }}</div>
                                            <div class="text-xs text-slate-400">{{ $product->code }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600 whitespace-nowrap">
                                    @if ($product->calculation_method === 'interest')
                                        Amount + {{ rtrim(rtrim(number_format($product->markup_rate * 100, 2), '0'), '.') }}%
                                    @elseif ($product->calculation_method === 'fixed_amount')
                                        Fixed TZS {{ number_format($product->fixed_total_amount) }}
                                    @else
                                        Principal only
                                    @endif
                                    <div class="text-xs text-slate-400">Mandate × {{ rtrim(rtrim(number_format($product->mandate_multiplier, 2), '0'), '.') }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600 whitespace-nowrap">{{ $product->plan_months }} {{ Str::plural('month', $product->plan_months) }}</td>
                                <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">
                                    <div>Fees: <span class="font-semibold">{{ $product->fees_label }}</span></div>
                                    <div>Penalty: <span class="font-semibold">{{ $product->penalty_label }}</span></div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @if ($product->requires_agent)<span class="text-[11px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700">Agent</span>@endif
                                        @if ($product->requires_device)<span class="text-[11px] px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">Device</span>@endif
                                        @if ($product->allows_initial_deposit)<span class="text-[11px] px-2 py-0.5 rounded-full bg-amber-50 text-amber-700">Deposit</span>@endif
                                        @if ($product->requires_eco_water_campus)<span class="text-[11px] px-2 py-0.5 rounded-full bg-cyan-50 text-cyan-700">Campus</span>@endif
                                        @if ($product->register_with_eco_water)<span class="text-[11px] px-2 py-0.5 rounded-full bg-cyan-50 text-cyan-700">EcoWater API</span>@endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ number_format($product->loan_applications_count) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if ($product->is_active)
                                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700">Active</span>
                                    @else
                                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-200 text-slate-600">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        <button type="button" class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors edit-btn"
                                                data-uuid="{{ $product->uuid }}" title="Edit">
                                            <i class="bx bx-edit text-sm"></i>
                                        </button>
                                        <button type="button" class="inline-flex items-center justify-center w-8 h-8 {{ $product->is_active ? 'bg-amber-500 hover:bg-amber-600' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white rounded-lg transition-colors status-btn"
                                                data-uuid="{{ $product->uuid }}" data-name="{{ $product->name }}" data-active="{{ $product->is_active ? 1 : 0 }}"
                                                title="{{ $product->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bx {{ $product->is_active ? 'bx-pause' : 'bx-play' }} text-sm"></i>
                                        </button>
                                        @if ($product->loan_applications_count == 0)
                                        <button type="button" class="inline-flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors delete-btn"
                                                data-uuid="{{ $product->uuid }}" data-name="{{ $product->name }}" title="Delete">
                                            <i class="bx bx-trash text-sm"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-10 text-center text-sm text-slate-400">
                                    <i class="bx bx-package text-3xl block mb-2"></i>
                                    No loan products yet. Click <strong>Add Loan Product</strong> to create one.
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
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-package text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0" id="product_modal_title">Add Loan Product</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form method="POST" id="product_form"
                      data-store-url="{{ route('loan-products.store') }}"
                      data-update-url="{{ route('loan-products.update', ['loanProduct' => '__UUID__']) }}">
                    @csrf
                    <input type="hidden" name="_method" id="form_method" value="POST">

                    {{-- General --}}
                    <h6 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">General</h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                        <div>
                            <label class="{{ $labelCls }}">Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" class="{{ $inputCls }}" required>
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Code <span class="text-red-500">*</span></label>
                            <input type="text" name="code" class="{{ $inputCls }} uppercase" placeholder="e.g. SCHOOL_FEES" required>
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Sort Order</label>
                            <input type="number" name="sort_order" class="{{ $inputCls }}" value="0">
                        </div>
                        <div class="md:col-span-3">
                            <label class="{{ $labelCls }}">Description</label>
                            <input type="text" name="description" class="{{ $inputCls }}" placeholder="Short text shown on the product card">
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Icon <span class="normal-case font-normal text-slate-400">(boxicons class)</span></label>
                            <input type="text" name="icon" class="{{ $inputCls }}" placeholder="bx-money">
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Color</label>
                            <select name="color" class="{{ $inputCls }}">
                                @foreach ($colors as $name => $hex)
                                    <option value="{{ $name }}">{{ ucfirst($name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" checked> Active
                            </label>
                        </div>
                    </div>

                    {{-- Pricing --}}
                    <h6 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 pt-4 border-t border-slate-100">Pricing</h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                        <div>
                            <label class="{{ $labelCls }}">Calculation Method <span class="text-red-500">*</span></label>
                            <select name="calculation_method" id="calculation_method" class="{{ $inputCls }}" required>
                                @foreach ($methods as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div data-method="interest">
                            <label class="{{ $labelCls }}">Markup (%)</label>
                            <input type="number" step="0.01" min="0" name="markup_rate" class="{{ $inputCls }}" placeholder="20">
                        </div>
                        <div data-method="fixed_amount">
                            <label class="{{ $labelCls }}">Fixed Total Amount (TZS)</label>
                            <input type="number" step="1" min="0" name="fixed_total_amount" class="{{ $inputCls }}" placeholder="15000">
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Plan (months) <span class="text-red-500">*</span></label>
                            <input type="number" min="1" name="plan_months" class="{{ $inputCls }}" value="3" required>
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Interest Rate (%) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" min="0" name="interest_rate" class="{{ $inputCls }}" value="3.5" required>
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Mandate Multiplier <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" min="0.01" name="mandate_multiplier" class="{{ $inputCls }}" value="1" required>
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Min Amount (TZS) <span class="text-red-500">*</span></label>
                            <input type="number" min="0" name="min_amount" class="{{ $inputCls }}" value="1000" required>
                        </div>
                        <div>
                            <label class="{{ $labelCls }}">Max Amount (TZS)</label>
                            <input type="number" min="0" name="max_amount" class="{{ $inputCls }}" placeholder="No limit">
                        </div>
                    </div>

                    {{-- Fees & Penalty --}}
                    <h6 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 pt-4 border-t border-slate-100">Fees &amp; Penalty</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        @foreach (['fees' => ['has_fees_and_charges', 'Fees and Charges'], 'penalty' => ['has_penalty', 'Penalty']] as $prefix => [$toggle, $title])
                        <div class="rounded-xl border border-slate-200 p-4">
                            <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 mb-3">
                                <input type="hidden" name="{{ $toggle }}" value="0">
                                <input type="checkbox" name="{{ $toggle }}" value="1" class="form-check-input charge-toggle" data-target="{{ $prefix }}" checked> {{ $title }}
                            </label>
                            <div class="grid grid-cols-2 gap-3" data-charge="{{ $prefix }}">
                                <div>
                                    <label class="{{ $labelCls }}">Type</label>
                                    <select name="{{ $prefix }}_is_percentage" class="{{ $inputCls }} charge-type" data-target="{{ $prefix }}">
                                        <option value="1">Percentage rate</option>
                                        <option value="0">Fixed amount (TZS)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="{{ $labelCls }}">Value <span class="normal-case font-normal text-slate-400" data-unit="{{ $prefix }}">(%)</span></label>
                                    <input type="number" step="0.01" min="0" name="{{ $prefix }}_value" class="{{ $inputCls }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Requirements --}}
                    <h6 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 pt-4 border-t border-slate-100">Application Requirements</h6>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-2">
                        @foreach ([
                            'requires_agent'            => 'Requires agent',
                            'requires_device'           => 'Requires device',
                            'allows_initial_deposit'    => 'Allows initial deposit',
                            'requires_eco_water_campus' => 'Requires Eco Water campus',
                            'register_with_eco_water'   => 'Register with Eco Water after mandate',
                        ] as $flag => $label)
                        <label class="inline-flex items-center gap-2 text-sm text-slate-700 rounded-lg border border-slate-200 px-3 py-2">
                            <input type="hidden" name="{{ $flag }}" value="0">
                            <input type="checkbox" name="{{ $flag }}" value="1" class="form-check-input"> {{ $label }}
                        </label>
                        @endforeach
                    </div>

                    <div id="product_alert" class="mt-4"></div>

                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="product_submit_btn">
                            <i class="bx bx-save"></i> Save Product
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
        const products  = @json($editPayload);
        const form      = document.getElementById('product_form');
        const modalEl   = document.getElementById('productModal');
        const modal     = bootstrap.Modal.getOrCreateInstance(modalEl);
        const alertDiv  = document.getElementById('product_alert');
        const submitBtn = document.getElementById('product_submit_btn');
        const idleLabel = '<i class="bx bx-save"></i> Save Product';
        const defaults  = {
            name: '', code: '', description: '', icon: '', color: 'blue', calculation_method: 'interest',
            markup_rate: '', fixed_total_amount: '', plan_months: 3, interest_rate: 3.5,
            has_fees_and_charges: true, fees_is_percentage: true, fees_value: 9.5,
            has_penalty: true, penalty_is_percentage: true, penalty_value: 5,
            mandate_multiplier: 1, min_amount: 1000, max_amount: '',
            requires_agent: false, requires_device: false, allows_initial_deposit: false,
            requires_eco_water_campus: false, register_with_eco_water: false, is_active: true, sort_order: 0,
        };

        try {
            if (window.jQuery && jQuery.fn && jQuery.fn.DataTable && !jQuery.fn.DataTable.isDataTable('#loan_products_table')) {
                jQuery('#loan_products_table').DataTable();
            }
        } catch (e) { console.warn('[LoanProducts] DataTable init skipped:', e.message); }

        function fill(values) {
            Object.entries(values).forEach(([key, val]) => {
                const checkbox = form.querySelector(`input[type="checkbox"][name="${key}"]`);
                if (checkbox) { checkbox.checked = !!val; return; }
                const field = form.querySelector(`[name="${key}"]:not([type="hidden"])`);
                if (!field) return;
                if (field.tagName === 'SELECT' && typeof val === 'boolean') {
                    field.value = val ? '1' : '0';
                } else {
                    field.value = val ?? '';
                }
            });
            refreshVisibility();
        }

        function refreshVisibility() {
            const method = document.getElementById('calculation_method').value;
            form.querySelectorAll('[data-method]').forEach(el => {
                el.style.display = el.dataset.method === method ? '' : 'none';
            });
            form.querySelectorAll('.charge-toggle').forEach(t => {
                form.querySelector(`[data-charge="${t.dataset.target}"]`).style.opacity = t.checked ? '1' : '0.4';
                form.querySelectorAll(`[data-charge="${t.dataset.target}"] select, [data-charge="${t.dataset.target}"] input`)
                    .forEach(i => i.disabled = !t.checked);
            });
            form.querySelectorAll('.charge-type').forEach(s => {
                form.querySelector(`[data-unit="${s.dataset.target}"]`).textContent = s.value === '1' ? '(%)' : '(TZS)';
            });
        }

        form.addEventListener('change', refreshVisibility);

        document.getElementById('add_product_btn').addEventListener('click', function () {
            form.action = form.dataset.storeUrl;
            document.getElementById('form_method').value = 'POST';
            document.getElementById('product_modal_title').textContent = 'Add Loan Product';
            alertDiv.innerHTML = '';
            fill(defaults);
            modal.show();
        });

        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const uuid = this.dataset.uuid;
                form.action = form.dataset.updateUrl.replace('__UUID__', uuid);
                document.getElementById('form_method').value = 'PUT';
                document.getElementById('product_modal_title').textContent = 'Update Loan Product';
                alertDiv.innerHTML = '';
                fill(Object.assign({}, defaults, products[uuid] || {}));
                modal.show();
            });
        });

        async function send(url, options) {
            const response = await fetch(url, Object.assign({
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            }, options));
            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                throw new Error(`Server returned ${response.status}. Check the Network tab.`);
            }
            const data = await response.json();
            if (response.status === 422 && data.errors) {
                throw new Error(Object.values(data.errors).flat().join('<br>'));
            }
            if (response.status === 419) throw new Error('Session expired. Please reload the page.');
            if (!response.ok || data.success === false) throw new Error(data.debug || data.message || 'An error occurred');
            return data;
        }

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            submitBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Saving...';
            submitBtn.disabled = true;
            alertDiv.innerHTML = '';
            try {
                const data = await send(form.action, { method: 'POST', body: new FormData(form) });
                alertDiv.innerHTML = `<div class="alert alert-success"><i class="bx bx-check-circle me-2"></i>${data.message}</div>`;
                setTimeout(() => location.reload(), 800);
            } catch (err) {
                alertDiv.innerHTML = `<div class="alert alert-danger"><i class="bx bx-error-circle me-2"></i>${err.message}</div>`;
            } finally {
                submitBtn.innerHTML = idleLabel;
                submitBtn.disabled = false;
            }
        });

        const baseUrl = "{{ url('settings/loan-products') }}/";

        function notify(msg, type) {
            if (window.$ && $.notify) { $.notify(msg, type); } else { alert(msg); }
        }

        document.querySelectorAll('.status-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const { uuid, name, active } = this.dataset;
                const verb = active === '1' ? 'Deactivate' : 'Activate';
                swal({
                    title: `${verb} Loan Product`,
                    text: `${verb} "${name}"? ${active === '1' ? 'It will no longer be offered for new applications.' : ''}`,
                    type: 'warning', showCancelButton: true, confirmButtonText: `Yes, ${verb}`, cancelButtonText: 'Cancel'
                }, function () {
                    send(baseUrl + uuid + '/status', { method: 'POST' })
                        .then(d => { notify(d.message, 'success'); setTimeout(() => location.reload(), 500); })
                        .catch(err => notify(err.message, 'error'));
                });
            });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const { uuid, name } = this.dataset;
                swal({
                    title: 'Delete Loan Product',
                    text: `Are you sure you want to delete "${name}"?`,
                    type: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, Delete', cancelButtonText: 'Cancel'
                }, function () {
                    send(baseUrl + uuid, { method: 'DELETE' })
                        .then(d => { notify(d.message, 'success'); setTimeout(() => location.reload(), 500); })
                        .catch(err => notify(err.message, 'error'));
                });
            });
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
