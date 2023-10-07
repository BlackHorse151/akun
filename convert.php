 <?php
require_once 'vendor/autoload.php'; //
use Symfony\Component\Yaml\Yaml;
function curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($ch);
    curl_close($ch);
    return $resp;
}
function getFlags($country_code) {
    $flag = mb_convert_encoding('&#' . (127397 + ord($country_code[0])) . ';', 'UTF-8', 'HTML-ENTITIES');
    $flag.= mb_convert_encoding('&#' . (127397 + ord($country_code[1])) . ';', 'UTF-8', 'HTML-ENTITIES');
    return $flag;
}
function convertToFormat($data) {
    $proxies = Yaml::parse($data)['proxies'];
    $formats = [];
    $id = "";
    foreach ($proxies as $proxy) {
        $bug = array(/*"104.17.72.206",*/ "104.16.66.85");
        foreach ($bug as $bg) {
            $uid = $proxy["server"];
            $ip_info = json_decode(curl("http://ip-api.com/json/{$uid}"));        
            $nama = $uid." ".rand(1000, 9999);
            echo "$nama \n";
            if (isset($ip_info->status)) {
                if($ip_info->status == "success") {
                    $flag = getFlags($ip_info->countryCode);
                    $nama = "{$flag} {$ip_info->countryCode} {$ip_info->as} " . rand(1000, 9999);
                }
            } else {
                $ip_info = json_decode(curl("ipinfo.io/{$uid}/json"));
                if (isset($ip_info->org)) {
                    $flag = getFlags($ip_info->country);
                    $nama = "{$flag} {$ip_info->country} {$ip_info->org} " . rand(1000, 9999);
                }
            }
            $id.= $uid . " ";
            $format = "";
            $server = $bg;
            if ($proxy["ws-opts"]["headers"]["Host"] != "") {
                $servername = $proxy["ws-opts"]["headers"]["Host"];
                $server = $bg;
            }
            if ($proxy["type"] == "vmess" or $proxy["type"] == "vless") {
                if ($proxy["ws-opts"]["headers"]["Host"] == "") { //or isset($proxy['sni']) == "") {
                    $servername = $proxy['server'];
                    $server = $bg;
                }
            }
            if ($proxy["type"] == "trojan") {
                if ($proxy["sni"] == "") {
                    $servername = $proxy['server'];
                    $server = $bg;
                }
            }
            if ($proxy['type'] === 'vless') {
                if (isset($proxy['network'])) {
                    if ($proxy['network'] === 'ws') {
                        $format = 'vless://' . $proxy['uuid'] . '@' . $server . ':' . $proxy['port'] . '?path=' . $proxy['ws-opts']['path'] . '&security=tls&encryption=none&host=' . $servername . '&type=ws&sni=' . $servername . '#' . $nama;
                    } elseif ($proxy['network'] === 'grpc') {
                        $format = 'vless://' . $proxy['uuid'] . '@' . $proxy['server'] . ':' . $proxy['port'] . '?mode=gun&security=tls&encryption=none&type=grpc&serviceName=' . $proxy['grpc-opts']['grpc-service-name'] . '&sni=' . $proxy['servername'] . '#' . $nama;
                    }
                }
                $formats[] = $format;
            } elseif ($proxy['type'] === 'trojan') {
                if (isset($proxy['network'])) {
                    if ($proxy['network'] === 'ws') {
                        $format = 'trojan://' . $proxy['password'] . '@' . $server . ':' . $proxy['port'] . '?path=' . $proxy['ws-opts']['path'] . '&security=tls&host=' . $servername . '&type=ws&sni=' . $servername . '#' . $nama;
                    } elseif ($proxy['network'] === 'grpc') {
                        $format = 'trojan://' . $proxy['password'] . '@' . $proxy['server'] . ':' . $proxy['port'] . '?mode=gun&security=tls&type=grpc&serviceName=' . $proxy['grpc-opts']['grpc-service-name'] . '&sni=' . $proxy['sni'] . '#' . $nama;
                    }
                }
                $formats[] = $format;
            } elseif ($proxy['type'] === 'vmess') {
                if (isset($proxy['network'])) {
                    if ($proxy['network'] == "ws") {
                        if (isset($proxy['ws-opts'])) {
                            $format = 'vmess://' . base64_encode(json_encode(['add' => $server, 'aid' => $proxy['alterId'], 'host' => $servername, 'id' => $proxy['uuid'], 'net' => $proxy['network'], 'path' => $proxy['ws-opts']['path'], 'port' => $proxy['port'], 'ps' => $nama, 'scy' => $proxy['cipher'], 'sni' => $servername, 'tls' => $proxy['tls'] ? "tls" : "", 'type' => $proxy['type'], 'v' => "2", ]));
                        }
                    } elseif ($proxy['network'] == "grpc") {
                        $format = 'vmess://' . base64_encode(json_encode(['add' => $proxy['server'], 'aid' => $proxy['alterId'], 'id' => $proxy['uuid'], 'net' => $proxy['network'], 'path' => $proxy['grpc-opts']['grpc-service-name'], 'port' => $proxy['port'], 'ps' => $nama, 'scy' => $proxy['cipher'], 'sni' => $proxy['servername'], 'tls' => $proxy['tls'] ? "tls" : "", 'type' => $proxy['type'], 'v' => "2", ]));
                    }
                }
                $formats[] = $format;
            }
            echo $nama." => ".$format."\n";
        }
    }
    return $formats;
}
$hasil = "";
$formats = convertToFormat(file_get_contents("b.yaml"));
foreach ($formats as $format) {
    if ($format != "") {
        $hasil.= $format . "\n";
        file_put_contents("result.txt", $format."\n", FILE_APPEND);
        //echo $format . "\n";
    }
}
file_put_contents("sing-box-base64.txt", base64_encode($hasil));
//shell_exec("gzip -d lite-linux-amd64.gz");
?>
