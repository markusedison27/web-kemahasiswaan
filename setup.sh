#!/bin/bash

# =============================================================================
#  SETUP SERVER - WEB KEMAHASISWAAN (SIAKAD)
#  Laravel 12 | PHP 8.2 | MySQL | Nginx | SSH
#  Ubuntu Server - VirtualBox Local Deployment
#  Dibuat otomatis - jalankan sebagai root atau dengan sudo
# =============================================================================

set -euo pipefail

# ───────────────────────────────────────────────────────
# WARNA TERMINAL
# ───────────────────────────────────────────────────────
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
MAGENTA='\033[0;35m'
BOLD='\033[1m'
RESET='\033[0m'

# ───────────────────────────────────────────────────────
# FUNGSI BANNER
# ───────────────────────────────────────────────────────
print_banner() {
    clear
    echo -e "${CYAN}${BOLD}"
    echo "  ╔══════════════════════════════════════════════════════════════╗"
    echo "  ║          SETUP SERVER - WEB KEMAHASISWAAN (SIAKAD)          ║"
    echo "  ║        Laravel 12 | PHP 8.2 | MySQL | Nginx | SSH           ║"
    echo "  ║              Ubuntu Server - VirtualBox Local                ║"
    echo "  ╚══════════════════════════════════════════════════════════════╝"
    echo -e "${RESET}"
}

print_step() {
    echo -e "\n${BLUE}${BOLD}┌─────────────────────────────────────────────────────┐${RESET}"
    echo -e "${BLUE}${BOLD}│  ► $1${RESET}"
    echo -e "${BLUE}${BOLD}└─────────────────────────────────────────────────────┘${RESET}\n"
}

print_ok() {
    echo -e "  ${GREEN}✔  $1${RESET}"
}

print_warn() {
    echo -e "  ${YELLOW}⚠  $1${RESET}"
}

print_error() {
    echo -e "  ${RED}✘  $1${RESET}"
}

print_info() {
    echo -e "  ${CYAN}ℹ  $1${RESET}"
}

separator() {
    echo -e "${MAGENTA}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${RESET}"
}

# ───────────────────────────────────────────────────────
# CEK ROOT
# ───────────────────────────────────────────────────────
check_root() {
    if [[ $EUID -ne 0 ]]; then
        echo -e "${RED}${BOLD}✘ Script ini harus dijalankan sebagai root atau dengan sudo!${RESET}"
        echo -e "${YELLOW}  Gunakan: sudo bash setup.sh${RESET}"
        exit 1
    fi
}

# ───────────────────────────────────────────────────────
# DETEKSI OTOMATIS INFORMASI SERVER
# ───────────────────────────────────────────────────────
detect_server_info() {
    print_step "DETEKSI INFORMASI SERVER"

    # Deteksi Network Interface
    PRIMARY_IFACE=$(ip route | grep '^default' | awk '{print $5}' | head -n1)
    if [[ -z "$PRIMARY_IFACE" ]]; then
        PRIMARY_IFACE=$(ip link show | grep -v lo | grep 'state UP' | awk -F': ' '{print $2}' | head -n1)
    fi

    # Deteksi IP Address
    SERVER_IP=$(ip addr show "$PRIMARY_IFACE" 2>/dev/null | grep 'inet ' | awk '{print $2}' | cut -d/ -f1 | head -n1)
    if [[ -z "$SERVER_IP" ]]; then
        SERVER_IP=$(hostname -I | awk '{print $1}')
    fi

    # Deteksi Hostname
    CURRENT_HOSTNAME=$(hostname)

    # Deteksi OS
    OS_NAME=$(lsb_release -ds 2>/dev/null || cat /etc/os-release | grep PRETTY_NAME | cut -d'"' -f2)

    # Deteksi CPU & RAM
    CPU_CORES=$(nproc)
    RAM_TOTAL=$(free -h | awk '/^Mem:/ {print $2}')
    DISK_FREE=$(df -h / | awk 'NR==2 {print $4}')

    # Tampilkan info yang terdeteksi
    separator
    echo -e "${BOLD}  INFORMASI YANG TERDETEKSI SECARA OTOMATIS:${RESET}"
    separator
    print_info "OS           : ${OS_NAME}"
    print_info "IP Address   : ${SERVER_IP}"
    print_info "Interface    : ${PRIMARY_IFACE}"
    print_info "Hostname     : ${CURRENT_HOSTNAME}"
    print_info "CPU Cores    : ${CPU_CORES}"
    print_info "RAM          : ${RAM_TOTAL}"
    print_info "Disk Bebas   : ${DISK_FREE}"
    separator
}

