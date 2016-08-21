<?php
include ("functions.php");
include ("config.php");
// kdyby bylo potřeba parsovat z webu
//include("simple_html_dom.php");

$webhook= 'https://hooks.slack.com/services/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';
$output="";
$fromApp = false;
$resID = 16506453;
if ($_GET["text"] == "uslamu")
{
    $resID = 16506453;
}

if ($_GET["text"] == "kormidlo")
{
    $resID = 18337479;
}

if (isset($_GET["token"]))
{
    $fromApp = true;
}

if($fromApp != true)
{
    echo "<pre>";
}

$curl = curl_init();
$apiURL = "https://developers.zomato.com/api/v2.1/dailymenu?res_id=". $resID;
curl_setopt_array($curl, array(
    CURLOPT_URL => $apiURL,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_VERBOSE => 0,

    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "user_key: $apiKey"
    ),
));

$response = curl_exec($curl);
$json = json_decode($response);

$output .= getRestaurantName($json).", ". getMenuDate($json)."\n";

$dishes = getDishes($json, $resID);

foreach($dishes as $item)
{
    $output .= cleanDish($item->dish->name) ."\t".$item->dish->price .  "\n";
}

$message = array('payload' => json_encode(array('text' => $output)));
$curlSend = curl_init($webhook);
curl_setopt($curlSend, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curlSend, CURLOPT_POST, true);
curl_setopt($curlSend, CURLOPT_POSTFIELDS, $message);

if($fromApp == true || isset($_GET["debug"]))
{
    header('Content-type:application/json;charset=utf-8');
    echo json_encode( array("response_type"=> "in_channel","text" => $output));
}
else
{
    echo $output;
    curl_exec($curlSend);
    curl_close($curlSend);
}

if($fromApp != true)
{
    echo "</pre>";
}
?>