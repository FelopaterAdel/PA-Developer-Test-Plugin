<?php

class PA_Test_REST_API {

    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }

    public function register_routes() {
        register_rest_route( 'pa-test/v1', '/process', [
            'methods'  => 'POST',
            'callback' => [ $this, 'process_number' ],
            'permission_callback' => '__return_true'
        ] );
    }

    public function process_number( WP_REST_Request $request ) {
        $number = intval( $request->get_param( 'number' ) );

        if ( $number <= 0 ) {
            return new WP_REST_Response(
                [ 'error' => 'Invalid number' ],
                400
            );
        }

        $result = $number * 2;

        return [
            'original' => $number,
            'processed' => $result
        ];
    }
}
