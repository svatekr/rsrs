RS::RS
======
[![Downloads this Month](https://img.shields.io/packagist/dm/rsrs/rsrs.svg)](https://packagist.org/packages/rsrs/rsrs)
[![Latest Stable Version](https://poser.pugx.org/rsrs/rsrs/v/stable)](https://github.com/svatekr/rsrs/releases)

Kostra redakčního systému postavená nad Nette Frameworkem


Instalace
------------
Nejlepší je použít Composer. pokud ho nemáš, [pořiď si ho](https://doc.nette.org/composer). Instalaci sandboxu spustíš příkazem:

		composer create-project rsrs/rsrs path/to/install
		cd path/to/install

Složky `temp/` a `log/` potřebují mít povolený zápis.

Vytvoř databázi a naimportujte do ní soubor redakcni-system.sql. V souboru www/config/config.local.neon změň výchozí údaje pro připojení k databázi.


Web Server
----------------
Pokud neprovozuješ nějaký server jako třeba Apache, je nejjednodušší spustit vlastní server příkazem:

		php -S localhost:8000

Po zadání adresy `http://localhost:8000` do prohlížeče pak uvidíš úvodní stranu.


Založení Administrátora
-----------------------
Spusť příkazový řádek a zadej:

        cd redakcni-system\bin
        php create-user.php root password admin

Argument root a password si můžeš změnit jak chceš. Jde o uživatelské jméno a heslo. Argument admin je role uživatele a tu bys v tomto případě měnit neměl. Mrkni do databázové tabulky users a měl bys vidět jeden záznam.

Administrace je na adrese `http://localhost:8000/admin`
