# Extbaser
Create a TYPO3 Extbase Extension from an existing database scheme.

## Usage
### 1. Export your database scheme to a TYPO3 extension
If you have a basic [Symfony installation](http://symfony.com/doc/current/best_practices/configuration.html), you can use:
```
php bin/app.php extbaser:export your_database_name your_new_extension_key
```

Or configure your connection manually:
```
php bin/app.php extbaser:export your_database_name your_new_extension_key --host=host --user=username --password=password
```

The generated extension consists of a folder containing the file *ExtensionBuilder.json*, which is the project file for the *Extension Builder*, see below.

### 2. Upload the extension to your TYPO3 installation
Move the generated folder to the 'typo3conf/ext' folder.

### 3. Install the new extension in your TYPO3 installation
* Login to your TYPO3 Backend
* Open the module *Extension Builder* (or install it first in the Extension Manager if there's no such module)
* Click *Load* and select your new extension
* Adapt the extension configuration to your needs
* Click *Save*
* Activate your extension in the Extension Manager

## Roundtrip
Extbaser also supports roundtriping of Extbase Extensions, which means that if you update the extension with Extbaser all **Extension properties** set in the Extension Builder will remain:
* Copy the extension to somewhere where Extbaser can access it
* Retype the export you used before command and add the *-f* option: `php bin/app.php extbaser:export your_database_name your_new_extension_key --path=... -f` (the path variable is the parent folder of your extension folder, set it if the extension is not in your current directory)

## Help
To see a list of all arguments you can pass to Extbaser, type:
```
php bin/app.php extbaser:export --help
```