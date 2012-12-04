1) Для использования нужно добавить в файл настроек в раздел компонентов:
```php
    'version' => array(
        'class' => 'wmdl.components.version.VersionComponent',
        'prefix' => 'v', // префикс перед версией
        'enable' => true, // включить либо выключить компонент
        'vcs' => 'hg' // тип системы контроля версий из которой будем брать версию
    ),
```

2) Вставить в title
```php
    <?php echo Yii::app()->getComponent('version')->getCurrentVersion(); ?>
````