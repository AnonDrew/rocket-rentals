<?php
function safe_query(&$db, $query, $types, ...$vars) {
    $safe_query = $db->prepare($query);
    $safe_query->bind_param($types, ...$vars);
    $safe_query->execute();
    return $safe_query->get_result();
}
