#准备好所需文件
wget -O lite-linux-amd64.gz https://github.com/xxf098/LiteSpeedTest/releases/download/v0.15.0/lite-linux-amd64-v3-v0.15.0.gz
gzip -d lite-linux-amd64.gz
chmod +x ./lite-linux-amd64
./lite-linux-amd64 --test https://raw.githubusercontent.com/ardi5209/akun/main/sing-box-base64.txt>speedtest.log
