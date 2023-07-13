<?php
//https://raw.githubusercontent.com/mahdibland/V2RayAggregator/master/sub/sub_merge.txt
//https://raw.githubusercontent.com/Bardiafa/Free-V2ray-Config/main/All_Configs_Sub.txt
//https://raw.githubusercontent.com/yebekhe/ConfigCollector/main/sub/mix_base64
$url = [
    "https://raw.githubusercontent.com/snakem982/proxypool/main/v2ray.txt",
    "https://raw.githubusercontent.com/ardi5209/TelegramV2rayCollector/main/sub/mix_base64",
];
//https://raw.githubusercontent.com/snakem982/proxypool/main/v2ray.txt
file_put_contents("a.yaml", "proxies:");
foreach ($url as $link) {
    $isi = base64_decode(file_get_contents($link));
    $baris = explode("\n", $isi);
    // Menghitung total baris
    $totalBaris = count($baris);
    $urlHasil = "https://sub.bonds.id/sub2?target=clash&url=";
    //file_put_contents("a.yaml", "proxies:");
    // Menginisialisasi variabel untuk menyimpan hasil URL
    $in = 0;
    $urlHasil = "https://sub.bonds.id/sub2?target=clash&url=";
    for ($i = 0; $i < $totalBaris; $i++) {
        $bagian = $baris[$i];
        if (preg_match("/(vmess)/i", $bagian)) {
            echo $i . " => " . $bagian . "\n";
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
            break;
        }
    }
}
?>
