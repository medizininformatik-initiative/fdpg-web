function searchPlatform() {
    var input, filter, platformdiv, h3, i, txtValue,platDesc,platVal;
    input = document.getElementById('platform-filter');
    filter = input.value.toUpperCase();
    platformdiv = document.querySelectorAll('.col-app,.col-form');
    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < platformdiv.length; i++) {
    h3 = platformdiv[i].getElementsByTagName("h3")[0];
    platDesc= platformdiv[i].getElementsByClassName('app-desc')[0];
    txtValue = h3.textContent || h3.innerText;
    platVal=platDesc.textContent || platDesc.innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1 || platVal.toUpperCase().indexOf(filter) > -1) {
        platformdiv[i].style.display = "";
       } else {
       platformdiv[i].style.display = "none";
       }
    }
    visibleCount();
}
totalwidth=jQuery(".row-app").width();
numElements=Math.floor(totalwidth/339);
estimatedWidth=339*numElements;
searchBoxWidth=estimatedWidth-343;
jQuery(".searchbox").css("margin-left",searchBoxWidth+"px");


const $ = document.querySelector.bind(document);
const $$ = document.querySelectorAll.bind(document);
const tabs = $$(".tabs-panel__left-tab");
const categories = $$(".cat");
tabs.forEach((tab, index) => {
  tab.onclick = function () {
    jQuery('.ui-tooltip').hide();
    $(".tabs-panel__left-tab.semitabactive").classList.remove("semitabactive");
    this.classList.add("semitabactive");      
    if(jQuery(this).data('type') == "apps"){
        jQuery('*[data-type="form"]').hide();
        jQuery('*[data-type="enterprise"]').hide();
        jQuery('*[data-type="disabled"]').hide();
        jQuery('*[data-type="app"]').show();
    }
    else if(jQuery(this).data('type') == "forms"){
        jQuery('*[data-type="app"]').hide();
        jQuery('*[data-type="enterprise"]').hide();
        jQuery('*[data-type="disabled"]').hide();
        jQuery('*[data-type="form"]').show();
    } 
    else if(jQuery(this).data('type') == "all"){
        jQuery('*[data-type="disabled"]').hide();
        jQuery('*[data-type="app"]').show();
        jQuery('*[data-type="form"]').show();
        jQuery('*[data-type="enterprise"]').show();
    }   
    else if(jQuery(this).data('type') == "enterprises"){
        jQuery('*[data-type="app"]').hide();
        jQuery('*[data-type="form"]').hide();
        jQuery('*[data-type="disabled"]').hide();
        jQuery('*[data-type="enterprise"]').show();
    }   
    else if(jQuery(this).data('type') == "disableds"){
        jQuery('*[data-type="app"]').hide();
        jQuery('*[data-type="form"]').hide();
        jQuery('*[data-type="enterprise"]').hide();
        jQuery('*[data-type="disabled"]').show();
    }   
    visibleCount();
  };
});
categories.forEach((category, index) => {
  category.onclick = function () {
    $(".cat.secondarytabsactive").classList.remove("secondarytabsactive");
    this.classList.add("secondarytabsactive");
    if(jQuery(this).data('cat') == "favourites"){
        jQuery('*[data-type="app"]').hide();
        jQuery('*[data-type="form"]').hide();
        jQuery('*[data-type="premium"]').hide();
        jQuery('*[data-category*="favourite"]').show();
    }
    if(jQuery(this).data('cat') == "crm"){
        jQuery('*[data-type="app"]').hide();
        jQuery('*[data-type="form"]').hide();
        jQuery('*[data-type="premium"]').hide();
        jQuery('*[data-category*="crm"]').show();
    }
    if(jQuery(this).data('cat') == "esp"){
        jQuery('*[data-type="app"]').hide();
        jQuery('*[data-type="form"]').hide();
        jQuery('*[data-type="premium"]').hide();
        jQuery('*[data-category*="esp"]').show();
    }
    if(jQuery(this).data('cat') == "sms"){
        jQuery('*[data-type="app"]').hide();
        jQuery('*[data-type="form"]').hide();
        jQuery('*[data-type="premium"]').hide();
        jQuery('*[data-category*="sms"]').show();
    }
    if(jQuery(this).data('cat') == "webinars"){
        jQuery('*[data-type="app"]').hide();
        jQuery('*[data-type="form"]').hide();
        jQuery('*[data-type="premium"]').hide();
        jQuery('*[data-category*="webinars"]').show();
    }
    if(jQuery(this).data('cat') == "other"){
        jQuery('*[data-type="app"]').show();
        jQuery('*[data-type="form"]').hide();
        jQuery('*[data-type*="premium"]').hide();
    }
    visibleCount();
  };
});

