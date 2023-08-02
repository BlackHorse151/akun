<?php
$date = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
$tanggal = $date->format('Y/m/Ymd').".txt";
$tanggal2 = "data".$date->format('Ymj').".txt";
$url = [
    "https://clashnode.com/wp-content/uploads/".$tanggal,
    "https://nodefree.org/dy/".$tanggal,
    "https://raw.githubusercontent.com/mianfeifq/share/main/".$tanggal2,
    "https://v2rayshare.com/wp-content/uploads/".$tanggal,
    "https://www.ckcloud.xyz/api/v1/client/subscribe?token=1b5f05083c51f45f79febf8fa7c3732e",
    "https://fastestcloud.xyz/api/v1/client/subscribe?token=ea2c076fc30f6ed135883fcc87f74576",
    "https://feiniaoyun.xyz/api/v1/client/subscribe?token=684c75425bbdcb1b5995594cd53297c0",
    "https://www.ckcloud.xyz/api/v1/client/subscribe?token=b0c8ae9dd0a5ffaf67c1166da354feb3",
    "https://fastestcloud.xyz/api/v1/client/subscribe?token=16a94a468eef0998bc07b3d037fba188",
    "https://feiniaoyun.xyz/api/v1/client/subscribe?token=e0d5448650a5a75235592a77e5ce4e93",
    "https://www.ckcloud.xyz/api/v1/client/subscribe?token=6ace919b50dc1ec9fa3dbd53951db1d3",
    "https://fastestcloud.xyz/api/v1/client/subscribe?token=4fd5044a06d52e2fe74ef331d01b1a8d",
    "https://feiniaoyun.xyz/api/v1/client/subscribe?token=e326c1f5873797c1af0c53bafaf0381f",
    "https://www.ckcloud.xyz/api/v1/client/subscribe?token=00188216ca2d018bdab388245fba36a2",
    "https://fastestcloud.xyz/api/v1/client/subscribe?token=4b67c61b6e9d22b834a25938e1f0f72f",
    "https://feiniaoyun.xyz/api/v1/client/subscribe?token=a0a6e41934f6d693140d797e61c022c8",
    "https://service-o5lxqvaa-1300544878.gz.apigw.tencentcs.com/link/Vq25Sy6IYW85y5hw?sub=3",
    "https://www.jafiyun2023.shop/link/cLmSRwtSeBIDoboO?clash=1
https://service-o5lxqvaa-1300544878.gz.apigw.tencentcs.com/link/e9zv6R9ttovwtiPD?sub=3",
    "https://raw.githubusercontent.com/yebekhe/ConfigCollector/main/sub/mix_base64",
    "https://ghproxy.com/https://raw.githubusercontent.com/Pawdroid/Free-servers/main/sub", 
    "https://raw.githubusercontent.com/adiwzx/freenode/main/adispeed.txt", 
    "https://raw.githubusercontent.com/snakem982/proxypool/main/v2ray.txt", 
    "https://raw.githubusercontent.com/ZywChannel/free/main/sub", 
    "https://raw.githubusercontent.com/gfwcross/v2pool/main/merge/all.txt", 
    //"https://raw.githubusercontent.com/Bardiafa/Free-V2ray-Config/main/All_Configs_Sub.txt", 
    "https://raw.githubusercontent.com/LonUp/NodeList/main/V2RAY/Latest.txt", 
    "https://raw.githubusercontent.com/Leon406/SubCrawler/main/sub/share/all3", 
    "https://raw.githubusercontent.com/mianfeifq/share/main/README.md", 
    "https://raw.githubusercontent.com/Lewis-1217/FreeNodes/main/bpjzx1", 
    "https://raw.githubusercontent.com/Lewis-1217/FreeNodes/main/bpjzx2", 
    "https://raw.githubusercontent.com/peasoft/NoMoreWalls/master/list_raw.txt", 
    "https://muma16fx.netlify.app/", 
    "https://youlianboshi.netlify.app/", 
    "https://qiaomenzhuanfx.netlify.app/", 
    "https://raw.githubusercontent.com/mfuu/v2ray/master/v2ray", 
    "https://raw.githubusercontent.com/tbbatbb/Proxy/master/dist/v2ray.config.txt", 
    'https://raw.githubusercontent.com/MrPooyaX/VpnsFucking/main/Shenzo.txt', 
    'https://raw.githubusercontent.com/MrPooyaX/SansorchiFucker/main/data.txt', 
    'https://mrpooya.xyz/api/ramezan/fastRay.php?sub=1', 
    "https://raw.githubusercontent.com/HakurouKen/free-node/main/public", 
];
//https://raw.githubusercontent.com/snakem982/proxypool/main/v2ray.txt
file_put_contents("a.yaml", "proxies:");
foreach ($url as $link) {
		if (base64_encode(base64_decode(file_get_contents($link) , true)) === file_get_contents($link)) {
				$isi = base64_decode(file_get_contents($link));
		} elseif (preg_match("/```/", file_get_contents($link))) {
				$a = explode("```", file_get_contents($link)) [1];
				$isi = explode("```", $a) [0];
		} else {
				$isi = file_get_contents($link);
		}
		$baris = explode("\n", $isi);
		// Menghitung total baris
		$totalBaris = count($baris);
		$urlHasil = "https://sub.bonds.id/sub2?target=clash&url=";
		$git = explode("://", $link) [1];
		//file_put_contents("a.yaml", "proxies:");
		// Menginisialisasi variabel untuk menyimpan hasil URL
		//$in = 0;
		$i = 0;
		foreach ($baris as $bar) {
				$urlHasil = "https://sub.bonds.id/sub2?target=clash&url=";
				//for ($i = 0; $i < $totalBaris; $i++) {
				$bagian = $bar;
				echo "\r                                               \r";
				//if (strpos($bagian, 'ss://') === false or strpos($bagian, 'ssr://') ===false) {
				if (preg_match("/(vmess):\/\//i", $bagian)) {
						echo " [{$i}/{$totalBaris}] {$git} \r";
						$bagianTeks = $bagian . "|";
						$bagianUrl = urlencode($bagianTeks);
						$urlHasil .= $bagianUrl;
						$urlHasil .= "&insert=false&config=base%2Fdatabase%2Fconfig%2Fstandard%2Fstandard_redir.ini&filename=a.yaml&emoji=true&list=false&udp=true&tfo=false&expand=false&scv=true&fdn=false&sort=false&new_name=true";
						$url = file_get_contents($urlHasil);
						$hasil = explode("proxies:", $url) [1];
						$hasil = explode("proxy-groups:", $hasil) [0];
						if (strpos($hasil, '~') === false) {
								file_put_contents("a.yaml", $hasil, FILE_APPEND);
						}
						$urlHasil = "https://sub.bonds.id/sub2?target=clash&url=";
				}
				$i += 1;
		}
}
?>
