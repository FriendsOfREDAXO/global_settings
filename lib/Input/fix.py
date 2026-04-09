import os
import re

for filename in os.listdir('.'):
    if not filename.endswith('.php'): continue
    with open(filename, 'r') as f:
        content = f.read()
    
    content = re.sub(r'(?<!\\)\b(rex_[a-zA-Z0-9_]+)\b', r'\\\1', content)
    
    with open(filename, 'w') as f:
        f.write(content)