# ───────────────────────────────────────────────────────
# FORM INPUT DATA DARI PENGGUNA
# ───────────────────────────────────────────────────────
collect_user_input() {
    print_step "FORM KONFIGURASI SERVER"

    echo -e "${BOLD}${YELLOW}  Silakan isi data konfigurasi berikut.${RESET}"
    echo -e "${YELLOW}  (Tekan ENTER untuk menggunakan nilai default dalam [ ])${RESET}\n"

    # ── Nama Aplikasi ──
    echo -e "${CYAN}  ┌─ KONFIGURASI APLIKASI ───────────────────────────────────┐${RESET}"
    read -rp "$(echo -e "  ${BOLD}Nama Aplikasi${RESET}              [SIAKAD Web Kemahasiswaan]: ")" APP_NAME
    APP_NAME="${APP_NAME:-SIAKAD Web Kemahasiswaan}"

    # ── Domain / IP untuk Nginx ──
    read -rp "$(echo -e "  ${BOLD}Domain / IP Server${RESET}         [${SERVER_IP}]: ")" APP_DOMAIN
    APP_DOMAIN="${APP_DOMAIN:-$SERVER_IP}"

    # ── Environment ──
    read -rp "$(echo -e "  ${BOLD}Environment${RESET}                [production]: ")" APP_ENV
    APP_ENV="${APP_ENV:-production}"

    echo ""
    echo -e "${CYAN}  ├─ KONFIGURASI DATABASE ──────────────────────────────────┤${RESET}"

    # ── Database Name ──
    read -rp "$(echo -e "  ${BOLD}Nama Database${RESET}              [web_kemahasiswaan]: ")" DB_DATABASE
    DB_DATABASE="${DB_DATABASE:-web_kemahasiswaan}"

    # ── DB Username ──
    read -rp "$(echo -e "  ${BOLD}Username Database${RESET}          [siakad_user]: ")" DB_USERNAME
    DB_USERNAME="${DB_USERNAME:-siakad_user}"

    # ── DB Password ──
    while true; do
        read -rsp "$(echo -e "  ${BOLD}Password Database${RESET}          : ")" DB_PASSWORD
        echo ""
        if [[ -z "$DB_PASSWORD" ]]; then
            print_warn "Password tidak boleh kosong! Coba lagi."
        else
            read -rsp "$(echo -e "  ${BOLD}Konfirmasi Password DB${RESET}     : ")" DB_PASSWORD_CONFIRM
            echo ""
            if [[ "$DB_PASSWORD" == "$DB_PASSWORD_CONFIRM" ]]; then
                print_ok "Password database dikonfirmasi."
                break
            else
                print_warn "Password tidak cocok! Coba lagi."
            fi
        fi
    done

    # ── MySQL Root Password ──
    echo ""
    read -rsp "$(echo -e "  ${BOLD}Password MySQL Root${RESET}        : ")" MYSQL_ROOT_PASS
    echo ""

    echo ""
    echo -e "${CYAN}  ├─ KONFIGURASI SSH ───────────────────────────────────────┤${RESET}"

    # ── SSH Port ──
    read -rp "$(echo -e "  ${BOLD}Port SSH${RESET}                   [22]: ")" SSH_PORT
    SSH_PORT="${SSH_PORT:-22}"

    # ── SSH User ──
    read -rp "$(echo -e "  ${BOLD}Username SSH Baru${RESET}          [webadmin]: ")" SSH_USER
    SSH_USER="${SSH_USER:-webadmin}"

    # ── SSH Password ──
    while true; do
        read -rsp "$(echo -e "  ${BOLD}Password SSH User${RESET}          : ")" SSH_PASS
        echo ""
        if [[ -z "$SSH_PASS" ]]; then
            print_warn "Password SSH tidak boleh kosong!"
        else
            read -rsp "$(echo -e "  ${BOLD}Konfirmasi Password SSH${RESET}    : ")" SSH_PASS_CONFIRM
            echo ""
            if [[ "$SSH_PASS" == "$SSH_PASS_CONFIRM" ]]; then
                print_ok "Password SSH dikonfirmasi."
                break
            else
                print_warn "Password tidak cocok! Coba lagi."
            fi
        fi
    done

    echo ""
    echo -e "${CYAN}  ├─ KONFIGURASI SERVER TAMBAHAN ──────────────────────────┤${RESET}"

    # ── Hostname baru ──
    read -rp "$(echo -e "  ${BOLD}Hostname Server Baru${RESET}       [siakad-server]: ")" NEW_HOSTNAME
    NEW_HOSTNAME="${NEW_HOSTNAME:-siakad-server}"

    # ── Timezone ──
    read -rp "$(echo -e "  ${BOLD}Timezone${RESET}                   [Asia/Jakarta]: ")" SERVER_TZ
    SERVER_TZ="${SERVER_TZ:-Asia/Jakarta}"

    # ── Path instalasi project ──
    read -rp "$(echo -e "  ${BOLD}Path Instalasi Aplikasi${RESET}    [/var/www/siakad]: ")" APP_DIR
    APP_DIR="${APP_DIR:-/var/www/siakad}"

    # ── Apakah import SQL ──
    echo ""
    echo -e "${CYAN}  └─ IMPORT DATABASE ───────────────────────────────────────┘${RESET}"
    read -rp "$(echo -e "  ${BOLD}Import database dari file SQL?${RESET} (y/n) [y]: ")" IMPORT_SQL
    IMPORT_SQL="${IMPORT_SQL:-y}"

    if [[ "$IMPORT_SQL" =~ ^[Yy]$ ]]; then
        read -rp "$(echo -e "  ${BOLD}Path file SQL${RESET}              [./web_kemahasiswaan.sql]: ")" SQL_FILE
        SQL_FILE="${SQL_FILE:-./web_kemahasiswaan.sql}"
    fi

    # ───── Tampilkan Konfirmasi ─────
    echo ""
    separator
    echo -e "${BOLD}${GREEN}  ✔  KONFIGURASI ANDA:${RESET}"
    separator
    echo -e "  Nama Aplikasi     : ${BOLD}${APP_NAME}${RESET}"
    echo -e "  Domain/IP         : ${BOLD}${APP_DOMAIN}${RESET}"
    echo -e "  Environment       : ${BOLD}${APP_ENV}${RESET}"
    echo -e "  Database          : ${BOLD}${DB_DATABASE}${RESET}"
    echo -e "  DB User           : ${BOLD}${DB_USERNAME}${RESET}"
    echo -e "  SSH Port          : ${BOLD}${SSH_PORT}${RESET}"
    echo -e "  SSH User          : ${BOLD}${SSH_USER}${RESET}"
    echo -e "  Hostname Baru     : ${BOLD}${NEW_HOSTNAME}${RESET}"
    echo -e "  Timezone          : ${BOLD}${SERVER_TZ}${RESET}"
    echo -e "  Path Aplikasi     : ${BOLD}${APP_DIR}${RESET}"
    echo -e "  IP Server         : ${BOLD}${SERVER_IP}${RESET}"
    echo -e "  Network Interface : ${BOLD}${PRIMARY_IFACE}${RESET}"
    [[ "$IMPORT_SQL" =~ ^[Yy]$ ]] && echo -e "  Import SQL        : ${BOLD}${SQL_FILE}${RESET}"
    separator

    echo ""
    read -rp "$(echo -e "  ${BOLD}${YELLOW}Lanjutkan setup dengan konfigurasi di atas? (y/n): ${RESET}")" CONFIRM
    if [[ ! "$CONFIRM" =~ ^[Yy]$ ]]; then
        print_warn "Setup dibatalkan oleh pengguna."
        exit 0
    fi
}

