<?php

namespace App\Http\Controllers;

use App\Models\XmlE;
use App\Models\XmlR;
use App\Models\Prueba;
use App\Models\MetadataR;
use DirectoryIterator;
use PhpCfdi\CfdiToJson\JsonConverter;
use PhpCfdi\CfdiCleaner\Cleaner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Scriptp extends Controller
{
    public function cfdi_to_json(string $contents) : string
    {
        $document = new \DOMDocument();
        @$document-> loadXML($contents);

        $factory = new \PhpCfdi\CfdiToJson\Factory();
        $converter = $factory->createConverter();
        $node = $converter->convertXmlDocument($document);

        return \json_encode($node->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    }

    public function xmlLeer()
    {

        set_time_limit(36000);
        $num = 0;
        $rfcs = [
            // '1',
            'AHF060131G59',
            'AFU1809135Y4',
            'AIJ161001UD1',
            'AAE160217C36',
            'CDI1801116Y9',
            'COB191129AZ2',
            'DOT1911294F3',
            'DRO191104EZ0',
            'DRO191129DK5',
            'ERO1911044L4',
            'PERE9308105X4',
            'FGA980316918',
            'GPA161202UG8',
            'GEM190507UW8',
            'GPR020411182',
            'HRU121221SC2',
            'IAB0210236I7',
            'JQU191009699',
            'JCO171102SI9',
            'MEN171108IG6',
            'MAR191104R53',
            'MCA130429FM8',
            'MCA130827V4A',
            'MOP18022474A',
            'MOBJ8502058A4',
            'PEM180224742',
            'PEMJ7110258J3',
            'PML170329AZ9',
            'PERA0009086X3',
            'PER180309RB3',
            'RUCE750317I21',
            'SBE190522I97',
            'SGA1905229H3',
            'SGA1410217U4',
            'SGT190523QX8',
            'SGX190523KA4',
            'SGX160127MC4',
            'STR9303188X3',
            'SVI831123632',
            'SCT150918RC9',
            'SAJ161001KC6',
            'SPE171102P94',
            'SCO1905221P2',
            'GMH1602172L8',
            'MGE1602172LA',
            'SAE191009dd8',
            'SMA180913NK6',
            'SST030407D77',
            'TEL1911043PA',
            'TOVF901004DN5',
            'VER191104SP3',
            'VPT050906GI8',
            'VCO990603D84',
            'IAR010220GK5',
        ];
        foreach ($rfcs as $e) {
            $meses = [
                // '1.Enero',
                // '2.Febrero',
                // '3.Marzo',
                // '4.Abril',
                // '5.Mayo',
                // '6.Junio',
                // '7.Julio',
                '8.Agosto',
                // '9.Septiembre',
                // '10.Octubre',
                // '11.Noviembre',
                // '12.Diciembre',
            ];
            foreach ($meses as $m) {
                $rutas =
                [
                    'Emitidos',
                    'Recibidos'
                ];
                foreach ($rutas as $r) {
                    $num++;
                    $n = 0;
                    $ruta = "C:/laragon/www/contarappv1/public/storage/contarappv1_descargas/$e/2021/Descargas/$m/$r/XML";
                    $dir = new DirectoryIterator($ruta);
                    echo "$num - $ruta <br><br>";
                    foreach ($dir as $fileinfo) {
                        $fileName = $fileinfo->getFilename();
                        $fileExt = $fileinfo->getExtension();
                        $fileBaseName = $fileinfo->getBasename(".$fileExt");
                        $filePathname = $fileinfo->getPathname();
                        echo $filePathname;
                        echo "<br>";
                        if (!$fileinfo->isDot()) {
                            ++$n;

                            $contents = file_get_contents($filePathname);
                            $cleaner = Cleaner::staticClean($contents);

                            $script = new Scriptp;
                            $cfdi = $script->cfdi_to_json($cleaner);
                            // $json = JsonConverter::convertToJson($cfdi);
                            $array = json_decode($cfdi, true);

                            if ($r == 'Recibidos') {

                                XmlR::where(['UUID' => $fileBaseName])
                                    ->update(
                                        $array,
                                        ['upsert' => true]
                                    );
                            } else {
                                XmlE::where(['UUID' => $fileBaseName])
                                    ->update(
                                        $array,
                                        ['upsert' => true]
                                    );
                            }
                        }
                    }
                }
            }
        }
    }

    public function xmlborrar()
    {
        $cfdi = [
            '29D9BC93-D405-4530-B926-8070D478DFA5',
            '9F049FBA-E2FD-4450-A39D-8070D478D3BD',
            '7C22FB32-6FF9-4345-A678-8070D478B827',
            '8206F29D-0D92-484D-80C2-8070D47834AB',
            'FFFD55FB-27E3-4EB2-9DAD-8070D478E87B',
            'DD476A9D-98F1-4A73-A70F-8070D478B811',
            '906BA266-3DBA-4770-9269-8070D4783FEA',
            '2056E9C8-B555-47A1-9BC9-8070D478733D',
            '42DF0D1C-2084-42C4-B14B-8070D478A257',
            '9CFB02BC-00A8-41A1-8FED-8070D478DFDF',
            '0EA28CD4-F6C8-4F6B-A1EC-8070D4781210',
            '345F913B-2547-4D99-8B9A-8070D478CE31',
            'CA6CD044-8F39-4174-85D3-8070D478CA27',
            '2ED398FF-EC58-42B2-B6C8-8070D478E710',
            'CE2BB531-4650-4AF4-B717-8070D478F7B7',
            '6B35071A-B22B-4673-9F06-8070D47827E1',
            '12E1ABBF-5AA0-4B36-9EF7-8070D478EADC',
            '40F8DA04-085A-46C7-8902-8070D4787698',
            '8130FF0C-8EBA-483C-82E5-8070D478C299',
            'A667D1A6-79C4-4722-B3E2-8070D4784183',
            '76EE4329-1D02-44C4-80E2-8070D478DC19',
            '157ACEBF-DF4F-4B52-964C-8070D478086F',
            'D6ED2F41-4CC5-4216-89E7-8070D4783B1B',
            '30F5F1CB-13AC-4C2D-9726-F8CEA0328638',
            '61C3EFF6-7AE3-45A7-976D-DDEB9DA3C5CC',
            '8628989D-14A7-4C1D-86D8-F39CFEE62159',
            'F394EBAF-7105-4F74-A5B6-56D1B6D53165',
            'D8F16C14-C634-41D6-963C-EE05B2BE9F17',
            'A1DA8058-D8A9-49C3-A5D4-82A7E9E915E7',
            'A3B0C51C-2340-45E6-A2E6-6DA12DA1FA91',
            '685234A4-E093-4F27-8C6E-88570D29C944',
            '0CAE3540-D9E0-4BD5-9D37-5E889D2DA806',
            // '024D2635-EDD6-470E-A99E-D15F1E886FFD',
            // '767A5DE7-ECFD-4F76-BCAE-9D3F1DD78A87',
            // '60942223-5B60-4925-B991-1EC28A4FBFE6',
            // '62740B8B-2E42-40CC-90D4-EA441703790C',
            // '23D9D2F0-61AA-4164-9C20-98E1C5B5A013',
            // '2ACCC53A-2973-4F41-914F-5CCAEB723427',
            // '1C89A261-1AC8-471E-9941-2509CC85C820',
            // 'E659DA36-CC28-4627-B9BF-242AF6BEE558',
            // '2EAB7747-58A4-4786-9758-3574642AEB1B',
            // '12235E4D-AA48-4ACB-9951-C42B858F0B60',
            // 'A8050050-3572-4C10-A10D-214AE15FF83B',
            // '9D50E082-1A91-4495-A58B-99912D9B356B',
            // 'B1C9E224-245F-6143-AFCF-B57EF10CBEF3',
            // 'C23A605A-9ADF-6844-B223-14162E388E0B',
            // '32EA5677-AA34-6044-86D9-5A579F8A31E0',
            // '2D5698AD-61EE-2B4C-9530-04D2F01ABB9E',
            // 'C1BCD0AF-FD57-674E-81F3-4607FBE4F8C8',
            // '5A025069-E137-1F47-9B73-7D2411900D4D',
            // 'A4256DC9-FCD5-1B42-B680-8F829C162CC2',
            // 'C0D59F23-C9E3-694A-A986-BCC046A84DF0',
            // 'D10F056A-4EB7-5546-8EE7-57B99D5989AD',
            // 'B3D17C39-440D-6745-9E4C-D2CDFDA7311D',
            // 'E0F381E6-A89B-D744-BC05-5BC8A02C2EE5',
            // '3E50E736-5459-CC4F-AA2C-C134CFAED3C6',
            // '323FA4F9-0295-BA4C-B6A8-58902D09776A',
            // 'C763C180-E6B5-B146-90E2-DB3F6B072545',
            // 'A71DCA03-DD78-D541-85CF-B9C998B0B339',
            // 'A48C50BB-FDE5-4E0D-86FD-CA397386EE56',
            // '554953F0-990A-416C-8EF4-5D02A6FDE608',
            // '6B438534-0060-4F13-AD9E-D06DB27EBF0C',
            // 'C7CE23E2-35D8-4759-BBE1-1BF84FA1984E',
            // 'E2E72949-3C9A-4E35-8503-A551E21C7D46',
            // '96A4578B-BF55-4E87-8242-A39D77534521',
            // 'C9767AD3-37D7-4F29-BEEE-1DEA4AAD2DB5',
            // '44B11574-2463-4A0E-98F2-CA979A527341',
            // 'BAAB14A9-FFD2-49E8-8348-6EC123703826',
            // '822AC085-48CF-4D8B-8EB2-529F50CAC215',
            // 'B936A874-115E-4178-9E1D-C4B77A0A9B26',
            // 'D270DE14-FA0C-470A-B76D-F89AC582DC87',
            // 'C27B1987-5717-4700-BFE3-BE021BD79FCF',
            // 'A52A6B63-EFF5-4F9E-89F7-17FF0253FB4C',
            // 'E2AB796C-95AC-4788-A886-343874141635',
            // '207DFC63-7476-4313-8B17-24681A2D315A',
            // 'C19BAA8B-B1FB-444F-9389-32E192A93E00',
            // '5FC10897-5D79-4568-A6AD-3976AD9486A9',
            // 'E139EBAE-5B6C-4D17-A8E7-A241FE752FC4',
            // 'C7F7D9AA-FD5D-435F-A0B0-9BCAC043E912',
            // 'DCB90C74-4010-4158-9309-8E1CA01796CD',
            // 'CE6FCD7D-FC3C-4CBD-A624-8F034FD10280',
            // '159B8C4E-9A6F-422B-9FF2-F703DE24EEAA',
            // '12C3A3D5-5906-46AA-9A28-9059005C4EDA',
            // 'ECC157FC-E3F9-44C1-9AFE-A862F0468919',
            // 'A96945F4-BD1D-46C2-96AD-94452B4D6FAB',
            // 'E42F5E81-CAB8-46B2-9D1A-DB070A330BEB',
            // '14381513-F794-4072-B28D-2081C5CA4578',
            // '4FE2956D-F30C-4099-A03A-87D157A61204',
            // 'D1B10EA2-4DB7-4882-B795-BA42AF2D3472',
            // '315B2A7A-D7F5-47F4-939C-03035824B52F',
            // '2360D8D7-2453-4DE4-A0FB-DF479D31F6A8',
            // '2B7EC2B4-DD88-4650-9C1A-B2440DBEFC11',
            // '9945086D-326A-4F1C-8A39-B22363D402EC',
            // '632BF4AD-FA90-488E-8269-D9850D065B1F',
            // '2C8427DA-868E-4DD5-96BE-76011BD42C96',
            // '6F4D28C0-51DE-46FA-BF3A-B1F8DC0009A1',
            // 'C3E74880-C4B5-43C6-B5CE-2506D17A05F9',
            // '5E04A0A4-1D79-4C32-9C3C-B8BC2B735217',
            // 'EFA8B5A6-23CA-4F2F-9631-28F5800CB54C',
            // 'D1CC8821-CA90-4F76-873C-4E9BC553328D',
            // '01B249CB-F570-4471-961D-A6B14E8F10A5',
            // 'D201D8FF-9E46-4C08-A49C-BAB55EB23FA5',
            // '670BE533-9751-4A5B-BB74-63A969742521',
            // 'F9C593CC-87FE-4E0F-BBAF-0BF66A986313',
            // 'D715AE1B-D903-4399-855A-9FD82AC944D4',
            // '3A598ED1-5218-4FE4-BD57-9F641254DC4F',
            // '25B82DA5-34BE-4499-8D8B-3F73F82251B5',
            // '8D4FA637-434F-4D3F-9F83-E07675375511',
            // '813FEA94-DBD8-4CF4-8F1A-2FC7E588806E',
            // 'DC90546E-A05A-4CF8-B820-03FEA9898724',
            // 'E0D7B737-2B22-48CB-B973-8269DCCC899F',
            // '35640B47-30FD-4ACB-9E1C-150858C2E067',
            // '8B139384-26BA-42F3-9494-1B17B017CDC6',
            // '83D35396-DFD8-46EE-95C4-3B75F699458A',
            // '7E8B604C-2362-49B7-8E19-102CB0F4C93F',
            // '059E9467-6F1A-4BA6-92A7-76DE242E4517',
            // '8CF0EEF3-47AD-453D-96C3-F79FDBA6494A',
            // '1473D565-A0C4-4E72-B1FF-F8E8BF984100',
            // 'AC4DB4D3-CFF0-4117-95B5-6AF63C1A9FD8',
            // '0D7DCF3A-6552-4A4A-9324-EC9C3230F202',
            // '5EA6590A-561B-430D-B51D-5CE5A21E47D9',
            // '488B1669-987F-42D6-9A9C-B885DDC5847F',
            // '1E7E1618-29F7-43D5-8202-854262B3C6E5',
            // 'FECE91D6-425A-4AB1-9654-86ED66E7C45E',
            // 'A74D04F0-A075-48F8-8097-16145E434F63',
            // '15CA0DB7-9449-4D28-B5D3-DE4A80BC7A21',
            // 'B2AC7C7C-3725-478A-83BA-8B7258C67819',
            // 'E48E126B-8819-4AE9-9ADD-37E85190D0C1',
            // '0386E67D-D72A-4C86-B120-5F290E8BF853',
            // '5CA87EB1-550D-43BC-ABA8-E16D836AC44A',
            // 'BB1F2B75-8DDD-486A-971C-BEDAED25D632',
            // '10A8978B-01C9-4046-BE0A-B7F3617F5684',
            // '1D246251-B7EF-4C10-A058-F1A15E748E24',
            // '4F4F54C9-0AEF-4E0A-9B4D-34541BA6C181',
            // '5F021185-4358-469C-AC03-2C6DB0D9A2CE',
            // 'F1FD058B-3533-49AD-9343-227B60E15A14',
            // '8FEEB5FE-6E3A-4B5B-8ABA-C4C3604EF304',
            // '8187E4B9-1BDC-47C7-904C-87F53943E73F',
            // '6C56166C-593E-4429-A766-D4EEC6E0BEE8',
            // 'D20CA9C9-5B44-41B8-AA69-23B192DFDF50',
            // '03DC89A9-EDB6-4C7F-942A-15FD44516CFB',
            // 'FF19F286-C484-43EE-9E0F-DFAD5029E7A6',
            // '3E6ED2B1-7EC2-48E7-A358-D44CCA096C4C',
            // '07E3A56D-54CE-4801-BDE6-535907E449F0',
            // '45CA7233-706C-4A8C-B6EF-8070D4785257',
            // '62F13D48-7E87-43BD-9C8B-8070D4780445',
            // '4DD438F4-D0AC-4C91-8A29-8070D4787DE0',
            // '6A5DDA0E-E458-4C36-B094-8070D478A220',
            // 'D85EC36C-9690-4697-90F7-8070D478FCB3',
            // 'B45F74C7-B44E-4A6F-A5DA-8070D4787C75',
            // 'F831A368-287B-49DC-9D19-8070D4787F83',
            // 'D4F86B9B-F99D-4E93-9E72-8070D478E59A',
            // '259017DA-0DF0-48E5-8A50-8070D4784519',
            // 'C3F15975-1065-4225-A4D9-8070D4786A39',
            // 'A5470E02-3215-4405-988B-8070D478F6B1',
            // '39DC66A5-93FC-4430-BE31-8070D478BA5D',
            // '174F132F-6C7E-450C-BAF9-8070D478B81C',
            // '6A3E9EDE-1FCB-4883-9280-8070D4787126',
            // 'A305C726-8EDE-4C20-8A50-8070D478A50B',
            // 'B77D51FE-E6D4-4716-AC21-8070D4785DA7',
            // '613737CE-2F7D-4EAE-B38C-8070D4782F8B',
            // 'BB7B611C-5FFD-4BD2-BF80-8070D4788FBF',
            // '527642BC-528F-4738-BA0F-8070D4783956',
            // 'B2B01D0E-FEFB-4930-91DF-8070D4789913',
            // 'B67AEEA7-16FF-4B22-89C7-8070D47837F6',
            // '717D206E-64D9-4B8C-AEE3-8070D4785203',
            // '43E15985-4FFD-44E3-A685-8070D478342B',
            // '8C1DA030-7482-4020-8251-8070D478195C',
            // '7904A236-C300-42B0-962E-8070D47844B1',
            // '9755D294-913C-471E-A05C-8070D478AF14',
            // '6360E49F-D0A2-4075-9466-8070D4782123',
            // '434DE8F8-9E32-4C04-90E9-73C07DFDBF08',
            // 'B5732221-1AC6-4D79-A6AD-22D8DDA8D207',
            // 'F8B05DAD-1C09-4556-8259-D8A81627A680',
            // '291CC673-6DF5-4F97-8978-0DFB8CEF16E9',
            // '87049D08-AD25-4441-85B0-9ADD7BB83842',
            // 'C8276278-D273-4CA0-86F6-FEED9C8B4378',
            // 'CEB07727-3BD9-4F74-A90A-C4E9FB31FC15',
            // 'D1E821DA-86EC-43F9-8D7B-04F9E54498F6',
            // '5DF88F26-9527-4057-A338-491FD87A3647',
            // '1B8284ED-AFDD-4196-85A0-3E01AE7592B5',
            // 'F484A750-A869-4194-B293-A1F91ADA128C',
            // '45891183-7221-4400-ADA9-752983CE08B0',
            // 'E7DAD2D1-FD84-44E1-8D1A-2C18B0B578ED',
            // 'C6B1BBA9-3C2D-4259-852C-AB547166CF3E',
            // 'BC7E804D-EEAD-45A9-ACA3-EF961095DABB',
            // 'AF0EFA98-5DCB-4CDE-A8FC-1BD13C0CCBD1',
            // '3D62F75D-C055-4B6B-A3A2-C43734D84F3B',
            // '4BAA1B13-BEAC-419F-92C3-AB87FE805DA5',
            // '58F542C0-DFDB-42C7-B5EC-11438F13C2E9',
            // '1B38A8A6-E479-4AEB-A692-C8F7D6599FC8',
            // '941C5CEE-3A27-4F8E-98A3-7AF1FE67F9D4',
            // '41FB4F15-51DA-4DB1-8547-6C2199D9DA95',
            // '352E50C9-FCB3-0645-B21F-D38F3A134748',
            // 'ECA70C7E-5833-7A44-AD81-4A9C57FFD6EF',
            // 'E777B105-83C1-41F2-AC39-FB29E11C0E49',
            // '1B4C4FD6-F9AB-4453-BC16-883796E4464A',
            // '4527AFC5-F449-4211-A744-5277908CA488',
            // '4365BC59-E3BC-43D9-AE07-AA2CA3ABABA7',
            // '5AE34F18-8F6A-4CA5-9852-BE1F10543717',
            // 'B881A5F9-3459-11EB-8935-00155D014009',
            // '7F1BCAF6-1F29-11EB-9DBA-00155D014007',
            // '74324276-B897-4A0F-84F2-6C4AFF411EF1',
            // '1D1BCD81-36D2-4FD3-AADF-DFB37250C330',
            // '19288846-B5CF-4280-AB9E-D26A6AC2F1FF',
            // '83CED139-E99C-43D8-9DE4-952DEB99847A',
            // '67EA1DB6-DF65-4746-9864-AD899DC65962',
            // '9679D6DA-E394-47ED-9C26-DE778ACBDB9F',
        ];

        foreach ($cfdi as $c) {

            // $borrar = XmlR::where(['UUID' => $c])
            //     ->first();
            // $borrar->delete();

            $del = MetadataR::where(['folioFiscal' => $c])
                ->first();
            $del->delete();

            $meses = [
                '1.Enero',
                '2.Febrero',
                '3.Marzo',
                '4.Abril',
                '5.Mayo',
                '6.Junio',
                '7.Julio',
                '8.Agosto',
                '9.Septiembre',
                '10.Octubre',
                '11.Noviembre',
                '12.Diciembre',
            ];

            foreach ($meses as $m) {
                $n = 0;
                $ruta = "C:/laragon/www/contarappv1/public/storage/contarappv1_descargas/GPA161202UG8/2020/Descargas/$m/Recibidos/XML/";
                $dir = new DirectoryIterator($ruta);


                foreach ($dir as $fileinfo) {
                    $fileExt = $fileinfo->getExtension();
                    $fileBaseName = $fileinfo->getBasename(".$fileExt");
                    $filePathname = $fileinfo->getPathname();


                    if (!$fileinfo->isDot()) {
                        echo $fileBaseName;
                        echo "<br>";

                        if($fileBaseName == $c){


                            unlink($filePathname);
                        }
                    }
                }
            }
        }
    }
}
