<?php $pageTitle = "Notification Builder"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { overflow: hidden; height: 100vh; display: flex; }
        .workspace { flex-grow: 1; background: #e2e8f0; display: flex; flex-direction: column; }
        /* Simulation of a browser window */
        .preview-area { 
            flex-grow: 1; margin: 40px; background: #fff; 
            border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
            position: relative; overflow: hidden; /* Important for absolute toasts */
            display: flex; align-items: center; justify-content: center;
        }
        .properties-panel { width: 350px; background: #fff; border-left: 1px solid #cbd5e1; padding: 20px; }
        .form-select, .form-input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #cbd5e1; border-radius: 6px; }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        <div class="workspace-header" style="background:#fff; height:60px; padding:0 20px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #e2e8f0;">
            <h3>Notifications (Toasts)</h3>
        </div>
        
        <div class="preview-area" id="previewContainer">
            <button class="btn btn-primary" onclick="showToast()">
                <i class="ri-play-circle-line"></i> Trigger Notification
            </button>
            
            <div id="toastContainer" class="toast-wrapper toast-top-right"></div>
        </div>
    </main>

    <aside class="properties-panel">
        <h4>Configuration</h4>
        
        <label style="display:block; margin-bottom:5px; font-size:13px; color:#64748b;">Position</label>
        <select class="form-select" id="optPosition" onchange="updateConfig()">
            <option value="toast-top-right">Top Right</option>
            <option value="toast-top-left">Top Left</option>
            <option value="toast-bottom-right">Bottom Right</option>
            <option value="toast-bottom-left">Bottom Left</option>
        </select>

        <label style="display:block; margin-bottom:5px; font-size:13px; color:#64748b;">Type</label>
        <select class="form-select" id="optType">
            <option value="success">Success</option>
            <option value="error">Error</option>
            <option value="info">Info</option>
        </select>

        <label style="display:block; margin-bottom:5px; font-size:13px; color:#64748b;">Title</label>
        <input type="text" class="form-input" id="optTitle" value="Notification">

        <label style="display:block; margin-bottom:5px; font-size:13px; color:#64748b;">Message</label>
        <input type="text" class="form-input" id="optMessage" value="Action completed successfully.">

        <hr style="border:0; border-top:1px solid #e2e8f0; margin:20px 0;">
        
        <h4>JS Usage Code</h4>
        <textarea id="codeOutput" style="width:100%; height:150px; padding:10px; font-family:monospace; border:1px solid #cbd5e1; border-radius:6px; font-size:12px;"></textarea>
    </aside>

    <script>
        function updateConfig() {
            // Update container position
            const pos = document.getElementById('optPosition').value;
            const container = document.getElementById('toastContainer');
            container.className = `toast-wrapper ${pos}`;
            
            generateCode();
        }

        function showToast() {
            const type = document.getElementById('optType').value;
            const title = document.getElementById('optTitle').value;
            const message = document.getElementById('optMessage').value;
            const container = document.getElementById('toastContainer');

            // Icon mapping
            const icons = { success: 'ri-checkbox-circle-fill', error: 'ri-error-warning-fill', info: 'ri-information-fill' };
            const icon = icons[type];

            // Create HTML Element
            const toast = document.createElement('div');
            toast.className = `toast-card toast-${type}`;
            toast.innerHTML = `
                <i class="${icon} toast-icon"></i>
                <div class="toast-body">
                    <strong>${title}</strong>
                    <p>${message}</p>
                </div>
                <i class="ri-close-line toast-close" onclick="this.parentElement.remove()"></i>
            `;

            // Append
            container.appendChild(toast);

            // Auto remove after 3s
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
            
            generateCode();
        }

        function generateCode() {
            const type = document.getElementById('optType').value;
            const title = document.getElementById('optTitle').value;
            const message = document.getElementById('optMessage').value;
            const pos = document.getElementById('optPosition').value;

            const js = `
// 1. Ensure you have the HTML Container in your layout:
// <div id="toastContainer" class="toast-wrapper ${pos}"></div>

// 2. JS Function to trigger
function showToast() {
    const toast = document.createElement('div');
    toast.className = 'toast-card toast-${type}';
    toast.innerHTML = \`
        <i class="ri-notification-line toast-icon"></i>
        <div class="toast-body">
            <strong>${title}</strong>
            <p>${message}</p>
        </div>
        <i class="ri-close-line toast-close" onclick="this.parentElement.remove()"></i>
    \`;
    document.getElementById('toastContainer').appendChild(toast);
    
    // Auto Remove
    setTimeout(() => toast.remove(), 3000);
}`;
            document.getElementById('codeOutput').value = js;
        }

        // Init
        generateCode();
    </script>
</body>
</html>