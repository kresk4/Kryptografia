$(document).ready(function() {
  var regExtended = /[^A-Za-z0-9ĄĆĘŁŃÓŚŻŹąćęłńóśżź., _-]/g;
  $('input[name="login"]').on('input', function() {
    $(this).val($(this).val().replace(/[^\w.-]/g, ''));
  })  
  $('input[name="email"]').on('input', function() {
    $(this).val($(this).val().replace(/[^\w.-@]/g, ''));
  })
  $('input[type="password"]').on('input', function() {
    $(this).val($(this).val().replace(/[^\x21-\x7E]/g, ''));
  });  
  $('input[name="receiver"]').on('input', function() {
    $(this).val($(this).val().replace(regExtended, ''));
  })  
  $('input[name="address"]').on('input', function() {
    $(this).val($(this).val().replace(regExtended, ''));
  })
  $('input[name="title"]').on('input', function() {
    $(this).val($(this).val().replace(regExtended, ''));
  })
  $('input[name="id"]').keypress(function(e) {
    var keycode = e.charCode || e.keyCode;
    if (keycode == 32) return false;
  });
  $('input[name="id"]').on('input', function() {
    $(this).val($(this).val().replace(/[^0-9 ]/g, ''));
    var n = $(this).val().length;
    if (n == 2 || n == 7 || n == 12 || n == 17 || n == 22 || n == 27) {
      $(this).val($(this).val() + ' ');
    }
  })
  $('input[name="sum"]').on('input', function() {
    $(this).val($(this).val().replace(/[^0-9.]/g, ''));
  })
});