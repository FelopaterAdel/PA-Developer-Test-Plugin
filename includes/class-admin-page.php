<?php

class PA_Test_Admin_Page {

    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'pa_test_entries';

        add_action( 'admin_menu', [ $this, 'register_menu' ] );
        add_action( 'admin_post_pa_test_save', [ $this, 'handle_form' ] );
    }

    public function register_menu() {
        add_options_page(
            'PA Test Plugin',
            'PA Test Plugin',
            'manage_options',
            'pa-test-plugin',
            [ $this, 'render_page' ]
        );
    }

    public function render_page() {
        global $wpdb;
        $entries = $wpdb->get_results( "SELECT * FROM {$this->table_name} ORDER BY created_at DESC" );
        ?>
        <div class="wrap">
            <h1>PA Developer Test Plugin</h1>

            <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
                <input type="hidden" name="action" value="pa_test_save">
                <?php wp_nonce_field( 'pa_test_nonce' ); ?>

                <input type="text" name="entry_text" required>
                <button class="button button-primary">Save</button>
            </form>

            <hr>

            <h2>Saved Entries</h2>
            <ul>
                <?php foreach ( $entries as $entry ) : ?>
                    <li><?php echo esc_html( $entry->entry_text ); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }

    public function handle_form() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Unauthorized' );
        }

        check_admin_referer( 'pa_test_nonce' );

        global $wpdb;

        $text = sanitize_text_field( $_POST['entry_text'] );

        $wpdb->insert(
            $this->table_name,
            [ 'entry_text' => $text ],
            [ '%s' ]
        );

        wp_redirect( admin_url( 'options-general.php?page=pa-test-plugin' ) );
        exit;
    }
}
