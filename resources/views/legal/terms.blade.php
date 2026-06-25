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
            <h1>Terms of Service</h1>
            <p>Please read these terms of service carefully before using Villa Bit AI Server.</p>
            <h2>1. Acceptance of Terms</h2>
            <p>By accessing or using our service, you agree to be bound by these terms. If you do not agree to these terms, please do not use the service.</p>
            <h2>2. Description of Service</h2>
            <p>Villa Bit AI Server provides AI-powered tools and services for real estate agencies and investors. Features, pricing, and availability may change without notice.</p>
            <h2>3. Account Registration</h2>
            <p>You must provide accurate and complete information when creating an account. You are responsible for maintaining the confidentiality of your account credentials.</p>
            <h2>4. Payment and Subscription</h2>
            <p>Subscription fees are billed in advance according to the plan selected. Payments are processed securely through PayPal. You may cancel or change your plan at any time.</p>
            <h2>5. Limitation of Liability</h2>
            <p>Villa Bit AI Server is provided "as is" without warranties of any kind. We are not liable for any damages arising from the use or inability to use the service.</p>
            <h2>6. Changes to Terms</h2>
            <p>We may update these terms from time to time. Continued use of the service after changes constitutes acceptance of the updated terms.</p>
            <div class="legal-back">
                <a href="{{ url('/register') }}">&larr; Back to Registration</a>
            </div>
        </div>
    </div>
@endsection
