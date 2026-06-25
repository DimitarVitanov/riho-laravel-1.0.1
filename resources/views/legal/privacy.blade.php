@extends('layouts.authentication.master')

@section('css')
    <style>
        .legal-page { max-width: 900px; margin: 40px auto; padding: 30px; background: #fff; border-radius: 8px; box-shadow: 0 0 20px rgba(0,0,0,0.05); }
        .legal-page h1 { font-size: 28px; margin-bottom: 24px; }
        .legal-page h2 { font-size: 20px; margin-top: 24px; margin-bottom: 12px; }
        .legal-page p { line-height: 1.7; margin-bottom: 16px; }
        .legal-back { margin-top: 24px; }
    </style>
@endsection

@section('main_content')
    <div class="container">
        <div class="legal-page">
            <h1>Privacy Policy</h1>
            <p>Villa Bit AI Server is committed to protecting your privacy. This policy explains how we collect, use, and safeguard your personal information.</p>
            <h2>1. Information We Collect</h2>
            <p>We collect information you provide during registration, such as your name, email address, company name, and phone number. We also collect usage data related to your account.</p>
            <h2>2. How We Use Your Information</h2>
            <p>We use your information to provide and improve our services, process payments, communicate with you, and ensure the security of your account.</p>
            <h2>3. Data Sharing</h2>
            <p>We do not sell your personal information. We may share data with trusted service providers who help us operate the service, such as payment processors and hosting providers.</p>
            <h2>4. Cookies and Tracking</h2>
            <p>We may use cookies and similar technologies to enhance your experience and analyze usage. You can control cookie settings through your browser.</p>
            <h2>5. Data Security</h2>
            <p>We implement reasonable security measures to protect your information. However, no internet transmission is completely secure.</p>
            <h2>6. Your Rights</h2>
            <p>You may request access to, correction of, or deletion of your personal information by contacting us.</p>
            <h2>7. Changes to This Policy</h2>
            <p>We may update this privacy policy from time to time. Continued use of the service after changes constitutes acceptance of the updated policy.</p>
            <div class="legal-back">
                <a href="{{ url('/register') }}">&larr; Back to Registration</a>
            </div>
        </div>
    </div>
@endsection
