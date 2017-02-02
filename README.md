#Name From Email String
Extracts person's real name from an email (or any) string.

This package replaces an earlier version found at <https://github.com/peterkahl/name-extractor>.

##Usage

```php
require __DIR__.'/namefromstring.class.php';

$email = 'johnbutterworth@genius.whatever';

echo nameFromString::getName($email); # John Butterworth

```

##Dictionary
This class depends on a dictionary of names.

While it is good to have large dictionary of all possible strings (names), having the dictionary too large may cause erroneous results. Remember, you can never expect a precise result from this class; in most cases, the results are quite precise.

For your convenience, extended version of the dictionary is provided. You may, also, create your own dictionary, especially if the one provided doesn't contain the names that are common in your geographical area.