# ───────────────────────────────────────────────────────
# 1. UPDATE & UPGRADE SISTEM
# ───────────────────────────────────────────────────────
setup_system() {
    print_step "1/10 | UPDATE & UPGRADE SISTEM"

    export DEBIAN_FRONTEND=noninteractive
    apt-get update -qq
    apt-get upgrade -y -qq
    apt-get install -y -qq \
        curl wget git unzip zip nano htop \
        net-tools ufw fail2ban \
        ca-certificates gnupg lsb-release \
        software-properties-common apt-transport-https

    # Set Hostname
    hostnamectl set-hostname "$NEW_HOSTNAME"
    echo "127.0.1.1  ${NEW_HOSTNAME}" >> /etc/hosts
    print_ok "Hostname diset ke: ${NEW_HOSTNAME}"

    # Set Timezone
    timedatectl set-timezone "$SERVER_TZ"
    print_ok "Timezone diset ke: ${SERVER_TZ}"

    print_ok "Sistem berhasil diupdate."
}

# ───────────────────────────────────────────────────────
# 2. SETUP SSH
# ───────────────────────────────────────────────────────
setup_ssh() {
    print_step "2/10 | SETUP SSH SERVER"

    # Buat user baru untuk SSH
    if id "$SSH_USER" &>/dev/null; then
        print_warn "User '${SSH_USER}' sudah ada, dilewati pembuatan."
    else
        useradd -m -s /bin/bash "$SSH_USER"
        echo "${SSH_USER}:${SSH_PASS}" | chpasswd
        usermod -aG sudo "$SSH_USER"
        print_ok "User SSH '${SSH_USER}' dibuat dan ditambahkan ke grup sudo."
    fi

    # Konfigurasi SSH
    SSH_CONFIG="/etc/ssh/sshd_config"
    cp "$SSH_CONFIG" "${SSH_CONFIG}.bak.$(date +%Y%m%d)"

    # Terapkan konfigurasi SSH yang aman
    cat > /etc/ssh/sshd_config.d/siakad-hardening.conf <<EOF
# =====================================================
# SSH Hardening Config - SIAKAD Web Kemahasiswaan
# =====================================================
Port ${SSH_PORT}
Protocol 2
LoginGraceTime 30
MaxAuthTries 5
MaxSessions 10
PermitRootLogin no
StrictModes yes
PubkeyAuthentication yes
PasswordAuthentication yes
PermitEmptyPasswords no
ChallengeResponseAuthentication no
UsePAM yes
X11Forwarding no
PrintMotd yes
ClientAliveInterval 300
ClientAliveCountMax 2
AllowUsers ${SSH_USER}
Banner /etc/ssh/banner
EOF

    # MOTD Banner
    cat > /etc/ssh/banner <<EOF

╔══════════════════════════════════════════════════════╗
║          SIAKAD - WEB KEMAHASISWAAN SERVER           ║
║          Akses tidak sah DILARANG KERAS!             ║
║          Seluruh aktivitas dicatat & dimonitor.      ║
╚══════════════════════════════════════════════════════╝

EOF

    # Buat direktori .ssh untuk user
    SSH_USER_HOME=$(eval echo "~$SSH_USER")
    mkdir -p "${SSH_USER_HOME}/.ssh"
    chmod 700 "${SSH_USER_HOME}/.ssh"
    touch "${SSH_USER_HOME}/.ssh/authorized_keys"
    chmod 600 "${SSH_USER_HOME}/.ssh/authorized_keys"
    chown -R "${SSH_USER}:${SSH_USER}" "${SSH_USER_HOME}/.ssh"

    # Restart SSH
    systemctl restart sshd
    systemctl enable sshd

    print_ok "SSH dikonfigurasi di port ${SSH_PORT}."
    print_ok "Login root via SSH dinonaktifkan."
    print_warn "Gunakan user '${SSH_USER}' untuk SSH: ssh ${SSH_USER}@${SERVER_IP} -p ${SSH_PORT}"
}

