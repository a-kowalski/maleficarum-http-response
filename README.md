# Change Log
This is the Maleficarum HTTP response component implementation. 

## [6.0.1] - 2020-05-27
### Fixed
- Cast response body to string

## [6.0.0] - 2020-04-29
### Changed
- Updated to depend on and work with Phalcon 4.0.X

## [5.1.2] - 2019-06-14
### Changed
- Updated README

## [5.1.1] - 2019-03-13
### Changed
- Bound Twig dependency to 2.6.* (changed from ^2.4) since 2.7 makes and incompatible class change.  

## [5.1.0] - 2018-10-16  
### Changed  
- Add Twig extensions to TemplateHandler

## [5.0.0] - 2018-09-17  
### Changed  
- Upgraded IoC component to version 3.x  
- Upgraded phpunit version  
- Removed repositories section from composer file
- Fixed JsonHandlerTest test

## [4.1.1] - 2019-06-14
### Changed
- Changed composer.json file

## [4.1.0] - 2017-10-03
### Changed
- Support plugins in template handler 

## [4.0.0] - 2017-08-01
### Changed
- Replace phalcon template engine with twig
- Make use of nullable types provided in PHP 7.1 (http://php.net/manual/en/migration71.new-features.php)
- Fix tests
- Bump phalcon version

## [3.0.0] - 2017-03-23
### Changed
- Changed internal structure.
- Added default package initializer.

## [2.0.1] - 2017-03-09
### Changed
- Removed unnecessary dependencies from composer.json.

## [2.0.0] - 2017-03-08
### Changed
- Removed Config and Profiler dependencies from JSON Handler

## Added
- Added support for response plugins that can be assigned as closures. 

## [1.0.1] - 2017-03-06
### Fixed
- Fix render method forward
- Fix tests

## [1.0.0] - 2017-02-27
### Added
- This was an initial release based on the code written by pharaun13 and added to the repo by me
