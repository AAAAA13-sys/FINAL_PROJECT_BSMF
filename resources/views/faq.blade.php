@extends('layouts.app')

@section('title', 'FAQ - BSMF Garage Support')

@section('content')
<div class="faq-container py-5 mt-5">
    <div class="container">
        <div class="mb-4 fade-in">
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm rounded-pill px-4" style="font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; border-color: rgba(255,255,255,0.2);">
                <i class="fas fa-arrow-left me-2"></i> BACK TO GARAGE
            </a>
        </div>
        <div class="text-center mb-5 fade-in">
            <h1 class="display-4 fw-black text-white italic text-uppercase">FREQUENTLY ASKED <span style="color: var(--color-brand-red);">QUESTIONS</span></h1>
            <p class="text-white-50 ls-2 text-uppercase smaller">Everything you need to know about the BSMF Collector Experience</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="faq-grid">
                    {{-- CATEGORY: GENERAL --}}
                    <div class="faq-section mb-5">
                        <h3 class="faq-category-title"><i class="fas fa-garage me-2"></i> GENERAL GARAGE INFO</h3>
                        <div class="accordion accordion-flush bg-transparent" id="faqGeneral">
                            <div class="accordion-item bg-transparent border-white border-opacity-10">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-transparent text-white fw-bold py-4" type="button" data-bs-toggle="collapse" data-bs-target="#q1">
                                        What is BSMF Garage?
                                    </button>
                                </h2>
                                <div id="q1" class="accordion-collapse collapse" data-bs-parent="#faqGeneral">
                                    <div class="accordion-body text-white-50 lh-lg">
                                        BSMF Garage is a premium destination for die-cast collectors. We specialize in rare, featured, and high-demand models from brands like Hot Wheels, Matchbox, and more. Our mission is to provide collectors with a seamless, high-integrity acquisition experience.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item bg-transparent border-white border-opacity-10">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-transparent text-white fw-bold py-4" type="button" data-bs-toggle="collapse" data-bs-target="#q2">
                                        Are the products brand new?
                                    </button>
                                </h2>
                                <div id="q2" class="accordion-collapse collapse" data-bs-parent="#faqGeneral">
                                    <div class="accordion-body text-white-50 lh-lg">
                                        Yes! Unless explicitly marked as "LOOSE" in the description, all our models are in their original factory packaging. We maintain a strict "MINT ON CARD" standard for our inventory.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CATEGORY: ORDERS & SHIPPING --}}
                    <div class="faq-section mb-5">
                        <h3 class="faq-category-title"><i class="fas fa-shipping-fast me-2"></i> ORDERS & LOGISTICS</h3>
                        <div class="accordion accordion-flush bg-transparent" id="faqShipping">
                            <div class="accordion-item bg-transparent border-white border-opacity-10">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-transparent text-white fw-bold py-4" type="button" data-bs-toggle="collapse" data-bs-target="#q3">
                                        How long does shipping take?
                                    </button>
                                </h2>
                                <div id="q3" class="accordion-collapse collapse" data-bs-parent="#faqShipping">
                                    <div class="accordion-body text-white-50 lh-lg">
                                        We typically process orders within 24-48 hours. Depending on your location, shipping usually takes 3-7 business days for domestic orders. Tracking numbers are provided as soon as the item leaves the garage.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item bg-transparent border-white border-opacity-10">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-transparent text-white fw-bold py-4" type="button" data-bs-toggle="collapse" data-bs-target="#q4">
                                        Do you offer international shipping?
                                    </button>
                                </h2>
                                <div id="q4" class="accordion-collapse collapse" data-bs-parent="#faqShipping">
                                    <div class="accordion-body text-white-50 lh-lg">
                                        Currently, we are focusing on our local collector community. Please contact us via our Facebook page for special requests regarding international shipping.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item bg-transparent border-white border-opacity-10">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-transparent text-white fw-bold py-4" type="button" data-bs-toggle="collapse" data-bs-target="#q5">
                                        What is "Extra Care Packaging"?
                                    </button>
                                </h2>
                                <div id="q5" class="accordion-collapse collapse" data-bs-parent="#faqShipping">
                                    <div class="accordion-body text-white-50 lh-lg">
                                        As collectors ourselves, we know how important the card condition is. If you select "Extra Care Packaging" during checkout, we add double bubble wrap and rigid card protectors to ensure your model arrives in pristine condition.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CATEGORY: RETURNS & EXCHANGES --}}
                    <div class="faq-section mb-5">
                        <h3 class="faq-category-title"><i class="fas fa-undo-alt me-2"></i> RETURNS & INTEGRITY</h3>
                        <div class="accordion accordion-flush bg-transparent" id="faqReturns">
                            <div class="accordion-item bg-transparent border-white border-opacity-10">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-transparent text-white fw-bold py-4" type="button" data-bs-toggle="collapse" data-bs-target="#q6">
                                        Can I return a model if I change my mind?
                                    </button>
                                </h2>
                                <div id="q6" class="accordion-collapse collapse" data-bs-parent="#faqReturns">
                                    <div class="accordion-body text-white-50 lh-lg">
                                        Due to the high-demand and collectible nature of our products, all sales are final. However, if your item arrives damaged or is not what you ordered, please contact us within 24 hours of delivery.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.faq-container {
    background: #050608; /* DEEPER DARK */
    min-height: 80vh;
    position: relative;
    border-radius: 50px;
    margin: 2rem auto; /* Centered with auto margins */
    width: calc(100% - 4rem); /* Takes full width minus margins */
    max-width: 1400px; /* Prevents it from getting too wide on ultrawide monitors */
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.05);
}
.faq-container::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: radial-gradient(circle at 50% 0%, rgba(230, 57, 70, 0.05) 0%, transparent 70%);
    pointer-events: none;
}
.faq-grid {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 40px;
    padding: 4rem;
    backdrop-filter: blur(20px);
    box-shadow: 0 40px 100px rgba(0, 0, 0, 0.4);
}
.faq-category-title {
    color: var(--color-brand-red);
    font-size: 0.9rem;
    font-weight: 900;
    letter-spacing: 3px;
    text-transform: uppercase;
    margin-bottom: 1.5rem;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05); /* DIMMER BORDER */
}
.accordion-item {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(255, 255, 255, 0.03) !important;
}
.accordion-item:hover {
    background: rgba(255, 255, 255, 0.01) !important;
}
.accordion-button:not(.collapsed) {
    color: var(--color-brand-red);
    background: rgba(230, 57, 70, 0.02);
    box-shadow: none;
}
.accordion-button::after {
    filter: invert(1);
    opacity: 0.5;
}
.accordion-button:focus {
    box-shadow: none;
}
.fw-black { font-weight: 900; }
.ls-2 { letter-spacing: 2px; }
.italic { font-style: italic; }

@media (max-width: 768px) {
    .faq-grid {
        padding: 2rem 1.5rem;
        border-radius: 24px;
    }
}
</style>
@endsection
