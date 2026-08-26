@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4" role="alert">
        <div class="flex gap-3">
            <i class="fas fa-circle-exclamation mt-0.5 text-red-500" aria-hidden="true"></i>
            <div>
                <p class="font-semibold text-red-800">Please correct the following:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-700" role="alert">
        {{ session('error') }}
    </div>
@endif

<div class="mb-6 hidden rounded-2xl border p-4 text-sm" data-form-message role="status" aria-live="polite"></div>
<div class="mb-6 hidden rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" data-client-errors role="alert"></div>
