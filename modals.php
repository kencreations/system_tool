<?php $pageTitle = "Modal Builder"; ?>
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
        .preview-area { 
            flex-grow: 1; display: flex; align-items: center; justify-content: center; 
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 20px 20px; 
        }
        .properties-panel { width: 350px; background: #fff; border-left: 1px solid #cbd5e1; padding: 20px; overflow-y: auto; }
        
        .form-label { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 5px; display: block; }
        .form-input, .form-select, textarea { width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; margin-bottom: 15px; }
        
        .code-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 999; align-items: center; justify-content: center; }
        .code-window { width: 80%; height: 70%; background: #1e1e1e; color: #ccc; padding: 20px; overflow: auto; border-radius: 8px; font-family: monospace; }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        <div class="workspace-header" style="height:60px; background:#fff; border-bottom:1px solid #e2e8f0; padding:0 20px; display:flex; align-items:center; justify-content:space-between;">
            <h3>Modal Builder</h3>
            <button onclick="toggleCode(true)" class="btn btn-outline">View Code</button>
        </div>
        
        <div class="preview-area">
            <button class="btn btn-primary" onclick="openModal()">
                <i class="ri-window-line"></i> Open Modal Preview
            </button>
        </div>
    </main>

    <aside class="properties-panel">
        <h4>Configuration</h4>
        
        <label class="form-label">Size</label>
        <select id="optSize" class="form-select" onchange="renderModal()">
            <option value="">Default (Medium)</option>
            <option value="modal-sm">Small (Confirmations)</option>
            <option value="modal-lg">Large (Forms)</option>
            <option value="modal-xl">Extra Large (Tables)</option>
        </select>

        <label class="form-label">Header Title</label>
        <input type="text" id="optTitle" class="form-input" value="Confirm Action" oninput="renderModal()">

        <label class="form-label">Body Content</label>
        <textarea id="optBody" rows="4" oninput="renderModal()">Are you sure you want to delete this item? This action cannot be undone.</textarea>

        <label class="form-label">Primary Button</label>
        <input type="text" id="optBtnPrimary" class="form-input" value="Confirm" oninput="renderModal()">
        
        <label class="form-label">Secondary Button</label>
        <input type="text" id="optBtnSecondary" class="form-input" value="Cancel" oninput="renderModal()">
    </aside>

    <div id="modalOverlay" class="modal-overlay">
        <div id="modalContent" class="modal-content">
            <div class="modal-header">
                <h3 id="previewTitle">Title</h3>
                <button class="modal-close" onclick="closeModal()"><i class="ri-close-line"></i></button>
            </div>
            <div class="modal-body" id="previewBody">Body text...</div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal()" id="previewBtnSec">Cancel</button>
                <button class="btn btn-primary" onclick="closeModal()" id="previewBtnPri">Action</button>
            </div>
        </div>
    </div>

    <div id="codeModal" class="code-modal">
        <div class="code-window">
            <button onclick="toggleCode(false)" class="btn btn-danger" style="float:right; margin-bottom:10px;">Close</button>
            <pre id="codeOutput"></pre>
        </div>
    </div>

    <script>
        function renderModal() {
            // Update Visuals
            const size = document.getElementById('optSize').value;
            const title = document.getElementById('optTitle').value;
            const body = document.getElementById('optBody').value;
            const btnPri = document.getElementById('optBtnPrimary').value;
            const btnSec = document.getElementById('optBtnSecondary').value;

            // Apply Classes & Text
            const content = document.getElementById('modalContent');
            content.className = `modal-content ${size}`;
            
            document.getElementById('previewTitle').textContent = title;
            document.getElementById('previewBody').innerHTML = body; // Using HTML allows <p> tags
            document.getElementById('previewBtnPri').textContent = btnPri;
            document.getElementById('previewBtnSec').textContent = btnSec;

            // Generate Code
            const code = `
<button class="btn btn-primary" onclick="toggleModal('myModal', true)">Open Modal</button>

<div id="myModal" class="modal-overlay">
    <div class="modal-content ${size}">
        <div class="modal-header">
            <h3>${title}</h3>
            <button class="modal-close" onclick="toggleModal('myModal', false)">
                <i class="ri-close-line"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>${body}</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="toggleModal('myModal', false)">${btnSec}</button>
            <button class="btn btn-primary">${btnPri}</button>
        </div>
    </div>
</div>

<script>
function toggleModal(id, show) {
    const el = document.getElementById(id);
    if(show) el.classList.add('open');
    else el.classList.remove('open');
}
<\/script>`;
            document.getElementById('codeOutput').textContent = code;
        }

        function openModal() {
            document.getElementById('modalOverlay').classList.add('open');
        }
        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('open');
        }
        function toggleCode(show) { document.getElementById('codeModal').style.display = show ? 'flex' : 'none'; }

        // Init
        renderModal();
    </script>
</body>
</html>