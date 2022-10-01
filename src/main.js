'use strict';

// jquery
var $ = require('jquery-browserify');

// ____________________________ GENERAL FUNCTIONS ____________________________
function isMobile() {
  var mobileView = 900;
  return $( window ).width() < mobileView;
}
function rand(min, max) {
  return Math.random() * (max - min) + min;
}
function enabled(obj) {
	return !obj.hasClass('grey');
}
function loading() {
	$('.loading').show();
}
function stopLoading() {
	$('.loading').hide();
}
function close_input() {
	$("#input-overlayer, .loading, #info-bar").hide();
}

// _______________________________ UI EFFECTS ________________________________

$(document).ready(function() {
  // select entry of menu
  var page = window.location.href.split('/#/');
  if (!page[1])
    // select first item
    $("nav ul:first-child li:first-child").addClass('active');
  else
    // select requested item
    $("nav ul li[rel='"+page[1]+"']").addClass('active');

  // open/close menu
  $("#nav-toggle").click(function() {
    $("nav").toggle();
  });

  // menu navigation
  $(document).on('click', 'nav ul li', function(e) {
    // if this menu entry isn't already active
    if (!$(this).hasClass('active')) {
      // request
      var q = $(this).attr('rel')
      // check if the request is invalid
      if (!q) return;
      // redirect
      window.location = '#/' + q;
      // check as active
      $("nav ul li").removeClass('active');
      $(this).addClass('active');
    }
    // hide menu on mobile
    if (isMobile()) $('nav, #input-overlayer').toggle();

    close_input();
  });

  // header shadow
  $(window).scroll(function() {
  	var scroll = $(window).scrollTop();
  	if (scroll > 0)
  		$("header").addClass("active");
  	else
  		$("header").removeClass("active");
  });

  // toggle menu on mobile
  $(document).on('click', 'header', function() {
    if (isMobile()) $('nav, #input-overlayer').toggle();
  });

  // checkbox selection controller
  $(document).on('click', 'table input[type=checkbox].selectAll', function() {
    var status = ($(this).is(":checked") ? true : false);
    $('table input[type=checkbox]:not(.selectAll)').prop('checked',status);
  });
  $(document).on('click', 'table input[type=checkbox]:not(.selectAll)', function() {
    $('table input[type=checkbox].selectAll').prop('checked',false);
  });

  // buttons that shows another panel
  /*$(document).on('click', '[data-panel != ""]', function() {
    var $panel_to_show = $(this).data('panel');

    if ($panel_to_show != null) {

      // display the black behinf the panel
    	$('#input-overlayer').show();

      // closed other panels
      $('panel').hide();

      // idenify and show panel
      var $panel = $('panel[p-name="' + $panel_to_show + '"]');
      $panel.show();
      // show top bar only if required (default)
      if ($panel.attr('p-bar') != "false")
        $('#info-bar').show();
    }
  });*/
});
