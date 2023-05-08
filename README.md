# Rest API

### Developer installation

run docker:
```
 docker compose up
```

*for osx m1 processor, there is prepared file docker-compose.override.yml_osx with custom configuration, copy wile without _osx*

Connect to php terminal via docker exec
```
 docker compose exec php sh
```

run this inside php container
```
composer install
php bin/console lexik:jwt:generate-keypair
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
```

This will install all composer dependencies.
Generate secure keypair (pem and public key) for jwt authentication.
Migrates database to last version.
And inserts test data.

### Admin user
There is prepared admin user with credentials:
```
username: admin 
password: admin
```

### User accounts
```
username: user-<1,100>
password: user
```

### Postman collection
Postman collection is prepared at .data/REST_API.postman_collection.json

### Ready up
1. In default settings, app is running on 127.0.0.1:80 
2. Call /api/login_check route to obtain a token.
3. Use given token to authenticate. (Header Token: <token>)
4. Api documentation is located at http://localhost/api/doc
