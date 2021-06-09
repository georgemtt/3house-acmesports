# Resulta Web Development Challenge Submission

## Mind map

### Future Proof Infrastructure: Very Vanilla and Contrib dependent.

Platform: Drupal 9 (Latest)

Default theme: Olivero (Currently Experimental but looking to be included in core)
However, it's highly popular in the community and likely to be included in core as soon as it's stable. It's also very modernized, stylistic and mobile friendly.

### Performance and Data Storage / Persistence

Data persistence on Drupal is minimized and is very much dependent on APIs. My approach was that the data is on the API so we just need to render it. We don't have to recreate nodes/entities or manually import updates when the service data is updated.

### Variety / Options

If this were a real life client, chances are they would love to see differnet implmentations of how the data is handle and displayed on the site. So, I've created to different pages: 
	- Active NFL Teams (Representation/option 1): /nfl/teams
	- ACME Sports NFL Team Form (Representation/option 2): nfl/teams/form

### Easy for generic Site builders (Accesible settings on the UI)

Exposed Admin settings @ NFL Teams Configuration to allow users easily update API settings from directly on the Drupal Interface(Location: /admin/config/nfl_teams/settings). This useful for users who are not technical OR do not have access to the code base.

### Caching

    - 1 hour caching of Views / Blocks (AFC and NFC / AFC Block and NFC Block) used in more static pages. i.e Active NFL Teams page.
    - No caching in Views / Blocks (NFL Team Form view / NFL Team Form Block) used in more dynamic/interactive pages. i.e in ACME Sports NFL Team Form page.
    - Overall Browser and proxy cache maximum age set to 3 hours.
    - Bandwidth optimization: Aggregation of CSS and JS files.
    - In the description, the client claims that the remote API is frequently updated, so this can easily changed on the Drupal configuration accordingly.

A future todo can be to look in to implementing a Varnish cache layer. 

### Fail-Over strategy (Exception Handling)

What happens when the API service is down or not available? Display outage message to users. As a test, you can simply disable the internet connection and access the created pages: 
	- 	The ACME Sports NFL Team Form page by design has no cache implemeneted for the view created. Since it's not cached, it will the display outage message right away.
	- 	The Active NFL Team page by design has a 1 hour cache implemented for the views created. Since it's cacheable,  the display outage message may not display right away, thus the API data pulled may remain. 

### Site UI, UX and Look & Feel

	- No, custom twig templating. Leveraged the out of the box functionality of Olivero, and just updated different blocks in different regions, redesigned certain aspects to look & feel better and maximize ease of use.
	- Retrieved images from unsplash.
	- Mobile compatible.
	- Ran Accesibility checks using WAVE. Added follow up todo to implement recommendations.
	- Seamless story experience: I created the 3 pages and related them with each other, thereby helping experience of Site Visitors.  From the homepage, visitors will be able to navigate seamlessly to other pages.
	- Configured Drupal out of the box search.


## Solution to Consuming external APIs: 

### Custom module : Create a Views Query Plugin and a Service using HTTP Client / Guzzle. (My solution)

I went with this apporach for the following reasons:
	- Extensive level of control over what data is consumed, and how it's handled.
	- Views can be used to build a UI for displaying and filtering the data.
	- Displays built with Views integrate well with Drupal's Layout Builder and Blocks systems.
	- Data is not persisted in Drupal and is queried fresh for each page view.
	- Views offers an easily updateable caching to help improve performance and reduce the need to make API calls for every page load.
	- Guzzle middleware: Guzzle middleware has a large ecosystem for handling common tasks like authentication, connection timeout etc. For example, if in the future the service requires OAuth authentication, Guzzle middleware has a functionality to handle that.

### Other solutions I considered are:
	- Using the Migrate API combined with the HTTP Fetchers in the Migrate Plus module.
	- Use client-side JavaScript to query the API and display the returned data.
	- Feeds module.


## Site Access

### Admin Login Credentials
Randomized for Security purposes.

Username: LfYZyRPVQZVuaN
Password: 2F23Qq8HyC-b&5+b}@s:


## Closing Statements

I wanted to treat this like a real life project, so I took time to create and configure the ACME Sports site (beyond the challenge in itself) to a viable MVP state. With that, I have also created .md files for the NFL Teams module and as well as the Acme Sports site for instructions on how to set up and install, todo future improvements and updates, changelog file etc. 

Please do not hesitate to contact me if you have any questions. In all Scrum fashion, I'm open to a live demo/review and walkthrough of my submission OR can look into uploading and configuring my local ACME Sports site to my pantheon and providing you the credentials to access it.
