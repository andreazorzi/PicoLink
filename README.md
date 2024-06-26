<p align="center">
	<img src="https://github.com/andreazorzi/PicoLink/blob/development/public/images/logo.png?raw=true" alt="PicoLink" width="400"/>
</p>
<br>
PicoLink is not the regular url shortner. It provide super power to your links.

## ⚡️ Super Powers
- Custom short link code, even with emojis: https://yoursite.com/🚀
- Customized tags and advanced search to find your shorts faster 🔎
- Quick sharing and qrcode creation 💻
- Complete reports with visits by day, devices, referrers and countries 📊
- Simple API for createating new shorts 🤖
- Multilingual short link with automatic redirect based on client browser language 💥

## 🛠️ How to install
### 🐳 Docker File
```yml
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
            ADMIN_PASSWORD: '_password_'
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


### 🎁 Additional .env configurations
```yml
# API Token
API_TOKEN: _api_token_

# Authentik
AUTHENTIK_BASE_URL: "https://auth.host.com"
AUTHENTIK_CLIENT_ID: ""
AUTHENTIK_CLIENT_SECRET: ""
AUTHENTIK_REDIRECT_URI: "/auth/authentik/callback"
AUTHENTIK_SLUG: "picolink"
```

### 🤖 API Endpoint
```json
// Create multiple shorts
// PUT /api/short/create
// -H Authorization: Bearer _api_token_

// Data
{
    "shorts": [
        {
            "code": "short1",
            "description": "link to website",
            "url": "https://website.com/default-redirect-url",
            "languages": [
                {
                    "language": "it",
                    "url": "https://website.com/italian-redirect-url"
                },
                {
                    "language": "de",
                    "url": "https://website.com/german-redirect-url"
                },
                ...
            ],
            "tags": ["Tag1", "Tag2", "Tag3"]
        },
        ...
    ]
}

// JSON Response
{
    "status": "success", // or danger
    "message": "Short links created successfully." // or the errors
}
```