<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');


$host = '92.113.22.127';
$user = 'u442158423_prod_user';
$pass = 'F6QEI5|s';
$name = 'u442158423_prod_db';
$port = 3306;


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

function out($payload, $statusCode = 200)
{
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$body = json_decode(file_get_contents('php://input') ?: '{}', true);
if (!is_array($body)) {
    $body = [];
}

// Allow both query string and JSON style.
$action = $_GET['action'] ?? ($body['actions']['action'] ?? ($body['action'] ?? 'execture_query'));

$mysqli = new mysqli($host, $user, $pass, $name, $port);
if ($mysqli->connect_error) {
    out([
        'ok' => false,
        'error' => 'Database connection failed.',
        'message' => $mysqli->connect_error,
    ], 500);
}
$mysqli->set_charset('utf8mb4');

switch ($action) {
    case 'metadata':
        $tables = [];

        $tableSql = "SELECT table_name FROM information_schema.tables WHERE table_schema = '{$name}' ORDER BY table_name";
        $tableRes = $mysqli->query($tableSql);
        if (!$tableRes) {
            out(['ok' => false, 'error' => 'Failed to load tables.', 'message' => $mysqli->error], 500);
        }

        while ($t = $tableRes->fetch_assoc()) {
            $tableName = $t['table_name'];
            $tables[$tableName] = [
                'table' => $tableName,
                'columns' => [],
                'columns_map' => [],
            ];
        }

        $colSql = "SELECT table_name, column_name, data_type, is_nullable, column_key, extra
                   FROM information_schema.columns
                   WHERE table_schema = '{$name}'
                   ORDER BY table_name, ordinal_position";
        $colRes = $mysqli->query($colSql);
        if (!$colRes) {
            out(['ok' => false, 'error' => 'Failed to load columns.', 'message' => $mysqli->error], 500);
        }

        while ($c = $colRes->fetch_assoc()) {
            $tn = $c['table_name'];
            if (!isset($tables[$tn])) {
                $tables[$tn] = ['table' => $tn, 'columns' => [], 'columns_map' => []];
            }
            $tables[$tn]['columns'][] = [
                'name' => $c['column_name'],
                'type' => $c['data_type'],
                'nullable' => $c['is_nullable'] === 'YES',
                'key' => $c['column_key'],
                'extra' => $c['extra'],
            ];
            $tables[$tn]['columns_map'][$c['column_name']] = $c['data_type'];
        }

        out([
            'ok' => true,
            'action' => 'metadata',
            'db' => $name,
            'table_count' => count($tables),
            'tables' => array_values($tables),
        ]);
        break;

    case 'execture_query':
    case 'execute_query':
        $sql = trim((string) ($body['sql'] ?? ''));
        if ($sql === '') {
            out(['ok' => false, 'error' => 'Missing sql field.'], 400);
        }

        // Allow broader read/query forms including CTEs.
        if (!preg_match('/^\s*(WITH|SELECT|SHOW|DESCRIBE|EXPLAIN)\b/i', $sql)) {
            out(['ok' => false, 'error' => 'Query type is not allowed.'], 400);
        }

        // Keep destructive/writing queries blocked.
        if (preg_match('/;\s*\S+/', $sql) || preg_match('/\b(insert|update|delete|drop|alter|truncate|create|replace|grant|revoke)\b/i', $sql)) {
            out(['ok' => false, 'error' => 'Unsafe SQL detected.'], 400);
        }

        $startedAt = microtime(true);
        $res = $mysqli->query($sql);
        if (!$res) {
            out(['ok' => false, 'error' => 'Query failed.', 'message' => $mysqli->error], 400);
        }

        $rows = [];
        $affectedRows = 0;
        if ($res instanceof mysqli_result) {
            while ($row = $res->fetch_assoc()) {
                $rows[] = $row;
            }
        } else {
            $affectedRows = $mysqli->affected_rows;
        }

        out([
            'ok' => true,
            'action' => 'execture_query',
            'db' => $name,
            'row_count' => count($rows),
            'affected_rows' => $affectedRows,
            'elapsed_ms' => (int) round((microtime(true) - $startedAt) * 1000),
            'rows' => $rows,
        ]);
        break;

    default:
        out([
            'ok' => false,
            'error' => 'Invalid action. Use execture_query or metadata.',
        ], 400);
}

