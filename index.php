<?php 
$pageTitle = "Web System Creator";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        /* =========================================
           APP LAYOUT (3-Pane System)
           ========================================= */
        body { overflow: hidden; height: 100vh; display: flex; }

        /* PANE 1: MAIN APP NAVIGATION (Left - EXPANDED) */
        .app-sidebar {
            width: 260px; /* Expanded Width */
            background: #1e293b; /* Dark Slate */
            display: flex; flex-direction: column; 
            padding: 20px 15px;
            z-index: 50;
            flex-shrink: 0;
            border-right: 1px solid #334155;
        }
        
        .app-brand {
            color: #fff; font-weight: bold; font-size: 18px; margin-bottom: 30px;
            display: flex; align-items: center; gap: 10px; padding-left: 10px;
        }

        .nav-category {
            font-size: 11px; text-transform: uppercase; letter-spacing: 1px;
            color: #64748b; margin-bottom: 10px; padding-left: 10px; font-weight: 700;
        }

        .nav-item {
            display: flex; align-items: center; gap: 12px;
            color: #cbd5e1; font-size: 14px; 
            padding: 10px 12px; border-radius: 6px;
            cursor: pointer; transition: all 0.2s;
            margin-bottom: 4px;
            text-decoration: none;
        }
        .nav-item:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .nav-item.active { background: var(--primary); color: #fff; box-shadow: 0 4px 12px rgba(59,130,246,0.3); }
        .nav-item i { font-size: 18px; }

        /* PANE 2: PREVIEW AREA (Center - Flexible) */
        .workspace {
            flex-grow: 1;
            background: #f1f5f9;
            display: flex; flex-direction: column;
            position: relative;
            min-width: 0; 
        }
        .workspace-header {
            height: 60px; background: #fff; border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between; padding: 0 20px;
            flex-shrink: 0;
        }
        .preview-scroll-area {
            flex-grow: 1; overflow-y: auto; padding: 40px; display: flex; justify-content: center;
        }
        .preview-card {
            background: white; padding: 40px; border-radius: 8px; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); 
            width: 100%; max-width: 800px; min-height: 400px;
            border: 1px solid #e2e8f0;
        }

        /* PANE 3: BUILDER CONTROLS (Right - Fixed Width) */
        .properties-panel {
            width: 350px; min-width: 350px;
            background: #fff; border-left: 1px solid #cbd5e1;
            display: flex; flex-direction: column;
            z-index: 40;
            flex-shrink: 0;
        }

        /* Builder Specific Styles */
        .panel-header { padding: 15px 20px; border-bottom: 1px solid #f1f5f9; font-weight: bold; font-size: 14px; color: #334155; display: flex; justify-content: space-between; align-items: center; }
        .panel-content { flex-grow: 1; overflow-y: auto; padding: 20px; }
        
        .field-item {
            background: #fff; padding: 12px; border-radius: 6px; margin-bottom: 10px;
            border: 1px solid #e2e8f0; font-size: 13px;
        }
        .field-item:hover { border-color: #94a3b8; }
        .field-item.is-button { border-left: 3px solid var(--primary); background: #f8fafc; }

        /* Code Window (Overlay Mode) */
        .code-modal {
            display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.8); z-index: 100;
            align-items: center; justify-content: center;
        }
        .code-window {
            width: 80%; height: 80%; background: #1e1e1e; border-radius: 8px; overflow: hidden;
            display: flex; flex-direction: column;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .code-content { flex-grow: 1; padding: 20px; color: #d4d4d4; overflow: auto; font-family: 'Consolas', monospace; margin: 0; }
        .window-header { background: #2d2d2d; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; }
        
    </style>
</head>
<body>

    <nav class="app-sidebar">
        <div class="app-brand">
            <i class="ri-code-box-line text-primary"></i> DevStudio
        </div>

        <div class="nav-category">Core Generators</div>
        
        <a href="#" class="nav-item active">
            <i class="ri-article-line"></i> <span>Form Builder</span>
        </a>
        <a href="#" class="nav-item">
            <i class="ri-layout-masonry-line"></i> <span>Card Positioning</span>
        </a>
        <a href="#" class="nav-item">
            <i class="ri-layout-left-line"></i> <span>Nav & Sidebar</span>
        </a>

        <div class="nav-category" style="margin-top: 20px;">Components</div>
        
        <a href="#" class="nav-item">
            <i class="ri-table-line"></i> <span>Tables</span>
        </a>
        <a href="#" class="nav-item">
            <i class="ri-notification-badge-line"></i> <span>Notifications</span>
        </a>
        <a href="#" class="nav-item">
            <i class="ri-alert-line"></i> <span>Alerts</span>
        </a>

        <div class="nav-item" style="margin-top: auto;">
            <i class="ri-settings-4-line"></i> <span>Settings</span>
        </div>
    </nav>

    <main class="workspace">
        <header class="workspace-header">
            <h3 style="margin:0; font-size:16px; font-weight: 600; color: #1e293b;">Form Builder Workspace</h3>
            <div class="flex items-center">
                <button onclick="toggleCode(true)" class="btn btn-outline" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                    <i class="ri-code-s-slash-line"></i> View Code
                </button>
                <button class="btn btn-primary ml-auto" style="margin-left: 10px; font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                    <i class="ri-save-line"></i> Save Project
                </button>
            </div>
        </header>

        <div class="preview-scroll-area">
            <div class="preview-card">
                <h2 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:15px; margin-bottom:20px; font-size:18px; color: #334155;">Preview</h2>
                <div id="formRenderArea"></div>
            </div>
        </div>

        <div id="codeModal" class="code-modal">
            <div class="code-window">
                <div class="window-header">
                    <span style="color:#9ca3af; font-size: 13px;">source_code.html</span>
                    <button onclick="toggleCode(false)" class="btn btn-danger" style="padding:4px 12px; font-size:12px;">Close</button>
                </div>
                <pre class="code-content"><code id="codeOutput"></code></pre>
            </div>
        </div>
    </main>

    <aside class="properties-panel">
        <div class="panel-header">
            <span>COMPONENTS</span>
            <span style="font-size: 10px; color: #94a3b8; font-weight: normal;">DRAG & DROP READY</span>
        </div>
        
        <div style="padding: 15px; border-bottom: 1px solid #f1f5f9; display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <button onclick="addComponent('input')" class="btn btn-outline" style="font-size:12px; background: #fff;">
                <i class="ri-input-cursor-move"></i> Input
            </button>
            <button onclick="addComponent('button')" class="btn btn-outline" style="font-size:12px; color:var(--primary); border-color:#bfdbfe; background: #eff6ff;">
                <i class="ri-toggle-line"></i> Button
            </button>
        </div>

        <div class="panel-content" id="fieldList"></div>
    </aside>

    <script>
        // --- 1. DATA STATE ---
        let components = [
            { category: 'input', label: 'Email Address', type: 'email', width: 'w-100', placeholder: 'name@email.com' },
            { category: 'input', label: 'Password', type: 'password', width: 'w-100', placeholder: '******' },
            { category: 'button', label: 'Cancel', type: 'button', width: 'w-auto', style: 'btn-secondary', align: 'ml-auto' },
            { category: 'button', label: 'Sign In', type: 'submit', width: 'w-auto', style: 'btn-primary', align: '' }
        ];

        // --- 2. RENDER RIGHT PANEL ---
        function renderList() {
            const list = document.getElementById('fieldList');
            list.innerHTML = '';

            components.forEach((comp, index) => {
                const item = document.createElement('div');
                item.className = `field-item ${comp.category === 'button' ? 'is-button' : ''}`;
                
                const inputStyle = "width:100%; padding:5px; font-size:12px; border:1px solid #cbd5e1; border-radius:4px; margin-bottom:6px; color: #1e293b;";
                const widthOpts = `
                    <option value="w-100" ${comp.width === 'w-100' ? 'selected' : ''}>100%</option>
                    <option value="w-50" ${comp.width === 'w-50' ? 'selected' : ''}>50%</option>
                    <option value="w-33" ${comp.width === 'w-33' ? 'selected' : ''}>33%</option>
                    <option value="w-auto" ${comp.width === 'w-auto' ? 'selected' : ''}>Auto</option>
                `;

                let html = `
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                        <span style="font-weight:bold; color:#64748b; font-size:10px;">${comp.category === 'button' ? 'BUTTON' : 'INPUT'} #${index + 1}</span>
                        <div>
                            <i class="ri-arrow-up-s-line" style="cursor:pointer; margin-right:5px; color:#94a3b8;" onclick="moveItem(${index}, -1)"></i>
                            <i class="ri-arrow-down-s-line" style="cursor:pointer; margin-right:5px; color:#94a3b8;" onclick="moveItem(${index}, 1)"></i>
                            <i class="ri-delete-bin-line" style="cursor:pointer; color:#ef4444;" onclick="removeItem(${index})"></i>
                        </div>
                    </div>
                `;

                if (comp.category === 'input') {
                    html += `
                        <input type="text" style="${inputStyle}" value="${comp.label}" oninput="updateItem(${index}, 'label', this.value)">
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:5px;">
                            <select style="${inputStyle}" onchange="updateItem(${index}, 'type', this.value)">
                                <option value="text" ${comp.type === 'text' ? 'selected' : ''}>Text</option>
                                <option value="email" ${comp.type === 'email' ? 'selected' : ''}>Email</option>
                                <option value="password" ${comp.type === 'password' ? 'selected' : ''}>Pass</option>
                                <option value="number" ${comp.type === 'number' ? 'selected' : ''}>Num</option>
                            </select>
                            <select style="${inputStyle}" onchange="updateItem(${index}, 'width', this.value)">${widthOpts}</select>
                        </div>
                    `;
                } else {
                    html += `
                        <input type="text" style="${inputStyle}" value="${comp.label}" oninput="updateItem(${index}, 'label', this.value)">
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:5px;">
                            <select style="${inputStyle}" onchange="updateItem(${index}, 'style', this.value)">
                                <option value="btn-primary" ${comp.style === 'btn-primary' ? 'selected' : ''}>Blue</option>
                                <option value="btn-secondary" ${comp.style === 'btn-secondary' ? 'selected' : ''}>Gray</option>
                                <option value="btn-danger" ${comp.style === 'btn-danger' ? 'selected' : ''}>Red</option>
                                <option value="btn-success" ${comp.style === 'btn-success' ? 'selected' : ''}>Green</option>
                                <option value="btn-outline" ${comp.style === 'btn-outline' ? 'selected' : ''}>Outline</option>
                            </select>
                            <select style="${inputStyle}" onchange="updateItem(${index}, 'width', this.value)">${widthOpts}</select>
                        </div>
                        <select style="${inputStyle}" onchange="updateItem(${index}, 'align', this.value)">
                            <option value="" ${comp.align === '' ? 'selected' : ''}>None</option>
                            <option value="ml-auto" ${comp.align === 'ml-auto' ? 'selected' : ''}>Push Right (Margin)</option>
                            <option value="justify-center" ${comp.align === 'justify-center' ? 'selected' : ''}>Center</option>
                        </select>
                    `;
                }
                
                item.innerHTML = html;
                list.appendChild(item);
            });
        }

        // --- 3. RENDER PREVIEW ---
        function renderForm() {
            const renderArea = document.getElementById('formRenderArea');
            const codeOutput = document.getElementById('codeOutput');
            let html = `<form>\n  <div class="flex flex-wrap -mx-2">\n`;

            components.forEach(comp => {
                let wrapperClass = `${comp.width} px-2 mb-4`;
                if (comp.category === 'button') {
                    wrapperClass += ` flex`; 
                    if (comp.align === 'ml-auto') wrapperClass += ` ml-auto`;
                    else if (comp.align) wrapperClass += ` ${comp.align}`;
                }

                html += `    <div class="${wrapperClass}">\n`;
                if (comp.category === 'input') {
                    html += `      <label class="form-label">${comp.label}</label>\n      <input type="${comp.type}" class="form-control" placeholder="${comp.placeholder}">\n`;
                } else {
                    html += `      <button type="${comp.type}" class="btn ${comp.style}">${comp.label}</button>\n`;
                }
                html += `    </div>\n`;
            });

            html += `  </div>\n</form>`;
            renderArea.innerHTML = html;
            codeOutput.textContent = html;
        }

        // --- 4. ACTIONS ---
        function addComponent(type) {
            if(type === 'input') components.push({ category: 'input', label: 'Label', type: 'text', width: 'w-100', placeholder: '' });
            else components.push({ category: 'button', label: 'Btn', type: 'button', width: 'w-auto', style: 'btn-primary', align: '' });
            renderList(); renderForm();
            setTimeout(() => { const list = document.getElementById('fieldList'); list.scrollTop = list.scrollHeight; }, 50);
        }
        function removeItem(i) { components.splice(i, 1); renderList(); renderForm(); }
        function moveItem(i, dir) { 
            let n = i + dir;
            if (n >= 0 && n < components.length) { [components[i], components[n]] = [components[n], components[i]]; renderList(); renderForm(); }
        }
        function updateItem(i, k, v) { components[i][k] = v; renderForm(); }
        function toggleCode(show) { document.getElementById('codeModal').style.display = show ? 'flex' : 'none'; }

        renderList(); renderForm();
    </script>
</body>
</html>