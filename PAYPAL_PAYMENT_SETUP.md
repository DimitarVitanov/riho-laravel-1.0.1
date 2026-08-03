# EST8ADS PayPal Payment Setup

## How It Works

The EST8ADS billing system uses a **simple PayPal redirect** - the same system as VillaBit AI.

When a user clicks "Extend another 30 days" button, they are redirected to a PayPal payment link.

## How to Update the PayPal Link

### File to Edit:
```
app/Http/Controllers/Est8ads/PaymentController.php
```

### Line to Change:
```php
// Line 17 - Replace this URL with your actual PayPal link
$paypalLink = 'https://www.paypal.com/paypalme/yourusername/12.00';
```

### Example PayPal Links:

**PayPal.Me Link:**
```php
$paypalLink = 'https://www.paypal.com/paypalme/yourusername/12.00';
```

**PayPal Button/Invoice Link:**
```php
$paypalLink = 'https://www.paypal.com/invoice/p/#INVOICEID';
```

**PayPal Subscription Link:**
```php
$paypalLink = 'https://www.paypal.com/webapps/billing/plans/subscribe?plan_id=YOUR_PLAN_ID';
```

## Payment Flow

1. **User clicks "Extend another 30 days"**
   - Button is disabled if status is "pending"
   - Button is active if status is "active"

2. **Redirect to PayPal**
   - User is sent to: `est8ads.com/payment/paypal`
   - Controller redirects to PayPal link

3. **User completes payment on PayPal**

4. **PayPal redirects back** (configure in PayPal):
   - Success: `est8ads.com/payment/success`
   - Cancelled: `est8ads.com/payment/cancelled`

5. **Admin manually approves**
   - Payment shows as "PENDING APPROVAL"
   - Admin checks payment in VillaBit AI admin panel
   - Admin changes status to "ACTIVE"

## Routes

- **Payment redirect**: `GET /payment/paypal`
- **Success callback**: `GET /payment/success`
- **Cancelled callback**: `GET /payment/cancelled`

## Important Notes

- ✅ Same simple system as VillaBit AI
- ✅ Easy to update - just change one line in PaymentController.php
- ✅ No complex payment gateway integration needed
- ✅ Goran will send the correct PayPal link later today

## Testing

To test the payment flow:

1. Go to EST8ADS dashboard
2. Click "Billing" in sidebar
3. Click "Extend another 30 days" button
4. You will be redirected to PayPal
5. Complete payment
6. Return to dashboard

The subscription will show "⏳ PENDING APPROVAL" until admin approves it in VillaBit AI admin panel.
