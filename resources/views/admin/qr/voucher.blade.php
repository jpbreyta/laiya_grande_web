<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Laiya Grande - Booking Confirmation Voucher</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            @page {
                size: letter;
                margin: 0.5in;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>

    <!-- Google fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Playfair+Display:wght@600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --navy: #0f4a78;
            --light-blue: #e8f0f8;
            --accent: #1f6fb8;
        }

        body {
            font-family: "Montserrat", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background-color: white;
        }

        /* soft repeating watermark text */
        .watermark {
            position: absolute;
            inset: 0;
            pointer-events: none;
            opacity: 0.06;
            font-family: "Playfair Display", serif;
            font-size: 48px;
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 12px;
            padding: 32px;
            transform: rotate(-10deg);
            align-items: center;
        }

        .watermark span {
            transform: translateY(-10px);
            color: #0f1724;
            white-space: nowrap;
        }

        /* printable box shadow look */
        .voucher {
            max-width: 980px;
            margin: 28px auto;
            border: 1px solid rgba(15, 74, 120, 0.12);
            border-radius: 8px;
            overflow: hidden;
            background: white;
            position: relative;
        }

        /* blue header style similar to image */
        .thin-blue {
            background: linear-gradient(180deg, rgba(15, 74, 120, 0.08), rgba(15, 74, 120, 0.02));
            border-bottom: 1px solid rgba(15, 74, 120, 0.08);
        }

        /* small checkbox square used in terms */
        .term-check {
            width: 16px;
            height: 16px;
            border: 1px solid rgba(15, 74, 120, 0.35);
            display: inline-block;
            margin-right: 8px;
            vertical-align: middle;
        }

        /* responsive tweaks */
        @media (max-width: 860px) {
            .watermark {
                display: none;
            }
        }

        /* mimic thin watermark background repeated lines */
        .bg-lines {
            background-image: linear-gradient(180deg, rgba(15, 74, 120, 0.015) 1px, transparent 1px);
            background-size: 100% 36px;
        }
    </style>
</head>

