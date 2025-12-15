/* assets/js/backend-generator.js */

let fields = [
    { name: "username", type: "VARCHAR(100)" },
    { name: "email", type: "VARCHAR(150)" },
    { name: "password", type: "VARCHAR(255)" },
    { name: "role", type: "VARCHAR(50)" },
];
let currentTab = "db";

// --- UI RENDERER ---
function renderFields() {
    const list = document.getElementById("fieldList");
    list.innerHTML = "";
    fields.forEach((f, i) => {
        let div = document.createElement("div");
        div.className = "field-row";
        div.innerHTML = `
            <input type="text" class="form-input" value="${
                f.name
            }" oninput="updateField(${i}, 'name', this.value)" placeholder="Col">
            <select class="form-select" onchange="updateField(${i}, 'type', this.value)">
                <option value="VARCHAR(255)" ${
                    f.type.includes("VARCHAR") ? "selected" : ""
                }>String</option>
                <option value="INT" ${
                    f.type.includes("INT") ? "selected" : ""
                }>Int</option>
                <option value="DECIMAL" ${
                    f.type.includes("DECIMAL") ? "selected" : ""
                }>Price</option>
                <option value="TEXT" ${
                    f.type.includes("TEXT") ? "selected" : ""
                }>Text</option>
            </select>
            <i class="ri-close-circle-fill" style="color:#ef4444; cursor:pointer; margin-top:8px;" onclick="removeField(${i})"></i>
        `;
        list.appendChild(div);
    });
}

// --- GENERATOR LOGIC ---

function getDbCode() {
    return `<?php
// db.php
$host = '${document.getElementById("dbHost").value}';
$db   = '${document.getElementById("dbName").value}';
$user = '${document.getElementById("dbUser").value}';
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die(json_encode(['error' => "Connection failed: " . $e->getMessage()]));
}
?>`;
}

function getAuthCode() {
    const table = document.getElementById("tableName").value;
    let passCol =
        fields.find((f) => f.name.includes("pass") || f.name.includes("pwd"))
            ?.name || "password";
    let idCol =
        fields.find((f) => ["email", "username"].includes(f.name))?.name ||
        fields[0].name;

    // 1. Build Validation Logic
    let validation = `
    // 1. Validate Required Fields
    $required = [${fields.map((f) => `'${f.name}'`).join(", ")}];
    foreach($required as $field) {
        if(empty($data[$field])) {
            echo json_encode(['error' => "$field is required"]); exit;
        }
    }`;

    // 2. Email Validation
    if (fields.some((f) => f.name === "email")) {
        validation += `
    
    // 2. Validate Email Format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => "Invalid email format"]); exit;
    }`;
    }

    // 3. Password Validation
    validation += `

    // 3. Validate Password Strength
    if (strlen($data['${passCol}']) < 6) {
        echo json_encode(['error' => "Password must be at least 6 characters"]); exit;
    }`;

    // 4. Build Insert Logic
    let cols = fields.map((f) => f.name);
    let vals = fields.map((f) =>
        f.name === passCol
            ? `password_hash($data['${f.name}'], PASSWORD_DEFAULT)`
            : `$data['${f.name}']`
    );

    return `<?php
// auth.php
require 'db.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$data = json_decode(file_get_contents("php://input"), true);

if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    ${validation}

    // 4. Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM ${table} WHERE ${idCol} = ?");
    $stmt->execute([$data['${idCol}']]);
    if ($stmt->fetch()) { echo json_encode(['error' => 'User already exists']); exit; }

    // 5. Insert User
    $sql = "INSERT INTO ${table} (${cols.join(", ")}) VALUES (${cols
        .map(() => "?")
        .join(", ")})";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([${vals.join(", ")}])) {
        echo json_encode(['success' => 'User created successfully']);
    } else {
        echo json_encode(['error' => 'Registration failed']);
    }
}

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if(empty($data['${idCol}']) || empty($data['${passCol}'])) {
        echo json_encode(['error' => 'Credentials required']); exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM ${table} WHERE ${idCol} = ?");
    $stmt->execute([$data['${idCol}']]);
    $user = $stmt->fetch();

    if ($user && password_verify($data['${passCol}'], $user['${passCol}'])) {
        echo json_encode(['success' => true, 'user_id' => $user['id']]);
    } else {
        echo json_encode(['error' => 'Invalid credentials']);
    }
}
?>`;
}

function getApiCode() {
    const table = document.getElementById("tableName").value;
    let cols = fields.map((f) => f.name).join(", ");
    let params = fields.map(() => "?").join(", ");
    let insertVars = fields.map((f) => `$data['${f.name}']`).join(", ");
    let updateSet = fields.map((f) => `${f.name}=?`).join(", ");

    return `<?php
// api.php
require 'db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM ${table} WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch() ?: ['error' => 'Not found']);
        } else {
            echo json_encode($pdo->query("SELECT * FROM ${table}")->fetchAll());
        }
        break;

    case 'POST':
        $stmt = $pdo->prepare("INSERT INTO ${table} (${cols}) VALUES (${params})");
        if($stmt->execute([${insertVars}])) {
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        } else {
            echo json_encode(['error' => 'Insert failed']);
        }
        break;

    case 'PUT':
        $id = $_GET['id'] ?? null;
        $stmt = $pdo->prepare("UPDATE ${table} SET ${updateSet} WHERE id=?");
        if($stmt->execute([${insertVars}, $id])) {
            echo json_encode(['success' => true]);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? null;
        $pdo->prepare("DELETE FROM ${table} WHERE id=?")->execute([$id]);
        echo json_encode(['success' => true]);
        break;
}
?>`;
}

// --- STATE MANAGEMENT ---
function switchTab(tab) {
    currentTab = tab;
    document
        .querySelectorAll(".tab-item")
        .forEach((t) => t.classList.remove("active"));
    event.target.classList.add("active");
    generateAll();
}

function generateAll() {
    const area = document.getElementById("codeArea");
    if (currentTab === "db") area.value = getDbCode();
    else if (currentTab === "auth") area.value = getAuthCode();
    else if (currentTab === "api") area.value = getApiCode();
}

function addField() {
    fields.push({ name: "new_col", type: "VARCHAR(255)" });
    renderFields();
    generateAll();
}
function removeField(i) {
    fields.splice(i, 1);
    renderFields();
    generateAll();
}
function updateField(i, k, v) {
    fields[i][k] = v;
    generateAll();
}

// Init
renderFields();
generateAll();
