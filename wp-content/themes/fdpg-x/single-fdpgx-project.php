<?php /* Template Name: FDPG Projektdetails */
get_header(); ?>
<!-- header end

=========================================== -->

	  


<main class="main overview">
    <div class="main__bg">
        <div class="main__bg-image">
            <img src="<?php echo esc_url(get_template_directory_uri()."/./images/main-bg.svg"); ?>" alt="FDPG">
        </div>
    </div>
    <!-- info start
  =========================================== -->
    <section class="info overview">
        <div class="auto__container">
            <div class="info__inner">
                <h2>Projekt&uuml;bersicht</h2>
            </div>
        </div>
    </section>
    <!-- info end
  =========================================== -->

    <!-- header start
  =========================================== -->	
	<section class="hero overview"  style="background-image: linear-gradient(to right, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0) 100%), url('<?php echo get_post_meta(get_the_ID(), 'project-fields_fdpgx-projectimage', true) ?>'); background-size: cover; background-repeat: no-repeat;">
        <div class="auto__container">
            <div class="hero__inner">
                <div class="hero__inner-content">
                    <h1><?php echo get_post_meta(get_the_ID(), 'project-fields_fdpgx-projecttitle', true) ?></h1>
                    <h5>Verantwortliche/r Mitarbeiter/in</h5>
     <?php
                $researchers = get_post_meta(get_the_ID(), 'project-fields_fdpgx-researcher', true);
			 echo '<h6>';
			 	$sSientistsList = '';
                foreach ($researchers as $field) {
					$sSientistsList = $sSientistsList.get_post_meta($field, 'fdpgx-researcher-fields_fdpgx-scientifictitle', true) . ' ' . get_post_meta($field, 'fdpgx-researcher-fields_fdpgx-firstname', true) . ' ' . get_post_meta($field, 'fdpgx-researcher-fields_fdpgx-lastname', true) . ', ';
                     
                }
			 echo substr($sSientistsList, 0, -2);$sSientistsList;
			 echo '</h6>';
                ?>					
					

                    <h5>Projektpartner</h5>

                	<?php
                	$participants = get_post_meta(get_the_ID(), 'project-fields_fdpgx-participantsinstitute', true);
                	foreach ($participants as $field) {
                    	echo '<h6>' . get_post_meta($field, 'location-fields_fdpgx-name', true) . '</h6>';
                	}
                	?>

                </div>
            </div>
        </div>
    </section>
    <!-- head end
  =========================================== -->
    <!-- article start
  =========================================== -->
    <section class="article">
        <div class="auto__container">
            <div class="article__inner">
                <div class="article__inner-top">
                    <h6>Projektstatus: <span><?php echo get_post_meta(get_the_ID(), 'project-fields_fdpgx-projectstate', true) ?></span></h6>
					<h6>Förderer: <span><?php echo get_post_meta(get_the_ID(), 'project-fields_fdpgx-fundingpartner', true) ?> (<a href="https://www.gesundheitsforschung-bmbf.de/de/suche.php?q=<?php echo get_post_meta(get_the_ID(), 'project-fields_fdpgx-projectfunding', true) ?>" target="_blank" style="color: rgba(83, 90, 122, 0.5);"><?php echo get_post_meta(get_the_ID(), 'project-fields_fdpgx-projectfunding', true) ?></a>)</span></h6>

					<h6>
					&nbsp;
					</h6>
					
					
                    <!--<div class="progress">
                        <div class="progress__bar" style="width: 66%">66$</div>
                    </div>-->
					
                    <div class="article__inner-top-row">

                        <h6>Start: <span> 
							<?php
							$sStartDate = strtotime(get_post_meta(get_the_ID(), 'project-fields_fdpgx-projectstart', true));
							$sStartDate = date("d.m.Y", $sStartDate);
							echo $sStartDate;
							?></span></h6>
                        <h6>Abschluss: <span> <?php
							$sEndDate = strtotime(get_post_meta(get_the_ID(), 'project-fields_fdpgx-projectend', true));
							$sEndDate = date("d.m.Y", $sEndDate);
							echo $sEndDate;  ?></span></h6>
                        <h6>Dauer: <span> <?php echo get_post_meta(get_the_ID(), 'project-fields_fdpgx-projectduration', true) ?> Monate</span></h6>
						<h6>
							<span>&nbsp;</span>
						</h6>
						
                    </div>
                </div>
                <div class="article__inner-row">
                    <div class="article__inner-col">
                        <h4>Schlagw&ouml;rter:</h4>
                        <p class="big">
						<?php

                		$tags = get_post_meta(get_the_ID(), 'project-fields_fdpgx-tags', true);
                		foreach ($Tags as $field) {
                		    echo $field;
                		    echo ',';
                		}
                		?>
							
                        </p>
                    </div>
                    <div class="article__inner-col">
                        <h4>Fachrichtung:</h4>
                        <p class="big">
						<?php

		                $medical_fields = get_post_meta(get_the_ID(), 'project-fields_fdpgx-medicalfields', true);
		                foreach ($medical_fields as $field) {
		                    echo $field;
		                    echo ',';
		                }
		                ?>
						</p>
                    </div>
                    <div class="article__inner-col">
                        <h4>Diagnosen:</h4>
                        <p class="big">
						<?php
						$sDiagnosesList = '';
                		$diagnoses = get_post_meta(get_the_ID(), 'project-fields_fdpgx-diagnoses', true);
                		foreach ($diagnoses as $field) {
                		    $sDiagnosesList = $sDiagnosesList . $field . ', ';
                    		
                		}
							if (strlen(substr($sDiagnosesList, 0, -2))>2) {
								echo substr($sDiagnosesList, 0, -2);
							}
							
                		?>						
						
						</p>
                    </div>
                    <div class="article__inner-col">
                        <h4>Prozeduren:</h4>
                        <p class="big">
						<?php
						$sProceduresList = '';
                		$procedures = get_post_meta(get_the_ID(), 'project-fields_fdpgx-procedures', true);
                		foreach ($procedures as $field) {
                		    echo $field;
							$sProceduresList = $sProceduresList . $field . ', ';

                		}
							if (strlen(substr($sProceduresList, 0, -2))>2) {
								echo substr($sProceduresList, 0, -2);
							}							
                		?>
						</p>
                    </div>
                </div>
          
                <h3>Projektbeschreibung</h3>
                <p class="big">
                    <?php echo get_post_meta(get_the_ID(), 'project-fields_fdpgx-simpleprojectdescription', true) ?>
                </p>

				<h3>Projektdetails</h3>
				
                <h6>Projektziele</h6>
                <br>
                <p class="big">
                    <?php echo get_post_meta(get_the_ID(), 'project-fields_fdpgx-hypothesisandquestionprojectgoals', true) ?>
                </p>
                <br>
                <br>
                <h6>
                    Wissenschaftlicher Hintergrund
                </h6>
                <br>
                <p class="big">
                    <?php echo get_post_meta(get_the_ID(), 'project-fields_fdpgx-scientificbackground', true) ?>
                </p>
                <br>
				<br>
                <h6>
                    Material und Methoden
                </h6>
				<br>
				<p class="big" style="text-decoration: none;">
					<?php echo get_post_meta(get_the_ID(), 'project-fields_fdpgx-materialandmethods', true) ?>
				</p>
				<br>
				<h6>
					&nbsp; <!-- Forschungsergebnis für <?php echo get_post_meta(get_the_ID(), 'project-fields_fdpgx-projecttitle', true) ?> -->
				</h6>
				<h3>Projektergebnis</h3>
                <p class="big">
					<?php echo get_post_meta(get_the_ID(), 'project-fields_fdpgx-projectresults', true) ?>
				</p>
				
				
				

            </div>
        </div>
    </section>
    <!-- article end
  =========================================== -->
    <!-- providers start
  =========================================== -->
    <section class="providers">
        <div class="auto__container">
            <div class="providers__inner">
                <h3>
                    Beteiligte Datengeber
                </h3>
                <div class="providers__inner-list-wrapper">
                    <div class="providers__inner-list">
               
							
                <?php

                $locations = get_post_meta(get_the_ID(), 'project-fields_fdpgx-location', true);
                foreach ($locations as $field) {
					echo ' <div class="providersItem"><p class="big">';
                    echo get_post_meta($field, 'location-fields_fdpgx-name', true);
					echo '</p></div>';
                }
                ?>							
							
               
            
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- providers end
  =========================================== -->
    <!-- footer start
  =========================================== -->



	
    <?php
    // get_sidebar();
    get_footer();
    ?>