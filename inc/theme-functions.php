<?php 
/**
 * This file contained a set of functions for this theme.
 *
 * @author Juyal Ahmed<tojibon@gmail.com>
 * @version: 1.0.0
 * https://themeredesign.com
 **/


function umamah_get_option($prefix, $field, $default = '') {
    if ( !class_exists('TitanFramework') ) return false;

    $titan = TitanFramework::getInstance( 'umamah' );
    $return = $titan->getOption( $prefix . '_' . $field );
    if ( is_bool($return) ) return $return;
    return $return ? $return : $default;
}

/**
 *
 * Getting logo image from theme option
 *
 * @Author       : Jewel Ahmed
 * @Author Web   : http://codeatomic.com
 *
 * @return str URL of logo image
 **/
if ( !function_exists( 'umamah_get_logo_image' ) ) {
    function umamah_get_logo_image() {
        global $post;
        $logo_image = umamah_get_option( 'general', 'logo_image' );	 
        $logo_image_featured = umamah_get_option( 'general', 'logo_image_featured' );	 
		if ( !empty( $logo_image_featured ) ) {
            if ( is_front_page() && is_home() ) {
              // Default homepage
            } elseif ( is_front_page() ) {
              // static homepage
            } elseif ( 
                is_home() || 
                ( !empty( $post ) && is_single() && 'post' == get_post_type( $post ) ) ||
                is_category() || is_tag() || is_tax( 'post_format' ) || is_author()
            ) {
                // blog page
                $logo_image = $logo_image_featured;
            } else {
              //everything else
            }            
		}
		return $logo_image;
    }
}

/**
 *
 * Getting post featured image
 *
 * @Author       : Jewel Ahmed
 * @Author Web   : http://codeatomic.com
 *
 * @return str URL of post featured image
 **/
if ( !function_exists( 'umamah_get_header_featured_image' ) ) {
    function umamah_get_header_featured_image() {
        
        $blog_featured_image = umamah_get_option('blog', 'blog_featured_image', get_template_directory_uri() . '/resources/images/blog-header.jpg');
        
		return $blog_featured_image;
    }
}

/**
 *
 * Getting page / post title
 *
 * @Author       : Jewel Ahmed
 * @Author Web   : http://codeatomic.com
 *
 * @return str
 **/
if ( !function_exists( 'umamah_get_header_post_title' ) ) {
    function umamah_get_header_post_title() {
        $header_post_title = umamah_get_option('blog', 'blog_index_heading', __( 'Reach the high with memorable story', TRTHEME_LANG_DOMAIN ));
		return $header_post_title;
    }
}

/**
 *
 * Adding Open Graph Content.
 **/
if ( !function_exists( 'umamah_add_opengraph' ) ) {
    function umamah_add_opengraph() {
        global $post; 
        if ( empty( $post ) ) {
            return "";
        }
        
        echo "\n<!-- " . get_bloginfo( 'name' ) . " Open Graph Tags -->\n";
        
        echo "<meta property='og:site_name' content='". get_bloginfo( 'name' ) ."'/>\n"; 
        echo "<meta property='og:url' content='" . get_permalink() . "'/>\n"; 

        if ( is_singular() ) { 
            if ( !empty( $post ) && is_single() && 'themes' == get_post_type( $post ) ) {
                // theme single
                $_data_sub_title = $_header_sub_title = get_post_meta( $post->ID, 'themes-general_theme_sub_heading', TRUE );
                $post_title = get_the_title() . ' - ' . $_data_sub_title;
            } else if ( !empty( $post ) && is_single() && 'plugins' == get_post_type( $post ) ) {
                // theme single
                $_data_sub_title = $_header_sub_title = get_post_meta( $post->ID, 'plugins-general_plugin_sub_heading', TRUE );
                $post_title = get_the_title() . ' - ' . $_data_sub_title;
            } else {
                $post_title = get_the_title();
            }
            echo "<meta property='og:title' content='" . $post_title . "'/>\n"; 
            echo "<meta property='og:type' content='article'/>\n"; 
            
            $content = ( !empty( $post->post_excerpt ) ) ?
                            wp_trim_words( strip_shortcodes( $post->post_excerpt ), 30 ) :
                                wp_trim_words( strip_shortcodes( $post->post_content ), 30 ); 
            if ( empty( $content ) ) {
                $content = "Visit the post for more.";
            }
            echo "<meta property='og:description' content='" . $content . "' />\n";
        } elseif( is_front_page() or is_home() ) { 
            echo "<meta property='og:title' content='" . get_bloginfo( "name" ) . "'/>\n"; 
            echo "<meta property='og:type' content='website'/>\n"; 
        }

        if( has_post_thumbnail( $post->ID ) ) { 
            $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
            echo "<meta property='og:image' content='" . esc_attr( $thumbnail[0] ) . "'/>\n";
        }
        echo "\n";
    }
}
if ( !defined('WPSEO_VERSION') && !class_exists('NY_OG_Admin')) {
    add_action( 'wp_head', 'umamah_add_opengraph', 5 );
}


