#!/bin/sh

chgrp nobody ../../include
chgrp nobody ../../include/db.ph
chmod 640 ../include/db.ph
chmod 770 ../../include

exit 0
