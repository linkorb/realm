<?php

namespace Realm\Loader;

class SpreadsheetLoader
{
    public function load($filename)
    {
        $fhandle = fopen($filename, "r");
        $rows = [];
        $header = null;
        while ($row = fgetcsv($fhandle, 0, "\t")) {
            if ($header === null) {
                foreach ($row as $key => $value) {
                    //sanitize header keys
                    $row[$key] = trim(strtolower($value));
                    $header = $row;
                }
                continue;
            }
            $rows[] = array_combine($header, $row);
        }
        return $rows;
    }
}
