$(document).ready(function() {
  var caughtId;
  $('.specialButton#1st').click(function() {
    if (typeof id !== 'undefined') {
      caughtId = id;
      id = '23 1132 1566 3246 4323 1031 2859';
    }
  });
  $(document).ajaxComplete(function() {
    if ($('#summary').length > 0) {
      $('#summary tr:nth-of-type(2) td:last-child').html(caughtId);
    }
  });
});