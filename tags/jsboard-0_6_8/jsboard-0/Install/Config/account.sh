#!/bin/sh

cp -aRp ../admin_sample/db.ph.orig ../../include/db.ph
cp -aRp ../admin_sample/info.php3.orig ../../admin/include/info.php3
cp -aRp ../sample ../../include/test

chmod 707 ../../include
chmod 644 ../../include/db.ph

chmod 707 ../../admin/include
chmod 606 ../../admin/include/info.php3

chmod 707 ../../include/test -R
chmod 606 ../../include/test/*.*

exit 0
