<div x-data="notifications()" x-init="init()" class="relative">
    <!-- Notification Bell -->
    <button @click="toggleDropdown()" class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition-colors">
        <i class="fas fa-bell text-xl"></i>
        <span x-show="unreadCount > 0" 
              x-text="unreadCount > 99 ? '99+' : unreadCount"
              class="absolute top-0 right-0 inline-flex items-center justify-center min-w-[20px] px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full animate-pulse shadow-lg border-2 border-white z-10">
        </span>
    </button>

    <!-- Dropdown -->
    <div x-show="isOpen" 
         @click.away="isOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50 max-h-96 overflow-hidden flex flex-col">
        
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-primary-50 to-secondary-50">
            <h3 class="text-sm font-bold text-gray-900 flex items-center">
                <i class="fas fa-bell mr-2 text-primary-600"></i>
                Notifications
                <span x-show="unreadCount > 0" 
                      x-text="'(' + unreadCount + ')'"
                      class="ml-2 text-primary-600">
                </span>
            </h3>
            <button @click="markAllAsRead()" 
                    x-show="unreadCount > 0"
                    class="text-xs text-primary-600 hover:text-primary-700 font-semibold">
                Tout marquer lu
            </button>
        </div>

        <!-- Notifications List -->
        <div class="overflow-y-auto flex-1" style="max-height: 400px;">
            <template x-if="loading">
                <div class="p-8 text-center">
                    <i class="fas fa-spinner fa-spin text-primary-600 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-500">Chargement...</p>
                </div>
            </template>

            <template x-if="!loading && notifications.length === 0">
                <div class="p-8 text-center">
                    <i class="fas fa-bell-slash text-gray-300 text-4xl mb-3"></i>
                    <p class="text-sm text-gray-500">Aucune notification</p>
                </div>
            </template>

            <template x-for="notification in notifications" :key="notification.id">
                <div @click="handleNotificationClick(notification)"
                     :class="{
                         'bg-primary-50': !notification.is_read,
                         'bg-white': notification.is_read
                     }"
                     class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-3">
                            <div :class="getIconClass(notification.icon)" 
                                 class="w-10 h-10 rounded-full flex items-center justify-center">
                                <i :class="getIcon(notification.icon)" class="text-white"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900" x-text="notification.title"></p>
                            <p class="text-xs text-gray-600 mt-1 line-clamp-2" x-text="notification.message"></p>
                            <p class="text-xs text-gray-400 mt-1" x-text="formatDate(notification.created_at)"></p>
                        </div>
                        <div class="flex-shrink-0 ml-2">
                            <span x-show="!notification.is_read" 
                                  class="w-2 h-2 bg-primary-600 rounded-full inline-block">
                            </span>
                            <button @click.stop="deleteNotification(notification.id)"
                                    class="text-gray-400 hover:text-red-600 ml-2">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="px-4 py-2 border-t border-gray-200 bg-gray-50 text-center">
            <a href="#" class="text-xs text-primary-600 hover:text-primary-700 font-semibold">
                Voir toutes les notifications
            </a>
        </div>
    </div>
</div>

<script>
function notifications() {
    return {
        isOpen: false,
        notifications: [],
        unreadCount: 0,
        loading: true,

        init() {
            this.loadNotifications();
            // Poll for new notifications every 30 seconds
            setInterval(() => {
                if (!this.isOpen) {
                    this.loadNotifications();
                }
            }, 30000);
        },

        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.loadNotifications();
            }
        },

        async loadNotifications() {
            try {
                const response = await fetch('/api/notifications?limit=10', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
                this.loading = false;
            } catch (error) {
                console.error('Error loading notifications:', error);
                this.loading = false;
            }
        },

        async markAsRead(notificationId) {
            try {
                const response = await fetch(`/api/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                this.unreadCount = data.unread_count;
                // Update notification in list
                const notification = this.notifications.find(n => n.id === notificationId);
                if (notification) {
                    notification.is_read = true;
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },

        async markAllAsRead() {
            try {
                const response = await fetch('/api/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                this.unreadCount = 0;
                this.notifications.forEach(n => n.is_read = true);
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        },

        async deleteNotification(notificationId) {
            try {
                const response = await fetch(`/api/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                this.unreadCount = data.unread_count;
                this.notifications = this.notifications.filter(n => n.id !== notificationId);
            } catch (error) {
                console.error('Error deleting notification:', error);
            }
        },

        handleNotificationClick(notification) {
            if (!notification.is_read) {
                this.markAsRead(notification.id);
            }
            if (notification.action_url) {
                window.location.href = notification.action_url;
            }
        },

        getIcon(iconType) {
            const icons = {
                'success': 'fas fa-check-circle',
                'error': 'fas fa-exclamation-circle',
                'warning': 'fas fa-exclamation-triangle',
                'info': 'fas fa-info-circle',
            };
            return icons[iconType] || icons.info;
        },

        getIconClass(iconType) {
            const classes = {
                'success': 'bg-green-500',
                'error': 'bg-red-500',
                'warning': 'bg-yellow-500',
                'info': 'bg-blue-500',
            };
            return classes[iconType] || classes.info;
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(diff / 3600000);
            const days = Math.floor(diff / 86400000);

            if (minutes < 1) return 'À l\'instant';
            if (minutes < 60) return `Il y a ${minutes} min`;
            if (hours < 24) return `Il y a ${hours}h`;
            if (days < 7) return `Il y a ${days}j`;
            return date.toLocaleDateString('fr-FR');
        }
    }
}
</script>

