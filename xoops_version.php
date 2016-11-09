<?php
// Tutorial Module                    										
// Created by kaotik													

$modversion['name'] = "CRM";
$modversion['version'] = 1.00;
$modversion['description'] = "This is a tutorial module to teach how to build module crm";
$modversion['author'] = "Fujicon";
$modversion['credits'] = "Fujicon";
$modversion['help'] = "";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "images/orang.png";
$modversion['dirname'] = "crm";

// Admin
$modversion['hasAdmin'] = 0;

$modversion['sqlfile']['mysql']	= "sql/mysql_crm.sql";
$modversion['tables'][1] 		= "crm_history";
$modversion['tables'][2] 		= "crm_invoice";
$modversion['tables'][3] 		= "crm_kegiatan";
$modversion['tables'][4] 		= "crm_komentar";
$modversion['tables'][5] 		= "crm_komentarin";
$modversion['tables'][6] 		= "crm_kontak";
$modversion['tables'][7] 		= "crm_listinvoice";
$modversion['tables'][8] 		= "crm_listproduct";
$modversion['tables'][9] 		= "crm_listunit";
$modversion['tables'][10] 		= "crm_perusahaan";
$modversion['tables'][11] 		= "crm_quotation";
$modversion['tables'][12] 		= "crm_rekening";
$modversion['tables'][13] 		= "crm_reply";
$modversion['tables'][14] 		= "crm_replyin";


// Menu
$modversion['hasMain'] = 1;
?>
