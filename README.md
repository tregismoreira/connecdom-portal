# Install project with docker
First of all, you need Docker installed in your machine. If you dont, please follow steps here https://www.docker.com/get-started

### Fisrt time
* open terminal and go to the project folder
* type `docker-compose up`
* If everything goes well, you should see
    ````
    database_1  | 2020-06-11T20:44:32.584691Z 0 [Note] Event Scheduler: Loaded 0 events
    database_1  | 2020-06-11T20:44:32.585126Z 0 [Note] mysqld: ready for connections.
    database_1  | Version: '5.7.29'  socket: '/var/run/mysqld/mysqld.sock'  port: 3306  MySQL Community Server (GPL)

* Keep current terminal session opened and open another one
* type `docker exec -it connecdom_app_1 bash`
* inside container session, type the following commands:
* * `composer -n install --prefer-dist`
* * `mkdir sites/default/files`
* * `chmod -R 0777 sites/default/files`

After that, copy file `web/sites/default/docker.settings.php` and rename to `web/sites/default/settings.php`

Now we have to import database dump. To import, please read bellow __Import Database topic__

Now, open browser and type url http://localhost:8080/web/user/login. The login is `admin` and password is `teste123`

When you finish to work, go to session where you've typed `docker-compose up` and press CMD+C (OSX) / CTRL+C (Linux/windows) and type `docker-compose down`

### Everyday
* open terminal and go to the project folder
* type `docker-compose up`

Now, open browser and type url http://localhost:8080/web/user/login. The login is `admin` and password is `teste123`

When you finish to work, go to session where you've typed `docker-compose up` and press CMD+C (OSX) / CTRL+C (Linux/windows) and type `docker-compose down`

### Import Database
When you download a Mysql database dump and need to import database locally, follow the steps:
* open terminal and go to the project folder
* type `docker-compose up`
* open a new terminal sessio
* go to folder where you have downloaded database dump
* copy database dump to inside mysql docker container typing command `docker cp DATABASE-NAME.sql connecdom_database_1:/tmp`
* after that, type `docker exec -it connecdom_database_1 bash`
* inside mysql container, type command `mysql -u drupal -p drupal < /tmp/DATABASE-NAME.sql`. This command will prompt mysql password. The password is `password`
* when database has imported, type `exit`. Now, type `docker exec -it connecdom_app_1 bash`.
* inside container session, go to folder `/var/www/html/web`
* type `drush user:password admin teste123`