/**
 *
 * Adding favicon on theme head and admin head.
 *
 * @Author       : Jewel Ahmed
 * @Author Web   : http://codeatomic.com
 *
 * @return str
 **/
if ( !function_exists( 'umamah_favicon' ) ) {
    function umamah_favicon() {
        $custom_favicon = umamah_get_option( 'others', 'favicon_url', '' );      
        ?>
        <!-- FavIcon -->
        <?php 
        $custom_favicon = umamah_get_option( 'others', 'favicon_url', '' );  
        if ( !empty( $custom_favicon ) ) { ?>
			<link rel="shortcut icon" href="<?php echo $custom_favicon; ?>" type="image/x-icon">
			<link rel="icon" href="<?php echo $custom_favicon; ?>" type="image/x-icon">
        <?php } else if ( file_exists( get_template_directory() . '/favicon.gif' ) ) { ?>
            <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.gif" type="image/x-icon">
            <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.gif" type="image/x-icon">
        <?php } else if ( file_exists( get_template_directory() . '/favicon.ico' ) ) { ?>
            <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-icon">
            <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-icon">
        <?php }
    }
}
add_action( 'wp_head', 'umamah_favicon' ); 
add_action( 'admin_head', 'umamah_favicon' );
 
 
/**
 *
 * Adding typography css which is changable by theme settings.
 *
 * @Author       : Jewel Ahmed
 * @Author Web   : http://codeatomic.com
 *
 * @return str
 **/
if ( !function_exists( 'umamah_theme_options_custom_css' ) ) {
	function umamah_theme_options_custom_css(){
		global $post;
		$post_id = get_the_ID();
		
		$theme_typography_styles = '';
		wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/assets/css/custom_css.css' );

		/*
		-----------------------------------------------------------------------------------
			Global custom CSS from Theme Option others tab.
		-----------------------------------------------------------------------------------
		*/
		$additional_css = umamah_get_option( 'others', 'custom_css', '' );
		if ( $additional_css ) {
			$theme_typography_styles .= $additional_css;
		}
		
		/*
		-----------------------------------------------------------------------------------
			Page wise custom CSS from Page Options Others meta settings.
		-----------------------------------------------------------------------------------
		*/
		$_content_background_color = get_post_meta( $post_id, 'page-other_content_background_color', TRUE );
		$_content_text_color = get_post_meta( $post_id, 'page-other_content_text_color', TRUE );
		$_content_padding_top = get_post_meta( $post_id, 'page-other_content_padding_top', TRUE );
		$_content_padding_bottom = get_post_meta( $post_id, 'page-other_content_padding_bottom', TRUE );
		if ( !empty( $_content_background_color ) ) {
			$theme_typography_styles .= "
				#content-body {
					background: {$_content_background_color};
				}
			";
		}
		if ( !empty( $_content_text_color ) ) {
			$theme_typography_styles .= "
				#content-body {
					color: {$_content_text_color};
				}
			";
		}
		if ( !empty( $_content_padding_top ) || ( $_content_padding_top == "0" ) ) {
			$theme_typography_styles .= "
				.post-{$post_id} .entry-content {
					padding-top: {$_content_padding_top}px;
				}
			";
		}
		if ( !empty( $_content_padding_bottom ) || ( $_content_padding_bottom == "0" ) ) {
			$theme_typography_styles .= "
				.post-{$post_id} .entry-content {
					padding-bottom: {$_content_padding_bottom}px;
				}
			";
		}
		
		$page_additional_css = get_post_meta( $post_id, 'page-other_custom_css', TRUE );
		if ( $page_additional_css ) {
			$theme_typography_styles .= $page_additional_css;
		}	
		
		
		/*
		-----------------------------------------------------------------------------------
			WP Editor Shortcode Generator generated internal css
		-----------------------------------------------------------------------------------
		*/
		if ( !empty( $post ) ) {
		
			$content = $post->post_content;
			if ( !empty( $content ) ) {
			
				$css_list = explode( 'css="#umamah', $content );
				array_shift( $css_list );
				foreach( $css_list as $k => $v ) {
					$str = '#umamah'. $v;
					$css = '';
					$css = umamah_get_value_by_tag_name( $str, '#umamah', '}' );
					if ( !empty( $css ) ) {
						$theme_typography_styles .= '#umamah'. $css . '}';
					}
				}
				
			}
		}
		
		/*
		-----------------------------------------------------------------------------------
			Finally a filter call for other useful customization of generated CSS styles.
		-----------------------------------------------------------------------------------
		*/
		$theme_typography_styles = apply_filters( 'theme_shortcode_typography_styles', $theme_typography_styles );    
		if ( $theme_typography_styles ){
			wp_add_inline_style( 'custom-style', $theme_typography_styles );
		} 
	} 
} 
add_action( 'wp_enqueue_scripts', 'umamah_theme_options_custom_css', 21 );


