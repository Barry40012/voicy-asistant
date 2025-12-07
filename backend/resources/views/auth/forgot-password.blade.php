<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800 font-medium">
                ✅ {{ session('status') }}
            </p>
            <div class="text-xs text-green-700 mt-2 space-y-1">
                <p>💡 <strong>Conseils :</strong></p>
                <ul class="list-disc list-inside ml-2 space-y-1">
                    <li>Vérifie ton dossier <strong>Spam</strong> ou <strong>Courrier indésirable</strong></li>
                    <li>Vérifie sur <strong>tous tes appareils</strong> (téléphone, tablette, ordinateur)</li>
                    <li>L'email peut prendre 1-2 minutes à arriver</li>
                    <li>Si tu utilises Gmail, vérifie aussi les <strong>onglets</strong> (Principal, Promotions, etc.)</li>
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
