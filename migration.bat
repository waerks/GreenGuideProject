@REM Supprimer les versions antérieures de migration pour éviter les erreurs
@REM Marquer toutes les migrations comme "migrées" dans la DB
symfony console doctrine:migrations:version --delete --all --no-interaction
@REM Supprimer les anciens fichiers de migration
echo yes | del migrations\*.php
@REM Supprime l'ancienne BD
symfony console doctrine:database:drop --force --no-interaction
@REM Crée une nouvelle BD
symfony console doctrine:database:create --no-interaction
@REM Génère de nouvelles migrations
symfony console make:migration --no-interaction
@REM Applique les migrations
symfony console doctrine:migrations:migrate --no-interaction
@REM Supprime et recrée les données dans la DB
symfony console doctrine:fixtures:load --no-interaction