#!/bin/sh

os=`uname`

if [ "$os" = "Linux" ]; then
  cp -aRp ../ad_sample/global.ph.orig ../../config/global.ph
  cp -aRp ../ad_sample/spam_list.txt.orig ../../config/spam_list.txt
  cp -aRp ../ad_sample/config.ph.orig ../../admin/include/config.ph
else
  cp -Rp ../ad_sample/global.ph.orig ../../config/global.ph
  cp -Rp ../ad_sample/spam_list.txt.orig ../../config/spam_list.txt
  cp -Rp ../ad_sample/config.ph.orig ../../admin/include/config.ph
fi

if [ ! -f ../../config/default.themes ] ; then
  ln -s ./themes/white.themes ../../config/default.themes
fi

if [ "$os" = "Linux" ]; then
  cp -aRp ../sample ../../data/test
else
  cp -Rp ../sample ../../data/test
fi

# permission configuration
chmod 707 ../../config
chmod 707 ../../data
chmod 707 ../../data/test
chmod 707 ../../data/test/files
chmod 660 ../../config/{global.ph,spam_list.txt}
chmod 664 ../../admin/include/config.ph
chmod 606 ../../data/test/{config.ph,html_head.ph,html_tail.ph}

exit 0
