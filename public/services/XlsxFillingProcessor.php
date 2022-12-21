<?php

use Shuchkin\SimpleXLSX;

function createID($name)
{
    $name = strtolower($name);
    $name = preg_replace('/\s+/', '_', $name);
    // $name = strtok($name, '-');
    $name = rtrim($name, '_');

    return $name;
}

//CONSIDER: SEPARATING PARSING THE XLS AND CONSTRUCTING THE HTML
function extractExcelTables(string $path)
{
    $tables = [];

    if ($xlsx = @SimpleXLSX::parse($path)) {
        $sheets = $xlsx->sheetNames();

        for ($i = 0; $i < count($sheets); $i++) {
            $fillingSection = $xlsx->rows($i);
            $name = $fillingSection[0][0];
            $id = createID($name);
            // $headerOpening = "<h3><strong>";
            // $headerClosing = "</strong></h3>";
            $tableOpening = "<table id=\"$id\" border=\"1\" cellpadding=\"3\" style=\"border-collapse: collapse\">";
            $tableClosing = "</table>";

            $rows = [];

            foreach ($fillingSection as $row) {
                $rows[] = '<tr><td>' . implode('</td><td>', $row) . '</td></tr>';
            }
            $tableString = implode($rows);
            // $tables[] = $headerOpening . $name . $headerClosing . $tableOpening . $tableString . $tableClosing;
            $tables[] = $tableOpening . $tableString . $tableClosing;
        }
        return $tables;
    } else {
        echo SimpleXLSX::parseError();
    }
    return $tables;
}
