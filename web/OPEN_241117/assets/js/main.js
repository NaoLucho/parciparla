$(document).ready(function(){

 // Open menu onclick
 $('.toggleMenu').on('click', function(e) {
   e.preventDefault();
   $('.mainNav').toggleClass('open');
   $(this).addClass('hidden');
 });

 // Close menu
 $('.mainNav .closeMenu, .closeMenu--sm').on('click', function(e){
   e.preventDefault();
   $('.mainNav').removeClass('open');
   $('.toggleMenu').removeClass('hidden');
 });

 darkNav();

});

$(window).resize(function(){ darkNav(); });
$(window).scroll(function(){ darkNav(); });


// Fonction pour passer la navigation avec un fond noir lors du scroll
function darkNav() {
  var heroHeight = $('.page-hero').height(),
      windowTop = $(window).scrollTop(),
      header = $('.header');

  if(windowTop > (heroHeight - 200)) {
    header.addClass('scrollable');
  } else {
    header.removeClass('scrollable');
  }
}
