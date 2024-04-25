# Hackathon

## Pour le back
cd back

### installer les dependances (si ce n'est pas deja fait)
composer install

### Faire la bdd
php bin/console d:d:c
php bin/console d:s:u --force

### lancer la commande de fixture
php bin/console doctrine:fixtures:load --no-interaction

### lancer le serveur
symfony server:start
