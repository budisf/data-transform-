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

class ManageDataController extends Controller
{
    public function export($data){

        $date = Carbon::now()->format('d m Y');
        $filename = "Form Nominatif Reiumburse Transportasi ".$date.".xlsx";
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
     

     
        foreach($data['transport'] as $key){
            foreach($data['vokasi_dikti'] as $vokasiDikti){
                 //create sheet 1
            $sheet     = $writer->getCurrentSheet();
            $sheetName = $key." ".$vokasiDikti;
            $sheet->setName($sheetName);
            $writer->addRow(['','','','','','','','','Lampiran','']);
            $writer->addRow(['','','','','','','','','No','']);
            $writer->addRow(['','','','','','','','','Tanggal','']);
            $writer->addRow(['','']);
            $writer->addRowWithStyle(['DAFTAR NAMA REIMBURSEMENT TIKET KEBERANGKATAN MAHASISWA MSIB',''],$style);
            $writer->addRowWithStyle([
                "NO",
                "ID Akun",
                "Nama Beneficiaries",
                "NIK",
                "Nama Mitra",
                "Vokasi/Dikti",
                "Moda Transport",
                "Tanggal Keberangkatan",
                "Bandara/Stasiun/Terminal Asal",
                "Bandara/Stasiun/Terminal Tujuan",
                "Nama Penerima",
                "komponen",
                "Uraian",
                "Sifat",
                "Nama Rekening",
                "No Rekening",
                "Bank",
                "Valuta",
                "Jumlah Netto",
            ], $styleBorder);

            $i = 1;
            $rows = [];
            $rowsArsip = [];
            $total = 0;

            foreach ($data['data'] as $key1 => $value){
                 if ($value['moda_transport'] == $key && $value['vokasi_dikti'] == $vokasiDikti ){

                    $dataA = [ 
                        $i,
                        $value['id_akun'],
                        $value['nama_beneficiaries'],
                        $value['nik'],
                        $value['nama_mitra'],
                        ucfirst($value['vokasi_dikti']),
                        ucfirst($value['moda_transport']),
                        $value['tanggal_keberangkatan'],
                        $value['bandara_stasiun_terminal_asal'],
                        $value['bandara_stasiun_terminal_tujuan'],
                        $value['nama_penerima'],
                        $value['komponen'],
                        $value['uraian'],
                        $value['sifat'],
                        $value['nama_rekening'],
                        $value['no_rekening'],
                        $value['bank'],
                        $value['valuta'],
                        $value['jumlah_netto']
                     ];
                   $total += $value['jumlah_netto'];
                    
                   //  $writer->setRowHeight(20);
                   array_push($rows, $dataA);

                     //data arsip
                    $dataArsip = [
                        $i,
                        $value['id_akun'],
                        $value['moda_transport'],
                        $value['link']
                    ];
                    array_push($rowsArsip, $dataArsip);
                    $i++;
                 }
            }

            // var_dump($rows);
            $writer->addRowsWithStyle($rows, $styleBorder);
            $writer->addRowWithStyle(['','','','','','','','','','','','','','','','','','Total',$total], $styleBorder);
            $writer->addNewSheetAndMakeItCurrent();

                //create sheet 2
                $sheet     = $writer->getCurrentSheet();
                $sheetName = "arsip ".$key." ".$vokasiDikti;
                $sheet->setName($sheetName);
                $writer->addRowWithStyle([
                    "NO",
                    "ID Akun",
                    "Moda Transport",
                    "Link Dokumen",
                ], $styleBorder);
                $writer->addRowsWithStyle($rowsArsip, $styleBorder);
                $writer->addNewSheetAndMakeItCurrent();

            }
           
        
        }
            $writer->close();

    }
    public function import(Request $request)
    {
        // $this->validate($request, [
        //     'file_import' => 'required|mimes:xls,xlsx'
        // ]);
        
        $file = $request->file("file_import");

        $reader = ReaderFactory::create(Type::XLSX); // for XLSX files
        $reader->open($file);
        $excel     = $reader->getSheetIterator();

        $result = [];

      //  try {
            // foreach ($excel as $sheet){
            //     $sheetName = $sheet->getName();
            //     $rows    = $sheet->getRowIterator();

            //    foreach ($rows as $index => $row){
            //         array_push($result, $row[21]);
            //    }
            //    var_dump($result);
            // }
        $modeTransport = [];
        $columnCount = 0;
        $vokasiDikti = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                if (count($row) > $columnCount) {
                    $columnCount = count($row);
                }

                array_push($modeTransport,strtolower(trim($row[21])));
                array_push($vokasiDikti,strtolower(trim($row[8])));
                $documents[] = $row;
            }
        }
        // foreach ($documents as $key => $value) {
        //     if (count($documents[$key]) < $columnCount) {
        //         for ($i = count($documents[$key]); $i <= $columnCount-1; $i++) {
        //             array_push($documents[$key], '');
        //         }
        //     }
        // }
        //return count($documents);
        // foreach ($documents as $key){
        //     if ($key[0]){
        //         continue;
        //     } 
            

        // }
        //$header =  $documents[0];
        // return $header[1];
    
        unset($documents[0]);
        $dataDocument = [];
        

        foreach ($documents as $key ){

            $tgl_keberangkatan = $key[20];
            $tgl = is_string($key[20]);
            if ($tgl != true){
                $tgl = $tgl_keberangkatan->format('d/m/Y');
            }
            $data = [
                "id_akun" => $key[1],
                "nama_beneficiaries" => $key[2],
                "nik" => "-",
                "nama_mitra" => $key[4],
                "moda_transport" => strtolower(trim($key[21])),
               // "tanggal_keberangkatan" => $key[20]->format('d/m/Y'),
                "tanggal_keberangkatan" => $tgl_keberangkatan,
                "bandara_stasiun_terminal_asal" => $key[22],
                "bandara_stasiun_terminal_tujuan" => $key[24],
                "nama_penerima" => $key[2],
                "komponen" => "-",
                "uraian" => "-",
                "sifat" => "-",
                "nama_rekening" => $key[15],
                "no_rekening" => $key[16],
                "bank" => $key[17],
                "valuta" => "IDR",
                "jumlah_netto" => $key[27],
                "link" => $key[26],
                "vokasi_dikti" => strtolower(trim($key[8]))
            ];
            array_push($dataDocument, $data);
        }
   
        $data['data'] = $dataDocument;
        unset($modeTransport[0]);
        $data['transport'] = array_unique($modeTransport);

        unset($vokasiDikti[0]);
        $data['vokasi_dikti'] = array_unique($vokasiDikti);

       return $this->export($data);
        
        // } catch (\Throwable $th) {
        //     //$str = "Format error di sheet : " . $sheetName;//throw $th;
        //     return redirect()->back()->with("error");
        // } 
        
    }
}

