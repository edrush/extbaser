# Extbaser
Create a TYPO3 Extbase Extension from an existing database schema.

## Installation

Clone or install via composer:
```
composer require edrush/extbaser
```

## Usage
### 1. Export your database schema to a TYPO3 Extbase Extension
```
php bin/app.php extbaser:export dbname
```
(Connection parameters default to [Symfony best practices](http://symfony.com/doc/current/best_practices/configuration.html))

To configure your connection type:
```
php bin/app.php extbaser:export -u username -p password dbname
```


The generated extension folder contains the file **ExtensionBuilder.json**, which is the project file for the *TYPO3 Extension Builder* to scaffold your extension, see below.

### 2. Upload the extension to your TYPO3 installation
Move the generated folder to the 'typo3conf/ext' folder of your TYPO3 installation.

### 3. Install the new extension in your TYPO3 installation
* Log in to your TYPO3 Backend
* Open module *Extension Builder* (if there's no such module: install it in the Extension Manager)
* Click *Load* and select your new extension key
* Adapt the extension configuration to your needs
* Click *Save*
* Activate your extension in the Extension Manager

## Roundtrip
Extbaser also supports roundtriping of TYPO3 Extbase Extensions, which means that if you update the extension with Extbaser all **Extension properties** set in the Extension Builder will remain:
* Copy the extension to a location where Extbaser can access it
* Retype the export command you used before  and add the *-r* option: `php bin/app.php extbaser:export dbname target_extension_key -r --path=...` (the path variable is the parent folder of your extension folder, set it if the extension is not in your current directory)

## Help
```
php bin/app.php extbaser:export --help

Usage:
  extbaser:export [options] [--] <dbname>

Arguments:
  dbname                               The database you want to export

Options:
      --extension-key[=EXTENSION-KEY]  The target TYPO3 Extension key
      --path[=PATH]                    The path to export the extension to [default: "."]
  -u, --user[=USER]                    The database user [default: "root"]
  -p, --password[=PASSWORD]            The database password
      --host[=HOST]                    The database host [default: "127.0.0.1"]
      --driver[=DRIVER]                The database driver [default: "pdo_mysql"]
      --port[=PORT]                    The database port
      --filter=FILTER                  A string pattern used to match entities that should be mapped (multiple values allowed)
  -f, --force                          Override existing extension
  -r, --round-trip                     Roundtrip existing extension
  -h, --help                           Display this help message
  -q, --quiet                          Do not output any message
  -V, --version                        Display this application version
      --ansi                           Force ANSI output
      --no-ansi                        Disable ANSI output
  -n, --no-interaction                 Do not ask any interactive question
  -v|vv|vvv, --verbose                 Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```