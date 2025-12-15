<?php $pageTitle = "Profile Builder"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { overflow: hidden; height: 100vh; display: flex; }
        .workspace { flex-grow: 1; background: #e2e8f0; padding: 40px; overflow-y: auto; }
        
        /* SETTINGS LAYOUT */
        .settings-container {
            display: flex; gap: 30px; max-width: 1000px; margin: 0 auto;
        }
        .settings-sidebar {
            width: 250px; background: #fff; border-radius: 12px; padding: 15px; height: fit-content;
            border: 1px solid #cbd5e1;
        }
        .settings-link {
            display: flex; align-items: center; gap: 10px; padding: 12px; border-radius: 8px;
            color: #64748b; text-decoration: none; font-size: 14px; font-weight: 500;
        }
        .settings-link.active { background: #eff6ff; color: var(--primary); }
        .settings-link:hover { background: #f8fafc; }

        .settings-content {
            flex: 1; background: #fff; border-radius: 12px; padding: 30px; border: 1px solid #cbd5e1;
        }
        
        .section-head { border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px; }
        .section-head h2 { margin: 0; font-size: 18px; color: #1e293b; }
        .section-head p { margin: 5px 0 0; color: #64748b; font-size: 13px; }

        .avatar-upload { display: flex; align-items: center; gap: 20px; margin-bottom: 30px; }
        .avatar-preview { width: 80px; height: 80px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #94a3b8; }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #334155; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        <div style="display:flex; justify-content:space-between; max-width:1000px; margin:0 auto 20px;">
            <h3 style="margin:0;">Profile & Settings</h3>
            <button class="btn btn-outline" onclick="alert('Copy the HTML structure from the container div!')">View Code</button>
        </div>

        <div class="settings-container">
            <div class="settings-sidebar">
                <a href="#" class="settings-link active"><i class="ri-user-line"></i> My Profile</a>
                <a href="#" class="settings-link"><i class="ri-lock-password-line"></i> Password</a>
                <a href="#" class="settings-link"><i class="ri-notification-3-line"></i> Notifications</a>
                <a href="#" class="settings-link"><i class="ri-bill-line"></i> Billing</a>
            </div>

            <div class="settings-content">
                <div class="section-head">
                    <h2>Personal Information</h2>
                    <p>Update your photo and personal details here.</p>
                </div>

                <div class="avatar-upload">
                    <div class="avatar-preview"><i class="ri-user-smile-line"></i></div>
                    <div>
                        <button class="btn btn-outline" style="margin-right:10px;">Change Photo</button>
                        <span style="font-size:12px; color:#94a3b8;">JPG or PNG, max 1MB</span>
                    </div>
                </div>

                <form>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" value="John">
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control" value="Doe">
                        </div>
                        <div class="form-group" style="grid-column: span 2;">
                            <label>Email Address</label>
                            <input type="email" class="form-control" value="john@example.com">
                        </div>
                        <div class="form-group" style="grid-column: span 2;">
                            <label>Bio</label>
                            <textarea class="form-control" rows="3">Software Engineer based in NY.</textarea>
                        </div>
                    </div>
                    <div style="margin-top:30px; text-align:right;">
                        <button class="btn btn-outline" style="margin-right:10px;">Cancel</button>
                        <button class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>