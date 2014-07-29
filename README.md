smsAdmin
========

#SMS Tool

Interface to allow campaign managers to save Mobile Commons campaigns to our sms system. 

##File Structure

This tool was designed in Laravel, but includes minimal PHP, so it can be easily ported to any other framework.
That being said, in the current Laravel structure, there are 3 important files:

1. [public/includes/index.js](public/includes/index.js) - Directives, controllers, and functions are all defined here.
2. [app/views/sms-tool.blade.php](app/views/sms-tool.blade.php) - The view used by the tool. 
3.  [app/controllers/HomeController.php](app/controllers/HomeController.php) - Saving the data.

