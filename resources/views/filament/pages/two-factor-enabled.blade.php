<div class="space-y-6">
    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-green-800">
                    Two-Factor Authentication is Enabled
                </h3>
                <div class="mt-2 text-sm text-green-700">
                    <p>Your account is now protected with two-factor authentication.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Current QR Code</h3>
        
        <div class="space-y-4">
            <div class="text-sm text-gray-600">
                <p class="mb-4">This is your current QR code for Google Authenticator:</p>
            </div>
            
            <div class="flex justify-center">
                <div class="bg-white p-4 border rounded-lg">
                    @if($qrCodeUrl)
                        <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(200)->generate($qrCodeUrl)) }}" 
                             alt="QR Code" class="mx-auto">
                    @endif
                </div>
            </div>
            
            @if($secretKey)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Manual Entry Code:</p>
                    <p class="font-mono text-sm bg-white p-2 rounded border">{{ $secretKey }}</p>
                </div>
            @endif
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Important Security Notice
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Keep your QR code and secret key secure</li>
                                <li>Don't share these with anyone</li>
                                <li>If you lose access to your authenticator app, you may be locked out of your account</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 