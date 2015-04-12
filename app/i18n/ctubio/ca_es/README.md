Generator cmd:
```
php -f dev/tools/Magento/Tools/I18n/generator.php -- --directory ./ --magento yes --output-file app/i18n/ctubio/ca_es/ca_ES.xml
```

Pack cmd:
```
php -f dev/tools/Magento/Tools/I18n/pack.php -- --source app/i18n/ctubio/ca_es/ca_ES.csv --locale ca_ES --pack ./ --allow_duplicates yes
```