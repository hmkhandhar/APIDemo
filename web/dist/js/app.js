
// ProductGrid Slider
$(document).ready(function() {
  $(".grid-slider").owlCarousel({
    nav : true,
    dots: false,
    navText:["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
    lazyLoad: true,
    loop:false,
    autoplay:true,
    autoplayTimeout:3500,
    margin: 20,
    responsive:{
        0:{
            items:1,
        },
        480:{
            items:2, 
        },
        992:{
            items:3
        }
    }
  });
});

// Help
$(document).ready(function(){
    $(".helpHeader-toggle").click(function(){
        $(this).parent().toggleClass("active");
    });
});

// Offers
$(document).ready(function(){
    $(".offer-label-toggle").click(function(){
        $(this).parent().toggleClass("active");
    });
});

$(document).ready(function() {
    //$('.navbar').scrollToFixed();
});

// Select 2
$('.select-single').select2();

// Main Height
function manage_height() {
    var w_height = $( window ).height();
    var footer_height = $('.footer').height();
    //var navbar_height = $('.navbar').height();
    var banner_height = $('.banner').height();
    var main_height=w_height-footer_height-banner_height-0;
    $('.main-container').css('min-height',main_height);
}

$( window ).ready(function () {
    manage_height();
});
$( window ).resize(function() {
    manage_height();
});

// Navbar
function openNav() {
    $('html').addClass("open-sidemenu");
    $('.overlay').show();
}
function closeNav() {
    $('html').removeClass("open-sidemenu");
    $('.overlay').hide();
}

// Date/Time Picker (https://eonasdan.github.io/bootstrap-datetimepicker/)
var dateToday = new Date();
$('.datepickershow').datepicker({
  autoclose: true,
  format: "dd-mm-yyyy",
  startDate : dateToday,
});

//Timepicker
$(".timepickershow").timepicker({
   showInputs: false
});

// Modal Button Click
$('.modal-open-click').click(function(){
    setTimeout(function(){ $('body').addClass("modal-open"); }, 400);
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
// Fixed Sidebar
$(document).ready(function() {
    var summaries = $('.sidebar');
    summaries.each(function(i) {
        var summary = $(summaries[i]);
        var next = summaries[i + 1];
    
        summary.scrollToFixed({
            top: 10,
            //marginTop: $('.header').outerHeight(true) + 10,
            limit: function() {
                var limit = 0;
                if (next) {
                    limit = $(next).offset().top - $(this).outerHeight(true) - 10;
                } else {
                    limit = $('.footer').offset().top - $(this).outerHeight(true) - 60;
                }
                return limit;
            },
            zIndex: 999
        });
    });
    
});
/* ********************************************************************************************
   Qty
*********************************************************************************************** */
//$(document).on('click', ".quantity", function () {
//    var ele = $(this).data('qty');
//    var eleChild = $(this).parent('.qty-wrapper').find(".qty-value");
//    var currentVal = parseInt($(eleChild).val());
//
//    if (ele == "prev") { if (currentVal > 0) { currentVal = currentVal - 1; } else { currentVal = 0; } }
//    if (ele == "next") { if (currentVal < 0) { currentVal = 0; } else { currentVal = currentVal + 1; } }
//    $(eleChild).val(currentVal);
//});





$(document).on('click', ".quantity", function () {
    var ele = $(this).data('qty');
    var eleChild = $(this).parent('.qty-wrapper').find(".qty-value");
    var currentVal = parseInt($(eleChild).val());
    var max = eleChild.data('max');
    
    if (ele == "prev") { 
        if (currentVal > 0) { 
            currentVal = currentVal - 1; 
        } else { 
            currentVal = 0; 
        } 
    }
    if (ele == "next") { 
        if (currentVal < 0) { 
            currentVal = 0; 
        } else {

            if(currentVal < max){
                currentVal = currentVal + 1; 
            }
        } 
    }
    if(currentVal==0){
        $(this).parents(':eq(6)').removeClass('active');
    }else{
        $(this).parents(':eq(6)').addClass('active');
    }
    $(eleChild).val(currentVal);
});