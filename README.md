# PHP Nginx - Secured links
It took me some time to figure out how to get this done properly.. The most important part to understand is this nginx config-
```nginx
secure_link_md5 "$arg_expires$uri$remote_addr <custom string>";
```
This is your "encryption" structure. You can add your custom string and change the order of the variables as you wish. For example-
```nginx
secure_link_md5 "$remote_addr$arg_expires$uri no_one_can_guess_my_secret>";
```
It is required to have the same structure inside buildSecuredUrl.php:
# MD5 (Binary mode)
Please note that MD5 requires binary mode. You can check the examples below:
# PHP
```php
return $url .'?md5='. md5( $url . $_SERVER['REMOTE_ADDR'] .' <custom string>', true) .'&expires='. $expiration;
```
# JS
```javascript
return url + '?md5=' + $.md5( url + user_ip + ' <custom_string>', ' <custom_string>') + '&expires=' + expiration;
```
# Nginx example
Secured links must be set via your site's configuration. You can also play with the returned errors as you wish.
```nginx
secure_link $arg_md5,$arg_expires;
secure_link_md5 "$arg_expires$uri$remote_addr <custom string>";

if ($secure_link = "") { return 403; }
if ($secure_link = "0") { return 410; }
```
