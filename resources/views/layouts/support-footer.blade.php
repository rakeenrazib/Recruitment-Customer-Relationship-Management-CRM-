<footer class="mx-auto max-w-7xl px-4 pb-8 pt-2 sm:px-6 lg:px-8">
    <div class="panel px-6 py-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="eyebrow">Support</p>
                <h3 class="mt-2 font-display text-xl font-black text-slate-950">Need help?</h3>
            </div>

            <div class="grid gap-3 text-sm sm:text-right">
                <a href="mailto:{{ $siteAdmin?->support_email ?? 'support@talenthub.test' }}" class="font-semibold text-slate-700 transition hover:text-teal-700">
                    {{ $siteAdmin?->support_email ?? 'support@talenthub.test' }}
                </a>
                <a href="tel:{{ preg_replace('/\s+/', '', $siteAdmin?->support_phone ?? '+8801000000000') }}" class="font-semibold text-slate-700 transition hover:text-teal-700">
                    {{ $siteAdmin?->support_phone ?? '+880 1000-000000' }}
                </a>
            </div>
        </div>
    </div>
</footer>
