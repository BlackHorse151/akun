#准备好所需文件
wget -O lite-linux-amd64 https://github.com/Dreamacro/clash/releases/download/v1.11.8/clash-linux-amd64-v1.11.8.gz
wget -O lite_config.json https://raw.githubusercontent.com/ardi5209/akun/main/utils/lite_config.json
#运行 LiteSpeedTest
chmod +x ./lite-linux-amd64
sudo nohup ./lite-linux-amd64 --config ./lite_config.json --test subs >speedtest.log
