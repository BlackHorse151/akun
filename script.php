<?php
// Mendapatkan isi dari link
$isi = file_get_contents('https://raw.githubusercontent.com/mahdibland/V2RayAggregator/master/sub/sub_merge.txt');

// Mengekoding isi menjadi URL-safe
$encodedIsi = urlencode($isi);

// Membentuk URL dengan menggabungkan isi yang telah dikodekan
$url = "https://sub.bonds.id/sub2?target=clash&url=$encodedIsi&insert=false&config=base%2Fdatabase%2Fconfig%2Fstandard%2Fstandard_redir.ini&filename=a.yaml&emoji=true&list=false&udp=true&tfo=false&expand=false&scv=true&fdn=false&sort=false&new_name=true";

// Output URL yang telah dibentuk
echo $url;
?
