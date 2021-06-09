# ACME Sports site

Todo file for the ACME Sports site.

### Todo

- [ ] Implement Wave Accesibility recommendations.
- [ ] Push sites to Pantheon if necessary for live testing.
- [ ] Varnish caching layer.
- [ ] Since we are in Canada, add translation support to French. (Nice to have)

### In Progress

- [ ] Setup Repository of site codebase on GitHub.

### Done ✓

- [x] Created Drupal 9 site with Acquia Dev Desktop.
- [x] Updated core dependencies.
- [x] Updated composer.json and installed essential contrib modules and libraries.
- [x] Set default theme to Olivero: Although this is currently Experimental, it's highly popular and likely included in core as soon as it's stable. It's also very modernized, stylistic and mobile friendly.
- [x] Updated settings.php and settings.local.php (see CUSTOM TODO ENTRIES BELOW in respective files).
- [x] Created NFL Teams module that pulls data from the ACME Sports API.
- [x] Extended NFL Teams module into a service that can be integrated with blocks and views.
- [x] Configured Yoast SEO for real time seo checks when creating content and verifying metatags.
- [x] Configured Easy Breadcrumb.
- [x] Configured SEO Checklist 
- [x] Configured search.
- [x] Enabled Admin toolbar for easier navigation.
- [x] Enabled layout builder module and integrate with basic page.
- [x] Created three views / blocks pairs: AFC / AFC Block, NFC / NFC Block and NFL Team form view / NFL Team Form Block.
- [x] Created three pages: (ACME Sports NFL Home) leads to two other pages and representations (Active NFL Teams and ACME Sports NFL Team Form) that display the NFL Teams pulled from the API. 
    - [x] ACME Sports NFL Home (Welcome page): /nfl
    - [x] Active NFL Teams (Representation/option 1): /nfl/teams
    - [x] ACME Sports NFL Team Form (Representation/option 2): nfl/teams/form
    - [x] Page URLs were configured in order to establish a user guided path. (Using Easy breadcrumbs, used can easily retrace back to previous pages)
- [x] Added Menu links for created pages.
- [x] Implement caching strategy. 
    - [x] 1 hour caching of Views / Blocks (AFC and NFC / AFC Block and NFC Block) used in more static pages. i.e Active NFL Teams page.
    - [x] No caching in Views / Blocks (NFL Team Form view / NFL Team Form Block) used in more dynamic/interactive pages. i.e in ACME Sports NFL Team Form page.
    - [x] Overall Browser and proxy cache maximum age set to 3 hours.
    - [x] Bandwidth optimization: Aggregation of CSS and JS files.
    - [x] Depending on frequency of API Updates, this can easily changed on the Drupal configuration.
- [x] Exposed Admin settings @ NFL Teams Configuration to allow users update API settings from directly on the Drupal Interface(Location: /admin/config/nfl_teams/settings).
- [x] Added Exception handling to NFL Teams module in the event of BAD API response or API outage or any service interruption.
- [x] Added Configuration entry (outage_msg) to allow users set custom outage message. Default message is set to 'Unable to Access ACME Sports API currently. Please try again later.' 
- [x] Perfomed UX/UI anaylsis and styling.
- [x] Performed SEO and metatags checks for created contents.
- [x] Configured EU Cookie Compliance module and implemented minimal configuration of the site GDPR banner.
- [x] Accessibility check with WAVE.
- [x] Browser and Mobile Compatibility: Tested site and pages on multiple mobile devices and browsers.
- [x] Updated LazySizes library.
- [x] Export configurations.
