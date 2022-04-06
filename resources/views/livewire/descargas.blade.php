<div>
  @php
      //Calendario
      //Obtenemos la zona horaria
      date_default_timezone_set('America/Mexico_City');

      //Condicional para saber si el mes y año tiene algun valor
      if (isset($anio) || isset($mes)) {
        //Si tiene algo obtenemos el valor de las variables
        $ym = $anio."-".$mes;
      } else {
        //De lo contario no vamos al mes y año actual
        $ym = date('Y-m');
      }

      //Establecemos el inicio del calendario
      $timestamp = strtotime($ym . '-01');
      if ($timestamp === false) {
        $ym = date('Y-m');
        $timestamp = strtotime($ym . '-01');
      }

      //Obtenemos el dia de hoy
      $today = date('Y-m-j', time());

      //Obtenemos lo dias que tiene el mes
      $day_count = date('t', $timestamp);
      
      // 0:Sun 1:Mon 2:Tue ...
      $str = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));

      //Variables para la creacion del calendario
      $weeks = array();
      $week = '';

      //Campos vacios
      $week .= str_repeat('<td></td>', $str);

      //Ciclo for para llenar los campos con los dias que le pertenece
      for ( $day = 1; $day <= $day_count; $day++, $str++) {
        $date = $ym . '-' . $day;

        //Condicional para saber si el dia creado pertenece al dia de hoy
        if ($today == $date) {
          $week .= '<td class="hoy">' . $day;
        } else {
          $week .= '<td>' . $day;
        }

        //Creamos los demas dias
        $week .= '</td>';
          
        //Condicional para saber si llegamos el final de la semana o mes
        if ($str % 7 == 6 || $day == $day_count) {

          //Condicion para saber si el dia pertenece al final de los dias contados
          if ($day == $day_count) {
            //Agregamos un campo vacio
            $week .= str_repeat('<td></td>', 6 - ($str % 7));
          }

          //Todas las semanas las agregas en un arreglo
          $weeks[] = '<tr>' . $week . '</tr>';

          //Limpiamos la variable para agregar ora semana
          $week = '';
        }
      }
  @endphp

    {{--Contenedor para mantener responsivo el contenido del modulo--}}
    <div class="app-content content">
        <div class="content-wrapper">
          <div class="content-body">
            <section class="invoice-list-wrapper">
                {{--Aqui va el contenido del modulo--}}
                {{--Encabezado del modulo--}}
                <div class="justify-content-start">
                    <h1 style="font-weight: bold">{{ ucfirst(Auth::user()->nombre) }}</h1>
                    <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
                </div>
                
                <br>

            {{--Select para selccionar la empresa (Contadores)--}}
            @empty(!$empresas)
             {{--Mostramos el RFC de la empresa que se selecciona--}}
              <label for="inputState">Empresa: {{$empresa}}</label>
              <select wire:model="rfcEmpresa" id="inputState1" class="select form-control" wire:change="ObtAuth()">
                <option  value="" >--Selecciona Empresa--</option>

                {{--Llenamos el select con las empresa vinculadas--}}
                <?php $rfc=0; $rS=1;foreach($empresas as $fila){
                  echo '<option value="' . $fila[$rfc] . '">'. $fila[$rS] . '</option>';
                }?>
              </select>

              <br>
            @endempty

            {{--Animacion de cargando--}}
            <div wire:loading>
              <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                <div></div>
                <div></div>
              </div>
              <i class="fas fa-mug-hot"></i>&nbsp;Iniciando sesión espere un momento....
            </div>

            {{--Boton de inicio de sesión--}}
            <button class="btn btn-success BtnVinculadas" wire:click="AuthEmpre()">Iniciar sesión</button>

            <br>

            {{--Mensaje de alerta del inicio de sesion--}}
            {{--Mensaje correcto--}}
            <div id="MnsSuccess">
              <br>
              <div class="alert alert-success">
                <label class="Lblmsn"> - </label>
              </div>
            </div>

            {{--Mensaje error--}}
            <div id="MnsDanger">
              <br>
              <div class="alert alert-danger">
                <label class="Lblmsn"> - </label>
              </div>
            </div>

            <br>

            {{--Seccion del calendario--}}
            {{--Fecha de hoy--}}
            <div id="contfechahoy" align="center">
              @php
                //Swich para convertir Int mes en String
                switch ($mes){
                    case 1 :
                        $mes="Enero de ";
                        break;
                    case 2 :
                        $mes="Febrero de ";
                        break;
                    case 3 :
                        $mes="Marzo de ";
                        break;
                    case 4 :
                        $mes="Abril de ";
                        break;
                    case 5 :
                        $mes="Mayo de ";
                        break;
                    case 6 :
                        $mes="Junio de ";
                        break;
                    case 7 :
                        $mes="Julio de ";
                        break;
                    case 8 :
                        $mes="Agosto de ";
                        break;
                    case 9 :
                        $mes="Septiembre de ";
                        break;
                    case 10 :
                        $mes="Octubre de ";
                        break;
                    case 11 :
                        $mes="Noviembre de ";
                        break;
                    case 12 :
                        $mes="Diciembre de ";
                        break;
                    default :
                        $mes="Seleccione un mes";
                        $anio="";
                        break;
                      }
              @endphp

              <h3>{{$mes}} {{$anio}}</h3>
            </div>

            <br>

            {{--Calendario--}}
            {{--Filtros de busqueda--}}
            <div class="form-inline mr-auto">
              {{--Busqueda por mes--}}
              <label for="inputState">Mes</label>
              <select wire:model="mes" id="inputState1"  wire:loading.attr="disabled"  class="select form-control"  >
                  <?php foreach ($meses as $key => $value) {
                      echo '<option value="' . $key . '">' . $value . '</option>';
                  }?>
              </select>
              &nbsp;&nbsp;

              {{--Busqueda por año--}}
              <label for="inputState">Año</label>
              <select wire:loading.attr="disabled" wire:model="anio" id="inputState2" class="select form-control">
                  <?php foreach (array_reverse($anios) as $value) {
                      echo '<option value="' . $value . '">' . $value . '</option>';
                  }?>
              </select>
              &nbsp;&nbsp;
            </div>

            <br>

            {{--Formato calendario--}}
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Domingo</th>
                    <th>Lunes</th>
                    <th>Martes</th>
                    <th>Miercoles</th>
                    <th>Jueves</th>
                    <th>Viernes</th>
                    <th>Sabado</th>
                </tr>
                </thead>
                <tbody>
                  @php
                  foreach ($weeks as $week) {
                      echo $week;
                  }
                  @endphp
                </tbody>
              </table>
          </div>
            </section>
          </div>
        </div>
    </div>
    <script>
      //Mostramos el mensaje de confirmacion de inico de sesion
      window.addEventListener('mnssesion', event => {
        //Alamacenamos el mensaje y el estado en variables
        var mensconfirm = event.detail.mns;
        var statemns = event.detail.state;

        //Funcion para ocultar los mensajes
        function hidemsnconfi(){
          $("#MnsDanger").hide();
          $("#MnsSuccess").hide();
        }

        //Condicional para saber si hay un mensaje
        if(mensconfirm !== null){
          //Switch para saber si es un mensaje satisfactorio o de error
          switch(statemns){
            case 0:
              //Mostramos el mensaje deseado y ocultamos el no deseado
              $("#MnsDanger").show();
              $("#MnsSuccess").hide();

              //Mostramos label con el mensaje
              $(".Lblmsn").text(mensconfirm);

              //Escondemos el mensaje despues de 5 segundos
              setTimeout(() => {
                hidemsnconfi();
              }, 5000);
              break;

            case 1:
              //Mostramos el mensaje deseado y ocultamos el no deseado
              $("#MnsDanger").hide();
              $("#MnsSuccess").show();

              //Mostramos label con el mensaje
              $(".Lblmsn").text(mensconfirm);

              //Escondemos el mensaje despues de 5 segundos
              setTimeout(() => {
                hidemsnconfi();
              }, 5000);
              break;
          }
        }
      });
    </script>
</div>