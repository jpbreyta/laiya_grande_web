<div id="privacyModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden shadow-2xl">
        <div class="bg-teal-700 px-6 py-4">
            <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                <i class="fas fa-shield-alt"></i> Data Privacy & Terms
            </h2>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-200px)] custom-scrollbar">
            <div class="mb-8">
                <h3 class="text-xl font-bold text-teal-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-user-shield text-teal-600"></i> Data Privacy Notice
                </h3>
                <div class="space-y-4 text-gray-700">
                    <p>At <strong>Laiya Grande Beach Resort</strong>, we protect your personal information and privacy.
                    </p>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <h4 class="font-bold text-blue-900 mb-2">Information We Collect:</h4>
                        <ul class="list-disc pl-5 space-y-1 text-sm">
                            <li>Personal details (name, email, phone)</li>
                            <li>Reservation information (dates, guests)</li>
                            <li>Payment information (verification only)</li>
                        </ul>
                    </div>
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                        <h4 class="font-bold text-green-900 mb-2">How We Use Your Information:</h4>
                        <ul class="list-disc pl-5 space-y-1 text-sm">
                            <li>Process reservations</li>
                            <li>Send confirmations via email/SMS</li>
                            <li>Provide customer support</li>
                            <li>Improve our services</li>
                        </ul>
                    </div>
                    <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r-lg">
                        <h4 class="font-bold text-purple-900 mb-2">Data Security:</h4>
                        <ul class="list-disc pl-5 space-y-1 text-sm">
                            <li>SSL/TLS encryption</li>
                            <li>Secure servers</li>
                            <li>Limited access</li>
                            <li>Never shared without consent</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mb-6">
                <h3 class="text-xl font-bold text-teal-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-file-contract text-teal-600"></i> Terms & Conditions
                </h3>
                <div class="space-y-4 text-gray-700">
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                        <h4 class="font-bold text-yellow-900 mb-2">Reservation Policy:</h4>
                        <ul class="list-disc pl-5 space-y-1 text-sm">
                            <li>24-hour payment deadline</li>
                            <li>Payment proof required</li>
                            <li>Check-in: 2:00 PM | Check-out: 12:00 NN</li>
                        </ul>
                    </div>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                        <h4 class="font-bold text-red-900 mb-2">Cancellation Policy:</h4>
                        <ul class="list-disc pl-5 space-y-1 text-sm">
                            <li>Confirmed reservations are <strong>NON-REFUNDABLE</strong></li>
                            <li>Modifications allowed only for typhoon/force majeure</li>
                            <li>No-shows charged 100%</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 border-t flex flex-col sm:flex-row gap-3 justify-end">
            <button type="button" id="declineBtn"
                class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold rounded-lg">
                <i class="fas fa-times"></i> Decline
            </button>
            <button type="button" id="acceptBtn"
                class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg">
                <i class="fas fa-check"></i> I Accept
            </button>
        </div>
    </div>
</div>
