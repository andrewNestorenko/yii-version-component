1) You should add this part of code to your main.php file to the "components" part
```php
    'version' => array(
        'class' => 'wmdl.components.version.VersionComponent',
        'prefix' => 'v', // prefix before version (e.q. v1.2.1 or version-2.1.2)
        'enable' => true, 
        'vcs' => 'hg' // type of VCS (git, mercurial (hg))
        'allowedIp' => array(
        	'*',
        	'192.168.*'
        )
    ),
```

2) Include verstion output in page title
```php
    <?php echo Yii::app()->getComponent('version')->getCurrentVersion(); ?>
````