<?php $pageTitle = "Auth Page Builder"; ?>
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
        .preview-area { flex-grow: 1; overflow-y: auto; padding: 0; display: flex; } /* Full width for split layout */
        .properties-panel { width: 350px; background: #fff; border-left: 1px solid #cbd5e1; padding: 20px; overflow-y: auto; }
        
        .form-label { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 5px; display: block; }
        .form-input, .form-select { width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; margin-bottom: 15px; }
        
        /* Code Modal */
        .code-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 50; align-items: center; justify-content: center; }
        .code-window { width: 80%; height: 70%; background: #1e1e1e; color: #ccc; padding: 20px; overflow: auto; border-radius: 8px; font-family: monospace; }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        <div class="workspace-header" style="height:60px; background:#fff; border-bottom:1px solid #e2e8f0; padding:0 20px; display:flex; align-items:center; justify-content:space-between;">
            <h3>Auth Builder</h3>
            <button onclick="toggleCode(true)" class="btn btn-outline">View Code</button>
        </div>
        
        <div class="preview-area" id="renderArea">
            </div>
    </main>

    <aside class="properties-panel">
        <h4>Configuration</h4>
        
        <label class="form-label">Layout</label>
        <select id="optLayout" class="form-select" onchange="render()">
            <option value="centered">Centered Card</option>
            <option value="split">Split Screen (Image)</option>
        </select>

        <label class="form-label">Page Type</label>
        <select id="optType" class="form-select" onchange="render()">
            <option value="login">Login</option>
            <option value="register">Register</option>
            <option value="forgot">Forgot Password</option>
        </select>

        <label class="form-label">Heading</label>
        <input type="text" id="optTitle" class="form-input" value="Welcome Back" oninput="render()">

        <label class="form-label">Sub-Heading</label>
        <input type="text" id="optSub" class="form-input" value="Enter your details to sign in." oninput="render()">
        
        <label class="form-label">Brand Name</label>
        <input type="text" id="optBrand" class="form-input" value="DevStudio" oninput="render()">
    </aside>

    <div id="codeModal" class="code-modal">
        <div class="code-window">
            <button onclick="toggleCode(false)" class="btn btn-danger" style="float:right; margin-bottom:10px;">Close</button>
            <pre id="codeOutput"></pre>
        </div>
    </div>

    <script>
        function render() {
            const layout = document.getElementById('optLayout').value;
            const type = document.getElementById('optType').value;
            const title = document.getElementById('optTitle').value;
            const sub = document.getElementById('optSub').value;
            const brand = document.getElementById('optBrand').value;
            
            const area = document.getElementById('renderArea');
            
            // Build Form Fields based on Type
            let fields = '';
            if(type === 'login') {
                fields = `
                    <div class="form-group mb-4">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" placeholder="name@company.com">
                    </div>
                    <div class="form-group mb-4">
                        <div class="flex justify-between">
                            <label class="form-label">Password</label>
                            <a href="#" class="text-sm text-primary" style="text-decoration:none;">Forgot?</a>
                        </div>
                        <input type="password" class="form-control" placeholder="••••••••">
                    </div>
                    <button class="btn btn-primary w-100">Sign In</button>
                `;
            } else if(type === 'register') {
                fields = `
                    <div class="form-group mb-4">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" placeholder="John Doe">
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" placeholder="name@company.com">
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" placeholder="Create a password">
                    </div>
                    <button class="btn btn-primary w-100">Create Account</button>
                `;
            } else {
                fields = `
                    <div class="form-group mb-4">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" placeholder="name@company.com">
                    </div>
                    <button class="btn btn-primary w-100">Send Reset Link</button>
                `;
            }

            // Build Layout HTML
            let html = '';
            if(layout === 'centered') {
                html = `
<div class="auth-layout auth-centered">
    <div class="auth-card">
        <div class="auth-header">
            <div style="font-size:24px; font-weight:bold; color:var(--primary); margin-bottom:20px;">
                <i class="ri-command-line"></i> ${brand}
            </div>
            <h1>${title}</h1>
            <p>${sub}</p>
        </div>
        <form>
            ${fields}
        </form>
        <div class="auth-footer">
            ${type === 'register' ? 'Already have an account? <a href="#">Sign in</a>' : 'Don\'t have an account? <a href="#">Sign up</a>'}
        </div>
    </div>
</div>`;
            } else {
                // Split Layout
                html = `
<div class="auth-layout auth-split">
    <div class="auth-banner" style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
        <div style="max-width:400px; text-align:center;">
            <h2 style="font-size:2rem; margin-bottom:1rem;">"Digital design is like painting, except the paint never dries."</h2>
            <p style="opacity:0.8;">- Neville Brody</p>
        </div>
    </div>
    <div class="auth-form-side">
        <div class="auth-content">
            <div class="auth-header" style="text-align:left;">
                <div style="font-size:24px; font-weight:bold; color:var(--primary); margin-bottom:20px;">${brand}</div>
                <h1>${title}</h1>
                <p>${sub}</p>
            </div>
            <form>
                ${fields}
            </form>
            <div class="auth-footer" style="text-align:left;">
                ${type === 'register' ? 'Already have an account? <a href="#">Sign in</a>' : 'Don\'t have an account? <a href="#">Sign up</a>'}
            </div>
        </div>
    </div>
</div>`;
            }

            area.innerHTML = html;
            document.getElementById('codeOutput').innerText = html;
        }

        function toggleCode(show) { document.getElementById('codeModal').style.display = show ? 'flex' : 'none'; }

        render();
    </script>
</body>
</html>