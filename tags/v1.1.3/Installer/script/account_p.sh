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
chmod 606 ../../data/test/config.ph
chmod 606 ../../data/test/html_head.ph
chmod 606 ../../data/test/html_tail.ph

exit 0
