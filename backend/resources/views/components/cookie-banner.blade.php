<!-- Cookie Consent Banner -->
<div x-data="cookieBanner()" 
     x-show="showBanner"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-4"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-4"
     class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t-2 border-primary-200 shadow-2xl"
     style="display: none;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center mb-2">
                    <i class="fas fa-cookie-bite text-primary-600 text-2xl mr-3"></i>
                    <h3 class="text-lg font-bold text-gray-900">Gestion des Cookies</h3>
                </div>
                <p class="text-sm text-gray-700 leading-relaxed">
                    Nous utilisons des <strong>cookies essentiels</strong> pour assurer le bon fonctionnement de la plateforme (authentification, sécurité, préférences). 
                    Ces cookies sont nécessaires et ne peuvent pas être désactivés. 
                    <a href="/cookies-policy" class="text-primary-600 hover:text-primary-700 underline font-semibold" target="_blank">En savoir plus</a>
                </p>
                <div class="mt-3 text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>Cookies utilisés :</strong> Session (authentification), CSRF (sécurité), Préférences (langue, thème)
                </div>
            </div>
            <div class="flex items-center space-x-3 flex-shrink-0">
                <button @click="acceptCookies()" 
                        class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-semibold text-sm shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-check-circle mr-2"></i>
                    J'accepte
                </button>
                <button @click="showDetails = !showDetails" 
                        class="px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold text-sm">
                    <i class="fas fa-cog mr-2"></i>
                    Paramètres
                </button>
            </div>
        </div>
        
        <!-- Details Section -->
        <div x-show="showDetails" 
             x-collapse
             class="mt-4 pt-4 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-shield-alt text-blue-600 mr-2"></i>
                        <h4 class="font-bold text-blue-900">Cookies Essentiels</h4>
                        <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">Toujours actifs</span>
                    </div>
                    <p class="text-xs text-blue-700">
                        Ces cookies sont nécessaires pour l'authentification, la sécurité (protection CSRF) et le fonctionnement de base de la plateforme. Ils ne peuvent pas être désactivés.
                    </p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-chart-line text-gray-600 mr-2"></i>
                        <h4 class="font-bold text-gray-900">Cookies Analytics (Optionnel)</h4>
                    </div>
                    <p class="text-xs text-gray-700 mb-3">
                        Ces cookies nous aident à comprendre comment vous utilisez la plateforme pour l'améliorer.
                    </p>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" x-model="analyticsEnabled" @change="updateAnalytics()" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <span class="ml-2 text-xs text-gray-700">Activer les cookies analytics</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function cookieBanner() {
    return {
        showBanner: false,
        showDetails: false,
        analyticsEnabled: false,

        init() {
            // Check if user has already accepted cookies
            const cookieConsent = localStorage.getItem('cookie_consent');
            if (!cookieConsent) {
                this.showBanner = true;
            } else {
                const consent = JSON.parse(cookieConsent);
                this.analyticsEnabled = consent.analytics || false;
            }
        },

        acceptCookies() {
            const consent = {
                accepted: true,
                date: new Date().toISOString(),
                analytics: this.analyticsEnabled
            };
            
            localStorage.setItem('cookie_consent', JSON.stringify(consent));
            this.showBanner = false;
            
            // If analytics enabled, you can initialize tracking here
            if (this.analyticsEnabled) {
                this.initializeAnalytics();
            }
            
            // Show success message
            this.showSuccessMessage();
        },

        updateAnalytics() {
            // Update analytics preference immediately
            const cookieConsent = localStorage.getItem('cookie_consent');
            if (cookieConsent) {
                const consent = JSON.parse(cookieConsent);
                consent.analytics = this.analyticsEnabled;
                localStorage.setItem('cookie_consent', JSON.stringify(consent));
                
                if (this.analyticsEnabled) {
                    this.initializeAnalytics();
                } else {
                    this.disableAnalytics();
                }
            }
        },

        initializeAnalytics() {
            // Initialize analytics tracking (Google Analytics, etc.)
            // Example: gtag('config', 'GA_MEASUREMENT_ID');
            console.log('Analytics enabled');
        },

        disableAnalytics() {
            // Disable analytics tracking
            console.log('Analytics disabled');
        },

        showSuccessMessage() {
            const successEl = document.createElement('div');
            successEl.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center';
            successEl.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Préférences enregistrées';
            document.body.appendChild(successEl);
            
            setTimeout(() => {
                successEl.remove();
            }, 3000);
        }
    }
}
</script>

