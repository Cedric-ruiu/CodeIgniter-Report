CodeIgniter-Report
========================================

My CodeIgniter Report is a librairie class to use in your CodeIgniter v2.2.x applications. It provides a solution to store and retrieve/display your error or information messages whenever you want. This librairie is multilangue ready, messages are cleared just when you retrieve them (remain available even after a redirect), purpose different type of storage (session CI, flashdata CI, array PHP), purpose templates (Twitter Bootstrap 3 ready) or make your own, display time or not, additionnel store in CI log or not, and to finnish: fast use!

Synopsis
--------

Quick
```php
$this->load->library('Report');

$this->report->set(FALSE, 'Ow you make a little mistake...');
$this->report->set(TRUE, 'Congratulation guys this time it\'s good!');

//Reload page can be possible at this time

echo $this->report->get_all();
```

Pimp my report
```php
$this->report->enable_log()->set = array(FALSE, 'Ow you make a little mistake...');
$this->report->set = TRUE; // Use the default success message
$this->report->set(2, 'Warning guy, you have maybe walked on something')->disable_log();
$this->report->set('3', 'Good info: Use your brain, but not forget your heart');

echo $this->report->with_time()->set_template('yes-i-can-use-my-template')->get_all();
```

Installation
------------

Ok, basic steps for Codeigniter newbies

1. Download project.
2. Drag files from folder project into your CodeIgniter application
3. You can load librarie manually in your controller: `$this->load->library('Report');` OR load automatically in your config/autoload.php: `$autoload['libraries'] = array('Report');`.
4. That's all! It's just a small library!

Storage
-------

###Type of message###

4 types of message can be stored: success, error, warning, info. You can use one of these markers to indicate which message you want:

* error code:   `0`, `'0'`, `FALSE`
* success code: `1`, `'1'`, `TRUE`
* warning code: `2`, `'2'`
* notice code:  `3`, `'3'`

###Way to store###

You have differents ways to store your message, exemple (with success):
```php
$this->report->set(TRUE, 'Congratulation guys everything is good!');
```
or
```php
$this->report->set = array(TRUE, 'You`re the best...');
```
or if you just want to use default message (see multi-language):
```php
$this->report->set = TRUE;
```

###Engine store###
You have the choice for type of store your data:
* `session`: use the CI Session to store the data. So you can reload set many messages and relaod page before print them!
* `flashdata`: use the CI Flashdata.
* `stack_array`: Report librairie make an array PHP. Be careful because you're array of datas will be cleaned if you reload page.

```php
protected $save_type = 'session';
```

###CI Log###
If you want you can in parallel save message in CI Log. That's easy if you want that, just change the default variable:
```php
protected $log = FALSE; // default
```
However, you can enable log in your application with this following method:
```php
$this->report->enable_log();
```
And disable:
```php
$this->report->disable_log();
```
Remember of you're `config/config.php` to set log options.

Display
-------

Simple and fast:
```php
echo $this->report->get_all();
```
The display use templating (see section below), and after that, all of your datas will be deleted, if you don't want this, you can change the default parameter:
```php
protected $auto_clean = TRUE;
```

Template
--------
In folder `views/report/` you will find different template to display your messages. For the moment you have just choice between Twitter bootstrap 2 or 3. If you want you can write your own template and set the default variable with the name of folder that you created:
```php
protected $template_default = 'my-awesome-template';
```
If you want use several templates in your application, for exemple one for client app view ans one for admin panel (bootstrap): no problem!
Just use the following method in your application like this:
```php
$this->report->set_template('template-wouah');
```

Language
--------

For your multilanguage application, you can use `report_lang.php` in the correct folder to fill the four default messages:
```php
$lang['report_error']   = '...';
$lang['report_success'] = '...';
$lang['report_warning'] = '...';
$lang['report_info']    = '...';
```
At the moment, just french and english version are writed.

Time
----

Maybe less helpful for client application, but more for administration panel, i like to associate time of registed message when i display them. To enable time when displaying message: 
```php
$this->report->enable_time();
```
To disable:
```php
$this->report->disable_time();
```
Or you can enable or disable time for you're entire application with the default vaiable:
```php
protected $time = FALSE;
```

Changelog
---------

**Version 2.0 (21/04/2015)**
* CI3 compatible
* New template engine, move into views folder. It's more flexible, more pretty, and less code
* Language slug refactoring
* Several fixing

**Version 1.2 (30/01/2015)**
* Add template change method and push project to GitHub
* Add 2 methods to enable/diable log CI
* Write README.md
* Push project to GitHub

**Version 1.1 (17/11/2013)**
* Moving template view in librairie folder
* Refactoring log option

**Version 1.0**
* Initial release
