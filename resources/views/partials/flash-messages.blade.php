@if (session('success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 3000)" 
         class="mb-4 p-3 rounded-lg bg-green-100 text-green-800 border border-green-400 shadow-md transition transform duration-500 ease-in-out"
         role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 3000)" 
         class="mb-4 p-3 rounded-lg bg-red-100 text-red-800 border border-red-400 shadow-md transition transform duration-500 ease-in-out"
         role="alert">
        {{ session('error') }}
    </div>
@endif

@if (session('warning'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 3000)" 
         class="mb-4 p-3 rounded-lg bg-yellow-100 text-yellow-800 border border-yellow-400 shadow-md transition transform duration-500 ease-in-out"
         role="alert">
        {{ session('warning') }}
    </div>
@endif
