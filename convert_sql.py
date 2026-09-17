import sqlite3
import re

# Read sql file
with open('studio12_studio.sql', 'r', encoding='latin1') as f:
    sql_text = f.read()

conn = sqlite3.connect('database.sqlite')
cursor = conn.cursor()

# Clean SQL for SQLite
# SQLite table definitions
cursor.execute("DROP TABLE IF EXISTS agenda")
cursor.execute("""
CREATE TABLE agenda (
  agenda_id INTEGER PRIMARY KEY AUTOINCREMENT,
  agenda_title TEXT,
  agenda_data TEXT,
  agenda_info TEXT,
  agenda_local TEXT,
  agenda_hora TEXT,
  foto_url TEXT,
  agenda_atracao TEXT,
  agenda_producao TEXT
);
""")

cursor.execute("DROP TABLE IF EXISTS albuns")
cursor.execute("""
CREATE TABLE albuns (
  album_id INTEGER PRIMARY KEY AUTOINCREMENT,
  album_name TEXT,
  album_pos INTEGER DEFAULT 999,
  album_desc TEXT,
  album_data TEXT,
  album_local TEXT
);
""")

cursor.execute("DROP TABLE IF EXISTS banner")
cursor.execute("""
CREATE TABLE banner (
  banner_id INTEGER PRIMARY KEY AUTOINCREMENT,
  banner_url TEXT,
  banner_link TEXT DEFAULT '0',
  banner_pos INTEGER DEFAULT 1,
  banner_location INTEGER
);
""")

cursor.execute("DROP TABLE IF EXISTS contato")
cursor.execute("""
CREATE TABLE contato (
  contato_id INTEGER PRIMARY KEY AUTOINCREMENT,
  contato_nome TEXT,
  contato_cidade TEXT,
  contato_email TEXT,
  contato_telefone TEXT,
  contato_mensagem TEXT
);
""")

cursor.execute("DROP TABLE IF EXISTS fotos")
cursor.execute("""
CREATE TABLE fotos (
  foto_id INTEGER PRIMARY KEY AUTOINCREMENT,
  foto_url TEXT,
  foto_caption TEXT,
  foto_data TEXT,
  foto_album INTEGER,
  foto_pos INTEGER DEFAULT 0,
  foto_info TEXT
);
""")

cursor.execute("DROP TABLE IF EXISTS noticia")
cursor.execute("""
CREATE TABLE noticia (
  noticia_id INTEGER PRIMARY KEY AUTOINCREMENT,
  noticia_title TEXT,
  noticia_foto TEXT,
  noticia_content TEXT,
  noticia_data TEXT
);
""")

cursor.execute("DROP TABLE IF EXISTS users")
cursor.execute("""
CREATE TABLE users (
  user_id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_login TEXT,
  user_password TEXT,
  user_email TEXT
);
""")

cursor.execute("DROP TABLE IF EXISTS video")
cursor.execute("""
CREATE TABLE video (
  video_id INTEGER PRIMARY KEY AUTOINCREMENT,
  video_title TEXT,
  video_cod TEXT,
  video_thumb TEXT
);
""")

# Parse INSERT statements
inserts = re.findall(r"INSERT INTO `(\w+)` \((.*?)\) VALUES\s*(.*?);", sql_text, re.DOTALL)
for table_name, cols, vals in inserts:
    # Fix backticks to SQLite or strip them
    cols_clean = cols.replace('`', '')
    # split values by tuple if multiple
    # convert VALUES block into valid SQL for sqlite
    # We can execute directly if formatting is sanitized
    vals_clean = vals.replace("`", "")
    try:
        cursor.execute(f"INSERT INTO {table_name} ({cols_clean}) VALUES {vals_clean};")
    except Exception as e:
        print(f"Error inserting into {table_name}: {e}")

conn.commit()

# Check table counts
for table in ['agenda', 'albuns', 'banner', 'contato', 'fotos', 'noticia', 'users', 'video']:
    count = cursor.execute(f"SELECT COUNT(*) FROM {table}").fetchone()[0]
    print(f"{table}: {count} rows")

conn.close()
