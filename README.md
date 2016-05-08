[![Code Climate](https://codeclimate.com/github/Forge-Media/Forge-TSM/badges/gpa.svg)](https://codeclimate.com/github/Forge-Media/Forge-TSM)
[![DevDependencies](https://david-dm.org/Forge-Media/Forge-TSM.svg)](https://david-dm.org/Forge-Media/Forge-TSM.svg)
[![Heroku](http://heroku-badge.herokuapp.com/?app=forge-tsm&style=flat)](http://heroku-badge.herokuapp.com/?app=forge-tsm=&style=flat)

# ForgeTSM 0.2.0 *Development*
## For development purposes only (Based on heroku-multipack-nodejs-php-example)

### By Jeremy Paton & Marc Berman

Copyright (c) Forge Gaming Network 2015

Welcome to ForgeTSM, a Teamspeak 3 channel creator web-app based on PHP & ts3admin.class, developed using Cloud9 & deployable to Heroku. We intend to either use AngularJS or Bootstrap for the frontend, however thats all in the future right now we are focused on the backend. 

By using this multipack-nodejs-php the Forge-TSM app can be quickly deployed to Heroku, wihich would otherwise be difficult as Forge-TSM is a PHP app which requires UI Bootstrap to be installed by Bower.

Dev branch details:
-----------------
- Based on the [heroku/heroku-buildpack-multi](https://github.com/heroku/heroku-buildpack-multi)
- Combines the [Node.js](https://github.com/heroku/heroku-buildpack-nodejs) and [PHP](https://github.com/heroku/heroku-buildpack-php) buildpacks, which allows using Node from inside the PHP buildpack's `bin/compile`.
- Forge-TSM will use [Bower](http://bower.io) in a [Composer](http://getcomposer.org) [post-install-cmd](https://getcomposer.org/doc/articles/scripts.md) to install [UI Bootstrap](https://github.com/angular-ui/bootstrap).

Dev quick deploy:
-----------------
[![Deploy](https://www.herokucdn.com/deploy/button.png)](https://heroku.com/deploy)

Example: [http://heroku-multipack-nodejs-php-ex.herokuapp.com/](http://heroku-multipack-nodejs-php-ex.herokuapp.com/)

How it works
-----------------
1. The file `.buildpacks` instructs the Multi Buildpack which buildpacks to run in sequence
2. The Node.js buildpack installs Bower using NPM (see `package.json`/`npm-shrinkwrap.json`)
3. The Node.js buildpack makes its binaries available to the next buildpack in the chain
4. The PHP buildpack runs and installs dependencies using Composer
5. As part of the composer install step, the `post-install-cmd` scripts run
1. That executes `$(npm bin -q)/bower install` - `bower install` would work too, as `node_modules/.bin` is on `$PATH` on Heroku, but it would likely not work on local development environments, hence the more portable use of prefixing the result from `npm bin -q` to retrieve said directory name.
1. Bower installs Bootstrap
1. Done!