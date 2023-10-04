<?php
//shell_exec("chmod +x ./lite-linux-amd64 && ./lite-linux-amd64 --config config.json --test https://raw.githubusercontent.com/ardi5209/akun/main/sing-box-base64.txt");
$speedtest = json_decode(file_get_contents("output.json"));
file_put_contents("sing-box.txt", "");
file_put_contents("sing-box.yaml", "proxies:");
file_put_contents("hasil_convert(untest).yaml", "proxies:");
$query = "&insert=false&config=base%2Fdatabase%2Fconfig%2Fstandard%2Fstandard_redir.ini&emoji=true&list=false&udp=true&tfo=false&expand=false&scv=true&fdn=false&sort=false&new_name=true";
$check = "";
foreach ($speedtest->nodes as $akun) {
    if ($akun->isok == true) {
        file_put_contents("sing-box.txt", $akun->link . "\n", FILE_APPEND);
        $urlHasil = "https://sub.bonds.id/sub2?target=clash&url=";
        $akn = urlencode($akun->link);
        $url = file_get_contents($urlHasil . $akn . $query);
        $hasil = explode("proxies:", $url) [1];
        $hasil = explode("proxy-groups:", $hasil) [0];
        $tes_check = "server:".explode("network: ws", explode("server:",$hasil)[1])[0];
        print($hasil."\n");
        if(preg_match("/^{$tes_check}/im",$check)) {
            echo "node sudah ada \n";
        } else {
            $check .= $hasil."\n";
            file_put_contents("sing-box.yaml", $hasil, FILE_APPEND);
        }
    }
    $urlHasil = "https://sub.bonds.id/sub2?target=clash&url=";
    $akn = urlencode($akun->link);
    $url = file_get_contents($urlHasil . $akn . $query);
    $hasil = explode("proxies:", $url) [1];
    $hasil = explode("proxy-groups:", $hasil) [0];
    echo "node mati\n{$hasil}\n";
    file_put_contents("hasil_convert(untest).yaml", $hasil, FILE_APPEND);
}
?>