<?php
require_once 'vendor/autoload.php'; // Pastikan Anda sudah menginstal pustaka Symfony YAML melalui Composer

use Symfony\Component\Yaml\Yaml;

function convertToV2RayLink($yaml) {
    $proxies = Yaml::parse($yaml)['proxies'];
    foreach ($proxies as $proxy) {
        $vmessUrl = 'vmess://';

        $name = $proxy['servername'];
        $server = $proxy['server'];
        $port = $proxy['port'];
        $uuid = $proxy['uuid'];
        $alterId = $proxy['alterId'];
        $cipher = $proxy['cipher'];
        $tls = $proxy['tls'];
        $skipCertVerify = $proxy['skip-cert-verify'];
        $servername = $proxy['servername'];
        $network = $proxy['network'];
        $path = urlencode($proxy['ws-opts']['path']);
        $headersHost = urlencode($proxy['ws-opts']['headers']['Host']);
        $udp = $proxy['udp'];

        $vmessUrl .= base64_encode("{$server}:{$port}/?add={$servername}&aid={$alterId}&host={$servername}&path=%2F{$path}&tls={$tls}&sni={$servername}&type=ws&v=2&security={$cipher}&servername={$servername}&network={$network}&wsHeaders=Host%3A%20{$headersHost}&udp={$udp}&allowInsecure={$skipCertVerify}");

        $vmessUrls[] = [
            'name' => $name,
            'url' => $vmessUrl
        ];
    }
    return $vmessUrls;
}
$v2rayLinks = convertToV2RayLink(file_get_contents("b.yaml"));
file_put_contents("sing-box.txt", "");
foreach ($v2rayLinks as $link) {
    echo $link["url"] . "\n";
    file_put_contents("sing-box.txt", $link["url"]."\n", FILE_APPEND);
}
?>
