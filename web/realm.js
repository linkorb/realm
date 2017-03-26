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
  var selected=false;
  $(".detail").each(function(index) {
    //console.log(this.id);
    if (!selected) {
      selectDetail(this.id);
      selected = true;
    }
  })
});

function selectDetail(detailId) {
  console.log("selecting " + detailId);
  $(".detail").each(function(index) {
    $(this).removeClass('detail-active')
  })
  $(".nav-detail").each(function(index) {
    $(this).removeClass('nav-detail-active')
  })
  $('#' + detailId).addClass('detail-active');
  $('.nav-' + detailId).addClass('nav-detail-active');
}
