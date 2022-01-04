<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\WriterInterface;
use Box\Spout\Writer\XLSX\Writer;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\Style\BorderBuilder;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderPart;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\Style;
use Carbon\Carbon;

class NominatifMentorController extends Controller
{
    public function export($data){

        // $dt = new DateTime();
        // $date = $dt->format('Y-m-d H:i:s');
        $date = Carbon::now()->format('M d Y');
        $filename = "Form Nominatif Mentor MSIB ".$date.".xlsx";
        $writer   = WriterFactory::create(Type::XLSX);
        $writer->openToBrowser($filename);
        $border = (new BorderBuilder())
        ->setBorderBottom($color = Color::BLACK, $width = Border::WIDTH_THIN, $style = Border::STYLE_SOLID)
        ->setBorderTop($color = Color::BLACK, $width = Border::WIDTH_THIN, $style = Border::STYLE_SOLID)
        ->setBorderLeft($color = Color::BLACK, $width = Border::WIDTH_THIN, $style = Border::STYLE_SOLID)
        ->setBorderRight($color = Color::BLACK, $width = Border::WIDTH_THIN, $style = Border::STYLE_SOLID)
        ->build();

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
        //    ->setFontColor(Color::BLUE)
        //    ->setShouldWrapText()
        //    ->setCellAlignment(CellAlignment::RIGHT)
        //    ->setBackgroundColor(Color::YELLOW)
            ->build();

        $styleBorder =  (new StyleBuilder())
           ->setBorder($border)
           ->build();
      
        //$defaultStyle = (new StyleBuilder)->setFontSize($defaultFontSize)->build();

        //create sheet 1 

        $rowsSheetOne = [];
        $totalSheetOne = 0;
        foreach ($data['data'] as $key1 => $value){
           
               $dataA = [ 
                  // $i,
                   $value['mentor_name'],
                   $value['instansi'],
                   $value['mitra'],
                   $value['nomor_rekening'],
                   $value['nama_rekening'],
                   $value['nama_bank'],
                   $value['npwp'],
                   $value['golongan'],
                   $value['email'],
                   $value['pot_pajak_persen'] ,
                   $value['volume'],
                   $value['honor_perbulan'],
                   $value['durasi_mentor'],
                   $value['Honor_berdasarkan_jam'],
                   $value['honor_final'],
                   $value['jumlah_bruto'],
                   $value['dasar_pengenaan_pajak'],
                   $value['jumlah_pot_pajak'],
                   $value['jumlah_netto'],
                   $value['link']
                ];
              $totalSheetOne += $value['jumlah_netto'];
               
              array_push($rowsSheetOne, $dataA);

       }

            $sheet     = $writer->getCurrentSheet();
            $sheet->setName("All data");
            $writer->addRow(['LEMBAGA PENGELOLA DANA PENDIDIKAN','','','','','','','','','']);
            $writer->addRow(['DIREKTORAT DANA KEGIATAN PENDIDIKAN','','','','','','','','']);
            $writer->addRow(['','']);
            $writer->addRow(['Nama Kegiatan','','','',': Magang dan Studi Independent Bersertifikat']);
            $writer->addRow(['Kode Kegiatan','','','',': ']);
            $writer->addRow(['Jumlah Bruto (Rp) ','','','',': '.$totalSheetOne]);
     
          // $writer->addRowWithStyle(['DAFTAR NAMA REIMBURSEMENT TIKET KEBERANGKATAN MAHASISWA MSIB',''],$style);
            $writer->addRowWithStyle([
                "Nama",
                "Instansi",
                "Nama Mitra",
                "Nomor Rekening",
                "Nama Pemilik Rekening",
                "Nama Bank",
                "NPWP",
                "Gol",
                "Email",
                "Pot. Pajak (%)",
                "Volume",
                "honor_per_bulan",
                "Durasi_Mentoring",
                "Honor_berdasarkan_jam",
                "Honor_final",
                "Jumlah Bruto",
                "Dasar Pengenaan Pajak",
                "Jumlah Pot. Pajak",
                "Jumlah Netto",
                "Link"
            ], $styleBorder);

            $writer->addRowsWithStyle($rowsSheetOne, $styleBorder);
            $writer->addRowWithStyle(['','','','','','','','','','','','','','','','','','Total',$totalSheetOne], $styleBorder);
            $writer->addNewSheetAndMakeItCurrent();
     

     
        foreach($data['mitra'] as $key){

            $i = 1;
            $rows = [];
            $total = 0;

            foreach ($data['data'] as $key1 => $value){
           
                 if (strtolower(trim($value['mitra'])) == $key){

                    $dataA = [ 
                       // $i,
                        $value['mentor_name'],
                        $value['instansi'],
                        $value['mitra'],
                        $value['nomor_rekening'],
                        $value['nama_rekening'],
                        $value['nama_bank'],
                        $value['npwp'],
                        $value['golongan'],
                        $value['email'],
                        $value['pot_pajak_persen'] ,
                        $value['volume'],
                        $value['honor_perbulan'],
                        $value['durasi_mentor'],
                        $value['Honor_berdasarkan_jam'],
                        $value['honor_final'],
                        $value['jumlah_bruto'],
                        $value['dasar_pengenaan_pajak'],
                        $value['jumlah_pot_pajak'],
                        $value['jumlah_netto'],
                        $value['link']
                     ];
                   $total += $value['jumlah_netto'];
                    
                   array_push($rows, $dataA);

                 }
            }

            //create sheet 2 and next
            $sheet     = $writer->getCurrentSheet();
            $jumlahkarakter = 30;
            $sheetName = substr($key, 0, $jumlahkarakter);
            $sheet->setName($sheetName);
            $writer->addRow(['LEMBAGA PENGELOLA DANA PENDIDIKAN','','','','','','','','','']);
            $writer->addRow(['DIREKTORAT DANA KEGIATAN PENDIDIKAN','','','','','','','','']);
            $writer->addRow(['','']);
            $writer->addRow(['Nama Kegiatan','','','',': Magang dan Studi Independent Bersertifikat']);
            $writer->addRow(['Kode Kegiatan','','','',': ']);
            $writer->addRow(['Jumlah Bruto (Rp) ','','','',': '.$total]);
     
          // $writer->addRowWithStyle(['DAFTAR NAMA REIMBURSEMENT TIKET KEBERANGKATAN MAHASISWA MSIB',''],$style);
            $writer->addRowWithStyle([
                "Nama",
                "Instansi",
                "Nama Mitra",
                "Nomor Rekening",
                "Nama Pemilik Rekening",
                "Nama Bank",
                "NPWP",
                "Gol",
                "Email",
                "Pot. Pajak (%)",
                "Volume",
                "honor_per_bulan",
                "Durasi_Mentoring",
                "Honor_berdasarkan_jam",
                "Honor_final",
                "Jumlah Bruto",
                "Dasar Pengenaan Pajak",
                "Jumlah Pot. Pajak",
                "Jumlah Netto",
                "Link"
            ], $styleBorder);

            

             $writer->addRowsWithStyle($rows, $styleBorder);
             $writer->addRowWithStyle(['','','','','','','','','','','','','','','','','','Total',$total], $styleBorder);
            $writer->addNewSheetAndMakeItCurrent();

      }
            $writer->close();

    }
    public function import(Request $request)
    {
 
        $file = $request->file("file_import");

        $reader = ReaderFactory::create(Type::XLSX); // for XLSX files
        $reader->open($file);
        $excel     = $reader->getSheetIterator();

        $result = [];

        //try {
         
        $mitra = [];
        $columnCount = 0;
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                if (count($row) > $columnCount) {
                    $columnCount = count($row);
                }

                array_push($mitra,strtolower(trim($row[2])));
                $documents[] = $row;
            }
        }
        unset($documents[0]);
        $dataDocument = [];
        foreach ($documents as $key ){

            $honor_berdasarkan_jam = 300000;
            $m = $key[13] * $honor_berdasarkan_jam;
            $honor_final = min([ $m,$key[12]]);
            $dasar_pengenaan_pajak = $honor_final * 0.5;
            $pot_pajak_persen = 0.05;
            $jumlah_pot_pajak = $pot_pajak_persen * $dasar_pengenaan_pajak;
            $jumlah_netto = $honor_final - $jumlah_pot_pajak;
            $data = [
                "mentor_name" => $key[1],
                "instansi" => $key[2],
                "mitra" => $key[2],
                "nomor_rekening" => $key[4],
                "nama_rekening" => $key[5],
                "nama_bank" => $key[6],
                "npwp" => $key[8],
                "golongan" => $key[7],
                "email" => $key[3],
                "pot_pajak_persen" =>  $pot_pajak_persen ,
                "volume" => 1,
                "honor_perbulan" => floatval($key[12]),
                "durasi_mentor" => floatval($key[13]),
                "Honor_berdasarkan_jam" => $honor_berdasarkan_jam,
                "honor_final" => $honor_final,
                "jumlah_bruto" => $honor_final,
                "dasar_pengenaan_pajak" => $dasar_pengenaan_pajak,
                "jumlah_pot_pajak" =>   $jumlah_pot_pajak ,
                "jumlah_netto" => $jumlah_netto,
                "link" => $key[14]
            ];
            array_push($dataDocument, $data);
        }
      
       //var_dump($dataDocument);
        $data['data'] = $dataDocument;
        unset($mitra[0]);
        $data['mitra'] = array_unique($mitra);
        return $this->export($data);
       
        
        // } catch (\Throwable $th) {
        //     //$str = "Format error di sheet : " . $sheetName;//throw $th;
        //     return redirect()->back()->with("error");
        // } 
        
    }
}