<body class="bg-gray-50 py-8">

    <div class="voucher shadow-md mx-4 md:mx-auto">

        <!-- Top header -->
        <div class="px-6 py-6 thin-blue">
            <div class="max-w-6xl mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <!-- left small logo area placeholder -->
                    <div class="flex-shrink-0">
                        <div class="w-20 h-16 rounded flex items-center justify-center bg-white/0">
                            <!-- optional logo area -->
                            <svg width="72" height="48" viewBox="0 0 72 48" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect width="72" height="48" rx="6" fill="rgba(15,74,120,0.06)" />
                            </svg>
                        </div>
                    </div>

                    <div>
                        <div class="text-sm text-slate-500">LAIYA GRANDE BEACH RESORT</div>
                        <div class="text-2xl md:text-3xl font-extrabold tracking-widest text-[var(--navy)]">LAIYA GRANDE
                        </div>
                        <div class="text-xs md:text-sm text-slate-600 mt-1">BEACH RESORT</div>
                    </div>
                </div>

                <div class="text-right">
                    <div class="text-sm text-slate-600">CONTACT NUMBERS:</div>
                    <div class="text-base md:text-lg font-semibold text-[var(--accent)]">0963-033-7629 | 0977-426-4729
                    </div>
                </div>
            </div>
        </div>

        <!-- Big title area -->
        <div class="px-6 py-4 border-b bg-white">
            <div class="max-w-6xl mx-auto text-center">
                <div class="text-xl md:text-2xl font-bold text-[var(--navy)]">BOOKING CONFIRMATION VOUCHER</div>
                <div class="text-sm text-slate-500 mt-1">BOOKING ID NUMBER: <span
                        class="font-semibold text-[var(--accent)]">{{ $booking_id_display }}</span></div>
            </div>
        </div>

        <!-- Main content: left table and terms column -->
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 px-6 py-6">
            <!-- Left: table card (spans 2 cols on md) -->
            <div class="md:col-span-2 bg-white border border-slate-100 rounded-md overflow-hidden">
                <div class="p-4 bg-white">
                    <table class="w-full table-fixed text-sm">
                        <tbody>
                            <tr class="border-y border-slate-100">
                                <td class="w-1/3 px-4 py-3 font-semibold text-slate-700 uppercase">GUEST NAME/COMPANY
                                    NAME:</td>
                                <td class="px-4 py-3 text-[var(--navy)] font-semibold" contenteditable="true">
                                    {{ $booking->customer->firstname ?? 'N/A' }}
                                    {{ $booking->customer->lastname ?? '' }}</td>
                            </tr>

                            <tr class="border-b border-slate-100">
                                <td class="px-4 py-3 font-semibold text-slate-700 uppercase">CONTACT PERSON/NUMBER:</td>
                                <td class="px-4 py-3 text-[var(--accent)] font-semibold" contenteditable="true">
                                    {{ $booking->customer->phone_number ?? 'N/A' }}
                                </td>
                            </tr>

                            <tr class="border-b border-slate-100">
                                <td class="px-4 py-3 font-semibold text-slate-700 uppercase">RESERVED DATE:</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}
                                    - {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</td>
                            </tr>

                            <tr class="border-b border-slate-100">
                                <td class="px-4 py-3 font-semibold text-slate-700 uppercase">NUMBER OF GUEST(S):</td>
                                <td class="px-4 py-3" contenteditable="true">{{ $booking->number_of_guests }}</td>
                            </tr>

                            <tr class="border-b border-slate-100">
                                <td class="px-4 py-3 font-semibold text-slate-700 uppercase">ACCOMMODATION NAME:</td>
                                <td class="px-4 py-3">{{ $booking->room->name ?? 'N/A' }}</td>
                            </tr>

                            <tr class="border-b border-slate-100">
                                <td class="px-4 py-3 font-semibold text-slate-700 uppercase">TOTAL AMOUNT:</td>
                                <td class="px-4 py-3 text-[var(--navy)] font-bold">PHP
                                    {{ number_format($booking->total_price, 2) }}</td>
                            </tr>

                            <tr class="border-b border-slate-100">
                                <td class="px-4 py-3 font-semibold text-slate-700 uppercase">DEPOSITED AMOUNT:</td>
                                <td class="px-4 py-3 text-[var(--navy)] font-semibold" contenteditable="true">PHP
                                    {{ number_format($deposited_amount, 2) }}</td>
                            </tr>

                            <tr class="border-b border-slate-100">
                                <td class="px-4 py-3 font-semibold text-slate-700 uppercase">REMAINING BALANCE:</td>
                                <td class="px-4 py-3 font-semibold" contenteditable="true">PHP
                                    {{ number_format($remaining_balance, 2) }}</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-3 font-semibold text-slate-700 uppercase">PREPARED BY:</td>
                                <td class="px-4 py-3 text-[var(--navy)] font-semibold" contenteditable="true">
                                    {{ $prepared_by }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right: Terms & Conditions -->
            <div class="bg-white border border-slate-100 rounded-md p-4">
                <div class="text-sm font-semibold text-[var(--navy)] uppercase mb-3">Terms and Condition</div>

                <div class="space-y-3 text-xs text-slate-700">
                    <div><span class="term-check"></span> <strong>OVERNIGHT</strong> (Check-In 2:00 PM Check-Out
                        12:00nn)</div>
                    <div><span class="term-check"></span> <strong>DAYTOUR</strong> (Check-In 8:00 AM Check-Out 5:00 PM)
                    </div>
                    <div><span class="term-check"></span> Bringing of ELECTRICAL APPLIANCES such as rice cooker,
                        induction, etc., will be charged PHP 500.00 EACH.</div>
                    <div><span class="term-check"></span> LOST KEY will be CHARGED PHP 500.00</div>
                    <div><span class="term-check"></span> SWIMMING TIME (6:00 AM – 6:00 PM)</div>
                    <div><span class="term-check"></span> SWIMMING while UNDER THE INFLUENCE OF ALCOHOL is STRICTLY
                        PROHIBITED.</div>
                    <div><span class="term-check"></span> BEACHFRONT GATE CLOSE AT 10:00 PM</div>
                    <div><span class="term-check"></span> LOUD NOISE/SOUNDS ARE STRICTLY PROHIBITED FROM 10PM-8AM</div>
                </div>

                <div class="mt-4 border-t pt-3">
                    <div class="text-sm font-semibold text-[var(--navy)] uppercase mb-2">Cancellation Policy</div>
                    <ul class="list-none text-xs text-slate-700 space-y-2">
                        <li><span class="term-check"></span> Confirmed BOOKING is NON-REFUNDABLE.</li>
                        <li><span class="term-check"></span> Confirmed BOOKING can only be RE-SCHEDULED or MODIFIED if
                            due to TYPHOON.</li>
                        <li><span class="term-check"></span> FAILURE to arrive at the resort shall be a result of NO
                            SHOW and shall be treated as 100% charged.</li>
                    </ul>
                </div>

                <!-- QR Code Section -->
                <div class="mt-4 border-t pt-3">
                    <div class="text-sm font-semibold text-[var(--navy)] uppercase mb-2 text-center">QR Code</div>
                    <div class="flex justify-center">
                        <div class="bg-white p-2 rounded border">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate(
                                $qrString ?? ($booking->reservation_number ?? $booking->id),
                            ) !!}
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 text-center mt-2">Scan for verification</p>

                    <!-- DEBUG: Show QR content -->
                    <div class="no-print mt-2 p-2 bg-gray-100 text-xs">
                        <strong>QR Code Contains:</strong><br>
                        <pre class="whitespace-pre-wrap">{{ $qrString ?? 'NOT SET' }}</pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer banner -->
        <div class="px-6 py-4 bg-slate-100/50 border-t">
            <div class="max-w-6xl mx-auto text-center">
                <div class="text-xs md:text-sm text-slate-700">PLEASE DON'T FORGET TO DROP BY AT OUR TOURISM RECEPTION
                    AREA BEFORE GOING TO THE RESORT AND PAY ECO TICKET PHP50.00 EACH</div>
                <div class="text-lg md:text-xl font-bold text-[var(--navy)] mt-2">PRESENT THIS VOUCHER AS A PROOF OF
                    YOUR CONFIRMED RESERVATION</div>
            </div>
        </div>
    </div>

    <!-- Print Button -->
    <div class="no-print fixed bottom-8 right-8 flex gap-3">
        <button onclick="window.print()"
            class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl shadow-lg font-semibold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print Voucher
        </button>
        <button onclick="window.close()"
            class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-xl shadow-lg font-semibold">
            Close
        </button>
    </div>

    <script>
        // Make certain fields editable on double-click
        document.addEventListener('DOMContentLoaded', function() {
            const editableFields = document.querySelectorAll('[contenteditable="true"]');

            editableFields.forEach(field => {
                field.addEventListener('focus', function() {
                    this.style.outline = '2px solid #0f4a78';
                    this.style.backgroundColor = '#fffbeb';
                });

                field.addEventListener('blur', function() {
                    this.style.outline = 'none';
                    this.style.backgroundColor = 'transparent';
                });
            });
        });
    </script>
</body>

</html>
