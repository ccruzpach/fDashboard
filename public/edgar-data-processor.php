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

        if ($xlsx = @SimpleXLSX::parse($path)) {
            $sheets = $xlsx->sheetNames();

            for ($i = 0; $i < count($sheets); $i++) {
                $fillingSection = $xlsx->rows($i);
                $name = $fillingSection[0][0];
                $id = $this->createID($name);
                $headerOpening = "<h3><strong>";
                $headerClosing = "</strong></h3>";
                $tableOpening = "<table id=\"$id\" border=\"1\" cellpadding=\"3\" style=\"border-collapse: collapse\">";
                $footer = "</table>";

                $rows = [];

                foreach ($fillingSection as $row) {
                    $rows[] = '<tr><td>' . implode('</td><td>', $row) . '</td></tr>';
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

    public function getFillingsListByCompany($cik)
    {       
        $edgarData = (new EDGARDataRetriever())->getEdgarData('', $cik, '', 20000101);

        $dom = new DOMDocument();
        $dom->validateOnParse = true;
        @$dom->loadHTML($edgarData);
        
        $tRows = $dom->getElementsByTagName('tr');
        $fillingList = [];
        $tDocLinks = $dom->getElementsByTagName('a');
        $fillingLinks = [];
        
        foreach ($tDocLinks as $link)
        {
            $l = $link->getAttribute('href');
            (preg_match('(Archives/edgar/data)', $l)) ? $fillingLinks[] = 'https://www.sec.gov' . $l : '';
        }
        
        foreach ($tRows as $row)
        {
            $row = trim($row->textContent);
            $row = explode("\n", $row);    
            $tempArray = [];
        
            for ($i = 0; $i < count($row); $i++) 
            {
                if (($i == 0)
                    or
                    ($i == 3 or $i == 4) and preg_match("/\s\d{4}-\d{2}-\d{2}/", $row[$i]))
                {
                    $tempArray[] = trim($row[$i]);
                }
            }
            $fillingList[] = implode(' | ', $tempArray);
        }
        
        return array_combine(array_slice($fillingList, 5, -1), $fillingLinks);
    }

    public function populateCIKDatabase()
    {
        $cikWithoutTicker = "https://www.sec.gov/Archives/edgar/cik-lookup-data.txt";
        $cikWithTicker = "https://www.sec.gov/files/company_tickers.json";

        $url = file_get_contents($cikWithTicker);
        $decoded_url = json_decode($url, true);
        $dde = [];

        foreach ($decoded_url as $url) {
            $dde[] = $url['ticker'];           
        }
        return $dde;
    }
}

// $results = $p->getFillingsListByCompany('078003');