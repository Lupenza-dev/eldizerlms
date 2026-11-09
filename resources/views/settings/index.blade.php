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
                    <x-menu-hub key="settings" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection