<x-admin-layout pageTitle="Message de Contact">
    <div class="mb-6">
        <a href="{{ route('admin.contact-messages') }}" class="inline-flex items-center text-primary-600 hover:text-primary-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $message->subject }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Reçu le {{ $message->created_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
                <div>
                    @if($message->status === 'new')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">Nouveau</span>
                    @elseif($message->status === 'read')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Lu</span>
                    @elseif($message->status === 'replied')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">Répondu</span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Archivé</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="px-6 py-6">
            <!-- Informations expéditeur -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nom</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $message->name }}</p>
                    @if($message->user)
                        <p class="text-xs text-primary-600 mt-1">Utilisateur connecté (ID: {{ $message->user_id }})</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                    <p class="text-lg text-gray-900">
                        <a href="mailto:{{ $message->email }}" class="text-primary-600 hover:text-primary-800">
                            {{ $message->email }}
                        </a>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Date d'envoi</label>
                    <p class="text-gray-900">{{ $message->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">IP Address</label>
                    <p class="text-gray-900 font-mono text-sm">{{ $message->ip_address ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Message -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-500 mb-2">Message</label>
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <p class="text-gray-900 whitespace-pre-wrap leading-relaxed">{{ $message->message }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="flex space-x-3">
                    @if($message->isNew())
                        <form method="POST" action="{{ route('admin.contact-messages.read', $message) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition shadow-md hover:shadow-lg">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Marquer comme lu
                                </span>
                            </button>
                        </form>
                    @endif

                    @if($message->status !== 'replied')
                        <form method="POST" action="{{ route('admin.contact-messages.archive', $message) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition shadow-md hover:shadow-lg">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                    Archiver
                                </span>
                            </button>
                        </form>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.contact-messages.delete', $message) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ? Cette action est irréversible.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition shadow-md hover:shadow-lg">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Supprimer
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Actions rapides</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" class="flex items-center justify-center px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition shadow-md hover:shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Répondre par email
            </a>
            <button onclick="copyToClipboard('{{ $message->email }}')" class="flex items-center justify-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition shadow-md hover:shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                Copier l'email
            </button>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Email copié dans le presse-papiers !');
            }, function(err) {
                console.error('Erreur lors de la copie:', err);
            });
        }
    </script>
</x-admin-layout>

