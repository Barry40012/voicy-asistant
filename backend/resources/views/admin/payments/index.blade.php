<x-admin-layout pageTitle="Gestion des Paiements">
    <!-- Statistiques -->
    <div class="space-y-6 mb-6">
        <!-- Totaux convertis -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm font-medium text-gray-600">Total</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['converted']['XOF']['total'], 2) }} XOF</p>
                <p class="text-sm text-gray-500 mt-1">{{ number_format($stats['converted']['GNF']['total'], 2) }} GNF</p>
                <p class="text-sm text-gray-500">{{ number_format($stats['converted']['USD']['total'], 2) }} USD</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm font-medium text-gray-600">Réussis</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($stats['converted']['XOF']['succeeded'], 2) }} XOF</p>
                <p class="text-sm text-gray-500 mt-1">{{ number_format($stats['converted']['GNF']['succeeded'], 2) }} GNF</p>
                <p class="text-sm text-gray-500">{{ number_format($stats['converted']['USD']['succeeded'], 2) }} USD</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm font-medium text-gray-600">En attente</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">{{ number_format($stats['converted']['XOF']['pending'], 2) }} XOF</p>
                <p class="text-sm text-gray-500 mt-1">{{ number_format($stats['converted']['GNF']['pending'], 2) }} GNF</p>
                <p class="text-sm text-gray-500">{{ number_format($stats['converted']['USD']['pending'], 2) }} USD</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm font-medium text-gray-600">Échoués</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format($stats['converted']['XOF']['failed'], 2) }} XOF</p>
                <p class="text-sm text-gray-500 mt-1">{{ number_format($stats['converted']['GNF']['failed'], 2) }} GNF</p>
                <p class="text-sm text-gray-500">{{ number_format($stats['converted']['USD']['failed'], 2) }} USD</p>
            </div>
        </div>

        <!-- Détails par devise -->
        @if(count($stats['by_currency']['total']) > 0)
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Détails par devise</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($stats['by_currency']['total'] as $currency => $total)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-600 mb-2">{{ $currency }}</p>
                            <p class="text-xl font-bold text-gray-900">{{ number_format($total, 2) }} {{ $currency }}</p>
                            <div class="mt-2 space-y-1 text-xs text-gray-500">
                                <p>Réussis: {{ number_format($stats['by_currency']['succeeded'][$currency] ?? 0, 2) }} {{ $currency }}</p>
                                <p>En attente: {{ number_format($stats['by_currency']['pending'][$currency] ?? 0, 2) }} {{ $currency }}</p>
                                <p>Échoués: {{ number_format($stats['by_currency']['failed'][$currency] ?? 0, 2) }} {{ $currency }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Liste des Paiements</h2>
            <div class="text-sm text-gray-500">
                Total: {{ $payments->total() }} paiement(s)
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($payment->provider) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->status === 'succeeded')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        ✓ Réussi
                                    </span>
                                @elseif($payment->status === 'pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        ⏳ En attente
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        ✗ Échoué
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $payment->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Aucun paiement trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $payments->links() }}
        </div>
    </div>
</x-admin-layout>

