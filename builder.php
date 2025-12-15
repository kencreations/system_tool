<?php 
// builder.php
$pageTitle = "Web Builder - Framework Edition";
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
           BUILDER INTERFACE STYLES
           (These are specific to the tool, not the framework)
           ========================================= */
        
        body { overflow: hidden; } /* Prevent double scrollbars */
        
        /* Layout Engine */
        .builder-layout { display: flex; height: 100vh; width: 100vw; }
        
        /* Sidebar */
        .controls-panel {
            width: 380px; min-width: 380px; background: #fff; border-right: 1px solid #cbd5e1;
            display: flex; flex-direction: column; z-index: 10;
        }
        .panel-header, .panel-toolbar { padding: 20px; border-bottom: 1px solid #f1f5f9; flex-shrink: 0; }
        .panel-scroll-area { flex-grow: 1; overflow-y: auto; padding: 20px; }

        /* Preview Area */
        .preview-panel { flex-grow: 1; background: #f1f5f9; display: flex; flex-direction: column; }
        .preview-scroll-area { flex-grow: 1; overflow-y: auto; padding: 40px; }
        .preview-card {
            background: white; padding: 40px; border-radius: 8px; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); 
            max-width: 900px; margin: 0 auto; border: 1px solid #e2e8f0;
        }

        /* Sidebar Items */
        .field-item {
            background: #fff; padding: 15px; border-radius: 6px; margin-bottom: 12px;
            border: 1px solid #e2e8f0; position: relative;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .field-item:hover { border-color: #94a3b8; }
        .field-item.is-button { border-left: 4px solid var(--primary); background: #eff6ff; }

        /* Sort Controls */
        .sort-controls { display: flex; gap: 6px; align-items: center; }
        .sort-controls i { cursor: pointer; color: #94a3b8; font-size: 16px; }
        .sort-controls i:hover { color: var(--primary); }
        .delete-btn { color: var(--danger) !important; }

        /* --- MAC-STYLE CODE WINDOW --- */
        .code-window {
            background: #1e1e1e; border-radius: 12px; 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            overflow: hidden; font-family: 'Consolas', 'Monaco', monospace; 
            max-width: 900px; margin: 0 auto;
        }
        .window-header {
            background: #2d2d2d; padding: 12px 20px; display: flex; 
            justify-content: space-between; align-items: center; border-bottom: 1px solid #333;
        }
        .dots { display: flex; gap: 8px; }
        .dot { width: 12px; height: 12px; border-radius: 50%; }
        .dot.red { background: #ff5f56; }
        .dot.yellow { background: #ffbd2e; }
        .dot.green { background: #27c93f; }
        .window-title { color: #9ca3af; font-size: 13px; font-weight: 500; }
        
        .copy-btn {
            background: rgba(255,255,255,0.1); border: none; color: #fff; 
            padding: 5px 12px; border-radius: 4px; font-size: 12px; cursor: pointer; 
            display: flex; align-items: center; gap: 6px; transition: background 0.2s;
        }
        .copy-btn:hover { background: rgba(255,255,255,0.2); }
        
        .code-content { padding: 20px; color: #d4d4d4; margin: 0; overflow-x: auto; font-size: 14px; line-height: 1.5; }
        
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
            <p style="margin:5px 0 0; color:#64748b; font-size:0.875rem;">Framework Edition</p>
        </div>

        <div class="panel-toolbar">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <button onclick="addComponent('input')" class="btn btn-outline" style="background:#fff;">
                    <i class="ri-input-cursor-move"></i> Add Input
                </button>
                <button onclick="addComponent('button')" class="btn btn-outline" style="background:#eff6ff; color: var(--primary); border-color: #bfdbfe;">
                    <i class="ri-toggle-line"></i> Add Button
                </button>
            </div>
        </div>

        <div class="panel-scroll-area" id="fieldList"></div>
    </aside>

    <main class="preview-panel">
        <div style="padding: 20px 40px; display: flex; gap: 15px; border-bottom: 1px solid #e2e8f0; background: #fff;">
            <button onclick="switchTab('preview')" id="btn-preview" class="btn btn-primary">
                <i class="ri-eye-line"></i> Visual Preview
            </button>
            <button onclick="switchTab('code')" id="btn-code" class="btn btn-outline">
                <i class="ri-code-s-slash-line"></i> Source Code
            </button>
        </div>

        <div class="preview-scroll-area">
            <div id="tab-preview">
                <div class="preview-card">
                    <h3 style="margin-top:0; color:var(--dark); border-bottom:1px solid #f1f5f9; padding-bottom:15px; margin-bottom:25px;">Form Preview</h3>
                    <div id="formRenderArea"></div>
                </div>
            </div>

            <div id="tab-code" style="display: none;">
                <div class="code-window">
                    <div class="window-header">
                        <div class="dots"><span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span></div>
                        <span class="window-title">generated_form.html</span>
                        <button class="copy-btn" onclick="copyCode()"><i class="ri-file-copy-line"></i> Copy HTML</button>
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
        // Default "Push to End" example
        { category: 'button', label: 'Cancel', type: 'button', width: 'w-auto', style: 'btn-secondary', align: 'ml-auto' },
        { category: 'button', label: 'Sign In', type: 'submit', width: 'w-auto', style: 'btn-primary', align: '' }
    ];

    // --- 2. RENDER SIDEBAR ---
    function renderList() {
        const list = document.getElementById('fieldList');
        list.innerHTML = '';

        components.forEach((comp, index) => {
            const item = document.createElement('div');
            item.className = `field-item ${comp.category === 'button' ? 'is-button' : ''}`;
            
            // Sidebar Inputs Style (inline styles to keep sidebar independent)
            const inputStyle = "width:100%; padding:6px; font-size:13px; border:1px solid #cbd5e1; border-radius:4px; margin-bottom:8px;";
            
            // Header
            let html = `
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <span style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">${comp.category === 'button' ? 'BUTTON' : 'INPUT'} #${index + 1}</span>
                    <div class="sort-controls">
                        <i class="ri-arrow-up-s-fill" onclick="moveItem(${index}, -1)"></i>
                        <i class="ri-arrow-down-s-fill" onclick="moveItem(${index}, 1)"></i>
                        <i class="ri-delete-bin-line delete-btn" onclick="removeItem(${index})"></i>
                    </div>
                </div>
            `;

            // Width Options
            const widthOpts = `
                <option value="w-100" ${comp.width === 'w-100' ? 'selected' : ''}>Width: 100%</option>
                <option value="w-50" ${comp.width === 'w-50' ? 'selected' : ''}>Width: 50%</option>
                <option value="w-33" ${comp.width === 'w-33' ? 'selected' : ''}>Width: 33%</option>
                <option value="w-auto" ${comp.width === 'w-auto' ? 'selected' : ''}>Fit Content (Auto)</option>
            `;

            if (comp.category === 'input') {
                html += `
                    <input type="text" style="${inputStyle}" value="${comp.label}" oninput="updateItem(${index}, 'label', this.value)">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px; margin-bottom:8px;">
                        <select style="${inputStyle}" onchange="updateItem(${index}, 'type', this.value)">
                            <option value="text" ${comp.type === 'text' ? 'selected' : ''}>Text</option>
                            <option value="email" ${comp.type === 'email' ? 'selected' : ''}>Email</option>
                            <option value="password" ${comp.type === 'password' ? 'selected' : ''}>Password</option>
                        </select>
                        <select style="${inputStyle}" onchange="updateItem(${index}, 'width', this.value)">${widthOpts}</select>
                    </div>
                `;
            } else {
                html += `
                    <input type="text" style="${inputStyle}; font-weight:bold;" value="${comp.label}" oninput="updateItem(${index}, 'label', this.value)">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px; margin-bottom:8px;">
                        <select style="${inputStyle}" onchange="updateItem(${index}, 'style', this.value)">
                            <option value="btn-primary" ${comp.style === 'btn-primary' ? 'selected' : ''}>Blue</option>
                            <option value="btn-secondary" ${comp.style === 'btn-secondary' ? 'selected' : ''}>Gray</option>
                            <option value="btn-danger" ${comp.style === 'btn-danger' ? 'selected' : ''}>Red</option>
                            <option value="btn-outline" ${comp.style === 'btn-outline' ? 'selected' : ''}>Outline</option>
                        </select>
                        <select style="${inputStyle}" onchange="updateItem(${index}, 'width', this.value)">${widthOpts}</select>
                    </div>
                    <label style="font-size:11px; font-weight:bold; color:#64748b;">POSITION</label>
                    <select style="${inputStyle}" onchange="updateItem(${index}, 'align', this.value)">
                        <option value="" ${comp.align === '' ? 'selected' : ''}>Default (Next to prev)</option>
                        <option value="ml-auto" ${comp.align === 'ml-auto' ? 'selected' : ''}>Push to End (Margin)</option>
                        <option value="justify-center" ${comp.align === 'justify-center' ? 'selected' : ''}>Center in Box</option>
                        <option value="justify-end" ${comp.align === 'justify-end' ? 'selected' : ''}>Right in Box</option>
                        <option value="w-100" ${comp.align === 'w-100' ? 'selected' : ''}>Stretch Full</option>
                    </select>
                `;
            }
            
            item.innerHTML = html;
            list.appendChild(item);
        });
    }

    // --- 3. RENDER PREVIEW (Uses Framework Classes) ---
    function renderForm() {
        const renderArea = document.getElementById('formRenderArea');
        const codeOutput = document.getElementById('codeOutput');

        // We use classes from main.css: flex, flex-wrap, -mx-2, px-2
        let html = `<form>\n`;
        html += `  <div class="flex flex-wrap -mx-2">\n\n`;

        components.forEach(comp => {
            // Base wrapper class
            let wrapperClass = `${comp.width} px-2 mb-4`;
            
            if (comp.category === 'button') {
                wrapperClass += ` flex`; 
                
                // If "Push to End", apply ml-auto to the wrapper itself
                if (comp.align === 'ml-auto') {
                    wrapperClass += ` ml-auto`; 
                } 
                // If "Stretch", make the button full width
                else if (comp.align === 'w-100') {
                    // Wrapper is already handled by width option, we need button width later
                }
                // Normal Alignment (justify-center, justify-end)
                else if (comp.align) {
                    wrapperClass += ` ${comp.align}`;
                }
            }

            html += `    \n`;
            html += `    <div class="${wrapperClass}">\n`;
            
            if (comp.category === 'input') {
                html += `      <label class="form-label">${comp.label}</label>\n`;
                html += `      <input type="${comp.type}" class="form-control" placeholder="${comp.placeholder}">\n`;
            } else {
                let btnClass = `btn ${comp.style}`;
                if (comp.align === 'w-100') btnClass += ` w-100`;
                html += `      <button type="${comp.type}" class="${btnClass}">${comp.label}</button>\n`;
            }
            
            html += `    </div>\n\n`;
        });

        html += `  </div>\n`; 
        html += `</form>`;

        renderArea.innerHTML = html;
        codeOutput.textContent = html;
    }

    // --- 4. CONTROLLERS ---
    function addComponent(type) {
        if(type === 'input') {
            components.push({ category: 'input', label: 'Label', type: 'text', width: 'w-100', placeholder: '' });
        } else {
            components.push({ category: 'button', label: 'Submit', type: 'submit', width: 'w-auto', style: 'btn-primary', align: '' });
        }
        renderList(); renderForm();
        setTimeout(() => document.getElementById('fieldList').scrollTop = 9999, 50);
    }
    function removeItem(i) { components.splice(i, 1); renderList(); renderForm(); }
    function moveItem(i, dir) { 
        let n = i + dir;
        if (n >= 0 && n < components.length) { 
            [components[i], components[n]] = [components[n], components[i]]; 
            renderList(); renderForm(); 
        }
    }
    function updateItem(i, k, v) { components[i][k] = v; renderForm(); }

    function switchTab(t) {
        document.getElementById('tab-preview').style.display = t === 'preview' ? 'block' : 'none';
        document.getElementById('tab-code').style.display = t === 'code' ? 'block' : 'none';
        document.getElementById('btn-preview').className = t === 'preview' ? 'btn btn-primary' : 'btn btn-outline';
        document.getElementById('btn-code').className = t === 'code' ? 'btn btn-primary' : 'btn btn-outline';
    }
    
    function copyCode() {
        navigator.clipboard.writeText(document.getElementById('codeOutput').textContent);
        alert("Copied!");
    }

    // Init
    renderList(); renderForm();
</script>
</body>
</html>