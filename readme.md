# EA-RP CAD

## Purpose

A cross-platform _web-app_ designed for the use of members, staff and developers of __Flashing Lights__ Roleplay Servers to improve dispatch and communication procedures and therefore efficiency and immersion put into using or managing units in __Flashing Lights__.

## Installation

#### 1) Upload The Files
__Method 1:__ Go to your web hosting panel (Usually CPanel) and find a _"file manager"_ from your host provider, then upload all of the CAD files to where you want it to be located.  
__Method 2:__ Go to your web hosting panel (Usually CPanel) and find your domain's _"FTP Details"_. Download and Install [FileZilla](https://filezilla-project.org/) and input the FTP details, then navigate to the right directory and upload the CAD files.  
[Get Help](https://www.siteground.co.uk/kb/connect-to-ftp-using-filezilla/)
#### 2) Grant Database Access
__Step 1:__ In your hosting control panel (Usually CPanel), find your SQL Database details and create a database and an admin login for it.  
__Step 2:__ Find the __connect.inc.php__ file in the root directory of the CAD project and open it.  
__Step 3:__ Edit the details at the very top of the file with your database details  
__Step 4:__ Save and reupload the file to the same location  
[Get Help](https://help.dreamhost.com/hc/en-us/articles/221610868-Finding-your-database-login-credentials)
#### 3) Set Up The Database
_There are 4 tables that need to be set up in the database, this needs to be done manually for now but I plan to make this process automatic in the future_
- User login table __(cad_users)__
- Roleplay character table __(cad_characters)__
- Roleplay mobile unit table __(cad_units)__
- Backup request table __(cad_requests)__

___Copy and execute this SQL in your Database___ [Get Help](https://www.hostgator.com/help/article/how-to-run-sql-queries-in-phpmyadmin)

    CREATE DATABASE earp;

___Initiate the database___

Open __{root}/portal/admin/db.php__ which will set up the tables and entries into the tables

___Customise the database___

If you wish to customise the users, units, unit requests or characters you can go back into your database and fill in with the relevant values. __Be aware!__ This may break the web application so make sure you know what you're doing.

#### 4) Test It Out

Open the URL where you chose to put your CAD files (You can click view at the top navbar if you're using CPanel) and then login with the following details and set your CAD up with all the users and units you would like:  
__Username__: _administrator_  
__Password__: _password_

#### 5) Enjoy!

I hope you and your communities have worlds of fun using this CAD system since that's the whole reason it was created! For roleplay fun and games :)

## Changelog

#### Beta 0.1

- base user login and character creation completed
- users cannot access portal until manually approved in the database
- Admins can now approved or disapprove new users in the app
- character names are coloured according to department in the portal
- added shared dropbox folder to portal
- Introduced user tags
- User tags are displayed next to the username in the portal
- Added a maximum number of characters
- Added a member count and member list in admin area
- Added var_export for developers
- Gave admins the ability to log in as any non-admin

#### Beta 0.2

- Admins can reset user passwords
- Added new super tag
- Admins can suspend (_unapprove_) users
- Users can change their passwords in the portal
- Added ranks and note section for all characters
- Made users able to view details about their character
- Made a dispatcher tag for users who have dispatch permissions
- Made a dispatch page for dispatchers
- Added character list for admins
- When you click on a user / character in admin area, it automatically inputs their name
- Changed HEX colour for CIVs
- Users can kill their characters
- Can change a character's rank from admin panel
- Can change a character's alive status from admin panel
- Characters can leave a unit if they're in one
- Characters can see a unit selection menu to join if they're not in one
- Character can join units from any department
- Dispatchers can now update and reset a unit's status & postal
- The dispatch screen now live updates without refreshing

#### Beta 0.3

- Mobile Unit details table is live updating
- Supporter roles can approve users
- Admins can change user tags
- added __/v__ command to view details about a unit
- added __/help__ command to see list of commands
- added __/exit__ command to leave dispatch CAD
- added click sound
- removed click sound
- added hover help menu & removed __/help__ command
- added a popup for the __/v__ command
- added an update ( __/u__ ) command
- changed __/p__ to __/l__ (for location)
- Updated FD callsigns
- Removed admins ability to promote themselves (MASSIVE BUG)
- minor bug fixes

#### Beta 0.4

- Users can now be approved as well as suspended (bug fix)
- Users can change their character's bio & appearance
- Changed the button text from "Rest In Peace" to "Mark as dead"
- Added buttons to request units
- Units can now request _PD, ARVO, IAS, PD_
- Units can press panic button
- Dispatchers can see unit requests
- Dispatch can see panic button presses (with alert sound)
- Units get a panic alert
- Dispatchers can cancel backup requests
- Units type in a more accurate location when they request backup
- Location field for backup auto-fills with the unit's current location
- Style changes to admin section
- Admins can change user tags more easily
- Current tags: _super, admin, supporter, dispatcher, beta-tester_
- Added colours for user tags
- New command: __/kick__ replaces the previous __/kickall__
- __/kickall__ now kicks everyone from every unit
- Terminal auto-fills with the __/u {callsign}__ when a unit is clicked in dispatch
- Animated view details window in dispatch
- The panic button will not sounds every time a command is typed when a panic is active (Bug Fix)
- Automated the table setup process
- Minor Bug Fixes

## Developer

Made by __Cobain Ambrose__: The Lead Programmer and Co-Director behind [Ambr3 Ltd](http://ambr3.com)

Ambr3's Website: http://ambr3.com

Support: enquiries@ambr3.com

## Collaborators

The logo was made by [Emergency Academy Roleplay](http://ea-rp.com)

Beta tested and used by [Emergency Academy Roleplay](http://ea-rp.com)

Made for use with [Flashing Lights - Police, Firefighting, Emergency Services Simulator](https://store.steampowered.com/app/605740/Flashing_Lights__Police_Firefighting_Emergency_Services_Simulator/)

## License

Feel free to use this open-source application but ___DO NOT___ claim my work as your own, even if you change it slightly. If you wish to assist by submitting a pull request or creating your own branch, please feel free to do so and contact me at coby@ambr3.com.
