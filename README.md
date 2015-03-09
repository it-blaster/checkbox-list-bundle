CheckboxListBundle
====================

[![Build Status](https://scrutinizer-ci.com/g/it-blaster/checkbox-list-bundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/it-blaster/checkbox-list-bundle/build-status/master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/it-blaster/checkbox-list-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/it-blaster/checkbox-list-bundle/?branch=master)

Symfony2 bundle. Type "checkbox-list" for symfony-form.

Installation
------------

Добавьте <b>ItBlasterCheckboxListBundle</b> в `composer.json`:

```js
{
    "require": {
        "it-blaster/checkbox-list-bundle": "dev-master"
	},
}
```

Теперь запустите композер, чтобы скачать бандл командой:

``` bash
$ php composer.phar update it-blaster/checkbox-list-bundle
```

Композер установит бандл в папку проекта `vendor/it-blaster/checkbox-list-bundle`.

Далее подключите бандл в ядре `AppKernel.php`:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new ItBlaster\CheckboxListBundle\ItBlasterCheckboxListBundle(),
    );
}
```

Usage
-------
В форме редактирования нужно добавить поле с типом `checkbox_list`
``` php
->add('formStories', 'checkbox_list', array(
    'label'                     => 'Истории:',
    'foreign_objects'           => $this->getStoryForeignObjects(),
    'choices'                   => $this->stories_choices,
    'filter_add_foreign_object' => $this->getFilterAddStory(),
    'foreign_object_model'      => 'story',
    'bundle_alias'              => 'app_main',
))
```
И необходимо добавить соответствующие методы и поля в форме:


Credits
-------

It-Blaster <it-blaster@yandex.ru>