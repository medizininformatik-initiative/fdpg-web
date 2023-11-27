<?php
                        /**
                         * Functions and definitions
                         *
                         */

                        /*
                         * Let WordPress manage the document title.
                         */
                        add_theme_support( 'title-tag' );

                        /*
                         * Enable support for Post Thumbnails on posts and pages.
                         */
                        add_theme_support( 'post-thumbnails' );

						add_theme_support('menus');

                        /*
                         * Switch default core markup for search form, comment form, and comments
                         * to output valid HTML5.
                         */
                        add_theme_support( 'html5', array(
                          'search-form',
                          'comment-form',
                          'comment-list',
                          'gallery',
                          'caption',
                        ) );

                        /** 
                         * Include primary navigation menu
                         */
                        function htmlwp_nav_init() {
                          register_nav_menus( array(
                            'menu-header' => 'Header Menu',
                            'menu-footer' => 'Footer Menu',
                          ) );
                        }
                        add_action( 'init', 'htmlwp_nav_init' );

                        /**
                         * Register widget area.
                         *
                         */
                        function htmlwp_widgets_init() {
                          register_sidebar( array(
                            'name'          => 'Sidebar',
                            'id'            => 'sidebar-1',
                            'description'   => 'Add widgets',
                            'before_widget' => '<section id="%1" class="widget %2">',
                            'after_widget'  => '</section>',
                            'before_title'  => '<h2 class="widget-title">',
                            'after_title'   => '</h2>',
                          ) );
                        }
                        add_action( 'widgets_init', 'htmlwp_widgets_init' );

                        /**
                         * Enqueue scripts and styles.
                         */
                        function htmlwp_scripts() {
                          wp_enqueue_style( 'htmlwp-style', get_stylesheet_uri() );
                          
                        }
                        add_action( 'wp_enqueue_scripts', 'htmlwp_scripts' );

                        function htmlwp_create_post_custom_post() {
                          register_post_type('custom_post', 
                            array(
                            'labels' => array(
                              'name' => __('Custom Post', 'htmlwp'),
                            ),
                            'public'       => true,
                            'hierarchical' => true,
                            'supports'     => array(
                              'title',
                              'editor',
                              'excerpt',
                              'custom-fields',
                              'thumbnail',
                            ), 
                            'taxonomies'   => array(
                                'post_tag',
                                'category',
                            ) 
                          ));
                        }
                        add_action('init', 'htmlwp_create_post_custom_post'); // Add our work type

// Menü aus der Tabelle wp_posts abrufen
$menu_items = $wpdb->get_results("
    SELECT *
    FROM 114wp_posts
    WHERE post_type = 'nav_menu_item'
    ORDER BY menu_order ASC
");

// Assoziatives Array für die Menüelemente erstellen
$menu_items_by_id = array();
foreach ($menu_items as $item) {
    $menu_items_by_id[$item->ID] = $item;
}

function generate_menu_tree($menu_items, $parent_id = 0) {
    $menu_html = '<ul>';

    // Iteriere über die Menüelemente
    foreach ($menu_items as $menu_item) {
        if ($menu_item->post_parent == $parent_id) {
            // Beginne ein neues Menüelement
            $menu_html .= '<li class="menu-item menu-item-type-' . $menu_item->post_type . ' menu-item-object-' . $menu_item->post_type . '">';

            // Füge den Link hinzu
            $menu_html .= '<a href="' . esc_url(get_permalink($menu_item->ID)) . '">' . esc_html($menu_item->post_title) . '</a>';

            // Überprüfe, ob das Menüelement untergeordnete Elemente hat
            $has_children = false;
            foreach ($menu_items as $child_menu_item) {
                if ($child_menu_item->post_parent == $menu_item->ID) {
                    $has_children = true;
                    break;
                }
            }

            // Falls es untergeordnete Elemente gibt, rufe die Funktion rekursiv auf
            if ($has_children) {
                $menu_html .= generate_menu_tree($menu_items, $menu_item->ID);
            }

            // Schließe das Menüelement
            $menu_html .= '</li>';
        }
    }

    $menu_html .= '</ul>';

    return $menu_html;
}


