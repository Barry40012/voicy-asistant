<?php

/**
 * Script de test manuel pour vérifier un token HuggingFace
 * Usage: php scripts/test-huggingface-token.php VOTRE_TOKEN_ICI
 */

if ($argc < 2) {
    echo "❌ Usage: php scripts/test-huggingface-token.php VOTRE_TOKEN_ICI\n";
    echo "\n";
    echo "Exemple:\n";
    echo "php scripts/test-huggingface-token.php hf_VOTRE_TOKEN_ICI\n";
    exit(1);
}

$token = trim($argv[1]);

echo "🔍 Test du token HuggingFace\n";
echo "============================\n\n";

// Vérifier le format
echo "1. Vérification du format...\n";
if (!str_starts_with($token, 'hf_')) {
    echo "   ❌ ERREUR: Le token doit commencer par 'hf_'\n";
    echo "   Token reçu: " . substr($token, 0, 10) . "...\n";
    exit(1);
}
echo "   ✅ Le token commence par 'hf_'\n";

// Vérifier la longueur
echo "\n2. Vérification de la longueur...\n";
$length = strlen($token);
echo "   Longueur: {$length} caractères\n";
if ($length < 20) {
    echo "   ⚠️  ATTENTION: Le token semble trop court (normalement 40-50 caractères)\n";
} elseif ($length > 100) {
    echo "   ⚠️  ATTENTION: Le token semble trop long\n";
} else {
    echo "   ✅ Longueur correcte\n";
}

// Vérifier les espaces
echo "\n3. Vérification des espaces...\n";
if (str_contains($token, ' ')) {
    echo "   ❌ ERREUR: Le token contient des espaces!\n";
    echo "   Token avec espaces: '{$token}'\n";
    echo "   Solution: Enlevez les espaces avant/après\n";
    exit(1);
}
echo "   ✅ Pas d'espaces détectés\n";

// Test de connexion
echo "\n4. Test de connexion à l'API HuggingFace...\n";

// Essayer d'abord avec l'endpoint whoami
echo "   Test 1: Endpoint /api/whoami...\n";
$ch = curl_init('https://huggingface.co/api/whoami');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Content-Type: application/json',
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Pour Windows local

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Si whoami échoue, essayer avec l'endpoint inference
if ($httpCode !== 200) {
    echo "   Test 2: Endpoint Inference API (test simple)...\n";
    $ch = curl_init('https://api-inference.huggingface.co/models/bert-base-uncased');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['inputs' => 'Hello world']));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response2 = curl_exec($ch);
    $httpCode2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Si l'inference fonctionne, le token est valide même si whoami échoue
    if ($httpCode2 === 200 || $httpCode2 === 503) { // 503 = modèle en chargement, mais token valide
        echo "   ✅ Le token fonctionne avec l'API Inference!\n";
        $httpCode = 200; // Considérer comme succès
        $response = json_encode(['name' => 'Token valide (testé via Inference API)']);
    }
}

if ($error) {
    echo "   ❌ ERREUR CURL: {$error}\n";
    exit(1);
}

echo "   Code HTTP: {$httpCode}\n";

if ($httpCode === 200) {
    $data = json_decode($response, true);
    echo "   ✅ CONNEXION RÉUSSIE!\n";
    echo "\n   Informations utilisateur:\n";
    echo "   - Nom: " . ($data['name'] ?? 'N/A') . "\n";
    echo "   - Type: " . ($data['type'] ?? 'N/A') . "\n";
    echo "\n   ✅ Votre token est VALIDE et fonctionne!\n";
    echo "\n   Vous pouvez maintenant l'utiliser dans l'administration.\n";
} elseif ($httpCode === 401) {
    echo "   ❌ ERREUR 401: Token invalide ou expiré\n";
    echo "   Réponse: {$response}\n";
    echo "\n   Solutions:\n";
    echo "   1. Vérifiez que vous avez copié le token complet\n";
    echo "   2. Vérifiez que le token n'a pas expiré\n";
    echo "   3. Créez un nouveau token sur huggingface.co/settings/tokens\n";
    exit(1);
} elseif ($httpCode === 403) {
    echo "   ❌ ERREUR 403: Token sans permissions\n";
    echo "   Réponse: {$response}\n";
    echo "\n   Solution: Créez un token avec permission 'Read' minimum\n";
    exit(1);
} else {
    echo "   ❌ ERREUR: Code HTTP {$httpCode}\n";
    echo "   Réponse: {$response}\n";
    exit(1);
}

echo "\n✅ Test terminé avec succès!\n";

