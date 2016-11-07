<?php

namespace Realm\Loader;

use SimpleXMLElement;
use Realm\Model\View;
use Realm\Model\Project;
use Realm\Model\Property;
use RuntimeException;

class CsvPropertyLoader
{
    public function loadFile($filename, Project $project, $type, $idColumn, $valueColumn, $propertyName, $propertyLanguage)
    {
        $filename = $project->getBasePath() . '/' . $filename;
        if (!file_exists($filename)) {
            throw new RuntimeException("File not found: " . $filename);
        }
        $handle = fopen($filename, "r");
        $headers = $row = fgetcsv($handle, 1000, ";");
        
        while (($row = fgetcsv($handle, 1000, ";")) !== false) {
            // map to assoc array by header names
            $row = array_combine($headers, $row);
            $id = $row[$idColumn];
            $value = $row[$valueColumn];
            
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
        fclose($handle);
        return $project;
    }
}
