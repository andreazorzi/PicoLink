cp -R -u -p .env.example .env && composer update && ./vendor/bin/sail up -d && ./vendor/bin/sail npm install && php artisan key:generate && ./vendor/bin/sail npm run dev && echo "remember to run saildb"