# ───────────────────────────────────────────────────────
# 3. SETUP FIREWALL (UFW)
# ───────────────────────────────────────────────────────
setup_firewall() {
    print_step "3/10 | SETUP FIREWALL (UFW)"

    ufw --force reset
    ufw default deny incoming
    ufw default allow outgoing
    ufw allow "$SSH_PORT/tcp" comment 'SSH Port'
    ufw allow 80/tcp comment 'HTTP Nginx'
    ufw allow 443/tcp comment 'HTTPS Nginx'
    ufw --force enable

    print_ok "Firewall UFW aktif."
    print_ok "Port yang dibuka: ${SSH_PORT} (SSH), 80 (HTTP), 443 (HTTPS)."

    # Konfigurasi Fail2Ban
    cat > /etc/fail2ban/jail.local <<EOF
[DEFAULT]
bantime  = 3600
findtime = 600
maxretry = 5

[sshd]
enabled  = true
port     = ${SSH_PORT}
logpath  = %(sshd_log)s
backend  = %(sshd_backend)s

[nginx-http-auth]
enabled = true

[nginx-limit-req]
enabled = true
port    = http,https
logpath = /var/log/nginx/error.log

[php-url-fopen]
enabled = true
port    = http,https
logpath = /var/log/nginx/access.log
EOF

    systemctl restart fail2ban
    systemctl enable fail2ban
    print_ok "Fail2Ban dikonfigurasi (brute force protection aktif)."
}

