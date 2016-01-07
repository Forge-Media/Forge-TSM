
# ForgeCP 0.2.1
[![Build Status](https://scrutinizer-ci.com/g/Forge-Media/ForgeCP/badges/build.png?b=forgecp)](https://scrutinizer-ci.com/g/Forge-Media/ForgeCP/build-status/forgecp)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Forge-Media/ForgeCP/badges/quality-score.png?b=forgecp)](https://scrutinizer-ci.com/g/Forge-Media/ForgeCP/?branch=forgecp)
## FGN is committed to the ForgeCP Branch!

### By Jeremy Paton

Copyright (c) Forge Gaming Network 2015

Welcome to ForgeCP, a secure, modern DigitalOcean and Teamspeak 3 control-panel based on UserFrosting, jQuery, Bootstrap, ts3admin.class and DigitalOceanV2 PHP. ForgeCP is based on the UserFrosting system, written in PHP which in-turn is based of off UserCake. 

By using UserFrosting, ForgeCP gains a fine-grained authorization system, intuitive frontend interface based on HTML5 and Twitter Bootstrap. 

See UserFrosting here: https://github.com/alexweissman/UserFrosting
See UserCake here: http://usercake.com
See 

What is ForgeCP
-----------------
ForgeCP's primary use if for reselling DigitalOcean Droplets by Forge Gaming Network. ForgeCP allows one to associate a DO Droplet to a ForgeCP user and therefore provide them with control panel access to only their DO Droplets (Atoms).

Along with this functionality ForgeCP will in the near future also provide gaming communities and companies to manage their teamspeak server(s). By utilising  ts3admin.class the ForgeCP panel requires no server side application or integration and communicates directly with a TS3 server via Server Query.

User Features
-------------
What ForgeCP beta will offer:
- Manually add Droplet ID's to ForgeCP users
- Admins: View all Atoms
- Clients: View their Atoms only
- Atom control (Power On)
- Atom status & specifications
- Modern, responsive HTML5 interface
- Secure user managment engine

What ForgeCPv1 will offer:
- ALL THE ABOVE
- Clients will be able to make/delete Atoms
- Auto creation & deletion of Droplets on Atom creation

What ForgeCPv2 will offer:
- ALL THE ABOVE
- Multiple TS3 server support
- Admins: View all Atoms
- Clients: View their TS Servers only
- TS3 Server status & control (Stop|Start|Restart)
- TS3 Server snapshot (Automated & Manual)
- TS3 Server restore
- Twitter integration to TS3 Server(s)

Screenshots
-----------------
#### Login page
![Image of Theme](http://i.imgur.com/Cb8xq4x.png)

Security Features
-----------------
UserFrosting is designed to address the most common security issues with websites that handle sensitive user data:

- SSL/HTTPS compatibility
- Strong password hashing
- Protection against cross-site request forgery (CSRF)
- Protection against cross-site scripting (XSS)
- Protection against SQL injection

See the [security](http://www.userfrosting.com/security.html) section of our website for more details.

Requirments
--------------
- php 5.4 minimum
- MySQL 5.1 minimum
- Server Mail()

Installation
--------------
- Drop folder onto server
- Navigate your servers address where you droped ForgeCP
- Follow installation instructions
- Rename or Delete the install folder
- Enjoy

Change Log - v0.2.1
-------------------

[Older changes](CHANGELOG.md)