/**
 *
 * Show analytics code in footer.
 *
 * @Author       : Jewel Ahmed
 * @Author Web   : http://codeatomic.com
 *
 * @return str
 **/
if ( !function_exists( 'umamah_footer_analytics' ) ) {
    function umamah_footer_analytics() {
        $google_analytics = umamah_get_option( 'others', 'google_analytics', '' );      
        if ( $google_analytics <> '' ) {
            echo stripslashes( $google_analytics ) . "\n";
        }
    }
}
add_action( 'wp_footer', 'umamah_footer_analytics' );



/**
 *
 * Return the site global notice from theme settings.
 *
 * @Author       : Jewel Ahmed
 * @Author Web   : http://codeatomic.com
 *
 * @return str
 **/
if ( !function_exists( 'umamah_front_notice' ) ) {
	function umamah_front_notice() {
		$notice_html = umamah_get_option( 'others_', 'notice_html', '' );
		ob_start();
		if ( !empty( $notice_html ) ) {
		?>
			<div id="umamah-notice-bar" class="umamah-notice">
				<div id="umamah-notice-message" class="umamah-notice-message">
                    <div class="umamah-notice-message-inner">
                        <?php echo $notice_html; ?>
                    </div>
				</div>
				<a href="#" class="umamah-notice-btn"><i class="fa fa-angle-up"></i></a>
			</div>
		<?php 	
		}
		$buffer = ob_get_contents();
		ob_end_clean();
		$buffer = apply_filters( 'umamah_front_notice_filter', $buffer );
		
		echo $buffer;
	}
}

/**
 *
 * Umamah URLs to generate page settings specific urls which all are using in theme different page contents.
 *
 * @Author       : Jewel Ahmed
 * @Author Web   : http://codeatomic.com
 *
 * @param str $page - (optional) page slug.
 * @param bool $echo_a - (optional) if true the generated link will echo.
 * @param array $additional_args - (optional) query arguments ass array to make url query string.
 *
 * @return str
 **/
