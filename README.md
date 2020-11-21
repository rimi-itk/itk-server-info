# ITK server info - Hub

```sh
git clone --branch=hub https://github.com/rimi-itk/itk-server-info
```

```sh
composer install --no-dev --classmap-authoritative
bin/console doctrine:migrations:migrate --no-interaction
```

## Development

```sh
docker-compose up -d
symfony composer install
symfony console doctrine:migrations:migrate --no-interaction
symfony local:server:start
```

## Coding standards

```sh
composer coding-standards-check
composer coding-standards-apply
```
