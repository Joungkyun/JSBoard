#!/bin/sh

cp -aRp ../admin_sample/db.ph.orig ../../include/db.ph
cp -aRp ../sample ../../include/test
chmod 707 ../../include
chmod 644 ../../include/db.ph
chmod 707 ../../include/test -R
chmod 644 ../../include/*.*

exit 0
