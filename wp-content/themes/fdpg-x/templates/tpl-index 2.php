<?php /* Template Name: Template Index


               */
get_header(); ?>
<!-- header end
=========================================== -->
<main class="main">
    <div class="main__bg">
        <div class="main__bg-image">
            <img src="<?php echo esc_url(get_template_directory_uri() . "/./images/main-bg.svg"); ?>" alt="">
        </div>
    </div>
    <!-- hero start
  =========================================== -->
    <swiper-container class="mySwiper" navigation="true">
        <swiper-slide>
            <section class="hero"
                     style="background:  linear-gradient(to right, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0) 100%), url('https://prelive.forschen-fuer-gesundheit.de/wp-content/uploads/2023/05/forscher-slider.jpg') no-repeat center top;">
                <div class="auto__container">
                    <div class="hero__inner">
                        <div class="hero__inner-content">
                            <h1>
                                Daten für mein Forschungsprojekt<br>
                                <span>zentral beantragen</span>
                            </h1>
                            <p>
                                Entdecken Sie Daten der Universitätskliniken der Medizininformatik-Initiative,
                                standardisiert, aktuell und in nie dagewesenem Umfang.
                            </p>
                            <a href="https://antrag.forschen-fuer-gesundheit.de" class="button"> Jetzt registrieren </a>
                        </div>
                    </div>
                </div>
            </section>
        </swiper-slide>

        <swiper-slide>
            <section class="hero"
                     style="background:  linear-gradient(to right, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0) 100%), url('https://prelive.forschen-fuer-gesundheit.de/wp-content/uploads/2023/05/patient-forschung-datenschutz-header-scaled.jpg') no-repeat center top;">
                <div class="auto__container">
                    <div class="hero__inner">
                        <div class="hero__inner-content">
                            <h1>
                                Mit Patientendaten<br>
                                <span>die medizinische Forschung<br>unterstützten</span>
                            </h1>
                            <p>
                                Je mehr Patienten sich mit ihrer Einwilligung beteiligen, desto reicher ist der
                                Datenschatz, mit dem die Forschenden arbeiten können
                            </p>
                            <a href="/newsletter/" class="button"> Newsletter abonieren </a>
                        </div>
                    </div>
                </div>
            </section>
        </swiper-slide>

    </swiper-container>


    <!-- hero end
  =========================================== -->
    <!-- intro start
  =========================================== -->
    <section class="intro">
        <div class="auto__container">
            <div class="intro__inner">
                <div class="intro__inner-content">
                    <h4>Forschen f&uuml;r Gesundheit</h4>
                    <p class="dark">

                        Das Deutsche Forschungsdatenportal für Gesundheit (FDPG) ist zentraler Anlaufpunkt für
                        Wissenschaftlerinnen und Wissenschaftler, die ein Forschungsprojekt mit Routinedaten der
                        deutschen Universitätsmedizin durchführen möchten.

                        Im Rahmen der Medizininformatik-Initiative (MII), gefördert vom Bundesministerium für Bildung
                        und Forschung, werden in den Datenintegrationszentren der universitätsmedizinischen <a
                                href="/standorte/">Standorte</a> Patientendaten und Bioproben aus der Routineversorgung
                        für die medizinische Forschung nutzbar gemacht und datenschutzgerecht bereitgestellt.


                    </p>
                    <h5>Das FDPG bietet:</h5>
                    <ul>
                        <li>
                            eine Übersicht über Datenbestände für die standortübergreifende Forschung
                        </li>
                        <li>
                            die Möglichkeit, die Machbarkeit spezifischer Forschungsfragen anhand von
                            Machbarkeitsabfragen zu evaluieren
                        </li>
                        <li>
                            einen standardisierten Prozess zur Beantragung von Daten und Bioproben
                        </li>
                        <li>
                            etablierte vertragliche Rahmenbedingungen zur einfachen Datennutzung
                        </li>
                        <li>eine zentrale Koordination der Datenbereitstellung</li>
                        <li>
                            eine transparente Darstellung von Forschungsprojekten im Projektregister
                        </li>
                    </ul>
                    <p>
                        Mehr Informationen finden Sie im <a
                                href="https://www.medizininformatik-initiative.de/sites/default/files/2022-09/MII_Faltflyer_A4_FDPG_digital.pdf"
                                target="_blank">Flyer</a>.
                    </p>


                </div>


                <section class="projects" style="margin-top: 40px;">
                    <div class="auto__container"
                         style="background-color: #206fa7 !important; opacity: 0.8 !important; padding: 10px !important; color: #fff !important;">
                        <div class="projects__inner" style=" opacity: 0.8 !important;">
                            <p style=" color: #fff !important">
                                <br>
                                Seit 16. Mai 2023 dürfen Forschende (auch außerhalb der MII) Zugang zu Patientendaten
                                und Bioproben für medizinische Forschungszwecke beantragen und Machbarkeitsanfragen
                                stellen.


                            </p>

                            <ul style=" color: #fff !important;">
                                <li style=" color: #fff !important">Das Portal ist als Betaversion verfügbar, da die
                                    zugehörige Dateninfrastruktur zunächst anhand erster Nutzungsprojekte getestet und
                                    laufend verbessert wird. Ziel ist, dass alle Standorte der MII Daten liefern können.<br><br>
                                </li>
                                <li style=" color: #fff !important">Die Daten werden im FHIR-Format standardisiert
                                    bereitgestellt. Dennoch ist die Heterogenität der Daten nach wie vor eine große
                                    Herausforderung. Die MII arbeitet gemeinsam mit den Datennutzenden und -gebenden im
                                    Sinne eines lernenden Systems weiterhin an Verbesserungen der Standardisierung und
                                    Verfügbarkeit der Daten.<br><br></li>
                                <li style=" color: #fff !important">Die Sichtung durch die Standorte (UACs) und die
                                    Frist für die Datenbereitstellung dauert jeweils bis zu zwei Monate. Außerdem müssen
                                    Forschende Zeit für das Ethikvotum und den Vertragsschluss mit den Standorten
                                    einplanen. Daher sollten Forschungsprojekte mindestens fünf Monate Zeit einplanen.
                                </li>
                            </ul>

                            <br>
                        </div>
                    </div>
                </section>


                <div class="intro__inner-row">
                    <div class="introItem">
                        <h6>Daten und Bioproben beantragen</h6>
                        <p>
                            Forschende k&ouml;nnen &uuml;ber das Deutsche Forschungsdatenportal die
                            Verf&uuml;gbarkeit von Daten und Bioproben mit einer
                            Machbarkeitsanfrage pr&uuml;fen und zentral einen
                            Antrag auf Nutzung von Daten und Bioproben
                            stellen.
                        </p>
                        <a href="/daten-und-bioproben/daten-und-bioproben-fur-ein-forschungsprojekt-beantragen/">
                  <span>
                    <img src="<?php echo esc_url(get_template_directory_uri() . "/images/icons/more.png"); ?>" alt="">
                  </span>
                            Wie können Daten beantragt werden?
                        </a>
                    </div>
                    <div class="introItem">
                        <h6>Forschungsprojekte finden</h6>
                        <p>
                            Das Projektregister des FDPG bietet Patientinnen und
                            Patienten, Forschenden und allen Interessierten einen
                            transparenten &Uuml;berblick &uuml;ber beantragte, laufende und bereits
                            abgeschlossene Forschungsprojekte im Rahmen der MII.
                        </p>
                        <a href="/projektregister/">
                  <span>
                    <img src="<?php echo esc_url(get_template_directory_uri() . "/images/icons/more.png"); ?>" alt="">
                  </span>
                            Welche Projekte gibt es bereits?
                        </a>
                    </div>
                    <div class="introItem">
                        <h6>Patienteninformationen</h6>
                        <p>
                            Medizinische Forschung hilft, Krankheiten besser zu erkennen,
                            zu behandeln und ihnen vorzubeugen. Mit Ihren Gesundheitsdaten
                            k&ouml;nnen Sie die medizinische Forschung in Deutschland
                            unterst&uuml;tzen.
                        </p>
                        <a href="/patienteninformationen/">
                  <span>
                    <img src="<?php echo esc_url(get_template_directory_uri() . "/images/icons/more.png"); ?>" alt="">
                  </span>
                            Wie werden Patientendaten geschützt?
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- intro end





  =========================================== -->
    <!-- update start
  =========================================== -->
    <section class="update">
        <div class="update__bg">
            <div class="update__bg-image">
                <img src="<?php echo esc_url(get_template_directory_uri() . "/./images/main-bg.svg"); ?>" alt="">
            </div>
        </div>
        <div class="auto__container">
            <div class="update__inner">
                <div class="update__inner-header">
                    <h4>Daten&uuml;bersicht</h4>
                    <p>
                        Hier finden Sie eine &Uuml;bersicht &uuml;ber alle verf&uuml;gbaren Daten.
                        Diese wird regelm&auml;&szlig;ig aktualisiert.<br>
                        <span style="color: #081340; font-size:  11px;">*Die Zahlen in der Datenübersicht sind Summen der Rückmeldungen aller Standorte zu den jeweiligen Suchanfragen, die in Abhängigkeit davon, wie viele und welche Standorte zum Zeitpunkt der Suchanfrage antworten, variieren können. Abweichungen von den Ergebnissen der Machbarkeitsanfragen sind daher erwartbar.</span>
                    </p>
                </div>
                <!--
              <div class="update__inner-title">
                <h4>letzte Aktualisierung</h4>
                <h2>10.11.2022</h2>
              </div>-->
                <div class="update__inner-row">
                    <div class="updateItem">
                        <div class="updateItem__inner">
                            <h2>7.629.148</h2>
                            <h4>Personen</h4>
                            <p>
                                Basisdaten eines Krankenhausaufenthaltes von Patientinnen und Patienten
                            </p>
                        </div>
                        <div class="updateItem__icon">
                            <img src="<?php echo esc_url(get_template_directory_uri() . "/images/icons/persons.svg"); ?>"
                                 alt="">
                        </div>
                    </div>
                    <div class="updateItem">
                        <div class="updateItem__inner">
                            <h2>20</h2>
                            <h4>Standorte</h4>
                            <p>
                                Datenintegrationszentren, die über das Forschungsdatenportal Daten bereitstellen
                            </p>
                        </div>
                        <div class="updateItem__icon sm">
                            <img src="<?php echo esc_url(get_template_directory_uri() . "/images/icons/location.svg"); ?>"
                                 alt="">
                        </div>
                    </div>
                    <div class="updateItem">
                        <div class="updateItem__inner">
                            <h2>85.567.136</h2>
                            <h4>Diagnosen</h4>
                            <p>
                                Beschreibung der Krankheiten einer Person
                            </p>
                        </div>
                        <div class="updateItem__icon sm">
                            <img src="<?php echo esc_url(get_template_directory_uri() . "/images/icons/diagnosis.svg"); ?>"
                                 alt="">
                        </div>
                    </div>
                    <div class="updateItem">
                        <div class="updateItem__inner">
                            <h2>152.246.518</h2>
                            <h4>Laborwerte</h4>
                            <p>
                                Daten zu Laboruntersuchungen von Patientinnen und Patienten
                            </p>
                        </div>
                        <div class="updateItem__icon sm">
                            <img src="<?php echo esc_url(get_template_directory_uri() . "/images/icons/laboratory.svg"); ?>"
                                 alt="">
                        </div>
                    </div>
                    <div class="updateItem">
                        <div class="updateItem__inner">
                            <h2>37.599.484</h2>
                            <h4>Prozeduren</h4>
                            <p>
                                Dokumentation von Operationen und medizinischen Eingriffen
                            </p>
                        </div>
                        <div class="updateItem__icon big">
                            <img src="<?php echo esc_url(get_template_directory_uri() . "/images/icons/procedure.svg"); ?>"
                                 alt="">
                        </div>
                    </div>

                    <div class="updateItem">
                        <div class="updateItem__inner">
                            <h2>46.329.154</h2>
                            <h4>Medikationsdaten</h4>
                            <p>
                                Dokumentation von Arzneimittelverordnungen und -gaben
                            </p>
                        </div>
                        <div class="updateItem__icon sm">
                            <img src="<?php echo esc_url(get_template_directory_uri() . "/images/icons/medication.svg"); ?>"
                                 alt="">
                        </div>
                    </div>
                    <div class="updateItem">
                        <div class="updateItem__inner">
                            <h2>142.872</h2>
                            <h4>Bioproben</h4>
                            <p>
                                Verfügbare Bioproben, die zur Diagnose oder Therapie entnommen wurden
                            </p>
                        </div>
                        <div class="updateItem__icon">
                            <img src="<?php echo esc_url(get_template_directory_uri() . "/images/icons/bioproben.svg"); ?>"
                                 alt="">
                        </div>
                    </div>
                    <div class="updateItem">
                        <div class="updateItem__inner">
                            <h2>37.599.484</h2>
                            <h4>Prozeduren</h4>
                            <p>
                                Dokumentation von Operationen und medizinischen Eingriffen
                            </p>
                        </div>
                        <div class="updateItem__icon big">
                            <img src="<?php echo esc_url(get_template_directory_uri() . "/images/icons/procedure.svg"); ?>"
                                 alt="">
                        </div>
                    </div>
                    <div class="updateItem">
                        <div class="updateItem__inner">
                            <h2>37.599.484</h2>
                            <h4>Prozeduren</h4>
                            <p>
                                Dokumentation von Operationen und medizinischen Eingriffen
                            </p>
                        </div>
                        <div class="updateItem__icon big">
                            <img src="<?php echo esc_url(get_template_directory_uri() . "/images/icons/procedure.svg"); ?>"
                                 alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- update end -->


    <section class="projects">
        <div class="auto__container">
            <div class="projects__inner">
                <div class="projects__inner-title">

                    <h4>&nbsp;</h4>

                </div>
            </div>
        </div>
    </section>


    <!-- projects start
  =========================================== -->
    <section class="projects">
        <div class="auto__container">
            <div class="projects__inner">
                <div class="projects__inner-title">
                    <h4>Forschungsprojekte</h4>
                    <a href="/projektregister/">
                <span>
                  <img src="<?php echo esc_url(get_template_directory_uri() . "/images/icons/more.png"); ?>"
                       alt="FDP Projektregister">
                </span>
                        Alle Projekte
                    </a>
                </div>
                <div class="projects__inner-row slider active" style="margin-top: 40px;">
                    <?php
                    $args = array(
                        'post_type' => 'fdpgx-project',
                        'posts_per_page' => 10,
                        'meta_key' => 'project-fields_fdpgx-projectstart',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC'
                    );
                    $query = new WP_Query($args);
                    while ($query->have_posts()) {
                        $query->the_post();
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
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <!-- projects end







=========================================== -->
    <!-- footer start
  =========================================== -->

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-element-bundle.min.js"></script>

    <?php
    // get_sidebar();
    get_footer();
    ?>