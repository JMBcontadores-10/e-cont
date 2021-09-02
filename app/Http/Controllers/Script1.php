<?php

namespace App\Http\Controllers;

use App\Models\MetadataR;
use App\Models\User;
use App\Models\XmlR;
use DirectoryIterator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Script1 extends Controller
{

    public function tipoUsuarios()
    {
        $rfcs = [
            'AHF060131G59',
            // 'AFU1809135Y4',
            // 'AIJ161001UD1',
            // 'AAE160217C36',
            // 'CDI1801116Y9',
            // 'COB191129AZ2',
            // 'DOT1911294F3',
            // 'DRO191104EZ0',
            // 'DRO191129DK5',
            // 'ERO1911044L4',
            'PERE9308105X4',
            'FGA980316918',
            // 'GPA161202UG8',
            // 'GEM190507UW8',
            // 'GPR020411182',
            // 'HRU121221SC2',
            // 'IAB0210236I7',
            // 'JQU191009699',
            // 'JCO171102SI9',
            // 'MEN171108IG6',
            // 'MAR191104R53',
            // 'MCA130429FM8',
            // 'MCA130827V4A',
            // 'MOP18022474A',
            // 'MOBJ8502058A4',
            // 'PEM180224742',
            // 'PEMJ7110258J3',
            // 'PML170329AZ9',
            // 'PERA0009086X3',
            // 'PER180309RB3',
            'RUCE750317I21',
            // 'SBE190522I97',
            // 'SGA1905229H3',
            'SGA1410217U4',
            'SGT190523QX8',
            'SGX190523KA4',
            // 'SGX160127MC4',
            'STR9303188X3',
            // 'SVI831123632',
            //  'SCT150918RC9',
            // 'SAJ161001KC6',
            // 'SPE171102P94',
            // 'SCO1905221P2',
            // 'GMH1602172L8',
            // 'MGE1602172LA',
            // 'SAE191009dd8',
            // 'SMA180913NK6',
            'SST030407D77',
            // 'TEL1911043PA',
            // 'TOVF901004DN5',
            // 'VER191104SP3',
            // 'VPT050906GI8',
            // 'VCO990603D84',
            // 'IAR010220GK5',
            // 'ADMINISTRADOR',
        ];
        foreach ($rfcs as $e) {
            $gas = array("diesel" => "1");

            User::where(['RFC' => $e])
                ->update(
                    $gas,
                    ['upsert' => true]
                );
        }
    }

    public function xmlborrar()
    {
        $cfdi = [

            'D1E821DA-86EC-43F9-8D7B-04F9E54498F6',
            'CEB07727-3BD9-4F74-A90A-C4E9FB31FC15',
            'C8276278-D273-4CA0-86F6-FEED9C8B4378',
            '45891183-7221-4400-ADA9-752983CE08B0',
            'F484A750-A869-4194-B293-A1F91ADA128C',
            '1B8284ED-AFDD-4196-85A0-3E01AE7592B5',
            '5DF88F26-9527-4057-A338-491FD87A3647',

            'A71DCA03-DD78-D541-85CF-B9C998B0B339',
            '5AE34F18-8F6A-4CA5-9852-BE1F10543717',
            '291CC673-6DF5-4F97-8978-0DFB8CEF16E9',
            '4F4F54C9-0AEF-4E0A-9B4D-34541BA6C181',
            '822AC085-48CF-4D8B-8EB2-529F50CAC215',
            '1D246251-B7EF-4C10-A058-F1A15E748E24',
            'F8B05DAD-1C09-4556-8259-D8A81627A680',
            'D201D8FF-9E46-4C08-A49C-BAB55EB23FA5',
            '25B82DA5-34BE-4499-8D8B-3F73F82251B5',
            '8D4FA637-434F-4D3F-9F83-E07675375511',
            '67EA1DB6-DF65-4746-9864-AD899DC65962',
            'C3E74880-C4B5-43C6-B5CE-2506D17A05F9',
            '6F4D28C0-51DE-46FA-BF3A-B1F8DC0009A1',
            'DC90546E-A05A-4CF8-B820-03FEA9898724',
            'BAAB14A9-FFD2-49E8-8348-6EC123703826',
            '44B11574-2463-4A0E-98F2-CA979A527341',
            'C9767AD3-37D7-4F29-BEEE-1DEA4AAD2DB5',
            '4527AFC5-F449-4211-A744-5277908CA488',
            '2C8427DA-868E-4DD5-96BE-76011BD42C96',
            '1B4C4FD6-F9AB-4453-BC16-883796E4464A',
            '632BF4AD-FA90-488E-8269-D9850D065B1F',
            '9945086D-326A-4F1C-8A39-B22363D402EC',
            '10A8978B-01C9-4046-BE0A-B7F3617F5684',
            '7F1BCAF6-1F29-11EB-9DBA-00155D014007',
            '9D50E082-1A91-4495-A58B-99912D9B356B',
            'A8050050-3572-4C10-A10D-214AE15FF83B',
            '12235E4D-AA48-4ACB-9951-C42B858F0B60',
            '2EAB7747-58A4-4786-9758-3574642AEB1B',
            '941C5CEE-3A27-4F8E-98A3-7AF1FE67F9D4',
            '1B38A8A6-E479-4AEB-A692-C8F7D6599FC8',
            '58F542C0-DFDB-42C7-B5EC-11438F13C2E9',
            '4BAA1B13-BEAC-419F-92C3-AB87FE805DA5',
            '96A4578B-BF55-4E87-8242-A39D77534521',
            '1473D565-A0C4-4E72-B1FF-F8E8BF984100',
            'B936A874-115E-4178-9E1D-C4B77A0A9B26',
            '813FEA94-DBD8-4CF4-8F1A-2FC7E588806E',
            'C763C180-E6B5-B146-90E2-DB3F6B072545',
            '3E6ED2B1-7EC2-48E7-A358-D44CCA096C4C',
            'BB1F2B75-8DDD-486A-971C-BEDAED25D632',
            '323FA4F9-0295-BA4C-B6A8-58902D09776A',
            '3E50E736-5459-CC4F-AA2C-C134CFAED3C6',
            '5CA87EB1-550D-43BC-ABA8-E16D836AC44A',
            '3A598ED1-5218-4FE4-BD57-9F641254DC4F',
            '01B249CB-F570-4471-961D-A6B14E8F10A5',
            'D715AE1B-D903-4399-855A-9FD82AC944D4',
            'B5732221-1AC6-4D79-A6AD-22D8DDA8D207',
            'E0F381E6-A89B-D744-BC05-5BC8A02C2EE5',
            '03DC89A9-EDB6-4C7F-942A-15FD44516CFB',
            '62740B8B-2E42-40CC-90D4-EA441703790C',
            'B3D17C39-440D-6745-9E4C-D2CDFDA7311D',
            '2B7EC2B4-DD88-4650-9C1A-B2440DBEFC11',
            'D20CA9C9-5B44-41B8-AA69-23B192DFDF50',
            '2360D8D7-2453-4DE4-A0FB-DF479D31F6A8',
            '315B2A7A-D7F5-47F4-939C-03035824B52F',
            '74324276-B897-4A0F-84F2-6C4AFF411EF1',
            'D1B10EA2-4DB7-4882-B795-BA42AF2D3472',
            'EFA8B5A6-23CA-4F2F-9631-28F5800CB54C',
            'B2AC7C7C-3725-478A-83BA-8B7258C67819',
            'E48E126B-8819-4AE9-9ADD-37E85190D0C1',
            '0386E67D-D72A-4C86-B120-5F290E8BF853',
            '83CED139-E99C-43D8-9DE4-952DEB99847A',
            'B881A5F9-3459-11EB-8935-00155D014009',
            '4365BC59-E3BC-43D9-AE07-AA2CA3ABABA7',
            'E2E72949-3C9A-4E35-8503-A551E21C7D46',
            'C7CE23E2-35D8-4759-BBE1-1BF84FA1984E',
            '207DFC63-7476-4313-8B17-24681A2D315A',
            '07E3A56D-54CE-4801-BDE6-535907E449F0',
            'D10F056A-4EB7-5546-8EE7-57B99D5989AD',
            '4FE2956D-F30C-4099-A03A-87D157A61204',
            'E777B105-83C1-41F2-AC39-FB29E11C0E49',
            '059E9467-6F1A-4BA6-92A7-76DE242E4517',
            '19288846-B5CF-4280-AB9E-D26A6AC2F1FF',
            '1D1BCD81-36D2-4FD3-AADF-DFB37250C330',
            '7E8B604C-2362-49B7-8E19-102CB0F4C93F',
            '83D35396-DFD8-46EE-95C4-3B75F699458A',
            'C0D59F23-C9E3-694A-A986-BCC046A84DF0',
            '15CA0DB7-9449-4D28-B5D3-DE4A80BC7A21',
            'E0D7B737-2B22-48CB-B973-8269DCCC899F',
            '41FB4F15-51DA-4DB1-8547-6C2199D9DA95',
            'A4256DC9-FCD5-1B42-B680-8F829C162CC2',
            'A74D04F0-A075-48F8-8097-16145E434F63',
            '14381513-F794-4072-B28D-2081C5CA4578',
            'E42F5E81-CAB8-46B2-9D1A-DB070A330BEB',
            'A96945F4-BD1D-46C2-96AD-94452B4D6FAB',
            '6C56166C-593E-4429-A766-D4EEC6E0BEE8',
            '8187E4B9-1BDC-47C7-904C-87F53943E73F',
            '8CF0EEF3-47AD-453D-96C3-F79FDBA6494A',
            'D1CC8821-CA90-4F76-873C-4E9BC553328D',
            '670BE533-9751-4A5B-BB74-63A969742521',
            'F9C593CC-87FE-4E0F-BBAF-0BF66A986313',
            'E2AB796C-95AC-4788-A886-343874141635',
            '5A025069-E137-1F47-9B73-7D2411900D4D',
            'C1BCD0AF-FD57-674E-81F3-4607FBE4F8C8',
            'ECC157FC-E3F9-44C1-9AFE-A862F0468919',
            'A52A6B63-EFF5-4F9E-89F7-17FF0253FB4C',
            '434DE8F8-9E32-4C04-90E9-73C07DFDBF08',
            'E659DA36-CC28-4627-B9BF-242AF6BEE558',
            '1C89A261-1AC8-471E-9941-2509CC85C820',
            '2ACCC53A-2973-4F41-914F-5CCAEB723427',
            '23D9D2F0-61AA-4164-9C20-98E1C5B5A013',
            '352E50C9-FCB3-0645-B21F-D38F3A134748',
            'ECA70C7E-5833-7A44-AD81-4A9C57FFD6EF',
            '2D5698AD-61EE-2B4C-9530-04D2F01ABB9E',
            '6360E49F-D0A2-4075-9466-8070D4782123',
            '8B139384-26BA-42F3-9494-1B17B017CDC6',
            '12C3A3D5-5906-46AA-9A28-9059005C4EDA',
            '159B8C4E-9A6F-422B-9FF2-F703DE24EEAA',
            'C27B1987-5717-4700-BFE3-BE021BD79FCF',
            '9755D294-913C-471E-A05C-8070D478AF14',
            '7904A236-C300-42B0-962E-8070D47844B1',
            '8C1DA030-7482-4020-8251-8070D478195C',
            '43E15985-4FFD-44E3-A685-8070D478342B',
            '32EA5677-AA34-6044-86D9-5A579F8A31E0',
            '87049D08-AD25-4441-85B0-9ADD7BB83842',
            'E7DAD2D1-FD84-44E1-8D1A-2C18B0B578ED',
            'FF19F286-C484-43EE-9E0F-DFAD5029E7A6',
            'CE6FCD7D-FC3C-4CBD-A624-8F034FD10280',
            '717D206E-64D9-4B8C-AEE3-8070D4785203',
            'FECE91D6-425A-4AB1-9654-86ED66E7C45E',
            '1E7E1618-29F7-43D5-8202-854262B3C6E5',
            '488B1669-987F-42D6-9A9C-B885DDC5847F',
            'B67AEEA7-16FF-4B22-89C7-8070D47837F6',
            'B2B01D0E-FEFB-4930-91DF-8070D4789913',
            'DCB90C74-4010-4158-9309-8E1CA01796CD',
            'C7F7D9AA-FD5D-435F-A0B0-9BCAC043E912',
            '3D62F75D-C055-4B6B-A3A2-C43734D84F3B',
            'AF0EFA98-5DCB-4CDE-A8FC-1BD13C0CCBD1',
            'BC7E804D-EEAD-45A9-ACA3-EF961095DABB',
            'C6B1BBA9-3C2D-4259-852C-AB547166CF3E',
            '527642BC-528F-4738-BA0F-8070D4783956',
            '8FEEB5FE-6E3A-4B5B-8ABA-C4C3604EF304',
            '6B438534-0060-4F13-AD9E-D06DB27EBF0C',
            'BB7B611C-5FFD-4BD2-BF80-8070D4788FBF',
            '613737CE-2F7D-4EAE-B38C-8070D4782F8B',
            'B77D51FE-E6D4-4716-AC21-8070D4785DA7',
            'A305C726-8EDE-4C20-8A50-8070D478A50B',
            '6A3E9EDE-1FCB-4883-9280-8070D4787126',
            '174F132F-6C7E-450C-BAF9-8070D478B81C',
            'C23A605A-9ADF-6844-B223-14162E388E0B',
            'F1FD058B-3533-49AD-9343-227B60E15A14',
            '9679D6DA-E394-47ED-9C26-DE778ACBDB9F',
            '39DC66A5-93FC-4430-BE31-8070D478BA5D',
            '554953F0-990A-416C-8EF4-5D02A6FDE608',
            '5EA6590A-561B-430D-B51D-5CE5A21E47D9',
            'A5470E02-3215-4405-988B-8070D478F6B1',
            'C3F15975-1065-4225-A4D9-8070D4786A39',
            '35640B47-30FD-4ACB-9E1C-150858C2E067',
            '259017DA-0DF0-48E5-8A50-8070D4784519',
            'D4F86B9B-F99D-4E93-9E72-8070D478E59A',
            '5F021185-4358-469C-AC03-2C6DB0D9A2CE',
            'F831A368-287B-49DC-9D19-8070D4787F83',
            'B45F74C7-B44E-4A6F-A5DA-8070D4787C75',
            'D270DE14-FA0C-470A-B76D-F89AC582DC87',
            'B1C9E224-245F-6143-AFCF-B57EF10CBEF3',
            'D85EC36C-9690-4697-90F7-8070D478FCB3',
            '6A5DDA0E-E458-4C36-B094-8070D478A220',
            'E139EBAE-5B6C-4D17-A8E7-A241FE752FC4',
            '0D7DCF3A-6552-4A4A-9324-EC9C3230F202',
            '4DD438F4-D0AC-4C91-8A29-8070D4787DE0',
            'A48C50BB-FDE5-4E0D-86FD-CA397386EE56',
            'C19BAA8B-B1FB-444F-9389-32E192A93E00',
            '5FC10897-5D79-4568-A6AD-3976AD9486A9',
            '60942223-5B60-4925-B991-1EC28A4FBFE6',
            '45CA7233-706C-4A8C-B6EF-8070D4785257',
            '62F13D48-7E87-43BD-9C8B-8070D4780445',
            '5E04A0A4-1D79-4C32-9C3C-B8BC2B735217',
            'AC4DB4D3-CFF0-4117-95B5-6AF63C1A9FD8',
            '024D2635-EDD6-470E-A99E-D15F1E886FFD',
            '767A5DE7-ECFD-4F76-BCAE-9D3F1DD78A87',
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
            // '23A7A04C-D168-4C82-8B9D-032F33B27D70',
            // 'F8F3F0E0-0850-4145-9CD7-8E6B501F6E17',
            // 'EBF348AE-2EAF-42DF-8601-7F2301B854C2',
            // 'A9D17A7F-ABAA-4DB0-AC9E-3365A76B5BE0',
            // '40B4D5DD-FFDD-4815-99DB-CCA5186B4745',
            // 'F0CC6FCD-23F5-48B0-886A-B5B3AF71893A',
            // 'B7CD276D-D85C-412C-B299-5F86A961900A',
            // 'D3AD7B82-AED4-4387-BD66-4747CB530826',
            // '337C0628-69B0-4CE3-8B20-3511349402AD',
            // '1D90BBB0-E748-4645-827D-AEB6DF63318B',
            // '8E225C6B-7C83-4DD5-92CB-8070D478D7E6',
            // '5823711C-E7F2-464A-9A97-9C0E3AEB538C',
            // '7A93474C-FE52-45EE-9C9E-C08C355D3734',
            // '696DE84E-4D91-4A6B-A8F7-627EF521DD09',
            // 'DE11A07D-7B90-4F66-8E35-0436BB3F5D8A',
            // 'E3DBC97A-0843-44E9-8152-1F61F7745BEB',
            // 'FB84344F-8484-43C2-842A-C8B9CE3E8FD7',
            // '428F4FD6-6AF1-D941-A4C4-DA320C84A48C',
            // '6726ACC2-8EF9-A348-8B8E-633C44B9F4CD',
            // '8E07DED0-17F6-2D48-B5D1-FA431B5F555F',
            // 'ED7FB809-4AA2-EA41-841C-1FB9338D2E69',
            // 'FDAB5C59-A52D-1A4D-B05A-D40D8D4FB2D3',
            // '81D262D6-55B8-425C-9192-8070D4783285',
            // '98B67C4C-4CA8-4AE1-B177-8070D478E8A4',
            // '594CF761-DE4C-4684-BE2B-8070D47878A8',

        ];

        foreach ($cfdi as $c) {

            // $borrar = XmlR::where(['UUID' => $c])
            //     ->first();
            // $borrar->delete();

            // $del = MetadataR::where(['folioFiscal' => $c])
            //     ->first();
            // $del->delete();

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

                        if ($fileBaseName == $c) {


                            unlink($filePathname);
                        }
                    }
                }
            }
        }
    }
}
