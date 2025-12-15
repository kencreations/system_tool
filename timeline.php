<?php $pageTitle = "Timeline Builder"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { overflow: hidden; height: 100vh; display: flex; }
        .workspace { flex-grow: 1; background: #f8fafc; display: flex; flex-direction: column; }
        .preview-area { flex-grow: 1; padding: 40px; overflow-y: auto; display: flex; justify-content: center; }
        /* Card wrapper for the preview */
        .timeline-card { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); width: 100%; max-width: 600px; border: 1px solid #e2e8f0; }
        
        .properties-panel { width: 360px; background: #fff; border-left: 1px solid #cbd5e1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; }
        .field-group { border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 12px; background: #fff; transition: border 0.2s; }
        .field-group:hover { border-color: #94a3b8; }
        
        .form-input { width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; margin-bottom: 8px; }
        .row { display: flex; gap: 8px; }
        
        /* Code Modal */
        .code-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 50; align-items: center; justify-content: center; }
        .code-window { width: 80%; height: 70%; background: #1e1e1e; color: #ccc; padding: 20px; overflow: auto; border-radius: 8px; font-family: monospace; }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        <div class="workspace-header" style="height:60px; background:#fff; border-bottom:1px solid #e2e8f0; padding:0 20px; display:flex; align-items:center; justify-content:space-between;">
            <h3 style="margin:0; font-size:16px; font-weight:600; color:#1e293b;">Timeline Builder</h3>
            <button onclick="toggleCode(true)" class="btn btn-outline" style="font-size:0.85rem;">View Code</button>
        </div>
        
        <div class="preview-area">
            <div class="timeline-card">
                <h3 style="margin-top:0; border-bottom:1px solid #f1f5f9; padding-bottom:15px; margin-bottom:20px; font-size:1.1rem;">Activity Log</h3>
                <div id="renderArea" class="timeline"></div>
            </div>
        </div>
    </main>

    <aside class="properties-panel">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h4 style="margin:0;">Timeline Events</h4>
            <button onclick="addEvent()" class="btn btn-primary" style="font-size:11px; padding:4px 8px;">+ Add Event</button>
        </div>
        <div id="eventList"></div>
    </aside>

    <div id="codeModal" class="code-modal">
        <div class="code-window">
            <div style="display:flex; justify-content:flex-end; margin-bottom:10px;">
                <button onclick="toggleCode(false)" class="btn btn-danger" style="padding:4px 12px;">Close</button>
            </div>
            <pre id="codeOutput"></pre>
        </div>
    </div>

    <script>
        // Data State
        let events = [
            { date: 'Just now', title: 'Project Created', text: 'New system architecture initiated.', type: 'primary', icon: 'ri-add-line', style: 'icon' },
            { date: '2 hrs ago', title: 'Meeting with Team', text: 'Discussed Q3 roadmap goals.', type: 'success', icon: '', style: 'dot' },
            { date: 'Yesterday', title: 'Server Alert', text: 'High CPU usage detected on Node 4.', type: 'danger', icon: 'ri-alert-line', style: 'icon' }
        ];

        // 1. RENDER PREVIEW & CODE
        function render() {
            const area = document.getElementById('renderArea');
            const code = document.getElementById('codeOutput');
            area.innerHTML = '';
            
            let html = '';

            events.forEach(ev => {
                let markerHtml = '';
                
                // Logic for Icon vs Dot style
                if (ev.style === 'icon') {
                    markerHtml = `<div class="timeline-marker has-icon bg-${ev.type}"><i class="${ev.icon}"></i></div>`;
                } else {
                    markerHtml = `<div class="timeline-marker marker-${ev.type}"></div>`;
                }

                html += `
    <div class="timeline-item">
        ${markerHtml}
        <div class="timeline-content">
            <span class="timeline-date">${ev.date}</span>
            <div class="timeline-title">${ev.title}</div>
            <div class="timeline-text">${ev.text}</div>
        </div>
    </div>`;
            });

            area.innerHTML = html;
            
            // Format code output
            code.innerText = `<div class="timeline">\n${html}\n</div>`;
            
            renderControls();
        }

        // 2. RENDER SIDEBAR CONTROLS
        function renderControls() {
            const list = document.getElementById('eventList');
            list.innerHTML = '';

            events.forEach((ev, i) => {
                let div = document.createElement('div');
                div.className = 'field-group';
                
                // Icon input visibility toggle
                let iconDisplay = ev.style === 'icon' ? 'block' : 'none';

                div.innerHTML = `
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                        <span style="font-weight:bold; font-size:12px; color:#94a3b8;">EVENT ${i+1}</span>
                        <div>
                            <i class="ri-arrow-up-s-line" style="cursor:pointer; color:#94a3b8;" onclick="moveEvent(${i}, -1)"></i>
                            <i class="ri-arrow-down-s-line" style="cursor:pointer; color:#94a3b8;" onclick="moveEvent(${i}, 1)"></i>
                            <i class="ri-delete-bin-line" style="cursor:pointer; color:#ef4444; margin-left:5px;" onclick="removeEvent(${i})"></i>
                        </div>
                    </div>
                    
                    <div class="row">
                        <select class="form-input" onchange="updateEvent(${i}, 'type', this.value)">
                            <option value="primary" ${ev.type==='primary'?'selected':''}>Blue</option>
                            <option value="success" ${ev.type==='success'?'selected':''}>Green</option>
                            <option value="warning" ${ev.type==='warning'?'selected':''}>Orange</option>
                            <option value="danger" ${ev.type==='danger'?'selected':''}>Red</option>
                        </select>
                        <select class="form-input" onchange="updateEvent(${i}, 'style', this.value)">
                            <option value="dot" ${ev.style==='dot'?'selected':''}>Dot</option>
                            <option value="icon" ${ev.style==='icon'?'selected':''}>Icon</option>
                        </select>
                    </div>

                    <div id="iconInput-${i}" style="display:${iconDisplay}; margin-bottom:8px;">
                        <input type="text" class="form-input" value="${ev.icon}" oninput="updateEvent(${i}, 'icon', this.value)" placeholder="Icon Class (ri-...)">
                    </div>

                    <input type="text" class="form-input" value="${ev.date}" oninput="updateEvent(${i}, 'date', this.value)" placeholder="Date/Time">
                    <input type="text" class="form-input" value="${ev.title}" oninput="updateEvent(${i}, 'title', this.value)" placeholder="Title">
                    <textarea class="form-input" oninput="updateEvent(${i}, 'text', this.value)" placeholder="Description" rows="2">${ev.text}</textarea>
                `;
                list.appendChild(div);
            });
        }

        // 3. LOGIC
        function addEvent() { 
            events.push({ date: 'Now', title: 'New Event', text: 'Details...', type: 'primary', icon: 'ri-circle-fill', style: 'dot' }); 
            render(); 
        }
        function removeEvent(i) { events.splice(i, 1); render(); }
        function moveEvent(i, dir) {
            let n = i + dir;
            if (n >= 0 && n < events.length) {
                [events[i], events[n]] = [events[n], events[i]];
                render();
            }
        }
        function updateEvent(i, k, v) { 
            events[i][k] = v; 
            render(); 
        }
        function toggleCode(show) { document.getElementById('codeModal').style.display = show ? 'flex' : 'none'; }

        // Init
        render();
    </script>
</body>
</html>