if ( !function_exists( 'umamah_url' ) ) {
	function umamah_url( $page = '', $echo_a = false, $additional_args = array() ) {
	
		if ( is_multisite() )
			$site_name = $GLOBALS['current_site']->site_name;
		else
			/*
			 * The blogname option is escaped with esc_html on the way into the database
			 * in sanitize_option we want to reverse this for the plain text arena of emails.
			 */
			$site_name = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
		
		
		$url_str = $title = '';
		$url = home_url();
		
		if ( empty( $page ) ) {
			$title = __( 'Home', TRTHEME_LANG_DOMAIN );
		} else if ( !empty( $page ) && $page == 'login' ) {
			$title = __( 'Login', TRTHEME_LANG_DOMAIN );
			$url_page_id = umamah_get_option( 'pages', 'login', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'login' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url = get_page_link( $url_page_id, false, false );
			}			
		} else if ( !empty( $page ) && $page == 'logout' ) {
			$url = add_query_arg( 'umamah-action', 'logout', get_permalink() );
			$title = __( 'Log Out', TRTHEME_LANG_DOMAIN );
		} else if ( !empty( $page ) && $page == 'account' ) {
			$title = __( 'Account', TRTHEME_LANG_DOMAIN );
			$url_page_id = umamah_get_option( 'pages', 'account', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'account' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url = get_page_link( $url_page_id, false, false );
			}		
		} else if ( !empty( $page ) && $page == 'reset-password-checkemail' ) {
			$umamah_login_uri = umamah_url( 'login' );
			$url = add_query_arg( 'checkemail', 'confirm', $umamah_login_uri );
			$title = ''; //Forcing it not to generate the anchor with text and url.
		} else if ( !empty( $page ) && $page == 'forgot-password' ) {
			$title = __( 'Lost your password?', TRTHEME_LANG_DOMAIN );
			$url_page_id = umamah_get_option( 'pages', 'forgot_password', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'forgot-password' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url = get_page_link( $url_page_id, false, false );
			}		
		} else if ( !empty( $page ) && $page == 'login-redirect' ) {
			$title = '';
			$url_page_id = umamah_get_option( 'pages', 'login_redirect', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'account' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url = get_page_link( $url_page_id, false, false );
			}		
		} else if ( !empty( $page ) && $page == 'reset-password' ) {
			$title = __( 'Reset Password', TRTHEME_LANG_DOMAIN );
			$url_page_id = umamah_get_option( 'pages', 'reset_password', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'reset-password' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url = get_page_link( $url_page_id, false, false );
			}		
		} else if ( !empty( $page ) && $page == 'registration' ) {
			$title = __( 'Register here', TRTHEME_LANG_DOMAIN );
			$url_page_id = umamah_get_option( 'pages', 'registration', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'registration' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url = get_page_link( $url_page_id, false, false );
			}		
		} else if ( !empty( $page ) && $page == 'checkout' ) {
			$title = __( 'Checkout', TRTHEME_LANG_DOMAIN );
			$url_page_id = umamah_get_option( 'pages', 'checkout', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'checkout' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url = get_page_link( $url_page_id, false, false );
			}		
		} else if ( !empty( $page ) && $page == 'payment-success' ) {
			$title = __( 'Payment Success', TRTHEME_LANG_DOMAIN );
			$url_page_id = umamah_get_option( 'payment', 'success', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'payment-success' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url = get_page_link( $url_page_id, false, false );
			} else {
				$url = '';
			}		
		} else if ( !empty( $page ) && $page == 'payment-cancel' ) {
			$title = __( 'Payment Cancel', TRTHEME_LANG_DOMAIN );
			$url_page_id = umamah_get_option( 'payment', 'cancel', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'payment-cancel' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url = get_page_link( $url_page_id, false, false );
			} else {
				$url = '';
			}		
		} else if ( !empty( $page ) && $page == 'add-to-cart' ) {
			$url = get_permalink();
			$title = '';
			$url_page_id = umamah_get_option( 'pages', 'checkout', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'checkout' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				} 
			}		
		} else if ( !empty( $page ) && $page == 'themes' ) {
			$url = get_permalink();
			$title = '';
			$url_page_id = umamah_get_option( 'themes', 'theme_listing_page1', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'themes' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				} 
			}		
		} else if ( !empty( $page ) && $page == 'plugins' ) {
			$url = get_permalink();
			$title = '';
			$url_page_id = umamah_get_option( 'plugin', 'plugin_listing_page1', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'plugins' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				} 
			}		
		} else if ( !empty( $page ) && $page == 'remove-cart-item' ) {
			$url = get_permalink();
			$title = __('Remove', TRTHEME_LANG_DOMAIN);
			$url_page_id = umamah_get_option( 'pages', 'checkout', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'checkout' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				} 
			}		
		} else if ( !empty( $page ) && $page == 'articles' ) {
			$url = get_permalink();
			$title = __('Knowledgebase', TRTHEME_LANG_DOMAIN);
			$url_page_id = umamah_get_option( 'pages', 'knowledgebase_page_id', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'knowledgebase' );
				$articles_obj = get_page_by_path( 'articles' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				} else if ( !empty( $articles_obj ) && !empty( $articles_obj->ID ) ) {
					$url_page_id = $articles_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				} 
			}		
		} else if ( !empty( $page ) && $page == 'tickets' ) {
			$url = get_permalink();
			$title = __('Tickets', TRTHEME_LANG_DOMAIN);
			$url_page_id = umamah_get_option( 'pages', 'ticket_page_id', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'tickets' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				} 
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				} 
			}		
		} else if ( !empty( $page ) && $page == 'submit-a-ticket' ) {
			$url = get_permalink();
			$title = __('Submit A Ticket', TRTHEME_LANG_DOMAIN);
			$url_page_id = umamah_get_option( 'pages', 'submit_ticket_page_id', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'submit-a-ticket' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				} 
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				} 
			}		
		} else if ( !empty( $page ) && $page == 'edit-profile' ) {
			$url = get_permalink();
			$title = __('Edit Profile', TRTHEME_LANG_DOMAIN);
			$url_page_id = umamah_get_option( 'pages', 'edit_profile', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'edit-profile' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				} 
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				} 
			}		
		} else if ( !empty( $page ) && $page == 'download' ) {
			$url = get_permalink();
			$title = __('Download', TRTHEME_LANG_DOMAIN);
			$url_page_id = umamah_get_option( 'pages', 'account_download', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'download' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				} 
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				} 
			}		
		} else if ( !empty( $page ) && $page == 'change-password' ) {
			$url = get_permalink();
			$title = __('Change Password', TRTHEME_LANG_DOMAIN);
			$url_page_id = umamah_get_option( 'pages', 'account_change_password', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'change-password' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				} 
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				} 
			}		
		} else if ( !empty( $page ) && $page == 'download' ) {
			$url = get_permalink();
			$title = __('Download', TRTHEME_LANG_DOMAIN);
			$url_page_id = umamah_get_option( 'pages', 'download', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'download' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				} 
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				} 
			}		
		} else if ( !empty( $page ) && $page == 'subscription' ) {
			$url = get_permalink();
			$title = __('Subscription', TRTHEME_LANG_DOMAIN);
			$url_page_id = umamah_get_option( 'pages', 'account_subscription', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'subscription' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				} 
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				} 
			}		
		} else if ( !empty( $page ) && $page == 'theme-customization' ) {
			$url = get_permalink();
			$title = __('Theme Customization', TRTHEME_LANG_DOMAIN);
			$url_page_id = umamah_get_option( 'pages', 'theme_customization', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'theme-customization' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				} 
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				} 
			}		
		} else if ( !empty( $page ) && ($page == 'terms-and-condition' || $page == 'toc') ) {
			$url = get_permalink();
			$title = __('Terms and Condition', TRTHEME_LANG_DOMAIN);
			$url_page_id = umamah_get_option( 'pages', 'toc', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'terms-and-condition' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				} 
			}
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'toc' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				} 
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				} 
			}		
		} else if ( !empty( $page ) && ($page == 'terms-of-service' || $page == 'tos') ) {
			$url = get_permalink();
			$title = __('Terms of Service', TRTHEME_LANG_DOMAIN);
			$url_page_id = umamah_get_option( 'pages', 'tos', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'terms-and-condition' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'tos' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				}
			}
		} else if ( !empty( $page ) && $page == 'contact' ) {
			$url = get_permalink();
			$title = __('Contact', TRTHEME_LANG_DOMAIN);
			$url_page_id = umamah_get_option( 'pages', 'contact', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'contact' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				} 
			}
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'contact-us' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				} 
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				} 
			}		
		} else if ( !empty( $page ) && $page == 'payment-success' ) {
			$url = get_permalink();
			$title = __('Payment Success', TRTHEME_LANG_DOMAIN);
			$url_page_id = umamah_get_option( 'payment', 'success', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'payment_success' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				}
			}
		} else if ( !empty( $page ) && $page == 'payment-cancel' ) {
			$url = get_permalink();
			$title = __('Payment Cancel', TRTHEME_LANG_DOMAIN);
			$url_page_id = umamah_get_option( 'payment', 'cancel', 0 );
			if ( empty( $url_page_id ) ) {
				$url_obj = get_page_by_path( 'payment_cancel' );
				if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
					$url_page_id = $url_obj->ID;
				}
			}
			if ( !empty( $url_page_id ) ) {
				$url_tmp = get_page_link( $url_page_id, false, false );
				if ( !empty( $url_tmp ) ) {
					$url = $url_tmp;
				}
			}
        } else if ( !empty( $page ) && $page == 'email-payment-success' ) {
            $url = get_permalink();
            $title = __('Payment Success', TRTHEME_LANG_DOMAIN);
            $url_page_id = umamah_get_option( 'payment', 'email_order_success', 0 );
            if ( empty( $url_page_id ) ) {
                $url_obj = get_page_by_path( 'email-payment-success' );
                if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
                    $url_page_id = $url_obj->ID;
                }
            }
            if ( !empty( $url_page_id ) ) {
                $url_tmp = get_page_link( $url_page_id, false, false );
                if ( !empty( $url_tmp ) ) {
                    $url = $url_tmp;
                }
            }
        } else if ( !empty( $page ) && $page == 'support-tickets' ) {
            $url = get_permalink();
            $title = __('Support Tickets', TRTHEME_LANG_DOMAIN);
            $url_page_id = umamah_get_option( 'pages', 'support_tickets', 0 );
            if ( empty( $url_page_id ) ) {
                $url_obj = get_page_by_path( 'support-tickets' );
                if ( !empty( $url_obj ) && !empty( $url_obj->ID ) ) {
                    $url_page_id = $url_obj->ID;
                }
            }
            if ( !empty( $url_page_id ) ) {
                $url_tmp = get_page_link( $url_page_id, false, false );
                if ( !empty( $url_tmp ) ) {
                    $url = $url_tmp;
                }
            }
        } else {
			$url = get_permalink();
			$title = get_the_title();
		}
		
		if ( !empty( $additional_args ) ) {
			foreach( $additional_args as $k => $v ) {
				$url = add_query_arg( $k, $v, $url );
			}
		}
		
		
		if ( $echo_a && !empty( $title ) ) {
			$url_str = '<a href="' . $url . '">' . $title . '</a>';
		} else {
			$url_str = $url;
		}
		return $url_str;
	}
}