# ───────────────────────────────────────────────────────
# 4. INSTALL PHP 8.2 & EKSTENSI LARAVEL
# ───────────────────────────────────────────────────────
setup_php() {
    print_step "4/10 | INSTALL PHP 8.2 & EKSTENSI"

    # Tambah repository PHP
    add-apt-repository -y ppa:ondrej/php
    apt-get update -qq

    apt-get install -y -qq \
        php8.2 \
        php8.2-fpm \
        php8.2-mysql \
        php8.2-mbstring \
        php8.2-xml \
        php8.2-bcmath \
        php8.2-curl \
        php8.2-zip \
        php8.2-gd \
        php8.2-intl \
        php8.2-tokenizer \
        php8.2-fileinfo \
        php8.2-dom \
        php8.2-pdo \
        php8.2-opcache \
        php8.2-redis \
        php8.2-cli

    # Konfigurasi PHP untuk production
    PHP_INI="/etc/php/8.2/fpm/php.ini"
    sed -i "s/upload_max_filesize = .*/upload_max_filesize = 64M/" "$PHP_INI"
    sed -i "s/post_max_size = .*/post_max_size = 64M/" "$PHP_INI"
    sed -i "s/max_execution_time = .*/max_execution_time = 300/" "$PHP_INI"
    sed -i "s/memory_limit = .*/memory_limit = 512M/" "$PHP_INI"
    sed -i "s/;date.timezone.*/date.timezone = ${SERVER_TZ}/" "$PHP_INI"

    # Aktifkan OPcache
    cat > /etc/php/8.2/fpm/conf.d/10-opcache.ini <<EOF
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
EOF

    systemctl restart php8.2-fpm
    systemctl enable php8.2-fpm

    print_ok "PHP 8.2 + ekstensi Laravel berhasil diinstall."
    print_ok "OPcache diaktifkan untuk performa optimal."
}

# ───────────────────────────────────────────────────────
# 5. INSTALL COMPOSER
# ───────────────────────────────────────────────────────
setup_composer() {
    print_step "5/10 | INSTALL COMPOSER"

    if command -v composer &>/dev/null; then
        print_warn "Composer sudah terinstall, melakukan update..."
        composer self-update --quiet
    else
        EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
        php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
        ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

        if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
            rm composer-setup.php
            print_error "Checksum Composer tidak valid! Instalasi dibatalkan."
            exit 1
        fi

        php composer-setup.php --quiet --install-dir=/usr/local/bin --filename=composer
        rm composer-setup.php
    fi

    print_ok "Composer berhasil diinstall: $(composer --version --no-ansi)"
}

# ───────────────────────────────────────────────────────
# 6. INSTALL NODE.JS & NPM (untuk Vite Build)
# ───────────────────────────────────────────────────────
setup_nodejs() {
    print_step "6/10 | INSTALL NODE.JS & NPM"

    if ! command -v node &>/dev/null; then
        curl -fsSL https://deb.nodesource.com/setup_20.x | bash - > /dev/null 2>&1
        apt-get install -y -qq nodejs
    else
        print_warn "Node.js sudah terinstall: $(node -v)"
    fi

    print_ok "Node.js: $(node -v) | NPM: $(npm -v)"
}

