# ACME Sports site

This is a Drupal 9 site developed with Acquia Dev Desktop on PHP 7.3.15.


## Installation

The local site name url is acmesports.dd and is already configured in the settings.local.php to add this a trusted host.

### Preset Install (Recommended)
Set up the site with my local database export acmesports.sql OR import into your respective database. (This will include the non-config changes (i.e custom pages, nodes and database entries) I've implemented and created for the Acme Sports site.)

### Fresh Install
Set up the site like any other drupal site with the codebase. (Note non-config entitites like nodes etc will have to be recreated)
1. Install dependencies
    ```
    composer install 

    ```
2. Import config
    ```
    drush cim 

    ```

### Preferred IDE
Using Acquia Dev Desktop 2, you can import a new local drupal site quite easily as well and access https://acmesports.dd:8443.


## Usage

### Preset Install
Dive right in. Homepage (aka ACME Sports NFL Home) leads to two other pages and representations (aka Active NFL Teams and ACME Sports NFL Team Form) that display the NFL Teams pulled from the API. 

### Fresh Install
1. View the created views / blocks
    AFC / AFC Block (views.view.afc.yml)
    NFC / NFC Block (views.view.nfc.yml)
    NFL Team Form / NFL Team Form Block (views.view.nfl_team_form_view.yml)
2. Add them to your desired pages or implementations.


## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.


## License
[MIT](https://choosealicense.com/licenses/mit/)
