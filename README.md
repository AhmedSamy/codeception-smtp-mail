### Installation

Add this to composer.json

``` bash
$ composer require ahmedsamy/codeception-smtp-mail
```

### configuration
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
- ``` seeEmailBy($criteria) ```
- ``` canSeeEmailBy($criteria) ```
- ``` seeEmailBy($criteria) ```
- ``` cantSeeEmailBy($criteria) ```
- ``` dontSeeEmailBy($criteria) ```
- ``` grabEmailBy() ```

* $criteria is according to imap syntax, see http://php.net/manual/en/function.imap-search.php

### Examples

Checking email with subject and date

``` php
$I->seeEmailBy('SUBJECT "HOWTO be Awesome" SINCE "8 August 2008"');

```


###TODOs

- Write tests
- Handle attachments
- Asserts links in email body
- Click links in email body
- Implement wait method
