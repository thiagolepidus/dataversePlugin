# Dataverse Plugin

We are implementing this plugin for OPS and OJS 3.3 (or higher) for SciELO Brasil.

It is a work in progress, and we do not have an estimate to finish it.

## Compatibility

The latest release of this plugin is compatible with the following PKP applications:

* OPS 3.3 (or higher)

## Plugin Download

To download the plugin, go to the [Releases page](https://github.com/lepidus/dataversePlugin/releases) and download the tar.gz package of the latest release compatible with your website.

## Installation dependencies 
* [php-zip](https://www.php.net/manual/pt_BR/zip.installation.php)

## Development dependencies
* [php-zip](https://www.php.net/manual/pt_BR/zip.installation.php)

## Install from repository clone and start `swordappv` submodule

1. Use SSH/HTTP to clone the Dataverse plugin repository on your machine
2. With the Dataverse plugin repository cloned on your machine, open a terminal inside the repository and run the following commands:
 * git submodule init
 * git submodule update
3. In the root of the OPS directory, execute the command to create the tables used by the plugin:
 * php tools/upgrade.php upgrade
4. With the submodules installed in the plugin from the steps above, it is possible to link the symbolic plugin or simply copy and paste the plugin type into the OPS, in this case the folder is located at the path ops/plugins/generic .

## Installation

1. Install the 'php-zip' dependency.
2. Enter the administration area of ​​your application and navigate to `Settings`>` Website`> `Plugins`> `Upload a new plugin`.
3. Under __Upload file__ select the file __dataverse.tar.gz__.
4. Click __Save__ and the plugin will be installed on your website.

## Instructions for use

After installation, it is necessary to enable the plugin. This is done in `Website Settings` > `Plugins` > `Installed Plugins`.

With the plugin enabled, you should expand its options by clicking the arrow next to its name and then accessing its `Settings`. In the new window, the _Dataverse URL_ (Root Dataverse URL), _Dataverse_ (Dataverse URL) and _API Token_ will be displayed. After filling in the fields, just confirm the action by clicking `Save`. The plugin will only work after filling in these data.

# License

__This plugin is licensed under the GNU General Public License v3.0__

__Copyright (c) 2021-2022 Lepidus Tecnologia__

__Copyright (c) 2021-2022 SciELO__
