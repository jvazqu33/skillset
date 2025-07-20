<?php
require 'db.php';


if (isset($_POST['add_skill'])) {
    $alumniID = $_POST['alumniID'];
    $skill = $_POST['skill'];
    $proficiency = $_POST['proficiency'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO skillset (alumniID, skill, proficiency, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$alumniID, $skill, $proficiency, $description]);

    header("Location: skillset.php"); 
    exit;
}


if (isset($_POST['update_skill'])) {
    $SID = $_POST['SID'];
    $alumniID = $_POST['alumniID'];
    $skill = $_POST['skill'];
    $proficiency = $_POST['proficiency'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("UPDATE skillset SET alumniID=?, skill=?, proficiency=?, description=? WHERE SID=?");
    $stmt->execute([$alumniID, $skill, $proficiency, $description, $SID]);

    header("Location: skillset.php");
    exit;
}


if (isset($_GET['delete'])) {
    $SID = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM skillset WHERE SID = ?");
    $stmt->execute([$SID]);

    header("Location: skillset.php");
    exit;
}


$stmt = $pdo->query("SELECT * FROM skillset ORDER BY SID");
$skills = $stmt->fetchAll(PDO::FETCH_ASSOC);


$editSkill = null;
if (isset($_GET['edit'])) {
    $SID = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM skillset WHERE SID = ?");
    $stmt->execute([$SID]);
    $editSkill = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Skillset Management</title>
    <style>
        
        
        table { border-collapse: collapse; width: 90%; margin: 20px auto; background: #fff; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #000; color: #ffb700; }
        form { max-width: 500px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 10px; }
        input, select, textarea, button { width: 95%; padding: 10px; margin-top: 10px; }
        button { background: #000; color: #ffb700; border: none; cursor: pointer; }
        button:hover { background: #ffb700; color: #000; }
        a { text-decoration: none; color: #0000EE; }
    </style>
</head>
<body>

<h1>Skillset Management</h1>

<table>
    <thead>
        <tr>
            <th>SID</th>
            <th>Alumni ID</th>
            <th>Skill</th>
            <th>Proficiency</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($skills as $skill): ?>
        <tr>
            <td><?= htmlspecialchars($skill['SID']) ?></td>
            <td><?= htmlspecialchars($skill['alumniID']) ?></td>
            <td><?= htmlspecialchars($skill['skill']) ?></td>
            <td><?= htmlspecialchars($skill['proficiency']) ?></td>
            <td><?= htmlspecialchars($skill['description']) ?></td>
            <td>
                <a href="?edit=<?= $skill['SID'] ?>">Edit</a> |
                <a href="?delete=<?= $skill['SID'] ?>" onclick="return confirm('Delete this skill?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2><?= $editSkill ? "Edit Skill (SID: {$editSkill['SID']})" : "Add New Skill" ?></h2>

<form method="post" action="skillset.php">
    <input type="hidden" name="SID" value="<?= $editSkill ? htmlspecialchars($editSkill['SID']) : '' ?>"/>

    <label>Alumni ID:</label>
    <input type="number" name="alumniID" required value="<?= $editSkill ? htmlspecialchars($editSkill['alumniID']) : '' ?>"/>

    <label>Skill:</label>
    <input type="text" name="skill" required value="<?= $editSkill ? htmlspecialchars($editSkill['skill']) : '' ?>"/>

    <label>Proficiency:</label>
    <select name="proficiency" required>
        <?php
        $levels = ['Basic', 'Intermed', 'Adv'];
        foreach ($levels as $level) {
            $selected = ($editSkill && $editSkill['proficiency'] === $level) ? "selected" : "";
            echo "<option value='$level' $selected>$level</option>";
        }
        ?>
    </select>

    <label>Description:</label>
    <textarea name="description"><?= $editSkill ? htmlspecialchars($editSkill['description']) : '' ?></textarea>

    <button type="submit" name="<?= $editSkill ? 'update_skill' : 'add_skill' ?>">
        <?= $editSkill ? 'Update Skill' : 'Add Skill' ?>
    </button>
</form>
<style>
    .ksu-back-link {
        display: inline-block;
        background-color: #FFC61E; 
        color: #000;
        font-weight: bold;
        padding: 10px 16px;
        text-decoration: none;
        border-radius: 4px;
        margin-top: 20px;
        transition: background-color 0.3s ease;
        box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
    }

    .ksu-back-link:hover {
        background-color: #e6b800;
        color: #111;
    }
</style>


<div style="text-align: center; margin: 30px 0;">
    <a href="index.php" class="ksu-back-link">&#8592;&nbsp;&nbsp;Back to Home</a>
</div>
</body>
</html>