Work *intensively* in creation : do not use at this time !
===========================================================

What
----
The purpose of this bundle is scaffolding. 
Primary tasks are unit test for a entity|document including validation

Who
---
Developers in the early stage of a project and "Refactorers" in a advanced stage of a project

Where
-----
Include this bundle in your AppKernel and the registerNamespace like any other
bundles.

Why
---
Because unit testing are mandatory. But the creation of an unit testing class for an entity|document is somewhat dull. This is usually the same code. Even if I prefer to spend more time on functional tests, unit tests are vital when you are in a team, when you need refactoring (and you always need refactoring, unless you can beat Spock on 3D chess)

When
----
Your are in a hurry and you need to refactor one or many bundles. Then, you discover that the sub-directory DirtyBundle/Test/ is empty, you are doomed ! 

How
---
Then, you have 2 choices :
* refactoring without unit testing. This is like playing russian roulette with a semi-automatic pistol
* use this bundle to rapidily generate some unit testing. Now you have only 2 or 3 bullets in your six shooter.

How much
--------
Don't abuse of this bundle. You should use this only once per entity. It does not make sense to make automatic unit testing every time you modifiy your class.

