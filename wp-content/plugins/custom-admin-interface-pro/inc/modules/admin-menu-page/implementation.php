<?php

    // /**
    // * 
    // *
    // *
    // * Dashboard content
    // */
    // function admin_menu_page_output( $var, $args ) {

    //     //replace shortcodes
    //     $output = custom_admin_interface_pro_shortcode_replacer($args['args']);

    //     $output = apply_filters('custom_admin_interface_pro_admin_menu_page_content', $output);

    //     echo $output; 
    // }
    /**
    * 
    *
    *
    * Enable shortcodes to run propertly
    */
    add_filter( 'custom_admin_interface_pro_admin_menu_page_content', 'do_shortcode' );




    // function my_custom_admin_inline_styles() {

    //     if (class_exists('\Elementor\Plugin')) {


    //         $kit_id = get_option('elementor_active_kit');
    //         $css_file = \Elementor\Core\Files\CSS\Post::create($kit_id);

    //         $css_path = $css_file->get_path();
    //         $css_contents = file_get_contents($css_path);

    //         // Define your raw CSS string
    //         $custom_css = '
    //             <style>
    //                 '.$css_contents.'
    //             </style>
    //         ';
        
    //         // Output the inline CSS
    //         echo $custom_css;
    //     }
    // }
    // add_action('admin_head', 'my_custom_admin_inline_styles');



    /**
    * 
    *
    *
    * Fix script issue with wordpress 5.9 and above
    */
    add_action( 'admin_enqueue_scripts', 'admin_menu_page_register_scripts_and_styles');
    function admin_menu_page_register_scripts_and_styles() {
        if (class_exists('\Elementor\Plugin')) {



            // if (class_exists('\Elementor\Plugin')) {

            //     wp_enqueue_style('elementor-kit-css', plugins_url( '../../../inc/dummy.css', __FILE__ ));

                
            //     $kit_id = get_option('elementor_active_kit');
            //     $css_file = \Elementor\Core\Files\CSS\Post::create($kit_id);

            //     $css_path = $css_file->get_path();
            //     $css_contents = file_get_contents($css_path);

            //     // Add the inline CSS
            //     wp_add_inline_style('elementor-kit-css', $css_contents);


            // }



            \Elementor\Plugin::$instance->frontend->enqueue_styles();
            \Elementor\Plugin::$instance->frontend->enqueue_scripts();


            // wp_enqueue_style( 'elementor-global' );

            // $kit_id = get_option('elementor_active_kit');

            // if ($kit_id) {
            //     // Load the global kit CSS
            //     \Elementor\Plugin::$instance->frontend->enqueue_styles();
            //     \Elementor\Core\Files\CSS\Post::create($kit_id)->enqueue();
            // }

            // Load Elementor global styles, which include global colors
            // \Elementor\Plugin::$instance->frontend->enqueue_default_styles();
            
        }
    }



    /**
    * 
    *
    *
    * Function to add new dashboard widget
    */
    add_action( 'admin_menu', 'admin_menu_page_implementation' );
    function admin_menu_page_implementation() {

        //we need to get all published posts and loop through them
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'posts';
        
        //just get all data
        $query = "SELECT * FROM $table_name WHERE post_type='admin_menu_page' AND post_status='publish'";
        
        $posts = $wpdb->get_results($query);




        if($posts){
            foreach($posts as $post){

                $post_id = $post->ID;
                
                //check if the code needs to be executed
                if(custom_admin_interface_pro_exception_check($post_id)){

                    // $widget_title = get_post_meta($post_id, 'widget_title', true);
                    $page_content = get_post_meta($post_id, 'page_content', true);
                    $page_content = custom_admin_interface_pro_shortcode_replacer($page_content);
                    $page_content = apply_filters('custom_admin_interface_pro_admin_menu_page_content', $page_content, $post_id);

                    $page_title = get_the_title($post_id);

                    $menu_capability = get_post_meta($post_id, 'menu_capability', true);
                    $menu_icon = get_post_meta($post_id, 'menu_icon', true);


                    //display the title?
                    $display_title = get_post_meta($post_id, 'display_title', true);
                    
                    if($display_title == 'checked'){
                        $page_content = '<h1>'.$page_title.'</h1><br>'.$page_content;
                    }

                    //remove left padding?
                    $remove_left_padding = get_post_meta($post_id, 'remove_left_padding', true);
                    
                    if($remove_left_padding == 'checked'){
                        $page_content = '<style>#wpcontent {padding-left: 0px !important;}</style>'.$page_content;
                    }

                    


                    //we need to slugify the page title
                    $page_slug = str_replace(' ','-',$page_title);
                    $page_slug = strtolower($page_slug);


                    add_menu_page(
                        $page_title, // Page title
                        $page_title,                           // Menu title
                        $menu_capability,                        // Capability required to access the menu
                        $page_slug,                      // Menu slug
                        function() use($page_content){
                            echo $page_content;
                        },              // Function to display the page content
                        $menu_icon,               // Icon (optional)
                        // 6                                        // Position in the menu (optional)
                    );                    
            
                } //end exception check
            } //end foreach post
        } //end post check

    }
    
?>