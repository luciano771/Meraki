<?php
require '../vendor/autoload.php'; // Carga la biblioteca Spout
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;


$xlsxFilePath = 'arch1.xlsx'; // Reemplaza con la ruta de tu archivo XLSX
$reader = ReaderEntityFactory::createXLSXReader();
$reader->open($xlsxFilePath);


foreach ($reader->getSheetIterator() as $sheet) {
    foreach ($sheet->getRowIterator() as $row) {

        $nombres = $row->getCellAtIndex(0)->getValue(); // Columna A
        $apellido = $row->getCellAtIndex(1)->getValue(); // Columna A
        $dni = $row->getCellAtIndex(2)->getValue(); // Columna B

        echo "Nombres: $nombres<br>";
        echo "apellido: $apellido<br>";
        echo "DNI: $dni<br>";
        echo "<br>"; // Salto de línea después de cada fila

    }
    
}


$reader->close();



?>