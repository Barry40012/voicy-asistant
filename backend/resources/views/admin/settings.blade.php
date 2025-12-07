<x-admin-layout pageTitle="Paramètres de la Plateforme">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Gestion du Logo -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Logo de la Plateforme</h2>
                <p class="text-sm text-gray-600 mt-1">Téléversez un logo qui sera affiché sur toute la plateforme</p>
            </div>

            <div class="p-6">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                <form action="{{ route('admin.settings.logo') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Logo actuel -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Logo actuel</label>
                        <div class="flex items-center space-x-4">
                            @if($logoUrl)
                                <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                                    <img src="{{ asset($logoUrl) }}" alt="Logo actuel" class="h-20 w-auto max-w-xs object-contain">
                                </div>
                            @else
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 bg-gray-50 text-center">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500">Aucun logo uploadé</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upload nouveau logo -->
                    <div>
                        <label for="logo" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nouveau logo <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex items-center">
                            <input type="file" 
                                   name="logo" 
                                   id="logo" 
                                   accept="image/jpeg,image/png,image/jpg,image/svg+xml,image/webp"
                                   required
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 file:cursor-pointer border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('logo') border-red-500 @enderror">
                        </div>
                        <div class="mt-2 text-xs text-gray-500 space-y-1">
                            <p>• Formats acceptés : JPEG, PNG, JPG, SVG, WEBP</p>
                            <p>• Taille maximale : 2 MB</p>
                            <p>• Dimensions recommandées : 500x500 pixels maximum</p>
                            <p>• Le logo sera affiché derrière "Voicy Assistant" sur toute la plateforme</p>
                        </div>
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Aperçu -->
                    <div id="preview-container" class="hidden">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Aperçu</label>
                        <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                            <img id="preview" src="" alt="Aperçu" class="h-20 w-auto max-w-xs object-contain">
                        </div>
                    </div>

                    <!-- Bouton -->
                    <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                        <button type="submit" class="flex items-center justify-center px-8 py-3.5 bg-primary-50 border-2 border-primary-500 text-primary-700 rounded-lg hover:bg-primary-100 hover:border-primary-600 hover:text-primary-800 transition-all duration-200 font-bold text-sm shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            <span class="text-primary-700 font-bold tracking-wide">Téléverser le logo</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Aperçu du logo avant upload
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('preview-container').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-admin-layout>

