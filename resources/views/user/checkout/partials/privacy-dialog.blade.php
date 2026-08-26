<dialog id="privacyDialog" class="w-[min(92vw,48rem)] rounded-3xl p-0 shadow-2xl backdrop:bg-slate-950/60">
    <div class="overflow-hidden rounded-3xl bg-white">
        <header class="flex items-center justify-between bg-teal-800 px-6 py-5 text-white">
            <h2 class="text-xl font-bold"><i class="fas fa-shield-halved mr-2" aria-hidden="true"></i>Privacy and Booking Terms</h2>
            <button type="button" data-close-dialog class="flex h-9 w-9 items-center justify-center rounded-full hover:bg-white/10" aria-label="Close">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </header>
        <div class="max-h-[65vh] space-y-6 overflow-y-auto p-6 text-sm leading-relaxed text-slate-700">
            <section>
                <h3 class="mb-2 font-bold text-teal-900">Information collected</h3>
                <p>We collect the contact, stay, and payment-verification information needed to process the booking and communicate important updates.</p>
            </section>
            <section>
                <h3 class="mb-2 font-bold text-teal-900">How the information is used</h3>
                <p>The information is used for reservation processing, payment verification, customer support, required operational records, and booking notifications.</p>
            </section>
            <section>
                <h3 class="mb-2 font-bold text-teal-900">Booking conditions</h3>
                <ul class="list-disc space-y-2 pl-5">
                    <li>Availability and prices are revalidated before records are committed.</li>
                    <li>Uploaded payment proof is subject to staff verification.</li>
                    <li>Pending reservations may expire after the stated payment period.</li>
                    <li>Cancellation and refund rules follow the resort's current published policies.</li>
                </ul>
            </section>
        </div>
        <footer class="flex justify-end border-t border-slate-100 px-6 py-4">
            <button type="button" data-close-dialog class="rounded-xl bg-teal-700 px-5 py-2.5 font-semibold text-white hover:bg-teal-800">Close</button>
        </footer>
    </div>
</dialog>
