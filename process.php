<?php
session_start();

if (!isset($_SESSION['entries'])) {
    $_SESSION['entries'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $action = $_POST['action'] ?? '';

    // PANG delete lang sa isang tao HEHEH
    if ($action === 'delete') {
        foreach ($_SESSION['entries'] as $key => $entry) {
            if ($entry['name'] == $name && $entry['email'] == $email) {
                unset($_SESSION['entries'][$key]); 
                echo json_encode(['success' => true, 'message' => "Entry deleted: $name ($email)"]);
                exit;
            }
        }
        echo json_encode(['success' => false, 'message' => "Entry not found: $name ($email)"]);
        exit;
    }

    // PANG DELETE NG LAHAT NG TAO SA MUNDO HAHAH!!
    if ($action === 'delete_all') {
        $_SESSION['entries'] = []; 
        echo json_encode(['success' => true, 'message' => "All entries deleted."]);
        exit;
    }

    // palabasin lahat ng ENTRIES!!
    if ($action === 'show_all') {
        echo json_encode(['entries' => $_SESSION['entries']]);
        exit;
    }

    // para lumabas yung old name to edit it.
    if ($action === 'edit') {
        $old_name = $_POST['old_name'];
        $old_email = $_POST['old_email'];
        $new_name = $name;
        $new_email = $email;

        // ITO YUNG PANG SEARCH NG ALREADY EXIST FILE PARA MA
        foreach ($_SESSION['entries'] as &$entry) {
            if ($entry['name'] == $old_name && $entry['email'] == $old_email) {
                $entry['name'] = $new_name; // PANG UPDATE NG NAME
                $entry['email'] = $new_email; // PANG UPDATE NG EMAIL
                echo json_encode(['success' => true, 'new_name' => $new_name, 'new_email' => $new_email, 'message' => "Entry updated to: $new_name ($new_email)"]);
                exit;
            }
        }
        echo json_encode(['success' => false, 'message' => "Failed to edit the entry. Entry not found."]);
        exit;
    }

    // pang check kung already exist naba!
    foreach ($_SESSION['entries'] as $entry) {
        if ($entry['name'] == $name || $entry['email'] == $email) {
            echo json_encode([
                'exists' => true,
                'message' => "Entry already exists: $name ($email)",
                'name' => $name,
                'email' => $email
            ]);
            exit;
        }
    }

    // pang dagdag ng bagong entries
    $_SESSION['entries'][] = ['name' => $name, 'email' => $email];
    echo json_encode([
        'exists' => false,
        'name' => $name,
        'email' => $email,
        'message' => "New entry added: $name ($email)"
    ]);
}