<?php

namespace Realm\Loader;

use SimpleXMLElement;
use Realm\Model\View;
use Realm\Model\Project;
use Realm\Model\Property;
use RuntimeException;

class CsvPropertyLoader
{
    public function loadFile(
        $filename,
        Project $project,
        $type,
        $idColumn,
        $valueColumn,
        $propertyName,
        $propertyLanguage,
        $delimiter
    )
    {
        if ($delimiter == '') {
            $delimiter = ';';
        }
        if ($delimiter == 'TAB') {
            $delimiter = "\t";
        }
        $filename = $project->getBasePath() . '/' . $filename;
        if (!file_exists($filename)) {
            throw new RuntimeException("File not found: " . $filename);
        }
        $handle = fopen($filename, "r");
        $headers = $row = fgetcsv($handle, 10000, $delimiter);
        
        while (($row = fgetcsv($handle, 10000, $delimiter)) !== false) {
            // map to assoc array by header names
            while (count($row)<count($headers)) {
                $row[] = '';
            }
            if (count($row)!=count($headers)) {
                throw new RuntimeException("Column / header count mismatch");
                print_r($row);
                exit();
            }

            $row = array_combine($headers, $row);
            if (isset($row[$idColumn])) {
                $id = $row[$idColumn];
                if ($id!='') {
                    $value = trim($row[$valueColumn]);
                    
                    if ($value) {
                        switch ($type) {
                            case 'concept':
                                $obj = $project->getConcept($id);
                                break;
                            default:
                                throw new RuntimeException("Unsupported type: " . $type);
                        }
                    
                        $property = new Property();
                        $property->setName($propertyName);
                        $property->setLanguage($propertyLanguage);
                        $property->setValue($value);
                        $obj->addProperty($property);
                    }
                }
                
            }
        }
        fclose($handle);
        return $project;
    }
}
