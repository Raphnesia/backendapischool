<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Setup Two-Factor Authentication</h3>
        
        <div class="space-y-4">
            <div class="text-sm text-gray-600">
                <p class="mb-4">To enable two-factor authentication, follow these steps:</p>
                
                <ol class="list-decimal list-inside space-y-2">
                    <li>Install Google Authenticator app on your mobile device</li>
                    <li>Scan the QR code below with the app</li>
                    <li>Enter the 6-digit code from the app to verify</li>
                </ol>
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
            
            <form wire:submit="verifyAndEnable" class="space-y-4">
                <div>
                    <label for="verificationCode" class="block text-sm font-medium text-gray-700">
                        Verification Code
                    </label>
                    <input type="text" 
                           id="verificationCode" 
                           wire:model="verificationCode"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Enter 6-digit code"
                           maxlength="6"
                           autocomplete="off">
                    @error('verificationCode')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Verify & Enable
                    </button>
                    
                    <button type="button" 
                            wire:click="enableTwoFactor"
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Regenerate QR Code
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 