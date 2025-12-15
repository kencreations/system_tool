<?php 
// builder.php
$pageTitle = "Form Builder - Ultimate Customization";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        /* --- CORE LAYOUT --- */
        body { overflow: hidden; font-family: sans-serif; }
        .builder-layout { display: grid; grid-template-columns: 380px 1fr; height: 100vh; }
        
        /* Sidebar */
        .controls-panel { 
            background: #fff; border-right: 1px solid #e2e8f0; padding: 20px; 
            overflow-y: auto; display: flex; flex-direction: column; z-index: 10; 
        }
        
        /* Preview */
        .preview-panel { background: #f8fafc; padding: 40px; overflow-y: auto; }
        
        /* Field Item Card */
        .field-item {
            background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 12px;
            border: 1px solid #e2e8f0; box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            position: relative;
        }
        .field-item.is-button { border-left: 4px solid #3b82f6; background: #eff6ff; }

        /* Sort Controls */
        .sort-controls { display: flex; gap: 5px; }
        .sort-btn { cursor: pointer; color: #94a3b8; font-size: 1.2rem; }
        .sort-btn:hover { color: #3b82f6; }

        /* Layout Utilities for Preview */
        .w-100 { width: 100%; }
        .w-50  { width: 50%; }
        .w-33  { width: 33.33%; }
        .w-25  { width: 25%; }
        
        /* Button Styles (Mocking your main.css if missing) */
        .btn-primary { background-color: var(--primary, #3b82f6); color: white; }
        .btn-secondary { background-color: var(--secondary, #64748b); color: white; }
        .btn-danger { background-color: #ef4444; color: white; }
        .btn-success { background-color: #22c55e; color: white; }
        .btn-outline { background-color: transparent; border: 1px solid #cbd5e1; color: #333; }
    </style>
</head>
<body>

<div class="builder-layout">

    <aside class="controls-panel">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-dark"><i class="ri-hammer-line text-primary"></i> Builder</h2>
            <a href="index.php" class="text-sm text-secondary">Exit</a>
        </div>

        <div class="flex gap-2 mb-4 border-b pb-4">
            <button onclick="addComponent('input')" class="btn w-full bg-light text-dark border border-gray-300 text-sm py-2">
                + Input
            </button>
            <button onclick="addComponent('button')" class="btn w-full bg-blue-50 text-primary border border-blue-200 text-sm py-2">
                + Button
            </button>
        </div>

        <div id="fieldList" class="flex-1"></div>
    </aside>

    <main class="preview-panel">
        <div class="flex justify-center gap-4 mb-8">
            <button onclick="switchTab('preview')" id="btn-preview" class="px-4 py-2 rounded font-bold bg-white text-primary shadow-sm border border-primary">Visual Preview</button>
            <button onclick="switchTab('code')" id="btn-code" class="px-4 py-2 rounded font-bold bg-white text-secondary shadow-sm border border-gray-200">Source Code</button>
        </div>

        <div id="tab-preview">
            <div class="card shadow-lg mx-auto bg-white rounded-lg border border-gray-200 p-8" style="max-width: 900px;">
                <h3 class="text-lg font-bold text-dark mb-6 border-b pb-2">Form Preview</h3>
                <div id="formRenderArea"></div>
            </div>
        </div>

        <div id="tab-code" class="hidden" style="max-width: 900px; margin: 0 auto;">
            <div class="relative bg-dark rounded-lg overflow-hidden shadow-lg">
                <div class="flex justify-between items-center bg-gray-800 px-4 py-2 border-b border-gray-700">
                    <span class="text-gray-400 text-sm">HTML Output</span>
                    <button onclick="copyCode()" class="text-white text-xs bg-primary px-2 py-1 rounded">Copy</button>
                </div>
                <pre class="p-4 text-white text-sm overflow-auto" style="height: 400px;"><code id="codeOutput"></code></pre>
            </div>
        </div>
    </main>

</div>

<script>
    // --- 1. DATA STATE ---
    // 'category' determines if it's an input or a button
    let components = [
        { category: 'input', label: 'Email Address', type: 'email', width: 'w-100', placeholder: 'name@email.com' },
        { category: 'input', label: 'Password', type: 'password', width: 'w-100', placeholder: '******' },
        { category: 'button', label: 'Login', type: 'submit', width: 'w-50', style: 'btn-primary' },
        { category: 'button', label: 'Cancel', type: 'button', width: 'w-50', style: 'btn-outline' }
    ];

    // --- 2. RENDER CONTROLS (Sidebar) ---
    function renderList() {
        const list = document.getElementById('fieldList');
        list.innerHTML = '';

        components.forEach((comp, index) => {
            const item = document.createElement('div');
            item.className = `field-item ${comp.category === 'button' ? 'is-button' : ''}`;
            
            // Header (Label + Move/Delete Controls)
            let headerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <span class="font-bold text-xs text-secondary uppercase">
                        ${comp.category === 'button' ? 'BUTTON' : 'INPUT'} #${index + 1}
                    </span>
                    <div class="sort-controls">
                        <i class="ri-arrow-up-s-line sort-btn" onclick="moveItem(${index}, -1)"></i>
                        <i class="ri-arrow-down-s-line sort-btn" onclick="moveItem(${index}, 1)"></i>
                        <i class="ri-close-circle-line text-danger cursor-pointer ml-2" onclick="removeItem(${index})"></i>
                    </div>
                </div>
            `;

            // Dynamic Body based on type
            let bodyHTML = '';
            
            if (comp.category === 'input') {
                // INPUT CONTROLS
                bodyHTML = `
                    <input type="text" class="form-control mb-2 text-sm" value="${comp.label}" oninput="updateItem(${index}, 'label', this.value)" placeholder="Label">
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <select class="form-control text-sm" onchange="updateItem(${index}, 'type', this.value)">
                            <option value="text" ${comp.type === 'text' ? 'selected' : ''}>Text</option>
                            <option value="email" ${comp.type === 'email' ? 'selected' : ''}>Email</option>
                            <option value="password" ${comp.type === 'password' ? 'selected' : ''}>Password</option>
                            <option value="number" ${comp.type === 'number' ? 'selected' : ''}>Number</option>
                            <option value="date" ${comp.type === 'date' ? 'selected' : ''}>Date</option>
                        </select>
                        <select class="form-control text-sm" onchange="updateItem(${index}, 'width', this.value)">
                            <option value="w-100" ${comp.width === 'w-100' ? 'selected' : ''}>100% Width</option>
                            <option value="w-50" ${comp.width === 'w-50' ? 'selected' : ''}>50% Width</option>
                            <option value="w-33" ${comp.width === 'w-33' ? 'selected' : ''}>33% Width</option>
                        </select>
                    </div>
                    <input type="text" class="form-control text-sm" value="${comp.placeholder}" oninput="updateItem(${index}, 'placeholder', this.value)" placeholder="Placeholder">
                `;
            } else {
                // BUTTON CONTROLS
                bodyHTML = `
                    <input type="text" class="form-control mb-2 text-sm font-bold" value="${comp.label}" oninput="updateItem(${index}, 'label', this.value)" placeholder="Button Text">
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <select class="form-control text-sm" onchange="updateItem(${index}, 'style', this.value)">
                            <option value="btn-primary" ${comp.style === 'btn-primary' ? 'selected' : ''}>Blue (Primary)</option>
                            <option value="btn-secondary" ${comp.style === 'btn-secondary' ? 'selected' : ''}>Gray (Secondary)</option>
                            <option value="btn-danger" ${comp.style === 'btn-danger' ? 'selected' : ''}>Red (Danger)</option>
                            <option value="btn-success" ${comp.style === 'btn-success' ? 'selected' : ''}>Green (Success)</option>
                            <option value="btn-outline" ${comp.style === 'btn-outline' ? 'selected' : ''}>Outline (White)</option>
                        </select>
                        <select class="form-control text-sm" onchange="updateItem(${index}, 'width', this.value)">
                            <option value="w-100" ${comp.width === 'w-100' ? 'selected' : ''}>100% Width</option>
                            <option value="w-50" ${comp.width === 'w-50' ? 'selected' : ''}>50% Width</option>
                            <option value="w-auto" ${comp.width === 'w-auto' ? 'selected' : ''}>Auto Width</option>
                        </select>
                    </div>
                `;
            }

            item.innerHTML = headerHTML + bodyHTML;
            list.appendChild(item);
        });
    }

    // --- 3. RENDER PREVIEW (Flexbox Logic) ---
    function renderForm() {
        const renderArea = document.getElementById('formRenderArea');
        const codeOutput = document.getElementById('codeOutput');

        let html = `<form>\n`;
        html += `  <div class="flex flex-wrap -mx-2">\n\n`; // Wrapper

        components.forEach(comp => {
            // Common wrapper div for spacing
            html += `    <div class="${comp.width} px-2 mb-4">\n`;
            
            if (comp.category === 'input') {
                html += `      <label class="block font-bold text-sm mb-1">${comp.label}</label>\n`;
                html += `      <input type="${comp.type}" class="form-control w-full" placeholder="${comp.placeholder}">\n`;
            } else {
                // Render Button
                // w-full on button forces it to fill the column width
                html += `      <button type="${comp.type}" class="btn ${comp.style} w-full">${comp.label}</button>\n`;
            }
            
            html += `    </div>\n\n`;
        });

        html += `  </div>\n`; // End Wrapper
        html += `</form>`;

        renderArea.innerHTML = html;
        codeOutput.textContent = html;
    }

    // --- 4. LOGIC HANDLERS ---
    
    function addComponent(type) {
        if(type === 'input') {
            components.push({ category: 'input', label: 'New Input', type: 'text', width: 'w-100', placeholder: '' });
        } else {
            components.push({ category: 'button', label: 'Submit', type: 'submit', width: 'w-100', style: 'btn-primary' });
        }
        renderList();
        renderForm();
    }

    function removeItem(index) {
        components.splice(index, 1);
        renderList();
        renderForm();
    }

    function moveItem(index, direction) {
        const newIndex = index + direction;
        // Bounds check
        if (newIndex >= 0 && newIndex < components.length) {
            // Swap array elements
            [components[index], components[newIndex]] = [components[newIndex], components[index]];
            renderList();
            renderForm();
        }
    }

    function updateItem(index, key, value) {
        components[index][key] = value;
        renderForm();
    }

    // Tabs & Copy
    function switchTab(tab) {
        document.getElementById('tab-preview').style.display = tab === 'preview' ? 'block' : 'none';
        document.getElementById('tab-code').style.display = tab === 'code' ? 'block' : 'none';
    }

    function copyCode() {
        navigator.clipboard.writeText(document.getElementById('codeOutput').textContent);
        alert('Copied!');
    }

    // Init
    renderList();
    renderForm();
</script>

</body>
</html>