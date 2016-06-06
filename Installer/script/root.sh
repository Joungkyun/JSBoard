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

if [ -z ../../config/default.themes ] ; then
  ln -sf ./themes/white.themes ../../config/default.themes
fi

if [ "$os" = "Linux" ]; then
  cp -aRp ../sample ../../data/test
else
  cp -Rp ../sample ../../data/test
fi

# owner configuration
chgrp nobody ../../config
chgrp -R nobody ../../data/
chgrp nobody ../../config/{global.ph,spam_list.txt}
chgrp nobody ../../admin/include/config.ph

# permission configuration
chmod 731 ../../config
chmod 775 ../../data
chmod 775 ../../data/test
chmod 775 ../../data/test/files
chmod 664 ../../config/spam_list.txt
chmod 660 ../../config/global.ph
chmod 664 ../../admin/include/config.ph
chmod 664 ../../data/test/{config.ph,html_head.ph,html_tail.ph}

exit 0
