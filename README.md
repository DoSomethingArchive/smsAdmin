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

1.  Clone the repo into a location of your choice (as long as you can run that location on a php server)
2.  Get the necessary .json files (currently `tips-config.json` and `routing-config.json`) from here: [mdata-responder](https://github.com/DoSomething/ds-mdata-responder/tree/master/app/config). In the cloned repo, put them in `public/app/config`.
3.  That's it! Spin up your server and try it out

##How to deploy (first time)
1.  `ssh -p 38383 dosomething@admin.dosomething.org`
2.  `ssh utility1` (when prompted, enter normal dev pw here)
3.  Docroot is var/www/smsutils so `cd ../../var/www/smsutils`
4.  `git clone git@github.com:DoSomething/smsAdmin.git laravel`
5.  May need `sudo chmod -R 777 .` 

Subsequent deploys:
1.  Login as before and navigate to the directory `cd ../../var/www/smsutils/laravel
2.  `git pull`

##Laravel File Structure
![Laravel File Structure](http://laravelbook.com/images/laravel-architecture/laravel-project-structure.png "Laravel File Structure")
