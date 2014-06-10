## Installation
To install the extension you need to have Magento CE 1.6+ or Magento EE 1.11+.
- Copy contents of the extension's folder to your Magento root folder
- Go to *Admin->System->Cache Management* and push the 'Flush Cache Storage' button
- Log out from the admin panel (this step is required)
- Login to admin panel
- Go to *Admin->System->Configuration*, find Segment.io tab and enter your Segment.io key there
If you don't see the Segment.io tab, perhaps you forgot to relogin into the admin panel as described above. 

## Running tests
There are two types of tests included into the extension: functional and unit. To run the functional tests you need to have [PhantomJS](http://phantomjs.org/) and [CasperJS](http://casperjs.org/) installed. To run the unit tests everything you need it's to have [PHPUnit](http://phpunit.de/) installed.
To execute the tests using *nix command line interface on Linux/OSX or Cygwin like emulator on Windows go to [magento_root]/tests and execute the shell script:

```
./test.sh
```


