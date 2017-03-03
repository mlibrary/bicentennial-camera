# Setting up PHP

```
$ brew install composer
$ composer install
```

Or:

```
$ # download https://getcomposer.org/download/1.3.2/composer.phar
$ php /path/to/composer.phar install
```

Set database configuration: `./vendor/database.ini`, whatever you want:

```
[mysql]
host = "localhost"
dbname = "dlxs"
user = "user"
password = "password"
``` 

# About image_href

`image_href` is the base of an IIIF Image API call: `https://quod.lib.umich.edu/cgi/i/image/api/image/bhl:bl005608:BL005608` takes parameters to fetch region, 
size, rotation, and format:

* `/full/full/0/default.jpg` is the whole honking image
* `/full/,500/0/default.jpg` = whole image at 500px wide, with aspect ratio
* `/full/!500,500/0/default.jpg` = fit the image in 500x500 box, with aspect ratio
* `/full/500,500/0/default.jpg` = fit the image in a 500x500 box, aspect ratio be damned
* `/full/!500,500/90/default.jpg` = rotate the image by 90 degrees

