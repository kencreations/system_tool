<?php $pageTitle = "Card Layout Builder"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        /* PAGE SPECIFIC STYLES */
        body { overflow: hidden; height: 100vh; display: flex; }

        /* Workspace */
        .workspace { flex-grow: 1; background: #f8fafc; display: flex; flex-direction: column; position: relative; min-width: 0; }
        .workspace-header { height: 60px; background: #fff; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; flex-shrink: 0; }
        .preview-scroll-area { flex-grow: 1; overflow-y: auto; padding: 40px; }
        
        /* Dashboard Container Guide */
        .dashboard-container { 
            width: 100%; max-width: 1200px; margin: 0 auto; min-height: 600px; 
            border: 2px dashed #e2e8f0; border-radius: 12px; padding: 20px; 
        }

        /* Right Panel */
        .properties-panel { width: 350px; min-width: 350px; background: #fff; border-left: 1px solid #cbd5e1; display: flex; flex-direction: column; z-index: 40; flex-shrink: 0; }
        .panel-header { padding: 15px 20px; border-bottom: 1px solid #f1f5f9; font-weight: bold; font-size: 14px; color: #334155; display: flex; justify-content: space-between; align-items: center; }
        .panel-content { flex-grow: 1; overflow-y: auto; padding: 20px; }
        
        .field-item { background: #fff; padding: 12px; border-radius: 6px; margin-bottom: 10px; border: 1px solid #e2e8f0; font-size: 13px; position: relative; }
        .field-item:hover { border-color: #94a3b8; }
        .field-item.is-stat { border-left: 3px solid #10b981; background: #f0fdf4; }
        .field-item.is-content { border-left: 3px solid #3b82f6; background: #eff6ff; }

        /* Code Window */
        .code-modal { display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 100; align-items: center; justify-content: center; }
        .code-window { width: 80%; height: 80%; background: #1e1e1e; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
        .code-content { flex-grow: 1; padding: 20px; color: #d4d4d4; overflow: auto; font-family: 'Consolas', monospace; margin: 0; }
        .window-header { background: #2d2d2d; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; }
        
        /* Color Input Style */
        input[type="color"] {
            -webkit-appearance: none; border: none; width: 30px; height: 30px; cursor: pointer; padding: 0; background: none;
        }
        input[type="color"]::-webkit-color-swatch-wrapper { padding: 0; }
        input[type="color"]::-webkit-color-swatch { border: 1px solid #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        <header class="workspace-header">
            <h3 style="margin:0; font-size:16px; font-weight: 600; color: #1e293b;">Dashboard Layout Builder</h3>
            <div class="flex items-center">
                <button onclick="toggleCode(true)" class="btn btn-outline" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                    <i class="ri-code-s-slash-line"></i> View Code
                </button>
                <button class="btn btn-primary ml-auto" style="margin-left: 10px; font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                    <i class="ri-save-line"></i> Save Layout
                </button>
            </div>
        </header>

        <div class="preview-scroll-area">
            <div id="dashboardRenderArea" class="dashboard-container"></div>
        </div>

        <div id="codeModal" class="code-modal">
            <div class="code-window">
                <div class="window-header">
                    <span style="color:#9ca3af; font-size: 13px;">dashboard.html</span>
                    <button onclick="toggleCode(false)" class="btn btn-danger" style="padding:4px 12px; font-size:12px;">Close</button>
                </div>
                <pre class="code-content"><code id="codeOutput"></code></pre>
            </div>
        </div>
    </main>

    <aside class="properties-panel">
        <div class="panel-header">
            <span>DASHBOARD WIDGETS</span>
            <span style="font-size: 10px; color: #94a3b8; font-weight: normal;">ADD ITEMS</span>
        </div>
        
        <div style="padding: 15px; border-bottom: 1px solid #f1f5f9; display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <button onclick="addCard('stat')" class="btn btn-outline" style="font-size:12px; background: #f0fdf4; border-color: #bbf7d0; color: #15803d;">
                <i class="ri-bar-chart-fill"></i> Stat Card
            </button>
            <button onclick="addCard('content')" class="btn btn-outline" style="font-size:12px; background: #eff6ff; border-color: #bfdbfe; color: #1d4ed8;">
                <i class="ri-file-text-line"></i> Content Card
            </button>
        </div>

        <div class="panel-content" id="cardList"></div>
    </aside>

    <script>
    // --- 1. DATA STATE ---
    let cards = [
        // Added 'iconStyle' property to control the background box
        { type: 'stat', layout: 'layout-classic', iconStyle: 'soft', title: 'Total Revenue', value: '$54,200', icon: 'ri-wallet-3-fill', bgColor: '#3b82f6', width: 'w-25' },
        { type: 'stat', layout: 'layout-classic', iconStyle: 'transparent', title: 'Active Users', value: '1,240', icon: 'ri-user-smile-fill', bgColor: '#ffffff', width: 'w-25' },
        { type: 'stat', layout: 'layout-classic', iconStyle: 'soft', title: 'Bounce Rate', value: '42.5%', icon: 'ri-pulse-fill', bgColor: '#ffffff', width: 'w-25' },
        { type: 'stat', layout: 'layout-minimal', iconStyle: 'transparent', title: 'Growth', value: '+12%', icon: 'ri-arrow-right-up-line', bgColor: '#ffffff', width: 'w-25' },
        
        { type: 'content', title: 'Performance Overview', height: 'min-h-300', width: 'w-66', bgColor: '#ffffff' },
        { type: 'content', title: 'Server Status', height: 'min-h-300', width: 'w-33', bgColor: '#ffffff' }
    ];

    // --- HELPER: TEXT CONTRAST ---
    function getContrastColor(hexColor) {
        const hex = hexColor.replace('#', '');
        const r = parseInt(hex.substr(0, 2), 16);
        const g = parseInt(hex.substr(2, 2), 16);
        const b = parseInt(hex.substr(4, 2), 16);
        const yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;
        return (yiq >= 128) ? '#0f172a' : '#ffffff';
    }

    // --- HELPER: ICON STYLES ---
    function getIconStyles(hexColor, layout, iconStyle) {
        // 1. If Layout is Minimal or User chose Transparent, remove BG
        if (layout === 'layout-minimal' || iconStyle === 'transparent') {
            return `background: transparent; color: inherit; width: auto; height: auto; padding: 0;`; 
        }

        // 2. Otherwise, use the "Soft Box" style
        let contrast = getContrastColor(hexColor);
        if (contrast === '#ffffff') {
            // Dark Card: Icon box is slightly lighter transparency
            return `background: rgba(255,255,255,0.2); color: #fff;`;
        } else {
            // Light Card: Icon box is a soft gray/tint
            return `background: #f1f5f9; color: ${hexColor === '#ffffff' ? '#3b82f6' : hexColor};`;
        }
    }

    // --- 2. RENDER LIST (Sidebar) ---
    function renderList() {
        const list = document.getElementById('cardList');
        list.innerHTML = '';

        cards.forEach((card, index) => {
            const item = document.createElement('div');
            item.className = `field-item ${card.type === 'stat' ? 'is-stat' : 'is-content'}`;
            
            const inputStyle = "width:100%; padding:5px; font-size:12px; border:1px solid #cbd5e1; border-radius:4px; margin-bottom:6px; color: #1e293b;";
            const widthOpts = `
                <option value="w-100" ${card.width === 'w-100' ? 'selected' : ''}>100%</option>
                <option value="w-75" ${card.width === 'w-75' ? 'selected' : ''}>75%</option>
                <option value="w-66" ${card.width === 'w-66' ? 'selected' : ''}>66%</option>
                <option value="w-50" ${card.width === 'w-50' ? 'selected' : ''}>50%</option>
                <option value="w-33" ${card.width === 'w-33' ? 'selected' : ''}>33%</option>
                <option value="w-25" ${card.width === 'w-25' ? 'selected' : ''}>25%</option>
            `;

            let html = `
                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <input type="color" value="${card.bgColor}" oninput="updateItem(${index}, 'bgColor', this.value)" style="border:none; width:24px; height:24px; cursor:pointer; border-radius:4px;">
                        <span style="font-weight:bold; color:#64748b; font-size:10px;">${card.type === 'stat' ? 'STAT CARD' : 'CONTENT'}</span>
                    </div>
                    <div>
                        <i class="ri-delete-bin-line" style="cursor:pointer; color:#ef4444;" onclick="removeItem(${index})"></i>
                    </div>
                </div>
            `;

            if (card.type === 'stat') {
                html += `
                    <input type="text" style="${inputStyle}" value="${card.title}" oninput="updateItem(${index}, 'title', this.value)" placeholder="Label">
                    <input type="text" style="${inputStyle}" value="${card.value}" oninput="updateItem(${index}, 'value', this.value)" placeholder="Value">
                    
                    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:5px; margin-bottom:6px;">
                        <input type="text" style="${inputStyle}; margin-bottom:0;" value="${card.icon}" oninput="updateItem(${index}, 'icon', this.value)" placeholder="Icon Class">
                        <select style="${inputStyle}; margin-bottom:0;" onchange="updateItem(${index}, 'iconStyle', this.value)">
                            <option value="soft" ${card.iconStyle === 'soft' ? 'selected' : ''}>Box</option>
                            <option value="transparent" ${card.iconStyle === 'transparent' ? 'selected' : ''}>None</option>
                        </select>
                    </div>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:5px;">
                         <select style="${inputStyle}" onchange="updateItem(${index}, 'width', this.value)">${widthOpts}</select>
                         <select style="${inputStyle}" onchange="updateItem(${index}, 'layout', this.value)">
                            <option value="layout-classic" ${card.layout === 'layout-classic' ? 'selected' : ''}>Classic</option>
                            <option value="layout-stacked" ${card.layout === 'layout-stacked' ? 'selected' : ''}>Stacked</option>
                            <option value="layout-minimal" ${card.layout === 'layout-minimal' ? 'selected' : ''}>Minimal</option>
                         </select>
                    </div>
                `;
            } else {
                html += `
                    <input type="text" style="${inputStyle}" value="${card.title}" oninput="updateItem(${index}, 'title', this.value)">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:5px;">
                         <select style="${inputStyle}" onchange="updateItem(${index}, 'width', this.value)">${widthOpts}</select>
                         <select style="${inputStyle}" onchange="updateItem(${index}, 'height', this.value)">
                            <option value="" ${card.height === '' ? 'selected' : ''}>Auto H</option>
                            <option value="min-h-300" ${card.height === 'min-h-300' ? 'selected' : ''}>Tall</option>
                         </select>
                    </div>
                `;
            }
            item.innerHTML = html;
            list.appendChild(item);
        });
    }

    // --- 3. RENDER DASHBOARD (Center) ---
    function renderDashboard() {
        const renderArea = document.getElementById('dashboardRenderArea');
        const codeOutput = document.getElementById('codeOutput');

        let html = `<div class="flex flex-wrap -mx-3">\n`;

        cards.forEach(card => {
            let wrapperClass = `${card.width} px-3 mb-6`;
            let textColor = getContrastColor(card.bgColor);
            let cardStyle = `background-color: ${card.bgColor}; color: ${textColor};`;

            html += `  <div class="${wrapperClass}">\n`;

            if (card.type === 'stat') {
                let layoutClass = card.layout || 'layout-classic';
                // Pass the new iconStyle preference to the style generator
                let iconStyleStr = getIconStyles(card.bgColor, layoutClass, card.iconStyle);
                
                // If transparent, we might want to bump the font size of the icon slightly
                let iconFontSize = (card.iconStyle === 'transparent') ? 'font-size: 2rem;' : '';

                html += `    <div class="card stat-card ${layoutClass}" style="${cardStyle}">\n`;
                html += `      <div class="stat-card-inner">\n`;
                
                html += `        <div class="stat-icon-box" style="${iconStyleStr} ${iconFontSize}">\n`;
                html += `          <i class="${card.icon}"></i>\n`;
                html += `        </div>\n`;

                html += `        <div class="stat-content">\n`;
                html += `          <div class="stat-label">${card.title}</div>\n`;
                html += `          <div class="stat-value">${card.value}</div>\n`;
                html += `        </div>\n`;

                html += `      </div>\n`;
                html += `    </div>\n`;
            } else {
                let heightClass = card.height || '';
                html += `    <div class="card ${heightClass}" style="${cardStyle}">\n`;
                html += `      <div class="card-header">${card.title}</div>\n`;
                html += `      <div class="card-body pt-4">\n`;
                html += `        <p style="opacity:0.7">Panel Content...</p>\n`;
                html += `      </div>\n`;
                html += `    </div>\n`;
            }
            
            html += `  </div>\n\n`;
        });

        html += `</div>`;
        renderArea.innerHTML = html;
        codeOutput.textContent = html;
    }

    // --- 4. CONTROLLERS ---
    function addCard(type) {
        if(type === 'stat') cards.push({ type: 'stat', layout: 'layout-classic', iconStyle: 'soft', title: 'Stat', value: '0', icon: 'ri-star-line', bgColor: '#ffffff', width: 'w-25' });
        else cards.push({ type: 'content', title: 'Panel', height: '', width: 'w-50', bgColor: '#ffffff' });
        renderList(); renderDashboard();
    }
    function removeItem(i) { cards.splice(i, 1); renderList(); renderDashboard(); }
    function moveItem(i, dir) { 
        let n = i + dir;
        if (n >= 0 && n < cards.length) { [cards[i], cards[n]] = [cards[n], cards[i]]; renderList(); renderDashboard(); }
    }
    function updateItem(i, k, v) { cards[i][k] = v; renderDashboard(); }
    function toggleCode(show) { document.getElementById('codeModal').style.display = show ? 'flex' : 'none'; }

    renderList(); renderDashboard();
</script>
</body>
</html>