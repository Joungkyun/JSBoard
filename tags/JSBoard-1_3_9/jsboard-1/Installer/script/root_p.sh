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
  elif [ -f /etc/redhat-release ]; then
    DIST="Redhat"
  else
    DIST="Unknown"
  fi
else
  DIST=$OS
  UGROUPS="nobody"
fi

# location of apache configuration file
if [ -f "/etc/httpd/conf/httpd.conf" ] ;then
  CONF="/etc/httpd/conf/httpd.conf"
  break
elif [ -f "/etc/www/conf/httpd.conf" ]; then
  CONF="/etc/www/conf/httpd.conf"
  break
elif [ -f "/etc/www/httpd.conf" ]; then
  CONF="/etc/www/httpd.conf"
  break
elif [ -f "/usr/local/apache/conf/httpd.conf" ]; then
  CONF="/usr/local/apache/conf/httpd.conf"
else
  while [ true ];
  do
    echo "ERROR : Can't file httpd.conf"
    echo -n "Input path of httpd.conf : "
    read CONF

    if [ -f "${CONF}" ]; then
      break
    fi
  done
fi

UGROUPS=$(cat ${CONF} | grep -E "^(`echo -ne "\t"`|[ ])*Group " | awk '{print $2}')

echo "## Operating System : ${DIST}"
echo -e "## Httpd Group : ${UGROUPS}\n\n"

if [ "${UGROUPS}" = "#-1" ]; then
  echo "${UGROUPS} is not system group"
  echo "Excute again this script, after changed GROUP"
  echo "directive in httpd.conf that existed group on system"
  exit 1
else
  echo -n "Is right follow information? [Y/N](default Y) : "
  read INFO
fi

if [ "$INFO" = "N" ] || [ "$INFO" = "n" ]; then
  echo -n "Input Ur httpd group : "
  read UGROUPS
fi

# owner configuration
chgrp $UGROUPS ../../config
chgrp -R $UGROUPS ../../data/
chgrp $UGROUPS ../../config/global.ph
chgrp $UGROUPS ../../config/security_data.ph
chgrp $UGROUPS ../../config/spam_list.txt
chgrp $UGROUPS ../../config/allow_browser.txt
chgrp $UGROUPS ../../admin/include/config.ph

# permission configuration
chmod 731 ../../config
chmod 775 ../../data
chmod 664 ../../config/spam_list.txt
chmod 664 ../../config/allow_browser.txt
chmod 660 ../../config/global.ph
chmod 660 ../../config/security_data.ph
chmod 664 ../../admin/include/config.ph
if [ -d "../../data/test" ]; then
  chmod 775 ../../data/test
  chmod 775 ../../data/test/files
  chmod 664 ../../data/test/config.ph
  chmod 664 ../../data/test/html_head.ph
  chmod 664 ../../data/test/html_tail.ph
fi

echo -e "\nDONE!!!!"

exit 0
