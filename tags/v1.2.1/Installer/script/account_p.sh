#!/bin/sh

# permission configuration
chmod 707 ../../config
chmod 707 ../../data
chmod 707 ../../data/test
chmod 707 ../../data/test/files
chmod 660 ../../config/global.ph
chmod 660 ../../config/spam_list.txt
chmod 606 ../../config/allow_browser.txt
chmod 660 ../../admin/include/config.ph
if [ -d "../../data/test" ]; then
  chmod 606 ../../data/test/config.ph
  chmod 606 ../../data/test/html_head.ph
  chmod 606 ../../data/test/html_tail.ph
fi

if [ "$LANG" = "ko" ]; then
  echo "config/global.ph config/spam_list.txt admin/include/config.ph"
  echo "file의 group 소유권이 nobody 에게 있어야 합니다. 만약 위의 파"
  echo "일들의 그룹 소유권을 변경할수 없다면 permission을 606으로 맞춰"
  echo "주십시오"
else
  echo "Group Owner of \"config/global.ph config/spam_list.txt"
  echo "admin/include/config.ph\" will set nobody that is operation"
  echo "group of Web Server. If don't set nobody, you will set the"
  echo "permission 606."
fi

exit 0
