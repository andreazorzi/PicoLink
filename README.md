<div style="text-align: center">
	<img src="https://raw.githubusercontent.com/andreazorzi/PicoLink/development/public/images/logo.png?token=GHSAT0AAAAAACOQRLWVBGNLVTBNBDQUSSFSZTZQNJQ" alt="PicoLink" style="max-width: 500px;"/>
</div>
<br>
PicoLink is not the regular url shortner. It provide super power to your links.

## Super Powers
- Custom short link code, even with emojis: https://yoursite.com/🚀
- Customized tags and advanced search to find your shorts faster 🔎
- Quick sharing and qrcode creation 💻
- Complete reports with visits by day, devices, referrers and countries 📊
- Multilingual short link with automatic redirect based on client browser language ✨

## How to install
### Docker File
```
services:
    server:
        container_name: "picolink-server"
        image: ghcr.io/andreazorzi/picolink:latest
        restart: unless-stopped
        ports:
            - '80:80'
        environment:
            APP_ENV: 'production'
            APP_DEBUG: 'false'
            APP_URL: 'https://yourdomain.com'
            DB_DATABASE: 'laravel'
            DB_USERNAME: 'picolink'
            DB_PASSWORD: '_db_password_'
            
            ADMIN_USERNAME: 'admin'
            ADMIN_PASSWORD: 'password'

            # API_TOKEN: 'v5R9KhRexSQYKcBmeoUbBrxtXR'
        networks:
            - sail
        depends_on:
            - mysql
    mysql:
        container_name: "picolink-db"
        image: 'mysql/mysql-server:8.0'
        restart: unless-stopped
        environment:
            MYSQL_ROOT_PASSWORD: '_root_password_'
            MYSQL_ROOT_HOST: '%'
            MYSQL_DATABASE: 'laravel'
            MYSQL_USER: 'picolink'
            MYSQL_PASSWORD: '_db_password_'
        volumes:
            - 'pathtodocker/picolink/mysql:/var/lib/mysql'
        networks:
            - sail
networks:
    sail:
        driver: bridge
```