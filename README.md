INSTALL


```
git clone 

```

install Laradock
[do instructions on Laradock] (https://laradock.io/)
```
git clone https://github.com/Laradock/laradock.git && cd laradock
```

run docker containers
```
docker-compose up -d nginx mysql workspace
```

run cli workspace 
```
docker-compose exec workspace bash
```
Add data to database
```
php artisan migrate --seed
```
Generate Api documentation
```
php artisan l5-swagger:generate 
```

[go to API Documentation] http://localhost/api/documentation
