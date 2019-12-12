<?php

if (isset($_POST['submit'])) {
    $date = $_POST['date'];

    if ($date < 1 || $date > 12) {
        echo "خارج از محدوده";
    } else if ( $date == 1) {
        echo "بهار - فروردین";
    } else if ($date == 2) {
        echo "بهار - اردیبهشت";
    } else if ($date == 3) {
        echo "بهار - خرداد";
    } else if ($date == 4) {
        echo "تابستان - تیر";
    } else if ($date == 5) {
        echo "تابستان - مرداد";
    } else if ($date == 6) {
        echo "تابستان - شهریور";
    } else if ($date == 7) {
        echo "پاییز - مهر";
    } else if ($date == 8) {
        echo "پاییز - آبان";
    } else if ($date == 9) {
        echo "پاییز - آذر";
    } else if ($date == 10) {
        echo "زمستان - دی";
    } else if ($date == 11) {
        echo "زمستان - بهمن";
    } else if ($date == 12) {
        echo "زمستان - اسفند";
    } else {
        echo "موردی یافت نشد";
    }
}
?>








<!DOCTYPE HTML>

<html>

<head>
    <title>Untitled</title>
</head>

<body>
    <p>لطفا ماه مورد نظر را وارد کنید!</p>
    <form method="post">
        <input type="text" name="date" placeholder="ماه" />
        <input type="submit" name="submit" value="نتیجه" />
    </form>

</body>

</html>