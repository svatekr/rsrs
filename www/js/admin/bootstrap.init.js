$(function () {

  $('[data-toggle="tooltip"]').tooltip();
  $('[data-toggle="popover"]').popover();
  $(':checkbox.bootstrap').checkboxpicker({
    html: true,
    offLabel: '',
    onLabel: '',
    switchAlways: 1
  });

  $(document).ready(function () {
    $(".navbar .navbar-nav").find(".active").removeClass("active");
    $('.nav a[href="' + this.location.pathname + '"]').parent().addClass('active');
    $('.nav a[href="' + this.location.pathname + '"]').parent().parent().parent().addClass('active');
  });

  $('ul.nav li.dropdown').hover(function() {
    $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(200);
  }, function() {
    $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(200);
  });

});
