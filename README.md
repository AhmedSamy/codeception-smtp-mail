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
- ``` seeInEmailBy($criteria) ```
- ``` canSeeInEmailBy($criteria) ```
- ``` seeInEmailBy($criteria) ```
- ``` cantSeeInEmailBy($criteria) ```
- ``` dontSeeInEmailBy($criteria) ```
- ``` grabEmailBy() ```

* $criteria is according to imap syntax, see http://php.net/manual/en/function.imap-search.php

###TODOs

- Write tests
- Handle attachments
- Asserts links in email body
- Click links in email body
- Implement wait method
