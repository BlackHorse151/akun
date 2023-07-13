<?php
require_once 'vendor/autoload.php'; // Pastikan Anda sudah menginstal pustaka Symfony YAML melalui Composer

use Symfony\Component\Yaml\Yaml;
$a = "";
function convertToV2RayLink($yaml) {
    $proxies = Yaml::parse($yaml)['proxies'];
    $vmessUrls = [];

    foreach ($proxies as $proxy) {
        $vmessUrl = 'vmess://';

        $server = $proxy['server'];
        $ps = $proxy['name'];
        $port = $proxy['port'];
        $uuid = $proxy['uuid'];
        $alterId = $proxy['alterId'];
        $cipher = $proxy['cipher'];
        $tls = $proxy['tls'];
        if($tls == 1) {
            $tls = "tls";
        }
        $skipCertVerify = $proxy['skip-cert-verify'];
        $servername = $proxy['servername'];
        if($servername == "") {
            $servername = $server;
            $server = "104.16.66.85";
        }
        $network = $proxy['network'];
        $path = $proxy['ws-opts']['path'];
        $headersHost = urlencode($proxy['ws-opts']['headers']['Host']);
        $udp = $proxy['udp'];

        $vmessUrl .= base64_encode(json_encode([
            'add' => "104.16.66.85",
            'aid' => $alterId,
            'host' => $servername,
            'id' => $uuid,
            'path' => "{$path}",
            'tls' => $tls,
            'sni' => $servername,
            'port' => $port,
            'v' => '2',
            'scy' => $cipher,
            'ps' => $ps,
            'net' => $network,
            'allowInsecure' => $skipCertVerify,
        ]));
        $vmessUrls[] = $vmessUrl;
    }
    return $vmessUrls;
}
$v2rayLinks = convertToV2RayLink(file_get_contents("b.yaml"));
//file_put_contents("sing-box.txt", "");
foreach ($v2rayLinks as $link) {
    echo $link . "\n";
    $a .= $link."\n";
    //file_put_contents("sing-box.txt", $link."\n", FILE_APPEND);
}
$a = base64_encode($a);
file_put_contents("sing-box-base64.txt", $a);
shell_exec("chmod +x ./lite-linux-amd64");
shell_exec("./lite-linux-amd64 --config config.json --test {$a}");
$speedtest = json_decode(file_get_contents("output.json"));
file_put_contents("sing-box.txt", "");
foreach($speedtest->nodes as $akun) {
    if($akun->isok == true) {
        file_put_contents("sing-box.txt", $akun->link."\n", FILE_APPEND);
    }
}
?>
