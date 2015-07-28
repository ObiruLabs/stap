Southern Tier AIDS Program
============

The craft cms powered Southern Tier AIDS Program site.

Initial Setup
-----------
1. Download and install MAMP.
2. Start up MAMP and setup new database and user permissions.
2. Update db credentials from /craft/config/db.php.example to /craft/config/db.php
3. Setup Craft
  * Download from https://buildwithcraft.com/
  * Rename as extracted folder to "craftapp"
  * Copy folder here
  * You should now have the following folders at the same level - craft and craftapp
4. Install Craft
  * Go to localhost:8888/admin/login
  * Setup local admin
5. Optionally restore the db backup from /config/southern-tier-aids-program*.zip
6. Setup MandrillForm with keys and fix local ssl issue
  * http://tutewall.com/ssl-certificate-problem-unable-to-get-local-issuer-certificate/
7. Setup Craft CMS email SMTP to use Mandrill
  * Under localhost:8888/admin/settings/email
8. Configure Stripe key for the stripe plugin

Development
-----------
* Make sure npm is installed. To install, follow these [instructions](https://docs.npmjs.com/getting-started/installing-node).
* Make sure grunt is installed. If not, run `sudo npm install -g grunt`.
* Make sure the grunt CLI is installed. If not, run `sudo npm install -g grunt-cli`.
* Run `npm install` to install dependencies.
* Run `grunt server` to start up a server on `localhost:8080`
* Run `grunt watch` to watch for changes in Haml and Sass files and automatically compile to HTML and CSS
* When adding new JavaScript files:
  * Add them to ` application.js `
  * Copy over the dependency list to ` Gruntfile.js `
* When adding a new CSS module:
  * Include module inside ` application.scss `
* When including a new asset, consider using an aws s3 bucket

Deployment
-----------
1. Create a branch off master with a version ` x.x.x `
2. Compile assets
  * If JS changes are made, increment version in application.js to update its hash.
  * In the terminal, run ` grunt `
3. Stage generated assets from ` /dist `
4. Commit change
5. Tag the commit to be deployed
6. Highlight all files
7. Right click, Sync with Deployed

Note: You have to expand the .html file that is generated for it to be uploaded/diffed when syncing.
