var inputLatitud, inputLongitud, divControlado;

function obtenerGPS(idLatitud, idLongitud, divForm) {
  inputLatitud = idLatitud;
  inputLongitud = idLongitud;
  divControlado = divForm;
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(colocarGPSinputs, errorGPS, {
      enableHighAccuracy: true,
      maximumAge: 0,
      timeout: 30000
    });
  }
  else {
    alert('Tu navegador no soporta geolocalizacion.');
  }
}

function colocarGPSinputs(pos) {
  var latitud = pos.coords.latitude;
  var longitud = pos.coords.longitude;
  $("#" + inputLatitud).val(latitud);
  $("#" + inputLongitud).val(longitud);
  $("#" + divControlado).slideDown();
  $("#" + divControlado + "-ayuda").slideUp();

  $.ajax({
    method: "POST",
    data: { 'usuarioULTIMALATITUD': latitud, 'usuarioULTIMALONGITUD': longitud },
    xhrFields: {
      withCredentials: true
    },
    headers: {
      'Authorization': 'Basic ' + btoa('invitado:invitado')
    },
    url: "registrarUbicacion",
    crossDomain: true
  }).done(function(data) {
    console.log(data);
  });


  // var xhr = new XMLHttpRequest();
  // xhr.open("POST", "registrarPosicion/", true);
  // xhr.withCredentials = true;
  // xhr.setRequestHeader("Authorization", 'Basic ' + btoa('invitado:invitado'));
  // xhr.onload = function () {
  //     console.log(xhr.responseText);
  // };
  // xhr.send();


  //   $.post("registrarPosicion/", { usuarioULTIMALATITUD: latitud, usuarioULTIMALONGITUD: longitud })
  //     .done(function(data) {
  //       console.log(data);
  //     });

}

function errorGPS(errorCode) {
  $("#" + divControlado).slideUp();
  $("#" + divControlado + "-ayuda").slideDown();
  switch (errorCode.code) {
    case 1:
      alert("El sistema necesita la autorización para conocer su ubicación, sino sabe contactar a Soporte TICS." +
        "\r\n\nAhora debemos recargar el sitio para validar nuevamente tu posición geografica.");
      //setTimeout(function() { window.location.reload(); }, 3210);
      break;
    case 2:
      alert("Ubicacion no encontrada en el sistema");
      break;
  }
}
