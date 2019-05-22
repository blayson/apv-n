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

// $('#inputLocation').keyup(function (e) {
//   $.ajax({
//     url: '/ajax/location',
//     type: 'POST',
//     data: {
//       data: $('#inputLocation').val(),
//       csrf_name: $('.csrfName').val(),
//       csrf_value: $('.csrfValue').val()
//     },
//     success: function (data) {
//       $('.csrfName').val(data.csrf_name);
//       $('.csrfValue').val(data.csrf_value);
//
//       console.log('it worked!');
//       console.log(data['locations'][0]['city']);
//       for(let location in data['locations']) {
//         $('#suggestions').append(`<option> ${location['city']} </option>`)
//       }
//       // let dropdown = document.getElementById("myDropdown").classList.toggle("show");
//       // dropdown.appendChild();
//
//       // var input, filter, ul, li, a, i;
//       // input = document.getElementById("myInput");
//       // filter = input.value.toUpperCase();
//       // div = document.getElementById("myDropdown");
//       // a = div.getElementsByTagName("a");
//       // for (i = 0; i < a.length; i++) {
//       //   txtValue = a[i].textContent || a[i].innerText;
//       //   if (txtValue.toUpperCase().indexOf(filter) > -1) {
//       //     a[i].style.display = "";
//       //   } else {
//       //     a[i].style.display = "none";
//       //   }
//       // }
//     },
//     error: function () {
//       console.log('it failed!');
//     }
//   });
// });

$( function() {
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
  } );
} );