<?php
require_once 'includes/database.php';
$tables = [
    'marriage_permits', 'family_visits', 'tourism_visits', 'business_visits', 
    'labor_requests', 'followup_requests', 'profession_changes', 
    'civil_affairs_requests', 'recruitment_requests'
];
$output = "";
foreach ($tables as $table) {
    $output .= "--- Table: $table ---\n";
    try {
        $stmt = $pdo->query("DESCRIBE `$table` ");
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($cols as $col) {
            $output .= "  {$col['Field']} ({$col['Type']})\n";
        }
    } catch (Exception $e) {
        $output .= "  Error: " . $e->getMessage() . "\n";
    }
}
file_put_contents('tmp_schema_output.txt', $output);
echo "Done\n";
