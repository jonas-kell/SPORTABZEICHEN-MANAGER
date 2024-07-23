# SPORTABZEICHEN-MANAGER

## What is this software?

A home-grown solution that was initially developed to improve the management of `Sportabzeichen` examinations in the scope of the Bavarian procedures.
The program was used internally for several years to manage the high volume of examinations in our holyday-camp with very little overhead.

The software is designed to be safe for public hosting, however there is no guarantee that security updates will be provided, nor is it audited.
Guaranteeing the security of the personal data stored when using this tool is a job that must be fulfilled by the person that chooses to install this.
The usage is not endorsed by the official [organizers](https://deutsches-sportabzeichen.de/) of the `Sportabzeichen`, nor by the Bavarian part of the regulating body [BLSV](https://www.blsv.de/).
Providing public-facing access to this, may violate the Personal-Data-Protection regulations that one as a `Sportabzeichen-Prüfer` (licensed evaluator for the Sportabzeichen-Program) is required to uphold.
Handling access-credentials to this software without care definitely violates these regulations, so be responsible and only carry on using this with care and expert knowledge.
Also the Personal-Data-Protection regulations require the regular deleting of participant data.
Such cleanup is **NOT** done by the program, nor does it provide a process designed to do so. This needs to be done externally (e.g. clearing the database).

By now, this software is merely legacy to provide handy print-scripts and an overview on how this has been done in our organization.
The DOSB recommends the usage of the new software [Sportabzeichen-Digital](https://sportabzeichen-digital.de), while for specific parts of Germany, like Bavaria, the software [SPAZ](https://spaz.verein360.de/) is recommended.
Because there are solutions where the developers are payed, the developer of this software does no longer consider doing updates to it and also heavily discourages the use of this tool, because it WILL get outdated in terms of features, data and security.
(Even though the software is waaay easier to use, has more convenience features and is pretty and way more efficient than the new solutions ;-P ).

## Performance-Requirements Data Caveats

The examination criteria that are displayed are **NOT** updated unless the developer does so manually.
There are mistakes that are known when this software is used **from and including 2024 onwards**.
There is no updating of the data planned going forward, because by now there are tools designed by other parties that are supposed to do this job going forward.

## Installation + Usage + Development

### Laravel-Backend installation

This can be easily tested in dev-mode by employing [Laravel-Sail](https://laravel.com/docs/11.x/sail).

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

### Bash alias (Linux)

```
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```

### External Executables

The execution of the pdf-generation functionality requires the program [pdftk](https://www.pdflabs.com/tools/pdftk-the-pdf-toolkit/) in a specific version.

To ensure this is available, one must download the executable and other required .so files from [HERE](https://github.com/lob/lambda-pdftk-example/tree/master/bin).

Then the executable `pdftk` and the file `libgcj.so.10` must be placed in a folder, named `pdftk` and the folder must be placed in the `vendor` folder of the project.

If one intends to use other versions of the pdftk software, it is necessary to change the executable-path in `.env`.

### Start dev-server

```
sail up
```

Then, other default Laravel-steps may be required. Read the [Docs](https://laravel.com/docs/11.x/installation).

(Database migration, seeding, cache-clearing, symlinks, .env-configuration (copy `/.env.example` to `/.env` and `/frontend/.env.quasar.example` to `/frontend/.env.quasar` and fill in values), user creation)

To setup a user with login, it should be the best to use the [Artisan-Shell](https://laravel.com/docs/11.x/artisan) (e.g. Tinker).

For local installations, per default the database will have one user, if the database is created with:

```
sail artisan migrate:fresh --seed
```

Username/Email: `test@test`
Password: `asdfasdf`

### Sail Symlinks for Dev

Using `sail artisan storage:link` doesn't work. Instead run

```
sail root-shell
php artisan storage:link
```

#### Dev-Services

-   [http://localhost:8080/](http://localhost:8080/) Main Application (Direct Access to the Hot-Reloading Vite dev-server)
-   [http://localhost:8081/](http://localhost:8081/) phpMyAdmin Database interface
-   [http://localhost:8082/](http://localhost:8082/) MailHog email simulation webinterface
-   [http://localhost:80/](http://localhost:80/) Main Application Backend (not "working" as expected in dev mode, meaning you cannot use that page there even if it loos like the one under 8080)

## Deploy to a live-server

The deployment of this requires external webserver-knowledge.
It must be made sure that the webserver is properly hardened and access-controlled.

During the installation, commands are printed on the terminal that must be run locally, because the script cannot execute sail-commands from inside sail.

```
sail php vendor/bin/envoy run deploy
```

A very specific folder structure is required on the target server, that is NOT documented, to avoid hosting by inexperienced persons, that would generate a risk of incomplete/insecure access-control.

This software is NOT designed for end-user-usage, there are now better suited alternatives.

## License

The Program is provided under GPL 3, see [LICENSE](./LICENSE).

The developer does not hold any rights to the `Sportabzeichen`, `DOSB`, `BLSV` or `SPAZ` Brands, Branding, Logos or Data.

All information that links to these in any way, shape or form was available publicly at the time of development on the named brands websites.

For ease of use, a binary pdf-file of the `Gruppenprüfkarte` was downloaded from [The DOSB WEBSITE](https://deutsches-sportabzeichen.de/service/materialien) and modified heavily to be auto-fillable in this application.
The Developer holds no rights to any of the branding in `./resources/DSA_Gruppenpruefkarte_2021_beschreibbar.pdf`.
