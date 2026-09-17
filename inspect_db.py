import re

with open('studio12_studio.sql', 'r', encoding='latin1') as f:
    sql = f.read()

tables = re.findall(r'CREATE TABLE `(\w+)` \((.*?)\) ENGINE', sql, re.DOTALL)
for name, schema in tables:
    print(f'=== TABLE: {name} ===')
    print(schema.strip())
    print()