function visibleCount(){
    jQuery("#visibleCounter").text(jQuery(".col-app:visible,.col-form:visible").length); 
 }
 visibleCount();

 jQuery(document).ready(function($) {
    triggerFavorites();
    jQuery('.install-app').on('click', function() {
        jQuery(this).children('i').removeClass().addClass("fa fa-circle-o-notch fa-spin");
        window.location=jQuery(this).children('a').attr("href");
    });
    jQuery(".heart").click(function(){
        if(jQuery(this).hasClass("liked")){
          jQuery(this).html('<i class="fa fa-heart-o" aria-hidden="true"></i>');
          jQuery(this).removeClass("liked");
          title=jQuery(this).siblings(".title-app").text().toLowerCase();
          title = title.replace(/\s/g, '');
          remove_favorite_platform(title);
          currentVal=jQuery(this).siblings(".title-app").parent().attr("data-category");
          currentVal=currentVal.replace(',favourite','');
          jQuery(this).siblings(".title-app").parent().attr("data-category",currentVal);          
        }else{
          jQuery(this).html('<i class="fa fa-heart" aria-hidden="true"></i>');
          jQuery(this).addClass("liked");
          title=jQuery(this).siblings(".title-app").text().toLowerCase();
          title = title.replace(/\s/g, '');
          add_favorite_platform(title);
          currentVal=jQuery(this).siblings(".title-app").parent().attr("data-category");       
          jQuery(this).siblings(".title-app").parent().attr("data-category",currentVal+",favourite");
        }
    });
});
jQuery(document).ready(function($) {
	$(function () {
		'use strict';

		// *Platforms filter
		if ($('.tabs-panel').length) {

			// Filter function
			$('#platform-filter').on('keyup', function (event) {
				
					searchPlatform();
			
			});
			function filter(filter, query) {
				query = $.trim(query);
				$(filter).each(function () {
					($(this).text().search(new RegExp(query, "i")) < 0) ? $(this).hide().removeClass('name') :
						$(this).show().addClass('name');
				});
			}

			// Show/hide button
			let openBtn = $('.tabs-panel__search-button');
			let filterInput = $('.tabs-panel__search-input');

			openBtn.on('click', function () {
				filterInput.toggleClass('is-active');
				openBtn.removeClass('is-hide');
				$(this).addClass('is-hide');
			});
		}
		// *Platforms filter

		// Burger
// 		$('.header__burger').on('click', function (e) {
// 			$('.header__nav').toggleClass('is-show');
// 			$('.header__burger').toggleClass('header__burger--active');
// 			e.stopPropagation();
// 		});

// 		$('.header__nav').on('click', function (e) {
// 			e.stopPropagation();
// 		});

// 		$('.header__nav a').on('click', function (e) {
// 			$('.header__nav').removeClass('is-show');
// 			$('.header__burger').removeClass('header__burger--active');
// 			e.stopPropagation();
// 		});

// 		$(document).on('click', function () {
// 			$('.header__nav').removeClass('is-show');
// 			$('.header__burger').removeClass('header__burger--active');
// 		});

		// Password view
		$('.registration__password-view').on('click', function (e) {
			if ($('.registration__password-input').attr('type') == 'password') {
				$(this).addClass('view');
				$('.registration__password-input').attr('type', 'text');
			} else {
				$(this).removeClass('view');
				$('.registration__password-input').attr('type', 'password');
			}
			e.preventDefault();
		});

		// Chk-all
		$(document).on('change', 'input[type=checkbox]', function () {
			var $this = $(this), $chks = $(document.getElementsByName(this.name)), $all = $chks.filter(".chk-all");

			if ($this.hasClass('chk-all')) {
				$chks.prop('checked', $this.prop('checked'));
			} else switch ($chks.filter(":checked").length) {
				case +$all.prop('checked'):
					$all.prop('checked', false).prop('indeterminate', false);
					break;
				case $chks.length - !!$this.prop('checked'):
					$all.prop('checked', true).prop('indeterminate', false);
					break;
				default:
					$all.prop('indeterminate', true);
			}
		});

		// Marqueee
		class The_Marquee {
			constructor($obj, options = false) {
				// This class inner var
				let this_class = this;

				// Define Wrapper and Marquee
				if ($obj instanceof jQuery) {
					this.$wrap = $obj;
				} else {
					this.$wrap = jQuery($obj);
				}
				this.$mar = this.$wrap.children();

				// Options
				this.options = {
					time: '3s',
					dir: 'down'
				}
				if (options)
					jQuery.extend(this.options, options);

				// Create Style
				this.$style = jQuery('<style></style>').prependTo('body');

				// Init
				this.init();

				// Window Resize Event
				jQuery(window).on('resize', function () {
					this_class.layout();
				});
			}
			init() {
				let this_class = this;
				// Get Point
				this.point = 0;
				this.gap = parseInt(this.$mar.css('grid-row-gap'), 10);
				this.$mar.children(':not(.is-clone)').each(function () {
					this_class.point = this_class.point + jQuery(this).height() + this_class.gap;
				});

				// Check Height
				this.checkHeight();

				// Create animation
				this.anim();

				// Apply animation
				this.$mar.css({
					'animation-name': 'down' == this.options.dir ? 'scrollDown' : 'scrollUp',
					'animation-duration': this.options.time,
					'animation-timing-function': 'linear',
					'animation-iteration-count': 'infinite'
				});
			}
			checkHeight() {
				let this_class = this,
					chkHeight = this.$mar.height() - this.point;

				if (chkHeight < this.$wrap.height()) {
					// Clone Items
					this.$mar.children(':not(.is-clone)').each(function () {
						let $clone = jQuery(this).clone();
						$clone.addClass('is-clone').appendTo(this_class.$mar);
					});

					// Check Again
					this.checkHeight();
				} else {
					// Append First Fake Item
					this.$mar.children().eq(-1).clone().addClass('is-clone is-first').prependTo(this_class.$mar);
				}
			}
			layout() {
				let this_class = this;

				// Remove Clones
				this.$mar.children('.is-clone').remove();

				// Empty Animation
				this.$style.empty();

				// Init Once Again
				this.init();
			}
			anim() {
				// Append Anim KeyFrames
				this.$style.append('\
						@keyframes scrollUp {\
							0% { transform: translateY(0px); }\
							100% { transform: translateY(-'+ this.point + 'px); }\
						}\
						@keyframes scrollDown {\
							0% { transform: translateY(-'+ this.point + 'px); }\
							100% { transform: translateY(0px); }\
						}\
					');
			}
		}

		let $test1 = new The_Marquee('.marquee-wrap1', {
			time: '15s',
			dir: 'up'
		});

		let $test2 = new The_Marquee('.marquee-wrap2', {
			time: '20s',
			dir: 'up'
		});

		let $test3 = new The_Marquee('.marquee-wrap3', {
			time: '30s',
			dir: 'up'
		});

		let $test4 = new The_Marquee('.marquee-wrap4', {
			time: '25s',
			dir: 'up'
		});

		// Selects
		var lists = {
			SperseCRM1: {
				// value -> if Label test1
				'val1': 'Sperse CRM1',
				'val2': 'Sperse CRM1',
				'val3': 'Sperse CRM1',
				'val4': 'Sperse CRM1',
			},
			SperseCRM2: {
				// value -> if Label test2
				'val5': 'Sperse CRM2',
				'val6': 'Sperse CRM2',
				'val7': 'Sperse CRM2',
				'val8': 'Sperse CRM2',
			}
		}

		function custom_select() {
			jQuery('select:not(.select-hidden)').each(function () {
				let $this = jQuery(this), numberOfOptions = jQuery(this).children('option').length;

				jQuery(this).test = 'test me';

				$this.wrap('<div class="select"></div>');
				$this.after('<div class="select-styled"></div>');

				let $styledSelect = $this.next('div.select-styled');
				$styledSelect.text($this.children('option').eq(0).text());

				if ($this.attr('class')) {
					$styledSelect.addClass($this.attr('class'));
				}

				$this.addClass('select-hidden');

				let $list = jQuery('<ul />', {
					'class': 'select-options'
				}).insertAfter($styledSelect);

				for (let i = 0; i < numberOfOptions; i++) {
					jQuery('<li />', {
						text: $this.children('option').eq(i).text(),
						rel: $this.children('option').eq(i).val()
					}).appendTo($list);
				}

				let $listItems = $list.children('li');

				$styledSelect.on('click', function (e) {
					e.stopPropagation();
					jQuery('div.select-styled.active').not(this).each(function () {
						jQuery(this).removeClass('active').next('ul.select-options').hide();
					});
					jQuery(this).toggleClass('active').next('ul.select-options').toggle();
				});

				$listItems.on('click', function (e) {
					e.stopPropagation();
					$styledSelect.text(jQuery(this).text()).removeClass('active');
					$this.val(jQuery(this).attr('rel'));

					$this.trigger('change'); // TRIGGER CHANGE EVENT;

					$list.hide();
				});

				jQuery(document).on('click', function () {
					$styledSelect.removeClass('active');
					$list.hide();
				});
			});
		}

		custom_select();

		// Depended select 
		jQuery('#whose').on('change', function () {
			let $this = jQuery(this),
				$appendTo = jQuery($this.attr('data-depand'));

			$appendTo.empty();

			let $newSelect = jQuery('<select/>');

			for (const [key, value] of Object.entries(lists[$this.val()])) {

				$newSelect.append('<option value="' + key + '">' + value + '</option>');
			}

			$appendTo.append($newSelect);

			custom_select();
		});


	});



});
