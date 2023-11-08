<?php

function pre_m_y($month, $year)
{
    if ($month == 1) {
        $pre_month = 12;
        $pre_year = $year - 1;
    } else {
        $pre_month = $month - 1;
        $pre_year = $year;
    }
    $data = array(
        'pre_month' => $pre_month,
        'pre_year' => $pre_year
    );
    return $data;
}

if (isset($_GET['c_month']) && isset($_GET['c_year'])) {
    $c_month = $_GET['c_month'];
    $c_year = $_GET['c_year'];
} else {
    $c_month = date("m");
    $c_year = date("Y");
}

$t = $modx->runSnippet('hipersound_month_and_year', array('month' => $c_month, 'year' => $c_year));

$pre_year1 = $t['pre_year1'];
$pre_year2 = $t['pre_year2'];
$pre_year3 = $t['pre_year3'];
$pre_year4 = $t['pre_year4'];
$pre_year5 = $t['pre_year5'];
$next_year = $t['next_year'];
$pre_month1 = $t['pre_month1'];
$pre_month2 = $t['pre_month2'];
$pre_month3 = $t['pre_month3'];
$pre_month4 = $t['pre_month4'];
$pre_month5 = $t['pre_month5'];
$next_month = $t['next_month'];
$c_month_text = $t['c_month_text'];
$c_nds = $t['c_nds'];
$pre_nds1 = $t['pre_nds1'];
$pre_nds2 = $t['pre_nds2'];
$pre_nds3 = $t['pre_nds3'];
$pre_nds4 = $t['pre_nds4'];
$pre_nds5 = $t['pre_nds5'];

$ats_rate = 300;

$pts_rate = 750;

$t1 = $t;
$t1['month'] = $c_month;
$t1['year'] = $c_year;

$ats_pts = $modx->runSnippet('hipersound_ats/pts_make', $t1);
//print_r('ats_pts <br>');
//print_r($ats_pts);
//print_r('<br>');


/*
print_r('c_nds = ');
print_r($c_nds);
print_r('<br>');
print_r('pre_nds1 = ');
print_r($pre_nds1);
print_r('<br>');
print_r('pre_nds2 = ');
print_r($pre_nds2);
print_r('<br>');
print_r('pre_nds3 = ');
print_r($pre_nds3);
print_r('<br>');
print_r('pre_nds4 = ');
print_r($pre_nds4);
print_r('<br>');
*/

$rates = $modx->runSnippet('hipersound_get_rate', array('month' => $pre_month1, 'year' => $pre_year1));

$sql = "
SELECT
  rent_id,
  rent_name,
  rent_1c,
  rent_1,
  rent_contracts
FROM
  `m-engine_hipersound_renters`
ORDER BY rent_name";
$q = $modx->prepare($sql);
$q->execute();
$res_renters_advance = $q->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['make_table'])) {
    $check = $modx->runSnippet('hipersound_check_before_make_table', array('month' => $c_month, 'year' => $c_year, 'table_name' => 'advance'));
    if ($check == 0) {
        foreach ($res_renters_advance as $v) {
            $sql = "INSERT INTO `m-engine_hipersound_advance`(`advance_renter`, `advance_water`, `advance_non1`, `advance_pts`, `advance_non2`, `advance_ats`, `advance_parking`, `advance_month`, `advance_year`) VALUES ('" . $v['rent_id'] . "','0','0','0','0','0','0','" . $c_month . "','" . $c_year . "')";
            $q = $modx->prepare($sql);
            $q->execute();
        }
    }
}
if (isset($_GET['action']) == 'add_str') {
    $month = $_GET['c_month'];
    $year = $_GET['c_year'];
    $renter = $_GET['renter'];
    $sql = "INSERT INTO `m-engine_hipersound_advance`(`advance_renter`, `advance_water`, `advance_non1`, `advance_pts`, `advance_non2`, `advance_ats`, `advance_parking`, `advance_month`, `advance_year`) VALUES ('" . $renter . "','0','0','0','0','0','0','" . $month . "','" . $year . "')";
    $q = $modx->prepare($sql);
    $q->execute();
}
if ($_GET['action'] == 'delete_str') {
    $sql = "DELETE FROM `m-engine_hipersound_advance` WHERE id_advance = " . $_GET['delete_id'] . ";";
    print_r($sql);
    print_r('<br>');
    $q = $modx->prepare($sql);
    $q->execute();

}
$sql = "
SELECT
  rent_id,
  rent_name,
  rent_1c,
  rent_1,
  rent_contracts
