# EA-RP CAD

***

## Purpose

A cross-platform _web-app_ designed for the use of members, staff and developers of __Flashing Lights__ Roleplay Servers to improve dispatch and communication procedures and therefore efficiency and immersion put into using or managing units in __Flashing Lights__.

***

## Installation

#### Upload The Files
__Method 1:__ Go to your web hosting panel (Usually CPanel) and find a _"file manager"_ from your host provider, then upload all of the CAD files to where you want it to be located.  
__Method 2:__ Go to your web hosting panel (Usually CPanel) and find your domain's _"FTP Details"_. Download and Install [FileZilla]() and input the FTP details, then navigate to the right directory and upload the CAD files.  
[Get Help](https://www.siteground.co.uk/kb/connect-to-ftp-using-filezilla/)
#### Grant Database Access
__Step 1:__ In your hosting control panel (Usually CPanel), find your SQL Database details and create a database and an admin login for it.  
__Step 2:__ Find the __connect.inc.php__ file in the root directory of the CAD project and open it.  
__Step 3:__ Edit the details at the very top of the file with your database details  
__Step 4:__ Save and reupload the file to the same location
#### Set Up The Database
_There are 4 tables that need to be set up in the database, this needs to be done manually for now but I plan to make this process automatic in the future_
- User login table __(cad_users)__
- Roleplay character table __(cad_characters)__
- Roleplay mobile unit table __(cad_units)__
- Backup request table __(cad_requests)__

___Copy and execute this SQL in your Database___

    CREATE TABLE cad_users( id INT PRIMARY KEY auto_increment, uid VARCHAR(50) NOT NULL, pwd VARCHAR(150) NOT NULL, tags VARCHAR(100) DEFAULT '', approved BOOLEAN DEFAULT false, characterids VARCHAR(30) NOT NULL DEFAULT ''); CREATE TABLE cad_characters ( id INT PRIMARY KEY auto_increment, name VARCHAR(40) UNIQUE NOT NULL, dept VARCHAR(10) NOT NULL, rank VARCHAR(40) NOT NULL DEFAULT 'Probationary', bio VARCHAR(1500) NOT NULL, appearance VARCHAR(1500) NOT NULL, status VARCHAR(30) NOT NULL DEFAULT 'alive', notes VARCHAR(1500) NOT NULL DEFAULT '', userid INT ); CREATE TABLE cad_units ( id INT PRIMARY KEY auto_increment, dept VARCHAR(20) NOT NULL DEFAULT 'pd', display VARCHAR(50) NOT NULL UNIQUE, callsign VARCHAR(10) NOT NULL UNIQUE, staff VARCHAR(20) NOT NULL, statuscode INT NOT NULL DEFAULT 1, postal VARCHAR(10) DEFAULT '' ); CREATE TABLE cad_requests ( id INT PRIMARY KEY auto_increment, characterid INT NOT NULL, unitid INT, types VARCHAR(30) NOT NULL DEFAULT '' ) INSERT INTO `cad_users` (`uid`, `pwd`, `approved`, `tags`) VALUES ('administrator', MD5('password'), 1, 'super|admin|dispatcher|owner'); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('ias', 'E1', 'Echo 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('ias', 'E12', 'Echo 1-2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('ias', 'E2', 'Echo 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('ias', 'E22', 'Echo 2-2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('ias', 'E3', 'Echo 3', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('ias', 'E4', 'Echo 4', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'A1', 'Alpha 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'A2', 'Alpha 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'WA1', 'Whiskey Alpha 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'WA2', 'Whiskey Alpha 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'P1', 'Papa 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'P2', 'Papa 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'WP1', 'Whiskey Papa 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'WP2', 'Whiskey Papa 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'F1', 'Foxtrot 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'F2', 'Foxtrot 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'B1', 'Bravo 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'B2', 'Bravo 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'R1', 'Romeo 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'R2', 'Romeo 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'G1', 'Griffin 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'D1', 'Delta 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'D2', 'Delta 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'T1', 'Trojan 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('pd', 'T2', 'Trojan 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('fd', 'L1', 'Ladder 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statuscode`, `postal`) VALUES ('fd', 'L2', 'Ladder 2', '', '7', '');

#### Test It Out

Open the URL where you chose to put your CAD files (You can click view at the top navbar if you're using CPanel) and then login with the following details and set your CAD up with all the users and units you would like:  
__Username__: _administrator_  
__Password__: _password_

#### Enjoy!

***

## Developer

Made by __Cobain Ambrose__:
\tThe Lead Programmer and Co-Director behind __Ambr3 Ltd__

Ambr3's Website: [Ambr3](http://ambr3.com)

## Collaborators

The logo was made by [Emergency Academy Roleplay](http://ea-rp.com)

## License

Feel free to use this open-source application but please do not claim my work as your own, even if you change it slightly. If you wish to assist by submitting a pull request or creating your own branch, please feel free to do so.
