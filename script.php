<?php
//https://raw.githubusercontent.com/mahdibland/V2RayAggregator/master/sub/sub_merge.txt
//https://raw.githubusercontent.com/Bardiafa/Free-V2ray-Config/main/All_Configs_Sub.txt
//https://raw.githubusercontent.com/yebekhe/ConfigCollector/main/sub/mix_base64
$url = [
    "https://raw.githubusercontent.com/yebekhe/ConfigCollector/main/sub/mix_base64",
    //"https://raw.githubusercontent.com/mahdibland/V2RayAggregator/master/sub/sub_merge_base64.txt",
    "https://ghproxy.com/https://raw.githubusercontent.com/Pawdroid/Free-servers/main/sub",
    //"https://raw.githubusercontent.com/adiwzx/freenode/main/adispeed.txt",
    "https://raw.githubusercontent.com/snakem982/proxypool/main/v2ray.txt",
    "https://raw.githubusercontent.com/gfwcross/v2pool/main/merge/all.txt",
    "https://raw.githubusercontent.com/LonUp/NodeList/main/V2RAY/001.txt",
    "https://raw.githubusercontent.com/LonUp/NodeList/main/V2RAY/002.txt",
    "https://raw.githubusercontent.com/LonUp/NodeList/main/V2RAY/003.txt",
    "https://raw.githubusercontent.com/mianfeifq/share/main/README.md",
    "https://raw.githubusercontent.com/peasoft/NoMoreWalls/master/list_raw.txt",
// "https://muma16fx.netlify.app/",
   // "https://youlianboshi.netlify.app/",
 //   "https://qiaomenzhuanfx.netlify.app/",
    "https://raw.githubusercontent.com/mfuu/v2ray/master/v2ray",
    "https://raw.githubusercontent.com/HakurouKen/free-node/main/public",
];
//https://raw.githubusercontent.com/snakem982/proxypool/main/v2ray.txt
file_put_contents("a.yaml", "proxies:");
foreach ($url as $link) {
    if ( base64_encode(base64_decode(file_get_contents($link), true)) === file_get_contents($link)){
        $isi = base64_decode(file_get_contents($link));
    } elseif (preg_match("/```/", file_get_contents($link))) {
        $a = explode("```", file_get_contents($link))[1];
        $isi = explode("```",$a)[0];
    } else {
        $isi = file_get_contents($link);
    }
    $baris = explode("\n", $isi);
    // Menghitung total baris
    $totalBaris = count($baris);
    $urlHasil = "https://sub.bonds.id/sub2?target=clash&url=";
    $git = explode("://", $link)[1];
    //file_put_contents("a.yaml", "proxies:");
    // Menginisialisasi variabel untuk menyimpan hasil URL
    $in = 0;
    $urlHasil = "https://sub.bonds.id/sub2?target=clash&url=";
    for ($i = 0; $i < $totalBaris; $i++) {
        $bagian = $baris[$i];
        echo "\r                                               \r";
        //if (strpos($bagian, 'ss://') === false or strpos($bagian, 'ssr://') ===false) {
        if (preg_match("/(vless|vmess|trojan):\/\//i", $bagian)) {
            //echo $i . " => " . $bagian . "\n";
            echo " [{$i}/{$totalBaris}] {$git} \r";
            $bagianTeks = $bagian . "|";
            $bagianUrl = urlencode($bagianTeks);
            $urlHasil .= $bagianUrl;
            if ($i >= $in + 40 or $i >= $totalBaris) {
                $urlHasil .=
                    "&insert=false&config=base%2Fdatabase%2Fconfig%2Fstandard%2Fstandard_redir.ini&filename=a.yaml&emoji=true&list=false&udp=true&tfo=false&expand=false&scv=true&fdn=false&sort=false&new_name=true";
                $url = file_get_contents($urlHasil);
                $hasil = explode("proxies:", $url)[1];
                $hasil = explode("proxy-groups:", $hasil)[0];
                file_put_contents("a.yaml", $hasil, FILE_APPEND);
                $in += 40;
                $urlHasil = "https://sub.bonds.id/sub2?target=clash&url=";
            }
        }
        if ($i == $totalBaris or $i >= $totalBaris) {
            echo "\n";
            break;
        }
    }
}
require_once 'vendor/autoload.php'; // 
use Symfony\Component\Yaml\Yaml;
function convertToFormat($data) {
    $proxies = Yaml::parse($data)['proxies'];
    $formats = [];
    foreach ($proxies as $proxy) {
        $format = "";
        $server = "104.16.66.85";
        if(isset($proxy['servername']) == "" or isset($proxy['sni']) == "") {
            $servername = $proxy['server'];
            $server = "104.16.66.85";
        } else {
            if(isset($proxy['servername'])) {
                $servername = $proxy['servername'];
            }
            if(isset($proxy['sni'])) {
                $servername = $proxy['sni'];
            }
        }
        if ($proxy['type'] === 'vless') {
            if(isset($proxy['network'])) {
            if ($proxy['network'] === 'ws') {
                $format = 'vless://' . $proxy['uuid'] . '@' . $server . ':' . $proxy['port'] . '?path=' . $proxy['ws-opts']['path']. '&security=tls&encryption=none&host=' . $servername . '&type=ws&sni=' . $servername . '#' . $proxy["name"];
            } elseif ($proxy['network'] === 'grpc') {
                $format = 'vless://' . $proxy['uuid'] . '@' . $proxy['server'] . ':' . $proxy['port'] . '?mode=gun&security=tls&encryption=none&type=grpc&serviceName=' . $proxy['grpc-opts']['grpc-service-name'] . '&sni=' . $proxy['servername'] . '#' . $proxy["name"];
            }
            }
            $formats[] = $format;
        } elseif ($proxy['type'] === 'trojan') {
            if(isset($proxy['network'])) {
            if ($proxy['network'] === 'ws') {
                $format = 'trojan://' . $proxy['password'] . '@' . $server . ':' . $proxy['port'] . '?path=' . $proxy['ws-opts']['path'] . '&security=tls&host=' . $servername . '&type=ws&sni=' . $servername . '#' . $proxy["name"];
            } elseif ($proxy['network'] === 'grpc') {
                $format = 'trojan://' . $proxy['password'] . '@' . $proxy['server'] . ':' . $proxy['port'] . '?mode=gun&security=tls&type=grpc&serviceName=' . $proxy['grpc-opts']['grpc-service-name'] . '&sni=' . $proxy['sni'] . '#' . $proxy["name"];
            }
            }
            $formats[] = $format;
        } elseif ($proxy['type'] === 'vmess') {
            if(isset($proxy['network'])) {
            if($proxy['network'] == "ws") {
                if(isset($proxy['ws-opts'])) {
                $format = 'vmess://' . base64_encode(json_encode([
                    'add' => $server,
                    'aid' => $proxy['alterId'],
                    'host' => $servername,
                    'id' => $proxy['uuid'],
                    'net' => $proxy['network'],
                    'path' => $proxy['ws-opts']['path'],
                    'port' => $proxy['port'],
                    'ps' => $proxy['name'],
                    'scy' => $proxy['cipher'],
                    'sni' => $servername,
                    'tls' => $proxy['tls'] ? "tls" : "",
                    'type' => $proxy['type'],
                    'v' => "2",
                ]));
                }
            } elseif ($proxy['network'] == "grpc") {
                $format = 'vmess://' . base64_encode(json_encode([
                    'add' => $proxy['server'],
                    'aid' => $proxy['alterId'],
                    'id' => $proxy['uuid'],
                    'net' => $proxy['network'],
                    'path' => $proxy['grpc-opts']['grpc-service-name'],
                    'port' => $proxy['port'],
                    'ps' => $proxy['name'],
                    'scy' => $proxy['cipher'],
                    'sni' => $proxy['servername'],
                    'tls' => $proxy['tls'] ? "tls" : "",
                    'type' => $proxy['type'],
                    'v' => "2",
                ]));
            }
            }
            $formats[] = $format;
        }
    }
    return $formats;
}
/*$url_clash_format = [
    "https://raw.githubusercontent.com/WilliamStar007/ClashX-V2Ray-TopFreeProxy/main/combine/clash.config.yaml",
];
$query="&insert=false&config=base%2Fdatabase%2Fconfig%2Fstandard%2Fstandard_redir.ini&filename=a.yaml&emoji=true&list=false&udp=true&tfo=false&expand=false&scv=true&fdn=false&sort=false&new_name=true";          
foreach($url_clash_format as $link) {
    $isi = file_get_contents($link);
    $format = convertToFormat($isi);
    $urlHasil = "https://sub.bonds.id/sub2?target=clash&url=";
    foreach($format as $for) {
        $urlHasil = "https://sub.bonds.id/sub2?target=clash&url=";
        if($for != "") {
            $url = file_get_contents($urlHasil.$for.$query);
            $hasil = explode("proxies:", $url)[1];
            $hasil = explode("proxy-groups:", $hasil)[0];
            if(strpos($hasil, '~') === false){
               file_put_contents("b.yaml", $hasil, FILE_APPEND);
            }
        }
    }
}*/
?>
