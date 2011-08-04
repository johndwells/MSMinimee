# MSMinimee

* Author: [John D Wells](http://johndwells.com)
* Requires: [ExpressionEngine 2](http://www.expressionengine.com), [Minimee 2+](http://johndwells.com/software/minimee)

_MSMinimee is a work in progress; do not install without expecting it to break something._

_Minimee 2 is currently under heavy development; you're welcome to check out development over on [Github](https://github.com/johndwells/Minimee)._


## Overview

MSMinimee is an ExpressionEngine Module that makes [Minimee](http://johndwells.com/software/minimee) fully MSM-compatible.  
_This is not a replacement of Minimee_; rather it extends it only where it needs to. This way I can develop the features & functionality of MSM support separate from the core of what Minimee does.


## But wait, why?

You may wonder, why create a new Module rather than simply develop Minimee to be MSM-compatible?  A few reasons:

* When Minimee is configured via the bootstrap method, then it effectively is already MSM-compatible.
* When Minimee is configured via the bootstrap/config/global_var methods, **installing the Extension is not required**. This is when Minimee is at its most lean and mean.
* If Minimee were to save MSM site configs in its Extension, its performance would be progressively diminished for every MSM site added.
* Moving Minimee to an entirely Module-based setup would require _all users_ to formally "install" the add-on, even if they were to continue to configure via config.php or bootstrap.
* Moving Minimee to a Module would've also required a complicated, _potentially destructive_ upgrade procedure, because an EE add-on cannot have a module & plugin installed alongside each other (name collision).

So a new Module seemed the least disruptive, most scalable solution. And for those without a need for MSM compatibility, there's nothing new to do, learn or worry about.

But let me know if you read this and think otherwise.


## Installation Instructions

* Be sure Minimee 2+ is present in your system/expressionengine/third_party folder
* Copy the MSMinimee folder into your system/expressionengine/third_party folder
* Visit your Modules page and install MSMinimee

If you previously had Minimee's Extension installed, settings will be automatically carried over for your default site.



## Tag Usage

Usage is identical to Minimee, except all tags begin with {exp:msminimee:xxx} instead of {exp:minimee:xxx}. Simples.



## Changelog

* 0.0.0 - Waiting on initial release

