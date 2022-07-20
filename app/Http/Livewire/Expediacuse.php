<?php

namespace App\Http\Livewire;

use App\Models\ExpedFiscal;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

//Libreria PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Expediacuse extends Component
{
    //Variables globales
    public $dataacuse;


    //Listener para escuchar los emit de otros componente
    protected $listeners = ['recidataacuse' => 'recidataacuse', 'refreshacuse' => '$refresh', 'uploadacuse' => 'uploadacuse'];

    //Metodo perteneciente al emit donde recibimos la cadena con los datos
    public function recidataacuse($identacuse)
    {
        //Almacenamos el argumento que pasa por nuestra funcion 
        $this->dataacuse = $identacuse;

        //Convertimos el input con el plugin de filepond
        $this->dispatchBrowserEvent('inputfilepond', ['idacuse' => $identacuse]);
    }

    //Metodo para mostrar los archivos cada vez que se suba uno
    public function uploadacuse($data)
    {
        //Convertimos el input con el plugin de filepond
        $this->dispatchBrowserEvent('inputfilepond', ['idacuse' => $data['id']]);
    }

    //Metodo para eliminar los acuses
    public function Eliminar($acuse)
    {
        //Descomponemos la cadena enviada (a un arreglo)
        $iddescompuestos = explode('&', $this->dataacuse);

        //Tipo
        $Tipo = $iddescompuestos[0];

        //Empresa
        $Empresa = $iddescompuestos[1];

        //Mes
        $Mes = $iddescompuestos[2];

        //Año
        $Anio = $iddescompuestos[3];

        //Consultamos lo datos de los volumetricos
        $dataacuse = ExpedFiscal::where(['rfc' => $Empresa])
            ->first();

        //Obtenemos la ruta donde estan los archivos
        $path = 'contarappv1_descargas/' . $Empresa . '/' . $Anio . '/Expediente_Fiscal/' . $Tipo . '/' . $Mes . '/' . $acuse;

        //Eliminamos el dato del arreglo
        $dataacuse->pull('ExpedFisc.' . $Anio . '.' . $Tipo . '.' . $Mes . '.Acuse', $acuse);

        //Elimina el pdf de la carpeta correspondiente
        Storage::disk('public2')->delete($path);

        //Emitimos la accion de cerrar el modal de eliminar
        $this->dispatchBrowserEvent('inputfilepond', ['idacuse' => $this->dataacuse]);
    }

    //Metodo para el reenvio de archivos
    public function ResendAcuse($tipo, $empresa, $rutasftp)
    {
        //Subi archivo al servidor FTP
        //Condicional para identificar los impuestos que se enviara el correo
        if ($tipo == 'Impuestos_Federales' || $tipo == 'Impuestos_Remuneraciones' || $tipo == 'Impuestos_Hospedaje' || $tipo == 'IMSS' || $tipo == 'Impuestos_Estatal') {
            switch ($tipo) {
                case 'Impuestos_Federales':
                    $asunto = 'Impuestos Federales';
                    break;

                case 'Impuestos_Remuneraciones':
                    $asunto = 'Impuestos sobre Remuneraciones';
                    break;

                case 'Impuestos_Hospedaje':
                    $asunto = 'Impuestos de Hospedaje';
                    break;

                case 'IMSS':
                    $asunto = 'IMSS';
                    break;

                case 'DIOT':
                    $asunto = 'DIOT';
                    break;

                case 'Balanza_Mensual':
                    $asunto = 'Balanza Mensual';

                case 'Impuestos_Estatal':
                    $asunto = 'Impuestos Estatal';
                    break;
            }

            //Descomponemos la cadena enviada (a un arreglo)
            $iddescompuestos = explode('&', $this->dataacuse);

            if (!empty($iddescompuestos[4])) {
                $mailinfo = [
                    ['rfc' => $iddescompuestos[1], 'email' => $iddescompuestos[4]],

                    //14. PERE9308105X4 (Copia contabilidad)
                    ['rfc' => $iddescompuestos[1], 'email' => 'contabilidad@jmbcontadores.mx'],

                    //14. PERE9308105X4 (Copia Daniel)
                    ['rfc' => $iddescompuestos[1], 'email' => 'fdaniel_torres@hotmail.com'],

                    //14. PERE9308105X4 (Copia Julio)
                    ['rfc' => $iddescompuestos[1], 'email' => 'juliojet10@hotmail.com'],
                ];
            } else {
                //Arreglo donde alamacenaremos los correos electronico
                $mailinfo = [
                    //CORREOS DE EMPRESAS

                    //1. AHF060131G59
                    ['rfc' => 'AHF060131G59', 'email' => 'servicio.hf@permergas.mx'],

                    //1. AHF060131G60 (Copia contabilidad)
                    ['rfc' => 'AHF060131G59', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //1. AHF060131G60 (Copia Daniel)
                    ['rfc' => 'AHF060131G59', 'email' => 'fdaniel_torres@hotmail.com'],

                    //1. AHF060131G60 (Copia Julio)
                    ['rfc' => 'AHF060131G59', 'email' => 'juliojet10@hotmail.com'],

                    //2. AFU1809135Y4 (Lorena)
                    ['rfc' => 'AFU1809135Y4', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //2. AFU1809135Y4 (Auxiliar)
                    ['rfc' => 'AFU1809135Y4', 'email' => 'auxiliar.5708@outlook.com'],

                    //2. AFU1809135Y4 (Copia contabilidad)
                    ['rfc' => 'AFU1809135Y4', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //2. AFU1809135Y4 (Copia Daniel)
                    ['rfc' => 'AFU1809135Y4', 'email' => 'fdaniel_torres@hotmail.com'],

                    //2. AFU1809135Y4 (Copia Julio)
                    ['rfc' => 'AFU1809135Y4', 'email' => 'juliojet10@hotmail.com'],

                    //3. AIJ161001UD1 (Lorena)
                    ['rfc' => 'AIJ161001UD1', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //3. AIJ161001UD1 (Auxiliar)
                    ['rfc' => 'AIJ161001UD1', 'email' => 'auxiliar.5708@outlook.com'],

                    //3. AIJ161001UD1 (Copia contabilidad)
                    ['rfc' => 'AIJ161001UD1', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //3. AIJ161001UD1 (Copia Daniel)
                    ['rfc' => 'AIJ161001UD1', 'email' => 'fdaniel_torres@hotmail.com'],

                    //3. AIJ161001UD1 (Copia Julio)
                    ['rfc' => 'AIJ161001UD1', 'email' => 'juliojet10@hotmail.com'],

                    //4. AAE160217C36 (Lorena)
                    ['rfc' => 'AAE160217C36', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //4. AAE160217C36 (Auxiliar)
                    ['rfc' => 'AAE160217C36', 'email' => 'auxiliar.5708@outlook.com'],

                    //4. AAE160217C36 (Copia contabilidad)
                    ['rfc' => 'AAE160217C36', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //4. AAE160217C36 (Copia Daniel)
                    ['rfc' => 'AAE160217C36', 'email' => 'fdaniel_torres@hotmail.com'],

                    //4. AAE160217C36 (Copia Julio)
                    ['rfc' => 'AAE160217C36', 'email' => 'juliojet10@hotmail.com'],

                    //5. CDI1801116Y9
                    ['rfc' => 'CDI1801116Y9', 'email' => 'motelpicassocircuito@gmail.com'],

                    //5. CDI1801116Y9 (Copia contabilidad)
                    ['rfc' => 'CDI1801116Y9', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //5. CDI1801116Y9 (Copia Daniel)
                    ['rfc' => 'CDI1801116Y9', 'email' => 'fdaniel_torres@hotmail.com'],

                    //5. CDI1801116Y9 (Copia Julio)
                    ['rfc' => 'CDI1801116Y9', 'email' => 'juliojet10@hotmail.com'],

                    //6. CAJ161001KF6 (Lorena)
                    ['rfc' => 'CAJ161001KF6', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //6. CAJ161001KF6 (Auxiliar)
                    ['rfc' => 'CAJ161001KF6', 'email' => 'auxiliar.5708@outlook.com'],

                    //6. CAJ161001KF6 (Copia contabilidad)
                    ['rfc' => 'CAJ161001KF6', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //6. CAJ161001KF6 (Copia Daniel)
                    ['rfc' => 'CAJ161001KF6', 'email' => 'fdaniel_torres@hotmail.com'],

                    //6. CAJ161001KF6 (Copia Julio)
                    ['rfc' => 'CAJ161001KF6', 'email' => 'juliojet10@hotmail.com'],

                    //7. COB191129AZ2 (Lorena)
                    ['rfc' => 'COB191129AZ2', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //7. COB191129AZ2 (Auxiliar)
                    ['rfc' => 'COB191129AZ2', 'email' => 'auxiliar.5708@outlook.com'],

                    //7. COB191129AZ2 (Copia contabilidad)
                    ['rfc' => 'COB191129AZ2', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //7. COB191129AZ2 (Copia Daniel)
                    ['rfc' => 'COB191129AZ2', 'email' => 'fdaniel_torres@hotmail.com'],

                    //7. COB191129AZ2 (Copia Julio)
                    ['rfc' => 'COB191129AZ2', 'email' => 'juliojet10@hotmail.com'],

                    // //8. CGU111019TF2
                    // ['rfc' => 'CGU111019TF2', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //8. CGU111019TF2 (Copia Daniel)
                    // ['rfc' => 'CGU111019TF2', 'email' => 'fdaniel_torres@hotmail.com'],

                    // //8. CGU111019TF2 (Copia Julio)
                    // ['rfc' => 'CGU111019TF2', 'email' => 'juliojet10@hotmail.com'],

                    // //9. CGC111019330
                    // ['rfc' => 'CGC111019330', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //9. CGC111019330 (Copia Daniel)
                    // ['rfc' => 'CGC111019330', 'email' => 'fdaniel_torres@hotmail.com'],

                    // //9. CGC111019330 (Copia Julio)
                    // ['rfc' => 'CGC111019330', 'email' => 'juliojet10@hotmail.com'],

                    //10. DOT1911294F3 (Lorena)
                    ['rfc' => 'DOT1911294F3', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //10. DOT1911294F3 (Auxiliar)
                    ['rfc' => 'DOT1911294F3', 'email' => 'auxiliar.5708@outlook.com'],

                    //10. DOT1911294F3 (Copia contabilidad)
                    ['rfc' => 'DOT1911294F3', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //10. DOT1911294F3 (Copia Daniel)
                    ['rfc' => 'DOT1911294F3', 'email' => 'fdaniel_torres@hotmail.com'],

                    //10. DOT1911294F3 (Copia Julio)
                    ['rfc' => 'DOT1911294F3', 'email' => 'juliojet10@hotmail.com'],

                    //11. DRO191104EZ0 (Lorena)
                    ['rfc' => 'DRO191104EZ0', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //11. DRO191104EZ0 (Auxiliar)
                    ['rfc' => 'DRO191104EZ0', 'email' => 'auxiliar.5708@outlook.com'],

                    //11. DRO191104EZ0 (Copia contabilidad)
                    ['rfc' => 'DRO191104EZ0', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //11. DRO191104EZ0 (Copia Daniel)
                    ['rfc' => 'DRO191104EZ0', 'email' => 'fdaniel_torres@hotmail.com'],

                    //11. DRO191104EZ0 (Copia Julio)
                    ['rfc' => 'DRO191104EZ0', 'email' => 'juliojet10@hotmail.com'],

                    //12. DRO191129DK5 (Lorena)
                    ['rfc' => 'DRO191129DK5', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //12. DRO191129DK5 (Auxiliar)
                    ['rfc' => 'DRO191129DK5', 'email' => 'auxiliar.5708@outlook.com'],

                    //12. DRO191129DK5 (Copia contabilidad)
                    ['rfc' => 'DRO191129DK5', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //12. DRO191129DK5 (Copia Daniel)
                    ['rfc' => 'DRO191129DK5', 'email' => 'fdaniel_torres@hotmail.com'],

                    //12. DRO191129DK5 (Copia Julio)
                    ['rfc' => 'DRO191129DK5', 'email' => 'juliojet10@hotmail.com'],

                    //13. ERO1911044L4 (Lorena)
                    ['rfc' => 'ERO1911044L4', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //13. ERO1911044L4 (Auxiliar)
                    ['rfc' => 'ERO1911044L4', 'email' => 'auxiliar.5708@outlook.com'],

                    //13. ERO1911044L4 (Copia contabilidad)
                    ['rfc' => 'ERO1911044L4', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //13. ERO1911044L4 (Copia Daniel)
                    ['rfc' => 'ERO1911044L4', 'email' => 'fdaniel_torres@hotmail.com'],

                    //13. ERO1911044L4 (Copia Julio)
                    ['rfc' => 'ERO1911044L4', 'email' => 'juliojet10@hotmail.com'],

                    //14. PERE9308105X4
                    ['rfc' => 'PERE9308105X4', 'email' => 'servicio.chalco@permergas.mx'],

                    //14. PERE9308105X4 (Copia contabilidad)
                    ['rfc' => 'PERE9308105X4', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //14. PERE9308105X4 (Copia Daniel)
                    ['rfc' => 'PERE9308105X4', 'email' => 'fdaniel_torres@hotmail.com'],

                    //14. PERE9308105X4 (Copia Julio)
                    ['rfc' => 'PERE9308105X4', 'email' => 'juliojet10@hotmail.com'],

                    //15. FGA980316918
                    ['rfc' => 'FGA980316918', 'email' => 'servicio.figuergas@permergas.mx'],

                    //15. FGA980316918 (Copia contabilidad)
                    ['rfc' => 'FGA980316918', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //15. FGA980316918 (Copia Daniel)
                    ['rfc' => 'FGA980316918', 'email' => 'fdaniel_torres@hotmail.com'],

                    //15. FGA980316918 (Copia Julio)
                    ['rfc' => 'FGA980316918', 'email' => 'juliojet10@hotmail.com'],

                    //16. GPA161202UG8
                    ['rfc' => 'GPA161202UG8', 'email' => 'servicio.paris@permergas.mx'],

                    //16. GPA161202UG8 (Copia contabilidad)
                    ['rfc' => 'GPA161202UG8', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //16. GPA161202UG8 (Copia Daniel)
                    ['rfc' => 'GPA161202UG8', 'email' => 'fdaniel_torres@hotmail.com'],

                    //16. GPA161202UG8 (Copia Julio)
                    ['rfc' => 'GPA161202UG8', 'email' => 'juliojet10@hotmail.com'],

                    //17. GEM190507UW8
                    ['rfc' => 'GEM190507UW8', 'email' => 'admin@jmbcontadores.mx'],

                    //17. GEM190507UW8 (Copia contabilidad)
                    ['rfc' => 'GEM190507UW8', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //18. GPR020411182 (Lorena)
                    ['rfc' => 'GPR020411182', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //18. GPR020411182 (Auxiliar)
                    ['rfc' => 'GPR020411182', 'email' => 'auxiliar.5708@outlook.com'],

                    //18. GPR020411182 (Copia contabilidad)
                    ['rfc' => 'GPR020411182', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //18. GPR020411182 (Copia Daniel)
                    ['rfc' => 'GPR020411182', 'email' => 'fdaniel_torres@hotmail.com'],

                    //18. GPR020411182 (Copia Julio)
                    ['rfc' => 'GPR020411182', 'email' => 'juliojet10@hotmail.com'],

                    // //19. HRU121221SC2
                    // ['rfc' => 'HRU121221SC2', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //19. HRU121221SC2 (Copia Daniel)
                    // ['rfc' => 'HRU121221SC2', 'email' => 'fdaniel_torres@hotmail.com'],

                    // //19. HRU121221SC2 (Copia Julio)
                    // ['rfc' => 'HRU121221SC2', 'email' => 'juliojet10@hotmail.com'],

                    //20. IAB0210236I7
                    ['rfc' => 'IAB0210236I7', 'email' => 'gcendon@hotmail.com'],

                    //20. IAB0210236I7 (Copia contabilidad)
                    ['rfc' => 'IAB0210236I7', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //20. IAB0210236I7 (Copia Daniel)
                    ['rfc' => 'IAB0210236I7', 'email' => 'fdaniel_torres@hotmail.com'],

                    //20. IAB0210236I7 (Copia Julio)
                    ['rfc' => 'IAB0210236I7', 'email' => 'juliojet10@hotmail.com'],

                    //21. JQU191009699
                    ['rfc' => 'JQU191009699', 'email' => 'admin@jmbcontadores.mx'],

                    //21. JQU191009699 (Copia contabilidad)
                    ['rfc' => 'JQU191009699', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //22. JCO171102SI9 (Lorena)
                    ['rfc' => 'JCO171102SI9', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //22. JCO171102SI9 (Auxiliar)
                    ['rfc' => 'JCO171102SI9', 'email' => 'auxiliar.5708@outlook.com'],

                    //22. JCO171102SI9 (Copia contabilidad)
                    ['rfc' => 'JCO171102SI9', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //22. JCO171102SI9 (Copia Daniel)
                    ['rfc' => 'JCO171102SI9', 'email' => 'fdaniel_torres@hotmail.com'],

                    //22. JCO171102SI9 (Copia Julio)
                    ['rfc' => 'JCO171102SI9', 'email' => 'juliojet10@hotmail.com'],

                    // //23. MEN171108IG6
                    // ['rfc' => 'MEN171108IG6', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //23. MEN171108IG6 (Copia Daniel)
                    // ['rfc' => 'MEN171108IG6', 'email' => 'fdaniel_torres@hotmail.com'],

                    // //23. MEN171108IG6 (Copia Julio)
                    // ['rfc' => 'MEN171108IG6', 'email' => 'juliojet10@hotmail.com'],

                    //24. MAR191104R53 (Lorena)
                    ['rfc' => 'MAR191104R53', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //24. MAR191104R53 (Auxiliar)
                    ['rfc' => 'MAR191104R53', 'email' => 'auxiliar.5708@outlook.com'],

                    //24. MAR191104R53 (Copia contabilidad)
                    ['rfc' => 'MAR191104R53', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //24. MAR191104R53 (Copia Daniel)
                    ['rfc' => 'MAR191104R53', 'email' => 'fdaniel_torres@hotmail.com'],

                    //24. MAR191104R53 (Copia Julio)
                    ['rfc' => 'MAR191104R53', 'email' => 'juliojet10@hotmail.com'],

                    //25. MCA130429FM8
                    ['rfc' => 'MCA130429FM8', 'email' => 'admin@jmbcontadores.mx'],

                    //25. MCA130429FM8 (Copia contabilidad)
                    ['rfc' => 'MCA130429FM8', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //26. MCC140101RV3
                    // ['rfc' => 'MCC140101RV3', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //26. MCC140101RV3 (Copia Daniel)
                    // ['rfc' => 'MCC140101RV3', 'email' => 'fdaniel_torres@hotmail.com'],

                    // //26. MCC140101RV3 (Copia Julio)
                    // ['rfc' => 'MCC140101RV3', 'email' => 'juliojet10@hotmail.com'],

                    //27. MCA130827V4A
                    ['rfc' => 'MCA130827V4A', 'email' => 'admin@jmbcontadores.mx'],

                    //27. MCA130827V4A (Copia contabilidad)
                    ['rfc' => 'MCA130827V4A', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //28. MOP18022474A
                    ['rfc' => 'MOP18022474A', 'email' => 'dotero@thealest.com'],

                    //28. MOP18022474A (Copia contabilidad)
                    ['rfc' => 'MOP18022474A', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //28. MOP18022474A (Copia Daniel)
                    ['rfc' => 'MOP18022474A', 'email' => 'fdaniel_torres@hotmail.com'],

                    //28. MOP18022474A (Copia Julio)
                    ['rfc' => 'MOP18022474A', 'email' => 'juliojet10@hotmail.com'],

                    //29. MOBJ8502058A4
                    ['rfc' => 'MOBJ8502058A4', 'email' => 'admin@jmbcontadores.mx'],

                    //29. MOBJ8502058A4 (Copia contabilidad)
                    ['rfc' => 'MOBJ8502058A4', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //30. PEM180224742 (Lorena)
                    ['rfc' => 'PEM180224742', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //30. PEM180224742 (Auxiliar)
                    ['rfc' => 'PEM180224742', 'email' => 'auxiliar.5708@outlook.com'],

                    //30. PEM180224742 (Copia contabilidad)
                    ['rfc' => 'PEM180224742', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //30. PEM180224742 (Copia Daniel)
                    ['rfc' => 'PEM180224742', 'email' => 'fdaniel_torres@hotmail.com'],

                    //30. PEM180224742 (Copia Julio)
                    ['rfc' => 'PEM180224742', 'email' => 'juliojet10@hotmail.com'],

                    //31. PEMJ7110258J3
                    ['rfc' => 'PEMJ7110258J3', 'email' => 'servicio.ruave@permergas.mx'],

                    //31. PEMJ7110258J3 (Copia contabilidad)
                    ['rfc' => 'PEMJ7110258J3', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //31. PEMJ7110258J3 (Copia Daniel)
                    ['rfc' => 'PEMJ7110258J3', 'email' => 'fdaniel_torres@hotmail.com'],

                    //31. PEMJ7110258J3 (Copia Julio)
                    ['rfc' => 'PEMJ7110258J3', 'email' => 'juliojet10@hotmail.com'],

                    // //32. PML170329AZ9
                    // ['rfc' => 'PML170329AZ9', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //32. PML170329AZ9 (Copia Daniel)
                    // ['rfc' => 'PML170329AZ9', 'email' => 'fdaniel_torres@hotmail.com'],

                    // //32. PML170329AZ9 (Copia Julio)
                    // ['rfc' => 'PML170329AZ9', 'email' => 'juliojet10@hotmail.com'],

                    //33. PERA0009086X3
                    ['rfc' => 'PERA0009086X3', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //33. PERA0009086X3 (Copia Daniel)
                    ['rfc' => 'PERA0009086X3', 'email' => 'fdaniel_torres@hotmail.com'],

                    //33. PERA0009086X3 (Copia Julio)
                    ['rfc' => 'PERA0009086X3', 'email' => 'juliojet10@hotmail.com'],

                    //34. PER180309RB3 (Lorena)
                    ['rfc' => 'PER180309RB3', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //34. PER180309RB3 (Auxiliar)
                    ['rfc' => 'PER180309RB3', 'email' => 'auxiliar.5708@outlook.com'],

                    //34. PER180309RB3 (Copia contabilidad)
                    ['rfc' => 'PER180309RB3', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //34. PER180309RB3 (Copia Daniel)
                    ['rfc' => 'PER180309RB3', 'email' => 'fdaniel_torres@hotmail.com'],

                    //34. PER180309RB3 (Copia Julio)
                    ['rfc' => 'PER180309RB3', 'email' => 'juliojet10@hotmail.com'],

                    //35. RUCE750317I21
                    ['rfc' => 'RUCE750317I21', 'email' => 'servicio.sanjuan@permergas.mx'],

                    //35. RUCE750317I21 (Copia contabilidad)
                    ['rfc' => 'RUCE750317I21', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //35. RUCE750317I21 (Copia Daniel)
                    ['rfc' => 'RUCE750317I21', 'email' => 'fdaniel_torres@hotmail.com'],

                    //35. RUCE750317I21 (Copia Julio)
                    ['rfc' => 'RUCE750317I21', 'email' => 'juliojet10@hotmail.com'],

                    //36. SBE190522I97
                    ['rfc' => 'SBE190522I97', 'email' => 'picasso.churubusco@gmail.com'],

                    //36. SBE190522I97 (Copia contabilidad)
                    ['rfc' => 'SBE190522I97', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //36. SBE190522I97 (Copia Daniel)
                    ['rfc' => 'SBE190522I97', 'email' => 'fdaniel_torres@hotmail.com'],

                    //36. SBE190522I97 (Copia Julio)
                    ['rfc' => 'SBE190522I97', 'email' => 'juliojet10@hotmail.com'],

                    // //37. SGA1905229H3
                    // ['rfc' => 'SGA1905229H3', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //37. SGA1905229H3 (Copia Daniel)
                    // ['rfc' => 'SGA1905229H3', 'email' => 'fdaniel_torres@hotmail.com'],

                    // //37. SGA1905229H3 (Copia Julio)
                    // ['rfc' => 'SGA1905229H3', 'email' => 'juliojet10@hotmail.com'],

                    //38. SGA1410217U4
                    ['rfc' => 'SGA1410217U4', 'email' => 'servicio.azcapotzalco@permergas.mx'],

                    //38. SGA1410217U4 (Copia contabilidad)
                    ['rfc' => 'SGA1410217U4', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //38. SGA1410217U4 (Copia Daniel)
                    ['rfc' => 'SGA1410217U4', 'email' => 'fdaniel_torres@hotmail.com'],

                    //38. SGA1410217U4 (Copia Julio)
                    ['rfc' => 'SGA1410217U4', 'email' => 'juliojet10@hotmail.com'],

                    //39. SGT190523QX8
                    ['rfc' => 'SGT190523QX8', 'email' => 'servicio.tlahuac@permergas.mx'],

                    //39. SGT190523QX8 (Copia contabilidad)
                    ['rfc' => 'SGT190523QX8', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //39. SGT190523QX8 (Copia Daniel)
                    ['rfc' => 'SGT190523QX8', 'email' => 'fdaniel_torres@hotmail.com'],

                    //39. SGT190523QX8 (Copia Julio)
                    ['rfc' => 'SGT190523QX8', 'email' => 'juliojet10@hotmail.com'],

                    // //40. SGX190523KA4
                    // ['rfc' => 'SGX190523KA4', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //40. SGX190523KA4 (Copia Daniel)
                    // ['rfc' => 'SGX190523KA4', 'email' => 'fdaniel_torres@hotmail.com'],

                    // //40. SGX190523KA4 (Copia Julio)
                    // ['rfc' => 'SGX190523KA4', 'email' => 'juliojet10@hotmail.com'],

                    //41. SGX160127MC4
                    ['rfc' => 'SGX160127MC4', 'email' => 'servicio.xola@permergas.mx'],

                    //41. SGX160127MC4 (Copia contabilidad)
                    ['rfc' => 'SGX160127MC4', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //41. SGX160127MC4 (Copia Daniel)
                    ['rfc' => 'SGX160127MC4', 'email' => 'fdaniel_torres@hotmail.com'],

                    //41. SGX160127MC4 (Copia Julio)
                    ['rfc' => 'SGX160127MC4', 'email' => 'juliojet10@hotmail.com'],

                    //42. STR9303188X3
                    ['rfc' => 'STR9303188X3', 'email' => 'servicio.trece@permergas.mx'],

                    //42. STR9303188X3 (Copia contabilidad)
                    ['rfc' => 'STR9303188X3', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //42. STR9303188X3 (Copia Daniel)
                    ['rfc' => 'STR9303188X3', 'email' => 'fdaniel_torres@hotmail.com'],

                    //42. STR9303188X3 (Copia Julio)
                    ['rfc' => 'STR9303188X3', 'email' => 'juliojet10@hotmail.com'],

                    //43. SVI831123632 (Lorena)
                    ['rfc' => 'SVI831123632', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //43. SVI831123632 (Auxiliar)
                    ['rfc' => 'SVI831123632', 'email' => 'auxiliar.5708@outlook.com'],

                    //43. SVI831123632 (Copia contabilidad)
                    ['rfc' => 'SVI831123632', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //43. SVI831123632 (Copia Daniel)
                    ['rfc' => 'SVI831123632', 'email' => 'fdaniel_torres@hotmail.com'],

                    //43. SVI831123632 (Copia Julio)
                    ['rfc' => 'SVI831123632', 'email' => 'juliojet10@hotmail.com'],

                    //44. SCT150918RC9
                    ['rfc' => 'SCT150918RC9', 'email' => 'servicios.carranza@permergas.mx'],

                    //44. SCT150918RC9 (Copia contabilidad)
                    ['rfc' => 'SCT150918RC9', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //44. SCT150918RC9 (Copia Daniel)
                    ['rfc' => 'SCT150918RC9', 'email' => 'fdaniel_torres@hotmail.com'],

                    //44. SCT150918RC9 (Copia Julio)
                    ['rfc' => 'SCT150918RC9', 'email' => 'juliojet10@hotmail.com'],

                    //45. SAJ161001KC6
                    ['rfc' => 'SAJ161001KC6', 'email' => 'motelpicassolerma@gmail.com'],

                    //45. SAJ161001KC6 (Copia contabilidad)
                    ['rfc' => 'SAJ161001KC6', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //45. SAJ161001KC6 (Copia Daniel)
                    ['rfc' => 'SAJ161001KC6', 'email' => 'fdaniel_torres@hotmail.com'],

                    //45. SAJ161001KC6 (Copia Julio)
                    ['rfc' => 'SAJ161001KC6', 'email' => 'juliojet10@hotmail.com'],

                    //46. SPE171102P94 (Lorena)
                    ['rfc' => 'SPE171102P94', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //46. SPE171102P94 (Auxiliar)
                    ['rfc' => 'SPE171102P94', 'email' => 'auxiliar.5708@outlook.com'],

                    //46. SPE171102P94 (Copia contabilidad)
                    ['rfc' => 'SPE171102P94', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //46. SPE171102P94 (Copia Daniel)
                    ['rfc' => 'SPE171102P94', 'email' => 'fdaniel_torres@hotmail.com'],

                    //46. SPE171102P94 (Copia Julio)
                    ['rfc' => 'SPE171102P94', 'email' => 'juliojet10@hotmail.com'],

                    // //47. SCO1905221P2
                    // ['rfc' => 'SCO1905221P2', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //47. SCO1905221P2 (Copia Daniel)
                    // ['rfc' => 'SCO1905221P2', 'email' => 'fdaniel_torres@hotmail.com'],

                    // //47. SCO1905221P2 (Copia Julio)
                    // ['rfc' => 'SCO1905221P2', 'email' => 'juliojet10@hotmail.com'],

                    //48. GMH1602172L8 (Lorena)
                    ['rfc' => 'GMH1602172L8', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //48. GMH1602172L8 (Auxiliar)
                    ['rfc' => 'GMH1602172L8', 'email' => 'auxiliar.5708@outlook.com'],

                    //48. GMH1602172L8 (Copia contabilidad)
                    ['rfc' => 'GMH1602172L8', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //48. GMH1602172L8 (Copia Daniel)
                    ['rfc' => 'GMH1602172L8', 'email' => 'fdaniel_torres@hotmail.com'],

                    //48. GMH1602172L8 (Copia Julio)
                    ['rfc' => 'GMH1602172L8', 'email' => 'juliojet10@hotmail.com'],

                    //49. MGE1602172LA (Lorena)
                    ['rfc' => 'MGE1602172LA', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //49. MGE1602172LA (Auxiliar)
                    ['rfc' => 'MGE1602172LA', 'email' => 'auxiliar.5708@outlook.com'],

                    //49. MGE1602172LA (Copia contabilidad)
                    ['rfc' => 'MGE1602172LA', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //49. MGE1602172LA (Copia Daniel)
                    ['rfc' => 'MGE1602172LA', 'email' => 'fdaniel_torres@hotmail.com'],

                    //49. MGE1602172LA (Copia Julio)
                    ['rfc' => 'MGE1602172LA', 'email' => 'juliojet10@hotmail.com'],

                    // //50. SIC111019LP4
                    // ['rfc' => 'SIC111019LP4', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //50. SIC111019LP4 (Copia Daniel)
                    // ['rfc' => 'SIC111019LP4', 'email' => 'fdaniel_torres@hotmail.com'],

                    // //50. SIC111019LP4 (Copia Julio)
                    // ['rfc' => 'SIC111019LP4', 'email' => 'juliojet10@hotmail.com'],

                    //51. SAE191009dd8
                    ['rfc' => 'SAE191009dd8', 'email' => 'admin@jmbcontadores.mx'],

                    //51. SAE191009dd8 (Copia contabilidad)
                    ['rfc' => 'SAE191009dd8', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //52. SMA180913NK6 (Lorena)
                    ['rfc' => 'SMA180913NK6', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //52. SMA180913NK6 (Auxiliar)
                    ['rfc' => 'SMA180913NK6', 'email' => 'auxiliar.5708@outlook.com'],

                    //52. SMA180913NK6 (Copia contabilidad)
                    ['rfc' => 'SMA180913NK6', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //52. SMA180913NK6 (Copia Daniel)
                    ['rfc' => 'SMA180913NK6', 'email' => 'fdaniel_torres@hotmail.com'],

                    //52. SMA180913NK6 (Copia Julio)
                    ['rfc' => 'SMA180913NK6', 'email' => 'juliojet10@hotmail.com'],

                    //53. SST030407D77J
                    ['rfc' => 'SST030407D77J', 'email' => 'servicio.jet@permergas.mx'],

                    //53. SST030407D77J (Copia contabilidad)
                    ['rfc' => 'SST030407D77J', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //53. SST030407D77J (Copia Daniel)
                    ['rfc' => 'SST030407D77J', 'email' => 'fdaniel_torres@hotmail.com'],

                    //53. SST030407D77J (Copia Julio)
                    ['rfc' => 'SST030407D77J', 'email' => 'juliojet10@hotmail.com'],

                    //53. SST030407D77M
                    ['rfc' => 'SST030407D77M', 'email' => 'servicio.matlazincas@permergas.mx'],

                    //53. SST030407D77M (Copia contabilidad)
                    ['rfc' => 'SST030407D77M', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //53. SST030407D77M (Copia Daniel)
                    ['rfc' => 'SST030407D77M', 'email' => 'fdaniel_torres@hotmail.com'],

                    //53. SST030407D77M (Copia Julio)
                    ['rfc' => 'SST030407D77M', 'email' => 'juliojet10@hotmail.com'],

                    //54. TEL1911043PA (Lorena)
                    ['rfc' => 'TEL1911043PA', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //54. TEL1911043PA (Auxiliar)
                    ['rfc' => 'TEL1911043PA', 'email' => 'auxiliar.5708@outlook.com'],

                    //54. TEL1911043PA (Copia contabilidad)
                    ['rfc' => 'TEL1911043PA', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //54. TEL1911043PA (Copia Daniel)
                    ['rfc' => 'TEL1911043PA', 'email' => 'fdaniel_torres@hotmail.com'],

                    //54. TEL1911043PA (Copia Julio)
                    ['rfc' => 'TEL1911043PA', 'email' => 'juliojet10@hotmail.com'],

                    // //55. TOVF901004DN5
                    // ['rfc' => 'TOVF901004DN5', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //55. TOVF901004DN5 (Copia Daniel)
                    // ['rfc' => 'TOVF901004DN5', 'email' => 'fdaniel_torres@hotmail.com'],

                    // //55. TOVF901004DN5 (Copia Julio)
                    // ['rfc' => 'TOVF901004DN5', 'email' => 'juliojet10@hotmail.com'],

                    //56. VER191104SP3 (Lorena)
                    ['rfc' => 'VER191104SP3', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //56. VER191104SP3 (Auxiliar)
                    ['rfc' => 'VER191104SP3', 'email' => 'auxiliar.5708@outlook.com'],

                    //56. VER191104SP3 (Copia contabilidad)
                    ['rfc' => 'VER191104SP3', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //56. VER191104SP3 (Copia Daniel)
                    ['rfc' => 'VER191104SP3', 'email' => 'fdaniel_torres@hotmail.com'],

                    //56. VER191104SP3 (Copia Julio)
                    ['rfc' => 'VER191104SP3', 'email' => 'juliojet10@hotmail.com'],

                    //57. VPT050906GI8
                    ['rfc' => 'VPT050906GI8', 'email' => 'villadelparquetoluca@hotmail.com'],

                    //57. VPT050906GI8 (Copia contabilidad)
                    ['rfc' => 'VPT050906GI8', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //57. VPT050906GI8 (Copia Daniel)
                    ['rfc' => 'VPT050906GI8', 'email' => 'fdaniel_torres@hotmail.com'],

                    //57. VPT050906GI8 (Copia Julio)
                    ['rfc' => 'VPT050906GI8', 'email' => 'juliojet10@hotmail.com'],

                    //58. VCO990603D84
                    ['rfc' => 'VCO990603D84', 'email' => 'serviciovillada0968@hotmail.com'],

                    //58. VCO990603D84 (Copia contabilidad)
                    ['rfc' => 'VCO990603D84', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //58. VCO990603D84 (Copia Daniel)
                    ['rfc' => 'VCO990603D84', 'email' => 'fdaniel_torres@hotmail.com'],

                    //58. VCO990603D84 (Copia Julio)
                    ['rfc' => 'VCO990603D84', 'email' => 'juliojet10@hotmail.com'],

                    // //59. IAR010220GK5
                    // ['rfc' => 'IAR010220GK5', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //59. IAR010220GK5 (Copia Daniel)
                    // ['rfc' => 'IAR010220GK5', 'email' => 'fdaniel_torres@hotmail.com'],

                    // //59. IAR010220GK5 (Copia Julio)
                    // ['rfc' => 'IAR010220GK5', 'email' => 'juliojet10@hotmail.com'],

                    //60. GRU210504TH9 (Lorena)
                    ['rfc' => 'GRU210504TH9', 'email' => 'lorena_cisneros01@hotmail.com'],

                    //60. GRU210504TH9 (Auxiliar)
                    ['rfc' => 'GRU210504TH9', 'email' => 'auxiliar.5708@outlook.com'],

                    //60. GRU210504TH9 (Copia contabilidad)
                    ['rfc' => 'GRU210504TH9', 'email' => 'contabilidad@jmbcontadores.mx'],

                    //60. GRU210504TH9 (Copia Daniel)
                    ['rfc' => 'GRU210504TH9', 'email' => 'fdaniel_torres@hotmail.com'],

                    //60. GRU210504TH9 (Copia Julio)
                    ['rfc' => 'GRU210504TH9', 'email' => 'juliojet10@hotmail.com'],

                    // //61. GMG2101076W2
                    // ['rfc' => 'GMG2101076W2', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //61. GMG2101076W2 (Copia Daniel)
                    // ['rfc' => 'GMG2101076W2', 'email' => 'fdaniel_torres@hotmail.com'],

                    // //61. GMG2101076W2 (Copia Julio)
                    // ['rfc' => 'GMG2101076W2', 'email' => 'juliojet10@hotmail.com'],

                    //62. JCO2105043Y1
                    ['rfc' => 'JCO2105043Y1', 'email' => 'admin@jmbcontadores.mx'],

                    //62. JCO2105043Y1 (Copia contabilidad)
                    ['rfc' => 'JCO2105043Y1', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //63. SGP210107CE8
                    // ['rfc' => 'SGP210107CE8', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //63. GME210504KW1
                    // ['rfc' => 'GME210504KW1', 'email' => 'contabilidad@jmbcontadores.mx'],

                    // //63. GME210504KW1 (Copia Daniel)
                    // ['rfc' => 'GME210504KW1', 'email' => 'fdaniel_torres@hotmail.com'],

                    // //63. GME210504KW1 (Copia Julio)
                    // ['rfc' => 'GME210504KW1', 'email' => 'juliojet10@hotmail.com'],

                ];
            }

            //Ciclo para pasar por todos los RFC de las empresas
            foreach ($mailinfo as $mailinfo) {
                //Condicional para saber si los RFC coinciden
                if ($mailinfo['rfc'] == $empresa) {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'smtp.ionos.mx'; //servidor saliente stmp
                    $mail->Port = 587; //puerto 465 para ssl
                    $mail->SMTPSecure = "tls";
                    $mail->SMTPAuth = true; //requiere auth
                    $mail->Username = "econt@e-cont.tk";
                    $mail->Password = "C0nT4RapP100";
                    $mail->setFrom('econt@e-cont.tk', 'Econt'); //desde:
                    $mail->addReplyTo('econt@e-cont.tk', 'Econt'); //responder a :
                    $mail->addAddress($mailinfo['email']); //para :

                    //Subimos los archivos a la servidor FTP y eliminamos los archivos en la carpeta local
                    foreach ($rutasftp as $file) {
                        $fileftp = explode('&', basename($file));
                        $fileftp = end($fileftp);

                        $mail->addAttachment($file, $fileftp);    //Optional name
                    }

                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = 'Línea de captura ' . $asunto . ' ' . $empresa; // asunto
                    $mail->Body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
            <html><head></head><body>
            <p>Buen dia, Se envía línea de captura para el pago de ' . $asunto . ' del mes</p>
            <p>Atentamente:</p>
            <p>JMB CONTADORES</p>
            <p>TEL. (55) 5536-0293, (55) 8662-3397</p>
            <p>*Favor de no responder a este correo, ya que se genera automáticamente. Si deseas comunicarte con nosotros hazlo a través de los teléfonos de oficina o al correo contabilidad@jmbcontadores.mx*</p><br>
            <p>La Información contenida en este correo electrónico y anexos es confidencial. Está dirigido únicamente para el uso del individuo o entidad a la que fue dirigida y puede contener información propietaria que no es del dominio público. Si has recibido este correo por error o no eres el destinatario al que fue enviado, por favor notificar al remitente de inmediato y borra este mensaje de tú computadora. Cualquier uso, distribución o reproducción de este correo que no sea por el destinatario queda prohibido.</p></body></html>';
                    $mail->SMTPOptions = array(  //necesario sino me larga error
                        'tls' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );

                    if ($mail->send()) {  //envio el email
                        //Convertimos el input con el plugin de filepond
                        $this->dispatchBrowserEvent('enviosuccessmail', ['idacuse' => $this->dataacuse, 'estado' => 'Exito']);
                    } else {
                        //Convertimos el input con el plugin de filepond
                        $this->dispatchBrowserEvent('enviosuccessmail', ['idacuse' => $this->dataacuse, 'estado' => 'Error']);
                    }
                }
            }
        }
    }

    //Metodo para refrescar los datos de la vista principal
    public function Refresh()
    {
        $this->emit('refreshexpedi');
    }

    public function render()
    {
        return view('livewire.expediacuse');
    }
}
