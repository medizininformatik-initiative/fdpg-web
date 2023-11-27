<?php
/*
 * Template Name: Template Register
*/

get_header();
$upload_dir = wp_upload_dir();
$upload_path = $upload_dir['baseurl'];
?>
<!-- header end
=========================================== -->
<main class="main">
    <!-- info start
  =========================================== -->
    <section class="info">
        <div class="auto__container">
            <div class="info__inner">
                <div class="info__inner-title">
                    <h2>Projektregister</h2>
                    <p>
                        Im Projektregister des Deutschen Forschungsdatenportals f&uuml;r
                        Gesundheit k&ouml;nnen alle im Rahmen der
                        Medizininformatik-Initiative (MII) beantragten, laufenden und
                        abgeschlossenen Forschungsprojekte gefunden werden.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- info end
  =========================================== -->
    <!-- projects start
  =========================================== -->
    <section class="projects big">
        <div class="auto__container">
            <div class="projects__inner"><!--
            <button type="button" id="filterBtn">
                <img src="<?php echo esc_url(get_template_directory_uri() . "/images/icons/filter-button.svg"); ?>" alt="">
            </button>-->
                <div class="projects__inner-row active" id="filterRow">
                    <div class="main__bg project">
                        <div class="main__bg-image">
                            <img src="<?php echo esc_url(get_template_directory_uri() . "/images/main-bg.svg"); ?>"
                                 alt="">
                        </div>
                    </div>
                    <?php
                    $args = array(
                        'post_type' => 'fdpgx-project',
                        'posts_per_page' => 9999,
                        'meta_key' => 'project-fields_fdpgx-projectstart',
                        'orderby' => 'meta_value',
                        'order' => 'DESC',
                    );
                    $query = new WP_Query($args);
                    $date_list = [];
                    while ($query->have_posts()) {
                        $query->the_post();
                        array_push($date_list, get_post_meta(get_the_id(), 'project-fields_fdpgx-projectstart', true));
                        $image_url = get_post_meta(get_the_id(), 'project-fields_fdpgx-projectimage', true) ? get_post_meta(get_the_id(), 'project-fields_fdpgx-projectimage', true) : "https://prelive.forschen-fuer-gesundheit.de/wp-content/uploads/2023/05/default.jpg";
                        ?>
                        <div class="projectsCard">
                            <div class="projectsCard__image">
                                <img src="<?php echo $image_url ?>" alt="">
                            </div>
                            <div class="projectsCard__content">
                                <h5>
                                    <?php echo get_the_title() ?>
                                </h5>
                                <p>
                                    <?php echo get_post_meta(get_the_id(), 'project-fields_fdpgx-simpleprojectdescription', true) ?>
                                </p>
                                <div class="projectsCard__content-bottom">
                                    <a href="<?php echo get_permalink() ?>" class="button"> Details </a>
                                    <h6><?php echo get_post_meta(get_the_id(), 'project-fields_fdpgx-projectstart', true) ?>  </h6>
                                </div>
                            </div>
                        </div>
                    <?php }
                    ?>

                </div>
                <div class="projects__inner-list-wrapper" id="filterList">
                    <div class="projects__inner-list">
                        <div class="projects__inner-list-header">
                            <h6 class="name">
                                Forschungsprojekt
                            </h6>
                            <h6 class="category">
                                Category
                            </h6>
                            <h6 class="date">
                                Projektstart
                            </h6>
                        </div>


                        <div class="projectsItem">
                            <div class="projectsItem__name">
                                <p>
                                    NT-proBNP als Marker bei Vorhofflimmern (dezentral)
                                </p>
                            </div>
                            <div class="projectsItem__category">
                                <p class="big">
                                    Kardiologie, Innere Medizin
                                </p>
                            </div>
                            <div class="projectsItem__date">
                                <p class="big">
                                    07.02.2023
                                </p>
                            </div>
                            <a href="/fdpgx-project/vhf-mi-dezentral/" class="button">
                                Details
                            </a>
                        </div>


                        <div class="projectsItem">
                            <div class="projectsItem__name">
                                <p>
                                    Regelmäßige Ausführung von verteilten Machbarkeitsanfragen zur Vorbereitung und
                                    Durchführung standortübergreifender Forschungsprojekte am Standort Bonn
                                </p>
                            </div>
                            <div class="projectsItem__category">
                                <p class="big">
                                    Sonstige Fachabteilung
                                </p>
                            </div>
                            <div class="projectsItem__date">
                                <p class="big">
                                    09.03.2023
                                </p>
                            </div>
                            <a href="https://prelive.forschen-fuer-gesundheit.de/fdpgx-project/machbarkeitsanfragen/"
                               class="button">
                                Details
                            </a>
                        </div>


                        <div class="projectsItem">
                            <div class="projectsItem__name">
                                <p>
                                    NT-proBNP als Marker bei Vorhofflimmern (dezentral, datashield)
                                </p>
                            </div>
                            <div class="projectsItem__category">
                                <p class="big">
                                    Kardiologie, Innere Medizin
                                </p>
                            </div>
                            <div class="projectsItem__date">
                                <p class="big">
                                    07.03.2023
                                </p>
                            </div>
                            <a href="https://prelive.forschen-fuer-gesundheit.de/fdpgx-project/nt-probnp-dezentral-datashield/"
                               class="button">
                                Details
                            </a>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- projects end
  =========================================== -->
    <!-- footer start
  =========================================== -->


    <?php
    // get_sidebar();
    get_footer();
    ?>