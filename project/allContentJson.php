<?php
// Imports
global $db;
require "db.php";

// Set proper JSON header
header('Content-Type: application/json');

$sql = "SELECT content.*, users.username 
        FROM content 
        JOIN users ON content.creator_id = users.id 
        WHERE content.status = 'approved'";

$stmt = $db->prepare($sql);
$stmt->execute();
$contents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Turn it into Json String
echo json_encode($contents);