FROM
  `m-engine_hipersound_renters`
ORDER BY rent_sort";
$q = $modx->prepare($sql);
$q->execute();
$res_renters_advance = $q->fetchAll(PDO::FETCH_ASSOC);

$sql = "
SELECT
  rent_id,
  rent_name,
  rent_1c,
  rent_1,
  rent_contracts,
  id_advance,
  advance_renter,
  advance_water,
  advance_non1,
  advance_pts,
  advance_non2,
  advance_ats,
  advance_parking,
  advance_sys_alarm,
  advance_month,
  advance_year,
  id_energy_r,
  energy_r_renter,
  energy_r_kva,
  energy_r_kvch,
  energy_r_month,
  energy_r_year
FROM
  `m-engine_hipersound_renters`
INNER JOIN (
  SELECT
    id_advance,
    advance_renter,
    advance_water,
    advance_non1,
    advance_pts,
    advance_non2,
    advance_ats,
    advance_parking,
    advance_sys_alarm,
    advance_month,
    advance_year
  FROM
    `m-engine_hipersound_advance`
  WHERE
     advance_month = '" . $pre_month2 . "'
   AND
     advance_year = '" . $pre_year2 . "'
) as ad ON rent_id = advance_renter
INNER JOIN (
  SELECT
    id_energy_r,
    energy_r_renter,
    energy_r_kva,
    energy_r_kvch,
    energy_r_month,
    energy_r_year
  FROM
    `m-engine_hipersound_energy_request`
  WHERE
     energy_r_month = '" . $pre_month2 . "'
   AND
     energy_r_year = '" . $pre_year2 . "'
) as enr ON rent_id = energy_r_renter";
$q = $modx->prepare($sql);
$q->execute();
$res_renters_advance_pre = $q->fetchAll(PDO::FETCH_ASSOC);

$total_rows = count($res_renters_advance);
$check = $modx->runSnippet('hipersound_check_before_make_table', array('month' => $c_month, 'year' => $c_year, 'table_name' => 'advance'));
if ($check == 0) {
    $make_table_html = '<h3><a href = "[[~112]]?c_month=' . $c_month . '&c_year=' . $c_year . '&make_table=true">Создать авансовых платежей</a></h3>';
} else {
    $make_table_html = '';
}

$modx->setPlaceholder('head_title', 'Авансовые платежи за ' . $c_month_text . ' ' . $c_year);
$modx->setPlaceholder('make_table_html', $make_table_html);
$modx->setPlaceholder('id_res', '[[~112]]');
$modx->setPlaceholder('pre_month', $pre_month1);
$modx->setPlaceholder('pre_year', $pre_year1);
$modx->setPlaceholder('next_month', $next_month);
$modx->setPlaceholder('next_year', $next_year);

$whater_plus = $modx->getObject('modSystemSetting', 'whater_plus');

$ee_plus = $modx->getObject('modSystemSetting', 'ee_plus');

$proc = 5;
if ($pre_year3 >= 2020) {
    $proc = 7;
}
if ($pre_year3 >= 2021) {
    $proc = 9;
}
$proc_value = $proc / 100;

$output .= '<h3> Тариф : КВА = ' . $rates[$pre_month3 . '_' . $pre_year3 . '_КВА'] . ' | КВТЧ = ' . $rates[$pre_month3 . '_' . $pre_year3 . '_КВТЧ'] . ' </h3>
 <table class = "table">
  <tr style = "background-color:#93e6cf;">
   <td style = "width:30px;"></td>
   <td style = "width:200px;">Арендаторы</td>
   <td>Электроэнергия</td>
   <td>Водоснабжение</td>
   <td>ПТС линий</td>
   <td>Аб.плата ПТС</td>
   <td>АТС линий</td>
   <td>Аб.плата АТС</td>
   <td>Парковка</td>
   <td>Сис. оповещ.</td>
   <td>Итого</td>
   <td>Долг</td>
   <td>Всего</td>
   <td>Аванс за ' . $pre_month2 . '</td>
   <td>Услуги предв. за ' . $pre_month2 . '</td>
  </tr>
