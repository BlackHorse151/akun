package main

import (
	"fmt"
	"io/ioutil"
	"log"
	"net"
	"net/http"
	"time"

	"github.com/gorilla/websocket"
	"gopkg.in/yaml.v2"
)

type Proxy struct {
	Name            string `yaml:"name"`
	Server          string `yaml:"server"`
	Port            int    `yaml:"port"`
	Type            string `yaml:"type"`
	UUID            string `yaml:"uuid"`
	Cipher          string `yaml:"cipher"`
	TLS             bool   `yaml:"tls"`
	SkipCertVerify  bool   `yaml:"skip-cert-verify"`
	ServerName      string `yaml:"servername"`
	Network         string `yaml:"network"`
	WsOpts          WsOpts `yaml:"ws-opts"`
	UDP             bool   `yaml:"udp"`
}

type WsOpts struct {
	Path    string            `yaml:"path"`
	Headers map[string]string `yaml:"headers"`
}

func main() {
	// Baca file YAML
	yamlFile, err := ioutil.ReadFile("sing-box.yaml")
	if err != nil {
		log.Fatalf("Gagal membaca file YAML: %v", err)
	}

	// Parse konfigurasi YAML
	var proxies []Proxy
	err = yaml.Unmarshal(yamlFile, &proxies)
	if err != nil {
		log.Fatalf("Gagal memparse konfigurasi YAML: %v", err)
	}

	// Tes koneksi untuk setiap proxy
	for _, proxy := range proxies {
		fmt.Printf("Menguji koneksi untuk proxy: %s\n", proxy.Name)

		switch proxy.Type {
		case "vless", "vmess", "trojan":
			err := testConnection(proxy)
			if err != nil {
				fmt.Printf("Koneksi ke %s gagal: %v\n", proxy.Name, err)
			} else {
				fmt.Printf("Koneksi ke %s berhasil\n", proxy.Name)
			}
		default:
			fmt.Printf("Tipe proxy tidak didukung: %s\n", proxy.Type)
		}
	}
}

func testConnection(proxy Proxy) error {
	var dialer net.Dialer
	if !proxy.TLS {
		dialer = net.Dialer{
			Timeout: 10 * time.Second,
		}
	} else {
		dialer = net.Dialer{
			Timeout:   10 * time.Second,
			TLSConfig: &tls.Config{InsecureSkipVerify: proxy.SkipCertVerify},
		}
	}

	switch proxy.Network {
	case "ws":
		url := fmt.Sprintf("wss://%s:%d%s", proxy.Server, proxy.Port, proxy.WsOpts.Path)
		headers := http.Header{"Host": []string{proxy.ServerName}}
		dialer.NetDial = func(network, addr string) (net.Conn, error) {
			conn, err := net.DialTimeout(network, addr, dialer.Timeout)
			if err != nil {
				return nil, err
			}

			wsConn, _, err := websocket.NewClient(conn, &url, headers, 1024, 1024)
			if err != nil {
				return nil, err
			}

			return wsConn.UnderlyingConn(), nil
		}
	default:
		return fmt.Errorf("Tipe jaringan tidak didukung: %s", proxy.Network)
	}

	conn, err := dialer.Dial("tcp", fmt.Sprintf("%s:%d", proxy.Server, proxy.Port))
	if err != nil {
		return err
	}
	defer conn.Close()

	return nil
}