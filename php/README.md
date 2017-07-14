### php

PHP Parser.

## NOTE FROM THE FOUNDER OF SchemeRealizer - Michael Watzer

I highly encourage you to use the Token-based parser instead of the Introspection/Reflection-based parser.

Reasons:

 - Performance
 - No redeclare error(I tried my best to avoid them, but they can still occur)
 - Maintenance(I prefer to improve the Token-based parser over the Introspection/Reflection-based parser in the future)

