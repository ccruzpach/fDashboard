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
        $name = strtolower($name);
        $name = preg_replace('/\s+/', '_', $name);
        $name = strtok($name, '-');
        $name = rtrim($name, '_');

        return $name;
    }

    public function createHTMLTables($path)
    {

    }


    //CONSIDER: SEPARATING PARSING THE XLS AND CONSTRUCTING THE HTML
    public function extractExcelTables(string $path)
    {
        $tables = [];

        if ($xlsx = @SimpleXLSX::parse($path)) {
            $sheets = $xlsx->sheetNames();

            for ($i = 0; $i < count($sheets); $i++) 
            {
                $fillingSection = $xlsx->rows($i);
                $name = $fillingSection[0][0];
                $id = $this->createID($name);
                $headerOpening = "<h3><strong>";
                $headerClosing = "</strong></h3>";
                $tableOpening = "<table id=\"$id\" border=\"1\" cellpadding=\"3\" style=\"border-collapse: collapse\">";
                $tableClosing = "</table>";

                $rows = [];

                foreach ($fillingSection as $row) 
                {
                    $rows[] = '<tr><td>' . implode('</td><td>', $row) . '</td></tr>';
                }
                $tableString = implode($rows);
                $tables[] = $headerOpening . $name . $headerClosing . $tableOpening . $tableString . $tableClosing;
            }
            return $tables;
        } else {
            echo SimpleXLSX::parseError();
        }
        return $tables;
    }

    //TODO: Finish this function
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
        return $decoded_url;
    }
}

// $p = new EDGARDataProcessor();
// $results = $p->extractExcelTables($results);
// $results = $p->getFillingsListByCompany('078003', 20180101);