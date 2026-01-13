<?php

require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../middlewares/auth.php';

function register_auth_routes() {

    register_rest_route('app', '/login', [
        'methods' => 'POST',
        'callback' => function ($request) {

            $email = $request->get_param('email');
            $password = $request->get_param('password');

            if (!$email || !$password) {
                return api_response(null, 'Email et mot de passe requis', 400);
            }

            $result = AuthService::login($email, $password);

            if (is_wp_error($result)) {
                return api_response(null, $result->get_error_message(), 401);
            }

            return api_response($result, 'Connexion réussie');
        },
        'permission_callback' => '__return_true'
    ]);

    register_rest_route('app', '/signup', [
        'methods' => 'POST',
        'callback' => function ($request) {

            $email = $request->get_param('email');
            $password = $request->get_param('password');
            $name = $request->get_param('name');

            if (!$email || !$password || !$name) {
                return api_response(null, 'Nom, email et mot de passe requis', 400);
            }

            if (email_exists($email)) {
                return api_response(null, 'Cet email est déjà utilisé', 400);
            }

            $user_id = wp_create_user($email, $password, $email);

            if (is_wp_error($user_id)) {
                return api_response(null, 'Erreur lors de la création de l’utilisateur', 500);
            }

            wp_update_user([
                'ID' => $user_id,
                'display_name' => $name
            ]);

            $result = AuthService::login($email, $password);

            return api_response($result, 'Inscription réussie');
        },
        'permission_callback' => '__return_true'
    ]);

    register_rest_route('app', '/me', [
    'methods' => 'GET',
    'callback' => function ($request) {

        $user = $request->get_param('current_user');

        return api_response([
            'id' => $user->ID,
            'email' => $user->user_email,
            'name' => $user->display_name
        ], 'Utilisateur connecté');
    },
    'permission_callback' => 'app_api_auth_middleware'
]);
}
