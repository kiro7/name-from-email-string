# Name From Email String

Extracts person's real name from an email string.

This package replaces an earlier version found at <https://github.com/peterkahl/name-extractor>.

# Usage

```php
require __DIR__.'/namefromstring.class.php';

$extractor = new nameFromString;

$email = 'johnbutterworth@genius.whatever';

echo $extractor->get_name($email); # John Butterworth

```
