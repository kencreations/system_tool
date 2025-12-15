<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevStudio Dashboard</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>



<div class="auth-wrapper auth-v2">
    <div class="auth-side-form">
        <div class="form-content">
            <div class="brand-logo"><i class="ri-command-fill" style="color:#8b5cf6;"></i> DevStudio</div>
            <h1>Welcome back</h1>
            <p class="subtitle">Please enter your details to sign in.</p>
            
            <form action="#" method="POST">
                
                <div class="form-group">
                    <label class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••">
                </div>
                <div class="form-group" style="display:flex; justify-content:space-between; align-items:center; font-size:0.9rem;">
                    <label style="cursor:pointer; display:flex; gap:5px;"><input type="checkbox" name="remember"> Remember me</label>
                    <a href="#" style="color:var(--primary); text-decoration:none;">Forgot password?</a>
                </div>
                <button type="submit" class="btn btn-primary" style="background-color:#8b5cf6; margin-top:1rem;">Sign in</button>
                
                <button type="button" class="btn btn-google">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google"> 
                    Sign in with Google
                </button>
            </form>
            
            <div style="margin-top:2rem; text-align:center; font-size:0.9rem; color:#64748b;">
                Don't have an account? <a href="#" style="font-weight:600; text-decoration:none; color:var(--primary);">Sign up</a>
            </div>
        </div>
    </div>
    
    <div class="auth-side-img" style="background-color:#8b5cf6;">
        <div style="position:relative; width:80%; height:60%;">
             <i class="ri-customer-service-2-line" style="font-size:15rem; color:rgba(255,255,255,0.2); position:absolute; top:20%; left:10%;"></i>
             <div style="position:absolute; bottom:0; left:50%; transform:translateX(-50%); width:200px; height:240px; background:rgba(255,255,255,0.1); border-radius: 20px 20px 0 0; display:flex; align-items:flex-end; justify-content:center;">
                <i class="ri-user-smile-line" style="font-size:8rem; color:#fff;"></i>
             </div>
        </div>
    </div>
</div>
</body>
</html>