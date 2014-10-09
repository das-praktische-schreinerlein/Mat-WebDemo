MAT-WebDemo - Das Framework für die Seite mit dem "Stein im Schuh"
=====================

# Desc
"MATWeb" is the php/js/css-webapp-framework for my webproject "Michas-Ausflugstipps.de".
It's from 2005 and so no more "state of the art" ;-). But it's my playground and has many features like AppCaching, geo-support, ui-support, automatic-tagclouds-from-db... 
Take a loook at http://www.michas-ausflugstipps.de The whooole path SQL till Frontend-UI is made by this framework.

But as I said: the technologie is "1980" and so is he aim to migrate the functions to a symfony2-based web. But it's huge work by less time...

More information can you find at http://www.michas-ausflugstipps.de/portal-infos.html

# TODO for me
- [ ] update the documentation on http://www.michas-ausflugstipps.de/portal-infos.html
- [ ] udate this documentation
- [ ] add the demo-pages to http://www.michas-ausflugstipps.de/matweb_demo
- [ ] migration to Symfony2 :-)
- [ ] use and optimize it :-)

# History and milestones
- 2013
   - prepared the framework-demo for going public
- 2005-2013
   - added many features like GeoMaps, Profiles, 
- 2005
   - initial version and use at www.michas-ausfligstipps.de

# Requires
- to use
   - php
   - mysql
   - webserver (apache)

# Install
```
#########################
#
# Install-MatWeb-Demo
#
#########################  
    
#########################
#### Linux/Unix-Standard-Server
#########################

# Entpacken
cd /srv/www/vhosts/ 
unzip matweb_demo-current.zip 

# Datenbank konfigurieren
cd /srv/www/vhosts/matweb_demo/install
mysql -u root -p

create database mat_demodb CHARACTER SET latin1;
GRANT ALL PRIVILEGES ON mat_demodb.* TO mat_portaluser@localhost IDENTIFIED BY 'FIXME_change_on_install';
flush privileges;
exit;

# Tabellen importieren
mysql -u mat_portaluser -p mat_demodb

source import_RATES.sql;
source import_IMAGE.sql;
exit;

# Apache konfigurieren
cd /srv/www/vhosts/matweb_demo/install
cp inc_matweb_demo.conf /etc/apache2/conf.d/

#########################
#### Windows
#########################

# Entpacken
cd $CHANGE_TO_YOUR_WEBBASE$
unzip matweb_demo-current.zip 

# datenbabnk konfigurieren
cd $CHANGE_TO_YOUR_WEBBASE$\matweb_demo\install\
mysql -u root -p

create database mat_demodb CHARACTER SET latin1;
GRANT ALL PRIVILEGES ON mat_demodb.* TO mat_portaluser@localhost IDENTIFIED BY 'FIXME_change_on_install';
flush privileges;
exit;

# Tabellen importieren
mysql -u mat_portaluser -p mat_demodb
source import_RATES.sql;
source import_IMAGE.sql;
exit;

# Apache konfigurieren
# folgende Zeilen in Apache-Config-Verzeichnis apache/conf/inc_matweb_demo.conf
alias /matweb_demo/ "$CHANGE_TO_YOUR_WEBBASE$/matweb_demo/webapp/"
alias /mobile/matweb_demo/ "$CHANGE_TO_YOUR_WEBBASE$/matweb_demo/webapp/"
alias /smartphone/matweb_demo/ "$CHANGE_TO_YOUR_WEBBASE$/matweb_demo/webapp/"
<Directory "$CHANGE_TO_YOUR_WEBBASE$/matweb_demo/webapp">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Order allow,deny
    Allow from all
    
    php_admin_flag engine on
    php_admin_flag safe_mode on
    php_admin_value open_basedir "$CHANGE_TO_YOUR_WEBBASE$/matweb_demo/webapp/:/tmp"
</Directory>
```

# License
```
/**
 * @author Michael Schreiner <ich@michas-ausflugstipps.de>
 * @category fraemwork
 * @copyright Copyright (c) 2005-2014, Michael Schreiner
 * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
```
