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
