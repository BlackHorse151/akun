<?php
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
                $servername = $proxy['server'];
            }
            if(isset($proxy['sni'])) {
                $servername = $proxy['server'];
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

$url = [
    "https://raw.githubusercontent.com/yebekhe/ConfigCollector/main/sub/mix_base64",
    "https://raw.githubusercontent.com/adiwzx/freenode/main/adispeed.txt",
    "https://raw.githubusercontent.com/snakem982/proxypool/main/v2ray.txt",
    "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix",
];
file_put_contents("sing-box.txt", "");
foreach ($url as $link) {
    if ( base64_encode(base64_decode(file_get_contents($link), true)) === file_get_contents($link)){
        $isi = file_get_contents($link);
    } else {
        $isi = base64_encode(file_get_contents($link));
    }
    file_put_contents("dump.txt", $isi);
    shell_exec("chmod +x ./lite-linux-amd64");
    shell_exec("./lite-linux-amd64 --config config.json --test https://raw.githubusercontent.com/ardi5209/akun/main/dump.txt");
    $hasil = json_decode(file_get_contents("output.json"));
    file_put_contents("sing-box.txt", "");
    foreach($hasil->nodes as $akun) {
        if($akun->isok == true) {
            file_put_contents("sing-box.txt", $akun->link."\n", FILE_APPEND);
        }
    }
}
$akun = explode("\n", file_get_contents("sing-box.txt"));
$total = count($akun);
file_put_contents("a.yaml", "proxies:");
foreach($akun as $i) {
    if (strpos($i, 'ss://') === false and strpos($i, 'ssr://') ===false) {
    //if (preg_match("/(vless|vmess|trojan):\/\//i", $i)) {
        echo $i."\n";
        $url = "https://sub.bonds.id/sub2?target=clash&url=";
        $url .= urlencode($i);
        $url .= "&insert=false&config=base%2Fdatabase%2Fconfig%2Fstandard%2Fstandard_redir.ini&filename=a.yaml&emoji=true&list=false&udp=true&tfo=false&expand=false&scv=true&fdn=false&sort=false&new_name=true";
        $hasil = file_get_contents($url);
        $hasil = explode("proxies:", $hasil)[1];
        $hasil = explode("proxy-groups:", $hasil)[0];
        if(strpos($hasil, '~') === false){
               file_put_contents("a.yaml", $hasil, FILE_APPEND);
        }
    }
}
    /*
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

$hasil = "";
$formats = convertToFormat(file_get_contents("https://raw.githubusercontent.com/WilliamStar007/ClashX-V2Ray-TopFreeProxy/main/combine/clash.config.yaml"));
foreach ($formats as $format) {
    if($format != "") {  
        $hasil .= $format."\n";
        file_put_contents("result.txt", $format."\n", FILE_APPEND);
        echo $format . "\n";
    }
}
/*file_put_contents("sing-box-base64.txt", base64_encode($hasil));
shell_exec("chmod +x ./lite-linux-amd64");
shell_exec("./lite-linux-amd64 --config config.json --test https://raw.githubusercontent.com/ardi5209/akun/main/sing-box-base64.txt");
$speedtest = json_decode(file_get_contents("output.json"));
file_put_contents("sing-box.txt", "");
foreach($speedtest->nodes as $akun) {
    if($akun->isok == true) {
        file_put_contents("sing-box.txt", $akun->link."\n", FILE_APPEND);
    }
}
*/
?>
