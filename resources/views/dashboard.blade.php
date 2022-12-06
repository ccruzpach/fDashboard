<?php

// require public_path('EdgarDataProcessor.php');
// require public_path('EdgarDataRetrival.php');

// require app_path('Services/htmlParsingHelpers.php');
// dd(getHtmlDocument('320193', '10-K', 20050101));


require app_path('Services/extractEdgarFillingsUrls.php');

// dd(getAllFillingsListByCompany('320193', 20050101));


dd(getFilingsHtmlsUrls('320193', '10-K', 20050101));
// dd(getFillingsXlsUrls('320193', '10-K', 20050101))

// // $sample = $r->getHtmlContent($r->createSearchUrl('320193', '10-K', 20050101));
// // $sample = $r->getFillingDates('320193', '8-K', 20050101);
// $sample = $r->getAllFillingsListByCompany('320193', 20050101);

// // $sample = $r->getFilingsHtmlsUrls('320193', '8-K', 20050101);
// // $sample = $r->getFillingsXlsUrls('320193', '10-K', 20050101);
// // $r->downloadExcelFillings('320193', '10-K', 20050101);

// dd($sample);

