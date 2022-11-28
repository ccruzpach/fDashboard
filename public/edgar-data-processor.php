<?php

use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Storage;
use Shuchkin\SimpleXLSX;

class EDGARDataProcessor
{
    public function createID($name)
    {
        //CREATE LOGIC TO SHORTEN NAME | ADD CONDITIONAL STATEMENTS BASED ON AMOUNT OF WORDS
        //CREATE TABLE WITH SHORTCUTS FOR THESE TITLES
        $name = strtolower($name);
        $name = preg_replace('/\s+/', '_', $name);
        $name = strtok($name, '-');
        $name = rtrim($name, '_');

        return $name;
    }

    public function extractExcelTables(string $path) 
    {
        $tables = [];

        if ( $xlsx = @SimpleXLSX::parse($path) ) 
        {
            $sheets=$xlsx->sheetNames();

            for ($i = 0; $i < count($sheets); $i++)
            {
                $fillingSection = $xlsx->rows($i);
                $name = $fillingSection[0][0];
                $id = $this->createID($name);
                $headerOpening = "<h3><strong>";
                $headerClosing = "</strong></h3>";
                $tableOpening = "<table id=\"$id\" border=\"1\" cellpadding=\"3\" style=\"border-collapse: collapse\">";
                $footer = "</table>";

                $rows = [];

                foreach ($fillingSection as $row)
                {
                    $rows[] = '<tr><td>'.implode('</td><td>', $row ).'</td></tr>';
                }

                $tableString = implode($rows);
                $tables[] = $headerOpening . $name . $headerClosing . $tableOpening . $tableString . $footer;
            }

            return $tables;

        } else {
            echo SimpleXLSX::parseError();
        }

        return $tables;
    }

    public function getFillingsListByCompany($EDGARData)
    {
        $dom = new DOMDocument();
        @ $dom->loadHTML($EDGARData);

        $elements = $dom->getElementsByTagName('tr');
        $tableElements = [];

        foreach ($elements as $element)
        {
            $element = trim($element->textContent);
            $element = explode("\n", $element);
            $tempArray = [];

            for ($i = 0; $i < count($element); $i++)
            {
                if (($i == 0)
                    or
                    ($i == 3 or $i == 4) and preg_match("/\s\d{4}-\d{2}-\d{2}/", $element[$i]))
                {
                    $tempArray[] = trim($element[$i]);
                }
            }
            $tableElements[] = implode(' | ', $tempArray);
        }
        $tableElements = array_splice($tableElements, 5);
        array_pop($tableElements);

        return $tableElements;
    }
}