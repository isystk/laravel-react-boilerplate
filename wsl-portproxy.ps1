<#
.SYNOPSIS
    WSL2 ポートフォワーディング自動設定スクリプト

.DESCRIPTION
    WSL2は内部ネットワーク(NAT)で動作しているため、外部のPCからWindowsのIPにアクセスしても、
    通常はWSL2内のサービス（Webサーバー等）には繋がりません。

    このスクリプトは以下の3ステップを自動化します：
    1. WSL2インスタンスの動的な内部IPアドレスを取得する。
    2. Windowsの特定のポート(ListenPort)への通信を、WSL2のIP(ConnectPort)へ転送するよう設定(netsh)。
    3. Windowsのファイアウォールを開放し、外部からのパケットを許可する。

    ※実行には「管理者権限」が必要です。
#>

# ===== 設定 =====
$ListenPort  = 80         # Windows側で待ち受けるポート
$ConnectPort = 80         # WSL側のWebサーバーポート
$Distro      = "Ubuntu"   # WSLディストリ名（省略可）

# ===== WSLのIPを取得 =====
$WslIp = wsl -d $Distro hostname -I
$WslIp = $WslIp.Trim().Split(" ")[0]

Write-Host "WSL IP: $WslIp"

# ===== 既存の portproxy を削除 =====
netsh interface portproxy delete v4tov4 `
  listenaddress=0.0.0.0 listenport=$ListenPort

# ===== portproxy を再登録 =====
netsh interface portproxy add v4tov4 `
  listenaddress=0.0.0.0 listenport=$ListenPort `
  connectaddress=$WslIp connectport=$ConnectPort

# ===== ファイアウォール（初回のみ） =====
if (-not (Get-NetFirewallRule -DisplayName "WSL Port $ListenPort" -ErrorAction SilentlyContinue)) {
  New-NetFirewallRule `
    -DisplayName "WSL Port $ListenPort" `
    -Direction Inbound `
    -Protocol TCP `
    -LocalPort $ListenPort `
    -Action Allow
}

Write-Host "portproxy updated!"
