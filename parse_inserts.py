import re
import sqlite3

with open('studio12_studio.sql', 'r', encoding='latin1') as f:
    sql = f.read()

conn = sqlite3.connect('database.sqlite')
cursor = conn.cursor()

# Find all statements starting with INSERT INTO
statements = re.split(r';\s*\n', sql)
for stmt in statements:
    stmt_strip = stmt.strip()
    if stmt_strip.startswith('INSERT INTO'):
        # Fix MySQL escaping like \' or \" or \n if needed for SQLite
        # In MySQL dump, escaped single quotes are \' which in SQLite standard SQL are ''
        # Also fix \r\n
        table_match = re.search(r'INSERT INTO `(\w+)`', stmt_strip)
        if not table_match:
            continue
        tbl = table_match.group(1)

        # Replace backticks
        clean_stmt = stmt_strip.replace('`' + tbl + '`', tbl)
        clean_stmt = re.sub(r'`(\w+)`', r'\1', clean_stmt)

        # Replace \' with '' for SQLite string literal escaping
        # Note: be careful with \\
        clean_stmt = clean_stmt.replace("\\'", "''")
        clean_stmt = clean_stmt.replace('\\"', '"')
        clean_stmt = clean_stmt.replace('\\r\\n', '\n')
        clean_stmt = clean_stmt.replace('\\n', '\n')

        try:
            cursor.execute(clean_stmt)
        except Exception as e:
            print(f"Error in table {tbl}: {e}")

conn.commit()

for table in ['agenda', 'albuns', 'banner', 'contato', 'fotos', 'noticia', 'users', 'video']:
    count = cursor.execute(f"SELECT COUNT(*) FROM {table}").fetchone()[0]
    print(f"{table}: {count} rows")

conn.close()
