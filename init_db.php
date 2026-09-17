<?php
// init_db.php - Initializes database schema and imports data from studio12_studio.sql

$dbPath = __DIR__ . '/database.sqlite';
$sqlDumpPath = __DIR__ . '/studio12_studio.sql';

$pdo = new PDO("sqlite:" . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create tables
$tables = [
    "agenda" => "CREATE TABLE IF NOT EXISTS agenda (
        agenda_id INTEGER PRIMARY KEY AUTOINCREMENT,
        agenda_title TEXT,
        agenda_data TEXT,
        agenda_info TEXT,
        agenda_local TEXT,
        agenda_hora TEXT,
        foto_url TEXT,
        agenda_atracao TEXT,
        agenda_producao TEXT
    )",
    "albuns" => "CREATE TABLE IF NOT EXISTS albuns (
        album_id INTEGER PRIMARY KEY AUTOINCREMENT,
        album_name TEXT,
        album_pos INTEGER DEFAULT 999,
        album_desc TEXT,
        album_data TEXT,
        album_local TEXT
    )",
    "banner" => "CREATE TABLE IF NOT EXISTS banner (
        banner_id INTEGER PRIMARY KEY AUTOINCREMENT,
        banner_url TEXT,
        banner_link TEXT DEFAULT '0',
        banner_pos INTEGER DEFAULT 1,
        banner_location INTEGER
    )",
    "contato" => "CREATE TABLE IF NOT EXISTS contato (
        contato_id INTEGER PRIMARY KEY AUTOINCREMENT,
        contato_nome TEXT,
        contato_cidade TEXT,
        contato_email TEXT,
        contato_telefone TEXT,
        contato_mensagem TEXT,
        contato_data DATETIME DEFAULT CURRENT_TIMESTAMP
    )",
    "fotos" => "CREATE TABLE IF NOT EXISTS fotos (
        foto_id INTEGER PRIMARY KEY AUTOINCREMENT,
        foto_url TEXT,
        foto_caption TEXT,
        foto_data TEXT,
        foto_album INTEGER,
        foto_pos INTEGER DEFAULT 0,
        foto_info TEXT
    )",
    "noticia" => "CREATE TABLE IF NOT EXISTS noticia (
        noticia_id INTEGER PRIMARY KEY AUTOINCREMENT,
        noticia_title TEXT,
        noticia_foto TEXT,
        noticia_content TEXT,
        noticia_data TEXT
    )",
    "users" => "CREATE TABLE IF NOT EXISTS users (
        user_id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_login TEXT UNIQUE,
        user_password TEXT,
        user_email TEXT
    )",
    "video" => "CREATE TABLE IF NOT EXISTS video (
        video_id INTEGER PRIMARY KEY AUTOINCREMENT,
        video_title TEXT,
        video_cod TEXT,
        video_thumb TEXT
    )",
    "musicas" => "CREATE TABLE IF NOT EXISTS musicas (
        musica_id INTEGER PRIMARY KEY AUTOINCREMENT,
        musica_title TEXT NOT NULL,
        musica_artist TEXT,
        musica_album TEXT,
        musica_url TEXT NOT NULL,
        musica_cover TEXT,
        musica_duration TEXT,
        musica_data DATETIME DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($tables as $name => $sql) {
    $pdo->exec($sql);
}

// Check if data already populated
$stmt = $pdo->query("SELECT COUNT(*) FROM agenda");
if ($stmt->fetchColumn() == 0 && file_exists($sqlDumpPath)) {
    $lines = file($sqlDumpPath);
    $current_stmt = [];
    $in_insert = false;

    foreach ($lines as $line) {
        if (str_starts_with(trim($line), "INSERT INTO")) {
            $in_insert = true;
            $current_stmt = [$line];
        } elseif ($in_insert) {
            $current_stmt[] = $line;
        }

        if ($in_insert && str_ends_with(trim($line), ";")) {
            $stmt_text = implode("", $current_stmt);
            $in_insert = false;

            if (preg_match('/INSERT INTO `(\w+)`/', $stmt_text, $m)) {
                $tbl = $m[1];
                $clean_stmt = str_replace('`' . $tbl . '`', $tbl, $stmt_text);
                $clean_stmt = preg_replace('/`(\w+)`/', '$1', $clean_stmt);
                $clean_stmt = str_replace("\\'", "''", $clean_stmt);
                $clean_stmt = str_replace('\\"', '"', $clean_stmt);
                $clean_stmt = str_replace("INSERT INTO {$tbl}", "INSERT OR REPLACE INTO {$tbl}", $clean_stmt);

                try {
                    $pdo->exec($clean_stmt);
                } catch (Exception $e) {
                    // Silently continue on duplicate/minor insert issues
                }
            }
        }
    }
}

// Insert sample music tracks if empty
$stmt = $pdo->query("SELECT COUNT(*) FROM musicas");
if ($stmt->fetchColumn() == 0) {
    $sample_music = [
        ['Flashback Dance Mix 80s', 'DJ Vitinho Veneno', 'Studio 1250 Special Vol. 1', 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3', 'sample_cover1.jpg', '06:12'],
        ['Electro Eurodance Hits 90s', 'DJ Julio Reis', 'Retro Night Sessions', 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3', 'sample_cover2.jpg', '07:05'],
        ['Rock & Synth Classics', 'Luiz Alberto & Joel SP', 'Sexta Rock Live', 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3', 'sample_cover3.jpg', '05:40'],
        ['Underground Project Set', 'Marco Dusch', 'Underground Sessions', 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-4.mp3', 'sample_cover4.jpg', '08:15']
    ];

    $ins_music = $pdo->prepare("INSERT INTO musicas (musica_title, musica_artist, musica_album, musica_url, musica_cover, musica_duration) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($sample_music as $m) {
        $ins_music->execute($m);
    }
}

// Ensure default admin password hash is updated if using standard bcrypt or md5
// studio12_studio.sql has md5 passwords: 'b1dd7a36d95cf1ec610f6945ab02029a' (admin)
