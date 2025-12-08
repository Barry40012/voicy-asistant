<x-admin-layout pageTitle="Créer une Newsletter">
    <div class="mb-6">
        <div class="bg-gradient-to-r from-primary-50 to-secondary-50 border-l-4 border-primary-500 p-4 rounded-r-lg mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2 flex items-center">
                <i class="fas fa-paper-plane text-primary-600 mr-3"></i>
                Envoyer une Newsletter
            </h2>
            <p class="text-gray-700">
                Créez et envoyez une newsletter à tous vos abonnés actifs. Les emails seront envoyés automatiquement via la file d'attente.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulaire de création -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-primary-500 to-primary-600">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-edit mr-2"></i>
                        Nouvelle Newsletter
                    </h3>
                </div>
                
                <form method="POST" action="{{ route('admin.newsletter.send') }}" class="p-6">
                    @csrf
                    
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <label for="subject" class="block text-sm font-bold text-gray-700">
                                <i class="fas fa-heading mr-1 text-primary-600"></i>
                                Sujet de l'email *
                            </label>
                            <button 
                                type="button" 
                                id="analyze-btn"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white text-sm font-bold rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all shadow-md hover:shadow-lg transform hover:scale-105"
                                onclick="analyzeWithAI()"
                            >
                                <i class="fas fa-magic mr-2"></i>
                                <span class="hidden sm:inline">Analyser avec l'IA</span>
                                <span class="sm:hidden">IA</span>
                            </button>
                        </div>
                        <input 
                            type="text" 
                            id="subject" 
                            name="subject" 
                            value="{{ old('subject') }}"
                            required
                            placeholder="Ex: Nouvelle fonctionnalité disponible !"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all"
                        >
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="content" class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-1 text-primary-600"></i>
                            Contenu de la newsletter *
                        </label>
                        <textarea 
                            id="content" 
                            name="content" 
                            rows="15"
                            required
                            placeholder="Rédigez votre message ici. Vous pouvez utiliser du HTML pour formater le texte."
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all font-mono text-sm"
                        >{{ old('content') }}</textarea>
                        <div class="mt-2 flex items-center justify-between">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Vous pouvez utiliser du HTML pour formater votre message (balises &lt;p&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;a&gt;, etc.)
                            </p>
                            <span id="ai-status" class="text-xs font-semibold hidden"></span>
                        </div>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- Zone d'affichage des améliorations -->
                        <div id="improvements-box" class="hidden mt-4 p-4 bg-green-50 border-2 border-green-200 rounded-xl">
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="font-bold text-green-900 flex items-center">
                                    <i class="fas fa-check-circle mr-2 text-green-600"></i>
                                    Améliorations apportées
                                </h4>
                                <button type="button" onclick="closeImprovements()" class="text-green-600 hover:text-green-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <ul id="improvements-list" class="list-disc list-inside text-sm text-green-800 space-y-1"></ul>
                        </div>
                    </div>

                    @php
                        $activeSubscribers = \App\Models\NewsletterSubscriber::where('is_active', true)->count();
                    @endphp
                    
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-users text-blue-600 mr-2"></i>
                            <span class="font-bold text-blue-900">Destinataires</span>
                        </div>
                        <p class="text-sm text-blue-700">
                            Cette newsletter sera envoyée à <strong class="text-blue-900">{{ number_format($activeSubscribers) }}</strong> abonné{{ $activeSubscribers > 1 ? 's' : '' }} actif{{ $activeSubscribers > 1 ? 's' : '' }}.
                        </p>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.newsletter') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-300 transition-all">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour
                        </a>
                        <div class="flex items-center space-x-3">
                            <button 
                                type="button"
                                id="analyze-btn-bottom"
                                onclick="analyzeWithAI()"
                                class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all shadow-lg hover:shadow-2xl transform hover:scale-105"
                            >
                                <i class="fas fa-magic mr-2"></i>
                                Analyser avec l'IA
                            </button>
                            <button 
                                type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all shadow-lg hover:shadow-2xl transform hover:scale-105"
                                onclick="return confirm('Êtes-vous sûr de vouloir envoyer cette newsletter à {{ number_format($activeSubscribers) }} abonné(s) ?');"
                            >
                                <i class="fas fa-paper-plane mr-2"></i>
                                Envoyer la Newsletter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Historique des newsletters -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-secondary-500 to-secondary-600">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-history mr-2"></i>
                        Historique
                    </h3>
                </div>
                
                <div class="p-4">
                    @if($newsletters->count() > 0)
                        <div class="space-y-3 max-h-[600px] overflow-y-auto">
                            @foreach($newsletters as $newsletter)
                                <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-primary-300 transition-all">
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="font-bold text-gray-900 text-sm line-clamp-2">{{ $newsletter->subject }}</h4>
                                        <span class="px-2 py-1 text-xs font-bold rounded-full
                                            @if($newsletter->status === 'sent') bg-green-100 text-green-800
                                            @elseif($newsletter->status === 'sending') bg-yellow-100 text-yellow-800
                                            @elseif($newsletter->status === 'failed') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            @if($newsletter->status === 'sent') Envoyé
                                            @elseif($newsletter->status === 'sending') En cours
                                            @elseif($newsletter->status === 'failed') Échec
                                            @else Brouillon
                                            @endif
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 mb-2">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $newsletter->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    @if($newsletter->status === 'sent' || $newsletter->status === 'sending')
                                        <div class="text-xs text-gray-600 space-y-1">
                                            <div class="flex items-center justify-between">
                                                <span>Envoyés:</span>
                                                <span class="font-bold text-green-600">{{ $newsletter->sent_count }}/{{ $newsletter->total_recipients }}</span>
                                            </div>
                                            @if($newsletter->failed_count > 0)
                                                <div class="flex items-center justify-between">
                                                    <span>Échecs:</span>
                                                    <span class="font-bold text-red-600">{{ $newsletter->failed_count }}</span>
                                                </div>
                                            @endif
                                            @if($newsletter->status === 'sent')
                                                <div class="flex items-center justify-between">
                                                    <span>Taux de succès:</span>
                                                    <span class="font-bold text-primary-600">{{ number_format($newsletter->success_rate, 1) }}%</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $newsletters->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 text-sm text-center py-8">
                            <i class="fas fa-inbox text-gray-400 text-3xl mb-2 block"></i>
                            Aucune newsletter envoyée pour le moment.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour analyser le contenu avec l'IA
        async function analyzeWithAI() {
            const subject = document.getElementById('subject').value.trim();
            const content = document.getElementById('content').value.trim();
            const analyzeBtn = document.getElementById('analyze-btn');
            const analyzeBtnBottom = document.getElementById('analyze-btn-bottom');
            const statusEl = document.getElementById('ai-status');
            const improvementsBox = document.getElementById('improvements-box');
            const improvementsList = document.getElementById('improvements-list');

            // Vérifier que les champs sont remplis
            if (!subject || !content) {
                alert('Veuillez remplir le sujet et le contenu avant d\'analyser.');
                return;
            }

            if (content.length < 10) {
                alert('Le contenu doit contenir au moins 10 caractères.');
                return;
            }

            // Afficher le statut de chargement
            analyzeBtn.disabled = true;
            analyzeBtnBottom.disabled = true;
            analyzeBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Analyse en cours...';
            analyzeBtnBottom.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Analyse...';
            statusEl.textContent = '🔄 Analyse en cours...';
            statusEl.classList.remove('hidden', 'text-red-600', 'text-green-600');
            statusEl.classList.add('text-blue-600');
            improvementsBox.classList.add('hidden');

            try {
                const response = await fetch('{{ route("admin.newsletter.analyze") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        subject: subject,
                        content: content,
                        language: 'fr'
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Mettre à jour les champs avec le contenu amélioré
                    document.getElementById('subject').value = data.subject;
                    document.getElementById('content').value = data.content;

                    // Afficher les améliorations
                    if (data.improvements && data.improvements.length > 0) {
                        improvementsList.innerHTML = '';
                        data.improvements.forEach(improvement => {
                            const li = document.createElement('li');
                            li.textContent = improvement;
                            improvementsList.appendChild(li);
                        });
                        improvementsBox.classList.remove('hidden');
                    }

                    // Afficher le succès
                    statusEl.textContent = '✅ Contenu amélioré avec succès !';
                    statusEl.classList.remove('text-blue-600', 'text-red-600');
                    statusEl.classList.add('text-green-600');

                    // Animation de succès
                    analyzeBtn.classList.add('animate-pulse');
                    setTimeout(() => {
                        analyzeBtn.classList.remove('animate-pulse');
                    }, 2000);
                } else {
                    // Afficher l'erreur
                    statusEl.textContent = '❌ ' + (data.error || 'Erreur lors de l\'analyse');
                    statusEl.classList.remove('text-blue-600', 'text-green-600');
                    statusEl.classList.add('text-red-600');
                    
                    // Message d'erreur plus informatif
                    let errorMsg = data.error || 'Impossible d\'analyser le contenu';
                    if (errorMsg.includes('Quota')) {
                        errorMsg += '\n\n💡 Solutions:\n- Vérifiez votre quota OpenAI\n- Utilisez HuggingFace comme alternative\n- Consultez GUIDE_QUOTA_OPENAI.md';
                    }
                    alert('Erreur: ' + errorMsg);
                }
            } catch (error) {
                console.error('Error:', error);
                statusEl.textContent = '❌ Erreur de connexion';
                statusEl.classList.remove('text-blue-600', 'text-green-600');
                statusEl.classList.add('text-red-600');
                alert('Erreur de connexion. Vérifiez votre connexion internet.');
            } finally {
                // Réactiver les boutons
                analyzeBtn.disabled = false;
                analyzeBtnBottom.disabled = false;
                analyzeBtn.innerHTML = '<i class="fas fa-magic mr-2"></i><span class="hidden sm:inline">Analyser avec l\'IA</span><span class="sm:hidden">IA</span>';
                analyzeBtnBottom.innerHTML = '<i class="fas fa-magic mr-2"></i>Analyser avec l\'IA';
            }
        }

        // Fonction pour fermer la boîte d'améliorations
        function closeImprovements() {
            document.getElementById('improvements-box').classList.add('hidden');
        }

        // Animation au chargement
        document.addEventListener('DOMContentLoaded', function() {
            // Ajouter un effet de focus sur les champs
            const subjectInput = document.getElementById('subject');
            const contentInput = document.getElementById('content');

            if (subjectInput && contentInput) {
                subjectInput.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        this.classList.add('border-primary-300');
                    }
                });

                contentInput.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        this.classList.add('border-primary-300');
                    }
                });
            }
        });
    </script>
</x-admin-layout>

