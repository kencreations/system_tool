<?php $pageTitle = "Advanced Form Builder"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        /* LAYOUT */
        body { overflow: hidden; height: 100vh; display: flex; }
        .workspace { flex-grow: 1; background: #f1f5f9; display: flex; flex-direction: column; }
        
        /* HEADER STYLES (Fixed) */
        .workspace-header { 
            height: 60px; background: #fff; border-bottom: 1px solid #e2e8f0; 
            display: flex; align-items: center; justify-content: space-between; 
            padding: 0 20px; flex-shrink: 0;
        }
        .header-title { font-size: 16px; font-weight: 600; color: #1e293b; margin: 0; }
        .header-actions { display: flex; align-items: center; gap: 10px; }

        /* BUTTON STYLES (Explicitly Defined to prevent layout issues) */
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 8px 16px; border-radius: 6px; font-weight: 500; font-size: 13px;
            cursor: pointer; border: 1px solid transparent; transition: all 0.2s;
            text-decoration: none; line-height: 1.2;
        }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-primary:hover { background: #2563eb; }
        
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; }
        
        .btn-outline { background: #fff; border: 1px solid #cbd5e1; color: #475569; }
        .btn-outline:hover { border-color: #94a3b8; background: #f8fafc; }

        /* PREVIEW AREA */
        .preview-area { 
            flex-grow: 1; padding: 40px; overflow-y: auto; 
            display: flex; justify-content: center; align-items: flex-start;
        }
        
        .form-preview-card {
            background: #fff; width: 100%; max-width: 800px; padding: 40px; 
            border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid #cbd5e1;
        }

        /* UTILITY CLASSES */
        .flex-row { display: flex; flex-wrap: wrap; margin: 0 -10px; }
        .col { padding: 0 10px; margin-bottom: 15px; box-sizing: border-box; }
        
        .w-100 { width: 100%; } .w-75 { width: 75%; } .w-66 { width: 66.66%; }
        .w-50 { width: 50%; } .w-33 { width: 33.33%; } .w-25 { width: 25%; } .w-auto { width: auto; }

        /* Alignment Utilities */
        .align-start { display: flex; justify-content: flex-start; width: 100%; }
        .align-center { display: flex; justify-content: center; width: 100%; }
        .align-end { display: flex; justify-content: flex-end; width: 100%; }

        /* PROPERTIES PANEL */
        .properties-panel { width: 360px; background: #fff; border-left: 1px solid #cbd5e1; display: flex; flex-direction: column; }
        .panel-content { padding: 15px; overflow-y: auto; flex: 1; }
        
        .field-item { 
            background: #fff; border: 1px solid #e2e8f0; padding: 12px; border-radius: 8px; margin-bottom: 10px; 
            transition: border 0.2s; position: relative;
        }
        .field-item:hover { border-color: #94a3b8; }
        .field-item.is-btn { background: #f8fafc; border-left: 3px solid var(--primary); }
        
        .field-header { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 11px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; }
        
        .form-input-sm, .form-select-sm { 
            width: 100%; padding: 6px 8px; border: 1px solid #cbd5e1; border-radius: 4px; 
            font-size: 12px; color: #334155; margin-bottom: 6px; 
        }
        
        .controls-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }

        /* CODE MODAL */
        .code-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 100; align-items: center; justify-content: center; }
        .code-window { width: 85%; height: 85%; background: #1e1e1e; border-radius: 8px; display: flex; flex-direction: column; overflow: hidden; }
        .code-tabs { display: flex; background: #252526; border-bottom: 1px solid #333; }
        .code-tab { padding: 12px 20px; color: #999; cursor: pointer; font-size: 13px; border-right: 1px solid #333; }
        .code-tab.active { background: #1e1e1e; color: #fff; border-top: 2px solid var(--primary); }
        .code-content { flex: 1; padding: 20px; overflow: auto; color: #d4d4d4; font-family: 'Consolas', monospace; font-size: 13px; line-height: 1.6; white-space: pre; background: transparent; border: none; outline: none; resize: none; }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        <div class="workspace-header">
            <h3 class="header-title">Form Builder</h3>
            <div class="header-actions">
                <button onclick="resetForm()" class="btn btn-danger">Reset</button>
                <button onclick="toggleCode(true)" class="btn btn-primary">View Code</button>
            </div>
        </div>
        
        <div class="preview-area">
            <div class="form-preview-card">
                <h3 style="margin-top:0; border-bottom:1px solid #f1f5f9; padding-bottom:15px; margin-bottom:25px;">Form Preview</h3>
                <div id="formRenderArea"></div>
            </div>
        </div>
    </main>

    <aside class="properties-panel">
        <div style="padding:15px; border-bottom:1px solid #f1f5f9; font-weight:600; font-size:13px; color:#334155;">
            PROPERTIES
        </div>
        <div class="panel-content" id="componentList"></div>
        
        <div style="padding:20px; border-top:1px solid #e2e8f0; background:#f8fafc;">
            <div style="font-size:11px; font-weight:700; color:#94a3b8; margin-bottom:10px;">ADD COMPONENT</div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                <button class="btn btn-outline" onclick="addField('input')">+ Input</button>
                <button class="btn btn-outline" onclick="addField('textarea')">+ Textarea</button>
                <button class="btn btn-outline" onclick="addField('select')">+ Select</button>
                <button class="btn btn-outline" style="border-color:#bfdbfe; color:var(--primary);" onclick="addField('button')">+ Button</button>
            </div>
        </div>
    </aside>

    <div id="codeModal" class="code-modal">
        <div class="code-window">
            <div class="code-tabs">
                <div class="code-tab active" onclick="switchTab('html')">HTML Structure</div>
                <div class="code-tab" onclick="switchTab('js')">JS (AJAX)</div>
                <div class="code-tab" onclick="switchTab('php')">PHP (Backend)</div>
                <div style="margin-left:auto; padding:12px 20px; cursor:pointer; color:#ef4444;" onclick="toggleCode(false)">Close</div>
            </div>
            <textarea id="codeContent" class="code-content" readonly spellcheck="false"></textarea>
        </div>
    </div>

    <script src="assets/js/form_builder.js"></script>
</body>
</html>