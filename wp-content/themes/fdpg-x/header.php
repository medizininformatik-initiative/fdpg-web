<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="<?php echo esc_url("https://fonts.googleapis.com"); ?>">
    <link rel="preconnect" href="<?php echo esc_url("https://fonts.gstatic.com"); ?>" crossorigin>
    <link href="<?php echo esc_url("https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Noto Sans Georgian&display=swap"); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri()."/css/main.css"); ?>">
    <title>FDPG - Forschungsdatenportal für Gesundheit</title>
  </head>
  <body>

    <!-- header start
    =========================================== -->
<header class="header" id="header">
  <div class="auto__container" id="auto__container">
    <div class="header__inner">
      <div class="header__inner-logo">
        <a href="https://prelive.forschen-fuer-gesundheit.de">
          <img src="https://prelive.forschen-fuer-gesundheit.de/wp-content/themes/fdpg-x/./images/logo.png" alt="Forschen für Gesundheit">
        </a>
      </div>
      <nav class="nav">
        <ul id="menu-header-menu" class="nav__inner" style="z-index: 20;">
          <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-650 dropdown">
            <a href="#">Forschungsprojekte</a>
            <ul class="sub-menu">
              <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-355">
                <a href="https://prelive.forschen-fuer-gesundheit.de/projektregister/">Projektregister</a>
              </li>
              <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-450">
                <a href="https://prelive.forschen-fuer-gesundheit.de/patienteninformationen/">Patienteninformationen</a>
              </li>
            </ul>
          </li>
          <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-652 dropdown">
            <a href="#">Ein Projekt durchführen</a>
            <ul class="sub-menu">
              <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-346">
                <a href="https://prelive.forschen-fuer-gesundheit.de/daten-und-bioproben/">Daten und Bioproben</a>
              </li>
              <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-434">
                <a href="https://prelive.forschen-fuer-gesundheit.de/daten-und-bioproben/prozesse-der-antragstellung-und-datennutzung-in-der-mii/">Prozesse der Antragstellung und Datennutzung in der MII</a>
              </li>
              <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-406">
                <a href="https://prelive.forschen-fuer-gesundheit.de/daten-und-bioproben/projekt-vorbereiten/">Projekt vorbereiten</a>
              </li>
              <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-373">
                <a href="https://prelive.forschen-fuer-gesundheit.de/daten-und-bioproben/daten-finden/">Daten und Bioproben finden</a>
              </li>
              <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-396">
                <a href="https://prelive.forschen-fuer-gesundheit.de/daten-und-bioproben/daten-und-bioproben-fur-ein-forschungsprojekt-beantragen/">Daten und Bioproben für ein Forschungsprojekt beantragen</a>
              </li>
              <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-654">
                <a href="https://prelive.forschen-fuer-gesundheit.de/daten-und-bioproben/daten-und-proben-analysieren/">Daten und Proben analysieren</a>
              </li>
            </ul>
          </li>
          <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-653 dropdown">
            <a href="#">Infrastruktur MII</a>
            <ul class="sub-menu">
              <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-661">
                <a href="https://prelive.forschen-fuer-gesundheit.de/diz/">Standorte</a>
              </li>
              <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-661">
                <a href="https://prelive.forschen-fuer-gesundheit.de/uber-das-forschungsdatenportal/">Über das Forschungsdatenportal</a>
              </li>
              <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-661">
                <a href="https://prelive.forschen-fuer-gesundheit.de/datengeber-werden/">Datengeber werden</a>
              </li>                            
            </ul>
          </li>
        </ul>
      </nav>
      <div class="header__inner-buttons">
        <a href="https://antrag.forschen-fuer-gesundheit.de" class="header__inner-buttons-link">Login</a>
        <a href="https://antrag.forschen-fuer-gesundheit.de" class="button">Registrierung</a>
        <div class="burger" id="menuBtn">
          <span></span>
        </div>
      </div>
    </div>
  </div>
<?php wp_head(); ?>
</header>
<script type="text/javascript">
  requestAnimationFrame(() => document.body.classList.add("stk--anim-init"))
</script>
<script type="text/javascript">
  if (typeof ClipboardJS !== 'undefined') {
    new ClipboardJS('.btn');
  }
</script>
<script type="text/template" id="tmpl-elementor-templates-modal__header">
  <div class="elementor-templates-modal__header__logo-area"></div>
  <div class="elementor-templates-modal__header__menu-area"></div>
  <div class="elementor-templates-modal__header__items-area">
    <# if ( closeType ) { #>
      <div class="elementor-templates-modal__header__close elementor-templates-modal__header__close--{{{ closeType }}} elementor-templates-modal__header__item">
        <# if ( 'skip' === closeType ) { #>
          <span>Skip</span>
        <# } #>
        <i class="eicon-close" aria-hidden="true"></i>
        <span class="elementor-screen-only">Close</span>
      </div>
    <# } #>
    <div id="elementor-template-library-header-tools"></div>
  </div>
</script>
<script type="text/template" id="tmpl-elementor-templates-modal__header__logo">
  <span class="elementor-templates-modal__header__logo__icon-wrapper e-logo-wrapper">
    <i class="eicon-elementor"></i>
  </span>
  <span class="elementor-templates-modal__header__logo__title">{{{ title }}}</span>
</script>
<script type="text/template" id="tmpl-elementor-finder">
  <div id="elementor-finder__search">
    <i class="eicon-search" aria-hidden="true"></i>
    <input id="elementor-finder__search__input" placeholder="Type to find anything in Elementor" autocomplete="off">
  </div>
  <div id="elementor-finder__content"></div>
</script>
<script type="text/template" id="tmpl-elementor-finder-results-container">
  <div id="elementor-finder__no-results">No Results Found</div>
  <div id="elementor-finder__results"></div>
</script>
<script type="text/template" id="tmpl-elementor-finder__results__category">
  <div class="elementor-finder__results__category__title">{{{ title }}}</div>
  <div class="elementor-finder__results__category__items"></div>
</script>
<script type="text/template" id="tmpl-elementor-finder__results__item">
  <a href="{{ url }}" class="elementor-finder__results__item__link">
    <div class="elementor-finder__results__item__icon">
      <i class="eicon-{{{ icon }}}" aria-hidden="true"></i>
    </div>
    <div class="elementor-finder__results__item__title">{{{ title }}}</div>
    <# if ( description ) { #>
      <div class="elementor-finder__results__item__description">- {{{ description }}}</div>
    <# } #>

    <# if ( lock ) { #>
      <div class="elementor-finder__results__item__badge"><i class="{{{ lock.badge.icon }}}"></i>{{ lock.badge.text }}</div>
    <# } #>
  </a>
  <# if ( actions.length ) { #>
    <div class="elementor-finder__results__item__actions">
    <# jQuery.each( actions, function() { #>
      <a class="elementor-finder__results__item__action elementor-finder__results__item__action--{{ this.name }}" href="{{ this.url }}" target="_blank">
        <i class="eicon-{{{ this.icon }}}"></i>
      </a>
    <# } ); #>
    </div>
  <# } #>
</script>
<?php //wp_footer(); ?>

<!--   </body>
</html> -->
