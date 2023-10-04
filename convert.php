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
        $bug = array("104.17.72.206", "104.16.66.85");
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
                print_r($ip_info);
                echo "\nCek Baru\n";
            }
            $id.= $uid . " ";
            $format = "";
            $server = $bg;
            if ($proxy["ws-opts"]["headers"]["Host"] != "") {
                $servername = $proxy["ws-opts"]["headers"]["Host"];
                $server = $bg;
            }
            if ($proxy["type"] == "vmess" or $proxy["type"] == "vless") {
                if ($proxy["servername"] == "") { //or isset($proxy['sni']) == "") {
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
shell_exec("chmod +x ./lite-linux-amd64 && ./lite-linux-amd64 --config config.json --test https://raw.githubusercontent.com/ardi5209/akun/main/sing-box-base64.txt");
$speedtest = json_decode(file_get_contents("output.json"));
file_put_contents("sing-box.txt", "");
file_put_contents("sing-box.yaml", "proxies:");
file_put_contents("hasil_convert(untest).yaml", "proxies:");
$query = "&insert=false&config=base%2Fdatabase%2Fconfig%2Fstandard%2Fstandard_redir.ini&filename=a.yaml&emoji=true&list=false&udp=true&tfo=false&expand=false&scv=true&fdn=false&sort=false&new_name=true";
$check = "";
foreach ($speedtest->nodes as $akun) {
    if ($akun->isok == true) {
        file_put_contents("sing-box.txt", $akun->link . "\n", FILE_APPEND);
        $urlHasil = "https://sub.bonds.id/sub2?target=clash&url=";
        $akn = urlencode($akun->link);
        $url = file_get_contents($urlHasil . $akn . $query);
        $hasil = explode("proxies:", $url) [1];
        $hasil = explode("proxy-groups:", $hasil) [0];
        $tes_check = explode("network: ws", explode("type: vmess",$hasil)[1])[0];
        if(preg_match("/^{$tes_check}/im",$check)) {
            echo "node sudah ada \n";
        } else {
            $check .= $tes_check."\n";
            file_put_contents("sing-box.yaml", $hasil, FILE_APPEND);
        }
    }
    $urlHasil = "https://sub.bonds.id/sub2?target=clash&url=";
    $akn = urlencode($akun->link);
    $url = file_get_contents($urlHasil . $akn . $query);
    $hasil = explode("proxies:", $url) [1];
    $hasil = explode("proxy-groups:", $hasil) [0];
    file_put_contents("hasil_convert(untest).yaml", $hasil, FILE_APPEND);
}
?>
