#!/bin/sh

cp -Rp ../admin_sample/db.ph.orig ../../include/db.ph
cp -Rp ../sample ../../include/test
chmod 703 ../../include
chmod 604 ../../include/db.ph
chmod 707 -R ../../include/test
chmod 644 ../../include/*.*

exit 0
