newn
====

A Symfony project created on March 16, 2017, 3:20 pm.

For LCSH, do something like this:

(from http://id.loc.gov/techcenter/searching.html )

    $ curl -v http://id.loc.gov/authorities/label/marriage--drama

The locatin header in the result is something like

    Location: http://id.loc.gov/authorities/subjects/sh2008107247

Then get the data for sh2008107247 (the ID of the subject heading)

    $ curl -H 'Accept: application/json' -v http://id.loc.gov/authorities/subjects/sh2008107247 

Which will redirect to 

    Location: http://id.loc.gov/authorities/subjects/sh2008107247.json

Which is the parseable, usable version.
