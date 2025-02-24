@if (session('success'))
    <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-800">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-800">
        {{ session('error') }}
    </div>
@endif

@if (session('warning'))
    <div class="mb-4 p-3 rounded-lg bg-yellow-100 text-yellow-800">
        {{ session('warning') }}
    </div>
@endif
