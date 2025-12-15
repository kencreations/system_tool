<?php 
// builder.php
$pageTitle = "Web Builder - Professional Edition";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        /* --- 1. RESET & BASICS --- */
        * { box-sizing: border-box; }
        body { 
            margin: 0; padding: 0; 
            font-family: 'Inter', system-ui, -apple-system, sans-serif; 
            background: #f8fafc; color: #1e293b;
            height: 100vh; overflow: hidden; /* Prevent body scroll */
        }
        
        /* --- 2. LAYOUT ENGINE (Fixes Scrolling) --- */
        .builder-layout {
            display: flex; 
            height: 100vh;
            width: 100vw;
        }

        /* SIDEBAR (Left) */
        .controls-panel {
            width: 380px;
            min-width: 380px;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column; /* Vertical Stack */
            height: 100%;
            z-index: 10;
        }

        /* Fixed Header & Toolbar */
        .panel-header, .panel-toolbar {
            padding: 20px;
            border-bottom: 1px solid #f1f5f9;
            flex-shrink: 0; 
        }

        /* Scrollable List Area */
        .panel-scroll-area {
            flex-grow: 1; 
            overflow-y: auto; 
            padding: 20px;
        }

        /* PREVIEW (Right) */
        .preview-panel {
            flex-grow: 1;
            background: #f1f5f9;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .preview-scroll-area {
            flex-grow: 1;
            overflow-y: auto;
            padding: 40px;
        }

        /* --- 3. COMPONENT STYLING --- */
        .field-item {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: border-color 0.2s;
            position: relative;
        }
        .field-item:hover { border-color: #cbd5e1; }
        .field-item.is-button { border-left: 4px solid #3b82f6; background: #eff6ff; }

        /* Sort Arrows */
        .sort-controls { display: flex; gap: 4px; align-items: center; }
        .sort-controls i { 
            cursor: pointer; color: #94a3b8; font-size: 16px; transition: color 0.2s; 
        }
        .sort-controls i:hover { color: #3b82f6; }
        .delete-btn { color: #ef4444 !important; }

        /* --- 4. PREVIEW UTILITIES (CRITICAL FOR LAYOUT) --- */
        /* These ensure the preview works even without main.css */
        .w-100 { width: 100%; }
        .w-50  { width: 50%; }
        .w-33  { width: 33.333%; }
        
        .flex { display: flex; }
        .flex-wrap { flex-wrap: wrap; }
        .-mx-2 { margin-left: -0.5rem; margin-right: -0.5rem; }
        .px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .w-full { width: 100%; }

        .justify-start { justify-content: flex-start; }
        .justify-center { justify-content: center; }
        .justify-end { justify-content: flex-end; }
        
        /* Simulated Form Styles */
        .form-label { display: block; font-weight: 600; margin-bottom: 5px; font-size: 0.9rem; }
        .form-control {
            display: block; width: 100%; padding: 0.6rem 0.8rem; font-size: 0.95rem;
            color: #334155; background-color: #fff; border: 1px solid #cbd5e1; border-radius: 6px;
        }
        
        /* Button Styles */
        .btn {
            display: inline-block; padding: 0.6rem 1.2rem; font-weight: 600; text-align: center;
            border-radius: 6px; border: none; cursor: pointer; transition: opacity 0.2s;
        }
        .btn:hover { opacity: 0.9; }
        
        .btn-primary { background: #3b82f6; color: white; }
        .btn-secondary { background: #64748b; color: white; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-outline { background: transparent; border: 1px solid #cbd5e1; color: #334155; }

        /* --- 5. THE "AI STYLE" CODE WINDOW --- */
        .code-window {
            background: #1e1e1e; /* VS Code Dark */
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            font-family: 'Consolas', 'Monaco', monospace;
            max-width: 900px;
            margin: 0 auto;
        }

        .window-header {
            background: #2d2d2d;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
        }

        .dots { display: flex; gap: 8px; }
        .dot { width: 12px; height: 12px; border-radius: 50%; }
        .dot.red { background: #ff5f56; }
        .dot.yellow { background: #ffbd2e; }
        .dot.green { background: #27c93f; }

        .window-title { color: #9ca3af; font-size: 13px; font-weight: 500; }

        .copy-btn {
            background: rgba(255,255,255,0.1);
            border: none;
            color: #fff;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }
        .copy-btn:hover { background: rgba(255,255,255,0.2); }

        .code-content {
            padding: 20px;
            color: #d4d4d4;
            margin: 0;
            overflow-x: auto;
            font-size: 14px;
            line-height: 1.5;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .code-content::-webkit-scrollbar-thumb { background: #4b5563; }
    </style>
</head>
<body>

<div class="builder-layout">

    <aside class="controls-panel">
        <div class="panel-header">
            <h2 style="margin:0; font-size:1.25rem;">Web Builder</h2>
            <p style="margin:5px 0 0; color:#64748b; font-size:0.875rem;">Professional Edition</p>
        </div>

        <div class="panel-toolbar">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <button onclick="addComponent('input')" class="btn" style="background:#f1f5f9; color:#334155; border:1px solid #e2e8f0;">
                    <i class="ri-input-cursor-move"></i> Add Input
                </button>
                <button onclick="addComponent('button')" class="btn" style="background:#eff6ff; color:#3b82f6; border:1px solid #bfdbfe;">
                    <i class="ri-toggle-line"></i> Add Button
                </button>
            </div>
        </div>

        <div class="panel-scroll-area" id="fieldList">
            </div>
    </aside>

    <main class="preview-panel">
        
        <div style="padding: 20px 40px; display: flex; gap: 15px; border-bottom: 1px solid #e2e8f0; background: #fff;">
            <button onclick="switchTab('preview')" id="btn-preview" class="btn btn-primary" style="box-shadow: 0 2px 5px rgba(59,130,246,0.3);">
                <i class="ri-eye-line"></i> Visual Preview
            </button>
            <button onclick="switchTab('code')" id="btn-code" class="btn btn-outline" style="border:none;">
                <i class="ri-code-s-slash-line"></i> Source Code
            </button>
        </div>

        <div class="preview-scroll-area">
            
            <div id="tab-preview">
                <div style="background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); max-width: 800px; margin: 0 auto; border: 1px solid #e2e8f0;">
                    <h3 style="margin-top:0; color:#1e293b; border-bottom:1px solid #f1f5f9; padding-bottom:15px; margin-bottom:25px;">Form Preview</h3>
                    <div id="formRenderArea"></div>
                </div>
            </div>

            <div id="tab-code" style="display: none;">
                <div class="code-window">
                    <div class="window-header">
                        <div class="dots">
                            <span class="dot red"></span>
                            <span class="dot yellow"></span>
                            <span class="dot green"></span>
                        </div>
                        <span class="window-title">generated_form.html</span>
                        <button class="copy-btn" onclick="copyCode()">
                            <i class="ri-file-copy-line"></i> Copy HTML
                        </button>
                    </div>
                    <pre class="code-content"><code id="codeOutput"></code></pre>
                </div>
            </div>

        </div>
    </main>

</div>

<script>
    // --- 1. DATA STATE ---
    let components = [
        { category: 'input', label: 'Email Address', type: 'email', width: 'w-100', placeholder: 'name@email.com' },
        { category: 'input', label: 'Password', type: 'password', width: 'w-100', placeholder: '******' },
        { category: 'button', label: 'Sign In', type: 'submit', width: 'w-100', style: 'btn-primary', align: 'justify-center' }
    ];

    // --- 2. RENDER SIDEBAR CONTROLS ---
    function renderList() {
        const list = document.getElementById('fieldList');
        list.innerHTML = '';

        components.forEach((comp, index) => {
            const item = document.createElement('div');
            item.className = `field-item ${comp.category === 'button' ? 'is-button' : ''}`;
            
            // Header
            let headerHTML = `
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <span style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">
                        ${comp.category === 'button' ? 'BUTTON' : 'INPUT'} #${index + 1}
                    </span>
                    <div class="sort-controls">
                        <i class="ri-arrow-up-s-fill" onclick="moveItem(${index}, -1)"></i>
                        <i class="ri-arrow-down-s-fill" onclick="moveItem(${index}, 1)"></i>
                        <i class="ri-delete-bin-line delete-btn" onclick="removeItem(${index})"></i>
                    </div>
                </div>
            `;

            let bodyHTML = '';
            
            // Reusing common inline styles for sidebar inputs to keep it reliable
            const inputStyle = "width:100%; padding:6px; font-size:13px; border:1px solid #cbd5e1; border-radius:4px; margin-bottom:8px;";
            const selectStyle = "width:100%; padding:6px; font-size:13px; border:1px solid #cbd5e1; border-radius:4px;";

            if (comp.category === 'input') {
                bodyHTML = `
                    <input type="text" style="${inputStyle}" value="${comp.label}" oninput="updateItem(${index}, 'label', this.value)" placeholder="Label">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px; margin-bottom:8px;">
                        <select style="${selectStyle}" onchange="updateItem(${index}, 'type', this.value)">
                            <option value="text" ${comp.type === 'text' ? 'selected' : ''}>Text</option>
                            <option value="email" ${comp.type === 'email' ? 'selected' : ''}>Email</option>
                            <option value="password" ${comp.type === 'password' ? 'selected' : ''}>Password</option>
                            <option value="number" ${comp.type === 'number' ? 'selected' : ''}>Number</option>
                        </select>
                        <select style="${selectStyle}" onchange="updateItem(${index}, 'width', this.value)">
                            <option value="w-100" ${comp.width === 'w-100' ? 'selected' : ''}>Width: 100%</option>
                            <option value="w-50" ${comp.width === 'w-50' ? 'selected' : ''}>Width: 50%</option>
                            <option value="w-33" ${comp.width === 'w-33' ? 'selected' : ''}>Width: 33%</option>
                        </select>
                    </div>
                    <input type="text" style="${inputStyle}; margin-bottom:0;" value="${comp.placeholder}" oninput="updateItem(${index}, 'placeholder', this.value)" placeholder="Placeholder">
                `;
            } else {
                bodyHTML = `
                    <input type="text" style="${inputStyle}; font-weight:bold;" value="${comp.label}" oninput="updateItem(${index}, 'label', this.value)" placeholder="Button Text">
                    
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px; margin-bottom:8px;">
                        <select style="${selectStyle}" onchange="updateItem(${index}, 'style', this.value)">
                            <option value="btn-primary" ${comp.style === 'btn-primary' ? 'selected' : ''}>Blue</option>
                            <option value="btn-secondary" ${comp.style === 'btn-secondary' ? 'selected' : ''}>Gray</option>
                            <option value="btn-danger" ${comp.style === 'btn-danger' ? 'selected' : ''}>Red</option>
                            <option value="btn-outline" ${comp.style === 'btn-outline' ? 'selected' : ''}>Outline</option>
                        </select>
                        <select style="${selectStyle}" onchange="updateItem(${index}, 'width', this.value)">
                            <option value="w-100" ${comp.width === 'w-100' ? 'selected' : ''}>Block: 100%</option>
                            <option value="w-50" ${comp.width === 'w-50' ? 'selected' : ''}>Block: 50%</option>
                            <option value="w-33" ${comp.width === 'w-33' ? 'selected' : ''}>Block: 33%</option>
                        </select>
                    </div>

                    <label style="font-size:11px; font-weight:bold; color:#64748b; display:block; margin-bottom:4px;">ALIGNMENT</label>
                    <select style="${selectStyle}" onchange="updateItem(${index}, 'align', this.value)">
                        <option value="justify-start" ${comp.align === 'justify-start' ? 'selected' : ''}>Left</option>
                        <option value="justify-center" ${comp.align === 'justify-center' ? 'selected' : ''}>Center</option>
                        <option value="justify-end" ${comp.align === 'justify-end' ? 'selected' : ''}>Right</option>
                        <option value="stretch" ${comp.align === 'stretch' ? 'selected' : ''}>Stretch Full</option>
                    </select>
                `;
            }

            item.innerHTML = headerHTML + bodyHTML;
            list.appendChild(item);
        });
    }

    // --- 3. RENDER PREVIEW (Logic: Using CSS Classes) ---
    function renderForm() {
        const renderArea = document.getElementById('formRenderArea');
        const codeOutput = document.getElementById('codeOutput');

        // We use the CSS classes (w-50, flex, etc.)
        let html = `<form>\n`;
        html += `  <div class="flex flex-wrap -mx-2">\n\n`;

        components.forEach(comp => {
            // 1. Determine Wrapper Classes
            let wrapperClass = `${comp.width} px-2 mb-4`;
            
            // If it's a button, we add 'flex' and the alignment class to the Wrapper
            if (comp.category === 'button') {
                wrapperClass += ` flex`; 
                if(comp.align !== 'stretch') {
                    wrapperClass += ` ${comp.align}`; // e.g., justify-center
                }
            }

            html += `    \n`;
            html += `    <div class="${wrapperClass}">\n`;
            
            if (comp.category === 'input') {
                html += `      <label class="form-label">${comp.label}</label>\n`;
                html += `      <input type="${comp.type}" class="form-control" placeholder="${comp.placeholder}">\n`;
            } else {
                // Button Rendering
                let btnClass = `btn ${comp.style}`;
                if (comp.align === 'stretch') {
                    btnClass += ` w-full`; // Stretch mode needs w-full on the button itself
                }
                html += `      <button type="${comp.type}" class="${btnClass}">${comp.label}</button>\n`;
            }
            
            html += `    </div>\n\n`;
        });

        html += `  </div>\n`; // End Wrapper
        html += `</form>`;

        renderArea.innerHTML = html;
        codeOutput.textContent = html;
    }

    // --- 4. CONTROLLERS ---
    function addComponent(type) {
        if(type === 'input') {
            components.push({ category: 'input', label: 'New Input', type: 'text', width: 'w-100', placeholder: '' });
        } else {
            components.push({ category: 'button', label: 'Submit', type: 'submit', width: 'w-100', style: 'btn-primary', align: 'justify-center' });
        }
        renderList();
        renderForm();
        
        const sidebar = document.getElementById('fieldList');
        setTimeout(() => sidebar.scrollTop = sidebar.scrollHeight, 100);
    }

    function removeItem(index) {
        components.splice(index, 1);
        renderList();
        renderForm();
    }

    function moveItem(index, direction) {
        const newIndex = index + direction;
        if (newIndex >= 0 && newIndex < components.length) {
            [components[index], components[newIndex]] = [components[newIndex], components[index]];
            renderList();
            renderForm();
        }
    }

    function updateItem(index, key, value) {
        components[index][key] = value;
        renderForm();
    }

    function switchTab(tab) {
        const previewTab = document.getElementById('tab-preview');
        const codeTab = document.getElementById('tab-code');
        const btnPreview = document.getElementById('btn-preview');
        const btnCode = document.getElementById('btn-code');

        if(tab === 'preview') {
            previewTab.style.display = 'block';
            codeTab.style.display = 'none';
            btnPreview.className = 'btn btn-primary';
            btnPreview.style.boxShadow = '0 2px 5px rgba(59,130,246,0.3)';
            btnCode.className = 'btn btn-outline';
            btnCode.style.boxShadow = 'none';
        } else {
            previewTab.style.display = 'none';
            codeTab.style.display = 'block';
            btnCode.className = 'btn btn-primary';
            btnCode.style.boxShadow = '0 2px 5px rgba(59,130,246,0.3)';
            btnPreview.className = 'btn btn-outline';
            btnPreview.style.boxShadow = 'none';
        }
    }

    function copyCode() {
        const code = document.getElementById('codeOutput').textContent;
        navigator.clipboard.writeText(code);
        
        const btn = document.querySelector('.copy-btn');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = `<i class="ri-check-line"></i> Copied!`;
        setTimeout(() => btn.innerHTML = originalHTML, 2000);
    }

    // Init
    renderList();
    renderForm();
</script>

</body>
</html>