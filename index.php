<?php
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

// Add Task
if (isset($_POST['task']) && $_POST['task'] !== '') {
    $_SESSION['tasks'][] = ['text' => $_POST['task'], 'done' => false];
}

// Toggle Complete
if (isset($_GET['toggle'])) {
    $index = (int)$_GET['toggle'];
    $_SESSION['tasks'][$index]['done'] = !$_SESSION['tasks'][$index]['done'];
}

// Delete Task
if (isset($_GET['delete'])) {
    $index = (int)$_GET['delete'];
    array_splice($_SESSION['tasks'], $index, 1);
}

// Clear All Completed Tasks
if (isset($_POST['clear_completed'])) {
    $_SESSION['tasks'] = array_filter($_SESSION['tasks'], function ($task) {
        return !$task['done']; // keep only not-done tasks
    });
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="text-center mb-4">📝 To-Do List</h1>

    <!-- Add Task -->
    <form method="post" class="d-flex mb-3">
        <input type="text" name="task" class="form-control me-2" placeholder="Add a new task..." required>
        <button type="submit" class="btn btn-success">Add</button>
    </form>

    <!-- Clear Completed -->
    <form method="post" class="mb-4">
        <button type="submit" name="clear_completed" class="btn btn-danger">Clear All Completed</button>
    </form>

    <!-- Task List -->
    <ul class="list-group">
        <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center
                <?= $task['done'] ? 'list-group-item-secondary' : '' ?>">
                <span style="<?= $task['done'] ? 'text-decoration: line-through;' : '' ?>">
                    <?= htmlspecialchars($task['text']) ?>
                </span>
                <div>
                    <a href="?toggle=<?= $index ?>" class="btn btn-sm btn-outline-primary me-2">
                        <?= $task['done'] ? 'Undo' : 'Done' ?>
                    </a>
                    <a href="?delete=<?= $index ?>" class="btn btn-sm btn-outline-danger">Delete</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>
