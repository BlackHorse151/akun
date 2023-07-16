<?php
require_once 'vendor/autoload.php'; // 
use Symfony\Component\Yaml\Yaml;
function convertToFormat($data) {
    $proxies = Yaml::parse($data)['proxies'];
    $formats = [];
    foreach ($proxies as $proxy) {
        $format = "";
        $server = "104.16.66.85";
        if($proxy['servername'] == "" or $proxy['sni'] == "") {
            $servername = $proxy['server'];
            $server = "104.16.66.85";
        } else {
            if($proxy['servername'] != "") {
                $servername = $proxy['servername'];
            }
            if($proxy['sni'] != "") {
                $servername = $proxy['sni'];
            }
        }
        if ($proxy['type'] === 'vless') {
            if(isset($proxy['network'])) {
            if ($proxy['network'] === 'ws') {
                $format = 'vless://' . $proxy['uuid'] . '@' . $proxy['server'] . ':' . $proxy['port'] . '?path=' . urlencode($proxy['ws-opts']['path']) . '&security=tls&encryption=none&host=' . $servername . '&type=ws&sni=' . $servername . '#' . $proxy["name"];
            } elseif ($proxy['network'] === 'grpc') {
                $format = 'vless://' . $proxy['uuid'] . '@' . $proxy['server'] . ':' . $proxy['port'] . '?mode=gun&security=tls&encryption=none&type=grpc&serviceName=' . urlencode($proxy['grpc-opts']['grpc-service-name']) . '&sni=' . $proxy['servername'] . '#' . $proxy["name"];
            }
            }
            $formats[] = $format;
        } elseif ($proxy['type'] === 'trojan') {
            if(isset($proxy['network'])) {
            if ($proxy['network'] === 'ws') {
                $format = 'trojan://' . $proxy['password'] . '@' . $server . ':' . $proxy['port'] . '?path=' . urlencode($proxy['ws-opts']['path']) . '&security=tls&host=' . $servername . '&type=ws&sni=' . $servername . '#' . $proxy["name"];
            } elseif ($proxy['network'] === 'grpc') {
                $format = 'trojan://' . $proxy['password'] . '@' . $proxy['server'] . ':' . $proxy['port'] . '?mode=gun&security=tls&type=grpc&serviceName=' . urlencode($proxy['grpc-opts']['grpc-service-name']) . '&sni=' . $proxy['sni'] . '#' . $proxy["name"];
            }
            }
            $formats[] = $format;
        } elseif ($proxy['type'] === 'vmess') {
            if(isset($proxy['network'])) {
            if($proxy['network'] == "ws") {
                $format = 'vmess://' . base64_encode(json_encode([
                    'add' => $server,
                    'aid' => $proxy['alterId'],
                    'host' => $servername,
                    'id' => $proxy['uuid'],
                    'net' => $proxy['network'],
                    'path' => urlencode($proxy['ws-opts']['path']),
                    'port' => $proxy['port'],
                    'ps' => $proxy['name'],
                    'scy' => $proxy['cipher'],
                    'sni' => $servername,
                    'tls' => $proxy['tls'] ? "tls" : "",
                    'type' => $proxy['type'],
                    'v' => "2",
                ]));
            } elseif ($proxy['network'] == "grpc") {
                $format = 'vmess://' . base64_encode(json_encode([
                    'add' => $proxy['server'],
                    'aid' => $proxy['alterId'],
                    'id' => $proxy['uuid'],
                    'net' => $proxy['network'],
                    'path' => urlencode($proxy['grpc-opts']['grpc-service-name']),
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
$hasil = "";
$formats = convertToFormat(file_get_contents("b.yaml"));
foreach ($formats as $format) {
    if($format != "") {  
        $hasil .= $format."\n";
        //file_put_contents("result.txt", $format."\n", FILE_APPEND);
        echo $format . "\n";
    }
}
file_put_contents("sing-box-base64.txt", base64_encode($hasil));
shell_exec("chmod +x ./lite-linux-amd64");
shell_exec("./lite-linux-amd64 --config config.json --test https://raw.githubusercontent.com/ardi5209/akun/main/sing-box-base64.txt");
$speedtest = json_decode(file_get_contents("output.json"));
file_put_contents("sing-box.txt", "");
foreach($speedtest->nodes as $akun) {
    if($akun->isok == true) {
        file_put_contents("sing-box.txt", $akun->link."\n", FILE_APPEND);
    }
}

?>
