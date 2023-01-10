# kvalifikacijas_darbs_uni
Kvalifikācijas darba programmatūras kods universitātei.
Kvalifikācijas darba laikā izstrādāta sistēma auto stāvvietu rezervāciju izveidei. Sistēma pašlaik ir konfigurēta, lai to varētu palaist, izmantojot Docker. Zemāk atrodama instrukcija kā ielādēt šo sistēmu caur docker.

Required:

Docker installed
To use this, save the repository on your computer. Then, open powershell and cd to your directory, where you just saved this repo, then run docker-compose up, to create docker containers for this project.

Install/prepare docker: run docker-compose up (--build)

then, once the docker containers have been created, run

docker exec --workdir /Mapon-prakse-backend-projekts php_container_name composer install

to install dependencies from composer. Now, if everything has been installed correctly and no errors appear, can move on to the next part. Database preparation:

Open the php container command line, either through docker desktop or through powershell
Once php CLI is opened,cd to project directory(which should be /Mapon-prakse-backend-projekts)
If phinx.php file isn't in the project, initiate phinx by running "vendor/bin/phinx init"
run "vendor/bin/phinx migrate"
then run "vendor/bin/phinx seed:run"
Possible issues:

Migration/Seeding doesn't work
Migration/Seeding seems to work fine, but doesn't actually create table or insert data in the table
Possible fixes:

If the migration doesn't properly create table, truncate the phinxlog table in the database server, to clear the migration from logs, allowing it to be ran again.
Use application/launch it on a server/docker hub
