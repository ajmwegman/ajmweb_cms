  var options = {
    target: '#display',
    success: function () {

      $('#display').fadeIn("slow").delay(5000).fadeOut("slow");

    }
  };

  $('#form').ajaxForm(options);
  // --- end --- //
