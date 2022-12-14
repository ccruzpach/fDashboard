<?php

use App\Models\Cik;
use App\Models\Sic;
use App\Models\Industry;
use App\Models\Sector;
use App\Models\Company;

function getCompanyName($stockSymbol)
{
    return Company::where('stock_symbol', $stockSymbol)->get()->first();
}

function getCompanyIndustry($companyQuery)
{
    $cik = Cik::where('id', $companyQuery->cik_id)->get()->first();
    $sic = Sic::where('id', $cik->sic_id)->get()->first();

    return Industry::where('id', $sic->industry_id)->first();
}

function getCompanySector($industryQuery)
{
    return Sector::where('id', $industryQuery->sector_id)->first();
}

?>