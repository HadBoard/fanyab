<?php

$d = json_decode(file_get_contents("/home/sewingap/public_html/app/sourcearena/crypto/symbols.txt"));

foreach($d as $item){
    $symbols[] = $item->symbol;
}
$symbols[] = "ALICE";
$symbols[] = "UNIFY";
$symbols[] = "LIT";
$m = join(",",($symbols));

$servername = "localhost";
$username = "sewingap_db";
$password = "5D4NBfOzOo7F@";
$dbname = "sewingap_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("UTF8");
sleep(10);
insertData($m,$conn);
sleep(25);
insertData($m,$conn);
function insertData($m,$conn){
    $data = json_decode(post($m));

    foreach($data as $coin){

        $a = $coin->symbol;
        $b = $coin->name;
        $c = number_format($coin->price, 8, '.', '');
        $d = $coin->change_percent_24h;
        $e = $coin->volume_24h;
        $f = $coin->market_cap;
        $g = $coin->icon;
        $chart24 = "https://s3.coinmarketcap.com/generated/sparklines/web/1d/usd/".$coin->id.".png";
        $chart24 = ",  chart_24h_link = '" . $chart24 . "'";

        $exist =  $conn->query("select * from crypto_api where symbol ='".strtoupper($a)."'")->num_rows > 0;
        $sql = $exist ? "update crypto_api set price='{$c}', change_percent_24h='{$d}', volume_24h='{$e}', market_cap='{$f}', last_update='".date("Y/m/d H:i:s")."' {$chart24} where symbol='".strtoupper($a)."'":"insert into crypto_api (symbol,name,price,change_percent_24h,volume_24h, market_cap,icon,last_update) values('{$a}','{$b}','{$c}','{$d}','{$e}','{$f}','{$g}','".date("Y/m/d H:i:s")."')";
        $result = $conn->query($sql);
        if(!$result){
            echo mysqli_error($conn);
        }
    }
    $fp = fopen(__DIR__.'/updates.txt', 'a');
    fwrite($fp, date("Y/m/d H:i:s")." ".($data)[0]->price."\n");
    fclose($fp);
}


function post($arr){
    $ch = curl_init('http://195.201.76.222/coinmarketcap.php' );

    $payload = json_encode(["links"=>$arr]);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
# Send request.
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}