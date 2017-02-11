# PHPUnit / Selenium
[![Build Status](http://d7767b8b.ngrok.io/buildStatus/icon?job=SeleniumDouceursCoree)](http://d7767b8b.ngrok.io/job/SeleniumDouceursCoree/)

**Introduction**
> Les tests ont été implémenté en PHP. Les librairies choisies sont : <br :>
 - [PHPUnit](https://phpunit.de/) <br>
 - [Selenium](https://phpunit.de/manual/3.7/en/selenium.html) 
  
**Requirements** <br>
1. Composer<br>
2. Java<br>
3. Selenium

**Usage**
```
composer install
```
**Roadmap**
``` <br>
Launch Selenium Server in background
> cd ROOT_FOOLDER/java/bin
> $java.exe -jar -Dchromedriver.exe selenium-server-standalone-{{version}}.jar
```
``` <br> 
Launch PHPUnit test
> cd ROOT_FOOLDER/vendor/bin
> $phpunit {{PATH_TO_CLASS_TEST}}
```
