<div class="container shortcode-request-quote">
    <div class="row">
        <div class="card">
            {!! \Botble\Portfolio\Forms\Fronts\QuotationForm::create()
                ->setFormInputClass('form-control quotation-form-input')
                ->setFormInputWrapperClass('form-group mb-3')
                ->renderForm()
            !!}
        </div>
    </div>
</div>
