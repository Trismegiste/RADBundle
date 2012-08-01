How To Use ?
=============

Unit test for the Model
^^^^^^^^^^^^^^^^^^^^^^^^

1 - Create your Entity or Document
-----------------------------------
Create your new entity like any other entity

2 - Scaffold Unit Test Class
------------------------------
For ORM/Entity : app/console test:generate:entity ChoralTestBundle:Verify

For MongoDB/Document : app/console test:generate:document ChoralTestBundle:Verify

3 - Customize object initialization and validation
--------------------------------------------------
This scaffolder is not smart, nor smarter than you.
It provides only a skeleton with a listing of initializations to not forgot.

You have to fill the method XxxxxUnitTest::initializeValid(Xxxxx $obj) with good setters callings. The goal is the object must be valid through
the validator.

4 - Customize values for properties
-----------------------------------
The default values of dataProviders are just dumb ! Try something else, much more accurate to your domain model.


Func test for controllers
^^^^^^^^^^^^^^^^^^^^^^^^^^^

1 - Generating Test for one controller
---------------------------------------
Specify the shortname of the controller ::

  app/console test:generate:routing AcmeDemoBundle:Demo

2 - Generating Test for a filtered collection of routes
------------------------------------------------------------
Specify the prefix of the routes' names ::

  app/console test:generate:routing --bundle AcmeDemoBundle _demo