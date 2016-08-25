<?php
function delete_all_between($beginning, $end, $string) {
    $beginningPos = strpos($string, $beginning);
    $endPos = strpos($string, $end);
    if ($beginningPos === false || $endPos === false) {
        return $string;
    }

    $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

    return str_replace($textToDelete, '', $string);
}

function contains($input, $word)
{
    if (strpos($input, $word) !== false)
    {
        return true;
    }
    else
    {
        return false;
    }
}
function cleanDish($string)
{
    $string = delete_all_between("(",")", $string);
    //$string = strstr($string, " ");
    $string = trim($string);

    return $string;
}

function getDishes($json, $resID)
{
    if ($resID == 16506453)
    {
        $startOffset = 2;
        $endOffset = -3;
    }
    else if ($resID == 18337479)
    {
        $startOffset = 0;
        $endOffset = null;
    }

    else{
        $startOffset = null;
        $endOffset = null;
    }
    $json = $json->daily_menus[0];
    $json = $json->daily_menu;
    $json = $json->dishes;
    $json = array_slice($json,$startOffset,$endOffset);
    //$json = array_slice($json,-3);
    return $json;
}

function getMenuDate($json)
{
    $json = $json->daily_menus[0];
    $json = $json->daily_menu;
    $date = explode(' ',$json->start_date)[0];
    $date = date("j. n. Y", strtotime($date));

    return $date;
}

function getRestaurantName($json)
{
    $json = $json->daily_menus[0];
    $json = $json->daily_menu;
    $json = $json->dishes;
    return trim(str_replace('"', '', $json[0]->dish->name));

}

function writeToLog($username, $channel, $text)
{
    $file = "log.txt";
    $current = file_get_contents($file);
    $date = new DateTime();
    $date = $date->format("Y-m-d H:i:s");
    $text = $date." - ". $username. " - ".$channel. " - ". $text. "\n";
    file_put_contents($file, $current.$text);
}


?>