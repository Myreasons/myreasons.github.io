<?php header('refresh: 300'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Все тематики</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <script src="lib/dopyo.js"></script>
</head>
<body style="margin:0; padding:0;">
<?php
echo '<div id="chart1" style="height:550px;"></div>

		<button id="bar">Горизонтальные колонки</button>
		<button id="column">Вертикальные колонки</button>
		<button id="line">Линейный график</button>
		<button id="stacked-bar">Сгруппированный график</button>
		<button id="pie">Круговая диаграмма</button>

		<div style="display: inline-block; margin-left: 150px;">
		<button onclick="theme()">Тематики обращений</button>
		<button onclick="techno()">Технология</button>
		<button onclick="tables()">Таблицы</button>
		</div>';
		
$f_json = '\\10.147.11.242\contact_centr\Балов\parsing\graph\df_piv1.json';
$json = file_get_contents("$f_json");
$obj = json_decode($json, true);
$all_records = $obj['data'];
$count_all_records = count($all_records);

$arrayTown = [];
$arrayProblem = [];
$arrayVol = [];
for($countRecords = 0; $countRecords < $count_all_records; $countRecords++)
{
    $checkTown = $obj['data'][$countRecords]['Problem'];
    $checkProblem = $obj['data'][$countRecords]['Problem'];
    $checkVol = $obj['data'][$countRecords]['Vol'];

    $arrayTown[$countRecords] = $checkTown;
    $arrayProblem[$countRecords] = $checkProblem;
    $arrayVol[$countRecords] = $checkVol;

    if ($checkTown == "")
    {
        $checkTown = "Не определено";
        $arrayTown[$countRecords] = $checkTown;
    }
}

$uniTown = array_unique($arrayTown);
$uniProblem = array_unique($arrayProblem);

$newArrTown = array_values($uniTown);
$newArrProblem = array_values($uniProblem);

$finalProblArr = [];
$test = [];
$testVolCount = [];

$countTown = count($newArrTown);
$countProblem = count($newArrProblem);

for ($i = 0; $i < $countTown; $i++)
{
    $testval = $newArrTown[$i];
    $test[0]=$testval;
    for ($j = 0; $j < $count_all_records; $j++)
    {
        if($arrayTown[$j] == $testval)
        {
            $test[$j + 1] = $arrayProblem[$j];
            $newTestArr = array_unique($test);
            $newUniTestArr = array_values($newTestArr);
        }
    }
    $finalProblArr[$i]=$newUniTestArr;
    $test = array();
}

//-----------------------------------Суммирование тематик и подгонка их под города--------------------------------
$l = 0;
$finalVol1 = [];
$finalVol2 = [];
for ($i = 0; $i < $countTown; $i++)
{
    $testval = $newArrTown[$i];
    for ($k = 0; $k < $countProblem; $k++)
    {
        $testvalVol = $newArrProblem[$k];
        for ($j = 0; $j < $count_all_records; $j++)
        {
            if($arrayTown[$j] == $testval && $arrayProblem[$j] == $testvalVol)
            {
                $testVolCount[$l] = $testVolCount[$l] + $arrayVol[$j];
            }
        }
        $finalVol2[$k] = $testVolCount[$l];
        $l = $l + 1;
        $testVolCount = array();
    }
    $finalVol1[$i] = $finalVol2;
    $testVolCount = array();
}

//-----------------------------Проставление нулей по пустым местам------------------------------------------------
$masscount = count($finalVol1);
$masscount2 = count($finalVol2);

for ($e = 0; $e < $masscount; $e++)
{
    for ($p = 0; $p < $masscount2; $p++)
    {
        if ($finalVol1[$e][$p] == "" OR $finalVol1[$e][$p] == null)
        {
            $finalVol1[$e][$p] = 0;
        }
    }
}

//-----------------------------Расстановка значений по тематикам и городам----------------------------------------
$rebus = [];
$countless = count($finalVol1);
$flag = 0;
$name = 'prog_'.$flag;
$for_value = 0;

for ($forone = 0; $forone < $countless; $forone++)
{
    for ($fortwo = 0; $fortwo < $countless; $fortwo++)
    {
        $name = 'prog_'.$forone;
        $$name = $$name.','.$finalVol1[$fortwo][$forone];
        $$name = trim($$name,",");
        $rebus[$forone] = $$name;
    }
    $for_value = $for_value + 1;
}

//---------------------------------Сведение городов в одну переменную---------------------------------------------
$rytp_town = $newArrTown[0];

for($countRecords = 1; $countRecords < $countTown; $countRecords++)
{
    $deliverTown = $newArrTown[$countRecords];
    $rytp_town = $rytp_town.'","'.$deliverTown;
}

//---------------------------------------Код отвечающий за отрисовку графиков------------------------------------
echo '<script>

			function theme()
			{
				location.href = "Graph.php";
			}

			function techno()
			{
				location.href = "technology.php";
			}

			function tables()
			{
				location.href = "alltables.php";
			}

			let d = new Dopyo("chart-id", "Bar");
			
			d.setLabels(["'.$rytp_town.'"]);';

for($countRecords = 0; $countRecords < $countProblem; $countRecords++)
{
    echo 'd.addLegend({"name": "'.$newArrProblem[$countRecords].'", "values": ['.$rebus[$countRecords].']});';
}

echo '
			// d.setAnimateShow(false);
			// d.setAnimateDuration(1);
			d.setPadding(75, 30, 10, 0); // TRBL
			// d.setBackgroundColor("rgba(0, 50, 50, 1)");

			// d.setValueOnChartShow(true);

			d.setTitleText("Все тематики");
			// d.setTitleShow(false);
			// d.setTitlePosition("center", 10);
			// d.setTitleBackgroundColor("rgba(100, 100, 100, 0.5)");
			// d.setTitleBorderColor("rgba(100, 100, 100, 1)");
			// d.setTitleFontSize(20);
			// d.setTitleFontFamily("monospace");
			// d.setTitleFontWeight(100);
			// d.setTitleFontColor("white");

			// d.setAxisXShow(false);
			// d.setAxisXColor("rgba(255, 200, 200, 1)");
			// d.setAxisXWidth(0.5);
			// d.setAxisXMinValue(-100);
			// d.setAxisXMaxValue(100);

			// d.setAxisYShow(false);
			// d.setAxisYColor("rgba(255, 200, 200, 1)");
			// d.setAxisYWidth(0.5);
			// d.setAxisYMinValue(-100);
			// d.setAxisYMaxValue(100);
			// d.setAxisYStackedBarMinValue(3000);
			// d.setAxisYStackedBarMaxValue(27500);

			// d.setGridXShow(true);
			// d.setGridXInterval(5000);
			// d.setGridXLabelShow(true);

			d.setGridYShow(true);

			// d.setLabelShow(false);
			// d.setLabelFontColor("rgba(255, 255, 255, 1)");
			// d.setLabelFontFamily("sans-serif");
			// d.setLabelFontSize(12);
			// d.setLabelFontWeight(300);

			// d.setLegendTableShow(false);
			// d.setLegendTablePosition("center", 470);
			// d.setLegendTableDirection("horizontal");
			// d.setLegendTableDirection("vertical");
			// d.setLegendTableBackgroundColor("rgba(100, 100, 100, 0.5)");
			// d.setLegendTableBorderColor("rgba(100, 100, 100, 1)");
			// d.setLegendTableFontColor("white");

			// d.setTooltipShow(false);
			// d.setTooltipBoxColor("rgba(200, 200, 240, 0.8)");
			// d.setTooltipFontColor("rgba(100, 100, 100, 1)");

			// d.setPointShow(false);
			// d.setLineWidth(2);
			// d.setLineStyle("straight");

			// d.setPointShow(false);
			// d.setPointRadius(3);
			// d.setPointStrokeWidth(1);

			// Inject chart into DOM object
			let div = document.getElementById("chart1");
			d.inject(div);

			// Draw
			d.draw();

			document.getElementById("column").addEventListener("click", function() {
				d.setType("Column");
				d.draw();
			});

			document.getElementById("bar").addEventListener("click", function() {
				d.setType("Bar");
				d.draw();
			});

			document.getElementById("line").addEventListener("click", function() {
				d.setType("Line");
				d.draw();
			});

			document.getElementById("stacked-bar").addEventListener("click", function() {
				d.setType("Stacked-Bar");
				d.draw();
			});

			document.getElementById("pie").addEventListener("click", function() {
				d.setType("Pie");
				d.draw();
			});

		</script>';
?>
</body>
</html>