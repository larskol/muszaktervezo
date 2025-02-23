<?php
if(!function_exists('readCsv')){
    function readCSV($filePath, $headers, $delimiter = ";"){
        if(!file_exists($filePath)){
            return [];
        }
        $data = [];
        //Kompatibilitás miatt át kell konvertálni a file kódolást, ha nem excel csv utf-8-ban van mentve
        $fileContent = file_get_contents($filePath);
        //szövegszerkesztő esetén UTF-8, excel esetén false
        if(!mb_detect_encoding($fileContent, mb_detect_order(), TRUE)){
            //régebbi excel esetén ezeket a karaktereket le kell cserélni
            file_put_contents($filePath, str_replace(["õ", "Õ", "û", "Û"], ["ő", "Ő", "ű", "Ű"], utf8_encode($fileContent)));
        }
        $csv = fopen($filePath,"r");
        
        $k = 0;
        while(! feof($csv))
        {
            $line = fgetcsv($csv, 0, $delimiter);
            //ha nem megefelő a fejléc elemszám
            if($k == 0 && count($line) != count($headers)){
                return [];
            }
            if($k > 0){
                if($line){
                    $temp = [];
                    foreach($headers as $key => $header){
                        $temp[$header] = $line[$key];
                    }
                    $data[] = $temp;
                }
            }
            $k++;
        }

        return $data;
    }
}