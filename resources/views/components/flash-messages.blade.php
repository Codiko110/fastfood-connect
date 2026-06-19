@if(session('success'))
    <div class="mb-6 px-5 py-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm font-semibold flex items-center gap-3">
        <x-icon name="check-circle" class="text-green-600" />
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 px-5 py-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-semibold flex items-center gap-3">
        <x-icon name="exclamation-circle" class="text-red-600" />
        {{ session('error') }}
    </div>
@endif
