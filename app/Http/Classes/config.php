<?php

use Illuminate\Support\Facades\Auth;

$rfc = Auth::user()->RFC;

return array(
	// número máximo de conexiones simultaneas con el servidor
	// del SAT para la descarga de XMLs y Acuses
	'maxDescargasSimultaneas' => 25,

	// Ruta donde serán guardados los archivos descargados
	'rutaDescarga' => 'C:/contarappv1_descargas/descargas/1DescargasManuales/'.$rfc.'/'
);
