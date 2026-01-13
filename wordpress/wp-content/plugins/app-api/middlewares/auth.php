<?php

require_once __DIR__ . '/../helpers/response.php';

function app_api_auth_middleware($request) {

    $headers = $request->get_headers();

    if (!isset($headers['authorization'])) {
        return new WP_Error(
            'unauthorized',
            'Token manquant',
            ['status' => 401]
        );
    }

    $auth = $headers['authorization'][0];
    $token = str_replace('Bearer ', '', $auth);

    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        return new WP_Error('invalid_token', 'Token invalide', ['status' => 401]);
    }

    [$header, $payload, $signature] = $parts;

    $valid_signature = base64_encode(
        hash_hmac(
            'sha256',
            "$header.$payload",
            JWT_AUTH_SECRET_KEY,
            true
        )
    );

    if (!hash_equals($valid_signature, $signature)) {
        return new WP_Error('invalid_signature', 'Signature invalide', ['status' => 401]);
    }

    $payload = json_decode(base64_decode($payload), true);

    if ($payload['exp'] < time()) {
        return new WP_Error('token_expired', 'Token expiré', ['status' => 401]);
    }

    $user = get_user_by('ID', $payload['user_id']);
    if (!$user) {
        return new WP_Error('user_not_found', 'Utilisateur introuvable', ['status' => 401]);
    }

    // Stocker l'utilisateur pour la requête courante
    $request->set_param('current_user', $user);

    return true;
}
