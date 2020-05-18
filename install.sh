#!/usr/bin/env bash

banner() {
    cat <<"NS"

  _   _            _   _      _____ _              _____ ___  
 | \ | |          | | | |    / ____| |            / ____|__ \ 
 |  \| | ___  _ __| |_| |__ | (___ | |_ __ _ _ __| |       ) |
 | . ` |/ _ \| '__| __| '_ \ \___ \| __/ _` | '__| |      / / 
 | |\  | (_) | |  | |_| | | |____) | || (_| | |  | |____ / /_ 
 |_| \_|\___/|_|   \__|_| |_|_____/ \__\__,_|_|   \_____|____|

NS
}

isRoot() {
    if [ "$EUID" -ne 0 ]; then
        echo -e "\n[!] Please run the script with root privileges.\n"
        exit
    fi
}

checkInternet() {
    wget -q --spider http://google.com
    if [ $? -ne 0 ]; then
        echo -e "\n[!] Please check your internet connection.\n"
        exit
    fi
}

checkOS() {
    . /etc/os-release -r
    if [ "$NAME" == "Ubuntu" ]; then
        OS="ubuntu"
    elif [ "$NAME" == "Kali GNU/Linux" ]; then
        OS="kali/parrot"
    elif [ "$NAME" == "Parrot GNU/Linux" ]; then
        OS="kali/parrot"
    else
        echo -e "\n[!] Your OS is detected as: $NAME\n"
        echo "      > Which is not supported for this installation script."
        echo -e "      > Please refer to Wiki Page for proper installation on your system.\n"
        exit
    fi
}

installPackages() {
    echo -e "\n[*] Starting to install required packages.\n"
    sleep 3
    DEBIAN_FRONTEND=noninteractive apt install apache2 php libapache2-mod-php php-mysql mysql-server -y
}

configure() {
    clear
    banner
    echo -e "\n[✓] Packages are installed.\n"
    sleep 1
    echo -e "[*] Please insert configuration data:\n"
    TTY=$(/usr/bin/tty)
    read -p "       Database Name       : " NSDBNAME < $TTY
    read -p "       Mysql Root Password : " NSDBPASS < $TTY
    read -p "       Web Panel Username  : " NSPANELUSER < $TTY
    read -p "       Web Panel Password  : " NSPANELPASS < $TTY
    echo -e "\n[*] Configuration is ongoing, please wait.\n"
    sed -i '5 s/ = ".*/ = "'"root"'";/' conn.php
    sed -i '6 s/ = ".*/ = "'"$NSDBPASS"'";/' conn.php
    sed -i '7 s/ = ".*/ = "'"$NSDBNAME"'";/' conn.php
    systemctl start mysql
    if [ "$OS" == "ubuntu" ]; then
        mysql <<QUERY
        ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '${NSDBPASS}';
QUERY
    elif [ "$OS" == "kali/parrot" ]; then
        mysql <<QUERY
        ALTER USER 'root'@'localhost' IDENTIFIED BY '${NSDBPASS}';
QUERY
    fi
    mysql -u root -p${NSDBPASS} -h localhost > /dev/null 2>&1 <<QUERY
    CREATE DATABASE $NSDBNAME;
    USE $NSDBNAME;
    source northstar.sql
    INSERT INTO users(username,password) values('$NSPANELUSER', MD5('NorthyBoi${NSPANELPASS}NorthyBoi'));
QUERY
    rm -rf /var/www/html/*
    cp -r * /var/www/html/
    chown -R www-data:www-data /var/www/html/
    echo -e "\n[mysqld]\nbind-address = 127.0.0.1\nskip-networking" >>/etc/mysql/my.cnf
    sed -i 's/Options Indexes FollowSymLinks/Options -Indexes/' /etc/apache2/apache2.conf
    systemctl restart mysql
    systemctl restart apache2
    systemctl enable mysql > /dev/null 2>&1
    systemctl enable apache2 > /dev/null 2>&1
    clear
    banner
    echo -e "\n[✓] Installation completed.\n"
    sleep 1
    echo -e "[*] You can login and start using your panel at: 127.0.0.1/getin.php\n"
    sleep 1
    echo -e "              ☆ Let the NorthStar lighten your way ☆               \n"
}

clear
banner
isRoot
checkOS
checkInternet
installPackages
configure
