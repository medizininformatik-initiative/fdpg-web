// menu start
var menu = document.getElementById("menu");
var menuBtn = document.getElementById("menuBtn");
var body = document.body;
menuBtn.onclick = function () {
  menu.classList.toggle("active");
  menuBtn.classList.toggle("active");
  body.classList.toggle("active");
};
window.onclick = function (event) {
  if (event.target == menu) {
    menu.classList.remove("active");
    menuBtn.classList.remove("active");
    body.classList.remove("active");
  }
};
// menu end

// scroll start
let header = document.getElementById("header");
function scrollFunc() {
  if (window.pageYOffset >= 60) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}
window.onscroll = function () {
  scrollFunc();
};
// scroll end

$(function () {
  $(".slider").slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: true,
    dots: false,
    speed: 600,
    fade: false,
    responsive: [
      {
        breakpoint: 1181,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 751,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });
});
/*mobile slider */
function mobileOnlySlider() {
  $(".intro__inner-row").slick({
    infinite: true,
    speed: 300,
    slidesToShow: 2,
    slidesToScroll: 1,
    adaptiveHeight: false,
    arrows: false,
    dots: false,
        responsive: [
      {
        breakpoint: 751,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });
}
if (window.innerWidth < 1180) {
  mobileOnlySlider();
}
function resizeListener(e) {
  if (window.innerWidth < 1180) {
    $(".intro__inner-row").addClass("sliderMob");
    if (!$(".intro__inner-row").hasClass("slick-initialized")) {
      mobileOnlySlider();
    }
  } else {
    $(".intro__inner-row").removeClass("sliderMob");
    if ($(".intro__inner-row").hasClass("slick-initialized")) {
      $(".intro__inner-row").slick("unslick");
    }
  }
}
resizeListener();
$(window).resize(resizeListener);


const filterBtn = document.getElementById("filterBtn");
const filterRow = document.getElementById("filterRow");
const filterList = document.getElementById("filterList");

if(filterBtn) {
    filterBtn.onclick = () => {
        filterRow.classList.toggle("active");
        filterList.classList.toggle("active");
    }
}
