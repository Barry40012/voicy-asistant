<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tester l\'Analyse Audio') }}
            </h2>
            <a href="{{ route('dashboard.audios.index') }}" class="text-sm text-primary-600 hover:text-primary-800">
                ← Mes Audios
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Mode Test :</strong> Uploadez un fichier audio pour tester le système d'analyse IA sans WhatsApp Business.
                            Le système va transcrire, analyser et générer un résumé automatiquement.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Upload Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-upload mr-2 text-primary-600"></i>
                        Uploader un Audio de Test
                    </h3>

                    <form action="{{ route('dashboard.test-audio.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Audio File -->
                        <div>
                            <label for="audio" class="block text-sm font-medium text-gray-700 mb-2">
                                Fichier Audio <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-microphone text-4xl text-gray-400 mb-2"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="audio" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span>Choisir un fichier</span>
                                            <input id="audio" name="audio" type="file" accept="audio/*" class="sr-only" required>
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">MP3, WAV, OGG, M4A, MP4, WEBM, AAC, FLAC (max 10MB)</p>
                                </div>
                            </div>
                            @error('audio')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sender Phone (Optional) -->
                        <div>
                            <label for="sender_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Numéro de téléphone expéditeur (optionnel)
                            </label>
                            <input type="text" 
                                   name="sender_phone" 
                                   id="sender_phone" 
                                   value="{{ old('sender_phone', '+221000000000') }}"
                                   placeholder="+221000000000"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Simule le numéro qui envoie l'audio (pour les tests)</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('dashboard.audios.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Annuler
                            </a>
                            <button type="submit" class="px-6 py-2 bg-primary-600 text-white text-sm font-semibold rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition shadow-lg hover:shadow-xl">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Analyser l'Audio
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mt-6 bg-gray-50 rounded-lg p-6">
                <h4 class="text-md font-semibold text-gray-900 mb-3">
                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                    Comment ça fonctionne ?
                </h4>
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
                    <li><strong>Uploadez un audio</strong> : Choisissez un fichier audio (MP3, WAV, OGG, M4A)</li>
                    <li><strong>Traitement automatique</strong> : Le système va :
                        <ul class="list-disc list-inside ml-6 mt-1 space-y-1">
                            <li>Transcrire l'audio avec Whisper (OpenAI)</li>
                            <li>Détecter la langue automatiquement (français, anglais, mixte)</li>
                            <li>Générer un résumé intelligent</li>
                            <li>Extraire les actions à effectuer</li>
                            <li>Générer une réponse suggérée</li>
                        </ul>
                    </li>
                    <li><strong>Voir les résultats</strong> : Une fois traité, vous verrez :
                        <ul class="list-disc list-inside ml-6 mt-1 space-y-1">
                            <li>La transcription complète</li>
                            <li>Le résumé (3 lignes)</li>
                            <li>Les actions à effectuer</li>
                            <li>La réponse suggérée (que vous pouvez envoyer manuellement)</li>
                        </ul>
                    </li>
                    <li><strong>Où voir les résultats ?</strong> : Dans <strong>Dashboard > Mes Audios</strong></li>
                </ol>
            </div>

            <!-- Note about auto-reply -->
            <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Note :</strong> En mode test, le système ne répond pas automatiquement. 
                            Il génère une réponse suggérée que vous pouvez voir dans les détails de l'audio.
                            Pour activer la réponse automatique, configurez WhatsApp Business et activez l'option dans vos paramètres.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

