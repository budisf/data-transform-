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


class MergeSheet extends Controller
{
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
                if($row[0] != "ID AKUN"){
                    $documents[] = $row;
                }
                //($mitra,strtolower(trim($row[2])));
             
            }
         
        }
        //unset($documents[0]);
        $dataDocument = [];
        // foreach ($documents as $key ){

        //     $honor_berdasarkan_jam = 300000;
        //     $m = $key[13] * $honor_berdasarkan_jam;
        //     $honor_final = min([ $m,$key[12]]);
        //     $dasar_pengenaan_pajak = $honor_final * 0.5;
        //     $pot_pajak_persen = 0.05;
        //     $jumlah_pot_pajak = $pot_pajak_persen * $dasar_pengenaan_pajak;
        //     $jumlah_netto = $honor_final - $jumlah_pot_pajak;
        //     $data = [
        //         "mentor_name" => $key[1],
        //         "instansi" => $key[2],
        //         "mitra" => $key[2],
        //         "nomor_rekening" => $key[4],
        //         "nama_rekening" => $key[5],
        //         "nama_bank" => $key[6],
        //         "npwp" => $key[8],
        //         "golongan" => $key[7],
        //         "email" => $key[3],
        //         "pot_pajak_persen" =>  $pot_pajak_persen ,
        //         "volume" => 1,
        //         "honor_perbulan" => $key[12],
        //         "durasi_mentor" => (int)$key[13],
        //         "Honor_berdasarkan_jam" => $honor_berdasarkan_jam,
        //         "honor_final" => $honor_final,
        //         "jumlah_bruto" => $honor_final,
        //         "dasar_pengenaan_pajak" => $dasar_pengenaan_pajak,
        //         "jumlah_pot_pajak" =>   $jumlah_pot_pajak ,
        //         "jumlah_netto" => $jumlah_netto,
        //         "link" => $key[14]
        //     ];
        //     array_push($dataDocument, $data);
        // }
      
        // var_dump($documents);
        // $data['data'] = $dataDocument;
        // unset($mitra[0]);
        // $data['mitra'] = array_unique($mitra);
        return $this->export($documents);
       
    }
    public function export($data){

        // $dt = new DateTime();
        // $date = $dt->format('Y-m-d H:i:s');
        $date = Carbon::now()->format('M d Y');
        $filename = "Merge data ".$date.".xlsx";
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
     
        $sheet = $writer->getCurrentSheet();

        $sheet->setName("Master");
        $writer->addRowWithStyle([
            "ID Akun",
            "NIM",
            "Nama Mahasiswa",
            "Perguruan Tinggi",
            "NIK",
            "Nama Rekening",
            "Nomor Rekening",
            "Nama Bank",
            "Periode Pembayaran",
            "Jumlah Pembayaran"
        ], $styleBorder);
        $writer->addRowsWithStyle($data, $styleBorder);
        $writer->close();

    }


}
