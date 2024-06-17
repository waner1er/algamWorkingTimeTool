
<?php

session_start();

include "Translator.php";
$translator = new Translator();


if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'fr'])) {
    // Saves language preference in session
    $_SESSION['lang'] = $_GET['lang'];
}

$translator = new Translator();

$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'fr';
$translator->setLang($lang);

$semaines = [
    ['semaine1', 'data1', 'data2'],
    ['semaine2', 'data3', 'data4'],
];

$fp = fopen('semaines.csv', 'w');

foreach ($semaines as $semaine) {
    fputcsv($fp, $semaine);
}

fclose($fp);

?>
<!doctype html>
<html lang="<?= $translator->getLang(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Check my planning</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="?lang=fr">Français</a>
<a href="?lang=en">English</a>
<h1 class="main-title"><?= ucfirst($translator('my-hours')); ?></h1>

<div class="actual-date">
    <?=  date('d/m/Y H:i:s'); ?>
</div>


<div class="container">
    <div>
        <h2><?=  ucfirst($translator('monday')); ?></h2>
        <input type="time" name="" id="Lundi-m1">
        <input type="time" name="" id="Lundi-m2">
        <input type="time" name="" id="Lundi-am1">
        <input type="time" name="" id="Lundi-am2">
    </div>
    <div>
        <h2><?= ucfirst( $translator('tuesday')); ?></h2>
        <input type="time" name="" id="Mardi-m1">
        <input type="time" name="" id="Mardi-m2">
        <input type="time" name="" id="Mardi-am1">
        <input type="time" name="" id="Mardi-am2">
    </div>
    <div>
        <h2><?=  ucfirst($translator('wednesday')); ?></h2>
        <input type="time" name="" id="Mercredi-m1">
        <input type="time" name="" id="Mercredi-m2">
        <input type="time" name="" id="Mercredi-am1">
        <input type="time" name="" id="Mercredi-am2">
    </div>
    <div>
        <h2><?=  ucfirst($translator('thursday')); ?></h2>
        <input type="time" name="" id="Jeudi-m1">
        <input type="time" name="" id="Jeudi-m2">
        <input type="time" name="" id="Jeudi-am1">
        <input type="time" name="" id="Jeudi-am2">
    </div>
    <div>
        <h2><?=  ucfirst($translator('friday')); ?></h2>
        <input type="time" name="" id="Vendredi-m1">
        <input type="time" name="" id="Vendredi-m2">
        <input type="time" name="" id="Vendredi-am1">
        <input type="time" name="" id="Vendredi-am2">
    </div>

</div>
<div class="results">
    <label for="startWeek"><?= $translator('week-choice'); ?> </label>
    <input type="week" id="startWeek">
    <h2><?= $translator('total'); ?> <span id="resultat">00</span></h2>
    <h2>Reste à travailler <span id="rest">38 <?= $translator('hours'); ?></span></h2>
    <button id="exportBtn"><?= ucfirst($translator('export')) ?></button>
    <button id="reset-form">Reset</button>
</div>

<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
<script src="calculate.js"></script>
</body>
</html>