# ───────────────────────────────────────────────────────
# 7. INSTALL & KONFIGURASI MYSQL
# ───────────────────────────────────────────────────────
setup_mysql() {
    print_step "7/10 | INSTALL & KONFIGURASI MYSQL"

    export DEBIAN_FRONTEND=noninteractive
    apt-get install -y -qq mysql-server

    # Start MySQL
    systemctl start mysql
    systemctl enable mysql

    # Secure MySQL & buat database
    mysql -u root <<MYSQL_EOF
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '${MYSQL_ROOT_PASS}';
DELETE FROM mysql.user WHERE User='';
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
DROP DATABASE IF EXISTS test;
DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';
CREATE DATABASE IF NOT EXISTS \`${DB_DATABASE}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USERNAME}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON \`${DB_DATABASE}\`.* TO '${DB_USERNAME}'@'localhost';
FLUSH PRIVILEGES;
MYSQL_EOF

    print_ok "MySQL dikonfigurasi."
    print_ok "Database '${DB_DATABASE}' dibuat."
    print_ok "User MySQL '${DB_USERNAME}' dibuat."

    # Konfigurasi MySQL untuk performa
    cat > /etc/mysql/conf.d/siakad-performance.cnf <<EOF
[mysqld]
innodb_buffer_pool_size = 256M
innodb_log_file_size    = 64M
innodb_flush_log_at_trx_commit = 2
query_cache_type        = 0
max_connections         = 150
character-set-server    = utf8mb4
collation-server        = utf8mb4_unicode_ci
slow_query_log          = 1
slow_query_log_file     = /var/log/mysql/slow.log
long_query_time         = 2
EOF

    systemctl restart mysql
    print_ok "MySQL performance tuning diterapkan."
}

# ───────────────────────────────────────────────────────
# 8. DEPLOY APLIKASI LARAVEL
# ───────────────────────────────────────────────────────
deploy_laravel() {
    print_step "8/10 | DEPLOY APLIKASI LARAVEL"

    # Buat direktori aplikasi
    mkdir -p "$APP_DIR"

    # Cek apakah sudah ada project di direktori script ini
    SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

    print_info "Menyalin file project ke ${APP_DIR}..."

    # Salin semua file project (kecuali .git dan node_modules)
    rsync -a --exclude='.git' --exclude='node_modules' --exclude='vendor' \
        "${SCRIPT_DIR}/" "${APP_DIR}/"

    cd "$APP_DIR"

    # Buat file .env untuk production
    cat > "${APP_DIR}/.env" <<EOF
APP_NAME="${APP_NAME}"
APP_ENV=${APP_ENV}
APP_KEY=
APP_DEBUG=false
APP_URL=http://${APP_DOMAIN}

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@${APP_DOMAIN}"
MAIL_FROM_NAME="\${APP_NAME}"
EOF

    # Install Composer dependencies
    print_info "Menginstall Composer dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction --quiet

    # Generate APP_KEY
    php artisan key:generate --force

    # Install NPM & Build Vite
    print_info "Menginstall NPM dependencies dan build assets..."
    npm ci --silent
    npm run build

    # Import Database
    if [[ "$IMPORT_SQL" =~ ^[Yy]$ ]]; then
        if [[ -f "${SCRIPT_DIR}/${SQL_FILE##*/}" ]] || [[ -f "$SQL_FILE" ]]; then
            SQL_PATH="${SCRIPT_DIR}/${SQL_FILE##*/}"
            [[ ! -f "$SQL_PATH" ]] && SQL_PATH="$SQL_FILE"

            print_info "Mengimport database dari ${SQL_PATH}..."
            mysql -u root -p"${MYSQL_ROOT_PASS}" "${DB_DATABASE}" < "$SQL_PATH"
            print_ok "Database berhasil diimport."
        else
            print_warn "File SQL tidak ditemukan: ${SQL_FILE}"
            print_info "Menjalankan migrations Laravel sebagai alternatif..."
            php artisan migrate --force
            php artisan db:seed --force 2>/dev/null || true
        fi
    else
        print_info "Menjalankan migrations Laravel..."
        php artisan migrate --force
    fi

    # Jalankan artisan commands untuk production
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan storage:link 2>/dev/null || true

    # Set permission yang benar
    chown -R www-data:www-data "$APP_DIR"
    find "$APP_DIR" -type f -exec chmod 644 {} \;
    find "$APP_DIR" -type d -exec chmod 755 {} \;
    chmod -R 775 "${APP_DIR}/storage"
    chmod -R 775 "${APP_DIR}/bootstrap/cache"

    print_ok "Aplikasi Laravel berhasil di-deploy ke ${APP_DIR}."
}

# ───────────────────────────────────────────────────────
# 9. INSTALL & KONFIGURASI NGINX
# ───────────────────────────────────────────────────────
setup_nginx() {
    print_step "9/10 | INSTALL & KONFIGURASI NGINX"

    apt-get install -y -qq nginx

    # Hapus konfigurasi default
    rm -f /etc/nginx/sites-enabled/default

    # Buat konfigurasi Virtual Host untuk SIAKAD
    cat > "/etc/nginx/sites-available/siakad" <<EOF
# =====================================================
# NGINX Config - SIAKAD Web Kemahasiswaan
# =====================================================
server {
    listen 80;
    listen [::]:80;

    server_name ${APP_DOMAIN} ${SERVER_IP};
    root ${APP_DIR}/public;
    index index.php index.html;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Permissions-Policy "camera=(), microphone=(), geolocation=()" always;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types
        text/plain text/css text/xml text/javascript
        application/json application/javascript application/xml+rss
        application/atom+xml image/svg+xml;

    # Batas ukuran upload
    client_max_body_size 64M;

    # Logging
    access_log /var/log/nginx/siakad-access.log;
    error_log  /var/log/nginx/siakad-error.log warn;

    # Laravel App
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        fastcgi_pass   unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include        fastcgi_params;
        fastcgi_read_timeout 300;
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;
    }

    # Cache Static Assets
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot|webp)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Block akses ke file sensitif
    location ~ /\.(env|git|htaccess) {
        deny all;
        return 404;
    }

    location ~ /(vendor|node_modules|storage/app) {
        deny all;
        return 404;
    }

    # Rate Limiting untuk Login
    location = /login {
        limit_req zone=login burst=10 nodelay;
        try_files \$uri \$uri/ /index.php?\$query_string;
    }
}
EOF

    # Tambahkan rate limiting ke nginx.conf
    cat > /etc/nginx/conf.d/rate-limiting.conf <<EOF
limit_req_zone \$binary_remote_addr zone=login:10m rate=5r/m;
EOF

    # Aktifkan site
    ln -sf /etc/nginx/sites-available/siakad /etc/nginx/sites-enabled/siakad

    # Test konfigurasi
    nginx -t

    systemctl restart nginx
    systemctl enable nginx

    print_ok "Nginx dikonfigurasi untuk SIAKAD."
    print_ok "Rate limiting aktif untuk endpoint /login."
    print_ok "Security headers diterapkan."
}

# ───────────────────────────────────────────────────────
# 10. SETUP LARAVEL QUEUE WORKER (Systemd Service)
# ───────────────────────────────────────────────────────
setup_queue_worker() {
    print_step "10/10 | SETUP LARAVEL QUEUE WORKER"

    cat > /etc/systemd/system/siakad-queue.service <<EOF
[Unit]
Description=SIAKAD Laravel Queue Worker
After=network.target mysql.service

[Service]
User=www-data
Group=www-data
WorkingDirectory=${APP_DIR}
ExecStart=/usr/bin/php ${APP_DIR}/artisan queue:work --sleep=3 --tries=3 --timeout=90 --max-jobs=500 --max-time=3600
Restart=on-failure
RestartSec=5s
KillMode=mixed
TimeoutStopSec=30
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
EOF

    # Setup Scheduler via Cron (Laravel Scheduler)
    (crontab -l 2>/dev/null; echo "* * * * * www-data cd ${APP_DIR} && php artisan schedule:run >> /dev/null 2>&1") | crontab -

    systemctl daemon-reload
    systemctl start siakad-queue
    systemctl enable siakad-queue

    print_ok "Laravel Queue Worker berjalan sebagai systemd service."
    print_ok "Laravel Scheduler dikonfigurasi via cron."
}

# ───────────────────────────────────────────────────────
# SETUP LOG ROTATION
# ───────────────────────────────────────────────────────
setup_logrotate() {
    cat > /etc/logrotate.d/siakad <<EOF
${APP_DIR}/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0664 www-data www-data
    sharedscripts
    postrotate
        php ${APP_DIR}/artisan cache:clear > /dev/null 2>&1
    endscript
}

/var/log/nginx/siakad-*.log {
    daily
    missingok
    rotate 30
    compress
    notifempty
    create 0640 www-data adm
    sharedscripts
    postrotate
        nginx -s reopen > /dev/null 2>&1
    endscript
}
EOF

    print_ok "Log rotation dikonfigurasi (retain 14 hari)."
}

# ───────────────────────────────────────────────────────
# RINGKASAN AKHIR
# ───────────────────────────────────────────────────────
print_summary() {
    echo ""
    echo -e "${GREEN}${BOLD}"
    echo "  ╔══════════════════════════════════════════════════════════════╗"
    echo "  ║              ✔  SETUP SELESAI DENGAN SUKSES!                ║"
    echo "  ╚══════════════════════════════════════════════════════════════╝"
    echo -e "${RESET}"
    separator
    echo -e "${BOLD}  🌐 AKSES APLIKASI:${RESET}"
    echo -e "     Web   : ${CYAN}http://${SERVER_IP}${RESET}"
    [[ "$APP_DOMAIN" != "$SERVER_IP" ]] && \
        echo -e "     Domain : ${CYAN}http://${APP_DOMAIN}${RESET}"
    echo ""
    echo -e "${BOLD}  🔐 AKSES SSH:${RESET}"
    echo -e "     ${CYAN}ssh ${SSH_USER}@${SERVER_IP} -p ${SSH_PORT}${RESET}"
    echo ""
    echo -e "${BOLD}  📊 AKUN DEFAULT SIAKAD:${RESET}"
    echo -e "     Admin    : ${CYAN}admin@siakad.com${RESET}  / password: ${CYAN}password${RESET}"
    echo -e "     Dosen    : ${CYAN}budi@siakad.com${RESET}   / password: ${CYAN}password${RESET}"
    echo -e "     Mahasiswa: ${CYAN}adi@siakad.com${RESET}    / password: ${CYAN}password${RESET}"
    echo ""
    echo -e "${BOLD}  📁 PATH SERVER:${RESET}"
    echo -e "     Aplikasi  : ${CYAN}${APP_DIR}${RESET}"
    echo -e "     Nginx Log : ${CYAN}/var/log/nginx/siakad-access.log${RESET}"
    echo -e "     App Log   : ${CYAN}${APP_DIR}/storage/logs/laravel.log${RESET}"
    echo ""
    echo -e "${BOLD}  ⚙  SERVICES AKTIF:${RESET}"
    echo -e "     ${GREEN}✔${RESET} nginx          $(systemctl is-active nginx)"
    echo -e "     ${GREEN}✔${RESET} mysql          $(systemctl is-active mysql)"
    echo -e "     ${GREEN}✔${RESET} php8.2-fpm     $(systemctl is-active php8.2-fpm)"
    echo -e "     ${GREEN}✔${RESET} siakad-queue   $(systemctl is-active siakad-queue)"
    echo -e "     ${GREEN}✔${RESET} sshd           $(systemctl is-active sshd)"
    echo -e "     ${GREEN}✔${RESET} ufw            $(systemctl is-active ufw)"
    echo -e "     ${GREEN}✔${RESET} fail2ban       $(systemctl is-active fail2ban)"
    separator
    echo -e "${YELLOW}  ⚠  PENTING: Segera ganti password default SIAKAD setelah login pertama!${RESET}"
    echo -e "${YELLOW}  ⚠  Pastikan VirtualBox menggunakan mode jaringan 'Bridged Adapter'${RESET}"
    echo -e "${YELLOW}     agar dapat diakses dari perangkat lain di local network.${RESET}"
    separator
    echo ""
}

# ───────────────────────────────────────────────────────
# MAIN - EKSEKUSI UTAMA
# ───────────────────────────────────────────────────────
main() {
    print_banner
    check_root
    detect_server_info
    collect_user_input

    setup_system
    setup_ssh
    setup_firewall
    setup_php
    setup_composer
    setup_nodejs
    setup_mysql
    deploy_laravel
    setup_nginx
    setup_queue_worker
    setup_logrotate

    print_summary
}

# Jalankan
main "$@"
