function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

function filterFunction() {
  var input, filter, ul, li, a, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  div = document.getElementById("myDropdown");
  a = div.getElementsByTagName("a");
  for (i = 0; i < a.length; i++) {
    txtValue = a[i].textContent || a[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      a[i].style.display = "";
    } else {
      a[i].style.display = "none";
    }
  }
}

$( function() {
  function log( message ) {
    $( "<div>" ).text( message ).prependTo( "#log" );
    $( "#log" ).scrollTop( 0 );
  }

  $( "#inputLocation" ).autocomplete({
    source: function( request, response ) {
      $.ajax( {
        url: '/ajax/location',
        data: {
          term: request.term
        },
        success: function( data ) {
          response( data['location'] );
        }
      } );
    },
    minLength: 2,
    select: function( event, ui ) {
      $('#idLocation').val(ui.item.id);
    }
  } );

} );