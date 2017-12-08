<?php
/**
 *
 * Theme default header.
 *
 * @author Juyal Ahmed<tojibon@gmail.com>
 * @version: 1.0.0
 * https://themeredesign.com
 */

global $current_user;

$logo_image = '';
?>
<div id="header-outer">
    <header id="top">
        <div class="container container-wide">
            <div class="row">
                <div class="col-md-12">
                    <div class="logo-wrapper pull-left">
                        <div class="logo-wrapper-inner">
                            <a id="logo" href="<?php echo home_url(); ?>">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.jpg" alt="Logo" />
                            </a>
                        </div>
                    </div>

                    <div class="pull-right">
                        <div class="primary-menu-wrapper secondary-menu-exists-<?php echo $secondary_nav_using; ?>">
                            <nav class="primary-menu">
                                <ul class="sf-menu">
                                    <?php
                                    if( has_nav_menu( 'primary_menu' ) ) {
                                        wp_nav_menu( array('walker' => new Umamah_Arrow_Walker_Nav_Menu, 'theme_location' => 'primary_menu', 'container' => '', 'items_wrap' => '%3$s' ) );
                                    } else {
                                        echo '<li><a href="">No menu assigned!</a></li>';
                                    }
                                    ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>