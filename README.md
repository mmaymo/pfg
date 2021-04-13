

## Interactive tools for "Diseño y Administración de Sistemas Operativos" course

...
## Installation
A pre-requisite to install this tool is to have Docker installed on your machine.
Then just clone this repository
 Inside the root directory of the project copy the .env.example file to .env 
 ```
 cp .env.example .env
 ```
Change the APP_URL constant if needed and then build the image:
```
docker-compose build app
```
Start the docker environment in detached mode:
```
docker-compose up -d
```
From now on all commands will be run in the app service. So, to install dependencies through composer we must:
```
docker-compose exec app composer install
```
Create the app key too (you can check in the .env file the new key)
```
docker-compose exec app php artisan key:generate
```
And to create the databases with the initial seed:
```
docker-compose exec app php artisan migrate --seed
```
Now we need to fix permissions for the docker socket:
```
docker-compose exec  -u root app chown carmen /var/run/docker.sock
```
And make sure to have available the test image:
```
docker run dduportal/bats
```

The tool is now ready to visit in `http://192.168.1.88:8000/`

## Usage
### Admin Role
...

### Student Role
...

## License
...
