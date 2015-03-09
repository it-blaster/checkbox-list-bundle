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

В `app/config/config.yml` необходимо подключить шаблон виджета `checkbox_list`:
``` bash
twig:
    form:
        resources:
            - 'ItBlasterCheckboxListBundle:Form:checkbox_list_widget.html.twig'

assetic:
    bundles:
        - 'ItBlasterAttachFileBundle'
```

Usage
-------
Разберём использование виджета `checkbox_list` на примере 2х сущностей 'Contact' и 'ContactGroup'.
В форме редактирования группы контактов добавляем виджет и вспомогательные методы:
``` php
class ContactGroupAdmin extends Admin
{
    protected $contact_choices = array();

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('Атрибуты')
                ->with('Атрибуты', ['class'=>'col-md-6'])
                    ->add('Slug')
                    ->add('formContacts', 'checkbox_list', array(
                        'label'                     => 'Контакты:',
                        'foreign_objects'           => $this->getContactForeignObjects(),
                        'choices'                   => $this->contact_choices,
                        'filter_add_foreign_object' => $this->getFilterAddContact(),
                        'foreign_object_model'      => 'contact',
                        'bundle_alias'              => 'app_main',
                    ))
                    ...
                ->end()
            ->end()
        ;
    }

    /**
     * Привязанные контакты
     *
     * @return array
     */
    protected function getContactForeignObjects()
    {
        $object = $this->getSubject();
        if($object) {
            $foreign_objects = ContactQuery::create()
                ->filterByGroupId($object->getId())
                ->_or()
                ->filterByGroupId(NULL)
                ->find();
        } else {
            $foreign_objects = ContactQuery::create()
                ->filterByGroupId(NULL)
                ->find();
        }

        $choices = array();
        foreach ($foreign_objects as $foreign_object) {
            $email = $foreign_object->getEmail() ? $foreign_object->getEmail() : 'не указан';
            $phone = $foreign_object->getPhone() ? $foreign_object->getPhone() : 'не указан';
            $label = 'Email: '.$email.', Телефон: '.$phone;

            $choices[] = array(
                'id'      => $foreign_object->getId(),
                'label'   => $label,
                'checked' => $foreign_object->getGroupId()
            );
            $this->contact_choices[$foreign_object->getId()] = $label;
        }
        return $choices;
    }

    /**
     * После создания объекта
     *
     * @param mixed $object
     * @return mixed|void
     */
    public function postPersist($object)
    {
        $object->updateContacts();
    }

    /**
     * После создания объекта
     *
     * @param mixed $object
     * @return mixed|void
     */
    public function postUpdate($object)
    {
        $object->updateContacts();
    }

    ...
}
```

И в модель `ContactGroup` добавляем следующие методы:
``` php
class ContactGroup extends BaseContactGroup
{
    protected $form_contacts = NULL;

    /**
     * Привязанные контакты
     * Используется только в форме CMS
     *
     * @return null
     */
    public function getFormContacts()
    {
        return $this->form_contacts;
    }

    /**
     * Привязанные контакты
     * Используется только в форме CMS
     *
     * @param mixed $form_contacts
     */
    public function setFormContacts($form_contacts)
    {
        $this->form_contacts = $form_contacts;
    }

    /**
     * Обновляем привязанные контакты
     * Используется только в форме CMS
     */
    public function updateContacts()
    {
        $object_list = $this->getFormContacts();
        if ($object_list !== NULL) {
            //затираем старые значения
            ContactQuery::create()
                ->filterByGroupId($this->getId())
                ->update(array('GroupId' => NULL));

            //выставляем новые
            if (is_array($object_list) && count($object_list)) {
                ContactQuery::create()
                    ->filterById($object_list)
                    ->update(array('GroupId' => $this->getId()));
            }
        }
    }
}
```


Credits
-------

It-Blaster <it-blaster@yandex.ru>