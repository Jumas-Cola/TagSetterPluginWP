<?php



class TagSetter_class{
    public function __construct(){
        #add_action(wp_head,array($this,'main'));
        add_action(admin_menu, array($this,'menu'));
    }

    public function menu(){
        add_menu_page('Set Tags Page', 'Set Tag Button', 'manage_options', 'button-slug', array($this,'button_admin_page'));
    }

    public function button_admin_page() {

        // This function creates the output for the admin page.
        // It also checks the value of the $_POST variable to see whether
        // there has been a form submission. 

        // The check_admin_referer is a WordPress function that does some security
        // checking and is recommended good practice.

        // General check for user permissions.
        if (!current_user_can('manage_options'))  {
            wp_die( __('You do not have sufficient pilchards to access this page.')    );
        }

        // Start building the page

        echo '<div class="wrap">';

        echo '<h2>Set tags</h2>';

        // Check whether the button has been pressed AND also check the nonce
        if (isset($_POST['import_button']) && check_admin_referer('import_button_clicked')) {
            // the button has been pressed AND we've passed the security check
            $this->main();
        }

        echo '<form action="options-general.php?page=button-slug" method="post">';


        echo '<h3>Skus</h3>';
        echo '<textarea id="skus" name="skus" rows="5" cols="33"></textarea>';
        
        echo '<h3>Tags</h3>';
        echo '<textarea id="tags" name="tags" rows="5" cols="33"></textarea>';

        // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
        wp_nonce_field('import_button_clicked');
        echo '<input type="hidden" value="true" name="import_button" />';
        submit_button('Start');
        echo '</form>';

        echo '</div>';

    }


    public function main() {
        echo '<div id="message" class="updated fade"><p>'
            .'Start.' . '</p></div>';

        $skus = explode(',', $_POST['skus']);

        $tags_array = explode(',', $_POST['tags']);

        foreach ($skus as $sku) {

            $sku = trim($sku);

            $post_id = wc_get_product_id_by_sku($sku);

            $terms =  get_the_terms( $post_id, 'product_tag' );
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
                foreach ( $terms as $term ) {
                    $tags_array[] = $term->name;
                }
            }

            $tags = array_unique($tags_array);

            if (isset($tags))
            {
                foreach ( $tags as $tag ) {
                    $tag = trim(str_replace(' ', '_', $tag));
                }
                wp_set_object_terms( $post_id, $tags, 'product_tag' );
            }

            $product_data = ['ID' => $post_id];

            wp_update_post($product_data);
        }

        echo '<div id="message" class="updated fade"><p>'
            .'Complete.' . '</p></div>';
    }


}

