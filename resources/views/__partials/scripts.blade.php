<!-- plugins:js -->
<script src="{{asset('public/staradmin/assets/vendors/js/vendor.bundle.base.js')}}"></script>
<script src="{{asset('public/staradmin/assets/vendors/js/vendor.bundle.addons.js')}}"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="{{asset('public/staradmin/assets/js/shared/off-canvas.js')}}"></script>
<script src="{{asset('public/staradmin/assets/js/shared/hoverable-collapse.js')}}"></script>
<script src="{{asset('public/staradmin/assets/js/shared/misc.js')}}"></script>
<script src="{{asset('public/staradmin/assets/js/shared/settings.js')}}"></script>
<script src="{{asset('public/staradmin/assets/js/shared/todolist.js')}}"></script>
<!-- endinject -->
<script src="{{ asset('public/leaflet/leaflet.js') }}"></script>

<script>
  $(document).ready(function () {
      $fecha = new Date();
      $year = $fecha.getFullYear();
      $month = $fecha.getMonth() + 1;
      $day = $fecha.getDate();
      $fechaNew = null;

      if ($day.toString().length == 1){
          $fechaNew = $year + '-' + $month + '-' + "0" + $day;

      } else {
          $fechaNew = $year + '-' + $month + '-' + $day;
      }

      $('#fecha').val($fechaNew);
      $('#fechaD1').val($fechaNew);
      $('#fechaD2').val($fechaNew);
      $('#fechaAgenda').val($fechaNew);
      $('#fechapagoedit').val($fechaNew);

      $('#avisos-check-all').click(function () {
        if ( $(this).is(':checked') ){
            $('.check-avisos').prop("checked", true);
        }
        else{
            $('.check-avisos').prop("checked", false);
        }
      });
      $('#borrar_masivo').click(function () {
        var form = $('<form id="form_temp_delete" action="{{route('aviso.eliminar.all')}}" method="post"></form>');
        form.append('<input type="hidden" name="agenda_id" value="'+$('#agenda_id').val()+'">');
        $('.check-avisos').each(function () {
          if ( $(this).is(':checked') ){
            form.append('<input type="checkbox" class="check-avisos" name="avisos[]" value="'+$(this).val()+'" checked>');
          }
        });
        $('#form-hidden').append(form);
        $('#form_temp_delete').submit();
      });

      $('#btnIndicador').on('click', function () {
        let fecha = $('#fecha').val();
        let gestor_filtro = $('#gestor_filtro').val();
        let delegacion_filtro = $('#delegacion_filtro').val();
        let estados_filtro = $('#estados_filtro').val();
        dashboard.getAvancePorGestor(fecha, gestor_filtro, delegacion_filtro, estados_filtro);
        dashboard.getAvanceDiario(fecha, gestor_filtro, delegacion_filtro);
        dashboard.getPointMapGestores(fecha, gestor_filtro, delegacion_filtro, estados_filtro);
      });
  });
</script>

