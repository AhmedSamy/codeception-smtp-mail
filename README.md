### Installation

``` bash
$ composer require ahmedsamy/codeception-smtp-mail
```

### Configuration
in `acceptance.yml`
``` yaml
modules:
    enabled:
        - Gmail
    config:
        Gmail:
            username: name@email.com
            password: password

```

Build codeception

``` bash
$ bin/codecept build
```

### Available methods
- ``` seeEmail($criteria) ```
- ``` canSeeEmail($criteria) ```
- ``` seeEmail($criteria) ```
- ``` cantSeeEmail($criteria) ```
- ``` dontSeeEmail($criteria) ```
- ``` openEmail($criteria) ```
- ``` grabEmail() ```
- ``` grabLinkFromEmail($url) ```
- ``` clickInEmail($url) ```
- ``` canSeeLinkInEmail($url) ```
- ``` seeLinkInEmail($url) ```
- ``` cantSeeLinkInEmail($url) ```
- ``` dontSeeLinkInEmail($url) ```

* $criteria is according to imap syntax, see http://php.net/manual/en/function.imap-search.php

### Examples

Checking email with subject and date

``` php
$I->seeEmailBy('SUBJECT "HOWTO be Awesome" SINCE "8 August 2008"');

$I->canSeeEmail('SUBJECT "Welcome Email"');

$I->openEmail('SUBJECT "Open me"');

$I->canSeeEmail('SUBJECT "good words"');

$I->seeLinkInEmail('http://google.com/awesome');

$I->clickInEmail("http://google.com/awesome");

$I->grabLinkFromEmail("http://google.com/awesome");

```

### Configuration reference

TODO !


###TODOs

- Write tests
- Auto clear attachment option
- Add travis.yml
