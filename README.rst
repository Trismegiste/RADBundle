TrismegisteRADBundle
=====================

What
----
The purpose of this bundle is scaffolding :

* unit test for a entity|document including validation tests.
* functional tests for controller

Who
---

* Coders in the early stage of a project just after creating model
* Coders in a advanced stage of a project just before refactoring if unit tests don't exists (bad)
* Scanning all routes of bundle to check security when unauthenticated user
* Checking if no HTTP/500

Where
-----
Include this bundle in your AppKernel like any other bundles.

Why
---
Because unit testing are mandatory. You can't live without it. But honestly the creation of an unit testing class for an entity|document
is somewhat dull. This is usually the same code, the same start... Even if I prefer to spend more time on functional tests,
unit tests are vital when you are in a team and/or when you need refactoring (and you always need
refactoring, unless you can beat Spock on 3D chess).

Eventualy, the goal of the bundle is to earn time when you're starting test classes, every test class, but don't expect
it'll code in your place. You'll probably have to fill the gap, move the namespace, delete some tests, add yours, extends some validations...

When
----

- You have just created your entity with strong assertions and you need to check if validations are OK now
  and will be OK in the future after any change in the code.
- Your are in a hurry and you need to refactor one or many bundles. Then, you discover that the sub-directory DirtyBundle/Test/ is empty, you are doomed !

How
---
Then, you have 2 choices :

- refactoring without unit testing. This is like playing russian roulette with a machine gun
- use this bundle to rapidily generate some unit testing. Now you have only 2 or 3 bullets in your six shooter.

How much
--------
Don't abuse of this bundle. You should use this only once per entity.
It does not make ANY sense to re-create unit testing every time you modifiy your class !

