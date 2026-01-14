<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') - Inventra</title>
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            overflow: hidden;
        }
        
        .login-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }
        
        .login-brand {
            flex: 1;
            position: relative;
            background-image: url('https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=2070');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-brand::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(5, 150, 105, 0.5) 0%, rgba(4, 120, 87, 0.6) 100%);
        }
        
        .login-brand-content {
            position: relative;
            z-index: 1;
            color: white;
            text-align: center;
            padding: 40px;
            max-width: 500px;
        }
        
        .login-brand-content .brand-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-brand-content .brand-icon i {
            font-size: 2.5rem;
        }
        
        .login-brand-content h1 {
            font-size: 2.75rem;
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }
        
        .login-brand-content p {
            font-size: 1.125rem;
            opacity: 0.95;
            line-height: 1.7;
        }
        
        .login-form-wrapper {
            flex: 1;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }
        
        .login-form-container {
            width: 100%;
            max-width: 420px;
        }
        
        .login-header {
            margin-bottom: 40px;
        }
        
        .login-header h2 {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            letter-spacing: -0.01em;
        }
        
        .login-header p {
            color: #64748b;
            font-size: 1rem;
        }
        
        .form-label {
            font-weight: 500;
            color: #334155;
            margin-bottom: 8px;
            font-size: 0.875rem;
        }
        
        .form-control {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.15s;
            height: auto;
        }
        
        .form-control:focus {
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.08);
            outline: none;
        }
        
        .form-control::placeholder {
            color: #cbd5e1;
        }
        
        .btn-primary {
            background: #059669;
            border: none;
            border-radius: 8px;
            padding: 14px 24px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s;
            height: auto;
        }
        
        .btn-primary:hover {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .btn-block {
            width: 100%;
        }
        
        .form-check-label {
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .form-check-input:checked {
            background-color: #059669;
            border-color: #059669;
        }
        
        .alert {
            border: none;
            border-radius: 10px;
            padding: 14px 18px;
            font-size: 0.9375rem;
        }
        
        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #dc2626;
        }
        
        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border-left: 4px solid #16a34a;
        }
        
        .login-footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #f1f5f9;
            text-align: center;
        }
        
        .login-footer p {
            color: #64748b;
            font-size: 0.875rem;
            line-height: 1.6;
        }
        
        .login-footer strong {
            color: #059669;
            font-weight: 600;
        }
        
        @media (max-width: 992px) {
            .login-wrapper {
                flex-direction: column;
            }
            
            .login-brand {
                min-height: 40vh;
            }
            
            .login-brand-content h1 {
                font-size: 2rem;
            }
            
            .login-form-wrapper {
                padding: 40px 30px;
            }
        }
        
        @media (max-width: 576px) {
            .login-form-wrapper {
                padding: 30px 20px;
            }
            
            .login-header h2 {
                font-size: 1.5rem;
            }
            
            .login-brand {
                min-height: 30vh;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @yield('content')

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
