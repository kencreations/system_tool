<?php $pageTitle = "Page Templates"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        /* PAGE LAYOUT */
        body { overflow: hidden; height: 100vh; display: flex; }
        .workspace { flex-grow: 1; background: #e2e8f0; display: flex; flex-direction: column; }
        .workspace-header { height: 60px; background: #fff; border-bottom: 1px solid #cbd5e1; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        
        /* PREVIEW AREA */
        .preview-scroll-area { 
            flex-grow: 1; overflow: hidden; padding: 30px; 
            display: flex; align-items: center; justify-content: center;
            background-color: #f1f5f9;
        }
        
        .browser-mockup {
            width: 100%; height: 100%; background: #fff; 
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2); 
            border-radius: 8px; overflow: hidden; display: flex; flex-direction: column;
            border: 1px solid #cbd5e1;
        }

        .browser-header {
            height: 36px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; padding-left: 12px; gap: 6px; flex-shrink: 0;
        }
        .browser-dot { width: 10px; height: 10px; border-radius: 50%; }
        .bg-red { background: #ef4444; } .bg-yellow { background: #f59e0b; } .bg-green { background: #10b981; }

        /* Render Area */
        #renderArea { flex-grow: 1; overflow-y: auto; position: relative; display:flex; flex-direction:column; }
        
        #renderArea .auth-wrapper {
            min-height: 100% !important; 
            height: auto;
            position: absolute; width: 100%; top: 0; left: 0; bottom: 0;
        }

        /* PROPERTIES PANEL */
        .properties-panel { width: 360px; background: #fff; border-left: 1px solid #cbd5e1; padding: 20px; overflow-y: auto; }
        .section-title { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin: 20px 0 10px; }
        .form-select, .form-input { width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; margin-bottom: 10px; }
        
        .version-card {
            border: 1px solid #e2e8f0; padding: 10px; border-radius: 8px; cursor: pointer; margin-bottom: 10px; transition: all 0.2s;
            display: flex; align-items: center; gap: 10px;
        }
        .version-card:hover { border-color: var(--primary); background: #eff6ff; }
        .version-card.active { border-color: var(--primary); background: #eff6ff; box-shadow: 0 0 0 2px rgba(59,130,246,0.2); }
        .v-icon { width: 32px; height: 32px; background: #e2e8f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 10px; color: #64748b; }

        /* Code Modal */
        .code-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 50; align-items: center; justify-content: center; }
        .code-window { width: 80%; height: 80%; background: #1e1e1e; color: #ccc; padding: 20px; border-radius: 8px; font-family: monospace; display:flex; flex-direction:column; }
        .code-text { flex-grow:1; background:transparent; border:none; color:inherit; resize:none; outline:none; font-size:13px; line-height:1.5; }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        <div class="workspace-header">
            <h3>Auth & Templates</h3>
            <button onclick="toggleCode(true)" class="btn btn-outline" style="font-size:0.85rem;">View Code</button>
        </div>
        
        <div class="preview-scroll-area">
            <div class="browser-mockup">
                <div class="browser-header">
                    <div class="browser-dot bg-red"></div>
                    <div class="browser-dot bg-yellow"></div>
                    <div class="browser-dot bg-green"></div>
                </div>
                <div id="renderArea"></div>
            </div>
        </div>
    </main>

    <aside class="properties-panel">
        <h4 style="margin-top:0;">Select Layout</h4>
        
        <div class="version-card active" onclick="setVersion('v1', this)">
            <div class="v-icon">V1</div>
            <div>
                <div style="font-weight:600; font-size:13px;">Classic (Bentley)</div>
                <div style="font-size:11px; color:#64748b;">Dark BG, centered white card</div>
            </div>
        </div>
        
        <div class="version-card" onclick="setVersion('v2', this)">
            <div class="v-icon">V2</div>
            <div>
                <div style="font-weight:600; font-size:13px;">Split (CubeFactory)</div>
                <div style="font-size:11px; color:#64748b;">Form Left, Illustration Right</div>
            </div>
        </div>

        <div class="version-card" onclick="setVersion('v3', this)">
            <div class="v-icon">V3</div>
            <div>
                <div style="font-weight:600; font-size:13px;">Side Banner</div>
                <div style="font-size:11px; color:#64748b;">Dark sidebar left, Form right</div>
            </div>
        </div>

        <div class="section-title">Settings</div>
        <label style="font-size:12px; font-weight:600;">Page Type</label>
        <select id="optType" class="form-select" onchange="render()">
            <option value="login">Login</option>
            <option value="register">Sign Up</option>
            <option value="forgot">Forgot Password</option>
        </select>

        <label style="font-size:12px; font-weight:600;">Brand Name</label>
        <input type="text" id="optBrand" class="form-input" value="DevStudio" oninput="render()">
        
        <label style="font-size:12px; font-weight:600;">V2 Accent Color</label>
        <input type="color" id="optColor" class="form-input" value="#8b5cf6" style="height:35px; cursor:pointer;" oninput="render()">
        
        <div style="margin-top:20px; padding:10px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:6px; font-size:11px; color:#166534;">
            <i class="ri-check-double-line"></i> <strong>jQuery Included:</strong> <br>Code includes AJAX script to connect with <code>auth.php</code> automatically.
        </div>
    </aside>

    <div id="codeModal" class="code-modal">
        <div class="code-window">
            <div style="text-align:right; margin-bottom:10px;"><button onclick="toggleCode(false)" class="btn btn-danger">Close</button></div>
            <textarea class="code-text" id="codeOutput" spellcheck="false"></textarea>
        </div>
    </div>

    <script>
        let currentVersion = 'v1';

        function setVersion(ver, el) {
            currentVersion = ver;
            document.querySelectorAll('.version-card').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            render();
        }

        function render() {
            const type = document.getElementById('optType').value;
            const brand = document.getElementById('optBrand').value;
            const color = document.getElementById('optColor').value;
            const area = document.getElementById('renderArea');
            const code = document.getElementById('codeOutput');

            // --- CONTENT VARIABLES ---
            let title = type==='login' ? 'Welcome back' : 'Create account';
            let sub = type==='login' ? 'Please enter your details.' : 'Start your free trial.';
            let btn = type==='login' ? 'Sign in' : 'Sign up';
            if(type==='forgot') { title='Reset Password'; sub='Enter email to continue.'; btn='Send Link'; }

            // --- FORM FIELDS ---
            let fields = `
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="user@example.com" required>
                </div>`;
            
            if(type !== 'forgot') {
                fields += `
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••" required>
                </div>`;
            }
            
            if(type === 'register') {
                fields = `<div class="form-group"><label class="form-label">Username</label><input type="text" name="username" class="form-control" placeholder="JohnDoe" required></div>` + fields;
            }

            // --- LAYOUTS ---
            let html = '';

            if (currentVersion === 'v1') {
                // Bentley Style
                html = `
<div class="auth-wrapper auth-v1" style="background:#0f172a;">
    <div class="auth-card">
        <div class="auth-logo"><i class="ri-flashlight-fill"></i></div>
        <h1>${brand} System</h1>
        <p style="margin-bottom:20px;">${sub}</p>
        <form id="authForm">
            ${fields}
            <button type="submit" class="btn btn-primary" style="margin-top:15px;">${btn}</button>
        </form>
    </div>
    <div class="auth-footer-links" style="margin-top:20px; color:#94a3b8;">
        <a href="#" style="color:#cbd5e1;">Forgot password?</a>
        <a href="#" style="color:#cbd5e1;">Create account</a>
    </div>
</div>`;
            } 
            else if (currentVersion === 'v2') {
                // CubeFactory Style
                let googleBtn = type!=='forgot' ? `<button type="button" class="btn btn-google" style="margin-top:10px;">Sign in with Google</button>` : '';
                
                html = `
<div class="auth-wrapper auth-v2">
    <div class="auth-side-form">
        <div class="form-content">
            <div class="brand-logo"><i class="ri-command-fill" style="color:${color};"></i> ${brand}</div>
            <h1>${title}</h1>
            <p class="subtitle">${sub}</p>
            <form id="authForm">
                ${fields}
                <button type="submit" class="btn btn-primary" style="background-color:${color}; border-color:${color};">${btn}</button>
                ${googleBtn}
            </form>
        </div>
    </div>
    <div class="auth-side-img" style="background-color:${color};">
        <div style="color:rgba(255,255,255,0.2); display:flex; justify-content:center; align-items:center; height:100%;">
            <i class="ri-artboard-line" style="font-size:150px;"></i>
        </div>
    </div>
</div>`;
            }
            else if (currentVersion === 'v3') {
                // RESTORED V3 (Side Banner)
                html = `
<div class="auth-wrapper auth-v3">
    <aside class="auth-sidebar">
        <div class="sidebar-logo"><i class="ri-command-line"></i> ${brand}</div>
        <div class="sidebar-text">
            <h2>"Simple, powerful, and fast."</h2>
            <p>Join thousands of developers.</p>
        </div>
        <div class="sidebar-footer">© 2024 ${brand} Inc.</div>
    </aside>
    <div class="auth-main">
        <div class="auth-form-container">
            <div class="auth-header" style="text-align:left;">
                <h1>${title}</h1>
                <p>${sub}</p>
            </div>
            <form id="authForm">
                ${fields}
                <button type="submit" class="btn btn-primary" style="margin-top:15px;">${btn}</button>
            </form>
            <div class="auth-footer" style="text-align:left; margin-top:1rem;">
                <a href="#">Forgot Password?</a>
            </div>
        </div>
    </div>
</div>`;
            }

            area.innerHTML = html;

            // --- JQUERY AJAX SCRIPT GENERATOR ---
            let actionEndpoint = type; 
            let redirectUrl = type === 'login' ? 'dashboard.html' : 'login.html';
            
            const jqueryScript = `
<script src="https://code.jquery.com/jquery-3.6.0.min.js"><\/script>
<script>
$(document).ready(function() {
    $('#authForm').on('submit', function(e) {
        e.preventDefault();
        
        // 1. Collect Data
        var formData = {};
        $(this).serializeArray().forEach(function(item) {
            formData[item.name] = item.value;
        });

        // 2. Send to Backend
        $.ajax({
            url: 'auth.php?action=${actionEndpoint}', 
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(response) {
                if(response.success) {
                    alert('${title} Successful!');
                    // window.location.href = '${redirectUrl}'; 
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function(xhr) {
                alert('Request failed. Check console.');
                console.log(xhr.responseText);
            }
        });
    });
});
<\/script>`;

            code.value = `\n\n\n` + html + `\n\n` + jqueryScript;
        }

        function toggleCode(show) { document.getElementById('codeModal').style.display = show ? 'flex' : 'none'; }
        render();
    </script>
</body>
</html>