';
$num = 0;
$td2 = 0;
$td3 = 0;
$td4 = 0;
$td5 = 0;
$td6 = 0;
$td7 = 0;
$td8 = 0;
$td9 = 0;
$td10 = 0;
$td11 = 0;
$td12 = 0;
$td13 = 0;
$td14 = 0;
foreach ($res_renters_advance as $v) {
    $num = $num + 1;
    $sql = "
  SELECT
    id_advance,
    advance_renter,
    advance_water,
    advance_non1,
    advance_pts,
    advance_non2,
    advance_ats,
    advance_parking,
    advance_sys_alarm,
    advance_month,
    advance_year
  FROM
    `m-engine_hipersound_advance`
  WHERE
     advance_month = '" . $c_month . "'
   AND
     advance_year = '" . $c_year . "'
   AND
     advance_renter = '" . $v['rent_id'] . "'
  ";
    $q = $modx->prepare($sql);
    $q->execute();
    $res_advance = $q->fetchAll(PDO::FETCH_ASSOC);

    $sql = "
  SELECT
    id_advance,
    advance_renter,
    advance_water,
    advance_non1,
    advance_pts,
    advance_non2,
    advance_ats,
    advance_parking,
    advance_sys_alarm,
    advance_month,
    advance_year
  FROM
    `m-engine_hipersound_advance`
  WHERE
     advance_month = '" . $pre_month2 . "'
   AND
     advance_year = '" . $pre_year2 . "'
   AND
     advance_renter = '" . $v['rent_id'] . "'
  ";
    $q = $modx->prepare($sql);
    $q->execute();
    $pre_res_advance = $q->fetchAll(PDO::FETCH_ASSOC);

    $sql = "
  SELECT
    id_energy_r,
    energy_r_renter,
    energy_r_kva,
    energy_r_kvch,
    energy_r_month,
    energy_r_year
  FROM
    `m-engine_hipersound_energy_request`
  WHERE
     energy_r_month = '" . $c_month . "'
   AND
     energy_r_year = '" . $c_year . "'
   AND
     energy_r_renter = '" . $v['rent_id'] . "'
  ";
    $q = $modx->prepare($sql);
    $q->execute();
    $res_en_r = $q->fetchAll(PDO::FETCH_ASSOC);

    $sql = "
  SELECT
    id_energy_r,
    energy_r_renter,
    energy_r_kva,
    energy_r_kvch,
    energy_r_month,
    energy_r_year
  FROM
    `m-engine_hipersound_energy_request`
  WHERE
     energy_r_month = '" . $pre_month2 . "'
   AND
     energy_r_year = '" . $pre_year2 . "'
   AND
     energy_r_renter = '" . $v['rent_id'] . "'
  ";
    $q = $modx->prepare($sql);
    $q->execute();
    $pre_res_en_r = $q->fetchAll(PDO::FETCH_ASSOC);

    $ee = ($rates[$pre_month3 . '_' . $pre_year3 . '_КВА'] * $res_en_r[0]['energy_r_kva'] + $rates[$pre_month3 . '_' . $pre_year3 . '_КВТЧ'] * $res_en_r[0]['energy_r_kvch']) * $c_nds;

    $itogo = $ee + $res_advance[0]['advance_water'] + $res_advance[0]['advance_non1'] * pts_rate + $res_advance[0]['advance_non2'] * $ats_pts['ats_rate'] + $res_advance[0]['advance_parking'] + $res_advance[0]['advance_sys_alarm'];

    $ee1 = 0;
    $ee1 = ($rates[$pre_month5 . '_' . $pre_year5 . '_КВА'] * $pre_res_en_r[0]['energy_r_kva'] + $rates[$pre_month5 . '_' . $pre_year5 . '_КВТЧ'] * $pre_res_en_r[0]['energy_r_kvch']) * $pre_nds2;

    $itogo1 = $ee1 + $pre_res_advance[0]['advance_water'] + $pre_res_advance[0]['advance_non1'] * $pts_rate + $pre_res_advance[0]['advance_non2'] * $ats_pts['pre2_ats_rate'] + $pre_res_advance[0]['advance_parking'] + $pre_res_advance[0]['advance_sys_alarm'];

    $sql = "
SELECT
  id_contract,
  contract_name,
  contract_renter,
  contract_k,
  contract_date_in,
  c_energy_kva,
  c_energy_readings,
  pre_energy_readings
FROM
  `m-engine_hipersound_contracts`
INNER JOIN
  (SELECT
     id_energy as c_id_energy,
     energy_contract as c_energy_contract,
     energy_kva as c_energy_kva,
     energy_month as c_energy_month,
     energy_year as c_energy_year,
     energy_readings as c_energy_readings
   FROM
     `m-engine_hipersound_energy`
   WHERE
     energy_month = '" . $pre_month2 . "'
   AND
     energy_year = '" . $pre_year2 . "'
   ) as en ON id_contract = c_energy_contract
INNER JOIN
  (SELECT
     id_energy AS pre_id_energy,
     energy_contract AS pre_energy_contract,
     energy_kvch AS pre_energy_kvch,
     energy_month AS pre_energy_month,
     energy_year AS pre_energy_year,
     energy_readings AS pre_energy_readings
   FROM
     `m-engine_hipersound_energy`
   WHERE
     energy_month = '" . $pre_month3 . "'
   AND
     energy_year = '" . $pre_year3 . "'
   ) as pre_en ON id_contract = pre_energy_contract
WHERE
  contract_renter = '" . $v['rent_id'] . "'
  ";
    $q = $modx->prepare($sql);
    $q->execute();
    $contracts = $q->fetchAll(PDO::FETCH_ASSOC);

    $s_readings = 0;
    $kva = 0;
    foreach ($contracts as $v1) {
        if ($v1['contract_date_in'] == '00.0000') {
            $s_readings = $s_readings + ($v1['c_energy_readings'] - $v1['pre_energy_readings']) * $v1['contract_k'];
        } else {
            $tms = explode('.', $v1['contract_date_in']);
            if ((int)$c_year == (int)$tms[1]) {
                if ((int)$c_month >= (int)$tms[0]) {
                    $s_readings = $s_readings + ($v1['c_energy_readings'] - $v1['pre_energy_readings']) * $v1['contract_k'];
                }
            }
            if ((int)$c_year > (int)$tms[1]) {
                $s_readings = $s_readings + ($v1['c_energy_readings'] - $v1['pre_energy_readings']) * $v1['contract_k'];
            }
        }
        $kva = $v1['c_energy_kva'];
    }

    $sql = "
SELECT
  id_contract,
  contract_name,
  contract_renter,
  contract_k,
  contract_date_in,
  c_energy_kva,
  c_energy_readings,
  pre_energy_readings
FROM
  `m-engine_hipersound_contracts`
INNER JOIN
  (SELECT
     id_energy as c_id_energy,
     energy_contract as c_energy_contract,
     energy_kva as c_energy_kva,
     energy_month as c_energy_month,
     energy_year as c_energy_year,
     energy_readings as c_energy_readings
   FROM
     `m-engine_hipersound_energy`
   WHERE
     energy_month = '" . $pre_month3 . "'
   AND
     energy_year = '" . $pre_year3 . "'
   ) as en ON id_contract = c_energy_contract
INNER JOIN
  (SELECT
     id_energy AS pre_id_energy,
     energy_contract AS pre_energy_contract,
     energy_kvch AS pre_energy_kvch,
     energy_month AS pre_energy_month,
     energy_year AS pre_energy_year,
     energy_readings AS pre_energy_readings
   FROM
     `m-engine_hipersound_energy`
   WHERE
     energy_month = '" . $pre_month4 . "'
   AND
     energy_year = '" . $pre_year4 . "'
   ) as pre_en ON id_contract = pre_energy_contract
WHERE
  contract_renter = '" . $v['rent_id'] . "'
  ";
    $q = $modx->prepare($sql);
    $q->execute();
    $pre_contracts = $q->fetchAll(PDO::FETCH_ASSOC);

    $pre_s_readings = 0;
    $pre_kva = 0;
    foreach ($pre_contracts as $v1) {
        if ($v1['contract_date_in'] == '00.0000') {
            $pre_s_readings = $pre_s_readings + ($v1['c_energy_readings'] - $v1['pre_energy_readings']) * $v1['contract_k'];
        } else {
            $tms = explode('.', $v1['contract_date_in']);
            if ((int)$c_year == (int)$tms[1]) {
                if ((int)$c_month >= (int)$tms[0]) {
                    $pre_s_readings = $pre_s_readings + ($v1['c_energy_readings'] - $v1['pre_energy_readings']) * $v1['contract_k'];
                }
            }
            if ((int)$c_year > (int)$tms[1]) {
                $pre_s_readings = $pre_s_readings + ($v1['c_energy_readings'] - $v1['pre_energy_readings']) * $v1['contract_k'];
            }
        }
        $pre_kva = $v1['c_energy_kva'];
    }

    $sql = "
SELECT
  id_contract,
  contract_name,
  contract_renter,
  contract_k,
  c_water_readings,
  pre_water_readings
FROM
  `m-engine_hipersound_contracts`
INNER JOIN
  (SELECT
     id_water as c_id_water,
     water_contract as c_water_contract,
     water_month as c_water_month,
     water_year as c_water_year,
     water_readings as c_water_readings
   FROM
     `m-engine_hipersound_water`
   WHERE
     water_month = '" . $pre_month2 . "'
   AND
     water_year = '" . $pre_year2 . "'
   ) as w ON id_contract = c_water_contract
INNER JOIN
  (SELECT
     id_water AS pre_id_water,
     water_contract AS pre_water_contract,
     water_month AS pre_water_month,
     water_year AS pre_water_year,
     water_readings AS pre_water_readings
   FROM
     `m-engine_hipersound_water`
   WHERE
     water_month = '" . $pre_month3 . "'
   AND
     water_year = '" . $pre_year3 . "'
   ) as pre_w ON id_contract = pre_water_contract
WHERE
  contract_renter = '" . $v['rent_id'] . "'
  ";
    $q = $modx->prepare($sql);
    $q->execute();
    $contracts_w = $q->fetchAll(PDO::FETCH_ASSOC);

    $s_w = 0;
    foreach ($contracts_w as $v1) {
        $tmp = 0;
        $tmp = $v1['c_water_readings'] - $v1['pre_water_readings'];
        $s_w = $s_w + ($rates[$pre_month2 . '_' . $pre_year2 . '_ВОДАХ'] * $tmp +
                $rates[$pre_month2 . '_' . $pre_year2 . '_КАН'] * $tmp) * $pre_nds3;
    }


    $pre_p_energy_services = ($pre_kva * $rates[$pre_month4 . '_' . $pre_year4 . '_КВА'] + $pre_s_readings * $rates[$pre_month4 . '_' . $pre_year4 . '_КВТЧ']) * $pre_nds4;
    $pre_f_energy_services = ($pre_kva * $rates[$pre_month3 . '_' . $pre_year3 . '_КВА'] + $pre_s_readings * $rates[$pre_month3 . '_' . $pre_year3 . '_КВТЧ']) * $pre_nds3;
    $delta = $pre_f_energy_services - $pre_p_energy_services;
    $p_energy_services = ($kva * $rates[$pre_month3 . '_' . $pre_year3 . '_КВА'] + $s_readings * $rates[$pre_month3 . '_' . $pre_year3 . '_КВТЧ']) * $pre_nds3;
    $energy_services = ($kva * $rates[$pre_month2 . '_' . $pre_year2 . '_КВА'] + $s_readings * $rates[$pre_month2 . '_' . $pre_year2 . '_КВТЧ']) * $pre_nds2;

    $sql = "
SELECT
  id_services,
  services_renter,
  services_water,
  services_non1,
  services_pts,
  services_non2,
  services_ats,
  services_lift,
  services_passing,
  services_month,
  services_year,
  services_comein,
  services_traffic,
  services_garbage
FROM
   `m-engine_hipersound_services`
WHERE
   services_month = '" . $pre_month2 . "'
 AND
   services_year = '" . $pre_year2 . "'
 AND
   services_renter = '" . $v['rent_id'] . "'
  ";
    $q = $modx->prepare($sql);
    $q->execute();
    $pre_services = $q->fetchAll(PDO::FETCH_ASSOC);

    $ats = $pre_services[0]['services_non2'] * $ats_pts['pre2_ats_rate'];
    $pts = $pre_services[0]['services_non1'] * $ats_pts['pts_rate'];

    $proc_value_e = 0.05;
    $proc_value_w = 0.05;
    if ($c_year >= 2020) {
        $proc_value_e = 0.07;
        $proc_value_w = 0.07;
    }
    if ($c_year >= 2021) {
        $proc_value_e = 0.09;
        $proc_value_w = 0.09;
    }
    if ($c_year == 2022 && $c_month > 2) {
        $proc_value_e = 0.1;
        $proc_value_w = 0.15;
    }
    if ($c_year > 2022) {
        $proc_value_e = 0.1;
        $proc_value_w = 0.15;
    }
    if ($v['rent_id'] == 33) {
        $proc_value = 0;
        if ($c_year >= 2021) {
            $proc_value_e = 0.09;
            $proc_value_w = 0.09;
        }
        if ($c_year >= 2021) {
            $proc_value_e = 0.1;
            $proc_value_w = 0.15;
        }
    }
    if ($v['rent_id'] == 71) {
        if ($c_year >= 2020) {
            $proc_value_e = 0.05;
            $proc_value_w = 0.05;
        }
        if ($c_year >= 2020) {
            $proc_value_e = 0.1;
            $proc_value_w = 0.15;
        }
    }
    if ($v['rent_id'] == 34) {
        if ($c_year >= 2021) {
            $proc_value_e = 0.08;
            $proc_value_w = 0.08;
            if ($c_year == 2021) {
                if ($c_month >= 5) {
                    $proc_value = 0.09;
                    $proc_value_e = 0.09;
                    $proc_value_w = 0.09;
                }
            }
            if ($c_year > 2021) {
                $proc_value_e = 0.09;
                $proc_value_w = 0.09;
            }
            if ($c_year >= 2022) {
                $proc_value_e = 0.1;
                $proc_value_w = 0.15;
            }
        }
    }
    if ($whater_plus->get('value') == '1') {
        $nadbavka_w = $s_w * $proc_value_w;
        $tr_whater_plus_value = '<td>' . round($nadbavka_w, 2) . '</td>';
        $colspan = $colspan + 1;
    }

    if ($ee_plus->get('value') == '1') {
        $nadbavka_ee = $p_energy_services * $proc_value_e;
        $tr_ee_plus_value = '<td>' . round($nadbavka_ee, 2) . '</td>';
        $colspan = $colspan + 1;
    }
    /*
    print_r($v['rent_id']);
    print_r(' - ');
    print_r($pre_year2);
    print_r(' - ');
    print_r($proc_value);
    print_r(' - ');
    print_r(' nadbavka w = ');
    print_r($nadbavka_w);
    print_r(' nadbavka ee = ');
    print_r($nadbavka_ee);
    print_r('<br>');
    */
    $itogo_pre = $p_energy_services + $delta + $s_w + $pts + $ats + $pre_services[0]['services_lift'] +
        $pre_services[0]['services_passing'] + $pre_services[0]['services_comein'] + $pre_services[0]['services_traffic'] + $pre_services[0]['services_garbage'] + $nadbavka_ee + $nadbavka_w;
    /*
      print_r($s_readings);
      print_r(' ----/---- ');
      print_r($p_energy_services);
      print_r('------ ');
      print_r($delta);
      print_r('----+++-- ');
      print_r($s_w);
      print_r('------ ');
      print_r($pts);
      print_r('------ ');
      print_r($ats);
      print_r('------');
      print_r($pre_services[0]['services_lift']);
      print_r('------');
      print_r($pre_services[0]['services_passing']);
      print_r('------');
      print_r($pre_services[0]['services_comein']);
      print_r('------');
      print_r($pre_services[0]['services_traffic']);
      print_r('<br>');
    */
    $ats_advance = $res_advance[0]['advance_non2'] * $ats_pts['ats_rate'];
    $pts_advance = $res_advance[0]['advance_non1'] * $ats_pts['pts_rate'];
    $debt = $itogo_pre - $itogo1;
    //$debt = $modx->runSnippet('hipersound_services', array('ftype'=>'2', 'fid'=>$v['rent_id'] ,'month'=>$pre_month2, 'year'=> $pre_year2));

    $all = $debt + $itogo;
    $name = iconv("cp1251", "utf-8", $v['rent_name']);
    $delete_str = '';
    //$delete_str = '<a href = "[[~112]]?c_month='.$c_month.'&c_year='.$c_year.'&action=delete_str&delete_id='.$v['id_advance'].'">удл</a>';
    if (count($res_advance) == 0) {
        $output .= '
  <tr>
   <td style = "width:30px;">' . $delete_str . ' ' . $num . '</td>
   <td><a href = "[[~119]]?id=' . iconv("cp1251", "utf-8", $v['rent_name']) . '&cm=' . $c_month . '&cy=' . $c_year . '"  target = "blank">' . $name . '</a></td>
   <td colspan = "13"><a href = "[[~112]]?id=' . $v['rent_id'] . '&c_month=' . $c_month . '&c_year=' . $c_year . '&action=add_str&renter=' . $v['rent_id'] . '">Создать строчку в таблице авансов</a></td>
  </tr>
    ';
    } else {
        $output .= '
  <tr>
   <td style = "width:30px;">' . $delete_str . ' ' . $num . '</td>
   <td><a href = "[[~119]]?id=' . $v['rent_id'] . '&cm=' . $c_month . '&cy=' . $c_year . '"  target = "blank">' . $name . '</a></td>
   <td>' . round($ee, 2) . '</td>
   <td class = "edit" ondblclick = "open_input(\'awater_' . $res_advance[0]['id_advance'] . '\');" id = "awater_' . $res_advance[0]['id_advance'] . '">' . $res_advance[0]['advance_water'] . '</td>
   <td class = "edit" ondblclick = "open_input(\'anon1_' . $res_advance[0]['id_advance'] . '\');" id = "anon1_' . $res_advance[0]['id_advance'] . '">' . $res_advance[0]['advance_non1'] . '</td>
   <td>' . $pts_advance . '</td>
   <td class = "edit" ondblclick = "open_input(\'anon2_' . $res_advance[0]['id_advance'] . '\');" id = "anon2_' . $res_advance[0]['id_advance'] . '">' . $res_advance[0]['advance_non2'] . '</td>
   <td>' . $ats_advance . '</td>
   <td class = "edit" ondblclick = "open_input(\'aparking_' . $res_advance[0]['id_advance'] . '\');" id = "aparking_' . $res_advance[0]['id_advance'] . '">' . $res_advance[0]['advance_parking'] . '</td>
   <td class = "edit" ondblclick = "open_input(\'asysalarm_' . $res_advance[0]['id_advance'] . '\');" id = "asysalarm_' . $res_advance[0]['id_advance'] . '">' . $res_advance[0]['advance_sys_alarm'] . '</td>
   <td>' . round($itogo, 2) . '</td>
   <td>' . round($debt, 2) . '</td>
   <td>' . round($all, 2) . '</td>
   <td>' . round($itogo1, 2) . '</td>
   <td>' . round($itogo_pre, 2) . '</td>
  </tr>
    ';
    }

    $td2 = $td2 + $ee;
    $td3 = $td3 + $res_advance[0]['advance_water'];
    $td4 = $td4 + $res_advance[0]['advance_non1'];
    $td6 = $td6 + $res_advance[0]['advance_non2'];
    $td5 = $td5 + $pts_advance;
    $td7 = $td7 + $ats_advance;
    $td8 = $td8 + $res_advance[0]['advance_parking'];
    $td9 = $td9 + $res_advance[0]['advance_sys_alarm'];
    $td10 = $td10 + $itogo;
    $td11 = $td11 + $debt;
    $td12 = $td12 + $all;
    $td13 = $td13 + $itogo1;
    $td14 = $td14 + $itogo_pre;
}
$output .= '
  <tr>
   <td></td>
   <td><strong>ИТОГО:</strong></td>
   <td>' . round($td2, 2) . '</td>
   <td>' . round($td3, 2) . '</td>
   <td>' . round($td4, 2) . '</td>
   <td>' . round($td5, 2) . '</td>
   <td>' . round($td6, 2) . '</td>
   <td>' . round($td7, 2) . '</td>
   <td>' . round($td8, 2) . '</td>
   <td>' . round($td9, 2) . '</td>
   <td>' . round($td10, 2) . '</td>
   <td>' . round($td11, 2) . '</td>
   <td>' . round($td12, 2) . '</td>
   <td>' . round($td13, 2) . '</td>
   <td>' . round($td14, 2) . '</td>
  </tr>
 </table>
</form>
[[$hipersound_script]]
';
return $output;
