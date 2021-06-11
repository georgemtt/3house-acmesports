# NFL Teams

This is a Drupal 8 & 9 compatible module that pulls NFL team data from an external API and provides functionalities to display it on the Acme Sports site. This module queries the ACME Sports API, and exposes the NFL Team data to blocks and views.


## Installation

This module can be installed simply by copying the src folder into the modules/custom folder and enabling it via the UI or Drush. 

However, there may be certain dependencies that have to be configured seperately to maximize the module's capability. There is a todo file entry to tie these dependencies into the module's composer.json form./


## Usage

1. After enabling the module, navigate to /admin/config/nfl_teams/settings to enter and configure the NFL Teams module settings.
2. Create a new view of NFL teams to your respective taste. (Note: Sorting is done manually in the module for only division and fullname. A todo is created to implement a Custom Sort Plugin that will allow creators to create dynamically on the Views UI and also expose forms to users)

### Starter Configs
1. See starter-config folder for useful config files and maybe use as a starting point. Remove the last txt suffix extenstion after yml. 
2. Order of config import: nfl_teams.settings must go first before any of the order view configurations. So,
    1. NFL Teams Config settings: nfl_teams.settings.yml.txt (SKIP: If you have entered the credentials already at /admin/config/nfl_teams/settings)
    2. Sample NFL Teams views in the module
        AFC: views.view.afc.yml.txt
        NFC: views.view.nfc.yml.txt
        NFL Team form view: views.view.nfl_team_form_view.yml.txt
3. The NFL Team views generate blocks that are then used to build pages with the dynamic data. Here are the respective block names: 
        AFC: AFC Block
        NFC: NFC Block
        NFL Team form view: NFL Team Form Block


## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.


## License
[MIT](https://choosealicense.com/licenses/mit/)