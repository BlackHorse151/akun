<?php
require_once 'vendor/autoload.php'; // Pastikan Anda sudah menginstal pustaka Symfony YAML melalui Composer

use Symfony\Component\Yaml\Yaml;

function convertToV2RayLink($yaml) {
    $proxies = Yaml::parse($yaml)['proxies'];
    $v2rayLinks = [];
    foreach ($proxies as $proxy) {
        $v2rayLink = 'vmess://';

        $v2rayLink .= base64_encode($proxy['type'] . ':' . $proxy['uuid']) . '@';
        $v2rayLink .= $proxy['server'] . ':' . $proxy['port'] . '/';
        $v2rayLink .= '?security=' . $proxy['cipher'];

        if ($proxy['tls']) {
            $v2rayLink .= '&tls=true';
            if ($proxy['skip-cert-verify']) {
                $v2rayLink .= '&skip-cert-verify=true';
            }
        }

        if ($proxy['network'] === 'ws') {
            $v2rayLink .= '&network=ws';
            $v2rayLink .= '&wsPath=' . urlencode($proxy['ws-opts']['path']);
            $v2rayLink .= '&wsHost=' . urlencode($proxy['ws-opts']['headers']['Host']);
        }

        if ($proxy['udp']) {
            $v2rayLink .= '&udp=true';
        }

        $v2rayLinks[] = $v2rayLink;
    }
    return $v2rayLinks;
}
$v2rayLinks = convertToV2RayLink(file_get_contents("b.yaml"));
file_put_contents("sing-box.txt", "");
foreach ($v2rayLinks as $link) {
    echo $link . "\n";
    file_put_contents("sing-box.txt", $link."\n", FILE_APPEND);
}
?>
