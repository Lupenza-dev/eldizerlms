@extends('layouts.master')

@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">System Settings</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-slate-400"><i class="bx bx-cog"></i></a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">Overview</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="bx bx-cog text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">System Management</h6>
            </div>

            {{-- Navigation Cards Grid --}}
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">

                    {{-- Devices --}}
                    @can('manage devices')
                    <a href="{{ route('devices.index') }}" class="group block">
                        <div class="flex items-center gap-4 p-5 rounded-xl border border-slate-200 hover:border-blue-300 hover:shadow-md hover:bg-blue-50/40 transition-all duration-200">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition-colors shrink-0">
                                <i class="bx bx-mobile text-blue-600 text-2xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="font-semibold text-slate-800 mb-0.5 text-base leading-tight">Devices</h5>
                                <p class="text-xs text-slate-500 mb-0">Manage device inventory</p>
                            </div>
                            <i class="bx bx-chevron-right text-slate-300 text-2xl group-hover:text-blue-500 group-hover:translate-x-1 transition-all shrink-0"></i>
                        </div>
                    </a>
                    @endcan

                    {{-- Universities --}}
                    @can('manage universities')
                    <a href="{{ route('colleges.index') }}" class="group block">
                        <div class="flex items-center gap-4 p-5 rounded-xl border border-slate-200 hover:border-emerald-300 hover:shadow-md hover:bg-emerald-50/40 transition-all duration-200">
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:bg-emerald-200 transition-colors shrink-0">
                                <i class="bx bx-buildings text-emerald-600 text-2xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="font-semibold text-slate-800 mb-0.5 text-base leading-tight">Universities</h5>
                                <p class="text-xs text-slate-500 mb-0">Manage universities</p>
                            </div>
                            <i class="bx bx-chevron-right text-slate-300 text-2xl group-hover:text-emerald-500 group-hover:translate-x-1 transition-all shrink-0"></i>
                        </div>
                    </a>
                    @endcan

                    {{-- Campuses --}}
                    @can('manage universities')
                    <a href="{{ route('campuses.index') }}" class="group block">
                        <div class="flex items-center gap-4 p-5 rounded-xl border border-slate-200 hover:border-teal-300 hover:shadow-md hover:bg-teal-50/40 transition-all duration-200">
                            <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center group-hover:bg-teal-200 transition-colors shrink-0">
                                <i class="bx bx-building-house text-teal-600 text-2xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="font-semibold text-slate-800 mb-0.5 text-base leading-tight">Campuses</h5>
                                <p class="text-xs text-slate-500 mb-0">Manage campuses</p>
                            </div>
                            <i class="bx bx-chevron-right text-slate-300 text-2xl group-hover:text-teal-500 group-hover:translate-x-1 transition-all shrink-0"></i>
                        </div>
                    </a>
                    @endcan

                    {{-- HESLB Beneficiaries --}}
                    @can('manage beneficiaries')
                    <a href="{{ route('beneficaries.index') }}" class="group block">
                        <div class="flex items-center gap-4 p-5 rounded-xl border border-slate-200 hover:border-amber-300 hover:shadow-md hover:bg-amber-50/40 transition-all duration-200">
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center group-hover:bg-amber-200 transition-colors shrink-0">
                                <i class="bx bx-award text-amber-600 text-2xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="font-semibold text-slate-800 mb-0.5 text-base leading-tight">HESLB Beneficiaries</h5>
                                <p class="text-xs text-slate-500 mb-0">Manage HESLB beneficiaries</p>
                            </div>
                            <i class="bx bx-chevron-right text-slate-300 text-2xl group-hover:text-amber-500 group-hover:translate-x-1 transition-all shrink-0"></i>
                        </div>
                    </a>
                    @endcan

                    {{-- Hospitals --}}
                    @can('manage hospitals')
                    <a href="{{ route('hospitals.index') }}" class="group block">
                        <div class="flex items-center gap-4 p-5 rounded-xl border border-slate-200 hover:border-rose-300 hover:shadow-md hover:bg-rose-50/40 transition-all duration-200">
                            <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center group-hover:bg-rose-200 transition-colors shrink-0">
                                <i class="bx bx-plus-medical text-rose-600 text-2xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="font-semibold text-slate-800 mb-0.5 text-base leading-tight">Hospitals</h5>
                                <p class="text-xs text-slate-500 mb-0">Manage hospitals</p>
                            </div>
                            <i class="bx bx-chevron-right text-slate-300 text-2xl group-hover:text-rose-500 group-hover:translate-x-1 transition-all shrink-0"></i>
                        </div>
                    </a>
                    @endcan

                    {{-- Professionals --}}
                    @if (Route::has('professionals.index'))
                    <a href="{{ route('professionals.index') }}" class="group block">
                        <div class="flex items-center gap-4 p-5 rounded-xl border border-slate-200 hover:border-violet-300 hover:shadow-md hover:bg-violet-50/40 transition-all duration-200">
                            <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center group-hover:bg-violet-200 transition-colors shrink-0">
                                <i class="bx bx-briefcase text-violet-600 text-2xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="font-semibold text-slate-800 mb-0.5 text-base leading-tight">Professionals</h5>
                                <p class="text-xs text-slate-500 mb-0">Manage professional profiles</p>
                            </div>
                            <i class="bx bx-chevron-right text-slate-300 text-2xl group-hover:text-violet-500 group-hover:translate-x-1 transition-all shrink-0"></i>
                        </div>
                    </a>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection