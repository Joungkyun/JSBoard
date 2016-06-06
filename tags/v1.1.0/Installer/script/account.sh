#!/bin/sh
echo "==================================================="
echo " Scripted By JoungKyun Kim <admin@oops.org>"
echo " JSBoard Permission Configurations v1.0"
echo "==================================================="
echo

OS=`uname`

if [ "$OS" = "Linux" ]; then
  AD="Linux"
else
  if [ "$OS" = "FreeBSD" ]; then
    AD="FreeBSD"
  else
    AD="Others"
  fi
fi

if [ "$OS" = "Linux" ]; then
  cp -af ../ad_sample/$AD/global.ph.orig ../../config/global.ph
  cp -af ../ad_sample/$AD/spam_list.txt.orig ../../config/spam_list.txt
  cp -af ../ad_sample/$AD/allow_browser.txt.orig ../../config/allow_browser.txt
  cp -af ../ad_sample/$AD/config.ph.orig ../../admin/include/config.ph
  if [ ! -d "../../data/test" ]; then
    cp -a ../sample/$AD/* ../../data/test/
  else
    cp -a ../sample/$AD ../../data/test
  fi
else
  cp -Rp ../ad_sample/$AD/global.ph.orig ../../config/global.ph
  cp -Rp ../ad_sample/$AD/spam_list.txt.orig ../../config/spam_list.txt
  cp -Rp ../ad_sample/$AD/allow_browser.txt.orig ../../config/allow_browser.txt
  cp -Rp ../ad_sample/$AD/config.ph.orig ../../admin/include/config.ph
  if [ -d "../../data/test" ]; then
    cp -Rp ../sample/$AD/* ../../data/test/
  else
    cp -Rp ../sample/$AD ../../data/test
  fi
fi

if [ ! -f ../../config/default.themes ]; then
  ln -s ./themes/basic.themes ../../config/default.themes
fi

# permission configuration
chmod 707 ../../config
chmod 707 ../../data
chmod 707 ../../data/test
chmod 707 ../../data/test/files
chmod 660 ../../config/global.ph
chmod 606 ../../config/spam_list.txt
chmod 606 ../../config/allow_browser.txt
chmod 606 ../../admin/include/config.ph
chmod 606 ../../data/test/config.ph
chmod 606 ../../data/test/html_head.ph
chmod 606 ../../data/test/html_tail.ph

exit 0
