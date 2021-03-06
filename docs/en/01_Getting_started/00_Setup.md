# Setup

1. First, make sure that needed tools are available on the system

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


2. Get the repository

    ```bash
    # example with git
    git clone https://github.com/Viva-Resurs/ReturButikenOnline.git
    cd ReturButikenOnline
    ```


3. Install dependencies

    ```bash
    # install PHP-packages
    composer install

    # install npm-packages
    npm install
    ```

## Advanced / Troubleshooting

When running composer install, the following important commands will be runned:
```bash
# Create a database file
touch database/database.sqlite

# Setup enviroment variables and generate application key for Laravel
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
```

When running npm install, the following commands will be runned to setup GUI:
```bash
# Create folders for js & css files
npm run create-folders

# Copy needed fonts (icons)
npm run copy-assets

# Copy vendor css (semantic-ui)
npm run copy-css

# Build js & css to create GUI
npm run build

# Build local documentation
npm run docs
```
