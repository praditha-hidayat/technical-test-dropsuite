# Duplication file counter
### Praditha Hidayat (pradithah.1124@gmail.com)
### Programming Language: PHP v7.x

This project contain two php files with description below:
1. `config.php` => Contain the configuration of path which will be scanned
2. `file_counter.php` => Main function to count the duplicate file on the folder which has been set on `config.php`

### How to run the function
* Set the path of folder which you want to scanned. By default, it will use `example folder` on this repository
* Run the PHP using terminal. Point the terminal to this root folder and execute command `php file_counter.php`

*Notes:*
* This function can be executed as well from the browser, BUT only for the small files. 
If it scan the big files, probably the function will show the maximum execution time error (depends on the `max_execution_time` value on php.ini, by default it is 30 seconds)
* There is a limitation to write the output of the file. It will only take the first 2kb maximum for the content it self.
