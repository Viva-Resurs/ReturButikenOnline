# Konfigurera

1. Först, se till att följande verktyg finns installerade på systemet

    - [x] [PHP](http://php.net/)
        - [x] PHP >= 5.6.4
        - [x] OpenSSL PHP Extension
        - [x] PDO PHP Extension
        - [x] Mbstring PHP Extension
        - [x] Tokenizer PHP Extension
        - [x] XML PHP Extension

    - [x] [Composer](https://getcomposer.org/download/)

    - [x] [NodeJS & npm](https://nodejs.org/en/download/)

    - [ ] [Git](https://git-scm.com/download/win)


2. Hämta projektet

    ```bash
    # exempel med git
    git clone https://github.com/Viva-Resurs/ReturButikenOnline.git
    cd ReturButikenOnline
    ```


3. Installera paket

    ```bash
    # installera PHP-paket
    composer install

    # instaleral npm-paket
    npm install
    ```

## Avancerat / Felsökning

När composer install körs så körs även följande kommandon:
```bash
# Skapa en databas fil
touch database/database.sqlite

# Ställ in miljö variabler och generera en applikationsnyckel för Laravel
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
```

När npm install körs så körs även följande kommandon för att bygga GUI:
```bash
# Skapa mappar för js & css filer
npm run create-folders

# Kopiera teckensnitt (ikoner)
npm run copy-assets

# Kopiera tredjeparts css (semantic-ui)
npm run copy-css

# Kompilera js & css för att skapa användargränssnittet
npm run build

# Skapa en lokal version av dokumentationen
npm run docs
```
