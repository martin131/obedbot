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
    else{
        $startOffset = 0;
        $endOffset = 0;
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
    return str_replace('"', '', $json[0]->dish->name);

}


?>