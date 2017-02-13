# Continuons Integration with : Les Douceurs de Corée :two_women_holding_hands:
[![Build Status](http://d7767b8b.ngrok.io/buildStatus/icon?job=SeleniumDouceursCoree)](http://d7767b8b.ngrok.io/job/SeleniumDouceursCoree/)
[![Code Climate](https://codeclimate.com/github/DidYoun/DouceursDeCoree/badges/gpa.svg)](https://codeclimate.com/github/DidYoun/DouceursDeCoree)
[![Issue Count](https://codeclimate.com/github/DidYoun/DouceursDeCoree/badges/issue_count.svg)](https://codeclimate.com/github/DidYoun/DouceursDeCoree)
[![Test Coverage](https://codeclimate.com/github/DidYoun/DouceursDeCoree/badges/coverage.svg)](https://codeclimate.com/github/DidYoun/DouceursDeCoree/coverage)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

**Introduction**
> The tests have been implemented with : 
 - [PHPUnit](https://phpunit.de/) <br>
 - [Selenium](https://phpunit.de/manual/3.7/en/selenium.html) 
> In addition of those tests, we have configure Jenkins job in order to review all the Pull Request from X branch to protected branch (as master)
 - [Jenkins](https://jenkins.io/) <br>
 - Jenkins plugins :
    - [Github](https://plugins.jenkins.io/github) <br />
    - [Github Pull Request Builder](https://plugins.jenkins.io/ghprb)<br />

**Requirements** <br>
- Composer<br>
- Java<br>
- Selenium
- [Chromedriver](http://chromedriver.storage.googleapis.com/index.html)
- A Jenkins server
- Github repository

**Usage**
```
composer install
```

**Roadmap**
> In this project, we launch tests with Selenium and PHPUnit. In that case, we need to launch a Selenium server before running phpunit.
``` <br>
Launch Selenium Server in background
> $cd path/to/folder/java/bin 
> OR $java -jar 
> $java.exe -jar -Dchromedriver.exe selenium-server-standalone-{{version}}.jar
```
> When the Selenium server is running, you have to run the phpunit tests.
``` <br> 
Launch PHPUnit test
> cd ROOT_FOOLDER
> $vendor/bin/phpunit --configuration phpunit.xml --testsuite all 
```
