<?php
//include main weather class
include 'inc/weather.class.php';

$weather = new Weather();

// if form submited set location id
if (isset($_POST["location_id"]) && $_POST["location_id"] != "") {
    $weather->setLocationID($_POST["location_id"]);
}
?>
<!DOCTYPE html>
<html lang="he">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>מזג האוויר בארץ ישראל</title>
    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css" integrity="sha384-vus3nQHTD+5mpDiZ4rkEPlnkcyTP+49BhJ4wJeJunw06ZAp+wzzeBPUXr42fi8If" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body dir="rtl">
    <h1><?=$weather->getPageTitle()?></h1>
    
    <div class="card text-white bg-dark weather-box">
        <img class="card-img-top" src="<?=$weather->getTempImage()?>" alt="Card image cap">
        <div class="card-body">
            <h5 class="card-title">מזג האוויר ב<?=$weather->getSelectedLocationNameByLocationId()?></h5>
            <h2 class="card-text">
               <?=$weather->getTodayTemp("html")?>
            </h2>
        </div>
    </div>


    <div class="form-holder">
    <h4>בחר עיר</h4>
    <form method="post" action="#">
        <div class="form-group">
            <select name="location_id" class="cities_list form-control">

                <?php
                foreach ($weather->buildCityArray() as $cityKey => $cityName) {
                ?>
                    <option value="<?= $cityKey ?>" <?= $weather->loactionId == $cityKey ? "selected" : "" ?>><?= $cityName ?></option>

                <?php
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-warning"> מה מזג האוויר? </button>
    </form>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js" crossorigin="anonymous"></script>

</html>