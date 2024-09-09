
$(document).ready(function()
{

$(".button-nav-toggle").click(function(){
  $(".main").toggleClass("open");
  $(".menu").toggleClass("open");
});

$(".nav-main li:has(ul)").addClass("has-sub-nav").prepend("<div class=\"sub-toggle\"></div>");

$(".has-sub-nav a").click(function(){
  $(this).parent().addClass("active");
  $(".nav-container").addClass("show-sub");
});

$(".has-sub-nav .back").click(function(){
  $(".nav-container").removeClass("show-sub");
  $(".has-sub-nav").removeClass("active");
});



});



/* navigation */

function ok()
{
$('.down1').slideToggle();
}

function ok1()
{
$('.down2').slideToggle();
}

function ok2()
{
$('.down3').slideToggle();
}

function ok3()
{
$('.down4').slideToggle();
}


function ok4()
{
$('.down5').slideToggle();
}