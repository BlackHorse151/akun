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
        $network = $proxy['network'];
        $path = $proxy['ws-opts']['path'];
        $headersHost = urlencode($proxy['ws-opts']['headers']['Host']);
        $udp = $proxy['udp'];

        $vmessUrl .= base64_encode(json_encode([
            'add' => $server,
            'aid' => $alterId,
            'host' => $servername,
            'id' => $uuid,
            'path' => "{$path}",
            'tls' => $tls,
            'sni' => $servername,
            'port' => $port,
            'v' => '2',
            'scy' => $cipher,
            'ps' => $servername,
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
file_put_contents("sing-box-base64.txt", base64_encode($a));
shell_exec("sudo sh ./utils/speedtest.sh");
$speedtest = json_decode(file_get_contents("output.json"));
file_put_contents("sing-box.txt", "");
foreach($speedtest["nodes"] as $akun) {
    if($akun->ping != "0") {
        file_put_contents("sing-box.txt", $akun->link."\n", FILE_APPEND);
    }
}
?>
