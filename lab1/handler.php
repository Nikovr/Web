<?php
$time_start = microtime(true);
session_start();
$x = (float)htmlspecialchars($_GET["X"]);
$y = (float)htmlspecialchars($_GET["Y"]);
$r = (float)htmlspecialchars($_GET["R"]);
validateParams($x, $y, $r);

$check = "нет";
if ($x <= 0 && $y >= 0 && $y <= 2*$x + $r) {
    $check = "да";
}
if ($x >= 0 && $y >= 0 && $y <= $r / 2 && $x <= $r) {
    $check = "да";
}
if ($x >= 0 && $y <= 0 && $y **2 + $x **2 <= ($r / 2)**2 ) {
    $check = "да";
}

if (isset($_SESSION['data'])) {
    $array = $_SESSION['data'];
} else {
    $array = array();
}

date_default_timezone_set('Europe/Moscow');

array_push($array, array("X" => $x, "Y" => $y, "R" => $r, "Попадание" => $check, "Время" => date('Y/m/d H:i:s'), "Время работы" => microtime(true) - $time_start));
$_SESSION['data'] = $array;

function validateParams($x, $y, $r){
    if (!is_numeric($x) | $x >= 3 | $x <= -3){
        badRequest();
    }

    $possibleY = array("-4", "-3", "-2", "-1", "0", "1", "2", "3", "4");
    if (!in_array($y, $possibleY)) {
        badRequest();
    }

    $possibleR = array("1", "1.5", "2", "2.5", "3");
    if (!in_array($r, $possibleR))
        badRequest();

}

function badRequest(){
    header("HTTP/1.1 400 Bad Request");
    die("Проверьте передаваемые значения параметров");
}

?>

<!DOCTYPTE html>
    <html lang="ru">

    <head>
        <title>Таблица</title>
        <meta charset="UTF-8">

        <style>
            .center {
                margin-left: auto;
                margin-right: auto;
            }
        </style>
    </head>



    <body>
        <?php if (count($array) > 0) : ?>
            <table class="center" border="1">
                <thead>
                    <tr>
                        <th><?php echo implode('</th><th>', array_keys(current($array))); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($array as $row) : array_map('htmlentities', $row); ?>
                        <tr>
                            <td><?php echo implode('</td><td>', $row); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </body>