<?php

class AuthService {

    public static function login($email, $password) {
        $user = get_user_by('email', $email);

        if (!$user) {
            return new WP_Error('invalid_email', 'Email incorrect', ['status' => 401]);
        }

        if (!wp_check_password($password, $user->user_pass, $user->ID)) {
            return new WP_Error('invalid_password', 'Mot de passe incorrect', ['status' => 401]);
        }

        return self::generateToken($user);
    }

    private static function generateToken($user) {
        $header = base64_encode(json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]));

        $payload = base64_encode(json_encode([
            'iss' => get_site_url(),
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24), // 24h
            'user_id' => $user->ID,
            'email' => $user->user_email
        ]));

        $signature = hash_hmac(
            'sha256',
            "$header.$payload",
            JWT_AUTH_SECRET_KEY,
            true
        );

        return [
            'token' => "$header.$payload." . base64_encode($signature),
            'user' => [
                'id' => $user->ID,
                'email' => $user->user_email,
                'name' => $user->display_name
            ]
        ];
    }
}
