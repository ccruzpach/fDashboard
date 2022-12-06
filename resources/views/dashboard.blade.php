<?php

require public_path('EdgarDataProcessor.php');
require public_path('EdgarDataRetrival.php');

$r = new EDGARDataRetriever;


// $sample = $r->getHtmlContent($r->createSearchUrl('320193', '10-K', 20050101));
// $sample = $r->getFillingDates('320193', '8-K', 20050101);
$sample = $r->getAllFillingsListByCompany('320193', 20050101);

// $sample = $r->getFilingsHtmlsUrls('320193', '8-K', 20050101);
// $sample = $r->getFillingsXlsUrls('320193', '10-K', 20050101);
// $r->downloadExcelFillings('320193', '10-K', 20050101);

dd($sample);

