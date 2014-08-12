smsAdmin
========

Interface to allow campaign managers to save Mobile Commons campaigns to our sms system. Built with [angular.js](angularjs.org).

##File Structure

This tool was designed in Laravel, but relies upon minimal PHP, so it can be easily ported to any other framework.
That being said, in the current Laravel structure, there are 3 important files:

1.  [public/includes/index.js](public/includes/index.js) - Directives, controllers, and functions are all defined here.
2.  [app/views/sms-tool.blade.php](app/views/sms-tool.blade.php) - The view used by the tool. 
3.  [app/controllers/HomeController.php](app/controllers/HomeController.php) - Saving the data.

Any libraries to be leveraged can be added to [public/requirements](public/requirements)

###Points to note
Since both angular and [Blade](http://laravel.com/docs/templates#blade-templating) use the double brace `{{}}` for outputting expressions by default, the Blade default has been overwritten in [routes.php](app/routes.php) to use `</%` and `<%%` instead.


##How to develop locally

1.  [Install Laravel](http://laravel.com/docs/installation) and create a new laravel project. Requirements are PHP 5.4 or greater.
  - If MCrypt is installed, you can create the project anywhere and use Laravel's built in server, `php artisan serve`. Otherwise create the project wherever your php projects normally go (htdocs in MAMP, htdocs/mysites in XAMPP, etc.).
2.  Clone the repo, then move the cloned files into the Laravel project (`cp -al source/* dest/ && rm -r source`)
3.  Get the necessary .json files (currently `tips-config.json` and `routing-config.json`) from here: [mdata-responder](https://github.com/DoSomething/ds-mdata-responder/tree/master/app/config).
4.  That's it! Spin up your server and try it out

##Laravel File Structure
![Laravel File Structure](http://laravelbook.com/images/laravel-architecture/laravel-project-structure.png "Laravel File Structure")
