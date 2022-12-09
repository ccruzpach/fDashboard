/*INSERT INTO company_and_industries (cik_number, sic_code)
VALUES ('320193', '3571');*/

/*INSERT INTO company_and_industries (cik_number, sic_code)
VALUES ('789019', '7372');
*/

/*INSERT INTO company_and_industries (cik_number, sic_code)
VALUES ('1067983', '6331');
*/

/*SELECT companies.cik_number, company_and_industries.cik_number
FROM companies
INNER JOIN company_and_industries ON companies.cik_number=company_and_industries.cik_number*/

/*SELECT companies.cik_number, company_and_industries.cik_number
FROM companies
LEFT JOIN company_and_industries ON companies.cik_number=company_and_industries.cik_number*/

/*SELECT sector_and_industries.sic_code, company_and_industries.sic_code
FROM sector_and_industries
INNER JOIN company_and_industries ON sector_and_industries.sic_code=company_and_industries.sic_code*/

/*SELECT sector_and_industries.sic_code, company_and_industries.sic_code
FROM sector_and_industries
LEFT JOIN company_and_industries ON sector_and_industries.sic_code=company_and_industries.sic_code*/
