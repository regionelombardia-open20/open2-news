# Amos News

News management.

### Installation
You need to require this package and enable the module in your configuration.

add to composer requirements in composer.json
```
"open20/amos-news": "dev-master",
```

or run command bash:
```bash
composer require "open20/amos-news:dev-master"
```

Enable the News modules in modules-amos.php, add :
```php
 'news' => [
	'class' => 'open20\amos\news\AmosNews',
 ],

```

add news migrations to console modules (console/config/migrations-amos.php):
```
'@vendor/open20/amos-news/src/migrations'
```

If a frontend or a public site are used in your project and news need to be visible outside backend, enable form/wizard fields to allow publication in frontend/home page with params:
```php
'news' => [
        'class' => 'open20\amos\news\AmosNews',
        'params' => [
            'site_publish_enabled' => true,
            'site_featured_enabled' => true
        ]
    ],
```


The content is suitable to be used with cwh content management.
To do so:
- Activate cwh plugin
- Open cwh configuration wizard (admin privilege is required) url: <yourPlatformurl>/cwh/configuration/wizard
- search for news in content configuration section
- edit configuration of news and save

If tags are needed enable this module in "modules-amos.php" (backend/config folder in main project) in tag section.
After that, enable the trees in tag manager.

If platform uses report and/or comments and you want to enable News to be commented/to report a content, 
add the model to the configuration in modules-amos.php:

for reports: 

```
 'report' => [
     'class' => 'open20\amos\report\AmosReport',
     'modelsEnabled' => [
        .
        .
        'open20\amos\news\models\News', //line to add
        .
        .
     ]
     ],

```

for comments:

```
  'comments' => [
    'class' => 'open20\amos\comments\AmosComments',
    'modelsEnabled' => [
        .
        .
        'open20\amos\news\models\News', //line to add
        .
        .
 	],
  ],
```


### Configurable fields

Here the list of configurable fields, properties of module AmosNews.
If some property default is not suitable for your project, you can configure it in module, eg: 

```php
 'news' => [
	'class' => 'open20\amos\news\AmosNews',
	'validatePublicationDate' => false, //changed property (default was true)
 ],
```

* **validatePublicationDate** - boolean, default = true  
If this attribute is true the validation of the publication date is active.  
By default, you can ONLY validate news with publication_date greater or equal than TODAY.  
Set to false to allow validation for news with publication_date less than TODAY.

* **filterCategoriesByRole** - boolean, default = false   
If true, enables category role check via table news_category_roles_mm.  
By default news category are available to all users.  
In case categories are in association with rbac roles, populate table 'news_category_roles_mm' 
and set to true the Module property filterCategoriesByRole in configurations:

```php
    'news' => [
      'class' => 'open20\amos\news\AmosNews',
      'filterCategoriesByRole' => true
    ]
```

* **hidePubblicationDate** - boolean, default = false  
The news created are always visible, hide fields publication_from, publication_to

* **newsRequiredFields** - array, default = ['news_categorie_id', 'titolo', 'status', 'descrizione_breve']  
Mandatory fields in news form: by default news category, title and status are mandatory.  
If in your platform, for example, you don't want title to be a mandatory field, overwrite newsRequiredFields property as below:
```php
'news' => [
    'class' => 'open20\amos\news\AmosNews',
    'newsRequiredFields' => ['news_categorie_id', 'status']  
],
```

* **defaultCategory** - integer 
The ID of the default category pre-selected for the new News
```php
'news' => [
    'class' => 'open20\amos\news\AmosNews',
    'defaultCategory' => 3  
],
```
* **autoUpdatePublicationDate** - boolean, default = false  
This set the auto update of the publication date on the save if the news is published
```php
'news' => [
    'class' => 'open20\amos\news\AmosNews',
    'autoUpdatePublicationDate' => true
],
```

* **$enableCategoriesForCommunity** - boolean, default = false  
* **$showAllCategoriesForCommunity** - boolean, default = false  
* **$whiteListRolesCategories** - default = ['ADMIN', 'BASIC_USER'] 


