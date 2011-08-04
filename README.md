# MSMinimee

* Author: [John D Wells](http://johndwells.com)
* Requires: ExpressionEngine 2, [http://johndwells.com/software/minimee](Minimee) 1.4+


## Overview

MSMinimee is an ExpressionEngine Module that makes [http://johndwells.com/software/minimee](Minimee) fully MSM-compatible.
_This is not a replacement of Minimee_; rather it extends it only where it needs to. This way I can develop the features & functionality of MSM support separate from the core of what Minimee does.

You may wonder, why create a new Module rather than simply develop Minimee to be MSM-compatible?  A few reasons:

* When Minimee is configured via the bootstrap method, then it effectively is already MSM-compatible.
* When Minimee is configured via the bootstrap/config/global_var methods, **installing the Extension is not required**. This is when Minimee is at its most lean and mean.
* If Minimee were to save MSM site configs in its Extension, it could potentially balloon in size, and most certainly drag on performance.
* Moving Minimee to an entirely Module-based setup would require _all users_ to formally "install" the add-on, even if they were to continue to configure via config.php or bootstrap.
* Moving Minimee to a Module would've also required a complicated, potentially destructive upgrade procedure, because an EE add-on cannot have a module & plugin installed alongside each other (name collision).


## Installation Instructions

* Be sure Minimee 1.4+ is installed


## Changelog

* 1.0.0 - Initial Release

