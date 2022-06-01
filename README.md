## Installation

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

## Bash alias

```
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```

## Sail Symlinks for Dev:

```
sail root-shell
php artisan storage:link
```

Using `sail artisan storage:link` doesn't work
