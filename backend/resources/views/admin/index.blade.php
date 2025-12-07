<x-admin-layout pageTitle="Dashboard Administrateur">

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Utilisateurs -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Utilisateurs</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_users']) }}</p>
                    <p class="text-xs text-green-600 mt-1">+{{ $stats['new_users_today'] }} aujourd'hui</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Abonnements Actifs -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Abonnements Actifs</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['active_subscriptions']) }}</p>
                    <p class="text-xs text-yellow-600 mt-1">{{ $stats['pending_subscriptions'] }} en attente</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Audios Traités -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Audios Traités</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['processed_audios']) }}</p>
                    <p class="text-xs text-blue-600 mt-1">{{ $stats['processing_audios'] }} en cours</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Revenus Totaux -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Revenus Totaux</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_revenue']['XOF'], 2) }} XOF</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['total_revenue']['GNF'], 2) }} GNF</p>
                    <p class="text-xs text-gray-500">{{ number_format($stats['total_revenue']['USD'], 2) }} USD</p>
                    <p class="text-xs text-green-600 mt-2">{{ number_format($stats['revenue_this_month']['XOF'], 2) }} XOF ce mois</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique des revenus (7 derniers jours) -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Revenus des 7 derniers jours</h2>
        <div class="h-64 flex items-end justify-between space-x-2">
            @foreach($revenueChart as $day)
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-gray-200 rounded-t relative" style="height: {{ $day['amount'] > 0 ? max(20, ($day['amount'] / max(array_column($revenueChart, 'amount'))) * 100) : 0 }}%">
                        <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 text-xs text-gray-600 whitespace-nowrap">
                            {{ number_format($day['amount'], 0) }} XOF
                        </div>
                    </div>
                    <span class="text-xs text-gray-500 mt-2">{{ $day['date'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Activités récentes -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Paiements récents -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Paiements récents</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentPayments as $payment)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $payment->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-green-600">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    {{ $payment->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Aucun paiement récent</p>
                    @endforelse
                </div>
                <a href="{{ route('admin.payments') }}" class="block mt-4 text-center text-sm text-primary-600 hover:text-primary-700">
                    Voir tous les paiements →
                </a>
            </div>
        </div>

        <!-- Utilisateurs récents -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Nouveaux utilisateurs</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentUsers as $user)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Aucun nouvel utilisateur</p>
                    @endforelse
                </div>
                <a href="{{ route('admin.users') }}" class="block mt-4 text-center text-sm text-primary-600 hover:text-primary-700">
                    Voir tous les utilisateurs →
                </a>
            </div>
        </div>

        <!-- Audios récents -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Audios récents</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentAudios as $audio)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $audio->user->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $audio->created_at->diffForHumans() }}
                                    @if($audio->status === 'done')
                                        <span class="ml-2 text-green-600">✓ Traité</span>
                                    @elseif($audio->status === 'processing')
                                        <span class="ml-2 text-yellow-600">⏳ En cours</span>
                                    @else
                                        <span class="ml-2 text-gray-600">⏸ En attente</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Aucun audio récent</p>
                    @endforelse
                </div>
                <a href="{{ route('admin.audios') }}" class="block mt-4 text-center text-sm text-primary-600 hover:text-primary-700">
                    Voir tous les audios →
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>

