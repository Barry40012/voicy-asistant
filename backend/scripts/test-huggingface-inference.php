<?php

/**
 * Script de test pour vérifier un token HuggingFace avec l'API Inference
 * Usage: php scripts/test-huggingface-inference.php VOTRE_TOKEN_ICI
 */

if ($argc < 2) {
    echo "❌ Usage: php scripts/test-huggingface-inference.php VOTRE_TOKEN_ICI\n";
    exit(1);
}

$token = trim($argv[1]);

echo "🔍 Test du token HuggingFace avec l'API Inference\n";
echo "================================================\n\n";

// Test direct avec l'API Inference
echo "Test de l'API Inference...\n";
$urls = [
    'https://api-inference.huggingface.co/models/bert-base-uncased',
    'https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct-v0.2',
];

$success = false;
$finalHttpCode = null;
$finalResponse = null;

foreach ($urls as $url) {
    echo "   Test avec: {$url}\n";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['inputs' => 'Hello world']));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "   Code HTTP: {$httpCode}\n";
    
    if ($httpCode === 200 || $httpCode === 503) {
        $success = true;
        $finalHttpCode = $httpCode;
        $finalResponse = $response;
        break;
    } elseif ($httpCode === 401) {
        echo "   ❌ Token invalide avec cette URL\n";
        continue;
    } elseif ($httpCode === 410) {
        echo "   ⚠️  URL obsolète, essayons la suivante...\n";
        continue;
    } else {
        $finalHttpCode = $httpCode;
        $finalResponse = $response;
    }
}

// Si aucun test n'a fonctionné, essayer whoami
if (!$success) {
    echo "\n   Test d'authentification basique (whoami)...\n";
    $ch = curl_init('https://huggingface.co/api/whoami');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "   Code HTTP whoami: {$httpCode}\n";
    
    if ($httpCode === 200) {
        $success = true;
        $finalHttpCode = 200;
        $finalResponse = $response;
    }
}

echo "\nCode HTTP final: " . ($finalHttpCode ?? 'N/A') . "\n";

if ($success && ($finalHttpCode === 200 || $finalHttpCode === 503)) {
    echo "✅ SUCCÈS! Le token fonctionne!\n";
    if ($finalHttpCode === 503) {
        echo "⚠️  Code 503: Le modèle est en train de charger (normal pour la première fois)\n";
        echo "✅ Mais le token est VALIDE! (Le 503 signifie que l'authentification a réussi)\n";
    }
    echo "Vous pouvez utiliser ce token dans votre plateforme.\n";
    exit(0);
} elseif ($finalHttpCode === 401) {
    echo "❌ ERREUR 401: Token invalide\n";
    echo "Réponse: " . substr($finalResponse ?? '', 0, 200) . "\n";
    echo "\nSolutions:\n";
    echo "1. Vérifiez que vous avez copié le token complet\n";
    echo "2. Vérifiez que le token n'a pas expiré\n";
    echo "3. Créez un nouveau token sur huggingface.co/settings/tokens\n";
    exit(1);
} else {
    echo "Code HTTP: " . ($finalHttpCode ?? 'N/A') . "\n";
    echo "Réponse: " . substr($finalResponse ?? '', 0, 200) . "\n";
    
    // Si c'est une erreur de modèle mais pas d'authentification, le token est valide
    if (strpos($finalResponse ?? '', 'model') !== false || strpos($finalResponse ?? '', 'loading') !== false) {
        echo "✅ Le token semble VALIDE! (L'erreur vient du modèle, pas de l'authentification)\n";
        exit(0);
    }
    
    exit(1);
}
