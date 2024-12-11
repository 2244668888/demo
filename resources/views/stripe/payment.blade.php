<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment Gateway Integration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table">
                    <h3 class="panel-title">Payment Details</h3>
                </div>
                <div class="panel-body">
                    @if (session()->has('success'))
                        <div class="alert alert-success text-center">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger text-center">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('stripe.post') }}" method="POST" id="payment-form">
                        @csrf
                        <div class="form-group">
                            <label for="card-holder-name">Name on Card</label>
                            <input id="card-holder-name" class="form-control" type="text" placeholder="Card Holder Name" required>
                        </div>

                        <div class="form-group">
                            <label for="card-element">Card Number</label>
                            <div id="card-element">
                                <!-- Stripe Card Element will be mounted here -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="expiration-date">Expiration Date (MM/YY)</label>
                            <input type="text" id="expiration-date" class="form-control" placeholder="MM/YY" maxlength="5" required>
                        </div>

                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" class="form-control" placeholder="CVV" maxlength="4" required>
                        </div>

                        <div id="card-errors" role="alert" class="text-danger"></div>

                        <button id="card-button" class="btn btn-primary btn-lg btn-block" type="submit" data-secret="{{ $clientSecret }}">
                            Pay Now ($100)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const stripe = Stripe('{{ env("STRIPE_KEY") }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');  // Mount Stripe Card Element

    const form = document.getElementById('payment-form');
    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;  // Retrieve the clientSecret passed from the backend

    cardButton.addEventListener('click', async (e) => {
        e.preventDefault();

        const { setupIntent, error } = await stripe.confirmCardSetup(clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: cardHolderName.value
                }
            }
        });

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
        } else {
            // Handle successful setup
            const paymentInput = document.createElement('input');
            paymentInput.type = 'hidden';
            paymentInput.name = 'paymentMethodId';
            paymentInput.value = setupIntent.payment_method;
            form.appendChild(paymentInput);
            form.submit();
        }
    });
</script>
</body>
</html>
