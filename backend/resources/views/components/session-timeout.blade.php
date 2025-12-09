<!-- Session Timeout Warning -->
<div x-data="sessionTimeout()" x-show="showWarning" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-4"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-4"
     class="fixed bottom-4 right-4 z-50 max-w-md w-full sm:w-96"
     style="display: none;">
    <div class="bg-white rounded-lg shadow-2xl border-2 border-yellow-400 p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-lg font-bold text-gray-900 mb-1">
                    Session sur le point d'expirer
                </h3>
                <p class="text-sm text-gray-600 mb-4">
                    Votre session expirera dans <span x-text="remainingSeconds" class="font-bold text-yellow-600"></span> seconde<span x-show="remainingSeconds > 1">s</span>.
                    <br>
                    Cliquez sur "Rester connecté" pour continuer.
                </p>
                <div class="flex items-center space-x-3">
                    <button @click="extendSession()" 
                            class="flex-1 bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors font-semibold text-sm">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Rester connecté
                    </button>
                    <button @click="logout()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold text-sm">
                        Se déconnecter
                    </button>
                </div>
            </div>
            <button @click="dismiss()" class="ml-2 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Progress bar -->
        <div class="mt-4 w-full bg-gray-200 rounded-full h-2 overflow-hidden">
            <div class="bg-yellow-500 h-full rounded-full transition-all duration-1000 ease-linear"
                 :style="`width: ${progressPercentage}%`"></div>
        </div>
    </div>
</div>

<script>
function sessionTimeout() {
    return {
        showWarning: false,
        remainingSeconds: 60,
        progressPercentage: 100,
        warningInterval: null,
        countdownInterval: null,
        activityCheckInterval: null,
        lastActivity: Date.now(),
        sessionLifetime: {{ config('session.lifetime', 30) * 60 }}, // Convert to seconds
        warningTime: 60, // Show warning 60 seconds before expiration
        inactivityThreshold: 5 * 60 * 1000, // 5 minutes of inactivity

        init() {
            // Track user activity
            this.trackActivity();
            
            // Check for inactivity every minute
            this.activityCheckInterval = setInterval(() => {
                this.checkInactivity();
            }, 60000);
            
            // Check session status every 30 seconds
            setInterval(() => {
                this.checkSessionStatus();
            }, 30000);
        },

        trackActivity() {
            // Track mouse movement, clicks, keyboard, scroll
            ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'].forEach(event => {
                document.addEventListener(event, () => {
                    this.lastActivity = Date.now();
                    this.resetWarning();
                }, true);
            });
        },

        checkInactivity() {
            const timeSinceLastActivity = Date.now() - this.lastActivity;
            
            if (timeSinceLastActivity >= this.inactivityThreshold) {
                // User is inactive, show warning
                this.showWarningMessage();
            }
        },

        checkSessionStatus() {
            // Check if session is about to expire
            // This is a client-side estimation based on inactivity
            const timeSinceLastActivity = Date.now() - this.lastActivity;
            const estimatedTimeRemaining = (this.sessionLifetime * 1000) - timeSinceLastActivity;
            
            if (estimatedTimeRemaining <= (this.warningTime * 1000) && estimatedTimeRemaining > 0) {
                this.showWarningMessage();
            }
        },

        showWarningMessage() {
            if (this.showWarning) return; // Already showing
            
            this.showWarning = true;
            this.remainingSeconds = this.warningTime;
            this.progressPercentage = 100;
            
            // Start countdown
            this.countdownInterval = setInterval(() => {
                this.remainingSeconds--;
                this.progressPercentage = (this.remainingSeconds / this.warningTime) * 100;
                
                if (this.remainingSeconds <= 0) {
                    this.logout();
                }
            }, 1000);
        },

        resetWarning() {
            if (this.showWarning) {
                this.showWarning = false;
                this.remainingSeconds = this.warningTime;
                this.progressPercentage = 100;
                
                if (this.countdownInterval) {
                    clearInterval(this.countdownInterval);
                    this.countdownInterval = null;
                }
            }
        },

        async extendSession() {
            try {
                // Ping the server to extend session
                const response = await fetch('/api/session/keep-alive', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });
                
                if (response.ok) {
                    this.lastActivity = Date.now();
                    this.resetWarning();
                    
                    // Show success message
                    this.showSuccessMessage();
                } else {
                    this.logout();
                }
            } catch (error) {
                console.error('Error extending session:', error);
                this.logout();
            }
        },

        showSuccessMessage() {
            // Show a brief success message
            const successEl = document.createElement('div');
            successEl.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            successEl.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Session prolongée';
            document.body.appendChild(successEl);
            
            setTimeout(() => {
                successEl.remove();
            }, 3000);
        },

        dismiss() {
            this.resetWarning();
        },

        logout() {
            // Clear intervals
            if (this.countdownInterval) clearInterval(this.countdownInterval);
            if (this.activityCheckInterval) clearInterval(this.activityCheckInterval);
            
            // Redirect to logout
            window.location.href = '/logout';
        }
    }
}
</script>

