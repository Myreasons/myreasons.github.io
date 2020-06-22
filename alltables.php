<?php header('refresh: 300'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Тематики</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <style>
        body
        {
            background-color: skyblue;
        }

        tr,td
        {
            background-color: khaki;
        }
    </style>
</head>
<body style="margin:0; padding: 20px 0 0 10px;">
<?php

$f_json = '\\10.147.11.242\contact_centr\Балов\parsing\graph\df_piv5.json';
$f_json1 = '\\10.147.11.242\contact_centr\Балов\parsing\graph\df_piv6.json';
$f_json2 = '\\10.147.11.242\contact_centr\Балов\parsing\graph\df_piv2.json';

$json = file_get_contents("$f_json");
$json1 = file_get_contents("$f_json1");
$json2 = file_get_contents("$f_json2");
$obj = json_decode($json, true);
$obj1 = json_decode($json1, true);
$obj2 = json_decode($json2, true);
$all_records = $obj['data'];
$all_records1 = $obj1['data'];
$all_records2 = $obj2['data'];
$count_all_records = count($all_records);
$count_all_records1 = count($all_records1);
$count_all_records2 = count($all_records2);

$arrayGPNumber = [];
$arraySummGPNumber = [];

$arrayBRAS = [];
$arrayProblemBRAS = [];
$arrayVolBRAS = [];

$arraySwitch = [];
$arrayVolSwitch = [];

for($countRecords = 0; $countRecords < $count_all_records; $countRecords++)
{
    $checkGPNumber = $obj['data'][$countRecords]['№ГП'];
    $checkSummGPNumber = $obj['data'][$countRecords]['SUMM'];

    $arrayGPNumber[$countRecords] = $checkGPNumber;
    $arraySummGPNumber[$countRecords] = $checkSummGPNumber;
}

for($countRecords = 0; $countRecords < $count_all_records1; $countRecords++)
{
    $checkBRAS = $obj1['data'][$countRecords]['BRAS'];
    $checkProblem = $obj1['data'][$countRecords]['Problem'];
    $checkVol = $obj1['data'][$countRecords]['Vol'];

    $arrayBRAS[$countRecords] = $checkBRAS;
    $arrayProblem[$countRecords] = $checkProblem;
    $arrayVol[$countRecords] = $checkVol;
}

for($countRecords = 0; $countRecords < $count_all_records2; $countRecords++)
{
    $checkSwitch = $obj2['data'][$countRecords]['Switch'];
    $checkVolSwitch = $obj2['data'][$countRecords]['Vol'];

    $arraySwitch[$countRecords] = $checkSwitch;
    $arrayVolSwitch[$countRecords] = $checkVolSwitch;
}

echo '<table style="border: 1px solid black; border-collapse: collapse; text-align: center;">
		<tr><td style="border: 1px solid black;">№ГП</td><td>Количество</td></tr>';
for ($i = 0; $i < $count_all_records; $i++) 
{ 
	echo '<tr>';
		echo '<td style="border: 1px solid black;">'.$arrayGPNumber[$i].'</td>';
		echo '<td style="border: 1px solid black;">'.$arraySummGPNumber[$i].'</td>';
	echo '</tr>';
}
echo '</table><br><br>';

echo '<table id="BRAS" style="border: 1px solid black; border-collapse: collapse; text-align: center;">
        <tr><td style="border: 1px solid black;">BRAS</td><td style="border: 1px solid black;">Проблема</td><td>Количество</td></tr>';
for ($j = 0; $j < $count_all_records1; $j++) 
{ 
    echo '<tr>';
        echo '<td style="border: 1px solid black; padding: 0 5px 0 5px;">'.$arrayBRAS[$j].'</td>';
        echo '<td style="border: 1px solid black; padding: 0 10px 0 10px;">'.$arrayProblem[$j].'</td>';
        echo '<td style="border: 1px solid black; padding: 0 5px 0 5px;">'.$arrayVol[$j].'</td>';
    echo '</tr>';
}
echo '</table><br><br>';

echo '<table style="border: 1px solid black; border-collapse: collapse; text-align: center;">
        <tr><td style="border: 1px solid black;">Switch</td><td>Количество</td></tr>';
for ($k = 0; $k < $count_all_records2; $k++) 
{ 
    echo '<tr>';
        echo '<td style="border: 1px solid black;">'.$arraySwitch[$k].'</td>';
        echo '<td style="border: 1px solid black;">'.$arrayVolSwitch[$k].'</td>';
    echo '</tr>';
}
echo '</table>';

echo '<div style="display: inline-block; margin: 60px 0 0 850px;">
        <button onclick="theme()">Тематики обращений</button>
        <button onclick="techno()">Технология</button>
        <button onclick="allcount()">Все тематики</button>
        </div>

        <script>

            function theme()
            {
                location.href = "Graph.php";
            }

            function allcount()
            {
                location.href = "tematicksallcount.php";
            }

            function techno()
            {
                location.href = "technology.php";
            }

        </script>';
?>
</body>
</html>