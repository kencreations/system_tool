<?php $pageTitle = "Alerts Builder"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { overflow: hidden; height: 100vh; display: flex; }
        .workspace { flex-grow: 1; background: #fff; display: flex; flex-direction: column; }
        .workspace-header { height: 60px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; }
        .preview-area { flex-grow: 1; padding: 40px; overflow-y: auto; background: #f8fafc; }
        .preview-container { max-width: 800px; margin: 0 auto; }
        
        .properties-panel { width: 350px; border-left: 1px solid #e2e8f0; padding: 20px; overflow-y: auto; background: #fff; }
        .field-group { border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 15px; background: #fff; }
        .form-select, .form-input { width: 100%; padding: 8px; margin-bottom: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; }
        
        .code-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 50; align-items: center; justify-content: center; }
        .code-window { width: 80%; height: 70%; background: #1e1e1e; color: #ccc; padding: 20px; overflow: auto; border-radius: 8px; font-family: monospace; }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        <header class="workspace-header">
            <h3>Alerts Builder</h3>
            <div style="display:flex; gap:10px;">
                <button onclick="toggleCode(true)" class="btn btn-outline">View Code</button>
            </div>
        </header>
        
        <div class="preview-area">
            <div class="preview-container" id="alertRenderArea"></div>
        </div>
    </main>

    <aside class="properties-panel">
        <h4 style="margin-top:0;">Alert Stack</h4>
        <button onclick="addAlert()" class="btn btn-primary w-100" style="margin-bottom:20px;">+ Add Alert</button>
        <div id="alertList"></div>
    </aside>

    <div id="codeModal" class="code-modal">
        <div class="code-window">
            <button onclick="toggleCode(false)" class="btn btn-danger" style="float:right; margin-bottom:10px;">Close</button>
            <pre id="codeOutput"></pre>
        </div>
    </div>

    <script>
        let alerts = [
            { style: 'soft', type: 'primary', icon: 'ri-information-line', title: 'Note', message: 'This is a soft primary alert.', dismiss: true },
            { style: 'solid', type: 'danger', icon: 'ri-error-warning-line', title: 'Error', message: 'Something went wrong!', dismiss: false }
        ];

        function render() {
            const area = document.getElementById('alertRenderArea');
            const list = document.getElementById('alertList');
            const code = document.getElementById('codeOutput');
            
            area.innerHTML = '';
            list.innerHTML = '';
            
            let fullHtml = '';

            alerts.forEach((alert, i) => {
                // 1. Render Preview
                let closeHtml = alert.dismiss ? '<i class="ri-close-line alert-close"></i>' : '';
                let alertHtml = `
                <div class="alert alert-${alert.style}-${alert.type}">
                    <i class="${alert.icon} alert-icon"></i>
                    <div class="alert-content">
                        ${alert.title ? `<span class="alert-title">${alert.title}</span>` : ''}
                        <span>${alert.message}</span>
                    </div>
                    ${closeHtml}
                </div>`;
                
                area.innerHTML += alertHtml;
                fullHtml += alertHtml + '\n';

                // 2. Render Controls
                let div = document.createElement('div');
                div.className = 'field-group';
                div.innerHTML = `
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                        <strong>Alert ${i+1}</strong>
                        <i class="ri-delete-bin-line" style="cursor:pointer; color:red;" onclick="removeAlert(${i})"></i>
                    </div>
                    <select class="form-select" onchange="updateAlert(${i}, 'style', this.value)">
                        <option value="soft" ${alert.style==='soft'?'selected':''}>Soft (Modern)</option>
                        <option value="solid" ${alert.style==='solid'?'selected':''}>Solid (Bold)</option>
                        <option value="outline" ${alert.style==='outline'?'selected':''}>Outline</option>
                    </select>
                    <select class="form-select" onchange="updateAlert(${i}, 'type', this.value)">
                        <option value="primary" ${alert.type==='primary'?'selected':''}>Primary (Blue)</option>
                        <option value="success" ${alert.type==='success'?'selected':''}>Success (Green)</option>
                        <option value="warning" ${alert.type==='warning'?'selected':''}>Warning (Orange)</option>
                        <option value="danger" ${alert.type==='danger'?'selected':''}>Danger (Red)</option>
                    </select>
                    <input type="text" class="form-input" value="${alert.title}" oninput="updateAlert(${i}, 'title', this.value)" placeholder="Title">
                    <input type="text" class="form-input" value="${alert.message}" oninput="updateAlert(${i}, 'message', this.value)" placeholder="Message">
                    <label style="font-size:13px;"><input type="checkbox" ${alert.dismiss?'checked':''} onchange="updateAlert(${i}, 'dismiss', this.checked)"> Dismissible</label>
                `;
                list.appendChild(div);
            });
            
            code.innerText = fullHtml;
        }

        function addAlert() { alerts.push({style:'soft', type:'success', icon:'ri-check-line', title:'Success', message:'Operation completed.', dismiss:true}); render(); }
        function removeAlert(i) { alerts.splice(i,1); render(); }
        function updateAlert(i,k,v) { alerts[i][k]=v; render(); }
        function toggleCode(show) { document.getElementById('codeModal').style.display = show ? 'flex' : 'none'; }

        render();
    </script>
</body>
</html>