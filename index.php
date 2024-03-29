<?php
include ("functions.php");
include ("config.php");
// kdyby bylo potřeba parsovat z webu
// include("simple_html_dom.php");

$webhook= 'https://hooks.slack.com/services/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';
$output="";
$fromApp = false;
$isZomato = false;
$responseType = "ephemeral";
if (strpos($_GET["text"], '-p') !== false)
{
    $responseType = "in_channel";
}

if (contains($_GET["text"], "uslamu"))
{
    $resID = 16506453;
    $resName = "U Slámů";
    $isZomato = true;
}

else if (contains($_GET["text"], "kormidlo"))
{
    $resID = 18337479;
    $resName = "U kormidla";
    $isZomato = true;
}

else if (contains($_GET["text"], "kasparek"))
{
    $resID = 16507122;
    $resName = "Hospůdka U Kašpárka";
    $isZomato = true;
}

if (isset($_GET["token"]))
{
    $fromApp = true;
}

if($fromApp != true)
{
    echo "<pre>";
}

if($isZomato ==true)
{
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
    $output .= $resName.", ". getMenuDate($json)."\n";
    $dishes = getDishes($json, $resID);

    foreach($dishes as $item)
    {
        $output .= cleanDish($item->dish->name) ."\t".$item->dish->price .  "\n";
    }

    $message = array('payload' => json_encode(array('text' => $output)));
}

else{
    if(contains($_GET["text"], "kebab"))
    {
        $output = "Kebab je v poho, ale co třeba pro změnu zkusit něco jiného?";
    }
    else if (contains($_GET["text"], "martin"))
    {
       
        $output = "Už je čas";
    }
    else
    {
        $output = 'Přepínače:
    -p - vrátí odpověď do aktuálního channelu
         
Podporované restaurace: uslamu, kormidlo, kasparek';
    }
}

/*
$message = array('payload' => json_encode(array('text' => $output)));
$curlSend = curl_init($webhook);
curl_setopt($curlSend, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curlSend, CURLOPT_POST, true);
curl_setopt($curlSend, CURLOPT_POSTFIELDS, $message);
*/

if($fromApp || isset($_GET["debug"]))
{
    header('Content-type:application/json;charset=utf-8');
    echo json_encode( array("response_type"=> $responseType,"text" => $output));

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

writeToLog($_GET["user_name"],$_GET["channel_name"], $_GET["text"]);
?>