### Installation

``` bash
$ composer require ahmedsamy/codeception-smtp-mail
```

### Configuration
in `acceptance.yml`
``` yaml
modules:
    enabled:
        - Smtp
    config:
        Smtp:
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
- ``` canSeeEmailAttachmentsCount($count) ```
- ``` canSeeEmailAttachment($name) ```
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

$I->canSeeEmailAttachmentsCount(2);

$I->canSeeEmailAttachment('contract.pdf'); //accepts full file name or part of it

```

### Configuration reference

``` yaml
Smtp:
    username: username@domain.com #required
    password: password123 #required
    imap_path: {imap.gmail.com:993/imap/ssl}INBOX  #imap path defaults to gmail config
    wait_interval: 1 #waiting interval between trials in seconds
    retry_counts: 3 # how many trials till
    attachments_dir: tests/_data #where email attachments are stored
    auto_clear_attachments: true #whether to clear attachments folder every run or not

```


###TODOs

- Write tests
- Add travis.yml
