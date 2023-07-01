**Test task**

Contents:

* ***task files***
* ***docker files***
* ***how2 run***
* ***implementation details***

---

**Task files**

These files were placed in `code` folder

Files requested by spec.:

* index1.html
* index2.html
* banner.php

Extra file for default page:

* index.html

Extra file for table creation:

* docker/db/create.sql

Caution:
DB should be created already, `create.sql` checks if db exists, if not,
it will be created, + table exists, and if not, it will create table.
So `create.sql` is a prepared version of structure, which can be loaded
into DB from console with:
* `mysql -u username -p database_name < create.sql`

---

**Docker files**

These files/folders are in the root of the repo:
* `docker/db/data` folder, will be used as an extra storage for DB, created/used by docker's mysql container
* `docker/db/init` folder which will be linked to mysql container with DB table pre-build.
* `docker/nginx` folder with nginx customization
* `docker/php` folder with php mysql+PDO adding
* `docker-compose.yml` as-is<br>
  Network was specified only due to nicer compiled container names.
  Type `bridge` was selected specifically (so only specified ports can be shared outside).
  The only shared outside ports are:
  * 8080 - adminer (for faster DB changes checking)
  * 80 - used for main task

---

**How2 run**

Just run in console from the root folder of cloned repo:
`docker-compose up -d`

---

**Implementation details**

Code is written in 3 files only, as it was requested in original spec, with only 1 exception: index.html.
This extra file was created only for better UI/IX to deal better access to extra pages.
All files are placed in `code` folder.

Code in `php` was written in 1 file, which is unacceptable due to SOLID rules. but it goes according to spec.
PSR rules were used, php 7.0+ was used in syntax (even though docker contains 8.1 version).
In case of going according to SOLID rules, there should be separated classes to work with:
* DB quering
* Getting 3 values: ip + user-address + referer
* Validating 3 values: ip + user-agent + referer

Extra headers `X-Track-Status` with exceptions should be removed.
This code was left specifically, if something goes wrong (due to logs were not stored specifically via nginx/php containers).

Dockerizing was selected due to easy way to compile the whole task + run on
any environment, which has `docker-compose` installed.
