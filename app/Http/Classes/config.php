<?php

use Illuminate\Support\Facades\Auth;

return array(
	// número máximo de conexiones simultaneas con el servidor
	// del SAT para la descarga de XMLs y Acuses
	'maxDescargasSimultaneas' => 10,

	// Ruta donde serán guardados los archivos descargados
	'rutaDescarga' => 'C:/laragon/www/final/public/storage/contarappv1_descargas/'

);
