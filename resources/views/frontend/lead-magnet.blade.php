<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get in Touch - {{ $agencyProfile->company_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .lead-form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 16px;
            border: 1px solid #e0e0e0;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 14px 28px;
            font-weight: 600;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .agency-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="lead-form-card">
        <div class="text-center">
            @if($agencyProfile->logo)
                <img src="{{ asset($agencyProfile->logo) }}" alt="{{ $agencyProfile->company_name }}" class="agency-logo">
            @else
                <div class="agency-logo bg-light d-flex align-items-center justify-content-center mx-auto">
                    <i class="fa fa-building fa-2x text-muted"></i>
                </div>
            @endif
            <h3 class="mb-2">{{ $agencyProfile->company_name }}</h3>
            <p class="text-muted mb-4">Get exclusive property opportunities delivered to your inbox</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('lead-magnet.store', $agencyProfile->id) }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">First Name *</label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Name *</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Country</label>
                <select name="country" class="form-select">
                    <option value="">Select country...</option>
                    <option value="Croatia" {{ old('country') == 'Croatia' ? 'selected' : '' }}>Croatia</option>
                    <option value="Germany" {{ old('country') == 'Germany' ? 'selected' : '' }}>Germany</option>
                    <option value="Austria" {{ old('country') == 'Austria' ? 'selected' : '' }}>Austria</option>
                    <option value="Switzerland" {{ old('country') == 'Switzerland' ? 'selected' : '' }}>Switzerland</option>
                    <option value="Slovenia" {{ old('country') == 'Slovenia' ? 'selected' : '' }}>Slovenia</option>
                    <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">I am a...</label>
                <select name="investor_type" class="form-select">
                    <option value="">Select type...</option>
                    <option value="cash_buyer" {{ old('investor_type') == 'cash_buyer' ? 'selected' : '' }}>Cash Buyer</option>
                    <option value="mortgage_buyer" {{ old('investor_type') == 'mortgage_buyer' ? 'selected' : '' }}>Mortgage Buyer</option>
                    <option value="investor" {{ old('investor_type') == 'investor' ? 'selected' : '' }}>Investor</option>
                    <option value="other" {{ old('investor_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Investment Budget (€)</label>
                <input type="number" name="interest_amount" class="form-control" value="{{ old('interest_amount') }}" placeholder="e.g. 500000">
            </div>

            <div class="mb-4">
                <label class="form-label">Message</label>
                <textarea name="message" class="form-control" rows="3" placeholder="Tell us what you're looking for...">{{ old('message') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fa fa-paper-plane me-2"></i>Send Request
            </button>

            <p class="text-center text-muted small mt-3 mb-0">
                <i class="fa fa-lock me-1"></i>Your information is secure and will never be shared.
            </p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