/**
 *
 * Contact email submission processing.
 **/
if ( !function_exists( 'send_contact_request' ) ) {
	function send_contact_request() {
		if ( !session_id() ) {
			session_start();
		}
		
		global $contact_error_text, $contact_has_error, $contact_email_sent, $contact_captcha_placeholder;
		$contact_error_text = $contact_has_error = false;
		$contact_captcha_placeholder = 'What is 4 + 6?';
		
		if( isset( $_POST[ 'contact_submitted' ] ) ) {
    
			if( trim( $_POST[ 'contact_name' ] ) === '' ) {
				$contact_error_text = __( 'Name field can not be empty.', TRTHEME_LANG_DOMAIN );
				$contact_has_error = true;
			} else {
				$contact_name = trim( strip_tags( $_POST[ 'contact_name' ] ) );
			}
				
			if ( !$contact_has_error ) {		
				if( trim( $_POST[ 'contact_email' ] ) === '' )  {
					$contact_error_text = __( 'Please enter your valid email address.', TRTHEME_LANG_DOMAIN );
					$contact_has_error = true;
				} else if( !preg_match( "/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", trim( $_POST[ 'contact_email' ] ) ) ) {
					$contact_error_text = __( 'You entered an invalid email address.', TRTHEME_LANG_DOMAIN );
					$contact_has_error = true;
				} else {
					$contact_email = trim( strip_tags( $_POST[ 'contact_email' ] ) );
				}
			}
				
			if ( !$contact_has_error ) {			
				if( trim( $_POST[ 'contact_comments' ] ) === '' ) {
					$contact_error_text = __( 'Message can not be blank, Please enter a message.', TRTHEME_LANG_DOMAIN );
					$contact_has_error = true;
				} else {
					$contact_comments = trim( strip_tags( $_POST[ 'contact_comments' ] ) );
				}
			}
			
			if ( !$contact_has_error ) {	
				if( trim( $_POST[ 'contact_captcha' ] ) === '' || empty( $_SESSION['umamah_contact_captcha'] ) || $_SESSION['umamah_contact_captcha'] != $_POST[ 'contact_captcha' ] ) {
					$contact_error_text = __( 'Invalid captcha answer.', TRTHEME_LANG_DOMAIN );
					$contact_has_error = true;
				} 
			}
					
			if( !$contact_has_error ) {
				$contact_subject = stripslashes( trim( $_POST[ 'contact_subject' ] ) );   
				$admin_email = get_option( 'admin_email' );
				
				$subject = '[Contact Request] From ' . $contact_name;
				$body = "Name: $contact_name \n\nEmail: $contact_email \n\nSubject: $contact_subject \n\nComments: $contact_comments";
				$headers = 'From: ' . $contact_name . ' <' . $admin_email . '>' . "\r\n" . 'Reply-To: ' . $contact_email;
				
				@mail( $admin_email, $subject, $body, $headers );
				$contact_email_sent = true;
			}
			
		}
		
		$val1 = rand( 1, 10 );
		$val2 = rand( 1, 10 );
		$_SESSION['umamah_contact_captcha'] = $val1 + $val2;
		$contact_captcha_placeholder = "What is $val1 + $val2?";
		
	}
}
add_action( 'init', 'send_contact_request', 1 );