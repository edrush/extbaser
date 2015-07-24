# Extbaser
Create a TYPO3 Extbase Extension from an existing database schema.

## Installation

Clone or install via composer:
```
composer require edrush/extbaser
```

## Usage
### 1. Export your database schema to a TYPO3 Extbase Extension
Configure your connection:
```
php bin/app.php extbaser:export -u username -p password dbname target_extension_key
```
Connection parameters default to [Symfony best practices](http://symfony.com/doc/current/best_practices/configuration.html):
```
php bin/app.php extbaser:export dbname target_extension_key
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
* Retype the export command you used before  and add the *-f* option: `php bin/app.php extbaser:export dbname target_extension_key -f --path=...` (the path variable is the parent folder of your extension folder, set it if the extension is not in your current directory)

## Help
To see a list of all arguments you can pass to Extbaser type:
```
php bin/app.php extbaser:export --help
```