import re
import os
for filename in ['legacy.php', 'rex_global_settings.php', 'rex_global_settings_list.php', 'rex_var_global_var.php']:
    if not os.path.exists(filename): continue
    with open(filename, 'r') as f:
        content = f.read()
    content = content.replace('class \\rex_', 'class rex_')
    with open(filename, 'w') as f:
        f.write(content)
