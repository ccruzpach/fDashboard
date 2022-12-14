<?php 

require public_path('/services/Queries.php');

use App\Models\Cik;
use App\Models\Sic;
use App\Models\Industry;
use App\Models\Sector;
use App\Models\Company;

?>



<!DOCTYPE html>

<title>Document</title>
<link rel="stylesheet" href="/app.css">

<body>
    <form action="" method="get" style="margin: 30px auto; width: 310px">
        <label for="search" style="margin-right: 2px;">Search Company</label>
        <input type="text" id="search" name="search" placeholder="Symbol">
    </form>

    <?php
        $companySymbol = strtoupper($_GET['search']);        
    ?>

    <div id="company_highlights" style="width: 1000px; margin: 30px auto ; border: 1px solid black; padding: 10px; border-radius: 15px;">
        <div style="display:flex; justify-content: space-between;">
            <div>
                <p>Company: <strong>{{ getCompanyName($companySymbol)->company_title }}</strong></p>
            </div>
            <div>
                <p>Industry/Sector: <strong>{{ ucwords(strtolower(getCompanyIndustry(getCompanyName($companySymbol))->industry_name)) }} | {{ getCompanySector(getCompanyIndustry(getCompanyName($companySymbol)))->sector_name }}</strong></p>
            </div>
        </div>
    </div>
</body>
