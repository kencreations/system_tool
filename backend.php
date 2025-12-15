<?php $pageTitle = "Backend Generator"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { overflow: hidden; height: 100vh; display: flex; }
        .workspace { flex-grow: 1; background: #1e1e1e; display: flex; flex-direction: column; } 
        
        /* TABS */
        .editor-tabs { display: flex; background: #252526; border-bottom: 1px solid #333; }
        .tab-item { 
            padding: 10px 20px; color: #999; cursor: pointer; font-size: 13px; border-right: 1px solid #333; 
            display: flex; align-items: center; gap: 8px;
        }
        .tab-item.active { background: #1e1e1e; color: #fff; border-top: 2px solid var(--primary); }
        .tab-item i { font-size: 14px; color: #eab308; } 

        /* CODE AREA */
        .code-editor { 
            flex-grow: 1; 
            width: 100%; 
            background: #1e1e1e; 
            color: #d4d4d4; 
            border: none; 
            resize: none; 
            padding: 20px; 
            font-family: 'Consolas', 'Monaco', monospace; 
            font-size: 14px; 
            line-height: 1.6;
            outline: none;
            white-space: pre;
        }

        /* PROPERTIES PANEL */
        .properties-panel { width: 360px; background: #fff; border-left: 1px solid #e2e8f0; display: flex; flex-direction: column; }
        .panel-content { padding: 20px; overflow-y: auto; flex: 1; }
        .panel-header { padding: 15px 20px; border-bottom: 1px solid #e2e8f0; font-weight: 700; color: #1e293b; }
        
        .section-label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-top: 15px; margin-bottom: 8px; }
        .form-input, .form-select { width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; margin-bottom: 10px; }
        
        .field-row { display: flex; gap: 5px; margin-bottom: 5px; }
        .btn-add { background: #eff6ff; color: var(--primary); border: 1px solid #bfdbfe; width: 100%; padding: 8px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        <div class="editor-tabs">
            <div class="tab-item active" onclick="switchTab('db')"><i class="ri-database-2-line"></i> db.php</div>
            <div class="tab-item" onclick="switchTab('auth')"><i class="ri-shield-key-line"></i> auth.php</div>
            <div class="tab-item" onclick="switchTab('api')"><i class="ri-server-line"></i> api.php</div>
        </div>
        
        <textarea class="code-editor" id="codeArea" spellcheck="false"></textarea>
    </main>

    <aside class="properties-panel">
        <div class="panel-header">Backend Config</div>
        <div class="panel-content">
            <div class="section-label">Database</div>
            <input type="text" id="dbHost" class="form-input" value="localhost" placeholder="Host" oninput="generateAll()">
            <input type="text" id="dbName" class="form-input" value="my_app_db" placeholder="DB Name" oninput="generateAll()">
            <input type="text" id="dbUser" class="form-input" value="root" placeholder="User" oninput="generateAll()">
            
            <div class="section-label">Table Config</div>
            <input type="text" id="tableName" class="form-input" value="users" placeholder="Table Name" oninput="generateAll()">
            
            <div class="section-label">Fields</div>
            <div id="fieldList"></div>
            <button class="btn-add" onclick="addField()">+ Add Column</button>
        </div>
    </aside>

    <script src="assets/js/backend.js"></script>
</body>
</html>