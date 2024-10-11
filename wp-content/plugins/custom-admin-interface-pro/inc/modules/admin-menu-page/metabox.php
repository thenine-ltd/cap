<?php
    
    /**
    * 
    *
    *
    * Output content of metabox
    */
    function admin_menu_page_metabox_content($post){

        //set no once
        wp_nonce_field( basename( __FILE__ ), 'admin_menu_page_metabox_nonce' );

        //get variables
        $post_id = $post->ID;

        //get existing values
        $menu_icon = get_post_meta($post_id, 'menu_icon', true);
        $display_title = get_post_meta($post_id, 'display_title', true);
        $remove_left_padding = get_post_meta($post_id, 'remove_left_padding', true);
        $menu_capability = get_post_meta($post_id, 'menu_capability', true);
        $page_content = html_entity_decode(stripslashes(get_post_meta($post_id, 'page_content', true)));

        // //enqueue styles and scripts
        wp_enqueue_script(array('common-script','admin-menu-page-script'));
        wp_enqueue_style(array('common-style','admin-menu-page-style'));


        //output code

        echo '<div class="custom-admin-interface-pro-wrapper">';

            echo custom_admin_interface_pro_settings_inline_notice('blue',__('This section enables you to create a custom menu page which will display in the main menu.','custom-admin-interface-pro'));

   



            echo '<label>'.__('Display Title','custom-admin-interface-pro').'</label>';
            
            echo '<label class="switch">';
                if(strlen($display_title)>0){
                    echo '<input type="checkbox" name="display_title" value="checked" checked>';
                } else {
                    echo '<input type="checkbox" name="display_title" value="checked">';
                }
                echo '<span class="slider"></span>';
            echo '</label>';


            echo '<label>'.__('Remove Left Padding','custom-admin-interface-pro').'</label>';
            
            echo '<label class="switch">';
                if(strlen($remove_left_padding)>0){
                    echo '<input type="checkbox" name="remove_left_padding" value="checked" checked>';
                } else {
                    echo '<input type="checkbox" name="remove_left_padding" value="checked">';
                }
                echo '<span class="slider"></span>';
            echo '</label>';

            
            //menu icon
            echo '<label>'.__('Menu Icon','custom-admin-interface-pro').'</label>';

            //display a preview of the menu icon
            if( filter_var($menu_icon, FILTER_VALIDATE_URL) ){
                //display image
                echo '<span class="select-icon svg-menu-icon" style="background-image: url('.$menu_icon.')"></span>';
            } else {
                echo '<span class="select-icon dashicons '.$menu_icon.'"></span>';
            }


            echo '<input id="menu_icon" type="text" name="menu_icon" value="'.esc_html($menu_icon).'">';




            $html = '<div data-icon-popup-title="'.__('Choose a Custom Icon','custom-admin-interface-pro').'" data-cancel-button="'.__('Cancel','custom-admin-interface-pro').'" id="icon_edit_html" style="display:none;">';

                //do dash icons
                $html .= '<div class="dash-icons">';

                    //do heading
                    $html .= '<h4>'.__('WordPress Dashicons','custom-admin-interface-pro').'</h4>';

                    //declare an array that holds all dashicon classes
                    $all_dash_icons = array('dashicons-menu', 'dashicons-admin-site', 'dashicons-dashboard', 'dashicons-admin-post', 'dashicons-admin-media', 'dashicons-admin-links', 'dashicons-admin-page', 'dashicons-admin-comments', 'dashicons-admin-appearance', 'dashicons-admin-plugins', 'dashicons-admin-users', 'dashicons-admin-tools', 'dashicons-admin-settings', 'dashicons-admin-network', 'dashicons-admin-home', 'dashicons-admin-generic', 'dashicons-admin-collapse', 'dashicons-filter', 'dashicons-admin-customizer', 'dashicons-admin-multisite', 'dashicons-welcome-write-blog', 'dashicons-welcome-add-page', 'dashicons-welcome-view-site', 'dashicons-welcome-widgets-menus', 'dashicons-welcome-comments', 'dashicons-welcome-learn-more', 'dashicons-format-aside', 'dashicons-format-image', 'dashicons-format-gallery', 'dashicons-format-video', 'dashicons-format-status', 'dashicons-format-quote', 'dashicons-format-chat', 'dashicons-format-audio', 'dashicons-camera', 'dashicons-images-alt', 'dashicons-images-alt2', 'dashicons-video-alt', 'dashicons-video-alt2', 'dashicons-video-alt3', 'dashicons-media-archive', 'dashicons-media-audio', 'dashicons-media-code', 'dashicons-media-default', 'dashicons-media-document', 'dashicons-media-interactive', 'dashicons-media-spreadsheet', 'dashicons-media-text', 'dashicons-media-video', 'dashicons-playlist-audio', 'dashicons-playlist-video', 'dashicons-controls-play', 'dashicons-controls-pause', 'dashicons-controls-forward', 'dashicons-controls-skipforward', 'dashicons-controls-back', 'dashicons-controls-skipback', 'dashicons-controls-repeat', 'dashicons-controls-volumeon', 'dashicons-controls-volumeoff', 'dashicons-image-crop', 'dashicons-image-rotate', 'dashicons-image-rotate-left', 'dashicons-image-rotate-right', 'dashicons-image-flip-vertical', 'dashicons-image-flip-horizontal', 'dashicons-image-filter', 'dashicons-undo', 'dashicons-redo', 'dashicons-editor-bold', 'dashicons-editor-italic', 'dashicons-editor-ul', 'dashicons-editor-ol', 'dashicons-editor-quote', 'dashicons-editor-alignleft', 'dashicons-editor-aligncenter', 'dashicons-editor-alignright', 'dashicons-editor-insertmore', 'dashicons-editor-spellcheck', 'dashicons-editor-expand', 'dashicons-editor-contract', 'dashicons-editor-kitchensink', 'dashicons-editor-underline', 'dashicons-editor-justify', 'dashicons-editor-textcolor', 'dashicons-editor-paste-word', 'dashicons-editor-paste-text', 'dashicons-editor-removeformatting', 'dashicons-editor-video', 'dashicons-editor-customchar', 'dashicons-editor-outdent', 'dashicons-editor-indent', 'dashicons-editor-help', 'dashicons-editor-strikethrough', 'dashicons-editor-unlink', 'dashicons-editor-rtl', 'dashicons-editor-break', 'dashicons-editor-code', 'dashicons-editor-paragraph', 'dashicons-editor-table', 'dashicons-align-left', 'dashicons-align-right', 'dashicons-align-center', 'dashicons-align-none', 'dashicons-lock', 'dashicons-unlock', 'dashicons-calendar', 'dashicons-calendar-alt', 'dashicons-visibility', 'dashicons-hidden', 'dashicons-post-status', 'dashicons-edit', 'dashicons-trash', 'dashicons-sticky', 'dashicons-external', 'dashicons-arrow-up', 'dashicons-arrow-down', 'dashicons-arrow-right', 'dashicons-arrow-left', 'dashicons-arrow-up-alt', 'dashicons-arrow-down-alt', 'dashicons-arrow-right-alt', 'dashicons-arrow-left-alt', 'dashicons-arrow-up-alt2', 'dashicons-arrow-down-alt2', 'dashicons-arrow-right-alt2', 'dashicons-arrow-left-alt2', 'dashicons-sort', 'dashicons-leftright', 'dashicons-randomize', 'dashicons-list-view', 'dashicons-exerpt-view', 'dashicons-grid-view', 'dashicons-move', 'dashicons-share', 'dashicons-share-alt', 'dashicons-share-alt2', 'dashicons-twitter', 'dashicons-rss', 'dashicons-email', 'dashicons-email-alt', 'dashicons-facebook', 'dashicons-facebook-alt', 'dashicons-googleplus', 'dashicons-networking', 'dashicons-hammer', 'dashicons-art', 'dashicons-migrate', 'dashicons-performance', 'dashicons-universal-access', 'dashicons-universal-access-alt', 'dashicons-tickets', 'dashicons-nametag', 'dashicons-clipboard', 'dashicons-heart', 'dashicons-megaphone', 'dashicons-schedule', 'dashicons-wordpress', 'dashicons-wordpress-alt', 'dashicons-pressthis', 'dashicons-update', 'dashicons-screenoptions', 'dashicons-info', 'dashicons-cart', 'dashicons-feedback', 'dashicons-cloud', 'dashicons-translation', 'dashicons-tag', 'dashicons-category', 'dashicons-archive', 'dashicons-tagcloud', 'dashicons-text', 'dashicons-yes', 'dashicons-no', 'dashicons-no-alt', 'dashicons-plus', 'dashicons-plus-alt', 'dashicons-minus', 'dashicons-dismiss', 'dashicons-marker', 'dashicons-star-filled', 'dashicons-star-half', 'dashicons-star-empty', 'dashicons-flag', 'dashicons-warning', 'dashicons-location', 'dashicons-location-alt', 'dashicons-vault', 'dashicons-shield', 'dashicons-shield-alt', 'dashicons-sos', 'dashicons-search', 'dashicons-slides', 'dashicons-analytics', 'dashicons-chart-pie', 'dashicons-chart-bar', 'dashicons-chart-line', 'dashicons-chart-area', 'dashicons-groups', 'dashicons-businessman', 'dashicons-id', 'dashicons-id-alt', 'dashicons-products', 'dashicons-awards', 'dashicons-forms', 'dashicons-testimonial', 'dashicons-portfolio', 'dashicons-book', 'dashicons-book-alt', 'dashicons-download', 'dashicons-upload', 'dashicons-backup', 'dashicons-clock', 'dashicons-lightbulb', 'dashicons-microphone', 'dashicons-desktop', 'dashicons-laptop', 'dashicons-tablet', 'dashicons-smartphone', 'dashicons-phone', 'dashicons-index-card', 'dashicons-carrot', 'dashicons-building', 'dashicons-store', 'dashicons-album', 'dashicons-palmtree', 'dashicons-tickets-alt', 'dashicons-money', 'dashicons-smiley', 'dashicons-thumbs-up', 'dashicons-thumbs-down', 'dashicons-layout', 'dashicons-paperclip');
                        
                    //for each dash icon print it out
                    foreach ($all_dash_icons as $icon){
                        $html .= '<span data="'.$icon.'" class="icon-for-selection dashicons '.$icon.'"></span>';       
                    }


                $html .= '</div>';

                //upload button
                $html .= '<div class="upload-icon">';
                    $html .= '<h4>'.__('Upload an Icon','custom-admin-interface-pro').'</h4>';
                    $html .= '<input type="button" name="upload-icon-button" id="upload-icon-button" class="button-secondary" value="'.__('Upload an Icon','custom-admin-interface-pro').'">';
                $html .= '</div>';


            $html .= '</div>';

            echo $html;









                
            //capability
            echo '<label>'.__('Menu Capability','custom-admin-interface-pro').'</label>';
            echo '<p>'.__('If you are not sure, we recommend selecting "manage_options" as only administrators have this capability.','custom-admin-interface-pro').'</p>';

            //options

            $capabilities = get_role( 'administrator' )->capabilities;

            ksort($capabilities);
            // var_dump($capabilities);

            echo '<select name="menu_capability">';
                foreach($capabilities as $capability => $value){
                    if($capability == $menu_capability){
                        echo '<option selected="selected" value="'.$capability.'">'.$capability.'</option>';
                    } else {
                        echo '<option value="'.$capability.'">'.$capability.'</option>';
                    }
                }

            echo '</select>';





            echo '<label>'.__('Page Content','custom-admin-interface-pro').'</label>';

            //do shortcodes
            echo custom_admin_interface_pro_shortcode_output();
                
            wp_editor( $page_content, 'page_content', $settings = array(
                'wpautop' => false,
                'textarea_name' => 'page_content',
                'drag_drop_upload' => true,
                'textarea_rows' => 30,
            ));         
            
        echo '</div>';

    }
    /**
    * 
    *
    *
    * Save the data in metabox
    */
    add_action( 'save_post', 'admin_menu_page_save_metabox', 10, 2 );
    function admin_menu_page_save_metabox( $post_id ){
	
        if ( !isset( $_POST['admin_menu_page_metabox_nonce'] ) || !wp_verify_nonce( $_POST['admin_menu_page_metabox_nonce'], basename( __FILE__ ) ) ){
            return;
        }
        
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
           return;
        }
        
        if ( ! current_user_can( 'edit_post', $post_id ) ){
            return;
        }

        //do settings
        
        if ( isset( $_REQUEST['menu_icon'] ) ) {
            update_post_meta( $post_id, 'menu_icon', sanitize_textarea_field($_POST['menu_icon']) );
        }

        if ( isset( $_REQUEST['page_content'] ) ) {
            update_post_meta( $post_id, 'page_content', $_POST['page_content'] );
        }


        if ( isset( $_REQUEST['display_title'] ) && strlen($_REQUEST['display_title'])>0) {
            update_post_meta( $post_id, 'display_title', sanitize_text_field($_POST['display_title']) );
        } else {
            delete_post_meta( $post_id, 'display_title');    
        }

        if ( isset( $_REQUEST['remove_left_padding'] ) && strlen($_REQUEST['remove_left_padding'])>0) {
            update_post_meta( $post_id, 'remove_left_padding', sanitize_text_field($_POST['remove_left_padding']) );
        } else {
            delete_post_meta( $post_id, 'remove_left_padding');    
        }

        if ( isset( $_REQUEST['menu_capability'] ) ) {
            update_post_meta( $post_id, 'menu_capability', sanitize_text_field($_POST['menu_capability']) );
        }
    
    }
    /**
    * 
    *
    *
    * Save the revision data
    */
    add_action( 'save_post', 'admin_menu_page_save_revision');
    function admin_menu_page_save_revision( $post_id ){

        $parent_id = wp_is_post_revision( $post_id );

        if ( $parent_id ) {

            $parent  = get_post( $parent_id );

            //field
            $menu_icon = get_post_meta( $parent->ID, 'menu_icon', true );

            if ( false !== $menu_icon ){
                add_metadata( 'post', $post_id, 'menu_icon', $menu_icon );
            }

            //field
            $page_content = get_post_meta( $parent->ID, 'page_content', true );

            if ( false !== $page_content ){
                add_metadata( 'post', $post_id, 'page_content', $page_content );
            }

            //field
            $display_title = get_post_meta( $parent->ID, 'display_title', true );

            if ( false !== $display_title ){
                add_metadata( 'post', $post_id, 'display_title', $display_title );
            }

            //field
            $remove_left_padding = get_post_meta( $parent->ID, 'remove_left_padding', true );

            if ( false !== $remove_left_padding ){
                add_metadata( 'post', $post_id, 'remove_left_padding', $remove_left_padding );
            }

            //field
            $menu_capability = get_post_meta( $parent->ID, 'menu_capability', true );

            if ( false !== $menu_capability ){
                add_metadata( 'post', $post_id, 'menu_capability', $menu_capability );
            }
                

        }
        
    }
    /**
    * 
    *
    *
    * Restore the revision
    */
    add_action( 'wp_restore_post_revision', 'admin_menu_page_restore_revision', 10, 2 );
    function admin_menu_page_restore_revision( $post_id, $revision_id ) {

        $post     = get_post( $post_id );
        $revision = get_post( $revision_id );
        
        //field
        $menu_icon  = get_metadata( 'post', $revision->ID, 'menu_icon', true );
    
        if ( false !== $menu_icon ){
            update_post_meta( $post_id, 'menu_icon', $menu_icon );
        } else {
            delete_post_meta( $post_id, 'menu_icon' );
        }

        //field
        $page_content  = get_metadata( 'post', $revision->ID, 'page_content', true );
    
        if ( false !== $page_content ){
            update_post_meta( $post_id, 'page_content', $page_content );
        } else {
            delete_post_meta( $post_id, 'page_content' );
        }

        //field
        $display_title  = get_metadata( 'post', $revision->ID, 'display_title', true );
    
        if ( false !== $display_title ){
            update_post_meta( $post_id, 'display_title', $display_title );
        } else {
            delete_post_meta( $post_id, 'display_title' );
        }

        //field
        $remove_left_padding  = get_metadata( 'post', $revision->ID, 'remove_left_padding', true );
    
        if ( false !== $remove_left_padding ){
            update_post_meta( $post_id, 'remove_left_padding', $remove_left_padding );
        } else {
            delete_post_meta( $post_id, 'remove_left_padding' );
        }

        //field
        $menu_capability  = get_metadata( 'post', $revision->ID, 'menu_capability', true );
    
        if ( false !== $menu_capability ){
            update_post_meta( $post_id, 'menu_capability', $menu_capability );
        } else {
            delete_post_meta( $post_id, 'menu_capability' );
        }
    
    }
?>