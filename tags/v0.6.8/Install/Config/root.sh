#!/bin/sh

chgrp nobody ../../include
chgrp nobody ../../admin/include

chmod 775 ../../include
chmod 775 ../../admin/include

exit 0
