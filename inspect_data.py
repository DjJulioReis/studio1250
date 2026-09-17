import sqlite3
import json

conn = sqlite3.connect('database.sqlite')
conn.row_factory = sqlite3.Row
cursor = conn.cursor()

for tbl in ['agenda', 'albuns', 'banner', 'noticia', 'video', 'users', 'fotos']:
    rows = cursor.execute(f"SELECT * FROM {tbl} LIMIT 3").fetchall()
    print(f"=== {tbl} ===")
    for r in rows:
        print(dict(r))
    print()

conn.close()
