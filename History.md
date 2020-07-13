2.0.1 / 2015-09-17
==================
* Identified and fixed bug affecting added/removed product behavior

2.0.0 / 2015-09-01
==================

* The identify logic was filtering the userId from its usable data before it could render it into the `userId` field of its call. This meant that we weren't reliably rendering the userId in the one identify call we were firing on registration. That's been **fixed.**
* `Added Product` and `Removed Product` only used to pass SKU; now they pass the full product info dictionary
* Call identify on every page when the user is logged in, not just on registration
* Allow store owner to optionally flush a.js `user()` object with [`analytics.reset()`](https://segment.com/docs/libraries/analytics.js/#reset-logout) after customers log out (defaults to false)
* Allow store owner to optionally pass product properties which they'd like to **omit** from their product info dictionary (the same one used to populate `Completed Order`,  `Viewed Product`, `Added Product`, and `Removed Product` properties). This allows them to deny fields from being rendered in analytics calls.
* Strips all `null` K/V pairs from product properties by default.
* Customer log out used to trigger two `Logged Out` events due to a redundant logout mechanism that would attempt to grab the customers id to use post-logout. That mechanism has been removed. If a user opts not to flush (# 4 above), then analytics.js will handle caching and use of the resilient `userId`. If they do flush, then they will deliberately not have that id after logout.

1.1.0 / 2015-08-20
==================

  * fix duplicate category bug
  * remove debug logging
  * add intro text
  * adding functionality to record discounts and revenue
  * removing code that accidentally overwrites casting of Magento product info into Segment API friendly product info

1.0.0 - August 23, 2014
-----------------------
* first stable release

0.0.1 - July 14, 2014
---------------------
* initial release
