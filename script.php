<?php
$isi = file_get_contents("https://raw.githubusercontent.com/mahdibland/V2RayAggregator/master/sub/sub_merge.txt");
$baris = explode("\n", $isi);
// Menghitung total baris
$totalBaris = count($baris);

// Menginisialisasi variabel untuk menyimpan hasil URL
$i = 0;
while (true) {
  $urlHasil = "https://sub.bonds.id/sub2?target=clash&url=";
  $bagian = $baris[$i];
  echo $i." => ".$bagian."\n";
  $bagianTeks = $bagian;
  $bagianUrl = urlencode($bagianTeks);
  $urlHasil .= $bagianUrl;
  $urlHasil .="&insert=false&config=base%2Fdatabase%2Fconfig%2Fstandard%2Fstandard_redir.ini&filename=a.yaml&emoji=true&list=false&udp=true&tfo=false&expand=false&scv=true&fdn=false&sort=false&new_name=true";
  $url = file_get_contents($urlHasil);
  $hasil = explode("proxies:", $url)[1];
  $hasil = explode("proxy-groups:", $hasil)[0];
  file_put_contents("a.yaml", $hasil, FILE_APPEND);
  $i++;
  if ($i == $totalBaris) {
    break;
  }
}
?>
