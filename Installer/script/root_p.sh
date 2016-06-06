#!/bin/sh

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
