@extends('layouts.master')

@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Loan Management</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-slate-400"><i class="bx bx-building"></i></a></li>
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
                    <i class="bx bx-money text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Loan Management</h6>
            </div>

            {{-- Navigation Cards Grid --}}
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">

                    <a href="{{ route('loan.applications')}}" class="group block">
                        <div class="flex items-center gap-4 p-5 rounded-xl border border-slate-200 hover:border-blue-300 hover:shadow-md hover:bg-blue-50/40 transition-all duration-200">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition-colors shrink-0">
                                <i class="bx bx-file text-blue-600 text-2xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="font-semibold text-slate-800 mb-0.5 text-base leading-tight">Loan Applications</h5>
                                <p class="text-xs text-slate-500 mb-0">Manage Loan Applications</p>
                            </div>
                            <i class="bx bx-chevron-right text-slate-300 text-2xl group-hover:text-blue-500 group-hover:translate-x-1 transition-all shrink-0"></i>
                        </div>
                    </a>

                    <a href="{{ route('loan.contracts')}}" class="group block">
                        <div class="flex items-center gap-4 p-5 rounded-xl border border-slate-200 hover:border-indigo-300 hover:shadow-md hover:bg-indigo-50/40 transition-all duration-200">
                            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:bg-indigo-200 transition-colors shrink-0">
                                <i class="bx bx-file text-indigo-600 text-2xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="font-semibold text-slate-800 mb-0.5 text-base leading-tight">Loan Contracts</h5>
                                <p class="text-xs text-slate-500 mb-0">Manage Loan Contracts</p>
                            </div>
                            <i class="bx bx-chevron-right text-slate-300 text-2xl group-hover:text-indigo-500 group-hover:translate-x-1 transition-all shrink-0"></i>
                        </div>
                    </a>

                    <a href="{{ route('loan.applications.waiting.mandate')}}" class="group block">
                        <div class="flex items-center gap-4 p-5 rounded-xl border border-slate-200 hover:border-violet-300 hover:shadow-md hover:bg-violet-50/40 transition-all duration-200">
                            <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center group-hover:bg-violet-200 transition-colors shrink-0">
                                <i class="bx bx-time text-violet-600 text-2xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="font-semibold text-slate-800 mb-0.5 text-base leading-tight">Waiting Mandate Confirmation</h5>
                                <p class="text-xs text-slate-500 mb-0">Loan Applications Pending Mandate</p>
                            </div>
                            <i class="bx bx-chevron-right text-slate-300 text-2xl group-hover:text-violet-500 group-hover:translate-x-1 transition-all shrink-0"></i>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
