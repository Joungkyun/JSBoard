#!/bin/sh

# permission configuration
chmod 707 ../../config
chmod 707 ../../data
chmod 707 ../../data/test
chmod 707 ../../data/test/files
chmod 606 ../../config/{global.ph,spam_list.txt}
chmod 660 ../../admin/include/config.ph
chmod 606 ../../data/test/{config.ph,html_head.ph,html_tail.ph}

exit 0
