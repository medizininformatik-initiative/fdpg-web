<?php
                /**
                 * The template for displaying all pages
                 *
                 */

                get_header();
                ?>

                <main id="main" class="site-main" role="main">

                  <?php
                  while ( have_posts() ) : the_post();

                    get_template_part( 'template-parts/content', 'page' );

                    // If page are open or we have at least one comment, load up the comment template.
                   

                  endwhile; 
                  ?>

                </main>

                <?php

                get_footer();