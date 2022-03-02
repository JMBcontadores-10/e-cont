<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use DateTimeImmutable;
use PhpCfdi\CfdiSatScraper\QueryByFilters;
use PhpCfdi\CfdiSatScraper\Filters\Options\ComplementsOption;
use PhpCfdi\CfdiSatScraper\Filters\DownloadType;
use PhpCfdi\CfdiSatScraper\Filters\Options\StatesVoucherOption;
use PhpCfdi\CfdiSatScraper\Filters\Options\RfcOption;
use PhpCfdi\CfdiSatScraper\Contracts\CaptchaResolverInterface;

use PhpCfdi\CfdiSatScraper\ResourceType;
use PhpCfdi\CfdiSatScraper\SatScraper;
use PhpCfdi\CfdiSatScraper\SatSessionData;

class DescargascfdiController extends Controller
{





    public function index()
    {
       
/** @var CaptchaResolverInterface $captchaResolver */
$satScraper = new SatScraper(new SatSessionData('rfc', 'ciec', $captchaResolver));

$query = new QueryByFilters(new DateTimeImmutable('2019-03-01'), new DateTimeImmutable('2019-03-31'));
$list = $satScraper->listByPeriod($query);

// impresión de cada uno de los metadata
foreach ($list as $cfdi) {
    echo 'UUID: ', $cfdi->uuid(), PHP_EOL;
    echo 'Emisor: ', $cfdi->get('rfcEmisor'), ' - ', $cfdi->get('nombreEmisor'), PHP_EOL;
    echo 'Receptor: ', $cfdi->get('rfcReceptor'), ' - ', $cfdi->get('nombreReceptor'), PHP_EOL;
    echo 'Fecha: ', $cfdi->get('fechaEmision'), PHP_EOL;
    echo 'Tipo: ', $cfdi->get('efectoComprobante'), PHP_EOL;
    echo 'Estado: ', $cfdi->get('estadoComprobante'), PHP_EOL;
}

// descarga de cada uno de los CFDI, reporta los descargados en $downloadedUuids
$downloadedUuids = $satScraper->resourceDownloader(ResourceType::xml(), $list)
    ->setConcurrency(50)                            // cambiar a 50 descargas simultáneas
    ->saveTo('/storage/downloads');                 // ejecutar la instrucción de descarga
echo json_encode($downloadedUuids);





       

        return view('descargascfdi');


    }

}


       
    
