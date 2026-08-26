@php($activeStep = $activeStep ?? 1)
<div class="mx-auto mb-10 max-w-xl" aria-label="Checkout progress">
    <div class="relative flex items-start justify-between">
        <div class="absolute left-10 right-10 top-5 h-1 rounded bg-slate-200" aria-hidden="true">
            <div class="h-full rounded bg-teal-700 transition-all" style="width: {{ $activeStep >= 2 ? '100%' : '0%' }}"></div>
        </div>

        <div class="relative z-10 flex w-24 flex-col items-center gap-2 text-center">
            <span class="flex h-10 w-10 items-center justify-center rounded-full border-4 border-slate-50 bg-teal-700 font-bold text-white shadow">
                @if ($activeStep >= 2)<i class="fas fa-check text-sm" aria-hidden="true"></i>@else 1 @endif
            </span>
            <span class="text-xs font-bold uppercase tracking-wide text-teal-800">Details</span>
        </div>

        <div class="relative z-10 flex w-24 flex-col items-center gap-2 text-center">
            <span class="flex h-10 w-10 items-center justify-center rounded-full border-4 border-slate-50 font-bold shadow {{ $activeStep >= 2 ? 'bg-teal-700 text-white' : 'bg-white text-slate-400' }}">2</span>
            <span class="text-xs font-bold uppercase tracking-wide {{ $activeStep >= 2 ? 'text-teal-800' : 'text-slate-400' }}">Review</span>
        </div>
    </div>
</div>