<script>
  var dashboard = (function () {
    function getAvancePorGestor(fecha, gestor_filtro, delegacion_filtro, estados_filtro) {
      var loader = $('#dash-loader-avance-diario');
      loader.show();
      var box = $('#dash-avance-gestor');
      box.hide();
      var request = $.ajax({
        url: "{{route('admin.dashboard.getAvancePorGestor')}}",
        method: "POST",
        data: {
            'fecha': fecha,
            'gestor_filtro': gestor_filtro,
            'delegacion_filtro': delegacion_filtro,
            'estados_filtro': estados_filtro
        },
        beforeSend: function() {

        }
      });
      request.done(function (response) {
        var tabla = $("#dash_tabla_gestores tbody");
        var json = response.gestores;
        var content = '', colorBar = 'danger', porcentaje = 0;
        for (var i = 0; i < json.length; i++) {
          porcentaje = Math.round((100 * json[i].realizados) / (json[i].pendientes + json[i].realizados));
          if(porcentaje < 20){
            colorBar = 'danger';
          } else if(porcentaje >= 20 && porcentaje < 50){
            colorBar = 'warning';
          } else if(porcentaje >= 50 && porcentaje < 70){
            colorBar = 'info';
          } else if(porcentaje >= 70 && porcentaje < 100){
            colorBar = 'primary';
          } else if(porcentaje == 100){
            colorBar = 'success';
          }
          content += '<tr>' +
                      '<td>' + json[i].nombre + '</td>' +
                      '<td>' + json[i].realizados + '</td>' +
                      '<td>' + json[i].pendientes + '</td>' +
                      '<td>' +
                        '<div class="progress">' +
                          '<div class="progress-bar bg-' + colorBar + '" role="progressbar" style="width: ' + porcentaje + '%" aria-valuenow="' + porcentaje + '" aria-valuemin="0" aria-valuemax="100"></div>' +
                        '</div>' +
                        porcentaje + '%' +
                      '</td>';
        }
        if(json.length > 0){
          tabla.html(content);
        } else{
          tabla.html('');
        }
        loader.hide();
        box.show();
      });
    }

    function getAvanceDiario(fecha, gestor_filtro, delegacion_filtro) {
      var loader = $('#dash-loader-avance-gestor');
      loader.show();
      var box = $('#dash-avance-diario');
      box.hide();
      var request = $.ajax({
        url: "{{route('admin.dashboard.getAvanceDiario')}}",
        method: "POST",
        data: {
            'fecha': fecha,
            'gestor_filtro': gestor_filtro,
            'delegacion_filtro': delegacion_filtro
        },
        beforeSend: function() {

        }
      });
      request.done(function (response) {
        var tabla = $("#dash_tabla_gestores tbody");
        var pendientes = response.pendientes;
        var resueltos = response.resueltos;
        $('#contP').html(pendientes + "<span class='mdi mdi-thumb-down' style='color:#35abde;'></span>");
        $('#contR').html(resueltos + "<span class='mdi mdi-thumb-up' style='color:#95de6b;'></span>");
        loader.hide();
        box.show();
      });
    }

    function getPointMapGestores(fecha, gestor_filtro, delegacion_filtro, estados_filtro) {
      var loader = $('#dash-loader-mapa-gestor');
      loader.show();
      var box = $('#dash-mapa-gestor');
      box.hide();
      mapDashboard.eachLayer(function (layer) {
          mapDashboard.removeLayer(layer);
      });
      L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
          '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
          'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        id: 'mapbox.streets'
      }).addTo(mapDashboard);
      var request = $.ajax({
        url: "{{route('admin.dashboard.getPointMapGestores')}}",
        method: "POST",
        data: {
            'fecha': fecha,
            'gestor_filtro': gestor_filtro,
            'delegacion_filtro': delegacion_filtro,
            'estados_filtro': estados_filtro
        },
        beforeSend: function() {

        }
      });
      request.done(function (response) {
        var json = response.gestores;
        var size = json.length;
        for (var i = 0; i < size; i++) {
          if(json[i].lat != null){
            L.marker([json[i].lat, json[i].lon]).addTo(mapDashboard)
              .bindPopup("<b>" + json[i].nombre + "</b>");
              //.openPopup();
            L.circle([json[i].lat, json[i].lon], 20, {
              color: 'red',
              fillColor: '#f03',
              fillOpacity: 0.5
            }).addTo(mapDashboard);
          }
        }
        loader.hide();
        box.show();
      });
    }
    return {
      getAvancePorGestor: function(fecha, gestor_filtro, delegacion_filtro, estados_filtro){
          getAvancePorGestor(fecha, gestor_filtro, delegacion_filtro, estados_filtro);
      },
      getAvanceDiario: function(fecha, gestor_filtro, delegacion_filtro){
          getAvanceDiario(fecha, gestor_filtro, delegacion_filtro);
      },
      getPointMapGestores: function(fecha, gestor_filtro, delegacion_filtro, estados_filtro){
          getPointMapGestores(fecha, gestor_filtro, delegacion_filtro, estados_filtro);
      }
    };
  })();

  var visitasMap = (function () {
    function getPointMapVisita(fecha, gestor_id) {
      $('#mensaje').hide();
      var loader = $('#geo-loader-ruta');
      loader.show();
      var box = $('#geo-ruta');
      box.hide();
      mapVisitas.eachLayer(function (layer) {
          mapVisitas.removeLayer(layer);
      });
      L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
          '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
          'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        id: 'mapbox.streets'
      }).addTo(mapVisitas);
      var request = $.ajax({
        url: "{{route('admin.avisos.getPointMapVisita')}}",
        method: "POST",
        data: {
            'fecha': fecha,
            'gestor_id': gestor_id
        },
        beforeSend: function() {

        }
      });
      request.done(function (response) {
        var json = response.puntos;
        var size = json.length;
        var latlngs = [];
        for (var i = 0; i < size; i++) {
          L.marker([json[i].latitud, json[i].longitud]).addTo(mapVisitas)
            .bindPopup("NIC:<b>" + json[i].nic + "</b><br>DIRECCION:<b>" + json[i].direccion + "</b><br>CLIENTE:<b>" + json[i].cliente + "</b><br>ORDEN:<b>" + json[i].orden_realizado + "</b>");
            //.openPopup();
          if(i == 0){
            L.circle([json[i].latitud, json[i].longitud], 60, {
              color: 'green',
              fillColor: '#2BEC0C',
              fillOpacity: 0.5
            }).addTo(mapVisitas);
          } else if(i == (size - 1)){
            L.circle([json[i].latitud, json[i].longitud], 60, {
              color: 'red',
              fillColor: '#f03',
              fillOpacity: 0.5
            }).addTo(mapVisitas);
          }
          latlngs.push([json[i].latitud, json[i].longitud]);
        }
        if(size > 0){
          var polyline = L.polyline(latlngs, {color: 'red'}).addTo(mapVisitas);
          // zoom the map to the polyline
          mapVisitas.fitBounds(polyline.getBounds());
        } else{
          $('#mensaje').show();
        }
        loader.hide();
        box.show();
      });
    }
    return {
      getPointMapVisita: function(fecha, gestor_id){
          getPointMapVisita(fecha, gestor_id);
      }
    };
  })();
</script>
