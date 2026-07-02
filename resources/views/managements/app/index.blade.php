@extends('layouts.master')

@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">App Management</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-slate-400"><i class="bx bx-building"></i></a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="bx bx-grid-alt text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">App Management</h6>
            </div>

            {{-- Navigation Cards --}}
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                    <a href="{{ route('assignments.index') }}" class="group block">
                        <div class="flex items-center gap-4 p-5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="w-12 h-12 bg-blue-100 group-hover:bg-blue-600 rounded-xl flex items-center justify-center shrink-0 transition-colors duration-200">
                                <i class="bx bx-task text-blue-600 group-hover:text-white text-2xl transition-colors duration-200"></i>
                            </div>
                            <div class="min-w-0">
                                <h5 class="text-sm font-semibold text-slate-800 mb-0.5">Assignments Management</h5>
                                <p class="text-xs text-slate-500 mb-0">Management of Assignments per University</p>
                            </div>
                            <i class="bx bx-chevron-right text-slate-300 group-hover:text-blue-500 text-xl ms-auto transition-colors duration-200"></i>
                        </div>
                    </a>

                    <a href="{{ route('groups.index') }}" class="group block">
                        <div class="flex items-center gap-4 p-5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-emerald-50 hover:border-emerald-200 transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="w-12 h-12 bg-emerald-100 group-hover:bg-emerald-600 rounded-xl flex items-center justify-center shrink-0 transition-colors duration-200">
                                <i class="bx bx-group text-emerald-600 group-hover:text-white text-2xl transition-colors duration-200"></i>
                            </div>
                            <div class="min-w-0">
                                <h5 class="text-sm font-semibold text-slate-800 mb-0.5">Group Management</h5>
                                <p class="text-xs text-slate-500 mb-0">Management of University Groups</p>
                            </div>
                            <i class="bx bx-chevron-right text-slate-300 group-hover:text-emerald-500 text-xl ms-auto transition-colors duration-200"></i>
                        </div>
                    </a>

                    <a href="{{ route('adverts.index') }}" class="group block">
                        <div class="flex items-center gap-4 p-5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-purple-50 hover:border-purple-200 transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="w-12 h-12 bg-purple-100 group-hover:bg-purple-600 rounded-xl flex items-center justify-center shrink-0 transition-colors duration-200">
                                <i class="bx bx-cloud-download text-purple-600 group-hover:text-white text-2xl transition-colors duration-200"></i>
                            </div>
                            <div class="min-w-0">
                                <h5 class="text-sm font-semibold text-slate-800 mb-0.5">Adverts Management</h5>
                                <p class="text-xs text-slate-500 mb-0">Advert Management — create, update, delete</p>
                            </div>
                            <i class="bx bx-chevron-right text-slate-300 group-hover:text-purple-500 text-xl ms-auto transition-colors duration-200"></i>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

