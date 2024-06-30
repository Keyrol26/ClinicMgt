{{-- <div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Appointment Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-6">
                    <label class="col-lg-6 col-form-label fw-bold fs-4">
                        <span class="required">Total Amount : RM50.00</span>
                    </label>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-6 col-form-label fw-bold fs-6">
                        <span class="required">Credit or Debit card</span>
                    </label>
                    <div id="card-element" class="form-control">
                        <!-- A Stripe Element will be inserted here. -->
                    </div>
                    <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">Close</button>
                <button type="button" name="submit" id="submit-button" class="btn btn-primary font-weight-bold">Submit
                    Payment</button>
            </div>
        </div>
    </div>
</div>
@if (session('success_message'))
    <div class="alert alert-success mt-3">
        {{ session('success_message') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger mt-3">
        {{ $errors->first() }}
    </div>
@endif
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe(
        'pk_test_51PWszrHGh3p3OmlRiVGYwCkjZ5hW14sBIRoXn2f4xdYzIdQ61LBJwR9vxP24ndZdzFUSdxkMJvEfT9vZsOjBmI5a00LcmBp1tn'
    );
    var elements = stripe.elements();

    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    var cardElement = elements.create('card', {
        style: style
    });
    cardElement.mount('#card-element');

    cardElement.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    var form = document.getElementById('submit-button');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(cardElement).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                stripeTokenHandler(result.token);
            }
        });
    });

    function stripeTokenHandler(token) {
        var form = document.getElementById('booking-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        form.submit();
    }
</script> --}}
<div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Appointment Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-6">
                    <label class="col-lg-6 col-form-label fw-bold fs-4">
                        <span class="required">Total Amount : RM50.00</span>
                    </label>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-6 col-form-label fw-bold fs-6">
                        <span class="required">Credit or Debit card</span>
                    </label>
                    <div id="card-element" class="form-control">
                        <!-- A Stripe Element will be inserted here. -->
                    </div>
                    <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" id="submit-button" class="btn btn-primary font-weight-bold">Submit Payment</button>
            </div>
        </div>
    </div>
</div>
@if (session('success_message'))
    <div class="alert alert-success mt-3">
        {{ session('success_message') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger mt-3">
        {{ $errors->first() }}
    </div>
@endif
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('pk_test_51PWszrHGh3p3OmlRiVGYwCkjZ5hW14sBIRoXn2f4xdYzIdQ61LBJwR9vxP24ndZdzFUSdxkMJvEfT9vZsOjBmI5a00LcmBp1tn');
    var elements = stripe.elements();

    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    var cardElement = elements.create('card', {
        style: style
    });
    cardElement.mount('#card-element');

    cardElement.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    document.getElementById('submit-button').addEventListener('click', function(event) {
        event.preventDefault();

        stripe.createToken(cardElement).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                stripeTokenHandler(result.token);
            }
        });
    });

    function stripeTokenHandler(token) {
        var bookingForm = document.getElementById('booking-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        bookingForm.appendChild(hiddenInput);

        bookingForm.submit(); // Changed form to bookingForm
    }
</script>
