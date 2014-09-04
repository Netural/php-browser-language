# php-browser-language

Simple function to determine the default language of the browser.

## Usage

Use it like this:

```
<?php
include_once ('http_lang.php');

$allowed_langs = array ('es','en','pt');

$lang = lang_getfrombrowser ($allowed_langs, 'en', null, false);

header('Location: ./'.$lang.'/');

?>
```

First param is the available languages, second one is the default language to fall back if you don't have the browser's language in your scope.

## Credits

Found it at [SelfHTML](http://aktuell.de.selfhtml.org/artikel/php/httpsprache/)
