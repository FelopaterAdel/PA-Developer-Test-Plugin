<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PA_Test_REST_API {

    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }

    /**
     * Register custom REST API routes
     */
    public function register_routes() {
        register_rest_route(
            'pa-test/v1',
            '/process',
            [
                'methods'             => 'POST',
                'callback'            => [ $this, 'handle_user_lookup' ],
                'permission_callback' => '__return_true', // Public endpoint (can be restricted later)
                'args'                => [
                    'user_id' => [
                        'required'          => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => function ( $param ) {
                            return $param > 0;
                        },
                    ],
                ],
            ]
        );
    }

    /**
     * REST API callback:
     * - Verifies user existence by ID
     * - Returns username and email in JSON format
     */
    public function handle_user_lookup( WP_REST_Request $request ) {

        $user_id = $request->get_param( 'user_id' );

        $user = get_user_by( 'id', $user_id );

        if ( ! $user ) {
            return new WP_REST_Response(
                [
                    'success' => false,
                    'message' => 'User not found',
                ],
                404
            );
        }

        return new WP_REST_Response(
            [
                'success'  => true,
                'data'     => [
                    'id'       => $user->ID,
                    'username' => $user->user_login,
                    'email'    => $user->user_email,
                ],
            ],
            200
        );
    }
}
