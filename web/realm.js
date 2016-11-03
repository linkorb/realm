$( document ).ready(function() {
  console.log("ready!");
  $(".realm-value").each(function(index) {
    $(this).mouseover(function() {
      console.log($(this).text());
      $(this).addClass('realm-value-hover')
    });
    $(this).mouseout(function() {
      console.log($(this).text());
      $(this).removeClass('realm-value-hover')
    });
    console.log( index + ": " + $( this ).text() );
  });
});
