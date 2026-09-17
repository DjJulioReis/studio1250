import re
import sqlite3

# Re-create fresh database schema
conn = sqlite3.connect('database.sqlite')
cursor = conn.cursor()

tables_schema = [
    ("agenda", "agenda_id INTEGER PRIMARY KEY, agenda_title TEXT, agenda_data TEXT, agenda_info TEXT, agenda_local TEXT, agenda_hora TEXT, foto_url TEXT, agenda_atracao TEXT, agenda_producao TEXT"),
    ("albuns", "album_id INTEGER PRIMARY KEY, album_name TEXT, album_pos INTEGER, album_desc TEXT, album_data TEXT, album_local TEXT"),
    ("banner", "banner_id INTEGER PRIMARY KEY, banner_url TEXT, banner_link TEXT, banner_pos INTEGER, banner_location INTEGER"),
    ("contato", "contato_id INTEGER PRIMARY KEY AUTOINCREMENT, contato_nome TEXT, contato_cidade TEXT, contato_email TEXT, contato_telefone TEXT, contato_mensagem TEXT"),
    ("fotos", "foto_id INTEGER PRIMARY KEY, foto_url TEXT, foto_caption TEXT, foto_data TEXT, foto_album INTEGER, foto_pos INTEGER, foto_info TEXT"),
    ("noticia", "noticia_id INTEGER PRIMARY KEY, noticia_title TEXT, noticia_foto TEXT, noticia_content TEXT, noticia_data TEXT"),
    ("users", "user_id INTEGER PRIMARY KEY, user_login TEXT, user_password TEXT, user_email TEXT"),
    ("video", "video_id INTEGER PRIMARY KEY, video_title TEXT, video_cod TEXT, video_thumb TEXT")
]

for tbl, cols in tables_schema:
    cursor.execute(f"DROP TABLE IF EXISTS {tbl}")
    cursor.execute(f"CREATE TABLE {tbl} ({cols})")

with open('studio12_studio.sql', 'r', encoding='latin1') as f:
    lines = f.readlines()

current_stmt = []
in_insert = False

for line in lines:
    if line.startswith("INSERT INTO"):
        in_insert = True
        current_stmt = [line]
    elif in_insert:
        current_stmt.append(line)
        if line.strip().endswith(";"):
            stmt_text = "".join(current_stmt)
            in_insert = False

            # extract table name
            m = re.search(r'INSERT INTO `(\w+)`', stmt_text)
            if m:
                tbl = m.group(1)
                # replace backticks
                clean_stmt = stmt_text.replace('`' + tbl + '`', tbl)
                clean_stmt = re.sub(r'`(\w+)`', r'\1', clean_stmt)
                clean_stmt = clean_stmt.replace("\\'", "''")
                clean_stmt = clean_stmt.replace('\\"', '"')
                # Use INSERT OR REPLACE INTO for sqlite in case of duplicate IDs in dump
                clean_stmt = clean_stmt.replace(f"INSERT INTO {tbl}", f"INSERT OR REPLACE INTO {tbl}")

                try:
                    cursor.execute(clean_stmt)
                except Exception as e:
                    print(f"Error in {tbl}: {e}")

conn.commit()

for tbl, _ in tables_schema:
    count = cursor.execute(f"SELECT COUNT(*) FROM {tbl}").fetchone()[0]
    print(f"{tbl}: {count} rows")

conn.close()
