#!/bin/sh

echo "==================================================="
echo " Scripted By JoungKyun Kim <admin@oops.org>"
echo " JSBoard Permission Configurations v1.0"
echo "==================================================="
echo 

OS=`uname`

if [ "$OS" = "Linux" ]; then
  if [ -f /etc/debian_version ]; then
    DIST="Debian"
    GROUPS="www-data"
    AD="Linux"
  elif [ -f /etc/redhat-release ]; then
    DIST="Redhat"
    GROUPS="nobody"
    AD="Linux"
  else
    DIST="Unknown"
    GROUPS="nobody"
    AD="Linux"
  fi
else
  DIST=$OS
  GROUPS="nobody"
  if [ "$OS" = "FreeBSD" ]; then
    AD="FreeBSD"
  else
    AD="Others"
  fi
fi

echo "## Operating System : ${DIST}"
echo -e "## Httpd Group : ${GROUPS}\n\n"
echo -n "Is right follow information? [Y/N](default Y) : "
read INFO

if [ "$INFO" = "N" ] || [ "$INFO" = "n" ]; then
  echo -n "Input Ur httpd group : "
  read GROUPS
fi

if [ "$OS" = "Linux" ]; then
  cp -af ../ad_sample/global.ph.orig ../../config/global.ph
  cp -af ../ad_sample/spam_list.txt.orig ../../config/spam_list.txt
  cp -af ../ad_sample/config.ph.orig ../../admin/include/config.ph
  if [ -d "../../data/test" ]; then
    cp -af ../sample/* ../../data/test/
  else
    cp -af ../sample ../../data/test
  fi
else
  cp -Rp ../ad_sample/global.ph.orig ../../config/global.ph
  cp -Rp ../ad_sample/spam_list.txt.orig ../../config/spam_list.txt
  cp -Rp ../ad_sample/config.ph.orig ../../admin/include/config.ph
  if [ -d "../../data/test"]; then
    cp -Rp ../sample/* ../../data/test/
  else
    cp -Rp ../sample ../../data/test
  fi
fi

if [ ! -f "../../config/default.themes" ] ; then
  ln -sf ./themes/basic.themes ../../config/default.themes
fi

# owner configuration
chgrp $GROUPS ../../config
chgrp -R $GROUPS ../../data/
chgrp $GROUPS ../../config/global.ph
chgrp $GROUPS ../../config/spam_list.txt
chgrp $GROUPS ../../admin/include/config.ph

# permission configuration
chmod 731 ../../config
chmod 775 ../../data
chmod 775 ../../data/test
chmod 775 ../../data/test/files
chmod 664 ../../config/spam_list.txt
chmod 660 ../../config/global.ph
chmod 664 ../../admin/include/config.ph
chmod 664 ../../data/test/config.ph
chmod 664 ../../data/test/html_head.ph
chmod 664 ../../data/test/html_tail.ph

echo -e "\nDONE!!!!"
exit 0
