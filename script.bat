
docker compose run --rm php php bin/console doctrine:schema:validate

echo "Tests en cours..."

docker compose run --rm php php bin/console ajouter-donnees

docker compose run --rm php php bin/phpunit

echo "Tests terminés"
