# Name From Email String

Extracts person's real name from an email (or any) string.

This package replaces an earlier version found at <https://github.com/peterkahl/name-extractor>.

# Usage

```php
require __DIR__.'/namefromstring.class.php';

$email = 'johnbutterworth@genius.whatever';

echo nameFromString::getName($email); # John Butterworth

```
