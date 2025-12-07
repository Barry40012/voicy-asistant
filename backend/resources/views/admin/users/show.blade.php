<x-admin-layout pageTitle="Détails Utilisateur">
    <div class="space-y-6">
        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Informations utilisateur -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Nom</p>
                    <p class="text-base font-medium text-gray-900">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="text-base font-medium text-gray-900">{{ $user->email }}</p>
                    @if($user->email_verified_at)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                            ✓ Vérifié
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                            ⚠ Non vérifié
                        </span>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-500">Téléphone</p>
                    <p class="text-base font-medium text-gray-900">{{ $user->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Rôle</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                        {{ ucfirst($user->role ?? 'user') }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Inscription</p>
                    <p class="text-base font-medium text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Modifier les identifiants de connexion -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Modifier les identifiants de connexion</h2>
            
            <div class="space-y-6">
                <!-- Modifier l'email -->
                <div class="border-t pt-6">
                    <h3 class="text-md font-medium text-gray-700 mb-3">Modifier l'email</h3>
                    <form action="{{ route('admin.users.update-email', $user) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Nouvel email</label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <p class="mt-1 text-xs text-gray-500">L'utilisateur devra vérifier son nouveau email</p>
                        </div>
                        <button type="submit" 
                                class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            Modifier l'email
                        </button>
                    </form>
                </div>

                <!-- Modifier le mot de passe -->
                <div class="border-t pt-6">
                    <h3 class="text-md font-medium text-gray-700 mb-3">Modifier le mot de passe</h3>
                    <form action="{{ route('admin.users.update-password', $user) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   required
                                   minlength="8"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <p class="mt-1 text-xs text-gray-500">Minimum 8 caractères</p>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   required
                                   minlength="8"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <button type="submit" 
                                class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            Modifier le mot de passe
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Abonnements -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Abonnements ({{ $user->subscriptions->count() }})</h2>
            <div class="space-y-3">
                @forelse($user->subscriptions as $subscription)
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $subscription->plan->name }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $subscription->started_at->format('d/m/Y') }} - {{ $subscription->expires_at->format('d/m/Y') }}
                                </p>
                            </div>
                            <span class="px-2 py-1 rounded text-xs font-medium 
                                {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Aucun abonnement</p>
                @endforelse
            </div>
        </div>

        <!-- Paiements -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Paiements ({{ $user->payments->count() }})</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Montant</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Statut</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($user->payments as $payment)
                            <tr>
                                <td class="px-4 py-2 text-sm">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-0.5 rounded text-xs font-medium 
                                        {{ $payment->status === 'succeeded' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-500">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-sm text-gray-500 text-center">Aucun paiement</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Audios -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Audios ({{ $user->audios->count() }})</h2>
            <div class="text-sm text-gray-500">
                Traités: {{ $user->audios->where('status', 'done')->count() }} | 
                En cours: {{ $user->audios->where('status', 'processing')->count() }} | 
                Erreurs: {{ $user->audios->where('status', 'error')->count() }}
            </div>
        </div>
    </div>
</x-admin-layout>

