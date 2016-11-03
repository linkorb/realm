$( document ).ready(function() {
  console.log("ready!");
  $(".realm-value").each(function(index) {
    $(this).click(function() {
      $(".realm-value").each(function(index) {
        $(this).removeClass('realm-value-hover')
      })
      $(this).addClass('realm-value-hover');
      return false;
    });
    $(document).mouseout(function() {
      //$(this).removeClass('realm-value-hover')
    });
    console.log( index + ": " + $( this ).text() );
  });

  $(document).click(function() {
    $(".realm-value").each(function(index) {
      $(this).removeClass('realm-value-hover')
    })
  });

});
