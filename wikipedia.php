<?php
if(isset($_POST['data'])){



$fileToCheck = 'files/'.$_POST['data'].'.json';
// Define the base directory
$baseDirectory = __DIR__ . '/';
$local=false;
// Get the full path to the file
$fullPath = $baseDirectory . $fileToCheck;



if (file_exists($fullPath)) {
    header('Content-Type: application/json');
    echo file_get_contents($fullPath);
    die();
}
else{
    $directory = explode('/',$fileToCheck);
    array_pop($directory);

    $new_d=$baseDirectory;
    foreach ($directory as $d){
        $new_d=$new_d.'/'.$d;
        if (!is_dir($new_d)) {
            mkdir($new_d, 0777, true);
        }
    }
    
    file_put_contents($fullPath, '');
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://en.wikipedia.org/api/rest_v1/page/summary/'.$_POST['data']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'authority: en.wikipedia.org',
        'accept: application/json; charset=utf-8; profile="https://www.mediawiki.org/wiki/Specs/Summary/1.2.0"',
        'accept-language: en',
        'referer: https://en.wikipedia.org/wiki/Information_card',
        'sec-ch-ua: "Not_A Brand";v="8", "Chromium";v="120", "Google Chrome";v="120"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Linux"',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
        'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ]);
    
    $response = curl_exec($ch);
    
    curl_close($ch);
    
    $jsonData = json_decode($response,true);
    $jsonData = json_encode($jsonData);
    
    // Set the HTTP response headers
    header('Content-Type: application/json');
    
    
    
    echo $jsonData;
    file_put_contents($fullPath, $jsonData);
}


}